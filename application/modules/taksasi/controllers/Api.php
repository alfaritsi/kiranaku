<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
/*
@application  : Taksasi Bokar
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Api extends MX_Controller
{
	private $admin;
	function __construct()
	{
		parent::__construct();
		$this->load->library('PHPExcel');
		$this->load->helper(array('form', 'url'));

		$this->load->model('dgeneral');
		$this->load->model('dapi');
		// $this->load->model('devent');
	}

	public function index()
	{
		show_404();
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'jadwal':
				$username 	= (isset($_POST['username']) ? $_POST['username'] : NULL);
				$pass 		= (isset($_POST['pass']) ? $_POST['pass'] : NULL);
				$tanggal 	= (isset($_POST['tanggal']) ? date("Y-m-d", strtotime($_POST['tanggal'])) : date('Y-m-d'));
				$param_jadwal = array(
					"connect" => TRUE,
					"data" => $this->input->post("data", TRUE),
					"return" => $this->input->post("return", TRUE),
					"username" => $username,
					"pass" => $pass,
					"tanggal" => $tanggal,
					"encrypt" => array("id")
				);
				$this->get_data_jadwal($param_jadwal);
				break;
			case 'login':
				$username 	= (isset($_POST['username']) ? $_POST['username'] : NULL);
				$pass 		= (isset($_POST['pass']) ? $_POST['pass'] : NULL);
				$param_user = array(
					"connect" => TRUE,
					"data" => $this->input->post("data", TRUE),
					"return" => $this->input->post("return", TRUE),
					"username" => $username,
					"pass" => $pass,
					"encrypt" => array("id")
				);
				$this->get_data_user($param_user);
				break;
			case 'nilai':
				$pabrik 	= (isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
				$id_jadwal	= (isset($_POST['id_jadwal']) ? $_POST['id_jadwal'] : NULL);
				$nik 		= (isset($_POST['nik']) ? $_POST['nik'] : NULL);
				$tanggal 	= (isset($_POST['tanggal']) ? date('Y-m-d', strtotime($_POST['tanggal'])) : date('Y-m-d'));
				$param_nilai = array(
					"connect" => TRUE,
					"data" => $this->input->post("data", TRUE),
					"return" => $this->input->post("return", TRUE),
					"pabrik" => $pabrik,
					"pabrik" => $pabrik,
					"id_jadwal" => $id_jadwal,
					"nik" => $nik,
					"tanggal" => $tanggal,
					"encrypt" => array("id")
				);
				$this->get_data_nilai($param_nilai);
				break;
			case 'nilai_detail':
				$nik 		= (isset($_POST['nik']) ? $_POST['nik'] : NULL);
				$tanggal 	= (isset($_POST['tanggal']) ? date('Y-m-d', strtotime($_POST['tanggal'])) : date('Y-m-d'));
				$param_detail = array(
					"connect" => TRUE,
					"data" => $this->input->post("data", TRUE),
					"return" => $this->input->post("return", TRUE),
					"nik" => $nik,
					"tanggal" => $tanggal,
					"encrypt" => array("id")
				);
				$this->get_data_nilai_detail($param_detail);
				break;
				
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function save($param = NULL)
	{
		switch ($param) {
			case 'input':
				$this->save_input($param);
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

	private function save_input($param)
	{
		$fp      = fopen('php://input', 'r');
		$rawData = stream_get_contents($fp);
		if ($rawData) {
			$postData = json_decode($rawData, true);
			$_POST    = $postData;
		}
		$post  = $this->input->post(NULL, TRUE);

		$datetime	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$username = (isset($_POST['username']) ? $_POST['username'] : NULL);
		$pass 	  = (isset($_POST['pass']) ? $_POST['pass'] : NULL);


		//set delete nilai detail
		$data_delete = array(
			"na"     		=> 'y',
			"del"			=> 'y',
			'tanggal_edit'	=> $datetime,
			'login_edit'	=> base64_decode($this->session->userdata("-id_user-")),
		);
		$this->dgeneral->update("tbl_taksasi_jadwal_peserta_nilai_detail",$data_delete, array(
			array(
				'kolom' => 'pabrik',
				'value' => $this->general->emptyconvert($post['pabrik'], NULL),
			),
			array(
				'kolom' => 'id_jadwal',
				'value' => $this->general->emptyconvert($post['id_jadwal'], NULL),
			),
			array(
				'kolom' => 'nik',
				'value' => $this->general->emptyconvert($post['nik'], NULL),
			),
			array(
				'kolom' => 'tanggal',
				'value' => $this->general->emptyconvert(date('Y-m-d', strtotime($post['tanggal'])), NULL)
			)
		));

		//insert nilai detail
		$data_nilai_details = array();
		foreach ($post['no_sample'] as $index => $no_sample) {	
			$data_nilai_detail    = array(
				"id_jadwal" 	=> $this->general->emptyconvert($post['id_jadwal'], NULL),
				"nik"	 		=> $this->general->emptyconvert($post['nik'], NULL),
				"pabrik" 		=> $this->general->emptyconvert($post['pabrik'], NULL),
				"tanggal" 		=> $this->general->emptyconvert(date('Y-m-d', strtotime($post['tanggal'])), NULL),
				"no_sample"		=> $this->general->emptyconvert($post['no_sample'][$index], NULL),
				"cut_kwe"		=> $this->general->emptyconvert($post['cut_kwe'][$index], NULL),
				"vm"			=> $this->general->emptyconvert($post['vm'][$index], NULL),
				"drc"			=> $this->general->emptyconvert($post['drc'][$index], NULL),
				"tanggal_buat"	=> $datetime,
				"login_buat"	=> base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit"	=> $datetime,
				"login_edit"	=> base64_decode($this->session->userdata("-id_user-")),
				"na" 			=> 'n',
				"del" 			=> 'n',
			);
			$data_nilai_detail = $this->dgeneral->basic_column('insert', $data_nilai_detail, $datetime);
			$data_nilai_details[] = $data_nilai_detail;
		}
		$this->dgeneral->insert_batch('tbl_taksasi_jadwal_peserta_nilai_detailxx', $data_nilai_details);
		

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data sudah disimpan.";
			$sts = "OK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function get_data_jadwal($param)
	{
		//header
		$result = $this->dapi->get_data_jadwal($param);
		if($result){
			//arr_pabrik
			$data_pabrik			= $this->general->get_master_plant();
			$result[0]->arr_pabrik  = $data_pabrik;
		}
		
		echo json_encode($result);
	}
	
	private function get_data_user($param)
	{
		//header
		$result = $this->dapi->get_data_user($param);
		echo json_encode($param);
		exit;
		echo json_encode($result);
	}
	
	private function get_data_nilai($param)
	{
		//header
		$result = $this->dapi->get_data_nilai($param);
		echo json_encode($result);
	}
	
	private function get_data_nilai_detail($param)
	{
		//header
		$result = $this->dapi->get_data_nilai_detail($param);
		echo json_encode($result);
	}

	/*====================================================================*/
}
