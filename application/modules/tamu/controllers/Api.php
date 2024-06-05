<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Buku Tamu
@author       : Lukman Hakim (7143)
@contributor  : 
      1. Benazi S. Bahari (10183) 16-06-2021
         tambah fungsi untuk get data peserta event, self assessment         
      2. <insert your fullname> (<insert your nik>) <insert the date>
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
		$this->load->model('dtransaksitamu');
		$this->load->model('devent');
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
			case 'peserta':
				$id_event 		  = (isset($_POST['id_event']) ? $this->generate->kirana_decrypt($_POST['id_event']) : NULL);
				$id_peserta 	  = ($param2 ? $this->generate->kirana_decrypt($param2) : NULL);
				$param_peserta = array(
					"connect" => TRUE,
					"data" => $this->input->post("data", TRUE),
					"return" => $this->input->post("return", TRUE),
					"id_event" => $id_event,
					"id_peserta" => $id_peserta,
					"encrypt" => array("id")
				);
				$this->get_data_peserta($param_peserta);
				break;
			case 'pertanyaan':
				$this->get_pertanyaan(NULL);
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

		$id_event 		  = (isset($_POST['id_event']) ? $this->generate->kirana_decrypt($_POST['id_event']) : NULL);
		$id_peserta 	  = (isset($_POST['id_peserta']) ? $this->generate->kirana_decrypt($_POST['id_peserta']) : NULL);

		$nik_karyawan = null;
		$waktu_datang = $datetime;
		if ($id_peserta){
			$waktu_datang = null;
			$param_peserta = array(
				"connect" => FALSE,
				// "id_event" => $id_event,
				"id_peserta" => $id_peserta,
				"encrypt" => array("id")
			);
			$data_peserta = $this->devent->get_data_peserta($param_peserta);
			if ($data_peserta) {
				$tanggal_kunjungan = $this->generate->regenerateDateFormat($data_peserta->tanggal_format);
				$nik_karyawan = $data_peserta->nik_pic;
				//batas waktu pengisian
				// $date1 = date_create('2021-06-17');
				$date1 = date_create($tanggal_kunjungan);
				$date2 = date_create(date('Y-m-d'));
				$diff = date_diff($date1, $date2);
				$selisih_waktu = intval($diff->format("%R%a"));
				if ($selisih_waktu < -1 || $selisih_waktu > 0) {
					$msg    = "Anda Belum Diperbolehkan Mengisi Daily Self Assessment. Harap Mengisi H-1 Atau Hari H Acara";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				// check sudah dientri
				$cek_data = $this->dtransaksitamu->get_data_assessment(
						array(
							"connect" => FALSE,
							"id_peserta" => $id_peserta
						)
					);
				if (count($cek_data)) {
					$msg    = "Daily Self Assessment Anda sudah terinput";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
			}
		} else {
			$tanggal_kunjungan = date("Y-m-d");
		}
		
		//save tamu
		$data_tamu["tanggal_kunjungan"]	= $tanggal_kunjungan;
		$data_tamu["nama_tamu"]			= $post['nama_tamu'];
		$data_tamu["email"]				= $post['email'];
		$data_tamu["perusahaan"]		= $post['perusahaan'];
		$data_tamu["waktu_datang"]		= $waktu_datang;
		$data_tamu["nik_tamu"]			= $post['nik_tamu'];
		$data_tamu["telepon"]			= $post['telepon'];
		$data_tamu["tujuan_kunjungan"]	= $post['tujuan_kunjungan'];
		$data_tamu["nama_karyawan"]		= $post['nama_karyawan'];
		$data_tamu["durasi_plan"]		= $post['durasi_plan'];
		$data_tamu["completed"]			= 'n';
		$data_tamu["nik_karyawan"]		= $nik_karyawan;
		$data_tamu = $this->dgeneral->basic_column("insert", $data_tamu);
		$this->dgeneral->insert("tbl_tamu", $data_tamu);
		$id_tamu = $this->db->insert_id();
		
		//save assessment
		$total_score 		= 0;
		$total_score_danger	= 0;
		$arr_pertanyaan    	= explode(",", $post['all_pertanyaan']);
		foreach ($arr_pertanyaan as $id_pertanyaan) {
			$total_score += $post['score_' . $id_pertanyaan];
			$total_score_danger += $post['score_' . $id_pertanyaan . '_is_danger'];
			$data_assessment["id_tamu"]				= $id_tamu;
			$data_assessment["id_peserta"]				= $id_peserta;
			$data_assessment["tanggal"]				= date("Y-m-d");
			$data_assessment["suhu_tubuh"]				= $post['suhu_tubuh'];
			$data_assessment["id_pertanyaan"]			= $post['id_pertanyaan_' . $id_pertanyaan];
			$data_assessment["score"]		 			= $post['score_' . $id_pertanyaan];
			$data_assessment["is_danger"]		 		= $post['score_' . $id_pertanyaan . '_is_danger'];
			// $data_assessment["login_edit"]	 			= base64_decode($this->session->userdata("-id_user-"));
			$data_assessment["tanggal_edit"]			= $datetime;
			
			//tambahan
			if ($id_pertanyaan == 15) {
				$suhu_tertinggi   = (isset($post['suhu_tertinggi_' . $id_pertanyaan]) ? $post['suhu_tertinggi_' . $id_pertanyaan] : NULL);
				$gejala   		  = (isset($post['gejala_' . $id_pertanyaan]) ? $post['gejala_' . $id_pertanyaan] : NULL);
				$riwayat_dokter   = (isset($post['riwayat_dokter_' . $id_pertanyaan]) ? $post['riwayat_dokter_' . $id_pertanyaan] : NULL);
				$data_assessment["suhu_tertinggi"]	= ($suhu_tertinggi == '') ? 0 : $suhu_tertinggi;
				$data_assessment["gejala"]			= $gejala;
				$data_assessment["riwayat_dokter"]	= $riwayat_dokter;
			}
			//tambahan sampe sini

			$data_assessment = $this->dgeneral->basic_column("insert", $data_assessment);
			$this->dgeneral->insert("tbl_tamu_ass_data", $data_assessment);
		}
		
		if ($total_score >= 15 || $total_score_danger > 0) {
			$msg = "Terimakasih telah mengisi Assesment Tamu. Anda (" . $post['nama_tamu'] . ") masuk dalam <span class='badge bg-red'>Kategori Resiko Besar</span>.";
			$msg .= "<br><b>Anda tidak diperkenankan masuk area kantor Kirana Megatara.</b>";
		} else if ($total_score >= 10) {
			$msg = "Terimakasih telah mengisi Assesment Tamu. Anda (" . $post['nama_tamu'] . ") masuk dalam <span class='badge bg-yellow'>Kategori Resiko Sedang</span>.";
			$msg .= "<br><b>Anda diwajibkan Interview dengan Gugus Tugas Kirana Megatara terlebih dahulu, silahkan menghubungi Karyawan yang ingin Anda kunjungi.</b>";
		} else {
			$msg = "Terimakasih telah mengisi Assesment Tamu. Anda (" . $post['nama_tamu'] . ") masuk dalam <span class='badge bg-green'>Kategori Resiko Kecil</span>.";
			$msg .= "<br><b>Anda diperkenakan masuk area kantor Kirana Megatara dengan mengikuti Protokol Kesehatan Yang Berlaku.</b>";
		}

		$data_hasil_assessment = array(
			"score_assessment" => $total_score,
			"score_assessment_danger" => $total_score_danger,
			"message_assessment" => $msg,
			"is_assessment" => 1
		);
		$data_hasil_assessment = $this->dgeneral->basic_column("update", $data_hasil_assessment);
		$this->dgeneral->update("tbl_tamu", $data_hasil_assessment, array(
			array(
				'kolom' => 'id_tamu',
				'value' => $id_tamu
			)
		));

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			// $msg = "Data sudah disimpan.";
			$sts = "OK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function get_data_peserta($param)
	{
		$result = $this->devent->get_data_peserta($param);
		echo json_encode($result);
	}

	private function get_pertanyaan($array = NULL)
	{
		$pertanyaan 		= $this->devent->get_data_pertanyaan("open");
		$pertanyaan 		= $this->general->generate_encrypt_json($pertanyaan);
		if ($array) {
			return $pertanyaan;
		} else {
			echo json_encode($pertanyaan); 
		} 
	}

	private function check_access_input_peserta($param)
	{
		$data_peserta = $this->devent->get_data_peserta($param);
		$id_peserta = "";
		$data_assessment = $this->dtransaksitamu->get_data_assessment($param);
		return true;
	}
	/*====================================================================*/
}
