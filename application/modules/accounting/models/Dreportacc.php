<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : Attachment Accounting
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dreportacc extends CI_Model{

	function get_data_sum_hasilujiairlimbah($opt=NULL, $pabrik=NULL, $from=NULL, $to=NULL){
		$from = substr($from, -4).substr($from, 0, 2);
		$to = substr($to, -4).substr($to, 0, 2);

		$string	= "EXEC portal.dbo.SP_Kiranaku_SHE_RptHasilJjiAirLimbah ".$opt."";
		if(isset($pabrik) && $pabrik != ""){
			$string .= ", ".$pabrik."";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($from) && $from != ""){
			$string .= ", '".str_replace('-', '', $from)."'";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($to) && $to != ""){
			$string .= ", '".str_replace('-', '', $to)."'";				
		}else{
			$string .= ", NULL";				
		}
            
		$query	= $this->db->query($string);
		$result	= $query->result_array();
		return $result;
	}

	function get_data_sum_bebancemar($opt=NULL, $pabrik=NULL, $from=NULL, $to=NULL){
		$this->general->connectDbDefault();

		$from = substr($from, -4).substr($from, 0, 2);
		$to = substr($to, -4).substr($to, 0, 2);

		$string	= "EXEC dbo.SP_Kiranaku_SHE_RptBebanCemar ".$opt."";
		if(isset($pabrik) && $pabrik != ""){
			$string .= ", ".$pabrik."";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($from) && $from != ""){
			$string .= ", '".str_replace('-', '', $from)."'";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($to) && $to != ""){
			$string .= ", '".str_replace('-', '', $to)."'";				
		}else{
			$string .= ", NULL";				
		}
            
		$query	= $this->db->query($string);
		$result	= $query->result();

		$this->general->closeDB();

		return $result;
	}

	function get_data_sum_cemaraktual($opt=NULL, $pabrik=NULL, $from=NULL, $to=NULL){
		$from = substr($from, -4).substr($from, 0, 2);
		$to = substr($to, -4).substr($to, 0, 2);

		$string	= "EXEC dbo.SP_Kiranaku_SHE_RptCemarAktual ".$opt."";
		if(isset($pabrik) && $pabrik != ""){
			$string .= ", ".$pabrik."";				
		}else{
			$string .= ", 6";				
		}
		if(isset($from) && $from != ""){
			$string .= ", ".str_replace('-', '', $from);				
		}else{
			$string .= ", '201608'";				
		}
		if(isset($to) && $to != ""){
			$string .= ", ".str_replace('-', '', $to);				
		}else{
			$string .= ", '201608'";				
		}
            
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_kualitasudara_filterjenis($plant=NULL, $kategori=NULL){
		$string	= "select b.id, b.jenis from tbl_she_jenis a
					inner join tbl_she_master_jenis b
						on a.fk_jenis = b.id
					where fk_pabrik = ".$plant." and fk_kategori = ".$kategori."
					order by b.jenis";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;	
	}

	function get_data_bpa_emisi_udara($opt=NULL, $pabrik=NULL, $jenis=NULL, $periode=NULL){
		$string	= "EXEC dbo.SP_Kiranaku_SHE_RptBpaEmisiUdara ".$opt."";
		if(isset($pabrik) && $pabrik != ""){
			$string .= ", ".$pabrik."";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($jenis) && $jenis != ""){
			$string .= ", ".$jenis."";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($periode) && $periode != ""){
			$string .= ", '".$periode."'";				
		}else{
			$string .= ", NULL";				
		}
            
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_bpa_vs_sample($opt=NULL, $pabrik=NULL, $periode=NULL){
		$string	= "EXEC dbo.SP_Kiranaku_SHE_RptBpaVsSample ".$opt."";
		if(isset($pabrik) && $pabrik != ""){
			$string .= ", ".$pabrik."";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($periode) && $periode != ""){
			$string .= ", '".$periode."'";				
		}else{
			$string .= ", NULL";				
		}
            
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_akk_kualitasudara($opt=NULL, $pabrik=NULL, $kategori=NULL, $jenis=NULL, $periode=NULL){
		$string	= "EXEC dbo.SP_Kiranaku_SHE_RptAkkKualitasUdara ".$opt."";
		if(isset($pabrik) && $pabrik != ""){
			$string .= ", ".$pabrik."";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($kategori) && $kategori != ""){
			$string .= ", ".$kategori."";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($jenis) && $jenis != ""){
			$string .= ", ".$jenis."";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($periode) && $periode != ""){
			$string .= ", '".$periode."'";				
		}else{
			$string .= ", NULL";				
		}
            
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_neracalimbah_b3($opt=NULL, $pabrik=NULL, $periode=NULL, $tahun=NULL){
		$string	= "EXEC dbo.SP_Kiranaku_SHE_RptNeracaLimbahB3 ".$opt.", ".$pabrik.", '".$periode."', '".$tahun."'";	
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_logbook_b3($opt=NULL, $pabrik=NULL, $limbah=NULL, $from=NULL, $to=NULL){
		$from = substr($from, -4).substr($from, 0, 2);
		$to = substr($to, -4).substr($to, 0, 2);
		
		$string	= "EXEC dbo.SP_Kiranaku_SHE_RptLogBookLimbahB3 ".$opt.", ".$pabrik.", ".$limbah.", '".$from."', '".$to."'";				
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_ba_logbook_b3($pabrik=NULL, $from=NULL, $to=NULL){
		$from = $this->generate->regenerateDateFormat($from);
		$to = $this->generate->regenerateDateFormat($to);

		$string	= "
			SELECT * FROM 
			(
				SELECT DISTINCT b.nama, c.nama_vendor, a.no_berita_acara, a.tanggal_keluar, 
				a.jenis_kendaraan, a.nomor_kendaraan, a.nama_driver,
				(SELECT TOP 1 CASE WHEN transfer_ba_sap = 'y' THEN 'Transfered' else '' END FROM tbl_she_tr_b3_limbah WHERE no_berita_acara = a.no_berita_acara AND transfer_ba_sap = 'y') transfer_ba_sap,
				(SELECT TOP 1 CAST(tanggal_transfer_sap AS DATE) FROM tbl_she_tr_b3_limbah WHERE no_berita_acara = a.no_berita_acara AND transfer_ba_sap = 'y') tanggal_transfer_sap,
				(SELECT TOP 1 stok FROM tbl_she_tr_b3_limbah WHERE no_berita_acara = a.no_berita_acara AND stok = -1) stok
				FROM tbl_she_tr_b3_limbah a
				LEFT JOIN tbl_inv_pabrik b
					ON a.fk_pabrik = b.id_pabrik
				LEFT JOIN tbl_she_vendor c
					ON a.fk_vendor = c.id
				WHERE a.fk_pabrik = ".$pabrik." AND TYPE = 'OUT' AND ISNULL(no_berita_acara,'') <> ''
				AND CAST(tanggal_keluar AS DATE) BETWEEN '".$from."' AND '".$to."'
			) a
			WHERE stok IS NULL";	
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_ba_logbook_b3_detail($no_ba){
		$string	= "SELECT a.fk_pabrik, b.kode, b.nama, c.kode_vendor, c.nama_vendor, a.no_berita_acara, a.no_manifest, 
				CASE a.[type] WHEN 'IN' THEN 'Limbah Masuk' WHEN 'OUT' THEN 'Limbah Keluar' ELSE '' END tipe, 
				a.tanggal_keluar, a.fk_limbah, d.jenis_limbah, a.stok, a.quantity, e.keterangan satuan, 
				f.konversi_ton, a.jenis_kendaraan, a.nomor_kendaraan, a.nama_driver, a.lampiran1, a.lampiran2, a.lampiran3
				FROM tbl_she_tr_b3_limbah a
				LEFT JOIN tbl_inv_pabrik b
					ON a.fk_pabrik = b.id_pabrik
				LEFT JOIN tbl_she_vendor c
					ON a.fk_vendor = c.id
				LEFT JOIN tbl_she_limbah d
					ON a.fk_limbah = d.id
				LEFT JOIN tbl_she_master_satuan e
					ON d.fk_satuan = e.id_uom
				LEFT JOIN tbl_she_konversi_limbah f
					ON a.fk_limbah = f.fk_limbah
					AND a.fk_pabrik = f.fk_pabrik
				WHERE no_berita_acara = '".str_replace('-', '/', $no_ba)."'
				ORDER BY a.id";	
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function preview_data_ba_logbook_b3($no_ba){
		$string	= "SELECT a.fk_pabrik, b.kode, b.nama, c.kode_vendor, c.nama_vendor, a.no_berita_acara, a.no_manifest,
				CONVERT(VARCHAR,a.tanggal_keluar,106) tanggal,
				CASE DATEPART(DW,a.tanggal_keluar) 
					WHEN 1 THEN 'Minggu' 
					WHEN 2 THEN 'Senin' 
					WHEN 3 THEN 'Selasa'
					WHEN 4 THEN 'Rabu'
					WHEN 5 THEN 'Kamis'
					WHEN 6 THEN 'Jumat'
					WHEN 7 THEN 'Sabtu' END	hari,				
				a.fk_limbah, d.jenis_limbah, a.quantity, e.keterangan satuan, a.jenis_kendaraan, 
				a.nomor_kendaraan, a.nama_driver, g.nama nama_user,
				(
				select top 1 nama from tbl_karyawan
				where posisi like '%DIREKTUR OPERASIONAL%' and na = 'n' AND del = 'n' and id_gedung = b.kode
				) dirops,
				(
				select top 1 nama from tbl_karyawan
				where (posisi like '%MANAGER PABRIK%' or posisi like '%MANAGER KANTOR%') and na = 'n' AND del = 'n' and id_gedung = b.kode
				) manager
				FROM tbl_she_tr_b3_limbah a
				LEFT JOIN tbl_inv_pabrik b
					ON a.fk_pabrik = b.id_pabrik
				LEFT JOIN tbl_she_vendor c
					ON a.fk_vendor = c.id
				LEFT JOIN tbl_she_limbah d
					ON a.fk_limbah = d.id
				LEFT JOIN tbl_she_master_satuan e
					ON d.fk_satuan = e.id_uom
				LEFT JOIN tbl_user f
					ON a.login_buat = f.id_user
				LEFT JOIN tbl_karyawan g
					ON f.id_karyawan = g.nik
				WHERE no_berita_acara = '".$no_ba."'
				ORDER BY a.id";	
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}


}

?>
