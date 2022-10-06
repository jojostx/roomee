<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompliantFromContactPageRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'message' => 'required|max:255'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Your first name is required',
            'last_name.required' => 'Your last name is required',
            'email.required' => 'Your email address is required',
            'message.required' => 'the message field is required',
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email address',
        ];
    }
}
