<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : MASTER DEPO
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmaster extends CI_Model{
	function get_data_dokumen($conn = NULL, $id_dokumen = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $ck_id_dokumen = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_master_jenis_dokumen.*');
		$this->db->select('CASE
								WHEN tbl_master_jenis_dokumen.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_master_jenis_dokumen');
		if ($id_dokumen !== NULL) {
			$this->db->where('tbl_master_jenis_dokumen.id_dokumen', $id_dokumen);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_master_jenis_dokumen.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_master_jenis_dokumen.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_master_jenis_dokumen.nama', $nama);
		}
		if ($ck_id_dokumen !== NULL) {
			$this->db->where("tbl_master_jenis_dokumen.id_dokumen!='$ck_id_dokumen'");
		}
		$this->db->order_by("tbl_master_jenis_dokumen.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
}
?>