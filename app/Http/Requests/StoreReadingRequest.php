<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReadingRequest extends FormRequest
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
            "reader_id" => "required|max:20",
            "consumer_id" => "required|max:20",
            "service_period_id" => "required|max:20",
            "previous_reading" => "required|max:20",
            "present_reading" => "required|max:20",
            "reading_date" => "required|max:50"
        ];
    }
}
