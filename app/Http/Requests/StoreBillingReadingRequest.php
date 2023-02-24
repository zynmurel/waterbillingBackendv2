<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBillingReadingRequest extends FormRequest
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
            "reader_id" =>"required|max:50",
            "consumer_id" =>"required|max:50",
            "service_period_id" =>"required|max:50",
            "previous_reading" =>"required|max:50",
            "present_reading" =>"required|max:50",
            "reading_date" =>"required|max:150",
            "due_date" =>"required|max:50",
            "present_bill" =>"required|max:50",
            "proof_image"=>"required"
        ];
    }
}
