<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "cashier_id" => "required|max:20",
            "consumer_id" => "required|max:20",
            "date_paid" => "required|max:50",
            "amount_paid" =>"required|max:20"
        ];
    }
}
