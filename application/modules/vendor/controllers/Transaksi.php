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
include_once APPPATH . "modules/material/controllers/BaseControllers.php";

// Class Transaksi extends BaseControllers{
Class Transaksi extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		
		$this->load->model('spk/dmaster');
		$this->load->model('dmastervendor');
	    $this->load->model('dtransaksivendor');
		$this->load->model('folder/dsettingfolder');
	}

	public function index(){
		show_404();
	}
	
	public function test_send_email(){
		$content = '
			<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 11px;">
				<thead>
					<tr>
						<th align="left" width="20%">Nama Vendor</th>
						<th align="left">: test</th>
					</tr>
					<tr>
						<th align="left" width="20%">Jenis Vendor</th>
						<th align="left">: test</th>
					</tr>
					<tr>
						<th align="left" width="20%">Jenis Pengajuan</th>
						<th align="left">: test</th>
					</tr>
					<tr>
						<th align="left" width="20%">Status Pengajuan</th>
						<th align="left">: test</th>
					</tr>
				</thead>
			</table>';	
		$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM','Pengajuan Master Vendor',$content);
	}
	public function template_email_vendor($to=NULL, $subject=NULL, $content=NULL, $nama_penerima=NULL){
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
		$this->email->to($to);

			
		$message = '<html>
		<body style=" background-color: #386d22">
		<center style="width: 100%;">
		<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
			&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
		</div>
		<div class="email-container" style="max-width: 800px; margin: 0 auto;">
			<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="min-width:600px;">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td style="color: #fff; padding:20px;" align="center">
						<h1 style="margin-bottom: 0;">Master Vendor</h1>
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
									<p><strong>Kepada Bapak/Ibu '.$nama_penerima.',</strong></p>
									<p>Berikut adalah pemberitahuan dari Master Vendor Kiranaku</p>
									<table role="presentation" border="0" width="100%" cellpadding="0" cellspacing="0">
										<tbody>
											<tr><td><strong>Konfirmasi '.$subject.', Mohon untuk ditindaklanjuti.</strong></td></tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left" style="background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;">
									'.$content.'
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
						<strong style="color: #214014; font-size: 10px;">Terkirim pada '.date('d.m.Y H:i:s').'</strong>
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
	
	public function daftar($param=NULL){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Create Vendor";
		$data['title_form']  = "Form Create Vendor";
		$data['tipe']	 	 = $this->get_tipe('array');
		$data['kategori'] 	 = $this->get_kategori('array');
		$data['kriteria'] 	 = $this->get_kriteria('array');
		
		// $data['spec'] 	 	 = $this->get_spec('array', NULL, NULL, NULL);
		$this->load->view("transaksi/daftar", $data);	
	}
	
	public function input($param=NULL){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Input Master Vendor";
		$data['title_form']  = "Create Vendor";
		// $data['tipe']	 	 = $this->get_tipe('array');
		// $data['kategori'] 	 = $this->get_kategori('array');	
		$data['jenis']	 	 = $this->get_jenis('array', NULL, 'n');
		$data['kualifikasi'] = $this->get_kualifikasi('array', NULL, 'n');	
		$data['role'] 		 = $this->get_role('array', NULL, 'n');	

		$data['kriteria'] 	 = $this->get_kriteria('array');
		$data['plant']		 = $this->dtransaksivendor->get_master_plant(); 
		// echo json_encode($data['plant']);
		// exit();
		$data['acc_group'] 	 = $this->get_acc_group('array');
		$data['title_medi']  = $this->get_title('array');
		$data['negara']  	 = $this->get_negara('array');
		// $data['provinsi'] = $this->get_provinsi('array');
		$data['industri']  	 = $this->get_industri('array');
		$data['payment_term']= $this->get_payment_term('array');
		$data['cur']  		 = $this->get_cur('array');
		$data['tax_type']  	 = $this->get_tax_type('array');
		$data['tax_code'] 	 = $this->get_tax_code('array');
		$data['user_role'] 	 = $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, base64_decode($this->session->userdata("-nik-")));
		$data['term1']  	 = $this->get_term1('array');
		
		// $data['spec'] 	 	 = $this->get_spec('array', NULL, NULL, NULL);
		$this->load->view("transaksi/input", $data);	
	}
	
	public function extend($param=NULL){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Extend Master Vendor";
		$data['title_form']  = "Create Vendor";
		$data['tipe']	 	 = $this->get_tipe('array');
		$data['kategori'] 	 = $this->get_kategori('array');	
		$data['kriteria'] 	 = $this->get_kriteria('array');
		$data['plant']		 = $this->dgeneral->get_master_plant(); 
		$data['acc_group'] 	 = $this->get_acc_group('array');
		$data['title_medi']  = $this->get_title('array');
		$data['negara']  	 = $this->get_negara('array');
		// $data['provinsi']  	 = $this->get_provinsi('array');
		$data['industri']  	 = $this->get_industri('array');
		$data['payment_term']  	 = $this->get_payment_term('array');
		$data['cur']  		 = $this->get_cur('array');
		$data['tax_type']  		 = $this->get_tax_type('array');
		$data['tax_code']  		 = $this->get_tax_code('array');
		
		// $data['spec'] 	 	 = $this->get_spec('array', NULL, NULL, NULL);
		$this->load->view("transaksi/extend", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'kualifikasi_dokumen':    
				// $id_kualifikasi_spk = (isset($_POST['id_kualifikasi_spk']) ? $_POST['id_kualifikasi_spk'] : NULL);
				if(isset($_POST['id_kualifikasi_spk'])){
					$id_kualifikasi_spk	= array();
					foreach ($_POST['id_kualifikasi_spk'] as $dt) {
						// array_push($id_kualifikasi_spk, $this->generate->kirana_decrypt($dt));
						array_push($id_kualifikasi_spk, $dt);
					}
				}else{
					$id_kualifikasi_spk  = NULL;
				}
				$id_data = (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
				$id_data_temp = (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
				$this->get_kualifikasi_dokumen(NULL, $id_kualifikasi_spk, 'n', NULL, $id_data, $id_data_temp);
				break;
			case 'kualifikasi_dokumen_temp':    
				// $id_kualifikasi_spk = (isset($_POST['id_kualifikasi_spk']) ? $_POST['id_kualifikasi_spk'] : NULL);
				if(isset($_POST['id_kualifikasi_spk'])){
					$id_kualifikasi_spk	= array();
					foreach ($_POST['id_kualifikasi_spk'] as $dt) {
						// array_push($id_kualifikasi_spk, $this->generate->kirana_decrypt($dt));
						array_push($id_kualifikasi_spk, $dt);
					}
				}else{
					$id_kualifikasi_spk  = NULL;
				}
				$id_data = (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
				$id_data_temp = (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
				$this->get_kualifikasi_dokumen_temp(NULL, $id_kualifikasi_spk, 'n', NULL, $id_data, $id_data_temp);
				break;
			
			case 'jenis_vendor_dokumen':
				$id_jenis_vendor = (isset($_POST['id_jenis_vendor']) ? $this->generate->kirana_decrypt($_POST['id_jenis_vendor']) : NULL);
				$id_data 		 = (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
				$id_data_temp 	 = (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
				$this->get_jenis_vendor_dokumen(NULL, NULL, 'n', NULL, $id_jenis_vendor, $id_data, $id_data_temp);
				break;
			case 'jenis_vendor_dokumen_temp':
				$id_jenis_vendor = (isset($_POST['id_jenis_vendor']) ? $this->generate->kirana_decrypt($_POST['id_jenis_vendor']) : NULL);
				$id_data 		 = (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
				$id_data_temp 	 = (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
				$this->get_jenis_vendor_dokumen_temp(NULL, NULL, 'n', NULL, $id_jenis_vendor, $id_data, $id_data_temp);
				break;
			
			case 'data':
				$id_data  = (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
				$action   = (isset($_POST['action']) ? $_POST['action'] : NULL);
				//jenis
				if(isset($_POST['id_jenis_vendor'])){
					$id_jenis_vendor	= array();
					foreach ($_POST['id_jenis_vendor'] as $dt) {
						array_push($id_jenis_vendor, $this->generate->kirana_decrypt($dt));
					}
				}else{
					$id_jenis_vendor  = NULL;
				}
				//kualifikasi
				if(isset($_POST['id_kualifikasi_spk'])){
					$id_kualifikasi_spk	= array();
					foreach ($_POST['id_kualifikasi_spk'] as $dt) {
						array_push($id_kualifikasi_spk, $dt);
					}
				}else{
					$id_kualifikasi_spk  = NULL;
				}
				//status pending
				if(isset($_POST['id_role'])){
					$id_role	= array();
					foreach ($_POST['id_role'] as $dt) {
						array_push($id_role, $dt);
					}
				}else{
					$id_role  = NULL;
				}
				//jenis pengajuan
				if(isset($_POST['jenis_pengajuan'])){
					$jenis_pengajuan	= array();
					foreach ($_POST['jenis_pengajuan'] as $dt) {
						array_push($jenis_pengajuan, $dt);
					}
				}else{
					$jenis_pengajuan  = NULL;
				}
				
				//pengajuan
				if(isset($_POST['status_pengajuan'])){
					$status_pengajuan	= array();
					foreach ($_POST['status_pengajuan'] as $dt) {
						array_push($status_pengajuan, $dt);
					}
				}else{
					$status_pengajuan  = NULL;
				}
				//extend
				if(isset($_POST['status_extend'])){
					$status_extend	= array();
					foreach ($_POST['status_extend'] as $dt) {
						array_push($status_extend, $dt);
					}
				}else{
					$status_extend  = NULL;
				}
				//change
				if(isset($_POST['status_change'])){
					$status_change	= array();
					foreach ($_POST['status_change'] as $dt) {
						array_push($status_change, $dt);
					}
				}else{
					$status_change  = NULL;
				}
				//delete
				if(isset($_POST['status_delete'])){
					$status_delete	= array();
					foreach ($_POST['status_delete'] as $dt) {
						array_push($status_delete, $dt);
					}
				}else{
					$status_delete  = NULL;
				}
				//undelete
				if(isset($_POST['status_undelete'])){
					$status_undelete	= array();
					foreach ($_POST['status_undelete'] as $dt) {
						array_push($status_undelete, $dt);
					}
				}else{
					$status_undelete  = NULL;
				}
				
				if($param2=='bom'){
					header('Content-Type: application/json');
					$return = $this->dtransaksivendor->get_data_vendor_bom('open', $id_data, NULL, NULL, $id_jenis_vendor, $id_kualifikasi_spk, $id_role, $status_pengajuan, $status_extend, $status_change, $status_delete, $status_undelete, $jenis_pengajuan);

					echo $return;
					break;
				}else{
					$this->get_vendor(NULL, $id_data, NULL, NULL, $param2, $action);
					break;
				}
			case 'data_extend':
				$id_data  = (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
				
				if(isset($_POST['id_tipe'])){
					$id_tipe	= array();
					foreach ($_POST['id_tipe'] as $dt) {
						array_push($id_tipe, $this->generate->kirana_decrypt($dt));
					}
				}else{
					$id_tipe  = NULL;
				}
				if(isset($_POST['id_kategori'])){
					$id_kategori	= array();
					foreach ($_POST['id_kategori'] as $dt) {
						array_push($id_kategori, $this->generate->kirana_decrypt($dt));
					}
				}else{
					$id_kategori  = NULL;
				}
				if(isset($_POST['status_filter'])){
					$status_filter	= array();
					foreach ($_POST['status_filter'] as $dt) {
						array_push($status_filter, $dt);
					}
				}else{
					$status_filter  = NULL;
				}
				
				if($param2=='bom'){
					header('Content-Type: application/json');
					$return = $this->dtransaksivendor->get_data_extend_vendor_bom('open', $id_data, NULL, NULL, $id_tipe, $id_kategori, $status_filter);
					echo $return;
					break;
				}else{
					$this->get_vendor(NULL, $id_data, NULL, NULL);
					break;
				}
			case 'tax_code':
				$tax_type 	= (isset($_POST['tax_type']) ? $_POST['tax_type'] : NULL);
				$this->get_tax_code(NULL, $tax_type);
				break;
			case 'provinsi':
				$id_propinsi  	= (isset($_POST['id_propinsi']) ? $_POST['id_propinsi'] : NULL);
				$negara  		= (isset($_POST['negara']) ? $_POST['negara'] : NULL);
				$this->get_provinsi(NULL, $id_propinsi, $negara);
				break;
			case 'term2':
				$term1	= (isset($_POST['term1']) ? $_POST['term1'] : NULL);
				$this->get_term2(NULL, $term1);
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
				case 'data':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_vendor_data", array(
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
	public function save($param = NULL) {
		switch ($param) {
			case 'vendor':
				$this->save_vendor($param);
				break;
			case 'approve':
				$this->save_approve($param);
				break;
			case 'approve_change':
				$this->save_approve_change($param);
				break;
			case 'approve_extend':
				$this->save_approve_extend($param);
				break;
			case 'approve_undelete':
				$this->save_approve_undelete($param);
				break;
			case 'decline':
				$this->save_decline($param);
				break;
			case 'decline_change':
				$this->save_decline_change($param);
				break;
			case 'decline_extend':
				$this->save_decline_extend($param);
				break;
			case 'decline_delete':
				$this->save_decline_delete($param);
				break;
			case 'decline_undelete':
				$this->save_decline_undelete($param);
				break;
			case 'extend':
				$this->save_extend($param);
				break;
			case 'delete':
				$this->save_delete($param);
				break;
			case 'undelete':
				$this->save_undelete($param);
				break;
			case 'change':
				$this->save_change($param);
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
		$role 		= $this->dmastervendor->get_data_role("open", $id_role, $active, $deleted);
		if ($array) {
			return $role;
		} else {
			echo json_encode($role);
		}
		
	}
	private function get_kualifikasi_dokumen($array = NULL, $id_kualifikasi_spk = NULL, $active = NULL, $deleted = NULL, $id_data = NULL, $id_data_temp = NULL) {
		$kualifikasi_dokumen 		= $this->dtransaksivendor->get_data_kualifikasi_dokumen("open", $id_kualifikasi_spk, $active, $deleted, $id_data, $id_data_temp);
		if ($array) {
			return $kualifikasi_dokumen;
		} else {
			echo json_encode($kualifikasi_dokumen);
		}
		
	}
	private function get_kualifikasi_dokumen_temp($array = NULL, $id_kualifikasi_spk = NULL, $active = NULL, $deleted = NULL, $id_data = NULL, $id_data_temp = NULL) {
		$kualifikasi_dokumen_temp 		= $this->dtransaksivendor->get_data_kualifikasi_dokumen_temp("open", $id_kualifikasi_spk, $active, $deleted, $id_data, $id_data_temp);
		if ($array) {
			return $kualifikasi_dokumen_temp;
		} else {
			echo json_encode($kualifikasi_dokumen_temp);
		}
		
	}
	private function get_jenis_vendor_dokumen($array = NULL, $id_oto_vendor = NULL, $active = NULL, $deleted = NULL, $id_jenis_vendor = NULL, $id_data = NULL, $id_data_temp = NULL) {
		$jenis_vendor_dokumen 		= $this->dtransaksivendor->get_data_jenis_vendor_dokumen("open", $id_oto_vendor, $active, $deleted, $id_jenis_vendor, $id_data, $id_data_temp);
		$jenis_vendor_dokumen 		= $this->general->generate_encrypt_json($jenis_vendor_dokumen, array("id_oto_vendor"));
		if ($array) {
			return $jenis_vendor_dokumen;
		} else {
			echo json_encode($jenis_vendor_dokumen);
		}
	}
	
	private function get_jenis_vendor_dokumen_temp($array = NULL, $id_oto_vendor = NULL, $active = NULL, $deleted = NULL, $id_jenis_vendor = NULL, $id_data = NULL, $id_data_temp = NULL) {
		$jenis_vendor_dokumen_temp 		= $this->dtransaksivendor->get_data_jenis_vendor_dokumen_temp("open", $id_oto_vendor, $active, $deleted, $id_jenis_vendor, $id_data, $id_data_temp);
		$jenis_vendor_dokumen_temp 		= $this->general->generate_encrypt_json($jenis_vendor_dokumen_temp, array("id_oto_vendor"));
		if ($array) {
			return $jenis_vendor_dokumen_temp;
		} else {
			echo json_encode($jenis_vendor_dokumen_temp);
		}
	}
	
	private function get_jenis($array = NULL, $id_jenis_vendor = NULL, $active = NULL, $deleted = NULL) {
		$jenis 		= $this->dtransaksivendor->get_data_jenis("open", $id_jenis_vendor, $active, $deleted);
		$jenis 		= $this->general->generate_encrypt_json($jenis, array("id_jenis_vendor"));
		if ($array) {
			return $jenis;
		} else {
			echo json_encode($jenis);
		}
	}
	private function get_kualifikasi($array = NULL, $id_kualifikasi_spk = NULL, $active = NULL, $deleted = NULL) {
		$kualifikasi 		= $this->dtransaksivendor->get_data_kualifikasi("open", $id_kualifikasi_spk, $active, $deleted);
		// $kualifikasi 		= $this->general->generate_encrypt_json($kualifikasi, array("id_kualifikasi_spk"));
		if ($array) {
			return $kualifikasi;
		} else {
			echo json_encode($kualifikasi);
		}
	}
	private function get_tipe($array = NULL, $id_tipe = NULL, $active = NULL, $deleted = NULL) {
		$tipe 		= $this->dmastervendor->get_data_tipe("open", $id_tipe, $active, $deleted);
		$tipe 		= $this->general->generate_encrypt_json($tipe, array("id_tipe"));
		if ($array) {
			return $tipe;
		} else {
			echo json_encode($tipe);
		}
	}
	private function get_kategori($array = NULL, $id_kategori = NULL, $active = NULL, $deleted = NULL) {
		$kategori 		= $this->dmastervendor->get_data_kategori("open", $id_kategori, $active, $deleted);
		$kategori 		= $this->general->generate_encrypt_json($kategori, array("id_kategori"));
		if ($array) {
			return $kategori;
		} else {
			echo json_encode($kategori);
		}
	}
	private function get_acc_group($array = NULL) {
		$acc_group 		= $this->dmastervendor->get_data_acc_group("open");
		if ($array) {
			return $acc_group;
		} else {
			echo json_encode($acc_group);
		}
	}
	private function get_title($array = NULL) {
		$title 		= $this->dmastervendor->get_data_title("open");
		if ($array) {
			return $title;
		} else {
			echo json_encode($title);
		}
	}
	private function get_payment_term($array = NULL) {
		$payment_term 		= $this->dmastervendor->get_data_payment_term("open");
		if ($array) {
			return $payment_term;
		} else {
			echo json_encode($payment_term);
		}
	}
	private function get_cur($array = NULL) {
		$cur 		= $this->dmastervendor->get_data_cur("open");
		if ($array) {
			return $cur;
		} else {
			echo json_encode($cur);
		}
	}
	private function get_tax_type($array = NULL) {
		$tax_type 		= $this->dmastervendor->get_data_tax_type("open");
		if ($array) {
			return $tax_type;
		} else {
			echo json_encode($tax_type);
		}
	}
	private function get_tax_code($array = NULL, $tax_type = NULL) {
		$tax_code 		= $this->dmastervendor->get_data_tax_code("open", $tax_type);
		if ($array) {
			return $tax_code;
		} else {
			echo json_encode($tax_code);
		}
	}
	private function get_negara($array = NULL) {
		$negara 		= $this->dmastervendor->get_data_negara("open");
		if ($array) {
			return $negara;
		} else {
			echo json_encode($negara);
		}
	}
	private function get_term1($array = NULL) {
		$term1 		= $this->dmastervendor->get_data_term1("open");
		if ($array) {
			return $term1;
		} else {
			echo json_encode($term1);
		}
	}
	private function get_term2($array = NULL, $term1 = NULL) {
		$term2 		= $this->dmastervendor->get_data_term2("open", $term1);
		if ($array) {
			return $term2;
		} else {
			echo json_encode($term2);
		}
	}
	private function get_provinsi($array = NULL, $id_provinsi = NULL, $negara = NULL) {
		$provinsi 		= $this->dmastervendor->get_data_provinsi("open", $id_provinsi, $negara);
		if ($array) {
			return $provinsi;
		} else {
			echo json_encode($provinsi);
		}
	}
	private function get_industri($array = NULL) {
		$industri 		= $this->dmastervendor->get_data_industri("open");
		if ($array) {
			return $industri;
		} else {
			echo json_encode($industri);
		}
	}
	private function get_kriteria($array = NULL, $id_kriteria = NULL, $active = NULL, $deleted = NULL) {
		$kriteria 		= $this->dmastervendor->get_data_kriteria("open", $id_kriteria, $active, $deleted);
		$kriteria 		= $this->general->generate_encrypt_json($kriteria, array("id_kriteria"));
		if ($array) {
			return $kriteria;
		} else {
			echo json_encode($kriteria);
		}
	}
	
	private function get_vendor($array = NULL, $id_data = NULL, $active = NULL, $deleted = NULL, $action = NULL, $action2 = NULL) {
		$vendor	= $this->dtransaksivendor->get_data_vendor("open", $id_data, $active, $deleted);
		$vendor	= $this->general->generate_encrypt_json($vendor, array("id_data","id_jenis_vendor"));
		if(!empty($vendor)){
			if($action=='history'){
				$history				= $this->dtransaksivendor->get_data_history("array", $this->generate->kirana_decrypt($vendor[0]->id_data));
				$vendor[0]->arr_history = $history;

				$history_extend			= $this->dtransaksivendor->get_data_history_pengajuan("array", $this->generate->kirana_decrypt($vendor[0]->id_data),'extend');
				$vendor[0]->arr_history_extend = $history_extend;
				$history_change			= $this->dtransaksivendor->get_data_history_pengajuan("array", $this->generate->kirana_decrypt($vendor[0]->id_data),'change');
				$vendor[0]->arr_history_change = $history_change;
				$history_delete			= $this->dtransaksivendor->get_data_history_pengajuan("array", $this->generate->kirana_decrypt($vendor[0]->id_data),'delete');
				$vendor[0]->arr_history_delete = $history_delete;
				$history_undelete			= $this->dtransaksivendor->get_data_history_pengajuan("array", $this->generate->kirana_decrypt($vendor[0]->id_data),'undelete');
				$vendor[0]->arr_history_undelete = $history_undelete;
			}else{
				$provinsi		= $this->dmastervendor->get_data_provinsi("array",NULL, $vendor[0]->negara);
				// $vendor[0]->arr_provinsi	= json_decode($this->general->generate_json(array("data" => $provinsi)));
				$vendor[0]->arr_provinsi	= $provinsi;
				
				$nilai_detail	= $this->dtransaksivendor->get_data_nilai_detail("array",NULL,NULL,NULL, $this->generate->kirana_decrypt($vendor[0]->id_data));
				$vendor[0]->arr_nilai_detail= $nilai_detail;
				
				$plant_asis		= $this->dtransaksivendor->get_master_plant_asis("array", $this->generate->kirana_decrypt($vendor[0]->id_data),'y');
				$vendor[0]->arr_plant_asis 	= $plant_asis;
				
				$plant_edit		= $this->dtransaksivendor->get_master_plant_asis("array", $this->generate->kirana_decrypt($vendor[0]->id_data),'n');
				$vendor[0]->arr_plant_edit 	= $plant_edit;
				
				$plant_extend	= $this->dtransaksivendor->get_master_plant_extend($this->generate->kirana_decrypt($vendor[0]->id_data));
				$vendor[0]->arr_plant_extend 	= $plant_extend;
				
				// $jenis_vendor_dokumen	= $this->dtransaksivendor->get_data_jenis_vendor_dokumen("array", NULL, NULL, NULL, $this->generate->kirana_decrypt($vendor[0]->id_jenis_vendor), $this->generate->kirana_decrypt($vendor[0]->id_data));
				// $vendor[0]->arr_jenis_vendor_dokumen 	= $jenis_vendor_dokumen;
				
				// $kualifikasi_dokumen	= $this->dtransaksivendor->get_data_kualifikasi_dokumen("array", $vendor[0]->kualifikasi_spk, NULL, NULL, $this->generate->kirana_decrypt($vendor[0]->id_data));
				// $vendor[0]->arr_kualifikasi_dokumen 	= $kualifikasi_dokumen;				
				
				$term2			= $this->dmastervendor->get_data_term2("array", $vendor[0]->jenis_barang_jasa1);
				$vendor[0]->arr_term2 		= $term2;
				
				// if($action=='change'){
					// $vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp("array", $id_data, "change", "y");
					// $vendor[0]->arr_vendor_temp 	= $vendor_temp;
				// }	
				if($action=='delete'){
					$vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp("array", $id_data, "delete", "y");
					$vendor[0]->arr_vendor_temp 	= $vendor_temp;
				}else if($action=='undelete'){
					$vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp("array", $id_data, "undelete", "y");
					$vendor[0]->arr_vendor_temp 	= $vendor_temp;
				}else if($action=='extend'){
					$vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp("array", $id_data, "extend", "y");
					$vendor[0]->arr_vendor_temp 	= $vendor_temp;
				}else{
					$vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp("array", $id_data, "change", "y");
					$vendor_temp	= $this->general->generate_encrypt_json($vendor_temp, array("id_data","id_jenis_vendor"));					
					$vendor[0]->arr_vendor_temp 	= $vendor_temp;
				}	
				if(count($vendor_temp) != 0){
					$tax_code		= $this->dmastervendor->get_data_tax_code("array", $vendor_temp[0]->tax_type);
					$vendor[0]->arr_tax_code 	= $tax_code;
				}else{
					$tax_code		= $this->dmastervendor->get_data_tax_code("array", $vendor[0]->tax_type);
					$vendor[0]->arr_tax_code 	= $tax_code;
					
				}
				
				// if($vendor[0]->id_status_delete==4){
					// $plant_temp	= $this->dtransaksivendor->get_data_plant_temp("array", NULL, NULL, $id_data, NULL, 'n');
					// $vendor[0]->arr_plant_temp 	= $plant_temp;
				// }
				if(($vendor[0]->id_status_undelete==4)or($vendor[0]->id_status_undelete==5)){
					$plant_temp	= $this->dtransaksivendor->get_data_plant_temp("array", NULL, NULL, $id_data, NULL, 'y');
					$vendor[0]->arr_plant_temp 	= $plant_temp;
				}
				
			}
			
		}

		if ($array) {
			return $vendor;
		} else {
			echo json_encode($vendor);
		}
	}
	

	private function cek_sap($nomor=NULL, $array_terpakai = NULL) {
		// $this->connectSAP("ERP_310");            //310
		$this->connectSAP("ERP_KMTEMP");
		// $this->connectSAP("ERP");            //prod
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction("Z_RFC_CHECK_MATERIALCODE",
													   array(
															array("IMPORT", "I_MATNR", $nomor),
															array("EXPORT", "E_RETURN", array()),
													   )
			);
			// echo json_encode($result);
			if ($result['E_RETURN']['TYPE'] == 'E') {
				$array_terpakai[] = $nomor;
				$nomor = explode('-',$nomor);
				$no = $nomor[0]."-".$nomor[1]."-".str_pad($nomor[2]+1,4,0,STR_PAD_LEFT);
				$this->cek_sap($no, $array_terpakai);				
				//log rfc gagal
				$datetime     = date("Y-m-d H:i:s");
				$data_row = array(
					'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CHECK_MATERIALCODE',
					'log_code'      => $result['E_RETURN'][1]['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['E_RETURN'][1]['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
				
			}else{
				$result = array(
					"sts" => true,
					"nomor" => $nomor,
					"terpakai" => $array_terpakai
				);
				echo json_encode($result);
				//log rfc berhasil
				$datetime     = date("Y-m-d H:i:s");
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CHECK_MATERIALCODE',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				
			}
			// echo json_encode($result);
		}
		
	}
	
	private function save_vendor($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 	 		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$nama 				= (isset($_POST['nama']) ? strtoupper($_POST['nama']) : NULL);
		$id_jenis_vendor 	= (isset($_POST['id_jenis_vendor']) ? $this->generate->kirana_decrypt($_POST['id_jenis_vendor']) : NULL);
		$id_jenis_vendor_hide	= (isset($_POST['id_jenis_vendor_hide']) ? $this->generate->kirana_decrypt($_POST['id_jenis_vendor_hide']) : NULL);
		$kualifikasi_spk	= (isset($_POST['kualifikasi_spk']) ? implode(",", $_POST['kualifikasi_spk']) : NULL);
		$kualifikasi_spk_hide	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		$id_propinsi 		= (isset($_POST['id_propinsi']) ? $this->generate->kirana_decrypt($_POST['id_propinsi']) : NULL);
		$id_kabupaten 		= (isset($_POST['id_kabupaten']) ? $this->generate->kirana_decrypt($_POST['id_kabupaten']) : NULL);
		$tax_type			= (isset($_POST['tax_type']) ? $_POST['tax_type'] : NULL);
		$tax_code			= (isset($_POST['tax_code']) ? $_POST['tax_code'] : NULL);
		$pengajuan			= (isset($_POST['pengajuan']) ? $_POST['pengajuan'] : NULL);
		$provinsi			= (isset($_POST['provinsi']) ? $_POST['provinsi'] : NULL);
		$status_do			= (isset($_POST['status_do']) ? $_POST['status_do'] : NULL);
		
		// //untuk menentukan approval selanjutnya
		// $user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, base64_decode($this->session->userdata("-nik-")));
		// if($kualifikasi_spk==NULL){
			// $id_status			= ($user_role[0]->level==1) ? $user_role[0]->if_approve_create_pabrik : $user_role[0]->if_approve_create_ho;
		// }else{
			// $id_status			= ($user_role[0]->level==1) ? $user_role[0]->if_approve_create_pabrik : $user_role[0]->if_approve_create_legal_ho;
		// }
		// // echo json_encode($user_role);
		// // echo json_encode($id_status);
		// // exit();
		
		// $pengajuan_ho	= ($user_role[0]->level==1) ? 'n' : 'y';
		// if($user_role[0]->level==1){
			// $next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status, $user_role[0]->gsber);
		// }else{
			// $next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		// }
		
		// $user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, base64_decode($this->session->userdata("-nik-")));
		$pengajuan_ho	= ($user_role[0]->level==1) ? 'n' : 'y';
		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = ($kualifikasi_spk==NULL)? $user_role[0]->if_approve_create_ho : $user_role[0]->if_approve_create_legal_ho;
			$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		}else{
			$id_status = ($kualifikasi_spk==NULL)? $user_role[0]->if_approve_create_pabrik : $user_role[0]->if_approve_create_legal_pabrik;
			$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status, $user_role[0]->gsber);
		}
		
		
		// echo json_encode($next_user_role);
		// exit;

	
		$add_pilihan 			= (isset($_POST['add_pilihan']) ? "y": "n");
		$add_vendor_existing 	= (isset($_POST['add_vendor_existing']) ? $_POST['add_vendor_existing']: NULL);
		$add_alasan 			= (isset($_POST['add_alasan']) ? $_POST['add_alasan']: NULL);
		$add_vendor_flag 		= (isset($_POST['add_vendor_flag']) ? $_POST['add_vendor_flag']: NULL);
		//=============
		//update vendor	
		//=============
		if ($id_data!=NULL){	
			//update nilai
			// $data_kriteria 	= $this->get_kriteria('array');
			$data_kriteria 	= $this->dmastervendor->get_data_kriteria(NULL);
			foreach($data_kriteria as $dt2){
				// $id_kriteria 	= $this->generate->kirana_decrypt($dt2->id_kriteria);
				$id_kriteria 	= $dt2->id_kriteria;
				$id_nilai		= (isset($_POST['id_nilai_'.$id_kriteria]) ? $_POST['id_nilai_'.$id_kriteria] : NULL); 
				$data_nilai = array(
					"id_nilai"	 	=> $id_nilai,
				);
				$data_nilai = $this->dgeneral->basic_column("update", $data_nilai);
				$this->dgeneral->update("tbl_vendor_data_nilai", $data_nilai, array(
					array(
						'kolom' => 'id_data',
						'value' => $id_data
					),
					array(
						'kolom' => 'id_kriteria',
						'value' => $id_kriteria
					)
				));
			}
			//update data
			$data_row = array(
				"id_status"			=> $id_status,
				"id_jenis_vendor"	=> $id_jenis_vendor,
				"kualifikasi_spk"	=> $kualifikasi_spk,
				"plant"	 			=> $_POST['plant'],
				"title"	 			=> $_POST['title'],
				"nama"	 			=> $nama,
				"acc_group"	 		=> $_POST['acc_group'],
				"lang"	 			=> "EN",
				"jenis_barang_jasa1"=> $_POST['jenis_barang_jasa1'],
				"jenis_barang_jasa2"=> $_POST['jenis_barang_jasa2'],
				"nama_bank"	 		=> $_POST['nama_bank'],
				"nama_rekening"	 	=> $_POST['nama_rekening'],
				"nomor_rekening" 	=> $_POST['nomor_rekening'],
				// "payment" 			=> $_POST['payment'],
				"alamat"	 		=> $_POST['alamat'],
				"no"	 			=> $_POST['no'],
				"kode_pos"	 		=> $_POST['kode_pos'],
				"provinsi"		 	=> $provinsi,
				"kota"			 	=> $_POST['kota'],
				"negara"			=> $_POST['negara'],
				"time_zone"			=> 'UTC+7',
				"telepon"	 		=> $_POST['telepon'],
				"fax"	 			=> $_POST['fax'],
				"email"	 			=> $_POST['email'],
				"one_time_acc"	 	=> 'X',
				"npwp"	 			=> $_POST['npwp'],
				"ktp"	 			=> $_POST['ktp'],
				"industri"	 		=> $_POST['industri'],
				"dlgrp"	 			=> $_POST['dlgrp'],
				"akont"	 			=> $_POST['akont'],
				"zterm"	 			=> $_POST['zterm'],
				"reprf"	 			=> "X",
				"qland"	 			=> "ID",
				"tax_type"	 		=> $tax_type,
				"tax_code"	 		=> $tax_code,
				// "tax_code2"	 		=> $_POST['tax_code2'],
				"curr"	 			=> $_POST['curr'],
				"schema_grup"		=> "NB",
				"sales_person"		=> $_POST['sales_person'],
				"sales_phone"		=> $_POST['sales_phone'],
				"webre"	 			=> "X",
				// "status_pkp"	 	=> $_POST['status_pkp'],
				"status_do"	 		=> $status_do,
				"deletion_flag"	 	=> "X",
				//additional input
				"add_pilihan"	 		=> $add_pilihan,
				"add_vendor_existing"	=> $add_vendor_existing,
				"add_alasan"	 		=> $add_alasan,
				"add_vendor_flag"	 	=> $add_vendor_flag,
				"total_nilai"	 		=> $_POST['total_nilai'],
				"total_penilaian"	 	=> $_POST['total_penilaian'],
				"total_nilai_max"	 	=> $_POST['total_nilai_max'],
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data", $data_row, array(
				array(
					'kolom' => 'id_data',
					'value' => $id_data
				)
			));
			//update plant
			$data_plant = array(
				"plant"			=> $_POST['plant'],
			);
			$data_plant = $this->dgeneral->basic_column("update", $data_plant);
			$this->dgeneral->update("tbl_vendor_plant", $data_plant, array(
				array(
					'kolom' => 'id_data',
					'value' => $id_data
				)
			));
			
			
			//update file
			// //===========
			// if($id_jenis_vendor!=$id_jenis_vendor_hide){
				// //set del tbl_vendor_data_dokumen
				// $this->db->query("update tbl_vendor_data_dokumen set na='y', del='y' where id_data='$id_data' and tipe_referensi='jenis_vendor'");
				// //set del tbl_file
				// $this->db->query("update tbl_file set na='y', del='y' where id_file in(select id_file from tbl_vendor_data_dokumen where id_data='$id_data' and tipe_referensi='jenis_vendor')");
			// }
			
			$nama_folder	= $id_data." - ".strtoupper($nama);
			$ck_folder 		= $this->dmaster->get_data_folder(NULL, 553, $nama_folder);	//set untuk go-live berbeda xxx
			$id_folder		= (count($ck_folder) == 0)?$this->db->insert_id():$ck_folder[0]->id_folder;
			//dokumen jenis vendor
			$jenis_vendor 				= $this->dtransaksivendor->get_data_jenis_vendor_dokumen(NULL, NULL, NULL, NULL, $id_jenis_vendor);
			foreach($jenis_vendor as $dt2){
				$file_dokumen 			= 'file_dokumen_'.$dt2->id_master_dokumen;
				$nama_dokumen_hide		= 'nama_dokumen_hide_'.$dt2->id_master_dokumen;
				$tanggal_awal_jenis		= 'tanggal_awal_jenis_'.$dt2->id_master_dokumen;
				$tanggal_awal_jenis		= ($_POST[$tanggal_awal_jenis]!='') ? date_create($_POST[$tanggal_awal_jenis])->format('Y-m-d'): '1900-01-01';
				$tanggal_akhir_jenis	= 'tanggal_akhir_jenis_'.$dt2->id_master_dokumen;				
				$tanggal_akhir_jenis	= ($_POST[$tanggal_akhir_jenis]!='') ? date_create($_POST[$tanggal_akhir_jenis])->format('Y-m-d'): '9999-12-31';
				$nama_dokumen			= str_replace(' ','_',$dt2->nama_dokumen)." - $id_data $tanggal_awal_jenis sd $tanggal_akhir_jenis";				
				$nama_dokumen			= str_replace(' ','_',$nama_dokumen);
				$nama_dokumen			= strtolower($nama_dokumen);
				//upload file jenis vendor
				if($_FILES[$file_dokumen]['name'][0]!=''){
					$config['upload_path']   = str_replace('/vendor/','/folder/',$this->general->kirana_file_path($this->router->fetch_module()) . '/vendor');
					$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
					$config['max_size']      = 0;
					
					$newname	= array($nama_dokumen);			
					$file		= $this->general->upload_files($_FILES[$file_dokumen], $newname, $config);
					$nama_file	= str_replace('_',' ',$newname[0]);
					$url_file	= str_replace("assets/", "", $file[0]['url']);
					if($file === NULL){
						$msg        = "Upload files error";
						$sts        = "NotOK";
						$return     = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					//xx	
					$ck_file	= $this->dtransaksivendor->get_data_file(NULL, NULL, $id_folder, $nama_file);
					if (count($ck_file) == 0){
						//batch data file jenis
						$data_file_jenis = array(
							'id_folder'     	=> $id_folder,
							'nama'     		    => $nama_file,
							'ukuran'        	=> $file[0]['size'],
							'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
							'link'      		=> str_replace(' ','_',$url_file),
							'divisi_akses'    	=> NULL, 
							'departemen_akses'  => NULL,
							'divisi_write'    	=> NULL,
							'departemen_write'  => NULL,
							'level_akses'       => NULL,
							'level_write'       => NULL,
							'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'  	=> $datetime,
							'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'  	=> $datetime,
							'lihat'				=> 'y'  
						);
						$data_file_jenis = $this->dgeneral->basic_column("insert", $data_file_jenis);
						$this->dgeneral->insert("tbl_file", $data_file_jenis);
						$id_file	= $this->db->insert_id();
						
						//batch data dokumen jenis
						$data_dokumen_jenis = array(
							"id_data"				=> $id_data,
							"id_master_dokumen"		=> $dt2->id_master_dokumen,
							"id_referensi" 			=> 1,
							"tipe_referensi"		=> 'jenis_vendor',
							"id_folder" 			=> $id_folder,
							"id_file" 				=> $id_file,
							"tanggal_awal"		 	=> $tanggal_awal_jenis,
							"tanggal_akhir"		 	=> $tanggal_akhir_jenis,
							"status"			 	=> 1,
							"mandatory"			 	=> $dt2->mandatory,
						);
						$data_dokumen_jenis 		= $this->dgeneral->basic_column("insert", $data_dokumen_jenis);
						$this->dgeneral->insert("tbl_vendor_data_dokumen", $data_dokumen_jenis);					
					}else{
						//batch data file jenis
						$data_file_jenis = array(
							// 'id_folder'     	=> $id_folder,
							'nama'     		    => $nama_file,
							'ukuran'        	=> $file[0]['size'],
							'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
							'link'      		=> str_replace(' ','_',$url_file),
							'divisi_akses'    	=> NULL, 
							'departemen_akses'  => NULL,
							'divisi_write'    	=> NULL,
							'departemen_write'  => NULL,
							'level_akses'       => NULL,
							'level_write'       => NULL,
							'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'  	=> $datetime,
							'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'  	=> $datetime,
							'lihat'				=> 'y'  
						);
						$data_file_jenis = $this->dgeneral->basic_column("update", $data_file_jenis);
						$this->dgeneral->update("tbl_file", $data_file_jenis, array(
							array(
								'kolom' => 'id_file',
								'value' => $ck_file[0]->id_file
							)
						));
						$this->dgeneral->insert("tbl_file", $data_file_jenis);
						$id_file	= $ck_file[0]->id_file;

						//batch data dokumen jenis
						$data_dokumen_jenis = array(
							"id_data"				=> $id_data,
							"id_master_dokumen"		=> $dt2->id_master_dokumen,
							"id_referensi" 			=> 1,
							"tipe_referensi"		=> 'jenis_vendor',
							"id_folder" 			=> $id_folder,
							"id_file" 				=> $id_file,
							"tanggal_awal"		 	=> $tanggal_awal_jenis,
							"tanggal_akhir"		 	=> $tanggal_akhir_jenis,
							"status"			 	=> 1,
							"mandatory"			 	=> $dt2->mandatory,
						);
						$data_dokumen_jenis = $this->dgeneral->basic_column("update", $data_dokumen_jenis);
						$this->dgeneral->update("tbl_vendor_data_dokumen", $data_dokumen_jenis, array(
							array(
								'kolom' => 'id_file',
								'value' => $ck_file[0]->id_file
							)
						));
						
					}
					
				}
			}
			
			//save folder, file dan dokumen dari kualifikasi vendor
			if(isset($_POST['kualifikasi_spk'])){
				$id_kualifikasi_spk	= array();
				foreach ($_POST['kualifikasi_spk'] as $dt) {
					array_push($id_kualifikasi_spk, $dt);
				}
				$data_dokumen_kualifikasi	= array();
				$kualifikasi_vendor			= $this->dtransaksivendor->get_data_kualifikasi_dokumen(NULL, $id_kualifikasi_spk);
				foreach($kualifikasi_vendor as $dt2){
					$file_dokumen 	= 'file_dokumen_'.$dt2->id_master_dokumen;
					$nama_dokumen_hide = 'nama_dokumen_hide_'.$dt2->id_master_dokumen;
					// $nama_dokumen	= str_replace(' ','_',$dt2->nama_dokumen).' - '.$id_data.' ('.date('d-m-Y').')';
					// $nama_dokumen	= str_replace(' ','_',$dt2->nama_dokumen).' - '.$id_data;
					$tanggal_awal_kualifikasi	= 'tanggal_awal_kualifikasi_'.$dt2->id_master_dokumen;
					$tanggal_awal_kualifikasi	= ($_POST[$tanggal_awal_kualifikasi]!='') ? date_create($_POST[$tanggal_awal_kualifikasi])->format('Y-m-d'): '1900-01-01';
					$tanggal_akhir_kualifikasi	= 'tanggal_akhir_kualifikasi_'.$dt2->id_master_dokumen;				
					$tanggal_akhir_kualifikasi	= ($_POST[$tanggal_akhir_kualifikasi]!='') ? date_create($_POST[$tanggal_akhir_kualifikasi])->format('Y-m-d'): '9999-12-31';
					$nama_dokumen				= str_replace(' ','_',$dt2->nama_dokumen)." - $id_data $tanggal_awal_kualifikasi sd $tanggal_akhir_kualifikasi";				
					$nama_dokumen				= str_replace(' ','_',$nama_dokumen);
					//upload file kualifikasi vendor
					if($_FILES[$file_dokumen]['name'][0]!=''){
						$config['upload_path']   = str_replace('/vendor/','/folder/',$this->general->kirana_file_path($this->router->fetch_module()) . '/vendor');
						$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
						$config['max_size']      = 0;
						
						$newname	= array($nama_dokumen);			
						$file		= $this->general->upload_files($_FILES[$file_dokumen], $newname, $config);
						$nama_file	= str_replace('_',' ',$newname[0]);
						$url_file	= str_replace("assets/", "", $file[0]['url']);
						if($file === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
						
						$ck_file	= $this->dtransaksivendor->get_data_file(NULL, NULL, $id_folder, $nama_file);
						if (count($ck_file) == 0){
							//batch data file kualifikasi
							$data_file_jenis = array(
								'id_folder'     	=> $id_folder,
								'nama'     		    => $nama_file,
								'ukuran'        	=> $file[0]['size'],
								'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
								'link'      		=> str_replace(' ','_',$url_file),
								'divisi_akses'    	=> NULL, 
								'departemen_akses'  => NULL,
								'divisi_write'    	=> NULL,
								'departemen_write'  => NULL,
								'level_akses'       => NULL,
								'level_write'       => NULL,
								'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'  	=> $datetime,
								'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'  	=> $datetime,
								'lihat'				=> 'y'  
							);
							$data_file_jenis = $this->dgeneral->basic_column("insert", $data_file_jenis);
							$this->dgeneral->insert("tbl_file", $data_file_jenis);
							$id_file	= $this->db->insert_id();

							//batch data dokumen kualifikasi
							$data_dokumen_kualifikasi = array(
								"id_data"				=> $id_data,
								"id_master_dokumen"		=> $dt2->id_master_dokumen,
								"id_referensi" 			=> 1,
								"tipe_referensi"		=> 'kualifikasi_spk',
								"id_folder" 			=> $id_folder,
								"id_file" 				=> $id_file,
								"tanggal_awal"		 	=> $tanggal_awal_kualifikasi,
								"tanggal_akhir"		 	=> $tanggal_akhir_kualifikasi,
								"status"			 	=> 1,
								"mandatory"			 	=> 'Mandatory',
							);
							$data_dokumen_kualifikasi 		= $this->dgeneral->basic_column("insert", $data_dokumen_kualifikasi);
							$this->dgeneral->insert("tbl_vendor_data_dokumen", $data_dokumen_kualifikasi);							
						}else{
							//batch data file kualifikasi
							$data_file_jenis = array(
								// 'id_folder'     	=> $id_folder,
								'nama'     		    => $nama_file,
								'ukuran'        	=> $file[0]['size'],
								'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
								'link'      		=> str_replace(' ','_',$url_file),
								'divisi_akses'    	=> NULL, 
								'departemen_akses'  => NULL,
								'divisi_write'    	=> NULL,
								'departemen_write'  => NULL,
								'level_akses'       => NULL,
								'level_write'       => NULL,
								'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'  	=> $datetime,
								'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'  	=> $datetime,
								'lihat'				=> 'y'  
							);
							$data_file_jenis = $this->dgeneral->basic_column("update", $data_file_jenis);
							$this->dgeneral->update("tbl_file", $data_file_jenis, array(
								array(
									'kolom' => 'id_file',
									'value' => $ck_file[0]->id_file
								)
							));
							$this->dgeneral->insert("tbl_file", $data_file_jenis);
							$id_file	= $ck_file[0]->id_file;
							
							//batch data dokumen kualifikasi
							$data_dokumen_kualifikasi = array(
								"id_data"				=> $id_data,
								"id_master_dokumen"		=> $dt2->id_master_dokumen,
								"id_referensi" 			=> 1,
								"tipe_referensi"		=> 'kualifikasi_spk',
								"id_folder" 			=> $id_folder,
								"id_file" 				=> $id_file,
								"tanggal_awal"		 	=> $tanggal_awal_kualifikasi,
								"tanggal_akhir"		 	=> $tanggal_akhir_kualifikasi,
								"status"			 	=> 1,
								"mandatory"			 	=> 'Mandatory',
							);
							$data_dokumen_kualifikasi = $this->dgeneral->basic_column("update", $data_dokumen_kualifikasi);
							$this->dgeneral->update("tbl_vendor_data_dokumen", $data_dokumen_kualifikasi, array(
								array(
									'kolom' => 'id_file',
									'value' => $ck_file[0]->id_file
								)
							));
							
						}
					}
				}
			}
			
			if($pengajuan=='ulang'){
				//save log 
				$data_row_log = array(
					"act"		=> 'create',
					"id_data"	=> $id_data,
					"id_status"	=> $user_role[0]->level
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_vendor_data_log", $data_row_log);
				
				//send email ulang
				$content = '
					<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
						<thead>
							<tr>
								<th align="left" width="20%">Tanggal Pengajuan</th>
								<th align="left">: '.date('d.m.Y').'</th>
							</tr>
							<tr>
								<th align="left" width="20%">Pabrik Pengaju</th>
								<th align="left">: '.$_POST["plant"].'</th>
							</tr>
							<tr>
								<th align="left" width="20%">Nama Vendor</th>
								<th align="left">: '.$nama.'</th>
							</tr>
							<tr>
								<th align="left" width="20%">Jenis Pengajuan</th>
								<th align="left">: Create Vendor</th>
							</tr>
							<tr>
								<th align="left" width="20%">Status Pengajuan</th>
								<th align="left">: Menunggu Approval '.$next_user_role[0]->nama_role.'</th>
							</tr>
						</thead>
					</table>';	
				$subject = "Pengajuan Master Vendor (".$nama.")";	
				foreach($next_user_role as $dt){
					$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
					// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
				}
				
				// $this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $next_user_role[0]->nama_karyawan);
				// // $this->template_email_vendor($next_user_role[0]->email_karyawan,$subject,$content, $next_user_role[0]->nama_karyawan);
				
			}
			
		//=============
		//insert vendor	
		//=============
		}else{
			
			//save nilai
			// $data_nilai 	= array();
			// $data_kriteria 	= $this->get_kriteria('array');
			$data_kriteria 	= $this->dmastervendor->get_data_kriteria(NULL);
			foreach($data_kriteria as $dt2){
				// $id_kriteria 	= $this->generate->kirana_decrypt($dt2->id_kriteria);
				$id_kriteria 	= $dt2->id_kriteria;
				$id_nilai		= (isset($_POST['id_nilai_'.$id_kriteria]) ? $_POST['id_nilai_'.$id_kriteria] : NULL); 
				$data_nilai = array(
					// "id_data"		=> $id_data,
					"id_data"		=> 0,
					"id_kriteria" 	=> $id_kriteria,
					"id_nilai"	 	=> $id_nilai
				);
				$data_nilai = $this->dgeneral->basic_column("insert", $data_nilai);
				$data_nilai 		= $this->dgeneral->basic_column("insert", $data_nilai);
				$this->dgeneral->insert("tbl_vendor_data_nilai", $data_nilai);
				// $data_batch_nilai[] = $data_nilai; 	
				
			}
			// $this->dgeneral->insert_batch("tbl_vendor_data_nilai", $data_batch_nilai);
			
			//save data
			$data_row = array(
				"id_status"			=> $id_status,
				"id_jenis_vendor"	=> $id_jenis_vendor,
				"kualifikasi_spk"	=> $kualifikasi_spk,
				"plant"	 			=> $_POST['plant'],
				"title"	 			=> $_POST['title'],
				"nama"	 			=> $nama,
				"acc_group"	 		=> $_POST['acc_group'],
				"lang"	 			=> "EN",
				"jenis_barang_jasa1"=> $_POST['jenis_barang_jasa1'],
				"jenis_barang_jasa2"=> $_POST['jenis_barang_jasa2'],
				"nama_bank"	 		=> $_POST['nama_bank'],
				"nama_rekening"	 	=> $_POST['nama_rekening'],
				"nomor_rekening" 	=> $_POST['nomor_rekening'],
				// "payment" 			=> $_POST['payment'],
				"alamat"	 		=> $_POST['alamat'],
				"no"	 			=> $_POST['no'],
				"kode_pos"	 		=> $_POST['kode_pos'],
				"provinsi"		 	=> $provinsi,
				"kota"			 	=> $_POST['kota'],
				"negara"			=> $_POST['negara'],
				"time_zone"			=> 'UTC+7',
				"telepon"	 		=> $_POST['telepon'],
				"fax"	 			=> $_POST['fax'],
				"email"	 			=> $_POST['email'],
				"one_time_acc"	 	=> 'X',
				"npwp"	 			=> $_POST['npwp'],
				"ktp"	 			=> $_POST['ktp'],
				"industri"	 		=> $_POST['industri'],
				"dlgrp"	 			=> $_POST['dlgrp'],
				"akont"	 			=> $_POST['akont'],
				"zterm"	 			=> $_POST['zterm'],
				"reprf"	 			=> "X",
				"qland"	 			=> "ID",
				"tax_type"	 		=> $tax_type,
				"tax_code"	 		=> $tax_code,
				// "tax_code2"	 		=> $_POST['tax_code2'],
				"curr"	 			=> $_POST['curr'],
				"schema_grup"		=> "NB",
				"sales_person"		=> $_POST['sales_person'],
				"sales_phone"		=> $_POST['sales_phone'],
				"webre"	 			=> "X",
				// "status_pkp"	 	=> $_POST['status_pkp'],
				"status_do"	 		=> $status_do,
				"deletion_flag"	 	=> "X",
				"req"	 			=> 'y',
				"pengajuan_ho"		=> $pengajuan_ho,
				//additional input
				"add_pilihan"	 		=> $add_pilihan,
				"add_vendor_existing"	=> $add_vendor_existing,
				"add_alasan"	 		=> $add_alasan,
				"add_vendor_flag"	 	=> $add_vendor_flag,
				"total_nilai"	 		=> $_POST['total_nilai'],
				"total_penilaian"	 	=> $_POST['total_penilaian'],
				"total_nilai_max"	 	=> $_POST['total_nilai_max'],
				"extend"				=> 'n'
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_vendor_data", $data_row);
			$id_data	= $this->db->insert_id();
			
			//update tbl_vendor_data_nilai(update id_data)
			$this->db->query("update tbl_vendor_data_nilai set id_data='$id_data' where id_data='0' and login_buat='".base64_decode($this->session->userdata("-id_user-"))."'");

			//save plant
			$data_plant = array(
				"id_data"		=> $id_data,
				"plant"			=> $_POST['plant'],
				"status_sap"	=> 'n',
				"status_delete"	=> 'n'
			);
			$data_plant = $this->dgeneral->basic_column("insert", $data_plant);
			$this->dgeneral->insert("tbl_vendor_plant", $data_plant);

			//create folder
			$nama_folder		= $id_data." - ".strtoupper($nama);
			$ck_folder 	= $this->dmaster->get_data_folder(NULL, 553, $nama_folder);	//set untuk go-live berbeda
			if (count($ck_folder) == 0){ 
				$data_row = array(
					"parent_folder"  => 553,	//set untuk go-live berbeda xxx
					"nama"			 => $nama_folder,
				);
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_folder", $data_row);
			}
			$id_folder	= (count($ck_folder) == 0)?$this->db->insert_id():$ck_folder[0]->id_folder;
			
			//save folder, file dan dokumen dari jenis vendor
			$data_batch_dokumen_jenis 	= array();
			$jenis_vendor 				= $this->dtransaksivendor->get_data_jenis_vendor_dokumen(NULL, NULL, NULL, NULL, $id_jenis_vendor);
			
			$test = array();
			foreach($jenis_vendor as $dt2){
				$file_dokumen 			= 'file_dokumen_'.$dt2->id_master_dokumen;
				$tanggal_awal_jenis		= 'tanggal_awal_jenis_'.$dt2->id_master_dokumen;
				$tanggal_awal_jenis		= ($_POST[$tanggal_awal_jenis]!='') ? date_create($_POST[$tanggal_awal_jenis])->format('Y-m-d'): '1900-01-01';
				$tanggal_akhir_jenis	= 'tanggal_akhir_jenis_'.$dt2->id_master_dokumen;				
				$tanggal_akhir_jenis	= ($_POST[$tanggal_akhir_jenis]!='') ? date_create($_POST[$tanggal_akhir_jenis])->format('Y-m-d'): '9999-12-31';
				$nama_dokumen			= str_replace(' ','_',$dt2->nama_dokumen)." - $id_data $tanggal_awal_jenis sd $tanggal_akhir_jenis";				
				$nama_dokumen			= str_replace(' ','_',$nama_dokumen);
				$nama_dokumen			= strtolower($nama_dokumen);
				$test[] = $_FILES[$file_dokumen];
				//upload file jenis vendor
				if($_FILES[$file_dokumen]['name'][0]!=''){
					$config['upload_path']   = str_replace('/vendor/','/folder/',$this->general->kirana_file_path($this->router->fetch_module()) . '/vendor');
					$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
					$config['max_size']      = 0;
					
					$newname	= array($nama_dokumen);			
					$file		= $this->general->upload_files($_FILES[$file_dokumen], $newname, $config);
					$nama_file	= str_replace('_',' ',$newname[0]);
					$url_file	= str_replace("assets/", "", $file[0]['url']);
					if($file === NULL){
						$msg        = "Upload files error";
						$sts        = "NotOK";
						$return     = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					//batch data file jenis
					$data_file_jenis = array(
						'id_folder'     	=> $id_folder,
						'nama'     		    => $nama_file,
						'ukuran'        	=> $file[0]['size'],
						'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
						'link'      		=> str_replace(' ','_',$url_file),
						'divisi_akses'    	=> NULL, 
						'departemen_akses'  => NULL,
						'divisi_write'    	=> NULL,
						'departemen_write'  => NULL,
						'level_akses'       => NULL,
						'level_write'       => NULL,
						'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'  	=> $datetime,
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime,
						'lihat'				=> 'y'  
					);
					$data_file_jenis = $this->dgeneral->basic_column("insert", $data_file_jenis);
					$this->dgeneral->insert("tbl_file", $data_file_jenis);
					$id_file	= $this->db->insert_id();
					
					//set na
					// $this->db->query("update tbl_vendor_data_dokumen set na='y', del='y' where id_file in (select tbl_file.id_file from tbl_file where tbl_file.nama='".$_POST[$nama_dokumen_hide]."')");					
					//batch data dokumen jenis
					$data_dokumen_jenis = array(
						"id_data"				=> $id_data,
						"id_master_dokumen"		=> $dt2->id_master_dokumen,
						"id_referensi" 			=> 1,
						"tipe_referensi"		=> 'jenis_vendor',
						"id_folder" 			=> $id_folder,
						"id_file" 				=> $id_file,
						"tanggal_awal"		 	=> $tanggal_awal_jenis,
						"tanggal_akhir"		 	=> $tanggal_akhir_jenis,
						"status"			 	=> 1,
						"mandatory"			 	=> $dt2->mandatory,
					);
					$data_dokumen_jenis 		= $this->dgeneral->basic_column("insert", $data_dokumen_jenis);
					$this->dgeneral->insert("tbl_vendor_data_dokumen", $data_dokumen_jenis);					
				}
			}

			//save folder, file dan dokumen dari kualifikasi vendor
			if(isset($_POST['kualifikasi_spk'])){
				$id_kualifikasi_spk	= array();
				foreach ($_POST['kualifikasi_spk'] as $dt) {
					array_push($id_kualifikasi_spk, $dt);
				}
				$data_dokumen_kualifikasi	= array();
				$kualifikasi_vendor			= $this->dtransaksivendor->get_data_kualifikasi_dokumen(NULL, $id_kualifikasi_spk);
				foreach($kualifikasi_vendor as $dt2){
					$file_dokumen 				= 'file_dokumen_'.$dt2->id_master_dokumen;
					$nama_dokumen_hide 			= 'nama_dokumen_hide_'.$dt2->id_master_dokumen;
					$tanggal_awal_kualifikasi	= 'tanggal_awal_kualifikasi_'.$dt2->id_master_dokumen;
					$tanggal_awal_kualifikasi	= ($_POST[$tanggal_awal_kualifikasi]!='') ? date_create($_POST[$tanggal_awal_kualifikasi])->format('Y-m-d'): '1900-01-01';
					$tanggal_akhir_kualifikasi	= 'tanggal_akhir_kualifikasi_'.$dt2->id_master_dokumen;				
					$tanggal_akhir_kualifikasi	= ($_POST[$tanggal_akhir_kualifikasi]!='') ? date_create($_POST[$tanggal_akhir_kualifikasi])->format('Y-m-d'): '9999-12-31';
					$nama_dokumen				= str_replace(' ','_',$dt2->nama_dokumen)." - $id_data $tanggal_awal_kualifikasi sd $tanggal_akhir_kualifikasi";				
					$nama_dokumen				= str_replace(' ','_',$nama_dokumen);
					//upload file kualifikasi vendor
					if($_FILES[$file_dokumen]['name'][0]!=''){
						$config['upload_path']   = str_replace('/vendor/','/folder/',$this->general->kirana_file_path($this->router->fetch_module()) . '/vendor');
						$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
						$config['max_size']      = 0;
						
						$newname	= array($nama_dokumen);			
						$file		= $this->general->upload_files($_FILES[$file_dokumen], $newname, $config);
						$nama_file	= str_replace('_',' ',$newname[0]);
						$url_file	= str_replace("assets/", "", $file[0]['url']);
						if($file === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
						//set na
						$this->db->query("update tbl_file set na='y', del='y' where nama='".$_POST[$nama_dokumen_hide]."'");					
						//batch data file kualifikasi
						$data_file_jenis = array(
							'id_folder'     	=> $id_folder,
							'nama'     		    => $nama_file,
							'ukuran'        	=> $file[0]['size'],
							'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
							'link'      		=> str_replace(' ','_',$url_file),
							'divisi_akses'    	=> NULL, 
							'departemen_akses'  => NULL,
							'divisi_write'    	=> NULL,
							'departemen_write'  => NULL,
							'level_akses'       => NULL,
							'level_write'       => NULL,
							'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'  	=> $datetime,
							'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'  	=> $datetime,
							'lihat'				=> 'y'  
						);
						$data_file_jenis = $this->dgeneral->basic_column("insert", $data_file_jenis);
						$this->dgeneral->insert("tbl_file", $data_file_jenis);
						$id_file	= $this->db->insert_id();

						//batch data dokumen kualifikasi
						$data_dokumen_kualifikasi = array(
							"id_data"				=> $id_data,
							"id_master_dokumen"		=> $dt2->id_master_dokumen,
							"id_referensi" 			=> 1,
							"tipe_referensi"		=> 'kualifikasi_spk',
							"id_folder" 			=> $id_folder,
							"id_file" 				=> $id_file,
							"tanggal_awal"		 	=> $tanggal_awal_kualifikasi,
							"tanggal_akhir"		 	=> $tanggal_akhir_kualifikasi,
							"status"			 	=> 1,
							// "mandatory"			 	=> $dt2->mandatory,
						);
						$data_dokumen_kualifikasi 		= $this->dgeneral->basic_column("insert", $data_dokumen_kualifikasi);
						$this->dgeneral->insert("tbl_vendor_data_dokumen", $data_dokumen_kualifikasi);							
					}
				}
			}
			
			
			//save log 
			$data_row_log = array(
				"act"		=> 'create',
				"id_data"	=> $id_data,
				"id_status"	=> $user_role[0]->level
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_log", $data_row_log);
			
			//send email
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y').'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Pabrik Pengaju</th>
							<th align="left">: '.$_POST["plant"].'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Create Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: Menunggu Approval '.$next_user_role[0]->nama_role.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Pengajuan Master Vendor (".$nama.")";	
			foreach($next_user_role as $dt){
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
				// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
			}
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$this->dsettingfolder->update_folder_path(null);
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	private function save_approve($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		  		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$kualifikasi_spk_hide 	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		$pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$id_status 				= (isset($_POST['id_status']) ? $_POST['id_status'] : NULL);
		$nama 					= (isset($_POST['nama_hide']) ? $_POST['nama_hide'] : NULL);
		$user_role				= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		$komentar 				= (isset($_POST['komentar']) ? $_POST['komentar'] : NULL);
		// $pengajuan_ho	= ($user_role[0]->level==1) ? 'n' : 'y';
		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = ($kualifikasi_spk_hide==NULL)? $user_role[0]->if_approve_create_ho : $user_role[0]->if_approve_create_legal_ho;
			$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		}else{
			$id_status = ($kualifikasi_spk_hide==NULL)? $user_role[0]->if_approve_create_pabrik : $user_role[0]->if_approve_create_legal_pabrik;
			$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status, $user_role[0]->gsber);
		}
		
		// if($pengajuan_ho!='y'){
			// $next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status, $user_role[0]->gsber);
		// }else{
			// $next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		// }
		
		if ($id_data!=NULL){	
			//update data
			$data_row = array(
				"id_status"	 	=> $id_status,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data", $data_row, array(
				array(
					'kolom' => 'id_data',
					'value' => $id_data
				)
			));
			//save log jika status completed log diset di RFC
			if($id_status!=99){
				$data_row_log = array(
					"act"		=> 'approve',
					"id_data"	=> $id_data,
					"id_status"	=> $user_role[0]->level,
					"komentar"	=> $komentar,
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_vendor_data_log", $data_row_log);
			}
			
			//send email
			$data_vendor	  = $this->dtransaksivendor->get_data_vendor(NULL, $id_data);
			$status_pengajuan = ($id_status!=99)? 'Menunggu Approval '.$next_user_role[0]->nama_role:'Completed';
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y',strtotime($data_vendor[0]->tanggal_buat)).'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Pabrik Pengaju</th>
							<th align="left">: '.$data_vendor[0]->plant.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Create Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: '.$status_pengajuan.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Pengajuan Master Vendor (".$nama.")";	
			
			if($id_status!=99){
				foreach($next_user_role as $dt){
					$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
					// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
				}
			}else{
				$email_pengaju = $this->dtransaksivendor->get_data_karyawan(NULL, $data_vendor[0]->login_buat);
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
				// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama);
			}
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
	
	private function save_approve_extend($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		  		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$id_data_temp	  		= (isset($_POST['id_data']) ? $_POST['id_data_temp'] : NULL);
		$kualifikasi_spk_hide 	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		// $pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$id_status_awal 				= (isset($_POST['level']) ? $_POST['level'] : NULL);
		$komentar	  			= (isset($_POST['komentar_extend']) ? $_POST['komentar_extend'] : NULL);
		
		$user_role				= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status_awal);
		$pengajuan_ho	= ($user_role[0]->level==1) ? 'n' : 'y';
		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = $user_role[0]->if_approve_extend_ho;
		}else{
			$id_status = $user_role[0]->if_approve_extend_pabrik;
		}
		$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		
		if ($id_data!=NULL){	
			//update data
			$data_row = array(
				"id_status"	 	=> $id_status,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				),
				array(
					'kolom' => 'id_data',
					'value' => $id_data
				),
				array(
					'kolom' => 'jenis_pengajuan',
					'value' => 'extend'
				)
			));
			//save log extend
			$data_row_log = array(
				"act"		=> 'approve',
				"komentar"		=> $komentar,
				"id_data_temp"	=> $id_data_temp,
				"id_status"		=> $id_status
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			
			//send email
			$data_vendor	= $this->dtransaksivendor->get_data_vendor(NULL, $id_data);
			$nama_vendor	= (isset($_POST['nama']) ? strtoupper($_POST['nama']) : NULL);
			$plant_extend	= (isset($_POST['plant_extend_hide']) ? substr($_POST['plant_extend_hide'], 0, -1) : NULL);
			$status_pengajuan = ($id_status!=99)? 'Menunggu Approval '.$next_user_role[0]->nama_role:'Completed';
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y',strtotime($data_vendor[0]->tanggal_buat)).'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama_vendor.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Extend Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Pabrik Extend</th>
							<th align="left">: '.$plant_extend.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: '.$status_pengajuan.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Extend Master Vendor (".$nama_vendor.")";	
			// exit();	
			if($id_status!=99){
				foreach($next_user_role as $dt){
					$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
					// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
				}
			}else{
				$data_temp 		= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);	
				$email_pengaju 	= $this->dtransaksivendor->get_data_karyawan(NULL, $data_temp[0]->login_buat);
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
				// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama);
			}
			

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
	
	private function save_approve_change($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		  		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$id_data_temp	  		= (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
		$kualifikasi_spk_hide 	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		$pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$id_status 				= (isset($_POST['level']) ? $_POST['level'] : NULL);
		$perubahan_data			= ($_POST['perubahan_data']=='y') ? 'y' : 'n';
		$approval_legal			= ($_POST['approval_legal']=='y') ? 'y' : 'n';
		$approval_proc			= ($_POST['approval_proc']=='y') ? 'y' : 'n';
		// echo json_encode($id_data_temp);
		// exit();
		$data_temp 				= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);	
		$user_role				= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);

		if($data_temp[0]->pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = ($data_temp[0]->approval_legal=='y')?$user_role[0]->if_approve_change_legal_ho:$user_role[0]->if_approve_change_ho;
		}else{
			if($data_temp[0]->approval_legal=='y'){
				$id_status = $user_role[0]->if_approve_change_legal_pabrik;
			}else{
				$id_status = 99;
			}
		}
		$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		if ($id_data!=NULL){	
			if($id_status == 99){
				//update data header
				$data_header = array(
					"id_jenis_vendor"	=> $data_temp[0]->id_jenis_vendor,
					"kualifikasi_spk"	=> $data_temp[0]->kualifikasi_spk,
				);
				$data_header = $this->dgeneral->basic_column("update", $data_header);
				$this->dgeneral->update("tbl_vendor_data", $data_header, array(
					array(
						'kolom' => 'id_data',
						'value' => $id_data
					)
				));
				//update data temp	
				$data_row = array(
					"id_status"	=> $id_status,
					"req"	 	=> 'n',
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
					array(
						'kolom' => 'id_data_temp',
						'value' => $id_data_temp
					),
					array(
						'kolom' => 'id_data',
						'value' => $id_data
					),
					array(
						'kolom' => 'jenis_pengajuan',
						'value' => 'change'
					)
				));
				// //save log change jika ada perubahan dokumen, jika perubahan data dari RFC
				// if(($data_temp[0]->approval_proc=='y')or($data_temp[0]->approval_legal=='y')){
					// $data_row_log = array(
						// "act"		=> 'approve',
						// "id_data_temp"	=> $id_data_temp,
						// "id_status"	=> $user_role[0]->level
					// );
					// $data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
					// $this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
				// }
				//pindahkan file_temp dan dokumen_temp ke asli
				$file_temp = $this->dtransaksivendor->get_data_file_temp(NULL, NULL, NULL, NULL, $id_data_temp);	
				foreach($file_temp as $dt){
					// copy('assets/file/folder/vendor_temp/NPWP_-_7841_2022-09-29_sd_2022-09-29.pdf', 'assets/file/folder/vendor/NPWP_-_7841_2022-09-29_sd_2022-09-29.pdf');
					copy('assets/'.$dt->link, 'assets/'.str_replace('vendor_temp','vendor',$dt->link));
					unlink('assets/'.$dt->link);
					//batch data file jenis
					$data_file_jenis = array(
						'id_folder'     	=> $dt->id_folder,
						'nama'     		    => $dt->nama,
						'ukuran'        	=> $dt->ukuran,
						'tipe'          	=> $dt->tipe,
						// 'link'      		=> str_replace(' ','_',$dt->link),
						'link'      		=> str_replace('vendor_temp','vendor',$dt->link),
						'divisi_akses'    	=> NULL, 
						'departemen_akses'  => NULL,
						'divisi_write'    	=> NULL,
						'departemen_write'  => NULL,
						'level_akses'       => NULL,
						'level_write'       => NULL,
						'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'  	=> $datetime,
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime,
						'lihat'				=> 'y'  
					);
					$data_file_jenis = $this->dgeneral->basic_column("insert", $data_file_jenis);
					$this->dgeneral->insert("tbl_file", $data_file_jenis);
					$id_file	= $this->db->insert_id();
					
					//set tbl_vendor_data_dokumen na='y'
					$doc_temp = $this->dtransaksivendor->get_data_doc_temp(NULL, $dt->id_file);	
					$data_dokumen_update = $this->dgeneral->basic_column('delete', NULL, $datetime);
					$this->dgeneral->update("tbl_vendor_data_dokumen", $data_dokumen_update, array(
						array(
							'kolom' => 'id_data',
							'value' => $doc_temp[0]->id_data
						),
						array(
							'kolom' => 'id_master_dokumen',
							'value' => $doc_temp[0]->id_master_dokumen
						),
						array(
							'kolom' => 'na',
							'value' => 'n'
						)
					));
					//batch data dokumen jenis
					$data_dokumen_jenis = array(
						"id_data"				=> $doc_temp[0]->id_data,
						"id_data_temp"			=> $doc_temp[0]->id_data_temp,
						"id_master_dokumen"		=> $doc_temp[0]->id_master_dokumen,
						"id_referensi" 			=> 1,
						"tipe_referensi"		=> $doc_temp[0]->tipe_referensi,
						"id_folder" 			=> $doc_temp[0]->id_folder,
						"id_file" 				=> $id_file,
						"tanggal_awal"		 	=> $doc_temp[0]->tanggal_awal,
						"tanggal_akhir"		 	=> $doc_temp[0]->tanggal_akhir,
						"status"			 	=> 1,
						"mandatory"			 	=> $doc_temp[0]->mandatory,
					);
					$data_dokumen_jenis 		= $this->dgeneral->basic_column("insert", $data_dokumen_jenis);
					$this->dgeneral->insert("tbl_vendor_data_dokumen", $data_dokumen_jenis);					
				}
			}else{
				$data_row = array(
					"id_status"	 	=> $id_status,
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
					array(
						'kolom' => 'id_data_temp',
						'value' => $id_data_temp
					),
					array(
						'kolom' => 'id_data',
						'value' => $id_data
					),
					array(
						'kolom' => 'jenis_pengajuan',
						'value' => 'change'
					)
				));
				//save log change
				$data_row_log = array(
					"act"		=> 'approve',
					"id_data_temp"	=> $id_data_temp,
					"id_status"	=> $user_role[0]->level
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			}
			
			//send email
			$nama_vendor	= $data_temp[0]->nama;
			$plant_undelete	= (isset($_POST['plant_undelete_hide']) ? substr($_POST['plant_undelete_hide'], 0, -1) : NULL);
			$status_pengajuan = ($id_status!=99)? 'Menunggu Approval '.$next_user_role[0]->nama_role:'Completed';
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y',strtotime($data_temp[0]->tanggal_buat)).'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama_vendor.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Change Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: '.$status_pengajuan.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Change Master Vendor (".$nama_vendor.")";	
			// exit();	
			if($id_status!=99){
				foreach($next_user_role as $dt){
					$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
					// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
				}
			}else{
				$data_temp 		= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);	
				$email_pengaju 	= $this->dtransaksivendor->get_data_karyawan(NULL, $data_temp[0]->login_buat);
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
				// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama_karyawan);
			}
			


		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$this->dsettingfolder->update_folder_path(null);
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	private function save_approve_undelete($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		  		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$id_data_temp	  		= (isset($_POST['id_data']) ? $_POST['id_data_temp'] : NULL);
		$kualifikasi_spk_hide 	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		// $pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$id_status 				= (isset($_POST['id_status_undelete']) ? $_POST['id_status_undelete'] : NULL);
		$komentar	  			= (isset($_POST['komentar_undelete']) ? $_POST['komentar_undelete'] : NULL);
		
		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		$pengajuan_ho	= ($user_role[0]->level==1) ? 'n' : 'y';
		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = $user_role[0]->if_approve_undelete_ho;
		}else{
			$id_status = $user_role[0]->if_approve_undelete_pabrik;
		}
		$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		
		if ($id_data!=NULL){	
			//update data
			$data_row = array(
				"id_status"	 	=> $id_status,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				),
				array(
					'kolom' => 'id_data',
					'value' => $id_data
				),
				array(
					'kolom' => 'jenis_pengajuan',
					'value' => 'undelete'
				)
			));
			//save log undelete
			$data_row_log = array(
				"act"		=> 'approve',
				"komentar"		=> $komentar,
				"id_data_temp"	=> $id_data_temp,
				"id_status"		=> $id_status
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			
			//send email
			$data_temp 		= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);
			$nama_vendor	= (isset($_POST['nama']) ? strtoupper($_POST['nama']) : NULL);
			$plant_undelete	= (isset($_POST['plant_undelete_hide']) ? substr($_POST['plant_undelete_hide'], 0, -1) : NULL);
			$status_pengajuan = ($id_status!=99)? 'Menunggu Approval '.$next_user_role[0]->nama_role:'Completed';
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y',strtotime($data_temp[0]->tanggal_buat)).'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama_vendor.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Undelete Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Undelete Vendor</th>
							<th align="left">: '.$plant_undelete.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Alasan Undelete</th>
							<th align="left">: '.$data_temp[0]->alasan_undelete.'</th>
						</tr>
						
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: '.$status_pengajuan.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Undelete Master Vendor (".$nama_vendor.")";	
			if($id_status!=99){
				foreach($next_user_role as $dt){
					$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
					// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
				}
			}else{
				$data_temp 		= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);	
				$email_pengaju 	= $this->dtransaksivendor->get_data_karyawan(NULL, $data_temp[0]->login_buat);
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
				// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama_karyawan);
			}
			

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
	
	private function save_decline($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		  		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$nama 					= (isset($_POST['nama_hide']) ? $_POST['nama_hide'] : NULL);
		$kualifikasi_spk_hide 	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		// $pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$id_status 				= (isset($_POST['id_status']) ? $_POST['id_status'] : NULL);
		$komentar 				= (isset($_POST['komentar']) ? $_POST['komentar'] : NULL);
		
		// $user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		// $pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		// if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			// $id_status = ($kualifikasi_spk_hide==NULL)? $user_role[0]->if_decline_create_ho : $user_role[0]->if_decline_create_legal_ho;
		// }else{
			// $id_status = ($kualifikasi_spk_hide==NULL)? $user_role[0]->if_decline_create_pabrik : $user_role[0]->if_decline_create_legal_pabrik;
		// }

		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		$pengajuan_ho 	= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = ($kualifikasi_spk_hide==NULL)? $user_role[0]->if_decline_create_ho : $user_role[0]->if_decline_create_legal_ho;
			$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		}else{
			$id_status = ($kualifikasi_spk_hide==NULL)? $user_role[0]->if_decline_create_pabrik : $user_role[0]->if_decline_create_legal_pabrik;
			$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status, $user_role[0]->gsber);
		}
		

		
		if ($id_data!=NULL){	
			//update data
			$data_row = array(
				"komentar"	 	=> $komentar,
				"id_status"	 	=> $id_status,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data", $data_row, array(
				array(
					'kolom' => 'id_data',
					'value' => $id_data
				)
			));
			//save log 
			$data_row_log = array(
				"act"		=> 'reject',
				"id_data"	=> $id_data,
				"id_status"	=> $id_status,
				"komentar"	=> $komentar
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_log", $data_row_log);
			
			//send email
			$data_vendor	= $this->dtransaksivendor->get_data_vendor(NULL, $id_data);
			$user_create	= $this->dtransaksivendor->get_data_karyawan(NULL, $data_vendor[0]->login_buat);

						// <tr>
							// <th align="left" width="20%">Pabrik Pengaju</th>
							// <th align="left">: '.$data_vendor[0]->plant.'</th>
						// </tr>
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y',strtotime($data_vendor[0]->tanggal_buat)).'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Create Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Komentar</th>
							<th align="left">: '.$komentar.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: Reject '.$user_role[0]->nama_role.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Pengajuan Master Vendor (".$nama.")";	
			if($id_status!=99){
				foreach($next_user_role as $dt){
					$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
					// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
				}
			}else{
				$email_pengaju = $this->dtransaksivendor->get_data_karyawan(NULL, $data_vendor[0]->login_buat);
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
				// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama);
			}
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
	
	private function save_decline_change($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		  		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$id_data_temp			= (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
		$nama 					= (isset($_POST['nama_hide']) ? $_POST['nama_hide'] : NULL);
		$kualifikasi_spk_hide 	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		$komentar 				= (isset($_POST['komentar']) ? $_POST['komentar'] : NULL);
		// $pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$approval_legal	= ($_POST['approval_legal']=='y') ? 'y' : 'n';
		$id_status 				= (isset($_POST['level']) ? $_POST['level'] : NULL);
		
		$data_temp 				= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);	
		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL,  base64_decode($this->session->userdata("-nik-")), $id_status);

		// echo json_encode($data_temp[0]->pengajuan_ho);
		// exit();
		if($data_temp[0]->pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = $user_role[0]->if_decline_change_legal_ho;
		}else{
			$id_status = ($approval_legal=='y')?$user_role[0]->if_decline_change_legal_pabrik:$user_role[0]->if_decline_change_pabrik;
		}
		
		if ($id_data!=NULL){	
			//update data temp
			$data_row = array(
				"komentar"	 	=> $komentar,
				"id_status"	 	=> $id_status,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $data_temp[0]->id_data_temp
				)
			));
			//save log 
			$data_row_log = array(
				"act"			=> 'reject',
				"komentar"	 	=> $komentar,
				"id_data_temp"	=> $data_temp[0]->id_data_temp,
				"id_status"		=> $id_status
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			
			//send email
			$data_vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp(NULL, NULL, NULL, NULL, $id_data_temp);
			$user_create		= $this->dtransaksivendor->get_data_karyawan(NULL, $data_vendor_temp[0]->login_buat);
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y',strtotime($data_vendor_temp[0]->tanggal_buat)).'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Change Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Komentar</th>
							<th align="left">: '.$komentar.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: Reject '.$user_role[0]->nama_role.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Change Master Vendor (".$nama.")";	
			$data_temp 		= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);	
			$email_pengaju 	= $this->dtransaksivendor->get_data_karyawan(NULL, $data_temp[0]->login_buat);
			$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
			// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama_karyawan);
			

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
	
	private function save_decline_extend($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		  		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$nama			  		= (isset($_POST['nama']) ? $_POST['nama'] : NULL);
		$id_data_temp	  		= (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
		$kualifikasi_spk_hide 	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		$pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$komentar 				= (isset($_POST['komentar_extend']) ? $_POST['komentar_extend'] : NULL);
		$id_status 				= (isset($_POST['level']) ? $_POST['level'] : NULL);
		
		
		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);

		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = $user_role[0]->if_decline_extend_ho;
		}else{
			$id_status = $user_role[0]->if_decline_extend_pabrik;
		}
		
		if ($id_data!=NULL){	
			//update data
			$data_row = array(
				"komentar"	 	=> $komentar,
				"id_status"	 	=> $id_status,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				)
			));
			//save log 
			$data_row_log = array(
				"act"			=> 'reject',
				"komentar"	 	=> $komentar,
				"id_data_temp"	=> $id_data_temp,
				"id_status"		=> 100
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			
			//send email
			$nama_vendor	= (isset($_POST['nama']) ? strtoupper($_POST['nama']) : NULL);
			$plant_extend	= (isset($_POST['plant_extend_hide']) ? substr($_POST['plant_extend_hide'], 0, -1) : NULL);
			$data_vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp(NULL, NULL, NULL, NULL, $id_data_temp);
			$user_create		= $this->dtransaksivendor->get_data_karyawan(NULL, $data_vendor_temp[0]->login_buat);
			
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y',strtotime($data_vendor_temp[0]->tanggal_buat)).'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama_vendor.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Extend Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Pabrik Extend</th>
							<th align="left">: '.$plant_extend.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Komentar</th>
							<th align="left">: '.$komentar.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: Reject '.$user_role[0]->nama_role.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Extend Master Vendor (".$nama.")";	
			$email_pengaju = $this->dtransaksivendor->get_data_karyawan(NULL, $data_vendor_temp[0]->login_buat);
			$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
			// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama);			
			
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
	
	private function save_decline_delete($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		  		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$nama			  		= (isset($_POST['nama']) ? $_POST['nama'] : NULL);
		$id_data_temp	  		= (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
		$kualifikasi_spk_hide 	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		$pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$komentar 				= (isset($_POST['komentar_delete']) ? $_POST['komentar_delete'] : NULL);
		$id_status 				= (isset($_POST['level']) ? $_POST['level'] : NULL);
		
		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);

	
		if ($id_data!=NULL){	
			//update data
			$data_row = array(
				"id_status"	 	=> 100,
				"req"		 	=> "n",
				"na"		 	=> "y",
				"del"		 	=> "y",
			);
			$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				)
			));
			//update data plant_temp
			$data_plant = array(
				"na"		 	=> "y",
				"del"		 	=> "y",
			);
			$this->dgeneral->update("tbl_vendor_plant_temp", $data_plant, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				)
			));
			//save log 
			$data_row_log = array(
				"act"		=> 'reject',
				"komentar"		=> $komentar,
				"id_data_temp"	=> $id_data_temp,
				"id_status"		=> 100
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			
			//send email
			$data_vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp(NULL, NULL, NULL, NULL, $id_data_temp, 'y');
			$user_create		= $this->dtransaksivendor->get_data_karyawan(NULL, $data_vendor_temp[0]->login_buat);
			$plant_delete		= (isset($_POST['plant_delete_hide']) ? substr($_POST['plant_delete_hide'], 0, -1) : NULL);
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y',strtotime($data_vendor_temp[0]->tanggal_buat)).'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Delete Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Delete Vendor</th>
							<th align="left">: '.$plant_delete.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Alasan Delete</th>
							<th align="left">: '.$data_vendor_temp[0]->alasan_delete.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Komentar</th>
							<th align="left">: '.$komentar.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: Reject '.$user_role[0]->nama_role.'</th>
						</tr>
					</thead>
					
				</table>';	
			$subject = "Delete Master Vendor (".$nama.")";	
			$data_temp 		= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);	
			$email_pengaju 	= $this->dtransaksivendor->get_data_karyawan(NULL, $data_temp[0]->login_buat);
			$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
			// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama);
			
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
	
	private function save_decline_undelete($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		  		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$nama			  		= (isset($_POST['nama']) ? $_POST['nama'] : NULL);
		$id_data_temp	  		= (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
		$kualifikasi_spk_hide 	= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		$pengajuan_ho 			= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$id_status 				= (isset($_POST['level']) ? $_POST['level'] : NULL);
		$komentar 				= (isset($_POST['komentar_undelete']) ? $_POST['komentar_undelete'] : NULL);
		
		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		
		if ($id_data!=NULL){	
			//update data
			$data_row = array(
				"komentar"	 	=> $komentar,
				"id_status"	 	=> 100,
				"req"		 	=> "n",
				"na"		 	=> "y",
				"del"		 	=> "y",
			);
			$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				)
			));
			//update data plant_temp
			$data_plant = array(
				"na"		 	=> "y",
				"del"		 	=> "y",
			);
			$this->dgeneral->update("tbl_vendor_plant_temp", $data_plant, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				)
			));
			//save log 
			$data_row_log = array(
				"act"		=> 'reject',
				"komentar"	 	=> $komentar,
				"id_data_temp"	=> $id_data_temp,
				"id_status"		=> 100
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);

			//send email
			$data_vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp(NULL, NULL, NULL, NULL, $id_data_temp);
			// echo json_encode($data_vendor_temp);
			// exit();
			$nama_vendor	= (isset($_POST['nama']) ? strtoupper($_POST['nama']) : NULL);
			$plant_undelete	= (isset($_POST['plant_undelete_hide']) ? substr($_POST['plant_undelete_hide'], 0, -1) : NULL);
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y',strtotime($data_vendor_temp[0]->tanggal_buat)).'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama_vendor.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Undelete Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Undelete Vendor</th>
							<th align="left">: '.$plant_undelete.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Alasan Undelete</th>
							<th align="left">: '.$data_vendor_temp[0]->alasan_undelete.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Komentar</th>
							<th align="left">: '.$komentar.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: Reject '.$user_role[0]->nama_role.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Undelete Master Vendor (".$nama.")";	
			$data_temp 		= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);	
			$email_pengaju 	= $this->dtransaksivendor->get_data_karyawan(NULL, $data_temp[0]->login_buat);
			$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
			// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama);
			
			
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
	
	
	private function save_extend($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$id_data_temp 	= (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
		// $pengajuan_ho	= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$pengajuan		= (isset($_POST['pengajuan']) ? $_POST['pengajuan'] : NULL);
		$level 			= (isset($_POST['level']) ? $_POST['level'] : NULL);
		
		//additional input
		$add_pilihan 			= (isset($_POST['add_pilihan_extend']) ? "y": "n");
		$add_vendor_existing 	= (isset($_POST['add_vendor_existing_extend']) ? $_POST['add_vendor_existing_extend']: NULL);
		$add_alasan 			= (isset($_POST['add_alasan_extend']) ? $_POST['add_alasan_extend']: NULL);
		$add_vendor_flag 		= (isset($_POST['add_vendor_flag_extend']) ? $_POST['add_vendor_flag_extend']: NULL);
		
		
		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $level, base64_decode($this->session->userdata("-gsber-")));
		$pengajuan_ho	= ($user_role[0]->level==1) ? 'n' : 'y';
		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = $user_role[0]->if_approve_extend_ho;
		}else{
			$id_status = $user_role[0]->if_approve_extend_pabrik;
		}
		// $id_status = $user_role[0]->if_approve_extend_pabrik;
		$next_user_role	= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		// $pengajuan_ho	= ($user_role[0]->level==1) ? 'n' : 'y';
		// echo json_encode($id_status);
		// exit();

		// save extend
		$ck_extend	= $this->dtransaksivendor->get_data_vendor_temp(NULL,$id_data,'extend', 'y');
		if (count($ck_extend) == 0){
			$data_row = array(
				"jenis_pengajuan"	=> 'extend',
				"id_data"			=> $id_data,
				"id_status"			=> $id_status,
				"req"				=> 'y',
				"pengajuan_ho"		=> $pengajuan_ho,
				//additional input
				"add_pilihan"	 		=> $add_pilihan,
				"add_vendor_existing"	=> $add_vendor_existing,
				"add_alasan"	 		=> $add_alasan,
				"add_vendor_flag"	 	=> $add_vendor_flag
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_vendor_data_temp", $data_row);

			$id_data_temp	= $this->db->insert_id();
			//save log extend
			$data_row_log = array(
				"act"		=> 'create',
				"id_data_temp"	=> $id_data_temp,
				"id_status"	=> $user_role[0]->level
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			//xxx
			//save plant_temp
			$plants = "";
			if(isset($_POST['plant_extend'])){
				$plants .= implode(",", $_POST['plant_extend']);
			}
			$arr_plant = explode(',', $plants);
			foreach ($arr_plant as $plant) {
				$ck_plant 	= $this->dtransaksivendor->get_data_plant_temp(NULL, NULL, NULL, $id_data, $plant, 'y', NULL, 'extend', $id_data_temp);
				if (count($ck_plant) == 0){
					$data_extend = array(
						"id_data_temp" 	=> $id_data_temp,
						"id_data" 		=> $id_data,
						"status_sap"	=> 'y',
						"plant" 		=> $plant,
						"jenis_pengajuan"	=> 'extend',
						"status_delete"	=> 'y'
					);
					$data_extend = $this->dgeneral->basic_column("insert", $data_extend);
					if($plant!=''){
						$data_extend = $this->dgeneral->basic_column("insert", $data_extend);
						$this->dgeneral->insert("tbl_vendor_plant_temp", $data_extend);
					}
				}
			}		
			
			//save tbl_vendor_plant extend
			$plants = "";
			if(isset($_POST['plant_extend'])){
				$plants .= implode(",", $_POST['plant_extend']);
			}
			$arr_plant = explode(',', $plants);
			foreach ($arr_plant as $plant) {
				// echo json_encode($plant);
				// exit();
				$ck_plant 	= $this->dtransaksivendor->get_data_plant(NULL,NULL, NULL,$id_data,$plant);
				if (count($ck_plant) == 0){
					$data_extend = array(
						"id_data_temp" 	=> $id_data_temp,
						"id_data" 		=> $id_data,
						"status_sap"	=> 'n',
						"status_delete"	=> 'n',
						"plant" 		=> $plant
					);
					$data_extend = $this->dgeneral->basic_column("insert", $data_extend);
					if($plant!=''){
						$data_extend = $this->dgeneral->basic_column("insert", $data_extend);
						$this->dgeneral->insert("tbl_vendor_plant", $data_extend);
					}
				}
			}		
		}else{
			//update data extend
			$data_row = array(
				"id_status"	 	=> $id_status,
				//additional input
				"add_pilihan"	 		=> $add_pilihan,
				"add_vendor_existing"	=> $add_vendor_existing,
				"add_alasan"	 		=> $add_alasan,
				"add_vendor_flag"	 	=> $add_vendor_flag,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				)
			));
			//save log extend
			$data_row_log = array(
				"act"		=> 'create',
				"id_data_temp"	=> $id_data_temp,
				"id_status"	=> $user_role[0]->level
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			
			$plants = "";
			if(isset($_POST['plant_extend'])){
				$plants .= implode(",", $_POST['plant_extend']);
			}
			$arr_plant = explode(',', $plants);
			foreach ($arr_plant as $plant) {
				$ck_plant 	= $this->dtransaksivendor->get_data_plant(NULL,NULL, NULL,$id_data,$plant);
				if (count($ck_plant) == 0){
					$data_extend = array(
						"id_data_temp" 	=> $id_data_temp,
						"id_data" 		=> $id_data,
						"status_sap"	=> 'n',
						"status_delete"	=> 'n',
						"plant" 		=> $plant
					);
					$data_extend = $this->dgeneral->basic_column("insert", $data_extend);
					if($plant!=''){
						$data_extend = $this->dgeneral->basic_column("insert", $data_extend);
						$this->dgeneral->insert("tbl_vendor_plant", $data_extend);
					}
				}
			}		
		}
		//send email
		$nama_vendor	= (isset($_POST['nama']) ? strtoupper($_POST['nama']) : NULL);
		$plant_extend	= (isset($_POST['plant_extend']) ? implode(",", $_POST['plant_extend']) : NULL);
		$status_pengajuan = ($id_status!=99)? 'Menunggu Approval '.$next_user_role[0]->nama_role:'Completed';
		$content = '
			<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
				<thead>
					<tr>
						<th align="left" width="20%">Tanggal Pengajuan</th>
						<th align="left">: '.date('d.m.Y').'</th>
					</tr>
					<tr>
						<th align="left" width="20%">Nama Vendor</th>
						<th align="left">: '.$nama_vendor.'</th>
					</tr>
					<tr>
						<th align="left" width="20%">Jenis Pengajuan</th>
						<th align="left">: Extend Vendor</th>
					</tr>
					<tr>
						<th align="left" width="20%">Pabrik Extend</th>
						<th align="left">: '.$plant_extend.'</th>
					</tr>
					<tr>
						<th align="left" width="20%">Status Pengajuan</th>
						<th align="left">: '.$status_pengajuan.'</th>
					</tr>
				</thead>
			</table>';	
		$subject = "Extend Master Vendor (".$nama_vendor.")";	
		
		foreach($next_user_role as $dt){
			$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
			// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
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
	private function save_delete($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 			  = (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		// $pengajuan_ho	= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$level 				  = (isset($_POST['level']) ? $_POST['level'] : NULL);
		$alasan_delete		  = (isset($_POST['alasan_delete']) ? $_POST['alasan_delete'] : NULL);
		$alasan_delete_detail = (isset($_POST['alasan_delete_detail']) ? $_POST['alasan_delete_detail'] : NULL);
		
		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $level);
		
		$pengajuan_ho	= ($user_role[0]->level==1) ? 'n' : 'y';
		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = $user_role[0]->if_approve_delete_ho;
			$plant_pengaju	= "HO";
			$req			= "n";
		}else{
			$id_status = $user_role[0]->if_approve_delete_pabrik;
			$plant_pengaju	= base64_decode($this->session->userdata("-gsber-"));
			$req			= "y";
		}
		$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
	
		if ($id_data!=NULL){	
			//save delete(tbl_vendor_data_temp)
			$ck_delete	= $this->dtransaksivendor->get_data_vendor_temp(NULL,$id_data,'delete', 'y');
			if (count($ck_delete) == 0){
				$data_row = array(
					"plant"			=> $plant_pengaju,
					"alasan_delete"			=> $alasan_delete,
					"alasan_delete_detail"	=> $alasan_delete_detail,
					"jenis_pengajuan"		=> 'delete',
					"id_data"				=> $id_data,
					"id_status"				=> $id_status,
					"req"					=> $req,
					"pengajuan_ho"			=> $pengajuan_ho
				);
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_vendor_data_temp", $data_row);
				$id_data_temp	= $this->db->insert_id();
				
				
				//save log delete(jika pengajuan dari proc ho, langsung set completed)
				if($pengajuan_ho=='y'){
					$data_row_log = array(
						"act"		=> 'create',
						"id_data_temp"	=> $id_data_temp,
						"id_status"	=> 99
					);
					$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
					$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
				}else{
					//save log delete(by status)
					$data_row_log = array(
						"act"		=> 'create',
						"id_data_temp"	=> $id_data_temp,
						"id_status"	=> $user_role[0]->level
					);
					$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
					$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
				}
			}
			
			//save plant_temp delete
			$plants		= (isset($_POST['plant_delete']) ? implode(",", $_POST['plant_delete']) : NULL);
			$arr_plant 	= explode(',', $plants);
			foreach ($arr_plant as $plant) {
				$ck_plant 	= $this->dtransaksivendor->get_data_plant_temp(NULL, NULL, NULL, $id_data, $plant, 'n', NULL, 'delete', $id_data_temp);
				if (count($ck_plant) == 0){
					$data_delete = array(
						"id_data" 		=> $id_data,
						"id_data_temp" 	=> $id_data_temp,
						"status_sap"	=> 'y',
						"plant" 		=> $plant,
						"jenis_pengajuan"	=> 'delete',
						"status_delete"	=> 'n'
					);
					$data_delete = $this->dgeneral->basic_column("insert", $data_delete);
					if($plant!=''){
						$data_delete = $this->dgeneral->basic_column("insert", $data_delete);
						$this->dgeneral->insert("tbl_vendor_plant_temp", $data_delete);
					}
				}
			}		
			
			//send email
			$nama_vendor	= (isset($_POST['nama']) ? strtoupper($_POST['nama']) : NULL);
			$alasan_delete	= (isset($_POST['alasan_delete']) ? $_POST['alasan_delete'] : NULL);
			$plant_delete	= (isset($_POST['plant_delete']) ? implode(",", $_POST['plant_delete']) : NULL);
			$status_pengajuan = ($id_status!=99)? 'Menunggu Approval '.$next_user_role[0]->nama_role:'Completed';
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y').'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama_vendor.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Delete Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Delete Vendor</th>
							<th align="left">: '.$plant_delete.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Alasan Delete</th>
							<th align="left">: '.$alasan_delete.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: '.$status_pengajuan.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Delete Master Vendor (".$nama_vendor.")";	
			
			if($id_status!=99){
				foreach($next_user_role as $dt){
					$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
					// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
				}
			}else{
				$email_pengaju = $this->dtransaksivendor->get_data_karyawan(NULL, $id_data_temp);
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
				// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama);			
			}
			
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
	private function save_undelete($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 			= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		// $pengajuan_ho	= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$level 				= (isset($_POST['level']) ? $_POST['level'] : NULL);
		$alasan_undelete	= (isset($_POST['alasan_undelete']) ? $_POST['alasan_undelete'] : NULL);
		
		$user_role			= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $level);
		$pengajuan_ho		= ($user_role[0]->level==1) ? 'n' : 'y';
		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = $user_role[0]->if_approve_undelete_ho;
			$plant_pengaju	= 'HO';
		}else{
			$id_status = $user_role[0]->if_approve_undelete_pabrik;
			$plant_pengaju	= base64_decode($this->session->userdata("-gsber-"));
		}
		$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		
		if ($id_data!=NULL){	
			//save undelete(tbl_vendor_data_temp)
			$ck_delete	= $this->dtransaksivendor->get_data_vendor_temp(NULL,$id_data,'undelete', 'y');
			if (count($ck_delete) == 0){
				$data_row = array(
					"plant"				=> $plant_pengaju,
					"alasan_undelete"	=> $alasan_undelete,
					"jenis_pengajuan"	=> 'undelete',
					"id_data"			=> $id_data,
					"id_status"			=> $id_status,
					"req"				=> 'y',
					"pengajuan_ho"		=> $pengajuan_ho
				);
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_vendor_data_temp", $data_row);
			}
			$id_data_temp	= $this->db->insert_id();
			//save log delete
			$data_row_log = array(
				"act"		=> 'create',
				"id_data_temp"	=> $id_data_temp,
				"id_status"	=> $user_role[0]->level
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			
			//save plant_temp undelete
			$plants		= (isset($_POST['plant_undelete']) ? implode(",", $_POST['plant_undelete']) : NULL);
			$arr_plant = explode(',', $plants);
			foreach ($arr_plant as $plant) {
				$ck_plant 	= $this->dtransaksivendor->get_data_plant_temp(NULL, NULL, NULL, $id_data, $plant, 'y', NULL, 'undelete', $id_data_temp);
				if (count($ck_plant) == 0){
					$data_undelete = array(
						"id_data" 		=> $id_data,
						"id_data_temp" 	=> $id_data_temp,
						"status_sap"	=> 'y',
						"plant" 		=> $plant,
						"jenis_pengajuan"	=> 'undelete',
						"status_delete"	=> 'y'
					);
					$data_undelete = $this->dgeneral->basic_column("insert", $data_undelete);
					if($plant!=''){
						$data_undelete = $this->dgeneral->basic_column("insert", $data_undelete);
						$this->dgeneral->insert("tbl_vendor_plant_temp", $data_undelete);
					}
				}
			}		
			
			//send email
			$nama_vendor	= (isset($_POST['nama']) ? strtoupper($_POST['nama']) : NULL);
			$alasan_undelete= (isset($_POST['alasan_undelete']) ? $_POST['alasan_undelete'] : NULL);
			$plant_undelete	= (isset($_POST['plant_undelete']) ? implode(",", $_POST['plant_undelete']) : NULL);
			$status_pengajuan = ($id_status!=99)? 'Menunggu Approval '.$next_user_role[0]->nama_role:'Completed';
			$content = '
				<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
					<thead>
						<tr>
							<th align="left" width="20%">Tanggal Pengajuan</th>
							<th align="left">: '.date('d.m.Y').'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Nama Vendor</th>
							<th align="left">: '.$nama_vendor.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Jenis Pengajuan</th>
							<th align="left">: Undelete Vendor</th>
						</tr>
						<tr>
							<th align="left" width="20%">Undelete Vendor</th>
							<th align="left">: '.$plant_undelete.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Alasan Undelete</th>
							<th align="left">: '.$alasan_undelete.'</th>
						</tr>
						<tr>
							<th align="left" width="20%">Status Pengajuan</th>
							<th align="left">: '.$status_pengajuan.'</th>
						</tr>
					</thead>
				</table>';	
			$subject = "Undelete Master Vendor (".$nama_vendor.")";	
			if($id_status!=99){
				foreach($next_user_role as $dt){
					$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
					// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
				}
			}else{
				$email_pengaju = $this->dtransaksivendor->get_data_vendor(NULL, $data_vendor[0]->login_buat);
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content);
				// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content);
			}
			
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
	private function save_change($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		// echo json_encode($_POST);
		// exit;

		$id_data 		= (isset($_POST['id_data']) ? $this->generate->kirana_decrypt($_POST['id_data']) : NULL);
		$data_vendor	= $this->dtransaksivendor->get_data_vendor(NULL, $id_data);
		$id_data_temp 	= (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
		$nama			= (isset($_POST['nama']) ? $_POST['nama'] : NULL);
		$id_jenis_vendor= (isset($_POST['id_jenis_vendor']) ? $this->generate->kirana_decrypt($_POST['id_jenis_vendor']) : NULL);
		$kualifikasi_spk= (isset($_POST['kualifikasi_spk']) ? implode(",", $_POST['kualifikasi_spk']) : NULL);
		// $kualifikasi_spk= (isset($_POST['kualifikasi_spk_hide']) ? $_POST['kualifikasi_spk_hide'] : NULL);
		$pengajuan		= (isset($_POST['pengajuan']) ? $_POST['pengajuan'] : NULL);
		// $pengajuan_ho	= (isset($_POST['pengajuan_ho']) ? $_POST['pengajuan_ho'] : NULL);
		$level 			= (isset($_POST['level']) ? $_POST['level'] : NULL);
		$tax_type		= (isset($_POST['tax_type']) ? $_POST['tax_type'] : NULL);
		$tax_code		= (isset($_POST['tax_code']) ? $_POST['tax_code'] : NULL);
		$approval_legal	= ($_POST['approval_legal']=='y') ? 'y' : 'n';
		$approval_proc	= ($_POST['approval_proc']=='y') ? 'y' : 'n';
		$perubahan_data = ($_POST['perubahan_data']=='y') ? 'y' : 'n';
		
		$user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $level);
		$pengajuan_ho	= ($user_role[0]->level==1) ? 'n' : 'y';
		
		if($pengajuan_ho=='y'){	//jika pengajuan dr ho
			$id_status = ($approval_legal=='y')?$user_role[0]->if_approve_change_legal_ho:$user_role[0]->if_approve_change_ho;
			$plant_pengaju	= 'HO';
		}else{
			if($approval_legal=='y'){
				$id_status = $user_role[0]->if_approve_change_legal_pabrik;
			}else if($approval_proc=='y'){
				$id_status = $user_role[0]->if_approve_change_pabrik;
			}else{
				$id_status = $user_role[0]->if_approve_change_pabrik;
			}
			$plant_pengaju	= base64_decode($this->session->userdata("-gsber-"));
		}
		$next_user_role		= $this->dmastervendor->get_data_user_role(NULL, NULL, NULL, NULL, NULL, $id_status);
		
		//save change(tbl_vendor_data_temp)
		$ck_change	= $this->dtransaksivendor->get_data_vendor_temp(NULL,$id_data,'change', 'y');
		if (count($ck_change) == 0){
			$req = (($level=='4')and($approval_legal=='n')and($approval_proc=='n'))?'n':'y';
			$data_row = array(
				"id_jenis_vendor"	=> $id_jenis_vendor,
				"kualifikasi_spk"	=> $kualifikasi_spk,
				"plant"				=> $plant_pengaju,
				"perubahan_data"	=> $perubahan_data,
				"approval_legal"	=> $approval_legal,
				"approval_proc"		=> $approval_proc,
				"pengajuan_ho"		=> $pengajuan_ho,
				"jenis_pengajuan"	=> 'change',
				"id_data"			=> $id_data,
				"id_status"			=> $id_status,
				
				"title"				=> $data_vendor[0]->title,
				"nama"	 			=> $data_vendor[0]->nama,
				"ktp"	 			=> $data_vendor[0]->ktp,
				"npwp"	 			=> $data_vendor[0]->npwp,
				"tax_type"	 		=> $data_vendor[0]->tax_type,
				"tax_code"	 		=> $data_vendor[0]->tax_code,
				"negara"			=> $data_vendor[0]->negara,
				"provinsi"		 	=> $data_vendor[0]->provinsi,
				"kota"			 	=> $data_vendor[0]->kota,
				"alamat"	 		=> $data_vendor[0]->alamat,
				"no"	 			=> $data_vendor[0]->no,
				"kode_pos"	 		=> $data_vendor[0]->kode_pos,
				"req"				=> $req,
				
				"title_new"			=> $_POST['title'],
				"nama_new"			=> strtoupper($_POST['nama']),
				"ktp_new"			=> $_POST['ktp'],
				"npwp_new"			=> $_POST['npwp'],
				"tax_type_new"		=> $tax_type,
				"tax_code_new"		=> $tax_code,
				"negara_new"		=> $_POST['negara'],
				"provinsi_new"		=> @$_POST['provinsi'],
				"kota_new"			=> $_POST['kota'],
				"alamat_new"		=> $_POST['alamat'],
				"no_new"			=> $_POST['no'],
				"kode_pos_new"		=> $_POST['kode_pos'],
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_vendor_data_temp", $data_row);
			$id_data_temp	= $this->db->insert_id();
			
			//save log change
			$data_row_log = array(
				"act"		=> 'create',
				"id_data_temp"	=> $id_data_temp, 
				"id_status"		=> $id_status
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
			//
		//jika change ulang	
		}else{
			$data_row = array(
				"id_jenis_vendor"	=> $id_jenis_vendor,
				"kualifikasi_spk"	=> $kualifikasi_spk,
				"title"				=> $_POST['title'],
				"pengajuan_ho"		=> $pengajuan_ho,
				"jenis_pengajuan"	=> 'change',
				"id_data"			=> $id_data,
				"id_status"			=> $id_status,
				
				"title"				=> $data_vendor[0]->title,
				"nama"	 			=> $data_vendor[0]->nama,
				"ktp"	 			=> $data_vendor[0]->ktp,
				"npwp"	 			=> $data_vendor[0]->npwp,
				"tax_type"	 		=> $data_vendor[0]->tax_type,
				"tax_code"	 		=> $data_vendor[0]->tax_code,
				"negara"			=> $data_vendor[0]->negara,
				"provinsi"		 	=> $data_vendor[0]->provinsi,
				"kota"			 	=> $data_vendor[0]->kota,
				"alamat"	 		=> $data_vendor[0]->alamat,
				"no"	 			=> $data_vendor[0]->no,
				"kode_pos"	 		=> $data_vendor[0]->kode_pos,
				"req"				=> 'y',
				
				"title_new"			=> $_POST['title'],
				"nama_new"			=> strtoupper($_POST['nama']),
				"ktp_new"			=> $_POST['ktp'],
				"npwp_new"			=> $_POST['npwp'],
				"tax_type_new"		=> $tax_type,
				"tax_code_new"		=> $tax_code,
				"negara_new"		=> $_POST['negara'],
				"provinsi_new"		=> @$_POST['provinsi'],
				"kota_new"			=> $_POST['kota'],
				"alamat_new"		=> $_POST['alamat'],
				"no_new"			=> $_POST['no'],
				"kode_pos_new"		=> $_POST['kode_pos'],

			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data_temp", $data_row, array(
				array(
					'kolom' => 'id_data',
					'value' => $id_data
				),
				array(
					'kolom' => 'jenis_pengajuan',
					'value' => 'change'
				),
				array(
					'kolom' => 'req',
					'value' => 'y'
				)
			));
			
			//save log 
			$id_data_temp	= $_POST['id_data_temp'];
			//save log change
			$data_row_log = array(
				"act"		=> 'create',
				"id_data_temp"	=> $id_data_temp,
				"id_status"	=> $user_role[0]->level
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
		}
		
		//upload dokumen jenis vendor
		//jika pengajuan dari HO tanpa approval legal langsung update file
		if(($level=='4')and($approval_proc=='y')){
			//update tabel utama 
			$data_row = array(
				"id_jenis_vendor"	 	=> $id_jenis_vendor,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data", $data_row, array(
				array(
					'kolom' => 'id_data',
					'value' => $id_data
				)
			));
			
			//==========================
			//change dokumen jenis vendor
			//==========================
			$nama_folder	= $id_data." - ".strtoupper($nama);
			$ck_folder 		= $this->dmaster->get_data_folder(NULL, 553, $nama_folder);	//set untuk go-live berbeda xxx
			$id_folder		= (count($ck_folder) == 0)?$this->db->insert_id():$ck_folder[0]->id_folder;
			//dokumen jenis vendor
			$jenis_vendor 				= $this->dtransaksivendor->get_data_jenis_vendor_dokumen(NULL, NULL, NULL, NULL, $id_jenis_vendor);
			foreach($jenis_vendor as $dt2){
				$file_dokumen 			= 'file_dokumen_'.$dt2->id_master_dokumen;
				$nama_dokumen_hide		= 'nama_dokumen_hide_'.$dt2->id_master_dokumen;
				$tanggal_awal_jenis		= 'tanggal_awal_jenis_'.$dt2->id_master_dokumen;
				$tanggal_awal_jenis		= ($_POST[$tanggal_awal_jenis]!='') ? date_create($_POST[$tanggal_awal_jenis])->format('Y-m-d'): '1900-01-01';
				$tanggal_akhir_jenis	= 'tanggal_akhir_jenis_'.$dt2->id_master_dokumen;				
				$tanggal_akhir_jenis	= ($_POST[$tanggal_akhir_jenis]!='') ? date_create($_POST[$tanggal_akhir_jenis])->format('Y-m-d'): '9999-12-31';
				$nama_dokumen			= str_replace(' ','_',$dt2->nama_dokumen)." - $id_data $tanggal_awal_jenis sd $tanggal_akhir_jenis";				
				$nama_dokumen			= str_replace(' ','_',$nama_dokumen);
				//upload file jika ada file yang diupload
				if($_FILES[$file_dokumen]['name'][0]!=''){
					$config['upload_path']   = str_replace('/vendor/','/folder/',$this->general->kirana_file_path($this->router->fetch_module()) . '/vendor');
					$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
					$config['max_size']      = 0;
					
					$newname	= array($nama_dokumen);			
					$file		= $this->general->upload_files($_FILES[$file_dokumen], $newname, $config);
					$nama_file	= str_replace('_',' ',$newname[0]);
					$url_file	= str_replace("assets/", "", $file[0]['url']);
					if($file === NULL){
						$msg        = "Upload files error";
						$sts        = "NotOK";
						$return     = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$ck_file	= $this->dtransaksivendor->get_data_file(NULL, NULL, $id_folder, $nama_file);
					if (count($ck_file) == 0){
						//batch data file jenis
						$data_file_jenis = array(
							'id_folder'     	=> $id_folder,
							'nama'     		    => $nama_file,
							'ukuran'        	=> $file[0]['size'],
							'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
							'link'      		=> str_replace(' ','_',$url_file),
							'divisi_akses'    	=> NULL, 
							'departemen_akses'  => NULL,
							'divisi_write'    	=> NULL,
							'departemen_write'  => NULL,
							'level_akses'       => NULL,
							'level_write'       => NULL,
							'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'  	=> $datetime,
							'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'  	=> $datetime,
							'lihat'				=> 'y'  
						);
						$data_file_jenis = $this->dgeneral->basic_column("insert", $data_file_jenis);
						$this->dgeneral->insert("tbl_file", $data_file_jenis);
						$id_file	= $this->db->insert_id();
						
						//batch data dokumen jenis
						$data_dokumen_jenis = array(
							"id_data"				=> $id_data,
							"id_data_temp"			=> $id_data_temp,
							"id_master_dokumen"		=> $dt2->id_master_dokumen,
							"id_referensi" 			=> 1,
							"tipe_referensi"		=> 'jenis_vendor',
							"id_folder" 			=> $id_folder,
							"id_file" 				=> $id_file,
							"tanggal_awal"		 	=> $tanggal_awal_jenis,
							"tanggal_akhir"		 	=> $tanggal_akhir_jenis,
							"status"			 	=> 1,
							"mandatory"			 	=> $dt2->mandatory,
						);
						$data_dokumen_jenis 		= $this->dgeneral->basic_column("insert", $data_dokumen_jenis);
						$this->dgeneral->insert("tbl_vendor_data_dokumen", $data_dokumen_jenis);					
					}else{
						//batch data file jenis
						$data_file_jenis = array(
							// 'id_folder'     	=> $id_folder,
							'nama'     		    => $nama_file,
							'ukuran'        	=> $file[0]['size'],
							'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
							'link'      		=> str_replace(' ','_',$url_file),
							'divisi_akses'    	=> NULL, 
							'departemen_akses'  => NULL,
							'divisi_write'    	=> NULL,
							'departemen_write'  => NULL,
							'level_akses'       => NULL,
							'level_write'       => NULL,
							'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'  	=> $datetime,
							'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'  	=> $datetime,
							'lihat'				=> 'y'  
						);
						$data_file_jenis = $this->dgeneral->basic_column("update", $data_file_jenis);
						$this->dgeneral->update("tbl_file", $data_file_jenis, array(
							array(
								'kolom' => 'id_file',
								'value' => $ck_file[0]->id_file
							)
						));
						// $this->dgeneral->insert("tbl_file", $data_file_jenis);
						$id_file	= $ck_file[0]->id_file;

						//batch data dokumen jenis
						$data_dokumen_jenis = array(
							"id_data"				=> $id_data,
							"id_data_temp"			=> $id_data_temp,
							"id_master_dokumen"		=> $dt2->id_master_dokumen,
							"id_referensi" 			=> 1,
							"tipe_referensi"		=> 'jenis_vendor',
							"id_folder" 			=> $id_folder,
							"id_file" 				=> $id_file,
							"tanggal_awal"		 	=> $tanggal_awal_jenis,
							"tanggal_akhir"		 	=> $tanggal_akhir_jenis,
							"status"			 	=> 1,
							"mandatory"			 	=> $dt2->mandatory,
						);
						$data_dokumen_jenis = $this->dgeneral->basic_column("update", $data_dokumen_jenis);
						$this->dgeneral->update("tbl_vendor_data_dokumen", $data_dokumen_jenis, array(
							array(
								'kolom' => 'id_file',
								'value' => $ck_file[0]->id_file
							)
						));
					}
				}
			}
			//xx
		}else{
			//change dokumen jika dari proc pabrik
			$nama_folder	= $id_data." - ".strtoupper($nama);
			$ck_folder 		= $this->dmaster->get_data_folder(NULL, 553, $nama_folder);	//set untuk go-live berbeda xxx
			$id_folder		= (count($ck_folder) == 0)?$this->db->insert_id():$ck_folder[0]->id_folder;
			//set tbl_file_temp na='y'
			$data_file_temp = $this->dgeneral->basic_column('delete', NULL, $datetime);
			$this->dgeneral->update("tbl_file_temp", $data_file_temp, array(
				array(
					'kolom' => 'id_folder',
					'value' => $id_folder
				),
				array(
					'kolom' => 'na',
					'value' => 'n'
				)
			));
			//set tbl_vendor_data_dokumen_temp na='y'
			$data_dokumen_temp = $this->dgeneral->basic_column('delete', NULL, $datetime);
			$this->dgeneral->update("tbl_vendor_data_dokumen_temp", $data_dokumen_temp, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				),
				array(
					'kolom' => 'tipe_referensi',
					'value' => 'jenis_vendor'
				),
				array(
					'kolom' => 'na',
					'value' => 'n'
				)
			));
			
			//dokumen jenis vendor
			$jenis_vendor 				= $this->dtransaksivendor->get_data_jenis_vendor_dokumen(NULL, NULL, NULL, NULL, $id_jenis_vendor);
			foreach($jenis_vendor as $dt2){
				$file_dokumen 			= 'file_dokumen_'.$dt2->id_master_dokumen;
				$nama_dokumen_hide		= 'nama_dokumen_hide_'.$dt2->id_master_dokumen;
				$tanggal_awal_jenis		= 'tanggal_awal_jenis_'.$dt2->id_master_dokumen;
				$tanggal_awal_jenis		= ($_POST[$tanggal_awal_jenis]!='') ? date_create($_POST[$tanggal_awal_jenis])->format('Y-m-d'): '1900-01-01';
				$tanggal_akhir_jenis	= 'tanggal_akhir_jenis_'.$dt2->id_master_dokumen;				
				$tanggal_akhir_jenis	= ($_POST[$tanggal_akhir_jenis]!='') ? date_create($_POST[$tanggal_akhir_jenis])->format('Y-m-d'): '9999-12-31';
				$nama_dokumen			= str_replace(' ','_',$dt2->nama_dokumen)." - $id_data $tanggal_awal_jenis sd $tanggal_akhir_jenis";				
				$nama_dokumen			= str_replace(' ','_',$nama_dokumen);
				//upload file jenis vendor
				if($_FILES[$file_dokumen]['name'][0]!=''){
					$config['upload_path']   = str_replace('/vendor/','/folder/',$this->general->kirana_file_path($this->router->fetch_module()) . '/vendor_temp');
					$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
					$config['max_size']      = 0;
					
					$newname	= array($nama_dokumen);			
					$file		= $this->general->upload_files($_FILES[$file_dokumen], $newname, $config);
					$nama_file	= str_replace('_',' ',$newname[0]);
					$url_file	= str_replace("assets/", "", $file[0]['url']);
					if($file === NULL){
						$msg        = "Upload files error";
						$sts        = "NotOK";
						$return     = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					//batch data file jenis
					$data_file_jenis = array(
						'id_folder'     	=> $id_folder,
						'nama'     		    => $nama_file,
						'ukuran'        	=> $file[0]['size'],
						'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
						'link'      		=> str_replace(' ','_',$url_file),
						'divisi_akses'    	=> NULL, 
						'departemen_akses'  => NULL,
						'divisi_write'    	=> NULL,
						'departemen_write'  => NULL,
						'level_akses'       => NULL,
						'level_write'       => NULL,
						'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'  	=> $datetime,
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime,
						'lihat'				=> 'y'  
					);
					$data_file_jenis = $this->dgeneral->basic_column("insert", $data_file_jenis);
					$this->dgeneral->insert("tbl_file_temp", $data_file_jenis);
					$id_file	= $this->db->insert_id();
					
					//batch data dokumen jenis
					$data_dokumen_jenis = array(
						"id_data"				=> $id_data,
						"id_data_temp"			=> $id_data_temp,
						"id_master_dokumen"		=> $dt2->id_master_dokumen,
						"id_referensi" 			=> 1,
						"tipe_referensi"		=> 'jenis_vendor',
						"id_folder" 			=> $id_folder,
						"id_file" 				=> $id_file,
						"tanggal_awal"		 	=> $tanggal_awal_jenis,
						"tanggal_akhir"		 	=> $tanggal_akhir_jenis,
						"status"			 	=> 1,
						"mandatory"			 	=> $dt2->mandatory,
					);
					$data_dokumen_jenis 		= $this->dgeneral->basic_column("insert", $data_dokumen_jenis);
					$this->dgeneral->insert("tbl_vendor_data_dokumen_temp", $data_dokumen_jenis);					
					
				}
			}	
		}			
		
		//==========================
		//change dokumen kualifikasi vendor
		//==========================
		//jika pengajuan dari HO tanpa approval legal langsung update file
		if(($level=='3')and($approval_legal=='y')){
			//update tabel utama 
			$kualifikasi_spk	= (isset($_POST['kualifikasi_spk']) ? implode(",", $_POST['kualifikasi_spk']) : NULL);
			$data_row = array(
				"kualifikasi_spk"	 	=> $kualifikasi_spk,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_vendor_data", $data_row, array(
				array(
					'kolom' => 'id_data',
					'value' => $id_data
				)
			));

			$nama_folder	= $id_data." - ".strtoupper($nama);
			$ck_folder 		= $this->dmaster->get_data_folder(NULL, 553, $nama_folder);	//set untuk go-live berbeda xxx
			$id_folder		= (count($ck_folder) == 0)?$this->db->insert_id():$ck_folder[0]->id_folder;
			//dokumen kualifikasi vendor
			$id_kualifikasi_spk	= array();
			$arr_kualifikasi = explode(',', $kualifikasi_spk);
			foreach ($arr_kualifikasi as $dt) {
				array_push($id_kualifikasi_spk, $dt);
			}
			$data_dokumen_kualifikasi	= array();
			$kualifikasi_vendor			= $this->dtransaksivendor->get_data_kualifikasi_dokumen(NULL, $id_kualifikasi_spk);
			foreach($kualifikasi_vendor as $dt2){
				$file_dokumen 			= 'file_dokumen_'.$dt2->id_master_dokumen;
				$nama_dokumen_hide		= 'nama_dokumen_hide_'.$dt2->id_master_dokumen;
				$tanggal_awal_kualifikasi	= 'tanggal_awal_kualifikasi_'.$dt2->id_master_dokumen;
				$tanggal_awal_kualifikasi	= ($_POST[$tanggal_awal_kualifikasi]!='') ? date_create($_POST[$tanggal_awal_kualifikasi])->format('Y-m-d'): '1900-01-01';
				$tanggal_akhir_kualifikasi	= 'tanggal_akhir_kualifikasi_'.$dt2->id_master_dokumen;				
				$tanggal_akhir_kualifikasi	= ($_POST[$tanggal_akhir_kualifikasi]!='') ? date_create($_POST[$tanggal_akhir_kualifikasi])->format('Y-m-d'): '9999-12-31';
				$nama_dokumen				= str_replace(' ','_',$dt2->nama_dokumen)." - $id_data $tanggal_awal_kualifikasi sd $tanggal_akhir_kualifikasi";				
				$nama_dokumen				= str_replace(' ','_',$nama_dokumen);

				//upload file jika ada file yang diupload
				if($_FILES[$file_dokumen]['name'][0]!=''){
					$config['upload_path']   = str_replace('/vendor/','/folder/',$this->general->kirana_file_path($this->router->fetch_module()) . '/vendor');
					$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
					$config['max_size']      = 0;
					
					$newname	= array($nama_dokumen);			
					$file		= $this->general->upload_files($_FILES[$file_dokumen], $newname, $config);
					$nama_file	= str_replace('_',' ',$newname[0]);
					$url_file	= str_replace("assets/", "", $file[0]['url']);
					if($file === NULL){
						$msg        = "Upload files error";
						$sts        = "NotOK";
						$return     = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$ck_file	= $this->dtransaksivendor->get_data_file(NULL, NULL, $id_folder, $nama_file);
					if (count($ck_file) == 0){
						//batch data file jenis
						$data_file_jenis = array(
							'id_folder'     	=> $id_folder,
							'nama'     		    => $nama_file,
							'ukuran'        	=> $file[0]['size'],
							'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
							'link'      		=> str_replace(' ','_',$url_file),
							'divisi_akses'    	=> NULL, 
							'departemen_akses'  => NULL,
							'divisi_write'    	=> NULL,
							'departemen_write'  => NULL,
							'level_akses'       => NULL,
							'level_write'       => NULL,
							'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'  	=> $datetime,
							'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'  	=> $datetime,
							'lihat'				=> 'y'  
						);
						$data_file_jenis = $this->dgeneral->basic_column("insert", $data_file_jenis);
						$this->dgeneral->insert("tbl_file", $data_file_jenis);
						$id_file	= $this->db->insert_id();
						
						//batch data dokumen jenis
						$data_dokumen_jenis = array(
							"id_data"				=> $id_data,
							"id_data_temp"			=> $id_data_temp,
							"id_master_dokumen"		=> $dt2->id_master_dokumen,
							"id_referensi" 			=> 1,
							"tipe_referensi"		=> 'kualifikasi_spk',
							"id_folder" 			=> $id_folder,
							"id_file" 				=> $id_file,
							"tanggal_awal"		 	=> $tanggal_awal_kualifikasi,
							"tanggal_akhir"		 	=> $tanggal_akhir_kualifikasi,
							"status"			 	=> 1,
							"mandatory"			 	=> 'Mandatory',
						);
						$data_dokumen_jenis 		= $this->dgeneral->basic_column("insert", $data_dokumen_jenis);
						$this->dgeneral->insert("tbl_vendor_data_dokumen", $data_dokumen_jenis);					
					}else{
						//batch data file jenis
						$data_file_jenis = array(
							// 'id_folder'     	=> $id_folder,
							'nama'     		    => $nama_file,
							'ukuran'        	=> $file[0]['size'],
							'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
							'link'      		=> str_replace(' ','_',$url_file),
							'divisi_akses'    	=> NULL, 
							'departemen_akses'  => NULL,
							'divisi_write'    	=> NULL,
							'departemen_write'  => NULL,
							'level_akses'       => NULL,
							'level_write'       => NULL,
							'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'  	=> $datetime,
							'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'  	=> $datetime,
							'lihat'				=> 'y'  
						);
						$data_file_jenis = $this->dgeneral->basic_column("update", $data_file_jenis);
						$this->dgeneral->update("tbl_file", $data_file_jenis, array(
							array(
								'kolom' => 'id_file',
								'value' => $ck_file[0]->id_file
							)
						));
						// $this->dgeneral->insert("tbl_file", $data_file_jenis);
						$id_file	= $ck_file[0]->id_file;

						//batch data dokumen jenis
						$data_dokumen_jenis = array(
							"id_data"				=> $id_data,
							"id_data_temp"			=> $id_data_temp,
							"id_master_dokumen"		=> $dt2->id_master_dokumen,
							"id_referensi" 			=> 1,
							"tipe_referensi"		=> 'kualifikasi_spk',
							"id_folder" 			=> $id_folder,
							"id_file" 				=> $id_file,
							"tanggal_awal"		 	=> $tanggal_awal_kualifikasi,
							"tanggal_akhir"		 	=> $tanggal_akhir_kualifikasi,
							"status"			 	=> 1,
							"mandatory"			 	=> 'Mandatory',
						);
						$data_dokumen_jenis = $this->dgeneral->basic_column("update", $data_dokumen_jenis);
						$this->dgeneral->update("tbl_vendor_data_dokumen", $data_dokumen_jenis, array(
							array(
								'kolom' => 'id_file',
								'value' => $ck_file[0]->id_file
							)
						));
					}
				}
			}
			//jika upload dokumen kualifikasi vendor dilakukan oleh selain legal HO
		}else{
			//change dokumen
			$nama_folder	= $id_data." - ".strtoupper($nama);
			$ck_folder 		= $this->dmaster->get_data_folder(NULL, 553, $nama_folder);	//set untuk go-live berbeda xxx
			$id_folder		= (count($ck_folder) == 0)?$this->db->insert_id():$ck_folder[0]->id_folder;
			//set tbl_vendor_data_dokumen_temp na='y'
			$data_dokumen_temp = $this->dgeneral->basic_column('delete', NULL, $datetime);
			$this->dgeneral->update("tbl_vendor_data_dokumen_temp", $data_dokumen_temp, array(
				array(
					'kolom' => 'id_data_temp',
					'value' => $id_data_temp
				),
				array(
					'kolom' => 'tipe_referensi',
					'value' => 'kualifikasi_vendor'
				),
				array(
					'kolom' => 'na',
					'value' => 'n'
				)
			));


			//dokumen kualifikasi vendor
			$id_kualifikasi_spk	= array();
			$arr_kualifikasi = explode(',', $kualifikasi_spk);
			foreach ($arr_kualifikasi as $dt) {
				array_push($id_kualifikasi_spk, $dt);
			}
			$data_dokumen_kualifikasi	= array();
			$kualifikasi_vendor			= $this->dtransaksivendor->get_data_kualifikasi_dokumen(NULL, $id_kualifikasi_spk);
			foreach($kualifikasi_vendor as $dt2){
				$file_dokumen 			= 'file_dokumen_'.$dt2->id_master_dokumen;
				$nama_dokumen_hide		= 'nama_dokumen_hide_'.$dt2->id_master_dokumen;
				$tanggal_awal_kualifikasi	= 'tanggal_awal_kualifikasi_'.$dt2->id_master_dokumen;
				$tanggal_awal_kualifikasi	= (($_POST[$tanggal_awal_kualifikasi]!='')or($_POST[$tanggal_awal_kualifikasi]!=null)) ? date_create($_POST[$tanggal_awal_kualifikasi])->format('Y-m-d'): '1900-01-01';
				$tanggal_akhir_kualifikasi	= 'tanggal_akhir_kualifikasi_'.$dt2->id_master_dokumen;				
				$tanggal_akhir_kualifikasi	= (($_POST[$tanggal_akhir_kualifikasi]!='')or($_POST[$tanggal_akhir_kualifikasi]!=null)) ? date_create($_POST[$tanggal_akhir_kualifikasi])->format('Y-m-d'): '9999-12-31';
				$nama_dokumen				= str_replace(' ','_',$dt2->nama_dokumen)." - $id_data $tanggal_awal_kualifikasi sd $tanggal_akhir_kualifikasi";				
				$nama_dokumen				= str_replace(' ','_',$nama_dokumen);
				//upload file jenis vendor
				if($_FILES[$file_dokumen]['name'][0]!=''){
					$config['upload_path']   = str_replace('/vendor/','/folder/',$this->general->kirana_file_path($this->router->fetch_module()) . '/vendor_temp');
					$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
					$config['max_size']      = 0;
					
					$newname	= array($nama_dokumen);			
					$file		= $this->general->upload_files($_FILES[$file_dokumen], $newname, $config);
					$nama_file	= str_replace('_',' ',$newname[0]);
					$url_file	= str_replace("assets/", "", $file[0]['url']);
					if($file === NULL){
						$msg        = "Upload files error";
						$sts        = "NotOK";
						$return     = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					//batch data file jenis
					$data_file_jenis = array(
						'id_folder'     	=> $id_folder,
						'nama'     		    => $nama_file,
						'ukuran'        	=> $file[0]['size'],
						'tipe'          	=> pathinfo($url_file, PATHINFO_EXTENSION),
						'link'      		=> str_replace(' ','_',$url_file),
						'divisi_akses'    	=> NULL, 
						'departemen_akses'  => NULL,
						'divisi_write'    	=> NULL,
						'departemen_write'  => NULL,
						'level_akses'       => NULL,
						'level_write'       => NULL,
						'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'  	=> $datetime,
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime,
						'lihat'				=> 'y'  
					);
					$data_file_jenis = $this->dgeneral->basic_column("insert", $data_file_jenis);
					$this->dgeneral->insert("tbl_file_temp", $data_file_jenis);
					$id_file	= $this->db->insert_id();
					
					//batch data dokumen jenis
					$data_dokumen_jenis = array(
						"id_data"				=> $id_data,
						"id_data_temp"			=> $id_data_temp,
						"id_master_dokumen"		=> $dt2->id_master_dokumen,
						"id_referensi" 			=> 1,
						"tipe_referensi"		=> 'kualifikasi_spk',
						"id_folder" 			=> $id_folder,
						"id_file" 				=> $id_file,
						"tanggal_awal"		 	=> $tanggal_awal_kualifikasi,
						"tanggal_akhir"		 	=> $tanggal_akhir_kualifikasi,
						"status"			 	=> 1,
						"mandatory"			 	=> 'Mandatory',
					);
					$data_dokumen_jenis 		= $this->dgeneral->basic_column("insert", $data_dokumen_jenis);
					$this->dgeneral->insert("tbl_vendor_data_dokumen_temp", $data_dokumen_jenis);					
				}
			}	
		}
		
		//send emailxx
		$status_pengajuan = ($id_status!=99)? 'Menunggu Approval '.$next_user_role[0]->nama_role:'Completed';
		$content = '
			<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
				<thead>
					<tr>
						<th align="left" width="20%">Tanggal Pengajuan</th>
						<th align="left">: '.date('d.m.Y').'</th>
					</tr>
					<tr>
						<th align="left" width="20%">Nama Vendor</th>
						<th align="left">: '.$data_vendor[0]->nama.'</th>
					</tr>
					<tr>
						<th align="left" width="20%">Jenis Pengajuan</th>
						<th align="left">: Change Vendor</th>
					</tr>
					<tr>
						<th align="left" width="20%">Status Pengajuan</th>
						<th align="left">: '.$status_pengajuan.'</th>
					</tr>
				</thead>
			</table>';	
		$subject = "Change Master Vendor (".$data_vendor[0]->nama.")";	
		if($id_status!=99){
			foreach($next_user_role as $dt){
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM', $subject, $content, $dt->nama_karyawan);
				// $this->template_email_vendor($dt->email_karyawan,$subject,$content, $dt->nama_karyawan);
			}
		}else{
			$email_pengaju = $this->dtransaksivendor->get_data_karyawan(NULL, $data_vendor[0]->login_buat);
			$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
			// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama);
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$this->dsettingfolder->update_folder_path(null);
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	/*====================================================================*/
		
}