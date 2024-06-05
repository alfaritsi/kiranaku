<?php
	/*
    @application  : K-IASS
    @author       : MATTHEW JODI (8944)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */

	include_once APPPATH . "modules/kiass/controllers/BaseControllers.php";

	class Deviasi extends BaseControllers {
		public function __construct() {
			parent::__construct();
			$this->data['module'] = "K-IASS";
			$this->load->model('dmasterscrap');
			$this->load->model('dmainscrap');
			$this->load->model('ddeviasiscrap');
		}

		public function lists() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "List Pengajuan Deviasi";
			$this->data['title_form'] = "List Pengajuan Deviasi";

			$this->data['tahun'] = $this->ddeviasiscrap->get_deviasi_header_tahun(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
			));

			$this->data['list'] = $this->ddeviasiscrap->get_deviasi_header(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
				'year' 		=> date("Y"),
				'plant'		=> explode(",", $this->data['session_role'][0]->pabrik)
			));
			

			$this->data['session_role'] = $this->data['session_role'];

			$this->data['pabrik']       = $this->get_master_plant(explode(",", $this->data['session_role'][0]->pabrik), false, NULL, "array");

			$this->load->view("deviasi/lists", $this->data);
		}

        public function approval() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Approval Pengajuan Deviasi";
			$this->data['title_form'] = "Approval Pengajuan Deviasi";

			$this->data['tahun'] = $this->ddeviasiscrap->get_deviasi_header_tahun(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
			));

			$this->data['list'] = $this->ddeviasiscrap->get_deviasi_header(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
				'year' 		=> date("Y"),
				'approval'	=> array($this->data['session_role'][0]->level),
				'plant'		=> explode(",", $this->data['session_role'][0]->pabrik)
			));
			

			$this->data['session_role'] = $this->data['session_role'];

			$this->data['pabrik']       = $this->get_master_plant(explode(",", $this->data['session_role'][0]->pabrik), false, NULL, "array");


			$this->load->view("deviasi/approval", $this->data);
		}

		public function tambah($key = NULL) {
			$this->general->check_session($_SERVER['REQUEST_URI']);
			if (!isset($key)) {
				show_404();
			}
			$plant = explode("-", $key)[2];
			$key   = str_replace("-", "/", $key);
			//====must be initiate in every view function====/
			// $this->general->check_access();
			//===============================================/


			// TAMBAHIN CEK STATUS CAPEX AKTIF BY NO PP

			$this->data['title']      = "Form Pengajuan Deviasi";
			$this->data['title_form'] = "Pengajuan Deviasi";

			$no_deviasi = $key;
			$no_deviasi[0] = 'D';
			$no_deviasi[1] = 'V';

			$this->data['no_deviasi'] = $no_deviasi;
			$this->data['no_pp'] = $key;

			$this->load->view("deviasi/add", $this->data);
		}

		public function detail($key = NULL) {
			$this->general->check_session($_SERVER['REQUEST_URI']);
			
			if (!isset($key)) {
				show_404();
			}

			$plant = explode("-", $key)[2];
			$key   = str_replace("-", "/", $key);

			if (in_array($plant, explode(",", $this->data['session_role'][0]->pabrik)) == false) {
				show_404();
			}
			
			$dt = $this->ddeviasiscrap->get_deviasi_header(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_deviasi" => $key,
				"single_row" => true 
			));

			if($dt->status == $this->data['session_role'][0]->level){
				$this->data['approval'] = $data = $this->dmasterscrap->get_master_role_detail(array(
																	"connect" => NULL,
																		"app"   		=> 'kiass',
																		"single_row"   	=> TRUE,
																		"id_flow" 		=> $dt->id_flow,
																		"level" 		=> $this->data['session_role'][0]->level
																	));				
			}


			$this->data['no_deviasi'] = $key;
			$this->data['session_role'] = $this->data['session_role'];

			$this->load->view('deviasi/detail', $this->data);
		}

		public function edit($key = NULL) {
			$this->general->check_session($_SERVER['REQUEST_URI']);
			if (!isset($key)) {
				show_404();
			}
			$key   = str_replace("-", "/", $key);
			//====must be initiate in every view function====/
			// $this->general->check_access();
			//===============================================/
			$this->data['title']      = "Form Edit Deviasi";
			$this->data['title_form'] = "Pengajuan Deviasi";

			$this->data['no_deviasi'] = $key;


			$this->load->view("deviasi/edit", $this->data);
		}


		public function get($param = NULL) {
			switch ($param) {
				case 'pengajuan':
					$this->get_data_pengajuan();
					break;
				case 'deviasi':
					$this->get_data_deviasi();
					break;
				
				case 'lists':
					$this->get_data_header();
					break;
				
				case 'log-status':
					$this->get_log_status();
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		public function save($param = NULL) {
			switch ($param) {
				case 'deviasi':
					$this->save_deviasi();
					break;
				case 'fincon':
					$this->save_fincon();
					break;
				case 'approval':
					$this->save_approval();
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		private function generate_no_deviasi() {
			$separator = "/";
			$plant     = $this->data['session_role'][0]->pabrik;
			$month     = date('m');
			$year      = date('Y');
			$kmtr      = 'KMTR';
			$cek = $this->ddeviasiscrap->get_no_deviasi(array(
																"connect" => NULL,
																	"app"   => 'kiass',
																	"year" => $year,
																	"plant" => $plant,
																	"month" => $month
																));
																
			$no = (count($cek) + 1);
			return  $no . $separator . $plant . $separator . $kmtr . $separator . $month . $separator . $year;
		}

		private function generate_status($param = NULL)
		{
			$action 	= $param['action'];
			$no_deviasi = $param['no_deviasi'];
			$id_flow 	= $param['id_flow'];


			if($action == 'submit' || $action == 'edit'){
				$level = $this->data['session_role'][0]->level;
			}else{
				$dataheader = $this->ddeviasiscrap->get_deviasi_header(array(
					"connect" => NULL,
					"app"   => 'kiass',
					"no_deviasi" => $no_deviasi,
					"single_row" => true 
				));

				$pembeli = $dataheader->pembeli;
				$level = $dataheader->status;

			}

			$data = $this->dmasterscrap->get_master_role_detail(array(
				"connect" => NULL,
					"app"   		=> 'kiass',
					"single_row"   	=> TRUE,
					"id_flow" 		=> $id_flow,
					"level" 		=> $level

				));
			
			
			
			switch ($action) {
				case 'submit'   :
				case 'edit'     :
					$status = $data->if_approve_capex;
					break;

				case 'approve'  :
					

					if($data->app_lim_val_capex > 0 &&  $dataheader->nilai_pengajuan <= $data->app_lim_val_capex){
						$status = 'finish';
					}else{
						$status = $data->if_approve_capex;
					}

					if($pembeli !== 'pihakKetiga' && $status == '7'){
						$datas = $this->dmasterscrap->get_master_role_detail(array(
							"connect" => NULL,
								"app"   		=> 'kiass',
								"single_row"   	=> TRUE,
								"id_flow" 		=> $id_flow,
								"level" 		=> $status
			
							));

						$status = $datas->if_approve_capex;
					}

					break;

				case 'assign'  :
					$status = $data->if_assign_capex;
					break;

				case 'decline'  :
					$status = $data->if_decline_capex;

					if($pembeli !== 'pihakKetiga' && $status == '7'){
						$datas = $this->dmasterscrap->get_master_role_detail(array(
							"connect" => NULL,
								"app"   		=> 'kiass',
								"single_row"   	=> TRUE,
								"id_flow" 		=> $id_flow,
								"level" 		=> $status
			
							));

						$status = $datas->if_decline_capex;
					}

					break;

				case 'drop'     :
					$status = 'drop';
					break;
				case 'stop'     :
					$status = 'stop';
					break;
				case 'finish'   :
					$status = "finish";
					break;

				default         :
					break;
			}
			return $status;

		}

		private function get_log_status() {
			$log_status = $this->ddeviasiscrap->get_log_status(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_deviasi" => $_POST['no_deviasi']
			));

			echo json_encode($log_status);
		}

		private function get_data_pengajuan() {
			$no_pp = (isset($_POST['no_pp']) ? $_POST['no_pp'] : NULL);
			$data = $this->ddeviasiscrap->get_data_pengajuan(array(
				"connect" 		=> NULL,
				"app"   		=> 'kiass',
				'no_pp' 		=> $no_pp
			));

			echo json_encode($data);
		}

		private function get_data_header() {
			$tahun = (isset($_POST['tahun']) ? $_POST['tahun'] : NULL);
			$plant = (isset($_POST['plant']) ? $_POST['plant'] : explode(",", $this->data['session_role'][0]->pabrik));
			$approval = (isset($_POST['approval']) && $_POST['approval'] == 'yes'  ? $this->data['session_role'][0]->level : NULL);
			$status = (isset($_POST['status']) && $_POST['status'] !== "" ? $_POST['status'] : NULL);
			$all    = array("onprogress", "finish", "drop", "deleted");
			if ($status !== NULL) {
				if (in_array("onprogress", $status) == true) {
					$filter = array_diff($all, $status);
					$query  = "NOT IN";
				}
				else {
					$filter = $status;
					$query  = "IN";
				}
				$filter = "'" . implode("','", $filter) . "'";
			}
			else {
				$filter = NULL;
				$query  = NULL;
			}

			$data = $this->ddeviasiscrap->get_deviasi_header(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"year" => $tahun,
				"plant" => $plant,
				"approval" => $approval,
				"status_in" => $filter,
				"in_not_in" => $query
			));

			echo json_encode($data);
		}

		private function get_data_deviasi() {
			$no_deviasi = (isset($_POST['no_deviasi']) ? $_POST['no_deviasi'] : NULL);
			
			$data['header'] = $this->ddeviasiscrap->get_deviasi_header(array(
				"connect" 		=> NULL,
				"app"   		=> 'kiass',
				"single_row"	=> TRUE,
				'no_deviasi' 	=> $no_deviasi
			));

			$data['detail'] = $this->ddeviasiscrap->get_deviasi_detail(array(
				"connect" 		=> NULL,
				"app"   		=> 'kiass',
				'no_deviasi' 	=> $no_deviasi
			));

			$data['session_role'] = $this->data['session_role'][0];

			echo json_encode($data);
		}

		private function save_deviasi(){

			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();

			
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if (isset($param['action']) && trim($param['action']) == "edit"){

				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|jpeg|png|pdf';

				//================================SAVE DEVIASI HEADER================================//
				
				$id_lampiran_deviasi = "";
				if ($_FILES['lampiran']['name'][0] !== "") {
					if (count($_FILES['lampiran']['name']) > 1) {
						$msg    = "You can only upload maximum 1 file";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$newname  = array(str_replace("/", "-", $_POST['no_deviasi']) . "-lampiran");
					$file_lampiran = $this->general->upload_files($_FILES['lampiran'], $newname, $config);
					$data_lampiran = array(
						"no_pp"    				=> $param['no_deviasi'],
						"filename"    			=> $newname[0],
						"size"    				=> $file_lampiran[0]['size'],
						"ext"    				=> pathinfo($file_lampiran[0]['full_path'], PATHINFO_EXTENSION),
						"location"    			=> $file_lampiran[0]['url'],
						"desc"    				=> "Lampiran Deviasi"
					);
	
					// echo json_encode($file_lampiran);exit();
	
	
					$data_row_lampiran     = $this->dgeneral->basic_column("insert", $data_lampiran);
					$this->dgeneral->insert("tbl_scrap_file", $data_row_lampiran);
					$id_lampiran_deviasi = $this->db->insert_id();
				}


				$data_scrap_header     = array(
					"latar_belakang"		=> $param['latar_belakang'],
					"selisih"				=> NULL,
					"status"				=> $this->generate_status(
																		array(
																			"action" 		=>  'edit',
																			"no_deviasi" 	=>  $param['no_deviasi'],
																			"id_flow" 		=>  $param['id_flow']
																		)
																	),
					"id_lampiran_deviasi"	=> $id_lampiran_deviasi
				);

				if ($id_lampiran_deviasi == "")
					unset($data_scrap_header['id_lampiran_deviasi']);

				$data_row_header     = $this->dgeneral->basic_column("update", $data_scrap_header);
				$this->dgeneral->update("tbl_scrap_deviasi_header", $data_row_header, array(
					array(
						'kolom' => 'no_deviasi',
						'value' => $param['no_deviasi']
					)
				));


				for ($j=0; $j < $param['counter'] ; $j++) {
					$data_analisa_harga     = array(
						"qty_deviasi"			=> $param['qty_row'.($j+1)],
						"harga_deviasi"			=> str_replace(",", "",$param['harga_deviasi_row'.($j+1)]),
						"total_deviasi"			=> str_replace(",", "",$param['total_deviasi_row'.($j+1)]),
						"keterangan"			=> $param['keterangan_row'.($j+1)],
						"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit" 			=> $datetime
					);
					
					$data_row_analisa_harga     = $this->dgeneral->basic_column("update", $data_analisa_harga);
					$this->dgeneral->update("tbl_scrap_deviasi_detail", $data_row_analisa_harga, array(
						array(
							'kolom' => 'no_deviasi',
							'value' => $param['no_deviasi']
						),
						array(
							'kolom' => 'id_row_analisa',
							'value' => $param['id_row_analisa_row'.($j+1)]
						)
					));

				}

			}else{

				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|jpeg|png|pdf';

				//================================SAVE DEVIASI HEADER================================//
				
				$id_lampiran_deviasi = "";
				if ($_FILES['lampiran']['name'][0] !== "") {
					if (count($_FILES['lampiran']['name']) > 1) {
						$msg    = "You can only upload maximum 1 file";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$newname  = array(str_replace("/", "-", $_POST['no_deviasi']) . "-lampiran");
					$file_lampiran = $this->general->upload_files($_FILES['lampiran'], $newname, $config);
					$data_lampiran = array(
						"no_pp"    				=> $param['no_deviasi'],
						"filename"    			=> $newname[0],
						"size"    				=> $file_lampiran[0]['size'],
						"ext"    				=> pathinfo($file_lampiran[0]['full_path'], PATHINFO_EXTENSION),
						"location"    			=> $file_lampiran[0]['url'],
						"desc"    				=> "Lampiran Deviasi"
					);
	
					// echo json_encode($file_lampiran);exit();
	
	
					$data_row_lampiran     = $this->dgeneral->basic_column("insert", $data_lampiran);
					$this->dgeneral->insert("tbl_scrap_file", $data_row_lampiran);
					$id_lampiran_deviasi = $this->db->insert_id();
				}


				$data_scrap_header     = array(
					"plant"    				=> explode("/", $param['no_pp'])[2],
					"no_deviasi"    		=> $param['no_deviasi'],
					"no_pp"    				=> $param['no_pp'],
					"latar_belakang"		=> $param['latar_belakang'],
					"tanggal_pengajuan"		=> date('Y-m-d', strtotime($param['tgl_pengajuan'])),
					"selisih"				=> NULL,
					"id_flow"				=> $param['id_flow'],
					"counter_item"			=> $param['counter'],
					"status"				=> $this->generate_status(
																		array(
																			"action" 		=>  'submit',
																			"no_deviasi" 	=>  $param['no_deviasi'],
																			"id_flow" 		=>  $param['id_flow']
																		)
																	),
					"id_lampiran_deviasi"	=> $id_lampiran_deviasi,
					"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" 			=> $datetime
				);

				$data_row_header     = $this->dgeneral->basic_column("insert", $data_scrap_header);
				$this->dgeneral->insert("tbl_scrap_deviasi_header", $data_row_header);	

				for ($j=0; $j < $param['counter'] ; $j++) {
					$data_analisa_harga     = array(
						"plant"					=> explode("/", $param['no_pp'])[2],
						"no_pp"    				=> $param['no_pp'],
						"no_deviasi"    		=> $param['no_deviasi'],
						"no_so"					=> $param['so_row'.($j+1)],
						"id_row_analisa"		=> $param['id_row_analisa_row'.($j+1)],
						"id_calon_pembeli"		=> $param['id_calon_pembeli_row'.($j+1)],
						"kode_customer"			=> $param['customer_row'.($j+1)],
						"nama_pembeli"			=> $param['nama_pembeli_row'.($j+1)],
						"qty_deviasi"			=> $param['qty_row'.($j+1)],
						"harga_deviasi"			=> str_replace(",", "",$param['harga_deviasi_row'.($j+1)]),
						"total_deviasi"			=> str_replace(",", "",$param['total_deviasi_row'.($j+1)]),
						"keterangan"			=> $param['keterangan_row'.($j+1)],
						"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit" 			=> $datetime
					);
					$data_row_analisa_harga     = $this->dgeneral->basic_column("insert", $data_analisa_harga);
					$this->dgeneral->insert("tbl_scrap_deviasi_detail", $data_row_analisa_harga);

				}

			}


			$data_log = array(
				"no_pp" 		  => $param['no_pp'],
				"no_deviasi"	  => $param['no_deviasi'],
				"tgl_status"      => $datetime,
				"action"          => $param['action'],
				"status"          => $this->data['session_role'][0]->level,
				"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit"    => $datetime,
				"comment"         => isset($param['komentar']) ? $param['komentar'] : NULL
			);
			$this->dgeneral->insert("tbl_scrap_log_status_deviasi", $data_log);


			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			else {
				$this->dgeneral->commit_transaction();
				$this->generate_message_email($param['no_deviasi'], $param['action'], $param['no_pp']);
				$msg = "Data berhasil ditambahkan";
				$sts = "OK";
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);

		}

		private function save_fincon(){

			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();
			$dataheader = $this->ddeviasiscrap->get_deviasi_header(array(
				"connect" 		=> NULL,
				"app"   		=> 'kiass',
				"single_row"	=> TRUE,
				'no_deviasi' 	=> $param['no_deviasi']
			));

			$data_scrap_header     = array(
				"status"				=> $this->generate_status(
																	array(
																		"action" 		=>  'approve',
																		"no_deviasi" 	=>  $param['no_deviasi'],
																		"id_flow" 		=>  $param['id_flow']
																	)
																),
				"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit" 			=> $datetime
			);

			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if($dataheader->status == '5'){
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'pdf';

				$id_lampiran_fincon = "";
				if (isset($_FILES['lampiran_fincon']) && $_FILES['lampiran_fincon']['name'][0] !== "") {
					if (count($_FILES['lampiran_fincon']['name']) > 1) {
						$msg    = "You can only upload maximum 1 file";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$newname  = array(str_replace("/", "-", $param['no_deviasi']) . "-lampiran-fincon");
					$file_lampiran = $this->general->upload_files($_FILES['lampiran_fincon'], $newname, $config);
					$data_row_lampiran = array(
						"no_pp"    				=> $param['no_pp'],
						"filename"    			=> $newname[0],
						"size"    				=> $file_lampiran[0]['size'],
						"ext"    				=> pathinfo($file_lampiran[0]['full_path'], PATHINFO_EXTENSION),
						"location"    			=> $file_lampiran[0]['url'],
						"desc"    				=> "Lampiran Deviasi"
					);

					$data_row_lampiran = $this->dgeneral->basic_column("insert", $data_row_lampiran);
					$this->dgeneral->insert("tbl_scrap_file", $data_row_lampiran);
					$id_lampiran_fincon = $this->db->insert_id();
					$data_scrap_header["lampiran_fincon"] = $id_lampiran_fincon;
				}
			}

			$this->dgeneral->update('tbl_scrap_deviasi_header', $data_scrap_header, array(
				array(
					'kolom' => 'no_deviasi',
					'value' => $param['no_deviasi']
				)
			));

			for ($j=0; $j < $param['counter'] ; $j++) {
				$data_analisa_harga     = array(
					"qty_deviasi"			=> $param['qty_row'.($j+1)],
					"harga_deviasi"			=> str_replace(",", "",$param['harga_deviasi_row'.($j+1)]),
					"total_deviasi"			=> str_replace(",", "",$param['total_deviasi_row'.($j+1)]),
				);
				
				$data_row_analisa_harga     = $this->dgeneral->basic_column("update", $data_analisa_harga);
				$this->dgeneral->update("tbl_scrap_deviasi_detail", $data_row_analisa_harga, array(
					array(
						'kolom' => 'no_deviasi',
						'value' => $param['no_deviasi']
					),
					array(
						'kolom' => 'id_row_analisa',
						'value' => $param['id_row_analisa_row'.($j+1)]
					)
				));
			}

			$data_log = array(
				"no_pp" 		  => $param['no_pp'],
				"no_deviasi"	  => $param['no_deviasi'],
				"tgl_status"      => $datetime,
				"action"          => 'approve',
				"status"          => $this->data['session_role'][0]->level,
				"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit"    => $datetime,
				"comment"         => isset($param['komentar_fincon']) ? $param['komentar_fincon'] : NULL
			);
			$this->dgeneral->insert("tbl_scrap_log_status_deviasi", $data_log);

			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			else {
				$this->dgeneral->commit_transaction();
				$this->generate_message_email($param['no_deviasi'], 'approve', $param['no_pp'], isset($param['komentar_fincon']) ? $param['komentar_fincon'] : NULL);
				$msg = "Data berhasil ditambahkan";
				$sts = "OK";
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);

		}

		private function save_approval() {
			
			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();
			// echo json_encode($param);exit();
			$dataheader = $this->ddeviasiscrap->get_deviasi_header(array(
				"connect" => NULL,
				"app"   => 'scrap',
				"no_deviasi" => $param['no_deviasi'],
				"single_row" => true 
			));

			$current_status = $dataheader->status;

			$data_scrap_header     = array(
				"status"				=> $this->generate_status(
																	array(
																		"action" 	=>  $param['action'],
																		"no_deviasi" 	=>  $param['no_deviasi'],
																		"id_flow" 	=>  $param['id_flow']
																	)
																),
				"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit" 			=> $datetime
			);

			//butuh nilai total deviasi disini

			if ($param['action'] == 'approve'){

				// KONDISI KHUSUS

				// Persediaan dapat dijual ke pihak ketiga apabila mendapatkan rekomendasi dari FC Dept. H dan persetujuan sampai CFO. 
				// Jika persediaan di jual ke afiliasi maka persetujuan cukup sampai dengan CSC.
				// DEFAULT FLOW PERSEDIAN SPR CFO
				// affliasi csc finish
				// PERSEDIAAN id_flow = '3'
				if($param['id_flow'] == '3' && $dataheader->pembeli !== 'pihakKetiga' && $this->data['session_role'][0]->nama_role == 'CSC'){
					$data_scrap_header['status'] = 'finish';
				}

				// Limbah B3 dapat dijual ke pihak selain yang sudah diatur dalam SPK penjualan limbah B3 setelah mendapatkan 
				// Persetujuan CFO dengan adanya konfirmasi dari procurement HO. Jika Limbah B3 dijual ke pihak yang sudah diatur dalam SPK 
				// penjualan limbah B3 maka persetujuan cukup sampai dengan CSC.
				// DEFAULt LB3 ke CFO
				// if is_spk finish di CSC
				// LB3 id_flow = '4'
				// - limbah b3 spk dicentang csc
				if($param['id_flow'] == '4' && $dataheader->is_spk == 'y' && $this->data['session_role'][0]->nama_role == 'CSC'){
					$data_scrap_header['status'] = 'finish';
				}

				// Berlaku sesuai limit otorisasi persetujuan, dimana barang bekas (selain limbah B3) di bawah Rp 10jt s/d Div Head Fincon 
				// SLB3 id_flow = '5'
				// SUDAH masuk ke logic di generate status
				// if($param['id_flow'] == '5' && $this->data['session_role'][0]->nama_role == 'Finance Controller Div Head'){
				// 	$data_scrap_header['status'] = 'finish';
				// }
				


			}


			// echo json_encode($param);exit();

			if($data_scrap_header['status'] == 'finish'){
				$this->changeSO($param['no_deviasi']);
			}




			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();


			$this->dgeneral->update('tbl_scrap_deviasi_header', $data_scrap_header, array(
				array(
					'kolom' => 'no_deviasi',
					'value' => $param['no_deviasi']
				)
			));

			
			$data_log = array(
				"no_deviasi" 	  => $param['no_deviasi'],
				"no_pp"		 	  => $param['no_pp'],
				"tgl_status"      => $datetime,
				"action"          => $param['action'],
				"status"          => $current_status,
				"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit"    => $datetime,
				"comment"         => isset($param['komentar']) ? $param['komentar'] : NULL
			);
			$this->dgeneral->insert("tbl_scrap_log_status_deviasi", $data_log);


			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			else {
				$this->dgeneral->commit_transaction();
				$this->generate_message_email($param['no_deviasi'], $param['action'], $param['no_pp'], $param['komentar']);
				$msg = "Data berhasil ditambahkan";
				$sts = "OK";
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);


		}

		public function changeSO($no_deviasi) {
			$datetime = date("Y-m-d H:i:s");

			// GET DATA SAP

			$data = $this->ddeviasiscrap->get_data_scrap_sap(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_deviasi" => $no_deviasi
			));
		
			$prevCustomer = "";
			$counter = 1;
			foreach($data as $dt){
				
				if($prevCustomer == ""){
					// DATA PERTAMA
					$data_sap = array(
						// I_HEADER
						"no_deviasi"		=> $dt->no_deviasi,
						"VBELN"   			=> $dt->no_so, // no so
						"I_FLAG"   			=> 'X', // no so
					);
					
					//format posnr 6 digit
					$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
					
					$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
					
					$table_items[] = array(
						// "id_row_analisa" => $dt->id_row_analisa, // SAP SERVER
						"MANDT"   	=> '310', // SAP SERVER
						"FPKP"   	=> $dt->perihal, // Perihal Pengajuan
						"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
						"MATNR"   	=> $dt->kode_material, // Kode Material
						"MEINS"   	=> $dt->uom, // UOM
						"KWMENG"   	=> str_replace(",", "", $dt->qty_deviasi), // HARGA SATUAN
						"NETWR"   	=> $dt->harga_deviasi, // HARGA DISETUJUI TOTAL
						"WAERS"   	=> 'IDR' // CURRENCY
					);

					$prevCustomer = $dt->no_so;
				}else{
					if($prevCustomer !== $dt->no_so){
						//if kunnr beda
						// execute ke sap
						// clear data sap dan data items
						// input data sap dan data items
						$this->sap_scrapSO($data_sap, $table_items, $id_row_analisa);
						$data_sap = array();
						unset($table_items);
						unset($id_row_analisa);
						$counter = 1;

						$data_sap = array(
							// I_HEADER
							"no_deviasi"		=> $dt->no_deviasi,
							"VBELN"   			=> $dt->no_so, // no so
							"I_FLAG"   			=> 'X', // no so
						);
						
						//format posnr 6 digit
						$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
						
						$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
						$table_items[] = array(
							// "id_row_analisa" => $dt->id_row_analisa, // SAP SERVER
							"MANDT"   	=> '310', // SAP SERVER
							"FPKP"   	=> $dt->perihal, // Perihal Pengajuan
							"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
							"MATNR"   	=> $dt->kode_material, // Kode Material
							"MEINS"   	=> $dt->uom, // UOM
							"KWMENG"   	=> str_replace(",", "", $dt->qty_deviasi), // HARGA SATUAN
							"NETWR"   	=> $dt->harga_deviasi, // HARGA DISETUJUI TOTAL
							"WAERS"   	=> 'IDR' // CURRENCY
						);
	
						$prevCustomer = $dt->no_so;

					}else{
						
						// if kunnr sama
						// gaush input data sap dan input data items

						$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
						$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
						$table_items[] = array(
							// "id_row_analisa" => $dt->id_row_analisa, // SAP SERVER
							"MANDT"   	=> '310', // SAP SERVER
							"FPKP"   	=> $dt->perihal, // Perihal Pengajuan
							"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
							"MATNR"   	=> $dt->kode_material, // Kode Material
							"MEINS"   	=> $dt->uom, // UOM
							"KWMENG"   	=> str_replace(",", "", $dt->qty_deviasi), // HARGA SATUAN
							"NETWR"   	=> $dt->harga_deviasi, // HARGA DISETUJUI TOTAL
							"WAERS"   	=> 'IDR' // CURRENCY
						);
						$prevCustomer = $dt->no_so;
					}
				}

				$counter++;
				
			}
			
			// execute ke sap data terakhir atah kalau datanya cuma satu
			$this->sap_scrapSO($data_sap, $table_items, $id_row_analisa);

			// echo json_encode("berhasil");exit();

			return true;
			
		}

		public function test_run_changeSO($no_deviasi) {
			$datetime = date("Y-m-d H:i:s");

			// GET DATA SAP

			$data = $this->ddeviasiscrap->get_data_scrap_sap(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_deviasi" => $no_deviasi
			));
		
			$prevCustomer = "";
			$counter = 1;
			foreach($data as $dt){
				
				if($prevCustomer == ""){
					// DATA PERTAMA
					$data_sap = array(
						// I_HEADER
						"no_deviasi"		=> $dt->no_deviasi,
						"VBELN"   			=> $dt->no_so, // no so
						"I_FLAG"   			=> ' ', // no so
					);
					
					//format posnr 6 digit
					$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
					
					$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
					$table_items[] = array(
						"MANDT"   	=> '310', // SAP SERVER
						"FPKP"   	=> '', // Perihal Pengajuan
						"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
						"MATNR"   	=> '', // Kode Material
						"MEINS"   	=> $dt->uom, // UOM
						"KWMENG"   	=> str_replace(",", "", $dt->qty_deviasi), // HARGA SATUAN
						"NETWR"   	=> $dt->harga_deviasi, // HARGA DISETUJUI TOTAL
						"WAERS"   	=> 'IDR' // CURRENCY
					);

					$prevCustomer = $dt->no_so;
				}else{
					if($prevCustomer !== $dt->no_so){
						//if kunnr beda
						// execute ke sap
						// clear data sap dan data items
						// input data sap dan data items
						$this->sap_scrapSO($data_sap, $table_items, $id_row_analisa);
						$data_sap = array();
						unset($table_items);
						unset($id_row_analisa);
						$counter = 1;

						$data_sap = array(
							// I_HEADER
							"no_deviasi"		=> $dt->no_deviasi,
							"VBELN"   			=> $dt->no_so, // no so
							"I_FLAG"   			=> ' ', // no so
						);
						
						//format posnr 6 digit
						$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
						
						$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
						$table_items[] = array(
							"MANDT"   	=> '310', // SAP SERVER
							"FPKP"   	=> '', // Perihal Pengajuan
							"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
							"MATNR"   	=> '', // Kode Material
							"MEINS"   	=> $dt->uom, // UOM
							"KWMENG"   	=> str_replace(",", "", $dt->qty_deviasi), // HARGA SATUAN
							"NETWR"   	=> $dt->harga_deviasi, // HARGA DISETUJUI TOTAL
							"WAERS"   	=> 'IDR' // CURRENCY
						);
	
						$prevCustomer = $dt->no_so;

					}else{
						
						// if kunnr sama
						// gaush input data sap dan input data items

						$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
						$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
						$table_items[] = array(
							"MANDT"   	=> '310', // SAP SERVER
							"FPKP"   	=> '', // Perihal Pengajuan
							"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
							"MATNR"   	=> '', // Kode Material
							"MEINS"   	=> $dt->uom, // UOM
							"KWMENG"   	=> str_replace(",", "", $dt->qty_deviasi), // HARGA SATUAN
							"NETWR"   	=> $dt->harga_deviasi, // HARGA DISETUJUI TOTAL
							"WAERS"   	=> 'IDR' // CURRENCY
						);
						$prevCustomer = $dt->no_so;
					}
				}

				$counter++;
				
			}
			
			// execute ke sap data terakhir atah kalau datanya cuma satu
			$this->sap_scrapSO($data_sap, $table_items, $id_row_analisa);

			// echo json_encode("berhasil");exit();

			return true;
			
		}


		public function sap_scrapSO($header, $table_items, $id_row_analisa) {
			$this->connectSAP("ERP_310");
			// $this->connectSAP("ERP");
			
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$datetime = date("Y-m-d H:i:s");

			if ($this->data['sap']->getStatus() == SAPRFC_OK) {
				
				
				$param = array(
					array("IMPORT", "I_VBELN", $header['VBELN']),
					array("IMPORT", "I_FLAG", $header['I_FLAG']),
					array("TABLE", "T_RETURN", array()),
					array("TABLE", "T_ITEMS", $table_items),
					array("EXPORT", "E_INFNU", array()),
				);		

				// echo json_encode($param);exit();
				$result = $this->data['sap']->callFunction("Z_RFC_CHANGESOSCRAP", $param);
				// echo json_encode($result);exit();

				if ($this->data['sap']->getStatus() == SAPRFC_OK && !empty($result["T_RETURN"])) {
					$type    = array();
					$message = array();
					foreach ($result["T_RETURN"] as $return) {
						$type[]    = $return['TYPE'];
						$message[] = $return['MESSAGE'];
					}

					if (in_array('E', $type) === true) {
						// echo json_encode($param);exit();
						$data_row_log = array(
							'app'           => 'DATA RFC Change SO (K-IASS)',
							'rfc_name'      => 'Z_RFC_CHANGESOSCRAP',
							'log_code'      => implode(" , ", $type),
							'log_status'    => 'Gagal',
							'log_desc'      => "Change SO Failed [T_RETURN]: " . implode(" , ", $message),
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
					}
					else {
						$data_row_log = array(
							'app'           => 'DATA RFC Change SO (K-IASS)',
							'rfc_name'      => 'Z_RFC_CHANGESOSCRAP',
							'log_code'      => implode(" , ", $type),
							'log_status'    => 'Berhasil',
							'log_desc'      => implode(" , ", $message),
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
					}

					//update data NO SO
					$no_infnu = NULL;
					
					$no_infnu = $result["E_INFNU"];
					

					if ($no_infnu !== NULL && $no_infnu !== "") {

						foreach($id_row_analisa as $dt){
											
							if($header['I_FLAG'] == 'X'){
								$data_row = array(
									"no_infnu"               => $no_infnu
								);
							}
							
							$data_row = $this->dgeneral->basic_column("update", $data_row);
							$this->dgeneral->update("tbl_scrap_deviasi_detail", $data_row, array(
								array(
									'kolom' => 'no_deviasi',
									'value' => $header["no_deviasi"]
								),
								array(
									'kolom' => 'id_row_analisa',
									'value' => $dt['id_row_analisa']
								)
							));
						}
						
						
					}

					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

					//================================SAVE ALL================================//
					if ($this->dgeneral->status_transaction() === false) {
						$this->dgeneral->rollback_transaction();
						$this->general->closeDb();
						$msg = "Periksa kembali data yang dimasukkan";
						$sts = "NotOK";

						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					else {
						$this->dgeneral->commit_transaction();
						$this->general->closeDb();
						$msg = $data_row_log['log_desc'];
						$sts = "OK";
						if (in_array('E', $type) === true)
							$sts = "NotOK";

						if ($sts == "NotOK") {
							$return = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
						else {
							return array('sts' => $sts, 'msg' => $msg);
						}
					}
				}
				else {					
					$data_row_log = array(
						'app'           => 'DATA RFC Change SO (K-IASS)',
						'rfc_name'      => 'Z_RFC_CHANGESOSCRAP',
						'log_code'      => isset($result["T_RETURN"]["TYPE"]),
						'log_status'    => 'Gagal',
						'log_desc'      => "Change SO Failed: " . isset($result["T_RETURN"]["MESSAGE"]),
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);

					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				}
			}
			else {
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC Change SO (K-IASS)',
					'rfc_name'      => 'Z_RFC_CHANGESOSCRAP',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => "Connecting Failed: " . $status,
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}

			//================================SAVE ALL================================//
			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			else {
				$this->dgeneral->commit_transaction();
				$msg = $data_row_log['log_desc'];
				$sts = "NotOK";
			}
			$this->general->closeDb();

			if ($sts == "NotOK") {
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			else {
				return array('sts' => $sts, 'msg' => $msg);
			}
		}

		private function generate_message_email($no_deviasi, $action, $no_pp, $komentar = NULL) {
			$plant = explode("/", $no_deviasi)[2];
			switch ($action) {
				case 'submit'   :
					$status = "Submit";
					break;
				case 'approve'  :
					$status = "Approved";
					break;
				case 'edit'  :
					$status = "Edited & Approved";
					break;
				case 'assign'  :
					$status = "Assigned";
					break;
				case 'decline'  :
					$status = "Declined";
					break;
				case 'drop'     :
					$status = "Drop";
					break;
				case 'deleted'  :
					$status = "Deleted";
					break;
				case 'stop'  :
					$status = "Stop";
					break;
				case 'finish'   :
					$status = "Finish";
					break;
			}

			if (isset($komentar) && $komentar !== "") {
				$comment = $komentar;
			}
			else {
				$comment = "-";
			}

			//list data email
			$data_recipient = $this->ddeviasiscrap->get_email_recipient(
				array(
					"conn" => TRUE,
					"no_deviasi" => $no_deviasi,
					"plant" => $plant
				)
			);

			$email_cc = array();
			$email_to = array();
			$email_bcc = array();
			foreach ($data_recipient as $dt) {
				if ($dt->nilai == 'cc') {
					// $email_cc[] = ENVIRONMENT == 'development' ? "matthew.jodi@kiranamegatara.com" : $dt->email;
					$email_cc[] = "matthew.jodi@kiranamegatara.com";
				} else {
					// $email_to[] = ENVIRONMENT == 'development' ? "matthew.jodi@kiranamegatara.com" : $dt->email;
					$email_to[] = "frans.darmawan@kiranamegatara.com";
					if ($dt->nama !== "" && $dt->gender !== "") {
						$nama_to[] = $dt->gender . " " . ucwords(strtolower($dt->nama)) . "<br>";
					}
				}
				$email_bcc[] = "matthew.jodi@kiranamegatara.com";
			}

			if (empty($email_to)) {
				$email_to = $email_cc;
				$email_cc = array();
			}

			$oleh = ucwords(strtolower(base64_decode($this->session->userdata("-gelar-")) . " " . base64_decode($this->session->userdata("-nama-"))));

			$data_email = array(
				"no_deviasi"	  => $no_deviasi,
				"no_pp" 		  => $no_pp,
				"status"          => $status,
				"comment"         => $comment,
				"oleh"            => $oleh,
				"email_cc"        => $email_cc,
				"email_to"        => $email_to,
				"nama_to"         => empty($nama_to) ? "" : implode("", $nama_to)
			);
			$this->send_email($data_email);
		}

		private function send_email($param) {
			// setlocale(LC_ALL, 'id');
			setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');

			$config['protocol']    = 'smtp';
			$config['smtp_host']   = 'mail.kiranamegatara.com';
			$config['smtp_user']   = 'no-reply@kiranamegatara.com';
			$config['smtp_pass']   = '1234567890';
			$config['smtp_port']   = '465';
			$config['smtp_crypto'] = 'ssl';
			$config['charset']     = 'iso-8859-1';
			$config['wordwrap']    = true;
			$config['mailtype']    = 'html';

			$this->load->library('email', $config);

			$this->email->from('no-reply@kiranamegatara.com', 'K-IASS');
			$this->email->to($param['email_to']);
			$this->email->cc($param['email_cc']);


			$message = "<html>
							<body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
							<center style='width: 100%;'>
								<div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
									Notifikasi Email Aplikasi Pengajuan Penjualan
								</div>
								<div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
									&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
								</div>
								<div class='email-container' style='max-width: 800px; margin: 0 auto;'>
									<table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'
										style='min-width:600px;'>
										<tr>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td style='color: #fff; padding:20px;' align='center'>
												<div style='width: 50%; padding-bottom: 10px;''>
													<img src='" . base_url() . "/assets/apps/img/logo-lg.png'>
												</div>
												<h3 style='margin-bottom: 0;'>Kirana Inventory, Asset, and Scrap Sales (K-IASS)</h3>
												<hr style='border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;'/>
												<h3 style='margin-top: 0;'>Notifikasi Email</h3>
											</td>
										</tr>
										<tr>
											<td>
												<table style='background-color: #ffffff; margin: auto; -webkit-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); -moz-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4);'
													role='presentation' border='0' width='100%' cellspacing='0'
													cellpadding='0'
													align='center'>
													<tbody>
													<tr>
														<td style='padding: 20px;'>
															";
			if (!$param['nama_to']) {
				$param['nama_to'] = 'Bapak & Ibu';
			}
			$message .= "<p><strong>Kepada :<br><br> " . $param['nama_to'] . "</strong></p>";
			$message .= "<p>Email ini menandakan bahwa ada Pengajuan Penjualan baru yang membutuhkan perhatian anda.</p>";
			$message .= "<table style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
													<tr>
														<td>Nomor Pengajuan Deviasi</td>
														<td>:</td>";
			$message .= "<td>" . $param['no_deviasi'] . "</td>"; //NOMOR PP
			$message .= "</tr>
													<tr>
														<td>Nomor Pengajuan Penjualan</td>
														<td>:</td>";
			$message .= "<td>" . $param['no_pp'] . "</td>"; //PERIHAL
			$message .= "</tr>
													<tr>
														<td>Status</td>
														<td>:</td>";
			$message .= "<td>" . $param['status'] . "</td>"; // STATUS (disetujui, ditolak, selesai)
			$message .= "</tr>
													<tr>
														<td>Oleh</td>
														<td>:</td>";
			$message .= "<td>" . $param['oleh'] . "</td>"; //OLEH atau LAST ACTION PP
			$message .= "</tr>
													<tr>
														<td>Tanggal</td>
														<td>:</td>";
			$message .= "<td>" . strftime('%A, %d %B %Y') . "</td>"; //TANGGAL KIRIM EMAIL
			$message .= "</tr>
													<tr>
														<td>Catatan</td>
														<td>:</td>";
			if (!$param['comment']) {
				$param['comment'] = '-';
			}
			$message .= "<td>" . $param['comment'] . "</td>"; // COMMENT PP
			$message .= "</tr>
									</table>
									<p>Selanjutnya anda dapat melakukan review pada Deviasi Penjualan tersebut</p><p>melalui aplikasi K-IASS di Portal Kiranaku.</p>
								</td>
							</tr>
							<tr>
								<td align='left'
									style='background-color: #ffffff; padding: 15px; border-top: 1px dashed #386d22;'>
								</td>
							</tr>
							<tr>
								<td align='center' style='padding-bottom: 10px; font-size:15px;'><b>Click Tombol dibawah ini untuk login pada Portal Kiranaku</b></td>
							</tr>
							<tr>
								<td align='center' style='padding-bottom: 20px;'>";
			$message .= "<a href='" . base_url() . "' style='
											color: #fff;
											text-decoration: none; 
											background-color: #008d4c;
											border-color: #4cae4c; 
											display: inline-block; 
											margin-bottom: 0; 
											font-weight: 400; 
											text-align: center; 
											white-space: nowrap; 
											vertical-align: middle; 
											cursor: pointer;
											background-image: none;
											border: 1px solid transparent;
											padding: 6px 80px;
											font-size: 17px;
											letter-spacing: 2px;
											line-height: 1.42857143;
											border-radius: 4px;'>Login</a>"; // LINK PORTAL KIRANAKU
			$message .= " </td>
									</tr>
									<tr>
										<td align='left'
											style='background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;'>
											<p>
												Terima kasih atas perhatiannya.
											</p>
										</td>
									</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr>
							<td style='color: #fff; padding-top:20px;' align='center'>
								<small>Kiranaku Auto-Mail System</small><br/>";
			$message .= "<strong style='color: #214014; font-size: 10px;'>Terkirim pada " . date('d.m.Y H:i:s') . "</strong>"; // TANGGAL KIRIM EMAIL
			$message .= " </td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</div>
				</center>
				</body>
				</html>";

			$this->email->subject('Notifikasi Status Deviasi Penjualan');
			$this->email->message($message);

			$this->email->send();
		}
		

	}

?>
