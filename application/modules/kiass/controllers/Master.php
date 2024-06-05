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

	class Master extends BaseControllers {
		public function __construct() {
			parent::__construct();
			$this->data['module'] = "K-IASS";
			$this->load->model('dmasterscrap');
			// $this->load->model('dsettingumb');
		}

		public function role() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Master Role";
			$this->data['title_form'] = "Master Role";

			$flow = $this->dmasterscrap->get_master_flow(array(
				"connect" => NULL,
				"app"   => 'kiass'
			));

			$this->data['flow'] = $flow;

			$this->load->view("master/role", $this->data);
		}

		public function flow() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Master Flow Approval";
			$this->data['title_form'] = "Master Flow Approval";

			$flow = $this->dmasterscrap->get_master_flow(array(
				"connect" => NULL,
				"app"   => 'kiass'
			));

			$this->data['flow'] = $flow;

			$this->load->view("master/flow", $this->data);
		}


		public function get($param = NULL) {
			switch ($param) {
				case 'flow':
					$this->get_master_flow();
					break;
				case 'role':
					$this->get_master_role();
					break;
				case 'rolelist':
					$this->get_master_role_list();
					break;
				case 'roledtl':
					$this->get_master_role_detail();
					break;
				// case 'role':
				// 	$kode    = (isset($_POST['kode']) ? $this->generate->kirana_decrypt($_POST['kode']) : NULL);
				// 	$level   = (isset($_POST['level']) ? $_POST['level'] : NULL);
				// 	$active  = (isset($_POST['active']) ? $_POST['active'] : NULL);
				// 	$deleted = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
				// 	$name    = (isset($_POST['role']) ? $_POST['role'] : NULL);
				// 	$this->get_role(NULL, $kode, $level, $active, $deleted, $name);
				// 	break;
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
				$action = "activate_na";
			}
			else if (isset($_POST['type']) && $_POST['type'] == "delete") {
				$action = "delete_na_del";
			}

			if ($action) {
				switch ($param) {
					case 'flow':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_scrap_mflow_approval", array(
							array(
								'kolom' => 'id_flow',
								'value' => $_POST['id_flow']
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'role':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_scrap_role", array(
							array(
								'kolom' => 'kode_role',
								'value' => $_POST['kode_role']
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
				case 'flow':
					$this->save_flow();
					break;
				case 'role':
					$this->save_role();
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

		//MASTER FLOW
		private function get_master_flow() {
			$id_flow = (isset($_POST['id_flow']) ? $_POST['id_flow'] : NULL);
			$flow = $this->dmasterscrap->get_master_flow(array(
				"connect" => NULL,
				"app"   => 'scrap',
				"id_flow" => $id_flow
			));

			echo json_encode($flow);
		}

		private function save_flow() {
			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();

			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if (isset($param['id_flow']) && trim($param['id_flow']) !== ""){
				//EDIT
				$data_row     = array(
					"lokasi"       => $param['lokasi'],
					"keterangan"   => $param['keterangan'],
					"alias_flow"   => $param['alias_flow']
				);
				$data_row     = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_scrap_mflow_approval", $data_row, array(
					array(
						'kolom' => 'id_flow',
						'value' => $param['id_flow']
					)
				));

			}else{
				// INSERT
				$data_row     = array(
					"lokasi"       => $param['lokasi'],
					"keterangan"   => $param['keterangan'],
					"alias_flow"   => $param['alias_flow'],
					"login_edit"   => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" => $datetime
				);
				$data_row     = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_scrap_mflow_approval", $data_row);

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

		// MASTER ROLE
		private function get_master_role() {
			$kode_role = (isset($_POST['kode_role']) ? $_POST['kode_role'] : NULL);

			$return['role'] = $this->dmasterscrap->get_master_role(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"kode_role" => $kode_role,
			));

			$return['flow'] = $this->dmasterscrap->get_master_flow(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"active"   => 'y',
				"id_flow" => NULL
			));

			echo json_encode($return);
		}

		private function get_master_role_detail() {
			$kode_role = (isset($_POST['kode_role']) ? $_POST['kode_role'] : NULL);
			$id_flow = (isset($_POST['id_flow']) ? $_POST['id_flow'] : NULL);

			$role = $this->dmasterscrap->get_master_role_detail(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"kode_role" => $kode_role,
				"id_flow" => $id_flow
			));

			echo json_encode($role);
		}

		private function get_master_role_list() {
			$id_flow = (isset($_POST['id_flow']) ? $_POST['id_flow'] : NULL);

			$role = $this->dmasterscrap->get_master_role_list(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"id_flow" => $id_flow
			));

			echo json_encode($role);
		}

		private function save_role() {
			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();

			if (isset($param['kode_role']) && trim($param['kode_role']) !== ""){
				
				// $param_role['connect'] = FALSE;
				// $param_role['nama_role'] = $param['role'];
				// $param_role['checker'] = $param['kode_role'];
                // $param_role['app'] = "scrap";
				// $check_exists = $this->dmasterscrap->get_master_role($param_role);
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
					"nama_role"    			=> $param['role'],
					"level"   	   			=> $param['level'],
					"is_limit_pabrik"   	=> $param['isLimitPabrik'],
					"akses_delete"   		=> $param['isDelete'],
					"tipe_user"		   		=> $param['tipe_user'],
				);
				$data_row     = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_scrap_role", $data_row, array(
					array(
						'kolom' => 'kode_role',
						'value' => $param['kode_role']
					)
				));

				for ($i=0; $i < $param['counter_flow'] ; $i++) { 
					$id_flow = $param['id_flow_'.$i];
					$data_row_detail     = array(
						"kode_role"    			=> $param['kode_role'],
						"id_flow"   	   		=> $id_flow,
						"if_approve" 			=> $this->general->emptyconvert($param['if_approve_flow_'.$id_flow]),
						"if_decline" 			=> $this->general->emptyconvert($param['if_decline_flow_'.$id_flow]),
						"if_assign" 			=> $this->general->emptyconvert($param['if_assign_flow_'.$id_flow]),
						"if_drop" 				=> $this->general->emptyconvert($param['if_drop_flow_'.$id_flow]),
						"app_lim_val"			=> str_replace(",", "",$param['limit_app_flow_'.$id_flow]),
						"if_approve_capex" 		=> $this->general->emptyconvert($param['if_approve_deviasi_flow_'.$id_flow]),
						"if_decline_capex" 		=> $this->general->emptyconvert($param['if_decline_deviasi_flow_'.$id_flow]),
						"if_assign_capex" 		=> $this->general->emptyconvert($param['if_assign_deviasi_flow_'.$id_flow]),
						"if_drop_capex" 		=> $this->general->emptyconvert($param['if_drop_deviasi_flow_'.$id_flow]),
						"app_lim_val_capex"		=> str_replace(",", "",$param['limit_app_deviasi_flow_'.$id_flow]),
						"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit" 			=> $datetime
					);

					
					$check_exists_dtl = $this->dmasterscrap->get_master_role_detail(array(
						"connect" => FALSE,
						"kode_role" => $param['kode_role'],
						"id_flow" => $id_flow,
						"app" => "kiass"
					));
	
					if ($check_exists_dtl) {
						unset($data_row_detail['kode_role']);
						unset($data_row_detail['id_flow']);
	
						$this->dgeneral->update('tbl_scrap_role_dtl', $data_row_detail, array(
							array(
								'kolom' => 'kode_role',
								'value' => $param['kode_role']
							),
							array(
								'kolom' => 'id_flow',
								'value' => $id_flow
							)
						));
					} else {
						$this->dgeneral->insert('tbl_scrap_role_dtl', $data_row_detail);
					}

				}


			}else{

				$param_role['connect'] = FALSE;
                $param_role['nama_role'] = $param['role'];
                $param_role['app'] = "kiass";
				$check_exists = $this->dmasterscrap->get_master_role($param_role);
                if (count($check_exists) > 0) {
                    $msg    = "Duplicate data dengan Nama Role " . $post['role'] . ", periksa kembali data yang dimasukkan";
                    $sts    = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                    exit();
                }

				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				// INSERT
				$data_row     = array(
					"nama_role"    			=> $param['role'],
					"level"   	   			=> $param['level'],
					"is_limit_pabrik"   	=> $param['isLimitPabrik'],
					"dual_option_decline"   => 0,
					"akses_delete"   		=> $param['isDelete'],
					"tipe_user"		   		=> $param['tipe_user'],
					"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" 			=> $datetime
				);
				$data_row     = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_scrap_role", $data_row);
				$kode_role = $this->db->insert_id();



				
				for ($i=0; $i < $param['counter_flow'] ; $i++) { 
					$id_flow = $param['id_flow_'.$i];
					
					$data_row_detail     = array(
						"kode_role"    			=> $kode_role,
						"id_flow"   	   		=> $id_flow,
						"if_approve" 			=> $this->general->emptyconvert($param['if_approve_flow_'.$id_flow]),
						"if_decline" 			=> $this->general->emptyconvert($param['if_decline_flow_'.$id_flow]),
						"if_assign" 			=> $this->general->emptyconvert($param['if_assign_flow_'.$id_flow]),
						"if_drop" 				=> $this->general->emptyconvert($param['if_drop_flow_'.$id_flow]),
						"app_lim_val"			=> str_replace(",", "",$param['limit_app_flow_'.$id_flow]),
						"if_approve_capex" 		=> $this->general->emptyconvert($param['if_approve_deviasi_flow_'.$id_flow]),
						"if_decline_capex" 		=> $this->general->emptyconvert($param['if_decline_deviasi_flow_'.$id_flow]),
						"if_assign_capex" 		=> $this->general->emptyconvert($param['if_assign_deviasi_flow_'.$id_flow]),
						"if_drop_capex" 		=> $this->general->emptyconvert($param['if_drop_deviasi_flow_'.$id_flow]),
						"app_lim_val_capex"		=> str_replace(",", "",$param['limit_app_deviasi_flow_'.$id_flow]),
						"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit" 			=> $datetime
					);

					$this->dgeneral->insert("tbl_scrap_role_dtl", $data_row_detail);

				}
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
