<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : BANK SPECIMEN
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Setting extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		$this->load->model('dmasterbank');
		$this->load->model('dsettingbank');
	}

	public function index(){
		show_404();
	}
	public function user($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Setting User Role";
		$data['title_form']  = "Form Setting User Role";
		$data['role'] 	 	 = $this->get_role('array', NULL, 'n', NULL);
		$data['plant']		 = $this->general->get_master_plant(); 
		$data['user_role'] 	 = $this->get_user_role('array', NULL, NULL, NULL);
		$this->load->view("setting/user_role", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	// public function get_user_auto(){
		// return $this->get_user_autocomplete();
	// }
	// public function get_posisi_auto(){
		// return $this->get_posisi_autocomplete();
	// }
	
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
            case 'user':
				$post = $this->input->post_get(NULL, TRUE);
                if ($post['tipe_user'] == "nik") {
                    $this->get_user_autocomplete();
                } else {
                    $this->get_posisi_autocomplete();
                }
                break;
			
			case 'user_role':
				$id_user_role = (isset($_POST['id_user_role']) ? $this->generate->kirana_decrypt($_POST['id_user_role']) : NULL);
				$this->get_user_role(NULL, $id_user_role);
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set($param = NULL) {
		$action = NULL;
		if (isset($_POST['type']) && $_POST['type'] == "nonactive") {
			$action = "delete_na";
		} else if (isset($_POST['type']) && $_POST['type'] == "setactive") {
			$action = "activate_na";
		}
		if ($action) {
			switch ($param) {
				case 'user':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_bank_user_role", array(
						array(
							'kolom' => 'id_user_role',
							'value' => $this->generate->kirana_decrypt($_POST['id_user_role'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}
	}
	public function save($param = NULL) {
		switch ($param) {
			case 'user':
				$this->save_user($param);
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
	private function get_role($array = NULL, $id_role = NULL, $active = NULL, $deleted = NULL) {
		$role 	= $this->dmasterbank->get_data_role("open", $id_role, $active, $deleted);
		$role 	= $this->general->generate_encrypt_json($role, array("id_role"));
		if ($array) {
			return $role;
		} else {
			echo json_encode($role);
		}
	}
	
	private function get_user_role($array = NULL, $id_user_role = NULL, $active = NULL, $deleted = NULL) {
		$user_role	= $this->dsettingbank->get_data_user_role("open", $id_user_role, $active, $deleted);
		$user_role 	= $this->general->generate_encrypt_json($user_role, array("id_user_role","id_role"));
		if ($array) {
			return $user_role;
		} else {
			echo json_encode($user_role);
		}
	}
	
	private function save_user($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_user_role 	= (isset($_POST['id_user_role']) ? $this->generate->kirana_decrypt($_POST['id_user_role']) : NULL);
		$id_role 		= (isset($_POST['id_role']) ? $this->generate->kirana_decrypt($_POST['id_role']) : NULL);
		$user	 		= (isset($_POST['user']) ? $_POST['user']: NULL);
		$pabrik			= (isset($_POST['pabrik']) ? implode(",", $_POST['pabrik']) : NULL);
		if ($id_user_role!=NULL){	
			$data_row = array(
				"id_role" 	=> $id_role,
				"user"	 	=> $user,
				"pabrik" 	=> $pabrik
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_bank_user_role", $data_row, array(
				array(
					'kolom' => 'id_user_role',
					'value' => $id_user_role
				)
			));
		}else{
			$ck_user 	= $this->dsettingbank->get_data_user_role(NULL, NULL, NULL, NULL, $user);
			if (count($ck_user) != 0){ 
				$msg    = "User/ Posisi sudah diset role, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"id_role" 	=> $id_role,
				"user"	 	=> $user,
				"pabrik" 	=> $pabrik
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_bank_user_role", $data_row);
		}
		if ($this->dgeneral->status_transaction() === false) {
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
		echo json_encode($return);
	}
    private function get_user_autocomplete()
    {
        if (isset($_GET['q'])) {
			$data	= $this->dsettingbank->get_data_user_autocomplete($_GET['q']);
            $data_json = array(
                "total_count"        => count($data),
                "incomplete_results" => false,
                "items"              => $data
            );
            echo json_encode($data_json);
        }
    }
    private function get_posisi_autocomplete()
    {
        if (isset($_GET['q'])) {
			$data	= $this->dsettingbank->get_data_posisi_autocomplete($_GET['q']);
            $data_json = array(
                "total_count"        => count($data),
                "incomplete_results" => false,
                "items"              => $data
            );
            echo json_encode($data_json);
        }
    }
	
	/*====================================================================*/
		
}