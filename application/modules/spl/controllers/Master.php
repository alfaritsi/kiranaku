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

class Master extends MX_Controller
{
    private $data;

    function __construct()
    {
        parent::__construct();
        $this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));

        $this->data['generate'] = $this->generate;
        $this->data['module'] = $this->router->fetch_module();
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dmaster');
    }

    public function index()
    {
        show_404();
    }

    public function plan()
    {
        //====must be initiate in every view function====/
        // $this->general->check_access();
        //===============================================/
        $this->data['pabrik'] = $this->general->get_master_plant();
        $this->load->view("master/plan", $this->data);
    }

    //==================================================//
    /*                    Get data                      */
    //==================================================//
    public function get($param = NULL, $param2 = NULL)
    {
        switch ($param) {
            case 'departemen':
                $post = $this->input->post_get(NULL, TRUE);
                $param_ = array(
                    "connect" => TRUE,
                    "return" => @$post['return'],
                    "id_departemen" => (isset($post['id_departemen'])) ? $this->generate->kirana_decrypt($post['id_departemen']) : NULL,
                    "plant" => (isset($post['plant'])) ? $this->generate->kirana_decrypt($post['plant']) : NULL,
                    "all" => @$post['all'],
                    "search" => @$post['search'],
                    "encrypt" => array("id", "id_departemen")
                );

                $this->get_data_departemen($param_);
                break;
            case 'seksie':
                $post = $this->input->post(NULL, TRUE);
                $param_ = array(
                    "connect" => TRUE,
                    "return" => @$post['return'],
                    "id_departemen" => (isset($post['id_departemen'])) ? $this->generate->kirana_decrypt($post['id_departemen']) : NULL,
                    "id_seksie" => (isset($post['id_seksie'])) ? $this->generate->kirana_decrypt($post['id_seksie']) : NULL,
                    "plant" => (isset($post['plant'])) ? $post['plant'] : NULL,
                    "all" => @$post['all'],
                    "search" => @$post['search'],
                    "encrypt" => array("id", "id_departemen")
                );

                $this->get_data_seksie($param_);
                break;
            case 'unit':
                $post = $this->input->post(NULL, TRUE);
                $param_ = array(
                    "connect" => TRUE,
                    "return" => @$post['return'],
                    "id_departemen" => (isset($post['id_departemen'])) ? $this->generate->kirana_decrypt($post['id_departemen']) : NULL,
                    "id_seksie" => (isset($post['id_seksie'])) ? $this->generate->kirana_decrypt($post['id_seksie']) : NULL,
                    "id_unit" => (isset($post['id_unit'])) ? $this->generate->kirana_decrypt($post['id_unit']) : NULL,
                    "plant" => (isset($post['plant'])) ? $post['plant'] : NULL,
                    "all" => @$post['all'],
                    "search" => @$post['search'],
                    "encrypt" => array("id", "id_seksi")
                );

                $this->get_data_unit($param_);
                break;
            case 'keterangan_lembur':
                $post = $this->input->post(NULL, TRUE);
                $param_ = array(
                    "connect" => TRUE,
                    "all" => @$post['all'],
                    "search" => @$post['search'],
                );
                $return = $this->dmaster->get_data_keterangan_lembur($param_);
                echo json_encode($return);
                break;
            case 'data':
                $filter_bulan = (isset($_POST['filter_bulan']) ? $_POST['filter_bulan'] : NULL);
                //filter pabrik
                if (isset($_POST['filter_pabrik'])) {
                    $filter_pabrik = array();
                    foreach ($_POST['filter_pabrik'] as $dt) {
                        array_push($filter_pabrik, $dt);
                    }
                } else {
                    $filter_pabrik  = NULL;
                }
                //filter unit
                if (isset($_POST['filter_unit'])) {
                    $filter_unit = array();
                    foreach ($_POST['filter_unit'] as $dt) {
                        array_push($filter_unit, $dt);
                    }
                } else {
                    $filter_unit  = NULL;
                }
                if ($param2 == 'bom') {
                    header('Content-Type: application/json');
                    $return = $this->dmaster->get_data_mpl_bom('open', $filter_pabrik, $filter_bulan, $filter_unit);
                    echo $return;
                    break;
                } else {
                    $post = $this->input->post(NULL, TRUE);
                    $param_ = array(
                        "connect" => TRUE,
                        "return" => @$post['return'],
                        "id_departemen" => (isset($post['id_departemen'])) ? $this->generate->kirana_decrypt($post['id_departemen']) : NULL,
                        "id_seksi" => (isset($post['id_seksie'])) ? $this->generate->kirana_decrypt($post['id_seksie']) : NULL,
                        "plant" => (isset($post['plant'])) ? $post['plant'] : NULL,
                        "tanggal_spl" => (isset($post['tanggal_spl']) ? $this->generate->regenerateDateFormat($post['tanggal_spl']) : NULL),
                        "is_lembur" => @$post['is_lembur'],
                        "search" => @$post['search'],
                        "encrypt" => array("id_seksie", "id_departemen")
                    );

                    $this->get_data_master_plan($param_);
                    break;
                }
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
            case 'tahap':
                $return = $this->set_tahap();
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
            case 'tahap':
                $this->save_tahap();
                break;
            case 'excel':
                $this->save_excel($param);
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
    private function get_data_departemen($param = NULL)
    {
        $result = $this->dmaster->get_data_departemen($param);

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

    private function get_data_seksie($param = NULL)
    {
        $result = $this->dmaster->get_data_seksie($param);

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

    private function get_data_unit($param = NULL)
    {
        $result = $this->dmaster->get_data_unit($param);

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

    private function get_data_master_plan($param = NULL)
    {
        $result = $this->dmaster->get_data_mpl($param);

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

    private function save_excel($param)
    {
        $datetime       = date("Y-m-d H:i:s");
        $post             = $this->input->post(NULL, TRUE);
        $pabrik            = $post['pabrik'];
        $bulan_tahun  = $post['bulan_tahun'];
        $bulan          = substr($bulan_tahun, 0, 2);
        $tahun          = substr($bulan_tahun, 3, 4);
        $maks_tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $this->db->query("update tbl_spl_master_plan set na='y', del='y' where plant='$pabrik' and RIGHT('00' + CAST(MONTH(tanggal) as varchar(2)),2)  + '.' + CAST(YEAR(tanggal) as char(4)) = '$bulan_tahun'");

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        if (!empty($_FILES['file_excel']['name'])) {

            $target_dir = "./assets/temp";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            $config['upload_path']          = $target_dir;
            $config['allowed_types']        = 'xls|xlsx';

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file_excel')) {
                $msg     = $this->upload->display_errors();
                $sts     = "NotOK";
            } else {
                $data             = array('upload_data' => $this->upload->data());
                $objPHPExcel     = PHPExcel_IOFactory::load($data['upload_data']['full_path']);
                $title_desc        = $objPHPExcel->getProperties()->getTitle();
                $objPHPExcel->setActiveSheetIndex(0);
                $data_excel        = $objPHPExcel->getActiveSheet();
                $highestRow     = $data_excel->getHighestRow();
                $highestColumn     = PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn());
                $datetime        = date("Y-m-d H:i:s");
                if ($data['upload_data']['orig_name'] != 'template_upload_data_spl.xlsx') {
                    $msg    = "Template File Yang diupload, tidak sesuai.";
                    $sts    = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                    exit();
                }
                $jumlah_kolom_tanggal = ($highestColumn - 6) / 2;
                // echo json_encode($jumlah_kolom_tanggal);
                // exit;

                if ($jumlah_kolom_tanggal > $maks_tanggal) {
                    $msg    = "Jumlah hari dalam kolom excel tidak boleh lebih dari " . $maks_tanggal . ", mohon cek kembali jumlah hari pada file yang diupload.";
                    $sts    = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                    exit();
                }

                //input data
                $data_rows        = array();
                $tanggal        = 0;
                for ($kolom = 4; $kolom <= $highestColumn; $kolom++) {
                    if ($kolom % 2 == 0) {
                        $tanggal++;
                        $tanggal_format    = $tahun . '-' . $bulan . '-' . str_pad($tanggal, 2, '0', STR_PAD_LEFT);
                        //=======
                        //milling
                        //=======
                        $ck_data     = $this->dmaster->get_data_detail('open', $pabrik, 'milling');
                        //milling shift 1
                        $jam_milling_normal1    = $data_excel->getCellByColumnAndRow($kolom, 6)->getValue();
                        $jam_milling_shift1        = $data_excel->getCellByColumnAndRow($kolom, 7)->getValue();
                        if ((($jam_milling_shift1 != NULL) or ($jam_milling_shift1 != 0)) && ($tanggal <= $maks_tanggal)) {
                            $data_row    = array(
                                'plant'            => $pabrik,
                                'id_departemen'    => $ck_data[0]->id_departemen,
                                'id_seksie'        => $ck_data[0]->id_seksi,
                                'id_unit'        => $ck_data[0]->id_unit,
                                'tanggal'         => $tanggal_format,
                                'shift'            => 1,
                                'jumlah_jam_lembur'    => $jam_milling_shift1,
                                'jumlah_jam_normal'    => $jam_milling_normal1,
                            );
                            $data_row = $this->dgeneral->basic_column('insert_full', $data_row, $datetime);
                            $data_rows[] = $data_row;
                        }
                        //milling shift 2
                        $jam_milling_normal2    = $data_excel->getCellByColumnAndRow($kolom, 8)->getValue();
                        $jam_milling_shift2        = $data_excel->getCellByColumnAndRow($kolom, 9)->getValue();
                        if ((($jam_milling_shift2 != NULL) or ($jam_milling_shift2 != 0)) && ($tanggal <= $maks_tanggal)) {
                            $data_row    = array(
                                'plant'            => $pabrik,
                                'id_departemen'    => $ck_data[0]->id_departemen,
                                'id_seksie'        => $ck_data[0]->id_seksi,
                                'id_unit'        => $ck_data[0]->id_unit,
                                'tanggal'         => $tanggal_format,
                                'shift'            => 2,
                                'jumlah_jam_lembur'    => $jam_milling_shift2,
                                'jumlah_jam_normal'    => $jam_milling_normal2,
                            );
                            $data_row = $this->dgeneral->basic_column('insert_full', $data_row, $datetime);
                            $data_rows[] = $data_row;
                        }
                        //milling shift 3
                        $jam_milling_normal3    = $data_excel->getCellByColumnAndRow($kolom, 10)->getValue();
                        $jam_milling_shift3        = $data_excel->getCellByColumnAndRow($kolom, 11)->getValue();
                        if ((($jam_milling_shift3 != NULL) or ($jam_milling_shift3 != 0)) && ($tanggal <= $maks_tanggal)) {
                            $data_row    = array(
                                'plant'            => $pabrik,
                                'id_departemen'    => $ck_data[0]->id_departemen,
                                'id_seksie'        => $ck_data[0]->id_seksi,
                                'id_unit'        => $ck_data[0]->id_unit,
                                'tanggal'         => $tanggal_format,
                                'shift'            => 3,
                                'jumlah_jam_lembur'    => $jam_milling_shift3,
                                'jumlah_jam_normal'    => $jam_milling_normal3,
                            );
                            $data_row = $this->dgeneral->basic_column('insert_full', $data_row, $datetime);
                            $data_rows[] = $data_row;
                        }

                        //=======
                        //crumbing
                        //=======
                        $ck_data     = $this->dmaster->get_data_detail('open', $pabrik, 'crumbing');
                        //crumbing shift 1
                        $jam_crumbing_normal1    = $data_excel->getCellByColumnAndRow($kolom, 19)->getValue();
                        $jam_crumbing_shift1    = $data_excel->getCellByColumnAndRow($kolom, 20)->getValue();
                        if ((($jam_crumbing_shift1 != NULL) or ($jam_crumbing_shift1 != 0)) && ($tanggal <= $maks_tanggal)) {
                            $data_row    = array(
                                'plant'            => $pabrik,
                                'id_departemen'    => $ck_data[0]->id_departemen,
                                'id_seksie'        => $ck_data[0]->id_seksi,
                                'id_unit'        => $ck_data[0]->id_unit,
                                'tanggal'         => $tanggal_format,
                                'shift'            => 1,
                                'jumlah_jam_lembur'    => $jam_crumbing_shift1,
                                'jumlah_jam_normal'    => $jam_crumbing_normal1,
                            );
                            $data_row = $this->dgeneral->basic_column('insert_full', $data_row, $datetime);
                            $data_rows[] = $data_row;
                        }
                        //crumbing shift 2
                        $jam_crumbing_normal2    = $data_excel->getCellByColumnAndRow($kolom, 21)->getValue();
                        $jam_crumbing_shift2    = $data_excel->getCellByColumnAndRow($kolom, 22)->getValue();
                        if ((($jam_crumbing_shift2 != NULL) or ($jam_crumbing_shift2 != 0)) && ($tanggal <= $maks_tanggal)) {
                            $data_row    = array(
                                'plant'            => $pabrik,
                                'id_departemen'    => $ck_data[0]->id_departemen,
                                'id_seksie'        => $ck_data[0]->id_seksi,
                                'id_unit'        => $ck_data[0]->id_unit,
                                'tanggal'         => $tanggal_format,
                                'shift'            => 2,
                                'jumlah_jam_lembur'    => $jam_crumbing_shift2,
                                'jumlah_jam_normal'    => $jam_crumbing_normal2,
                            );
                            $data_row = $this->dgeneral->basic_column('insert_full', $data_row, $datetime);
                            $data_rows[] = $data_row;
                        }
                        //crumbing shift 3
                        $jam_crumbing_normal3    = $data_excel->getCellByColumnAndRow($kolom, 23)->getValue();
                        $jam_crumbing_shift3    = $data_excel->getCellByColumnAndRow($kolom, 24)->getValue();
                        if ((($jam_crumbing_shift3 != NULL) or ($jam_crumbing_shift3 != 0)) && ($tanggal <= $maks_tanggal)) {
                            $data_row    = array(
                                'plant'            => $pabrik,
                                'id_departemen'    => $ck_data[0]->id_departemen,
                                'id_seksie'        => $ck_data[0]->id_seksi,
                                'id_unit'        => $ck_data[0]->id_unit,
                                'tanggal'         => $tanggal_format,
                                'shift'            => 3,
                                'jumlah_jam_lembur'    => $jam_crumbing_shift3,
                                'jumlah_jam_normal'    => $jam_crumbing_normal3,
                            );
                            $data_row = $this->dgeneral->basic_column('insert_full', $data_row, $datetime);
                            $data_rows[] = $data_row;
                        }
                    }
                }
                $this->dgeneral->insert_batch('tbl_spl_master_plan', $data_rows);
                // exit;
                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg     = "Periksa kembali data yang diunggah";
                    $sts     = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg     = "Data berhasil ditambahkan";
                    $sts     = "OK";
                }

                @unlink($data['upload_data']['full_path']);
            }
        } else {
            $msg     = "Silahkan pilih file yang ingin diunggah";
            $sts     = "NotOK";
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }
}
