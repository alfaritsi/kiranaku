<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Management Reporting App Settings
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */
Class Reports extends MX_Controller
{

    private $data;

    private $functions = [
        "fop004" => "FOP004",
        "fop004_level_2" => "FOP004 Level 2",
        "pur006" => "PUR006",
    ];

    public function __construct()
    {
        parent::__construct();
//        $this->general->check_access();
        $this->data['module'] = "Management Reporting";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dmrreports');
    }

    public function index()
    {
        $this->load->library('mrapp_functions');

        $this->data['title'] = "Reports";
        $this->data['title_form'] = "Report";
        $this->data['datas'] = $this->dmrreports->get_reports();
        $this->data['types'] = $this->dmrreports->get_type();
        $this->data['functions'] = $this->mrapp_functions->getFunctionList();
//        $this->data['functions'] = $this->functions;

        $this->load->view('reports', $this->data);
    }

    public function types()
    {
        $this->data['title'] = "Reports Types";
        $this->data['title_form'] = "report type";
        $this->data['datas'] = $this->dmrreports->get_type();
        $this->load->view('types', $this->data);

    }

    public function parameters()
    {
        $id_report = $this->input->post('id_report');

        if (isset($id_report)) {

            $report = $this->get_reports($id_report);
            if ($report) {
                $this->data['title'] = "Report Parameter - [" . $report[0]->kode_report . "] " . $report[0]->nama_report . "";
                $this->data['title_form'] = "parameter";
                $this->data['id_report'] = $id_report;
                $this->data['report'] = $report;
                $this->data['datas'] = $this->dmrreports->get_parameter($report[0]->id_report);
                $this->load->view('parameters', $this->data);
            } else {
                redirect(base_url('mrapp/reports'));
            }
        } else {
            redirect(base_url('mrapp/reports'));
        }
    }

    public function thresholds()
    {
        $id_report = $this->input->post('id_report');

        if (isset($id_report)) {

            $report = $this->get_reports($id_report);
            if ($report) {
                $this->load->library('Mrapp_format');
                $this->data['title'] = "Report Thresholds - [" . $report[0]->kode_report . "] " . $report[0]->nama_report . "";
                $this->data['title_form'] = "threshold";
                $this->data['id_report'] = $id_report;
                $this->data['report'] = $report;
                $this->data['datas'] = $this->dmrreports->get_threshold($report[0]->id_report);
                $this->load->view('thresholds', $this->data);
            } else {
                redirect(base_url('mrapp/reports'));
            }
        } else {
            redirect(base_url('mrapp/reports'));
        }
    }

    public function links()
    {
        $id_report = $this->input->post('id_report');

        if (isset($id_report)) {

            $report = $this->get_reports($id_report);
            if ($report) {
                $this->data['title'] = "Report Links - [" . $report[0]->kode_report . "] " . $report[0]->nama_report . "";
                $this->data['title_form'] = "link";
                $this->data['id_report'] = $id_report;
                $this->data['report'] = $report;
                $this->data['reports'] = $this->dmrreports->get_link_report_options($id_report);
                $this->data['datas'] = $this->dmrreports->get_link($report[0]->id_report);
                $this->load->view('links', $this->data);
            } else {
                redirect(base_url('mrapp/reports'));
            }
        } else {
            redirect(base_url('mrapp/reports'));
        }


    }

    public function subscribers()
    {
        $id_report = $this->input->post('id_report');

        if (isset($id_report)) {

            $report = $this->get_reports($id_report);
            if ($report) {
                $this->data['title'] = "Report Subscribers - [" . $report[0]->kode_report . "] " . $report[0]->nama_report . "";
                $this->data['title_form'] = "subscriber";
                $this->data['id_report'] = $id_report;
                $this->data['report'] = $report;
                $this->data['roles'] = $this->dmrreports->get_subscriber_role_options($id_report);
                $this->data['datas'] = $this->dmrreports->get_subscriber($report[0]->id_report);
                $this->load->view('subscribers', $this->data);
            } else {
                redirect(base_url('mrapp/reports'));
            }
        } else {
            redirect(base_url('mrapp/reports'));
        }


    }

    public function save($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'report':
                $return = $this->save_report($data);
                break;
            case 'type':
                $return = $this->save_type($data);
                break;
            case 'parameter':
                $return = $this->save_parameter($data);
                break;
            case 'link':
                $return = $this->save_link($data);
                break;
            case 'subscriber':
                $return = $this->save_subscriber($data);
                break;
            case 'threshold':
                $return = $this->save_threshold($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    public function get($param)
    {
        switch ($param) {
            case 'report':
                $return = $this->get_reports($_POST['id_report']);
                break;
            case 'type':
                $return = $this->get_type($_POST['id_type']);
                break;
            case 'parameter':
                $return = $this->get_parameter($_POST['id']);
                break;
            case 'link':
                $return = $this->get_link($_POST['id']);
                break;
            case 'subscriber':
                $return = $this->get_subscriber($_POST['id']);
                break;
            case 'threshold':
                $return = $this->get_threshold($_POST['id']);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    public function delete($param)
    {
        $data = $_POST;
        $id = $this->generate->kirana_decrypt($data['id']);
        unset($data['id']);
        switch ($param) {
            case 'report':
                $return = $this->dmrreports->delete_report($id);
                break;
            case 'type':
                $return = $this->dmrreports->delete_type($id);
                break;
            case 'parameter':
                $return = $this->dmrreports->delete_parameter($id);
                break;
            case 'link':
                $id_report = $this->generate->kirana_decrypt($data['id_report']);
                $return = $this->dmrreports->delete_link($id_report,$id);
                break;
            case 'subscriber':
                $id_report = $this->generate->kirana_decrypt($data['id_report']);
                $return = $this->dmrreports->delete_subscriber($id_report,$id);
                break;
            case 'threshold':
                $id_report = $this->generate->kirana_decrypt($data['id_report']);
                $return = $this->dmrreports->delete_threshold($id_report,$id);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function set($param,$action)
    {
        $data = $_POST;
        $id = $this->generate->kirana_decrypt($data['id']);
        unset($data['id']);
        $this->general->connectDbPortal();
        switch ($param) {
            case 'report':
                $return = $this->general->set($action,"tbl_mrapp_reports",[
                    [
                        "kolom" => "id_report",
                        "value" => $id
                    ]
                ]);
                break;
            case 'type':
                $return = $this->general->set($action,"tbl_mrapp_reports_types",[
                    [
                        "kolom" => "id_report_type",
                        "value" => $id
                    ]
                ]);
                break;
            case 'parameter':
                $return = $this->general->set($action,"tbl_mrapp_reports_parameters",[
                    [
                        "kolom" => "id_report_parameter",
                        "value" => $id
                    ]
                ]);
                break;
            case 'link':
                $id_report = $this->generate->kirana_decrypt($data['id_report']);
                $return = $this->general->set($action,"tbl_mrapp_reports_links",[
                    [
                        "kolom" => "id_report_link",
                        "value" => $id
                    ],
                    [
                        "kolom" => "id_report",
                        "value" => $id_report
                    ]
                ]);
                break;
            case 'subscriber':
                $id_report = $this->generate->kirana_decrypt($data['id_report']);
                $return = $this->general->set($action,"tbl_mrapp_reports_subscribers",[
                    [
                        "kolom" => "id_role",
                        "value" => $id
                    ],
                    [
                        "kolom" => "id_report",
                        "value" => $id_report
                    ]
                ]);
                break;
            case 'threshold':
                $id_report = $this->generate->kirana_decrypt($data['id_report']);
                $return = $this->general->set($action,"tbl_mrapp_reports_thresholds",[
                    [
                        "kolom" => "id_threshold",
                        "value" => $id
                    ],
                    [
                        "kolom" => "id_report",
                        "value" => $id_report
                    ]
                ]);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        $this->general->closeDb();

        echo json_encode($return);
    }

    private function get_reports($id=null,$all=null,$exclude=false)
    {
        $id = isset($id) ? $this->generate->kirana_decrypt($id) : null;
        $topic = $this->dmrreports->get_reports($id,$all,$exclude);
        return $topic;
    }

    private function get_type($id=null)
    {
        $id = isset($id) ? $this->generate->kirana_decrypt($id) : null;
        $data = $this->dmrreports->get_type($id);
        return $data;
    }

    private function get_parameter($id=null)
    {
        $id = isset($id) ? $this->generate->kirana_decrypt($id) : null;
        $idReport = isset($_POST['id_report']) ? $this->generate->kirana_decrypt($_POST['id_report']) : null;
        $data = $this->dmrreports->get_parameter($idReport,$id);
        return $data;

    }

    private function get_link($id=null)
    {
        $id = isset($id) ? $this->generate->kirana_decrypt($id) : null;
        $idReport = isset($_POST['id_report']) ? $this->generate->kirana_decrypt($_POST['id_report']) : null;
        $data = $this->dmrreports->get_link($idReport,$id);
        return $data;
    }

    private function get_subscriber($id)
    {
        $id = isset($id) ? $this->generate->kirana_decrypt($id) : null;
        $idReport = isset($_POST['id_report']) ? $this->generate->kirana_decrypt($_POST['id_report']) : null;
        $data = $this->dmrreports->get_subscriber($idReport,$id);
        return $data;
    }

    private function get_threshold($id=null)
    {
        $id = isset($id) ? $this->generate->kirana_decrypt($id) : null;
        $idReport = isset($_POST['id_report']) ? $this->generate->kirana_decrypt($_POST['id_report']) : null;
        $data = $this->dmrreports->get_threshold($idReport,$id);
        return $data;
    }

    private function save_report($data)
    {
        $datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_report'];
        unset($data_row['id_report']);

        if (!empty($id)) {
            $basic_data = array(
                'login_edit' => base64_decode($this->session->userdata("-id_user-")),
                'tanggal_edit' => $datetime
            );

            $data_row = array_merge($data_row, $basic_data);

            $data = $this->dgeneral->update('tbl_mrapp_reports', $data_row, array(
                array(
                    "kolom" => "id_report",
                    "value" => $id
                )
            ));

        } else {

            $basic_data = array(
                'login_buat' => base64_decode($this->session->userdata("-id_user-")),
                'tanggal_buat' => $datetime,
                'na' => 'n',
                'del' => 'n'
            );

            $data_row = array_merge($data_row, $basic_data);

            $data = $this->dgeneral->insert('tbl_mrapp_reports', $data_row);
            $id = $this->db->insert_id();
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
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_type($data)
    {
        $datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_report_type'];
        unset($data_row['id_report_type']);

        if (!empty($id)) {
            $basic_data = array(
                'login_edit' => base64_decode($this->session->userdata("-id_user-")),
                'tanggal_edit' => $datetime
            );

            $data_row = array_merge($data_row, $basic_data);

            $data = $this->dgeneral->update("tbl_mrapp_reports_types", $data_row, array(
                array(
                    "kolom" => "id_report_type",
                    "value" => $id
                )
            ));

        } else {

            $basic_data = array(
                'login_buat' => base64_decode($this->session->userdata("-id_user-")),
                'tanggal_buat' => $datetime,
                'na' => 'n',
                'del' => 'n'
            );

            $data_row = array_merge($data_row, $basic_data);

            $data = $this->dgeneral->insert("tbl_mrapp_reports_types", $data_row);
            $id = $this->db->insert_id();
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
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_parameter($data)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_report_parameter'];
        $data_row['id_report'] = $this->generate->kirana_decrypt($data_row['id_report']);
        $id_report = $data_row['id_report'];
        unset($data_row['id_report_parameter']);

        if (!empty($id)) {

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $data = $this->dgeneral->update("tbl_mrapp_reports_parameters", $data_row, array(
                array(
                    "kolom" => "id_report_parameter",
                    "value" => $id
                ), array(
                    "kolom" => "id_report",
                    "value" => $id_report
                ),
            ));
            $msg = "Data berhasil dirubah";

        } else {

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $data = $this->dgeneral->insert("tbl_mrapp_reports_parameters", $data_row);
            $id = $this->db->insert_id();

            $msg = "Data berhasil ditambahkan";
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $sts = "OK";
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_link($data)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_report_link'];
        $id_old = $data_row['id_report_link_old'];
        $data_row['id_report'] = $this->generate->kirana_decrypt($data_row['id_report']);
        $id_report = $data_row['id_report'];

        unset($data_row['id_report_link_old']);

        $checkOld = $this->dmrreports->get_link_count($id_report,$id_old);
        $check = $this->dmrreports->get_link_count($id_report,$id,'ALL');

        if ($checkOld > 0) {
            unset($data_row['id_report']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $data = $this->dgeneral->update("tbl_mrapp_reports_links", $data_row, array(
                array(
                    "kolom" => "id_report_link",
                    "value" => $id_old
                ), array(
                    "kolom" => "id_report",
                    "value" => $id_report
                ),
            ));
            $msg = "Data berhasil dirubah";

        } else {

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $data = $this->dgeneral->insert("tbl_mrapp_reports_links", $data_row);
            $id = $this->db->insert_id();

            $msg = "Data berhasil ditambahkan";
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $sts = "OK";
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_subscriber($data)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_role'];
        $id_old = $data_row['id_role_old'];
        $data_row['id_report'] = $this->generate->kirana_decrypt($data_row['id_report']);
        $id_report = $data_row['id_report'];

        unset($data_row['id_role_old']);

        $checkOld = $this->dmrreports->get_subscriber_count($id_report,$id_old);
//        $check = $this->dmrreports->get_link_count($id_report,$id,'ALL');

        if ($checkOld > 0) {
            unset($data_row['id_report']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $data = $this->dgeneral->update("tbl_mrapp_reports_subscribers", $data_row, array(
                array(
                    "kolom" => "id_role",
                    "value" => $id_old
                ), array(
                    "kolom" => "id_report",
                    "value" => $id_report
                ),
            ));
            $msg = "Data berhasil dirubah";

        } else {

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $data = $this->dgeneral->insert("tbl_mrapp_reports_subscribers", $data_row);
            $id = $this->db->insert_id();

            $msg = "Data berhasil ditambahkan";
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $sts = "OK";
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }



    private function save_threshold($data)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_report_threshold'];
        $data_row['id_report'] = $this->generate->kirana_decrypt($data_row['id_report']);
        $id_report = $data_row['id_report'];

        unset($data_row['id_report_threshold']);

        if (!empty($id)) {
            unset($data_row['id_report']);

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $data = $this->dgeneral->update("tbl_mrapp_reports_thresholds", $data_row, array(
                array(
                    "kolom" => "id_report_threshold",
                    "value" => $id
                ), array(
                    "kolom" => "id_report",
                    "value" => $id_report
                ),
            ));
            $msg = "Data berhasil dirubah";

        } else {

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $data = $this->dgeneral->insert("tbl_mrapp_reports_thresholds", $data_row);
            $id = $this->db->insert_id();

            $msg = "Data berhasil ditambahkan";
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $sts = "OK";
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }
}