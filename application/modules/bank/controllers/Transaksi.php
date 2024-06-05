<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
include_once APPPATH . "modules/material/controllers/BaseControllers.php";

// Class Transaksi extends BaseControllers{
class Transaksi extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('PHPExcel');
		$this->load->helper(array('form', 'url'));

		$this->load->model('dmasterbank');
		$this->load->model('dsettingbank');
		$this->load->model('dtransaksibank');
	}

	public function index()
	{
		show_404();
	}

	public function create($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Form Bank Specimen";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksibank->get_data_user_role("open", $nik, $posst);
		$data['mata_uang']	= $this->get_mata_uang(('array'));
		$this->load->view("transaksi/create", $data);
	}
	public function detail($id_date_temp = NULL, $act = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title'] 	 	 	  = "Detail Bank Specimen";
		$nik				 	  = base64_decode($this->session->userdata("-nik-"));
		$posst 				 	  = base64_decode($this->session->userdata("-posst-"));
		$data['id_data_temp']	  = $id_date_temp;
		$data['act']		 	  = $act;
		$data['user_role']	 	  = $this->dtransaksibank->get_data_user_role("open", $nik, $posst);
		// $data_temp			 	  = $this->dtransaksibank->get_data_bank_temp("open", $this->generate->kirana_decrypt($id_date_temp));
		// // echo json_encode($this->generate->kirana_decrypt($id_date_temp));
		// // exit();
		// if($data_temp[0]->status==99){
		// $data['user_role_status'] = $this->dtransaksibank->get_data_user_role_status("open", 6);
		// }else{
		// $data['user_role_status'] = $this->dtransaksibank->get_data_user_role_status("open", $data_temp[0]->status);
		// }
		// // echo json_encode($data['user_role_status']);
		// // exit();
		$data['mata_uang']	 	  = $this->get_mata_uang(('array'));
		$this->load->view("transaksi/detail", $data);
	}
	public function cetak($id_date_temp = NULL, $act = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title'] 	 	 	  = "Final Bank Account Confirmation";
		$nik				 	  = base64_decode($this->session->userdata("-nik-"));
		$posst 				 	  = base64_decode($this->session->userdata("-posst-"));
		$data['id_data_temp']	  = $id_date_temp;
		$data['act']		 	  = $act;
		$data['user_role']	 	  = $this->dtransaksibank->get_data_user_role("open", $nik, $posst);
		$data['mata_uang']	 	  = $this->get_mata_uang(('array'));
		$this->load->view("transaksi/cetak", $data);
	}
	public function approve($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Approve Bank Specimen";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksibank->get_data_user_role("open", $nik, $posst);;
		$this->load->view("transaksi/approve", $data);
	}
	public function data($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data Bank Specimen";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksibank->get_data_user_role("open", $nik, $posst);;
		$this->load->view("transaksi/data", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'history':
				$id_data_temp	= (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']) : NULL);
				$this->get_history(NULL, $id_data_temp, 'n', NULL);
				break;

			case 'prioritas':
				$pabrik		= (isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
				$id_role	= (isset($_POST['id_role']) ? $_POST['id_role'] : NULL);
				$this->get_prioritas(NULL, $pabrik, $id_role);
				break;

			case 'rekening_auto':
				$post = $this->input->post_get(NULL, TRUE);
				$this->get_rekening_autocomplete($post['pabrik'], $post['id_data']);
															 
				break;

			case 'bank_auto':
				$this->get_bank_auto();
				break;

			case 'user_auto':
				$post = $this->input->post_get(NULL, TRUE);
				$this->get_user_autocomplete($post['jenis'], $post['pabrik']);
				break;

			case 'data':
				$id_data  		= (isset($_POST['id_data']) ? $_POST['id_data'] : NULL);
				$nomor_rekening = (isset($_POST['nomor_rekening']) ? $_POST['nomor_rekening'] : NULL);
				$status_sap 	= (isset($_POST['status_sap']) ? $_POST['status_sap'] : NULL);
				//filter pabrik
				if (isset($_POST['pabrik_filter'])) {
					$pabrik_filter	= array();
					foreach ($_POST['pabrik_filter'] as $dt) {
						array_push($pabrik_filter, $dt);
					}
				} else {
					$pabrik_filter  = NULL;
				}

				if ($param2 == 'bom') {
					header('Content-Type: application/json');
					$return = $this->dtransaksibank->get_data_bank_bom('open', $id_data, NULL, NULL, $pabrik_filter, $status_sap);
					echo $return;
					break;
				} else {
					$this->get_bank(NULL, $id_data, NULL, NULL, NULL, NULL, $nomor_rekening);
					break;
				}
			case 'data_temp':
				$view_data		= (isset($_POST['view_data']) ? $_POST['view_data'] : NULL);
				$id_data_temp  	= (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']) : NULL);
				//filter jenis pengajuan
				if (isset($_POST['jenis_pengajuan_filter'])) {
					$jenis_pengajuan_filter	= array();
					foreach ($_POST['jenis_pengajuan_filter'] as $dt) {
						array_push($jenis_pengajuan_filter, $dt);
					}
				} else {
					$jenis_pengajuan_filter  = NULL;
				}
				//filter pabrik
				if (isset($_POST['pabrik_filter'])) {
					$pabrik_filter	= array();
					foreach ($_POST['pabrik_filter'] as $dt) {
						array_push($pabrik_filter, $dt);
					}
				} else {
					$pabrik_filter  = NULL;
				}
				//filter status
				if (isset($_POST['status_filter'])) {
					$status_filter	= array();
					foreach ($_POST['status_filter'] as $dt) {
						array_push($status_filter, $dt);
					}
				} else {
					$status_filter  = NULL;
				}

				if ($param2 == 'bom') {
					header('Content-Type: application/json');
					$return = $this->dtransaksibank->get_data_bank_temp_bom('open', $id_data_temp, NULL, NULL, $jenis_pengajuan_filter, $pabrik_filter, $status_filter, $view_data);
					echo $return;
					break;
				} else {
					$this->get_bank_temp(NULL, $id_data_temp, NULL, NULL);
					break;
				}
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set($param = NULL)
	{
		$action = NULL;
		if (isset($_POST['type']) && $_POST['type'] == "nonactive") {
			$action = "delete_na";
		} else if (isset($_POST['type']) && $_POST['type'] == "setactive") {
			$action = "activate_na";
		}
		if ($action) {
			switch ($param) {
				case 'data':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_bank_data", array(
						array(
							'kolom' => 'id_data',
							'value' => $this->generate->kirana_decrypt($_POST['id_data'])
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
	public function save($param = NULL)
	{
		switch ($param) {
			case 'dokumen':
				$this->save_dokumen($param);
				break;
			case 'coa':
				$this->save_coa($param);
				break;
			case 'rekening':
				$this->save_rekening($param);
				break;
			case 'data':
				$this->save_data($param);
				break;
			case 'update':
				$this->save_update($param);
				break;
			case 'approve':
				$this->save_approve($param);
				break;
			case 'decline':
				$this->save_decline($param);
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function template_email_bank($to = NULL, $subject = NULL, $content = NULL, $nama_penerima = 'Bapak/Ibu', $cc = NULL)
	{
		setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mail.kiranamegatara.com';
		$config['smtp_user'] = 'no-reply@kiranamegatara.com';
		$config['smtp_pass'] = '1234567890';
		$config['smtp_port'] = '465';
		$config['smtp_crypto'] = 'ssl';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = true;
		$config['mailtype'] = 'html';

		$this->load->library('email', $config);
		$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
		$this->email->subject($subject);
		if (empty($to) && !empty($cc)){
			$to = $cc;
			$cc = array();
		}
		if (!empty($cc))
			$this->email->cc($cc);

		$this->email->to($to);
		$this->email->bcc("lukman.hakim@kiranamegatara.com");
		$this->email->bcc("ADY.PUDRIANSYAH@KIRANAMEGATARA.COM");
		$this->email->bcc("SYAIFUL.YAMANG@KIRANAMEGATARA.COM");

		$message = '<html>
		<body style=" background-color: #386d22">
		<center style="width: 100%;">
		<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
			Notifikasi Email Aplikasi Bank Specimen
		</div>
		<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
			&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
		</div>
		<div class="email-container" style="max-width: 800px; margin: 0 auto;">
			<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="min-width:600px;">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td style="color: #fff; padding:20px;" align="center">
						<h1 style="margin-bottom: 0;">Bank Specimen</h1>
						<hr style="border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;"/>
						<h3 style="margin-top: 0;">KIRANAKU</h3>
					</td>
				</tr>
				<tr>
					<td>
						<table style="background-color: #ffffff; margin: auto; -webkit-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); -moz-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4);" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
							<tbody>
							<tr>
								<td style="padding: 20px;">
									<p><strong>Kepada ' . ucwords(strtolower((is_array($nama_penerima) ? implode(", ", $nama_penerima) : $nama_penerima))) . ',</strong></p>
									<p>Berikut adalah pemberitahuan dari Bank Specimen</p>
									<table role="presentation" border="0" width="100%" cellpadding="0" cellspacing="0">
										<tbody>
											<tr><td><strong>Konfirmasi ' . $subject . ', Mohon untuk ditindaklanjuti.</strong></td></tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left" style="background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;">
									' . $content . '
								</td>
							</tr>
							<tr>
								<td align="left"
									style="background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;">
									<p>
										Harap segera ditindak lanjuti,<br/>
										Terima kasih atas perhatiannya.
									</p>
								</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td style="color: #fff; padding-top:20px;" align="center">
						<small>Kiranaku Auto-MailSystem</small><br/>
						<strong style="color: #214014; font-size: 10px;">Terkirim pada ' . date('d.m.Y H:i:s') . '</strong>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</div>
		</center>
		</body>
		</html>
		';
		// echo $message;		
		$this->email->message($message);
		$this->email->send();
	}

	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_bank_auto()
	{
		if (isset($_GET['q'])) {
			$data	= $this->dtransaksibank->get_data_bank_auto($_GET['q']);
			$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
			$data_json = array(
				"total_count"        => count($data),
				"incomplete_results" => false,
				"items"              => $data
			);
			echo json_encode($data_json);
		}
	}
	private function get_rekening_autocomplete($pabrik = NULL, $id_data = NULL)
	{
		if (isset($_GET['q'])) {
			$data	= $this->dtransaksibank->get_data_rekening_autocomplete($_GET['q'], $pabrik, $id_data);
			$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
			$data_json = array(
				"total_count"        => count($data),
				"incomplete_results" => false,
				"items"              => $data
			);
			echo json_encode($data_json);
		}
	}

	private function get_user_autocomplete($jenis = NULL, $pabrik = NULL)
	{
		if (isset($_GET['q'])) {
			$data	= $this->dtransaksibank->get_data_user_autocomplete($_GET['q'], $jenis, $pabrik);
			$data_json = array(
				"total_count"        => count($data),
				"incomplete_results" => false,
				"items"              => $data
			);
			echo json_encode($data_json);
		}
	}

	private function get_prioritas($array = NULL, $pabrik = NULL, $id_role = NULL)
	{
		$prioritas 		= $this->dtransaksibank->get_data_prioritas("open", $pabrik, $id_role);
		if ($array) {
			return $prioritas;
		} else {
			echo json_encode($prioritas);
		}
	}

	private function get_mata_uang($array = NULL)
	{
		$mata_uang 		= $this->dtransaksibank->get_data_mata_uang("open");
		if ($array) {
			return $mata_uang;
		} else {
			echo json_encode($mata_uang);
		}
	}

	private function get_bank_temp($array = NULL, $id_data_temp = NULL, $active = NULL, $deleted = NULL)
	{
		$data_temp	= $this->dtransaksibank->get_data_bank_temp("open", $id_data_temp, $active, $deleted);
		$data_temp 	= $this->general->generate_encrypt_json($data_temp, array("id_data_temp"));
		$data_bank	= $this->get_bank("array", $data_temp[0]->id_data);
		$data_bank_tujuan	= $this->get_bank("array", $data_temp[0]->id_data_tujuan);

		$data_temp[0]->arr_data_bank = $data_bank;
		$data_temp[0]->arr_data_bank_tujuan = $data_bank_tujuan;

		if ($array) {
			return $data_temp;
		} else {
			echo json_encode($data_temp);
		}
	}

	private function get_bank($array = NULL, $id_data = NULL, $active = NULL, $deleted = NULL, $nama_bank = NULL, $cabang_bank = NULL, $nomor_rekening = NULL)
	{
		$data	= $this->dtransaksibank->get_data_bank("open", $id_data, $active, $deleted, $nama_bank, $cabang_bank, $nomor_rekening);
		$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}

	private function get_history($array = NULL, $id_data_temp = NULL, $active = NULL, $deleted = NULL)
	{
		$history 	= $this->dtransaksibank->get_data_history("open", $id_data_temp, $active, $deleted);
		$history 	= $this->general->generate_encrypt_json($history, array("id_data_temp"));
		if ($array) {
			return $history;
		} else {
			echo json_encode($history);
		}
	}

	private function save_data($param)
	{
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		//get data
		$nik			= base64_decode($this->session->userdata("-nik-"));
		$posst 			= base64_decode($this->session->userdata("-posst-"));
		$pabrik 		= (isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
		$user_role		= $this->dtransaksibank->get_data_user_role(NULL, $nik, $posst);
		$get_nomor		= $this->dtransaksibank->get_data_nomor(NULL, $pabrik);
		$data_role		= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, $user_role[0]->level);

		$id_data_temp			= (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']) : NULL);
		$asal_pengajuan			= ($user_role[0]->level == 3) ? 'ho' : 'pabrik';
		$jenis_pengajuan		= (isset($_POST['jenis_pengajuan']) ? $_POST['jenis_pengajuan'] : NULL);
		// $pabrik 				= $user_role[0]->pabrik;
		// $pabrik 				= (isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
		$status		 			= $user_role[0]->level;
		$nomor 					= $get_nomor[0]->nomor . '/' . $pabrik . '/' . date('m') . '/' . date('Y');
		$tanggal 				= (isset($_POST['tanggal']) ? $_POST['tanggal'] : NULL);
		$latar_belakang 		= (isset($_POST['latar_belakang']) ? $_POST['latar_belakang'] : NULL);
		$nama_bank 				= (isset($_POST['nama_bank']) ? $_POST['nama_bank'] : NULL);
		$cabang_bank			= (isset($_POST['cabang_bank']) ? $_POST['cabang_bank'] : NULL);
		$nomor_rekening 		= (isset($_POST['nomor_rekening']) ? $_POST['nomor_rekening'] : NULL);
		$mata_uang 				= (isset($_POST['mata_uang']) ? $_POST['mata_uang'] : NULL);
		//pembukaan
		$tujuan 				= (isset($_POST['tujuan']) ? $_POST['tujuan'] : NULL);
		$tujuan_detail 			= (isset($_POST['tujuan_detail']) ? $_POST['tujuan_detail'] : NULL);
		$no_coa 				= (isset($_POST['no_coa']) ? $_POST['no_coa'] : NULL);
		$prioritas1	 			= (isset($_POST['prioritas1']) ? $_POST['prioritas1'] : NULL);
		$prioritas2	 			= (isset($_POST['prioritas2']) ? $_POST['prioritas2'] : NULL);
		$pendamping				= (isset($_POST['pendamping']) ? implode(",", $_POST['pendamping']) : NULL);
		//penutupan
		$id_data 				= (isset($_POST['id_data']) ? $_POST['id_data'] : NULL);
		$data_bank 				= $this->dtransaksibank->get_data_bank(NULL, $id_data, NULL, NULL);
		$sisa_dana 				= (isset($_POST['sisa_dana']) ? str_replace(',', '', $_POST['sisa_dana']) : NULL);
		$nama_bank_tujuan 		= (isset($_POST['nama_bank_tujuan']) ? $_POST['nama_bank_tujuan'] : NULL);
		$cabang_bank_tujuan 	= (isset($_POST['cabang_bank_tujuan']) ? $_POST['cabang_bank_tujuan'] : NULL);
		$nomor_rekening_tujuan 	= (isset($_POST['nomor_rekening_tujuan']) ? $_POST['nomor_rekening_tujuan'] : NULL);
		$no_coa_tujuan			= (isset($_POST['no_coa_tujuan']) ? $_POST['no_coa_tujuan'] : NULL);
		//perubahan
		$id_data_tujuan			= (isset($_POST['id_data_tujuan']) ? $_POST['id_data_tujuan'] : NULL);
		$tujuan_new 			= (isset($_POST['tujuan_new']) ? $_POST['tujuan_new'] : NULL);
		$tujuan_detail_new 		= (isset($_POST['tujuan_detail_new']) ? $_POST['tujuan_detail_new'] : NULL);
		$prioritas1_new	 		= (isset($_POST['prioritas1_new']) ? $_POST['prioritas1_new'] : NULL);
		$prioritas2_new	 		= (isset($_POST['prioritas2_new']) ? $_POST['prioritas2_new'] : NULL);
		$pendamping_new			= (isset($_POST['pendamping_new']) ? implode(",", $_POST['pendamping_new']) : NULL);
		$subject = "";
		$content = "";

		//save data pembukaan
		if ($jenis_pengajuan == 'pembukaan') {
			$ck_data 	= $this->dtransaksibank->get_data_bank(NULL, NULL, NULL, NULL, $nama_bank, $cabang_bank, NULL, NULL, NULL, $pabrik, $tujuan, $tujuan_detail);
			if (count($ck_data) != 0) {
				$msg    = "Bank " . $nama_bank . " Cabang " . $cabang_bank . " sudah terdaftar, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$ck_data_temp 	= $this->dtransaksibank->get_data_bank_temp(NULL, NULL, NULL, NULL, $nama_bank, $cabang_bank, $pabrik, $tujuan, $tujuan_detail);
			if (count($ck_data_temp) != 0) {
				$msg    = "Bank " . $nama_bank . " Cabang " . $cabang_bank . " sudah terdaftar, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"asal_pengajuan" 		=> $asal_pengajuan,
				"jenis_pengajuan" 		=> $jenis_pengajuan,
				"pabrik" 				=> $pabrik,
				"status" 				=> $status,
				"nomor" 				=> $nomor,
				"tanggal" 				=> date("Y-m-d", strtotime($tanggal)),
				"latar_belakang" 		=> $latar_belakang,
				"nama_bank" 			=> $nama_bank,
				"cabang_bank" 			=> $cabang_bank,
				"nomor_rekening" 		=> $nomor_rekening,
				"mata_uang" 			=> $mata_uang,
				"tujuan" 				=> $tujuan,
				"tujuan_detail" 		=> $tujuan_detail,
				"no_coa" 				=> $no_coa,
				"prioritas1" 			=> $prioritas1,
				"prioritas2" 			=> $prioritas2,
				"pendamping" 			=> $pendamping,
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_bank_data_temp", $data_row);
			
			$last_id = $this->db->insert_id();
			//save data temp log 
			$data_row_log = array(
				"id_data_temp"	=> $last_id,
				"status"		=> $status,
				"catatan"		=> NULL
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_bank_data_temp_log", $data_row_log);

			//send email
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<td align="left" width="20%">Tanggal</td>
							<td align="left">: ' . date("d.m.Y", strtotime($tanggal)) . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Nomor</td>
							<td align="left">: ' . $nomor . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Latar Belakang</td>
							<td align="left">: ' . $latar_belakang . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Nama Bank</td>
							<td align="left">: ' . $nama_bank . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Cabang Bank</td>
							<td align="left">: ' . $cabang_bank . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Mata Uang</td>
							<td align="left">: ' . $mata_uang . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Tujuan Penggunaan</td>
							<td align="left">: ' . $tujuan . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Penggunaan Detail</td>
							<td align="left">: ' . $tujuan_detail . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Status</td>
							<td align="left">: Menunggu Approval ' . $data_role[0]->nama . '</td>
						</tr>
					</thead>
				</table>';
			$subject 	= "Pembukaan Rekening Bank (" . $nomor . ")";
			/*
			$email_user	= $this->dtransaksibank->get_data_email_user(NULL, $data_role[0]->tipe_user, $data_role[0]->id_role, $pabrik);
			foreach ($email_user as $dt) {
				$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
				// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
			}
			*/
		}
		//save data penutupan
		if ($jenis_pengajuan == 'penutupan') {
			$data_row = array(
				"id_data" 				=> $id_data,
				"id_data_tujuan"		=> $id_data_tujuan,
				"asal_pengajuan" 		=> $asal_pengajuan,
				"jenis_pengajuan" 		=> $jenis_pengajuan,
				"pabrik" 				=> $pabrik,
				"status" 				=> $status,
				"nomor" 				=> $nomor,
				"tanggal" 				=> date("Y-m-d", strtotime($tanggal)),
				"latar_belakang" 		=> $latar_belakang,
				"nama_bank" 			=> $data_bank[0]->nama_bank,
				"cabang_bank" 			=> $data_bank[0]->cabang_bank,
				"nomor_rekening" 		=> $data_bank[0]->nomor_rekening,
				"mata_uang" 			=> $data_bank[0]->mata_uang,
				"no_coa" 				=> $data_bank[0]->no_coa,
				"sisa_dana" 			=> $sisa_dana,
				"nama_bank_tujuan" 		=> $nama_bank_tujuan,
				"cabang_bank_tujuan" 	=> $cabang_bank_tujuan,
				"nomor_rekening_tujuan" => $nomor_rekening_tujuan,
				"no_coa_tujuan" 		=> $no_coa_tujuan,
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_bank_data_temp", $data_row);

			//save data temp log 
			$data_row_log = array(
				"id_data_temp"	=> $id_data,
				"status"		=> $status,
				"catatan"		=> NULL
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_bank_data_temp_log", $data_row_log);

			//send email
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<td align="left" width="20%">Tanggal</td>
							<td align="left">: ' . date("d.m.Y", strtotime($tanggal)) . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Nomor</td>
							<td align="left">: ' . $nomor . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Latar Belakang</td>
							<td align="left">: ' . $latar_belakang . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Nama Bank</td>
							<td align="left">: ' . $nama_bank . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Cabang Bank</td>
							<td align="left">: ' . $cabang_bank . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Mata Uang</td>
							<td align="left">: ' . $mata_uang . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Sisa Dana</td>
							<td align="left">: ' . $sisa_dana . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Nomor Rekening Tujuan</td>
							<td align="left">: ' . $nomor_rekening_tujuan . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">No COA Tujuan</td>
							<td align="left">: ' . $no_coa_tujuan . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Status</td>
							<td align="left">: Menunggu Approval ' . $data_role[0]->nama . '</td>
						</tr>
					</thead>
				</table>';
			$subject 	= "Penutupan Rekening Bank (" . $nomor . ")";
			/*
			$email_user	= $this->dtransaksibank->get_data_email_user(NULL, $data_role[0]->tipe_user, $data_role[0]->id_role, $pabrik);
			foreach ($email_user as $dt) {
				$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
				// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
			}
			*/
		}
		//save data perubahan
		if ($jenis_pengajuan == 'perubahan') {
			$data_row = array(
				"id_data" 				=> $id_data,
				"asal_pengajuan" 		=> $asal_pengajuan,
				"jenis_pengajuan" 		=> $jenis_pengajuan,
				"pabrik" 				=> $pabrik,
				"status" 				=> $status,
				"nomor" 				=> $nomor,
				"tanggal" 				=> date("Y-m-d", strtotime($tanggal)),
				"latar_belakang" 		=> $latar_belakang,
				"nama_bank" 			=> $data_bank[0]->nama_bank,
				"cabang_bank" 			=> $data_bank[0]->cabang_bank,
				"nomor_rekening" 		=> $data_bank[0]->nomor_rekening,
				"mata_uang" 			=> $data_bank[0]->mata_uang,
				"no_coa" 				=> $data_bank[0]->no_coa,
				"tujuan" 				=> $tujuan_new,
				"tujuan_detail" 		=> $tujuan_detail_new,
				"prioritas1" 			=> $prioritas1_new,
				"prioritas2" 			=> $prioritas2_new,
				"pendamping" 			=> $pendamping_new,
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_bank_data_temp", $data_row);

			//save data temp log 
			$data_row_log = array(
				"id_data_temp"	=> $id_data,
				"status"		=> $status,
				"catatan"		=> NULL
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_bank_data_temp_log", $data_row_log);

			//send email
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<td align="left" width="20%">Tanggal</td>
							<td align="left">: ' . date("d.m.Y", strtotime($tanggal)) . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Nomor</td>
							<td align="left">: ' . $nomor . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Latar Belakang</td>
							<td align="left">: ' . $latar_belakang . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Nama Bank</td>
							<td align="left">: ' . $data_bank[0]->nama_bank . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Cabang Bank</td>
							<td align="left">: ' . $data_bank[0]->cabang_bank . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Mata Uang</td>
							<td align="left">: ' . $data_bank[0]->mata_uang . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Tujuan Penggunaan</td>
							<td align="left">:' . $data_bank[0]->tujuan . ' &#8594; ' . $tujuan_new . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Penggunaan Detail</td>
							<td align="left">: ' . $data_bank[0]->tujuan_detail . ' &#8594; ' . $tujuan_detail_new . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Pihak Prioritas 1</td>
							<td align="left">: ' . $data_bank[0]->prioritas1 . ' &#8594; ' . $prioritas1_new . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Pihak Prioritas 2</td>
							<td align="left">: ' . $data_bank[0]->prioritas2 . ' &#8594; ' . $prioritas2_new . '</td>
						</tr>
						<tr>
							<td align="left" width="20%">Status</td>
							<td align="left">: Menunggu Approval ' . $data_role[0]->nama . '</td>
						</tr>
					</thead>
				</table>';
			$subject 	= "Perubahan Rekening Bank (" . $nomor . ")";
			/*
			$email_user	= $this->dtransaksibank->get_data_email_user(NULL, $data_role[0]->tipe_user, $data_role[0]->id_role, $pabrik);
			foreach ($email_user as $dt) {
				$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
				// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
			}
			*/
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			if (!empty($subject) && !empty($content)) {
				$email_user	= $this->dtransaksibank->get_data_email_user(
					array(
						"connect" => TRUE,
						"no" => $nomor
					)
				);
				if ($email_user) {
					$email_to = array();
					$nama_to = array();
					$email_cc = array();
					foreach ($email_user as $dt) {
						if ($dt->user_status == 'to') {
							$email_to[] = $dt->email;
							$nama_to[] = $dt->gender . ' ' . $dt->nama;
						}
						if ($dt->user_status == 'cc') {
							$email_cc[] = $dt->email;
						}
					}
					$this->template_email_bank($email_to, $subject, $content, $nama_to, $email_cc);
				}
			}
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function save_update($param)
	{
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data_temp	= (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']) : NULL);
		$data_temp 		= $this->dtransaksibank->get_data_bank_temp(NULL, $id_data_temp, NULL, NULL);


		$jenis_pengajuan = (isset($_POST['jenis_pengajuan']) ? $_POST['jenis_pengajuan'] : NULL);
		$tanggal 		= (isset($_POST['tanggal']) ? $_POST['tanggal'] : NULL);
		$latar_belakang = (isset($_POST['latar_belakang']) ? $_POST['latar_belakang'] : NULL);
		$nama_bank 		= (isset($_POST['nama_bank']) ? $_POST['nama_bank'] : NULL);
		$cabang_bank	= (isset($_POST['cabang_bank']) ? $_POST['cabang_bank'] : NULL);
		$nomor_rekening = (isset($_POST['nomor_rekening']) ? $_POST['nomor_rekening'] : NULL);
		$mata_uang 		= (isset($_POST['mata_uang']) ? $_POST['mata_uang'] : NULL);
		$tujuan 		= (isset($_POST['tujuan']) ? $_POST['tujuan'] : NULL);
		$tujuan_detail 	= (isset($_POST['tujuan_detail']) ? $_POST['tujuan_detail'] : NULL);
		$no_coa 		= (isset($_POST['no_coa']) ? $_POST['no_coa'] : NULL);
		$prioritas1	 	= (isset($_POST['prioritas1']) ? $_POST['prioritas1'] : NULL);
		$prioritas2	 	= (isset($_POST['prioritas2']) ? $_POST['prioritas2'] : NULL);
		$pendamping		= (isset($_POST['pendamping']) ? implode(",", $_POST['pendamping']) : NULL);
		//penutupan
		$sisa_dana 			= (isset($_POST['sisa_dana']) ? str_replace(',', '', $_POST['sisa_dana']) : NULL);
		$id_data_awal		= (isset($_POST['id_data']) ? $_POST['id_data'] : NULL);
		$data_bank_awal		= $this->dtransaksibank->get_data_bank(NULL, $id_data_awal);
		$id_data_tujuan		= (isset($_POST['id_data_tujuan']) ? str_replace(',', '', $_POST['id_data_tujuan']) : NULL);
		$data_bank_tujuan	= $this->dtransaksibank->get_data_bank(NULL, $id_data_tujuan);
		//perubahan
		$tujuan_new 		= (isset($_POST['tujuan_new']) ? $_POST['tujuan_new'] : NULL);
		$tujuan_detail_new 	= (isset($_POST['tujuan_detail_new']) ? $_POST['tujuan_detail_new'] : NULL);
		$prioritas1_new	 	= (isset($_POST['prioritas1_new']) ? $_POST['prioritas1_new'] : NULL);
		$prioritas2_new	 	= (isset($_POST['prioritas2_new']) ? $_POST['prioritas2_new'] : NULL);
		$pendamping_new		= (isset($_POST['pendamping_new']) ? implode(",", $_POST['pendamping_new']) : NULL);


		if ($data_temp[0]->jenis_pengajuan == 'pembukaan') {
			$data_row = array(
				"tanggal" 				=> date("Y-m-d", strtotime($tanggal)),
				"latar_belakang" 		=> $latar_belakang,
				"nama_bank" 			=> $nama_bank,
				"cabang_bank" 			=> $cabang_bank,
				"nomor_rekening" 		=> $nomor_rekening,
				"mata_uang" 			=> $mata_uang,
				"tujuan" 				=> $tujuan,
				"tujuan_detail" 		=> $tujuan_detail,
				"no_coa" 				=> $no_coa,
				"prioritas1" 			=> $prioritas1,
				"prioritas2" 			=> $prioritas2,
				"pendamping" 			=> $pendamping,
			);
		}
		if ($data_temp[0]->jenis_pengajuan == 'penutupan') {
			$data_row = array(
				"tanggal" 				=> date("Y-m-d", strtotime($tanggal)),
				"latar_belakang" 		=> $latar_belakang,
				"id_data" 				=> $id_data_awal,
				"nama_bank" 			=> $data_bank_awal[0]->nama_bank,
				"cabang_bank" 			=> $data_bank_awal[0]->cabang_bank,
				"nomor_rekening" 		=> $data_bank_awal[0]->nomor_rekening,
				"mata_uang" 			=> $data_bank_awal[0]->mata_uang,
				"no_coa" 				=> $data_bank_awal[0]->no_coa,
				"sisa_dana" 			=> $sisa_dana,
				"id_data_tujuan" 		=> $id_data_tujuan,
				"nama_bank_tujuan" 		=> $data_bank_tujuan[0]->nama_bank,
				"cabang_bank_tujuan" 	=> $data_bank_tujuan[0]->cabang_bank,
				"nomor_rekening_tujuan" => $data_bank_tujuan[0]->nomor_rekening,
				"no_coa_tujuan" 		=> $data_bank_tujuan[0]->no_coa,

			);
		}
		if ($data_temp[0]->jenis_pengajuan == 'perubahan') {
			$data_row = array(
				"tanggal" 				=> date("Y-m-d", strtotime($tanggal)),
				"latar_belakang" 		=> $latar_belakang,
				"id_data" 				=> $id_data_awal,
				"nama_bank" 			=> $data_bank_awal[0]->nama_bank,
				"cabang_bank" 			=> $data_bank_awal[0]->cabang_bank,
				"nomor_rekening" 		=> $data_bank_awal[0]->nomor_rekening,
				"mata_uang" 			=> $data_bank_awal[0]->mata_uang,
				"no_coa" 				=> $data_bank_awal[0]->no_coa,
				"tujuan" 				=> $tujuan_new,
				"tujuan_detail" 		=> $tujuan_detail_new,
				"prioritas1" 			=> $prioritas1_new,
				"prioritas2" 			=> $prioritas2_new,
				"pendamping" 			=> $pendamping_new,
			);
		}

		$data_row = $this->dgeneral->basic_column("update", $data_row);
		$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
			array(
				'kolom' => 'id_data_temp',
				'value' => $id_data_temp
			)
		));
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil diupdate";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function save_approve($param)
	{
		$datetime 	= date("Y-m-d H:i:s");

		$id_data_temp				 	 = (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']) : NULL);
		$data_temp 					 	 = $this->dtransaksibank->get_data_bank_temp("open", $id_data_temp, NULL, NULL);
		$data_bank					 	 = $this->get_bank("array", $data_temp[0]->id_data);
		$status						 	 = (isset($_POST['status']) ? $_POST['status'] : NULL);
		$next_status				 	 = 	$this->dtransaksibank->get_data_user_role_status("open", $status);
		if ($data_temp[0]->asal_pengajuan == 'pabrik') {
			$status_approve			 	 = $next_status[0]->if_approve;
			$status_approve_perubahan	 = $next_status[0]->if_approve_perubahan;
			$status_approve_penutupan	 = $next_status[0]->if_approve_perubahan;
		} else {
			$status_approve			 	 = $next_status[0]->if_approve_ho;
			$status_approve_perubahan	 = $next_status[0]->if_approve_perubahan_ho;
			$status_approve_penutupan	 = $next_status[0]->if_approve_perubahan_ho;
		}
		$catatan					 	 = (isset($_POST['catatan']) ? $_POST['catatan'] : NULL);
		$subject = "";
		$content = "";
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		if ($id_data_temp != NULL) {
			//pembukaan
			if ($data_temp[0]->jenis_pengajuan == 'pembukaan') {
				//update data temp
				$data_row = array(
					"status"	 	=> $status_approve,
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
					array(
						'kolom' => 'id_data_temp',
						'value' => $id_data_temp
					)
				));
				//save data temp log 
				$data_row_log = array(
					"id_data_temp"	=> $id_data_temp,
					"status"		=> $status_approve,
					"catatan"		=> $catatan
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_bank_data_temp_log", $data_row_log);

				$data_role		= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, $status_approve);
				if ($status_approve != 99) {
					$caption_status = '
						<tr>
							<td align="left" width="20%">Status</td>
							<td align="left">: Menunggu Approval ' . $data_role[0]->nama . '</td>
						</tr>';
				} else {
					$caption_status = '
						<tr>
							<td align="left" width="20%">Status</td>
							<td align="left">: Completed</td>
						</tr>';
				}
				$content = '
					<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
						<thead>
							<tr>
								<td align="left" width="20%">Tanggal</td>
								<td align="left">: ' . $data_temp[0]->tanggal_format . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nomor</td>
								<td align="left">: ' . $data_temp[0]->nomor . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Latar Belakang</td>
								<td align="left">: ' . $data_temp[0]->latar_belakang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nama Bank</td>
								<td align="left">: ' . $data_temp[0]->nama_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Cabang Bank</td>
								<td align="left">: ' . $data_temp[0]->cabang_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Mata Uang</td>
								<td align="left">: ' . $data_temp[0]->mata_uang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Tujuan Penggunaan</td>
								<td align="left">: ' . $data_temp[0]->tujuan . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Penggunaan Detail</td>
								<td align="left">: ' . $data_temp[0]->tujuan_detail . '</td>
							</tr>
							' . $caption_status . '
						</thead>
					</table>';
				$subject 	= "Pembukaan Rekening Bank (" . $data_temp[0]->nomor . ")";
				/*
				if ($status_approve == 99) {	//complete
					$email_user	= $this->dtransaksibank->get_data_email_user_all(NULL, $data_temp[0]->pabrik);
					foreach ($email_user as $dt) {
						$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
						// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
					}
				} else {

					$email_user	= $this->dtransaksibank->get_data_email_user(NULL, $data_role[0]->tipe_user, $status_approve, $data_temp[0]->pabrik);
					foreach ($email_user as $dt) {
						$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
						// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
					}
				}
				*/
				//jika completed input ke tbl_data_bank
				if ($status_approve == 99) {
					$data_row = array(
						"asal_pengajuan" 		=> $data_temp[0]->asal_pengajuan,
						"jenis_pengajuan" 		=> $data_temp[0]->jenis_pengajuan,
						"pabrik" 				=> $data_temp[0]->pabrik,
						"status" 				=> $status_approve,
						"nomor" 				=> $data_temp[0]->nomor,
						"tanggal" 				=> $data_temp[0]->tanggal,
						"latar_belakang" 		=> $data_temp[0]->latar_belakang,
						"nama_bank" 			=> $data_temp[0]->nama_bank,
						"cabang_bank" 			=> $data_temp[0]->cabang_bank,
						"nomor_rekening" 		=> $data_temp[0]->nomor_rekening,
						"mata_uang" 			=> $data_temp[0]->mata_uang,
						"tujuan" 				=> $data_temp[0]->tujuan,
						"tujuan_detail" 		=> $data_temp[0]->tujuan_detail,
						"no_coa" 				=> $data_temp[0]->no_coa,
						"prioritas1" 			=> $data_temp[0]->prioritas1,
						"prioritas2" 			=> $data_temp[0]->prioritas2,
						"pendamping" 			=> $data_temp[0]->pendamping,
						"status_sap"	 		=> $data_temp[0]->status_sap,
					);
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_bank_data", $data_row);
					$id_data	= $this->db->insert_id();
					//update data temp
					$data_row = array(
						"id_data"	 	=> $id_data,
					);
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
						array(
							'kolom' => 'id_data_temp',
							'value' => $data_temp[0]->id_data_temp
						)
					));
				}
			}
			//penutupan
			if ($data_temp[0]->jenis_pengajuan == 'penutupan') {
				//update data temp
				$data_row = array(
					"status"	 	=> $status_approve_penutupan,
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
					array(
						'kolom' => 'id_data_temp',
						'value' => $id_data_temp
					)
				));
				//save data temp log 
				$data_row_log = array(
					"id_data_temp"	=> $id_data_temp,
					"status"		=> $status_approve_penutupan,
					"catatan"		=> $catatan
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_bank_data_temp_log", $data_row_log);

				//jika completed update ke tbl_data_bank
				if ($status_approve == 99) {
					//update data
					$data_row = array(
						"na"	 	=> 'y',
						"del"	 	=> 'y',
					);
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_bank_data", $data_row, array(
						array(
							'kolom' => 'id_data',
							'value' => $data_temp[0]->id_data
						)
					));
				}


				$data_role		= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, $status_approve_penutupan);
				if ($status_approve != 99) {
					$caption_status = '
						<tr>
							<td align="left" width="20%">Status</td>
							<td align="left">: Menunggu Approval ' . $data_role[0]->nama . '</td>
						</tr>';
				} else {
					$caption_status = '
						<tr>
							<td align="left" width="20%">Status</td>
							<td align="left">: Completed</td>
						</tr>';
				}
				$content = '
					<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
						<thead>
							<tr>
								<td align="left" width="20%">Tanggal</td>
								<td align="left">: ' . $data_temp[0]->tanggal_format . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nomor</td>
								<td align="left">: ' . $data_temp[0]->nomor . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Latar Belakang</td>
								<td align="left">: ' . $data_temp[0]->latar_belakang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nama Bank</td>
								<td align="left">: ' . $data_temp[0]->nama_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Cabang Bank</td>
								<td align="left">: ' . $data_temp[0]->cabang_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Mata Uang</td>
								<td align="left">: ' . $data_temp[0]->mata_uang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Sisa Dana</td>
								<td align="left">: ' . $data_temp[0]->sisa_dana . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nomor Rekening Tujuan</td>
								<td align="left">: ' . $data_temp[0]->nomor_rekening_tujuan . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">No COA Tujuan</td>
								<td align="left">: ' . $data_temp[0]->no_coa_tujuan . '</td>
							</tr>
							' . $caption_status . '
						</thead>
					</table>';
				$subject 	= "Penutupan Rekening Bank (" . $data_temp[0]->nomor . ")";
				/*
				if ($status_approve == 99) {	//complete
					$email_user	= $this->dtransaksibank->get_data_email_user_all(NULL, $data_temp[0]->pabrik);
					foreach ($email_user as $dt) {
						$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
						// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
					}
				} else {

					$email_user	= $this->dtransaksibank->get_data_email_user(NULL, $data_role[0]->tipe_user, $status_approve, $data_temp[0]->pabrik);
					foreach ($email_user as $dt) {
						$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
						// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
					}
				}
				*/
			}
			//perubahan
			if ($data_temp[0]->jenis_pengajuan == 'perubahan') {
				//update data temp
				$data_row = array(
					"status"	 	=> $status_approve_perubahan,
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
					array(
						'kolom' => 'id_data_temp',
						'value' => $id_data_temp
					)
				));
				//save data temp log 
				$data_row_log = array(
					"id_data_temp"	=> $id_data_temp,
					"status"		=> $status_approve_perubahan,
					"catatan"		=> $catatan
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_bank_data_temp_log", $data_row_log);

				//jika completed update ke tbl_data_bank
				if ($status_approve == 99) {
					//update data
					$data_row = array(
						"tujuan" 				=> $data_temp[0]->tujuan,
						"tujuan_detail" 		=> $data_temp[0]->tujuan_detail,
						"prioritas1" 			=> $data_temp[0]->prioritas1,
						"prioritas2" 			=> $data_temp[0]->prioritas2,
						"pendamping" 			=> $data_temp[0]->pendamping,
					);
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_bank_data", $data_row, array(
						array(
							'kolom' => 'id_data',
							'value' => $data_temp[0]->id_data
						)
					));
				}


				$data_role		= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, $status_approve_perubahan);
				if ($status_approve != 99) {
					$caption_status = '
						<tr>
							<td align="left" width="20%">Status</td>
							<td align="left">: Menunggu Approval ' . $data_role[0]->nama . '</td>
						</tr>';
				} else {
					$caption_status = '
						<tr>
							<td align="left" width="20%">Status</td>
							<td align="left">: Completed</td>
						</tr>';
				}
				$content = '
					<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
						<thead>
							<tr>
								<td align="left" width="20%">Tanggal</td>
								<td align="left">: ' . $data_temp[0]->tanggal_format . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nomor</td>
								<td align="left">: ' . $data_temp[0]->nomor . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Latar Belakang</td>
								<td align="left">: ' . $data_temp[0]->latar_belakang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nama Bank</td>
								<td align="left">: ' . $data_bank[0]->nama_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Cabang Bank</td>
								<td align="left">: ' . $data_bank[0]->cabang_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Mata Uang</td>
								<td align="left">: ' . $data_bank[0]->mata_uang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Tujuan Penggunaan</td>
								<td align="left">:' . $data_bank[0]->tujuan . ' &#8594; ' . $data_temp[0]->tujuan . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Penggunaan Detail</td>
								<td align="left">: ' . $data_bank[0]->tujuan_detail . ' &#8594; ' . $data_temp[0]->tujuan_detail . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Pihak Prioritas 1</td>
								<td align="left">: ' . $data_bank[0]->prioritas1 . ' &#8594; ' . $data_temp[0]->prioritas1 . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Pihak Prioritas 2</td>
								<td align="left">: ' . $data_bank[0]->prioritas2 . ' &#8594; ' . $data_temp[0]->prioritas2 . '</td>
							</tr>
							' . $caption_status . '
						</thead>
					</table>';
				$subject 	= "Perubahan Rekening Bank (" . $data_temp[0]->nomor . ")";
				/*
				if ($status_approve == 99) {	//complete
					$email_user	= $this->dtransaksibank->get_data_email_user_all(NULL, $data_temp[0]->pabrik);
					foreach ($email_user as $dt) {
						$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
																									  
						// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
					}
				} else {

					$email_user	= $this->dtransaksibank->get_data_email_user(NULL, $data_role[0]->tipe_user, $status_approve, $data_temp[0]->pabrik);
					foreach ($email_user as $dt) {
						$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
																									  
						// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
					}
				}
				*/
			}
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			if (!empty($subject) && !empty($content)) {
				$email_user	= $this->dtransaksibank->get_data_email_user(
					array(
						"connect" => TRUE,
						"no" => $data_temp[0]->nomor
					)
				);
				if ($email_user) {
					$email_to = array();
					$nama_to = array();
					$email_cc = array();
					foreach ($email_user as $dt) {
						if ($dt->user_status == 'to') {
							$email_to[] = $dt->email;
							$nama_to[] = $dt->gender . ' ' . $dt->nama;
						}
						if ($dt->user_status == 'cc') {
							$email_cc[] = $dt->email;
						}
					}
					$this->template_email_bank($email_to, $subject, $content, $nama_to, $email_cc);
				}
			}
			$msg = "Data berhasil diapprove";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function save_decline($param)
	{
		$datetime 	= date("Y-m-d H:i:s");
		$id_data_temp				 	 = (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']) : NULL);
		$data_temp 					 	 = $this->dtransaksibank->get_data_bank_temp("open", $id_data_temp, NULL, NULL);
		$data_bank					 	 = $this->get_bank("array", $data_temp[0]->id_data);
		$status						 	 = (isset($_POST['status']) ? $_POST['status'] : NULL);
		$next_status				 	 = 	$this->dtransaksibank->get_data_user_role_status("open", $status);
		if ($data_temp[0]->asal_pengajuan == 'pabrik') {
			$status_decline			 	 = $next_status[0]->if_decline;
			$status_decline_perubahan	 = $next_status[0]->if_decline_perubahan;
			$status_decline_penutupan	 = $next_status[0]->if_decline_perubahan;
		} else {
			$status_decline			 	 = $next_status[0]->if_decline_ho;
			$status_decline_perubahan	 = $next_status[0]->if_decline_perubahan_ho;
			$status_decline_penutupan	 = $next_status[0]->if_decline_perubahan_ho;
		}
		$catatan					 	 = (isset($_POST['catatan']) ? $_POST['catatan'] : NULL);
		$subject = "";
		$content = "";
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		if ($id_data_temp != NULL) {
			//pembukaan
			if ($data_temp[0]->jenis_pengajuan == 'pembukaan') {
				//update data temp
				$data_row = array(
					"status"	 	=> $status_decline,
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
					array(
						'kolom' => 'id_data_temp',
						'value' => $id_data_temp
					)
				));
				//save data temp log 
				$data_row_log = array(
					"id_data_temp"	=> $id_data_temp,
					"status"		=> $status_decline,
					"catatan"		=> $catatan
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_bank_data_temp_log", $data_row_log);


				$data_role		= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, $status);
				$content = '
					<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
						<thead>
							<tr>
								<td align="left" width="20%">Tanggal</td>
								<td align="left">: ' . $data_temp[0]->tanggal_format . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nomor</td>
								<td align="left">: ' . $data_temp[0]->nomor . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Latar Belakang</td>
								<td align="left">: ' . $data_temp[0]->latar_belakang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nama Bank</td>
								<td align="left">: ' . $data_temp[0]->nama_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Cabang Bank</td>
								<td align="left">: ' . $data_temp[0]->cabang_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Mata Uang</td>
								<td align="left">: ' . $data_temp[0]->mata_uang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Tujuan Penggunaan</td>
								<td align="left">: ' . $data_temp[0]->tujuan . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Penggunaan Detail</td>
								<td align="left">: ' . $data_temp[0]->tujuan_detail . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Status</td>
								<td align="left">: Decline Oleh ' . $data_role[0]->nama . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Catatan</td>
								<td align="left">: ' . $catatan . '</td>
							</tr>
						</thead>
					</table>';
				$subject 	= "Pembukaan Rekening Bank (" . $data_temp[0]->nomor . ")";
				/*
				$email_user	= $this->dtransaksibank->get_data_email_user(NULL, NULL, NULL, NULL, $data_temp[0]->login_buat);
				foreach ($email_user as $dt) {
					$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
					// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
				}
				*/
			}
			//penutupan
			if ($data_temp[0]->jenis_pengajuan == 'penutupan') {
				//update data temp
				$data_row = array(
					"status"	 	=> $status_decline_penutupan,
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
					array(
						'kolom' => 'id_data_temp',
						'value' => $id_data_temp
					)
				));
				//save data temp log 
				$data_row_log = array(
					"id_data_temp"	=> $id_data_temp,
					"status"		=> $status_decline_penutupan,
					"catatan"		=> $catatan
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_bank_data_temp_log", $data_row_log);

				$data_role		= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, $status);
				$content = '
					<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
						<thead>
							<tr>
								<td align="left" width="20%">Tanggal</td>
								<td align="left">: ' . $data_temp[0]->tanggal_format . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nomor</td>
								<td align="left">: ' . $data_temp[0]->nomor . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Latar Belakang</td>
								<td align="left">: ' . $data_temp[0]->latar_belakang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nama Bank</td>
								<td align="left">: ' . $data_temp[0]->nama_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Cabang Bank</td>
								<td align="left">: ' . $data_temp[0]->cabang_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Mata Uang</td>
								<td align="left">: ' . $data_temp[0]->mata_uang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Sisa Dana</td>
								<td align="left">: ' . $data_temp[0]->sisa_dana . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nomor Rekening Tujuan</td>
								<td align="left">: ' . $data_temp[0]->nomor_rekening_tujuan . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">No COA Tujuan</td>
								<td align="left">: ' . $data_temp[0]->no_coa_tujuan . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Status</td>
								<td align="left">: Decline Oleh ' . $data_role[0]->nama . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Catatan</td>
								<td align="left">: ' . $catatan . '</td>
							</tr>
						</thead>
					</table>';
				$subject 	= "Penutupan Rekening Bank (" . $data_temp[0]->nomor . ")";
				/*
				$email_user	= $this->dtransaksibank->get_data_email_user(NULL, NULL, NULL, NULL, $data_temp[0]->login_buat);
				foreach ($email_user as $dt) {
					$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
					// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
				}
				*/
			}
			//perubahan
			if ($data_temp[0]->jenis_pengajuan == 'perubahan') {
				//update data temp
				$data_row = array(
					"status"	 	=> $status_decline_perubahan,
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
					array(
						'kolom' => 'id_data_temp',
						'value' => $id_data_temp
					)
				));
				//save data temp log 
				$data_row_log = array(
					"id_data_temp"	=> $id_data_temp,
					"status"		=> $status_decline_perubahan,
					"catatan"		=> $catatan
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_bank_data_temp_log", $data_row_log);

				$data_role		= $this->dmasterbank->get_data_role(NULL, NULL, NULL, NULL, $status);
				$content = '
					<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
						<thead>
							<tr>
								<td align="left" width="20%">Tanggal</td>
								<td align="left">: ' . $data_temp[0]->tanggal_format . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nomor</td>
								<td align="left">: ' . $data_temp[0]->nomor . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Latar Belakang</td>
								<td align="left">: ' . $data_temp[0]->latar_belakang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Nama Bank</td>
								<td align="left">: ' . $data_temp[0]->nama_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Cabang Bank</td>
								<td align="left">: ' . $data_temp[0]->cabang_bank . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Mata Uang</td>
								<td align="left">: ' . $data_temp[0]->mata_uang . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Tujuan Penggunaan</td>
								<td align="left">:' . $data_bank[0]->tujuan . ' &#8594; ' . $data_temp[0]->tujuan . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Penggunaan Detail</td>
								<td align="left">: ' . $data_bank[0]->tujuan_detail . ' &#8594; ' . $data_temp[0]->tujuan_detail . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Pihak Prioritas 1</td>
								<td align="left">: ' . $data_bank[0]->prioritas1 . ' &#8594; ' . $data_temp[0]->prioritas1 . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Pihak Prioritas 2</td>
								<td align="left">: ' . $data_bank[0]->prioritas2 . ' &#8594; ' . $data_temp[0]->prioritas2 . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Status</td>
								<td align="left">: Decline Oleh ' . $data_role[0]->nama . '</td>
							</tr>
							<tr>
								<td align="left" width="20%">Catatan</td>
								<td align="left">: ' . $catatan . '</td>
							</tr>
						</thead>
					</table>';
				$subject 	= "Penutupan Rekening Bank (" . $data_temp[0]->nomor . ")";
				/*
				$email_user	= $this->dtransaksibank->get_data_email_user(NULL, NULL, NULL, NULL, $data_temp[0]->login_buat);
				foreach ($email_user as $dt) {
					$this->template_email_bank($dt->email, $subject, $content, $dt->nama);
					// $this->template_email_bank('ADY.PUDRIANSYAH@KIRANAMEGATARA.COM', $subject, $content, $dt->nama);
				}
				*/
			}
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			if (!empty($subject) && !empty($content)) {
				$email_user	= $this->dtransaksibank->get_data_email_user(
					array(
						"connect" => TRUE,
						"no" => $data_temp[0]->nomor
					)
				);
				if ($email_user) {
					$email_to = array();
					$nama_to = array();
					$email_cc = array();
					foreach ($email_user as $dt) {
						if ($dt->user_status == 'to') {
							$email_to[] = $dt->email;
							$nama_to[] = $dt->gender . ' ' . $dt->nama;
						}
						if ($dt->user_status == 'cc') {
							$email_cc[] = $dt->email;
						}
					}
					$this->template_email_bank($email_to, $subject, $content, $nama_to, $email_cc);
				}
			}
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function save_rekening($param)
	{
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data_temp	= (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']) : NULL);
		$data_temp 		= $this->dtransaksibank->get_data_bank_temp(NULL, $id_data_temp, NULL, NULL);
		$nomor_rekening	= (isset($_POST['nomor_rekening']) ? $_POST['nomor_rekening'] : NULL);
		if ($id_data_temp != NULL) {
			//update data temp
			$data_row = array(
				"nomor_rekening" => $nomor_rekening,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $data_temp[0]->nomor
				)
			));
			//update data
			$data = array(
				"nomor_rekening" => $nomor_rekening,
			);
			$data = $this->dgeneral->basic_column("update", $data);
			$this->dgeneral->update("tbl_bank_data", $data, array(
				array(
					'kolom' => 'nomor',
					'value' => $data_temp[0]->nomor
				)
			));
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Nomor Rekening berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function save_coa($param)
	{
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data_temp	= (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']) : NULL);
		$data_temp 		= $this->dtransaksibank->get_data_bank_temp(NULL, $id_data_temp, NULL, NULL);
		$no_coa			= (isset($_POST['no_coa']) ? $_POST['no_coa'] : NULL);
		if ($id_data_temp != NULL) {
			//update data temp
			$data_row = array(
				"no_coa" => $no_coa,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_bank_data_temp", $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $data_temp[0]->nomor
				)
			));
			//update data
			$data = array(
				"no_coa" => $no_coa,
			);
			$data = $this->dgeneral->basic_column("update", $data);
			$this->dgeneral->update("tbl_bank_data", $data, array(
				array(
					'kolom' => 'nomor',
					'value' => $data_temp[0]->nomor
				)
			));
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "No COA berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function save_dokumen($param)
	{
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data_temp	= (isset($_POST['id_data_temp']) ? $this->generate->kirana_decrypt($_POST['id_data_temp']) : NULL);
		$jenis_pengajuan = (isset($_POST['jenis_pengajuan']) ? $_POST['jenis_pengajuan'] : NULL);

		//list data dokumen
		$dokumen 	= $this->dmasterbank->get_data_dokumen(NULL, NULL, 'n', 'n', NULL, NULL, NULL, $jenis_pengajuan);
		$dokumen 	= $this->general->generate_encrypt_json($dokumen, array("id_dokumen"));
		// echo json_encode($dokumen);
		// exit();
		foreach ($dokumen as $dt2) {
			$file_dokumen	= 'dokumen_' . $dt2->id_dokumen;
			$nama_dokumen	= str_replace(' ', '_', $dt2->nama . '_' . $id_data_temp);
			//upload file jenis vendor
			if ($_FILES[$file_dokumen]['name'][0] != '') {
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
				$config['max_size']      = 0;

				$newname	= array($nama_dokumen);
				// echo json_encode($config);
				// exit();


				$file		= $this->general->upload_files($_FILES[$file_dokumen], $newname, $config);
				$nama_file	= str_replace('_', ' ', $newname[0]);
				$url_file	= str_replace("assets/", "", $file[0]['url']);
				if ($file === NULL) {
					$msg        = "Upload files error";
					$sts        = "NotOK";
					$return     = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				//xx	
				$ck_dokumen	= $this->dtransaksibank->get_data_bank_dokumen_temp(NULL, $id_data_temp, $this->generate->kirana_decrypt($dt2->id_dokumen));
				if (count($ck_dokumen) == 0) {
					//data dokumen
					$data_dokumen = array(
						"id_data_temp"	=> $id_data_temp,
						'id_dokumen' => $this->generate->kirana_decrypt($dt2->id_dokumen),
						'nama'      => $nama_file,
						'url' 	 	=> str_replace(' ', '_', $url_file),
						'tipe'     	=> pathinfo($url_file, PATHINFO_EXTENSION),
						'ukuran'   	=> $file[0]['size'],
					);
					$data_dokumen = $this->dgeneral->basic_column("insert", $data_dokumen);
					$this->dgeneral->insert("tbl_bank_data_dokumen_temp", $data_dokumen);
				} else {
					//data dokumen
					$data_dokumen = array(
						"id_data_temp"	=> $id_data_temp,
						'id_dokumen' => $this->generate->kirana_decrypt($dt2->id_dokumen),
						'nama'      => $nama_file,
						'url' 	 	=> str_replace(' ', '_', $url_file),
						'tipe'     	=> pathinfo($url_file, PATHINFO_EXTENSION),
						'ukuran'   	=> $file[0]['size'],
					);
					$data_dokumen = $this->dgeneral->basic_column("update", $data_dokumen);
					$this->dgeneral->update("tbl_bank_data_dokumen_temp", $data_dokumen, array(
						array(
							'kolom' => 'id_data_temp',
							'value' => $id_data_temp
						),
						array(
							'kolom' => 'id_dokumen',
							'value' => $ck_dokumen[0]->id_dokumen
						)

					));
				}
			}
		}


		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Dokumen Berhasil diupload";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	/*====================================================================*/
}
