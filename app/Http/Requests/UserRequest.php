<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if ($this->method() == 'POST') {
            return [
                'name'     => 'required|max:255',
                'email'    => 'required|email|unique:users,email,' . $this->input('id'),
                'level_id' => 'required|exists:user_levels,id',
                'site_id'  => in_array($this->input('id'), [1]) ? '' : 'required|exists:sites,id',
                'password' => 'required|max:20',
            ];
        }
        else if ($this->method() == 'PUT') {
            return [
                'id'       => 'required|exists:users,id',
                'name'     => 'required|max:255',
                'email'    => 'required|email|unique:users,email,' . $this->input('id'),
                'level_id' => 'required|exists:user_levels,id',
                'site_id'  => in_array($this->input('id'), [1]) ? '' : 'required|exists:sites,id',
                'new_password' => 'max:20',
            ];
        }
        else {
            return [];
        }
    }

    /**
     * OVERIDE
     * =================================
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Bad Request',
            'data'    => [
                'errors' => $validator->errors(),
            ]
        ], 400));
    }

    public function messages()
    {
        return [
            'required'=> 'harus diisi',
            'email'   => 'email tidak valid',
            'unique'  => '(:input) sudah digunakan',
            'max'     => 'maximal :max karakter',
            'exists'  => '(:input) tidak ditemukan',
        ];
    }
}
