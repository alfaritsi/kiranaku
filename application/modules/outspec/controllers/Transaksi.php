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

class Transaksi extends BaseControllers
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dtransaksi');
    }

    public function permukaan()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['pabrik'] = array();

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->dgeneral->get_master_plant();
        } else {
            $data['pabrik'] = array($data['user']->gsber);
        }

        $data['tanggal_awal'] = date('Y-m-d', strtotime('-1 months'));
        $data['tanggal_akhir'] = date('Y-m-d');

        $this->load->view("transaksi/permukaan/page", $data);
    }

    public function random()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['pabrik'] = array();

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->dgeneral->get_master_plant();
        } else {
            $data['pabrik'] = array($data['user']->gsber);
        }

        $data['tanggal_awal'] = date('Y-m-d', strtotime('-1 months'));
        $data['tanggal_akhir'] = date('Y-m-d');

        $this->load->view("transaksi/random/page", $data);
    }

    public function detail($param = NULL, $key = NULL)
    {
        //====must be initiate in every view function====/
        $this->general->check_session();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        switch ($param) {
            case 'permukaan':
                $id_cek  = $this->generate->kirana_decrypt($key);
                $data['data_cek'] = $this->get_data_cek(array(
                    "connect" => TRUE,
                    "data" => "header",
                    "id_cek" => $id_cek,
                    "tipe" => "permukaan",
                    "encrypt" => array("id")
                ));

                if (
                    !$key || empty($data['data_cek'])
                    //|| in_array($this->data['project_header']->plant, $this->access_plant) === false
                )
                    show_404();

                $this->load->view("transaksi/permukaan/detail", $data);
                break;
            case 'random':
                $id_cek  = $this->generate->kirana_decrypt($key);
                $data['data_cek'] = $this->get_data_cek(array(
                    "connect" => TRUE,
                    "data" => "header",
                    "id_cek" => $id_cek,
                    "tipe" => "random",
                    "encrypt" => array("id")
                ));

                if (
                    !$key || empty($data['data_cek'])
                )
                    show_404();

                $this->load->view("transaksi/random/detail", $data);
                break;
            default:
                show_404();
                break;
        }
    }

    //==================================================//
    /*                    Get data                      */
    //==================================================//
    public function get($param = NULL)
    {
        switch ($param) {
            case 'data':
                $in_plant = (base64_decode($this->session->userdata('-ho-')) == 'y' ? NULL : base64_decode($this->session->userdata('-gsber-')));
                $param_ = array(
                    "connect" => TRUE,
                    "return" => $this->input->post("return", TRUE),
                    "data" => $this->input->post("data", TRUE),
                    "tipe" => $this->input->post("tipe", TRUE),
                    "id_cek" => (isset($_POST['id_cek'])) ? $this->generate->kirana_decrypt($_POST['id_cek']) : NULL,
                    "tanggal_awal" => (isset($_POST['tanggal_awal']) && $_POST['tanggal_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL,
                    "tanggal_akhir" => (isset($_POST['tanggal_akhir']) && $_POST['tanggal_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL,
                    "plant" => $this->input->post("plant", TRUE),
                    "IN_plant" => empty($_POST["IN_plant"]) ? $in_plant : $_POST["IN_plant"],
                    "tahun_produksi" => $this->input->post("tahun_produksi", TRUE),
                    "encrypt" => array("id")
                );

                $this->get_data_cek($param_);
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
    private function get_data_cek($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dtransaksi->get_data_cek($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                        //
                    } else {
                        if (isset($param['return']) && $param['return'] == "datatables")
                            $result = $this->general->jsonify($result);
                    }
                }
                break;
            case 'complete':
                $result = $this->dtransaksi->get_data_cek($param);
                unset($param['encrypt']);
                if ($result) {
                    $result->data_bales = $this->dtransaksi->get_data_bales($param);
                    $result->data_label = $this->dtransaksi->get_data_label($param);
                    $result->data_layer = $this->dtransaksi->get_data_layer($param);

                    //layout image
                    if (!empty($result->laygrp)) {
                        $data_layout = $this->dtransaksi->get_data_layout_kms(array(
                            "connect" => TRUE,
                            "laygrp" => $result->laygrp,
                            "single_row" => TRUE
                        ));
                        if ($data_layout && !empty($data_layout->laypath)) {
                            $filename = basename(str_replace("\\", "/", $data_layout->laypath));
                            $result->layout_image = 'file/kms_layout/GbrLay/' . $filename;
                        }
                    }
                }
                break;
            default:
                $result = $this->dtransaksi->get_data_cek($param);
                break;
        }

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
    }
}
