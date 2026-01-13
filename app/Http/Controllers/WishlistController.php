<?php

namespace App\Http\Controllers;

use App\Http\Resources\V2\Seller\ProductResource;
use Illuminate\Http\Request;
use Auth;
use App\Models\Wishlist;

class WishlistController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $wishlists = get_wishlists()->paginate(15);
    return view('frontend.user.view_wishlist', compact('wishlists'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if (Auth::check()) {
      $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
      if ($wishlist == null) {
        $wishlist = new Wishlist;
        $wishlist->user_id = Auth::user()->id;
        $wishlist->product_id = $request->id;
        $wishlist->save();
      }
      return view('frontend.partials.wishlist');
    }
    return 0;
  }

  public function remove(Wishlist $wishlist)
  {
    $wishlist->delete();
    return redirect()->back();
  }


  public function wishlistHandling(Request $request)
  {
    // Fetch wishlists with related products
    $wishlists = Wishlist::where('user_id', Auth::id())
      ->with('product')
      ->get();

    // Extract products from wishlists
    $products = $wishlists->pluck('product');

    // Format using ProductResource
    $wishlistItems = ProductResource::collection($products)->toArray($request);

    foreach ($wishlistItems as $index => $item) {
      $wishlistItems[$index]['wishlist_id'] = $wishlists[$index]->id;
    }

    return $wishlistItems;
  }

  public function wishlistIndex(Request $request)
  {

    $wishlistItems = $this->wishlistHandling($request);

    return inertia('Wishlist/Wishlist', [
      'wishlistItems' => $wishlistItems,
    ]);
  }

  public function DashboardWishlistIndex(Request $request)
  {

    $wishlistItems = $this->wishlistHandling($request);

    return inertia('UserDashboard/Wishlist/Wishlist', [
      'wishlistItems' => $wishlistItems,
    ]);
  }


  public function wishlistToggle(Request $request)
  {
    if (!Auth::check()) {
      return redirect()->back();
    }

    // Validate input
    $validated = $request->validate([
      'product_id' => 'required|exists:products,id',
      'new_is_favorite' => 'required|boolean',
    ]);


    $productId = $validated['product_id'];
    $userId = Auth::id();

    // Check if product already exists in wishlist
    $wishlist = Wishlist::where('user_id', $userId)
      ->where('product_id', $productId)
      ->first();

    // If exists, remove it; otherwise, add it
    if ($wishlist) {
      $wishlist->delete();
    } else {
      Wishlist::create([
        'user_id' => $userId,
        'product_id' => $productId,
      ]);
    }

    return redirect()->back();
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
