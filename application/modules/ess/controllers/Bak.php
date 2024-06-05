<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS BAK - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Bak extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        //        $this->general->check_access();
        $this->data['module'] = "Employee Self Service";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->library('form_validation');
        $this->load->library('less');
        $this->load->model('dessgeneral');
        $this->load->model('dbak');
    }

    public function pengajuan()
    {
        $this->general->check_access();
        $this->data['title'] = "Form Berita Acara Kehadiran";

        $this->general->connectDbPortal();

        $getNik = $this->input->get('nik');

        $user = isset($getNik) ? $this->dessgeneral->get_user(null, null, null, $getNik) : $this->data['user'];

        // filter start
        $lengkap = $this->input->post_get('lengkap');
        if (!isset($lengkap))
            $lengkap = 1;

        //        var_dump($lengkap);die();

        $filter = $this->input->post();

        /** Filter data yang lengkap atau tidak **/
        $filter['lengkap'] = $lengkap;

        if ($lengkap == 0) {
            $minDate = $this->dbak->get_bak_min_date(
                array(
                    'nik' => $user->nik
                )
            );

            if (isset($minDate) and isset($minDate->tanggal_absen))
                $tanggal_awal = date('Y-m-d', strtotime($minDate->tanggal_absen));
            else
                $tanggal_awal = date('Y-m-d', strtotime('-1 months'));
        } else
            $tanggal_awal = date('Y-m-d', strtotime('-1 months'));

        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $list_bak_temp = $this->dbak->get_bak(
            array(
                'nik' => $user->nik,
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
            )
        );

        $list_bak = $this->less->proses_list_bak(
            array(
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'list_bak' => $list_bak_temp,
                'user' => $user,
                'lengkap' => $lengkap
            )
        );

        $this->data['filter'] = $filter;
        $this->data['list_bak'] = $list_bak;
        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;
        $this->data['bak_alasan'] = $this->dbak->get_bak_alasan();

        $atasan = $this->less->get_atasan();

        $this->data['atasan'] = $atasan;

        $this->load->view('bak/pengajuan', $this->data);
    }

    public function persetujuan()
    {
        $this->general->check_access();
        $this->data['title'] = "Persetujuan Berita Acara Kehadiran";

        // filter start
        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('first day of January ' . date('Y')));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $user = $this->data['user'];

        $this->general->connectDbPortal();

        $list_menunggu = $this->dbak->get_bak(
            array(
                'atasan' => $user->nik,
                'id_bak_status' => ESS_BAK_STATUS_MENUNGGU
            )
        );

        $list_history = $this->dbak->get_bak(
            array(
                'atasan' => $user->nik,
                'id_bak_status' => array(
                    ESS_BAK_STATUS_DISETUJUI,
                    ESS_BAK_STATUS_DITOLAK,
                    ESS_BAK_STATUS_COMPLETE,
                    ESS_BAK_STATUS_DIBATALKAN
                ),
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir
            )
        );

        $this->data['tab_persetujuan_menunggu'] = $this->load->view('bak/_tab_persetujuan_menunggu', array(
            'list_menunggu' => $list_menunggu,
            'searchNik' => $this->input->get('nik')
        ), true);
        $this->data['tab_persetujuan_history'] = $this->load->view('bak/_tab_persetujuan_history', array(
            'list_history' => $list_history,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'searchNik' => $this->input->get('nik'),
            'status' => $this->input->get('status')
        ), true);

        $statusFilter = $this->input->get('status');
        $nikFilter = $this->input->get('nik');
        if (isset($statusFilter) and $statusFilter == 0) {
            $this->dgeneral->begin_transaction();
            if (isset($nikFilter)) {
                $this->db->where_in('detail', array('MISS_CI', 'MISS_CO', 'MISS_CICO'));
                $this->dgeneral->update(
                    'tbl_bak',
                    array(
                        'read_notif' => 1
                    ),
                    array(
                        array(
                            'kolom' => 'read_notif',
                            'value' => 0
                        ),
                        array(
                            'kolom' => 'nik',
                            'value' => $this->input->get('nik')
                        )
                    )
                );
            } else {
                $nik = base64_decode($this->session->userdata('-nik-'));
                $this->db->where_in('detail', array('MISS_CI', 'MISS_CO', 'MISS_CICO'));
                $this->db->like('atasan', $nik . '.', 'both');
                $this->dgeneral->update(
                    'tbl_bak',
                    array(
                        'read_notif' => 1
                    ),
                    array(
                        array(
                            'kolom' => 'read_notif',
                            'value' => 0
                        )
                    )
                );
            }

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
            } else {
                $this->dgeneral->commit_transaction();
            }
        }

        $this->load->view('bak/persetujuan', $this->data);
    }

    public function persetujuan_hr()
    {
        $this->general->check_access();
        $this->data['title'] = "Persetujuan Berita Acara Kehadiran HROGA";

        // filter start
        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('first day of January ' . date('Y')));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date('Y-m-d', strtotime($filter['tanggal_akhir']));

        $user = $this->data['user'];

        $this->general->connectDbPortal();

        $list_menunggu = $this->dbak->get_bak(
            array(
                'id_bak_status' => ESS_BAK_STATUS_MENUNGGU
            )
        );

        $list_history = $this->dbak->get_bak(
            array(
                'id_bak_status' => array(
                    ESS_BAK_STATUS_DISETUJUI_OLEH_HR
                ),
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir
            )
        );

        $this->data['tab_persetujuan_menunggu'] = $this->load->view('bak/_tab_persetujuan_hr', array(
            'list_menunggu' => $list_menunggu
        ), true);
        $this->data['tab_persetujuan_history'] = $this->load->view('bak/_tab_persetujuan_history', array(
            'list_history' => $list_history,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'searchNik' => $this->input->get('nik')
        ), true);

        $this->load->view('bak/persetujuan_hr', $this->data);
    }

    public function persetujuan_hr_final()
    {
        $this->general->check_access();
        $this->data['title'] = "Persetujuan Berita Acara Kehadiran HROGA Final";

        // filter start
        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('first day of January ' . date('Y')));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            //            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            //            $tanggal_akhir = date('Y-m-d', strtotime($filter['tanggal_akhir']));
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $user = $this->data['user'];

        $this->general->connectDbPortal();

        $list_menunggu = $this->dbak->get_bak(
            array(
                'id_bak_status' => ESS_BAK_STATUS_DISETUJUI_OLEH_HR
            )
        );

        $this->data['tab_persetujuan_menunggu'] = $this->load->view('bak/_tab_persetujuan_hr_final', array(
            'list_menunggu' => $list_menunggu
        ), true);

        $this->load->view('bak/persetujuan_hr_final', $this->data);
    }

    public function massal()
    {
        $this->general->check_access();
        $this->data['title'] = "Berita Acara Kehadiran Massal";

        // filter start
        $filter = $this->input->post();

        $this->data['karyawan'] = $this->dbak->get_karyawan();
        $this->data['divisi'] = $this->dbak->get_divisi();
        $this->data['departemen'] = $this->dbak->get_department();

        $this->data['list_bak_massal'] = $this->dbak->get_bak_massal();

        $this->load->view('bak/bak_masal', $this->data);
    }

    //    public function dashboard()
    //    {
    //        $this->data['title'] = "Berita Acara Kehadiran Massal";
    //
    //        // filter start
    //        $filter = $this->input->post();
    //
    //        $tanggal_awal = date_create(date('Y-m-d', strtotime('-1 month')));
    //        $tanggal_akhir = date_create();
    //
    //        if (isset($filter['tanggal_awal']))
    //            $tanggal_awal = date_create($filter['tanggal_awal']);
    //
    //        if (isset($filter['tanggal_akhir']))
    //            $tanggal_akhir = date_create($filter['tanggal_akhir']);
    //
    //        $this->data['list_data'] = $this->dbak->get_dashboard(array(
    //            'nik' => $this->data['user']->nik,
    //            'tanggal_awal' => $tanggal_awal->format('Ymd'),
    //            'tanggal_akhir' => $tanggal_akhir->format('Ymd')
    //
    //        ));
    //
    //        $this->data['tanggal_awal'] = $tanggal_awal->format('Y-m-d');
    //        $this->data['tanggal_akhir'] = $tanggal_akhir->format('Y-m-d');
    //
    //        $this->load->view('bak/dashboard', $this->data);
    //    }

    public function sap($lokasi = "ho", $level = 'kasi')
    {
        $this->general->check_access();
        $managerUp = $level == 'mg';
        if ($lokasi == 'pabrik') {
            if ($managerUp)
                $this->data['title'] = "Proses SAP Berita Acara Kehadiran Pabrik Mg Up";
            else
                $this->data['title'] = "Proses SAP Berita Acara Kehadiran Pabrik Kasie Down";
        } else
            $this->data['title'] = "Proses SAP Berita Acara Kehadiran HO";

        // filter start
        $filter = $this->input->post();
        $filter['tahun'] = date('Y');
        //        $filter['tahun'] = 2017;
        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            //            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            //            $tanggal_akhir = date('Y-m-d', strtotime($filter['tanggal_akhir']));
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        if (isset($filter['id_bak_status'])) {
            if ($filter['id_bak_status'] == 'Semua')
                $id_bak_status = array(
                    //                    ESS_BAK_STATUS_DEFAULT,
                    //                    ESS_BAK_STATUS_TDK_ABSENT,
                    //                    ESS_BAK_STATUS_MENUNGGU,
                    ESS_BAK_STATUS_DISETUJUI,
                    //                    ESS_BAK_STATUS_COMPLETE,
                    //                    ESS_BAK_STATUS_DITOLAK,
                    ESS_BAK_STATUS_DISETUJUI_OLEH_HR
                );
            else
                $id_bak_status = $filter['id_bak_status'];
        } else {
            $id_bak_status = array(
                ESS_BAK_STATUS_DISETUJUI,
                ESS_BAK_STATUS_DISETUJUI_OLEH_HR
            );
            $filter['id_bak_status'] = 'Semua';
        }

        if ($lokasi == 'ho')
            $ho = 'y';
        else
            $ho = 'n';

        $list_bak = $this->dbak->get_bak(array(
            'sap' => true,
            'id_bak_status' => $id_bak_status,
            'ho' => $ho,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'manager' => ($lokasi != 'ho' ? $managerUp : null)
        ));

        //        var_dump($list_bak);die();

        $list_history = $this->dbak->get_bak(array(
            'sap' => true,
            'id_bak_status' => array(
                ESS_BAK_STATUS_COMPLETE,
                ESS_BAK_STATUS_DIBATALKAN
            ),
            'ho' => $ho,
            'tanggal_awal' => date('Y-m-d', strtotime('first day of January ' . date('Y'))),
            'tanggal_akhir' => date('Y-m-d'),
            'manager' => ($lokasi != 'ho' ? $managerUp : null)
        ));

        $this->data['tab_sap_bak'] = $this->load->view('bak/_tab_sap_bak', array(
            'list_bak' => $list_bak,
            'lokasi' => $lokasi,
            'manager' => $managerUp,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'statuses' => $this->dbak->get_bak_status(array(
                'id' => array(
                    ESS_BAK_STATUS_DISETUJUI,
                    ESS_BAK_STATUS_DISETUJUI_OLEH_HR
                )
            )),
            'filter' => $filter
        ), true);

        $this->data['tab_sap_history'] = $this->load->view('bak/_tab_sap_history', array(
            'list_history' => $list_history
        ), true);

        $this->load->view('bak/sap', $this->data);
    }

    public function rfc($action = 'sync')
    {

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->load->library('sap');

        $user = $this->data['user'];

        $filter = $this->input->post_get(null, TRUE);

        if (isset($filter['lokasi']))
            $lokasi = $filter['lokasi'];
        else
            $lokasi = "ho";

            if (isset($filter['nik']))
                $nik = $filter['nik'];
            else
                $nik = null;

        // $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_awal = date('Y-m-d', strtotime('-3 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            //            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            //            $tanggal_akhir = date('Y-m-d', strtotime($filter['tanggal_akhir']));
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

        switch ($action) {
            case "sync":
                $ho = 'n';
                if ($lokasi == 'ho')
                    $ho = 'y';

                //                if (isset($filter['tanggal']) && !empty($filter['tanggal'])) {
                //                    $tanggal_awal = DateTime::createFromFormat('d.m.Y', $filter['tanggal'])->format('Ymd');
                //                    $tanggal_akhir = DateTime::createFromFormat('d.m.Y', $filter['tanggal'])->format('Ymd');
                //                }

                $data_check = $this->dbak->get_bak(array(
                    'id_bak_status' => ESS_BAK_STATUS_DISETUJUI,
                    'ho' => $ho,
                    'nik' => $nik,
                    'tanggal_awal' => $tanggal_awal,
                    'tanggal_akhir' => $tanggal_akhir
                ));

                $this->general->connectDbPortal();
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
                                        'login_migrasi' => $user->id_user,
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
                                //                                else {
                                //                                    $data_bak = array(
                                //                                        'id_bak_status' => 0,
                                //                                        'nik' => $d['PERNR'],
                                //                                        'tipe' => $tipe,
                                //                                        'tanggal_absen' => $this->generate->format_nilai("SAP2SQLDATE", $d['DATUM']),
                                //                                        'absen_masuk' => $absen_masuk,
                                //                                        'absen_keluar' => $absen_keluar,
                                //                                        'id_bak_alasan' => '0',
                                //                                        'login_migrasi' => $user->id_user,
                                //                                        'tanggal_migrasi' => date('Y-m-d'),
                                //                                        'na' => 'n',
                                //                                        'del' => 'n',
                                //                                    );
                                //
                                //                                    $this->dgeneral->insert('tbl_bak', $data_bak);
                                //                                }
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

                $sap->logoff();
                if (!isset($check_bak)) {
                    $check_bak = array();
                    $data_bak = array();
                }
                $return = array(
                    'sts' => $sts,
                    'msg' => $msg,
                    'result' => compact('result', 'check_bak', 'data_bak', 'data_check')
                );
                break;

            case "time_event":
                $ho = 'n';
                if ($lokasi == 'ho')
                    $ho = 'y';
                $nik = $filter['nik'];

                $awal = date_create($tanggal_awal)->format('Ymd');
                $akhir = date_create($tanggal_akhir)
                    ->modify('-1 day')
                    ->format('Ymd');

                $today = date('Y-m-d');
                if ($tanggal_akhir != $today)
                    $akhir = date_create($tanggal_akhir)
                        ->format('Ymd');

                $result = $sap->callFunction('Z_GET_EMPLOYEE_TIME_EVENT', array(
                    array("IMPORT", "I_HO", $ho == 'y' ? 'X' : null),
                    array("IMPORT", "I_BEGDA", $awal),
                    array("IMPORT", "I_ENDDA", $akhir),
                    array("IMPORT", "I_PERNR", $nik),
                    array("TABLE", "T_DATA", array()),
                    //                    array("TABLE", "T_SCHEDULE", array()),
                    //                    array("TABLE", "T_HOLIDAY", array()),
                ));

                if ($sap->getStatus() == SAPRFC_OK) {

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();
                    if (isset($result['T_DATA'])) {

                        //                        $absensi = array();
                        //
                        //                        foreach ($result['T_DATA'] as $bak) {
                        //                            $tanggal = empty($bak['DATUM']) ? null :
                        //                                DateTime::createFromFormat('Ymd', $bak['DATUM']);
                        //                            $absen_masuk = empty($bak['BEGTM']) ? null :
                        //                                DateTime::createFromFormat('YmdHis', $bak['DATUM'] . $bak['BEGTM']);
                        //                            $absen_keluar = empty($bak['ENDTM']) ? null :
                        //                                DateTime::createFromFormat('YmdHis', $bak['DATUM'] . $bak['ENDTM']);
                        //                            $ws_masuk = empty($bak['SOBEG']) ? null :
                        //                                DateTime::createFromFormat('YmdHis', $bak['DATUM'] . $bak['SOBEG']);
                        //                            $ws_keluar = empty($bak['SOEND']) ? null :
                        //                                DateTime::createFromFormat('YmdHis', $bak['DATUM'] . $bak['SOEND']);
                        //
                        //                            $absensi[$bak['PERNR']][] = array(
                        //                                'nik' => $bak['PERNR'],
                        //                                'tanggal' => $tanggal,
                        //                                'absen_masuk' => $absen_masuk,
                        //                                'absen_keluar' => $absen_keluar,
                        //                                'ws_masuk' => $ws_masuk,
                        //                                'ws_keluar' => $ws_keluar,
                        //                                'tipe' => $bak['TIPE'],
                        //                                'jenis' => $bak['LINE']
                        //                            );
                        //                        }
                        //
                        //                        foreach ($absensi as $nik => $absens) {
                        //
                        //                            foreach ($absens as $i => $absen) {
                        //                                $normal = false;
                        //                                if (
                        //                                    isset($absen['absen_masuk']) && isset($absen['absen_keluar']) &&
                        //                                    $absen['absen_masuk'] < $absen['absen_keluar'] &&
                        //                                    $absen['absen_keluar'] > $absen['ws_masuk']
                        //                                )
                        //                                    $normal = true;
                        //
                        //                                if (isset($absen['absen_masuk']) && !$normal) {
                        //                                    $absen['absen_keluar'] = isset($absens[$i + 1]) ? $absens[$i + 1]['absen_keluar'] : $absen['absen_keluar'];
                        //                                }
                        //
                        //                                $absens[$i] = $absen;
                        //                            }
                        //
                        //                            $absensi[$nik] = $absens;
                        //
                        ////                            $absensiNormal = $absens;
                        ////                            $absensiLibur = array();
                        ////                            $absensiAbsen = array();
                        ////
                        ////                            $normalTemp = array();
                        ////                            $noNormalTemp = array();
                        ////
                        ////                            foreach ($absensiNormal as $normal) {
                        ////                                if (!isset($normal['ws_masuk'])) {
                        ////                                    $absensiLibur[] = $normal;
                        ////                                } else if (
                        ////                                    isset($normal['absen_masuk']) && isset($normal['absen_keluar']) &&
                        ////                                    $normal['absen_masuk'] < $normal['absen_keluar'] &&
                        ////                                    $normal['absen_keluar'] > $normal['ws_masuk']
                        ////                                ) {
                        ////                                    $normalTemp[] = $normal;
                        ////                                } else {
                        ////                                    $noNormalTemp[] = $normal;
                        ////                                }
                        ////                            }
                        ////
                        ////                            foreach ($noNormalTemp as $i => $noNormal) {
                        ////                                if (isset($noNormal['absen_masuk'])) {
                        ////                                    $absenNextDay = new DateTime($noNormal['absen_masuk']->format('Y-m-d'));
                        ////                                    $absenNextDay->modify('+1 day');
                        ////
                        ////                                    $newAbsen = null;
                        ////
                        //////                                    $arrayCheck = array($noNormalTemp, $absensiLibur, $absensiAbsen);
                        ////
                        //////                                    foreach ($arrayCheck as $array) {
                        ////                                    foreach ($absens as $d) {
                        ////                                        if (isset($d['absen_keluar'])) {
                        ////                                            if (
                        ////                                                $d['absen_keluar'] > $noNormal['absen_masuk'] &&
                        ////                                                $d['absen_keluar'] > $noNormal['ws_masuk']
                        ////                                            ) {
                        ////                                                if (
                        ////                                                    !isset($newAbsen) ||
                        ////                                                    $newAbsen > $d['absen_keluar']
                        ////                                                ) {
                        ////                                                    $newAbsen = $d['absen_keluar'];
                        ////                                                    break;
                        ////                                                }
                        ////                                            }
                        ////                                        }
                        ////                                    }
                        ////
                        ////                                    $noNormal['absen_keluar_new'] = $newAbsen;
                        ////
                        ////                                    $noNormalTemp[$i] = $noNormal;
                        ////                                }
                        ////                            }
                        ////
                        ////                            $absens['normal'] = $normalTemp;
                        ////                            $absens['no_normal'] = $noNormalTemp;
                        ////                            $absens['libur'] = $absensiLibur;
                        ////                            $absens['absen'] = $absensiAbsen;
                        ////
                        ////                            $absensi[$nik] = $absens;
                        //                        }
                        //                        foreach ($absensi as $nik => $absens) {
                        //                            foreach ($absens as $d) {
                        //                                $absenMasuk = isset($d['absen_masuk']) ? $d['absen_masuk']->format('Y-m-d H:i') : 'NaN';
                        ////                                $absenMasuk = isset($d['absen_masuk_new']) ? $d['absen_masuk_new']->format('Y-m-d H:i') : $absenMasuk;
                        //                                $absenKeluar = isset($d['absen_keluar']) ? $d['absen_keluar']->format('Y-m-d H:i') : 'NaN';
                        ////                                $absenKeluar = isset($d['absen_keluar_new']) ? $d['absen_keluar_new']->format('Y-m-d H:i') : $absenKeluar;
                        //                                echo $d['nik'] . ' ' . $d['tanggal']->format('Y-m-d')
                        //                                    . ' ' . $absenMasuk
                        //                                    . ' - ' . $absenKeluar
                        //                                    . ' - ' . $d['jenis']
                        //                                    . "\r\n";
                        //
                        //
                        //                            }
                        //                        }
                        //                        die();
                        //
                        //                        $allAbsensi = array();
                        //
                        //                        foreach ($absensi as $nik => $dataAbsensi) {
                        //
                        //                            foreach ($dataAbsensi['normal'] as $d) {
                        //                                if (isset($d['absen_masuk']) && isset($d['absen_keluar']))
                        //                                    $allAbsensi[] = array(
                        //                                        'nik' => $nik,
                        //                                        'tanggal' => $d['tanggal'],
                        //                                        'absen_masuk' => $d['absen_masuk'],
                        //                                        'absen_keluar' => $d['absen_keluar'],
                        //                                        'ws_masuk' => $d['ws_masuk'],
                        //                                        'ws_keluar' => $d['ws_keluar'],
                        //                                        'jenis' => $d['jenis'],
                        //                                        'tipe' => $d['tipe']
                        //                                    );
                        //                            }
                        //
                        //                            foreach ($dataAbsensi['no_normal'] as $d) {
                        //                                if (isset($d['absen_keluar_new']))
                        //                                    $allAbsensi[] = array(
                        //                                        'nik' => $nik,
                        //                                        'tanggal' => $d['tanggal'],
                        //                                        'absen_masuk' => $d['absen_masuk'],
                        //                                        'absen_keluar' => $d['absen_keluar_new'],
                        //                                        'ws_masuk' => $d['ws_masuk'],
                        //                                        'ws_keluar' => $d['ws_keluar'],
                        //                                        'jenis' => $d['jenis'],
                        //                                        'tipe' => $d['tipe']
                        //                                    );
                        //                                else if (isset($d['absen_masuk']) && isset($d['absen_keluar']))
                        //                                    $allAbsensi[] = array(
                        //                                        'nik' => $nik,
                        //                                        'tanggal' => $d['tanggal'],
                        //                                        'absen_masuk' => $d['absen_masuk'],
                        //                                        'absen_keluar' => $d['absen_keluar'],
                        //                                        'ws_masuk' => $d['ws_masuk'],
                        //                                        'ws_keluar' => $d['ws_keluar'],
                        //                                        'jenis' => $d['jenis'],
                        //                                        'tipe' => $d['tipe']
                        //                                    );
                        //                            }
                        //
                        //                            foreach ($dataAbsensi['libur'] as $d) {
                        //                                if (isset($d['tanggal']))
                        //                                    $allAbsensi[] = array(
                        //                                        'nik' => $nik,
                        //                                        'tanggal' => $d['tanggal'],
                        //                                        'absen_masuk' => null,
                        //                                        'absen_keluar' => null,
                        //                                        'ws_masuk' => null,
                        //                                        'ws_keluar' => null,
                        //                                        'jenis' => $d['jenis'],
                        //                                        'tipe' => $d['tipe']
                        //                                    );
                        //                            }
                        //
                        //                            foreach ($dataAbsensi['absen'] as $d) {
                        //                                if (isset($d['tanggal']))
                        //                                    $allAbsensi[] = array(
                        //                                        'nik' => $nik,
                        //                                        'tanggal' => $d['tanggal'],
                        //                                        'absen_masuk' => $d['absen_masuk'],
                        //                                        'absen_keluar' => $d['absen_keluar'],
                        //                                        'ws_masuk' => $d['ws_masuk'],
                        //                                        'ws_keluar' => $d['ws_keluar'],
                        //                                        'jenis' => $d['jenis'],
                        //                                        'tipe' => $d['tipe']
                        //                                    );
                        //                            }
                        //                        }
                        //
                        //                        foreach ($allAbsensi as $d) {
                        //                            $absenMasuk = isset($d['absen_masuk']) ? $d['absen_masuk']->format('Y-m-d H:i') : 'NaN';
                        //                            $absenKeluar = isset($d['absen_keluar']) ? $d['absen_keluar']->format('Y-m-d H:i') : 'NaN';
                        //                            echo $d['nik'] . ' ' . $d['tanggal']->format('Y-m-d')
                        //                                . ' ' . $absenMasuk
                        //                                . ' - ' . $absenKeluar
                        //                                . "\r\n";
                        //                        }
                        //                        die();

                        //                        foreach ($result['T_DATA'] as $bak) {
                        //                            $absen_masuk = empty($bak['BEGTM']) ? '-' :
                        //                                DateTime::createFromFormat('His', $bak['BEGTM'])->format('H:i:s');
                        //                            $absen_keluar = empty($bak['ENDTM']) ? '-' :
                        //                                DateTime::createFromFormat('His', $bak['ENDTM'])->format('H:i:s');
                        //
                        //                            $id_bak_status = ($absen_masuk != "-" and $absen_keluar != "-") ?
                        //                                ESS_BAK_STATUS_COMPLETE : ESS_BAK_STATUS_DEFAULT;
                        //
                        //                            $tipe = empty($bak['TIPE']) ? '-' : $bak['TIPE'];
                        //
                        //                            $check_cuti = $this->dbak->get_check_cuti(array(
                        //                                'id_cuti_status' => array(
                        //                                    ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                        //                                    ESS_CUTI_STATUS_DISETUJUI_HR
                        //                                ),
                        //                                'tanggal_awal' => $bak['DATUM'],
                        //                                'tanggal_akhir' => $bak['DATUM'],
                        //                                'nik' => $bak['PERNR'],
                        //                                'single_row' => true
                        //                            ));
                        //
                        //                            $tipe = empty($check_cuti->kode) ? $tipe : $check_cuti->kode;
                        //
                        //                            $check_bak = $this->dbak->get_bak(array(
                        //                                'nik' => $bak['PERNR'],
                        //                                'tanggal_absen' => $bak['DATUM'],
                        //                                'single_row' => true
                        //                            ));
                        //
                        //                            if (!isset($check_bak->atasan) && !isset($check_bak->atasan_email)) {
                        //                                if (isset($check_bak->id_bak)) {
                        //                                    $data_bak = array(
                        //                                        'nik' => $bak['PERNR'],
                        //                                        'tipe' => $tipe,
                        //                                        'tanggal_absen' => $this->generate->format_nilai("SAP2SQLDATE", $bak['DATUM']),
                        //                                        'absen_masuk' => $absen_masuk,
                        //                                        'absen_keluar' => $absen_keluar
                        //                                    );
                        //
                        //                                    $this->dgeneral->update('tbl_bak', $data_bak, array(
                        //                                        array(
                        //                                            'kolom' => 'id_bak',
                        //                                            'value' => $check_bak->id_bak
                        //                                        )
                        //                                    ));
                        //                                } else {
                        //
                        //                                    $data_bak = array(
                        //                                        'id_bak_status' => $id_bak_status,
                        //                                        'nik' => $bak['PERNR'],
                        //                                        'tipe' => $tipe,
                        //                                        'tanggal_absen' => $this->generate->format_nilai("SAP2SQLDATE", $bak['DATUM']),
                        //                                        'absen_masuk' => $absen_masuk,
                        //                                        'absen_keluar' => $absen_keluar,
                        //                                        'id_bak_alasan' => '0',
                        //                                        'login_migrasi' => $user->id_user,
                        //                                        'tanggal_migrasi' => date('Y-m-d'),
                        //                                        'na' => 'n',
                        //                                        'del' => 'n',
                        //                                    );
                        //
                        //                                    $this->dgeneral->insert('tbl_bak', $data_bak);
                        //                                }
                        //
                        //                            }
                        //                        }
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
                } else {
                    $msg = "Koneksi SAP error";
                    $sts = "NotOK";
                }

                $sap->logoff();

                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function excel($modul = 'sap', $param = 'ho', $level = 'kasi')
    {
        $managerUp = $level == 'mg';

        $this->load->library('PHPExcel');

        $gen = $this->generate;

        $filter = $this->input->get();

        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');
        $cico = "lengkap";

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        if (isset($filter['cico']))
            $cico = $filter['cico'];

        if (isset($filter['nik']))
            $nik = $filter['nik'];
        else
            $nik = "";

        if (isset($filter['id_bak_status'])) {
            if ($filter['id_bak_status'] == 'Semua')
                $id_bak_status = null;
            else
                $id_bak_status = $filter['id_bak_status'];
        } else {
            $id_bak_status = null;
            $filter['id_bak_status'] = 'Semua';
        }

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        if ($modul == 'sap') {
            switch ($param) {
                case "ho":
                    $title = "HO";

                    $list_proses = $this->dbak->get_bak(array(
                        'sap' => true,
                        'tanggal_awal' => $tanggal_awal,
                        'tanggal_akhir' => $tanggal_akhir,
                        'id_bak_status' => ESS_BAK_STATUS_DISETUJUI,
                        'ho' => 'y'
                    ));
                    break;
                case "pabrik":
                    if ($managerUp)
                        $title = "Pabrik Mg Up";
                    else
                        $title = "Pabrik Kasie Down";

                    $list_proses = $this->dbak->get_bak(array(
                        'sap' => true,
                        'tanggal_awal' => $tanggal_awal,
                        'tanggal_akhir' => $tanggal_akhir,
                        'id_bak_status' => ESS_BAK_STATUS_DISETUJUI,
                        'ho' => 'n',
                        'manager' => $managerUp
                    ));
                    break;
                default:
                    $title = "HO";
                    $list_proses = array();
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Kiranaku")
                ->setLastModifiedBy("Kiranaku")
                ->setTitle("Export BAK ($title) to SAP (" . date('d-m-Y') . ")")
                ->setSubject("Export BAK ($title) to SAP (" . date('d-m-Y') . ")")
                ->setDescription("Export BAK ($title) to SAP (" . date('d-m-Y') . ")")
                ->setCategory("EXPORT SAP BAK");

            // Add some data
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'PERNR');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'BEGDA');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'SATZA');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'LTIME');

            $baris = 1;
            foreach ($list_proses as $data) {
                $baris++;
                if (isset($data->tanggal_masuk))
                    $tanggal_absen = $this->generate->generateDateFormat($data->tanggal_masuk);
                else
                    $tanggal_absen = $this->generate->generateDateFormat($data->tanggal_absen);
                if ($data->detail == "NO_CI") {
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $data->nik, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $tanggal_absen, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, "P10", PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $data->absen_masuk, PHPExcel_Cell_DataType::TYPE_STRING);
                } else if ($data->detail == "NO_CO") {
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $data->nik, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $tanggal_absen, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, "P20", PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $data->absen_keluar, PHPExcel_Cell_DataType::TYPE_STRING);
                } else if ($data->detail == "NO_CICO") {
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $data->nik, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $tanggal_absen, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, "P10", PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $data->absen_masuk, PHPExcel_Cell_DataType::TYPE_STRING);
                    $baris++;
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $data->nik, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $tanggal_absen, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, "P20", PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $data->absen_keluar, PHPExcel_Cell_DataType::TYPE_STRING);
                }
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('BAK_SAP');


            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Export BAK (' . $title . ') to SAP (' . date('d-m-Y') . ').xls"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        } elseif ($modul == 'laporan') {
            switch ($param) {
                case "ho":
                    $title = "HO";
                    $this->general->connectDbPortal();
                    $list_proses = $this->dbak->get_bak(array(
                        'tanggal_awal' => $tanggal_awal,
                        'tanggal_akhir' => $tanggal_akhir,
                        'id_bak_status' => $id_bak_status,
                        'ho' => 'y'
                    ));
                    break;
                case "pabrik":
                    if ($managerUp)
                        $title = "Pabrik Mg Up";
                    else
                        $title = "Pabrik Kasie Down";
                    $this->general->connectDbPortal();
                    $list_proses = $this->dbak->get_bak(array(
                        'tanggal_awal' => $tanggal_awal,
                        'tanggal_akhir' => $tanggal_akhir,
                        'id_bak_status' => $id_bak_status,
                        'ho' => 'n',
                        'manager' => $managerUp
                    ));
                    break;
                case "ktp":
                    $title = "PT. Kirana Triputra Persada";

                    if ($cico == "nonci") {
                        $filter_cico = " AND #cico.fddatetimein is null";
                    } elseif ($cico == "nonco") {
                        $filter_cico = " AND #cico.fddatetimeout is null";
                    } elseif ($cico == "noncico") {
                        $filter_cico = " AND #cico.fddatetimein is null AND #cico.fddatetimeout is null";
                    } elseif ($cico == "lengkapcico") {
                        $filter_cico = " AND #cico.fddatetimein is not null AND #cico.fddatetimeout is not null";
                    } else {
                        $filter_cico = "";
                    }

                    $filter_ktp = ($nik == "") ? "" : " AND Transaksi.FcIdNo = `" . $nik . "`";
                    $this->general->connectDbPortal();
                    $list_proses = $this->dbak->get_bak_ktp(array(
                        'tanggal_awal' => $tanggal_awal,
                        'tanggal_akhir' => $tanggal_akhir,
                        'filter_ktp' => $filter_ktp,
                        'filter_cico' => $filter_cico
                    ));
                    break;
                default:
                    $title = "HO";
                    $list_proses = array();
                    break;
            }
            $this->general->closeDb();

            $objPHPExcel = new PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Kiranaku")
                ->setLastModifiedBy("Kiranaku")
                ->setTitle("Rekap Laporan BAK $title (" . date('d-m-Y') . ")")
                ->setSubject("Rekap Laporan BAK $title (" . date('d-m-Y') . ")")
                ->setDescription("Rekap Laporan BAK $title (" . date('d-m-Y') . ")")
                ->setCategory("Rekap Laporan BAK");

            $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:H1');

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(
                'A1',
                'Rekap Berita Kehadiran ' . $title . ' (' . $gen->generateDateFormat($tanggal_awal) .
                    ' sd ' . $gen->generateDateFormat($tanggal_akhir) . ')'
            );

            // Add some data
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'NIK');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', 'Nama');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'Tanggal Absen');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', 'Absen Masuk');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', 'Absen Keluar');
            if ($param <> "ktp") {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', 'Tanggal Pengajuan');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', 'Alasan');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H3', 'Status');
            } else {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', 'Durasi');
            }

            $baris = 3;
            foreach ($list_proses as $data) {
                $baris++;
                if ($param <> "ktp") {
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $data->nik, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $data->nama_karyawan, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, $gen->generateDateFormat($data->tanggal_absen), PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $data->absen_masuk, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $baris, $data->absen_keluar, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $baris, $gen->generateDateFormat($data->tanggal_absen), PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $baris, ucwords($data->alasan), PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('H' . $baris, ucwords($data->nama_status), PHPExcel_Cell_DataType::TYPE_STRING);
                } else {
                    $durasi = ($data->all_duration_in_minutes) ? ($data->duration_hour . " jam " . $data->duration_minutes . " menit") : "";
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $data->fcidno, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $data->Nama, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, $gen->generateDateFormat($data->tanggal), PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $data->tanggal_absen_in, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $baris, $data->tanggal_absen_out, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $baris, $durasi, PHPExcel_Cell_DataType::TYPE_STRING);
                }
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('BAK_LAPORAN');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            foreach (range('A', $objPHPExcel->getActiveSheet()->getHighestDataColumn()) as $col) {
                $objPHPExcel->getActiveSheet()
                    ->getColumnDimension($col)
                    ->setAutoSize(true);
            }
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Rekap BAK ' . $title . ' (' . date('d-m-Y') . ').xls"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
    }

    public function data()
    {
        $this->general->check_access();
        $this->data['title'] = "Data Berita Acara Kehadiran";

        // filter start
        $filter = $this->input->post();

        //        $tanggal_awal = date('Y-m-d', strtotime('first day of January ' . date('Y')));
        //        $tanggal_akhir = date('Y-m-d');
        //
        //        if (isset($filter['tanggal_awal']))
        //            $tanggal_awal = DateTime::createFromFormat('d.m.Y', $filter['tanggal_awal'])->format('Y-m-d');
        //
        //        if (isset($filter['tanggal_akhir']))
        //            $tanggal_akhir = DateTime::createFromFormat('d.m.Y', $filter['tanggal_akhir'])->format('Y-m-d');

        $user = $this->data['user'];

        $this->general->connectDbPortal();

        $list_data = $this->get_data_bak(
            array(
                'user' => $user,
                'atasan' => $user->nik,
                'id_bak_status' => ESS_BAK_STATUS_MENUNGGU
            )
        );

        $this->general->closeDb();

        $this->data['list_data'] = $list_data;

        $tanggal_awal = new DateTime('-7 day');
        $tanggal_akhir = new DateTime('-1 day');
        $tanggal_akhir->modify('+1 day');

        $interval = new DateInterval('P1D');
        $period = new DatePeriod($tanggal_awal, $interval, $tanggal_akhir);

        $periodeTanggal = array();
        foreach ($period as $dt) {
            $periodeTanggal[] = $dt->format('Y-m-d');
        }

        $this->data['periode'] = $periodeTanggal;

        $this->load->view('bak/data', $this->data);
    }

    public function karyawan($nik = null)
    {
        $this->general->check_access();
        $this->data['title'] = "Persetujuan Berita Acara Kehadiran";

        // filter start
        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('-7 day'));
        $tanggal_akhir = date('Y-m-d', strtotime('-1 day'));

        if (isset($filter['tanggal_awal']))
            //            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $user = $this->data['user'];

        $this->general->connectDbPortal();

        $nik = $this->generate->kirana_decrypt($nik);

        $karyawan = $this->dessgeneral->get_karyawans($nik);

        $this->data['title'] .= " (" . $karyawan[0]->nama . ")";

        $this->data['list_detail'] = $this->dbak->get_bak(
            array(
                'nik' => $nik,
                'id_bak_status' => array(
                    ESS_BAK_STATUS_DISETUJUI,
                    ESS_BAK_STATUS_COMPLETE
                ),
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir
            )
        );

        $this->general->closeDb();

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;

        $this->load->view('bak/data-detail', $this->data);
    }

    public function laporan($lokasi = 'ho')
    {
        $this->general->check_access();
        $this->data['title'] = "Rekap Laporan Berita Kehadiran";

        // filter start
        $filter = $this->input->post();
        $filter['tahun'] = date('Y');
        //        $filter['tahun'] = 2017;
        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        if (isset($filter['id_bak_status'])) {
            if ($filter['id_bak_status'] == 'Semua')
                $id_bak_status = null;
            else
                $id_bak_status = $filter['id_bak_status'];
        } else {
            $id_bak_status = null;
            $filter['id_bak_status'] = 'Semua';
        }
        if ($lokasi <> 'ktp') {
            if ($lokasi == 'ho')
                $ho = 'y';
            else
                $ho = 'n';

            $this->general->connectDbPortal();

            $list_bak = $this->dbak->get_bak(array(
                'sap' => true,
                'id_bak_status' => $id_bak_status,
                'ho' => $ho,
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir
            ));

            $this->data['list_bak'] = $list_bak;
            $this->data['filter'] = $filter;
            $this->data['id_bak_status'] = $id_bak_status;
            $this->data['lokasi'] = $lokasi;
            $this->data['tanggal_awal'] = $tanggal_awal;
            $this->data['tanggal_akhir'] = $tanggal_akhir;
            $this->data['statuses'] = $this->dbak->get_bak_status();

            $this->general->closeDb();

            $this->load->view('bak/laporan_bak', $this->data);
        } else {
            $this->general->connectDbPortal();

            $nik = (empty($filter['nik'])) ? "" : $filter['nik'];

            $cico = (empty($filter['cico'])) ? 'lengkap' : $filter['cico'];

            if ($cico == "nonci") {
                $filter_cico = " AND #cico.fddatetimein is null";
            } elseif ($cico == "nonco") {
                $filter_cico = " AND #cico.fddatetimeout is null";
            } elseif ($cico == "noncico") {
                $filter_cico = " AND #cico.fddatetimein is null AND #cico.fddatetimeout is null";
            } elseif ($cico == "lengkapcico") {
                $filter_cico = " AND #cico.fddatetimein is not null AND #cico.fddatetimeout is not null";
            } else {
                $filter_cico = "";
            }

            $filter_ktp = ($nik == "") ? "" : " AND Transaksi.FcIdNo = `" . $nik . "`";

            $list_bak = $this->dbak->get_bak_ktp(array(
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'filter_ktp' => $filter_ktp,
                'filter_cico' => $filter_cico
            ));

            $this->data['lokasi'] = $lokasi;
            $this->data['list_bak'] = $list_bak;
            $this->data['cico'] = $cico;
            $this->data['tanggal_awal'] = $tanggal_awal;
            $this->data['tanggal_akhir'] = $tanggal_akhir;

            $this->general->closeDb();

            $this->load->view('bak/laporan_bak_ktp', $this->data);
        }
    }

    public function get($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'pengajuan':
                $return = $this->get_pengajuan($data);
                break;
            case 'history':
                $return = $this->get_history($data);
                break;
            case 'massal':
                $return = $this->get_massal($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    public function save($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'masal':
                $return = $this->save_masal($data);
                break;
            case 'pengajuan':
                $return = $this->save_pengajuan($data);
                break;
            case 'approve':
                $return = $this->save_persetujuan($param, $data);
                break;
            case 'disapprove':
                $return = $this->save_persetujuan($param, $data);
                break;
            case 'approve_hr':
                $return = $this->save_persetujuan($param, $data);
                break;
            case 'batal':
                $return = $this->save_pembatalan($param, $data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    private function get_data_bak($params = array())
    {
        $user = $params['user'];

        $id_level = $user->id_level;
        $id_direktorat = $user->id_direktorat;
        $id_divisi = $user->id_divisi;
        $id_departemen = $user->id_departemen;
        $id_karyawan = $user->id_karyawan;

        $karyawans = $this->dessgeneral->get_karyawans($id_karyawan, false, $id_departemen, $id_divisi, $id_direktorat, $id_level);


        foreach ($karyawans as $index => $karyawan) {
            $karyawan->enId = $this->generate->kirana_encrypt($karyawan->id_karyawan);
            $karyawan->bak = $this->dbak->get_bak(array(
                'id_bak_status' => ESS_BAK_STATUS_COMPLETE,
                'nik' => $karyawan->id_karyawan,
                'tanggal_awal' => date('Y-m-d', strtotime('-7 day')),
                'tanggal_akhir' => date('Y-m-d', strtotime('-1 day'))
            ));

            $karyawans[$index] = $karyawan;
        }

        return $karyawans;
    }

    private function get_pengajuan($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dbak->get_bak(array(
                'single_row' => true,
                'id' => $id
            ));

            $this->general->closeDb();

            if (isset($result)) {
                $this->general->connectDbDefault();

                $detail_hari = $this->dbak->get_detail_hari(array(
                    'single_row' => true,
                    'nik' => $result->nik,
                    'tanggal_absen' => $result->tanggal_absen
                ));

                $jam_masuk = "08:00";
                $jam_keluar = "17:00";

                if (isset($detail_hari)) {
                    $jam_masuk = $detail_hari->absen_masuk;
                    $jam_keluar = $detail_hari->absen_keluar;
                }

                if ($result->absen_masuk == '-') {
                    $absen_masuk = $jam_masuk;
                    $enable_masuk = true;
                } else {
                    $absen_masuk = $result->absen_masuk;
                    $enable_masuk = false;
                }

                if ($result->detail == 'NO_CI' or $result->detail == 'NO_CICO') {
                    $enable_masuk = true;
                }

                if ($result->absen_keluar == '-') {
                    if ($jam_keluar == '24:00:00')
                        $jam_keluar = '00:00:00';
                    $absen_keluar = $jam_keluar;
                    $enable_keluar = true;
                } else {
                    $absen_keluar = $result->absen_keluar;
                    $enable_keluar = false;
                }

                if ($result->detail == 'NO_CO' or $result->detail == 'NO_CICO') {
                    $enable_keluar = true;
                }

                $result->absen_masuk_label = $absen_masuk;
                $result->absen_keluar_label = $absen_keluar;
                $result->absen_masuk_enable = $enable_masuk;
                $result->absen_keluar_enable = $enable_keluar;

                if (!empty($result->gambar_bukti)) {
                    $data_image = site_url(
                        'assets/file/ess/' .
                            $result->gambar_bukti
                    );

                    $headers = get_headers($data_image);
                    if ($headers[0] != "HTTP/1.1 200 OK") {
                        $data_image = "http://10.0.0.18/home/" . $result->gambar_bukti;
                        $headers = get_headers($data_image);
                        if ($headers[0] == "HTTP/1.1 200 OK") {
                            $result->gambar_bukti = $data_image;
                        } else
                            $result->gambar_bukti = null;
                    } else
                        $result->gambar_bukti = $data_image;
                }

                $this->general->closeDb();
            }


            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_history($data)
    {
        if (isset($data['id']))
            $id = $this->generate->kirana_decrypt($data['id']);
        else
            $id = null;
        $result = $this->dessgeneral->get_bak_history(array(
            'id_bak' => $id
        ));

        foreach ($result as $i => $dt) {
            if ($dt->ho == 'y') {
                $bagian = (empty($dt->nama_departemen)) ? $dt->nama_divisi : $dt->nama_departemen;
            } else {
                $bagian = (empty($dt->nama_seksi)) ? $dt->nama_departemen : $dt->nama_seksi;
                $bagian = (empty($bagian)) ? $dt->nama_sub_divisi : $bagian;
                $bagian = (empty($bagian)) ? $dt->nama_divisi : $bagian;
                $bagian = (empty($bagian)) ? $dt->nama_pabrik : $bagian;
            }
            $dt->nama_bagian = $bagian;
            $result[$i] = $dt;
        }

        return $result;
    }

    private function save_pengajuan($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id_bak']) && !empty($data['id_bak'])) {

            $this->dgeneral->begin_transaction();

            $jenis_pengajuan = $data['jenis'];

            $send_email = false;

            $id_bak = $this->generate->kirana_decrypt($data['id_bak']);

            $data_bak = $this->dbak->get_bak(array(
                'id' => $id_bak,
                'single_row' => true
            ));
            if (isset($data['tanggal_masuk']) && date_create($data['tanggal_masuk']) !== false)
                $data['tanggal_masuk'] = date_create($data['tanggal_masuk'])->format('Y-m-d');
            if (isset($data['tanggal_keluar']) && date_create($data['tanggal_keluar']) !== false)
                $data['tanggal_keluar'] = date_create($data['tanggal_keluar'])->format('Y-m-d');

            unset($data['id_bak']);
            unset($data['jenis']);

            if (empty($jenis_pengajuan)) {
                $data['id_bak_status'] = ESS_BAK_STATUS_MENUNGGU;
            } else {
                switch (intval($jenis_pengajuan)) {
                    case ESS_BAK_ALASAN_TERLAMBAT:
                        $data['detail'] = 'MISS_CI';
                        break;
                    case ESS_BAK_ALASAN_PULANG_CEPAT:
                        $data['detail'] = 'MISS_CO';
                        break;
                    case ESS_BAK_ALASAN_KOMBINASI_DTG_PLG:
                        $data['detail'] = 'MISS_CICO';
                        break;
                }
            }

            if ($data['id_bak_alasan'] != ESS_BAK_ALASAN_LAIN) {
                $alasan = $this->dbak->get_bak_alasan(array(
                    'single_row' => true,
                    'id' => $data['id_bak_alasan']
                ));

                $data['alasan'] = $alasan->nama;
            }

            if (isset($data_bak) and ($data_bak->id_bak_status == ESS_BAK_STATUS_DEFAULT or $data_bak->id_bak_status == ESS_BAK_STATUS_DITOLAK)) {

                if ($data_bak->id_bak_status != ESS_BAK_STATUS_DITOLAK) {
                    if ($data['id_bak_alasan'] == ESS_BAK_ALASAN_HAPUS_BAK) {
                        $data['detail'] = 'DEL_BAK';
                        unset($data['tanggal_masuk']);
                        unset($data['absen_masuk']);
                        unset($data['tanggal_keluar']);
                        unset($data['absen_keluar']);
                    } else if ($data_bak->absen_masuk == '-' and $data_bak->absen_keluar == '-')
                        $data['detail'] = 'NO_CICO';
                    else if ($data_bak->absen_masuk == '-')
                        $data['detail'] = "NO_CI";
                    else if ($data_bak->absen_keluar == '-')
                        $data['detail'] = "NO_CO";
                }

                $data_update = $this->dgeneral->basic_column("insert", $data);

                $result = $this->dgeneral->update('tbl_bak', $data_update, array(
                    array(
                        "kolom" => "id_bak",
                        "value" => $id_bak
                    )
                ));

                $data_history = array(
                    'id_bak' => $id_bak,
                    'id_bak_status' => ESS_BAK_STATUS_MENUNGGU
                );

                $data_history = $this->dgeneral->basic_column('insert_simple', $data_history);

                $this->dgeneral->insert('tbl_bak_history', $data_history);

                $send_email = true;
            } else {

                $data_update = $this->dgeneral->basic_column("update", $data);

                $result = $this->dgeneral->update('tbl_bak', $data_update, array(
                    array(
                        "kolom" => "id_bak",
                        "value" => $id_bak
                    )
                ));
            }

            $send_email_result = true;

            try {
                if ($send_email) {

                    $bak = $this->dbak->get_bak(
                        array(
                            'id' => $id_bak,
                            'single_row' => true
                        )
                    );

                    $karyawan = $this->dessgeneral->get_karyawan($bak->id_karyawan);

                    $atasan = $this->less->get_atasan(
                        array(
                            'nik' => $bak->nik
                        )
                    );

                    $email_tujuan = ESS_EMAIL_DEBUG_MODE ? json_decode(ESS_EMAIL_TESTER) : $atasan['list_atasan_email'];
                    //                    $email_tujuan = $atasan['list_atasan_email'];

                    $result = $this->less->send_email(
                        array(
                            'judul' => "Konfirmasi Pengajuan Berita Acara Kehadiran " . ucwords(strtolower($karyawan->nama)),
                            'email_pengirim' => "KiranaKu",
                            'email_tujuan' => $email_tujuan,
                            'view' => 'emails/pengajuan_bak_new',
                            'data' => array(
                                'data' => $bak
                            )
                        )
                    );
                    if ($result['sts'] == "NotOK")
                        $send_email_result = false;
                }
            } catch (Exception $exception) {
                $send_email_result = false;
            }

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else if ($send_email && !$send_email_result) {
                $this->dgeneral->rollback_transaction();
                $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil ditambahkan";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }


        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_persetujuan($param, $data)
    {

        $id_bak_status = ESS_BAK_STATUS_DISETUJUI;
        if ($param == "disapprove")
            $id_bak_status = ESS_BAK_STATUS_DITOLAK;
        else if ($param == "approve_hr")
            $id_bak_status = ESS_BAK_STATUS_DISETUJUI_OLEH_HR;

        if (isset($data['approvals'])) {

            $upload_error = array();
            $uploaded = array();

            /** Upload untuk file bukti dari persetujuan oleh HR **/
            if (isset($_FILES) && $id_bak_status == ESS_BAK_STATUS_DISETUJUI_OLEH_HR) {
                $uploaddir = ESS_PATH_FILE . KIRANA_PATH_APPS_IMAGE_FOLDER . ESS_BAK_UPLOAD_FOLDER;
                if (!file_exists($uploaddir)) {
                    mkdir($uploaddir, 0777, true);
                }
                $config['upload_path'] = $uploaddir;
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['max_size'] = 5000;
                $config['overwrite'] = true;

                $this->load->library('upload', $config);

                foreach ($_FILES as $i => $file) {
                    $_FILES[$i]['name'] = "BUKTI_" . $i . "." . pathinfo($_FILES[$i]['name'], PATHINFO_EXTENSION);
                    if ($this->upload->do_upload($i)) {
                        $upload_data = $this->upload->data();
                        $uploaded[$i] = KIRANA_PATH_APPS_IMAGE_FOLDER .
                            ESS_BAK_UPLOAD_FOLDER . $upload_data['file_name'];
                    } else {
                        $upload_error[] = $this->upload->display_errors();
                    }
                }
            }
            if (!is_array($data['approvals']))
                $approvals = json_decode($data['approvals']);
            else {
                $approvals = json_decode(json_encode($data['approvals'], false));
            }

            if (count($approvals) > 0) {
                $this->general->connectDbPortal();
                $this->dgeneral->begin_transaction();

                $this->db->query("SET ANSI_NULLS ON");
                $this->db->query("SET ANSI_WARNINGS ON");

                foreach ($approvals as $approval) {
                    if (isset($approval->id)) {
                        $id = $this->generate->kirana_decrypt($approval->id);
                        $catatan = "-";
                        if (isset($approval->catatan))
                            $catatan = $approval->catatan;

                        $data_row = $this->dgeneral->basic_column('update');

                        if (isset($uploaded[$approval->id]))
                            $data_row['gambar'] = $uploaded[$approval->id];

                        $data_row['id_bak_status'] = $id_bak_status;
                        $data_row['catatan'] = $catatan;

                        $this->dgeneral->update(
                            'tbl_bak',
                            $data_row,
                            array(
                                array(
                                    'kolom' => 'id_bak',
                                    'value' => $id
                                )
                            )
                        );

                        $data_history = array(
                            'id_bak' => $id,
                            'id_bak_status' => $id_bak_status
                        );

                        $data_history = $this->dgeneral->basic_column('insert_simple', $data_history);

                        $this->dgeneral->insert('tbl_bak_history', $data_history);
                    }
                }

                $send_email_result = true;

                try {
                    if ($id_bak_status == ESS_BAK_STATUS_DITOLAK) {
                        foreach ($approvals as $approval) {
                            if (isset($approval->id)) {
                                $id = $this->generate->kirana_decrypt($approval->id);

                                $bak = $this->dbak->get_bak(
                                    array(
                                        'id' => $id,
                                        'single_row' => true
                                    )
                                );

                                $karyawan = $this->dessgeneral->get_karyawan($bak->id_karyawan);

                                $atasan = $this->less->get_atasan(array(
                                    'nik' => $karyawan->nik
                                ));

                                //                                $email_tujuan = $atasan['list_atasan_email'];
                                $email_tujuan = ESS_EMAIL_DEBUG_MODE ? json_decode(ESS_EMAIL_TESTER) : $atasan['list_atasan_email'];

                                $result = $this->less->send_email(
                                    array(
                                        'judul' => "Konfirmasi Pengajuan Berita Acara Kehadiran " . ucwords(strtolower($karyawan->nama)),
                                        'email_pengirim' => "KiranaKu",
                                        'email_tujuan' => $email_tujuan,
                                        'view' => 'emails/pengajuan_bak_new',
                                        'data' => array(
                                            'data' => $bak
                                        )
                                    )
                                );
                                if ($result['sts'] == "NotOK")
                                    $send_email_result = false;
                            }
                        }
                    }
                } catch (Exception $exception) {
                    $send_email_result = false;
                }

                if (
                    $this->dgeneral->status_transaction() === FALSE or
                    ($id_bak_status == ESS_BAK_STATUS_DISETUJUI_OLEH_HR
                        and count($upload_error) > 0
                        and count($uploaded) != count($approvals))
                ) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";

                    foreach ($uploaded as $upload) {
                        unlink(ESS_PATH_FILE . $upload);
                    }
                } else if (!$send_email_result) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil disimpan";
                    $sts = "OK";
                }
                $this->general->closeDb();
            } else {
                $msg = "Tidak ada data yang disimpan";
                $sts = "OK";
            }
        } else {
            $msg = "Tidak ada data yang disimpan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_masal($data)
    {
        $this->general->connectDbPortal();

        $nik = base64_decode($this->session->userdata('-nik-'));

        if (isset($data['id_massal']) && !empty($data['id_massal']))
            $id_masal = $this->generate->kirana_decrypt($data['id_massal']);
        else
            $id_masal = null;

        $data['jam_bak_masuk'] = isset($data['jam_bak_masuk']) ? $data['jam_bak_masuk'] : null;
        $data['jam_bak_keluar'] = isset($data['jam_bak_keluar']) ? $data['jam_bak_keluar'] : null;

        $karyawans = explode('.', $data['karyawans']);

        $tanggal_bak = date('Y-m-d', strtotime($data['tanggal_bak']));

        $data['id_bak_alasan'] = intval($data['id_bak_alasan']);

        $data_row = array(
            'karyawans' => $data['karyawans'],
            'tanggal_bak' => $tanggal_bak,
            'jam_bak_masuk' => $data['jam_bak_masuk'],
            'jam_bak_keluar' => $data['jam_bak_keluar'],
            'catatan' => $data['catatan'],
            'id_bak_alasan' => $data['id_bak_alasan']
        );

        $this->dgeneral->begin_transaction();

        if (!isset($id_masal)) {
            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $result = $this->dgeneral->insert('tbl_bak_massal', $data_row);
        } else {

            $masal = $this->dbak->get_bak_massal(array(
                'single_row' => true,
                'id' => $id_masal
            ));

            $this->db->where('tanggal_absen', $masal->tanggal_bak);

            if ($masal->id_bak_alasan == ESS_BAK_ALASAN_TERLAMBAT)
                $this->db->where('detail', 'MASSAL_CI');
            else if ($masal->id_bak_alasan == ESS_BAK_ALASAN_PULANG_CEPAT)
                $this->db->where('detail', 'MASSAL_CO');
            else
                $this->db->where('detail', 'MASSAL_CICO');

            $oldKaryawans = explode('.', $masal->karyawans);
            $this->db->where_in('nik', $oldKaryawans);

            $result = $this->db->update('tbl_bak', array(
                'detail' => null,
                'keterangan' => null,
                'alasan' => null,
                'id_bak_alasan' => ESS_BAK_ALASAN_KOSONG
            ));

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $result = $this->dgeneral->update('tbl_bak_massal', $data_row, array(
                array(
                    "kolom" => "id_bak_massal",
                    "value" => $id_masal
                )
            ));
        }


        $karyawansDiperbolehkan = array();

        if ($result and count($karyawans) > 0) {
            if ($data['id_bak_alasan'] == ESS_BAK_ALASAN_TERLAMBAT)
                $data_update_bak = array(
                    'tanggal_absen' => $tanggal_bak,
                    'id_bak_alasan' => $data['id_bak_alasan'],
                    'alasan' => 'Datang Terlambat Massal - ' . $data['catatan'],
                    'keterangan' => 'Datang Terlambat Massal - ' . $data['catatan'],
                    'detail' => 'MASSAL_CI'
                );
            else if ($data['id_bak_alasan'] == ESS_BAK_ALASAN_PULANG_CEPAT)
                $data_update_bak = array(
                    'tanggal_absen' => $tanggal_bak,
                    'id_bak_alasan' => $data['id_bak_alasan'],
                    'alasan' => 'Pulang Cepat Massal - ' . $data['catatan'],
                    'keterangan' => 'Pulang Cepat Massal - ' . $data['catatan'],
                    'detail' => 'MASSAL_CO'
                );
            else
                $data_update_bak = array(
                    'tanggal_absen' => $tanggal_bak,
                    'id_bak_alasan' => $data['id_bak_alasan'],
                    'alasan' => 'Datang Terlambat & Pulang Cepat Massal - ' . $data['catatan'],
                    'keterangan' => 'Dtg Trlmbt & Plg Cpt Massal - ' . $data['catatan'],
                    'detail' => 'MASSAL_CICO'
                );

            foreach ($karyawans as $karyawan) {
                if ($data['id_bak_alasan'] == ESS_BAK_ALASAN_TERLAMBAT) {
                    $bak = $this->dbak->get_bak(array(
                        'nik' => $karyawan,
                        'tanggal_absen' => $tanggal_bak,
                        'order_by' => 'absen_masuk asc',
                        'jenis' => 1,
                        'single_row' => true
                    ));

                    if (isset($bak)) {
                        if (
                            isset($bak->absen_masuk) and isset($bak->tanggal_masuk)
                        ) {
                            $absenMassal = date_create($tanggal_bak . ' ' . $data['jam_bak_masuk']);
                            $absenMasuk = date_create($bak->tanggal_masuk . ' ' . $bak->absen_masuk);
                            if (isset($bak->jadwal_absen)) {
                                $jadwalMasuk = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_masuk);
                                if ($jadwalMasuk < $absenMasuk and $absenMassal >= $absenMasuk)
                                    $karyawansDiperbolehkan[] = $karyawan;
                            } else if ($absenMassal >= $absenMasuk)
                                $karyawansDiperbolehkan[] = $karyawan;
                        }
                    }
                } else if ($data['id_bak_alasan'] == ESS_BAK_ALASAN_PULANG_CEPAT) {
                    $bak = $this->dbak->get_bak(array(
                        'nik' => $karyawan,
                        'tanggal_absen' => $tanggal_bak,
                        'order_by' => 'absen_keluar desc',
                        'jenis' => 1,
                        'single_row' => true
                    ));

                    if (isset($bak)) {
                        if (isset($bak->tanggal_keluar) and isset($bak->absen_keluar)) {
                            $absenMassal = date_create($tanggal_bak . ' ' . $data['jam_bak_keluar']);
                            $absenKeluar = date_create($bak->tanggal_keluar . ' ' . $bak->absen_keluar);
                            if (isset($bak->jadwal_absen)) {
                                if (date_create($bak->tanggal_absen) != date_create($bak->jadwal_absen))
                                    $jadwalKeluar = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_keluar)->add(DateInterval::createFromDateString('+1 day'));
                                else
                                    $jadwalKeluar = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_keluar);
                                if ($jadwalKeluar > $absenKeluar and $absenMassal <= $absenKeluar)
                                    $karyawansDiperbolehkan[] = $karyawan;
                            } else if ($absenMassal <= $absenKeluar)
                                $karyawansDiperbolehkan[] = $karyawan;
                        }
                    }
                } else if ($data['id_bak_alasan'] == ESS_BAK_ALASAN_KOMBINASI_DTG_PLG) {

                    $bak = $this->dbak->get_bak(array(
                        'nik' => $karyawan,
                        'tanggal_absen' => $tanggal_bak,
                        'order_by' => 'absen_masuk asc, absen_keluar desc',
                        'single_row' => true
                    ));

                    if (isset($bak)) {
                        $absenMasukOveride = false;
                        $absenKeluarOveride = false;
                        if (
                            isset($bak->absen_masuk) and isset($bak->tanggal_masuk)
                        ) {
                            $absenMassalMasuk = date_create($tanggal_bak . ' ' . $data['jam_bak_masuk']);
                            $absenMassalKeluar = date_create($tanggal_bak . ' ' . $data['jam_bak_keluar']);
                            $absenMasuk = date_create($bak->tanggal_masuk . ' ' . $bak->absen_masuk);
                            if (isset($bak->jadwal_absen)) {
                                $jadwalMasuk = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_masuk);
                                if ($jadwalMasuk < $absenMasuk and $absenMassalMasuk >= $absenMasuk)
                                    $absenMasukOveride = true;
                            } else if ($absenMassalMasuk >= $absenMasuk)
                                $absenMasukOveride = true;


                            if (isset($bak->tanggal_keluar) and isset($bak->absen_keluar)) {
                                $absenKeluar = date_create($bak->tanggal_keluar . ' ' . $bak->absen_keluar);
                                if (isset($bak->jadwal_absen)) {
                                    if (date_create($bak->tanggal_absen) != date_create($bak->jadwal_absen))
                                        $jadwalKeluar = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_keluar)->add(DateInterval::createFromDateString('+1 day'));
                                    else
                                        $jadwalKeluar = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_keluar);
                                    if ($jadwalKeluar > $absenKeluar and $absenMassalKeluar <= $absenKeluar)
                                        $absenKeluarOveride = true;
                                } else if ($absenMassalKeluar <= $absenKeluar)
                                    $absenKeluarOveride = true;
                            }
                        }

                        if ($absenMasukOveride and $absenKeluarOveride)
                            $karyawansDiperbolehkan[] = $karyawan;

                        //                        $absenMasuk = date_create($bak->tanggal_masuk . ' ' . $bak->absen_masuk);
                        //                        $absenKeluar = date_create($bak->tanggal_keluar . ' ' . $bak->absen_keluar);
                        //                        if (isset($bak->jadwal_absen) and isset($bak->jadwal_absen_masuk)) {
                        //                            $jadwalMasuk = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_masuk);
                        //                            $jadwalKeluar = date_create($bak->jadwal_absen . ' ' . $bak->jadwal_absen_keluar);
                        //                            if ($jadwalKeluar < $jadwalMasuk)
                        //                                $jadwalKeluar = $jadwalKeluar->add(DateInterval::createFromDateString('1 day'));
                        //
                        //                            if ($jadwalKeluar > $absenKeluar and $jadwalMasuk < $absenMasuk)
                        //                                $karyawansDiperbolehkan[] = $karyawan;
                        //                        } else
                        //                            $karyawansDiperbolehkan[] = $karyawan;
                    }
                }
            }

            $data_update = $this->dgeneral->basic_column('update', $data_update_bak);

            if (count($karyawansDiperbolehkan) > 0) {
                $this->db->where('tanggal_absen', $tanggal_bak);
                $this->db->where('absen_masuk !=', '-');
                $this->db->where('absen_keluar !=', '-');

                //                if ($data['id_bak_alasan'] == ESS_BAK_ALASAN_TERLAMBAT)
                //                    $this->db->where('absen_masuk <=', $data['jam_bak']);
                //                else if ($data['id_bak_alasan'] == ESS_BAK_ALASAN_PULANG_CEPAT)
                //                    $this->db->where('absen_keluar >=', $data['jam_bak']);
                //                else if ($data['id_bak_alasan'] == ESS_BAK_ALASAN_KOMBINASI_DTG_PLG) {
                //                    $this->db->where('absen_masuk <=', $data['jam_bak']);
                //                    $this->db->where('absen_keluar >=', $data['jam_bak']);
                //                }

                //                $this->db->where_not_in('tipe', array('L', '0120'));
                $this->db->where_in('nik', $karyawansDiperbolehkan);
                $this->db->update('tbl_bak', $data_update);
            }
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();

            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }
        $this->general->closeDb();

        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_pembatalan($param, $data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id_bak']) && !empty($data['id_bak'])) {

            $id_bak = $this->generate->kirana_decrypt($data['id_bak']);

            $data_bak = $this->dbak->get_bak(array(
                'id' => $id_bak,
                'single_row' => true
            ));

            unset($data['id_bak']);

            $upload_error = array();
            $uploaded = null;

            if (isset($data_bak) and (in_array($data_bak->id_bak_status, array(ESS_BAK_STATUS_DISETUJUI, ESS_BAK_STATUS_DISETUJUI_OLEH_HR, ESS_BAK_STATUS_COMPLETE)))) {

                /** Upload untuk file bukti dari pembatalan oleh HR **/
                if (isset($_FILES['gambar_bukti'])) {
                    $uploaddir = ESS_PATH_FILE . KIRANA_PATH_APPS_IMAGE_FOLDER . ESS_BAK_UPLOAD_FOLDER;
                    if (!file_exists($uploaddir)) {
                        mkdir($uploaddir, 0777, true);
                    }
                    $config['upload_path'] = $uploaddir;
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                    $config['max_size'] = 5000;
                    $config['overwrite'] = true;

                    $this->load->library('upload', $config);

                    $_FILES['gambar_bukti']['name'] = "BUKTI_BATAL_" . $id_bak . "." . pathinfo($_FILES['gambar_bukti']['name'], PATHINFO_EXTENSION);
                    if ($this->upload->do_upload('gambar_bukti')) {
                        $upload_data = $this->upload->data();
                        $uploaded = KIRANA_PATH_APPS_IMAGE_FOLDER .
                            ESS_BAK_UPLOAD_FOLDER . $upload_data['file_name'];
                    } else {
                        $upload_error[] = $this->upload->display_errors();
                    }
                } else {
                    if ($_FILES['gambar_bukti']['error'] != 0) {
                        switch ($_FILES['gambar_bukti']['error']) {
                            case UPLOAD_ERR_INI_SIZE:
                                $upload_error[] = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                break;
                            case UPLOAD_ERR_FORM_SIZE:
                                $upload_error[] = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                break;
                            case UPLOAD_ERR_PARTIAL:
                                $upload_error[] = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                                break;
                            case UPLOAD_ERR_EXTENSION:
                                $upload_error[] = 'Upload berkas dihentikan oleh ekstensi. Harap pilih file lain.';
                                break;
                            default:
                                $upload_error[] = "Error upload gambar.";
                                break;
                        }
                    } else
                        $upload_error[] = "File upload bukti tidak tersedia, harap pilih file.";
                }

                if (isset($uploaded)) {
                    $this->dgeneral->begin_transaction();

                    $data_update = $this->dgeneral->basic_column("update", $data);
                    $data_update['gambar_bukti'] = $uploaded;
                    $data_update['id_bak_status'] = ESS_BAK_STATUS_DIBATALKAN;

                    $result = $this->dgeneral->update('tbl_bak', $data_update, array(
                        array(
                            "kolom" => "id_bak",
                            "value" => $id_bak
                        )
                    ));

                    $data_history = array(
                        'id_bak' => $id_bak,
                        'id_bak_status' => ESS_BAK_STATUS_DIBATALKAN
                    );

                    $data_history = $this->dgeneral->basic_column('insert_simple', $data_history);

                    $this->dgeneral->insert('tbl_bak_history', $data_history);

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();

                        unlink(ESS_PATH_FILE . $uploaded);

                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil ditambahkan";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else if (count($upload_error) > 0) {
                    $msg = join(', ', $upload_error);
                    $sts = "NotOK";
                } else {
                    $msg = "File upload bukti tidak tersedia, harap pilih file.";
                    $sts = "NotOK";
                }
            } else {
                $msg = "Tidak ada data BAK yg tersedia.";
                $sts = "NotOK";
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function get_massal($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dbak->get_bak_massal(array(
                'single_row' => true,
                'id' => $id
            ));

            $this->general->closeDb();

            if (isset($result)) {
                $this->general->connectDbDefault();

                $this->general->closeDb();
            }


            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    public function delete($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'massal':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $masal = $this->dbak->get_bak_massal(array(
                        'single_row' => true,
                        'id' => $id
                    ));

                    $this->db->where('tanggal_absen', $masal->tanggal_bak);

                    if ($masal->id_bak_alasan == ESS_BAK_ALASAN_TERLAMBAT)
                        $this->db->where('detail', 'MASSAL_CI');
                    else
                        $this->db->where('detail', 'MASSAL_CO');

                    $oldKaryawans = explode('.', $masal->karyawans);
                    $this->db->where_in('nik', $oldKaryawans);
                    $this->db->update('tbl_bak', array(
                        'detail' => null,
                        'keterangan' => null,
                        'alasan' => null,
                        'id_bak_alasan' => ESS_BAK_ALASAN_KOSONG
                    ));

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update(
                        'tbl_bak_massal',
                        $data_row,
                        array(
                            array(
                                'kolom' => 'id_bak_massal',
                                'value' => $id
                            )
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil dihapus";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            case "pengajuan":
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $bak = $this->dbak->get_bak(array(
                        'single_row' => true,
                        'id' => $id
                    ));

                    if (in_array($bak->detail, array('NO_CI', 'NO_CO', 'NO_CICO', 'DEL_BAK'))) {
                        $data_update = null;
                        switch ($bak->detail) {
                            case 'NO_CI':
                                $data_update = array(
                                    'detail' => null,
                                    'atasan' => null,
                                    'atasan_email' => null,
                                    'absen_masuk' => '-',
                                    'tanggal_masuk' => null,
                                    'id_bak_alasan' => 0,
                                    'id_bak_status' => $bak->status_awal,
                                    'alasan' => null,
                                    'keterangan' => null
                                );
                                break;
                            case 'NO_CO':
                                $data_update = array(
                                    'detail' => null,
                                    'atasan' => null,
                                    'atasan_email' => null,
                                    'absen_keluar' => '-',
                                    'tanggal_keluar' => null,
                                    'id_bak_alasan' => 0,
                                    'id_bak_status' => $bak->status_awal,
                                    'alasan' => null,
                                    'keterangan' => null
                                );
                                break;
                            case 'NO_CICO':
                                $data_update = array(
                                    'detail' => null,
                                    'atasan' => null,
                                    'atasan_email' => null,
                                    'absen_masuk' => '-',
                                    'tanggal_masuk' => null,
                                    'absen_keluar' => '-',
                                    'tanggal_keluar' => null,
                                    'id_bak_alasan' => 0,
                                    'id_bak_status' => $bak->status_awal,
                                    'alasan' => null,
                                    'keterangan' => null
                                );
                                break;
                            case 'DEL_BAK':
                                $data_update = array(
                                    'detail' => null,
                                    'atasan' => null,
                                    'atasan_email' => null,
                                    'id_bak_alasan' => 0,
                                    'id_bak_status' => $bak->status_awal,
                                    'alasan' => null,
                                    'keterangan' => null
                                );
                                break;
                        }

                        if (isset($data_update)) {
                            $data_row = $this->dgeneral->basic_column('update', $data_update);

                            $this->dgeneral->update(
                                'tbl_bak',
                                $data_row,
                                array(
                                    array(
                                        'kolom' => 'id_bak',
                                        'value' => $id
                                    )
                                )
                            );
                        }
                        if ($this->dgeneral->status_transaction() === FALSE) {
                            $this->dgeneral->rollback_transaction();
                            $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";
                        } else {
                            $this->dgeneral->commit_transaction();
                            $msg = "Data berhasil dihapus";
                            $sts = "OK";
                        }
                        $this->general->closeDb();
                    } else {
                        $msg = "Tidak ada data yang akan dibatalkan";
                        $sts = "NotOK";
                    }
                    //                    else if (in_array($bak->detail, array('MISS_CI', 'MISS_CO', 'MISS_CICO'))) {
                    //                        $data_update = null;
                    //                        switch($bak->detail)
                    //                        {
                    //                            case 'NO_CI':
                    //                                $data_update = array(
                    //                                    'absen_masuk' => '-',
                    //                                    'tanggal_masuk' => null,
                    //                                    'id_bak_alasan' => 0,
                    //                                    'alasan' => null,
                    //                                    'keterangan' => null
                    //                                );
                    //                                break;
                    //                            case 'NO_CO':
                    //                                $data_update = array(
                    //                                    'absen_keluar' => '-',
                    //                                    'tanggal_keluar' => null,
                    //                                    'id_bak_alasan' => 0,
                    //                                    'alasan' => null,
                    //                                    'keterangan' => null
                    //                                );
                    //                                break;
                    //                            case 'NO_CICO':
                    //                                $data_update = array(
                    //                                    'absen_masuk' => '-',
                    //                                    'tanggal_masuk' => null,
                    //                                    'absen_keluar' => '-',
                    //                                    'tanggal_keluar' => null,
                    //                                    'id_bak_alasan' => 0,
                    //                                    'alasan' => null,
                    //                                    'keterangan' => null
                    //                                );
                    //                                break;
                    //                        }
                    //                        if(isset($data_update))
                    //                        {
                    //                            $data_row = $this->dgeneral->basic_column('update',$data_update);
                    //
                    //                            $this->dgeneral->update('tbl_bak', $data_row,
                    //                                array(
                    //                                    array(
                    //                                        'kolom' => 'id_bak',
                    //                                        'value' => $id
                    //                                    )
                    //                                )
                    //                            );
                    //                        }
                    //                    }
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }
}
