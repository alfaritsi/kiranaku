<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Travel - SPD - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
include_once APPPATH . "modules/travel/controllers/BaseControllers.php";

class Spd extends BaseControllers
{
    // private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Travel";
        $this->data['user'] = $this->general->get_data_user();

        $this->load->model('dspd');
        $this->load->library('lspd');
    }

    public function expenses_um()
    {
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Master";
        $this->data['title'] = "Master Tipe Expense Uang muka";
        $this->data['title_form'] = "Tambah Tipe Expense Uang muka";
        $this->data['activities'] = $this->dspd->get_jenis_aktifitas();
        $this->data['destinations'] = $this->dspd->get_travel_pabrik();
        $this->data['kode_expenses'] = $this->dspd->get_travel_tipeexpenses_kode();
        $this->data['costcenter'] = $this->dspd->get_travel_costcenter();
        $this->data['list'] = $this->dspd->get_travel_costcenter_expenses();

        $this->load->view('master/costcenter_expenses', $this->data);
    }

    public function expenses_declare()
    {
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Master";
        $this->data['title'] = "Master Tipe Expense Deklarasi";
        $this->data['title_form'] = "Tambah Tipe Expense Deklarasi";
        $this->data['activities'] = $this->dspd->get_jenis_aktifitas();
        $this->data['destinations'] = $this->dspd->get_travel_pabrik();
        $this->data['kode_expenses'] = $this->dspd->get_travel_tipeexpenses_kode();
        $this->data['costcenter'] = $this->dspd->get_travel_costcenter();
        $this->data['list'] = $this->dspd->get_travel_costcenter_declare();

        $this->load->view('master/costcenter_declare', $this->data);
    }

    public function pengajuan()
    {
        $this->general->check_access();
        $filter = $this->input->post();
        /** Filter Tanggal */
        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Pengajuan SPD";
        $this->data['title'] = "Pengajuan Perjalanan Dinas";
        $this->data['countries'] = $this->dspd->get_countries();
        $this->data['transports'] = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();
        $this->data['tanggal_travels'] = $this->lspd->get_all_travel_dates(
            array(
                'user' => $this->data['user']
            )
        );

        $listSpd = $this->dspd->get_travel_header(array(
            'nik' => $this->data['user']->nik,
            'status' => array(
                TR_STATUS_MENUNGGU,
                TR_STATUS_DISETUJUI,
                TR_STATUS_REVISI,
            ),
            'non_cancel' => true,
            'status_transportasi' => 0
        ));

        $listCancelSpd = $this->dspd->get_travel_cancel(array(
            'nik' => $this->data['user']->nik,
            'status' => array(
                TR_STATUS_MENUNGGU,
                TR_STATUS_DISETUJUI,
                TR_STATUS_REVISI,
            ),
        ));

        $listHistorySpd = $this->dspd->get_travel_header(array(
            'nik' => $this->data['user']->nik,
            'level' => array(
                TR_LEVEL_FINISH,
                TR_STATUS_MENUNGGU
            )
        ));

        /** Reproses list array SPD */
        $listSpd = $this->lspd->proses_spd_list($listSpd);
        $listCancelSpd = $this->lspd->proses_spd_list($listCancelSpd);
        $listHistorySpd = $this->lspd->proses_spd_list($listHistorySpd);

        $this->data['list'] = $listSpd;
        $this->data['list_cancel'] = $listCancelSpd;
        $this->data['list_history'] = $listHistorySpd;
        $this->data['approval'] = $this->dspd->get_approval();

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;

        $this->data['modal_chat'] = $this->lspd->load_spd_chat();
        $this->data['modal_detail'] = $this->lspd->load_spd_detail();
        $this->data['modal_tujuan'] = $this->lspd->load_spd_tujuan();
        $this->data['modal_history'] = $this->lspd->load_spd_history();

        $this->load->view('spd/pengajuan_spd', $this->data);
    }

    //LHA
    public function add_deklarasi_old($param)
    {
        // $this->general->check_access();
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Pengajuan SPD";
        $this->data['title'] = "Pengajuan Perjalanan Dinas";
        $this->data['id_travel_header'] = str_replace("xyz", "=", $param);
        $this->load->view('spd/add_deklarasi', $this->data);
    }

    public function add_deklarasi($id)
    {
        $this->data['module'] = "Travel - Deklarasi SPD";
        $this->data['title'] = "Deklarasi Perjalanan Dinas";
        $this->data['id_header'] = $id;

        $this->general->check_session();
        $filter = $this->input->post();
        /** Filter Tanggal */
        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $this->general->connectDbPortal();
        /** Cek pengajuan deklarasi */
        $deklarasi = $this->dspd->get_travel_deklarasi_header(
            array(
                'id_travel_header' => $id,
                'single' => true
            )
        );

        if (!$id || $deklarasi) {
            show_404();
        }

        $this->data['countries'] = $this->dspd->get_countries();
        $this->data['transports'] = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();
        $this->data['tanggal_travels'] = $this->lspd->get_all_travel_dates(
            array(
                'user' => $this->data['user']
            )
        );

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;
        $this->data['id_header'] = $this->generate->kirana_encrypt($id);

        $this->load->view('spd/add_deklarasi', $this->data);
    }

    public function app_deklarasi($param, $param2 = null)
    {
        // $this->general->check_access();
        $this->general->check_session();
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Pengajuan SPD";
        $this->data['title'] = "Pengajuan Perjalanan Dinas";
        $this->data['id_travel_header'] = str_replace("xyz", "=", $param);
        $this->data['is_approval_by'] = (isset($param2)) ? $param2 : 0;

        $this->load->view('spd/app_deklarasi', $this->data);
    }

    public function detail_deklarasi_old($param)
    {
        // $this->general->check_access();
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Detail Deklarasi";
        $this->data['title'] = "Detail Deklarasi";
        $this->data['id_travel_header'] = str_replace("xyz", "=", $param);
        $this->load->view('spd/detail_deklarasi', $this->data);
    }

    public function cancel_pengajuan($param, $param2 = null)
    {
        $this->general->check_session();
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Pengajuan SPD";
        $this->data['title'] = "Pengajuan Perjalanan Dinas";
        $this->data['id_travel_header'] = str_replace("xyz", "=", $param);

        $this->load->view('spd/cancel_pengajuan', $this->data);
    }

    public function add()
    {
        $this->general->check_session();
        $filter = $this->input->post();
        /** Filter Tanggal */
        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Pengajuan SPD";
        $this->data['title'] = "Pengajuan Perjalanan Dinas";
        $this->data['countries'] = $this->dspd->get_countries();
        $this->data['transports'] = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();
        $this->data['tanggal_travels'] = $this->lspd->get_all_travel_dates(
            array(
                'user' => $this->data['user']
            )
        );

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;

        $this->load->view('spd/add', $this->data);
    }

    public function detail($id)
    {
        $this->general->check_session();
        $this->data['module'] = "Travel - Pengajuan SPD";
        $this->data['title'] = "Pengajuan Perjalanan Dinas";

        $cancel = $this->dspd->cek_approval_cancel(array(
            'id_travel_header' => $id
        ));
        $deklarasi = $this->dspd->cek_approval_deklarasi(array(
            'id_travel_header' => $id
        ));

        if ($cancel) {
            $listSpd = $this->dspd->get_approval_list(array(
                'nik' => $this->data['user']->nik,
                'mode' => 'pembatalan',
                'id_travel_header' => $id
            ));
        } else if ($deklarasi) {
            $listSpd = $this->dspd->get_approval_list(array(
                'nik' => $this->data['user']->nik,
                'mode' => 'deklarasi',
                'id_travel_header' => $id
            ));
        } else {
            $listSpd = $this->dspd->get_approval_list(array(
                'nik' => $this->data['user']->nik,
                'mode' => 'pengajuan',
                'id_travel_header' => $id
            ));
        }

        $listSpd = $this->lspd->proses_spd_list($listSpd, 'persetujuan');

        $data_header = $this->dspd->get_travel_header(
            array(
                'id' => $id,
                'single' => true
            )
        );

        if (!$id || empty($data_header)) {
            show_404();
        }

        $this->data['flagApproval'] = empty($listSpd) ? ''  : $listSpd[0]->flagApproval;
        $this->data['flagApprovalBy'] = empty($listSpd) ? '' : $listSpd[0]->flagApprovalBy;
        $this->data['id_header'] = $this->generate->kirana_encrypt($id);
        $this->data['pengajuan'] = $data_header;
        $this->data['cancel'] = $cancel;
        $this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();
        $this->data['tanggal_travels'] = $this->lspd->get_all_travel_dates(
            array(
                'user' => $this->data['user']
            )
        );

        $list_transport = array();
        $list_penginapan = array();

        if ($data_header->no_trip) {
            $list_transport = $this->dspd->get_trans_book(array(
                'id_travel_header' => $id,
                // 'status_tiket_primary' => 'primary',

            ));

            $list_penginapan = $this->dspd->get_hotel_book(array(
                'id_travel_header' => $id,
                // 'status_tiket_primary' => 'primary',
            ));
        }

        $this->data['list_transport'] = $list_transport;
        $this->data['list_penginapan'] = $list_penginapan;
        $this->data['deklarasi'] = $deklarasi;

        $this->load->view('spd/detail', $this->data);
    }

    public function edit($id)
    {
        $this->data['module'] = "Travel - Pengajuan SPD";
        $this->data['title'] = "Pengajuan Perjalanan Dinas";
        $this->data['id_header'] = $id;

        $this->general->check_session();
        $filter = $this->input->post();
        /** Filter Tanggal */
        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $this->general->connectDbPortal();
        $data_header = $this->dspd->get_travel_header(
            array(
                'id' => $id,
                'single' => true
            )
        );

        if (
            !$id ||
            empty($data_header) ||
            ($data_header->approval_level > TR_LEVEL_1
                &&
                !in_array($data_header->approval_status, array(TR_STATUS_MENUNGGU, TR_STATUS_REVISI))
            )
        ) {
            show_404();
        }

        $this->data['countries'] = $this->dspd->get_countries();
        $this->data['transports'] = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();
        $this->data['tanggal_travels'] = $this->lspd->get_all_travel_dates_edit(
            array(
                'user' => $this->data['user'],
                'id_travel_header' => $id,
            )
        );

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;
        $this->data['id_header'] = $this->generate->kirana_encrypt($id);

        $this->load->view('spd/edit', $this->data);
    }

    public function init_data_form()
    {
        $this->general->connectDbPortal();
        $data['countries']        = $this->dspd->get_countries();
        $data['transports']       = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $data['jenis_aktifitas']  = $this->dspd->get_jenis_aktifitas();
        $data['tanggal_travels']  = $this->lspd->get_all_travel_dates(
            array(
                'user' => $this->data['user']
            )
        );

        echo json_encode($data);
    }

    public function persetujuan()
    {
        $this->general->check_access();
        $filter = $this->input->post();
        /** Filter Tanggal */
        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Persetujuan SPD";
        $this->data['title'] = "Persetujuan Perjalanan Dinas";
        $this->data['countries'] = $this->dspd->get_countries();
        $this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();

        $this->data['lv_role'] = $this->dspd->get_lv_role(
            array(
                'nik'         => base64_decode($this->session->userdata('-nik-')),
                'single'     => true
            )
        );
        if (isset($this->data['lv_role']) and ($this->data['lv_role']->level_role == 4)) {
            $lv_role = $this->data['lv_role']->level_role;
        } else {
            $lv_role = null;
        }
        $listSpd = $this->dspd->get_approval_list(array(
            'nik' => $this->data['user']->nik,
            'mode' => 'pengajuan',
            'lv_role' => $lv_role
        ));


        $listSpdCancel = $this->dspd->get_approval_list(array(
            'nik' => $this->data['user']->nik,
            'mode' => 'pembatalan',
            'lv_role' => $lv_role
        ));

        $listSpdDeklarasi = $this->dspd->get_approval_list(array(
            'nik' => $this->data['user']->nik,
            'mode' => 'deklarasi',
            'lv_role' => $lv_role
        ));
        $listSpdHistory = $this->dspd->get_travel_approval_history(array(
            'action_by' => $this->data['user']->nik,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'level' => array(
                TR_LEVEL_FINISH
            ),
            'lv_role' => $lv_role
        ));

        /** Reproses list array SPD */
        $listSpd = $this->lspd->proses_spd_list($listSpd, 'persetujuan');

        $listSpdCancel = $this->lspd->proses_spd_list($listSpdCancel, 'persetujuan');

        $listSpdDeklarasi = $this->lspd->proses_spd_list($listSpdDeklarasi, 'persetujuan_deklarasi');

        $listHistory = $this->lspd->proses_spd_list($listSpdHistory, 'history-approval');

        $this->data['list'] = $listSpd;
        $this->data['list_cancel'] = $listSpdCancel;
        $this->data['list_deklarasi'] = $listSpdDeklarasi;
        $this->data['list_history'] = $listHistory;

        $this->data['modal_detail'] = $this->lspd->load_spd_detail();
        $this->data['modal_tujuan'] = $this->lspd->load_spd_tujuan();
        $this->data['modal_history'] = $this->lspd->load_spd_history();

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;

        $this->load->view('spd/persetujuan_spd', $this->data);
    }

    public function deklarasi()
    {
        $this->general->check_access();
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Deklarasi SPD";
        $this->data['title'] = "Deklarasi Perjalanan Dinas";

        $listDeklarasi = $this->dspd->get_travel_deklarasi_ready(array(
            'nik' => $this->data['user']->nik
            // ,'status_transportasi' => 1
        ));
        $listHistory = $this->dspd->get_travel_deklarasi_header(array(
            'nik' => $this->data['user']->nik,
            'status' => array(
                TR_STATUS_SELESAI,
                TR_STATUS_DIBATALKAN,
            ),
            'level' => array(
                TR_LEVEL_FINISH
            )
        ));

        /** Reproses list array SPD */
        $listDeklarasi = $this->lspd->proses_spd_list($listDeklarasi, 'deklarasi');
        $listHistory = $this->lspd->proses_spd_list($listHistory, 'history');

        $this->data['list'] = $listDeklarasi;
        $this->data['list_history'] = $listHistory;
        $this->data['approval'] = $this->dspd->get_approval();
        $this->data['modal_detail'] = $this->lspd->load_spd_detail();
        $this->data['modal_tujuan'] = $this->lspd->load_spd_tujuan();
        $this->data['modal_history'] = $this->lspd->load_spd_history();

        $this->load->view('spd/deklarasi_spd', $this->data);
    }

    public function detail_deklarasi($id)
    {
        $this->data['module'] = "Travel - Detail Deklarasi SPD";
        $this->data['title'] = "Deklarasi Perjalanan Dinas";
        $this->data['id_header'] = $id;

        $this->general->check_session();
        $filter = $this->input->post();
        /** Filter Tanggal */
        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $this->general->connectDbPortal();
        /** Cek pengajuan deklarasi */
        $deklarasi = $this->dspd->get_travel_deklarasi_header(
            array(
                'id_travel_header' => $id,
                'single' => true
            )
        );

        if (!$id || empty($deklarasi)) {
            show_404();
        }

        $this->data['countries'] = $this->dspd->get_countries();
        $this->data['transports'] = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();
        $this->data['tanggal_travels'] = $this->lspd->get_all_travel_dates(
            array(
                'user' => $this->data['user']
            )
        );

        $listSpd = $this->dspd->get_approval_list(array(
            'nik' => $this->data['user']->nik,
            'mode' => 'deklarasi',
            'id_travel_header' => $id
        ));
        $listSpd = $this->lspd->proses_spd_list($listSpd, 'persetujuan');
        $data_header = $this->dspd->get_travel_header(
            array(
                'id' => $id,
                'single' => true
            )
        );

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;
        $this->data['flagApproval'] = empty($listSpd) ? ''  : $listSpd[0]->flagApproval;
        $this->data['flagApprovalBy'] = empty($listSpd) ? '' : $listSpd[0]->flagApprovalBy;
        $this->data['is_approval_by'] = (isset($param2)) ? $param2 : 0;
        $this->data['id_header'] = $this->generate->kirana_encrypt($id);
        $this->data['pengajuan'] = $data_header;
        $this->data['pengajuan_deklarasi'] = $deklarasi;
        $this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();
        $this->data['personel'] = $this->dspd->get_karyawan(array('nik' => $deklarasi->nik, 'single' => true));

        // echo json_encode($this->data);
        // exit();
        $this->load->view('spd/detail_deklarasi', $this->data);
    }

    public function edit_deklarasi($id)
    {
        $this->data['module'] = "Travel - Edit Deklarasi SPD";
        $this->data['title'] = "Deklarasi Perjalanan Dinas";
        $this->data['id_header'] = $id;

        $this->general->check_session();
        $filter = $this->input->post();
        /** Filter Tanggal */
        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        $this->general->connectDbPortal();
        /** Cek pengajuan deklarasi */
        $deklarasi = $this->dspd->get_travel_deklarasi_header(
            array(
                'id_travel_header' => $id,
                'single' => true
            )
        );

        if (
            !$id ||
            empty($deklarasi) ||
            ($deklarasi->approval_level > TR_LEVEL_1
                &&
                !in_array($deklarasi->approval_status, array(TR_STATUS_MENUNGGU, TR_STATUS_REVISI, TR_STATUS_DITOLAK))
            )
        ) {
            show_404();
        }

        $this->data['countries'] = $this->dspd->get_countries();
        $this->data['transports'] = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();
        $this->data['tanggal_travels'] = $this->lspd->get_all_travel_dates(
            array(
                'user' => $this->data['user']
            )
        );

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;
        $this->data['id_header'] = $this->generate->kirana_encrypt($id);

        $this->load->view('spd/edit_deklarasi', $this->data);
    }

    public function cetak($param = NULL, $key = NULL)
    {
        $this->general->check_session();
        $this->data['module'] = "Travel - Pengajuan SPD";
        $this->data['title'] = "Pengajuan Perjalanan Dinas";

        switch ($param) {
            case 'pengajuan':
                $this->cetak_pengajuan($key);
                break;
            case 'deklarasi':
                $this->cetak_deklarasi($key);
                break;
            default:
                show_404();
                break;
        }
    }

    //==================================================//
    /*                    Get data                      */
    //==================================================//
    public function get($param = null)
    {
        $inputs = $this->input->post();
        switch ($param) {
            case 'personel':
                $result = $this->get_personel();
                break;
            case 'atasan':
                $result = $this->dspd->get_approval();
                break;
            case 'expenses':
                $result = $this->get_expenses($inputs);
                break;
            case 'tujuan':
                $inputs = $this->input->post();
                $result = $this->get_tujuan($inputs);
                break;
            case 'expenses_value':
                $result = $this->get_expenses_value($inputs);
                break;
            case 'pengajuan':
                $inputs = $this->input->post();
                $idHeader = $this->generate->kirana_decrypt($inputs['id']);
                $result['data'] = $this->get_data_pengajuan(array(
                    'id_header' => $idHeader,
                    'personal_area' => @$inputs['personal_area'],
                    'activity' => @$inputs['activity'],
                ));
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'pengajuan_deklarasi':
                $inputs = $this->input->post();
                $idHeader = $this->generate->kirana_decrypt($inputs['id']);

                $result['data'] = $this->get_data_deklarasi(array(
                    'id_header' => $idHeader,
                    'personal_area' => @$inputs['personal_area'],
                    'activity' => @$inputs['activity'],
                ));
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'booking':
                $inputs = $this->input->post();
                $idHeader = $this->generate->kirana_decrypt($inputs['id']);
                $pengajuan = $this->dspd->get_travel_header(
                    array(
                        'id' => $idHeader,
                        'single' => true
                    )
                );
                $pengajuan = $this->lspd->proses_spd_list_header($pengajuan);

                $pengajuan = $this->general->generate_encrypt_json($pengajuan, array('id_travel_header'));

                $transports = $this->dspd->get_travel_transport(
                    array(
                        'id_header' => $idHeader,
                    )
                );
                $transports = $this->general->generate_encrypt_json(
                    $transports,
                    array(
                        'id_travel_header', 'id_travel_detail', 'id_travel_transport'
                    )
                );
                $hotels = $this->dspd->get_travel_hotel(
                    array(
                        'id_header' => $idHeader,
                    )
                );
                $hotels = $this->general->generate_encrypt_json(
                    $hotels,
                    array(
                        'id_travel_header', 'id_travel_detail', 'id_travel_hotel'
                    )
                );

                $details = $this->dspd->get_travel_detail(
                    array(
                        'id_header' => $idHeader,
                    )
                );
                $lspd = $this->lspd;
                $details = array_map(function ($detail) use ($lspd) {
                    return $lspd->proses_spd_list_detail($detail);
                }, $details);

                $details = $this->general->generate_encrypt_json($details, array('id_travel_detail', 'id_travel_header'));
                $transport_pesawat = $this->dspd->get_travel_merk_trans(array(
                    'transport' => "pesawat",
                ));
                $transport_taxi = $this->dspd->get_travel_merk_trans(array(
                    'transport' => "taxi",
                ));
                $personel = $this->dspd->get_karyawan(array('nik' => $pengajuan->nik, 'single' => true));
                $result['data'] = compact(
                    'pengajuan',
                    'transports',
                    'hotels',
                    'details',
                    'personel',
                    'transport_pesawat',
                    'transport_taxi'

                );
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'penerimaan':
                $inputs = $this->input->post();
                $idHeader = $this->generate->kirana_decrypt($inputs['id_header']);
                $idDetail = $this->generate->kirana_decrypt($inputs['id']);
                $pengajuan = $this->dspd->get_travel_header(
                    array(
                        'id' => $idHeader,
                        'single' => true
                    )
                );
                $pengajuan = $this->lspd->proses_spd_list_header($pengajuan);

                $pengajuan = $this->general->generate_encrypt_json($pengajuan, array('id_travel_header'));

                $detail = $this->dspd->get_travel_detail(
                    array(
                        'id' => $idDetail,
                        'single' => true
                    )
                );
                $detail = $this->lspd->proses_spd_list_detail($detail);
                $detail = $this->general->generate_encrypt_json($detail, array('id_travel_header', 'id_travel_detail'));

                $personel = $this->dspd->get_karyawan(array('nik' => $pengajuan->nik, 'single' => true));
                $result['data'] = compact(
                    'pengajuan',
                    'detail',
                    'personel'
                );
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'history':
                $inputs = $this->input->post();
                $idHeader = $this->generate->kirana_decrypt($inputs['id']);
                $deklarasi = $this->dspd->get_travel_deklarasi_header(array(
                    'id_travel_header' => $idHeader,
                    'single' => true
                ));
                if (isset($deklarasi)) {
                    $logs = $this->dspd->get_travel_deklarasi_log_status(
                        array(
                            'id_travel_header' => $idHeader,
                            'order_by' => 'tgl_status asc'
                        )
                    );
                } else {
                    $logs = $this->dspd->get_travel_log_status(
                        array(
                            'id_travel_header' => $idHeader,
                            'order_by' => 'tgl_status asc'
                        )
                    );
                }

                $result['data'] = $logs;
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'transport_master':
                $inputs = $this->input->post();
                $id = $this->generate->kirana_decrypt($inputs['id']);

                $transport = $this->dspd->get_travel_transport_master(array(
                    'id' => $id,
                    'single' => true
                ));

                $transport = $this->general->generate_encrypt_json($transport, array('id_travel_transport_master'));

                $result['data'] = $transport;
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'transport_options':
                $inputs = $this->input->post();
                $id = $this->generate->kirana_decrypt($inputs['id']);

                $transport = $this->dspd->get_travel_transport_options(array(
                    'id' => $id,
                    'single' => true
                ));

                $transport = $this->general->generate_encrypt_json($transport, array('id_travel_transport_options'));

                $result['data'] = $transport;
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'mess_options':
                $inputs = $this->input->post();
                $id = $this->generate->kirana_decrypt($inputs['id']);

                $mess = $this->dspd->get_travel_mess_options(array(
                    'id' => $id,
                    'single' => true
                ));

                $mess = $this->general->generate_encrypt_json($mess, array('id_travel_mess_option'));

                $result['data'] = $mess;
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'costcenter_expenses':
                $inputs = $this->input->post();
                $id = $this->generate->kirana_decrypt($inputs['id']);

                $data = $this->dspd->get_travel_costcenter_expenses(array(
                    'id' => $id,
                    'single' => true
                ));
                $data = $this->general->generate_encrypt_json($data, array('id_travel_costcenter_expense'));
                $result['data'] = $data;
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'costcenter_declare':
                $inputs = $this->input->post();
                $id = $this->generate->kirana_decrypt($inputs['id']);

                $data = $this->dspd->get_travel_costcenter_declare(array(
                    'id' => $id,
                    'single' => true
                ));
                $data = $this->general->generate_encrypt_json($data, array('id_travel_costcenter_declare'));
                $result['data'] = $data;
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'discuss':
                $inputs     = $this->input->post();
                $id         = $this->generate->kirana_decrypt($inputs['id']);

                $discusses  = $this->get_discusses(array(
                    'id' => $id
                ));
                $result['discusses'] = $discusses;
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'deklarasi':
                $idHeader = $this->generate->kirana_decrypt($inputs['id']);
                $pengajuan = $this->dspd->get_travel_header(
                    array(
                        'id' => $idHeader,
                        'single' => true
                    )
                );
                $pengajuan = $this->lspd->proses_spd_list_header($pengajuan, 'deklarasi');

                $pengajuan = $this->general->generate_encrypt_json($pengajuan, array('id_travel_header'));

                if ($pengajuan->tipe_trip === 'single') {
                    $details = $this->dspd->get_travel_detail(
                        array(
                            'id_header' => $idHeader,
                            'single' => true
                        )
                    );
                } else {
                    $details = $this->dspd->get_travel_detail(
                        array(
                            'id_header' => $idHeader,
                        )
                    );
                    $lspd = $this->lspd;
                    $details = array_map(function ($detail) use ($lspd) {
                        return $lspd->proses_spd_list_detail($detail);
                    }, $details);
                }
                $details = $this->general->generate_encrypt_json($details, array('id_travel_detail', 'id_travel_header'));
                $personel = $this->dspd->get_karyawan(array('nik' => $pengajuan->nik, 'single' => true));
                $downpayments = $this->dspd->get_travel_downpayment(
                    array(
                        'id_header' => $idHeader,
                    )
                );
                $deklarasi = $this->dspd->get_travel_deklarasi_header(array(
                    'id_travel_header' => $idHeader,
                    'single' => true,
                ));

                $deklarasi_details = array();
                if (isset($deklarasi)) {
                    $deklarasi_details = $this->dspd->get_travel_deklarasi_detail(array(
                        'id_travel_deklarasi_header' => $deklarasi->id_travel_deklarasi_header
                    ));
                    $deklarasi_details = $this->general->generate_encrypt_json($deklarasi_details, array('id_travel_deklarasi_header', 'id_travel_deklarasi_detail'));
                }
                $deklarasi_details = $this->general->generate_encrypt_json($deklarasi_details, array('id_travel_deklarasi_header', 'id_travel_header'));

                $expenses = $this->lspd->get_default_expenses(
                    array(
                        'id_header' => $inputs['id'],
                        'user' => $this->data['user'],
                        'country' => $pengajuan->country,
                        'jenis_aktifitas' => $pengajuan->activity,
                        'type' => 'declare'
                    )
                );
                foreach ($expenses as &$expens) {
                    $expens->value = floatval($expens->value) * 100;
                }

                $expenses_currency = $this->dspd->get_travel_tipeexpenses_currency();

                $result['data'] = compact(
                    'pengajuan',
                    'details',
                    'personel',
                    'downpayments',
                    'deklarasi',
                    'deklarasi_details',
                    'expenses',
                    'expenses_currency'
                );
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case 'vendor_transport':
                $result = $this->vendor_transport($inputs);
                break;
            case 'status_trip':
                $result = $this->get_status_trip(
                    array(
                        "connect" => TRUE,
                        "return" => 'array'
                    )
                );
                break;
            default:
                $result = array(
                    'sts' => 'NotOK',
                    'msg' => 'Data tidak ditemukan',
                );
                break;
        }

        echo json_encode($result);
    }

    //==================================================//
    /*                   Save data                      */
    //==================================================//
    public function save($param = null)
    {
        $inputs = $this->input->post();
        switch ($param) {
            case 'pengajuan':
                $this->save_pengajuan_spd($inputs);
                break;
            case 'pengajuans':
                $this->save_pengajuans_spd($inputs);
                break;
            case 'persetujuan':
                if ($inputs['approval_type'] == 'pengajuan')
                    $this->save_persetujuan_spd($inputs);
                else if ($inputs['approval_type'] == 'pembatalan')
                    $this->save_persetujuan_cancel_spd($inputs);
                else if ($inputs['approval_type'] == 'deklarasi') {
                    $this->save_persetujuan_deklarasi_spd($inputs);
                }
                break;
            case 'pembatalan':
                $this->save_pembatalan_spd($inputs);
                break;
            case 'booking':
                $this->save_booking_spd($inputs);
                break;
            case 'transport_options':
                $this->save_transport_options($inputs);
                break;
            case 'transport_master':
                $this->save_transport_master($inputs);
                break;
            case 'mess_options':
                $this->save_mess_options($inputs);
                break;
            case 'costcenter_expenses':
                $this->save_costcenter_expenses($inputs);
                break;
            case 'costcenter_declare':
                $this->save_costcenter_declare($inputs);
                break;
            case 'penerimaan':
                $this->save_penerimaan_spd($inputs);
                break;
            case 'diskusi':
                $this->save_diskusi_spd($inputs);
                break;
            case 'deklarasi':
                $this->save_deklarasi_spd($inputs);
                break;
            case 'deklarasi2':
                $this->save_deklarasi2_spd($inputs);
                break;
            case 'um_tambahan':
                $this->save_data_um_tambahan($inputs);
                break;
            default:
                echo json_encode(array(
                    'msg' => 'Forbidden',
                    'sts' => 'NotOK',
                ));
                break;
        }
    }

    //==================================================//
    /*                   Delete data                      */
    //==================================================//
    public function delete($param)
    {
        $data = $this->input->post();

        switch ($param) {
            case 'pengajuan':
                $return = $this->delete_pengajuan_spd($data);
                break;
            case 'pembatalan':
                $return = $this->delete_pembatalan_spd($data);
                break;
            case 'deklarasi':
                $return = $this->delete_deklarasi_spd($data);
                break;
            case 'mess_options':
                $return = $this->delete_mess_options($data);
                break;
            case 'costcenter_expenses':
                $return = $this->delete_costcenter_expenses($data);
                break;
            case 'costcenter_declare':
                $return = $this->delete_costcenter_declare($data);
                break;
            case 'transport_master':
                $return = $this->delete_transport_master($data);
                break;
            case 'transport_options':
                $return = $this->delete_transport_options($data);
                break;
            case 'cuti_pengganti':
                $return = $this->delete_cuti_pengganti($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    /**********************************/
    /*			  private  			  */
    /**********************************/
    private function save_pengajuans_spd($data = null)
    {
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();

        $sendEmail = false;

        $userData = $this->data['user'];
        try {
            if (isset($data)) {
                $persa = $userData->persa;
                $asal = $this->dspd->get_travel_tujuan(
                    array(
                        'company_code' => $userData->btrtl,
                        'single' => true
                    )
                );

                $id_travel_header = null;
                if (isset($data['id_travel_header']) && !empty($data['id_travel_header']))
                    $id_travel_header = $this->generate->kirana_decrypt($data['id_travel_header']);

                unset($data['id_travel_header']);

                if (isset($data['tipe_trip']) && !empty($data['tipe_trip'])) {
                    $tipe_trip = $data['tipe_trip'];

                    $approval = $this->dspd->get_approval();

                    $this->dgeneral->begin_transaction();
                    /** Multi Trip */
                    $trips = $data['detail'];
                    /** get first trip */
                    $firstTrip = reset($trips);

                    $multiData = array();

                    $multiData['tipe_travel'] = $firstTrip['country'] == 'ID' ? 'D' : 'O';
                    $multiData['jenis_tujuan'] = $firstTrip['country'] == 'ID' ? 'Domestik' : 'Luar Negeri';
                    $multiData['activity'] = $data['activity'];
                    $multiData['country'] = $firstTrip['country'];
                    $multiData['tujuan'] = $firstTrip['tujuan'];
                    $multiData['tujuan_persa'] = $firstTrip['tujuan_persa'];
                    $multiData['tujuan_lain'] = $firstTrip['tujuan_lain'];
                    $startDate = date_create($firstTrip['start']);
                    $multiData['start_date'] = $startDate->format('Y-m-d');
                    $multiData['start_time'] = $startDate->format('H:i:s');

                    if (isset($data['detail_end'])) {
                        $endDate = date_create($data['detail_end']);
                        $multiData['end_date'] = $endDate->format('Y-m-d');
                        $multiData['end_time'] = $endDate->format('H:i:s');
                    } else {
                        $multiData['end_date'] = null;
                        $multiData['end_time'] = null;
                    }

                    $diffDate = date_diff($startDate, $endDate);

                    if ($diffDate->days > 30) {
                        $msg = "Tanggal Kembali lebih dari 30 hari dari Tanggal Berangkat";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        echo json_encode($return);
                        exit();
                    }

                    $multiData['keperluan'] = $firstTrip['keperluan'];
                    if (!isset($id_travel_header)) {
                        $sendEmail = true;
                        /** Create new travel header & detail */
                        $id_travel_header = $this->lspd->generate_header_code($userData);
                        $insertHeaderData = array_merge(
                            $this->dgeneral->basic_column('insert_full'),
                            array(
                                'activity' => $data['activity'],
                                'approval_level' => 1,
                                'approval_status' => 0,
                                'approval_nik' => $approval['nik_atasan'],
                                'nik' => $userData->nik,
                                'persa' => $persa,
                                'no_hp' => $data['no_hp'],
                                'kota_asal' => $asal->kota,
                                'tipe_travel' => $multiData['tipe_travel'],
                                'tipe_trip' => $data['tipe_trip'],
                                'country' => $multiData['country'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $multiData['tujuan'],
                                'tujuan_persa' => $multiData['tujuan_persa'],
                                'tujuan_lain' => $multiData['tujuan_lain'],
                                'start_date' => $multiData['start_date'],
                                'start_time' => $multiData['start_time'],
                                'end_date' => $multiData['end_date'],
                                'end_time' => $multiData['end_time'],
                                'keperluan' => $multiData['keperluan'],
                            )
                        );

                        $insertHeaderData['id_travel_header'] = $id_travel_header;

                        $details            = array();
                        $transportasi_arr   = array();
                        $penginapan_arr     = array();
                        $tiket_arr          = array();

                        foreach ($trips as $i => $trip) {
                            $trip['id_travel_header'] = $id_travel_header;
                            $startDateTrip = date_create($trip['start']);
                            $trip['start_date'] = $startDateTrip->format('Y-m-d');
                            $trip['start_time'] = $startDateTrip->format('H:i:s');
                            $trip['end_date'] = $multiData['end_date'];
                            $trip['end_time'] = $multiData['end_time'];

                            $nextTrip = null;
                            if (isset($trips[$i + 1])) {
                                $nextTrip = $trips[$i + 1];

                                $endDateTrip = date_create($nextTrip['start']);
                                $trip['end_date'] = $endDateTrip->format('Y-m-d');
                                $trip['end_time'] = $endDateTrip->format('H:i:s');
                            }

                            $trip['tipe_travel'] = $trip['country'] == 'ID' ? 'D' : 'O';
                            $trip['tiket'] = isset($trip['tiket']) ? $trip['tiket'] : [0];

                            $updateDetailData = array(
                                'id_travel_header' => $trip['id_travel_header'],
                                'activity' => $multiData['activity'],
                                'country' => $trip['country'],
                                'tipe_travel' => $trip['tipe_travel'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $trip['tujuan'],
                                'tujuan_persa' => $trip['tujuan_persa'],
                                'tujuan_lain' => $trip['tujuan_lain'],
                                'start_date' => $trip['start_date'],
                                'start_time' => $trip['start_time'],
                                'end_date' => $trip['end_date'],
                                'end_time' => $trip['end_time'],
                                'no_urut' => $i,
                                'keperluan' => $trip['keperluan'],
                                'jenis_penginapan' => $trip['inap'],
                                'transportasi_tiket' => implode(',', $trip['tiket']),
                                'transportasi' => implode(',', $trip['trans']),
                            );

                            $updateDetailData = $this->dgeneral->basic_column('insert_full', $updateDetailData);

                            array_push($penginapan_arr, $trip['inap']);
                            array_push($transportasi_arr, implode(',', $trip['trans']));
                            $tiket_arr = array_merge($trip['tiket']);
                            array_push($details, $updateDetailData);
                        }

                        array_unique($penginapan_arr);
                        array_unique($transportasi_arr);

                        $insertHeaderData['jenis_penginapan'] = implode(',', $penginapan_arr);
                        $insertHeaderData['transportasi'] = implode(',', $transportasi_arr);
                        $insertHeaderData['booking_brgkt'] = (in_array('berangkat', $tiket_arr) ? 1 : 0);
                        $insertHeaderData['booking_kembali'] = (in_array('pulang', $tiket_arr) ? 1 : 0);

                        /** Insert travel header */
                        $this->dgeneral->insert(
                            'tbl_travel_header',
                            $insertHeaderData
                        );

                        /** Update/Insert travel detail */
                        foreach ($details as $detail) {
                            $this->db->insert('tbl_travel_detail', $detail);
                        }
                    } else {
                        /** Update existing travel header & detail */
                        $updateHeaderData = array_merge(
                            $this->dgeneral->basic_column('update'),
                            array(
                                'activity' => $data['activity'],
                                'approval_level' => 1,
                                'approval_status' => 0,
                                'approval_nik' => $approval['nik_atasan'],
                                'nik' => $userData->nik,
                                'persa' => $persa,
                                'no_hp' => $data['no_hp'],
                                'kota_asal' => $asal->kota,
                                'tipe_travel' => $multiData['tipe_travel'],
                                'tipe_trip' => $data['tipe_trip'],
                                'country' => $multiData['country'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $multiData['tujuan'],
                                'tujuan_persa' => $multiData['tujuan_persa'],
                                'tujuan_lain' => $multiData['tujuan_lain'],
                                'start_date' => $multiData['start_date'],
                                'start_time' => $multiData['start_time'],
                                'end_date' => $multiData['end_date'],
                                'end_time' => $multiData['end_time'],
                                'keperluan' => $multiData['keperluan'],
                            )
                        );

                        $details            = array();
                        $transportasi_arr   = array();
                        $penginapan_arr     = array();
                        $tiket_arr          = array();

                        foreach ($trips as $i => $trip) {
                            $startDateTrip = date_create($trip['start']);
                            $trip['start_date'] = $startDateTrip->format('Y-m-d');
                            $trip['start_time'] = $startDateTrip->format('H:i:s');
                            $trip['end_date'] = $multiData['end_date'];
                            $trip['end_time'] = $multiData['end_time'];

                            $nextTrip = null;
                            if (isset($trips[$i + 1])) {
                                $nextTrip = $trips[$i + 1];

                                $endDateTrip = date_create($nextTrip['start']);
                                $trip['end_date'] = $endDateTrip->format('Y-m-d');
                                $trip['end_time'] = $endDateTrip->format('H:i:s');
                            }

                            $trip['tipe_travel'] = $trip['country'] == 'ID' ? 'D' : 'O';

                            $updateDetailData = array(
                                'activity' => $multiData['activity'],
                                'country' => $trip['country'],
                                'tipe_travel' => $trip['tipe_travel'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $trip['tujuan'],
                                'tujuan_persa' => $trip['tujuan_persa'],
                                'tujuan_lain' => $trip['tujuan_lain'],
                                'start_date' => $trip['start_date'],
                                'start_time' => $trip['start_time'],
                                'end_date' => $trip['end_date'],
                                'end_time' => $trip['end_time'],
                                'no_urut' => $i,
                                'keperluan' => $trip['keperluan'],
                                'jenis_penginapan' => $trip['inap'],
                                'transportasi_tiket' => implode(',', $trip['tiket']),
                                'transportasi' => implode(',', $trip['trans']),
                            );

                            /** Cek bila dalam data detail terdapat id detail */
                            if (isset($trip['id']) && !empty($trip['id'])) {
                                $idTrip = $this->generate->kirana_decrypt($trip['id']);
                                $updateDetailData['id'] = $idTrip;
                                $updateDetailData = $this->dgeneral->basic_column('update', $updateDetailData);
                            } else {
                                $updateDetailData['id_travel_header'] = $id_travel_header;
                                $updateDetailData = $this->dgeneral->basic_column('insert_full', $updateDetailData);
                            }
                            $inap = explode(',', $trip['inap']);
                            array_push($penginapan_arr, $trip['inap']);
                            array_push($transportasi_arr, implode(',', $trip['trans']));
                            $tiket_arr = array_merge($trip['tiket']);
                            array_push($details, $updateDetailData);
                        }

                        /** Collect id detail untuk di exclude penghapusan detail  */
                        $detailIds = array_reduce($details, function ($all, $current) {
                            if (isset($current['id']))
                                array_push($all, $current['id']);
                            return $all;
                        }, array());

                        // add ayy
                        array_unique($penginapan_arr);
                        array_unique($transportasi_arr);
                        $updateHeaderData['jenis_penginapan'] = implode(',', $penginapan_arr);
                        $updateHeaderData['transportasi'] = implode(',', $transportasi_arr);
                        $updateHeaderData['booking_brgkt'] = (in_array('berangkat', $tiket_arr) ? 1 : 0);
                        $updateHeaderData['booking_kembali'] = (in_array('pulang', $tiket_arr) ? 1 : 0);

                        /** Update travel header */
                        $this->dgeneral->update(
                            'tbl_travel_header',
                            $updateHeaderData,
                            array(
                                array('kolom' => 'id_travel_header', 'value' => $id_travel_header)
                            )
                        );

                        /** Delete detail yang tidak terupdate */
                        if (count($detailIds) > 0) {
                            $this->db->where_not_in('id_travel_detail', $detailIds);
                            $this->db->delete(
                                'tbl_travel_detail',
                                array(
                                    'id_travel_header' => $id_travel_header
                                )
                            );
                        }

                        /** Update/Insert travel detail */
                        foreach ($details as $detail) {
                            if (!isset($detail['id'])) {
                                $this->db->insert('tbl_travel_detail', $detail);
                            } else {
                                $idDetail = $detail['id'];
                                unset($detail['id']);
                                $this->db->update('tbl_travel_detail', $detail, array(
                                    'id_travel_detail' => $idDetail
                                ));
                            }
                        }
                    }

                    // rencana aktifitas
                    // delete data aktifitas
                    $data_delete = $this->dgeneral->basic_column('delete', NULL, $datetime);
                    $this->dgeneral->update("tbl_travel_rencana_aktifitas", $data_delete, array(
                        array(
                            'kolom' => 'id_travel_header',
                            'value' => $id_travel_header
                        )
                    ));

                    if (isset($data['aktifitas_add'])) {
                        foreach ($data['aktifitas_add'] as $index => $aktifitas) {
                            $data_aktifitas = array(
                                "id_travel_header" => $id_travel_header,
                                "tanggal_aktifitas" => $this->generate->regenerateDateFormat($data['tanggal_aktifitas_add'][$index]),
                                "tanggal_aktifitas_end" => $this->generate->regenerateDateFormat($data['tanggal_aktifitas_add'][$index]),
                                "lokasi" => trim($data['pabrik_aktifitas_add'][$index]),
                                "aktifitas" => trim($aktifitas),
                            );
                            $data_aktifitas = $this->dgeneral->basic_column("insert", $data_aktifitas);
                            $this->dgeneral->insert("tbl_travel_rencana_aktifitas", $data_aktifitas);
                        }
                    }

                    /** Uang Muka */
                    $totalUangMuka = $this->generate->revert_rupiah($data['total_um']);

                    if ($totalUangMuka > 0) {
                        $uangmukas = $data['uangmuka'];
                        $uangmukasData = array();
                        foreach ($uangmukas as $i => $uangmuka) {
                            $no = $i + 1;
                            $foreignData = json_decode($uangmuka['fk'], true);

                            if (isset($uangmuka['id']) && !empty($uangmuka['id'])) {
                                $updateUmData = array(
                                    'id' => $uangmuka['id'],
                                    'no_urut' => $no,
                                    'durasi' => $uangmuka['durasi'],
                                    'rate' => $this->generate->revert_rupiah($uangmuka['rate']),
                                    'jumlah' => $this->generate->revert_rupiah($uangmuka['jumlah']),
                                );

                                $umData = array_merge(
                                    $updateUmData,
                                    $this->dgeneral->basic_column('update')
                                );
                            } else {
                                $insertUmData = array(
                                    'id_travel_header' => $id_travel_header,
                                    'no_urut' => $no,
                                    'durasi' => $uangmuka['durasi'],
                                    'rate' => $this->generate->revert_rupiah($uangmuka['rate']),
                                    'jumlah' => $this->generate->revert_rupiah($uangmuka['jumlah']),
                                );
                                $umData = array_merge(
                                    $insertUmData,
                                    $foreignData,
                                    $this->dgeneral->basic_column('insert_full')
                                );
                            }

                            array_push($uangmukasData, $umData);
                        }

                        /** Collect id UM untuk di exclude penghapusan UM  */
                        $umIds = array_reduce($uangmukasData, function ($all, $current) {
                            if (isset($current['id']))
                                array_push($all, $current['id']);
                            return $all;
                        }, array());

                        /** Delete UM yang tidak terupdate */
                        if (count($umIds) > 0) {
                            $this->db->reset_query();
                            $this->db->where_not_in('id_travel_downpayment', $umIds);
                            $this->db->delete(
                                'tbl_travel_downpayment',
                                array(
                                    'id_travel_header' => $id_travel_header
                                )
                            );
                        }

                        /** Update/Insert travel UM */
                        foreach ($uangmukasData as $uangmuka) {
                            $this->db->reset_query();
                            if (!isset($uangmuka['id'])) {
                                $this->db->insert('tbl_travel_downpayment', $uangmuka);
                            } else {
                                $idUm = $uangmuka['id'];
                                unset($uangmuka['id']);
                                $this->db->update('tbl_travel_downpayment', $uangmuka, array(
                                    'id_travel_downpayment' => $idUm
                                ));
                            }
                        }

                        /** update travel header total um */
                        $this->db->reset_query();
                        $this->db->update(
                            'tbl_travel_header',
                            array(
                                'total_um' => $totalUangMuka
                            ),
                            array(
                                'id_travel_header' => $id_travel_header
                            )
                        );
                    }

                    $data = $this->dspd->get_travel_header(array('id' => $id_travel_header, 'single' => true));
                    /** Store Travel Log */
                    $travelLogSaved = true;
                    $lastLog = $this->dspd->get_travel_log_status(
                        array(
                            'id_travel_header' => $id_travel_header,
                            'single' => true
                        )
                    );
                    $travelLogging = false;
                    if (isset($lastLog)) {
                        if (
                            $lastLog->approval_level !== $data->approval_level
                            || $lastLog->approval_status !== $data->approval_status
                        ) {
                            $travelLogging = true;
                        }
                    } else {
                        $travelLogging = true;
                    }

                    if ($travelLogging) {
                        $travelLogSaved = $this->lspd->travel_log(array(
                            'id_travel_header' => $id_travel_header,
                            'data' => $data,
                            'actor' => $userData->nik,
                            'action' => 'pengajuan',
                            'remark' => 'new',
                        ));
                    }

                    /** Error handler DB Transaction */
                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else if (!$travelLogSaved) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Terjadi kesalahan pada sistem, silahkan hubungi admin (IT Staff Kirana). ERR: LOG";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Pengajuan SPD berhasil disimpan.";
                        $sts = "OK";

                        /** Send email notifikasi */
                        $sendEmailResult = true;
                        $data = $this->dspd->get_travel_header(array('id' => $id_travel_header, 'single' => true));
                        if ($sendEmail) {
                            try {
                                $result = $this->send_approval_email_spd($data);
                                if ($result['sts'] == "NotOK")
                                    $sendEmailResult = false;
                            } catch (Exception $exception) {
                                $sendEmailResult = false;
                            }
                        }
                    }
                } else {
                    $msg = "Data yang disimpan tidak ada";
                    $sts = "NotOK";
                }
            } else {
                $msg = "Data yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_pengajuan_spd($data = null)
    {
        $this->general->connectDbPortal();

        $sendEmail = false;

        $userData = $this->data['user'];
        try {
            if (isset($data)) {
                $persa = $userData->persa;
                $asal = $this->dspd->get_travel_tujuan(
                    array(
                        'company_code' => $userData->btrtl,
                        'single' => true
                    )
                );

                $id_travel_header = null;
                if (isset($data['id_travel_header']) && !empty($data['id_travel_header']))
                    $id_travel_header = $this->generate->kirana_decrypt($data['id_travel_header']);

                unset($data['id_travel_header']);

                if (isset($data['tipe_trip']) && !empty($data['tipe_trip'])) {
                    $tipe_trip = $data['tipe_trip'];

                    $approval = $this->dspd->get_approval();

                    $this->dgeneral->begin_transaction();

                    /** Multi Trip */
                    $trips = $data['detail'];
                    /** get first trip */
                    $firstTrip = reset($trips);

                    $multiData = array();

                    $multiData['tipe_travel'] = $firstTrip['country'] == 'ID' ? 'D' : 'O';
                    $multiData['jenis_tujuan'] = $firstTrip['country'] == 'ID' ? 'Domestik' : 'Luar Negeri';
                    $multiData['activity'] = $data['activity'];
                    $multiData['country'] = $firstTrip['country'];
                    $multiData['tujuan'] = $firstTrip['tujuan'];
                    $multiData['tujuan_persa'] = $firstTrip['tujuan_persa'];
                    $multiData['tujuan_lain'] = $firstTrip['tujuan_lain'];
                    $startDate = date_create($firstTrip['start']);
                    $multiData['start_date'] = $startDate->format('Y-m-d');
                    $multiData['start_time'] = $startDate->format('H:i:s');

                    if (isset($data['detail_end'])) {
                        $endDate = date_create($data['detail_end']);
                        $multiData['end_date'] = $endDate->format('Y-m-d');
                        $multiData['end_time'] = $endDate->format('H:i:s');
                    } else {
                        $multiData['end_date'] = null;
                        $multiData['end_time'] = null;
                    }

                    $multiData['keperluan'] = $firstTrip['keperluan'];
                    if (!isset($id_travel_header)) {
                        $sendEmail = true;
                        /** Create new travel header & detail */
                        $id_travel_header = $this->lspd->generate_header_code($userData);
                        $insertHeaderData = array_merge(
                            $this->dgeneral->basic_column('insert_full'),
                            array(
                                'activity' => $data['activity'],
                                'approval_level' => 1,
                                'approval_status' => 0,
                                'approval_nik' => $approval['nik_atasan'],
                                'nik' => $userData->nik,
                                'persa' => $persa,
                                'no_hp' => $data['no_hp'],
                                'kota_asal' => $asal->kota,
                                'tipe_travel' => $multiData['tipe_travel'],
                                'tipe_trip' => $data['tipe_trip'],
                                'country' => $multiData['country'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $multiData['tujuan'],
                                'tujuan_persa' => $multiData['tujuan_persa'],
                                'tujuan_lain' => $multiData['tujuan_lain'],
                                'start_date' => $multiData['start_date'],
                                'start_time' => $multiData['start_time'],
                                'end_date' => $multiData['end_date'],
                                'end_time' => $multiData['end_time'],
                                'keperluan' => $multiData['keperluan'],
                            )
                        );

                        $insertHeaderData['id_travel_header'] = $id_travel_header;

                        $details            = array();
                        $transportasi_arr   = array();
                        $penginapan_arr     = array();

                        foreach ($trips as $i => $trip) {
                            $trip['id_travel_header'] = $id_travel_header;
                            $startDateTrip = date_create($trip['start']);
                            $trip['start_date'] = $startDateTrip->format('Y-m-d');
                            $trip['start_time'] = $startDateTrip->format('H:i:s');
                            $trip['end_date'] = $multiData['end_date'];
                            $trip['end_time'] = $multiData['end_time'];

                            $nextTrip = null;
                            if (isset($trips[$i + 1])) {
                                $nextTrip = $trips[$i + 1];

                                $endDateTrip = date_create($nextTrip['start']);
                                $trip['end_date'] = $endDateTrip->format('Y-m-d');
                                $trip['end_time'] = $endDateTrip->format('H:i:s');
                            }

                            $trip['tipe_travel'] = $trip['country'] == 'ID' ? 'D' : 'O';

                            $updateDetailData = array(
                                'id_travel_header' => $trip['id_travel_header'],
                                'activity' => $multiData['activity'],
                                'country' => $trip['country'],
                                'tipe_travel' => $trip['tipe_travel'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $trip['tujuan'],
                                'tujuan_persa' => $trip['tujuan_persa'],
                                'tujuan_lain' => $trip['tujuan_lain'],
                                'start_date' => $trip['start_date'],
                                'start_time' => $trip['start_time'],
                                'end_date' => $trip['end_date'],
                                'end_time' => $trip['end_time'],
                                'no_urut' => $i,
                                'keperluan' => $trip['keperluan'],
                                'jenis_penginapan' => $trip['inap'],
                                'transportasi_tiket' => implode(',', $trip['tiket']),
                                'transportasi' => implode(',', $trip['trans']),
                            );

                            $updateDetailData = $this->dgeneral->basic_column('insert_full', $updateDetailData);

                            array_push($penginapan_arr, $trip['inap']);
                            array_push($transportasi_arr, implode(',', $trip['trans']));
                            array_push($details, $updateDetailData);
                        }

                        array_unique($penginapan_arr);
                        array_unique($transportasi_arr);

                        $insertHeaderData['jenis_penginapan'] = implode(',', $penginapan_arr);
                        $insertHeaderData['transportasi'] = implode(',', $transportasi_arr);
                        $insertHeaderData['booking_brgkt'] = 1;

                        /** Insert travel header */
                        $this->dgeneral->insert(
                            'tbl_travel_header',
                            $insertHeaderData
                        );

                        /** Update/Insert travel detail */
                        foreach ($details as $detail) {
                            $this->db->insert('tbl_travel_detail', $detail);
                        }
                    } else {
                        /** Update existing travel header & detail */
                        $updateHeaderData = array_merge(
                            $this->dgeneral->basic_column('update'),
                            array(
                                'activity' => $data['activity'],
                                'approval_level' => 1,
                                'approval_status' => 0,
                                'approval_nik' => $approval['nik_atasan'],
                                'nik' => $userData->nik,
                                'persa' => $persa,
                                'no_hp' => $data['no_hp'],
                                'kota_asal' => $asal->kota,
                                'tipe_travel' => $multiData['tipe_travel'],
                                'tipe_trip' => $data['tipe_trip'],
                                'country' => $multiData['country'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $multiData['tujuan'],
                                'tujuan_persa' => $multiData['tujuan_persa'],
                                'tujuan_lain' => $multiData['tujuan_lain'],
                                'start_date' => $multiData['start_date'],
                                'start_time' => $multiData['start_time'],
                                'end_date' => $multiData['end_date'],
                                'end_time' => $multiData['end_time'],
                                'keperluan' => $multiData['keperluan'],
                            )
                        );

                        $details            = array();
                        $transportasi_arr   = array();
                        $penginapan_arr     = array();

                        foreach ($trips as $i => $trip) {
                            $startDateTrip = date_create($trip['start']);
                            $trip['start_date'] = $startDateTrip->format('Y-m-d');
                            $trip['start_time'] = $startDateTrip->format('H:i:s');
                            $trip['end_date'] = $multiData['end_date'];
                            $trip['end_time'] = $multiData['end_time'];

                            $nextTrip = null;
                            if (isset($trips[$i + 1])) {
                                $nextTrip = $trips[$i + 1];

                                $endDateTrip = date_create($nextTrip['start']);
                                $trip['end_date'] = $endDateTrip->format('Y-m-d');
                                $trip['end_time'] = $endDateTrip->format('H:i:s');
                            }

                            $trip['tipe_travel'] = $trip['country'] == 'ID' ? 'D' : 'O';

                            $updateDetailData = array(
                                'activity' => $multiData['activity'],
                                'country' => $trip['country'],
                                'tipe_travel' => $trip['tipe_travel'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $trip['tujuan'],
                                'tujuan_persa' => $trip['tujuan_persa'],
                                'tujuan_lain' => $trip['tujuan_lain'],
                                'start_date' => $trip['start_date'],
                                'start_time' => $trip['start_time'],
                                'end_date' => $trip['end_date'],
                                'end_time' => $trip['end_time'],
                                'no_urut' => $i,
                                'keperluan' => $trip['keperluan'],
                                'jenis_penginapan' => $trip['inap'],
                                'transportasi_tiket' => implode(',', $trip['tiket']),
                                'transportasi' => implode(',', $trip['trans']),
                            );

                            /** Cek bila dalam data detail terdapat id detail */
                            if (isset($trip['id']) && !empty($trip['id'])) {
                                $idTrip = $this->generate->kirana_decrypt($trip['id']);
                                $updateDetailData['id'] = $idTrip;
                                $updateDetailData = $this->dgeneral->basic_column('update', $updateDetailData);
                            } else {
                                $updateDetailData['id_travel_header'] = $id_travel_header;
                                $updateDetailData = $this->dgeneral->basic_column('insert_full', $updateDetailData);
                            }
                            $inap = explode(',', $trip['inap']);
                            array_push($penginapan_arr, $trip['inap']);
                            array_push($transportasi_arr, implode(',', $trip['trans']));
                            array_push($details, $updateDetailData);
                        }

                        /** Collect id detail untuk di exclude penghapusan detail  */
                        $detailIds = array_reduce($details, function ($all, $current) {
                            if (isset($current['id']))
                                array_push($all, $current['id']);
                            return $all;
                        }, array());

                        // add ayy
                        array_unique($penginapan_arr);
                        array_unique($transportasi_arr);
                        $insertHeaderData['jenis_penginapan'] = implode(',', $penginapan_arr);
                        $insertHeaderData['transportasi'] = implode(',', $transportasi_arr);

                        /** Update travel header */
                        $this->dgeneral->update(
                            'tbl_travel_header',
                            $updateHeaderData,
                            array(
                                array('kolom' => 'id_travel_header', 'value' => $id_travel_header)
                            )
                        );

                        /** Delete detail yang tidak terupdate */
                        if (count($detailIds) > 0) {
                            $this->db->where_not_in('id_travel_detail', $detailIds);
                            $this->db->delete(
                                'tbl_travel_detail',
                                array(
                                    'id_travel_header' => $id_travel_header
                                )
                            );
                        }

                        /** Update/Insert travel detail */
                        foreach ($details as $detail) {
                            if (!isset($detail['id'])) {
                                $this->db->insert('tbl_travel_detail', $detail);
                            } else {
                                $idDetail = $detail['id'];
                                unset($detail['id']);
                                $this->db->update('tbl_travel_detail', $detail, array(
                                    'id_travel_detail' => $idDetail
                                ));
                            }
                        }
                    }

                    /** Uang Muka */
                    $totalUangMuka = $this->generate->revert_rupiah($data['total_um']);

                    if ($totalUangMuka > 0) {
                        $uangmukas = $data['uangmuka'];
                        $uangmukasData = array();
                        foreach ($uangmukas as $i => $uangmuka) {
                            $no = $i + 1;
                            $foreignData = json_decode($uangmuka['fk'], true);

                            if (isset($uangmuka['id']) && !empty($uangmuka['id'])) {
                                $updateUmData = array(
                                    'id' => $uangmuka['id'],
                                    'no_urut' => $no,
                                    'durasi' => $uangmuka['durasi'],
                                    'rate' => $this->generate->revert_rupiah($uangmuka['rate']),
                                    'jumlah' => $this->generate->revert_rupiah($uangmuka['jumlah']),
                                );

                                $umData = array_merge(
                                    $updateUmData,
                                    $this->dgeneral->basic_column('update')
                                );
                            } else {
                                $insertUmData = array(
                                    'id_travel_header' => $id_travel_header,
                                    'no_urut' => $no,
                                    'durasi' => $uangmuka['durasi'],
                                    'rate' => $this->generate->revert_rupiah($uangmuka['rate']),
                                    'jumlah' => $this->generate->revert_rupiah($uangmuka['jumlah']),
                                );
                                $umData = array_merge(
                                    $insertUmData,
                                    $foreignData,
                                    $this->dgeneral->basic_column('insert_full')
                                );
                            }

                            array_push($uangmukasData, $umData);
                        }

                        /** Collect id UM untuk di exclude penghapusan UM  */
                        $umIds = array_reduce($uangmukasData, function ($all, $current) {
                            if (isset($current['id']))
                                array_push($all, $current['id']);
                            return $all;
                        }, array());

                        /** Delete UM yang tidak terupdate */
                        if (count($umIds) > 0) {
                            $this->db->reset_query();
                            $this->db->where_not_in('id_travel_downpayment', $umIds);
                            $this->db->delete(
                                'tbl_travel_downpayment',
                                array(
                                    'id_travel_header' => $id_travel_header
                                )
                            );
                        }

                        /** Update/Insert travel UM */
                        foreach ($uangmukasData as $uangmuka) {
                            $this->db->reset_query();
                            if (!isset($uangmuka['id'])) {
                                $this->db->insert('tbl_travel_downpayment', $uangmuka);
                            } else {
                                $idUm = $uangmuka['id'];
                                unset($uangmuka['id']);
                                $this->db->update('tbl_travel_downpayment', $uangmuka, array(
                                    'id_travel_downpayment' => $idUm
                                ));
                            }
                        }

                        /** update travel header total um */
                        $this->db->reset_query();
                        $this->db->update(
                            'tbl_travel_header',
                            array(
                                'total_um' => $totalUangMuka
                            ),
                            array(
                                'id_travel_header' => $id_travel_header
                            )
                        );
                    }

                    $data = $this->dspd->get_travel_header(array('id' => $id_travel_header, 'single' => true));
                    /** Store Travel Log */
                    $travelLogSaved = true;
                    $lastLog = $this->dspd->get_travel_log_status(
                        array(
                            'id_travel_header' => $id_travel_header,
                            'single' => true
                        )
                    );
                    $travelLogging = false;
                    if (isset($lastLog)) {
                        if (
                            $lastLog->approval_level !== $data->approval_level
                            || $lastLog->approval_status !== $data->approval_status
                        ) {
                            $travelLogging = true;
                        }
                    } else {
                        $travelLogging = true;
                    }
                    if ($travelLogging) {
                        $travelLogSaved = $this->lspd->travel_log(array(
                            'id_travel_header' => $id_travel_header,
                            'data' => $data,
                            'actor' => $userData->nik,
                            'action' => 'pengajuan',
                            'remark' => 'new',
                        ));
                    }

                    /** Error handler DB Transaction */
                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else if (!$travelLogSaved) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Terjadi kesalahan pada sistem, silahkan hubungi admin (IT Staff Kirana). ERR: LOG";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Pengajuan SPD berhasil disimpan.";
                        $sts = "OK";

                        /** Send email notifikasi */
                        $sendEmailResult = true;
                        $data = $this->dspd->get_travel_header(array('id' => $id_travel_header, 'single' => true));

                        if ($sendEmail) {
                            try {
                                $result = $this->send_approval_email_spd($data);
                                if ($result['sts'] == "NotOK")
                                    $sendEmailResult = false;
                            } catch (Exception $exception) {
                                $sendEmailResult = false;
                            }
                        }
                    }
                } else {
                    $msg = "Data yang disimpan tidak ada";
                    $sts = "NotOK";
                }
            } else {
                $msg = "Data yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_data_um_tambahan($data = null)
    {
        $this->general->connectDbPortal();

        $sendEmail = false;

        $userData = $this->data['user'];
        try {
            if (isset($data)) {
                $persa = $userData->persa;
                $asal = $this->dspd->get_travel_tujuan(
                    array(
                        'company_code' => $userData->btrtl,
                        'single' => true
                    )
                );

                $id_travel_header = null;
                if (isset($data['id_travel_header']) && !empty($data['id_travel_header']))
                    $id_travel_header = $this->generate->kirana_decrypt($data['id_travel_header']);

                unset($data['id_travel_header']);

                /** Uang Muka */
                $totalUangMuka = $this->generate->revert_rupiah($data['total_umt']);

                if ($totalUangMuka > 0) {
                    $uangmukas = $data['uangmuka'];
                    $uangmukasData = array();
                    foreach ($uangmukas as $i => $uangmuka) {
                        $no = $i + 1;
                        $foreignData = json_decode($uangmuka['fk'], true);

                        if (isset($uangmuka['id']) && !empty($uangmuka['id'])) {
                            $updateUmData = array(
                                'id' => $uangmuka['id'],
                                'no_urut' => $no,
                                'durasi' => $uangmuka['durasi'],
                                'rate' => $this->generate->revert_rupiah($uangmuka['rate']),
                                'jumlah' => $this->generate->revert_rupiah($uangmuka['jumlah']),
                            );

                            $umData = array_merge(
                                $updateUmData,
                                $this->dgeneral->basic_column('update')
                            );
                        } else {
                            $insertUmData = array(
                                'id_travel_header' => $id_travel_header,
                                'no_urut' => $no,
                                'durasi' => $uangmuka['durasi'],
                                'rate' => $this->generate->revert_rupiah($uangmuka['rate']),
                                'jumlah' => $this->generate->revert_rupiah($uangmuka['jumlah']),
                            );
                            $umData = array_merge(
                                $insertUmData,
                                $foreignData,
                                $this->dgeneral->basic_column('insert_full')
                            );
                        }

                        array_push($uangmukasData, $umData);
                    }

                    /** Collect id UM untuk di exclude penghapusan UM  */
                    $umIds = array_reduce($uangmukasData, function ($all, $current) {
                        if (isset($current['id']))
                            array_push($all, $current['id']);
                        return $all;
                    }, array());

                    /** Delete UM yang tidak terupdate */
                    if (count($umIds) > 0) {
                        $this->db->reset_query();
                        $this->db->where_not_in('id_travel_downpayment', $umIds);
                        $this->db->delete(
                            'tbl_travel_downpayment',
                            array(
                                'id_travel_header' => $id_travel_header
                            )
                        );
                    }

                    $dataum_lama = $this->dspd->get_travel_downpayment(
                        array(
                            'id_header' => $id_travel_header,
                        )
                    );

                    $n          = 0;
                    $totalumnew = 0;
                    $datetime = date("Y-m-d H:i:s");
                    if (isset($dataum_lama) && $dataum_lama != null) {
                        foreach ($dataum_lama as $dataum_last) {
                            $this->db->reset_query();
                            if (isset($dataum_last->id)) {
                                $idUm_last  = $dataum_last->id;
                                $newjumlah  = $uangmukasData[$n]['jumlah'] + $dataum_last->jumlah;
                                $totalumnew = $totalumnew + $newjumlah;
                                $data_row   = array(
                                    "jumlah"       => $newjumlah,
                                    'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_edit' => $datetime
                                );
                                $this->dgeneral->update("tbl_travel_downpayment", $data_row, array(
                                    array(
                                        'kolom'    => 'id_travel_downpayment',
                                        'value'    => $idUm_last
                                    )
                                ));
                            }
                            $n++;
                        }
                    } else {
                        $arrins = [];
                        $urut   = 0;
                        foreach ($uangmukas as $x => $dtins) {
                            $urut++;
                            $arrins  = array(
                                "id_travel_header"  => $id_travel_header,
                                "no_urut"           => $urut,
                                "durasi"            => $dtins['durasi'],
                                "rate"              => $dtins['rate'],
                                "jumlah"            => $dtins['jumlah'],
                                "no_klaim"          => 1,
                            );
                            $arrins2 = json_decode($dtins['fk'], true);
                            $arrinsert = array_merge(
                                $arrins,
                                $arrins2,
                                $this->dgeneral->basic_column('insert_full')
                            );
                            $this->db->insert('tbl_travel_downpayment', $arrinsert);
                        }


                        $totalumnew = $totalUangMuka;
                    }

                    /** update travel header total um */
                    $this->db->reset_query();
                    $this->db->update(
                        'tbl_travel_header',
                        array(
                            'total_um' => $totalumnew,
                        ),
                        array(
                            'id_travel_header' => $id_travel_header
                        )
                    );
                }

                $data = $this->dspd->get_travel_header(array('id' => $id_travel_header, 'single' => true));
                /** Store Travel Log */
                $travelLogSaved = true;
                $lastLog = $this->dspd->get_travel_log_status(
                    array(
                        'id_travel_header' => $id_travel_header,
                        'single' => true
                    )
                );
                $travelLogging = false;
                if (isset($lastLog)) {
                    if (
                        $lastLog->approval_level !== $data->approval_level
                        || $lastLog->approval_status !== $data->approval_status
                    ) {
                        $travelLogging = true;
                    }
                } else {
                    $travelLogging = true;
                }

                if ($travelLogging) {
                    // update header
                    $approvalDecline = $this->dspd->get_approval(array('nik' => $data->nik, 'level' => 1));
                    $dataApproval['approval_status']    = 0;
                    $dataApproval['approval_level']     = 1;
                    $dataApproval['approval_nik']       = $approvalDecline['nik_atasan'];
                    $dataApproval['approval_catatan']   = 'Pengajuan UM Tambahan SPD';

                    $this->db->update(
                        'tbl_travel_header',
                        $dataApproval,
                        array(
                            'id_travel_header' => $id_travel_header
                        )
                    );

                    $travelLogSaved = $this->lspd->travel_log(array(
                        'id_travel_header' => $id_travel_header,
                        'data'      => $data,
                        'actor'     => $userData->nik,
                        'action'    => 'pengajuan',
                        'remark'    => 'new_um',
                        'status'    => $dataApproval['approval_status'],
                        'level'     => $dataApproval['approval_level'],
                        'nik'       => $dataApproval['approval_nik']
                    ));
                    $sendEmail = true;
                }

                /** Error handler DB Transaction */
                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else if (!$travelLogSaved) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Terjadi kesalahan pada sistem, silahkan hubungi admin (IT Staff Kirana). ERR: LOG";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Pengajuan UM Tambahan SPD berhasil disimpan.";
                    $sts = "OK";

                    /** Send email notifikasi */
                    $sendEmailResult = true;
                    $data = $this->dspd->get_travel_header(array('id' => $id_travel_header, 'single' => true));

                    if ($sendEmail) {
                        try {
                            $result = $this->send_approval_email_spd($data, 'revise');
                            if ($result['sts'] == "NotOK")
                                $sendEmailResult = false;
                        } catch (Exception $exception) {
                            $sendEmailResult = false;
                        }
                    }
                }
            } else {
                $msg = "Data yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    /** Approval functions start */
    private function save_persetujuan_spd($data = null)
    {
        $dataUser = $this->general->get_data_user();
        if (isset($data['id_travel_header'])) {
            $sendEmail = true;
            $id = $this->generate->kirana_decrypt($data['id_travel_header']);
            $action = $data['action'] === 'approve' ? 1 : 0;
            $actor_by = isset($data['is_approval_by']) && $data['is_approval_by'] ? true : false;
            $status_next = 0;

            $pengajuan = $this->dspd->get_travel_header(
                array(
                    'id' => $id,
                    'single' => true
                )
            );
            $role = $this->dspd->get_role(array('level' => $pengajuan->approval_level, 'single' => true));
            $approvalDecline = $this->dspd->get_approval(array('nik' => $pengajuan->nik, 'level' => 1));
            $approvalNext = $this->dspd->get_approval_next(array('id' => $id, 'mode' => 'pengajuan', $action));

            $upload_error = null;
            if (isset($_FILES['lampiran']) and $_FILES['lampiran']['size'] > 0) {
                if ($_FILES['lampiran']['error'] != 0) {
                    switch ($_FILES['lampiran']['error']) {
                        case UPLOAD_ERR_INI_SIZE:
                            $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                            break;
                        case UPLOAD_ERR_FORM_SIZE:
                            $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $upload_error = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                            break;
                        case UPLOAD_ERR_EXTENSION:
                            $upload_error = 'Upload berkas dihentikan oleh ekstensi. Harap pilih file lain.';
                            break;
                        default:
                            $upload_error = "Error upload gambar.";
                            break;
                    }
                } else {
                    $uploaddir = TR_PATH_FILE . TR_APPROVAL_UPLOAD_FOLDER;
                    if (!file_exists($uploaddir)) {
                        mkdir($uploaddir, 0777, true);
                    }

                    $config['upload_path'] = $uploaddir;
                    $config['allowed_types'] = TR_UPLOAD_ALLOWED;
                    $config['max_size'] = TR_UPLOAD_MAX;

                    $this->load->library('upload', $config);

                    $filename = 'APPROVAL_' . $dataUser->nik . "_" . date('YmdHis') . "_" . $_FILES['lampiran']['name'];
                    $_FILES['lampiran']['name'] = $filename;

                    if ($this->upload->do_upload('lampiran')) {
                        $upload_data = $this->upload->data();
                        $dataApproval['approval_lampiran'] = TR_APPROVAL_UPLOAD_FOLDER
                            . $upload_data['file_name'];
                    } else {
                        $upload_error = $this->upload->display_errors();
                    }
                }
            }

            switch ($data['action']) {
                case 'approve':
                    $dataApproval['approval_status'] = TR_STATUS_DISETUJUI;
                    if (isset($approvalNext)) {

                        $dataApproval['approval_level'] = $approvalNext->level;
                        $dataApproval['approval_nik'] = $approvalNext->atasan;
                        $status_next = $dataApproval['approval_level'];
                        if ($approvalNext->level == 99) {
                            $dataApproval['approval_nik'] = $approvalDecline['nik_atasan'];
                            $dataApproval['approval_status'] = '1';
                            $dataApproval['approval_level'] = '4';
                            $actor_by = false;
                            require('Sync.php');
                            $sync = new Sync();
                            $dataApp = array('approvals' => array(
                                "id" => $data['id_travel_header']
                            ));
                            $sync->save_sync_pengajuan(NULL, $dataApp, '1');
                        }
                    } else {
                        if ($pengajuan->total_um > 0)
                            $dataApproval['approval_level'] = intval($role->if_approve_spd_um);
                        else
                            $dataApproval['approval_level'] = intval($role->if_approve_spd);

                        $status_next = $dataApproval['approval_level'];
                        if ($dataApproval['approval_level'] == 99) {
                            $dataApproval['approval_nik'] = $approvalDecline['nik_atasan'];

                            // sync data pengajuan 
                            require('Sync.php');
                            $sync = new Sync();
                            $dataApp = array('approvals' => array(
                                "id" => $data['id_travel_header']
                            ));
                            $sync->save_sync_pengajuan(NULL, $dataApp, '1');
                            $dataApproval['approval_status'] = '1';
                            $dataApproval['approval_level'] = '4';
                            $actor_by = false;
                        }
                    }

                    if ($role->v_transport_spd || $role->v_transport_spd_um) {
                        // $sendEmail = false;
                    }
                    break;
                case 'disapprove':
                    $dataApproval['approval_status'] = TR_STATUS_DITOLAK;
                    if ($pengajuan->total_um > 0)
                        $dataApproval['approval_level'] = intval($role->if_decline_spd_um);
                    else
                        $dataApproval['approval_level'] = intval($role->if_decline_spd);

                    $dataApproval['approval_nik'] = $approvalDecline['nik_atasan'];
                    break;
                case 'revise':
                    $dataApproval['approval_status'] = TR_STATUS_REVISI;
                    if ($pengajuan->total_um > 0)
                        $dataApproval['approval_level'] = intval($role->if_decline_spd_um);
                    else
                        $dataApproval['approval_level'] = intval($role->if_decline_spd);
                    $dataApproval['approval_nik'] = $approvalDecline['nik_atasan'];
                    break;
            }

            $dataApproval['approval_catatan'] = $data['comment'];

            //Validasi Kalau final approval dan sync sap gagal
            $data_header = $this->dspd->get_travel_header(
                array(
                    'id' => $id,
                    'single' => true
                )
            );
            if ($data_header->approval_level == '4' && $data_header->approval_status == '1' && $data_header->no_trip == "" && $data['action'] != 'revise') {
                $return = array('sts' => 'NotOK', 'msg' => 'Gagal Generate No Trip. Mohon refresh halaman ini dan lakukan approval kembali.');
                echo json_encode($return);
                exit();
            }

            //====Status finish jika tidak ada transportasi pesawat, taxi, dan hotel saat pengajuan====//
            if ($status_next == 99) {
                $listBookTrans = $this->dspd->get_travel_booking_transport(array(
                    'idheader' => $id,
                    'type'     => 'trans'
                ));

                $listBookHotel = $this->dspd->get_travel_booking_transport(array(
                    'idheader' => $id,
                    'type'     => 'hotel'
                ));

                $transport_primary_booked = $this->dspd->get_trans_book(array(
                    'id_travel_header' => $id,
                    'status_tiket_primary' => 'primary',

                ));

                $hotel_booked = $this->dspd->get_hotel_book(array(
                    'id_travel_header' => $id,
                    'status_tiket_primary' => 'primary',

                ));

                //hitung jumlah transportasi primary
                $jumlah_trans_primary = 0;
                foreach ($listBookTrans as $dt) {
                    if ($dt['tipe'] == 'primary' && in_array($dt['tiket_trans_jenis'], ['pesawat', 'taxi']))
                        $jumlah_trans_primary++;
                }
                $jumlah_trans_primary_booked = count($transport_primary_booked);

                if (
                    $jumlah_trans_primary_booked >= $jumlah_trans_primary
                    && count($hotel_booked) >= count($listBookHotel)
                ) {
                    $dataApproval['approval_level'] = '99';
                    $dataApproval['approval_status'] = '4';
                    $dataApproval['status_transportasi'] = '1';
                }
            }
            //===============================================================//

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $dataApproval = $this->dgeneral->basic_column('update', $dataApproval);
            $this->db->update(
                'tbl_travel_header',
                $dataApproval,
                array(
                    'id_travel_header' => $id
                )
            );

            $this->lspd->travel_log(
                array(
                    'id_travel_header' => $id,
                    'data' => $pengajuan,
                    'data_user' => $dataUser,
                    'action' => 'approval',
                    'remark' => $data['action'],
                    'status' => $dataApproval['approval_status'],
                    'comment' => $data['comment'],
                    'actor' => $dataUser->nik,
                    'actor_by' => $actor_by
                )
            );

            if (isset($upload_error) || !empty($upload_error)) {
                $this->dgeneral->rollback_transaction();
                $msg = $upload_error;
                $sts = "NotOK";
            } else if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Pengajuan berhasil dikonfirmasi";
                $sts = "OK";

                /** Send email notifikasi */
                $sendEmailResult = true;
                if ($sendEmail) {
                    $dataSpd = $this->dspd->get_travel_header(array('id' => $id, 'single' => true));
                    try {
                        $result = $this->send_approval_email_spd($dataSpd, $data['action']);
                        if ($result['sts'] == "NotOK")
                            $sendEmailResult = false;
                    } catch (Exception $exception) {
                        $sendEmailResult = false;
                    }
                }
            }
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di hapus.";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_persetujuan_cancel_spd($data = null)
    {
        $dataUser = $this->general->get_data_user();
        if (isset($data['id_travel_header'])) {
            $id = $this->generate->kirana_decrypt($data['id_travel_header']);
            $action = $data['action'] === 'approve' ? 1 : 0;
            $this->general->connectDbPortal();

            $pengajuan = $this->dspd->get_travel_cancel(
                array(
                    'id_header' => $id,
                    'single' => true
                )
            );

            $this->dgeneral->begin_transaction();

            $approvalNext = $this->dspd->get_approval_next(array('id' => $id, 'mode' => 'pembatalan', 'action' => $action));
            $approvalDecline = $this->dspd->get_approval(array('nik' => $pengajuan->nik, 'level' => 1));
            $role = $this->dspd->get_role(array('level' => $pengajuan->approval_level, 'single' => true));

            switch ($data['action']) {
                case 'approve':
                    $dataApproval['approval_status'] = TR_STATUS_DISETUJUI;
                    if ($role->if_approve_cancel == 99) {
                        $dataApproval['approval_level'] = $role->if_approve_cancel;
                        $dataApproval['approval_nik']     = "";
                        //sync sap
                        require('Sync.php');
                        $sync = new Sync();
                        $dataApp = array('approvals' => array(
                            "id" => $data['id_travel_header']
                        ));
                        $sync->save_sync_cancel(NULL, $dataApp, '1');
                    } else {
                        $dataApproval['approval_level'] = $approvalNext->level;
                        $dataApproval['approval_nik'] = $approvalNext->atasan;
                    }
                    break;
                case 'disapprove':
                    $dataApproval['approval_status'] = TR_STATUS_DITOLAK;
                    if (isset($approvalNext)) {
                        $dataApproval['approval_level'] = $approvalNext->level;
                        $dataApproval['approval_nik'] = $approvalNext->atasan;
                    } else {
                        $dataApproval['approval_level'] = $role->if_decline_cancel;
                        $dataApproval['approval_nik'] = $approvalDecline['nik_atasan'];
                    }
                    break;
                case 'revise':
                    $dataApproval['approval_status'] = TR_STATUS_REVISI;
                    if (isset($approvalNext)) {
                        $dataApproval['approval_level'] = $approvalNext->level;
                        $dataApproval['approval_nik'] = $approvalNext->atasan;
                    } else {
                        $dataApproval['approval_level'] = $role->if_decline_cancel;
                        $dataApproval['approval_nik'] = $approvalDecline['nik_atasan'];
                    }
                    break;
            }

            $this->db->update(
                'tbl_travel_cancel',
                $dataApproval,
                array(
                    'id_travel_header' => $id
                )
            );

            $this->lspd->travel_log(
                array(
                    'id_travel_header' => $id,
                    'data' => $pengajuan,
                    'action' => 'approval',
                    'remark' => $data['action'],
                    'status' => $dataApproval['approval_status'],
                    'comment' => $data['comment'],
                    'actor' => $dataUser->nik,
                    'tipe' => 'pembatalan'
                )
            );

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Pengajuan berhasil dikonfirmasi";
                $sts = "OK";
            }
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di hapus.";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_persetujuan_deklarasi_spd($data = null)
    {
        $dataUser = $this->general->get_data_user();
        if (isset($data['id_travel_header'])) {
            $id = $this->generate->kirana_decrypt($data['id_travel_header']);
            $action = $data['action'] === 'approve' ? 1 : 0;
            $this->general->connectDbPortal();

            $pengajuan = $this->dspd->get_travel_deklarasi_header(
                array(
                    'id_travel_header' => $id,
                    'single' => true
                )
            );

            $this->dgeneral->begin_transaction();

            $approvalNext = $this->dspd->get_approval_next(array('id' => $id, 'mode' => 'deklarasi', 'action' => $action));
            $approvalDecline = $this->dspd->get_approval(array('nik' => $pengajuan->nik, 'level' => 1));
            $role = $this->dspd->get_role(array('level' => $pengajuan->approval_level, 'single' => true));

            switch ($data['action']) {
                case 'approve':
                    if ($role->if_approve_declare == 99) {
                        $dataApproval['approval_status'] = TR_STATUS_SELESAI;
                        $dataApproval['approval_level'] = $role->if_approve_declare;
                        $dataApproval['approval_nik']     = "";
                        //sync sap
                        require('Sync.php');
                        $sync = new Sync();
                        $dataApp = array('approvals' => array(
                            "id" => $data['id_travel_header']
                        ));
                        $sync->save_sync_declare(NULL, $dataApp, '1');
                    } else {
                        $dataApproval['approval_status'] = TR_STATUS_DISETUJUI;
                        $dataApproval['approval_level'] = $role->if_approve_declare;
                        $dataApproval['approval_nik']     = $approvalNext->atasan;
                    }
                    break;
                case 'disapprove':
                    $dataApproval['approval_status'] = TR_STATUS_DITOLAK;
                    if (isset($approvalNext)) {
                        $dataApproval['approval_level'] = $approvalNext->level;
                        $dataApproval['approval_nik'] = $approvalNext->atasan;
                    } else {
                        $dataApproval['approval_level'] = $role->if_decline_declare;
                        $dataApproval['approval_nik'] = $approvalDecline['nik_atasan'];
                    }
                    break;
                case 'revise':
                    $dataApproval['approval_status'] = TR_STATUS_REVISI;
                    if (isset($approvalNext)) {
                        $dataApproval['approval_level'] = $approvalNext->level;
                        $dataApproval['approval_nik'] = $approvalNext->atasan;
                    } else {
                        $dataApproval['approval_level'] = $role->if_decline_declare;
                        $dataApproval['approval_nik'] = $approvalDecline['nik_atasan'];
                    }
                    break;
            }
            $this->db->update(
                'tbl_travel_deklarasi_header',
                $dataApproval,
                array(
                    'id_travel_header' => $id
                )
            );

            $this->lspd->travel_deklarasi_log(
                array(
                    'id_travel_header' => $id,
                    'data' => $pengajuan,
                    'action' => 'approval',
                    'remark' => $data['action'],
                    'status' => $dataApproval['approval_status'],
                    'comment' => $data['comment'],
                    'actor' => $dataUser->nik,
                )
            );

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Pengajuan berhasil dikonfirmasi";
                $sts = "OK";

                // /** Send email notifikasi */
                $sendEmailResult = true;
                $dataPengajuan = $this->dspd->get_travel_header(array('id' => $id, 'single' => true));
                $dataDeklarasi = $this->dspd->get_travel_deklarasi_header(array('id' => $id, 'single' => true));
                try {
                    $result = $this->send_deklarasi_email_spd($dataPengajuan, $dataDeklarasi, $data['action']);
                    if ($result['sts'] == "NotOK")
                        $sendEmailResult = false;
                } catch (Exception $exception) {
                    $sendEmailResult = false;
                }
            }
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di hapus.";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    /** Approval functions end */

    private function save_pembatalan_spd($data = null)
    {
        $dataUser = $this->general->get_data_user();
        if (isset($data['id_travel_header'])) {
            $id = $this->generate->kirana_decrypt($data['id_travel_header']);
            $idCancel = $this->generate->kirana_decrypt($data['id_travel_cancel']);

            $this->general->connectDbPortal();

            $pengajuan = $this->dspd->get_travel_header(
                array(
                    'id' => $id,
                    'single' => true
                )
            );
            if (!isset($pengajuan)) {
                $sts = "NotOK";
                $msg = "Tidak ada pengajuan yang akan di batalkan.";
            } else {

                $approval = $this->dspd->get_approval(array('nik' => $pengajuan->nik, 'level' => TR_LEVEL_1));

                $checkCancel = $this->dspd->get_travel_cancel(
                    array('id_header' => $id, 'single' => true)
                );

                if (isset($checkCancel) && (!isset($idCancel) || empty($idCancel))) {
                    $sts = "NotOK";
                    $msg = "Sudah ada pengajuan pembatalan.";
                } else {
                    $upload_error = null;

                    $this->dgeneral->begin_transaction();
                    $sendEmail = false;

                    $dataPembatalan = array(
                        'id_travel_header' => $id,
                        'approval_level' => TR_LEVEL_1,
                        'approval_status' => TR_STATUS_MENUNGGU,
                        'approval_nik' => $approval['nik_atasan'],
                        'jumlah_kembali' => $this->generate->revert_rupiah($data['jumlah_kembali']),
                        'batal_um_only' => $data['batal_um_only'],
                        'catatan' => $data['catatan'],
                    );

                    if (isset($_FILES['lampiran']) and $_FILES['lampiran']['size'] > 0) {
                        if ($_FILES['lampiran']['error'] != 0) {
                            switch ($_FILES['lampiran']['error']) {
                                case UPLOAD_ERR_INI_SIZE:
                                    $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                    break;
                                case UPLOAD_ERR_FORM_SIZE:
                                    $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                    break;
                                case UPLOAD_ERR_PARTIAL:
                                    $upload_error = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                                    break;
                                case UPLOAD_ERR_EXTENSION:
                                    $upload_error = 'Upload berkas dihentikan oleh ekstensi. Harap pilih file lain.';
                                    break;
                                default:
                                    $upload_error = "Error upload gambar.";
                                    break;
                            }
                        } else {
                            $uploaddir = TR_PATH_FILE . TR_CANCEL_UPLOAD_FOLDER;
                            if (!file_exists($uploaddir)) {
                                mkdir($uploaddir, 0777, true);
                            }

                            $config['upload_path'] = $uploaddir;
                            $config['allowed_types'] = TR_UPLOAD_ALLOWED;
                            $config['max_size'] = TR_UPLOAD_MAX;

                            $this->load->library('upload', $config);

                            $filename = $dataUser->nik . "_" . date('YmdHis') . "_" . $_FILES['lampiran']['name'];
                            $_FILES['lampiran']['name'] = $filename;

                            if ($this->upload->do_upload('lampiran')) {
                                $upload_data = $this->upload->data();
                                $dataPembatalan['lampiran'] = TR_CANCEL_UPLOAD_FOLDER
                                    . $upload_data['file_name'];
                            } else {
                                $upload_error = $this->upload->display_errors();
                            }
                        }
                    }

                    if (isset($idCancel) && !empty($idCancel)) {
                        $dataPembatalan = $this->dgeneral->basic_column('update', $dataPembatalan);

                        $this->db->update(
                            'tbl_travel_cancel',
                            $dataPembatalan,
                            array(
                                'id_travel_cancel' => $idCancel
                            )
                        );
                    } else {
                        $sendEmail = true;

                        $dataPembatalan = $this->dgeneral->basic_column('insert', $dataPembatalan);

                        $this->db->insert(
                            'tbl_travel_cancel',
                            $dataPembatalan
                        );
                    }

                    $this->lspd->travel_log(
                        array(
                            'id_travel_header' => $id,
                            'data' => $dataPembatalan,
                            'action' => 'pembatalan',
                            'remark' => 'new',
                            'status' => TR_STATUS_MENUNGGU,
                            'comment' => $data['catatan'],
                            'actor' => $dataUser->nik,
                        )
                    );

                    if (isset($upload_error)) {
                        $this->dgeneral->rollback_transaction();
                        $msg = $upload_error;
                        $sts = "NotOK";
                    } else if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Pengajuan berhasil dikonfirmasi";
                        $sts = "OK";

                        /** Send email notifikasi */
                        $sendEmailResult = true;
                        $data = $this->dspd->get_travel_header(array('id' => $id, 'single' => true));
                        if ($sendEmail) {
                            try {
                                $result = $this->send_cancel_email_spd($data, $dataPembatalan);
                                if ($result['sts'] == "NotOK")
                                    $sendEmailResult = false;
                            } catch (Exception $exception) {
                                $sendEmailResult = false;
                            }
                        }
                    }
                }
            }
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di hapus.";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_booking_spd($data = null)
    {
        $dataUser = $this->general->get_data_user();
        $sendEmail = false;
        if (isset($data['id_travel_header'])) {
            $id = $this->generate->kirana_decrypt($data['id_travel_header']);

            $this->general->connectDbPortal();

            $pengajuan = $this->dspd->get_travel_header(
                array(
                    'id' => $id,
                    'single' => true
                )
            );

            /** Cek kirim email approval ke role selanjutnya jika ada validasi spd yang harus dipesankan
             * transportasi terlebih dahulu
             */
            $role       = $this->dspd->get_role(array('level' => $pengajuan->approval_level, 'single' => true));
            $last_book  = $this->dspd->get_travel_transport(array('id_header' => $id, 'single' => true));

            if (isset($role)) {
                if ($role->v_transport_spd || $role->v_transport_spd_um) {
                    $sendEmail = true;
                }
            }

            if (!isset($pengajuan)) {
                $msg = "Tidak ada data yang disimpan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->begin_transaction();
                $upload_error = null;

                $last_bookid = "";
                /** Save transportasi */
                if (isset($data['transport'])) {
                    if (isset($last_book)) {
                        $last_bookid = $last_book->id_travel_detail;
                    }
                    $uploaded_lampiran = array();

                    if (isset($_FILES)) {
                        $uploaddir = TR_PATH_FILE . TR_BOOKING_UPLOAD_FOLDER;
                        if (!file_exists($uploaddir)) {
                            mkdir($uploaddir, 0777, true);
                        }

                        $uploaded_gambar = null;

                        $lampirans = $_FILES['transport'];

                        $config['upload_path'] = $uploaddir;
                        $config['allowed_types'] = TR_UPLOAD_ALLOWED;
                        $config['max_size'] = TR_UPLOAD_MAX;
                        $config['mod_mime_fix'] = false;

                        $this->load->library('upload', $config);

                        try {
                            foreach ($lampirans['name'] as $i => $lampiran) {
                                $_FILES['lampiran_' . $i]['name'] = $id . "_" . date('YmdHis') . "_" . $lampirans['name'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['type'] = $lampirans['type'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['tmp_name'] = $lampirans['tmp_name'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['error'] = $lampirans['error'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['size'] = $lampirans['size'][$i]['lampiran'];

                                if ($_FILES['lampiran_' . $i]['error'] != 0) {
                                    switch ($_FILES['lampiran_' . $i]['error']) {
                                        case UPLOAD_ERR_INI_SIZE:
                                            $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                            break;
                                        case UPLOAD_ERR_EXTENSION:
                                            $upload_error[] = 'Lampiran ke ' . ($i + 1) . '.Jenis berkas yang Anda coba untuk mengunggah tidak diperbolehkan.';
                                            break;
                                        case UPLOAD_ERR_FORM_SIZE:
                                            $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                            break;
                                        case UPLOAD_ERR_PARTIAL:
                                            $upload_error = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                                            break;
                                    }
                                }

                                if ($_FILES['lampiran_' . $i]['size'] > 0) {
                                    $this->upload->initialize($config, true);
                                    if ($this->upload->do_upload('lampiran_' . $i)) {
                                        $upload_data = $this->upload->data();
                                        $uploaded_lampiran[$i] = TR_BOOKING_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                        $data['transport'][$i]['lampiran'] = TR_BOOKING_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                    } else {
                                        $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. ' . $this->upload->display_errors('', '');
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            $upload_error[] = $e->getMessage();
                        }

                        if (count($upload_error) > 0) {
                            foreach ($uploaded_lampiran as $lampiran) {
                                unlink(TR_PATH_FILE . $lampiran);
                            }
                            if (count($upload_error) > 0)
                                $msg = join('<br/>', $upload_error);
                            else
                                $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            echo json_encode($return);
                            return;
                        }
                    }

                    $transports = $data['transport'];
                    $iddet      = "";
                    foreach ($transports as $transport) {
                        if (isset($transport['jadwal']) && !empty($transport['jadwal'])) {
                            $jadwal = date_create($transport['jadwal']);
                            unset($transport['jadwal']);
                            $transport['tanggal'] = $jadwal->format('Y-m-d');
                            $transport['jam'] = $jadwal->format('H:i:s');
                        }
                        $transport['id_travel_header'] = $id;
                        $iddet                         = $transport['id_travel_detail'] == "kembali" ? $last_bookid : $transport['id_travel_detail'];
                        if (isset($transport['harga'])) {
                            $transport['harga'] = $this->generate->revert_rupiah($transport['harga']);
                        }

                        if (strpos($transport['id_travel_detail'], "kembali") !== false) {
                            $id_det = explode("_", $transport['id_travel_detail']);
                            $transport['id_travel_detail'] = $this->generate->kirana_decrypt($id_det[0]) != "kembali" && $this->generate->kirana_decrypt($id_det[0]) != "" ? $this->generate->kirana_decrypt($id_det[0]) : $iddet;

                            $transport['transport_kembali'] = 1;
                        } else {
                            $transport['id_travel_detail'] = $this->generate->kirana_decrypt($transport['id_travel_detail']);
                        }
                        $transport['status_tiket'] = isset($transport['status_tiket']) ? $transport['status_tiket'] : " ";
                        if ($transport['status_tiket'] == "on" && $transport['status_tiket'] != "") {
                            $transport['status_tiket'] = "Issued";
                        } else if ($transport['status_tiket'] != "on" && $transport['status_tiket'] != "") {
                            $transport['status_tiket'] = "Cancel";
                        } else {
                            $transport['status_tiket'] = "";
                        }
                        $transport['status_tiket_refund'] = isset($transport['status_tiket_refund']) ? $transport['status_tiket_refund'] : " ";

                        if (isset($transport['id_travel_transport']) && !empty($transport['id_travel_transport'])) {
                            $idTransport = $this->generate->kirana_decrypt($transport['id_travel_transport']);
                            unset($transport['id_travel_transport']);
                            $dataBooking = $this->dgeneral->basic_column('update', $transport);
                            $this->db->update(
                                'tbl_travel_transport',
                                $dataBooking,
                                array(
                                    'id_travel_transport' => $idTransport
                                )
                            );
                        } else {
                            unset($transport['id_travel_transport']);
                            $dataBooking = $this->dgeneral->basic_column('insert_full', $transport);
                            $this->db->insert(
                                'tbl_travel_transport',
                                $dataBooking
                            );
                        }
                    }
                }

                if (isset($data['penginapan'])) {

                    $uploaded_lampiran = array();

                    if (isset($_FILES)) {
                        $uploaddir = TR_PATH_FILE . TR_BOOKING_UPLOAD_FOLDER;
                        if (!file_exists($uploaddir)) {
                            mkdir($uploaddir, 0777, true);
                        }

                        $uploaded_gambar = null;

                        $lampirans = $_FILES['transport'];

                        $config['upload_path'] = $uploaddir;
                        $config['allowed_types'] = TR_UPLOAD_ALLOWED;
                        $config['max_size'] = TR_UPLOAD_MAX;
                        $config['mod_mime_fix'] = false;

                        $this->load->library('upload', $config);

                        try {
                            foreach ($lampirans['name'] as $i => $lampiran) {
                                $_FILES['lampiran_' . $i]['name'] = $id . "_" . date('YmdHis') . "_" . $lampirans['name'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['type'] = $lampirans['type'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['tmp_name'] = $lampirans['tmp_name'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['error'] = $lampirans['error'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['size'] = $lampirans['size'][$i]['lampiran'];

                                if ($_FILES['lampiran_' . $i]['error'] != 0) {
                                    switch ($_FILES['lampiran_' . $i]['error']) {
                                        case UPLOAD_ERR_INI_SIZE:
                                            $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                            break;
                                        case UPLOAD_ERR_EXTENSION:
                                            $upload_error[] = 'Lampiran ke ' . ($i + 1) . '.Jenis berkas yang Anda coba untuk mengunggah tidak diperbolehkan.';
                                            break;
                                        case UPLOAD_ERR_FORM_SIZE:
                                            $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                            break;
                                        case UPLOAD_ERR_PARTIAL:
                                            $upload_error = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                                            break;
                                    }
                                }

                                if ($_FILES['lampiran_' . $i]['size'] > 0) {
                                    $this->upload->initialize($config, true);
                                    if ($this->upload->do_upload('lampiran_' . $i)) {
                                        $upload_data = $this->upload->data();
                                        $uploaded_lampiran[$i] = TR_BOOKING_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                        $data['penginapan'][$i]['lampiran'] = TR_BOOKING_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                    } else {
                                        $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. ' . $this->upload->display_errors('', '');
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            $upload_error[] = $e->getMessage();
                        }

                        if (count($upload_error) > 0) {
                            foreach ($uploaded_lampiran as $lampiran) {
                                unlink(TR_PATH_FILE . $lampiran);
                            }
                            if (count($upload_error) > 0)
                                $msg = join('<br/>', $upload_error);
                            else
                                $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            echo json_encode($return);
                            return;
                        }
                    }

                    $penginapans = $data['penginapan'];
                    if ($penginapans[0]['id_travel_hotel'] != "") {
                        foreach ($penginapans as $penginapan) {
                            $penginapan['id_travel_header'] = $id;
                            $penginapan['id_travel_detail'] = $this->generate->kirana_decrypt($penginapan['id_travel_detail']);

                            $penginapan['start_date'] = $this->generate->regenerateDateFormat($penginapan['start_date']);
                            $penginapan['end_date'] = $this->generate->regenerateDateFormat($penginapan['end_date']);

                            if (isset($penginapan['id_travel_hotel']) && !empty($penginapan['id_travel_hotel'])) {
                                $idHotel = $this->generate->kirana_decrypt($penginapan['id_travel_transport']);
                                unset($penginapan['id_travel_hotel']);
                                $dataBooking = $this->dgeneral->basic_column('update', $penginapan);
                                $this->db->update(
                                    'tbl_travel_transport',
                                    $dataBooking,
                                    array(
                                        'id_travel_hotel' => $idHotel
                                    )
                                );
                            } else {
                                unset($penginapan['id_travel_hotel']);
                                $dataBooking = $this->dgeneral->basic_column('insert_full', $penginapan);
                                $this->db->insert(
                                    'tbl_travel_hotel',
                                    $dataBooking
                                );
                            }
                        }
                    }
                }

                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Booking berhasil disimpan";
                    $sts = "OK";

                    if ($sendEmail) {
                        try {
                            $result = $this->send_approval_email_spd($pengajuan);
                            if ($result['sts'] == "NotOK")
                                $sendEmailResult = false;
                        } catch (Exception $exception) {
                            $sendEmailResult = false;
                        }
                    }
                }
            }
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_transport_options($data = null)
    {
        $this->general->connectDbPortal();

        try {
            if (isset($data)) {
                $duplicate = false;
                if (isset($data['id_travel_transport_options']) && !empty($data['id_travel_transport_options'])) {
                    $id = $this->generate->kirana_decrypt($data['id_travel_transport_options']);
                    $updateData = array(
                        'persa_from' => $data['persa_from'],
                        'persa_to' => $data['persa_to'],
                        'transports' => join(',', $data['transport']),
                    );
                    $updateData = $this->dgeneral->basic_column('update', $updateData);

                    $this->db->update(
                        'tbl_travel_transport_options',
                        $updateData,
                        array(
                            'id_travel_transport_options' => $id
                        )
                    );
                } else {

                    $checkData = $this->dspd->get_travel_transport_options(
                        array(
                            'persa_from' => $data['persa_from'],
                            'persa_to' => $data['persa_to'],
                            'single' => true
                        )
                    );
                    if (isset($checkData)) {
                        $duplicate = true;
                    } else {
                        $insertData = array(
                            'persa_from' => $data['persa_from'],
                            'persa_to' => $data['persa_to'],
                            'transports' => join(',', $data['transport']),
                        );
                        $insertData = $this->dgeneral->basic_column('insert_full', $insertData);

                        $this->db->insert(
                            'tbl_travel_transport_options',
                            $insertData
                        );
                    }
                }

                /** Error handler DB Transaction */
                if ($duplicate) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan. Data sudah ada didatabase";
                    $sts = "NotOK";
                } else if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil disimpan.";
                    $sts = "OK";
                }
            } else {
                $msg = "Data yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_transport_master($data = null)
    {
        $this->general->connectDbPortal();

        try {
            if (isset($data)) {
                $duplicate = false;
                $data['kode'] = url_title($data['nama'], '_', true);

                if (isset($data['id_travel_transport_master']) && !empty($data['id_travel_transport_master'])) {
                    $id = $this->generate->kirana_decrypt($data['id_travel_transport_master']);
                    $updateData = array(
                        'kode' => $data['kode'],
                        'nama' => $data['nama'],
                        'jenis' => $data['jenis'],
                    );
                    $updateData = $this->dgeneral->basic_column('update', $updateData);

                    $this->db->update(
                        'tbl_travel_transport_master',
                        $updateData,
                        array(
                            'id_travel_transport_master' => $id
                        )
                    );
                } else {

                    $checkData = $this->dspd->get_travel_transport_master(
                        array(
                            'kode' => $data['kode'],
                            'jenis' => $data['jenis'],
                            'single' => true
                        )
                    );
                    if (isset($checkData)) {
                        $duplicate = true;
                    } else {
                        $insertData = array(
                            'kode' => $data['kode'],
                            'nama' => $data['nama'],
                            'jenis' => $data['jenis'],
                        );
                        $insertData = $this->dgeneral->basic_column('insert_full', $insertData);

                        $this->db->insert(
                            'tbl_travel_transport_master',
                            $insertData
                        );
                    }
                }

                /** Error handler DB Transaction */
                if ($duplicate) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan. Data sudah ada didatabase";
                    $sts = "NotOK";
                } else if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil disimpan.";
                    $sts = "OK";
                }
            } else {
                $msg = "Data yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_mess_options($data = null)
    {
        $this->general->connectDbPortal();

        try {
            if (isset($data)) {
                if (isset($data['id_travel_mess_option']) && !empty($data['id_travel_mess_option'])) {
                    $id = $this->generate->kirana_decrypt($data['id_travel_mess_option']);
                    $updateData = array(
                        'persa' => $data['persa'],
                        'available' => $data['available'],
                    );
                    $updateData = $this->dgeneral->basic_column('update', $updateData);

                    $this->db->update(
                        'tbl_travel_mess_options',
                        $updateData,
                        array(
                            'id_travel_mess_option' => $id
                        )
                    );
                } else {
                    $insertData = array(
                        'persa' => $data['persa'],
                        'available' => $data['available'],
                    );
                    $insertData = $this->dgeneral->basic_column('insert_full', $insertData);

                    $this->db->insert(
                        'tbl_travel_mess_options',
                        $insertData
                    );
                }

                /** Error handler DB Transaction */
                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil disimpan.";
                    $sts = "OK";
                }
            } else {
                $msg = "Data yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_costcenter_expenses($data = null)
    {
        $this->general->connectDbPortal();

        try {
            if (isset($data)) {

                if (isset($data['id_travel_costcenter_expense']) && !empty($data['id_travel_costcenter_expense'])) {
                    $id = $this->generate->kirana_decrypt($data['id_travel_costcenter_expense']);

                    $checkDuplicate = $this->dspd->get_travel_costcenter_expenses(array(
                        'kode_expense' => $data['kode_expense'],
                        'activity_type' => $data['activity'],
                        'domestik' => $data['domestik'],
                        'id_not' => $id
                    ));

                    $isDuplicate = count($checkDuplicate) > 0 ? true : false;

                    if (!$isDuplicate) {
                        $updateData = array(
                            'cost_center' => '.' . join('.', $data['cost_center']) . '.',
                            'kode_expense' => '.' . join('.', $data['kode_expense']) . '.',
                            'personal_area' => $data['personal_area'],
                            'activity_type' => '.' . join('.', $data['activity']) . '.',
                            'domestik' => $data['domestik'],
                        );
                        $updateData = $this->dgeneral->basic_column('update', $updateData);

                        $this->db->update(
                            'tbl_travel_costcenter_expenses',
                            $updateData,
                            array(
                                'id_travel_costcenter_expense' => $id
                            )
                        );
                    }
                } else {

                    $checkDuplicate = $this->dspd->get_travel_costcenter_expenses(array(
                        'kode_expense' => $data['kode_expense'],
                        'activity_type' => $data['activity'],
                        'domestik' => $data['domestik'],
                    ));

                    $isDuplicate = count($checkDuplicate) > 0 ? true : false;

                    if (!$isDuplicate) {
                        $insertData = array(
                            'cost_center' => '.' . join('.', $data['cost_center']) . '.',
                            'kode_expense' => '.' . join('.', $data['kode_expense']) . '.',
                            'personal_area' => $data['personal_area'],
                            'activity_type' => '.' . join('.', $data['activity']) . '.',
                            'domestik' => $data['domestik'],
                        );
                        $insertData = $this->dgeneral->basic_column('insert_full', $insertData);

                        $this->db->insert(
                            'tbl_travel_costcenter_expenses',
                            $insertData
                        );
                    }
                }

                /** Error handler DB Transaction */
                if ($isDuplicate) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan. Data sudah ada di database.";
                    $sts = "NotOK";
                } else if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil disimpan.";
                    $sts = "OK";
                }
            } else {
                $msg = "Data yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_costcenter_declare($data = null)
    {
        $this->general->connectDbPortal();

        try {
            if (isset($data)) {

                if (isset($data['id_travel_costcenter_declare']) && !empty($data['id_travel_costcenter_declare'])) {
                    $id = $this->generate->kirana_decrypt($data['id_travel_costcenter_declare']);

                    $checkDuplicate = $this->dspd->get_travel_costcenter_declare(array(
                        'kode_expense' => $data['kode_expense'],
                        'activity_type' => $data['activity'],
                        'domestik' => $data['domestik'],
                        'id_not' => $id
                    ));

                    $isDuplicate = count($checkDuplicate) > 0 ? true : false;

                    if (!$isDuplicate) {
                        $updateData = array(
                            'cost_center' => '.' . join('.', $data['cost_center']) . '.',
                            'kode_expense' => '.' . join('.', $data['kode_expense']) . '.',
                            'personal_area' => $data['personal_area'],
                            'activity_type' => '.' . join('.', $data['activity']) . '.',
                            'domestik' => $data['domestik'],
                            'day_min' => intval($data['day_min']),
                            'day_max' => intval($data['day_max']),
                            'total_min' => floatval($data['total_min']),
                            'total_max' => floatval($data['total_max']),
                            'auto_total' => $data['auto_total'],
                        );
                        $updateData = $this->dgeneral->basic_column('update', $updateData);

                        $this->db->update(
                            'tbl_travel_costcenter_declare',
                            $updateData,
                            array(
                                'id_travel_costcenter_declare' => $id
                            )
                        );
                    }
                } else {

                    $checkDuplicate = $this->dspd->get_travel_costcenter_declare(array(
                        'kode_expense' => $data['kode_expense'],
                        'activity_type' => $data['activity'],
                        'domestik' => $data['domestik'],
                    ));

                    $isDuplicate = count($checkDuplicate) > 0 ? true : false;

                    if (!$isDuplicate) {
                        $insertData = array(
                            'cost_center' => '.' . join('.', $data['cost_center']) . '.',
                            'kode_expense' => '.' . join('.', $data['kode_expense']) . '.',
                            'personal_area' => $data['personal_area'],
                            'activity_type' => '.' . join('.', $data['activity']) . '.',
                            'domestik' => $data['domestik'],
                            'day_min' => intval($data['day_min']),
                            'day_max' => intval($data['day_max']),
                            'total_min' => floatval($data['total_min']),
                            'total_max' => floatval($data['total_max']),
                            'auto_total' => $data['auto_total'],
                        );
                        $insertData = $this->dgeneral->basic_column('insert_full', $insertData);

                        $this->db->insert(
                            'tbl_travel_costcenter_declare',
                            $insertData
                        );
                    }
                }

                /** Error handler DB Transaction */
                if ($isDuplicate) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan. Data sudah ada di database.";
                    $sts = "NotOK";
                } else if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil disimpan.";
                    $sts = "OK";
                }
            } else {
                $msg = "Data yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_penerimaan_spd($data = null)
    {
        $this->general->connectDbPortal();

        try {
            if (!isset($data)) {
                $msg = "Tidak ada data yang disimpan";
                $sts = "NotOK";
            } else {
                $idDetail = $this->generate->kirana_decrypt($data['id_travel_detail']);
                $idHeader = $this->generate->kirana_decrypt($data['id_travel_header']);

                $pengajuan = $this->dspd->get_travel_header(
                    array(
                        'id' => $idHeader,
                        'single' => true
                    )
                );

                if (!isset($pengajuan)) {
                    $msg = "Tidak ada data yang disimpan";
                    $sts = "NotOK";
                } else {
                    $dataPenerimaan = array(
                        'pic_check' => 1,
                        'tanggal_pic_check' => date('Y-m-d H:i:s'),
                        'transport_pick' => $data['transport_pick'],
                        'mess_available' => $data['mess_available'],
                    );

                    $dataPenerimaan = $this->dgeneral->basic_column('update', $dataPenerimaan);

                    $this->db->update(
                        'tbl_travel_detail',
                        $dataPenerimaan,
                        array(
                            'id_travel_detail' => $idDetail
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Pengajuan berhasil dikonfirmasi";
                        $sts = "OK";

                        /** Send email notifikasi */
                        $sendEmailResult = true;
                        $dataSpd = $this->dspd->get_travel_header(array('id' => $idHeader, 'single' => true));
                        $detail = $this->dspd->get_travel_detail(
                            array(
                                'id' => $idDetail,
                                'single' => true
                            )
                        );
                        $detail = $this->lspd->proses_spd_list_detail($detail);
                        try {
                            $result = $this->send_penerimaan_email_spd($dataSpd, $detail);
                            if ($result['sts'] == "NotOK")
                                $sendEmailResult = false;
                        } catch (Exception $exception) {
                            $sendEmailResult = false;
                        }
                    }
                }
            }
        } catch (Error $e) {
            $msg = "Terjadi gagal proses simpan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_diskusi_spd($data = null)
    {
        $this->general->connectDbPortal();
        $discusses = array();
        try {
            if (isset($data)) {
                $id = $this->generate->kirana_decrypt($data['id']);
                $nik = base64_decode($this->session->userdata("-nik-")) . ".";

                $diskusiData = array(
                    'id_travel_header' => $id,
                    'comment' => $data['comment'],
                    'status_read' => $nik
                );
                $upload_error = null;

                if (isset($_FILES['lampiran']) and $_FILES['lampiran']['size'] > 0) {
                    if ($_FILES['lampiran']['error'] != 0) {
                        switch ($_FILES['lampiran']['error']) {
                            case UPLOAD_ERR_INI_SIZE:
                                $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                break;
                            case UPLOAD_ERR_FORM_SIZE:
                                $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                break;
                            case UPLOAD_ERR_PARTIAL:
                                $upload_error = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                                break;
                            case UPLOAD_ERR_EXTENSION:
                                $upload_error = 'Upload berkas dihentikan oleh ekstensi. Harap pilih file lain.';
                                break;
                            default:
                                $upload_error = "Error upload gambar.";
                                break;
                        }
                    } else {
                        $uploaddir = TR_PATH_FILE . TR_CHAT_UPLOAD_FOLDER;
                        if (!file_exists($uploaddir)) {
                            mkdir($uploaddir, 0777, true);
                        }

                        $config['upload_path'] = $uploaddir;
                        $config['allowed_types'] = TR_UPLOAD_ALLOWED;
                        $config['max_size'] = TR_UPLOAD_MAX;

                        $this->load->library('upload', $config);

                        $filename = "C_" . $id . "_" . date('YmdHis') . "_" . $_FILES['lampiran']['name'];
                        $_FILES['lampiran']['name'] = $filename;

                        if ($this->upload->do_upload('lampiran')) {
                            $upload_data = $this->upload->data();
                            $diskusiData['lampiran'] = TR_CHAT_UPLOAD_FOLDER . $upload_data['file_name'];
                        } else {
                            $upload_error = $this->upload->display_errors('', '.');
                        }
                    }
                }

                $this->dgeneral->begin_transaction();

                $diskusiData = $this->dgeneral->basic_column('insert_simple', $diskusiData);
                $this->db->insert(
                    'tbl_travel_discuss',
                    $diskusiData
                );
                /** Error handler DB Transaction */
                if (isset($upload_error) || !empty($upload_error)) {
                    $this->dgeneral->rollback_transaction();
                    $msg = $upload_error;
                    $sts = "NotOK";
                } else if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil disimpan.";
                    $sts = "OK";
                }

                /** Load all discuss after insert new */
                $discusses = $this->get_discusses(array('id' => $id));
            } else {
                $msg = "Diskusi yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses simpan";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg, 'discusses' => $discusses);
        echo json_encode($return);
    }

    private function save_deklarasi2_spd($data = null)
    {
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();

        $dataUser = $this->general->get_data_user();
        $sendEmail = false;

        $userData = $this->data['user'];
        try {
            if (isset($data)) {
                $persa = $userData->persa;
                $asal = $this->dspd->get_travel_tujuan(
                    array(
                        'company_code' => $userData->btrtl,
                        'single' => true
                    )
                );
                $id_travel_deklarasi_header = null;
                if (isset($data['id_travel_deklarasi_header']) && !empty($data['id_travel_deklarasi_header']))
                    $id_travel_deklarasi_header = $this->generate->kirana_decrypt($data['id_travel_deklarasi_header']);

                if (isset($data['tipe_trip']) && !empty($data['tipe_trip'])) {
                    $tipe_trip = $data['tipe_trip'];

                    $approval = $this->dspd->get_approval();

                    $this->dgeneral->begin_transaction();
                    /** Multi Trip */
                    $trips = $data['detail'];
                    /** get first trip */
                    $firstTrip = reset($trips);

                    $multiData = array();

                    $multiData['tipe_travel'] = $firstTrip['country'] == 'ID' ? 'D' : 'O';
                    $multiData['jenis_tujuan'] = $firstTrip['country'] == 'ID' ? 'Domestik' : 'Luar Negeri';
                    $multiData['activity'] = $data['activity'];
                    $multiData['country'] = $firstTrip['country'];
                    $multiData['tujuan'] = $firstTrip['tujuan'];
                    $multiData['tujuan_persa'] = $firstTrip['tujuan_persa'];
                    $multiData['tujuan_lain'] = $firstTrip['tujuan_lain'];
                    $startDate = date_create($firstTrip['start']);
                    $multiData['start_date'] = $startDate->format('Y-m-d');
                    $multiData['start_time'] = $startDate->format('H:i:s');

                    if (isset($data['detail_end'])) {
                        $endDate = date_create($data['detail_end']);
                        $multiData['end_date'] = $endDate->format('Y-m-d');
                        $multiData['end_time'] = $endDate->format('H:i:s');
                    } else {
                        $multiData['end_date'] = null;
                        $multiData['end_time'] = null;
                    }

                    $multiData['keperluan'] = $firstTrip['keperluan'];
                    if (!isset($id_travel_deklarasi_header)) {
                        $sendEmail = true;
                        /** Create new travel header & detail */
                        $id_travel_header = $this->generate->kirana_decrypt($data['id_travel_header']);
                        $insertHeaderData = array_merge(
                            $this->dgeneral->basic_column('insert_full'),
                            array(
                                'id_travel_header'         => $id_travel_header,
                                'no_trip'                 => $data['no_trip'],
                                'total_um'                 => $this->generate->revert_rupiah($data['total_um']),
                                'status_transportasi'     => $data['status_transportasi'],
                                'activity' => $data['activity'],
                                'approval_level' => TR_LEVEL_1,    //1
                                'approval_status' => TR_STATUS_MENUNGGU,    //0
                                'approval_nik' => $approval['nik_atasan'],
                                'nik' => $userData->nik,
                                'persa' => $persa,
                                'no_hp' => $data['no_hp'],
                                'kota_asal' => $asal->kota,
                                'tipe_travel' => $multiData['tipe_travel'],
                                'tipe_trip' => $data['tipe_trip'],
                                'country' => $multiData['country'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $multiData['tujuan'],
                                'tujuan_persa' => $multiData['tujuan_persa'],
                                'tujuan_lain' => $multiData['tujuan_lain'],
                                'start_date' => $multiData['start_date'],
                                'start_time' => $multiData['start_time'],
                                'end_date' => $multiData['end_date'],
                                'end_time' => $multiData['end_time'],
                                'keperluan' => $multiData['keperluan'],
                                'total_biaya' => $this->generate->revert_rupiah($data['total_biaya']),
                                'total_bayar' => $this->generate->revert_rupiah($data['total_bayar']),
                                'employee_group' => $this->data['user']->ho === 'y' ? 'H' : 'F',

                            )
                        );

                        $insertHeaderData['id_travel_header'] = $id_travel_header;

                        $details            = array();
                        $transportasi_arr   = array();
                        $penginapan_arr     = array();

                        foreach ($trips as $i => $trip) {
                            $trip['id_travel_header'] = $id_travel_header;
                            $startDateTrip = date_create($trip['start']);
                            $trip['start_date'] = $startDateTrip->format('Y-m-d');
                            $trip['start_time'] = $startDateTrip->format('H:i:s');
                            $trip['end_date'] = $multiData['end_date'];
                            $trip['end_time'] = $multiData['end_time'];

                            $nextTrip = null;
                            if (isset($trips[$i + 0])) {
                                $nextTrip = $trips[$i + 0];

                                $endDateTrip = date_create($nextTrip['start']);
                                $trip['end_date'] = $endDateTrip->format('Y-m-d');
                                $trip['end_time'] = $endDateTrip->format('H:i:s');
                            }

                            $trip['tipe_travel'] = $trip['country'] == 'ID' ? 'D' : 'O';

                            $updateDetailData = array(
                                'id_travel_header' => $trip['id_travel_header'],
                                'activity' => $multiData['activity'],
                                'country' => $trip['country'],
                                'tipe_travel' => $trip['tipe_travel'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $trip['tujuan'],
                                'tujuan_persa' => $trip['tujuan_persa'],
                                'tujuan_lain' => $trip['tujuan_lain'],
                                'start_date' => $trip['start_date'],
                                'start_time' => $trip['start_time'],
                                'end_date' => $trip['end_date'],
                                'end_time' => $trip['end_time'],
                                'no_urut' => $i,
                                'keperluan' => $trip['keperluan'],
                                'jenis_penginapan' => $trip['inap'],
                                'transportasi_tiket' => implode(',', $trip['tiket']),
                                'transportasi' => implode(',', $trip['trans']),
                            );

                            $updateDetailData = $this->dgeneral->basic_column('insert_full', $updateDetailData);

                            array_push($penginapan_arr, $trip['inap']);
                            array_push($transportasi_arr, implode(',', $trip['trans']));
                            array_push($details, $updateDetailData);
                        }

                        array_unique($penginapan_arr);
                        array_unique($transportasi_arr);

                        $insertHeaderData['jenis_penginapan'] = implode(',', $penginapan_arr);
                        $insertHeaderData['transportasi'] = implode(',', $transportasi_arr);
                        $insertHeaderData['booking_brgkt'] = 1;

                        /** Insert travel header */
                        $this->dgeneral->insert(
                            'tbl_travel_deklarasi_header',
                            $insertHeaderData
                        );
                        $idDeklarasiHeader = $this->db->insert_id();

                        /** Update/Insert travel detail */

                        $this->db->delete(
                            'tbl_travel_deklarasi_header_detail',
                            array(
                                'id_travel_header' => $id_travel_header
                            )
                        );
                        foreach ($details as $detail) {
                            $this->db->insert('tbl_travel_deklarasi_header_detail', $detail);
                        }
                    } else {
                        /** Update existing travel header & detail */
                        $id_travel_header = $this->generate->kirana_decrypt($data['id_travel_deklarasi_header']);
                        $idDeklarasiHeader = $this->generate->kirana_decrypt($data['idDeklarasiHeader']);
                        $updateHeaderData = array_merge(
                            $this->dgeneral->basic_column('update'),
                            array(
                                'activity' => $data['activity'],
                                'approval_level' => 1,
                                'approval_status' => 0,
                                'approval_nik' => $approval['nik_atasan'],
                                'nik' => $userData->nik,
                                'persa' => $persa,
                                'no_hp' => $data['no_hp'],
                                'kota_asal' => $asal->kota,
                                'tipe_travel' => $multiData['tipe_travel'],
                                'tipe_trip' => $data['tipe_trip'],
                                'country' => $multiData['country'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $multiData['tujuan'],
                                'tujuan_persa' => $multiData['tujuan_persa'],
                                'tujuan_lain' => $multiData['tujuan_lain'],
                                'start_date' => $multiData['start_date'],
                                'start_time' => $multiData['start_time'],
                                'end_date' => $multiData['end_date'],
                                'end_time' => $multiData['end_time'],
                                'keperluan' => $multiData['keperluan'],
                                'total_biaya' => $this->generate->revert_rupiah($data['total_biaya']),
                                'total_bayar' => $this->generate->revert_rupiah($data['total_bayar']),
                                'employee_group' => $this->data['user']->ho === 'y' ? 'H' : 'F',
                            )
                        );

                        $details            = array();
                        $transportasi_arr   = array();
                        $penginapan_arr     = array();

                        foreach ($trips as $i => $trip) {
                            $startDateTrip = date_create($trip['start']);
                            $trip['start_date'] = $startDateTrip->format('Y-m-d');
                            $trip['start_time'] = $startDateTrip->format('H:i:s');
                            $trip['end_date'] = $multiData['end_date'];
                            $trip['end_time'] = $multiData['end_time'];

                            $nextTrip = null;
                            if (isset($trips[$i + 1])) {
                                $nextTrip = $trips[$i + 1];

                                $endDateTrip = date_create($nextTrip['start']);
                                $trip['end_date'] = $endDateTrip->format('Y-m-d');
                                $trip['end_time'] = $endDateTrip->format('H:i:s');
                            }

                            $trip['tipe_travel'] = $trip['country'] == 'ID' ? 'D' : 'O';

                            $updateDetailData = array(
                                'id_travel_header' => $id_travel_header,
                                'activity' => $multiData['activity'],
                                'country' => $trip['country'],
                                'tipe_travel' => $trip['tipe_travel'],
                                'jenis_tujuan' => $multiData['jenis_tujuan'],
                                'tujuan' => $trip['tujuan'],
                                'tujuan_persa' => $trip['tujuan_persa'],
                                'tujuan_lain' => $trip['tujuan_lain'],
                                'start_date' => $trip['start_date'],
                                'start_time' => $trip['start_time'],
                                'end_date' => $trip['end_date'],
                                'end_time' => $trip['end_time'],
                                'no_urut' => $i,
                                'keperluan' => $trip['keperluan'],
                                'jenis_penginapan' => $trip['inap'],
                                'transportasi_tiket' => implode(',', $trip['tiket']),
                                'transportasi' => implode(',', $trip['trans']),
                            );

                            $inap = explode(',', $trip['inap']);
                            $updateDetailData = $this->dgeneral->basic_column('insert_full', $updateDetailData);

                            array_push($penginapan_arr, $trip['inap']);
                            array_push($transportasi_arr, implode(',', $trip['trans']));
                            array_push($details, $updateDetailData);
                        }

                        /** Collect id detail untuk di exclude penghapusan detail  */
                        $detailIds = array_reduce($details, function ($all, $current) {
                            if (isset($current['id']))
                                array_push($all, $current['id']);
                            return $all;
                        }, array());

                        // add ayy
                        array_unique($penginapan_arr);
                        array_unique($transportasi_arr);
                        $insertHeaderData['jenis_penginapan'] = implode(',', $penginapan_arr);
                        $insertHeaderData['transportasi'] = implode(',', $transportasi_arr);

                        /** Update travel header */
                        $this->dgeneral->update(
                            'tbl_travel_deklarasi_header',
                            $updateHeaderData,
                            array(
                                array('kolom' => 'id_travel_header', 'value' => $id_travel_header)
                            )
                        );

                        /** Delete detail yang tidak terupdate */
                        $this->db->delete(
                            'tbl_travel_deklarasi_header_detail',
                            array(
                                'id_travel_header' => $id_travel_header
                            )
                        );
                        /** Insert travel detail */
                        foreach ($details as $detail) {
                            $this->db->insert('tbl_travel_deklarasi_header_detail', $detail);
                        }
                    }
                    // }

                    //save biaya dari sini
                    if (isset($data['biaya'])) {

                        $uploaded_lampiran = array();
                        $upload_error = null;
                        if (isset($_FILES)) {
                            $uploaddir = TR_PATH_FILE . TR_DEKLARASI_UPLOAD_FOLDER;
                            if (!file_exists($uploaddir)) {
                                mkdir($uploaddir, 0777, true);
                            }

                            $uploaded_gambar = null;

                            $lampirans = $_FILES['biaya'];

                            $config['upload_path'] = $uploaddir;
                            $config['allowed_types'] = TR_UPLOAD_ALLOWED;
                            $config['max_size'] = TR_UPLOAD_MAX;
                            $config['mod_mime_fix'] = false;

                            $this->load->library('upload', $config);

                            try {
                                foreach ($lampirans['name'] as $i => $lampiran) {
                                    $_FILES['lampiran_' . $i]['name'] = date('YmdHis') . '_' . $id_travel_header . "_" . urlencode($lampirans['name'][$i]['lampiran']);
                                    $_FILES['lampiran_' . $i]['type'] = $lampirans['type'][$i]['lampiran'];
                                    $_FILES['lampiran_' . $i]['tmp_name'] = $lampirans['tmp_name'][$i]['lampiran'];
                                    $_FILES['lampiran_' . $i]['error'] = $lampirans['error'][$i]['lampiran'];
                                    $_FILES['lampiran_' . $i]['size'] = $lampirans['size'][$i]['lampiran'];

                                    if ($_FILES['lampiran_' . $i]['error'] != 0) {
                                        switch ($_FILES['lampiran_' . $i]['error']) {
                                            case UPLOAD_ERR_INI_SIZE:
                                                $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                                break;
                                            case UPLOAD_ERR_EXTENSION:
                                                $upload_error[] = 'Lampiran ke ' . ($i + 1) . '.Jenis berkas yang Anda coba untuk mengunggah tidak diperbolehkan.';
                                                break;
                                        }
                                    }

                                    if ($_FILES['lampiran_' . $i]['size'] > 0) {
                                        $this->upload->initialize($config, true);
                                        if ($this->upload->do_upload('lampiran_' . $i)) {
                                            $upload_data = $this->upload->data();
                                            $uploaded_lampiran[$i] = TR_DEKLARASI_UPLOAD_FOLDER
                                                . $upload_data['file_name'];
                                            $data['biaya'][$i]['lampiran'] = TR_DEKLARASI_UPLOAD_FOLDER
                                                . $upload_data['file_name'];
                                        } else {
                                            $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. ' . $this->upload->display_errors('', '');
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                $upload_error[] = $e->getMessage();
                            }

                            if (count($upload_error) > 0) {
                                foreach ($uploaded_lampiran as $lampiran) {
                                    unlink(TR_PATH_FILE . $lampiran);
                                }
                                if (count($upload_error) > 0)
                                    $msg = join('<br/>', $upload_error);
                                else
                                    $msg = "Periksa kembali data yang dimasukkan";
                                $sts = "NotOK";

                                $return = array('sts' => $sts, 'msg' => $msg);
                                echo json_encode($return);
                                return;
                            }
                        }

                        $biayas = $data['biaya'];

                        $updatedBiaya = array();

                        foreach ($biayas as $biaya) {
                            $dataBiaya = array(
                                'id_travel_deklarasi_header' => $idDeklarasiHeader,
                                'tanggal' => $this->generate->regenerateDateFormat($biaya['tanggal']),
                                'kode_expense' => $biaya['biaya'],
                                'keterangan' => $biaya['keterangan'],
                                'jumlah' => $this->generate->revert_rupiah($biaya['jumlah']),
                                'currency' => $biaya['currency'],
                                'lampiran' => @$biaya['lampiran'],
                                'rate' => $this->generate->revert_rupiah($biaya['rate']),
                            );

                            if (isset($biaya['id']) && !empty($biaya['id'])) {
                                $idDeklarasiDetail = $this->generate->kirana_decrypt($biaya['id']);
                                $dataBiaya = $this->dgeneral->basic_column('update', $dataBiaya);
                                $this->db->update(
                                    'tbl_travel_deklarasi_detail',
                                    $dataBiaya,
                                    array(
                                        'id_travel_deklarasi_detail' => $idDeklarasiDetail
                                    )
                                );
                                $updatedBiaya[] = $idDeklarasiDetail;
                            } else {
                                $dataBiaya = $this->dgeneral->basic_column('insert_full', $dataBiaya);
                                $this->db->insert(
                                    'tbl_travel_deklarasi_detail',
                                    $dataBiaya
                                );
                                $updatedBiaya[] = $this->db->insert_id();
                            }
                        }

                        $this->db->where_not_in('id_travel_deklarasi_detail', $updatedBiaya);
                        $this->db->delete(
                            'tbl_travel_deklarasi_detail',
                            array(
                                'id_travel_deklarasi_header' => $idDeklarasiHeader
                            )
                        );
                    }
                    //save biaya sampe sini

                    // cuti pengganti
                    // delete cuti pengganti
                    $data_delete = $this->dgeneral->basic_column('delete', NULL, $datetime);
                    $this->dgeneral->update("tbl_travel_cuti_pengganti", $data_delete, array(
                        array(
                            'kolom' => 'id_travel_deklarasi',
                            'value' => $idDeklarasiHeader
                        ),
                        array(
                            'kolom' => 'id_travel_header',
                            'value' => $id_travel_header
                        )
                    ));

                    if (isset($data['cuti_add'])) {
                        foreach ($data['cuti_add'] as $index => $cuti) {
                            $data_cuti = array(
                                "id_travel_deklarasi" => $idDeklarasiHeader,
                                "id_travel_header" => $id_travel_header,
                                "tanggal_cuti" => $this->generate->regenerateDateFormat($data['tanggal_cuti_add'][$index]),
                                "keterangan" => trim($cuti),
                            );
                            $data_cuti = $this->dgeneral->basic_column("insert", $data_cuti);
                            $this->dgeneral->insert("tbl_travel_cuti_pengganti", $data_cuti);
                        }
                    }

                    /** Store Travel Log */
                    $travelLogSaved = true;
                    $lastLog = $this->dspd->get_travel_deklarasi_log_status(
                        array(
                            'id_travel_header' => $id_travel_header,
                            'single' => true
                        )
                    );
                    $travelLogging = false;
                    if (isset($lastLog)) {
                        if (
                            $lastLog->approval_level !== TR_LEVEL_1
                            || $lastLog->approval_status !== TR_STATUS_MENUNGGU
                        ) {
                            $travelLogging = true;
                        }
                    } else {
                        $travelLogging = true;
                    }

                    $dataDeklarasiHeader = array(
                        'approval_level' => TR_LEVEL_1,
                        'approval_status' => TR_STATUS_MENUNGGU,
                        'approval_nik' => $approval['nik_atasan'],
                        'total_biaya' => $this->generate->revert_rupiah($data['total_biaya']),
                        'total_bayar' => $this->generate->revert_rupiah($data['total_bayar']),
                    );
                    if ($travelLogging) {
                        $travelLogSaved = $this->lspd->travel_deklarasi_log(array(
                            'id_travel_header' => $id_travel_header,
                            'data' => $dataDeklarasiHeader,
                            'actor' => $dataUser->nik,
                            'action' => 'pengajuan',
                            'remark' => 'new',
                        ));
                    }

                    /** Error handler DB Transaction */
                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else if (!$travelLogSaved) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Terjadi kesalahan pada sistem, silahkan hubungi admin (IT Staff Kirana). ERR: LOG";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Deklarasi SPD berhasil disimpan.";
                        $sts = "OK";

                        // /** Send email notifikasi */
                        // $sendEmailResult = true;
                        // $data = $this->dspd->get_travel_header(array('id' => $id_travel_header, 'single' => true));

                        // if ($sendEmail) {
                        // try {
                        // $result = $this->send_approval_email_spd($data);
                        // if ($result['sts'] == "NotOK")
                        // $sendEmailResult = false;
                        // } catch (Exception $exception) {
                        // $sendEmailResult = false;
                        // }
                        // }
                    }
                } else {
                    $msg = "Data yang disimpan tidak ada";
                    $sts = "NotOK";
                }
            } else {
                $msg = "Data yang disimpan tidak ada";
                $sts = "NotOK";
            }
        } catch (Exception $e) {
            $msg = "Terjadi gagal proses";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_deklarasi_spd($data = null)
    {
        $dataUser = $this->general->get_data_user();
        $sendEmail = false;

        if (isset($data['id_travel_header'])) {
            $idHeader = $this->generate->kirana_decrypt($data['id_travel_header']);

            $this->general->connectDbPortal();

            $pengajuan = $this->dspd->get_travel_header(
                array(
                    'id' => $idHeader,
                    'single' => true
                )
            );

            if (!isset($pengajuan)) {
                $msg = "Tidak ada data spd yang akan dibuatkan deklarasi";
                $sts = "NotOK";
            } else {
                $this->dgeneral->begin_transaction();
                $upload_error = array();

                $approval = $this->dspd->get_approval();

                if (isset($data['id_travel_deklarasi_header']) && !empty($data['id_travel_deklarasi_header'])) {
                    $idDeklarasiHeader = $this->generate->kirana_decrypt($data['id_travel_deklarasi_header']);
                    $dataDeklarasiHeader = array(
                        'approval_level' => TR_LEVEL_1,
                        'approval_status' => TR_STATUS_MENUNGGU,
                        'approval_nik' => $approval['nik_atasan'],
                        'total_biaya' => $this->generate->revert_rupiah($data['total_biaya']),
                        'total_bayar' => $this->generate->revert_rupiah($data['total_bayar']),
                    );

                    $dataDeklarasiHeader = $this->dgeneral->basic_column('update', $dataDeklarasiHeader);

                    $this->db->update(
                        'tbl_travel_deklarasi_header',
                        $dataDeklarasiHeader,
                        array('id_travel_deklarasi_header' => $idDeklarasiHeader)
                    );
                } else {
                    $sendEmail = true;
                    $dataDeklarasiHeader = array(
                        'id_travel_header' => $idHeader,
                        'approval_level' => TR_LEVEL_1,
                        'approval_status' => TR_STATUS_MENUNGGU,
                        'approval_nik' => $approval['nik_atasan'],
                        'total_biaya' => $this->generate->revert_rupiah($data['total_biaya']),
                        'total_bayar' => $this->generate->revert_rupiah($data['total_bayar']),
                        'employee_group' => $this->data['user']->ho === 'y' ? 'H' : 'F',
                    );

                    $dataDeklarasiHeader = $this->dgeneral->basic_column('insert_full', $dataDeklarasiHeader);

                    $this->db->insert('tbl_travel_deklarasi_header', $dataDeklarasiHeader);

                    $idDeklarasiHeader = $this->db->insert_id();
                }

                /** Save detail update */
                if (isset($data['detail'])) {

                    $details = $data['detail'];

                    $jadwal = date_create($data['multi_end']);

                    $tanggalKembali = $jadwal->format('Y-m-d');
                    $jamKembali = $jadwal->format('H:i:s');

                    $lastDetail = end($details);

                    foreach ($details as $detail) {
                        if (isset($transport['id'])) {
                            $idDetail = $this->generate->kirana_decrypt($detail['id']);

                            $dataDetail = array(
                                'keperluan' => $detail['keperluan']
                            );
                            if ($detail['id'] === $lastDetail['id']) {
                                $dataDetail['end_date'] = $tanggalKembali;
                                $dataDetail['end_time'] = $jamKembali;
                            }

                            $dataDetail = $this->dgeneral->basic_column(
                                'update',
                                $dataDetail
                            );

                            $this->db->update(
                                'tbl_travel_detail',
                                $dataDetail,
                                array(
                                    'id_travel_detail' => $idDetail
                                )
                            );
                        }
                    }
                }

                if (isset($data['biaya'])) {

                    $uploaded_lampiran = array();

                    if (isset($_FILES)) {
                        $uploaddir = TR_PATH_FILE . TR_DEKLARASI_UPLOAD_FOLDER;
                        if (!file_exists($uploaddir)) {
                            mkdir($uploaddir, 0777, true);
                        }

                        $uploaded_gambar = null;

                        $lampirans = $_FILES['biaya'];

                        $config['upload_path'] = $uploaddir;
                        $config['allowed_types'] = TR_UPLOAD_ALLOWED;
                        $config['max_size'] = TR_UPLOAD_MAX;
                        $config['mod_mime_fix'] = false;

                        $this->load->library('upload', $config);

                        try {
                            foreach ($lampirans['name'] as $i => $lampiran) {
                                $_FILES['lampiran_' . $i]['name'] = date('YmdHis') . '_' . $idHeader . "_" . urlencode($lampirans['name'][$i]['lampiran']);
                                $_FILES['lampiran_' . $i]['type'] = $lampirans['type'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['tmp_name'] = $lampirans['tmp_name'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['error'] = $lampirans['error'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['size'] = $lampirans['size'][$i]['lampiran'];

                                if ($_FILES['lampiran_' . $i]['error'] != 0) {
                                    switch ($_FILES['lampiran_' . $i]['error']) {
                                        case UPLOAD_ERR_INI_SIZE:
                                            $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                            break;
                                        case UPLOAD_ERR_EXTENSION:
                                            $upload_error[] = 'Lampiran ke ' . ($i + 1) . '.Jenis berkas yang Anda coba untuk mengunggah tidak diperbolehkan.';
                                            break;
                                    }
                                }

                                if ($_FILES['lampiran_' . $i]['size'] > 0) {
                                    $this->upload->initialize($config, true);
                                    if ($this->upload->do_upload('lampiran_' . $i)) {
                                        $upload_data = $this->upload->data();
                                        $uploaded_lampiran[$i] = TR_DEKLARASI_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                        $data['biaya'][$i]['lampiran'] = TR_DEKLARASI_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                    } else {
                                        $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. ' . $this->upload->display_errors('', '');
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            $upload_error[] = $e->getMessage();
                        }

                        if (count($upload_error) > 0) {
                            foreach ($uploaded_lampiran as $lampiran) {
                                unlink(TR_PATH_FILE . $lampiran);
                            }
                            if (count($upload_error) > 0)
                                $msg = join('<br/>', $upload_error);
                            else
                                $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            echo json_encode($return);
                            return;
                        }
                    }

                    $biayas = $data['biaya'];

                    $updatedBiaya = array();

                    foreach ($biayas as $biaya) {
                        $dataBiaya = array(
                            'id_travel_deklarasi_header' => $idDeklarasiHeader,
                            'tanggal' => $this->generate->regenerateDateFormat($biaya['tanggal']),
                            'kode_expense' => $biaya['biaya'],
                            'keterangan' => $biaya['keterangan'],
                            'jumlah' => $this->generate->revert_rupiah($biaya['jumlah']),
                            'currency' => $biaya['currency'],
                            'lampiran' => @$biaya['lampiran'],
                        );

                        if (isset($biaya['id']) && !empty($biaya['id'])) {
                            $idDeklarasiDetail = $this->generate->kirana_decrypt($biaya['id']);
                            $dataBiaya = $this->dgeneral->basic_column('update', $dataBiaya);
                            $this->db->update(
                                'tbl_travel_deklarasi_detail',
                                $dataBiaya,
                                array(
                                    'id_travel_deklarasi_detail' => $idDeklarasiDetail
                                )
                            );
                            $updatedBiaya[] = $idDeklarasiDetail;
                        } else {
                            $dataBiaya = $this->dgeneral->basic_column('insert_full', $dataBiaya);
                            $this->db->insert(
                                'tbl_travel_deklarasi_detail',
                                $dataBiaya
                            );
                            $updatedBiaya[] = $this->db->insert_id();
                        }
                    }

                    $this->db->where_not_in('id_travel_deklarasi_detail', $updatedBiaya);
                    $this->db->delete(
                        'tbl_travel_deklarasi_detail',
                        array(
                            'id_travel_deklarasi_header' => $idDeklarasiHeader
                        )
                    );
                }

                /** Store Travel Log */
                $travelLogSaved = true;
                $lastLog = $this->dspd->get_travel_deklarasi_log_status(
                    array(
                        'id_travel_header' => $idHeader,
                        'single' => true
                    )
                );
                $travelLogging = false;
                if (isset($lastLog)) {
                    if (
                        $lastLog->approval_level !== TR_LEVEL_1
                        || $lastLog->approval_status !== TR_STATUS_MENUNGGU
                    ) {
                        $travelLogging = true;
                    }
                } else {
                    $travelLogging = true;
                }
                if ($travelLogging) {
                    $travelLogSaved = $this->lspd->travel_deklarasi_log(array(
                        'id_travel_header' => $idHeader,
                        'data' => $dataDeklarasiHeader,
                        'actor' => $dataUser->nik,
                        'action' => 'pengajuan',
                        'remark' => 'new',
                    ));
                }

                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else if (!$travelLogSaved) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Terjadi kesalahan pada sistem, silahkan hubungi admin (IT Staff Kirana). ERR: LOG";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Deklarasi berhasil disimpan";
                    $sts = "OK";

                    // /** Send email notifikasi */
                    // $sendEmailResult = true;
                    // $data = $this->dspd->get_travel_header(array('id' => $idHeader, 'single' => true));
                    // $dataDeklarasi = $this->dspd->get_travel_deklarasi_header(array('id' => $idHeader, 'single' => true));
                    // if ($sendEmail) {
                    // try {
                    // $result = $this->send_deklarasi_email_spd($data, $dataDeklarasi);
                    // if ($result['sts'] == "NotOK")
                    // $sendEmailResult = false;
                    // } catch (Exception $exception) {
                    // $sendEmailResult = false;
                    // }
                    // }

                }
            }
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    /** Emailing proses */
    private function send_approval_email_spd($data = null, $action = 'pengajuan')
    {
        if (isset($data)) {
            $data = $this->lspd->proses_spd_list($data, 'persetujuan');
            $approval = $this->dspd->get_approval(
                array(
                    'nik' => $data->nik,
                    'level' => $data->approval_level,
                )
            );

            $email_tujuan = array();
            $email_tujunarr = [];
            foreach ($approval['list_atasan_email'] as $emtujuan) {
                $email_tujuan[] = $emtujuan;
                $email_tujunarr[] = $emtujuan;
            }

            $email_original = $approval['list_atasan_email'];
            // $email_tujuan = TR_EMAIL_DEBUG == false ? $approval['list_atasan_email'] : json_decode(TR_EMAIL_TESTER);;
            // $email_tujuan = TR_EMAIL_DEBUG == false ? $email_tujuan : json_decode(TR_EMAIL_TESTER);
            $email_tujuan = TR_EMAIL_DEBUG == false ? $email_tujuan : json_decode(TR_EMAIL_TESTER);
            if (($action === 'disapprove' || $action === 'revise') && TR_EMAIL_DEBUG == false) {
                // $email_tujuan = $data->email_karyawan.", ";
                $email_tujuan[] = $data->email_karyawan;
            }

            if (!empty($email_tujuan)) {
                $email_tujuan = $email_tujuan;
            } else {
                $email_tujuan = $approval['email_user'];
            }

            if (in_array($action, array('approve', 'disapprove', 'revise'))) {
                $message = $this->load->view('emails/spd_persetujuan', compact('data', 'email_original'), true);
            } else {
                $message = $this->load->view('emails/spd_pengajuan', compact('data', 'email_original'), true);
            }

            // add email cc ayy
            $datalog = $this->dspd->get_email_log(
                array(
                    'id_travel_header' => $data->id_travel_header,
                )
            );
            $arr_log = [];
            if (TR_EMAIL_DEBUG == false) {
                foreach ($datalog as $dtlog) {
                    if (!in_array($dtlog->email, $email_tujuan))
                        $arr_log[] = $dtlog->email;
                }
            } else {
                $arr_log = json_decode(TR_EMAIL_TESTER);
            }
            $email_log = array_unique($arr_log);

            $approval_email_result = $this->general->send_email_new(array(
                'subject'       => 'Pengajuan Perjalanan Dinas',
                'from_alias'    => 'e-Travel KiranaKu',
                'message'       => $message,
                'to'            => $email_tujuan,
                'cc'            => $email_log,
            ));
            return $approval_email_result;
        } else {
            $msg = "Tidak ada data pengajuan yang akan dikirimkan email";
            $sts = "NotOK";
            return compact('msg', 'sts');
        }
    }

    private function send_cancel_email_spd($data = null, $dataPembatalan = null, $action = 'pengajuan')
    {
        if (isset($data) && isset($dataPembatalan)) {
            $data = $this->lspd->proses_spd_list($data, 'persetujuan');
            $approval = $this->dspd->get_approval(
                array(
                    'nik' => $data->nik,
                    'level' => $data->approval_level,
                )
            );

            $email_original = $approval['list_atasan_email'];
            $email_tujuan = !TR_EMAIL_DEBUG ? $approval['list_atasan_email'] : json_decode(TR_EMAIL_TESTER);

            if (($action === 'disapprove' || $action === 'revise') && !TR_EMAIL_DEBUG) {
                $email_tujuan[] = $data->email_karyawan;
            }
            $message = $this->load->view('emails/spd_pembatalan', compact('data', 'dataPembatalan', 'email_tujuan', 'email_original'), true);
            $approval_email_result = $this->lspd->proses_send_email(array(
                'subject' => 'Pembatalan Perjalanan Dinas',
                'from_alias' => 'e-Travel KiranaKu',
                'message' => $message,
                'to' => $email_tujuan,
            ));
            return $approval_email_result;
        } else {
            $msg = "Tidak ada data pengajuan yang akan dikirimkan email";
            $sts = "NotOK";
            return compact('msg', 'sts');
        }
    }

    private function send_penerimaan_email_spd($data = null, $detail = null)
    {
        if (isset($data) && isset($detail)) {
            $data = $this->lspd->proses_spd_list($data, 'pengajuan');

            $email_original = $data->email_karyawan;
            $email_tujuan = !TR_EMAIL_DEBUG ? $data->email_karyawan : json_decode(TR_EMAIL_TESTER);;

            $transportasi = $this->dspd->get_travel_transport_master(
                array(
                    'kode' => $detail->transport_pick,
                    'jenis' => 'penjemputan',
                    'single' => true,
                )
            );
            $message = $this->load->view(
                'emails/spd_penerimaan',
                compact('data', 'detail', 'transportasi', 'email_original'),
                true
            );
            $approval_email_result = $this->lspd->proses_send_email(array(
                'subject' => 'Penerimaan Perjalanan Dinas',
                'from_alias' => 'e-Travel KiranaKu',
                'message' => $message,
                'to' => $email_tujuan,
            ));
            return $approval_email_result;
        } else {
            $msg = "Tidak ada data pengajuan yang akan dikirimkan email";
            $sts = "NotOK";
            return compact('msg', 'sts');
        }
    }

    private function send_deklarasi_email_spd($data = null, $dataDeklarasi = null, $action = 'pengajuan')
    {
        if (isset($data) && isset($dataDeklarasi)) {
            $data = $this->lspd->proses_spd_list($data, 'persetujuan');
            $approval = $this->dspd->get_approval(
                array(
                    'nik' => $data->nik,
                    'level' => $dataDeklarasi->approval_level,
                )
            );

            $email_original = $approval['list_atasan_email'];

            $message = $this->load->view('emails/spd_deklarasi', compact('data', 'dataDeklarasi', 'email_original'), true);

            $email_tujuan = array();
            $email_tujunarr = [];
            foreach ($approval['list_atasan_email'] as $emtujuan) {
                $email_tujuan[] = $emtujuan;
                $email_tujunarr[] = $emtujuan;
            }

            $email_tujuan = TR_EMAIL_DEBUG == false ? $email_tujuan : json_decode(TR_EMAIL_TESTER);
            if (($action === 'disapprove' || $action === 'revise') && TR_EMAIL_DEBUG == false) {
                $email_tujuan[] = $data->email_karyawan;
            }

            if (!empty($email_tujuan)) {
                $email_tujuan = $email_tujuan;
            } else {
                $email_tujuan = $approval['email_user'];
            }

            // add email cc ayy
            $datalog = $this->dspd->get_email_log(
                array(
                    'id_travel_header' => $data->id_travel_header,
                )
            );
            $arr_log = [];
            if (TR_EMAIL_DEBUG == false) {
                foreach ($datalog as $dtlog) {
                    if (!in_array($dtlog->email, $email_tujuan))
                        $arr_log[] = $dtlog->email;
                }
            } else {
                $arr_log = json_decode(TR_EMAIL_TESTER);
            }
            $email_log      = array_unique($arr_log);
            // $email_tujuan   = isset($email_tujuan) && $email_tujuan != ''? rtrim($email_tujuan , ', ') : null;
            // $email_log      = isset($email_log) && $email_log != ''      ? implode(",", $email_log)    : null;

            $approval_email_result = $this->lspd->proses_send_email(array(
                'subject'   => 'Deklarasi Perjalanan Dinas',
                'from_alias' => 'e-Travel KiranaKu',
                'message'   => $message,
                'to'        => $email_tujuan,
                'cc'        => $email_log,
            ));
            return $approval_email_result;
        } else {
            $msg = "Tidak ada data pengajuan yang akan dikirimkan email";
            $sts = "NotOK";
            return compact('msg', 'sts');
        }
    }

    private function delete_pengajuan_spd($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);
            $deletable = true;

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $dataPengajuan = $this->dspd->get_travel_header(array(
                'id' => $id,
                'single' => true
            ));

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update(
                'tbl_travel_header',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_header',
                        'value' => $id
                    )
                )
            );

            $this->dgeneral->update(
                'tbl_travel_detail',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_header',
                        'value' => $id
                    )
                )
            );

            $this->lspd->travel_log(
                array(
                    'id_travel_header' => $id,
                    'data' => $dataPengajuan,
                    'action' => 'pengajuan',
                    'remark' => 'delete',
                    'status' => TR_STATUS_DIHAPUS,
                    'actor' => $this->data['user']->nik,
                )
            );

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

    private function delete_pembatalan_spd($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);
            $deletable = true;

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $dataPembatalan = $this->dspd->get_travel_cancel(array(
                'id' => $id,
                'single' => true
            ));

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update(
                'tbl_travel_cancel',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_cancel',
                        'value' => $id
                    )
                )
            );

            $this->lspd->travel_log(
                array(
                    'id_travel_header' => $dataPembatalan->id_travel_header,
                    'data' => $dataPembatalan,
                    'action' => 'pembatalan',
                    'remark' => 'delete',
                    'status' => TR_STATUS_DIHAPUS,
                    'actor' => $this->data['user']->nik,
                )
            );

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

    private function delete_deklarasi_spd($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);
            $deletable = true;

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $dataDeklarasi = $this->dspd->get_travel_deklarasi_header(array(
                'id' => $id,
                'single' => true
            ));

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update(
                'tbl_travel_deklarasi_header',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_deklarasi_header',
                        'value' => $id
                    )
                )
            );
            $this->dgeneral->update(
                'tbl_travel_deklarasi_detail',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_deklarasi_header',
                        'value' => $id
                    )
                )
            );

            $this->lspd->travel_deklarasi_log(
                array(
                    'id_travel_header' => $dataDeklarasi->id_travel_header,
                    'data' => $dataDeklarasi,
                    'action' => 'pengajuan',
                    'remark' => 'delete',
                    'status' => TR_STATUS_DIHAPUS,
                    'actor' => $this->data['user']->nik,
                )
            );

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

    private function delete_mess_options($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update(
                'tbl_travel_mess_options',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_mess_option',
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

    private function delete_costcenter_expenses($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update(
                'tbl_travel_costcenter_expenses',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_costcenter_expense',
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

    private function delete_costcenter_declare($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update(
                'tbl_travel_costcenter_declare',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_costcenter_declare',
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

    private function delete_transport_master($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update(
                'tbl_travel_transport_master',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_transport_master',
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

    private function delete_transport_options($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update(
                'tbl_travel_transport_options',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_travel_transport_options',
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

    private function get_discusses($params = array())
    {
        $discusses = $this->dspd->get_travel_discuss(array('id' => $params['id']));
        $nik = base64_decode($this->session->userdata("-nik-"));

        foreach ($discusses as $discuss) {

            //update user_read tbl_leg_komentar
            if (count($discusses) != 0) {
                $arr_read    = explode('.', $discuss->status_read);
                if (in_array($nik, $arr_read) == false) {
                    $status_read   = $discuss->status_read . '' . $nik . '.';
                    $data_update = array(
                        "status_read"    => $status_read
                    );
                    $data_update = $this->dgeneral->basic_column("update", $data_update);
                    $this->dgeneral->update("tbl_travel_discuss", $data_update, array(
                        array(
                            'kolom' => 'id_travel_header',
                            'value' => $discuss->id_travel_header
                        )
                    ));
                }
            }

            if ($discuss->gender == "l") {
                $image = base_url() . "assets/apps/img/avatar5.png";
            } else {
                $image = base_url() . "assets/apps/img/avatar2.png";
            }

            if ($discuss->gambar) {
                $data_image = "http://kiranaku.kiranamegatara.com/home/" . strtolower($discuss->gambar);
                $headers = get_headers($data_image);
                if ($headers[0] == "HTTP/1.1 200 OK") {
                    $image = $data_image;
                } else {
                    $links = explode("/", $discuss->gambar);
                    $data_image = "http://kiranaku.kiranamegatara.com/home/" . $links[0] . "/" . $links[1] . "/" . strtoupper($links[2]);
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $image = $data_image;
                    }
                }
            }

            $discuss->me = false;
            if ($this->data['user']->nik == $discuss->nik)
                $discuss->me = true;

            if (isset($discuss->lampiran) && !empty($discuss->lampiran)) {
                $discuss->lampiran_r = explode('/', $discuss->lampiran)[1];
                $discuss->lampiran = site_url('assets/file/travel/' . $discuss->lampiran);
            }

            $discuss->gambar = $image;
            $discuss->comment = nl2br($discuss->comment);
        }
        return $discusses;
    }

    private function get_personel()
    {
        $personel = $this->dspd->get_karyawan(array('nik' => $this->data['user']->nik, 'single' => true));
        $result['data'] = compact('personel');
        $result['sts'] = 'OK';
        $result['msg'] = '';
        return $result;
    }

    private function vendor_transport($inputs)
    {

        $tipe   = isset($inputs['transport']) ? $inputs['transport'] : NULL;
        $vendor = $this->dspd->get_travel_merk_trans(array('transport' => $tipe));
        $result['data'] = compact('vendor');
        $result['sts'] = 'OK';
        $result['msg'] = '';
        return $result;
    }

    private function get_expenses($inputs)
    {
        $defaults = $this->lspd->get_default_expenses(
            array(
                'id_header'         => @$inputs['id_header'],
                'user'              => $this->data['user'],
                'country'           => $inputs['country'],
                'jenis_aktifitas'   => $inputs['jenis_aktifitas'],
                // 'company_code' => $inputs['company_code'],
                'kode_expense' => @$inputs['kode_expense'],
            )
        );
        $expenses = $this->lspd->get_all_expenses(
            array(
                'user' => $this->data['user'],
                'country' => $inputs['country'],
                'amount_type' => @$inputs['amount_type'],
                'kode_expense' => @$inputs['kode_expense'],
            )
        );
        $result['data'] = compact('defaults', 'expenses');
        $result['sts'] = 'OK';
        $result['msg'] = '';
        return $result;
    }

    private function get_expenses_value($inputs)
    {
        $defaults = $this->lspd->get_default_expenses(
            array(
                'id_header'         => @$inputs['id_header'],
                'user'              => $this->data['user'],
                'country'           => $inputs['country'],
                'jenis_aktifitas'   => $inputs['jenis_aktifitas'],
                // 'company_code' => $inputs['company_code'],
                'kode_expense' => $inputs['kode_expense'],
            )
        );
        $result['data'] = $defaults;
        $result['sts'] = 'OK';
        $result['msg'] = '';
        return $result;
    }

    private function get_tujuan($inputs)
    {
        $dataPabriks = array();
        $dataTujuans = array();
        $transportOptions = null;
        $penginapan = $this->lspd->get_penginapan_options(array(
            'user' => $this->data['user'],
            'persa' => @$inputs['personal_area']
        ));
        if ($inputs['country'] === 'ID') {
            $dataPabriks = $this->dspd->get_travel_pabrik(array(
                'activity' => @$inputs['activity']
            ));

            $dataTujuans = $this->dspd->get_travel_tujuan(
                array(
                    'personal_area' => @$inputs['personal_area'],
                    'activity' => @$inputs['activity'],
                )
            );
            /** Mapping label text persa dan persub, cek jika depo mitra dapat isi nama depo **/
            $dataTujuans = array_map(function ($tujuan) {
                if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                    $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                    $tujuan->label = $tujuanArray[0];
                    $tujuan->value = $tujuan->company_code;
                    $tujuan->free = true;
                } else {
                    $tujuan->label = $tujuan->personal_subarea_text;
                    $tujuan->value = $tujuan->company_code;
                    $tujuan->free = false;
                }
                return $tujuan;
            }, $dataTujuans);

            if (isset($inputs['personal_area'])) {
                $transports = $this->dspd->get_travel_transport_options(array(
                    'persa_from' => $this->data['user']->persa,
                    'persa_to' => $inputs['personal_area'],
                    'single' => true
                ));

                if (isset($transports))
                    $transportOptions = $transports->transports;
            }
        } else {
            $transportOptions = 'pesawat';
        }
        array_push($dataTujuans, array(
            'label' => 'Lain-lain',
            'value' => 'lain',
            'free' => true,
        ));

        $result['pabrik'] = $dataPabriks;
        $result['tujuan'] = $dataTujuans;
        $result['transport_options'] = $transportOptions;
        $result['penginapan'] = $penginapan;
        $result['sts'] = 'OK';
        $result['msg'] = '';

        return $result;
    }

    private function get_status_trip($param = NULL)
    {
        $this->connectSAP("ESS");
        $datetime = date("Y-m-d H:i:s");

        $post = $this->input->post(NULL, TRUE);
        $nik = $post['nik'];
        $no_trip = $post['no_trip'];

        $type = array();
        $message = array();
        $iserror = false;
        $status_trip = null;

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->dgeneral->begin_transaction();

        if ($this->data['sap']->getStatus() == SAPRFC_OK) {
            $param_rfc = array(
                array("IMPORT", "I_PERNR", $nik),
                array("IMPORT", "I_REINR", $no_trip),
                array("EXPORT", "E_RETURN", array()),
                array("EXPORT", "E_STATUS", array())
            );
            $result = $this->data['sap']->callFunction('Z_RFC_STATUS_TRIP', $param_rfc);

            //cek kalo ada error
            if (!empty($result["E_RETURN"])) {
                // foreach ($result["E_RETURN"] as $return) {
                //     if ("E" == $return['TYPE']) {
                //         $iserror = true;
                //         break;
                //     }
                // }
                if ("E" == $result["E_RETURN"])
                    $iserror = true;
            }

            if (
                $this->data['sap']->getStatus() == SAPRFC_OK
                && !empty($result["E_STATUS"])
                && !$iserror
            ) {
                $status_trip = $result["E_STATUS"];

                $data_row_log = array(
                    'app'           => 'DATA RFC CHECK STATUS TRIP',
                    'rfc_name'      => 'Z_RFC_STATUS_TRIP',
                    'log_code'      => 'S',
                    'log_status'    => 'Berhasil',
                    'log_desc'      => "Berhasil Cek Status Trip " . $no_trip . "/" . $nik . "status " . $status_trip,
                    'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                    'executed_date' => $datetime
                );

                $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
            } else {
                $msg_fail = array();
                $type_fail = array();
                if ($result["T_RETURN"]) {
                    foreach ($result["T_RETURN"] as $return) {
                        $type[]    = $return['TYPE'];
                        $message[] = $return['MESSAGE'];
                        $type_fail[] = $return['TYPE'];
                        $msg_fail[] = $return['MESSAGE'];
                    }
                } else {
                    $type[]    = 'E';
                    $message[] = $result;
                    $type_fail[] = 'E';
                    $msg_fail[] = $result;
                }
                $data_row_log = array(
                    'app'           => 'DATA RFC CHECK STATUS TRIP',
                    'rfc_name'      => 'Z_RFC_STATUS_TRIP',
                    'log_code'      => implode(" , ", $type_fail),
                    'log_status'    => 'Gagal',
                    'log_desc'      => "Get status IO Failed [T_RETURN]: " . implode(" , ", $msg_fail),
                    'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                    'executed_date' => $datetime
                );

                $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
            }
        } else {
            $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
            $data_row_log = array(
                'app'           => 'DATA RFC CHECK STATUS TRIP',
                'rfc_name'      => 'Z_RFC_STATUS_TRIP',
                'log_code'      => 'E',
                'log_status'    => 'Gagal',
                'log_desc'      => "Connecting Failed: " . $status,
                'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                'executed_date' => $datetime
            );

            $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
        }

        //================================SAVE ALL================================//
        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "OK";
            $sts = "OK";
            if (in_array('E', $type) === true) {
                $sts = "NotOK";
                $msg = implode(" , ", $message);
            }
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'status_trip' => $status_trip);
            return $return;
        } else {
            $return = array('sts' => $sts, 'msg' => $msg, 'status_trip' => $status_trip);
            return json_encode($return);
        }
    }

    private function get_data_pengajuan($param = NULL)
    {
        $idHeader = $param['id_header'];
        $pengajuan = $this->dspd->get_travel_header(
            array(
                'id' => $idHeader,
                'single' => true
            )
        );

        $pengajuan = $this->lspd->proses_spd_list_header($pengajuan);

        $pengajuan = $this->general->generate_encrypt_json($pengajuan, array('id_travel_header'));
        $opttransport = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $transports = $this->dspd->get_travel_transport(
            array(
                'id_header' => $idHeader,
            )
        );
        $transports = $this->general->generate_encrypt_json(
            $transports,
            array(
                'id_travel_header', 'id_travel_detail', 'id_travel_transport'
            )
        );
        if ($pengajuan->tipe_trip === 'single') {
            $details = $this->dspd->get_travel_detail(
                array(
                    'id_header' => $idHeader,
                    'single' => true
                )
            );
            $details = $this->lspd->proses_spd_list_detail($details);
        } else {
            $details = $this->dspd->get_travel_detail(
                array(
                    'id_header' => $idHeader,
                )
            );
            $lspd = $this->lspd;
            $details = array_map(function ($detail) use ($lspd) {
                return $lspd->proses_spd_list_detail($detail);
            }, $details);
        }
        $details = $this->general->generate_encrypt_json($details, array('id_travel_detail', 'id_travel_header'));
        $personel = $this->dspd->get_karyawan(array('nik' => $pengajuan->nik, 'single' => true));
        $downpayments = $this->dspd->get_travel_downpayment(
            array(
                'id_header' => $idHeader,
            )
        );
        $rencana_aktifitas = $this->dspd->get_rencana_aktifitas(
            array(
                "connect" => TRUE,
                'id_travel_header' => $idHeader,
                "encrypt" => array("id")
            )
        );

        $cancel = $this->dspd->get_travel_cancel(array(
            'id_header' => $idHeader,
            'single' => true,
        ));
        $cancel = $this->general->generate_encrypt_json($cancel, array('id_travel_cancel', 'id_travel_header'));

        $deklarasi = $this->dspd->get_travel_deklarasi_header(array(
            'id_travel_header' => $idHeader,
            'single' => true,
        ));
        if (isset($deklarasi)) {
            $deklarasi_details = $this->dspd->get_travel_deklarasi_detail(array(
                'id_travel_deklarasi_header' => $deklarasi->id_travel_deklarasi_header
            ));
            $deklarasi_details = $this->general->generate_encrypt_json($deklarasi_details, array('id_travel_deklarasi_header', 'id_travel_deklarasi_detail'));
        }
        $deklarasi = $this->general->generate_encrypt_json($deklarasi, array('id_travel_deklarasi_header', 'id_travel_header'));

        // add by ayy
        $optcountry = $this->dspd->get_countries();

        $optpabrik = $this->dspd->get_travel_pabrik(array(
            'activity' => @$param['activity']
        ));

        $opttujuan = $this->dspd->get_travel_tujuan(
            array(
                'personal_area' => @$param['personal_area'],
                'activity' => @$param['activity'],
            )
        );
        /** Mapping label text persa dan persub, cek jika depo mitra dapat isi nama depo **/
        $opttujuan = array_map(function ($tujuan) {
            if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                $tujuan->label = $tujuanArray[0];
                $tujuan->value = $tujuan->company_code;
                $tujuan->free = true;
            } else {
                $tujuan->label = $tujuan->personal_subarea_text;
                $tujuan->value = $tujuan->company_code;
                $tujuan->free = false;
            }
            return $tujuan;
        }, $opttujuan);

        $deklarasix = $this->dspd->get_travel_deklarasi_header(array(
            'id_travel_header' => $idHeader,
            'single' => true
        ));
        if (isset($deklarasix)) {
            $history = $this->dspd->get_travel_deklarasi_log_status(
                array(
                    'id_travel_header' => $idHeader,
                    'order_by' => 'tgl_status asc'
                )
            );
        } else {
            $history = $this->dspd->get_travel_log_status(
                array(
                    'id_travel_header' => $idHeader,
                    'order_by' => 'tgl_status asc'
                )
            );
        }
        $approval = $this->dspd->get_approval();
        //lha
        $expenses = $this->lspd->get_default_expenses(
            array(
                'id_header' => $this->generate->kirana_encrypt($idHeader),
                'user' => $this->data['user'],
                'country' => $pengajuan->country,
                'jenis_aktifitas' => $pengajuan->activity,
                'type' => 'declare'
            )
        );
        foreach ($expenses as &$expens) {
            $expens->value = floatval($expens->value) * 100;
        }

        $expenses_currency = $this->dspd->get_travel_tipeexpenses_currency();

        $result = compact(
            'pengajuan',
            'opttransport',
            'transports',
            'details',
            'personel',
            'downpayments',
            'cancel',
            'deklarasi',
            'deklarasi_details',
            'optcountry',
            'optpabrik',
            'opttujuan',
            'history',
            'approval',
            'expenses',
            'expenses_currency',
            'rencana_aktifitas'
        );
        return $result;
    }

    private function get_data_deklarasi($param = NULL)
    {
        $idHeader = $param['id_header'];
        //pengajuan
        $pengajuan = $this->dspd->get_travel_header(
            array(
                'id' => $idHeader,
                'single' => true
            )
        );
        $pengajuan = $this->lspd->proses_spd_list_header($pengajuan);
        $pengajuan = $this->general->generate_encrypt_json($pengajuan, array('id_travel_header'));
        //pengajuan-detail
        if ($pengajuan->tipe_trip === 'single') {
            $details = $this->dspd->get_travel_detail(
                array(
                    'id_header' => $idHeader,
                    'single' => true
                )
            );
            $details = $this->lspd->proses_spd_list_detail($details);
        } else {
            $details = $this->dspd->get_travel_detail(
                array(
                    'id_header' => $idHeader,
                )
            );
            $lspd = $this->lspd;
            $details = array_map(function ($detail) use ($lspd) {
                return $lspd->proses_spd_list_detail($detail);
            }, $details);
        }
        $details = $this->general->generate_encrypt_json($details, array('id_travel_detail', 'id_travel_header'));
        //untuk edit deklarasi
        //pengajuan_deklarasi
        $pengajuan_deklarasi = $this->dspd->get_travel_header_deklarasi(
            array(
                'id' => $idHeader,
                'single' => true
            )
        );
        $pengajuan_deklarasi = $this->lspd->proses_spd_list_header($pengajuan_deklarasi);
        $pengajuan_deklarasi = $this->general->generate_encrypt_json($pengajuan_deklarasi, array('id_travel_deklarasi_header'));
        //pengajuan_deklarasi-detail
        if ($pengajuan_deklarasi->tipe_trip === 'single') {
            $details_deklarasi = $this->dspd->get_travel_detail_deklarasi(
                array(
                    'id_header' => $idHeader,
                    'single' => true
                )
            );
            $details_deklarasi = $this->lspd->proses_spd_list_detail($details_deklarasi);
        } else {
            $details_deklarasi = $this->dspd->get_travel_detail_deklarasi(
                array(
                    'id_header' => $idHeader,
                )
            );
            $lspd = $this->lspd;
            $details_deklarasi = array_map(function ($detail) use ($lspd) {
                return $lspd->proses_spd_list_detail($detail);
            }, $details_deklarasi);
        }
        $details_deklarasi = $this->general->generate_encrypt_json($details_deklarasi, array('id_travel_header'));
        // $details_deklarasi = $this->general->generate_encrypt_json($details_deklarasi, array('id_travel_deklarasi_header_detail', 'id_travel_header'));

        //pengajuan_deklarasi-detail-biaya
        $details_deklarasi_biaya = $this->dspd->get_travel_deklarasi_detail(
            array(
                'id_travel_header' => $idHeader,
            )
        );
        $details_deklarasi_biaya = $this->general->generate_encrypt_json($details_deklarasi_biaya, array('id_travel_deklarasi_detail'));

        //pengajuan-transport
        $opttransport = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $transports = $this->dspd->get_travel_transport(
            array(
                'id_header' => $idHeader,
            )
        );
        $transports = $this->general->generate_encrypt_json(
            $transports,
            array(
                'id_travel_header', 'id_travel_detail', 'id_travel_transport'
            )
        );
        //pengajuan-personel
        $personel = $this->dspd->get_karyawan(array('nik' => $pengajuan->nik, 'single' => true));

        //pengajuan-personel
        $downpayments = $this->dspd->get_travel_downpayment(
            array(
                'id_header' => $idHeader,
            )
        );

        // add by ayy
        //pengajuan-country
        $optcountry = $this->dspd->get_countries();

        //pengajuan-pabrik
        $optpabrik = $this->dspd->get_travel_pabrik(array(
            'activity' => @$param['activity']
        ));
        //pengajuan-tujuan
        $opttujuan = $this->dspd->get_travel_tujuan(
            array(
                'personal_area' => @$param['personal_area'],
                'activity' => @$param['activity'],
            )
        );
        /** Mapping label text persa dan persub, cek jika depo mitra dapat isi nama depo **/
        $opttujuan = array_map(function ($tujuan) {
            if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                $tujuan->label = $tujuanArray[0];
                $tujuan->value = $tujuan->company_code;
                $tujuan->free = true;
            } else {
                $tujuan->label = $tujuan->personal_subarea_text;
                $tujuan->value = $tujuan->company_code;
                $tujuan->free = false;
            }
            return $tujuan;
        }, $opttujuan);

        //history deklarasi
        $log_deklarasi = $this->dspd->get_travel_deklarasi_log_status(array(
            'id_travel_header' => $idHeader,
            'single' => true
        ));
        if (isset($log_deklarasi)) {
            $history = $this->dspd->get_travel_deklarasi_log_status(
                array(
                    'id_travel_header' => $idHeader,
                    'order_by' => 'tgl_status asc'
                )
            );
        }

        $approval = $this->dspd->get_approval();
        //lha
        $expenses = $this->lspd->get_default_expenses(
            array(
                'id_header' => $this->generate->kirana_encrypt($idHeader),
                'user' => $this->data['user'],
                'country' => $pengajuan->country,
                'jenis_aktifitas' => $pengajuan->activity,
                'type' => 'declare'
            )
        );
        foreach ($expenses as &$expens) {
            $expens->value = floatval($expens->value) * 100;
        }

        $expenses_currency = $this->dspd->get_travel_tipeexpenses_currency();

        $cuti_pengganti = $this->dspd->get_cuti_pengganti(
            array(
                "connect" => TRUE,
                'id_travel_header' => $idHeader,
                "encrypt" => array("id")
            )
        );

        $result = compact(
            'pengajuan',
            'opttransport',
            'transports',
            'details',
            'personel',
            'downpayments',
            'cancel',
            'deklarasi',
            'deklarasi_details',
            'optcountry',
            'optpabrik',
            'opttujuan',
            'history',
            'approval',
            'expenses',
            'expenses_currency',
            'pengajuan_deklarasi',
            'details_deklarasi',
            'details_deklarasi_biaya',
            'cuti_pengganti'
        );
        return $result;
    }

    private function cetak_pengajuan($key = NULL)
    {
        $data_view['module'] = "Travel - Pengajuan SPD";
        $data_view['title'] = "Pengajuan Perjalanan Dinas";

        $id_header  = $key;
        $data = $this->get_data_pengajuan(array(
            "connect" => TRUE,
            "id_header" => $id_header,
        ));

        if ($data['pengajuan']->tipe_trip == "multi") {
            $tujuan = array();
            foreach ($data['pengajuan']->details as $detail) {
                $tujuan[] = $detail->tujuan_lengkap;
            }
            $data['tujuan_dinas'] = implode(". ", $tujuan);
        } else {
            $data['tujuan_dinas'] = $data['pengajuan']->tujuan_lengkap;
        }
        $penginapan = $data['pengajuan']->jenis_penginapan;
        $penginapan = explode(',', $penginapan);
        $data['jenis_penginapan'] = array_unique($penginapan);

        $tiket_berangkat = array();
        $tiket_kembali = array();
        foreach ($data['transports'] as $transport) {
            if ($transport->jenis_kendaraan == "pesawat" && $transport->status_tiket != "Cancel") {
                if ($transport->transport_kembali == 1)
                    $tiket_kembali[] = $transport->no_tiket;
                else
                    $tiket_berangkat[] = $transport->no_tiket;
            }
        }
        $data['tiket_berangkat'] = implode(", ", $tiket_berangkat);
        $data['tiket_kembali'] = implode(", ", $tiket_kembali);
        $atasan1 = "";
        $atasan2 = "";
        $pejabat_berwenang = "";
        foreach ($data['history'] as $ht) {
            if ($ht->approval_status == 1 && $ht->approval_level == 1) {
                $atasan1 = $ht->action_by_name;
            } else if ($ht->approval_status == 1 && $ht->approval_level == 2) {
                $atasan2 = $ht->action_by_name;
            } else if ($ht->approval_status == 1 && $ht->approval_level == 4) {
                $pejabat_berwenang = $ht->action_by_name;
            }
        }
        $data['nama_approval'] = array(
            "atasan1" => $atasan1,
            "atasan2" => $atasan2,
            "pejabat_berwenang" => $pejabat_berwenang,
        );
        // $data['pejabat_berwenang'] = $this->dspd->get_approval(array(
        //     'nik' => $data['pengajuan']->nik,
        //     'mode' => 'pengajuan',
        //     'level' => 4
        // ));
        $uangmuka = array(
            "transport" => "",
            "hotel" => "",
            "uang_makan" => "",
            "uang_saku" => ""
        );
        if (!empty($data['downpayments'])) {
            foreach ($data['downpayments'] as $um) {
                $kode_expense = $um->kode_expense;
                $jumlah = number_format($um->jumlah, 2, '.', ',');
                if ($kode_expense == "DHTL") {
                    $uangmuka["hotel"] = $jumlah;
                } else if ($kode_expense == "DUMK") {
                    $uangmuka["uang_makan"] = $jumlah;
                } else if ($kode_expense == "DUSK") {
                    $uangmuka["uang_saku"] = $jumlah;
                }
            }
        }
        $data['uangmuka'] = $uangmuka;

        if (!$key || empty($data))
            show_404();

        $data_view['data'] = $data;

        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'Portrait');
        $this->pdf->filename = 'SPD_' . $id_header . ".pdf";
        $this->pdf->load_view('travel/cetak/pengajuan', $data_view);
    }

    private function cetak_deklarasi($key = NULL)
    {
        $data_view['module'] = "Travel - Deklarasi SPD";
        $data_view['title'] = "Deklarasi Perjalanan Dinas";

        $id_header  = $key;
        $data = $this->get_data_deklarasi(array(
            "connect" => TRUE,
            "id_header" => $id_header,
        ));

        if ($data['pengajuan_deklarasi']->tipe_trip == "multi") {
            $pabrik_tujuan = array();
            $kota_tujuan = array();
            foreach ($data['pengajuan_deklarasi']->details as $detail) {
                $tujuan = explode(", ", $detail->tujuan_lengkap);
                $pabrik_tujuan[] = @$tujuan[0];
                $kota_tujuan[] = @$tujuan[1];
            }
            $data['pabrik_tujuan'] = implode(". ", $pabrik_tujuan);
            $data['kota_tujuan'] = implode(". ", $kota_tujuan);
        } else {
            $tujuan = explode(", ", $data['pengajuan_deklarasi']->tujuan_lengkap);
            $data['pabrik_tujuan'] = @$tujuan[0];
            $data['kota_tujuan'] = @$tujuan[1];
            // $data['tujuan_dinas'] = $data['pengajuan_deklarasi']->tujuan_lengkap;
        }

        $atasan1 = "";
        $atasan2 = "";
        $pejabat_berwenang = "";
        foreach ($data['history'] as $ht) {
            if ($ht->approval_status == 1 && $ht->approval_level == 1) {
                $atasan1 = $ht->action_by_name;
            } else if ($ht->approval_status == 1 && $ht->approval_level == 2) {
                $atasan2 = $ht->action_by_name;
            } else if ($ht->approval_status == 1 && $ht->approval_level == 4) {
                $pejabat_berwenang = $ht->action_by_name;
            }
        }
        // $pejabat_berwenang = $this->dspd->get_approval(array(
        //     'nik' => $data['pengajuan']->nik,
        //     'mode' => 'pengajuan',
        //     'level' => 4
        // ));
        $data['nama_approval'] = array(
            "atasan1" => $atasan1,
            "atasan2" => $atasan2,
            "pejabat_berwenang" => $pejabat_berwenang, //@$pejabat_berwenang['list_atasan'][0],
        );

        if (!$key || empty($data))
            show_404();

        $data_view['data'] = $data;

        $tipe = ($data['personel']->ho == 'n') ? 'deklarasi_pabrik' : 'deklarasi';

        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'Portrait');
        $this->pdf->filename = 'Deklarasi_' . $id_header . ".pdf";
        $this->pdf->load_view('travel/cetak/' . $tipe, $data_view);
    }

    private function delete_cuti_pengganti($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data_row = array(
                "rejected" => 1
            );
            $data_row = $this->dgeneral->basic_column('delete', $data_row);

            $this->dgeneral->update(
                'tbl_travel_cuti_pengganti',
                $data_row,
                array(
                    array(
                        'kolom' => 'id',
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
}
