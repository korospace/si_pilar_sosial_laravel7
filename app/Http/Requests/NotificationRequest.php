<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class NotificationRequest extends FormRequest
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
        if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'forgotPassword') {
            return [
                'email' => 'required|exists:users,email',
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
            'unique'  => '(:input) sudah digunakan',
            'exists'  => ':attribute tidak ditemukan',
            'max'     => 'maximal :max karakter',
            'in'      => "nilai :attribute hanya boleh (:values)",
            'mimes'   => ":attribute hanya boleh bertipe: :values",
        ];
    }
}
