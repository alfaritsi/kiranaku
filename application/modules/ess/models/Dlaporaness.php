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

class Dlaporaness extends CI_Model{
	function get_data_bagian($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		$string = "select ZTBPA0003.*, ZTBPA0003.DESCR as group_produksi from sapsync.dbo.ZTBPA0003";
		$query = $this->db->query($string);

		return $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_group($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		$string = "select * from sapsync.dbo.ZTBPA0004";
		$query = $this->db->query($string);

		return $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_mp($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		// var_dump($string);
		$string = "
				select 
				tbl_karyawan.bagian,
				tbl_karyawan.group_produksi,
				(
				  select 
				  count(distinct(tbl_karyawan2.nik)) 
				  from 
				  vw_data_mapping as tbl_karyawan2 
				  where 
				  1=1
				  and tbl_karyawan2.bagian=tbl_karyawan.bagian
				  and tbl_karyawan2.group_produksi=tbl_karyawan.group_produksi
				  and tbl_karyawan2.gsber='".base64_decode($this->session->userdata("-gsber-"))."'
				) as jumlah_mp,
				(
				  select  
				  count(distinct(absensi.nik)) 
				  from 
				  vw_absensi_karyawan as absensi
				  where 
				  1=1
				  and CONVERT(date, absensi.tanggal)=CONVERT(date, getdate())
				  and absensi.bagian=tbl_karyawan.bagian
				  and absensi.group_produksi=tbl_karyawan.group_produksi
				  and absensi.gsber='".base64_decode($this->session->userdata("-gsber-"))."'
				) as jumlah_hadir				
				from 
				vw_data_mapping as tbl_karyawan
				where
				tbl_karyawan.na='n'
				and tbl_karyawan.ho='n'
				and tbl_karyawan.bagian!=''
				and tbl_karyawan.group_produksi!=''
				and tbl_karyawan.bagian!='-'
				and tbl_karyawan.group_produksi!='-'
				and tbl_karyawan.gsber='".base64_decode($this->session->userdata("-gsber-"))."'
				group by
				tbl_karyawan.bagian,
				tbl_karyawan.group_produksi
			";
		// echo "<pre>$string</pre>";	
		$query = $this->db->query($string);
		// var_dump($query->result());
		return $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_absen($conn = NULL, $group_produksi = NULL, $bagian = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		// var_dump($string);
		$string = "
					select   
					tbl_karyawan.nik,
					tbl_karyawan.nama,
					tbl_karyawan.jabatan,
					tbl_karyawan.group_produksi, 
					tbl_karyawan.bagian,
					(select top 1 SUBSTRING(ZDMTM0002.TPROG, 4, 1) from sapsync.dbo.ZDMTM0002 where RIGHT('00000000' + CAST(tbl_karyawan.nik AS VARCHAR(8)), 8)=ZDMTM0002.PERNR order by ZDMTM0002.datum desc) as sub_bagian
					from 
					vw_data_mapping as tbl_karyawan  
					where
					tbl_karyawan.na='n'
					and tbl_karyawan.ho='n'
					and tbl_karyawan.group_produksi='".$group_produksi."'
					and tbl_karyawan.bagian='".$bagian."'
					and tbl_karyawan.gsber='".base64_decode($this->session->userdata("-gsber-"))."'
					and tbl_karyawan.nik not in 
					(
						select  
						distinct(absensi.nik)
						from 
						vw_absensi_karyawan as absensi
						where 
						1=1
						and (absensi.list_ci is not null or absensi.list_co is not null)
						and CONVERT(date, absensi.tanggal)=CONVERT(date, getdate())
						and absensi.group_produksi='".$group_produksi."'
						and absensi.bagian='".$bagian."'
						and absensi.gsber='".base64_decode($this->session->userdata("-gsber-"))."'
					)
				"; 
		// echo"<pre>$string</pre>";		
		$query = $this->db->query($string);
		// var_dump($query->result());
		return $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_absensi_bom($conn = NULL, $nik = NULL, $active = NULL, $deleted = 'n', $group_produksi = NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		// // var_dump($string);
		// $string = "select getdate()";
		// $this->db->query($string);
		mssql_query("SET ANSI_NULLS ON;");
		mssql_query("SET ANSI_WARNINGS ON;"); 		
		$this->datatables->select(" 
									nik,
									nama,
									group_produksi,
									jabatan,
									tanggal,
									list_ci,
									list_co
									");
		$this->datatables->from('vw_absensi_karyawan');				
		if ($nik !== NULL) {
			$this->datatables->where('nik', $nik);
		}
		if ($active !== NULL) {
			$this->datatables->where('na', $active);
		}
		if ($deleted !== NULL) {
			$this->datatables->where('del', $deleted);
		}
		if($group_produksi != NULL){
			if(is_string($group_produksi)) $group_produksi = explode(",", $group_produksi);
			$this->datatables->where_in('group_produksi', $group_produksi);
		}else{
			// $this->datatables->where("group_produksi is not null");
		}
		if (($tanggal_awal !== NULL)and($tanggal_akhir !== NULL)) {	
			$this->datatables->where("tanggal between '".$this->generate->regenerateDateFormat($tanggal_awal)."' and '".$this->generate->regenerateDateFormat($tanggal_akhir)."'");
		}else{
			$this->datatables->where("tanggal between '".date('Y-m-d')."' and '".date('Y-m-d')."'");
		}
		$this->datatables->where("ho='n' and gsber='".base64_decode($this->session->userdata("-gsber-"))."'");
		// $this->datatables->where("nik='8347'");
		// if(base64_decode($this->session->userdata("-ho-")=='y')){
			// $this->datatables->where('ho', 'y');
		// }else{
			// $this->datatables->where("gsber='".base64_decode($this->session->userdata("-gsber-"))."'");
		// }
		
		
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("nik"));
		return $this->general->jsonify($raw);
	}
	
	function get_data_mapping_bom($conn = NULL, $nik = NULL, $active = NULL, $deleted = 'n', $pabrik = NULL, $group_produksi = NULL, $bagian = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->datatables->select(" 
									nik,
									nama,
									email,
									gsber,
									jabatan,
									group_produksi,
									bagian
									");
		$this->datatables->from('vw_data_mapping');				
		if ($nik !== NULL) {
			$this->datatables->where('nik', $nik);
		}
		if ($pabrik !== NULL) {
			$this->datatables->where('gsber', $pabrik);
		}

		if($group_produksi != NULL){
			if(is_string($group_produksi)) $group_produksi = explode(",", $group_produksi);
			$this->datatables->where_in('group_produksi_status', $group_produksi);
		}
		if($bagian != NULL){
			if(is_string($bagian)) $bagian = explode(",", $bagian);
			$this->datatables->where_in('bagian_status', $bagian);
		}
		
		
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("nik"));
		return $this->general->jsonify($raw);
	}
	
	function get_data_mapping($conn = NULL, $nik = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.*');
		$this->db->from('vw_data_mapping as tbl_karyawan');						   
		if ($nik !== NULL) {
			$this->db->where('tbl_karyawan.nik', $nik);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_absensi($conn = NULL, $nik = NULL, $active = NULL, $deleted = 'n', $group_produksi = NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		// if(is_string($group_produksi)){
			// $group_produksi = explode(",", $group_produksi);
		// } 
		// $this->datatables->where_in('group_produksi', $group_produksi);
		// if($group_produksi !== NULL){
			// // $filter_group = "";
			// foreach ($group_produksi as $dt) {
				// $filter_group += "'".$dt."',";
			// }
		// }
		// foreach ($group_produksi as $dt) {
			// $filter_group += $dt."',";
		// }
		
		
		// $filter_group_produksi = ($group_produksi !== NULL)?"and tbl_karyawanxx.descr in(aa)":"";
		$tanggal_awal 	= ($tanggal_awal !== NULL)?$this->generate->regenerateDateFormat($tanggal_awal):date('Y-m-d');
		$tanggal_akhir 	= ($tanggal_awal !== NULL)?$this->generate->regenerateDateFormat($tanggal_akhir):date('Y-m-d');
		// var_dump($string);
		$string = "
					select
					top 10
					tbl_karyawan.nik,
					tbl_karyawan.nama,
					tbl_karyawan.gsber,
					tbl_karyawan.na,
					tbl_karyawan.ho,
					tbl_karyawan.posst as jabatan,
					tbl_karyawan.descr as group_produksi, 
					tbl_karyawan.descr1 as bagian,
					convert(varchar, transaksi.FdDate, 104) as tanggal,
					(
						SELECT 
						top 1 CONVERT(VARCHAR(6),transaksi2.FdDate, 108)
						FROM [10.0.0.31].absensi.dbo.transaksi transaksi2
						WHERE 
						transaksi2.FcInOut = 'IN'
						and convert(varchar, transaksi2.FdDate, 104)=convert(varchar, transaksi.FdDate, 104)
						and transaksi2.FcIdNo=RIGHT('00000000' + CAST(tbl_karyawan.nik AS VARCHAR(8)), 8)
						ORDER BY transaksi2.FdDate asc
					)as list_ci,
					(
						SELECT 
						top 1 CONVERT(VARCHAR(6),transaksi2.FdDate, 108)
						FROM [10.0.0.31].absensi.dbo.transaksi transaksi2
						WHERE 
						transaksi2.FcInOut = 'OUT'
						and convert(varchar, transaksi2.FdDate, 104)=convert(varchar, transaksi.FdDate, 104)
						and transaksi2.FcIdNo=RIGHT('00000000' + CAST(tbl_karyawan.nik AS VARCHAR(8)), 8)
						ORDER BY transaksi2.FdDate desc
					)as list_co
					from 
					[10.0.0.31].absensi.dbo.transaksi transaksi
					left outer join [10.0.0.32].portal.dbo.tbl_karyawan on RIGHT('00000000' + CAST(tbl_karyawan.nik AS VARCHAR(8)), 8)=transaksi.FcIdNo
					where 
					1=1
					and tbl_karyawan.na='n'
					and tbl_karyawan.ho='n'
					and tbl_karyawan.gsber='".base64_decode($this->session->userdata("-gsber-"))."'
					and CONVERT(date, transaksi.FdDate) between '".$tanggal_awal."' and '".$tanggal_akhir."'
					group by
					tbl_karyawan.nik,
					tbl_karyawan.nama,
					tbl_karyawan.gsber,
					tbl_karyawan.na,
					tbl_karyawan.ho,
					tbl_karyawan.posst,
					tbl_karyawan.descr, 
					tbl_karyawan.descr1,
					convert(varchar, transaksi.FdDate, 104)
					";
		// if ($werks !== NULL) {
			// $string .= " AND ZDMMSPLANT.WERKS='$werks'";
		// }
					
		$query = $this->db->query($string);
		// var_dump($query->result());
		return $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_absensi_sp() {
		// if ($conn !== NULL)
			$this->general->connectDbPortal();
		
        $query = $this->db->query("EXEC portal_dev.dbo.SP_Absensi '".base64_decode($this->session->userdata("-gsber-"))."', NULL, '20210101', '".date('Ymd')."' ");

        $result = $query->result();

        return $result;
		

		// $string	= "EXEC portal_dev.dbo.SP_Absensi '".base64_decode($this->session->userdata("-gsber-"))."', NULL, '20210101', '20210105'";

		
		// $query = $this->db->query($string);
		// var_dump($query->result());
		// exit();
		// return $query->result();

		// if ($conn !== NULL)
			// $this->general->closeDb();
		// return $result;
	}
	
}
?>