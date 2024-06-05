<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : TAKSASI BOKAR
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmastertaksasi extends CI_Model{
	function get_data_tahap($conn = NULL, $id_tahap = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $ck_id_tahap = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_taksasi_tahap.*');
		$this->db->select('CASE
								WHEN tbl_taksasi_tahap.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_taksasi_tahapxx');
		if ($id_tahap !== NULL) {
			$this->db->where('tbl_taksasi_tahap.id_tahap', $id_tahap);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_taksasi_tahap.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_taksasi_tahap.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_taksasi_tahap.nama', $nama);
		}
		if ($ck_id_tahap !== NULL) {
			$this->db->where("tbl_taksasi_tahap.id_tahap!='$ck_id_tahap'");
		}
		
		$this->db->order_by("tbl_taksasi_tahap.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_nilai($conn = NULL, $id_nilai = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $ck_id_nilai = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_taksasi_nilai.*');
		$this->db->select('CASE
								WHEN tbl_taksasi_nilai.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_taksasi_nilai');
		if ($id_nilai !== NULL) {
			$this->db->where('tbl_taksasi_nilai.id_nilai', $id_nilai);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_taksasi_nilai.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_taksasi_nilai.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_taksasi_nilai.nama', $nama);
		}
		if ($ck_id_nilai !== NULL) {
			$this->db->where("tbl_taksasi_nilai.id_nilai!='$ck_id_nilai'");
		}
		
		$this->db->order_by("tbl_taksasi_nilai.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
}
?>