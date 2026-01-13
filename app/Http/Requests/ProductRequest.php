<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];

        $rules['name']          = 'required|max:255';
        $rules['category_ids']  = 'required';
        $rules['category_id']   = ['required', Rule::in($this->category_ids)];

        // For main product discount
        if ($this->get('discount_type') == 'amount') {
            // Get minimum variant price for validation
            $minPrice = 0;
            if ($this->has('variant_price')) {
                $minPrice = min($this->variant_price);
            }
            
            if ($minPrice > 0) {
                $rules['discount'] = 'sometimes|required|numeric|lt:' . $minPrice;
            }
        } else {
            $rules['discount'] = 'sometimes|required|numeric|lt:100';
        }

        $rules['current_stock'] = 'sometimes|required|numeric';
        $rules['starting_bid']  = 'sometimes|required|numeric|min:1';
        $rules['auction_date_range']  = 'sometimes|required';

        // Validate single variant
        $rules['variant_price.0'] = 'required|numeric|min:0';
        $rules['variant_qty.0'] = 'required|numeric|min:0';
        $rules['variant_sku.0'] = 'required|string';

        $rules['variant_barcode.0'] = 'required|string|max:255';
        $rules['variant_box_qty.0'] = 'required|numeric|min:0';
        $rules['variant_lead_time.0'] = 'required|string|max:255';
        $rules['variant_made_in_country_id.0'] = 'required|exists:countries,id';
        $rules['variant_available_document.0'] = 'required|in:0,1,2';
        $rules['variant_msrp.0'] = 'required|numeric|min:0';

        return $rules;
    }

    /**
     * Get the validation messages of rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'             => translate('Product name is required'),
            'category_ids.required'     => translate('Product category is required'),
            'category_id.required'      => translate('Main Category is required'),
            'category_id.in'            => translate('Main Category must be within selected categories'),
            'unit.required'             => translate('Product unit is required'),
            'min_qty.required'          => translate('Minimum purchase quantity is required'),
            'min_qty.numeric'           => translate('Minimum purchase must be numeric'),

            'variant_price.0.required'  => translate('Variant price is required'),
            'variant_price.0.gt'        => translate('Variant price must be greater than 0'),
            'variant_qty.0.required'    => translate('Variant quantity is required'),
            'variant_qty.0.numeric'     => translate('Variant quantity must be numeric'),
            'variant_sku.0.required'    => translate('Variant SKU is required'),
            'variant_sku.0.string'      => translate('Variant SKU must be a string'),

            'variant_barcode.0.required' => translate('Variant barcode is required'),
            'variant_barcode.0.string'   => translate('Variant barcode must be a string'),
            'variant_barcode.0.max'      => translate('Variant barcode may not be greater than 255 characters'),

            'variant_box_qty.0.required' => translate('Variant box quantity is required'),
            'variant_box_qty.0.numeric'  => translate('Variant box quantity must be numeric'),
            'variant_box_qty.0.min'      => translate('Variant box quantity must be at least 0'),

            'variant_lead_time.0.required' => translate('Variant lead time is required'),
            'variant_lead_time.0.string'   => translate('Variant lead time must be a string'),
            'variant_lead_time.0.max'      => translate('Variant lead time may not be greater than 255 characters'),

            'variant_made_in_country_id.0.required' => translate('Variant country of origin is required'),
            'variant_made_in_country_id.0.exists'   => translate('Selected country of origin is invalid'),

            'variant_available_document.0.required' => translate('Variant available document is required'),
            'variant_available_document.0.in'       => translate('Variant available document must be either Commercial Invoice, MSDS, or both'),

            'variant_msrp.0.required' => translate('Variant MSRP is required'),
            'variant_msrp.0.numeric'  => translate('Variant MSRP must be numeric'),
            'variant_msrp.0.min'      => translate('Variant MSRP must be at least 0'),

            'discount.required'         => translate('Discount is required'),
            'discount.numeric'          => translate('Discount must be numeric'),
            'discount.lt'               => translate('Discount should be less than product price'),

            'current_stock.required'    => translate('Current stock is required'),
            'current_stock.numeric'     => translate('Current stock must be numeric'),

            'starting_bid.required'     => translate('Starting Bid is required'),
            'starting_bid.numeric'      => translate('Starting Bid must be numeric'),
            'starting_bid.min'          => translate('Minimum Starting Bid is 1'),

            'auction_date_range.required' => translate('Auction Date Range is required'),
        ];
    }


    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    public function failedValidation(Validator $validator)
    {
        // dd($this->expectsJson());
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'message' => $validator->errors()->all(),
                'result' => false
            ], 422));
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }
}
