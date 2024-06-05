<?php
/**
 * @application  : Jenis Sakit (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Jenissakit extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('djenissakit');
    }

    public function index()
    {
        $this->general->check_access();
        $data['module'] = "Jenis Sakit";
        $data['title'] = "Jenis Sakit";
        $data['title_form'] = "Jenis Sakit";
        $data['user'] = $this->general->get_data_user();
        $data['jenissakit'] = $this->djenissakit->get_all_data();
        $this->load->view('jenissakit', $data);
    }

    public function delete()
    {
        $data = $_POST;
        $id = $this->generate->kirana_decrypt($data['id']);
        unset($data['id']);
        $result = $this->djenissakit->delete_data(
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
                    $result = $this->djenissakit->set_data(
                        $id,
                        $action
                    );
                    break;
                case "save" :
                    $id = $this->generate->kirana_decrypt($data['id']);
                    unset($data['id']);
                    if(!empty($id))
                    {
                        $result = $this->djenissakit->update_data(
                            $id,
                            $data,
                            array(
                                array(
                                    'kolom' => 'id_fbk_sakit',
                                    'value' => $id
                                )
                            )
                        );
                    }else{

                        $result = $this->djenissakit->save_data(
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
        $data = $this->djenissakit->get_data($id);

        echo json_encode(array('data'=>$data,'id'=>$formData['id']));
    }
}