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

// class Realisasi extends MX_Controller
class Realisasi extends BaseControllers
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('PHPExcel');
		$this->load->helper(array('form', 'url'));

		$this->load->model('dmasterdepo');
		$this->load->model('dsettingdepo');
		$this->load->model('dtransaksidepo');
		$this->load->model('dpenutupandepo');
		$this->load->model('drealisasidepo');
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
		$data['title']    	= "Approve Realisasi Penutupan Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);;
		$this->load->view("realisasi/approve", $data);
	}
	public function data($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data Realisasi Penutupan Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);;
		$this->load->view("realisasi/data", $data);
	}
	
	public function edit($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Edit Realisasi Penutupan Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['nomor'] 		= str_replace("-", "/", $param);		
		
		$this->load->view("realisasi/edit", $data);
	}
	
	public function detail($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Detail Realisasi Penutupan Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['nomor'] 		= str_replace("-", "/", $param);		
		
		$this->load->view("realisasi/detail", $data);
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
					$return = $this->drealisasidepo->get_realisasi_depo_bom('open', $nomor, NULL, NULL, $pabrik_filter, $status_filter, $view_data);
					echo $return;
					break;
				} else {
					$this->get_penutupan(NULL, $nomor);
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
			case 'realisasi':
				$this->save_realisasi($param2);
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
	private function save_realisasi($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html 		= false;
        $post 		= $this->input->post(NULL, TRUE);
		$nomor		= $this->general->emptyconvert(@$post['nomor']);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		//======update tbl_depo_penutupan_sdm======//
		foreach ($post['nik'] as $index => $nik) {	
			$data_sdm    = array(
				"status_aktual"		=> $post['sdm_status_aktual'][$index],
				"lokasi_aktual"		=> $post['sdm_lokasi_aktual'][$index],
				"tanggal_aktual"	=> date("Y-m-d", strtotime($post['sdm_tanggal_aktual'][$index])),
			);
			$this->dgeneral->update("tbl_depo_penutupan_sdm", $data_sdm, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				),
				array(
					'kolom' => 'nik',
					'value' => $post['nik'][$index],
				),
				array(
					'kolom' => 'na',
					'value' => 'n'
				)
			));
		}
		//======update tbl_depo_penutupan_asset======//
		foreach ($post['asset'] as $index => $asset) {	
			$data_asset    = array(
				"status_aktual"		=> $post['asset_status_aktual'][$index],
				"lokasi_aktual"		=> $post['asset_lokasi_aktual'][$index],
				"tanggal_aktual"	=> date("Y-m-d", strtotime($post['asset_tanggal_aktual'][$index])),
			);
			$this->dgeneral->update("tbl_depo_penutupan_asset", $data_asset, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				),
				array(
					'kolom' => 'kode',
					'value' => $post['asset'][$index],
				),
				array(
					'kolom' => 'na',
					'value' => 'n'
				)
			));
		}
		//======update tbl_depo_penutupan_keuangan======//
		if(isset($post['id_keuangan'])){
			foreach ($post['id_keuangan'] as $index => $id_keuangan) {	
				$data_keuangan    = array(
					"penyelesaian_aktual"	=> (float)str_replace(',','',$post['keuangan_penyelesaian_aktual'][$index]),
					"tanggal_aktual"		=> date("Y-m-d", strtotime($post['keuangan_tanggal_aktual'][$index])),
				);
				$this->dgeneral->update("tbl_depo_penutupan_keuangan", $data_keuangan, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
					),
					array(
						'kolom' => 'id_keuangan',
						'value' => $this->generate->kirana_decrypt($post['id_keuangan'][$index]),
					),
					array(
						'kolom' => 'na',
						'value' => 'n'
					)
				));
			}
			
		}
		
		//======update tbl_depo_penutupan_bokar======//
		$data_bokar = array(
			"penyelesaian_aktual"	=> (float)str_replace(',','',$post['bokar_penyelesaian_aktual']),
			"tanggal_aktual"		=> date("Y-m-d", strtotime($post['bokar_tanggal_aktual'])),
		);			
		$this->dgeneral->update("tbl_depo_penutupan_bokar", $data_bokar, array(
			array(
				'kolom' => 'nomor',
				'value' => $nomor
			),
			array(
				'kolom' => 'nama',
				'value' => $post['bokar_nama'],
			),
			array(
				'kolom' => 'na',
				'value' => 'n'
			)
		));

		//======update tbl_depo_penutupan_lain======//
		if(isset($post['id_keuangan'])){
			foreach ($post['lain_nama'] as $index => $lain_nama) {	
				$data_lain    = array(
					"penyelesaian_aktual"	=> (float)str_replace(',','',$post['lain_penyelesaian_aktual'][$index]),
					"tanggal_aktual"		=> date("Y-m-d", strtotime($post['lain_tanggal_aktual'][$index])),
				);
				$this->dgeneral->update("tbl_depo_penutupan_lain", $data_lain, array(
					array(
						'kolom' => 'nomor',
						'value' => $nomor
					),
					array(
						'kolom' => 'nama',
						'value' => $post['lain_nama'][$index],
					),
					array(
						'kolom' => 'na',
						'value' => 'n'
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
			$msg = "Update Realisasi Penutupan Depo Berhasil.";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
    private function save_approve()
    {
        $datetime 		= date("Y-m-d H:i:s");
        $post 			= $this->input->post(NULL, TRUE);
        $action 		= $post['action'];
        $nomor 			= $post['nomor'];
        $catatan 		= $post['komentar_realisasi'];
        $status 		= $post['status_akhir'];
		$jenis_depo		= $post['jenis_depo'];
		$id_depo_master	= $post['id_depo_master'];
		$next_status_data 	= 	$this->dtransaksidepo->get_data_user_role_status("open", $status);
		$next_status		= 0;	//set default 
		$error		= false;
		
		if ($jenis_depo == 'tetap') {
			if($action=='approve'){
				$next_status = $next_status_data[0]->if_approve_realisasi_tetap;
			}
			if($action=='decline'){
				$next_status = $next_status_data[0]->if_decline_realisasi_tetap;
			}
		} else {	//mitra(trial)
			if($action=='approve'){
				$next_status = $next_status_data[0]->if_approve_realisasi_trial;
			}
			if($action=='decline'){
				$next_status = $next_status_data[0]->if_decline_realisasi_trial;
			}
		}
		
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        //========Update data status approval========//
		$data_row_header = array(
			"status_realisasi" => $next_status
		);
        $data_row_header = $this->dgeneral->basic_column("update", $data_row_header, $datetime);
        $this->dgeneral->update('tbl_depo_penutupan', $data_row_header, array(
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
			"catatan"	=> $catatan,
			"realisasi"	=> 'y'
		);
		$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
		$this->dgeneral->insert("tbl_depo_penutupan_log", $data_row_log);
		
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
				$data_penutupan = $this->dpenutupandepo->get_data_penutupan(NULL, $nomor);
				//send email approval
				$this->send_email(
					array(
						"post" => $post,
						"header" => $data_penutupan
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

        if (isset($post->komentar_realisasi))
            $comment = $post->komentar_realisasi;
        else
            $comment = '';

        $data_recipient = $this->drealisasidepo->get_email_recipient(
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
			$subject 	= "Notifikasi Realisasi Penutupan Depo";
			$from_alias	= "TP-DEPO";
			$this->general->send_email($subject, $from_alias, $email_to, $email_cc, $message);
        return true;
    }

    private function generate_email_message($param = NULL)
    {
		$message = "<html>
                        <body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
                        <center style='width: 100%;'>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                Notifikasi Email Aplikasi Form Realisasi Penutupan Depo
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
                                            <h1 style='margin-bottom: 0;'>Realisasi Penutupan Depo</h1>
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
        $message .= "<p>Email ini menandakan bahwa ada Realisasi Penutupan Depo yang membutuhkan perhatian anda.</p>";
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
                                <p>Selanjutnya anda dapat melakukan review pada Realisasi Penutupan Depo tersebut</p><p>melalui aplikasi PORTAL di Portal Kiranaku.</p>
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
	
	private function get_penutupan($array = NULL, $nomor = NULL)
	{
		//header
		$data	= $this->dpenutupandepo->get_data_penutupan("open", $nomor);
		$data 	= $this->general->generate_encrypt_json($data, array("id_data"));
		//detail
		$data_sdm		= $this->dpenutupandepo->get_data_penutupan_sdm("array", $nomor);
		$data_asset		= $this->dpenutupandepo->get_data_penutupan_asset("array", $nomor);
		$data_keuangan	= $this->dpenutupandepo->get_data_penutupan_keuangan("array", $nomor);
		$data_keuangan 	= $this->general->generate_encrypt_json($data_keuangan, array("id_keuangan"));
		$data_bokar		= $this->dpenutupandepo->get_data_penutupan_bokar("array", $nomor);
		$data_lain		= $this->dpenutupandepo->get_data_penutupan_lain("array", $nomor);


		$data[0]->arr_data_sdm 		= $data_sdm;
		$data[0]->arr_data_asset 	= $data_asset;
		$data[0]->arr_data_keuangan = $data_keuangan;
		$data[0]->arr_data_bokar 	= $data_bokar;
		$data[0]->arr_data_lain 	= $data_lain;
		
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
		$history 	= $this->drealisasidepo->get_data_history("open", $nomor, $active, $deleted);
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
