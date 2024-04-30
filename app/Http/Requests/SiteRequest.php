<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class SiteRequest extends FormRequest
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
                'region_id' => 'required|exists:regions,id|unique:sites,region_id,' . $this->input('id'),
                'name'      => 'required|max:255|unique:sites,name,' . $this->input('id'),
            ];
        }
        else if ($this->method() == 'PUT') {
            return [
                'id'        => 'required',
                'name'      => 'required|max:255|unique:sites,name,' . $this->input('id'),
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
            'max'     => 'maximal :max karakter',
        ];
    }
}
