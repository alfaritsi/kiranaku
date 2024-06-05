<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/*
        @application  : 
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 21-Dec-18
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */
include_once APPPATH . "modules/nusira/controllers/BaseControllers.php";

class Order extends BaseControllers
{
	protected $maintenance;

	public function __construct()
	{
		parent::__construct();
		$this->data['module'] = "NUSIRA WORKSHOP";
		$this->load->model('dmasternusira');
		$this->load->model('dordernusira');
		$this->maintenance = 0;
	}

	public function tambah()
	{
		if (base64_decode($this->session->userdata('-nik-')) !== '8347' && $this->maintenance == 1) {
			$this->load->view("maintenance");
		} else {
			//====must be initiate in every view function====//
			$this->general->check_access();
			//===============================================//
			$this->check_acces_pi("/k-air/invest/tambah");
			$plant                  = explode(",", $this->session->userdata("-pi_plant_code-"));
			$this->data['title']    = "Tambah Permintaan Investasi Nusira Workshop";
			$this->data['kepada']   = $this->dordernusira->get_data_userrole("open", NULL, "Division Head", NULL, NULL, NULL, "DIVISION FACTORY OPERATION");
			$this->data['tujuan']   = $this->dordernusira->get_data_tujuan("open");
			$this->data['no_pi']    = $this->generate_no_pi("open");
			$this->data['pic_proj'] = $this->dordernusira->get_pic_proyek("open", $plant);
			$this->load->view("order/tambah", $this->data);
		}
	}

	public function detail($key = NULL)
	{
		if (base64_decode($this->session->userdata('-nik-')) !== '8347' && $this->maintenance == 1) {
			$this->load->view("maintenance");
		} else {
			//====must be initiate in every view function====//
			$this->general->check_session();
			//===============================================//
			$plant                  = explode("-", $key)[2];
			$this->data['title']    = "Detail Permintaan Investasi Nusira Workshop";
			$this->data['no_pi']    = str_replace("-", "/", $key);
			$this->data['header']   = $this->dordernusira->get_pi_header("open", $plant, NULL, $this->data['no_pi']);
			$this->data['kepada']   = $this->dordernusira->get_data_userrole("open", NULL, NULL, NULL, $this->data['header']->kepada);
			$this->data['tujuan']   = $this->dordernusira->get_data_tujuan("open");
			$this->data['pic_proj'] = $this->dordernusira->get_pic_proyek("open", $plant);
			if (!$this->data['header']) {
				show_404();
			}
			$this->data['capex']       = $this->dordernusira->get_capex("open", $plant, NULL, $this->data['no_pi']);
			$this->data['dept_head']   = $this->dordernusira->get_dept_head_in_division("open", base64_decode($this->session->userdata('-id_divisi-')));
			$this->data['last_action'] = $this->dordernusira->get_last_action_pi("open", $this->data['no_pi']);
			$this->data['disposisi']   = $this->dordernusira->get_disposisi_pi("open", base64_decode($this->session->userdata('-nik-')), $this->data['no_pi']);
			$this->data['base_url_pi'] = $this->base_url_pi;
			if (in_array($this->data['header']->status, array('deleted', 'finish', 'drop')) == false) {
				$this->data['pi_action'] = $this->dordernusira->get_data_role(NULL, NULL, NULL, $this->data['header']->status);
			}
			$this->load->view("order/detail", $this->data);
		}
	}

	public function edit($key = NULL)
	{
		if (base64_decode($this->session->userdata('-nik-')) !== '8347' && $this->maintenance == 1) {
			$this->load->view("maintenance");
		} else {
			//====must be initiate in every view function====//
			$this->general->check_session();
			//===============================================//
			if (!isset($key)) {
				show_404();
			}
			$plant                  = explode("-", $key)[2];
			$this->data['title']    = "Edit Permintaan Investasi Nusira Workshop";
			$this->data['no_pi']    = str_replace("-", "/", $key);
			$this->data['kepada']   = $this->dordernusira->get_data_userrole("open", NULL, "Division Head");
			$this->data['tujuan']   = $this->dordernusira->get_data_tujuan("open");
			$this->data['pic_proj'] = $this->dordernusira->get_pic_proyek("open", $plant);
			$this->data['header']   = $this->dordernusira->get_pi_header("open", $plant, NULL, $this->data['no_pi']);
			if (!$this->data['header'] || in_array(base64_encode($this->data['header']->status), $this->session->userdata('-pi_level-')) == false || in_array($this->data['header']->status, array(1, 2)) == false) {
				show_404();
			}
			$this->load->view("order/edit", $this->data);
		}
	}

