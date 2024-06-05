<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : MASTER DEPO
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

include_once APPPATH . "modules/depo/controllers/BaseControllers.php";

class Evaluasi extends MX_Controller
// class Evaluasi extends BaseControllers
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('PHPExcel');
		$this->load->helper(array('form', 'url'));

		$this->load->model('dmasterdepo');
		$this->load->model('dsettingdepo');
		$this->load->model('dtransaksidepo');
		$this->load->model('devaluasidepo');
	}

	public function index()
	{
		show_404();
	}
	
	public function approve($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Approve Evaluasi Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);;
		$this->load->view("evaluasi/approve", $data);
	}
	public function data($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data Evaluasi Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);;
		$this->load->view("evaluasi/data", $data);
	}
	public function edit($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Edit Evaluasi Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['nomor'] 		= str_replace("-", "/", $param);		
		
		$this->load->view("evaluasi/edit", $data);
	}
	
	public function detail($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Detail Evaluasi Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['nomor'] 		= str_replace("-", "/", $param);		
		
		$this->load->view("evaluasi/detail", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
    public function generate($param = NULL, $param2 = NULL)
	{
        $this->generate_evaluasi($param, $param2);
    }
	
	public function get($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'history':
				$nomor	= (isset($_POST['nomor']) ? str_replace('-','/',$_POST['nomor']) : NULL);
				$this->get_history(NULL, $nomor, 'n', NULL);
				break;
			
			case 'data':
				$nomor  		= (isset($_POST['nomor']) ? $_POST['nomor'] : NULL);
				$view_data		= (isset($_POST['view_data']) ? $_POST['view_data'] : NULL);
				//filter jenis depo
				if (isset($_POST['jenis_depo_filter'])) {
					$jenis_depo_filter	= array();
					foreach ($_POST['jenis_depo_filter'] as $dt) {
						array_push($jenis_depo_filter, $dt);
					}
				} else {
					$jenis_depo_filter  = NULL;
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
					$return = $this->devaluasidepo->get_evaluasi_depo_bom('open', $nomor, NULL, NULL, $jenis_depo_filter, $pabrik_filter, $status_filter, $view_data);
					echo $return;
					break;
				} else {
					$this->get_evaluasi(NULL, $nomor);
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
			case 'evaluasi':
				$this->save_evaluasi($param2);
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
	private function save_evaluasi($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);
		// echo json_encode($post);
		// exit;	
		
		$id_depo_master	= $post['id_depo_master'];
		$nomor			= $post['nomor'];

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		//==============
		//input detail
		//==============
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_evaluasi_biaya_detail", $data, array(
			array(
				'kolom' => 'nomor',
				'value' => $nomor
			),
			array(
				'kolom' => 'id_depo_master',
				'value' => $id_depo_master
			)
		));

		$biaya_biayas = array();
		//update biaya propesional
		foreach ($post['biaya_profesional_kgb'] as $index => $id_evaluasi_biaya) {	
			$biaya_biaya    = array(
				"nomor"			 		=> $nomor,
				"id_depo_master" 		=> $id_depo_master,
				"id_evaluasi_biaya"		=> $post['id_evaluasi_biaya_profesional'][$index],
				"nama" 					=> $post['nama_biaya_profesional'][$index],
				"biaya_kgb_pembukaan"	=> (float)str_replace(',','',$post['biaya_kgb_pembukaan'][$index]),
				"biaya_kgb_evaluasi"	=> (float)str_replace(',','',$post['biaya_profesional_kgb'][$index]),
				"na" 					=> 'n',
				"del" 					=> 'n',
			);
			$biaya_biaya = $this->dgeneral->basic_column('insert', $biaya_biaya, $datetime);
			$biaya_biayas[] = $biaya_biaya;
		}
		//update biaya_opex_kgb
		$biaya_biaya    = array(
			"nomor"			 		=> $nomor,
			"id_depo_master" 		=> $id_depo_master,
			"id_evaluasi_biaya"		=> $post['id_evaluasi_biaya_opex'],
			"nama" 					=> $post['nama_biaya_opex'],
			"biaya_kgb_pembukaan"	=> (float)str_replace(',','',$post['biaya_kgb_pembukaan']),
			"biaya_kgb_evaluasi"	=> (float)str_replace(',','',$post['biaya_opex_kgb']),
			"na" 					=> 'n',
			"del" 					=> 'n',
		);
		$biaya_biaya = $this->dgeneral->basic_column('insert', $biaya_biaya, $datetime);
		$biaya_biayas[] = $biaya_biaya;
		//update biaya_angkut_kgb
		$biaya_biaya    = array(
			"nomor"			 		=> $nomor,
			"id_depo_master" 		=> $id_depo_master,
			"id_evaluasi_biaya"		=> $post['id_evaluasi_biaya_angkut'],
			"nama" 					=> $post['nama_biaya_angkut'],
			"biaya_kgb_pembukaan"	=> (float)str_replace(',','',$post['biaya_kgb_pembukaan']),
			"biaya_kgb_evaluasi"	=> (float)str_replace(',','',$post['biaya_angkut_kgb']),
			"na" 					=> 'n',
			"del" 					=> 'n',
		);
		$biaya_biaya = $this->dgeneral->basic_column('insert', $biaya_biaya, $datetime);
		$biaya_biayas[] = $biaya_biaya;
		//update data gaji dan tunjangan
		foreach ($post['biaya_gaji_kgb'] as $index => $id_evaluasi_biaya) {	
			$biaya_biaya    = array(
				"nomor"			 		=> $nomor,
				"id_depo_master" 		=> $id_depo_master,
				"id_evaluasi_biaya"		=> $post['id_evaluasi_biaya_gaji'][$index],
				"nama" 					=> $post['nama_biaya_gaji'][$index],
				"biaya_kgb_pembukaan"	=> (float)str_replace(',','',$post['biaya_kgb_pembukaan'][$index]),
				"biaya_kgb_evaluasi"	=> (float)str_replace(',','',$post['biaya_gaji_kgb'][$index]),
				"na" 					=> 'n',
				"del" 					=> 'n',
			);
			$biaya_biaya = $this->dgeneral->basic_column('insert', $biaya_biaya, $datetime);
			$biaya_biayas[] = $biaya_biaya;
		}

		$this->dgeneral->insert_batch('tbl_depo_evaluasi_biaya_detail', $biaya_biayas);

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Update Evaluasi Depo Berhasil.";
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
		$id_depo_master	= $post['id_depo_master'];
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


        //========create DEPO when last Approve========//
		if(($action=='approve')&&($next_status==999)){	
			$data_depo = $this->dtransaksidepo->get_data_depo(NULL, NULL, 'n', 'n', $id_depo_master);
            $sap_depo = $this->create_depo_sap(
                array(
                    "connect" => FALSE,
                    "nomor" => $nomor,
                    "testrun" => FALSE,
                    "data" => $data_depo,
                    "return" => "array"
                )
            );

        }
		// echo json_encode($post);
		// exit;
        //=====================================//

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

    private function create_depo_sap($param = NULL){
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310

        $datetime = date("Y-m-d H:i:s");
        $data  = isset($param['data']) ? $param['data'] : NULL;
		$nomor = $data[0]->nomor;
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->dgeneral->begin_transaction();

        $type    = array();
        $message = array();
        $data_send = array();
        if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$list = array(
				"EKORG" => $data[0]->pabrik,
				"DEPID" => $data[0]->id_depo_master,
				"DEPNM" => $data[0]->nama,
				"KONTR" => '',
				"JNSDP" => strtoupper($data[0]->jenis_depo),
				"SJCOD" => $data[0]->kode_sj,
				"STCD1" => $data[0]->npwp,
				"ACTIV" => '',
				"ZZKOTA" => $data[0]->nama_kabupaten,
				"ZZPROV" => $data[0]->nama_propinsi
			);
			
			$param_rfc = array(
				array("TABLE", "T_DATA", array($list)),
				array("TABLE", "T_RETURN", array()),
			);
			
			$iserror = false;
			$result = $this->data['sap']->callFunction('Z_RFC_CREATEDEPOMASTER', $param_rfc);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && empty($result["T_RETURN"]) && !$iserror){
				$data_row_log = array(
					'app'           => 'DATA RFC CREATE DEPO',
					'rfc_name'      => 'Z_RFC_CREATEDEPOMASTER',
					'log_code'      => 'S',
					'log_status'    => 'Berhasil',
					'log_desc'      => "Berhasil Update Master Depo " . $nomor. " ke SAP",
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {
				$msg_fail = array();
				$type_fail = array();
				if ($result["T_RETURN"]) {
					foreach ($result["T_RETURN"] as $return) {
						$type[]    = $return['TYPE'];
						$message[] = $return['MESSAGE'];
						$type_fail[] = $return['TYPE'];
						$msg_fail[] = $return['MESSAGE'];
					}
				} else {
					$type[]    = 'E';
					$message[] = $result;
					$type_fail[] = 'E';
					$msg_fail[] = $result;
				}
				
				$data_row_log = array(
					'app'           => 'DATA RFC CREATE DEPO',
					'rfc_name'      => 'Z_RFC_CREATEDEPOMASTER',
					'log_code'      => implode(" , ", $type_fail),
					'log_status'    => 'Gagal',
					'log_desc'      => "CREATE DEPO Failed [T_RETURN]: " . implode(" , ", $msg_fail),
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			$data_send[] = $data_row_log;			
			// return $data_send;
        } else {
            $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());

            $type[]    = 'E';
            $message[] = $status;

            $data_row_log = array(
				'app'           => 'DATA RFC CREATE DEPO',
				'rfc_name'      => 'Z_RFC_CREATEDEPOMASTER',
                'log_code'      => 'E',
                'log_status'    => 'Gagal',
                'log_desc'      => "Connecting Failed: " . $status,
                'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                'executed_date' => $datetime
            );

            $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
        }

        //================================SAVE ALL================================//
        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
			
            $this->dgeneral->commit_transaction();
            $msg = "Berhasil mengirim data " . $nomor . " ke SAP"; //$data_row_log['log_desc'];
            $sts = "OK";
            if (in_array('E', $type) === true) {
                $sts = "NotOK";
                $msg = implode(" , ", $message);
            }
			
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
			
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);

			if (in_array('E', $type) === true) {
                $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
            } else
                return $return;
        } else {
            $return = array('sts' => $sts, 'msg' => $msg);
        }
        return $return;
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
	
	private function get_evaluasi($array = NULL, $nomor = NULL)
	{
		//header
		$data	= $this->devaluasidepo->get_data_evaluasi("open", $nomor);
		$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
		//detail
		$data_detail			= $this->devaluasidepo->get_data_evaluasi_detail("array", $nomor);
		$data_biaya				= $this->devaluasidepo->get_data_evaluasi_biaya("array", $data[0]->id_depo_master, $nomor);
		// $data_biaya_depo		= $this->get_biaya_depo("array", $data[0]->id_data, $data[0]->nomor_depo);
		// $data_biaya_investasi	= $this->get_biaya_investasi("array", $data[0]->id_data, $data[0]->nomor_depo);
		// $data_biaya_sdm			= $this->get_biaya_sdm("array", $data[0]->id_data, $data[0]->nomor_depo);
		// $data_biaya_trans		= $this->get_biaya_trans("array", $this->generate->kirana_decrypt($data[0]->id_data), $data[0]->nomor_depo);
		// $data_survei			= $this->get_survei("array", $this->generate->kirana_decrypt($data[0]->id_data), $data[0]->nomor_depo);
		// $data_target			= $this->get_target("array", $this->generate->kirana_decrypt($data[0]->id_data), $data[0]->nomor_depo);


		$data[0]->arr_data_detail = $data_detail;
		$data[0]->arr_data_biaya = $data_biaya;
		// $data[0]->arr_data_biaya_depo = $data_biaya_depo;
		// $data[0]->arr_data_biaya_investasi = $data_biaya_investasi;
		// $data[0]->arr_data_biaya_sdm = $data_biaya_sdm;
		// $data[0]->arr_data_biaya_trans = $data_biaya_trans;
		// $data[0]->arr_data_survei = $data_survei;
		// $data[0]->arr_data_target = $data_target;
		
		

		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_biaya_depo($array = NULL, $id_data = NULL, $nomor = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_biaya_depo("open", $id_data, $nomor);
		$data 	= $this->general->generate_encrypt_json($data);
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
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
        $datetime 	= date("Y-m-d H:i:s");
		$data	  	= $this->devaluasidepo->generate_data_evaluasi("open", $param);
		$jenis_depo = $data[0]->jenis_depo;
		$nomor		= $data[0]->nomor;
		foreach ($data as $dt) {
			if($dt->evaluasi_pending==0){
				//=====================================
				//jika trial 3 bulan
				//=====================================
				$today 			= (!empty($param2))? $dt->tanggal_akhir_transaksi : date('Y-m-d');
				$ck_bulan		= date('m', strtotime('-0 month', strtotime($today)));
				if($ck_bulan==07){
					$tanggal_awal	= date('Y-m-d', strtotime('-7 month', strtotime($today)));
				}else{
					$tanggal_awal	= date('Y-m-d', strtotime('-13 month', strtotime($today)));
				}
				$tanggal_akhir	= date('Y-m-d', strtotime('-2 month', strtotime($today)));
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
						"pabrik"			=> $dt->pabrik,
						"nomor"				=> $nomor,
						"status"			=> 1,
					);			
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_depo_evaluasi", $data_row);
					
					//input detail evaluasi	
					$data_evaluasi_details = array();
					for ($i = 0; $i < 3; $i++) {
						$tanggal_hitung = date('Y-m-d', strtotime('-'.$i.' month', strtotime($tanggal_akhir)));
						$bulan_ke = date('m', strtotime($tanggal_hitung));
						$tahun_ke = date('Y', strtotime($tanggal_hitung));

						$data_aktual  = $this->devaluasidepo->get_data_aktual("open", $dt->pabrik, $dt->nama, $tahun_ke, $bulan_ke, $dt->jumlah_bulan);
						$data_biaya_aktual	   = $this->devaluasidepo->get_data_biaya_aktual("open", $dt->pabrik, $tanggal_awal, $tanggal_hitung);

						$total_jumlah_pembelian = 0; 
						$total_biaya_pembelian = 0; 
						foreach ($data_biaya_aktual as $dt3) {
							$jumlah_pembelian		= $dt3->jumlah_pembelian;
							$total_jumlah_pembelian +=$jumlah_pembelian; 
							
							$biaya_pembelian 		= $dt3->jumlah_pembelian*$dt3->total_biaya_next;
							$total_biaya_pembelian	+=$biaya_pembelian;
							
							$biaya_depo_next	= $dt3->biaya_depo_next;		
						}
						$biaya_aktual_final = ($total_biaya_pembelian/$total_jumlah_pembelian)-$biaya_depo_next;

						$data_evaluasi_detail    = array(
							"nomor" 					=> $nomor,
							"pabrik" 					=> $dt->pabrik,
							"bulan" 					=> $bulan_ke,
							"tahun" 					=> $tahun_ke,
							"target" 					=> $data_aktual[0]->$target,
							"aktual_kering"				=> $data_aktual[0]->berat_kering,
							"aktual_basah" 				=> $data_aktual[0]->berat_basah,
							"harga_notarin" 			=> $data_aktual[0]->harga_notarin,
							"sicom" 					=> $data_aktual[0]->mixed_sicom,
							// "biaya_pabrik" 				=> $data_aktual[0]->biaya_produksi,
							"biaya_pabrik" 				=> $biaya_aktual_final,
							"harga_beli_depo" 			=> $data_aktual[0]->harga_beli_depo,
							"susut_pabrik" 				=> $data_aktual[0]->persen_susut_pabrik,
							"harga_beli_batch_pabrik"	=> $data_aktual[0]->harga_beli_pabrik,
							"susut_depo" 				=> $data_aktual[0]->persen_susut_depo,
							"harga_beli_batch_depo" 	=> $data_aktual[0]->harga_beli_depo,
							"na" 						=> 'n',
							"del" 						=> 'n',
						);
						// $data_evaluasi_detail = $this->dgeneral->basic_column('insert', $data_evaluasi_detail, $datetime);
						// $data_evaluasi_details[] = $data_evaluasi_detail;
						$this->dgeneral->insert("tbl_depo_evaluasi_detail", $data_evaluasi_detail);
					}	
					// $this->dgeneral->insert_batch('tbl_depo_evaluasi_detail', $data_evaluasi_details);	
				}
				//=====================================
				//jika lebih dari 6 bulan(bulan jun/des)
				//=====================================
				$today 			= (!empty($param2))? $param2 : date('Y-m-d');
				$ck_bulan		= date('m', strtotime('-0 month', strtotime($today)));
				if($ck_bulan==07){
					$tanggal_awal	= date('Y-m-d', strtotime('-7 month', strtotime($today)));
				}else{
					$tanggal_awal	= date('Y-m-d', strtotime('-13 month', strtotime($today)));
				}
				$tanggal_akhir	= date('Y-m-d', strtotime('-2 month', strtotime($today)));
				
				if(($dt->jumlah_bulan>=6)and($ck_bulan=07 or $ck_bulan=01)){
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
						"pabrik"			=> $dt->pabrik,
						"nomor"				=> $nomor,
						"status"			=> 1,
					);			
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_depo_evaluasi", $data_row);
					
					//input detail evaluasi	
					$data_evaluasi_details = array();
					for ($i = 0; $i < 6; $i++) {
						$tanggal_hitung = date('Y-m-d', strtotime('-'.$i.' month', strtotime($tanggal_akhir)));
						$bulan_ke = date('m', strtotime($tanggal_hitung));
						$tahun_ke = date('Y', strtotime($tanggal_hitung));

						$data_aktual  = $this->devaluasidepo->get_data_aktual("open", $dt->pabrik, $dt->nama, $tahun_ke, $bulan_ke, $dt->jumlah_bulan, $ck_bulan);
						$data_biaya_aktual	   = $this->devaluasidepo->get_data_biaya_aktual("open", $dt->pabrik, $tanggal_awal, $tanggal_hitung);
						$total_jumlah_pembelian = 0; 
						$total_biaya_pembelian = 0; 
						foreach ($data_biaya_aktual as $dt3) {
							$jumlah_pembelian		= $dt3->jumlah_pembelian;
							$total_jumlah_pembelian +=$jumlah_pembelian; 
							
							$biaya_pembelian 		= $dt3->jumlah_pembelian*$dt3->total_biaya_next;
							$total_biaya_pembelian	+=$biaya_pembelian;
							
							$biaya_depo_next	= $dt3->biaya_depo_next;		
							
						}
						if($total_jumlah_pembelian!=0){
							$biaya_aktual_final = ($total_biaya_pembelian/$total_jumlah_pembelian)-$biaya_depo_next;
						}else{
							$biaya_aktual_final = 0;
						}
						

						// $target = 'm'.$bulan_ke;
						$data_evaluasi_detail    = array(
							"nomor" 					=> $nomor,
							"pabrik" 					=> $dt->pabrik,
							"bulan" 					=> $bulan_ke,
							"tahun" 					=> $tahun_ke,
							"target" 					=> $data_aktual[0]->target,
							"aktual_kering"				=> $data_aktual[0]->berat_kering,
							"aktual_basah" 				=> $data_aktual[0]->berat_basah,
							"harga_notarin" 			=> $data_aktual[0]->harga_notarin,
							"sicom" 					=> $data_aktual[0]->mixed_sicom,
							// "biaya_pabrik" 				=> $data_aktual[0]->biaya_produksi,
							"biaya_pabrik" 				=> $biaya_aktual_final,
							"harga_beli_depo" 			=> $data_aktual[0]->harga_beli_depo,
							"susut_pabrik" 				=> $data_aktual[0]->persen_susut_batch_pabrik,
							"harga_beli_batch_pabrik"	=> $data_aktual[0]->harga_beli_batch_pabrik,
							"susut_depo" 				=> $data_aktual[0]->persen_susut_batch_depo,
							"harga_beli_batch_depo" 	=> $data_aktual[0]->harga_beli_batch_depo,
							"na" 						=> 'n',
							"del" 						=> 'n',
						);
						// $data_evaluasi_detail = $this->dgeneral->basic_column('insert', $data_evaluasi_detail, $datetime);
						// $data_evaluasi_details[] = $data_evaluasi_detail;
						$data_evaluasi_detail = $this->dgeneral->basic_column('insert', $data_evaluasi_detail, $datetime);
						$this->dgeneral->insert("tbl_depo_evaluasi_detail", $data_evaluasi_detail);
						
					}	
					// $this->dgeneral->insert_batch('tbl_depo_evaluasi_detailxx', $data_evaluasi_details);	
				}
			}
		}
		//tambahan buat set biaya evaluasi
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_evaluasi_biaya_detail", $data, array(
			array(
				'kolom' => 'nomor',
				'value' => $nomor
			),
			array(
				'kolom' => 'id_depo_master',
				'value' => $param
			),
			array(
				'kolom' => 'na',
				'value' => 'n'
			),
			array(
				'kolom' => 'del',
				'value' => 'n'
			)
		));
		
		$data_biaya	  = $this->devaluasidepo->generate_data_evaluasi_biaya("open", $param, $jenis_depo, $nomor);
		foreach ($data_biaya as $dt2) {
			$data_evaluasi_biaya_detail    = array(
				"nomor"					=> $nomor,
				"id_depo_master"		=> $param,
				"id_evaluasi_biaya"		=> $dt2->id_evaluasi_biaya,
				"nama" 					=> $dt2->nama,
				"biaya_kgb_pembukaan"	=> $dt2->biaya_kgb_pembukaan,
				"biaya_kgb_evaluasi"	=> $dt2->biaya_kgb_evaluasi,
			);
			$data_evaluasi_biaya_detail = $this->dgeneral->basic_column('insert', $data_evaluasi_biaya_detail, $datetime);
			$this->dgeneral->insert("tbl_depo_evaluasi_biaya_detail", $data_evaluasi_biaya_detail);
			
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
		return $this->devaluasidepo->get_nomor(
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
