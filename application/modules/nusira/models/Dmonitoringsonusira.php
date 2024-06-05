<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * @application  : Monitoring SO - Model
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dmonitoringsonusira extends CI_Model
{
	public function get_spk($conn = NULL, $params = array())
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$all      = isset($params['all']) ? $params['all'] : false;
		$id       = isset($params['id']) ? $params['id'] : NULL;
		$no_so    = isset($params['no_so']) ? $params['no_so'] : NULL;
		$no_mat   = isset($params['no_mat']) ? $params['no_mat'] : NULL;
		$no_pos   = isset($params['no_pos']) ? $params['no_pos'] : NULL;
		$order_by = isset($params['order_by']) ? $params['order_by'] : 'tanggal_buat desc';

		if (isset($order_by))
			$this->db->order_by($order_by);

		$this->db->from('tbl_pi_spk');

		if (!$all) {
			$this->db->where('tbl_pi_spk.na', 'n');
			$this->db->where('tbl_pi_spk.del', 'n');
		}

		if (isset($id))
			$this->db->where('tbl_pi_spk.id_spk_so', $id);
		if (isset($no_so))
			$this->db->where('tbl_pi_spk.no_so', $no_so);
		if (isset($no_mat))
			$this->db->where('tbl_pi_spk.no_mat', $no_mat);
		if (isset($no_pos))
			$this->db->where('tbl_pi_spk.no_pos', $no_pos);

		$query = $this->db->get();

		if (isset($params['single_row']) && $params['single_row'])
			$result = $query->row();
		else
			$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();

		return $result;
	}

	public function get_booked($conn = NULL, $params = array())
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$all      = isset($params['all']) ? $params['all'] : false;
		$id       = isset($params['id']) ? $params['id'] : NULL;
		$no_so    = isset($params['no_so']) ? $params['no_so'] : NULL;
		$no_mat   = isset($params['no_mat']) ? $params['no_mat'] : NULL;
		$no_pos   = isset($params['no_pos']) ? $params['no_pos'] : NULL;
		$order_by = isset($params['order_by']) ? $params['order_by'] : 'tanggal_buat desc';

		if (isset($order_by))
			$this->db->order_by($order_by);

		$this->db->from('tbl_pi_booked_freestock');

		if (!$all) {
			$this->db->where('tbl_pi_booked_freestock.na', 'n');
			$this->db->where('tbl_pi_booked_freestock.del', 'n');
		}

		if (isset($id))
			$this->db->where('tbl_pi_booked_freestock.id_spk_so', $id);
		if (isset($no_so))
			$this->db->where('tbl_pi_booked_freestock.no_so', $no_so);
		if (isset($no_mat))
			$this->db->where('tbl_pi_booked_freestock.no_mat', $no_mat);
		if (isset($no_pos))
			$this->db->where('tbl_pi_booked_freestock.no_pos', $no_pos);

		$query = $this->db->get();

		if (isset($params['single_row']) && $params['single_row'])
			$result = $query->row();
		else
			$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();

		return $result;
	}

	public function get_demand($conn = NULL, $params = array())
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$no_so    = isset($params['no_so']) ? $params['no_so'] : NULL;
		$no_mat   = isset($params['no_mat']) ? $params['no_mat'] : NULL;


		$this->db->from('vw_pi_monitoring_demand');

		if (isset($no_mat))
			$this->db->where('matnr', $no_mat);

		if (isset($no_so))
			$this->db->where('no_so <>', $no_so);

		$this->db->order_by('plant', 'no_so');

		$query = $this->db->get();

		if (isset($params['single_row']) && $params['single_row'])
			$result = $query->row();
		else
			$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();

		return $result;
	}

	public function get_history($conn = NULL, $params = array())
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$no_so    = isset($params['no_so']) ? $params['no_so'] : NULL;
		$no_mat   = isset($params['no_mat']) ? $params['no_mat'] : NULL;
		$no_pos   = isset($params['no_pos']) ? $params['no_pos'] : NULL;


		$this->db->from('vw_pi_monitoring_history');

		if (isset($no_mat))
			$this->db->where('no_mat', $no_mat);

		if (isset($no_so))
			$this->db->where('no_so', $no_so);

		if (isset($no_pos))
			$this->db->where('no_pos', $no_pos);

		$this->db->order_by('pstng_date');

		$query = $this->db->get();

		if (isset($params['single_row']) && $params['single_row'])
			$result = $query->row();
		else
			$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();

		return $result;
	}

	function get_data_so_datatables($conn = NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL, $pabrik = NULL, $status = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select('plant,
								   no_pi,
								   no_po,
								   no_so,
								   convert(varchar(10), tanggal, 104) tanggal,
								   tanggal as tanggal_ori,
								   pabrik_pemesan,
								   jml_item_pi,
								   jml_item_pi_sudah_spk,
								   status');
		$this->datatables->from('vw_pi_monitoring_so');
		if ($tanggal_awal !== NULL && $tanggal_akhir !== NULL) {
			$this->datatables->where("tanggal BETWEEN '" . $tanggal_awal . "' AND '" . $tanggal_akhir . "'");
		}
		if ($pabrik !== NULL) {
			$this->datatables->where_in("plant", $pabrik);
		}
		if ($status !== NULL && $status !== "semua") {
			$this->datatables->where("status", $status);
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		return $return;
	}

	function get_data_so($conn = NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL, $pabrik = NULL, $status = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('plant,
								   no_pi,
								   no_po,
								   no_so,
								   tanggal,
								   pabrik_pemesan,
								   jml_item_pi,
								   jml_item_pi_sudah_spk,
								   status');
		$this->db->from('vw_pi_monitoring_so');
		if ($tanggal_awal !== NULL && $tanggal_akhir !== NULL) {
			$this->db->where("tanggal BETWEEN '" . $tanggal_awal . "' AND '" . $tanggal_akhir . "'");
		}
		if ($pabrik !== NULL) {
			$this->db->where_in("plant", $pabrik);
		}
		if ($status !== NULL && $status !== "semua") {
			$this->db->where("status", $status);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_mts_datatables($conn = NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL, $status = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select('tbl_pi_spk_mts.no_io,
								   convert(varchar(10), tbl_pi_spk_mts.prod_schedule_start, 104) prod_schedule_start,
								   convert(varchar(10), tbl_pi_spk_mts.prod_schedule_end, 104) prod_schedule_end,
								   tbl_pi_spk_mts.no_mat,
								   tbl_pi_rfc_bom.MAKTX,
								   tbl_pi_spk_mts.no_pos,
								   tbl_pi_spk_mts.prod_uom,
								   tbl_pi_spk_mts.prod_qty');
		$this->datatables->from('tbl_pi_spk_mts');
		$this->datatables->join('tbl_pi_rfc_bom', 'tbl_pi_spk_mts.no_mat = tbl_pi_rfc_bom.MATNR', 'inner');

		if ($tanggal_awal !== NULL && $tanggal_akhir !== NULL) {
			$where = "(prod_schedule_start BETWEEN '" . $tanggal_awal . "' AND '" . $tanggal_akhir . "'
							OR prod_schedule_end BETWEEN '" . $tanggal_awal . "' AND '" . $tanggal_akhir . "')";
			$this->datatables->where($where);
		}
		if ($status !== NULL && $status !== "semua") {
			$this->datatables->where("status", $status);
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		return $return;
	}

	function get_data_mts($conn = NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL, $status = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_pi_spk_mts.no_io,
								   tbl_pi_spk_mts.prod_schedule_start,
								   tbl_pi_spk_mts.prod_schedule_end,
								   tbl_pi_spk_mts.no_mat + ' - ' + tbl_pi_rfc_bom.MAKTX no_mat,
								   tbl_pi_spk_mts.no_pos,
								   tbl_pi_spk_mts.prod_uom,
								   tbl_pi_spk_mts.prod_qty');
		$this->db->from('tbl_pi_spk_mts');
		$this->db->join('tbl_pi_rfc_bom', 'tbl_pi_spk_mts.no_mat = tbl_pi_rfc_bom.MATNR', 'inner');

		if ($tanggal_awal !== NULL && $tanggal_akhir !== NULL) {
			$this->db->where("prod_schedule_start BETWEEN '" . $tanggal_awal . "' AND '" . $tanggal_akhir . "'");
		}
		if ($status !== NULL && $status !== "semua") {
			$this->db->where("status", $status);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_report_dashboard($conn = NULL, $filter = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select('no_pi,
									   no_so,
									   req_deliv_date,
									   plan_deliv_date,
									   no_io,
									   no_mat,
									   prod_qty,
									   prod_uom,
									   prod_schedule_start,
									   prod_schedule_end,
									   mat_doc,
									   pstng_date');
		$this->datatables->from('vw_pi_report_dashboard_nsw');
		if ($filter) {
			foreach ($filter as $key => $val) {
				if ($val == "mto") {
					$this->datatables->or_where('no_pi IS NOT NULL');
				}
				if ($val == "mts") {
					$this->datatables->or_where('no_pi IS NULL');
				}
			}
		}
		//			$this->db->order_by('mat_doc ASC, plan_deliv_date ASC, no_so ASC');

		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		return $return;
	}

	function get_data_report_dashboard_detail($conn = NULL, $orderid = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select('*');
		$this->datatables->from('vw_pi_report_dashboard_detail');
		$this->datatables->where('no_io_spk', $orderid);
		//			$this->db->order_by('mat_doc ASC, plan_deliv_date ASC, no_so ASC');

		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		return $return;
	}

	function get_mts_progress_qty($conn = NULL, $matnr = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		//			$result = array();
		$this->db->select('ISNULL(SUM(tbl_pi_spk_mts.prod_qty),0) as tot_qty');
		$this->db->from('tbl_pi_spk_mts');
		$this->db->join('tbl_pi_rfc_bom', 'tbl_pi_spk_mts.no_mat = tbl_pi_rfc_bom.MATNR', 'inner');
		//
		if ($matnr !== NULL) {
			$this->db->where("tbl_pi_spk_mts.no_mat", $matnr);
		}

		$this->db->where("tbl_pi_spk_mts.pstng_date IS NULL");
		$this->db->where("tbl_pi_spk_mts.mat_doc IS NULL");
		//
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
}
