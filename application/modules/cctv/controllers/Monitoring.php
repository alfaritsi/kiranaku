<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	/*
		@application  : Monitoring CCTV 
		@author       : Airiza Yuddha (7849)
		@contributor  :
			  	1. <airiza yuddha> (7849) 07.02.2019
				 change title report monitoring
			  	2. <airiza yuddha> (7849) 05.03.2019
				 - change save_monitoring add try catch if error upload
				 - edit get_monitoring_report_achv add $last_day for if statement get week  
				3. <airiza yuddha> (7849) 01.04.2019
				 - edit get_monitoring_report_achv add if getweek
				4. <airiza yuddha> (7849) 29.04.2019
				 - edit report uptime - 
				5. <airiza yuddha> (7849) 10.06.2019
				 - edit form input - 
				6. <airiza yuddha> (7849) 21.11.2020
				 - edit save monitoring
			  	7. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
    */

	//include_once APPPATH . "modules/umb/controllers/BaseControllers.php";

	Class Monitoring extends MX_Controller {
		// private $data;
		function __construct() {
			parent::__construct();
			$this->load->model('dmonitoringcctv');
			$this->load->model('dmastercctv');
			/*load model*/
			$this->load->model('dgeneral');

			// $this->load->model('she/dreportshe');
			
		}

		public function index(){
			show_404();
		}

		/*================================Monitoring CCTV====================================*/
		public function data() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "CCTV";
			$data['title']      	= "Data Monitoring CCTV";
			$data['title_form'] 	= "Setting Data Monitoring CCTV";

			/*load global attribute*/
			$data['generate']   	= $this->generate;
			$data['module']     	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();

			$data['dot']    		= $this->dmastercctv->get_master_dot('array', NULL, NULL, 'n');
			$data['cdot']    		= count($data['dot']);
			// $data['dot_fieldname'] 	= $this->get_dot_fieldname($data['dot']);
			$datanext_sat 			= $this->get_week_month_year(date("Y-m-d"));
			$data_this 				= explode('-', $datanext_sat);
			$data['week']			= $this->get_week(date("Y-m-d", strtotime($datanext_sat)) );
			$data['month']			= $data_this[1];
			$data['month2']			= date("M",strtotime($datanext_sat));
			$data['year']			= $data_this[0];
			// $data['week']			= $this->get_week(date("Y-m-d"));
			// $data['month']			= date("m");
			// $data['month2']			= date("M");
			// $data['year']			= date("Y");

			
			// $thismonth 		= $data_this[1];
			// $thisyear 		= $data_this[0];
			// $thisweek 		= $this->get_week(date("Y-m-d", strtotime($datanext_sat)) );
			
			//get oto
			$ho						= base64_decode($this->session->userdata("-ho-"));
			if($ho=='n'){
        		$pabrik = base64_decode($this->session->userdata("-gsber-"));
        		if($pabrik == "KJP1" || $pabrik == "KJP2")
					$pabrik = array("KJP1","KJP2");
        	} else {
        		$pabrik = NULL;
        	}        	
			$data['plant'] 			= $this->dgeneral->get_master_plant($pabrik);
			
			// echo json_encode($this->session->userdata());
			// $data['lokasiparent'] 	= $this->dmastercctv->get_master_sublokasi("portal", NULL, NULL, NULL, "pabrik","tran","tbl_inv_sub_lokasi.nama");
			// $data['lokasiparentid'] = $this->dmastercctv->get_master_sublokasi("portal", NULL, NULL, NULL, "pabrik","tran","tbl_inv_sub_lokasi.id_sub_lokasi");
			// $data['monitoring'] 	= $this->get_monitoring('array',$id_monitoring = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $exceptioncheck = NULL,$monitoringcheck1 = NULL, $monitoringcheck2 = NULL, $monitoringcheck3 = NULL, $monitoringcheck4 = NULL, $monitoringcheck5 = NULL,$monitoringtypedetail = NULL, $filtertahun = NULL, $pabrik);
			// $data['dot'] = $this->dmastercctv->get_master_dot();
			// $dot = $this->general->generate_encrypt_json($dot, "id_mdot");
			

			$this->load->view("monitoring/page", $data);
		}

		/*================================Monitoring CCTV====================================*/
		public function report() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "CCTV";
			$data['title']      	= "Uptime CCTV Pabrik (Update Weekly)";
			
			/*load global attribute*/
			$data['generate']   	= $this->generate;
			$data['module']     	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();

			//get oto
			$ho						= base64_decode($this->session->userdata("-ho-"));
			if($ho=='n'){
        		$pabrik 			= base64_decode($this->session->userdata("-gsber-"));
        	} else {
        		$pabrik 			= NULL;
        	}        	
			$data['plant'] 			= $this->dgeneral->get_master_plant($pabrik);
			// $data['monitoring'] 	= $this->get_monitoring('array',$id_monitoring = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $exceptioncheck = NULL,$monitoringcheck1 = NULL, $monitoringcheck2 = NULL, $monitoringcheck3 = NULL, $monitoringcheck4 = NULL, $monitoringcheck5 = NULL,$monitoringtypedetail = NULL, $filtertahun = NULL, $pabrik);
			$this->load->view("monitoring/report", $data);
		}

		public function detail() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "CCTV";
			$data['title']      	= "Laporan Detail Monitoring CCTV";
			
			/*load global attribute*/
			$data['generate']   	= $this->generate;
			$data['module']     	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			
			//get oto
			$ho						= base64_decode($this->session->userdata("-ho-"));
			if($ho=='n'){
        		$pabrik = base64_decode($this->session->userdata("-gsber-"));
        	} else {
        		$pabrik = NULL;
        	}        	
			$data['plant'] 			= $this->dgeneral->get_master_plant($pabrik);
			
			// $data['monitoring'] 	= $this->get_monitoring('array',$id_monitoring = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $exceptioncheck = NULL,$monitoringcheck1 = NULL, $monitoringcheck2 = NULL, $monitoringcheck3 = NULL, $monitoringcheck4 = NULL, $monitoringcheck5 = NULL,$monitoringtypedetail = NULL, $filtertahun = NULL, $pabrik);
			// $data['dot'] = $this->dmastercctv->get_master_dot();
			// $dot = $this->general->generate_encrypt_json($dot, "id_mdot");
			

			$this->load->view("monitoring/detail", $data);
		}

		

		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//

		//get week 
		public function get_week($date){
			//  $tgl=explode('-',$date); //explode untuk pemisah kata,  variable $date dengan batas - ke array
			//     $bln=$tgl[1]; //mengambil array $tgl[1] yang isinya 03
			//     $thn=$tgl[0]; //mengambil array $tgl[0] yang isinya 2015
			//     $ref_date=strtotime( "$date" ); //strtotime ini mengubah varchar menjadi format time
			//     $week_of_year=date( 'W', $ref_date ); //mengetahui minggu ke berapa dari tahun
			//     $week_of_month=$week_of_year - date( 'W', strtotime( "$bln/1/$thn" ) ) + 1; //mengetahui minggu ke berapa dari bulan 
			// return $week_of_month;

				$day = date('D', strtotime($date));
				if($day == 'Sat'){					
					$first_monday = $date;
				} else {
					//get prev senin 
					$first_monday = date('Y-m-d',strtotime( "next saturday" , strtotime($date) ));	
				}
				// $result = $first_monday;
				$result 	= $this->dmonitoringcctv->get_week('dashboarddev',$first_monday); //edit sap
				if(isset($result->WKMNT) && $result->WKMNT != null )
					return $result->WKMNT;
				else 
					return 0;

		}

		//get week 
		public function get_week_month_year($date){
			$day = date('D', strtotime($date));
			if($day == 'Sat'){					
				$first_sat = $date;
			} else {
				//get prev senin 
				$first_sat = date('Y-m-d',strtotime( "next saturday" , strtotime($date) ));	
			}
			// $result = $first_monday;
			$result 	= $this->dmonitoringcctv->get_week('dashboarddev',$first_sat); //edit sap
			if(isset($result->WKMNT) && $result->WKMNT != null )
				return $result->ACTDT;
			else 
				return 0;
		}

		public function get_week_all($monthyear){			
			$explodevar = explode(".", $monthyear);
			$month 		= $explodevar[0];
			$year 		= $explodevar[1];
			// $result = $first_monday;
			$result 	= $this->dmonitoringcctv->get_week('dashboarddev',NULL, 'WKMNT', $month, $year);
			return $result;
		}
		
		public function get($param = NULL) {
			//get oto
			$ho						= base64_decode($this->session->userdata("-ho-"));
			if($ho=='n'){
        		$pabrik = base64_decode($this->session->userdata("-gsber-"));
        	} else {
        		$pabrik = NULL;
        	}    
			switch ($param) {
				
				
				case 'monitoring':
					$id_monitoring 	= (isset($_POST['id_monitoring']) ? $this->generate->kirana_decrypt($_POST['id_monitoring']) : NULL);
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_monitoring(NULL, $id_monitoring, $active, $deleted, $typecheck = NULL, $exceptioncheck = NULL,$monitoringcheck1 = NULL, $monitoringcheck2 = NULL, $monitoringcheck3 = NULL, $monitoringcheck4 = NULL, $monitoringcheck5 = NULL,$monitoringtypedetail = NULL, $filtertahun = NULL, $pabrik);
					break;
				case 'data':
					$id_monitoring 	= (isset($_POST['id_monitoring']) ? $this->generate->kirana_decrypt($_POST['id_monitoring']) : NULL);
					$tahun 			= (!empty($_POST['tahun']) ? $_POST['tahun'] : NULL);
					$bulan 			= (!empty($_POST['bulan']) ? $_POST['bulan'] : NULL);					
					$pabrik2 		= (!empty($_POST['pabrik']) ? $_POST['pabrik'] : $pabrik);
					// $pabrik2 		= (isset($_POST['plant']) ? $_POST['plant'] : $pabrik);
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$monthMin1    	= (isset($_POST['monthMin1']) ? $_POST['monthMin1'] : NULL);
					$this->get_monitoring(NULL, $id_monitoring, $active, $deleted, $typecheck = NULL, $exceptioncheck = NULL, $monitoringcheck1 = NULL, $monitoringcheck2 = NULL,$monitoringcheck3 = NULL,$monitoringcheck4 = NULL, $monitoringcheck5 = NULL, $monitoringtypedetail = 'all', $tahun, $pabrik2, $bulan,$monthMin1);
					break;
				case 'detail':
					$id_monitoring 	= (isset($_POST['id_monitoring']) ? $this->generate->kirana_decrypt($_POST['id_monitoring']) : NULL);
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_monitoring(NULL, $id_monitoring, $active, $deleted, $typecheck = NULL, $exceptioncheck = NULL,$monitoringcheck1 = NULL, $monitoringcheck2 = NULL, $monitoringcheck3 = NULL, $monitoringcheck4 = NULL, $monitoringcheck5 = NULL,$monitoringtypedetail = NULL, $filtertahun = NULL, $pabrik2);
					break;
				case 'dataedit':
					$data_edit 		= (isset($_POST['dataedit']) ? explode("|", $_POST['dataedit']) : NULL);
					$typecheck		= "in";
					$year 			= $data_edit[3];
					$plant 			= $data_edit[2];
					$week 			= $data_edit[1];
					$month 			= $data_edit[0];
					
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_monitoring(NULL, NULL, $active, $deleted,'in', NULL, $plant, $week, $month, $year, NULL, 'all', $filtertahun = NULL, $pabrik);
					break;
				case 'datasum':
					$data_edit 		= (isset($_POST['dataedit']) ? explode("|", $_POST['dataedit']) : NULL);
					$typecheck		= "in";
					$year 			= (isset($_POST['year']) ? $_POST['year'] : NULL);
					$plant 			= (isset($_POST['plant']) ? $_POST['plant'] : NULL);
					$week 			= (isset($_POST['week']) ? $_POST['week'] : NULL);
					$month 			= (isset($_POST['month']) ? $_POST['month'] : NULL);
					$pabrik2 		= (isset($_POST['plant']) ? $_POST['plant'] : $pabrik);
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_monitoring_sum(NULL, NULL, $active, $deleted,'in', NULL, $plant, $week, $month, $year, NULL, 'sum', $filtertahun = NULL, $pabrik2);
					break;
				case 'sublokasi':
					$data_pabrik 	= (isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
					
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					// $this->get_monitoring(NULL, NULL, $active, $deleted,'in', NULL, $plant, $week, $month, $year, NULL, 'all');
					$sublok 		= $this->dmastercctv->get_master_sublokasi("portal", NULL, NULL, NULL, "pabrik","tran","tbl_inv_sub_lokasi.nama",$data_pabrik);
					echo json_encode($sublok);
					break;
				case 'lokasi':
					$data_pabrik 	= (isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
					
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$lokasi 		= $this->dmastercctv->get_master_sublokasi("portal", NULL, NULL, NULL, "pabrik","tran","tbl_inv_sub_lokasi.id_sub_lokasi",$data_pabrik);
					echo json_encode($lokasi);
					break;
				case 'tabdot':
					$data_pabrik 	= (isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
					
					$active     	= 'n';
					$deleted    	= 'n';
					// $lokasi 		= $this->dmastercctv->get_master_sublokasi("portal", NULL, NULL, NULL, "pabrik","tran","tbl_inv_sub_lokasi.id_sub_lokasi",$data_pabrik);
					$dot 			= $this->dmastercctv->get_master_dot('array', NULL, $active, $deleted,$typecheck = NULL, $exceptioncheck = NULL, $dotcheck1 = NULL, $dotcheck2 = NULL, $dotcheck3 = NULL, $data_pabrik);
					echo json_encode($dot);
					break;
				case 'week':
					$data_date 		= (isset($_POST['dateinput']) ? $_POST['dateinput'] : date("Y-m-d"));
					
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					// $lokasi 		= $this->dmastercctv->get_master_sublokasi("portal", NULL, NULL, NULL, "pabrik","tran","tbl_inv_sub_lokasi.id_sub_lokasi",$data_pabrik);
					$week 			= $this->get_week($data_date);;
					echo json_encode($week);
					break;
				case 'listweek':
					$data_date 		= (isset($_POST['dateinput']) ? $_POST['dateinput'] : date("m.Y"));
					// echo json_encode($data_date)."-----------";
					$week 			= $this->get_week_all($data_date);;
					echo json_encode($week);
					break;				
				case 'data_report_achv':
					$id_monitoring 	= (isset($_POST['id_monitoring']) ? $this->generate->kirana_decrypt($_POST['id_monitoring']) : NULL);
					$tahun 			= (!empty($_POST['tahun']) ? $_POST['tahun'] : NULL);
					$bulan 			= (!empty($_POST['bulan']) ? $_POST['bulan'] : NULL);					
					$pabrik2 		= (!empty($_POST['pabrik']) ? $_POST['pabrik'] : $pabrik);
					// $pabrik2 		= (isset($_POST['plant']) ? $_POST['plant'] : $pabrik);
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$distinct_plant = 'plant';
					$this->get_monitoring_report_achv(NULL, $tahun, $distinct_plant);
					break;
				case 'data_achv':
					$tahun_sync 		= (isset($_POST['tahun_sync']) ? $_POST['tahun_sync'] : null);

					// echo json_encode($data_date)."-----------";
					$achv 			= $this->dmonitoringcctv->get_data_achv('array',NULL,NULL,$tahun_sync);
					
					echo json_encode($achv);
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
			} else if (isset($_POST['type']) && $_POST['type'] == "delete") {
				$action = "delete_del";
			}

			if ($action) {
				switch ($param) {
					
					
					case 'data':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_cctv_monitoring", array(
							array(
								'kolom' => 'id_monitoring',
								'value' => $this->generate->kirana_decrypt($_POST['kode'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'delete_temp':
						$this->general->connectDbPortal();
						$data 		= $_POST['id'];
						$data_exp 	= $data != "" && isset($data) ? explode("|", $data) : 0;  
						if($data_exp == 0){
							$return = array('sts' => 'NotOK', 'msg' => 'Data tidak ditemukan');	
						} else {
							// "6|3|ABL1|2020"
							$plant 	= $data_exp[2];
							$tahun 	= $data_exp[3];
							$bulan 	= $data_exp[0];
							$minggu	= $data_exp[1];
							$delete = $this->dmonitoringcctv->delete_data_cctv("portal",$plant,$tahun,$bulan,$minggu);
							if($delete == "success"){
								$msg = "Data berhasil dihapus";
								$sts = "OK";
								$return = array('sts' => $sts, 'msg' => $msg);
							}
						}
						
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
				
				
				case 'data':
					$this->save_monitoring();
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

		/*================================DOT CCTV====================================*/
		

		/*================================DOT CCTV====================================*/
		private function get_monitoring($array = NULL, $id_monitoring = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL, $exceptioncheck = NULL, $monitoringcheck1 = NULL, $monitoringcheck2 = NULL,$monitoringcheck3 = NULL,$monitoringcheck4 = NULL, $monitoringcheck5 = NULL, $monitoringtypedetail = NULL, $filtertahun= NULL, $filterpabrik = NULL, $filterbulan= NULL,$monthMin1=NULL,$display=NULL) {

			$monitoring = $this->dmonitoringcctv->get_data_monitoring("open", $id_monitoring, $active, $deleted, $typecheck, $exceptioncheck, $monitoringcheck1, $monitoringcheck2, $monitoringcheck3, $monitoringcheck4, 
				$monitoringcheck5, 'all', $filtertahun, $filterpabrik, $filterbulan ,$monthMin1);

			foreach ($monitoring as $i => $dt) {
				$dt->attch = base_url().'assets/file/cctv/'.$dt->attch;
				$monitoring[$i] = $dt;
			}
			// var_dump($monitoring);
			// get dot
			$dot = $this->dmastercctv->get_master_dot("open", NULL, $active, $deleted, NULL, NULL, NULL, NULL, NULL, $filterpabrik);
			// $monitoring = $this->general->generate_encrypt_json($monitoring, "id_monitoring");
			$data_pabrik = $this->dmonitoringcctv->get_data_monitoring("open", $id_monitoring, $active, $deleted, $typecheck, $exceptioncheck, $monitoringcheck1, $monitoringcheck2, $monitoringcheck3, $monitoringcheck4, $monitoringcheck5, NULL, $monitoringtypedetail = NULL, $filtertahun, $filterpabrik = NULL, $filterbulan, $monthMin1);
			$monitoring = $this->general->generate_encrypt_json($monitoring, array("id_monitoring"));

			
			if ($array || $display == "none") {
				return $monitoring;
			} else {

				echo json_encode(array('var1' => $monitoring, 'var2' => $data_pabrik, 'var3' => $dot));
			}
		}

		private function get_monitoring_trans($array = NULL, $id_monitoring = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL, $exceptioncheck = NULL, $monitoringcheck1 = NULL, $monitoringcheck2 = NULL,$monitoringcheck3 = NULL,$monitoringcheck4 = NULL, $monitoringcheck5 = NULL, $monitoringtypedetail = NULL, $filtertahun= NULL, $filterpabrik = NULL, $filterbulan= NULL,$monthMin1=NULL,$display=NULL) {

			$monitoring = $this->dmonitoringcctv->get_data_monitoring($array, $id_monitoring, $active, $deleted, $typecheck, $exceptioncheck, $monitoringcheck1, $monitoringcheck2, $monitoringcheck3, $monitoringcheck4, 
				$monitoringcheck5, 'all', $filtertahun, $filterpabrik, $filterbulan ,$monthMin1);

			foreach ($monitoring as $i => $dt) {
				$dt->attch = base_url().'assets/file/cctv/'.$dt->attch;
				$monitoring[$i] = $dt;
			}
			$monitoring = $this->general->generate_encrypt_json($monitoring, array("id_monitoring"));
			return $monitoring;
			
		}

		private function get_monitoring_sum($array = NULL, $id_monitoring = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL, $exceptioncheck = NULL, $monitoringcheck1 = NULL, $monitoringcheck2 = NULL,$monitoringcheck3 = NULL,$monitoringcheck4 = NULL, $monitoringcheck5 = NULL, $monitoringtypedetail = NULL, $filtertahun= NULL, $filterpabrik = NULL) {
			$monitoring = $this->dmonitoringcctv->get_data_monitoring("open", $id_monitoring, $active, $deleted, $typecheck, $exceptioncheck, $monitoringcheck1, $monitoringcheck2, $monitoringcheck3, $monitoringcheck4, $monitoringcheck5, $monitoringtypedetail, $filtertahun, $filterpabrik );
			
			if ($array) {
				return $monitoring;
			} else {

				echo json_encode(array('var1' => $monitoring,'year' => $monitoringcheck4, 'month' => $monitoringcheck3,'plant' => $filterpabrik));
			}
		}
		
		private function save_monitoring() {
			ob_get_contents();  ob_end_clean();
			if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST) &&      
				empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
				$displayMaxSize = ini_get('post_max_size');    
				switch(substr($displayMaxSize,-1))   {     
					case 'G':       $displayMaxSize = $displayMaxSize * 1024;     
					case 'M':       $displayMaxSize = $displayMaxSize * 1024;     
					case 'K':        $displayMaxSize = $displayMaxSize * 1024;   
				}    
					$error = 'Posted data is too large '.$_SERVER['CONTENT_LENGTH'].'. bytes exceeds the maximum size of '.$displayMaxSize.' bytes.';


			$return = array('sts' => 'notOK', 'msg' => $error);
			echo json_encode($return);
			exit();
			}
			set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
			    // error was suppressed with the @-operator
			    if (0 === error_reporting()) {
			        return false;
			    }

			    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
			}, E_ALL);

			try{
				$datetime = date("Y-m-d H:i:s");
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				$array_insert 	= [];
				$pabrik 		= ($_POST['pabrik']!="") ? $_POST['pabrik']:0;
				//edit
				if (isset($_POST['id_hide']) && trim($_POST['id_hide']) !== "" && $_POST['id_hide'] != 0 ) {
					// echo "xxx"; 
					$jumlahlokasi 		= isset($_POST['hidden_file_count']) ? $_POST['hidden_file_count'] : 0 ;
					$lokasi_explode		= isset($_POST['dot_hidden']) ? explode(",", $_POST['dot_hidden']) : 0 ;
					$dataedit_explode 	= $_POST['id_hide'] != null ? explode("|", $_POST['id_hide']) : 0 ;

					for($i=0; $i<$jumlahlokasi; $i++){
						$lokasi 		= ($lokasi_explode[$i]!='') ? explode("|", $lokasi_explode[$i]):0;  
						$condition_val 	= isset($_POST['condition_fieldname'.$lokasi[0]]) ? "ON" : "OFF";
						$keterangan_val	= ($_POST['keterangan_fieldname'.$lokasi[0]] != null && $condition_val == "OFF") ? 
							$_POST['keterangan_fieldname'.$lokasi[0]] : "";
						
						$w=0;
						$monitoring = $this->get_monitoring_trans(NULL, NULL, NULL, 'n', 'in',NULL, $dataedit_explode[2], $dataedit_explode[1], $dataedit_explode[0], $dataedit_explode[3], $lokasi[0],NULL,NULL,NULL,NULL,NULL,"none");
						// ABL1,3 week, 1 bulan, 2019 tahun
						// var_dump(count($monitoring));
						if (count($monitoring) > 0) {
							 $w++;
							foreach ($monitoring as $dt) {
								
								$last_id 	= $this->generate->kirana_decrypt($dt->id_monitoring);
								$filetemp 	= 'attch'.$lokasi[0];	
								$datex      = date("Ymd")."_".date("His");
								$nm_file 	= "";
								$files      = array();
								//edit if upload file exist
								if($_FILES[$filetemp]['name'][0] != ""){
									//cek count file
									if (count($_FILES[$filetemp]['name'][0]) > 1) {
									  $msg    = "You can only upload maximum 1 file";
									  $sts    = "NotOK";
									  $return = array('sts' => $sts, 'msg' => $msg);
									  echo json_encode($return);
									  exit();
									}

			                    	$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
									$config['allowed_types'] = 'jpg|jpeg|png';
				                	$nm_file 	= array($last_id."_".$lokasi[0]."_".$datex);
				                	$files  	= $this->general->upload_files($_FILES[$filetemp], $nm_file, $config);  	              
							            
							        

			                	} 
			                 	// else {
			                 		// $data_row = array(						
									// 	"condition" 		=> $condition_val,
									// 	"note_monitoring" 	=> $keterangan_val,				
									// 	"login_edit"    	=> base64_decode($this->session->userdata("-id_user-")),
									// 	"tanggal_edit"  	=> $datetime
									// );
									// $data_row = $this->dgeneral->basic_column("update", $data_row);
									// $this->dgeneral->update("tbl_cctv_monitoring", $data_row, array(
									// 	array(
									// 		'kolom' 		=> 'id_monitoring',
									// 		'value' 		=> $last_id
									// 	)	

									// ));
			                 	// }


				                		// validasi for keterangan
										if($condition_val == "OFF" && ($keterangan_val == "" || $keterangan_val == " ") ){
											$msg    = "Silahkan isi keterangan ketika kondisi cctv dalam keadaan OFF pada titik lokasi ".$lokasi[1];
											$sts    = "NotOK";
											$return = array('sts' => $sts, 'msg' => $msg);
											echo json_encode($return);
											exit();	
										}
				                	
				                	
							            $data_row   = array(
							            				"condition" 		=> $condition_val,
														"note_monitoring" 	=> $keterangan_val,	                                 
					                                    'login_edit'    	=> base64_decode($this->session->userdata("-nik-")),
					                                    'tanggal_edit'  	=> $datetime	                                    
					                                );
							            // echo json_encode($data_row);
							            if(count($files) > 0){
							            	$data_row['attch'] = $files[0]['file_name'];
							            }

						                $this->dgeneral->update("tbl_cctv_monitoring", $data_row, array(
											array(
												'kolom' 		=> 'id_monitoring',
												'value' 		=> $last_id
											)	
										)); 
							}
						} else if(count($monitoring) <= 0) {

							// $lokasi_explode	= isset($_POST['dot_hidden']) ? explode(",", $_POST['dot_hidden']) : 0 ;
							// $pabrik 		= ($_POST['pabrik']!="") ? $_POST['pabrik']:0;
							// for($n=0; $n<$jumlahlokasi; $n++){
								 
								
								// $lokasi 		= ($lokasi_explode[$i]!='') ? explode("|", $lokasi_explode[$i]):0;  
								$condition_val 	= isset($_POST['condition_fieldname'.$lokasi[0]]) ? "ON" : "OFF";
								$keterangan_val	= ($_POST['keterangan_fieldname'.$lokasi[0]] != null && $condition_val=="OFF") ? $_POST['keterangan_fieldname'.$lokasi[0]] : "";

								$monitor_desc 	= "CCTV/".$pabrik."/".$lokasi[0]."/".$condition_val."/".$_POST['week_hidden']."/".$_POST['month_hidden']."/".$_POST['year_hidden'];
								// validasi for keterangan
								if($condition_val == "OFF" && ($keterangan_val == "" || $keterangan_val == " ") ){
									$msg    = "Silahkan isi keterangan ketika kondisi cctv dalam keadaan OFF pada titik lokasi ".$lokasi[1];
									$sts    = "NotOK";
									$return = array('sts' => $sts, 'msg' => $msg);
									echo json_encode($return);
									exit();	
								}
								
								$data_row = array(
									"monitoring" 		=> $monitor_desc,
									"plant" 			=> $pabrik,
									"id_mdot" 			=> $lokasi[0],
									"dot" 				=> $lokasi[1],
									"week" 				=> $_POST['week_hidden'],
									"month" 			=> $_POST['month_hidden'],
									"year" 				=> $_POST['year_hidden'],
									"condition" 		=> $condition_val,
									"note_monitoring" 	=> $keterangan_val,
									"login_buat"    	=> base64_decode($this->session->userdata("-nik-")),
									"tanggal_buat"  	=> $datetime,
									"login_edit"    	=> base64_decode($this->session->userdata("-nik-")),
									"tanggal_edit"  	=> $datetime
								);


								$this->dgeneral->insert("tbl_cctv_monitoring", $data_row);
								//get last id 
								$last_id 	= $this->db->insert_id();
								$filetemp 	= 'attch'.$lokasi[0];
								if($_FILES[$filetemp]['name'][0] != ""){
									$array_insert[] = $last_id.'~'.$filetemp.'~'.$lokasi[0]; 
								}

								// upload file when insert new
								for($n=0; $n<count($array_insert); $n++){
									// echo json_encode($array_insert[$n]);
									$explode_data = explode("~",$array_insert[$n] );
									$last_id 	= $explode_data[0];
									$filetemp 	= $explode_data[1];
									$lokasi 	= $explode_data[2];
									$datex      = date("Ymd")."_".date("His");
									$nm_file 	= "";

									if($_FILES[$filetemp]['name'][0] != ""){
										//cek count file
										if (count($_FILES[$filetemp]['name'][0]) > 1) {
										  $msg    = "You can only upload maximum 1 file";
										  $sts    = "NotOK";
										  $return = array('sts' => $sts, 'msg' => $msg);
										  echo json_encode($return);
										  exit();
										}

				                    	$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
										$config['allowed_types'] = 'jpg|jpeg|png';
										$config['max_size'] = 20480;
					                	$nm_file 	= array($last_id."_".$lokasi."_".$datex);
					                	$upload_error = $this->general->check_upload_file($filetemp, true);
					                	if(empty($upload_error)){
					                		$files  	= $this->general->upload_files($_FILES[$filetemp], $nm_file, $config);
					                	} else {
					                		$files  	= "";
					                		$upload_error = $this->upload->display_errors('', '');
					                	}

					                	if($last_id !== ""){
								            $data_row   = array(	                                    
						                                    'attch'                 => $files[0]['file_name'],
						                                    'login_edit'            => base64_decode($this->session->userdata("-nik-")),
						                                    'tanggal_edit'          => $datetime	                                    
						                                );
								                $this->dgeneral->update("tbl_cctv_monitoring", $data_row, array(
													array(
														'kolom' 		=> 'id_monitoring',
														'value' 		=> $last_id
													)	
												));   	              
								            
								        }else{
								            $msg    = "Periksa kembali data yang dimasukkan";
								            $sts    = "NotOK";
								                $filestring = PUBPATH."assets/file/cctv/".$files[0]['file_name'];
								                unlink($filestring);
								            				            
								        }

				                	}
								}
								
							// }
						}
						//get last id 
						


					}
					// exit();
				// 	$this->dgeneral->commit_transaction();
				// exit();	
					$this->dmonitoringcctv->get_data_achv(NULL,'triger',$pabrik,$_POST['year_hidden'],$_POST['month_hidden'],$_POST['week_hidden'], base64_decode($this->session->userdata("-nik-")) );
								
				// insert
				} else {
					$monitoring = $this->get_monitoring_trans(NULL, NULL, NULL, 'n', 'in',NULL, $_POST['pabrik'], $_POST['week_hidden'], $_POST['month_hidden'], $_POST['year_hidden'],NULL,NULL,NULL,NULL,NULL,NULL,"none");
					if (count($monitoring) > 0) {
						$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$jumlahlokasi 	= isset($_POST['hidden_file_count']) ? $_POST['hidden_file_count'] : 0 ;
					$lokasi_explode	= isset($_POST['dot_hidden']) ? explode(",", $_POST['dot_hidden']) : 0 ;
					$pabrik 		= ($_POST['pabrik']!="") ? $_POST['pabrik']:0;
					for($i=0; $i<$jumlahlokasi; $i++){
						 
						
						$lokasi 		= ($lokasi_explode[$i]!='') ? explode("|", $lokasi_explode[$i]):0;  
						$condition_val 	= isset($_POST['condition_fieldname'.$lokasi[0]]) ? "ON" : "OFF";
						$keterangan_val	= ($_POST['keterangan_fieldname'.$lokasi[0]] != null && $condition_val=="OFF") ? $_POST['keterangan_fieldname'.$lokasi[0]] : "";

						$monitor_desc 	= "CCTV/".$pabrik."/".$lokasi[0]."/".$condition_val."/".$_POST['week_hidden']."/".$_POST['month_hidden']."/".$_POST['year_hidden'];
						// validasi for keterangan
						if($condition_val == "OFF" && ($keterangan_val == "" || $keterangan_val == " ") ){
							$msg    = "Silahkan isi keterangan ketika kondisi cctv dalam keadaan OFF pada titik lokasi ".$lokasi[1];
							$sts    = "NotOK";
							$return = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();	
						}
						$data_row = array(
							"monitoring" 		=> $monitor_desc,
							"plant" 			=> $pabrik,
							"id_mdot" 			=> $lokasi[0],
							"dot" 				=> $lokasi[1],
							"week" 				=> $_POST['week_hidden'],
							"month" 			=> $_POST['month_hidden'],
							"year" 				=> $_POST['year_hidden'],
							"condition" 		=> $condition_val,
							"note_monitoring" 	=> $keterangan_val,
							"login_buat"    	=> base64_decode($this->session->userdata("-nik-")),
							"tanggal_buat"  	=> $datetime,
							"login_edit"    	=> base64_decode($this->session->userdata("-nik-")),
							"tanggal_edit"  	=> $datetime
						);


						$this->dgeneral->insert("tbl_cctv_monitoring", $data_row);
						//get last id 
						$last_id 	= $this->db->insert_id();
						$filetemp 	= 'attch'.$lokasi[0];
						if($_FILES[$filetemp]['name'][0] != ""){
							$array_insert[] = $last_id.'~'.$filetemp.'~'.$lokasi[0]; 
						}
						
					}

					// echo json_encode($array_insert);
					for($n=0; $n<count($array_insert); $n++){
						// echo json_encode($array_insert[$n]);
						$explode_data = explode("~",$array_insert[$n] );
						$last_id 	= $explode_data[0];
						$filetemp 	= $explode_data[1];
						$lokasi 	= $explode_data[2];
						$datex      = date("Ymd")."_".date("His");
						$nm_file 	= "";

						if($_FILES[$filetemp]['name'][0] != ""){
							//cek count file
							if (count($_FILES[$filetemp]['name'][0]) > 1) {
							  $msg    = "You can only upload maximum 1 file";
							  $sts    = "NotOK";
							  $return = array('sts' => $sts, 'msg' => $msg);
							  echo json_encode($return);
							  exit();
							}

	                    	$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
							$config['allowed_types'] = 'jpg|jpeg|png';
							$config['max_size'] = 20480;
		                	$nm_file 	= array($last_id."_".$lokasi."_".$datex);
		                	$upload_error = $this->general->check_upload_file($filetemp, true);
		                	if(empty($upload_error)){
		                		$files  	= $this->general->upload_files($_FILES[$filetemp], $nm_file, $config);
		                	} else {
		                		$files  	= "";
		                		$upload_error = $this->upload->display_errors('', '');
		                	}

		                	if($last_id !== ""){
					            $data_row   = array(	                                    
			                                    'attch'                 => $files[0]['file_name'],
			                                    'login_edit'            => base64_decode($this->session->userdata("-nik-")),
			                                    'tanggal_edit'          => $datetime	                                    
			                                );
					                $this->dgeneral->update("tbl_cctv_monitoring", $data_row, array(
										array(
											'kolom' 		=> 'id_monitoring',
											'value' 		=> $last_id
										)	
									));   	              
					            
					        }else{
					            $msg    = "Periksa kembali data yang dimasukkan";
					            $sts    = "NotOK";
					                $filestring = PUBPATH."assets/file/cctv/".$files[0]['file_name'];
					                unlink($filestring);
					            				            
					        }

	                	}
					}

					$this->dmonitoringcctv->get_data_achv(NULL,'triger',$pabrik,$_POST['year_hidden'],$_POST['month_hidden'],$_POST['week_hidden'],base64_decode($this->session->userdata("-nik-")) );
					
					
				}
				// $this->dgeneral->commit_transaction();
				// $this->dgeneral->rollback_transaction();
				
				// echo json_encode($upload_error);
				// echo $this->dgeneral->status_transaction();
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else if (isset($upload_error)) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					// $this->dgeneral->rollback_transaction();
					$this->dgeneral->commit_transaction();
					$msg = "Data berhasil disimpan";
					$sts = "OK";
				}
			} catch (ErrorException $e) {
				// echo "--------------------";
				// echo $displayMaxSize = ini_get('post_max_size');
				// echo "|".$_FILES[size];
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan ". $e->getMessage();
				$sts = "NotOK";
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}
		
		/*====================================================================*/

		private function get_monitoring_report_achv($array = NULL, $tahun = NULL, $distinct_plant = NULL) {
			$distinct_plant = 'plant';
			$distinct_month = 'month';
			$plant 			= $this->dmastercctv->get_pabrik_oto("y");
			$data_thn 		= array();
			$data_bln 		= array();
			$data_all 		= array();
			$array_plant 	= array();
			foreach($plant as $p){
				$bulan 		= $this->dmonitoringcctv->get_data_monitoring_achv("open", $tahun, $distinct_month,null, $p->plant );
				// echo json_encode($bulan);
				$array_plant = array();
				foreach($bulan as $bl){
					// echo $bl->month;
					$monitoring  	= $this->dmonitoringcctv->get_data_monitoring_achv("open", $tahun, null, null, $p->plant,$bl->month);
					$array_plant['trans'][$bl->month] = $monitoring;
					$sum_bulan 		= 0;
					$pembagi 		= 0;
					$pembagi_final 	= 0; 
					// $thismonth 		= date('m');
					// $thisyear 		= date('Y');
					// $datanext_sat 	= $this->get_week_month_year(date("Y-m-d"));
					// $data_this 		= explode('-', $datanext_sat);
					// $thismonth 		= $data_this[1];
					// $thisyear 		= $data_this[0];
					// $thisweek 		= $data_this[2];
					// $thisweek 		= $this->get_week(date("Y-m-d"));
					$datanext_sat 	= $this->get_week_month_year(date("Y-m-d"));
					$data_this 		= explode('-', $datanext_sat);
					$thismonth 		= $data_this[1];
					$thisyear 		= $data_this[0];
					$thisweek 		= $this->get_week(date("Y-m-d", strtotime($datanext_sat)) );

					foreach($monitoring as $mon){
						$sum_bulan 	= $sum_bulan + $mon->persen;
						$satOrWeek 	= 4; // cek pembagi max_week / jumlah sabtu
						$a_date 	= $mon->year."-".$mon->month."-1";
						$last_date 	= date("Y-m-t", strtotime($a_date));
						$last_day 	= date('D',strtotime($last_date));

						//	4 <= 5 && 31 != sat maret
						//  4 == 4 && 28 != sat feb
						// 	5 >= 4 && 31 != sat Jan
						
						//2018  
						// 5 > 4 && LAST != SAT D JAN = 5-1 = 4
						// 5 > 4 && LAST != SAT D FEB = 5-1 = 4
						// 5 = 5 && LAST == SAT E MAR = MAX	= 5
						// 4 > 4 && LAST != SAT F E APR = 
						// 5 > 4 && LAST != SAT D MAY = 
						// 4 < 5 && LAST == SAT A JUN = 
						// 4 = 4 && LAST != SAT F E JUL = 4
						// 5 > 4 && LAST != SAT D AUG = 5-1 = 4
						// 4 < 5 && LAST != SAT B SEP = 
						// 5 > 4 && LAST != SAT D OCT = 5-1 = 4
						// 5 > 4 && LAST != SAT D NOV = 5-1 = 4
						// 4 < 5 && LAST != SAT B DES = 
						// 4 = 4 && LAST != SAT   FEB 2019 = 3
						// 4 = 4 && LAST != SAT

						// check max week <= count sat && last day == sat
						if($mon->max_week < $mon->saturday && $last_day == 'Sat'){ // A 
							$satOrWeek = $mon->max_week;
						// check max week <= count sat && last day != sat
						} else if($mon->max_week < $mon->saturday && $last_day != 'Sat') { // B
							$satOrWeek = $mon->saturday -1 ;
						} else if($mon->max_week > $mon->saturday && $last_day == 'Sat') { // C
							$satOrWeek = $mon->saturday ;
						} else if($mon->max_week > $mon->saturday && $last_day != 'Sat') { // D
							$satOrWeek = $mon->max_week -1 ;
						} else if($mon->max_week == $mon->saturday) { // E
							// $satOrWeek = $mon->max_week ;
							// $satOrWeek = $mon->countmax_tran ;
							$satOrWeek = $mon->maxtran ;
						} else {
							$satOrWeek = $mon->saturday;
						}
						// else if($mon->max_week == $mon->saturday && $last_day != 'Sat') { // F
						// 	$satOrWeek = $mon->max_week -1 ;
						// }
						// else {
						// 	$satOrWeek = $mon->saturday;
						// }
						
						if($mon->month == $thismonth && $mon->year == $thisyear){
							if($thisweek < $satOrWeek && $thisweek <> 1){
	                          $pembagi_final = $thisweek -1;
	                        } elseif($thisweek < $satOrWeek && $thisweek == 1) {
	                          $pembagi_final = 1;
	                        } else {
	                          $pembagi_final = $satOrWeek;
	                        }
                        } else {$pembagi_final = $satOrWeek;}
					}
					 // echo $pembagi_final." ";
					$sum_bulan = $sum_bulan/$pembagi_final;
					$array_plant['sum'][$bl->month] = $sum_bulan;
					
					
					$data_thn[$p->plant] = $array_plant;
				} //echo "\n";
				
			}
			$data[$tahun] 		= $data_thn;
			echo json_encode($data);
			
		}

	}

?>
