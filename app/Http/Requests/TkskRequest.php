<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class TkskRequest extends FormRequest
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
        $user = $this->user;

        if ($this->method() == 'GET') {
            return [
            ];
        }
        else if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'importTksk') {
            return [
                'site_id'                       => $user->level_id == 1 ? 'required|exists:sites,id' : '',
                'year'                          => 'required|date_format:Y',
                'status'                        => $user->level_id == 1 ? 'required|in:diperiksa,diterima,ditolak,nonaktif' : '',
                'file_tksk'                     => 'required|mimes:xls,xlsx',
            ];
        }
        else if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'createTksk') {
            return [
                'site_id'                       => $this->user->level_id == 1 ? 'required|exists:sites,id' : '',
                'status'                        => $this->user->level_id == 1 ? 'required|in:diperiksa,diterima,ditolak' : '',
                'year'                          => 'required|date_format:Y',
                // 'no_induk_anggota'              => 'required|max:255',
                // 'tempat_tugas'                  => 'required|max:255',
                // 'nama'                          => 'required|max:255',
                // 'nama_ibu_kandung'              => 'required|max:255',
                // 'nik'                           => 'required|digits:16|max:255|unique:tksk,nik',
                // 'tempat_lahir'                  => 'required|max:255',
                // 'tanggal_lahir'                 => 'required|max:255',
                // 'pendidikan_terakhir'           => 'required|max:255',
                // 'jenis_kelamin'                 => 'required|in:L,P',
                // 'alamat_jalan'                  => 'required',
                // 'alamat_rt'                     => 'required|max:10',
                // 'alamat_rw'                     => 'required|max:10',
                // 'alamat_kelurahan'              => 'required|max:255',
                // 'telepon'                       => 'required|max:255',
                // 'nama_di_rekening'              => 'required|max:255',
                // 'no_rekening'                   => 'required|max:255',
                // 'nama_bank'                     => 'required|max:255',
                // 'tahun_pengangkatan_pertama'    => 'required|max:11',
                // 'nosk_pengangkatan_pertama'     => 'required|max:255',
                // 'pejabat_pengangkatan_pertama'  => 'required|max:255',
                // 'tahun_pengangkatan_terakhir'   => 'required|max:11',
                // 'nosk_pengangkatan_terakhir'    => 'required|max:255',
                // 'pejabat_pengangkatan_terakhir' => 'required|max:255',
                // 'no_kartu_registrasi'           => 'required|max:255',
            ];
        }
        else if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'updateTksk') {
            return [
                'id'                            => 'required|exists:tksk,id',
                'site_id'                       => 'required|exists:sites,id',
                'status'                        => 'required|in:diperiksa,diterima,ditolak',
                'year'                          => 'required|date_format:Y',
                // 'no_induk_anggota'              => 'required|max:255',
                // 'tempat_tugas'                  => 'required|max:255',
                // 'nama'                          => 'required|max:255',
                // 'nama_ibu_kandung'              => 'required|max:255',
                // 'nik'                           => 'required|digits:16|max:255|unique:tksk,nik,' . $this->input('id'),
                // 'tempat_lahir'                  => 'required|max:255',
                // 'tanggal_lahir'                 => 'required|max:255',
                // 'pendidikan_terakhir'           => 'required|max:255',
                // 'jenis_kelamin'                 => 'required|in:L,P',
                // 'alamat_jalan'                  => 'required',
                // 'alamat_rt'                     => 'required|max:10',
                // 'alamat_rw'                     => 'required|max:10',
                // 'alamat_kelurahan'              => 'required|max:255',
                // 'telepon'                       => 'required|max:255',
                // 'nama_di_rekening'              => 'required|max:255',
                // 'no_rekening'                   => 'required|max:255',
                // 'nama_bank'                     => 'required|max:255',
                // 'tahun_pengangkatan_pertama'    => 'required|max:11',
                // 'nosk_pengangkatan_pertama'     => 'required|max:255',
                // 'pejabat_pengangkatan_pertama'  => 'required|max:255',
                // 'tahun_pengangkatan_terakhir'   => 'required|max:11',
                // 'nosk_pengangkatan_terakhir'    => 'required|max:255',
                // 'pejabat_pengangkatan_terakhir' => 'required|max:255',
                // 'no_kartu_registrasi'           => 'required|max:255',
            ];
        }
        else if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'verifTksk') {
            return [
                'id'                            => 'required|exists:tksk,id',
                'status'                        => 'required|in:diterima,ditolak',
            ];
        }
        else if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'updateStatus') {
            return [
                'id'                            => 'required|exists:tksk,id',
                'status'                        => 'required|in:diperiksa,diterima,ditolak,nonaktif',
                'description'                   => '',
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
