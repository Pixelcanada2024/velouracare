<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
  public function __construct()
  {
    // Staff Permission Check
    $this->middleware(['permission:view_all_subscribers'])->only('index');
    $this->middleware(['permission:delete_subscriber'])->only('destroy');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $subscribers = Subscriber::orderBy('created_at', 'desc')->paginate(15);
    return view('backend.marketing.subscribers.index', compact('subscribers'));
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
   */
  public function store(Request $request)
  {
    $isEn = app()->getLocale() == 'en';

    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
    ]);

    if ($validator->fails()) {
      flash(translate('A valid email is required'))->error();
      return back()->with('error', 'A valid email is required');
    }

    // Track if a new subscriber was added
    $isNew = false;

    $subscriber = Subscriber::where('email', $request->email)->first();

    if ($subscriber == null) {

      $isNew = true;

      $subscriber = new Subscriber;
      $subscriber->email = $request->email;
      $subscriber->save();

      // Email info
      $info = [
        'phone' => get_setting('contact_phone') ?? '+966 112 860 262',
        'email' => get_setting('contact_email') ?? 'info@velouracare.com',
        'address' => get_setting('contact_address') ?? 'Kingdom of Saudi Arabia – Riyadh – Al-Malaz – Salah Al-Din Al-Ayyubi Road ',
        'facebook' => get_setting('facebook_link') ?? '#',
        'twitter' => get_setting('twitter_link') ?? '#',
        'instagram' => get_setting('instagram_link') ?? '#',
        'linkedin' => get_setting('linkedin_link') ?? '#',
        'url' => url('/'),
      ];

      Mail::send('emails.subscribing', ['info' => $info, 'subscriber' => $subscriber], function ($message) use ($subscriber) {
        $message->to($subscriber->email)
          ->subject('Subscription Confirmation' . ' - ' . get_setting('site_name', 'Veloura Care'));
        $message->from(config('mail.from.address'), config('mail.from.name'));
      });

      // Texts for NEW subscription
      $title = $isEn ? 'Subscription Confirmed' : 'تم تأكيد الاشتراك';

      $messageText = $isEn ?
        'Thank you for subscribing to the Veloura Care newsletter. You’ll now receive updates on our latest beauty offers.'
        : "نشكرك على اشتراكك في نشرة فيلاورا كير الإخبارية. ستتلقى الآن تحديثات حول أحدث منتجات التجميل والصحة، وعروضًا حصرية.";
    } else {
      // Texts for EXISTING subscriber
      $title = $isEn
        ? "Oops, You're Already Subscribed!"
        : 'عذراً، أنت مشترك بالفعل!';

      $messageText = $isEn
        ? 'It looks like you\'re already part of the Veloura Care family! Thank you for your continued support and loyalty.'
        : 'يبدو أنك بالفعل عضوًا في عائلة فيلاورا كير! شكرًا لدعمك المستمر وولائك.';
    }

    return back()
      ->with('title', $title)
      ->with('message', $messageText)
      ->with('status', $isNew ? 'success' : 'normal');
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
    Subscriber::destroy($id);
    flash(translate('Subscriber has been deleted successfully'))->success();
    return redirect()->route('subscribers.index');
  }


  public function toggle(Request $request)
  {
    $data = $request->validate([
      'subscribe' => 'required|boolean'
    ]);

    $isEn = app()->getLocale() == 'en';


    $user = auth()->user();

    // Find if user is already subscribed
    $subscriber = Subscriber::where('email', $user->email)->first();


    if ($data['subscribe']) {
      // User wants to subscribe
      if (!$subscriber) {
        $subscriber = new Subscriber;
        $subscriber->email = $user->email;
        $subscriber->save();
        $message = $isEn ? 'You have successfully subscribed to our newsletter' : 'تم الاشتراك بنجاح';
      } else {
        $message = $isEn ? 'You are already subscribed to our newsletter' : 'انت بالفعل مشترك';
      }
    } else {
      // User wants to unsubscribe
      if ($subscriber) {
        $subscriber->delete();
        $message = $isEn ? 'You have successfully unsubscribed from our newsletter' : 'تم الغاء الاشتراك بنجاح';
      } else {
        $message = $isEn ? 'You are not subscribed to our newsletter' : 'انت غير مشترك';
      }
    }

    $title = $isEn ? 'Success' : 'تم بنجاح';

    return redirect()->back()
      ->with('title', $title)
      ->with('message', $message);
  }


  public function Subscription(Request $request)
  {
    $user = auth()->user();
    $isSubscribed = Subscriber::where('email', $user->email)->exists();

    return inertia('UserDashboard/NewsletterSubscription/NewsletterSubscription', [
      'isSubscribed' => $isSubscribed
    ]);
  }


  public function unsubscribe($id, $email)
  {
    $subscriber = Subscriber::where('id', $id)->where('email', $email)->first();
    $isEn = app()->getLocale() == 'en';

    if ($subscriber) {
      $subscriber->delete();
      $title = $isEn ? 'Unsubscription Complete!' : 'تم إلغاء الاشتراك!';
      $message = $isEn ?
        'You have been unsubscribed from Veloura Care\'s newsletter. We appreciate your support and interest.'
        : 'تم إلغاء اشتراكك في نشرة فيلاورا كير الإخبارية. نشكر دعمك واهتمامك.';

      return redirect()->route('react.home')->with('message', $message)->with('title', $title);
    } else {
      $title = $isEn ? 'Error' : 'حدث خطأ';
      $message = $isEn ? 'Subscriber not found or already unsubscribed' : 'المشترك غير موجود او تم الغاء الاشتراك بنجاح';
      return redirect()->route('react.home')->with('message', $message)->with('title', $title)->with('status', 'error');
    }
  }


  public function modalSubscribe(Request $request)
  {
    $data = $request->validate([
      'email' => 'required|email|unique:subscribers,email',
    ], [
      'email.unique' => 'This email is already subscribed.',
    ]);

    // If validation passes, create the subscriber
    $subscriber = new Subscriber;
    $subscriber->email = $data['email'];
    $subscriber->save();

    return back()->with('status', 'modal-subscribe-newsletter-done');
  }
}
