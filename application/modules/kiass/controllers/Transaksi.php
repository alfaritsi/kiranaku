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

	class Transaksi extends BaseControllers {
		public function __construct() {
			parent::__construct();
			$this->data['module'] = "K-IASS";
			$this->load->model('dmasterscrap');
			$this->load->model('dmainscrap');
		}

		public function tambah() {
			//====must be initiate in every view function====/
			// $this->general->check_access();
			//===============================================/
			$this->data['title']      = "Form Pengajuan Penjualan";
			$this->data['title_form'] = "Pengajuan Penjualan";

			// echo json_encode($this->data['session_role']);exit();

			$this->data['no_pp'] = $this->generate_no_pp();
			
			$this->load->view("transaksi/add", $this->data);
		}

		public function edit($key = NULL) {
			//====must be initiate in every view function====/
			$this->general->check_session($_SERVER['REQUEST_URI']);
			//===============================================/
			$this->data['title']      = "Form Pengajuan Penjualan";
			$this->data['title_form'] = "Pengajuan Penjualan";

			if (!isset($key)) {
				show_404();
			}
			$plant = explode("-", $key)[2];
			$key   = str_replace("-", "/", $key);

			$this->data['no_pp'] = $key;

			$this->load->view("transaksi/edit", $this->data);
		}

		public function detail($key = NULL) {
			$this->general->check_session($_SERVER['REQUEST_URI']);
			$this->data['title']      = "Detail Pengajuan Penjualan";
			$this->data['title_form'] = "Pengajuan Penjualan";
			
			if (!isset($key)) {
				show_404();
			}
			$plant = explode("-", $key)[2];
			$key   = str_replace("-", "/", $key);

			if (in_array($plant, explode(",", $this->data['session_role'][0]->pabrik)) == false) {
				show_404();
			}
			
			$dt = $this->dmainscrap->get_scrap_header(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
				"single_row"   	=> TRUE,
				'no_pp' 	=> $key
			));

			$checker_kode = $this->dmainscrap->cek_invalid_kode(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_pp" => $key,
				"single_row" => true 
			));

			// echo json_encode($no_deviasi);exit();
			//owner div head
			switch($dt->pic_ho)
			{
				case 'Factory Operation':
					$div = 'FACTORY OPERATION DIVISION HEAD';
					$dept = 'PROCESS ENGINEERING DEPARTMENT HEAD';
					break;
				case 'Sourcing':
					$div = 'SOURCING DIVISION HEAD';
					$dept = 'SMALLHOLDER PARTNERSHIP DEPARTMENT HEAD';
					break;
				
				case 'ICT':
					$div = 'INFORM & COMM TECH DIVISION HEAD';
					$dept = 'IT DEVELOPMENT & SUPPORT DEPARTMENT HEAD';
					break;

				case 'HRGA':
					$div = 'HR & GA DEPUTY DIVISION HEAD';
					$dept = 'HR OPERATION & GA DEPARTMENT HEAD';
					break;
				
				case 'Finance Controller':
					$div = 'FINANCE CONTROLLER DIVISION HEAD';
					$dept = 'FINANCE CONTROLLER DEPARTMENT HEAD';
					break;
				
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Posisi tidak ditemukan');
					echo json_encode($return);
					break;
			}

			if($dt->status == $this->data['session_role'][0]->level){
				$this->data['approval'] = $data = $this->dmasterscrap->get_master_role_detail(array(
																	"connect" => NULL,
																		"app"   		=> 'kiass',
																		"single_row"   	=> TRUE,
																		"active"   		=> TRUE,
																		"id_flow" 		=> $dt->id_flow,
																		"level" 		=> $this->data['session_role'][0]->level
																	));				
			
				if($dt->status == '9' && base64_decode($this->session->userdata("-posst-")) !== $div){
					$this->data['approval'] = NULL;
				}

				if($dt->status == '16' && base64_decode($this->session->userdata("-posst-")) !== $dept){
					$this->data['approval'] = NULL;
				}

			}


			$kode_accounting = ($dt->status == 'finish' && $this->data['session_role'][0]->level == '6' && ($checker_kode->cek_kode > '0' || $checker_kode->cek_so > '0' )) ? true : false;
			
			$this->data['id_flow'] = $dt->id_flow;
			$this->data['no_pp'] = $key;
			$this->data['kode_accounting'] = $kode_accounting;
			$this->data['session_role'] = $this->data['session_role'];

			$this->load->view('transaksi/detail', $this->data);
		}

		public function lists() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "List Pengajuan Penjualan";
			$this->data['title_form'] = "List Pengajuan Penjualan";

			$this->data['tahun'] = $this->dmainscrap->get_scrap_header_tahun(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
			));

			$this->data['list'] = $this->dmainscrap->get_scrap_header(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
				'year' 		=> date("Y"),
				'plant'		=> explode(",", $this->data['session_role'][0]->pabrik)
			));

			$this->data['session_role'] = $this->data['session_role'];

			$this->data['pabrik']       = $this->get_master_plant(explode(",", $this->data['session_role'][0]->pabrik), false, NULL, "array");

			$this->load->view("transaksi/lists", $this->data);
		}

		public function approval() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/
			$this->data['title']      = "Approval Pengajuan Penjualan";
			$this->data['title_form'] = "Approval Pengajuan Penjualan";

			$this->data['tahun'] = $this->dmainscrap->get_scrap_header_tahun(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
			));

			$level = array($this->data['session_role'][0]->level);

			if ($this->data['session_role'][0]->level == "5" || $this->data['session_role'][0]->level == "6" ){
				array_push($level, '4');
			}

			$pic_ho = NULL;
			if($this->data['session_role'][0]->level == "9" || $this->data['session_role'][0]->level == "16" ){

				switch(base64_decode($this->session->userdata("-posst-")))
				{
					case 'FACTORY OPERATION DIVISION HEAD':
					case 'PROCESS ENGINEERING DEPARTMENT HEAD':
						$pic_ho = 'Factory Operation';
						break;
					case 'SOURCING DIVISION HEAD':
					case 'SMALLHOLDER PARTNERSHIP DEPARTMENT HEAD':
						$pic_ho = 'Sourcing';
						break;
					
					case 'INFORM & COMM TECH DIVISION HEAD':
					case 'IT DEVELOPMENT & SUPPORT DEPARTMENT HEAD':
						$pic_ho = 'ICT';
						break;

					case 'HR & GA DIVISION HEAD':
					case 'HR OPERATION & GA DEPARTMENT HEAD':
						$pic_ho = 'HRGA';
						break;
					
					case 'FINANCE CONTROLLER DIVISION HEAD':
					case 'FINANCE CONTROLLER DEPARTMENT HEAD':
						$pic_ho = 'Finance Controller';
						break;
					
					default:
						$pic_ho = null;
						break;
				}


			}

			$this->data['list'] = $this->dmainscrap->get_scrap_header(array(
				"connect" 	=> NULL,
				"app"   	=> 'kiass',
				'year' 		=> date("Y"),
				'approval' 	=> $level,
				'plant'		=> explode(",", $this->data['session_role'][0]->pabrik),
				'pic_ho'	=> $pic_ho
			));

			$this->data['session_role'] = $this->data['session_role'];

			$this->data['pabrik']       = $this->get_master_plant(explode(",", $this->data['session_role'][0]->pabrik), false, NULL, "array");

			$this->load->view("transaksi/approval", $this->data);
		}


		public function get($param = NULL) {
			switch ($param) {
				case 'lists':
					$this->get_data_header();
					break;
				case 'detail':
					$this->get_scrap_detail();
					break;
				
				case 'kunnr':
					$this->get_data_kunnr();
					break;

				case 'log-status':
					$this->get_log_status();
					break;
				case 'nbv':
					$this->get_nilai_nbv();
					break;
				
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		

		public function save($param = NULL) {
			switch ($param) {
				case 'pengajuan':
					$this->save_pengajuan();
					break;
				case 'approval':
					$this->save_approval();
					break;
				case 'procurement':
					$this->save_procurement();
					break;
				case 'delete':
					$this->save_delete();
					break;
				case 'kunnr':
					$this->save_kunnr();
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

		
		private function get_data_header() {
			$tahun = (isset($_POST['tahun']) ? $_POST['tahun'] : NULL);
			$plant = (isset($_POST['plant']) ? $_POST['plant'] : explode(",", $this->data['session_role'][0]->pabrik));
			$status = (isset($_POST['status']) && $_POST['status'] !== "" ? $_POST['status'] : NULL);
			$all    = array("onprogress", "finish", "drop", "deleted");
			$approval = (isset($_POST['approval']) && $_POST['approval'] == 'yes'  ? ($this->data['session_role'][0]->level == "5" || $this->data['session_role'][0]->level == "6" ? array($this->data['session_role'][0]->level, '4') : array($this->data['session_role'][0]->level)) : NULL);
			$pic_ho = NULL;
			
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


			if(isset($_POST['approval']) && $_POST['approval'] == 'yes' && ($this->data['session_role'][0]->level == "9" || $this->data['session_role'][0]->level == "16")){

				switch(base64_decode($this->session->userdata("-posst-")))
				{
					case 'FACTORY OPERATION DIVISION HEAD':
					case 'PROCESS ENGINEERING DEPARTMENT HEAD':
						$pic_ho = 'Factory Operation';
						break;
					case 'SOURCING DIVISION HEAD':
					case 'SMALLHOLDER PARTNERSHIP DEPARTMENT HEAD':
						$pic_ho = 'Sourcing';
						break;
					
					case 'INFORM & COMM TECH DIVISION HEAD':
					case 'IT DEVELOPMENT & SUPPORT DEPARTMENT HEAD':
						$pic_ho = 'ICT';
						break;

					case 'HR & GA DIVISION HEAD':
					case 'HR OPERATION & GA DEPARTMENT HEAD':
						$pic_ho = 'HRGA';
						break;
					
					case 'FINANCE CONTROLLER DIVISION HEAD':
					case 'FINANCE CONTROLLER DEPARTMENT HEAD':
						$pic_ho = 'Finance Controller';
						break;
					
					default:
						$pic_ho = null;
						break;
				}


			}

			$data = $this->dmainscrap->get_scrap_header(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"year" => $tahun,
				"plant" => $plant,
				"approval" => $approval,
				"status_in" => $filter,
				"in_not_in" => $query,
				'pic_ho'	=> $pic_ho
			));

			echo json_encode($data);
		}

		private function get_data_kunnr() {
			$kunnr = $this->dmainscrap->get_data_kunnr(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_pp" => $_POST['no_pp']
			));

			echo json_encode($kunnr);
		}

		private function get_nilai_nbv() {
			$param = $this->input->post();

			$param_nbv = array(
				"connect" => TRUE,
				"app"   => 'kiass',
				"single_row"   => TRUE,
				"kode_asset" => (int) $param['kode_asset'],
				"sno" => (int) $param['sno'],
			);

			if (isset($param['bukrs']) && !in_array($param['bukrs'], array('2132', '2122', '2111'))) {
				$param_nbv['bukrs'] = $param['bukrs'];
			} else {
				$param_nbv['gsber'] = $param['plant'];
			}
			$nbv = $this->dmainscrap->get_nilai_nbv($param_nbv);

			echo json_encode($nbv);

			// $datetime = date("Y-m-d H:i:s");

			// if ($param['kode_asset'] && $param['sno']) {
			// 	$msg    = "Gagal menemukan Kode Asset dan Sub Number.";
			// 	$sts    = "NotOK";
			// 	$return = array('sts' => $sts, 'msg' => $msg);
			// 	echo json_encode($return);
			// 	exit();
			// }
			// // LOGIC perhitungan Tanggal
			// // 1-15 ambil hari terakhir dari bulan sebelumnya
			// // 16-31 ambil hari terakhir di bulan ini
			// // $tgl_tarik = date('d');
			// // $tgl_sap = $tgl_tarik > 15 ? date("Y-m-t") : date('Y-m-d', strtotime('last day of previous month'));
			// $kode_asset = $param['kode_asset'];
			// $sno = $param['sno'];

			// // $sts    = "NotOK";
			// // $return = array('sts' => $sts, 'msg' => $kode_asset.$sno);
			// // echo json_encode($return);
			// // exit();

			// // $table_items[] = array(
			// // 	"BUKRS"   	=> '2122', // Company Code
			// // 	"ANLN1"   	=> $kode_asset, // Kode Asset
			// // 	"ANLN2"   	=> $sno, // Sub number
			// // 	"TXT50"   	=> '', // Asset Description
			// // 	"GSBER"   	=> '', // Pabrik
			// // 	"BUCHWERT"  => '', // Asset netbook value
			// // 	"AKTIV"   	=> '', // CAP date
			// // 	"WAERS"   	=> '' // CURRENCY
			// // );

			// // $data_nbv = $this->sap_scrapNbv(date("Ymd", strtotime($tgl_sap)), $table_items);

			// $sts    = "OK";
			// $return = array('sts' => $sts, 'datas' => $data_nbv);
			// echo json_encode($return);
		}

		private function get_log_status() {
			$log_status = $this->dmainscrap->get_log_status(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_pp" => $_POST['no_pp']
			));

			echo json_encode($log_status);
		}

		private function get_scrap_detail() {
			
			$detail['header'] = $this->dmainscrap->get_scrap_header(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_pp" => $_POST['no_pp']
			));

			// $detail['analisa_harga'] = $this->dmainscrap->get_analisa_harga(array(
			// 	"connect" => NULL,
			// 	"app"   => 'scrap',
			// 	"no_pp" => $_POST['no_pp'],
			// 	"plant" => explode("/", $_POST['no_pp'])[1]
			// ));

			$analisa = $this->dmainscrap->get_analisa_harga(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_pp" => $_POST['no_pp'],
				"plant" => explode("/", $_POST['no_pp'])[2]
			));

			$detail['analisa_harga'] = $analisa;
			// foreach ($analisa as $dt) {
			// 	$kode_asset = $this->dmainscrap->get_kode_asset(array(
			// 		"connect" => NULL,
			// 		"app"   => 'kiass',
			// 		"no_pp" => $dt->no_pp,
			// 		"id_row_analisa" => $dt->id_row_analisa
			// 	));
			// 	$dt->kode_asset = $kode_asset;
			// 	array_push($detail['analisa_harga'], $dt);


			// }

			$detail['calon_pembeli'] = $this->dmainscrap->get_calon_pembeli(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_pp" => $_POST['no_pp']
			));

			$detail['session_role'] = $this->data['session_role'][0];

			echo json_encode($detail);
		}

		private function generate_no_pp() {
			$kode = "PP";
			$separator = "/";
			$plant     = $this->data['session_role'][0]->pabrik;
			$month     = date('m');
			$year      = date('Y');
			$cek = $this->dmainscrap->get_no_pp(array(
																"connect" => NULL,
																	"app"   => 'kiass',
																	"year" => $year,
																	"plant" => $plant,
																	"month" => $month
																));
																
			$no = (count($cek) + 1);
			return  $kode . $separator . $no . $separator . $plant . $separator . $month . $separator . $year;
		}

		private function generate_status($param = NULL)
		{
			$action = $param['action'];
			$no_pp 	= $param['no_pp'];
			$id_flow = $param['id_flow'];


			if($action == 'submit' || $action == 'edit'){
				$level = $this->data['session_role'][0]->level;
			}else{
				$dataheader = $this->dmainscrap->get_scrap_header(array(
					"connect" => NULL,
					"app"   => 'kiass',
					"no_pp" => $no_pp,
					"single_row" => true 
				));

				$pembeli = $dataheader->pembeli;
				$level = $dataheader->status;

			}



			$data = $this->dmasterscrap->get_master_role_detail(array(
				"connect" => NULL,
					"app"   		=> 'kiass',
					"single_row"   	=> TRUE,
					"active"	   	=> TRUE,
					"id_flow" 		=> $id_flow,
					"level" 		=> $level

				));
			
			
			
			switch ($action) {
				case 'submit'   :
				case 'edit'     :
					$status = $data->if_approve;
					break;

				case 'approve'  :

					// LOGIC LIMIT
					// IF $data->app_lim_val !== '0' &&  $dataheader->nilai_pengajuan <= $data->app_lim_val THEN FINISH ELSE if_approve

					if($data->app_lim_val > 0 &&  $dataheader->nilai_pengajuan <= $data->app_lim_val){
						$status = 'finish';
					}else{
						$status = $data->if_approve;
					}

					if($pembeli !== 'pihakKetiga' && $status == '7'){
						$datas = $this->dmasterscrap->get_master_role_detail(array(
							"connect" => NULL,
								"app"   		=> 'kiass',
								"single_row"   	=> TRUE,
								"active"	   	=> TRUE,
								"id_flow" 		=> $id_flow,
								"level" 		=> $status
			
							));
						$datas2 = $this->dmasterscrap->get_master_role_detail(array(
							"connect" => NULL,
								"app"   		=> 'kiass',
								"single_row"   	=> TRUE,
								"active"	   	=> TRUE,
								"id_flow" 		=> $id_flow,
								"level" 		=> $datas->if_approve
			
							));

						$status = $datas2->if_approve;
					}

					break;

				case 'assign'  :
					$status = $data->if_assign;
					break;

				case 'decline'  :
					$status = $data->if_decline;

					if($pembeli !== 'pihakKetiga' && $status == '7'){
						$datas = $this->dmasterscrap->get_master_role_detail(array(
							"connect" => NULL,
								"app"   		=> 'kiass',
								"single_row"   	=> TRUE,
								"active"	   	=> TRUE,
								"id_flow" 		=> $id_flow,
								"level" 		=> $status
			
							));
						$datas2 = $this->dmasterscrap->get_master_role_detail(array(
							"connect" => NULL,
								"app"   		=> 'kiass',
								"single_row"   	=> TRUE,
								"active"	   	=> TRUE,
								"id_flow" 		=> $id_flow,
								"level" 		=> $datas->if_decline
			
							));

						$status = $datas2->if_decline;
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

		private function save_approval() {
			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();

			$dataheader = $this->dmainscrap->get_scrap_header(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_pp" => $param['no_pp'],
				"single_row" => true 
			));

			$checker_kode = $this->dmainscrap->cek_invalid_kode(array(
				"connect" => NULL,
				"app"   => 'kiass', 
				"no_pp" => $param['no_pp'],
				"single_row" => true 
			));

			$data_scrap_header     = array(
				"status"				=> $this->generate_status(
																	array(
																		"action" 	=>  $param['action'],
																		"no_pp" 	=>  $param['no_pp'],
																		"id_flow" 	=>  $param['id_flow']
																	)
																),
				"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit" 			=> $datetime
			);
			
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

			// echo json_encode($data_scrap_header['status']);exit();

	
			if($data_scrap_header['status'] == 'finish' && $checker_kode->cek_kode < '1'){
				$this->createSO($param['no_pp']);	
			}


			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			$this->dgeneral->update('tbl_scrap_header', $data_scrap_header, array(
				array(
					'kolom' => 'no_pp',
					'value' => $param['no_pp']
				)
			));

			// SPESIAL KONDISI SIMPAN NILAI NBV KETIKA FINISH
			if ($data_scrap_header['status'] == 'finish'){
				for ($i=0; $i < $param['counter_tabel'] ; $i++) {

					$countRow = $param['counter_row_tabel'.($i+1)];
				
					for ($j=1; $j < ($countRow+1) ; $j++) {
						$data_analisa_harga     = array(
							// NILAI NBV baru di simpan ketika FINISH.
							"nilai_nbv"			=> $this->general->emptyconvert(str_replace(",", "", $param['nbv_tabel'.($i+1).'_row'.$j])),
							"login_edit"   		=> base64_decode($this->session->userdata("-id_user-")),
							"tanggal_edit" 		=> $datetime
							
						);
	
						$id_analisa_harga = $param['id_row_analisa_tabel'.($i+1).'_row'.$j];
						$this->dgeneral->update("tbl_scrap_analisa_harga", $data_analisa_harga, array(
							array(
								'kolom' => 'no_pp',
								'value' => $param['no_pp']
							),
							array(
								'kolom' => 'id_row_analisa',
								'value' => $id_analisa_harga
							)
						));
					}
				}
			}

			// SPESIAL KONDISI NILAI NBV ACCOUNTING
			if ($param['action'] == 'approve' && $dataheader->status == '6'){
				for ($i=0; $i < $param['counter_tabel'] ; $i++) {

					$countRow = $param['counter_row_tabel'.($i+1)];
				
					for ($j=1; $j < ($countRow+1) ; $j++) {
						$data_analisa_harga     = array(
							"kode_asset"		=> $param['kode_asset_tabel'.($i+1).'_row'.$j],
							"deskripsi_asset"	=> $param['deskripsi_asset_tabel'.($i+1).'_row'.$j],
							"sno"				=> isset($param['sno_tabel'.($i+1).'_row'.$j]) ? $param['sno_tabel'.($i+1).'_row'.$j] : '0',
							"cap_date"			=> date('Y-m-d', strtotime($param['cap_date_tabel'.($i+1).'_row'.$j])),
							// NILAI NBV baru di simpan ketika FINISH.
							// "nilai_nbv"			=> $this->general->emptyconvert(str_replace(",", "", $param['nbv_tabel'.($i+1).'_row'.$j])),
							"login_edit"   		=> base64_decode($this->session->userdata("-id_user-")),
							"tanggal_edit" 		=> $datetime
							
						);
	
						$id_analisa_harga = $param['id_row_analisa_tabel'.($i+1).'_row'.$j];
						$this->dgeneral->update("tbl_scrap_analisa_harga", $data_analisa_harga, array(
							array(
								'kolom' => 'no_pp',
								'value' => $param['no_pp']
							),
							array(
								'kolom' => 'id_row_analisa',
								'value' => $id_analisa_harga
							)
						));
					}
				}
			}

			// SPECIAL CASE FINCON DEPT HEAD
			// JIKA PEMBELI != PIHAK KETIGA PUNYA OTORITAS ISI HARGA SATUAN DAN HARGA NEGO LALU JALANIN TEST RUN
			if($dataheader->pembeli !== 'pihakKetiga' && $param['action'] == 'approve' && $dataheader->status == '5'){

				for ($i=0; $i < $param['counter_tabel'] ; $i++) {

					$countRow = $param['counter_row_tabel'.($i+1)];
					$countCalon = $param['counter_calon_tabel'.($i+1)];
					
					for ($j=1; $j < ($countRow+1) ; $j++) {				
						
						$data_analisa_harga     = array(
							"harga_varian"			=> $this->general->emptyconvert(str_replace(",", "", $param['harga_varian_tabel'.($i+1).'_row'.$j])),
							"total_varian"			=> $this->general->emptyconvert(str_replace(",", "", $param['total_varian_tabel'.($i+1).'_row'.$j])),
							"harga_nego"			=> $this->general->emptyconvert(str_replace(",", "", $param['harga_nego_tabel'.($i+1).'_row'.$j])),
							"total_harga_nego"		=> $this->general->emptyconvert(str_replace(",", "", $param['total_harga_nego_tabel'.($i+1).'_row'.$j])),
							"pemenang"				=> $this->general->emptyconvert($param['pembeli_tabel'.($i+1).'_row'.$j]),
							"counter_pemenang"		=> $countCalon,
							"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
							"tanggal_edit" 			=> $datetime
							
						);
	
						$id_analisa_harga = $param['id_row_analisa_tabel'.($i+1).'_row'.$j];
						$data_row_analisa_harga     = $this->dgeneral->basic_column("update", $data_analisa_harga);
						$this->dgeneral->update("tbl_scrap_analisa_harga", $data_row_analisa_harga, array(
							array(
								'kolom' => 'no_pp',
								'value' => $param['no_pp']
							),
							array(
								'kolom' => 'id_row_analisa',
								'value' => $id_analisa_harga
							)
						));
	
						
						for ($d=1; $d < ($countCalon+1) ; $d++) {
								
							$data_calon_pembeli     = array(
								"harga_satuan"			=> str_replace(",", "", $param['harga_satuan_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
								"harga_total"			=> str_replace(",", "", $param['harga_total_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
								"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
								"tanggal_edit" 			=> $datetime
							);
							
							$this->dgeneral->update("tbl_scrap_calon_pembeli", $data_calon_pembeli, array(
								array(
									'kolom' => 'no_pp',
									'value' => $param['no_pp']
								),
								array(
									'kolom' => 'id_calon_pembeli',
									'value' => $param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d]
								)
							));	
							
						}						
					
					}
				
				}

			}

			// SPECIAL CASE PROCUREMENT HO (STAFF)
			// PUNYA OTORISASI ISI HARGA NEGO DAN TAMBAH ALTERNATIF LALU JALANIN TEST RUN
			if($param['action'] == 'approve' && $dataheader->status == '7'){
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|jpeg|png|pdf';

				$id_lampiran_proc = "";
				if ($_FILES['lampiran_procurement']['name'][0] !== "") {
					if (count($_FILES['lampiran_procurement']['name']) > 1) {
						$msg    = "You can only upload maximum 1 file";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$newname  = array(str_replace("/", "-", $_POST['no_pp']) . "-lampiran-procurement");
					$file_lampiran = $this->general->upload_files($_FILES['lampiran_procurement'], $newname, $config);
					$data_lampiran = array(
						"no_pp"    				=> $param['no_pp'],
						"filename"    			=> $newname[0],
						"size"    				=> $file_lampiran[0]['size'],
						"ext"    				=> pathinfo($file_lampiran[0]['full_path'], PATHINFO_EXTENSION),
						"location"    			=> $file_lampiran[0]['url'],
						"desc"    				=> "Lampiran Pengajuan Penjualan"
					);

					$data_row_lampiran     = $this->dgeneral->basic_column("insert", $data_lampiran);
					$this->dgeneral->insert("tbl_scrap_file", $data_row_lampiran);
					$id_lampiran_proc = $this->db->insert_id();
				}

				$data_scrap_header_proc     = array(
					"catatan_proc"			=> $param['catatan_proc'],
					"lampiran_proc"			=> $id_lampiran_proc,
					"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" 			=> $datetime
				);
				$this->dgeneral->update('tbl_scrap_header', $data_scrap_header_proc, array(
					array(
						'kolom' => 'no_pp',
						'value' => $param['no_pp']
					)
				));

				for ($i=0; $i < $param['counter_tabel'] ; $i++) {

					$countRow = $param['counter_row_tabel'.($i+1)];
					$countCalon = $param['counter_calon_tabel'.($i+1)];
				
					for ($j=1; $j < ($countRow+1) ; $j++) {
						
						$data_analisa_harga     = array(
							"harga_varian"			=> $this->general->emptyconvert(str_replace(",", "", $param['harga_varian_tabel'.($i+1).'_row'.$j])),
							"total_varian"			=> $this->general->emptyconvert(str_replace(",", "", $param['total_varian_tabel'.($i+1).'_row'.$j])),
							"harga_nego"			=> $this->general->emptyconvert(str_replace(",", "", $param['harga_nego_tabel'.($i+1).'_row'.$j])),
							"total_harga_nego"		=> $this->general->emptyconvert(str_replace(",", "", $param['total_harga_nego_tabel'.($i+1).'_row'.$j])),
							"pemenang"				=> $this->general->emptyconvert($param['pembeli_tabel'.($i+1).'_row'.$j]),
							"counter_pemenang"		=> $countCalon,
							"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
							"tanggal_edit" 			=> $datetime
							
						);

						$id_analisa_harga = $param['id_row_analisa_tabel'.($i+1).'_row'.$j];
						$this->dgeneral->update("tbl_scrap_analisa_harga", $data_analisa_harga, array(
							array(
								'kolom' => 'no_pp',
								'value' => $param['no_pp']
							),
							array(
								'kolom' => 'id_row_analisa',
								'value' => $id_analisa_harga
							)
						));

						for ($d=1; $d < ($countCalon+1) ; $d++) {
							
							if ($j == 1 ){ // hanya input data pertama biar ga dobel dobel

								if($param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d] == ""){
									$id_lampiran_calon = NULL;

									if ($_FILES['lampiran_tabel'.($i+1).'_calon'.($d)]['name'][0] !== "") {
										
										$newname_calon  = array(str_replace("/", "-", $_POST['no_pp']) . "-lampiran-tbl". ($i+1) . "-calon". ($d));
										$file_lampiran_calon = $this->general->upload_files($_FILES['lampiran_tabel'.($i+1).'_calon'.($d)], $newname_calon, $config);
										$data_lampiran_calon = array(
											"no_pp"    				=> $param['no_pp'],
											"filename"    			=> $newname_calon[0],
											"size"    				=> $file_lampiran_calon[0]['size'],
											"ext"    				=> pathinfo($file_lampiran_calon[0]['full_path'], PATHINFO_EXTENSION),
											"location"    			=> $file_lampiran_calon[0]['url'],
											"desc"    				=> "Lampiran calon pemenang"
										);
										$data_row_calon     = $this->dgeneral->basic_column("insert", $data_lampiran_calon);
										$this->dgeneral->insert("tbl_scrap_file", $data_row_calon);
										$id_lampiran_calon = $this->db->insert_id();
									}

									$data_calon_pembeli     = array(
										"id_row_analisa"		=> $id_analisa_harga,
										"no_tabel"				=> ($i+1),
										"no_row"				=> ($j),
										"no_pp"    				=> $param['no_pp'],

										"identitas"				=> $param['identitas_tabel'.($i+1).'_calon'.$d],
										"no_hp"					=> $param['hp_tabel'.($i+1).'_calon'.$d],
										"kode_customer"			=> $param['customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
										"nama_pembeli"			=> $param['nama_customer_tabel'.($i+1).'_calon'.$d],
										"harga_satuan"			=> str_replace(",", "", $param['harga_satuan_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
										"harga_total"			=> str_replace(",", "", $param['harga_total_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
										"is_pemenang"			=> null,
										"metode_pembayaran"		=> $param['metode_tabel'.($i+1).'_calon'.$d],
										"durasi"				=> $param['tod_tabel'.($i+1).'_calon'.$d],
										"id_lampiran_calon"		=> $id_lampiran_calon,
										"no_urut"				=> ($d), //no urut calon
										
										"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
										"tanggal_edit" 			=> $datetime
									);


									if ($id_lampiran_calon == NULL){
										unset($data_calon_pembeli['id_lampiran_calon']);
									}

									$data_row_calon_pembeli     = $this->dgeneral->basic_column("insert", $data_calon_pembeli);
									$this->dgeneral->insert("tbl_scrap_calon_pembeli", $data_row_calon_pembeli);
								}
							
							}else {
								if($param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d] == ""){
									$data_calon_pembeli     = array(
										"id_row_analisa"		=> $id_analisa_harga,
										"no_tabel"				=> ($i+1),
										"no_pp"    				=> $param['no_pp'],
										"no_row"				=> ($j),
										"identitas"				=> $param['identitas_tabel'.($i+1).'_calon'.$d],
										"no_hp"					=> $param['hp_tabel'.($i+1).'_calon'.$d],
										"kode_customer"			=> $param['customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
										"nama_pembeli"			=> $param['nama_customer_tabel'.($i+1).'_calon'.$d],
										"harga_satuan"			=> str_replace(",", "",$param['harga_satuan_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
										"harga_total"			=> str_replace(",", "",$param['harga_total_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
										"is_pemenang"			=> null,
										"metode_pembayaran"		=> $param['metode_tabel'.($i+1).'_calon'.$d],
										"durasi"				=> $param['tod_tabel'.($i+1).'_calon'.$d],
										"id_lampiran_calon"		=> NULL,
										"no_urut"				=> ($d), //no urut calon
										
										"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
										"tanggal_edit" 			=> $datetime
									);

									$data_row_calon_pembeli     = $this->dgeneral->basic_column("insert", $data_calon_pembeli);
									$this->dgeneral->insert("tbl_scrap_calon_pembeli", $data_row_calon_pembeli);
								}

							}
							
						}
					}
				}
			}
			// END SPECIAL CASE PROCUREMENT
			
			$data_log = array(
				"no_pp" 		  => $param['no_pp'],
				"tgl_status"      => $datetime,
				"action"          => $param['action'],
				"status"          => $this->data['session_role'][0]->level,
				"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit"    => $datetime,
				"comment"         => isset($param['komentar']) ? $param['komentar'] : NULL
			);
			$this->dgeneral->insert("tbl_scrap_log_status", $data_log);

			// SPECIAL CASE FINCON DEPT HEAD
			// PUNYA OTORITAS ISI HARGA SATUAN DAN HARGA NEGO JIKA PEMBELI != PIHAK KETIGA DAN JALANIN TEST RUN
			if(($dataheader->pembeli !== 'pihakKetiga' && $param['action'] == 'approve' && $dataheader->status == '5') || ($param['action'] == 'approve' && $dataheader->status == '7')){
				//comment dulu lokal error rfc
				$test_run = $this->test_run_createSO($param['no_pp']);
			}

			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$this->general->closeDb();

				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			else {
				$this->dgeneral->commit_transaction();
				$this->general->closeDb();

				$this->generate_message_email($param['no_pp'], $param['action'], $dataheader->perihal, $param['komentar'], $data_scrap_header['status'], $dataheader->pic_ho);
				$msg = "Data berhasil ditambahkan";
				$sts = "OK";

				if($data_scrap_header['status'] == 'finish' && $checker_kode->cek_kode > '0'){
					$this->generate_message_email_kunnr($param['no_pp']);	
				}
			}
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);

		}

		private function save_pengajuan() {
			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();
			$flag_ganti_nomor_pp = 0;

			// GET id_flow dan jenis barang by param['lokasi'] dan param['jenis_barang'] alias barang ========================================
			$flow = $this->dmasterscrap->get_master_flow(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"single_row" => TRUE,
				"alias_flow" => $param['radioJenis'],
				"lokasi" => $param['lokasi']
			));

			if (count($flow) == 0) {
                    $msg    = "Jenis Barang tidak ditemukan, Mohon hubungi PIC aplikasi K-IASS Head Office.";
                    $sts    = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                    exit();
			}
			
			// echo json_encode($data);exit();

			if (isset($param['action']) && trim($param['action']) == "edit"){
				

				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				// INSERT
				// SCRAP HEADER

				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|jpeg|png|pdf';

				//================================SAVE SCORING HEADER================================//
				
				$id_lampiran_header = "";
				if ($_FILES['lampiran']['name'][0] !== "") {
					if (count($_FILES['lampiran']['name']) > 1) {
						$msg    = "You can only upload maximum 1 file";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$newname  = array(str_replace("/", "-", $_POST['no_pp']) . "-lampiran");
					$file_lampiran = $this->general->upload_files($_FILES['lampiran'], $newname, $config);
					$data_lampiran = array(
						"no_pp"    				=> $param['no_pp'],
						"filename"    			=> $newname[0],
						"size"    				=> $file_lampiran[0]['size'],
						"ext"    				=> pathinfo($file_lampiran[0]['full_path'], PATHINFO_EXTENSION),
						"location"    			=> $file_lampiran[0]['url'],
						"desc"    				=> "Lampiran Pengajuan Penjualan"
					);
	
					// echo json_encode($file_lampiran);exit();
	
	
					$data_row_lampiran     = $this->dgeneral->basic_column("insert", $data_lampiran);
					$this->dgeneral->insert("tbl_scrap_file", $data_row_lampiran);
					$id_lampiran_header = $this->db->insert_id();
				}


				$data_scrap_header     = array(
					"no_pp"    				=> $param['no_pp'],
					"lokasi"    			=> $param['lokasi'],
					"pembeli"  				=> $param['pembeli'],
					"perihal"  				=> $param['perihal'],
					"jenis_barang"			=> $flow->keterangan,
					"id_flow"				=> $flow->id_flow,
					"latar_belakang"		=> $param['latar_belakang'],
					"pic_ho"				=> $param['pic_ho'],
					"pic_proj"				=> $param['pic_pabrik'],
					"status"				=> $this->generate_status(
																		array(
																			"action" 	=>  'edit',
																			"no_pp" 	=>  $param['no_pp'],
																			"id_flow" 	=>  $flow->id_flow
																		)
																	),
					"ket_satu_pembeli"		=> $param['ket_satu_pembeli'],
					"is_spk"				=> $param['radiospk'],
					"keterangan_spk"		=> $param['keterangan_spk'],
					"catatan_proc"			=> $param['catatan_proc'],
					"counter_analisa_harga"	=> $param['counter_tabel'],
					"id_lampiran"			=> $id_lampiran_header
				);

				if ($id_lampiran_header == "")
					unset($data_scrap_header['id_lampiran']);

				$data_row_header     = $this->dgeneral->basic_column("update", $data_scrap_header);
				$this->dgeneral->update("tbl_scrap_header", $data_row_header, array(
					array(
						'kolom' => 'no_pp',
						'value' => $param['no_pp']
					)
				));

				//======set all analisa harga detail not active======//                
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_scrap_analisa_harga", $data, array(
					array(
						'kolom' => 'no_pp',
						'value' => $param['no_pp']
					),
					array(
						'kolom' => 'na',
						'value' => 'n'
					),
					array(
						'kolom' => 'del',
						'value' => 'n'
					)
				));

				//======set all calon pembeli detail not active======//                
				$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
				$this->dgeneral->update("tbl_scrap_calon_pembeli", $data, array(
					array(
						'kolom' => 'no_pp',
						'value' => $param['no_pp']
					),
					array(
						'kolom' => 'na',
						'value' => 'n'
					),
					array(
						'kolom' => 'del',
						'value' => 'n'
					)
				));

				for ($i=0; $i < $param['counter_tabel'] ; $i++) {

					$countRow = $param['counter_row_tabel'.($i+1)];
					$countCalon = $param['counter_calon_tabel'.($i+1)];
				
					for ($j=1; $j < ($countRow+1) ; $j++) {

						$id_foto_kondisi = NULL;

						if ($_FILES['foto_tabel'.($i+1).'_row'.($j)]['name'][0] !== "") {
							
							$newname_analisa  = array(str_replace("/", "-", $_POST['no_pp']) . "-foto-kondisi-tbl". ($i+1) . "-row". ($j));
							$file_lampiran_analisa = $this->general->upload_files($_FILES['foto_tabel'.($i+1).'_row'.($j)], $newname_analisa, $config);
							$data_lampiran_analisa = array(
								"no_pp"    				=> $param['no_pp'],
								"filename"    			=> $newname_analisa[0],
								"size"    				=> $file_lampiran_analisa[0]['size'],
								"ext"    				=> pathinfo($file_lampiran_analisa[0]['full_path'], PATHINFO_EXTENSION),
								"location"    			=> $file_lampiran_analisa[0]['url'],
								"desc"    				=> "Lampiran Foto Kondisi Barang"
							);
							$data_row_analisa     = $this->dgeneral->basic_column("insert", $data_lampiran_analisa);
							$this->dgeneral->insert("tbl_scrap_file", $data_row_analisa);
							$id_foto_kondisi = $this->db->insert_id();
						}
		
						
						
						$data_analisa_harga     = array(
							"no_tabel"				=> ($i+1),
							"no_pp"    				=> $param['no_pp'],
							"kode_material"			=> $param['kode_material_tabel'.($i+1).'_row'.$j],
							"kode_asset"			=> $param['kode_asset_tabel'.($i+1).'_row'.$j],
							"sno"					=> $param['sno_tabel'.($i+1).'_row'.$j],
							// "nbv"    				=> $param['nbv_tabel'.($i+1).'_row'.$j],
							// "tahun_beli"  			=> $datetime,
							"deskripsi"				=> $param['deskripsi_tabel'.($i+1).'_row'.$j],
							"rincian"				=> $param['rincian_tabel'.($i+1).'_row'.$j],
							"uom"					=> $param['satuan_tabel'.($i+1).'_row'.$j],
							"qty"					=> $param['qty_tabel'.($i+1).'_row'.$j],
							"harga_terakhir"		=> str_replace(",", "",$param['harga_terakhir_tabel'.($i+1).'_row'.$j]),
							"harga_varian"			=> $this->general->emptyconvert($param['harga_varian_tabel'.($i+1).'_row'.$j]),
							"total_varian"			=> $this->general->emptyconvert($param['total_varian_tabel'.($i+1).'_row'.$j]),
							"harga_nego"			=> $this->general->emptyconvert($param['harga_nego_tabel'.($i+1).'_row'.$j]),
							"total_harga_nego"		=> $this->general->emptyconvert($param['total_harga_nego_tabel'.($i+1).'_row'.$j]),
							"pemenang"				=> $this->general->emptyconvert($param['pembeli_tabel'.($i+1).'_row'.$j]),
							"counter_pemenang"		=> $countCalon,
							"id_foto_kondisi"		=> $id_foto_kondisi,
							"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
							"tanggal_edit" 			=> $datetime,
							"na" 					=> 'n',
							"del" 					=> 'n',
						);

						if ($id_foto_kondisi == NULL)
							unset($data_analisa_harga['id_foto_kondisi']);

						if(isset($param['id_row_analisa_tabel'.($i+1).'_row'.$j]) && $param['id_row_analisa_tabel'.($i+1).'_row'.$j] !== ""){
							unset($data_analisa_harga['login_edit']);
							unset($data_analisa_harga['tanggal_edit']);
							$id_analisa_harga = $param['id_row_analisa_tabel'.($i+1).'_row'.$j];
							$data_row_analisa_harga     = $this->dgeneral->basic_column("update", $data_analisa_harga);
							$data_row_analisa_harga = $this->dgeneral->basic_column('update', $data_row_analisa_harga, $datetime);
							$this->dgeneral->update("tbl_scrap_analisa_harga", $data_row_analisa_harga, array(
								array(
									'kolom' => 'no_pp',
									'value' => $param['no_pp']
								),
								array(
									'kolom' => 'id_row_analisa',
									'value' => $id_analisa_harga
								)
							));
						}else{
							$data_row_analisa_harga     = $this->dgeneral->basic_column("insert_full", $data_analisa_harga);
							$this->dgeneral->insert("tbl_scrap_analisa_harga", $data_row_analisa_harga);
							$id_analisa_harga = $this->db->insert_id();
						}

						
						

						for ($d=1; $d < ($countCalon+1) ; $d++) {
							
							if ($j == 1 ){ // hanya input data pertama biar ga dobel dobel

								$id_lampiran_calon = NULL;
	
								if ($_FILES['lampiran_tabel'.($i+1).'_calon'.($d)]['name'][0] !== "") {
									
									$newname_calon  = array(str_replace("/", "-", $_POST['no_pp']) . "-lampiran-tbl". ($i+1) . "-calon". ($d));
									$file_lampiran_calon = $this->general->upload_files($_FILES['lampiran_tabel'.($i+1).'_calon'.($d)], $newname_calon, $config);
									$data_lampiran_calon = array(
										"no_pp"    				=> $param['no_pp'],
										"filename"    			=> $newname_calon[0],
										"size"    				=> $file_lampiran_calon[0]['size'],
										"ext"    				=> pathinfo($file_lampiran_calon[0]['full_path'], PATHINFO_EXTENSION),
										"location"    			=> $file_lampiran_calon[0]['url'],
										"desc"    				=> "Lampiran calon pemenang"
									);
									$data_row_calon     = $this->dgeneral->basic_column("insert", $data_lampiran_calon);
									$this->dgeneral->insert("tbl_scrap_file", $data_row_calon);
									$id_lampiran_calon = $this->db->insert_id();
								}

								$data_calon_pembeli     = array(
									"id_row_analisa"		=> $id_analisa_harga,
									"no_tabel"				=> ($i+1),
									"no_row"				=> ($j),
									"no_pp"    				=> $param['no_pp'],
	
									"identitas"				=> $param['identitas_tabel'.($i+1).'_calon'.$d],
									"no_hp"					=> $param['hp_tabel'.($i+1).'_calon'.$d],
									"kode_customer"			=> $param['customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
									"nama_pembeli"			=> $param['nama_customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
									"harga_satuan"			=> str_replace(",", "", $param['harga_satuan_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
									"harga_total"			=> str_replace(",", "", $param['harga_total_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
									"is_pemenang"			=> null,
									"metode_pembayaran"		=> $param['metode_tabel'.($i+1).'_calon'.$d],
									"durasi"				=> $param['tod_tabel'.($i+1).'_calon'.$d],
									"id_lampiran_calon"		=> $id_lampiran_calon,
									"no_urut"				=> ($d), //no urut calon
									
									"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
									"tanggal_edit" 			=> $datetime,
									"na" 					=> 'n',
									"del" 					=> 'n',
								);
	

								if ($id_lampiran_calon == NULL)
									unset($data_calon_pembeli['id_lampiran_calon']);

								if(isset($param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d]) && $param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d] !== ""){
									unset($data_calon_pembeli['login_edit']);
									unset($data_calon_pembeli['tanggal_edit']);
									$data_row_calon_pembeli     = $this->dgeneral->basic_column("update", $data_calon_pembeli);
									$this->dgeneral->update("tbl_scrap_calon_pembeli", $data_row_calon_pembeli, array(
										array(
											'kolom' => 'no_pp',
											'value' => $param['no_pp']
										),
										array(
											'kolom' => 'id_calon_pembeli',
											'value' => $param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d]
										)
									));	
								}else{
									$data_row_calon_pembeli     = $this->dgeneral->basic_column("insert_full", $data_calon_pembeli);
									$this->dgeneral->insert("tbl_scrap_calon_pembeli", $data_row_calon_pembeli);
								}

							
							}else {
								$data_calon_pembeli     = array(
									"id_row_analisa"		=> $id_analisa_harga,
									"no_tabel"				=> ($i+1),
									"no_pp"    				=> $param['no_pp'],
									"no_row"				=> ($j),
									"identitas"				=> $param['identitas_tabel'.($i+1).'_calon'.$d],
									"no_hp"					=> $param['hp_tabel'.($i+1).'_calon'.$d],
									"kode_customer"			=> $param['customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
									"nama_pembeli"			=> $param['nama_customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
									"harga_satuan"			=> str_replace(",", "",$param['harga_satuan_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
									"harga_total"			=> str_replace(",", "",$param['harga_total_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
									"is_pemenang"			=> null,
									"metode_pembayaran"		=> $param['metode_tabel'.($i+1).'_calon'.$d],
									"durasi"				=> $param['tod_tabel'.($i+1).'_calon'.$d],
									"id_lampiran_calon"		=> NULL,
									"no_urut"				=> ($d), //no urut calon
									
									"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
									"tanggal_edit" 			=> $datetime
								);

								if(isset($param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d]) && $param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d] !== ""){
									unset($data_calon_pembeli['login_edit']);
									unset($data_calon_pembeli['tanggal_edit']);
									$data_row_calon_pembeli     = $this->dgeneral->basic_column("update", $data_calon_pembeli);
									$this->dgeneral->update("tbl_scrap_calon_pembeli", $data_row_calon_pembeli, array(
										array(
											'kolom' => 'no_pp',
											'value' => $param['no_pp']
										),
										array(
											'kolom' => 'id_calon_pembeli',
											'value' => $param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d]
										)
									));	
								}else{
									$data_row_calon_pembeli     = $this->dgeneral->basic_column("insert", $data_calon_pembeli);
									$this->dgeneral->insert("tbl_scrap_calon_pembeli", $data_row_calon_pembeli);
								}

							}
							

						}
					
					
					
					}
				
				}

			}else{

				
				// CHECK DUPLICATE NOMOR PP ========================================
				
				$cek_duplikat   = $this->generate_no_pp();

				if ($cek_duplikat !== $param['no_pp']) {
					$param['no_pp']      = $cek_duplikat;
					$flag_ganti_nomor_pp = 1;
				}

				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				// INSERT
				// SCRAP HEADER

				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|jpeg|png|pdf';

				//================================SAVE SCORING HEADER================================//
				if ($_FILES['lampiran']['name'][0] !== "") {
					if (count($_FILES['lampiran']['name']) > 1) {
						$msg    = "You can only upload maximum 1 file";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$newname  = array(str_replace("/", "-", $_POST['no_pp']) . "-lampiran");
					$file_lampiran = $this->general->upload_files($_FILES['lampiran'], $newname, $config);
				}

				// echo json_encode($file_lampiran);exit();


				$data_lampiran = array(
					"no_pp"    				=> $param['no_pp'],
					"filename"    			=> $newname[0],
					"size"    				=> $file_lampiran[0]['size'],
					"ext"    				=> pathinfo($file_lampiran[0]['full_path'], PATHINFO_EXTENSION),
					"location"    			=> $file_lampiran[0]['url'],
					"desc"    				=> "Lampiran Pengajuan Penjualan"
				);

				// echo json_encode($data_lampiran);exit();


				$data_row_lampiran     = $this->dgeneral->basic_column("insert", $data_lampiran);
				$this->dgeneral->insert("tbl_scrap_file", $data_row_lampiran);
				$id_lampiran_header = $this->db->insert_id();


				$data_scrap_header     = array(
					"plant"    				=> explode("/", $param['no_pp'])[2],
					"no_pp"    				=> $param['no_pp'],
					"lokasi"    			=> $param['lokasi'],
					"pembeli"  				=> $param['pembeli'],
					"perihal"  				=> $param['perihal'],
					"jenis_barang"			=> $flow->keterangan,
					"id_flow"				=> $flow->id_flow,
					"latar_belakang"		=> $param['latar_belakang'],
					"pic_ho"				=> $param['pic_ho'],
					"pic_proj"				=> $param['pic_pabrik'],
					"tanggal_pengajuan"		=> date('Y-m-d', strtotime($param['tgl_pengajuan'])),
					"status"				=> $this->generate_status(
																		array(
																			"action" 	=>  'submit',
																			"no_pp" 	=>  $param['no_pp'],
																			"id_flow" 	=>  $flow->id_flow
																		)
																	),
					"ket_satu_pembeli"		=> $param['ket_satu_pembeli'],
					"is_spk"				=> $param['radiospk'],
					"keterangan_spk"		=> $param['keterangan_spk'],
					"catatan_proc"			=> $param['catatan_proc'],
					"counter_analisa_harga"	=> $param['counter_tabel'],
					"id_lampiran"			=> $id_lampiran_header,
					"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit" 			=> $datetime
				);
				$data_row_header     = $this->dgeneral->basic_column("insert", $data_scrap_header);
				$this->dgeneral->insert("tbl_scrap_header", $data_row_header);	
				
				// SCRAP Analisa Harga
				for ($i=0; $i < $param['counter_tabel'] ; $i++) {

					$countRow = $param['counter_row_tabel'.($i+1)];
					$countCalon = $param['counter_calon_tabel'.($i+1)];
				
					for ($j=1; $j < ($countRow+1) ; $j++) {

						$id_foto_kondisi = NULL;

						if ($_FILES['foto_tabel'.($i+1).'_row'.($j)]['name'][0] !== "") {
							
							$newname_analisa  = array(str_replace("/", "-", $_POST['no_pp']) . "-foto-kondisi-tbl". ($i+1) . "-row". ($j));
							$file_lampiran_analisa = $this->general->upload_files($_FILES['foto_tabel'.($i+1).'_row'.($j)], $newname_analisa, $config);
							$data_lampiran_analisa = array(
								"no_pp"    				=> $param['no_pp'],
								"filename"    			=> $newname_analisa[0],
								"size"    				=> $file_lampiran_analisa[0]['size'],
								"ext"    				=> pathinfo($file_lampiran_analisa[0]['full_path'], PATHINFO_EXTENSION),
								"location"    			=> $file_lampiran_analisa[0]['url'],
								"desc"    				=> "Lampiran Foto Kondisi Barang"
							);
							$data_row_analisa     = $this->dgeneral->basic_column("insert", $data_lampiran_analisa);
							$this->dgeneral->insert("tbl_scrap_file", $data_row_analisa);
							$id_foto_kondisi = $this->db->insert_id();
						}
		
						
						
						$data_analisa_harga     = array(
							"no_tabel"				=> ($i+1),
							"no_pp"    				=> $param['no_pp'],
							"kode_material"			=> $param['kode_material_tabel'.($i+1).'_row'.$j],
							// "kode_asset"			=> $param['kode_asset_tabel'.$i.'_row'.$j],
							// "nbv"    				=> $param['nbv_tabel'.($i+1).'_row'.$j],
							// "tahun_beli"  			=> $datetime,
							"deskripsi"				=> $param['deskripsi_tabel'.($i+1).'_row'.$j],
							"rincian"				=> $param['rincian_tabel'.($i+1).'_row'.$j],
							"uom"					=> $param['satuan_tabel'.($i+1).'_row'.$j],
							"qty"					=> $param['qty_tabel'.($i+1).'_row'.$j],
							"harga_terakhir"		=> str_replace(",", "",$param['harga_terakhir_tabel'.($i+1).'_row'.$j]),
							"harga_varian"			=> $this->general->emptyconvert($param['harga_varian_tabel'.($i+1).'_row'.$j]),
							"total_varian"			=> $this->general->emptyconvert($param['total_varian_tabel'.($i+1).'_row'.$j]),
							"harga_nego"			=> $this->general->emptyconvert($param['harga_nego_tabel'.($i+1).'_row'.$j]),
							"total_harga_nego"		=> $this->general->emptyconvert($param['total_harga_nego_tabel'.($i+1).'_row'.$j]),
							"pemenang"				=> $this->general->emptyconvert($param['pembeli_tabel'.($i+1).'_row'.$j]),
							"counter_pemenang"		=> $countCalon,
							"id_foto_kondisi"		=> $id_foto_kondisi,
							"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
							"tanggal_edit" 			=> $datetime
						);
						$data_row_analisa_harga     = $this->dgeneral->basic_column("insert", $data_analisa_harga);
						$this->dgeneral->insert("tbl_scrap_analisa_harga", $data_row_analisa_harga);
						$id_analisa_harga = $this->db->insert_id();

						for ($d=1; $d < ($countCalon+1) ; $d++) {
							
							if ($j == 1 ){ // hanya input data pertama biar ga dobel dobel

								$id_lampiran_calon = NULL;
	
								if ($_FILES['lampiran_tabel'.($i+1).'_calon'.($d)]['name'][0] !== "") {
									
									$newname_calon  = array(str_replace("/", "-", $_POST['no_pp']) . "-lampiran-tbl". ($i+1) . "-calon". ($d));
									$file_lampiran_calon = $this->general->upload_files($_FILES['lampiran_tabel'.($i+1).'_calon'.($d)], $newname_calon, $config);
									$data_lampiran_calon = array(
										"no_pp"    				=> $param['no_pp'],
										"filename"    			=> $newname_calon[0],
										"size"    				=> $file_lampiran_calon[0]['size'],
										"ext"    				=> pathinfo($file_lampiran_calon[0]['full_path'], PATHINFO_EXTENSION),
										"location"    			=> $file_lampiran_calon[0]['url'],
										"desc"    				=> "Lampiran calon pemenang"
									);
									$data_row_calon     = $this->dgeneral->basic_column("insert", $data_lampiran_calon);
									$this->dgeneral->insert("tbl_scrap_file", $data_row_calon);
									$id_lampiran_calon = $this->db->insert_id();
								}

								$data_calon_pembeli     = array(
									"id_row_analisa"		=> $id_analisa_harga,
									"no_tabel"				=> ($i+1),
									"no_row"				=> ($j),
									"no_pp"    				=> $param['no_pp'],
	
									"identitas"				=> $param['identitas_tabel'.($i+1).'_calon'.$d],
									"no_hp"					=> $param['hp_tabel'.($i+1).'_calon'.$d],
									"kode_customer"			=> $param['customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
									"nama_pembeli"			=> $param['nama_customer_tabel'.($i+1).'_calon'.$d],
									"harga_satuan"			=> str_replace(",", "",$param['harga_satuan_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
									"harga_total"			=> str_replace(",", "",$param['harga_total_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
									"is_pemenang"			=> null,
									"metode_pembayaran"		=> $param['metode_tabel'.($i+1).'_calon'.$d],
									"durasi"				=> $param['tod_tabel'.($i+1).'_calon'.$d],
									"id_lampiran_calon"		=> $id_lampiran_calon,
									"no_urut"				=> ($d), //no urut calon
	
	
									
									"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
									"tanggal_edit" 			=> $datetime
								);
	
								$data_row_calon_pembeli     = $this->dgeneral->basic_column("insert", $data_calon_pembeli);
								$this->dgeneral->insert("tbl_scrap_calon_pembeli", $data_row_calon_pembeli);

							
							}else {
								$data_calon_pembeli     = array(
									"id_row_analisa"		=> $id_analisa_harga,
									"no_tabel"				=> ($i+1),
									"no_pp"    				=> $param['no_pp'],
									"no_row"				=> ($j),
									"identitas"				=> $param['identitas_tabel'.($i+1).'_calon'.$d],
									"no_hp"					=> $param['hp_tabel'.($i+1).'_calon'.$d],
									"kode_customer"			=> $param['customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
									"nama_pembeli"			=> $param['nama_customer_tabel'.($i+1).'_calon'.$d], 
									"harga_satuan"			=> str_replace(",", "",$param['harga_satuan_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
									"harga_total"			=> str_replace(",", "",$param['harga_total_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
									"is_pemenang"			=> null,
									"metode_pembayaran"		=> $param['metode_tabel'.($i+1).'_calon'.$d],
									"durasi"				=> $param['tod_tabel'.($i+1).'_calon'.$d],
									"id_lampiran_calon"		=> NULL,
									"no_urut"				=> ($d), //no urut calon
									
									"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
									"tanggal_edit" 			=> $datetime
								);
	
								$data_row_calon_pembeli     = $this->dgeneral->basic_column("insert", $data_calon_pembeli);
								$this->dgeneral->insert("tbl_scrap_calon_pembeli", $data_row_calon_pembeli);

							}
							

						}
					}
				}

				
			}

			$data_log = array(
				"no_pp" 		  => $param['no_pp'],
				"tgl_status"      => $datetime,
				"action"          => $param['action'],
				"status"          => $this->data['session_role'][0]->level,
				"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit"    => $datetime,
				"comment"         => isset($param['komentar']) ? $param['komentar'] : NULL
			);
			$this->dgeneral->insert("tbl_scrap_log_status", $data_log);


			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			else {
				$this->dgeneral->commit_transaction();
				$this->generate_message_email($param['no_pp'], $param['action'], $param['perihal']);
				$msg = "Data berhasil ditambahkan";
				$sts = "OK";
				if ($flag_ganti_nomor_pp == 1) {
					$msg = "Data berhasil ditambahkan, namun nomor pp diubah menjadi " . $param['no_pp'] . ".";
				}
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}

		public function save_delete() {
			$datetime = date("Y-m-d H:i:s");
			$no_pp    = $_POST['no_pp'];
			$alasan   = $_POST['alasan'];
			$this->dgeneral->begin_transaction();

			//set status di tbl_scrap_header menjadi deleted
			$datas = array(
				"login_edit"   => base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit" => $datetime,
				"status"       => "deleted",
			);
			
			$this->dgeneral->update("tbl_scrap_header", $datas, array(
				array(
					'kolom' => 'no_pp',
					'value' => $no_pp
				)
			));


			$data_log = array(
				"no_pp"        => $no_pp,
				"tgl_status"   => $datetime,
				"status"       => $this->data['session_role'][0]->level,
				"action"       => 'delete',
				"login_edit"   => base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit" => $datetime,
				"comment"      => $alasan
			);
			$this->dgeneral->insert('tbl_scrap_log_status', $data_log);

		

			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Gagal Melakukan Delete Pengajuan Penjualan";
				$sts = "NotOK";
			}
			else {
				$this->dgeneral->commit_transaction();
				$msg = "Berhasil Delete Pengajuan Penjualan";
				$sts = "OK";
			}

			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}

		private function save_procurement() {
			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();

			// $test_run = $this->test_run_createSO($param['no_pp']);
			// LOGIC
			// INSERT dulu tanpa update status -> jalananin test run -> if success update status else tampilin error

			// echo json_encode($test_run);exit();
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			$data_scrap_header     = array(
				"catatan_proc"			=> $param['catatan_proc'],
				"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
				"tanggal_edit" 			=> $datetime
			);
			$this->dgeneral->update('tbl_scrap_header', $data_scrap_header, array(
				array(
					'kolom' => 'no_pp',
					'value' => $param['no_pp']
				)
			));

			$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
			$config['allowed_types'] = 'jpg|jpeg|png|pdf';

			for ($i=0; $i < $param['counter_tabel'] ; $i++) {

				$countRow = $param['counter_row_tabel'.($i+1)];
				$countCalon = $param['counter_calon_tabel'.($i+1)];
			
				for ($j=1; $j < ($countRow+1) ; $j++) {
					
					$data_analisa_harga     = array(
						"harga_varian"			=> $this->general->emptyconvert(str_replace(",", "", $param['harga_varian_tabel'.($i+1).'_row'.$j])),
						"total_varian"			=> $this->general->emptyconvert(str_replace(",", "", $param['total_varian_tabel'.($i+1).'_row'.$j])),
						"harga_nego"			=> $this->general->emptyconvert(str_replace(",", "", $param['harga_nego_tabel'.($i+1).'_row'.$j])),
						"total_harga_nego"		=> $this->general->emptyconvert(str_replace(",", "", $param['total_harga_nego_tabel'.($i+1).'_row'.$j])),
						"pemenang"				=> $this->general->emptyconvert($param['pembeli_tabel'.($i+1).'_row'.$j]),
						"counter_pemenang"		=> $countCalon,
						"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit" 			=> $datetime
						
					);

					$id_analisa_harga = $param['id_row_analisa_tabel'.($i+1).'_row'.$j];
					$this->dgeneral->update("tbl_scrap_analisa_harga", $data_analisa_harga, array(
						array(
							'kolom' => 'no_pp',
							'value' => $param['no_pp']
						),
						array(
							'kolom' => 'id_row_analisa',
							'value' => $id_analisa_harga
						)
					));

					for ($d=1; $d < ($countCalon+1) ; $d++) {
						
						if ($j == 1 ){ // hanya input data pertama biar ga dobel dobel

							$id_lampiran_calon = NULL;

							if ($_FILES['lampiran_tabel'.($i+1).'_calon'.($d)]['name'][0] !== "") {
								
								$newname_calon  = array(str_replace("/", "-", $_POST['no_pp']) . "-lampiran-tbl". ($i+1) . "-calon". ($d));
								$file_lampiran_calon = $this->general->upload_files($_FILES['lampiran_tabel'.($i+1).'_calon'.($d)], $newname_calon, $config);
								$data_lampiran_calon = array(
									"no_pp"    				=> $param['no_pp'],
									"filename"    			=> $newname_calon[0],
									"size"    				=> $file_lampiran_calon[0]['size'],
									"ext"    				=> pathinfo($file_lampiran_calon[0]['full_path'], PATHINFO_EXTENSION),
									"location"    			=> $file_lampiran_calon[0]['url'],
									"desc"    				=> "Lampiran calon pemenang"
								);
								$data_row_calon     = $this->dgeneral->basic_column("insert", $data_lampiran_calon);
								$this->dgeneral->insert("tbl_scrap_file", $data_row_calon);
								$id_lampiran_calon = $this->db->insert_id();
							}

							$data_calon_pembeli     = array(
								"id_row_analisa"		=> $id_analisa_harga,
								"no_tabel"				=> ($i+1),
								"no_row"				=> ($j),
								"no_pp"    				=> $param['no_pp'],

								"identitas"				=> $param['identitas_tabel'.($i+1).'_calon'.$d],
								"no_hp"					=> $param['hp_tabel'.($i+1).'_calon'.$d],
								"kode_customer"			=> $param['customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
								"nama_pembeli"			=> $param['nama_customer_tabel'.($i+1).'_calon'.$d],
								"harga_satuan"			=> str_replace(",", "", $param['harga_satuan_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
								"harga_total"			=> str_replace(",", "", $param['harga_total_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
								"is_pemenang"			=> null,
								"metode_pembayaran"		=> $param['metode_tabel'.($i+1).'_calon'.$d],
								"durasi"				=> $param['tod_tabel'.($i+1).'_calon'.$d],
								"id_lampiran_calon"		=> $id_lampiran_calon,
								"no_urut"				=> ($d), //no urut calon
								
								"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
								"tanggal_edit" 			=> $datetime
							);


							if ($id_lampiran_calon == NULL)
								unset($data_calon_pembeli['id_lampiran_calon']);

							if($param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d] == ""){
								$data_row_calon_pembeli     = $this->dgeneral->basic_column("insert", $data_calon_pembeli);
								$this->dgeneral->insert("tbl_scrap_calon_pembeli", $data_row_calon_pembeli);
							}else{
								$this->dgeneral->update("tbl_scrap_calon_pembeli", $data_calon_pembeli, array(
									array(
										'kolom' => 'no_pp',
										'value' => $param['no_pp']
									),
									array(
										'kolom' => 'id_calon_pembeli',
										'value' => $param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d]
									)
								));
							}

						
						}else {
							$data_calon_pembeli     = array(
								"id_row_analisa"		=> $id_analisa_harga,
								"no_tabel"				=> ($i+1),
								"no_pp"    				=> $param['no_pp'],
								"no_row"				=> ($j),
								"identitas"				=> $param['identitas_tabel'.($i+1).'_calon'.$d],
								"no_hp"					=> $param['hp_tabel'.($i+1).'_calon'.$d],
								"kode_customer"			=> $param['customer_tabel'.($i+1).'_calon'.$d], //KUNNNR
								"nama_pembeli"			=> $param['nama_customer_tabel'.($i+1).'_calon'.$d],
								"harga_satuan"			=> str_replace(",", "",$param['harga_satuan_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
								"harga_total"			=> str_replace(",", "",$param['harga_total_tabel'.($i+1).'_row'.$j.'_calon'.$d]),
								"is_pemenang"			=> null,
								"metode_pembayaran"		=> $param['metode_tabel'.($i+1).'_calon'.$d],
								"durasi"				=> $param['tod_tabel'.($i+1).'_calon'.$d],
								"id_lampiran_calon"		=> NULL,
								"no_urut"				=> ($d), //no urut calon
								
								"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
								"tanggal_edit" 			=> $datetime
							);

							if($param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d] == ""){
								$data_row_calon_pembeli     = $this->dgeneral->basic_column("insert", $data_calon_pembeli);
								$this->dgeneral->insert("tbl_scrap_calon_pembeli", $data_row_calon_pembeli);
							}else{
								$this->dgeneral->update("tbl_scrap_calon_pembeli", $data_calon_pembeli, array(
									array(
										'kolom' => 'no_pp',
										'value' => $param['no_pp']
									),
									array(
										'kolom' => 'id_calon_pembeli',
										'value' => $param['id_calon_pembeli_tabel'.($i+1).'_row'.$j.'_calon'.$d]
									)
								));
							}

						}
						
					}
				}
			
			}
			
			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$this->general->closeDb();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			else {
				$this->dgeneral->commit_transaction();
				$this->general->closeDb();
				$msg = "Data berhasil ditambahkan";
				$sts = "OK";

				// JALANIN TEST RUN
				$test_run = $this->test_run_createSO($param['no_pp']);

				if($test_run == true){
					$this->general->connectDbPortal();
					$this->dgeneral->begin_transaction();
					$data_scrap_header     = array(
						"status"				=> $this->generate_status(
																			array(
																				"action" 	=>  'approve',
																				"no_pp" 	=>  $param['no_pp'],
																				"id_flow" 	=>  $param['id_flow']
																			)
																		),
						"login_edit"   			=> base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit" 			=> $datetime
					);
					$this->dgeneral->update('tbl_scrap_header', $data_scrap_header, array(
						array(
							'kolom' => 'no_pp',
							'value' => $param['no_pp']
						)
					));

					$data_log = array(
						"no_pp" 		  => $param['no_pp'],
						"tgl_status"      => $datetime,
						"action"          => 'approve',
						"status"          => $this->data['session_role'][0]->level,
						"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit"    => $datetime,
						"comment"         => isset($param['komentar_proc']) ? $param['komentar_proc'] : NULL
					);
					$this->dgeneral->insert("tbl_scrap_log_status", $data_log);

					if ($this->dgeneral->status_transaction() === false) {
						$this->dgeneral->rollback_transaction();
						$this->general->closeDb();
						$msg = "Periksa kembali data yang dimasukkan";
						$sts = "NotOK";
					}else{
						$this->dgeneral->commit_transaction();
						$this->general->closeDb();
						$msg = "Data berhasil ditambahkan";
						$sts = "OK";
						$this->generate_message_email($param['no_pp'], 'approve', $param['perihal'], isset($param['komentar_proc']) ? $param['komentar_proc'] : NULL);
					}



				}
			}
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}

		private function save_kunnr() {
			$datetime = date("Y-m-d H:i:s");
			$param = $this->input->post();

			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			for ($i=0; $i < $param['counter_kode'] ; $i++) {

				$data_calon_pembeli     = array(
					"kode_customer"			=> $param['kunnr'.($i+1)] //KUNNNR
				);

				
				$this->dgeneral->update("tbl_scrap_calon_pembeli", $data_calon_pembeli, array(
					array(
						'kolom' => 'no_pp',
						'value' => $param['no_pp_kode']
					),
					array(
						'kolom' => 'no_tabel',
						'value' => $param['kode_tabel'.($i+1)]
					),
					array(
						'kolom' => 'nama_pembeli',
						'value' => $param['kode_nama'.($i+1)]
					)
				));
					
			}
			
			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$this->general->closeDb();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			else {
				$this->dgeneral->commit_transaction();
				$this->general->closeDb();
				$msg = "Data berhasil ditambahkan";
				$sts = "OK";

				// JALANIN TEST RUN
				$run_so = $this->createSO($param['no_pp_kode']);

				if($run_so == true){
					$data_log = array(
						"no_pp" 		  => $param['no_pp_kode'],
						"tgl_status"      => $datetime,
						"action"          => 'approve',
						"status"          => 'finish',
						"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit"    => $datetime,
						"comment"         => 'Submit Kode Customer dan Generate SO'
					);
					$this->dgeneral->insert("tbl_scrap_log_status", $data_log);
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";

				}
			}
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}

		public function createSO($no_pp) {
			$datetime = date("Y-m-d H:i:s");

			// GET DATA SAP

			$data = $this->dmainscrap->get_data_scrap_sap(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_pp" => $no_pp
			));
		
			$prevCustomer = "";
			$counter = 1;
			foreach($data as $dt){
				
				if($prevCustomer == ""){
					// DATA PERTAMA
					$data_sap = array(
						// I_HEADER
						"no_pp"   			=> $dt->no_pp,
						"KUNNR"   			=> $dt->kode_customer, // kode customer
						"VKORG"   			=> explode("/", $dt->no_pp)[2], // Plant
						"VDATU"   			=> date("Ymd", strtotime($datetime)), // Req Delivery Date
						"BSTDK"   			=> date("Ymd", strtotime($datetime)), // tanggal SO
						"BSTKD"   			=> $dt->no_pp, // NAMA PO
						"TELF1"   			=> $dt->no_hp, // no telp
						"VBELN"   			=> null, // no so
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
						"KWMENG"   	=> str_replace(",", "", $dt->qty), // HARGA SATUAN
						"NETWR"   	=> $dt->harga_nego, // HARGA DISETUJUI TOTAL
						"WAERS"   	=> 'IDR' // CURRENCY
					);

					$prevCustomer = $dt->kode_customer;
				}else{
					if($prevCustomer !== $dt->kode_customer){
						//if kunnr beda
						// execute ke sap
						// clear data sap dan data items
						// input data sap dan data items
						$this->sap_scrapSO(array(
							"header" => $data_sap, 
							"table_items" => $table_items,
							"id_row_analisa" => $id_row_analisa,
							"connect" => FALSE
						));
						$data_sap = array();
						unset($table_items);
						unset($id_row_analisa);
						$counter = 1;

						$data_sap = array(
							// I_HEADER
							"no_pp"   			=> $dt->no_pp,
							"KUNNR"   			=> $dt->kode_customer, // kode customer
							"VKORG"   			=> explode("/", $dt->no_pp)[2], // Plant
							"VDATU"   			=> date("Ymd", strtotime($datetime)), // Req Delivery Date
							"BSTDK"   			=> date("Ymd", strtotime($datetime)), // tanggal SO
							"BSTKD"   			=> $dt->no_pp, // NAMA PO
							"TELF1"   			=> $dt->no_hp, // no telp
							"VBELN"   			=> null, // no so
							"I_FLAG"   			=> 'X', // no so
						);
						
						//format posnr 6 digit
						$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
						
						$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
						$table_items[] = array(
							// "id_row_analisa" => $dt->id_row_analisa,
							"MANDT"   	=> '310', // SAP SERVER
							"FPKP"   	=> $dt->perihal, // Perihal Pengajuan
							"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
							"MATNR"   	=> $dt->kode_material, // Kode Material
							"MEINS"   	=> $dt->uom, // UOM
							"KWMENG"   	=> str_replace(",", "", $dt->qty), // HARGA SATUAN
							"NETWR"   	=> $dt->harga_nego, // HARGA DISETUJUI TOTAL
							"WAERS"   	=> 'IDR' // CURRENCY
						);
	
						$prevCustomer = $dt->kode_customer;

					}else{
						
						// if kunnr sama
						// gaush input data sap dan input data items

						$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
						$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
						$table_items[] = array(
							// "id_row_analisa" => $dt->id_row_analisa,
							"MANDT"   	=> '310', // SAP SERVER
							"FPKP"   	=> $dt->perihal, // Perihal Pengajuan
							"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
							"MATNR"   	=> $dt->kode_material, // Kode Material
							"MEINS"   	=> $dt->uom, // UOM
							"KWMENG"   	=> str_replace(",", "", $dt->qty), // HARGA SATUAN
							"NETWR"   	=> $dt->harga_nego, // HARGA DISETUJUI TOTAL
							"WAERS"   	=> 'IDR' // CURRENCY
						);
						$prevCustomer = $dt->kode_customer;
					}
				}

				$counter++;
				
			}
			
			// execute ke sap data terakhir atah kalau datanya cuma satu
			$this->sap_scrapSO(array(
				"header" => $data_sap, 
				"table_items" => $table_items,
				"id_row_analisa" => $id_row_analisa,
				"connect" => FALSE
			));

			return true;
			
		}

		public function test_run_createSO($no_pp) {
			$datetime = date("Y-m-d H:i:s");

			// GET DATA SAP

			$data = $this->dmainscrap->get_data_scrap_sap(array(
				"connect" => NULL,
				"app"   => 'kiass',
				"no_pp" => $no_pp
			));
		
			$prevCustomer = "";
			$counter = 1;
			foreach($data as $dt){
				
				if($prevCustomer == ""){
					// DATA PERTAMA
					$data_sap = array(
						// I_HEADER
						"no_pp"   			=> $dt->no_pp,
						"KUNNR"   			=> $dt->kode_customer, // kode customer
						"VKORG"   			=> explode("/", $dt->no_pp)[2], // Plant
						"VDATU"   			=> date("Ymd", strtotime($datetime)), // Req Delivery Date
						"BSTDK"   			=> date("Ymd", strtotime($datetime)), // tanggal SO
						"BSTKD"   			=> $dt->no_pp, // NAMA PO
						"TELF1"   			=> $dt->no_hp, // no telp
						"VBELN"   			=> null, // no so
						"I_FLAG"   			=> ' ', // no so
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
						"KWMENG"   	=> str_replace(",", "", $dt->qty), // HARGA SATUAN
						"NETWR"   	=> $dt->harga_nego, // HARGA DISETUJUI TOTAL
						"WAERS"   	=> 'IDR' // CURRENCY
					);

					$prevCustomer = $dt->kode_customer;
				}else{
					if($prevCustomer !== $dt->kode_customer){
						//if kunnr beda
						// execute ke sap
						// clear data sap dan data items
						// input data sap dan data items
						$this->sap_scrapSO(array(
							"header" => $data_sap, 
							"table_items" => $table_items,
							"id_row_analisa" => $id_row_analisa,
							"connect" => FALSE
						));
						$data_sap = array();
						unset($table_items);
						unset($id_row_analisa);
						$counter = 1;

						$data_sap = array(
							// I_HEADER
							"no_pp"   			=> $dt->no_pp,
							"KUNNR"   			=> $dt->kode_customer, // kode customer
							"VKORG"   			=> explode("/", $dt->no_pp)[2], // Plant
							"VDATU"   			=> date("Ymd", strtotime($datetime)), // Req Delivery Date
							"BSTDK"   			=> date("Ymd", strtotime($datetime)), // tanggal SO
							"BSTKD"   			=> $dt->no_pp, // NAMA PO
							"TELF1"   			=> $dt->no_hp, // no telp
							"VBELN"   			=> null, // no so
							"I_FLAG"   			=> ' ', // no so
						);
						
						//format posnr 6 digit
						$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
						
						$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
						$table_items[] = array(
							// "id_row_analisa" => $dt->id_row_analisa,
							"MANDT"   	=> '310', // SAP SERVER
							"FPKP"   	=> $dt->perihal, // Perihal Pengajuan
							"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
							"MATNR"   	=> $dt->kode_material, // Kode Material
							"MEINS"   	=> $dt->uom, // UOM
							"KWMENG"   	=> str_replace(",", "", $dt->qty), // HARGA SATUAN
							"NETWR"   	=> $dt->harga_nego, // HARGA DISETUJUI TOTAL
							"WAERS"   	=> 'IDR' // CURRENCY
						);
	
						$prevCustomer = $dt->kode_customer;

					}else{
						
						// if kunnr sama
						// gaush input data sap dan input data items

						$posnr = str_pad($counter*10, 6, '0', STR_PAD_LEFT);
						$id_row_analisa[] = array("id_row_analisa" => $dt->id_row_analisa);
						$table_items[] = array(
							// "id_row_analisa" => $dt->id_row_analisa,
							"MANDT"   	=> '310', // SAP SERVER
							"FPKP"   	=> $dt->perihal, // Perihal Pengajuan
							"POSNR"   	=> $posnr, // Line item format 0000i0 - i = jumlah item - QTY
							"MATNR"   	=> $dt->kode_material, // Kode Material
							"MEINS"   	=> $dt->uom, // UOM
							"KWMENG"   	=> str_replace(",", "", $dt->qty), // HARGA SATUAN
							"NETWR"   	=> $dt->harga_nego, // HARGA DISETUJUI TOTAL
							"WAERS"   	=> 'IDR' // CURRENCY
						);
						$prevCustomer = $dt->kode_customer;
					}
				}

				$counter++;
				
			}
			
			// execute ke sap data terakhir atah kalau datanya cuma satu
			$this->sap_scrapSO(array(
				"header" => $data_sap, 
				"table_items" => $table_items,
				"id_row_analisa" => $id_row_analisa,
				"connect" => FALSE
			));

			// echo json_encode("berhasil");exit();

			return true;
			
		}

		public function sap_scrapSO($param = NULL) {
			$header = $param['header'];
			$table_items = $param['table_items'];
			$id_row_analisa = $param['id_row_analisa'];
			$this->connectSAP("ERP_310");
			// $this->connectSAP("ERP");

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbPortal();

			$this->dgeneral->begin_transaction();
			$datetime = date("Y-m-d H:i:s");

			if ($this->data['sap']->getStatus() == SAPRFC_OK) {
				
				$param = array(
					array("IMPORT", "I_KUNNR", $header['KUNNR']),
					array("IMPORT", "I_VKORG", $header['VKORG']),
					array("IMPORT", "I_VDATU", date("Ymd", strtotime($datetime))),
					array("IMPORT", "I_BSTDK", date("Ymd", strtotime($datetime))),
					array("IMPORT", "I_BSTKD", $header['BSTKD']),
					array("IMPORT", "I_TELF1", $header['TELF1']),
					array("IMPORT", "I_VBELN", $header['VBELN']),
					array("IMPORT", "I_FLAG", $header['I_FLAG']),
					array("TABLE", "T_RETURN", array()),
					array("TABLE", "T_ITEMS", $table_items),
					array("EXPORT", "E_VBELN", array()),
				);		

				// echo json_encode($param);exit();
				$result = $this->data['sap']->callFunction("Z_RFC_CREATESOSCRAP", $param);
				// echo json_encode($result);exit();

				/**=======================================
				 * - add function write data into log file
				 *=======================================*/
				$logdir = KIRANA_PATH_LOGS . 'kiass/';
				if (!file_exists($logdir)) {
					@mkdir($logdir, 0777, true);
				}
				@chmod($logdir, 0775);
				$logfile = $logdir . 'KIASS -' . date('Ymd') . ".txt";
				$this->general->prepend_log(JSON_encode(compact('result','param')), $logfile);
				/**=======================================*/

				if ($this->data['sap']->getStatus() == SAPRFC_OK && !empty($result["T_RETURN"])) {
					$type    = array();
					$message = array();
					foreach ($result["T_RETURN"] as $return) {
						$type[]    = $return['TYPE'];
						$message[] = $return['MESSAGE'];
					}

					if (in_array('E', $type) === true) {
						$data_row_log = array(
							'app'           => 'DATA RFC Create SO (K-IASS)',
							'rfc_name'      => 'Z_RFC_CREATESOSCRAP',
							'log_code'      => implode(" , ", $type),
							'log_status'    => 'Gagal',
							'log_desc'      => "Create SO Failed [T_RETURN]: " . implode(" , ", $message),
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
					}
					else {
						$data_row_log = array(
							'app'           => 'DATA RFC Create SO (K-IASS)',
							'rfc_name'      => 'Z_RFC_CREATESOSCRAP',
							'log_code'      => implode(" , ", $type),
							'log_status'    => 'Berhasil',
							'log_desc'      => implode(" , ", $message),
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
					}

					//update data NO SO
					$no_so = NULL;
					
					$no_so = $result["E_VBELN"];
					

					if ($no_so !== NULL && $no_so !== "") {

						foreach($id_row_analisa as $dt){
											
							if($header['I_FLAG'] == 'X'){
								$data_row = array(
									"no_so"               => $no_so
								);
							}
							
							$data_row = $this->dgeneral->basic_column("update", $data_row);
							$this->dgeneral->update("tbl_scrap_analisa_harga", $data_row, array(
								array(
									'kolom' => 'no_pp',
									'value' => $header["no_pp"]
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
					// if ($this->dgeneral->status_transaction() === false) {
					// 	$this->dgeneral->rollback_transaction();
					// 	$this->general->closeDb();
					// 	$msg = "Periksa kembali data yang dimasukkan";
					// 	$sts = "NotOK";

					// 	$return = array('sts' => $sts, 'msg' => $msg);
					// 	echo json_encode($return);
					// 	exit();
					// }
					// else {
					// 	$this->dgeneral->commit_transaction();
					// 	$this->general->closeDb();
					// 	$msg = $data_row_log['log_desc'];
					// 	$sts = "OK";
					// 	if (in_array('E', $type) === true)
					// 		$sts = "NotOK";

					// 	if ($sts == "NotOK") {
					// 		$return = array('sts' => $sts, 'msg' => $msg);
					// 		echo json_encode($return);
					// 		exit();
					// 	}
					// 	else {
					// 		return array('sts' => $sts, 'msg' => $msg);
					// 	}
					// }
				}
				else {					
					$data_row_log = array(
						'app'           => 'DATA RFC Create SO (K-IASS)',
						'rfc_name'      => 'Z_RFC_CREATESOSCRAP',
						'log_code'      => isset($result["T_RETURN"]["TYPE"]),
						'log_status'    => 'Gagal',
						'log_desc'      => "Create SO Failed: " . isset($result["T_RETURN"]["MESSAGE"]),
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);

					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				}
			}
			else {
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC Create SO (K-IASS)',
					'rfc_name'      => 'Z_RFC_CREATESOSCRAP',
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
				$sts = "OK";
			}
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
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



		private function generate_message_email($no_pp, $action, $perihal = NULL, $komentar = NULL, $current_status = NULL, $pic_ho = NULL) {
			$plant = explode("/", $no_pp)[2];
			$pos = NULL;
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

			if($current_status == '9'){
				switch($pic_ho)
				{
					case 'Factory Operation':
						$pos = 'FACTORY OPERATION DIVISION HEAD';
						break;
					case 'Sourcing':
						$pos = 'SOURCING DIVISION HEAD';
						break;
					
					case 'ICT':
						$pos = 'INFORM & COMM TECH DIVISION HEAD';
						break;

					case 'HRGA':
						$pos = 'HR & GA DEPUTY DIVISION HEAD';
						break;
					
					case 'Finance Controller':
						$pos = 'FINANCE CONTROLLER DIVISION HEAD';
						break;
					
					default:
						$pos = NULL;
						break;
				}
			}

			if($current_status == '16'){
				switch($pic_ho)
				{
					case 'Factory Operation':
						$pos = 'PROCESS ENGINEERING DEPARTMENT HEAD';
						break;
					case 'Sourcing':
						$pos = 'SMALLHOLDER PARTNERSHIP DEPARTMENT HEAD';
						break;
					
					case 'ICT':
						$pos = 'IT DEVELOPMENT & SUPPORT DEPARTMENT HEAD';
						break;

					case 'HRGA':
						$pos = 'HR OPERATION & GA DEPARTMENT HEAD';
						break;
					
					case 'Finance Controller':
						$pos = 'FINANCE CONTROLLER DEPARTMENT HEAD';
						break;
					
					default:
						$pos = NULL;
						break;
				}
			}

			//list data email
			$data_recipient = $this->dmainscrap->get_email_recipient(
				array(
					"conn" => TRUE,
					"no_pp" => $no_pp,
					"plant" => $plant,
					"pic_ho" => $pos
				)
			);

			$email_cc = array();
			$email_to = array();
			$email_bcc = array();
			foreach ($data_recipient as $dt) {
				if ($dt->nilai == 'cc') {
					// $email_cc[] = ENVIRONMENT == 'development' ? "matthew.jodi@kiranamegatara.com" : $dt->email;
					$email_cc[] = 'matthew.jodi@kiranamegatara.com';
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
				"no_pp" 		  => $no_pp,
				"status"          => $status,
				"perihal"         => $perihal,
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
									Notifikasi Email K-IASS
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
														<td>Nomor Pengajuan Penjualan</td>
														<td>:</td>";
			$message .= "<td>" . $param['no_pp'] . "</td>"; //NOMOR PP
			$message .= "</tr>
													<tr>
														<td>Perihal</td>
														<td>:</td>";
			$message .= "<td>" . $param['perihal'] . "</td>"; //PERIHAL
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
									<p>Selanjutnya anda dapat melakukan review pada Pengajuan Penjualan tersebut</p><p>melalui aplikasi K-IASS di Portal Kiranaku.</p>
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

			$this->email->subject('Notifikasi Status Pengajuan Penjualan');
			$this->email->message($message);

			$this->email->send();
		}

		private function generate_message_email_kunnr($no_pp) {
			$plant = explode("/", $no_pp)[2];
			$pos = NULL;

			//list data email
			$data_recipient = $this->dmainscrap->get_email_recipient_kunnr(
				array(
					"conn" => TRUE,
					"plant" => $plant,
				)
			);

			$email_cc = array();
			$email_to = array();
			$email_bcc = array();
			foreach ($data_recipient as $dt) {
				
				// $email_to[] = ENVIRONMENT == 'development' ? "matthew.jodi@kiranamegatara.com" : $dt->email;
				$email_to[] = "frans.darmawan@kiranamegatara.com";
				$email_to[] = "matthew.jodi@kiranamegatara.com";
				if ($dt->nama !== "" && $dt->gender !== "") {
					$nama_to[] = $dt->gender . " " . ucwords(strtolower($dt->nama)) . "<br>";
				}
				
			}

			$data_email = array(
				"no_pp" 		  => $no_pp,
				"email_cc"        => $email_cc,
				"email_to"        => $email_to,
				"nama_to"         => empty($nama_to) ? "" : implode("", $nama_to)
			);
			$this->send_email_kunnr($data_email);
		}

		private function send_email_kunnr($param) {
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
									Notifikasi Kode Customer KIASS
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
												<h3 style='margin-top: 0;'>Notifikasi Kode Customer</h3>
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
			$message .= "<p>Email ini menandakan bahwa ada Pengajuan Penjualan yang membutuhkan input Kode Customer dari anda.</p>";
			$message .= "<table style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
													<tr>
														<td>Nomor Pengajuan Penjualan</td>
														<td>:</td>";
			$message .= "<td>" . $param['no_pp'] . "</td>"; //NOMOR PP
			$message .= "</tr>
													<tr>
														<td>Tanggal</td>
														<td>:</td>";
			$message .= "<td>" . strftime('%A, %d %B %Y') . "</td>"; //TANGGAL KIRIM EMAIL
			$message .= "</tr>
									</table>
									<p>Selanjutnya anda dapat melakukan input Kode Customer pada Pengajuan Penjualan tersebut</p><p>melalui aplikasi K-IASS di Portal Kiranaku.</p>
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

			$this->email->subject('Notifikasi Kode Customer Pengajuan Penjualan');
			$this->email->message($message);

			$this->email->send();
		}


	}
