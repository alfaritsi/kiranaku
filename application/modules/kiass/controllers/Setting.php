<?php
	/*
    @application  : SCRAP
    @author       : MATTHEW JODI (8944)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */

	include_once APPPATH . "modules/kiass/controllers/BaseControllers.php";

	class Setting extends BaseControllers {
		public function __construct() {
			parent::__construct();
			$this->data['module'] = "K-IASS";
			$this->load->model('dmasterscrap');
			$this->load->model('dsettingscrap');
		}

		public function userrole() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Setting User Role";
			$this->data['title_form'] = "Hak akses Role";

			$this->data['role'] = $this->dmasterscrap->get_master_role(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
				"active"   	=> 'active'
			));

			$this->data['pabrik']       = $this->get_master_plant(NULL, false, NULL, "array");

			// echo json_encode($this->data['pabrik']);exit();


			$this->load->view("setting/userrole", $this->data);
		}

		public function get($param = NULL) {
			switch ($param) {
				case 'user':
					$post = $this->input->post_get(NULL, TRUE);
					if ($post['type'] == "nik") {
						$this->get_data_karyawan();
					} else {
						$this->get_data_posisi();
					}
					break;
				case 'roleuser':
					$this->get_data_roleuser_format();
					break;
				case 'customer':
					$this->get_data_customer();
					break;	
				case 'material':
					$this->get_data_material();
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
			}
			else if (isset($_POST['type']) && $_POST['type'] == "setactive") {
				$action = "activate_na_del";
			}
			else if (isset($_POST['type']) && $_POST['type'] == "delete") {
				$action = "delete_na_del";
			}

			if ($action) {
				switch ($param) {
					case 'user':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_scrap_roleuser", array(
							array(
								'kolom' => 'kode_role',
								'value' => $_POST['kode_role']
							),
							array(
								'kolom' => 'user',
								'value' => $_POST['user']
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
				case 'user':
					$this->save_roleuser();
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

		private function get_data_customer()
		{
			$param = $this->input->post_get(NULL, TRUE);
			if (isset($param['autocomplete']) && $param['autocomplete'] == TRUE) {
				$customer = $this->dsettingscrap->get_data_customer(array(
					"connect" => TRUE,
					"search" => $param['search'],
					"plant" => $param['plant']
				));
				$data_customer  = array(
					"total_count" => count($customer),
					"incomplete_results" => false,
					"items" => $customer
				);
				echo json_encode($data_customer);
				exit();
			}
		}

		private function get_data_material()
		{
			$param = $this->input->post_get(NULL, TRUE);
			if (isset($param['autocomplete']) && $param['autocomplete'] == TRUE) {
				$material = $this->dsettingscrap->get_data_material(array(
					"connect" => NULL,
					"search" => $param['search'],
					"plant" => $param['plant']
				));
				$data_material  = array(
					"total_count" => count($material),
					"incomplete_results" => false,
					"items" => $material
				);
				echo json_encode($data_material);
				exit();
			}
		}

		private function get_data_roleuser_format() {
			$kode_role = (isset($_POST['role']) ? $_POST['role'] : NULL);
			$user = (isset($_POST['user']) ? $_POST['user'] : NULL);
			$single = (isset($_POST['single']) ? TRUE : NULL);
			$roleuser = $this->dsettingscrap->get_data_roleuser_format(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"kode_role" => $kode_role,
				"user" => $user,
				"single_row" => $single
			));

			echo json_encode($roleuser);
		}

		private function save_roleuser() {
			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();

			if (isset($param['action']) && trim($param['action']) == "edit"){
				
				// $param_role['connect'] = FALSE;
				// $param_role['kode_role'] = $param['role'];
				// $param_role['user'] = $param['users'];
				// $param_role['checker'] = $param['kode_role'];
                // $param_role['app'] = "kiass";
				// $check_exists = $this->dsettingscrap->get_data_roleuser($param_role);
                // if (count($check_exists) > 0) {
                //     $msg    = "Duplicate data dengan Nama Role " . $param['role'] . ", periksa kembali data yang dimasukkan";
                //     $sts    = "NotOK";
                //     $return = array('sts' => $sts, 'msg' => $msg);
                //     echo json_encode($return);
                //     exit();
                // }


				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				
				//EDIT
				$data_row     = array(
					"kode_role"    			=> $param['role'],
					"user"   	   			=> $param['user'],
					"caption"			   	=> $param['caption'],
					"pabrik"		   		=> implode(",", $param['pabrik'])
				);

				$data_row     = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_scrap_roleuser", $data_row, array(
					array(
						'kolom' => 'kode_role',
						'value' => $param['role']
					),
					array(
						'kolom' => 'user',
						'value' => $param['user']
					)
				));

			}else{

				$param_role['connect'] = FALSE;
                $param_role['kode_role'] = $param['role'];
                $param_role['user'] = $this->generate->kirana_decrypt($param['user']);
                $param_role['app'] = "kiass";
				$check_exists = $this->dsettingscrap->get_data_roleuser($param_role);
                if (count($check_exists) > 0) {
                    $msg    = "Duplicate data, periksa kembali data yang dimasukkan";
                    $sts    = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                    exit();
                }

				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				// INSERT
				$data_row     = array(
					"kode_role"    			=> $param['role'],
					"user"   	   			=> $this->generate->kirana_decrypt($param['user']),
					"caption"			   	=> $param['caption'],
					"pabrik"		   		=> implode(",", $param['pabrik']),
					"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" 			=> $datetime
				);
				$data_row     = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_scrap_roleuser", $data_row);				
				
			}


			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			else {
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
