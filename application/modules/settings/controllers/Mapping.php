<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Mapping Plant
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Mapping extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		
	    $this->load->model('dmapping');
	}

	public function index(){
		show_404();
	}
	
	public function planth($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Mapping Plant Header";
		$data['title_form']  = "Form Mapping Plant Header";
		$data['plant'] 		 = $this->dgeneral->get_master_plant();
		$data['plant_header']= $this->get_plant_header('array', NULL, NULL, NULL);
		$this->load->view("plant_header", $data);	
	}
	public function plantd($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Mapping Plant Detail";
		$data['title_form']  = "Form Mapping Plant Detail";
		$data['plant'] 	 	 = $this->dgeneral->get_master_plant();
		$data['plant_detail']= $this->get_plant_detail('array', NULL, NULL, NULL);
		$this->load->view("plant_detail", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'plant_header':
				$apps = (isset($_POST['apps']) ? $_POST['apps'] : NULL);
				$this->get_plant_header(NULL, $apps, NULL, NULL);
				break;
			case 'plant_detail':
				$apps = (isset($_POST['apps']) ? $_POST['apps'] : NULL);
				$this->get_plant_detail(NULL, $apps, NULL, NULL);
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
				case 'plant_header':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_mapping_plant_header", array(
						array(
							'kolom' => 'apps',
							'value' => $_POST['apps']
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'plant_detail':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_mapping_plant_detail", array(
						array(
							'kolom' => 'apps',
							'value' => $_POST['apps']
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
			case 'plant_header':
				$this->save_plant_header($param);
				break;
			case 'plant_detail':
				$this->save_plant_detail($param);
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
	private function get_plant_header($array = NULL, $apps = NULL, $active = NULL, $deleted = NULL) {
		$plant_header 		= $this->dmapping->get_data_plant_header("open", $apps, $active, $deleted);
		if ($array) {
			return $plant_header;
		} else {
			echo json_encode($plant_header);
		}
	}
	private function get_plant_detail($array = NULL, $apps = NULL, $active = NULL, $deleted = NULL) {
		$plant_detail 		= $this->dmapping->get_data_plant_detail("open", $apps, $active, $deleted);
		if ($array) {
			return $plant_detail;
		} else {
			echo json_encode($plant_detail);
		}
	}
	private function save_plant_header($param) {
		$datetime		= date("Y-m-d H:i:s");
		$apps 			= (isset($_POST['apps']) ? $_POST['apps'] : NULL);
		$plant_exclude	= (isset($_POST['plant_exclude']) ? implode(",", $_POST['plant_exclude']) : NULL);
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		$plant_header = $this->dmapping->get_data_plant_header(NULL, $apps);
		if (count($plant_header) != 0){
			$data_row = array(
				"plant_exclude"	=> $plant_exclude
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_mapping_plant_header", $data_row, array(
				array(
					'kolom' => 'apps',
					'value' => $apps
				)
			));
		}else{
			$data_row = array(
				"apps" 			=> $apps,
				"plant_exclude"	=> $plant_exclude
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_mapping_plant_header", $data_row);
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
	
	private function save_plant_detail($param) {
		$datetime		= date("Y-m-d H:i:s");
		$apps 			= (isset($_POST['apps']) ? $_POST['apps'] : NULL);
		$plant			= (isset($_POST['plant']) ? $_POST['plant'] : NULL);
		$plant_access	= (isset($_POST['plant_access']) ? implode(",", $_POST['plant_access']) : NULL);
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		$plant_detail = $this->dmapping->get_data_plant_detail(NULL, $apps);
		if (count($plant_detail) != 0){
			$data_row = array(
				"plant"			=> $plant,
				"plant_access"	=> $plant_access
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_mapping_plant_detail", $data_row, array(
				array(
					'kolom' => 'apps',
					'value' => $apps
				)
			));
		}else{
			$data_row = array(
				"apps" 			=> $apps,
				"plant"			=> $plant,
				"plant_access"	=> $plant_access
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_mapping_plant_detail", $data_row);
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