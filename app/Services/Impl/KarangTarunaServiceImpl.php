<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\KarangTarunaRequest;
use App\Models\KarangTaruna;
use App\Services\KarangTarunaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KarangTarunaServiceImpl implements KarangTarunaService
{
    public function getKarangTarunaDataTable(KarangTarunaRequest $request): JsonResponse
    {
        try {
            $no = 1;
            $rows = KarangTaruna::query();

            // filter
            if ($request->user->site) {
                $rows->where('site_id', $request->user->site_id);
            }
            if ($request->year) {
                $rows->where("year", $request->year);
            }
            if ($request->site_id) {
                $rows->where('site_id', $request->site_id);
            }
            if ($request->status) {
                $rows->where('status', $request->status);
            }

            $rows = $rows->select('id', 'year', 'site_id', 'no_urut', 'nama', 'nama_ketua', 'program_unggulan', 'status')->orderBy('id', 'DESC')->with('site')->get();

            return datatables()->of($rows)
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
                        <a href="'.route('karang_taruna.update', $row->id).'" class="btn btn-sm bg-info">
                            <i class="fas fa-eye"></i>
                        </a>
                    ';

                    return $html;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getKarangTarunaInfoStatus(KarangTarunaRequest $request): JsonResponse
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
                    (SELECT COUNT(id) FROM karang_taruna WHERE status = 'diterima' $whereSite1) as diterima,
                    (SELECT COUNT(id) FROM karang_taruna WHERE status = 'ditolak' $whereSite1) as ditolak,
                    (SELECT COUNT(id) FROM karang_taruna WHERE status = 'diperiksa' $whereSite1) as diperiksa,
                    COUNT(*) as total
                FROM
                    karang_taruna
                $whereSite2;
            ");

            return response()->json($result, 200);
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createKarangTaruna(KarangTarunaRequest $request): JsonResponse
    {
        try {
            $siteId   = $request->user->level_id === 1 ? $request->site_id : $request->user->site_id;
            $skTgl    = $request->kepengurusan_sk_tgl ? date("d-m-Y", strtotime($request->kepengurusan_sk_tgl)) : null;
            $status   = $request->user->level_id === 1 ? $request->status : 'diperiksa';
            $noUrut   = $request->user->level_id === 1 && $request->status == 'diterima' ? $this->generateNoUrut($siteId) : null;
            $verifier = $request->user->level_id === 1 && $request->status == 'diterima' ? $request->user->id : null;

            $newKarangTaruna = KarangTaruna::create([
                'site_id'                       => $siteId,
                'year'                          => $request->year,
                'no_urut'                       => $noUrut,
                'nama'                          => $request->nama,
                'nama_ketua'                    => $request->nama_ketua,
                'alamat_jalan'                  => $request->alamat_jalan,
                'alamat_rt'                     => $request->alamat_rt,
                'alamat_rw'                     => $request->alamat_rw,
                'alamat_kelurahan'              => $request->alamat_kelurahan,
                'alamat_kecamatan'              => $request->alamat_kecamatan,
                'telepon'                       => $request->telepon,
                'kepengurusan_status'           => $request->kepengurusan_status,
                'kepengurusan_sk_tgl'           => $skTgl,
                'kepengurusan_periode_tahun'    => $request->kepengurusan_periode_tahun ? $request->kepengurusan_periode_tahun : null,
                'kepengurusan_jumlah'           => $request->kepengurusan_jumlah ? $request->kepengurusan_jumlah : null,
                'keaktifan_status'              => $request->keaktifan_status,
                'program_unggulan'              => $request->program_unggulan,
                'status'                        => $status,
                'inputter'                      => $request->user->id,
                'verifier'                      => $verifier,
            ]);

            return response()->json(
                [
                    'message' => 'KARANG TARUNA berhasil dibuat',
                    'data'    => $newKarangTaruna
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function importKarangTaruna(KarangTarunaRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $file = $request->file('file_karang_taruna');

            $spreadsheet = IOFactory::load($file);
            $worksheet   = $spreadsheet->getActiveSheet();
            $rows        = $worksheet->toArray();

            $rowCount = 0;
            $rowAdded = 0;

            foreach ($rows as $row) {
                $rowCount++;

                if ($rowCount > 2) {
                    KarangTaruna::create([
                        'site_id'                       => $request->site_id,
                        'year'                          => $request->year,
                        'no_urut'                       => $this->generateNoUrut($request->site_id),
                        'nama'                          => $row[1],
                        'nama_ketua'                    => $row[2],
                        'alamat_jalan'                  => $row[3],
                        'alamat_rt'                     => $row[4],
                        'alamat_rw'                     => $row[5],
                        'alamat_kelurahan'              => $row[6],
                        'alamat_kecamatan'              => $row[7],
                        'telepon'                       => $row[8],
                        'kepengurusan_status'           => $row[9],
                        'kepengurusan_sk_tgl'           => date("d-m-Y", strtotime($row[10])),
                        'kepengurusan_periode_tahun'    => $row[11],
                        'kepengurusan_jumlah'           => $row[12],
                        'keaktifan_status'              => $row[13],
                        'program_unggulan'              => $row[14],
                        'status'                        => 'diterima',
                        'inputter'                      => $request->user->id,
                        'verifier'                      => $request->user->id,
                    ]);

                    $rowAdded++;
                }
            }

            DB::commit();

            return response()->json(
                [
                    'message' => "$rowAdded baris KARANG TARUNA berhasil ditambah",
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

    public function verifKarangTaruna(KarangTarunaRequest $request): JsonResponse
    {
        try {
            $siteId = $request->user->site_id;
            $noUrut = $request->status == 'diterima' ? $this->generateNoUrut($siteId) : null;

            $newKarangTaruna = KarangTaruna::where('id', $request->id)->update([
                'no_urut'  => $noUrut,
                'status'   => $request->status,
                'verifier' => $request->user->id,
            ]);

            return response()->json(
                [
                    'message' => 'KARANG TARUNA berhasil disimpan',
                    'data'    => $newKarangTaruna
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateKarangTaruna(KarangTarunaRequest $request): JsonResponse
    {
        try {
            $karangTaruna = KarangTaruna::where("id", $request->id)->first();
            $skTgl    = $request->kepengurusan_sk_tgl ? date("d-m-Y", strtotime($request->kepengurusan_sk_tgl)) : null;
            $verifier = $request->status == 'diproses' ? null : $request->user->id;
            $data     = [
                'site_id'                       => $request->site_id,
                'year'                          => $request->year,
                'nama'                          => $request->nama,
                'nama_ketua'                    => $request->nama_ketua,
                'alamat_jalan'                  => $request->alamat_jalan,
                'alamat_rt'                     => $request->alamat_rt,
                'alamat_rw'                     => $request->alamat_rw,
                'alamat_kelurahan'              => $request->alamat_kelurahan,
                'alamat_kecamatan'              => $request->alamat_kecamatan,
                'telepon'                       => $request->telepon,
                'kepengurusan_status'           => $request->kepengurusan_status,
                'kepengurusan_sk_tgl'           => $skTgl,
                'kepengurusan_periode_tahun'    => $request->kepengurusan_periode_tahun ? $request->kepengurusan_periode_tahun : null,
                'kepengurusan_jumlah'           => $request->kepengurusan_jumlah ? $request->kepengurusan_jumlah : null,
                'keaktifan_status'              => $request->keaktifan_status,
                'program_unggulan'              => $request->program_unggulan,
                'status'                        => $request->status,
                'inputter'                      => $request->user->id,
                'verifier'                      => $verifier,
            ];

            if ($karangTaruna->no_urut && $karangTaruna->status == 'diterima' && $request->status == 'ditolak') {
                throw new GeneralException('data sudah diterima, status tidak dapat diubah', 409);
            }
            if ($karangTaruna->no_urut == null && $request->status == 'diterima') {
                $data['no_urut'] = $this->generateNoUrut($request->site_id);
            }

            KarangTaruna::where("id", $request->id)->update($data);

            return response()->json(
                [
                    'message' => 'KARANG TARUNA berhasil diubah',
                    'data'    => []
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
        $lastRecord = KarangTaruna::where('site_id', $siteId)->latest('no_urut')->first();
        return $lastRecord ? $lastRecord->no_urut + 1 : 1;
    }
}
