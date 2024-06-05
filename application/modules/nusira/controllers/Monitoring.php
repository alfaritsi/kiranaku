<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

include_once APPPATH . "modules/nusira/controllers/BaseControllers.php";

/**
 * @application  : Monitoring SO - Controller
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. Akhmad Syaiful Yamang (8347) 20190301
 *    - method get for dashboard NSW
 * 2. Akhmad Syaiful Yamang (8347) 20190308
 *    - change all the process
 * 3. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Monitoring extends BaseControllers
{
	public function __construct()
	{
		parent::__construct();
		$this->data['module'] = "NUSIRA WORKSHOP";
		$this->load->model('dmonitoringsonusira');
		$this->load->model('dtransaksinusira');
		$this->load->model('dordernusira');
		$this->load->model('dmasternusira');
	}

	public function get_list_io()
	{
		$gen = $this->generate;

		$input = $this->input;

		$no_so  = $input->post('no_so');
		$no_mat = $input->post('no_mat');
		$no_pos = $input->post('no_pos');

		if (isset($no_so) and isset($no_pos) and isset($no_mat)) {
			$spks   = $this->dmonitoringsonusira->get_spk(
				NULL,
				array(
					'no_so'  => $no_so,
					'no_mat' => $no_mat,
					'no_pos' => $no_pos,
				)
			);
			$result = array();

			foreach ($spks as $spk) {
				$result[] = array(
					'start' => $gen->generateDateFormat($spk->prod_schedule_start),
					'end'   => $gen->generateDateFormat($spk->prod_schedule_end),
					'qty'   => $spk->prod_qty,
					'uom'   => $spk->prod_uom,
					'no_io' => $spk->no_io,
					'status_produksi' => ($spk->mat_doc ? '<span class="label label-success">FINISH</span>' : '<span class="label label-warning">ON PROGRESS</span>'),
					'no_gr' => $spk->mat_doc ? $spk->mat_doc : ""
				);
			}

			echo json_encode(array('sts' => 'OK', 'data' => $result));
		} else {
			echo json_encode(array('sts' => 'NotOK', 'msg' => 'Periksa kembali data Sales Order yang direquest.'));
		}
	}

	//==========================================================//
	public function data($param = NULL)
	{
		switch ($param) {
			case 'so':
				//====must be initiate in every view function====//
				$this->general->check_access();
				//===============================================//
				$this->data['title'] = "Surat Perintah Kerja";

				$tanggal_awal                = date_create()->sub(DateInterval::createFromDateString('1 year'));
				$tanggal_akhir               = date_create();
				$this->data['tanggal_awal']  = $tanggal_awal;
				$this->data['tanggal_akhir'] = $tanggal_akhir;
				$this->data['pabrik']        = $this->general->get_master_plant();

				$this->load->view('monitoring/data-so', $this->data);
				break;
			case 'mts':
				//====must be initiate in every view function====//
				$this->general->check_access();
				//===============================================//

				$this->data['title'] = "Surat Perintah Kerja (MTS)";

				$tanggal_awal                = date_create()->sub(DateInterval::createFromDateString('1 year'));
				$tanggal_akhir               = date_create();
				$this->data['tanggal_awal']  = $tanggal_awal;
				$this->data['tanggal_akhir'] = $tanggal_akhir;

				$this->load->view('monitoring/data-mts', $this->data);
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function cetak($jenis = NULL, $no_io = NULL)
	{
		$cetak = $this->get_cetak(array(
			"connect" => TRUE,
			"jenis" => $jenis,
			"no_io" => $no_io,
			"return" => "array",
		));

		if ($cetak) {
			$this->load->library('Mypdf');
			$this->mypdf->AddPage('P', 'A4');
			$this->mypdf->setAutoPageBreak(0, 0.5);
			$this->mypdf->AddFont('times', '', 'times.php');
			$this->mypdf->SetFont('Times', 'B', 14);
			$this->mypdf->Cell(0, 10, 'Surat Perintah Kerja (SPK)' . ($jenis == 'mts' ? ' Make to Stock' : ''), 0, 0, 'C');
			$this->mypdf->Ln(10);
			$this->mypdf->SetFont('times', '', 8);

			$this->mypdf->Cell(35, 5, 'No PO', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$this->mypdf->Cell(80, 5, $cetak[0]->no_po, 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Cell(50, 5, 'No:', 0, 0);
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(35, 5, 'No IO', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$this->mypdf->Cell(80, 5, $cetak[0]->no_io, 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Cell(50, 5, 'Dikeluarkan Oleh:', 'LTR', 0, 'C');
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(35, 5, 'No SO', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$this->mypdf->Cell(80, 5, $cetak[0]->no_so, 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Cell(50, 5, '', 'LBR', 0, 'C');
			$this->mypdf->Ln(5);
			//
			$this->mypdf->Cell(35, 5, 'Mesin', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$cetak[0]->MAKTX_ITEM = utf8_decode($cetak[0]->MAKTX_ITEM);
			$cetak[0]->MAKTX_ITEM = html_entity_decode($cetak[0]->MAKTX_ITEM);
			$cetak[0]->MAKTX_ITEM =  iconv('UTF-8', 'windows-1252', $cetak[0]->MAKTX_ITEM);
			$this->mypdf->Cell(80, 5, $cetak[0]->MATNR_ITEM . " (" . html_entity_decode($cetak[0]->MAKTX_ITEM) . ")", 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Cell(50, 5, 'Diterima Oleh:', 'LTR', 0, 'C');
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(35, 5, 'Qty', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$this->mypdf->Cell(80, 5, (empty($cetak[0]->prod_qty) ? 0 : $cetak[0]->prod_qty), 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Cell(50, 5, '', 'LR', 0, 'C');
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(35, 5, 'Tanggal Mulai', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$this->mypdf->Cell(80, 5, $cetak[0]->prod_schedule_start, 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Cell(50, 5, 'Diinput Oleh:', 'LTR', 0, 'C');
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(35, 5, 'Tanggal Selesai', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$this->mypdf->Cell(80, 5, $cetak[0]->prod_schedule_end, 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Cell(50, 5, '', 'LBR', 0, 'C');
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(35, 5, 'Pabrik Tujuan', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$this->mypdf->Cell(80, 5, $cetak[0]->WERKS, 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Cell(50, 5, 'No. GR:', 0, 0);
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(35, 5, 'PIC', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$this->mypdf->Cell(80, 5, '', 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(35, 5, 'Production', 0, 0, 'L');
			$this->mypdf->Cell(5, 5, ':', 0, 0, 'L');
			$this->mypdf->Cell(80, 5, (isset($cetak[0]->prod_inhouse) && $cetak[0]->prod_inhouse == 0 ? 'Afiliasi' : 'In House'), 'B', 0, 'L');
			$this->mypdf->Cell(20, 5, '', 0, 0, 'L');
			$this->mypdf->Ln(5);

			$this->mypdf->Ln(10);

			$this->mypdf->SetFont('Times', 'B', 8);
			$this->mypdf->Cell(190, 5, 'Mesin/ Sparepart', 1, 0, 'C');
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(10, 10, 'No.', 1, 0, 'C');
			$this->mypdf->Cell(20, 10, 'Kode Material', 1, 0, 'C');
			$this->mypdf->Cell(60, 10, 'Mesin/ Sparepart', 1, 0, 'C');
			$this->mypdf->Cell(10, 10, 'Jumlah', 1, 0, 'C');
			$this->mypdf->Cell(10, 10, 'Satuan', 1, 0, 'C');
			$this->mypdf->Cell(20, 10, 'Proses', 1, 0, 'C');
			$this->mypdf->Cell(30, 5, 'Pengambilan Ke-1', 1, 0, 'C');
			$this->mypdf->Cell(30, 5, 'Pengambilan Ke-2', 1, 0, 'C');
			$this->mypdf->Ln(5);
			$this->mypdf->SetFont('Times', '', 8);
			$this->mypdf->Cell(130, 5, '', 0, 0, 'C');
			$this->mypdf->Cell(15, 5, 'Tanggal', 1, 0, 'C');
			$this->mypdf->Cell(15, 5, 'Jumlah', 1, 0, 'C');
			$this->mypdf->Cell(15, 5, 'Tanggal', 1, 0, 'C');
			$this->mypdf->Cell(15, 5, 'Jumlah', 1, 0, 'C');
			$this->mypdf->Ln(5);
			//data
			$no = 0;
			foreach ($cetak as $dt) {
				$no++;
				$this->mypdf->Cell(10, 5, $no, 1, 0, 'C');
				$this->mypdf->Cell(20, 5, $dt->IDNRK, 1, 0, 'L');
				$dt->MAKTX = utf8_decode($dt->MAKTX);
				$dt->MAKTX = html_entity_decode($dt->MAKTX);
				$dt->MAKTX =  iconv('UTF-8', 'windows-1252', $dt->MAKTX);
				$this->mypdf->Cell(60, 5, html_entity_decode($dt->MAKTX), 1, 0, 'L');
				$this->mypdf->Cell(10, 5, (empty($cetak[0]->prod_qty) ? 0 : $cetak[0]->prod_qty) * floatval(str_replace(',', '.', str_replace('.', '', $dt->KMPMG))), 1, 0, 'R');
				$this->mypdf->Cell(10, 5, $dt->KMPME, 1, 0, 'C');
				$this->mypdf->Cell(20, 5, '-', 1, 0, 'L');
				$this->mypdf->Cell(15, 5, '', 1, 0, 'C');
				$this->mypdf->Cell(15, 5, '', 1, 0, 'C');
				$this->mypdf->Cell(15, 5, '', 1, 0, 'C');
				$this->mypdf->Cell(15, 5, '', 1, 0, 'C');
				$this->mypdf->Ln(5);
			}
			$this->mypdf->Ln(15);
			//footer
			$this->mypdf->Cell(40, 5, 'Menyerahkan:', 1, 0, 'C');
			$this->mypdf->Cell(70, 5, 'Pengecekan Kualitas:', 1, 0, 'C');
			$this->mypdf->Cell(25, 5, 'Menerima', 1, 0, 'C');
			$this->mypdf->Cell(25, 5, 'Menyetujui', 1, 0, 'C');
			$this->mypdf->Cell(30, 5, 'Mengetahui', 1, 0, 'C');
			$this->mypdf->Ln(5);
			$this->mypdf->Cell(40, 15, '', 1, 0, 'C');
			$this->mypdf->Cell(70, 15, '', 1, 0, 'C');
			$this->mypdf->Cell(25, 15, '', 1, 0, 'C');
			$this->mypdf->Cell(25, 15, '', 1, 0, 'C');
			$this->mypdf->Cell(30, 15, '', 1, 0, 'C');
			$this->mypdf->Ln(15);
			$this->mypdf->Cell(40, 5, '', 1, 0, 'C');
			$this->mypdf->Cell(70, 5, '', 1, 0, 'C');
			$this->mypdf->Cell(25, 5, '(Kepala Gudang)', 1, 0, 'C');
			$this->mypdf->Cell(25, 5, '(Manager)', 1, 0, 'C');
			$this->mypdf->Cell(30, 5, '(Direktur)', 1, 0, 'C');
			$this->mypdf->Ln(5);

			$this->mypdf->Output();
		} else {
			echo "Data Material Produksi dengan No IO tersebut tidak ditemukan";
		}
	}

	public function get($param = NULL)
	{
		switch ($param) {
			case 'so':
				$status        = isset($_POST['status']) ? $_POST['status'] : NULL;
				$tanggal_awal  = isset($_POST['tanggal_awal']) ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL;
				$tanggal_akhir = isset($_POST['tanggal_akhir']) ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL;
				$pabrik        = isset($_POST['pabrik']) ? $_POST['pabrik'] : NULL;
				$this->get_data_so($status, $tanggal_awal, $tanggal_akhir, $pabrik, "ignited");
				break;
			case 'count_so':
				$status       = isset($_POST['status']) ? $_POST['status'] : NULL;
				$tanggal_awal = isset($_POST['tanggal_awal']) ? date_create($_POST['tanggal_awal']) : NULL;
				$list         = $this->get_data_so($status, $tanggal_awal, NULL, NULL, NULL, "array");
				echo json_encode(array("count" => count($list)));
				break;
			case 'item_so':
				$this->get_detail_so();
				break;
			case 'item_bom':
				$this->get_detail_bom();
				break;
			case 'item_material':
				$this->get_item_material();
				break;
			case 'mts':
				$status        = isset($_POST['status']) ? $_POST['status'] : NULL;
				$tanggal_awal  = isset($_POST['tanggal_awal']) ? $this->generate->regenerateDateFormat($_POST['tanggal_awal']) : NULL;
				$tanggal_akhir = isset($_POST['tanggal_akhir']) ? $this->generate->regenerateDateFormat($_POST['tanggal_akhir']) : NULL;
				$this->get_data_mts($status, $tanggal_awal, $tanggal_akhir, "ignited");
				break;
			case 'mts_progress':
				$this->get_mts_progress_qty();
				break;
			case 'list_history':
				$this->get_list_history();
				break;
			case 'list_demand':
				$this->get_list_demand();
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set($param = NULL)
	{
		switch ($param) {
			case 'spk':
				$this->get_data_io();
				break;
			case 'booked':
				$this->get_data_booked_freestock();
				break;
			case 'spk_mts':
				$this->get_data_io_mts();
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
	private function get_data_so($status, $tanggal_awal, $tanggal_akhir, $pabrik, $ignited = NULL, $return = NULL)
	{
		if ($ignited) {
			$data = $this->dmonitoringsonusira->get_data_so_datatables('open', $tanggal_awal, $tanggal_akhir, $pabrik, $status);
			echo $data;
		} else {
			$data = $this->dmonitoringsonusira->get_data_so('open', $tanggal_awal, $tanggal_akhir, $pabrik, $status);
			if ($return == "array")
				return $data;
			else
				echo json_encode($data);
		}
	}

	private function get_detail_so()
	{
		$datetime = date("Y-m-d H:i:s");
		$this->connectSAP("ERP_310");
		// $this->connectSAP("ERP_KMTEMP");
		$data = array();
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$params = array(
				array("EXPORT", "E_RETURN", array()),
				array("IMPORT", "I_VBELN", $_POST['no_so']),
				array("TABLE", "T_ORDERHDR", array()),
				array("TABLE", "T_ORDERDTL", array()),
				array("TABLE", "T_PODETAIL", array()),
			);

			$result = $this->data['sap']->callFunction("Z_RFC_WORKSHOP_SALESORDER_GET", $params);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && $result["E_RETURN"]["TYPE"] == "" && !empty($result["T_ORDERDTL"]) && !empty($result["T_ORDERDTL"])) {
				$data = array();
				$header = array();

				foreach ($result['T_ORDERHDR'] as $dt) {
					$header = array(
						'plant' => $dt['KUNNR']
					);
				}

				foreach ($result['T_ORDERDTL'] as $dt) {
					$spks = $this->dmonitoringsonusira->get_spk(
						NULL,
						array(
							'no_so'  => $dt['VBELN'],
							'no_mat' => $dt['MATNR'],
							'no_pos' => $dt['POSNR']
						)
					);
					$books = $this->dmonitoringsonusira->get_booked(
						NULL,
						array(
							'no_so'  => $dt['VBELN'],
							'no_mat' => $dt['MATNR'],
							'no_pos' => $dt['POSNR']
						)
					);
					$totalTersimpan = 0;
					foreach ($spks as $spk) {
						$totalTersimpan += $spk->prod_qty;
					}
					foreach ($books as $book) {
						$totalTersimpan += $book->booked_qty;
					}

					$pi_detail = $this->dtransaksinusira->get_pi_detail(
						array(
							"connect" => FALSE,
							"no_pi" => $dt['PINUM'],
							"matnr" => $dt['MATNR']
						)
					);

					$data[$dt['POSNR']] = array(
						'header'                => $header,
						'no_so'                 => $dt['VBELN'],
						'no_pos'                => $dt['POSNR'],
						'no_mat'                => $dt['MATNR'],
						'no_pi'                 => $dt['PINUM'],
						'nama_mat'              => $dt['MAKTX'],
						'qty_ord'               => floatval($this->generate->format_nilai('SAPSQL', $dt['KWMENG'])),
						'qty_stock'             => floatval($this->generate->format_nilai('SAPSQL', $dt['LABST'])),
						'qty_reserve'           => floatval($this->generate->format_nilai('SAPSQL', ($dt['KALAB'] >= $dt['KWMENG'] ? ($dt['KALAB'] - $totalTersimpan) : $dt['KALAB']))),
						'qty_ord_left'          => floatval($this->generate->format_nilai('SAPSQL', $dt['KWMENG'])) - ($dt['KALAB'] >= $dt['KWMENG'] ? $dt['KALAB'] : $totalTersimpan),
						'qty_spk'               => $totalTersimpan,
						'uom'                   => $dt['VRKME'],
						'no_io'                 => $dt['AUFNR'],
						'tanggal_plan_delivery' => ($dt['VDATU'] == '00000000') ? NULL : date_create($dt['VDATU'])->format('d.m.Y'),
					);

					foreach ($result['T_PODETAIL'] as $dtpo) {
						$data[$dt['POSNR']]['detail'] = array(
							'no_po'                => $dtpo['EBELN'],
							'no_pos'               => $dtpo['EBELP'],
							'tanggal_req_delivery' => ($pi_detail && $pi_detail->req_deliv_date) ? date_create($pi_detail->req_deliv_date)->format('d.m.Y') : NULL,
						);
					}
				}
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC Create PO Automatic K-AIR NSW',
					'rfc_name'      => 'Z_RFC_PURCHORDER_ME21N_CREATE',
					'log_code'      => $result["E_RETURN"]["TYPE"],
					'log_status'    => 'Gagal',
					'log_desc'      => "Create PO Failed: " . $result["E_RETURN"]["MESSAGE"],
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC GET ITEM SO',
				'rfc_name'      => 'Z_RFC_WORKSHOP_SALESORDER_GET',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Connecting Failed: " . $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);

			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
		} else {
			$this->dgeneral->commit_transaction();
		}
		$this->general->closeDb();

		echo json_encode($data);
		exit();
	}

	private function get_list_history()
	{
		$gen = $this->generate;

		$input = $this->input;

		$no_so  = $input->post('no_so');
		$no_mat = $input->post('no_mat');
		$no_pos = $input->post('no_pos');

		if (isset($no_so) and isset($no_pos) and isset($no_mat)) {
			$spks   = $this->dmonitoringsonusira->get_history(
				NULL,
				array(
					'no_so'  => $no_so,
					'no_mat' => $no_mat,
					'no_pos' => $no_pos,
				)
			);
			$result = array();

			foreach ($spks as $spk) {
				$result[] = array(
					'doc_from'   => $spk->doc_from,
					'start' 	 => $gen->generateDateFormat($spk->prod_schedule_start),
					'end'   	 => $gen->generateDateFormat($spk->prod_schedule_end),
					'qty'   	 => $spk->prod_qty,
					'uom'   	 => $spk->prod_uom,
					'no_io' 	 => $spk->no_io,
					'mat_doc' 	 => $spk->mat_doc ? $spk->mat_doc : "",
					'doc_year' 	 => $spk->doc_year ? $spk->doc_year : "",
					'doc_date' 	 => $spk->doc_date ? $spk->doc_date : "",
					'pstng_date' => $spk->pstng_date ? $spk->pstng_date : ""
				);
			}

			echo json_encode(array('sts' => 'OK', 'data' => $result));
		} else {
			echo json_encode(array('sts' => 'NotOK', 'msg' => 'Periksa kembali data Sales Order yang direquest.'));
		}
	}

	private function get_data_io()
	{
		$datetime = date("Y-m-d H:i:s");
		$this->connectSAP("ERP_310");
		// $this->connectSAP("ERP_KMTEMP");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$user      = $this->data['user'];
			$no_io     = NULL;
			$isHouse = ($this->input->post('isHouse') == "1" ? "X" : "");
			$rfcParams = array(
				array("IMPORT", "I_GSBER", 'NSI2'),
				array("IMPORT", "I_MATNR", $this->input->post('no_mat')),
				array("IMPORT", "I_KTEXT", substr($this->input->post('nama_mat'), 0, 20)),
				array("IMPORT", "I_PRCTR", 21222),
				array("IMPORT", "I_EBELN", $this->input->post('no_po')),
				array("IMPORT", "I_VBELN", $this->input->post('no_so')),
				array("IMPORT", "I_POSNR", str_pad($this->input->post('no_pos'), 6, '0', STR_PAD_LEFT)),
				array("IMPORT", "I_UNAME", substr($user->nama, 0, 12)),
				array("IMPORT", "I_DATUM", date('Ymd')),
				array("IMPORT", "I_GAMNG", $this->input->post('qty')),
				array("IMPORT", "I_PRDIH", $isHouse),
				array("EXPORT", "E_AUFNR", NULL),
				array("EXPORT", "E_RETURN", NULL),
			);

			$result = $this->data['sap']->callFunction(
				"Z_RFC_CO_PRODORDER_KKF1_CREATE",
				$rfcParams
			);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && $result["E_RETURN"]["TYPE"] == "") {
				$data = array(
					'no_so'               => $this->input->post('no_so'),
					'no_mat'              => $this->input->post('no_mat'),
					'no_pos'              => $this->input->post('no_pos'),
					'no_po'               => $this->input->post('no_po'),
					'no_io'               => $result['E_AUFNR'],
					'prod_schedule_start' => $this->generate->regenerateDateFormat($this->input->post('start')),
					'prod_schedule_end'   => $this->generate->regenerateDateFormat($this->input->post('end')),
					'prod_uom'            => $this->input->post('uom'),
					'prod_qty'            => $this->input->post('qty'),
					'spk_dibuat'          => 1,
					'prod_inhouse'		  => $this->input->post('isHouse')
				);

				$data = $this->dgeneral->basic_column('insert_full', $data);

				$this->dgeneral->insert('tbl_pi_spk', $data);

				//================================SAVE ALL================================//
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg = "Pembuatan SPK dengan nomor " . $result['E_AUFNR'] . " dari SAP berhasil";
					$sts = "OK";
				}
				$this->general->closeDb();

				$return = array('sts' => $sts, 'msg' => $msg, 'no_io' => $result['E_AUFNR'], 'param' => $rfcParams);
				echo json_encode($return);
				exit();
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC Create IO / SPK',
					'rfc_name'      => 'Z_RFC_CO_PRODORDER_KKF1_CREATE',
					'log_code'      => $result["E_RETURN"]["TYPE"],
					'log_status'    => 'Gagal',
					'log_desc'      => "Create IO Failed: " . $result["E_RETURN"]["MESSAGE"],
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC Create IO / SPK',
				'rfc_name'      => 'Z_RFC_CO_PRODORDER_KKF1_CREATE',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Connecting Failed: " . $status,
				'executed_by'   => 0,
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

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		exit();
	}
	//==========================================================//

	private function get_data_booked_freestock()
	{
		$datetime = date("Y-m-d H:i:s");
		$this->connectSAP("ERP_310");
		// $this->connectSAP("ERP_KMTEMP");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$user      = $this->data['user'];
			$no_io     = NULL;
			$rfcParams = array(
				array("IMPORT", "I_VBELN", $this->input->post('no_so')),
				array("IMPORT", "I_POSNR", str_pad($this->input->post('no_pos'), 6, '0', STR_PAD_LEFT)),
				array("IMPORT", "I_MATNR", $this->input->post('no_mat')),
				// array("IMPORT", "I_BUDAT", '20190401'),
				array("IMPORT", "I_BUDAT", date('Ymd')),
				array("IMPORT", "I_MENGE", $this->input->post('qty')),
				array("EXPORT", "E_MBLNR", NULL),
				array("EXPORT", "E_MJAHR", NULL),
				array("EXPORT", "E_RETURN", NULL),
				// array("TABLES", "T_RETURN", NULL),
			);
			$result = $this->data['sap']->callFunction(
				"Z_RFC_WORKSHOP_BOOK_FREESTOCK",
				$rfcParams
			);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && $result["E_RETURN"]["TYPE"] == "" && $result["E_MBLNR"] != "" && empty($result["T_RETURN"]["TYPE"])) {
				$data = array(
					'no_so'               => $this->input->post('no_so'),
					'no_mat'              => $this->input->post('no_mat'),
					'no_pos'              => $this->input->post('no_pos'),
					'booked_uom'          => $this->input->post('uom'),
					'booked_qty'          => $this->input->post('qty'),
					'mat_doc'          	  => $result["E_MBLNR"],
					'doc_year'            => $result["E_MJAHR"],
					'doc_date'            => $datetime,
					'pstng_date'          => $datetime,
				);

				// var_dump($result["T_RETURN"]); die();

				$data = $this->dgeneral->basic_column('insert_full', $data);

				$this->dgeneral->insert('tbl_pi_booked_freestock', $data);

				//================================SAVE ALL================================//
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg = "Proses Booked Free Stock dengan nomor " . $result['E_MBLNR'] . " dari SAP berhasil";
					$sts = "OK";
				}
				$this->general->closeDb();

				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC Create Booked Free Stock',
					'rfc_name'      => 'Z_RFC_WORKSHOP_BOOK_FREESTOCK',
					'log_code'      => ($result["E_RETURN"]["TYPE"] ? $result["E_RETURN"]["TYPE"] : $result["T_RETURN"]["TYPE"]),
					'log_status'    => 'Gagal',
					'log_desc'      => "Create Booked Failed: " . ($result["E_RETURN"]["MESSAGE"] ? $result["E_RETURN"]["MESSAGE"] : $result["T_RETURN"]["MESSAGE"]),
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC Create Booked Free Stock',
				'rfc_name'      => 'Z_RFC_WORKSHOP_BOOK_FREESTOCK',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Connecting Failed: " . $status,
				'executed_by'   => 0,
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

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		exit();
	}
	//==========================================================//

	private function get_data_io_mts()
	{
		$datetime = date("Y-m-d H:i:s");
		$this->connectSAP("ERP_310");
		// $this->connectSAP("ERP_KMTEMP");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$user      = $this->data['user'];
			$no_io     = NULL;
			$rfcParams = array(
				array("IMPORT", "I_GSBER", 'NSI2'),
				array("IMPORT", "I_MATNR", $this->input->post('no_mat')),
				array("IMPORT", "I_KTEXT", substr($this->input->post('nama_mat'), 0, 20)),
				array("IMPORT", "I_PRCTR", 21222),
				array("IMPORT", "I_UNAME", substr($user->nama, 0, 12)),
				array("IMPORT", "I_DATUM", date('Ymd')),
				array("IMPORT", "I_GAMNG", $this->input->post('qty')),
				array("EXPORT", "E_AUFNR", NULL),
				array("EXPORT", "E_RETURN", NULL),
			);

			$result = $this->data['sap']->callFunction(
				"Z_RFC_CO_PRODORDER_MAKETOSTOCK",
				$rfcParams
			);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && $result["E_RETURN"]["TYPE"] == "") {
				$data = array(
					'no_mat'              => $this->input->post('no_mat'),
					'no_pos'              => 10,
					'no_io'               => $result['E_AUFNR'],
					'prod_schedule_start' => $this->generate->regenerateDateFormat($this->input->post('start')),
					'prod_schedule_end'   => $this->generate->regenerateDateFormat($this->input->post('end')),
					'prod_uom'            => $this->input->post('uom'),
					'prod_qty'            => $this->input->post('qty'),
					'spk_dibuat'          => 1
				);

				$data = $this->dgeneral->basic_column('insert_full', $data);

				$this->dgeneral->insert('tbl_pi_spk_mts', $data);

				//================================SAVE ALL================================//
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg = "Pembuatan MTS dengan nomor " . $result['E_AUFNR'] . " dari SAP berhasil";
					$sts = "OK";
				}
				$this->general->closeDb();

				$return = array('sts' => $sts, 'msg' => $msg, 'no_io' => $result['E_AUFNR']);
				echo json_encode($return);
				exit();
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC Create IO / MTS',
					'rfc_name'      => 'Z_RFC_CO_PRODORDER_MAKETOSTOCK',
					'log_code'      => $result["E_RETURN"]["TYPE"],
					'log_status'    => 'Gagal',
					'log_desc'      => "Create IO Failed: " . $result["E_RETURN"]["MESSAGE"],
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC Create IO / MTS',
				'rfc_name'      => 'Z_RFC_CO_PRODORDER_MAKETOSTOCK',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Connecting Failed: " . $status,
				'executed_by'   => 0,
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

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		exit();
	}

	private function get_detail_bom()
	{
		$datetime = date("Y-m-d H:i:s");
		$this->connectSAP("ERP_310");
		// $this->connectSAP("ERP_KMTEMP");
		$data = array();
		$this->general->connectDbPortal();

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$params = array(
				array("EXPORT", "RETURN", array()),
				array("IMPORT", "I_MATNR", $_POST['matnr']),
				array("TABLE", "T_BOM", array())
			);

			$result = $this->data['sap']->callFunction("Z_RFC_WORKSHOP_SPK_READ_STOCK", $params);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && $result["RETURN"]["TYPE"] == "" && !empty($result["T_BOM"])) {
				$data = array();

				foreach ($result["T_BOM"] as $key => $dt) {
					// if($dt['IDNRK'] != "ASH-0032"){
					$data[] = array(
						'SPOSN' => $dt['SPOSN'],
						'IDNRK' => $dt['IDNRK'],
						'MAKTX' => htmlentities($dt['MAKTX']), //iconv('iso-8859-15', 'utf-8', $dt['MAKTX']),
						// 'MAKTX' => utf8_decode(iconv('iso-8859-15', 'utf-8', $dt['MAKTX'])),
						'KMPMG' => $dt['KMPMG'],
						'KALAB' => $dt['KALAB'],
						'KMPME'	=> $dt['KMPME']
					);
					// }
				}
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC GET ITEM BOM',
					'rfc_name'      => 'Z_RFC_WORKSHOP_SPK_READ_STOCK',
					'log_code'      => $result["RETURN"]["TYPE"],
					'log_status'    => 'Gagal',
					'log_desc'      => "Get Data Failed: " . $result["RETURN"]["MESSAGE"],
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC GET ITEM BOM',
				'rfc_name'      => 'Z_RFC_WORKSHOP_SPK_READ_STOCK',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Connecting Failed: " . $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);

			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		$this->general->closeDb();

		echo json_encode($data);
		exit();
	}
	// ================================================================================

	private function get_item_material($return = NULL)
	{
		$data = $this->dmasternusira->get_all_bom("open", NULL, NULL, $_POST['material'], NULL, NULL, NULL, NULL);
		if ($return == "array")
			return $data;
		else
			echo json_encode($data);
	}

	private function get_data_mts($status, $tanggal_awal, $tanggal_akhir, $ignited = NULL, $return = NULL)
	{
		if ($ignited) {
			$data = $this->dmonitoringsonusira->get_data_mts_datatables('open', $tanggal_awal, $tanggal_akhir, $status);
			echo $data;
		} else {
			$data = $this->dmonitoringsonusira->get_data_mts('open', $tanggal_awal, $tanggal_akhir, $status);
			if ($return == "array")
				return $data;
			else
				echo json_encode($data);
		}
	}

	private function get_mts_progress_qty($return = NULL)
	{
		$data = $this->dmonitoringsonusira->get_mts_progress_qty("open", $_POST['matnr']);
		if ($return == "array")
			return $data;
		else
			echo json_encode($data);
	}

	private function get_list_demand()
	{
		$gen = $this->generate;

		$input = $this->input;

		$no_so = $input->post('no_so');
		$no_mat = $input->post('no_mat');

		if (isset($no_mat)) {
			$sos   = $this->dmonitoringsonusira->get_demand(
				NULL,
				array(
					'no_so'  => $no_so,
					'no_mat' => $no_mat,
				)
			);
			$result = array();

			foreach ($sos as $so) {
				$result[] = array(
					'plant' => $so->plant,
					'no_so' => $so->no_so,
					'qty'   => $so->qty
				);
			}

			echo json_encode(array('sts' => 'OK', 'data' => $result));
		} else {
			echo json_encode(array('sts' => 'NotOK', 'msg' => 'Periksa kembali data Sales Order yang direquest.'));
		}
	}

	private function get_cetak($param = NULL)
	{
		$result = $this->dtransaksinusira->get_data_cetak(array(
			"connect" => $param['connect'],
			"jenis" => $param['jenis'],
			"no_io" => $param['no_io'],
		));
		if (isset($param['return']) && $param['return'] == "array") {
			return $result;
		} else {
			echo json_encode($result);
		}
	}
	//=========================================================//
}
