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

Class Master extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
	
	    $this->load->model('dmasterbank');
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
		
		$data['title']    	 = "Role Bank Specimen";
		$data['title_form']  = "Form Role Bank Specimen";
		$data['role'] 	 	 = $this->get_role('array', NULL, NULL, NULL);
		$this->load->view("master/role", $data);	
	}
	public function dokumen($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Dokumen Bank Specimen";
		$data['title_form']  = "Form Dokumen Bank Specimen";
		$data['dokumen'] 	 = $this->get_dokumen('array', NULL, NULL, NULL);
		$this->load->view("master/dokumen", $data);	
	}
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'role':
				$id_role = (isset($_POST['id_role']) ? $this->generate->kirana_decrypt($_POST['id_role']) : NULL);
				$this->get_role(NULL, $id_role);
				break;
			case 'dokumen':
				$id_dokumen 		= (isset($_POST['id_dokumen']) ? $this->generate->kirana_decrypt($_POST['id_dokumen']) : NULL);
				$id_data_temp		= (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']): NULL);
				$jenis_pengajuan 	= (isset($_POST['jenis_pengajuan']) ? $_POST['jenis_pengajuan'] : NULL);
				$na 				= (isset($_POST['na']) ? $_POST['na'] : NULL);
				$this->get_dokumen(NULL, $id_dokumen, $na, NULL, NULL, NULL, $id_data_temp, $jenis_pengajuan);
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
				case 'role':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_bank_role", array(
						array(
							'kolom' => 'id_role',
							'value' => $this->generate->kirana_decrypt($_POST['id_role'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'dokumen':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_bank_dokumen", array(
						array(
							'kolom' => 'id_dokumen',
							'value' => $this->generate->kirana_decrypt($_POST['id_dokumen'])
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
			case 'role':
				$this->save_role($param);
				break;
			case 'dokumen':
				$this->save_dokumen($param);
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
	private function get_dokumen($array = NULL, $id_dokumen = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $ck_id_dokumen = NULL, $id_data_temp = NULL, $jenis_pengajuan = NULL) {
		$dokumen 	= $this->dmasterbank->get_data_dokumen("open", $id_dokumen, $active, $deleted, $nama, $ck_id_dokumen, $id_data_temp, $jenis_pengajuan);
		$dokumen 	= $this->general->generate_encrypt_json($dokumen, array("id_dokumen"));
		if ($array) {
			return $dokumen;
		} else {
			echo json_encode($dokumen);
		}
	}
	
	private function save_role($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_role				 = (isset($_POST['id_role']) ? $this->generate->kirana_decrypt($_POST['id_role']) : NULL);
		$nama					 = (isset($_POST['nama']) ? $_POST['nama'] : NULL);
		$level					 = (isset($_POST['level']) ? $_POST['level'] : NULL);
		$tipe_user				 = (isset($_POST['tipe_user']) ? $_POST['tipe_user'] : NULL);
		$if_approve				 = (isset($_POST['if_approve']) ? $_POST['if_approve'] : NULL);
		$if_decline				 = (isset($_POST['if_decline']) ? $_POST['if_decline'] : NULL);
		$if_approve_perubahan	 = (isset($_POST['if_approve_perubahan']) ? $_POST['if_approve_perubahan'] : NULL);
		$if_decline_perubahan	 = (isset($_POST['if_decline_perubahan']) ? $_POST['if_decline_perubahan'] : NULL);
		$if_approve_penutupan	 = (isset($_POST['if_approve_penutupan']) ? $_POST['if_approve_penutupan'] : NULL);
		$if_decline_penutupan	 = (isset($_POST['if_decline_penutupan']) ? $_POST['if_decline_penutupan'] : NULL);
		$if_approve_ho			 = (isset($_POST['if_approve_ho']) ? $_POST['if_approve_ho'] : NULL);
		$if_decline_ho			 = (isset($_POST['if_decline_ho']) ? $_POST['if_decline_ho'] : NULL);
		$if_approve_perubahan_ho = (isset($_POST['if_approve_perubahan_ho']) ? $_POST['if_approve_perubahan_ho'] : NULL);
		$if_decline_perubahan_ho = (isset($_POST['if_decline_perubahan_ho']) ? $_POST['if_decline_perubahan_ho'] : NULL);
		$if_approve_penutupan_ho = (isset($_POST['if_approve_penutupan_ho']) ? $_POST['if_approve_penutupan_ho'] : NULL);
		$if_decline_penutupan_ho = (isset($_POST['if_decline_penutupan_ho']) ? $_POST['if_decline_penutupan_ho'] : NULL);
		
		if ($id_role!=NULL){	
			$ck_nama_role	= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, NULL, $nama, $id_role);
			if (count($ck_nama_role) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$ck_level_role	= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, $level, NULL, $id_role);
			if (count($ck_level_role) != 0){ 
				$msg    = "Duplicate Level, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"						=> $nama,
				"level"						=> $level,
				"tipe_user"					=> $tipe_user,
				"if_approve"				=> $if_approve,
				"if_decline"				=> $if_decline,
				"if_approve_perubahan"		=> $if_approve_perubahan,
				"if_decline_perubahan"		=> $if_decline_perubahan,
				"if_approve_penutupan"		=> $if_approve_penutupan,
				"if_decline_penutupan"		=> $if_decline_penutupan,
				"if_approve_ho"				=> $if_approve_ho,
				"if_decline_ho"				=> $if_decline_ho,
				"if_approve_perubahan_ho"	=> $if_approve_perubahan_ho,
				"if_decline_perubahan_ho"	=> $if_decline_perubahan_ho,
				"if_approve_penutupan_ho"	=> $if_approve_penutupan_ho,
				"if_decline_penutupan_ho"	=> $if_decline_penutupan_ho,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_bank_role", $data_row, array(
				array(
					'kolom' => 'id_role',
					'value' => $id_role
				)
			));
		}else{
			$ck_nama_role	= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, NULL, $nama);
			if (count($ck_nama_role) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$ck_level_role	= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, $level, NULL);
			if (count($ck_level_role) != 0){ 
				$msg    = "Duplicate Level, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"						=> $nama,
				"level"						=> $level,
				"tipe_user"					=> $tipe_user,
				"if_approve"				=> $if_approve,
				"if_decline"				=> $if_decline,
				"if_approve_perubahan"		=> $if_approve_perubahan,
				"if_decline_perubahan"		=> $if_decline_perubahan,
				"if_approve_penutupan"		=> $if_approve_penutupan,
				"if_decline_penutupan"		=> $if_decline_penutupan,
				"if_approve_ho"				=> $if_approve_ho,
				"if_decline_ho"				=> $if_decline_ho,
				"if_approve_perubahan_ho"	=> $if_approve_perubahan_ho,
				"if_decline_perubahan_ho"	=> $if_decline_perubahan_ho,
				"if_approve_penutupan_ho"	=> $if_approve_penutupan_ho,
				"if_decline_penutupan_ho"	=> $if_decline_penutupan_ho,
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_bank_role", $data_row);
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
	
	private function save_dokumen($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_dokumen			= (isset($_POST['id_dokumen']) ? $this->generate->kirana_decrypt($_POST['id_dokumen']) : NULL);
		$nama				= (isset($_POST['nama']) ? $_POST['nama'] : NULL);
		$jenis_pengajuan	= (isset($_POST['jenis_pengajuan']) ? implode(",", $_POST['jenis_pengajuan']) : NULL);
		
		if ($id_dokumen!=NULL){	
			$ck_nama_dokumen	= $this->dmasterbank->get_data_dokumen(NULL, NULL, NULL, NULL, $nama, $id_dokumen);
			if (count($ck_nama_dokumen) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"				=> $nama,
				"jenis_pengajuan"	=> $jenis_pengajuan,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_bank_dokumen", $data_row, array(
				array(
					'kolom' => 'id_dokumen',
					'value' => $id_dokumen
				)
			));
		}else{
			$ck_nama_dokumen	= $this->dmasterbank->get_data_dokumen(NULL, NULL, NULL, NULL, $nama);
			if (count($ck_nama_dokumen) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"				=> $nama,
				"jenis_pengajuan"	=> $jenis_pengajuan,
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_bank_dokumen", $data_row);
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