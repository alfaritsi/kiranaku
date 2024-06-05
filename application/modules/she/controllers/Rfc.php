<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : SHE 
@author     : Syah Jadianto (8604)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Rfc extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmaster');
	    $this->load->model('dmastershe');
	    $this->load->model('dtransactionshe');
	}

	public function post_beritaacara(){
		$beritaacara = $_POST['id'];
		$beritaacara = str_replace('-', '/', $beritaacara);

		// $data = parse_ini_file("//var/www/html/uat/kiranaku/application/config/koneksi.ini");
		// $data = parse_ini_file("D:/koneksi.ini");
		// $data = $this->config->load('koneksi.ini');
		// $data = parse_ini_file('koneksi.ini');
		// echo json_encode($data);exit();

		$datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();

		$rfc_query = $this->dtransactionshe->push_ba_saprfc($beritaacara);


		$this->load->library('sap');
		// $sap = new SAPConnection();
		
		$koneksi = parse_ini_file(FILE_KONEKSI_SAP,true);

		$data = $koneksi['SHE'];
		
		// $constr = array("logindata"=>array(
	 //            "ASHOST"	=> "10.0.0.209"		// application server
	 //            ,"SYSNR"	=> "12"				// system number
	 //            ,"CLIENT" 	=> "310"			// client
	 //            ,"USER"		=> "yanto"			// user
	 //            ,"PASSWD"	=> "08des2007"		// password
	 //            )
	 //            ,"show_errors"=>false			// let class printout errors
	 //            ,"debug"=>false) ; 				// detailed debugging information

		$constr = array("logindata"=>array(
	            "ASHOST"	=> $data['ASHOST']		// application server
	            ,"SYSNR"	=> $data['SYSNR']				// system number
	            ,"CLIENT" 	=> $data['CLIENT']			// client
	            ,"USER"		=> $data['USER']			// user
	            ,"PASSWD"	=> $data['PASSWD']		// password
	            )
	            ,"show_errors"=>false			// let class printout errors
	            ,"debug"=>false) ; 				// detailed debugging information

		$sap = new saprfc($constr);

		// echo $sap->getStatus();
		// exit();

		$result=$sap->callFunction("Z_RFC_INBOUND_INTERFACE",
			array(	
				array("IMPORT","I_INFTY",'1'), //get SHE
				array("TABLE","T_DATAPO",$rfc_query),
				array("TABLE","T_RETURN",array()),
			)
		); 

		// $result=$sap->callFunction("Z_RFC_INBOUND_INTERFACE",
		// 	array(	
		// 		array("IMPORT","I_INFTY",'1'), //get SHE
		// 		array("TABLE","T_DATAPO",array(array('POSNR' => '1',
		// 										'LIFNR' => '0000240000', 
		// 										'MENGE' => 2,
		// 										'MEINS' => 'KG',
		// 										'EKORG' => 'ABL1',
		// 										'BANUM' => 'BA-0001',
		// 										'TXT01' => 'TRUK | B 87687 SJ | JONO'
		// 									))
		// 								),
		// 		array("TABLE","T_RETURN",array()),
		// 	)
		// ); 

    	$this->dgeneral->begin_transaction();

		if ($sap->getStatus() == SAPRFC_OK) {

			if(!empty($result['T_RETURN'])){
				// echo $result['T_RETURN'][1]['TYPE']." - ".$result['T_RETURN'][1]['MESSAGE'];

	            $data_row   = array(
	                              'app'     			=> 'SHE',
	                              'rfc_name'    		=> 'Z_RFC_INBOUND_INTERFACE',
	                              'log_code'    		=> $result['T_RETURN'][1]['TYPE'],
	                              'log_status'    		=> 'Gagal',
	                              'log_desc'    		=> $beritaacara." ".$result['T_RETURN'][1]['MESSAGE'],
	                              'executed_by'    		=> base64_decode($this->session->userdata("-id_user-")),
	                              'executed_date'      	=> $datetime
	                            );
            	$this->dgeneral->insert('tbl_log_rfc', $data_row);

		    	if($this->dgeneral->status_transaction() === FALSE){
		            $this->dgeneral->rollback_transaction();
		            $msg    = $result['T_RETURN'][1]['MESSAGE'].", & Gagal insert data log RFC";
		            $sts    = "NotOK";
		        }else{
		            $this->dgeneral->commit_transaction();
		            $msg    = "Gagal, ".$result['T_RETURN'][1]['MESSAGE']." !";
		            $sts    = "NotOK";
		        }	
		        goto finish;

			}else{
				// echo "SUCCESS";
	            $data_row   = array(
	                              'app'     			=> 'SHE',
	                              'rfc_name'    		=> 'Z_RFC_INBOUND_INTERFACE',
	                              'log_code'    		=> '',
	                              'log_status'    		=> 'Berhasil',
	                              'log_desc'    		=> $beritaacara." Berhasil ditransfer",
	                              'executed_by'    		=> base64_decode($this->session->userdata("-id_user-")),
	                              'executed_date'      	=> $datetime
	                            );
            	$this->dgeneral->insert('tbl_log_rfc', $data_row);

            	// Update status berita acara di SQL
				$datetime = date("Y-m-d H:i:s");
		        $data_row   = array(
		                          'transfer_ba_sap'    		=> 'y',
		                          'tanggal_transfer_sap'    => $datetime,
		                          'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
		                          'tanggal_edit'      		=> $datetime
		                     );
		        $this->dgeneral->update('tbl_she_tr_b3_limbah', $data_row, array( 
		                                                                    array(
		                                                                        'kolom'=>'no_berita_acara',
		                                                                        'value'=>$beritaacara
		                                                                    )
		                                                                ));

		    	if($this->dgeneral->status_transaction() === FALSE){
		            $this->dgeneral->rollback_transaction();
		            $msg    = "Gagal insert data log RFC !";
		            $sts    = "NotOK";
		        }else{
		            $this->dgeneral->commit_transaction();
		            $msg    = "Berita acara berhasil ditransfer";	
		            $sts    = "OK";
		        }	
		        goto finish;
			}

		}else {
			// echo "KONEKSI GAGAL"."<br/>";
			// $sap->printStatus();

            $data_row   = array(
                              'app'     			=> 'SHE',
                              'rfc_name'    		=> 'Z_RFC_INBOUND_INTERFACE',
                              'log_code'    		=> 'E',
                              'log_status'    		=> 'Gagal',
                              'log_desc'    		=> $beritaacara." RFC gagal",
                              'executed_by'    		=> base64_decode($this->session->userdata("-id_user-")),
                              'executed_date'      	=> $datetime
                            );
        	$this->dgeneral->insert('tbl_log_rfc', $data_row);

	    	if($this->dgeneral->status_transaction() === FALSE){
	            $this->dgeneral->rollback_transaction();
	            $msg    = "Gagal insert data log RFC !";
	            $sts    = "NotOK";
	        }else{
	            $this->dgeneral->commit_transaction();
	            $msg    = "RFC gagal !";	
	            $sts    = "NotOK";
	        }	
	        goto finish;

		}
		// echo json_encode($result['T_RETURN']);

		finish:{
			$sap->logoff();
			$return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
	    }

	}


}