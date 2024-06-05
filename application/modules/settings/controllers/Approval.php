<?php
/**
 * @application  : Setting Approval (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Approval extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dapproval');
    }

    public function index()
    {
        $this->general->check_access();
        $data['module'] = "Management Approval";
        $data['title'] = "Approval";
        $data['title_form'] = "Approval";
        $data['user'] = $this->general->get_data_user();
        $data['approval'] = $this->dapproval->get_all_data();
        $this->load->view('approval', $data);
    }

    public function detail()
    {
        $this->general->check_access();
        $data['module'] = "Approval";
        $data['title'] = "Info Approval";
        $data['title_form'] = "Approval";
        $data['user'] = $this->general->get_data_user();
        $data['pabrik'] = $this->dapproval->get_list_pabrik();
        $approvals = $this->dapproval->get_all_data_detail(NULL, @$_POST['pabrik']);
        foreach ($approvals as $i => $approval) {
            $approval->atasan_nama = array_filter(explode(', ',$approval->atasan_nama),function($val){ $val = rtrim(ltrim($val)); return !empty($val) && $val != ','; });
            $approvals[$i] = $approval;
        }
        $data['approval'] = $approvals;

        $this->load->view('infoapproval', $data);
    }

    public function detail_export_ho()
    {
        $nama_file = "Laporan_Approval_ho.xls";
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$nama_file");
        header("Pragma: no-cache");
        header("Expires: 0");
        $data['approval'] = $this->dapproval->get_all_data_detail(NULL, @$_POST['pabrik']);
        echo $this->load->view('excel_infoapproval', $data, false);
    }

    public function detail_export_pabrik()
    {
        $nama_file = "Laporan_Approval_pabrik.xls";
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=$nama_file");
        header("Pragma: no-cache");
        header("Expires: 0");
        $data['approval'] = $this->dapproval->get_all_data_detail(NULL, @$_POST['pabrik'], false);
        echo $this->load->view('excel_infoapproval', $data, false);
    }

    public function get_data_detail()
    {
        $formData = $_POST;
        $id = $this->generate->kirana_decrypt($formData['id']);
        $data = $this->dapproval->get_data_detail($id);

        echo json_encode(array('data' => $data, 'id' => $formData['id']));
    }

    public function delete()
    {
        $data = $_POST;
        $id = $this->generate->kirana_decrypt($data['id']);
        unset($data['id']);
        $result = $this->dapproval->delete_data(
            $id
        );
        echo json_encode($result);
    }

    public function set_data($method, $action = null)
    {
        $result = array();
        if (isset($method)) {
            $data = $_POST;
            switch ($method) {
                case "publish" :
                    $id = $this->generate->kirana_decrypt($data['id']);
                    unset($data['id']);
                    $result = $this->dapproval->set_data(
                        $id,
                        $action
                    );
                    break;
                case "save" :
                    $id = $this->generate->kirana_decrypt($data['id']);
                    unset($data['id']);
                    if (!empty($id)) {
                        $result = $this->dapproval->update_data(
                            $id,
                            $data,
                            array(
                                array(
                                    'kolom' => 'id_atasan_master',
                                    'value' => $id
                                )
                            )
                        );
                    } else {

                        $result = $this->dapproval->save_data(
                            $data
                        );
                    }

                    break;
            }
        }

        echo json_encode($result);
    }

    public function get_data()
    {
        $formData = $_POST;
        $id = $this->generate->kirana_decrypt($formData['id']);
        $data = $this->dapproval->get_data($id);

        echo json_encode(array('data' => $data, 'id' => $formData['id']));
    }
}