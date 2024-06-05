<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS Dashboard - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dashboard extends MX_Controller
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

    public function index()
    {
        $this->general->check_access();
        $this->data['title'] = "Dashboard";

        // filter start
        $filter = $this->input->post();

        $tanggal_awal = date_create()->modify('-1 month');
        $tanggal_akhir = date_create();

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal']);

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir']);

        $this->data['list_data'] = $this->dbak->get_dashboard(array(
            'nik' => $this->data['user']->nik,
            'tanggal_awal' => $tanggal_awal->format('Ymd'),
            'tanggal_akhir' => $tanggal_akhir->format('Ymd')
        ));

        $this->data['tanggal_awal'] = $tanggal_awal->format('Y-m-d');;
        $this->data['tanggal_akhir'] = $tanggal_akhir->format('Y-m-d');;

        $this->load->view('dashboard/dashboard', $this->data);
    }

    public function bak()
    {
        $this->data['title'] = "Detail BAK";

        // filter start
        $filter = $this->input->get();

        $tanggal_awal = date_create()->modify('-1 month');
        $tanggal_akhir = date_create();

        $tipe = (isset($filter['tipe'])) ? $filter['tipe'] : null;

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal']);

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir']);

        $list_data_temp = $this->dbak->get_bak(array(
            'nik' => $filter['nik'],
            'tanggal_awal' => $tanggal_awal->format('Y-m-d'),
            'tanggal_akhir' => $tanggal_akhir->format('Y-m-d'),
//            'new_method' => 1
        ));

        $list_data = $this->less->proses_list_bak(
            array(
                'user' => $this->dessgeneral->get_user(null, null, null, $filter['nik']),
                'tanggal_awal' => $tanggal_awal->format('Y-m-d'),
                'tanggal_akhir' => $tanggal_akhir->format('Y-m-d'),
                'list_bak' => $list_data_temp,
                'lengkap' => true,
            )
        );

        if (isset($tipe) and $tipe != "") {
            switch ($tipe) {
                case 0:
                    $this->data['title'] .= ' Tidak lengkap';
                    $list_data = array_filter($list_data, function ($i) {
                        return $i->tidak_lengkap;
                    });
                    break;
                case 1:
                    $this->data['title'] .= ' Terlambat';
                    $list_data = array_filter($list_data, function ($i) {
                        return $i->absen_miss_ci;
                    });
                    break;
                case 2:
                    $this->data['title'] .= ' Pulang cepat';
                    $list_data = array_filter($list_data, function ($i) {
                        return $i->absen_miss_co;
                    });
                    break;
            }
        } else {
            $list_data = array_filter($list_data, function ($i) {
                return $i->tidak_lengkap or $i->absen_miss_ci or $i->absen_miss_co
                    or (isset($i->cuti) and $i->cuti_tipe == 'cuti')
                    or (isset($i->cuti) and $i->cuti_tipe == 'ijin');
            });
        }

        $this->data['list_data'] = $list_data;
        $this->data['tipe'] = isset($filter['tipe']) ? $filter['tipe'] : null;
        $this->data['nik'] = $filter['nik'];
        $this->data['tanggal_awal'] = $tanggal_awal->format('Y-m-d');
        $this->data['tanggal_akhir'] = $tanggal_akhir->format('Y-m-d');

        $this->load->view('dashboard/bak', $this->data);
    }

    public function dinas()
    {
        $this->data['title'] = "Detail Perjalanan Dinas";

        // filter start
        $filter = $this->input->get();

        $tanggal_awal = date_create()->modify('-1 month');//DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime('-1 month')));
        $tanggal_akhir = date_create();

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal']);

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir']);

        $list_data_temp = $this->dbak->get_bak(array(
            'nik' => $filter['nik'],
            'tanggal_awal' => $tanggal_awal->format('Y-m-d'),
            'tanggal_akhir' => $tanggal_akhir->format('Y-m-d'),
//            'new_method' => 1,
            'tipe' => array('0610')
        ));

        $this->data['list_data'] = $this->less->proses_list_bak(
            array(
                'user' => $this->dessgeneral->get_user(null, null, null, $filter['nik']),
                'tanggal_awal' => $tanggal_awal->format('Y-m-d'),
                'tanggal_akhir' => $tanggal_akhir->format('Y-m-d'),
                'list_bak' => $list_data_temp,
                'lengkap' => true,
            )
        );

        $this->data['nik'] = $filter['nik'];
        $this->data['tipe'] = null;
        $this->data['tanggal_awal'] = $tanggal_awal->format('Y-m-d');
        $this->data['tanggal_akhir'] = $tanggal_akhir->format('Y-m-d');

        $this->load->view('dashboard/bak', $this->data);
    }

    public function cuti()
    {
        $this->data['title'] = "Detail Cuti";

        // filter start
        $filter = $this->input->get();

        $tanggal_awal = date_create()->modify('-1 month');
        $tanggal_akhir = date_create();

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal']);

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir']);

        $list_data_temp = $this->dbak->get_bak(array(
            'nik' => $filter['nik'],
            'tanggal_awal' => $tanggal_awal->format('Y-m-d'),
            'tanggal_akhir' => $tanggal_akhir->format('Y-m-d'),
//            'new_method' => 1
        ));

        $list_data = $this->less->proses_list_bak(
            array(
                'user' => $this->dessgeneral->get_user(null, null, null, $filter['nik']),
                'tanggal_awal' => $tanggal_awal->format('Y-m-d'),
                'tanggal_akhir' => $tanggal_akhir->format('Y-m-d'),
                'list_bak' => $list_data_temp,
                'lengkap' => true,
            )
        );

        $list_data = array_filter($list_data, function ($item) {
            return isset($item->cuti) and $item->cuti_tipe == 'cuti';
        });

        $this->data['list_data'] = $list_data;

//        $this->data['list_data'] = $this->dcutiijin->get_cuti(array(
//            'nik' => $filter['nik'],
//            'tanggal_awal' => array($tanggal_awal->format('Y-m-d'), $tanggal_akhir->format('Y-m-d')),
//            'tipe' => 'Cuti',
//            'id_tipe_status' => array(
//                ESS_CUTI_STATUS_DISETUJUI_HR
//            )
//        ));

        $this->data['nik'] = $filter['nik'];
        $this->data['tanggal_awal'] = $tanggal_awal->format('Y-m-d');
        $this->data['tanggal_akhir'] = $tanggal_akhir->format('Y-m-d');

        $this->load->view('dashboard/cuti', $this->data);
    }

    public function ijin()
    {
        $this->data['title'] = "Detail Ijin";

        // filter start
        $filter = $this->input->get();

        $tanggal_awal = date_create()->modify('-1 month');
        $tanggal_akhir = date_create();

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal']);

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir']);

        $list_data_temp = $this->dbak->get_bak(array(
            'nik' => $filter['nik'],
            'tanggal_awal' => $tanggal_awal->format('Y-m-d'),
            'tanggal_akhir' => $tanggal_akhir->format('Y-m-d'),
//            'new_method' => 1,
            'tipe_exclude' => array(ESS_CUTI_JENIS_DINAS, ESS_CUTI_JENIS_CUTI_BERSAMA)
        ));

        $list_data = $this->less->proses_list_bak(
            array(
                'user' => $this->dessgeneral->get_user(null, null, null, $filter['nik']),
                'tanggal_awal' => $tanggal_awal->format('Y-m-d'),
                'tanggal_akhir' => $tanggal_akhir->format('Y-m-d'),
                'list_bak' => $list_data_temp,
                'lengkap' => true,
            )
        );

        $list_data = array_filter($list_data, function ($item) {
            return isset($item->cuti) and $item->cuti_tipe == 'ijin';
        });

        $this->data['list_data'] = $list_data;

        $this->data['nik'] = $filter['nik'];
        $this->data['tanggal_awal'] = $tanggal_awal->format('Y-m-d');
        $this->data['tanggal_akhir'] = $tanggal_akhir->format('Y-m-d');

        $this->load->view('dashboard/ijin', $this->data);
    }
}