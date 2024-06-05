<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : Kiranaku v2
@author 		: Akhmad Syaiful Yamang (8347)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dhome extends CI_Model{
	function get_log_user($user=NULL, $date=NULL){
		if($date == NULL){
			$date = date('Y-m-d');
		}

		$this->general->connectDbPortal();

		$this->db->select('tbl_userlog.*');
		$this->db->from('tbl_userlog');
		$this->db->where('tbl_userlog.tanggal', $date);
		if($user !== NULL){
			$this->db->where('tbl_userlog.id_user', $user);
		}
		$query 	= $this->db->get();
		$result = $query->row();

		$this->general->closeDb();
		return $result;
	} 

	function get_last_modified_menu($nik=NULL){
		$this->general->connectDbPortal();

		$string	= "SELECT TOP 1 tbl_menu.tanggal_edit
					 FROM tbl_menu
				 	WHERE tbl_menu.na = 'n'
				      AND tbl_menu.del = 'n'";
		if($nik !== NULL){
		  $string	.= " AND CHARINDEX('".$nik."', tbl_menu.nik_akses) > 0";
		}
		$string	.= " ORDER BY tbl_menu.tanggal_edit DESC";

		$query 	= $this->db->query($string);
		$result = $query->row();

		$this->general->closeDb();
		return $result;
	}

	function get_data_berita_web($type=NULL, $limit=NULL){
        $this->general->connectDbWeb();

        $this->db->select("blog.*");
        $this->db->from("blog");
        $this->db->where("blog.status = '1'");
        if($type !== NULL) {
            $this->db->where("blog.blog_type", $type);
        }
        $this->db->order_by("blog.publish_date DESC");
        $this->db->limit($limit);

        $query 	= $this->db->get();
        $result = $query->result();

        $this->general->closeDb();
        return $result;
    }

	function get_data_new_role($limit=NULL){
        $this->general->connectDbPortal();

        $string	= "SELECT TOP $limit tbl_karyawan.*
					 FROM tbl_karyawan
				 	WHERE tbl_karyawan.na = 'n'
				      AND tbl_karyawan.del = 'n'
				    ORDER BY tbl_karyawan.tanggal_join";

        $query 	= $this->db->query($string);
        $result = $query->result();

        $this->general->closeDb();
        return $result;
    }

    function get_data_karyawan($limit=NULL,$gedung=NULL){
        $this->general->connectDbPortal();
        $where_gedung = ' ';
        if($gedung != '' AND $gedung != NULL) {
        	if($gedung == 'ho' || ($gedung !== 'ho' && base64_decode($this->session->userdata("-id_level-")) <= 9102 ) ){
        		$where_gedung = " AND (tbl_karyawan.id_gedung = '$gedung' OR (tbl_karyawan.id_gedung <> '$gedung' 
        							AND tbl_user.id_level <= 9102)) ";
        	} else {
        		$where_gedung = " AND tbl_karyawan.id_gedung = '$gedung' ";
        	}
        }
        $string	= " 
        			SELECT $limit tbl_karyawan.nama,
					       tbl_karyawan.ho,
					       tbl_level.id_level,
					       tbl_level.nama level,
					       tbl_karyawan.posst,
					       tbl_karyawan.id_gedung,
                           tbl_wf_master_plant.plant_name,
					       tbl_karyawan.gambar,
					       tbl_karyawan.gender,
					       SUBSTRING(CONVERT(varchar,Convert(date,convert(char(8),gbpas)),6),1,6) birthdate
					  FROM [10.0.0.32].portal.dbo.tbl_karyawan 
					  LEFT JOIN [10.0.0.32].portal.dbo.tbl_user ON tbl_karyawan.nik = tbl_user.id_karyawan
					  LEFT JOIN [10.0.0.32].portal.dbo.tbl_level ON tbl_level.id_level = tbl_user.id_level
                      LEFT JOIN [10.0.0.32].portal.dbo.tbl_wf_master_plant ON tbl_wf_master_plant.plant = CASE 
                                                                                        WHEN tbl_karyawan.id_gedung = 'ho' THEN 'KMTR'
                                                                                        ELSE tbl_karyawan.id_gedung
                                                                                   END
					 WHERE RIGHT(gbpas,4) BETWEEN RIGHT(CONVERT(varchar, GETDATE(), 112), 4) AND RIGHT(CONVERT(varchar, DATEADD(day, 100, GETDATE()), 112), 4)
					   AND tbl_karyawan.na = 'n' and tbl_karyawan.del = 'n'
					   AND tbl_user.na = 'n' and tbl_user.del = 'n'
					   AND tbl_karyawan.nik NOT IN ('8247', '8248')
					   $where_gedung
					 GROUP BY tbl_karyawan.nama,
							  tbl_karyawan.ho,
							  tbl_level.id_level,
							  tbl_level.nama,
							  tbl_karyawan.posst,
							  tbl_karyawan.id_gedung,
                              tbl_wf_master_plant.plant_name,
							  tbl_karyawan.gbpas,
							  tbl_karyawan.gambar,
					       	tbl_karyawan.gender
					 ORDER BY RIGHT(gbpas,4) ASC

					";

					/* 
					FROM [10.0.0.32].portal.dbo.tbl_karyawan 
					  LEFT JOIN [10.0.0.32].portal.dbo.tbl_user ON tbl_karyawan.nik = tbl_user.id_karyawan
					  LEFT JOIN [10.0.0.32].portal.dbo.tbl_level ON tbl_level.id_level = tbl_user.id_level

					SELECT $limit *,
					-- 	SUBSTRING(CONVERT(varchar,Convert(date,convert(char(8),gbpas)),6),1,6) birthdate
					-- FROM tbl_karyawan 
					-- LEFT JOIN tbl_user ON tbl_karyawan.nik = tbl_user.id_karyawan
					-- WHERE RIGHT(gbpas,4) >= RIGHT(CONVERT(char(8),GETDATE(),112),4)
					-- and tbl_karyawan.na = 'n' and tbl_karyawan.del = 'n'
					-- AND tbl_user.na = 'n' and tbl_user.del = 'n'
					-- $where_gedung
					 order by RIGHT(tbl_karyawan.gbpas,4) ASC , tbl_karyawan.nama ASC
					*/
		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");			
		$query 	= $this->db->query($string);
        $result = $query->result();

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
								WHEN tbl_notif_berita.sent = \'n\' THEN \'<span class="label label-default">Not Sent</span>\'
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
		$this->db->where('tbl_notif_berita.tanggal', date('Y-m-d'));

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
	
	function get_data_milad($conn = NULL, $id_karyawan = NULL, $active = 'n', $deleted = 'n', $gedung = NULL, $date = NULL, $start = NULL, $end = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		
		$string = "	
					select
					tbl_karyawan.*,
					SUBSTRING(tbl_karyawan.gbpas, 5, 2) as bulan_milad,
					SUBSTRING(tbl_karyawan.gbpas, 7, 2) as tanggal_milad,
					CASE
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='01' THEN 'Januari'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='02' THEN 'Februari'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='03' THEN 'Maret'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='04' THEN 'April'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='05' THEN 'Mei'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='06' THEN 'Juni'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='07' THEN 'Juli'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='08' THEN 'Agustus'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='09' THEN 'September'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='10' THEN 'Oktober'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='11' THEN 'November'
						WHEN SUBSTRING(tbl_karyawan.gbpas, 5, 2)='12' THEN 'Desember'
						ELSE '-'
					END as nama_bulan_milad,					
					--SUBSTRING(CONVERT(varchar,Convert(date,convert(char(8),gbpas)),6),1,6) birthdate,
					CONVERT(varchar,(tbl_karyawan.id_karyawan)) +' - '+ tbl_karyawan.nama as title,
					CONVERT(varchar,YEAR('$start'))+'-'+CONVERT(varchar,SUBSTRING(tbl_karyawan.gbpas, 5, 2))+'-'+CONVERT(varchar,SUBSTRING(tbl_karyawan.gbpas, 7, 2)) as start,
					'true' as allDay,
					tbl_karyawan.id_karyawan as nik,
					tbl_karyawan.nama as nama,
					tbl_karyawan.email as email,
					tbl_karyawan.posst as bagian,
					'".base_url()."'+'assets/apps/'+tbl_karyawan.gambar as gambar
					from tbl_karyawan
					left outer join tbl_user on tbl_karyawan.id_karyawan = tbl_user.id_karyawan
					where 
					tbl_karyawan.na='n'
					and tbl_karyawan.del='n'
					and tbl_karyawan.gbpas is not null
		";
		if ($id_karyawan !== NULL) {
			$string .= "and tbl_karyawan.id_karyawan='$id_karyawan'";
		}
		if ($active !== NULL) {
			$string .= "and tbl_karyawan.na='$active'";
		}
		if ($deleted !== NULL) {
			$string .= "and tbl_karyawan.del='$deleted'";
		}
		if ($date !== NULL) {
			$string .= " and SUBSTRING(tbl_karyawan.gbpas, 5, 2)='".date('m')."'";
			$string .= " and SUBSTRING(tbl_karyawan.gbpas, 7, 2)='".date('d')."'";
		}
		if (($start !== NULL)and($end !== NULL)) {
			// $string .= " and SUBSTRING(tbl_karyawan.gbpas, 5, 2)='".substr($start,3,2)."'";
			$string .= " AND gbpas NOT IN ('19930200','00000000') AND CONVERT(VARCHAR,YEAR('$start')) + '-' + SUBSTRING(CONVERT(VARCHAR,CONVERT(DATE, gbpas)), 6, 10) BETWEEN '$start' AND '$end'";
		}

		//ho
		if((base64_decode($this->session->userdata("-ho-"))=='y')and((base64_decode($this->session->userdata("-id_jabatan-"))!='9005')or(base64_decode($this->session->userdata("-id_jabatan-"))!='9057')or(base64_decode($this->session->userdata("-id_jabatan-"))!='9058'))){
			$string .= " AND (tbl_karyawan.ho='y' OR (tbl_karyawan.ho!='y' and tbl_user.id_level <= 9102))";	
		}
		//ceo
		else if((base64_decode($this->session->userdata("-id_jabatan-"))=='9005')or(base64_decode($this->session->userdata("-id_jabatan-"))=='9057')or(base64_decode($this->session->userdata("-id_jabatan-"))=='9058')){ 
			$string .= " AND (tbl_karyawan.ho='y' OR  (tbl_karyawan.ho!='y' and tbl_karyawan.id_gedung in (select tbl_inv_pabrik.kode from tbl_wf_region left outer join tbl_inv_pabrik on tbl_inv_pabrik.plant_code=tbl_wf_region.plant_code where tbl_wf_region.nik=".base64_decode($this->session->userdata('-nik-'))."))) ";
		}
		//dirops
		elseif(base64_decode($this->session->userdata("-id_jabatan-"))=='9056'){ 
			$string .= " AND ((tbl_karyawan.ho='y' and tbl_user.id_jabatan not in('9055','9057','9058')) OR (tbl_karyawan.ho!='y' and tbl_karyawan.id_gedung='$gedung')OR(tbl_karyawan.nik=(select tbl_wf_region.nik from tbl_wf_region where tbl_wf_region.plant_code=(select tbl_karyawan2.persa from tbl_karyawan tbl_karyawan2 where tbl_karyawan2.nik='".base64_decode($this->session->userdata('-nik-'))."')))) ";
		}
		//manager kantor
		else if(base64_decode($this->session->userdata("-id_jabatan-"))=='9055'){
			$string .= " AND ((tbl_karyawan.ho='y' and tbl_user.id_jabatan not in('9055','9057','9058')) OR (tbl_karyawan.ho!='y' and tbl_user.id_jabatan='9055') OR (tbl_karyawan.ho!='y' and tbl_user.id_jabatan='9056' and tbl_karyawan.id_gedung='$gedung') OR (tbl_karyawan.nik=(select tbl_wf_region.nik from tbl_wf_region where tbl_wf_region.plant_code=(select tbl_karyawan2.persa from tbl_karyawan tbl_karyawan2 where tbl_karyawan2.nik='".base64_decode($this->session->userdata('-nik-'))."'))))";
		}
		//staff pabrik
		else{
			$string .= " AND (tbl_karyawan.id_gedung='$gedung' OR (tbl_karyawan.nik=(select tbl_wf_region.nik from tbl_wf_region where tbl_wf_region.plant_code=(select tbl_karyawan2.persa from tbl_karyawan tbl_karyawan2 where tbl_karyawan2.nik='".base64_decode($this->session->userdata('-nik-'))."'))))";
		}

		
		// echo"<pre>$string</pre>";
		// $this->db->query("SET ANSI_NULLS ON");
		// $this->db->query("SET ANSI_WARNINGS ON");			
		$query 	= $this->db->query($string);
		$result = $query->result();
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	
}

?>