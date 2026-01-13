<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Upload;
use App\Models\Country;
use Auth;
use Illuminate\Http\Request;

class UserDashboardService
{

  public function userDashboard(Request $request)
  {
    $user = Auth::user();

    $businessInfo = $user->businessInfo()->with('country')->first();

    $businessProofAssets = [];

    if ($businessInfo && $businessInfo->business_proof_assets) {
      $assetsArray = json_decode($businessInfo->business_proof_assets, true);

      foreach ($assetsArray as $assetPath) {
        $businessProofAssets[] = [
          'url' => asset("/public/storage/" . $assetPath),
          'name' => basename($assetPath),
          'path' => $assetPath
        ];
      }
    }

    // Get user addresses
    $addresses = $user->addresses()->with('country')->get();

    // Get addresses available for shipping (not assigned to billing)
    $availableShippingAddresses = $user->addresses()
      ->with('country')
      ->where(function ($query) {
        $query->whereNull('address_type')
          ->orWhere('address_type', 'shipping');
      })
      ->get();

    // Get addresses available for billing (not assigned to shipping)
    $availableBillingAddresses = $user->addresses()
      ->with('country')
      ->where(function ($query) {
        $query->whereNull('address_type')
          ->orWhere('address_type', 'billing');
      })
      ->get();

    $shippingAddress = $user->addresses()->whereIn('address_type', ['shipping'])->with('country')->first();
    $billingAddress = $user->addresses()->whereIn('address_type', ['billing'])->with('country')->first();
    $countries = Country::orderBy('name')->get();

    return inertia('UserDashboard/Dashboard', [
      'businessInfo' => $businessInfo,
      'businessProofAssets' => $businessProofAssets,
      'availableShippingAddresses' => $availableShippingAddresses,
      'availableBillingAddresses' => $availableBillingAddresses,
      'shippingAddress' => $shippingAddress,
      'billingAddress' => $billingAddress,
      'countries' => $countries,
    ]);
  }


