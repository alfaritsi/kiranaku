<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Asset Management - Maintenance
 * @author          : Octe Reviyanto N (8731)
 * @contributor  :
 * 1. Matthew Jodi (8944) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */

class Maintenance extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dmasterasset');
        $this->load->model('dtransaksiasset');
        $this->load->model('dmaintenance');
        $this->load->library('lmaintenance');
    }

    function index($pengguna = 'it')
    {
    }

    function preventive($pengguna = 'it')
    {

        //	    $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        $data['user'] = $this->general->get_data_user();
        //===============================================/

        $data['title'] = "Jadwal Maintenance";
        $data['title_form'] = "Jadwal Maintenance";
        $data['pengguna'] = $pengguna;

        // AKSES MOBILE FO
        $data['akses'] = $this->dmasterasset->get_mobile_user(array(
            "connect"      => true,
            "app"        => 'PM',
            'pengguna'      => $pengguna,
            'nik'        => base64_decode($this->session->userdata("-nik-")),
            'active'     => 'yes',
            'single_row' => TRUE
        ));

        $plant = $pengguna == 'fo' ? explode(",", $data['akses']->pabrik) : NULL;
        $role = $pengguna == 'fo' && $data['akses']->role !== 'OPERATOR' ? $data['akses']->role : NULL;

        $data['kategori'] = $this->general->generate_encrypt_json(
            $this->dmaintenance->get_kategori(array(
                'pengguna' => $pengguna,
                'role' => $role
            )),
            array('id_kategori')
        );

        $data['kondisi'] = $this->general->generate_encrypt_json(
            $this->dmaintenance->get_kondisi(array(
                'active' => true
            )),
            array('id_kondisi')
        );

        $data['jenis'] = $this->general->generate_encrypt_json(
            $this->dmaintenance->get_jenis(array(
                'pengguna' => $pengguna,
                'role' => $role
            )),
            array('id_jenis')
        );
        $data['satuan'] = $this->dmaintenance->get_satuan();
        $data['status'] = $this->dmaintenance->get_satuan();
        $data['pabrik'] = $this->general->generate_encrypt_json(
            $this->dmaintenance->get_pabrik(array('plant_in' => $plant)),
            array('id_pabrik')
        );
        $data['lokasi'] = $this->general->generate_encrypt_json(
            $this->dmaintenance->get_lokasi(array(
                'pengguna' => $pengguna
            )),
            array('id_lokasi')
        );

        $data['tanggal_awal'] = date_create()->sub(DateInterval::createFromDateString('1 month'))->format('d.m.Y');
        $data['tanggal_akhir'] = date_create()->add(DateInterval::createFromDateString('1 month'))->format('d.m.Y');

        $data['operator'] = $this->dmaintenance->get_operator();


        if ($pengguna == 'it') {
            $this->load->view("maintenance/preventive_maintenance_it", $data);
        } else if ($pengguna == 'fo') {
            $this->load->view("maintenance/preventive_maintenance_fo", $data);
        } else {
            show_404();
        }
    }

    function approval($pengguna = 'it')
    {

        //	    $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        $data['user'] = $this->general->get_data_user();
        //===============================================/

        $data['title'] = "Approval Maintenance";
        $data['pengguna'] = $pengguna;


        if ($pengguna == 'it') {
            $data['kategori'] = $this->general->generate_encrypt_json(
                $this->dmaintenance->get_kategori(array(
                    'pengguna' => $pengguna
                )),
                array('id_kategori')
            );
            $data['jenis'] = $this->general->generate_encrypt_json(
                $this->dmaintenance->get_jenis(array(
                    'pengguna' => $pengguna
                )),
                array('id_jenis')
            );
            $data['satuan'] = $this->dmaintenance->get_satuan();
            $data['status'] = $this->dmaintenance->get_satuan();
            $data['pabrik'] = $this->general->generate_encrypt_json(
                $this->dmaintenance->get_pabrik(),
                array('id_pabrik')
            );
            $data['lokasi'] = $this->general->generate_encrypt_json(
                $this->dmaintenance->get_lokasi(array(
                    'pengguna' => $pengguna
                )),
                array('id_lokasi')
            );

            $this->load->view("maintenance/approval_pm_it", $data);
        } else if ($pengguna == 'fo') {
            // AKSES MOBILE FO
            $data['akses'] = $this->dmasterasset->get_mobile_user(array(
                "connect"      => true,
                "app"        => 'PM',
                'pengguna'      => $pengguna,
                'nik'        => base64_decode($this->session->userdata("-nik-")),
                'active'     => 'yes',
                'single_row' => TRUE
            ));

            $plant = $pengguna == 'fo' ? explode(",", $data['akses']->pabrik) : NULL;
            $role = $pengguna == 'fo' && $data['akses']->role !== 'OPERATOR' ? $data['akses']->role : NULL;
            // echo json_encode($plant);exit();

            $data['kategori'] = $this->general->generate_encrypt_json(
                $this->dmaintenance->get_kategori(array(
                    'pengguna' => $pengguna,
                    'role' => $role
                )),
                array('id_kategori')
            );

            $data['kondisi'] = $this->general->generate_encrypt_json(
                $this->dmaintenance->get_kondisi(array(
                    'active' => true
                )),
                array('id_kondisi')
            );

            $data['jenis'] = $this->general->generate_encrypt_json(
                $this->dmaintenance->get_jenis(array(
                    'pengguna' => $pengguna,
                    'role' => $role
                )),
                array('id_jenis')
            );
            $data['satuan'] = $this->dmaintenance->get_satuan();
            $data['status'] = $this->dmaintenance->get_satuan();
            $data['pabrik'] = $this->general->generate_encrypt_json(
                $this->dmaintenance->get_pabrik(array('plant_in' => $plant)),
                array('id_pabrik')
            );
            $data['lokasi'] = $this->general->generate_encrypt_json(
                $this->dmaintenance->get_lokasi(array(
                    'pengguna' => $pengguna
                )),
                array('id_lokasi')
            );

            $data['tanggal_awal'] = date_create()->sub(DateInterval::createFromDateString('1 month'))->format('d.m.Y');
            $data['tanggal_akhir'] = date_create()->add(DateInterval::createFromDateString('1 month'))->format('d.m.Y');

            $this->load->view("maintenance/approval_pm_fo", $data);
        } else {
            show_404();
        }
    }

    function import($pengguna = 'it')
    {

        //	    $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        $data['user'] = $this->general->get_data_user();
        //===============================================/

        $data['title'] = "Import hasil PM";
        $data['pengguna'] = $pengguna;
        $data['kategori'] = $this->general->generate_encrypt_json(
            $this->dmaintenance->get_kategori(array(
                'pengguna' => $pengguna
            )),
            array('id_kategori')
        );

        if ($pengguna == 'it')
            $this->load->view("maintenance/import_data", $data);
        else
            show_404();
    }

    function get($pengguna = 'it', $param, $param2 = NULL)
    {
        switch ($param) {
            case 'jadwal':
                // AKSES MOBILE FO
                $akses = $this->dmasterasset->get_mobile_user(array(
                    "connect"      => true,
                    "app"        => 'PM',
                    'pengguna'      => $pengguna,
                    'nik'        => base64_decode($this->session->userdata("-nik-")),
                    'active'     => 'yes',
                    'single_row' => TRUE
                ));

                $this->general->connectDbPortal();
                $id_aset = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
                $id_main = (isset($_POST['id_main']) ? $this->generate->kirana_decrypt($_POST['id_main']) : NULL);
                $query = (isset($_POST['term']) ? $_POST['term'] : NULL);
                $barcode = (isset($_POST['barcode']) ? $_POST['barcode'] : NULL);
                $tanggal_awal = (isset($_POST['tanggal_awal']) ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL);
                $tanggal_akhir = (isset($_POST['tanggal_akhir']) ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL);
                $nama = (isset($_POST['nama']) ? $_POST['nama'] : NULL);
                $inputs = (isset($_POST['inputs']) ? $_POST['inputs'] : NULL);
                $jenis_tindakan = (isset($_POST['jenis_tindakan']) ? $_POST['jenis_tindakan'] : NULL);
                if (isset($_POST['jenis'])) {
                    $jenis = array();
                    foreach ($_POST['jenis'] as $dt) {
                        array_push($jenis, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $jenis = NULL;
                }

                if (isset($_POST['merk'])) {
                    $merk = array();
                    foreach ($_POST['merk'] as $dt) {
                        array_push($merk, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $merk = NULL;
                }
                if (isset($_POST['pabrik'])) {
                    $pabrik = array();
                    foreach ($_POST['pabrik'] as $dt) {
                        array_push($pabrik, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $pabrik = NULL;
                }
                if (isset($_POST['lokasi'])) {
                    $lokasi = array();
                    foreach ($_POST['lokasi'] as $dt) {
                        array_push($lokasi, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $lokasi = NULL;
                }
                if (isset($_POST['area'])) {
                    $area = array();
                    foreach ($_POST['area'] as $dt) {
                        array_push($area, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $area = NULL;
                }

                if (isset($_POST['main_status'])) {
                    $main_status = array();
                    foreach ($_POST['main_status'] as $dt) {
                        array_push($main_status, $dt);
                    }
                } else {
                    $main_status = null;
                }
                if (isset($_POST['filter_operator'])) {
                    $filter_operator = array();
                    foreach ($_POST['filter_operator'] as $dt) {
                        array_push($filter_operator, $dt);
                    }
                } else {
                    $filter_operator = NULL;
                }

                if ($pengguna == 'fo') {
                    $dtRaw = json_decode(
                        $this->dmaintenance->get_pm(
                            array(
                                'id_aset' => $id_aset,
                                'id_main' => $id_main,
                                'barcode' => $barcode,
                                'nama' => isset($nama) ? $nama : $query,
                                'pengguna' => $pengguna,
                                'role' => $akses->role !== 'OPERATOR' ? $akses->role : NULL,
                                'operator_fo' => $inputs !== 'pm' && $akses->role == 'OPERATOR' ? base64_decode($this->session->userdata("-nik-")) : NULL,
                                'id_jenis' => $jenis,
                                'id_merk' => $merk,
                                'kode_pabrik' => explode(",", $akses->pabrik),
                                'id_lokasi' => $lokasi,
                                'id_area' => $area,
                                'main_status' => $main_status,
                                'tanggal_awal' => $tanggal_awal,
                                'tanggal_akhir' => $tanggal_akhir,
                                'jenis_tindakan' => $jenis_tindakan,
                            )
                        ),
                        true
                    );
                } else {
                    $dtRaw = json_decode(
                        $this->dmaintenance->get_pm(
                            array(
                                'id_aset' => $id_aset,
                                'id_main' => $id_main,
                                'barcode' => $barcode,
                                'nama' => isset($nama) ? $nama : $query,
                                'pengguna' => $pengguna,
                                'id_jenis' => $jenis,
                                'id_merk' => $merk,
                                'id_pabrik' => $pabrik,
                                'id_lokasi' => $lokasi,
                                'id_area' => $area,
                                'main_status' => $main_status,
                                'tanggal_awal' => $tanggal_awal,
                                'tanggal_akhir' => $tanggal_akhir,
                                'jenis_tindakan' => $jenis_tindakan,
                                'filter_operator' => $filter_operator,
                            )
                        ),
                        true
                    );
                }

                foreach ($dtRaw['data'] as &$dt) {
                    if ($pengguna == 'fo')
                        $dt['akses_pm'] = $akses->role;

                    $dt['buttons'] = $this->load->view('maintenance/includes/buttons_pm', $dt, true);
                }

                $dtRaw['data'] = $this->general->generate_encrypt_json(
                    $dtRaw['data'],
                    array(
                        'id_main',
                        'id_aset',
                        'id_kategori',
                        'id_jenis',
                        'id_merk',
                    )
                );

                $return = json_encode($dtRaw);

                echo $return;
                break;
                // TRIAL JADWAL FO
            case 'jadwal_fo':
                // AKSES MOBILE FO
                $akses = $this->dmasterasset->get_mobile_user(array(
                    "connect"      => true,
                    "app"        => 'PM',
                    'pengguna'      => $pengguna,
                    'nik'        => base64_decode($this->session->userdata("-nik-")),
                    'active'     => 'yes',
                    'single_row' => TRUE
                ));

                $this->general->connectDbPortal();
                $id_aset = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
                $id_main = (isset($_POST['id_main']) ? $this->generate->kirana_decrypt($_POST['id_main']) : NULL);
                $query = (isset($_POST['term']) ? $_POST['term'] : NULL);
                $barcode = (isset($_POST['barcode']) ? $_POST['barcode'] : NULL);
                $tanggal_awal = (isset($_POST['tanggal_awal']) ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL);
                $tanggal_akhir = (isset($_POST['tanggal_akhir']) ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL);
                $nama = (isset($_POST['nama']) ? $_POST['nama'] : NULL);
                $inputs = (isset($_POST['inputs']) ? $_POST['inputs'] : NULL);
                $jenis_tindakan = (isset($_POST['jenis_tindakan']) ? $_POST['jenis_tindakan'] : NULL);
                if (isset($_POST['jenis'])) {
                    $jenis = array();
                    foreach ($_POST['jenis'] as $dt) {
                        array_push($jenis, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $jenis = NULL;
                }

                if (isset($_POST['merk'])) {
                    $merk = array();
                    foreach ($_POST['merk'] as $dt) {
                        array_push($merk, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $merk = NULL;
                }
                if (isset($_POST['pabrik'])) {
                    $pabrik = array();
                    foreach ($_POST['pabrik'] as $dt) {
                        array_push($pabrik, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $pabrik = NULL;
                }
                if (isset($_POST['lokasi'])) {
                    $lokasi = array();
                    foreach ($_POST['lokasi'] as $dt) {
                        array_push($lokasi, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $lokasi = NULL;
                }
                if (isset($_POST['area'])) {
                    $area = array();
                    foreach ($_POST['area'] as $dt) {
                        array_push($area, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $area = NULL;
                }

                if (isset($_POST['main_status'])) {
                    $main_status = array();
                    foreach ($_POST['main_status'] as $dt) {
                        array_push($main_status, $dt);
                    }
                } else {
                    $main_status = null;
                }
                if (isset($_POST['filter_operator'])) {
                    $filter_operator = array();
                    foreach ($_POST['filter_operator'] as $dt) {
                        array_push($filter_operator, $dt);
                    }
                } else {
                    $filter_operator = NULL;
                }

                if ($pengguna == 'fo') {
                    $dtRaw = json_decode(
                        $this->dmaintenance->get_pm_fo(
                            array(
                                'id_aset' => $id_aset,
                                'id_main' => $id_main,
                                'barcode' => $barcode,
                                'nama' => isset($nama) ? $nama : $query,
                                'pengguna' => $pengguna,
                                'role' => $akses->role !== 'OPERATOR' ? $akses->role : NULL,
                                'operator_fo' => $inputs !== 'pm' && $akses->role == 'OPERATOR' ? base64_decode($this->session->userdata("-nik-")) : NULL,
                                'id_jenis' => $jenis,
                                'id_merk' => $merk,
                                'kode_pabrik' => explode(",", $akses->pabrik),
                                'id_lokasi' => $lokasi,
                                'id_area' => $area,
                                'main_status' => $main_status,
                                'tanggal_awal' => $tanggal_awal,
                                'tanggal_akhir' => $tanggal_akhir,
                                'jenis_tindakan' => $jenis_tindakan,
                            )
                        ),
                        true
                    );
                }

                foreach ($dtRaw['data'] as &$dt) {

                    $dt['akses_pm'] = $akses->role;

                    $dt['buttons'] = $this->load->view('maintenance/includes/buttons_pm', $dt, true);
                }



                $dtRaw['data'] = $this->general->generate_encrypt_json(
                    $dtRaw['data'],
                    array(
                        'id_main',
                        'id_aset',
                        'id_kategori',
                        'id_jenis',
                        'id_merk',
                    )
                );

                $return = json_encode($dtRaw);

                echo $return;
                break;
            case 'list-asset':
                // AKSES MOBILE FO
                $akses = $this->dmasterasset->get_mobile_user(array(
                    "connect"    => true,
                    "app"        => 'PM',
                    'pengguna'   => $pengguna,
                    'nik'        => base64_decode($this->session->userdata("-nik-")),
                    'active'     => 'yes',
                    'single_row' => TRUE
                ));

                if (isset($_POST['jenis'])) {
                    $jenis = array();
                    foreach ($_POST['jenis'] as $dt) {
                        array_push($jenis, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $jenis = NULL;
                }

                $dtRaw = $this->dmaintenance->get_list_asset(
                    array(
                        'id_jenis' => $jenis,
                        'kode_pabrik' => explode(",", $akses->pabrik),
                    )
                );


                $dtRaw = $this->general->generate_encrypt_json(
                    $dtRaw,
                    array(
                        'id_main',
                        'id_aset',
                        'id_kategori',
                        'id_jenis',
                        'id_merk',
                    )
                );

                $return = json_encode($dtRaw);

                echo $return;
                break;
            case 'karyawan':
                $this->general->connectDbPortal();
                $list = $this->dmaintenance->get_karyawan(
                    array(
                        'search' => $this->input->post('q')
                    ),
                    $param2
                );
                echo json_encode(array('data' => $list));
                break;
            case 'approval':
                $this->general->connectDbPortal();
                $id_aset = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
                $id_main = (isset($_POST['id_main']) ? $this->generate->kirana_decrypt($_POST['id_main']) : NULL);
                $active = (isset($_POST['active']) ? $_POST['active'] : NULL);
                $deleted = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
                $nama = (isset($_POST['nama']) ? $_POST['nama'] : NULL);
                if (isset($_POST['jenis'])) {
                    $jenis = array();
                    foreach ($_POST['jenis'] as $dt) {
                        array_push($jenis, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $jenis = NULL;
                }

                if (isset($_POST['merk'])) {
                    $merk = array();
                    foreach ($_POST['merk'] as $dt) {
                        array_push($merk, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $merk = NULL;
                }
                if (isset($_POST['pabrik'])) {
                    $pabrik = array();
                    foreach ($_POST['pabrik'] as $dt) {
                        array_push($pabrik, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $pabrik = NULL;
                }
                if (isset($_POST['lokasi'])) {
                    $lokasi = array();
                    foreach ($_POST['lokasi'] as $dt) {
                        array_push($lokasi, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $lokasi = NULL;
                }
                if (isset($_POST['area'])) {
                    $area = array();
                    foreach ($_POST['area'] as $dt) {
                        array_push($area, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $area = NULL;
                }

                if (isset($_POST['main_status'])) {
                    $main_status = array();
                    foreach ($_POST['main_status'] as $dt) {
                        array_push($main_status, $dt);
                    }
                } else {
                    $main_status = NULL;
                }
                $filter_status   = (isset($_POST['filter_status']) ? $_POST['filter_status'] : NULL);
                $dtRaw = json_decode(
                    $this->dmaintenance->get_pm_approval(
                        array(
                            'id_aset' => $id_aset,
                            'id_main' => $id_main,
                            'nama' => $nama,
                            'pengguna' => $pengguna,
                            'id_jenis' => $jenis,
                            'id_merk' => $merk,
                            'id_pabrik' => $pabrik,
                            'filter_status' => $filter_status,
                            'id_lokasi' => $lokasi,
                            'id_area' => $area
                        )
                    ),
                    true
                );

                foreach ($dtRaw['data'] as &$dt) {
                    if ($pengguna == 'fo')
                        $dt['akses_pm'] = NULL;

                    $dt['buttons'] = $this->load->view('maintenance/includes/buttons_pm', $dt, true);
                }

                $dtRaw['data'] = $this->general->generate_encrypt_json($dtRaw['data'], array('id_main', 'id_aset', 'id_kategori'));

                $return = json_encode($dtRaw);

                echo $return;
                break;
            case 'data':
                $this->general->connectDbPortal();
                $id_main = (isset($_POST['id_main']) ? $this->generate->kirana_decrypt($_POST['id_main']) : NULL);

                $data = $this->dmaintenance->get_pm_data(
                    array(
                        'id_main' => $id_main,
                        'single_row' => true
                    )
                );

                $data->detail = $this->general->generate_encrypt_json(
                    $this->dmaintenance->get_pm_detail(
                        array(
                            'id_main' => $id_main
                        )
                    ),
                    array(
                        'id_main_detail',
                        'id_main',
                        'id_aset',
                        'id_jenis_detail',
                        'id_periode',
                        'id_periode_detail'
                    )
                );

                $data = $this->general->generate_encrypt_json($data, array('id_main', 'id_aset', 'id_kategori', 'id_kondisi'));

                echo json_encode($data);
                break;
            case 'detail':
                $this->general->connectDbPortal();
                $id_main = (isset($_POST['id_main']) ? $this->generate->kirana_decrypt($_POST['id_main']) : NULL);

                $data = $this->dmaintenance->get_pm_data_fo(
                    array(
                        'id_main' => $id_main,
                        'single_row' => true
                    )
                );

                $data->detail = $this->general->generate_encrypt_json(
                    $this->dmaintenance->get_pm_detail(
                        array(
                            'id_main' => $id_main
                        )
                    ),
                    array(
                        'id_main_detail',
                        'id_main',
                        'id_aset',
                        'id_jenis_detail',
                        'id_periode',
                        'id_periode_detail'
                    )
                );

                $data = $this->general->generate_encrypt_json($data, array('id_main', 'id_aset', 'id_kategori', 'id_kondisi'));

                echo json_encode($data);
                break;
            case 'history':
                $this->general->connectDbPortal();
                $id_aset = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
                $id_main = (isset($_POST['id_main']) ? $this->generate->kirana_decrypt($_POST['id_main']) : NULL);
                $active = (isset($_POST['active']) ? $_POST['active'] : NULL);
                $deleted = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
                $nama = (isset($_POST['nama']) ? $_POST['nama'] : NULL);
                if (isset($_POST['jenis'])) {
                    $jenis = array();
                    foreach ($_POST['jenis'] as $dt) {
                        array_push($jenis, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $jenis = NULL;
                }

                if (isset($_POST['merk'])) {
                    $merk = array();
                    foreach ($_POST['merk'] as $dt) {
                        array_push($merk, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $merk = NULL;
                }
                if (isset($_POST['pabrik'])) {
                    $pabrik = array();
                    foreach ($_POST['pabrik'] as $dt) {
                        array_push($pabrik, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $pabrik = NULL;
                }
                if (isset($_POST['lokasi'])) {
                    $lokasi = array();
                    foreach ($_POST['lokasi'] as $dt) {
                        array_push($lokasi, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $lokasi = NULL;
                }
                if (isset($_POST['area'])) {
                    $area = array();
                    foreach ($_POST['area'] as $dt) {
                        array_push($area, $this->generate->kirana_decrypt($dt));
                    }
                } else {
                    $area = NULL;
                }

                if (isset($_POST['main_status'])) {
                    $main_status = array();
                    foreach ($_POST['main_status'] as $dt) {
                        array_push($main_status, $dt);
                    }
                } else {
                    $main_status = NULL;
                }

                $dtRaw = json_decode(
                    $this->dmaintenance->get_pm_history(
                        array(
                            'id_aset' => $id_aset,
                            'id_main' => $id_main,
                            'nama' => $nama,
                            'pengguna' => $pengguna,
                            'id_jenis' => $jenis,
                            'id_merk' => $merk,
                            'id_pabrik' => $pabrik,
                            'id_lokasi' => $lokasi,
                            'id_area' => $area,
                            'main_status' => $main_status
                        )
                    ),
                    true
                );
                $dtRawAsset = $this->dmaintenance->get_asset_history(
                    array(
                        'id_aset' => $id_aset
                    )
                );
                $dtRawPerbaikan = $this->dmaintenance->get_asset_perbaikan($id_aset);

                $dtRaw['data'] = $this->general->generate_encrypt_json($dtRaw['data'], array('id_main', 'id_aset', 'id_kategori'));
                $dtRaw['data_asset'] = $this->general->generate_encrypt_json($dtRawAsset);
                $dtRaw['data_perbaikan'] = $this->general->generate_encrypt_json($dtRawPerbaikan);
                $return = json_encode($dtRaw);

                echo $return;
                break;
            case 'agent':
                $this->general->connectDbPortal();
                $kode = $this->input->post('kode');
                if ($pengguna == 'fo') {
                    $agents = $this->dmaintenance->get_agent_fo(
                        array(
                            'kode' => $kode,
                            'akses_pm' => true
                        )
                    );
                } else {
                    $agents = $this->dmaintenance->get_agent(
                        array(
                            'kode' => $kode,
                        )
                    );
                }

                echo json_encode($agents);
                break;
            case 'operator':
                $this->general->connectDbPortal();
                $kode = $this->input->post('kode');
                if ($pengguna == 'fo') {
                    $agents = $this->dmaintenance->get_agent_fo(
                        array(
                            'kode' => $kode,
                        )
                    );
                } else {
                    $agents = $this->dmaintenance->get_agent(
                        array(
                            'kode' => $kode,
                        )
                    );
                }

                echo json_encode($agents);
                break;
        }
    }

    /**
     * save_jadwal
     * private function save_jadwal($data_row = null, $assets = array())
     * {
     *
     * $periode_detail = $this->dmaintenance->get_periode_detail(
     * array(
     * 'id_periode' => $data_row['id_periode'],
     * 'id_jenis' => $data_row['id_jenis']
     * )
     * );
     *
     * if (count($periode_detail) > 0) {
     * foreach ($assets as $id_aset) {
     * $id_aset = $this->generate->kirana_decrypt($id_aset);
     *
     * $data_row['id_aset'] = $id_aset;
     *
     * $main_aset = $this->dmaintenance->get_pm_data(array(
     * 'ho' => null,
     * 'gsber' => null,
     * 'id_aset' => $id_aset,
     * 'single_row' => true
     * ));
     *
     * $operator = $this->dmaintenance->get_agent(array('single_row' => true, 'kode' => $main_aset->kode));
     * if (isset($operator))
     * $data_row['operator'] = $operator->agent;
     * else
     * $data_row['operator'] = null;
     *
     * $data_row = $this->dgeneral->basic_column('insert', $data_row);
     *
     * $this->dgeneral->insert("tbl_inv_main", $data_row);
     *
     * $id_main_terakhir = $this->db->insert_id();
     *
     * $save_status = $this->lmaintenance->save_status_maintenance($id_main_terakhir);
     *
     * $data_ans_batch = array();
     * foreach ($periode_detail as $dt) {
     * $data_ans_row = array(
     * 'id_main' => $id_main_terakhir,
     * 'id_aset' => $id_aset,
     * "id_jenis" => $data_row['id_jenis'],
     * 'id_jenis_detail' => $dt->id_jenis_detail,
     * 'nama_jenis_detail' => $dt->nama_jenis_detail,
     * 'id_periode' => $dt->id_periode,
     * 'id_periode_detail' => $dt->id_periode_detail,
     * 'nama_periode_detail' => $dt->nama
     *
     * );
     *
     * $data_ans_row = $this->dgeneral->basic_column('insert_full', $data_ans_row);
     * array_push($data_ans_batch, $data_ans_row);
     * }
     *
     * $this->dgeneral->insert_batch('tbl_inv_main_detail', $data_ans_batch);
     * }
     * return true;
     * } else
     * return false;
     * }*/

    function save($pengguna = 'it', $param = null)
    {
        if (isset($param)) {
            switch ($param) {
                case 'bulk_jadwal':
                    $this->general->connectDbPortal();

                    $id_jenis = $this->generate->kirana_decrypt($this->input->post('id_jenis'));
                    $id_periode = $this->generate->kirana_decrypt($this->input->post('id_periode'));
                    $jadwal_service = $this->generate->regenerateDateFormat($this->input->post('jadwal_service'));
                    $single_row = true;
                    $jenis_tindakan = 'perawatan';
                    $pengguna = $pengguna;

                    $assets = $this->input->post('id_aset');

                    if (isset($assets) && isset($id_jenis) && isset($id_periode)) {
                        $this->dgeneral->begin_transaction();

                        $final = 'n';

                        $save_jadwal = $this->lmaintenance->save_jadwal(
                            compact('id_jenis', 'id_periode', 'jenis_tindakan', 'jadwal_service', 'final', 'pengguna'),
                            $assets
                        );
                        if (!$save_jadwal) {
                            $this->dgeneral->rollback_transaction();
                            $msg = "Periode Jenis Aset yang dipilih belum ditentukan kegiatan nya.";
                            $sts = "NotOK";
                        } else if ($this->dgeneral->status_transaction() === FALSE) {
                            $this->dgeneral->rollback_transaction();
                            $msg = "Please re-check the submitted data";
                            $sts = "NotOK";
                        } else {
                            $this->dgeneral->commit_transaction();
                            $msg = "Succesfully added data";
                            $sts = "OK";
                        }
                    } else {

                        $msg = "Tidak ada aset yang dibuatkan jadwal PM. <br/>Pilih aset terlebih dahulu.";
                        $sts = "NotOK";
                    }

                    $this->general->closeDb();
                    $return = array('sts' => $sts, 'msg' => $msg);

                    echo json_encode($return);
                    break;
                case 'perbaikan_jadwal':
                    $this->general->connectDbPortal();

                    $id_jenis = $this->generate->kirana_decrypt($this->input->post('id_jenis'));
                    $jenis_tindakan = 'perbaikan';
                    $operator = $this->input->post('operator');
                    $catatan = $this->input->post('catatan');
                    if (!isset($operator) || empty($operator)) {
                        $randomOperator = $this->dmaintenance->get_agent(
                            array(
                                'kode' => $this->input->post('kode'),
                                'single_row' => true
                            )
                        );
                        $operator = $randomOperator->agent;
                    }
                    $jadwal_service = $this->generate->regenerateDateFormat($this->input->post('jadwal_service'));
                    $id_aset = $this->generate->kirana_decrypt($this->input->post('id_aset'));
                    $data_aset = $this->dmaintenance->get_aset(array('id_aset' => $id_aset, 'single_row' => true));

                    if (isset($id_aset) && isset($id_jenis)) {

                        $dtRaw = json_decode(
                            $this->dmaintenance->get_pm(
                                array(
                                    'id_aset' => $id_aset,
                                    'pengguna' => $pengguna,
                                    'main_status' => null,
                                    'jenis_tindakan' => $jenis_tindakan,
                                )
                            ),
                            true
                        );
                        $cek_jadwal = $dtRaw['data'];

                        $cek_jadwal = array_filter($cek_jadwal, function ($jadwal) {
                            return isset($jadwal['id_main']);
                        });

                        if (count($cek_jadwal) > 0) {

                            $msg = "Aset sudah memiliki jadwal Perbaikan. Cek kembali di list jadwal.";
                            $sts = "NotOK";
                        } else {
                            $this->dgeneral->begin_transaction();

                            $final = 'n';

                            $data_row = array(
                                'id_aset' => $id_aset,
                                "id_jenis" => $id_jenis,
                                'jenis_tindakan' => $jenis_tindakan,
                                'operator' => $operator,
                                'catatan' => $catatan,
                                'jadwal_service' => $jadwal_service,
                                'final' => $final,
                            );

                            if (isset($data_aset->pic) && is_numeric($data_aset->pic))
                                $data_row['pic_nik'] = $data_aset->pic;

                            $data_row = $this->dgeneral->basic_column('insert', $data_row);

                            $this->dgeneral->insert("tbl_inv_main", $data_row);

                            $id_main_terakhir = $this->db->insert_id();

                            $save_status = $this->lmaintenance->save_status_maintenance($id_main_terakhir);
                            //add lha
                            //UPDATE tbl_inv_aset
                            $data["id_kondisi"]         = 2;
                            $data = $this->dgeneral->basic_column("update", $data);
                            $this->dgeneral->update("tbl_inv_aset", $data, array(
                                array(
                                    'kolom' => 'id_aset',
                                    'value' => $data_aset->id_aset
                                )
                            ));
                            //INSERT tbl_inv_aset_temp
                            $data_temp["id_aset"]         = $data_aset->id_aset;
                            $data_temp["id_kondisi"]    = 2;
                            $data_temp["flag"]             = 'proses';
                            $data_temp["proses"]        = 'set_perbaikan';
                            $data_temp["status_awal"]    = $data_aset->id_kondisi;
                            $data_temp["status_akhir"]    = 2;
                            $data_temp["login_edit"]    = base64_decode($this->session->userdata("-id_user-"));
                            $data_temp["tanggal_edit"]    = date("Y-m-d H:i:s");

                            $data_temp = $this->dgeneral->basic_column("insert", $data_temp);
                            $this->dgeneral->insert("tbl_inv_aset_temp", $data_temp);


                            if ($this->dgeneral->status_transaction() === FALSE) {
                                $this->dgeneral->rollback_transaction();
                                $msg = "Please re-check the submitted data";
                                $sts = "NotOK";
                            } else {
                                $this->dgeneral->commit_transaction();
                                $msg = "Succesfully added data";
                                $sts = "OK";
                            }
                        }
                    } else {

                        $msg = "Tidak ada aset yang dibuatkan jadwal Perbaikan";
                        $sts = "NotOK";
                    }

                    $this->general->closeDb();
                    $return = array('sts' => $sts, 'msg' => $msg);

                    echo json_encode($return);
                    break;
                case 'pm':
                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();
                    $keterangans = $this->input->post('keterangan');
                    $ceks = $this->input->post('cek');
                    if (isset($_POST['id_main']) && trim($_POST['id_main']) !== "") {
                        $id_main = $this->generate->kirana_decrypt($_POST['id_main']);

                        $main = $this->dmaintenance->get_pm_data(array('id_main' => $id_main, 'single_row' => true));
                        // save status on progress
                        $save_status = $this->lmaintenance->save_status_maintenance($id_main, $this->input->post('jenis_tindakan'));

                        $data_row = array(
                            'jenis_tindakan' => $this->input->post('jenis_tindakan'),
                            "tanggal_mulai" => $this->generate->regenerateDateFormat($this->input->post('tanggal_mulai')),
                            "tanggal_selesai" => $this->generate->regenerateDateFormat($this->input->post('tanggal_selesai')),
                            "final" => 'n'
                        );

                        $pengguna = isset($_POST['pengguna']) ? $_POST['pengguna'] : 'it';

                        $data_row['pengguna'] = $pengguna;
                        //UPDATE ASET DAN JAM JALAN JIKA PM FO
                        // echo json_encode($main);exit();
                        if ($pengguna == 'fo') {
                            $data_row['jam_jalan'] = $main->id_kategori == '1' ? $this->input->post('jam_jalan') : NULL;
                            $data_aset = array(
                                'jam_jalan_terakhir' => $main->id_kategori == '1' ? $this->input->post('jam_jalan') : NULL,
                                "id_kondisi" => $this->generate->kirana_decrypt($_POST['kondisi'])
                            );
                            $data_aset = $this->dgeneral->basic_column("update", $data_aset);
                            $this->dgeneral->update("tbl_inv_aset", $data_aset, array(
                                array(
                                    'kolom' => 'id_aset',
                                    'value' => $main->id_aset
                                )
                            ));
                        }

                        if ($data_row['jenis_tindakan'] != 'perawatan') {
                            $data_row['tanggal_rusak'] = $this->generate->regenerateDateFormat($this->input->post('tanggal_rusak'));
                            $data_row['catatan'] = $this->input->post('catatan');
                        }

                        // PM IT jika tidak ada pic aset, maka langsung sudah approved
                        if ($data_row['pengguna'] !== 'fo' && empty($main->pic)) {
                            $data_row['pic_approve'] = 'y';
                        }

                        $data_row = $this->dgeneral->basic_column("update", $data_row);
                        $this->dgeneral->update("tbl_inv_main", $data_row, array(
                            array(
                                'kolom' => 'id_main',
                                'value' => $this->generate->kirana_decrypt($_POST['id_main'])
                            )
                        ));

                        if ($data_row['jenis_tindakan'] == 'perawatan') {
                            foreach ($keterangans as $id_main_detail => $keterangan) {
                                $cek = $ceks[$id_main_detail];
                                if ($cek != 'y') $cek = 'n';

                                $data_row_detail = array(
                                    "keterangan" => $keterangan,
                                    "cek" => $cek
                                );
                                $data_row_detail = $this->dgeneral->basic_column("update", $data_row_detail);
                                $this->dgeneral->update("tbl_inv_main_detail", $data_row_detail, array(
                                    array(
                                        'kolom' => 'id_main_detail',
                                        'value' => $this->generate->kirana_decrypt($id_main_detail)
                                    )
                                ));
                            }
                        }

                        // save status confirmation
                        $save_status = $this->lmaintenance->save_status_maintenance($id_main, $data_row['jenis_tindakan']);
                        if ($pengguna !== 'fo') {
                            $generate_jadwal = $this->lmaintenance->generate_jadwal($main->id_periode, $main, $this->input->post('tanggal_selesai'));
                        } else {
                            $generate_jadwal = TRUE;
                        }

                        if (
                            $this->dgeneral->status_transaction() === false
                            or $save_status === false
                            or $generate_jadwal === false
                        ) {
                            $this->dgeneral->rollback_transaction();
                            $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";
                        } else {
                            $this->dgeneral->commit_transaction();
                            if ($pengguna == 'fo') {
                                $this->generate_message_email($main->id_main);
                            } else {
                                $this->lmaintenance->send_konfirmasi_email($main);
                            }
                            $msg = "Maintenance berhasil ditambahkan";
                            $sts = "OK";
                        }
                    } else {

                        $msg = "PM tidak ditemukan";
                        $sts = "NotOK";
                    }
                    $this->general->closeDb();

                    $return = array('sts' => $sts, 'msg' => $msg);

                    echo json_encode($return);
                    break;
                case 'perbaikan':
                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();
                    if (isset($_POST['id_main']) && trim($_POST['id_main']) !== "") {
                        $id_main = $this->generate->kirana_decrypt($_POST['id_main']);

                        $main = $this->dmaintenance->get_pm_data(array('id_main' => $id_main, 'single_row' => true));
                        // save status on progress
                        $save_status = $this->lmaintenance->save_status_maintenance($id_main, $this->input->post('jenis_tindakan'));

                        $data_row = array(
                            'jenis_tindakan' => $this->input->post('jenis_tindakan'),
                            "tanggal_mulai" => $this->generate->regenerateDateFormat($this->input->post('tanggal_mulai')),
                            "tanggal_selesai" => $this->generate->regenerateDateFormat($this->input->post('tanggal_selesai')),
                            "tanggal_rusak" => $this->generate->regenerateDateFormat($this->input->post('tanggal_selesai')),
                            "catatan_service" => $this->input->post('catatan_service'),
                            "final" => 'n'
                        );

                        // jika tidak ada pic aset, maka langsung sudah approved
                        if (empty($main->pic)) {
                            $data_row['pic_approve'] = 'y';
                        }

                        $data_row = $this->dgeneral->basic_column("update", $data_row);
                        $this->dgeneral->update("tbl_inv_main", $data_row, array(
                            array(
                                'kolom' => 'id_main',
                                'value' => $this->generate->kirana_decrypt($_POST['id_main'])
                            )
                        ));

                        // save status confirmation
                        $save_status = $this->lmaintenance->save_status_maintenance($id_main, $this->input->post('jenis_tindakan'));

                        if (
                            $this->dgeneral->status_transaction() === false
                            and $save_status === false
                        ) {
                            $this->dgeneral->rollback_transaction();
                            $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";
                        } else {
                            $this->dgeneral->commit_transaction();
                            $this->lmaintenance->send_konfirmasi_email($this->generate->kirana_decrypt($_POST['id_main']));
                            $msg = "Maintenance berhasil ditambahkan";
                            $sts = "OK";
                        }
                    } else {

                        $msg = "Maintenance tidak ditemukan";
                        $sts = "NotOK";
                    }
                    $this->general->closeDb();

                    $return = array('sts' => $sts, 'msg' => $msg);

                    echo json_encode($return);
                    break;
                case 'operator':
                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();
                    if (isset($_POST['id_main']) && trim($_POST['id_main']) !== "") {

                        $data_row = array(
                            'operator' => $this->input->post('operator_baru'),
                        );

                        $data_row = $this->dgeneral->basic_column("update", $data_row);
                        $this->dgeneral->update("tbl_inv_main", $data_row, array(
                            array(
                                'kolom' => 'id_main',
                                'value' => $this->generate->kirana_decrypt($_POST['id_main'])
                            )
                        ));

                        if ($this->dgeneral->status_transaction() === false) {
                            $this->dgeneral->rollback_transaction();
                            $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";
                        } else {
                            $this->dgeneral->commit_transaction();
                            $msg = "Operator berhasil diganti";
                            $sts = "OK";
                        }
                    } else {

                        $msg = "Data PM tidak ditemukan";
                        $sts = "NotOK";
                    }
                    $this->general->closeDb();

                    $return = array('sts' => $sts, 'msg' => $msg);

                    echo json_encode($return);
                    break;
                case 'konfirmasi':
                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();
                    if (isset($_POST['id_main']) && trim($_POST['id_main']) !== "") {
                        $id_main = $this->generate->kirana_decrypt($_POST['id_main']);

                        $main = $this->dmaintenance->get_pm_data(array('id_main' => $id_main, 'single_row' => true));

                        if (!isset($main)) {
                            $msg = "Tidak ada PM yang perlu di konfirmasi";
                            $sts = "NotOK";
                        } else {
                            $data_row = array(
                                "tanggal_approve" => date('Y-m-d'),
                                "pic_nik" => base64_decode($this->session->userdata("-nik-")),
                                "pic_approve" => 'y',
                            );
                            $data_row = $this->dgeneral->basic_column("update", $data_row);
                            $this->dgeneral->update("tbl_inv_main", $data_row, array(
                                array(
                                    'kolom' => 'id_main',
                                    'value' => $id_main
                                )
                            ));

                            $save_status = $this->lmaintenance->save_status_maintenance($id_main, $main->jenis_tindakan, base64_decode($this->session->userdata("-nik-")));

                            $save_jadwal = true;
                            if ($main->jenis_tindakan == 'perawatan')
                                $save_jadwal = $this->lmaintenance->generate_jadwal($main->id_periode, $main, $main->tanggal_selesai, true);

                            if ($this->dgeneral->status_transaction() === false || !$save_status || !$save_jadwal) {
                                $this->dgeneral->rollback_transaction();
                                $msg = "Periksa kembali data yang dimasukkan";
                                $sts = "NotOK";
                            } else {
                                $this->dgeneral->commit_transaction();
                                $msg = "Maintenance berhasil dikonfirmasi";
                                $sts = "OK";
                            }
                        }
                    }

                    $this->general->closeDb();

                    $return = array('sts' => $sts, 'msg' => $msg);

                    echo json_encode($return);
                    break;
                case 'konfirmasi_multi':
                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();
                    $id_mains = $this->input->post('id_main');
                    if (isset($id_mains)) {
                        if (count($id_mains) == 0) {
                            $msg = "Tidak ada PM yang perlu di konfirmasi";
                            $sts = "NotOK";
                        } else {
                            foreach ($id_mains as $id_main) {
                                $id_main = $this->generate->kirana_decrypt($id_main);

                                $main = $this->dmaintenance->get_pm_data(array('id_main' => $id_main, 'single_row' => true));
                                $data_row = array(
                                    "tanggal_approve" => date('Y-m-d'),
                                    "pic_nik" => base64_decode($this->session->userdata("-nik-")),
                                    "pic_approve" => 'y',
                                );
                                $data_row = $this->dgeneral->basic_column("update", $data_row);
                                $this->dgeneral->update("tbl_inv_main", $data_row, array(
                                    array(
                                        'kolom' => 'id_main',
                                        'value' => $id_main
                                    )
                                ));
                                $save_status = $this->lmaintenance->save_status_maintenance($id_main, $main->jenis_tindakan, base64_decode($this->session->userdata("-nik-")));
                                if ($main->jenis_tindakan == 'perawatan')
                                    $this->lmaintenance->generate_jadwal($main->id_periode, $main, $main->tanggal_selesai, true);
                            }

                            if ($this->dgeneral->status_transaction() === false) {
                                $this->dgeneral->rollback_transaction();
                                $msg = "Periksa kembali data yang dimasukkan";
                                $sts = "NotOK";
                            } else {
                                $this->dgeneral->commit_transaction();
                                $msg = "Maintenance berhasil dikonfirmasi";
                                $sts = "OK";
                            }
                        }
                    } else {
                        $msg = "Tidak ada PM yang perlu di konfirmasi";
                        $sts = "NotOK";
                    }

                    $this->general->closeDb();

                    $return = array('sts' => $sts, 'msg' => $msg);

                    echo json_encode($return);
                    break;
                case 'konfirmasi_atasan':
                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();
                    $id_mains = $this->input->post('id_main');
                    if (isset($id_mains)) {

                        if (count($id_mains) == 0) {
                            $msg = "Tidak ada PM yang perlu di konfirmasi";
                            $sts = "NotOK";
                        } else {

                            foreach ($id_mains as $id_main) {
                                $id_main = $this->generate->kirana_decrypt($id_main);

                                $main = $this->dmaintenance->get_pm_approval(array('id_main' => $id_main, 'single_row' => true));

                                if (isset($main)) {
                                    $data_row = array(
                                        "final" => 'y'
                                    );
                                    $data_row = $this->dgeneral->basic_column("update", $data_row);
                                    $this->dgeneral->update("tbl_inv_main", $data_row, array(
                                        array(
                                            'kolom' => 'id_main',
                                            'value' => $id_main
                                        )
                                    ));

                                    $save_status = $this->lmaintenance->save_status_maintenance($id_main, $this->input->post('jenis_tindakan'));
                                }
                            }

                            if ($this->dgeneral->status_transaction() === false) {
                                $this->dgeneral->rollback_transaction();
                                $msg = "Periksa kembali data yang dimasukkan";
                                $sts = "NotOK";
                            } else {
                                $this->dgeneral->commit_transaction();
                                $msg = "Maintenance berhasil dikonfirmasi";
                                $sts = "OK";
                            }
                        }
                    } else {
                        $msg = "Tidak ada PM yang perlu di konfirmasi";
                        $sts = "NotOK";
                    }

                    $this->general->closeDb();

                    $return = array('sts' => $sts, 'msg' => $msg);

                    echo json_encode($return);
                    break;
                case 'import_excel':
                    $this->load->library('phpexcel');

                    $this->general->connectDbPortal();

                    $params = $this->input->post();

                    $agents = $this->dmaintenance->get_agent();
                    $id_periode = isset($params['id_periode']) ? $this->generate->kirana_decrypt($params['id_periode']) : null;
                    $id_jenis = isset($params['id_jenis']) ? $this->generate->kirana_decrypt($params['id_jenis']) : null;
                    $periode = $this->dmaintenance->get_periode(array('id_periode' => $id_periode, 'single_row' => true));
                    $details = $this->dmaintenance->get_periode_detail(array('id_periode' => $id_periode));
                    $imported = array();
                    if (isset($_FILES['import_file'])) {

                        $file = $_FILES['import_file']['tmp_name'];

                        try {
                            $load = PHPExcel_IOFactory::load($file);
                            $sheets = $load->getActiveSheet()->toArray(null, true, true, true);

                            foreach ($sheets as $index => $sheet) {
                                if ($index >= 3 and isset($sheet['A']) and !empty($sheet['A'])) {
                                    $asetPm = $this->dmaintenance->get_pm_data(array(
                                        'single_row' => true,
                                        'barcode' => $sheet['A']
                                    ));
                                    array_push($imported, array(
                                        'nomor_aset' => $sheet['A'],
                                        'nik_user' => $sheet['B'],
                                        'tanggal' => date_create($sheet['C'])->format('d.m.Y'),
                                        'keterangan' => $sheet['D'],
                                        'operator' => $sheet['E'],
                                        'detail_aset' => $asetPm
                                    ));
                                }
                            }
                            $imports = $imported;
                            $sts = 'OK';
                        } catch (PHPExcel_Reader_Exception $e) {
                            $sts = 'NotOK';
                        } catch (PHPExcel_Exception $e) {
                            $sts = 'NotOK';
                        }
                    } else
                        $sts = 'NotOK';

                    echo json_encode(compact('periode', 'imports', 'details', 'agents', 'sts'));
                    break;
                case 'import_validate':
                    $this->general->connectDbPortal();
                    $params = $this->input->post();
                    $id_periode = isset($params['id_periode']) ? $params['id_periode'] : null;
                    $id_jenis = isset($params['id_jenis']) ? $params['id_jenis'] : null;

                    $jadwal_service = date_create($this->generate->regenerateDateFormat($params['jadwal_service']))
                        ->format('Y-m-d');

                    if (isset($params['items'])) {
                        $this->dgeneral->begin_transaction();

                        $items = $params['items'];
                        $pmIds = array();
                        foreach ($items as $item) {
                            $decodedItem = json_decode($item);
                            if (isset($decodedItem)) {

                                $tanggal = date_create($this->generate->regenerateDateFormat($decodedItem->tanggal))
                                    ->format('Y-m-d');
                                $pmIds[] = $this->lmaintenance->save_jadwal(
                                    array(
                                        'id_jenis' => $id_jenis,
                                        'id_periode' => $id_periode,
                                        'jenis_tindakan' => "perawatan",
                                        'jadwal_service' => $jadwal_service,
                                        "tanggal_mulai" => $tanggal,
                                        "tanggal_selesai" => $tanggal,
                                        "catatan_service" => $decodedItem->keterangan,
                                        'pic_approve' => 'y',
                                        'final' => 'n'
                                    ),
                                    array(
                                        $this->generate->kirana_encrypt($decodedItem->id_aset)
                                    ),
                                    true
                                );
                            }
                        }

                        if ($this->dgeneral->status_transaction() === false) {
                            $this->dgeneral->rollback_transaction();
                            $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";
                        } else {
                            $this->dgeneral->commit_transaction();
                            $msg = "Import Maintenance berhasil dikonfirmasi";
                            $sts = "OK";
                        }
                    } else {
                        $sts = 'NotOK';
                        $msg = "Tidak ada Aset yang di import, harap pilih salah satu";
                    }
                    echo json_encode(compact('sts', 'msg'));
                    break;
            }
        }
    }

    public function set($param = NULL)
    {
        $action = NULL;
        if (isset($_POST['type']) && $_POST['type'] == "non_active") {
            $action = "delete_na";
        } else if (isset($_POST['type']) && $_POST['type'] == "set_active") {
            $action = "activate_na";
        } else if (isset($_POST['type']) && $_POST['type'] == "delete") {
            $action = "delete_na_del";
        }

        if (isset($action)) {
            switch ($param) {
                case 'main':
                    $this->general->connectDbPortal();
                    $return = $this->general->set($action, "tbl_inv_main", array(
                        array(
                            'kolom' => 'id_main',
                            'value' => $this->generate->kirana_decrypt($_POST['id_main'])
                        )
                    ));
                    $this->general->set($action, "tbl_inv_main_detail", array(
                        array(
                            'kolom' => 'id_main',
                            'value' => $this->generate->kirana_decrypt($_POST['id_main'])
                        )
                    ));
                    echo json_encode($return);
                    $this->general->closeDb();
                    break;

                default:
                    $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                    echo json_encode($return);
                    break;
            }
        } else {
            $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
            echo json_encode($return);
        }
    }

    function konfirmasi($id_main = null)
    {

        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        $data['user'] = $this->general->get_data_user();

        $data['title'] = "Konfirmasi Maintenance";

        $data['id_main'] = $id_main;

        $id_main = $this->generate->kirana_decrypt($id_main);

        $main = $this->dmaintenance->get_pm_data(array('id_main' => $id_main, 'single_row' => true));

        if (isset($main) && !empty($main)) {
            if ($main->jenis_tindakan == 'perawatan') {
                $main->detail = $this->general->generate_encrypt_json(
                    $this->dmaintenance->get_pm_detail(
                        array(
                            'id_main' => $id_main
                        )
                    ),
                    array(
                        'id_main_detail',
                        'id_main',
                        'id_aset',
                        'id_jenis_detail',
                        'id_periode',
                        'id_periode_detail'
                    )
                );
            } else {
                $main->detail = $this->general->generate_encrypt_json(
                    $this->dmaintenance->get_pm_items(
                        array(
                            'id_main' => $id_main
                        )
                    ),
                    array(
                        'id_main_item',
                        'id_main',
                        'id_jenis',
                        'id_jenis_detail',
                    )
                );
            }

            $data['main'] = $main;
        }

        //        var_dump($data);die();

        $this->load->view("maintenance/konfirmasi", $data);
    }

    function view_konfirmasi_email($id_main = null)
    {
        $this->general->connectDbPortal();
        //        $this->lmaintenance->send_konfirmasi_email($id_main);
        //        die();

        $main = $this->dmaintenance->get_pm_data(array('id_main' => $id_main, 'single_row' => true));

        //        if (isset($main->pic) && !empty($main->pic)) {
        $email = PM_EMAIL_DEBUG_MODE ? json_decode(PM_EMAIL_TESTER) : $main->email_pic;
        $emailOri = null;
        $message = $this->load->view('emails/mail_konfirmasi_user', compact('main', 'emailOri'), true);
        echo $message;
        //        }
    }

    private function generate_message_email($id_main = NULL)
    {

        if (isset($id_main)) {

            //list data email
            $data_recipient = $this->dmaintenance->get_pm_email(
                array(
                    "conn" => TRUE,
                    "id_main" => $id_main
                )
            );

            $data = $this->dmaintenance->get_pm_data(array('id_main' => $id_main, 'single_row' => true));

            $email_to = array();
            $nama_to = array();
            foreach ($data_recipient as $dt) {
                // $email_to[] = $dt->email;
                $email_to[] = "matthew.jodi@kiranamegatara.com";
                $email_to[] = "airiza.perdana@kiranamegatara.com";
                if ($dt->nama !== "" && $dt->gender !== "") {
                    $nama_to[] = $dt->gender . " " . ucwords(strtolower($dt->nama)) . "<br>";
                }
            }

            if (empty($email_to)) {
                $email_to[] = "matthew.jodi@kiranamegatara.com";
            }

            $data_email = array(
                "nomor"           => $data->nomor,
                "nama_kategori"      => $data->nama_kategori,
                "nama_jenis"      => $data->nama_jenis,
                "nama_operator"      => $data->nama_operator,
                "id_enkrip"          => $this->generate->kirana_encrypt($id_main),
                "email_to"        => $email_to,
                "nama_to"         => empty($nama_to) ? "" : implode("", $nama_to)
            );
            $this->send_email($data_email);
        } else
            return false;
    }

    private function send_email($param)
    {
        // setlocale(LC_ALL, 'id');
        setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');

        $config['protocol']    = 'smtp';
        $config['smtp_host']   = 'mail.kiranamegatara.com';
        $config['smtp_user']   = 'no-reply@kiranamegatara.com';
        $config['smtp_pass']   = '1234567890';
        $config['smtp_port']   = '465';
        $config['smtp_crypto'] = 'ssl';
        $config['charset']     = 'iso-8859-1';
        $config['wordwrap']    = true;
        $config['mailtype']    = 'html';

        $this->load->library('email', $config);

        $this->email->from('no-reply@kiranamegatara.com', 'PM FO');
        $this->email->to($param['email_to']);

        $message = "<html>
            <body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
            <center style='width: 100%;'>
                <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                    Konfirmasi PM FO
                </div>
                <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                    &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
                </div>
                <div class='email-container' style='max-width: 800px; margin: 0 auto;'>
                    <table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'
                        style='min-width:600px;'>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style='color: #fff; padding:20px;' align='center'>
                                <div style='width: 50%; padding-bottom: 10px;''>
                                    <img src='" . base_url() . "/assets/apps/img/logo-lg.png'>
                                </div>
                                <h3 style='margin-bottom: 0;'>Preventive Maintenance FO</h3>
                                <hr style='border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;'/>
                                <h3 style='margin-top: 0;'>Konfirmasi Asset</h3>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table style='background-color: #ffffff; margin: auto; -webkit-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); -moz-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4);'
                                    role='presentation' border='0' width='100%' cellspacing='0'
                                    cellpadding='0'
                                    align='center'>
                                    <tbody>
                                    <tr>
                                        <td style='padding: 20px;'>
                                            ";
        if (!$param['nama_to']) {
            $nama_to = 'Bapak & Ibu';
        }
        $message .= "<p><strong>Kepada :<br><br> " . $param['nama_to'] . "</strong></p>";
        $message .= "<p>Email ini menandakan bahwa ada hasil Preventive Maintenance baru yang membutuhkan perhatian anda.</p>";
        $message .= "<table style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
													<tr>
														<td>Nomor Asset</td>
														<td>:</td>";
        $message .= "<td>" . $param['nomor'] . "</td>"; //Nomor Aset
        $message .= "</tr>
													<tr>
														<td>Kategori</td>
														<td>:</td>";
        $message .= "<td>" . $param['nama_kategori'] . "</td>"; //Kategori
        $message .= "</tr>
													<tr>
														<td>Jenis</td>
														<td>:</td>";
        $message .= "<td>" . $param['nama_jenis'] . "</td>"; // Jenis
        $message .= "</tr>
													<tr>
														<td>Operator</td>
														<td>:</td>";
        $message .= "<td>" . $param['nama_operator'] . "</td>"; //operator
        $message .= "</tr>
									</table>
									<p>Silahkan menekan tombol dibawah untuk melakukan konfirmasi PM</p><p>melalui aplikasi Portal Kiranaku.</p>
								</td>
							</tr>
							<tr>
								<td align='left'
									style='background-color: #ffffff; padding: 15px; border-top: 1px dashed #386d22;'>
								</td>
							</tr>
							<tr>
								<td align='center' style='padding-bottom: 10px; font-size:15px;'><b>Mohon untuk login Portal Kiranaku terlebih dahulu sebelum menekan tombal ini.</b></td>
							</tr>
							<tr>
								<td align='center' style='padding-bottom: 20px;'>";
        $message .= "<a href='" . base_url() . "/asset/maintenance/konfirmasi/" . $param['id_enkrip'] . "' style='
											color: #fff;
											text-decoration: none; 
											background-color: #008d4c;
											border-color: #4cae4c; 
											display: inline-block; 
											margin-bottom: 0; 
											font-weight: 400; 
											text-align: center; 
											white-space: nowrap; 
											vertical-align: middle; 
											cursor: pointer;
											background-image: none;
											border: 1px solid transparent;
											padding: 6px 80px;
											font-size: 17px;
											letter-spacing: 2px;
											line-height: 1.42857143;
											border-radius: 4px;'>Konfirmasi</a>"; // LINK PORTAL KIRANAKU
        $message .= " </td>
									</tr>
									<tr>
										<td align='left'
											style='background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;'>
											<p>
												Terima kasih atas perhatiannya.
											</p>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td style='color: #fff; padding-top:20px;' align='center'>
								<small>Kiranaku Auto-Mail System</small><br/>";
        $message .= "<strong style='color: #214014; font-size: 10px;'>Terkirim pada " . date('d.m.Y H:i:s') . "</strong>"; // TANGGAL KIRIM EMAIL
        $message .= " </td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</center>
                </body>
            </html>";

        $this->email->subject('Konfirmasi Preventive Maintenance');
        $this->email->message($message);

        $this->email->send();
    }

    public function resend_konfirmasi($nomor = NULL)
    {
        $this->general->connectDbPortal();
        $main = $this->dmaintenance->get_pm_data(
            array(
                'main_status' => 'confirmpic',
                'pengguna' => 'it',
                'active' => TRUE,
                'barcode' => $nomor
            )
        );

        if ($main && $nomor) {
            foreach ($main as $key => $value) {
                $this->lmaintenance->send_konfirmasi_email($value);
            }
        } else {
            echo json_encode(
                array(
                    "sts" => "NotOK",
                    "msg" => "Data PM tidak ditemukan atau sudah di konfirmasi"
                )
            );
            exit();
        }
    }
}
