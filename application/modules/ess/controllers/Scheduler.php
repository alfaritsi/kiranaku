<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS Scheduler - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Scheduler extends MX_Controller
{
    private $nik_exclude = array(7427, 7426, 8892, 5588);

    public function __construct()
    {
        parent::__construct();
        $this->load->library('less');
        $this->load->model('dessgeneral');
        $this->load->model('dbak');
        $this->load->model('dcutiijin');
    }

    public function bak_time_event()
    {

        $this->load->library('sap');

        $filter = $this->input->get();

        $filter['lokasi'] = $this->input->get_post('lokasi', true);
        $filter['tanggal_awal'] = $this->input->get_post('tanggal_awal', true);
        $filter['tanggal_akhir'] = $this->input->get_post('tanggal_akhir', true);
        $filter['nik'] = $this->input->get_post('nik', true);
        $filter['mode'] = $this->input->get_post('mode', true);
        $filter['log'] = $this->input->get_post('log', true);

        if (isset($filter['lokasi']))
            $lokasi = $filter['lokasi'];
        else
            $lokasi = "ho";

        if (isset($filter['log']))
            $logging = $filter['log'] == 1 ? true : false;
        else
            $logging = false;

        $tanggal_awal = date('Y-m-d', strtotime('-7 days'));
        $tanggal_akhir = date('Y-m-d', strtotime('-1 days'));

        $today = date_create(date('Y-m-d'));

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        if (date_create($filter['tanggal_akhir']) > $today)
            $tanggal_akhir = date('Y-m-d', strtotime('-1 days'));

        $gen = $this->generate;

        $koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);

        $data_koneksi = $koneksi['ESS'];

        $constr = array(
            "logindata" => array(
                "ASHOST" => $data_koneksi['ASHOST'],
                "SYSNR" => $data_koneksi['SYSNR'],
                "CLIENT" => $data_koneksi['CLIENT'],
                "USER" => $data_koneksi['USER'],
                "PASSWD" => $data_koneksi['PASSWD']
            ),
            "show_errors" => $data_koneksi['DEBUG'],
            "debug" => $data_koneksi['DEBUG']
        );

        $sap = new saprfc($constr);

        $ho = 'n';
        if ($lokasi == 'ho')
            $ho = 'y';

        $nik = null;
        if (isset($filter['nik']))
            $nik = $filter['nik'];

        if (isset($nik)) {
            $this->general->connectDbPortal();
            $check_nik = $this->dessgeneral->get_karyawan($nik);
            if (!isset($check_nik)) {
                $return = array('sts' => 'NotOK', 'msg' => 'NIK tidak ditemukan');
                echo json_encode($return);
                die();
            }
        }

        $awal = date_create($tanggal_awal)
            ->modify('-1 day')
            ->format('Ymd');

        if (isset($filter['nik']) && isset($filter['tanggal_awal']) && isset($filter['tanggal_akhir']))
            $awal = date_create($tanggal_awal)
                ->format('Ymd');
        // echo json_encode($awal);
		// exit();	
        $akhir = date_create($tanggal_akhir)
            ->format('Ymd');

        if ($logging) {
            $this->general->log_schedule_master('ess/scheduler/bak_time_event', array(
                'sesi' => date('H:i'),
                'source' => 'SAP HRIS',
                'terminal' => '-',
                'destination' => 'Portal',
                'keterangan' => 'SYNC data Time Event karyawan dari HRIS ke PORTAL'
            ));
        }

        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $result = $sap->callFunction(
			// 'Z_GET_EMPLOYEE_TIME_EVAL', 
			'Z_GET_EMPLOYEE_TIME_EVENT', 
			array(
            array("IMPORT", "I_HO", $ho == 'y' ? 'X' : null),
            array("IMPORT", "I_BEGDA", $awal),
            array("IMPORT", "I_ENDDA", $akhir),
            array("IMPORT", "I_PERNR", $nik),
            array("TABLE", "T_DATA", array()),
            //                    array("TABLE", "T_SCHEDULE", array()),
            //                    array("TABLE", "T_HOLIDAY", array()),
        ));

        $delete_request = false;

        if ($sap->getStatus() == SAPRFC_OK) {

            if ($logging) $this->general->log_schedule_running('ess/scheduler/bak_time_event', array('rfc' => 'Z_GET_EMPLOYEE_TIME_EVENT'));

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();
            if (isset($result['T_DATA'])) {

                //                var_dump($result['T_DATA']);die();
                $absensi = array();

                if ($logging) $this->general->log_schedule_running('ess/scheduler/bak_time_event', array('rfc' => 'Proses T_DATA'));
                foreach ($result['T_DATA'] as $i => $bak) {

                    $tanggal = empty($bak['DATUM']) ? null :
                        date_create($bak['DATUM']);
                    $absen_masuk = empty($bak['BEGTM']) ? null :
                        date_create($bak['DATUM'] . $bak['BEGTM']);
                    $absen_keluar = empty($bak['ENDTM']) ? null :
                        date_create($bak['DATUM'] . $bak['ENDTM']);
                    $ws_masuk = (empty($bak['SOBEG']) or !isset($bak['SOBEG'])) ? null :
                        date_create($bak['DATUM'] . $bak['SOBEG']);
                    $ws_keluar = (empty($bak['SOEND']) or !isset($bak['SOEND'])) ? null :
                        date_create($bak['DATUM'] . $bak['SOEND']);

                    if (isset($ws_masuk) and $bak['LINE'] == 0)
                        $jenis = 1;
                    else
                        $jenis = $bak['LINE'];

                    $ws_tolerance = null;
                    $bak['BTEND'] = trim($bak['BTEND']);
                    if (isset($bak['BTEND']) and !empty($bak['BTEND'])) {
                        $ws_tolerance = date_create($bak['DATUM'] . $bak['BTEND']);
                        if ($ws_tolerance > $ws_keluar)
                            $ws_keluar = $ws_keluar->add(DateInterval::createFromDateString('1 day'));
                    }


                    if (isset($ws_masuk)) {
                        if ($ws_masuk > $ws_keluar) {
                            $ws_keluar = $ws_keluar->add(DateInterval::createFromDateString('1 day'));
                        }
                    }

                    $absensi[$bak['PERNR']][] = array(
                        'nik' => $bak['PERNR'],
                        'tanggal' => $tanggal,
                        'absen_masuk' => $absen_masuk,
                        'absen_keluar' => $absen_keluar,
                        'absen_keluar_paired' => false,
                        'ws_masuk' => $ws_masuk,
                        'ws_keluar' => $ws_keluar,
                        'ws_tolerance' => $ws_tolerance,
                        'tipe' => $bak['TIPE'],
                        'jenis' => $jenis,
                        'cek_next' => 0,
                    );
                }

                //                var_dump($absensi);die();

                foreach ($absensi as $nik => $absens) {
                    $extendedAbsens = array();

                    foreach ($absens as $i => $absen) {
                        $normal = false;
                        $absen['normal'] = false;
                        if (
                            isset($absen['absen_masuk']) && isset($absen['absen_keluar'])
                        ) {
                            if ($absen['absen_masuk'] < $absen['absen_keluar']) {
                                $normal = true;
                                $absen['normal'] = true;
                                $absen['absen_keluar_paired'] = true;
                            } else {
                                $absenBaru = array_replace(array(), $absen);
                                $absenBaru['absen_masuk'] = null;
                                $absens[] = $absenBaru;
                                $absen['absen_keluar'] = null;
                            }
                        }

                        if (isset($absen['absen_masuk']) && !$normal) {
                            if (isset($absens[$i + 1])) {
                                $nexAbsen = $absens[$i + 1];
                                //                                if ($nexAbsen['jenis'] == $absen['jenis']) {
                                if (isset($nexAbsen['absen_masuk'])) {
                                    if (
                                        $nexAbsen['absen_keluar'] < $nexAbsen['absen_masuk']
                                        and (isset($absen['ws_masuk']) and
                                            isset($nexAbsen['ws_masuk']) and
                                            $nexAbsen['absen_keluar'] > $absen['ws_keluar'] and
                                            $nexAbsen['absen_keluar'] < $nexAbsen['ws_masuk'])
                                    ) {
                                        $absen['absen_keluar'] = $nexAbsen['absen_keluar'];
                                        $absen['absen_keluar_paired'] = true;
                                        $nexAbsen['absen_keluar_paired'] = true;
                                        $nexAbsen['absen_keluar'] = null;
                                        $extendedAbsens[] = $nexAbsen;
                                    }
                                } else {
                                    if (
                                        (isset($nexAbsen['ws_masuk'])
                                            or (!isset($nexAbsen['ws_masuk'])
                                                and $nexAbsen['tipe'] != 'L'))
                                        and ($nexAbsen['absen_keluar'] > $absen['ws_masuk'] and
                                            $nexAbsen['absen_keluar'] < $nexAbsen['ws_masuk'])
                                    ) {
                                        $absen['absen_keluar'] = $nexAbsen['absen_keluar'];
                                        $absen['absen_keluar_paired'] = true;
                                        $nexAbsen['absen_keluar_paired'] = true;
                                        $nexAbsen['absen_keluar'] = null;
                                        $extendedAbsens[] = $nexAbsen;
                                    }
                                }
                                $absens[$i + 1] = $nexAbsen;
                                //                                }
                            }
                        }

                        if (
                            !isset($absen['absen_masuk']) && !isset($absen['absen_keluar']) and !$normal
                        ) {
                            if (isset($absens[$i + 1])) {
                                $nexAbsen = $absens[$i + 1];
                                //                                if ($nexAbsen['jenis'] == $absen['jenis']) {
                                if (isset($nexAbsen['absen_keluar']) and isset($nexAbsen['absen_masuk']))
                                    if (
                                        $nexAbsen['absen_keluar'] < $nexAbsen['absen_masuk']
                                    ) {
                                        $absen['absen_keluar'] = $nexAbsen['absen_keluar'];
                                        $nexAbsen['absen_keluar'] = null;
                                        $nexAbsen['absen_keluar_paired'] = true;
                                        $extendedAbsens[] = $nexAbsen;
                                    }

                                $absens[$i + 1] = $nexAbsen;
                                //                                }
                            }
                        }

                        $absens[$i] = $absen;
                    }

                    //                    var_dump($absens);die();

                    foreach ($absens as $i => $absen) {

                        foreach ($extendedAbsens as $extendedAbsen) {
                            if (
                                !isset($extendedAbsen['absen_masuk']) &&
                                $extendedAbsen['absen_keluar_paired'] &&
                                $absen['tanggal'] == $extendedAbsen['tanggal'] &&
                                $absen['jenis'] == $extendedAbsen['jenis']
                            ) {
                                $absen['absen_keluar_paired'] = true;
                                break;
                            }
                        }

                        $absens[$i] = $absen;
                    }

                    $absensi[$nik] = $absens;
                }

                //                var_dump($absensi);die();

                $check_cuti = $this->dbak->get_check_cuti(array(
                    'id_cuti_status' => array(
                        ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                        ESS_CUTI_STATUS_DISETUJUI_HR
                    ),
                    'tanggal_awal' => $tanggal_awal,
                    'tanggal_akhir' => $tanggal_akhir,
                    'nik' => $nik
                ));
                $this->general->connectDbDefault();
                $check_holiday = $this->dessgeneral->get_libur($nik, $tanggal_awal, $tanggal_akhir);
                $this->general->connectDbPortal();
                //                var_dump($absensi); die();

                foreach ($absensi as $nik => $absens) {
                    foreach ($absens as $index => $d) {

                        $nextAbsensIndexes = array();
                        $nextAbsens = array_filter($absens, function ($absen, $i) use ($d, $index, $nextAbsensIndexes) {
                            $isNextDay = false;
                            $tanggal = clone $d['tanggal'];

                            if (
                                ($absen['tanggal'] == $tanggal
                                    or $absen['tanggal'] == $tanggal->add(DateInterval::createFromDateString('1 day')))
                                and $absen != $d
                                and $i <> $index
                            ) {
                                $isNextDay = true;
                            }
                            return $isNextDay;
                        }, ARRAY_FILTER_USE_BOTH);

                        //                        var_dump($nextAbsens);die();
                        $d['cek_next'] = count($nextAbsens);

                        $absen_masuk_found = false;

                        if ($d['absen_masuk'] < $d['absen_keluar']) {
                            $absen_masuk_found = true;
                        }

                        foreach ($nextAbsens as $nextIndex => $nextAbsen) {
                            if (isset($d['ws_masuk'])) {
                                if (isset($nextAbsen['absen_masuk']) && !$absen_masuk_found) {
                                    if (
                                        ($nextAbsen['absen_masuk'] > $d['ws_masuk'] and
                                            $nextAbsen['absen_masuk'] < $d['ws_keluar'] and
                                            !isset($d['absen_masuk'])) or $nextAbsen['absen_masuk'] == $d['absen_masuk']
                                    ) {
                                        if (isset($d['absen_masuk'])) {
                                            $absenLemparan = array_merge(array(), $d);
                                            $absenLemparan['absen_keluar'] = null;
                                            $absenLemparan['absen_masuk'] = $d['absen_masuk'];
                                            $absens[] = $absenLemparan;
                                        }
                                        $d['jenis'] = $nextAbsen['jenis'];
                                        $d['absen_masuk'] = $nextAbsen['absen_masuk'];
                                        $nextAbsen['absen_masuk'] = null;
                                        $absen_masuk_found = true;
                                    }
                                }
                            }
                            $nextAbsens[$nextIndex] = $nextAbsen;
                            $absens[$nextIndex] = $nextAbsen;
                        }

                        $absen_keluar_update = null;
                        $absen_keluar_update_index = null;

                        foreach ($nextAbsens as $nextIndex => $nextAbsen) {
                            if (isset($nextAbsen['absen_keluar'])) {
                                if (
                                    (isset($nextAbsen['ws_masuk'])
                                        and $nextAbsen['absen_keluar'] <= $nextAbsen['ws_masuk'])
                                    or (
                                        // Masang CICO yang tidak punya CI tapi punya CO dijadwal yang sama
                                        isset($d['absen_masuk'])
                                        and !isset($nextAbsen['absen_masuk'])
                                        and $nextAbsen['absen_keluar'] > $d['ws_masuk']
                                        and $nextAbsen['absen_keluar'] > $d['absen_masuk']
                                        and $nextAbsen['ws_masuk'] == $d['ws_masuk']
                                        and $nextAbsen['ws_keluar'] == $d['ws_keluar'])
                                    or (!isset($nextAbsen['ws_masuk'])
                                        and (!isset($nextAbsen['absen_masuk']) or
                                            (isset($nextAbsen['absen_masuk'])
                                                and isset($nextAbsen['absen_keluar'])
                                                and $nextAbsen['absen_masuk'] >= $nextAbsen['absen_keluar']))
                                        and (isset($d['ws_masuk']) and date_create($d['ws_masuk']->format('YmdHis'))->add(DateInterval::createFromDateString('1 day')) >= $nextAbsen['absen_keluar']))
                                ) {
                                    if (
                                        isset($absen_keluar_update)
                                        and $absen_keluar_update['absen_keluar'] > $nextAbsen['absen_keluar']
                                        and $d['absen_masuk'] < $nextAbsen['absen_keluar']
                                    ) {
                                        $absen_keluar_update = $nextAbsen;
                                        $absen_keluar_update_index = $nextIndex;
                                    } else if (!isset($absen_keluar_update) and $d['absen_masuk'] < $nextAbsen['absen_keluar']) {
                                        $absen_keluar_update = $nextAbsen;
                                        $absen_keluar_update_index = $nextIndex;
                                    }
                                }
                            }
                        }

                        if (isset($absen_keluar_update) && $absen_keluar_update['absen_keluar'] > $d['absen_masuk']) {
                            if (
                                isset($absen_keluar_update['absen_masuk'])
                                and $absen_keluar_update['absen_keluar'] < $absen_keluar_update['absen_masuk']
                            ) {
                                $absenLemparan = $absen_keluar_update;
                                $absenLemparan['absen_masuk'] = null;
                                $absens[] = $absenLemparan;
                            } else if (
                                (!isset($d['absen_keluar']) and isset($d['absen_masuk'])
                                    //                                    and $nextAbsen['absen_masuk'] > $nextAbsen['absen_keluar']
                                )
                                or (!isset($d['absen_keluar']) and !isset($d['absen_masuk'])
                                    and !isset($absen_keluar_update['absen_masuk']))
                                or (isset($d['absen_masuk']) and isset($d['absen_keluar'])
                                    and $d['absen_keluar'] < $d['absen_masuk'])
                            ) {
                                if (isset($d['absen_keluar'])) {
                                    $absenLemparan = array_merge(array(), $d);
                                    $absenLemparan['absen_masuk'] = null;
                                    $absenLemparan['absen_keluar'] = $d['absen_keluar'];
                                    $absens[] = $absenLemparan;
                                }
                                $d['absen_keluar'] = $absen_keluar_update['absen_keluar'];
                                $absen_keluar_update['absen_keluar'] = null;
                                $absens[$absen_keluar_update_index] = $absen_keluar_update;
                            }
                        }
                        $absens[$index] = $d;
                    }
                    $absensi[$nik] = $absens;
                }

                //                var_dump($absensi);

                foreach ($absensi as $nik => $nikAbsens) {
                    $absensi[$nik] = array_filter($nikAbsens, function ($absen) use ($tanggal_awal, $tanggal_akhir) {
                        return $absen['tanggal'] >= date_create($tanggal_awal) and $absen['tanggal'] <= date_create($tanggal_akhir);
                    });
                }

                //                var_dump($absensi);

                $data = array();

                if ($logging) $this->general->log_schedule_running('ess/scheduler/bak_time_event', array('rfc' => 'Saving Absensi per NIK'));

                foreach ($absensi as $nik => $nikAbsens) {
                    //                    var_dump($nik);
                    //                    var_dump($nikAbsens);
                    $karyawan = $this->dessgeneral->get_karyawan($nik);
                    if (isset($karyawan)) {
                        $tanggal_join = date_create($karyawan->tanggal_join);


                        /** Hapus BAK yang belum ada pengajuan */
                        $this->db->reset_query();
                        $this->db->where('tanggal_absen >=', $tanggal_awal);
                        $this->db->where('tanggal_absen <=', $tanggal_akhir);
                        $this->dgeneral->delete('tbl_bak', array(
                            array(
                                'kolom' => 'nik',
                                'value' => $nik
                            ),
                            array(
                                'kolom' => 'id_bak_alasan',
                                'value' => ESS_BAK_ALASAN_KOSONG
                            ),
                        ));

                        /** Mengurutkan absensi berdasarkan tanggal dan jam masuk atau jam keluar **/
                        $sort = array();
                        foreach ($nikAbsens as $i => $d) {
                            if (isset($d['absen_masuk']))
                                $sort[$i] = (isset($d['absen_masuk']) ? $d['absen_masuk']->getTimestamp() : $d['tanggal']->getTimestamp());
                            else if (isset($d['absen_keluar']))
                                $sort[$i] = (isset($d['absen_keluar']) ? $d['absen_keluar']->getTimestamp() : $d['tanggal']->getTimestamp());
                            else
                                $sort[$i] = $d['tanggal']->getTimestamp();
                        }

                        array_multisort($sort, SORT_ASC, $nikAbsens);

                        if (isset($tanggal_join)) {
                            $nikAbsens = array_filter($nikAbsens, function ($nikAbsen) use ($tanggal_join) {
                                return $nikAbsen['tanggal'] >= $tanggal_join;
                            });
                        }

                        $dataBAK = array();

                        foreach ($nikAbsens as $i => $d) {
                            $absensiHariSama = count(array_filter($nikAbsens, function ($i) use ($d) {
                                return $d['tanggal'] == $i['tanggal'];
                            }));
                            $absenMasuk = isset($d['absen_masuk']) ? $d['absen_masuk']->format('Y-m-d H:i') : '-';
                            $absenKeluar = isset($d['absen_keluar']) ? $d['absen_keluar']->format('Y-m-d H:i') : '-';
                            $pakaiTolerance = false;
                            if (isset($d['ws_tolerance'])) {
                                $pakaiTolerance = true;
                                $jadwalAbsenMasuk = $d['ws_tolerance']->format('Y-m-d H:i');
                            } else if (isset($d['ws_masuk']))
                                $jadwalAbsenMasuk = $d['ws_masuk']->format('Y-m-d H:i');
                            else
                                $jadwalAbsenMasuk = '-';

                            $jadwalAbsenKeluar = isset($d['ws_keluar']) ? $d['ws_keluar']->format('Y-m-d H:i') : '-';
                            $isOff = ($d['tipe'] != 'L' and isset($d['ws_masuk']) && isset($d['ws_keluar'])) ? false : true;

                            $absenKe = 1;
                            foreach ($nikAbsens as $j => $d2) {
                                if (
                                    $d2['tanggal'] == $d['tanggal'] and $i > $j and
                                    (isset($d2['absen_masuk']) or isset($d2['absen_keluar']))
                                ) {
                                    $absenKe++;
                                }
                            }
                            $d['jenis'] = $absenKe;

                            $absenSaved = array(
                                'nik' => $d['nik'],
                                'tanggal' => $d['tanggal']->format('Y-m-d'),
                                'absen_masuk' => $absenMasuk,
                                'absen_keluar' => $absenKeluar,
                                'jadwal' => ($isOff ? 'Off' : (isset($d['ws_tolerance']) ? 'Tolerance ' : '') . ' Work ' . $jadwalAbsenMasuk . ' - ' . $jadwalAbsenKeluar),
                                'absen_ke' => $d['jenis'],
                                'tipe' => $d['tipe'],
                                'pair' => $d['absen_keluar_paired'],
                            );

                            $id_bak_status = ($absenMasuk != "-" and $absenKeluar != "-") ?
                                ESS_BAK_STATUS_COMPLETE : ESS_BAK_STATUS_DEFAULT;

                            $tipe = empty($d['tipe']) ? '-' : $d['tipe'];

                            $tipe = $isOff ? 'L' : $tipe;

                            foreach ($check_holiday as $check) {
                                if ($check['tanggal'] == $d['tanggal']->format('Y-m-d'))
                                    $tipe = 'L';
                            }

                            if (in_array($d['tipe'], array(ESS_CUTI_JENIS_DINAS, ESS_CUTI_JENIS_MEETING, ESS_CUTI_JENIS_TRAINING)))
                                $tipe = $d['tipe'];

                            foreach ($check_cuti as $check) {
                                $tanggalAwalCheck = date_create($check->tanggal_awal);
                                $tanggalAkhirCheck = date_create($check->tanggal_akhir);
                                $tanggalCheck = $d['tanggal'];
                                if ($tanggalAwalCheck <= $tanggalCheck && $tanggalCheck <= $tanggalAkhirCheck) {
                                    $tipe = empty($check->kode) ? $tipe : $check->kode;
                                }
                            }

                            $check_bak = $this->dbak->get_bak(array(
                                'nik' => $d['nik'],
                                'tanggal_absen' => $d['tanggal']->format('Y-m-d'),
                                'jenis' => $d['jenis'],
                                'single_row' => true
                            ));

                            /** Pengecekan data yang sama tanggal dan jenis nya sudah di simpah atau belum
                             * agar tidak tertimpa.
                             * @var $checkSaved
                             */
                            //                        $checkSaved = array_filter($absensSaved, function ($v) use ($d, $jenis) {
                            //                            if (
                            //                                $v['tanggal'] == $d['tanggal']->format('Y-m-d')
                            //                                and $v['jenis'] == $jenis
                            //                            )
                            //                                return true;
                            //                            else
                            //                                return false;
                            //                        });

                            /** TODO:disabled sementara */
                            //                        if (count($checkSaved) == 0) {
                            //                        if (true) {
                            //                            $absensSaved[] = array(
                            //                                'tanggal' => $d['tanggal']->format('Y-m-d'),
                            //                                'jenis' => $jenis
                            //                            );
                            $resultDb = false;
                            $override = false;
                            $absenSaved['bak_cek'] = isset($check_bak);

                            if (isset($check_bak)) {
                                if (
                                    $check_bak->absen_masuk == '-'
                                    and $check_bak->absen_keluar == '-'
                                ) {
                                    $override = true;
                                } else if (
                                    $check_bak->absen_masuk != '-'
                                    and $check_bak->absen_keluar == '-'
                                    and !isset($d['absen_masuk'])
                                    and isset($d['absen_keluar'])
                                    and date_create($check_bak->tanggal_masuk . ' ' . $check_bak->absen_masuk) < $d['absen_keluar']
                                ) {
                                    $override = false;
                                } else if (
                                    $check_bak->absen_masuk != '-'
                                    and $check_bak->absen_keluar != '-'
                                    and isset($d['absen_masuk'])
                                ) {
                                    if (
                                        date_create($check_bak->tanggal_keluar . ' ' . $check_bak->absen_keluar) <= $d['absen_masuk']
                                    ) {
                                        $check_bak = null;
                                    }
                                } else if (
                                    (isset($d['absen_masuk'])
                                        or isset($d['absen_keluar']))
                                    and $check_bak->id_bak_alasan == ESS_BAK_ALASAN_KOSONG
                                ) {
                                    $check_bak = null;
                                }
                            }

                            if ((isset($check_bak)) or $override) {
                                if (
                                    (isset($d['absen_masuk']) or isset($d['absen_keluar']))
                                    and $absensiHariSama >= 1
                                ) {

                                    if (
                                        in_array(
                                            $check_bak->id_bak_status,
                                            array(
                                                ESS_BAK_STATUS_DEFAULT,
                                                ESS_BAK_STATUS_COMPLETE,
                                                ESS_BAK_STATUS_DISETUJUI,
                                                ESS_BAK_STATUS_DISETUJUI_OLEH_HR
                                            )
                                        )
                                    ) {
                                        $data_bak = array(
                                            'nik' => $d['nik'],
                                            'tipe' => $tipe,
                                            'tanggal_absen' => $d['tanggal']->format('Y-m-d'),
                                            'jenis' => intval($d['jenis'])
                                        );

                                        if (
                                            in_array(
                                                $check_bak->id_bak_status,
                                                array(
                                                    ESS_BAK_STATUS_DEFAULT,
                                                    ESS_BAK_STATUS_COMPLETE
                                                )
                                            )
                                        ) {
                                            if ($check_bak->id_bak_alasan == ESS_BAK_ALASAN_KOSONG) {
                                                $data_bak['status_awal'] = $id_bak_status;
                                                $data_bak['id_bak_status'] = $id_bak_status;
                                            }
                                        } else if (in_array(
                                            $check_bak->id_bak_status,
                                            array(
                                                ESS_BAK_STATUS_DISETUJUI,
                                                ESS_BAK_STATUS_DISETUJUI_OLEH_HR
                                            )
                                        )) {
                                            if (
                                                $id_bak_status == ESS_BAK_STATUS_COMPLETE and ($check_bak->absen_masuk != '-' and
                                                    $check_bak->absen_keluar != '-')
                                            )
                                                $data_bak['id_bak_status'] = $id_bak_status;
                                        }


                                        if ($check_bak->id_bak_alasan != ESS_BAK_ALASAN_KOSONG) {
                                            if ($absenMasuk != '-') {
                                                $data_bak['absen_masuk'] = $d['absen_masuk']->format('H:i:s');
                                                $data_bak['tanggal_masuk'] = $d['absen_masuk']->format('Y-m-d');
                                            }
                                            //                                        else {
                                            //                                            $data_bak['absen_masuk'] = '-';
                                            //                                            $data_bak['tanggal_masuk'] = null;
                                            //                                        }
                                            if ($absenKeluar != '-') {
                                                $data_bak['absen_keluar'] = $d['absen_keluar']->format('H:i:s');
                                                $data_bak['tanggal_keluar'] = $d['absen_keluar']->format('Y-m-d');
                                            }
                                            //                                        else {
                                            //                                            $data_bak['absen_keluar'] = '-';
                                            //                                            $data_bak['tanggal_keluar'] = null;
                                            //                                        }

                                        }


                                        if (isset($d['ws_masuk'])) {
                                            if (isset($d['ws_tolerance'])) {
                                                $data_bak['jadwal_absen_masuk'] = $d['ws_tolerance']->format('H:i');
                                                $data_bak['jadwal_absen'] = $d['ws_tolerance']->format('Y-m-d');
                                            } else {
                                                $data_bak['jadwal_absen_masuk'] = $d['ws_masuk']->format('H:i');
                                                $data_bak['jadwal_absen'] = $d['ws_masuk']->format('Y-m-d');
                                            }
                                        }
                                        //                                    else {
                                        //                                        $data_bak['jadwal_absen_masuk'] = null;
                                        //                                        $data_bak['jadwal_absen'] = null;
                                        //                                    }

                                        if (isset($d['ws_keluar']))
                                            $data_bak['jadwal_absen_keluar'] = $d['ws_keluar']->format('H:i');
                                        //                                    else
                                        //                                        $data_bak['jadwal_absen_keluar'] = null;

                                        $dataBAK[] = array(
                                            'data' => $data_bak,
                                            'id' => $check_bak->id_bak,
                                            'insert' => false
                                        );
                                    }
                                }
                            } else if (
                                $absensiHariSama == 1
                                or ($absensiHariSama > 1
                                    and (isset($d['absen_masuk'])
                                        or isset($d['absen_keluar'])))
                            ) {
                                $cutoff_live = date_create('2019-05-06');

                                $new_method = 1;
                                if ($d['tanggal'] < $cutoff_live)
                                    $new_method = 0;

                                $data_bak = array(
                                    'status_awal' => $id_bak_status,
                                    'id_bak_status' => $id_bak_status,
                                    'nik' => $d['nik'],
                                    'tipe' => $tipe,
                                    'tanggal_absen' => $d['tanggal']->format('Y-m-d'),
                                    'jenis' => intval($d['jenis']),
                                    'id_bak_alasan' => '0',
                                    'new_method' => $new_method,
                                    'login_migrasi' => 0,
                                    'tanggal_migrasi' => date('Y-m-d H:i:s'),
                                    'na' => 'n',
                                    'del' => 'n',
                                );

                                if ($absenMasuk != '-') {
                                    $data_bak['absen_masuk'] = $d['absen_masuk']->format('H:i:s');
                                    $data_bak['tanggal_masuk'] = $d['absen_masuk']->format('Y-m-d');
                                } else {
                                    $data_bak['absen_masuk'] = '-';
                                    $data_bak['tanggal_masuk'] = null;
                                }

                                if ($absenKeluar != '-') {
                                    $data_bak['absen_keluar'] = $d['absen_keluar']->format('H:i:s');
                                    $data_bak['tanggal_keluar'] = $d['absen_keluar']->format('Y-m-d');
                                } else {
                                    $data_bak['absen_keluar'] = '-';
                                    $data_bak['tanggal_keluar'] = null;
                                }

                                if (isset($d['ws_masuk'])) {
                                    if (isset($d['ws_tolerance'])) {
                                        $data_bak['jadwal_absen_masuk'] = $d['ws_tolerance']->format('H:i');
                                        $data_bak['jadwal_absen'] = $d['ws_tolerance']->format('Y-m-d');
                                    } else {
                                        $data_bak['jadwal_absen_masuk'] = $d['ws_masuk']->format('H:i');
                                        $data_bak['jadwal_absen'] = $d['ws_masuk']->format('Y-m-d');
                                    }
                                } else {
                                    $data_bak['jadwal_absen_masuk'] = null;
                                    $data_bak['jadwal_absen'] = null;
                                }

                                if (isset($d['ws_keluar']))
                                    $data_bak['jadwal_absen_keluar'] = $d['ws_keluar']->format('H:i');
                                else
                                    $data_bak['jadwal_absen_keluar'] = null;

                                $dataBAK[] = array(
                                    'data' => $data_bak,
                                    'insert' => true
                                );
                            }

                            $absenSaved['saved'] = $resultDb;
                            $absenSaved['cek_next'] = $d['cek_next'];

                            $data[] = $absenSaved;
                        }

                        $this->db->reset_query();
                        $this->db->group_start();
                        $this->db->where('id_bak_alasan', ESS_BAK_ALASAN_HAPUS_BAK);
                        $this->db->where_in('id_bak_status', array(ESS_BAK_STATUS_DISETUJUI, ESS_BAK_STATUS_DISETUJUI_OLEH_HR));
                        $this->db->group_end();
                        foreach ($dataBAK as $bak) {
                            $this->db->group_start();
                            $this->db->where('tanggal_absen', $bak['data']['tanggal_absen']);
                            $this->db->where('nik', $bak['data']['nik']);
                            //                        $this->db->where('jenis !=', intvalda($bak['data']['jenis']));
                            if (isset($bak['data']['absen_masuk']) || isset($bak['data']['absen_keluar'])) {
                                $this->db->group_start();
                                if (isset($bak['data']['absen_masuk']))
                                    $this->db->where('absen_masuk !=', $bak['data']['absen_masuk']);
                                if (isset($bak['data']['absen_keluar']))
                                    $this->db->or_where('absen_keluar !=', $bak['data']['absen_keluar']);
                                $this->db->group_end();
                            }
                            $this->db->group_end();
                        }
                        $this->db->delete('tbl_bak', '1=1');
                        //                    var_dump($this->db->last_query());die();
                        $this->db->reset_query();

                        foreach ($dataBAK as $i => $bak) {

                            if ($bak['insert']) {
                                $resultDb = $this->dgeneral->insert('tbl_bak', $bak['data']);
                            } else {
                                $resultDb = $this->dgeneral->update('tbl_bak', $bak['data'], array(
                                    array(
                                        'kolom' => 'id_bak',
                                        'value' => $bak['id']
                                    )
                                ));
                            }

                            $data[$i]['saved'] = $resultDb;
                        }
                    }
                }

                if ($logging) $this->general->log_schedule_running('ess/scheduler/bak_time_event', array('rfc' => 'Saving Absensi per NIK Selesai'));
            }

            //            die();

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                //                $this->dgeneral->commit_transaction();
                $msg = "Sinkronisasi data BAK dari SAP berhasil";
                $sts = "OK";
            }

            $this->general->log_schedule_running('ess/scheduler/bak_time_event', array('end_time' => date('Y-m-d H:i:s')));

            $this->general->closeDb();
        } else {
            $msg = "Koneksi SAP error";
            $sts = "NotOK";
        }

        $sap->logoff();

        if (isset($filter['mode'])) {
            switch ($filter['mode']) {
                case 'table':
                    return $this->load->view('scheduler/absen_table', compact('data', 'delete_request'));
                    break;
                case 'json':
                    echo json_encode(compact('msg', 'sts', 'data', 'delete_request'));
                    break;
                default:
                    echo json_encode(compact('msg', 'sts'));
                    break;
            }
        }
    }

    public function cuti_ijin()
    {
        $this->general->log_schedule_master('ess/scheduler/cuti_ijin', array(
            'sesi' => date('H:i'),
            'source' => 'SAP HRIS',
            'terminal' => '-',
            'destination' => 'Portal',
            'keterangan' => 'SYNC data CUTI IJIN karyawan dari HRIS ke PORTAL'
        ));

        $this->load->library('sap');
        $gen = $this->generate;

        $today = date_create();

        $koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);

        $data_koneksi = $koneksi['ESS'];

        $constr = array(
            "logindata" => array(
                "ASHOST" => $data_koneksi['ASHOST'],
                "SYSNR" => $data_koneksi['SYSNR'],
                "CLIENT" => $data_koneksi['CLIENT'],
                "USER" => $data_koneksi['USER'],
                "PASSWD" => $data_koneksi['PASSWD']
            ),
            "show_errors" => $data_koneksi['DEBUG'],
            "debug" => $data_koneksi['DEBUG']
        );

        $sap = new saprfc($constr);

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $personal_area = $this->dessgeneral->get_personal_area();
        /*
                // SYNC RFC SAP JENIS IJIN
                foreach ($personal_area as $area) {
                    if (isset($area->moabw)) {
                        $this->db->query("DELETE FROM tbl_cuti_ijin where kode_grouping_area= ?", array($area->moabw));
                        $resultArea = $sap->callFunction("Z_LIST_ABSENCE_TYPE",
                            array(
                                array("IMPORT", "I_MOABW", $area->moabw),
                                array("TABLE", "T_DATA", array())
                            )
                        );

                        if ($sap->getStatus() == SAPRFC_OK) {
                            foreach ($resultArea["T_DATA"] as $d) {
                                $check = $this->dessgeneral->get_cuti_ijin(
                                    array(
                                        'kode_grouping_area' => $d['MOABW'],
                                        'kode' => $d['SUBTY'],
                                        'single_row' => true
                                    )
                                );

                                if (isset($check)) {
                                    $data_row = $this->dgeneral->basic_column('update',
                                        array(
                                            'nama' => $d['ATEXT'],
                                            'tanggal_awal' => date_create($d['BEGDA'])->format('Y-m-d'),
                                            'tanggal_akhir' => date_create($d['ENDDA'])->format('Y-m-d'),
                                            'jumlah' => $d['MAXTG']
                                        )
                                    );

                                    $this->dgeneral->update('tbl_cuti_ijin',
                                        $data_row,
                                        array(
                                            array(
                                                'kolom' => 'id_cuti_ijin',
                                                'value' => $check->id_cuti_ijin
                                            )
                                        )
                                    );
                                } else {
                                    $data_row = $this->dgeneral->basic_column('insert',
                                        array(
                                            'kode' => $d['SUBTY'],
                                            'kode_grouping_area' => $d['MOABW'],
                                            'nama' => $d['ATEXT'],
                                            'tanggal_awal' => date('Y-m-d', strtotime($d['BEGDA'])),
                                            'tanggal_akhir' => date('Y-m-d', strtotime($d['ENDDA'])),
                                            'jumlah' => $d['MAXTG']
                                        )
                                    );
                                    $this->dgeneral->insert('tbl_cuti_ijin', $data_row);
                                }
                            }
                        }
                    }
                }*/

        // SYNC RFC SAP KUOTA CUTI
        $result = $sap->callFunction(
            "Z_LIST_ABSENCE_QUOTAS",
            array(
                array("TABLE", "T_DETAIL", array()),
                array("TABLE", "T_DATA", array())
            )
        );

        if ($sap->getStatus() == SAPRFC_OK) {

            /*foreach ($result['T_DETAIL'] as $data) {
                $cek_cuti = $this->dcutiijin->get_cuti(
                    array(
                        'tanggal_awal' => array(
                            date_format(date_create_from_format('Ymd', $data['BEGDA']), 'Y-m-d'),
                            date_format(date_create_from_format('Ymd', $data['ENDDA']), 'Y-m-d')
                        ),
                        'tipe' => array('Cuti', 'Ijin'),
                        'id_tipe_status' => array(ESS_CUTI_STATUS_DISETUJUI_ATASAN),
                        'nik' => $data['PERNR']
                    )
                );

                if (count($cek_cuti) > 0) {
                    foreach ($cek_cuti as $cuti) {
                        $data_row = array();
                        $data_row['id_cuti_status'] = ESS_CUTI_STATUS_DISETUJUI_HR;
                        $data_row['tanggal_migrasi'] = date("Y-m-d H:i:s");

                        $this->dgeneral->update('tbl_cuti', $data_row,
                            array(
                                array(
                                    'kolom' => 'id_cuti',
                                    'value' => $cuti->id_cuti
                                )
                            )
                        );

                        $cek_history = $this->dessgeneral->get_history_persetujuan($cuti->id_cuti, ESS_CUTI_STATUS_DISETUJUI_HR);

                        if (count($cek_history) == 0) {
                            $data_history = array(
                                'id_cuti' => $cuti->id_cuti,
                                'id_cuti_status' => ESS_CUTI_STATUS_DISETUJUI_HR
                            );

                            $data_history = $this->dgeneral->basic_column('insert_simple', $data_history);

                            $this->dgeneral->insert('tbl_cuti_history', $data_history);
                        }
                    }


                }
            }*/

            if (isset($result['T_DATA']) && count($result['T_DATA'])) {

                if (!empty($nik))
                    $this->db->query('delete from tbl_cuti_cuti where nik = ?', array($nik));
                else
                    $this->db->query('delete from tbl_cuti_cuti');

                foreach ($result['T_DATA'] as $data) {
                    $sisa = $gen->format_nilai("SAPSQL", $data['ANZHL']) - $gen->format_nilai("SAPSQL", $data['KVERB']);

                    $data_cuti = array(
                        'nik' => $data['PERNR'],
                        'kode' => $data['KTART'],
                        'nama' => $data['KTEXT'],
                        'tanggal_awal' => $data['BEGDA'],
                        'tanggal_akhir' => $data['ENDDA'],
                        'jumlah' => $gen->format_nilai("SAPSQL", $data['ANZHL']),
                        'terpakai' => $gen->format_nilai("SAPSQL", $data['KVERB']),
                        'sisa' => $sisa,
                        'batas_negatif' => $gen->format_nilai("SAPSQL", $data['QTNEG'])
                    );

                    $data_cuti = $this->dgeneral->basic_column('insert', $data_cuti);

                    $this->dgeneral->insert('tbl_cuti_cuti', $data_cuti);
                }
            }
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else if ($sap->getStatus() != SAPRFC_OK) {
            $this->dgeneral->rollback_transaction();
            $msg = @saprfc_exception($sap->func_id);
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Sinkronisasi Cuti dari SAP berhasil";
            $sts = "OK";
        }

        $sap->logoff();

        $this->general->log_schedule_running('ess/scheduler/cuti_ijin', array('end_time' => date('Y-m-d H:i:s')));

        echo json_encode(array('msg' => $msg, 'sts' => $sts));
    }

    public function medical()
    {
        $this->general->log_schedule_master('ess/scheduler/medical', array(
            'sesi' => date('H:i'),
            'source' => 'SAP HRIS',
            'terminal' => '-',
            'destination' => 'Portal',
            'keterangan' => 'SYNC data MEDICAL karyawan dari HRIS ke PORTAL'
        ));

        $this->load->library('sap');

        $filter['nik'] = $this->input->get_post('nik', true);
        $filter['lokasi'] = $this->input->get_post('lokasi', true);

        $nik = isset($filter['nik']) ? $filter['nik'] : "";
        $lokasi = isset($filter['lokasi']) ? $filter['lokasi'] : "";

        if (isset($nik)) {
            $check_nik = $this->dessgeneral->get_karyawan($nik);
            if (!isset($check_nik)) {
                $return = array('sts' => 'NotOK', 'msg' => 'NIK tidak ditemukan');
                echo json_encode($return);
                die();
            }
        }

        $gen = $this->generate;

        $koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);

        $data_koneksi = $koneksi['ESS'];

        $constr = array(
            "logindata" => array(
                "ASHOST" => $data_koneksi['ASHOST'],
                "SYSNR" => $data_koneksi['SYSNR'],
                "CLIENT" => $data_koneksi['CLIENT'],
                "USER" => $data_koneksi['USER'],
                "PASSWD" => $data_koneksi['PASSWD']
            ),
            "show_errors" => $data_koneksi['DEBUG'],
            "debug" => $data_koneksi['DEBUG']
        );

        $sap = new saprfc($constr);

        $ho = '';
        if ($lokasi == 'ho')
            $ho = 'X';

        $result = $sap->callFunction(
            "Z_GET_MEDICAL_INFORMATION",
            array(
                array("IMPORT", "I_HO", $ho),
                array("IMPORT", "I_PERNR", $nik),
                array("TABLE", "T_DATA", array()),
                array("TABLE", "T_CLAIMS", array()),
                array("TABLE", "T_KAMAR", array())
            )
        );

        if ($sap->getStatus() == SAPRFC_OK) {
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            if (isset($result['T_CLAIMS']) && count($result['T_CLAIMS']) > 0) {


                $result['T_CLAIMS'] = array_reverse($result['T_CLAIMS']);
                $plafonUpdate = array();

                if (isset($result['T_DATA']) && count($result['T_DATA']) > 0) {
                    foreach ($result['T_DATA'] as $data) {
                        $plafonUpdate[$data['PERNR']] = array(
                            'nik' => $data['PERNR'],
                            'BRIN' => $gen->format_nilai("SAPSQL", $data['BRIN']),
                            'BBNR' => $gen->format_nilai("SAPSQL", $data['BBNR']),
                            'BBCS' => $gen->format_nilai("SAPSQL", $data['BBCS']),
                            'BRJL' => $gen->format_nilai("SAPSQL", $data['BRJL']),
                            'VAL_BRJL' => $gen->format_nilai("SAPSQL", $data['VAL_BRJL']),
                            'BBKI' => $gen->format_nilai("SAPSQL", $data['BBKI']),
                            'VAL_BBKI' => $gen->format_nilai("SAPSQL", $data['VAL_BBKI']),
                            'BLNS' => $gen->format_nilai("SAPSQL", $data['BLNS']),
                            'VAL_BLNS' => $gen->format_nilai("SAPSQL", $data['VAL_BLNS'])
                        );
                    }
                }

                $fbkTobeUpdated = array();
                $plafons = array();

                $cutoffs = array();

                foreach ($result['T_CLAIMS'] as $data_claim) {
                    if (!isset($plafons[$data_claim['PERNR']]) || !array_key_exists($data_claim['PERNR'], $plafons)) {
                        $plafons[$data_claim['PERNR']] = $this->dmedical->get_plafon(array(
                            "nik" => $data_claim['PERNR'],
                            "single_row" => true
                        ));
                        if (isset($plafons[$data_claim['PERNR']])) {
                            foreach ($plafons[$data_claim['PERNR']] as $key => $plafon) {
                                if (array_key_exists($key, $plafonUpdate[$data_claim['PERNR']])) {
                                    if ($plafonUpdate[$data_claim['PERNR']][$key] != $plafon)
                                        $plafon = intval($plafonUpdate[$data_claim['PERNR']][$key]);
                                }
                                $plafons[$data_claim['PERNR']]->$key = $plafon;
                            }
                        } else {
                            $plafons[$data_claim['PERNR']] = new \stdClass();
                            foreach ($plafonUpdate[$data_claim['PERNR']] as $key => $plafon) {
                                $plafons[$data_claim['PERNR']]->$key = $plafon;
                            }
                        }
                    }
                    $plafon = $plafons[$data_claim['PERNR']];
                    $filterKwitansi = array(
                        //                                'single_row' => true,
                        'nik' => $data_claim['PERNR'],
                        'kode' => $data_claim['BPLAN'],
                        'disetujui' => true,
                        'nomor_kwitansi' => $data_claim['BILNR'],
                        'id_fbk_status' => array(ESS_MEDICAL_STATUS_LENGKAP, ESS_MEDICAL_STATUS_DISETUJUI)
                    );


                    /** Ditambah filter jumlah klaim yang sama dengan SAP */
                    $filterKwitansi['amount_ganti'] = floatval($gen->format_nilai('SAPSQL', $data_claim['CLAMT']));

                    $list_kwitansi = $this->dmedical->get_fbk_kwitansi($filterKwitansi);

                    /** Pengecekan kwitansi mana yang tanggal nya sama, sekaligus pengecekan jika ada
                     *  pengajuan yang melewati tanggal cutoff
                     */

                    $cek_kwitansi = null;

                    foreach ($list_kwitansi as $check) {
                        if (
                            date_create($check->tanggal_kwitansi)->format('Y-m-d') == $gen->format_nilai('SAP2SQLDATE', $data_claim['BILDT'])
                        ) {
                            $cek_kwitansi = $check;
                        } else {
                            $tahun_kwitansi = intval(date_create($check->tanggal_kwitansi)->format('Y'));

                            if ($tahun_kwitansi < intval(date('Y'))) {
                                if (isset($cutoffs[$tahun_kwitansi])) {
                                    $cutoff = $cutoffs[$tahun_kwitansi];
                                } else {
                                    $cutoff = $this->dmedical->get_fbk_cutoff(
                                        array(
                                            'tahun' => $tahun_kwitansi,
                                            'single_row' => true
                                        )
                                    );
                                    $cutoffs[$tahun_kwitansi] = $cutoff;
                                }

                                if (isset($cutoff)) {
                                    $jadwal = date_create($cutoff->jadwal);
                                    // $tanggal_kwitansi_cutoff = date_create('02-01-' . $jadwal->modify('+1 year')->format('Y'));
                                    if (
                                        date_create($cutoff->jadwal) <= date_create($check->tanggal_buat)
                                        // && $tanggal_kwitansi_cutoff->format('Y-m-d') == $gen->format_nilai('SAP2SQLDATE', $data_claim['BILDT'])
                                    ) {
                                        $cek_kwitansi = $check;
                                    }
                                }
                            }
                        }
                    }

                    if (isset($cek_kwitansi)) {
                        $data_update_kwitansi = array(
                            'amount_ganti' => $gen->format_nilai('SAPSQL', $data_claim['CLAMT']),
                            'tanggal_kwitansi' => $gen->format_nilai('SAP2SQLDATE', $data_claim['BILDT'])
                        );

                        if (!isset($cek_kwitansi->tanggal_migrasi)) {
                            $data_update_kwitansi['tanggal_migrasi'] = date('Y-m-d H:i:s');
                            $data_update_kwitansi['status_migrasi'] = $data_claim['STATS'];
                            $data_update_kwitansi['login_migrasi'] = base64_decode($this->session->userdata('-id_user-'));
                        }

                        $this->dgeneral->update('tbl_fbk_kwitansi', $data_update_kwitansi, array(
                            array(
                                'kolom' => 'id_fbk_kwitansi',
                                'value' => $cek_kwitansi->id_fbk_kwitansi
                            )
                        ));

                        if ($data_claim['STATS'] == 'A') {
                            if (in_array($data_claim['BPLAN'], array('BRJL', 'BLNS', 'BBKI')))
                                $kolomPlafon = 'VAL_' . $data_claim['BPLAN'];
                            else
                                $kolomPlafon = $data_claim['BPLAN'];

                            $kwitansi = $this->dmedical->get_fbk_kwitansi(
                                array(
                                    'disetujui' => true,
                                    'id_fbk' => $cek_kwitansi->id_fbk
                                )
                            );
                            $total_ganti = 0;
                            if (
                                isset($fbkTobeUpdated[$cek_kwitansi->id_fbk]) and
                                isset($fbkTobeUpdated[$cek_kwitansi->id_fbk]['total_ganti'])
                            )
                                $total_ganti = $fbkTobeUpdated[$cek_kwitansi->id_fbk]['total_ganti'];

                            $total_ganti += $gen->format_nilai('SAPSQL', $data_claim['CLAMT']);

                            //                                    foreach ($kwitansi as $kw)
                            //                                        $total_ganti += $kw->amount_ganti;

                            $sisaPlafon = 0;
                            switch ($data_claim['BPLAN']) {
                                case "BRJL":
                                    $sisaPlafon = $plafon->$kolomPlafon;
                                    break;
                                case "BLNS":
                                    $sisaPlafon = $plafon->$kolomPlafon;
                                    break;
                                case "BBKI":
                                    $sisaPlafon = $plafon->$kolomPlafon;
                                    break;
                            }

                            $plafonAwal = $sisaPlafon;

                            //                            if ($sisaPlafon > 0) {
                            $sisaPlafon += $gen->format_nilai('SAPSQL', $data_claim['CLAMT']);
                            //                            }

                            $cek_fbk = $this->dmedical->get_fbk(
                                array(
                                    'id' => $cek_kwitansi->id_fbk,
                                    'id_fbk_status' => array(ESS_MEDICAL_STATUS_LENGKAP, ESS_MEDICAL_STATUS_DISETUJUI),
                                    'single_row' => true
                                )
                            );

                            if (isset($cek_fbk)) {
                                //                                        if ($sisaPlafon > $plafonAwal)
                                //                                            $plafonAwal = $sisaPlafon;
                                $fbkTobeUpdated[$cek_kwitansi->id_fbk]['jenis'] = $data_claim['BPLAN'];
                                $fbkTobeUpdated[$cek_kwitansi->id_fbk]['plafonAwal'] = $plafonAwal;
                                $fbkTobeUpdated[$cek_kwitansi->id_fbk]['sisaPlafon'] = $sisaPlafon;
                                $fbkTobeUpdated[$cek_kwitansi->id_fbk]['total_ganti'] = $total_ganti;
                                $plafon->$kolomPlafon = $sisaPlafon;
                            }
                        }
                    }

                    $plafons[$data_claim['PERNR']] = $plafon;
                }

                //                        var_dump($fbkTobeUpdated);die();

                foreach ($fbkTobeUpdated as $id_fbk => $fbk) {
                    $cek_fbk = $this->dmedical->get_fbk(
                        array(
                            'id' => $id_fbk,
                            'id_fbk_status' => array(ESS_MEDICAL_STATUS_LENGKAP, ESS_MEDICAL_STATUS_DISETUJUI),
                            'single_row' => true
                        )
                    );

                    $fbkUpdate = array(
                        'total_ganti' => $fbk['total_ganti'],
                        'id_fbk_status' => ESS_MEDICAL_STATUS_DISETUJUI
                    );

                    if (isset($cek_fbk) && isset($cek_fbk->tanggal_migrasi)) {
                        $fbkUpdate['login_migrasi'] = base64_decode($this->session->userdata('-id_user-'));
                        $fbkUpdate['tanggal_migrasi'] = date('Y-m-d H:i:s');
                    }

                    switch ($fbk['jenis']) {
                        case "BRJL":
                            $fbkUpdate['plafon_medical'] = $fbk['sisaPlafon'];
                            break;
                        case "BLNS":
                            $fbkUpdate['plafon_lensa'] = $fbk['sisaPlafon'];
                            break;
                        case "BBKI":
                            $fbkUpdate['plafon_frame'] = $fbk['sisaPlafon'];
                            break;
                    }

                    $this->db->where('id_fbk', $id_fbk);
                    $this->db->where_in('id_fbk_status', array(ESS_MEDICAL_STATUS_LENGKAP, ESS_MEDICAL_STATUS_DISETUJUI));
                    $this->db->update(
                        'tbl_fbk',
                        $fbkUpdate
                    );

                    $cek_history = $this->dessgeneral->get_medical_history(
                        array(
                            'id_fbk' => $id_fbk,
                            'id_fbk_status' => ESS_MEDICAL_STATUS_DISETUJUI,
                            'single_row' => true
                        )
                    );

                    if (!isset($cek_history)) {
                        $this->dgeneral->insert(
                            'tbl_fbk_history',
                            array(
                                'id_fbk' => $id_fbk,
                                'id_fbk_status' => ESS_MEDICAL_STATUS_DISETUJUI,
                                'login_buat' => base64_decode($this->session->userdata('-id_user-')),
                                'tanggal_buat' => date('Y-m-d H:i:s')
                            )
                        );
                    }
                }
            }

            if (isset($result['T_DATA']) && count($result['T_DATA']) > 0) {
                if (!empty($nik))
                    $this->db->query('delete from tbl_fbk_plafon where nik = ?', array($nik));
                else
                    $this->db->query('delete from tbl_fbk_plafon');

                foreach ($result['T_DATA'] as $data) {

                    $data_fbk = array(
                        'nik' => $data['PERNR'],
                        'BRIN' => $gen->format_nilai("SAPSQL", $data['BRIN']),
                        'BBNR' => $gen->format_nilai("SAPSQL", $data['BBNR']),
                        'BBCS' => $gen->format_nilai("SAPSQL", $data['BBCS']),
                        'BRJL' => $gen->format_nilai("SAPSQL", $data['BRJL']),
                        'VAL_BRJL' => $gen->format_nilai("SAPSQL", $data['VAL_BRJL']),
                        'BBKI' => $gen->format_nilai("SAPSQL", $data['BBKI']),
                        'VAL_BBKI' => $gen->format_nilai("SAPSQL", $data['VAL_BBKI']),
                        'BLNS' => $gen->format_nilai("SAPSQL", $data['BLNS']),
                        'VAL_BLNS' => $gen->format_nilai("SAPSQL", $data['VAL_BLNS']),
                        'login_migrasi' => base64_decode($this->session->userdata('-id_user-')),
                        'tanggal_migrasi' => date('Y-m-d H:i:s')
                    );

                    $this->dgeneral->insert('tbl_fbk_plafon', $data_fbk);
                }
            }

            if (isset($result['T_KAMAR']) && count($result['T_KAMAR']) > 0) {
                foreach ($result['T_KAMAR'] as $data_claim) {
                    $plafon_kamar = $this->dmedical->get_plafon_kamar(array(
                        'single_row' => true,
                        'id_golongan' => $data_claim['PERSK']
                    ));
                    if (!isset($plafon_kamar)) {
                        $this->dgeneral->insert(
                            'tbl_fbk_plafon_kamar',
                            $this->dgeneral->basic_column('insert', array(
                                'nominal' => floatval($data_claim['DMBTR']) * 100, //$this->generate->format_nilai('SAPSQL', $data_claim['DMBTR'])
                                'id_golongan' => $data_claim['PERSK']
                            ))
                        );
                    } else {
                        $this->dgeneral->update(
                            'tbl_fbk_plafon_kamar',
                            $this->dgeneral->basic_column('update', array(
                                'nominal' => floatval($data_claim['DMBTR']) * 100 //$this->generate->format_nilai('SAPSQL', $data_claim['DMBTR'])
                            )),
                            array(
                                array(
                                    'kolom' => 'id_golongan',
                                    'value' => $data_claim['PERSK']
                                )
                            )
                        );
                    }
                }
            }

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Sinkronisasi data medical dari SAP berhasil";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $msg = @saprfc_exception($sap->func_id);
            $sts = "NotOK";
        }

        $sap->logoff();

        $this->general->log_schedule_running('ess/scheduler/medical', array('end_time' => date('Y-m-d H:i:s')));

        echo json_encode(array('msg' => $msg, 'sts' => $sts));
    }

    public function bak_time_eval()
    {
        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->load->library('sap');
        $filter = $this->input->post_get(null, TRUE);

        if (isset($filter['lokasi']))
            $lokasi = $filter['lokasi'];
        else
            $lokasi = "ho";

        if (isset($filter['nik']))
            $nik = $filter['nik'];
        else
            $nik = null;

        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $gen = $this->generate;
        $koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);
        $data_koneksi = $koneksi['ESS'];

        $constr = array(
            "logindata" => array(
                "ASHOST" => $data_koneksi['ASHOST'],
                "SYSNR" => $data_koneksi['SYSNR'],
                "CLIENT" => $data_koneksi['CLIENT'],
                "USER" => $data_koneksi['USER'],
                "PASSWD" => $data_koneksi['PASSWD']
            ),
            "show_errors" => $data_koneksi['DEBUG'],
            "debug" => $data_koneksi['DEBUG']
        );

        $sap = new saprfc($constr);

        $ho = 'n';
        if ($lokasi == 'ho')
            $ho = 'y';

        $this->general->log_schedule_master('ess/scheduler/bak_time_eval', array(
            'sesi' => date('H:i'),
            'source' => 'SAP HRIS',
            'terminal' => '-',
            'destination' => 'Portal',
            'keterangan' => 'SYNC data Time Eval karyawan dari HRIS ke PORTAL'
        ));
        
        $this->general->log_schedule_running('ess/scheduler/bak_time_eval', array('rfc' => 'Z_GET_EMPLOYEE_TIME_EVENT'));

        $this->general->connectDbPortal();

        $data_check = $this->dbak->get_bak(array(
            'id_bak_status' => ESS_BAK_STATUS_DISETUJUI,
            'ho' => $ho,
            'nik' => $nik,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ));

        $this->dgeneral->begin_transaction();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        foreach ($data_check as $check) {
            $nik = $check->nik;
            $tanggal = date('Ymd', strtotime($check->tanggal_absen));
            $result = $sap->callFunction(
                // "Z_GET_EMPLOYEE_TIME_EVAL",
                "Z_GET_EMPLOYEE_TIME_EVENT",
                array(
                    array("IMPORT", "I_HO", $ho == 'y' ? 'X' : null),
                    array("IMPORT", "I_BEGDA", $tanggal),
                    array("IMPORT", "I_ENDDA", $tanggal),
                    array("IMPORT", "I_PERNR", $nik),
                    array("TABLE", "T_DATA", array())
                )
            );

            if ($sap->getStatus() == SAPRFC_OK) {
                if (isset($result['T_DATA']) && count($result['T_DATA']) > 0) {
                    foreach ($result['T_DATA'] as $d) {
                        $tipe = (empty($d['TIPE'])) ? '-' : $d['TIPE'];
                        $absen_masuk = (empty($d['BEGTM'])) ?
                            '-' :
                            str_replace('::', '', $gen->format_nilai('SAP2SQLTIME', $d['BEGTM']));
                        $absen_keluar = (empty($d['ENDTM'])) ?
                            '-' :
                            str_replace('::', '', $gen->format_nilai('SAP2SQLTIME', $d['ENDTM']));

                        $check_cuti = $this->dbak->get_check_cuti(array(
                            'id_cuti_status' => array(
                                ESS_CUTI_STATUS_MENUNGGU,
                                ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                                ESS_CUTI_STATUS_DISETUJUI_HR
                            ),
                            'tanggal_awal' => $d['DATUM'],
                            'tanggal_akhir' => $d['DATUM'],
                            'nik' => $d['PERNR'],
                            'single_row' => true
                        ));

                        if (isset($check_cuti))
                            $tipe = $check_cuti->kode;

                        $check_bak = $this->dbak->get_bak(array(
                            'nik' => $d['PERNR'],
                            'tanggal_absen' => $d['DATUM'],
                            'id_bak_status' => array(
                                ESS_BAK_STATUS_DISETUJUI,
                                ESS_BAK_STATUS_DISETUJUI_OLEH_HR
                            ),
                            'single_row' => true
                        ));

                        if (isset($check_bak)) {
                            $data_bak = array(
                                'tipe' => $tipe,
                                'login_migrasi' => 0,
                                'tanggal_migrasi' => date('Y-m-d'),
                            );
                            if (!empty($d['BEGTM']) and !empty($d['ENDTM']) and $check_bak->id_bak_status != 0)
                                $data_bak['id_bak_status'] = ESS_BAK_STATUS_COMPLETE;

                            $this->dgeneral->update(
                                'tbl_bak',
                                $data_bak,
                                array(
                                    array(
                                        'kolom' => 'id_bak',
                                        'value' => $check_bak->id_bak
                                    )
                                )
                            );
                        }
                    }
                }
            } else {
                $msg = "Koneksi SAP error";
                $sts = "NotOK";
            }
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Sinkronisasi data BAK dari SAP berhasil";
            $sts = "OK";
        }
        $this->general->closeDb();
    
        $this->general->log_schedule_running('ess/scheduler/bak_time_eval', array('end_time' => date('Y-m-d H:i:s')));

        $sap->logoff();

        $return = array(
            'sts' => $sts, 
            'msg' => $msg, 
            'result' => compact('result')//, 'check_bak', 'data_bak', 'data_check')
        );

        echo json_encode($return);
    }

    public function rekap_bak_pengajuan_email()
    {
        $today = date_create(date('Y-m-d'));
        $tglAkhir = date_create($today->format('Y-m-20'));
        $tglAwal = date_create($today->format('Y-m-16'));
        $senin = date_create('monday this week');
        $nik = $this->input->get('nik');

        //        if ($today == $senin or ($today >= $tglAwal and $today <= $tglAkhir)) {
        if ($today == $senin) {
            //        if (true) {
            $this->general->log_schedule_master('ess/scheduler/rekap_bak_pengajuan_email', array(
                'sesi' => date('H:i'),
                'source' => 'Portal',
                'terminal' => '-',
                'destination' => 'Email',
                'keterangan' => 'Schedule Email Rekap pengajuan BAK'
            ));

            $this->general->log_schedule_running('ess/scheduler/rekap_bak_pengajuan_email');

            $this->general->connectDbPortal();

            $searchNik = "";
            if (isset($nik))
                $searchNik = "and tbl_bak.nik= '$nik'";

            $query = $this->db->query('select tbl_bak.atasan from tbl_bak
            where tbl_bak.id_bak_status = ' . ESS_BAK_STATUS_MENUNGGU . '
            and atasan is not null and tbl_bak.na = \'n\' and tbl_bak.del = \'n\' 
            and tanggal_absen >= \'2019-01-01\'
            ' . $searchNik . '
            group by tbl_bak.atasan');

            $list = $query->result();

            $atasanArray = array();

            foreach ($list as $d) {
                $exData = explode('.', $d->atasan);
                $atasanArray = array_merge($atasanArray, $exData);
            }

            $atasanArray = array_filter(array_unique($atasanArray), 'strlen');

            foreach ($atasanArray as $atasan) {

                $karyawan = $this->dessgeneral->get_karyawan($atasan);

                //                    $email_tujuan = DB_PORTAL == "portal" ? $atasan['list_atasan_email'] : json_decode(ESS_EMAIL_TESTER);

                if (
                    isset($karyawan->email) && !empty($karyawan->email)
                    && !in_array(intval($karyawan->nik), $this->nik_exclude)
                ) {
                    $emailOri = $karyawan->email;
                    if (ESS_EMAIL_SCHEDULER_DEBUG_MODE)
                        $email_tujuan = json_decode(ESS_EMAIL_TESTER);
                    else
                        $email_tujuan = $karyawan->email;

                    $list_menunggu = $this->dbak->get_bak(
                        array(
                            'tanggal_awal' => '2019-01-01',
                            'atasan' => $atasan,
                            'id_bak_status' => ESS_BAK_STATUS_MENUNGGU
                        )
                    );
                    $this->less->send_email(
                        array(
                            'judul' => "Rekapan Konfirmasi Pengajuan Berita Acara Kehadiran",
                            'email_pengirim' => "KiranaKu",
                            'email_tujuan' => $email_tujuan,
                            'view' => 'emails/rekap_pengajuan_bak',
                            'data' => array(
                                'list_menunggu' => $list_menunggu,
                                'emailOri' => $emailOri
                            )
                        )
                    );
                    //                    die();
                }
            }

            $this->general->log_schedule_running('ess/scheduler/rekap_bak_pengajuan_email', array('end_time' => date('Y-m-d H:i:s')));
        }
    }

    public function rekap_bak_tidak_lengkap_email()
    {
        $today = date_create(date('Y-m-d'));
        $tglAkhir = date_create($today->format('Y-m-20'));
        $tglAwal = date_create($today->format('Y-m-16'));
        $senin = date_create('monday this week');
        $nik = $this->input->get('nik');

        //        if ($today == $senin or ($today >= $tglAwal and $today <= $tglAkhir)) {
        if ($today == $senin) {
            //        if (true) {
            $this->general->log_schedule_master('ess/scheduler/rekap_bak_tidak_lengkap_email', array(
                'sesi' => date('H:i'),
                'source' => 'Portal',
                'terminal' => '-',
                'destination' => 'Email',
                'keterangan' => 'Schedule Email Rekap BAK tidak lengkap'
            ));

            $this->general->log_schedule_running('ess/scheduler/rekap_bak_tidak_lengkap_email');

            $this->general->connectDbPortal();

            $searchNik = "";
            if (isset($nik))
                $searchNik = "and tbl_bak.nik= '$nik'";

            $query = $this->db->query("select distinct tbl_bak.nik
            from tbl_bak left join tbl_cuti
            on tbl_bak.nik = tbl_cuti.nik and tbl_cuti.na = 'n' and tbl_cuti.del = 'n'
            AND tanggal_absen BETWEEN tanggal_awal AND tanggal_akhir
            where             
            (
                (
                absen_masuk = '-' 
                AND absen_keluar = '-'
                AND tipe IN ('-','0610')
                )
            OR
                (
                (absen_masuk <> '-' AND absen_keluar = '-')
                OR (absen_masuk = '-' AND absen_keluar <> '-')
                )
            )	
            and tanggal_absen >= ?
            and tbl_cuti.id_cuti is null
            $searchNik          
            ", array(date('Y-m-d', strtotime('first day of january this year'))));

            $list = $query->result();
            //            var_dump($list);die();
            foreach ($list as $d) {
                $karyawan = $this->dessgeneral->get_karyawan($d->nik);

                if (
                    isset($karyawan->email) && !empty($karyawan->email)
                    && !in_array(intval($d->nik), $this->nik_exclude)
                    && (
                        ($karyawan->ho == 'y') or ($karyawan->ho == 'n' && intval($karyawan->id_level) <= 9102))
                ) {

                    $user = $this->dessgeneral->get_user(null, null, null, $d->nik);

                    $list_bak_temp = $this->dbak->get_bak(
                        array(
                            'nik' => $user->nik,
                            'tanggal_awal' => date('Y-m-d', strtotime('first day of january this year')),
                            'tanggal_akhir' => date('Y-m-d')
                        )
                    );

                    $list_tidak_lengkap = $this->less->proses_list_bak(
                        array(
                            'tanggal_awal' => date('Y-m-d', strtotime('first day of january this year')),
                            'tanggal_akhir' => date('Y-m-d'),
                            'list_bak' => $list_bak_temp,
                            'user' => $user,
                            'lengkap' => false
                        )
                    );

                    $d->n_bak = count($list_tidak_lengkap);

                    if (count($list_tidak_lengkap) > 0) {
                        $emailOri = $karyawan->email;
                        if (ESS_EMAIL_SCHEDULER_DEBUG_MODE)
                            $email_tujuan = json_decode(ESS_EMAIL_TESTER);
                        else
                            $email_tujuan = $karyawan->email;

                        $status = $this->less->send_email(
                            array(
                                'judul' => "Rekapan Berita Acara Kehadiran",
                                'email_pengirim' => "KiranaKu",
                                'email_tujuan' => $email_tujuan,
                                'view' => 'emails/rekap_bak_tidak_lengkap',
                                'data' => array(
                                    'list_tidak_lengkap' => $list_tidak_lengkap,
                                    'emailOri' => $emailOri
                                )
                            )
                        );
                        $d->terkirim = $status['sts'] == "OK" ? true : false;
                    }
                }
            }
            echo date('d.m.Y') . "\r\n " . json_encode($list) . "\r\n";
            $this->general->log_schedule_running('ess/scheduler/rekap_bak_tidak_lengkap_email', array('end_time' => date('Y-m-d H:i:s')));
        } else
            echo date('d.m.Y') . "\r\n Tidak ada schedule\r\n";
    }

    public function rekap_cutiijin_pengajuan_email()
    {
        $today = date_create(date('Y-m-d'));
        $tglAkhir = date_create($today->format('Y-m-20'));
        $tglAwal = date_create($today->format('Y-m-16'));
        $senin = date_create('monday this week');
        $nik = $this->input->get('nik');

        //        if ($today == $senin or ($today >= $tglAwal and $today <= $tglAkhir)) {
        if ($today == $senin) {
            //        if (true) {
            $this->general->log_schedule_master('ess/scheduler/rekap_cutiijin_pengajuan_email', array(
                'sesi' => date('H:i'),
                'source' => 'Portal',
                'terminal' => '-',
                'destination' => 'Email',
                'keterangan' => 'Schedule Email Rekap pengajuan Cuti/Ijin'
            ));

            $this->general->log_schedule_running('ess/scheduler/rekap_cutiijin_pengajuan_email');

            $this->general->connectDbPortal();

            $searchNik = "";
            if (isset($nik))
                $searchNik = "and tbl_cuti.nik= '$nik'";

            $query = $this->db->query('select tbl_cuti.atasan from tbl_cuti
            where tbl_cuti.id_cuti_status = ' . ESS_CUTI_STATUS_MENUNGGU . '
            and atasan is not null and tbl_cuti.na = \'n\' and tbl_cuti.del = \'n\'
            and tanggal_awal >= \'2019-01-01\'
            ' . $searchNik . '
            group by tbl_cuti.atasan');

            $list = $query->result();

            $atasanArray = array();

            foreach ($list as $d) {
                $exData = explode('.', $d->atasan);
                $atasanArray = array_merge($atasanArray, $exData);
            }

            $atasanArray = array_filter(array_unique($atasanArray), 'strlen');

            foreach ($atasanArray as $atasan) {

                $karyawan = $this->dessgeneral->get_karyawan($atasan);

                //                    $email_tujuan = DB_PORTAL == "portal" ? $atasan['list_atasan_email'] : json_decode(ESS_EMAIL_TESTER);

                if (
                    isset($karyawan->email) && !empty($karyawan->email)
                    && !in_array(intval($karyawan->nik), $this->nik_exclude)
                ) {
                    $emailOri = $karyawan->email;
                    if (ESS_EMAIL_SCHEDULER_DEBUG_MODE)
                        $email_tujuan = json_decode(ESS_EMAIL_TESTER);
                    else
                        $email_tujuan = $karyawan->email;

                    $list_menunggu = $this->dcutiijin->get_cuti(
                        array(
                            'atasan' => $atasan,
                            'id_tipe_status' => ESS_CUTI_STATUS_MENUNGGU,
                            'tanggal_awal' => array('2019-01-01', date('Y-m-d')),
                            'order_by' => 'tbl_cuti.nik desc,tbl_cuti.form asc,tbl_cuti.tanggal_awal DESC'
                        )
                    );

                    foreach ($list_menunggu as $i => $d) {
                        if ($d->form == 'Cuti') {
                            $sisa = $this->less->get_cuti_sisa(array(null, $d->tanggal_akhir), $d->nik);
                            $d->sisa = $sisa['sisa'];
                        } else
                            $d->sisa = '-';
                        $list_menunggu[$i] = $d;
                    }

                    //                echo $this->load->view('emails/rekap_pengajuan_cutiijin', array('data' => compact('list_menunggu')));

                    $this->less->send_email(
                        array(
                            'judul' => "Rekapan Konfirmasi Pengajuan Cuti/Ijin",
                            'email_pengirim' => "KiranaKu",
                            'email_tujuan' => $email_tujuan,
                            'view' => 'emails/rekap_pengajuan_cutiijin',
                            'data' => array(
                                'list_menunggu' => $list_menunggu,
                                'emailOri' => $emailOri
                            )
                        )
                    );
                    //                    die();
                }
            }

            $this->general->log_schedule_running('ess/scheduler/rekap_cutiijin_pengajuan_email', array('end_time' => date('Y-m-d H:i:s')));
        }
    }

    public function test_email($id = null)
    {
        //        $this->general->connectDbPortal();
        //        $cuti_ijin = $this->dcutiijin->get_cuti(
        //            array(
        //                'id' => 494,
        //                'single_row' => true,
        //                'id_tipe_status' => array(1, 2, 3, 4)
        //            )
        //        );
        //
        //        $karyawan = $this->dessgeneral->get_karyawan($cuti_ijin->id_karyawan);
        //
        //        $atasan = $this->less->get_atasan(array(
        //            'nik' => $karyawan->nik
        //        ));
        //
        //        $sisacuti = $this->less->get_cuti_sisa(null, $karyawan->nik);

        //        $this->less->send_email(
        //            array(
        //                'judul' => "Rekapan Konfirmasi Pengajuan Cuti/Ijin",
        //                'email_pengirim' => "KiranaKu",
        //                'email_tujuan' => json_decode(ESS_EMAIL_TESTER),
        //                'view' => 'emails/pengajuan_cuti_ijin_new',
        //                'data' => null
        //            )
        //        );

        $this->load->view(
            'emails/pengajuan_cuti_ijin_new'
            //            ,array(
            //                'data' => $cuti_ijin,
            //                'sisa_cuti' => $sisacuti
            //            )
        );
    }

    function date_compare_masuk($a, $b)
    {
        $t1 = strtotime($a['absen_masuk']);
        $t2 = strtotime($b['absen_masuk']);
        return $t1 - $t2;
    }

    function date_compare_keluar($a, $b)
    {
        $t1 = strtotime($a['absen_keluar']);
        $t2 = strtotime($b['absen_keluar']);
        return $t1 - $t2;
    }
}
