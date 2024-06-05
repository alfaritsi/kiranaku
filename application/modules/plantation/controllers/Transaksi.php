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

class Transaksi extends BaseControllers
{
    private $access_plant;
    private $site_ktp = ['AAP1', 'AAP2', 'PKP1', 'PKP2', 'KGK1'];

    function __construct()
    {
        parent::__construct();
        $this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));

        $this->load->model('dtransaksi');
        $this->load->model('dmaster');

        $this->access_plant = base64_decode($this->session->userdata("-gsber-"));
    }

    public function index()
    {
        show_404();
    }

    public function data($param = "ppb")
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['pabrik'] = array();

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }

        $data['tanggal_awal'] = date('Y-m-d', strtotime('-1 months'));
        $data['tanggal_akhir'] = date('Y-m-d');

        $data['akses_kirim_sap'] = 0;
        //=====Enable Kirim SAP=====//
        if (
            in_array($this->access_plant, ['KTP1'])
            || in_array(base64_decode($this->session->userdata("-id_divisi-")), ['754'])
        )
            $data['akses_kirim_sap'] = 1;

        switch ($param) {
            case 'ppb':
                $this->data['param'] = $param;
                $this->load->view("transaksi/ppb/data_ppb", $data);
                break;
            case 'po':
                $this->data['param'] = $param;
                $this->load->view("transaksi/po/data_po", $data);
                break;
            case 'gr':
                $this->data['param'] = $param;
                $this->load->view("transaksi/gr/data_gr", $data);
                break;
            case 'gi':
                $this->data['param'] = $param;
                $this->load->view("transaksi/gi/data_gi", $data);
                break;
            default:
                show_404();
                break;
        }
    }

    public function unggah()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $this->load->view("transaksi/unggah", $data);
    }

    public function detail($param = NULL, $key = NULL)
    {
        //====must be initiate in every view function====/
        $this->general->check_session();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['akses_cetak'] = 1;
        $data['akses_upload_attachment'] = 0;

        //=====Enable Cetak=====//
        if (in_array($this->access_plant, ['KTP1']))
            $data['akses_cetak'] = 1;

        switch ($param) {
            case 'ppb':
                $data['title']      = "Data PPB";
                $data['title_form'] = "Detail PPB";

                $id_ppb  = $this->generate->kirana_decrypt($key);
                $data['data_ppb'] = $this->get_data_ppb(array(
                    "connect" => TRUE,
                    "data" => "header",
                    "id_ppb" => $id_ppb,
                    "encrypt" => array("id")
                ));

                if (
                    !$key || empty($data['data_ppb'])
                    //|| in_array($this->data['project_header']->plant, $this->access_plant) === false
                )
                    show_404();

                //=====Enable Upload File=====//
                // if (base64_decode($this->session->userdata("-id_user-")) == $data['data_ppb']->login_buat)
                if (
                    // $this->access_plant == $data['data_ppb']->plant
                    $this->access_plant !== "PKP" && $this->access_plant == $data['data_ppb']->plant
                    || ($this->access_plant === "PKP" && in_array($data['data_ppb']->plant, array("PKP1", "PKP2")))
                )
                    $data['akses_upload_attachment'] = 1;

                $this->load->view("transaksi/ppb/detail", $data);
                break;
            case 'po':
                $data['title']         = "Data PO";
                $data['title_form']  = "Detail PO";

                $id_po  = $this->generate->kirana_decrypt($key);
                $data['data_po'] = $this->get_data_po(array(
                    "connect" => TRUE,
                    "data" => "header",
                    "id_po" => $id_po,
                    "encrypt" => array("id", "id_ppb")
                ));

                if (
                    !$key || empty($data['data_po'])
                    //|| in_array($this->data['project_header']->plant, $this->access_plant) === false
                )
                    show_404();

                //=====Enable Upload File=====//
                if (in_array(base64_decode($this->session->userdata("-id_divisi-")), ['754']) && $data['data_po']->tipe_po == 'HO')
                    $data['akses_upload_attachment'] = 1;

                $this->load->view("transaksi/po/detail", $data);
                break;
            case 'gr':
                $data['title']         = "Data TTG";
                $data['title_form']  = "Detail TTG";

                $id_gr  = $this->generate->kirana_decrypt($key);
                $data['data_gr'] = $this->get_data_gr(array(
                    "connect" => TRUE,
                    "data" => "header",
                    "id_gr" => $id_gr,
                    "encrypt" => array("id", "id_po")
                ));

                if (
                    !$key || empty($data['data_gr'])
                    //|| in_array($this->data['project_header']->plant, $this->access_plant) === false
                )
                    show_404();

                //=====Enable Upload File=====//
                // if (base64_decode($this->session->userdata("-id_user-")) == $data['data_gr']->login_buat)
                if (
                    // $this->access_plant == $data['data_gr']->plant
                    $this->access_plant !== "PKP" && $this->access_plant == $data['data_gr']->plant
                    || ($this->access_plant === "PKP" && in_array($data['data_gr']->plant, array("PKP1", "PKP2")))
                )
                    $data['akses_upload_attachment'] = 1;

                $this->load->view("transaksi/gr/detail", $data);
                break;
            case 'gi':
                $data['title']         = "Data BKB";
                $data['title_form']  = "Detail BKB";

                $id_gi  = $this->generate->kirana_decrypt($key);
                $data['data_gi'] = $this->get_data_gi(array(
                    "connect" => TRUE,
                    "data" => "header",
                    "id_gi" => $id_gi,
                    "encrypt" => array("id")
                ));

                if (
                    !$key || empty($data['data_gi'])
                    //|| in_array($this->data['project_header']->plant, $this->access_plant) === false
                )
                    show_404();

                $this->load->view("transaksi/gi/detail", $data);
                break;
            default:
                show_404();
                break;
        }
    }

    public function konfirmppb($key = NULL)
    {
        //====must be initiate in every view function====/
        $this->general->check_session();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['title']         = "Data PPB";
        $data['title_form']  = "Data PPB";

        if (!$key) show_404();

        $id_ppb  = $this->generate->kirana_decrypt($key);
        $data['data_ppb'] = $this->get_data_ppb(array(
            "connect" => TRUE,
            "data" => "complete",
            "id_ppb" => $id_ppb,
            "encrypt" => array("id")
        ));

        if (
            empty($data['data_ppb'])
            || !in_array($this->access_plant, ['KTP1'])
        )
            show_404();

        $this->load->view("transaksi/ppb/konfirmppb", $data);
    }

    public function createpoho($key = NULL)
    {
        //====must be initiate in every view function====/
        $this->general->check_session();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['title']      = "Data PPB";
        $data['title_form'] = "Data PPB";
        if (!$key) show_404();

        $id_ppb  = $this->generate->kirana_decrypt($key);
        $data['data_ppb'] = $this->get_data_ppb(array(
            "connect" => TRUE,
            "data" => "header",
            "id_ppb" => $id_ppb,
            // "is_closed" => false,
            "encrypt" => array("id")
        ));

        if (
            empty($data['data_ppb'])
            || !in_array(base64_decode($this->session->userdata("-id_divisi-")), ['754'])
        )
            show_404();

        $this->load->view("transaksi/po/po_ho", $data);
    }

    public function download($param = NULL)
    {
        $tipe = $_POST['tipe'];
        switch ($tipe) {
            case 'ppb':
                $file_name = "TEMPLATE-KTP-PPB";
                break;
            case 'po_site':
                $file_name = "TEMPLATE-KTP-PO";
                break;
            case 'gr_ho':
                $file_name = "TEMPLATE-KTP-GR";
                break;
            case 'gi':
                $file_name = "TEMPLATE-KTP-GI";
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }

        $file = "assets/file/plantation/" . $file_name . ".xlsx";
        $this->general->download($file);
    }

    public function cetak($param = NULL, $key = NULL)
    {
        //====must be initiate in every view function====/
        // $this->general->check_access();
        $data['generate']   = $this->generate;
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        switch ($param) {
            case 'ppb':
                $this->cetak_ppb($key);

                // $data['title']    	 = "Data PPB";
                // $data['title_form']  = "Detail PPB";

                // $id_ppb  = $this->generate->kirana_decrypt($key);
                // $data['data_ppb'] = $this->get_data_ppb(array(
                //     "connect" => TRUE,
                //     "data" => "complete",
                //     "id_ppb" => $id_ppb,
                //     "encrypt" => array("id")
                // ));

                // if (!$key || empty($data['data_ppb']) 
                //     //|| in_array($this->data['project_header']->plant, $this->access_plant) === false
                // )
                //     show_404();

                // $this->load->view("cetak/ppb", $data);
                break;
            case 'gr_ho':
                $this->cetak_gr_ho($key);
                break;
            case 'gr_site':
                $this->cetak_gr_site($key);
                break;
            case 'gi':
                $this->cetak_gi($key);
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
                // $in_plant = is_array($this->session->userdata("-plant_code-")) ? $this->session->userdata("-plant_code-") : $this->session->userdata("-plant_code-");
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
                    "IN_status_konfirmasi" => $this->input->post("IN_status_konfirmasi", TRUE),
                    "IN_status_po_ho" => $this->input->post("IN_status_po_ho", TRUE),
                    "IN_status_po_site" => $this->input->post("IN_status_po_site", TRUE),
                    "IN_status_ppb" => $this->input->post("IN_status_ppb", TRUE),
                    "tanggal_awal" => $tanggal_awal,
                    "tanggal_akhir" => $tanggal_akhir,
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE),
                    "encrypt" => array("id")
                );

                $this->get_data_ppb($param_ppb);
                break;
            case 'po':
                $id_po  = (isset($_POST['id_po'])) ? $this->generate->kirana_decrypt($_POST['id_po']) : NULL;
                $id_ppb  = (isset($_POST['id_ppb'])) ? $this->generate->kirana_decrypt($_POST['id_ppb']) : NULL;
                $tanggal_awal  = (isset($_POST['tanggal_awal']) && $_POST['tanggal_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL;
                $tanggal_akhir  = (isset($_POST['tanggal_akhir']) && $_POST['tanggal_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL;

                $param_po = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "id_po" => $id_po,
                    "id_ppb" => $id_ppb,
                    "no_ppb" => $this->input->post("no_ppb", TRUE),
                    "tipe_po" => $this->input->post("tipe_po", TRUE),
                    "status_sap" => $this->input->post("status_sap", TRUE),
                    "IN_status_sap" => $this->input->post("IN_status_sap", TRUE),
                    "tanggal_awal" => $tanggal_awal,
                    "tanggal_akhir" => $tanggal_akhir,
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE),
                    "encrypt" => array("id", "id_ppb")
                );

                $this->get_data_po($param_po);
                break;
            case 'gr':
                $id_gr  = (isset($_POST['id_gr'])) ? $this->generate->kirana_decrypt($_POST['id_gr']) : NULL;
                $id_po  = (isset($_POST['id_po'])) ? $this->generate->kirana_decrypt($_POST['id_po']) : NULL;
                $tanggal_awal  = (isset($_POST['tanggal_awal']) && $_POST['tanggal_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL;
                $tanggal_akhir  = (isset($_POST['tanggal_akhir']) && $_POST['tanggal_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL;

                $param_gr = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "id_po" => $id_po,
                    "id_gr" => $id_gr,
                    "no_ppb" => $this->input->post("no_ppb", TRUE),
                    "tipe_po" => $this->input->post("tipe_po", TRUE),
                    "IN_status_sap" => $this->input->post("IN_status_sap", TRUE),
                    "status_sap" => $this->input->post("status_sap", TRUE),
                    "tanggal_awal" => $tanggal_awal,
                    "tanggal_akhir" => $tanggal_akhir,
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE),
                    "encrypt" => array("id", "id_po")
                );

                $this->get_data_gr($param_gr);
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
                    "IN_status_sap" => $this->input->post("IN_status_sap", TRUE),
                    "status_sap" => $this->input->post("status_sap", TRUE),
                    "tanggal_awal" => $tanggal_awal,
                    "tanggal_akhir" => $tanggal_akhir,
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE),
                    "encrypt" => array("id")
                );

                $this->get_data_gi($param_gi);
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
    public function set($param = NULL, $param2 = NULL)
    {
        switch ($param) {
            case 'delete':
                switch ($param2) {
                    case 'ppb':
                        $this->delete_ppb(array(
                            "connect" => TRUE
                        ));
                        break;
                    case 'po':
                        $this->delete_po(array(
                            "connect" => TRUE
                        ));
                        break;
                    case 'gr':
                        $this->delete_gr(array(
                            "connect" => TRUE
                        ));
                        break;
                    case 'gi':
                        $this->delete_gi(array(
                            "connect" => TRUE
                        ));
                        break;
                    default:
                        $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                        echo json_encode($return);
                        break;
                }
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
            case 'upload':
                $tipe = $_POST['tipe'];
                switch ($tipe) {
                    case 'ppb':
                        $this->save_upload_ppb();
                        break;
                    case 'po_site':
                        $this->save_upload_po_site();
                        break;
                    case 'gr_ho':
                        $this->save_upload_gr_ho();
                        break;
                    case 'gi':
                        $this->save_upload_gi();
                        break;
                    default:
                        $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                        echo json_encode($return);
                        break;
                }
                break;
            case 'ppb':
                $this->save_ppb($param);
                break;
            case 'konfirmppb':
                $this->save_konfirm_ppb($param);
                break;
            case 'po_ho':
                $this->save_po_ho($param);
                break;
            case 'attachment_transaksi':
                $this->save_attachment_transaksi($param);
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
    private function get_data_ppb($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dtransaksi->get_ppb_header($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {

                        //=====Enable Konfirmasi PPB=====//
                        if (in_array($this->access_plant, ['KTP1']))
                            $result->akses_konfirmasi_ppb = 1;

                        //=====Enable Create PO HO=====//
                        if (in_array(base64_decode($this->session->userdata("-id_divisi-")), ['754']))
                            $result->akses_create_po = 1;

                        //=====Enable Delete PPB=====//
                        if (
                            // in_array($this->access_plant, $this->site_ktp)
                            $this->access_plant !== "PKP" && $this->access_plant == $result->plant
                            || ($this->access_plant === "PKP" && in_array($result->plant, array("PKP1", "PKP2")))
                        )
                            $result->akses_delete = 1;
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {
                                    //=====Enable Konfirmasi PPB=====//
                                    if (in_array($this->access_plant, ['KTP1']))
                                        $val['akses_konfirmasi_ppb'] = 1;

                                    //=====Enable Create PO HO=====//
                                    if (in_array(base64_decode($this->session->userdata("-id_divisi-")), ['754']))
                                        $val['akses_create_po'] = 1;

                                    //=====Enable Delete PPB=====//
                                    if (
                                        // in_array($this->access_plant, $this->site_ktp)
                                        $this->access_plant !== "PKP" && $this->access_plant == $val['plant']
                                        || ($this->access_plant === "PKP" && in_array($val['plant'], array("PKP1", "PKP2")))
                                    )
                                        $val['akses_delete'] = 1;

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
                $result = $this->dtransaksi->get_ppb_header($param);
                unset($param['encrypt']);
                if ($result) {
                    $result->detail = $this->dtransaksi->get_ppb_detail($param);
                }
                break;

            default:
                $result = $this->dtransaksi->get_ppb_header($param);
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

    private function get_data_po($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dtransaksi->get_po_header($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                        //=====Enable Delete PO=====//
                        if (
                            in_array(base64_decode($this->session->userdata("-id_divisi-")), ['754'])
                            && $result->tipe_po == "HO"
                            && (!$result->done_kirim_sap || $result->status_sap != "success")
                        )
                            $result->akses_delete = 1;
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {
                                    //=====Enable Delete PO=====//
                                    if (
                                        in_array(base64_decode($this->session->userdata("-id_divisi-")), ['754'])
                                        && $val['tipe_po'] == "HO"
                                        && (!$val['done_kirim_sap'] || $val['status_sap'] != "success")
                                    )
                                        $val['akses_delete'] = 1;

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
                $result = $this->dtransaksi->get_po_header($param);
                unset($param['encrypt']);
                if ($result) {
                    $result->detail = $this->dtransaksi->get_po_detail($param);
                }
                break;

            default:
                $result = $this->dtransaksi->get_po_header($param);
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

    private function get_data_gr($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dtransaksi->get_gr_header($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                        //=====Enable Delete GR/TTG=====//
                        if (
                            // in_array($this->access_plant, $this->site_ktp)
                            // && $this->access_plant == $result->plant
                            ($this->access_plant !== "PKP" && $this->access_plant == $result->plant
                                || ($this->access_plant === "PKP" && in_array($result->plant, array("PKP1", "PKP2"))))
                            && (!$result->done_kirim_sap || $result->status_sap != "success")
                        )
                            $result->akses_delete = 1;
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {
                                    //=====Enable Delete GR/TTG=====//
                                    if (
                                        // in_array($this->access_plant, $this->site_ktp)
                                        // && $this->access_plant == $val['plant']
                                        ($this->access_plant !== "PKP" && $this->access_plant == $val['plant']
                                            || ($this->access_plant === "PKP" && in_array($val['plant'], array("PKP1", "PKP2"))))
                                        && (!$val['done_kirim_sap'] || $val['status_sap'] != "success")
                                    )
                                        $val['akses_delete'] = 1;

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
                $result = $this->dtransaksi->get_gr_header($param);
                unset($param['encrypt']);
                if ($result) {
                    $result->detail = $this->dtransaksi->get_gr_detail_sum($param);
                }
                break;

            default:
                $result = $this->dtransaksi->get_gr_header($param);
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

    private function get_data_gi($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dtransaksi->get_gi_header($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                        //=====Enable Delete GI/BKB=====//
                        if (
                            // in_array($this->access_plant, $this->site_ktp)
                            // && $this->access_plant == $result->plant
                            ($this->access_plant !== "PKP" && $this->access_plant == $result->plant
                                || ($this->access_plant === "PKP" && in_array($result->plant, array("PKP1", "PKP2"))))
                            && (!$result->done_kirim_sap || $result->status_sap != "success")
                        )
                            $result->akses_delete = 1;
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {
                                    //=====Enable Delete GI/BKB=====//
                                    if (
                                        // in_array($this->access_plant, $this->site_ktp)
                                        // && $this->access_plant == $val['plant']
                                        ($this->access_plant !== "PKP" && $this->access_plant == $val['plant']
                                            || ($this->access_plant === "PKP" && in_array($val['plant'], array("PKP1", "PKP2"))))
                                        && (!$val['done_kirim_sap'] || $val['status_sap'] != "success")
                                    )
                                        $val['akses_delete'] = 1;
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
                $result = $this->dtransaksi->get_gi_header($param);
                unset($param['encrypt']);
                if ($result) {
                    $result->detail = $this->dtransaksi->get_gi_detail($param);
                }
                break;

            default:
                $result = $this->dtransaksi->get_gi_header($param);
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

    private function save_upload_ppb($param = NULL)
    {
        $post = $this->input->post(NULL, TRUE);
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
        if (!empty($_FILES['file_excel']['name']) && !empty($_FILES['file_excel']['name']) != "") {
            $target_dir = "./assets/temp";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $config['upload_path']          = $target_dir;
            $config['allowed_types']        = 'xls|xlsx';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file_excel')) {
                $msg = $this->upload->display_errors();
                $sts = "NotOK";
            } else {
                $data = array('upload_data' => $this->upload->data());
                $objPHPExcel = PHPExcel_IOFactory::load($data['upload_data']['full_path']);
                $title_desc = $objPHPExcel->getProperties()->getTitle();
                $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
                $sheet_name     = $objWorksheet->getTitle();
                $data_excel = $objPHPExcel->getActiveSheet();
                $highestRow = $data_excel->getHighestRow();
                $highestColumn = PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                $data_row = array();

                if ($sheet_name != "PPB") {
                    $return = array('sts' => 'NotOk', 'msg' => "File yang diupload tidak sesuai.");

                    unlink($data['upload_data']['full_path']);
                    echo json_encode($return);
                    exit();
                }

                $this->dgeneral->begin_transaction();

                $current_ppb = "";
                $id_ppb = "";
                $i = 1;
                $list_barang = array();

                for ($brs = 5; $brs <= $highestRow; $brs++) {
                    $no_ppb            = $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                    $plant            = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                    $tanggal        = $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                    $kode_barang    = $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                    $jumlah         = $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                    $harga          = $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();
                    $tanggal_perlu  = $data_excel->getCellByColumnAndRow(6, $brs)->getFormattedValue();
                    $keterangan     = $data_excel->getCellByColumnAndRow(7, $brs)->getFormattedValue();
                    $perihal        = $data_excel->getCellByColumnAndRow(8, $brs)->getFormattedValue();

                    if ((!$kode_barang || $kode_barang == "") && (!$no_ppb || $no_ppb == "")) {
                        break;
                    }

                    if (
                        !$no_ppb || $no_ppb == ""
                        || !$plant || $plant == ""
                        || !$tanggal || $tanggal == ""
                        || !$kode_barang || $kode_barang == ""
                        || !$jumlah || $jumlah == "" || !is_numeric($jumlah)
                        || !$harga || $harga == ""
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data untuk barang $kode_barang pada No PPB $no_ppb tidak lengkap/valid.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    //======cek akses plant======//
                    if (
                        ($this->access_plant !== "PKP" && !in_array($this->access_plant, [$plant]))
                        || ($this->access_plant === "PKP" && !in_array($plant, array("PKP1", "PKP2")))
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Tidak memiliki akses untuk plant $plant");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $validasi_tanggal = $this->validasi_tanggal($tanggal);
                    if (!$validasi_tanggal) {
                        $msg = "Tanggal Tidak Valid pada Barang $kode_barang pada No PPB $no_ppb. Gunakan format 'dd.mm.yyyy'";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $tanggal = $this->generate->regenerateDateFormat($tanggal);
                    $tanggal_perlu = $this->generate->regenerateDateFormat($tanggal_perlu);

                    // cek kode barang
                    $barang = $this->dmaster->get_data_material_by_plant(array(
                        "connect" => false,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant,
                        "is_active" => 1
                    ));

                    if (!$barang || ($barang && $barang->classification == "")) {
                        $return = array('sts' => 'NotOk', 'msg' => "Barang dengan kode $kode_barang belum terdaftar untuk pabrik ini.");

                        if ($barang && $barang->classification == "") {
                            $return = array('sts' => 'NotOk', 'msg' => "Barang dengan kode $kode_barang belum dilakukan konfigurasi. Harap menghubungi Admin HO.");
                        }

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    //======Data Header======//
                    if ($no_ppb != $current_ppb) {
                        // cek nomor ppb
                        $ck_data_header = $this->dtransaksi->get_ppb_header(array(
                            "connect" => false,
                            "no_ppb" => $no_ppb,
                            "plant" => $plant
                        ));

                        if ($ck_data_header) {
                            $msg = "No PPB $no_ppb sudah digunakan";
                            $sts = "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            unlink($data['upload_data']['full_path']);

                            echo json_encode($return);
                            exit();
                        } else {
                            $data_header = array(
                                'no_ppb'                => $no_ppb,
                                'plant'                    => $plant,
                                'tanggal'                => $tanggal,
                                'tanggal_diperlukan'    => $tanggal_perlu,
                                "perihal"               => $perihal,
                            );

                            $data_header = $this->dgeneral->basic_column("insert", $data_header);
                            $this->dgeneral->insert("tbl_ktp_ppb_header", $data_header);
                            $id_ppb = $this->db->insert_id();

                            $data_log = array(
                                "id_transaksi"  => $id_ppb,
                                "tipe"          => "ppb",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
                        }
                        $i = 1;
                        $list_barang = [];
                        $current_ppb = $no_ppb;
                    }

                    //======Data Detail======//
                    //cek double kode barang
                    // $cek_double = $this->dtransaksi->get_ppb_detail(array(
                    //     "connect" => false,
                    //     "kode_barang" => $kode_barang,
                    //     "plant" => $plant,
                    //     "id_ppb" => $id_ppb,
                    // ));

                    // if ($cek_double) {
                    if (in_array($kode_barang, $list_barang)) {
                        $msg = "Barang $kode_barang pada No PPB $no_ppb double.";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $data_detail = array(
                        'id_ppb'                => $id_ppb,
                        'no_ppb'                => $no_ppb,
                        'no_detail'                => $i,
                        'kode_barang'            => $kode_barang,
                        'satuan'                => $barang->MEINS,
                        'jumlah'                => $jumlah,
                        'harga'                    => $harga,
                        'keterangan'            => $keterangan,
                        'stok'                  => $barang->LABST,
                        'classification'        => $barang->classification
                    );
                    $data_detail = $this->dgeneral->basic_column("insert", $data_detail);
                    $this->dgeneral->insert("tbl_ktp_ppb_detail", $data_detail);
                    $i++;
                    $list_barang[] = $kode_barang;
                }
                unlink($data['upload_data']['full_path']);

                if ($this->db->trans_status() === false) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang diunggah";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil diunggah";
                    $sts = "OK";
                }
            }
        } else {
            $msg = "Silahkan pilih file yang ingin diunggah";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);
        exit();
    }

    private function save_upload_po_site($param = NULL)
    {
        $post = $this->input->post(NULL, TRUE);
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
        if (!empty($_FILES['file_excel']['name']) && !empty($_FILES['file_excel']['name']) != "") {
            $target_dir = "./assets/temp";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $config['upload_path']          = $target_dir;
            $config['allowed_types']        = 'xls|xlsx';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file_excel')) {
                $msg = $this->upload->display_errors();
                $sts = "NotOK";
            } else {
                $data = array('upload_data' => $this->upload->data());
                $objPHPExcel = PHPExcel_IOFactory::load($data['upload_data']['full_path']);
                // $title_desc		= $objPHPExcel->getProperties()->getTitle();
                $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
                $sheet_name     = $objWorksheet->getTitle();
                $data_excel = $objPHPExcel->getActiveSheet();
                $highestRow = $data_excel->getHighestRow();
                $highestColumn = PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                $data_row = array();

                if ($sheet_name != "PO") {
                    $return = array('sts' => 'NotOk', 'msg' => "File yang diupload tidak sesuai.");

                    unlink($data['upload_data']['full_path']);
                    echo json_encode($return);
                    exit();
                }

                $this->dgeneral->begin_transaction();

                $current_ttg = "";
                $jenis_po = "";
                $current_ppb = "";
                $current_vendor = "";
                $id_po = "";
                $id_gr = "";
                $no_detail_po = 1;
                $no_detail_gr = 1;
                $total_diskon = 0;
                $ppn = "";
                $nilai_ppn = 0;
                $list_barang = array();

                for ($brs = 5; $brs <= $highestRow; $brs++) {
                    $no_ttg            = $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                    $plant            = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                    $tanggal        = $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                    $kode_barang    = $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                    $vendor            = $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                    $cost_center    = $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();
                    $jumlah         = $data_excel->getCellByColumnAndRow(6, $brs)->getFormattedValue();
                    $harga          = $data_excel->getCellByColumnAndRow(7, $brs)->getFormattedValue();
                    $diskon         = $data_excel->getCellByColumnAndRow(8, $brs)->getFormattedValue();
                    $ppn_item       = $data_excel->getCellByColumnAndRow(9, $brs)->getFormattedValue();
                    $sloc           = $data_excel->getCellByColumnAndRow(10, $brs)->getFormattedValue();
                    $no_ppb            = $data_excel->getCellByColumnAndRow(11, $brs)->getFormattedValue();
                    $keterangan     = $data_excel->getCellByColumnAndRow(12, $brs)->getFormattedValue();

                    if ((!$no_ttg || $no_ttg == "") && (!$kode_barang || $kode_barang == "")) {
                        break;
                    }

                    if (
                        !$no_ppb || $no_ppb == ""
                        || !$no_ttg || $no_ttg == ""
                        || !$plant || $plant == ""
                        || !$tanggal || $tanggal == ""
                        || !$kode_barang || $kode_barang == ""
                        || !$vendor || $vendor == ""
                        // || !$cost_center || $cost_center == ""
                        || !$jumlah || $jumlah == "" || !is_numeric($jumlah)
                        || !$harga || $harga == "" || !is_numeric($harga)
                        || !$sloc || $sloc == ""
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data untuk barang $kode_barang pada No TTG $no_ttg tidak lengkap/valid.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    //======cek akses plant======//
                    if (
                        ($this->access_plant !== "PKP" && !in_array($this->access_plant, [$plant]))
                        || ($this->access_plant === "PKP" && !in_array($plant, array("PKP1", "PKP2")))
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Tidak memiliki akses untuk plant $plant");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $validasi_tanggal = $this->validasi_tanggal($tanggal);
                    if (!$validasi_tanggal) {
                        $msg = "Tanggal Tidak Valid pada Barang $kode_barang pada No TTG $no_ttg. Gunakan format 'dd.mm.yyyy'";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }
                    $tanggal = $this->generate->regenerateDateFormat($tanggal);

                    //validasi ppn
                    if ($tanggal < "2022-04-01")
                        $list_ppn = array('B5');
                    else if ($tanggal == "2022-04-01")
                        $list_ppn = array('B5', 'BK');
                    else 
                        $list_ppn = array('BK');

                    if ($ppn_item && $ppn_item !== "" && !in_array(strtoupper($ppn_item), $list_ppn)) {
                        $msg     = "Nilai PPN Tidak Valid pada Barang $kode_barang pada No TTG $no_ttg. Input yg diperbolehkan adalah " . implode(", ", $list_ppn) . " atau kosong.";
                        $sts     = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $vendor = intval($vendor) == 0 ? $vendor : str_pad($vendor, 10, 0, STR_PAD_LEFT);
                    $cost_center = str_pad($cost_center, 10, 0, STR_PAD_LEFT);

                    // cek PPB kode barang
                    $data_ppb = $this->dtransaksi->get_ppb_detail(array(
                        "connect" => false,
                        "no_ppb" => $no_ppb,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant,
                        "tipe_po" => 'SITE',
                        // "is_closed" => false
                    ));

                    if (!$data_ppb) {
                        $return = array('sts' => 'NotOk', 'msg' => "Barang dengan kode $kode_barang tidak termasuk dalam PPB $no_ppb.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    if (($data_ppb[0]->jumlah_po + $jumlah) > $data_ppb[0]->jumlah_disetujui) {
                        unlink($data['upload_data']['full_path']);

                        $return = array('sts' => 'NotOk', 'msg' => "PO untuk item $kode_barang pada No TTG $no_ttg melebihi jumlah yang disetujui.");
                        echo json_encode($return);
                        exit();
                    }

                    // validasi vendor
                    $data_vendor = $this->dmaster->get_data_vendor(array(
                        "connect" => false,
                        "LIFNR" => $vendor,
                        "plant" => $plant,
                    ));

                    // validasi cost center
                    $data_cost_center = true;
                    if (in_array($data_ppb[0]->classification, ['A', 'K'])) {
                        if (!$cost_center || $cost_center == "") $data_cost_center = false;
                        else {
                            $data_cost_center = $this->dmaster->get_data_cost_center(array(
                                "connect" => false,
                                "code" => $cost_center,
                                "GSBER" => $plant,
                                "matnr" => $kode_barang,
                            ));
                        }
                    } else {
                        $cost_center = "";
                    }

                    if (!$data_vendor || !$data_cost_center) {
                        if (!$data_vendor) $text = "Kode Vendor $vendor tidak terdaftar untuk pabrik ini.";
                        else if (!$data_cost_center) $text = "Cost Center $cost_center belum terdaftar untuk item $kode_barang.";

                        $return = array('sts' => 'NotOk', 'msg' => $text);

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $id_ppb = $data_ppb[0]->id_ppb;

                    //======Data Header======//
                    if ($no_ttg != $current_ttg) {
                        // cek nomor ttg
                        $ck_data_header = $this->dtransaksi->get_gr_header(array(
                            "connect" => false,
                            "no_gr" => $no_ttg,
                            "plant" => $plant
                        ));

                        if ($ck_data_header) {
                            $msg = "No TTG $no_ttg sudah digunakan";
                            $sts = "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            unlink($data['upload_data']['full_path']);

                            echo json_encode($return);
                            exit();
                        } else {
                            $data_header_po = array(
                                "id_ppb" => 0,
                                // "no_ppb" => $no_ppb,
                                "plant"  => $plant,
                                "tanggal" => $tanggal,
                                "tipe_po" => "SITE",
                                "vendor" => $vendor,
                                // "diskon" => $total_diskon,
                                // "ppn" => $ppn,
                            );

                            $data_header_po = $this->dgeneral->basic_column("insert", $data_header_po);
                            $this->dgeneral->insert("tbl_ktp_po_header", $data_header_po);
                            $id_po = $this->db->insert_id();

                            $po_reff = "POS" . $id_po;

                            $data_header = array(
                                "po_reff" => $po_reff,
                            );
                            $data_header = $this->dgeneral->basic_column('update', $data_header, $datetime);
                            $this->dgeneral->update("tbl_ktp_po_header", $data_header, array(
                                array(
                                    'kolom' => 'id',
                                    'value' => $id_po
                                )
                            ));

                            //-----log PO------//
                            $data_log = array(
                                "id_transaksi"  => $id_po,
                                "tipe"          => "po",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);

                            $data_header_gr = array(
                                'no_gr'         => $no_ttg,
                                'id_po'         => $id_po,
                                'plant'            => $plant,
                                'tanggal'        => $tanggal,
                                "po_reff"       => $po_reff,
                                "tipe_po"       => 'SITE',
                            );

                            $data_header_gr = $this->dgeneral->basic_column("insert", $data_header_gr);
                            $this->dgeneral->insert("tbl_ktp_gr_header", $data_header_gr);
                            $id_gr = $this->db->insert_id();

                            //-----log GR------//
                            $data_log = array(
                                "id_transaksi"  => $id_gr,
                                "tipe"          => "gr",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
                        }
                        $no_detail_po = 1;
                        $no_detail_gr = 1;
                        $total_diskon = 0;
                        $ppn = "";
                        $nilai_ppn = 0;
                        $current_ttg = $no_ttg;
                        $current_ppb = $no_ppb;
                        $list_barang = [];
                        $jenis_po = $data_ppb[0]->classification;
                        $current_vendor = $vendor;
                    }

                    //======Data Detail======//
                    if (in_array($kode_barang, $list_barang)) {
                        $msg = "Barang $kode_barang pada No TTG $no_ttg double.";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    /*--tidak boleh ada lebih dari satu tipe barang dan vendor dalam setiap ttg--*/
                    if (
                        $jenis_po != $data_ppb[0]->classification
                        || $current_vendor != $vendor
                        // || $current_ppb != $no_ppb
                    ) {
                        if ($jenis_po != $data_ppb[0]->classification) $tipe_error = "tipe barang";
                        else if ($current_vendor != $vendor) $tipe_error = "vendor";
                        // else if ($current_ppb != $no_ppb) $tipe_error = "NO PPB";

                        $msg = "Terdapat lebih dari satu $tipe_error pada No TTG $no_ttg .";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $data_detail_po = array(
                        'id_po'             => $id_po,
                        'no_detail'         => $no_detail_po,
                        'no_detail_ppb'     => $data_ppb[0]->no_detail,
                        'id_ppb'            => $data_ppb[0]->id_ppb,
                        "kode_barang"       => $kode_barang,
                        "jumlah"            => (float) $jumlah,
                        "satuan"            => $data_ppb[0]->satuan,
                        "harga"             => (float) $harga,
                        "diskon"            => (float) $diskon,
                        "total"             => (float) (($jumlah * $harga) - $diskon),
                        "asset_class"       => $data_ppb[0]->asset_class,
                        "cost_center"       => $cost_center,
                        "gl_account"        => $data_ppb[0]->gl_account,
                        "classification"    => $data_ppb[0]->classification
                    );
                    $data_detail_po = $this->dgeneral->basic_column("insert", $data_detail_po);
                    $this->dgeneral->insert("tbl_ktp_po_detail", $data_detail_po);

                    //set diskon header
                    $total_diskon += $diskon;
                    //set ppn header
                    if (strtoupper($ppn_item) == 'B5') {
                        $ppn = strtoupper($ppn_item);
                        $nilai_ppn = 10;
                    } else if (strtoupper($ppn_item) == 'BK') {
                        $ppn = strtoupper($ppn_item);
                        $nilai_ppn = 11;
                    }

                    $data_header = array(
                        "diskon" => $total_diskon,
                        "ppn" => $ppn,
                        "nilai_ppn" => $nilai_ppn,
                    );
                    $data_header = $this->dgeneral->basic_column('update', $data_header, $datetime);
                    $this->dgeneral->update("tbl_ktp_po_header", $data_header, array(
                        array(
                            'kolom' => 'id',
                            'value' => $id_po
                        )
                    ));

                    if ($data_ppb[0]->classification == "A") {
                        for ($i = 0; $i < $jumlah; $i++) {
                            $data_detail_gr = array(
                                'id_gr'             => $id_gr,
                                'no_gr'             => $no_ttg,
                                'no_detail'         => $no_detail_gr,
                                'kode_barang'       => $kode_barang,
                                'satuan'            => $data_ppb[0]->satuan,
                                'jumlah'            => 1,
                                'keterangan'        => $keterangan,
                                'sloc'              => $sloc,
                                'id_po'             => $id_po
                            );
                            $data_detail_gr = $this->dgeneral->basic_column("insert", $data_detail_gr);
                            $this->dgeneral->insert("tbl_ktp_gr_detail", $data_detail_gr);
                            $no_detail_gr++;
                        }
                    } else {
                        $data_detail_gr = array(
                            'id_gr'             => $id_gr,
                            'no_gr'             => $no_ttg,
                            'no_detail'         => $no_detail_gr,
                            'kode_barang'       => $kode_barang,
                            'satuan'            => $data_ppb[0]->satuan,
                            'jumlah'            => $jumlah,
                            'keterangan'        => $keterangan,
                            'sloc'              => $sloc,
                            'id_po'             => $id_po
                        );
                        $data_detail_gr = $this->dgeneral->basic_column("insert", $data_detail_gr);
                        $this->dgeneral->insert("tbl_ktp_gr_detail", $data_detail_gr);
                        $no_detail_gr++;
                    }
                    $no_detail_po++;
                    $list_barang[] = $kode_barang;
                }
                unlink($data['upload_data']['full_path']);

                if ($this->db->trans_status() === false) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang diunggah";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil diunggah";
                    $sts = "OK";
                }
            }
        } else {
            $msg = "Silahkan pilih file yang ingin diunggah";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);
        exit();
    }

    private function save_upload_gr_ho($param = NULL)
    {
        $post = $this->input->post(NULL, TRUE);
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
        if (!empty($_FILES['file_excel']['name']) && !empty($_FILES['file_excel']['name']) != "") {
            $target_dir = "./assets/temp";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $config['upload_path']          = $target_dir;
            $config['allowed_types']        = 'xls|xlsx';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file_excel')) {
                $msg = $this->upload->display_errors();
                $sts = "NotOK";
            } else {
                $data = array('upload_data' => $this->upload->data());
                $objPHPExcel = PHPExcel_IOFactory::load($data['upload_data']['full_path']);
                // $title_desc		= $objPHPExcel->getProperties()->getTitle();
                $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
                $sheet_name     = $objWorksheet->getTitle();
                $data_excel = $objPHPExcel->getActiveSheet();
                $highestRow = $data_excel->getHighestRow();
                $highestColumn = PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                $data_row = array();

                if ($sheet_name != "GR") {
                    $return = array('sts' => 'NotOk', 'msg' => "File yang diupload tidak sesuai.");

                    unlink($data['upload_data']['full_path']);
                    echo json_encode($return);
                    exit();
                }

                $this->dgeneral->begin_transaction();

                $current_ttg = "";
                $current_po = "";
                $id_gr = "";
                $i = 1;
                $list_barang = array();

                for ($brs = 5; $brs <= $highestRow; $brs++) {
                    $no_ttg            = $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                    $plant            = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                    $tanggal        = $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                    $no_po            = $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                    $kode_barang    = $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                    $jumlah         = $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();
                    $keterangan     = $data_excel->getCellByColumnAndRow(6, $brs)->getFormattedValue();
                    $sloc           = $data_excel->getCellByColumnAndRow(7, $brs)->getFormattedValue();
                    if ((!$no_ttg || $no_ttg == "") && (!$kode_barang || $kode_barang == "")) {
                        break;
                    }

                    if (
                        !$no_po || $no_po == ""
                        || !$no_ttg || $no_ttg == ""
                        || !$plant || $plant == ""
                        || !$tanggal || $tanggal == ""
                        || !$kode_barang || $kode_barang == ""
                        || !$jumlah || $jumlah == "" || !is_numeric($jumlah)
                        || !$sloc || $sloc == ""
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data untuk barang $kode_barang pada No TTG $no_ttg tidak lengkap/valid.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    //======cek akses plant======//
                    if (
                        ($this->access_plant !== "PKP" && !in_array($this->access_plant, [$plant]))
                        || ($this->access_plant === "PKP" && !in_array($plant, array("PKP1", "PKP2")))
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Tidak memiliki akses untuk plant $plant");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $validasi_tanggal = $this->validasi_tanggal($tanggal);
                    if (!$validasi_tanggal) {
                        $msg = "Tanggal Tidak Valid pada Barang $kode_barang pada No TTG $no_ttg. Gunakan format 'dd.mm.yyyy'";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $tanggal = $this->generate->regenerateDateFormat($tanggal);

                    // cek PO kode barang
                    $data_po = $this->dtransaksi->get_po_detail_by_no_po(array(
                        "connect" => false,
                        "no_po" => $no_po,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant,
                        "tipe_po" => 'HO',
                    ));

                    if (!$data_po) {
                        $return = array('sts' => 'NotOk', 'msg' => "Barang dengan kode $kode_barang pada No TTG $no_ttg tidak termasuk dalam PO $no_po.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    // validasi jumlah po dan ttg
                    if (($data_po[0]->jumlah_gr + $jumlah) > $data_po[0]->jumlah) {
                        unlink($data['upload_data']['full_path']);

                        $return = array('sts' => 'NotOk', 'msg' => "TTG untuk item $kode_barang pada No TTG $no_ttg melebihi jumlah PO.");
                        echo json_encode($return);
                        exit();
                    }

                    // $id_po = $data_po[0]->id_po;
                    $id_po = 0;

                    //======Data Header======//
                    if ($no_ttg != $current_ttg) {
                        // cek nomor ttg
                        $ck_data_header = $this->dtransaksi->get_gr_header(array(
                            "connect" => false,
                            "no_gr" => $no_ttg,
                            "plant" => $plant
                        ));

                        if ($ck_data_header) {
                            $msg = "No TTG $no_ttg sudah digunakan";
                            $sts = "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            unlink($data['upload_data']['full_path']);

                            echo json_encode($return);
                            exit();
                        } else {
                            $data_header = array(
                                'no_gr'     => $no_ttg,
                                'id_po'     => $id_po,
                                'plant'     => $plant,
                                'tanggal'   => $tanggal,
                                "po_reff"   => $no_po,
                                "no_po"     => $no_po,
                                "tipe_po"   => 'HO'
                            );

                            $data_header = $this->dgeneral->basic_column("insert", $data_header);
                            $this->dgeneral->insert("tbl_ktp_gr_header", $data_header);
                            $id_gr = $this->db->insert_id();

                            //-----log GR------//
                            $data_log = array(
                                "id_transaksi"  => $id_gr,
                                "tipe"          => "gr",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
                        }
                        $i = 1;
                        $current_ttg = $no_ttg;
                        $current_po = $no_po;
                        $list_barang = [];
                    }

                    //======Data Detail======//
                    if (in_array($kode_barang, $list_barang)) {
                        $msg = "Barang $kode_barang pada No TTG $no_ttg double.";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    /*--tidak boleh ada lebih dari satu tipe barang dan vendor dalam setiap ttg--*/
                    if (
                        $current_po != $no_po
                    ) {
                        $msg = "Terdapat lebih dari satu NO PO pada No TTG $no_ttg .";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $jumlah_masuk = $jumlah;
                    $item = $this->dtransaksi->get_po_detail_by_line_item(array(
                        "connect" => false,
                        "no_po" => $no_po,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant,
                        "tipe_po" => 'HO',
                        "sisa" => true
                    ));

                    for ($j = 0; $j < count($item); $j++) {
                        if ($jumlah_masuk > 0) {
                            if ($jumlah_masuk < $item[$j]->sisa) {
                                $qty = $jumlah_masuk;
                                $jumlah_masuk = 0;
                            } else {
                                $qty = $item[$j]->sisa;
                                $jumlah_masuk -= $item[$j]->sisa;
                            }

                            if (($item[$j]->sisa - $qty) < 0) {
                                $return = array('sts' => "NotOK", 'msg' => "melebihi stok");
                                unlink($data['upload_data']['full_path']);

                                echo json_encode($return);
                                exit();
                            }

                            $data_detail = array(
                                'id_gr'             => $id_gr,
                                'no_gr'             => $no_ttg,
                                'no_detail'         => $i,
                                'kode_barang'       => $kode_barang,
                                'satuan'            => $data_po[0]->satuan,
                                'jumlah'            => $qty,
                                'keterangan'        => $keterangan,
                                'sloc'              => $sloc,
                                'id_po'             => $item[$j]->id_po,
                                'item_po'           => $item[$j]->no_item_po,
                            );
                            $data_detail = $this->dgeneral->basic_column("insert", $data_detail);
                            $this->dgeneral->insert("tbl_ktp_gr_detail", $data_detail);
                            $i++;
                        }
                    }

                    $list_barang[] = $kode_barang;
                }
                unlink($data['upload_data']['full_path']);

                if ($this->db->trans_status() === false) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang diunggah";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil diunggah";
                    $sts = "OK";
                }
            }
        } else {
            $msg = "Silahkan pilih file yang ingin diunggah";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);
        exit();
    }

    private function save_upload_gi($param = NULL)
    {
        $post = $this->input->post(NULL, TRUE);
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
        if (!empty($_FILES['file_excel']['name']) && !empty($_FILES['file_excel']['name']) != "") {
            $target_dir = "./assets/temp";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $config['upload_path']          = $target_dir;
            $config['allowed_types']        = 'xls|xlsx';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file_excel')) {
                $msg = $this->upload->display_errors();
                $sts = "NotOK";
            } else {
                $data = array('upload_data' => $this->upload->data());
                $objPHPExcel = PHPExcel_IOFactory::load($data['upload_data']['full_path']);
                // $title_desc		= $objPHPExcel->getProperties()->getTitle();
                $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
                $sheet_name     = $objWorksheet->getTitle();
                $data_excel = $objPHPExcel->getActiveSheet();
                $highestRow = $data_excel->getHighestRow();
                $highestColumn = PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                $data_row = array();

                if ($sheet_name != "GI") {
                    $return = array('sts' => 'NotOk', 'msg' => "File yang diupload tidak sesuai.");

                    unlink($data['upload_data']['full_path']);
                    echo json_encode($return);
                    exit();
                }

                $this->dgeneral->begin_transaction();

                $current_bkb = "";
                $current_tipe = "";
                $id_gi = "";
                $i = 1;
                $list_barang = array();

                for ($brs = 5; $brs <= $highestRow; $brs++) {
                    $no_bkb            = $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                    $plant            = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                    $tanggal        = $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                    $no_spb            = $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                    $kode_barang    = $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                    $gl_account        = $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();
                    $no_io          = $data_excel->getCellByColumnAndRow(6, $brs)->getFormattedValue();
                    $cost_center    = $data_excel->getCellByColumnAndRow(7, $brs)->getFormattedValue();
                    $afd            = $data_excel->getCellByColumnAndRow(8, $brs)->getFormattedValue();
                    $blok            = $data_excel->getCellByColumnAndRow(9, $brs)->getFormattedValue();
                    $kategori        = $data_excel->getCellByColumnAndRow(10, $brs)->getFormattedValue();
                    $kode_vra        = $data_excel->getCellByColumnAndRow(11, $brs)->getFormattedValue();
                    $no_polisi        = $data_excel->getCellByColumnAndRow(12, $brs)->getFormattedValue();
                    $jumlah         = $data_excel->getCellByColumnAndRow(13, $brs)->getFormattedValue();
                    $keterangan     = $data_excel->getCellByColumnAndRow(14, $brs)->getFormattedValue();
                    $sloc           = $data_excel->getCellByColumnAndRow(15, $brs)->getFormattedValue();

                    if ((!$no_bkb || $no_bkb == "") && (!$kode_barang || $kode_barang == "")) {
                        break;
                    }

                    if (
                        !$gl_account || $gl_account == ""
                        || !$no_bkb || $no_bkb == ""
                        || !$tanggal || $tanggal == ""
                        || !$no_spb || $no_spb == ""
                        || !$plant || $plant == ""
                        || !$kode_barang || $kode_barang == ""
                        // || ((!$no_io || $no_io == "") && (!$cost_center || $cost_center == ""))
                        || !$jumlah || $jumlah == "" || !is_numeric($jumlah)
                        || !$sloc || $sloc == ""
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data untuk barang $kode_barang pada No BKB $no_bkb tidak lengkap/valid.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    //======cek akses plant======//
                    if (
                        ($this->access_plant !== "PKP" && !in_array($this->access_plant, [$plant]))
                        || ($this->access_plant === "PKP" && !in_array($plant, array("PKP1", "PKP2")))
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Tidak memiliki akses untuk plant $plant");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $validasi_tanggal = $this->validasi_tanggal($tanggal);
                    if (!$validasi_tanggal) {
                        $msg = "Tanggal Tidak Valid pada Barang $kode_barang pada No BKB $no_bkb. Gunakan format 'dd.mm.yyyy'";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $tanggal = $this->generate->regenerateDateFormat($tanggal);
                    // $cost_center = str_pad($cost_center,10,0, STR_PAD_LEFT);
                    $gl_account = str_pad($gl_account, 10, 0, STR_PAD_LEFT);

                    // cek kode barang
                    $cek_barang = $this->dmaster->get_data_material_by_plant(array(
                        "connect" => false,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant
                    ));

                    //======Tipe BKB======//
                    //gl_account + cost_center = tipe 1
                    if (($cost_center && $cost_center != "") && (!$no_io || $no_io == ""))
                        $tipe_bkb = 1;
                    //gl_account only
                    else if ((!$cost_center && $cost_center == "") && (!$no_io || $no_io == ""))
                        $tipe_bkb = 2;
                    //gl_account + no io
                    else if (($no_io || $no_io != "") && (!$cost_center && $cost_center == ""))
                        $tipe_bkb = 3;
                    //cost center + no_io
                    else if (($cost_center && $cost_center != "") && ($no_io || $no_io != "")) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data Tidak Valid pada Barang $kode_barang pada No BKB $no_bkb. Cost Center dan No IO tidak boleh diisi secara bersamaan.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    // cek gl account
                    $cek_gl_account = $this->dmaster->get_data_gl_account(array(
                        "connect" => false,
                        "code" => $gl_account,
                        "GSBER" => $plant,
                    ));

                    $cek_io = 1;
                    // cek io
                    if ($no_io && $no_io != "") {
                        $no_io = str_pad($no_io, 12, 0, STR_PAD_LEFT);
                        $cek_io = $this->dmaster->get_data_io(array(
                            "connect" => false,
                            "AUFNR" => $no_io,
                            "plant" => $plant,
                            "status" => "open"
                        ));
                    }

                    // cek cost center
                    $cek_cost_center = 1;
                    if ($cost_center && $cost_center != "") {
                        $cost_center = str_pad($cost_center, 10, 0, STR_PAD_LEFT);
                        $cek_cost_center = $this->dmaster->get_data_cost_center(array(
                            "connect" => false,
                            "code" => $cost_center,
                            "GSBER" => $plant,
                        ));
                    }

                    if (!$cek_barang || !$cek_gl_account || !$cek_cost_center || !$cek_io) {
                        if (!$cek_barang) $text = "Barang dengan kode $kode_barang belum terdaftar untuk pabrik ini.";
                        else if (!$cek_gl_account) $text = "COA $gl_account belum terdaftar untuk item pabrik ini.";
                        else if (!$cek_cost_center) $text = "Cost Center $cost_center belum terdaftar untuk item pabrik ini.";
                        else if (!$cek_io) $text = "IO $no_io tidak terdaftar / berstatus closed untuk pabrik ini.";

                        $return = array('sts' => 'NotOk', 'msg' => $text);

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    //======Data Header======//
                    if ($no_bkb != $current_bkb) {
                        // cek nomor bkb
                        $ck_data_header = $this->dtransaksi->get_gi_header(array(
                            "connect" => false,
                            "no_gi" => $no_bkb,
                            "plant" => $plant
                        ));

                        if ($ck_data_header) {
                            $msg = "No BKB $no_bkb sudah digunakan";
                            $sts = "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            unlink($data['upload_data']['full_path']);

                            echo json_encode($return);
                            exit();
                        } else {
                            $data_header = array(
                                'no_gi'     => $no_bkb,
                                'plant'     => $plant,
                                'tanggal'   => $tanggal,
                                'afd'       => $afd,
                                'no_spb'    => $no_spb,
                            );

                            $data_header = $this->dgeneral->basic_column("insert", $data_header);
                            $this->dgeneral->insert("tbl_ktp_gi_header", $data_header);
                            $id_gi = $this->db->insert_id();

                            //-----log GI------//
                            $data_log = array(
                                "id_transaksi"  => $id_gi,
                                "tipe"          => "gi",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
                        }
                        $i = 1;
                        $current_bkb = $no_bkb;
                        $current_tipe = $tipe_bkb;
                        $list_barang = [];
                    }

                    //======Data Detail======//
                    if (in_array($kode_barang, $list_barang)) {
                        $msg = "Barang $kode_barang pada No BKB $no_bkb double.";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    /*--tidak boleh ada lebih dari satu tipe bkb dalam setiap bkb--*/
                    if (
                        $current_tipe != $tipe_bkb
                    ) {
                        $msg = "Terdapat lebih dari satu tipe bkb pada No BKB $no_bkb .";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $data_detail = array(
                        'id_gi'             => $id_gi,
                        'no_gi'             => $no_bkb,
                        'no_detail'            => $i,
                        'kode_barang'        => $kode_barang,
                        'satuan'            => $cek_barang->MEINS,
                        'jumlah'            => $jumlah,
                        'gl_account'        => $gl_account,
                        'cost_center'        => $cost_center,
                        // 'afd'	            => $afd,
                        'blok'              => $blok,
                        'kategori'            => $kategori,
                        'kode_vra'            => $kode_vra,
                        'no_polisi'            => $no_polisi,
                        'keterangan'        => $keterangan,
                        'sloc'              => $sloc,
                        'no_io'             => $no_io,
                    );
                    $data_detail = $this->dgeneral->basic_column("insert", $data_detail);
                    $this->dgeneral->insert("tbl_ktp_gi_detail", $data_detail);
                    $i++;
                    $list_barang[] = $kode_barang;
                }
                unlink($data['upload_data']['full_path']);

                if ($this->db->trans_status() === false) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang diunggah";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil diunggah";
                    $sts = "OK";
                }
            }
        } else {
            $msg = "Silahkan pilih file yang ingin diunggah";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);
        exit();
    }

    private function save_ppb($param = NULL)
    {
        $post = $this->input->post(NULL, TRUE);
        $datetime = date("Y-m-d H:i:s");
        $id_ppb = $this->generate->kirana_decrypt($post["id_ppb"]);
        $no_ppb = $post["no_ppb"];

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        //================================UPLOAD FILE================================//        
        if (isset($_POST['id_file'])) {
            $id_file = $_POST['id_file'];
        } else $id_file = NULL;

        if (isset($_FILES['file_ppb']) && $_FILES['file_ppb']['error'][0] == 0 && $_FILES['file_ppb']['name'][0] !== "") {
            if (count($_FILES['file_ppb']['name']) > 1) {
                $msg    = "You can only upload maximum 1 file";
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            $newname = array(str_replace('/', '-', $no_ppb));

            //UPLOADING
            $config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module()) . '/ppb';
            $config['allowed_types'] = 'jpg|png|pdf';

            $file = $this->general->upload_files($_FILES['file_ppb'], $newname, $config)[0];
            if ($file) {
                $data_file = array(
                    'filename'     => $file['filename'],
                    'size'         => $file['size'],
                    'ext'          => pathinfo($file['full_path'], PATHINFO_EXTENSION),
                    'location'     => $file['url'],
                    'tipe'         => 'ppb',
                );

                if (isset($_POST['id_file']) && $_POST['id_file'] !== "") {
                    $data_file = $this->dgeneral->basic_column("update", $data_file);
                    $this->dgeneral->update('tbl_ktp_file', $data_file, array(
                        array(
                            'kolom' => 'id',
                            'value' => $id_file
                        )
                    ));
                } else {
                    $data_file = $this->dgeneral->basic_column("insert", $data_file);
                    $this->dgeneral->insert('tbl_ktp_file', $data_file);
                    $id_file = $this->db->insert_id();
                }

                $data_row = array(
                    "id_file" => $id_file,
                );
                $data_row = $this->dgeneral->basic_column("update", $data_row);
                $this->dgeneral->update('tbl_ktp_ppb_header', $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $id_ppb
                    )
                ));
            }
        }

        if ($this->db->trans_status() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang diunggah";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil disimpan";
            $sts = "OK";
        }

        $this->general->closeDb();

        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);
        exit();
    }

    private function save_konfirm_ppb($param =  NULL)
    {
        $post = $this->input->post(NULL, TRUE);
        $datetime = date("Y-m-d H:i:s");
        $id_ppb = $this->generate->kirana_decrypt($post["id_ppb"]);
        $tanggal_konfirmasi = $this->generate->regenerateDateFormat($post["tanggal_konfirmasi"]);
        $tipe_po = $post["tipe_po"];

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_ppb = $this->dtransaksi->get_ppb_header(array(
            "id_ppb" => $id_ppb,
            "connect" => false,
        ));

        //set tanggal konfirmasi
        if (!$data_ppb->tanggal_konfirmasi) {
            $data_row = array(
                "tanggal_konfirmasi" => $datetime,
            );
            $data_row = $this->dgeneral->basic_column("update", $data_row);
            $this->dgeneral->update('tbl_ktp_ppb_header', $data_row, array(
                array(
                    'kolom' => 'id',
                    'value' => $id_ppb
                )
            ));

            $data_log = array(
                "id_transaksi"  => $id_ppb,
                "tipe"          => "ppb",
                "tgl_status"    => $datetime,
                // "status"        => "",
                "action"        => 'confirm'
            );
            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
            $data_log['tanggal_edit'] = $datetime;
            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
        }

        foreach ($post['item_ppb'] as $item) {
            //cek status PO dari item bersangkutan
            $cek = $this->dtransaksi->get_ppb_detail(array(
                "connect" => false,
                "no_detail" => $item,
                "id_ppb" => $id_ppb,
            ));

            if ((float) $cek->jumlah_po) {
                $return = array('sts' => 'NotOk', 'msg' => "PO untuk item $cek->kode_barang sudah terbentuk.");
                echo json_encode($return);
                exit();
            }

            $data_row = array(
                "tipe_po" => $tipe_po,
                "jumlah_disetujui" => (float) str_replace(",", "", $post["jumlah_disetujui_$item"]),
                'classification' => $post["classification_$item"]
            );
            $data_row = $this->dgeneral->basic_column('update', $data_row, $datetime);
            $this->dgeneral->update("tbl_ktp_ppb_detail", $data_row, array(
                array(
                    'kolom' => 'id_ppb',
                    'value' => $id_ppb
                ),
                array(
                    'kolom' => 'no_detail',
                    'value' => $item
                )
            ));
        }

        if ($this->db->trans_status() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang diunggah";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil disimpan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);
        exit();
    }

    private function save_po_ho($param =  NULL)
    {
        $post = $this->input->post(NULL, TRUE);
        $datetime = date("Y-m-d H:i:s");
        $id_ppb = $this->generate->kirana_decrypt($post["id_ppb"]);
        $tanggal_po = $this->generate->regenerateDateFormat($post["tanggal"]);
        // $tipe_po = $post["tipe_po"];

        $test_PO = $this->simulasi_po(array(
            "connect" => TRUE,
            "return" => "array"
        ));

        if ($test_PO['sts'] == 'NotOK') {
            $sts = 'NotOK';
            $msg = $test_PO['msg'];
        } else {
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_header = array(
                "id_ppb" => $id_ppb,
                "no_ppb" => $post['no_ppb'],
                "plant"  => $post['plant'],
                "tanggal" => $tanggal_po,
                "tipe_po" => "HO",
                "vendor" => $post['vendor'],
                "diskon" => (float) str_replace(",", "", $post['total_diskon']),
                "ppn" => $post['ppn'],
                "nilai_ppn" => $post['nilai_ppn']
            );

            $data_header = $this->dgeneral->basic_column("insert", $data_header);
            $this->dgeneral->insert("tbl_ktp_po_header", $data_header);
            $id_po = $this->db->insert_id();

            //-----log PO------//
            $data_log = array(
                "id_transaksi"  => $id_po,
                "tipe"          => "po",
                "tgl_status"    => $datetime,
                // "status"        => "",
                "action"        => 'submit'
            );
            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
            $data_log['tanggal_edit'] = $datetime;
            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);

            $jenis_po = "";
            $i = 1;
            foreach ($post['item_ppb'] as $item) {
                $jenis_po = $post["tipe_barang_$item"];
                $jumlah = (float) str_replace(",", "", $post["jumlah_barang_$item"]);
                //cek status PO dari item bersangkutan
                $cek = $this->dtransaksi->get_ppb_detail(array(
                    "connect" => false,
                    "no_detail" => $item,
                    "id_ppb" => $id_ppb,
                ));

                if (($cek->jumlah_po + $jumlah) > $cek->jumlah_disetujui) {
                    $return = array('sts' => 'NotOk', 'msg' => "PO untuk item $cek->kode_barang melebihi jumlah yang disetujui.");
                    echo json_encode($return);
                    exit();
                }

                $data_detail = array(
                    'id_po'                => $id_po,
                    'no_detail'                => $i,
                    'no_detail_ppb'         => $item,
                    "id_ppb" => $id_ppb,
                    "kode_barang" => $post["kode_barang_$item"],
                    "jumlah" => (float) str_replace(",", "", $jumlah),
                    "satuan" => $post["satuan_barang_$item"],
                    "harga" => (float) str_replace(",", "", $post["harga_barang_$item"]),
                    "diskon" => (float) str_replace(",", "", $post["diskon_barang_$item"]),
                    "total" => (float) str_replace(",", "", $post["total_barang_$item"]),
                    "asset_class" => $post["asset_class_barang_$item"],
                    "cost_center" => @$post["cost_center_barang_$item"],
                    "gl_account" => $post["gl_account_barang_$item"],
                    "classification" => $post["classification_barang_$item"],
                );
                $data_detail = $this->dgeneral->basic_column("insert", $data_detail);
                $this->dgeneral->insert("tbl_ktp_po_detail", $data_detail);
                $i++;
            }

            if ($this->db->trans_status() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang diunggah";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil disimpan";
                $sts = "OK";
            }
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);
        exit();
    }

    private function save_attachment_transaksi($param = NULL)
    {
        $post = $this->input->post(NULL, TRUE);
        $datetime = date("Y-m-d H:i:s");

        $tipe = $post["tipe"];

        if ($tipe == "ppb") {
            $id_transaksi = $this->generate->kirana_decrypt($post["id_ppb"]);
            $nomor = $post["no_ppb"];
            $newname = array(str_replace('/', '-', $nomor));
            $tbl_header = "tbl_ktp_ppb_header";
        } else if ($tipe == "po") {
            $id_transaksi = $this->generate->kirana_decrypt($post["id_po"]);
            $nomor = $post["no_ppb"];
            $newname = array('PO' . $id_transaksi . '-' . str_replace('/', '-', $nomor));
            $tbl_header = "tbl_ktp_po_header";
        } else if ($tipe == "gr") {
            $id_transaksi = $this->generate->kirana_decrypt($post["id_gr"]);
            $nomor = $post["no_gr"];
            $newname = array(str_replace('/', '-', $nomor));
            $tbl_header = "tbl_ktp_gr_header";
        } else {
            $msg    = "Tipe Transaksi Harus Valid";
            $sts    = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);
            exit();
        }

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        //================================UPLOAD FILE================================//        
        if (isset($_POST['id_file'])) {
            $id_file = $_POST['id_file'];
        } else $id_file = NULL;

        if (isset($_FILES['file']) && $_FILES['file']['error'][0] == 0 && $_FILES['file']['name'][0] !== "") {
            if (count($_FILES['file']['name']) > 1) {
                $msg    = "You can only upload maximum 1 file";
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            //UPLOADING
            $config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module()) . '/' . $tipe;
            $config['allowed_types'] = 'jpg|png|pdf';
            $config['max_size'] = 5000;

            $file = $this->general->upload_files($_FILES['file'], $newname, $config)[0];
            if ($file) {
                $data_file = array(
                    'filename'     => $file['filename'],
                    'size'         => $file['size'],
                    'ext'          => pathinfo($file['full_path'], PATHINFO_EXTENSION),
                    'location'     => $file['url'],
                    'tipe'         => $tipe,
                );

                if (isset($_POST['id_file']) && $_POST['id_file'] !== "") {
                    $data_file = $this->dgeneral->basic_column("insert", $data_file);
                    $this->dgeneral->update('tbl_ktp_file', $data_file, array(
                        array(
                            'kolom' => 'id',
                            'value' => $id_file
                        )
                    ));
                } else {
                    $data_file = $this->dgeneral->basic_column("insert", $data_file);
                    $this->dgeneral->insert('tbl_ktp_file', $data_file);
                    $id_file = $this->db->insert_id();
                }

                $data_row = array(
                    "id_file" => $id_file,
                );
                $data_row = $this->dgeneral->basic_column("update", $data_row);
                $this->dgeneral->update($tbl_header, $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $id_transaksi
                    )
                ));
            }
        }

        if ($this->db->trans_status() === false) {
            $this->dgeneral->rollback_transaction();
            $msg     = "Periksa kembali data yang diunggah";
            $sts     = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg     = "Data berhasil disimpan";
            $sts     = "OK";
        }

        $this->general->closeDb();

        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);
        exit();
    }

    private function delete_ppb($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);
        $id_ppb = $this->generate->kirana_decrypt($post["id_ppb"]);
        $ppb_header = $this->get_data_ppb(array(
            "connect" => $param['connect'],
            "data" => "header",
            "id_ppb" => $id_ppb
        ));

        if ($ppb_header) {
            if ($ppb_header->jumlah_konfirmasi > 0 && $ppb_header->jumlah_hari_berjalan > 30) {
                $msg = "PPB sudah terkonfirmasi. Tidak dapat dihapus.";
                $sts = "NotOK";

                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data = $this->dgeneral->basic_column('delete', NULL, $datetime);
            $this->dgeneral->update("tbl_ktp_ppb_header", $data, array(
                array(
                    'kolom' => 'id',
                    'value' => $id_ppb
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

            //======set all ppb detail not active======//
            $this->dgeneral->update("tbl_ktp_ppb_detail", $data, array(
                array(
                    'kolom' => 'id_ppb',
                    'value' => $id_ppb
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

            $data_log = array(
                "id_transaksi"  => $id_ppb,
                "tipe"          => "ppb",
                "tgl_status"    => $datetime,
                // "status"        => "",
                "action"        => 'delete',
                "comment"       => $post['alasan']
            );
            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
            $data_log['tanggal_edit'] = $datetime;
            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);

            if ($this->db->trans_status() === false) {
                $this->dgeneral->rollback_transaction();
                $msg     = "Periksa kembali data yang diunggah";
                $sts     = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg     = "Data berhasil dihapus";
                $sts     = "OK";
            }

            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->closeDb();
        } else {
            $msg = "PPB tidak ditemukan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
        exit();
    }

    private function delete_po($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);
        $id_po = $this->generate->kirana_decrypt($post["id_po"]);
        $po_header = $this->get_data_po(array(
            "connect" => $param['connect'],
            "data" => "header",
            "id_po" => $id_po,
            "IN_tipe" => ['HO'],
            "status_sap" => 'not_completed',
        ));

        if ($po_header) {
            if ($po_header->done_kirim_sap && $po_header->status_sap == "success") {
                $msg = "PO sudah terkirim ke SAP. Tidak dapat dihapus.";
                $sts = "NotOK";

                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data = $this->dgeneral->basic_column('delete', NULL, $datetime);
            $this->dgeneral->update("tbl_ktp_po_header", $data, array(
                array(
                    'kolom' => 'id',
                    'value' => $id_po
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

            //======set all po detail not active======//
            $this->dgeneral->update("tbl_ktp_po_detail", $data, array(
                array(
                    'kolom' => 'id_po',
                    'value' => $id_po
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

            $data_log = array(
                "id_transaksi"  => $id_po,
                "tipe"          => "po",
                "tgl_status"    => $datetime,
                // "status"        => "",
                "action"        => 'delete',
                "comment"       => $post['alasan']
            );
            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
            $data_log['tanggal_edit'] = $datetime;
            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);

            if ($this->db->trans_status() === false) {
                $this->dgeneral->rollback_transaction();
                $msg     = "Periksa kembali data yang diunggah";
                $sts     = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg     = "Data berhasil dihapus";
                $sts     = "OK";
            }

            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->closeDb();
        } else {
            $msg = "PO tidak ditemukan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
        exit();
    }

    private function delete_gr($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);
        $id_gr = $this->generate->kirana_decrypt($post["id_gr"]);
        $gr_header = $this->get_data_gr(array(
            "connect" => $param['connect'],
            "data" => "header",
            "id_gr" => $id_gr,
            "status_sap" => 'not_completed',
        ));

        if ($gr_header) {
            if ($gr_header->done_kirim_sap && $gr_header->status_sap == "success") {
                $msg = "TTG sudah terkirim ke SAP. Tidak dapat dihapus.";
                $sts = "NotOK";

                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data = $this->dgeneral->basic_column('delete', NULL, $datetime);
            $this->dgeneral->update("tbl_ktp_gr_header", $data, array(
                array(
                    'kolom' => 'id',
                    'value' => $id_gr
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

            //======set all po detail not active======//
            $this->dgeneral->update("tbl_ktp_gr_detail", $data, array(
                array(
                    'kolom' => 'id_gr',
                    'value' => $id_gr
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

            $data_log = array(
                "id_transaksi"  => $id_gr,
                "tipe"          => "gr",
                "tgl_status"    => $datetime,
                // "status"        => "",
                "action"        => 'delete',
                "comment"       => $post['alasan']
            );
            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
            $data_log['tanggal_edit'] = $datetime;
            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);

            //======delete PO Site======//
            if ($gr_header->tipe_po == "SITE") {
                $id_po = $gr_header->id_po;
                $po_header = $this->get_data_po(array(
                    "connect" => false,
                    "data" => "header",
                    "id_gr" => $id_gr,
                    "IN_tipe" => ['SITE'],
                    "status_sap" => 'not_completed',
                ));

                if ($po_header) {
                    $data = $this->dgeneral->basic_column('delete', NULL, $datetime);
                    $this->dgeneral->update("tbl_ktp_po_header", $data, array(
                        array(
                            'kolom' => 'id',
                            'value' => $id_po
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

                    //======set all po detail not active======//
                    $this->dgeneral->update("tbl_ktp_po_detail", $data, array(
                        array(
                            'kolom' => 'id_po',
                            'value' => $id_po
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

                    $data_log = array(
                        "id_transaksi"  => $id_po,
                        "tipe"          => "po",
                        "tgl_status"    => $datetime,
                        // "status"        => "",
                        "action"        => 'delete',
                        "comment"       => $post['alasan']
                    );
                    $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                    $data_log['tanggal_edit'] = $datetime;
                    $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
                } else {
                    $msg = "PO tidak ditemukan";
                    $sts = "NotOK";

                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                    exit();
                }
            }

            if ($this->db->trans_status() === false) {
                $this->dgeneral->rollback_transaction();
                $msg     = "Periksa kembali data yang diunggah";
                $sts     = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg     = "Data berhasil dihapus";
                $sts     = "OK";
            }

            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->closeDb();
        } else {
            $msg = "TTG tidak ditemukan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
        exit();
    }

    private function delete_gi($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);
        $id_gi = $this->generate->kirana_decrypt($post["id_gi"]);
        $gi_header = $this->get_data_gi(array(
            "connect" => $param['connect'],
            "data" => "header",
            "id_gi" => $id_gi,
            "status_sap" => 'not_completed',
        ));

        if ($gi_header) {
            if ($gi_header->done_kirim_sap && $gi_header->status_sap == "success") {
                $msg = "BKB sudah terkirim ke SAP. Tidak dapat dihapus.";
                $sts = "NotOK";

                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data = $this->dgeneral->basic_column('delete', NULL, $datetime);
            $this->dgeneral->update("tbl_ktp_gi_header", $data, array(
                array(
                    'kolom' => 'id',
                    'value' => $id_gi
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

            //======set all gi detail not active======//
            $this->dgeneral->update("tbl_ktp_gi_detail", $data, array(
                array(
                    'kolom' => 'id_gi',
                    'value' => $id_gi
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

            $data_log = array(
                "id_transaksi"  => $id_gi,
                "tipe"          => "gi",
                "tgl_status"    => $datetime,
                // "status"        => "",
                "action"        => 'delete',
                "comment"       => $post['alasan']
            );
            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
            $data_log['tanggal_edit'] = $datetime;
            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);

            if ($this->db->trans_status() === false) {
                $this->dgeneral->rollback_transaction();
                $msg     = "Periksa kembali data yang diunggah";
                $sts     = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg     = "Data berhasil dihapus";
                $sts     = "OK";
            }

            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->closeDb();
        } else {
            $msg = "BKB tidak ditemukan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
        exit();
    }

    private function simulasi_po($param = NULL)
    {
        $this->connectSAP("ERP_310");
        $datetime = date("Y-m-d H:i:s");

        $post = $this->input->post(NULL, TRUE);

        $type = array();
        $message = array();
        $data_send = array();
        $iserror = false;

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->dgeneral->begin_transaction();

        if ($this->data['sap']->getStatus() == SAPRFC_OK) {
            $table = array();
            foreach ($post['item_ppb'] as $item) {
                $detail  = array(
                    "LIFNR" => $post['vendor'],
                    "MATNR" => $post["kode_barang_$item"],
                    "MENGE" => (float) str_replace(",", "", $post["jumlah_barang_$item"]),
                    "MEINS" => $post["satuan_barang_$item"],
                    "EKORG" => $post["plant"],
                    "BEDAT" => date_format(date_create($post["tanggal"]), "Ymd"),
                    "EDATU" => date_format(date_create($post["tanggal"]), "Ymd"),
                    "NETPR" => (float) str_replace(",", "", $post["harga_barang_$item"]),
                    "WAERS" => "IDR",
                    "PEINH" => 1,
                    "EKGRP" => "OTH",
                    // "KNTTP" => ($post["classification_$item"] == "A" || $post["classification_$item"] == "K") ? $post["classification_$item"] : "",
                    "ANLKL" => $post["asset_class_barang_$item"],
                    "KOSTL" => @$post["cost_center_barang_$item"],
                    "TXT50" => "",
                    "SAKNR" => $post["gl_account_barang_$item"],
                    "BUKRS" => "",
                    "ANLN1" => "",
                    "ANLN2" => "",
                    "AUFNR" => "",
                    "HDDIS" => (float) str_replace(",", "", $post["diskon_barang_$item"]),
                    "MWSKZ" => $post['ppn']
                );
                $table[] = $detail;
            }

            $param_rfc = array(
                array("IMPORT", "I_EKORG", $post["plant"]),
                array("TABLE", "T_RETURN", array()),
                array("TABLE", "T_DATAPO", $table)
            );

            $result = $this->data['sap']->callFunction('Z_RFC_PAS_TESTRUN_PURCHORDER', $param_rfc);

            //cek kalo ada error
            if (!empty($result["T_RETURN"])) {
                foreach ($result["T_RETURN"] as $return) {
                    if ("E" == $return['TYPE']) {
                        $iserror = true;
                        break;
                    }
                }
            }

            if (
                $this->data['sap']->getStatus() == SAPRFC_OK
                // (!$isupdate && $result["E_AUFNR"] != "") && 
                && !empty($result["T_RETURN"])
                && !$iserror
            ) {
                $data_row_log = array(
                    'app'           => 'DATA RFC SIMULASI CREATE PO',
                    'rfc_name'      => 'Z_RFC_PAS_TESTRUN_PURCHORDER',
                    'log_code'      => 'S',
                    'log_status'    => 'Berhasil',
                    'log_desc'      => "Berhasil Simulasi PO",
                    'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                    'executed_date' => $datetime
                );
                // $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
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
                    'app'           => 'DATA RFC SIMULASI CREATE PO',
                    'rfc_name'      => 'Z_RFC_PAS_TESTRUN_PURCHORDER',
                    'log_code'      => 'implode(" , ", $type_fail)',
                    'log_status'    => 'Gagal',
                    'log_desc'      => "SIMULASI CREATE PO Failed [T_RETURN]: " . implode(" , ", $msg_fail),
                    'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                    'executed_date' => $datetime
                );

                $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
            }
            $data_send[] = $data_row_log;
        } else {
            $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
            $data_row_log = array(
                'app'           => 'DATA RFC SIMULASI CREATE PO',
                'rfc_name'      => 'Z_RFC_PAS_TESTRUN_PURCHORDER',
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
                $msg = implode(", ", $message);
            }
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        } else {
            $return = array('sts' => $sts, 'msg' => $msg);
        }
        return $return;
    }

    private function cetak_ppb($key = NULL)
    {
        $data_view['user']                 = $this->general->get_data_user();
        $data_view['module']             = $this->router->fetch_module();

        $id_ppb  = $this->generate->kirana_decrypt($key);
        $data = $this->get_data_ppb(array(
            "connect" => TRUE,
            "data" => "complete",
            "id_ppb" => $id_ppb,
            // "encrypt" => array("id")
        ));

        if (
            !$key || empty($data)
            //|| in_array($this->data['project_header']->plant, $this->access_plant) === false
        )
            show_404();

        $data_view['data'] = $data;

        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'Portrait');
        $this->pdf->filename = str_replace("-", "/", $data->no_ppb) . ".pdf";
        $this->pdf->load_view('plantation/cetak/ppb', $data_view);

        /*
        $this->load->library('Mypdf');

        $this->mypdf->AddPage('portrait', 'A4');
        $this->mypdf->SetFont('Arial', '', 10);

        // header
        // $this->mypdf->headers();
        $this->mypdf->Image(base_url() . '/assets/apps/img/logo-lg.png', 150, 10, 50);
        $this->mypdf->Cell(0, 5, $data->nama_pabrik, 0, 0, 'L');
        $this->mypdf->Ln();
        $this->mypdf->SetFont('Arial', 'BU', 14);
        $this->mypdf->Cell(0, 10, 'PERMOHONAN PEMBELIAN BARANG', 0, 1, 'C');

        // content top
        $this->mypdf->SetFont('Arial', '', 10);
        $this->mypdf->Cell(40, 5, 'NOMOR PPB', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->no_ppb, 1, 0, 'L');
        $this->mypdf->Cell(8, 5, '', 0, 0, 'L');
        $this->mypdf->Cell(40, 5, 'TANGGAL PPB', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->tanggal_format, 1, 1, 'L');
        $this->mypdf->Ln(5);

        // content bottom
        $start_x = $this->mypdf->GetX();
        $current_y = $this->mypdf->GetY();
        $current_x = $this->mypdf->GetX();

        $width = array(
            25,
            40,
            10,
            // 26, 
            13,
            12,
            15,
            15,
            22,
            36
        );

        $this->mypdf->SetFont('Arial', 'B', 7.5);

        $this->mypdf->Cell($width[0], 10, 'Kode Barang', 1, 0, 'C', false);
        $this->mypdf->Cell($width[1], 10, 'Nama Barang', 1, 0, 'C', false);
        $this->mypdf->Cell($width[2], 10, 'Satuan', 1, 0, 'C', false);
        // $this->mypdf->Cell($width[3], 10, 'Spesifikasi', 1, 0, 'C',false);
        $this->mypdf->Cell($width[3], 10, 'Tipe', 1, 0, 'C', false);
        $this->mypdf->Cell($width[4], 10, 'Stok', 1, 0, 'C', false);
        $current_x = $this->mypdf->GetX();
        $this->mypdf->Cell($width[5], 5, 'Jumlah', 'LRT', 0, 'C', false);
        $this->mypdf->Cell($width[6], 5, 'Jumlah', 'LRT', 0, 'C', false);
        $this->mypdf->Cell($width[7], 5, 'Referensi', 'LRT', 0, 'C', false);
        $this->mypdf->Cell($width[8], 5, 'Spesifikasi/', 'LRT', 0, 'C', false);

        $this->mypdf->SetXY($current_x, $this->mypdf->GetY() + 5);
        $this->mypdf->Cell($width[5], 5, 'Diminta', 'LRB', 0, 'C', false);
        $this->mypdf->Cell($width[6], 5, 'Disetujui', 'LRB', 0, 'C', false);
        $this->mypdf->Cell($width[7], 5, 'Harga', 'LRB', 0, 'C', false);
        $this->mypdf->Cell($width[8], 5, 'Keterangan', 'LRB', 1, 'C', false);

        $this->mypdf->SetFont('Arial', '', 7.5);
        $i    = 0;
        $fill = false;
        foreach ($data->detail as $dt) {
            $i++;

            $tipe = "";
            switch ($dt->classification) {
                case 'A':
                    $tipe = "Asset";
                    break;
                case 'K':
                    $tipe = "Expense";
                    break;
                case 'I':
                    $tipe = "Inventory";
                    break;
                default:
                    $tipe = "";
                    break;
            }

            $data_table = array(
                array(
                    "align" => "C",
                    "value" => $dt->kode_barang
                ), //"kode barang"
                array(
                    "align" => "L",
                    "value" => $dt->nama_barang
                ),//nama barang
                array(
                    "align" => "C",
                    "value" => $dt->satuan
                ),//"satuan"
                // array(
                //     "align" => "L",
                //     "value" => $dt->keterangan
                // ),//"spesifikasi"
                array(
                    "align" => "L",
                    "value" => $tipe
                ),//"tipe"
                array(
                    "align" => "C",
                    "value" => number_format($dt->stok, 2, ".", ",")
                ),//"stok"
                array(
                    "align" => "C",
                    "value" => number_format($dt->jumlah, 2, ".", ",")
                ),//"jumlah diminta"
                array(
                    "align" => "C",
                    "value" => number_format($dt->jumlah_disetujui, 2, ".", ",")
                ),//"jumlah disetujui"
                array(
                    "align" => "R",
                    "value" => number_format($dt->harga, 2, ".", ",")
                ),//"harga"
                array(
                    "align" => "L",
                    "value" => $dt->keterangan
                ),//"keterangan"
            );

            $this->mypdf->SetWidths($width);
            $this->mypdf->Row($data_table);
        }

        $this->mypdf->Ln(5);
        $this->mypdf->SetFont('Arial', '', 8);
        $current_y = $this->mypdf->GetY();
        $this->mypdf->Cell(18, 5, 'Catatan :', 0, 0, 'L', false);
        $this->mypdf->MultiCell(170, 5, $data->perihal, 1, 1, 0, false);

        $this->mypdf->Ln(5);
        $this->mypdf->Cell(30, 5, 'DIBUAT', 1, 0, 'C', false);
        $this->mypdf->Cell(30, 5, 'DIPERIKSA', 1, 0, 'C', false);
        $this->mypdf->Cell(128, 5, 'DISETUJUI', 1, 0, 'C', false);
        $this->mypdf->Ln();
        
        $this->mypdf->Cell(30, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(30, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(32, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(32, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(32, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(32, 15, '', 1, 0, 'C', false);
        $this->mypdf->Ln();

        $this->mypdf->Cell(30, 5, 'Asst. Gudang/Kasie', 1, 0, 'C', false);
        $this->mypdf->Cell(30, 5, 'KTU', 1, 0, 'C', false);
        $this->mypdf->Cell(32, 5, 'Estate Manager', 1, 0, 'C', false);
        $this->mypdf->Cell(32, 5, 'QAD Dept Head', 1, 0, 'C', false);
        $this->mypdf->Cell(32, 5, 'Operational Dept Head', 1, 0, 'C', false);
        $this->mypdf->Cell(32, 5, 'Direktur', 1, 0, 'C', false);
        $this->mypdf->Ln();

        $this->mypdf->Cell(30, 5, 'Nama&Tgl.', 1, 0, 'L', false);
        $this->mypdf->Cell(30, 5, 'Nama&Tgl.', 1, 0, 'L', false);
        $this->mypdf->Cell(32, 5, 'Nama&Tgl.', 1, 0, 'L', false);
        $this->mypdf->Cell(32, 5, 'Nama&Tgl.', 1, 0, 'L', false);
        $this->mypdf->Cell(32, 5, 'Nama&Tgl.', 1, 0, 'L', false);
        $this->mypdf->Cell(32, 5, 'Nama&Tgl.', 1, 0, 'L', false);
        $this->mypdf->Ln();

        $this->mypdf->SetFont('Arial', 'I', 7);
        $this->mypdf->Cell(0, 3, 'Lembar 1 untuk Gudang; Lembar 2 untuk Peminta', 0, 0, 'L');
        $this->mypdf->Ln();

        $this->mypdf->SetFont('Arial', 'I', 7);
        $this->mypdf->Cell(0, 3, 'Nama, Jabatan, Paraf, dan Tandatangan harus jelas.', 0, 0, 'L');

        $file_name = str_replace("-", "/", $data->no_ppb) . ".pdf";
        $this->mypdf->Output('I', $file_name);
        */
    }

    private function cetak_gr_ho($key = NULL)
    {
        $data_view['user']                 = $this->general->get_data_user();
        $data_view['module']             = $this->router->fetch_module();

        $id_gr  = $this->generate->kirana_decrypt($key);
        $data = $this->get_data_gr(array(
            "connect" => TRUE,
            "data" => "complete",
            "id_gr" => $id_gr,
            // "encrypt" => array("id")
        ));

        if (!$key || empty($data))
            show_404();

        $data_view['data'] = $data;

        $this->load->library('pdf');
        $this->pdf->setPaper('A5', 'Landscape');
        $this->pdf->filename = str_replace("-", "/", $data->no_gr) . ".pdf";
        $this->pdf->load_view('plantation/cetak/gr', $data_view);

        /*
        $this->load->library('Mypdf');

        $this->mypdf->AddPage('landscape', 'A5');
        $this->mypdf->SetFont('Arial', '', 10);

        // header
        // $this->mypdf->headers();
        $this->mypdf->Image(base_url() . '/assets/apps/img/logo-lg.png', 150, 10, 50);
        $this->mypdf->Cell(0, 5, $data->nama_pabrik, 0, 0, 'L');
        $this->mypdf->Ln();
        $this->mypdf->SetFont('Arial', 'BU', 14);
        $this->mypdf->Cell(0, 10, 'TANDA TERIMA GUDANG', 0, 0, 'C');

        // Line break
        $this->mypdf->Ln(10);

        // content top
        $this->mypdf->SetFont('Arial', '', 8);
        $this->mypdf->Cell(40, 5, 'NOMOR TTG', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->no_gr, 1, 0, 'L');
        $this->mypdf->Cell(8, 5, '', 0, 0, 'L');
        $this->mypdf->Cell(40, 5, 'TANGGAL', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->tanggal_format, 1, 0, 'L');
        $this->mypdf->Ln(5);

        $this->mypdf->Cell(40, 5, 'VENDOR', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->nama_vendor, 1, 0, 'L');
        $this->mypdf->Cell(8, 5, '', 0, 0, 'L');
        $this->mypdf->Cell(40, 5, 'PO', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->no_po, 1, 0, 'L');
        $this->mypdf->Ln(10);

        // content bottom
        $start_x = $this->mypdf->GetX();
        $current_y = $this->mypdf->GetY();
        $current_x = $this->mypdf->GetX();

        $width = array(
            13,
            35,
            55,
            20,
            15,
            50
        );

        $this->mypdf->SetFont('Arial', '', 7.5);
        $this->mypdf->Cell($width[0], 10, 'NO', 1, 0, 'C');
        $current_x = $this->mypdf->GetX();
        $this->mypdf->Cell($width[1] + $width[2], 5, 'BARANG', 1, 0, 'C');
        $this->mypdf->Cell($width[3] + $width[4], 5, 'JUMLAH', 1, 0, 'C');
        $this->mypdf->Cell($width[5], 10, 'KETERANGAN', 1, 0, 'C');
        $this->mypdf->SetXY($current_x, $this->mypdf->GetY() + 5);

        $this->mypdf->Cell($width[1], 5, 'KODE', 1, 0, 'C');
        $this->mypdf->Cell($width[2], 5, 'NAMA BARANG', 1, 0, 'C');
        $this->mypdf->Cell($width[3], 5, 'QTY', 1, 0, 'C');
        $this->mypdf->Cell($width[4], 5, 'SAT', 1, 1, 'C');

        $i    = 0;
        $fill = false;
        foreach ($data->detail as $dt) {
            $i++;
            $data_table = array(
                array(
                    "align" => "C",
                    "value" => $i
                ), //"no"
                array(
                    "align" => "C",
                    "value" => $dt->kode_barang
                ), //"kode barang"
                array(
                    "align" => "L",
                    "value" => $dt->nama_barang
                ), //nama barang
                array(
                    "align" => "C",
                    "value" => number_format($dt->jumlah, 2, ".", ",")
                ), //"jumlah"
                array(
                    "align" => "C",
                    "value" => $dt->satuan
                ), //"satuan"
                array(
                    "align" => "L",
                    "value" => $dt->keterangan
                ), //"keterangan"
            );

            $this->mypdf->SetWidths($width);
            $this->mypdf->Row($data_table);
        }

        $this->mypdf->Ln(5);

        $this->mypdf->SetFont('Arial', '', 8);
        // if ($this->mypdf->GetY() < 100)
        //     $this->mypdf->SetY(100);

        $this->mypdf->Cell(47, 5, 'DIBUAT OLEH', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'DIPERIKSA OLEH', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'DIKETAHUI OLEH', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'DISERAHKAN OLEH', 1, 1, 'C', false);

        $this->mypdf->Cell(47, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 15, '', 1, 1, 'C', false);

        $this->mypdf->Cell(47, 5, 'STAFF GUDANG', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'KTU', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'ESTATE MANAGER', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, '', 1, 1, 'C', false);

        $file_name = str_replace("-", "/", $data->no_gr) . ".pdf";
        $this->mypdf->Output('I', $file_name);
        */
    }

    private function cetak_gr_site($key = NULL)
    {
        $data_view['user']                 = $this->general->get_data_user();
        $data_view['module']             = $this->router->fetch_module();

        $id_gr  = $this->generate->kirana_decrypt($key);
        $data_gr = $this->get_data_gr(array(
            "connect" => TRUE,
            "data" => "header",
            "id_gr" => $id_gr,
        ));

        $data = $this->get_data_po(array(
            "connect" => TRUE,
            "data" => "complete",
            "id_po" => $data_gr->id_po,
        ));

        if (!$key || empty($data))
            show_404();

        $data_view['data_gr'] = $data_gr;
        $data_view['data'] = $data;

        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'Portrait');
        $this->pdf->filename = str_replace("-", "/", $data_gr->no_gr) . ".pdf";
        $this->pdf->load_view('plantation/cetak/grsite', $data_view);
        /*
        $this->load->library('Mypdf');

        $this->mypdf->AddPage('portrait', 'A4');
        $this->mypdf->SetFont('Arial', '', 10);

        // header
        // $this->mypdf->headers();
        $this->mypdf->Image(base_url() . '/assets/apps/img/logo-lg.png', 150, 10, 50);
        $this->mypdf->Cell(0, 5, $data->nama_pabrik, 0, 0, 'L');
        $this->mypdf->Ln();
        $this->mypdf->SetFont('Arial', 'BU', 14);
        $this->mypdf->Cell(0, 10, 'PURCHASE ORDER & TANDA TERIMA GUDANG', 0, 0, 'C');

        // Line break
        $this->mypdf->Ln(11);

        // content top
        $this->mypdf->SetFont('Arial', '', 9);
        $this->mypdf->Cell(35, 5, 'NO PO', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(50, 5, $data->no_po, 1, 0, 'L');
        $this->mypdf->Cell(8, 5, '', 0, 0, 'L');
        $this->mypdf->Cell(35, 5, 'TANGGAL', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(50, 5, $data_gr->tanggal_format, 1, 0, 'L');
        $this->mypdf->Ln(5);

        $this->mypdf->Cell(35, 5, 'VENDOR', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(50, 5, $data_gr->nama_vendor, 1, 0, 'L');
        $this->mypdf->Cell(8, 5, '', 0, 0, 'L');
        $this->mypdf->Cell(35, 5, 'NO TTG', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(50, 5, $data_gr->no_gr, 1, 0, 'L');
        $this->mypdf->Ln(10);

        $width = array(
            10,
            25,
            45,
            16,
            12,
            25,
            25,
            30
        );

        $this->mypdf->SetFont('Arial', '', 7.5);
        $this->mypdf->Cell($width[0], 10, 'NO', 1, 0, 'C');
        $current_x = $this->mypdf->GetX();
        $this->mypdf->Cell($width[1] + $width[2], 5, 'BARANG', 1, 0, 'C');
        $this->mypdf->Cell($width[3] + $width[4], 5, 'JUMLAH', 1, 0, 'C');
        $this->mypdf->Cell($width[5] + $width[6], 5, 'HARGA', 1, 0, 'C');
        $this->mypdf->Cell($width[7], 10, 'KETERANGAN', 1, 0, 'C');
        $this->mypdf->SetXY($current_x, $this->mypdf->GetY() + 5);

        $this->mypdf->Cell($width[1], 5, 'KODE', 1, 0, 'C');
        $this->mypdf->Cell($width[2], 5, 'NAMA BARANG', 1, 0, 'C');
        $this->mypdf->Cell($width[3], 5, 'QTY', 1, 0, 'C');
        $this->mypdf->Cell($width[4], 5, 'SAT', 1, 0, 'C');
        $this->mypdf->Cell($width[5], 5, 'SATUAN', 1, 0, 'C');
        $this->mypdf->Cell($width[6], 5, 'TOTAL', 1, 1, 'C');

        $i    = 0;
        $subtotal = 0;
        $total_diskon = 0;
        $fill = false;
        foreach ($data->detail as $dt) {
            $i++;
            $total = ($dt->jumlah * $dt->harga);
            $subtotal += $total;
            $total_diskon += $dt->diskon;

            $data_table = array(
                array(
                    "align" => "C",
                    "value" => $i
                ),//"no"
                array(
                    "align" => "C",
                    "value" => $dt->kode_barang
                ), //"kode barang"
                array(
                    "align" => "L",
                    "value" => $dt->nama_barang
                ),//nama barang
                array(
                    "align" => "R",
                    "value" => number_format($dt->jumlah, 2, '.', ',')
                ),//"jumlah"
                array(
                    "align" => "C",
                    "value" => $dt->satuan
                ),//"satuan"
                array(
                    "align" => "R",
                    "value" => number_format($dt->harga, 2, '.', ',')
                ),//"harga satuan"
                array(
                    "align" => "R",
                    "value" => number_format($total, 2, '.', ',')
                ),//"harga total"
                array(
                    "align" => "L",
                    "value" => $dt->keterangan
                ),//"keterangan"
            );

            $this->mypdf->SetWidths($width);
            $this->mypdf->Row($data_table);
        }

        $ppn = 0;
        if ($data->ppn == "B5") {
            $ppn = ($subtotal - $total_diskon) * $data->nilai_ppn / 100;
        }

        $total = $subtotal - $total_diskon + $ppn;

        $this->mypdf->Cell($width[0] + $width[1] + $width[2] + $width[3] + $width[4], 5, '', 0, 0, 'C');
        $this->mypdf->Cell($width[5], 5, 'SUBTOTAL', 0, 0, 'L');
        $this->mypdf->Cell($width[6], 5, number_format($subtotal, 2, '.', ','), 1, 1, 'R');

        $this->mypdf->Cell($width[0] + $width[1] + $width[2] + $width[3] + $width[4], 5, '', 0, 0, 'C');
        $this->mypdf->Cell($width[5], 5, 'DISKON', 0, 0, 'L');
        $this->mypdf->Cell($width[6], 5, number_format($total_diskon, 2, '.', ','), 1, 1, 'R');

        $this->mypdf->Cell($width[0] + $width[1] + $width[2] + $width[3] + $width[4], 5, '', 0, 0, 'C');
        $this->mypdf->Cell($width[5], 5, 'PPN', 0, 0, 'L');
        $this->mypdf->Cell($width[6], 5, number_format($ppn, 2, '.', ','), 1, 1, 'R');

        $this->mypdf->Cell($width[0] + $width[1] + $width[2] + $width[3] + $width[4], 5, '', 0, 0, 'C');
        $this->mypdf->Cell($width[5], 5, 'TOTAL', 0, 0, 'L');
        $this->mypdf->Cell($width[6], 5, number_format($total, 2, '.', ','), 1, 1, 'R');

        $this->mypdf->Ln(5);

        $this->mypdf->SetFont('Arial', '', 8);
        // if ($this->mypdf->GetY() < 250)
        //     $this->mypdf->SetY(250);

        $this->mypdf->Cell(47, 5, 'DIBUAT OLEH', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'DIPERIKSA OLEH', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'DIKETAHUI OLEH', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'DISERAHKAN OLEH', 1, 1, 'C', false);
        
        $this->mypdf->Cell(47, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 15, '', 1, 1, 'C', false);

        $this->mypdf->Cell(47, 5, 'STAFF GUDANG', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'KTU', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'ESTATE MANAGER', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, '', 1, 1, 'C', false);

        $file_name = str_replace("-", "/", $data_gr->no_gr) . ".pdf";
        $this->mypdf->Output('I', $file_name);
        */
    }

    private function cetak_gi($key = NULL)
    {
        $data_view['user']                 = $this->general->get_data_user();
        $data_view['module']             = $this->router->fetch_module();

        $id_gi  = $this->generate->kirana_decrypt($key);
        $data = $this->get_data_gi(array(
            "connect" => TRUE,
            "data" => "complete",
            "id_gi" => $id_gi,
        ));

        if (!$key || empty($data))
            show_404();

        $data_view['data'] = $data;

        $this->load->library('pdf');
        $this->pdf->setPaper('A5', 'Landscape');
        $this->pdf->filename = str_replace("-", "/", $data->no_gi) . ".pdf";
        $this->pdf->load_view('plantation/cetak/gi', $data_view);

        /*
        $this->load->library('Mypdf');

        $this->mypdf->AddPage('landscape', 'A5');
        $this->mypdf->SetFont('Arial', '', 10);

        // header
        // $this->mypdf->headers();
        $this->mypdf->Image(base_url() . '/assets/apps/img/logo-lg.png', 150, 10, 50);
        $this->mypdf->Cell(0, 5, $data->nama_pabrik, 0, 0, 'L');
        $this->mypdf->Ln();
        $this->mypdf->SetFont('Arial', 'BU', 14);
        $this->mypdf->Cell(0, 10, 'BUKTI KELUAR BARANG', 0, 0, 'C');

        // Line break
        $this->mypdf->Ln(10);

        // content top
        $this->mypdf->SetFont('Arial', '', 10);
        $this->mypdf->Cell(40, 5, 'NO BKB', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->no_gi, 1, 0, 'L');
        $this->mypdf->Cell(8, 5, '', 0, 0, 'L');
        $this->mypdf->Cell(40, 5, 'NO SPB', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->no_spb, 1, 0, 'L');
        $this->mypdf->Ln(5);

        $this->mypdf->SetFont('Arial', '', 10);
        $this->mypdf->Cell(40, 5, 'TANGGAL', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->tanggal_format, 1, 0, 'L');
        $this->mypdf->Cell(8, 5, '', 0, 0, 'L');
        $this->mypdf->Cell(40, 5, 'AFD', 0, 0, 'L');
        $this->mypdf->Cell(5, 5, ': ', 0, 0, 'L');
        $this->mypdf->Cell(45, 5, $data->afd, 1, 0, 'L');
        $this->mypdf->Ln(10);

        // content bottom
        $start_x = $this->mypdf->GetX();
        $current_y = $this->mypdf->GetY();
        $current_x = $this->mypdf->GetX();

        $width = array(
            8,
            22,
            30,
            18,
            18,
            18,
            14,
            12,
            15,
            15,
            18
        );

        $this->mypdf->SetFont('Arial', '', 6.5);
        $this->mypdf->Cell($width[0], 10, 'NO', 1, 0, 'C');
        $current_x = $this->mypdf->GetX();
        $this->mypdf->Cell($width[1] + $width[2], 5, 'BARANG', 1, 0, 'C');
        $this->mypdf->Cell($width[3], 10, 'COA', 1, 0, 'C');
        $this->mypdf->Cell($width[4], 10, 'COST CENTER', 1, 0, 'C');
        $this->mypdf->Cell($width[5], 10, 'TBM/CIP', 1, 0, 'C');
        $this->mypdf->Cell($width[6] + $width[7], 5, 'BKB', 1, 0, 'C');
        $this->mypdf->Cell($width[8], 10, 'BLOK', 1, 0, 'C');
        $this->mypdf->Cell($width[9], 10, 'KODE VRA', 1, 0, 'C');
        $this->mypdf->Cell($width[10], 10, 'KETERANGAN', 1, 0, 'C');
        $this->mypdf->SetXY($current_x, $this->mypdf->GetY() + 5);

        $this->mypdf->Cell($width[1], 5, 'KODE', 1, 0, 'C');
        $this->mypdf->Cell($width[2], 5, 'NAMA BARANG', 1, 0, 'C');
        $current_x = $this->mypdf->GetX();
        $this->mypdf->SetX($current_x + $width[3] + $width[4] + $width[5]);
        $this->mypdf->Cell($width[6], 5, 'QTY', 1, 0, 'C');
        $this->mypdf->Cell($width[7], 5, 'SAT', 1, 1, 'C');

        // $this->mypdf->SetFont('Arial', '', 7);
        $i    = 0;
        $fill = false;
        foreach ($data->detail as $dt) {
            $i++;

            $data_table = array(
                array(
                    "align" => "C",
                    "value" => $i
                ), //"no"
                array(
                    "align" => "C",
                    "value" => $dt->kode_barang
                ), //"kode barang"
                array(
                    "align" => "L",
                    "value" => $dt->nama_barang
                ), //nama barang
                array(
                    "align" => "L",
                    "value" => $dt->gl_account
                ), //coa
                array(
                    "align" => "L",
                    "value" => $dt->cost_center
                ), //"cost center"
                array(
                    "align" => "L",
                    "value" => $dt->no_io
                ), //"tbm"
                array(
                    "align" => "C",
                    "value" => number_format($dt->jumlah, 2, ".", ",")
                ), //"jumlah"
                array(
                    "align" => "C",
                    "value" => $dt->satuan
                ), //"satuan"
                array(
                    "align" => "L",
                    "value" => $dt->blok
                ), //"blok"
                array(
                    "align" => "L",
                    "value" => $dt->kode_vra
                ), //"no pol"
                array(
                    "align" => "L",
                    "value" => $dt->keterangan
                ), //"keterangan"
            );

            $this->mypdf->SetWidths($width);
            $this->mypdf->Row($data_table);
        }

        $this->mypdf->Ln(5);
        $this->mypdf->SetFont('Arial', '', 8);
        // if ($this->mypdf->GetY() < 100)
        //     $this->mypdf->SetY(100);

        $this->mypdf->Cell(47, 5, 'DIBUAT OLEH,', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'DIPERIKSA OLEH,', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'DISETUJUI OLEH,', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'DITERIMA OLEH,', 1, 1, 'C', false);

        $this->mypdf->Cell(47, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 15, '', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 15, '', 1, 1, 'C', false);

        $this->mypdf->Cell(47, 5, 'STAFF GUDANG', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'KTU', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'ESTATE MANAGER', 1, 0, 'C', false);
        $this->mypdf->Cell(47, 5, 'USER', 1, 1, 'C', false);

        $file_name = str_replace("-", "/", $data->no_gi) . ".pdf";
        $this->mypdf->Output('I', $file_name);
        */
    }

    private function validasi_tanggal($date, $format = "d.m.Y")
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
