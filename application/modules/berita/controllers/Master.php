<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Notifikasi HRGA
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
	    $this->load->model('dmasterberita');
	}

	public function index(){
		show_404();
	}
	
	public function suka($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "List Berita Suka Cita";
		$data['title_form']  = "Form List Berita Suka Cita";
		$data['email'] 		 = $this->get_email('array', NULL);
		$data['berita'] 	 = $this->get_berita('array', NULL, NULL, 'n', 'suka');
		$this->load->view("master/suka", $data);	
	}
	
	public function duka($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "List Berita Duka";
		$data['title_form']  = "Form List Berita Duka";
		$data['email'] 		 = $this->get_email('array', NULL);
		$data['berita'] 	 = $this->get_berita('array', NULL, NULL, 'n', 'duka');
		$this->load->view("master/duka", $data);	
	}
	
	public function get_user(){
		return $this->general->get_user_autocomplete();
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL) {
		switch ($param) {
			case 'duka':
				$id_notif_berita  = (isset($_POST['id_notif_berita']) ? $this->generate->kirana_decrypt($_POST['id_notif_berita']) : NULL);
				$this->get_berita(NULL, $id_notif_berita, NULL, NULL);
				break;
			case 'penerima':
				$id_notif_berita  = (isset($_POST['id_notif_berita']) ? $this->generate->kirana_decrypt($_POST['id_notif_berita']) : NULL);
				$nik_duka 		  = (isset($_POST['nik_duka']) ? $_POST['nik_duka'] : NULL);
				$this->get_penerima(NULL, $id_notif_berita, $nik_duka);
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
		} else if (isset($_POST['type']) && $_POST['type'] == "delete") {
			$action = "delete_na_del";
		}
		if ($action) {
			switch ($param) {
				case 'duka':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_notif_berita", array(
						array(
							'kolom' => 'id_notif_berita',
							'value' => $this->generate->kirana_decrypt($_POST['id_notif_berita'])
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
			case 'duka':
				$this->save_duka($param);
				break;
			case 'suka':
				$this->save_suka($param);
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}
	//sent email
	public function sent_email() {
		$datetime  			= date("Y-m-d H:i:s");
		$id_notif_berita  	= (isset($_POST['id_notif_berita']) ? $this->generate->kirana_decrypt($_POST['id_notif_berita']) : NULL);
		$data				= $this->get_berita("open", $id_notif_berita, NULL, NULL);
		$color				= ($data[0]->gender=='Son')?"#57a1e0":"#dc14ba";
		//email
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
		
		try {
			$this->load->library('email', $config);
			$this->email->from('no-reply@kiranamegatara.com', 'KiranaKu');
			if($_POST['to']=='all'){
				$penerima	= $this->get_penerima('array', $this->generate->kirana_decrypt($_POST['id_notif_berita']), $_POST['nik_duka'],'y');
				foreach($penerima as $email) {    
					// $this->email->to($email->email);
					//save log
					$data_log   = array(
										'id_notif_berita'	=> $this->generate->kirana_decrypt($_POST['id_notif_berita']),
										'nik' 				=> $email->nik,
										'na'     			=> 'n',
										'del'     			=> 'n',
										'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
										'tanggal_buat'      => $datetime,
										'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
										'tanggal_edit'      => $datetime
									);
					$this->dgeneral->insert('tbl_notif_berita_log', $data_log);					
				}				
			}else{
				$this->email->to($_POST['to']);
				// $this->email->to('lukman.hakim@kiranamegatara.com');
			}
			

			// $filename = 'http://localhost:8080/dev/kiranaku/assets/file/berita/duka/2628_20190925.png';
			$filename = base_url()."".$data[0]->template;
			$this->email->attach($filename);
			$cid = $this->email->attachment_cid($filename);
			
			
			if($data[0]->jenis=='suka'){
				$this->email->subject('Berita Bahagia');
				$message  =	'<body>';
				$message  .=	'<style>#font_warna_1{color:'.$color.';font-size:25px;font-family: "Candara";}</style>';
				$message  .=	'<style>#font_warna_2{color:'.$color.';font-size:30px;font-family: "Candara";}</style>';
				$message  .=	'<style>#font_warna_3{color:'.$color.';font-size:45px;font-family: "Candara";}</style>';
				$message  .=	'<style>#font_nama{color:'.$color.';font-size:45px;font-family: "Harlow";}</style>';
				// $message .=	'<table border=1 background="'.$filename.'" 
				$message .=	'<table background="cid:'.$cid.'" 
								style="	background-repeat:no-repeat;
										background-align:center;
										box-shadow: 0 0 15px #ccc;
										margin-left: auto;
										margin-right: auto;"									
								width="800" height="600">';
				$message .=		'<tr>';
				$message .=			'<td width="50%">';
				$message .=			'</td>';
				$message .=			'<td valign="top" align="center">';
				$message .=				'<br><br>';
				$message .=				'<div id="font_warna_1">'.ucwords(strtolower($data[0]->editorial1)).'</div>';
				$message .=				'<div id="font_warna_2"><b>'.ucwords(strtolower($data[0]->nama_karyawan)).' '.$data[0]->gender.'</b></div>';
				$message .=				'<div id="font_warna_1">('.ucwords(strtolower($data[0]->posisi_karyawan)).')</div>';
				$message .=				'<br><br><br>';
				$message .=				'<div id="font_warna_3"><b>'.ucwords(strtolower($data[0]->nama_anak)).'</b></div>';
				$message .=				'<br><br><br><br><br><br><br><br>';
				$message .=				'<div id="font_warna_1">on '.$data[0]->name_days.', '.$data[0]->tanggal_konversi.'</div>';
				$message .=				'<br><br><br><br>';
				$message .=			'</td>';
				$message .=		'</tr>';
				$message .=	'</table>';
				$message .=	'</body>';
				// echo $message;				
				// exit();
			}else{
				$this->email->subject('Berita Duka');
				$message  =	'<body bgcolor="#E6E6FA">';
				$message .=	'<table background="cid:'.$cid.'" 
								style="	background-repeat:no-repeat;
										background-align:center;
										box-shadow: 0 0 15px #ccc;
										margin-left: auto;
										margin-right: auto;"									
								width="800" height="500">';
				$message .=		'<tr>';
				$message .=			'<td>';
				$message .=				'<br><br><div align="center" style="font-size:25px;">'.$data[0]->editorial1.'</div>';
				$message .=				'<div align="center" style="font-size:25px;">'.$data[0]->editorial2.'</div>';
				$message .=				'<div align="center" style="font-size:25px;">'.$data[0]->editorial3.'</div><br><br>';
				$message .=				'<div align="center" style="font-size:55px;"><b>'.$data[0]->nama_keluarga.'</b></div><br><br>';
				$message .=				'<div align="center" style="font-size:25px;"><b>'.$data[0]->status_keluarga.' dari '.$data[0]->gender_karyawan.' '.$data[0]->nama_karyawan.'</b></div>';
				$message .=				'<div align="center" style="font-size:25px;">('.$data[0]->posisi_karyawan.')</div><br>';
				$message .=				'<div align="center" style="font-size:25px;">Pada hari '.$data[0]->hari.', '.$data[0]->tanggal_konversi.'</div><br><br>';
				$message .=				'<div align="center" style="font-size:25px;">'.$data[0]->editorial4.'</div><br>';
				$message .=			'</td>';
				$message .=		'</tr>';
				$message .=	'</table>';
				$message .=	'</body>';
				// echo $message;
			}
			
			$this->email->message($message);
			$this->email->send();
			
			//update data sent
			if($_POST['to']=='all'){
				$datetime  = date("Y-m-d H:i:s");
				$data_row  = array(
								  'sent'     	=> 'y',
								  'login_edit'  => base64_decode($this->session->userdata("-id_user-")),
								  'tanggal_edit'=> $datetime
							 );
				// $this->dgeneral->update('tbl_notif_berita', $data_row, array( 
																	// array(
																		// 'kolom'=>'id_notif_berita',
																		// 'value'=>$id_notif_berita
																	// )
																// ));
			}
			$msg = "Email Berhasil dikirim";
			$sts = "OK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		} catch (Exception $e) {
			$msg = $e->getMessage();
			$sts = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
	}
	
	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_email($array = NULL, $id_email = NULL) {
		$email	= $this->dmasterberita->get_data_email("open", $id_email);
		if ($array) {
			return $email;
		} else {
			echo json_encode($email);
		}
	}
	
	private function get_berita($array = NULL, $id_notif_berita = NULL, $active = NULL, $deleted = NULL, $jenis = NULL) {
		$duka	= $this->dmasterberita->get_data_berita("open", $id_notif_berita, $active, $deleted, $jenis);
		$duka	= $this->general->generate_encrypt_json($duka, array("id_notif_berita"));
		if ($array) {
			return $duka;
		} else {
			echo json_encode($duka);
		}
	}

	private function get_penerima($array = NULL, $id_notif_berita = NULL, $nik_duka = NULL, $status_kirim = NULL) {
		$karyawan	= $this->dmasterberita->get_data_karyawan("open", $nik_duka);
		$penerima	= $this->dmasterberita->get_data_penerima("open", $nik_duka, $karyawan[0]->ho, $karyawan[0]->id_jabatan, $karyawan[0]->id_gedung, $id_notif_berita, $status_kirim);
		if ($array) {
			return $penerima;
		} else {
			echo json_encode($penerima);
		}
	}
	
	private function save_duka(){
		$datetime  	= date("Y-m-d H:i:s");
		$email		= (isset($_POST['email']) ? implode(",", $_POST['email']) : NULL);
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		//==========================UPLOAD TTD==============================//
		if(count($_FILES['gambar']['name']) > 1){
			$msg        = "You can only upload maximum 1 file";
			$sts        = "NotOK";
			$return     = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		if($_FILES['gambar']['name'][0]!=''){
			$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/duka';
			$config['allowed_types'] 	= 'png';			
			$newname 					= array($_POST['nik'].'_'.date('Ymd'));			
			$gambar						= $this->general->upload_files($_FILES['gambar'], $newname, $config);
			$url_gambar					= $gambar[0]['url'];
			if($gambar === NULL){
				$msg        = "Upload files error";
				$sts        = "NotOK";
				$return     = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
		}else{
			$url_gambar					= $_POST['gambar_url'];
		}
		//========================================================================//
					
		if(isset($_POST['id_notif_berita']) && $_POST['id_notif_berita'] != ""){
			$id_notif_berita = $this->generate->kirana_decrypt($_POST['id_notif_berita']);	
			$data_row   = array(
							  'jenis' 			=> 'duka',
							  'template' 		=> $url_gambar,
							  'editorial1'     	=> $_POST['editorial1'],
							  'editorial2'     	=> $_POST['editorial2'],
							  'editorial3'     	=> $_POST['editorial3'],
							  'editorial4'     	=> $_POST['editorial4'],
							  'nama_keluarga'	=> $_POST['nama_keluarga'],
							  'status_keluarga'	=> $_POST['status_keluarga'],
							  'nik'				=> $_POST['nik'],
							  'email'			=> $email,
							  'tanggal'			=> $_POST['tanggal'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_notif_berita', $data_row, array( 
																array(
																	'kolom'=>'id_notif_berita',
																	'value'=>$id_notif_berita
																)
															));
			
		}else{
			$data_row   = array(
								'jenis' 			=> 'duka',
								'template' 			=> $url_gambar,
								'editorial1'     	=> $_POST['editorial1'],
								'editorial2'     	=> $_POST['editorial2'],
								'editorial3'     	=> $_POST['editorial3'],
								'editorial4'     	=> $_POST['editorial4'],
								'nama_keluarga'		=> $_POST['nama_keluarga'],
								'status_keluarga'	=> $_POST['status_keluarga'],
								'nik'				=> $_POST['nik'],
								'email'				=> $email,
								'tanggal'			=> $_POST['tanggal'],
								'sent'     			=> 'n',
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_notif_berita', $data_row);
		}

		if($this->dgeneral->status_transaction() === FALSE){
			$this->dgeneral->rollback_transaction();
			$msg    = "Periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
		}else{
			$this->dgeneral->commit_transaction();
			$msg    = "Transaksi Berhasil";
			$sts    = "OK";
		}	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}
	
	private function save_suka(){
		$datetime  	= date("Y-m-d H:i:s");
		$email		= (isset($_POST['email']) ? implode(",", $_POST['email']) : NULL);
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		//==========================UPLOAD TTD==============================//
		if(count($_FILES['gambar']['name']) > 1){
			$msg        = "You can only upload maximum 1 file";
			$sts        = "NotOK";
			$return     = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		if($_FILES['gambar']['name'][0]!=''){
			$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/suka';
			$config['allowed_types'] 	= 'png';			
			$newname 					= array($_POST['nik'].'_'.date('Ymd'));			
			$gambar						= $this->general->upload_files($_FILES['gambar'], $newname, $config);
			$url_gambar					= $gambar[0]['url'];
			if($gambar === NULL){
				$msg        = "Upload files error";
				$sts        = "NotOK";
				$return     = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
		}else{
			$url_gambar					= $_POST['gambar_url'];
		}
		//========================================================================//
					
		if(isset($_POST['id_notif_berita']) && $_POST['id_notif_berita'] != ""){
			$id_notif_berita = $this->generate->kirana_decrypt($_POST['id_notif_berita']);	
			$data_row   = array(
							  'jenis' 			=> 'suka',
							  'template' 		=> $url_gambar,
							  'editorial1'     	=> $_POST['editorial1'],
							  'nama_anak'		=> $_POST['nama_anak'],
							  'gender'			=> $_POST['gender'],
							  'nik'				=> $_POST['nik'],
							  'email'			=> $email,
							  'tanggal'			=> $_POST['tanggal'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_notif_berita', $data_row, array( 
																array(
																	'kolom'=>'id_notif_berita',
																	'value'=>$id_notif_berita
																)
															));
			
		}else{
			$data_row   = array(
								'jenis' 			=> 'suka',
								'template' 			=> $url_gambar,
								'editorial1'     	=> $_POST['editorial1'],
								'nama_anak'			=> $_POST['nama_anak'],
								'gender'			=> $_POST['gender'],
								'nik'				=> $_POST['nik'],
								'email'				=> $email,
								'tanggal'			=> $_POST['tanggal'],
								'sent'     			=> 'n',
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_notif_berita', $data_row);
		}

		if($this->dgeneral->status_transaction() === FALSE){
			$this->dgeneral->rollback_transaction();
			$msg    = "Periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
		}else{
			$this->dgeneral->commit_transaction();
			$msg    = "Transaksi Berhasil";
			$sts    = "OK";
		}	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}
	/*====================================================================*/
		
}