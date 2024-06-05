<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

class Setting extends BaseControllers
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dsetting');
    }

    public function user()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $this->load->view("setting/user", $data);
    }

    //==================================================//
    /*                    Get data                      */
    //==================================================//
    public function get($param = NULL)
    {
        switch ($param) {
            case 'user':
                $param_ = array(
                    "connect" => TRUE,
                    "return" => $this->input->post("return", TRUE),
                    "id_user" => (isset($_POST['id'])) ? $this->generate->kirana_decrypt($_POST['id']) : NULL,
                    "all" => $this->input->post("all", TRUE),
                    "encrypt" => array("id_user")
                );

                $this->get_data_user($param_);
                break;
            case 'karyawan':
                $this->get_data_karyawan();
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
            case 'user':
                $return = $this->set_user();
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
            case 'user':
                $this->save_user();
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
    private function get_data_user($param = NULL)
    {
        $result = $this->dsetting->get_data_user($param);

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
    }

    private function get_data_karyawan()
    {
        $param = $this->input->post_get(NULL, TRUE);
        if (isset($param['autocomplete']) && $param['autocomplete'] == TRUE) {
            $user = $this->dsetting->get_data_karyawan(
                array(
                    "connect" => TRUE,
                    "search" => $param['search'],
                    "encrypt" => array("id_karyawan"),
                    "exclude" => array("id_karyawan", "login_buat", "login_edit"),
                )
            );
            $data_user  = array(
                "total_count" => count($user),
                "incomplete_results" => false,
                "items" => $user
            );
            echo json_encode($data_user);
            exit();
        }
    }

    private function set_user($action = NULL)
    {
        if (isset($_POST['id_user'])) {
            $id_user = $this->generate->kirana_decrypt($_POST['id_user']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column($_POST['action']);

            $this->dgeneral->update('tbl_outspec_user', $data_row, array(
                array(
                    'kolom' => 'id_user',
                    'value' => $id_user
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

    private function save_user()
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $id_user = empty($post['id_user']) ? NULL : $this->generate->kirana_decrypt($post['id_user']);

        $data_row = array(
            "nik" => $post['nik'],
            "plant" => $post['plant'],
        );

        if (!empty($post['id_user'])) {
            $data_row = $this->dgeneral->basic_column("update", $data_row);
            $this->dgeneral->update("tbl_outspec_user", $data_row, array(
                array(
                    'kolom' => 'id',
                    'value' => $id_user
                )
            ));
        } else {
            $data_row = $this->dgeneral->basic_column("insert", $data_row);
            $this->dgeneral->insert("tbl_outspec_user", $data_row);
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