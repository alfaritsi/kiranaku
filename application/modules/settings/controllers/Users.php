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


class Users extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dusers');
    }

    public function index()
    {
        $this->general->check_access();
        $data['module'] = "Users";
        $data['title'] = "Users";
        $data['title_form'] = "User";
        $data['user'] = $this->general->get_data_user();
        $idUser = $this->session->userdata("-id_user-");
        $ck_action = $this->dusers->GetFields(
            'tbl_menu',
            "CHARINDEX('".$data['user']->id_level."', level_action)>0 and url_external='settings/users' and na",
            'n',
            'level_action',
            true
        );
        $data['ck_action'] = $ck_action;

        if(!empty($idUser) && empty($ck_action))
        {
            $data['users'] = $this->dusers->get_all_data($idUser);
        }else{

            $data['users'] = $this->dusers->get_all_data();
        }
        $this->load->view('users', $data);
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
                    $result = $this->dusers->set_data(
                        $id,
                        $action
                    );
                    break;
                case "save" :
                    $id = $this->generate->kirana_decrypt($data['id']);
                    unset($data['id']);
                    if($data['pass']==$data['pass_conf'])
                    {
                        $data['pass'] = md5($data['pass']);
                        unset($data['pass_conf']);
                        if(!empty($id))
                        {
                            $result = $this->dusers->update_data(
                                $id,
                                $data,
                                array(
                                    array(
                                        'kolom' => 'id_user',
                                        'value' => $id
                                    )
                                )
                            );
                        }else{

                            $data['user'] = $this->general->get_data_user();

                            $ck_action = $this->dusers->GetFields(
                                'tbl_menu',
                                "CHARINDEX('".$data['user']->id_level."', level_action)>0 and url_external='settings/users' and na",
                                'n',
                                'level_action',
                                true
                            );
                            if(!empty($ck_action))
                            {

                                $result = $this->dusers->save_data(
                                    $data
                                );
                            }else{
                                $result = array('sts' => "NotOK", 'msg' => "Tidak memiliki akses untuk tambah data.");
                            }
                        }
                    }else{
                        $result = array('sts' => "NotOK", 'msg' => "Password & Password Konfirmasi tidak sama");
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
        $data = $this->dusers->get_data($id);

        echo json_encode(array('data'=>$data,'id'=>$formData['id']));
    }
}