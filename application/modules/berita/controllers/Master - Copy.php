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
	
	public function duka($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "List Berita Duka Cita";
		$data['title_form']  = "Form List Berita Duka Cita";
		$data['template']	 = $this->get_template('array', NULL, NULL, NULL,'duka');
		$data['duka'] 	 	 = $this->get_duka('array', NULL, NULL, NULL);
		$this->load->view("master/duka", $data);	
	}
	
	public function get_user(){
		return $this->general->get_user_autocomplete();
	}
	
	//sent email
	public function sent_email2() {
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
                $this->email->to('lukman.hakim@kiranamegatara.com');
                $this->email->subject('Berita Duka');

				$filename = 'http://localhost:8080/dev/kiranaku/assets/file/berita/duka/duka.PNG';
				$this->email->attach($filename);
				$cid = $this->email->attachment_cid($filename);
				$message =	'<body background="cid:'.$cid.'"><table><tr><td>aa</td></tr></table></body>';
				$this->email->message($message);
                $this->email->send();
				
            } catch (Exception $e) {
                $msg = $e->getMessage();
                $sts = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }
		
		// try{
            // $config['protocol'] = 'smtp';
            // $config['smtp_host'] = 'mail.kiranamegatara.com';
            // $config['smtp_user'] = 'no-reply@kiranamegatara.com';
            // $config['smtp_pass'] = '1234567890';
            // $config['smtp_port'] = '465';
            // $config['smtp_crypto'] = 'ssl';
            // $config['charset'] = 'iso-8859-1';
            // $config['wordwrap'] = true;
            // $config['mailtype'] = 'html';
		
		// $this->load->library('email', $config);
		
		// // $filename = 'https://www.kiranamegatara.com/theme/img/history1a.jpg';
		// // $this->email->attach($filename);
		// // $this->email->to('lukman.hakim@kiranamegatara.com');
		// // $this->email->to('octe.nugroho@kiranamegatara.com');
		// // $cid = $this->email->attachment_cid($filename);
		// $this->email->message('aa');
		// $this->email->send();
		// echo"ok";
		// } catch (Exception $e) {
			// var_dump($e->getMessage());
		// }
	}
	public function sent_email() {
		$id_notif_dukacita  = (isset($_POST['id_notif_dukacita']) ? $this->generate->kirana_decrypt($_POST['id_notif_dukacita']) : NULL);
		$id_notif_dukacita  = 2;
		$data				= $this->get_duka("open", $id_notif_dukacita, NULL, NULL);

		$email           = 'lukman.hakim@kiranamegatara.com';
		$email_no_domain = explode("@", $email)[0];//str_replace("@kiranamegatara.com", "", $email);
		if (strpos($email, 'kiranamegatara.com') !== false && strlen($email_no_domain) > 0) {
			$filename = 'https://www.kiranamegatara.com/theme/img/history1a.jpg';
			$this->email->attach($filename);
			$cid = $this->email->attachment_cid($filename);
			// $this->email->message('aaa<img src="cid:'. $cid .'" alt="photo1" />bbb');
			
			$message = "<html>";
			$message .= "<body>";
			$message .= "<style>.background{align:'center';background-repeat:no-repeat;background-image: url($cid);}</style>";
			// $message .= "<div class='background' align='center'>";
			// $message .= 	"<div>aaa</div>";
			// $message .= "</div>";
			
			$message .= "<table align='center' class='background' cellpadding='0' width='100%'>";
			$message .= "<tr>";
			$message .= 	"<td>";
			$message .= 		"<table align='center'><tr><td style='color:red'>".$data[0]->editorial1."</td></tr></table>";
			$message .= 		"<table><tr><td style='color:red'>".$data[0]->editorial2."</td></tr></table>";
			$message .= 		"<table><tr><td style='color:red'>".$data[0]->editorial3."</td></tr></table>";
			$message .= 		"<table><tr><td>aaa</td></tr></table>";
			$message .= 	"</td>";
			$message .= "</tr>";
			$message .= "</table>";
			$message .= "</body>";
			$message .= "</html>";
			// echo $message; 

			$this->general->send_email("Konfirmasixx Reset Password Untuk Login KiranaKu", "KiranaKu", $email, "", $message);
			$msg = "Silahkan check email Anda";
			$sts = "OK";
		}
		else {
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		exit();
	}

	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL) {
		switch ($param) {
			case 'duka':
				$id_notif_dukacita  = (isset($_POST['id_notif_dukacita']) ? $this->generate->kirana_decrypt($_POST['id_notif_dukacita']) : NULL);
				$this->get_duka(NULL, $id_notif_dukacita, NULL, NULL);
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
				case 'duka':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_notif_dukacita", array(
						array(
							'kolom' => 'id_notif_dukacita',
							'value' => $this->generate->kirana_decrypt($_POST['id_notif_dukacita'])
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
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}
	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_template($array = NULL, $id_notif_template = NULL, $active = NULL, $deleted = NULL, $tipe = NULL) {
		$template	= $this->dmasterberita->get_data_template("open", $id_notif_template, $active, $deleted, $tipe);
		if ($array) {
			return $template;
		} else {
			echo json_encode($template);
		}
	}
	private function get_duka($array = NULL, $id_notif_dukacita = NULL, $active = NULL, $deleted = NULL) {
		$duka	= $this->dmasterberita->get_data_duka("open", $id_notif_dukacita, $active, $deleted);
		$duka	= $this->general->generate_encrypt_json($duka, array("id_notif_dukacita"));
		if ($array) {
			return $duka;
		} else {
			echo json_encode($duka);
		}
	}
	private function save_duka(){
		$datetime  = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		if(isset($_POST['id_notif_dukacita']) && $_POST['id_notif_dukacita'] != ""){
			$id_notif_dukacita = $this->generate->kirana_decrypt($_POST['id_notif_dukacita']);	
			$data_row   = array(
							  'id_notif_template'=> $_POST['id_notif_template'],
							  'editorial1'     	=> $_POST['editorial1'],
							  'editorial2'     	=> $_POST['editorial2'],
							  'editorial3'     	=> $_POST['editorial3'],
							  'editorial4'     	=> $_POST['editorial4'],
							  'nama_keluarga'	=> $_POST['nama_keluarga'],
							  'status_keluarga'	=> $_POST['status_keluarga'],
							  'nik'				=> $_POST['nik'],
							  'tanggal'			=> $_POST['tanggal'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_notif_dukacita', $data_row, array( 
																array(
																	'kolom'=>'id_notif_dukacita',
																	'value'=>$id_notif_dukacita
																)
															));
			
		}else{
			$data_row   = array(
								'id_notif_template'	=> $_POST['id_notif_template'],
								'editorial1'     	=> $_POST['editorial1'],
								'editorial2'     	=> $_POST['editorial2'],
								'editorial3'     	=> $_POST['editorial3'],
								'editorial4'     	=> $_POST['editorial4'],
								'nama_keluarga'		=> $_POST['nama_keluarga'],
								'status_keluarga'	=> $_POST['status_keluarga'],
								'nik'				=> $_POST['nik'],
								'tanggal'			=> $_POST['tanggal'],
								'sent'     			=> 'n',
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_notif_dukacita', $data_row);
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