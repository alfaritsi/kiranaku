<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KODE VENDOR
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dsettingvendor extends CI_Model{
		function get_data_user($user = NULL, $email = NULL, $isPosisi = NULL, $nik = NULL, $jabatan_in = NULL, $level_in = NULL, $posisi_in = NULL, $pabrik_in = NULL) {
			$this->general->connectDbPortal();

			$this->db->select('tbl_karyawan.nik as id');
			$this->db->select('tbl_user.*');
			$this->db->select('tbl_karyawan.*');
			$this->db->from('tbl_user');
			$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan
										 AND tbl_karyawan.na = \'n\' 
										 AND tbl_karyawan.del = \'n\'', 'inner');
			if ($isPosisi !== NULL) {
				$this->db->join('tbl_posisi', 'tbl_posisi.nama = tbl_karyawan.posst 
										 AND tbl_posisi.na = \'n\'', 'inner');
				if ($posisi_in != NULL) {
					$this->db->where_in('tbl_posisi.id_posisi', $posisi_in);
				}
			}
			if ($user !== NULL) {
				$this->db->where("(tbl_karyawan.nik like '%".$user."%' or tbl_karyawan.nama like '%".$user."%') ");
			}
			if ($email !== NULL) {
				$this->db->where('tbl_karyawan.email', $email);
			}
			if ($nik != NULL) {
				$this->db->where('tbl_karyawan.nik', $nik);
			}
			if ($jabatan_in != NULL) {
				$this->db->where_in('tbl_user.id_jabatan', $jabatan_in);
			}
			if ($level_in != NULL) {
				$this->db->where_in('tbl_user.id_level', $level_in);
			}
			if ($pabrik_in != NULL) {
				$this->db->where_in('tbl_karyawan.gsber', $pabrik_in);
			}
			$this->db->where('tbl_user.na', 'n');
			$this->db->where('tbl_user.del', 'n');
			$this->db->order_by('tbl_karyawan.nama', 'ASC');
			$query  = $this->db->get();
			$result = $query->result();

			$this->general->closeDb();
			return $result;
		}	
	function get_data_karyawan($conn = NULL, $nik = NULL, $active = NULL, $deleted = 'n', $search = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.*');
		$this->db->select('CASE
								WHEN tbl_karyawan.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_karyawan');						   
		if ($nik !== NULL) {
			$this->db->where('tbl_karyawan.nik', $nik);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_karyawan.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_karyawan.del', $deleted);
		}
		if ($search !== NULL) {
			$this->db->where("(tbl_karyawan.nik like '%$search%')or(tbl_karyawan.nama like '%$search%')");
		}
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_user_role($conn = NULL, $id_user_role = NULL, $active = NULL, $deleted = 'n', $nik = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_user_role.*');
		$this->db->select('CASE
								WHEN tbl_vendor_user_role.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('tbl_vendor_role.nama as nama_role');				   
		$this->db->select('tbl_karyawan.nama as nama_karyawan');				   
		$this->db->from('tbl_vendor_user_role');
		$this->db->join('tbl_vendor_role', 'tbl_vendor_role.id_role = tbl_vendor_user_role.id_role', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_vendor_user_role.nik', 'left outer');
		if ($id_user_role !== NULL) {
			$this->db->where('tbl_vendor_user_role.id_user_role', $id_user_role);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_user_role.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_user_role.del', $deleted);
		}
		if ($nik !== NULL) {
			$this->db->where('tbl_vendor_user_role.nik', $nik);
		}
		$this->db->order_by("tbl_vendor_user_role.nik", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_kualifikasi($conn = NULL, $id_kualifikasi_spk = NULL, $active = NULL, $deleted = 'n', $id_master_dokumen = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_leg_kualifikasi_spk.*');
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR(MAX), tbl_vendor_kualifikasi_dokumen.id_master_dokumen)+RTRIM(',')
					            FROM tbl_vendor_kualifikasi_dokumen
								WHERE tbl_vendor_kualifikasi_dokumen.id_kualifikasi_spk = tbl_leg_kualifikasi_spk.id_kualifikasi_spk
								and tbl_vendor_kualifikasi_dokumen.na='n'
								ORDER BY tbl_vendor_kualifikasi_dokumen.id_master_dokumen
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_master_dokumen,
						  ");
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR(MAX), tbl_vendor_master_dokumen.nama)+RTRIM(',')
					            FROM tbl_vendor_kualifikasi_dokumen
								left outer join tbl_vendor_master_dokumen on tbl_vendor_master_dokumen.id_master_dokumen=tbl_vendor_kualifikasi_dokumen.id_master_dokumen
								WHERE tbl_vendor_kualifikasi_dokumen.id_kualifikasi_spk = tbl_leg_kualifikasi_spk.id_kualifikasi_spk
								and tbl_vendor_kualifikasi_dokumen.na='n'
								ORDER BY tbl_vendor_kualifikasi_dokumen.id_master_dokumen
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_nama_master_dokumen,
						  ");
		$this->db->select('CASE
								WHEN tbl_leg_kualifikasi_spk.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_leg_kualifikasi_spk');
		if ($id_kualifikasi_spk !== NULL) {
			$this->db->where('tbl_leg_kualifikasi_spk.id_kualifikasi_spk', $id_kualifikasi_spk);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_leg_kualifikasi_spk.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_leg_kualifikasi_spk.del', $deleted);
		}
		$this->db->where('tbl_leg_kualifikasi_spk.na','n');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	// function get_data_kualifikasi_dokumen($conn = NULL, $id_kualifikasi_spk = NULL, $active = NULL, $deleted = 'n') {
		// if ($conn !== NULL)
			// $this->general->connectDbPortal();

		// $this->db->select('tbl_vendor_master_dokumen.*');
		// $this->db->select('CASE
								// WHEN tbl_vendor_master_dokumen.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								// ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   // END as label_active');
		// $this->db->from('tbl_vendor_master_dokumen');
		// $this->db->join('tbl_vendor_master_dokumen', 'tbl_vendor_master_dokumen.id_master_dokumen = tbl_vendor_kualifikasi_dokumen.id_master_dokumen', 'left outer');
		// if ($active !== NULL) {
			// $this->db->where('tbl_vendor_master_dokumen.na', $active);
		// }
		// if ($deleted !== NULL) {
			// $this->db->where('tbl_vendor_master_dokumen.del', $deleted);
		// }
		// $query  = $this->db->get();
		// $result = $query->result();

		// if ($conn !== NULL)
			// $this->general->closeDb();
		// return $result;
	// }
}
?>