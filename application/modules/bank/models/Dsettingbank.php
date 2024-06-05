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

class Dsettingbank extends CI_Model{
	function get_data_user_autocomplete($cari = NULL) {
		$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.nik as id');
		$this->db->select('tbl_karyawan.nik');
		$this->db->select('tbl_karyawan.nama');
		$this->db->from('tbl_user');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan AND tbl_karyawan.na = \'n\'  AND tbl_karyawan.del = \'n\'', 'inner');
		if ($cari !== NULL) {
			$this->db->where("(tbl_karyawan.nik like '%".$cari."%' or tbl_karyawan.nama like '%".$cari."%') ");
		}
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}	
	function get_data_posisi_autocomplete($cari = NULL) {
		$this->general->connectDbPortal();

		$this->db->select('tbl_posisi.nama as id');
		$this->db->select("'Jumlah '+ CAST((select count(*) from tbl_karyawan where tbl_karyawan.posst=tbl_posisi.nama and tbl_karyawan.na='n') AS varchar) as nik");
		$this->db->select('tbl_posisi.nama');
		
		$this->db->from('tbl_posisi');
		if ($cari !== NULL) {
			$this->db->where("(tbl_posisi.nama like '%".$cari."%') ");
		}
		$this->db->where('tbl_posisi.na', 'n');
		$this->db->where('tbl_posisi.del', 'n');
		$this->db->order_by('tbl_posisi.nama', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}	

	function get_data_user_role($conn = NULL, $id_user_role = NULL, $active = NULL, $deleted = 'n', $user = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_bank_user_role.*');
		$this->db->select('CASE
								WHEN tbl_bank_user_role.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('tbl_bank_role.nama as nama_role');				   
		$this->db->select('tbl_bank_role.tipe_user');				   
		$this->db->select("CASE
								WHEN tbl_bank_role.tipe_user = 'nik' THEN (select tbl_karyawan.nama from tbl_karyawan where CONVERT(varchar(10), tbl_karyawan.nik)=tbl_bank_user_role.[user])
								ELSE 'Jumlah '+ CAST((select count(*) from tbl_karyawan where tbl_karyawan.posst=tbl_bank_user_role.[user] and tbl_karyawan.na='n') AS varchar)
						   END as caption_user");
		$this->db->from('tbl_bank_user_role');
		$this->db->join('tbl_bank_role', 'tbl_bank_role.id_role = tbl_bank_user_role.id_role', 'left outer');
		if ($id_user_role !== NULL) {
			$this->db->where('tbl_bank_user_role.id_user_role', $id_user_role);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_bank_user_role.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_bank_user_role.del', $deleted);
		}
		if ($user !== NULL) {
			$this->db->where('tbl_bank_user_role.user', $user);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
}
?>