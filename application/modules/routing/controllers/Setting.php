<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Email Routing
@author     : Matthew Jodi
@contributor  :
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Setting extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dmasteremail');
        $this->load->model('dsettingemail');
    }

    public function index()
    {
        show_404();
    }

    public function role()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        $data['user'] = $this->general->get_data_user();
        //===============================================/

        $data['topics'] = $this->dmasteremail->get_data_topic(NULL, 'all');
        $data['menus'] = $this->dsettingemail->get_kiranalytics_menu(NULL, 'all');
        $data['jabatans'] = $this->dsettingemail->get_jabatan(NULL, 'all');
        $data['divisis'] = $this->dsettingemail->get_divisi(NULL, 'all');
        $data['departemens'] = $this->dsettingemail->get_departemen(NULL, 'all');
        $data['roles'] = $this->dsettingemail->get_data_role(NULL, NULL, 'all');
        $this->load->view('setting/role', $data);
    }

    public function user()
    {
        //====must be initiate in every view function====/
//        $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        $data['user'] = $this->general->get_data_user();
        //===============================================/
        $plant = isset($_POST['plant'])?$_POST['plant']:'ho';
        $data['users'] = $this->dsettingemail->get_data_user(NULL, $plant,'all');
        $data['companies'] = $this->dsettingemail->get_company(NULL, 'all');
        $data['buyers'] = $this->dsettingemail->get_buyer(NULL, 'all');
        $this->load->view('setting/user', $data);
    }

    public function menu(){
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['generate'] = $this->generate;
		$data['module'] = $this->router->fetch_module();
		$data['user'] = $this->general->get_data_user();
		//===============================================/
		$data['title']    	= "Menu Kiranalytics";
		$data['menu']		= $this->get_menu_kiranalytics(NULL, 'yes');
        $this->load->view('setting/menu', $data);
	}

    public function save($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'role':
                $return = $this->save_role($data);
                break;
            case 'user':
                $return = $this->save_user($data);
                break;
            case 'menu':
                $return = $this->save_menu($data);
                break;

            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function roles($action = null)
    {
        switch ($action)
        {
            case 'delete' :
                $return = $this->delete_role();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    private function delete_role()
    {
        $data = $_POST;
        $result = array();
        if(isset($data['id']))
        {

            $id = $this->generate->kirana_decrypt($data['id']);
            unset($data['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $return = $this->general->set('delete_del',"tbl_ac_roles", array(
                array(
                    "kolom" => "id_role",
                    "value" => $id
				)
			));

            if ($this->dgeneral->status_transaction() === FALSE ) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil dihapus";
                $sts = "OK";
            }
            $result = array('sts' => $sts, 'msg' => $msg);
        }

        return $result;
    }

    public function get_data($param)
    {
        $formData = $_POST;
        switch ($param) {
            case 'role':
                $id = $this->generate->kirana_decrypt($formData['id_role']);
                $this->get_role($id);
                break;
            case 'user':
                $id = $this->generate->kirana_decrypt($formData['id_user']);
                $this->get_user($id);
                break;
            case 'menu_kiranalytics':
                $id = $formData['id_menu'];
                $this->get_menu_kiranalytics($id, NULL);
                break;
            default:
                $return = array();
                echo json_encode($return);
                break;
        }
    }

    private function get_role($id)
    {
        $report = $this->dsettingemail->get_data_role($id, NULL, 'all');
        echo json_encode($report);

    }

    private function save_role($data)
    {

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $topics = $data_row['topics'];
        $menus = $data_row['menus'];
        $jabatans = $data_row['jabatans'];
        $divisis = $data_row['divisis'];
        $departemens = $data_row['departemens'];
//        $data_row['jabatan_keywords'] = $data_row['jabatans'];

        $id = $data_row['id_role'];
        unset($data_row['id_role']);
        unset($data_row['topics']);
        unset($data_row['menus']);
        unset($data_row['jabatans']);
        unset($data_row['divisis']);
        unset($data_row['departemens']);

        if(!empty($id))
        {
            $data_row = $this->dgeneral->basic_column('update',$data_row);

            $data = $this->dgeneral->update('tbl_ac_roles', $data_row,array(
                array(
                    "kolom"=>"id_role",
                    "value"=>$id
                )
            ));

        }else{

            $data_row = $this->dgeneral->basic_column('insert',$data_row);

            $data = $this->dgeneral->insert('tbl_ac_roles', $data_row);
            $id = $this->db->insert_id();
        }

        $deleteWhere = array(
            array(
                "kolom" => "id_role",
                "value" => $id
            )
        );

        $arrayDepartemens = preg_split('/,/', $departemens, null, PREG_SPLIT_NO_EMPTY);

        $deleteOldData = $this->dgeneral->delete("tbl_ac_roles_departemens",$deleteWhere);

        if($deleteOldData)
        {
            foreach ($arrayDepartemens as $departemen)
            {
                $data = $this->dgeneral->insert("tbl_ac_roles_departemens",array(
                    "id_role" => $id,
                    "id_departemen" => $departemen
                ));
            }
        }

        $arrayDivisis = preg_split('/,/', $divisis, null, PREG_SPLIT_NO_EMPTY);

        $deleteOldData = $this->dgeneral->delete("tbl_ac_roles_divisis",$deleteWhere);

        if($deleteOldData)
        {
            foreach ($arrayDivisis as $divisi)
            {
                $data = $this->dgeneral->insert("tbl_ac_roles_divisis",array(
                    "id_role" => $id,
                    "id_divisi" => $divisi
                ));
            }
        }

        $arrayJabatans= preg_split('/,/', $jabatans, null, PREG_SPLIT_NO_EMPTY);

        $deleteOldData = $this->dgeneral->delete("tbl_ac_roles_jabatans",$deleteWhere);

        if($deleteOldData)
        {
            foreach ($arrayJabatans as $jabatan)
            {
                $data = $this->dgeneral->insert("tbl_ac_roles_jabatans",array(
                    "id_role" => $id,
                    "id_jabatan" => $jabatan
                ));
            }
        }

        $arrayTopics = preg_split('/,/', $topics, null, PREG_SPLIT_NO_EMPTY);

        $deleteOldData = $this->dgeneral->delete("tbl_ac_roles_topics",$deleteWhere);

        if($deleteOldData)
        {
            foreach ($arrayTopics as $topic)
            {
                $data = $this->dgeneral->insert("tbl_ac_roles_topics",array(
                    "id_role" => $id,
                    "id_topic" => $topic
                ));
            }
        }

        $arrayMenus = preg_split('/,/', $menus, null, PREG_SPLIT_NO_EMPTY);

        $deleteOldData = $this->dgeneral->delete("tbl_ac_roles_menus",$deleteWhere);
        if($deleteOldData)
        {
            foreach ($arrayMenus as $menu)
            {
                $data = $this->dgeneral->insert("tbl_ac_roles_menus",array(
                    "id_role" => $id,
                    "id_menu" => $menu,
                    "app" => "kiranalytics"
                ));
            }
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
        return $return;
    }

	private function get_user($id)
	{
		$report = $this->dsettingemail->get_data_user($id, null, 'all');
		echo json_encode($report);

	}

    private function save_user($data=null)
    {

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_user'];

        $check = $this->dsettingemail->count_user_companies($id);

        if($check>0)
        {
            unset($data_row['id_user']);

            $data_row = $this->dgeneral->basic_column('update',$data_row);

            $data = $this->dgeneral->update("tbl_ac_users_companies", $data_row,array(
                array(
                    "kolom"=>"id_user",
                    "value"=>$id
                )
            ));

        }else{

            $data_row = $this->dgeneral->basic_column('insert',$data_row);

            $data = $this->dgeneral->insert("tbl_ac_users_companies", $data_row);
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
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_menu($data=null)
    {

        $this->general->connectDbDefault();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_menu'];

        // $check = $this->dsettingemail->count_menu_companies($id);

        unset($data_row['id_menu']);

        if($id != "")
        {
            $data_row = $this->dgeneral->basic_column('update_kiranalytics',$data_row);

            $data = $this->dgeneral->update("tb_menu_baru", $data_row,array(
                array(
                    "kolom"=>"id_menu",
                    "value"=>$id
                )
            ));

        }else{

            $data_row = $this->dgeneral->basic_column('insert_kiranalytics',$data_row);

            $data = $this->dgeneral->insert("tb_menu_baru", $data_row);
            $id = $this->db->insert_id();
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            if($id != "")
            {
                $msg = "Data berhasil diupdate";
                $sts = "OK";
                }else{
                $msg = "Data berhasil ditambahkan";
                $sts = "OK";
            }
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    public function get_menu_kiranalytics($id_menu=NULL, $array=NULL){
        $id_menu = $this->generate->kirana_decrypt($id_menu);
        $menu   = $this->dsettingemail->get_menu_kiranalytics(NULL, $id_menu, NULL);
        if($array == "yes") return $menu;
        else echo json_encode($menu);
    }

    public function set_data($action, $param){
        switch ($param) {
            case 'menu_kiranalytics':
                $this->set_menu_kiranalytics($action);
                break;
            
            default:
                $return = array();
                echo json_encode($return);
                break;
        }
    }

    private function set_menu_kiranalytics($action){
        $id = $this->generate->kirana_decrypt($_POST['id']);
        $this->general->connectDbDefault();

        $data_row = array('is_active' => 0);
        $data_row = $this->dgeneral->basic_column('update_kiranalytics',$data_row);

        $delete = $this->dgeneral->update("tb_menu_baru", $data_row,array(
            array(
                "kolom"=>"id_menu",
                "value"=>$id
            )
        ));

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            if($id != "")
            {
                $msg = "Data berhasil diupdate";
                $sts = "OK";
                }else{
                $msg = "Data berhasil ditambahkan";
                $sts = "OK";
            }
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }


}

?>
