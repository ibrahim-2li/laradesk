<?php

namespace App\Http\Requests\Dashboard\Admin\Stock;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
            'name' => ['required', 'max:255'],
            'brand_id' => ['required'],
            'quantity' => ['required', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => __('The :attribute field is required', ['attribute' => __('name')]),
            'name.max' => __('The :attribute may not be greater than :max characters', ['attribute' => __('name'), 'max' => 255]),

            'brand_id.required' => __('The :attribute field is required', ['attribute' => __('brand_id')]),
            'brand_id.exists' => __('The selected :attribute is invalid', ['attribute' => __('brand_id')]),

            'quantity.required' => __('The :attribute field is required', ['attribute' => __('quantity')]),
            'quantity.exists' => __('The selected :attribute is invalid', ['attribute' => __('quantity')]),

        ];
    }
}
