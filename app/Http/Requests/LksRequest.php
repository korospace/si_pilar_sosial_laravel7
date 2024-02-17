<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class LksRequest extends FormRequest
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
        else if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'importLks') {
            return [
                'site_id'                      => 'required|exists:sites,id',
                'year'                         => 'required',
                'file_lks'                     => 'required|mimes:xls,xlsx',
            ];
        }
        else if ($this->method() == 'POST' && $this->route()->getActionMethod() == 'createLks') {
            return [
                'site_id'                           => $this->user->level_id == 1 ? 'required|exists:sites,id' : '',
                'year'                              => 'required|date_format:Y',
                'nama'                              => 'required|max:255',
                'nama_ketua'                        => 'required|max:255',
                'alamat_jalan'                      => 'required',
                'alamat_rt'                         => 'required|max:10',
                'alamat_rw'                         => 'required|max:10',
                'alamat_kelurahan'                  => 'required|max:255',
                'alamat_kecamatan'                  => 'required|max:255',
                'no_telp_yayasan'                   => 'required|max:255',
                'jenis_layanan'                     => 'required|max:255',
                'sk_domisili_yayasan_nomor'                => 'required|max:255',
                'sk_domisili_yayasan_masaberlaku_mulai'    => 'required|max:255',
                'sk_domisili_yayasan_masaberlaku_selesai'  => 'required|max:255',
                'sk_domisili_yayasan_instansi_penerbit'    => 'required|max:255',
                'tanda_daftar_yayasan_nomor'               => 'required|max:255',
                'tanda_daftar_yayasan_masaberlaku_mulai'   => 'required|max:255',
                'tanda_daftar_yayasan_masaberlaku_selesai' => 'required|max:255',
                'tanda_daftar_yayasan_instansi_penerbit'   => 'required|max:255',
                'izin_kegiatan_yayasan_nomor'              => 'required|max:255',
                'izin_kegiatan_yayasan_masaberlaku_mulai'  => 'required|max:255',
                'izin_kegiatan_yayasan_masaberlaku_selesai'=> 'required|max:255',
                'izin_kegiatan_yayasan_instansi_penerbit'  => 'required|max:255',
                'induk_berusaha_status'             => 'required|in:ada,tidak ada',
                'induk_berusaha_nomor'              => 'required|max:255',
                'induk_berusaha_tgl_terbit'         => 'required|max:255',
                'induk_berusaha_instansi_penerbit'  => 'required|max:255',
                'akreditasi'                        => 'required|max:255',
                'akreditasi_tgl'                    => 'required|max:255',
                'status'                            => $this->user->level_id == 1 ? 'required|in:diperiksa,diterima,ditolak' : '',
            ];
        }
        else if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'verifLks') {
            return [
                'id'                            => 'required|exists:lks,id',
                'status'                        => 'required|in:diterima,ditolak',
            ];
        }
        else if ($this->method() == 'PUT' && $this->route()->getActionMethod() == 'updateLks') {
            return [
                'id'                                => 'required|exists:lks,id',
                'site_id'                           => 'required|exists:sites,id',
                'year'                              => 'required|date_format:Y',
                'nama'                              => 'required|max:255',
                'nama_ketua'                        => 'required|max:255',
                'alamat_jalan'                      => 'required',
                'alamat_rt'                         => 'required|max:10',
                'alamat_rw'                         => 'required|max:10',
                'alamat_kelurahan'                  => 'required|max:255',
                'alamat_kecamatan'                  => 'required|max:255',
                'no_telp_yayasan'                   => 'required|max:255',
                'jenis_layanan'                     => 'required|max:255',
                'sk_domisili_yayasan_nomor'                => 'required|max:255',
                'sk_domisili_yayasan_masaberlaku_mulai'    => 'required|max:255',
                'sk_domisili_yayasan_masaberlaku_selesai'  => 'required|max:255',
                'sk_domisili_yayasan_instansi_penerbit'    => 'required|max:255',
                'tanda_daftar_yayasan_nomor'               => 'required|max:255',
                'tanda_daftar_yayasan_masaberlaku_mulai'   => 'required|max:255',
                'tanda_daftar_yayasan_masaberlaku_selesai' => 'required|max:255',
                'tanda_daftar_yayasan_instansi_penerbit'   => 'required|max:255',
                'izin_kegiatan_yayasan_nomor'              => 'required|max:255',
                'izin_kegiatan_yayasan_masaberlaku_mulai'  => 'required|max:255',
                'izin_kegiatan_yayasan_masaberlaku_selesai'=> 'required|max:255',
                'izin_kegiatan_yayasan_instansi_penerbit'  => 'required|max:255',
                'induk_berusaha_status'             => 'required|in:ada,tidak ada',
                'induk_berusaha_nomor'              => 'required|max:255',
                'induk_berusaha_tgl_terbit'         => 'required|max:255',
                'induk_berusaha_instansi_penerbit'  => 'required|max:255',
                'akreditasi'                        => 'required|max:255',
                'akreditasi_tgl'                    => 'required|max:255',
                'status'                            => 'required|in:diperiksa,diterima,ditolak',
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
