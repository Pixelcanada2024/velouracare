<?php

namespace App\Http\Controllers\Auth;

use Cookie;
use Session;
use App\Models\Cart;
use App\Models\User;
use App\Models\Country;
use App\Rules\Recaptcha;
use App\Models\BusinessInfo;
use Illuminate\Http\Request;
use App\Utility\EmailUtility;
use App\Models\BusinessSetting;
use Illuminate\Validation\Rule;
use App\Jobs\SendRegisterEmailsJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\OTPVerificationController;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showRegistrationForm()
    {
      // USA 231
      $country = Country::all();

      $data = [
          'countryData' => $country->map(function ($item) {
              return [
                  'label' => $item->name,
                  'value' => $item->id,
                  'code'=>  $item->code,
              ];
          })
      ];
        return inertia('Auth/Register',$data);
    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

  protected function validator(array $data)
  {
    // dd($data);
      $rules = [
          // Personal Information
          'name' => 'required|string|max:255',
          'email' => 'required|email|unique:users,email|max:255',
          'phone' => 'required|string|max:20',
          'password' => 'required|string|min:6',

          'address_line_one' => 'nullable|string|max:255',
          'address_line_two' => 'nullable|string|max:255',
          'country_id' => 'required|exists:countries,id',
          'city' => 'required|string|max:100',
          'postal_code' => 'nullable|string|max:20',
          'agree' => 'required|accepted',

          // Business Information
          'company_name' => 'required|string|max:255',
          'store_link' => 'required|url|max:255',
          'business_id' => 'required|string|max:255',
          'business_proof_assets' => 'required|array|min:1|max:6',
          'business_proof_assets.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:10240', // 10MB max per file
          'business_type' => 'required|string|in:0,1,2',
          'find_us' => 'nullable|integer|in:0,1,2,3,4',
          'wholesale_agree' => 'required|accepted',

          'g-recaptcha-response' => Rule::when(
              get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1,
              ['required', new Recaptcha()],
              ['sometimes']
          ),
      ];

      if ( config('app.sky_type') == 'America' ) {
          $rules['state'] = 'nullable|string|max:100';
      }

      return Validator::make($data, $rules, [
          'name.required' => customTrans('validator_name_required'),
          'name.string' => customTrans('validator_name_string'),
          'name.max' => customTrans('validator_name_max'),
          'email.required' => customTrans('validator_email_required'),
          'email.email' => customTrans('validator_email_email'),
          'email.unique' => customTrans('validator_email_unique'),
          'email.max' => customTrans('validator_email_max'),
          'phone.required' => customTrans('validator_phone_required'),
          'phone.string' => customTrans('validator_phone_string'),
          'phone.max' => customTrans('validator_phone_max'),
          'password.required' => customTrans('validator_password_required'),
          'password.string' => customTrans('validator_password_string'),
          'password.min' => customTrans('validator_password_min'),
          'address_line_one.string' => customTrans('validator_address_line_one_string'),
          'address_line_one.max' => customTrans('validator_address_line_one_max'),
          'address_line_two.string' => customTrans('validator_address_line_two_string'),
          'address_line_two.max' => customTrans('validator_address_line_two_max'),
          'country_id.required' => customTrans('validator_country_id_required'),
          'country_id.exists' => customTrans('validator_country_id_exists'),
          'city.required' => customTrans('validator_city_required'),
          'city.string' => customTrans('validator_city_string'),
          'city.max' => customTrans('validator_city_max'),
          'postal_code.string' => customTrans('validator_postal_code_string'),
          'postal_code.max' => customTrans('validator_postal_code_max'),
          'agree.required' => customTrans('validator_agree_required'),
          'agree.accepted' => customTrans('validator_agree_accepted'),
          'company_name.required' => customTrans('validator_company_name_required'),
          'company_name.string' => customTrans('validator_company_name_string'),
          'company_name.max' => customTrans('validator_company_name_max'),
          'store_link.required' => customTrans('validator_store_link_required'),
          'store_link.url' => customTrans('validator_store_link_url'),
          'store_link.max' => customTrans('validator_store_link_max'),
          'business_id.required' => customTrans('validator_business_id_required'),
          'business_id.string' => customTrans('validator_business_id_string'),
          'business_id.max' => customTrans('validator_business_id_max'),
          'business_proof_assets.required' => customTrans('validator_business_proof_assets_required'),
          'business_proof_assets.array' => customTrans('validator_business_proof_assets_array'),
          'business_proof_assets.min' => customTrans('validator_business_proof_assets_min'),
          'business_proof_assets.max' => customTrans('validator_business_proof_assets_max'),
          'business_proof_assets.*.file' => customTrans('validator_business_proof_assets_file'),
          'business_proof_assets.*.mimes' => customTrans('validator_business_proof_assets_mimes'),
          'business_proof_assets.*.max' => customTrans('validator_business_proof_assets_max_size'),
          'business_type.required' => customTrans('validator_business_type_required'),
          'business_type.string' => customTrans('validator_business_type_string'),
          'business_type.in' => customTrans('validator_business_type_in'),
          'find_us.integer' => customTrans('validator_find_us_integer'),
          'find_us.in' => customTrans('validator_find_us_in'),
          'wholesale_agree.required' => customTrans('validator_wholesale_agree_required'),
          'wholesale_agree.accepted' => customTrans('validator_wholesale_agree_accepted'),
          'g-recaptcha-response.required' => customTrans('validator_g_recaptcha_response_required'),
          'state.string' => customTrans('validator_state_string'),
          'state.max' => customTrans('validator_state_max'),
      ]);
  }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // dd($data);
        if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
            ]);
        }
        else {
            if (addon_is_activated('otp_system')){
                $user = User::create([
                    'name' => $data['name'],
                    'phone' => '+'.$data['country_code'].$data['phone'],
                    'password' => Hash::make($data['password']),
                    'verification_code' => rand(100000, 999999)
                ]);

                if(get_setting('customer_registration_verify') != '1' ){
                    $otpController = new OTPVerificationController;
                    $otpController->send_code($user);
                }

            }
        }

        if(session('temp_user_id') != null){
            if(auth()->user()->user_type == 'customer'){
                Cart::where('temp_user_id', session('temp_user_id'))
                ->update(
                    [
                        'user_id' => auth()->user()->id,
                        'temp_user_id' => null
                    ]
                );
            }
            else {
                Cart::where('temp_user_id', session('temp_user_id'))->delete();
            }
            Session::forget('temp_user_id');
        }

        if(Cookie::has('referral_code')){
            $referral_code = Cookie::get('referral_code');
            $referred_by_user = User::where('referral_code', $referral_code)->first();
            if($referred_by_user != null){
                $user->referred_by = $referred_by_user->id;
                $user->save();
            }
        }

        return $user;
    }




  protected function createUser($request)
  {
    $data = $request->all();

    // Create user
    $user = User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'phone' => $data['phone'],
      'password' => Hash::make($data['password']),
      'status' => 0,
    ]);

    // File storage path
    $uploadPath = "users/{$user->id}/register_files/";

    // Handle business proof assets files
    $businessProofAssets = [];
    if ($request->hasFile('business_proof_assets')) {
      foreach ($request->file('business_proof_assets') as $file) {
        $businessProofAssets[] = $file->store($uploadPath . "business_proof_assets", 'public');
      }
    }


    // Save business info with new structure
    BusinessInfo::create([
      'user_id' => $user->id,

      // Address information
      'address_line_one' => $data['address_line_one'] ?? null,
      'address_line_two' => $data['address_line_two'] ?? null,
      'country_id' => $data['country_id'] ?? null,
      'state' => $data['state'] ?? null,
      'city' => $data['city'] ?? null,
      'postal_code' => $data['postal_code'] ?? null,

      // Business information
      'company_name' => $data['company_name'] ?? null,
      'store_link' => $data['store_link'] ?? null,
      'business_id' => $data['business_id'] ?? null,
      'business_proof_assets' => json_encode($businessProofAssets),
      'business_type' => $data['business_type'] ?? null,
      'find_us' => $data['find_us'] ?? null,
    ]);

    return $user;
  }


  public function sendingEmailsToAdminAndCustomer($user)
  {
      $user->load('businessInfo');

      $registrationNotificationEmail = get_setting('registration_notification_email') ?? 'info@velouracare.sa' ;

      $info = [
        'phone' => get_setting('contact_phone') ?? '+966 112 860 262',
        'email' => get_setting('contact_email') ?? 'info@velouracare.sa',
        'address' => get_setting('contact_address') ?? 'Kingdom of Saudi Arabia - Riyadh - Al-Malaz - Salah Al-Din Al-Ayyubi Road ',
        'facebook' => get_setting('facebook_link') ?? '#',
        'twitter' => get_setting('twitter_link') ?? '#',
        'instagram' => get_setting('instagram_link') ?? '#',
        'linkedin' => get_setting('linkedin_link') ?? '#',
        'url' => url('/'),
      ];

      SendRegisterEmailsJob::dispatch($user, $info, $registrationNotificationEmail);
  }


    public function register(Request $request)
    {
        // if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        //     if(User::where('email', $request->email)->first() != null){
        //         flash(translate('Email or Phone already exists.'));
        //         return back();
        //     }
        // }
        // elseif (User::where('phone', '+'.$request->country_code.$request->phone)->first() != null) {
        //     flash(translate('Phone already exists.'));
        //     return back();
        // }

        $this->validator($request->all())->validate();

        // $user = $this->create($request->all());
        $user = $this->createUser($request);

        $this->sendingEmailsToAdminAndCustomer($user);

        return back();

        $this->guard()->login($user);

        if($user->email != null){
            if(BusinessSetting::where('type', 'email_verification')->first()->value != 1 || get_setting('customer_registration_verify') === '1'){
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                offerUserWelcomeCoupon();
                flash(translate('Registration successful.'))->success();
            }
            else {
                try {
                    EmailUtility::email_verification($user, 'customer');
                    flash(translate('Registration successful. Please verify your email.'))->success();
                } catch (\Throwable $e) {
                    dd($e);
                    $user->delete();
                    flash(translate('Registration failed. Please try again later.'))->error();
                }
            }

            // Account Opening Email to customer
            if ( $user != null && (get_email_template_data('registration_email_to_customer', 'status') == 1)) {
                try {
                    EmailUtility::customer_registration_email('registration_email_to_customer', $user, null);
                } catch (\Exception $e) {}
            }
        }

        // customer Account Opening Email to Admin
        if ( $user != null && (get_email_template_data('customer_reg_email_to_admin', 'status') == 1)) {
            try {
                EmailUtility::customer_registration_email('customer_reg_email_to_admin', $user, null);
            } catch (\Exception $e) {}
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        if ($user->email == null) {
            return redirect()->route('verification');
        }elseif(session('link') != null){
            return redirect(session('link'));
        }else {
            return redirect()->route('home');
        }
    }
}
