<?php
/**
 * @application  : Bantuan Kesehatan Wanita (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Fbkwanita extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dfbkwanita');
    }

    public function index()
    {
        $this->general->check_access();
        $data['module'] = "Karyawan Wanita Menanggung";
        $data['title'] = "Karyawan Wanita Menanggung";
        $data['title_form'] = "Karyawan Wanita";
        $data['user'] = $this->general->get_data_user();
        $data['fbkwanita'] = $this->dfbkwanita->get_all_data();
        $this->load->view('fbkwanita', $data);
    }

    public function delete()
    {
        $data = $_POST;
        $id = $this->generate->kirana_decrypt($data['id']);
        unset($data['id']);
        $result = $this->dfbkwanita->delete_data(
            $id
        );
        echo json_encode($result);
    }

    public function set_data($method,$action=null)
    {
        $result = array();
        if (isset($method)) {
            $data = $_POST;
            switch ($method) {
                case "publish" :
                    $id = $this->generate->kirana_decrypt($data['id']);
                    unset($data['id']);
                    $result = $this->dfbkwanita->set_data(
                        $id,
                        $action
                    );
                    break;
                case "save" :
                    $id = $this->generate->kirana_decrypt($data['id']);
                    unset($data['id']);
                    if(!empty($id))
                    {
                        $result = $this->dfbkwanita->update_data(
                            $id,
                            $data,
                            array(
                                array(
                                    'kolom' => 'id_fbk_cek',
                                    'value' => $id
                                )
                            )
                        );
                    }else{

                        $result = $this->dfbkwanita->save_data(
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
        $data = $this->dfbkwanita->get_data($id);

        echo json_encode(array('data'=>$data,'id'=>$formData['id']));
    }
}