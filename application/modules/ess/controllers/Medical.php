<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS Medical - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Medical extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Employee Self Service";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->library('form_validation');
        $this->load->library('less');
        $this->load->model('dessgeneral');
        $this->load->model('dmedical');
    }

    /**
     * List FBK rawat jalan, lensa dan kacamata
     * - Dipisahkan pengambilan data, diambil data yang belum disetujui HR lalu yang disetujui HR
     * setelah itu di merge
     */
    public function pengajuan()
    {
        $this->general->check_access();
        $this->data['title'] = "Form Bantuan Kesehatan";

        // filter start
        $filter = $this->input->post();

        if (!isset($filter['tahun']))
            $filter['tahun'] = date('Y');

        $nik = base64_decode($this->session->userdata('-nik-'));

        $cutoff = $this->dmedical->get_fbk_cutoff(
            array(
                'tahun' => date('Y'),
                'single_row' => true
            )
        );
        $this->data['cutoff'] = $cutoff;

        $plafons = $this->less->get_plafon_sisa(
            array(
                'tanggal_akhir' => date('Y-m-d'),
                'nik' => $nik
            )
        );
		// echo json_encode($plafons);
		// exit();


        $karyawan = $this->dessgeneral->get_karyawan($nik);

        $last_frame = $this->dmedical->get_fbk(array(
            'jenis' => 'frame',
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'order_by' => 'id_fbk DESC',
            'single_row' => true,
            'id_fbk_status' => ESS_MEDICAL_STATUS_DISETUJUI
        ));

        $first_frame = $this->dmedical->get_fbk(array(
            'jenis' => 'frame',
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'order_by' => 'id_fbk ASC',
            'single_row' => true,
            'id_fbk_status' => ESS_MEDICAL_STATUS_DISETUJUI
        ));

        $last_lensa = $this->dmedical->get_fbk(array(
            'jenis' => 'lensa',
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'order_by' => 'id_fbk DESC',
            'single_row' => true,
            'id_fbk_status' => ESS_MEDICAL_STATUS_DISETUJUI
        ));


        $allowFrame = false;
        $warningFrame = "";
        $allowLensa = false;
        $warningLensa = "";
        $allowBersalin = false;
        $warningBersalin = "";

        $tanggal_join = date('Y-m-d', strtotime($karyawan->tanggal_join));
        $tanggal_join_allowed = date('Y-m-d', strtotime('+' . ESS_MEDICAL_JUMLAH_TAHUN_JOIN . ' Year', strtotime($karyawan->tanggal_join)));
        $tanggal_tetap = date('Y-m-d', strtotime($karyawan->tanggal_tetap));
        $today = date('Y-m-d');
        $tahun_sekarang = date('Y');
        if (isset($_GET['tahun_klaim']) && ESS_EMAIL_DEBUG_MODE) {
            $today = date('Y-m-d', strtotime('last day of december ' . $_GET['tahun_klaim']));
            $tahun_sekarang = $_GET['tahun_klaim'];
        }

        if (
        ($karyawan->status === 'TP' && $today >= $tanggal_join_allowed) || $karyawan->status === 'CK'
        ) {

            if ($filter['tahun'] == $tahun_sekarang) {

                $allowBersalin = true;

                /** Check Lensa  **/
                if (isset($last_lensa)) {
                    $tanggal_lensa = date_create($last_lensa->tanggal_buat);
                    $tanggal_lensa = explode('-', $tanggal_lensa->format('Y-m-d'));

                    $tanggal_allow_lensa = date(
                        'Y-m-d',
                        strtotime(
                            'First day of January ' .
                            ($tanggal_lensa[0] + ESS_MEDICAL_JUMLAH_TAHUN_LENSA)
                        )
                    );

                    if ($tanggal_allow_lensa > $today && $tanggal_lensa[0] != date('Y'))
                        $warningLensa = "Anda belum mendapatkan bantuan kesehatan lensa di tahun ini.";
                    else if ($plafons['sisa_fbk_lensa'] <= 0)
                        $warningLensa = "Plafon lensa kacamata anda habis.";

                    if (
                        $tanggal_allow_lensa <= $today or
                        (
                            $tanggal_lensa[0] == date('Y') and
                            $plafons['sisa_fbk_lensa'] > 0
                        )
                    )
                        $allowLensa = true;
                } else if ($plafons['sisa_fbk_lensa'] <= 0)
                    $warningLensa = "Plafon lensa kacamata anda habis.";
                else
                    $allowLensa = true;

                /** Check Frame  **/

                if (isset($first_frame)) {
                    $tanggal_first_frame = date_create($first_frame->tanggal_buat);
                    $tahun_first_frame = $tanggal_first_frame->format('Y');

                    $next_frame = $tanggal_first_frame->modify('+' . ESS_MEDICAL_JUMLAH_TAHUN_FRAME . ' years');
                    $temp_tahun_awal = $tahun_first_frame;
                    $temp_tahun_akhir = $next_frame->format('Y');

                    $tahun_allow_frame = false;
                    while (!$tahun_allow_frame) {
                        if ($tahun_sekarang >= $temp_tahun_awal && $tahun_sekarang < $temp_tahun_akhir) {
                            $tahun_allow_frame = true;
                            break;
                        }
                        $next_frame->modify('+' . ESS_MEDICAL_JUMLAH_TAHUN_FRAME . ' years');
                        $temp_tahun_awal = $temp_tahun_akhir;
                        $temp_tahun_akhir = $next_frame->format('Y');
                    }

                    $tanggal_allow_frame = date(
                        'Y-m-d',
                        strtotime(
                            'First day of January ' .
                            (intval($temp_tahun_awal))
                        )
                    );

                    if (
                        $tanggal_allow_frame > $today
//                        && $tanggal_frame[0] != date('Y')
                    )
                        $warningFrame = "Anda belum dapat mengajukan bantuan kesehatan Frame tahun ini.";
                    else if ($plafons['sisa_fbk_frame'] <= 0)
                        $warningFrame = "Plafon frame kacamata anda habis.";

                    if (
                        $tanggal_allow_frame <= $today or
                        (
//                            $tanggal_frame[0] == date('Y') and
                            $plafons['sisa_fbk_frame'] > 0
                        )
                    )
                        $allowFrame = true;

                } else if ($plafons['sisa_fbk_frame'] <= 0)
                    $warningFrame = "Plafon frame kacamata anda habis.";
                else
                    $allowFrame = true;
            }
        } else {
            $warningFrame = "Anda belum mendapatkan bantuan kesehatan Frame.";
            $warningLensa = "Anda belum mendapatkan bantuan kesehatan Lensa.";
            $warningBersalin = "Anda belum mendapatkan bantuan kesehatan Bersalin.";
        }

        $data_karyawan = $this->dessgeneral->get_karyawan($nik);
        $nama_karyawan = $data_karyawan->nama;
        $data_keluarga = $this->dmedical->get_keluarga(array('nik' => $nik));
        $jenis_sakit = $this->dmedical->get_jenis_sakit();
        $rumah_sakit = $this->dmedical->get_rumah_sakit();

        $this->data['nama_karyawan'] = $nama_karyawan;
        $this->data['data_keluarga'] = $data_keluarga;
        $this->data['jenis_sakit'] = $jenis_sakit;

        $this->data['tanggal_tetap'] = $tanggal_tetap;

        $this->data['allow_frame'] = $allowFrame;
        $this->data['allow_lensa'] = $allowLensa;
        $this->data['allow_bersalin'] = $allowBersalin;

        $list_jalan_belum_disetujui = $this->dmedical->get_fbk(array(
            'jenis' => 'jalan',
            'tahun' => $filter['tahun'],
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'kwitansi' => true,
            'id_fbk_status' => array(ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP, ESS_MEDICAL_STATUS_LENGKAP)
        ));

        $list_jalan_sudah_disetujui = $this->dmedical->get_fbk(array(
            'jenis' => 'jalan',
            'tahun' => $filter['tahun'],
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'kwitansi' => true,
            'id_fbk_status' => ESS_MEDICAL_STATUS_DISETUJUI,
            'order_by' => 'plafon_medical asc'
        ));

        $list_jalan = array_merge($list_jalan_belum_disetujui, $list_jalan_sudah_disetujui);

        $list_inap = $this->dmedical->get_fbk(array(
            'jenis' => 'inap',
            'tahun' => $filter['tahun'],
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'kwitansi' => true
        ));

        $list_bersalin = $this->dmedical->get_fbk(array(
            'jenis' => 'bersalin',
            'tahun' => $filter['tahun'],
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'kwitansi' => true
        ));

        $list_frame_belum_disetujui = $this->dmedical->get_fbk(array(
            'jenis' => 'frame',
            'tahun' => $filter['tahun'],
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'kwitansi' => true,
            'id_fbk_status' => array(ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP, ESS_MEDICAL_STATUS_LENGKAP)
        ));

        $list_frame_sudah_disetujui = $this->dmedical->get_fbk(array(
            'jenis' => 'frame',
            'tahun' => $filter['tahun'],
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'kwitansi' => true,
            'id_fbk_status' => ESS_MEDICAL_STATUS_DISETUJUI,
            'order_by' => 'plafon_frame asc'
        ));

        $list_frame = array_merge($list_frame_belum_disetujui, $list_frame_sudah_disetujui);


        $list_lensa_belum_disetujui = $this->dmedical->get_fbk(array(
            'jenis' => 'lensa',
            'tahun' => $filter['tahun'],
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'kwitansi' => true,
            'id_fbk_status' => array(ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP, ESS_MEDICAL_STATUS_LENGKAP)
        ));
        $list_lensa_sudah_disetujui = $this->dmedical->get_fbk(array(
            'jenis' => 'lensa',
            'tahun' => $filter['tahun'],
            'id_user' => base64_decode($this->session->userdata('-id_user-')),
            'encrypted' => true,
            'kwitansi' => true,
            'id_fbk_status' => ESS_MEDICAL_STATUS_DISETUJUI,
            'order_by' => 'plafon_lensa asc'
        ));

        $list_lensa = array_merge($list_lensa_belum_disetujui, $list_lensa_sudah_disetujui);
		
		
        $this->data['tab_pengajuan_jalan'] = $this->load->view('medical/_tab_pengajuan_jalan', array(
            'cutoff' => $cutoff,
            'sisa_fbk_jalan' => $plafons['sisa_fbk_jalan'],
            'sisa_fbk_jalan_next' => $plafons['sisa_fbk_jalan_next'],
            'list_jalan' => $list_jalan,
            'tahun' => $filter['tahun']
        ), true);

        $this->data['tab_pengajuan_inap'] = $this->load->view('medical/_tab_pengajuan_inap', array(
            'plafon_fbk_inap' => $plafons['sisa_fbk_inap'],
            'rumah_sakit' => $rumah_sakit,
            'list_inap' => $list_inap,
            'tahun' => $filter['tahun']
        ), true);

        $this->data['tab_pengajuan_bersalin'] = $this->load->view('medical/_tab_pengajuan_bersalin', array(
            'plafon_bersalin_normal' => $plafons['sisa_fbk_bersalin_normal'],
            'plafon_bersalin_cesar' => $plafons['sisa_fbk_bersalin_cesar'],
            'list_bersalin' => $list_bersalin,
            'tahun' => $filter['tahun'],
            'allow' => $allowBersalin,
            'warning' => $warningBersalin
        ), true);

        $this->data['tab_pengajuan_frame'] = $this->load->view('medical/_tab_pengajuan_frame', array(
            'sisa_fbk_frame' => $plafons['sisa_fbk_frame'],
            'list_frame' => $list_frame,
            'tahun' => $filter['tahun'],
            'allow' => $allowFrame,
            'warning' => $warningFrame
        ), true);

        $this->data['tab_pengajuan_lensa'] = $this->load->view('medical/_tab_pengajuan_lensa', array(
            'sisa_fbk_lensa' => $plafons['sisa_fbk_lensa'],
            'list_lensa' => $list_lensa,
            'tahun' => $filter['tahun'],
            'allow' => $allowLensa,
            'warning' => $warningLensa
        ), true);

        $this->load->view('medical/pengajuan', $this->data);
    }

    public function kelengkapan($lokasi = "ho", $level = 'kasi')
    {
//        $this->general->check_access();

        if ($lokasi == 'ho')
            $ho = 'y';
        else
            $ho = 'n';

        $managerUp = $level == 'mg';

        if ($lokasi == 'pabrik') {
            if ($managerUp)
                $this->data['title'] = "Set Kelengkapan Medical Pabrik Mg Up";
            else
                $this->data['title'] = "Set Kelengkapan Medical Pabrik Kasie Down";
        } else
            $this->data['title'] = "Set Kelengkapan Medical HO";

        // filter start
        $filter = $this->input->post();
        $filter_tahun = $this->input->post_get('tahun');
        $filter['tahun'] = isset($filter_tahun) ? $filter_tahun : date('Y');
//        $filter['tahun'] = 2017;

        $list_menunggu = $this->dmedical->get_fbk(array(
            'id_fbk_status' => ESS_MEDICAL_STATUS_MENUNGGU,
            'kwitansi' => true,
            'tahun' => $filter['tahun'],
            'encrypted' => true,
            'ho' => $ho,
            'manager' => ($lokasi != 'ho' ? $managerUp : null)
        ));

        $list_history = $this->dmedical->get_fbk(array(
            'id_fbk_status' => array(
                ESS_MEDICAL_STATUS_TDK_LENGKAP,
                ESS_MEDICAL_STATUS_LENGKAP
            ),
            'kwitansi' => true,
            'tahun' => $filter['tahun'],
            'encrypted' => true,
            'ho' => $ho,
            'manager' => ($lokasi != 'ho' ? $managerUp : null)
        ));

        $this->data['tab_kelengkapan_menunggu'] = $this->load->view('medical/_tab_kelengkapan_menunggu', array(
            'list_menunggu' => $list_menunggu
        ), true);
        $this->data['tab_kelengkapan_history'] = $this->load->view('medical/_tab_kelengkapan_history', array(
            'list_history' => $list_history
        ), true);

        $this->load->view('medical/kelengkapan', $this->data);

    }

    public function cutoff()
    {
        $this->general->check_access();
        $this->data['title'] = 'Cut-off medical tahunan';

        // filter start
        $filter = $this->input->post();

        $this->data['list_cutoff'] = $this->dmedical->get_fbk_cutoff();

        $this->load->view('medical/cutoff', $this->data);
    }

    public function cetak($id = null)
    {
        if (isset($id) && $id != 'undefined') {
            $this->data['title'] = "Cetak";

            $data = $this->get_pengajuan(array(
                'id' => $id
            ));

            if (isset($data['data'])) {
//                var_dump($data);die();
                $this->data['pengajuan'] = $data;

                $this->load->view('medical/cetak', $this->data);
            } else
                redirect('ess/medical/pengajuan');
        } else {
            redirect('ess/medical/pengajuan');
        }
    }

    public function sap($lokasi = "ho", $level = 'kasi')
    {
        $this->general->check_access();
        $managerUp = $level == 'mg';
        if ($lokasi == 'pabrik') {
            if ($managerUp)
                $this->data['title'] = "Proses SAP Medical Pabrik Mg Up";
            else
                $this->data['title'] = "Proses SAP Medical Pabrik Kasie Down";
        } else
            $this->data['title'] = "Proses SAP Medical HO";

        // filter start
        $filter = $this->input->post();
        $filter['tahun'] = date('Y');
//        $filter['tahun'] = 2017;

        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));
