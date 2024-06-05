<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Simulasi Penjualan SPOT
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
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
		$this->load->library('less');
		$this->load->helper(array('form', 'url'));
		$this->load->model('dgeneral');
		$this->load->model('dtransaksiassessment');
		$this->admin = array("8347");
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
			case 'pertanyaan':
				$this->get_pertanyaan(NULL);
				break;
			case 'pertanyaan_ganda':
				$this->get_pertanyaan_ganda(NULL);
				break;
			case 'berita_acara':
				$this->get_berita_acara(NULL);
				break;
			case 'checking':
				$nik  = $this->input->post('nik', TRUE); //isset($_POST['nik']) ? $_POST['nik'] : NULL;
				$ck_data 	= $this->dtransaksiassessment->get_data_assessment("open", $nik);
				if (count($ck_data) != 0) {
					$msg    = "Daily Self Assessment tanggal " . date('d.m.Y') . " Untuk NIK " . $nik . " sudah terinput!";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				$data = $this->dtransaksiassessment->get_karyawan($nik);
				if (!empty($data)) {

					$data->is_admin = false;
					if (in_array($nik, $this->admin) === TRUE) {
						$data->is_admin = true;
					}
					$data->sap = 'y';
				} else {
					// if(!$data){
					$data2 = $this->dtransaksiassessment->get_karyawan_nonSap(NULL, $nik);
					$data2->is_admin = false;
					if (in_array($nik, $this->admin) === TRUE) {
						$data2->is_admin = true;
					}
					$data2->sap = 'n';
					$data2->id_user = $data2->nik;
					$data2->nama_divisi = $data2->divisi;
					$data = $data2;
					// }
				}
				echo json_encode($data);
				break;
			case 'karyawan_non_sap':
				$access = $this->check_access();
				$karyawan = $this->dtransaksiassessment->get_karyawan_nonSap("open");
				if ($access['sts'] == 'OK') {
					$data['karyawan'] = $karyawan;
					$data['msg'] = 'Otentikasi berhasil';
					$data['sts'] = 'OK';
				} else {
					$data['msg'] = 'Otentikasi tidak berhasil';
					$data['sts'] = 'NotOK';
				}
				echo json_encode($data);
				break;
		}
	}

	public function save($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'assessment':
				$this->save_assessment($param);
				break;
			case 'upload':
				if ($param2 == "karyawan_non_sap")
					$data = $this->save_upload_karyawan_non_sap($param);
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
	private function get_pertanyaan($array = NULL)
	{
		$pertanyaan 		= $this->dtransaksiassessment->get_data_pertanyaan("open");
		$pertanyaan 		= $this->general->generate_encrypt_json($pertanyaan);
		if ($array) {
			return $pertanyaan;
		} else {
			echo json_encode($pertanyaan);
		}
	}

	private function get_pertanyaan_ganda($array = NULL)
	{
		$pertanyaan_ganda 		= $this->dtransaksiassessment->get_data_pertanyaan_ganda("open");
		$pertanyaan_ganda 		= $this->general->generate_encrypt_json($pertanyaan_ganda);
		if ($array) {
			return $pertanyaan_ganda;
		} else {
			echo json_encode($pertanyaan_ganda);
		}
	}

	private function get_berita_acara($array = NULL)
	{
		$fp      = fopen('php://input', 'r');
		$rawData = stream_get_contents($fp);
		if ($rawData) {
			$postData = json_decode($rawData, true);
			$_POST    = $postData;
		}
		$post  = $this->input->post(NULL, TRUE);

		$berita_acara 		= $this->dtransaksiassessment->get_data_berita_acara("open", @$post['nik']);
		$berita_acara 		= $this->general->generate_encrypt_json($berita_acara);
		if ($array) {
			return $berita_acara;
		} else {
			echo json_encode($berita_acara);
		}
	}

	private function save_assessment($param)
	{
		$fp      = fopen('php://input', 'r');
		$rawData = stream_get_contents($fp);
		if ($rawData) {
			$postData = json_decode($rawData, true);
			$_POST    = $postData;
		}
		$post  = $this->input->post(NULL, TRUE);

		//checking
		$nik  = $post['nik'];
		$ck_data 	= $this->dtransaksiassessment->get_data_assessment("open", $nik);
		if (count($ck_data) != 0) {
			$msg = "Daily Self Assessment tanggal " . date('d.m.Y') . " Untuk NIK " . $nik . " sudah terinput!";
			$sts = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}

		$datetime	= date("Y-m-d H:i:s");
		$datenow	= date("Y-m-d");

		$karyawan_sap = base64_decode($this->session->userdata("-sap-"));
		$karyawan_nik = $post['nik'];

		$karyawan = $karyawan_nik;
		$tanggal_bak = $datenow;
  
									

													
					  
								   
													  
					   
	 

		$this->general->connectDbPortal();
						   
						   
																																								  
							   
							  

		if ($post['lokasi_kerja'] == 'wfh' || $post['lokasi_kerja'] == 'si_wfh') {
			$bak = $this->dtransaksiassessment->get_bak(array(
				'nik' => $karyawan,
				'tanggal_absen' => $tanggal_bak,
				'order_by' => 'absen_masuk asc, absen_keluar desc',
				'single_row' => true
			));

			if (empty($bak)) {
				$param = new stdClass();
				$param->method = 'POST';
				$param->url = 'http://10.0.0.105/ess/scheduler/bak_time_event?nik=' . $karyawan_nik . '&lokasi=pabrik&tanggal_awal=' . $datenow . '&tanggal_akhir=' . $datenow;
				$data = $this->curl($param);
				$data = json_decode($data);

				$bak = $this->dtransaksiassessment->get_bak(array(
					'nik' => $karyawan,
					'tanggal_absen' => $tanggal_bak,
					'order_by' => 'absen_masuk asc, absen_keluar desc',
					'single_row' => true
				));
			}
		}

		$this->dgeneral->begin_transaction();
		//save assessment
		$total_score = 0;
		$arr_pertanyaan    = explode(",", $post['all_pertanyaan']);
		foreach ($arr_pertanyaan as $id_pertanyaan) {
			$total_score += $post['score_' . $id_pertanyaan];
			$data["lokasi_detail"]	= $post['lokasi'];
			$data["tanggal"]		= date("Y-m-d");
			$data["nik"]			= $post['nik'];
			$data["lokasi_kerja"]	= $post['lokasi_kerja'];
			$data["suhu_tubuh"]		= $post['suhu_tubuh'];
			$data["id_pertanyaan"]	= $post['id_pertanyaan_' . $id_pertanyaan];
			$data["score"]		 	= $post['score_' . $id_pertanyaan];
			// $data["catatan"]		= $post['catatan_' . $id_pertanyaan];
			$data["login_buat"]	 	= $post['-id_user-'];
			$data["tanggal_buat"]	= $datetime;
			$data["login_edit"]	 	= $post['-id_user-'];
			$data["tanggal_edit"]	= $datetime;
			$data["na"]           	= 'n';
			$data["del"]          	= 'n';

			//tambahan
			if ($id_pertanyaan == 5) {
				$hubungan_keluarga   = (isset($post['hubungan_keluarga_' . $id_pertanyaan]) ? $post['hubungan_keluarga_' . $id_pertanyaan] : NULL);
				$hubungan_kategori   = (isset($post['hubungan_kategori_' . $id_pertanyaan]) ? $post['hubungan_kategori_' . $id_pertanyaan] : NULL);
				$data["hubungan_keluarga"]	= $hubungan_keluarga;
				$data["hubungan_kategori"]	= $hubungan_kategori;
			}
			if ($id_pertanyaan == 6) {
				$suhu_tertinggi   = (isset($post['suhu_tertinggi_' . $id_pertanyaan]) ? $post['suhu_tertinggi_' . $id_pertanyaan] : NULL);
				$gejala   		  = (isset($post['gejala_' . $id_pertanyaan]) ? $post['gejala_' . $id_pertanyaan] : NULL);
				$riwayat_dokter   = (isset($post['riwayat_dokter_' . $id_pertanyaan]) ? $post['riwayat_dokter_' . $id_pertanyaan] : NULL);
				$data["suhu_tertinggi"]	= ($suhu_tertinggi == '') ? 0 : $suhu_tertinggi;
				$data["gejala"]			= $gejala;
				$data["riwayat_dokter"]	= $riwayat_dokter;
			}
			//tambahan sampe sini
			$data = $this->dgeneral->basic_column("insert", $data);
			$this->dgeneral->insert("tbl_ass_data", $data);
		}

		$arr_pertanyaan_ganda    = explode(",", $post['all_pertanyaan_ganda']);
		foreach ($arr_pertanyaan_ganda as $id_pertanyaan_ganda) {
			$data_ganda["lokasi_detail"] 	= $post['lokasi'];
			$data_ganda["tanggal"]			= date("Y-m-d");
			$data_ganda["nik"]				= $post['nik'];
			$data_ganda["lokasi_kerja"]		= $post['lokasi_kerja'];
			$data_ganda["suhu_tubuh"]		= $post['suhu_tubuh'];
			$data_ganda["id_pertanyaan"] 	= $post['id_pertanyaan_ganda_' . $id_pertanyaan_ganda];
			if ($post['jawaban_' . $id_pertanyaan_ganda] == 'Lain-Lain') {
				$data_ganda["jawaban"]		= $post['catatan_ganda_' . $id_pertanyaan_ganda];
				$data_ganda["catatan"]		= '-';
			} else {
				$data_ganda["jawaban"]		= $post['jawaban_' . $id_pertanyaan_ganda];
				$data_ganda["catatan"]		= $post['catatan_ganda_' . $id_pertanyaan_ganda];
			}
			$data_ganda["login_buat"]	 	= $post['-id_user-'];
			$data_ganda["tanggal_buat"]	= $datetime;
			$data_ganda["login_edit"]		= base64_decode($this->session->userdata("-id_user-"));
			$data_ganda["tanggal_edit"]		= $datetime;
			//tambahan
			if (($id_pertanyaan_ganda == 8) or ($id_pertanyaan_ganda == 9) or ($id_pertanyaan_ganda == 10)) {
				$hubungan_keluarga_ganda	= (isset($post['hubungan_keluarga_ganda_' . $id_pertanyaan_ganda]) ? $post['hubungan_keluarga_ganda_' . $id_pertanyaan_ganda] : NULL);
				$jarak_ganda   		  		= (isset($post['jarak_ganda_' . $id_pertanyaan_ganda]) ? $post['jarak_ganda_' . $id_pertanyaan_ganda] : NULL);
				$interaksi_ganda   		  	= (isset($post['interaksi_ganda_' . $id_pertanyaan_ganda]) ? $post['interaksi_ganda_' . $id_pertanyaan_ganda] : NULL);
				$data_ganda["hubungan_keluarga_ganda"] 	= $hubungan_keluarga_ganda;
				$data_ganda["jarak_ganda"] 				= $jarak_ganda;
				$data_ganda["interaksi_ganda"]			= $interaksi_ganda;
			}

			$data_ganda = $this->dgeneral->basic_column("insert", $data_ganda);
			$this->dgeneral->insert("tbl_ass_data_ganda", $data_ganda);
		}

		//input berita acara 
		for ($i = 1; $i <= $post['jumlah_berita_acara']; $i++) {
			$tanggal_ba	= (isset($post['tanggal_ba_' . $i]) ? $post['tanggal_ba_' . $i] : NULL);
			$gejala_ba	= (isset($post['gejala_ba_' . $i]) ? $post['gejala_ba_' . $i] : NULL);
			$riwayat_ba	= (isset($post['riwayat_ba_' . $i]) ? $post['riwayat_ba_' . $i] : NULL);
			$tindakan_ba = (isset($post['tindakan_ba_' . $i]) ? $post['tindakan_ba_' . $i] : NULL);
			if ($tanggal_ba) {
				$data_ba["lokasi_detail"] 	= $post['lokasi'];
				$data_ba["nik"]				= $post['nik'];
				$data_ba["tanggal"]			= date("Y-m-d");
				$data_ba["lokasi_kerja"]	= $post['lokasi_kerja'];
				$data_ba["suhu_tubuh"]		= $post['suhu_tubuh'];
				$data_ba["tanggal_ba"]		= $tanggal_ba;
				$data_ba["gejala_ba"]		= $gejala_ba;
				$data_ba["riwayat_ba"]		= $riwayat_ba;
				$data_ba["tindakan_ba"]		= $tindakan_ba;
				$data_ba["login_edit"]		= base64_decode($this->session->userdata("-id_user-"));
				$data_ba["tanggal_edit"]		= $datetime;

				$data_ba = $this->dgeneral->basic_column("insert", $data_ba);
				$this->dgeneral->insert("tbl_ass_berita_acara", $data_ba);
			}
		}

		//==========================================================================ess bak
		if ($post['lokasi_kerja'] == 'wfh' || $post['lokasi_kerja'] == 'si_wfh') {
			// if($karyawan_sap == 'y'){
			$send_email_result = true;
			$send_email = true;
			try {
				if (!empty($bak)) {
					$id_bak = $bak->id_bak;
					$bak = $this->dtransaksiassessment->get_bak(
						array(
							'id' => $id_bak,
							'single_row' => true
						)
					);

					if ($bak && $bak->tipe !== "L") {
						$karyawan = $this->dtransaksiassessment->get_karyawan_bak($bak->id_karyawan);

						$atasan = $this->less->get_atasan(
							array(
								'nik' => $bak->nik
							)
						);

						$list_atasan_email 	= $atasan['list_atasan_email'];
						$email_atasan 	= $atasan['nik_atasan_email'];
						$atasan 		= $atasan['nik_atasan'];

						$detail_bak 	= "NO_CICO";
						if ($post['lokasi_kerja'] == 'wfh') {
							$keterangan 	= "WFH";
						} else {
							$keterangan 	= "SI(Self Isolation) - WFH";
						}
						$data_row_bak	= array(
							'absen_masuk'		=> $bak->jadwal_absen_masuk,
							'absen_keluar'		=> $bak->jadwal_absen_keluar,
							'atasan'			=> $atasan,
							'atasan_email'		=> $email_atasan,
							'id_bak_status'		=> 2,
							'id_bak_alasan'		=> 9,
							'keterangan'		=> $keterangan,
							'detail'			=> $detail_bak,
							'tanggal_masuk'		=> $bak->jadwal_absen,
							'tanggal_keluar'	=> $bak->jadwal_absen,

							'login_buat' 		=> base64_decode(
								$this->session->userdata("-id_user-")
							),
							'tanggal_buat' 		=> $datetime,
						);

						$this->dgeneral->update('tbl_bak', $data_row_bak, array(
							array(
								'kolom' => 'id_bak',
								'value' => $id_bak
							)
						));

						$email_tujuan = ESS_EMAIL_DEBUG_MODE ? json_decode(ESS_EMAIL_TESTER) : $list_atasan_email;

						$bak->keterangan = $keterangan;
						$bak->id_bak_status = 2;
						$bak->absen_masuk = $bak->jadwal_absen_masuk;
						$bak->absen_keluar = $bak->jadwal_absen_keluar;
						$bak->nama_status = "Menunggu";


						// if ($post['nik'] == '8347') {
						// 	$result = array("sts" => "OK");
						// } else {
						$result = $this->less->send_email(
							array(
								'judul' => "Konfirmasi Pengajuan Berita Acara Kehadiran " . ucwords(strtolower($karyawan->nama)),
								'email_pengirim' => "KiranaKu",
								'email_tujuan' => $email_tujuan,
								'view' => 'emails/pengajuan_bak_new',
								'data' => array(
									'data' => $bak
								)
							)
						);
						// }
						if ($result['sts'] == "NotOK")
							$send_email_result = false;
					}
				}
			} catch (Exception $exception) {
				$send_email_result = false;
			}
			// }
		}
		//========================================================================== 

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {

			// if ($post['nik'] == '8347') {
			// 	$bak = $this->dtransaksiassessment->get_bak(array(
			// 		'nik' => $post['nik'],
			// 		'tanggal_absen' => $datenow,
			// 		'order_by' => 'absen_masuk asc, absen_keluar desc',
			// 		'single_row' => true
			// 	));
			// 	$msg = "Berhasil";
			// 	$sts = "OK";
			// 	$this->dgeneral->rollback_transaction();
			// 	$return = array('sts' => $sts, 'msg' => $bak);
			// 	echo json_encode($return);
			// 	exit();
			// } else {
			$this->dgeneral->commit_transaction();
			// }
			if ($total_score >= 5) {
				$msg = "Pada tanggal <b>" . date('d M Y') . "</b>, Nilai Assessment Anda (" . $post['nama'] . " NIK " . $post['nik'] . ") adalah <span class='badge bg-red'>" . $total_score . "</span>. Resiko termasuk Kategori <span class='badge bg-red'>Besar</span>.";
				$msg .= "<br>Anda tidak diperkenankan masuk kerja dan perlu diinvestigasi lebih lanjut.";
			} else {
				$kategori = ($total_score == 0) ? "<span class='badge bg-green'>Kecil</span>" : "<span class='badge bg-yellow'>Sedang</span>";
				$score = ($total_score == 0) ? "<span class='badge bg-green'>" . $total_score . "</span>" : "<span class='badge bg-yellow'>" . $total_score . "</span>";
				$msg = "Pada tanggal <b>" . date('d M Y') . "</b>, Nilai Assessment Anda (" . $post['nama'] . " NIK " . $post['nik'] . ") adalah " . $score . ". Resiko termasuk Kategori $kategori";
				$msg .= "<br>Anda diperbolehkan masuk kerja namun dilakukan pemeriksaan suhu di pintu masuk tempat kerja.";
				$msg .= "<br>Apabila didapatkan suhu >=37.3 derajat Celcius agar dilakukan investigasi dan pemeriksaan ke Faskes terdekat dan akan di review oleh Komite Covid-19 Head Office.";
			}
			$sts = "OK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function check_access($param = NULL)
	{
		$post 		= $this->input->post(NULL, TRUE);
		$user 		= $post['nik'];
		$pass 		= md5($post['pass']);

		if (in_array($user, json_decode(KIRANA_ASSESSMENT_ADMIN)) === TRUE) {
			if ($pass == KIRANA_ASSESSMENT_PASS_UPLOAD) {
				$msg 	= "Otentikasi berhasil";
				$sts 	= "OK";
			} else {
				$msg    = "Password tidak sesuai";
				$sts    = "NotOK";
			}
		} else {
			$msg    = "Anda tidak memiliki akses.";
			$sts    = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		return $return;
	}

	private function save_upload_karyawan_non_sap($param = NULL)
	{

		$post 			= $this->input->post(NULL, TRUE);
		$datetime 		= date("Y-m-d H:i:s");
		$nik 			= $post['nik'];
		$pass 			= md5($post['pass']);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$msg 	= "Periksa kembali data yang diunggah";
		$sts 	= "NotOK";

		if ($post['data_excel'] == "") {
			$msg 	= "Periksa kembali data yang diunggah";
			$sts 	= "NotOK";
		} else {

			$datetime       = date("Y-m-d H:i:s");

			$user 			= $nik;
			$data_row		= array();
			$dataexcel 		= rtrim($post['data_excel'], "| ");
			$expl_data 		= explode("|", $dataexcel);
			// 801112,waluyo@gmail.com,dodi waluyo,Purchasing,l,y,Kiranamegatara|801114,naning@gmail.com,naning,HRGA,p,n,Kirana Prima|
			if (count($expl_data) >= 1) {
				for ($brs = 0; $brs < count($expl_data); $brs++) {
					$dt = explode("~", $expl_data[$brs]);
					$nik		= $dt[0];
					$email		= $dt[1];
					$nama		= $dt[2];
					$divisi		= $dt[3];
					$gender		= $dt[4];
					$ho			= $dt[5];
					$plant		= $dt[6];
					$dptmt		= $dt[7];
					if ($nik == "") {
						break;
					}
					$data_row	= array(
						'nik'				=> $nik,
						'email'				=> $email,
						'nama'				=> $nama,
						'divisi'			=> $divisi,
						'gender'			=> $gender,
						'ho'				=> $ho,
						'plant'				=> $plant,
						'department'		=> $dptmt,
						'login_buat' 		=> $user,
						'tanggal_buat'		=> $datetime,
						'login_edit' 		=> $user,
						'tanggal_edit' 		=> $datetime,
					);
					$ck_data 	= $this->dtransaksiassessment->get_karyawan_nonSap(NULL, $nik);
					if ($ck_data) {
						unset($data_row['login_buat']);
						unset($data_row['tanggal_buat']);
						$this->dgeneral->update('tbl_ass_user', $data_row, array(
							array(
								'kolom' => 'nik',
								'value' => $nik
							)
						));
					} else {
						$this->dgeneral->insert("tbl_ass_user", $data_row);
					}
				}

				if ($this->db->trans_status() === false) {
					$this->dgeneral->rollback_transaction();
					$msg 	= "Periksa kembali data yang diunggah";
					$sts 	= "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg 	= "Data berhasil diunggah";
					$sts 	= "OK";
				}
			}
		}
		$return = array('sts' => $sts, 'msg' => $msg);


		echo json_encode($return);
		exit();
	}

	public function curl($param = NULL)
	{
		if ($param) {
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, @$param->method);
			curl_setopt($curl_handle, CURLOPT_URL, @$param->url);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_handle, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl_handle, CURLOPT_FAILONERROR, true);
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 20);
			curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt($curl_handle, CURLOPT_TIMEOUT, 20000);
			curl_setopt($curl_handle, CURLOPT_AUTOREFERER, true);
			curl_setopt($curl_handle, CURLOPT_COOKIEFILE, "");
			curl_setopt($curl_handle, CURLOPT_VERBOSE, true);

			//if param data exists
			if (@$param->data)
				curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $param->data);

			//execute curl
			$res = curl_exec($curl_handle);
			//check curl result error
			$httpcode = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
			if (curl_error($curl_handle)) {
				$res = curl_error($curl_handle);
			}
			curl_close($curl_handle);

			//return curl result
			return $res;
		}
	}
	/*====================================================================*/
}
