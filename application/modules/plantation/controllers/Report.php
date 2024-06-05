<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Plantation
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
include_once APPPATH . "modules/plantation/controllers/BaseControllers.php";

class Report extends BaseControllers
{
    private $access_plant;
    private $site_ktp = ['AAP1', 'AAP2', 'PKP1', 'PKP2', 'KGK1'];

    function __construct()
    {
        parent::__construct();
        $this->load->model('dreport');
        $this->load->model('dtransaksi');

        $this->access_plant = base64_decode($this->session->userdata("-gsber-"));
    }

    public function ppb()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['tanggal_awal'] = date('Y-m-d', strtotime('-1 months'));
        $data['tanggal_akhir'] = date('Y-m-d');

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }

        $this->load->view("report/ppb", $data);
    }

    public function bkb()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['tanggal_awal'] = date('Y-m-d', strtotime('-1 months'));
        $data['tanggal_akhir'] = date('Y-m-d');

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }

        $this->load->view("report/bkb", $data);
    }

    public function rekap()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['tanggal_awal'] = date('Y-m-d', strtotime('-1 months'));
        $data['tanggal_akhir'] = date('Y-m-d');

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }

        $this->load->view("report/rekap", $data);
    }

    public function datasap()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }

        $this->load->view("report/datasap", $data);
    }

    public function queuesap()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }

        $data['list'] = $this->dtransaksi->get_queue_data_to_sap(array(
            "IN_plant" => $data['pabrik']
        ));

        $this->load->view("report/queuesap", $data);
    }

    public function po()
    {
        //====must be initiate in every view function====/
        // $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['tanggal_awal'] = date('Y-m-d', strtotime('-1 months'));
        $data['tanggal_akhir'] = date('Y-m-d');

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }

        $this->load->view("report/po", $data);
    }

    //==================================================//
    /*                    Get data                      */
    //==================================================//
    public function get($param = NULL)
    {
        $data['user'] = $this->general->get_data_user();
        if ($data['user']->ho == 'y') {
            $in_plant = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $in_plant = array("PKP1", "PKP2");
            else
                $in_plant = array($data['user']->gsber);
        }

        switch ($param) {
            case 'ppb':
                // $in_plant = in_array($this->access_plant, $this->site_ktp) ? array($this->access_plant) : NULL;
                $id_ppb  = (isset($_POST['id_ppb'])) ? $this->generate->kirana_decrypt($_POST['id_ppb']) : NULL;
                $tanggal_awal  = (isset($_POST['tanggal_awal']) && $_POST['tanggal_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL;
                $tanggal_akhir  = (isset($_POST['tanggal_akhir']) && $_POST['tanggal_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL;

                $param_ppb = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "id_ppb" => $id_ppb,
                    "no_ppb" => $this->input->post("no_ppb", TRUE),
                    "tipe_po" => $this->input->post("tipe_po", TRUE),
                    "IN_year" => $this->input->post("tahun", TRUE),
                    "tanggal_awal" => $tanggal_awal,
                    "tanggal_akhir" => $tanggal_akhir,
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE)
                );

                $this->get_data_report_ppb($param_ppb);
                break;
            case 'gi':
                $id_gi  = (isset($_POST['id_gi'])) ? $this->generate->kirana_decrypt($_POST['id_gi']) : NULL;
                $tanggal_awal  = (isset($_POST['tanggal_awal']) && $_POST['tanggal_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL;
                $tanggal_akhir  = (isset($_POST['tanggal_akhir']) && $_POST['tanggal_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL;

                $param_gi = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "id_gi" => $id_gi,
                    "no_gi" => $this->input->post("no_gi", TRUE),
                    "IN_year" => $this->input->post("tahun", TRUE),
                    "tanggal_awal" => $tanggal_awal,
                    "tanggal_akhir" => $tanggal_akhir,
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE)
                );

                $this->get_data_report_gi($param_gi);
                break;
            case 'rekap':
                $id_gi  = (isset($_POST['id_gi'])) ? $this->generate->kirana_decrypt($_POST['id_gi']) : NULL;
                $tanggal_awal  = (isset($_POST['tanggal_awal']) && $_POST['tanggal_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL;
                $tanggal_akhir  = (isset($_POST['tanggal_akhir']) && $_POST['tanggal_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL;

                $param_gi = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "is_active" => $this->input->post("is_active", TRUE),
                    "tanggal_awal" => $tanggal_awal,
                    "tanggal_akhir" => $tanggal_akhir,
                    "IN_plant" => empty($this->input->post("IN_plant", TRUE)) ? $in_plant : $this->input->post("IN_plant", TRUE)
                );

                $this->get_data_report_rekap($param_gi);
                break;
            case 'datasap':
                $id_gi  = (isset($_POST['id_gi'])) ? $this->generate->kirana_decrypt($_POST['id_gi']) : NULL;
                $param_dt = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "no_transaksi" => $this->input->post("no_transaksi", TRUE),
                    "IN_year" => $this->input->post("tahun", TRUE),
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE),
                    "IN_jenis" => empty($this->input->post("jenis", TRUE)) ? null : $this->input->post("jenis", TRUE),
                    "IN_tipe_po" => empty($this->input->post("tipe_po", TRUE)) ? null : $this->input->post("tipe_po", TRUE)
                );

                $this->get_data_report_sap($param_dt);
                break;
            case 'po':
                $tanggal_awal  = (isset($_POST['tanggal_awal']) && $_POST['tanggal_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL;
                $tanggal_akhir  = (isset($_POST['tanggal_akhir']) && $_POST['tanggal_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL;

                $param_dt = array(
                    "connect" => TRUE,
                    "return" => $this->input->post("return", TRUE),
                    "tanggal_awal" => $tanggal_awal,
                    "tanggal_akhir" => $tanggal_akhir,
                    "tipe_po" => $this->general->emptyconvert($this->input->post("tipe_po", TRUE)),
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE),
                );

                $this->get_data_report_po($param_dt);
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
    private function get_data_report_ppb($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dreport->get_data_report_ppb($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                        //
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {
                                    //

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
                break;
            case 'complete':
                $result = $this->dreport->get_data_report_ppb($param);
                unset($param['encrypt']);
                if ($result) {
                    // $result->detail = $this->dreport->get_ppb_detail($param);
                }
                break;

            default:
                $result = $this->dreport->get_data_report_ppb($param);
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

    private function get_data_report_gi($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dreport->get_data_report_gi($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                        //
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {
                                    //

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
                break;
            case 'complete':
                $result = $this->dreport->get_data_report_gi($param);
                unset($param['encrypt']);
                break;

            default:
                $result = $this->dreport->get_data_report_gi($param);
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

    private function get_data_report_rekap($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dreport->get_data_report_rekap($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                        //
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {
                                    //

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
                break;
            case 'complete':
                $result = $this->dreport->get_data_report_rekap($param);
                unset($param['encrypt']);
                break;

            default:
                $result = $this->dreport->get_data_report_rekap($param);
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

    private function get_data_report_sap($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dreport->get_data_report_sap($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                        //
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {
                                    //

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
                break;
            case 'complete':
                $result = $this->dreport->get_data_report_sap($param);
                unset($param['encrypt']);
                break;

            default:
                $result = $this->dreport->get_data_report_gi($param);
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

    private function get_data_report_po($param = NULL)
    {
        $result = $this->dreport->get_data_report_po($param);

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
    }
}
