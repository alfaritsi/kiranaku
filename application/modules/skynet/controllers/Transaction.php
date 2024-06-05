	<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	/*
@application  	: SKYNET
@author     	: Syah Jadianto (8604)
@contributor  	: 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

	class Transaction extends MX_Controller
	{
		function __construct()
		{
			parent::__construct();
			$this->load->model('dmasterskynet');
			$this->load->model('dtransactionskynet');
		}

		public function index()
		{
			show_404();
		}

		public function ticket($param)
		{
			switch ($param) {
				case 'user':
					$this->user_ticket();
					break;
				case 'admin':
					$this->admin_ticket();
					break;
				case 'excel':
					$this->admin_ticket_excel();
					break;

				default:
					$return = array();
					echo json_encode($return);
					break;
			}
		}

		private function user_ticket()
		{
			if ($_GET) {
				$status = $_GET['key'];
			} else {
				$status = "";
				$this->general->check_access();
			}
			$data['title']    			= "SKYNET - IT Helpdesk Request";
			$data['title_form']    		= "";
			$data['module']     		= $this->router->fetch_module();
			$data['user']     			= $this->general->get_data_user();

			$from 						= date('d.m.Y', strtotime('-3 month'));
			$to 						= date("d.m.Y");

			$filterstatus    			= (empty($_POST['filterstatus'])) ? NULL : $this->generate->kirana_decrypt($_POST['filterstatus']);
			$filterkategori    			= (empty($_POST['filterkategori'])) ? NULL : $this->generate->kirana_decrypt($_POST['filterkategori']);
			$filterseverity    			= (empty($_POST['filterseverity'])) ? NULL : $this->generate->kirana_decrypt($_POST['filterseverity']);
			$filterfrom    				= (empty($_POST['filterfrom'])) ? $from : $_POST['filterfrom'];
			$filterto    				= (empty($_POST['filterto'])) ? $to : $_POST['filterto'];

			$data['status'] 			= $this->dmasterskynet->get_data_status();
			$data['kategori'] 			= $this->dmasterskynet->get_data_kategori();
			$data['severity'] 			= $this->dmasterskynet->get_data_severity();

			// if(base64_decode($this->session->userdata("-ho-")) == 'y'){
			//       	$lokasi 				= "KMTR";
			// }else{
			// 	$lokasi					= base64_decode($this->session->userdata("-id_gedung-"));
			//       	$filteragent			= " AND CHARINDEX('".base64_decode($this->session->userdata("-id_user-"))."', ht.assignto) > 0";
			// }
			$lokasi						= base64_decode($this->session->userdata("-ho-")) == "y" ? "KMTR" : base64_decode($this->session->userdata("-gsber-"));

			if ($status == "3" || $status == "2") {
				$data['ticket'] 		= $this->dtransactionskynet->get_data_skynet(NULL, $lokasi, NULL, $status, NULL, NULL, NULL, NULL, NULL);
			} else {
				if (base64_decode($this->session->userdata("-ho-")) == "y") {
					$filteragent		= " AND tbl_hd_ticket.login_buat = " . base64_decode($this->session->userdata("-id_user-"));
				} else {
					$filteragent		= NULL;
				}
				$data['ticket'] 		= $this->dtransactionskynet->get_data_skynet(NULL, $lokasi, NULL, $filterstatus, $filterkategori, $filterseverity, $filterfrom, $filterto, $filteragent);
			}

			$data['filterstatus'] 		= $filterstatus;
			$data['filterkategori'] 	= $filterkategori;
			$data['filterseverity'] 	= $filterseverity;
			$data['filterfrom'] 		= $filterfrom;
			$data['filterto'] 			= $filterto;

			$this->load->view("transaksi/user_ticket", $data);
		}

		private function admin_ticket()
		{
			if ($_GET) {
				$status = $_GET['key'];
			} else {
				$status = "";
				$this->general->check_access();
			}
			$data['title']    			= "SKYNET - IT Helpdesk Request";
			$data['title_form']    		= "";
			$data['module']     		= $this->router->fetch_module();
			$data['user']     			= $this->general->get_data_user();

			$from 						= date('d.m.Y', strtotime('-3 month'));
			$to 						= date("d.m.Y");

			$filterpabrik    			= (empty($_POST['filterpabrik'])) ? NULL : $_POST['filterpabrik'];
			$filterstatus    			= (empty($_POST['filterstatus'])) ? NULL : $this->generate->kirana_decrypt($_POST['filterstatus']);
			$filterkategori    			= (empty($_POST['filterkategori'])) ? NULL : $this->generate->kirana_decrypt($_POST['filterkategori']);
			$filterfrom    				= (empty($_POST['filterfrom'])) ? $from : $_POST['filterfrom'];
			$filterto    				= (empty($_POST['filterto'])) ? $to : $_POST['filterto'];

			$data['status'] 			= $this->dmasterskynet->get_data_status();
			$data['kategori'] 			= $this->dmasterskynet->get_data_kategori();

			if (base64_decode($this->session->userdata("-ho-")) == 'y') {
				$lokasi 				= "KMTR";
				$pb_lokasi 				= NULL;
			} else {
				$lokasi					= base64_decode($this->session->userdata("-id_gedung-"));
				$pb_lokasi 				= base64_decode($this->session->userdata("-id_gedung-"));
			}
			$data['pabrik'] 			= $this->dmasterskynet->get_data_pabrik($pb_lokasi);
			// var_dump($status."---xxxxxxxxxxxxxx");
			// var_dump($_GET);
			// exit();
			// if($status == "2"){
			if (base64_decode($this->session->userdata("-ho-")) != 'y') {
				$filteragent			= " AND tbl_hd_ticket.id_hd_agent = " . base64_decode($this->session->userdata("-nik-"));

				$data['ticket'] 		= $this->dtransactionskynet->get_data_skynet(NULL, NULL, NULL, $status, NULL, NULL, NULL, NULL, $filteragent);
			} else {
				$data['ticket'] 		= $this->dtransactionskynet->get_data_skynet(NULL, NULL, $filterpabrik, $filterstatus, $filterkategori, NULL, $filterfrom, $filterto, NULL);
			}


			$data['filterpabrik'] 		= $filterpabrik;
			$data['filterstatus'] 		= $filterstatus;
			$data['filterkategori'] 	= $filterkategori;
			$data['filterfrom'] 		= $filterfrom;
			$data['filterto'] 			= $filterto;

			$this->load->view("transaksi/admin_ticket", $data);
		}

		private function admin_ticket_excel()
		{
			// $this->general->check_access();
			$data['title']    			= "SKYNET - IT Helpdesk Request";
			$data['title_form']    		= "";
			$data['module']     		= $this->router->fetch_module();
			$data['user']     			= $this->general->get_data_user();

			$from 						= date('d.m.Y', strtotime('-3 month'));
			$to 						= date("d.m.Y");

			$filterpabrik    			= (empty($_GET['filterpabrik'])) ? NULL : $_GET['filterpabrik'];
			$filterstatus    			= (empty($_GET['filterstatus'])) ? NULL : $this->generate->kirana_decrypt($_GET['filterstatus']);
			$filterkategori    			= (empty($_GET['filterkategori'])) ? NULL : $this->generate->kirana_decrypt($_GET['filterkategori']);
			$filterfrom    				= (empty($_GET['filterfrom'])) ? $from : $_GET['filterfrom'];
			$filterto    				= (empty($_GET['filterto'])) ? $to : $_GET['filterto'];

			$data['pabrik'] 			= $this->dmasterskynet->get_data_pabrik();
			$data['status'] 			= $this->dmasterskynet->get_data_status();
			$data['kategori'] 			= $this->dmasterskynet->get_data_kategori();

			if (base64_decode($this->session->userdata("-ho-")) == 'y') {
				$lokasi 				= "KMTR";
			} else {
				$lokasi					= base64_decode($this->session->userdata("-id_gedung-"));
			}

			$filterpabrik				= explode(",", $filterpabrik[0]);

			$data['ticket'] 			= $this->dtransactionskynet->get_data_skynet(NULL, NULL, $filterpabrik, $filterstatus, $filterkategori, NULL, $filterfrom, $filterto, NULL);

			$data['filterpabrik'] 		= $filterpabrik;
			$data['filterstatus'] 		= $filterstatus;
			$data['filterkategori'] 	= $filterkategori;
			$data['filterfrom'] 		= $filterfrom;
			$data['filterto'] 			= $filterto;

			$this->load->view("transaksi/admin_ticket_excel", $data);
		}



		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//
		public function get_master_baku_mutu()
		{
			if (isset($_GET['q'])) {
				$data       = $this->dmastershe->get_data_bakumutu(NULL, $_GET['q']);
				$data_json  = array(
					"total_count" => count($data),
					"incomplete_results" => false,
					"items" => $data
				);
				echo json_encode($data_json);
			}
		}

		public function get_data($param)
		{
			switch ($param) {
				case 'close_ticket':
					$this->get_data_ticket();
					break;
				case 'pending_user':
					$this->get_data_ticket();
					break;
				case 'history_ticket':
					$this->get_data_history();
					break;
				case 'attachment_ticket':
					$this->get_data_ticket();
					break;

				default:
					$return = array();
					echo json_encode($return);
					break;
			}
		}

		public function set_data($action, $param)
		{
			switch ($param) {
				case 'close_ticket':
					$this->set_status_ticket($action);
					break;
				case 'pending_user':
					$this->set_status_ticket($action);
					break;

				default:
					$return = array();
					echo json_encode($return);
					break;
			}
		}

		public function save($param)
		{
			switch ($param) {
				case 'user_ticket':
					$this->save_user_ticket();
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
		private function get_data_ticket()
		{
			$this->general->connectDbPortal();
			$id = $this->generate->kirana_decrypt($_POST['id']);

			$tiket = $this->dtransactionskynet->get_data_skynet($id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

			$this->general->closeDb();
			echo json_encode($tiket);
		}

		private function get_data_history()
		{
			$this->general->connectDbPortal();
			$id = $this->generate->kirana_decrypt($_POST['id']);

			$history = $this->dtransactionskynet->get_data_history($id);

			$this->general->closeDb();
			echo json_encode($history);
		}

		private function set_status_ticket($action)
		{
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();

			$id 	= $this->generate->kirana_decrypt($_POST['id']);
			$tiket 	= $this->dtransactionskynet->get_data_skynet($id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
			$tiket_his 	= $this->dtransactionskynet->get_data_history($id, "DESC");
			// var_dump($tiket_his[0]);
			// exit();
			$nomor 	= $this->dtransactionskynet->get_data_counthistory($tiket[0]->id_hd_ticket);

			// Insert History
			$uploaded1 		= "";
			$uploadError1 	= "";
			$uploaddir  	= realpath('./') . '/assets/file/skynet';
			$datalampiran = array();
			if ($_FILES) {
				if (!file_exists($uploaddir)) {
					mkdir($uploaddir, 0777, true);
				}

				$config['upload_path']          = $uploaddir;
				$config['allowed_types']        = 'jpg|jpeg|png|bmp';
				// $config['max_size']             = 100;
				// $config['max_width']            = 1024;
				// $config['max_height']           = 768;

				$this->load->library('upload', $config);

				$temp = explode(".", $_FILES['file']['name']);
				$extension = end($temp);
				$filename = $tiket[0]->no_ticket . "_" . $nomor[0]->nomor . "." . $extension;

				$_FILES['gambar']['name'] = $filename;
				$_FILES['gambar']['type'] = $_FILES['file']['type'];
				$_FILES['gambar']['tmp_name'] = $_FILES['file']['tmp_name'];
				$_FILES['gambar']['error'] = $_FILES['file']['error'];
				$_FILES['gambar']['size'] = $_FILES['file']['size'];


				if (!$this->upload->do_upload('gambar')) {
					$uploadError1 = $this->upload->display_errors();
				} else {
					$upload_data1 = $this->upload->data();
					$uploaded1 = 'assets/file/skynet/' . $filename;
				}

				if ($uploaded1 != "") {
					$datalampiran = array('gambar' => $uploaded1);
				}
			}

			$data_row   = array(
				'id_hd_status'    => $_POST['status'],
				'id_hd_ticket'    => $tiket[0]->id_hd_ticket,
				'remark'    		=> $_POST['keterangan'],
				'title'    		=> $tiket[0]->title,
				'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_buat'    => $datetime,
				'tanggal_awal'    => date("Y-m-d h:i:s a", strtotime($tiket_his[0]->tglbuat_lengkap)),
				'na'    			=> 'n',
				'del'        		=> 'n'
			);

			// set for estimate respon time
			if ($_POST['status'] == 2) {
				$data_confirm = array(
					'open_tiket'    	=> '0',
					'open_tiket_end'  => $datetime,
				);
			} else {
				$data_confirm = [];
			}

			$data_row 	= array_merge($data_row, $datalampiran);
			$this->dgeneral->insert('tbl_hd_history', $data_row);
			
			//lha
			if($_POST['status']==3){
				$data_row   = array(
					'downtime_tiket_begin'	=> (isset($_POST['mulai']) ? $_POST['mulai'] : NULL),
					'downtime_tiket_end'	=> (isset($_POST['selesai']) ? $_POST['selesai'] : NULL),
					'id_hd_status'   		=> $_POST['status'],
					'login_edit'   			=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'   		=> $datetime
				);
			}else{
				$data_row   = array(
					'id_hd_status'    		=> $_POST['status'],
					'login_edit'   			=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'   		=> $datetime
				);
			}
			$data_row 	= array_merge($data_row, $data_confirm);
			$this->dgeneral->update('tbl_hd_ticket', $data_row, array(
				array(
					'kolom' => 'id_hd_ticket',
					'value' => $id
				)
			));


			//send email
			setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
			$config['protocol']  	= 'smtp';
			$config['smtp_host'] 	= 'mail.kiranamegatara.com';
			$config['smtp_user'] 	= 'no-reply@kiranamegatara.com';
			$config['smtp_pass'] 	= '1234567890';
			$config['smtp_port'] 	= '465';
			$config['smtp_crypto'] 	= 'ssl';
			$config['charset'] 		= 'iso-8859-1';
			$config['wordwrap'] 	= true;
			$config['mailtype'] 	= 'html';

			try {
				if ($_POST['status'] == 3) { //set pending user
					$this->load->library('email', $config);
					$this->email->from('no-reply@kiranamegatara.com', 'KiranaKu');
					$this->email->to($tiket[0]->email);
					$this->email->subject('Pending User Ticket Skynet');
					$message = "
				<html> 
				  <body style='font-size:11pt;font-family:Calibri'>
					<b>Dear " . $tiket[0]->nama . ",</b><br><br>
					Ticket Skynet Anda:<br><br>
					<table width='600'>
						<tr><td width='30'></td><td width='90'>No</td><td>: " . $tiket[0]->lokasi . "" . str_pad($tiket[0]->nomor, 4, '0', STR_PAD_LEFT) . "</td></tr>
						<tr><td></td><td>Problem</td><td>: " . $tiket[0]->title . "</td></tr>
						<tr><td></td><td>Tgl Ticket</td><td>: " . $tiket[0]->tanggal_buat . "</td></tr>
						<tr><td></td><td>Remark</td><td>: " . $_POST['keterangan'] . "</td></tr>
						<tr><td></td><td>Status</td><td>: Pending User</td></tr>
					</table>
					<br>
					Telah selesai dikerjakan. Bila masih ada keluhan silahkan hubungi " . $tiket[0]->agent . ", jika tidak ada silahkan close ticket Anda. 
					<br>
					Jika dalam waktu 2 (dua) hari sejak status pending user tidak ada konfirmasi status ticket dari user, maka system akan melakukan auto close terhadap ticket tersebut.
					<br><br><br>
					Terimakasih,<br>
					<b>" . $tiket[0]->agent . "</b>
				  </body> 
				</html>";
					$this->email->message($message);
					$this->email->send();
				}

				if ($this->dgeneral->status_transaction() === FALSE) {
					$this->dgeneral->rollback_transaction();
					$msg    = "Periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg    = "Data berhasil diupdate";
					$sts    = "OK";
				}
			} catch (Exception $e) {
				$msg = $e->getMessage();
				$sts = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			//sampe sini send email

			// if($this->dgeneral->status_transaction() === FALSE){
			// $this->dgeneral->rollback_transaction();
			// $msg    = "Periksa kembali data yang dimasukkan";
			// $sts    = "NotOK";
			// }else{
			// $this->dgeneral->commit_transaction();
			// $msg    = "Data berhasil diupdate";	
			// $sts    = "OK";
			// }	

			$return = array('sts' => $sts, 'msg' => $msg);
			$this->general->closeDb();
			echo json_encode($return);
		}

		private function save_user_ticket()
		{
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();

			$this->dgeneral->begin_transaction();

			// $user 			= base64_decode($this->session->userdata("-ho-")) == "y" ? base64_decode($this->session->userdata("-id_user-")) : $_POST['user'];
			$user 			= base64_decode($this->session->userdata("-id_user-"));
			$nomor 			= $this->dtransactionskynet->get_data_lastnumber();
			$gsber 			= base64_decode($this->session->userdata("-ho-")) == "y" ? "KMTR" : base64_decode($this->session->userdata("-id_gedung-"));
			if (substr(base64_decode($this->session->userdata("-ho-")), 0, 1) == "5" || substr(base64_decode($this->session->userdata("-ho-")), 0, 1) == "9") {
				$gsber 		= "KTP";
			}
			$lokasi 		= $this->dmasterskynet->get_data_agent($gsber);
																				 
																	  
		 
			$auto_agent 	= $this->dmasterskynet->get_data_auto_agent($gsber);
	

			$uploaded1 		= "";
			$uploadError1 	= "";
			$uploaddir  	= realpath('./') . '/assets/file/skynet';

			if (!file_exists($uploaddir)) {
				mkdir($uploaddir, 0777, true);
			}

			$config['upload_path']          = $uploaddir;
			$config['allowed_types']        = 'jpg|jpeg|png|bmp';
			// $config['max_size']             = 100;
			// $config['max_width']            = 1024;
			// $config['max_height']           = 768;

			$this->load->library('upload', $config);

			$temp = explode(".", $_FILES['file']['name']);
			$extension = end($temp);
			$filename = $nomor[0]->nomor . "." . $extension;

			$_FILES['gambar']['name'] = $filename;
			$_FILES['gambar']['type'] = $_FILES['file']['type'];
			$_FILES['gambar']['tmp_name'] = $_FILES['file']['tmp_name'];
			$_FILES['gambar']['error'] = $_FILES['file']['error'];
			$_FILES['gambar']['size'] = $_FILES['file']['size'];

			if (file_exists(realpath('./') . "/" . "assets/file/skynet/" . $filename)) {
				unlink(realpath('./') . "/" . "assets/file/skynet/" . $filename);
			}

			if (!$this->upload->do_upload('gambar')) {
				$uploadError1 = $this->upload->display_errors();
			} else {
				$upload_data1 = $this->upload->data();
				$uploaded1 = 'assets/file/skynet/' . $filename;
			}

			$datalampiran = array();
			if ($uploaded1 != "") {
				$datalampiran = array('gambar' => $uploaded1);
			}

			$tanggal = $datetime;
			if ($nomor && $lokasi) {
				$tanggal = $auto_agent[0]->tanggal !== date("Y-m-d") || strtotime($tanggal) < strtotime(date_format(date_create($auto_agent[0]->tanggal . ' ' . $auto_agent[0]->SOBEG), 'Y-m-d H:i:s')) ? $auto_agent[0]->tanggal . ' ' . $auto_agent[0]->SOBEG : $tanggal;
					
																				  
				$data_row   = array(
					'nomor'     			=> $nomor[0]->nomor,
					'id_hd_status'    	=> 1,
								
												
												   
									   
											   
							   
												  
											
								   
								  
					 
					  
											  
												   
						   
											
										   
	   
		  
						 
										
							  
					'login_buat'    		=> $user,
					'id_hd_kategori'    	=> $_POST['kategori'],
					'id_hd_subkategori'   => $_POST['subkategori'],
					'title'    			=> $_POST['title'],
					'keterangan'    		=> $_POST['keterangan'],
					'id_hd_severity'    	=> 1,
					'assignto'        	=> $lokasi[0]->agent,
					'lokasi'      		=> $lokasi[0]->lokasi,
					'tanggal_buat'		=> $datetime,
					'tanggal_awal'		=> $tanggal,
					'na'					=> 'n',
					'del'					=> 'n',
					'id_hd_agent'			=> $auto_agent[0]->agent,
					'id_hd_agent_order'	=> $auto_agent[0]->agent,
					'open_tiket'			=> '1',
					'open_tiket_begin'	=> $tanggal
				);
	 
			} else {
				$return = array('sts' => 'notOK', 'msg' => 'Gagal generate Agent/Nomor');
				echo json_encode($return);
				exit();
			}

			$data_row = array_merge($data_row, $datalampiran);
			$this->dgeneral->insert('tbl_hd_ticket', $data_row);

			//send email
			$data_kategori		= $this->dtransactionskynet->get_data_kategori(NULL, $_POST['kategori']);
			$data_subkategori	= $this->dtransactionskynet->get_data_subkategori(NULL, $_POST['subkategori']);
			$data_user			= $this->dtransactionskynet->get_data_user(NULL, base64_decode($this->session->userdata("-id_user-")));
			$no_ticket			= $lokasi[0]->lokasi . "" . str_pad($nomor[0]->nomor, 4, '0', STR_PAD_LEFT);
			setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
			$config['protocol']  	= 'smtp';
			$config['smtp_host'] 	= 'mail.kiranamegatara.com';
			$config['smtp_user'] 	= 'no-reply@kiranamegatara.com';
			$config['smtp_pass'] 	= '1234567890';
			$config['smtp_port'] 	= '465';
			$config['smtp_crypto'] 	= 'ssl';
			$config['charset'] 		= 'iso-8859-1';
			$config['wordwrap'] 	= true;
			$config['mailtype'] 	= 'html';

			$this->load->library('email', $config);
			$this->email->from('no-reply@kiranamegatara.com', 'KiranaKu');
			// $this->email->to($auto_agent[0]->email);
			$this->email->to("it-support-ho@kiranamegatara.com");
			// $this->email->to("lukman.hakim@kiranamegatara.com");
			// $this->email->to("ADY.PUDRIANSYAH@KIRANAMEGATARA.COM");
			// $this->email->to("DJAKA.PRASETYA@KIRANAMEGATARA.COM");
			$this->email->subject('Pengajuan Tiket Skynet baru Nomor ' . $no_ticket);
			$message = "
		<html> 
		  <body style='font-size:11pt;font-family:Calibri'>
			<b>Dear " . $auto_agent[0]->nama . ",</b><br><br>
			Email ini menandakan bahwa ada Pengajuan Tiket Skynet baru yang membutuhkan perhatian Anda<br><br>
			<table width='600'>
				<tr><td width='30'></td><td width='90'>Nomor Ticket</td><td>: $no_ticket</td></tr>
				<tr><td></td><td>User</td><td>: " . $data_user[0]->nik_karyawan . " - " . $data_user[0]->nama_karyawan . "</td></tr>
				<tr><td></td><td>Department</td><td>: " . $data_user[0]->divisi_karyawan . "</td></tr>
				<tr><td></td><td>Jenis Problem</td><td>: " . $data_kategori[0]->kategori . "</td></tr>
				<tr><td></td><td>Detail Ticket</td><td>: " . $data_subkategori[0]->nama . "</td></tr>
			</table>
			<br>
			Selanjutnya anda dapat melakukan follow up Pengajuan Tiket Skynet tersebut melalui aplikasi Skynet di Portal Kiranaku.
			<br>
			<center>
			<h4>Click Tombol dibawah ini untuk login pada Portal Kiranaku</h4>
			<a href='http://kiranaku.kiranamegatara.com/skynet/transaction/ticket/admin'><button type='button' class='btn btn-default'>Login</button></a>
			</center>
			<br><br><br>
			Terimakasih atas perhatiannya
		  </body> 
		</html>";
			// echo"$message";
			// echo '<pre>';
			// var_dump($_SESSION);
			// echo '</pre>';		
			// exit();
			$this->email->message($message);
			$this->email->send();

			if ($this->dgeneral->status_transaction() === FALSE) {
				$this->dgeneral->rollback_transaction();
				$msg    = "Periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				if (isset($_POST['id']) && $_POST['id'] != "") {
					$msg    	= "Data berhasil diupdate";
					// $idtiket 	= $_POST['id'];
				} else {
					$msg    	= "Data berhasil ditambahkan";
					$idtiket 	= $this->db->insert_id();

					// insert History
					$data_rowx   = array(
						'id_hd_status'    => 1,
						'id_hd_ticket'    => $idtiket,
						'remark'    		=> $_POST['keterangan'],
						'title'    		=> $_POST['title'],
						'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'    => $tanggal,
						'tanggal_awal'    => $tanggal,
						'na'    			=> 'n',
						'del'        		=> 'n'
					);
					$data_row 	= array_merge($data_rowx, $datalampiran);
					$this->dgeneral->insert('tbl_hd_history', $data_rowx);
				}



				$sts    = "OK";
			}

			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}

		//-------------------------------------------------//



	}
