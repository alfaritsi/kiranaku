<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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
	Class Master extends BaseControllers {
		// private $data;
		function __construct() {
			parent::__construct();
			$this->load->model('dmasterpica');
			
			/*load model*/
			$this->load->model('dgeneral');
		}

		public function index(){
			show_404();
		}

		/*================================ Temuan ====================================*/
		public function temuan() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "PICA";
			$data['title']      	= "Master Jenis Temuan ";
			$data['title_form'] 	= "Tambah Jenis Temuan ";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			
			$this->load->view("master/temuan", $data);
		}

		/*================================ Role ====================================*/
		public function role() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "PICA";
			$data['title']      	= "Master Role";
			$data['title_form'] 	= "Tambah Role";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			$data['rolename']      	= $this->dmasterpica->get_data_rolename();
			$data['role'] 			= $this->dmasterpica->get_data_pica_role_normal("portal", NULL,"only active");
			$data['temuan']       	= $this->dmasterpica->get_data_pica_temuan_normal('portal',NULL, 'only active');
			$this->load->view("master/role", $data);
		}

		/*================================ Role Posisi ====================================*/
		public function roleposisi() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "PICA";
			$data['title']      	= "Setting Role Posisi";
			$data['title_form'] 	= "Setting Role Posisi";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			// $data['plant']       	= $this->dgeneral->get_master_plant();
			$data['plant']       	= $this->dmasterpica->get_master_plant();
			$data['posisi']       	= $this->dmasterpica->get_data_pica_posisi('portal', NULL, 'only active');
			$data['role'] 			= $this->dmasterpica->get_data_pica_role_normal("portal", NULL,"only active");
			
			$this->load->view("master/roleposisi", $data);
		}

		/*================================ jenis Report ====================================*/
		public function report() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "PICA";
			$data['title']      	= "Master Jenis Report";
			$data['title_form'] 	= "Tambah Jenis Report";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			$data['plant']       	= $this->dgeneral->get_master_plant();
			$data['temuan']       	= $this->dmasterpica->get_data_pica_temuan_normal('portal',NULL, 'only active');
			$data['posisi']       	= $this->dmasterpica->get_data_pica_posisi('portal', NULL, 'only active');
			
			$this->load->view("master/jenisreport", $data);
		}

		/*================================ jenis Report ====================================*/
		public function template() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "PICA";
			$data['title']      	= "Master Template";
			
			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			$data['plant']       	= $this->dgeneral->get_master_plant();
			$data['temuan']       	= $this->dmasterpica->get_data_pica_temuan_normal('portal',NULL, 'only active');
			$data['posisi']       	= $this->dmasterpica->get_data_pica_posisi('portal', NULL, 'only active');
			
			$this->load->view("master/templatereport", $data);
		}

		public function input($param = NULL) {
			switch ($param) {
				
				case 'template' : // jenis temuan master
					// $this->general->check_access();
					//===============================================/
					$data['module'] 		= "PICA";
					$data['title']      	= "Tambah Template";
					
					/*load global attribute*/
					$data['generate']     	= $this->generate;
					$data['module']       	= $this->router->fetch_module();
					$data['user']       	= $this->general->get_data_user();
					$data['plant']       	= $this->dgeneral->get_master_plant();
					$data['temuan']       	= $this->dmasterpica->get_data_pica_temuan_normal('portal',NULL, 'only active');
					$data['jenis_report']   = $this->dmasterpica->get_data_pica_jenisreport_normal('portal',NULL, 'only active');
					$data['buyer'] 			= $this->dmasterpica->get_data_pica_buyer('portal',NULL, 'only active');
					
					$this->load->view("master/templatereport_input", $data);
					break;
				
				default 		:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}
	
//=================================//
//		  PROCESS FUNCTION 		   //
//=================================//
		
		public function get($param = NULL) {
	
			switch ($param) {
				
				case 'temuan'				: // jenis temuan master
					$id_temuan 		= (isset($_POST['id_temuan']) ? $this->generate->kirana_decrypt($_POST['id_temuan']) : NULL);
					$this->pica_temuan('portal', $id_temuan);
					break;
				
				case 'role'					: // role master
					$id_role 		= (isset($_POST['id_role']) ? $this->generate->kirana_decrypt($_POST['id_role']) : NULL);
					$this->pica_role('portal', $id_role);
					break;
				
				case 'role_data'			: // role master for roleposisi 
					$id_role 		= (isset($_POST['id_role']) ?$_POST['id_role'] : NULL);
					$this->pica_role('portal', $id_role);
					break;
				
				case 'roleposisi'			: // role posisi master
					$role 			= (isset($_POST['role']) ? $this->generate->kirana_decrypt($_POST['role']) : NULL);
					$this->pica_roleposisi('portal', $role);
					break;
				
				case 'jenisreport'			: // jenis report master
					$id_jenisreport = (isset($_POST['jenisreport']) ? $this->generate->kirana_decrypt($_POST['jenisreport']) : NULL);
					$this->pica_jenisreport('portal', $id_jenisreport);
					break;
				
				case 'workflow'				: // responder jenis report master
					$id 	= (isset($_POST['id']) ? $_POST['id'] : NULL);
					$type 	= (isset($_POST['type']) ? $_POST['type'] : NULL);
					$this->pica_workflow('portal', $id, $type);
					break;
				
				case 'templatereport'		: // template report master
					$id_templatereport =(isset($_POST['templatereport']) ? $this->generate->kirana_decrypt($_POST['templatereport']) : NULL);
					$this->pica_jenisreport('portal', $id_templatereport);
					break;	
				
				case 'detail_form'			: // jenis report master
					$detail_form = (isset($_POST['detail_form']) ? $this->generate->kirana_decrypt($_POST['detail_form']) : NULL);
					$this->pica_detail_form('portal', $detail_form);
					break;
				
				case 'templatereport_normal': // Template report master
					$id = (isset($_POST['id_header']) ? $this->generate->kirana_decrypt($_POST['id_header']) : NULL);
					$this->pica_templatereport_normal('portal', $id);
					break;		
				
				default 					:
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
					
					case 'temuan'			:
						$this->general->connectDbPortal();
						// echo "xxxx";
						$return = $this->general->set($action, "tbl_pica_jenis_temuan", array(
							array(
								'kolom' => 'id_pica_jenis_temuan',
								'value' => $this->generate->kirana_decrypt($_POST['id'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					
					case 'role'				:
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_pica_role", array(
							array(
								'kolom' => 'id_pica_role',
								'value' => $this->generate->kirana_decrypt($_POST['id'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					
					case 'roleposisi'		:
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_pica_role_posisi", array(
							array(
								'kolom' => 'id_pica_role_posisi',
								'value' => $this->generate->kirana_decrypt($_POST['id'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					
					case 'jenisreport'		:
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_pica_jenis_report", array(
							array(
								'kolom' => 'id_pica_jenis_report',
								'value' => $this->generate->kirana_decrypt($_POST['id'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;

					case 'templatereport'	:
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_pica_template_header", array(
							array(
								'kolom' => 'id_pica_template_header',
								'value' => $this->generate->kirana_decrypt($_POST['id'])
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
				
				case 'temuan'			:
					$this->save_temuan($param);
					break;
				
				case 'role'				:
					$this->save_role($param);
					break;
				
				case 'roleposisi'		:
					$this->save_roleposisi($param);
					break;
				
				case 'jenisreport'		:
					$this->save_jenisreport($param);
					break;
				
				case 'templatereport'	:
					$this->save_templatereport($param);
					break;
				
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

	/**********************************/
	/*			  private  			  */
	/**********************************///==========================//

	/*================================ Temuan ====================================*/
	    public function pica_temuan($conn=NULL,$id=NULL){
        	$data = $this->dmasterpica->get_data_pica_temuan('portal',$id);
            echo $data;	        
	    }

	    private function save_temuan($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$jenis_temuan = (!empty($_POST['jenis_temuan']))?trim($_POST['jenis_temuan']):0;
			$kode_temuan = (!empty($_POST['kode_temuan']))?trim($_POST['kode_temuan']):0;
			$requestor = (!empty($_POST['requestor']))?trim($_POST['requestor']):0;
			
			$data_row = array(
					//all 
					"jenis_temuan" 			=> $jenis_temuan,
					"requestor" 			=> $requestor,
					"kode_temuan" 			=> $kode_temuan,
					'na'     				=> 'n',
					'del'     				=> 'n',
					'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'      	=> $datetime,
					'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      	=> $datetime
				);

			if (isset($_POST['id_temuan']) && trim($_POST['id_temuan']) !== "") { // edit	
				$id_edit 	= $this->generate->kirana_decrypt($_POST['id_temuan']);
				// check if exist 
				$datacheck 	= $jenis_temuan;
				$datacheck2	= $requestor;
				$checkdata 	= $this->dmasterpica->get_data_pica_temuan_normal(NULL, $id_edit, NULL, 'up',$datacheck, $datacheck2);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data jenis temuan ".$jenis_temuan.", periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				unset($data_row['login_buat'],$data_row['tanggal_buat']);
				$this->dgeneral->update("tbl_pica_jenis_temuan", $data_row, array(
					array(
						'kolom' => 'id_pica_jenis_temuan',
						'value' => $id_edit
					)
				));
			} else {	//input
				
				// check if exist 
				$datacheck 	= $jenis_temuan;
				$datacheck2 = $requestor;
				$checkdata = $this->dmasterpica->get_data_pica_temuan_normal(NULL, NULL, NULL, 'in',$datacheck, $datacheck2);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data jenis temuan ".$jenis_temuan.", periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_pica_jenis_temuan", $data_row);	
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

	/*================================ Role ====================================*/
	    public function pica_role($conn=NULL,$id=NULL,$all=NULL){
        	$data = $this->dmasterpica->get_data_pica_role('portal',$id,$all);
            echo $data;
	    }

	    public function pica_role_normal($conn=NULL,$id=NULL,$all=NULL){	// for get role
    		$temuan = !isset($_POST['id_temuan']) ? NULL : $_POST['id_temuan'];	      
            $data = $this->dmasterpica->get_data_pica_role_normal("portal", NULL,"only active",NULL,NULL,NULL,$temuan);
            // $data = $this->general->generate_encrypt_json($data, array("id_pica_template_header"));
            // $conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL,$datacheck_2=NULL,$id_temuan=NULL
            echo json_encode($data);
	    }

	    public function user_data(){
	        if(isset($_GET['q'])){
	            $user       = $this->dmasterpica->get_data_user($_GET['q']);
	            $data_user  = array(
	                            "total_count" => count($user),
	                            "incomplete_results"=>false,
	                            "items"=>$user
	                          );
	            echo json_encode($data_user);
	        }
	    }

	    private function save_role($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$jenis_temuan	= (!empty($_POST['jenis_temuan']))?$_POST['jenis_temuan']:0;
			$temuan_split 	= explode('|', $jenis_temuan);
			$id_temuan 		= $temuan_split[0];
			$temuan 		= $temuan_split[1];
			$nama_role 		= (!empty($_POST['nama_role']))?$_POST['nama_role']:0;
			$level 			= (!empty($_POST['level']))?$_POST['level']:0;
			$if_approve 	= (!empty($_POST['if_approve']))?$_POST['if_approve']:0;
			$if_decline 	= (!empty($_POST['if_decline']))?$_POST['if_decline']:0;
			// $akses_delete 	= (isset($_POST['akses_delete']))?$_POST['akses_delete']:0;
			$multiple_plan 	= (isset($_POST['multiple_plan']))?$_POST['multiple_plan']:0;
			$isresponder 	= (isset($_POST['isresponder']))?$_POST['isresponder']:0;
			

			$data_row = array(
					//all 
					"id_pica_jenis_temuan" 	=> $id_temuan,
					"nama_role" 			=> $nama_role,
					"level" 				=> $level,
					"if_approve" 			=> $if_approve,
					"if_decline" 			=> $if_decline,
					// "if_decline" 			=> $if_decline,
					"isresponder" 			=> $isresponder,
					"multiple_plan" 		=> $multiple_plan,
					'na'     				=> 'n',
					'del'     				=> 'n',
					'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'      	=> $datetime,
					'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      	=> $datetime
				);

			if (isset($_POST['id_role']) && trim($_POST['id_role']) !== "") { // edit	

				$id_edit 	= $this->generate->kirana_decrypt($_POST['id_role']);
				// check if exist 
				$datacheck 	= $nama_role;
				$datacheck2	= $id_temuan;
				$checkdata 	= $this->dmasterpica->get_data_pica_role_normal(NULL, $id_edit, NULL, 'up',$datacheck,$datacheck2);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data role ".$nama_role." ".$temuan.", periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				// check data level
				$checkdata 	= $this->dmasterpica->check_data_pica_role_level(NULL, $id_temuan, $level, 'up', $id_edit);
				if (count($checkdata) > 0) {
					$msg    = "Level sudah terpakai, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				// check data isresponder
				$checkdata 	= $this->dmasterpica->check_data_pica_role_responder(NULL, $id_temuan, $isresponder, 'up', $id_edit);
				if (count($checkdata) > 0 && $isresponder != 0) {
					$msg    = "Role Responder sudah terpakai, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				unset($data_row['login_buat'],$data_row['tanggal_buat']);
				$this->dgeneral->update("tbl_pica_role", $data_row, array(
					array(
						'kolom' => 'id_pica_role',
						'value' => $this->generate->kirana_decrypt($_POST['id_role'])
					)
				));
			} else {	//input

				// check if exist 
				$datacheck 	= $nama_role;
				$datacheck2	= $id_temuan;
				$checkdata 	= $this->dmasterpica->get_data_pica_role_normal(NULL, NULL, NULL, 'in',$datacheck,$datacheck2);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data role ".$nama_role." ".$temuan.", periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				// check data level
				$checkdata 	= $this->dmasterpica->check_data_pica_role_level(NULL, $id_temuan, $level, 'in', NULL);
				if (count($checkdata) > 0) {
					$msg    = "Level sudah terpakai, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				// check data isresponder
				$checkdata 	= $this->dmasterpica->check_data_pica_role_responder(NULL, $id_temuan, $isresponder, 'in', NULL);
				if (count($checkdata) > 0 && $isresponder != 0) {
					$msg    = "Role Responder sudah terpakai, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_pica_role", $data_row);	
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

	/*================================ Role posisi ====================================*/
	    public function pica_roleposisi($conn=NULL,$role=NULL,$all=NULL){
	        $data = $this->dmasterpica->get_data_pica_roleposisi('portal',$role,$all);
	        echo $data;
	    }

	    public function posisi_data(){
	        if(isset($_GET['q'])){
	            $posisi       = $this->dmasterpica->get_data_posisi($_GET['q']);
	            $data_posisi  = array(
	                            "total_count" => count($posisi),
	                            "incomplete_results"=>false,
	                            "items"=>$posisi
	                          );
	            echo json_encode($data_posisi);
	        }
	    }

	    private function save_roleposisi($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$roleposisi = null; 		
			if(!empty($_POST['nama_role'])){
				$split_roleposisi 		= explode("|", $_POST['nama_role']);
				$id_pica_role 			= $split_roleposisi[0];
				$id_pica_jenis_temuan 	= $split_roleposisi[1];
			} else {
				$id_pica_role 			= 0;
				$id_pica_jenis_temuan 	= 0;
			}

			$posisi 			= (!empty($_POST['posisi']))?$_POST['posisi']:0;
			$pabrik 			= (!empty($_POST['pabrik']))?$_POST['pabrik']:0;
			$data_row 			= null;
			
			// save role posisi
			foreach ($posisi as $post) {
				$dataposisi = explode("|", $post);
				$id_posisi 	= $dataposisi[0];
				$nama_posisi= $dataposisi[1];
				$data_row 	= array(
						//all 
						"id_pica_role" 			=> $id_pica_role,
						"id_pica_jenis_temuan" 	=> $id_pica_jenis_temuan,
						"id_posisi" 			=> $id_posisi,
						'na'     				=> 'n',
						'del'     				=> 'n',
						'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'      	=> $datetime,
						'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'      	=> $datetime
					);
				// echo json_encode($data_row);
				if (isset($_POST['id_roleposisi']) && trim($_POST['id_roleposisi']) !== "") { // edit

					$id_edit 	= ($_POST['id_roleposisi'] != '' ) ? $this->generate->kirana_decrypt($_POST['id_roleposisi']) : 0;
					// check if exist 
					$datacheck 	= $id_pica_role;
					$datacheck2 = $id_pica_jenis_temuan;
					$checkdata 	= $this->dmasterpica->get_data_pica_roleposisi_normal(NULL, $id_edit, NULL, 'up',$datacheck,$datacheck2);
					if (count($checkdata) > 0) {
						$msg    = "Duplicate data , periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					unset($data_row['login_buat'],$data_row['tanggal_buat']);
					$this->dgeneral->update("tbl_pica_role_posisi", $data_row, array(
						array(
							'kolom' => 'id_pica_role_posisi',
							'value' => $this->generate->kirana_decrypt($_POST['id_roleposisi'])
						)
					));

					// echo "delete old data";
					$id     = $this->generate->kirana_decrypt($_POST['id_roleposisi']);
					$delete = $this->dmasterpica->delete_data_pica_rolepabrik("portal",$id);
			        
			        // save role pabrik
					foreach ($pabrik as $pb) {
						$data_row2 = array(
							//all 
							"id_pica_role_posisi" 	=> $id,
							"kode_pabrik" 			=> $pb,							
							'na'     				=> 'n',
							'del'     				=> 'n',
							'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'      	=> $datetime,
							'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'      	=> $datetime
						);
						
						$data_row = $this->dgeneral->basic_column("insert", $data_row2);
						$this->dgeneral->insert("tbl_pica_role_pabrik", $data_row2);							
					}

				} else {	//input
					
					// check if exist 
					$datacheck 	= $id_pica_role;
					$datacheck2	= $id_pica_jenis_temuan;
					$checkdata 	= $this->dmasterpica->get_data_pica_roleposisi_normal(NULL, NULL, NULL, 'in',$datacheck,$datacheck2);
					if (count($checkdata) > 0) {
						$msg    = "Duplicate data , periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					// save role posisi
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_pica_role_posisi", $data_row);	

					//last insert 
					$insert_id_posisi 	= $this->db->insert_id();
                	
					// save role pabrik
					foreach ($pabrik as $pb) {
						$data_row2 = array(
							//all 
							"id_pica_role_posisi" 	=> $insert_id_posisi,
							"kode_pabrik" 			=> $pb,
							'na'     				=> 'n',
							'del'     				=> 'n',
							'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'      	=> $datetime,
							'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'      	=> $datetime
						);
						
						$data_row = $this->dgeneral->basic_column("insert", $data_row2);
						$this->dgeneral->insert("tbl_pica_role_pabrik", $data_row2);	
						
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

		/*================================ Jenis report ====================================*/
	    public function pica_jenisreport($conn=NULL,$role=NULL,$all=NULL){
        	$data 		= $this->dmasterpica->get_data_pica_jenisreport('portal',$role,$all);
            echo $data;
	    }

	    public function pica_jenisreport_normal($conn=NULL,$id=NULL,$all=NULL){
	    	$idtemuan 	= (!empty($_POST['id_temuan']))?$_POST['id_temuan']:0;	        
            $data 		= $this->dmasterpica->get_data_pica_jenisreport_normal('portal',$id,$all,NULL,NULL,NULL,$idtemuan);
            echo json_encode($data);
	    }

	    public function pica_workflow($conn=NULL,$id=NULL,$type=NULL){	        
            $data = $this->dmasterpica->get_data_pica_workflow('portal',$id,$type);
            echo json_encode($data);
	    }
	
	    private function save_jenisreport($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$jenis_report 	= (!empty($_POST['jenis_report']))?$_POST['jenis_report']:0;
			$lama_duedate 	= (!empty($_POST['duedate']))?$_POST['duedate']:0;
			$jenis_temuan 	= (!empty($_POST['jenis_temuan']))?$_POST['jenis_temuan']:0;
			$responder 		= (!empty($_POST['id_responder']))?$_POST['id_responder']:0;
			$verificator 	= (!empty($_POST['verificator']))?$_POST['verificator']:0;
			$data_row 		= null;

			$datatemuan 	= explode("|", $jenis_temuan);
			$id_temuan 		= $datatemuan[0];
			$nama_temuan 	= $datatemuan[1];

			$data_row = array(
					//all 
					"id_pica_jenis_temuan" 	=> $id_temuan,
					"jenis_report" 			=> $jenis_report,
					"lama_duedate" 			=> $lama_duedate,
					// "responder" 			=> $responder,
					// "verificator" 		=> $verificator,
					
					'na'     				=> 'n',
					'del'     				=> 'n',
					'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'      	=> $datetime,
					'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      	=> $datetime
				);
			if (isset($_POST['id_jenisreport']) && trim($_POST['id_jenisreport']) !== "") { // edit	
				$id_edit 	= ($_POST['id_jenisreport'] != '' ) ? $this->generate->kirana_decrypt($_POST['id_jenisreport']) : 0;
				// check if exist 
				$datacheck 	= $jenis_report;
				$datacheck2	= $id_temuan;
				$checkdata 	= $this->dmasterpica->get_data_pica_jenisreport_normal(NULL, $id_edit, NULL, 'up', $datacheck, $datacheck2);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data report ".$jenis_report." dengan jenis temuan ".$nama_temuan.", periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				unset($data_row['login_buat'],$data_row['tanggal_buat']);
				$this->dgeneral->update("tbl_pica_jenis_report", $data_row, array(
					array(
						'kolom' => 'id_pica_jenis_report',
						'value' => $this->generate->kirana_decrypt($_POST['id_jenisreport'])
					)
				));

				$insert_id_report   = $this->generate->kirana_decrypt($_POST['id_jenisreport']);
				$delete 			= $this->dmasterpica->delete_data_pica_reportresponse("portal",$insert_id_report);
            	
				// save response
				foreach ($responder as $res) {
					$data_row2 = array(
						//all 
						"id_pica_jenis_report" 	=> $insert_id_report,
						"jenis_response" 		=> 'responder',
						"id_posisi" 			=> $res,
						
						'na'     				=> 'n',
						'del'     				=> 'n',
						'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'      	=> $datetime,
						'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'      	=> $datetime
					);
					
					// $data_row2 = $this->dgeneral->basic_column("insert", $data_row2);
					$this->dgeneral->insert("tbl_pica_jenis_report_dtl", $data_row2);						
				}

				// save role pabrik
				// foreach ($verificator as $ver) {
				// 	$data_row3 = array(
				// 		//all 
				// 		"id_pica_jenis_report" 	=> $insert_id_report,
				// 		"jenis_response" 		=> 'verificator',
				// 		"id_posisi" 			=> $ver,
						
				// 		'na'     				=> 'n',
				// 		'del'     				=> 'n',
				// 		'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
				// 		'tanggal_buat'      	=> $datetime,
				// 		'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
				// 		'tanggal_edit'      	=> $datetime
				// 	);
					
				// 	// $data_row3 = $this->dgeneral->basic_column("insert", $data_row3);
				// 	$this->dgeneral->insert("tbl_pica_jenis_report_dtl", $data_row3);						
				// }

			} else {	//input
				// echo "tes2 ";
				// check if exist 
				$datacheck 	= $jenis_report;
				$datacheck2	= $id_temuan;
				$checkdata 	= $this->dmasterpica->get_data_pica_jenisreport_normal(NULL, NULL, NULL, 'in',$datacheck, $datacheck2);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data ".$jenis_report." dengan jenis temuan ".$nama_temuan." , periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				// save role posisi
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_pica_jenis_report", $data_row);	

				//last insert 
				$insert_id_report 	= $this->db->insert_id();
            	
				// save role pabrik
				foreach ($responder as $res) {
					$data_row2 = array(
						//all 
						"id_pica_jenis_report" 	=> $insert_id_report,
						"jenis_response" 		=> 'responder',
						"id_posisi" 			=> $res,
						
						'na'     				=> 'n',
						'del'     				=> 'n',
						'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'      	=> $datetime,
						'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'      	=> $datetime
					);
					
					// $data_row2 = $this->dgeneral->basic_column("insert", $data_row2);
					$this->dgeneral->insert("tbl_pica_jenis_report_dtl", $data_row2);						
				}

				// save role pabrik
				// foreach ($verificator as $ver) {
				// 	$data_row3 = array(
				// 		//all 
				// 		"id_pica_jenis_report" 	=> $insert_id_report,
				// 		"jenis_response" 		=> 'verificator',
				// 		"id_posisi" 			=> $ver,
						
				// 		'na'     				=> 'n',
				// 		'del'     				=> 'n',
				// 		'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
				// 		'tanggal_buat'      	=> $datetime,
				// 		'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
				// 		'tanggal_edit'      	=> $datetime
				// 	);
					
				// 	// $data_row3 = $this->dgeneral->basic_column("insert", $data_row3);
				// 	$this->dgeneral->insert("tbl_pica_jenis_report_dtl", $data_row3);						
				// }

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

	/*================================ Template report ====================================*/
	    public function pica_templatereport($conn=NULL,$role=NULL,$all=NULL){
	        $data 	= $this->dmasterpica->get_data_pica_template('portal',$role,$all);
            echo $data;
	    }

	    public function pica_templatereport_normal($conn=NULL,$id=NULL,$all=NULL){	// for edit      
            $data 	= $this->dmasterpica->get_data_pica_template_normal('portal',$id);
            $data 	= $this->general->generate_encrypt_json($data, array("id_pica_template_header"));
            echo json_encode($data);
	    }

	    public function pica_detail_form($conn=NULL,$role=NULL,$all=NULL){
	        $data = $this->dmasterpica->get_data_pica_detail_form('portal',$role,$all);
            echo json_encode($data);
	    }
	
	    private function save_templatereport($param) {

	    	$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$temuan_fieldname 		= (!empty($_POST['temuan_fieldname']))?$_POST['temuan_fieldname']:0;
			$jenis_report_fieldname = (!empty($_POST['jenis_report_fieldname']))?$_POST['jenis_report_fieldname']:0;
			$buyer_fieldname 		= (!empty($_POST['buyer_fieldname']))?$_POST['buyer_fieldname']:0;
			$jumlah_baris_fieldname	= (!empty($_POST['jumlah_baris_fieldname']))?$_POST['jumlah_baris_fieldname']:0;
			$data_row 				= null;

			if($temuan_fieldname != 0){
				$datatemuan 	= explode("|", $temuan_fieldname);
				$id_temuan 		= $datatemuan[0]; 	$nama_temuan 	= $datatemuan[1];
			} else {
				$id_temuan 		= 0; 				$nama_temuan 	= 0;
			}
			
			$data_row = array(
					//all 
					"id_pica_jenis_temuan" 	=> $id_temuan,
					"jenis_report" 			=> $jenis_report_fieldname,
					"buyer" 				=> $buyer_fieldname,
					"jumlah_tipe" 			=> $jumlah_baris_fieldname,
					
					'na'     				=> 'n',
					'del'     				=> 'n',
					'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'      	=> $datetime,
					'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      	=> $datetime
				);
			
			if (trim($_POST['id_hide']) != "" && $_POST['id_hide'] != '0') { // edit		
				 // echo $_POST['id_hide'] ;
				$id_edit 	= ($_POST['id_hide'] != '' ) ? $this->generate->kirana_decrypt($_POST['id_hide']) : 0;
				// check if exist 
				$datacheck 	= $jenis_report_fieldname;
				$datacheck2	= $id_temuan;
				$datacheck3 = $buyer_fieldname;
				$checkdata 	= $this->dmasterpica->get_data_pica_template_normal(NULL, $id_edit, NULL, 'up', $datacheck, $datacheck2, $datacheck3);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data template ".$jenis_report_fieldname." dengan jenis temuan ".$nama_temuan." , periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				unset($data_row['login_buat'],$data_row['tanggal_buat']);
				$this->dgeneral->update("tbl_pica_template_header", $data_row, array(
					array(
						'kolom' => 'id_pica_template_header',
						'value' => $this->generate->kirana_decrypt($_POST['id_hide'])
					)
				));

				$insert_id_header   = $this->generate->kirana_decrypt($_POST['id_hide']);
				$delete 			= $this->dmasterpica->delete_data_pica_templatereportdetail("portal",$insert_id_header);
            	
				// loop data for insert detail
				for($i = 1 ;$i <= $jumlah_baris_fieldname ;$i++) {
					
					$form_available = explode(',', $_POST['id_form_'.$i] );
					$data_row2= array();
					// loop data for insert detail from row 
			    	for($j=0; $j < count($form_available); $j++ ){

			    		// if form tempalte is not null
			    		if($_POST['id_form_'.$i] != ""){
				    		$data_pica_mst_input 		= explode('|', $form_available[$j]);
				    		$data_pica_mst_input_id 	= $data_pica_mst_input[0]; 
				    		$var_field_desc 			= $data_pica_mst_input[1].'_text_'.$i; 
				    		// get data desc form
				    		$data_pica_mst_input_desc 	= (!empty($_POST[$var_field_desc]))?$_POST[$var_field_desc]:'';

				    		$data_row2 = array( 
											//all 
											"id_pica_template_header" 	=> $insert_id_header,
											"id_pica_mst_input" 		=> $data_pica_mst_input_id,
											"desc" 						=> $data_pica_mst_input_desc,
											"baris" 					=> $i,						
											'na'     					=> 'n',
											'del'     					=> 'n',
											'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
											'tanggal_buat'      		=> $datetime,
											'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
											'tanggal_edit'      		=> $datetime
										);	
			    			$this->dgeneral->insert("tbl_pica_template_detail", $data_row2);
			    			// echo json_encode($data_row2)."<br>";
				    	} 
			    	} 
			    }
			
			} else {	//input
				// check if exist 
				$datacheck 	= $jenis_report_fieldname;
				$datacheck2	= $id_temuan;
				$datacheck3 = $buyer_fieldname;
				$checkdata 	= $this->dmasterpica->get_data_pica_template_normal(NULL, NULL, NULL, 'in',$datacheck, $datacheck2, $datacheck3);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data template ".$jenis_report_fieldname." dengan jenis temuan ".$nama_temuan." , periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				// save role posisi
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_pica_template_header", $data_row);	

				//last insert 
				$insert_id_header 	= $this->db->insert_id();
            	
				// save detail template 
				// loop data for insert detail
				for($i = 1 ;$i <= $jumlah_baris_fieldname ;$i++) {
					
					$form_available = explode(',', $_POST['id_form_'.$i] );
					$data_row2= array();
					// loop data for insert detail from row 
			    	for($j=0; $j < count($form_available); $j++ ){

			    		// if form tempalte is not null
			    		if($_POST['id_form_'.$i] != ""){
				    		$data_pica_mst_input 		= explode('|', $form_available[$j]);
				    		$data_pica_mst_input_id 	= $data_pica_mst_input[0]; 
				    		$var_field_desc 			= $data_pica_mst_input[1].'_text_'.$i; 
				    		// get data desc form
				    		$data_pica_mst_input_desc 	= (!empty($_POST[$var_field_desc]))?$_POST[$var_field_desc]:'';

				    		$data_row2 = array( 
											//all 
											"id_pica_template_header" 	=> $insert_id_header,
											"id_pica_mst_input" 		=> $data_pica_mst_input_id,
											"desc" 						=> $data_pica_mst_input_desc,
											"baris" 					=> $i,						
											'na'     					=> 'n',
											'del'     					=> 'n',
											'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
											'tanggal_buat'      		=> $datetime,
											'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
											'tanggal_edit'      		=> $datetime
										);	
			    			$this->dgeneral->insert("tbl_pica_template_detail", $data_row2);
			    			// echo json_encode($data_row2)."<br>";
				    	} 
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
	}

?>
