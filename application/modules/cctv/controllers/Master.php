<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	/*
		@application  : Monitoring CCTV 
		@author       : Airiza Yuddha (7849)
		@contributor  :
			  1. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
    */

	//include_once APPPATH . "modules/umb/controllers/BaseControllers.php";

	Class Master extends MX_Controller {
		// private $data;
		function __construct() {
			parent::__construct();
			$this->load->model('dmastercctv');

			/*load model*/
			$this->load->model('dgeneral');
			
		}

		public function index(){
			show_404();
		}

		/*================================DOT CCTV====================================*/
		public function dot() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "CCTV";
			$data['title']      	= "Master Titik CCTV";
			$data['title_form'] 	= "Setting Titik CCTV";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			$data['lokasi'] 		= $this->dmastercctv->get_master_sublokasi("portal", NULL, NULL, NULL, "pabrik");

			//get oto
			$ho						= base64_decode($this->session->userdata("-ho-"));
			if($ho=='n'){
        		$pabrik = base64_decode($this->session->userdata("-gsber-"));
        		$id_lokasi_array 		= array(1);
        	} else {
        		$pabrik = NULL;
        		$id_lokasi_array 		= array(1,2,3);
        	}        	

			$data['lokasi_parent']	= $this->dmastercctv->get_master_lokasi("portal",$id_lokasi_array,'n');
			$data['plant'] 			= $this->dgeneral->get_master_plant($pabrik);
			// $data['plant'] 			= $this->dgeneral->get_master_plant();
			$data['dot']    		= $this->get_dot('array', $id_mdot = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $exceptioncheck = NULL, $dotcheck1 = NULL, $dotcheck2 = NULL, $dotcheck3 = NULL, $pabrik);
			// $data['dot'] = $this->dmastercctv->get_master_dot();
			// $dot = $this->general->generate_encrypt_json($dot, "id_mdot");
			

			$this->load->view("master/dot", $data);
		}

		/*================================Criteria CCTV====================================*/
		public function criteria() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "CCTV";
			$data['title']      	= "Master Kriteria Pencapaian CCTV";
			$data['title_form'] 	= "Setting Kriteria Pencapaian CCTV";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			$data['lokasi'] 		= $this->dmastercctv->get_master_sublokasi("portal", NULL, NULL, NULL, "pabrik");
			$data['css'] 			= $this->dmastercctv->get_master_csscolor("portal", NULL, 'n', NULL, "span-badges");

			$data['criteria']    		= $this->get_criteria('array');
			// $data['dot'] = $this->dmastercctv->get_master_dot();
			// $dot = $this->general->generate_encrypt_json($dot, "id_mdot");
			

			$this->load->view("master/criteria", $data);
		}

		

		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//
		
		public function get($param = NULL) {
			//get oto
			$ho						= base64_decode($this->session->userdata("-ho-"));
			if($ho=='n'){
        		$pabrik = base64_decode($this->session->userdata("-gsber-"));
        	} else {
        		$pabrik = NULL;
        	}        	
			// $data['plant'] 			= $this->dgeneral->get_master_plant($pabrik);
			switch ($param) {
				
				case 'dot': // for dot master
					$id_mdot 	= (isset($_POST['id_mdot']) ? $this->generate->kirana_decrypt($_POST['id_mdot']) : NULL);
					$active     = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_dot(NULL, $id_mdot, $active, $deleted, $typecheck = NULL, $exceptioncheck = NULL, $dotcheck1 = NULL, $dotcheck2 = NULL, $dotcheck3 = NULL, $pabrik);
					break;
				case 'criteria': // for criteria master
					$id_criteriaAchv 	= (isset($_POST['id_criteriaAchv']) ? $this->generate->kirana_decrypt($_POST['id_criteriaAchv']) : NULL);
					$active     = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_criteria(NULL, $id_criteriaAchv, $active, $deleted);
					break;
				case 'mdot': // for get dot on monitoring report achieve
					$id_mdot 	= (isset($_POST['id_mdot']) ? $this->generate->kirana_decrypt($_POST['id_mdot']) : NULL);
					$active     = 'n';
					$deleted    = 'n';
					$pabrik2 	= (!empty($_POST['pabrik']) ? $_POST['pabrik'] : $pabrik);
					$this->get_mdot(NULL, $id_mdot, $active, $deleted, $typecheck = NULL, $exceptioncheck = NULL, $dotcheck1 = NULL, $dotcheck2 = NULL, $dotcheck3 = NULL, $pabrik2);
					break;
				case 'mcriteria': // for get dot on monitoring report achieve
					$id_mcriteria 	= (isset($_POST['id_mcriteria']) ? $this->generate->kirana_decrypt($_POST['id_mcriteria']) : NULL);
					$valcriteria 	= (isset($_POST['persen']) ? $_POST['persen'] : NULL);
					$active     = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_mcriteria(NULL, $id_mcriteria, $active = NULL, $deleted = NULL, $typecheck = NULL, $exceptioncheck = NULL,$criteriacheck1 = NULL, $criteriacheck2 = NULL, $criteriacheck3 = NULL,$valcriteria);
					break;
				case 'criteria': // for criteria master
					$id_criteriaAchv 	= (isset($_POST['id_criteriaAchv']) ? $this->generate->kirana_decrypt($_POST['id_criteriaAchv']) : NULL);
					$active     = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_criteria(NULL, $id_criteriaAchv, $active, $deleted);
					break;
				case 'sublokasi':
					$data_lokasi	= (isset($_POST['lokasi']) ? $_POST['lokasi'] : NULL);
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$sublok 		= $this->dmastercctv->get_master_sublokasi("portal", NULL, NULL, NULL, NULL,NULL,NULL, NULL, $data_lokasi);
					echo json_encode($sublok);
					break;
				case 'area':
					$data_sublokasi	= (isset($_POST['sublokasi']) ? $_POST['sublokasi'] : NULL);
					$active     	= (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    	= (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$area 			= $this->dmastercctv->get_master_area("portal",$data_sublokasi,'n');;
					echo json_encode($area);
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
					
					case 'dot':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_cctv_mdot", array(
							array(
								'kolom' => 'id_mdot',
								'value' => $this->generate->kirana_decrypt($_POST['kode'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'criteria':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_cctv_criteria_achv", array(
							array(
								'kolom' => 'id_criteriaAchv',
								'value' => $this->generate->kirana_decrypt($_POST['kode'])
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
				
				case 'dot':
					$this->save_dot();
					break;
				case 'criteria':
					$this->save_criteria();
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
		public function get_dot($array = NULL, $id_mdot = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL,$exceptioncheck = NULL, $dotcheck1 = NULL, $dotcheck2 = NULL, $dotcheck3 = NULL, $pabrik= NULL) {
			$dot = $this->dmastercctv->get_master_dot("open", $id_mdot, $active, $deleted, $typecheck, $exceptioncheck, $dotcheck1, $dotcheck2, $dotcheck3, $pabrik);
			$dot = $this->general->generate_encrypt_json($dot, array("id_mdot"));
			if ($array) {
				return $dot;
			} else {
				echo json_encode($dot);
			}
		}
		public function get_mdot($array = NULL, $id_mdot = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL,$exceptioncheck = NULL , $dotcheck1 = NULL, $dotcheck2 = NULL, $dotcheck3 = NULL, $pabrik= NULL) {
			$dot = $this->dmastercctv->get_master_dot("open", $id_mdot, $active, $deleted, $typecheck,$exceptioncheck, $dotcheck1, $dotcheck2, $dotcheck3, $pabrik);
			// $dot = $this->general->generate_encrypt_json($dot, array("id_mdot"));
			if ($array) {
				return $dot;
			} else {
				echo json_encode($dot);
			}
		}

		private function save_dot() {
			
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$dot 		= !empty($_POST['dot_fieldname']) ? $_POST['dot_fieldname'] : NULL ;
			$lokasi1	= !empty($_POST['lokasi_fieldname_parent']) ? $_POST['lokasi_fieldname_parent'] : NULL ;
			$lokasi_sub	= !empty($_POST['lokasi_fieldname']) ? $_POST['lokasi_fieldname'] : NULL ;
			$area 		= !empty($_POST['area_fieldname']) ? $_POST['area_fieldname'] : NULL ;
			$pabrik 	= !empty($_POST['pabrik_fieldname']) ? $_POST['pabrik_fieldname'] : NULL ;

			if (isset($_POST['id_mdot']) && trim($_POST['id_mdot']) !== "") {
				for($i=0; $i<count($pabrik); $i++){	
					// check if exist
					$doting = $this->get_dot('array', NULL, NULL, 'n', 'up', $this->generate->kirana_decrypt($_POST['id_mdot']), $dot, $lokasi_sub, $pabrik[$i] );
					if (count($doting) > 0) {
						$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}

					$data_row = array(
						"dot" 			=> $_POST['dot_fieldname'],		
						"id_sublokasi" 	=> $_POST['lokasi_fieldname'],	
						"id_lokasi" 	=> $lokasi1,
						"id_area" 		=> $area,		
						"login_edit"    => base64_decode($this->session->userdata("-nik-")),
						"tanggal_edit"  => $datetime
					);
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_cctv_mdot", $data_row, array(
						array(
							'kolom' => 'id_mdot',
							'value' => $this->generate->kirana_decrypt($_POST['id_mdot'])
						)
					));
				}
							
			// insert
			} else {

				for($i=0; $i<count($pabrik); $i++){					
					// check if exist
					$doting = $this->get_dot('array', NULL, NULL, 'n', 'in', NULL ,$dot,$lokasi_sub,$pabrik[$i]);
					// echo json_encode($doting)."|".count($doting)."-".$dot.",".$lokasi.",".$pabrik[$i];
					// exit();
					if (count($doting) > 0) {
						$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$data_row = array(
						"dot" 				=> $dot,
						"id_sublokasi" 		=> $lokasi_sub,
						"id_lokasi" 		=> $lokasi1,
						"id_area" 			=> $area,

						"plant"				=> $pabrik[$i],
						"login_buat"      	=> base64_decode($this->session->userdata("-nik-")),
						"tanggal_buat"    	=> $datetime,
						"login_edit"      	=> base64_decode($this->session->userdata("-nik-")),
						"tanggal_edit"    	=> $datetime
					);
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_cctv_mdot", $data_row);
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

		/*================================Criteria CCTV====================================*/
		public function get_criteria($array = NULL, $id_criteriaAchv = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL, $exceptioncheck = NULL,$criteriacheck1 = NULL, $criteriacheck2 = NULL, $criteriacheck3 = NULL) {
			$criteria = $this->dmastercctv->get_master_criteria("open", $id_criteriaAchv, $active, $deleted, $typecheck, $exceptioncheck, $criteriacheck1, $criteriacheck2, $criteriacheck3);
			$criteria = $this->general->generate_encrypt_json($criteria, array("id_criteriaAchv"));
			if ($array) {
				return $criteria;
			} else {
				echo json_encode($criteria);
			}
		}
		
		public function get_mcriteria($array = NULL, $id_criteriaAchv = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL, $exceptioncheck = NULL,$criteriacheck1 = NULL, $criteriacheck2 = NULL, $criteriacheck3 = NULL, $persen = NULL) {
			$mcriteria = $this->dmastercctv->get_master_criteria("open", $id_criteriaAchv, 'n', $deleted, $typecheck, $exceptioncheck, $criteriacheck1 = NULL, $criteriacheck2 = NULL, $criteriacheck3 = NULL, $persen);
			// $dot = $this->general->generate_encrypt_json($dot, array("id_mdot"));
			if ($array) {
				return $mcriteria;
			} else {
				echo json_encode($mcriteria);
			}
		}

		private function save_criteria() {
			
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$criteria_name 	= isset($_POST['kriteria_fieldname']) ? $_POST['kriteria_fieldname'] : NULL;
			$criteria_min 	= isset($_POST['min_fieldname']) ? $_POST['min_fieldname'] : NULL;
			$criteria_max 	= isset($_POST['max_fieldname']) ? $_POST['max_fieldname'] : NULL;
			$criteria_class = isset($_POST['warna_fieldname']) ? $_POST['warna_fieldname'] : NULL;
			if (isset($_POST['id_criteriaAchv']) && trim($_POST['id_criteriaAchv']) !== "") {
				// check if exist
				$criteria = $this->get_criteria('array', NULL, NULL, 'n', 'up', $this->generate->kirana_decrypt($_POST['id_criteriaAchv']), $criteria_min, $criteria_max );
				if (count($criteria) > 0) {
					$msg    = "Terdapat data sudah dalam range, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"criteria" 		=> $criteria_name,		
					"val_min" 		=> $criteria_min,
					"val_max" 		=> $criteria_max,		
					"id_css" 		=> $criteria_class,			
					"login_edit"    => base64_decode($this->session->userdata("-nik-")),
					"tanggal_edit"  => $datetime
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_cctv_criteria_achv", $data_row, array(
					array(
						'kolom' => 'id_criteriaAchv',
						'value' => $this->generate->kirana_decrypt($_POST['id_criteriaAchv'])
					)
				));
							
			// insert
			} else {
				// echo $criteria_min.", ".$criteria_max;
				$criteria = $this->get_criteria('array', NULL, NULL, 'n', 'in', NULL, $criteria_min, $criteria_max);
				if (count($criteria) > 0) {
					$msg    = "Terdapat data sudah dalam range, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"criteria" 			=> $criteria_name,		
					"val_min" 			=> $criteria_min,
					"val_max" 			=> $criteria_max,		
					"id_css" 			=> $criteria_class,	
					"login_buat"      	=> base64_decode($this->session->userdata("-nik-")),
					"tanggal_buat"    	=> $datetime,
					"login_edit"      	=> base64_decode($this->session->userdata("-nik-")),
					"tanggal_edit"    	=> $datetime
				);

				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_cctv_criteria_achv", $data_row);
				
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



	}

?>
