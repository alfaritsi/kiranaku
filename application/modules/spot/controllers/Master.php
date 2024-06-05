<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Simulasi Penjualan SPOT
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Master extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('PHPExcel');
		$this->load->helper(array('form', 'url'));

		$this->load->model('dmasterpol');
	}

	public function index()
	{
		show_404();
	}
	public function pol($param = NULL)
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	 = "Port Of Load";
		$data['title_form']  = "Setting Port Of Load";
		$data['port'] 	 	 = $this->get_port('array');
		$data['plant']       = $this->get_plant('array');
		$data['pol'] 	 	 = $this->get_pol('array', NULL, NULL, NULL);
		// echo json_encode($data['pol']);
		$this->load->view("master/pol", $data);
	}
	public function cost($param = NULL)
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	 = "Production Cost";
		$data['title_form']  = "Setting Production Cost";
		$data['cost'] 	 	 = $this->get_cost('array', NULL);
		$this->load->view("master/cost", $data);
	}
	public function upload($param = NULL)
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	 = "Deal Harga Pembelian";
		$data['title_form']  = "Upload Deal Harga Pembelian";
		$this->load->view("master/upload", $data);
	}
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL)
	{
		switch ($param) {
			case 'port':
				$port	= (isset($_POST['port']) ? $this->generate->kirana_decrypt($_POST['port']) : NULL);
				$this->get_port(NULL, $port, NULL, NULL);
				break;
			case 'pol':
				$id_spot_setting_pol = (isset($_POST['id_spot_setting_pol']) ? $this->generate->kirana_decrypt($_POST['id_spot_setting_pol']) : NULL);
				$port				 = (isset($_POST['port']) ? $this->generate->kirana_decrypt($_POST['port']) : NULL);
				$name				 = (isset($_POST['name']) ? explode("-", $_POST['name']) : NULL);
				$name				 = substr($name[0], 0, -1);
				$name				 = ($name != '') ? $name : NULL;
				$this->get_pol(NULL, $id_spot_setting_pol, NULL, NULL, $port, NULL, $name);
				break;
			case 'history':
				$werks = (isset($_POST['werks']) ? $this->generate->kirana_decrypt($_POST['werks']) : NULL);
				$this->get_history(NULL, $werks);
				break;
			case 'cost':
				$werks = (isset($_POST['werks']) ? $this->generate->kirana_decrypt($_POST['werks']) : NULL);
				$this->get_cost(NULL, $werks);
				break;
			case 'buyer':
				$NMBYR = (isset($_POST['NMBYR']) ? $_POST['NMBYR'] : NULL);
				$this->get_buyer(NULL, $NMBYR);
				break;
			case 'plant':
				$plant = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
				$this->get_plant(NULL, $plant);
				break;
			case 'list_upload_deal_beli':
				$plant 		= (isset($_POST['plant']) ? $_POST['plant'] : NULL);
				$tanggal 	= (isset($_POST['tanggal']) ? $_POST['tanggal'] : NULL);
				// $this->get_list_upload_deal_beli(NULL, $plant);

				$listdt = $this->dmasterpol->get_list_upload_deal_beli_bom(array(
					'plant'     => $plant,
					'tanggal'   => $tanggal,
				));

				// $this->data['list'] = $listdt;
				header('Content-Type: application/json');
				$return = $listdt;
				echo $return;
				break;
				// case 'deal':
				// 	$id = (isset($_POST['id']) ? $this->generate->kirana_decrypt($_POST['id']) : NULL);
				// 	echo $_POST['id'];
				// 	$this->get_deal_harga_beli(NULL, $id);
				// 	break;
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
				case 'pol':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_spot_setting_pol", array(
						array(
							'kolom' => 'id_spot_setting_pol',
							'value' => $this->generate->kirana_decrypt($_POST['id_spot_setting_pol'])
						)
					));
					$this->upload_pol_sap(
						array(
							"msg" => $return['msg'],
							"sts" => $return['sts'],
						)
					);
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
			case 'pol':
				$this->save_pol($param);
				break;
			case 'cost':
				$this->save_cost($param);
				break;
			case 'upload':
				$this->save_upload_deal($param);
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
	private function get_port($array = NULL, $port = NULL, $active = NULL, $deleted = NULL)
	{
		$port 		= $this->dmasterpol->get_data_port("open", $port, $active, $deleted);
		$port 		= $this->general->generate_encrypt_json($port, array("port"));
		if ($array) {
			return $port;
		} else {
			echo json_encode($port);
		}
	}
	private function get_buyer($array = NULL, $NMBYR = NULL)
	{
		$buyer 		= $this->dmasterpol->get_data_buyer("open", $NMBYR);
		// $buyer 		= $this->general->generate_encrypt_json($buyer, array("NMBYR"));
		if ($array) {
			return $buyer;
		} else {
			echo json_encode($buyer);
		}
	}
	private function get_plant($array = NULL, $plant = NULL)
	{
		$plant 		= $this->dmasterpol->get_data_plant("open", $plant);
		// $plant 		= $this->general->generate_encrypt_json($plant, array("WERKS"));
		if ($array) {
			return $plant;
		} else {
			echo json_encode($plant);
		}
	}
	private function get_pol($array = NULL, $id_spot_setting_pol = NULL, $active = NULL, $deleted = NULL, $port = NULL, $werks = NULL, $name = NULL)
	{
		$pol 		= $this->dmasterpol->get_data_pol("open", $id_spot_setting_pol, $active, $deleted, $port, $werks, $name);
		$pol 		= $this->general->generate_encrypt_json($pol, array("id_spot_setting_pol", "port"));
		if ($array) {
			return $pol;
		} else {
			echo json_encode($pol);
		}
	}
	// private function get_deal_harga_beli($array = NULL, $id = NULL) {
	// 	$deal 		= $this->dmasterpol->get_data_deal_beli("open", $id);
	// 	echo json_encode($deal);

	// }

	private function get_cost($array = NULL, $werks = NULL)
	{
		$cost 		= $this->dmasterpol->get_data_cost("open", $werks);
		$cost 		= $this->general->generate_encrypt_json($cost, array("WERKS"));
		if ($array) {
			return $cost;
		} else {
			echo json_encode($cost);
		}
	}
	private function get_history($array = NULL, $werks = NULL)
	{
		$history 		= $this->dmasterpol->get_data_cost_log("open", $werks);
		// $history 		= $this->general->generate_encrypt_json($history, array("werks"));
		if ($array) {
			return $history;
		} else {
			echo json_encode($history);
		}
	}

	private function save_pol($param)
	{
		$datetime 				= date("Y-m-d H:i:s");
		$id_spot_setting_pol 	= (isset($_POST['id_spot_setting_pol']) ? $this->generate->kirana_decrypt($_POST['id_spot_setting_pol']) : NULL);
		$plant				 	= (isset($_POST['plant']) ? implode(",", $_POST['plant']) : NULL);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$pol = $this->dmasterpol->get_data_pol(NULL, $id_spot_setting_pol);
		if (count($pol) != 0) {
			$data_row = array(
				"port"  		=> $this->generate->kirana_decrypt($_POST['port']),
				"werks"			=> $plant,
				"tppco"			=> $plant,
				"no_urut"		=> $_POST['no_urut'],
				"selisih"		=> $_POST['selisih'],
				"login_edit" 	=> base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit" 	=> $datetime
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_spot_setting_pol", $data_row, array(
				array(
					"kolom" => "id_spot_setting_pol",
					"value" => $id_spot_setting_pol
				)
			));
		} else {
			$data_row = array(
				"port"  		=> $this->generate->kirana_decrypt($_POST['port']),
				"werks"			=> $plant,
				"tppco"			=> $plant,
				"no_urut"		=> $_POST['no_urut'],
				"selisih"		=> $_POST['selisih'],
				"login_buat" 	=> base64_decode($this->session->userdata("-id_user-")),
				"tanggal_buat"	=> $datetime,
				"login_edit" 	=> base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit" 	=> $datetime
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_spot_setting_pol", $data_row);
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
			$this->general->closeDb();

			$this->upload_pol_sap(
				array(
					"msg" => $msg,
					"sts" => $sts
				)
			);
		}
	}

	private function save_cost($param)
	{
		$datetime	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$data_cost = $this->get_cost('array', NULL);
		foreach ($data_cost as $dt) {
			$werks	= $_POST['werks_' . $dt->WERKS];
			$tppco	= $_POST['tppco_' . $dt->WERKS];
			$cost	= str_replace(',', '', $_POST['cost_' . $dt->WERKS]);
			$note	= $_POST['note_' . $dt->WERKS];

			$ck_cost = $this->dmasterpol->get_data_ck_cost('array', $werks);
			if (count($ck_cost) != 0) {
				$data["werks"]		 = $werks;
				$data["tppco"]		 = $tppco;
				$data["cost"]		 = $cost;
				$data["note"]		 = $note;
				$data["login_edit"]	 = base64_decode($this->session->userdata("-id_user-"));
				$data["tanggal_edit"] = $datetime;
				$data_main = $this->dgeneral->basic_column("update", $data);
				$this->dgeneral->update("tbl_spot_prod_cost", $data_main, array(
					array(
						"kolom" => "werks",
						"value" => $werks
					)
				));
				//buat log
				if (($cost != $ck_cost[0]->cost) or ($note != $ck_cost[0]->note)) {
					$data_log = $this->dgeneral->basic_column("insert", $data);
					$this->dgeneral->insert("tbl_spot_prod_cost_log", $data_log);
				}
			} else {
				$data["werks"]		 = $werks;
				$data["tppco"]		 = $tppco;
				$data["cost"]		 = $cost;
				$data["note"]		 = $note;
				$data["login_edit"]	 = base64_decode($this->session->userdata("-id_user-"));
				$data["tanggal_edit"] = $datetime;
				$data_main = $this->dgeneral->basic_column("insert", $data);
				$this->dgeneral->insert("tbl_spot_prod_cost", $data_main);
				//buat log
				if (!empty($cost)) {
					$data_log = $this->dgeneral->basic_column("insert", $data);
					$this->dgeneral->insert("tbl_spot_prod_cost_log", $data_log);
				}
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
	/*====================================================================*/
	private function save_upload_deal($param)
	{
		$datetime	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		// $data_cost = $this->get_cost('array', NULL);
		// foreach($data_cost as $dt){
		// 	$werks	= $_POST['werks_'.$dt->WERKS];
		// 	$tppco	= $_POST['tppco_'.$dt->WERKS];
		// 	$cost	= str_replace(',','',$_POST['cost_'.$dt->WERKS]);
		// 	$note	= $_POST['note_'.$dt->WERKS];

		// 	$ck_cost = $this->dmasterpol->get_data_ck_cost('array', $werks);
		// 	if (count($ck_cost) != 0){	
		// 		$data["werks"]		 = $werks;
		// 		$data["tppco"]		 = $tppco;
		// 		$data["cost"]		 = $cost;
		// 		$data["note"]		 = $note;
		// 		$data["login_edit"]	 = base64_decode($this->session->userdata("-id_user-"));
		// 		$data["tanggal_edit"]= $datetime;
		// 		$data_main = $this->dgeneral->basic_column("update", $data);
		// 		$this->dgeneral->update("tbl_spot_prod_cost", $data_main, array(
		// 			array(
		// 				"kolom" => "werks",
		// 				"value" => $werks
		// 			)
		// 		));				
		// 		//buat log
		// 		if(($cost!=$ck_cost[0]->cost)or($note!=$ck_cost[0]->note)){
		// 			$data_log = $this->dgeneral->basic_column("insert", $data);
		// 			$this->dgeneral->insert("tbl_spot_prod_cost_log", $data_log);
		// 		}
		// 	}else{
		// 		$data["werks"]		 = $werks;
		// 		$data["tppco"]		 = $tppco;
		// 		$data["cost"]		 = $cost;
		// 		$data["note"]		 = $note;
		// 		$data["login_edit"]	 = base64_decode($this->session->userdata("-id_user-"));
		// 		$data["tanggal_edit"]= $datetime;
		// 		$data_main = $this->dgeneral->basic_column("insert", $data);
		// 		$this->dgeneral->insert("tbl_spot_prod_cost", $data_main);
		// 		//buat log
		// 		if(!empty($cost)){
		// 			$data_log = $this->dgeneral->basic_column("insert", $data);
		// 			$this->dgeneral->insert("tbl_spot_prod_cost_log", $data_log);
		// 		}
		// 	}
		// }


		if (!empty($_FILES['file_excel']['name']) && !empty($_FILES['file_excel']['name']) != "") {
			$target_dir = "./assets/temp";
			if (!file_exists($target_dir)) {
				mkdir($target_dir, 0755, true);
			}
			$config['upload_path']          = $target_dir;
			$config['allowed_types']        = 'xls|xlsx';

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('file_excel')) {
				$msg 	= $this->upload->display_errors();
				$sts 	= "NotOK";
			} else {
				$data           = array('upload_data' => $this->upload->data());
				$objPHPExcel    = PHPExcel_IOFactory::load($data['upload_data']['full_path']);
				$title_desc     = $objPHPExcel->getProperties()->getTitle();
				$objPHPExcel->setActiveSheetIndex(0);
				$data_excel     = $objPHPExcel->getActiveSheet();
				$highestRow     = $data_excel->getHighestRow();
				$datetime       = date("Y-m-d H:i:s");

				$user 			= base64_decode($this->session->userdata("-id_user-"));
				$data_row		= array();
				for ($brs = 3; $brs <= $highestRow; $brs++) {

					$tanggal	= $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
					$tanggal 	= $this->datesql($tanggal);
					$pabrik		= $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
					$qty		= $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
					$harga		= $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
					$idbeli		= $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
					$delet		= $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();

					if ($tanggal == "") {
						break;
					}
					if ($idbeli != "") {
						$idbeli = $this->generate->kirana_decrypt($idbeli);
					}
					// $nik = intval($nik);              
					$data_row	= array(
						'plant_deal'	=> $pabrik,
						'tanggal_deal'	=> $tanggal,
						'qty_deal'		=> $qty,
						'harga_deal'	=> $harga,
						'login_buat' 	=> $user,
						'tanggal_buat'	=> $datetime,
						'login_edit' 	=> $user,
						'tanggal_edit' 	=> $datetime,
						'na' 			=> 'n',
						'del' 			=> 'n',
					);

					if ($idbeli != "" && $delet == "") {
						$data_row_edit1	= array(

							'qty_deal'		=> $qty,
							'harga_deal'	=> $harga,
							'login_edit' 	=> $user,
							'tanggal_edit' 	=> $datetime,

						);
						$this->dgeneral->update('tbl_spot_setting_deal_beli', $data_row_edit1, array(
							array(
								'kolom' => 'id_deal_beli',
								'value' => $idbeli
							),
							array(
								'kolom' => 'plant_deal',
								'value' => $pabrik
							),
							array(
								'kolom' => 'tanggal_deal',
								'value' => $tanggal
							)
						));
					} else if ($idbeli != "" && ($delet == "y" || $delet == "Y")) {
						$data_row_edit2	= array(
							'del' 			=> 'y',
						);
						$this->dgeneral->update('tbl_spot_setting_deal_beli', $data_row_edit2, array(
							array(
								'kolom' => 'id_deal_beli',
								'value' => $idbeli
							),
							array(
								'kolom' => 'plant_deal',
								'value' => $pabrik
							),
							array(
								'kolom' => 'tanggal_deal',
								'value' => $tanggal
							)
						));
					} else if ($idbeli != "" && ($delet == "n" || $delet == "N")) {
						$data_row_edit3	= array(
							'del' 			=> 'n',
						);
						$this->dgeneral->update('tbl_spot_setting_deal_beli', $data_row_edit3, array(
							array(
								'kolom' => 'id_deal_beli',
								'value' => $idbeli
							),
							array(
								'kolom' => 'plant_deal',
								'value' => $pabrik
							),
							array(
								'kolom' => 'tanggal_deal',
								'value' => $tanggal
							)
						));
					} else if ($idbeli == "" && $delet == "") {
						$this->dgeneral->insert("tbl_spot_setting_deal_beli", $data_row);
					}

					// var_dump($data_row);
					// $ck_data 	= $this->dmasterpol->get_data_cek_deal_beli(NULL, $pabrik,$tanggal);
					// if ($ck_data) {
					// 	// echo "update";
					// 	unset($data_row['login_buat']);
					// 	unset($data_row['tanggal_buat']);
					// 	$this->dgeneral->update('tbl_spot_setting_deal_beli', $data_row, array(
					// 		array(
					// 			'kolom' => 'plant_deal',
					// 			'value' => $pabrik
					// 		),
					// 		array(
					// 			'kolom' => 'tanggal_deal',
					// 			'value' => $tanggal
					// 		)
					// 	));
					// } else {
					// echo "insert";
					// $this->dgeneral->insert("tbl_spot_setting_deal_beli", $data_row);
					// }
				}
				unlink($data['upload_data']['full_path']);
				// echo json_decode($this->db->trans_status());
				// exit();
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}
			}
			// exit();
		} else {
			$msg 	= "Silahkan pilih file yang ingin diunggah";
			$sts 	= "NotOK";
		}


		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	/*====================================================================*/
	public function datesql($dates)
	{
		if (strpos($dates, "-")) {
			$result = $dates;
		} elseif (strpos($dates, ".")) {
			$datasplit = explode(".", $dates);
			$result = $datasplit[2] . "-" . $datasplit[1] . "-" . $datasplit[0];
		} elseif (strpos($dates, "/")) {
			$datasplit = explode("/", $dates);
			$result = $datasplit[2] . "-" . $datasplit[0] . "-" . $datasplit[1];
		} else {
			$result = "";
		}
		return $result;
	}

	//=====add by ASY=====//
	private function upload_pol_sap($param = NULL)
	{
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$data['sap'] = $this->general->connectSAP("ERP_310");

		$msg = @$param['msg'];
		$sts = @$param['sts'];

		$datetime = date("Y-m-d H:i:s");
		$type = array();
		$message = array();
		$data_row_log = array();
		if ($data['sap']->getStatus() == SAPRFC_OK) {
			$data_pol = $this->dmasterpol->get_data_pol_sap(
				array(
					"connect" => FALSE
				)
			);
			$result = $data['sap']->callFunction(
				"Z_RFC_SELISIHUSC_PORT",
				array(
					array("TABLE", "T_DATA", $data_pol),
					array("TABLE", "T_RETURN", array())
				)
			);

			if (empty($result["T_RETURN"])) {
				$status = 'Berhasil';
				$data_row_log = array(
					'app'           => 'DATA RFC PORT OF LOAD (SPOT) TO SAP',
					'rfc_name'      => 'Z_RFC_SELISIHUSC_PORT',
					'log_code'      => 'S',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Data berhasil di upload ke SAP',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {
				foreach ($result["T_RETURN"] as $return) {
					$type[]    = $return['TYPE'];
					$message[] = $return['MESSAGE'];
				}

				$status = in_array("E", $type) === true ? 'Gagal' : 'Berhasil';
				$data_row_log = array(
					'app'           => 'DATA RFC PORT OF LOAD (SPOT) TO SAP',
					'rfc_name'      => 'Z_RFC_SELISIHUSC_PORT',
					'log_code'      => implode(" , ", array_unique($type)),
					'log_status'    => $status,
					'log_desc'      => 'UPLOAD PORT OF LOAD (SPOT) TO SAP [T_RETURN]: '.implode(" , ", $message),
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORT OF LOAD (SPOT) TO SAP',
				'rfc_name'      => 'Z_RFC_SELISIHUSC_PORT',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg .= "Namun, gagal upload ke SAP.";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			if (in_array('E', $type) === true) {
				$msg = $data_row_log['log_desc'];
				$sts = "NotOK";
			}
		}
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		exit();
	}
}
