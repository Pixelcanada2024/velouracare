<?php

namespace App\Http\Controllers;

use App\Mail\ContactMailManager;
use App\Models\Contact;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Mail;

class ContactController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_contacts'])->only('index');
        $this->middleware(['permission:reply_to_contact'])->only('reply_modal');
    }

    public function index()
    {
        $contacts = Contact::orderBy('id', 'desc')->paginate(20);
        return view('backend.support.contact.contacts', compact('contacts'));
    }

    public function query_modal(Request $request)
    {
        $contact = Contact::findOrFail($request->id);
        return view('backend.support.contact.query_modal', compact('contact'));
    }

    public function reply_modal(Request $request)
    {
        $contact = Contact::findOrFail($request->id);
        return view('backend.support.contact.reply_modal', compact('contact'));
    }

    public function reply(Request $request)
    {
        $contact = Contact::findOrFail($request->contact_id);
        $admin = get_admin();

        $array['name'] = $admin->name;
        $array['email'] = $admin->email;
        $array['phone'] = $admin->phone;
        $array['content'] = str_replace("\n", "<br>", $request->reply);
        $array['subject'] = translate('Query Contact Reply');
        $array['from'] = $admin->email;

        try {
            Mail::to($contact->email)->queue(new ContactMailManager($array));
            $contact->update([
                'reply' => $request->reply,
            ]);
        } catch (\Exception $e) {
            flash(translate('Something Went wrong'))->error();
            return back();
        }
        flash(translate('Reply has been sent successfully'))->success();
        return back();
    }

    public function contact(Request $request)
    {
        // validate recaptcha
        $request->validate([
            'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_contact_form') == 1, ['required', new Recaptcha()], ['sometimes'])
            ],
        ]);
        $admin = get_admin();

        $array['name'] = $request->name;
        $array['email'] = $request->email;
        $array['phone'] = $request->phone;
        $array['content'] = str_replace("\n", "<br>", $request->content);
        $array['subject'] = translate('Query Contact');
        $array['from'] = $request->email;

        try {
            Mail::to($admin->email)->queue(new ContactMailManager($array));
            Contact::insert([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'content' => $request->content,
            ]);
        } catch (\Exception $e) {
            flash(translate('Something Went wrong'))->error();
            return back();
        }
        flash(translate('Query has been sent successfully'))->success();
        return back();
    }

    public function contactUs(Request $request)
    {
        // validate recaptcha
        $data = $request->validate([
                  'name' => 'required|string|max:255',
                  'company_name' => 'required|string|max:255',
                  'email'=>'required|email|max:255',
                  'phone' => 'required|string',
                  "content"=>'required|string|max:1000',
        ]);

        $array['name'] = $data['name'];
        $array['company_name'] = $data['company_name'];
        $array['email'] = $data['email'];
        $array['phone'] = $data['phone'];
        $array['content'] = str_replace("\n", "<br>", $data['content']);
        $array['subject'] = translate('Query Contact');

        $admin = get_admin();

        try {
            Mail::to($admin->email)->queue(new ContactMailManager($array));
            Contact::create([
                'name' => $array['name'],
                'company_name' => $data['company_name'],
                'email' => $array['email'],
                'phone' => $array['phone'],
                'content' => $array['content'],
            ]);
        } catch (\Exception $e) {
            flash(translate('Something Went wrong'))->error();
            return back()->with('title','Server Error Occurred')->with('message',"Please Contact The Support Team")->with('status',"error");
        }
        // flash(translate('Query has been sent successfully'))->success();
        return back()->with('title','Done')->with('message',"Message has been sent successfully");
    }


    public function ContactUsPage(){

        $contact_us_locations = json_decode(get_setting('contact_us_locations'), true) ?: [];

        return inertia('About/ContactUs', [
            'contact_us_locations' => $contact_us_locations,
        ]);
    }
}
