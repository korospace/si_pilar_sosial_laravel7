<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class PsmRequest extends FormRequest
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
        else if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'importPsm') {
            return [
                'site_id'                       => 'required|exists:sites,id',
                'year'                          => 'required',
                'file_psm'                      => 'required|mimes:xls,xlsx',
            ];
        }
        else if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'createPsm') {
            return [
                'site_id'                       => $this->user->level_id == 1 ? 'required|exists:sites,id' : '',
                'year'                          => 'required|date_format:Y',
                'nama'                          => 'required|max:255',
                'nik'                           => 'required|digits:16|max:255',
                'tempat_lahir'                  => 'required|max:255',
                'tanggal_lahir'                 => 'required|max:255',
                'jenis_kelamin'                 => 'required|in:L,P',
                'tempat_tugas_kelurahan'        => 'required|max:255',
                'tempat_tugas_kecamatan'        => 'required|max:255',
                'alamat_jalan'                  => 'required',
                'alamat_rt'                     => 'required|max:10',
                'alamat_rw'                     => 'required|max:10',
                'tingkatan_diklat'              => 'required|in:belum pernah,dasar,lanjutan,pengembangan,khusus',
                'sertifikasi_status'            => 'required|in:belum,sudah',
                'sertifikasi_tahun'             => 'max:11',
                'telepon'                       => 'required|max:255',
                'pendidikan_terakhir'           => 'required|max:255',
                'kondisi_existing'              => 'required|in:tidak aktif,kurang aktif,aktif,sangat aktif',
                'status'                        => $this->user->level_id == 1 ? 'required|in:diperiksa,diterima,ditolak' : '',
            ];
        }
        else if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'verifPsm') {
            return [
                'id'                            => 'required|exists:psm,id',
                'status'                        => 'required|in:diterima,ditolak',
            ];
        }
        else if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'updatePsm') {
            return [
                'id'                            => 'required|exists:psm,id',
                'site_id'                       => 'required|exists:sites,id',
                'year'                          => 'required|date_format:Y',
                'nama'                          => 'required|max:255',
                'nik'                           => 'required|digits:16|max:255',
                'tempat_lahir'                  => 'required|max:255',
                'tanggal_lahir'                 => 'required|max:255',
                'jenis_kelamin'                 => 'required|in:L,P',
                'tempat_tugas_kelurahan'        => 'required|max:255',
                'tempat_tugas_kecamatan'        => 'required|max:255',
                'alamat_jalan'                  => 'required',
                'alamat_rt'                     => 'required|max:10',
                'alamat_rw'                     => 'required|max:10',
                'tingkatan_diklat'              => 'required|in:belum pernah,dasar,lanjutan,pengembangan,khusus',
                'sertifikasi_status'            => 'in:belum,sudah',
                'sertifikasi_tahun'             => 'required|max:11',
                'telepon'                       => 'required|max:255',
                'pendidikan_terakhir'           => 'required|max:255',
                'kondisi_existing'              => 'required|in:tidak aktif,kurang aktif,aktif,sangat aktif',
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
