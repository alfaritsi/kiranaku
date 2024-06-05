<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS Cuti & Ijin - Library
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Less
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('dessgeneral');
        $this->CI->load->model('dbak');
        $this->CI->load->model('dcutiijin');
        $this->CI->load->model('dmedical');
    }

    public function get_plafon_sisa($params = array())
    {
        $tanggal_awal = isset($params['tanggal_awal']) ? $params['tanggal_awal'] : null;
        $tanggal_akhir = isset($params['tanggal_akhir']) ? $params['tanggal_akhir'] : null;
        $tahun = isset($params['tahun']) ? $params['tahun'] : date('Y');
        if (isset($_GET['tahun_klaim']) && ESS_EMAIL_DEBUG_MODE)
            $tahun = $_GET['tahun_klaim'];
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $is_cutoff = isset($params['is_cutoff']) ? $params['is_cutoff'] : null;
        $id_before = isset($params['id_before']) ? $params['id_before'] : null;
        $kode = isset($params['kode']) ? $params['kode'] : null;
		
        $karyawan = $this->CI->dessgeneral->get_karyawan($nik);
        $user = $this->CI->dessgeneral->get_user(null, null, null, $nik);

        $id_golongan = $karyawan->id_golongan;

        $sisa_fbk_jalan_next = 0;
		$sisa_fbk_jalan = 0;
        $sisa_fbk_inap = 0;
        $sisa_fbk_frame = 0;
        $sisa_fbk_lensa = 0;
        $sisa_fbk_bersalin_normal = 0;
        $sisa_fbk_bersalin_cesar = 0;
        

        $plafon = $this->CI->dmedical->get_plafon(array(
            "nik" => $nik,
            "single_row" => true
        ));

        $golongan = $this->CI->dmedical->get_golongan(array(
            "single_row" => true,
            "id_golongan" => $id_golongan
        ));

        $plafon_kamar = $this->CI->dmedical->get_plafon_kamar(array(
            'single_row' => true,
            'id_golongan' => $id_golongan
        ));

        $fbk_cek = $this->CI->dmedical->get_fbk_cek(array(
            "nik" => $nik,
            "single_row" => true
        ));

        if (!isset($fbk_cek) && isset($plafon)) {
            $sisa_fbk_jalan = $plafon->VAL_BRJL;
        } else {
            if (isset($golongan))
                $sisa_fbk_jalan = $golongan->jumlah;
            else
                $sisa_fbk_jalan = 0;
        }

        if (isset($plafon_kamar)) {
            $sisa_fbk_inap = $plafon_kamar->nominal;
        }

//        $tanggal_awal = null;
        if (isset($plafon)) {
            $sisa_fbk_jalan_next = $plafon->BRJL;
            $sisa_fbk_frame = $plafon->VAL_BBKI;
            $sisa_fbk_lensa = $plafon->VAL_BLNS;

            $sisa_fbk_bersalin_normal = $plafon->BBNR;
            $sisa_fbk_bersalin_cesar = $plafon->BBCS;

//            $tanggal_awal = date('Y-m-d', strtotime($plafon->tanggal_migrasi));
        }
        $tanggal_awal_frame = $tanggal_awal;
        $tanggal_akhir_frame = $tanggal_akhir;

        if ((isset($kode) && $kode == 'BBKI') || !isset($kode)) {
            $first_frame = $this->CI->dmedical->get_fbk(array(
                'jenis' => 'frame',
                'id_user' => $user->id_user,
                'encrypted' => true,
                'order_by' => 'id_fbk ASC',
                'single_row' => true,
                'id_fbk_status' => array(ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP, ESS_MEDICAL_STATUS_LENGKAP, ESS_MEDICAL_STATUS_DISETUJUI)
            ));

            if (isset($first_frame)) {
                $tanggal_first_frame = date_create($first_frame->tanggal_buat);
                $tahun_first_frame = $tanggal_first_frame->format('Y');

                $next_frame = $tanggal_first_frame->modify('+' . ESS_MEDICAL_JUMLAH_TAHUN_FRAME . ' years');
                $temp_tahun_awal = $tahun_first_frame;
                $temp_tahun_akhir = $next_frame->format('Y');

                $tahun_allow_frame = false;
                while (!$tahun_allow_frame) {
                    if ($tahun >= $temp_tahun_awal && $tahun < $temp_tahun_akhir) {
                        $tahun_allow_frame = true;
                        break;
                    }
                    $next_frame->modify('+' . ESS_MEDICAL_JUMLAH_TAHUN_FRAME . ' years');
                    $temp_tahun_awal = $temp_tahun_akhir;
                    $temp_tahun_akhir = $next_frame->format('Y');
                }

                $tanggal_awal_frame = date(
                    'Y-m-d',
                    strtotime(
                        'First day of January ' .
                        (intval($temp_tahun_awal))
                    )
                );

                $tanggal_akhir_frame = date(
                    'Y-m-d',
                    strtotime(
                        'First day of January ' .
                        (intval($temp_tahun_akhir))
                    )
                );

                if (date_create($first_frame->tanggal_buat) > date_create($tanggal_awal_frame)) {
                    $tanggal_awal_frame = date_create($first_frame->tanggal_buat)->format('Y-m-d');
                }
            }
        }

        $kwitansi_disetujui_frame = $this->CI->dmedical->get_fbk_kwitansi(array(
            'nik' => $nik,
            'id_fbk_status' => array(ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP, ESS_MEDICAL_STATUS_LENGKAP),
            'kode' => 'BBKI',
            'tanggal_after' => $tanggal_awal_frame,
            'tanggal_before' => $tanggal_akhir_frame,
            'id_before' => $id_before
        ));

        foreach ($kwitansi_disetujui_frame as $kwitansi) {
            $nominal = $kwitansi->amount_kwitansi;
            if ($kwitansi->id_fbk_status == ESS_MEDICAL_STATUS_LENGKAP)
                $nominal = $kwitansi->amount_ganti;
            switch ($kwitansi->kode) {
                case "BBKI":
                    $sisa_fbk_frame -= $nominal;
                    break;
            }
        }

        $kwitansi_disetujui = $this->CI->dmedical->get_fbk_kwitansi(array(
            'nik' => $nik,
            'id_fbk_status' => array(ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP, ESS_MEDICAL_STATUS_LENGKAP),
            'tahun' => $tahun,
            'kode' => array('BRJL', 'BLNS', 'BBCS', 'BBNR'),
            'tanggal_after' => $tanggal_awal,
            'tanggal_before' => $tanggal_akhir,
            'id_before' => $id_before
        ));

        foreach ($kwitansi_disetujui as $kwitansi) {
            $nominal = $kwitansi->amount_kwitansi;
            if ($kwitansi->id_fbk_status == ESS_MEDICAL_STATUS_LENGKAP)
                $nominal = $kwitansi->amount_ganti;
            switch ($kwitansi->kode) {
                case "BRJL":
                    $sisa_fbk_jalan -= $nominal;
                    break;
                case "BLNS":
                    $sisa_fbk_lensa -= $nominal;
                    break;
            }
        }

        if (isset($kode) && !empty($kode)) {
            switch ($kode) {
                case "BRJL":
                    return $sisa_fbk_jalan;
                    break;
                case "BBKI":
                    return $sisa_fbk_frame;
                    break;
                case "BLNS":
                    return $sisa_fbk_lensa;
                    break;
                case "BBCS":
                    return $sisa_fbk_bersalin_cesar;
                    break;
                case "BBNR":
                    return $sisa_fbk_bersalin_normal;
                    break;
                default:
                    return null;
                    break;
            }
        } else
            return compact(
                'sisa_fbk_jalan_next', 'sisa_fbk_jalan', 'sisa_fbk_inap', 'sisa_fbk_bersalin_normal', 'sisa_fbk_bersalin_cesar',
                'sisa_fbk_lensa', 'sisa_fbk_frame'
            );
    }

    public function get_cuti_sisa($tanggal_akhir = null, $nik = null)
    {
        $sisa_saldo = 0;

        $sisa_cuti_bukan_tahunan = 0;

        $sisa_cuti_tahunan = 0;

        $total_pengajuan = 0;

        $batas_negatif = 0;

        $punya_cuti_panjang = false;
        if (is_array($tanggal_akhir)) {
            $cutis = $this->CI->dessgeneral->get_saldo_nik($nik, 'tanggal_akhir', 'asc', $tanggal_akhir[1]);
        } else {
            $cutis = $this->CI->dessgeneral->get_saldo_nik($nik, 'tanggal_akhir', 'asc', $tanggal_akhir);
        }

        // menghitung batas negatif, mengambil negatif terkecil
        foreach ($cutis as $cuti) {
            if ($cuti->batas_negatif < $batas_negatif)
                $batas_negatif = $cuti->batas_negatif;
        }

        // mengambil data pengajuan cuti/ijin yang menunggu
        $pengajuan_menunggu = $this->CI->dcutiijin->get_cuti(
            array(
                'tipe' => array('Cuti'),
                'tanggal_awal' => $tanggal_akhir,
                'id_tipe_status' => array(ESS_CUTI_STATUS_MENUNGGU, ESS_CUTI_STATUS_DISETUJUI_ATASAN),
                'nik' => $nik
            )
        );

        // menghitung total hari pengajuan yang menunggu
        foreach ($pengajuan_menunggu as $pengajuan) {
            $total_pengajuan += $pengajuan->jumlah;
        }

        $cuti_bukan_tahunan = array_filter($cutis, function ($cuti) {
            if ($cuti->kode != ESS_CUTI_TAHUNAN)
                return true;
            else
                return false;
        });

        $cuti_tahunan = array_filter($cutis, function ($cuti) {
            if ($cuti->kode == ESS_CUTI_TAHUNAN)
                return true;
            else
                return false;
        });

        foreach ($cuti_bukan_tahunan as $cuti) {
            if ($cuti->kode == 93) {
                $punya_cuti_panjang = true;
                break;
            }
        }

        if (count($cuti_tahunan) > 0) {
            $lastCuti = null;
            $sisaBatasNegatif = $batas_negatif;
            $batasNegatifTerpakai = false;
//            var_dump($sisa_cuti_tahunan);
            foreach ($cuti_tahunan as $cuti) {
                $sisa = 0;
                if ($lastCuti == null) {
                    $lastCuti = $cuti;
                    $sisa = $cuti->sisa;
                } else if (
                    (
                        $lastCuti->sisa == $cuti->sisa
                        and $lastCuti->tanggal_akhir == $cuti->tanggal_akhir
                        and $lastCuti->tanggal_awal == $cuti->tanggal_awal
                    ) == false
                ) {
                    $sisa = $cuti->sisa;
                }

                /** jika cuti tahunan ada 2 dan memiliki batas negatif **/
                if (count($cuti_tahunan) > 1 && !$batasNegatifTerpakai) {
                    $batasNegatifTerpakai = true;
                    if ($sisa < 0 && $sisaBatasNegatif < 0) {
                        $sisaBatasNegatif -= $sisa;
                        if ($sisaBatasNegatif <= 0)
                            $sisa = 0;
                        else
                            $sisa = $sisaBatasNegatif + $batas_negatif;
                    }
                }

                /** jika cuti tahunan dan cuti panjang */
                if ($punya_cuti_panjang && $sisa < 0)
                    $sisa = 0;

                $sisa_cuti_tahunan += $sisa;

            }
        }

        foreach ($cuti_bukan_tahunan as $cuti) {
            $sisa_cuti_bukan_tahunan += $cuti->sisa;
        }

        $sisa_total = $sisa_cuti_bukan_tahunan + $sisa_cuti_tahunan - $total_pengajuan;

        return array(
            "sisa_awal" => $sisa_cuti_tahunan + $sisa_cuti_bukan_tahunan,
            "sisa" => $sisa_total,
            "pengajuan" => $total_pengajuan,
            "negatif" => $batas_negatif
        );

    }

    public function proses_list_bak($params = array())
    {
        $user = $params['user'];
        $tanggal_awal = $params['tanggal_awal'];
        $tanggal_akhir = $params['tanggal_akhir'];
        $list_bak_temp = $params['list_bak'];
        $lengkap = $params['lengkap'];

        $list_bak = array();

        $list_check_cuti = $this->CI->dbak->get_check_cuti(
            array(
                'nik' => $user->nik,
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'id_cuti_status' => array(
                    ESS_CUTI_STATUS_MENUNGGU,
                    ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                    ESS_CUTI_STATUS_DISETUJUI_HR
                )
            )
        );

        $list_absen_masuk = $this->CI->dbak->get_bak(array(
            'nik' => $user->nik,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'order_by' => 'tanggal_absen ASC, absen_masuk ASC'
        ));

        $list_absen_keluar = $this->CI->dbak->get_bak(array(
            'nik' => $user->nik,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'order_by' => 'tanggal_absen ASC, absen_keluar DESC'
        ));

        $cuti_ijin = $this->CI->dessgeneral->get_cuti_ijin(
            array(
                'exclude' => false,
                'kode_grouping_area' => $user->moabw
            )
        );

        $cuti_cuti = $this->CI->dessgeneral->get_cuti_cuti(array(
            'nik' => $user->nik
        ));

        $bak_massal = $this->CI->dbak->get_bak_massal(array(
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ));

//        var_dump($lengkap);die();

        foreach ($list_bak_temp as $index => $bak) {
            if ($bak->new_method)
                $bak = $this->proses_list_bak_label_baru($bak, $user, $list_check_cuti, $cuti_ijin, $cuti_cuti, $bak_massal);
            else
                $bak = $this->proses_list_bak_label($bak, $user, $list_check_cuti, $list_absen_masuk, $list_absen_keluar);

            if (!$lengkap) {
//                if ($bak->new_method) {
                if ($bak->tidak_lengkap or $bak->absen_miss)
                    $list_bak[] = $bak;
//                } else {
//                    if (
//                        ($bak->absen_masuk == '-' or $bak->absen_keluar == '-')
//                        and ($bak->tipe == '-')
//                    )
//                        $list_bak[] = $bak;
//                }
            } else {
                $list_bak[] = $bak;
            }
        }

        return $list_bak;
    }

    private function proses_list_bak_label($bak, $user, $list_check_cuti, $list_absen_masuk, $list_absen_keluar)
    {

        $bak->tidak_lengkap = false;
        $bak->absen_miss = false;
        $bak->absen_miss_ci = false;
        $bak->absen_miss_co = false;

        $bak->cuti = false;
        $bak->cuti_tipe = false;

        $check_cuti = null;
        foreach ($list_check_cuti as $lcheck) {
            if (
                strtotime($lcheck->tanggal_awal) <= strtotime($bak->tanggal_absen) &&
                strtotime($lcheck->tanggal_akhir) >= strtotime($bak->tanggal_absen)
            )
                $check_cuti = $lcheck;
        }
        $absen_masuk = null;
        $absen_keluar = null;

        foreach ($list_absen_masuk as $labsenmasuk) {
            if ($labsenmasuk->tanggal_absen == $bak->tanggal_absen) {
                $absen_masuk = $labsenmasuk;
                break;
            }
        }
        foreach ($list_absen_keluar as $labsenkeluar) {
            if ($labsenkeluar->tanggal_absen == $bak->tanggal_absen) {
                $absen_keluar = $labsenkeluar;
                break;
            }
        }
        $cuti_ijin = $this->CI->dessgeneral->get_cuti_ijin(
            array(
                'exclude' => false,
                'kode_grouping_area' => $user->moabw
            )
        );

        $cuti_cuti = $this->CI->dessgeneral->get_cuti_cuti(array(
            'nik' => $bak->nik
        ));

        if (isset($check_cuti)) {
            $bak->tidak_lengkap = false;
            $bak->cuti = $check_cuti;

            if ($check_cuti->form == 'Cuti') {
                $bak->cuti_tipe = 'cuti';
            } else {
                $bak->cuti_tipe = 'ijin';
            }
        } else {

            foreach ($cuti_ijin as $item) {
                if ($item->kode == $bak->tipe) {
                    $bak->cuti = $item;
                    if ($bak->tipe == ESS_CUTI_JENIS_CUTI_BERSAMA)
                        $bak->cuti_tipe = 'cuti';
                    else
                        $bak->cuti_tipe = 'ijin';
                    break;
                }
            }

            foreach ($cuti_cuti as $item) {
                if ($item->kode == $bak->tipe) {
                    $bak->cuti = $item;
                    $bak->cuti_tipe = 'cuti';
                    break;
                }
            }
        }

        $cuti_ijin = $this->CI->dessgeneral->get_cuti_ijin(
            array(
                'kode' => $bak->tipe,
                'exclude' => false,
                'kode_grouping_area' => $user->moabw,
                'single_row' => true
            )
        );

        if ($absen_masuk->absen_masuk == '-' and $absen_keluar->absen_keluar == '-') {
            if ($bak->tipe == 'L')
                $bak->keterangan_label = 'Libur';
            elseif ($bak->tipe != '-') {
                if (isset($cuti_ijin)) {
                    $bak->keterangan_label = $cuti_ijin->nama;
                } else {
                    $cuti_cuti = $this->CI->dessgeneral->get_cuti_cuti(array(
                        'kode' => $bak->tipe,
                        'nik' => $bak->nik,
                        'single_row' => true
                    ));
                    if (isset($cuti_cuti)) {
                        $bak->keterangan_label = $cuti_cuti->nama;
                    } else
                        $bak->keterangan_label = '-';
                }
            } else if (isset($check_cuti)) {
                $bak->keterangan_label = $check_cuti->form;
            } else
                $bak->keterangan_label = 'Absen';
        } else {
            if ($absen_masuk->absen_masuk == '-' or $absen_keluar->absen_keluar == '-')
                $bak->keterangan_label = 'Tidak CI/CO';
            else if (
                ($absen_masuk->absen_masuk == '-' or $absen_keluar->absen_keluar == '-') and
                $bak->id_bak_alasan != 0
            )
                $bak->keterangan_label = "Proses Pengajuan BAK";

            else if (isset($cuti_ijin))
                $bak->keterangan_label = $cuti_ijin->nama;
            else
                $bak->keterangan_label = "Hadir";

            if (!empty($bak->alasan))
                $bak->keterangan_label = $bak->alasan;
        }

        if (
            !in_array($bak->tipe, array('L', '0120'))
            and
            (
                $absen_masuk->absen_masuk == '-' or
                $absen_keluar->absen_keluar == '-'
            )
//            and in_array($bak->tipe, array('-', ESS_CUTI_JENIS_DINAS, ESS_CUTI_JENIS_MEETING, ESS_CUTI_JENIS_TRAINING))
            and in_array($bak->tipe, array('-'))
        ) {
            if ($bak->id_bak_alasan == 0) {
                $bak->tidak_lengkap = true;
            }
            $bak->status = "<span class='badge bg-red'>Tidak Lengkap</span>";
        } else
            $bak->status = "<i class='fa fa-check text-success'></i>";

        /** badge status bak */
        if ($bak->id_bak_alasan != 0)
            $bak->status = "<span class='badge " . $bak->warna . "'>" . $bak->nama_status . "</span>";
        else if (isset($check_cuti))
            $bak->status = "<span class='badge " . $check_cuti->warna . "'>" . $check_cuti->nama_status . "</span>";

        if ($bak->cuti_tipe !== false) {
            $bak->tidak_lengkap = false;
        }

        /** link pengajuan bak */
        if (
        in_array($bak->id_bak_status, array(ESS_BAK_STATUS_DITOLAK, ESS_BAK_STATUS_DIBATALKAN))
        )
            /** link pengajuan bak setelah ditolak */
            $bak->edit = "<li><a href='javascript:void(0)' class='bak-pengajuan' data-pengajuan='" . $bak->enId . "' data-jenis=0><i class='fa fa-edit'></i> Pengajuan BAK</a></li>";
        else if (
            ($absen_masuk->absen_masuk == '-' or $absen_keluar->absen_keluar == '-')
            and in_array($bak->tipe, array('-', ESS_CUTI_JENIS_DINAS, ESS_CUTI_JENIS_MEETING, ESS_CUTI_JENIS_TRAINING))
            and $bak->id_bak_alasan == ESS_BAK_ALASAN_KOSONG
        )
            /** link pengajuan bak CICO kosong */
            $bak->edit = "<li><a href='javascript:void(0)' class='bak-pengajuan' data-pengajuan='" . $bak->enId . "' data-jenis=0><i class='fa fa-edit'></i> Pengajuan BAK</a></li>";
        else
            $bak->edit = '';

        /** override link pengajuan jika status bisa di edit */
        if ($bak->id_bak_status == ESS_BAK_STATUS_MENUNGGU)
            $bak->edit = "<li><a href='javascript:void(0)' class='bak-edit' data-pengajuan='" . $bak->enId . "'><i class='fa fa-edit'></i> Edit BAK</a></li>";

        /** link detail */
        $bak->detail = "<li><a href='javascript:void(0)' class='bak-detail' data-detail='" . $bak->enId . "'><i class='fa fa-search'></i> Detail</a></li>";

        /** link history */
        if ($bak->id_bak_status != ESS_BAK_STATUS_DEFAULT)
            $bak->history = "<li><a href='javascript:void(0)' class='bak-history' data-history='" . $bak->enId . "'><i class='fa fa-history'></i> History</a></li>";
        else
            $bak->history = "";

        /** jenis bak */
        switch ($bak->jenis) {
            case '0':
                $bak->jenis_label = 'Libur';
                break;
            case '1':
                $bak->jenis_label = '-';
                break;
            case '2':
                $bak->jenis_label = 'Lembur';
                break;
            default:
                $bak->jenis_label = '-';
                break;
        }

        if (isset($check_cuti))
            $bak->btn = $bak->detail;
        else
            $bak->btn = $bak->edit
                . $bak->detail
                . $bak->history;

        return $bak;
    }

    private function proses_list_bak_label_baru(
        $bak,
        $user,
        $list_check_cuti,
        $cuti_ijin = array(),
        $cuti_cuti = array(),
        $bak_massal = array()
    )
    {
        $cuti_ijin_pengajuan = null;
        $cuti_ijin_sap = null;
        $massal = null;

        foreach ($bak_massal as $item) {
            $karyawans = array();
            if (isset($item->karyawans))
                $karyawans = explode('.', $item->karyawans);
            if (date_create($item->tanggal_bak) == date_create($bak->tanggal_absen) and in_array($bak->nik, $karyawans)) {
                $massal = $item;
                break;
            }
        }

        $bak->cuti = null;
        $bak->cuti_tipe = null;

        foreach ($list_check_cuti as $lcheck) {
            if (
                date_create($lcheck->tanggal_awal) <= date_create($bak->tanggal_absen) &&
                date_create($lcheck->tanggal_akhir) >= date_create($bak->tanggal_absen)
            ) {
                $cuti_ijin_pengajuan = $lcheck;
            }
        }
        $absen_masuk = $bak->absen_masuk;
        $absen_keluar = $bak->absen_keluar;

        $jadwal_masuk = null;
        $jadwal_keluar = null;

        if (isset($bak->jadwal_absen_masuk))
            $jadwal_masuk = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_masuk);
        if (isset($bak->jadwal_absen_keluar)) {
            if (isset($jadwal_masuk)) {
                $jadwal_keluar = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_keluar);
                if ($jadwal_keluar < $jadwal_masuk)
                    $jadwal_keluar = $jadwal_keluar->add(DateInterval::createFromDateString('1 day'));
            }
        }

        if ($absen_masuk != '-')
            $absen_masuk = date_create($bak->tanggal_masuk . ' ' . $bak->absen_masuk);
        else
            $absen_masuk = null;

        if ($absen_keluar != '-')
            $absen_keluar = date_create($bak->tanggal_keluar . ' ' . $bak->absen_keluar);
        else
            $absen_keluar = null;

        $bak->tidak_lengkap = false;
        $bak->absen_miss = false;
        $bak->absen_miss_ci = false;
        $bak->absen_miss_co = false;

        $bak->linkPengajuan = '';
        $bak->keterangan_label = '';


        /** Cek absensi luar jadwal kerja / lembur */
        $absenLembur = false;
        if (
            isset($absen_masuk) and isset($absen_keluar)
            and isset($jadwal_masuk) and isset($jadwal_keluar)
        ) {
            if (
                $absen_masuk > $jadwal_keluar
                or $absen_keluar < $jadwal_masuk
            ) {
                $absenLembur = true;
            }
        }

        if (
            !isset($bak->detail) and !$absenLembur
        ) {
            if (
                isset($absen_masuk) and isset($absen_keluar)
                and isset($jadwal_masuk) and isset($jadwal_keluar)
                and in_array($bak->tipe, array('-'))
            ) {
                if (
                    $absen_masuk > $jadwal_masuk
                    and $absen_keluar < $jadwal_keluar
                ) {
                    $bak->keterangan_label = 'Datang Terlambat & Pulang Cepat';
                    $bak->absen_miss = true;
                    $bak->absen_miss_ci = true;
                    $bak->absen_miss_co = true;
                } else if (
                    $absen_masuk > $jadwal_masuk
                ) {
                    $bak->keterangan_label = 'Datang Terlambat';
                    $bak->absen_miss = true;
                    $bak->absen_miss_ci = true;

                } else if (
                    $absen_keluar < $jadwal_keluar
                ) {
                    $bak->keterangan_label = 'Pulang Cepat';
                    $bak->absen_miss = true;
                    $bak->absen_miss_co = true;
                } else
                    $bak->keterangan_label = 'Hadir';
            } else if (
                !isset($absen_masuk) and !isset($absen_keluar)
                and in_array($bak->tipe, array('-', ESS_CUTI_JENIS_DINAS, ESS_CUTI_JENIS_MEETING, ESS_CUTI_JENIS_TRAINING))
            ) {
                $bak->tidak_lengkap = true;
                if (isset($jadwal_masuk) and isset($jadwal_keluar)) {
                    $bak->keterangan_label = 'Absen';
                }
            } else if (
                (
                    isset($absen_masuk) and !isset($absen_keluar)
                ) or (
                    !isset($absen_masuk) and isset($absen_keluar)
                )
            ) {
                $bak->tidak_lengkap = true;
                $bak->keterangan_label = 'Tidak CI/CO';
            }
        } else {
            if ($absenLembur)
                $bak->keterangan_label = 'Hadir';
            if (in_array($bak->detail, array('MASSAL_CI', 'MASSAL_CO', 'MASSAL_CICO'))) {
                if (isset($massal)) {
                    $bak->tidak_lengkap = false;
                    $bak->absen_miss = false;
                    $bak->keterangan_label = $bak->alasan;
                }
            }

            if ($bak->detail == 'MISS_CI') {
                $bak->absen_miss_ci = true;
                $bak->keterangan_label = 'Datang Terlambat';
            } else if ($bak->detail == 'MISS_CO') {
                $bak->absen_miss_co = true;
                $bak->keterangan_label = 'Pulang Cepat';
            } else if ($bak->detail == 'MISS_CICO') {
                $bak->absen_miss_ci = true;
                $bak->absen_miss_co = true;
                $bak->keterangan_label = 'Datang Terlambat & Pulang Cepat';
            } else if (in_array($bak->id_bak_status, array(ESS_BAK_STATUS_DITOLAK, ESS_BAK_STATUS_DIBATALKAN))) {
                $bak->tidak_lengkap = true;
            }
        }

        if (
            $bak->id_bak_alasan != 0
            and !$bak->absen_miss
            and !in_array($bak->detail, array('MASSAL_CI', 'MASSAL_CO', 'MASSAL_CICO', 'MISS_CI', 'MISS_CO', 'MISS_CICO'))
        ) {
            $bak->keterangan_label = 'Proses Pengajuan BAK';
        }

        if (isset($cuti_ijin_pengajuan)) {
            $bak->tidak_lengkap = false;
            $bak->absen_miss = false;
            $bak->cuti = $cuti_ijin_pengajuan;
            $cuti_ijin_label = $cuti_ijin_pengajuan->form;

            if ($cuti_ijin_pengajuan->form == 'Cuti') {
                $cuti_ijin_label = 'Cuti Tahunan';
                $bak->cuti_tipe = 'cuti';
            } else {
                foreach ($cuti_ijin as $item) {
                    if ($item->kode == $cuti_ijin_pengajuan->kode) {
                        $cuti_ijin_label = $item->nama;
                        $bak->cuti_tipe = 'ijin';
                        break;
                    }
                }
            }

            $bak->keterangan_label = $cuti_ijin_label;
        } else {
            foreach ($cuti_ijin as $item) {
                if ($item->kode == $bak->tipe) {
                    $cuti_ijin_sap = $item;
                    $bak->cuti = $item;
                    if ($bak->tipe == ESS_CUTI_JENIS_CUTI_BERSAMA)
                        $bak->cuti_tipe = 'cuti';
                    else
                        $bak->cuti_tipe = 'ijin';
                    break;
                }
            }

            foreach ($cuti_cuti as $item) {
                if ($item->kode == $bak->tipe) {
                    $cuti_ijin_sap = $item;
                    $bak->cuti = $item;
                    $bak->cuti_tipe = 'cuti';
                    break;
                }
            }
            if (isset($cuti_ijin_sap))
                $bak->keterangan_label = $cuti_ijin_sap->nama;
        }

        if ($bak->tipe == 'L') {
            $bak->keterangan_label = 'Libur';
        }

        $bak->status = "<i class='fa fa-check text-success'></i>";

        if (
            !isset($bak->detail)
            and in_array($bak->tipe, array('-'))
            and $bak->absen_miss

        )
            $bak->status = "<i class='fa fa-warning text-yellow'></i>";


        if (
            $bak->tidak_lengkap and !$bak->absen_miss
        )
            $bak->status = "<span class='badge bg-red'>Tidak Lengkap</span>";

        if (
            !$bak->absen_miss
            and $bak->id_bak_alasan != 0
            and !in_array($bak->detail, array('MASSAL_CI', 'MASSAL_CO', 'MASSAL_CICO', 'MISS_CI', 'MISS_CO', 'MISS_CICO'))
        )
            $bak->status = "<span class='badge " . $bak->warna . "'>" . $bak->nama_status . "</span>";

        if (
        isset($cuti_ijin_pengajuan)
        ) {
            $bak->status = "<span class='badge " . $cuti_ijin_pengajuan->warna . "'>" . $cuti_ijin_pengajuan->nama_status . "</span>";
        }

        $btnPengajuan = '';
        $btnDelete = '';
        $btnRevisi = '';
        /** link detail */
        $btnDetail = "<li><a href='javascript:void(0)' class='bak-detail' data-detail='" . $bak->enId . "'><i class='fa fa-search'></i> Detail</a></li>";
        $btnHistory = '';

        if (
            !isset($cuti_ijin_pengajuan)
            and (
                in_array($bak->id_bak_alasan, array(ESS_BAK_ALASAN_KOSONG))
                or in_array($bak->id_bak_status, array(ESS_BAK_STATUS_DITOLAK, ESS_BAK_STATUS_DIBATALKAN, ESS_BAK_STATUS_MENUNGGU))
            )
        ) {

            if (
            in_array($bak->id_bak_status, array(ESS_BAK_STATUS_DITOLAK, ESS_BAK_STATUS_DIBATALKAN))
            )
                /** link pengajuan bak setelah ditolak */
                $btnPengajuan = "<li><a href='javascript:void(0)' class='bak-pengajuan' data-pengajuan='" . $bak->enId . "' data-jenis=0><i class='fa fa-edit'></i> Pengajuan BAK</a></li>";
            else if ($bak->tidak_lengkap)
                /** link pengajuan bak CICO kosong */
                $btnPengajuan = "<li><a href='javascript:void(0)' class='bak-pengajuan' data-pengajuan='" . $bak->enId . "' data-jenis=0><i class='fa fa-edit'></i> Pengajuan BAK</a></li>";
            else if ($bak->absen_miss) {
                /** link pengajuan bak absen telat / pulang cepat */
                if ($bak->absen_miss_ci and $bak->absen_miss_co)
                    $jenis_absen = ESS_BAK_ALASAN_KOMBINASI_DTG_PLG;
                else if ($bak->absen_miss_ci)
                    $jenis_absen = ESS_BAK_ALASAN_TERLAMBAT;
                else if ($bak->absen_miss_co)
                    $jenis_absen = ESS_BAK_ALASAN_PULANG_CEPAT;
                else
                    $jenis_absen = ESS_BAK_ALASAN_KOSONG;

                $btnPengajuan = "<li><a href='javascript:void(0)' class='bak-pengajuan' data-pengajuan='" . $bak->enId . "' data-jenis='" . $jenis_absen . "'>
                                    <i class='fa fa-edit'></i> Pengajuan BAK</a>
                                </li>";
            }

            /** override link pengajuan jika status bisa di edit atau delete*/
            if ($bak->id_bak_status == ESS_BAK_STATUS_MENUNGGU) {
                $btnPengajuan = "<li><a href='javascript:void(0)' class='bak-edit' data-pengajuan='" . $bak->enId . "'><i class='fa fa-edit'></i> Edit BAK</a></li>";
                $btnDelete = "<li><a href='javascript:void(0)' class='bak-delete' data-pengajuan='" . $bak->enId . "'><i class='fa fa-times'></i> Hapus Pengajuan</a></li>";
            } else if (
                in_array($bak->tipe, array(ESS_CUTI_JENIS_DINAS, ESS_CUTI_JENIS_MEETING, ESS_CUTI_JENIS_TRAINING))
                and !$bak->tidak_lengkap
            ) {
                $btnPengajuan = "";
            }

        }

        /** link history */
        if ($bak->id_bak_status != ESS_BAK_STATUS_DEFAULT)
            $btnHistory = "<li><a href='javascript:void(0)' class='bak-history' data-history='" . $bak->enId . "'><i class='fa fa-history'></i> History</a></li>";

        $bak->btn = $btnPengajuan . $btnDelete . $btnRevisi . $btnDetail . $btnHistory;

        if (isset($bak->tanggal_masuk) and date_create($bak->tanggal_masuk) < date_create($bak->tanggal_absen)) {
            $bak->tanggal_absen = $bak->tanggal_masuk;
        }

        return $bak;
    }

    public function revert_rupiah($money)
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot);

        return (float)str_replace(',', '.', $removedThousendSeparator);
    }

    public
    function convert_rupiah($nilai, $pecahan = 0)
    {
        $rupiah = 'Rp. ' . number_format($nilai, $pecahan, ',', '.');
        return $rupiah;
    }

    public
    function jenis_fbk($kode)
    {
        $jenis_full = "(tidak ada)";
        switch ($kode) {
            case "BRJL":
                $jenis_full = "Rawat Jalan";
                break;
            case "BRIN":
                $jenis_full = "Rawat Inap";
                break;
            case "BLNS":
                $jenis_full = "Lensa Kacamata";
                break;
            case "BBKI":
                $jenis_full = "Frame Kacamata";
                break;
            case "BBNR":
                $jenis_full = "Persalinan Normal";
                break;
            case "BBCS":
                $jenis_full = "Persalinan Cesar";
                break;
        }

        return $jenis_full;
    }

    public
    function get_atasan($params = array())
    {
        $id_departemen = isset($params['id_departemen']) ? $params['id_departemen'] : base64_decode($this->CI->session->userdata('-id_departemen-'));
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : base64_decode($this->CI->session->userdata('-id_divisi-'));
        $id_direktorat = isset($params['id_direktorat']) ? $params['id_direktorat'] : base64_decode($this->CI->session->userdata('-id_direktorat-'));
        $id_ceo = isset($params['id_ceo']) ? $params['id_ceo'] : base64_decode($this->CI->session->userdata('-id_ceo-'));
        $id_level = isset($params['id_level']) ? $params['id_level'] : base64_decode($this->CI->session->userdata('-id_level-'));
        $nik = isset($params['nik']) ? $params['nik'] : base64_decode($this->CI->session->userdata('-nik-'));

        $query = $this->CI->db->query("EXEC dbo.SP_Kiranaku_Approval NULL,'$nik'");

        $row = $query->row();

        $nik_atasan = '';
        $nik_atasan_email = '';
        $list_atasan = array();
        $list_atasan_email = array();

        if (isset($row) && !empty($row)) {
            $nik_atasan = $row->atasan;
            $nik_atasan_email = $row->atasan_nik_email;
            $list_atasan = explode(', ', $row->atasan_nama);
            $list_atasan_email = explode(' | ', $row->atasan_email);
            foreach ($list_atasan_email as $i => $list_email) {
                $list_atasan_email[$i] = trim($list_email);
            }
        }

//        $nik_departemen = $this->CI->dessgeneral->nik_bagian($id_departemen);
//        $nik_divisi = $this->CI->dessgeneral->nik_bagian($id_divisi);
//        $nik_direktorat = $this->CI->dessgeneral->nik_bagian($id_direktorat);
//        $nik_ceo = $this->CI->dessgeneral->nik_bagian($id_ceo);
//
//        if ($id_level == 9100) {
//            $nik_atasan = $nik_ceo;
//            $nik_atasan_email = $nik_ceo;
//        } else if ($id_level == 9101) {
//            if ($id_direktorat == 0) {
//                $nik_atasan = $nik_ceo;
//                $nik_atasan_email = $nik_ceo;
//            } else {
//                $nik_atasan = $nik_direktorat . '' . $nik_ceo;
//                $nik_atasan_email = $nik_direktorat;
//            }
//        } elseif ($id_level == 9102) {    //ka dept
//            if ($id_divisi == 0) {
//                $nik_atasan = $nik_direktorat . '' . $nik_ceo;
//                $nik_atasan_email = $nik_direktorat;
//            } elseif ($id_direktorat == 0) {
//                $nik_atasan = $nik_divisi . '' . $nik_ceo;
//                $nik_atasan_email = $nik_divisi;
//            } else {
//                $nik_atasan = $nik_divisi . '' . $nik_direktorat;
//                $nik_atasan_email = $nik_divisi;
//            }
//        } elseif ($id_level == 9103) {    //staff
//            if ($id_departemen == 0) {
//                $nik_atasan = $nik_divisi . '' . $nik_direktorat;
//                $nik_atasan_email = $nik_divisi;
//            } elseif ($id_divisi == 0) {
//                $nik_atasan = $nik_direktorat . '' . $nik_ceo;
//                $nik_atasan_email = $nik_direktorat;
//            } else {
////                $ck = GetaField('tbl_atasan', 'id_departemen', base64_decode($_SESSION['-id_departemen-']), 'id_atasan');
//                $ck = $this->CI->dessgeneral->nik_atasan($id_departemen);
//                $nik_atasan = $nik_departemen . '' . $nik_divisi;
//                $nik_atasan_email = (empty($ck)) ? $nik_divisi : $nik_departemen;
//            }
//        }
//
//        //pengalihan approval CEO ke Kadep HR Ops
//        $user_hr_div = $this->CI->dessgeneral->get_user(9101, 766);
//        $user_hr_op = $this->CI->dessgeneral->get_user(9102, 797);
//        $nik_hr_division = isset($user_hr_div) ? $user_hr_div->id_karyawan : ''; //get nik untuk kadiv hr operation
//        $nik_hr_operation = isset($user_hr_op) ? $user_hr_op->id_karyawan : '';    //get nik untuk kadep hr operation
//        $nik_atasan = str_replace(5530, $nik_hr_operation, $nik_atasan);    //jika CEO(Pak martinus) dialihkan ke HR Operation
//        $nik_atasan = str_replace('6724.', '', $nik_atasan);    //jika CEO(Pak Toddy) dihilangkan
//        $nik_atasan = str_replace('6725.', '', $nik_atasan);    //jika CEO(Pak Toddy) dihilangkan
//        $nik_atasan_email = str_replace(5530, $nik_hr_operation, $nik_atasan_email);    //jika CEO(Pak martinus) dialihkan ke HR Operation
//        $nik_atasan_email = str_replace('6724.', $nik_hr_operation, $nik_atasan_email);    //jika CEO(Pak Toddy) dihilangkan
//        $nik_atasan_email = str_replace('6725.', $nik_hr_operation, $nik_atasan_email);    //jika CEO(Pak Toddy) dihilangkan
//        $nik_atasan_email = str_replace('8892.', '', $nik_atasan_email);    //jika bu jenny send email dihilangkan
//
//        //pengecualian untuk nik 7041(pak bani) approval hardcode
////        $nik_atasan = ($nik == 7041) ? $nik_hr_division . '.' . $nik_hr_operation . '.' : $nik_atasan;
////        $nik_atasan_email = ($nik == 7041) ? $nik_hr_division . '.' : $nik_atasan_email;
//
//        //ambil dari tbl_atasan_master
//        $atasan_master = $this->CI->dessgeneral->get_atasan_master($nik);
//        $nik_atasan = (!empty($atasan_master->id_atasan_master)) ? $atasan_master->atasan : $nik_atasan;
//        $nik_atasan_email = (!empty($atasan_master->id_atasan_master)) ? $atasan_master->atasan_email : $nik_atasan_email;
//
//        $atasan = str_replace('.' . $nik, '', $nik_atasan);
//        $atasan = substr($atasan, 0, -1);
//
//        $atasan = explode(".", $atasan);
//        $list_atasan = array();
//        foreach ($atasan as $val) {
//            $karyawan = $this->CI->dessgeneral->get_karyawan($val);
//            $list_atasan[] = ucwords(strtolower(isset($karyawan) ? $karyawan->nama : ''));
//        }
//        $atasan_email = str_replace('.' . $nik, '', $nik_atasan_email);
//        $atasan_email = substr($atasan_email, 0, -1);
//        $atasan_email = explode(".", $atasan_email);
//        $list_atasan_email = array();
//        foreach ($atasan_email as $val) {
//            $karyawan = $this->CI->dessgeneral->get_karyawan($val);
//            $list_atasan_email[] = strtolower(isset($karyawan) ? $karyawan->email : '');
//        }
//        $this->CI->general->closeDb();

        return compact('nik_atasan', 'nik_atasan_email', 'list_atasan', 'list_atasan_email');
    }

    public
    function send_email($params = array())
    {
        $judul = isset($params['judul']) ? $params['judul'] : null;
        $email_pengirim = isset($params['email_pengirim']) ? $params['email_pengirim'] : null;
        $email_tujuan = isset($params['email_tujuan']) ? $params['email_tujuan'] : array();
        $email_cc = isset($params['email_cc']) ? $params['email_cc'] : array();
        $view = isset($params['view']) ? $params['view'] : null;
        $data = isset($params['data']) ? $params['data'] : null;

        $message = "";
        if (isset($view) && !empty($view))
            $message = $this->CI->load->view($view, compact('data'), true);

        $result = $this->process_send_email(
            array(
                'subject' => $judul,
                'from_alias' => $email_pengirim,
                'to' => $email_tujuan,
                'cc' => $email_cc,
                'message' => $message
            )
        );

        return $result;
    }

    private
    function process_send_email($params = array())
    {
        $subject = isset($params['subject']) ? $params['subject'] : null;
        $from_alias = isset($params['from_alias']) ? $params['from_alias'] : null;
        $message = isset($params['message']) ? $params['message'] : null;
        $to = isset($params['to']) ? $params['to'] : null;
        $cc = isset($params['cc']) ? $params['cc'] : null;

        if (!empty($subject) && !empty($from_alias) && !empty($to) && !empty($message)) {
            setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = KIRANA_EMAIL_HOST;
            $config['smtp_user'] = KIRANA_EMAIL_USER;
            $config['smtp_pass'] = KIRANA_EMAIL_PASS;
            $config['smtp_port'] = KIRANA_EMAIL_PORT;
            $config['smtp_crypto'] = 'ssl';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = true;
            $config['mailtype'] = 'html';

            try {
                $open_socket = @fsockopen(KIRANA_EMAIL_HOST, KIRANA_EMAIL_PORT, $errno, $errstr, 30);
                if (!$open_socket) {
                    $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                    $sts = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                } else {
                    $this->CI->load->library('email', $config);

                    $this->CI->email->from('no-reply@kiranamegatara.com', $from_alias);
                    $this->CI->email->to($to);
                    if (isset($cc) && !empty($cc)) {
                        $this->CI->email->cc($cc);
                    }

                    $this->CI->email->subject($subject);
                    $this->CI->email->message($message);

                    if (!$this->CI->email->send()) {
                        $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                        $sts = "NotOK";
                        $return = array('sts' => $sts, 'msg' => $msg);
                    } else {
                        $sts = "OK";
                        $return = array('sts' => $sts);
                    }
                }
            } catch (Exception $e) {
                $msg = $e->getMessage();
                $sts = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
            }
        } else {
            $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
            $sts = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
        }

        return $return;
    }
}