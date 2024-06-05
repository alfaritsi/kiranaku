<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
        @application  : 
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 07-Jan-19
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */
	include_once APPPATH . "modules/nusira/controllers/BaseControllers.php";

	class Setting extends BaseControllers {
		public function __construct() {
			parent::__construct();
			$this->data['module'] = "NUSIRA WORKSHOP";
			$this->load->model('dmasternusira');
		}

		public function material() {
			//====must be initiate in every view function====//
			$this->general->check_access();
			//===============================================//
			$this->data['title']      = "Katalog Produk";
			$this->data['title_form'] = "Setting Katalog Produk";
			$this->load->view("setting/material", $this->data);
		}

		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//
		public function get($param = NULL) {
			switch ($param) {
				case 'bom_datatables':
					header('Content-Type: application/json');
					$return = $this->dmasternusira->get_all_bom_datatables('open');
					echo $return;
					break;
				case 'bom':
					$this->get_autocomplete_bom();
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		public function save($param = NULL) {
			switch ($param) {
				case 'material':
					$this->save_material();
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
		private function get_autocomplete_bom() {
			if (isset($_GET['q'])) {
				$data      = $this->dmasternusira->get_all_bom('open', NULL, NULL, $_GET['q']);
				$data_json = array(
					"total_count"        => count($data),
					"incomplete_results" => false,
					"items"              => $data
				);

				$return = json_encode($data_json);
				$return = $this->general->jsonify($data_json);

				echo $return;
			}
		}

		private function save_material() {
			$datetime = date("Y-m-d H:i:s");
			if (trim($_POST['material']) !== "" && trim($_POST['kode']) !== "") {
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|png';

				$data_row = array(
					"itnum"        => $_POST['itnum'],
					"matnr"        => $_POST['kode'],
					"spesifikasi"  => (isset($_POST['spesifikasi']) && trim($_POST['spesifikasi']) !== "" ? preg_replace("/\r\n|\r|\n/", '<br/>', htmlentities($_POST['spesifikasi'])) : NULL),
					"login_edit"   => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" => $datetime
				);
				$this->dgeneral->delete("tbl_pi_setting_material", array(
					array(
						'kolom' => 'itnum',
						'value' => $_POST['itnum']
					),
					array(
						'kolom' => 'matnr',
						'value' => $_POST['kode']
					)
				));
				$data_material     = $this->dgeneral->basic_column("insert", $data_row);
				$data_material_log = $this->dgeneral->basic_column("insert_simple", $data_row);
				$this->dgeneral->insert("tbl_pi_setting_material", $data_material);
				$this->dgeneral->insert("tbl_pi_setting_material_log", $data_material_log);

				if (isset($_FILES['file_material'])) {
					$jml_file = count($_FILES['file_material']['name']);
					if ($jml_file > 3) {
						$this->dgeneral->rollback_transaction();
						$msg    = "You can only upload maximum 3 files";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}

					$newname = array();
					for ($i = 0; $i < $jml_file; $i++) {
						if (isset($_FILES['file_material']) && $_FILES['file_material']['error'][$i] == 0 && $_FILES['file_material']['name'][$i] !== "") {
							list($width, $height, $type, $attr) = getimagesize($_FILES['file_material']['tmp_name'][$i]);
							if ($width !== $height) {
								$this->dgeneral->rollback_transaction();
								$msg    = "You can only upload file with square dimension, please resize your image first";
								$sts    = "NotOK";
								$return = array('sts' => $sts, 'msg' => $msg);
								echo json_encode($return);
								exit();
							}
							array_push($newname, $_POST['itnum'] . "_" . $_POST['kode'] . "_" . $i);
						}
					}

					if (count($newname) > 0) {
						$file_img = $this->general->upload_files($_FILES['file_material'], $newname, $config);
						if ($file_img) {
							$data_batch = array();
							foreach ($file_img as $dt) {
								$this->dgeneral->delete("tbl_pi_setting_material_file", array(
																						  array(
																							  'kolom' => 'itnum',
																							  'value' => $_POST['itnum']
																						  ),
																						  array(
																							  'kolom' => 'matnr',
																							  'value' => $_POST['kode']
																						  )
																					  )
								);

								$data_row     = array(
									"itnum"         => $_POST['itnum'],
									"matnr"         => $_POST['kode'],
									"file_name"     => $dt['filename'],
									"file_location" => $dt['url']
								);
								$data_row     = $this->dgeneral->basic_column("update", $data_row);
								$data_batch[] = $data_row;
							}
							$this->dgeneral->insert_batch("tbl_pi_setting_material_file", $data_batch);
						}
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
			}
			else {
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}
	}

?>
