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

// class Transaksi extends MX_Controller
class Transaksi extends BaseControllers
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('PHPExcel');
		$this->load->helper(array('form', 'url'));

		$this->load->model('dmasterdepo');
		$this->load->model('dsettingdepo');
		$this->load->model('dtransaksidepo');
	}

	public function index()
	{
		show_404();
	}
	//test upload gambar
	public function upload($param = NULL)
	{
		$data['title'] = "Upload";
		$this->load->view("transaksi/upload", $data);
	}
	
	public function input($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Form Master Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['matrix_mitra']	= $this->get_matrix_mitra('array');
		$data['provinsi'] 	= $this->get_provinsi('array');
		$this->load->view("transaksi/input", $data);
	}
	
	public function edit($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Edit Master Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['matrix_mitra']	= $this->get_matrix_mitra('array');
		$data['provinsi'] 	= $this->get_provinsi('array');
		$data['nomor'] 		= str_replace("-", "/", $param);		
		
		$this->load->view("transaksi/edit", $data);
	}
	
	public function detail($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Detail Master Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['matrix_mitra']	= $this->get_matrix_mitra('array');
		$data['provinsi'] 	= $this->get_provinsi('array');
		$data['nomor'] 		= str_replace("-", "/", $param);		
		
		$this->load->view("transaksi/detail", $data);
	}
	
	public function approve($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Approve Master Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);;
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

		$data['title']    	= "Data Master Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);;
		$this->load->view("transaksi/data", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'kabupaten':
				$id_provinsi  	= (isset($_POST['id_provinsi']) ? $_POST['id_provinsi'] : NULL);
				$this->get_kabupaten(NULL, $id_provinsi);
				break;
			
			case 'history':
				$nomor	= (isset($_POST['nomor']) ? str_replace('-','/',$_POST['nomor']) : NULL);
				$this->get_history(NULL, $nomor, 'n', NULL);
				break;
            case 'nomor':
                $no = $this->generate_nomor(
                    array(
                        "connect" => TRUE,
                        "jenis_depo" => $this->input->post("jenis_depo", TRUE),
                        "pabrik" => $this->input->post("pabrik", TRUE)
                    )
                );
                if ($no)
                    $return = array('sts' => 'OK', 'msg' => $no);
                else
                    $return = array('sts' => 'NotOK');

                echo json_encode($return);
                break;
			
			case 'cek_depo':
				$id_depo_master  	= (isset($_POST['id_depo_master']) ? strtoupper($_POST['id_depo_master']) : NULL);
				$kode_sj  	= (isset($_POST['kode_sj']) ? strtoupper($_POST['kode_sj']) : NULL);
				$pabrik  	= (isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
				$this->get_cek_depo(NULL, $id_depo_master, $kode_sj, $pabrik);
				break;

            case 'master_depo':
                $post = $this->input->post_get(NULL, TRUE);
                $material = array();
				$material = $this->dtransaksidepo->get_data_master_depo(
					array(
						"connect" => TRUE,
						"search" => $this->general->emptyconvert(@$post['search']),
						"pabrik" => @$post['pabrik'],
						"not_in_depo" => $this->general->emptyconvert(@$post['not_in_depo']),
						"return" => 'array'
					)
				);
                $material = array_merge($material);
                $data_material  = array(
                    "total_count" => count($material),
                    "incomplete_results" => false,
                    "items" => $material
                );
                echo json_encode($data_material);
				exit;
            break;
			
            case 'master_biaya':
                $post = $this->input->post_get(NULL, TRUE);
                $biaya = array();
				$biaya = $this->dtransaksidepo->get_data_biaya(
					array(
						"connect" => TRUE,
						"search" => $this->general->emptyconvert(@$post['search']),
						"jenis_depo" => @$post['jenis_depo'],
						"jenis_biaya" => @$post['jenis_biaya'],
						"jenis_biaya_detail" => @$post['jenis_biaya_detail'],
						"not_in_biaya" => $this->general->emptyconvert(@$post['not_in_biaya']),
						"return" => 'array'
					)
				);
                $biaya = array_merge($biaya);
                $data_biaya  = array(
                    "total_count" => count($biaya),
                    "incomplete_results" => false,
                    "items" => $biaya
                );
                echo json_encode($data_biaya);
				exit;
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
					$return = $this->dtransaksidepo->get_data_depo_bom('open', $nomor, NULL, NULL, $jenis_depo_filter, $pabrik_filter, $status_filter, $view_data);
					echo $return;
					break;
				} else {
					$this->get_depo(NULL, $nomor);
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
			case 'depo':
				$this->save_depo($param2);
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
	private function get_matrix_mitra($array = NULL) {
		$matrix_mitra 	= $this->dmasterdepo->get_data_matrix_mitra("open");
		$matrix_mitra 	= $this->general->generate_encrypt_json($matrix_mitra);
		if ($array) {
			return $matrix_mitra;
		} else {
			echo json_encode($matrix_mitra);
		}
	}
	private function get_provinsi($array = NULL) {
		$provinsi 		= $this->dtransaksidepo->get_data_provinsi("open");
		if ($array) {
			return $provinsi;
		} else {
			echo json_encode($provinsi);
		}
	}
	private function get_kabupaten($array = NULL, $id_provinsi = NULL) {
		$kabupaten 		= $this->dtransaksidepo->get_data_kabupaten("open", $id_provinsi);
		if ($array) {
			return $kabupaten;
		} else {
			echo json_encode($kabupaten);
		}
	}
	
    private function save_approve()
    {
        $datetime 	= date("Y-m-d H:i:s");
        $post 		= $this->input->post(NULL, TRUE);
        $action 	= $post['action'];
        $nomor 		= $post['nomor'];
        $catatan 	= $post['komentar_approve_depo'];
        $status 	= $post['status_akhir'];
		$jenis_depo	= $post['jenis_depo'];
		$next_status = 	$this->dtransaksidepo->get_data_user_role_status("open", $status);
		$status		= 0;	//set default 
		if ($jenis_depo == 'tetap') {
			if($action=='approve'){
				$status = $next_status[0]->if_approve_pembukaan_tetap;
			}
			if($action=='decline'){
				$status = $next_status[0]->if_decline_pembukaan_tetap;
			}
		} else {	//mitra(trial)
			if($action=='approve'){
				$status = $next_status[0]->if_approve_pembukaan_trial;
			}
			if($action=='decline'){
				$status = $next_status[0]->if_decline_pembukaan_trial;
			}
		}
		
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        //========Update data status approval========//
        $data_row_header = array(
            "status" => $status
        );
        $data_row_header = $this->dgeneral->basic_column("update", $data_row_header, $datetime);
        $this->dgeneral->update('tbl_depo_data', $data_row_header, array(
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
		$this->dgeneral->insert("tbl_depo_data_log", $data_row_log);


        //========create DEPO when last Approve========//
		$error = false;
		if(($action=='approve')&&($status==999)){	
			$data_depo = $this->dtransaksidepo->get_data_depo(NULL, $nomor);
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
				$data_depo = $this->dtransaksidepo->get_data_depo(NULL, $nomor);
				//send email approval
				$this->send_email(
					array(
						"post" => $post,
						"header" => $data_depo
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

        if (isset($post->komentar_approve_depo))
            $comment = $post->komentar_approve_depo;
        else
            $comment = '';

        $data_recipient = $this->dtransaksidepo->get_email_recipient(
            array(
                "connect" => TRUE,
                "nomor" => $post->nomor
            )
        );

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
			$subject 	= "Notifikasi Pendaftaran Depo";
			$from_alias	= "OP-DEPO";
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
				"ACTIV" => 'X',
				"ZZKOTA" => $data[0]->nama_kabupaten,
				"ZZPROV" => $data[0]->nama_propinsi
				// "UNAME" => $data[0]->nama,
				// "UDATE" => $data[0]->nama,
				// "UZEIT" => $data[0]->nama,
				// "UDATE_DNET" => $data[0]->nama,
				// "UZEIT_DNET" => $data[0]->nama,
				// "BUDAT" => date('Ymd')
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
					'log_desc'      => "Berhasil Create Master Depo " . $nomor. " ke SAP",
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
                                Notifikasi Email Aplikasi Form Pendaftaran Depo
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
                                            <h1 style='margin-bottom: 0;'>Pendaftaran Depo Baru</h1>
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
        $message .= "<p>Email ini menandakan bahwa ada Pendaftaran Depo Baru yang membutuhkan perhatian anda.</p>";
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
                                <p>Selanjutnya anda dapat melakukan review pada Pendaftaran Depo tersebut</p><p>melalui aplikasi PORTAL di Portal Kiranaku.</p>
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

	private function save_depo($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);
		// echo json_encode($post);
		// exit;
		switch ($param) {
			case 'data_supplier':
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
			
				//get status
				$nik		= base64_decode($this->session->userdata("-nik-"));
				$posst		= base64_decode($this->session->userdata("-posst-"));
				$user_role	= $this->dtransaksidepo->get_data_user_role(NULL, $nik, $posst);
				$nomor		= $this->general->emptyconvert(@$post['nomor']);
				//==============
				//input tab supplier
				//==============
				$data_row = array(
					"id_depo_master"			=> $this->general->emptyconvert(strtoupper(@$post['id_depo_master'])),
					"kode_sj"					=> $this->general->emptyconvert(strtoupper(@$post['kode_sj'])),
					"kabupaten"					=> $this->general->emptyconvert(@$post['kabupaten']),
					"propinsi"					=> $this->general->emptyconvert(@$post['propinsi']),
					"jenis_depo"				=> $this->general->emptyconvert(@$post['jenis_depo']),
					"pabrik"					=> $this->general->emptyconvert(@$post['pabrik']),
					"nomor"						=> $nomor,
					"status"					=> $this->general->emptyconvert(@$user_role[0]->level),
					"nama"						=> $this->general->emptyconvert(@$post['nama']),
					"nip"						=> $this->general->emptyconvert(@$post['nip']),
					"npwp"						=> $this->general->emptyconvert(@$post['npwp']),
					"alamat_rumah"				=> $this->general->emptyconvert(@$post['alamat_rumah']),
					"alamat_depo"				=> $this->general->emptyconvert(@$post['alamat_depo']),
					"gps_depo"					=> $this->general->emptyconvert(@$post['gps_depo']),
					"pekerjaan"					=> $this->general->emptyconvert(@$post['pekerjaan']),
					"status_kepemilikan_tanah"	=> $this->general->emptyconvert(@$post['status_kepemilikan_tanah']),
					"status_sertifikat_tanah"	=> $this->general->emptyconvert(@$post['status_sertifikat_tanah']),
					"dana_pembelian_bokar"		=> $this->general->emptyconvert(@$post['dana_pembelian_bokar']),
					"rekomendasi_oleh"			=> $this->general->emptyconvert(@$post['rekomendasi_oleh']),
				);			
				//cek nomor
				$ck_nomor = $this->dtransaksidepo->get_data_depo(NULL, $nomor);
				if ((count($ck_nomor) != 0)){	
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_depo_data", $data_row, array(
						array(
							'kolom' => 'nomor',
							'value' => $nomor
						)
					));
				}else{
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_depo_data", $data_row);
				}

				//======set all tbl_depo_data_lokasi not active by number======//
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_depo_data_lokasi", $data, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
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
				//======insert jarak lokasi======//
				$data_lokasi_details = array();
				foreach ($post['id_lokasi'] as $index => $id_lokasi) {	
					$data_lokasi_detail    = array(
						"nomor" 		=> $nomor,
						"id_lokasi" 	=> $this->generate->kirana_decrypt($post['id_lokasi'][$index]),
						"jarak" 		=> (float)str_replace(',','',$post['jarak_lokasi'][$index]),
						"waktu" 		=> (float)str_replace(',','',$post['waktu_lokasi'][$index]),
						"keterangan" 	=> $post['keterangan_lokasi'][$index],
						"na" 			=> 'n',
						"del" 			=> 'n',
					);
					$data_lokasi_detail = $this->dgeneral->basic_column('insert', $data_lokasi_detail, $datetime);
					$data_lokasi_details[] = $data_lokasi_detail;
				}
				$this->dgeneral->insert_batch('tbl_depo_data_lokasi', $data_lokasi_details);
				//======insert jarak depo======//
				$data_depo_details = array();
				foreach ($post['id_depo'] as $index => $id_depo) {	
					$data_depo_detail    = array(
						"nomor" 		=> $nomor,
						"id_depo" 		=> $post['id_depo'][$index],
						"jarak" 		=> (float)str_replace(',','',$post['jarak_depo'][$index]),
						"waktu" 		=> (float)str_replace(',','',$post['waktu_depo'][$index]),
						"keterangan" 	=> $post['keterangan_lokasi'][$index],
						"na" 			=> 'n',
						"del" 			=> 'n',
					);
					$data_depo_detail = $this->dgeneral->basic_column('insert', $data_depo_detail, $datetime);
					$data_depo_details[] = $data_depo_detail;
				}
				$this->dgeneral->insert_batch('tbl_depo_data_lokasi', $data_depo_details);
				//======insert jarak gudang kompetitor======//
				$data_gudang_details = array();
				foreach ($post['gudang_kompetitor'] as $index => $gudang_kompetitor) {	
					$data_gudang_detail    = array(
						"nomor" 			=> $nomor,
						"gudang_kompetitor" => $post['gudang_kompetitor'][$index],
						"jarak" 			=> (float)str_replace(',','',$post['jarak_gudang'][$index]),
						"waktu" 			=> (float)str_replace(',','',$post['waktu_gudang'][$index]),
						"keterangan" 		=> $post['keterangan_gudang'][$index],
						"na" 				=> 'n',
						"del" 				=> 'n',
					);
					$data_gudang_detail = $this->dgeneral->basic_column('insert', $data_gudang_detail, $datetime);
					$data_gudang_details[] = $data_gudang_detail;
				}
				$this->dgeneral->insert_batch('tbl_depo_data_lokasi', $data_gudang_details);
				//======insert jarak pabrik kompetitor======//
				$data_pabrik_details = array();
				foreach ($post['pabrik_kompetitor'] as $index => $pabrik_kompetitor) {	
					$data_pabrik_detail    = array(
						"nomor" 			=> $nomor,
						"pabrik_kompetitor" => $post['pabrik_kompetitor'][$index],
						"jarak" 			=> (float)str_replace(',','',$post['jarak_pabrik'][$index]),
						"waktu" 			=> (float)str_replace(',','',$post['waktu_pabrik'][$index]),
						"keterangan" 		=> $post['keterangan_pabrik'][$index],
						"na" 				=> 'n',
						"del" 				=> 'n',
					);
					$data_pabrik_detail = $this->dgeneral->basic_column('insert', $data_pabrik_detail, $datetime);
					$data_pabrik_details[] = $data_pabrik_detail;
				}
				$this->dgeneral->insert_batch('tbl_depo_data_lokasi', $data_pabrik_details);
				
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg = "Data Depo-Supplier berhasil ditambahkan";
					$sts = "OK";
				}
				$this->general->closeDb();
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);

				break;
				
			case 'data_lingkungan':
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				$nomor		= $this->general->emptyconvert(@$post['nomor']);
				//==============
				//input tab lingkungan
				//==============
				$data_row = array(
					"luas_gudang"				=> $this->general->emptyconvert(@$post['luas_gudang']),
					"luas_tanah"				=> $this->general->emptyconvert(@$post['luas_tanah']),
					"akses_jalan"				=> $this->general->emptyconvert(@$post['akses_jalan']),
					"koneksi_internet"			=> $this->general->emptyconvert(@$post['koneksi_internet']),
				);			
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_depo_data", $data_row, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
					)
				));
				//===============================
				//======insert data gambar======//
				//===============================
				$data_gambar_details = array();
				foreach ($post['nama_foto'] as $index => $nama_foto) {
					//======input gambar======//
					$file_foto_upload = 'file_foto'.$index;			
					$nama_foto_upload = strtoupper(str_replace(' ','_',$post['nama_foto'][$index]));
					if($_FILES[$file_foto_upload]['name'][0]!=''){
						//======set tbl_depo_data_gambar not active======//
						$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
						$this->dgeneral->update("tbl_depo_data_gambar", $data, array(
							array(
								'kolom' => 'nomor',
								'value' => $nomor
							),
							array(
								'kolom' => 'na',
								'value' => 'n'
							),
							array(
								'kolom' => 'del',
								'value' => 'n'
							),
							array(
								'kolom' => 'id_gambar',
								'value' => $this->generate->kirana_decrypt($post['id_gambar'][$index])
							)
						));
						
						//buat upload gambar
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/foto';
						$config['allowed_types'] 	= 'jpg|png|JPG|PNG|jpeg|gif|GIF|JPEG';			
						$newname	= array(str_replace('/','_',$nomor).'-'.$nama_foto_upload);			
						$file		= $this->general->upload_files($_FILES[$file_foto_upload], $newname, $config);
						$nama_file	= $newname[0];
						// $url_file	= base_url().$file[0]['url'];
						$url_file	= $file[0]['url'];
						$tipe_file	= substr($file[0]['file_ext'], 1);
						$ukuran_file= $file[0]['size'];
						if($file === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
						//buat save db
						$data_gambar_detail    = array(
							"nomor" 		=> $nomor,
							"id_gambar"		=> $this->generate->kirana_decrypt($post['id_gambar'][$index]),
							"nama" 			=> $nama_file,
							"url" 			=> $url_file,
							"tipe" 			=> $tipe_file,
							"ukuran" 		=> $ukuran_file,
							"na" 			=> 'n',
							"del" 			=> 'n',
						);
						$data_gambar_detail = $this->dgeneral->basic_column("insert", $data_gambar_detail);
						$this->dgeneral->insert("tbl_depo_data_gambar", $data_gambar_detail);
					}
				}
				
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg = "Data Depo-Lingkungan berhasil ditambahkan";
					$sts = "OK";
				}
				$this->general->closeDb();
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				break;
				
			case 'data_aktifitas':
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				$nomor		= $this->general->emptyconvert(@$post['nomor']);
				//==============
				//input tab aktifitas
				//==============
				$data_row = array(
					"kualitas_bokar"			=> $this->general->emptyconvert(@$post['kualitas_bokar']),
					"cara_penyimpanan"			=> $this->general->emptyconvert(@$post['cara_penyimpanan']),
					"jenis_bokar"				=> $this->general->emptyconvert(@$post['jenis_bokar']),
					"jenis_pembayaran"			=> $this->general->emptyconvert(@$post['jenis_pembayaran']),
					"pph_22"					=> $this->general->emptyconvert(@$post['pph_22']),
					"pengelola_keuangan"		=> $this->general->emptyconvert(@$post['pengelola_keuangan']),
					"frekuensi_penjualan_mitra_per_minggu"		=> $this->general->emptyconvert(@$post['frekuensi_penjualan_mitra_per_minggu']),
					"volume_bokar_mitra_per_hari"				=> $this->general->emptyconvert(@$post['volume_bokar_mitra_per_hari']),
					"sumber_pendapatan_mitra"					=> $this->general->emptyconvert(@$post['sumber_pendapatan_mitra']),
					"frekuensi_penjualan_rekan_mitra_per_minggu"=> $this->general->emptyconvert(@$post['frekuensi_penjualan_rekan_mitra_per_minggu']),
					"volume_bokar_rekan_mitra_per_hari"			=> $this->general->emptyconvert(@$post['volume_bokar_rekan_mitra_per_hari']),
					"status_sosial_mitra"						=> $this->general->emptyconvert(@$post['status_sosial_mitra']),
					"total_volume_penjualan_per_hari"			=> $this->general->emptyconvert(@$post['total_volume_penjualan_per_hari']),
					"modal_kerja"								=> $this->general->emptyconvert(@$post['modal_kerja']),
					"estimasi_tonase_kering"					=> $this->general->emptyconvert(@$post['estimasi_tonase_kering']),
					"pengiriman_dana_bokar"						=> $this->general->emptyconvert(@$post['pengiriman_dana_bokar']),
					"rekening_tujuan"							=> $this->general->emptyconvert(@$post['rekening_tujuan']),
					"jumlah_pelelangan"							=> $this->general->emptyconvert(@$post['jumlah_pelelangan']),
					"jumlah_tronton_per_minggu"					=> $this->general->emptyconvert(@$post['jumlah_tronton_per_minggu']),
				);			
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_depo_data", $data_row, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
					)
				));
				
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg = "Data Depo-Aktifitas berhasil ditambahkan";
					$sts = "OK";
				}
				$this->general->closeDb();
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);

				break;
				
			case 'data_peta':
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				$nomor		= $this->general->emptyconvert(@$post['nomor']);
				//==============
				//input tab peta
				//==============
				//======set all tbl_depo_data_desa not active by number======//
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_depo_data_desa", $data, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
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
				//======insert data desa======//
				$data_desa_details = array();
				foreach ($post['nama_desa'] as $index => $nama_desa) {	
					$data_desa_detail    = array(
						"nomor" 		=> $nomor,
						"nama" 			=> $post['nama_desa'][$index],
						"luas" 			=> (float)str_replace(',','',$post['luas_desa'][$index]),
						"keterangan" 	=> $post['keterangan_desa'][$index],
						"na" 			=> 'n',
						"del" 			=> 'n',
					);
					$data_desa_detail = $this->dgeneral->basic_column('insert', $data_desa_detail, $datetime);
					$data_desa_details[] = $data_desa_detail;
				}
				$this->dgeneral->insert_batch('tbl_depo_data_desa', $data_desa_details);
				
				//======set all tbl_depo_data_survei not active by number======//
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_depo_data_survei", $data, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
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
				//======insert data survei======//
				$data_survei_details = array();
				foreach ($post['tanggal_survei'] as $index => $tanggal_survei) {	
					$data_survei_detail    = array(
						"nomor" 			=> $nomor,
						"tanggal" 			=> $this->generate->regenerateDateFormat($post['tanggal_survei'][$index]),
						// "tanggal" 			=> $post['tanggal_survei'][$index],
						"harga_per_hari"	=> (float)str_replace(',','',$post['harga_per_hari_survei'][$index]),
						"harga_notarin" 	=> (float)str_replace(',','',$post['harga_notarin_survei'][$index]),
						"harga_sicom" 		=> (float)str_replace(',','',$post['harga_sicom_survei'][$index]),
						"total_produksi" 	=> (float)str_replace(',','',$post['total_produksi_survei'][$index]),
						"rata_rata" 		=> (float)str_replace(',','',$post['rata_rata_survei'][$index]),
						"na" 				=> 'n',
						"del" 				=> 'n',
					);
					$data_survei_detail = $this->dgeneral->basic_column('insert', $data_survei_detail, $datetime);
					$data_survei_details[] = $data_survei_detail;
				}
				$this->dgeneral->insert_batch('tbl_depo_data_survei', $data_survei_details);
				
				//======set all tbl_depo_data_target not active by number======//
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_depo_data_target", $data, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
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
				//======insert data target======//
				$data_target = array(
					"nomor"	=> $nomor,
					"m1"	=> (float)str_replace(',','',$post['target_m1']),
					"m2"	=> (float)str_replace(',','',$post['target_m2']),
					"m3"	=> (float)str_replace(',','',$post['target_m3']),
					"m4"	=> (float)str_replace(',','',$post['target_m4']),
					"m5"	=> (float)str_replace(',','',$post['target_m5']),
					"m6"	=> (float)str_replace(',','',$post['target_m6']),
					"m7"	=> (float)str_replace(',','',$post['target_m7']),
					"m8"	=> (float)str_replace(',','',$post['target_m8']),
					"m9"	=> (float)str_replace(',','',$post['target_m9']),
					"m10"	=> (float)str_replace(',','',$post['target_m10']),
					"m11"	=> (float)str_replace(',','',$post['target_m11']),
					"m12"	=> (float)str_replace(',','',$post['target_m12']),
				);
				$data_target = $this->dgeneral->basic_column("insert", $data_target);
				$this->dgeneral->insert("tbl_depo_data_target", $data_target);

				
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg = "Data Depo-Aktifitas berhasil ditambahkan";
					$sts = "OK";
				}
				$this->general->closeDb();
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);

				break;
				
			case 'data_biaya':
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				$nomor		= $this->general->emptyconvert(@$post['nomor']);
				//==============
				//input tab biaya
				//==============
				//======set all tbl_depo_data_biaya_depo not active by number======//
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_depo_data_biaya_depo", $data, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
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
				//======insert data biaya depo======//
				$data_depo_details = array();
				foreach ($post['id_biaya_depo'] as $index => $id_biaya_depo) {	
					$data_depo_detail    = array(
						"nomor" 		=> $nomor,
						"id_biaya" 		=> $post['id_biaya_depo'][$index],
						"biaya" 		=> (float)str_replace(',','',$post['biaya_depo'][$index]),
						"tonase" 		=> (float)str_replace(',','',$post['tonase_depo'][$index]),
						"total" 		=> (float)str_replace(',','',$post['total_depo'][$index]),
						"na" 			=> 'n',
						"del" 			=> 'n',
					);
					$data_depo_detail = $this->dgeneral->basic_column('insert', $data_depo_detail, $datetime);
					$data_depo_details[] = $data_depo_detail;
				}
				$this->dgeneral->insert_batch('tbl_depo_data_biaya_depo', $data_depo_details);
				
				//======set all tbl_depo_data_biaya_sdm not active by number======//
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_depo_data_biaya_sdm", $data, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
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
				//======insert data biaya sdm======//
				$data_sdm_details = array();
				foreach ($post['id_biaya_sdm'] as $index => $id_biaya_sdm) {	
					$data_sdm_detail    = array(
						"nomor" 		=> $nomor,
						"id_biaya" 		=> $post['id_biaya_sdm'][$index],
						"jenis_budget"	=> $post['jenis_budget_sdm'][$index],
						// "nik" 			=> $post['nik_sdm'][$index],
						"nik" 			=> str_replace(',','',$post['nik_sdm'][$index]),
						"nama" 			=> $post['nama_sdm'][$index],
						"gaji_pokok" 	=> (float)str_replace(',','',$post['gaji_pokok_sdm'][$index]),
						"tunjangan" 	=> (float)str_replace(',','',$post['tunjangan_sdm'][$index]),
						"status" 		=> $post['status_sdm'][$index],
						"na" 			=> 'n',
						"del" 			=> 'n',
					);
					$data_sdm_detail = $this->dgeneral->basic_column('insert', $data_sdm_detail, $datetime);
					$data_sdm_details[] = $data_sdm_detail;
				}
				$this->dgeneral->insert_batch('tbl_depo_data_biaya_sdm', $data_sdm_details);
				
				//======set all tbl_depo_data_biaya_investasi not active by number======//
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_depo_data_biaya_investasi", $data, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
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
				//======insert data biaya investasi======//
				$data_investasi_details = array();
				foreach ($post['id_biaya_investasi'] as $index => $id_biaya_investasi) {	
					$data_investasi_detail    = array(
						"nomor" 		=> $nomor,
						"id_biaya" 		=> $post['id_biaya_investasi'][$index],
						"kepemilikan"	=> $post['kepemilikan_investasi'][$index],
						"jumlah" 		=> $post['jumlah_investasi'][$index],
						"harga" 		=> (float)str_replace(',','',$post['harga_investasi'][$index]),
						"total" 		=> (float)str_replace(',','',$post['total_investasi'][$index]),
						"keterangan" 	=> $post['keterangan_investasi'][$index],
						"na" 			=> 'n',
						"del" 			=> 'n',
					);
					$data_investasi_detail = $this->dgeneral->basic_column('insert', $data_investasi_detail, $datetime);
					$data_investasi_details[] = $data_investasi_detail;
				}
				$this->dgeneral->insert_batch('tbl_depo_data_biaya_investasi', $data_investasi_details);
				
				//======set all tbl_depo_data_biaya_trans(darat dan air) not active by number======//
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_depo_data_biaya_trans", $data, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
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
				//======insert data biaya transportasi darat======//
				if(isset($post['nama_vendor_darat'])){
					$data_darat_details = array();
					foreach ($post['nama_vendor_darat'] as $index => $nama_vendor_darat) {	
						$data_darat_detail    = array(
							"nomor" 			=> $nomor,
							"nomor_vendor" 		=> $post['nomor_vendor_darat'][$index],
							"nama_vendor" 		=> $post['nama_vendor_darat'][$index],
							"jenis_trans"		=> 'darat',
							"penentuan_tarif" 	=> $post['penentuan_tarif_darat'][$index],
							"kapasitas_basah" 	=> (float)str_replace(',','',$post['kapasitas_basah_darat'][$index]),
							"biaya_per_trip" 	=> (float)str_replace(',','',$post['biaya_per_trip_darat'][$index]),
							"biaya_per_kg" 		=> (float)str_replace(',','',$post['biaya_per_kg_darat'][$index]),
							"na" 				=> 'n',
							"del" 				=> 'n',
						);
						$data_darat_detail = $this->dgeneral->basic_column('insert', $data_darat_detail, $datetime);
						$data_darat_details[] = $data_darat_detail;
					}
					$this->dgeneral->insert_batch('tbl_depo_data_biaya_trans', $data_darat_details);
				}
				//======insert data biaya transportasi air======//
				if(isset($post['nama_vendor_air'])){
					$data_air_details = array();
					foreach ($post['nama_vendor_air'] as $index => $nama_vendor_air) {	
						$data_air_detail    = array(
							"nomor" 			=> $nomor,
							"nomor_vendor" 		=> $post['nomor_vendor_air'][$index],
							"nama_vendor" 		=> $post['nama_vendor_air'][$index],
							"jenis_trans" 		=> 'air',
							"kapasitas_basah" 	=> (float)str_replace(',','',$post['kapasitas_basah_air'][$index]),
							"biaya_per_trip" 	=> (float)str_replace(',','',$post['biaya_per_trip_air'][$index]),
							"biaya_per_kg" 		=> (float)str_replace(',','',$post['biaya_per_kg_air'][$index]),
							"na" 				=> 'n',
							"del" 				=> 'n',
						);
						$data_air_detail = $this->dgeneral->basic_column('insert', $data_air_detail, $datetime);
						$data_air_details[] = $data_air_detail;
					}
					$this->dgeneral->insert_batch('tbl_depo_data_biaya_trans', $data_air_details);
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

				break;
				
			case 'data_dokumen':
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				$nomor		= $this->general->emptyconvert(@$post['nomor']);
				//======set all tbl_depo_data_dokumen not active by number======//
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_depo_data_dokumen", $data, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
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
				//input dokumen
				$data_dokumen_details = array();
				foreach ($post['nama_dokumen'] as $index => $nama_dokumen) {
					$file_lampiran_upload = 'file_lampiran'.$index;		
					$nama_lampiran_upload = strtoupper(str_replace(' ','_',$post['nama_dokumen'][$index]));
					if($_FILES[$file_lampiran_upload]['name'][0]!=''){
						//buat upload lampiran
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/lampiran';
						$config['allowed_types'] 	= 'jpg|png|JPG|PNG|jpeg|pdf|PDF|xls|xlsx|doc|docx';			
						$newname	= array(str_replace('/','_',$post['nomor']).'-'.$nama_lampiran_upload);			
						$file		= $this->general->upload_files($_FILES[$file_lampiran_upload], $newname, $config);
						$nama_file	= $newname[0];
						$url_file	= $file[0]['url'];
						$tipe_file	= substr($file[0]['file_ext'], 1);
						$ukuran_file= $file[0]['size'];
						if($file === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
						//buat save db
						$data_dokumen_detail    = array(
							"nomor" 		=> $nomor,
							"id_dokumen"	=> $this->generate->kirana_decrypt($post['id_dokumen'][$index]),
							"nama" 			=> $nama_file,
							"url" 			=> $url_file,
							"tipe" 			=> $tipe_file,
							"ukuran" 		=> $ukuran_file,
							"na" 			=> 'n',
							"del" 			=> 'n',
						);
						$data_dokumen_detail = $this->dgeneral->basic_column("insert", $data_dokumen_detail);
						$this->dgeneral->insert("tbl_depo_data_dokumen", $data_dokumen_detail);
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

				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
		
		
	}
	private function get_history($array = NULL, $nomor = NULL, $active = NULL, $deleted = NULL)
	{
		$history 	= $this->dtransaksidepo->get_data_history("open", $nomor, $active, $deleted);
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
		return $this->dtransaksidepo->get_nomor(
			array(
				"connect" => $param['connect'],
				"jenis_depo" => $jenis_depo,
				"pabrik" => $pabrik,
				"month" => $month,
				"year" => $year
			)
		)->nomor;
	}
	private function get_cek_depo($array = NULL, $id_depo_master = NULL, $kode_sj = NULL, $pabrik = NULL) {
		$data	= $this->dtransaksidepo->get_data_cek_depo("open", $id_depo_master, $kode_sj, $pabrik);
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	
	private function get_depo($array = NULL, $nomor = NULL)
	{
		//header
		$data	= $this->dtransaksidepo->get_data_depo("open", $nomor);
		$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
		
		$kabupaten		= $this->dtransaksidepo->get_data_kabupaten("array", $data[0]->propinsi);
		$data[0]->arr_kabupaten	= $kabupaten;

		//detail
		$data_biaya_depo		= $this->get_biaya_depo("array", $data[0]->id_data, $data[0]->nomor);
		$data_biaya_investasi	= $this->get_biaya_investasi("array", $data[0]->id_data, $data[0]->nomor);
		$data_biaya_sdm			= $this->get_biaya_sdm("array", $data[0]->id_data, $data[0]->nomor);
		$data_biaya_trans		= $this->get_biaya_trans("array", $this->generate->kirana_decrypt($data[0]->id_data), $data[0]->nomor);
		$data_desa				= $this->get_desa("array", $this->generate->kirana_decrypt($data[0]->id_data), $data[0]->nomor);
		$data_dokumen			= $this->get_dokumen("array", $data[0]->id_data, $data[0]->nomor, $data[0]->jenis_depo);
		$data_gambar			= $this->get_gambar("array", $data[0]->id_data, $data[0]->nomor);
		$data_lokasi			= $this->get_lokasi("array", $data[0]->id_data, $data[0]->nomor, $data[0]->pabrik);
		$data_survei			= $this->get_survei("array", $this->generate->kirana_decrypt($data[0]->id_data), $data[0]->nomor);
		$data_target			= $this->get_target("array", $this->generate->kirana_decrypt($data[0]->id_data), $data[0]->nomor);
		//detail approve
		$data_master_nilai		= $this->dmasterdepo->get_data_nilai("array", NULL, 'n', 'n');
		$data_matrix			= $this->dmasterdepo->get_data_matrix_header("array", NULL, 'n', 'n');
		$data_scoring			= $this->dmasterdepo->get_data_matrix_scoring("array", NULL, 'n', 'n', $nomor);
		$data_biaya_detail		= $this->get_biaya_detail("array", $data[0]->id_data, $data[0]->nomor);

		//detail
		$data[0]->arr_data_biaya_depo = $data_biaya_depo;
		$data[0]->arr_data_biaya_investasi = $data_biaya_investasi;
		$data[0]->arr_data_biaya_sdm = $data_biaya_sdm;
		$data[0]->arr_data_biaya_trans = $data_biaya_trans;
		$data[0]->arr_data_desa = $data_desa;
		$data[0]->arr_data_dokumen = $data_dokumen;
		$data[0]->arr_data_gambar = $data_gambar;
		$data[0]->arr_data_lokasi = $data_lokasi;
		$data[0]->arr_data_survei = $data_survei;
		$data[0]->arr_data_target = $data_target;
		//detail approve
		$data[0]->arr_data_master_nilai = $data_master_nilai;
		$data[0]->arr_data_matrix 		= $data_matrix;
		$data[0]->arr_data_scoring 		= $data_scoring;
		$data[0]->arr_data_biaya_detail = $data_biaya_detail;

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
	
	private function get_biaya_detail($array = NULL, $id_data = NULL, $nomor = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_biaya_detail("open", $id_data, $nomor);
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
	private function get_desa($array = NULL, $id_data = NULL, $nomor = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_desa("open", $id_data, $nomor);
		$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_dokumen($array = NULL, $id_data = NULL, $nomor = NULL, $jenis_depo = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_dokumen("open", $id_data, $nomor, $jenis_depo);
		$data 	= $this->general->generate_encrypt_json($data, array("id_dokumen"));
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_gambar($array = NULL, $id_data = NULL, $nomor = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_gambar("open", $id_data, $nomor);
		$data 	= $this->general->generate_encrypt_json($data, array("id_gambar"));
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_lokasi($array = NULL, $id_data = NULL, $nomor = NULL, $pabrik = NULL)
	{
		$data	= $this->dtransaksidepo->get_data_lokasi("open", $id_data, $nomor, $pabrik);
		$data 	= $this->general->generate_encrypt_json($data, array("id_lokasi"));
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
	
	/*====================================================================*/
}
