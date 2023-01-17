<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsumerRequest extends FormRequest
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
        return [[
            "first_name" => "required|max:50",
            "last_name" => "required|max:50",
            "middle_name" => "required|max:50",
            "gender" => "required|max:100",
            "birthday" => "required|max:100",
            "phone" => "required|max:20",
            "civil_status" => "required|max:20",
            "name_of_spouse" => "",
            "brgyprk_id" => "required|max:20",
            "household_no" => "required|max:100",
            "first_reading" => "required|max:20",
            "usage_type" => "required|max:20",
            "serial_no" => "required|max:50",
            "brand" => "required|max:20",
            "status" => "required|max:20",
            "delinquent" => "required|max:20",
            "registered_at" => "required|max:200"
        ]
        ];
    }
}
