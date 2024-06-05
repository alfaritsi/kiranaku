<?php
	/*
        @application  : UMB (Uang Muka Bokar)
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 25-Sep-18
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */

	include_once APPPATH . "modules/umb/controllers/BaseControllers.php";

	class Scoring extends BaseControllers {

		public function __construct() {
			parent::__construct();
			$this->data['module'] = "Uang Muka Bokar";
			$this->load->model('dmasterumb');
			$this->load->model('dsettingumb');
			$this->load->model('dscoringumb');
		}

		public function data() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/

			$this->data['title'] = "Scoring UM";
			$this->data['tahun'] = $this->dscoringumb->get_scoring_header_tahun();
			$this->data['plant'] = $this->get_master_plant(explode(",", $this->data['plant_kode']), false, NULL, "array");
			$this->data['tipe']  = $this->dmasterumb->get_master_tipe_scoring("open");

			// echo json_encode($this->generate->kirana_encrypt('15'));exit();
			// Y2wrdFZhaVB6MXhaVUY3YWtMZHVRUT09
			// eTJOMnJpZDROL2hpZlRGbnJkYzNEUT09

			$this->load->view("scoring/page", $this->data);
		}

		public function approve() {
			//====must be initiate in every view function====/
			$this->general->check_access();
			//===============================================/

			$this->data['title'] = "Approval Scoring UM";
			$this->data['tahun'] = $this->dscoringumb->get_scoring_header_tahun();
			$this->data['tipe']  = $this->dmasterumb->get_master_tipe_scoring("open");
			$this->data['plant'] = $this->get_master_plant(explode(",", $this->data['plant_kode']), false, NULL, "array");
			$this->load->view("scoring/approval", $this->data);

			// echo json_encode($this->generate->kirana_decrypt('NkJ4ZTcxd09xN1AvTHdWL2VUSXdoZz09'));exit();

		}

		// adjusting to reguler and non reguler
		public function tambah($param=NULL) {
			switch ($param) {
				case 'reguler':
					//====must be initiate in every view function====/
					$this->general->check_access();
					//===============================================/
					$this->data['title']         = "Tambah Scoring UM";
					$this->data['plant']         = $this->get_master_plant(explode(",", $this->data['plant_kode']), false, NULL, "array");
					$this->data['kriteria']      = $this->dmasterumb->get_master_kriteria("open", NULL, 'n', 'n', NULL, NULL);
					$this->data['plafon_pabrik'] = $this->get_sisa_plafon($this->data['plant_kode']);
					//dirops ranger
					$this->data['dirops']       = $this->get_ranger_dirops($this->data['plant_kode']);

					$this->load->view("scoring/add", $this->data);
					break;
				case 'nonreguler':
					//====must be initiate in every view function====/
					$this->general->check_access();
					//===============================================/
					$this->data['title']         = "Uang Muka non-reguler";
					$this->data['plant']         = $this->get_master_plant(explode(",", $this->data['plant_kode']), false, NULL, "array");
					// echo json_encode("testsdsfasdfa");exit();

					$this->load->view("scoring/ba", $this->data);
					break;
				default:
					show_404();
					break;
			}
		}

		public function detail($param=NULL, $no_form = NULL) {
			switch ($param) {
				case 'reguler':
					$check = $this->dscoringumb->get_data_scoring_header("open", str_replace("-", "/", $no_form));
					if (!isset($no_form) || $check == NULL || in_array($check[0]->plant, explode(",", $this->data['plant_kode'])) == false) {
						show_404();
					}
					//====must be initiate in every view function====/
					$this->general->check_session();
					//===============================================/

					$this->data['title']        = "Detail Scoring UM";
					$this->data['plant']        = $this->get_master_plant(explode(",", $this->data['plant_kode']), false, NULL, "array");
					$this->data['kriteria']     = $this->dmasterumb->get_master_kriteria("open", NULL, 'n', 'n', NULL, NULL);
					$this->data['summary']      = $check;
					$this->data['no_form']      = $no_form;
					$this->data['rekom_um_app'] = $this->dscoringumb->get_data_log_scoring("open", str_replace("-", "/", $no_form));

					//dirops ranger
					$this->data['dirops']       = $this->get_ranger_dirops($check[0]->plant);

					// echo json_encode($this->generate->kirana_encrypt('465'));exit();

					$this->load->view("scoring/detail", $this->data);
					break;
				case 'nonreguler':
					$check = $this->dscoringumb->get_data_scoring_header("open", str_replace("-", "/", $no_form));
					if (!isset($no_form) || $check == NULL || in_array($check[0]->plant, explode(",", $this->data['plant_kode'])) == false) {
						show_404();
					}
					//====must be initiate in every view function=====/
					$this->general->check_session();
					//===============================================/
					$this->data['title']        = "Detail Uang Muka non-reguler";
					$this->data['plant']        = $this->get_master_plant(explode(",", $this->data['plant_kode']), false, NULL, "array");
					$this->data['no_form']      = $no_form;
					$this->data['dirops']       = $this->get_ranger_dirops($check[0]->plant);		

					$this->load->view("scoring/ba_detail", $this->data);
					break;
				default:
					show_404();
					break;
			}
		}

		public function edit($no_form) {
			$check = $this->dscoringumb->get_data_scoring_header("open", str_replace("-", "/", $no_form));
			if (!isset($no_form) || $check == NULL || in_array($check[0]->plant, explode(",", $this->data['plant_kode'])) == false || $check[0]->status != $this->data['session_role'][0]->level) {
				show_404();
			}
			//====must be initiate in every view function====/
			$this->general->check_session();
			//===============================================/

			$this->data['title']         = "Edit Scoring UM";
			$this->data['plant']         = $this->get_master_plant(explode(",", $this->data['plant_kode']), false, NULL, "array");
			$this->data['kriteria']      = $this->dmasterumb->get_master_kriteria("open", NULL, 'n', 'n', NULL, NULL);
			$this->data['summary']       = $check;
			$this->data['no_form']       = $no_form;
			$this->data['plafon_pabrik'] = ($this->get_sisa_plafon($this->data['plant_kode']) + $this->data['summary'][0]->um_setuju);

			//dirops ranger
			$this->data['dirops']       = $this->get_ranger_dirops($check[0]->plant);


			$this->load->view("scoring/edit", $this->data);
		}

		

		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//
		public function get($param = NULL) {
			switch ($param) {
				case 'plafon-terpakai':
					$plant  = $this->data['plant_kode'];
					$tipe   = 'pabrik';
					$return = $this->dscoringumb->get_data_pemakaian_plafon("open", $plant, $tipe);
					echo json_encode($return);
					break;
				case 'list-scoring':
					$plant  = (isset($_POST['plant']) && $_POST['plant'] !== "" ? $_POST['plant'] : explode(",", $this->data['plant_kode']));
					$tahun  = (isset($_POST['tahun']) && $_POST['tahun'] !== "" ? $_POST['tahun'] : date("Y"));
					$status = (isset($_POST['status']) && $_POST['status'] !== "" ? $_POST['status'] : NULL);
					$all    = array("onprogress", "finish", "drop", "completed", "stop");
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

					$tipe            = (isset($_POST['tipe']) && $_POST['tipe'] !== "" ? $_POST['tipe'] : NULL);
					$no_form_scoring = (isset($_POST['no_form_scoring']) && $_POST['no_form_scoring'] !== "" ? $_POST['no_form_scoring'] : NULL);
					$approval        = (isset($_POST['approval']) && $_POST['approval'] !== "" ? ($this->data['session_role'][0]->level == '10' ? '9' : $this->data['session_role'][0]->level) : NULL);
					$return          = $this->dscoringumb->get_vw_scoring_header("open", $no_form_scoring, $plant, $tahun, $filter, $query, $approval, $tipe);
					echo json_encode($return);
					break;
				case 'vendor':
					$plant        = (isset($_POST['plant']) && $_POST['plant'] !== '0' ? $_POST['plant'] : NULL);
					$plant        = (isset($_GET['plant']) && $_GET['plant'] !== '0' ? $_GET['plant'] : $plant);
					$lifnr        = (isset($_GET['lifnr']) ? strtolower($_GET['lifnr']) : NULL);
					$desc         = (isset($_GET['q']) ? $_GET['q'] : NULL);
					$limit_suplai = (isset($_POST['limit']) && $_POST['limit'] !== '0' ? $_POST['limit'] : NULL);
					$limit_suplai = (isset($_GET['limit']) && $_GET['limit'] !== '0' ? $_GET['limit'] : $limit_suplai);
					if ($plant) {
						$this->get_vendor(NULL, $plant, $lifnr, $desc, $limit_suplai);
						// $this->get_vendor(NULL, $plant, $lifnr, $desc, NULL);
					}
					break;
				case 'vendor-nonbkr':
					$plant = (isset($_POST['plant']) && $_POST['plant'] !== '0' ? $_POST['plant'] : NULL);
					$plant = (isset($_GET['plant']) && $_GET['plant'] !== '0' ? $_GET['plant'] : $plant);
					$lifnr = (isset($_GET['lifnr']) ? strtolower($_GET['lifnr']) : NULL);
					$desc  = (isset($_GET['q']) ? $_GET['q'] : NULL);
					if ($plant) {
						$this->get_vendor_nonbkr(NULL, $plant, $lifnr, $desc);
					}
					break;
				case 'provinsi':
					$id_prov  = (isset($_POST['prov']) ? $this->generate->kirana_decrypt($_POST['prov']) : NULL);
					$provinsi = (isset($_POST['provinsi']) ? $_POST['provinsi'] : NULL);
					$this->general->get_data_provinsi(NULL, $id_prov, $provinsi);
					break;
				case 'kabupaten':
					$id_kab              = (isset($_POST['kab']) ? $this->generate->kirana_decrypt($_POST['kab']) : NULL);
					$kabupaten           = (isset($_POST['kabupaten']) ? $_POST['kabupaten'] : NULL);
					$kabupaten           = (isset($_GET['q']) ? $_GET['q'] : $kabupaten);
					$provinsi            = (isset($_POST['provinsi']) ? $_POST['provinsi'] : NULL);
					$provinsi_in         = (isset($_POST['provinsi_in']) ? $_POST['provinsi_in'] : NULL);
					$provinsi_in         = (isset($_GET['provinsi_in']) ? $_GET['provinsi_in'] : $provinsi_in);
					$provinsi_in_decrypt = array();
					if ($provinsi_in !== NULL) {
						foreach ($provinsi_in as $dt) {
							array_push($provinsi_in_decrypt, $this->generate->kirana_decrypt($dt));
						}
					}
					$this->general->get_data_kabupaten(NULL, $id_kab, $kabupaten, $provinsi, $provinsi_in_decrypt);
					break;
				case 'supply':
					$plant   = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
					$lifnr   = (isset($_POST['supplier']) && $_POST['supplier'] !== NULL ? $this->generate->kirana_decrypt($_POST['supplier']) : NULL);
					$depo    = (isset($_POST['depo']) && $_POST['depo'] !== '0' ? $_POST['depo'] : NULL);
					$tanggal = (isset($_POST['tanggal']) ? $_POST['tanggal'] : date("Y-m-d"));
					$tipe_um = (isset($_POST['tipe']) ? $this->generate->kirana_decrypt($_POST['tipe']) : NULL);
					$this->get_data_supply(NULL, $plant, $lifnr, $depo, $tipe_um, $tanggal);
					break;
				case 'no-form':
					$tipe_um = (isset($_POST['tipe']) ? $this->generate->kirana_decrypt($_POST['tipe']) : NULL);
					$tipe    = $this->dmasterumb->get_master_tipe_scoring("open", $tipe_um);
					$plant   = $this->dsettingumb->get_setting_user("open", NULL, base64_decode($this->session->userdata("-nik-")), NULL, 'n', 'n');
					$scoring = $this->dscoringumb->get_data_scoring_header("open", NULL, $tipe_um, date("Y"), $tipe->alias . "/" . rtrim($plant[0]->kode_pabrik_list, ",") . "/" . date("Y") . "/");
					$no_form = $tipe->alias . "/" . rtrim($plant[0]->kode_pabrik_list, ",") . "/" . date("Y") . "/" . str_pad(count($scoring) + 1, 5, '0', STR_PAD_LEFT);
					echo json_encode($no_form);
					break;
				case 'no-ba':
					$tipe_um = (isset($_POST['tipe']) ? $this->generate->kirana_decrypt($_POST['tipe']) : NULL);
					$tipe    = $this->dmasterumb->get_master_tipe_scoring("open", $tipe_um);
					$plant   = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
					$scoring = $this->dscoringumb->get_data_scoring_header("open", NULL, $tipe_um, date("Y"), $tipe->alias . "/" . $plant . "/" . date("Y") . "/");
					$no_ba = $tipe->alias . "/" . $plant . "/" . date("Y") . "/" . str_pad(count($scoring) + 1, 5, '0', STR_PAD_LEFT);
					$sisa_plafon = $this->get_sisa_plafon($_POST['plant']);
					$dirops = "";
					if($tipe_um == '4'){
						$dirops = $this->get_ranger_dirops($plant);
					}
					$data = array('no_ba' => $no_ba, 'sisa_plafon' => $sisa_plafon, 'dirops' => $dirops);
					echo json_encode($data);
					break;
				case 'scoring':
					$no_form = (isset($_POST['no_form']) ? $_POST['no_form'] : NULL);
					$plant   = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
					$tipe_um = (isset($_POST['tipe']) ? $this->generate->kirana_decrypt($_POST['tipe']) : NULL);
					$year    = (isset($_POST['year']) ? $_POST['year'] : NULL);
					// ada kondisi buat secretary
					$status = (isset($_POST['param']) && $_POST['param'] == "approve" ? ($this->data['session_role'][0]->level == '10' ? '9' : $this->data['session_role'][0]->level) : NULL);
					$this->get_data_scoring(NULL, $no_form, $tipe_um, $year, $status);
					break;
				case 'um-by-score':
					$score   = (isset($_POST['score']) ? $_POST['score'] : NULL);
					$tipe_um = (isset($_POST['tipe']) ? $this->generate->kirana_decrypt($_POST['tipe']) : NULL);
					$kelas   = (isset($_POST['kelas']) ? $_POST['kelas'] : NULL);
					$return  = $this->dscoringumb->get_data_um_scoring("open", $tipe_um, $score, $kelas);
					echo json_encode($return);
					break;
				case 'log-status':
					$no_form = (isset($_POST['no_form']) ? $_POST['no_form'] : NULL);
					$return  = $this->dscoringumb->get_data_log_scoring("open", $no_form);
					echo json_encode($return);
					break;
				case 'mou':
					$no_form = (isset($_POST['no_form']) ? $_POST['no_form'] : NULL);
					$return  = $this->dscoringumb->get_data_dok_mou("open", $no_form);
					echo json_encode($return);
					break;
				case 'jaminan-nilai':
					$id_scoring_jaminan_header = (isset($_POST['id_scoring_jaminan_header']) ? $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_header']) : NULL);
					$id_scoring_jaminan_detail = (isset($_POST['id_scoring_jaminan_detail']) ? $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_detail']) : NULL);

					$return = $this->dscoringumb->get_data_jaminan_detail_nilai("open", $id_scoring_jaminan_header, $id_scoring_jaminan_detail);

					if (isset($return->id_scoring_jaminan_nilai) && $return->id_scoring_jaminan_nilai != NULL) {
						$jaminan_metode = $this->dscoringumb->get_data_jaminan_detail_metode("open", $return->id_scoring_jaminan_nilai);
						if ($jaminan_metode) {
							$jaminan_metode = $this->general->generate_encrypt_json($jaminan_metode, array("id_scoring_jaminan_metode", "id_scoring_jaminan_nilai"));
						}
						$return->metode = $jaminan_metode;
					}

					$return = $this->general->generate_encrypt_json($return, array("id_scoring_jaminan_detail", "id_scoring_jaminan_header", "id_scoring_jaminan_nilai"));

					echo json_encode($return);
					break;
				case 'historical':
					$plant   = (isset($_POST['plant']) ? $_POST['plant'] : NULL);
					$lifnr   = (isset($_POST['supplier']) && $_POST['supplier'] !== "" ? $this->generate->kirana_decrypt($_POST['supplier']) : NULL);
					$depo    = (isset($_POST['depo']) && $_POST['depo'] !== "0" ? $this->generate->kirana_decrypt($_POST['depo']) : NULL);
					$deponm  = (isset($_POST['deponm']) && $_POST['deponm'] !== '0' ? $_POST['deponm'] : NULL);
					$tipe_um = (isset($_POST['tipe']) ? $this->generate->kirana_decrypt($_POST['tipe']) : NULL);
					$tanggal_awal = (isset($_POST['tanggal_awal']) ? $_POST['tanggal_awal'] : NULL);
					$tanggal_akhir = (isset($_POST['tanggal_akhir']) ? $_POST['tanggal_akhir'] : NULL);
					$this->get_historical(NULL, $lifnr, $depo, $plant, $tipe_um, $tanggal_awal, $tanggal_akhir, $deponm);
					
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		public function save($param = NULL, $param2 = NULL) {
			switch ($param) {
				case 'scoring':
					$this->save_scoring();
					break;
				case 'approval':
					$this->save_approval();
					break;
				case 'nilai':
					if ($param2 == "jaminan")
						$this->save_penilaian_jaminan();
					break;
				case 'appraisal':
					$this->save_penilaian_appraisal();
					break;
				case 'revisi':
					$this->save_revisi_appraisal();
					break;
				case 'legal':
					$this->save_rekomendasi_legal();
					break;
				case 'mou':
					$this->save_mou();
					break;
				case 'renewal':
					$this->save_renewal();
					break;
				case 'ba':
					$this->save_berita_acara();
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		public function cetak($no_form = NULL) {
			$no_form      = str_replace("-", "/", $no_form);
			$tipe_scoring = explode("/", $no_form)[0];
			if (isset($no_form) && trim($no_form) !== "" && trim($no_form) != NULL) {
				$this->load->library('Mypdf');

				$scoring  = $this->get_data_scoring('array', $no_form);
				$approval = $this->dscoringumb->get_data_log_scoring("open", $no_form, 'ASC');
				$jaminan  = $this->dscoringumb->get_data_jaminan_scoring("open", $no_form, 'ASC');


				if ($tipe_scoring == "UMK" || $tipe_scoring == "DM" || $tipe_scoring == "DMT") {

					$this->mypdf->AddPage();
					// Judul
					$this->mypdf->headers();
					$this->mypdf->SetFont('Arial', 'BU', 14);
					$this->mypdf->Cell(0, 0, 'FORM PERSETUJUAN UANG MUKA', 0, 0, 'C');
					$this->mypdf->Ln(5);
					$this->mypdf->SetFont('Arial', 'B', 10);
					$this->mypdf->Cell(0, 0, 'No Form : ' . $scoring[0]->no_form_scoring, 0, 0, 'C');
					$this->mypdf->Ln(20);

					// Scoring header
					$this->mypdf->SetFont('Arial', '', 10);
					$this->mypdf->Cell(40, 10, 'Pabrik', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ': ' . $scoring[0]->plant, 0, 0, 'L');
					$this->mypdf->Ln(5);
					if ($tipe_scoring == "UMK") {
						$this->mypdf->Cell(40, 10, 'Supplier', 0, 0, 'L');
						$this->mypdf->Cell(40, 10, ': ' . $scoring[0]->nama_supplier, 0, 0, 'L');
						$this->mypdf->Ln(5);
					}
					$depo = NULL;
					if ($tipe_scoring == "DM" || $tipe_scoring == "DMT") {
						$depo = $this->dgeneral->get_data_depo($this->generate->kirana_decrypt($scoring[0]->depo))[0]->DEPNM;
						$this->mypdf->Cell(40, 10, 'Depo', 0, 0, 'L');
						$this->mypdf->Cell(40, 10, ': ' . $depo, 0, 0, 'L');
						$this->mypdf->Ln(5);
						// $this->mypdf->Cell(40, 10, 'Jarak Tempuh', 0, 0, 'L');
						// $this->mypdf->Cell(40, 10, ': ' . $scoring[0]->jarak_tempuh, 0, 0, 'L');
						// $this->mypdf->Ln(5);
					}
					$this->mypdf->Cell(40, 10, 'Provinsi', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ': ' . implode(',', $scoring[0]->provinsi_nama), 0, 0, 'L');
					$this->mypdf->Ln(5);
					$this->mypdf->Cell(40, 10, 'Kabupaten', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ': ' . implode(',', $scoring[0]->kabupaten_nama), 0, 0, 'L');
					$this->mypdf->Ln(5);
					$this->mypdf->Cell(40, 10, 'Tanggal Pengajuan', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ': ' . date("d-m-Y", strtotime($scoring[0]->tanggal)), 0, 0, 'L');
					$this->mypdf->Ln(5);
					$this->mypdf->Cell(40, 10, 'Supply Sejak', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ': ' . date("d-m-Y", strtotime($scoring[0]->supply_start)), 0, 0, 'L');
					$this->mypdf->Ln(15);


					$supply = $this->get_print_supply('array', $scoring[0]->plant, $this->generate->kirana_decrypt($scoring[0]->kode_supplier), $depo, $this->generate->kirana_decrypt($scoring[0]->id_scoring_tipe), $scoring[0]->tanggal);

					//TABLE DATA PEMBELIAN 2 TAHUN TERAKHIR
					// TOTAL WIDTH = 180

					$this->mypdf->SetFont('Arial', 'B', 10);
					$this->mypdf->Cell(0, 0, 'Data Pembelian 2 Tahun Terakhir', 0, 0, 'C');
					$this->mypdf->Ln(5);

					//thead
					$this->mypdf->SetFillColor(0, 141, 76);
					$this->mypdf->SetTextColor(255);
					$this->mypdf->SetDrawColor(34, 45, 50);
					$this->mypdf->SetLineWidth(.3);
					$this->mypdf->SetFont('Arial', 'B', 8);

					$this->mypdf->Cell(20, 26, "Bulan", 1, 0, 'C', true);
					$this->mypdf->Cell(160, 8, "Supplier", 1, 0, 'C', true);
					$this->mypdf->Cell(0, 8, "", 0, 1, true);//imaginary line

					$this->mypdf->Cell(20, 5, "", 0, 0, true);//imaginary line
					$this->mypdf->Cell(160, 8, "Faktur", 1, 0, 'C', true);
					$this->mypdf->Cell(0, 8, "", 0, 1, true);

					$this->mypdf->Cell(20, 5, "", 0, 0, true);//imaginary line
					$this->mypdf->Cell(32, 10, "Qty Supply", 1, 0, 'C', true);
					$this->mypdf->Cell(32, 5, "Qty Supply / Minggu", 'LRT', 0, 'C', true);
					$this->mypdf->Cell(32, 5, "Total Kedatangan", 'LRT', 0, 'C', true);
					$this->mypdf->Cell(32, 10, "Jumlah HK", 1, 0, 'C', true);
					$this->mypdf->Cell(32, 5, "Qty Supply / Hari Beli", 'LRT', 0, 'C', true);
					$this->mypdf->Cell(0, 5, "", 0, 1, true);

					$this->mypdf->Cell(20, 5, "", 0, 0, true);//imaginary line
					$this->mypdf->Cell(32, 5, "", 0, 0, true);
					$this->mypdf->Cell(32, 5, "(Ton Kering)", 'LRB', 0, 'C', true);
					$this->mypdf->Cell(32, 5, "(Per Minggu)", 'LRB', 0, 'C', true);
					$this->mypdf->Cell(32, 5, "", 0, 0, true);
					$this->mypdf->Cell(32, 5, "(Ton Kering)", 'LRB', 0, 'C', true);
					$this->mypdf->Cell(0, 5, "", 0, 1, true);

					//CONTENT
					$this->mypdf->SetFont('Arial', 'B', 8);
					$this->mypdf->SetTextColor(0);
					$width                = array(20, 32, 32, 32, 32, 32);
					$jumlah_kedatangan    = 0;
					$qty_suplai_6         = 0;
					$qty_suplai_perweek_6 = 0;
					$total_dtg_perweek_6  = 0;
					$jumlah_hk_6          = 0;
					$qty_suplai_perhari_6 = 0;
					$suplai4bln_awal      = 0;
					$suplai2bln_akhir     = 0;
					$index                = 0;
					foreach ($supply as $dt) {
						$data_table = array(
							array(
								"align" => "C",
								"value" => $dt->bulan
							), //"bulan"
							array(
								"align" => "R",
								"value" => $dt->qty_suplai
							),//qty_suplai
							array(
								"align" => "R",
								"value" => $dt->qty_suplai_perweek
							),//"qty_suplai_perweek"
							array(
								"align" => "R",
								"value" => $dt->total_dtg_perweek
							),//"total_dtg_perweek"
							array(
								"align" => "R",
								"value" => $dt->jumlah_hk
							),//"jumlah_hk"
							array(
								"align" => "R",
								"value" => $dt->qty_suplai_perhari
							),//"qty_suplai_perhari"
						);

						if ($dt->qty_suplai > 0) {
							$jumlah_kedatangan++;
						}

						if ($index > 17) {
							if ($index < 22) {
								$suplai4bln_awal += $dt->qty_suplai_perhari;
							}
							$qty_suplai_6         += $dt->qty_suplai;
							$qty_suplai_perweek_6 += $dt->qty_suplai_perweek;
							$total_dtg_perweek_6  += $dt->total_dtg_perweek;
							$jumlah_hk_6          += $dt->jumlah_hk;
							$qty_suplai_perhari_6 += $dt->qty_suplai_perhari;
						}

						if ($index > 21) {
							$suplai2bln_akhir += $dt->qty_suplai_perhari;
						}

						$index++;

						$this->mypdf->SetWidths($width);
						$this->mypdf->Row($data_table);
					}

					if ($suplai2bln_akhir > 0 && $suplai4bln_awal > 0) {
						$growth1 = ($suplai2bln_akhir / 2) - ($suplai4bln_awal / 4);
						$growth2 = (($suplai2bln_akhir / 2) - ($suplai4bln_awal / 4)) / ($suplai4bln_awal / 4);
					}
					else {
						$growth1 = 0;
						$growth2 = 0;
					}
					// echo json_encode(($suplai2bln_akhir / 2)."-".($suplai4bln_awal / 4));exit();
					// echo json_encode($supply);exit();

					$this->mypdf->Cell(20, 5, "6 Bulan", 'LRT', 0, 'C', true);
					$this->mypdf->Cell(32, 5, "", 'LRT', 0, 'R', true);
					$this->mypdf->Cell(32, 5, "", 'LRT', 0, 'R', true);
					$this->mypdf->Cell(32, 5, "", 'LRT', 0, 'R', true);
					$this->mypdf->Cell(32, 5, "", 'LRT', 0, 'R', true);
					$this->mypdf->Cell(32, 5, "", "LRT", 1, 'R', true);

					$this->mypdf->Cell(20, 5, "terakhir", 'LR', 0, 'C', true);
					$this->mypdf->Cell(32, 5, number_format($qty_suplai_6 / 6, 2), 'LR', 0, 'R', true);
					$this->mypdf->Cell(32, 5, number_format($qty_suplai_perweek_6 / 6, 2), 'LR', 0, 'R', true);
					$this->mypdf->Cell(32, 5, number_format($total_dtg_perweek_6 / 6, 2), 'LR', 0, 'R', true);
					$this->mypdf->Cell(32, 5, number_format($jumlah_hk_6 / 6, 2), 'LR', 0, 'R', true);
					$this->mypdf->Cell(32, 5, number_format($qty_suplai_perhari_6 / 6, 2), 'LR', 1, 'R', true);

					$this->mypdf->Cell(20, 5, "(Average)", 'LRB', 0, 'C', true);
					$this->mypdf->Cell(32, 5, "", 'LRB', 0, 'R', true);
					$this->mypdf->Cell(32, 5, "", 'LRB', 0, 'R', true);
					$this->mypdf->Cell(32, 5, "", 'LRB', 0, 'R', true);
					$this->mypdf->Cell(32, 5, "", 'LRB', 0, 'R', true);
					$this->mypdf->Cell(32, 5, "", 'LRB', 0, 'R', true);
					// ==========================

					$this->mypdf->Ln(15);
					$this->mypdf->SetTextColor(0);
					$this->mypdf->SetFont('Arial', 'B', 8);
					$this->mypdf->Cell(20, 10, 'Kelas', 0, 0, 'C');
					$this->mypdf->Cell(10, 10, 'S', 1, 0, 'C');
					$this->mypdf->Ln(10);

					$this->mypdf->Cell(70, 10, 'Jumlah kedatangan dalam 24 bulan terakhir', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ": " . $jumlah_kedatangan . " bulan dari 24 bulan", 0, 0, 'L');
					$this->mypdf->Ln(5);
					$this->mypdf->Cell(70, 10, 'Frekuensi kedatangan / minggu dalam 6 bulan', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ": " . number_format($total_dtg_perweek_6 / 6, 2) . " x seminggu", 0, 0, 'L');
					$this->mypdf->Ln(5);
					$this->mypdf->Cell(70, 10, 'Tren suplai harian 4 bulan pertama dalam 6 bulan', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ': ' . number_format($suplai4bln_awal / 4, 2), 0, 0, 'L');
					$this->mypdf->Ln(5);
					$this->mypdf->Cell(70, 10, 'Tren suplai harian 2 bulan terakhir', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ': ' . number_format($suplai2bln_akhir / 2, 2), 0, 0, 'L');
					$this->mypdf->Ln(5);
					$this->mypdf->Cell(70, 10, 'Growth 2-4 6 bulan terakhir', 0, 0, 'L');
					$this->mypdf->Cell(40, 10, ': ' . number_format($growth1, 2) . ' atau ' . number_format($growth2, 2) . ' %', 0, 0, 'L');
					$this->mypdf->Ln(5);


					//KRITERIA SCORING
					if ($tipe_scoring == "UMK" || $tipe_scoring == "DM") {
						$this->mypdf->Ln(15);
						$this->mypdf->SetTextColor(0);
						$this->mypdf->SetFont('Arial', 'B', 10);
						$this->mypdf->Cell(0, 0, 'Kriteria Scoring', 0, 0, 'C');
						$this->mypdf->Ln(5);

						$this->mypdf->SetFillColor(0, 141, 76);
						$this->mypdf->SetTextColor(255);
						$this->mypdf->SetDrawColor(34, 45, 50);
						$this->mypdf->SetLineWidth(.3);
						$this->mypdf->SetFont('Arial', 'B', 8);

						$this->mypdf->Cell(40, 8, "Kriteria", 1, 0, 'C', true);
						$this->mypdf->Cell(20, 8, "Bobot", 1, 0, 'C', true);
						$this->mypdf->Cell(20, 8, "1", 1, 0, 'C', true);
						$this->mypdf->Cell(20, 8, "2", 1, 0, 'C', true);
						$this->mypdf->Cell(20, 8, "3", 1, 0, 'C', true);
						$this->mypdf->Cell(20, 8, "4", 1, 0, 'C', true);
						$this->mypdf->Cell(20, 8, "Nilai", 1, 0, 'C', true);
						$this->mypdf->Cell(20, 8, "Score", 1, 1, 'C', true);

						//CONTENT 
						$this->mypdf->SetFont('Arial', 'B', 8);
						$this->mypdf->SetTextColor(0);
						$width = array(40, 20, 20, 20, 20, 20, 20, 20);
						$score = 0;
						$bobot = 0;
						foreach ($scoring[0]->kriteria as $kr) {
							// echo json_encode(explode('|',$param[0])[2]);exit();

							$param = explode(',', $kr->param);
							$p1    = isset($param[0]) ? explode('|', $param[0])[1] . "-" . explode('|', $param[0])[2] : 'NULL';
							$p2    = isset($param[1]) ? explode('|', $param[1])[1] . "-" . explode('|', $param[1])[2] : 'NULL';
							$p3    = isset($param[2]) ? explode('|', $param[2])[1] . "-" . explode('|', $param[2])[2] : 'NULL';
							$p4    = isset($param[3]) ? explode('|', $param[3])[1] . "-" . explode('|', $param[3])[2] : 'NULL';
							$score = $score + $kr->score;
							$bobot = $bobot + $kr->bobot;

							$data_table = array(
								array(
									"align" => "C",
									"value" => $kr->kriteria_desc
								), //"kriteria"
								array(
									"align" => "R",
									"value" => $kr->bobot
								),//bobot
								array(
									"align" => "R",
									"value" => $p1
								),//"param 1"
								array(
									"align" => "R",
									"value" => $p2
								),//"param 2"
								array(
									"align" => "R",
									"value" => $p3
								),//"param 3"
								array(
									"align" => "R",
									"value" => $p4
								),//"param 4"
								array(
									"align" => "R",
									"value" => $kr->nilai
								),//"nilai"
								array(
									"align" => "R",
									"value" => $kr->score
								),//"score"
							);

							$this->mypdf->SetWidths($width);
							$this->mypdf->Row($data_table);
						}
						// ====================================================
						$this->mypdf->Cell(40, 8, "SUBTOTAL", 1, 0, 'C');
						$this->mypdf->Cell(20, 8, number_format($bobot, 2), 1, 0, 'R');
						$this->mypdf->Cell(60, 8, "", 1, 0, 'C');
						$this->mypdf->Cell(40, 8, "TOTAL", 1, 0, 'C');
						$this->mypdf->Cell(20, 8, $score, 1, 1, 'R');
					}


					//JAMINAN
					if ($scoring[0]->um_jamin > 0) {
						$this->mypdf->Ln(15);
						$this->mypdf->SetTextColor(0);
						$this->mypdf->SetFont('Arial', 'B', 10);
						$this->mypdf->Cell(0, 0, 'Jaminan', 0, 0, 'C');
						$this->mypdf->Ln(5);

						$this->mypdf->SetFillColor(0, 141, 76);
						$this->mypdf->SetTextColor(255);
						$this->mypdf->SetDrawColor(34, 45, 50);
						$this->mypdf->SetLineWidth(.3);
						$this->mypdf->SetFont('Arial', 'B', 8);

						$this->mypdf->Cell(30, 8, "Jenis Jaminan", 1, 0, 'C', true);
						$this->mypdf->Cell(30, 8, "Detail Jaminan", 1, 0, 'C', true);
						$this->mypdf->Cell(30, 8, "Nilai Jaminan", 1, 0, 'C', true);
						$this->mypdf->Cell(30, 8, "% Disc", 1, 0, 'C', true);
						$this->mypdf->Cell(30, 8, "Nilai Appraisal", 1, 0, 'C', true);
						$this->mypdf->Cell(30, 8, "Hasil Appraisal", 1, 1, 'C', true);

						//CONTENT
						$this->mypdf->SetFont('Arial', '', 8);
						$this->mypdf->SetTextColor(0);
						$width = array(30, 30, 30, 30, 30, 30);
						foreach ($jaminan as $jm) {
							$data_table = array(
								array(
									"align" => "C",
									"value" => $jm->jenis
								), //"bulan"
								array(
									"align" => "C",
									"value" => $jm->detail
								),//qty_suplai
								array(
									"align" => "R",
									"value" => number_format($jm->nilai_jaminan, 2)
								),//"qty_suplai_perweek"
								array(
									"align" => "R",
									"value" => $jm->persen_discount . ' %'
								),//"total_dtg_perweek"
								array(
									"align" => "R",
									"value" => number_format($jm->nilai_appraisal, 2)
								),//"jumlah_hk"
								array(
									"align" => "R",
									"value" => number_format($jm->hasil_appraisal, 2)
								),//"qty_suplai_perhari"
							);

							$this->mypdf->SetWidths($width);
							$this->mypdf->Row($data_table);
						}
					}

					//SUMMARY UM
					$this->mypdf->Ln(15);

					$this->mypdf->SetTextColor(0);
					$this->mypdf->SetFont('Arial', '', 10);
					$this->mypdf->Cell(75, 12, 'Uang muka yang diajukan', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': Rp ' . number_format($scoring[0]->um_minta, 2), 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Uang muka perhitungan scoring', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': Rp ' . number_format($scoring[0]->um_scoring, 2), 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Uang muka nilai jaminan', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': Rp ' . number_format($scoring[0]->um_jamin, 2), 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Uang muka yang disetujui', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': Rp ' . number_format($scoring[0]->um_setuju, 2), 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Waktu penyelesaian uang muka', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . $scoring[0]->waktu_selesai . ' Hari', 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Maks uang muka outstanding', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . $scoring[0]->max_um_outstanding . ' Kali', 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Rekomendasi uang muka (appraisal jaminan)', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': Rp ' . number_format($scoring[0]->um_jamin, 2), 0, 0, 'L');
					$this->mypdf->Ln(15);


					$this->mypdf->AddPage();
					$this->mypdf->headers();

					$this->mypdf->SetFont('Arial', 'B', 10);
					$this->mypdf->Cell(0, 0, 'Approval & Rekomendasi', 0, 0, 'C');
					$this->mypdf->Ln(8);
					$this->mypdf->SetTextColor(0);
					$this->mypdf->SetFont('Arial', '', 10);

					foreach ($approval as $app) {
						// if ($app->action !== 'submit') {
						$this->mypdf->SetFont('Arial', 'B', 10);
						$this->mypdf->Cell(75, 12, ucfirst($app->action) . ' Oleh ' . $app->nama_role, 0, 0, 'L');
						$this->mypdf->SetFont('Arial', '', 10);
						$this->mypdf->Ln(8);
						if (isset($app->rekom_um_app) && $app->rekom_um_app > 0) {
							$this->mypdf->Cell(45, 10, 'Rekomendasi uang muka', 0, 0, 'L');
							$this->mypdf->Cell(45, 10, ': Rp ' . number_format($app->rekom_um_app, 2), 0, 0, 'L');
							$this->mypdf->Ln(8);
						}
						$this->mypdf->Cell(45, 10, 'Komentar', 0, 0, 'L');
						$this->mypdf->Cell(45, 10, ': ' . $app->comment, 0, 0, 'L');
						$this->mypdf->Ln(10);
						// }
					}

					// $this->mypdf->Ln(10);
					$this->mypdf->SetFont('Arial', 'B', 10);
					$this->mypdf->Cell(75, 12, 'Approval Oleh Presiden Direktur', 0, 0, 'L');
					$this->mypdf->SetFont('Arial', '', 10);
					$this->mypdf->Ln(10);
					$this->mypdf->Cell(45, 10, 'Rekomendasi uang muka', 0, 0, 'L');
					$this->mypdf->Cell(60, 10, ': ....................................................................................', 0, 0, 'L');
					$this->mypdf->Ln(10);
					$this->mypdf->Cell(45, 10, 'Komentar', 0, 0, 'L');
					$this->mypdf->Cell(60, 10, ': ....................................................................................', 0, 0, 'L');

					$this->mypdf->Ln(25);
					$y = $this->mypdf->GetY();
					$this->mypdf->SetXY(120, $y);
					$this->mypdf->SetFont('Arial', 'U', 10);
					$this->mypdf->Cell(0, 10, 'Tanda Tangan', 0, 0, 'R');
					$this->mypdf->footer();
					// ========================================================================================================
				}
				else {
					$this->mypdf->AddPage();

					// Judul
					$this->mypdf->headers();
					$this->mypdf->SetFont('Arial', 'BU', 14);
					$this->mypdf->Cell(0, 0, 'FORM PERSETUJUAN UANG MUKA', 0, 0, 'C');
					$this->mypdf->Ln(5);
					$this->mypdf->SetFont('Arial', 'B', 10);
					$this->mypdf->Cell(0, 0, 'No Form : ' . $scoring[0]->no_form_scoring, 0, 0, 'C');
					$this->mypdf->Ln(20);

					// Scoring header
					$this->mypdf->SetFont('Arial', '', 10);
					$this->mypdf->Cell(75, 12, 'Pabrik', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . $scoring[0]->plant, 0, 0, 'L');
					$this->mypdf->Ln(7);
					$depo = $this->dgeneral->get_data_depo($this->generate->kirana_decrypt($scoring[0]->depo))[0]->DEPNM;
					$this->mypdf->Cell(75, 12, 'Depo', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . $depo, 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Jarak Tempuh', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . $scoring[0]->jarak_tempuh, 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Provinsi', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . implode(',', $scoring[0]->provinsi_nama), 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Kabupaten', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . implode(',', $scoring[0]->kabupaten_nama), 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Tanggal Pengajuan', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . date("d-m-Y", strtotime($scoring[0]->tanggal)), 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Supply Sejak', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . date("d-m-Y", strtotime($scoring[0]->supply_start)), 0, 0, 'L');
					$this->mypdf->Ln(15);

					$this->mypdf->SetTextColor(0);
					$this->mypdf->SetFont('Arial', '', 10);
					$this->mypdf->Cell(75, 12, 'Uang muka yang diajukan', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': Rp ' . number_format($scoring[0]->um_minta, 2), 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Waktu penyelesaian uang muka', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . $scoring[0]->waktu_selesai . ' Hari', 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Maks uang muka outstanding', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': ' . $scoring[0]->max_um_outstanding . ' Kali', 0, 0, 'L');
					$this->mypdf->Ln(7);
					$rekom_um_purch  = 0;
					$rekom_um_fincon = 0;
					foreach ($approval as $app) {
						if ($app->status == '6') {
							$rekom_um_purch = $app->rekom_um_app;
						}
						else if ($app->status == '7') {
							$rekom_um_fincon = $app->rekom_um_app;
						}
					}
					$this->mypdf->Cell(75, 12, 'Rekomendasi uang muka (Purchasing)', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': Rp ' . number_format($rekom_um_purch, 2), 0, 0, 'L');
					$this->mypdf->Ln(7);
					$this->mypdf->Cell(75, 12, 'Rekomendasi uang muka (appraisal jaminan)', 0, 0, 'L');
					$this->mypdf->Cell(75, 12, ': Rp ' . number_format($rekom_um_fincon, 2), 0, 0, 'L');
					$this->mypdf->Ln(15);

					// $this->mypdf->AddPage();
					// $this->mypdf->headers();
					$this->mypdf->SetFont('Arial', 'B', 10);
					$this->mypdf->Cell(0, 0, 'Approval & Rekomendasi', 0, 0, 'C');
					$this->mypdf->Ln(8);
					$this->mypdf->SetTextColor(0);
					$this->mypdf->SetFont('Arial', '', 10);

					foreach ($approval as $app) {
						// if ($app->action !== 'submit') {
						$this->mypdf->SetFont('Arial', 'B', 10);
						$this->mypdf->Cell(75, 12, ucfirst($app->action) . ' Oleh ' . $app->nama_role, 0, 0, 'L');
						$this->mypdf->SetFont('Arial', '', 10);
						$this->mypdf->Ln(8);
						if (isset($app->rekom_um_app) && $app->rekom_um_app > 0) {
							$this->mypdf->Cell(45, 10, 'Rekomendasi uang muka', 0, 0, 'L');
							$this->mypdf->Cell(45, 10, ': Rp ' . number_format($app->rekom_um_app, 2), 0, 0, 'L');
							$this->mypdf->Ln(8);
						}
						$this->mypdf->Cell(45, 10, 'Komentar', 0, 0, 'L');
						$this->mypdf->Cell(45, 10, ': ' . $app->comment, 0, 0, 'L');
						$this->mypdf->Ln(10);
						// }
					}

					$this->mypdf->Ln(10);
					$this->mypdf->SetFont('Arial', 'B', 10);
					$this->mypdf->Cell(75, 12, 'Approval Oleh Presiden Direktur', 0, 0, 'L');
					$this->mypdf->SetFont('Arial', '', 10);
					$this->mypdf->Ln(10);
					$this->mypdf->Cell(45, 10, 'Rekomendasi uang muka', 0, 0, 'L');
					$this->mypdf->Cell(60, 10, ': ....................................................................................', 0, 0, 'L');
					$this->mypdf->Ln(10);
					$this->mypdf->Cell(45, 10, 'Komentar', 0, 0, 'L');
					$this->mypdf->Cell(60, 10, ': ....................................................................................', 0, 0, 'L');

					$this->mypdf->Ln(25);
					$y = $this->mypdf->GetY();
					$this->mypdf->SetXY(120, $y);
					$this->mypdf->SetFont('Arial', 'U', 10);
					$this->mypdf->Cell(0, 10, 'Tanda Tangan', 0, 0, 'R');
					$this->mypdf->footer();
				}
				$this->mypdf->Output();
			}
			else {
				show_404();
			}

		}

		/**********************************/
		/*			  private  			  */
		/**********************************/

		public function sap_plafonum($datas) {
			$this->connectSAP("ERP_310");

			//INISIALISASI DATA
			$data_scoring = $this->dscoringumb->get_data_summary_scoring('open', $datas["no_form_scoring"]);
			$depo         = "";
			if (isset($data_scoring->depo)) {
				$depo = $this->dgeneral->get_data_depo($data_scoring->depo, NULL, $data_scoring->plant)[0]->DEPNM;
			}

			// echo json_encode(number_format($data_scoring->um_jamin, 0, '', ''));exit();
			$splch = "";
			// UMK = '' -- M = DM -- DMT = T -- Ranger -- R
			switch ($datas["tipe_scoring"]) {
				case 'RG':
					$lifnr 		   = $data_scoring->dirops;
					$splch         = 'R';
					$tanggal_akhir = '99991231';
					break;
				case 'DM':
					$lifnr = $data_scoring->kode_supplier;
					$splch         = 'M';
					$tanggal_akhir = '99991231';
					break;
				case 'DMT':
					$lifnr = $data_scoring->kode_supplier;
					$splch = 'T';
					//        		$dates = new DateTime($datas["tanggal_finish"]); // Y-m-d
					// $dates->add(new DateInterval('P90D'));
					//        		$tanggal_akhir = $dates->format('Y-m-d');
					$tanggal_akhir = date('Ymd', strtotime($datas["tanggal_finish"] . ' +3 months'));
					break;
				default:
					$lifnr = $data_scoring->kode_supplier;
					$splch         = '';
					$tanggal_akhir = '99991231';
					break;
			}
			
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$datetime = date("Y-m-d H:i:s");

			if ($this->data['sap']->getStatus() == SAPRFC_OK) {
				$table_fee[] = array(
					"MANDT" => '310', // 310 dev or kmtemp prod
					"EKORG" => $data_scoring->plant, // plant
					"DEPNM" => $depo, // depo name
					"BEGDA" => date("Ymd", strtotime($datas["tanggal_finish"])), // tanggal_finish format dmY
					// "BEGDA"   => '20191009', // tanggal_finish format dmY
					"ENDDA" => '99991231', // end_date (kiamat) format dmY
					"WAERS" => 'IDR',
					"LIFNR" => $lifnr, // supplier kode
					"FEE01" => number_format($data_scoring->fee_non_tax, 0, '', ''), // fee non tax
					"FEE02" => number_format($data_scoring->fee_tax_gross, 0, '', ''), // fee tax gross
					"FEE03" => number_format($data_scoring->fee_non_gross, 0, '', ''), // fee non gross
				);

				$table_scoring[] = array(
					// "NUMB"    => $test, return dari sap (id ini save di sql)
					"EKORG"   => $data_scoring->plant, // plant
					"LIFNR"   => $lifnr, // supplier kode
					"BEGDA"   => date("Ymd", strtotime($datas["tanggal_finish"])), // tanggal_finish format dmY
					// "BEGDA"   => '20191009', // tanggal_finish format dmY
					"ENDDA"   => date("Ymd", strtotime($tanggal_akhir)), //end_date (kiamat) format dmY
					"SPLCH"   => $splch, //tipe_scoring m/r/t
					"DEPNM"   => $depo, // depo name
					"DEFLG"   => '', // delete FLAG (if delete = X capital)
					"UMUR"    => $data_scoring->waktu_selesai, // waktu selesai (hari)
					"JAMINAN" => number_format($data_scoring->um_jamin, 0, '', ''), // Nilai Jaminan
					"PFAMT"   => number_format($datas['um_final'], 0, '', ''), // um_finish
					"WAERS"   => 'IDR', // IDR
					"TOTAL"   => $data_scoring->max_um_outstanding, // max_um_outstanding (default 2)
				);


				if ($splch == 'R' && ($data_scoring->fee_non_tax !== NULL || $data_scoring->fee_tax_gross !== NULL || $data_scoring->fee_non_gross !== NULL)) {
					$param = array(
						array("TABLE", "T_RETURN", array()),
						array("TABLE", "T_FEE", $table_fee),
						array("TABLE", "T_DATA", $table_scoring),
					);
				}
				else {
					$param = array(
						array("TABLE", "T_RETURN", array()),
						array("TABLE", "T_FEE", array()),
						array("TABLE", "T_DATA", $table_scoring),
					);
				}


				$result = $this->data['sap']->callFunction("Z_RFC_CRT_PLAFONUM", $param);
				// echo json_encode($result);exit();

				if ($this->data['sap']->getStatus() == SAPRFC_OK && !empty($result["T_RETURN"])) {
					$type    = array();
					$message = array();
					foreach ($result["T_RETURN"] as $return) {
						$type[]    = $return['TYPE'];
						$message[] = $return['MESSAGE'];
					}

					if (in_array('E', $type) === true) {
						$data_row_log = array(
							'app'           => 'DATA RFC Create Plafonum (UMB)',
							'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
							'log_code'      => implode(" , ", $type),
							'log_status'    => 'Gagal',
							'log_desc'      => "Create Plafonum Failed [T_RETURN]: " . implode(" , ", $message),
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
					}
					else {
						$data_row_log = array(
							'app'           => 'DATA RFC Create Plafonum (UMB)',
							'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
							'log_code'      => implode(" , ", $type),
							'log_status'    => 'Berhasil',
							'log_desc'      => implode(" , ", $message),
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
					}

					//update data NO SAP for future edit => UMB Scoring Header
					$no_sap = NULL;
					foreach ($result["T_DATA"] as $tdata) {
						$no_sap = $tdata["NUMB"];
					}

					if ($no_sap !== NULL && $no_sap !== "") {
						if ($datas["renewal"] == "false") {
							$tanggal_finish = date("Y-m-d", strtotime($datas["tanggal_finish"]));
							$tanggal_akhirs = date('Y-m-d H:i:s', strtotime($tanggal_finish . ' +3 months'));
							$data_row       = array(
								"no_sap"           => $no_sap,
								"tanggal_finish"   => date("Y-m-d", strtotime($datetime)),
								"tanggal_berakhir" => ($datas["tipe_scoring"] == 'DMT' ? $tanggal_akhirs : date("Y-m-d H:i:s", strtotime("9999-12-31")))
							);
						}
						else {
							$data_row = array(
								"no_extend"               => $no_sap,
								"tanggal_berakhir_extend" => date('Y-m-d H:i:s', strtotime($tanggal_akhir)),
							);
						}
						$data_row = $this->dgeneral->basic_column("update", $data_row);
						$this->dgeneral->update("tbl_umb_scoring_header", $data_row, array(
							array(
								'kolom' => 'no_form_scoring',
								'value' => $datas["no_form_scoring"]
							)
						));
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
						'app'           => 'DATA RFC Create Plafonum (UMB)',
						'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
						'log_code'      => $result["T_RETURN"]["TYPE"],
						'log_status'    => 'Gagal',
						'log_desc'      => "Create Plafonum Failed: " . $result["T_RETURN"]["MESSAGE"],
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);

					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				}
			}
			else {
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC Create Plafonum (UMB)',
					'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
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

		private function sap_stop_plafonum($data_stop) {
			$this->connectSAP("ERP_310");

			//INISIALISASI DATA
			$data_scoring = $this->dscoringumb->get_data_summary_scoring('open', $data_stop["no_form_scoring"]);

			if ($data_scoring->no_sap == "" || $data_scoring->no_sap == NULL) {
				$return = array('sts' => 'notOK', 'msg' => 'Nomor SAP tidak ditemukan');
				echo json_encode($return);
				exit();
			}

			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$datetime = date("Y-m-d H:i:s");

			if ($this->data['sap']->getStatus() == SAPRFC_OK) {
				$table_scoring[] = array(
					"NUMB"    => $data_scoring->no_sap, //return dari sap (id ini save di sql)
					"EKORG"   => '', // plant
					"LIFNR"   => '', // supplier kode
					"BEGDA"   => '', // tanggal_finish format dmY
					"ENDDA"   => date("Ymd", strtotime($data_stop["tanggal_stop"])), // end_date (kiamat) format dmY
					"SPLCH"   => '', //tipe_scoring m/r/t
					"DEPNM"   => '', // depo name
					"DEFLG"   => 'X', // delete FLAG (if delete = X capital)
					"UMUR"    => '', // waktu selesai (hari)
					"JAMINAN" => '', // Nilai Jaminan
					"PFAMT"   => '', // um_finish
					"WAERS"   => '', // IDR
					"TOTAL"   => '', // max_um_outstanding (default 2)
				);

				$param = array(
					array("TABLE", "T_RETURN", array()),
					array("TABLE", "T_FEE", array()),
					array("TABLE", "T_DATA", $table_scoring),
				);

				$result = $this->data['sap']->callFunction("Z_RFC_CRT_PLAFONUM", $param);
				// echo json_encode($result);exit();

				if ($this->data['sap']->getStatus() == SAPRFC_OK && !empty($result["T_RETURN"])) {
					$type    = array();
					$message = array();
					foreach ($result["T_RETURN"] as $return) {
						$type[]    = $return['TYPE'];
						$message[] = $return['MESSAGE'];
					}

					if (in_array('E', $type) === true) {
						$data_row_log = array(
							'app'           => 'DATA RFC Stop Plafonum (UMB)',
							'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
							'log_code'      => implode(" , ", $type),
							'log_status'    => 'Gagal',
							'log_desc'      => "Stop Plafonum Failed [T_RETURN]: " . implode(" , ", $message),
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
					}
					else {
						$data_row_log = array(
							'app'           => 'DATA RFC Stop Plafonum (UMB)',
							'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
							'log_code'      => implode(" , ", $type),
							'log_status'    => 'Berhasil',
							'log_desc'      => implode(" , ", $message),
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
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
						'app'           => 'DATA RFC Stop Plafonum (UMB)',
						'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
						'log_code'      => $result["T_RETURN"]["TYPE"],
						'log_status'    => 'Gagal',
						'log_desc'      => "Stop Plafonum Failed: " . $result["T_RETURN"]["MESSAGE"],
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);

					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				}
			}
			else {
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC Stop Plafonum (UMB)',
					'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
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

		private function get_ranger_dirops($plant = NULL, $lifnr = NULL){
			$dirops = $this->dscoringumb->get_ranger_dirops("open", $plant, $lifnr);
			$dirops = $this->general->generate_encrypt_json($dirops, array("id"));
			return $dirops;
		}

		private function get_vendor($array = NULL, $plant = NULL, $lifnr = NULL, $desc = NULL, $limit_suplai = NULL) {
			$vendor = $this->dscoringumb->get_data_vendor("open", $plant, $lifnr, $desc, $limit_suplai);
			// echo json_encode($vendor);exit();
			$vendor = $this->general->generate_encrypt_json($vendor, array("id"));
			if (isset($_GET['q'])) {
				$data_json = array(
					"total_count"        => count($vendor),
					"incomplete_results" => false,
					"items"              => $vendor
				);
				echo json_encode($data_json);
			}
			else {
				if ($array) {
					return $vendor;
				}
				else {
					echo json_encode($vendor);
				}
			}
		}

		private function get_vendor_nonbkr($array = NULL, $plant = NULL, $lifnr = NULL, $desc = NULL) {
			$vendor = $this->dscoringumb->get_data_vendor_nonbkr("open", $plant, $lifnr, strtolower($desc));
			$vendor = $this->general->generate_encrypt_json($vendor, array("id"));
			if (isset($_GET['q'])) {
				$data_json = array(
					"total_count"        => count($vendor),
					"incomplete_results" => false,
					"items"              => $vendor
				);
				echo json_encode($data_json);
			}
			else {
				if ($array) {
					return $vendor;
				}
				else {
					echo json_encode($vendor);
				}
			}
		}

		private function get_data_supply($array = NULL, $plant = NULL, $lifnr = NULL, $depo = NULL, $tipe_um = NULL, $tanggal = NULL) {
			$data    = array();
			$tanggal = $this->generate->regenerateDateFormat($tanggal);
			if ($tipe_um !== 4) {
				$first  = $this->dscoringumb->get_data_supply("open", $plant, $lifnr, $depo, $tipe_um, $tanggal, 'yes');
				$supply = $this->dscoringumb->get_data_supply("open", $plant, $lifnr, $depo, $tipe_um, $tanggal, 'no');

				$data = array(
					"first"  => $first,
					"supply" => $supply
				);
			}

			if ($array) {
				return $data;
			}
			else {
				echo json_encode($data);
			}
		}

		private function get_historical($array = NULL, $lifnr = NULL, $depo = NULL, $plant = NULL, $tipe_um = NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL, $deponm = NULL) {
			$data    = array();

			if($tanggal_awal !== null)
				$tanggal_awal = $this->generate->regenerateDateFormat($tanggal_awal);
			if($tanggal_akhir !== null)
				$tanggal_akhir = $this->generate->regenerateDateFormat($tanggal_akhir);
			
			$po  = $this->dscoringumb->get_historical_supply("open", $lifnr, $deponm, $plant, $tipe_um, $tanggal_awal, $tanggal_akhir);
			$sum_po  = $this->dscoringumb->get_sum_supply("open", $lifnr, $deponm, $plant, $tipe_um, $tanggal_awal, $tanggal_akhir);
			$historical = $this->dscoringumb->get_historical_um("open", $lifnr, $depo, $plant, $tipe_um, $tanggal_awal, $tanggal_akhir);

			$data = array(
				"historical"  => $historical,
				"po" => $po,
				"sum_po" => $sum_po
			);
			

			if ($array) {
				return $data;
			}
			else {
				echo json_encode($data);
			}
		}

		private function get_print_supply($array = NULL, $plant = NULL, $lifnr = NULL, $depo = NULL, $tipe_um = NULL, $tanggal = NULL) {
			$data    = array();
			$tanggal = $this->generate->regenerateDateFormat($tanggal);
			if ($tipe_um !== 4) {
				$supply = $this->dscoringumb->get_data_supply("open", $plant, $lifnr, $depo, $tipe_um, $tanggal, 'no');
			}

			if ($array) {
				return $supply;
			}
			else {
				echo json_encode($data);
			}
		}

		private function get_sisa_plafon($plant) {
			$data_plafon_pabrik = $this->dmasterumb->get_master_plafon("open", $plant, '1');
			$plafon_pabrik      = 0;
			if ($data_plafon_pabrik)
				$plafon_pabrik = $data_plafon_pabrik[0]->limit_plafon;

			$sum_um = $this->dscoringumb->get_sum_umb_pabrik("open", $plant);

			$sisa = $plafon_pabrik - (isset($sum_um) ? $sum_um->plafon_terpakai : 0);

			$sisa = $sisa < 0 ? 0 : $sisa;

			return $sisa;
		}

		private function save_scoring() {
			$datetime = date("Y-m-d H:i:s");

			// echo ini_get('upload_max_filesize');
			// exit();

			if ($_POST['pabrik'] !== 0 && isset($_POST['provinsi']) && isset($_POST['kabupaten'])) {
				$status = $this->generate_status_scoring($_POST['action'], $_POST['no_form']);

				//CEK apakah plafon pabrik masih cukup. Double cek disini untuk antisipasi user mengubah via inspect element.
				$sisa_plafon = $this->get_sisa_plafon($_POST['pabrik']);
				$um_propose = (isset($_POST['um_propose']) && trim($_POST['um_propose']) !== "" ? str_replace(",", "", $_POST['um_propose']) : '0');
				if ($sisa_plafon < $um_propose) {
						$msg    = "Gagal Submit. Uang muka yang diajukan melebihi Plafon yang dimiliki oleh pabrik.";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
				}

				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				$tipe_scoring            = $this->generate->kirana_decrypt($_POST['tipe_scoring']);
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|jpeg|png|pdf';

				//================================SAVE SCORING HEADER================================//
				if ($_FILES['ktp_file']['name'][0] !== "") {
					if ((count($_FILES['ktp_file']['name']) > 1) || count($_FILES['npwp_file']['name']) > 1) {
						$msg    = "You can only upload maximum 1 file";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$newname  = array(str_replace("/", "-", $_POST['no_form']) . "-KTP");
					$file_ktp = $this->general->upload_files($_FILES['ktp_file'], $newname, $config);
				}
				if ($_FILES['npwp_file']['name'][0] !== "") {
					$newname   = array(str_replace("/", "-", $_POST['no_form']) . "-NPWP");
					$file_npwp = $this->general->upload_files($_FILES['npwp_file'], $newname, $config);
				}

				if ($_FILES['ranger_file']['name'][0] !== "") {
					$newname   = array(str_replace("/", "-", $_POST['no_form']) . "-Attachment-".str_replace(' ', '', $this->data['session_role'][0]->nama_role));
					$file_ranger = $this->general->upload_files($_FILES['ranger_file'], $newname, $config);
				}

				$provinsi = array();
				foreach ($_POST['provinsi'] as $v) {
					array_push($provinsi, $this->generate->kirana_decrypt($v));
				}
				$kab = array();
				foreach ($_POST['kabupaten'] as $v) {
					array_push($kab, $this->generate->kirana_decrypt($v));
				}

				// Define di awal, mempermudah lempar ke SAP
				$fee_non_tax   = (isset($_POST['fee_non_tax']) && trim($_POST['fee_non_tax']) !== "" ? str_replace(",", "", $_POST['fee_non_tax']) : NULL);
				$fee_tax_gross = (isset($_POST['fee_tax_gross']) && trim($_POST['fee_tax_gross']) !== "" ? str_replace(",", "", $_POST['fee_tax_gross']) : NULL);
				$fee_non_gross = (isset($_POST['fee_non_gross']) && trim($_POST['fee_non_gross']) !== "" ? str_replace(",", "", $_POST['fee_non_gross']) : NULL);

				if (isset($_POST['id_scoring']) && trim($_POST['id_scoring']) !== "") { //EDIT tbl_umb_scoring_header
					$id_scoring   = $this->generate->kirana_decrypt($_POST['id_scoring']);
					$data_scoring = array(
						"kode_supplier"      => (isset($_POST['supplier']) ? $this->generate->kirana_decrypt($_POST['supplier']) : NULL),
						"depo"               => ($_POST['depo'] !== "0" ? $this->generate->kirana_decrypt($_POST['depo']) : NULL),
						"dirops"             => (isset($_POST['dirops']) ? $this->generate->kirana_decrypt($_POST['dirops']) : NULL),
						"provinsi"           => implode(",", $provinsi),
						"kabupaten"          => implode(",", $kab),
						"jarak_tempuh"       => (isset($_POST['jarak_tempuh']) && trim($_POST['jarak_tempuh']) !== "" ? str_replace(",", "", $_POST['jarak_tempuh']) : NULL),
						"tanggal"            => (isset($_POST['tgl_pengajuan']) && trim($_POST['tgl_pengajuan']) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_pengajuan']) : NULL),
						"supply_start"       => (isset($_POST['supply_since']) && trim($_POST['supply_since']) !== "" ? $this->generate->regenerateDateFormat($_POST['supply_since']) : NULL),
						"lama_join"          => (isset($_POST['lama_join']) && trim($_POST['lama_join']) !== "" ? $_POST['lama_join'] : NULL),
						"um_minta"           => (isset($_POST['um_propose']) && trim($_POST['um_propose']) !== "" ? str_replace(",", "", $_POST['um_propose']) : NULL),
						"um_scoring"         => (isset($_POST['um_scoring_summary']) && trim($_POST['um_scoring_summary']) !== "" ? str_replace(",", "", $_POST['um_scoring_summary']) : NULL),
						"um_setuju"          => (isset($_POST['um_setuju_summary']) && trim($_POST['um_setuju_summary']) !== "" ? str_replace(",", "", $_POST['um_setuju_summary']) : NULL),
						"um_jamin"           => (isset($_POST['um_nilai_jaminan_summary']) && trim($_POST['um_nilai_jaminan_summary']) !== "" ? str_replace(",", "", $_POST['um_nilai_jaminan_summary']) : NULL),
						"um_rekom"           => (isset($_POST['um_rekom_summary']) && trim($_POST['um_rekom_summary']) !== "" ? str_replace(",", "", $_POST['um_rekom_summary']) : NULL),
						"waktu_selesai"      => (isset($_POST['waktu']) && trim($_POST['waktu']) !== "" ? $_POST['waktu'] : NULL),
						"max_um_outstanding" => (isset($_POST['max_um_outs']) && trim($_POST['max_um_outs']) !== "" ? str_replace(",", "", $_POST['max_um_outs']) : NULL),
						"fee_non_tax"        => $fee_non_tax,
						"fee_tax_gross"      => $fee_tax_gross,
						"fee_non_gross"      => $fee_non_gross,
						"point_of_purch"     => ($_POST['pop'] !== "0" ? $_POST['pop'] : NULL),
						"vendor_nonbkr"      => (isset($_POST['vendor_nonbkr']) ? $this->generate->kirana_decrypt($_POST['vendor_nonbkr']) : NULL),
						"file_ktp"           => (isset($file_ktp) ? $file_ktp[0]['url'] : NULL),
						"file_npwp"          => (isset($file_npwp) ? $file_npwp[0]['url'] : NULL),
						"file_ranger"        => (isset($file_ranger) ? $file_ranger[0]['url'] : NULL),
						"status"             => $status,
						"login_edit"         => base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit"       => $datetime
					);

					if (!isset($file_ktp))
						unset($data_scoring["file_ktp"]);
					if (!isset($file_npwp))
						unset($data_scoring["file_npwp"]);
					$data_scoring = $this->dgeneral->basic_column("update", $data_scoring);
					$this->dgeneral->update("tbl_umb_scoring_header", $data_scoring, array(
						array(
							'kolom' => 'no_form_scoring',
							'value' => $id_scoring
						)
					));
				}
				else { //INSERT tbl_umb_scoring_header
					$data_scoring = array(
						"plant"              => $_POST['pabrik'],
						"no_form_scoring"    => $_POST['no_form'],
						"id_scoring_tipe"    => $tipe_scoring,
						"kode_supplier"      => (isset($_POST['supplier']) ? $this->generate->kirana_decrypt($_POST['supplier']) : NULL),
						"depo"               => ($_POST['depo'] !== "0" ? $this->generate->kirana_decrypt($_POST['depo']) : NULL),
						"dirops"             => (isset($_POST['dirops']) ? $this->generate->kirana_decrypt($_POST['dirops']) : NULL),
						"provinsi"           => implode(",", $provinsi),
						"kabupaten"          => implode(",", $kab),
						"jarak_tempuh"       => (isset($_POST['jarak_tempuh']) && trim($_POST['jarak_tempuh']) !== "" ? str_replace(",", "", $_POST['jarak_tempuh']) : NULL),
						"tanggal"            => (isset($_POST['tgl_pengajuan']) && trim($_POST['tgl_pengajuan']) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_pengajuan']) : NULL),
						"supply_start"       => (isset($_POST['supply_since']) && trim($_POST['supply_since']) !== "" ? $this->generate->regenerateDateFormat($_POST['supply_since']) : NULL),
						"lama_join"          => (isset($_POST['lama_join']) && trim($_POST['lama_join']) !== "" ? $_POST['lama_join'] : NULL),
						"um_minta"           => (isset($_POST['um_propose']) && trim($_POST['um_propose']) !== "" ? str_replace(",", "", $_POST['um_propose']) : NULL),
						"um_scoring"         => (isset($_POST['um_scoring_summary']) && trim($_POST['um_scoring_summary']) !== "" ? str_replace(",", "", $_POST['um_scoring_summary']) : NULL),
						"um_setuju"          => (isset($_POST['um_setuju_summary']) && trim($_POST['um_setuju_summary']) !== "" ? str_replace(",", "", $_POST['um_setuju_summary']) : NULL),
						"um_jamin"           => (isset($_POST['um_nilai_jaminan_summary']) && trim($_POST['um_nilai_jaminan_summary']) !== "" ? str_replace(",", "", $_POST['um_nilai_jaminan_summary']) : NULL),
						"um_rekom"           => (isset($_POST['um_rekom_summary']) && trim($_POST['um_rekom_summary']) !== "" ? str_replace(",", "", $_POST['um_rekom_summary']) : NULL),
						"waktu_selesai"      => (isset($_POST['waktu']) && trim($_POST['waktu']) !== "" ? $_POST['waktu'] : NULL),
						"max_um_outstanding" => (isset($_POST['max_um_outs']) && trim($_POST['max_um_outs']) !== "" ? str_replace(",", "", $_POST['max_um_outs']) : NULL),
						"fee_non_tax"        => $fee_non_tax,
						"fee_tax_gross"      => $fee_tax_gross,
						"fee_non_gross"      => $fee_non_gross,
						"point_of_purch"     => ($_POST['pop'] !== "0" ? $_POST['pop'] : NULL),
						"point_of_purch"     => ($_POST['pop'] !== "0" ? $_POST['pop'] : NULL),
						"vendor_nonbkr"      => (isset($_POST['vendor_nonbkr']) ? $this->generate->kirana_decrypt($_POST['vendor_nonbkr']) : NULL),
						"file_ktp"           => (isset($file_ktp) ? $file_ktp[0]['url'] : NULL),
						"file_ranger"        => (isset($file_ranger) ? $file_ranger[0]['url'] : NULL),
						"file_npwp"          => (isset($file_npwp) ? $file_npwp[0]['url'] : NULL),
						"status"             => $this->generate_status_scoring($_POST['action'], $_POST['no_form']),
						"login_edit"         => base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit"       => $datetime
					);

					$data_scoring = $this->dgeneral->basic_column("insert_simple", $data_scoring);
					$this->dgeneral->insert("tbl_umb_scoring_header", $data_scoring);
				}

				//================================SAVE SCORING KRITERIA================================//
				if (isset($_POST['kriteria'])) {
					for ($i = 0; $i < count($_POST['kriteria']); $i++) {
						$kriteria      = $this->generate->kirana_decrypt($_POST['kriteria'][$i]);
						$check_exists  = $this->dscoringumb->get_data_scoring_kriteria(NULL, $_POST['no_form'], $kriteria);
						$data_kriteria = array(
							"plant"            => $_POST['pabrik'],
							"no_form_scoring"  => $_POST['no_form'],
							"id_scoring_tipe"  => $tipe_scoring,
							"id_umb_mkriteria" => $kriteria,
							"kriteria_desc"    => $_POST['kriteria_desc'][$i],
							"param"            => $_POST['param'][$i],
							"bobot"            => $_POST['bobot'][$i],
							"nilai"            => $_POST['nilai'][$i],
							"score"            => $_POST['score'][$i],
							"login_edit"       => base64_decode($this->session->userdata("-id_user-")),
							"tanggal_edit"     => $datetime
						);

						if ($check_exists) { //EDIT tbl_umb_scoring_kriteria
							unset($data_kriteria["plant"]);
							unset($data_kriteria["no_form_scoring"]);
							unset($data_kriteria["id_scoring_tipe"]);
							unset($data_kriteria["id_umb_mkriteria"]);
							unset($data_kriteria["kriteria_desc"]);
							$data_kriteria = $this->dgeneral->basic_column("update", $data_kriteria);
							$this->dgeneral->update("tbl_umb_scoring_kriteria", $data_kriteria, array(
								array(
									'kolom' => 'no_form_scoring',
									'value' => $id_scoring
								),
								array(
									'kolom' => 'id_umb_mkriteria',
									'value' => $kriteria
								),
								array(
									'kolom' => 'id_scoring_tipe',
									'value' => $tipe_scoring
								),
								array(
									'kolom' => 'active',
									'value' => 1
								)
							));
						}
						else { //INSERT tbl_umb_scoring_kriteria
							$data_kriteria = $this->dgeneral->basic_column("insert_simple", $data_kriteria);
							$this->dgeneral->insert("tbl_umb_scoring_kriteria", $data_kriteria);
							$id_scoring_kriteria = $this->db->insert_id();
						}
					}
				}

				//================================SAVE SCORING JAMINAN================================//
                if ($_POST['isJaminan'] == '1') {
                    //cek isjaminan
					if (isset($_POST['nama_penjamin'])) {
						//start jaminan header//
						$penjamin = $this->dscoringumb->get_data_scoring_jaminan_header(NULL, $_POST['no_form']);
						$penjamin = $this->general->generate_encrypt_json($penjamin, array("id_scoring_jaminan_header"));
						foreach ($penjamin as $p) {
							if ((!isset($_POST['id_scoring_penjamin'])) || (isset($_POST['id_scoring_penjamin']) && !in_array($p->id_scoring_jaminan_header, $_POST['id_scoring_penjamin']))) {
								//delete file unselected
								//$mask = realpath('./') . '/assets/file/umb/' . str_replace("/", "-", $_POST['no_form']) . "-dokumen-*-" . strtolower($p->nama) . "*.*";//'your_prefix_*.*';
								//array_map('unlink', glob($mask));

								//delete unselected penjamin
								$this->dgeneral->delete("tbl_umb_scoring_jamin_header", array(
									array(
										'kolom' => 'nama',
										'value' => $p->nama
									),
									array(
										'kolom' => 'no_form_scoring',
										'value' => $_POST['no_form']
									)
								));

								//delete unselected dokumen penjamin
								$this->dgeneral->delete("tbl_umb_scoring_jamin_dok", array(
									array(
										'kolom' => 'id_scoring_jaminan_header',
										'value' => $this->generate->kirana_decrypt($p->id_scoring_jaminan_header)
									)
								));
							}
						}

						
						// echo json_encode(count($_POST['nama_penjamin']));exit();
						for ($i = 0; $i < count($_POST['nama_penjamin']); $i++) {
							$umb_dokumen = $this->dmasterumb->get_master_dokumen(NULL, NULL, 'n', 'n', $_POST['status_penjamin'][$i], $_POST['kepemilikan'][$i]);
							if ($umb_dokumen) {
								$data_jaminan_header = array(
									"plant"           => $_POST['pabrik'],
									"no_form_scoring" => $_POST['no_form'],
									"nama"            => $_POST['nama_penjamin'][$i],
									// "kepemilikan_bdn" => $_POST['kepemilikan_badan'][$i],
									"id_umb_mdokumen" => $umb_dokumen[0]->id_mdokumen,
									"total_appraisal" => (isset($_POST['nilai_appraisal_penjamin'][$i]) && trim($_POST['nilai_appraisal_penjamin'][$i]) !== "" ? str_replace(",", "", $_POST['nilai_appraisal_penjamin'][$i]) : NULL),
									"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
									"tanggal_edit"    => $datetime
								);

								if (isset($_POST['id_scoring_penjamin'][$i])) { //edit tbl_umb_scoring_jamin_header
									unset($data_jaminan_header["plant"]);
									unset($data_jaminan_header["no_form_scoring"]);
									$id_scoring_jaminan_header = $this->generate->kirana_decrypt($_POST['id_scoring_penjamin'][$i]);
									$this->dgeneral->update("tbl_umb_scoring_jamin_header", $data_jaminan_header, array(
										array(
											'kolom' => 'id_scoring_jaminan_header',
											'value' => $id_scoring_jaminan_header
										)
									));
								}
								else { //new tbl_umb_scoring_jamin_header
									$data_jaminan_header = $this->dgeneral->basic_column("insert_simple", $data_jaminan_header);
									$this->dgeneral->insert("tbl_umb_scoring_jamin_header", $data_jaminan_header);
									$id_scoring_jaminan_header = $this->db->insert_id();
								}


								//start dokumen jaminan
								if (isset($_POST['jns_dok' . $i])) {
									$dok_penjamin = $this->dscoringumb->get_data_scoring_jaminan_dokumen(NULL, $id_scoring_jaminan_header);
									$dok_penjamin = $this->general->generate_encrypt_json($dok_penjamin, array("id_scoring_jaminan_dok"));
									
									foreach ($dok_penjamin as $p) {
										if ((!isset($_POST['id_dok' . $i])) || (isset($_POST['id_dok' . $i]) && !in_array($p->id_scoring_jaminan_dok, $_POST['id_dok' . $i]))) {
											//delete unselected dokumen penjamin
											$this->dgeneral->delete("tbl_umb_scoring_jamin_dok", array(
												array(
													'kolom' => 'id_scoring_jaminan_header',
													'value' => $id_scoring_jaminan_header
												),
												array(
													'kolom' => 'jns_dokumen',
													'value' => $p->jns_dokumen
												),
												array(
													'kolom' => 'id_scoring_jaminan_dok',
													'value' => $this->generate->kirana_decrypt($p->id_scoring_jaminan_dok)
												)
											));
										}
									}


									$newname        = array();
									$newname_id_dok = array();
									$arr_newname    = array();
									for ($j = 0; $j < count($_POST['jns_dok' . $i]); $j++) {
										if (isset($_FILES['file_dok' . $i]) && $_FILES['file_dok' . $i]['error'][$j] == 0 && $_FILES['file_dok' . $i]['name'][$j] !== "") {
											if (count($_FILES['file_dok' . $i]['name'][$j]) > 1) {
												$msg    = "You can only upload maximum 1 file";
												$sts    = "NotOK";
												$return = array('sts' => $sts, 'msg' => $msg);
												echo json_encode($return);
												exit();
											}
											array_push($newname, str_replace("/", "-", $_POST['no_form']) . "-dokumen-" . str_replace(" ", "", $_POST['jns_dok' . $i][$j]) . "-" . str_replace(" ", "", strtolower($_POST['nama_penjamin'][$i])));
											array_push($arr_newname, str_replace("/", "-", $_POST['no_form']) . "-dokumen-" . str_replace(" ", "", $_POST['jns_dok' . $i][$j]) . "-" . str_replace(" ", "", strtolower($_POST['nama_penjamin'][$i])));
											if (isset($_POST['id_dok' . $i]))
												array_push($newname_id_dok, $this->generate->kirana_decrypt($_POST['id_dok' . $i][$j]));
										}else{
											array_push($arr_newname, "");
										}
									}
									
									if (count($newname) > 0) {
										$file_dok = $this->general->upload_files($_FILES['file_dok' . $i], $arr_newname, $config);
										for ($j = 0; $j < count($newname); $j++) {
											$data_jaminan_dokumen = array(
												"id_scoring_jaminan_header" => $id_scoring_jaminan_header,
												"file_location"             => $file_dok[$j]['url'],
												"jns_dokumen"               => $_POST['jns_dok' . $i][$j],
												"login_edit"                => base64_decode($this->session->userdata("-id_user-")),
												"tanggal_edit"              => $datetime
											);
											if (count($newname_id_dok) > 0 && $newname_id_dok[$j] !== "") { //edit tbl_umb_scoring_jamin_dok
												unset($data_jaminan_dokumen['jns_dokumen']);
												$data_jaminan_dokumen = $this->dgeneral->basic_column("update", $data_jaminan_dokumen);
												$this->dgeneral->update("tbl_umb_scoring_jamin_dok", $data_jaminan_dokumen, array(
													array(
														'kolom' => 'id_scoring_jaminan_dok',
														'value' => $newname_id_dok[$j]
													)
												));
												$id_scoring_jaminan_dok = $newname_id_dok[$j];
											}
											else { //new tbl_umb_scoring_jamin_dok
												$data_jaminan_dokumen = $this->dgeneral->basic_column("insert_simple", $data_jaminan_dokumen);
												$this->dgeneral->insert("tbl_umb_scoring_jamin_dok", $data_jaminan_dokumen);
												$id_scoring_jaminan_dok = $this->db->insert_id();
											}
										}
									}									
								}

								//start detail jaminan
								if (isset($_POST['jenis_jaminan' . $i])) {
									$jaminan_detail = $this->dscoringumb->get_data_scoring_jaminan_detail(NULL, $id_scoring_jaminan_header);
									$jaminan_detail = $this->general->generate_encrypt_json($jaminan_detail, array("id_scoring_jaminan_detail"));
									foreach ($jaminan_detail as $p) {
										if ((!isset($_POST['id_detail_jaminan' . $i])) || (isset($_POST['id_detail_jaminan' . $i]) && !in_array($p->id_scoring_jaminan_detail, $_POST['id_detail_jaminan' . $i]))) {
											//delete unselected detail jaminan
											$this->dgeneral->delete("tbl_umb_scoring_jamin_detail", array(
												array(
													'kolom' => 'id_scoring_jaminan_header',
													'value' => $id_scoring_jaminan_header
												),
												array(
													'kolom' => 'no_form_scoring',
													'value' => $_POST['no_form']
												),
												array(
													'kolom' => 'id_scoring_jaminan_detail',
													'value' => $this->generate->kirana_decrypt($p->id_scoring_jaminan_detail)
												)
											));
										}
									}

									$newname_dokumen_appraisal = array();
									for ($j = 0; $j < count($_POST['jenis_jaminan' . $i]); $j++) {
										if (isset($_FILES['dokumen_appraisal' . $i]) && $_FILES['dokumen_appraisal' . $i]['error'][$j] == 0 && $_FILES['dokumen_appraisal' . $i]['name'][$j] !== "") {
											if (count($_FILES['dokumen_appraisal' . $i]['name'][$j]) > 1) {
												$msg    = "You can only upload maximum 1 file";
												$sts    = "NotOK";
												$return = array('sts' => $sts, 'msg' => $msg);
												echo json_encode($return);
												exit();
											}
											array_push($newname_dokumen_appraisal, str_replace("/", "-", $_POST['no_form']) . "-dokumen-jaminan-" . str_replace(" ", "", strtolower($_POST['nama_penjamin'][$i])) . ($j + 1));
										}
										else {
											array_push($newname_dokumen_appraisal, "");
										}
									}

									for ($j = 0; $j < count($_POST['jenis_jaminan' . $i]); $j++) {
										
										$data_jaminan_detail = array(
											"id_scoring_jaminan_header" => $id_scoring_jaminan_header,
											"plant"                     => $_POST['pabrik'],
											"no_form_scoring"           => $_POST['no_form'],
											"id_mjaminan_header"        => $this->generate->kirana_decrypt($_POST['jenis_jaminan' . $i][$j]),
											"id_mjaminan_detail"        => $this->generate->kirana_decrypt($_POST['detail_jaminan' . $i][$j]),
											"nilai_jaminan"             => (isset($_POST['nilai_jaminan' . $i][$j]) && trim($_POST['nilai_jaminan' . $i][$j]) !== "" ? str_replace(",", "", $_POST['nilai_jaminan' . $i][$j]) : NULL),
											"nilai_appraisal"           => (isset($_POST['nilai_appraisal' . $i][$j]) && trim($_POST['nilai_appraisal' . $i][$j]) !== "" ? str_replace(",", "", $_POST['nilai_appraisal' . $i][$j]) : NULL),
											"desc"                      => (isset($_POST['desc_jaminan' . $i][$j]) && trim($_POST['desc_jaminan' . $i][$j]) !== "" ? $_POST['desc_jaminan' . $i][$j] : NULL),
											"nama"                      => (isset($_POST['nama' . $i][$j]) && trim($_POST['nama' . $i][$j]) !== "" ? $_POST['nama' . $i][$j] : NULL),
											"hasil_appraisal"           => (isset($_POST['hasil_appraisal' . $i][$j]) && trim($_POST['hasil_appraisal' . $i][$j]) !== "" ? str_replace(",", "", $_POST['hasil_appraisal' . $i][$j]) : NULL),
											"login_edit"                => base64_decode($this->session->userdata("-id_user-")),
											"tanggal_edit"              => $datetime
										);

										//find new index of success file
										$idx_new       = 0;
										$data_idx_file = array();
										foreach ($_FILES['dokumen_appraisal' . $i]["error"] as $key => $f) {
											if ($f == 0) {
												$data_idx_file[$i][$key] = $idx_new;
												$idx_new++;
											}
										}

										$file_dok_appraisal = array();
										if (isset($_FILES['dokumen_appraisal' . $i]) && $_FILES['dokumen_appraisal' . $i]['error'][$j] == 0) {
											$file_dok_appraisal = $this->general->upload_files($_FILES['dokumen_appraisal' . $i], $newname_dokumen_appraisal, $config);
											//get new index of success file
											$idx_file                                = $data_idx_file[$i][$j];
											$data_jaminan_detail["dokumen_location"] = $file_dok_appraisal[$idx_file]['url'];

											if (!isset($_POST['id_detail_jaminan' . $i][$j])) { //new tbl_umb_scoring_jamin_detail
												$data_jaminan_detail = $this->dgeneral->basic_column("insert_simple", $data_jaminan_detail);
												$this->dgeneral->insert("tbl_umb_scoring_jamin_detail", $data_jaminan_detail);
												$id_scoring_jaminan_detail = $this->db->insert_id();
											}
										}

										if (isset($_POST['id_detail_jaminan' . $i][$j])) { //edit tbl_umb_scoring_jamin_detail
											$id_scoring_jaminan_detail = $this->generate->kirana_decrypt($_POST['id_detail_jaminan' . $i][$j]);
											$data_jaminan_detail       = $this->dgeneral->basic_column("update", $data_jaminan_detail);
											$this->dgeneral->update("tbl_umb_scoring_jamin_detail", $data_jaminan_detail, array(
												array(
													'kolom' => 'id_scoring_jaminan_header',
													'value' => $id_scoring_jaminan_header
												),
												array(
													'kolom' => 'no_form_scoring',
													'value' => $_POST['no_form']
												),
												array(
													'kolom' => 'id_scoring_jaminan_detail',
													'value' => $id_scoring_jaminan_detail
												)
											));
										}


										
									}
								} //
								
							}else{
								$msg    = "Data Status Penjamin dan Kepemilikan tidak dapat ditemukan";
								$sts    = "NotOK";
								$return = array('sts' => $sts, 'msg' => $msg);
								echo json_encode($return);
								exit();
							}
						}

					}
                }else {
                    $check_jaminan_header = $this->dscoringumb->get_data_scoring_jaminan_header(NULL, $_POST['no_form']);
                    if ($check_jaminan_header) {
                        $this->dgeneral->delete("tbl_umb_scoring_jamin_header", array(
                            array(
                                'kolom' => 'no_form_scoring',
                                'value' => $_POST['no_form']
                            )
                        ));
                    }

                    $check_jaminan_detail = $this->dscoringumb->get_data_scoring_jaminan_detail(NULL, NULL, $_POST['no_form']);
                    if ($check_jaminan_detail) {
                        $this->dgeneral->delete("tbl_umb_scoring_jamin_detail", array(
                            array(
                                'kolom' => 'no_form_scoring',
                                'value' => $_POST['no_form']
                            )
                        ));
                    }
				}	

				$data_log = array(
					"no_form_scoring" => $_POST['no_form'],
					"tgl_status"      => $datetime,
					"action"          => $_POST['action'],
					"status"          => $this->data['session_role'][0]->level,
					"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"    => $datetime,
					"comment"         => ""
				);
				$this->dgeneral->insert("tbl_umb_scoring_log_status", $data_log);

				//================================SAVE ALL================================//

				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukan";
					$sts = "NotOK";
				}
				else {
					$this->dgeneral->commit_transaction();
					$nilai = isset($_POST['um_setuju_summary']) && $_POST['um_setuju_summary'] > 0 ? str_replace(",", "", $_POST['um_setuju_summary']) : str_replace(",", "", $_POST['um_propose']);
					$this->generate_message_email($_POST['no_form'], $_POST['action'], $nilai);
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}
			}
			else {
				$msg = "Periksa kembali data yang dimasukan";
				$sts = "NotOK";
			}
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}

		private function save_penilaian_jaminan() {
			$datetime = date("Y-m-d H:i:s");

			if (isset($_POST['rowjaminan']) && isset($_POST['rowdetail']) && isset($_POST['id_scoring_jaminan_detail']) && isset($_POST['id_scoring_jaminan_header'])) {
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				$i                         = $_POST['rowjaminan'];
				$j                         = $_POST['rowdetail'];
				$id_scoring_jaminan_detail = $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_detail']);
				$id_scoring_jaminan_header = $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_header']);
				$nama_penjamin             = $this->dscoringumb->get_nama_penjamin(NULL, $id_scoring_jaminan_header)->nama;
				$config['upload_path']     = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types']   = 'jpg|jpeg|png|pdf';

				// if (count($_FILES['foto_nilai_aset_jaminan' . $i . '_' . $j]['name']) > 4) {
				// 	$msg    = "You can only upload maximum 4 file";
				// 	$sts    = "NotOK";
				// 	$return = array('sts' => $sts, 'msg' => $msg);
				// 	echo json_encode($return);
				// 	exit();
				// }
				// $newname_foto_aset = array();
				// for ($x = 0; $x < 4; $x++) {
				// 	array_push($newname_foto_aset, str_replace("/", "-", $_POST['no_form_scoring']) . "-foto-aset-jaminan-" . str_replace(" ", "", strtolower($nama_penjamin)) . ($j + 1) . '-' . ($i + 1) . '-' . $x);
				// }
				// $file_aset_jaminan = $this->general->upload_files($_FILES['foto_nilai_aset_jaminan' . $i . '_' . $j], $newname_foto_aset, $config);

				$data_nilai_aset_jaminan = array(
					"id_scoring_jaminan_detail" => $id_scoring_jaminan_detail,
					"id_scoring_jaminan_header" => $id_scoring_jaminan_header,
					"plant"                     => $_POST['pabriks'],
					"no_form_scoring"           => $_POST['no_form_scoring'],
					"alamat"                    => (isset($_POST['alamat_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['alamat_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? $_POST['alamat_nilai_aset_jaminan' . $i . '_' . $j] : NULL),
					"luas_tanah"                => (isset($_POST['lt_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['lt_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? str_replace(",", "", $_POST['lt_nilai_aset_jaminan' . $i . '_' . $j]) : NULL),
					"luas_bangunan"             => (isset($_POST['lb_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['lb_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? str_replace(",", "", $_POST['lb_nilai_aset_jaminan' . $i . '_' . $j]) : NULL),
					"jenis_sertifikat"          => (isset($_POST['jns_sertifikat_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['jns_sertifikat_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? $_POST['jns_sertifikat_nilai_aset_jaminan' . $i . '_' . $j] : NULL),
					"no_sertifikat"             => (isset($_POST['no_sert_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['no_sert_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? $_POST['no_sert_nilai_aset_jaminan' . $i . '_' . $j] : NULL),
					"tgl_terbit"                => (isset($_POST['tgl_terbit_sertifikat_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['tgl_terbit_sertifikat_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_terbit_sertifikat_nilai_aset_jaminan' . $i . '_' . $j]) : NULL),
					"tgl_akhir"                 => (isset($_POST['tgl_akhir_sertifikat_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['tgl_akhir_sertifikat_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_akhir_sertifikat_nilai_aset_jaminan' . $i . '_' . $j]) : NULL),
					"tgl_gambar_situasi"        => (isset($_POST['tgl_situasi_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['tgl_situasi_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_situasi_nilai_aset_jaminan' . $i . '_' . $j]) : NULL),
					"no"                        => (isset($_POST['no_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['no_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? str_replace(",", "", $_POST['no_nilai_aset_jaminan' . $i . '_' . $j]) : NULL),
					"merk"                      => (isset($_POST['merk_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['merk_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? $_POST['merk_nilai_aset_jaminan' . $i . '_' . $j] : NULL),
					"type"                      => (isset($_POST['type_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['type_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? $_POST['type_nilai_aset_jaminan' . $i . '_' . $j] : NULL),
					"tahun"                     => (isset($_POST['thn_buat_nilai_aset_jaminan' . $i . '_' . $j]) && trim($_POST['thn_buat_nilai_aset_jaminan' . $i . '_' . $j]) !== "" ? $_POST['thn_buat_nilai_aset_jaminan' . $i . '_' . $j] : NULL),
					// "img1"                      => (isset($file_aset_jaminan1[0]['url'])) ? $file_aset_jaminan1[0]['url'] : NULL,
					// "img2"                      => (isset($file_aset_jaminan2[1]['url'])) ? $file_aset_jaminan2[1]['url'] : NULL,
					// "img3"                      => (isset($file_aset_jaminan3[2]['url'])) ? $file_aset_jaminan3[2]['url'] : NULL,
					// "img4"                      => (isset($file_aset_jaminan4[3]['url'])) ? $file_aset_jaminan4[3]['url'] : NULL,
					"login_edit"                => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"              => $datetime,
					"login_buat"                => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_buat"              => $datetime,
					"active"                    => '1'
				);

				
				if ($_FILES['foto_nilai_aset_jaminan_sisi1_' . $i . '_' . $j]['name'][0] !== "") {
					$newname1   = array(str_replace("/", "-", $_POST['no_form_scoring']) . "-foto-aset-jaminan-" . str_replace(" ", "", strtolower($nama_penjamin)) . ($j + 1) . '-' . ($i + 1) . '-0');
					$file_aset_jaminan1 = $this->general->upload_files($_FILES['foto_nilai_aset_jaminan_sisi1_' . $i . '_' . $j], $newname1, $config);
					$data_nilai_aset_jaminan['img1'] = (isset($file_aset_jaminan1[0]['url'])) ? $file_aset_jaminan1[0]['url'] : NULL;
				}

				if ($_FILES['foto_nilai_aset_jaminan_sisi2_' . $i . '_' . $j]['name'][0] !== "") {
					$newname2   = array(str_replace("/", "-", $_POST['no_form_scoring']) . "-foto-aset-jaminan-" . str_replace(" ", "", strtolower($nama_penjamin)) . ($j + 1) . '-' . ($i + 1) . '-1');
					$file_aset_jaminan2 = $this->general->upload_files($_FILES['foto_nilai_aset_jaminan_sisi2_' . $i . '_' . $j], $newname2, $config);
					$data_nilai_aset_jaminan['img2'] = (isset($file_aset_jaminan2[0]['url'])) ? $file_aset_jaminan2[0]['url'] : NULL;
				}

				if ($_FILES['foto_nilai_aset_jaminan_sisi3_' . $i . '_' . $j]['name'][0] !== "") {
					$newname3   = array(str_replace("/", "-", $_POST['no_form_scoring']) . "-foto-aset-jaminan-" . str_replace(" ", "", strtolower($nama_penjamin)) . ($j + 1) . '-' . ($i + 1) . '-2');
					$file_aset_jaminan3 = $this->general->upload_files($_FILES['foto_nilai_aset_jaminan_sisi3_' . $i . '_' . $j], $newname3, $config);
					$data_nilai_aset_jaminan['img3'] = (isset($file_aset_jaminan3[0]['url'])) ? $file_aset_jaminan3[0]['url'] : NULL;
				}

				if ($_FILES['foto_nilai_aset_jaminan_sisi4_' . $i . '_' . $j]['name'][0] !== "") {
					$newname4   = array(str_replace("/", "-", $_POST['no_form_scoring']) . "-foto-aset-jaminan-" . str_replace(" ", "", strtolower($nama_penjamin)) . ($j + 1) . '-' . ($i + 1) . '-3');
					$file_aset_jaminan4 = $this->general->upload_files($_FILES['foto_nilai_aset_jaminan_sisi4_' . $i . '_' . $j], $newname4, $config);
					$data_nilai_aset_jaminan['img4'] = (isset($file_aset_jaminan4[0]['url'])) ? $file_aset_jaminan4[0]['url'] : NULL;
				}

				if (isset($_POST['id_scoring_jaminan_nilai']) && $_POST['id_scoring_jaminan_nilai'] !== "") {
					//UPDATE
					$id_scoring_jaminan_nilai = $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_nilai']);
					unset($data_nilai_aset_jaminan['login_buat']);
					unset($data_nilai_aset_jaminan['tanggal_buat']);
					// unset($data_nilai_aset_jaminan['img1']);
					// unset($data_nilai_aset_jaminan['img2']);
					// unset($data_nilai_aset_jaminan['img3']);
					// unset($data_nilai_aset_jaminan['img4']);

					$this->dgeneral->update('tbl_umb_scoring_jamin_nilai', $data_nilai_aset_jaminan, array(
						array(
							'kolom' => 'id_scoring_jaminan_nilai',
							'value' => $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_nilai'])
						)
					));
				}
				else {
					//INSERT
					$this->dgeneral->insert("tbl_umb_scoring_jamin_nilai", $data_nilai_aset_jaminan);
					$id_scoring_jaminan_nilai = $this->db->insert_id();

				}

				//start methode penilaian aset
				if (isset($_POST['isWawancara' . $i . '_' . $j]) && $_POST['isWawancara' . $i . '_' . $j] == "on") {
					if (isset($_POST['tgl_penilaian_jaminan_wawancara' . $i . '_' . $j])) {
						for ($x = 0; $x < count($_POST['tgl_penilaian_jaminan_wawancara' . $i . '_' . $j]); $x++) {
							$data_jaminan_metode_wawancara = array(
								"id_scoring_jaminan_nilai" => $id_scoring_jaminan_nilai,
								"tipe"                     => "wawancara",
								"tgl_nilai"                => (isset($_POST['tgl_penilaian_jaminan_wawancara' . $i . '_' . $j][$x]) && trim($_POST['tgl_penilaian_jaminan_wawancara' . $i . '_' . $j][$x]) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_penilaian_jaminan_wawancara' . $i . '_' . $j][$x]) : NULL),
								"sumber_info"              => (isset($_POST['sumber_info_jaminan_wawancara' . $i . '_' . $j][$x]) && trim($_POST['sumber_info_jaminan_wawancara' . $i . '_' . $j][$x]) !== "" ? $_POST['sumber_info_jaminan_wawancara' . $i . '_' . $j][$x] : NULL),
								"jenis_aset"               => (isset($_POST['jns_aset_jaminan_wawancara' . $i . '_' . $j][$x]) && trim($_POST['jns_aset_jaminan_wawancara' . $i . '_' . $j][$x]) !== "" ? $_POST['jns_aset_jaminan_wawancara' . $i . '_' . $j][$x] : NULL),
								"spek_aset"                => (isset($_POST['spek_aset_jaminan_wawancara' . $i . '_' . $j][$x]) && trim($_POST['spek_aset_jaminan_wawancara' . $i . '_' . $j][$x]) !== "" ? $_POST['spek_aset_jaminan_wawancara' . $i . '_' . $j][$x] : NULL),
								"alamat"                   => (isset($_POST['lokasi_jaminan_wawancara' . $i . '_' . $j][$x]) && trim($_POST['lokasi_jaminan_wawancara' . $i . '_' . $j][$x]) !== "" ? $_POST['lokasi_jaminan_wawancara' . $i . '_' . $j][$x] : NULL),
								"tgl_transaksi"            => (isset($_POST['tgl_trans_jaminan_wawancara' . $i . '_' . $j][$x]) && trim($_POST['tgl_trans_jaminan_wawancara' . $i . '_' . $j][$x]) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_trans_jaminan_wawancara' . $i . '_' . $j][$x]) : NULL),
								"harga_transaksi"          => (isset($_POST['harga_trans_jaminan_wawancara' . $i . '_' . $j][$x]) && trim($_POST['harga_trans_jaminan_wawancara' . $i . '_' . $j][$x]) !== "" ? str_replace(",", "", $_POST['harga_trans_jaminan_wawancara' . $i . '_' . $j][$x]) : NULL),
								"harga_per_m"              => (isset($_POST['harga_trans_m2_jaminan_wawancara' . $i . '_' . $j][$x]) && trim($_POST['harga_trans_m2_jaminan_wawancara' . $i . '_' . $j][$x]) !== "" ? str_replace(",", "", $_POST['harga_trans_m2_jaminan_wawancara' . $i . '_' . $j][$x]) : NULL),
								"login_edit"               => base64_decode($this->session->userdata("-id_user-")),
								"tanggal_edit"             => $datetime,
								"login_buat"               => base64_decode($this->session->userdata("-id_user-")),
								"tanggal_buat"             => $datetime,
								"active"                   => '1'
							);

							if (isset($_POST['id_scoring_jaminan_metode_wawancara' . $i . '_' . $j][$x]) && $_POST['id_scoring_jaminan_metode_wawancara' . $i . '_' . $j][$x] !== "") {
								//UPDATE
								unset($data_jaminan_metode_wawancara['login_buat']);
								unset($data_jaminan_metode_wawancara['tanggal_buat']);
								unset($data_jaminan_metode_wawancara['id_scoring_jaminan_nilai']);

								$this->dgeneral->update('tbl_umb_scoring_jamin_metode', $data_jaminan_metode_wawancara, array(
									array(
										'kolom' => 'id_scoring_jaminan_metode',
										'value' => $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_metode_wawancara' . $i . '_' . $j][$x])
									)
								));
							}
							else {
								//INSERT
								$this->dgeneral->insert("tbl_umb_scoring_jamin_metode", $data_jaminan_metode_wawancara);
								$id_scoring_jaminan_metode = $this->db->insert_id();
							}

						}
					}
				}
				else {
					// CEK apakah exist namun dihapus ketika edit
					$jaminan_metode = $this->dscoringumb->get_data_jaminan_detail_metode(NULL, $id_scoring_jaminan_nilai, "wawancara");

					if ($jaminan_metode) {
						$data_rows = array(
							'active'       => '0',
							'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit' => $datetime
						);
						foreach ($jaminan_metode as $jm) {
							$this->dgeneral->update('tbl_umb_scoring_jamin_metode', $data_rows, array(
								array(
									'kolom' => 'id_scoring_jaminan_metode',
									'value' => $jm->id_scoring_jaminan_metode
								)
							));
						}
					}
				}

				if (isset($_POST['isAnalisaDesktop' . $i . '_' . $j]) && $_POST['isAnalisaDesktop' . $i . '_' . $j] == "on") {
					$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					// if (count($_FILES['file_pendukung_analisa' . $i . '_' . $j]['name']) > 3) {
					// 	$msg    = "You can only upload maximum 3 file";
					// 	$sts    = "NotOK";
					// 	$return = array('sts' => $sts, 'msg' => $msg);
					// 	echo json_encode($return);
					// 	exit();
					// }
					// $newname_file_pendukung = array();
					// for ($x = 0; $x < 3; $x++) {
					// 	array_push($newname_file_pendukung, str_replace("/", "-", $_POST['no_form_scoring']) . "-file-pendukung-analisa-" . ($j + 1) . '-' . ($i + 1) . '-' . $x);
					// }
					// $file_pendukung_analisa = $this->general->upload_files($_FILES['file_pendukung_analisa' . $i . '_' . $j], $newname_file_pendukung, $config);
					if (isset($_POST['tgl_penilaian_jaminan_analisa' . $i . '_' . $j])) {
						for ($x = 0; $x < count($_POST['tgl_penilaian_jaminan_analisa' . $i . '_' . $j]); $x++) {
							$data_jaminan_metode_analisa = array(
								"id_scoring_jaminan_nilai" => $id_scoring_jaminan_nilai,
								"tipe"                     => "analisa",
								"tgl_nilai"                => (isset($_POST['tgl_penilaian_jaminan_analisa' . $i . '_' . $j][$x]) && trim($_POST['tgl_penilaian_jaminan_analisa' . $i . '_' . $j][$x]) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_penilaian_jaminan_analisa' . $i . '_' . $j][$x]) : NULL),
								"sumber_info"              => (isset($_POST['sumber_info_jaminan_analisa' . $i . '_' . $j][$x]) && trim($_POST['sumber_info_jaminan_analisa' . $i . '_' . $j][$x]) !== "" ? $_POST['sumber_info_jaminan_analisa' . $i . '_' . $j][$x] : NULL),
								// "file_pendukung"           => (isset($file_pendukung_analisa[$x]['url']) ? $file_pendukung_analisa[$x]['url'] : NULL),
								"jenis_aset"               => (isset($_POST['jns_aset_jaminan_analisa' . $i . '_' . $j][$x]) && trim($_POST['jns_aset_jaminan_analisa' . $i . '_' . $j][$x]) !== "" ? $_POST['jns_aset_jaminan_analisa' . $i . '_' . $j][$x] : NULL),
								"spek_aset"                => (isset($_POST['spek_aset_jaminan_analisa' . $i . '_' . $j][$x]) && trim($_POST['spek_aset_jaminan_analisa' . $i . '_' . $j][$x]) !== "" ? $_POST['spek_aset_jaminan_analisa' . $i . '_' . $j][$x] : NULL),
								"alamat"                   => (isset($_POST['lokasi_jaminan_analisa' . $i . '_' . $j][$x]) && trim($_POST['lokasi_jaminan_analisa' . $i . '_' . $j][$x]) !== "" ? $_POST['lokasi_jaminan_analisa' . $i . '_' . $j][$x] : NULL),
								"tgl_transaksi"            => (isset($_POST['tgl_trans_jaminan_analisa' . $i . '_' . $j][$x]) && trim($_POST['tgl_trans_jaminan_analisa' . $i . '_' . $j][$x]) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_trans_jaminan_analisa' . $i . '_' . $j][$x]) : NULL),
								"harga_transaksi"          => (isset($_POST['harga_trans_jaminan_analisa' . $i . '_' . $j][$x]) && trim($_POST['harga_trans_jaminan_analisa' . $i . '_' . $j][$x]) !== "" ? str_replace(",", "", $_POST['harga_trans_jaminan_analisa' . $i . '_' . $j][$x]) : NULL),
								"harga_per_m"              => (isset($_POST['harga_trans_m2_jaminan_analisa' . $i . '_' . $j][$x]) && trim($_POST['harga_trans_m2_jaminan_analisa' . $i . '_' . $j][$x]) !== "" ? str_replace(",", "", $_POST['harga_trans_m2_jaminan_analisa' . $i . '_' . $j][$x]) : NULL),
								"login_edit"               => base64_decode($this->session->userdata("-id_user-")),
								"tanggal_edit"             => $datetime,
								"login_buat"               => base64_decode($this->session->userdata("-id_user-")),
								"tanggal_buat"             => $datetime,
								"active"                   => '1'
							);

							if ($_FILES['file_pendukung_analisa' . $x.'_'.$i . '_' . $j]['name'][0] !== "") {
								$newname   = array(str_replace("/", "-", $_POST['no_form_scoring']) . "-file-pendukung-analisa-" . ($j + 1) . '-' . ($i + 1) . '-' . $x);
								$file_pendukung_analisa = $this->general->upload_files($_FILES['file_pendukung_analisa' . $x.'_'. $i . '_' . $j], $newname, $config);
								$data_jaminan_metode_analisa['file_pendukung'] = (isset($file_pendukung_analisa[0]['url'])) ? $file_pendukung_analisa[0]['url'] : NULL;
							}

							if (isset($_POST['id_scoring_jaminan_metode_analisa' . $i . '_' . $j][$x]) && $_POST['id_scoring_jaminan_metode_analisa' . $i . '_' . $j][$x] !== "") {
								//UPDATE
								unset($data_jaminan_metode_analisa['login_buat']);
								unset($data_jaminan_metode_analisa['tanggal_buat']);
								unset($data_jaminan_metode_analisa['active']);
								unset($data_jaminan_metode_analisa['id_scoring_jaminan_nilai']);
								// echo json_encode($file_pendukung_analisa[$x]['url']);

								// if (!isset($file_pendukung_analisa[$x]['url'])) {
								// 	unset($data_jaminan_metode_analisa['file_pendukung']);
								// }

								$this->dgeneral->update('tbl_umb_scoring_jamin_metode', $data_jaminan_metode_analisa, array(
									array(
										'kolom' => 'id_scoring_jaminan_metode',
										'value' => $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_metode_analisa' . $i . '_' . $j][$x])
									)
								));
							}
							else {
								//INSERT
								$this->dgeneral->insert("tbl_umb_scoring_jamin_metode", $data_jaminan_metode_analisa);
								$id_scoring_jaminan_metode = $this->db->insert_id();
							}
						}
					}
				}
				else {
					// CEK apakah exist namun dihapus ketika edit
					$jaminan_metode = $this->dscoringumb->get_data_jaminan_detail_metode(NULL, $id_scoring_jaminan_nilai, "analisa");

					if ($jaminan_metode) {
						$data_rows = array(
							'active'       => '0',
							'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit' => $datetime
						);
						foreach ($jaminan_metode as $jm) {
							$this->dgeneral->update('tbl_umb_scoring_jamin_metode', $data_rows, array(
								array(
									'kolom' => 'id_scoring_jaminan_metode',
									'value' => $jm->id_scoring_jaminan_metode
								)
							));
						}
					}
				}

				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukan";
					$sts = "NotOK";
				}
				else {
					// $this->dgeneral->rollback_transaction();
					$this->dgeneral->commit_transaction();
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}

			}
			else {
				$msg = "Periksa kembali data yang dimasukan";
				$sts = "NotOK";
			}


			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}


		// ================ TIDAK DIPAKE, GABUNG KE REVISI APPRAISAL =====================
		// private function save_penilaian_appraisal() {
		// 	$datetime = date("Y-m-d H:i:s");

		// 	if (isset($_POST['id_scoring_jaminan_detail']) && isset($_POST['hasil_appraisal'])) {
		// 		$this->general->connectDbPortal();
		// 		$this->dgeneral->begin_transaction();

		// 		$data_row = array(
		// 			'hasil_appraisal' => $_POST['hasil_appraisal'],
		// 			'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
		// 			'tanggal_edit'    => $datetime
		// 		);
		// 		$this->dgeneral->update('tbl_umb_scoring_jamin_detail', $data_row, array(
		// 			array(
		// 				'kolom' => 'id_scoring_jaminan_detail',
		// 				'value' => $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_detail'])
		// 			)
		// 		));
		// 		//UPDATE KALKULASI APPRAISAL
		// 		$data_detail = $this->dscoringumb->get_data_scoring_jaminan_detail(NULL, $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_header']));

		// 		$total_appraisal = 0;
		// 		$no_form_scoring = "";
		// 		foreach ($data_detail as $dt) {
		// 			if ($dt->id_scoring_jaminan_detail == $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_detail'])) {
		// 				$appraisal = $_POST['hasil_appraisal'];
		// 			}
		// 			else {
		// 				$appraisal = $dt->hasil_appraisal !== "" ? $dt->hasil_appraisal : $dt->nilai_appraisal;
		// 			}
		// 			$total_appraisal += $appraisal;
		// 			$no_form_scoring = $dt->no_form_scoring;
		// 		}

		// 		$total_appraisal = number_format($total_appraisal, 2, '.', '');
		// 		// echo json_encode($total_appraisal);exit();

		// 		$data_row2 = array(
		// 			'total_appraisal' => $total_appraisal,
		// 			'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
		// 			'tanggal_edit'    => $datetime
		// 		);
		// 		$this->dgeneral->update('tbl_umb_scoring_jamin_header', $data_row2, array(
		// 			array(
		// 				'kolom' => 'id_scoring_jaminan_header',
		// 				'value' => $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_header'])
		// 			)
		// 		));

		// 		$data_header  = $this->dscoringumb->get_data_scoring_jaminan_header(NULL, $no_form_scoring);
		// 		$data_summary = $this->dscoringumb->get_data_summary_scoring(NULL, $no_form_scoring);
		// 		$um_jamin     = 0;
		// 		foreach ($data_header as $dta) {
		// 			if ($dta->id_scoring_jaminan_header == $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_header'])) {
		// 				$um_jaminan = $total_appraisal;
		// 			}
		// 			else {
		// 				$um_jaminan = $dta->total_appraisal;
		// 			}
		// 			$um_jamin += $um_jaminan;
		// 		}
		// 		$um_jamin  = number_format($um_jamin, 2, '.', '');
		// 		$um_setuju = min($um_jamin, $data_summary->um_scoring, $data_summary->um_minta);

		// 		$data_row3 = array(
		// 			'um_jamin'     => $um_jamin,
		// 			'um_setuju'    => $um_setuju,
		// 			'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
		// 			'tanggal_edit' => $datetime
		// 		);

		// 		$this->dgeneral->update('tbl_umb_scoring_header', $data_row3, array(
		// 			array(
		// 				'kolom' => 'no_form_scoring',
		// 				'value' => $no_form_scoring
		// 			)
		// 		));


		// 		if ($this->dgeneral->status_transaction() === false) {
		// 			$this->dgeneral->rollback_transaction();
		// 			$msg = "Periksa kembali data yang dimasukan";
		// 			$sts = "NotOK";
		// 		}
		// 		else {
		// 			$this->dgeneral->commit_transaction();
		// 			$msg = "Data berhasil ditambahkan";
		// 			$sts = "OK";
		// 		}

		// 	}
		// 	else {
		// 		$msg = "Gagal melakukan perubahan.";
		// 		$sts = "NotOK";
		// 	}

		// 	$return = array('sts'             => $sts,
		// 					'msg'             => $msg,
		// 					'total_appraisal' => $total_appraisal,
		// 					'um_jaminan'      => $um_jamin,
		// 					'um_setuju'       => $um_setuju
		// 	);
		// 	echo json_encode($return);

		// }

		private function save_revisi_appraisal() {
			$datetime = date("Y-m-d H:i:s");

			if (isset($_POST['id_scoring_jaminan_detail']) && isset($_POST['value_revisi'])) {
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|jpeg|png|pdf';
				
				//01-04-2020 cr attact fincon
				if ($_FILES['attach_revised']['name'][0] !== "") {
					$newname                 = array("file_revisi_appraisal_" . $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_detail']));
					$file_npwp = $this->general->upload_files($_FILES['attach_revised'], $newname, $config);
					$file_revisi_appraisal   = $this->general->upload_files($_FILES['attach_revised'], $newname, $config);
				}
				
				$file_attachment = isset($file_revisi_appraisal['0']['url']) ? $file_revisi_appraisal['0']['url'] : "";
				$data_row = array(
					'hasil_appraisal' => str_replace(',', '', $_POST['value_revisi']),
					'file_attachment' => $file_attachment,
					'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'    => $datetime
				);
				$this->dgeneral->update('tbl_umb_scoring_jamin_detail', $data_row, array(
					array(
						'kolom' => 'id_scoring_jaminan_detail',
						'value' => $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_detail'])
					)
				));


				//UPDATE KALKULASI APPRAISAL
				$data_detail = $this->dscoringumb->get_data_scoring_jaminan_detail(NULL, $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_header']));

				$total_appraisal = 0;
				$no_form_scoring = "";
				foreach ($data_detail as $dt) {
					if ($dt->id_scoring_jaminan_detail == $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_detail'])) {
						$appraisal = str_replace(',', '', $_POST['value_revisi']);
					}
					else {
						$appraisal = $dt->hasil_appraisal !== "" ? $dt->hasil_appraisal : $dt->nilai_appraisal;
					}
					$total_appraisal += $appraisal;
					$no_form_scoring = $dt->no_form_scoring;
				}

				$total_appraisal = number_format($total_appraisal, 2, '.', '');
				// echo json_encode($total_appraisal);exit();

				$data_row2 = array(
					'total_appraisal' => $total_appraisal,
					'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'    => $datetime
				);
				$this->dgeneral->update('tbl_umb_scoring_jamin_header', $data_row2, array(
					array(
						'kolom' => 'id_scoring_jaminan_header',
						'value' => $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_header'])
					)
				));

				$data_header  = $this->dscoringumb->get_data_scoring_jaminan_header(NULL, $no_form_scoring);
				$data_summary = $this->dscoringumb->get_data_summary_scoring(NULL, $no_form_scoring);
				$um_jamin     = 0;
				foreach ($data_header as $dta) {
					if ($dta->id_scoring_jaminan_header == $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_header'])) {
						$um_jaminan = $total_appraisal;
					}
					else {
						$um_jaminan = $dta->total_appraisal;
					}
					$um_jamin += $um_jaminan;
				}
				$um_jamin  = number_format($um_jamin, 2, '.', '');
				$um_setuju = min($um_jamin, $data_summary->um_scoring, $data_summary->um_minta);

				$data_row3 = array(
					'um_jamin'     => $um_jamin,
					'um_setuju'    => $um_setuju,
					'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit' => $datetime
				);

				$this->dgeneral->update('tbl_umb_scoring_header', $data_row3, array(
					array(
						'kolom' => 'no_form_scoring',
						'value' => $no_form_scoring
					)
				));


				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukan";
					$sts = "NotOK";
				}
				else {
					$this->dgeneral->commit_transaction();
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}

			}
			else {
				$msg = "Gagal melakukan perubahan.";
				$sts = "NotOK";
			}

			$return = array('sts'             => $sts,
							'msg'             => $msg,
							'total_appraisal' => $total_appraisal,
							'um_jaminan'      => $um_jamin,
							'um_setuju'       => $um_setuju,
							'file_attachment' => $file_attachment
			);
			echo json_encode($return);

		}

		private function save_rekomendasi_legal() {
			$datetime = date("Y-m-d H:i:s");

			if (isset($_POST['id_scoring_jaminan_detail']) && isset($_POST['rekomendasi_legal'])) {
				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				$data_row = array(
					'rekomendasi_legal' => $_POST['rekomendasi_legal'],
					'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'      => $datetime
				);
				$this->dgeneral->update('tbl_umb_scoring_jamin_detail', $data_row, array(
					array(
						'kolom' => 'id_scoring_jaminan_detail',
						'value' => $this->generate->kirana_decrypt($_POST['id_scoring_jaminan_detail'])
					)
				));

				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukan";
					$sts = "NotOK";
				}
				else {
					$this->dgeneral->commit_transaction();
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}

			}
			else {
				$msg = "Gagal melakukan perubahan.";
				$sts = "NotOK";
			}

			$return = array('sts' => $sts,
							'msg' => $msg
			);
			echo json_encode($return);

		}

		private function save_mou() {
			$datetime = date("Y-m-d H:i:s");

			if (isset($_POST['no_form_scoring']) && isset($_POST['action_mou'])) {
				// $this->general->connectDbPortal();
				// $this->dgeneral->begin_transaction();

				$scoring  = $this->dscoringumb->get_data_scoring_header("open", $_POST['no_form_scoring']);
				$um_jamin = $scoring[0]->um_jamin;

				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|jpeg|png|pdf';

				if ($_POST['action_mou'] == "upload") {
					$this->general->connectDbPortal();
					$this->dgeneral->begin_transaction();

					$check = $this->dscoringumb->get_data_dok_mou(NULL, $_POST['no_form_scoring']);

					if ($check) {
						//update
						if ($_POST['app_dok_jaminan'] !== 'approve' && $_FILES['dok_jaminan']['size'] > '0') {
							$newname   = array("MOU_dokumen_kerjasama" . $_POST['no_form_scoring']);
							$file1     = $this->general->upload_files($_FILES['dok_jaminan'], $newname, $config);
							$data_row1 = array(
								'file_location' => $file1['0']['url'],
								'status'        => 'upload',
								'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'  => $datetime,
							);
							$this->dgeneral->update('tbl_umb_scoring_mou', $data_row1, array(
								array(
									'kolom' => 'no_form_scoring',
									'value' => $_POST['no_form_scoring']
								),
								array(
									'kolom' => 'desc',
									'value' => 'Dokumen Perjanjian Kerjasama'
								)
							));
						}

						if ($_POST['app_dok_foto_bersama'] !== 'approve' && $_FILES['dok_foto_bersama']['size'] > 0) {
							$newname   = array("MOU_foto_bersama" . $_POST['no_form_scoring']);
							$file2     = $this->general->upload_files($_FILES['dok_foto_bersama'], $newname, $config);
							$data_row2 = array(
								'file_location' => $file2['0']['url'],
								'status'        => 'upload',
								'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'  => $datetime,
							);
							$this->dgeneral->update('tbl_umb_scoring_mou', $data_row2, array(
								array(
									'kolom' => 'no_form_scoring',
									'value' => $_POST['no_form_scoring']
								),
								array(
									'kolom' => 'desc',
									'value' => 'Foto Bersama'
								)
							));

						}

						if ($um_jamin > 0) {
							if ($_POST['app_dok_tanda_terima'] !== 'approve' && $_FILES['dok_tanda_terima']['size'] > 0) {
								$newname   = array("MOU_tanda_terima" . $_POST['no_form_scoring']);
								$file3     = $this->general->upload_files($_FILES['dok_tanda_terima'], $newname, $config);
								$data_row3 = array(
									'file_location' => $file3['0']['url'],
									'status'        => 'upload',
									'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit'  => $datetime,
								);
								$this->dgeneral->update('tbl_umb_scoring_mou', $data_row3, array(
									array(
										'kolom' => 'no_form_scoring',
										'value' => $_POST['no_form_scoring']
									),
									array(
										'kolom' => 'desc',
										'value' => 'Tanda Terima Jaminan'
									)
								));
							}
						}

						$data_header = array(
							'status_mou'   => '5',
							'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit' => $datetime
						);
						$this->dgeneral->update("tbl_umb_scoring_header", $data_header, array(
							array(
								'kolom' => 'no_form_scoring',
								'value' => $_POST['no_form_scoring']
							)
						));


					}
					else {
						//insert
						$newname   = array("MOU_dokumen_kerjasama" . $_POST['no_form_scoring']);
						$file1     = $this->general->upload_files($_FILES['dok_jaminan'], $newname, $config);
						$data_row1 = array(
							'no_form_scoring' => $_POST['no_form_scoring'],
							'desc'            => "Dokumen Perjanjian Kerjasama",
							'file_location'   => $file1['0']['url'],
							'status'          => $_POST['action_mou'],
							'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
							'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'    => $datetime,
							'tanggal_edit'    => $datetime,
							'active'          => '1'
						);
						$this->dgeneral->insert("tbl_umb_scoring_mou", $data_row1);

						$newname   = array("MOU_foto_bersama" . $_POST['no_form_scoring']);
						$file2     = $this->general->upload_files($_FILES['dok_foto_bersama'], $newname, $config);
						$data_row2 = array(
							'no_form_scoring' => $_POST['no_form_scoring'],
							'desc'            => "Foto Bersama",
							'file_location'   => $file2['0']['url'],
							'status'          => $_POST['action_mou'],
							'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
							'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_buat'    => $datetime,
							'tanggal_edit'    => $datetime,
							'active'          => '1'
						);
						$this->dgeneral->insert("tbl_umb_scoring_mou", $data_row2);

						if ($um_jamin > 0) {
							$newname   = array("MOU_tanda_terima" . $_POST['no_form_scoring']);
							$file3     = $this->general->upload_files($_FILES['dok_tanda_terima'], $newname, $config);
							$data_row3 = array(
								'no_form_scoring' => $_POST['no_form_scoring'],
								'desc'            => "Tanda Terima Jaminan",
								'file_location'   => $file3['0']['url'],
								'status'          => $_POST['action_mou'],
								'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
								'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'    => $datetime,
								'tanggal_edit'    => $datetime,
								'active'          => '1'
							);
							$this->dgeneral->insert("tbl_umb_scoring_mou", $data_row3);
						}

						$data_header = array(
							'status_mou'   => '5',
							'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit' => $datetime
						);
						$this->dgeneral->update("tbl_umb_scoring_header", $data_header, array(
							array(
								'kolom' => 'no_form_scoring',
								'value' => $_POST['no_form_scoring']
							)
						));
					}
				}
				else if ($_POST['action_mou'] == "approve") {

					$data_rows = array(
						'status'       => $_POST['action_mou'],
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime,
					);

					//SAP DISINI
					if ($scoring[0]->no_sap == "" || $scoring[0]->no_sap == NULL) {
						$tipe_scoring = explode("/", $_POST['no_form_scoring'])[0];
						$datas        = array(
							"no_form_scoring" => $_POST['no_form_scoring'],
							"tipe_scoring"    => $tipe_scoring,
							"tanggal_finish"  => date("Y-m-d", strtotime($datetime)),
							"um_final"        => $scoring[0]->um_setuju,
							"renewal"         => 'false',
						);
						$this->sap_plafonum($datas);
					}

					$this->general->connectDbPortal();
					$this->dgeneral->begin_transaction();

					$this->dgeneral->update('tbl_umb_scoring_mou', $data_rows, array(
						array(
							'kolom' => 'no_form_scoring',
							'value' => $_POST['no_form_scoring']
						)
					));

					if ($_POST['dokumen_asli'] == "true") {
						if ($_FILES['dok_asli']['size'] > 0) {
							$newname   = array("MOU_dokumen_jaminan_asli" . $_POST['no_form_scoring']);
							$file4     = $this->general->upload_files($_FILES['dok_asli'], $newname, $config);
							$data_row4 = array(
								'no_form_scoring' => $_POST['no_form_scoring'],
								'desc'            => "Dokumen Jaminan Asli",
								'file_location'   => $file4['0']['url'],
								'status'          => $_POST['action_mou'],
								'komentar'        => $_POST['ket_dok_asli'],
								'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
								'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'    => $datetime,
								'tanggal_edit'    => $datetime,
								'active'          => '1'
							);
							$this->dgeneral->insert("tbl_umb_scoring_mou", $data_row4);
						}

						$data_header = array(
							'status_mou'   => 'completed',
							'status'       => 'completed',
							'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit' => $datetime
						);
						$this->dgeneral->update("tbl_umb_scoring_header", $data_header, array(
							array(
								'kolom' => 'no_form_scoring',
								'value' => $_POST['no_form_scoring']
							)
						));

					}

				}
				else {
					//decline
					$this->general->connectDbPortal();
					$this->dgeneral->begin_transaction();


					$data_row1 = array(
						'status'       => $_POST['app_dok_jaminan'],
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime,
					);
					$this->dgeneral->update('tbl_umb_scoring_mou', $data_row1, array(
						array(
							'kolom' => 'no_form_scoring',
							'value' => $_POST['no_form_scoring']
						),
						array(
							'kolom' => 'desc',
							'value' => 'Dokumen Perjanjian Kerjasama'
						)
					));

					$data_row2 = array(
						'status'       => $_POST['app_dok_foto_bersama'],
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime,
					);
					$this->dgeneral->update('tbl_umb_scoring_mou', $data_row2, array(
						array(
							'kolom' => 'no_form_scoring',
							'value' => $_POST['no_form_scoring']
						),
						array(
							'kolom' => 'desc',
							'value' => 'Foto Bersama'
						)
					));

					if ($um_jamin > 0) {
						$data_row3 = array(
							'status'       => $_POST['app_dok_tanda_terima'],
							'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit' => $datetime,
						);
						$this->dgeneral->update('tbl_umb_scoring_mou', $data_row3, array(
							array(
								'kolom' => 'no_form_scoring',
								'value' => $_POST['no_form_scoring']
							),
							array(
								'kolom' => 'desc',
								'value' => 'Tanda Terima Jaminan'
							)
						));
					}

					$data_header = array(
						'status_mou'   => '1',
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime
					);
					$this->dgeneral->update("tbl_umb_scoring_header", $data_header, array(
						array(
							'kolom' => 'no_form_scoring',
							'value' => $_POST['no_form_scoring']
						)
					));

				}


				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukan";
					$sts = "NotOK";
				}
				else {
					$this->dgeneral->commit_transaction();
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}
			}
			else {
				$msg = "Gagal melakukan perubahan.";
				$sts = "NotOK";
			}

			$return = array('sts' => $sts,
							'msg' => $msg,
			);
			echo json_encode($return);

		}

		private function save_renewal() {
			$datetime = date("Y-m-d H:i:s");

			if (isset($_POST['no_form_scoring'])) {
				//Get tanggal_finish, no_sap, 
				$scoring      = $this->dscoringumb->get_data_scoring_header("open", $_POST['no_form_scoring']);
				$tipe_scoring = explode("/", $_POST['no_form_scoring'])[0];
				//PROSES SAP
				//STOP di sap THEN Tambah baru
				if ($scoring[0]->no_extend == "" || $scoring[0]->no_extend == NULL) {
					$datas = array(
						"no_form_scoring" => $_POST['no_form_scoring'],
						"no_sap"          => $scoring[0]->no_sap,
						"tipe_scoring"    => $tipe_scoring,
						"tanggal_finish"  => date("Y-m-d", strtotime($scoring[0]->tanggal_berakhir)),
						"um_final"        => $scoring[0]->um_setuju,
						"renewal"         => 'true',
					);
					$this->sap_plafonum($datas);
				}


				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();

				//UPDATE LOG
				$data_log = array(
					"no_form_scoring" => $_POST['no_form_scoring'],
					"tgl_status"      => $datetime,
					"action"          => 'extend',
					"status"          => $this->data['session_role'][0]->level,
					"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"    => $datetime,
					"comment"         => $_POST['komentar']
				);
				$this->dgeneral->insert("tbl_umb_scoring_log_status", $data_log);

				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukan";
					$sts = "NotOK";
				}
				else {
					$this->dgeneral->commit_transaction();
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}
			}
			else {
				$msg = "Gagal melakukan perubahan.";
				$sts = "NotOK";
			}

			$return = array('sts' => $sts,
							'msg' => $msg,
			);
			echo json_encode($return);

		}

		//JEJAK
		private function save_berita_acara() {
			$datetime = date("Y-m-d H:i:s");

			if ($_POST['pabrik'] !== 0 && isset($_POST['provinsi']) && isset($_POST['kabupaten'])) {
				//CEK apakah plafon pabrik masih cukup. Double cek disini untuk antisipasi user mengubah via inspect element.
				$sisa_plafon = $this->get_sisa_plafon($_POST['pabrik']);
				$um_propose = (isset($_POST['um_propose']) && trim($_POST['um_propose']) !== "" ? str_replace(",", "", $_POST['um_propose']) : '0');
				if ($sisa_plafon < $um_propose) {
						$msg    = "Gagal Submit. Uang muka yang diajukan melebihi Plafon yang dimiliki oleh pabrik.";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
				}

				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				$tipe_scoring            = $this->generate->kirana_decrypt($_POST['tipe_scoring']);
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] = 'jpg|jpeg|png|pdf';

				//================================SAVE SCORING HEADER================================//
				if ($_FILES['ktp_file']['name'][0] !== "") {
					if ((count($_FILES['ktp_file']['name']) > 1) || count($_FILES['npwp_file']['name']) > 1) {
						$msg    = "You can only upload maximum 1 file";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					$newname  = array(str_replace("/", "-", $_POST['no_form']) . "-KTP");
					$file_ktp = $this->general->upload_files($_FILES['ktp_file'], $newname, $config);
				}
				if ($_FILES['npwp_file']['name'][0] !== "") {
					$newname   = array(str_replace("/", "-", $_POST['no_form']) . "-NPWP");
					$file_npwp = $this->general->upload_files($_FILES['npwp_file'], $newname, $config);
				}

				if ($_FILES['berita_acara']['name'][0] !== "") {
					$newname   = array(str_replace("/", "-", $_POST['no_form']) . "-Berita-Acara");
					$file_berita_acara = $this->general->upload_files($_FILES['berita_acara'], $newname, $config);
				}

				$provinsi = array();
				foreach ($_POST['provinsi'] as $v) {
					array_push($provinsi, $this->generate->kirana_decrypt($v));
				}
				$kab = array();
				foreach ($_POST['kabupaten'] as $v) {
					array_push($kab, $this->generate->kirana_decrypt($v));
				}

								
				$data_scoring = array(
					"plant"              => $_POST['pabrik'],
					"no_form_scoring"    => $_POST['no_form'],
					"id_scoring_tipe"    => $tipe_scoring,
					"kode_supplier"      => (isset($_POST['supplier']) ? $this->generate->kirana_decrypt($_POST['supplier']) : NULL),
					"depo"               => ($_POST['depo'] !== "0" ? $this->generate->kirana_decrypt($_POST['depo']) : NULL),
					"dirops"             => (isset($_POST['dirops']) ? $this->generate->kirana_decrypt($_POST['dirops']) : NULL),
					"provinsi"           => implode(",", $provinsi),
					"kabupaten"          => implode(",", $kab),
					"jarak_tempuh"       => (isset($_POST['jarak_tempuh']) && trim($_POST['jarak_tempuh']) !== "" ? str_replace(",", "", $_POST['jarak_tempuh']) : NULL),
					"tanggal"            => (isset($_POST['tgl_pengajuan']) && trim($_POST['tgl_pengajuan']) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_pengajuan']) : NULL),
					"tanggal_berakhir"   => (isset($_POST['tgl_berakhir']) && trim($_POST['tgl_berakhir']) !== "" ? $this->generate->regenerateDateFormat($_POST['tgl_berakhir']) : NULL),
					"supply_start"       => (isset($_POST['supply_since']) && trim($_POST['supply_since']) !== "" ? $this->generate->regenerateDateFormat($_POST['supply_since']) : NULL),
					"lama_join"          => (isset($_POST['lama_join']) && trim($_POST['lama_join']) !== "" ? $_POST['lama_join'] : NULL),
					"um_minta"           => (isset($_POST['um_propose']) && trim($_POST['um_propose']) !== "" ? str_replace(",", "", $_POST['um_propose']) : NULL),
					"um_setuju"          => (isset($_POST['um_setuju_summary']) && trim($_POST['um_setuju_summary']) !== "" ? str_replace(",", "", $_POST['um_setuju_summary']) : NULL),
					"waktu_selesai"      => (isset($_POST['waktu']) && trim($_POST['waktu']) !== "" ? $_POST['waktu'] : NULL),
					"max_um_outstanding" => (isset($_POST['max_um_outs']) && trim($_POST['max_um_outs']) !== "" ? str_replace(",", "", $_POST['max_um_outs']) : NULL),
					// "point_of_purch"     => ($_POST['pop'] !== "0" ? $_POST['pop'] : NULL),
					// "vendor_nonbkr"      => (isset($_POST['vendor_nonbkr']) ? $this->generate->kirana_decrypt($_POST['vendor_nonbkr']) : NULL),
					"file_ktp"           => (isset($file_ktp) ? $file_ktp[0]['url'] : NULL),
					"file_npwp"          => (isset($file_npwp) ? $file_npwp[0]['url'] : NULL),
					"file_berita_acara"  => (isset($file_berita_acara) ? $file_berita_acara[0]['url'] : NULL),
					"status"             => 'completed',
					"login_edit"         => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"       => $datetime
				);

				$data_scoring = $this->dgeneral->basic_column("insert_simple", $data_scoring);
				$this->dgeneral->insert("tbl_umb_scoring_header", $data_scoring);
				                	
				//================================SAVE ALL================================//

				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukan";
					$sts = "NotOK";
				}
				else {
					$this->dgeneral->commit_transaction();
					$this->general->closeDb();
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
					
					// SAP MULAI DISINI
					// JIKA BERHASIL OK
					// JIKA GAGAL SAVE LOG DAN HAPUS SQL YANG TADI DI INPUT

					// //SAP BA MULAI
					$this->connectSAP("ERP_310");

					//INISIALISASI DATA
					$depo         = "";
					if ($_POST['depo'] !== "0") {
						$depo = $this->dgeneral->get_data_depo($this->generate->kirana_decrypt($_POST['depo']), NULL, $_POST['pabrik'])[0]->DEPNM;
					}

					$splch = "";
					$kode_scoring = explode("/", $_POST['no_form'])[0];;
					// UMK = '' -- M = DM -- DMT = T -- Ranger -- R
					switch ($kode_scoring) {
						case 'RG':
							$lifnr         = (isset($_POST['dirops']) ? $this->generate->kirana_decrypt($_POST['dirops']) : NULL);
							$splch         = 'R';
							$tanggal_akhir = date("Ymd", strtotime($_POST['tgl_berakhir']));
							break;
						case 'DM':
							$lifnr         = (isset($_POST['supplier']) ? $this->generate->kirana_decrypt($_POST['supplier']) : NULL);
							$splch         = 'M';
							$tanggal_akhir = date("Ymd", strtotime($_POST['tgl_berakhir']));
							break;
						case 'DMT':
							$lifnr         = (isset($_POST['supplier']) ? $this->generate->kirana_decrypt($_POST['supplier']) : NULL);
							$splch = 'T';
							
							$tanggal_akhir = date("Ymd", strtotime($_POST['tgl_berakhir']));
							break;
						default:
							$lifnr         = (isset($_POST['supplier']) ? $this->generate->kirana_decrypt($_POST['supplier']) : NULL);
							$splch         = '';
							$tanggal_akhir = date("Ymd", strtotime($_POST['tgl_berakhir']));
							break;
					}

					$this->general->connectDbPortal();
					$this->dgeneral->begin_transaction();

					if ($this->data['sap']->getStatus() == SAPRFC_OK) {
						$table_fee[] = array(
							"MANDT" => '310', // 310 dev or kmtemp prod
							"EKORG" => $_POST['pabrik'], // plant
							"DEPNM" => $depo, // depo name
							"BEGDA" => date("Ymd"), // tanggal_finish format dmY
							// "BEGDA"   => '20191009', // tanggal_finish format dmY
							"ENDDA" => '99991231', // end_date (kiamat) format dmY
							"WAERS" => 'IDR',
							"LIFNR" => (isset($_POST['supplier']) ? $this->generate->kirana_decrypt($_POST['supplier']) : NULL), // supplier kode
							"FEE01" => number_format('0', 0, '', ''), // fee non tax
							"FEE02" => number_format('0', 0, '', ''), // fee tax gross
							"FEE03" => number_format('0', 0, '', ''), // fee non gross
						);

						$table_scoring[] = array(
							// "NUMB"    => $test, return dari sap (id ini save di sql)
							"EKORG"   => $_POST['pabrik'], // plant
							"LIFNR"   => $lifnr, // supplier kode
							"BEGDA"   => date("Ymd"), // tanggal_finish format dmY
							// "BEGDA"   => '20191009', // tanggal_finish format dmY
							"ENDDA"   => date("Ymd", strtotime($tanggal_akhir)), //end_date (kiamat) format dmY
							"SPLCH"   => $splch, //tipe_scoring m/r/t
							"DEPNM"   => $depo, // depo name
							"DEFLG"   => '', // delete FLAG (if delete = X capital)
							"UMUR"    => (isset($_POST['waktu']) && trim($_POST['waktu']) !== "" ? $_POST['waktu'] : NULL), // waktu selesai (hari)
							"JAMINAN" => number_format('0', 0, '', ''), // Nilai Jaminan
							"PFAMT"   => number_format(str_replace(",", "", $_POST['um_propose']), 0, '', ''), // um_finish
							"WAERS"   => 'IDR', // IDR
							"TOTAL"   => (isset($_POST['max_um_outs']) && trim($_POST['max_um_outs']) !== "" ? str_replace(",", "", $_POST['max_um_outs']) : NULL), // max_um_outstanding (default 2)
						);


						// if ($splch == 'R' && ($data_scoring->fee_non_tax !== NULL || $data_scoring->fee_tax_gross !== NULL || $data_scoring->fee_non_gross !== NULL)) {
						// 	$param = array(
						// 		array("TABLE", "T_RETURN", array()),
						// 		array("TABLE", "T_FEE", $table_fee),
						// 		array("TABLE", "T_DATA", $table_scoring),
						// 	);
						// }
						// else {
							$param = array(
								array("TABLE", "T_RETURN", array()),
								array("TABLE", "T_FEE", array()),
								array("TABLE", "T_DATA", $table_scoring),
							);
						// }


						$result = $this->data['sap']->callFunction("Z_RFC_CRT_PLAFONUM", $param);
						// echo json_encode($result);exit();

						if ($this->data['sap']->getStatus() == SAPRFC_OK && !empty($result["T_RETURN"])) {
							$type    = array();
							$message = array();
							foreach ($result["T_RETURN"] as $return) {
								$type[]    = $return['TYPE'];
								$message[] = $return['MESSAGE'];
							}

							if (in_array('E', $type) === true) {
								$data_row_log = array(
									'app'           => 'DATA RFC Create Plafonum (UMB)',
									'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
									'log_code'      => implode(" , ", $type),
									'log_status'    => 'Gagal',
									'log_desc'      => "Create Plafonum Failed [T_RETURN]: " . implode(" , ", $message),
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
							}
							else {
								$data_row_log = array(
									'app'           => 'DATA RFC Create Plafonum (UMB)',
									'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
									'log_code'      => implode(" , ", $type),
									'log_status'    => 'Berhasil',
									'log_desc'      => implode(" , ", $message),
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
							}

							//update data NO SAP for future edit => UMB Scoring Header
							$no_sap = NULL;
							foreach ($result["T_DATA"] as $tdata) {
								$no_sap = $tdata["NUMB"];
							}

							if ($no_sap !== NULL && $no_sap !== "") {
								
								//tanggal
								$data_row       = array(
									"no_sap"           => $no_sap,
									"tanggal_finish"   => date("Y-m-d", strtotime($datetime))
								);
								
								$data_row = $this->dgeneral->basic_column("update", $data_row);
								$this->dgeneral->update("tbl_umb_scoring_header", $data_row, array(
									array(
										'kolom' => 'no_form_scoring',
										'value' => $_POST['no_form']
									)
								));

								$data_log = array(
									"no_form_scoring" => $_POST['no_form'],
									"tgl_status"      => $datetime,
									"action"          => 'submit',
									"status"          => $this->data['session_role'][0]->level,
									"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
									"tanggal_edit"    => $datetime,
									"comment"         => "PENGAJUAN BERITA ACARA UANG MUKA"
								);
								$this->dgeneral->insert("tbl_umb_scoring_log_status", $data_log);

								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
							
							}

							//================================SAVE ALL================================//
							if ($this->dgeneral->status_transaction() === false) {
								$this->dgeneral->rollback_transaction();
								$this->general->closeDb();

								//delete data yang tadi di input di sql
								$this->general->connectDbPortal();
								$this->dgeneral->begin_transaction();
								$this->dgeneral->delete("tbl_umb_scoring_header", array(
									array(
										'kolom' => 'plant',
										'value' => $_POST['pabrik']
									),
									array(
										'kolom' => 'no_form_scoring',
										'value' => $_POST['no_form']
									)
								));
								$this->dgeneral->commit_transaction();
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
								if (in_array('E', $type) === true){
									$sts = "NotOK";
									//delete data yang tadi di input di sql
									$this->general->connectDbPortal();
									$this->dgeneral->begin_transaction();
									$this->dgeneral->delete("tbl_umb_scoring_header", array(
										array(
											'kolom' => 'plant',
											'value' => $_POST['pabrik']
										),
										array(
											'kolom' => 'no_form_scoring',
											'value' => $_POST['no_form']
										)
									));
									$this->dgeneral->commit_transaction();
									$this->general->closeDb();
								}

								if ($sts == "NotOK") {
									$return = array('sts' => $sts, 'msg' => $msg);
									echo json_encode($return);
									exit();
								}
								else {
									$return = array('sts' => $sts, 'msg' => "Data Berhasil Ditambahkan");
									echo json_encode($return);
									exit();
								}
							}
						}
						else {
							$data_row_log = array(
								'app'           => 'DATA RFC Create Plafonum (UMB)',
								'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
								'log_code'      => $result["T_RETURN"]["TYPE"],
								'log_status'    => 'Gagal',
								'log_desc'      => "Create Plafonum Failed: " . $result["T_RETURN"]["MESSAGE"],
								'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
								'executed_date' => $datetime
							);

							$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

							//delete data yang tadi di input di sql
							$this->dgeneral->delete("tbl_umb_scoring_header", array(
								array(
									'kolom' => 'plant',
									'value' => $_POST['pabrik']
								),
								array(
									'kolom' => 'no_form_scoring',
									'value' => $_POST['no_form']
								)
							));

							$msg = $data_row_log['log_desc'];
							$sts = "NotOK";
						}
					}
					else {
						// KONDISI KONEKSI SAP GAGAL

						$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
						$data_row_log = array(
							'app'           => 'DATA RFC Create Plafonum (UMB)',
							'rfc_name'      => 'Z_RFC_CRT_PLAFONUM',
							'log_code'      => 'E',
							'log_status'    => 'Gagal',
							'log_desc'      => "Connecting Failed: " . $status,
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);

						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

						//delete data yang tadi di input di sql
						$this->dgeneral->delete("tbl_umb_scoring_header", array(
							array(
								'kolom' => 'plant',
								'value' => $_POST['pabrik']
							),
							array(
								'kolom' => 'no_form_scoring',
								'value' => $_POST['no_form']
							)
						));
						
						$msg = $data_row_log['log_desc'];
						$sts = "NotOK";
						
					}

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
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
					//SAP BA SELESAI

				}
			}
			else {
				$msg = "Periksa kembali data yang dimasukan";
				$sts = "NotOK";
			}

			// echo json_encode($_POST);exit();

			$return = array('sts' => $sts,
							'msg' => $msg,
			);
			echo json_encode($return);

		}

		private function get_data_scoring($array = NULL, $no_form = NULL, $tipe_um = NULL, $year = NULL, $status = NULL, $plant = NULL) {
			$scoring = $this->dscoringumb->get_data_scoring_header("open", $no_form, $tipe_um, $year, NULL, $status);
			$scoring = $this->general->generate_encrypt_json($scoring, array("id_scoring_tipe", "depo", "kode_supplier", "kode_vendor_nonbkr", "dirops"));
			if ($no_form && $scoring) {
				$provinsi            = explode(",", $scoring[0]->provinsi);
				$provinsi_nama_array = array();
				$provinsi_in_encrypt = array();
				foreach ($provinsi as $dt) {
					$provinsi_nama = $this->general->get_data_provinsi("array", $dt);
					if ($provinsi_nama)
						array_push($provinsi_nama_array, $provinsi_nama[0]->nama_provinsi);
					array_push($provinsi_in_encrypt, $this->generate->kirana_encrypt($dt));
				}

				$kabupaten            = explode(",", $scoring[0]->kabupaten);
				$kabupaten_in_encrypt = array();
				$kabupaten_nama_array = array();
				foreach ($kabupaten as $dt) {
					$kabupaten_nama = $this->general->get_data_kabupaten("array", $dt);
					if ($kabupaten_nama)
						array_push($kabupaten_nama_array, $kabupaten_nama[0]->nama_kab);
					array_push($kabupaten_in_encrypt, $this->generate->kirana_encrypt($dt));
				}

				$kriteria = $this->dscoringumb->get_data_scoring_kriteria("open", $no_form);
				$kriteria = $this->general->generate_encrypt_json($kriteria, array("id_scoring_tipe", "id_scoring_kriteria", "id_umb_mkriteria"));

				$jaminan = $this->dscoringumb->get_data_scoring_jaminan_header("open", $no_form);
				if ($jaminan) {
					foreach ($jaminan as $j) {
						$jaminan_dok = $this->dscoringumb->get_data_scoring_jaminan_dokumen("open", $j->id_scoring_jaminan_header);
						$jaminan_dok = $this->general->generate_encrypt_json($jaminan_dok, array("id_scoring_jaminan_header", "id_scoring_jaminan_dok"));

						$jaminan_detail = $this->dscoringumb->get_data_scoring_jaminan_detail("open", $j->id_scoring_jaminan_header);
						$jaminan_detail = $this->general->generate_encrypt_json($jaminan_detail, array("id_scoring_jaminan_header", "id_scoring_jaminan_detail", "id_mjaminan_header", "id_mjaminan_detail", "id_scoring_jaminan_nilai"));
						$j->dokumen     = $jaminan_dok;
						$j->detail      = $jaminan_detail;
					}
					$jaminan = $this->general->generate_encrypt_json($jaminan, array("id_scoring_jaminan_header", "id_umb_mdokumen"));
				}

				$depo = NULL;
				if (isset($scoring[0]->depo) && $scoring[0]->depo !== "") {
					$depo = $this->dgeneral->get_data_depo($this->generate->kirana_decrypt($scoring[0]->depo))[0]->DEPNM;
				}

				$scoring[0]->provinsi       = $provinsi_in_encrypt;
				$scoring[0]->provinsi_nama  = $provinsi_nama_array;
				$scoring[0]->kabupaten      = $kabupaten_in_encrypt;
				$scoring[0]->kabupaten_nama = $kabupaten_nama_array;
				$scoring[0]->jaminan        = $jaminan;
				$scoring[0]->kriteria       = $kriteria;
				$scoring[0]->depo_nama      = $depo;
			}

			if ($array) {
				return $scoring;
			}
			else {
				echo json_encode($scoring);
			}
		}

		private function generate_status_scoring($action, $no_form = NULL) {
			// $last_action = $this->dscoringumb->get_last_action_scoring("open", str_replace("-", "/", $no_form));

			switch ($action) {
				case 'submit'   :
				case 'edit'     :
					$status = $this->data['session_role'][0]->if_approve;
					break;

				case 'approve'  :
					$status = $this->data['session_role'][0]->if_approve;
					break;

				case 'assign'  :
					$status = $this->data['session_role'][0]->if_assign;
					break;

				case 'decline'  :
					$status = $this->data['session_role'][0]->if_decline;
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
			$datetime       = date("Y-m-d H:i:s");
			$app_ceo        = false;
			$app_ceo_by_sec = false;

			if (isset($_POST['no_form_scoring'])) {

				$scoring      = $this->dscoringumb->get_data_scoring_header("open", $_POST['no_form_scoring']);
				$tipe_scoring = explode("/", $_POST['no_form_scoring'])[0];
				// $kriteria_scoring = $this->dscoringumb->get_data_scoring_kriteria_nilai("open", $_POST['no_form_scoring']);
				// $scoring = $this->dmasterumb->get_master_scoring("open", NULL, 'n', 'n', $scoring[0]->id_scoring_tipe);
				// echo json_encode($kriteria_scoring->sum_score."-".$scoring[0]->std_minimal);exit();
				$um_final = $tipe_scoring == "RG" || $tipe_scoring == "DMT" ? (isset($_POST['rekom_um_app']) && trim($_POST['rekom_um_app']) !== "" ? str_replace(",", "", $_POST['rekom_um_app']) : $scoring[0]->um_minta) : $scoring[0]->um_setuju;


				//===== Approval Flow ======
				

				if ($_POST['action'] == 'approve' && $this->data['session_role'][0]->nama_role == 'COO' && $this->data['session_role'][0]->level == '4') {
					// Kalau tipe_scoring == ranger maka lanjut ke purchasing, jika non-ranger maka ke legal
					// level divhead legal == 5 || level divhead purcashing == 6
					$status = $scoring[0]->tipe_scoring == 'Ranger' ? '6' : '5'; //simple way but statis
					$status = $scoring[0]->tipe_scoring == 'Ranger' ? '6' : ( $scoring[0]->um_jamin > 0 ? '5' : '6'); //tambahan validasi, jika um non-ranger tidak ada jaminan, legal diganti purchasing
					// $status = $scoring[0]->tipe_scoring == 'Ranger' ? '6' : $this->generate_status_scoring($_POST['action'], $_POST['no_form_scoring']); //if_approve di set ke siapa
				}
				else if ($_POST['action'] == 'approve' && $this->data['session_role'][0]->nama_role == 'Finance Controller HO Div Head' && $this->data['session_role'][0]->level == '7') {
					// Cek apakah Permintaan UM < UM Scoring
					// level Chief Of Supply Chain == 8 || level divhead purcashing == 6
					// $status = $scoring[0]->um_minta < $scoring[0]->um_scoring ? $this->generate_status_scoring($_POST['action'], $_POST['no_form_scoring']) : ($scoring[0]->tipe_scoring == 'Ranger' ? '8' :'6');

					if ($tipe_scoring == "UMK" || $tipe_scoring == "DM") {
						// - um_minta < um_scoring (finish ke manager kantor untuk upload) UMK DM
						// - um_minta > um_scoring && nilai_scoring > nilai_std (lanjut ke CEO Group) UMK DM => ini sudah di validasi di awal pembuatan form
						// jika um tanpa jaminan - dari fincon langsung ke pak hendy dikarenakan purchasing sudah dipindah ke sebelum fincon
						$status = ($scoring[0]->um_jamin > 0 && $scoring[0]->um_minta < $scoring[0]->um_scoring) ? 'finish' : ($scoring[0]->um_jamin > 0 ? '6' : '8');

						// - jika nilai appraisal turun (lanjut ke CEO GRoup) == UMK DM REMARK
						// $jaminan_detail = $this->dscoringumb->get_data_scoring_jaminan_detail('open', NULL, $_POST['no_form_scoring']);
						// $penurunan = "false";
						// foreach ($jaminan_detail as $dt) {
						// 	if (isset($dt->hasil_appraisal) && $dt->hasil_appraisal < $dt->nilai_appraisal){
						// 		$penurunan = "true";
						// 	} 
						// }
						// if ($penurunan == "true") {
						// 	$status = '6';
						// }

					}
					else if ($tipe_scoring == "RG") {
						// 4 == id_scoring_tipe ranger
						$plant = explode("/", $_POST['no_form_scoring'])[1];
						$query_nilai_ranger = $this->dscoringumb->get_sum_umb_pabrik('open', $plant, '4');
						$total_nilai_ranger = (isset($query_nilai_ranger) ? $query_nilai_ranger->plafon_terpakai : '0');

						// - JIka tipe fee dipilih maka lanjut ke Ceo group== RG
						$dta    = $this->dscoringumb->get_data_summary_scoring('open', $_POST['no_form_scoring']);
						$status = (isset($dta->fee_tax_gross) && $dta->fee_tax_gross > 0) || (isset($dta->fee_non_tax) && $dta->fee_non_tax > 0) || (isset($dta->fee_non_gross) && $dta->fee_non_gross > 0) || ($total_nilai_ranger >= '500000000') ? '8' : 'finish';
					}
					else {
						$status = $scoring[0]->um_jamin > 0 ? 'finish' : '8';
					}
				}
				else if ($_POST['action'] == 'approve' && $this->data['session_role'][0]->nama_role == 'Purchasing HO Div Head' && $this->data['session_role'][0]->level == '6') {

					$status = ($tipe_scoring == "RG") ? $this->generate_status_scoring($_POST['action'], $_POST['no_form_scoring']) : ($scoring[0]->um_jamin > 0 ? '8' : '7');
				}
				else if ($_POST['action'] == 'upload' && $this->data['session_role'][0]->nama_role == 'Manager Kantor' && $this->data['session_role'][0]->level == '1') {
					$status = '5'; //legal
				}
				else if ($this->data['session_role'][0]->nama_role == 'CEO Group' && $this->data['session_role'][0]->level == '9') {
					$status = $_POST['action'] == 'approve' ? 'finish' : 'drop';
					// $status = $_POST['action'] == 'approve' ? ($tipe_scoring == 'RG' ? 'completed' : 'finish') : 'drop' ;
					$app_ceo = true;
				}
				else if ($this->data['session_role'][0]->nama_role == 'Secretary' && $this->data['session_role'][0]->level == '10') {
					$status = $_POST['action'] == 'approve' ? 'finish' : 'drop';
					// $status = $_POST['action'] == 'approve' ? ($tipe_scoring == 'RG' ? 'completed' : 'finish') : 'drop' ;
					$app_ceo_by_sec = true;

					$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$newname                 = array("file_ceo_group_" . str_replace("/", "-", $_POST['no_form_scoring']));
					$file_ceo_group          = $this->general->upload_files($_FILES['file_ceo_group'], $newname, $config);
				}
				else {
					$status = $this->generate_status_scoring($_POST['action'], $_POST['no_form_scoring']);
				}

				$data_row = array(
					"status" => $status
				);


				if ($app_ceo == true && isset($_POST['rekom_um_app']) && trim($_POST['rekom_um_app']) !== "") {
					$data_row["um_setuju"]  = str_replace(",", "", $_POST['rekom_um_app']);
					$um_final               = str_replace(",", "", $_POST['rekom_um_app']);
					$data_row["status_mou"] = '1';
				}

				if ($app_ceo_by_sec == true && isset($_POST['rekom_um_app']) && trim($_POST['rekom_um_app']) !== "") {
					$data_row["um_setuju"]      = str_replace(",", "", $_POST['rekom_um_app']);
					$um_final                   = str_replace(",", "", $_POST['rekom_um_app']);
					$data_row["file_ceo_group"] = $file_ceo_group['0']['url'];
					$data_row["status_mou"]     = '1';
				}

				if ($_POST['action'] == 'stop') {
					$config['upload_path']        = $this->general->kirana_file_path($this->router->fetch_module());
					$config['allowed_types']      = 'jpg|jpeg|png|pdf';
					$newname                      = array("file_stop_um_" . str_replace("/", "-", $_POST['no_form_scoring']));
					$file_stop_um                 = $this->general->upload_files($_FILES['stop_um'], $newname, $config);
					$data_row["file_stop_um"]     = $file_stop_um['0']['url'];
					$data_row["tanggal_berakhir"] = $datetime;
					$data_stop                    = array(
						"no_form_scoring" => $_POST['no_form_scoring'],
						"tanggal_stop"    => date("Y-m-d", strtotime($datetime)),
					);
					$this->sap_stop_plafonum($data_stop);
				}

				// if(($status == 'finish' && $tipe_scoring !== 'RG' ) || ($status == 'completed' && $tipe_scoring == 'RG')) {
				if ($status == 'finish') {
					$data_row["status_mou"] = '1';
					$data_row["um_setuju"]  = $um_final;

					// $data_row["tanggal_finish"] = date("Y-m-d", strtotime($datetime));
					// $tanggal_akhir = date('Y-m-d H:i:s', strtotime($data_row["tanggal_finish"] . ' +3 months'));
					// $data_row["tanggal_berakhir"] = $tipe_scoring == 'DMT' ? $tanggal_akhir : date("Y-m-d H:i:s", strtotime("9999-12-31"));

					//SAP
					// if ($scoring[0]->no_sap == ""  || $scoring[0]->no_sap == NULL ) {
					// 	$datas = array(
					// 			"no_form_scoring" => $_POST['no_form_scoring'], 
					// 			"tipe_scoring"    => $tipe_scoring, 
					// 			"tanggal_finish"  => date("Y-m-d", strtotime($datetime)), 
					// 			"um_final"        => $um_final,
					// 			"renewal"	      => 'false', 
					// 	); 
					// 	$this->sap_plafonum($datas);
					// }

				}

				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				if (isset($_FILES['ranger_file']) && $_FILES['ranger_file']['name'][0] !== "") {
					$newname   = array(str_replace("/", "-", $_POST['no_form_scoring']) . "-Attachment-".str_replace(' ', '', $this->data['session_role'][0]->nama_role));
					$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$file_ranger = $this->general->upload_files($_FILES['ranger_file'], $newname, $config);
					$data_row["file_ranger"] = $file_ranger[0]['url'];
				}
				
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_umb_scoring_header", $data_row, array(
					array(
						'kolom' => 'no_form_scoring',
						'value' => $_POST['no_form_scoring']
					)
				));

				$data_log = array(
					"no_form_scoring" => $_POST['no_form_scoring'],
					"tgl_status"      => $datetime,
					"action"          => $_POST['action'],
					"status"          => $this->data['session_role'][0]->level,
					"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"    => $datetime,
					"comment"         => $_POST['komentar']
				);
				$this->dgeneral->insert("tbl_umb_scoring_log_status", $data_log);

				if (isset($_POST['rekom_um_app']) && $this->data['session_role'][0]->is_rekom == '1') {
					$data_rekom = array(
						"no_form_scoring" => $_POST['no_form_scoring'],
						"tgl_status"      => $datetime,
						"status"          => $this->data['session_role'][0]->level,
						"login_edit"      => base64_decode($this->session->userdata("-id_user-")),
						"tanggal_edit"    => $datetime,
						"rekom_um"        => (isset($_POST['rekom_um_app']) && trim($_POST['rekom_um_app']) !== "" ? str_replace(",", "", $_POST['rekom_um_app']) : NULL)
					);

					$this->dgeneral->insert("tbl_umb_scoring_rekom_um", $data_rekom);
				}

				//================================SAVE ALL================================//
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				}
				else {
					$this->dgeneral->commit_transaction();
					$nilai = $um_final > 0 ? $um_final : $scoring[0]->um_minta;
					$this->generate_message_email($_POST['no_form_scoring'], $_POST['action'], $nilai, $_POST['komentar']);
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}
			}
			else {
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			}
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}

		private function generate_message_email($no_form_scoring, $action, $um_setuju, $komentar = NULL) {
			$plant      = explode("/", $no_form_scoring)[1];
			$plant_name = $this->dmasterumb->get_master_plafon("open", $plant)[0]->plant_name;

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
			$data_email = $this->dscoringumb->get_email_umb("open", $no_form_scoring, $plant);
			$email_cc   = array();
			$email_to   = array();
			$nama_to    = array();
			foreach ($data_email as $dt) {
				if ($dt->nilai == 'cc') {
					$email_cc[] = "matthew.jodi@kiranamegatara.com";//$dt->email;
				}
				else {
					$email_to[] = "matthew.jodi@kiranamegatara.com";//$dt->email;
					if ($dt->nama !== "" && $dt->gender !== "") {
						$nama_to[] = $dt->gender . " " . ucwords(strtolower($dt->nama)) . "<br>";
					}
				}
			}

			$oleh = ucwords(strtolower(base64_decode($this->session->userdata("-gelar-")) . " " . base64_decode($this->session->userdata("-nama-"))));

			$data_email = array(
				"no_form_scoring" => $no_form_scoring,
				"plant"           => $plant_name,
				"um_setuju"       => number_format($um_setuju, 2, '.', ''),
				"status"          => $status,
				"comment"         => $comment,
				"oleh"            => $oleh,
				"email_cc"        => $email_cc,
				"email_to"        => $email_to,
				"nama_to"         => empty($nama_to) ? "" : implode("", $nama_to)
			);
			$this->send_email($data_email);
		}

		private function send_email($data) {
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

			$this->email->from('no-reply@kiranamegatara.com', 'K-SCRUM');
			$this->email->to($data['email_to']);
			$this->email->cc($data['email_cc']);


			$message = "<html>
                        <body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
                        <center style='width: 100%;'>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                Notifikasi Email Aplikasi Scoring Uang Muka
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
                                            <h1 style='margin-bottom: 0;'>Kirana Scoring Uang Muka</h1>
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
			if (!$data['nama_to']) {
				$data['nama_to'] = 'Bapak & Ibu';
			}
			$message .= "<p><strong>Kepada :<br><br> " . $data['nama_to'] . "</strong></p>";
			$message .= "<p>Email ini menandakan bahwa ada Pengajuan penilaian Uang Muka baru yang membutuhkan perhatian anda.</p>";
			$message .= "<table style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
                                                <tr>
                                                    <td>Plant</td>
                                                    <td>:</td>";
			$message .= "<td>" . $data['plant'] . "</td>"; //Plant
			$message .= "</tr>
                                                <tr>
                                                    <td>No Form Scoring</td>
                                                    <td>:</td>";
			$message .= "<td>" . $data['no_form_scoring'] . "</td>"; //no form scoring
			$message .= "</tr>
                                                <tr>
                                                    <td>UM yang disetujui</td>
                                                    <td>:</td>";
			$message .= "<td>" . $data['um_setuju'] . "</td>"; //UM disetujui
			$message .= "</tr>
                                                <tr>
                                                    <td>Status</td>
                                                    <td>:</td>";
			$message .= "<td>" . $data['status'] . "</td>"; // STATUS (disetujui, ditolak, selesai)
			$message .= "</tr>
                                                <tr>
                                                    <td>Oleh</td>
                                                    <td>:</td>";
			$message .= "<td>" . $data['oleh'] . "</td>"; //OLEH atau LAST ACTION PI
			$message .= "</tr>
                                                <tr>
                                                    <td>Tanggal</td>
                                                    <td>:</td>";
			$message .= "<td>" . strftime('%A, %d %B %Y') . "</td>"; //TANGGAL KIRIM EMAIL
			$message .= "</tr>
                                                <tr>
                                                    <td>Catatan</td>
                                                    <td>:</td>";
			if (!$data['comment']) {
				$data['comment'] = '-';
			}
			$message .= "<td>" . $data['comment'] . "</td>"; // COMMENT PI
			$message .= "</tr>
                                </table>
                                <p>Selanjutnya anda dapat melakukan review pada Pengajuan penilaian Uang Muka tersebut</p><p>melalui aplikasi Uang Muka Bokar di Portal Kiranaku.</p>
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
                            <small>Kiranaku Auto-MailSystem</small><br/>";
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

			$this->email->subject('Notifikasi Status Pengajuan Scoring Uang Muka');
			$this->email->message($message);

			$this->email->send();
		}

	}
