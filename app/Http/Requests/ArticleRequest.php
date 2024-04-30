<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class ArticleRequest extends FormRequest
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
        if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'createArticle') {
            return [
                'title'        => 'required|max:255|unique:articles,title',
                'thumbnail'    => 'required|mimes:jpeg,png,jpg,webp|max:5120',
                'status'       => 'required|in:draft,release',
                'release_date' => $this->input('release_date') ? 'date_format:d-m-Y H:i' : '',
                'body'         => '',
            ];
        }
        else if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'updateArticle') {
            return [
                'title'        => 'required|max:255|unique:articles,title,' . $this->input('id'),
                'newthumbnail' => 'mimes:jpeg,png,jpg,webp|max:5120',
                'status'       => 'required|in:draft,release',
                'release_date' => $this->input('release_date') ? 'date_format:d-m-Y H:i' : '',
                'body'         => '',
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
            'max'     => 'maximal :max',
        ];
    }
}
