<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : TAKSASI BOKAR
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

include_once APPPATH . "modules/depo/controllers/BaseControllers.php";

class Transaksi extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('PHPExcel');
		$this->load->helper(array('form', 'url'));

		$this->load->model('dmastertaksasi');
		$this->load->model('dtransaksitaksasi');
	}

	public function index()
	{
		show_404();
	}
	
	public function jadwal($param = NULL, $param2 = NULL)
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['title']    	= "Jadwal BOKIN";
		$tahap				= $this->dtransaksitaksasi->get_data_tahap("open", NULL, "n");
		$data['tahap'] 		= $this->general->generate_encrypt_json($tahap, array("id_tahap"));
		$this->load->view("transaksi/jadwal", $data);
		
	}
	
	public function jadwal_old($param = NULL, $param2 = NULL)
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['title']    	= "Jadwal BOKIN";
		$tahap				= $this->dmastertaksasi->get_data_tahap("open", NULL, "n");
		$data['tahap'] 		= $this->general->generate_encrypt_json($tahap, array("id_tahap"));
		$this->load->view("transaksi/jadwal", $data);
	}
	public function nilai($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['title']    	= "Input Nilai BOKIN";
		
		$data['id_jadwal']	= $param;
		$data['bobot']		= $this->get_bobot("array", $this->generate->kirana_decrypt($param));	
		$this->load->view("transaksi/nilai", $data);
	}
	
	public function edit($param = NULL)
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['title']    	= "Edit Jadwal";
		$this->load->view("evaluasi/detail", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'depo_auto':
				$post = $this->input->post_get(NULL, TRUE);
				$this->get_depo_autocomplete();
				break;
			case 'data_depo':
				$id_depo_master = (isset($_POST['id_depo_master']) ? $this->generate->kirana_decrypt($_POST['id_depo_master']) : NULL);
				$this->get_depo(NULL, $id_depo_master);
				break;
			case 'user_auto':
				$post = $this->input->post_get(NULL, TRUE);
				$this->get_user_autocomplete($post['pra_syarat']);
				break;
            case 'penilaian_auto':
                $post = $this->input->post_get(NULL, TRUE);
                $penilaian = array();
				$penilaian = $this->dtransaksitaksasi->get_data_penilaian_auto(
					array(
						"connect" => TRUE,
						"search" => $this->general->emptyconvert(@$post['search']),
						"not_in_nilai" => $this->general->emptyconvert(@$post['not_in_nilai']),
						"return" => 'array'
					)
				);
                $penilaian = array_merge($penilaian);
                $data_penilaian  = array(
                    "total_count" => count($penilaian),
                    "incomplete_results" => false,
                    "items" => $penilaian
                );
                echo json_encode($data_penilaian);
				exit;
            break;
			case 'data':
				$id_jadwal  	= (isset($_POST['id_jadwal']) ? $this->generate->kirana_decrypt($_POST['id_jadwal']) : NULL);
				//filter tahap
				if (isset($_POST['tahap_filter'])) {
					$tahap_filter	= array();
					foreach ($_POST['tahap_filter'] as $dt) {
						array_push($tahap_filter, $this->generate->kirana_decrypt($dt));
					}
				} else {
					$tahap_filter  = NULL;
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
					$return = $this->dtransaksitaksasi->get_data_jadwal_bom('open', $id_jadwal, NULL, NULL, $tahap_filter, $status_filter);
					echo $return;
					break;
				} else {
					$this->get_jadwal(NULL, $id_jadwal);
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
				case 'jadwal':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_depo_data", array(
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
	public function save($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'jadwal':
				$this->save_jadwal($param2);
				break;
			case 'nilai':
				$this->save_nilai($param2);
				break;
			case 'approve':
				$this->save_approve($param2);
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
	private function get_jadwal($array = NULL, $id_jadwal = NULL)
	{
		//header
		$data	= $this->dtransaksitaksasi->get_data_jadwal("open", $id_jadwal);
		$data 	= $this->general->generate_encrypt_json($data, array("id_jadwal", "id_tahap"));

		//detail
		$data_peserta			= $this->get_peserta("array", $id_jadwal, $data[0]->id_program_batch);
		$data[0]->arr_peserta 	= $data_peserta;
		$data_bobot				= $this->get_bobot("array", $id_jadwal);
		$data[0]->arr_bobot 	= $data_bobot;

		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	
	private function get_peserta($array = NULL, $id_jadwal = NULL, $id_program_batch = NULL)
	{
		$data	= $this->dtransaksitaksasi->get_data_peserta("open", $id_jadwal, $id_program_batch);
		$data 	= $this->general->generate_encrypt_json($data);
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}

	private function get_bobot($array = NULL, $id_jadwal = NULL)
	{
		$data	= $this->dtransaksitaksasi->get_data_bobot("open", $id_jadwal);
		$data 	= $this->general->generate_encrypt_json($data);
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	
	private function get_user_autocomplete($pra_syarat = NULL)
	{
		if (isset($_GET['q'])) {
			$data	= $this->dtransaksitaksasi->get_data_user_autocomplete($_GET['q'], $pra_syarat);
			$data_json = array(
				"total_count"        => count($data),
				"incomplete_results" => false,
				"items"              => $data
			);
			echo json_encode($data_json);
		}
	}
	private function save_jadwal($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_jadwal	 = (isset($post['id_jadwal']) ? $this->generate->kirana_decrypt($post['id_jadwal']) : NULL);
		$nama		 = (isset($post['nama']) ? $post['nama'] : NULL);
		$id_tahap	 = (isset($post['id_tahap']) ? $this->generate->kirana_decrypt($post['id_tahap']) : NULL);
		$peserta 	 = $this->input->post('peserta[]', true);
		$pass_grade	 = (isset($post['pass_grade']) ? $post['pass_grade'] : NULL);
		
		if ($id_jadwal!=NULL){	
			//set delete bobot
			$data_delete_bobot = array(
				"na"     		=> 'y',
				"del"			=> 'y',
				'tanggal_edit'	=> $datetime,
				'login_edit'	=> base64_decode($this->session->userdata("-id_user-")),
			);
			$this->dgeneral->update("tbl_taksasi_jadwal_bobot",$data_delete_bobot, array(
				array(
					'kolom' => 'id_jadwal',
					'value' => $id_jadwal
				)
			));
			//insert bobot
			$data_bobot_details = array();
			foreach ($post['id_nilai'] as $index => $id_nilai) {	
				$data_bobot_detail    = array(
					"id_jadwal" 	=> $id_jadwal,
					"id_nilai"	 	=> $this->general->emptyconvert($_POST['id_nilai'][$index], NULL),
					"bobot" 		=> $this->general->emptyconvert($post['bobot'][$index], NULL),
					"pass_grade" 	=> $this->general->emptyconvert($post['pass_grade'], NULL),
					"tanggal_buat"	=> $datetime,
					"login_buat"	=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"	=> $datetime,
					"login_edit"	=> base64_decode($this->session->userdata("-id_user-")),
					"na" 			=> 'n',
					"del" 			=> 'n',
				);
				$data_bobot_detail = $this->dgeneral->basic_column('insert', $data_bobot_detail, $datetime);
				$data_bobot_details[] = $data_bobot_detail;
			}
			$this->dgeneral->insert_batch('tbl_taksasi_jadwal_bobot', $data_bobot_details);
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
	
	private function save_nilai($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_jadwal	= (isset($post['id_jadwal']) ? $this->generate->kirana_decrypt($post['id_jadwal']) : NULL);
		$bobot		= $this->get_bobot("array", $id_jadwal);		

		//set status batch di klems
		$status_batch = array(
			"status_bokin" 	=> 1,
			'tanggal_edit'	=> $datetime,
			'login_edit'	=> base64_decode($this->session->userdata("-id_user-")),
		);
		$this->dgeneral->update("tbl_batch", $status_batch, array(
			array(
				'kolom' => 'id_batch',
				'value' => $id_jadwal
			)
		));
		
		foreach($bobot as $dt){			
			foreach ($post['nik'] as $index => $nik) {	
				//insert nilai
				$post_nilai		=  "input_nilai_".$dt->id_nilai;
				$data_peserta    = array(
					"id_jadwal" 	=> $id_jadwal,
					"nik" 			=> $nik,
					"id_nilai" 		=> $dt->id_nilai,
					"bobot" 		=> $dt->bobot,
					"nilai" 		=> $this->general->emptyconvert($post[$post_nilai][$index], NULL),
					"tanggal_buat"	=> $datetime,
					"login_buat"	=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"	=> $datetime,
					"login_edit"	=> base64_decode($this->session->userdata("-id_user-")),
					"na" 			=> 'n',
					"del" 			=> 'n',
				);
				$this->dgeneral->insert('tbl_taksasi_jadwal_peserta_nilai', $data_peserta);
				
				//set status tbl_peserta
				$status_peserta = array(
					"lulus_bokin" 	=> $this->general->emptyconvert($post['lulus_bokin'][$index], NULL),
					'tanggal_edit'	=> $datetime,
					'login_edit'	=> base64_decode($this->session->userdata("-id_user-")),
				);
				$this->dgeneral->update("tbl_peserta", $status_peserta, array(
					array(
						'kolom' => 'id_karyawan',
						'value' => $nik
					),
					array(
						'kolom' => 'id_batch',
						'value' => $id_jadwal
					)
				));
				
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
	
	
    private function save_approve()
    {
        $datetime 	= date("Y-m-d H:i:s");
        $post 		= $this->input->post(NULL, TRUE);
        $action 	= $post['action'];
        $nomor 		= $post['nomor'];
        $catatan 	= $post['komentar_approve_evaluasi'];
        $status 	= $post['status_akhir'];
		$jenis_depo	= $post['jenis_depo'];
		$next_status_data = 	$this->dtransaksidepo->get_data_user_role_status("open", $status);
		$next_status		= 0;	//set default 
		$error		= false;
		
		if ($jenis_depo == 'tetap') {
			if($action=='approve'){
				$next_status = $next_status_data[0]->if_approve_evaluasi_tetap;
			}
			if($action=='decline'){
				$next_status = $next_status_data[0]->if_decline_evaluasi_tetap;
			}
		} else {	//mitra(trial)
			if($action=='approve'){
				$next_status = $next_status_data[0]->if_approve_evaluasi_trial;
			}
			if($action=='decline'){
				$next_status = $next_status_data[0]->if_decline_evaluasi_trial;
			}
		}
		
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        //========Update data status approval========//
		if($next_status==999){	//jika approval terakhir
			$data_row_header = array(
				"status" => $next_status,
				"status_evaluasi" => $post['status_evaluasi']
			);
		}else{
			$data_row_header = array(
				"status" => $next_status
			);
		}
        $data_row_header = $this->dgeneral->basic_column("update", $data_row_header, $datetime);
        $this->dgeneral->update('tbl_depo_evaluasi', $data_row_header, array(
            array(
                'kolom' => 'nomor',
                'value' => $post['nomor']
            )
        ));

		//save data temp log 
		$data_row_log = array(
			"nomor"		=> $nomor,
			"status"	=> $status,
			"action"	=> $action,
			"catatan"	=> $catatan
		);
		$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
		$this->dgeneral->insert("tbl_depo_evaluasi_log", $data_row_log);


        // //========create DEPO when last Approve========//
		
		// if(($action=='approve')&&($status==999)){	
			// $data_depo = $this->dtransaksidepo->get_data_depo(NULL, $nomor);
            // $sap_depo = $this->create_depo_sap(
                // array(
                    // "connect" => FALSE,
                    // "nomor" => $nomor,
                    // "testrun" => FALSE,
                    // "data" => $data_depo,
                    // "return" => "array"
                // )
            // );

        // }
		// // echo json_encode($post);
		// // exit;
        // //=====================================//

        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
			if ($error){
				$this->dgeneral->rollback_transaction();
				$msg = $sap_depo['msg'];
				$sts = "NotOK";
			}else{
				$this->dgeneral->commit_transaction();
				$data_evaluasi = $this->devaluasidepo->get_data_evaluasi(NULL, $nomor);
				//send email approval
				$this->send_email(
					array(
						"post" => $post,
						"header" => $data_evaluasi
					)
				);
				$msg = "Data berhasil ditambahkan";
				$sts = "OK";
			}
        }
        //============================================//

        $this->general->closeDb();
		if ($error){
			$return = array('sts' => $sts, 'msg' => $msg);
		}else{
			$return = array('sts' => $sts, 'msg' => $msg);
		}
        echo json_encode($return);
        exit();
    } 

    private function send_email($param = NULL)
    {
        $post = (object) $param['post'];
        $header = $param['header'];
        $action = $post->action;
		
        switch ($action) {
            case 'approve':
                $status = "Approved";
                break;
            case 'decline':
                $status = "Declined";
                break;
            case 'finish':
                $status = "Finish";
                break;
        }

        if (isset($post->komentar_approve_evaluasi))
            $comment = $post->komentar_approve_evaluasi;
        else
            $comment = '';

        $data_recipient = $this->devaluasidepo->get_email_recipient(
            array(
                "connect" => TRUE,
                "nomor" => $post->nomor
            )
        );
		// echo json_encode($data_recipient);
		// exit;

        $email_cc = array();
        $email_to = array();
        $email_bcc = array();
        foreach ($data_recipient as $dt) {
			$email_to[] = ENVIRONMENT == 'development' ? "AIRIZA.PERDANA@KIRANAMEGATARA.COM" : $dt->email;
			if ($dt->nama !== "" && $dt->gender !== "") {
				$nama_to[] = $dt->gender . " " . ucwords(strtolower($dt->nama)) . "<br>";
			}
        }
        if (ENVIRONMENT == 'development') {
            $email_cc[] = "lukman.hakim@kiranamegatara.com";
        }

        if ($status == "Confirm" && in_array("lukman.hakim@kiranamegatara.com", $email_cc) === FALSE)
            $email_cc[] = "lukman.hakim@kiranamegatara.com";

        if (empty($email_to)) {
            $email_to = $email_cc;
            $email_cc = array();
        }

        $message = $this->generate_email_message(
            array(
                "nama_to" => empty($nama_to) ? "" : implode("", $nama_to),
                "nomor" => $post->nomor,
                "status" => $status,
                "oleh" => ucwords(strtolower(base64_decode($this->session->userdata("-nama-")))),
                "comment" => $comment
            )
        );
		// echo $message;
		// exit;

        if (count($email_to) > 0)
			$subject 	= "Notifikasi Evaluasi Depo";
			$from_alias	= "EV-DEPO";
			$this->general->send_email($subject, $from_alias, $email_to, $email_cc, $message);
        return true;
    }

    private function generate_email_message($param = NULL)
    {
		$message = "<html>
                        <body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
                        <center style='width: 100%;'>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                Notifikasi Email Aplikasi Form Evaluasi Depo
                            </div>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
                            </div>
                            <div class='email-container' style='max-width: 800px; margin: 0 auto;'>
                                <table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'
                                       style='min-width:600px;'>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style='color: #fff; padding:20px;' align='center'>
                                            <div style='width: 50%; padding-bottom: 10px;''>
                                                <img src='" . base_url() . "/assets/apps/img/logo-lg.png'>
                                            </div>
                                            <h1 style='margin-bottom: 0;'>Evaluasi Depo</h1>
                                            <hr style='border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;'/>
                                            <h3 style='margin-top: 0;'>Notifikasi Email</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table style='background-color: #ffffff; margin: auto; -webkit-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); -moz-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4);'
                                                   role='presentation' border='0' width='100%' cellspacing='0'
                                                   cellpadding='0'
                                                   align='center'>
                                                <tbody>
                                                <tr>
                                                    <td style='padding: 20px;'>
                                                        ";
        if (!$param['nama_to']) {
            $param['nama_to'] = 'Bapak & Ibu';
        }
        $message .= "<p><strong>Kepada :<br><br> " . $param['nama_to'] . "</strong></p>";
        $message .= "<p>Email ini menandakan bahwa ada Evaluasi Depo yang membutuhkan perhatian anda.</p>";
        $message .= "<table style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
                                                <tr>
                                                    <td>Nomor</td>
                                                    <td>:</td>";
        $message .= "<td>" . $param['nomor'] . "</td>"; //NOMOR FPB
        $message .= "</tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>:</td>";
        $message .= "<td>" . $param['status'] . "</td>"; // STATUS (disetujui, ditolak, selesai)
        $message .= "</tr>
                                                <tr>
                                                    <td>Oleh</td>
                                                    <td>:</td>";
        $message .= "<td>" . $param['oleh'] . "</td>"; //OLEH atau LAST ACTION PI
        $message .= "</tr>
                                                <tr>
                                                    <td>Tanggal</td>
                                                    <td>:</td>";
        $message .= "<td>" . strftime('%A, %d %B %Y') . "</td>"; //TANGGAL KIRIM EMAIL
        $message .= "</tr>
                                                <tr>
                                                    <td>Catatan</td>
                                                    <td>:</td>";
        if (!$param['comment']) {
            $param['comment'] = '-';
        }
        $message .= "<td>" . $param['comment'] . "</td>"; // COMMENT PI
        $message .= "</tr>
                                </table>
                                <p>Selanjutnya anda dapat melakukan review pada Evaluasi Depo tersebut</p><p>melalui aplikasi PORTAL di Portal Kiranaku.</p>
                            </td>
                        </tr>
                        <tr>
                            <td align='left'
                                style='background-color: #ffffff; padding: 15px; border-top: 1px dashed #386d22;'>
                            </td>
                        </tr>
                                <tr>
                                    <td align='left'
                                        style='background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;'>
                                        <p>
                                            Terima kasih atas perhatiannya.
                                        </p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style='color: #fff; padding-top:20px;' align='center'>
                            <small>Kiranaku Auto-Mail System</small><br/>";
        $message .= "<strong style='color: #214014; font-size: 10px;'>Terkirim pada " . date('d.m.Y H:i:s') . "</strong>"; // TANGGAL KIRIM EMAIL
        $message .= " </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </center>
            </body>
            </html>";

        return $message;
    }
	
	private function get_biaya_investasi($array = NULL, $id_data = NULL, $nomor = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_biaya_investasi("open", $id_data, $nomor);
		$data 	= $this->general->generate_encrypt_json($data);
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_biaya_sdm($array = NULL, $id_data = NULL, $nomor = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_biaya_sdm("open", $id_data, $nomor);
		$data 	= $this->general->generate_encrypt_json($data);
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_biaya_trans($array = NULL, $id_data = NULL, $nomor = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_biaya_trans("open", $id_data, $nomor);
		$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_survei($array = NULL, $id_data = NULL, $nomor = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_survei("open", $id_data, $nomor);
		$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_target($array = NULL, $id_data = NULL, $nomor = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_target("open", $id_data, $nomor);
		$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	
    private function generate_evaluasi($param, $param2)
    {
        $datetime = date("Y-m-d H:i:s");
		$data	  = $this->devaluasidepo->generate_data_evaluasi("open", $param);
		foreach ($data as $dt) {
			if($dt->evaluasi_pending==0){
			// if(1==1){
				//jika trial 3 bulan
				if(($dt->jenis_depo=='mitra')&&($dt->jumlah_bulan==3)){
					//input header evaluasi
					$nomor = $this->generate_nomor(
						array(
							"connect" => TRUE,
							"jenis_depo" => $dt->jenis_depo,
							"pabrik" => $dt->pabrik
						)
					);
					$data_row = array(
						"id_depo_master"	=> $dt->id_depo_master,
						"nomor"				=> $nomor,
						"status"			=> 1,
					);			
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_depo_evaluasi", $data_row);
					
					//input detail evaluasi	
					$tanggal_transaksi = $dt->tanggal_akhir_transaksi;
					$data_evaluasi_details = array();
					for ($i = 0; $i < 3; $i++) {
						$bulan_ke = date('m', strtotime('-'.$i.' month', strtotime($tanggal_transaksi)));
						$tahun_ke = date('Y', strtotime('-'.$i.' month', strtotime($tanggal_transaksi)));
						$data_aktual  = $this->devaluasidepo->get_data_aktual("open", $dt->pabrik, $dt->nama, $tahun_ke, $bulan_ke);
						echo json_encode($data_aktual);
						exit;
						$data_evaluasi_detail    = array(
							"nomor" 					=> $nomor,
							"pabrik" 					=> $dt->pabrik,
							"bulan" 					=> $bulan_ke,
							"tahun" 					=> $tahun_ke,
							"target" 					=> 99,
							"aktual_kering"				=> $data_aktual[0]->berat_kering,
							"aktual_basah" 				=> $data_aktual[0]->berat_basah,
							"harga_notarin" 			=> $data_aktual[0]->harga_notarin,
							"sicom" 					=> 99,
							"biaya_pabrik" 				=> 99,
							"harga_beli_depo" 			=> $data_aktual[0]->harga_beli_depo,
							"susut_pabrik" 				=> 99,
							"harga_beli_batch_pabrik"	=> 99,
							"susut_depo" 				=> 99,
							"harga_beli_batch_depo" 	=> 99,
							"na" 						=> 'n',
							"del" 						=> 'n',
						);
						$data_evaluasi_detail = $this->dgeneral->basic_column('insert', $data_evaluasi_detail, $datetime);
						$data_evaluasi_details[] = $data_evaluasi_detail;
					}	
					$this->dgeneral->insert_batch('tbl_depo_evaluasi_detail', $data_evaluasi_details);	
				}
				
				//jika lebih dari 6 bulan(bulan jun/des)
				$today 		= (!empty($param2))? $param2 : date('Y-m-d');
				$ck_bulan	= date('m', strtotime('-0 month', strtotime($today)));
				if(($dt->jumlah_bulan>=6)and($ck_bulan=06 or $ck_bulan=12)){
					//input header evaluasi
					$nomor = $this->generate_nomor(
						array(
							"connect" => TRUE,
							"jenis_depo" => $dt->jenis_depo,
							"pabrik" => $dt->pabrik
						)
					);
					$data_row = array(
						"id_depo_master"	=> $dt->id_depo_master,
						"nomor"				=> $nomor,
						"status"			=> 1,
					);			
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_depo_evaluasi", $data_row);
					
					//input detail evaluasi	
					$tanggal_transaksi = date('Y-m-d', strtotime('-1 month', strtotime($today)));
					$data_evaluasi_details = array();
					for ($i = 0; $i < 6; $i++) {
						$ke		= $i+1;
						$target = 'm'.$ke;
						$bulan_ke = date('m', strtotime('-'.$i.' month', strtotime($tanggal_transaksi)));
						$tahun_ke = date('Y', strtotime('-'.$i.' month', strtotime($tanggal_transaksi)));
						$data_aktual  = $this->devaluasidepo->get_data_aktual("open", $dt->pabrik, $dt->nama, $tahun_ke, $bulan_ke);

						$data_evaluasi_detail    = array(
							"nomor" 					=> $nomor,
							"pabrik" 					=> $dt->pabrik,
							"bulan" 					=> $bulan_ke,
							"tahun" 					=> $tahun_ke,
							"target" 					=> $data_aktual[0]->$target,
							"aktual_kering"				=> $data_aktual[0]->berat_kering,
							"aktual_basah" 				=> $data_aktual[0]->berat_basah,
							"harga_notarin" 			=> $data_aktual[0]->harga_notarin,
							"sicom" 					=> 99,
							"biaya_pabrik" 				=> 99,
							"harga_beli_depo" 			=> $data_aktual[0]->harga_beli_depo,
							"susut_pabrik" 				=> 99,
							"harga_beli_batch_pabrik"	=> 99,
							"susut_depo" 				=> 99,
							"harga_beli_batch_depo" 	=> 99,
							"na" 						=> 'n',
							"del" 						=> 'n',
						);
						$data_evaluasi_detail = $this->dgeneral->basic_column('insert', $data_evaluasi_detail, $datetime);
						$data_evaluasi_details[] = $data_evaluasi_detail;
					}	
					$this->dgeneral->insert_batch('tbl_depo_evaluasi_detail', $data_evaluasi_details);	
				}
			}
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data Depo-Biaya berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
        exit();
    }
	private function get_depo_autocomplete()
	{
		if (isset($_GET['q'])) {
			$data	= $this->dpenutupandepo->get_data_depo_autocomplete($_GET['q']);
			$data 	= $this->general->generate_encrypt_json($data, array("id"));
			$data_json = array(
				"total_count"        => count($data),
				"incomplete_results" => false,
				"items"              => $data
			);
			echo json_encode($data_json);
		}
	}
	private function get_depo($array = NULL, $id_depo_master = NULL)
	{
		$data	= $this->dpenutupandepo->get_data_depo("open", $id_depo_master);
		$data 	= $this->general->generate_encrypt_json($data, array("id"));
		
		//get nomor penutupan	
		$nomor_penutupan = $this->generate_nomor(
			array(
				"connect" => TRUE,
				"jenis_depo" => $data[0]->jenis_depo,
				"pabrik" => $data[0]->pabrik
			)
		);
		$data[0]->nomor_penutupan = $nomor_penutupan;

		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	
	
	private function get_history($array = NULL, $nomor = NULL, $active = NULL, $deleted = NULL)
	{
		$history 	= $this->devaluasidepo->get_data_history("open", $nomor, $active, $deleted);
		$history 	= $this->general->generate_encrypt_json($history, array("nomor"));
		if ($array) {
			return $history;
		} else {
			echo json_encode($history);
		}
	}
	
	private function generate_nomor($param = NULL)
	{
		$jenis_depo = isset($param['jenis_depo']) ? $param['jenis_depo'] : NULL;
		$pabrik     = isset($param['pabrik']) ? $param['pabrik'] : $this->session->userdata("-plant_code-")[0];
		$month      = date('m');
		$year       = date('Y');
		return $this->dpenutupandepo->get_nomor(
			array(
				"connect" => $param['connect'],
				"jenis_depo" => $jenis_depo,
				"pabrik" => $pabrik,
				"month" => $month,
				"year" => $year
			)
		)->nomor;
	}
	
	/*====================================================================*/
}
