<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

// class Api extends REST_Controller{	
Class Api extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		
	    $this->load->model('dmasterpol');
	    $this->load->model('dtransaksipol');
	}

	public function index(){
		show_404();
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL, $param2 = NULL) {
		switch ($param) {
			//master
			case 'port':
				$port	= (isset($_POST['port']) ? $this->generate->kirana_decrypt($_POST['port']) : NULL);
				$this->get_port(NULL, $port, NULL, NULL);
				break;
			case 'pol':
				$id_spot_setting_pol = (isset($_POST['id_spot_setting_pol']) ? $this->generate->kirana_decrypt($_POST['id_spot_setting_pol']) : NULL);
				$port				 = (isset($_POST['port']) ? $this->generate->kirana_decrypt($_POST['port']) : NULL);
				$name				 = (isset($_POST['name']) ? explode("-", $_POST['name']) : NULL);
				$name				 = substr($name[0], 0, -1);
				$name				 = ($name!='')?$name:NULL;
				$this->get_pol(NULL, $id_spot_setting_pol, NULL, NULL, $port, NULL, $name);
				break;
			case 'history':
				$werks = (isset($_POST['werks']) ? $this->generate->kirana_decrypt($_POST['werks']) : NULL);
				$this->get_history(NULL, $werks);
				break;
			case 'buyer':
				$NMBYR = (isset($_POST['NMBYR']) ? $_POST['NMBYR'] : NULL);
				$this->get_buyer(NULL, $NMBYR);
				break;
			case 'plant':
				$plant = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
				$this->get_plant(NULL, $plant);
				break;
				
			
			//transaksi	
			case 'last':
				$this->get_last(NULL);
				break;
			case 'currency':
				$this->get_currency(NULL);
				break;
			case 'cost':
				$port_1 = (isset($_POST['port_1']) ? $_POST['port_1']: NULL);
				$tahun 	= (isset($_POST['sales']) ? substr($_POST['sales'], -4): NULL);
				$type 	= (isset($_POST['type']) ? $_POST['type']: NULL);
				$this->get_cost(NULL, NULL, $tahun, $type, $port_1);
				break;
			case 'no_form':
				$this->get_no_form(NULL);
				break;
			case 'sales':
				$data = json_decode(file_get_contents('php://input'), true);
				if($data){
					$_POST = $data;
				}
				$no_form    = (isset($_POST['no_form']) ? $_POST['no_form'] : NULL);
				if(isset($_POST['tahun'])){
					$tahun	= array();
					foreach ($_POST['tahun'] as $dt) {
						array_push($tahun, $dt);
					}
				}else{
					$tahun  = NULL;
				}
				if(isset($_POST['buyer'])){
					$buyer	= array();
					foreach ($_POST['buyer'] as $dt) {
						array_push($buyer, $dt);
					}
				}else{
					$buyer  = NULL;
				}
				if(isset($_POST['status'])){
					$status	= array();
					foreach ($_POST['status'] as $dt) {
						array_push($status, $dt);
					}
				}else{
					$status  = NULL;
				}
				if($param2=='bom'){
					header('Content-Type: application/json');
					$return = $this->dtransaksipol->get_data_sales_bom('open', $no_form, $tahun, $buyer, $status);
					echo $return;
					break;
				}else{
					$this->get_sales(NULL, $no_form, $tahun, $buyer, $status);
					break;
				}
			case 'detail':
				$data = json_decode(file_get_contents('php://input'), true);
				if($data){
					$_POST = $data;
				}
				$no_form    = (isset($_POST['no_form']) ? $this->generate->kirana_decrypt(@$this->input->post('no_form', true)) : NULL);
				if(isset($_POST['plant'])){
					$plant	= array();
					foreach (@$this->input->post('plant', true) as $dt) {
						array_push($plant, $dt);
					}
				}else{
					$plant  = NULL;
				}
				if(isset($_POST['tahun'])){
					$tahun	= array();
					foreach (@$this->input->post('tahun', true) as $dt) {
						array_push($tahun, $dt);
					}
				}else{
					$tahun  = NULL;
				}
				if(isset($_POST['buyer'])){
					$buyer	= array();
					foreach (@$this->input->post('buyer', true) as $dt) {
						array_push($buyer, $dt);
					}
				}else{
					$buyer  = NULL;
				}
				if(isset($_POST['status'])){
					$status	= array();
					foreach (@$this->input->post('status', true) as $dt) {
						array_push($status, $dt);
					}
				}else{
					$status  = NULL;
				}
				if($param2=='bom'){
					header('Content-Type: application/json');
					$return = $this->dtransaksipol->get_data_detail_bom('open', $no_form, $plant, $tahun, $buyer, $status);
					echo $return;
					break;
				}else{
					$this->get_detail(NULL, $no_form);
					break;
				}
			case 'resend':
				$no_form    = (isset($_POST['no_form']) ? $this->generate->kirana_decrypt($_POST['no_form']) : NULL);
				$buyer    = (isset($_POST['buyer']) ? $_POST['buyer'] : NULL);
				$this->get_resend(NULL, $no_form, $buyer);
				break;
				
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function save($param = NULL) {
		switch ($param) {
			//master
			case 'pol':
				$this->save_pol($param);
				break;
			//transaksi	
			case 'cost':
				$this->save_cost($param);
				break;
			case 'simulasi':
				$this->save_simulasi($param);
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
	//master
	private function get_port($array = NULL, $port = NULL, $active = NULL, $deleted = NULL) {
		$port 		= $this->dmasterpol->get_data_port("open", $port, $active, $deleted);
		$port 		= $this->general->generate_encrypt_json($port, array("port"));
		if ($array) {
			return $port;
		} else {
			echo json_encode($port);
		}
	}
	private function get_pol($array = NULL, $id_spot_setting_pol = NULL, $active = NULL, $deleted = NULL, $port = NULL, $werks = NULL, $name = NULL) {
		$pol 		= $this->dmasterpol->get_data_pol("open", $id_spot_setting_pol, $active, $deleted, $port, $werks, $name);
		$pol 		= $this->general->generate_encrypt_json($pol, array("id_spot_setting_pol","port"));
		if ($array) {
			return $pol;
		} else {
			echo json_encode($pol);
		}
	}
	private function get_history($array = NULL, $werks = NULL) {
		$history 		= $this->dmasterpol->get_data_cost_log("open", $werks);
		// $history 		= $this->general->generate_encrypt_json($history, array("werks"));
		if ($array) {
			return $history;
		} else {
			echo json_encode($history);
		}
	}
	
	private function get_buyer($array = NULL, $NMBYR = NULL) {
		$buyer 		= $this->dmasterpol->get_data_buyer("open", $NMBYR);
		// $buyer 		= $this->general->generate_encrypt_json($buyer, array("NMBYR"));
		if ($array) {
			return $buyer;
		} else {
			echo json_encode($buyer);
		}
	}
	private function get_plant($array = NULL, $plant = NULL) {
		$plant 		= $this->dmasterpol->get_data_plant("open", $plant);
		// $plant 		= $this->general->generate_encrypt_json($plant, array("WERKS"));
		if ($array) {
			return $plant;
		} else {
			echo json_encode($plant);
		}
	}
	
	//transaksi
	private function get_last($array = NULL) {
		$last 		= $this->dtransaksipol->get_data_last("open");
		// $last 		= $this->general->generate_encrypt_json($last, array("rate"));
		if ($array) {
			return $last;
		} else {
			echo json_encode($last);
		}
	}
	
	private function get_currency($array = NULL) {
		$currency 		= $this->dtransaksipol->get_data_currency("open");
		// $currency 		= $this->general->generate_encrypt_json($currency, array("rate"));
		if ($array) {
			return $currency;
		} else {
			echo json_encode($currency);
		}
	}
	private function get_detail($array = NULL, $no_form = NULL) {
		$detail 		= $this->dtransaksipol->get_data_detail("open", $no_form);
		$detail 		= $this->general->generate_encrypt_json($detail, array("no_form"));
		if ($array) {
			return $detail;
		} else {
			echo json_encode($detail);
		}
	}
	private function get_resend($array = NULL, $no_form = NULL, $buyer = 'xx') {
		
		//sent email
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
			$data_email = $this->get_email('array',$buyer);
			foreach($data_email as $dt){
				//jika HO
				if($dt->PAVIP=='5'){
					$subject = 'SPOT '.date('d-M-Y');
					$this->load->library('email', $config);
					$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
					$this->email->subject($subject);
					
					$this->email->to($dt->email_to);
					// $this->email->to('lukman.hakim@kiranamegatara.com');
					$detail		= $this->dtransaksipol->get_data_detail("open", $no_form);
					$message  =	'<body style="font-size:12px;font-family:Calibri,Helvetica, Arial, Sans-Serif;">';
					$message .=	'<table>';
					$message .=	'	<tr><td><b>Dear Mr/Ms '.$dt->nama_penerima.'</b></td></tr>';
					$message .=	'	<tr><td>We confirm the trade today asf:</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'</table>';
					
					foreach($detail as $det) {
						$message .=	'<table>';
						$message .=	'	<tr><td colspan="3"><b style="font-size:18px;">Sales Confirmation</b></td></tr>';
						$message .=	'	<tr><td>DATE</td><td>:</td><td>'.date('d-M-Y').'</td></tr>';
						$message .=	'	<tr><td>FORM NO</td><td>:</td><td>'.$det->no_form.'</td></tr>';
						$message .=	'	<tr><td>BUYER</td><td>:</td><td>'.$det->buyer.'</td></tr>';
						$message .=	'	<tr><td>FACTORY</td><td>:</td><td>'.$det->werks.'</td></tr>';
						$message .=	'	<tr><td>FACTORY CODE</td><td>:</td><td>'.$det->tppco.'</td></tr>';
						$message .=	'	<tr><td>PRODUCT GRADE</td><td>:</td><td>'.$det->prod_grade.'</td></tr>';
						$message .=	'	<tr><td>QUANTITY</td><td>:</td><td>'.$det->qty.' MT</td></tr>';
						$message .=	'	<tr><td>SHIPMENT PERIOD</td><td>:</td><td>'.$det->shipment_periode.'</td></tr>';
						$message .=	'	<tr><td>SHIPMENT TERM</td><td>:</td><td>FOB '.$det->shipment_term.'</td></tr>';
						$message .=	'	<tr><td>PRICE</td><td>:</td><td>'.$det->price.' USC/KG</td></tr>';
						$message .=	'	<tr><td>SICOM</td><td>:</td><td>'.$det->sicom.'</td></tr>';
						$message .=	'	<tr><td>MARGIN</td><td>:</td><td>'.$det->margin.'</td></tr>';
						$message .=	'	<tr><td>NOTES</td><td>:</td><td>'.$det->note.'</td></tr>';
						$message .=	'	<tr><td colspan="3">&nbsp;</td></tr>';
						$message .=	'</table>';
					}	
					$message .=	'<table>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'	<tr><td>Thank you.</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'	<tr><td>Best regards,</td></tr>';
					$message .=	'	<tr><td>Sales & Marketing Division.</td></tr>';
					$message .=	'</table>';
					$message .= '</body>';
					
					$this->email->message($message);
					$this->email->send();
				}
				//jika buyer
				if($dt->PAVIP=='3'){
					$subject = 'SPOT '.date('d-M-Y');
					$this->load->library('email', $config);
					$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
					$this->email->subject($subject);
					$this->email->to($dt->email_to);
					$this->email->cc($dt->email_cc);
					$detail		= $this->dtransaksipol->get_data_detail("open", $no_form);
					$message  =	'<body style="font-size:12px;font-family:Calibri,Helvetica, Arial, Sans-Serif;">';
					$message .=	'<table>';
					$message .=	'	<tr><td><b>Dear Mr/Ms '.$dt->nama_penerima.'</b></td></tr>';
					$message .=	'	<tr><td>Thank you for the business.</td></tr>';
					$message .=	'	<tr><td>We confirm the trade today asf:</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'</table>';
					foreach($detail as $det) {
						$message .=	'<table>';
						$message .=	'	<tr><td colspan="3"><b style="font-size:18px;">Sales Confirmation</b></td></tr>';
						$message .=	'	<tr><td>DATE</td><td>:</td><td>'.date('d-M-Y').'</td></tr>';
						$message .=	'	<tr><td>BUYER</td><td>:</td><td>'.$det->buyer.'</td></tr>';
						$message .=	'	<tr><td>FACTORY</td><td>:</td><td>'.$det->werks.'</td></tr>';
						$message .=	'	<tr><td>FACTORY CODE</td><td>:</td><td>'.$det->tppco.'</td></tr>';
						$message .=	'	<tr><td>PRODUCT GRADE</td><td>:</td><td>'.$det->prod_grade.'</td></tr>';
						$message .=	'	<tr><td>QUANTITY</td><td>:</td><td>'.$det->qty.' MT</td></tr>';
						$message .=	'	<tr><td>SHIPMENT PERIOD</td><td>:</td><td>'.$det->shipment_periode.'</td></tr>';
						$message .=	'	<tr><td>SHIPMENT TERM</td><td>:</td><td>FOB '.$det->shipment_term.'</td></tr>';
						$message .=	'	<tr><td>PRICE</td><td>:</td><td>'.$det->price.' USC/KG</td></tr>';
						$message .=	'	<tr><td>NOTES</td><td>:</td><td>'.$det->note.'</td></tr>';
						$message .=	'	<tr><td colspan="3">&nbsp;</td></tr>';
						$message .=	'</table>';
					}	
					$message .=	'<table>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'	<tr><td>Thank you.</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'	<tr><td>Best regards,</td></tr>';
					$message .=	'	<tr><td>Sales & Marketing Division.</td></tr>';
					$message .=	'</table>';
					$message .= '</body>';
					$this->email->message($message);
					$this->email->send();
				}
			}	
		
			//update data sent
			$datetime  = date("Y-m-d H:i:s");
			$data_row  = array(
							  'status'     	=> 1,
							  'login_edit'  => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'=> $datetime
						 );
			$this->dgeneral->update('tbl_spot_sales_conf_head', $data_row, array( 
																array(
																	'kolom'=>'no_form',
																	'value'=>$no_form
																)
															));
		} catch (Exception $e) {
			$msg = $e->getMessage();
			$sts = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
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
	private function get_no_form($array = NULL) {
		$no_form 		= $this->dtransaksipol->get_data_no_form("open");
		if ($array) {
			return $no_form;
		} else {
			echo json_encode($no_form);
		}
	}
	private function get_cost($array = NULL, $werks = NULL, $tahun = NULL, $type = NULL, $port_1 = NULL) {
		$cost 		= $this->dmasterpol->get_data_cost("open", $werks, $tahun, $type, $port_1);
		$cost 		= $this->general->generate_encrypt_json($cost, array("WERKS"));
		if ($array) {
			return $cost;
		} else {
			echo json_encode($cost);
		}
	}
	private function get_email($array = NULL, $buyer = NULL) {
		$email 		= $this->dtransaksipol->get_data_email("open", $buyer);
		if ($array) {
			return $email;
		} else {
			echo json_encode($email);
		}
	}
	
	private function save_pol($param) {
		$datetime 				= date("Y-m-d H:i:s");
		$id_spot_setting_pol 	= (isset($_POST['id_spot_setting_pol']) ? $this->generate->kirana_decrypt($_POST['id_spot_setting_pol']) : NULL);
		$plant				 	= (isset($_POST['plant']) ? implode(",", $_POST['plant']) : NULL);
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		$pol = $this->dmasterpol->get_data_pol(NULL, $id_spot_setting_pol);
		if (count($pol) != 0){
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
		}else{
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
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	private function save_cost($param) {
		$datetime	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		$data_cost = $this->get_cost('array', NULL);
		foreach($data_cost as $dt){
			$data["factory"]		 	= $_POST['factory_'.$dt->plant];
			$data["plant"]			 	= $_POST['plant_'.$dt->plant];
			$data["mtd_price"]		 	= $_POST['mtd_price_'.$dt->plant];
			$data["selling_price_usc"]	= $_POST['selling_price_usc_'.$dt->plant];
			$data["selling_price"]		= $_POST['selling_price_'.$dt->plant];
			$data["prod_cost"]		 	= $_POST['prod_cost_'.$dt->plant];
			$data["total_cost"]		 	= $_POST['total_cost_'.$dt->plant];
			$data["carry_cost"]		 	= $_POST['carry_cost_'.$dt->plant];
			$data["margin"]		 		= $_POST['margin_'.$dt->plant];
			$data["ocp"]		 		= $_POST['ocp_'.$dt->plant];
			$data["breakeven_price"]	= $_POST['breakeven_price_'.$dt->plant];
			$data["cur_rate"]		 	= $_POST['cur_rate_'.$dt->plant];
			$data["pol"]		 		= $_POST['pol_'.$dt->plant];
			$data["pol_value"]	 		= $_POST['pol_value_'.$dt->plant];
			$data["libor_rate"]	 		= $_POST['libor_rate_'.$dt->plant];
			$data["interest_rate"]		= $_POST['interest_rate_'.$dt->plant];
			$data["days"]		 		= $_POST['days_'.$dt->plant];
			$data["prod_cost_type"]		= $_POST['prod_cost_type_'.$dt->plant];
			$data["date"]		 		= $datetime;
			$data["login_edit"]	 		= base64_decode($this->session->userdata("-id_user-"));
			$data["tanggal_edit"]		= $datetime;
			$data = $this->dgeneral->basic_column("insert", $data);
			$this->dgeneral->insert("tbl_spot_simulate", $data);
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
	
	private function save_simulasi($param) {
		$datetime	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		//save simulasi
		$arr_all_plant    = explode(",", $_POST['all_plant']);
		foreach($arr_all_plant as $plant) {
			$data["factory"]		 	= $_POST['factory_'.$plant];
			$data["plant"]			 	= $_POST['plant_'.$plant];
			$data["mtd_price"]		 	= str_replace(',','',$_POST['mtd_price_'.$plant]);
			$data["selling_price_usc"]	= str_replace(',','',$_POST['selling_price_usc_'.$plant]);
			$data["selling_price"]		= str_replace(',','',$_POST['selling_price_'.$plant]);
			$data["prod_cost"]		 	= str_replace(',','',$_POST['prod_cost_'.$plant]);
			$data["total_cost"]		 	= str_replace(',','',$_POST['total_cost_'.$plant]);
			$data["carry_cost"]		 	= str_replace(',','',$_POST['carry_cost_'.$plant]);
			$data["margin"]		 		= str_replace(',','',$_POST['margin_'.$plant]);
			$data["ocp"]		 		= str_replace(',','',$_POST['ocp_'.$plant]);
			$data["breakeven_price"]	= str_replace(',','',$_POST['breakeven_price_'.$plant]);
			$data["cur_rate"]		 	= str_replace(',','',$_POST['cur_rate_'.$plant]);
			$data["pol"]		 		= str_replace(',','',$_POST['pol_'.$plant]);
			$data["pol_value"]	 		= str_replace(',','',$_POST['pol_value_'.$plant]);
			$data["libor_rate"]	 		= str_replace(',','',$_POST['libor_rate_'.$plant]);
			$data["interest_rate"]		= str_replace(',','',$_POST['interest_rate_'.$plant]);
			$data["days"]		 		= str_replace(',','',$_POST['days_'.$plant]);
			$data["prod_cost_type"]		= str_replace(',','',$_POST['prod_cost_type_'.$plant]);
			$data["date"]		 		= $datetime;
			$data["login_buat"]	 		= base64_decode($_POST['-id_user-']);
			$data["tanggal_buat"]		= $datetime;
			$data["login_edit"]	 		= base64_decode($_POST['-id_user-']);
			$data["tanggal_edit"]		= $datetime;
			$data["na"]					= 'n';
			$data["del"]				= 'n';
			// $data = $this->dgeneral->basic_column("insert", $data);
			$this->dgeneral->insert("tbl_spot_simulate", $data);
		}
		
		
		//save header
		$data_header = array(
			"no_form"  		=> $_POST['no_form'],
			"buyer"			=> $_POST['buyer'],
			"status"		=> 0,
			"sicom"			=> str_replace(',','',$_POST['sicom']),
			"login_buat" 	=> base64_decode($_POST['-id_user-']),
			"tanggal_buat"	=> $datetime,
			"login_edit" 	=> base64_decode($_POST['-id_user-']),
			"tanggal_edit" 	=> $datetime,
			"na" 			=> 'n',
			"del" 			=> 'n'
		);
		// $data_header = $this->dgeneral->basic_column("insert", $data_header);
		$this->dgeneral->insert("tbl_spot_sales_conf_head", $data_header);

		//save detail
		$arr_plant_selected    = explode(",", $_POST['plant_selected']);
		foreach($arr_plant_selected as $plant) {
			$data_detail 	= array(
				"no_form"  			=> $_POST['no_form'],
				"tppco"				=> $_POST['tppco_det_'.$plant],
				"werks"				=> $_POST['werks_det_'.$plant],
				"prod_grade"		=> $_POST['prod_grade_det_'.$plant],
				"qty"				=> str_replace(',','',$_POST['qty_det_'.$plant]),
				"shipment_periode"	=> $_POST['shipment_periode_det_'.$plant],
				"shipment_term"		=> $_POST['shipment_term_det_'.$plant],
				"price"				=> str_replace(',','',$_POST['price_det_'.$plant]),
				"margin"			=> str_replace(',','',$_POST['margin_det_'.$plant]),
				"note"				=> $_POST['note_det_'.$plant],
				"login_buat" 		=> base64_decode($_POST['-id_user-']),
				"tanggal_buat"		=> $datetime,
				"login_edit" 		=> base64_decode($_POST['-id_user-']),
				"tanggal_edit" 		=> $datetime,
				"na" 				=> 'n',
				"del" 				=> 'n'
			);
			// $data_detail = $this->dgeneral->basic_column("insert", $data_detail);
			$this->dgeneral->insert("tbl_spot_sales_conf_detail", $data_detail);
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
		
		//sent email
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
			$data_email = $this->get_email('array',$_POST['buyer']);
			foreach($data_email as $dt){
				//jika HO
				if($dt->PAVIP=='5'){
					$subject = 'SPOT '.date('d-M-Y');
					$this->load->library('email', $config);
					$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
					$this->email->subject($subject);
					
					$this->email->to($dt->email_to);
					// $this->email->to('lukman.hakim@kiranamegatara.com');
					$message  =	'<body style="font-size:12px;font-family:Calibri,Helvetica, Arial, Sans-Serif;">';
					$message .=	'<table>';
					$message .=	'	<tr><td><b>Dear Mr/Ms '.$dt->nama_penerima.'</b></td></tr>';
					$message .=	'	<tr><td>We confirm the trade today asf:</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'</table>';
					
					$arr_plant_selected    = explode(",", $_POST['plant_selected']);
					foreach($arr_plant_selected as $plant) {
						$message .=	'<table>';
						$message .=	'	<tr><td colspan="3"><b style="font-size:18px;">SALES CONFIRMATION</b></td></tr>';
						$message .=	'	<tr><td width="150px">DATE</td><td>:</td><td>'.date('d-M-Y').'</td></tr>';
						$message .=	'	<tr><td>FORM NO</td><td>:</td><td>'.$_POST['no_form'].'</td></tr>';
						$message .=	'	<tr><td>BUYER</td><td>:</td><td>'.$_POST['buyer'].'</td></tr>';
						$message .=	'	<tr><td>FACTORY</td><td>:</td><td>'.$_POST['factory_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>FACTORY CODE</td><td>:</td><td>'.$_POST['tppco_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>PRODUCT GRADE</td><td>:</td><td>'.$_POST['prod_grade_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>QUANTITY</td><td>:</td><td>'.$_POST['qty_det_'.$plant].' MT</td></tr>';
						$message .=	'	<tr><td>SHIPMENT PERIOD</td><td>:</td><td>'.$_POST['shipment_periode_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>SHIPMENT TERM</td><td>:</td><td>FOB '.$_POST['shipment_term_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>PRICE</td><td>:</td><td>'.$_POST['price_det_'.$plant].' USC/KG</td></tr>';
						$message .=	'	<tr><td>SICOM</td><td>:</td><td>'.$_POST['sicom'].'</td></tr>';
						$message .=	'	<tr><td>MARGIN</td><td>:</td><td>'.$_POST['margin_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>NOTES</td><td>:</td><td>'.$_POST['note_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td colspan="3">&nbsp;</td></tr>';
						$message .=	'</table>';
					}	
					$message .=	'<table>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'	<tr><td>Thank you.</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'	<tr><td>Best regards,</td></tr>';
					$message .=	'	<tr><td>Sales & Marketing Division.</td></tr>';
					$message .=	'</table>';
					$message .= '</body>';
					
					$this->email->message($message);
					$this->email->send();
				}
				//jika buyer
				if($dt->PAVIP=='3'){
					$subject = 'SPOT '.date('d-M-Y');
					$this->load->library('email', $config);
					$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
					$this->email->subject($subject);
					$this->email->to($dt->email_to);
					$this->email->cc($dt->email_cc);
					$message  =	'<body style="font-size:12px;font-family:Calibri,Helvetica, Arial, Sans-Serif;">';
					$message .=	'<table>';
					$message .=	'	<tr><td><b>Dear Mr/Ms '.$dt->nama_penerima.' Team</b></td></tr>';
					$message .=	'	<tr><td>Thank you for the business.</td></tr>';
					$message .=	'	<tr><td>We confirm the trade today asf:</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'</table>';
					$arr_plant_selected    = explode(",", $_POST['plant_selected']);
					foreach($arr_plant_selected as $plant) {
						$message .=	'<table>';
						$message .=	'	<tr><td colspan="3"><b style="font-size:18px;">SALES CONFIRMATION</b></td></tr>';
						$message .=	'	<tr><td>DATE</td><td>:</td><td>'.date('d-M-Y').'</td></tr>';
						$message .=	'	<tr><td>BUYER</td><td>:</td><td>'.$_POST['buyer'].'</td></tr>';
						$message .=	'	<tr><td>FACTORY</td><td>:</td><td>'.$_POST['factory_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>FACTORY CODE</td><td>:</td><td>'.$_POST['tppco_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>PRODUCT GRADE</td><td>:</td><td>'.$_POST['prod_grade_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>QUANTITY</td><td>:</td><td>'.$_POST['qty_det_'.$plant].' MT</td></tr>';
						$message .=	'	<tr><td>SHIPMENT PERIOD</td><td>:</td><td>'.$_POST['shipment_periode_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>SHIPMENT TERM</td><td>:</td><td>FOB '.$_POST['shipment_term_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td>PRICE</td><td>:</td><td>'.$_POST['price_det_'.$plant].' USC/KG</td></tr>';
						$message .=	'	<tr><td>NOTES</td><td>:</td><td>'.$_POST['note_det_'.$plant].'</td></tr>';
						$message .=	'	<tr><td colspan="3">&nbsp;</td></tr>';
						$message .=	'</table>';
					}	
					$message .=	'<table>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'	<tr><td>Thank you.</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'	<tr><td>Best regards,</td></tr>';
					$message .=	'	<tr><td>Sales & Marketing Division.</td></tr>';
					$message .=	'</table>';
					$message .= '</body>';
					$this->email->message($message);
					$this->email->send();
				}
			}	
			
			//update data sent
			$datetime  = date("Y-m-d H:i:s");
			$data_row  = array(
							  'status'     	=> 1,
							  'login_edit'  => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'=> $datetime
						 );
			$this->dgeneral->update('tbl_spot_sales_conf_head', $data_row, array( 
																array(
																	'kolom'=>'no_form',
																	'value'=>$_POST['no_form']
																)
															));
		} catch (Exception $e) {
			$msg = $e->getMessage();
			$sts = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}			
		
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	/*====================================================================*/
}
