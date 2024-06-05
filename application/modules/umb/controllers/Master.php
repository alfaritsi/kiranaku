<?php
	/*
		@application  : UMB (Uang Muka Bokar)
		@author       : Akhmad Syaiful Yamang (8347)
		@contributor  :
			  1. Lukman Hakim (7143) 26.09.2018
				 Menambahkan Master Scoring
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
    */

	include_once APPPATH . "modules/umb/controllers/BaseControllers.php";

	class Master extends BaseControllers {
		public function __construct() {
			parent::__construct();
			$this->data['module'] = "Uang Muka Bokar";
			$this->load->model('dmasterumb');
			$this->load->model('dsettingumb');
		}

		public function plafon() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Master Plafon";
			$this->data['title_form'] = "Setting Master Plafon";
			$this->load->view("master/plafon", $this->data);
		}

		public function role() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']       = "Master Role";
			$this->data['title_form']  = "Setting Master Role";
			$this->data['role_select'] = $this->get_role('array', NULL, NULL, 'n', 'n', NULL, array("0"));
			$this->data['role']        = $this->get_role('array', NULL, NULL, NULL, 'n');
			$this->load->view("master/role", $this->data);
		}

		public function scoring() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Master Scoring";
			$this->data['title_form'] = "Setting Master Scoring";
			$this->data['kelas']      = $this->dmasterumb->get_master_kelas("open");
			$this->data['tipe']       = $this->dmasterumb->get_master_tipe_scoring("open");
			$this->data['scoring']    = $this->get_scoring('array');
			$this->load->view("master/scoring", $this->data);
		}

		public function kriteria() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Master Kriteria";
			$this->data['title_form'] = "Setting Master Kriteria";
			$this->data['jenis']      = $this->dmasterumb->get_master_jenis_kriteria("open");
			$this->data['kelas']      = $this->dmasterumb->get_master_kelas("open");
			$this->data['satuan']     = $this->dmasterumb->get_master_satuan("open");
			$this->data['kriteria']   = $this->get_kriteria('array');
			$this->load->view("master/kriteria", $this->data);
		}

		public function jaminan() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Master Jaminan";
			$this->data['title_form'] = "Setting Master Jaminan";
			$this->data['jenis']      = $this->dmasterumb->get_master_jenis_jaminan("open");
			$this->data['jaminan']    = $this->get_jaminan('array');
			$this->load->view("master/jaminan", $this->data);
		}

		public function dokumen() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Master Dokumen";
			$this->data['title_form'] = "Setting Master Dokumen";
			$this->data['dokumen']    = $this->get_dokumen('array', NULL, NULL, 'n');
			$this->load->view("master/dokumen", $this->data);
		}

		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//
		public function get($param = NULL) {
			switch ($param) {
				case 'plant':
					$this->get_master_plant();
					break;
				case 'plafon':
					$this->get_plafon();
					break;
				case 'role':
					$kode    = (isset($_POST['kode']) ? $this->generate->kirana_decrypt($_POST['kode']) : NULL);
					$level   = (isset($_POST['level']) ? $_POST['level'] : NULL);
					$active  = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$name    = (isset($_POST['role']) ? $_POST['role'] : NULL);
					$this->get_role(NULL, $kode, $level, $active, $deleted, $name);
					break;
				case 'tipe-scoring':
					$return = $this->dmasterumb->get_master_tipe_scoring("open");
					$return = $this->general->generate_encrypt_json($return, array("id"));
					echo json_encode($return);
					break;
				case 'scoring':
					$id_mscoring_header = (isset($_POST['id_mscoring_header']) ? $this->generate->kirana_decrypt($_POST['id_mscoring_header']) : NULL);
					$active             = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted            = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_scoring(NULL, $id_mscoring_header, $active, $deleted);
					break;
				case 'kriteria':
					$id_mkriteria_header = (isset($_POST['id_mkriteria_header']) ? $this->generate->kirana_decrypt($_POST['id_mkriteria_header']) : NULL);
					$active              = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted             = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$kelas               = (isset($_POST['kelas']) ? $_POST['kelas'] : NULL);
					$this->get_kriteria(NULL, $id_mkriteria_header, $active, $deleted, NULL, NULL, $kelas);
					break;
				case 'cek_kriteria':
					$id_mkriteria_header = (isset($_POST['id_mkriteria_header']) ? $this->generate->kirana_decrypt($_POST['id_mkriteria_header']) : NULL);
					$nilai               = (isset($_POST['nilai']) ? $_POST['nilai'] : NULL);
					$this->cek_kriteria(NULL, $id_mkriteria_header, $nilai);
					break;
				case 'jaminan':
					$id_mjaminan_header = (isset($_POST['id_mjaminan_header']) ? $this->generate->kirana_decrypt($_POST['id_mjaminan_header']) : NULL);
					$active             = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted            = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_jaminan(NULL, $id_mjaminan_header, $active, $deleted);
					break;
				case 'jaminan-detail':
					$id_mjaminan_header = (isset($_POST['id_mjaminan_header']) ? $this->generate->kirana_decrypt($_POST['id_mjaminan_header']) : NULL);
					$return             = $this->dmasterumb->get_master_jaminan_detail("open", NULL, $id_mjaminan_header);
					$return             = $this->general->generate_encrypt_json($return, array("id_mjaminan_header", "id_mjaminan_detail"));
					echo json_encode($return);
					break;
				case 'dokumen':
					$id_mdokumen = (isset($_POST['id_mdokumen']) ? $this->generate->kirana_decrypt($_POST['id_mdokumen']) : NULL);
					$status      = (isset($_POST['status']) ? $_POST['status'] : NULL);
					$kepemilikan = (isset($_POST['kepemilikan']) ? $_POST['kepemilikan'] : NULL);
					$this->get_dokumen(NULL, $id_mdokumen, NULL, 'n', $status, $kepemilikan);
					break;
				case 'depo':
					$plant   = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
					$id      = (isset($_POST['id']) ? $_POST['id'] : NULL);
					$depo    = (isset($_POST['depo']) ? $_POST['depo'] : NULL);
					$all     = (isset($_POST['all']) ? $_POST['all'] : NULL);
					$tipe_um = (isset($_POST['tipe_um']) ? $_POST['tipe_um'] : NULL);
					$jnsdepo = NULL;
					if($tipe_um == "Ranger")
						$jnsdepo = "RNGER";

					$this->get_master_depo($id, $depo, $plant, $all, NULL, $jnsdepo);
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
				$action = "activate_na";
			}
			else if (isset($_POST['type']) && $_POST['type'] == "delete") {
				$action = "delete_na_del";
			}

			if ($action) {
				switch ($param) {
					case 'role':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_umb_role", array(
							array(
								'kolom' => 'kode_role',
								'value' => $this->generate->kirana_decrypt($_POST['kode'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'scoring':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_umb_mscoring_header", array(
							array(
								'kolom' => 'id_mscoring_header',
								'value' => $this->generate->kirana_decrypt($_POST['kode'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'kriteria':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_umb_mkriteria_header", array(
							array(
								'kolom' => 'id_mkriteria_header',
								'value' => $this->generate->kirana_decrypt($_POST['kode'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'jaminan':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_umb_mjaminan_header", array(
							array(
								'kolom' => 'id_mjaminan_header',
								'value' => $this->generate->kirana_decrypt($_POST['kode'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'dokumen':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_umb_mdokumen", array(
							array(
								'kolom' => 'id_mdokumen',
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
				case 'plafon':
					$this->save_plafon();
					break;
				case 'role':
					$this->save_role();
					break;
				case 'scoring':
					$this->save_scoring();
					break;
				case 'kriteria':
					$this->save_kriteria();
					break;
				case 'jaminan':
					$this->save_jaminan();
					break;
				case 'dokumen':
					$this->save_dokumen();
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
		private function get_plafon() {
			$active = (isset($_POST['active']) ? $_POST['active'] : NULL);
			$plant  = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
			$start  = (isset($_POST['start']) ? $_POST['start'] : NULL);
			$plafon = $this->dmasterumb->get_master_plafon("open", $plant, $active, $start, NULL);
			$plafon = $this->general->generate_encrypt_json($plafon, array("id_mplafon"));

			echo json_encode($plafon);
		}

		private function save_plafon() {
			$datetime = date("Y-m-d H:i:s");
			if (isset($_POST['plant_plafon'])) {
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'pdf';

				//================================SAVE SCORING HEADER================================//
				if ($_FILES['bukti_file']['name'][0] !== "" && $_POST['isnew'] == "yes") {
					if (count($_FILES['bukti_file']['name']) > 1) {
						$msg    = "You can only upload maximum 1 file";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$newname    = array("BuktiPerubahanPlafon_" . date("Ymd_His"));
					$bukti_file = $this->general->upload_files($_FILES['bukti_file'], $newname, $config);
				}

				$i = 0;
				foreach ($_POST['plant_plafon'] as $p) {
					if ($_POST['action'] == "approve") {
						$plafon = $this->dmasterumb->get_master_plafon("open", $p, true);
					}
					else {
						$plafon = $this->dmasterumb->get_master_plafon("open", $p, true, "NULL");
					}

					if ($plafon) {
						foreach ($plafon as $dt) {
							$data_row = array(
								"active"       => false,
								// "end_date"     => date('Y-m-d'),
								"end_date"     => date('Y-m-d', strtotime("-1 days")),
								'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit' => $datetime
							);

							if ($dt->start_date == NULL) {
								// $data_row["ranger"]       = str_replace(",", "", $_POST['ranger'][$i]);
								// $data_row["lain"]         = str_replace(",", "", $_POST['lain'][$i]);
								$data_row["limit_plafon"] = str_replace(",", "", $_POST['limit'][$i]);
								unset($data_row['active']);
								unset($data_row['end_date']);
							}

							if ($_POST['action'] == "approve" && $dt->start_date == NULL) {
								$data_row["active"]     = true;
								$data_row["start_date"] = date('Y-m-d');
							}
							else if ($_POST['action'] == "reject" && $dt->start_date == NULL) {
								$data_row["active"] = false;
							}

							if ($_POST['isnew'] == "yes") {
								$data_row["file_path"] = (isset($bukti_file) ? $bukti_file[0]['url'] : NULL);
							}

							$key      = array(
								array(
									'kolom' => 'id_mplafon',
									'value' => $dt->id_mplafon
								)
							);
							$data_row = $this->dgeneral->basic_column("update", $data_row);
							$this->dgeneral->update("tbl_umb_mplafon", $data_row, $key);
						}
					}
					else {
						$data_row = array(
							"kode_pabrik"  => $p,
							//sementara, karena harus drop tbl dulu kalau mau allow nulls
							"ranger"       => 0,
							"lain"         => 0,
							//===========================
							"limit_plafon" => str_replace(",", "", $_POST['limit'][$i]),
							"file_path"    => (isset($bukti_file) ? $bukti_file[0]['url'] : NULL),
							'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit' => $datetime
						);
						$data_row = $this->dgeneral->basic_column("insert_simple", $data_row);
						$this->dgeneral->insert("tbl_umb_mplafon", $data_row);
					}
					$i++;
				}

				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				}
				else {
					//					$this->dgeneral->rollback_transaction();
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

		/*====================================================================*/

		private function get_role($array = NULL, $kode = NULL, $level = NULL, $active = NULL, $deleted = NULL, $name = NULL, $exclude = NULL) {
			$role = $this->dmasterumb->get_master_role("open", $kode, $level, $active, $deleted, $name, $exclude);
			$role = $this->general->generate_encrypt_json($role, array("kode_role"));
			if ($array) {
				return $role;
			}
			else {
				echo json_encode($role);
			}
		}

		private function save_role() {
			$datetime = date("Y-m-d H:i:s");
			if (trim($_POST['role']) !== "" && trim($_POST['level']) !== "") {
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				if (isset($_POST['isRekom']) && $_POST['isRekom'] == 'on') {
					$isAksesRekom = 1;
				}
				else $isAksesRekom = 0;

				if (isset($_POST['isRenewal']) && $_POST['isRenewal'] == 'on') {
					$isAksesRenewal = 1;
				}
				else $isAksesRenewal = 0;

				if (isset($_POST['kode_role']) && trim($_POST['kode_role']) !== "") {
					$data_row = array(
						"nama_role"     => $_POST['role'],
						"level"         => $_POST['level'],
						"limit_app"     => (str_replace(",", "", $_POST['limit-app']) == 0 ? NULL : str_replace(",", "", $_POST['limit-app'])),
						"if_approve"    => ($_POST['if_approve'] == 0 ? NULL : $_POST['if_approve']),
						"if_assign"     => ($_POST['if_assign'] == 0 ? NULL : $_POST['if_assign']),
						"if_decline"    => ($_POST['if_decline'] == 0 ? NULL : $_POST['if_decline']),
						"if_drop"       => ($_POST['if_drop'] == 0 ? NULL : $_POST['if_drop']),
						"disposisi_nik" => (isset($_POST['disposisi']) ? $_POST['disposisi'] : NULL),
						"is_rekom"      => $isAksesRekom,
						"is_renewal"    => $isAksesRenewal,
						"akses_plafon"  => ($_POST['hak_akses_plafon'] == '0' ? NULL : $_POST['hak_akses_plafon'])
					);
					$data_row = $this->dgeneral->basic_column("update", $data_row);
					$this->dgeneral->update("tbl_umb_role", $data_row, array(
						array(
							'kolom' => 'kode_role',
							'value' => $this->generate->kirana_decrypt($_POST['kode_role'])
						)
					));

				}
				else {
					$role = $this->get_role('array', NULL, NULL, NULL, 'n', $_POST['role']);
					if (count($role) > 0) {
						$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}

					$data_row = array(
						"nama_role"     => $_POST['role'],
						"level"         => $_POST['level'],
						"limit_app"     => (str_replace(",", "", $_POST['limit-app']) == 0 ? NULL : str_replace(",", "", $_POST['limit-app'])),
						"if_approve"    => ($_POST['if_approve'] == 0 ? NULL : $_POST['if_approve']),
						"if_assign"     => ($_POST['if_assign'] == 0 ? NULL : $_POST['if_assign']),
						"if_decline"    => ($_POST['if_decline'] == 0 ? NULL : $_POST['if_decline']),
						"if_decline"    => ($_POST['if_drop'] == 0 ? NULL : $_POST['if_drop']),
						"disposisi_nik" => (isset($_POST['disposisi']) ? $_POST['disposisi'] : NULL),
						"is_rekom"      => $isAksesRekom,
						"is_renewal"    => $isAksesRenewal,
						"akses_plafon"  => ($_POST['hak_akses_plafon'] == '0' ? NULL : $_POST['hak_akses_plafon']),
						'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  => $datetime
					);

					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_umb_role", $data_row);
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

		/*====================================================================*/

		private function get_scoring($array = NULL, $id_mscoring_header = NULL, $active = NULL, $deleted = NULL, $id_scoring_tipe = NULL, $kelas = NULL) {
			$scoring = $this->dmasterumb->get_master_scoring("open", $id_mscoring_header, $active, $deleted, $id_scoring_tipe, $kelas);
			$scoring = $this->general->generate_encrypt_json($scoring, array("id_mscoring_header"));
			if ($array) {
				return $scoring;
			}
			else {
				echo json_encode($scoring);
			}
		}

		private function save_scoring() {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if (isset($_POST['id_mscoring_header']) && trim($_POST['id_mscoring_header']) !== "") {
				$kelas   = (!isset($_POST['kelas']) ? "" : $_POST['kelas']);
				$scoring = $this->get_scoring('array', NULL, NULL, 'n', $_POST['id_scoring_tipe'], $kelas);
				if ((count($scoring) > 0) and ($scoring[0]->id_mscoring_header != $_POST['id_mscoring_header'])) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"id_scoring_tipe" => $_POST['id_scoring_tipe'],
					"kelas"           => $kelas,
					"std_minimal"     => ($_POST['std_minimal'] == 0 && $_POST['id_scoring_tipe'] !== "3" ? NULL : (float)$_POST['std_minimal']),
					"min_bln_supply"  => ($_POST['min_bln_supply'] == 0 && $_POST['id_scoring_tipe'] !== "3" ? NULL : (float)$_POST['min_bln_supply']),
					"batas_bawah"     => (trim($_POST['batas_bawah']) == "" ? NULL : $_POST['batas_bawah']),
					"batas_atas"      => (trim($_POST['batas_atas']) == "" ? NULL : $_POST['batas_atas'])
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_umb_mscoring_header", $data_row, array(
					array(
						'kolom' => 'id_mscoring_header',
						'value' => $this->generate->kirana_decrypt($_POST['id_mscoring_header'])
					)
				));
				//update detail
				$i = 0;
				foreach ($_POST['score_awal'] as $p) {
					$id_mscoring_header = $this->generate->kirana_decrypt($_POST['id_mscoring_header']);
					$detail             = $this->dmasterumb->get_master_scoring_detail(NULL, NULL, $id_mscoring_header, $i, 'n');
					if ($detail) {
						foreach ($detail as $dt) {
							$data_row = array(
								"id_mscoring_header" => $id_mscoring_header,
								"no_urut"            => $i,
								"score_awal"         => $p,
								"score_akhir"        => str_replace(",", "", $_POST['score_akhir'][$i]),
								"UM"                 => str_replace(",", "", $_POST['uang_muka'][$i]),
								'login_edit'         => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'       => $datetime,
								'na'                 => 'n',
								'del'                => 'n'
							);
							$data_row = $this->dgeneral->basic_column("update", $data_row);
							$this->dgeneral->update("tbl_umb_mscoring_detail", $data_row, array(
								array(
									'kolom' => 'id_mscoring_detail',
									'value' => $dt->id_mscoring_detail
								)
							));
						}
					}
					else {
						$data_row = array(
							"id_mscoring_header" => $id_mscoring_header,
							"no_urut"            => $i,
							"score_awal"         => $p,
							"score_akhir"        => str_replace(",", "", $_POST['score_akhir'][$i]),
							"UM"                 => str_replace(",", "", $_POST['uang_muka'][$i]),
							'login_edit'         => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'       => $datetime
						);
						$data_row = $this->dgeneral->basic_column("insert_simple", $data_row);
						$this->dgeneral->insert("tbl_umb_mscoring_detail", $data_row);
					}
					$i++;
				}

			}
			else {
				$kelas   = (!isset($_POST['kelas']) ? "" : $_POST['kelas']);
				$scoring = $this->get_scoring('array', NULL, NULL, 'n', $_POST['id_scoring_tipe'], $kelas);
				if (count($scoring) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"id_scoring_tipe" => $_POST['id_scoring_tipe'],
					"kelas"           => $kelas,
					"std_minimal"     => ($_POST['std_minimal'] == 0 && $_POST['id_scoring_tipe'] !== "3" ? NULL : (float)$_POST['std_minimal']),
					"min_bln_supply"  => ($_POST['min_bln_supply'] == 0 && $_POST['id_scoring_tipe'] !== "3" ? NULL : (float)$_POST['min_bln_supply']),
					"batas_bawah"     => (trim($_POST['batas_bawah']) == "" ? NULL : $_POST['batas_bawah']),
					"batas_atas"      => (trim($_POST['batas_atas']) == "" ? NULL : $_POST['batas_atas']),
					"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"    => $datetime
				);

				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_umb_mscoring_header", $data_row);
				$id_mscoring_header = $this->db->insert_id();
				//insert detail
				$i = 0;
				foreach ($_POST['score_awal'] as $p) {
					$data_row = array(
						"id_mscoring_header" => $id_mscoring_header,
						"no_urut"            => $i,
						"score_awal"         => $p,
						"score_akhir"        => str_replace(",", "", $_POST['score_akhir'][$i]),
						"UM"                 => str_replace(",", "", $_POST['uang_muka'][$i]),
						'login_edit'         => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'       => $datetime
					);
					$data_row = $this->dgeneral->basic_column("insert_simple", $data_row);
					$this->dgeneral->insert("tbl_umb_mscoring_detail", $data_row);
					$i++;
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

		/*====================================================================*/
		private function cek_kriteria($array = NULL, $id_mkriteria_header = NULL, $nilai = NULL) {
			$kriteria = $this->dmasterumb->cek_master_kriteria("open", $id_mkriteria_header, $nilai);
			if ($array) {
				return $kriteria;
			}
			else {
				echo json_encode($kriteria);
			}
		}

		private function get_kriteria($array = NULL, $id_mkriteria_header = NULL, $active = NULL, $deleted = NULL, $id_mjenis_kriteria = NULL, $satuan = NULL, $kelas = NULL) {
			$kriteria = $this->dmasterumb->get_master_kriteria("open", $id_mkriteria_header, $active, $deleted, $id_mjenis_kriteria, $satuan, $kelas);
			$kriteria = $this->general->generate_encrypt_json($kriteria, array("id_mkriteria_header"));
			if ($array) {
				return $kriteria;
			}
			else {
				echo json_encode($kriteria);
			}
		}

		private function save_kriteria() {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if (isset($_POST['id_mkriteria_header']) && trim($_POST['id_mkriteria_header']) !== "") {
				$kriteria = $this->get_kriteria('array', NULL, NULL, 'n', $_POST['id_mjenis_kriteria'], NULL, $_POST['kelas']);
				if ((count($kriteria) > 0) and ($kriteria[0]->id_mkriteria_header != $_POST['id_mkriteria_header'] || $kriteria[0]->kelas != $_POST['kelas'])) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				$data_row = array(
					"id_mjenis_kriteria" => $_POST['id_mjenis_kriteria'],
					"kelas"              => $_POST['kelas'],
					"persen_bobot"       => $_POST['persen_bobot'],
					"satuan"             => $_POST['satuan'],
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_umb_mkriteria_header", $data_row, array(
					array(
						'kolom' => 'id_mkriteria_header',
						'value' => $this->generate->kirana_decrypt($_POST['id_mkriteria_header'])
					)
				));
				//insert detail
				$i = 0;
				foreach ($_POST['param_awal'] as $p) {
					$id_mkriteria_header = $this->generate->kirana_decrypt($_POST['id_mkriteria_header']);
					$detail              = $this->dmasterumb->get_master_kriteria_detail(NULL, NULL, $id_mkriteria_header, $i, 'n');
					if ($detail) {
						foreach ($detail as $dt) {
							$data_row = array(
								"id_mkriteria_header" => $id_mkriteria_header,
								"no_urut"             => $i,
								"param_awal"          => $p,
								"param_akhir"         => str_replace(",", "", $_POST['param_akhir'][$i]),
								"nilai"               => str_replace(",", "", $_POST['nilai'][$i]),
								'login_edit'          => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'        => $datetime
							);
							$data_row = $this->dgeneral->basic_column("update", $data_row);
							$this->dgeneral->update("tbl_umb_mkriteria_detail", $data_row, array(
								array(
									'kolom' => 'id_mkriteria_detail',
									'value' => $dt->id_mkriteria_detail
								)
							));
						}
					}
					else {
						$data_row = array(
							"id_mkriteria_header" => $id_mkriteria_header,
							"no_urut"             => $i,
							"param_awal"          => $p,
							"param_akhir"         => str_replace(",", "", $_POST['param_akhir'][$i]),
							"nilai"               => str_replace(",", "", $_POST['nilai'][$i]),
							'login_edit'          => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'        => $datetime
						);
						$data_row = $this->dgeneral->basic_column("insert_simple", $data_row);
						$this->dgeneral->insert("tbl_umb_mkriteria_detail", $data_row);
					}
					$i++;
				}

			}
			else {
				// $kriteria = $this->get_kriteria('array', NULL, NULL, 'n', $_POST['id_mjenis_kriteria'], $_POST['satuan']);
				$kriteria = $this->get_kriteria('array', NULL, NULL, 'n', $_POST['id_mjenis_kriteria'], NULL, $_POST['kelas']);
				if (count($kriteria) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"id_mjenis_kriteria" => $_POST['id_mjenis_kriteria'],
					"kelas"              => $_POST['kelas'],
					"persen_bobot"       => $_POST['persen_bobot'],
					"satuan"             => $_POST['satuan'],
					"login_edit"         => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"       => $datetime
				);

				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_umb_mkriteria_header", $data_row);
				$id_mkriteria_header = $this->db->insert_id();
				//insert detail
				$i = 0;
				foreach ($_POST['param_awal'] as $p) {
					$data_row = array(
						"id_mkriteria_header" => $id_mkriteria_header,
						"no_urut"             => $i,
						"param_awal"          => $p,
						"param_akhir"         => str_replace(",", "", $_POST['param_akhir'][$i]),
						"nilai"               => str_replace(",", "", $_POST['nilai'][$i]),
						'login_edit'          => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'        => $datetime
					);
					$data_row = $this->dgeneral->basic_column("insert_simple", $data_row);
					$this->dgeneral->insert("tbl_umb_mkriteria_detail", $data_row);
					$i++;
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

		/*====================================================================*/

		private function get_jaminan($array = NULL, $id_mjaminan_header = NULL, $active = NULL, $deleted = NULL, $jenis = NULL) {
			$jaminan = $this->dmasterumb->get_master_jaminan("open", $id_mjaminan_header, $active, $deleted, $jenis);
			$jaminan = $this->general->generate_encrypt_json($jaminan, array("id_mjaminan_header"));
			if ($array) {
				return $jaminan;
			}
			else {
				echo json_encode($jaminan);
			}
		}

		private function save_jaminan() {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if (isset($_POST['id_mjaminan_header']) && trim($_POST['id_mjaminan_header']) !== "") {
				$jaminan = $this->get_jaminan('array', NULL, NULL, 'n', $_POST['jenis']);
				if ((count($jaminan) > 0) and ($jaminan[0]->id_mjaminan_header != $_POST['id_mjaminan_header'])) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"jenis" => $_POST['jenis']
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_umb_mjaminan_header", $data_row, array(
					array(
						'kolom' => 'id_mjaminan_header',
						'value' => $this->generate->kirana_decrypt($_POST['id_mjaminan_header'])
					)
				));
				//insert detail
				$i = 0;
				foreach ($_POST['detail'] as $p) {
					$id_mjaminan_header = $this->generate->kirana_decrypt($_POST['id_mjaminan_header']);
					$detail             = $this->dmasterumb->get_master_jaminan_detail(NULL, NULL, $id_mjaminan_header, $i, 'n');
					if ($detail) {
						foreach ($detail as $dt) {
							$data_row = array(
								"id_mjaminan_header" => $id_mjaminan_header,
								"no_urut"            => $i,
								"detail"             => $p,
								"persen_discount"    => str_replace(",", "", $_POST['persen_discount'][$i]),
								'login_edit'         => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'       => $datetime
							);
							$data_row = $this->dgeneral->basic_column("update", $data_row);
							$this->dgeneral->update("tbl_umb_mjaminan_detail", $data_row, array(
								array(
									'kolom' => 'id_mjaminan_detail',
									'value' => $dt->id_mjaminan_detail
								)
							));
						}
					}
					else {
						$data_row = array(
							"id_mjaminan_header" => $id_mjaminan_header,
							"no_urut"            => $i,
							"detail"             => $p,
							"persen_discount"    => str_replace(",", "", $_POST['persen_discount'][$i]),
							'login_edit'         => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'       => $datetime
						);
						$data_row = $this->dgeneral->basic_column("insert_simple", $data_row);
						$this->dgeneral->insert("tbl_umb_mjaminan_detail", $data_row);
					}
					$i++;
				}

			}
			else {
				$jaminan = $this->get_jaminan('array', NULL, NULL, 'n', $_POST['jenis']);
				if (count($jaminan) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"jenis"        => $_POST['jenis'],
					"login_edit"   => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" => $datetime
				);

				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_umb_mjaminan_header", $data_row);
				$id_mjaminan_header = $this->db->insert_id();
				//insert detail
				$i = 0;
				foreach ($_POST['detail'] as $p) {
					$data_row = array(
						"id_mjaminan_header" => $id_mjaminan_header,
						"no_urut"            => $i,
						"detail"             => $p,
						"persen_discount"    => str_replace(",", "", $_POST['persen_discount'][$i]),
						'login_edit'         => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'       => $datetime
					);
					$data_row = $this->dgeneral->basic_column("insert_simple", $data_row);
					$this->dgeneral->insert("tbl_umb_mjaminan_detail", $data_row);
					$i++;
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

		/*====================================================================*/

		private function get_dokumen($array = NULL, $id_mdokumen = NULL, $active = NULL, $deleted = NULL, $status = NULL, $kepemilikan = NULL) {
			$dokumen = $this->dmasterumb->get_master_dokumen("open", $id_mdokumen, $active, $deleted, $status, $kepemilikan);
			$dokumen = $this->general->generate_encrypt_json($dokumen, array("id_mdokumen"));
			if ($array) {
				return $dokumen;
			}
			else {
				echo json_encode($dokumen);
			}
		}

		private function save_dokumen() {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if (isset($_POST['id_mdokumen']) && trim($_POST['id_mdokumen']) !== "") {
				$dokumen = $this->get_dokumen('array', NULL, NULL, 'n', $_POST['status'], $_POST['kepemilikan']);
				if ((count($dokumen) > 0) and ($dokumen[0]->id_mdokumen != $_POST['id_mdokumen'])) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$list = "";
				foreach ($_POST['document'] as $doc) {
					$list .= $doc . ",";
				}
				$list_dokumen = isset($_POST['document']) ? substr($list, 0, -1) : '';
				$data_row     = array(
					"status"      => $_POST['status'],
					"kepemilikan" => $_POST['kepemilikan'],
					"document"    => $list_dokumen
				);
				$data_row     = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_umb_mdokumen", $data_row, array(
					array(
						'kolom' => 'id_mdokumen',
						'value' => $this->generate->kirana_decrypt($_POST['id_mdokumen'])
					)
				));
			}
			else {
				$dokumen = $this->get_dokumen('array', NULL, NULL, 'n', $_POST['status'], $_POST['kepemilikan']);
				if (count($dokumen) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				$list = "";
				foreach ($_POST['document'] as $doc) {
					$list .= $doc . ",";
				}
				$list_dokumen = isset($_POST['document']) ? substr($list, 0, -1) : '';
				$data_row     = array(
					"status"       => $_POST['status'],
					"kepemilikan"  => $_POST['kepemilikan'],
					"document"     => $list_dokumen,
					"login_edit"   => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" => $datetime
				);
				$data_row     = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_umb_mdokumen", $data_row);
				$id_mdokumen = $this->db->insert_id();
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
		/*====================================================================*/

	}

?>
