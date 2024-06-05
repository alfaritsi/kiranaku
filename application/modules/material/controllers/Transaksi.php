<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KODE MATERIAL
@author       : Lukman Hakim (7143)
@contributor  : 
      1. Airiza Yuddha (7849) 14 okt 2020
         modified function save_request - add field estimate_price         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
include_once APPPATH . "modules/material/controllers/BaseControllers.php";

Class Transaksi extends BaseControllers{
// Class Transaksi extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		
		$this->load->model('dmastermaterial');
	    $this->load->model('dtransaksimaterial');
	}

	public function index(){
		show_404();
	}
	
	public function proc($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Konfirmasi Procurement";
		$data['title_form']  = "Form Konfirmasi Procurement";
		// $data['group'] 	 	 = $this->get_group('array');
		$data['group'] 	 	 = $this->get_group('array',NULL,'n');
		// $data['item'] 	 	 = $this->get_item('array');
		$data['uom'] 	 	 = $this->get_uom('array');
		$data['ekgrp'] 	 	 = $this->get_ekgrp('array');
		$data['plant'] 	 	 = $this->get_plant('array');
		$data['lgort'] 	 	 = $this->get_lgort('array');
		$data['dispo'] 	 	 = $this->get_dispo('array');
		$data['lot'] 	 	 = $this->get_lot('array');
		$data['dist'] 	 	 = $this->get_dist('array');
		$data['div'] 	 	 = $this->get_div('array');
		$data['profit']	 	 = $this->get_profit('array');
		// $data['spec'] 	 	 = $this->get_spec('array', NULL, NULL, NULL);
		$this->load->view("transaksi/proc", $data);	
	}

	public function acc($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Konfirmasi Accounting";
		$data['title_form']  = "Form Konfirmasi Accounting";
		// $data['acc'] 	 	 = $this->get_acc('array', NULL, NULL, NULL);
		$data['acc'] 	 	 = $this->get_item('array', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'y');
		$this->load->view("transaksi/acc", $data);	
	}
	
	
	public function input($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		//cek otoritas
		$otoritas 			= $this->dmastermaterial->get_data_otoritas(NULL);
		if (count($otoritas) == 0){
			show_404();
		}
		
		
		$data['title']    	 = "Konfirmasi Permintaan Kode Material Baru";
		$data['title_form']  = "Form Konfirmasi Permintaan Kode Material Baru";
		$data['spec']	 	 = $this->get_spec('array');
		// $data['input']  	 = $this->get_input('array', NULL, NULL, NULL);

		$filter_from		 = date('d.m.Y', strtotime('-1 month'));
		$filter_to 			 = date("d.m.Y");
		$data['input']  	 = $this->get_input('array', NULL, NULL, NULL, NULL, NULL, $filter_from, $filter_to, 'y');
		$this->load->view("transaksi/input", $data);	
	} 
	
	public function request($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		//cek otoritas
		$posisi				= $this->dmastermaterial->get_data_posisi("open", NULL, base64_decode($this->session->userdata("-posst-")));
		$otoritas 			= $this->dmastermaterial->get_data_otoritas(NULL, $posisi[0]->id_posisi);
		if (count($otoritas) == 0){
			show_404();
		}
		
		$filter_from		 = date('d.m.Y', strtotime('-1 month'));
		$filter_to 			 = date("d.m.Y");
		$data['title']    	 = "Permintaan Kode Material Baru";
		$data['title_form']  = "Form Permintaan Kode Material Baru";
		$data['request']  	 = $this->get_request('array', NULL, NULL, NULL, NULL, NULL, $filter_from, $filter_to);
		$this->load->view("transaksi/request", $data);	
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
                ->setTitle("Export Request (" . date('d-m-Y') . ")")
                ->setSubject("Export Request (" . date('d-m-Y') . ")")
                ->setDescription("Export Request (" . date('d-m-Y') . ")")
                ->setCategory("EXPORT Request");

            // Add some data
			//header
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Request Date');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Request Time');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Confirm Date');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Confirm Time');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Type');

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Description');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'UOM');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Estimate Price (Rp)');

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Images');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Material Code');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Classification');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Material Description');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Request Status');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'PIC');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Status');
            $objPHPExcel->getActiveSheet()->setTitle('Request');
			//content
			$list_data = $this->get_request('array');
            $baris = 1;
            foreach ($list_data as $data) {
                $baris++;
				$objPHPExcel->getActiveSheet()->getRowDimension($baris)->setRowHeight(40);
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
				
				$code_spec		= ($data->code!=NULL)?$data->code:$data->code_spec;
				$classification	= ($data->req=='n')?$data->label_classification:"";
				$spec_desc		= ($data->spec_desc!=NULL)?$data->spec_desc:$data->spec_desc_sap;
				$price_show = $data->estimate_price != "" && $data->estimate_price != null ? number_format($data->estimate_price,0,',','.') : "-";
				
				$price 			= $data->estimate_price != "" && $data->estimate_price != null ? $data->estimate_price : "-";
				
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $baris, $data->tanggal); 
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $baris, $data->jam_buat); 
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $baris, $data->tanggal_conf); 
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $baris, $data->jam_conf); 
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $baris, $data->type); 
				
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $baris, $data->description); 
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $baris, $data->uom); 
				$objPHPExcel->getActiveSheet()->setCellValue('H' . $baris, $price); 
				$lebar = 0;
				$list_gambar = explode(",", substr($data->list_gambar,0,-1));
				foreach ($list_gambar as $url_gambar) {
					if(!empty($url_gambar)){
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('Customer Signature');
						$objDrawing->setDescription('Customer Signature');
						//Path to signature .jpg file
						$gbr  = str_replace('http://','',$url_gambar); 
						$url  = explode("/" , $gbr);
						// $path = str_replace($url[0],$_SERVER['DOCUMENT_ROOT'],$url_gambar); 
						$path = str_replace($url[0],'/var/www/html/uat/',$url_gambar); 
						$path =  str_replace('http://', '', $path);
						// echo json_encode($url[0]);
						if (file_exists($path)) {
							// $signature = $path;  
							$signature = $url_gambar;	  
						}else{
							// $signature = 'D:/xampp2/htdocs/dev/kiranaku/assets/file/material/no_image.JPG';  
							// $signature = 'C:xampp/htdocs/uat/kiranaku/assets/file/cctv/default.png';
							$signature = '/var/www/html/uat/kiranaku/assets/file/cctv/default.png';
						}
						
						$objDrawing->setPath($signature);
						$objDrawing->setOffsetX(25+$lebar);                     //setOffsetX works properly
						$objDrawing->setOffsetY(10);                     //setOffsetY works properly
						$objDrawing->setCoordinates('I' . $baris);             //set image to cell 
						$objDrawing->setWidth(32);  
						$objDrawing->setHeight(32);                     //signature height  
						$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
						$lebar +=50;
					}
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue('J' . $baris, $code_spec); 
				$objPHPExcel->getActiveSheet()->setCellValue('K' . $baris, strip_tags($classification)); 
				$objPHPExcel->getActiveSheet()->setCellValue('L' . $baris, $data->spec_desc); 
				$objPHPExcel->getActiveSheet()->setCellValue('M' . $baris, $data->excel_request); 
				$objPHPExcel->getActiveSheet()->setCellValue('N' . $baris, $data->nama_pic.'-'.$data->nik_pic); 

				$objPHPExcel->getActiveSheet()->setCellValue('O' . $baris, $data->excel_status); 
			}	
			

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Request_Material_Code.xls"');
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

    public function excel_konfirmasi(){
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
                ->setTitle("Export Request (" . date('d-m-Y') . ")")
                ->setSubject("Export Request (" . date('d-m-Y') . ")")
                ->setDescription("Export Request (" . date('d-m-Y') . ")")
                ->setCategory("EXPORT Request");

            // Add some data
			//header
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Plant');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Request Date');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Request Time');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Confirm Date');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Confirm Time');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Type');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Description');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'UOM');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Estimate Price (Rp)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Images');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Material Code');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Classification');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Material Description');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Request Status');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'PIC');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Status');
            $objPHPExcel->getActiveSheet()->setTitle('Request');
			//content
			$list_data = $this->get_input('array');
            $baris = 1;
            foreach ($list_data as $data) {
                $baris++;
				$objPHPExcel->getActiveSheet()->getRowDimension($baris)->setRowHeight(40);
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
				$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
				
				// $tanggal_req 	= $data->tanggal != "" ? $data->tanggal : "-";
				// $tanggal_conf 	= $data->req == 'y' ? "-" : $data->tanggal_conf;
				// $code_spec		= ($data->code!=NULL)?$data->code:$data->code_spec;
				// $classification	= ($data->req=='n')?$data->label_classification:"";
				// $tanggal_req 	= $dt->tanggal != "" ? $dt->tanggal."<br>".$dt->jam_buat : "-";
				// $tanggal_conf 	= $dt->req == 'y' ? "-" : $dt->tanggal_conf."<br>".$dt->jam_conf;
				$code_spec		= ($data->code!=NULL)?$data->code:$data->code_spec;
				$classification	= ($data->req=='n')?$data->label_classification:"";
				$spec_desc		= ($data->spec_desc!=NULL)?$data->spec_desc:$data->spec_desc_sap;
				$price_show = $data->estimate_price != "" && $data->estimate_price != null ? number_format($data->estimate_price,0,',','.') : "-";
				
				$price 			= $data->estimate_price != "" && $data->estimate_price != null ? $data->estimate_price : "-";
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $baris, $data->gsber); 
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $baris, $data->tanggal); 
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $baris, $data->jam_buat); 
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $baris, $data->tanggal_conf); 
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $baris, $data->jam_conf); 
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $baris, $data->type); 
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $baris, $data->description); 
				$objPHPExcel->getActiveSheet()->setCellValue('H' . $baris, $data->uom); 
				$objPHPExcel->getActiveSheet()->setCellValue('I' . $baris, $price); 
				$lebar = 0;
				$list_gambar = explode(",", substr($data->list_gambar,0,-1));
				foreach ($list_gambar as $url_gambar) {
					if(!empty($url_gambar)){
						$objDrawing = new PHPExcel_Worksheet_Drawing();
						$objDrawing->setName('Customer Signature');
						$objDrawing->setDescription('Customer Signature');
						//Path to signature .jpg file
						$gbr  = str_replace('http://','',$url_gambar); 
						$url  = explode("/" , $gbr);
						// $path = str_replace($url[0],$_SERVER['DOCUMENT_ROOT'],$url_gambar); 
						$path = str_replace($url[0],'/var/www/html/uat/',$url_gambar); 
						$path =  str_replace('http://', '', $path);
						// echo json_encode($url[0]);
						if (file_exists($url_gambar)) {
							// $signature = $path;  
							$signature = $url_gambar;	  
						}else{
							// $signature = 'D:/xampp2/htdocs/dev/kiranaku/assets/file/material/no_image.JPG';  
							// $signature = 'C:xampp/htdocs/uat/kiranaku/assets/file/cctv/default.png';
							$signature = '/var/www/html/uat/kiranaku/assets/file/cctv/default.png';
						}
						
						$objDrawing->setPath($signature);
						$objDrawing->setOffsetX(25+$lebar);                     //setOffsetX works properly
						$objDrawing->setOffsetY(10);                     //setOffsetY works properly
						$objDrawing->setCoordinates('J' . $baris);             //set image to cell 
						$objDrawing->setWidth(32);  
						$objDrawing->setHeight(32);                     //signature height  
						$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
						$lebar +=50;
					}
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue('K' . $baris, $code_spec); 
				$objPHPExcel->getActiveSheet()->setCellValue('L' . $baris, strip_tags($classification)); 
				$objPHPExcel->getActiveSheet()->setCellValue('M' . $baris, $data->spec_desc); 
				$objPHPExcel->getActiveSheet()->setCellValue('N' . $baris, $data->excel_request); 
				$objPHPExcel->getActiveSheet()->setCellValue('O' . $baris, $data->nama_pic.'-'.$data->nik_pic); 
				$objPHPExcel->getActiveSheet()->setCellValue('P' . $baris, $data->excel_status); 
			}	

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Konfirmasi_Material_Code.xls"');
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
	
	public function spec($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Item Spesifikasi";
		$data['title_form']  = "Form Item Spesifikasi";
		// $data['group'] 	 	 = $this->get_group('array');
		$data['group'] 	 	 = $this->get_group('array',NULL,'n');
		// $data['item'] 	 	 = $this->get_item('array');
		$data['uom'] 	 	 = $this->get_uom('array');
		$data['ekgrp'] 	 	 = $this->get_ekgrp('array');
		$data['plant'] 	 	 = $this->get_plant('array');
		$data['lgort'] 	 	 = $this->get_lgort('array');
		$data['dispo'] 	 	 = $this->get_dispo('array');
		$data['lot'] 	 	 = $this->get_lot('array');
		$data['dist'] 	 	 = $this->get_dist('array');
		$data['div'] 	 	 = $this->get_div('array');
		$data['profit']	 	 = $this->get_profit('array');
		$data['bklas']	 	 = $this->get_bklas('array');
		// $data['spec'] 	 	 = $this->get_spec('array', NULL, NULL, NULL);
		$this->load->view("transaksi/spec", $data);	
	}
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'cek':
				$tabel   = (isset($_POST['tabel']) ? $_POST['tabel'] : NULL);
				$field   = (isset($_POST['field']) ? $_POST['field'] : NULL);
				$value   = (isset($_POST['value']) ? $_POST['value'] : NULL);
				$field2  = (isset($_POST['field2']) ? $_POST['field2'] : NULL);
				$value2  = (isset($_POST['value2']) ? $_POST['value2'] : NULL);
				$this->get_cek(NULL, $tabel, $field, $value, $field2, $value2);
				break;
			
			case 'lgort':
				if(isset($_POST['plant'])){
					$plant	= array();
					foreach ($_POST['plant'] as $dt) {
						array_push($plant, $dt);
					}
				}else{
					$plant  = NULL;
				}
				$this->get_lgort(NULL, $plant);
				break;
			
			case 'nomor':
				$id_item_group  = ((isset($_POST['id_item_group'])and($_POST['id_item_group']!=0)) ? $_POST['id_item_group'] : NULL);
				$id_item_name   = ((isset($_POST['id_item_name'])and($_POST['id_item_name']!=0)) ? $_POST['id_item_name'] : NULL);
				$this->get_nomor(NULL, $id_item_group, $id_item_name);
				break;
			
			case 'spec':
				$id_item_spec  = (isset($_POST['id_item_spec']) ? $this->generate->kirana_decrypt($_POST['id_item_spec']) : NULL);
				
				if(isset($_POST['id_item_group'])){
					$id_item_group	= array();
					foreach ($_POST['id_item_group'] as $dt) {
						array_push($id_item_group, $dt);
					}
				}else{
					$id_item_group  = NULL;
				}
				if(isset($_POST['id_item_name'])){
					$id_item_name	= array();
					foreach ($_POST['id_item_name'] as $dt) {
						array_push($id_item_name, $dt);
					}
				}else{
					$id_item_name  = NULL;
				}
				if(isset($_POST['status'])){
					$status	= array();
					foreach ($_POST['status'] as $dt) {
						array_push($status, $dt);
					}
				}else{
					$status  = NULL;
				}
				if(isset($_POST['filter_request_status'])){
					$filter_request_status	= array();
					foreach ($_POST['filter_request_status'] as $dt) {
						array_push($filter_request_status, $dt);
					}
				}else{
					$filter_request_status  = NULL;
				}
				if(isset($_POST['filter_classification'])){
					$filter_classification	= array();
					foreach ($_POST['filter_classification'] as $dt) {
						array_push($filter_classification, $dt);
					}
				}else{
					$filter_classification  = NULL;
				}
				
				if($param2=='bom'){
					header('Content-Type: application/json');
					$return = $this->dtransaksimaterial->get_data_spec_bom('open', $id_item_spec, NULL, 'n', $id_item_group, $id_item_name, $status, $filter_request_status, $filter_classification);
					// $return = $this->dtransaksimaterial->get_data_spec_bom_test('open', $id_item_spec, NULL, NULL, $id_item_group, $id_item_name, $status, $filter_request_status);
					echo $return;
					break;
				}else if($param2=='auto'){
					if (isset($_GET['q'])) {
						$data      = $this->dtransaksimaterial->get_data_spec('open', NULL, NULL, NULL, $_GET['q']);
						$data 	   = $this->general->generate_encrypt_json($data, array("id_item_spec"));
						$data_json = array(
							"total_count"        => count($data),
							"incomplete_results" => false,
							"items"              => $data
						);

						$return = json_encode($data_json);
						$return = $this->general->jsonify($data_json);

						echo $return;
						break;
					}
				}else{
					$req  = (isset($_POST['req']) ? $_POST['req'] : NULL);
					$this->get_spec(NULL, $id_item_spec, 'n', 'n', NULL, $req);
					break;
				}
			case 'request':
				$id_item_request  = (isset($_POST['id_item_request']) ? $this->generate->kirana_decrypt($_POST['id_item_request']) : NULL);
				$all 			  = (isset($_POST['all']) ? $_POST['all'] : NULL);
				$req 			  = (isset($_POST['req']) ? $_POST['req'] : NULL);
				$confirm		  = (isset($_POST['confirm']) ? $_POST['confirm'] : NULL);
				$filter_from 	  = (isset($_POST['filter_from'])) ? $_POST['filter_from'] : NULL;
				$filter_to 		  = (isset($_POST['filter_to'])) ? $_POST['filter_to'] : NULL;
				if(isset($_POST['filter_request_status'])){
					$filter_request_status	= array();
					foreach ($_POST['filter_request_status'] as $dt) {
						array_push($filter_request_status, $dt);
					}
				}else{
					$filter_request_status  = NULL;
				}
				if(isset($_POST['filter_status'])){
					$filter_status	= array();
					foreach ($_POST['filter_status'] as $dt) {
						array_push($filter_status, $dt);
					}
				}else{
					$filter_status  = NULL;
				}
				$this->get_request(NULL, $id_item_request, NULL, NULL, $all, $req, $filter_from, $filter_to, $filter_request_status, $filter_status, $confirm);
				break;
			// case 'input':
				// $id_item_request  = (isset($_POST['id_item_request']) ? $this->generate->kirana_decrypt($_POST['id_item_request']) : NULL);
				// $this->get_input(NULL, $id_item_request, NULL, NULL);
				// break;
			case 'input':
				$id_item_request  = (isset($_POST['id_item_request']) ? $this->generate->kirana_decrypt($_POST['id_item_request']) : NULL);
				$filter_from 	  = (isset($_POST['filter_from'])) ? $_POST['filter_from'] : NULL;
				$filter_to 		  = (isset($_POST['filter_to'])) ? $_POST['filter_to'] : NULL;
				if(isset($_POST['filter_request_status'])){
					$filter_request_status	= array();
					foreach ($_POST['filter_request_status'] as $dt) {
						array_push($filter_request_status, $dt);
					}
				}else{
					$filter_request_status  = NULL;
				}
				$this->get_input(NULL, NULL, NULL, NULL, NULL, NULL, $filter_from, $filter_to, $filter_request_status);
				break;
				
			case 'item':
				$id_item_name  	= (isset($_POST['id_item_name']) ? $_POST['id_item_name'] : NULL);
				$id_item_group  = (isset($_POST['id_item_group']) ? $_POST['id_item_group'] : NULL);
				if(isset($_POST['id_item_group_filter'])){
					$id_item_group_filter	= array();
					foreach ($_POST['id_item_group_filter'] as $dt) {
						array_push($id_item_group_filter, $dt);
					}
				}else{
					$id_item_group_filter  = NULL;
				}
				if(isset($_POST['filter_request_status'])){
					$filter_request_status	= array();
					foreach ($_POST['filter_request_status'] as $dt) {
						array_push($filter_request_status, $dt);
					}
				}else{
					$filter_request_status  = NULL;
				}
				$na = (isset($_POST['na']) ? $_POST['na'] : NULL);
				$this->get_item(NULL, $id_item_name, $na, NULL, NULL, $id_item_group, NULL, NULL, NULL, $id_item_group_filter, $filter_request_status,'n');
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
				case 'spec':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_item_spec", array(
						array(
							'kolom' => 'id_item_spec',
							'value' => $this->generate->kirana_decrypt($_POST['id_item_spec'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'request':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_item_request", array(
						array(
							'kolom' => 'id_item_request',
							'value' => $this->generate->kirana_decrypt($_POST['id_item_request'])
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
			// case 'change':
				// $this->save_change($param);
				// break;
			case 'extend':
				$this->save_extend($param);
				break;
			case 'input':
				$this->save_input($param);
				break;
			case 'inventory':
				$this->save_inventory($param);
				break;
			case 'proc_confirm':
				$this->save_proc_confirm($param);
				break;
			case 'proc':
				$this->save_proc($param);
				break;
			case 'acc_confirm':
				$this->save_acc_confirm($param);
				break;
			case 'acc':
				$this->save_acc($param);
				break;
			case 'spec':
				$this->save_spec($param);
				break;
			case 'request':
				$this->save_request($param);
				break;
			case 'request_ho':
				$this->save_request_ho($param);
				break;
			case 'request_pabrik':
				$this->save_request_pabrik($param);
				break;
			case 'excel_spec':
				$this->save_excel_spec($param);
				break;
			case 'tolak':
				$this->save_tolak($param);
				break;
			case 'sales':
				//extend tab sales
				$this->save_sales($param);
				break;
			case 'proc_edit':
				$this->save_proc_edit($param);
				break;
			case 'delete_request':
				$this->save_delete_request($param);
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
	private function get_group($array = NULL, $id_item_group = NULL, $active = NULL, $deleted = NULL, $description = NULL) {
		$group 		= $this->dmastermaterial->get_data_group("open", $id_item_group, $active, $deleted, $description);
		if ($array) {
			return $group;
		} else {
			echo json_encode($group);
		}
	}
	private function get_item($array = NULL, $id_item_name = NULL, $active = NULL, $deleted = NULL, $description = NULL, $id_item_group = NULL, $bklas = NULL, $matkl = NULL, $classification = NULL, $id_item_group_filter = NULL, $filter_request_status = NULL, $req = NULL) {
		$item 		= $this->dmastermaterial->get_data_item("open", $id_item_name, $active, $deleted, $description, $id_item_group, $bklas, $matkl, $classification, $id_item_group_filter, $filter_request_status, $req);
		if(!empty($item)){
			$kolom	= $this->dmastermaterial->get_data_kolom("array", NULL, $item[0]->mtart, $item[0]->classification);
			$item[0]->arr_kolom	= $kolom;
		}
		if ($array) {
			return $item;
		} else {
			echo json_encode($item);
		}
	}
	private function get_uom($array = NULL) {
		$uom 		= $this->dmastermaterial->get_data_uom("open");
		if ($array) {
			return $uom;
		} else {
			echo json_encode($uom);
		}
	}
	private function get_ekgrp($array = NULL) {
		$ekgrp 		= $this->dmastermaterial->get_data_ekgrp("open");
		if ($array) {
			return $ekgrp;
		} else {
			echo json_encode($ekgrp);
		}
	}
	private function get_plant($array = NULL) {
		$plant 		= $this->dmastermaterial->get_data_plant("open");
		if ($array) {
			return $plant;
		} else {
			echo json_encode($plant);
		}
	}
	private function get_lgort($array = NULL, $plant = NULL) {
		$lgort 		= $this->dmastermaterial->get_data_lgort("open", $plant);
		if ($array) {
			return $lgort;
		} else {
			echo json_encode($lgort);
		}
	}
	private function get_dispo($array = NULL) {
		$dispo 		= $this->dmastermaterial->get_data_dispo("open");
		if ($array) {
			return $dispo;
		} else {
			echo json_encode($dispo);
		}
	}
	private function get_lot($array = NULL) {
		$lot 		= $this->dmastermaterial->get_data_lot("open");
		if ($array) {
			return $lot;
		} else {
			echo json_encode($lot);
		}
	}
	private function get_dist($array = NULL) {
		$dist 		= $this->dmastermaterial->get_data_dist("open");
		if ($array) {
			return $dist;
		} else {
			echo json_encode($dist);
		}
	}
	private function get_div($array = NULL) {
		$div 		= $this->dmastermaterial->get_data_div("open");
		if ($array) {
			return $div;
		} else {
			echo json_encode($div);
		}
	}
	private function get_profit($array = NULL) {
		$profit 		= $this->dmastermaterial->get_data_profit("open");
		if ($array) {
			return $profit;
		} else {
			echo json_encode($profit);
		}
	}
	private function get_bklas($array = NULL) {
		$bklas 		= $this->dmastermaterial->get_data_bklas("open");
		if ($array) {
			return $bklas;
		} else {
			echo json_encode($bklas);
		}
	}
	
	private function get_spec($array = NULL, $id_item_spec = NULL, $active = NULL, $deleted = NULL, $description = NULL, $req = NULL) {
		$spec 		= $this->dtransaksimaterial->get_data_spec("open", $id_item_spec, $active, $deleted, $description, $req);
		$spec 		= $this->general->generate_encrypt_json($spec, array("id_item_spec"));
		if(!empty($spec)){
			$plant			= $this->dtransaksimaterial->get_data_plant("array",NULL,NULL, $this->generate->kirana_decrypt($spec[0]->id_item_spec));
			//====commented by syaiful====//
			/*
			$plant_extend	= $this->dmastermaterial->get_data_plant("array",$spec[0]->plant);
			*/
			//====added by syaiful====//
			$plants = array_map(function ($val) {
                return $val->plant;
            }, $plant);
			$plant_extends	= $this->dmastermaterial->get_data_plant("array");
			$plant_extends = array_filter($plant_extends, function($val) use ($plants){
				return !in_array($val->plant, $plants);
			});
			//========================//
			$item			= $this->dmastermaterial->get_data_item("array",NULL,NULL,NULL,NULL, $spec[0]->id_item_group);
			$lgort			= $this->dmastermaterial->get_data_lgort("array",$spec[0]->plant, $spec[0]->lgort);
			$kolom			= $this->dmastermaterial->get_data_kolom("array", NULL, $spec[0]->mtart, $spec[0]->classification);
			$history		= $this->dtransaksimaterial->get_data_history("array",NULL,NULL, $this->generate->kirana_decrypt($spec[0]->id_item_spec));
			//penambahan extends distribution channel
			$vtweg			= $this->dmastermaterial->get_data_dist("array");
			// $vtweg_extend	= $this->dmastermaterial->get_data_dist("array", NULL, $spec[0]->vtweg);
			$spec[0]->plant = implode(",",$plants);
			$spec[0]->arr_plant 		= $plant;
			$spec[0]->arr_plant_extend 	= $plant_extends;
			$spec[0]->arr_item			= $item;
			$spec[0]->arr_lgort			= $lgort;
			$spec[0]->arr_kolom 		= $kolom;
			$spec[0]->arr_history 		= $history;
			//penambahan extends distribution channel
			$spec[0]->arr_vtweg 		= $vtweg;
			// $spec[0]->arr_vtweg_extend 	= $vtweg_extend;
			
		}
		

		if ($array) {
			return $spec;
		} else {
			echo json_encode($spec);
		}
	}
	private function get_request($array = NULL, $id_item_request = NULL, $active = NULL, $deleted = NULL, $all = NULL, $req = NULL, $filter_from = NULL, $filter_to = NULL, $filter_request_status = NULL, $filter_status = NULL, $confirm = NULL) {
		$request	= $this->dtransaksimaterial->get_data_request("open", $id_item_request, $active, $deleted, $all, $req, $filter_from, $filter_to, $filter_request_status, $filter_status, $confirm);
		$request 	= $this->general->generate_encrypt_json($request, array("id_item_request"));
		// $gambar		= $this->get_data_gambar("array",NULL,NULL,NULL,NULL,NULL, $this->generate->kirana_decrypt($request[0]->id_item_request));
		// $request[0]->arr_gambar = $gambar;
		
		if ($array) {
			return $request;
		} else {
			echo json_encode($request);
		}
	}
	private function get_nomor($array = NULL, $id_item_group = NULL, $id_item_name = NULL) {
		$nomor	= $this->dtransaksimaterial->get_data_nomor("open", $id_item_group, $id_item_name);
		$array_terpakai = array();		
		$nomor = $nomor->nomor;
		$checking = $this->cek_sap($nomor, $array_terpakai);
		// echo json_encode($nomor);
		// exit();
		// if($checking && $checking['sts'] == true)
			// $nomor = $checking['nomor'];
		// if ($array) {
			// return $checking;
		// } else {
			// // echo json_encode($nomor);
			// return $checking;
		// }
	}

	private function cek_sap($nomor=NULL, $array_terpakai = NULL) {
		// $this->connectSAP("ERP_310");            //310
		$this->connectSAP("ERP_KMTEMP");
		// $this->connectSAP("ERP");            //prod
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction("Z_RFC_CHECK_MATERIALCODE",
													   array(
															array("IMPORT", "I_MATNR", $nomor),
															array("EXPORT", "E_RETURN", array()),
													   )
			);
			// echo json_encode($result);
			if ($result['E_RETURN']['TYPE'] == 'E') {
				$array_terpakai[] = $nomor;
				$nomor = explode('-',$nomor);
				$no = $nomor[0]."-".$nomor[1]."-".str_pad($nomor[2]+1,4,0,STR_PAD_LEFT);
				$this->cek_sap($no, $array_terpakai);				
				//log rfc gagal
				$datetime     = date("Y-m-d H:i:s");
				$data_row = array(
					'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CHECK_MATERIALCODE',
					'log_code'      => $result['E_RETURN']['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['E_RETURN']['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
				
			}else{
				$result = array(
					"sts" => true,
					"nomor" => $nomor,
					"terpakai" => $array_terpakai
				);
				echo json_encode($result);
				//log rfc berhasil
				$datetime     = date("Y-m-d H:i:s");
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CHECK_MATERIALCODE',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				
			}
			// echo json_encode($result);
		}
	}
	private function get_input($array = NULL, $id_item_request = NULL, $active = NULL, $deleted = NULL, $all = NULL, $req = NULL, $filter_from = NULL, $filter_to = NULL, $filter_request_status = NULL) {
		$otoritas 	= $this->dmastermaterial->get_data_otoritas('open');
		$input		= $this->dtransaksimaterial->get_data_request("open", $id_item_request, 'n', $deleted, 'all', NULL, $filter_from, $filter_to, $filter_request_status, NULL, NULL, $otoritas[0]->id_item_master_pic);
		$input 	= $this->general->generate_encrypt_json($input, array("id_item_request"));
		if ($array) {
			return $input;
		} else {
			echo json_encode($input, JSON_FORCE_OBJECT|JSON_HEX_QUOT);
			// echo json_encode($input);
		}
	}
	
	private function get_acc($array = NULL, $id_item_name = NULL, $active = NULL, $deleted = NULL) {
		$acc	= $this->dmastermaterial->get_data_item("open", $id_item_name, $active, $deleted);
		$acc 	= $this->general->generate_encrypt_json($acc, array("id_item_name"));
		if ($array) {
			return $acc;
		} else {
			echo json_encode($acc);
		}
	}
	
	private function save_acc($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$id_item_name 	= (isset($_POST['id_item_name']) ? $_POST['id_item_name'] : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_item_name']) && $_POST['id_item_name'] != ""){
			$data_row = array(
				"price_control" => $_POST['price_control']
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_item_name", $data_row, array(
				array(
					'kolom' => 'id_item_name',
					'value' => $id_item_name
				)
			));
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
	
	private function save_proc($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$id_item_spec 	= (isset($_POST['id_item_spec']) ? $this->generate->kirana_decrypt($_POST['id_item_spec']) : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_item_spec']) && $_POST['id_item_spec'] != ""){
			if(isset($_POST['purchase_type']) && $_POST['purchase_type'] != ""){
				$data_row = array(
					"purchase_type" => $_POST['purchase_type']
				);
			}
			if(isset($_POST['purchase_authorization']) && $_POST['purchase_authorization'] != ""){
				$data_row = array(
					"purchase_authorization" 	=> $_POST['purchase_authorization']
				);
			}
			if(isset($_POST['beli_di_nsi2']) && $_POST['beli_di_nsi2'] != ""){
				$data_row = array(
					"beli_di_nsi2" 	=> $_POST['beli_di_nsi2']
				);
			}
			if(isset($_POST['specification_check']) && $_POST['specification_check'] != ""){
				$data_row = array(
					"specification_check" 	=> $_POST['specification_check']
				);
			}
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_item_spec", $data_row, array(
				array(
					'kolom' => 'id_item_spec',
					'value' => $id_item_spec
				)
			));
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
	
	private function save_proc_edit($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$id_item_spec 	= (isset($_POST['id_item_spec']) ? $this->generate->kirana_decrypt($_POST['id_item_spec']) : NULL);
		$purchase_type 	= (isset($_POST['purchase_type_edit']) ? $_POST['purchase_type_edit'] : NULL);
		$purchase_authorization 	= (isset($_POST['purchase_authorization_edit']) ? $_POST['purchase_authorization_edit'] : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_item_spec']) && $_POST['id_item_spec'] != ""){
			$data_row = array(
				"purchase_type"		 		=> $purchase_type,
				"purchase_authorization"	=> $purchase_authorization
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_item_spec", $data_row, array(
				array(
					'kolom' => 'id_item_spec',
					'value' => $id_item_spec
				)
			));
			
			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg = "Data berhasil diedit";
				$sts = "OK";
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			
		}
	}
	
	private function save_delete_request($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$id_item_spec 	= (isset($_POST['id_item_spec']) ? $this->generate->kirana_decrypt($_POST['id_item_spec']) : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_item_spec']) && $_POST['id_item_spec'] != ""){
			$data_row = array(
				"req"	=> "d"
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_item_spec", $data_row, array(
				array(
					'kolom' => 'id_item_spec',
					'value' => $id_item_spec
				)
			));
			
			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg = "Data berhasil di hapus.";
				$sts = "OK";
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			
		}

	}
	
	private function save_spec($param) {
		$datetime 					= date("Y-m-d H:i:s");
		$id_item_spec 				= (isset($_POST['id_item_spec']) ? $this->generate->kirana_decrypt($_POST['id_item_spec']) : NULL);
		$id_item_request			= (isset($_POST['id_item_request']) ? $_POST['id_item_request'] : 0);
		$id_item_group				= (isset($_POST['id_item_group']) ? $_POST['id_item_group'] : NULL);
		$msehi_uom					= (isset($_POST['msehi_uom']) ? $_POST['msehi_uom'] : NULL);
		$id_item_name				= (isset($_POST['id_item_name']) ? $_POST['id_item_name'] : NULL);
		$msehi_order				= (isset($_POST['msehi_order']) ? $_POST['msehi_order'] : NULL);
		$code						= (isset($_POST['code']) ? $_POST['code'] : NULL);
		$old_material_number 		= (isset($_POST['old_material_number']) ? $_POST['old_material_number'] : NULL);
		$description				= (isset($_POST['description']) ? strtoupper($_POST['description']) : NULL);
		$ekgrp						= (isset($_POST['ekgrp']) ? $_POST['ekgrp'] : NULL);
		$plant						= (isset($_POST['plant']) ? implode(",", $_POST['plant']) : NULL);
		$availability_check			= (isset($_POST['availability_check']) ? $_POST['availability_check'] : NULL);
		$lgort						= (isset($_POST['lgort']) ? $_POST['lgort'] : NULL);
		$mrp_group					= (isset($_POST['mrp_group']) ? $_POST['mrp_group'] : NULL);
		$service_level				= (isset($_POST['service_level']) ? $_POST['service_level'] : NULL);
		$mrp_type					= (isset($_POST['mrp_type']) ? $_POST['mrp_type'] : NULL);
		$disls						= (isset($_POST['disls']) ? $_POST['disls'] : NULL);
		$dispo						= (isset($_POST['dispo']) ? $_POST['dispo'] : NULL);
		$period_indicator			= (isset($_POST['period_indicator']) ? $_POST['period_indicator'] : NULL);
		// $sales_plant				= (isset($_POST['sales_plant']) ? implode(",", $_POST['sales_plant']) : NULL);
		$sales_plant     			= isset($_POST["sales_plant"]) ? "X" : NULL;
		$gen_item_cat_group			= (isset($_POST['gen_item_cat_group']) ? $_POST['gen_item_cat_group'] : NULL);
		// $vtweg						= (isset($_POST['vtweg']) ? $_POST['vtweg'] : NULL);
		$vtweg						= (isset($_POST['vtweg']) ? implode(",", $_POST['vtweg']) : NULL);
		$material_pricing_group 	= (isset($_POST['material_pricing_group']) ? $_POST['material_pricing_group'] : NULL);
		$spart						= (isset($_POST['spart']) ? $_POST['spart'] : NULL);
		$material_statistic_group 	= (isset($_POST['material_statistic_group']) ? $_POST['material_statistic_group'] : NULL);
		$net_weight					= (isset($_POST['net_weight']) ? $_POST['net_weight'] : NULL);
		$acct_assignment_group 		= (isset($_POST['acct_assignment_group']) ? $_POST['acct_assignment_group'] : NULL);
		$gross_weight				= (isset($_POST['gross_weight']) ? $_POST['gross_weight'] : NULL);
		$taxm1						= (isset($_POST['taxm1']) ? $_POST['taxm1'] : NULL);
		$detail						= (isset($_POST['detail']) ? strtoupper($_POST['detail']) : NULL);
		$xchpf     					= isset($_POST["xchpf"]) ? "X" : NULL;
		//
		$umrez						= (isset($_POST['umrez']) ? $_POST['umrez'] : NULL);
		$prmod						= (isset($_POST['prmod']) ? $_POST['prmod'] : NULL);
		$peran						= (isset($_POST['peran']) ? $_POST['peran'] : NULL);
		$anzpr						= (isset($_POST['anzpr']) ? $_POST['anzpr'] : NULL);
		$kzini						= (isset($_POST['kzini']) ? $_POST['kzini'] : NULL);
		$siggr						= (isset($_POST['siggr']) ? $_POST['siggr'] : NULL);
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$spec 		 = $this->dtransaksimaterial->get_data_spec(NULL, $id_item_spec);
		
		if (count($spec) != 0){
			//jika edit ulang
			if(($id_item_group==null)and($id_item_name==null)){
				$data_row = array(
					"id_item_request" 		=> $id_item_request,
					"msehi_uom"			 	=> $msehi_uom,
					"msehi_order"			=> $msehi_order,
					"code"					=> $code,
					"old_material_number"	=> $old_material_number,
					"description"			=> htmlentities($description),
					"ekgrp"					=> $ekgrp,
					"plant"					=> $plant,
					"availability_check"	=> $availability_check,
					"lgort"					=> $lgort,
					// "gambar"				=> $_POST['gambar'],
					"mrp_group"				=> $mrp_group,
					"service_level"			=> $service_level,
					"mrp_type"				=> $mrp_type,
					"disls"					=> $disls,
					"dispo"					=> $dispo,
					"period_indicator"		=> $period_indicator,
					"sales_plant"			=> $sales_plant,
					"gen_item_cat_group"	=> $gen_item_cat_group,
					"vtweg"					=> $vtweg,
					"material_pricing_group"=> $material_pricing_group,
					"spart"					=> $spart,
					"material_statistic_group"	=> $material_statistic_group,
					"net_weight"			=> $net_weight,
					"acct_assignment_group"	=> $acct_assignment_group,
					"gross_weight"			=> $gross_weight,
					"taxm1"					=> $taxm1,
					"xchpf"					=> $xchpf,
					"detail"				=> htmlentities($detail),
					"detail_sap"			=> $detail,
					"umrez"					=> $umrez,
					"prmod"					=> $prmod,
					"peran"					=> $peran,
					"anzpr"					=> $anzpr,
					"kzini"					=> $kzini,
					"siggr"					=> $siggr,
					"req"					=> 'y'	//set request yes
				);
			}else{
				$data_row = array(
					"id_item_request" 		=> $id_item_request,
					"id_item_group" 		=> $id_item_group,
					"id_item_name"			=> $id_item_name,
					"msehi_uom"			 	=> $msehi_uom,
					"msehi_order"			=> $msehi_order,
					"code"					=> $code,
					"old_material_number"	=> $old_material_number,
					"description"			=> htmlentities($description),
					"ekgrp"					=> $ekgrp,
					"plant"					=> $plant,
					"availability_check"	=> $availability_check,
					"lgort"					=> $lgort,
					// "gambar"				=> $_POST['gambar'],
					"mrp_group"				=> $mrp_group,
					"service_level"			=> $service_level,
					"mrp_type"				=> $mrp_type,
					"disls"					=> $disls,
					"dispo"					=> $dispo,
					"period_indicator"		=> $period_indicator,
					"sales_plant"			=> $sales_plant,
					"gen_item_cat_group"	=> $gen_item_cat_group,
					"vtweg"					=> $vtweg,
					"material_pricing_group"=> $material_pricing_group,
					"spart"					=> $spart,
					"material_statistic_group"	=> $material_statistic_group,
					"net_weight"			=> $net_weight,
					"acct_assignment_group"	=> $acct_assignment_group,
					"gross_weight"			=> $gross_weight,
					"taxm1"					=> $taxm1,
					"xchpf"					=> $xchpf,
					"detail"				=> htmlentities($detail),
					"detail_sap"			=> $detail,
					"umrez"					=> $umrez,
					"prmod"					=> $prmod,
					"peran"					=> $peran,
					"anzpr"					=> $anzpr,
					"kzini"					=> $kzini,
					"siggr"					=> $siggr
				);
			}
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_item_spec", $data_row, array(
				array(
					'kolom' => 'id_item_spec',
					'value' => $id_item_spec
				)
			));
			//input tbl_item_plant(plant dan vtweg)
			$id_item_spec = $id_item_spec;
			$this->dgeneral->delete("tbl_item_plant", array(
				array(
					'kolom' => 'id_item_spec',
					'value' => $id_item_spec
				)
			));
			//jika plant dan vtweg terisi
			if((!empty($_POST['plant']))and(!empty($_POST['vtweg']))){
				foreach ($_POST['plant'] as $plant) {
					foreach ($_POST['vtweg'] as $vtweg) {
						$data_plant = array(
							"id_item_spec" 	=> $id_item_spec,
							"plant" 		=> $plant,
							"vtweg" 		=> $vtweg
						);
						$data_plant = $this->dgeneral->basic_column("insert", $data_plant);
						$this->dgeneral->insert("tbl_item_plant", $data_plant);
					}		
				}		
			}
			//jika plant saja yang terisi
			if(!empty($_POST['plant'])){
				foreach ($_POST['plant'] as $plant) {
					$data_plant = array(
						"id_item_spec" 	=> $id_item_spec,
						"plant" 		=> $plant,
						"vtweg" 		=> ''
					);
					$data_plant = $this->dgeneral->basic_column("insert", $data_plant);
					$this->dgeneral->insert("tbl_item_plant", $data_plant);
				}		
			}
			//gambar baru
			if (isset($_FILES['gambar'])) {
				$jml_file = count($_FILES['gambar']['name']);
				if ($jml_file > 3) {
					$this->dgeneral->rollback_transaction();
					$msg    = "You can only upload maximum 3 files";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|png';
				$newname = array();
				for ($i = 0; $i < $jml_file; $i++) {
					if (isset($_FILES['gambar']) && $_FILES['gambar']['error'][$i] == 0 && $_FILES['gambar']['name'][$i] !== "") {
						array_push($newname, $id_item_spec . "_" . $_POST['id_item_group'] . "_" . str_replace(' ','',$_POST['description']) . "_" . $i);
					}
				}

				if (count($newname) > 0) {
					//delete data images
					$this->dgeneral->delete("tbl_item_gambar", 
												array(
												  array(
													  'kolom' => 'id_item_spec',
													  'value' => $id_item_spec
												  )
												)
					);
					
					$file_img = $this->general->upload_files($_FILES['gambar'], $newname, $config);
					if ($file_img) {
						$data_batch = array();
						foreach ($file_img as $dt) {
							$data_row     = array(
								"id_item_spec"		=> $id_item_spec,
								"file_location"		=> base_url().$dt['url']
							);
							
							$data_row     = $this->dgeneral->basic_column("update", $data_row);
							$data_batch[] = $data_row;
						}
						$this->dgeneral->insert_batch("tbl_item_gambar", $data_batch);
					}
				}
			}
			//gambar baru sampe sini
		}else{
			//validasi diganti dengan key up dari inputan desc
			// $ck_spec 		= $this->dtransaksimaterial->get_data_spec(NULL, NULL, NULL, NULL, $description, NULL, $id_item_name);
			// if (count($ck_spec) != 0){ 
				// $msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				// $sts    = "NotOK";
				// $return = array('sts' => $sts, 'msg' => $msg);
				// echo json_encode($return);
				// exit();
			// }
			
			$data_row = array(
				"id_item_request" 		=> $id_item_request,
				"id_item_group" 		=> $id_item_group,
				"msehi_uom"			 	=> $msehi_uom,
				"id_item_name"			=> $id_item_name,
				"msehi_order"			=> $msehi_order,
				"code"					=> $code,
				"old_material_number"	=> $old_material_number,
				"description"			=> htmlentities($description),
				"ekgrp"					=> $ekgrp,
				"plant"					=> $plant,
				"availability_check"	=> $availability_check,
				"lgort"					=> $lgort,
				// "gambar"				=> $_POST['gambar'],
				"mrp_group"				=> $mrp_group,
				"service_level"			=> $service_level,
				"mrp_type"				=> $mrp_type,
				"disls"					=> $disls,
				"dispo"					=> $dispo,
				"period_indicator"		=> $period_indicator,
				"sales_plant"			=> $sales_plant,
				"gen_item_cat_group"	=> $gen_item_cat_group,
				"vtweg"					=> $vtweg,
				"material_pricing_group"=> $material_pricing_group,
				"spart"					=> $spart,
				"material_statistic_group"	=> $material_statistic_group,
				"net_weight"			=> $net_weight,
				"acct_assignment_group"	=> $acct_assignment_group,
				"gross_weight"			=> $gross_weight,
				"taxm1"					=> $taxm1,
				"xchpf"					=> $xchpf,
				"detail"				=> htmlentities($detail),
				"detail_sap"			=> $detail,
				"umrez"					=> $umrez,
				"prmod"					=> $prmod,
				"peran"					=> $peran,
				"anzpr"					=> $anzpr,
				"kzini"					=> $kzini,
				"siggr"					=> $siggr
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_item_spec", $data_row);
			
			//input tbl_item_plant(plant dan vtweg)
			$id_item_spec = $this->db->insert_id();
			//jika plant dan vtweg terisi
			if((!empty($_POST['plant']))and(!empty($_POST['vtweg']))){
				foreach ($_POST['plant'] as $plant) {
					foreach ($_POST['vtweg'] as $vtweg) {
						$data_plant = array(
							"id_item_spec" 	=> $id_item_spec,
							"plant" 		=> $plant,
							"vtweg" 		=> $vtweg
						);
						$data_plant = $this->dgeneral->basic_column("insert", $data_plant);
						$this->dgeneral->insert("tbl_item_plant", $data_plant);
					}		
				}		
			}
			//jika plant saja yang terisi
			if(!empty($_POST['plant'])){
				foreach ($_POST['plant'] as $plant) {
					$data_plant = array(
						"id_item_spec" 	=> $id_item_spec,
						"plant" 		=> $plant,
						"vtweg" 		=> ''
					);
					$data_plant = $this->dgeneral->basic_column("insert", $data_plant);
					$this->dgeneral->insert("tbl_item_plant", $data_plant);
				}		
			}
			//insert gambar baru
			if (isset($_FILES['gambar'])) {
				$jml_file = count($_FILES['gambar']['name']);
				if ($jml_file > 3) {
					$this->dgeneral->rollback_transaction();
					$msg    = "You can only upload maximum 3 files";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|png';
				$newname = array();
				for ($i = 0; $i < $jml_file; $i++) {
					if (isset($_FILES['gambar']) && $_FILES['gambar']['error'][$i] == 0 && $_FILES['gambar']['name'][$i] !== "") {
						array_push($newname, $id_item_spec . "_" . $_POST['id_item_group'] . "_" . str_replace(' ','',$_POST['description']) . "_" . $i);
					}
				}

				if (count($newname) > 0) {
					$file_img = $this->general->upload_files($_FILES['gambar'], $newname, $config);
					if ($file_img) {
						$data_batch = array();
						foreach ($file_img as $dt) {
							$data_row     = array(
								"id_item_spec"		=> $id_item_spec,
								"file_location"		=> base_url().$dt['url']
							);
							
							$data_row     = $this->dgeneral->basic_column("update", $data_row);
							$data_batch[] = $data_row;
						}
						$this->dgeneral->insert_batch("tbl_item_gambar", $data_batch);
					}
				}
			}
			//insert gambar baru sampe sini
			
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
	//save extend
	private function save_extend($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$id_item_spec 	= (isset($_POST['id_item_spec']) ? $this->generate->kirana_decrypt($_POST['id_item_spec']) : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		//update tbl_item_spec	
		$plants = $_POST['plant'];
		if(isset($_POST['plant_extend'])){
			$plants .= ",";
			$plants .= implode(",", $_POST['plant_extend']);
		}
		$vtwegs = $_POST['vtweg'];
		if(isset($_POST['vtweg_extend'])){
			$vtwegs .= ",";
			$vtwegs .= implode(",", $_POST['vtweg_extend']);
		}
		$data_row = array(
			"plant"		=> $plants,
			"vtweg"		=> $vtwegs
		);
		$data_row = $this->dgeneral->basic_column("update", $data_row);
		$this->dgeneral->update("tbl_item_spec", $data_row, array(
			array(
				'kolom' => 'id_item_spec',
				'value' => $id_item_spec
			)
		));
		//save plant extend
		$arr_plant = explode(',', $plants);
		$arr_vtweg = explode(',', $vtwegs);
		foreach ($arr_plant as $plant) {
			foreach ($arr_vtweg as $vtweg) {
				if(empty(trim($vtweg)))
					$vtweg = NULL;

				$ck_plant 	= $this->dtransaksimaterial->get_data_plant(NULL,NULL, NULL,$id_item_spec,NULL,$plant);
				if (count($ck_plant) == 0){
					$data_extend = array(
						"id_item_spec" 	=> $id_item_spec,
						"plant" 		=> $plant,
						"vtweg" 		=> $vtweg,
						"status_sap" 	=> 'n'
					);
					if($plant!=''){
						$data_extend = $this->dgeneral->basic_column("insert", $data_extend);
						$this->dgeneral->insert("tbl_item_plant", $data_extend);
					}
				}else{
					$data_extends = array(
						"vtweg" 		=> $vtweg
					);

					$data_extends = $this->dgeneral->basic_column("update", $data_extends);
					$this->dgeneral->update("tbl_item_plant", $data_extends, array(
						array(
							'kolom' => 'id_item_spec',
							'value' => $id_item_spec
						),
						array(
							'kolom' => 'plant',
							'value' => $plant
						)
					));
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
	
	// //save change
	// private function save_change($param) {
		// $datetime 		= date("Y-m-d H:i:s");
		// $id_item_spec 	= (isset($_POST['id_item_spec']) ? $this->generate->kirana_decrypt($_POST['id_item_spec']) : NULL);
		// $this->general->connectDbPortal();
		// $this->dgeneral->begin_transaction();
		// //update tbl_item_spec	
		// $data_row = array(
			// "description"	=> $_POST['description'],
			// "detail"		=> $_POST['detail']
		// );
		// $data_row = $this->dgeneral->basic_column("update", $data_row);
		// $this->dgeneral->update("tbl_item_spec", $data_row, array(
			// array(
				// 'kolom' => 'id_item_spec',
				// 'value' => $id_item_spec
			// )
		// ));
		// //input data log
		// $data_log = array(
			// "id_item_spec" 		=> $id_item_spec,
			// "description_old"	=> $_POST['description_awal'],
			// "description_new"	=> $_POST['description']
		// );
		// $data_log = $this->dgeneral->basic_column("insert", $data_log);
		// $this->dgeneral->insert("tbl_item_spec_log", $data_log);
		
		// if ($this->dgeneral->status_transaction() === false) {
			// $this->dgeneral->rollback_transaction();
			// $msg = "Periksa kembali data yang dimasukkan";
			// $sts = "NotOK";
		// } else {
			// $this->dgeneral->commit_transaction();
			// $msg = "Data berhasil ditambahkan";
			// $sts = "OK";
		// }
		// $this->general->closeDb();
		// $return = array('sts' => $sts, 'msg' => $msg);
		// echo json_encode($return);
	// }
	
	private function save_request($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$id_item_request= (isset($_POST['id_item_request']) ? $this->generate->kirana_decrypt($_POST['id_item_request']) : NULL);
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$posisi				= $this->dmastermaterial->get_data_posisi("open", NULL, base64_decode($this->session->userdata("-posst-")));
		$otoritas 			= $this->dmastermaterial->get_data_otoritas(NULL, $posisi[0]->id_posisi);
		// $otoritas 	= $this->dmastermaterial->get_data_otoritas(NULL);
		$request 	= $this->dtransaksimaterial->get_data_request(NULL, $id_item_request);
		if (count($request) != 0){
			$estimate_price = str_replace(",", "", $_POST['estimate_price']);
			$estimate_price = str_replace(".", "", $estimate_price);
			
			$data_row = array(
				"id_item_master_pic"=> $otoritas[0]->id_item_master_pic,
				"type" 				=> $_POST['type'],
				"description" 		=> htmlentities($_POST['description']),
				"uom" 				=> $_POST['uom'],
				"estimate_price" 	=> $estimate_price
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_item_request", $data_row, array(
				array(
					'kolom' => 'id_item_request',
					'value' => $id_item_request
				)
			));
			
			//xx gambar baru
			if (isset($_FILES['gambar'])) {
				$jml_file = count($_FILES['gambar']['name']);
				if ($jml_file > 3) {
					$this->dgeneral->rollback_transaction();
					$msg    = "You can only upload maximum 3 files";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|png';
				$newname = array();
				for ($i = 0; $i < $jml_file; $i++) {
					if (isset($_FILES['gambar']) && $_FILES['gambar']['error'][$i] == 0 && $_FILES['gambar']['name'][$i] !== "") {
						array_push($newname, $id_item_request . "_" . $_POST['type'] . "_" . str_replace(' ','',$_POST['description']) . "_" . $i);
					}
				}

				if (count($newname) > 0) {
					//delete data images
					$this->dgeneral->delete("tbl_item_gambar", 
												array(
												  array(
													  'kolom' => 'id_item_request',
													  'value' => $id_item_request
												  )
												)
					);
					
					$file_img = $this->general->upload_files($_FILES['gambar'], $newname, $config);
					if ($file_img) {
						$data_batch = array();
						foreach ($file_img as $dt) {
							$data_row     = array(
								"id_item_request"	=> $id_item_request,
								"file_location"		=> base_url().$dt['url']
							);
							
							$data_row     = $this->dgeneral->basic_column("update", $data_row);
							$data_batch[] = $data_row;
						}
						$this->dgeneral->insert_batch("tbl_item_gambar", $data_batch);
					}
				}
			}
			//xx gambar baru sampe sini
		}else{
			$estimate_price = str_replace(",", "", $_POST['estimate_price']);
			$estimate_price = str_replace(".", "", $estimate_price);
			
			$data_row = array(
				"id_item_master_pic"=> $otoritas[0]->id_item_master_pic,
				"type" 				=> $_POST['type'],
				"description" 		=> htmlentities($_POST['description']),
				"uom" 				=> $_POST['uom'],
				"estimate_price" 	=> $estimate_price
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_item_request", $data_row);
			$id_item_request = $this->db->insert_id();
			
			//xx gambar baru
			if (isset($_FILES['gambar'])) {
				$jml_file = count($_FILES['gambar']['name']);
				if ($jml_file > 3) {
					$this->dgeneral->rollback_transaction();
					$msg    = "You can only upload maximum 3 files";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|png';
				$newname = array();
				for ($i = 0; $i < $jml_file; $i++) {
					if (isset($_FILES['gambar']) && $_FILES['gambar']['error'][$i] == 0 && $_FILES['gambar']['name'][$i] !== "") {
						array_push($newname, $id_item_request . "_" . $_POST['type'] . "_" . str_replace(' ','',$_POST['description']) . "_" . $i);
					}
				}

				if (count($newname) > 0) {
					$file_img = $this->general->upload_files($_FILES['gambar'], $newname, $config);
					if ($file_img) {
						$data_batch = array();
						foreach ($file_img as $dt) {
							$data_row     = array(
								"id_item_request"	=> $id_item_request,
								"file_location"		=> base_url().$dt['url']
							);
							
							$data_row     = $this->dgeneral->basic_column("update", $data_row);
							$data_batch[] = $data_row;
						}
						$this->dgeneral->insert_batch("tbl_item_gambar", $data_batch);
					}
				}
			}
			//xx gambar baru sampe sini
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
	private function save_request_ho($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		for ($i = 1; $i <= $_POST['count']; $i++) {
			$id_item_request = $this->generate->kirana_decrypt($_POST["id_item_request_$i"]) ;
			$req = isset($_POST["ck_$i"]) ? 'y' : 'o';
            $data_request = array(
                'req' 			=> $req,
                'login_edit' 	=> base64_decode($this->session->userdata("-id_user-")),
                'tanggal_edit'  => $datetime,
				'tanggal_buat'  => $datetime
            );
            $this->dgeneral->update("tbl_item_request", $data_request,
                array(
                    array(
                        'kolom' => 'id_item_request',
                        'value' => $id_item_request
                    ),
					array(
						'kolom' => 'req',
						'value' => 'o'
					)
                )
            );
		}
		
		// $data_row = array(
			// "req" 			=> 'y'
		// );
		// $data_row = $this->dgeneral->basic_column("update", $data_row);
		// $this->dgeneral->update("tbl_item_request", $data_row, array(
			// array(
				// 'kolom' => 'login_buat',
				// 'value' => base64_decode($this->session->userdata("-id_user-"))
			// ),
			// array(
				// 'kolom' => 'req',
				// 'value' => 'o'
			// ),
			// array(
				// 'kolom' => 'na',
				// 'value' => 'n'
			// )
		// ));

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
	private function save_request_pabrik($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		for ($i = 1; $i <= $_POST['count']; $i++) {
			$id_item_request = $this->generate->kirana_decrypt($_POST["id_item_request_$i"]) ;
			$req = isset($_POST["ck_$i"]) ? 'n' : 'y';
            $data_request = array(
                'req' 			=> $req,
                'login_edit' 	=> base64_decode($this->session->userdata("-id_user-")),
                'tanggal_edit'  => $datetime
            );
            $this->dgeneral->update("tbl_item_request", $data_request,
                array(
                    array(
                        'kolom' => 'id_item_request',
                        'value' => $id_item_request
                    ),
					array(
						'kolom' => 'req',
						'value' => 'y'
					)
                )
            );
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
	
	private function save_acc_confirm($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		for ($i = 1; $i <= $_POST['count']; $i++) {
			$id_item_name = $_POST["id_item_name_$i"];
			$req = isset($_POST["ck_$i"]) ? 'n' : 'y';
            $data_request = array(
                'req' 			=> $req,
                'login_edit' 	=> base64_decode($this->session->userdata("-id_user-")),
                'tanggal_edit'  => $datetime
            );
            $this->dgeneral->update("tbl_item_name", $data_request,
                array(
                    array(
                        'kolom' => 'id_item_name',
                        'value' => $id_item_name
                    ),
					array(
						'kolom' => 'req',
						'value' => 'y'
					)
                )
            );
		}
		
		// $data_row = array(
			// "req" 			=> 'n'
		// );
		// $data_row = $this->dgeneral->basic_column("update", $data_row);
		// $this->dgeneral->update("tbl_item_namexxxx", $data_row, array(
			// array(
				// 'kolom' => 'req',
				// 'value' => 'y'
			// ),
			// array(
				// 'kolom' => 'price_control!=',
				// 'value' => ''
			// )
			
		// ));

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
	private function save_proc_confirm($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		for ($i = 1; $i <= $_POST['count']; $i++) {
			$id_item_spec 	= $this->generate->kirana_decrypt($_POST["id_item_spec_$i"]);
			$code 			= isset($_POST["code_$i"])?$_POST["code_$i"]:null;
			$ck				= isset($_POST["ck_$i"])?$_POST["ck_$i"]:null;
			if($ck != null){
				$ck_plant 		= $this->dtransaksimaterial->get_data_plant("open",NULL, NULL,$id_item_spec,'y');
				if (count($ck_plant) != 0){
					$msg    = "Mohon Sync SAP untuk data ".$code;
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}else{
					$req = isset($_POST["ck_$i"]) ? 'n' : 'y';	
				}
				$data_request = array(
					'req' 			=> $req,
					'login_edit' 	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'  => $datetime
				);
				$this->dgeneral->update("tbl_item_spec", $data_request,
					array(
						array(
							'kolom' => 'id_item_spec',
							'value' => $id_item_spec
						),
						array(
							'kolom' => 'req',
							'value' => 'y'
						)
					)
				);
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
	
	private function save_input($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$id_item_request 	= $this->generate->kirana_decrypt($_POST['id_item_request']);
		$id_item_spec 		= $this->generate->kirana_decrypt($_POST['id_item_spec']);
		
		// //update tbl_item_spec
		// $data_row = array(
			// "id_item_request" 	=> $id_item_request
		// );
		// $data_row = $this->dgeneral->basic_column("update", $data_row);
		// $this->dgeneral->update("tbl_item_spec", $data_row, array(
			// array(
				// 'kolom' => 'id_item_spec',
				// 'value' => $id_item_spec
			// )
		// ));
		
		//update tbl_item_request
		$data_row = array(
			"id_item_spec" 	=> $id_item_spec,
			"req" 			=> 'n'
		);
		$data_row = $this->dgeneral->basic_column("update", $data_row);
		$this->dgeneral->update("tbl_item_request", $data_row, array(
			array(
				'kolom' => 'id_item_request',
				'value' => $id_item_request
			)
		));

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
	private function save_inventory($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$id_item_request 	= $this->generate->kirana_decrypt($_POST['id_item_request']);
		$code 				= $_POST['code'];
		$ZDMMSMATNR			= $this->dtransaksimaterial->get_data_ZDMMSMATNR('open', $code);
		$description_sap	= htmlentities($ZDMMSMATNR[0]->MAKTX);
		
		//update tbl_item_request
		$data_row = array(
			"code" 	=> $code,
			"description_sap" 	=> $description_sap,
			"req" 	=> 'n'
		);
		$data_row = $this->dgeneral->basic_column("update", $data_row);
		$this->dgeneral->update("tbl_item_request", $data_row, array(
			array(
				'kolom' => 'id_item_request',
				'value' => $id_item_request
			)
		));

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
	private function get_cek($array = NULL, $tabel = NULL, $field = NULL, $value = NULL, $field2 = NULL, $value2 = NULL) {
		$cek = $this->dtransaksimaterial->get_data_cek("open", $tabel, $field, $value, $field2, $value2);
		$cek = $this->general->generate_encrypt_json($cek,array($field));
		if ($array) {
			return $cek;
		} else {
			echo json_encode($cek);
		}
	}
	private function save_excel_spec($param) {
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
				$objPHPExcel->setActiveSheetIndex(2);
				$data_excel		= $objPHPExcel->getActiveSheet();
				$highestRow 	= $data_excel->getHighestRow(); 
				$highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
				$datetime		= date("Y-m-d H:i:s");
				$data_row		= array();
				for($brs=3; $brs<=$highestRow; $brs++){
					$id_item_group	= $data_excel->getCellByColumnAndRow(2, $brs)->getValue();
					$code_item		= $data_excel->getCellByColumnAndRow(3, $brs)->getValue();
					$ck_item 		= $this->dmastermaterial->get_data_item('open', NULL, NULL, NULL, NULL, $id_item_group,NULL, NULL, NULL, NULL, NULL, NULL, $code_item);
					$code			= $data_excel->getCellByColumnAndRow(4, $brs)->getCalculatedValue();
					$sales_plant	= empty($data_excel->getCellByColumnAndRow(20, $brs)->getValue())?'':'X';
					$detail 		= $ck_item[0]->description.' '.$data_excel->getCellByColumnAndRow(5, $brs)->getValue();
					$data_row		= array(
									'id_item_group'			=> $id_item_group,	//item group
									'id_item_name'			=> $ck_item[0]->id_item_name, //item name
									'code'					=> $code, //material code(spec code)
									'description'			=> $data_excel->getCellByColumnAndRow(5, $brs)->getValue(),	//description
									'plant'	 				=> $data_excel->getCellByColumnAndRow(6, $brs)->getValue(),	//plant
									'lgort'					=> $data_excel->getCellByColumnAndRow(7, $brs)->getValue(),	//Storage Location(sloc)
									'msehi_uom' 			=> $data_excel->getCellByColumnAndRow(8, $brs)->getValue(),	//UOM
									'msehi_order'			=> $data_excel->getCellByColumnAndRow(9, $brs)->getValue(),	//order unit
									'umrez'					=> $data_excel->getCellByColumnAndRow(10, $brs)->getValue(),	//conversion
									'old_material_number'	=> $data_excel->getCellByColumnAndRow(11, $brs)->getValue(),	//old material number
									'ekgrp'					=> $data_excel->getCellByColumnAndRow(12, $brs)->getValue(),	//Purch Group
									'availability_check'	=> $data_excel->getCellByColumnAndRow(13, $brs)->getValue(),	//availability check
									'mrp_group'				=> $data_excel->getCellByColumnAndRow(14, $brs)->getValue(),	//MRP Group
									'mrp_type'				=> $data_excel->getCellByColumnAndRow(15, $brs)->getValue(),	//MRP Type
									'dispo'					=> $data_excel->getCellByColumnAndRow(16, $brs)->getValue(),	//MRP Controller
									'service_level'			=> $data_excel->getCellByColumnAndRow(17, $brs)->getValue(),	//Service Level(%)
									'disls'					=> $data_excel->getCellByColumnAndRow(18, $brs)->getValue(),	//Lot Size
									'period_indicator'		=> $data_excel->getCellByColumnAndRow(19, $brs)->getValue(),	//Period Indicator
									'sales_plant'			=> $sales_plant,	//Sales Org
									'vtweg'					=> $data_excel->getCellByColumnAndRow(21, $brs)->getValue(),	//Distribution Channel
									'spart'					=> $data_excel->getCellByColumnAndRow(22, $brs)->getValue(),	//Division
									'net_weight'			=> $data_excel->getCellByColumnAndRow(23, $brs)->getValue(),	//Net Weight
									'gross_weight'			=> $data_excel->getCellByColumnAndRow(24, $brs)->getValue(),	//Gross Weight
									'xchpf'					=> $data_excel->getCellByColumnAndRow(6, $brs)->getValue(),	//Batch Management
									'gen_item_cat_group'	=> $data_excel->getCellByColumnAndRow(25, $brs)->getValue(),	//Gen Item Cat Group
									'material_pricing_group'=> $data_excel->getCellByColumnAndRow(26, $brs)->getValue(),	//Material Pricing Group
									'material_statistic_group'	=> $data_excel->getCellByColumnAndRow(27, $brs)->getValue(),	//Material Statistic Group
									'acct_assignment_group'	=> $data_excel->getCellByColumnAndRow(28, $brs)->getValue(),	//Acct Assignment Group

									'prmod'					=> $data_excel->getCellByColumnAndRow(30, $brs)->getValue(),	//Forecast Model
									'anzpr'					=> $data_excel->getCellByColumnAndRow(31, $brs)->getValue(),	//Forecast Periods
									'siggr'					=> $data_excel->getCellByColumnAndRow(32, $brs)->getValue(),	//Tracking Limit
									'peran'					=> $data_excel->getCellByColumnAndRow(33, $brs)->getValue(),	//History Periods
									'kzini'					=> $data_excel->getCellByColumnAndRow(34, $brs)->getValue(),	//Initialization
									'taxm1'					=> $data_excel->getCellByColumnAndRow(35, $brs)->getValue(),	//Tax Class
									'purchase_type'			=> $data_excel->getCellByColumnAndRow(40, $brs)->getValue(),	//Purch Type
									'purchase_authorization'=> $data_excel->getCellByColumnAndRow(41, $brs)->getValue(),	//Purch Auth
									// 'detail'				=> $detail,	//Detail
									"detail"				=> htmlentities($detail),
									"detail_sap"			=> $detail,
									'login_buat' 			=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'			=> $datetime,
									'login_edit' 			=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit' 			=> $datetime,
									'req' 					=> 'y',
									'na' 					=> 'n',
									'del'					=> 'n'
								);	
					$ck_spec 	= $this->dtransaksimaterial->get_data_spec(NULL, NULL, NULL, NULL, NULL, NULL, NULL, $code);
					if(count($ck_spec) != 0){
						unset($data_row['id_item_group']);
						unset($data_row['code']);
						unset($data_row['login_buat']);
						unset($data_row['tanggal_buat']);
						$id_item_spec = $ck_spec[0]->id_item_spec;
						$this->dgeneral->update('tbl_item_spec', $data_row, array(
								array(
									'kolom'=>'id_item_spec',
									'value'=>$id_item_spec
								)
						));
						//input tbl_item_plant(plant dan vtweg)
						$this->dgeneral->delete("tbl_item_plant", array(
							array(
								'kolom' => 'id_item_spec',
								'value' => $id_item_spec
							)
						));
						$plant		= $data_excel->getCellByColumnAndRow(6, $brs)->getValue();
						$vtweg		= $data_excel->getCellByColumnAndRow(21, $brs)->getValue();
						if(!empty($vtweg)){
							$arr_plant 	= explode(',', $plant);
							$arr_vtweg 	= explode(',', $vtweg);
							foreach ($arr_plant as $data_plant) {
								foreach ($arr_vtweg as $data_vtweg) {
									$data_plant = array(
										"id_item_spec" 	=> $id_item_spec,
										"plant" 		=> $data_plant,
										"vtweg" 		=> $data_vtweg
									);
									$data_plant = $this->dgeneral->basic_column("insert", $data_plant);
									$this->dgeneral->insert("tbl_item_plant", $data_plant);
								}		
							}						
						}else{
							$arr_plant 	= explode(',', $plant);
							$arr_vtweg 	= explode(',', $vtweg);
							foreach ($arr_plant as $data_plant) {
								$data_plant = array(
									"id_item_spec" 	=> $id_item_spec,
									"plant" 		=> $data_plant
								);
								$data_plant = $this->dgeneral->basic_column("insert", $data_plant);
								$this->dgeneral->insert("tbl_item_plant", $data_plant);
							}						
						}
					}else{
						//input tbl_item_spec
						$this->dgeneral->insert('tbl_item_spec', $data_row);
						
						//input tbl_item_plant(plant dan vtweg)
						$id_item_spec 	= $this->db->insert_id();
						$plant			= $data_excel->getCellByColumnAndRow(6, $brs)->getValue();
						$vtweg			= $data_excel->getCellByColumnAndRow(21, $brs)->getValue();
						if(!empty($vtweg)){
							$arr_plant 	= explode(',', $plant);
							$arr_vtweg 	= explode(',', $vtweg);
							foreach ($arr_plant as $data_plant) {
								foreach ($arr_vtweg as $data_vtweg) {
									$data_plant = array(
										"id_item_spec" 	=> $id_item_spec,
										"plant" 		=> $data_plant,
										"vtweg" 		=> $data_vtweg
									);
									$data_plant = $this->dgeneral->basic_column("insert", $data_plant);
									$this->dgeneral->insert("tbl_item_plant", $data_plant);
								}		
							}						
						}else{
							$arr_plant 	= explode(',', $plant);
							$arr_vtweg 	= explode(',', $vtweg);
							foreach ($arr_plant as $data_plant) {
								$data_plant = array(
									"id_item_spec" 	=> $id_item_spec,
									"plant" 		=> $data_plant
								);
								$data_plant = $this->dgeneral->basic_column("insert", $data_plant);
								$this->dgeneral->insert("tbl_item_plant", $data_plant);
							}						
						}
						
					}
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

	private function save_tolak($param) {
		$datetime 		= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$id_item_request 	= $this->generate->kirana_decrypt($_POST['id_item_request']);
		$alasan 			= isset($_POST["alasan"])?htmlentities($_POST["alasan"]):null;
		
		//update data(tolak)
		$data_row = array(
			"alasan" 	=> $alasan,
			"req" 		=> 'x'
		);
		$data_row = $this->dgeneral->basic_column("update", $data_row);
		$this->dgeneral->update("tbl_item_request", $data_row, array(
			array(
				'kolom' => 'id_item_request',
				'value' => $id_item_request 
			)
		));

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
	
	// EXTEND TAB SALES
	private function save_sales($param) {
		$this->connectSAP("ERP_310");

		$dts = $this->dtransaksimaterial->get_extend_sales(array(
				"connect" 		=> TRUE,
				"app"   		=> 'scrap',
				"id_item_spec"	=> $this->generate->kirana_decrypt($_POST['id_item_spec'])
		));
		
		$VTWEG = '05';
		$MTPOS_MARA = 'ZLEI';
		$KNDM = '02';
		$VERSG = '1';
		
		foreach($dts as $dt){
			$KTGRM = ($dt->classification == 'A') ? '08' : '07';
			$LGORT = ($dt->lgort == '' || $dt->lgort == null) ? '' : $dt->lgort;
			if($dt->pabrik !== 'KMTR'){
				$table_sales[] = array(
					"MANDT"   		=> '310', //SAP SERVER
					"INFNU"   		=> '0000000000', // Interface Number
					"MATNR"   		=> $dt->code, // Kode Material
					"WERKS"   		=> $dt->pabrik, // plant
					"LGORT"   		=> $LGORT, // Location storage tbl_item_spec.lgort
					"VTWEG"   		=> $VTWEG, // Distribution Channel tbl_item_spec.vtweg
					"CLASS"   		=> '', // Material Classification
					"MTPOS_MARA"   	=> $MTPOS_MARA, // General item category group tbl_item_spec.gen_item_cat_group
					"KONDM"   		=> $KNDM, // Material Pricing Group tbl_item_spec.material_pricing_group
					"DWERK"   		=> $dt->pabrik, // Delivering Plant
					"VERSG"   		=> $VERSG, // Statistic Group tbl_item_spec.material_statistic_group
					"KTGRM"   		=> $KTGRM, // Account Assignment Group tbl_item_spec.acct_assignment_group
					"TAKLM"   		=> '1', // Account Assignment Group tbl_item_spec.acct_assignment_group
					
				);
			}
		}

		// echo json_encode($table_sales);
		// exit();
	
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$datetime = date("Y-m-d H:i:s");

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			
			
			
			$param = array(				
				array("IMPORT", "I_FLAG", 'Y'),
				array("TABLE", "T_SALES", $table_sales),
				array("TABLE", "T_RETURN", array()),
			);		


			$result = $this->data['sap']->callFunction("Z_RFC_CREATEMATERIAL", $param);


			if ($this->data['sap']->getStatus() == SAPRFC_OK && !empty($result["T_RETURN"])) {
				$type    = array();
				$message = array();
				foreach ($result["T_RETURN"] as $return) {
					$type[]    = $return['TYPE'];
					$message[] = $return['MESSAGE'];
				}


				if (in_array('E', $type) === true) {
					$key = array_search('E', $type);
					$data_row_log = array(
						'app'           => 'DATA RFC Kode Material',
						'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
						'log_code'      => 'E',
						'log_status'    => 'Gagal',
						'log_desc'      => "Extend Tab Sales Failed: " . $message[$key],
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);
				}
				else {
					$data_row_log = array(
						'app'           => 'DATA RFC Kode Material',
						'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
						'log_code'      => $type[0],
						'log_status'    => 'Berhasil',
						'log_desc'      => $message[0],
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);

					$data_spec = array(
						"sales_plant"				=> 'X', // toggle true
						"gen_item_cat_group"		=> $MTPOS_MARA,
						"vtweg"						=> $VTWEG,
						"material_pricing_group"	=> $KNDM,
						"material_statistic_group"	=> $VERSG,
						"acct_assignment_group"		=> $KTGRM,
						"taxm1"						=> '1',
						"availability_check"		=> '02'
					);
					$data_spec = $this->dgeneral->basic_column("update", $data_spec);
					$this->dgeneral->update("tbl_item_spec", $data_spec, array(
						array(
							'kolom' => 'id_item_spec',
							'value' => $this->generate->kirana_decrypt($_POST['id_item_spec'])
						)
					));


				}

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

				//================================SAVE ALL================================//
				// if ($this->dgeneral->status_transaction() === false) {
				// 	$this->dgeneral->rollback_transaction();
				// 	$this->general->closeDb();
				// 	$msg = "Periksa kembali data yang dimasukkan";
				// 	$sts = "NotOK";

				// 	$return = array('sts' => $sts, 'msg' => $msg);
				// 	echo json_encode($return);
				// 	exit();
				// }
				// else {
				// 	$this->dgeneral->commit_transaction();
				// 	$this->general->closeDb();
				// 	$msg = $data_row_log['log_desc'];
				// 	$sts = "OK";
				// 	if (in_array('E', $type) === true)
				// 		$sts = "NotOK";

				// 	if ($sts == "NotOK") {
				// 		$return = array('sts' => $sts, 'msg' => $msg);
				// 		echo json_encode($return);
				// 		exit();
				// 	}
				// 	else {
				// 		$return = array('sts' => $sts, 'msg' => $msg);
				// 		echo json_encode($return);
				// 	}
				// }
			}
			else {
				$data_row_log = array(
					'app'           => 'DATA RFC Kode Material',
					'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
					'log_code'      => isset($result["T_RETURN"]["TYPE"]),
					'log_status'    => 'Gagal',
					'log_desc'      => "Extend Tab Sales Failed: " . isset($result["T_RETURN"]["MESSAGE"]),
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		}
		else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC Kode Material',
				'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
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
			$msg = $data_row_log['log_desc'];
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = $data_row_log['log_desc'];
			$sts = $data_row_log['log_code'] == 'E' ? "NotOK" : "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	/*====================================================================*/
		
}