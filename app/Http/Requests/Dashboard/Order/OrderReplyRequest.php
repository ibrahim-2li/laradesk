<?php

namespace App\Http\Requests\Dashboard\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderReplyRequest extends FormRequest
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
            'body' => ['required'],
            'orders_status_id' => ['required', 'exists:order_statuses,id'],
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
            'body.required' => __('The :attribute field is required', ['attribute' => __('body')]),

            'orders_status_id.required' => __('The :attribute field is required', ['attribute' => __('order_statuses')]),
            'orders_status_id.exists' => __('The selected :attribute is invalid', ['attribute' => __('order_statuses')]),
        ];
    }
}