//            $tanggal_awal = date_create_from_format('d.m.Y', $filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date('Y-m-d', strtotime($filter['tanggal_akhir']));
//            $tanggal_akhir = date_create_from_format('d.m.Y', $filter['tanggal_akhir'])->format('Y-m-d');

        if ($lokasi == 'ho')
            $ho = 'y';
        else
            $ho = 'n';

        $list_medical = $this->dmedical->get_fbk(array(
            'id_fbk_status' => ESS_MEDICAL_STATUS_LENGKAP,
            'kwitansi' => true,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'encrypted' => true,
            'ho' => $ho,
            'manager' => ($lokasi != 'ho' ? $managerUp : null)
        ));

        $list_history = $this->dmedical->get_fbk(array(
            'id_fbk_status' => array(
                ESS_MEDICAL_STATUS_DISETUJUI
            ),
            'kwitansi' => true,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'encrypted' => true,
            'ho' => $ho,
            'manager' => ($lokasi != 'ho' ? $managerUp : null)
        ));

        $this->data['tab_sap_medical'] = $this->load->view('medical/_tab_sap_medical', array(
            'list_medical' => $list_medical,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'lokasi' => $lokasi,
            'manager' => $managerUp
        ), true);
        $this->data['tab_sap_history'] = $this->load->view('medical/_tab_sap_history', array(
            'list_history' => $list_history
        ), true);

        $this->load->view('medical/sap', $this->data);
    }

    public function rfc($action)
    {
        $this->load->library('sap');

        $gen = $this->generate;

        $filter = $this->input->post();

        $nik = isset($filter['nik']) ? $filter['nik'] : "";
        $lokasi = isset($filter['lokasi']) ? $filter['lokasi'] : "";

        if (isset($nik)) {
            $check_nik = $this->dessgeneral->get_karyawan($nik);
            if (!isset($check_nik)) {
                $return = array('sts' => 'NotOK', 'msg' => 'NIK tidak ditemukan');
                echo json_encode($return);
                return;
            }
        }

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
            case "sync_medical":
                $ho = '';
                if ($lokasi == 'ho')
                    $ho = 'X';

                $result = $sap->callFunction("Z_GET_MEDICAL_INFORMATION",
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

                        $cutoff = $this->dmedical->get_fbk_cutoff(
                            array(
                                'tahun' => date('Y'),
                                'single_row' => true
                            )
                        );

                        foreach ($result['T_CLAIMS'] as $data_claim) {
                            if (!isset($plafons[$data_claim['PERNR']])) {
                                $plafons[$data_claim['PERNR']] = $this->dmedical->get_plafon(array(
                                    "nik" => $data_claim['PERNR'],
                                    "single_row" => true
                                ));
                                foreach ($plafons[$data_claim['PERNR']] as $key => $plafon) {
                                    if (array_key_exists($key, $plafonUpdate[$data_claim['PERNR']])) {
                                        if ($plafonUpdate[$data_claim['PERNR']][$key] != $plafon)
                                            $plafon = intval($plafonUpdate[$data_claim['PERNR']][$key]);
                                    }
                                    $plafons[$data_claim['PERNR']]->$key = $plafon;
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
                                    $jadwal = date_create($cutoff->jadwal);
                                    $tanggal_kwitansi = date('Y-m-d', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
                                    if (
                                        isset($cutoff) &&
                                        date_create($cutoff->jadwal) <= date_create($check->tanggal_buat) &&
                                        $tanggal_kwitansi->format('Y-m-d') == $gen->format_nilai('SAP2SQLDATE', $data_claim['BILDT'])
                                    )
                                        $cek_kwitansi = $check;
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

                                    if ($sisaPlafon > 0) {
//                                        /** Cek kondisi plafon, ketika plafon yang akan diupdate sudah sama atau tidak
//                                         * jika sama berarti akan plafon yang sekarang ada di DB akan ditambah nilai claim
//                                         */
//
//                                        if (isset($sisaPlafonUpdate) and $plafonOri->$kolomPlafon < $sisaPlafonUpdate) {
//                                            $sisaPlafon += $gen->format_nilai('SAPSQL', $data_claim['CLAMT']);
//                                        } else {
//                                            $sisaPlafon -= $gen->format_nilai('SAPSQL', $data_claim['CLAMT']);
//                                        }
//
//                                        var_dump($sisaPlafon,$data_claim['CLAMT']);
                                        $sisaPlafon += $gen->format_nilai('SAPSQL', $data_claim['CLAMT']);
                                    }

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

                $return = array('sts' => $sts, 'msg' => $msg);
                break;
            case "validasi_benefit":
                $nik = base64_decode($this->session->userdata('-nik-'));
                $bplan = isset($filter['bplan']) ? $filter['bplan'] : "BBNR";

                if (in_array($bplan, array('BBNR', 'BBCS'))) {
                    $result = $sap->callFunction("Z_GET_VALIDASI_BENEFIT",
                        array(
                            array("IMPORT", "I_PERNR", $nik),
                            array("IMPORT", "I_BEGDA", date_create()->format('Ymd')),
                            array("IMPORT", "I_BPLAN", $bplan),
                            array("TABLE", "T_RETURN", array())
                        )
                    );

                    if ($sap->getStatus() == SAPRFC_OK) {
                        if (isset($result['T_RETURN']) and is_array($result['T_RETURN'])) {
                            $msg = "";
                            $sts = 'OK';
                            foreach ($result['T_RETURN'] as $item) {
                                if ($item['TYPE'] == 'E') {
                                    $msg = $item['MESSAGE_V1'];
                                    $sts = 'NotOK';
                                }
                            }
                        } else {
                            $msg = "Koneksi SAP gagal, ulangi proses lagi.";
                            $sts = "NotOK";
                        }

                        $return = array('sts' => $sts, 'msg' => $msg);
                    } else {
                        $msg = "Koneksi SAP gagal, ulangi proses lagi.";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                    }
                } else {
                    $msg = null;
                    $sts = "OK";

                    $return = array('sts' => $sts, 'msg' => $msg);
                }
                $sap->logoff();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function laporan()
    {
        $this->general->check_access();
        $this->data['title'] = "Laporan FBK";

        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));
//            $tanggal_awal = date_create_from_format('d.m.Y', $filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date('Y-m-d', strtotime($filter['tanggal_akhir']));
//            $tanggal_akhir = date_create_from_format('d.m.Y', $filter['tanggal_akhir'])->format('Y-m-d');

        if (isset($filter['id_fbk_status'])) {
            if ($filter['id_fbk_status'] == 'Semua')
                $id_fbk_status = null;
            else
                $id_fbk_status = $filter['id_fbk_status'];
        } else {
            $id_fbk_status = null;
            $filter['id_fbk_status'] = 'Semua';
        }

        if (isset($filter['jenis'])) {
            if ($filter['jenis'] == 'Semua')
                $jenis = null;
            else
                $jenis = $filter['jenis'];
        } else {
            $jenis = null;
            $filter['jenis'] = 'Semua';
        }

        $this->general->connectDbPortal();

        $list_fbk = $this->dmedical->get_fbk(array(
            'id_fbk_status' => $id_fbk_status,
            'jenis' => $jenis,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'encrypted' => true,
            'kwitansi' => true
        ));

        $fbk_status = $this->dessgeneral->get_fbk_status();

        $this->general->closeDb();

        $this->data['fbk_status'] = $fbk_status;
        $this->data['list_fbk'] = $list_fbk;
        $this->data['filter'] = $filter;
        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;

        $this->load->view('medical/laporan_medical', $this->data);
    }

    public function save($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'pengajuan':
                $return = $this->save_pengajuan($data);
                break;
            case 'lengkap':
                $return = $this->save_kelengkapan($data, true);
                break;
            case 'tidak-lengkap':
                $return = $this->save_kelengkapan($data, false);
                break;
            case 'cutoff':
                $return = $this->save_cutoff($data, false);
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

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Jakarta');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        if ($modul == 'sap') {
            switch ($param) {
                case "pabrik":
                    if ($managerUp)
                        $title = "Pabrik Mg Up";
                    else
                        $title = "Pabrik Kasie Down";

                    $list_proses = $this->dmedical->get_fbk(array(
                        'id_fbk_status' => ESS_MEDICAL_STATUS_LENGKAP,
                        'tanggal_awal' => $filter['tanggal_awal'],
                        'tanggal_akhir' => $filter['tanggal_akhir'],
                        'encrypted' => true,
                        'kwitansi' => true,
                        'ho' => 'n',
                        'manager' => $managerUp
                    ));
                    break;
                default:
                    $title = "HO";

                    $list_proses = $this->dmedical->get_fbk(array(
                        'id_fbk_status' => ESS_MEDICAL_STATUS_LENGKAP,
                        'tanggal_awal' => $filter['tanggal_awal'],
                        'tanggal_akhir' => $filter['tanggal_akhir'],
                        'encrypted' => true,
                        'kwitansi' => true,
                        'ho' => 'y'
                    ));
                    break;
            }

            $objPHPExcel = new PHPExcel();

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Kiranaku")
                ->setLastModifiedBy("Kiranaku")
                ->setTitle("Export Medical ($title) to SAP (" . date('d-m-Y') . ")")
                ->setSubject("Export Medical ($title) to SAP (" . date('d-m-Y') . ")")
                ->setDescription("Export Medical ($title) to SAP (" . date('d-m-Y') . ")")
                ->setCategory("EXPORT SAP Medical");

            // Add some data
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'NIK');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Claim Date');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Bill Date');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Claims');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Bill No');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Claim Amount');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Patient');

            $baris = 1;
            $cutoff = $this->dmedical->get_fbk_cutoff(
                array(
                    'tahun' => date('Y'),
                    'single_row' => true
                )
            );
            foreach ($list_proses as $data) {
                foreach ($data->kwitansi_disetujui as $kwitansi) {
                    $baris++;
                    $tanggal = $gen->generateDateFormat($kwitansi->tanggal_buat);
                    if (
                        isset($cutoff) &&
                        date_create($cutoff->jadwal) <= date_create($kwitansi->tanggal_buat)
                    ) {
                        $jadwal = date_create($cutoff->jadwal);
                        $tanggal_kwitansi = date('Y-m-d', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
                        $tanggal_kwitansi = $gen->generateDateFormat($tanggal_kwitansi);
                    } else {
                        $tanggal_kwitansi = $gen->generateDateFormat($kwitansi->tanggal_kwitansi);
                    }
                    $nama = $data->nama_pasien == $data->nama_karyawan ? '' : $data->nama_pasien;
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $kwitansi->nik, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $tanggal, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, $tanggal_kwitansi, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $kwitansi->kode, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $baris, $kwitansi->nomor_kwitansi, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('F' . $baris, $kwitansi->amount_ganti, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('G' . $baris, $nama, PHPExcel_Cell_DataType::TYPE_STRING);
                }
            }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('FBK_SAP');


            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Export FBK ' . $title . ' to SAP (' . date('d-m-Y') . ').xls"');
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

    public function get($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'pengajuan':
                $return = $this->get_pengajuan($data);
                break;
            case 'history':
                $return = $this->get_history_pengajuan($data);
                break;
            case 'cutoff':
                $return = $this->get_cutoff($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function delete($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'pengajuan':
                $return = $this->delete_pengajuan($data);
                break;
            case 'cutoff':
                $return = $this->delete_cutoff($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    private function save_pengajuan($data)
    {

        $this->general->connectDbPortal();

        $nik = base64_decode($this->session->userdata('-nik-'));

        if (isset($data['id_fbk']) && !empty($data['id_fbk']))
            $id_fbk = $this->generate->kirana_decrypt($data['id_fbk']);
        else
            $id_fbk = null;

        unset($data['id_fbk']);

        $jenis = $data['fbk_jenis'];
		//lha
		$cutoff = $this->dmedical->get_fbk_cutoff(
		   array(
			   'tahun' => date('Y'),
			   'single_row' => true
		   )
		);

		if(isset($cutoff)&& $cutoff->jadwal<=date('Y-m-d H:i:s') ){
		   $jadwal = date_create(date('Y-m-d'));
		   $bulan_tahun = date('m/Y', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
		} else {
		   $bulan_tahun = date('m/Y');
		}
		
        // $bulan_tahun = date('m/Y');

        $last_fbk = $this->dmedical->get_fbk(
            array(
                'bulan_tahun' => $bulan_tahun,
                'order_by' => 'tbl_fbk.nomor DESC',
                'single_row' => true
            )
        );

        $last_id = 0;

        if (isset($last_fbk) && count($last_fbk) > 0) {
            $last_id_array = explode('/', $last_fbk->nomor);
            $last_id = (int)$last_id_array[0];
        }

        $count_fbk = $last_id + 1;

        $nomor = str_pad($count_fbk, 6, "0", STR_PAD_LEFT) . '/BNF/' . $bulan_tahun;
		// echo $nomor;
		// exit();

        $this->dgeneral->begin_transaction();
        $uploaded_gambar_kwitansi = array();
        $upload_error = array();

        if (isset($_FILES)) {

            $uploaddir = ESS_PATH_FILE . KIRANA_PATH_APPS_IMAGE_FOLDER . ESS_MEDICAL_UPLOAD_FOLDER;
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777, true);
            }

            $uploaded_gambar = null;

            $gambar_kwitansi = $_FILES['kwitansi'];

            $config['upload_path'] = $uploaddir;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size'] = 5000;
            $config['mod_mime_fix'] = false;

            $this->load->library('upload', $config);

            try {
                for ($i = 0; $i < count($gambar_kwitansi['name']); $i++) {
                    $_FILES['kwitansi_' . $i]['name'] = $nik . "_" . date('YmdHis') . "_" . $gambar_kwitansi['name'][$i]['lampiran'];
                    $_FILES['kwitansi_' . $i]['type'] = $gambar_kwitansi['type'][$i]['lampiran'];
                    $_FILES['kwitansi_' . $i]['tmp_name'] = $gambar_kwitansi['tmp_name'][$i]['lampiran'];
                    $_FILES['kwitansi_' . $i]['error'] = $gambar_kwitansi['error'][$i]['lampiran'];
                    $_FILES['kwitansi_' . $i]['size'] = $gambar_kwitansi['size'][$i]['lampiran'];

                    if ($_FILES['kwitansi_' . $i]['error'] != 0) {
                        switch ($_FILES['kwitansi_' . $i]['error']) {
                            case UPLOAD_ERR_INI_SIZE:
                                $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                break;
                            case UPLOAD_ERR_EXTENSION:
                                $upload_error[] = 'Lampiran ke ' . ($i + 1) . '.Jenis berkas yang Anda coba untuk mengunggah tidak diperbolehkan.';
                                break;
                        }
                    }

                    if ($_FILES['kwitansi_' . $i]['size'] > 0) {
                        $this->upload->initialize($config, true);
                        if ($this->upload->do_upload('kwitansi_' . $i)) {
                            $upload_data = $this->upload->data();

                            $uploaded_gambar_kwitansi[$i] = KIRANA_PATH_APPS_IMAGE_FOLDER .
                                ESS_MEDICAL_UPLOAD_FOLDER . $upload_data['file_name'];
                            $data['kwitansi'][$i]['gambar'] = KIRANA_PATH_APPS_IMAGE_FOLDER .
                                ESS_MEDICAL_UPLOAD_FOLDER . $upload_data['file_name'];
                        } else {
                            $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. ' . $this->upload->display_errors('', '');
                        }
                    }
                }
            } catch (Exception $e) {
                $upload_error[] = $e->getMessage();
            }
        }

        switch ($jenis) {
            case "jalan":
                $id_fbk_sakit = $data['id_fbk_sakit'];
                if ($id_fbk_sakit == 999)
                    $sakit = $data['sakit'];
                else {
                    $jenis_sakit = $this->dmedical->get_jenis_sakit(
                        array(
                            'single_row' => true,
                            'id' => $id_fbk_sakit,
                        )
                    );
                    $sakit = $jenis_sakit->nama;
                }

                if (!isset($id_fbk)) {
                    // $data_insert = $this->dgeneral->basic_column("insert", $data);
					if(isset($cutoff)&& $cutoff->jadwal<=date('Y-m-d H:i:s') ){
						$jadwal = date_create(date('Y-m-d'));
						$datetime = date('Y-m-d H:i:s', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
					}else{
						$datetime = date('Y-m-d H:i:s');
					}
					$data_insert = array_merge(
						$data,
						array(
							'login_buat'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat' => $datetime,
							'na'           => 'n',
							'del'          => 'n'
						)
					);
                    $kwitansi = $data_insert['kwitansi'];
                    unset($data_insert['kwitansi']);
                    $data_insert['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_insert['plafon_medical'] = $this->less->revert_rupiah($data_insert['plafon_medical']);
                    $data_insert['total_kwitansi'] = $this->less->revert_rupiah($data_insert['total_kwitansi']);
                    $data_insert['nomor'] = $nomor;
                    $data_insert['sakit'] = $sakit;
                    $data_insert['biaya_medical'] = $data_insert['total_kwitansi'];
                    $data_insert['total_biaya'] = $data_insert['total_kwitansi'];

                    $result = $this->dgeneral->insert('tbl_fbk', $data_insert);

                    $id_fbk = $this->db->insert_id();

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);

                } else {
                    $data_update = $this->dgeneral->basic_column("update", $data);
                    $kwitansi = $data_update['kwitansi'];
                    unset($data_update['kwitansi']);
                    $data_update['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_update['plafon_medical'] = $this->less->revert_rupiah($data_update['plafon_medical']);
                    $data_update['total_kwitansi'] = $this->less->revert_rupiah($data_update['total_kwitansi']);
                    $data_update['sakit'] = $sakit;
                    $data_update['biaya_medical'] = $data_update['total_kwitansi'];
                    $data_update['total_biaya'] = $data_update['total_kwitansi'];

                    $result = $this->dgeneral->update('tbl_fbk', $data_update, array(
                        array(
                            "kolom" => "id_fbk",
                            "value" => $id_fbk
                        )
                    ));

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);
                }
                break;
            case "inap":
                $id_fbk_sakit = $data['id_fbk_sakit'];
                if ($id_fbk_sakit == 999)
                    $sakit = $data['sakit'];
                else {
                    $jenis_sakit = $this->dmedical->get_jenis_sakit(
                        array(
                            'single_row' => true,
                            'id' => $id_fbk_sakit,
                        )
                    );
                    $sakit = $jenis_sakit->nama;
                }
                $id_rs = $data['id_rs'];
                if ($id_rs == 999)
                    $rs = $data['rs'];
                else {
                    $jenis_rs = $this->dmedical->get_rumah_sakit(
                        array(
                            'single_row' => true,
                            'id' => $id_rs,
                        )
                    );
                    $rs = $jenis_rs->nama;
                }

                if (!isset($id_fbk)) {
                    // $data_insert = $this->dgeneral->basic_column("insert", $data);
					if(isset($cutoff)&& $cutoff->jadwal<=date('Y-m-d H:i:s') ){
						$jadwal = date_create(date('Y-m-d'));
						$datetime = date('Y-m-d H:i:s', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
					}else{
						$datetime = $datetime;
					}
					$data_insert = array_merge(
						$data,
						array(
							'login_buat'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat' => $datetime,
							'na'           => 'n',
							'del'          => 'n'
						)
					);
					
                    $kwitansi = $data_insert['kwitansi'];
                    unset($data_insert['kwitansi']);
                    $data_insert['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_insert['biaya_kamar'] = $this->less->revert_rupiah($data_insert['biaya_kamar']);
                    $data_insert['plafon_kamar'] = $this->less->revert_rupiah($data_insert['plafon_kamar']);
                    $data_insert['total_kwitansi'] = $this->less->revert_rupiah($data_insert['total_kwitansi']);
                    $data_insert['nomor'] = $nomor;
                    $data_insert['sakit'] = $sakit;
                    $data_insert['rs'] = $rs;
                    $data_insert['biaya_medical'] = $data_insert['total_kwitansi'];
                    $data_insert['total_biaya'] = $data_insert['total_kwitansi'];

                    $result = $this->dgeneral->insert('tbl_fbk', $data_insert);

                    $id_fbk = $this->db->insert_id();

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);

                } else {
                    $data_update = $this->dgeneral->basic_column("update", $data);
                    $kwitansi = $data_update['kwitansi'];
                    unset($data_update['kwitansi']);
                    $data_update['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_update['biaya_kamar'] = $this->less->revert_rupiah($data_update['biaya_kamar']);
                    $data_update['plafon_kamar'] = $this->less->revert_rupiah($data_update['plafon_kamar']);
                    $data_update['total_kwitansi'] = $this->less->revert_rupiah($data_update['total_kwitansi']);
                    $data_update['sakit'] = $sakit;
                    $data_update['rs'] = $rs;
                    $data_update['biaya_medical'] = $data_update['total_kwitansi'];
                    $data_update['total_biaya'] = $data_update['total_kwitansi'];

                    $result = $this->dgeneral->update('tbl_fbk', $data_update, array(
                        array(
                            "kolom" => "id_fbk",
                            "value" => $id_fbk
                        )
                    ));

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);
                }
                break;
            case "bersalin":

                if (!isset($id_fbk)) {
                    // $data_insert = $this->dgeneral->basic_column("insert", $data);
					if(isset($cutoff)&& $cutoff->jadwal<=date('Y-m-d H:i:s') ){
						$jadwal = date_create(date('Y-m-d'));
						$datetime = date('Y-m-d H:i:s', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
					}else{
						$datetime = $datetime;
					}
					$data_insert = array_merge(
						$data,
						array(
							'login_buat'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat' => $datetime,
							'na'           => 'n',
							'del'          => 'n'
						)
					);
                    $kwitansi = $data_insert['kwitansi'];
                    unset($data_insert['kwitansi']);
                    $data_insert['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_insert['nomor'] = $nomor;
                    $data_insert['plafon_persalinan'] = $this->less->revert_rupiah($data_insert['plafon_persalinan']);
                    $data_insert['total_kwitansi'] = $this->less->revert_rupiah($data_insert['total_kwitansi']);
                    $data_insert['biaya_persalinan'] = $data_insert['total_kwitansi'];
                    $data_insert['total_biaya'] = $data_insert['total_kwitansi'];

                    $result = $this->dgeneral->insert('tbl_fbk', $data_insert);

                    $id_fbk = $this->db->insert_id();

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);

                } else {
                    $data_update = $this->dgeneral->basic_column("update", $data);
                    $kwitansi = $data_update['kwitansi'];
                    unset($data_update['kwitansi']);
                    $data_update['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_update['plafon_persalinan'] = $this->less->revert_rupiah($data_update['plafon_persalinan']);
                    $data_update['total_kwitansi'] = $this->less->revert_rupiah($data_update['total_kwitansi']);
                    $data_update['biaya_persalinan'] = $data_update['total_kwitansi'];
                    $data_update['total_biaya'] = $data_update['total_kwitansi'];

                    $result = $this->dgeneral->update('tbl_fbk', $data_update, array(
                        array(
                            "kolom" => "id_fbk",
                            "value" => $id_fbk
                        )
                    ));

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);
                }
                break;
            case "frame":

                if (!isset($id_fbk)) {
                    // $data_insert = $this->dgeneral->basic_column("insert", $data);
					if(isset($cutoff)&& $cutoff->jadwal<=date('Y-m-d H:i:s') ){
						$jadwal = date_create(date('Y-m-d'));
						$datetime = date('Y-m-d H:i:s', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
					}else{
						$datetime = $datetime;
					}
					$data_insert = array_merge(
						$data,
						array(
							'login_buat'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat' => $datetime,
							'na'           => 'n',
							'del'          => 'n'
						)
					);
					
                    $kwitansi = $data_insert['kwitansi'];
                    unset($data_insert['kwitansi']);
                    $data_insert['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_insert['nomor'] = $nomor;
                    $data_insert['plafon_frame'] = $this->less->revert_rupiah($data_insert['plafon_frame']);
                    $data_insert['total_kwitansi'] = $this->less->revert_rupiah($data_insert['total_kwitansi']);
                    $data_insert['biaya_frame'] = $data_insert['total_kwitansi'];
                    $data_insert['total_biaya'] = $data_insert['total_kwitansi'];

                    $result = $this->dgeneral->insert('tbl_fbk', $data_insert);

                    $id_fbk = $this->db->insert_id();

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);

                } else {
                    $data_update = $this->dgeneral->basic_column("update", $data);
                    $kwitansi = $data_update['kwitansi'];
                    unset($data_update['kwitansi']);
                    $data_update['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_update['plafon_frame'] = $this->less->revert_rupiah($data_update['plafon_frame']);
                    $data_update['total_kwitansi'] = $this->less->revert_rupiah($data_update['total_kwitansi']);
                    $data_update['biaya_frame'] = $data_update['total_kwitansi'];
                    $data_update['total_biaya'] = $data_update['total_kwitansi'];

                    $result = $this->dgeneral->update('tbl_fbk', $data_update, array(
                        array(
                            "kolom" => "id_fbk",
                            "value" => $id_fbk
                        )
                    ));

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);
                }
                break;
            case "lensa":

                if (!isset($id_fbk)) {
                    // $data_insert = $this->dgeneral->basic_column("insert", $data);
					if(isset($cutoff)&& $cutoff->jadwal<=date('Y-m-d H:i:s') ){
						$jadwal = date_create(date('Y-m-d'));
						$datetime = date('Y-m-d H:i:s', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
					}else{
						$datetime = $datetime;
					}
					$data_insert = array_merge(
						$data,
						array(
							'login_buat'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat' => $datetime,
							'na'           => 'n',
							'del'          => 'n'
						)
					);
					
                    $kwitansi = $data_insert['kwitansi'];
                    unset($data_insert['kwitansi']);
                    $data_insert['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_insert['nomor'] = $nomor;
                    $data_insert['plafon_lensa'] = $this->less->revert_rupiah($data_insert['plafon_lensa']);
                    $data_insert['total_kwitansi'] = $this->less->revert_rupiah($data_insert['total_kwitansi']);
                    $data_insert['biaya_lensa'] = $data_insert['total_kwitansi'];
                    $data_insert['total_biaya'] = $data_insert['total_kwitansi'];

                    $result = $this->dgeneral->insert('tbl_fbk', $data_insert);

                    $id_fbk = $this->db->insert_id();

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);

                } else {
                    $data_update = $this->dgeneral->basic_column("update", $data);
                    $kwitansi = $data_update['kwitansi'];
                    unset($data_update['kwitansi']);
                    $data_update['id_fbk_status'] = ESS_MEDICAL_STATUS_MENUNGGU;
                    $data_update['plafon_lensa'] = $this->less->revert_rupiah($data_update['plafon_lensa']);
                    $data_update['total_kwitansi'] = $this->less->revert_rupiah($data_update['total_kwitansi']);
                    $data_update['biaya_lensa'] = $data_update['total_kwitansi'];
                    $data_update['total_biaya'] = $data_update['total_kwitansi'];

                    $result = $this->dgeneral->update('tbl_fbk', $data_update, array(
                        array(
                            "kolom" => "id_fbk",
                            "value" => $id_fbk
                        )
                    ));

                    $this->save_pengajuan_kwitansi($kwitansi, $id_fbk, $nik, $data);
                }
                break;
        }

        $data_history = $this->dgeneral->basic_column('insert_simple', array(
            'id_fbk' => $id_fbk,
            'id_fbk_status' => ESS_MEDICAL_STATUS_MENUNGGU
        ));

        $this->dgeneral->insert('tbl_fbk_history', $data_history);

        $enId = null;

        if ($this->dgeneral->status_transaction() === FALSE || count($upload_error) > 0) {
            $this->dgeneral->rollback_transaction();

            foreach ($uploaded_gambar_kwitansi as $gambar_kwitansi) {
                unlink(ESS_PATH_FILE . $gambar_kwitansi);
            }
            if (count($upload_error) > 0)
                $msg = join('<br/>', $upload_error);
            else
                $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
            $enId = $this->generate->kirana_encrypt($id_fbk);
        }
        $this->general->closeDb();

        $return = array('sts' => $sts, 'msg' => $msg, 'redirect' => base_url('ess/medical/cetak/') . $enId);
        return $return;
    }

    private function save_pengajuan_kwitansi($kwitansi = array(), $id_fbk = null, $nik = null, $data)
    {
       $cutoff = $this->dmedical->get_fbk_cutoff(
           array(
               'tahun' => date('Y'),
               'single_row' => true
           )
       );

        foreach ($kwitansi as $index => $data_kwitansi) {
            $nomor = str_pad(($index + 1), 2, "0", STR_PAD_LEFT);

           if (
               isset($cutoff) &&
               date_create($cutoff->jadwal) <= date_create() &&
               empty($data_kwitansi['id_fbk_kwitansi'])
           ) {
               $jadwal = date_create($cutoff->jadwal);
               $data_kwitansi['tanggal'] = date('Y-m-d', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
           } else {
               $data_kwitansi['tanggal'] = $this->generate->regenerateDateFormat($data_kwitansi['tanggal']);
           }

            // $data_kwitansi['tanggal'] = $this->generate->regenerateDateFormat($data_kwitansi['tanggal']);

            if (isset($data_kwitansi['id_fbk_kwitansi']) && !empty($data_kwitansi['id_fbk_kwitansi'])) {
                $kwitansi_update = array(
                    'kode' => $data['kode'],
                    'nomor' => $nomor,
                    'tanggal_kwitansi' => $this->generate->regenerateDateFormat($data_kwitansi['tanggal']),
                    'nomor_kwitansi' => $data_kwitansi['nomor'],
                    'amount_kwitansi' => $this->less->revert_rupiah($data_kwitansi['nominal']),
                );

                if (isset($data_kwitansi['gambar']))
                    $kwitansi_update['gambar'] = $data_kwitansi['gambar'];

                $kwitansi_update = $this->dgeneral->basic_column('update', $kwitansi_update);

                $this->dgeneral->update('tbl_fbk_kwitansi', $kwitansi_update, array(
                    array(
                        "kolom" => "id_fbk_kwitansi",
                        "value" => $data_kwitansi['id_fbk_kwitansi']
                    )
                ));
            } else {
                $kwitansi_insert = array(
                    'nik' => $nik,
                    'kode' => $data['kode'],
                    'id_fbk' => $id_fbk,
                    'nomor' => $nomor,
                    'tanggal_kwitansi' => $this->generate->regenerateDateFormat($data_kwitansi['tanggal']),
                    'nomor_kwitansi' => $data_kwitansi['nomor'],
                    'amount_kwitansi' => $this->less->revert_rupiah($data_kwitansi['nominal']),
                );

                if (isset($data_kwitansi['gambar']))
                    $kwitansi_insert['gambar'] = $data_kwitansi['gambar'];

                // $kwitansi_insert = $this->dgeneral->basic_column('insert', $kwitansi_insert);
				if(isset($cutoff)&& $cutoff->jadwal<=date('Y-m-d H:i:s') ){
					$jadwal = date_create(date('Y-m-d'));
					$datetime = date('Y-m-d H:i:s', strtotime('01-01-' . $jadwal->modify('+1 year')->format('Y')));
				}else{
					$datetime = date('Y-m-d H:i:s');
				}
				$kwitansi_insert = array_merge(
					$kwitansi_insert,
					array(
						'login_buat'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat' => $datetime,
						'na'           => 'n',
						'del'          => 'n'
					)
				);


                $this->dgeneral->insert('tbl_fbk_kwitansi', $kwitansi_insert);
            }
        }
		
		
    }

    private function get_pengajuan($data)
    {
        if (isset($data['id']))
            $id = $this->generate->kirana_decrypt($data['id']);
        else
            $id = null;

        $this->general->connectDbPortal();

        $result = $this->dmedical->get_fbk(array(
            'id' => $id,
            'single_row' => true
        ));

        if (!empty($result->gambar)) {
            $data_image = site_url(
                'assets/file/ess/' .
                $result->gambar
            );

            $headers = get_headers($data_image);
            if ($headers[0] != "HTTP/1.1 200 OK") {
                $data_image = "http://kiranaku.kiranamegatara.com/home/" . $result->gambar;
                $headers = get_headers($data_image);

                if ($headers[0] == "HTTP/1.1 200 OK") {
                    $result->gambar = $data_image;
                } else
                    $result->gambar = null;
            } else
                $result->gambar = $data_image;
        }

        if (isset($result)) {
            $result->kwitansi = $this->dmedical->get_fbk_kwitansi(array(
                'id_fbk' => $result->id_fbk
            ));

            foreach ($result->kwitansi as $kwitansi) {
                if (!empty($kwitansi->gambar)) {
                    $data_image = site_url(
                        'assets/file/ess/' .
                        $kwitansi->gambar
                    );

                    $headers = get_headers($data_image);
                    if ($headers[0] != "HTTP/1.1 200 OK") {
                        $data_image = "http://kiranaku.kiranamegatara.com/assets/file/ess/" . $kwitansi->gambar;
                        $headers = get_headers($data_image);
                        if ($headers[0] == "HTTP/1.1 200 OK") {
                            $kwitansi->gambar = $data_image;
                        } else
                            $kwitansi->gambar = null;
                    } else
                        $kwitansi->gambar = $data_image;
                }
            }
            $result->sisa_plafon_akhir = $this->less->get_plafon_sisa(
                array(
                    'tanggal_akhir' => $result->tanggal_buat,
                    'tahun' => date('Y', strtotime($result->tanggal_buat)),
//                    'id_before' => $result->id_fbk,
                    'nik' => $result->nik,
                    'kode' => $result->kode
                )
            );

            $result->id_fbk = $this->generate->kirana_encrypt($result->id_fbk);
        }

        $this->general->closeDb();

        return array(
            'data' => $result
        );
    }

    private function delete_pengajuan($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);
            $deletable = true;

            $this->general->connectDbPortal();

            $fbk = $this->dmedical->get_fbk(array(
                'id' => $id,
                'single_row' => true
            ));

            $this->dgeneral->begin_transaction();

            if (!in_array($fbk->id_fbk_status, array(ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP)))
                $deletable = false;
            else {
                $data_row = $this->dgeneral->basic_column('delete');

                $this->dgeneral->update('tbl_fbk', $data_row,
                    array(
                        array(
                            'kolom' => 'id_fbk',
                            'value' => $id
                        )
                    )
                );

                $this->dgeneral->update('tbl_fbk_kwitansi', $data_row,
                    array(
                        array(
                            'kolom' => 'id_fbk',
                            'value' => $id
                        )
                    )
                );
            }

            if (!$deletable) {
                $this->dgeneral->rollback_transaction();
                $msg = "Data tidak bisa dihapus, data sudah diproses.";
                $sts = "NotOK";
            } else if ($this->dgeneral->status_transaction() === FALSE) {
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
        return $return;
    }

    private function get_history_pengajuan($data)
    {
        if (isset($data['id']))
            $id = $this->generate->kirana_decrypt($data['id']);
        else
            $id = null;
        $result = $this->dessgeneral->get_medical_history(array(
            'id_fbk' => $id
        ));

        foreach ($result as $i => $dt) {
            if ($dt->ho == 'y') {
                $bagian = (empty($dt->nama_departemen)) ? $dt->nama_divisi : $dt->nama_departemen;
            } else {
                $bagian = (empty($dt->nama_seksi)) ? $dt->nama_departemen : $dt->nama_seksi;
                $bagian = (empty($bagian)) ? $dt->nama_sub_divisi : $bagian;
                $bagian = (empty($bagian)) ? $dt->nama_pabrik : $bagian;
            }
            $dt->nama_bagian = $bagian;
            $result[$i] = $dt;
        }

        return $result;
    }

    private function save_kelengkapan($data, $lengkap = null)
    {

        if (isset($lengkap)) {
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $id_fbk = $this->generate->kirana_decrypt($data['id_fbk']);

            $id_fbk_status = ($lengkap) ? ESS_MEDICAL_STATUS_LENGKAP : ESS_MEDICAL_STATUS_TDK_LENGKAP;

            $data_row = array(
                'id_fbk_status' => $id_fbk_status,
                'catatan' => $data['catatan'],
                'total_ganti' => $this->less->revert_rupiah($data['total_ganti']),
            );

            $jumlah_disetujui = 0;

            foreach ($data['kwitansi'] as $kwitansi) {
                $disetujui = isset($kwitansi['disetujui']) && $lengkap ? $kwitansi['disetujui'] : 'n';
                if ($disetujui == 'y')
                    $jumlah_disetujui++;

                $data_kwitansi = $this->dgeneral->basic_column('update', array(
                    'nomor_kwitansi' => $kwitansi['nomor'],
                    'amount_ganti' => $this->less->revert_rupiah($kwitansi['amount_ganti']),
                    'disetujui' => $disetujui
                ));


                $this->dgeneral->update('tbl_fbk_kwitansi', $data_kwitansi,
                    array(
                        array(
                            'kolom' => 'id_fbk_kwitansi',
                            'value' => $kwitansi['id_fbk_kwitansi']
                        )
                    )
                );
            }

            $this->dgeneral->update('tbl_fbk', $data_row,
                array(
                    array(
                        'kolom' => 'id_fbk',
                        'value' => $id_fbk
                    )
                )
            );

            $data_history = $this->dgeneral->basic_column('insert_simple', array(
                'id_fbk' => $id_fbk,
                'id_fbk_status' => $id_fbk_status
            ));

            $this->dgeneral->insert('tbl_fbk_history', $data_history);

            if ($this->dgeneral->status_transaction() === FALSE || ($jumlah_disetujui == 0 && $lengkap)) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else if ($jumlah_disetujui == 0 && $lengkap) {
                $sts = "NotOK";
                $msg = "Tidak ada kwitansi yang akan di setujui.";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil disimpan";
                $sts = "OK";
            }

            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di lengkapi.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    /**
     * Converts raw POST data into an array.
     *
     * Does not work for hierarchical POST data.
     *
     * @return array
     */
    function getRealPOST()
    {
        $rest_json = file_get_contents("php://input");
        $_POST = json_decode($rest_json, true);
        return $_POST;
    }

    private function save_cutoff($data, $false)
    {
        $this->general->connectDbPortal();

        if (isset($data['id_fbk_cutoff']) && !empty($data['id_fbk_cutoff']))
            $id_fbk_cutoff = $this->generate->kirana_decrypt($data['id_fbk_cutoff']);
        else
            $id_fbk_cutoff = null;

        $cekCutoff = $this->dmedical->get_fbk_cutoff(
            array(
                'tahun' => $data['tahun'],
                'single_row' => true
            )
        );

        if (isset($cekCutoff) && $id_fbk_cutoff != $cekCutoff->id_fbk_cutoff) {
            $msg = "Tahun yang dipilih sudah ada di database.";
            $sts = "NotOK";
        } else {
            $data_row = array(
                'tahun' => $data['tahun'],
                'jadwal' => date('Y-m-d H:i', strtotime($data['tanggal_cutoff'] . ' ' . $data['jam_cutoff'])),
//                'jadwal' => date_create_from_format('d.m.Y H:i', $data['tanggal_cutoff'] . ' ' . $data['jam_cutoff'])->format('Y-m-d H:i'),
                'catatan' => $data['catatan']
            );

            $this->dgeneral->begin_transaction();

            if (!isset($id_fbk_cutoff)) {
                $data_row = $this->dgeneral->basic_column('insert', $data_row);

                $result = $this->dgeneral->insert('tbl_fbk_cutoff', $data_row);

            } else {
                $data_row = $this->dgeneral->basic_column('update', $data_row);

                $result = $this->dgeneral->update('tbl_fbk_cutoff', $data_row, array(
                    array(
                        'kolom' => 'id_fbk_cutoff',
                        'value' => $id_fbk_cutoff
                    )
                ));
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
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function get_cutoff($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmedical->get_fbk_cutoff(array(
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

    private function delete_cutoff($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update('tbl_fbk_cutoff', $data_row,
                array(
                    array(
                        'kolom' => 'id_fbk_cutoff',
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
        return $return;
    }

    public function test()
    {
        $this->general->connectDbDefault();
        $q = $this->db->query("EXEC dbo.SP_Kiranalytics_PUR0054_TEST 1, 'DEPO SAMBAS,DEPO SAMPIT', '201705', '201805'");

        var_dump($q->result());
    }
}