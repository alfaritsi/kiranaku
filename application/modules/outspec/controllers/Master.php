<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Outspec Confirmation
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
include_once APPPATH . "modules/outspec/controllers/BaseControllers.php";

class Master extends BaseControllers
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dmaster');
    }

    public function parameter()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $this->load->view("master/parameter", $data);
    }

    public function layout()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $this->load->view("master/layout", $data);
    }

    public function pallet()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $this->load->view("master/pallet", $data);
    }

    //==================================================//
    /*                    Get data                      */
    //==================================================//
    public function get($param = NULL)
    {
        switch ($param) {
            case 'parameter':
                $param_ = array(
                    "connect" => TRUE,
                    "return" => $this->input->post("return", TRUE),
                    "id_parameter" => (isset($_POST['id'])) ? $this->generate->kirana_decrypt($_POST['id']) : NULL,
                    "all" => $this->input->post("all", TRUE),
                    "encrypt" => array("id")
                );

                $this->get_data_parameter($param_);
                break;
            case 'layout':
                $param_ = array(
                    "connect" => TRUE,
                    "return" => $this->input->post("return", TRUE),
                    "id_layout" => (isset($_POST['id_layout'])) ? $this->generate->kirana_decrypt($_POST['id_layout']) : NULL,
                    "all" => $this->input->post("all", TRUE),
                    "encrypt" => array("id_layout")
                );

                $this->get_data_layout($param_);
                break;
            case 'pallet':
                $param_ = array(
                    "connect" => TRUE,
                    "return" => $this->input->post("return", TRUE),
                    "id_pallet" => (isset($_POST['id_pallet'])) ? $this->generate->kirana_decrypt($_POST['id_pallet']) : NULL,
                    "all" => $this->input->post("all", TRUE),
                    "encrypt" => array("id_pallet")
                );

                $this->get_data_pallet($param_);
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
            case 'parameter':
                $return = $this->set_parameter();
                break;
            case 'layout':
                $return = $this->set_layout();
                break;
            case 'pallet':
                $return = $this->set_pallet();
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
            case 'parameter':
                $this->save_parameter();
                break;
            case 'layout':
                $this->save_layout();
                break;
            case 'pallet':
                $this->save_pallet();
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
    private function get_data_parameter($param = NULL)
    {
        $result = $this->dmaster->get_data_parameter($param);

        if ($result) {
            if (isset($param['return']) && $param['return'] == "datatables")
                $result = json_decode($result, true);

            if (is_object($result) === TRUE) {
                $result->nama = htmlspecialchars_decode($result->nama);
            } else {
                $newResult = array();
                foreach ($result as $key => $data) {
                    $newData = array();
                    if ($key == 'data') {
                        foreach ($data as $val) {
                            $val['nama'] = htmlspecialchars_decode($val['nama']);

                            $newData[] = $val;
                        }
                    } else {
                        $newData = $data;
                    }
                    $newResult[$key] = $newData;
                }

                $result = $newResult;
                if (isset($param['return']) && $param['return'] == "datatables")
                    $result = $this->general->jsonify($result);
            }
        }

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
    }

    private function get_data_layout($param = NULL)
    {
        $result = $this->dmaster->get_data_layout($param);

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
    }

    private function get_data_pallet($param = NULL)
    {
        $result = $this->dmaster->get_data_pallet($param);

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
    }

    private function set_parameter($action = NULL)
    {
        if (isset($_POST['id'])) {
            $id_parameter = $this->generate->kirana_decrypt($_POST['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($_POST['action']);

            $this->dgeneral->update('tbl_outspec_parameter', $data_row, array(
                array(
                    'kolom' => 'id',
                    'value' => $id_parameter
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil diupdate";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan diupdate.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function set_layout($action = NULL)
    {
        if (isset($_POST['id_layout'])) {
            $id_layout = $this->generate->kirana_decrypt($_POST['id_layout']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($_POST['action']);

            $this->dgeneral->update('tbl_outspec_layout', $data_row, array(
                array(
                    'kolom' => 'id_layout',
                    'value' => $id_layout
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil diupdate";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan diupdate.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function set_pallet($action = NULL)
    {
        if (isset($_POST['id_pallet'])) {
            $id_pallet = $this->generate->kirana_decrypt($_POST['id_pallet']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($_POST['action']);

            $this->dgeneral->update('tbl_outspec_pallet', $data_row, array(
                array(
                    'kolom' => 'id_pallet',
                    'value' => $id_pallet
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil diupdate";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan diupdate.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function save_parameter()
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, FALSE);

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $id_parameter = empty($post['id']) ? NULL : $this->generate->kirana_decrypt($post['id']);

        $data_row = array(
            "nama" => $post['nama'],
            "satuan" => $post['satuan'],
            "urutan" => $post['urutan'],
        );

        if (!empty($post['id'])) {
            $param_cek = array(
                'connect' => FALSE,
                'cek_nama_parameter' => $post['nama'],
                'NOT_IN_id_parameter' => $id_parameter,
                'cek_duplikat' => true,
                'all' => "yes",
            );

            $check_exist = $this->dmaster->get_data_parameter($param_cek);
            if (count($check_exist) > 0) {
                $msg    = "Nama Parameter Duplicate. Periksa kembali data yang dimasukkan";
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            $data_row = $this->dgeneral->basic_column("update", $data_row);
            $this->dgeneral->update("tbl_outspec_parameter", $data_row, array(
                array(
                    'kolom' => 'id',
                    'value' => $id_parameter
                )
            ));
        } else {
            $param_cek = array(
                'connect' => FALSE,
                'LIKE_nama_parameter' => $post['nama'],
                'cek_duplikat' => true,
                'all' => "yes",
            );

            $check_exist = $this->dmaster->get_data_parameter($param_cek);
            if (count($check_exist) > 0) {
                $msg    = "Nama Parameter Duplicate. Periksa kembali data yang dimasukkan";
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            $data_row = $this->dgeneral->basic_column("insert", $data_row);
            $this->dgeneral->insert("tbl_outspec_parameter", $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg    = "Data berhasil ditambahkan";
            $sts    = "OK";
        }
        $this->general->closeDb();

        $return = array("sts" => $sts, "msg" => $msg);
        echo json_encode($return);
        exit();
    }

    private function save_layout()
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $id_layout = empty($post['id_layout']) ? NULL : $this->generate->kirana_decrypt($post['id_layout']);

        $data_row = array(
            "nama" => $post['nama'],
            "jumlah_bales" => $post['jumlah_bales'],
            "urutan" => $post['urutan'],
        );

        if (isset($_FILES['file']) && $_FILES['file']['error'][0] == 0 && $_FILES['file']['name'][0] !== "") {
            if (count($_FILES['file']['name']) > 1) {
                $msg    = "You can only upload maximum 1 file";
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            $newname = array(str_replace(' ', '_', $post['nama'] . '_' . time()));

            //UPLOADING
            $uploaddir = KIRANA_PATH_FILE . 'outspec/layout/';
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777, true);
            }

            $config['upload_path']   = $uploaddir;
            $config['allowed_types'] = 'png';
            $config['max_size'] = 100;

            $file = $this->general->upload_files($_FILES['file'], $newname, $config)[0];
            if ($file) {
                $data_file = array(
                    'files'         => KIRANA_PATH_FILE_FOLDER . 'outspec/layout/' . $file['filename'],
                    'size_files'    => $file['size'],
                    'tipe_files'    => pathinfo($file['full_path'], PATHINFO_EXTENSION),
                    // 'location'     => $file['url'],
                );

                $data_row = array_merge($data_row, $data_file);
            } else {
                $msg    = "Upload File Gagal";
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }
        }

        if (!empty($post['id_layout'])) {
            $data_row = $this->dgeneral->basic_column("update", $data_row);
            $this->dgeneral->update("tbl_outspec_layout", $data_row, array(
                array(
                    'kolom' => 'id_layout',
                    'value' => $id_layout
                )
            ));
        } else {
            $data_row = $this->dgeneral->basic_column("insert", $data_row);
            $this->dgeneral->insert("tbl_outspec_layout", $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg    = "Data berhasil ditambahkan";
            $sts    = "OK";
        }
        $this->general->closeDb();

        $return = array("sts" => $sts, "msg" => $msg);
        echo json_encode($return);
        exit();
    }

    private function save_pallet()
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $id_pallet = empty($post['id_pallet']) ? NULL : $this->generate->kirana_decrypt($post['id_pallet']);

        $data_row = array(
            "berat" => $post['berat'],
            "jumlah_layer" => $post['jumlah_layer'],
            "layer_pertama" => $post['layer_pertama'],
            "show_option" => (isset($post['is_show_option']) && $post['is_show_option'] == 'on' ? 1 : 0),
        );

        if (!empty($post['id_pallet'])) {
            $data_row = $this->dgeneral->basic_column("update", $data_row);
            $this->dgeneral->update("tbl_outspec_pallet", $data_row, array(
                array(
                    'kolom' => 'id_pallet',
                    'value' => $id_pallet
                )
            ));
        } else {
            $data_row = $this->dgeneral->basic_column("insert", $data_row);
            $this->dgeneral->insert("tbl_outspec_pallet", $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg    = "Data berhasil ditambahkan";
            $sts    = "OK";
        }
        $this->general->closeDb();

        $return = array("sts" => $sts, "msg" => $msg);
        echo json_encode($return);
        exit();
    }
}
