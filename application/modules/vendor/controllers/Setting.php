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

Class Setting extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		$this->load->model('dmastervendor');
		$this->load->model('dsettingvendor');
	}

	public function index(){
		show_404();
	}
	public function kualifikasi($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Setting Kualifikasi Dokumen";
		$data['title_form']  = "Form Setting Kualifikasi Dokumen";
		$data['master_dokumen']	 = $this->dmastervendor->get_data_master_dokumen("array", NULL, 'n');
		$data['kualifikasi'] 	 = $this->get_kualifikasi('array', NULL, NULL, NULL);
		$this->load->view("setting/kualifikasi", $data);	
	}
	public function userrole($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Setting User Role";
		$data['title_form']  = "Form Setting User Role";
		$data['role'] 	 	 = $this->get_role('array', NULL, 'n', NULL);
		$data['user_role'] 	 = $this->get_user_role('array', NULL, NULL, NULL);
		$this->load->view("setting/user_role", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_user(){
		return $this->get_user_autocomplete();
	}
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'kualifikasi':
				$id_kualifikasi_spk = (isset($_POST['id_kualifikasi_spk']) ? $this->generate->kirana_decrypt($_POST['id_kualifikasi_spk']) : NULL);
				$this->get_kualifikasi(NULL, $id_kualifikasi_spk);
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
				case 'user_role':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_vendor_user_role", array(
						array(
							'kolom' => 'id_user_role',
							'value' => $this->generate->kirana_decrypt($_POST['id_user_role'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'kualifikasi':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_vendor_kualifikasi_dokumen", array(
						array(
							'kolom' => 'id_kategori_dokumen',
							'value' => $this->generate->kirana_decrypt($_POST['id_kategori_dokumen'])
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
			case 'user_role':
				$this->save_user_role($param);
				break;
			case 'kualifikasi':
				$this->save_kualifikasi($param);
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
	
	private function get_user_role($array = NULL, $id_user_role = NULL, $active = NULL, $deleted = NULL) {
		$user_role	= $this->dsettingvendor->get_data_user_role("open", $id_user_role, $active, $deleted);
		$user_role 	= $this->general->generate_encrypt_json($user_role, array("id_user_role","id_role"));
		if ($array) {
			return $user_role;
		} else {
			echo json_encode($user_role);
		}
	}
	
    private function get_user_autocomplete()
    {
        if (isset($_GET['q'])) {
            // $data      = $this->CI->dgeneral->get_data_user($_GET['q']);
			$data	= $this->dsettingvendor->get_data_user($_GET['q']);
            $data_json = array(
                "total_count"        => count($data),
                "incomplete_results" => false,
                "items"              => $data
            );
            echo json_encode($data_json);
        }
    }
	
	private function get_kualifikasi($array = NULL, $id_kualifikasi_spk = NULL, $active = NULL, $deleted = NULL, $id_master_dokumen = NULL) {
		$kualifikasi	= $this->dsettingvendor->get_data_kualifikasi("open", $id_kualifikasi_spk, $active, $deleted, $id_master_dokumen);
		$kualifikasi 	= $this->general->generate_encrypt_json($kualifikasi, array("id_kualifikasi_spk"));
		if ($array) {
			return $kualifikasi;
		} else {
			echo json_encode($kualifikasi);
		}
	}
	
	private function save_user_role($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_user_role 	= (isset($_POST['id_user_role']) ? $this->generate->kirana_decrypt($_POST['id_user_role']) : NULL);
		$id_role 		= (isset($_POST['id_role']) ? $this->generate->kirana_decrypt($_POST['id_role']) : NULL);
		$nik	 		= (isset($_POST['nik']) ? $_POST['nik']: NULL);
		if ($id_user_role!=NULL){	
			$data_row = array(
				"id_role" 	=> $id_role,
				"nik"	 	=> $nik
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_user_role", $data_row, array(
				array(
					'kolom' => 'id_user_role',
					'value' => $id_user_role
				)
			));
		}else{
			$ck_nik 	= $this->dsettingvendor->get_data_user_role(NULL, NULL, NULL, NULL, $nik);
			if (count($ck_nik) != 0){ 
				$msg    = "NIK ".$nik." sudah diset role, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"id_role" 	=> $id_role,
				"nik"	 	=> $nik
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_vendor_user_role", $data_row);
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
	private function save_kualifikasi($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_kualifikasi_spk 	= (isset($_POST['id_kualifikasi_spk']) ? $this->generate->kirana_decrypt($_POST['id_kualifikasi_spk']) : NULL);
		$dokumen			 	= (isset($_POST['dokumen']) ? implode(",", $_POST['dokumen']) : NULL);
		$this->dgeneral->delete('tbl_vendor_kualifikasi_dokumen', array(
			array(
				'kolom' => "id_kualifikasi_spk",
				'value' => $id_kualifikasi_spk
			)
		));
		$arr_dokumen = explode(',', $dokumen);
		foreach ($arr_dokumen as $dt) {
			$data = array(
				"id_kualifikasi_spk" 	=> $id_kualifikasi_spk,
				"id_master_dokumen"		=> $dt
			);
			$data = $this->dgeneral->basic_column("insert", $data);
			$this->dgeneral->insert("tbl_vendor_kualifikasi_dokumen", $data);
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