<?php

namespace App\Http\Controllers;

use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FaqCategoryController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'name_trans.en' => [
              'required',
              'string',
              'max:255',
              Rule::unique('faq_categories', 'name_trans->en') 
          ]
        ]);

        $category = new FaqCategory;
        $category->name_trans = [
            'en' => $request->name_trans['en']
        ];
        $category->save();

        flash(translate('FAQ Category has been inserted successfully'))->success();
        return redirect()->route('faqs.index');
    }


    public function edit($id)
    {
        $category = FaqCategory::findOrFail($id);
        return view('backend.website_settings.faqs.edit_category', compact('category'));
    }


    public function update(Request $request, $id)
    {
          $lang = $request['lang'];

          $request->validate([
            "lang"=>  "required|string|in:en,ar",
            "name_trans.{$lang}" => [
              'required',
              'string',
              'max:255',
              Rule::unique('faq_categories', "name_trans->{$lang}" )->ignore($id) 
            ],
          ]);

        $category = FaqCategory::findOrFail($id);
        $nameTrans = $category->name_trans ?? [];  
        $category->name_trans = [
            ...$nameTrans,
            $lang => $request->name_trans[$lang]
        ];
        $category->save();

        flash(translate('FAQ Category has been updated successfully'))->success();
        return redirect()->route('faqs.index');
    }


    public function destroy($id)
    {
        $category = FaqCategory::findOrFail($id);

        // Check if category has FAQs
        if($category->faqs()->count() > 0) {
            flash(translate('Cannot delete category that contains FAQs'))->error();
            return redirect()->route('faqs.index');
        }

        FaqCategory::destroy($id);

        flash(translate('FAQ Category has been deleted successfully'))->success();
        return redirect()->route('faqs.index');
    }
}
