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

Class Transaksi extends MX_Controller{
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
	public function test($param=NULL){
			$data_email = $this->get_email('array','PT. Gajah Tunggal Tbk');
			$list_email_ho = "";
			foreach($data_email as $dt2){
				$list_email_ho .= $dt2->email_to.",";
			}
			echo substr($list_email_ho, 0, -1);

		// exit();
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

			//jika HO
			// $data_email = $this->get_email('array',$dt->buyer);
			$subject = 'SPOT TEST';
			$this->load->library('email', $config);
			$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
			$this->email->subject($subject);
			// $this->email->to($data_email[0]->email_to);
			// $this->email->to('sylviani.oktarista@kiranamegatara.com');
			// $this->email->to('lukman.hakim@kiranamegatara.com','sylviani.oktarista@kiranamegatara.com');
			//lha cr-2289
			// $this->email->to(substr($list_email_ho, 0, -1));
			$this->email->to('sylviani.oktarista@kiranamegatara.com');
			
			$message  =	'<body style="font-size:12px;font-family:Calibri,Helvetica, Arial, Sans-Serif;">';
			$message .=	'<table>';
			$message .=	'	<tr><td>&nbsp;</td></tr>';
			$message .=	'	<tr><td>Thank you.</td></tr>';
			$message .=	'	<tr><td>&nbsp;</td></tr>';
			$message .=	'	<tr><td>Best regards,</td></tr>';
			$message .=	'	<tr><td>Sales & Marketing Division.</td></tr>';
			$message .=	'	<tr><td>PT Kirana Megatara Tbk.</td></tr>';
			$message .=	'</table>';
			$message .= '</body>';
			$this->email->message($message);
			$this->email->send();

	}
	public function simulasi($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	 = "Perhitungan Simulasi Penjualan SPOT";
		$data['title_form']  = "Perhitungan Simulasi Penjualan SPOT";
		// $data['port'] 	 	 = $this->get_port('array');
		// $data['plant']       = $this->dmasterpol->get_master_plant();
		// $data['pol'] 	 	 = $this->get_pol('array', NULL, NULL, NULL);
		$this->load->view("transaksi/simulasi", $data);	
	}
	public function sales($param=NULL){
		if($param!=NULL){
			//====must be initiate in every view function====/
			$data['generate']   = $this->generate; 
			$data['module']     = $this->router->fetch_module();
			$data['user']       = $this->general->get_data_user();
			//===============================================/
			$no_form			 = $this->generate->kirana_decrypt($param);
			$data['title']    	 = "List Sales Confirmation";
			$data['sales'] 	 	 = $this->get_sales('array', $no_form);
			// echo json_encode($data['sales']);
			// exit();
			$this->load->view("transaksi/sales_edit", $data);	
		}else{
			//====must be initiate in every view function====/
			$this->general->check_access();
			$data['generate']   = $this->generate; 
			$data['module']     = $this->router->fetch_module();
			$data['user']       = $this->general->get_data_user();
			//===============================================/
			$data['title']    	 = "List Sales Confirmation";
			$data['title_form']  = "List Sales Confirmation";
			// $data['port'] 	 	 = $this->get_port('array');
			// $data['plant']       = $this->dmasterpol->get_master_plant();
			// $data['pol'] 	 	 = $this->get_pol('array', NULL, NULL, NULL);
			$this->load->view("transaksi/sales", $data);	
		}
	}
	public function detail($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	 = "List Detail Sales SPOT";
		$data['title_form']  = "List Detail Sales SPOT";
		// $data['port'] 	 	 = $this->get_port('array');
		// $data['plant']       = $this->dmasterpol->get_master_plant();
		// $data['pol'] 	 	 = $this->get_pol('array', NULL, NULL, NULL);
		$this->load->view("transaksi/detail", $data);	
	}
	
    public function excel(){
        $this->load->library('PHPExcel');
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');
            $objPHPExcel = new PHPExcel();
            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Kiranaku")
                ->setLastModifiedBy("Kiranaku")
                ->setTitle("List Detail Sales SPOT (" . date('d-m-Y') . ")")
                ->setSubject("List Detail Sales SPOT (" . date('d-m-Y') . ")")
                ->setDescription("List Detail Sales SPOT (" . date('d-m-Y') . ")")
                ->setCategory("List Detail Sales SPOT");

            // Add some data
		
			//header
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Date');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Form No');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Contract No');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Buyer');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Factory');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'QTY (MT)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Contract Month');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Product Grade');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Port');

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Selling Price (USC/KG)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Kurs Price (IDR/USD)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Selling Price (IDR/KG)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'MTD Purch Price (IDR/KG)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Deal Harga Pembelian (IDR/KG)');

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Prod Cost (IDR/KG)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Trucking Cost (IDR/KG)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q1', 'Carry Cost (IDR/KG)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R1', 'Margin (IDR/KG)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S1', 'SICOM');
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T1', 'Amount (IDR)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U1', 'Margin After Packing Disc (IDR/KG)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V1', 'Amount After Packing Disc (IDR)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W1', 'Remark');
            $objPHPExcel->getActiveSheet()->setTitle('data spot');
			
			// //content
			
			// $list_data = $this->get_request('array');
			$list_data = $this->get_detail_excel('array');
			// echo json_encode($list_data);
			// exit();
            $baris = 1;
            foreach ($list_data as $data) {
            	$deal = $data->deal_harga_pembelian;
                $baris++;
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $baris, $data->tanggal); 
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $baris, $data->nomor); 
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $baris, $data->no_contract); 
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $baris, $data->buyer); 
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $baris, $data->tppco); 
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $baris, $data->qty); 
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $baris, $data->shipment_periode); 
				$objPHPExcel->getActiveSheet()->setCellValue('H' . $baris, $data->prod_grade); 
				$objPHPExcel->getActiveSheet()->setCellValue('I' . $baris, $data->pol); 

				$objPHPExcel->getActiveSheet()->setCellValue('J' . $baris, $data->selling_price_usc); 
				$objPHPExcel->getActiveSheet()->setCellValue('K' . $baris, $data->cur_rate); 
				$objPHPExcel->getActiveSheet()->setCellValue('L' . $baris, $data->selling_price); 
				$objPHPExcel->getActiveSheet()->setCellValue('M' . $baris, $data->mtd_price); 
				$objPHPExcel->getActiveSheet()->setCellValue('N' . $baris, $deal);

				$objPHPExcel->getActiveSheet()->setCellValue('O' . $baris, $data->prod_cost); 
				$objPHPExcel->getActiveSheet()->setCellValue('P' . $baris, $data->trucking_cost); 
				$objPHPExcel->getActiveSheet()->setCellValue('Q' . $baris, $data->carry_cost); 
				$objPHPExcel->getActiveSheet()->setCellValue('R' . $baris, $data->margin); 
				$objPHPExcel->getActiveSheet()->setCellValue('S' . $baris, $data->sicom);

				$objPHPExcel->getActiveSheet()->setCellValue('T' . $baris, $data->amount); 
				
				$margin_after_packing = $data->margin-(0.005*$data->cur_rate);
				$amount_after_packing = $data->qty * 1000 * $margin_after_packing;
				$objPHPExcel->getActiveSheet()->setCellValue('U' . $baris, $margin_after_packing); 
				$objPHPExcel->getActiveSheet()->setCellValue('V' . $baris, round($amount_after_packing)); 
				$objPHPExcel->getActiveSheet()->setCellValue('W' . $baris, $data->note); 
			}	
			

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="List Detail Sales SPOT.xls"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');

    }
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL, $param2 = NULL) {
		switch ($param) {
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
				// $data = json_decode(file_get_contents('php://input'), true);
				// if($data){
					// $_POST = $data;
				// }
				$no_form    = (isset($_POST['no_form']) ? $this->generate->kirana_decrypt($_POST['no_form']) : NULL);
				if(isset($_POST['plant'])){
					$plant	= array();
					foreach ($_POST['plant'] as $dt) {
						array_push($plant, $dt);
					}
				}else{
					$plant  = NULL;
				}
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
			case 'simulasi':
				$this->get_simulasi(NULL);
				break;
				
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function save($param = NULL) {
		switch ($param) {
			case 'cost':
				$this->save_cost($param);
				break;
			case 'simulasi':
				$this->save_simulasi($param);
				break;
			case 'excel_spot':
				$this->save_excel_spot($param);
				break;
			case 'selected':
				$this->save_selected($param);
				break;
			case 'deleted':
				$this->save_deleted($param);
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}
    public function set($param = NULL)
    {
        $action = NULL;
        if (isset($_POST['type']) && $_POST['type'] == "non_active") {
            $action = "delete_na";
        } else if (isset($_POST['type']) && $_POST['type'] == "set_active") {
            $action = "activate_na";
        } else if (isset($_POST['type']) && $_POST['type'] == "delete") {
            $action = "delete_na_del";
        }

        if ($action) {
            switch ($param) {
                case 'sales':
                    $this->general->connectDbPortal();
                    $return = $this->general->set($action, "tbl_spot_sales_conf_head", array(
                        array(
                            'kolom' => 'no_form',
                            'value' => $this->generate->kirana_decrypt($_POST['no_form'])
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
	
	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_sales($array = NULL, $no_form = NULL, $active = NULL, $deleted = NULL) {
		$sales 		= $this->dtransaksipol->get_data_sales("open", $no_form, $active, $deleted);
		// $sales 		= $this->general->generate_encrypt_json($sales, array("no_form"));
		if ($array) {
			return $sales;
		} else {
			echo json_encode($sales);
		}
	}
	
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
	private function get_detail_excel($array = NULL) {
		$detail 		= $this->dtransaksipol->get_data_detail_excel("open");
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
					//lha cr-2289 	
					// $this->email->to($dt->email_to);
					$this->email->to('sylviani.oktarista@kiranamegatara.com');
					$detail		= $this->dtransaksipol->get_data_detail("open", $no_form);
					$message  =	'<body style="font-size:12px;font-family:Calibri,Helvetica, Arial, Sans-Serif;">';
					$message .=	'<table>';
					$message .=	'	<tr><td><b>Dear Mr/Ms '.$dt->nama_penerima.' Team</b></td></tr>';
					$message .=	'	<tr><td>We confirm the trade today asf:</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'</table>';
					
					foreach($detail as $det) {
						$message .=	'<table>';
						$message .=	'	<tr><td colspan="3"><b style="font-size:18px;">Sales Confirmation</b></td></tr>';
						$message .=	'	<tr><td>DATE</td><td>:</td><td>'.date('d-M-Y').'</td></tr>';
						// $message .=	'	<tr><td>FORM NO</td><td>:</td><td>'.$det->no_form.'</td></tr>';
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
					//lha cr-2289
					// $this->email->to($dt->email_to);
					// $this->email->cc($dt->email_cc);
					$this->email->to('sylviani.oktarista@kiranamegatara.com');
					$detail		= $this->dtransaksipol->get_data_detail("open", $no_form);
					$message  =	'<body style="font-size:12px;font-family:Calibri,Helvetica, Arial, Sans-Serif;">';
					$message .=	'<table>';
					$message .=	'	<tr><td><b>Dear '.$dt->nama_penerima.' Team</b></td></tr>';
					$message .=	'	<tr><td>Thank you for the business.</td></tr>';
					$message .=	'	<tr><td>We confirm the trade today asf:</td></tr>';
					$message .=	'	<tr><td>&nbsp;</td></tr>';
					$message .=	'</table>';
					foreach($detail as $det) {
						$message .=	'<table>';
						$message .=	'	<tr><td colspan="3"><b style="font-size:18px;">Sales Confirmation</b></td></tr>';
						$message .=	'	<tr><td>DATE</td><td>:</td><td>'.date('d-M-Y').'</td></tr>';
						$message .=	'	<tr><td>BUYER</td><td>:</td><td>'.$det->nama_buyer.'</td></tr>';
						$message .=	'	<tr><td>FACTORY</td><td>:</td><td>'.$det->werks.'</td></tr>';
						$message .=	'	<tr><td>FACTORY CODE</td><td>:</td><td>'.$det->tppco.'</td></tr>';
						$message .=	'	<tr><td>PRODUCT GRADE</td><td>:</td><td>'.$det->prod_grade.'</td></tr>';
						$message .=	'	<tr><td>QUANTITY</td><td>:</td><td>'.$det->qty.' MT</td></tr>';
						$message .=	'	<tr><td>SHIPMENT PERIOD</td><td>:</td><td>'.$det->shipment_periode.'</td></tr>';
						$message .=	'	<tr><td>SHIPMENT TERM</td><td>:</td><td>FOB '.$det->shipment_term.'</td></tr>';
						$message .=	'	<tr><td>PRICE (SW Basis Price)</td><td>:</td><td>'.$det->price.' USC/KG</td></tr>';
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
	private function get_email_buyer($array = NULL, $buyer = NULL) {
		$email 		= $this->dtransaksipol->get_data_email_buyer("open", $buyer);
		if ($array) {
			return $email;
		} else {
			echo json_encode($email);
		}
	}
	private function get_sim($array = NULL, $id_simulate = NULL) {
		$sim 		= $this->dtransaksipol->get_data_sim("open", $id_simulate);
		if ($array) {
			return $sim;
		} else {
			echo json_encode($sim);
		}
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
			$data["shipment_periode"]	= $_POST['shipment_periode_'.$plant];
			$data = $this->dgeneral->basic_column("insert", $data);
			$this->dgeneral->insert("tbl_spot_simulatexx", $data);
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

	private function get_simulasi($array = NULL, $id_simulate = NULL, $buyer = NULL) {
		$simulasi 		= $this->dtransaksipol->get_data_simulasi("open",$id_simulate, $buyer);
		$simulasi 		= $this->general->generate_encrypt_json($simulasi, array("no_form"));
		if ($array) {
			return $simulasi;
		} else {
			echo json_encode($simulasi);
		}
	}
	
	private function save_selected($param) {
		$datetime	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		//save simulasi
		$arr_plant_selected    = explode(",", $_POST['plant_selected']);
		foreach($arr_plant_selected as $plant) {
			$trucking_cost = str_replace(',','',$_POST['trucking_cost_'.$plant])!="" ? str_replace(',','',$_POST['trucking_cost_'.$plant]) : 0;
			
			// $data["no_form"]		 	= $_POST['no_form'];
			$data["factory"]		 		= $_POST['factory_'.$plant];
			$data["plant"]			 		= $_POST['plant_'.$plant];
			$data["mtd_price"]		 		= str_replace(',','',$_POST['mtd_price_'.$plant]);
			$data["selling_price_usc"]		= str_replace(',','',$_POST['selling_price_usc_'.$plant]);
			$data["selling_price"]			= str_replace(',','',$_POST['selling_price_'.$plant]);
			$data["prod_cost"]		 		= str_replace(',','',$_POST['prod_cost_'.$plant]);
			$data["trucking_cost"]	 		= $trucking_cost;
			$data["total_cost"]		 		= str_replace(',','',$_POST['total_cost_'.$plant]);
			$data["carry_cost"]		 		= str_replace(',','',$_POST['carry_cost_'.$plant]); 
			$data["margin"]		 			= str_replace(',','',$_POST['margin_'.$plant]);
			$data["ocp"]		 			= str_replace(',','',$_POST['ocp_'.$plant]);
			$data["breakeven_price"]		= str_replace(',','',$_POST['breakeven_price_'.$plant]);
			$data["cur_rate"]		 		= str_replace(',','',$_POST['cur_rate_'.$plant]);
			$data["pol"]		 			= str_replace(',','',$_POST['pol_'.$plant]);
			$data["pol_value"]	 			= str_replace(',','',$_POST['pol_value_'.$plant]);
			$data["libor_rate"]	 			= str_replace(',','',$_POST['libor_rate_'.$plant]);
			$data["interest_rate"]			= str_replace(',','',$_POST['interest_rate_'.$plant]);
			$data["days"]		 			= str_replace(',','',$_POST['days_'.$plant]);
			$data["prod_cost_type"]			= str_replace(',','',$_POST['prod_cost_type_'.$plant]);
			$data["date"]		 			= $datetime;
			$data["login_edit"]	 			= base64_decode($this->session->userdata("-id_user-"));
			$data["tanggal_edit"]			= $datetime;
			$data["shipment_periode"]		= $_POST['shipment_periode_'.$plant];
			$data["sicom"]					= $_POST['sicom_'.$plant];
			$data["buyer"]					= $_POST['buyer_'.$plant];
			// add by ayy
			$data["deal_harga_pembelian"]	= $_POST['deal_harga_pembelian_'.$plant];
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
	
	private function save_deleted($param) {
		$datetime	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		//deleted simulasi
		$arr_id_simulate_selected    = explode(",", $_POST['id_simulate_selected']);
		foreach($arr_id_simulate_selected as $id_simulate) {
			//update data sent
			$datetime  = date("Y-m-d H:i:s");
			$data_row  = array(
							  'na'   	 	=> 'y',
							  'del'  	  	=> 'y',
							  'login_edit'  => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'=> $datetime
						 );
			$this->dgeneral->update('tbl_spot_simulate', $data_row, array(
																array(
																	'kolom'=>'id_simulate',
																	'value'=>$id_simulate
																)
															));
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil dihapus";
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
		// echo json_encode($_POST['mode']);
		// exit();
		//update simulasi
		// $arr_plant_selected    = explode(",", $_POST['plant_selected']);
		$arr_id_simulate_selected    = explode(",", $_POST['id_simulate_selected']);
		$mode				 		 = (isset($_POST['mode']) ? $_POST['mode'] : NULL);
		foreach($arr_id_simulate_selected as $id_simulate) {
			
			//lha
			if($mode=='edit'){
				//update header
				$datetime  = date("Y-m-d H:i:s");
				$data_row  = array(
								  "sicom"		=> str_replace(',','',$_POST['sicom_'.$id_simulate]),
								  'login_edit'  => base64_decode($this->session->userdata("-id_user-")),
								  'tanggal_edit'=> $datetime
							 );
				$this->dgeneral->update('tbl_spot_sales_conf_head', $data_row, array(
																	array(
																		'kolom'=>'no_form',
																		'value'=>$_POST['no_form_'.$id_simulate]
																	)
																));
				//update detail
				$datetime  = date("Y-m-d H:i:s");
				$data_row  = array(
								"distribution_channel"	=> $_POST['distribution_channel_'.$id_simulate],
								"contract_type" 		=> $_POST['contract_type_'.$id_simulate],
								"no_form"  			=> $_POST['no_form_'.$id_simulate],
								"tppco"				=> $_POST['factory_code_'.$id_simulate],
								"werks"				=> $_POST['factory_'.$id_simulate],
								"prod_grade"		=> $_POST['prod_grade_'.$id_simulate],
								"qty"				=> str_replace(',','',$_POST['qty_'.$id_simulate]),
								"shipment_periode"	=> $_POST['shipment_periode_'.$id_simulate],
								"shipment_term"		=> $_POST['shipment_term_'.$id_simulate],
								"price"				=> str_replace(',','',$_POST['price_'.$id_simulate]),
								"margin"			=> str_replace(',','',$_POST['margin_'.$id_simulate]),
								"note"				=> preg_replace("/\r\n|\r|\n/", '<br/>', htmlentities($_POST['note_'.$id_simulate])),
								'tanggal_edit'		=> $datetime
							 );
				$this->dgeneral->update('tbl_spot_sales_conf_detail', $data_row, array(
																	array(
																		'kolom'=>'no_form',
																		'value'=>$_POST['no_form_'.$id_simulate]
																	)
																));
				
			}else{
				//cek no form
				$ck_form = $this->dtransaksipol->get_data_detail('array', $_POST['no_form_'.$id_simulate]);
				if (count($ck_form) != 0){
					$msg    = "Nomor Form ".$_POST['no_form_'.$id_simulate]." Sudah Terpakai, silahkan proses ulang.";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}	

				//update data sent
				$datetime  = date("Y-m-d H:i:s");
				$data_row  = array(
								  'no_form'    	=> $_POST['no_form_'.$id_simulate],
								  'final'    	=> 'y',
								  'login_edit'  => base64_decode($this->session->userdata("-id_user-")),
								  'tanggal_edit'=> $datetime
							 );
				$this->dgeneral->update('tbl_spot_simulate', $data_row, array(
																	array(
																		'kolom'=>'id_simulate',
																		'value'=>$id_simulate
																	)
																));
				//save header
				$data_header = array(
					"no_form"  		=> $_POST['no_form_'.$id_simulate],
					"buyer"			=> $_POST['buyer_'.$id_simulate],
					"status"		=> 0,
					"sicom"			=> str_replace(',','',$_POST['sicom_'.$id_simulate]),
					"login_buat" 	=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_buat"	=> $datetime,
					"login_edit" 	=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" 	=> $datetime
				);
				$data_header = $this->dgeneral->basic_column("insert", $data_header);
				$this->dgeneral->insert("tbl_spot_sales_conf_head", $data_header);
				
				//save detail
				$data_detail 	= array(
					"distribution_channel"	=> $_POST['distribution_channel_'.$id_simulate],
					"contract_type" 		=> $_POST['contract_type_'.$id_simulate],
					"no_form"  			=> $_POST['no_form_'.$id_simulate],
					"tppco"				=> $_POST['factory_code_'.$id_simulate],
					"werks"				=> $_POST['factory_'.$id_simulate],
					"prod_grade"		=> $_POST['prod_grade_'.$id_simulate],
					"qty"				=> str_replace(',','',$_POST['qty_'.$id_simulate]),
					"shipment_periode"	=> $_POST['shipment_periode_'.$id_simulate],
					"shipment_term"		=> $_POST['shipment_term_'.$id_simulate],
					"price"				=> str_replace(',','',$_POST['price_'.$id_simulate]),
					"margin"			=> str_replace(',','',$_POST['margin_'.$id_simulate]),
					"note"				=> preg_replace("/\r\n|\r|\n/", '<br/>', htmlentities($_POST['note_'.$id_simulate])),
					"login_buat" 		=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_buat"		=> $datetime,
					"login_edit" 		=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" 		=> $datetime
				);
				$data_detail = $this->dgeneral->basic_column("insert", $data_detail);
				$this->dgeneral->insert("tbl_spot_sales_conf_detail", $data_detail);
				
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

		$data_sim = $this->get_sim('array',$_POST['id_simulate_selected']);
		foreach($data_sim as $dt) { 
			//jika HO
			$subject = 'SPOT '.date('d-M-Y').' ('.$dt->buyer.')';
			$this->load->library('email', $config);
			$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
			$this->email->subject($subject);
			$data_email = $this->get_email('array',$dt->buyer);
			// $data_email = $this->get_email('array','PT. Gajah Tunggal Tbk');
			$list_email_ho = "";
			foreach($data_email as $dt2){
				$list_email_ho .= $dt2->email_to.",";
			}
			//lha CR-2289
			// $this->email->to(substr($list_email_ho, 0, -1));
			// $this->email->to('sylviani.oktarista@kiranamegatara.com');
			$this->email->to('lukman.hakim@kiranamegatara.com');
		
			$message  =	'<body style="font-size:12px;font-family:Calibri,Helvetica, Arial, Sans-Serif;">';
			$data_simulasi = $this->get_simulasi('array',$_POST['id_simulate_selected'],$dt->buyer);	
			foreach($data_simulasi as $dt2) {
				$caption_factory = (($dt2->plant_name=='PT. Djambi Waras Jambi')or($dt2->plant_name=='PT. Djambi Waras Jujuhan')) ?"PT. Djambi Waras":$dt2->plant_name;
				$message .=	'<table>';
				$message .=	'	<tr><td colspan="3"><b style="font-size:18px;">SALES CONFIRMATION</b></td></tr>';
				// $message .=	'	<tr><td>CONTRACT NUMBER</td><td>:</td><td>'.$dt2->no_contract.'</td></tr>';
				$message .=	'	<tr><td>FORM NUMBER</td><td>:</td><td>'.$_POST['no_form_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td width="150px">DATE</td><td>:</td><td>'.date('d-M-Y').'</td></tr>';
				$message .=	'	<tr><td>BUYER</td><td>:</td><td>'.$_POST['buyer_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>FACTORY</td><td>:</td><td>'.$caption_factory.'</td></tr>';
				$message .=	'	<tr><td>FACTORY CODE</td><td>:</td><td>'.$_POST['factory_code_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>PRODUCT GRADE</td><td>:</td><td>'.$_POST['prod_grade_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>QUANTITY</td><td>:</td><td>'.$_POST['qty_'.$dt2->id_simulate].' MT</td></tr>';
				$message .=	'	<tr><td>SHIPMENT PERIOD</td><td>:</td><td>'.$_POST['shipment_periode_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>SHIPMENT TERM</td><td>:</td><td>FOB '.$_POST['shipment_term_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>PRICE</td><td>:</td><td>'.$_POST['price_'.$dt2->id_simulate].' USC/KG</td></tr>';
				$message .=	'	<tr><td>SICOM</td><td>:</td><td>'.$_POST['sicom_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>MARGIN</td><td>:</td><td>'.$_POST['margin_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>NOTES</td><td>:</td><td>'.$_POST['note_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td colspan="3">&nbsp;</td></tr>';
				$message .=	'</table>';
			}
			$message .=	'<table>';
			$message .=	'	<tr><td>&nbsp;</td></tr>';
			$message .=	'	<tr><td>Thank you.</td></tr>';
			$message .=	'	<tr><td>&nbsp;</td></tr>';
			$message .=	'	<tr><td>Best regards,</td></tr>';
			$message .=	'	<tr><td>Sales & Marketing Division.</td></tr>';
			$message .=	'	<tr><td>PT Kirana Megatara Tbk.</td></tr>';
			$message .=	'</table>';
			$message .= '</body>';
			$this->email->message($message);
			$this->email->send();
			// echo $message; 
			// exit();
			//jika buyer 	
			$data_email_buyer = $this->get_email_buyer('array',$dt->buyer);
			$list_email_to = "";
			$list_email_cc = "";
			foreach($data_email_buyer as $dt){
				$nama_buyer		= $dt->nama_buyer;
				$nama_penerima	= $dt->nama_penerima;
				$list_email_to .= $dt->email_to.",";
				$list_email_cc	.= $dt->email_cc;				
			}	
			$email_to	= substr($list_email_to, 0, -1); 
			$email_cc	= substr($list_email_cc, 0, -1); 
			$subject = 'SPOT '.date('d-M-Y');
			$this->load->library('email', $config);
			$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
			$this->email->subject($subject);
			//lha cr-2289
			// $this->email->to($email_to);
			// $this->email->cc($email_cc);
			$this->email->to('sylviani.oktarista@kiranamegatara.com');

			$message  =	'<body style="font-size:12px;font-family:Calibri,Helvetica, Arial, Sans-Serif;">';
			$message .=	'<table>';
			$message .=	'	<tr><td><b>Dear '.$nama_penerima.' Team</b></td></tr>';
			$message .=	'	<tr><td>Thank you for the business.</td></tr>';
			$message .=	'	<tr><td>We confirm the trade today asf:</td></tr>';
			$message .=	'	<tr><td>&nbsp;</td></tr>';
			$message .=	'</table>'; 
			foreach($data_simulasi as $dt2) {
				$caption_factory = (($dt2->plant_name=='PT. Djambi Waras Jambi')or($dt2->plant_name=='PT. Djambi Waras Jujuhan')) ?"PT. Djambi Waras":$dt2->plant_name;
				$message .=	'<table>';
				$message .=	'	<tr><td colspan="3"><b style="font-size:18px;">SALES CONFIRMATION</b></td></tr>';
				$message .=	'	<tr><td>DATE</td><td>:</td><td>'.date('d-M-Y').'</td></tr>';
				$message .=	'	<tr><td>BUYER</td><td>:</td><td>'.$nama_buyer.'</td></tr>';
				$message .=	'	<tr><td>FACTORY</td><td>:</td><td>'.$caption_factory.'</td></tr>';
				$message .=	'	<tr><td>FACTORY CODE</td><td>:</td><td>'.$_POST['factory_code_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>PRODUCT GRADE</td><td>:</td><td>'.$_POST['prod_grade_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>QUANTITY</td><td>:</td><td>'.$_POST['qty_'.$dt2->id_simulate].' MT</td></tr>';
				$message .=	'	<tr><td>SHIPMENT PERIOD</td><td>:</td><td>'.$_POST['shipment_periode_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>SHIPMENT TERM</td><td>:</td><td>FOB '.$_POST['shipment_term_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td>PRICE</td><td>:</td><td>'.$_POST['price_'.$dt2->id_simulate].' USC/KG</td></tr>';
				$message .=	'	<tr><td>NOTES</td><td>:</td><td>'.$_POST['note_'.$dt2->id_simulate].'</td></tr>';
				$message .=	'	<tr><td colspan="3">&nbsp;</td></tr>';
				$message .=	'</table>';
				
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
																		'value'=>$_POST['no_form_'.$dt2->id_simulate]
																	)
																));
				
			}
			$message .=	'<table>';
			$message .=	'	<tr><td>&nbsp;</td></tr>';
			$message .=	'	<tr><td>Please kindly confirm.</td></tr>';
			$message .=	'	<tr><td>&nbsp;</td></tr>';
			$message .=	'	<tr><td>Thank you.</td></tr>';
			$message .=	'	<tr><td>&nbsp;</td></tr>';
			$message .=	'	<tr><td>Best regards,</td></tr>';
			$message .=	'	<tr><td>Sales & Marketing Division.</td></tr>';
			$message .=	'	<tr><td>PT Kirana Megatara Tbk.</td></tr>';
			$message .=	'</table>';
			$message .= '</body>';
			$this->email->message($message);
			$this->email->send();
			
			
		}
		
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//import
	private function save_excel_spot($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		if(!empty($_FILES['file_excel']['name'])){
			$target_dir = "./assets/temp";

			if (!file_exists($target_dir)) {
			    mkdir($target_dir, 0755, true);
			}

			$config['upload_path']          = $target_dir;
			$config['allowed_types']        = 'xls|xlsx';
	 
			$this->load->library('upload', $config);
	 
			if ( ! $this->upload->do_upload('file_excel')){
				$msg 	= $this->upload->display_errors();
				$sts 	= "NotOK";
			}else{
				$data 			= array('upload_data' => $this->upload->data());
				$objPHPExcel 	= PHPExcel_IOFactory::load($data['upload_data']['full_path']);
				$title_desc		= $objPHPExcel->getProperties()->getTitle();
				$objPHPExcel->setActiveSheetIndex(0);
				$data_excel		= $objPHPExcel->getActiveSheet();
				$highestRow 	= $data_excel->getHighestRow(); 
				$highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
				$datetime		= date("Y-m-d H:i:s");
				$data_row		= array();
				for($brs=2; $brs<=$highestRow; $brs++){
					//tbl_spot_simulate
					$data_row	= array(
 									'no_contract'	 	=> $data_excel->getCellByColumnAndRow(0, $brs)->getValue(),
									'no_form'	 		=> $data_excel->getCellByColumnAndRow(1, $brs)->getValue(),
									'factory'	 		=> $data_excel->getCellByColumnAndRow(2, $brs)->getValue(),
									'plant'	 			=> $data_excel->getCellByColumnAndRow(3, $brs)->getValue(),
									'mtd_price'	 		=> $data_excel->getCellByColumnAndRow(4, $brs)->getValue(),
									'selling_price_usc'	=> $data_excel->getCellByColumnAndRow(5, $brs)->getValue(),
									'selling_price'	 	=> $data_excel->getCellByColumnAndRow(6, $brs)->getValue(),
									'prod_cost'	 		=> $data_excel->getCellByColumnAndRow(7, $brs)->getValue(),
									'trucking_cost'	 	=> $data_excel->getCellByColumnAndRow(8, $brs)->getValue(),
									'total_cost'	 	=> $data_excel->getCellByColumnAndRow(9, $brs)->getValue(),
									'carry_cost'	 	=> $data_excel->getCellByColumnAndRow(10, $brs)->getValue(),
									'margin'	 		=> $data_excel->getCellByColumnAndRow(11, $brs)->getValue(),
									'ocp'	 			=> $data_excel->getCellByColumnAndRow(12, $brs)->getValue(),
									'breakeven_price'	=> $data_excel->getCellByColumnAndRow(13, $brs)->getValue(),
									'cur_rate'	 		=> $data_excel->getCellByColumnAndRow(14, $brs)->getValue(),
									'pol'	 			=> $data_excel->getCellByColumnAndRow(15, $brs)->getValue(),
									'pol_value'	 		=> $data_excel->getCellByColumnAndRow(16, $brs)->getValue(),
									'libor_rate'	 	=> $data_excel->getCellByColumnAndRow(17, $brs)->getValue(),
									'interest_rate'	 	=> $data_excel->getCellByColumnAndRow(18, $brs)->getValue(),
									'days'	 			=> $data_excel->getCellByColumnAndRow(19, $brs)->getValue(),
									'prod_cost_type'	=> $data_excel->getCellByColumnAndRow(20, $brs)->getValue(),
									'date'	 			=> $data_excel->getCellByColumnAndRow(21, $brs)->getValue(),
									'login_buat' 		=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'		=> $datetime,
									'login_edit' 		=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit' 		=> $datetime,
									'na' 				=> 'n',
									'del'				=> 'n',
									'buyer'	 			=> $data_excel->getCellByColumnAndRow(29, $brs)->getValue(),
									'sicom'	 			=> $data_excel->getCellByColumnAndRow(32, $brs)->getValue(),
									'shipment_periode'	=> $data_excel->getCellByColumnAndRow(24, $brs)->getValue()
								);	
					$this->dgeneral->insert('tbl_spot_simulate', $data_row);
					//header
					$data_header	= array(
									'no_form'		=> $data_excel->getCellByColumnAndRow(1, $brs)->getValue(),
									'buyer'	 		=> $data_excel->getCellByColumnAndRow(29, $brs)->getValue(),
									'status'		=> 1,
									'sicom'	 		=> $data_excel->getCellByColumnAndRow(32, $brs)->getValue(),
									'login_buat'	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'	=> $datetime,
									'login_edit' 	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit'	=> $datetime,
									'na' 			=> 'n',
									'del'			=> 'n'
								);	
					$this->dgeneral->insert('tbl_spot_sales_conf_head', $data_header);
					//detail
					$data_detail	= array(
									'no_form'		=> $data_excel->getCellByColumnAndRow(1, $brs)->getValue(),
									'tppco'	 		=> $data_excel->getCellByColumnAndRow(2, $brs)->getValue(),
									'werks'			=> $data_excel->getCellByColumnAndRow(3, $brs)->getValue(),
									'prod_grade'	=> $data_excel->getCellByColumnAndRow(25, $brs)->getValue(),
									'qty'	 		=> $data_excel->getCellByColumnAndRow(23, $brs)->getValue(),
									'shipment_periode'	=> $data_excel->getCellByColumnAndRow(24, $brs)->getValue(),
									'shipment_term'	=> $data_excel->getCellByColumnAndRow(15, $brs)->getValue(),
									'price'	 		=> $data_excel->getCellByColumnAndRow(5, $brs)->getValue(),
									'margin'	 	=> $data_excel->getCellByColumnAndRow(11, $brs)->getValue(),
									'note'	 		=> '-',
									'login_buat'	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'	=> $datetime,
									'login_edit' 	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit'	=> $datetime,
									'na' 			=> 'n',
									'del'			=> 'n'
								);	
					$this->dgeneral->insert('tbl_spot_sales_conf_detail', $data_detail);
				}
				if($this->dgeneral->status_transaction() === FALSE){
					$this->dgeneral->rollback_transaction();
					$msg 	= "Periksa kembali data yang diunggah";
					$sts 	= "NotOK";
				}else{
					$this->dgeneral->commit_transaction();
					$msg 	= "Data berhasil ditambahkan";
					$sts 	= "OK";
				}
				
				unlink($data['upload_data']['full_path']);
			}
		}else{
			$msg 	= "Silahkan pilih file yang ingin diunggah";
			$sts 	= "NotOK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		
	}
	
	/*====================================================================*/
}
