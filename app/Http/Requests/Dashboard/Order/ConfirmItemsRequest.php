<?php

namespace App\Http\Requests\Dashboard\Order;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmItemsRequest extends FormRequest
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
            'confirmItems' => 'required|array',
        'confirmItems.*.item' => 'required|string',
        'confirmItems.*.item_count' => 'required|integer',
        'confirmItems.*.details' => 'nullable|string',
        'orders_status_id' => 'required|integer|exists:order_statuses,id',
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
            'item.required' => __('The :attribute field is required', ['attribute' => __('item')]),
            'item_count' => ['required'],
            'orders_status_id.required' => __('The :attribute field is required', ['attribute' => __('orders_statuses')]),
            'orders_status_id.exists' => __('The selected :attribute is invalid', ['attribute' => __('orders_statuses')]),
        ];
    }
}
