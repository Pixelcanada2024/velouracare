<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Validation\Rule;

class FaqController extends Controller
{
  public function page(Request $request)
  {
    $categories = FaqCategory::with(['faqs'])->orderBy('name', 'asc')->get();

    return inertia('About/FAQs', [
      'categories' => $categories
    ]);
  }




  public function index(Request $request)
  {
    $sort_search = null;
    $faqs = Faq::with('category')->orderBy('created_at', 'desc');

    if ($request->has('search')) {
      $sort_search = $request->search;
      $faqs = $faqs->where('question', 'like', '%' . $sort_search . '%')
        ->orWhere('answer', 'like', '%' . $sort_search . '%');
    }

    $faqs = $faqs->paginate(15);
    $categories = FaqCategory::orderBy('name', 'asc')->get();

    return view('backend.website_settings.faqs.index', compact('faqs', 'categories', 'sort_search'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'question_trans.en' => [
        'required',
        'string',
        'max:255',
        Rule::unique('faqs', 'question_trans->en')
      ],
      'answer_trans.en' => [
        'required',
        'string',
        'max:255',
        Rule::unique('faqs', 'answer_trans->en')
      ],
      'category_id' => 'required|exists:faq_categories,id'
    ]);

    $faq = new Faq;
    $faq->question_trans =  [
        'en' => $request->question_trans['en']
    ];
      $faq->answer_trans =  [
        'en' => $request->answer_trans['en']
    ];
    $faq->category_id = $request->category_id;
    $faq->save();

    flash(translate('FAQ has been inserted successfully'))->success();
    return redirect()->route('faqs.index');
  }


  public function edit($id)
  {
    $faq = Faq::findOrFail($id);
    $categories = FaqCategory::orderBy('name', 'asc')->get();
    return view('backend.website_settings.faqs.edit', compact('faq', 'categories'));
  }


  public function update(Request $request, $id)
  {

    $lang = $request['lang'];

    $request->validate([
      "lang"=>  "required|string|in:en,ar",
      "question_trans.{$lang}" => [
        'required',
        'string',
        'max:255',
        Rule::unique('faqs', "question_trans->{$lang}" )->ignore($id)
      ],
      "answer_trans.{$lang}" => [
        'required',
        'string',
        'max:255',
        Rule::unique('faqs', "answer_trans->{$lang}" )->ignore($id)
      ],
      'category_id' => 'required|exists:faq_categories,id'
    ]);

    $faq = Faq::findOrFail($id);

    $questionTrans = $faq->question_trans ?? [];
    $faq->question_trans = [
        ...$questionTrans,
        $lang => $request->question_trans[$lang]
    ];

    $AnswerTrans = $faq->answer_trans ?? [];
    $faq->answer_trans = [
        ...$AnswerTrans,
        $lang => $request->answer_trans[$lang]
    ];

    $faq->category_id = $request->category_id;
    $faq->save();

    flash(translate('FAQ has been updated successfully'))->success();
    return redirect()->route('faqs.index');
  }


  public function destroy($id)
  {
    $faq = Faq::findOrFail($id);
    Faq::destroy($id);

    flash(translate('FAQ has been deleted successfully'))->success();
    return redirect()->route('faqs.index');
  }

  public function updateHomeStatus(Request $request)
  {
    $faq = Faq::findOrFail($request->id);
    $faq->is_in_home = $request->status;
    $faq->save();

    return 1;
  }
}
