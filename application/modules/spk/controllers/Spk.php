<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Transaction - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. Lukman Hakim (7143) 28.03.2019
 * CR#1883 -> http://10.0.0.18/home/pdfviewer.php?q=crpdf/cr/CR_1883.pdf&n=CR_1883.pdf
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Spk extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Manage Perjanjian";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dmaster');
        $this->load->model('dspk');
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

    public function test($key = NULL, $nik = NULL)
    {
        if ($nik == '8347') {
            $id_spk  = $this->generate->kirana_decrypt($key);
            $data['data_spk'] = $this->get_data_spk(array(
                "connect" => TRUE,
                "data" => "complete",
                "id_spk" => $id_spk,
                "user_level" => $this->data['level_user'],
                "encrypt" => array("id_spk")
            ));

            echo json_encode($data['data_spk']);
            exit();
        } else {
            show_404();
        }
    }

    public function manage()
    {
        $this->general->check_access();

        $this->data['title'] = "Manage Perjanjian";

        $this->general->connectDbPortal();

        $leg_level_id = $this->data['user']->leg_level_id;
        $plant = $this->data['user']->gsber;


        $this->data['leg_level_id'] = $leg_level_id;
        $this->data['plant'] = $plant;
        $this->data['akses_plant'] = (isset($this->data['role_user'][0]->pabrik)) ? explode(",", $this->data['role_user'][0]->pabrik) : $this->data['user']->gsber;

        $cek_akses_buat = array_filter($this->data['role_user'], function ($obj) {
            if ($obj->akses_buat == 0) return false;
            return true;
        });

        $this->data['akses_buat'] = !empty($cek_akses_buat) ? 1 : 0;

        //lha
        if (isset($_POST['filter_plant'])) {
            $filter_plant    = array();
            foreach ($_POST['filter_plant'] as $dt) {
                array_push($filter_plant, $dt);
            }
        } else {
            if (($this->data['user']->ho == 'n') and  (($this->data['user']->gsber == 'KJP1') or ($this->data['user']->gsber == 'KJP2'))) {
                $filter_plant    = array("KJP1", "KJP2");
            } else {
                $filter_plant  = NULL;
            }
        }
        if (isset($_POST['filter_jenis'])) {
            $filter_jenis    = array();
            foreach ($_POST['filter_jenis'] as $dt) {
                array_push($filter_jenis, $dt);
            }
        } else {
            $filter_jenis  = NULL;
        }
        $filter_tanggal_berlaku_awal = (!empty($_POST['filter_tanggal_berlaku_awal'])) ? date('Y-m-d', strtotime($_POST['filter_tanggal_berlaku_awal'])) : NULL;
        $filter_tanggal_berlaku_akhir = (!empty($_POST['filter_tanggal_berlaku_akhir'])) ? date('Y-m-d', strtotime($_POST['filter_tanggal_berlaku_akhir'])) : NULL;
        $filter_tanggal_berakhir_awal = (!empty($_POST['filter_tanggal_berakhir_awal'])) ? date('Y-m-d', strtotime($_POST['filter_tanggal_berakhir_awal'])) : NULL;
        $filter_tanggal_berakhir_akhir = (!empty($_POST['filter_tanggal_berakhir_akhir'])) ? date('Y-m-d', strtotime($_POST['filter_tanggal_berakhir_akhir'])) : NULL;
        if (isset($_POST['filter_status'])) {
            $filter_status    = array();
            foreach ($_POST['filter_status'] as $dt) {
                array_push($filter_status, $dt);
            }
        } else {
            $filter_status  = NULL;
        }

        $canAddSPK = false;
        $canDownloadTemplate = false;

        if ($leg_level_id == 1 and $leg_level_id == 2) {
            $canAddSPK = true;
            $canDownloadTemplate = true;
        }

        array_push($this->data, compact('canAddSPK', 'canDownloadTemplate'));

        // $this->data['list'] = $list_spk;

        /** Data Form SPK */
        if (($this->data['user']->ho == 'n') and  (($this->data['user']->gsber == 'KJP1') or ($this->data['user']->gsber == 'KJP2'))) {
            $ck_plant    = array("KJP1", "KJP2");
        } else {
            $ck_plant    = ($leg_level_id == 2) ? $this->data['user']->gsber : NULL;
        }

        $this->data['filter_plant'] = $this->dgeneral->get_master_plant($ck_plant, NULL, NULL, 'ERP');

        $this->data['filter_jenis'] = $this->dmaster->get_jenis_spk();
        $this->data['filter_status'] = $this->dmaster->get_status_spk();

        $this->data['jenis_spk'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_jenis_spk(),
            array('id_jenis_spk')
        );


        $this->data['jenis_vendor'] = $this->general->generate_encrypt_json(
            $this->dmaster->get_jenis_vendor(),
            array('id_jenis_vendor')
        );

        $this->load->view('transaction/manage', $this->data);
    }

    public function detail($key = NULL)
    {
        //====must be initiate in every view function====/
        $this->general->check_session();
        $data['generate']   = $this->generate;
        $data['module']     = $this->data['module']; //$this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $data['title']         = "Data Perjanjian";
        $data['title_form']  = "Detail Perjanjian";

        $id_spk  = $this->generate->kirana_decrypt($key);
        $data['data_spk'] = $this->get_data_spk(array(
            "connect" => TRUE,
            "data" => "complete",
            "id_spk" => $id_spk,
            "user_level" => $this->data['level_user'],
            "encrypt" => array("id_spk")
        ));

        if (
            !$key || empty($data['data_spk'])
            //|| in_array($this->data['project_header']->plant, $this->access_plant) === false
        )
            show_404();

        $this->load->view("transaction/detail", $data);
    }

    private function get_spk_table_divisi($id_spk = null, $divisis = array())
    {
        return $this->load->view('transaction/includes/table_divisi', compact('divisis', 'id_spk'), true);
    }

    private function get_spk_table_dokumen($id_spk = null, $data = array(), $tipe = 'template')
    {
        if ($tipe == 'template')
            return $this->load->view('transaction/includes/table_dokumen_template', compact('data', 'id_spk'), true);
        elseif (($tipe == 'vendor') or ($tipe == 'vendor_dokumen'))
            return $this->load->view('transaction/includes/table_dokumen_vendor', compact('data', 'id_spk'), true);
        else
            return $this->load->view('transaction/includes/table_dokumen_kualifikasi', compact('data', 'id_spk'), true);
    }

    public function save($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'dokumen':
                $return = $this->save_dokumen($data);
                break;
            case 'spk':
                $return = $this->save_spk();
                break;
            case 'submitspk':
                $return = $this->save_submit_spk($data);
                break;
            case 'reviewspk':
                $return = $this->save_review_spk($data);
                break;
            case 'assignspk':
                $return = $this->save_assign_spk($data);
                break;
            case 'approvespk':
                $return = $this->save_approve_spk($data);
                break;
            case 'finaldraft':
                $return = $this->save_final_draft($data);
                break;
            case 'final':
                $return = $this->save_final($data);
                break;
            case 'komentar':
                $return = $this->save_komentar($data);
                break;
            case 'dropspk':
                $return = $this->save_drop_spk($data);
                break;
            case 'cancelspk':
                $return = $this->save_cancel_spk($data);
                break;
            case 'approval':
                $return = $this->save_approval();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function get($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'jenisspk':
                $return = $this->get_jenis_spk($data);
                break;
            case 'namaspk':
                $return = $this->get_nama_spk($data);
                break;
            case 'spk':
                $id_spk  = (isset($_POST['id_spk'])) ? $this->generate->kirana_decrypt($_POST['id_spk']) : NULL;
                $in_plant = (isset($this->data['role_user'][0]->pabrik)) ? explode(",", $this->data['role_user'][0]->pabrik) : $this->data['user']->gsber;

                $param_spk = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "id_spk" => $id_spk,
                    // "IN_status" => $this->input->post("IN_status", TRUE),
                    "IN_jenis_spk" => $this->input->post("IN_jenis_spk", TRUE),
                    "tanggal_perjanjian_awal" => (isset($_POST['tanggal_perjanjian_awal']) && $_POST['tanggal_perjanjian_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_perjanjian_awal']) : NULL,
                    "tanggal_perjanjian_akhir" => (isset($_POST['tanggal_perjanjian_akhir']) && $_POST['tanggal_perjanjian_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_perjanjian_akhir']) : NULL,
                    "tanggal_submit_awal" => (isset($_POST['tanggal_submit_awal']) && $_POST['tanggal_submit_awal'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_submit_awal']) : NULL,
                    "tanggal_submit_akhir" => (isset($_POST['tanggal_submit_akhir']) && $_POST['tanggal_submit_akhir'] != "") ? $this->generate->regenerateDateFormat($_POST['tanggal_submit_akhir']) : NULL,
                    "IN_plant" => empty($this->input->post("pabrik", TRUE)) ? $in_plant : $this->input->post("pabrik", TRUE),
                    "user_level" => $this->data['level_user'],
                    "encrypt" => array("id_spk", "id_jenis_spk", "id_jenis_vendor")
                );
                $status    = $this->input->post("IN_status", TRUE);
                $status_all    = array("onprogress", "confirmed", "finaldraft", "completed", "drop", "cancelled");
                if (!empty($status)) {
                    if (in_array("onprogress", $status) == true) {
                        $param_spk['NOT_IN_status'] = $this->general->emptyconvert(
                            array_diff($status_all, $status)
                        );
                    } else {
                        $param_spk['IN_status'] = $status;
                    }
                }
                $this->get_data_spk($param_spk);
                exit();
                break;
            case 'logspk':
                $id_spk  = (isset($_POST['id_spk'])) ? $this->generate->kirana_decrypt($_POST['id_spk']) : NULL;

                $return = $this->dspk->get_spk_log(array(
                    "CONNECT" => TRUE,
                    "id_spk" => $id_spk
                ));
                break;
            case 'divterkait':
                $id_spk  = (isset($_POST['id_spk'])) ? $this->generate->kirana_decrypt($_POST['id_spk']) : NULL;

                $return = $this->get_data_divisi_terkait(array(
                    "connect" => TRUE,
                    "id_spk" => $id_spk
                ));
                break;
            case 'submitspk':
                $return = $this->get_submit_spk($data);
                break;
            case 'approvespk':
                $return = $this->get_approve_spk($data);
                break;
            case 'komentar':
                $return = $this->get_komentar($data);
                break;
            case 'attachments':
                $return = $this->get_attachments($data);
                break;
                // //lha	
                // case 'plant':
                // $return = $this->dgeneral->get_master_plant(NULL);
                // break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }
    public function get2($param = NULL, $param2 = NULL)
    {
        switch ($param) {
            case 'vendor':
                if (isset($_GET['q'])) {
                    $plant                = isset($_GET['plant']) ? $_GET['plant'] : null;
                    $id_jenis_spk        = isset($_GET['id_jenis_spk']) ? $this->generate->kirana_decrypt($_GET['id_jenis_spk']) : null;
                    $id_jenis_vendor    = isset($_GET['id_jenis_vendor']) ? $this->generate->kirana_decrypt($_GET['id_jenis_vendor']) : null;
                    // $data      			= $this->dspk->get_data_spec('open', $_GET['q'], $id_jenis_vendor, $plant, $id_jenis_spk);
                    //lha go live master vendor
                    $data                  = $this->dspk->get_data_master_vendor('open', $_GET['q'], $id_jenis_vendor, $plant);
                    // $data 	   = $this->general->generate_encrypt_json($data, array("LIFNR"));
                    $data_json = array(
                        "total_count"        => count($data),
                        "incomplete_results" => false,
                        "items"              => $data
                    );

                    $return = json_encode($data_json);
                    $return = $this->general->jsonify($data_json);

                    echo $return;
                    break;
                }
            case 'kualifikasi_vendor':
                $lifnr     = (isset($_POST['lifnr']) ? $_POST['lifnr'] : NULL);
                $plant     = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
                // $data	= $this->dspk->get_data_spec('open', NULL, NULL, $plant, NULL, $lifnr);
                //lha go live master vendor
                $data                  = $this->dspk->get_data_master_vendor('open', NULL, NULL, $plant, $lifnr);
                $return = json_encode($data);
                echo $return;
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    public function set($param = NULL)
    {
        switch ($param) {
            case 'delete':
                $this->delete_spk(
                    array(
                        "connect" => TRUE
                    )
                );
                break;

            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    public function download($param, $id = null)
    {
        switch ($param) {
            case 'template':
                $return = $this->get_template($id);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    private function save_dokumen($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $id_oto = $this->generate->kirana_decrypt($data['id_oto']);

        if ($data['tipe'] == 'template') {
            $spk = $this->dspk->get_spk_template(
                array(
                    'id_spk' => $id_spk,
                    'id_oto_jenis' => $id_oto,
                    'single_row' => true
                )
            );
            $folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_TEMPLATE_FOLDER;
        } else {
            $spk = $this->dspk->get_spk_vendor(
                array(
                    'id_spk' => $id_spk,
                    'id_oto_vendor' => $id_oto,
                    'single_row' => true
                )
            );
            $folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_TEMPLATE_FOLDER;
        }


        $data_row = array();

        $upload_error = $this->general->check_upload_file('dokumen', true);

        if (isset($_FILES['dokumen']) and empty($upload_error)) {
            $uploaddir = KIRANA_PATH_FILE . $folder;
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777, true);
            }

            $config['upload_path'] = $uploaddir;
            $config['allowed_types'] = 'jpeg|jpg|png|pdf|dot|doc|docx|xls|xlsx';
            $config['max_size'] = 5000;
            $config['remove_spaces'] = true;

            $filename = strtolower($id_spk . '_' . $data['tipe'] . '_' . $spk->jenis_spk . '_' . $spk->nama_doc);
            // $config['file_name'] = $filename;

            $this->load->library('upload', $config);

            $upload_error = null;

            // if ($this->upload->do_upload('dokumen')) {
            //     $upload_data = $this->upload->data();
            //     $data_row['files'] = KIRANA_PATH_FILE_FOLDER . $folder . $upload_data['file_name'];
            //     $data_row['tipe_files'] = substr($upload_data['file_ext'], 1);
            //     $data_row['size_files'] = $upload_data['file_size'];
            // } else {
            //     $upload_error = $this->upload->display_errors('', '');
            // }

            $newname    = array(str_replace(' ', '_', $filename));
            $file        = $this->general->upload_files($_FILES['dokumen'], str_replace('_-_', '-', $newname), $config);
            $data_row['files'] = KIRANA_PATH_FILE_FOLDER . SPK_UPLOAD_FOLDER . SPK_UPLOAD_TEMPLATE_FOLDER . $file[0]['file_name'];
            $data_row['tipe_files'] = substr($file[0]['file_ext'], 1);
            $data_row['size_files'] = $file[0]['file_size'];
        }

        if (isset($data['id_upload']) && !empty($data['id_upload'])) {
            $id = $this->generate->kirana_decrypt($data['id_upload']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            /** Tipe upload dokumen template atau vendor */
            if ($data['tipe'] == 'template') {
                $result = $this->dgeneral->update('tbl_leg_upload_template', $data_row, array(
                    array(
                        'kolom' => 'id_upload_template',
                        'value' => $id
                    )
                ));
            } else {
                $result = $this->dgeneral->update('tbl_leg_upload_vendor', $data_row, array(
                    array(
                        'kolom' => 'id_upload_vendor',
                        'value' => $id
                    )
                ));
            }
        } else {

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            /** Tipe upload dokumen template atau vendor */
            if ($data['tipe'] == 'template') {
                $data_row['id_spk'] = $id_spk;
                $data_row['id_jenis_spk'] = $spk->id_jenis_spk;
                $data_row['id_oto_jenis'] = $spk->id_oto_jenis;
                $data_row['nama_dok_template']     = $spk->nama_doc;
                $data_row['login_edit']         = base64_decode($this->session->userdata("-id_user-"));
                $data_row['tanggal_edit']         = date("Y-m-d H:i:s");

                $result = $this->dgeneral->insert('tbl_leg_upload_template', $data_row);
            } else {
                $data_row['id_spk'] = $id_spk;
                $data_row['id_jenis_vendor'] = $spk->id_jenis_vendor;
                $data_row['id_oto_vendor'] = $spk->id_oto_vendor;
                $data_row['nama_dokumen'] = $spk->nama_doc;
                $data_row['mandatory'] = $spk->mandatory_doc;
                $data_row['login_edit']         = base64_decode($this->session->userdata("-id_user-"));
                $data_row['tanggal_edit']         = date("Y-m-d H:i:s");

                $result = $this->dgeneral->insert('tbl_leg_upload_vendor', $data_row);
            }
        }

        if (isset($upload_error)) {
            $this->dgeneral->rollback_transaction();
            $msg = $upload_error;
            $sts = "NotOK";
            if (isset($data_row['files']))
                unlink(KIRANA_PATH_ASSETS . $data_row['files']);
        } else if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
            //jika yang melakukan edit adalah pic legal pabrik, mengirim email
            //sent email dari sini
            /*
            if ($this->data['user']->leg_level_id == 2) {
                setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'mail.kiranamegatara.com';
                $config['smtp_user'] = 'no-reply@kiranamegatara.com';
                $config['smtp_pass'] = '1234567890';
                $config['smtp_port'] = '465';
                $config['smtp_crypto'] = 'ssl';
                $config['charset'] = 'iso-8859-1';
                $config['wordwrap'] = true;
                $config['mailtype'] = 'html';

                try {
                    $data_email = $this->get_email('array');
                    foreach ($data_email as $dt) {
                        $subject = 'Konfirmasi Perubahan File Vendor/Template Perjanjian';
                        $this->load->library('email', $config);
                        $this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
                        $this->email->subject($subject);

                        $this->email->to($dt->email);
                        // $this->email->to('mutia.ariani@kiranamegatara.com');
                        // $this->email->to('frans.darmawan@kiranamegatara.com');
                        // $this->email->to('lukman.hakim@kiranamegatara.com');
                        $message =    '<p><b>Kepada Bpk/Ibu</b></p>';
                        $message .=    '<p>Berikut terlampir perubahan vendor/template dari team legal pabrik dari:</p>';
                        $message .=    '<table>';
                        $message .=    '	<tr><td>Plant</td><td>: ' . $spk->plant . '</td></tr>';
                        $message .=    '	<tr><td>Jenis Perjanjian</td><td>: ' . $spk->jenis_spk . '</td></tr>';
                        // $message .=    '	<tr><td>Nama SPK</td><td>: ' . $spk->nama_spk . '</td></tr>';
                        $message .=    '	<tr><td>Perihal</td><td>: ' . $spk->perihal . '</td></tr>';
                        $message .=    '	<tr><td>SPPKP</td><td>: ' . $spk->SPPKP . '</td></tr>';
                        $message .=    '	<tr><td>File Template</td><td>: ' . $spk->nama_doc . '</td></tr>';
                        $message .=    '</table>';
                        $message .=    '<p>Mohon dapat dicek kembali.</p>';

                        $this->email->message($message);
                        $this->email->send();
                    }
                } catch (Exception $e) {
                    $msg = $e->getMessage();
                    $sts = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                    exit();
                }
            }
            */
            //sent email sampe sini
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_spk($param = NULL)
    {
        $post = $this->input->post(NULL, TRUE);
        $datetime     = date("Y-m-d H:i:s");


        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $plant = $this->general->get_master_plant(array($post["plant"]), null, null, 'ERP');

        $leg_level_id = $this->data['user']->leg_level_id;



        // $nama_spk = $this->dmaster->get_nama_spk(
        //     array(
        //         'id_nama_spk' => $this->generate->kirana_decrypt($_POST['id_nama_spk']),
        //         'single_row' => true
        //     )
        // );

        $jenis_vendor = $this->dmaster->get_jenis_vendor(
            array(
                'id_jenis_vendor' => $this->generate->kirana_decrypt($_POST['id_jenis_vendor']),
                'single_row' => true
            )
        );

        if ($leg_level_id == 1) {
            $id_status = 13;
        } else if ($leg_level_id == 2) {
            $id_status = 7;
        }

        $data_row = array(
            "plant" => $plant[0]->plant,
            "id_plant" => $plant[0]->id_pabrik,
            // "id_jenis_spk" => $id_jenis_spk,
            // "jenis_spk" => $jenis_spk->jenis_spk,
            // "id_nama_spk" => $this->generate->kirana_decrypt($post['id_nama_spk']),
            // "nama_spk" => $nama_spk->nama_spk,
            "perihal" => $post["perihal"],
            "tanggal_perjanjian" => $this->generate->regenerateDateFormat($post["tanggal_perjanjian"]),
            "tanggal_berlaku_spk" => $this->generate->regenerateDateFormat($post["tanggal_berlaku_spk"]),
            "tanggal_berakhir_spk" => $this->generate->regenerateDateFormat($post["tanggal_berakhir_spk"]),
            "id_jenis_vendor" => $this->generate->kirana_decrypt($post['id_jenis_vendor']),
            "jenis_vendor" => $jenis_vendor->jenis_vendor,
            "nama_vendor" => $post['nama_vendor'],
            "lifnr" => $post["lifnr"],
            "id_kualifikasi" => $post['id_kualifikasi'],
            "SPPKP" => $post['SPPKP'],
        );


        if (isset($_POST['id_spk']) && !empty($_POST['id_spk'])) {
            $action = "edit";
            $id_spk = $this->generate->kirana_decrypt($_POST['id_spk']);
            // $data_row["status"] = $this->generate_status(array(
            //     "connect" => false,
            //     "id_spk" => $id_spk
            // ));
            $data_spk = $this->dspk->get_data_spk(array(
                "connect" => false,
                "user_level" => $this->data['level_user'],
                "id_spk" => $id_spk
            ));
            $current_status = $data_spk->status;
            $id_jenis_spk = $data_spk->id_jenis_spk;

            unset($_POST['id_spk']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id_spk
                )
            ));
        } else {
            $id_jenis_spk = $this->generate->kirana_decrypt($post['id_jenis_spk']);
            $jenis_spk = $this->dmaster->get_jenis_spk(
                array(
                    'id_jenis_spk' => $id_jenis_spk,
                    'single_row' => true
                )
            );

            $current_status = NULL;
            $action = "create";
            $data_row['id_jenis_spk'] = $id_jenis_spk;
            $data_row['jenis_spk'] = $jenis_spk->jenis_spk;
            $data_row["status"] = $this->generate_status(array(
                "new" => true,
                "action" => $action,
            ));

            // $data['plant'] = $plant[0]->plant;
            // $data['id_plant'] = $plant[0]->id_pabrik;
            $data_row['id_status'] = $id_status;
            // $data_row['status'] = 'draft';

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $this->dgeneral->insert('tbl_leg_spk', $data_row);
            $id_spk = $this->db->insert_id();
        }

        //===========save spk log===========//
        $data_log = $this->order_log(
            array(
                "datetime" => $datetime,
                "id_spk" => $id_spk,
                "id_jenis_spk" => $id_jenis_spk,
                "action" => $action,
                "current_status" => ($current_status ? $current_status : $data_row['status']),
            )
        );
        $this->dgeneral->insert('tbl_leg_log_status', $data_log);

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function get_nama_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->dmaster->get_nama_spk(array(
                'id_jenis_spk' => $id
            ));

            $result = $this->general->generate_encrypt_json(
                $result,
                array('id_nama_spk', 'id_jenis_spk')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_jenis_spk($data)
    {
        $this->general->connectDbPortal();

        $result = $this->dmaster->get_jenis_spk();

        foreach ($result as $jenis) {
            $jenis->link = base_url('spk/download/template/' . $jenis->id_jenis_spk);
        }

        $result = $this->general->generate_encrypt_json(
            $result,
            array('id_jenis_spk')
        );

        return array('sts' => 'OK', 'data' => $result);
    }

    private function get_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->general->generate_encrypt_json(
                $this->dspk->get_spk(array(
                    'id_spk' => $id,
                    'single_row' => true
                )),
                array('id_spk', 'id_jenis_spk', 'id_jenis_vendor', 'id_nama_spk')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_data_spk($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dspk->get_data_spk($param);
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
                            $result = $this->general->jsonify($result);
                    }
                }
                break;
            case 'complete':
                $result = $this->dspk->get_data_spk($param);
                unset($param['encrypt']);
                if ($result) {
                    // $result->history = $this->dspk->get_spk_log($param);
                    $result->last_action = $this->dspk->get_spk_last_action($param);

                    $result->akses_cancel_spk = 0;
                    $result->akses_final_draft = 0;
                    $result->akses_final_spk = 0;

                    //akses cancel spk
                    if (isset($this->data['role_user']) && $this->data['role_user'][0]->akses_hapus == 1) {
                        $result->akses_cancel_spk = 1;
                    }

                    //akses upload final draft
                    if (in_array(1, $this->data['level_user'])) {
                        $result->akses_final_draft = 1;
                    }

                    //akses upload final draft
                    if (in_array($result->level_owner, $this->data['level_user'])) {
                        $result->akses_final_spk = 1;
                    }
                }
                break;

            default:
                $result = $this->dspk->get_data_spk($param);
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

    private function delete_spk($param = NULL)
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);

        $id_spk = $this->generate->kirana_decrypt($post['id_spk']);
        $spk = $this->dspk->get_data_spk(
            array(
                "connect" => $param['connect'],
                'id_spk' => $id_spk,
                'single_row' => true,
                "user_level" => $this->data['level_user'],
            )
        );

        if (isset($post['id_spk']) && $spk) {
            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->connectDbPortal();

            $this->dgeneral->begin_transaction();

            $data_row = $this->dgeneral->basic_column('delete');

            $this->dgeneral->update(
                'tbl_leg_spk',
                $data_row,
                array(
                    array(
                        'kolom' => 'id_spk',
                        'value' => $id_spk
                    )
                )
            );

            //===========save spk log===========//
            $data_log = $this->order_log(
                array(
                    "datetime" => $datetime,
                    "id_spk" => $id_spk,
                    "id_jenis_spk" => $spk->id_jenis_spk,
                    "action" => 'delete',
                    "current_status" => $spk->status,
                )
            );
            $this->dgeneral->insert('tbl_leg_log_status', $data_log);

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil dihapus";
                $sts = "OK";
            }
            $this->general->closeDb();
        } else {
            $sts = "NotOK";
            $msg = "Tidak ada data yang akan di hapus.";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
        exit();
    }

    private function get_submit_spk($param = NULL)
    {
        if (isset($param['id_spk'])) {
            // $id = $this->generate->kirana_decrypt($data['id']);

            // $this->general->connectDbPortal();

            $spk = $this->dspk->get_data_spk(array(
                "connect" => false,
                'single_row' => true,
                'id_spk' => $param['id_spk'],
                "user_level" => $this->data['level_user'],
            ));
            //untuk data yang belum upload dokumen vendor(spk lama)
            if ($spk->id_kualifikasi == null) {
                $template_count = $this->dspk->get_total_spk_template(array(
                    'single_row' => true,
                    'id_jenis_spk' => $spk->id_jenis_spk
                ));

                $template_up_count = $this->dspk->get_total_spk_template_uploaded(array(
                    'single_row' => true,
                    'id_spk' => $param['id_spk'],
                    'id_jenis_spk' => $spk->id_jenis_spk
                ));

                $vendor_count = $this->dspk->get_total_spk_vendor(array(
                    'single_row' => true,
                    'id_jenis_vendor' => $spk->id_jenis_vendor
                ));

                $vendor_up_count = $this->dspk->get_total_spk_vendor_uploaded(array(
                    'single_row' => true,
                    'id_spk' => $param['id_spk'],
                    'id_jenis_vendor' => $spk->id_jenis_vendor
                ));

                if (
                    $template_count->totaldok <= $template_up_count->totaldokup and
                    $vendor_count->total_ven_mandatory <= $vendor_up_count->total_ven_mandatory
                ) {
                    return array('sts' => 'OK');
                } else {
                    return array('sts' => 'NotOK', 'msg' => 'Submit Perjanjian gagal! Mohon Lengkapi dokumen yang harus di Upload.');
                }
            } else {
                if ($spk->tanggal_submit) {
                    //jika sudah pernah submit tidak perlu validasi
                    return array('sts' => 'OK');
                } else {
                    $template_count = $this->dspk->get_total_spk_template(array(
                        'single_row' => true,
                        'id_jenis_spk' => $spk->id_jenis_spk
                    ));

                    $template_up_count = $this->dspk->get_total_spk_template_uploaded(array(
                        'single_row' => true,
                        'id_spk' => $param['id_spk'],
                        'id_jenis_spk' => $spk->id_jenis_spk
                    ));
                    if ($template_count->totaldok <= $template_up_count->totaldokup) {
                        return array('sts' => 'OK');
                    } else {
                        return array('sts' => 'NotOK', 'msg' => 'Submit Perjanjian gagal! Mohon Lengkapi dokumen yang harus di Upload.');
                    }
                }
            }
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function get_approve_spk($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $leg_level_id = $this->data['user']->leg_level_id;

            $spk_approved = $this->dspk->get_spk_divisi(array(
                'single_row' => true,
                'id_spk' => $id,
                'id_divisi' => $leg_level_id
            ));

            if (isset($spk_approved)) {
                if ($spk_approved->approve != 'y') {
                    if ($leg_level_id == 3) {
                        return array('sts' => 'OK', 'msg' => 'Apakah anda sudah mengisi beban pajak Perjanjian dan yakin untuk melakukan approve Perjanjian ini?');
                    } else {

                        return array('sts' => 'OK', 'msg' => 'Apakah anda yakin untuk melakukan approve Perjanjian ini?');
                    }
                } else {
                    return array('sts' => 'NotOK', 'msg' => 'Perjanjian ini telah di approve oleh divisi anda.');
                }
            } else
                return array('sts' => 'NotOK', 'msg' => 'Anda tidak memiliki hak akses untuk approval Perjanjian ini.');
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function save_submit_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $this->dgeneral->begin_transaction();

            $id = $this->generate->kirana_decrypt($data['id']);

            $data_row = $this->dgeneral->basic_column(
                'update',
                array('id_status' => 2)
            );

            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Perjanjian berhasil di submit.";
                $sts = "OK";
                $this->send_email_konfirmasi($id);
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_review_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $this->dgeneral->begin_transaction();

            $id = $this->generate->kirana_decrypt($data['id']);

            $status = 4;
            if ($data['action'] == 'approve')
                $status = 3;

            $data_row = $this->dgeneral->basic_column(
                'update',
                array(
                    'id_status' => $status,
                    'tanggal_approve' => date('Y-m-d')
                )
            );

            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Perjanjian berhasil di " . $data['action'] . ".";
                $sts = "OK";
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_assign_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $this->dgeneral->begin_transaction();

            $id = $this->generate->kirana_decrypt($data['id']);

            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));


            $owner = $this->dmaster->get_karyawan(
                array(
                    'id_user' => $spk->login_buat,
                    'single_row' => true
                )
            );

            if ($owner->leg_level_id == 1)
                $data_row = $this->dgeneral->basic_column(
                    'update',
                    array(
                        'id_status' => 1,
                        'tanggal_approve' => date('Y-m-d')
                    )
                );
            else
                $data_row = $this->dgeneral->basic_column(
                    'update',
                    array(
                        'id_status' => 1
                    )
                );

            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id
                )
            ));

            $assigned_divisi = $this->dspk->get_oto_divisi(
                array(
                    'id_spk' => $id
                )
            );

            foreach ($assigned_divisi as $divisi) {
                $check_approval = $this->dspk->get_spk_divisi(
                    array(
                        'id_spk' => $id,
                        'id_oto_div' => $divisi->id_oto_divisi,
                        'single_row' => true
                    )
                );

                if (!isset($check_approval)) {
                    $data_div = $this->dgeneral->basic_column(
                        'insert',
                        array(
                            'id_spk' => $id,
                            'id_oto_div' => $divisi->id_oto_divisi
                        )
                    );
                    $insert_divisi = $this->dgeneral->insert('tbl_leg_approval', $data_div);
                }
            }

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Perjanjian berhasil di assign Perijinan ke divisi terkait.";
                $sts = "OK";
                /** Send email ke karyawan-karyawan di divisi terkait yang memiliki hak akses*/
                $this->send_assign_divisi($id);
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    public function test_email($id)
    {
        echo json_encode($this->send_assign_divisi($id));
    }

    private function send_email_konfirmasi($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {
                $owner = $this->dmaster->get_karyawan(
                    array(
                        'id_user' => $spk->login_buat,
                        'single_row' => true
                    )
                );

                $subject = 'Permohonan Pembuatan Perjanjian ' . $spk->nama_spk . '.';

                $karyawans = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 1
                    )
                );

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_konfirmasi', compact('spk', 'owner', 'emailOri'), true);

                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu Perjanjian',
                                'message' => $message,
                                'to' => $email
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function send_assign_divisi($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {
                $legalPabrik = array();
                if ($this->data['user']->leg_level_id == 2)
                    $legalPabrik = $this->dmaster->get_karyawan(
                        array(
                            'leg_level_id' => 2,
                            'plant' => $spk->plant
                        )
                    );

                $subject = 'Permohonan Konfirmasi Perjanjian dari Divisi Terkait ' . $spk->nama_spk . '.';

                $divisTerkait = $this->dmaster->get_karyawan(
                    array(
                        'id_spk' => $id
                    )
                );

                $karyawans = array_merge($legalPabrik, $divisTerkait);

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_assign_divisi', compact('spk', 'emailOri'), true);
                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu Perjanjian',
                                'message' => $message,
                                'to' => $email
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function send_approval_divisi($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {
                $owner = $this->dmaster->get_karyawan(
                    array(
                        'id_user' => $spk->login_buat,
                        'single_row' => true
                    )
                );

                $subject = 'Konfirmasi Perjanjian dari Divisi Terkait ' . $spk->nama_spk . '.';

                $legalPabrik = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 2,
                        'plant' => $spk->plant
                    )
                );

                $legalHO = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 1
                    )
                );

                $karyawans = array_merge($legalHO, $legalPabrik);

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_approval_divisi', compact('spk', 'emailOri', 'owner'), true);
                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu Perjanjian',
                                'message' => $message,
                                'to' => $email
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function send_konfirmasi_final_draft($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {

                $subject = 'Pemberitahuan Finalisasi Draft Perjanjian ' . $spk->nama_spk . '.';

                $divisiTerkait = $this->dmaster->get_karyawan(
                    array(
                        'id_spk' => $id
                    )
                );

                $legalPabrik = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 2,
                        'plant' => $spk->plant
                    )
                );

                $karyawans = array_merge($divisiTerkait, $legalPabrik);

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email) && !empty($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_final_draft', compact('spk', 'emailOri'), true);
                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu Perjanjian',
                                'message' => $message,
                                'to' => $email,
                                'attachment' => base_url('assets/' . $spk->files_1)
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function send_konfirmasi_final($id = null)
    {
        if (isset($id)) {
            $spk = $this->dspk->get_spk(array(
                'id_spk' => $id,
                'single_row' => true
            ));

            if (isset($spk)) {
                $owner = $this->dmaster->get_karyawan(
                    array(
                        'id_user' => $spk->login_buat,
                        'single_row' => true
                    )
                );

                $subject = 'Pemberitahuan Finalisasi Perjanjian ' . $spk->nama_spk . '.';

                $karyawans = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 1
                    )
                );

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view('emails/spk_final', compact('spk', 'emailOri', 'owner'), true);
                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu Perjanjian',
                                'message' => $message,
                                'to' => $email,
                                // 'attachment' => base_url('assets/' . $spk->files_1)
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function save_approve_spk($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $id = $this->generate->kirana_decrypt($data['id']);

            $leg_level_id = $this->data['user']->leg_level_id;

            $approval = $this->dspk->get_approval(
                array(
                    'single_row' => true,
                    'id_spk' => $id,
                    'id_divisi' => $leg_level_id
                )
            );

            if (isset($approval)) {

                $this->dgeneral->begin_transaction();

                $data_row = $this->dgeneral->basic_column(
                    'update',
                    array(
                        'approve' => 'y',
                        'tanggal_approve' => date('Y-m-d')
                    )
                );

                $result = $this->dgeneral->update('tbl_leg_approval', $data_row, array(
                    array(
                        'kolom' => 'id_spk',
                        'value' => $id
                    ),
                    array(
                        'kolom' => 'id_oto_div',
                        'value' => $approval->id_oto_div
                    )
                ));

                $divisis = $this->dspk->get_spk_divisi(
                    array(
                        'id_spk' => $id
                    )
                );

                $approved = $this->dspk->get_approval(
                    array(
                        'id_spk' => $id,
                        'approve' => 'y'
                    )
                );

                /** @var bool $send_email send email konfirmasi spk ketika approval dr divisi sudah selesai semua */
                $send_email = false;

                /** Jika approval sudah disetujui semua divisi, status dirubah menjadi 9 (Confirmed Div Teknis) */
                if (count($divisis) <= count($approved)) {
                    $data_update = $this->dgeneral->basic_column(
                        'update',
                        array('id_status' => 9)
                    );

                    $result_update_spk = $this->dgeneral->update('tbl_leg_spk', $data_update, array(
                        array(
                            'kolom' => 'id_spk',
                            'value' => $id
                        )
                    ));

                    $send_email = true;
                }

                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Perjanjian berhasil di approve.";
                    $sts = "OK";
                    if ($send_email)
                        $this->send_approval_divisi($id);
                }
            } else {
                $msg = "Approval Perjanjian untuk divisi anda tidak tersedia.";
                $sts = "NotOK";
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    // private function save_file($param) {
    private function save_final_draft_xx($data)
    {
        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $spk = $this->dspk->get_spk(
            array(
                'id_spk' => $id_spk,
                'single_row' => true
            )
        );
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $jml_file = count($_FILES['file']['name']);
        if ($jml_file > 1) {
            $this->dgeneral->rollback_transaction();
            $msg    = "You can only upload maximum 1 files";
            $sts    = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);
            exit();
        }

        //upload file 
        if ($_FILES['file']['name'][0] != '') {
            $folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_FINAL_DRAFT_FOLDER;
            $uploaddir = KIRANA_PATH_FILE . $folder;

            $config['upload_path']   = $uploaddir;
            $config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|7z';
            $config['max_size']      = 5000;

            $newname    = array(strtolower($id_spk . '_' . $spk->jenis_spk . '_' . $spk->nama_spk));
            $file        = $this->general->upload_files($_FILES['file'], $newname, $config);
            $url_file    = str_replace("assets/", "", $file[0]['url']);
            if ($file === NULL) {
                $msg        = "Upload files error";
                $sts        = "NotOK";
                $return     = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }
        }

        if ($spk->tanggal_final == null) {
            $datetime    = date("Y-m-d H:i:s");
            $data_row = array(
                'files_1'         => $url_file,
                'tipe_files1'     => pathinfo($url_file, PATHINFO_EXTENSION),
                'size_files1'     => $file[0]['size'],
                'nomor_spk'     => $data['nomor_spk'],
                'id_status'     => 11,
                'tanggal_final' => $datetime
            );
        } else {
            $data_row = array(
                'files_1'         => $url_file,
                'tipe_files1'     => pathinfo($url_file, PATHINFO_EXTENSION),
                'size_files1'     => $file[0]['size'],
                'nomor_spk'     => $data['nomor_spk'],
                'id_status'        => 11
            );
        }

        $data_row = $this->dgeneral->basic_column('update', $data_row);
        $this->dgeneral->update("tbl_leg_spk", $data_row, array(
            array(
                'kolom' => 'id_spk',
                'value' => $id_spk
            )
        ));

        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Final draft berhasil ditambahkan";
            $sts = "OK";
            $resEmail = $this->send_konfirmasi_final_draft($id_spk);
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_final_draft($data)
    {
        $datetime    = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $spk = $this->dspk->get_data_spk(
            array(
                'id_spk' => $id_spk,
                'single_row' => true,
                "user_level" => $this->data['level_user'],
            )
        );

        $folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_FINAL_DRAFT_FOLDER;

        if ($spk->tanggal_final == null) {

            $data_row = array(
                'nomor_spk' => $data['nomor_spk'],
                'id_status' => 11,
                'tanggal_final' => $datetime,
                'status' => 'finaldraft'
            );
        } else {
            $data_row = array(
                'nomor_spk' => $data['nomor_spk'],
                'id_status' => 11,
            );
        }

        $upload_error = $this->general->check_upload_file('dokumen', true);

        if (isset($_FILES['dokumen']) and empty($upload_error)) {
            $uploaddir = KIRANA_PATH_FILE . $folder;
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777, true);
            }

            $config['upload_path'] = $uploaddir;
            $config['allowed_types'] = 'jpeg|jpg|png|pdf|dot|doc|docx|xls|xlsx|zip|rar|7zip|7z';
            $config['max_size'] = 5000;
            $config['remove_spaces'] = true;
            $config['overwrite'] = true;

            $filename = strtolower($id_spk . '_' . $spk->jenis_spk);
            $config['file_name'] = str_replace('.', '_', $filename);

            $this->load->library('upload', $config);

            $upload_error = null;

            if ($this->upload->do_upload('dokumen')) {
                $upload_data = $this->upload->data();
                $data_row['files_1'] = KIRANA_PATH_FILE_FOLDER . $folder . $upload_data['file_name'];
                $data_row['tipe_files1'] = substr($upload_data['file_ext'], 1);
                $data_row['size_files1'] = $upload_data['file_size'];
            } else {
                $upload_error = $this->upload->display_errors('', '');
            }
        }

        $data_row = $this->dgeneral->basic_column('update', $data_row);
        $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
            array(
                'kolom' => 'id_spk',
                'value' => $id_spk
            )
        ));

        //===========save spk log===========//
        $data_log = $this->order_log(
            array(
                "datetime" => $datetime,
                "id_spk" => $id_spk,
                "id_jenis_spk" => $spk->id_jenis_spk,
                "action" => 'finaldraft',
                "current_status" => $spk->status,
            )
        );
        $this->dgeneral->insert('tbl_leg_log_status', $data_log);

        $catatan = "Terlampir finalisasi draft perjanjian untuk dapat ditandatangani. <br>
            Dalam penandatanganan, mohon agar diperhatikan ketentuan sebagai berikut:
            <ol type='1'>
            <li>Mohon Perjanjian ini agar dibuat 2 rangkap dengan mencantumkan materai 10000.
            <br>Rangkap pertama materai pada sisi KM diserahkan kepada vendor, dan rangkap kedua dengan materai pada sisi vendor dipegang oleh KM.</li>
            <li>Mohon agar mengupload perjanjian yang telah ditandatangani ke dalam menu <b>'Scan Perjanjian'</b>.</li>
            <li>Mohon agar rangkap asli yang dipegang KM juga dikirimkan kepada Legal HO.</li> 
            </ol>";

        if (isset($upload_error)) {
            $this->dgeneral->rollback_transaction();
            $msg = $upload_error;
            $sts = "NotOK";
            if (isset($data_row['files_1']))
                unlink(KIRANA_PATH_ASSETS . $data_row['files_1']);
        } else if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Final draft berhasil ditambahkan";
            $sts = "OK";
            $data_spk = $this->dspk->get_data_spk(
                array(
                    "connect" => FALSE,
                    "data" => "complete",
                    "id_spk" => $id_spk,
                    "user_level" => $this->data['level_user'],
                )
            );
            $this->send_email(
                array(
                    "post" => array(
                        "action" => "completed",
                        "note_spk" => $catatan,
                    ),
                    "data_spk" => $data_spk,
                    "attachment" => base_url('assets/' . $data_spk->files_1)
                )
            );
            // $resEmail = $this->send_konfirmasi_final_draft($id_spk);
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_drop_spk($data)
    {
        $datetime     = date("Y-m-d H:i:s");
        $id_spk        = (isset($_POST['id_spk']) ? $this->generate->kirana_decrypt($_POST['id_spk']) : NULL);
        $spk = $this->dspk->get_data_spk(
            array(
                'connect' => true,
                'id_spk' => $id_spk,
                'single_row' => true,
                "user_level" => $this->data['level_user'],
            )
        );

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $data_row = array(
            'id_spk'         => $id_spk,
            'id_oto_div'    => 0,
            'approve'         => 'n',
            'id_status'        => 18,
        );

        $data_row = $this->dgeneral->basic_column("insert", $data_row);
        $this->dgeneral->insert("tbl_leg_approval", $data_row);

        //===========save spk log===========//
        $data_log = $this->order_log(
            array(
                "datetime" => $datetime,
                "id_spk" => $id_spk,
                "id_jenis_spk" => $spk->id_jenis_spk,
                "action" => 'drop',
                "current_status" => $spk->status,
            )
        );
        $this->dgeneral->insert('tbl_leg_log_status', $data_log);

        //xx gambar baru
        if (isset($_FILES['gambar'])) {
            $jml_file = count($_FILES['gambar']['name']);
            if ($jml_file > 3) {
                $this->dgeneral->rollback_transaction();
                $msg    = "You can only upload maximum 3 files";
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }

            $config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
            $config['allowed_types'] = 'doc|docx|xls|xlsx|pdf';
            $newname = array();
            for ($i = 0; $i < $jml_file; $i++) {
                if (isset($_FILES['gambar']) && $_FILES['gambar']['error'][$i] == 0 && $_FILES['gambar']['name'][$i] !== "") {
                    array_push($newname, "CANCEL_" . $id_spk . "_" . $i);
                }
            }

            if (count($newname) > 0) {
                $file_img = $this->general->upload_files($_FILES['gambar'], $newname, $config);
                if ($file_img) {
                    $data_batch = array();
                    foreach ($file_img as $dt) {
                        $data_row     = array(
                            "id_status"        => 18,
                            "file_cancel"    => str_replace('assets/file/', 'file/', $dt['url']),
                            "tipe_file_cancel"    => pathinfo($dt['url'], PATHINFO_EXTENSION),
                            'status' => 'drop'
                        );
                    }
                    $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                        array(
                            'kolom' => 'id_spk',
                            'value' => $id_spk
                        )
                    ));
                }
            }
        }

        //xx gambar baru sampe sini
        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil di hapus";
            $sts = "OK";
        }

        //sent email dari sini
        setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.kiranamegatara.com';
        $config['smtp_user'] = 'no-reply@kiranamegatara.com';
        $config['smtp_pass'] = '1234567890';
        $config['smtp_port'] = '465';
        $config['smtp_crypto'] = 'ssl';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = true;
        $config['mailtype'] = 'html';

        try {
            $spk = $this->dspk->get_spk(
                array(
                    'id_spk' => $id_spk,
                    'single_row' => true
                )
            );
            $data_email = $this->get_email('array', $spk->plant, $spk->id_status, $id_spk);    //18 status spk drop
            foreach ($data_email as $dt) {
                $subject = 'Konfirmasi Drop Perjanjian';
                $this->load->library('email', $config);
                $this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
                $this->email->subject($subject);
                // $this->email->to($dt->email);
                $this->email->to('frans.darmawan@kiranamegatara.com');
                $message =    '<p><b>Kepada Bpk/Ibu ' . $dt->nama . '</b></p>';
                $message .=    '<p>Berikut adalah konfirmasi Drop Perjanjian dari:</p>';
                $message .=    '<table>';
                $message .=    '	<tr><td>Plant</td><td>: ' . $spk->plant . '</td></tr>';
                $message .=    '	<tr><td>Jenis Perjanjian</td><td>: ' . $spk->jenis_spk . '</td></tr>';
                $message .=    '	<tr><td>Perihal</td><td>: ' . $spk->perihal . '</td></tr>';
                $message .=    '	<tr><td>SPPKP</td><td>: ' . $spk->SPPKP . '</td></tr>';
                $message .=    '</table>';
                // echo $message; 	
                $this->email->message($message);
                $this->email->send();
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $sts = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);
            exit();
        }
        //sent email sampe sini

        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_final($data)
    {
        $datetime    = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $spk = $this->dspk->get_data_spk(
            array(
                'id_spk' => $id_spk,
                'single_row' => true,
                "user_level" => $this->data['level_user'],
            )
        );

        if (isset($spk)) {
            $folder = SPK_UPLOAD_FOLDER . SPK_UPLOAD_SPK_FOLDER;

            $data_row = array(
                'tanggal_kirim' => date_create($data['tanggal_kirim'])->format('Y-m-d'),
                'no_resi' => $data['no_resi'],
                'id_status' => 12,
                'status' => 'completed'
            );

            $upload_error = $this->general->check_upload_file('dokumen', false);

            if (isset($_FILES['dokumen']) and empty($upload_error) and $_FILES['dokumen']['size'] > 0) {
                $uploaddir = KIRANA_PATH_FILE . $folder;
                if (!file_exists($uploaddir)) {
                    mkdir($uploaddir, 0777, true);
                }

                $config['upload_path'] = $uploaddir;
                $config['allowed_types'] = 'jpeg|jpg|png|pdf|dot|doc|docx|xls|xlsx|zip|rar|7z';
                $config['max_size'] = 5000;
                $config['remove_spaces'] = true;
                $config['overwrite'] = true;

                $filename = strtolower($id_spk . '_' . $spk->jenis_spk);
                $config['file_name'] = str_replace('.', '_', $filename);

                $this->load->library('upload', $config);

                $upload_error = null;

                if ($this->upload->do_upload('dokumen')) {
                    $upload_data = $this->upload->data();
                    $data_row['files'] = KIRANA_PATH_FILE_FOLDER . $folder . $upload_data['file_name'];
                    $data_row['tipe_files'] = substr($upload_data['file_ext'], 1);
                    $data_row['size_files'] = $upload_data['file_size'];
                } else {
                    $upload_error = $this->upload->display_errors('', '');
                }
            }

            $data_row = $this->dgeneral->basic_column('update', $data_row);
            $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $id_spk
                )
            ));

            //===========save spk log===========//
            $data_log = $this->order_log(
                array(
                    "datetime" => $datetime,
                    "id_spk" => $id_spk,
                    "id_jenis_spk" => $spk->id_jenis_spk,
                    "action" => 'finalspk',
                    "current_status" => $spk->status,
                )
            );
            $this->dgeneral->insert('tbl_leg_log_status', $data_log);

            $catatan = "Perjanjian tersebut telah <b>ditanda tangani</b> oleh kedua belah pihak dan di <b>SCAN</b>";

            if (isset($upload_error)) {
                $this->dgeneral->rollback_transaction();
                $msg = $upload_error;
                $sts = "NotOK";
                if (isset($data_row['files']))
                    unlink(KIRANA_PATH_ASSETS . $data_row['files']);
            } else if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Final Perjanjian berhasil ditambahkan";
                $sts = "OK";
                $data_spk = $this->dspk->get_data_spk(
                    array(
                        "connect" => FALSE,
                        "data" => "complete",
                        "id_spk" => $id_spk,
                        "user_level" => $this->data['level_user'],
                    )
                );
                $this->send_email(
                    array(
                        "post" => array(
                            "action" => "completed",
                            "note_spk" => $catatan
                        ),
                        "data_spk" => $data_spk
                    )
                );
                // $this->send_konfirmasi_final($id_spk);
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }


        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }
    private function save_cancel_spk($data)
    {
        $datetime     = date("Y-m-d H:i:s");
        $id_spk        = (isset($_POST['id_spk']) ? $this->generate->kirana_decrypt($_POST['id_spk']) : NULL);
        $alasan        = (isset($_POST['alasan']) ? $_POST['alasan'] : NULL);
        $keterangan    = (isset($_POST['keterangan']) ? $_POST['keterangan'] : NULL);
        $status_akhir    = (isset($_POST['status_akhir']) ? $_POST['status_akhir'] : NULL);

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $data_row_log = array(
            'id_spk'         => $id_spk,
            'id_oto_div'    => 0,
            'approve'         => 'n',
            'id_status'        => 17
        );

        $data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
        $this->dgeneral->insert("tbl_leg_approval", $data_row_log);

        $data_row = array(
            'id_status'        => 17,
            'alasan_cancel'    => $alasan,
            'keterangan_cancel'    => $keterangan,
            'status_akhir'    => $status_akhir
        );
        $data_row = $this->dgeneral->basic_column('update', $data_row);
        $result = $this->dgeneral->update('tbl_leg_spk', $data_row, array(
            array(
                'kolom' => 'id_spk',
                'value' => $id_spk
            )
        ));
        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil di cancel";
            $sts = "OK";
        }

        //sent email dari sini
        setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.kiranamegatara.com';
        $config['smtp_user'] = 'no-reply@kiranamegatara.com';
        $config['smtp_pass'] = '1234567890';
        $config['smtp_port'] = '465';
        $config['smtp_crypto'] = 'ssl';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = true;
        $config['mailtype'] = 'html';

        try {
            $spk = $this->dspk->get_spk(
                array(
                    'id_spk' => $id_spk,
                    'single_row' => true
                )
            );
            $data_email = $this->get_email('array', $spk->plant, $spk->id_status, $id_spk);    //17 status cancel
            foreach ($data_email as $dt) {
                $subject = 'Konfirmasi Cancel Perjanjian';
                $this->load->library('email', $config);
                $this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
                $this->email->subject($subject);
                // $this->email->to($dt->email);

                // $this->email->to('HENDRA.SITORUS@KIRANAMEGATARA.COM');   
                $this->email->to('FRANS.DARMAWAN@KIRANAMEGATARA.COM');
                // $this->email->to('AIRIZA.PERDANA@KIRANAMEGATARA.COM');  
                // $this->email->to('skygod.shohoku@gmail.com');  
                // $this->email->to('lukman.hakim@kiranamegatara.com');
                // $this->email->to('lnn.hakim@gmail.com');
                $message =    '<p><b>Kepada Bpk/Ibu ' . $dt->nama . '</b></p>';
                $message .=    '<p>Berikut adalah konfirmasi Cancel Perjanjian dari:</p>';
                $message .=    '<table>';
                $message .=    '	<tr><td>Plant</td><td>: ' . $spk->plant . '</td></tr>';
                $message .=    '	<tr><td>Jenis Perjanjian</td><td>: ' . $spk->jenis_spk . '</td></tr>';
                $message .=    '	<tr><td>Perihal</td><td>: ' . $spk->perihal . '</td></tr>';
                $message .=    '	<tr><td>SPPKP</td><td>: ' . $spk->SPPKP . '</td></tr>';
                $message .=    '</table>';
                // echo $message; 	
                $this->email->message($message);
                $this->email->send();
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            $sts = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);
            exit();
        }
        //sent email sampe sini

        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    private function get_komentar($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);
            $nik = $this->data['user']->nik;
            $jumlah_komentar  = (isset($_POST['jumlah_komentar']) ? $_POST['jumlah_komentar'] : 0);

            $this->general->connectDbPortal();


            $spk = $this->dspk->get_spk(
                array(
                    'id_spk' => $id,
                    'single_row' => true
                )
            );

            $result = $this->general->generate_encrypt_json(
                $this->dspk->get_komentar(array(
                    'id_spk' => $id
                )),
                array('id_spk')
            );

            foreach ($result as $komentar) {
                //update user_read tbl_leg_komentar
                if ($jumlah_komentar != 0) {
                    $arr_read      = explode('.', $komentar->user_read);
                    if (in_array($nik, $arr_read) == false) {
                        $user_read     = $komentar->user_read . '.' . $nik;
                        $data_update = array(
                            "user_read"    => $user_read
                        );
                        $data_update = $this->dgeneral->basic_column("update", $data_update);
                        $this->dgeneral->update("tbl_leg_komentar", $data_update, array(
                            array(
                                'kolom' => 'id_komentar',
                                'value' => $komentar->id_komentar
                            )
                        ));
                    }
                }


                if ($komentar->gender == "l") {
                    $image = base_url() . "assets/apps/img/avatar5.png";
                } else {
                    $image = base_url() . "assets/apps/img/avatar2.png";
                }

                if ($komentar->gambar) {
                    $data_image = "http://kiranaku.kiranamegatara.com/home/" . strtolower($komentar->gambar);
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $image = $data_image;
                    } else {
                        $links = explode("/", $komentar->gambar);
                        $data_image = "http://kiranaku.kiranamegatara.com/home/" . $links[0] . "/" . $links[1] . "/" . strtoupper($links[2]);
                        $headers = get_headers($data_image);
                        if ($headers[0] == "HTTP/1.1 200 OK") {
                            $image = $data_image;
                        }
                    }
                }

                $komentar->me = false;
                if ($nik == $komentar->nik)
                    $komentar->me = true;

                $komentar->gambar = $image;
                $komentar->komentar = nl2br($komentar->komentar);
            }

            return array('sts' => 'OK', 'data' => $result, 'spk' => $spk);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function save_komentar($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $id_spk = $this->generate->kirana_decrypt($data['id_spk']);
        $spk = $this->dspk->get_spk(
            array(
                'id_spk' => $id_spk,
                'single_row' => true
            )
        );

        if (isset($spk)) {

            $data_row = array(
                'jam' => date('H:i:s'),
                'user_input' => $this->data['user']->nik,
                'id_spk' => $id_spk,
                'komentar' => $data['komentar'],
                'user_read' => $this->data['user']->nik
            );

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $result = $this->dgeneral->insert('tbl_leg_komentar', $data_row);

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();

                return $this->get_komentar(array(
                    'id' => $data['id_spk']
                ));
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function get_template($id)
    {
        $this->load->library('zip');

        $jenis_spk = $this->dmaster->get_jenis_spk(
            array(
                'id_jenis_spk' => $id,
                'single_row' => true
            )
        );

        if (isset($jenis_spk)) {
            $templates = $this->dmaster->get_oto_jenis_spk(
                array(
                    'id_jenis_spk' => $id
                )
            );

            foreach ($templates as $template) {
                $this->zip->read_file(KIRANA_PATH_ASSETS . $template->files);
            }

            $this->zip->download($jenis_spk->jenis_spk . '-draft-template.zip');
        }
    }

    private function get_attachments($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $list = $this->dspk->get_data_spk(array(
                "connect" => false,
                "user_level" => $this->data['level_user'],
                'id_spk' => $id,
                'single_row' => true,
            ));
            $leg_level_id = $this->data['user']->leg_level_id;
            if ($data['tipe'] == 'template') {
                // set ambil template dari master atau table upload
                // jika sudah submit, ambil dari table upload
                $from_upload = false;
                if ($list->tanggal_submit || in_array($list->status, ['completed', 'cancelled'])) {
                    $from_upload = true;
                }

                $datas = $this->general->generate_encrypt_json(
                    $this->dspk->get_spk_template(
                        array(
                            'id_spk' => $list->id_spk,
                            'from_upload' => $from_upload,
                            'all' => ($from_upload) ? true : false
                        )
                    ),
                    array('id_oto_jenis', 'id_upload_template')
                );

                $list->id_spk = $this->generate->kirana_encrypt($list->id_spk);

                foreach ($datas as $template) {
                    if (isset($template->files))
                        $template->files = site_url('spk/view_file?file=' . $template->files);

                    if (empty($template->id_upload_template)) {
                        $uploadStatus = false;

                        $linkAttach = "";
                        if ($leg_level_id == 1 || $leg_level_id == 2) {
                            $linkAttach = "<li>"
                                . "<a href='javascript:void(0)' class='spk-upload' data-tipe='template' data-id_spk='"
                                . $list->id_spk . "' data-id_oto_jenis='"
                                . $template->id_oto_jenis . "'>Upload</a>"
                                . "</li>";
                        }
                    } else {
                        $uploadStatus = true;
                        $linkAttach = "<li><a href='" . $template->files . "' data-fancybox>View attachment</a></li>";
                    }

                    /*if ($leg_level_id == 1) {
                        $linkEdit = "";

                        if (in_array($list->id_status, array(1, 2, 3, 7, 9, 11, 13))) {
                            if (isset($template->id_upload_template))
                                $linkEdit = "<li>"
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='template' "
                                    . " data-id_spk='" . $list->id_spk . "'"
                                    . " data-id_oto_jenis='" . $template->id_oto_jenis . "'"
                                    . " data-id_upload='" . $template->id_upload_template . "'"
                                    . ">Edit</a>"
                                    . "</li>";
                        }

                        $template->links = $linkAttach . $linkEdit;
                    } else if ($leg_level_id == 2) {
                        $linkEdit = "";
                        // if ($list->id_status == 7) { 
                        if (in_array($list->id_status, array(1, 2, 7))) {
                            if (isset($template->id_upload_template))
                                $linkEdit = "<li>"
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='template' "
                                    . " data-id_spk='" . $list->id_spk . "'"
                                    . " data-id_oto_jenis='" . $template->id_oto_jenis . "'"
                                    . " data-id_upload='" . $template->id_upload_template . "'"
                                    . ">Edit</a>"
                                    . "</li>";
                        }

                        $template->links = $linkAttach . $linkEdit;
                    } else {
                        $linkEdit = "";
                        if (in_array($list->id_status, array(1, 2, 7))) {
                            if (isset($template->id_upload_template))
                                $linkEdit = "<li>"
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='template' "
                                    . " data-id_spk='" . $list->id_spk . "'"
                                    . " data-id_oto_jenis='" . $template->id_oto_jenis . "'"
                                    . " data-id_upload='" . $template->id_upload_template . "'"
                                    . ">Edit</a>"
                                    . "</li>";
                        }

                        $template->links = $linkAttach . $linkEdit;
                    }*/
                    $linkEdit = "";
                    if (
                        ($list->akses == 1)
                        ||
                        (in_array(1, $this->data['level_user']) && !in_array($list->status, ['finaldraft', 'completed', 'cancelled']))
                    ) {
                        $linkEdit = "<li>"
                            . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='template' "
                            . " data-id_spk='" . $list->id_spk . "'"
                            . " data-id_oto_jenis='" . $template->id_oto_jenis . "'"
                            . " data-id_upload='" . $template->id_upload_template . "'"
                            . ">Edit</a>"
                            . "</li>";
                    }
                    $template->links = $linkAttach . $linkEdit;
                    $template->uploadStatus = $uploadStatus;
                }
            } else if ($data['tipe'] == 'vendor') {
                $datas = $this->general->generate_encrypt_json(
                    $this->dspk->get_spk_vendor(
                        array(
                            'id_spk' => $list->id_spk
                        )
                    ),
                    array('id_oto_vendor', 'id_upload_vendor')
                );

                $list->id_spk = $this->generate->kirana_encrypt($list->id_spk);

                foreach ($datas as $vendor) {
                    if (isset($vendor->files))
                        $vendor->files = site_url('spk/view_file?file=' . $vendor->files);

                    if (empty($vendor->id_upload_vendor)) {
                        $uploadStatus = false;
                        $linkAttach = "";
                        if ($leg_level_id == 1 or $leg_level_id == 2)
                            $linkAttach = "<li>"
                                . "<a href='javascript:void(0)' class='spk-upload' data-tipe='vendor' data-id_spk='"
                                . $list->id_spk . "' data-id_oto_vendor='"
                                . $vendor->id_oto_vendor . "'>Upload</a>"
                                . "</li>";
                    } else {
                        $uploadStatus = true;
                        $linkAttach = "<li><a href='" . $vendor->files . "' data-fancybox>View attachment</a></li>";
                    }

                    if ($leg_level_id == 1) {
                        $linkEdit = "";
                        if (in_array($list->id_status, array(1, 2, 3, 7, 9, 11, 13))) {
                            if (isset($vendor->id_upload_vendor))
                                $linkEdit = "<li>"
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='vendor' "
                                    . " data-id_spk='" . $list->id_spk . "'"
                                    . " data-id_oto_vendor='" . $vendor->id_oto_vendor . "'"
                                    . " data-id_upload='" . $vendor->id_upload_vendor . "'>Edit</a>"
                                    . "</li>";
                        }

                        $vendor->links = $linkAttach . $linkEdit;
                    } else if ($leg_level_id == 2) {
                        $linkEdit = "";
                        // if (in_array($list->id_status, array(7))) {
                        if (in_array($list->id_status, array(1, 2, 7))) {
                            if (isset($vendor->id_upload_vendor))
                                $linkEdit = "<li>"
                                    . "<a href='javascript:void(0)' class='spk-edit-upload' data-tipe='vendor' "
                                    . " data-id_spk='" . $list->id_spk . "'"
                                    . " data-id_oto_vendor='" . $vendor->id_oto_vendor . "'"
                                    . " data-id_upload='" . $vendor->id_upload_vendor . "'>Edit</a>"
                                    . "</li>";
                        }

                        $vendor->links = $linkAttach . $linkEdit;
                    } else
                        $vendor->links = $linkAttach;

                    $vendor->uploadStatus = $uploadStatus;
                }
            } else if ($data['tipe'] == 'vendor_dokumen') {
                /*==== by cut off
                //periode cut off master vendor
                $tanggal_buat_spk = date('Y-m-d', strtotime($list->tanggal_buat));
                if ($tanggal_buat_spk <= '2021-10-05') {    //dari matrix vendor
                    $datas = $this->general->generate_encrypt_json(
                        $this->dspk->get_spk_vendor_dokumen(
                            array(
                                'id_spk'     => $list->id_spk,
                                'lifnr'     => $list->lifnr
                            )
                        ),
                        array('id_oto_vendor', 'id_upload_vendor')
                    );
                } else {
                    // $vendor		= $this->dmaster->get_data_master_vendor("open", '0000717374');
                    $vendor        = $this->dmaster->get_data_master_vendor("open", $list->lifnr);
                    $datas         = $this->dspk->get_data_jenis_vendor_dokumen("open", NULL, 'n', 'n', $vendor[0]->id_jenis_vendor, $vendor[0]->id_data, NULL);
                }
                === */

                $datas = $this->general->generate_encrypt_json(
                    $this->dspk->get_spk_vendor_dokumen(
                        array(
                            'id_spk'     => $list->id_spk,
                            'lifnr'     => $list->lifnr
                        )
                    ),
                    array('id_oto_vendor', 'id_upload_vendor')
                );

                if ($list->lifnr) {
                    $vendor        = $this->dmaster->get_data_master_vendor("open", $list->lifnr);
                    if ($vendor) {
                        $data_baru         = $this->dspk->get_data_jenis_vendor_dokumen("open", NULL, 'n', 'n', $vendor[0]->id_jenis_vendor, $vendor[0]->id_data, NULL);
                        if (!empty($data_baru)) {
                            $datas = $data_baru;
                        }
                    }
                }

                foreach ($datas as $vendor) {
                    $vendor->files = site_url('spk/view_file?file=' . $vendor->link);
                    if ($vendor->link != null) {
                        $vendor->links = "<li><a href='" . $vendor->files . "' data-fancybox>View attachment</a></li>";
                        $vendor->uploadStatus = true;
                    } else {
                        $vendor->links = "";
                        $vendor->uploadStatus = false;
                    }
                    //lha
                    // if (($vendor->link != null) or ($tanggal_buat_spk >= '2021-10-05')) {
                    if (($vendor->link != null) or ($list->lifnr)) {
                        $vendor->data_lampiran = 'show';
                    } else {
                        $vendor->data_lampiran = 'hide';
                    }
                }
            } else {
                /*==== by cut off
                //periode cut off master vendor
                $tanggal_buat_spk = date('Y-m-d', strtotime($list->tanggal_buat));
                if ($tanggal_buat_spk <= '2021-10-05') {    //dari matrix vendor
                    $matrix     = $this->dmaster->get_data_matrix("open", $list->lifnr);
                    $datas = $this->general->generate_encrypt_json(
                        $this->dspk->get_spk_vendor_dokumen_kualifikasi(
                            array(
                                'id_spk'         => $list->id_spk,
                                'id_jenis_spk'     => $list->id_jenis_spk,
                                'lifnr'         => $list->lifnr,
                                'id_kualifikasi' => $list->id_kualifikasi,
                                'kualifikasi'    => $matrix[0]->kualifikasi
                            )
                        ),
                        array('id_kualifikasi_spk')
                    );
                } else {    //dari master vendor
                    $vendor        = $this->dmaster->get_data_master_vendor("open", $list->lifnr);
                    // $vendor		= $this->dmaster->get_data_master_vendor("open", '0000717374');
                    $datas = $this->general->generate_encrypt_json(
                        $this->dspk->get_spk_vendor_dokumen_kualifikasi_vendor(
                            array(
                                'id_data'         => $vendor[0]->id_data,
                                'kualifikasi'    => $vendor[0]->kualifikasi_spk
                            )
                        ),
                        array('id_master_dokumen')
                    );
                }
                === */

                $matrix     = $this->dmaster->get_data_matrix("open", $list->lifnr);
                $datas = $this->general->generate_encrypt_json(
                    $this->dspk->get_spk_vendor_dokumen_kualifikasi(
                        array(
                            'id_spk'         => $list->id_spk,
                            'id_jenis_spk'     => $list->id_jenis_spk,
                            'lifnr'         => $list->lifnr,
                            'id_kualifikasi' => $list->id_kualifikasi,
                            'kualifikasi'    => $matrix[0]->kualifikasi
                        )
                    ),
                    array('id_kualifikasi_spk')
                );

                if ($list->lifnr) {
                    $vendor        = $this->dmaster->get_data_master_vendor("open", $list->lifnr);
                    if ($vendor) {
                        $datas = $this->general->generate_encrypt_json(
                            $this->dspk->get_spk_vendor_dokumen_kualifikasi_vendor(
                                array(
                                    'id_data'         => $vendor[0]->id_data,
                                    'kualifikasi'    => $vendor[0]->kualifikasi_spk
                                )
                            ),
                            array('id_master_dokumen')
                        );
                    }
                }
                
                foreach ($datas as $vendor) {
                    $vendor->files = site_url('spk/view_file?file=' . $vendor->link);
                    if ($vendor->link != null) {
                        $vendor->links = "<li><a href='" . $vendor->files . "' data-fancybox>View attachment</a></li>";
                        $vendor->uploadStatus = true;
                    } else {
                        $vendor->links = NULL;
                        $vendor->uploadStatus = false;
                    }
                }
            }

            $list->table_dokumen = $this->get_spk_table_dokumen($list->id_spk, $datas, $data['tipe']);

            return array('sts' => 'OK', 'data' => $list->table_dokumen);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    public function view_file()
    {
        $file = $this->input->get('file');
        if (isset($file) && !empty($file)) {
            $data_image = site_url('assets/' . $file);
            $headers = get_headers($data_image);
            if ($headers[0] == "HTTP/1.1 200 OK") {
                $image = $data_image;
                redirect($image);
            } else {
                $data_image = "http://10.0.0.18/home/" . $file;
                $headers = get_headers($data_image);
                if ($headers[0] == "HTTP/1.1 200 OK") {
                    $image = $data_image;
                    redirect($image);
                } else
                    show_404();
            }
        } else
            show_404();
    }
    private function get_email($array = NULL, $plant = NULL, $id_status = NULL, $id_spk = NULL)
    {
        $email    = $this->dspk->get_data_email("open", $plant, $id_status, $id_spk);
        if ($array) {
            return $email;
        } else {
            echo json_encode($email);
        }
    }

    private function order_log($param)
    {
        // $post = $param['post'];
        $datetime = $param['datetime'];
        $current_status = $param['current_status'];
        // $plant = $post['plant'];
        $login = base64_decode($this->session->userdata("-id_user-"));
        $comment = isset($param['note_spk']) ? $param['note_spk'] : '';

        $last_action = $this->dspk->get_spk_last_action(
            array(
                "connect" => FALSE,
                "id_spk" => $param['id_spk'],
                "id_jenis_spk" => $param['id_jenis_spk']
            )
        );
        $level[] = $this->data['role_user'][0]->level;

        if ($last_action && !in_array($param['action'], ["finaldraft", "finalspk", "cancel"])) {
            if (($current_status !== NULL && in_array($current_status, $level) == true)) {
                $status = $current_status;
            } else {
                $msg = "Anda tidak memiliki akses untuk Perjanjian ini";
                $sts = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }
        } else {
            //==first submit==//
            // $status = in_array($current_status, $level) == true ? $current_status : NULL;
            $status = in_array($current_status, $level) == true ? $current_status : $level[0];
        }

        $data_log = array(
            "id_spk" => $param['id_spk'],
            "tgl_status" => $datetime,
            "status" => $status,
            "action" => $param['action'],
            "comment" => $comment,
            "id_divisi" => base64_decode($this->session->userdata('-id_divisi-'))
        );
        $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);

        return $data_log;
    }

    private function save_approval()
    {
        $datetime = date("Y-m-d H:i:s");
        $post = $this->input->post(NULL, TRUE);

        //=============update data detail=============//
        $action = $post['action'];
        $id_spk = $this->generate->kirana_decrypt($_POST['id_spk']);

        $send_email = true;

        $data_spk = $this->dspk->get_data_spk(
            array(
                "connect" => TRUE,
                "data" => "complete",
                "id_spk" => $id_spk,
                "user_level" => $this->data['level_user'],
            )
        );

        $last_action = $this->dspk->get_spk_last_action(
            array(
                "connect" => TRUE,
                "id_spk" => $id_spk,
            )
        );

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        //========Update data status approval========//
        $status = $this->generate_status(
            array(
                "connect" => FALSE,
                "id_spk" => $id_spk,
                "action" => $action,
                "post" => $post
            )
        );
        $data_row = array(
            "status" => $status
        );

        if ($data_spk->status == $status) {
            $send_email = false;
        }

        #jika if_approvenya adalah role paralel
        #set tanggal paralel di tabel spk
        if ($data_spk->if_approve_paralel == 1)
            $data_row['tanggal_paralel'] = $datetime;

        $data_row_add = array();
        if ($action == "submit") {
            //========Cek kelengkapan dokumen========//
            $cek_dokumen = $this->get_submit_spk(array(
                "id_spk" => $id_spk
            ));

            if ($cek_dokumen['sts'] !== "OK") {
                echo json_encode($cek_dokumen);
                exit();
            }

            $data_row_add = array(
                "id_status" => 2
            );
        } else if ($action == "approve") {
            #approve by Legal
            if ($status == 1) {
                $data_row_add = array(
                    "id_status" => 1, //3: approved, 1: waiting confirmation
                    "tanggal_approve" => date('Y-m-d')
                );
            }

            #approve by divisi if confirmed
            if ($status == "confirmed") {
                $data_row_add = array(
                    "id_status" => 9, //Confirmed Div Teknis
                );
            }
        }

        $data_row = $this->dgeneral->basic_column("update", array_merge($data_row, $data_row_add), $datetime);
        $this->dgeneral->update('tbl_leg_spk', $data_row, array(
            array(
                'kolom' => 'id_spk',
                'value' => $id_spk
            )
        ));
        //===========================================//

        //========save data log========//
        $data_log = $this->order_log(
            array(
                "datetime" => $datetime,
                "id_spk" => $id_spk,
                "id_jenis_spk" => $data_spk->id_jenis_spk,
                "action" => $action,
                "current_status" => $data_spk->status,
                "note_spk" => $post['note_spk']
            )
        );
        $this->dgeneral->insert('tbl_leg_log_status', $data_log);
        //============================//

        //=====================================//
        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
            $data_spk = $this->dspk->get_data_spk(
                array(
                    "connect" => FALSE,
                    "data" => "complete",
                    "id_spk" => $id_spk,
                    "user_level" => $this->data['level_user'],
                )
            );
            if ($send_email) {
                $this->send_email(
                    array(
                        "post" => $post,
                        "data_spk" => $data_spk
                    )
                );
            }
        }
        //============================================//

        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg, 'result' => $data_row);
        echo json_encode($return);
        exit();
    }

    private function generate_status($param = NULL)
    {
        // $post = $param['post'];
        $current_status = [];
        if (!isset($param['new'])) {
            $param_status = array(
                "connect" => $param['connect'],
                "id_spk" => $param['id_spk'],
                "user_level" => $this->data['level_user'],
            );
            $current_status = $this->dspk->get_data_spk($param_status);
        }

        //SUBMIT NEW
        if (empty($current_status)) {
            $data_role = $this->dspk->get_data_role_user(array(
                "connect" => false,
                "nik" => base64_decode($this->session->userdata("-nik-")),
                "posst" => base64_decode($this->session->userdata("-posst-")),
                "akses_buat" => true
            ));
            $current_status = $data_role[0];
        }

        //GET ROLE LEGAL
        $legal = $this->dmaster->get_data_role(
            array(
                "connect" => FALSE,
                "single_row" => TRUE,
                "nama_role" => "Legal HO"
            )
        );

        if (isset($param['action'])) {
            switch ($param['action']) {
                case 'create':
                case 'edit':
                    $status = $current_status->level;
                    break;
                case 'submit':
                    // $status = $legal->level; //Legal
                    // break;
                case 'approve':
                    $status = $current_status->if_approve;


                    #approve oleh role paralel
                    //=====check all paralel approve=====//
                    if ($current_status->paralel) {
                        $check_paralel = $this->check_complete_approval(
                            array(
                                "connect" => $param['connect'],
                                "id_spk" => $param['id_spk'],
                                "id_jenis_spk" => $current_status->id_jenis_spk,
                                "level" => $current_status->status,
                                "jumlah_divisi_terkait" => $current_status->jumlah_divisi_terkait
                            )
                        );
                        if (!$check_paralel) {
                            $status = $current_status->level;
                        }
                    }
                    //==============================//
                    break;
                case 'decline':
                    $status = $current_status->if_decline;

                    if ($current_status->if_decline === "owner")
                        $status = $current_status->level_owner;

                    break;
                case 'cancel':
                    $status = 'cancelled';
                    break;
                case 'finaldraft':
                    $status = 'finaldraft';
                    break;
                case 'completed':
                    $status = "completed";
                    break;
                default:
                    $status = $current_status + 1;
                    break;
            }

            return $status;
        }
    }

    private function check_complete_approval($param = NULL)
    {
        $data_approval = $this->dspk->get_data_approval(
            array(
                "connect" => $param['connect'],
                "id_spk" => $param['id_spk'],
                "id_jenis_spk" => $param['id_jenis_spk'],
                "level" => $param['level'],
                "single_row" => TRUE,
            )
        );

        $jumlah_approval = $data_approval->jumlah_approval + 1;

        if ($param['jumlah_divisi_terkait'] <= $jumlah_approval) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function generate_email_message($param = NULL)
    {
        $message = "<html>
                        <body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
                        <center style='width: 100%;'>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                Notifikasi Email Perjanjian
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
                                            <h1 style='margin-bottom: 0;'>Perjanjian LEGAL</h1>
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
                                                        ";
        if (!$param['nama_to']) {
            $param['nama_to'] = 'Bapak & Ibu';
        }
        $message .= "<p><strong>Kepada :<br><br> " . $param['nama_to'] . "</strong></p>";
        $message .= "<p>Email ini menandakan bahwa ada Perjanjian yang membutuhkan perhatian anda.</p>";
        $message .= "<table style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
                                                <tr>
                                                    <td>Jenis Perjanjian</td>
                                                    <td>:</td>";
        $message .= "<td>" . $param['jenis_spk'] . "</td>"; //Jenis Perjanjian
        $message .= "</tr>
                                                <tr>
                                                    <td>Perihal</td>
                                                    <td>:</td>";
        $message .= "<td>" . $param['perihal'] . "</td>"; //Perihal
        $message .= "</tr>
                                                <tr>
                                                    <td>Pabrik</td>
                                                    <td>:</td>";
        $message .= "<td>" . $param['pabrik'] . "</td>"; //Perihal
        $message .= "</tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>:</td>";
        $message .= "<td><b>" . $param['status'] . "</b></td>"; // STATUS
        $message .= "</tr>
                                                <tr>
                                                    <td>Oleh</td>
                                                    <td>:</td>";
        $message .= "<td>" . $param['oleh'] . "</td>"; //OLEH atau LAST ACTION PROJECT
        $message .= "</tr>
                                                <tr>
                                                    <td>Tanggal</td>
                                                    <td>:</td>";
        $message .= "<td>" . strftime('%A, %d %B %Y') . "</td>"; //TANGGAL KIRIM EMAIL
        $message .= "</tr>
                                                <tr>
                                                    <td style='vertical-align: top;'>Catatan</td>
                                                    <td style='vertical-align: top;'>:</td>";
        if (!$param['comment']) {
            $param['comment'] = '-';
        }
        $message .= "<td>" . $param['comment'] . "</td>"; // COMMENT Perjanjian
        $message .= "</tr>
                                </table>
                                <p>Selanjutnya anda dapat melakukan review pada Perjanjian tersebut melalui Aplikasi Perjanjian Online di Portal Kiranaku.</p>
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
                            <td align='center' style='padding-bottom: 20px;'>";
        $message .= "<a href='" . base_url() . 'spk/spk/detail/' . $param['id_spk'] . "' style='
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
                                        border-radius: 4px;'>Login</a>"; // LINK PORTAL KIRANAKU
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

        return $message;
    }

    private function send_email($param = NULL)
    {
        $post = (object) $param['post'];
        $data_spk = $param['data_spk'];
        $action = in_array($data_spk->status, ["confirmed", "finaldraft", "completed"]) ? $data_spk->status : $post->action;
        $attachment = (isset($param['attachment']) && $param['attachment'] !== NULL) ? $param['attachment'] : NULL;

        switch ($action) {
            case 'submit':
                $status = "Submit";
                break;
            case 'approve':
                $status = "Approved";
                break;
            case 'edit':
                $status = "Edited & Approved";
                break;
            case 'decline':
                $status = "<span style='color:red;'>Declined</span>";
                break;
            case 'drop':
                $status = "Dropped";
                break;
            case 'cancelled':
                $status = "Cancelled";
                break;
            case 'deleted':
                $status = "Deleted";
                break;
            case 'confirmed':
                $status = "Confirmed";
                break;
            case 'finaldraft':
                $status = "Final Draft";
                break;
            case 'completed':
                $status = "Completed";
                break;
            default:
                $status = $action;
                break;
        }

        if (isset($post->catatan))
            $comment = $post->catatan;
        else
            $comment = $post->note_spk;

        $data_recipient = $this->dspk->get_email_recipient(
            array(
                "connect" => FALSE,
                "id_spk" => $data_spk->id_spk
            )
        );

        $email_cc = array();
        $email_to = array();
        $email_bcc = array();
        foreach ($data_recipient as $dt) {
            if ($dt->nilai == 'cc') {
                $email_cc[] = ENVIRONMENT == 'development' ? "benazi.bahari@kiranamegatara.com" : $dt->email;
            } else {
                $email_to[] = ENVIRONMENT == 'development' ? "airiza.perdana@kiranamegatara.com" : $dt->email;
                if ($dt->nama !== "" && $dt->gender !== "") {
                    $nama_to[] = $dt->gender . " " . ucwords(strtolower($dt->nama)) . "<br>";
                }
            }
        }
        if (ENVIRONMENT == 'development')
            $email_bcc[] = "benazi.bahari@kiranamegatara.com";

        if (empty($email_to))
            $email_to = $email_cc;

        $message = $this->generate_email_message(
            array(
                "nama_to" => empty($nama_to) ? "" : implode("", $nama_to),
                "jenis_spk" => $data_spk->jenis_spk,
                "perihal" => $data_spk->perihal,
                "pabrik" => $data_spk->plant,
                "status" => $status,
                "oleh" => ucwords(strtolower(base64_decode($this->session->userdata("-nama-")))),
                "comment" => $comment,
                "id_spk" => $this->generate->kirana_encrypt($data_spk->id_spk),
            )
        );

        $this->general->send_email_new(
            array(
                "subject" => "Notifikasi Status Perjanjian",
                "from_alias" => "Perjanjian Legal",
                "to" => $email_to,
                "cc" => $email_cc,
                "bcc" => $email_bcc,
                "message" => $message,
                "attachment" => $attachment
            )
        );

        return true;
    }

    private function get_data_divisi_terkait($param = NULL)
    {
        $param_spk = array(
            "connect" => $param['connect'],
            "id_spk" => $param['id_spk'],
            "user_level" => $this->data['level_user'],
        );
        $data_spk = $this->dspk->get_data_spk($param_spk);

        $param_divisi = array(
            "connect" => $param['connect'],
            "id_spk" => $param['id_spk'],
            "IN_divisi" => explode(",", $data_spk->divisi_terkait),
            "status_spk" => $data_spk->status,
            "tanggal_paralel" => $data_spk->tanggal_paralel,
        );

        $result = $this->dspk->get_data_divisi_terkait($param_divisi);

        return $result;
    }

    /** generate status existing SPK */
    private function generate_spk($param = NULL)
    {
        $this->general->connectDbPortal();
        $this->db->select('vw_leg_spk.*');
        $this->db->select('tbl_user.id_divisi');
        $this->db->from('vw_leg_spk');
        $this->db->join('tbl_user', 'vw_leg_spk.login_buat = tbl_user.id_user', 'inner');
        $this->db->where_not_in('vw_leg_spk.id_status', [12, 17]);
        $this->db->where('vw_leg_spk.status IS NULL');
        // $this->db->where('vw_leg_spk.id_spk', 851);
        $query = $this->db->get();
        $list_spk = $result = $query->result();
        // $this->general->closeDb();

        $datetime    = date("Y-m-d H:i:s");

        $this->dgeneral->begin_transaction();

        foreach ($list_spk as $spk) {
            $tanggal_paralel = null;

            /** create log status */
            //log create
            $data_log = array(
                "id_spk" => $spk->id_spk,
                "tgl_status" => $spk->tanggal_buat,
                "status" => 2,
                "action" => 'create',
                "comment" => '',
                "login_edit" => $spk->login_buat,
                "tanggal_edit" => $datetime,
                "id_divisi" => $spk->id_divisi
            );
            $this->dgeneral->insert('tbl_leg_log_status', $data_log);

            //log submit
            if (in_array($spk->id_status, ['2', '3', '1', '9', '11'])) {
                $data_log = array(
                    "id_spk" => $spk->id_spk,
                    "tgl_status" => date('Y-m-d H:i:s', strtotime($spk->tanggal_buat) + 1), //$spk->tanggal_buat,
                    "status" => 2,
                    "action" => 'submit',
                    "comment" => '',
                    "login_edit" => $spk->login_buat,
                    "tanggal_edit" => $datetime,
                    "id_divisi" => $spk->id_divisi
                );
                $this->dgeneral->insert('tbl_leg_log_status', $data_log);
            }

            //log approve legal ho
            if (in_array($spk->id_status, ['3', '1', '9', '11'])) {
                $data_log = array(
                    "id_spk" => $spk->id_spk,
                    "tgl_status" => ($spk->tanggal_approve) ? $spk->tanggal_approve : $spk->tanggal_buat,
                    "status" => 1,
                    "action" => 'approve',
                    "comment" => '',
                    "login_edit" => 7150, //maisitah
                    "tanggal_edit" => $datetime,
                    "id_divisi" => 1561
                );
                $this->dgeneral->insert('tbl_leg_log_status', $data_log);
                $tanggal_paralel = ($spk->tanggal_approve) ? $spk->tanggal_approve : $spk->tanggal_buat;
            }

            //log approve divisi
            if (in_array($spk->id_status, ['1', '9', '11'])) {
                $approved = $this->dspk->get_spk_divisi(
                    array(
                        'id_spk' => $spk->id_spk,
                        // 'approve' => 'y'
                    )
                );

                foreach ($approved as $appdiv) {
                    if ($appdiv->approve === 'y') {
                        //log approve legal ho
                        switch ($appdiv->id_divisi) {
                            case '3': //TAX
                                $id_divisi = 762;
                                break;
                            case '4': //Procurement
                                $id_divisi = 2437;
                                break;
                            case '5': //Fincon
                                $id_divisi = 751;
                                break;
                            case '6': //Sourcing
                                $id_divisi = 756;
                                break;
                            case '7': //FO
                                $id_divisi = 758;
                                break;
                            case '11': //HRGA
                                $id_divisi = 766;
                                break;

                            default:
                                $id_divisi = null;
                                break;
                        }
                        $data_log = array(
                            "id_spk" => $spk->id_spk,
                            "tgl_status" => $appdiv->tanggal_approve,
                            "status" => 3,
                            "action" => 'approve',
                            "comment" => '',
                            "login_edit" => $appdiv->login_edit,
                            "tanggal_edit" => $datetime,
                            "id_divisi" => $id_divisi
                        );
                        $this->dgeneral->insert('tbl_leg_log_status', $data_log);
                    }
                }
            }

            //log finaldraft
            if (in_array($spk->id_status, ['11'])) {
                if ($spk->files_1) {
                    $data_log = array(
                        "id_spk" => $spk->id_spk,
                        "tgl_status" => ($spk->tanggal_final) ? $spk->tanggal_final : $spk->tanggal_edit,
                        "status" => 1,
                        "action" => 'finaldraft',
                        "comment" => '',
                        "login_edit" => 7150, //maisitah
                        "tanggal_edit" => $datetime,
                        "id_divisi" => 1561
                    );
                    $this->dgeneral->insert('tbl_leg_log_status', $data_log);
                }
            }

            /** set status */
            switch ($spk->id_status) {
                case '1':
                    $status = 3; //divisi terkait
                    break;
                case '2':
                    $status = 1; //legal
                    break;
                case '3':
                    $status = 3; //divisi terkait
                    break;
                case '7':
                case '13':
                    $status = 2; //pic legal pabrik
                    break;
                case '9':
                case '11':
                    $status = 'finaldraft'; //Final Draft
                    break;
                case '4':
                    $status = 'cancelled'; //Cancel
                    break;
                default:
                    $status = NULL;
                    break;
            }

            $data_row = array(
                "status" => $status,
                "tanggal_paralel" => $tanggal_paralel
            );
            $this->dgeneral->update('tbl_leg_spk', $data_row, array(
                array(
                    'kolom' => 'id_spk',
                    'value' => $spk->id_spk
                )
            ));
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);
        exit();
    }
}
