<?php
/**
 * @application  : Info Kirana (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Infokirana extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dinfokirana');
    }

    public function index()
    {
        $this->general->check_access();
        $data['module'] = "Info Kirana";
        $data['title'] = "Info Kirana";
        $data['title_form'] = "Info Kirana";
        $data['user'] = $this->general->get_data_user();
        $data['news'] = $this->dinfokirana->get_all_data();
        $this->load->view('infokirana', $data);
    }

    public function delete()
    {
        $data = $_POST;
        $id = $this->generate->kirana_decrypt($data['id']);
        unset($data['id']);
        $result = $this->dinfokirana->delete_data(
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
                    $result = $this->dinfokirana->set_data(
                        $id,
                        $action
                    );
                    break;
                case "komentar-publish" :
                    $id = $this->generate->kirana_decrypt($data['id']);
                    unset($data['id']);
                    $result = $this->dinfokirana->komentar_set_data(
                        $id,
                        $action
                    );
                    break;
                case "save" :
                    $id = $this->generate->kirana_decrypt($data['id']);
                    unset($data['id']);
                    if(!empty($id))
                    {
                        $result = $this->dinfokirana->update_data(
                            $id,
                            $data,
                            array(
                                array(
                                    'kolom' => 'id_news',
                                    'value' => $id
                                )
                            )
                        );
                    }else{

                        $result = $this->dinfokirana->save_data(
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
        $data = $this->dinfokirana->get_data($id);

        echo json_encode(array('data'=>$data,'id'=>$formData['id']));
    }

    public function get_list_komentar()
    {
        $id = $this->input->post('id');
        $result = array();
        if(isset($id))
        {
            $result = $this->dinfokirana->get_all_komentar_data($id);
        }
        $this->load->view(
            "infokiranakomentar",
            array(
                'komentars' => $result,
                'id' => $id
            )
        );
    }
}