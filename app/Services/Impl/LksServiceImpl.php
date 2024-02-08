<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\LksRequest;
use App\Models\Lks;
use App\Services\LksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class LksServiceImpl implements LksService
{
    public function getLksDataTable(LksRequest $request): JsonResponse
    {
        try {
            $no = 1;
            $lksRows = Lks::query();

            // filter
            if ($request->user->site) {
                $lksRows->where('site_id', $request->user->site_id);
            }
            if ($request->year) {
                $lksRows->where("year", $request->year);
            }
            if ($request->site_id) {
                $lksRows->where('site_id', $request->site_id);
            }
            if ($request->status) {
                $lksRows->where('status', $request->status);
            }

            $lksRows = $lksRows->select('id', 'year', 'site_id', 'no_urut', 'nama', 'nama_ketua', 'jenis_layanan', 'akreditasi', 'status')->orderBy('id', 'DESC')->with('site')->get();

            return datatables()->of($lksRows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('no_urut', function ($row) {
                    $html = $row->no_urut ? str_replace(".", "", $row->site->region_id) . str_pad($row->no_urut, 5, "0", STR_PAD_LEFT) : '-';

                    return $html;
                })
                ->addColumn('status', function ($row) use ($request) {
                    $statusClass = "";
                    $statusText  = "";

                    if ($row->status == 'diperiksa') {
                        $statusClass = "btn-outline-warning";
                    }
                    else if ($row->status == 'ditolak') {
                        $statusClass = "btn-outline-danger";
                    }
                    else if ($row->status == 'diterima') {
                        $statusClass = "btn-outline-success";
                    }

                    if ($row->status == 'diperiksa' && $request->user->level_id == 2) {
                        $statusText = "butuh pemeriksaan";
                    }
                    else {
                        $statusText = $row->status ? $row->status : '';
                    }

                    $html = '
                        <span class="btn '.$statusClass.' btn-sm" style="width: max-content;">
                            '.$statusText.'
                        </span>
                    ';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '
                        <a href="'.route('lks.update', $row->id).'" class="btn btn-sm bg-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    ';

                    return $html;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function getLksInfoStatus(LksRequest $request): JsonResponse
    {
        try {
            $whereSite1 = null;
            $whereSite2 = null;

            if ($request->user->site) {
                $whereSite1 = " AND site_id = ".$request->user->site_id."";
                $whereSite2 = " WHERE site_id = ".$request->user->site_id."";
            }

            $result = DB::select("
                SELECT
                    (SELECT COUNT(id) FROM lks WHERE status = 'diterima' $whereSite1) as diterima,
                    (SELECT COUNT(id) FROM lks WHERE status = 'ditolak' $whereSite1) as ditolak,
                    (SELECT COUNT(id) FROM lks WHERE status = 'diperiksa' $whereSite1) as diperiksa,
                    COUNT(*) as total
                FROM
                    lks
                $whereSite2;
            ");

            return response()->json($result, 200);
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createLks(LksRequest $request): JsonResponse
    {
        try {
            $siteId                          = $request->user->level_id === 1 ? $request->site_id : $request->user->site_id;
            $akta_pendirian_tgl              = date("d-m-Y", strtotime($request->akta_pendirian_tgl));
            $sk_hukumham_pendirian_tgl       = date("d-m-Y", strtotime($request->sk_hukumham_pendirian_tgl));
            $akta_perubahan_tgl              = date("d-m-Y", strtotime($request->akta_perubahan_tgl));
            $sk_hukumham_perubahan_tgl       = date("d-m-Y", strtotime($request->sk_hukumham_perubahan_tgl));
            $sk_domisili_yayasan_tgl_terbit  = date("d-m-Y", strtotime($request->sk_domisili_yayasan_tgl_terbit));
            $tanda_daftar_yayasan_tgl_terbit = date("d-m-Y", strtotime($request->tanda_daftar_yayasan_tgl_terbit));
            $izin_kegiatan_yayasan_tgl_terbit= date("d-m-Y", strtotime($request->izin_kegiatan_yayasan_tgl_terbit));
            $induk_berusaha_tgl              = date("d-m-Y", strtotime($request->induk_berusaha_tgl));
            $akreditasi_tgl                  = date("d-m-Y", strtotime($request->akreditasi_tgl));
            $status                          = $request->user->level_id === 1 ? $request->status : 'diperiksa';
            $noUrut                          = $request->user->level_id === 1 && $request->status == 'diterima' ? $this->generateNoUrut($siteId) : null;
            $verifier                        = $request->user->level_id === 1 && $request->status == 'diterima' ? $request->user->id : null;

            $newLks = Lks::create([
                'site_id'                           => $siteId,
                'year'                              => $request->year,
                'no_urut'                           => $noUrut,
                'nama'                              => $request->nama,
                'nama_ketua'                        => $request->nama_ketua,
                'alamat_jalan'                      => $request->alamat_jalan,
                'alamat_rt'                         => $request->alamat_rt,
                'alamat_rw'                         => $request->alamat_rw,
                'alamat_kelurahan'                  => $request->alamat_kelurahan,
                'alamat_kecamatan'                  => $request->alamat_kecamatan,
                'no_telp_yayasan'                   => $request->no_telp_yayasan,
                'jenis_layanan'                     => $request->jenis_layanan,
                'akta_pendirian_nomor'              => $request->akta_pendirian_nomor,
                'akta_pendirian_tgl'                => $akta_pendirian_tgl,
                'sk_hukumham_pendirian_nomor'       => $request->sk_hukumham_pendirian_nomor,
                'sk_hukumham_pendirian_tgl'         => $sk_hukumham_pendirian_tgl,
                'akta_perubahan_nomor'              => $request->akta_perubahan_nomor,
                'akta_perubahan_tgl'                => $akta_perubahan_tgl,
                'sk_hukumham_perubahan_nomor'       => $request->sk_hukumham_perubahan_nomor,
                'sk_hukumham_perubahan_tgl'         => $sk_hukumham_perubahan_tgl,
                'npwp'                              => $request->npwp,
                'sk_domisili_yayasan_nomor'         => $request->sk_domisili_yayasan_nomor,
                'sk_domisili_yayasan_tgl_terbit'    => $sk_domisili_yayasan_tgl_terbit,
                'sk_domisili_yayasan_masa_berlaku'  => $request->sk_domisili_yayasan_masa_berlaku,
                'tanda_daftar_yayasan_nomor'        => $request->tanda_daftar_yayasan_nomor,
                'tanda_daftar_yayasan_tgl_terbit'   => $tanda_daftar_yayasan_tgl_terbit,
                'tanda_daftar_yayasan_masa_berlaku' => $request->tanda_daftar_yayasan_masa_berlaku,
                'izin_kegiatan_yayasan_nomor'       => $request->izin_kegiatan_yayasan_nomor,
                'izin_kegiatan_yayasan_tgl_terbit'  => $izin_kegiatan_yayasan_tgl_terbit,
                'izin_kegiatan_yayasan_masa_berlaku'=> $request->izin_kegiatan_yayasan_masa_berlaku,
                'induk_berusaha_nomor'              => $request->induk_berusaha_nomor,
                'induk_berusaha_tgl'                => $induk_berusaha_tgl,
                'akreditasi'                        => $request->akreditasi,
                'akreditasi_tgl'                    => $akreditasi_tgl,
                'status'                            => $status,
                'inputter'                          => $request->user->id,
                'verifier'                          => $verifier,
            ]);

            return response()->json(
                [
                    'message' => 'LKS berhasil dibuat',
                    'data'    => $newLks
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function importLks(LksRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $file = $request->file('file_lks');

            $spreadsheet = IOFactory::load($file);
            $worksheet   = $spreadsheet->getActiveSheet();
            $rows        = $worksheet->toArray();

            $rowCount = 0;
            $rowAdded = 0;

            foreach ($rows as $row) {
                $rowCount++;

                if ($rowCount > 2) {
                    $newLks = Lks::create([
                        'site_id'                           => $request->site_id,
                        'year'                              => $request->year,
                        'no_urut'                           => $this->generateNoUrut($request->site_id),
                        'nama'                              => $row[1],
                        'nama_ketua'                        => $row[2],
                        'alamat_jalan'                      => $row[3],
                        'alamat_rt'                         => $row[4],
                        'alamat_rw'                         => $row[5],
                        'alamat_kelurahan'                  => $row[6],
                        'alamat_kecamatan'                  => $row[7],
                        'no_telp_yayasan'                   => $row[8],
                        'jenis_layanan'                     => $row[9],
                        'akta_pendirian_nomor'              => $row[10],
                        'akta_pendirian_tgl'                => date("d-m-Y", strtotime($row[11])),
                        'sk_hukumham_pendirian_nomor'       => $row[12],
                        'sk_hukumham_pendirian_tgl'         => date("d-m-Y", strtotime($row[13])),
                        'akta_perubahan_nomor'              => $row[14],
                        'akta_perubahan_tgl'                => date("d-m-Y", strtotime($row[15])),
                        'sk_hukumham_perubahan_nomor'       => $row[16],
                        'sk_hukumham_perubahan_tgl'         => date("d-m-Y", strtotime($row[17])),
                        'npwp'                              => $row[18],
                        'sk_domisili_yayasan_nomor'         => $row[19],
                        'sk_domisili_yayasan_tgl_terbit'    => date("d-m-Y", strtotime($row[20])),
                        'sk_domisili_yayasan_masa_berlaku'  => $row[21],
                        'tanda_daftar_yayasan_nomor'        => $row[22],
                        'tanda_daftar_yayasan_tgl_terbit'   => date("d-m-Y", strtotime($row[23])),
                        'tanda_daftar_yayasan_masa_berlaku' => $row[24],
                        'izin_kegiatan_yayasan_nomor'       => $row[25],
                        'izin_kegiatan_yayasan_tgl_terbit'  => date("d-m-Y", strtotime($row[26])),
                        'izin_kegiatan_yayasan_masa_berlaku'=> $row[27],
                        'induk_berusaha_nomor'              => $row[28],
                        'induk_berusaha_tgl'                => date("d-m-Y", strtotime($row[29])),
                        'akreditasi'                        => $row[30],
                        'akreditasi_tgl'                    => date("d-m-Y", strtotime($row[31])),
                        'status'                            => 'diterima',
                        'inputter'                          => $request->user->id,
                        'verifier'                          => $request->user->id,
                    ]);

                    $rowAdded++;
                }
            }

            DB::commit();

            return response()->json(
                [
                    'message' => "$rowAdded baris LKS berhasil ditambah",
                    'data'    => []
                ],
                201
            );
        }
        catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    public function verifLks(LksRequest $request): JsonResponse
    {
        try {
            $siteId = $request->user->site_id;
            $noUrut = $request->status == 'diterima' ? $this->generateNoUrut($siteId) : null;

            $newLks = Lks::where('id', $request->id)->update([
                'no_urut'  => $noUrut,
                'status'   => $request->status,
                'verifier' => $request->user->id,
            ]);

            return response()->json(
                [
                    'message' => 'LKS berhasil disimpan',
                    'data'    => $newLks
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateLks(LksRequest $request): JsonResponse
    {
        try {
            $akta_pendirian_tgl              = date("d-m-Y", strtotime($request->akta_pendirian_tgl));
            $sk_hukumham_pendirian_tgl       = date("d-m-Y", strtotime($request->sk_hukumham_pendirian_tgl));
            $akta_perubahan_tgl              = date("d-m-Y", strtotime($request->akta_perubahan_tgl));
            $sk_hukumham_perubahan_tgl       = date("d-m-Y", strtotime($request->sk_hukumham_perubahan_tgl));
            $sk_domisili_yayasan_tgl_terbit  = date("d-m-Y", strtotime($request->sk_domisili_yayasan_tgl_terbit));
            $tanda_daftar_yayasan_tgl_terbit = date("d-m-Y", strtotime($request->tanda_daftar_yayasan_tgl_terbit));
            $izin_kegiatan_yayasan_tgl_terbit= date("d-m-Y", strtotime($request->izin_kegiatan_yayasan_tgl_terbit));
            $induk_berusaha_tgl              = date("d-m-Y", strtotime($request->induk_berusaha_tgl));
            $akreditasi_tgl                  = date("d-m-Y", strtotime($request->akreditasi_tgl));
            $verifier                        = $request->status == 'diproses' ? null : $request->user->id;

            $LKS  = Lks::where("id", $request->id)->first();
            $data = [
                'site_id'                           => $request->site_id,
                'year'                              => $request->year,
                'nama'                              => $request->nama,
                'nama_ketua'                        => $request->nama_ketua,
                'alamat_jalan'                      => $request->alamat_jalan,
                'alamat_rt'                         => $request->alamat_rt,
                'alamat_rw'                         => $request->alamat_rw,
                'alamat_kelurahan'                  => $request->alamat_kelurahan,
                'alamat_kecamatan'                  => $request->alamat_kecamatan,
                'no_telp_yayasan'                   => $request->no_telp_yayasan,
                'jenis_layanan'                     => $request->jenis_layanan,
                'akta_pendirian_nomor'              => $request->akta_pendirian_nomor,
                'akta_pendirian_tgl'                => $akta_pendirian_tgl,
                'sk_hukumham_pendirian_nomor'       => $request->sk_hukumham_pendirian_nomor,
                'sk_hukumham_pendirian_tgl'         => $sk_hukumham_pendirian_tgl,
                'akta_perubahan_nomor'              => $request->akta_perubahan_nomor,
                'akta_perubahan_tgl'                => $akta_perubahan_tgl,
                'sk_hukumham_perubahan_nomor'       => $request->sk_hukumham_perubahan_nomor,
                'sk_hukumham_perubahan_tgl'         => $sk_hukumham_perubahan_tgl,
                'npwp'                              => $request->npwp,
                'sk_domisili_yayasan_nomor'         => $request->sk_domisili_yayasan_nomor,
                'sk_domisili_yayasan_tgl_terbit'    => $sk_domisili_yayasan_tgl_terbit,
                'sk_domisili_yayasan_masa_berlaku'  => $request->sk_domisili_yayasan_masa_berlaku,
                'tanda_daftar_yayasan_nomor'        => $request->tanda_daftar_yayasan_nomor,
                'tanda_daftar_yayasan_tgl_terbit'   => $tanda_daftar_yayasan_tgl_terbit,
                'tanda_daftar_yayasan_masa_berlaku' => $request->tanda_daftar_yayasan_masa_berlaku,
                'izin_kegiatan_yayasan_nomor'       => $request->izin_kegiatan_yayasan_nomor,
                'izin_kegiatan_yayasan_tgl_terbit'  => $izin_kegiatan_yayasan_tgl_terbit,
                'izin_kegiatan_yayasan_masa_berlaku'=> $request->izin_kegiatan_yayasan_masa_berlaku,
                'induk_berusaha_nomor'              => $request->induk_berusaha_nomor,
                'induk_berusaha_tgl'                => $induk_berusaha_tgl,
                'akreditasi'                        => $request->akreditasi,
                'akreditasi_tgl'                    => $akreditasi_tgl,
                'status'                            => $request->status,
                'verifier'                          => $verifier,
            ];

            if ($LKS->no_urut && $LKS->status == 'diterima' && $request->status == 'ditolak') {
                throw new GeneralException('data sudah diterima, status tidak dapat diubah', 409);
            }
            if ($LKS->no_urut == null && $request->status == 'diterima') {
                $data['no_urut'] = $this->generateNoUrut($request->site_id);
            }

            $newLks = Lks::where("id", $request->id)->update($data);

            return response()->json(
                [
                    'message' => 'LKS berhasil diubah',
                    'data'    => $newLks
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function generateNoUrut(string $siteId)
    {
        $lastRecord = Lks::where('site_id', $siteId)->latest('no_urut')->first();
        return $lastRecord ? $lastRecord->no_urut + 1 : 1;
    }
}
