<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : TAKSASI BOKAR
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
	
	    $this->load->model('dmastertaksasi');
	}

	public function index(){
		show_404();
	}
	public function tahap($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Tahap";
		$data['title_form']  = "Form Master Tahap";
		$data['tahap']	 	 = $this->get_tahap('array', NULL, NULL, NULL);
		$this->load->view("master/tahap", $data);	
	}
	
	public function nilai($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Penilaian";
		$data['title_form']  = "Form Master Penilaian";
		$data['nilai']	 	 = $this->get_nilai('array', NULL, NULL, NULL);
		$this->load->view("master/nilai", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'tahap':
				$id_tahap = (isset($_POST['id_tahap']) ? $this->generate->kirana_decrypt($_POST['id_tahap']) : NULL);
				$this->get_tahap(NULL, $id_tahap, NULL, NULL);
				break;
			case 'nilai':
				$id_nilai = (isset($_POST['id_nilai']) ? $this->generate->kirana_decrypt($_POST['id_nilai']) : NULL);
				$this->get_nilai(NULL, $id_nilai, NULL, NULL);
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
				case 'tahap':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_taksasi_tahap", array(
						array(
							'kolom' => 'id_tahap',
							'value' => $this->generate->kirana_decrypt($_POST['id_tahap'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'nilai':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_taksasi_nilai", array(
						array(
							'kolom' => 'id_nilai',
							'value' => $this->generate->kirana_decrypt($_POST['id_nilai'])
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
			case 'tahap':
				$this->save_tahap($param);
				break;
			case 'nilai':
				$this->save_nilai($param);
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
	private function get_tahap($array = NULL, $id_tahap = NULL, $active = NULL, $deleted = NULL, $nama = NULL) {
		$tahap 		= $this->dmastertaksasi->get_data_tahap("open", $id_tahap, $active, $deleted, $nama);
		$tahap 		= $this->general->generate_encrypt_json($tahap, array("id_tahap"));
		if ($array) {
			return $tahap;
		} else {
			echo json_encode($tahap);
		}
	}
	private function get_nilai($array = NULL, $id_nilai = NULL, $active = NULL, $deleted = NULL, $nama = NULL) {
		$nilai 		= $this->dmastertaksasi->get_data_nilai("open", $id_nilai, $active, $deleted, $nama);
		$nilai 		= $this->general->generate_encrypt_json($nilai, array("id_nilai"));
		if ($array) {
			return $nilai;
		} else {
			echo json_encode($nilai);
		}
	}
	
	private function save_tahap($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_tahap	 = (isset($post['id_tahap']) ? $this->generate->kirana_decrypt($post['id_tahap']) : NULL);
		$nama 		 = (isset($post['nama']) ? strtoupper($post['nama']) : NULL);
		
		if ($id_tahap!=NULL){	
			$ck_nama_tahap	= $this->dmastertaksasi->get_data_tahap(NULL, NULL, NULL, NULL, $nama, $id_tahap);
			if (count($ck_nama_tahap) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"			=> $nama,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_taksasi_tahap", $data_row, array(
				array(
					'kolom' => 'id_tahap',
					'value' => $id_tahap
				)
			));
		}else{
			$ck_nama_tahap	= $this->dmastertaksasi->get_data_tahap(NULL, NULL, NULL, NULL, $nama);
			if (count($ck_nama_tahap) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"			=> $nama,
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_taksasi_tahap", $data_row);
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
	
	private function save_nilai($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_nilai	 = (isset($post['id_nilai']) ? $this->generate->kirana_decrypt($post['id_nilai']) : NULL);
		$nama 		 = (isset($post['nama']) ? strtoupper($post['nama']) : NULL);
		
		if ($id_nilai!=NULL){	
			$ck_nama_nilai	= $this->dmastertaksasi->get_data_nilai(NULL, NULL, NULL, NULL, $nama, $id_nilai);
			if (count($ck_nama_nilai) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"			=> $nama,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_taksasi_nilai", $data_row, array(
				array(
					'kolom' => 'id_nilai',
					'value' => $id_nilai
				)
			));
		}else{
			$ck_nama_nilai	= $this->dmastertaksasi->get_data_nilai(NULL, NULL, NULL, NULL, $nama);
			if (count($ck_nama_nilai) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"			=> $nama,
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_taksasi_nilai", $data_row);
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