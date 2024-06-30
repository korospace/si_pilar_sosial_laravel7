<?php

namespace App\Services\Impl;

use App\Exceptions\GeneralException;
use App\Http\Requests\TkskRequest;
use App\Models\LogStatus;
use App\Models\Tksk;
use App\Services\TkskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TkskServiceImpl implements TkskService
{
    public function getTkskDataTable(TkskRequest $request): JsonResponse
    {
        try {
            $no = 1;
            $tkskRows = Tksk::query();

            // filter
            if ($request->user->site) {
                $tkskRows->where('site_id', $request->user->site_id);
            }
            if ($request->year) {
                $tkskRows->where("year", $request->year);
            }
            if ($request->site_id) {
                $tkskRows->where('site_id', $request->site_id);
            }
            if ($request->status) {
                $tkskRows->where('status', $request->status);
            }
            if ($request->non_aktif == "0") {
                $tkskRows->where('status', "!=", "nonaktif");
            }
            else if ($request->non_aktif == "1") {
                $tkskRows->where('status', "=", "nonaktif");
            }

            $tkskRows = $tkskRows->select('id', 'year', 'site_id', 'no_urut', 'no_induk_anggota', 'nama', 'tempat_tugas', 'jenis_kelamin', 'status')->orderBy('id', 'DESC')->with('site')->get();

            return datatables()->of($tkskRows)
                ->addColumn('no', function ($row) use (&$no) {
                    return $no++;
                })
                ->addColumn('no_urut', function ($row) {
                    $html = $row->no_urut ? str_replace(".", "", $row->site->region_id) . str_pad($row->no_urut, 5, "0", STR_PAD_LEFT) : '-';

                    return $html;
                })
                ->addColumn('tempat_tugas', function ($row) {
                    return $row->site->name . " - " . $row->tempat_tugas;
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
                        <a href="'.route('tksk.update', $row->id).'" class="btn btn-sm bg-info">
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

    public function getTkskInfoStatus(TkskRequest $request): JsonResponse
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
                    (SELECT COUNT(id) FROM tksk WHERE status = 'diterima' $whereSite1) as diterima,
                    (SELECT COUNT(id) FROM tksk WHERE status = 'ditolak' $whereSite1) as ditolak,
                    (SELECT COUNT(id) FROM tksk WHERE status = 'diperiksa' $whereSite1) as diperiksa,
                    (SELECT COUNT(id) FROM tksk WHERE status = 'nonaktif' $whereSite1) as nonaktif,
                    COUNT(*) as total
                FROM
                    tksk
                $whereSite2;
            ");

            return response()->json($result, 200);
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function createTksk(TkskRequest $request): JsonResponse
    {
        try {
            $siteId   = $request->user->level_id === 1 ? $request->site_id : $request->user->site_id;
            $tglLahir = date("d-m-Y", strtotime(MonthToEnglish($request->tanggal_lahir)));
            $status   = $request->user->level_id === 1 ? $request->status : 'diperiksa';
            $noUrut   = $request->user->level_id === 1 && $request->status == 'diterima' ? $this->generateNoUrut($siteId) : null;
            $verifier = $request->user->level_id === 1 && $request->status == 'diterima' ? $request->user->id : null;

            $newTksk = Tksk::create([
                'site_id'                       => $siteId,
                'year'                          => $request->year,
                'no_urut'                       => $noUrut,
                'no_induk_anggota'              => $request->no_induk_anggota,
                'tempat_tugas'                  => $request->tempat_tugas,
                'nama'                          => $request->nama,
                'nama_ibu_kandung'              => $request->nama_ibu_kandung,
                'nik'                           => $request->nik,
                'tempat_lahir'                  => $request->tempat_lahir,
                'tanggal_lahir'                 => $tglLahir,
                'pendidikan_terakhir'           => $request->pendidikan_terakhir,
                'jenis_kelamin'                 => $request->jenis_kelamin,
                'alamat_jalan'                  => $request->alamat_jalan,
                'alamat_rt'                     => $request->alamat_rt,
                'alamat_rw'                     => $request->alamat_rw,
                'alamat_kelurahan'              => $request->alamat_kelurahan,
                'telepon'                       => $request->telepon,
                'nama_di_rekening'              => $request->nama_di_rekening,
                'no_rekening'                   => $request->no_rekening,
                'nama_bank'                     => $request->nama_bank,
                'tahun_pengangkatan_pertama'    => $request->tahun_pengangkatan_pertama,
                'nosk_pengangkatan_pertama'     => $request->nosk_pengangkatan_pertama,
                'pejabat_pengangkatan_pertama'  => $request->pejabat_pengangkatan_pertama,
                'tahun_pengangkatan_terakhir'   => $request->tahun_pengangkatan_terakhir,
                'nosk_pengangkatan_terakhir'    => $request->nosk_pengangkatan_terakhir,
                'pejabat_pengangkatan_terakhir' => $request->pejabat_pengangkatan_terakhir,
                'no_kartu_registrasi'           => $request->no_kartu_registrasi,
                'status'                        => $status,
                'inputter'                      => $request->user->id,
                'verifier'                      => $verifier,
            ]);

            return response()->json(
                [
                    'message' => 'TKSK berhasil dibuat',
                    'data'    => $newTksk
                ],
                201
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function importTksk(TkskRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $file = $request->file('file_tksk');

            $spreadsheet = IOFactory::load($file);
            $worksheet   = $spreadsheet->getActiveSheet();
            $rows        = $worksheet->toArray();

            $rowCount = 0;
            $rowAdded = 0;

            foreach ($rows as $row) {
                $rowCount++;

                if ($rowCount > 2) {
                    Tksk::create([
                        'site_id'                       => $request->site_id,
                        'year'                          => $request->year,
                        'no_urut'                       => $this->generateNoUrut($request->site_id),
                        'no_induk_anggota'              => $row[0],
                        'tempat_tugas'                  => $row[1],
                        'nama'                          => $row[2],
                        'nama_ibu_kandung'              => $row[3],
                        'nik'                           => $row[4],
                        'tempat_lahir'                  => $row[5],
                        'tanggal_lahir'                 => date("d-m-Y", strtotime($row[6])),
                        'jenis_kelamin'                 => $row[7],
                        'alamat_jalan'                  => $row[8],
                        'alamat_rt'                     => $row[9],
                        'alamat_rw'                     => $row[10],
                        'alamat_kelurahan'              => $row[11],
                        'telepon'                       => $row[12],
                        'pendidikan_terakhir'           => $row[13],
                        'tahun_pengangkatan_pertama'    => $row[14],
                        'nosk_pengangkatan_pertama'     => $row[15],
                        'pejabat_pengangkatan_pertama'  => $row[16],
                        'tahun_pengangkatan_terakhir'   => $row[17],
                        'nosk_pengangkatan_terakhir'    => $row[18],
                        'pejabat_pengangkatan_terakhir' => $row[19],
                        'nama_di_rekening'              => $row[20],
                        'no_rekening'                   => $row[21],
                        'nama_bank'                     => $row[22],
                        'no_kartu_registrasi'           => $row[23],
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
                    'message' => "$rowAdded baris TKSK berhasil ditambah",
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

    public function updateTksk(TkskRequest $request): JsonResponse
    {
        try {
            $tglLahir = date("d-m-Y", strtotime(MonthToEnglish($request->tanggal_lahir)));
            $verifier = $request->status == 'diproses' ? null : $request->user->id;

            $TKSK = Tksk::where("id", $request->id)->first();
            $data = [
                'site_id'                       => $request->site_id,
                'year'                          => $request->year,
                'no_induk_anggota'              => $request->no_induk_anggota,
                'tempat_tugas'                  => $request->tempat_tugas,
                'nama'                          => $request->nama,
                'nama_ibu_kandung'              => $request->nama_ibu_kandung,
                'nik'                           => $request->nik,
                'tempat_lahir'                  => $request->tempat_lahir,
                'tanggal_lahir'                 => $tglLahir,
                'pendidikan_terakhir'           => $request->pendidikan_terakhir,
                'jenis_kelamin'                 => $request->jenis_kelamin,
                'alamat_jalan'                  => $request->alamat_jalan,
                'alamat_rt'                     => $request->alamat_rt,
                'alamat_rw'                     => $request->alamat_rw,
                'alamat_kelurahan'              => $request->alamat_kelurahan,
                'telepon'                       => $request->telepon,
                'nama_di_rekening'              => $request->nama_di_rekening,
                'no_rekening'                   => $request->no_rekening,
                'nama_bank'                     => $request->nama_bank,
                'tahun_pengangkatan_pertama'    => $request->tahun_pengangkatan_pertama,
                'nosk_pengangkatan_pertama'     => $request->nosk_pengangkatan_pertama,
                'pejabat_pengangkatan_pertama'  => $request->pejabat_pengangkatan_pertama,
                'tahun_pengangkatan_terakhir'   => $request->tahun_pengangkatan_terakhir,
                'nosk_pengangkatan_terakhir'    => $request->nosk_pengangkatan_terakhir,
                'pejabat_pengangkatan_terakhir' => $request->pejabat_pengangkatan_terakhir,
                'no_kartu_registrasi'           => $request->no_kartu_registrasi,
                'status'                        => $request->status,
                'verifier'                      => $verifier,
            ];

            if ($TKSK->no_urut && $TKSK->status == 'diterima' && $request->status == 'ditolak') {
                throw new GeneralException('data sudah diterima, status tidak dapat diubah', 409);
            }
            if ($TKSK->no_urut == null && $request->status == 'diterima') {
                $data['no_urut'] = $this->generateNoUrut($request->site_id);
            }

            $newTksk = Tksk::where("id", $request->id)->update($data);

            return response()->json(
                [
                    'message' => 'TKSK berhasil diubah',
                    'data'    => $newTksk
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function verifTksk(TkskRequest $request): JsonResponse
    {
        try {
            $siteId = $request->user->site_id;
            $noUrut = $request->status == 'diterima' ? $this->generateNoUrut($siteId) : null;

            $newTksk = Tksk::where('id', $request->id)->update([
                'no_urut'  => $noUrut,
                'status'   => $request->status,
                'verifier' => $request->user->id,
            ]);

            return response()->json(
                [
                    'message' => 'verifikasi TKSK berhasil diubah',
                    'data'    => $newTksk
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updateStatus(TkskRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $newPsm = Tksk::where('id', $request->id)->update([
                'status'   => $request->status,
                'verifier' => $request->user->id,
            ]);

            LogStatus::create([
                "id_reference"      => $request->id,
                "table_reference"   => "tksk",
                "status"            => $request->status,
                "description"       => $request->description ? $request->description : "",
                'verifier'          => $request->user->id,
            ]);

            DB::commit();

            return response()->json(
                [
                    'message' => 'TKSK status berhasil diubah',
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

    protected function generateNoUrut(string $siteId)
    {
        $lastRecord = Tksk::where('site_id', $siteId)->latest('no_urut')->first();
        return $lastRecord ? $lastRecord->no_urut + 1 : 1;
    }
}
