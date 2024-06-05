<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	/*
		@application  : Kirana Event 
		@author       : Airiza Yuddha (7849)
		@contributor  :
			  1. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
    */

	Class Master extends MX_Controller {
		// private $data;
		function __construct() {
			parent::__construct();
			$this->load->model('dmasterkiranaevent');

			/*load model*/
			$this->load->model('dgeneral');
			
		}

		public function index(){
			show_404();
		}

		

		/*================================Relationship KiranaEvent====================================*/
		public function relationship() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "Portal Event";
			$data['title']      	= "Master Hubungan Keluarga";
			$data['title_form'] 	= "Setting Hubungan Keluarga";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			

			$this->load->view("master/relationship", $data);
		}

		/*================================Type News KiranaEvent====================================*/
		public function type() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "Portal Event";
			$data['title']      	= "Master tipe berita";
			$data['title_form'] 	= "Setting tipe berita";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			

			$this->load->view("master/type", $data);
		}

		/*================================IMG Template News KiranaEvent====================================*/
		public function template() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "Portal Event";
			$data['title']      	= "Master Template Gambar Berita";
			$data['title_form'] 	= "Setting Template Gambar Berita";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			$data['type']       	= $this->dmasterkiranaevent->get_master_type();
			

			$this->load->view("master/template", $data);
		}

		// kpr
		// tomas 
		// ashari

		// data vendor ada penambahan - record update by system
		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//
		
		public function get($param = NULL) {
			
			switch ($param) {
				
				case 'data_relationship': // for relationship master
					$id_hubungan= (isset($_POST['id_hubungan']) ? $this->generate->kirana_decrypt($_POST['id_hubungan']) : NULL);
					$active     = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_relationship(NULL, $id_hubungan, $active, $deleted);
					break;

				case 'data_type': // for type news master
					$id_type 	= (isset($_POST['id_typeberita']) ? $this->generate->kirana_decrypt($_POST['id_typeberita']) : NULL);
					$active     = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_type(NULL, $id_type, $active, $deleted);
					break;
				case 'data_template': // for type news master
					$id_template 	= (isset($_POST['id_templategb']) ? $this->generate->kirana_decrypt($_POST['id_templategb']) : NULL);
					$active     = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_type(NULL, $id_template, $active, $deleted);
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
					
					case 'relationship':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_eventhr_mst_hubungan", array(
							array(
								'kolom' => 'id_hubungan',
								'value' => $this->generate->kirana_decrypt($_POST['kode'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'type':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_eventhr_mst_typeberita", array(
							array(
								'kolom' => 'id_typeberita',
								'value' => $this->generate->kirana_decrypt($_POST['kode'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'template':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_eventhr_mst_templategb", array(
							array(
								'kolom' => 'id_templategb',
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
				
				case 'relationship':
					$this->save_relationship();
					break;
				case 'type':
					$this->save_type();
					break;
				case 'template':
					$this->save_template();
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

		/*================================relationship kiranaevent====================================*/
		
		public function get_relationship($array = NULL, $id_relationship = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL,$exceptioncheck = NULL, $relationcheck1 = NULL, $relationcheck2 = NULL) {
			$hubungan = $this->dmasterkiranaevent->get_master_relationship("open", $id_relationship, $active, $deleted, $typecheck, $exceptioncheck, $relationcheck1, $relationcheck2);
			$hubungan = $this->general->generate_encrypt_json($hubungan, array("id_hubungan"));
			if ($array) {
				return $hubungan;
			} else {
				echo json_encode($hubungan);
			}
		}
		

		private function save_relationship() {
			$nik = base64_decode($this->session->userdata("-nik-"));
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$hubungan	= !empty($_POST['relationship_fieldname']) ? $_POST['relationship_fieldname'] : NULL ;
			$keterangan	= !empty($_POST['desc_fieldname']) ? $_POST['desc_fieldname'] : NULL ;
			if (isset($_POST['id_relationship']) && trim($_POST['id_relationship']) !== "") {
				
					// check if exist
					$relationship = $this->get_relationship('array', NULL, NULL, 'n', 'up', $this->generate->kirana_decrypt($_POST['id_relationship']), $hubungan );
					if (count($relationship) > 0) {
						$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}

					$data_row = array(
						"hubungan" 		=> $hubungan,		
						"keterangan" 	=> $keterangan,	
								
						"login_edit"    => $nik,
						"tanggal_edit"  => $datetime
					);
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_eventhr_mst_hubungan", $data_row, array(
						array(
							'kolom' => 'id_hubungan',
							'value' => $this->generate->kirana_decrypt($_POST['id_relationship'])
						)
					));
				
							
			// insert
			} else {

				// check if exist
				$relationship = $this->get_relationship('array', NULL, NULL, 'n', 'in', NULL ,$hubungan);
				if (count($relationship) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				$data_row = array(
					"hubungan" 			=> $hubungan,		
					"keterangan" 		=> $keterangan,

					"login_buat"      	=> $nik,
					"tanggal_buat"    	=> $datetime,
					"login_edit"      	=> $nik,
					"tanggal_edit"    	=> $datetime
				);
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_eventhr_mst_hubungan", $data_row);
				


				
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

		/*================================Type News kiranaevent====================================*/
		
		public function get_type($array = NULL, $id_type = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL,$exceptioncheck = NULL, $typeberitacheck1 = NULL, $typeberitacheck2 = NULL) {
			$type = $this->dmasterkiranaevent->get_master_type("open", $id_type, $active, $deleted, $typecheck, $exceptioncheck, $typeberitacheck1, $typeberitacheck2);
			$type = $this->general->generate_encrypt_json($type, array("id_typeberita"));
			if ($array) {
				return $type;
			} else {
				echo json_encode($type);
			}
		}
		

		private function save_type() {
			
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$type		= !empty($_POST['type_fieldname']) ? $_POST['type_fieldname'] : NULL ;
			$keterangan	= !empty($_POST['desc_fieldname']) ? $_POST['desc_fieldname'] : NULL ;
			if (isset($_POST['id_typeberita']) && trim($_POST['id_typeberita']) !== "") {
				
					// check if exist
					$type_chk = $this->get_type('array', NULL, NULL, 'n', 'up', $this->generate->kirana_decrypt($_POST['id_typeberita']), $type );
					if (count($type_chk) > 0) {
						$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}

					$data_row = array(
						"type_berita" 	=> $type,		
						"keterangan" 	=> $keterangan,	
								
						"login_edit"    => base64_decode($this->session->userdata("-nik-")),
						"tanggal_edit"  => $datetime
					);
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_eventhr_mst_typeberita", $data_row, array(
						array(
							'kolom' => 'id_typeberita',
							'value' => $this->generate->kirana_decrypt($_POST['id_typeberita'])
						)
					));
				
							
			// insert
			} else {

				// check if exist
				$type_chk = $this->get_type('array', NULL, NULL, 'n', 'in', NULL ,$type);
				if (count($type_chk) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				$data_row = array(
					"type_berita" 		=> $type,		
					"keterangan" 		=> $keterangan,

					"login_buat"      	=> base64_decode($this->session->userdata("-nik-")),
					"tanggal_buat"    	=> $datetime,
					"login_edit"      	=> base64_decode($this->session->userdata("-nik-")),
					"tanggal_edit"    	=> $datetime
				);
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_eventhr_mst_typeberita", $data_row);
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

		/*================================Template GB News kiranaevent====================================*/
		
		public function get_template($array = NULL, $id_template = NULL, $active = NULL, $deleted = NULL, $typecheck = NULL,$exceptioncheck = NULL, $templatecheck1 = NULL, $templatecheck2 = NULL) {
			$type = $this->dmasterkiranaevent->get_master_type("open", $id_template, $active, $deleted, $typecheck, $exceptioncheck, $templatecheck1, $templatecheck2);
			$type = $this->general->generate_encrypt_json($type, array("id_templategb"));
			if ($array) {
				return $type;
			} else {
				echo json_encode($type);
			}
		}
		

		private function save_template() {
			
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$id_type	= !empty($_POST['type_fieldname']) ? $_POST['type_fieldname'] : NULL ;
			$keterangan	= !empty($_POST['desc_fieldname']) ? $_POST['desc_fieldname'] : NULL ;
			if (isset($_POST['id_templategb']) && trim($_POST['id_templategb']) !== "") {
				
					// check if exist
					$template_chk = $this->get_type('array', NULL, NULL, 'n', 'up', $this->generate->kirana_decrypt($_POST['id_templategb']), $type );
					if (count($template_chk) > 0) {
						$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}

					$data_row = array(
						"id_typeberita" => $id_type,		
						"keterangan" 	=> $keterangan,	
								
						"login_edit"    => base64_decode($this->session->userdata("-nik-")),
						"tanggal_edit"  => $datetime
					);
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_eventhr_mst_templategb", $data_row, array(
						array(
							'kolom' => 'id_templategb',
							'value' => $this->generate->kirana_decrypt($_POST['id_templategb'])
						)
					));
				
							
			// insert
			} else {

				// check if exist
				$template_chk = $this->get_type('array', NULL, NULL, 'n', 'in', NULL ,$type);
				if (count($template_chk) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
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
				$data_row = array(
					"id_typeberita" 	=> $id_type,		
					"keterangan" 		=> $keterangan,

					"login_buat"      	=> base64_decode($this->session->userdata("-nik-")),
					"tanggal_buat"    	=> $datetime,
					"login_edit"      	=> base64_decode($this->session->userdata("-nik-")),
					"tanggal_edit"    	=> $datetime
				);
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_eventhr_mst_templategb", $data_row);


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


	}

?>
