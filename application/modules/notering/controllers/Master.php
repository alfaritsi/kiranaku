<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	include_once APPPATH . "modules/notering/controllers/BaseControllers.php";
	/*
		@application  : Notering
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
			$this->load->model('dmasternotering');
			
			/*load model*/
			$this->load->model('dgeneral');
		}

		public function index(){
			show_404();
		}

		/*================================ Temuan ====================================*/
		public function User() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$data['module'] 		= "Notering";
			$data['title']      	= "Master Data User ";
			$data['title_form'] 	= "Tambah Data User ";

			/*load global attribute*/
			$data['generate']     	= $this->generate;
			$data['module']       	= $this->router->fetch_module();
			$data['user']       	= $this->general->get_data_user();
			$data['role']       	= $this->dmasternotering->get_data_role();
			
			$this->load->view("master/user", $data);
		}

		
	
//=================================//
//		  PROCESS FUNCTION 		   //
//=================================//
		
		public function get($param = NULL) {
	
			switch ($param) {
				
				case 'user'				: // jenis temuan master
					$id_user 		= (isset($_POST['id_user']) ? $this->generate->kirana_decrypt($_POST['id_user']) : NULL);
					$jenis 			= isset($_POST['jenis']) && $_POST['jenis'] == 'normal' ? 'normal' : NULL; 
					$this->getuser('portal', $id_user,$jenis);
					break;
				
				case 'karyawan'				: // jenis temuan master
					$nik 		= isset($_POST['nik']) ? $_POST['nik'] : NULL;
					$this->getkaryawan('portal', $nik);
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
			} else if (isset($_POST['type']) && $_POST['type'] == "verifikasi"){
				$this->verifikasi($_POST['id']);
				exit();
			}
			
			if ($action) {
				switch ($param) {
					
					case 'user'			:
						$this->general->connectDbPortal();
						
						$return = $this->general->set($action, "tbl_notering_user_device", array(
							array(
								'kolom' => 'id_user',
								'value' => $this->generate->kirana_decrypt($_POST['id'])
							)
						));

						$return = $this->general->set($action, "tbl_notering_user_role", array(
							array(
								'kolom' => 'id_user',
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
				
				case 'user'			:
					$this->save_user($param);
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
	    public function getuser($conn=NULL,$id=NULL,$jenis=NULL){
	    	if($jenis != NULL && $jenis == 'normal'){
	    		$data = $this->dmasternotering->get_data_user_normal('portal',$id);
	    		$data = $this->general->generate_encrypt_json($data, array("id_user"));
	            echo json_encode($data);
	    	} else {
	        	$data = $this->dmasternotering->get_data_user_paging('portal',$id);
	        	//$data = $this->general->generate_encrypt_json($data, array("id_user"));
	            echo $data;
	        }	        
	    }

	    public function get_user_auto(){
			return $this->general->get_user_autocomplete();
		}

	    private function save_user($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$nik 		= (!empty($_POST['nik']))?trim($_POST['nik']):0;
			$kode_role 	= (!empty($_POST['kode_role']))?trim($_POST['kode_role']):0;
			
			$data_row = array(
					//all 
					"nik" 					=> $nik,
					'na'     				=> 'n',
					'del'     				=> 'n',
					'verify'     				=> 'n',
					'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'      	=> $datetime,
					'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      	=> $datetime
				);

			

			if (isset($_POST['id_user']) && trim($_POST['id_user']) !== "") { // edit	
				$id_edit 	= $this->generate->kirana_decrypt($_POST['id_user']);
				// check if exist 
				$datacheck 	= $nik;
				// $datacheck2	= $requestor;
				$checkdata 	= $this->dmasternotering->get_data_user_normal(NULL, $id_edit, NULL, 'up',$datacheck);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data nik ".$nik.", periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				unset($data_row['login_buat'],$data_row['tanggal_buat']);
				$this->dgeneral->update("tbl_notering_user_device", $data_row, array(
					array(
						'kolom' => 'id_user',
						'value' => $id_edit
					)
				));

				// delete master user_role
				$this->dmasternotering->delete_data("tbl_notering_user_role",'id_user', $id_edit);
				$data_row2 = array(
					//all 
					"id_user" 				=> $id_edit,
					"kode_role" 			=> $kode_role,
					'na'     				=> 'n',
					'del'     				=> 'n',
					'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'      	=> $datetime,
					'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      	=> $datetime
				);
				$this->dgeneral->insert("tbl_notering_user_role", $data_row2);

			} else {	//input
				
				// check if exist 
				$datacheck 	= $nik;
				// $datacheck2 = $requestor;
				$checkdata = $this->dmasternotering->get_data_user_normal(NULL, NULL, NULL, 'in',$datacheck);
				if (count($checkdata) > 0) {
					$msg    = "Duplicate data nik ".$nik.", periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				// $data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_notering_user_device", $data_row);
				// id_user
				$insert_id = $this->db->insert_id();

				$data_row2 = array(
					//all 
					"id_user" 				=> $insert_id,
					"kode_role" 			=> $kode_role,
					'na'     				=> 'n',
					'del'     				=> 'n',
					'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'      	=> $datetime,
					'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      	=> $datetime
				);
				$this->dgeneral->insert("tbl_notering_user_role", $data_row2);


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

		private function verifikasi($param) {
			$datetime = date("Y-m-d H:i:s");
			$id 	  = $this->generate->kirana_decrypt($param);

			$data = $this->dmasternotering->get_data_user_normal('portal',$id);
			$tempDeviceId = $data[0]->tempDeviceId;

			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			

			$data_row = array(
					'verify' 				=> 'n',
					'deviceId' 				=> $tempDeviceId,
					'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      	=> $datetime
				);

			$this->dgeneral->update("tbl_notering_user_device", $data_row, array(
				array(
					'kolom' => 'id_user',
					'value' => $id
				)
			));
			

			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg = "Data berhasil terverifikasi";
				$sts = "OK";
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}

		public function getkaryawan($conn=NULL,$nik=NULL){
        	$data = $this->dmasternotering->get_data_karyawan(NULL,$nik);
            echo json_encode($data);	        
	    }

	
	}

?>