  public function StoreAddress(Request $request)
  {
    // Validate the request
    $validated = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'address_line_one' => 'required|string|max:255',
      'address_line_two' => 'nullable|string|max:255',
      'country_id' => 'required|exists:countries,id',
      'state' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'postal_code' => 'string|nullable',
      'phone' => 'required|string|max:20',
      'email' => 'required|email|max:255',
      'address_type' => 'nullable|in:shipping,billing',
    ]);

    $user = Auth::user();
    // Set address type and user ID
    $validated['address'] = $validated['address_line_one'] . " " . $validated['address_line_two'];
    $validated['user_id'] = $user->id;

    // Create the address
    Address::Create($validated);

    if (app()->getLocale() == 'ar') {
      $title = 'تم إنشاء العنوان بنجاح!';
      $message = 'تم إنشاء عنوانك بنجاح.';
    } else {
      $title = 'Address Created Successfully!';
      $message = 'Your address has been successfully created.';
    }

    return back()
      ->with('title', $title)
      ->with('message', $message);
  }

  public function UpdateAddress(Request $request, Address $address)
  {
    // Check if the address belongs to the current user
    $user = Auth::user();
    if ($address->user_id !== $user->id) {
      return back()->withErrors(['error' => 'You do not have permission to update this address.']);
    }

    // Validate the request
    $validated = $request->validate([
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'address_line_one' => 'required|string|max:255',
      'address_line_two' => 'nullable|string|max:255',
      'country_id' => 'required|exists:countries,id',
      'state' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'postal_code' => 'string|nullable',
      'phone' => 'required|string|max:20',
      'email' => 'required|email|max:255',
    ]);

    // Set address and user ID
    $validated['address'] = $validated['address_line_one'] . " " . $validated['address_line_two'];
    $validated['user_id'] = $user->id;

    // Update the address
    $address->update($validated);

    if (app()->getLocale() == 'ar') {
      $title = 'تم تحديث العنوان بنجاح!';
      $message = 'تم تحديث عنوانك بنجاح.';
    } else {
      $title = 'Address Updated Successfully!';
      $message = 'Your address has been successfully updated.';
    }

    return back()
      ->with('title', $title)
      ->with('message', $message);

  }


  public function UpdateAddressType(Request $request, Address $address)
  {
    $user = Auth::user();

    // Check permission
    if ($address->id && $address->user_id !== $user->id) {
      return back()->withErrors(['error' => 'Permission denied']);
    }

    $validated = $request->validate([
      'address_type' => 'required|in:shipping,billing',
      'is_same_billing' => 'nullable|boolean',
    ]);

    $currentAddress = Address::where('user_id', $user->id)
      ->where('address_type', $validated['address_type'])->first();

    // Clear existing address type
    $currentAddress?->update(['address_type' => null]);

    // Handle "Same as Billing" case
    if ($validated['address_type'] === 'shipping' && $validated['is_same_billing']) {
      $billingAddress = Address::where('user_id', $user->id)
        ->where('address_type', 'billing')
        ->first();

      // Check if identical shipping address already exists
      $sameBillingAddress = Address::where([
        ['user_id', $billingAddress->user_id],
        ['first_name', $billingAddress->first_name],
        ['last_name', $billingAddress->last_name],
        ['address_line_one', $billingAddress->address_line_one],
        ['address_line_two', $billingAddress->address_line_two],
        ['city', $billingAddress->city],
        ['phone', $billingAddress->phone],
        ['state', $billingAddress->state],
        ['postal_code', $billingAddress->postal_code],
        ['country_id', $billingAddress->country_id],
      ])
        ->where(function ($query) {
          $query->where('address_type', '!=', 'billing')
            ->orWhereNull('address_type');
        })->first();

      if (!!$sameBillingAddress) {
        // Just update the type if identical address exists
        $sameBillingAddress->update(['address_type' => 'shipping']);
      } else {
        // Create new copy only if no identical address exists
        $shippingAddress = $billingAddress->replicate();
        $shippingAddress->address_type = 'shipping';
        $shippingAddress->save();
      }

      if (app()->getLocale() == 'ar') {
        $title = 'تم تحديث العنوان بنجاح!';
        $message = 'تم تعيين عنوان التسليم مثل عنوان الفاتورة.';
      } else {
        $title = 'Address Updated Successfully!';
        $message = 'Delivery address set same as invoice.';
      }

      return back()
        ->with('title', $title)
        ->with('message', $message);

    } else {
      // Normal case - update selected address
      $address->update(['address_type' => $validated['address_type']]);

      if ($validated['address_type'] === 'shipping') {
        $typeName = app()->getLocale() == 'ar' ? 'التسليم' : 'Delivery';
      } else {
        $typeName = app()->getLocale() == 'ar' ? 'الفاتورة' : 'Invoice';
      }

      if (app()->getLocale() == 'ar') {
        $title = 'تم تحديث العنوان بنجاح!';
        $message = "تم تحديث عنوان $typeName";
      } else {
        $title = 'Address Updated Successfully!';
        $message = "$typeName address updated";
      }

      return back()
        ->with('title', $title)
        ->with('message', $message);


    }

  }

  public function DeleteAddress(Address $address)
  {
    $user = Auth::user();

    // Check if the address belongs to the current user
    if ($address->user_id !== $user->id) {
      return back()->withErrors(['error' => 'You do not have permission to delete this address.']);
    }

    // Delete the address
    $address->delete();

    if (app()->getLocale() == 'ar') {
      $title = 'تم حذف العنوان بنجاح!';
      $message = 'تم حذف عنوانك بنجاح.';
    } else {
      $title = 'Address Deleted Successfully!';
      $message = 'Your address has been successfully deleted.';
    }

    return back()
      ->with('title', $title)
      ->with('message', $message);
  }
}
