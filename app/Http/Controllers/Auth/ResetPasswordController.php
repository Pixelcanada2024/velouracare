<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

  use ResetsPasswords;

  /**
   * Where to redirect users after resetting their password.
   *
   * @var string
   */
  //protected $redirectTo = '/';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest');
  }


  /**
   * Live verification endpoint for real-time code validation
   * Returns JSON for AJAX calls
   */
  public function verifyCodeLive(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'code' => 'required|string|size:6',
    ]);

    $user = User::where('email', $request->email)
      ->where('verification_code', $request->code)
      ->first();

    if (!$user) {
      $message = app()->getLocale() == 'ar'
        ? 'رمز التحقق غير صالح أو منتهي الصلاحية.'
        : 'The verification code is invalid or has expired.';

      return response()->json([
        'success' => false,
        'message' => $message
      ], 422);
    }

    $message = app()->getLocale() == 'ar'
      ? 'تم التحقق من الرمز بنجاح.'
      : 'Code verified successfully.';

    return response()->json([
      'success' => true,
      'message' => $message
    ]);
  }


  public function reset(Request $request)
  {
    $request->validate([
      'code' => 'required|string',
      'email' => 'required|email',
      'password' => 'required|string|min:6|confirmed',
    ]);

    $user = User::where('email', $request->email)
      ->where('verification_code', $request->code)
      ->first();

    if (!$user) {
      return back()->with('title', 'Invalid Code')
        ->with('message', 'The verification code is invalid or has expired.')
        ->with('status', 'error');
    }

    // Update the password
    $user->password = Hash::make($request->password);
    $user->verification_code = null;
    $user->save();

    if (app()->getLocale() == 'ar') {
      $title = 'تم تحديث كلمة المرور!';
      $message = 'تم تحديث كلمة المرور بنجاح، يمكنك الآن استخدام كلمة المرور الجديدة لتسجيل الدخول إلى حسابك.';
    } else {
      $title = 'Password Updated!';
      $message = 'Your password has been updated successfully, You can now use your new password to login to your account.';
    }

    return redirect('login')
      ->with('title', $title)
      ->with('message', $message)
      ->with('status', 'success');
  }
}
