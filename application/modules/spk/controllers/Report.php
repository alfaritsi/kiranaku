<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Report - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. Benazi S. Bahari (10183) 02.12.2021
 * tambah laporan SLA
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Report extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Report";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dmaster');
        $this->load->model('dspk');
        $this->load->model('dreport');

        $nik    = base64_decode($this->session->userdata("-nik-"));
        $posst  = base64_decode($this->session->userdata("-posst-"));
        $this->data['role_user'] = $this->dspk->get_data_role_user(array(
            "connect" => true,
            "nik" => $nik,
            "posst" => $posst,
        ));

        $level_user = array();
        foreach ($this->data['role_user'] as $dt) {
            if ($dt->level) $level_user[] = $dt->level;
        }
        $this->data['level_user'] = $level_user;
    }

    public function dokumen()
    {
        $this->general->check_access();

        $this->data['title'] = "Laporan Dokumen Perjanjian";

        // $this->general->connectDbPortal();

        // $leg_level_id = base64_decode($_SESSION['-leg_level_id-']);

        $filter = $this->input->post();

        // echo json_encode($filter);
        // exit();

        $tanggal_awal = date('Y-m-d', strtotime('-3 months'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = $this->generate->regenerateDateFormat($filter['tanggal_awal']);

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = $this->generate->regenerateDateFormat($filter['tanggal_akhir']);

        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;

        $plant = (isset($filter['plant']) and !empty($filter['plant'])) ? $filter['plant'] : null;

        $this->data['plant_selected'] = $plant;

        /*
        if ($leg_level_id == 2) {
            if(!isset($id_plant))
            {
                $plant = $this->general->get_master_plant(array($this->data['user']->gsber), null, null, 'ERP');
                $id_plant = $plant[0]->id_pabrik;
            }
            $list = $this->dspk->get_spk(
                array(
                    // 'id_status' => 12,
                    'tanggal_berlaku_spk' => array($tanggal_awal, $tanggal_akhir),
                    'id_plant' => $id_plant,
                    'nik' => $this->data['user']->nik
                )
            );
        } else {
            $list = $this->dspk->get_spk(
                array(
                    // 'id_status' => 12,
                    'tanggal_berlaku_spk' => array($tanggal_awal, $tanggal_akhir),
                    'id_plant' => $id_plant
                )
            );
        }

        foreach ($list as $spk) {
            $divisis = $this->dspk->get_spk_divisi(
                array(
                    'id_spk' => $spk->id_spk
                )
            );

            $spk->id_spk = $this->generate->kirana_encrypt($spk->id_spk);

            $spk->table_divisi = $this->get_spk_table_divisi($spk->id_spk, $divisis);
        }

        $this->data['list'] = $list;
        */

        $this->data['list'] = $this->dspk->get_data_spk(array(
            "connect" => TRUE,
            "data" => "header",
            "plant" => $plant,
            "user_level" => $this->data['level_user'],
            "tanggal_perjanjian_awal" => $tanggal_awal,
            "tanggal_perjanjian_akhir" => $tanggal_akhir,
            "encrypt" => array("id_spk"),
        ));

        // $plants = $this->dgeneral->get_master_plant();
        $plants = $this->dgeneral->get_master_plant(null, null, null, 'ERP');

        $this->data['plants'] = $plants;

        // echo json_encode($this->data);
        // exit();

        $this->load->view('reports/reportdokumen', $this->data);
    }


    private function get_spk_table_divisi($id_spk = null, $divisis = array())
    {
        return $this->load->view('transaction/includes/table_divisi', compact('divisis', 'id_spk'), true);
    }

    //add Benazi
    public function sla($param = NULL)
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        //===============================================/

        $this->data['title'] = "Laporan SLA Perjanjian";
        // $this->data['pabrik'] = $this->dgeneral->get_datas_plant(
        //     array(
        //         "connect" => TRUE,
        //         "nik" => base64_decode($this->session->userdata('-nik-'))
        //     )
        // );
        // $this->data['tahun_select'] = date('Y');
        // $this->data['tahun'] = $this->dordermain->get_pi_header_tahun(
        //     array(
        //         "connect" => TRUE
        //     )
        // );
        $this->data['tanggal_awal'] = date('Y-m-d', strtotime('-1 months'));
        $this->data['tanggal_akhir'] = date('Y-m-d');
        $this->data['akses_plant'] = (isset($this->data['role_user'][0]->pabrik)) ? explode(",", $this->data['role_user'][0]->pabrik) : $this->data['user']->gsber;
        $this->data['param'] = $param;
        $this->load->view("reports/sla", $this->data);
    }

    //==================================================//
    /*                    Get data                      */
    //==================================================//
    public function get($param = NULL)
    {
        switch ($param) {
            case 'sla':
                $in_plant = (isset($this->data['role_user'][0]->pabrik)) ? explode(",", $this->data['role_user'][0]->pabrik) : $this->data['user']->gsber;

                $param_sla = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "tanggal_perjanjian_awal" => (isset($_POST['tanggal_perjanjian_awal']) && $_POST['tanggal_perjanjian_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_perjanjian_awal']) : NULL,
                    "tanggal_perjanjian_akhir" => (isset($_POST['tanggal_perjanjian_akhir']) && $_POST['tanggal_perjanjian_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_perjanjian_akhir']) : NULL,
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE),
                );

                $this->get_data_sla($param_sla);
                break;

            case 'export':
                $in_plant = (isset($this->data['role_user'][0]->pabrik)) ? explode(",", $this->data['role_user'][0]->pabrik) : $this->data['user']->gsber;
                $param_spk = array(
                    "connect" => TRUE,
                    "tanggal_perjanjian_awal" => $this->input->get_post('tgl_awal') ? $this->generate->regenerateDateFormat($this->input->get_post('tgl_awal')) : NULL,
                    "tanggal_perjanjian_akhir" => $this->input->get_post('tgl_akhir') ? $this->generate->regenerateDateFormat($this->input->get_post('tgl_akhir')) : NULL,
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE),
                    "user_level" => $this->data['level_user'],
                    "return" => "array"
                );
                $data = $this->dspk->get_data_spk($param_spk);
                $this->excel(
                    array(
                        "data" => $data,
                        "post" => $param_spk
                    )
                );
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
    private function get_data_sla($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dreportmain->get_data_sla($param);
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {
                                    $newData[] = $val;
                                }
                            } else {
                                $newData = $data;
                            }
                            $newResult[$key] = $newData;
                        }

                        $result = $newResult;
                        if (isset($param['return']) && $param['return'] == "datatables")
                            $result = $this->general->jsonify($newResult);
                    }
                }
                break;
            default:
                $result = $this->dreport->get_data_sla($param);
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

    private function excel($param = NULL)
    {
        if ($param['data']) {
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=SPK.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            $output = "
                <table border='1'>
                    <tr>
                        <th>Pabrik</th>
                        <th>Jenis Perjanjian</th>
                        <th>Nomor Perjanjian</th>
                        <th>Perihal</th>
                        <th>Tanggal Perjanjian</th>
                        <th>Tanggal Berlaku Perjanjian</th>
                        <th>Tanggal Berakhir Perjanjian</th>
                        <th>Vendor</th>
                        <th>Status</th>
                    </tr>
            ";
            foreach ($param['data'] as $dt) {
                $output .= "<tr>";
                $output .= "    <td>" . $dt->plant . "</td>";
                $output .= "    <td>" . $dt->jenis_spk . "</td>";
                $output .= "    <td>" . $dt->nomor_spk . "</td>";
                $output .= "    <td>" . $dt->perihal . "</td>";
                $output .= "    <td>" . $dt->tanggal_perjanjian_format . "</td>";
                $output .= "    <td>" . $dt->tanggal_berlaku_format . "</td>";
                $output .= "    <td>" . $dt->tanggal_berakhir_format . "</td>";
                $output .= "    <td>" . $dt->nama_vendor . "</td>";
                $output .= "    <td>" . (in_array($dt->status, array("confirmed", "finaldraft", "completed", "drop", "cancelled")) ? strtoupper($dt->status) : "ON PROGRESS") . ($dt->status_spk && $dt->status_spk !== "" ? " - " . rtrim($dt->status_spk, ',') : "") . "</td>";
                $output .= "</tr>";
            }
            $output .= `
                </table>
            `;

            echo $output;
        }
    }
}
