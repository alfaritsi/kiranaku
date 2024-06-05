<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : BANK SPECIMEN
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmasterbank extends CI_Model{
	function get_data_role($conn = NULL, $id_role = NULL, $active = NULL, $deleted = 'n', $level = NULL, $nama = NULL, $ck_id_role = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_bank_role.*');
		$this->db->select('CASE
								WHEN tbl_bank_role.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_bank_role');
		if ($id_role !== NULL) {
			$this->db->where('tbl_bank_role.id_role', $id_role);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_bank_role.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_bank_role.del', $deleted);
		}
		if ($level !== NULL) {
			$this->db->where('tbl_bank_role.level', $level);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_bank_role.nama', $nama);
		}
		if ($ck_id_role !== NULL) {
			$this->db->where("tbl_bank_role.id_role!='$ck_id_role'");
		}
		$this->db->order_by("tbl_bank_role.level", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_dokumen($conn = NULL, $id_dokumen = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $ck_id_dokumen = NULL, $id_data_temp = NULL, $jenis_pengajuan = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if ($id_data_temp !== NULL) {
			$this->db->select("(select top 1 tbl_bank_data_dokumen_temp.url from tbl_bank_data_dokumen_temp where tbl_bank_data_dokumen_temp.id_data_temp='$id_data_temp' and tbl_bank_dokumen.id_dokumen=tbl_bank_data_dokumen_temp.id_dokumen) as url_dokumen");
		}
		$this->db->select('tbl_bank_dokumen.*');
		$this->db->select('CASE
								WHEN tbl_bank_dokumen.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_bank_dokumen');
		if ($id_dokumen !== NULL) {
			$this->db->where('tbl_bank_dokumen.id_dokumen', $id_dokumen);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_bank_dokumen.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_bank_dokumen.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_bank_dokumen.nama', $nama);
		}
		if ($ck_id_dokumen !== NULL) {
			$this->db->where("tbl_bank_dokumen.id_dokumen!='$ck_id_dokumen'");
		}
		if ($jenis_pengajuan !== NULL) {
			$this->db->where("CHARINDEX(''''+CONVERT(varchar(100), '$jenis_pengajuan')+'''',''''+REPLACE(tbl_bank_dokumen.jenis_pengajuan, RTRIM(','),''',''')+'''') > 0");
		}
		$this->db->order_by("tbl_bank_dokumen.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
}
?>