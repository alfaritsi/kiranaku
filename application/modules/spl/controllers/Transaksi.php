<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : SPL
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
include_once APPPATH . "modules/spl/controllers/BaseControllers.php";

class Transaksi extends BaseControllers
{
    // private $data;

    function __construct()
    {
        parent::__construct();
        $this->data['generate'] = $this->generate;
        $this->data['module'] = $this->router->fetch_module();
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dtransaksi');
        $this->load->model('dmaster');

        $access_plant = $this->dmaster->get_data_plant(array(
            "connect" => TRUE,
        ));
        $this->data['access_plant'] = array_map(function ($o) {
            return $o->id;
        }, $access_plant);
    }

    public function index()
    {
        show_404();
    }

    public function pengajuan($param = "list")
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        //===============================================/

        $this->data['tahun'] = $this->dtransaksi->get_spl_header_tahun(
            array(
                "connect" => TRUE
            )
        );
        $this->data['departemen'] = $this->dmaster->get_data_departemen(array(
            "connect" => TRUE,
            "plant" => $this->data['user']->gsber,
            // "encrypt" => array("id")
        ));
        $this->data['pabrik'] = $this->data['access_plant'];
        $this->data['tipe_list'] = $param;
        // echo json_encode($this->data);exit();
        $this->load->view("transaksi/pengajuan", $this->data);
    }

    public function tambah()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        //===============================================/

        $this->data['departemen'] = $this->dmaster->get_data_departemen(array(
            "connect" => TRUE,
            "plant" => $this->data['user']->gsber,
            "encrypt" => array("id")
        ));
        $this->data['keterangan_lembur'] = $this->dmaster->get_data_keterangan_lembur(array(
            "connect" => TRUE,
            "tipe" => "lembur",
            "encrypt" => array("id")
        ));
        $this->data['alasan_ba'] = $this->dmaster->get_data_keterangan_lembur(array(
            "connect" => TRUE,
            "tipe" => "ba",
            "encrypt" => array("id")
        ));
        $this->data['plant'] = $this->data['user']->gsber;
        $this->data['no_spl'] = $this->generate_no_spl(array(
            "connect" => true
        ));
        // echo json_encode($this->data);exit();
        $this->load->view("transaksi/tambah", $this->data);
    }

    public function detail($key = NULL)
    {
        //====must be initiate in every view function====/
        $this->general->check_session();
        //===============================================/

        $no_spl = str_replace("-", "/", $key);
        $this->data['data_spl'] = $this->get_data_spl(array(
            "connect" => TRUE,
            "no_spl" => $no_spl,
            "data" => "header",
            "single_row" => true,
            "encrypt" => array("id_departemen", "id_seksi")
        ));

        if (
            !$key || empty($this->data['data_spl'])
        )
            show_404();

        // echo json_encode($this->data);exit();
        $this->load->view("transaksi/detail", $this->data);
    }

    public function edit()
    {
        //====must be initiate in every view function====/
        // $this->general->check_access();
        //===============================================/

        $this->data['plant'] = $this->data['user']->gsber;
        $this->load->view("transaksi/edit", $this->data);
    }

    public function realisasi($key = NULL)
    {
        //====must be initiate in every view function====/
        $this->general->check_session();
        //===============================================/

        $this->data['plant'] = $this->data['user']->gsber;
        $no_spl = str_replace("-", "/", $key);
        $this->data['data_spl'] = $this->get_data_spl(array(
            "connect" => TRUE,
            "no_spl" => $no_spl,
            "data" => "header",
            "tipe_list" => "approval",
            "single_row" => true,
            "encrypt" => array("id_departemen", "id_seksi")
        ));

        if (
            !$key
            ||
            empty($this->data['data_spl'])
            ||
            ($this->data['data_spl'] && $this->data['data_spl']->realisasi == 1)
        )
            show_404();

        $this->load->view("transaksi/realisasi", $this->data);
    }

    //==================================================//
    /*                    Get data                      */
    //==================================================//
    public function get($param = NULL)
    {
        switch ($param) {
            case 'spl':
                $param_ = array(
                    "connect" => TRUE,
                    "return" => $this->input->post("return", TRUE),
                    "data" => $this->input->post("data", TRUE),
                    "tipe_list" => $this->input->post("page", TRUE),
                    "no_spl" => $this->input->post("no_spl", TRUE),
                    "IN_plant" => empty($this->input->post("IN_plant", TRUE)) ? $this->data['access_plant'] : $this->input->post("IN_plant", TRUE),
                    "IN_tahun" => empty($this->input->post("IN_tahun", TRUE)) ? NULL : $this->input->post("IN_tahun", TRUE),
                    "IN_departemen" => empty($this->input->post("IN_departemen", TRUE)) ? NULL : $this->input->post("IN_departemen", TRUE),
                    "all" => $this->input->post("all", TRUE),
                    "encrypt" => array("id_departemen", "id_seksi")
                );
                $status = $this->input->post("status", TRUE);
                $status_all = array("onprogress", "finish", "completed", "rejected");
                if (!empty($status))
                    if (in_array("onprogress", $status) == true) {
                        $param_['NOT_IN_status'] = $this->general->emptyconvert(
                            array_diff($status_all, $status)
                        );
                    } else {
                        $param_['IN_status'] = $status;
                    }

                $this->get_data_spl($param_);
                break;
            case 'history':
                $param_ = array(
                    "connect" => TRUE,
                    "no_spl" => $this->input->post("no_spl", TRUE),
                );
                $result = $this->dtransaksi->get_data_history($param_);
                echo json_encode($result);
                exit();
                break;
            case 'karyawan':
                $post = $this->input->post_get(NULL, TRUE);
                $param_karyawan = array(
                    "connect" => TRUE,
                    "search" => @$post['search'],
                    "plant" => @$post['plant'],
                    "id_departemen" => (isset($post['id_departemen']) ? $this->generate->kirana_decrypt($post['id_departemen']) : NULL),
                    "id_seksie" => (isset($post['id_seksie']) ? $this->generate->kirana_decrypt($post['id_seksie']) : NULL),
                    "IN_golongan" => @$post['in_golongan'],
                    "not_in_nik" => @$post['not_in_nik'],
                    "tanggal_spl" => (isset($post['tanggal_spl']) ? $this->generate->regenerateDateFormat($post['tanggal_spl']) : NULL),
                    "return" => @$post['return']
                );
                $this->get_karyawan($param_karyawan);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    //==================================================//
    /*                    Set data                      */
    //==================================================//
    public function set($param = NULL)
    {
        switch ($param) {
            case 'pengajuan':
                $return = $this->set_pengajuan();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    //==================================================//
    /*                   Save data                      */
    //==================================================//
    public function save($param = NULL)
    {
        switch ($param) {
            case 'pengajuan':
                $this->save_pengajuan();
                break;
            case 'approval':
                $this->save_approval();
                break;
            case 'realisasi':
                $this->save_realisasi();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    /**********************************/
    /*			  private  			  */
    /**********************************/
    private function get_karyawan($param = NULL)
    {
        $result = $this->dtransaksi->get_data_karyawan($param);

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "autocomplete") {
            $result  = array(
                "total_count" => count($result),
                "incomplete_results" => false,
                "items" => $result
            );
            echo json_encode($result);
        } else {
            return $result;
        }
    }

    private function get_data_spl($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dtransaksi->get_spl_header_list($param);
                // if ($result) {
                //     if (isset($param['return']) && $param['return'] == "datatables")
                //         $result = json_decode($result, true);

                //     if (is_object($result) === TRUE) {
                //     } else {
                //         $newResult = array();
                //         foreach ($result as $key => $data) {
                //             $newData = array();
                //             if ($key == 'data') {
                //                 foreach ($data as $val) {
                //                     $newData[] = $val;
                //                 }
                //             } else {
                //                 $newData = $data;
                //             }
                //             $newResult[$key] = $newData;
                //         }

                //         $result = $newResult;
                //         if (isset($param['return']) && $param['return'] == "datatables")
                //             $result = $this->general->jsonify($result);
                //     }
                // }
                break;
            case 'complete':
                $param["single_row"] = TRUE;
                $result = $this->dtransaksi->get_spl_header_list($param);
                unset($param['encrypt']);
                if ($result) {
                    $param_detail = array(
                        "tipe" => "pengajuan",
                        "status" => "ok"
                    );
                    $result->detail = $this->dtransaksi->get_spl_detail(array_merge($param, $param_detail));

                    $param_detail = array(
                        "tipe" => "realisasi"
                    );
                    $result->detail_realisasi = $this->dtransaksi->get_spl_detail(array_merge($param, $param_detail));

                    $result->history = $this->dtransaksi->get_data_history($param);
                }
                break;

            default:
                $result = $this->dtransaksi->get_spl_header_list($param);
                break;
        }

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            // echo $result;
            echo json_encode($result);
        } else {
            return $result;
        }
    }

    private function save_pengajuan($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);
        $plant = $post['plant'];
        $no_spl = $post['no_spl'];
        $tanggal_spl = $this->generate->regenerateDateFormat($post['tanggal_spl']);
        $change_no = false;

        //cek tanggal
        $today = new DateTime(date('Y-m-d'));
        $date_spl = new DateTime($tanggal_spl);
        $diff = $today->diff($date_spl)->format("%r%a");
        $limit = (date('N') == 1 ? -2 : -1);
        if ($diff < $limit) {
            $sts = "notOK";
            $msg = "Tanggal SPL melebihi batas.";
            $html = TRUE;

            $return = array("sts" => $sts, "msg" => $msg, "html" => $html);
            echo json_encode($return);
            exit();
        }

        if (empty($post['nik'])) {
            $sts = "notOK";
            $msg = "Data Karyawan Tidak Boleh Kosong";
            $html = TRUE;

            $return = array("sts" => $sts, "msg" => $msg, "html" => $html);
            echo json_encode($return);
            exit();
        }

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $cek_duplikat   = $this->generate_no_spl(
            array(
                "connect" => FALSE,
                "plant" => $plant,
            )
        );

        if ($cek_duplikat !== $no_spl) {
            $data_header['no_spl'] = $cek_duplikat;
            $post['no_spl'] = $cek_duplikat;
            $no_spl = $data_header['no_spl'];
            $change_no = true;
        }

        //===========save header===========//
        $status = $this->generate_status(
            array(
                "connect" => FALSE,
                "plant" => $plant,
                "no_spl" => $post['no_spl'],
                "action" => $post['action'],
                "post" => $post
            )
        );

        $unit = [];
        if (!empty($post['id_unit'])) {
            foreach ($post['id_unit'] as $dt) {
                $unit[] = $this->generate->kirana_decrypt($dt);
            }
        }

        $id_seksi = (isset($post['id_seksie']) && $post['id_seksie'] != '') ? $this->generate->kirana_decrypt($post['id_seksie']) : 0;
        $data_header = array(
            "no_spl" => $post['no_spl'],
            "plant" => $post['plant'],
            "tanggal_spl" => $tanggal_spl,
            "id_departemen" => $this->generate->kirana_decrypt($post['id_departemen']),
            "id_seksi" => $id_seksi,
            "unit" => empty($unit) ? NULL : implode(";", $unit),
            "plan_lembur" => $post['plan'],
            "rincian_plan_lembur" => $post['rincian_plan'],
            "status" => $status,
            "keterangan_lembur" => $post['keterangan_lembur'],
            "alasan_ba" => $post['alasan_ba'],
        );

        //simulasi SAP (cek status employee)
        $list_nik = array();
        if (isset($post['nik'])) {
            foreach ($post['nik'] as $index => $nik) {
                $list_nik[] = $nik;
            }
        }
        $cek_employee_sap = $this->cekStatusEmployee(array(
            "connect" => FALSE,
            "tanggal_spl" => $tanggal_spl,
            "detail" => $list_nik,
        ));

        if ($cek_employee_sap['sts'] == 'NotOK') {
            $msg = "Periksa kembali data karyawan. " . $cek_employee_sap['msg'];
            $sts = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg, 'result' => $cek_employee_sap['data']);
            echo json_encode($return);
            exit();
        }

        $current_status = NULL;

        //========save file attachment========//
        if (isset($_FILES['lampiran']) && !empty($_FILES['lampiran']['name'][0])) {
            $jml_file = count($_FILES['lampiran']['name']);

            if ($jml_file > 1) {
                $msg    = "You can only upload maximum 1 file";
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            //GENERATE NEW NAME
            $newname = array();
            for ($i = 0; $i < $jml_file; $i++) {
                if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'][$i] == 0 && $_FILES['lampiran']['name'][$i] !== "") {
                    array_push($newname, str_replace("/", "-", $no_spl));
                }
            }

            $target_dir = KIRANA_PATH_FILE . 'spl/pengajuan';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            //UPLOADING
            $config['upload_path']   = $target_dir;
            $config['allowed_types'] = 'jpg|png|pdf';
            $config['max_size'] = 2000;

            $id_file = NULL;
            $file = $this->general->upload_files($_FILES['lampiran'], $newname, $config)[0];
            if ($file) {
                $data_file = array(
                    'filename'     => $file['filename'],
                    'size'         => $file['size'],
                    'ext'          => pathinfo($file['full_path'], PATHINFO_EXTENSION),
                    'location'     => $file['url'],
                    'no_spl'       => $no_spl,
                    'tipe'         => "pengajuan",
                    'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
                    'tanggal_edit' => $datetime,
                );

                $this->dgeneral->insert('tbl_spl_file', $data_file);
                $id_file = $this->db->insert_id();
            }
            $data_header['id_file'] = $id_file;
        }

        if (isset($_FILES['lampiran_ba']) && !empty($_FILES['lampiran_ba']['name'][0])) {
            $jml_file = count($_FILES['lampiran_ba']['name']);

            if ($jml_file > 1) {
                $msg    = "You can only upload maximum 1 file";
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            //GENERATE NEW NAME
            $newname = array();
            for ($i = 0; $i < $jml_file; $i++) {
                if (isset($_FILES['lampiran_ba']) && $_FILES['lampiran_ba']['error'][$i] == 0 && $_FILES['lampiran_ba']['name'][$i] !== "") {
                    array_push($newname, str_replace("/", "-", $no_spl) . '-ba');
                }
            }

            $target_dir = KIRANA_PATH_FILE . 'spl/pengajuan';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            //UPLOADING
            $config['upload_path']   = $target_dir;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 2000;

            $id_file_ba = NULL;
            $file_ba = $this->general->upload_files($_FILES['lampiran_ba'], $newname, $config)[0];
            if ($file_ba) {
                $data_file_ba = array(
                    'filename'     => $file_ba['filename'],
                    'size'         => $file_ba['size'],
                    'ext'          => pathinfo($file_ba['full_path'], PATHINFO_EXTENSION),
                    'location'     => $file_ba['url'],
                    'no_spl'       => $no_spl,
                    'tipe'         => "pengajuan_ba",
                    'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
                    'tanggal_edit' => $datetime,
                );

                $this->dgeneral->insert('tbl_spl_file', $data_file_ba);
                $id_file_ba = $this->db->insert_id();
            }
            $data_header['id_file_ba'] = $id_file_ba;
        }
        //====================================//

        $data_header = $this->dgeneral->basic_column("insert_full", $data_header, $datetime);
        $this->dgeneral->insert('tbl_spl_header', $data_header);

        //===========save spl detail===========//
        if (isset($post['nik'])) {
            foreach ($post['nik'] as $index => $nik) {
                $post['no_urut'] = $post['number'][$index];
                // $post['nik'] = $nik;
                $post['waktu_mulai'] = $post['jam_mulai'][$index];
                $post['waktu_selesai'] = $post['jam_selesai'][$index];
                // $post['ket'] = $post['keterangan'][$index];

                //cek data lembur karyawan
                $cek_data_karyawan = $this->dtransaksi->get_data_lembur_karyawan(array(
                    "nik" => $nik,
                    "jam_mulai" => $tanggal_spl . " " . $post['waktu_mulai'],
                    "jam_selesai" => $tanggal_spl . " " . $post['waktu_selesai'],
                    // "tipe" => 'pengajuan',
                    "cek_data_exist" => TRUE
                ));

                if ($cek_data_karyawan) {
                    $sts = "notOK";
                    $msg = "Karyawan dengan NIK $nik sudah ada pengajuan lembur di periode jam yang sama.";
                    $html = TRUE;

                    $return = array("sts" => $sts, "msg" => $msg, "html" => $html);
                    echo json_encode($return);
                    exit();
                }

                $data_detail = array(
                    "plant" => $post['plant'],
                    "no_spl" => $no_spl,
                    "no_urut" => $post['no_urut'],
                    "nik" => $nik,
                    "jam_mulai" => $tanggal_spl . " " . $post['waktu_mulai'],
                    "jam_selesai" => $tanggal_spl . " " . $post['waktu_selesai'],
                    // "keterangan" => $post['ket'],
                    "status" => "ok"
                );
                $data_detail = $this->dgeneral->basic_column('insert_full', $data_detail, $datetime);
                $this->dgeneral->insert('tbl_spl_detail', $data_detail);
            }
        }

        //===========save spl log===========//
        $data_log = array(
            "no_spl" => $no_spl,
            "tgl_status" => $datetime,
            "status" => 1,
            "action" => $post['action'],
            "comment" => "", //$post['penjelasan']
        );
        $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
        $this->dgeneral->insert('tbl_spl_log_status', $data_log);

        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $sts = "OK";
            $msg = "Data berhasil ditambahkan";
            if ($change_no) {
                $msg = "Data berhasil ditambahkan, namun nomor SPL diubah menjadi " . $no_spl . ".";
            }
            $spl_data = $this->get_data_spl(
                array(
                    "connect" => TRUE,
                    "data" => "header",
                    "no_spl" => $no_spl,
                    "single_row" => TRUE
                )
            );
            $this->send_email(
                array(
                    "post" => $post,
                    "header" => $spl_data
                )
            );
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
        exit();
    }

    private function save_approval($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);
        $plant = $post['plant'];
        $no_spl = $post['no_spl'];
        $tanggal_spl = $this->generate->regenerateDateFormat($post['tanggal_spl']);
        $action = $post['action'];

        $data_spl = $this->dtransaksi->get_spl_header_list(array(
            "connect" => TRUE,
            "no_spl" => $no_spl,
            "tipe_list" => "approval",
            "single_row" => TRUE
        ));

        if (!$data_spl) {
            $sts = "notOK";
            $msg = "Anda tidak memiliki akses terhadap SPL ini. Refresh halaman untuk melihat status SPL terbaru.";
            $html = TRUE;

            $return = array("sts" => $sts, "msg" => $msg, "html" => $html);
            echo json_encode($return);
            exit();
        }

        if ($action == "confirm") {
            $current_status = 3; // Mgr Kantor
        } else {
            $current_status = $data_spl->status;
        }

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        //===========save header===========//
        $status = $this->generate_status(
            array(
                "connect" => FALSE,
                "plant" => $plant,
                "no_spl" => $post['no_spl'],
                "action" => $action,
                "post" => $post
            )
        );

        $data_row_header = array(
            "status" => $status,
        );

        $data_row_header = $this->dgeneral->basic_column("update", $data_row_header, $datetime);
        $this->dgeneral->update('tbl_spl_header', $data_row_header, array(
            array(
                'kolom' => 'no_spl',
                'value' => $no_spl
            )
        ));

        //===========save spl log===========//
        $data_log = array(
            "no_spl" => $no_spl,
            "tgl_status" => $datetime,
            "status" => $current_status,
            "action" => $post['action'],
            "comment" => @$post['komentar']
        );
        $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
        $this->dgeneral->insert('tbl_spl_log_status', $data_log);

        if ($action == "approve") {
            //======set all detail not active======//                
            $data = $this->dgeneral->basic_column('delete', NULL, $datetime);
            $this->dgeneral->update("tbl_spl_detail", $data, array(
                array(
                    'kolom' => 'no_spl',
                    'value' => $no_spl
                ),
                array(
                    'kolom' => 'status',
                    'value' => 'ok'
                ),
                array(
                    'kolom' => 'na',
                    'value' => 'n'
                ),
                array(
                    'kolom' => 'del',
                    'value' => 'n'
                )
            ));
            //===========save spl detail===========//
            if (isset($post['nik'])) {
                foreach ($post['nik'] as $index => $nik) {
                    // $post['no_urut'] = $post['no_urut'][$index];
                    // $post['nik'] = $nik;
                    $post['waktu_mulai'] = $post['jam_mulai'][$index];
                    $post['waktu_selesai'] = $post['jam_selesai'][$index];
                    // $post['ket'] = $post['keterangan'][$index];

                    // cek data lembur karyawan
                    $cek_data_karyawan = $this->dtransaksi->get_data_lembur_karyawan(array(
                        "nik" => $nik,
                        "jam_mulai" => $tanggal_spl . " " . $post['waktu_mulai'],
                        "jam_selesai" => $tanggal_spl . " " . $post['waktu_selesai'],
                        // "tipe" => 'pengajuan',
                        "cek_data_exist" => TRUE
                    ));

                    if ($cek_data_karyawan) {
                        $sts = "notOK";
                        $msg = "Karyawan dengan NIK $nik sudah ada pengajuan lembur di periode jam yang sama.";
                        $html = TRUE;

                        $return = array("sts" => $sts, "msg" => $msg, "html" => $html);
                        echo json_encode($return);
                        exit();
                    }

                    $data_detail = array(
                        "plant" => $post['plant'],
                        "no_spl" => $no_spl,
                        "no_urut" => $post['no_urut'][$index],
                        "nik" => $nik,
                        "jam_mulai" => $tanggal_spl . " " . $post['waktu_mulai'],
                        "jam_selesai" => $tanggal_spl . " " . $post['waktu_selesai'],
                        // "keterangan" => $post['ket'],
                        "status" => $post['status_detail'][$index],
                    );
                    $data_detail = $this->dgeneral->basic_column('insert_full', $data_detail, $datetime);
                    $this->dgeneral->insert('tbl_spl_detail', $data_detail);
                }
            }
        }

        if ($status == "completed") {
            //kirim SAP
            $sendSAP = $this->sendSAP(array(
                "connect" => FALSE,
                "data" => $data_spl,
                "detail" => $this->dtransaksi->get_spl_detail(array(
                    "connect" => FALSE,
                    "no_spl" => $data_spl->no_spl,
                    "tipe" => "realisasi",
                    "status" => "ok",
                )),
            ));

            if ($sendSAP['sts'] == 'NotOK') {
                $msg = "Gagal Submit SPL ke SAP : " . $sendSAP['msg'];
                $sts = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg, 'result' => $sendSAP['data']);
                echo json_encode($return);
                exit();
            }
        }

        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil disimpan";
            $sts = "OK";
            $spl_data = $this->get_data_spl(
                array(
                    "connect" => TRUE,
                    "data" => "header",
                    "no_spl" => $no_spl,
                    "single_row" => TRUE
                )
            );
            $this->send_email(
                array(
                    "post" => $post,
                    "header" => $spl_data
                )
            );
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
        exit();
    }

    private function save_realisasi($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);
        $plant = $post['plant'];
        $no_spl = $post['no_spl'];
        $tanggal_spl = $this->generate->regenerateDateFormat($post['tanggal_spl']);
        // $action = $post['action'];

        $data_spl = $this->dtransaksi->get_spl_header_list(array(
            "connect" => TRUE,
            "no_spl" => $no_spl,
            "single_row" => TRUE
        ));

        //cek tanggal
        // if (date('Y-m-d') < $data_spl->tanggal_spl) {
        //     $msg = "Realisasi tidak dapat dibuat.";
        //     $sts = "NotOK";
        //     $return = array('sts' => $sts, 'msg' => $msg);
        //     echo json_encode($return);
        //     exit();
        // }

        $current_status = $data_spl->status;

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        //simulasi SAP (cek status employee)
        $list_nik = array();
        if (isset($post['nik'])) {
            foreach ($post['nik'] as $index => $nik) {
                $list_nik[] = $nik;
            }
        }
        $cek_employee_sap = $this->cekStatusEmployee(array(
            "connect" => FALSE,
            // "data" => $data_spl,
            "tanggal_spl" => $data_spl->tanggal_spl,
            "detail" => $list_nik,
        ));

        if ($cek_employee_sap['sts'] == 'NotOK') {
            $msg = "Periksa kembali data karyawan. " . $cek_employee_sap['msg'];
            $sts = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg, 'result' => $cek_employee_sap['data']);
            echo json_encode($return);
            exit();
        }

        //===========save spl log===========//
        $data_log = array(
            "no_spl" => $no_spl,
            "tgl_status" => $datetime,
            "status" => 1,
            "action" => 'realisasi',
            "comment" => ''
        );
        $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
        $this->dgeneral->insert('tbl_spl_log_status', $data_log);

        //======set all detail not active======//                
        $data = $this->dgeneral->basic_column('delete', NULL, $datetime);
        $this->dgeneral->update("tbl_spl_detail", $data, array(
            array(
                'kolom' => 'no_spl',
                'value' => $no_spl
            ),
            array(
                'kolom' => 'tipe',
                'value' => 'realisasi'
            ),
            array(
                'kolom' => 'na',
                'value' => 'n'
            ),
            array(
                'kolom' => 'del',
                'value' => 'n'
            )
        ));
        //===========save spl detail===========//
        if (isset($post['nik'])) {
            foreach ($post['nik'] as $index => $nik) {
                if ($post['status_detail'][$index] == "ok") {
                    $post['waktu_mulai'] = $post['jam_mulai'][$index];
                    $post['waktu_selesai'] = $post['jam_selesai'][$index];
                    // $post['ket'] = $post['keterangan'][$index];

                    //cek data lembur karyawan
                    $cek_data_karyawan = $this->dtransaksi->get_data_lembur_karyawan(array(
                        "nik" => $nik,
                        "jam_mulai" => $tanggal_spl . " " . $post['waktu_mulai'],
                        "jam_selesai" => $tanggal_spl . " " . $post['waktu_selesai'],
                        "tipe" => 'realisasi',
                        "cek_data_exist" => TRUE
                    ));

                    if ($cek_data_karyawan) {
                        $sts = "notOK";
                        $msg = "Karyawan dengan NIK $nik sudah ada pengajuan lembur di periode jam yang sama.";
                        $html = TRUE;

                        $return = array("sts" => $sts, "msg" => $msg, "html" => $html);
                        echo json_encode($return);
                        exit();
                    }

                    $data_detail = array(
                        "plant" => $post['plant'],
                        "no_spl" => $no_spl,
                        "no_urut" => $post['no_urut'][$index],
                        "nik" => $nik,
                        "jam_mulai" => $tanggal_spl . " " . $post['waktu_mulai'],
                        "jam_selesai" => $tanggal_spl . " " . $post['waktu_selesai'],
                        // "keterangan" => $post['ket'],
                        "status" => $post['status_detail'][$index],
                        "tipe" => "realisasi",
                    );
                    $data_detail = $this->dgeneral->basic_column('insert_full', $data_detail, $datetime);
                    $this->dgeneral->insert('tbl_spl_detail', $data_detail);
                }
            }
        }

        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil disimpan";
            $sts = "OK";
            $spl_data = $this->get_data_spl(
                array(
                    "connect" => TRUE,
                    "data" => "header",
                    "no_spl" => $no_spl,
                    "single_row" => TRUE
                )
            );
            $this->send_email(
                array(
                    "post" => $post,
                    "header" => $spl_data
                )
            );
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
        exit();
    }

    private function generate_status($param = NULL)
    {
        $post = $param['post'];
        $param_status = array(
            "connect" => $param['connect'],
            "plant" => $param['plant'],
            "no_spl" => $param['no_spl'],
            "single_row" => true,
        );
        $data_spl = $this->dtransaksi->get_spl_header($param_status);
        $current_status = (empty($data_spl)) ? 1 : $data_spl->status;

        if (isset($param['action'])) {
            switch ($param['action']) {
                case 'submit':
                case 'approve':
                    $status = $current_status + 1;
                    //if departemen kantor, QC, Support Function, Depo
                    if ($status == 2) { //manager terkait
                        $data_departemen = $this->dmaster->get_data_departemen(array(
                            "connect" => FALSE,
                            "id_departemen" => $this->generate->kirana_decrypt($post['id_departemen']),
                            "single_row" => TRUE
                        ));
                        if (
                            $data_departemen
                            &&
                            (strpos($data_departemen->nama, 'DEPARTEMEN KANTOR') !== false
                                ||
                                strpos($data_departemen->nama, 'DEPARTEMEN QUALITY') !== false
                                ||
                                strpos($data_departemen->nama, 'SUPPORT FUNCTION') !== false
                                ||
                                strpos($data_departemen->nama, 'DEPO') !== false
                            )
                        ) {
                            // echo json_encode($data_departemen);
                            // exit();
                            $status++;
                        }
                    }
                    // echo json_encode($status);
                    // exit();

                    $cek_role = $this->dmaster->get_data_role(array(
                        "connect" => FALSE,
                        "level" => $status,
                        "single_row" => TRUE
                    ));
                    if (empty($cek_role)) {
                        $status = "finish";
                    }
                    //===========================//
                    break;
                case 'reject':
                    $status = 'rejected';
                    break;
                case 'confirm':
                    $status = "completed";
                    break;
                case 'finish':
                    $status = "finish";
                    break;
                default:
                    $status = $current_status + 1;
                    break;
            }

            return $status;
        }
    }

    private function generate_no_spl($param = NULL)
    {
        $romawi     = array('I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
        $separator  = "/";
        $plant      = isset($param['plant']) ? $param['plant'] : $this->data['user']->gsber;
        $month      = $romawi[(date('m') - 1)];
        $year       = date('Y');

        $no = (count($this->dtransaksi->get_nomor_spl(
            array(
                "connect" => $param['connect'],
                "plant" => $plant,
                "year" => $year,
                "month" => $month
            )
        )) + 1);
        return "SPL" . $separator . $no . $separator . $plant . $separator . $month . $separator . $year;
    }

    private function generate_email_message($param = NULL)
    {
        $message = "<html>
                        <body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
                        <center style='width: 100%;'>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                Notifikasi Email Aplikasi SPL Online
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
                                            <h1 style='margin-bottom: 0;'>SPL Online</h1>
                                            <hr style='border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;'/>
                                            <h3 style='margin-top: 0;'>Notifikasi Email</h3>
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
                                                    <p><strong>Kepada :<br><br> " . ($param['nama_to'] ? $param['nama_to'] : 'Bapak & Ibu') . "</strong></p>
                                                    <p>Berikut adalah pemberitahuan dari SPL Online yang menunggu persetujuan Anda.</p>
                                                        <table style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
                                                            <tr>
                                                                <td>Nomor SPL</td>
                                                                <td>:</td>
                                                                <td>" . $param['no_spl'] . "</td> 
                                                            </tr>
                                                            <tr>
                                                                <td>Tanggal SPL</td>
                                                                <td>:</td>
                                                                <td>" . $param['tanggal_spl'] . "</td> 
                                                            </tr>
                                                            <tr>
                                                                <td>Status</td>
                                                                <td>:</td>
                                                                <td>" . $param['status'] . "</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Oleh</td>
                                                                <td>:</td>
                                                                <td>" . $param['oleh'] . "</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tanggal</td>
                                                                <td>:</td>
                                                                <td>" . strftime('%A, %d %B %Y') . "</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Catatan</td>
                                                                <td>:</td>
                                                                <td>" . ($param['comment'] ? $param['comment'] : '-') . "</td>
                                                            </tr>
                                                        </table>
                                                        <p>Selanjutnya anda dapat melakukan review pada SPL tersebut</p><p>melalui aplikasi SPL di Portal Kiranaku.</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align='left'
                                                        style='background-color: #ffffff; padding: 15px; border-top: 1px dashed #386d22;'>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align='center' style='padding-bottom: 10px; font-size:15px;'><b>Click Tombol dibawah ini untuk login pada Portal Kiranaku</b></td>
                                                </tr>
                                                <tr>
                                                    <td align='center' style='padding-bottom: 20px;'>
                                                        <a href='" . base_url() . 'spl/transaksi/pengajuan/approve' . "' style='color: #fff;
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
                                                            border-radius: 4px;'>Login</a>
                                                    </td>
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
                                            <small>Kiranaku Auto-Mail System</small><br/>
                                            <strong style='color: #214014; font-size: 10px;'>Terkirim pada " . date('d.m.Y H:i:s') . "</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                        </center>
                        </body>
                    </html>";

        return $message;
    }

    private function send_email($param = NULL)
    {
        $post = (object) $param['post'];
        $header = $param['header'];
        $action = $header->status == "finish" ? "finish" : $post->action;

        $action_email = "approve";
        $messageNotif = 'SPL dengan nomor ' . $header->no_spl . ' menunggu persetujuan Anda';
        switch ($action) {
            case 'submit':
                $status = "Submit";
                break;
            case 'approve':
                $status = "Approved";
                break;
            case 'reject':
                $status = "Reject";
                $action_email = "reject";
                $messageNotif = 'SPL dengan nomor ' . $header->no_spl . ' Tidak Disetujui';
                break;
            case 'drop':
                $status = "Dropped";
                break;
            case 'deleted':
                $status = "Deleted";
                break;
            case 'confirm':
                $status = "Completed";
                break;
            case 'finish':
                if ($header->realisasi == 1) {
                    $status = "Realisasi";
                } else {
                    $status = "Finish";
                }
                break;
        }

        $comment = (isset($post->komentar)) ? $post->komentar : "-";

        $data_recipient = $this->dtransaksi->get_notif_recipient(
            array(
                "connect" => TRUE,
                "no_spl" => $header->no_spl,
                "action" => $action_email,
            )
        );

        $email_cc = array();
        $email_to = array();
        $email_bcc = array();
        $mobile_recipient = array();
        foreach ($data_recipient as $dt) {
            $email_to[] = ENVIRONMENT == 'development' ? "rainhard.rahakbauw@kiranamegatara.com" : $dt->email;
            if ($dt->nama !== "" && $dt->gender !== "") {
                $nama_to[] = $dt->gender . " " . ucwords(strtolower($dt->nama)) . "<br>";
            }
            if ($dt->deviceId && $dt->deviceId != '')
                $mobile_recipient[] = $dt->deviceId;
        }
        if (ENVIRONMENT == 'development') {
            $email_cc[] = "benazi.bahari@kiranamegatara.com";
        }

        $message = $this->generate_email_message(
            array(
                "nama_to" => empty($nama_to) ? "" : implode("", array_unique($nama_to)),
                "no_spl" => $header->no_spl,
                "tanggal_spl" => $header->tanggal_spl_format,
                "status" => $status,
                "oleh" => ucwords(strtolower(base64_decode($this->session->userdata("-gelar-")) . " " . base64_decode($this->session->userdata("-nama-")))),
                "comment" => $comment
            )
        );

        if (count($email_to) > 0)
            $this->general->send_email_new(
                array(
                    "subject" => "Notifikasi Pengajuan SPL",
                    "from_alias" => "Portal SPL",
                    "to" => array_unique($email_to),
                    "cc" => array_unique($email_cc),
                    "bcc" => array_unique($email_bcc),
                    "message" => $message,
                    "attachment" => NULL
                )
            );

        //=========send notif mobile=========//
        $mobile_recipient = array_unique($mobile_recipient);
        if (count($mobile_recipient) > 0)
            $this->send_notif(
                array(
                    "recipient" => $mobile_recipient,
                    "notif" => array(
                        'title' => 'K-MAPP',
                        'body' => $messageNotif,
                        'sound' => 'default',
                        'badge' => '1'
                    ),
                    "data" => array(
                        "notification_foreground" => "true",
                        "link" => "/spl/detail/" . str_replace("/", "-", $header->no_spl) . "/load/"
                    )
                )
            );

        $wa = array_values(array_filter($data_recipient, function ($v) {
            return empty($v->telepon_pribadi) === false && $v->nama_role !== 'Admin SPL';
        }));
        if (count($wa) > 0)
            $this->send_wa(
                array(
                    "recipient" => array_unique(
                        array_map(function ($v) {
                            return (substr($v->telepon_pribadi, 0, 1) == '0' ? '62' . ltrim($v->telepon_pribadi, "0") : $v->telepon_pribadi);
                        }, $wa)
                    ),
                    "message" => array(
                        'Bapak/Ibu',
                        'K-MAPP',
                        $messageNotif . "."
                    )
                )
            );
        //===================================//

        return true;
    }

    private function send_notif($param = NULL)
    {
        if ($param['recipient']) {
            $client = new GuzzleHttp\Client();

            try {
                $serverKey = SERVER_FCM_KEY;
                $result = $client->request(
                    'POST',
                    'https://fcm.googleapis.com/fcm/send',
                    array(
                        'debug' => false,
                        'headers' => array(
                            'Content-Type' => 'application/json',
                            'Authorization' => 'key=' . $serverKey
                        ),
                        'body' => json_encode(
                            array(
                                'registration_ids' => $param['recipient'],
                                'notification' => $param['notif'],
                                'data' => $this->general->emptyconvert(@$param['data']),
                                'priority' => 'high'
                            )
                        )
                    )
                );

                return true;
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                echo json_encode(
                    array(
                        'status' => false,
                        'message' => $e->getResponse()->getBody()->getContents()
                    )
                );
                exit();
            } catch (\GuzzleHttp\Exception\ServerException $e) {
                echo json_encode(
                    array(
                        'status' => false,
                        'message' => $e->getMessage()
                    )
                );
                exit();
            }
        } else
            return true;
    }

    private function send_wa($param = NULL)
    {
        $client = new GuzzleHttp\Client();

        try {
            $result = $client->request(
                'POST',
                'https://icwaba.damcorp.id/whatsapp/sendHsm/notif_whatsapp',
                array(
                    'debug' => DEBUG_MODE,
                    'headers' => array(
                        'Content-Type' => 'application/json'
                    ),
                    'body' => json_encode(
                        array(
                            'token' => SERVER_WA_KEY,
                            'to' => $param['recipient'],
                            'param' => $param['message']
                        )
                    )
                )
            );

            return true;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            echo json_encode(
                array(
                    'status' => false,
                    'message' => $e->getResponse()->getBody()->getContents()
                )
            );
            exit();
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            echo json_encode(
                array(
                    'status' => false,
                    'message' => $e->getMessage()
                )
            );
            exit();
        }
    }

    private function cekStatusEmployee($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $data_spl = isset($param['data']) ? $param['data'] : NULL;
        $this->connectSAP("ESS");

        $type    = array();
        $message = array();
        $data_send = array();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->dgeneral->begin_transaction();

        if ($this->data['sap']->getStatus() == SAPRFC_OK) {
            if (!empty($param['detail'])) {
                $table = array();
                foreach ($param['detail'] as $nik) {
                    $detail = array(
                        "PERNR" => $nik,
                        "ENDDA" => date_format(date_create($param['tanggal_spl']), "Ymd"),
                        "BEGDA" => date_format(date_create($param['tanggal_spl']), "Ymd"),
                        // "PERSG" => "",
                        // "PTEXT" => "",
                    );
                    $table[] = $detail;
                }

                $param_rfc = array(
                    array("TABLE", "T_EMPSTS", $table),
                    array("TABLE", "T_RETURN", array())
                );

                //rfc
                $result = $this->data['sap']->callFunction("Z_RFC_READ_EMPLOYEE_STATUS", $param_rfc);
                // echo json_encode(array("param_rfc" => $param_rfc, "result" => $result));
                // exit();

                //cek kalo ada error
                $iserror = false;
                if (!empty($result["T_RETURN"])) {
                    foreach ($result["T_RETURN"] as $return) {
                        if ($return['TYPE'] == "E") {
                            $iserror = true;
                            break;
                        }
                    }
                }

                if (
                    $this->data['sap']->getStatus() == SAPRFC_OK
                    && empty($result["T_RETURN"])
                    && !$iserror
                ) {
                    $data_row_log = array(
                        'app'           => 'DATA RFC READ EMPLOYEE STATUS',
                        'rfc_name'      => 'Z_RFC_READ_EMPLOYEE_STATUS',
                        'log_code'      => 'S',
                        'log_status'    => 'Berhasil',
                        'log_desc'      => "Berhasil READ EMPLOYEE STATUS",
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );

                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                } else {
                    if ($result["T_RETURN"]) {
                        foreach ($result["T_RETURN"] as $return) {
                            // if ($return['PTEXT'] != "Active") {
                            //     $status = ($return['PTEXT'] ? $return['PTEXT'] : "NIK Tidak Ditemukan");
                            //     $type[] = "E"; //$return['TYPE'];
                            //     $message[] = "NIK " . $return['PERNR'] . " : " . $status;
                            // }
                            $type[]    = $return['TYPE'];
                            $message[] = $return['MESSAGE'];
                        }
                    } else {
                        $type[]    = 'E';
                        $message[] = "Error tanpa message"; //$result;
                    }
                    $data_row_log = array(
                        'app'           => 'DATA RFC READ EMPLOYEE STATUS',
                        'rfc_name'      => 'Z_RFC_READ_EMPLOYEE_STATUS',
                        'log_code'      => implode(" , ", $type),
                        'log_status'    => 'Gagal',
                        'log_desc'      => "Read Employee Status: " . implode(" , ", $message),
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );

                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                }
                $data_send[] = $result;
            }
        } else {
            $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
            $data_row_log = array(
                'app'           => 'DATA RFC READ EMPLOYEE STATUS',
                'rfc_name'      => 'Z_RFC_READ_EMPLOYEE_STATUS',
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
            $msg = "Berhasil Cek Status Employee";
            $sts = "OK";
            if (in_array('E', $type) === true) {
                $sts = "NotOK";
                $msg = implode(" , ", $message);
            }
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        } else {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        }
        return $return;
    }

    private function sendSAP($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $data_spl = isset($param['data']) ? $param['data'] : NULL;
        $this->connectSAP("ESS");

        $type    = array();
        $message = array();
        $data_send = array();
        if ($data_spl) {
            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            if ($this->data['sap']->getStatus() == SAPRFC_OK) {
                if (!empty($param['detail'])) {
                    $table = array();
                    foreach ($param['detail'] as $dt) {
                        $detail = array(
                            "PERNR" => $dt->nik,
                            "ENDDA" => date_format(date_create($data_spl->tanggal_spl), "Ymd"),
                            "BEGDA" => date_format(date_create($data_spl->tanggal_spl), "Ymd"),
                            "BEGUZ" => date_format(date_create($dt->jam_mulai_format), "His"),
                            "ENDUZ" => date_format(date_create($dt->jam_selesai_format), "His"),
                            "ZZSPLNUM" => $data_spl->no_spl,
                            "ZZSPLRSN" => $data_spl->keterangan_lembur,
                        );
                        $table[] = $detail;
                    }

                    $param_rfc = array(
                        array("TABLE", "T_SPL", $table),
                        array("TABLE", "T_RETURN", array())
                    );

                    //rfc
                    $result = $this->data['sap']->callFunction("Z_RFC_INSERT_INFOTYPE_2007", $param_rfc);
                    // echo json_encode(array("param_rfc" => $param_rfc, "result" => $result));
                    // exit();

                    //==========T_RETURN==========//
                    if ($result["T_RETURN"]) {
                        foreach ($result["T_RETURN"] as $return) {
                            $type[]    = $return['TYPE'];
                            $message[] = $return['MESSAGE'];
                        }
                    } else {
                        $type[]    = 'E';
                        $message[] = "Error tanpa message";
                    }

                    //cek kalo ada error
                    // $iserror = false;
                    // if (!empty($result["T_RETURN"])) {
                    //     foreach ($result["T_RETURN"] as $return) {
                    //         if ($return['TYPE'] == "E") {
                    //             $iserror = true;
                    //             break;
                    //         }
                    //     }
                    // }

                    if (
                        $this->data['sap']->getStatus() == SAPRFC_OK
                        // && !empty($result["T_RETURN"])
                        // && !$iserror
                        && in_array('E', $type) === false
                    ) {
                        $data_row_log = array(
                            'app'           => 'DATA RFC SUBMIT SPL TO SAP',
                            'rfc_name'      => 'Z_RFC_INSERT_INFOTYPE_2007',
                            'log_code'      => 'S',
                            'log_status'    => 'Berhasil',
                            'log_desc'      => "Berhasil mengirim data SPL " . $param['data']->no_spl,
                            'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                            'executed_date' => $datetime
                        );

                        $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                    } else {
                        // if ($result["T_RETURN"]) {
                        //     foreach ($result["T_RETURN"] as $return) {
                        //         $type[]    = $return['TYPE'];
                        //         $message[] = $return['MESSAGE'];
                        //     }
                        // } else {
                        //     $type[]    = 'E';
                        //     $message[] = "Error tanpa message"; //$result;
                        // }
                        $data_row_log = array(
                            'app'           => 'DATA RFC SUBMIT SPL TO SAP',
                            'rfc_name'      => 'Z_RFC_INSERT_INFOTYPE_2007',
                            'log_code'      => implode(" , ", $type),
                            'log_status'    => 'Gagal',
                            'log_desc'      => "Submit SPL Failed [T_RETURN]: " . implode(" , ", $message),
                            'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                            'executed_date' => $datetime
                        );

                        $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                    }
                    $data_send[] = $result;
                }
            } else {
                $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
                $data_row_log = array(
                    'app'           => 'DATA RFC SUBMIT SPL TO SAP',
                    'rfc_name'      => 'Z_RFC_INSERT_INFOTYPE_2007',
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
                $msg = "Berhasil Insert Data SPL";
                $sts = "OK";
                if (in_array('E', $type) === true) {
                    $sts = "NotOK";
                    $msg = implode(" , ", $message);
                }
            }

            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->closeDb();
        } else {
            $msg = "Data SPL tidak ditemukan";
            $sts = "NotOK";
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        } else {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        }
        return $return;
    }
}
