<?php
	/*
        @application  : UMB (Uang Muka Bokar)
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 24-Sep-18
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */

	include_once APPPATH . "modules/umb/controllers/BaseControllers.php";

	class Setting extends BaseControllers {
		public function __construct() {
			parent::__construct();
			$this->data['module'] = "Uang Muka Bokar";
			$this->load->model('dmasterumb');
			$this->load->model('dsettingumb');
		}

		public function user() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/

			$this->data['title']       = "User Role";
			$this->data['title_form']  = "Setting User Role";
			$this->data['role_select'] = $this->dmasterumb->get_master_role("open", NULL, NULL, 'n', 'n');
			$this->data['plant']       = $this->get_master_plant(NULL, false, NULL, "array");
			$this->data['userrole']    = $this->get_user("array", NULL, NULL, NULL, NULL, 'n');
			$this->load->view("setting/user", $this->data);
		}

		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//
		public function get($param = NULL) {
			switch ($param) {
				case 'user':
					$id_rolenik = (isset($_POST['rolenik']) ? $this->generate->kirana_decrypt($_POST['rolenik']) : NULL);
					$kode       = (isset($_POST['kode']) ? $this->generate->kirana_decrypt($_POST['kode']) : NULL);
					$active     = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted    = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$nik        = (isset($_POST['nik']) ? $_POST['nik'] : NULL);
					$this->get_user(NULL, $id_rolenik, $nik, $kode, $active, $deleted);
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
					case 'user':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_umb_rolenik", array(
							array(
								'kolom' => 'id_rolenik',
								'value' => $this->generate->kirana_decrypt($_POST['rolenik'])
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
					$this->save_user();
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
		private function get_user($array = NULL, $id_rolenik = NULL, $nik = NULL, $kode_role = NULL, $active = NULL, $deleted = NULL) {
			$user = $this->dsettingumb->get_setting_user("open", $id_rolenik, $nik, $kode_role, $active, $deleted);
			$user = $this->general->generate_encrypt_json($user, array("kode_role","id_rolenik"));
			if ($array) {
				return $user;
			} else {
				echo json_encode($user);
			}
		}

		private function save_user() {
			$datetime = date("Y-m-d H:i:s");
			if (trim($_POST['karyawan']) !== "" && trim($_POST['role']) !== "") {
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				if (isset($_POST['id']) && trim($_POST['id']) !== "") {
					$id_rolenik = $this->generate->kirana_decrypt($_POST['id']);

					$data_row = array(
						"kode_role" => $this->generate->kirana_decrypt($_POST['role'])
					);
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_umb_rolenik", $data_row, array(
						array(
							'kolom' => 'id_rolenik',
							'value' => $id_rolenik
						)
					));

					$this->dgeneral->delete("tbl_umb_rolenik_pabrik", array(
						array(
							'kolom' => 'id_rolenik',
							'value' => $id_rolenik
						)
					));
				} else {
					$user = $this->get_user("array", NULL, $_POST['karyawan'], NULL, NULL, 'n');
					if (count($user) > 0) {
						$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}

					$data_row = array(
						"kode_role"    => $this->generate->kirana_decrypt($_POST['role']),
						"nik"          => $_POST['karyawan'],
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime
					);

					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_umb_rolenik", $data_row);
					$id_rolenik = $this->db->insert_id();
				}

				if (isset($_POST['pabrik'])) {
					foreach ($_POST['pabrik'] as $p) {
						$data_row = array(
							"id_rolenik"  => $id_rolenik,
							"kode_pabrik" => $p
						);

						$data_row = $this->dgeneral->basic_column("update", $data_row);
						$this->dgeneral->insert("tbl_umb_rolenik_pabrik", $data_row);
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
			} else {
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}
	}
