<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : BUKU TAMU
@author       : Lukman Hakim (7143)
@contributor  : 
      1. Benazi S. Bahari (10183) 17-06-2021
         tambah method konfirmasi kehadiran untuk peserta event         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Transaksi extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		
	    $this->load->model('material/dtransaksimaterial');
	    $this->load->model('dtransaksitamu');
	}

	public function index(){
		show_404();
	}
	
	public function data($param=NULL){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$filter_from		 = date("d.m.Y");
		$filter_to 			 = date("d.m.Y");
		$data['title']    	 = "Data Buku Tamu";
		$data['title_form']  = "Data Buku Tamu";
		$data['tamu']  	 	 = $this->get_tamu('array', NULL, NULL, NULL, $filter_from, $filter_to);
		$this->load->view("transaksi/tamu", $data);	
	}
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'data':
				$id_tamu 		  = (isset($_POST['id_tamu']) ? $this->generate->kirana_decrypt($_POST['id_tamu']) : NULL);
				$filter_from 	  = (isset($_POST['filter_from'])) ? $_POST['filter_from'] : NULL;
				$filter_to 		  = (isset($_POST['filter_to'])) ? $_POST['filter_to'] : NULL;
				if(isset($_POST['filter_status'])){
					$filter_status	= array();
					foreach ($_POST['filter_status'] as $dt) {
						array_push($filter_status, $dt);
					}
				}else{
					$filter_status  = NULL;
				}
				$this->get_tamu(NULL, $id_tamu, NULL, NULL, $filter_from, $filter_to, $filter_status);
				break;
            case 'karyawan':
                $this->general->connectDbPortal();
                $list = $this->dtransaksitamu->get_karyawan(
                    array(
                        'search' => $this->input->post('q')
                    )
					,$param2
                );
                echo json_encode(array('data' => $list));
                break;
			case 'assessment':
				$id_tamu = (isset($_POST['id_tamu']) ? $this->generate->kirana_decrypt($_POST['id_tamu']) : NULL);
				$param_dt = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "id_tamu" => $id_tamu,
                );
				$this->get_data_assessment($param_dt);
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
				case 'tamu':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_tamu", array(
						array(
							'kolom' => 'id_tamu',
							'value' => $this->generate->kirana_decrypt($_POST['id_tamu'])
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
			case 'konfirmasi':
				$this->save_konfirmasi($param);
				break;
			case 'tamu':
				$this->save_tamu($param);
				break;
			case 'konfirmasi_hadir':
				$this->save_konfirmasi_hadir($param);
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
	private function get_tamu($array = NULL, $id_tamu = NULL, $active = NULL, $deleted = NULL, $filter_from = NULL, $filter_to = NULL, $filter_status = NULL) {
		$tamu	= $this->dtransaksitamu->get_data_tamu("open", $id_tamu, $active, $deleted, $filter_from, $filter_to, $filter_status);
		$tamu 	= $this->general->generate_encrypt_json($tamu, array("id_tamu"));
		
		if ($array) {
			return $tamu;
		} else {
			echo json_encode($tamu);
		}
	}

	private function get_data_assessment($param = NULL) 
	{
        $result = $this->dtransaksitamu->get_data_assessment($param);

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
	}
	
	private function save_konfirmasi($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$id_tamu		= (isset($_POST['id_tamu']) ? $this->generate->kirana_decrypt($_POST['id_tamu']) : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if ($id_tamu != NULL){
			$data_row = array(
				"nik_karyawan"	=> $_POST['nik_karyawan'],
				"nama_karyawan" => $_POST['nama_karyawan'],
				"completed" 	=> 'y',
				"waktu_pulang"	=> $datetime
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_tamu", $data_row, array(
				array(
					'kolom' => 'id_tamu',
					'value' => $id_tamu
				)
			));
			
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
	
	private function save_tamu($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$id_tamu		= (isset($_POST['id_tamu']) ? $this->generate->kirana_decrypt($_POST['id_tamu']) : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if ($id_tamu != NULL){
			$data_row = array(
				"nik_karyawan"	=> $_POST['nik_karyawan'],
				"nama_karyawan" => $_POST['nama_karyawan'],
				"waktu_pulang"	=> $_POST['waktu_pulang']
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_tamu", $data_row, array(
				array(
					'kolom' => 'id_tamu',
					'value' => $id_tamu
				)
			));
			
		}else{
			$data_row = array(
				"tanggal_kunjungan" => $_POST['tanggal_kunjungan'],
				"nama_tamu" 		=> $_POST['nama_tamu'],
				"perusahaan" 		=> $_POST['perusahaan'],
				"waktu_datang" 		=> $_POST['waktu_datang'],
				"waktu_pulang" 		=> $_POST['waktu_pulang'],
				"nik_tamu" 			=> $_POST['nik_tamu'],
				"telepon" 			=> $_POST['telepon'],
				"tujuan_kunjungan" 	=> $_POST['tujuan_kunjungan'],
				"nama_karyawan" 	=> $_POST['nama_karyawan'],
				"nik_karyawan" 		=> $_POST['nik_karyawan']
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_tamu", $data_row);
			
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

	private function save_konfirmasi_hadir($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$id_tamu		= (isset($_POST['id_tamu']) ? $this->generate->kirana_decrypt($_POST['id_tamu']) : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if ($id_tamu != NULL){
			$data_peserta = $this->dtransaksitamu->get_data_peserta(array(
				"connect" => false,
				"id_tamu" => $id_tamu
			));

			$data_row = array(
				"completed" 	=> 'y',
				"waktu_datang"  => $data_peserta->waktu_mulai,
				"waktu_pulang"	=> $data_peserta->waktu_selesai
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_tamu", $data_row, array(
				array(
					'kolom' => 'id_tamu',
					'value' => $id_tamu
				)
			));
			
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