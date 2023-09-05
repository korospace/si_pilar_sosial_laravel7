<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class KarangTarunaRequest extends FormRequest
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
        if ($this->method() == 'GET') {
            return [
            ];
        }
        else if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'importKarangTaruna') {
            return [
                'site_id'                       => 'required|exists:sites,id',
                'year'                          => 'required',
                'file_karang_taruna'            => 'required|mimes:xls,xlsx',
            ];
        }
        else if ($this->method() == 'POST') {
            return [
                'site_id'                       => $this->user->level_id == 1 ? 'required|exists:sites,id' : '',
                'nama'                          => 'required|max:255',
                'nama_ketua'                    => 'required|max:255',
                'alamat_jalan'                  => 'required',
                'alamat_rt'                     => 'required|max:10',
                'alamat_rw'                     => 'required|max:10',
                'alamat_kelurahan'              => 'required|max:255',
                'alamat_kecamatan'              => 'required|max:255',
                'telepon'                       => 'required|max:255',
                'kepengurusan_status'           => 'required|in:sudah terbentuk,belum terbentuk',
                'kepengurusan_sk_tgl'           => 'max:255',
                'kepengurusan_periode_tahun'    => 'max:11',
                'kepengurusan_jumlah'           => 'max:11',
                'keaktifan_status'              => 'required|in:tidak aktif,kurang aktif,aktif,sangat aktif',
                'program_unggulan'              => 'required|max:255',
                'status'                        => $this->user->level_id == 1 ? 'required|in:diperiksa,diterima,ditolak' : '',
            ];
        }
        else if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'verifKarangTaruna') {
            return [
                'id'                            => 'required|exists:karang_taruna,id',
                'status'                        => 'required|in:diterima,ditolak',
            ];
        }
        else if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'updateKarangTaruna') {
            return [
                'id'                            => 'required|exists:karang_taruna,id',
                'site_id'                       => 'required|exists:sites,id',
                'nama'                          => 'required|max:255',
                'nama_ketua'                    => 'required|max:255',
                'alamat_jalan'                  => 'required',
                'alamat_rt'                     => 'required|max:10',
                'alamat_rw'                     => 'required|max:10',
                'alamat_kelurahan'              => 'required|max:255',
                'alamat_kecamatan'              => 'required|max:255',
                'telepon'                       => 'required|max:255',
                'kepengurusan_status'           => 'required|in:sudah terbentuk,belum terbentuk',
                'kepengurusan_sk_tgl'           => 'max:255',
                'kepengurusan_periode_tahun'    => 'max:11',
                'kepengurusan_jumlah'           => 'max:11',
                'keaktifan_status'              => 'required|in:tidak aktif,kurang aktif,aktif,sangat aktif',
                'program_unggulan'              => 'required|max:255',
                'status'                        => 'required|in:diperiksa,diterima,ditolak',
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
            'in'      => "nilai :attribute hanya boleh (:values)",
            'mimes'   => ":attribute hanya boleh bertipe: :values",
        ];
    }
}
