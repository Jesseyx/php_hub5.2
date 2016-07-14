<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreUserRequest extends Request
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
            'github_id'         => 'required|unique:users',
            'github_name'       => 'required',
            'name'              => 'required|alpha_num|unique:users',
            'email'             => 'email',
            'github_url'        => 'active_url',
            'image_url'         => 'active_url',
        ];
    }
}
