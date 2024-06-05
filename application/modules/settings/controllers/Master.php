<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : MASTER JENIS DOKUMEN
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
	
	    $this->load->model('dmaster');
	}

	public function index(){
		show_404();
	}
	public function dokumen($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Jenis Dokumen";
		$data['title_form']  = "Form Master Jenis Dokumen";
		$data['dokumen'] 	 = $this->get_dokumen('array', NULL, NULL, NULL);
		$this->load->view("master/dokumen", $data);	
	}
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'dokumen':
				$id_dokumen 		= (isset($_POST['id_dokumen']) ? $this->generate->kirana_decrypt($_POST['id_dokumen']) : NULL);
				$na 				= (isset($_POST['na']) ? $_POST['na'] : NULL);
				$this->get_dokumen(NULL, $id_dokumen, $na, NULL, NULL, NULL);
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
				case 'dokumen':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_master_jenis_dokumen", array(
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
	private function get_dokumen($array = NULL, $id_dokumen = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $ck_id_dokumen = NULL) {
		$dokumen 	= $this->dmaster->get_data_dokumen("open", $id_dokumen, $active, $deleted, $nama, $ck_id_dokumen);
		$dokumen 	= $this->general->generate_encrypt_json($dokumen, array("id_dokumen"));
		if ($array) {
			return $dokumen;
		} else {
			echo json_encode($dokumen);
		}
	}
	
	private function save_dokumen($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_dokumen			= (isset($_POST['id_dokumen']) ? $this->generate->kirana_decrypt($_POST['id_dokumen']) : NULL);
		$nama				= (isset($_POST['nama']) ? $_POST['nama'] : NULL);

		if ($id_dokumen!=NULL){	
			$ck_nama_dokumen	= $this->dmaster->get_data_dokumen(NULL, NULL, NULL, NULL, $nama, $id_dokumen);
			if (count($ck_nama_dokumen) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"				=> $nama
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_master_jenis_dokumen", $data_row, array(
				array(
					'kolom' => 'id_dokumen',
					'value' => $id_dokumen
				)
			));
		}else{
			$ck_nama_dokumen	= $this->dmaster->get_data_dokumen(NULL, NULL, NULL, NULL, $nama);
			if (count($ck_nama_dokumen) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"				=> $nama
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_master_jenis_dokumen", $data_row);
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