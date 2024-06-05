<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Notifikasi HRGA
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmasterberita extends CI_Model{
	function get_data_email($conn = NULL, $id_email = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_notif_email.*');
		$this->db->from('tbl_notif_email');
		$this->db->where("tbl_notif_email.na='n'");
		if($id_email !== NULL){
			$this->db->where("tbl_notif_email.id_email='$id_email'");
		}
		$this->db->order_by("tbl_notif_email.email", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_berita($conn = NULL, $id_notif_berita = NULL, $active = NULL, $deleted = 'n', $jenis = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_notif_berita.*');
		$this->db->select('convert(varchar, tbl_notif_berita.tanggal_buat, 104) as tanggal_buat_konversi');
		$this->db->select('convert(varchar, tbl_notif_berita.tanggal, 104) as tanggal_konversi');
		$this->db->select('CONVERT(VARCHAR(10), tbl_notif_berita.tanggal, 104) as tanggal_convert');
		$this->db->select('CASE
								WHEN tbl_notif_berita.sent = \'n\' 
								THEN \'<span class="label label-default">Not Sent</span>\'
								ELSE \'<span class="label label-success">Sent</span>\'
						   END as label_sent');
		$this->db->select('tbl_karyawan2.nama as nama_karyawan');
		
		$this->db->select('tbl_karyawan2.posst as posisi_karyawan');
		$this->db->select('CASE
								WHEN tbl_karyawan.gender = \'p\' THEN \'Bpk.\'
								ELSE \'Ibu.\'
						   END as gender_karyawan');
		$this->db->select('CASE
								WHEN {fn DAYNAME(tanggal)}=\'Sunday\' THEN \'Minggu\'
								WHEN {fn DAYNAME(tanggal)}=\'Monday\' THEN \'Senin\'
								WHEN {fn DAYNAME(tanggal)}=\'Tuesday\' THEN \'Selasa\'
								WHEN {fn DAYNAME(tanggal)}=\'Wednesday\' THEN \'Rabu\'
								WHEN {fn DAYNAME(tanggal)}=\'Thursday\' THEN \'Kamis\'
								WHEN {fn DAYNAME(tanggal)}=\'Friday\' THEN \'Jumat\'
								WHEN {fn DAYNAME(tanggal)}=\'Saturday\' THEN \'Sabtu\'
								ELSE \'-\'
						   END as hari');
						   
		$this->db->select('{fn DAYNAME(tanggal)} as name_days');
		$this->db->select('tbl_karyawan.nik as nik_buat');
		$this->db->select('tbl_karyawan.nama as nama_buat');
		$this->db->select('tbl_karyawan.email as email_buat');
		$this->db->from('tbl_notif_berita');
		$this->db->join('tbl_user', 'tbl_notif_berita.login_buat = tbl_user.id_user', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan', 'left outer');
		$this->db->join('tbl_karyawan tbl_karyawan2', 'tbl_notif_berita.nik = tbl_karyawan2.nik', 'left outer');
		$this->db->join('tbl_departemen', 'tbl_departemen.id_departemen = tbl_user.id_departemen', 'left outer');
		$this->db->join('tbl_divisi', 'tbl_divisi.id_divisi = tbl_user.id_divisi', 'left outer');
		if ($id_notif_berita !== NULL) {
			$this->db->where('tbl_notif_berita.id_notif_berita', $id_notif_berita);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_notif_berita.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_notif_berita.del', $deleted);
		}
		if ($jenis !== NULL) {
			$this->db->where('tbl_notif_berita.jenis', $jenis);
		}
		
		$query  = $this->db->get();
		$result = $query->result();
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_penerima($conn = NULL, $nik_duka = NULL, $ho = NULL, $id_jabatan = NULL, $gedung = NULL, $id_notif_berita = NULL, $status_kirim = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.*');
		if($id_notif_berita!==NULL){
			$this->db->select("
								CASE 
								WHEN(select count(tbl_notif_berita_log.id_notif_berita_log) from tbl_notif_berita_log where tbl_notif_berita_log.nik=tbl_karyawan.nik and tbl_notif_berita_log.id_notif_berita='$id_notif_berita')!=0 
								THEN '<span class=\"label label-success\">Sent</span>'
								ELSE '<span class=\"label label-default\">Not Sent</span>'
								END
								as label_sent");	
		}
		$this->db->select('tbl_user.id_jabatan');
		$this->db->from('tbl_karyawan');
		$this->db->join('tbl_user', 'tbl_karyawan.nik = tbl_user.id_karyawan', 'left outer');
		$this->db->where("tbl_karyawan.email!='' AND tbl_karyawan.email is not null");

		if (($ho !== NULL)and($id_jabatan !== NULL)) {
			//ho
			if(($ho=='y')and(($id_jabatan!='9005')or($id_jabatan!='9057')or($id_jabatan!='9058'))){
				$this->db->where("(tbl_karyawan.ho='y' OR (tbl_karyawan.ho!='y' and tbl_user.id_level <= 9102))");
			}
			//ceo
			else if(($id_jabatan=='9005')or($id_jabatan=='9057')or($id_jabatan=='9058')){ 
				$this->db->where("(tbl_karyawanyy.ho='y' OR  (tbl_karyawan.ho!='y' and tbl_karyawan.id_gedung in (select tbl_inv_pabrik.kode from tbl_wf_region left outer join tbl_inv_pabrik on tbl_inv_pabrik.plant_code=tbl_wf_region.plant_code where tbl_wf_region.nik=".$nik_duka.")))");
			}
			//dirops
			elseif($id_jabatan=='9056'){ 
				$this->db->where("((tbl_karyawanzz.ho='y' and tbl_user.id_jabatan not in('9055','9057','9058)) OR (tbl_karyawan.ho!='y' and tbl_karyawan.id_gedung='$gedung')OR(tbl_karyawan.nik=(select tbl_wf_region.nik from tbl_wf_region where tbl_wf_region.plant_code=(select tbl_karyawan2.persa from tbl_karyawan tbl_karyawan2 where tbl_karyawan2.nik='".$nik_duka."'))))");
			}
			//manager kantor
			else if($id_jabatan=='9055'){
				$this->db->where("((tbl_karyawanaa.ho='y' and tbl_user.id_jabatan not in('9055','9057','9058')) OR (tbl_karyawan.ho!='y' and tbl_user.id_jabatan='9055') OR (tbl_karyawan.ho!='y' and tbl_user.id_jabatan='9056' and tbl_karyawan.id_gedung='$gedung') OR (tbl_karyawan.nik=(select tbl_wf_region.nik from tbl_wf_region where tbl_wf_region.plant_code=(select tbl_karyawan2.persa from tbl_karyawan tbl_karyawan2 where tbl_karyawan2.nik='".$nik_duka."'))))");
			}
			//staff pabrik
			else{
				$this->db->where("(tbl_karyawan.id_gedung='$gedung' OR (tbl_karyawan.nik=(select tbl_wf_region.nik from tbl_wf_region where tbl_wf_region.plant_code=(select tbl_karyawan2.persa from tbl_karyawan tbl_karyawan2 where tbl_karyawan2.nik='".$nik_duka."'))))");
			}
		}
		if(($status_kirim!==NULL)and($status_kirim=='y')){
			$this->db->where("tbl_karyawan.nik not in(select tbl_notif_berita_log.nik from tbl_notif_berita_log where tbl_notif_berita_log.id_notif_berita='$id_notif_berita')");
		}
		// $this->db->limit(5);
		$query  = $this->db->get();
		$result = $query->result();
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
		
		
	}
	function get_data_karyawan($conn = NULL, $nik = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.*');
		$this->db->select('tbl_user.id_jabatan');
		$this->db->from('tbl_karyawan');
		$this->db->join('tbl_user', 'tbl_karyawan.nik = tbl_user.id_karyawan', 'left outer');
		if ($nik !== NULL) {
			$this->db->where('tbl_karyawan.nik', $nik);
		}
		$query  = $this->db->get();
		$result = $query->result();
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
}
?>