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
Class Laporan extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->model('dlaporaness');
	}

	public function index(){
		show_404();
	}
	public function mapping($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
	
		$data['title']    	 = "Display Group Produksi";
		$data['title_form']  = "Display Group Produksi";
		if(base64_decode($this->session->userdata("-ho-"))=='y'){
			$data['plant'] 		 = $this->dgeneral->get_master_plant();
		}else{
			$data['plant'] 		 = $this->dgeneral->get_master_plant(base64_decode($this->session->userdata("-gsber-"))); 
		}
		$data['group']	 	 = $this->get_group('array');
		$data['bagian']	 	 = $this->get_bagian('array');
		$this->load->view("laporan/mapping", $data);	
	}
	public function hadir($param=NULL){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		$tanggal_awal                = date_create();
		$tanggal_akhir               = date_create();
		$data['tanggal_awal']  = $tanggal_awal;
		$data['tanggal_akhir'] = $tanggal_akhir;
		
		$data['title']    	 = "Data Absensi Kehadiran";
		$data['title_form']  = "Data Absensi Kehadiran";
		// $data['absensi'] 	 = $this->get_absensi('array');
		// // $data['absensi'] 	 = $this->dlaporaness->get_data_absensi();
		$data['group']	 	 = $this->get_group('array');
		$data['mp']		 	 = $this->get_mp('array');
		$this->load->view("laporan/hadir", $data);	
	}
	
	public function absensi($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		$tanggal_awal                = date_create();
		$tanggal_akhir               = date_create();
		$data['tanggal_awal']  = $tanggal_awal;
		$data['tanggal_akhir'] = $tanggal_akhir;
		
		$data['title']    	 = "Data Absensi Kehadiran";
		$data['title_form']  = "Data Absensi Kehadiran";
		$data['group']	 	 = $this->get_group('array');
		$data['mp']		 	 = $this->get_mp('array');
		$this->load->view("laporan/absensi", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'absensi':
				$nik  = (isset($_POST['nik']) ? $this->generate->kirana_decrypt($_POST['nik']) : NULL);
				$tanggal_awal  	= (isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal']: NULL);
				$tanggal_akhir  = (isset($_POST['tanggal_akhir']) ? $_POST['tanggal_akhir']: NULL);
				if(isset($_POST['group_produksi'])){
					$group_produksi	= array();
					foreach ($_POST['group_produksi'] as $dt) {
						array_push($group_produksi, $dt);
					}
				}else{
					$group_produksi  = NULL;
				}
				if($param2=='bom'){
					header('Content-Type: application/json');
					$return = $this->dlaporaness->get_data_absensi_bom('open', $nik, NULL, NULL, $group_produksi, $tanggal_awal, $tanggal_akhir);
					echo $return;
					break;
				}else if($param2=='auto'){
					if (isset($_GET['q'])) {
						$data      = $this->dlaporaness->get_data_absensi('open', NULL, NULL, NULL, $_GET['q']);
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
					}
				}else{
					$this->get_absensi(NULL, $nik, NULL, NULL, $group_produksi, $tanggal_awal, $tanggal_akhir);
					break;
				}
			case 'mapping':
				$nik  = (isset($_POST['nik']) ? $_POST['nik'] : NULL);
				$pabrik  = (isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
				if(isset($_POST['group_produksi'])){
					$group_produksi	= array();
					foreach ($_POST['group_produksi'] as $dt) {
						array_push($group_produksi, $dt);
					}
				}else{
					$group_produksi  = NULL;
				}
				if(isset($_POST['bagian'])){
					$bagian	= array();
					foreach ($_POST['bagian'] as $dt) {
						array_push($bagian, $dt);
					}
				}else{
					$bagian  = NULL;
				}
				if($param2=='bom'){
					header('Content-Type: application/json');
					$return = $this->dlaporaness->get_data_mapping_bom('open', $nik, NULL, NULL, $pabrik, $group_produksi, $bagian);
					echo $return;
					break;
				}else if($param2=='auto'){
					if (isset($_GET['q'])) {
						$data      = $this->dlaporaness->get_data_mapping('open', NULL, NULL, NULL, $_GET['q']);
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
					} 
				}else{
					$this->get_mapping(NULL, $nik, NULL, NULL);
					break;
				}
			case 'absen':
				$group_produksi	  = (isset($_POST['group_produksi']) ? $_POST['group_produksi'] : NULL);
				$bagian 		  = (isset($_POST['bagian']) ? $_POST['bagian'] : NULL);
				$this->get_absen(NULL, $group_produksi, $bagian);
				break;
				
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function save($param = NULL) {
		switch ($param) {
			case 'mapping':
				$this->save_mapping($param);
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
	private function get_absensi($array = NULL, $nik = NULL, $active = NULL, $deleted = NULL, $group_produksi = NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL) {
		$absensi	= $this->dlaporaness->get_data_absensi("open", $nik, $active, $deleted, $group_produksi, $tanggal_awal, $tanggal_akhir);
		if ($array) {
			return $absensi;
		} else {
			echo json_encode($absensi);
		}
	}
	private function get_mapping($array = NULL, $nik = NULL, $active = NULL, $deleted = NULL, $pabrik = NULL) {
		$mapping	= $this->dlaporaness->get_data_mapping("open", $nik, $active, $deleted, $pabrik);
		if ($array) {
			return $mapping;
		} else {
			echo json_encode($mapping);
		}
	}
	
	private function get_group($array = NULL) {
		$group 		= $this->dlaporaness->get_data_group("open");
		if ($array) {
			return $group;
		} else {
			echo json_encode($group);
		}
	}
	
	private function get_bagian($array = NULL) {
		$bagian 		= $this->dlaporaness->get_data_bagian("open");
		if ($array) {
			return $bagian;
		} else {
			echo json_encode($bagian);
		}
	}
	private function get_pabrik($array = NULL) {
		$pabrik 		= $this->dlaporaness->get_data_pabrik("open");
		if ($array) {
			return $pabrik;
		} else {
			echo json_encode($pabrik);
		}
	}
	private function get_mp($array = NULL) {
		$mp 		= $this->dlaporaness->get_data_mp("open");
		if ($array) {
			return $mp;
		} else {
			echo json_encode($mp);
		}
	}
	private function get_absen($array = NULL, $group_produksi = NULL, $bagian = NULL) {
		$absen	= $this->dlaporaness->get_data_absen("open", $group_produksi, $bagian);
		if ($array) {
			return $absen;
		} else {
			echo json_encode($absen);
		}
	}
	
	private function save_mapping($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$nik 	= isset($_POST["nik"])?$_POST["nik"]:null;
		$group 	= isset($_POST["group"])?$_POST["group"]:null;
		$bagian = isset($_POST["bagian"])?$_POST["bagian"]:null;
		
		//update data(tolak)
		$data_row = array(
			"prunt_web" 	=> $bagian,
			"prgrp_web" 	=> $group
		);
		// $data_row = $this->dgeneral->basic_column("update", $data_row);
		$this->dgeneral->update("tbl_karyawan", $data_row, array(
			array(
				'kolom' => 'nik',
				'value' => $nik 
			)
		));

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