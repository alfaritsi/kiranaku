<?php
/**
 * @application  : Kirana News (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Kirananews extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dkirananews');
    }

    public function index()
    {
        $this->general->check_access();
        $data['module'] = "Kirana News";
        $data['title'] = "Kirana News";
        $data['title_form'] = "News";
        $data['user'] = $this->general->get_data_user();
        $data['news'] = $this->dkirananews->get_data_kirana_news();
        $this->load->view('kirananews', $data);
    }

    public function delete()
    {
        $data = $_POST;
        $id = base64_decode($data['id']);
        unset($data['id']);
        $result = $this->dkirananews->delete_data(
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
                    $id = base64_decode($data['id']);
                    unset($data['id']);
                    $result = $this->dkirananews->set_data(
                        $id,
                        $action
                    );
                    break;
                case "news" :
                    $id = base64_decode($data['id']);
                    unset($data['id']);
                    if(!empty($id))
                    {
                        $result = $this->dkirananews->update_data(
                            $id,
                            $data,
                            array(
                                array(
                                    'kolom' => 'id_kirananews',
                                    'value' => $id
                                )
                            )
                        );
                    }else{

                        $result = $this->dkirananews->save_data(
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
        $id = base64_decode($formData['id']);
        $data = $this->dkirananews->get_data($id);
        echo json_encode(array('data'=>$data,'id'=>$formData['id']));
    }
}