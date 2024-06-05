<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	include_once APPPATH . "modules/pica/controllers/BaseControllers.php";
	/*
		@application  : PICA 
		@author       : Airiza Yuddha (7849)
		@contributor  :
			  1. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
    */

	//include_once APPPATH . "modules/umb/controllers/BaseControllers.php";

	Class Trans extends BaseControllers {
		// private $data;
		function __construct() {
			parent::__construct();
			
        	$this->load->library('lpica');
			$this->load->model('dmasterpica');
			$this->load->model('dtranspica');
			/*load model*/
			$this->load->model('dgeneral');
		}

		public function index(){
			show_404();
		}

		/*================================ Trans create pica ====================================*/
		public function data() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "PICA";
			$data['title']      	= "List Data PICA";
			$data['title_form'] 	= "Input Data PICA";

			/*load global attribute*/
			$data['generate']   	= $this->generate;
			$data['module']     	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();

			//get oto
			$ho						= base64_decode($this->session->userdata("-ho-"));
			if($ho=='n'){
        		$pabrik = base64_decode($this->session->userdata("-id_gedung-")).",";
        		$pabrik = explode(",", $pabrik);
        	} else {
        		$pabrik = NULL;
        	}    
        	
			$data['plant'] 			= $this->dmasterpica->get_master_plant($pabrik);
			$data['jenis_report']   = $this->dmasterpica->get_data_pica_jenisreport_normal('portal',NULL, 'only active');
			$data['temuan']       	= $this->dmasterpica->get_data_pica_temuan_normal('portal',NULL, 'only active');
			$data['buyer'] 			= $this->dmasterpica->get_data_pica_buyer('portal',NULL, 'only active');
									
			$this->load->view("transaksi/pica", $data);
		}

		public function input($param = NULL,$param2 = NULL) {
			switch ($param) {
				
				case 'pica': // jenis temuan master
					//===============================================/
					$data['module'] 		= "PICA";
					$data['title']      	= "Input Data Pica";
					$data['title_form'] 	= "Input Data Pica";

					/*load global attribute*/
					$data['generate']     	= $this->generate;
					$data['module']       	= $this->router->fetch_module();
					$data['user']       	= $this->general->get_data_user();
					$data['temuan']       	= $this->dmasterpica->get_data_pica_temuan_normal('portal',NULL, 'only active');
					$data['buyer'] 			= $this->dmasterpica->get_data_pica_buyer('portal',NULL, 'only active');
					$data['posisi']       	= $this->dmasterpica->get_data_pica_posisi('portal', NULL, 'only active');
					$data['kategori']		= $this->dmasterpica->get_data_pica_kategori('portal',NULL,'only active');
					
					$this->load->view("transaksi/pica_input", $data);
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		public function pica_data_karyawan($status=NULL, $posisi=NULL, $pabrik=NULL){
			$data = $this->dtranspica->get_data_karyawan(NULL, NULL, $pabrik);
			return $data;
		}

		public function approval() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "PICA";
			$data['title']      	= "List Data PICA";
			$data['title_form'] 	= "Data PICA";

			/*load global attribute*/
			$data['generate']   	= $this->generate;
			$data['module']     	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();

			//get oto
			$ho						= base64_decode($this->session->userdata("-ho-"));
			if($ho=='n'){
        		$pabrik = base64_decode($this->session->userdata("-id_gedung-"));
        	} else {
        		$pabrik = NULL;
        	}        	
			$data['plant'] 			= $this->dmasterpica->get_master_plant($pabrik);
			
			$this->load->view("transaksi/approval", $data);
		}

		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//
		
		public function get($param = NULL) {
			
			switch ($param) {
				
				case 'data'				:
					$id_monitoring 	= (isset($_POST['id_monitoring']) ? $this->generate->kirana_decrypt($_POST['id_monitoring']) : NULL);
					$tahun 			= (!empty($_POST['tahun']) ? $_POST['tahun'] : NULL);
					$bulan 			= (!empty($_POST['bulan']) ? $_POST['bulan'] : NULL);					
					$pabrik2 		= (!empty($_POST['pabrik']) ? $_POST['pabrik'] : $pabrik);
					// $pabrik2 		= (isset($_POST['plant']) ? $_POST['plant'] : $pabrik);
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_monitoring(NULL, $id_monitoring, $active, $deleted, $typecheck = NULL, $exceptioncheck = NULL, $monitoringcheck1 = NULL, $monitoringcheck2 = NULL,$monitoringcheck3 = NULL,$monitoringcheck4 = NULL, $monitoringcheck5 = NULL, $monitoringtypedetail = 'all', $tahun, $pabrik2, $bulan);
					break;

				case 'data_pica_normal'	:
					$id_pica 		= (isset($_POST['id_pica']) ? $this->generate->kirana_decrypt($_POST['id_pica']) : NULL);
					$tahun 			= (!empty($_POST['tahun']) ? $_POST['tahun'] : NULL);
					$bulan 			= (!empty($_POST['bulan']) ? $_POST['bulan'] : NULL);					
					$pabrik 		= (!empty($_POST['pabrik']) ? $_POST['pabrik'] : NULL);
					
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$order    		= (isset($_POST['order']) ? $_POST['order'] : NULL);
					$this->pica_normal(NULL, $id_pica, $active, $deleted,$tahun,$bulan,$pabrik,$order);
					break;
				
				case 'pica_normal'		: // Template report master -- templatereport_normal
					$jenis_temuan 		= (!empty($_POST['jenis_temuan']))?$_POST['jenis_temuan']:0;
					if($jenis_temuan != 0){
						$datatemuan 	= explode("|", $jenis_temuan);
						$id_temuan 		= $datatemuan[0]; 	$nama_temuan 	= $datatemuan[1];
					} else {
						$id_temuan 		= 0; 				$nama_temuan 	= 0;
					}
					$jenis_report 		= (!empty($_POST['jenis_report']))?$_POST['jenis_report']:0;
					$buyer 				= (!empty($_POST['buyer']))?$_POST['buyer']:0;
			
					// $id = (isset($_POST['id_header']) ? $this->generate->kirana_decrypt($_POST['id_header']) : NULL);
					$datacheck 	= $jenis_report;
					$datacheck2	= $id_temuan;
					$datacheck3 = $buyer;
					$data 		= $this->dmasterpica->get_data_pica_template_normal('portal', NULL, NULL, 'in',$datacheck, $datacheck2, $datacheck3);
					if(empty($data))
						$data 		= $this->dmasterpica->get_data_pica_template_normal('portal', NULL, NULL, 'in',$datacheck, $datacheck2, 0);

					echo json_encode($data);
					break;

				case 'data_buyer_so'	: // pica input -- get no SI
					$buyer 		= (!empty($_POST['buyer']))?$_POST['buyer']:0;
					$type 		= (!empty($_POST['type']))?$_POST['type']:NULL;
					$so 		= (!empty($_POST['so']))?$_POST['so']:NULL;
					$lot 		= (!empty($_POST['lot']))?$_POST['lot']:NULL;
					$pabrik 	= (!empty($_POST['pabrik']))?$_POST['pabrik']:NULL;
					
					$data 		= $this->dmasterpica->get_data_pica_buyer_so('portal', NULL, $pabrik, $buyer,$type,$so,$lot);
					echo json_encode($data);
					break;

				case 'data_buyer_si'	: // pica input -- get no SI
					$buyer 		= (!empty($_POST['buyer']))?$_POST['buyer']:NULL;
					$pabrik 	= (!empty($_POST['pabrik']))?$_POST['pabrik']:NULL;
					$si 		= (!empty($_POST['si']))?$_POST['si']:NULL;
					$so 		= (!empty($_POST['so']))?$_POST['so']:NULL;
					$lot 		= (!empty($_POST['lot']))?$_POST['lot']:NULL;
					$pallet 	= (!empty($_POST['pallet']))?$_POST['pallet']:NULL;

					$data 		= $this->dmasterpica->get_data_pica_buyer_si('portal', NULL, NULL, $buyer, $pabrik,$si,$lot,$pallet,$so);
					echo json_encode($data);
					break;

				case 'detail_form'		: // jenis report master = load detail form
					$id_header 	= (isset($_POST['id_header']) ? $_POST['id_header'] : NULL);
					$baris 		= (isset($_POST['baris']) ? $_POST['baris'] : NULL);

					$this->pica_detail_form('portal', NULL, NULL, $id_header,$baris);
					break;
				
				case 'detail_trans'		: // jenis report master = load detail trans
					$id_header 	= (isset($_POST['id_header']) ? $_POST['id_header'] : NULL);
					$baris 		= (isset($_POST['baris']) ? $_POST['baris'] : NULL);

					$this->pica_detail_transaksi('portal', NULL, NULL, $id_header);
					break;

				case 'karyawan'			: // jenis report master = load detail trans
					$id_pabrik 	= (isset($_POST['id_pabrik']) ? $_POST['id_pabrik'] : NULL);
					$pabrik 	= $id_pabrik == 'KMTR' ? 'ho' : $id_pabrik; 
					$data 		= $this->pica_data_karyawan(NULL, NULL, $pabrik);
					echo json_encode($data);
					break;

				default 				:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		public function set($param = NULL) {
			$action = NULL;
			
			$datetime = date("Y-m-d H:i:s");
			if (isset($_POST['type']) && $_POST['type'] == "nonactive") {
				$action = "delete_na";
			} else if (isset($_POST['type']) && $_POST['type'] == "setactive") {
				$action = "activate_na";
			} else if (isset($_POST['type']) && $_POST['type'] == "delete") {
				$action = "delete_del";
			} else if (isset($_POST['type']) && $_POST['type'] == "approve") {
				$action = "approve";
			} else if (isset($_POST['type']) && $_POST['type'] == "submit") {
				$action = "submit";
			} else if (isset($_POST['type']) && $_POST['type'] == "reject") {
				$action = "reject";
			}

			if ($action) {
				switch ($param) {
					case 'data'		:
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_pica_transaksi_header", array(
							array(
								'kolom' => 'id_pica_transaksi_header',
								'value' => $this->generate->kirana_decrypt($_POST['id'])
							)
						));
						$return = $this->general->set($action, "tbl_pica_transaksi_detail", array(
							array(
								'kolom' => 'id_pica_transaksi_header',
								'value' => $this->generate->kirana_decrypt($_POST['id'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;

					case 'approval'	:
						$data 	= $_POST['data'] != NULL ? $_POST['data'] : NULL;
						$app 	= $_POST['app'] != NULL ? $_POST['app'] : NULL;
						$decl 	= $_POST['decl'] != NULL ? $_POST['decl'] : NULL;
						$desc 	= $_POST['desc'] != NULL ? $_POST['desc'] : NULL;
						$number	= $_POST['numb'] != NULL ? $_POST['numb'] : NULL;

						$status = $_POST['status_act'] != NULL ? $_POST['status_act'] : NULL;
						$baris 	= $_POST['baris_act'] != NULL ? $_POST['baris_act'] : NULL;
						$finding= $_POST['finding'] != NULL ? $_POST['finding'] : NULL;

						/*echo $baris." - ".$status." - ".$finding;
						exit();*/
						$return = $this->set_approval($action, "tbl_pica_transaksi_header", 
									$this->generate->kirana_decrypt($_POST['id']),$data,
									base64_decode($this->session->userdata("-id_user-")),
									$datetime,$app,$decl,$desc,$number,$status,$baris,$finding
								);
						// echo json_encode($return);
						break;
					
					default 		:
						$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
						echo json_encode($return);
						break;
				}
			}
		}

		public function save($param = NULL) {
			switch ($param) {				
				case 'pica' 	:
					$this->save_pica();
					break;

				default 		:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		/**********************************/
		/*			  private  			  */
		/**********************************/

		
		/*================================ Template report ====================================*/
	   	
	   	public function pica_data_log($conn=NULL,$id=NULL,$all=NULL,$normal=NULL,$nik=NULL){
	    		$id 		= (!empty($_POST['id_header']))?$this->generate->kirana_decrypt($_POST['id_header']):NULL;        
	    		$all 		= (!empty($_POST['all']))?$_POST['all']:NULL;		        
	    		$data 		= $this->dtranspica->get_data_pica_list_data_log('portal',$id,$all);
    			echo json_encode($data);
	    }

	    // get otorisasi $status_pica=NULL,$posisi=NULL,$pabrik=NULL,$id_temuan=NULL){
	    public function pica_data_otorisasi($conn=NULL,$posisi=NULL,$id_temuan=NULL){
	    		$posisi 		= (!empty($_POST['posisi']))?$_POST['posisi']:NULL;        
	    		$id_temuan 		= (!empty($_POST['id_temuan']))?$_POST['id_temuan']:NULL;		        
	    		$data 			= $this->dtranspica->get_data_pica_otorisasi('portal',$posisi,$id_temuan);
    			echo json_encode($data);
	    }

	    public function pica_normal($conn=NULL,$id=NULL,$active=NULL, $deleted=NULL, $tahun=NULL, $bulan=NULL, $pabrik=NULL,$order=NULL){
            $data = $this->dtranspica->get_data_pica_normal('portal', $id, $active, $deleted, $tahun, $bulan, $pabrik, $order);
            $data = $this->general->generate_encrypt_json($data, array("id_pica_transaksi_header"));
            echo json_encode($data);
	    }

	    public function pica_detail_form($conn=NULL,$id=NULL,$all=NULL,$id_header=NULL,$baris=NULL){	        
            $data = $this->dtranspica->get_data_pica_detail_template('portal',$id,$all,$id_header,$baris);
            echo json_encode($data);
	    }

	    public function pica_detail_transaksi($conn=NULL,$id=NULL,$all=NULL,$id_header=NULL,$baris=NULL,$for=NULL){	        
            $data = $this->dtranspica->get_data_pica_detail_transaksi('portal',$id,$all,$id_header,$baris);

            if($for == 'excel'){
            	return $data;
            } else {
            	echo json_encode($data);
            }
	    }

	    public function pica_data_approval($conn=NULL,$id=NULL,$all=NULL,$normal=NULL,$nik=NULL){
    		$id 					= (!empty($_POST['id_header']))?$this->generate->kirana_decrypt($_POST['id_header']):NULL;        
    		$all 					= (!empty($_POST['all']))?$_POST['all']:NULL;		        
    		$normal					= (!empty($_POST['normal']))?$_POST['normal']:NULL;	
    		$nik  					= (!empty($_POST['filternext_nik']))?$_POST['filternext_nik']:NULL;
    		$type  					= (!empty($_POST['type']))?$_POST['type']:NULL;
    		
    		// check otorisasi
    		$posisi 				= base64_decode($this->session->userdata("-posst-"));
    		$gedung 				= base64_decode($this->session->userdata("-ho-"));
    		$dataposisi 			= $this->dtranspica->get_data_pica_otorisasi('portal',$posisi,NULL,$gedung);
    		$level 					= NULL;
    		$pabrik 				= NULL;
    		$nama_role				= NULL;
    		$oto_temuan 			= NULL;
    		$sess_otorisasi 		= $this->session->userdata("-sess_pica_data_oto-");
    		$sess_otorisasi_file 	= $this->session->userdata("-sess_pica_number_app-") != null ? 
    									$this->session->userdata("-sess_pica_number_app-") : 'tidak ada approval';

    		if($normal != NULL){
    			$data 	= $this->dtranspica->get_data_pica_list_data_normal('portal',$id,$all,$pabrik,$level,$nama_role,$oto_temuan,NULL,
    						$filter_pabrik,$filter_report,$filter_temuan,$filter_buyer,$filter_no);
    			$data 	= $this->general->generate_encrypt_json($data, array("id_pica_transaksi_header"));
	            echo json_encode($data);
    		} else {
	            $data 	= $this->dtranspica->get_data_pica_list_data_app('portal',$id,$all,$sess_otorisasi_file,NULL);
	           	echo $data;
	    	}
		}

	    public function pica_data($conn=NULL,$id=NULL,$all=NULL,$normal=NULL,$nik=NULL){
    		$id 			= (!empty($_POST['id_header']))?$this->generate->kirana_decrypt($_POST['id_header']):NULL;        
    		$all 			= (!empty($_POST['all']))?$_POST['all']:NULL;		        
    		$normal			= (!empty($_POST['normal']))?$_POST['normal']:NULL;	
    		$nik  			= (!empty($_POST['filternext_nik']))?$_POST['filternext_nik']:NULL;
    		$type  			= (!empty($_POST['type']))?$_POST['type']:NULL;
    		$filter_pabrik  = (!empty($_POST['filter_pabrik']))?$_POST['filter_pabrik']:NULL;
    		$filter_report  = (!empty($_POST['filter_report']))?$_POST['filter_report']:NULL;
    		$filter_temuan  = (!empty($_POST['filter_temuan']))?$_POST['filter_temuan']:NULL;
    		$filter_buyer  	= (!empty($_POST['filter_buyer']))?$_POST['filter_buyer']:NULL;
    		$filter_no  	= (!empty($_POST['filter_no']))?$_POST['filter_no']:NULL;

    		// check otorisasi
    		$posisi 	= base64_decode($this->session->userdata("-posst-"));
    		$gedung 	= base64_decode($this->session->userdata("-ho-"));
    		$dataposisi = $this->dtranspica->get_data_pica_otorisasi('portal',$posisi,NULL,$gedung);
    		$level 		= NULL;
    		$pabrik 	= NULL;
    		$nama_role	= NULL;
    		$oto_temuan = NULL;
    		
    		$sess_otorisasi_file = $this->session->userdata("-sess_pica_number_oto-") != null ? array_unique($this->session->userdata("-sess_pica_number_oto-")) : 'tidak ada approval';
    		
    		foreach ($dataposisi as $dt) {
    			$level 		.= $dt->level.",";
    			$pabrik 	.= $dt->pabrik.",";
    			$nama_role 	= $dt->nama_role;
    			$oto_temuan	.= $dt->id_pica_jenis_temuan.",";
    		}
    		$oto_temuan = rtrim($oto_temuan, ',');
    		$level 		= rtrim($level, ', ');
    		$exp_pb 	= explode(',', $pabrik);
    		$dtpabrik 	= null;
    		for($i = 0 ; $i < count($exp_pb); $i++) {
    			if( !strrpos($dtpabrik, $exp_pb[$i]) ){
    				$dtpabrik .= $exp_pb[$i].",";
    			}
    		}
    		$pabrik = $dtpabrik != NULL ? rtrim($dtpabrik,',') : NULL;
    		$level 	= $level != NULL ? explode(',', $level) : NULL;
    		
    		// if super admin can see all 
	    	if($nama_role == 'Super Admin'){
	    		$oto_temuan = NULL;
	    		$pabrik 	= NULL;
	    		// echo $oto_temuan;
	    	}
    		// di bedakan hlaman approval / view
    		if($type == 'view'){
    			$level 		= NULL;
    			$nama_role 	= NULL;
    			
    		} else if($type == 'approval') {
    			$level 		= $level;
    			$nama_role 	= $nama_role;
    		}
    		if($dataposisi == NULL){ // tidak punya otorisasi
	    		$level 		= 'Tidak punya otorisasi';	
	    	}
    	
	    	if($normal != NULL){
    			$data 	= $this->dtranspica->get_data_pica_list_data_normal('portal',$id,$all,$pabrik,$level,$nama_role,$oto_temuan,NULL,
    						$filter_pabrik,$filter_report,$filter_temuan,$filter_buyer,$filter_no);
    			$data 	= $this->general->generate_encrypt_json($data, array("id_pica_transaksi_header"));
	            echo json_encode($data);
    		} else {
    		
            	$data 		= $this->dtranspica->get_data_pica_list_data('portal',$id,$all,NULL,NULL,NULL,NULL,NULL,$sess_otorisasi_file,
       							$filter_pabrik,$filter_report,$filter_temuan,$filter_buyer,$filter_no);

            	// $data->data_finding = $this->dtranspica->get_data_app_finding('portal',$data->id_pica_transaksi_header);
	            /*$data 	= $this->dtranspica->get_data_pica_list_data('portal',$id,$all,$pabrik,$level,$nama_role,$oto_temuan,$gedung,
	        				$filter_pabrik,$filter_report,$filter_temuan,$filter_buyer,$filter_no);*/
	            echo $data;
    		}
		}

	    public function pica_data_detail($conn=NULL,$id=NULL,$all=NULL,$normal=NULL,$nik=NULL){
	    	
    		// get data detail pica
    		$mode 					= (!empty($_POST['mode']))? $_POST['mode'] :NULL;
    		$id 					= (!empty($_POST['id']))?$this->generate->kirana_decrypt($_POST['id']):NULL;
    		$id_temuan 				= (!empty($_POST['id_temuan']))? $_POST['id_temuan'] :NULL;	 
    		$jenis_report 			= (!empty($_POST['jenis_report']))? $_POST['jenis_report'] :NULL;	 
    		$pabrik 				= (!empty($_POST['pabrik']))? explode(",", $_POST['pabrik'] ):NULL; 
    		$pabrik_si 				= (!empty($_POST['pabrik']))?  $_POST['pabrik'] :NULL; 
    		$pabrik_lot 			= (!empty($_POST['pabrik']))?  $_POST['pabrik'] :NULL;
    		$pabrik_prod 			= (!empty($_POST['pabrik']))?  $_POST['pabrik'] :NULL;
    		$buyer 					= (!empty($_POST['buyer']) && $_POST['buyer'] != '0' )? $_POST['buyer'] :NULL; 
    		$si 					= (!empty($_POST['si']))? $_POST['si'] :NULL;
    		$si_4siopt 				= (!empty($_POST['si']))? $_POST['si'] :NULL;
    		$si_4soopt 				= (!empty($_POST['si']))? $_POST['si'] :NULL;
    		$so 					= (!empty($_POST['so']))? $_POST['so'] :NULL;
    		$lot 					= (!empty($_POST['lot']))? $_POST['lot'] :NULL;
    		$lot_4lotopt 			= (!empty($_POST['lot']))? $_POST['lot'] :NULL;
    		$lot_4palletopt 		= (!empty($_POST['lot']))? $_POST['lot'] :NULL;
    		$pallet 				= (!empty($_POST['pallet']))? $_POST['pallet'] :NULL;
    		$pallet_4palletopt		= (!empty($_POST['pallet']))? $_POST['pallet'] :NULL;
    		$date_prod 				= (!empty($_POST['date_prod']))? $_POST['date_prod'] :NULL;
    		
    		// echo $mode."|".$buyer.'|'.$pabrik_si.'|'.$si_4siopt;
    		if($mode != 'detail'){
    			$jenis_report 		= NULL;
    			// $pabrik 			= NULL;
    			$si_4siopt 			= NULL;
    			$lot_4lotopt		= NULL;
    			$pallet_4palletopt 	= NULL;
    		} 
    		// set var 
    		$opt_report  			= $this->dmasterpica->get_data_pica_jenisreport_normal('portal',NULL, 'only active',NULL,NULL,NULL,$id_temuan,$jenis_report);
    		$opt_plant  			= $this->pica_data_plant('no print',$pabrik);
    		if($buyer != NULL){
	    		$opt_si 				= $this->dmasterpica->get_data_pica_buyer_si('portal', NULL, NULL, $buyer, $pabrik_si ,$si_4siopt);
	    		$opt_so 				= $this->dmasterpica->get_data_pica_buyer_si('portal', NULL, NULL, NULL, NULL ,$si_4soopt);
	    		$opt_lot 				= $this->dmasterpica->get_data_pica_buyer_so('portal', NULL, $pabrik_lot, $buyer,'lot',$so,$lot_4lotopt);
	    		$opt_pallet				= $this->dmasterpica->get_data_pica_buyer_so('portal', NULL, $pabrik_lot, $buyer,'pallet',$so,$lot_4palletopt,$pallet_4palletopt);
    		} else {
    			$opt_si 				= array();
	    		$opt_so 				= array();
	    		$opt_lot 				= array();
	    		$opt_pallet				= array();
    		}
    		if($pabrik != NULL && $lot != NULL && $pallet != NULL && $so != NULL )
    			$date_prod		= $this->dmasterpica->get_data_pica_buyer_si('portal', NULL, NULL, NULL, $pabrik_prod ,NULL , $lot, $pallet, $so);
    		else {
    			$date_prod 		= array();
    		}
    		
    		// set array
    		$data 				= $this->dtranspica->get_data_pica_list_data_detail('portal',$id);
    		$data->data_finding = $this->dtranspica->get_data_app_finding('portal',$data->id_pica_transaksi_header);
    		$data->opt_dtprod 	= $date_prod;
			$data->opt_report 	= $opt_report;
    		$data->opt_plant 	= $opt_plant;
    		$data->opt_si 		= $opt_si;
    		$data->opt_so 		= $opt_so;
    		$data->opt_lot 		= $opt_lot;
    		$data->opt_pallet 	= $opt_pallet;
   
            echo json_encode($data);
	    }

	    // get pica header for excel
	    public function pica_data_excel($conn=NULL,$id=NULL,$all=NULL,$normal=NULL,$nik=NULL){
			$data 	= $this->dtranspica->get_data_pica_list_data_normal('portal',$id,$all);
        	return $data;
        }

        // get pica plant
	    public function pica_data_plant($print=NULL,$pabrik=NULL){
	    	$pb 			= "";
	    	$id_temuan 		= (!empty($_POST['id_temuan']))?$_POST['id_temuan']:NULL; 
	    	$posisi 		= base64_decode($this->session->userdata("-posst-"));
	    	// get otorisasi 
	    	$dataposisi = $this->dtranspica->get_data_pica_otorisasi('portal',$posisi);
    		$nama_role	= NULL;
    		foreach ($dataposisi as $dt) {
    			$nama_role 	= $dt->nama_role;
    		} 
    		// echo $nama_role;
    		if($nama_role != 'Super Admin') {
    			// get plant in
				$data_plant_in 	= $this->dtranspica->get_data_akses_pabrik('portal', $posisi,$id_temuan,NULL,'multiple');
				// $data_plant_in 			= $this->check_access_plant(base64_decode($this->session->userdata("-posst-")));
				if($data_plant_in != null ){
					// echo json_encode($data_plant_in);
					foreach($data_plant_in as $dt) {
						$pb = $dt->pabrik;
					}
					$pb = rtrim($pb , ',');
					$pb2 = explode(",", $pb );
				} else {
					$msg    = "periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();	
				}

    		} else {
    			$pb2 = NULL;
    		}
			
			// mode detail condition
    		if($pabrik == NULL){
    			$pbk = $pb2;
    		} else {
    			$pbk = $pabrik;
    		}
	    	
			$data 	= $this->dmasterpica->get_master_plant($pbk);
			if($print == NULL)
				echo json_encode($data);
			else 
				return $data;
			
        }

	    public function excel($param=NULL){

	        $this->load->library('PHPExcel');
	        error_reporting(E_ALL);
	        ini_set('display_errors', TRUE);
	        ini_set('display_startup_errors', TRUE);
	        date_default_timezone_set('Europe/London');

	        if (PHP_SAPI == 'cli')
	            die('This example should only be run from a Web Browser');
	            $objPHPExcel = new PHPExcel();
	            // Set document properties
	            // $objPHPExcel->getProperties()->setCreator("Kiranaku")
	            //     ->setLastModifiedBy("Kiranaku")
	            //     ->setTitle("Export BAK to SAP (" . date('d-m-Y') . ")")
	            //     ->setSubject("Export BAK to SAP (" . date('d-m-Y') . ")")
	            //     ->setDescription("Export BAK to SAP (" . date('d-m-Y') . ")")
	            //     ->setCategory("EXPORT SAP BAK");

	            // Add some data
			//header ========================

	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Data Header');
				
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2'	, 'Jenis Temuan');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3'	, 'Jenis Report');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4'	, 'Kategori');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5'	, 'Buyer');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A6'	, 'Plant');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A7'	, 'No Pica');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A8'	, 'Tanggal');

	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A9'	, 'No SI ( SO )');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A10', 'No Lot');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A11', 'No Pallet');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A12', 'Tanggal Produksi');
	            // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A12', 'Verificator');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A13', 'Definition Pica');
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A14', 'Attachment');
	            // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A15', 'Tanggal');
	            
	            // $objPHPExcel->getActiveSheet()->setTitle('BAK_SAP');
				
	            $id_header = $param;
				//content
				$list_data = $this->pica_data_excel('portal', $id_header, 'only active', 'excel');
	            $baris = 3;
	            // echo '<pre>'.$list_data."</pre>";
	            foreach ($list_data as $data) {
	                $baris++;
					// $objPHPExcel->getActiveSheet()->getRowDimension($baris)->setRowHeight(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(80);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
					$pemisahtitik = ":";
					$objPHPExcel->getActiveSheet()->setCellValue('B2' , $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B3' , $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B4' , $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B5' , $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B6' , $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B7' , $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B8' , $pemisahtitik); 

					$objPHPExcel->getActiveSheet()->setCellValue('B9' , $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B10', $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B11', $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B12', $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B13', $pemisahtitik); 
					$objPHPExcel->getActiveSheet()->setCellValue('B14', $pemisahtitik); 
					// $objPHPExcel->getActiveSheet()->setCellValue('B14', $pemisahtitik);
					
					$objPHPExcel->getActiveSheet()->setCellValue('C2' , $data->temuan); 
					$objPHPExcel->getActiveSheet()->setCellValue('C3' , $data->jenis_report); 
					$objPHPExcel->getActiveSheet()->setCellValue('C4' , $data->kategori);
					$buyer = $data->buyer == 0 ? "" : $data->buyer ; 
					$objPHPExcel->getActiveSheet()->setCellValue('C5' , $buyer); 
					$objPHPExcel->getActiveSheet()->setCellValue('C6' , $data->pabrik); 
					$objPHPExcel->getActiveSheet()->setCellValue('C7' , $data->number); 

					$date_prod_split 	= ($data->date_prod != '' && $data->date_prod != null) ? explode('-', $data->date_prod) : "";
					if(strrpos($data->date_prod, '-')){
						$date_prod 		= $date_prod_split[2].".".$date_prod_split[1].".".$date_prod_split[0];
					} else {
						$date_prod 		= "";
					}

					$date_from_split 	= ($data->date_from != '' && $data->date_from != null) ? explode('-', $data->date_from) : "";
					if(strrpos($data->date_from, '-')){
						$date_from 		= $date_from_split[0].".".$date_from_split[1].".".$date_from_split[2];
					} else {
						$date_from 		= "";
					}
					

					$objPHPExcel->getActiveSheet()->setCellValue('C8' , $date_from); 
					$si 	= $data->si == 0 ? "" : $data->si . "(" .$data->so. ")";
					$lot 	= $data->lot == 0? "" : $data->lot;
					$pallet = $data->pallet == 0? "" : $data->pallet;

					$objPHPExcel->getActiveSheet()->setCellValue('C9' , $si ); 
					$objPHPExcel->getActiveSheet()->setCellValue('C10', $lot); 
					$objPHPExcel->getActiveSheet()->setCellValue('C11', $pallet); 
					// $objPHPExcel->getActiveSheet()->setCellValue('C12', $data->verificator); 
					
					$objPHPExcel->getActiveSheet()->setCellValue('C12', $date_prod); 
					$objPHPExcel->getActiveSheet()->setCellValue('C13', $data->desc); 
					// $objPHPExcel->getActiveSheet()->setCellValue('C14', $data->pica_file);
					$lebar = 0;
					// $list_gambar = explode(",", substr($data->list_gambar,0,-1));
					// foreach ($list_gambar as $url_gambar) {
						// $url = str_replace('http://', '', $link);
						// $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1,2)->getHyperlink()->setUrl('http://www.'.$url);
						if(!empty($data->pica_file)){
							/*
							if( strrpos(strtoupper($data->pica_file), '.JPG') || strrpos(strtoupper($data->pica_file), '.JPEG') 
								|| strrpos(strtoupper($data->pica_file), '.PNG')  ) {

								$objDrawing = new PHPExcel_Worksheet_Drawing();
								$objDrawing->setName('Customer Signature');
								$objDrawing->setDescription('Customer Signature');
								//Path to signature .jpg file
								// $url_gambar = 'uat/kiranaku/assets/file/pica/header/'.$data->pica_file;
								// $gbr  = str_replace('http://','',$url_gambar); 
								// $url  = explode("/" , $gbr);
								// $path = $_SERVER['DOCUMENT_ROOT']."/".$gbr; // local
								// $path = str_replace($url[0],$_SERVER['DOCUMENT_ROOT'],$url_gambar); // local
								// $path = str_replace($url[0],'/var/www/html/',$gbr);  		// 105
								// $path = '/var/www/html/'.$gbr;
								// $path =  str_replace('http://', '', $path);
								// echo json_encode($url[0]);
								$url_gambar = realpath("./").'/assets/file/pica/header/'.$data->pica_file;
								if (file_exists($url_gambar)) {
									$signature = $url_gambar;  
								}else{
									// $signature = 'C:/xampp/xampp/htdocs/uat/kiranaku/assets/file/cctv/default.png';  // local
									$signature = realpath("./").'/assets/file/cctv/default.png';  	// 105
								}
								
								$objDrawing->setPath($signature);
								$objDrawing->setOffsetX(50+$lebar);               //setOffsetX works properly
								$objDrawing->setOffsetY(10);                     //setOffsetY works properly
								$objDrawing->setCoordinates('C14');             //set image to cell 
								$objDrawing->setWidth(50);
								$objDrawing->setHeight(50);                    //signature height  
								$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
								$lebar +=50;

							} else if(strrpos(strtoupper($data->pica_file), '.PDF') || strrpos(strtoupper($data->pica_file), '.ZIP') ){
							*/
								$objPHPExcel->getActiveSheet()->setCellValue('C14', $data->pica_file); 
								$url_gambar = base_url().'/assets/file/pica/header/'.$data->pica_file;
								// $url = str_replace('http://', '', $link);
								$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2,14)->getHyperlink()->setUrl($url_gambar);	
							//}
						}
					// }
						// $objPHPExcel->getActiveSheet()->setCellValue('E1', $path); 
						// $objPHPExcel->getActiveSheet()->getColumnDimension('C13')->setWidth(50);
						$objPHPExcel->getActiveSheet()->getRowDimension('14')->setRowHeight(50);
				}
			 	// header ====================================================================================
				// set text bold
				$from 	= "A1"; // or any value
				$to 	= "B100"; // or any value	
				$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );

				// set header border table
				$styleArray = array(
				      'borders' => array(
				          'allborders' => array(
				              'style' => PHPExcel_Style_Border::BORDER_THIN
				          )
				      )
				  );
				$from_header 	= "A2"; // or any value
				$to_header 		= "C14"; // or any value	
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->applyFromArray($styleArray);
				// set align left
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				// Set collor name header table
				$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray(
				    array(
				        'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => '#18CA06')
				        ),
				        'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => '#FFFFFF'),
					        'size'  => 15,
					        // 'name'  => 'Verdana'
					    )
				    )
				);
			// header ====================================================================================

			//content detail
				$list_detail = $this->pica_detail_transaksi('portal', NULL, 'only active', $id_header,NULL,'excel');
	            // $data->data_finding = $this->dtranspica->get_data_app_finding('portal',$data->id_pica_transaksi_header);
	            // header table detail transaksi
	            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A16', 'Data Detail');
				$baris 			= 16;
				$baris_array 	= array();
				// echo '<pre>'.$list_data."</pre>"; $this->pica_detail_transaksi('portal', NULL, NULL, $id_header);
	            foreach ($list_detail as $data) {
	            	$baris++;
	            	if(!in_array((int)$data->baris, $baris_array)){
	            		$baris_array[] = $data->baris;
	            		if($data->posisi_finding != 'Finish'){
	            			$st = 'Sedang diproses '.$data->nama_posisi_finding;
	            		} else {
	            			$st = $data->posisi_finding;
	            		}
	            		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$baris, 'Baris '.$data->baris.' ( '.$st.' )');
	            		// set text bold
	            		$objPHPExcel->getActiveSheet()->getStyle("A".$baris)->getFont()->setBold( true );
	            		// Set collor name header table
						$objPHPExcel->getActiveSheet()->getStyle('A'.$baris.':C'.$baris)->applyFromArray(
						    array(
						        'fill' => array(
						            'type' => PHPExcel_Style_Fill::FILL_SOLID,
						            'color' => array('rgb' => '#18CA06')
						        ),
						        'font'  => array(
							        'bold'  => true,
							        'color' => array('rgb' => '#FFFFFF'),
							        'size'  => 12,
							        // 'name'  => 'Verdana'
							    )
						    )
						);
	            		$baris++;
	            	}
	            	$pemisahtitik = ":";
	            	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$baris, $data->label);
	            	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$baris, $pemisahtitik);
	            	if($data->type_input != 'file'){
	            		if($data->nama_form != 'pic'){
	            			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$baris, $data->desc);	
	            		} else if($data->nama_form == 'pic') {
	            			$list_data_karyawan = $this->dmasterpica->get_data_user($data->desc);
	            			$nama_karyawan = "";
	            			foreach ($list_data_karyawan as $dt_karyawan) {
	            				$nama_karyawan = $dt_karyawan->nama;
	            			}
	            			$data_karyawan = $nama_karyawan." ( ".$data->desc." ) ";
	            			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$baris, $data_karyawan);
	            		}
	            			
	            	} else {
	            		$lebar = 0;
						if(!empty($data->desc)){
							/*
							if( strrpos(strtoupper($data->desc), '.JPG') || strrpos(strtoupper($data->desc), '.JPEG') 
								|| strrpos(strtoupper($data->desc), '.PNG')  ) {

								$objDrawing = new PHPExcel_Worksheet_Drawing();
								$objDrawing->setName('Customer Signature');
								$objDrawing->setDescription('Customer Signature');
								//Path to signature .jpg file
								// $url_gambar = 'uat/kiranaku/assets/file/pica/detail/'.$data->desc;
								// $gbr  = str_replace('http://','',$url_gambar); 
								// $url  = explode("/" , $gbr);
								// $path = $_SERVER['DOCUMENT_ROOT']."/".$gbr; // local
								// $path = str_replace($url[0],$_SERVER['DOCUMENT_ROOT'],$url_gambar); // local
								// $path = str_replace($url[0],'/var/www/html/',$gbr);  		// 105
								// $path = '/var/www/html/'.$gbr;
								// $path =  str_replace('http://', '', $path);
								// echo json_encode($url[0]);
								$url_gambar = realpath("./").'/assets/file/pica/detail/'.$data->desc;
								if (file_exists($url_gambar)) {
									$signature = $url_gambar;  
								}else{
									// $signature = 'C:/xampp/xampp/htdocs/uat/kiranaku/assets/file/cctv/default.png';  // local
									$signature = realpath("./").'/assets/file/cctv/default.png';  	// 105
								}
								
								$objDrawing->setPath($signature);
								$objDrawing->setOffsetX(50+$lebar);               //setOffsetX works properly
								$objDrawing->setOffsetY(10);                     //setOffsetY works properly
								$objDrawing->setCoordinates('C'.$baris);             //set image to cell 
								$objDrawing->setWidth(50);
								$objDrawing->setHeight(50);                    //signature height  
								$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());  //save 
								$lebar +=50;
								$objPHPExcel->getActiveSheet()->getRowDimension($baris)->setRowHeight(50);

							} if(strrpos(strtoupper($data->desc), '.PDF') || strrpos(strtoupper($data->desc), '.ZIP') ){
							*/
								$objPHPExcel->getActiveSheet()->setCellValue('C'.$baris, $data->desc); 
								$url_gambar = base_url().'/assets/file/pica/detail/'.$data->desc;
								// $url = str_replace('http://', '', $link);
								$objPHPExcel->getActiveSheet()->getCell('C'.$baris)->getHyperlink()->setUrl($url_gambar);	
							// }
						}

	            	}
	            	
				}
				// detail ====================================================================================
				// set text bold
				$from 	= "A16"; // or any value
				$to 	= "B100"; // or any value	
				$objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );

				// set header border table
				$styleArray = array(
				      'borders' => array(
				          'allborders' => array(
				              'style' => PHPExcel_Style_Border::BORDER_THIN
				          )
				      )
				  );
				$from_header 	= "A17"; // or any value
				$to_header 		= "C".$baris; // or any value	
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->applyFromArray($styleArray);
				// set align left top
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				
				// Set collor name header table
				$objPHPExcel->getActiveSheet()->getStyle('A16:C16')->applyFromArray(
				    array(
				        'fill' => array(
				            'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => '#18CA06')
				        ),
				        'font'  => array(
					        'bold'  => true,
					        'color' => array('rgb' => '#FFFFFF'),
					        'size'  => 15,
					        // 'name'  => 'Verdana'
					    )
				    )
				);
			// detail ====================================================================================

	            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
	            $objPHPExcel->setActiveSheetIndex(0);


	            // Redirect output to a client’s web browser (Excel5)
	            header('Content-Type: application/vnd.ms-excel');
	            header('Content-Disposition: attachment;filename="Pica_online_fo.xls"');
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


	    public function set_approval($action=NULL,$table=NULL,$id=NULL,$data1=NULL,$data2=NULL,$data3=NULL,$app=NULL,$decl=NULL,
	    	$desc=NULL,$number=NULL,$status=NULL,$baris=NULL,$finding=NULL){
			$delete 		= $this->dtranspica->delete_data_pica_finding('portal', $id);
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$datetime 		= date("Y-m-d H:i:s");
			$date 			= date("Y-m-d");
			$data_split 	= explode("^", $data1);	
			$status_pica 	= ($data_split[0]=='null') ? 0 : $data_split[0];
			//insert into table log
			if($action == 'approve' || $action == 'submit' ){
				if($status_pica != 'null'){
					$pica_status 	= $app;
				} else {
					$pica_status 	= 1; 
				}
				
			} else if($action 	== 'reject'){
				$pica_status 	= $decl;
			}
			$data_row2 = array(
					//all 
					"id_pica_header"	=> $id,		
					"number"			=> $number,	
					"date_action"		=> $date,	
					"action"			=> $action,
					"pic"				=> base64_decode($this->session->userdata("-nik-")),	
					"posst"				=> base64_decode($this->session->userdata("-posst-")),
					"comment"			=> $desc,	
					"pica_status"		=> $pica_status,	
					'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'      => $datetime,
					'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      => $datetime
				);
   
			$this->dgeneral->insert("tbl_pica_log", $data_row2);

			

			if($baris != 0){
				
				// echo json_encode($finding);
				$j = 0;
				$arr_dtmail = [];
				$arr_status	= [];
				$data_split 	= explode("^", $data1);	
				for($i = 1 ; $i <= $baris; $i++ ){
					// echo $i;
					$status_pica_head	= ($data_split[0]=='null') ? 0 : $data_split[0];
					$pabrik 			= $data_split[1];
					$id_temuan 			= $data_split[2];
					// echo $i;
					$findexpl 			= explode(',', $finding);
					$status_finding 	= (!empty($finding)) ? trim($findexpl[$j]) : 0 ;
					$status_finding 	= ($status_finding != '') ? $status_finding : 0 ;
					
					// echo $status_finding;
					if($status_finding === "Approve" ){ // ok
						if($status != 'null'){
							$pica_status 	= $app;
						} else {
							$pica_status 	= 1; 
						}
						// echo "a".$pica_status;
						
					} else if($status_finding === "Decline"){ // ok
						$pica_status 	= $decl;
						// echo "b".$pica_status;
					} else  {
						if($status != 'null'){
							$data_app 		= $this->dtranspica->get_data_pica_app_last_hist(NULL,NULL,NULL,$id,$i,'level_app');
							$data_app2 		= $this->dtranspica->get_data_pica_app_last_hist(NULL,NULL,NULL,$id,$i,'status');
							// echo $data_app->level_app;
							if($data_app != ''){
								if($data_app->level_app != ''){
									$st 			= $data_app->level_app;
									$status_finding = $data_app2->status; 
								} else {
									$st= $status;
								}
							} else {
								$st = "";
							}
							$pica_status 	= $st;
						} else if($status == 'null'){
							$pica_status 	= 1; 
						}
						// echo "c".$pica_status;
					}


					if($pica_status == 100){$pica_statusinp = 'Finish';} else {$pica_statusinp = ($pica_status);}
					$data_row_finding = array(
						//all 
						// "id_pica_jenis_temuan" 	=> $id_temuan,
						"id_pica_transaksi_header" 	=> $id,
						"baris" 					=> $i,
						"status" 					=> trim($status_finding),
						"date_approval" 			=> $datetime,
						"level_app"					=> trim($pica_statusinp),
						'na'     					=> 'n',
						'del'     					=> 'n',
						'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'      		=> $datetime,
						'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'      		=> $datetime
					);
					// insert into table finding 
					$this->dgeneral->insert("tbl_pica_transaksi_finding_approval", $data_row_finding);
					$this->dgeneral->insert("tbl_pica_transaksi_finding_approval_history", $data_row_finding); 


					// echo "<br>data = ". json_encode($finding)." - ".json_encode($status_finding)." pbk = ".$pabrik."stat =".$pica_statusinp."stat pica head = ".$status_pica_head." -  ".$findexpl[$j];
					if(($finding == '0' && $status_finding == '0' && $status_pica_head == null ) 
							|| ($finding != '0' && $status_finding != '0' && $status_pica_head != null && ($findexpl[$j]) != '' ) ){
						// echo " masuk email1";

						if(!in_array($pica_statusinp, $arr_status)){					
							$arr_status[] = (trim($pica_statusinp));
							// echo " masuk email";
							if(trim($status_finding) 			== 'Approve'){
								$email_action 					= 'persetujuan approval';
							} else if(trim($status_finding) 	== 'submit'){
								$email_action 					= $status_pica == 'null' ?'melengkapi data':'periksa data';
							} else if(trim($status_finding) 	== 'Decline'){
								$email_action 					= 'dilakukan koreksi';
							} else {
								$email_action 					= 'persetujuan approval';
							}

							if( ($pica_statusinp != 'Finish' && trim($status_finding) != '0') || ( trim($status_finding) == '0' && $status_pica_head == null)   ){
								// get posisi
								$data_posisi 		= $this->dtranspica->get_data_akses_pabrik(NULL, NULL, $id_temuan, $pica_statusinp,'single');
								// echo json_encode($data_posisi)."\/n";
								$check_posisi_loc=[];
								if($data_posisi != null) {
									$check_posisi_loc 	= $this->dtranspica->check_data_posisi(NULL,  $data_posisi->id_posisi);
									foreach ($check_posisi_loc as $dt_check_loc) {						
										$loc			= $dt_check_loc->id_gedung;
									}
								} else {
									$msg    = "periksa kembali master role penerima pica selanjutnya";
									$sts    = "NotOK";
									$return = array('sts' => $sts, 'msg' => $msg);
									echo json_encode($return);
									exit();	
								} 
								// echo "lokasi_awal = ".$pabrik;
								if(count($check_posisi_loc) == 1 && $loc == 'ho'){
									$pabrik 		= NULL;
								} 
								// if(count($check_posisi_loc) >= 2){
								// 	$pabrik 		= 
								// }
								// echo "lokasi = ".$loc." - ".$pabrik;
								
								$list_email 		= array();
								$list_nama 			= array();
								$list_nik 			= array();
								// get data karyawan
								$data = $this->dtranspica->get_data_karyawan(NULL, $data_posisi->id_posisi , $pabrik);
								foreach ($data as $dt) {
									$list_email[]	= $dt->email;
									$list_nama[]	= $dt->nama_karyawan;
									$list_nik[] 	= $dt->nik;
								}
								$list_email = array_filter($list_email, create_function('$value', 'return $value !== "";'));
								
								// get posisi
								$data_log		= $this->dtranspica->get_data_pica_list_data_log(NULL,$id,'only active',$list_nik,'pic');

								$nik_log 		= array();
								$email_cc 		= array();
								foreach ($data_log as $dt_log) {
									if( !in_array($dt_log->pic, $nik_log) && !in_array($list_nik, $nik_log) ){
										$nik_log[]	= $dt_log->pic;
										$email_cc[] = $dt_log->email;
									}
								}
								// echo json_encode($list_email);
								// echo json_encode($email_cc);
							// aktifkan ketika live ayy
								$this->lpica->send_email(
			                        array(
			                            'judul' 			=> "Pica Online",
			                            'email_pengirim' 	=> "KiranaKu",
			                            'email_tujuan' 		=> $list_email,//'airiza.perdana@kiranamegatara.com',
			                            'email_cc' 			=> $email_cc,
			                            'view' 				=> 'email/next_approval',
			                            'data' 				=> array(
							                                'number' 	=> $number,
							                                'action' 	=> $email_action,
							                                'to_mail'	=> $list_nama

			                            )
			                        )
			                    );
							} 
							
						}
					}
				//	end email
					
					$j++;
				}

				// check data finding for finish 
				$ckdata_finding = $this->dtranspica->get_data_app_finding(NULL,$id);
				// echo json_encode($ckdata_finding);
				// $arrdt_finish	= [];
				$jum_finish = 0;
				$jum_data 	= $baris;
				foreach ($ckdata_finding as $value) {
					if($value->level_app == "Finish"){
						$jum_finish++;
					}
				}
				
				// echo "<br>Jum Data = ".$jum_data."jum Finish = ".$jum_finish;
				// exit();
				if($jum_data == $jum_finish){
					$data_log		= $this->dtranspica->get_data_pica_list_data_log(NULL,$id,'only active');
					$nik_log 		= array();
					$email_cc 		= array();
					foreach ($data_log as $dt_log) {
						if( !in_array($dt_log->pic, $nik_log)  ){
							$nik_log[]		= $dt_log->pic;
							$list_email[] 	= $dt_log->email;
						}
					}
				// aktifkan ketika live ayy
					$this->lpica->send_email(
                        array(
                            'judul' 			=> "Pica Online",
                            'email_pengirim' 	=> "KiranaKu",
                            'email_tujuan' 		=> $list_email,//'airiza.perdana@kiranamegatara.com',
                            // 'email_cc' 			=> $email_cc,
                            'view' 				=> 'email/next_approval',
                            'data' 				=> array(
				                                'number' 	=> $number,
				                                'action' 	=> 'finish',
				                                'to_mail'	=> null

                            )
                        )
                    );	
				}


			}	

			// exit();  
			// echo json_encode($arr_dtmail);
			// echo json_encode($arr_status);
			// exit();
	    	// echo $action;
	    	
	    	// $return = $this->set_approval($action, "tbl_pica_transaksi_header", $this->generate->kirana_decrypt($_POST['id']));
			
			$datetime 			= date("Y-m-d H:i:s");
			$date 				= date("Y-m-d");
			// $data_split 		= explode("^", $data1);	
			// $status_pica 		= ($data_split[0]=='null') ? 0 : $data_split[0];
			$pabrik 			= $data_split[1];
			$id_temuan 			= $data_split[2];
			$requestor_temuan 	= $data_split[3];
			$jenis_rpt			= $data_split[4];

			// echo json_encode($data_split);
			// exit();
				
			if($action == 'approve' || $action == 'submit' ){
				if($status_pica != 'null'){
					$pica_status 	= $app;
				} else {
					$pica_status 	= 1; 
				}
				
			} else if($action 	== 'reject'){
				$pica_status 	= $decl;
			}

			if($action == 'approve'){
				$email_action 	= 'persetujuan approval';
			} else if($action 	== 'submit'){
				$email_action 	= $status_pica == 'null' ?'melengkapi data':'periksa data';
			} else if($action 	== 'reject'){
				$email_action 	= 'dilakukan koreksi';
			} else {
				$email_action 	= 'persetujuan approval';
			}

			if($pica_status == 100 && ($jum_data == $jum_finish)){$pica_status = 'Finish';} else if($pica_status == 100 && ($jum_data != $jum_finish)){$pica_status = 'On Progress';}	
            $data_row = array(
							//all 
							"pica_status"			=> $pica_status,		
							// "next_nik"				=> $next_nik,		
							'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'      	=> $datetime
						);
            $data_row2 = array(
							//all 
							"id_pica_header"	=> $id,		
							"number"			=> $number,	
							"date_action"		=> $date,	
							"action"			=> $action,
							"pic"				=> base64_decode($this->session->userdata("-nik-")),	
							"posst"				=> base64_decode($this->session->userdata("-posst-")),
							"comment"			=> $desc,	
							"pica_status"		=> $pica_status,	
							'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'      => $datetime,
							'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'      => $datetime
						);
            // set status pica
			$this->dgeneral->update("tbl_pica_transaksi_header", $data_row, array(
				array(
					'kolom' => 'id_pica_transaksi_header',
					'value' => $id
				)
			));
			// $this->dgeneral->insert("tbl_pica_log", $data_row2);


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
	
	    private function save_pica() {
	    	// var_dump($this->generate->kirana_decrypt($_POST['id_hide']));
	    	// var_dump($_POST);die();
	    	$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$number_fieldname 		= (!empty($_POST['number_fieldname']))?$_POST['number_fieldname']:0;
			$temuan_fieldname 		= (!empty($_POST['temuan_fieldname']))?$_POST['temuan_fieldname']:0;
			$jenis_report_fieldname = (!empty($_POST['jenis_report_fieldname']))?$_POST['jenis_report_fieldname']:0;
			$kategori_fieldname 	= (!empty($_POST['kategori_fieldname']))?$_POST['kategori_fieldname']:0;
			$buyer_fieldname 		= (!empty($_POST['buyer_fieldname']))?$_POST['buyer_fieldname']:0;
			$pabrik_fieldname 		= (!empty($_POST['pabrik_fieldname']))?$_POST['pabrik_fieldname']:0;
			$tanggal_fieldname 		= (!empty($_POST['tanggal_fieldname']))?$_POST['tanggal_fieldname']:0;

			$si_fieldname 			= (!empty($_POST['si_fieldname']))?$_POST['si_fieldname']:0;
			$lot_fieldname 			= (!empty($_POST['lot_fieldname']))?$_POST['lot_fieldname']:0;
			$pallet_fieldname 		= (!empty($_POST['pallet_fieldname']))?$_POST['pallet_fieldname']:0;
			$so_fieldname 			= (!empty($_POST['so_fieldname']))?$_POST['so_fieldname']:0;
			$date_prod_fieldname 	= (!empty($_POST['tanggal_prod_fieldname']))?$_POST['tanggal_prod_fieldname']:0;
			// $verificator_fieldname 	= (!empty($_POST['id_verificator']))?implode(",", $_POST['id_verificator']):NULL;
			$verificator_fieldname 	= NULL;
			$def_fieldname 			= (!empty($_POST['def_fieldname']))?$_POST['def_fieldname']:0;
			$foto_fieldname 		= (!empty($_POST['foto_fieldname']))?$_POST['foto_fieldname']:0;
			$baris_hidden 			= (!empty($_POST['baris_hidden']))?$_POST['baris_hidden']:0;
			$list_detail_delete 	= (!empty($_POST['id_delete_hidden']))?rtrim($_POST['id_delete_hidden'],','):NULL;
			$mode_hidden 			= (!empty($_POST['mode_hidden']))?rtrim($_POST['mode_hidden'],','):'0';
			
			$data_row 				= null;
			if($temuan_fieldname != 0){
				$datatemuan 		= explode("|", $temuan_fieldname);
				$id_temuan 			= $datatemuan[0]; 	$nama_temuan 	= $datatemuan[1]; 		$requestor_temuan 	= $datatemuan[3];
			} else {
				$id_temuan 			= 0; 				$nama_temuan 	= 0; 					$requestor_temuan 	= "";
			}

			if($tanggal_fieldname != 0){
				$datatanggal 		= explode(".", $tanggal_fieldname);
				$tanggal_fieldname 	= $datatanggal[2].'-'.$datatanggal[1].'-'.$datatanggal[0]; 	
			} else {
				$tanggal_fieldname 	= 0; 				
			}

			if($date_prod_fieldname != 0){
				$datatanggal1 			= explode(".", $date_prod_fieldname);
				$date_prod_fieldname 	= $datatanggal1[2].'-'.$datatanggal1[1].'-'.$datatanggal1[0]; 	
			} else {
				$date_prod_fieldname 	= NULL; 				
			}

			$data_row = array(
					//all 
					// "id_pica_jenis_temuan" 	=> $id_temuan,
					"id_pica_jenis_temuan" 	=> $id_temuan,
					"requestor" 			=> $requestor_temuan,
					"id_pica_kategori" 		=> $kategori_fieldname,
					"pabrik" 				=> $pabrik_fieldname,
					"jenis_report" 			=> $jenis_report_fieldname,
					"buyer" 				=> $buyer_fieldname,
					"number" 				=> $number_fieldname,
					"date_from" 			=> $tanggal_fieldname,
					"date_to" 				=> $tanggal_fieldname,
					"date_posted" 			=> $datetime,
					"jumlah_baris" 			=> $baris_hidden,
					"si" 					=> $si_fieldname,
					"lot" 					=> $lot_fieldname,
					"pallet" 				=> $pallet_fieldname,
					"so" 					=> $so_fieldname,
					"date_prod" 			=> $date_prod_fieldname,
					"desc" 					=> htmlentities($def_fieldname), // setiap textarea 
					"verificator_posisi"	=> $verificator_fieldname,		
					// "next_nik"				=> base64_decode($this->session->userdata("-nik-")),		
					// "pica_status"			=> NULL,		
						
					
					'na'     				=> 'n',
					'del'     				=> 'n',
					'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'      	=> $datetime,
					'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      	=> $datetime
				);
			// echo json_encode($data_row);
			if (!empty($this->generate->kirana_decrypt($_POST['id_hide'])) && $this->generate->kirana_decrypt($_POST['id_hide']) != 0) { // edit		
				// echo 'update' ;
				$id_edit 	= ($_POST['id_hide'] != '' ) ? $this->generate->kirana_decrypt($_POST['id_hide']) : 0;
				// check if exist 
				
				$datacheck 	= $number_fieldname;
				$datacheck2	= $pabrik_fieldname;
				$datacheck3 = $buyer_fieldname;
				$checkdata 	= $this->dtranspica->check_data_pica(NULL, $id_edit, NULL, 'up',$datacheck, $datacheck2, $datacheck3);
				if (count($checkdata) > 0) {
					$this->dgeneral->rollback_transaction();
					$msg    = "Duplicate data nomor pica ".$datacheck." pada pabrik ".$datacheck2." ".$datacheck3." , periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				unset($data_row['login_buat'],$data_row['tanggal_buat']);

				if($mode_hidden != 'response'){
					$this->dgeneral->update("tbl_pica_transaksi_header", $data_row, array(
						array(
							'kolom' => 'id_pica_transaksi_header',
							'value' => $this->generate->kirana_decrypt($_POST['id_hide'])
						)
					));
				}

				$filetemp 	= 'foto_fieldname';	
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

                	$config['upload_path']   	= $this->general->kirana_file_path($this->router->fetch_module()).'/header/';
					$config['allowed_types'] 	= 'jpg|jpeg|png|pdf|zip';
					$config['max_size'] 		= 3120;
                	$nm_file 					= array($number_fieldname);
                	// $upload_error = $this->general->check_upload_file($filetemp, true);
					
					// if(empty($upload_error)){
                		$files  		= $this->general->upload_files($_FILES[$filetemp], $nm_file, $config);
                		
                	// } else {
                	// 	$files  		= "";
                	// 	$upload_error 	= $this->upload->display_errors('', '');
                	// }
                	
                	$last_id = $this->generate->kirana_decrypt($_POST['id_hide']);
                	// update detail data
                	if($last_id !== ""){
                		$data_row   = array(	                                    
	                                    "pica_file" 	=> $files[0]['file_name'],
	                                    'login_edit'    => base64_decode($this->session->userdata("-nik-")),
	                                    'tanggal_edit' 	=> $datetime	                                    
	                                );
			                $this->dgeneral->update("tbl_pica_transaksi_header", $data_row, array(
										array(
											'kolom' 	=> 'id_pica_transaksi_header',
											'value' 	=> $last_id
										)	
							));              
			        }else{
			        	$msg    	= "Periksa kembali data yang dimasukkan";
			            $sts    	= "NotOK";
			            $filestring = $this->general->kirana_file_path(
			            					$this->router->fetch_module()
			            				)."/header/".$files[0]['file_name'];
			            unlink($filestring);			            				            
			        }
			        
            	}

				$insert_id_header   = $this->generate->kirana_decrypt($_POST['id_hide']);
				$delete 			= $this->dtranspica->delete_data_pica_detail("portal", explode(',', $list_detail_delete) );
				// loop data for insert detail
				foreach ($_POST['detail'] as $key => $value) {
					foreach($value as $iInput => $vInput){
						$id 	= $vInput['id_detail'];
						$tipe 	= $vInput['tipe'];
						// declare var 
						$id_detail_per_baris	= isset($_POST['detail'][$key][$iInput]['id_detail']) ? $_POST['detail'][$key][$iInput]['id_detail'] : 0 ;
						$baris 					= $_POST['detail'][$key][$iInput]['baris'];
						$data_detail 			= $_POST['detail'][$key][$iInput]['detail_value'];
						$data_detail_split 		= explode(",", $data_detail);
						$data_id_pica_mst_input = $data_detail_split[0];
						$data_label 			= $data_detail_split[1];
						$data_fieldname			= $data_detail_split[2];
						if($tipe == 'file'){
							$nm_file 			= isset($_FILES['detail']['name'][$key][$iInput]['value']) 
													? $_FILES['detail']['name'][$key][$iInput]['value'] 
													: '' ;
							$data_row2 = array( 
										//all 
										"id_pica_transaksi_header" 	=> $insert_id_header,
										"id_pica_mst_input" 		=> $data_id_pica_mst_input,
										"baris" 					=> $baris,						
										"label" 					=> $data_label,						
										// "desc" 						=> $value,
										'na'     					=> 'n',
										'del'     					=> 'n',
										'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
										'tanggal_buat'      		=> $datetime,
										'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
										'tanggal_edit'      		=> $datetime
										);
							if($id_detail_per_baris != 0){
								unset($data_row2['login_buat'],$data_row2['tanggal_buat']);	
								$this->dgeneral->update("tbl_pica_transaksi_detail", $data_row2, 
																		array(
																			array(
																				'kolom' 	=> 'id_pica_transaksi_detail',
																				'value' 	=> $id
																			)	
																		)); 
								//get last id 
								$last_id 	= $id;
							} else {
			    				$this->dgeneral->insert("tbl_pica_transaksi_detail", $data_row2);
			    				//get last id 
								$last_id 	= $this->db->insert_id();
							}
				    			
				    		if($nm_file != ""){
				    			$datex      						= date("Ymd")."_".date("His");
								$filetemp2 							= 'detail_image_'.$key.'_'.$iInput;
								$_FILES[$filetemp2]['name'][]  		= $nm_file;
								$_FILES[$filetemp2]['type'][]  		= $_FILES['detail']['type'][$key][$iInput]['value'];
								$_FILES[$filetemp2]['tmp_name'][] 	= $_FILES['detail']['tmp_name'][$key][$iInput]['value'];
								$_FILES[$filetemp2]['error'][]  	= $_FILES['detail']['error'][$key][$iInput]['value'];
								$_FILES[$filetemp2]['size'][]  		= $_FILES['detail']['size'][$key][$iInput]['value'];
								

								if($_FILES[$filetemp2]['name'][0] != ""){	
									
									// unlink gb
									$img_form_name	= $number_fieldname."_".$insert_id_header."_".$baris."_".$datex;
									
				                	$config2['upload_path']   		= $this->general->kirana_file_path($this->router->fetch_module()).'/detail/';
				                	$config2['allowed_types'] 		= 'jpg|jpeg|png|pdf|zip';
									$config2['max_size'] 			= 3120;
				                	$nm_file2 						= array($img_form_name);
				                	// $upload_error2 					= $this->general->check_upload_file($filetemp2, true);
				                	
									// var_dump($upload_error2);
				                	// if($upload_error2 == NULL){
				                		$files2  		= $this->general->upload_files($_FILES[$filetemp2], $nm_file2, $config2);
				                		
				                	// } else {
				                	// 	$files2  		= "";
				                	// 	$upload_error2 	= $this->upload->display_errors('', '');
				                		
				                	// }
				                	if($last_id !== ""){
							            $data_row   = array(	                                    
					                                    "desc" 			=> $files2[0]['file_name'],
					                                    'login_edit'    => base64_decode($this->session->userdata("-nik-")),
					                                    'tanggal_edit' 	=> $datetime	                                    
					                                );
							                $this->dgeneral->update("tbl_pica_transaksi_detail", $data_row, array(
														array(
															'kolom' 	=> 'id_pica_transaksi_detail',
															'value' 	=> $last_id
														)	
											));   	              
							            
							        }else{
							            $msg    	= "Periksa kembali data yang dimasukkan";
							            $sts    	= "NotOK";
							            $filestring = $this->general->kirana_file_path(
							            					$this->router->fetch_module()
							            				)."/detail/".$files2[0]['file_name'];
							            unlink($filestring);			            				            
							        }
							    }
				            }
					    // input type != file
						} else {
							$val 					= isset($_POST['detail'][$key][$iInput]['value']) ? $_POST['detail'][$key][$iInput]['value'] : 0;				
							$data_row2 = array( 
										//all 
										"id_pica_transaksi_header" 	=> $insert_id_header,
										"id_pica_mst_input" 		=> $data_id_pica_mst_input,
										"baris" 					=> $baris,						
										"label" 					=> $data_label,						
										"desc" 						=> $val,
										'na'     					=> 'n',
										'del'     					=> 'n',
										'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
										'tanggal_buat'      		=> $datetime,
										'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
										'tanggal_edit'      		=> $datetime
										);
							if($id_detail_per_baris != 0){
								unset($data_row2['login_buat'],$data_row2['tanggal_buat']);	
								$this->dgeneral->update("tbl_pica_transaksi_detail", $data_row2, array(
													array(
														'kolom' 	=> 'id_pica_transaksi_detail',
														'value' 	=> $id
													)	
										)); 
							} else {
			    				$this->dgeneral->insert("tbl_pica_transaksi_detail", $data_row2);
							}	
			    		}

					}
				}

				// loop data for insert approval & approval his per finding
				$app 		= $_POST['if_approve_hide_details'] != NULL ? $_POST['if_approve_hide_details'] : NULL;
				$decl 		= $_POST['if_decline_hide_details'] != NULL ? $_POST['if_decline_hide_details'] : NULL;
				$sts 		= $_POST['status_pica_details'] != NULL ? $_POST['status_pica_details'] : NULL;
				$types 		= $_POST['type_hide_details'] != NULL ? $_POST['type_hide_details'] : NULL;
				// var_dump($app , $decl , $types);
				// exit();
				/*
				$delete 			= $this->dtranspica->delete_data_pica_finding("portal", $insert_id_header );
				
				if($baris_hidden != 0){

					for($i = 1 ; $i <= $baris_hidden; $i++ ){
						
						$status_finding 	= (!empty($_POST['actionapp_'.$i]))?trim($_POST['actionapp_'.$i]):0;
						// echo $status_finding;
						if($status_finding === "Approve" ){ // ok
							if($sts != 'null'){
								$pica_status 	= $app;
							} else {
								$pica_status 	= 1; 
							}
							
						} else if($status_finding === "Decline"){ // ok
							$pica_status 	= $decl;
						} else  {
							$pica_status 	= $sts;
						}
						
						$data_row_finding = array(
							//all 
							// "id_pica_jenis_temuan" 	=> $id_temuan,
							"id_pica_transaksi_header" 	=> $insert_id_header,
							"baris" 					=> $i,
							"status" 					=> $status_finding,
							"date_approval" 			=> $datetime,
							"level_app"					=> $pica_status,
							'na'     					=> 'n',
							'del'     					=> 'n',
							'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'      		=> $datetime,
							'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'      		=> $datetime
						);
						
						// $update = $this->dgeneral->update("tbl_pica_transaksi_finding_approval", $data_row_finding, array(
						// 				array(
						// 					'kolom' 	=> 'id_pica_transaksi_header',
						// 					'value' 	=> $insert_id_header
						// 				),
						// 				array(
						// 					'kolom' 	=> 'baris',
						// 					'value' 	=> $i
						// 				)	
						// ));
						// echo $status_finding." - ".$pica_status." - ".$status_finding."</n>";
						$this->dgeneral->insert("tbl_pica_transaksi_finding_approval", $data_row_finding);
						$this->dgeneral->insert("tbl_pica_transaksi_finding_approval_history", $data_row_finding); 

					}
				}*/

			} else {	//input
				
				// check if exist 
				$datacheck 	= $number_fieldname;
				$datacheck2	= $pabrik_fieldname;
				$datacheck3 = $buyer_fieldname;
				$checkdata 	= $this->dtranspica->check_data_pica(NULL, NULL, NULL, 'in',$datacheck, $datacheck2, $datacheck3);
				if (count($checkdata) > 0) {
					$this->dgeneral->rollback_transaction();
					$msg    = "Duplicate data nomor pica ".$datacheck." pada pabrik ".$datacheck2." ".$datacheck3." , periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				// save transaksi header ====================================================================================
				$data_row['pica_status'] = 1;
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_pica_transaksi_header", $data_row);	

				//get last id 
				$last_id 	= $this->db->insert_id();
				$filetemp 	= 'foto_fieldname';	
				$datex      = date("Ymd")."_".date("His");
				$nm_file 	= "";
				
				// save input type file upload
				if($_FILES[$filetemp]['name'][0] != ""){
					
					//cek count file
					if (count($_FILES[$filetemp]['name'][0]) > 1) {
					  $msg    = "You can only upload maximum 1 file";
					  $sts    = "NotOK";
					  $return = array('sts' => $sts, 'msg' => $msg);
					  echo json_encode($return);
					  exit();
					}

                	$config['upload_path']   	= $this->general->kirana_file_path($this->router->fetch_module()).'/header/';
					$config['allowed_types'] 	= 'jpg|jpeg|png|pdf|zip';
					$config['max_size'] 		= 3120;
                	$nm_file 					= array($number_fieldname);
                	// $upload_error = $this->general->check_upload_file($filetemp, true);
                	// if(empty($upload_error)){
                		$files  		= $this->general->upload_files($_FILES[$filetemp], $nm_file, $config);
                	// } else {
                	// 	$files  		= "";
                	// 	$upload_error 	= $this->upload->display_errors('', '');
                	// }

                	// update detail data
                	if($last_id !== ""){
			            $data_row   = array(	                                    
	                                    "pica_file" 	=> $files[0]['file_name'],
	                                    'login_edit'    => base64_decode($this->session->userdata("-nik-")),
	                                    'tanggal_edit' 	=> $datetime	                                    
	                                );
			                $this->dgeneral->update("tbl_pica_transaksi_header", $data_row, array(
										array(
											'kolom' 	=> 'id_pica_transaksi_header',
											'value' 	=> $last_id
										)	
							));              
			        }else{
			            $msg    	= "Periksa kembali data yang dimasukkan";
			            $sts    	= "NotOK";
			            $filestring = $this->general->kirana_file_path(
			            					$this->router->fetch_module()
			            				)."/header/".$files[0]['file_name'];
			            unlink($filestring);			            				            
			        }
            	}

            	// save transaksi detail ====================================================================================
				//last insert 
				$insert_id_header 	= $last_id;
            	$loop 				= 0;
            	// save detail template 
				// loop data for insert detail
				if($baris_hidden != 0 ){
					foreach ($_POST['detail'] as $key => $value) {
						foreach($value as $iInput => $vInput){
							$id 	= $vInput['id_detail'];
							$tipe 	= $vInput['tipe'];
							// declare var 
							$id_detail_per_baris	= isset($_POST['detail'][$key][$iInput]['id_detail']) ? $_POST['detail'][$key][$iInput]['id_detail'] : 0 ;
							$baris 					= $_POST['detail'][$key][$iInput]['baris'];
							$data_detail 			= $_POST['detail'][$key][$iInput]['detail_value'];
							$data_detail_split 		= explode(",", $data_detail);
							$data_id_pica_mst_input = $data_detail_split[0];
							$data_label 			= $data_detail_split[1];
							$data_fieldname			= $data_detail_split[2];
							if($tipe == 'file'){
								$nm_file 			= isset($_FILES['detail']['name'][$key][$iInput]['value']) 
														? $_FILES['detail']['name'][$key][$iInput]['value'] 
														: 'file tidak ada' ;
								// input type file
				    			$data_row2 = array( 
											//all 
											"id_pica_transaksi_header" 	=> $insert_id_header,
											"id_pica_mst_input" 		=> $data_id_pica_mst_input,
											"baris" 					=> $baris,						
											"label" 					=> $data_label,						
											// "desc" 						=> $value,
											'na'     					=> 'n',
											'del'     					=> 'n',
											'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
											'tanggal_buat'      		=> $datetime,
											'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
											'tanggal_edit'      		=> $datetime
											);
								if($id_detail_per_baris != 0){
									unset($data_row2['login_buat'],$data_row2['tanggal_buat']);	
									$this->dgeneral->update("tbl_pica_transaksi_detail", $data_row2, 
																			array(
																				array(
																					'kolom' 	=> 'id_pica_transaksi_detail',
																					'value' 	=> $id
																				)	
																			)); 
									//get last id 
									$last_id 	= $id;
								} else {
				    				$this->dgeneral->insert("tbl_pica_transaksi_detail", $data_row2);
				    				//get last id 
									$last_id 	= $this->db->insert_id();
								}
				    			
				    			
								// $filetemp2 	= $nm_file;	
								$datex      						= date("Ymd")."_".date("His");
								$filetemp2 							= 'detail_image_'.$key.'_'.$iInput;
								$_FILES[$filetemp2]['name'][]  		= $nm_file;
								$_FILES[$filetemp2]['type'][]  		= $_FILES['detail']['type'][$key][$iInput]['value'];
								$_FILES[$filetemp2]['tmp_name'][] 	= $_FILES['detail']['tmp_name'][$key][$iInput]['value'];
								$_FILES[$filetemp2]['error'][]  	= $_FILES['detail']['error'][$key][$iInput]['value'];
								$_FILES[$filetemp2]['size'][]  		= $_FILES['detail']['size'][$key][$iInput]['value'];
								

								if($_FILES[$filetemp2]['name'][0] != ""){	
									
									// unlink gb
									$img_form_name	= $number_fieldname."_".$insert_id_header."_".$baris."_".$datex;
									// // if($img_form_name)
									// 	$imageURL 		= $this->general->kirana_file_path($this->router->fetch_module()).'/detail/'.$img_form_name;
									// 	if (@getimagesize($imageURL) !== false) {
									// 	    // display image
									// 	    // $imageURL = str_replace(""\"","/",$imageURL);
									// 	    @unlink($imageURL);
									// 	    echo "xxxxxx";
									// 	}

									// die();
									
				                	$config2['upload_path']   		= $this->general->kirana_file_path($this->router->fetch_module()).'/detail/';
				                	$config2['allowed_types'] 		= 'jpg|jpeg|png|pdf|zip';
									$config2['max_size'] 			= 3120;
				                	$nm_file2 						= array($img_form_name);
				                	// $upload_error2 					= $this->general->check_upload_file($filetemp2, true);
				                	
									
									// var_dump($upload_error2);
				                	// if($upload_error2 == NULL){
				                		$files2  		= $this->general->upload_files($_FILES[$filetemp2], $nm_file2, $config2);
				                		
				                	// } else {
				                	// 	$files2  		= "";
				                	// 	$upload_error2 	= $this->upload->display_errors('', '');
				                		
				                	// }
				                	if($last_id !== ""){
							            $data_row   = array(	                                    
					                                    "desc" 			=> $files2[0]['file_name'],
					                                    'login_edit'    => base64_decode($this->session->userdata("-nik-")),
					                                    'tanggal_edit' 	=> $datetime	                                    
					                                );
							                $this->dgeneral->update("tbl_pica_transaksi_detail", $data_row, array(
														array(
															'kolom' 	=> 'id_pica_transaksi_detail',
															'value' 	=> $last_id
														)	
											));   	              
							            
							        }else{
							            $msg    	= "Periksa kembali data yang dimasukkan";
							            $sts    	= "NotOK";
							            $filestring = $this->general->kirana_file_path(
							            					$this->router->fetch_module()
							            				)."/detail/".$files2[0]['file_name'];
							            unlink($filestring);			            				            
							        }

				            	}
				            // input type != file
							} else {
								$val 		= isset($_POST['detail'][$key][$iInput]['value']) ? $_POST['detail'][$key][$iInput]['value'] : 0;				
								$data_row2 	= array( 
											//all 
											"id_pica_transaksi_header" 	=> $insert_id_header,
											"id_pica_mst_input" 		=> $data_id_pica_mst_input,
											"baris" 					=> $baris,						
											"label" 					=> $data_label,						
											"desc" 						=> $val,
											'na'     					=> 'n',
											'del'     					=> 'n',
											'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
											'tanggal_buat'      		=> $datetime,
											'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
											'tanggal_edit'      		=> $datetime
											);
								if($id_detail_per_baris != 0){
									unset($data_row2['login_buat'],$data_row2['tanggal_buat']);	
									$this->dgeneral->update("tbl_pica_transaksi_detail", $data_row2, array(
														array(
															'kolom' 	=> 'id_pica_transaksi_detail',
															'value' 	=> $id
														)	
											)); 
								} else {
				    				$this->dgeneral->insert("tbl_pica_transaksi_detail", $data_row2);
								}	
				    		}
						}
					}
					// loop data for insert approval & approval his per finding
					if($baris_hidden != 0){

						// log outstand log
							$date 					= date("Y-m-d");
							$action 				= 'submit';
							$data_rowlog = array(
								//all 
								"id_pica_header"	=> $insert_id_header,		
								"number"			=> $number_fieldname,	
								"date_action"		=> $date,	
								"action"			=> $action,
								"pic"				=> base64_decode($this->session->userdata("-nik-")),	
								"posst"				=> base64_decode($this->session->userdata("-posst-")),
								// "comment"			=> $desc,	
								"pica_status"		=> 1,	
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
				            $log = $this->dgeneral->insert("tbl_pica_log", $data_rowlog);
				            // echo $log;
				            // exit();
						// end log

						for($i = 1 ; $i <= $baris_hidden; $i++ ){
							$status_finding 				= (!empty($_POST['actionapp_'.$i]))?$_POST['actionapp_'.$i]:0;
							$data_row_finding = array(
								//all 
								// "id_pica_jenis_temuan" 	=> $id_temuan,
								"id_pica_transaksi_header" 	=> $insert_id_header,
								"baris" 					=> $i,
								"status" 					=> $status_finding,
								"date_approval" 			=> $datetime,
								"level_app" 				=> 1,
								'na'     					=> 'n',
								'del'     					=> 'n',
								'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      		=> $datetime,
								'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      		=> $datetime
							);
							$this->dgeneral->insert("tbl_pica_transaksi_finding_approval", $data_row_finding);
							$this->dgeneral->insert("tbl_pica_transaksi_finding_approval_history", $data_row_finding); 
						}

						
				            // echo json_encode($data_row2);
				            // exit();
						// mail
						
							$email_action 		= 'melengkapi data';
							$data_posisi 		= $this->dtranspica->get_data_akses_pabrik(NULL, NULL, $id_temuan, 1,'single');
							// echo json_encode($data_posisi)."\/n";
							$check_posisi_loc=[];
							if($data_posisi != null) {
								$check_posisi_loc 	= $this->dtranspica->check_data_posisi(NULL,  $data_posisi->id_posisi);
								foreach ($check_posisi_loc as $dt_check_loc) {						
									$loc			= $dt_check_loc->id_gedung;
								}
							} else {
								$msg    = "periksa kembali master role penerima pica selanjutnya";
								$sts    = "NotOK";
								$return = array('sts' => $sts, 'msg' => $msg);
								echo json_encode($return);
								exit();	
							} 
							// echo "lokasi_awal = ".$pabrik;
							if(count($check_posisi_loc) == 1 && $loc == 'ho'){
								$pabrik_fieldname 		= NULL;
							} 
							
							$list_email 		= array();
							$list_nama 			= array();
							$list_nik 			= array();
							// get data karyawan
							$data = $this->dtranspica->get_data_karyawan(NULL, $data_posisi->id_posisi , $pabrik_fieldname);
							foreach ($data as $dt) {
								$list_email[]	= $dt->email;
								$list_nama[]	= $dt->nama_karyawan;
								$list_nik[] 	= $dt->nik;
							}
							$list_email = array_filter($list_email, create_function('$value', 'return $value !== "";'));
							
							// get posisi
							$data_log		= $this->dtranspica->get_data_pica_list_data_log(NULL,$insert_id_header,'only active',$list_nik,'pic');

							$nik_log 		= array();
							$email_cc 		= array();
							foreach ($data_log as $dt_log) {
								if( !in_array($dt_log->pic, $nik_log) && !in_array($list_nik, $nik_log) ){
									$nik_log[]	= $dt_log->pic;
									$email_cc[] = $dt_log->email;
								}
							}
							// echo "to = ".json_encode($list_email);
							// echo "cc = ".json_encode($email_cc);
						// aktifkan ketika live ayy
							$this->lpica->send_email(
		                        array(
		                            'judul' 			=> "Pica Online",
		                            'email_pengirim' 	=> "KiranaKu",
		                            'email_tujuan' 		=> $list_email,//'airiza.perdana@kiranamegatara.com',
		                            'email_cc' 			=> $email_cc,
		                            'view' 				=> 'email/next_approval',
		                            'data' 				=> array(
						                                'number' 	=> $number_fieldname,
						                                'action' 	=> $email_action,
						                                'to_mail'	=> $list_nama

		                            )
		                        )
		                    );
							
						// end mail

						
					}	
				} else {
					$this->dgeneral->rollback_transaction();
					$msg    = "Lengkapi data detail , buat template terlebih dahulu.";
					$sts    = "NotOK";
					$this->general->closeDb();
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}			
			}
			// exit();
			// echo $this->dgeneral->status_transaction();
			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				// $this->dgeneral->rollback_transaction();
				$msg = "Data berhasil ditambahkan";
				$sts = "OK";
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}
	
	}

?>
