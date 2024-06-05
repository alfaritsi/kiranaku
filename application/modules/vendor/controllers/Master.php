<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KODE VENDOR
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Master extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
	
	    $this->load->model('dmastervendor');
	}

	public function index(){
		show_404();
	}
	public function role($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Role Master Vendor";
		$data['title_form']  = "Form Role Master Vendor";
		$data['role_all'] 	 = $this->get_role('array', NULL, NULL, NULL);
		$data['role'] 	 	 = $this->get_role('array', NULL, 'n', NULL);
		$this->load->view("master/role", $data);	
	}
	public function dokumen($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Dokumen";
		$data['title_form']  = "Form Master Dokumen";
		$data['master_dokumen']	 = $this->get_master_dokumen('array', NULL, NULL, NULL);
		$this->load->view("master/dokumen", $data);	
	}
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_user(){
		return $this->general->get_user_autocomplete();
	}
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'karyawan':
				$data      = $this->dmatervendor->get_data_karyawan('open', NULL, NULL, NULL, $_GET['q']);
				$data 	   = $this->general->generate_encrypt_json($data, array("nik"));
				$data_json = array(
					"total_count"        => count($data),
					"incomplete_results" => false,
					"items"              => $data
				);

				$return = json_encode($data_json);
				$return = $this->general->jsonify($data_json);

				echo $return;
				break;
			case 'master_dokumen':
				$id_master_dokumen = (isset($_POST['id_master_dokumen']) ? $this->generate->kirana_decrypt($_POST['id_master_dokumen']) : NULL);
				$nama   	= (isset($_POST['nama']) ? $_POST['nama'] : NULL);
				$this->get_master_dokumen(NULL, $id_master_dokumen, 'n', NULL, $nama);
				break;
			case 'role':
				$id_role = (isset($_POST['id_role']) ? $this->generate->kirana_decrypt($_POST['id_role']) : NULL);
				$this->get_role(NULL, $id_role);
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
				case 'master_dokumen':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_vendor_master_dokumen", array(
						array(
							'kolom' => 'id_master_dokumen',
							'value' => $this->generate->kirana_decrypt($_POST['id_master_dokumen'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'master_role':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_vendor_role", array(
						array(
							'kolom' => 'id_role',
							'value' => $this->generate->kirana_decrypt($_POST['id_role'])
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
			case 'master_dokumen':
				$this->save_master_dokumen($param);
				break;
			case 'role':
				$this->save_role($param);
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
		$role 	= $this->dmastervendor->get_data_role("open", $id_role, $active, $deleted);
		$role 	= $this->general->generate_encrypt_json($role, array("id_role"));
		if ($array) {
			return $role;
		} else {
			echo json_encode($role);
		}
	}
	private function get_master_dokumen($array = NULL, $id_master_dokumen = NULL, $active = NULL, $deleted = NULL) {
		$master_dokumen 		= $this->dmastervendor->get_data_master_dokumen("open", $id_master_dokumen, $active, $deleted);
		$master_dokumen 		= $this->general->generate_encrypt_json($master_dokumen, array("id_master_dokumen"));
		if ($array) {
			return $master_dokumen;
		} else {
			echo json_encode($master_dokumen);
		}
	}
	
	private function save_master_dokumen($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_master_dokumen 	= (isset($_POST['id_master_dokumen']) ? $this->generate->kirana_decrypt($_POST['id_master_dokumen']) : NULL);
		if ($id_master_dokumen!=NULL){	
			$ck_master_dokumen 		= $this->dmastervendor->get_data_master_dokumen(NULL, NULL, NULL, NULL, $_POST['nama'], $id_master_dokumen);
			if (count($ck_master_dokumen) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"	 => $_POST['nama']
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_master_dokumen", $data_row, array(
				array(
					'kolom' => 'id_master_dokumen',
					'value' => $id_master_dokumen
				)
			));
		}else{
			$ck_master_dokumen 		= $this->dmastervendor->get_data_master_dokumen(NULL, NULL, NULL, NULL, $_POST['nama']);
			if (count($ck_master_dokumen) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"  => $_POST['nama']
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_vendor_master_dokumen", $data_row);
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
	
	private function save_role($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_role	= (isset($_POST['id_role']) ? $this->generate->kirana_decrypt($_POST['id_role']) : NULL);
		$nama		= (isset($_POST['nama']) ? $_POST['nama'] : NULL);
		$level		= (isset($_POST['level']) ? $_POST['level'] : NULL);
		//ho
		$if_approve_create_ho	 	= (isset($_POST['if_approve_create_ho']) ? $_POST['if_approve_create_ho'] : NULL);
		$if_decline_create_ho	 	= (isset($_POST['if_decline_create_ho']) ? $_POST['if_decline_create_ho'] : NULL);
		$if_approve_create_legal_ho	= (isset($_POST['if_approve_create_legal_ho']) ? $_POST['if_approve_create_legal_ho'] : NULL);
		$if_decline_create_legal_ho	= (isset($_POST['if_decline_create_legal_ho']) ? $_POST['if_decline_create_legal_ho'] : NULL);
		$if_approve_change_ho	 	= (isset($_POST['if_approve_change_ho']) ? $_POST['if_approve_change_ho'] : NULL);
		$if_decline_change_ho	 	= (isset($_POST['if_decline_change_ho']) ? $_POST['if_decline_change_ho'] : NULL);
		$if_approve_change_legal_ho	= (isset($_POST['if_approve_change_legal_ho']) ? $_POST['if_approve_change_legal_ho'] : NULL);
		$if_decline_change_legal_ho	= (isset($_POST['if_decline_change_legal_ho']) ? $_POST['if_decline_change_legal_ho'] : NULL);
		$if_approve_extend_ho	 	= (isset($_POST['if_approve_extend_ho']) ? $_POST['if_approve_extend_ho'] : NULL);
		$if_decline_extend_ho	 	= (isset($_POST['if_decline_extend_ho']) ? $_POST['if_decline_extend_ho'] : NULL);
		$if_approve_delete_ho	 	= (isset($_POST['if_approve_delete_ho']) ? $_POST['if_approve_delete_ho'] : NULL);
		$if_decline_delete_ho	 	= (isset($_POST['if_decline_delete_ho']) ? $_POST['if_decline_delete_ho'] : NULL);
		$if_approve_undelete_ho	 	= (isset($_POST['if_approve_undelete_ho']) ? $_POST['if_approve_undelete_ho'] : NULL);
		$if_decline_undelete_ho	 	= (isset($_POST['if_decline_undelete_ho']) ? $_POST['if_decline_undelete_ho'] : NULL);
		//pabrik
		$if_approve_create_pabrik	 	= (isset($_POST['if_approve_create_pabrik']) ? $_POST['if_approve_create_pabrik'] : NULL);
		$if_decline_create_pabrik	 	= (isset($_POST['if_decline_create_pabrik']) ? $_POST['if_decline_create_pabrik'] : NULL);
		$if_approve_create_legal_pabrik	= (isset($_POST['if_approve_create_legal_pabrik']) ? $_POST['if_approve_create_legal_pabrik'] : NULL);
		$if_decline_create_legal_pabrik	= (isset($_POST['if_decline_create_legal_pabrik']) ? $_POST['if_decline_create_legal_pabrik'] : NULL);
		$if_approve_change_pabrik	 	= (isset($_POST['if_approve_change_pabrik']) ? $_POST['if_approve_change_pabrik'] : NULL);
		$if_decline_change_pabrik	 	= (isset($_POST['if_decline_change_pabrik']) ? $_POST['if_decline_change_pabrik'] : NULL);
		$if_approve_change_legal_pabrik	= (isset($_POST['if_approve_change_legal_pabrik']) ? $_POST['if_approve_change_legal_pabrik'] : NULL);
		$if_decline_change_legal_pabrik	= (isset($_POST['if_decline_change_legal_pabrik']) ? $_POST['if_decline_change_legal_pabrik'] : NULL);
		$if_approve_extend_pabrik	 	= (isset($_POST['if_approve_extend_pabrik']) ? $_POST['if_approve_extend_pabrik'] : NULL);
		$if_decline_extend_pabrik	 	= (isset($_POST['if_decline_extend_pabrik']) ? $_POST['if_decline_extend_pabrik'] : NULL);
		$if_approve_delete_pabrik	 	= (isset($_POST['if_approve_delete_pabrik']) ? $_POST['if_approve_delete_pabrik'] : NULL);
		$if_decline_delete_pabrik	 	= (isset($_POST['if_decline_delete_pabrik']) ? $_POST['if_decline_delete_pabrik'] : NULL);
		$if_approve_undelete_pabrik	 	= (isset($_POST['if_approve_undelete_pabrik']) ? $_POST['if_approve_undelete_pabrik'] : NULL);
		$if_decline_undelete_pabrik	 	= (isset($_POST['if_decline_undelete_pabrik']) ? $_POST['if_decline_undelete_pabrik'] : NULL);
		
		if ($id_role!=NULL){	
			$ck_role	= $this->dmastervendor->get_data_role(NULL, NULL, NULL, NULL, $nama, NULL, $id_role);
			if (count($ck_role) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$ck_role_level	= $this->dmastervendor->get_data_role(NULL, NULL, NULL, NULL, NULL, $level, $id_role);
			if (count($ck_role_level) != 0){ 
				$msg    = "Duplicate Level, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
		
			$data_row = array(
				"nama"								=> $nama,
				"level"								=> $level,
				"if_approve_create_ho"		 		=> $if_approve_create_ho,
				"if_decline_create_ho"		 		=> $if_decline_create_ho,
				"if_approve_create_legal_ho"		=> $if_approve_create_legal_ho,
				"if_decline_create_legal_ho"		=> $if_decline_create_legal_ho,
				"if_approve_change_ho"		 		=> $if_approve_change_ho,
				"if_decline_change_ho"		 		=> $if_decline_change_ho,
				"if_approve_change_legal_ho"		=> $if_approve_change_legal_ho,
				"if_decline_change_legal_ho"		=> $if_decline_change_legal_ho,
				"if_approve_extend_ho"		 		=> $if_approve_extend_ho,
				"if_decline_extend_ho"		 		=> $if_decline_extend_ho,
				"if_approve_delete_ho"		 		=> $if_approve_delete_ho,
				"if_decline_delete_ho"		 		=> $if_decline_delete_ho,
				"if_approve_undelete_ho"			=> $if_approve_undelete_ho,
				"if_decline_undelete_ho"			=> $if_decline_undelete_ho,
				"if_approve_create_pabrik"		 	=> $if_approve_create_pabrik,
				"if_decline_create_pabrik"		 	=> $if_decline_create_pabrik,
				"if_approve_create_legal_pabrik"	=> $if_approve_create_legal_pabrik,
				"if_decline_create_legal_pabrik"	=> $if_decline_create_legal_pabrik,
				"if_approve_change_pabrik"		 	=> $if_approve_change_pabrik,
				"if_decline_change_pabrik"		 	=> $if_decline_change_pabrik,
				"if_approve_change_legal_pabrik"	=> $if_approve_change_legal_pabrik,
				"if_decline_change_legal_pabrik"	=> $if_decline_change_legal_pabrik,
				"if_approve_extend_pabrik"		 	=> $if_approve_extend_pabrik,
				"if_decline_extend_pabrik"		 	=> $if_decline_extend_pabrik,
				"if_approve_delete_pabrik"		 	=> $if_approve_delete_pabrik,
				"if_decline_delete_pabrik"		 	=> $if_decline_delete_pabrik,
				"if_approve_undelete_pabrik"		=> $if_approve_undelete_pabrik,
				"if_decline_undelete_pabrik"		=> $if_decline_undelete_pabrik
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_role", $data_row, array(
				array(
					'kolom' => 'id_role',
					'value' => $id_role
				)
			));
		}else{
			$ck_role	= $this->dmastervendor->get_data_role(NULL, NULL, NULL, NULL, $nama);
			if (count($ck_role) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$ck_role_level	= $this->dmastervendor->get_data_role(NULL, NULL, NULL, NULL, NULL, $level);
			if (count($ck_role_level) != 0){ 
				$msg    = "Duplicate Level, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"								=> $nama,
				"level"								=> $level,
				"if_approve_create_ho"		 		=> $if_approve_create_ho,
				"if_decline_create_ho"		 		=> $if_decline_create_ho,
				"if_approve_create_legal_ho"		=> $if_approve_create_legal_ho,
				"if_decline_create_legal_ho"		=> $if_decline_create_legal_ho,
				"if_approve_change_ho"		 		=> $if_approve_change_ho,
				"if_decline_change_ho"		 		=> $if_decline_change_ho,
				"if_approve_change_legal_ho"		=> $if_approve_change_legal_ho,
				"if_decline_change_legal_ho"		=> $if_decline_change_legal_ho,
				"if_approve_extend_ho"		 		=> $if_approve_extend_ho,
				"if_decline_extend_ho"		 		=> $if_decline_extend_ho,
				"if_approve_delete_ho"		 		=> $if_approve_delete_ho,
				"if_decline_delete_ho"		 		=> $if_decline_delete_ho,
				"if_approve_undelete_ho"			=> $if_approve_undelete_ho,
				"if_decline_undelete_ho"			=> $if_decline_undelete_ho,
				"if_approve_create_pabrik"		 	=> $if_approve_create_pabrik,
				"if_decline_create_pabrik"		 	=> $if_decline_create_pabrik,
				"if_approve_create_legal_pabrik"	=> $if_approve_create_legal_pabrik,
				"if_decline_create_legal_pabrik"	=> $if_decline_create_legal_pabrik,
				"if_approve_change_pabrik"		 	=> $if_approve_change_pabrik,
				"if_decline_change_pabrik"		 	=> $if_decline_change_pabrik,
				"if_approve_change_legal_pabrik"	=> $if_approve_change_legal_pabrik,
				"if_decline_change_legal_pabrik"	=> $if_decline_change_legal_pabrik,
				"if_approve_extend_pabrik"		 	=> $if_approve_extend_pabrik,
				"if_decline_extend_pabrik"		 	=> $if_decline_extend_pabrik,
				"if_approve_delete_pabrik"		 	=> $if_approve_delete_pabrik,
				"if_decline_delete_pabrik"		 	=> $if_decline_delete_pabrik,
				"if_approve_undelete_pabrik"		=> $if_approve_undelete_pabrik,
				"if_decline_undelete_pabrik"		=> $if_decline_undelete_pabrik
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_vendor_role", $data_row);
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
	
	/*====================================================================*/
		
}