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

// class Penutupan extends MX_Controller
class Penutupan extends BaseControllers
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
	}

	public function index()
	{
		show_404();
	}
	
	public function input($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Input Penutupan Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['nomor'] 		= str_replace("-", "/", $param);		
		
		$this->load->view("penutupan/input", $data);
	}
	public function approve($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['title']    	= "Approve Penutupan Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);;
		$this->load->view("penutupan/approve", $data);
	}
	public function data($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data Penutupan Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);;
		$this->load->view("penutupan/data", $data);
	}
	
	public function edit($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Edit Penutupan Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['nomor'] 		= str_replace("-", "/", $param);		
		
		$this->load->view("penutupan/edit", $data);
	}
	
	public function detail($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Detail Penutupan Depo";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksidepo->get_data_user_role("open", $nik, $posst);
		$data['nomor'] 		= str_replace("-", "/", $param);		
		
		$this->load->view("penutupan/detail", $data);
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
			case 'master_sdm':
				$jenis_biaya_detail 	= (isset($_POST['jenis_biaya_detail']) ? $_POST['jenis_biaya_detail'] : NULL);
				$this->get_master_sdm(NULL, $jenis_biaya_detail);
				break;
			case 'depo_auto':
				$post = $this->input->post_get(NULL, TRUE);
				$this->get_depo_autocomplete();
				break;
			case 'data_depo':
				$id_depo_master = (isset($_POST['id_depo_master']) ? $this->generate->kirana_decrypt($_POST['id_depo_master']) : NULL);
				$this->get_depo(NULL, $id_depo_master);
				break;
            case 'master_karyawan':
                $post = $this->input->post_get(NULL, TRUE);
                $biaya = array();
				$biaya = $this->dpenutupandepo->get_data_karyawan(
					array(
						"connect" => TRUE,
						"search" => $this->general->emptyconvert(@$post['search']),
						"pabrik" => @$post['pabrik'],
						"not_in_nik" => $this->general->emptyconvert(@$post['not_in_nik']),
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
            case 'master_asset':
                $post = $this->input->post_get(NULL, TRUE);
                $asset = array();
				$asset = $this->dpenutupandepo->get_data_asset(
					array(
						"connect" => TRUE,
						"search" => $this->general->emptyconvert(@$post['search']),
						"pabrik" => @$post['pabrik'],
						"not_in_asset" => $this->general->emptyconvert(@$post['not_in_asset']),
						"return" => 'array'
					)
				);
                $asset = array_merge($asset);
                $data_asset  = array(
                    "total_count" => count($asset),
                    "incomplete_results" => false,
                    "items" => $asset
                );
                echo json_encode($data_asset);
				exit;
            break;
				
				
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
					$return = $this->dpenutupandepo->get_penutupan_depo_bom('open', $nomor, NULL, NULL, $pabrik_filter, $status_filter, $view_data);
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
			case 'penutupan':
				$this->save_penutupan($param2);
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
	private function save_penutupan($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		//get status
		$nik		= base64_decode($this->session->userdata("-nik-"));
		$posst		= base64_decode($this->session->userdata("-posst-"));
		$user_role	= $this->dtransaksidepo->get_data_user_role(NULL, $nik, $posst);
		$nomor		= $this->general->emptyconvert(@$post['nomor']);

		//==============
		//input header
		//==============
		$ck_penutupan = $this->dpenutupandepo->get_data_penutupan(NULL, $nomor);
		if (count($ck_penutupan) == 0){
			$data_row = array(
				"id_depo_master"			=> $this->generate->kirana_decrypt($post['id_depo_master']),
				"pabrik"					=> $this->general->emptyconvert(@$post['pabrik']),
				"nomor"						=> $nomor,
				"status"					=> $this->general->emptyconvert(@$user_role[0]->level),
			);			
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_depo_penutupan", $data_row);
		} 

		//==============
		//input detail
		//==============
		//======insert tbl_depo_penutupan_sdm======//
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_penutupan_sdm", $data, array(
			array(
				'kolom' => 'nomor',
				'value' => $nomor
			)
		));
		$data_sdms = array();
		foreach ($post['nik'] as $index => $nik) {	
			$data_sdm    = array(
				"nomor" 			=> $nomor,
				"id_biaya"			=> $post['id_biaya_sdm'][$index],
				"nik" 				=> $post['nik'][$index],
				"status_rencana"	=> $post['sdm_status_rencana'][$index],
				"lokasi_rencana"	=> $post['sdm_lokasi_rencana'][$index],
				"tanggal_rencana"	=> date("Y-m-d", strtotime($post['sdm_tanggal_rencana'][$index])),
				//diset sama dengan rencana(pada saat pengajuan)
				"status_aktual"		=> $post['sdm_status_rencana'][$index],	
				"lokasi_aktual"		=> $post['sdm_lokasi_rencana'][$index],
				"tanggal_aktual"	=> date("Y-m-d", strtotime($post['sdm_tanggal_rencana'][$index])), 
				"na" 				=> 'n',
				"del" 				=> 'n',
			);
			$data_sdm = $this->dgeneral->basic_column('insert', $data_sdm, $datetime);
			$data_sdms[] = $data_sdm;
		}
		$this->dgeneral->insert_batch('tbl_depo_penutupan_sdm', $data_sdms);

		//======insert tbl_depo_penutupan_asset======//
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_penutupan_asset", $data, array(
			array(
				'kolom' => 'nomor',
				'value' => $nomor
			)
		));
		$data_assets = array();
		foreach ($post['asset'] as $index => $asset) {	
			$data_asset    = array(
				"nomor" 			=> $nomor,
				"kode"				=> $post['asset'][$index],
				"jumlah"			=> (float)str_replace(',','',$post['asset_jumlah'][$index]),
				"status_rencana"	=> $post['asset_status_rencana'][$index],
				"lokasi_rencana"	=> $post['asset_lokasi_rencana'][$index],
				"tanggal_rencana"	=> date("Y-m-d", strtotime($post['asset_tanggal_rencana'][$index])),
				//diset sama dengan rencana(pada saat pengajuan)
				"status_aktual"		=> $post['asset_status_rencana'][$index],
				"lokasi_aktual"		=> $post['asset_lokasi_rencana'][$index],
				"tanggal_aktual"	=> date("Y-m-d", strtotime($post['asset_tanggal_rencana'][$index])),
				"na" 				=> 'n',
				"del" 				=> 'n',
			);
			$data_asset = $this->dgeneral->basic_column('insert', $data_asset, $datetime);
			$data_assets[] = $data_asset;
		}
		$this->dgeneral->insert_batch('tbl_depo_penutupan_asset', $data_assets);
		
		//======insert tbl_depo_penutupan_keuangan======//
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_penutupan_keuangan", $data, array(
			array(
				'kolom' => 'nomor',
				'value' => $nomor
			)
		));
		$data_keuangans = array();
		foreach ($post['id_keuangan'] as $index => $id_keuangan) {	
			$data_keuangan    = array(
				"nomor" 				=> $nomor,
				"id_keuangan"			=> $this->generate->kirana_decrypt($post['id_keuangan'][$index]),
				"jumlah"				=> (float)str_replace(',','',$post['keuangan_jumlah'][$index]),
				"penyelesaian_rencana"	=> (float)str_replace(',','',$post['keuangan_penyelesaian_rencana'][$index]),
				"tanggal_rencana"		=> date("Y-m-d", strtotime($post['keuangan_tanggal_rencana'][$index])),
				//diset sama dengan rencana(pada saat pengajuan)
				"penyelesaian_aktual"	=> (float)str_replace(',','',$post['keuangan_penyelesaian_rencana'][$index]),
				"tanggal_aktual"		=> date("Y-m-d", strtotime($post['keuangan_tanggal_rencana'][$index])),
				"na" 					=> 'n',
				"del" 					=> 'n',
			);
			$data_keuangan = $this->dgeneral->basic_column('insert', $data_keuangan, $datetime);
			$data_keuangans[] = $data_keuangan;
		}
		$this->dgeneral->insert_batch('tbl_depo_penutupan_keuangan', $data_keuangans);
		
		//======insert tbl_depo_penutupan_bokar======//
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_penutupan_bokar", $data, array(
			array(
				'kolom' => 'nomor',
				'value' => $nomor
			)
		));
		$data_bokar = array(
			"nomor" 				=> $nomor,
			"nama"					=> $post['bokar_nama'],
			"jumlah"				=> (float)str_replace(',','',$post['bokar_jumlah']),
			"penyelesaian_rencana"	=> (float)str_replace(',','',$post['bokar_penyelesaian_rencana']),
			"tanggal_rencana"		=> date("Y-m-d", strtotime($post['bokar_tanggal_rencana'])),
			//diset sama dengan rencana(pada saat pengajuan)
			"penyelesaian_aktual"	=> (float)str_replace(',','',$post['bokar_penyelesaian_rencana']),
			"tanggal_aktual"		=> date("Y-m-d", strtotime($post['bokar_tanggal_rencana'])),
		);			
		$data_bokar = $this->dgeneral->basic_column("insert", $data_bokar);
		$this->dgeneral->insert("tbl_depo_penutupan_bokar", $data_bokar);

		//======insert tbl_depo_penutupan_lain======//
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_penutupan_lain", $data, array(
			array(
				'kolom' => 'nomor',
				'value' => $nomor
			)
		));
		if(isset($post['lain_nama'])){
			$data_lains = array();
			foreach ($post['lain_nama'] as $index => $lain_nama) {	
				$data_lain    = array(
					"nomor" 				=> $nomor,
					"nama"					=> $post['lain_nama'][$index],
					"jumlah"				=> (float)str_replace(',','',$post['lain_jumlah'][$index]),
					"penyelesaian_rencana"	=> (float)str_replace(',','',$post['lain_penyelesaian_rencana'][$index]),
					"tanggal_rencana"		=> date("Y-m-d", strtotime($post['lain_tanggal_rencana'][$index])),
					//diset sama dengan rencana(pada saat pengajuan)
					"penyelesaian_aktual"	=> (float)str_replace(',','',$post['lain_penyelesaian_rencana'][$index]),
					"tanggal_aktual"		=> date("Y-m-d", strtotime($post['lain_tanggal_rencana'][$index])),
					"na" 					=> 'n',
					"del" 					=> 'n',
				);
				$data_lain = $this->dgeneral->basic_column('insert', $data_lain, $datetime);
				$data_lains[] = $data_lain;
			}
			$this->dgeneral->insert_batch('tbl_depo_penutupan_lain', $data_lains);
		}


		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Pengajuan Penutupan Depo Berhasil.";
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
        $catatan 	= $post['komentar_penutupan'];
        $status 	= $post['status_akhir'];
		$jenis_depo	= $post['jenis_depo'];
		$id_depo_master	= $post['id_depo_master'];
		$next_status_data = 	$this->dtransaksidepo->get_data_user_role_status("open", $status);
		$next_status		= 0;	//set default 
		$error		= false;

		if (strtolower($jenis_depo) == 'tetap') {
			if($action=='approve'){
				$next_status 		= $next_status_data[0]->if_approve_penutupan_tetap;
				$status_realisasi 	= $next_status_data[0]->if_penutupan_tetap_complete;
			}
			if($action=='decline'){
				$next_status = $next_status_data[0]->if_decline_penutupan_tetap;
			}
		} else {	//mitra(trial)
			if($action=='approve'){
				$next_status 		= $next_status_data[0]->if_approve_penutupan_trial;
				$status_realisasi 	= $next_status_data[0]->if_penutupan_trial_complete;
			}
			if($action=='decline'){
				$next_status = $next_status_data[0]->if_decline_penutupan_trial;
			}
		}
		
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        //========Update data status approval========//
		if($next_status==999){	//jika approval terakhir
			$data_row_header = array(
				"status" => $next_status,
				"status_realisasi" => $status_realisasi
			);
		}else{
			$data_row_header = array(
				"status" => $next_status
			);
		}
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
			"realisasi"	=> 'n'
		);
		$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
		$this->dgeneral->insert("tbl_depo_penutupan_log", $data_row_log);
		
        //========update DEPO when last Approve========//
		if(($action=='approve')&&($next_status==999)){	
			$data_depo = $this->dpenutupandepo->get_data_depo(NULL, $id_depo_master);
			
            $sap_depo = $this->update_depo_sap(
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

    private function update_depo_sap($param = NULL){
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
			
			// echo json_encode($param_rfc);
			// exit;
			
			$iserror = false;
			$result = $this->data['sap']->callFunction('Z_RFC_CREATEDEPOMASTER', $param_rfc);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && empty($result["T_RETURN"]) && !$iserror){
				$data_row_log = array(
					'app'           => 'DATA RFC CREATE DEPO',
					'rfc_name'      => 'Z_RFC_CREATEDEPOMASTER',
					'log_code'      => 'S',
					'log_status'    => 'Berhasil',
					'log_desc'      => "Berhasil Flag Delete Master Depo " . $nomor. " ke SAP",
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

        if (isset($post->komentar_penutupan))
            $comment = $post->komentar_penutupan;
        else
            $comment = '';

        $data_recipient = $this->dpenutupandepo->get_email_recipient(
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
			$subject 	= "Notifikasi Penutupan Depo";
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
                                Notifikasi Email Aplikasi Form Penutupan Depo
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
                                            <h1 style='margin-bottom: 0;'>Penutupan Depo</h1>
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
        $message .= "<p>Email ini menandakan bahwa ada Penutupan Depo yang membutuhkan perhatian anda.</p>";
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
                                <p>Selanjutnya anda dapat melakukan review pada Penutupan Depo tersebut</p><p>melalui aplikasi PORTAL di Portal Kiranaku.</p>
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
	private function get_master_sdm($array = NULL, $jenis_biaya_detail = NULL) {
		$sdm 		= $this->dpenutupandepo->get_data_master_sdm("open", $jenis_biaya_detail);
		if ($array) {
			return $sdm;
		} else {
			echo json_encode($sdm);
		}
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
		$history 	= $this->dpenutupandepo->get_data_history("open", $nomor, $active, $deleted);
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
