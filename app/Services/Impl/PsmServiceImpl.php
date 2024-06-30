<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\PsmRequest;
use App\Models\LogStatus;
use App\Models\Psm;
use App\Models\Region;
use App\Models\Site;
use App\Services\PsmService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PsmServiceImpl implements PsmService
{
    public function getPsmDataTable(PsmRequest $request): JsonResponse
    {
        try {
            $no = 1;
            $psmRows = Psm::query();

            // filter
            if ($request->user->site) {
                $psmRows->where('site_id', $request->user->site_id);
            }
            if ($request->year) {
                $psmRows->where("year", $request->year);
            }
            if ($request->site_id) {
                $psmRows->where('site_id', $request->site_id);
            }
            if ($request->status) {
                $psmRows->where('status', $request->status);
            }
            if ($request->non_aktif == "0") {
                $psmRows->where('status', "!=", "nonaktif");
            }
            else if ($request->non_aktif == "1") {
                $psmRows->where('status', "=", "nonaktif");
            }

            $psmRows = $psmRows->select('id', 'year', 'site_id', 'no_urut', 'nama', 'kec_id', 'tempat_tugas_kecamatan', 'kel_id', 'tempat_tugas_kelurahan', 'jenis_kelamin', 'status')->orderBy('id', 'DESC')->with('site')->get();

            return datatables()->of($psmRows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('id_psm', function ($row) {
                    $html = $row->no_urut
                        ?
                            str_replace(".", "", $row->site->region_id) .
                            str_replace(".", "", $row->kec_id) .
                            str_replace(".", "", $row->kel_id) .
                            str_pad($row->no_urut, 3, "0", STR_PAD_LEFT)
                        : '-';

                    return $html;
                })
                ->addColumn('tempat_tugas', function ($row) {
                    $html = $row->tempat_tugas_kelurahan.", ".$row->tempat_tugas_kecamatan.", ".$row->site->name;

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
                        <a href="'.route('psm.update', $row->id).'" class="btn btn-sm bg-info">
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

    public function getPsmInfoStatus(PsmRequest $request): JsonResponse
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
                    (SELECT COUNT(id) FROM psm WHERE status = 'diterima' $whereSite1) as diterima,
                    (SELECT COUNT(id) FROM psm WHERE status = 'ditolak' $whereSite1) as ditolak,
                    (SELECT COUNT(id) FROM psm WHERE status = 'diperiksa' $whereSite1) as diperiksa,
                    (SELECT COUNT(id) FROM psm WHERE status = 'nonaktif' $whereSite1) as nonaktif,
                    COUNT(*) as total
                FROM
                    psm
                $whereSite2;
            ");

            return response()->json($result, 200);
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function createPsm(PsmRequest $request): JsonResponse
    {
        try {
            $siteId   = $request->user->level_id === 1 ? $request->site_id : $request->user->site_id;
            $tglLahir = date("d-m-Y", strtotime(MonthToEnglish($request->tanggal_lahir)));
            $status   = $request->user->level_id === 1 ? $request->status : 'diperiksa';
            $noUrut   = $request->user->level_id === 1 && $request->status == 'diterima' ? $this->generateNoUrut($siteId, $request->tempat_tugas_kecamatan, $request->tempat_tugas_kelurahan) : null;
            $verifier = $request->user->level_id === 1 && $request->status == 'diterima' ? $request->user->id : null;
            $site     = Site::where("id", $siteId)->first();

            $newPsm = Psm::create([
                'site_id'               => $siteId,
                'year'                  => $request->year,
                'nama'                  => $request->nama,
                'nik'                   => $request->nik,
                'tempat_lahir'          => $request->tempat_lahir,
                'tanggal_lahir'         => $tglLahir,
                'jenis_kelamin'         => $request->jenis_kelamin,
                'kel_id'                => $this->getKodeKelurahan($site->region_id, $request->tempat_tugas_kelurahan),
                'tempat_tugas_kelurahan'=> $request->tempat_tugas_kelurahan,
                'kec_id'                => $this->getKodeKecamatan($site->region_id, $request->tempat_tugas_kecamatan),
                'tempat_tugas_kecamatan'=> $request->tempat_tugas_kecamatan,
                'alamat_jalan'          => $request->alamat_jalan,
                'alamat_rt'             => $request->alamat_rt,
                'alamat_rw'             => $request->alamat_rw,
                'tingkatan_diklat'      => $request->tingkatan_diklat,
                'sertifikasi_status'    => $request->sertifikasi_status,
                'sertifikasi_tahun'     => $request->sertifikasi_tahun,
                'telepon'               => $request->telepon,
                'pendidikan_terakhir'   => $request->pendidikan_terakhir,
                'kondisi_existing'      => $request->kondisi_existing,
                'status'                => $status,
                'no_urut'               => $noUrut,
                'inputter'              => $request->user->id,
                'verifier'              => $verifier,
            ]);

            return response()->json(
                [
                    'message' => 'PSM berhasil dibuat',
                    'data'    => $newPsm
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function importPsm(PsmRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $file = $request->file('file_psm');

            $spreadsheet = IOFactory::load($file);
            $worksheet   = $spreadsheet->getActiveSheet();
            $rows        = $worksheet->toArray();

            $rowCount = 0;
            $rowAdded = 0;

            $site = Site::where("id", $request->site_id)->first();

            foreach ($rows as $row) {
                $rowCount++;

                if ($rowCount > 2) {
                    $newPsm = Psm::create([
                        'site_id'               => $request->site_id,
                        'year'                  => $request->year,
                        'no_urut'               => $this->generateNoUrut($request->site_id, $row[6], $row[7]),
                        'nama'                  => $row[1],
                        'nik'                   => $row[2],
                        'tempat_lahir'          => $row[3],
                        'tanggal_lahir'         => date("d-m-Y", strtotime($row[4])),
                        'jenis_kelamin'         => $row[5],
                        'kec_id'                => $this->getKodeKecamatan($site->region_id, $row[6]),
                        'tempat_tugas_kecamatan'=> $row[6],
                        'kel_id'                => $this->getKodeKelurahan($site->region_id, $row[7]),
                        'tempat_tugas_kelurahan'=> $row[7],
                        'alamat_jalan'          => $row[8],
                        'alamat_rt'             => $row[9],
                        'alamat_rw'             => $row[10],
                        'tingkatan_diklat'      => $row[11],
                        'sertifikasi_status'    => $row[12],
                        'sertifikasi_tahun'     => $row[13],
                        'telepon'               => $row[14],
                        'pendidikan_terakhir'   => $row[15],
                        'kondisi_existing'      => $row[16],
                        'status'                => 'diterima',
                        'inputter'              => $request->user->id,
                        'verifier'              => $request->user->id,
                    ]);

                    $rowAdded++;
                }
            }

            DB::commit();

            return response()->json(
                [
                    'message' => "$rowAdded baris PSM berhasil ditambah",
                    'data'    => []
                ],
                201
            );
        }
        catch (\Throwable $th) {
            DB::rollback();
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updatePsm(PsmRequest $request): JsonResponse
    {
        try {
            $tglLahir = date("d-m-Y", strtotime(MonthToEnglish($request->tanggal_lahir)));
            $verifier = $request->status == 'diproses' ? null : $request->user->id;

            $site = Site::where("id", $request->site_id)->first();
            $PSM  = Psm::where("id", $request->id)->first();
            $data = [
                'site_id'                       => $request->site_id,
                'year'                          => $request->year,
                'nama'                          => $request->nama,
                'nik'                           => $request->nik,
                'tempat_lahir'                  => $request->tempat_lahir,
                'tanggal_lahir'                 => $tglLahir,
                'jenis_kelamin'                 => $request->jenis_kelamin,
                'tempat_tugas_kelurahan'        => $request->tempat_tugas_kelurahan,
                'tempat_tugas_kecamatan'        => $request->tempat_tugas_kecamatan,
                'alamat_jalan'                  => $request->alamat_jalan,
                'alamat_rt'                     => $request->alamat_rt,
                'alamat_rw'                     => $request->alamat_rw,
                'tingkatan_diklat'              => $request->tingkatan_diklat,
                'sertifikasi_status'            => $request->sertifikasi_status,
                'sertifikasi_tahun'             => $request->sertifikasi_tahun,
                'telepon'                       => $request->telepon,
                'pendidikan_terakhir'           => $request->pendidikan_terakhir,
                'kondisi_existing'              => $request->kondisi_existing,
                'status'                        => $request->status,
                'verifier'                      => $verifier,
            ];

            if ($PSM->no_urut && $PSM->status == 'diterima' && $request->site_id != $PSM->site_id) {
                throw new GeneralException('data sudah diterima, site tidak dapat diubah', 409);
            }
            if ($request->site_id != $PSM->site_id) {
                $data['kel_id'] = $this->getKodeKelurahan($site->region_id, $request->tempat_tugas_kelurahan);
                $data['kec_id'] = $this->getKodeKecamatan($site->region_id, $request->tempat_tugas_kecamatan);
            }
            if ($PSM->no_urut && $PSM->status == 'diterima' && $request->status == 'ditolak') {
                throw new GeneralException('data sudah diterima, status tidak dapat diubah', 409);
            }
            if ($PSM->no_urut == null && $request->status == 'diterima') {
                $data['no_urut'] = $this->generateNoUrut($request->site_id, $PSM->tempat_tugas_kecamatan, $PSM->tempat_tugas_kelurahan);
            }

            $newPsm = Psm::where("id", $request->id)->update($data);

            return response()->json(
                [
                    'message' => 'PSM berhasil diubah',
                    'data'    => $newPsm
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function verifPsm(PsmRequest $request): JsonResponse
    {
        try {
            $psm    = Psm::where("id", $request->id)->first();
            $siteId = $request->user->site_id;
            $noUrut = $request->status == 'diterima' ? $this->generateNoUrut($siteId, $psm->tempat_tugas_kecamatan, $psm->tempat_tugas_kelurahan) : null;

            $newPsm = Psm::where('id', $request->id)->update([
                'no_urut'  => $noUrut,
                'status'   => $request->status,
                'verifier' => $request->user->id,
            ]);

            return response()->json(
                [
                    'message' => 'verifikasi PSM berhasil diubah',
                    'data'    => $newPsm
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updateStatus(PsmRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $newPsm = Psm::where('id', $request->id)->update([
                'status'   => $request->status,
                'verifier' => $request->user->id,
            ]);

            LogStatus::create([
                "id_reference"      => $request->id,
                "table_reference"   => "psm",
                "status"            => $request->status,
                "description"       => $request->description ? $request->description : "",
                'verifier'          => $request->user->id,
            ]);

            DB::commit();

            return response()->json(
                [
                    'message' => 'PSM status berhasil diubah',
                    'data'    => $newPsm
                ],
                200
            );
        }
        catch (\Throwable $th) {
            DB::rollback();
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    protected function generateNoUrut(string $siteId, string $kecamatan, string $kelurahan)
    {
        $lastRecord = Psm::where("site_id", $siteId)
            ->where('tempat_tugas_kecamatan', 'LIKE', '%' . $kecamatan . '%')
            ->where('tempat_tugas_kelurahan', 'LIKE', '%' . $kelurahan . '%')
            ->latest('no_urut')
            ->first();

        return $lastRecord ? $lastRecord->no_urut + 1 : 1;
    }

    protected function getKodeKelurahan(string $regionId, string $nama)
    {
        $data = Region::where('type', 'kelurahan')
            ->where('id', 'LIKE', $regionId . '%')
            ->where('name', 'LIKE', '%' . $nama . '%')
            ->first();

        if ($data) {
            return $data->kel_id;
        } else {
            return "";
        }
    }

    protected function getKodeKecamatan(string $regionId, string $nama)
    {
        $data = Region::where('type', 'kecamatan')
            ->where('id', 'LIKE', $regionId . '%')
            ->where('name', 'LIKE', '%' . $nama . '%')
            ->first();

        if ($data) {
            return $data->kec_id;
        } else {
            return "";
        }
    }
}
