<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : SKYNET
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dtransactionskynet extends CI_Model{
	function get_data_kategori($conn = NULL, $id_hd_kategori = NULL) {	
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('*');
		$this->db->from('tbl_hd_kategori');
		if ($id_hd_kategori !== NULL) {
			$this->db->where('id_hd_kategori', $id_hd_kategori);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_subkategori($conn = NULL, $id_hd_subkategori = NULL) {	
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('*');
		$this->db->from('tbl_hd_subkategori');
		if ($id_hd_subkategori !== NULL) {
			$this->db->where('id_hd_subkategori', $id_hd_subkategori);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_user($conn = NULL, $id_user = NULL) {	
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_user.*');
		$this->db->select('tbl_karyawan.nik as nik_karyawan');
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select('tbl_divisi.nama as divisi_karyawan');
		$this->db->from('tbl_user');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		$this->db->join('tbl_divisi', 'tbl_divisi.id_divisi = tbl_user.id_divisi', 'left outer');
		if ($id_user !== NULL) {
			$this->db->where('tbl_user.id_user', $id_user);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_skynet($id=NULL, $lokasi=NULL, $lokasi_arr=NULL, $status=NULL, $kategori, $severity=NULL, $from=NULL, $to=NULL, $agent=NULL){
		$this->general->connectDbPortal();
		$arr_lokasi = "";
		if(isset($lokasi_arr)){
			foreach ($lokasi_arr as $key => $dt) {
				$arr_lokasi .= "'".$dt."',";
			}
			$arr_lokasi = rtrim($arr_lokasi,",");
		}
		if($arr_lokasi == "''"){ 
			$arr_lokasi = ""; 
		}

		$string	= "
		 SELECT X.email,
				X.nama,
				X.agent,
				X.no_ticket,
				X.id_hd_ticket,
				X.id_hd_kategori,
				X.id_hd_subkategori,
				X.id_hd_status,
				X.id_hd_level, 
				X.id_hd_severity, 
				X.id_hd_agent,
				X.id_hd_agent_order, 
				X.title, 
				X.review, 
				X.keterangan, 
				X.gambar, 
				X.assignto, 
				X.excalateby, 
				X.lokasi, 
				X.login_buat, 
				X.login_edit, 
				X.tanggal_buat,  
				X.tanggal_edit, 
				X.na, 
				X.del, 
				X.nomor,
				X.warna_sev,
				X.tingkat_severity,
				X.kategori,
				X.nama_subkategori,
				X.status, 
				X.warna,
				X.tanggal_awal, 
				X.tanggal_excalate,
				X.tgl_touser,
				X.tgl_userclose,
				X.tglwaktu_touser,
				X.tglwaktu_userclose,
				X.actual,
				X.open_tiket_begin,
				X.open_tiket_end,
				X.downtime_tiket_begin,
				X.downtime_tiket_end,
				CASE
					WHEN X.responsetime IS NOT NULL THEN CONVERT(VARCHAR, X.responsetime / 60 / 24) + ' hari ' + CONVERT(VARCHAR, X.responsetime / 60 % 24) + ' jam ' + CONVERT(VARCHAR, X.responsetime % 60) + ' menit'
					ELSE '-'
				END as responsetime,
				CASE
					WHEN X.leadtime IS NOT NULL THEN CONVERT(VARCHAR, X.leadtime / 60 / 24) + ' hari ' + CONVERT(VARCHAR, X.leadtime / 60 % 24) + ' jam ' + CONVERT(VARCHAR, X.leadtime % 60) + ' menit' 
					ELSE '-'
				END as leadtime
		FROM
		(
		 SELECT tbl_karyawan.email,
				tbl_karyawan.nama,
				agent.nama agent,
				tbl_hd_ticket.lokasi + right('0000' + cast(nomor as varchar(4)),4) no_ticket,
				tbl_hd_ticket.id_hd_ticket,
				tbl_hd_ticket.id_hd_kategori,
				tbl_hd_ticket.id_hd_subkategori,
				tbl_hd_ticket.id_hd_status,
				tbl_hd_ticket.id_hd_level, 
				tbl_hd_ticket.id_hd_severity, 
				tbl_hd_ticket.id_hd_agent,
				tbl_hd_ticket.id_hd_agent_order, 
				tbl_hd_ticket.title, 
				tbl_hd_ticket.review, 
				tbl_hd_ticket.keterangan, 
				tbl_hd_ticket.gambar, 
				tbl_hd_ticket.assignto, 
				tbl_hd_ticket.excalateby, 
				tbl_hd_ticket.lokasi, 
				tbl_hd_ticket.login_buat, 
				tbl_hd_ticket.login_edit, 
				tbl_hd_ticket.tanggal_buat,
				tbl_hd_ticket.tanggal_edit, 
				tbl_hd_ticket.na, 
				tbl_hd_ticket.del, 
				tbl_hd_ticket.nomor,
				tbl_hd_severity.warna as warna_sev,
				tbl_hd_severity.tingkat_severity,
				tbl_hd_kategori.kategori,
				tbl_hd_subkategori.nama as nama_subkategori,
				tbl_hd_status.status, 
				tbl_hd_status.warna,
				(
				SELECT TOP 1 CONVERT(DATE, tbl_hd_history.tanggal_buat) 
				FROM tbl_hd_history  
				WHERE tbl_hd_history.id_hd_ticket = tbl_hd_ticket.id_hd_ticket 
					AND tbl_hd_history.id_hd_status = '3'
				) as tgl_touser,
				(
				SELECT TOP 1 CONVERT(DATE, tbl_hd_history.tanggal_buat) 
				FROM tbl_hd_history  
				WHERE tbl_hd_history.id_hd_ticket = tbl_hd_ticket.id_hd_ticket 
					AND tbl_hd_history.id_hd_status = '4'
				) as tgl_userclose,
				tbl_hd_ticket.tanggal_awal, 
				tbl_hd_ticket.tanggal_excalate,
				(
					SELECT TOP 1 tbl_hd_history.tanggal_buat
					  FROM tbl_hd_history  
					 WHERE tbl_hd_history.id_hd_ticket = tbl_hd_ticket.id_hd_ticket 
					   AND tbl_hd_history.id_hd_status = '3'
				) as tglwaktu_touser, 
				(
				SELECT TOP 1 tbl_hd_history.tanggal_buat
				FROM tbl_hd_history  
				WHERE tbl_hd_history.id_hd_ticket = tbl_hd_ticket.id_hd_ticket 
					AND tbl_hd_history.id_hd_status = '4'
				) as tglwaktu_userclose,
				DATEDIFF(
				DD, 
				tbl_hd_ticket.tanggal_awal,
				(
					SELECT TOP 1 CONVERT(DATE, tbl_hd_history.tanggal_buat) 
					FROM tbl_hd_history  
					WHERE tbl_hd_history.id_hd_ticket = tbl_hd_ticket.id_hd_ticket 
						AND tbl_hd_history.id_hd_status = '4'
				)
				) -
				(
				DATEDIFF(
					WW, 
					tbl_hd_ticket.tanggal_awal,
					(
						SELECT TOP 1 CONVERT(DATE, tbl_hd_history.tanggal_buat) 
						FROM tbl_hd_history  
						WHERE tbl_hd_history.id_hd_ticket = tbl_hd_ticket.id_hd_ticket 
							AND tbl_hd_history.id_hd_status = '4'
					)
				) * 2
				) actual,
				tbl_hd_ticket.open_tiket_end, 
				tbl_hd_ticket.open_tiket_begin,
				tbl_hd_ticket.downtime_tiket_begin,
				tbl_hd_ticket.downtime_tiket_end,
				DATEDIFF(
					MINUTE, 
					tbl_hd_ticket.open_tiket_begin, 
					tbl_hd_ticket.open_tiket_end
				) as responsetime,
				DATEDIFF(
					MINUTE, 
					tbl_hd_ticket.tanggal_awal, 
					(
						SELECT TOP 1 tbl_hd_history.tanggal_buat
						FROM tbl_hd_history  
						WHERE tbl_hd_history.id_hd_ticket = tbl_hd_ticket.id_hd_ticket 
							AND tbl_hd_history.id_hd_status = '4'
					)
				) as leadtime
		   FROM tbl_hd_ticket
		   LEFT JOIN tbl_hd_severity ON tbl_hd_severity.id_hd_severity = tbl_hd_ticket.id_hd_severity
		   LEFT JOIN tbl_hd_kategori ON tbl_hd_kategori.id_hd_kategori = tbl_hd_ticket.id_hd_kategori
		   LEFT JOIN tbl_hd_subkategori ON tbl_hd_subkategori.id_hd_subkategori = tbl_hd_ticket.id_hd_subkategori
		   LEFT JOIN tbl_hd_status ON tbl_hd_status.id_hd_status = tbl_hd_ticket.id_hd_status
		   LEFT JOIN tbl_user ON tbl_user.id_user = tbl_hd_ticket.login_buat
		   LEFT JOIN tbl_karyawan ON tbl_karyawan.id_karyawan = tbl_user.id_karyawan
		   LEFT JOIN tbl_karyawan as agent ON agent.id_karyawan = tbl_hd_ticket.id_hd_agent
		  WHERE tbl_hd_ticket.na = 'n' 
			AND tbl_hd_ticket.del = 'n'
		";

		if(isset($id) && $id != ""){
			$string .= " AND tbl_hd_ticket.id_hd_ticket = ".$id."";				
		}
		if(isset($agent) && $agent != ""){
			$string .= " ".$agent."";	
		}
		if(isset($lokasi) && $lokasi != ""){
			$string .= " AND tbl_hd_ticket.lokasi = '".$lokasi."'";				
		}
		if(isset($arr_lokasi) && $arr_lokasi != ""){
			$string .= " AND tbl_hd_ticket.lokasi IN (".$arr_lokasi.")";				
		}
		if(isset($status) && $status != ""){
			$string .= " AND tbl_hd_ticket.id_hd_status = '".$status."'";				
		}
		if(isset($kategori) && $kategori != ""){
			$string .= " AND tbl_hd_ticket.id_hd_kategori = '".$kategori."'";				
		}
		if(isset($severity) && $severity != ""){
			$string .= " AND tbl_hd_ticket.id_hd_severity = '".$severity."'";				
		}
		if(isset($from) && $from != "" && isset($to) && $to != ""){
			$string .= " AND cast(tbl_hd_ticket.tanggal_buat as date) between '".$this->generate->regenerateDateFormat($from)."' 
						 AND '".$this->generate->regenerateDateFormat($to)."'";				
		}
		$string	.= " 		
		) X
		ORDER BY X.id_hd_ticket DESC 
		";

		$query	= $this->db->query($string);
		$result	= $query->result();

		$this->general->closeDb();

		return $result;

	}

	function get_data_history($id=NULL,$order=NULL){
		$this->general->connectDbPortal();

		$string	= "
				SELECT h.id_hd_history, h.title, DATEDIFF(minute, h.tanggal_awal, h.tanggal_buat) as menit, 
				DATEDIFF(hour, h.tanggal_awal, h.tanggal_buat) as jam,
				DATEDIFF(day, h.tanggal_awal, h.tanggal_buat) as hari,

				CONVERT(CHAR(10),h.tanggal_awal,104) as tanggal_awal, 
				CONVERT(CHAR(10),h.tanggal_awal,8) as jam_awal,

				CONVERT(CHAR(10),h.tanggal_buat,104) as tanggal_buat, 
				CONVERT(CHAR(10),h.tanggal_buat,8) as jam_buat, 

				h.tanggal_buat as tglbuat_lengkap,
				h.tanggal_awal as tglawal_lengkap,
				k.nama as author, s.status, h.remark, h.gambar
				FROM tbl_hd_history h
				left join tbl_user u 
					on u.id_user=h.login_buat
				left join tbl_karyawan k 
					on k.id_karyawan=u.id_karyawan
				left join tbl_hd_status s 
					on s.id_hd_status = h.id_hd_status 
				WHERE h.na = 'n' AND h.del = 'n' "; 

		if(isset($id) && $id != ""){
			$string .= " AND h.id_hd_ticket = ".$id."";				
		}

		if($order != NULL){
			$string	.= " order by id_hd_history DESC";
		} else {
			$string	.= " order by id_hd_history asc";
		}

		$query	= $this->db->query($string);
		$result	= $query->result();

		$this->general->closeDb();

		return $result;
	}

	function get_data_lastnumber(){
		$this->general->connectDbPortal();

		$string	= "SELECT ISNULL(MAX(nomor),0) + 1 nomor FROM tbl_hd_ticket";

		$query	= $this->db->query($string);
		$result	= $query->result();

		$this->general->closeDb();

		return $result;
	}

	function get_data_counthistory($id=NULL){
		$this->general->connectDbPortal();

		$string	= "SELECT id_hd_ticket, ISNULL(COUNT(id_hd_ticket),0) + 1 nomor FROM tbl_hd_history where id_hd_ticket = ".$id."
				group by id_hd_ticket";

		$query	= $this->db->query($string);
		$result	= $query->result();

		$this->general->closeDb();

		return $result;
	}

}

?>