	public function katalog()
	{
		//====must be initiate in every view function====//
		$this->general->check_access();
		//===============================================//
		$this->data['title'] = "Katalog Produk";
		$this->load->view("order/katalog", $this->data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL)
	{
		switch ($param) {
			case 'katalog':
				$object                          = new stdClass();
				$object->limit                   = 8;
				$object->link                    = $this->router->fetch_module() . '/order/get/katalog';
				$object->start                   = ($this->uri->segment(5)) ? ($this->uri->segment(5) - 1) : 0;
				$object->segment                 = 5;
				$start_id                        = ($object->limit * $object->start);
				$end_id                          = ($start_id + $object->limit);
				$material                        = (isset($_POST['search']) ? $_POST['search'] : NULL);
				$type                            = (isset($_POST['type']) && $_POST['type'] !== "0" ? $_POST['type'] : NULL);
				$idnrk                           = (isset($_POST['mesin']) && $_POST['mesin'] !== "0" ? $_POST['mesin'] : NULL);
				$data_all                        = $this->dmasternusira->get_all_bom("open", NULL, NULL, $material, NULL, $type, $idnrk);
				$data_page                       = $this->dmasternusira->get_all_bom("open", $end_id, $start_id, $material, NULL, $type, $idnrk);
				$object->total_records           = count($data_all);
				$config['use_page_numbers']      = true;
				$config['use_global_url_suffix'] = true;
				$config['url_suffix']            = "javascipt:void(0)";
				$paging                          = $this->pagination($object, $config);
				$paging["results"]               = $data_page;
				echo json_encode($paging);
				break;
			case 'material':
				$type      = (isset($_POST['type']) ? $_POST['type'] : NULL);
				$idnrk     = (isset($_POST['parent']) ? $_POST['parent'] : NULL);
				$itnum     = (isset($_POST['itnum']) ? $_POST['itnum'] : NULL);
				$matnr     = (isset($_POST['kode']) ? $_POST['kode'] : NULL);
				$data_page = $this->dmasternusira->get_all_bom("open", NULL, NULL, NULL, $matnr, $type, $idnrk, $itnum);
				$i         = 0;
				foreach ($data_page as $dt) {
					$data_page[$i]->child = $this->dmasternusira->get_all_bom("open", NULL, NULL, NULL, NULL, NULL, $matnr, NULL);
				}
				if (isset($_POST['no_pi'])) {
					$plant                 = explode("/", $_POST['no_pi'])[2];
					$data_page[$i]->ongkir = $this->get_data_engkir("open", $plant, "array");
				}
				echo json_encode($data_page);
				break;
			case 'budget':
				$this->get_budget();
				break;
			case 'count_budget':
				$this->get_count_budget_avail();
				break;
			case 'detail':
				$this->get_detail_pi();
				break;
			case 'ongkir':
				$this->get_data_engkir();
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set($param = NULL)
	{
		switch ($param) {
			case 'PO':
				$this->createPO();
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function save($param = NULL)
	{
		switch ($param) {
			case 'order':
				$this->save_order();
				break;
			case 'approve':
				$this->save_approval();
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

	private function check_acces_pi($uri)
	{
		$param = str_replace("/k-air/", "", $uri);
		if ($param == "")
			$param = "home";
		if (substr($param, -1) == "/")
			$param = rtrim($param, "/");
		$check = $this->dordernusira->get_data_menu(NULL, NULL, NULL, explode(",", base64_decode($this->session->userdata("-pi_menu-"))), $param);
		if (count($check) > 0) {
			return true;
		} else {
			show_404();
		}
	}

	private function get_detail_pi($conn = "open", $return = NULL)
	{
		$no_pi             = $_POST['no_pi'];
		$plant             = explode("/", $no_pi)[2];
		$pi_header         = $this->dordernusira->get_pi_header($conn, $plant, NULL, $no_pi);
		$pi_header         = $this->general->generate_encrypt_json($pi_header, array("tujuan_inv"));
		$pi_detail         = $this->dordernusira->get_pi_detail($conn, $no_pi);
		$pi_budget         = $this->dordernusira->get_pi_budget($conn, $no_pi);
		$pi_log            = $this->dordernusira->get_pi_log($conn, $no_pi);
		$pi_attachment     = $this->dordernusira->get_pi_attachment($conn, $no_pi);
		$pi_brt_acr        = $this->dordernusira->get_data_rekom_berita_acara($conn, $no_pi);
		$pi_total_rekom    = $this->dordernusira->get_data_total_rekom($conn, $no_pi);
		$data              = $pi_header;
		$data->attach      = $pi_attachment;
		$data->detail      = $pi_detail;
		$data->budget      = $pi_budget;
		$data->log         = $pi_log;
		$data->access      = (in_array(base64_encode($pi_header->status), $this->session->userdata('-pi_level-')) == true);
		$data->brt_acr     = $pi_brt_acr;
		$data->detail_nsw  = ($pi_header->kepada == base64_decode($this->session->userdata('-nik-')));
		$data->total_rekom = $pi_total_rekom;
		if ($return !== NULL) {
			return $data;
		}
		echo json_encode($data);
	}

	private function save_order()
	{
		$session = $this->set_session_param();

		$_POST['tujuan_inv'] = $this->generate->kirana_decrypt($_POST['tujuan_inv']);
		$_POST['pic_pemb']   = "Pabrik";
		$_POST['tanggal']    = $this->generate->regenerateDateFormat($_POST['tanggal']);
		$i                   = 0;
		foreach ($_POST['permin'] as $permin) {
			$_POST['req_deliver_date'][$i] = $this->generate->regenerateDateFormat($_POST['req_deliver_date'][$i]);
			$_POST['spes'][$i]             = htmlentities($_POST['spes'][$i]);
			$i++;
		}
		//=============================send data to K-AIR=============================//
		$respons = $this->send_data("invest/save_pi", $_POST);
		$respons = json_decode($respons);

		if ($respons == NULL) {
			$respons      = new stdClass();
			$respons->msg = 'Gagal membuat PI Nusira Workshop';
			$respons->sts = 'notOK';
		} else {
			$respons->link = $this->base_url_pi . 'home/validation?redirect=' . base64_encode("invest") . '&key=' . base64_encode(json_encode($session));
		}

		echo json_encode($respons);
		exit();
	}

	private function save_approval()
	{
		$session             = $this->set_session_param();
		$auto_generate_rekom = NULL;
		$recalculate         = false;
		$ongkir              = 0;
		$tahun               = explode("/", $_POST['no_pi'])[4];
		$plant               = explode("/", $_POST['no_pi'])[2];
		$message             = NULL;

		//===============================================================================================//
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$pi_data = $this->get_detail_pi(NULL, "array");

		//update action per detail
		$status      = (empty($_POST['status_detail']) ? NULL : explode(",", $_POST['status_detail']));
		$itnum       = (empty($_POST['itnum_detail']) ? NULL : explode(",", $_POST['itnum_detail']));
		$matnr       = (empty($_POST['matnr_detail']) ? NULL : explode(",", $_POST['matnr_detail']));
		$no          = (empty($_POST['no_detail']) ? NULL : explode(",", $_POST['no_detail']));
		$last_action = $this->dordernusira->get_last_action_pi(NULL, $_POST['no_pi']);
		for ($i = 0; $i < count($status); $i++) {
			if (trim($matnr[$i]) == "") {
				$ongkir += $pi_data->detail[$i]->total;
			}

			$data_row = array(
				"na"     => (($status[$i] == 1 || $last_action->action == 'assign') ? "n" : "y"),
				"del"    => (($status[$i] == 1 || $last_action->action == 'assign') ? "n" : "y"),
				"status" => ($status[$i] == 1 ? "approved" : "rejected")
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);

			$this->dgeneral->update("tbl_pi_detail", $data_row, array(
				array(
					'kolom' => 'no_pi',
					'value' => $_POST['no_pi']
				),
				array(
					'kolom' => 'no',
					'value' => $no[$i]
				),
				array(
					'kolom' => 'itnum',
					'value' => $itnum[$i]
				),
				array(
					'kolom' => 'matnr',
					'value' => $matnr[$i]
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

			//item rejected
			if ($status[$i] == 0 && $last_action->action !== 'assign') {
				$budget = $this->dordernusira->get_pi_budget(NULL, $_POST['no_pi'], NULL, NULL, $no[$i]);
				if ($budget) {
					foreach ($budget as $b) {
						//set remaining = budget
						$data_budget = array(
							"remaining" => $b->budget
						);
						$this->dgeneral->update("tbl_pi_budget", $data_budget, array(
							array(
								'kolom' => 'no_budget',
								'value' => $b->no_budget
							),
						));

						//set non active referensi budget & insert to log
						$data_refrensi_budget = $this->dgeneral->basic_column("delete");
						$this->dgeneral->update("tbl_pi_referensi_budget", $data_refrensi_budget, array(
							array(
								'kolom' => 'no_pi',
								'value' => $_POST['no_pi']
							),
							array(
								'kolom' => 'no_budget',
								'value' => $b->no_budget
							),
						));
						$data_refrensi_budget_log = array(
							"plant"                      => $b->plant,
							"no_pi"                      => $b->no_pi,
							"no_budget"                  => $b->no_budget,
							"no_detail"                  => $b->no_detail,
							"status_budget"              => $b->status_budget,
							"no_urut"                    => $b->no_urut,
							"value_budget_referensi"     => $b->value_budget_referensi,
							"remaining_budget_referensi" => $b->remaining_budget_referensi,
							"login_buat"                 => $b->login_buat_referensi,
							"tanggal_buat"               => $b->tanggal_buat_referensi,
							"login_edit"                 => $b->login_edit_referensi,
							"tanggal_edit"               => $b->tanggal_edit_referensi,
							"na"                         => "y",
							"del"                        => "y",
						);
						$this->dgeneral->insert("tbl_pi_referensi_budget_log", $data_refrensi_budget_log);
					}
				}

				//set delete rekom vendor item rejected
				$data_rekom_item_rejected = $this->dgeneral->basic_column("delete");
				$this->dgeneral->update("tbl_pi_rekom_vendor_detail", $data_rekom_item_rejected, array(
					array(
						'kolom' => 'no_pi',
						'value' => $_POST['no_pi']
					),
					array(
						'kolom' => 'no_detail_pi',
						'value' => $no[$i]
					),
				));

				$recalculate = true;
			}
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";

			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		} else {
			$this->dgeneral->commit_transaction();
		}
		//===============================================================================================//

		if (isset($_POST['action']) && $_POST['action'] == 'approve') {
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			$pi_data_last = $this->dordernusira->get_pi_header(NULL, explode("/", $_POST['no_pi'])[2], NULL, $_POST['no_pi']);
			//update div head vendor
			$div_head_vendor = (empty($_POST['div_head_vendor']) ? NULL : $_POST['div_head_vendor']);
			if ($div_head_vendor) {
				if ($pi_data_last && $pi_data_last->div_head_vendor !== NULL && $pi_data_last->div_head_vendor !== $div_head_vendor) {
					//reset is done recom
					$data_row_detail = array(
						"is_done_recom" => 0
					);
					$key_detail      = array(
						array(
							'kolom' => 'no_pi',
							'value' => $_POST['no_pi']
						),
						array(
							'kolom' => 'na',
							'value' => 'n'
						),
						array(
							'kolom' => 'del',
							'value' => 'n'
						)
					);
					$data_row_detail = $this->dgeneral->basic_column("update", $data_row_detail);
					$this->dgeneral->update("tbl_pi_detail", $data_row_detail, $key_detail);

					$pi_data = $this->get_detail_pi(NULL, "array");
				}

				//auto generate rekom vendor NSI
				if ($div_head_vendor == "nsw" && $pi_data->jml_sdh_rekom == 0) {
					$data_vendor = array(
						//header
						"user"             => explode("/", $_POST['no_pi'])[2],
						"no_pi"            => $_POST['no_pi'],
						"no_rekom"         => "",
						"desc"             => $pi_data->perihal,
						"tgl_pi"           => $pi_data->tanggal,
						"received"         => $pi_data->tanggal,
						"vendor"           => "Nusira Workshop",
						"currency"         => "idr",
						"harga_rekom"      => $pi_data->sum_detail,
						"tgl_analisa"      => $pi_data->tanggal,
						"tgl_harga_lama"   => NULL,
						"isSPK"            => "on",
						"analisa_proc"     => "-",
						"alasan_pemilihan" => "Kirana Megatara Subsidiaries",
						//content
						"jml_vendor"       => 1,
						"selected_vendor"  => 1,
						"vendor1"          => "Nusira Workshop",
						"ktp_vendor1"      => NULL,
						"hp_vendor1"       => NULL,
						"currency_vendor1" => "idr",
						"kurs_vendor1"     => 1,
						"discount_vendor1" => 0,
						"gtotal_vendor1"   => $pi_data->sum_detail,
						"stok_vendor1"     => "0",
						"tod_vendor1"      => "",
						"top_vendor1"      => "",
						"warranty_vendor1" => ""
					);

					$deskripsi_detail = array();
					$qty              = array();
					$harga_lama       = array();
					$harga_vendor     = array();
					$total_vendor     = array();
					foreach ($pi_data->detail as $dt) {
						$deskripsi_detail[] = $dt->no;
						$qty[]              = $dt->jumlah;
						$satuan[]           = $dt->satuan;
						$harga_lama[]       = $dt->harga;
						$harga_vendor[]     = $dt->harga;
						$total_vendor[]     = $dt->total;
					}
					$data_vendor["deskripsi_detail"] = $deskripsi_detail;
					$data_vendor["qty"]              = $qty;
					$data_vendor["satuan"]           = $satuan;
					$data_vendor["harga_lama"]       = $harga_lama;
					$data_vendor["harga_vendor1"]    = $harga_vendor;
					$data_vendor["total_vendor1"]    = $total_vendor;

					$respons             = $this->send_data("vendor/save_rekom", $data_vendor);
					$respons             = json_decode($respons);
					$auto_generate_rekom = true;
					if ($respons == NULL) {
						$auto_generate_rekom = false;
						$respons             = new stdClass();
						$respons->msg        = 'Gagal';
						$respons->sts        = 'notOK';
					}
				}

				$data_row_header = array(
					"div_head_vendor" => $div_head_vendor
				);

				if ($div_head_vendor == "nsw")
					$data_row_header['nsw_check'] = 1;
				else
					$data_row_header['nsw_check'] = 0;

				$data_row_header = $this->dgeneral->basic_column("update", $data_row_header);
				$this->dgeneral->update("tbl_pi_header", $data_row_header, array(
					array(
						'kolom' => 'no_pi',
						'value' => $_POST['no_pi']
					)
				));

				//auto delete rekom vendor NSI where change vendor from nusira to others
				if ($div_head_vendor !== "nsw" && $pi_data->jml_sdh_rekom > 0 || ($pi_data_last && $pi_data_last->div_head_vendor !== NULL && $pi_data_last->div_head_vendor !== $div_head_vendor)) {
					$key = array(
						array(
							'kolom' => 'no_pi',
							'value' => $_POST['no_pi']
						)
					);
					$this->dgeneral->delete("tbl_pi_rekom_vendor_header", $key);
					$this->dgeneral->delete("tbl_pi_rekom_vendor_content", $key);
					$this->dgeneral->delete("tbl_pi_rekom_vendor_detail", $key);
				}
			}

			//recalculate grand total rekom vendor
			if ($recalculate === true) {
				$rekom_header = $this->dordernusira->get_data_rekom_header(NULL, $_POST['no_pi'], $plant);
				if ($rekom_header) {
					foreach ($rekom_header as $h) {
						$new_total    = 0;
						$rekom_detail = $this->dordernusira->get_data_rekom_detail(NULL, $_POST['no_pi'], $plant, $h->no_rekom);
						if ($rekom_detail) {
							foreach ($rekom_detail as $dt) {
								$new_total += $dt->total;
							}
						}

						//update rekom_vendor_header & insert to log
						$data_rekom_header = array(
							"harga" => $new_total
						);
						$this->dgeneral->update("tbl_pi_rekom_vendor_header", $data_rekom_header, array(
							array(
								'kolom' => 'no_pi',
								'value' => $_POST['no_pi']
							),
						));
						$data_rekom_vendor_header_log = array(
							"plant"                => $h->plant,
							"no_pi"                => $h->no_pi,
							"no_rekom"             => $h->no_rekom,
							"desc_pekerjaan"       => $h->desc_pekerjaan,
							"tgl_pi"               => $h->tgl_pi,
							"tgl_received"         => $h->tgl_received,
							"vendor_rekomendasi"   => $h->vendor_rekomendasi,
							"kurs"                 => $h->kurs,
							"harga"                => $new_total,
							"tgl_analisa"          => $h->tgl_analisa,
							"tgl_harga_lama"       => $h->tgl_harga_lama,
							"is_spk"               => $h->is_spk,
							"analisa_proc"         => $h->analisa_proc,
							"reason_for_selection" => $h->reason_for_selection,
							"id_file"              => $h->id_file,
							"id_file_attach"       => $h->id_file_attach,
							"login_buat"           => $h->vendor_header_login_buat,
							"tanggal_buat"         => $h->vendor_header_tanggal_buat,
							"login_edit"           => $h->vendor_header_login_edit,
							"tanggal_edit"         => $h->vendor_header_tanggal_edit
						);
						$this->dgeneral->insert("tbl_pi_rekom_vendor_header_log", $data_rekom_vendor_header_log);

						$rekom_content = $this->dordernusira->get_data_rekom_content(NULL, $h->no_pi, $h->plant, $h->no_rekom);
						if ($rekom_content) {
							foreach ($rekom_content as $dt) {
								//update rekom_vendor_content & insert to log
								$data_rekom_content = array(
									"net_value" => $new_total
								);
								$this->dgeneral->update("tbl_pi_rekom_vendor_content", $data_rekom_content, array(
									array(
										'kolom' => 'no_pi',
										'value' => $_POST['no_pi']
									),
								));
								$data_rekom_vendor_content_log = array(
									"plant"                     => $dt->plant,
									"no_pi"                     => $dt->no_pi,
									"no_rekom"                  => $dt->no_rekom,
									"urut_vendor"               => $dt->urut_vendor,
									"nama_vendor"               => $dt->nama_vendor,
									"ktp_vendor"                => $dt->ktp_vendor,
									"hp_vendor"                 => $dt->hp_vendor,
									"currency"                  => $dt->currency,
									"nilai_kurs"                => $dt->nilai_kurs,
									"discount"                  => $dt->discount,
									"net_value"                 => $new_total,
									"stock_available"           => $dt->stock_available,
									"term_of_delivery_duration" => $dt->term_of_delivery_duration,
									"term_of_payment"           => $dt->term_of_payment,
									"warranty"                  => $dt->warranty,
									"is_selected"               => $dt->is_selected,
									"login_buat"                => $dt->login_buat,
									"tanggal_buat"              => $dt->tanggal_buat,
									"login_edit"                => $dt->login_edit,
									"tanggal_edit"              => $dt->tanggal_edit,
								);
								$this->dgeneral->insert("tbl_pi_rekom_vendor_content_log", $data_rekom_vendor_content_log);
							}
						}
					}
				}
			}

			if ($this->dgeneral->status_transaction() === false || (isset($respons) && $respons->sts !== "OK") || $auto_generate_rekom === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";

				if ((isset($respons) && $respons->sts !== "OK") || $auto_generate_rekom === false)
					$msg = $msg . " (generate vendor recommendation failed)";

				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			} else {
				$this->dgeneral->commit_transaction();
			}
			$this->general->closeDb();
		}

		//=============================send data to K-AIR=============================//
		if ($auto_generate_rekom !== false) {
			$message = array();
			if (in_array(base64_encode("Finance Controller"), $this->session->userdata("-pi_nama_role-")) == true && $_POST['action'] == 'approve' && $pi_data->tipe_pi == NULL && $pi_data->no_po == NULL && $pi_data->no_so == NULL && $pi_data->div_head_vendor == "nsw") {
				$message = $this->createPO("return");
			}

			if (empty($message) || ($message && $message['sts'] == "OK")) {
				$respons = $this->send_data("invest/save_approval", $_POST);
				$respons = json_decode($respons);

				if ($respons == NULL) {
					$respons         = new stdClass();
					$respons->msg    = 'Gagal Approval';
					$respons->sts    = 'notOK';
					$respons->status = 'Failed';
				}

				$respons->link = $this->base_url_pi . 'home/validation?redirect=' . base64_encode("invest") . '&key=' . base64_encode(json_encode($session));

				if (isset($_POST['action']) && $_POST['action'] == 'approve' && $respons && $respons->status !== 'Failed' && $respons->status == 'finish') {
					//==create budget sisa==//
					$pi_budget = $this->dordernusira->get_pi_budget("open", $_POST['no_pi']);
					$no        = "sisa/" . $plant . "/1/" . $tahun;
					$this->general->connectDbPortal();
					$this->dgeneral->begin_transaction();
					$budget_sisa = $this->dordernusira->get_data_budget(NULL, $tahun, $plant, $no, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "sisa", NULL, NULL, NULL, 'y', NULL);

					if (empty($budget_sisa)) {
						$tujuan_inv  = $this->dordernusira->get_data_master(NULL, "tbl_pi_mtujuan_inv", NULL, array(
							array(
								"method" => "where",
								"kolom"  => "tujuan_inv",
								"value"  => "Repair dan Maintenance"
							),
						));
						$dept        = $this->dordernusira->get_data_master(NULL, "tbl_pi_mdepart", NULL, array(
							array(
								"method" => "where",
								"kolom"  => "departemen",
								"value"  => "Pabrik"
							),
						));
						$tipe_inv    = $this->dordernusira->get_data_master(NULL, "tbl_pi_mtipe_inv", NULL, array(
							array(
								"method" => "where",
								"kolom"  => "tipe_inv",
								"value"  => "Repair dan Maintenance"
							),
						));
						$kategori    = $this->dordernusira->get_data_master(NULL, "tbl_pi_mkategori", NULL, array(
							array(
								"method" => "where",
								"kolom"  => "kategori",
								"value"  => "Repair dan Maintenance"
							),
						));
						$jenis_asset = $this->dordernusira->get_data_master(NULL, "tbl_pi_mjenis_asset", NULL, array(
							array(
								"method" => "where",
								"kolom"  => "jenis_asset",
								"value"  => "Repair dan Maintenance"
							),
						));

						$create = array(
							"plant"            => $plant,
							"no_budget"        => $no,
							"investasi"        => "sisa budget plant " . $plant . " tahun " . $tahun,
							"id_mtujuan_inv"   => $tujuan_inv ? $tujuan_inv[0]->id_mtujuan_inv : 1, //2,
							"spesifikasi"      => "sisa budget plant " . $plant . " tahun " . $tahun,
							"detail_pekerjaan" => "sisa budget plant " . $plant . " tahun " . $tahun,
							"justifikasi"      => "sisa budget plant " . $plant . " tahun " . $tahun,
							"id_mdepart"       => $dept ? $dept[0]->id_mdepart : 1, //2,
							"id_mtipe_inv"     => $tipe_inv ? $tipe_inv[0]->id_mtipe_inv : 1, //2,
							"id_mkategori"     => $kategori ? $kategori[0]->id_mkategori : 1, //5,
							"masa_manfaat"     => 1,
							"id_mjenis_asset"  => $jenis_asset ? $jenis_asset[0]->id_mjenis_asset : 1, //14,
							"budget"           => 0,
							"remaining"        => 0,
							"start_date"       => $tahun . '-01-01',
							"end_date"         => $tahun . '-12-01',
							"nilai_depresiasi" => 0,
							"coa"              => '0',
							"is_maintenance"   => 'y',
							"status"           => 1,
							"login_buat"       => 0,
							"tanggal_buat"     => date("Y-m-d H:i:s"),
							"login_edit"       => 0,
							"tanggal_edit"     => date("Y-m-d H:i:s"),
							"is_available"     => "y",
							"na"               => "n",
							"del"              => "n",
						);
						$this->dgeneral->insert("tbl_pi_budget", $create);
						$budget_sisa = $this->dordernusira->get_data_budget(NULL, $tahun, $plant, $no, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "sisa", NULL, NULL, NULL, 'y', NULL);
					}
					if ($pi_budget && $budget_sisa) {
						$budget_awal     = ($budget_sisa->budget != $budget_sisa->remaining ? $budget_sisa->remaining : $budget_sisa->budget);
						$remaining_awal  = $budget_sisa->remaining;
						$remaining       = $budget_sisa->remaining;
						$addition        = 0;
						$no_detail       = NULL;
						$remaining_hasil = $budget_sisa->remaining;
						$ongkir_budget   = false;
						$use_sisa        = false;

						foreach ($pi_budget as $key => $budg) {
							if ($budg->no_budget == $budget_sisa->no_budget) {
								$type           = "reduce";
								$remaining_awal = $remaining;

								$matnr = preg_replace('/\s/', '', $budg->matnr);
								if (empty($matnr)) {
									$ongkir_budget = true;
									$type          = "add + reduce";
									$addition      = 0;
									$remaining     = (($remaining - $ongkir) <= 0 ? 0 : ($remaining - $ongkir));
								} else {
									$remaining = ($budg->remaining_budget_referensi < 0 ? 0 : $budg->remaining_budget_referensi);
									$addition  = ($budg->remaining_budget_referensi < 0 ? 0 : $budg->remaining_budget_referensi);
								}
								$use_sisa = true;
							} else {
								$type = "add";
								if ($budg->remaining <= 0) {
									$remaining_awal = $remaining;
									$remaining      += ($budg->remaining_budget_referensi < 0 ? 0 : $budg->remaining_budget_referensi);
									$addition       = ($budg->remaining_budget_referensi < 0 ? 0 : $budg->remaining_budget_referensi);
								}
							}

							$array = array(
								"remaining" => 0
							);
							$this->dgeneral->update("tbl_pi_budget", $array, array(
								array(
									'kolom' => 'no_budget',
									'value' => $budg->no_budget
								)
							));

							//insert log maintenance budget
							$data_after = array(
								"no_budget_sisa"  => $budget_sisa->no_budget,
								"no_budget_add"   => $budg->no_budget,
								"no_pi"           => $budg->no_pi,
								"budget_addition" => $addition, //penambahan ke sisa
								"before"          => $remaining_awal, //sebelum penambahan
								"after"           => $remaining, //setelah penambahan
								"type"            => $type,
								"tanggal"         => date("Y-m-d H:i:s"),
							);
							$this->dgeneral->insert("tbl_pi_budget_maintenance_log", $data_after);
						}

						if ($ongkir_budget == false) {
							$update = array(
								"remaining"    => (($remaining - $ongkir) <= 0 ? 0 : ($remaining - $ongkir)),
								"budget"       => (($remaining - $ongkir) <= 0 ? 0 : ($remaining - $ongkir))
							);
							if ($budget_sisa->budget != $budget_sisa->remaining) { //apabila budget sisa sedang dipakai sebagai referensi PI lain
								if ($use_sisa == false)
									unset($update['budget']);
								else
									$update['budget'] = $update['remaining'];
							}
							//								$update = $this->dgeneral->basic_column("update", $update);

							$data_after = array(
								"no_budget_sisa" => $budget_sisa->no_budget,
								"no_pi"          => $_POST['no_pi'],
								"before"         => ($remaining <= 0 ? 0 : $remaining),
								"after"          => (($remaining - $ongkir) <= 0 ? 0 : ($remaining - $ongkir)),
								"type"           => "reduce",
								"tanggal"        => date("Y-m-d H:i:s"),
							);
							$this->dgeneral->insert("tbl_pi_budget_maintenance_log", $data_after);
						} else {
							$update = array(
								"remaining"    => $remaining,
								"budget"       => $remaining
							);
						}
						$update = $this->dgeneral->basic_column("update", $update);
						$this->dgeneral->update("tbl_pi_budget", $update, array(
							array(
								'kolom' => 'no_budget',
								'value' => $no
							)
						));

						//update referensi budget sisa pada pi on progress
						$budget_sisa_on_progress = $this->dordernusira->get_pi_budget(NULL, NULL, $budget_sisa->no_budget, NULL, NULL, NULL, array("finish", "drop", "delete"));
						if ($budget_sisa_on_progress) {
							foreach ($budget_sisa_on_progress as $dt) {
								if ($_POST['no_pi'] !== $dt->no_pi) {
									$log = array(
										"plant"                      => $dt->plant,
										"no_pi"                      => $dt->no_pi,
										"no_detail"                  => $dt->no_detail,
										"no_budget"                  => $dt->no_budget,
										"status_budget"              => $dt->status_budget,
										"no_urut"                    => $dt->no_urut,
										"value_budget_referensi"     => $dt->value_budget_referensi,
										"remaining_budget_referensi" => $dt->remaining_budget_referensi,
										"login_buat"                 => $dt->login_buat_referensi,
										"tanggal_buat"               => $dt->tanggal_buat_referensi,
										"login_edit"                 => $dt->login_edit_referensi,
										"tanggal_edit"               => $dt->tanggal_edit_referensi,
										"na"                         => 'n',
										"del"                        => 'n'
									);
									$this->dgeneral->insert('tbl_pi_referensi_budget_log', $log);

									$array = array(
										"value_budget_referensi"     => $dt->value_budget_referensi + ($remaining - $ongkir),
										"remaining_budget_referensi" => $dt->remaining_budget_referensi + ($remaining - $ongkir)
									);

									$this->dgeneral->update("tbl_pi_referensi_budget", $array, array(
										array(
											'kolom' => 'no_budget',
											'value' => $dt->no_budget
										),
										array(
											'kolom' => 'no_pi',
											'value' => $dt->no_pi
										),
									));
								}
							}
						}
					}

					if ($this->dgeneral->status_transaction() === false || (isset($respons) && $respons->sts !== "OK")) {
						$this->dgeneral->rollback_transaction();
						$msg = "Periksa kembali data yang dimasukkan";
						$sts = "NotOK";
					} else {
						$this->dgeneral->commit_transaction();
						$msg = "Data berhasil ditambahkan";
						$sts = "OK";
					}
					$this->general->closeDb();

					if ($message)
						$msg .= ".\n" . $message['msg'];

					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				} else {
					if ($message)
						$respons->msg .= ".\n" . $message['msg'];
					echo json_encode($respons);
					exit();
				}
			} else {
				echo json_encode($message);
				exit();
			}
		}
	}

	private function generate_no_pi()
	{
		$separator = "/";
		$plant     = $this->session->userdata("-pi_plant_code-");
		$semester  = date('m') > 6 ? "II" : "I";
		$year      = date('Y');
		$no        = (count($this->dordernusira->get_pi_no_pi("open", $plant, $semester, $year)) + 1);
		return "PI" . $separator . $no . $separator . $plant . $separator . $semester . $separator . $year;
	}

	private function get_budget()
	{
		if (isset($_GET['q'])) {
			if (isset($_GET['no_pi'])) {
				$pi   = $_GET['no_pi'];
				$year = explode("/", $pi)[4];
			} else {
				$pi   = NULL;
				$year = date("Y");
			}

			if (isset($_GET['edit']))
				$edit = $_GET['edit'];
			else
				$edit = NULL;

			if (isset($_GET['not_in']) && $_GET['not_in'] !== "")
				$not_in = explode(",", $_GET['not_in']);
			else
				$not_in = NULL;

			$is_main = $_GET['is_main'];
			if ($is_main == 'no') {
				// cek nilai budget | jika dibawah 10juta maka kasih validasi yang dibawah sama dengan 10 juta
				//get_budget by no_budget
				//					$cek_range    = $this->dordernusira->get_data_budget("open", $year, $this->session->userdata("-pi_plant_code-"), $_GET['nomor_budget'][0], NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'y');
				// $cek_range    = $this->dordernusira->get_data_budget("open", $year, $this->session->userdata("-pi_plant_code-"), $_GET['nomor_budget'][0], NULL, NULL, NULL, NULL, NULL, NULL, NULL, "sisa", NULL, NULL, NULL, 'y');
				$range_budget = isset($_GET['nilai_pi']) ? ($_GET['nilai_pi'] > 10000000 ? 'tbl_pi_budget.budget >' : 'tbl_pi_budget.budget <=') : NULL;
			}
			$switch_range = $is_main === 'no' ? $range_budget : NULL;
			$data         = $this->dordernusira->get_data_budget("open", $year, $this->session->userdata("-pi_plant_code-"), NULL, $_GET['q'], 'yes', $pi, $edit, $_GET['tipe_pi'], $is_main, $switch_range, "sisa", NULL, NULL, $not_in, 'y');
			$data_json    = array(
				"total_count"        => count($data),
				"incomplete_results" => false,
				"items"              => $data
			);
			echo json_encode($data_json);
		}
	}

	private function get_count_budget_avail()
	{
		if (isset($_POST['no_pi'])) {
			$pi   = $_POST['no_pi'];
			$exp  = explode("/", $pi);
			$year = $exp[count($exp) - 1];
		} else {
			$pi   = NULL;
			$year = date("Y");
		}

		if (isset($_POST['year']))
			$year = $_POST['year'];

		if (isset($_POST['edit']))
			$edit = $_POST['edit'];
		else $edit = NULL;

		if (isset($_POST['tipe_pi']))
			$tipe = $_POST['tipe_pi'];
		else $tipe = NULL;

		if (isset($_POST['no_budget']))
			$no_budget = $_POST['no_budget'];
		else $no_budget = NULL;

		if (isset($_POST['plant']))
			$plant = $_POST['plant'];
		else $plant = $this->session->userdata("-pi_plant_code-");

		$data = $this->dordernusira->get_data_budget("open", $year, $plant, NULL, NULL, 'yes', $pi, $edit, $tipe, NULL, NULL, "sisa", NULL, NULL, $no_budget, 'y');
		echo json_encode($data);
	}

	private function createPO($data_return = NULL)
	{
		$this->connectSAP("ERP_310");

		$data = $this->get_detail_pi("open", "array");
		if (!isset($_POST['no_pi']) || !$data || (isset($data) && $data->no_po !== NULL)) {
			$return = array('sts' => 'notOK', 'msg' => 'Silahkan periksa kembali data yang anda masukkan');
			if (isset($data) && $data->no_po !== NULL)
				$return['msg'] = 'PI tersebut sudah memiliki PO dengan nomer ' . $data->no_po;
			echo json_encode($return);
			exit();
		}

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$datetime = date("Y-m-d H:i:s");

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$table = array();

			foreach ($data->detail as $dt) {
				$durasi = 0;
				if ($dt->nsw_durasi_mgg !== NULL)
					$durasi = $dt->nsw_durasi_mgg;

				$pi_finish_date = date_format(date_create(date('Y-m-d')), "Y-m-d");
				$actual_date_so = date('Ymd', strtotime($pi_finish_date . ' +' . ($durasi * 7) . ' days'));

				$detail  = array(
					"MATNR" => $dt->matnr,
					"KDMAT" => $dt->kdmat,
					"TXZ01" => $dt->perm_invest,
					"MENGE" => $dt->jumlah,
					"MEINS" => $dt->satuan,
					"NETPR" => $dt->harga,
					"PEINH" => 1,
					"WAERS" => "IDR",
					"EEIND" => date_format(date_create($dt->req_deliv_date), "Ymd"),
					"EDATU" => $actual_date_so,
					"KNTTP" => $dt->acc_assign,
					"ANLKL" => $dt->asset_class ? $dt->asset_class : '',
					"KOSTL" => $dt->cost_center,
					"TXT50" => $dt->asset_desc ? $dt->asset_desc : '',
					"SAKNR" => $dt->gl_account,
				);
				$table[] = $detail;
			}

			$param = array(
				array("IMPORT", "I_EKORG", $data->plant),
				array("IMPORT", "I_LIFNR", "NSI2-NT"),
				array("IMPORT", "I_BEDAT", date_format(date_create($data->tanggal), "Ymd")),
				array("IMPORT", "I_PINUM", $data->no_pi),
				array("IMPORT", "I_UNAME", base64_decode($this->session->userdata("-pi_nama-"))),
				array("IMPORT", "I_DATUM", date("Ymd")),
				array("IMPORT", "I_EKGRP", "WKS"),
				array("TABLE", "T_DATAPO", $table),
				array("TABLE", "T_RETURN", array()),
				array("EXPORT", "E_RETURN", array()),
				array("EXPORT", "E_EBELN", array()),
				array("EXPORT", "E_VBELN", array()),
			);

			$result = $this->data['sap']->callFunction("Z_RFC_PURCHORDER_ME21N_CREATE", $param);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && $result["E_RETURN"]["TYPE"] == "" && !empty($result["T_RETURN"])) {
				$type    = array();
				$message = array();
				foreach ($result["T_RETURN"] as $return) {
					$type[]    = $return['TYPE'];
					$message[] = $return['MESSAGE'];
				}

				if (in_array('E', $type) === true) {
					$data_row_log = array(
						'app'           => 'DATA RFC Create PO Automatic K-AIR NSW',
						'rfc_name'      => 'Z_RFC_PURCHORDER_ME21N_CREATE',
						'log_code'      => implode(" , ", $type),
						'log_status'    => 'Gagal',
						'log_desc'      => "Create PO Failed [T_RETURN]: " . implode(" , ", $message),
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);
				} else {
					$data_row_log = array(
						'app'           => 'DATA RFC Create PO Automatic K-AIR NSW',
						'rfc_name'      => 'Z_RFC_PURCHORDER_ME21N_CREATE',
						'log_code'      => implode(" , ", $type),
						'log_status'    => 'Berhasil',
						'log_desc'      => implode(" , ", $message),
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);
				}

				//update data SO + PO => K-AIR
				$no_po = NULL;
				$no_so = NULL;
				if ($result["E_EBELN"] !== "")
					$no_po = $result["E_EBELN"];
				if ($result["E_VBELN"] !== "")
					$no_so = $result["E_VBELN"];
				$data_row = array(
					"no_so" => $no_so,
					"no_po" => $no_po,
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_pi_header", $data_row, array(
					array(
						'kolom' => 'no_pi',
						'value' => $data->no_pi
					)
				));

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
				} else {
					$this->dgeneral->commit_transaction();
					$this->general->closeDb();
					$msg = $data_row_log['log_desc'];
					$sts = "OK";
					if (in_array('E', $type) === true)
						$sts = "NotOK";

					if ($data_return) {
						return array('sts' => $sts, 'msg' => $msg);
					} else {
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
				}
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC Create PO Automatic K-AIR NSW',
					'rfc_name'      => 'Z_RFC_PURCHORDER_ME21N_CREATE',
					'log_code'      => $result["E_RETURN"]["TYPE"],
					'log_status'    => 'Gagal',
					'log_desc'      => "Create PO Failed: " . $result["E_RETURN"]["MESSAGE"],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC Create PO Automatic K-AIR NSW',
				'rfc_name'      => 'Z_RFC_PURCHORDER_ME21N_CREATE',
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
		} else {
			$this->dgeneral->commit_transaction();
			$msg = $data_row_log['log_desc'];
			$sts = "NotOK";
		}
		$this->general->closeDb();

		if ($data_return) {
			return array('sts' => $sts, 'msg' => $msg);
		} else {
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
	}

	private function get_data_engkir($conn = NULL, $plant = NULL, $return = NULL)
	{
		if (isset($_POST['plant']))
			$plant = $_POST['plant'];

		$select = array(
			array(
				"select" => "CONVERT(VARCHAR, CONVERT(MONEY, kbetr), 1) as ongkir_money"
			)
		);
		$param  = array(
			array(
				'method' => 'where',
				'kolom'  => 'werks',
				'value'  => $plant
			),
			array(
				'method' => 'where',
				'kolom'  => 'datab <=',
				'value'  => date('Y-m-d')
			),
			array(
				'method' => 'order',
				'kolom'  => 'datab',
				'value'  => 'DESC'
			),
		);
		$data   = $this->dordernusira->get_data_master($conn, "tbl_pi_master_ongkir", $select, $param);

		if ($return !== NULL) {
			return $data;
		} else {
			echo json_encode($data);
			exit();
		}
	}
}
