<?php

namespace App\Services\Impl;

use App\Excel\ExcelLks;
use App\Exceptions\GeneralException;
use App\Http\Requests\LksRequest;
use App\Models\Lks;
use App\Models\LogStatus;
use App\Services\LksService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
            if ($request->non_aktif == "0") {
                $lksRows->where('status', "!=", "nonaktif");
            }
            else if ($request->non_aktif == "1") {
                $lksRows->where('status', "=", "nonaktif");
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
                    (SELECT COUNT(id) FROM lks WHERE status = 'nonaktif' $whereSite1) as nonaktif,
                    COUNT(*) as total
                FROM
                    lks
                $whereSite2;
            ");

            return response()->json($result, 200);
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function downloadExcel(LksRequest $request): JsonResponse
    {
        try {
            // Get Rows
            $rows = Lks::select('id', 'no_urut', 'year', 'status', 'site_id', 'no_urut','year','status','nama','nama_ketua','alamat_jalan','alamat_rt','alamat_rw','alamat_kelurahan','alamat_kecamatan','no_telp_yayasan','jenis_layanan','jenis_lks','jumlah_wbs','jumlah_peksos','sk_domisili_yayasan_nomor','sk_domisili_yayasan_masaberlaku_mulai','sk_domisili_yayasan_masaberlaku_selesai','sk_domisili_yayasan_instansi_penerbit','tanda_daftar_yayasan_nomor','tanda_daftar_yayasan_masaberlaku_mulai','tanda_daftar_yayasan_masaberlaku_selesai','tanda_daftar_yayasan_instansi_penerbit','izin_kegiatan_yayasan_nomor','izin_kegiatan_yayasan_masaberlaku_mulai','izin_kegiatan_yayasan_masaberlaku_selesai','izin_kegiatan_yayasan_instansi_penerbit','induk_berusaha_status','induk_berusaha_nomor','induk_berusaha_tgl_terbit','induk_berusaha_instansi_penerbit','akreditasi','akreditasi_tgl')
                ->orderBy('id', 'ASC')
                ->with('site')
                ->get();

            // Modify Rows
            $newRows = [];
            foreach ($rows as $row) {
                $newRow = [
                    'no_urut'                   => $row->no_urut ? str_replace(".", "", $row->site->region_id) . str_pad($row->no_urut, 5, "0", STR_PAD_LEFT) : '-',
                    'year'                      => $row->year,
                    'status'                    => $row->status,
                    'wilayah'                   => $row->site->name,
                    'nama'                      => $row->nama,
                    'nama_ketua'                => $row->nama_ketua,
                    'alamat_jalan'              => $row->alamat_jalan,
                    'alamat_rt'                 => $row->alamat_rt,
                    'alamat_rw'                 => $row->alamat_rw,
                    'alamat_kelurahan'          => $row->alamat_kelurahan,
                    'alamat_kecamatan'          => $row->alamat_kecamatan,
                    'no_telp_yayasan'=> $row->no_telp_yayasan,
                    'jenis_layanan'=> $row->jenis_layanan,
                    'jenis_lks'=> $row->jenis_lks,
                    'jumlah_wbs'=> $row->jumlah_wbs,
                    'jumlah_peksos'=> $row->jumlah_peksos,
                    'sk_domisili_yayasan_nomor'=> $row->sk_domisili_yayasan_nomor,
                    'sk_domisili_yayasan_masaberlaku_mulai'=> $row->sk_domisili_yayasan_masaberlaku_mulai,
                    'sk_domisili_yayasan_masaberlaku_selesai'=> $row->sk_domisili_yayasan_masaberlaku_selesai,
                    'sk_domisili_yayasan_instansi_penerbit'=> $row->sk_domisili_yayasan_instansi_penerbit,
                    'tanda_daftar_yayasan_nomor'=> $row->tanda_daftar_yayasan_nomor,
                    'tanda_daftar_yayasan_masaberlaku_mulai'=> $row->tanda_daftar_yayasan_masaberlaku_mulai,
                    'tanda_daftar_yayasan_masaberlaku_selesai'=> $row->tanda_daftar_yayasan_masaberlaku_selesai,
                    'tanda_daftar_yayasan_instansi_penerbit'=> $row->tanda_daftar_yayasan_instansi_penerbit,
                    'izin_kegiatan_yayasan_nomor'=> $row->izin_kegiatan_yayasan_nomor,
                    'izin_kegiatan_yayasan_masaberlaku_mulai'=> $row->izin_kegiatan_yayasan_masaberlaku_mulai,
                    'izin_kegiatan_yayasan_masaberlaku_selesai'=> $row->izin_kegiatan_yayasan_masaberlaku_selesai,
                    'izin_kegiatan_yayasan_instansi_penerbit'=> $row->izin_kegiatan_yayasan_instansi_penerbit,
                    'induk_berusaha_status'=> $row->induk_berusaha_status,
                    'induk_berusaha_nomor'=> $row->induk_berusaha_nomor,
                    'induk_berusaha_tgl_terbit'=> $row->induk_berusaha_tgl_terbit,
                    'induk_berusaha_instansi_penerbit'=> $row->induk_berusaha_instansi_penerbit,
                    'akreditasi'=> $row->akreditasi,
                    'akreditasi_tgl'=> $row->akreditasi_tgl,
                ];
                $newRows[] = $newRow;
            }

            $data = [
                'sheet1' => $newRows
            ];

            $file_name = 'lks_download_'. date("YmdHis", time()) .'.xlsx';
            Excel::store(new ExcelLks($data), $file_name, 'excel'); // see config/filesystems.php

            return response()->json(
                [
                    'message' => 'excel berhasil dibuat',
                    'data'    => [
                        'url' => env("APP_URL") . '/api/v1/download_excel_tmp' . "?file_name=" . $file_name
                    ]
                ],
                200
            );
        } catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function createLks(LksRequest $request): JsonResponse
    {
        try {
            $siteId                                   = $request->user->level_id === 1 ? $request->site_id : $request->user->site_id;
            $sk_domisili_yayasan_masaberlaku_mulai    = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->sk_domisili_yayasan_masaberlaku_mulai)));
            $sk_domisili_yayasan_masaberlaku_selesai  = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->sk_domisili_yayasan_masaberlaku_selesai)));
            $tanda_daftar_yayasan_masaberlaku_mulai   = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->tanda_daftar_yayasan_masaberlaku_mulai)));
            $tanda_daftar_yayasan_masaberlaku_selesai = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->tanda_daftar_yayasan_masaberlaku_selesai)));
            $izin_kegiatan_yayasan_masaberlaku_mulai  = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->izin_kegiatan_yayasan_masaberlaku_mulai)));
            $izin_kegiatan_yayasan_masaberlaku_selesai= date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->izin_kegiatan_yayasan_masaberlaku_selesai)));
            $induk_berusaha_tgl_terbit                = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->induk_berusaha_tgl_terbit)));
            $akreditasi_tgl                           = date("d-m-Y", strtotime(MonthToEnglish($request->akreditasi_tgl)));
            $status                                   = $request->user->level_id === 1 ? $request->status : 'diperiksa';
            $noUrut                                   = $request->user->level_id === 1 && $request->status == 'diterima' ? $this->generateNoUrut($siteId) : null;
            $verifier                                 = $request->user->level_id === 1 && $request->status == 'diterima' ? $request->user->id : null;

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
                'jenis_lks'                         => $request->jenis_lks,
                'jumlah_wbs'                        => $request->jumlah_wbs,
                'jumlah_peksos'                     => $request->jumlah_peksos,
                'sk_domisili_yayasan_nomor'                 => $request->sk_domisili_yayasan_nomor,
                'sk_domisili_yayasan_masaberlaku_mulai'     => $sk_domisili_yayasan_masaberlaku_mulai,
                'sk_domisili_yayasan_masaberlaku_selesai'   => $sk_domisili_yayasan_masaberlaku_selesai,
                'sk_domisili_yayasan_instansi_penerbit'     => $request->sk_domisili_yayasan_instansi_penerbit,
                'tanda_daftar_yayasan_nomor'                => $request->tanda_daftar_yayasan_nomor,
                'tanda_daftar_yayasan_masaberlaku_mulai'    => $tanda_daftar_yayasan_masaberlaku_mulai,
                'tanda_daftar_yayasan_masaberlaku_selesai'  => $tanda_daftar_yayasan_masaberlaku_selesai,
                'tanda_daftar_yayasan_instansi_penerbit'    => $request->tanda_daftar_yayasan_instansi_penerbit,
                'izin_kegiatan_yayasan_nomor'               => $request->izin_kegiatan_yayasan_nomor,
                'izin_kegiatan_yayasan_masaberlaku_mulai'   => $izin_kegiatan_yayasan_masaberlaku_mulai,
                'izin_kegiatan_yayasan_masaberlaku_selesai' => $izin_kegiatan_yayasan_masaberlaku_selesai,
                'izin_kegiatan_yayasan_instansi_penerbit'   => $request->izin_kegiatan_yayasan_instansi_penerbit,
                'induk_berusaha_status'             => $request->induk_berusaha_status,
                'induk_berusaha_nomor'              => $request->induk_berusaha_nomor,
                'induk_berusaha_tgl_terbit'         => $induk_berusaha_tgl_terbit,
                'induk_berusaha_instansi_penerbit'  => $request->induk_berusaha_instansi_penerbit,
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
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function importLks(LksRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $file   = $request->file('file_lks');
            $status = $request->user->level_id == 1 ? $request->status : "diperiksa";
            $siteId = $request->user->level_id == 1 ? $request->site_id : $request->user->site_id;

            $spreadsheet = IOFactory::load($file);
            $worksheet   = $spreadsheet->getActiveSheet();
            $rows        = $worksheet->toArray();

            $rowCount = 0;
            $rowAdded = 0;

            foreach ($rows as $row) {
                $rowCount++;

                if ($rowCount > 2) {
                    $noUrut = ($request->user->level_id == 1) ? 
                        (($status == "diterima") ? $this->generateNoUrut($siteId) : null) : 
                        null;

                    Lks::create([
                        'site_id'                           => $siteId,
                        'year'                              => $request->year,
                        'no_urut'                           => $noUrut,
                        'nama'                              => $row[0],
                        'nama_ketua'                        => $row[1],
                        'alamat_jalan'                      => $row[2],
                        'alamat_rt'                         => $row[3],
                        'alamat_rw'                         => $row[4],
                        'alamat_kelurahan'                  => $row[5],
                        'alamat_kecamatan'                  => $row[6],
                        'no_telp_yayasan'                   => $row[7],
                        'jenis_layanan'                     => $row[8],
                        'jenis_lks'                         => $row[9],
                        'jumlah_wbs'                        => $row[10],
                        'jumlah_peksos'                     => $row[11],
                        'sk_domisili_yayasan_nomor'                 => $row[12],
                        'sk_domisili_yayasan_masaberlaku_mulai'     => date("Y-m-d H:i:s", strtotime($row[13])),
                        'sk_domisili_yayasan_masaberlaku_selesai'   => date("Y-m-d H:i:s", strtotime($row[14])),
                        'sk_domisili_yayasan_instansi_penerbit'     => $row[15],
                        'tanda_daftar_yayasan_nomor'                => $row[16],
                        'tanda_daftar_yayasan_masaberlaku_mulai'    => date("Y-m-d H:i:s", strtotime($row[17])),
                        'tanda_daftar_yayasan_masaberlaku_selesai'  => date("Y-m-d H:i:s", strtotime($row[18])),
                        'tanda_daftar_yayasan_instansi_penerbit'    => $row[19],
                        'izin_kegiatan_yayasan_nomor'               => $row[20],
                        'izin_kegiatan_yayasan_masaberlaku_mulai'   => date("Y-m-d H:i:s", strtotime($row[21])),
                        'izin_kegiatan_yayasan_masaberlaku_selesai' => date("Y-m-d H:i:s", strtotime($row[22])),
                        'izin_kegiatan_yayasan_instansi_penerbit'   => $row[23],
                        'induk_berusaha_status'             => $row[24],
                        'induk_berusaha_nomor'              => $row[24] == "ada" ? $row[25] : null,
                        'induk_berusaha_tgl_terbit'         => $row[24] == "ada" ? date("Y-m-d H:i:s", strtotime($row[26])) : null,
                        'induk_berusaha_instansi_penerbit'  => $row[24] == "ada" ? $row[27] : null,
                        'akreditasi'                        => $row[28],
                        'akreditasi_tgl'                    => date("d-m-Y", strtotime($row[29])),
                        'status'                            => $status,
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
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updateLks(LksRequest $request): JsonResponse
    {
        try {
            $sk_domisili_yayasan_masaberlaku_mulai    = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->sk_domisili_yayasan_masaberlaku_mulai)));
            $sk_domisili_yayasan_masaberlaku_selesai  = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->sk_domisili_yayasan_masaberlaku_selesai)));
            $tanda_daftar_yayasan_masaberlaku_mulai   = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->tanda_daftar_yayasan_masaberlaku_mulai)));
            $tanda_daftar_yayasan_masaberlaku_selesai = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->tanda_daftar_yayasan_masaberlaku_selesai)));
            $izin_kegiatan_yayasan_masaberlaku_mulai  = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->izin_kegiatan_yayasan_masaberlaku_mulai)));
            $izin_kegiatan_yayasan_masaberlaku_selesai= date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->izin_kegiatan_yayasan_masaberlaku_selesai)));
            $induk_berusaha_tgl_terbit                = date("Y-m-d H:i:s", strtotime(MonthToEnglish($request->induk_berusaha_tgl_terbit)));
            $akreditasi_tgl                           = date("d-m-Y", strtotime(MonthToEnglish($request->akreditasi_tgl)));
            $verifier                                 = $request->status == 'diproses' ? null : $request->user->id;

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
                'jenis_lks'                         => $request->jenis_lks,
                'jumlah_wbs'                        => $request->jumlah_wbs,
                'jumlah_peksos'                     => $request->jumlah_peksos,
                'sk_domisili_yayasan_nomor'                 => $request->sk_domisili_yayasan_nomor,
                'sk_domisili_yayasan_masaberlaku_mulai'     => $sk_domisili_yayasan_masaberlaku_mulai,
                'sk_domisili_yayasan_masaberlaku_selesai'   => $sk_domisili_yayasan_masaberlaku_selesai,
                'sk_domisili_yayasan_instansi_penerbit'     => $request->sk_domisili_yayasan_instansi_penerbit,
                'tanda_daftar_yayasan_nomor'                => $request->tanda_daftar_yayasan_nomor,
                'tanda_daftar_yayasan_masaberlaku_mulai'    => $tanda_daftar_yayasan_masaberlaku_mulai,
                'tanda_daftar_yayasan_masaberlaku_selesai'  => $tanda_daftar_yayasan_masaberlaku_selesai,
                'tanda_daftar_yayasan_instansi_penerbit'    => $request->tanda_daftar_yayasan_instansi_penerbit,
                'izin_kegiatan_yayasan_nomor'               => $request->izin_kegiatan_yayasan_nomor,
                'izin_kegiatan_yayasan_masaberlaku_mulai'   => $izin_kegiatan_yayasan_masaberlaku_mulai,
                'izin_kegiatan_yayasan_masaberlaku_selesai' => $izin_kegiatan_yayasan_masaberlaku_selesai,
                'izin_kegiatan_yayasan_instansi_penerbit'   => $request->izin_kegiatan_yayasan_instansi_penerbit,
                'induk_berusaha_status'             => $request->induk_berusaha_status,
                'induk_berusaha_nomor'              => $request->induk_berusaha_nomor,
                'induk_berusaha_tgl_terbit'         => $induk_berusaha_tgl_terbit,
                'induk_berusaha_instansi_penerbit'  => $request->induk_berusaha_instansi_penerbit,
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
            throw new GeneralException($th->getMessage(), 500);
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
                    'message' => 'verifikasi LKS berhasil diubah',
                    'data'    => $newLks
                ],
                200
            );
        }
        catch (\Throwable $th) {
            throw new GeneralException($th->getMessage(), 500);
        }
    }

    public function updateStatus(LksRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $newLks = Lks::where('id', $request->id)->update([
                'status'   => $request->status,
                'verifier' => $request->user->id,
            ]);

            LogStatus::create([
                "id_reference"      => $request->id,
                "table_reference"   => "lks",
                "status"            => $request->status,
                "description"       => $request->description ? $request->description : "",
                'verifier'          => $request->user->id,
            ]);

            DB::commit();

            return response()->json(
                [
                    'message' => 'LKS status berhasil diubah',
                    'data'    => $newLks
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
        $lastRecord = Lks::where('site_id', $siteId)->latest('no_urut')->first();
        return $lastRecord ? $lastRecord->no_urut + 1 : 1;
    }
}
