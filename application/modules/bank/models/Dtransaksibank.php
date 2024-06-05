<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

class Dtransaksibank extends CI_Model
{
	function get_data_prioritas($conn = NULL, $pabrik = NULL, $id_role = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_karyawan.nik");
		$this->db->select("tbl_karyawan.nama");
		$this->db->select("tbl_karyawan.email");
		$this->db->from('tbl_karyawan');
		$this->db->where('tbl_karyawan.na', 'n');
		$this->db->where("tbl_karyawan.posst in
							(
								select tbl_bank_user_role.[user] 
								from 
								tbl_bank_user_role
								left outer join tbl_bank_role on tbl_bank_role.id_role=tbl_bank_user_role.id_role
								where 
								CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0
								and tbl_bank_role.tipe_user='posisi'
								and tbl_bank_role.id_role='$id_role'
							)
						");
		$query1 = $this->db->get_compiled_select();

		$this->db->select("tbl_karyawan.nik");
		$this->db->select("tbl_karyawan.nama");
		$this->db->select("tbl_karyawan.email");
		$this->db->from('tbl_karyawan');
		$this->db->where('tbl_karyawan.na', 'n');
		$this->db->where("CONVERT(varchar(255), tbl_karyawan.nik) in
							(
								select 
								tbl_bank_user_role.[user]
								from 
								tbl_bank_user_role
								left outer join tbl_bank_role on tbl_bank_role.id_role=tbl_bank_user_role.id_role
								where 
								CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0
								and tbl_bank_role.tipe_user='nik'
								and tbl_bank_role.id_role='$id_role'
							)
						");
		$query2 = $this->db->get_compiled_select();

		$query = $this->db->query($query1 . ' UNION ' . $query2);
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_bank_dokumen_temp($conn = NULL, $id_data_temp = NULL, $id_dokumen = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('*');
		$this->db->from('tbl_bank_data_dokumen_temp');
		$this->db->where("na='n'");
		if ($id_data_temp !== NULL) {
			$this->db->where('id_data_temp', $id_data_temp);
		}
		if ($id_dokumen !== NULL) {
			$this->db->where('id_dokumen', $id_dokumen);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	/*
	function get_data_email_user_all($conn = NULL, $pabrik = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_karyawan.nik");
		$this->db->select("tbl_karyawan.nama");
		$this->db->select("tbl_karyawan.email");
		$this->db->from('tbl_karyawan');
		$this->db->where('tbl_karyawan.na', 'n');
		$this->db->where("tbl_karyawan.posst in
							(
								select tbl_bank_user_role.[user] 
								from 
								tbl_bank_user_role
								left outer join tbl_bank_role on tbl_bank_role.id_role=tbl_bank_user_role.id_role
								where 
								CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0
								and tbl_bank_role.tipe_user='posisi'
							)
						");
		$query1 = $this->db->get_compiled_select();

		$this->db->select("tbl_karyawan.nik");
		$this->db->select("tbl_karyawan.nama");
		$this->db->select("tbl_karyawan.email");
		$this->db->from('tbl_karyawan');
		$this->db->where('tbl_karyawan.na', 'n');
		$this->db->where("CONVERT(varchar(255), tbl_karyawan.nik) in
							(
								select 
								tbl_bank_user_role.[user]
								from 
								tbl_bank_user_role
								left outer join tbl_bank_role on tbl_bank_role.id_role=tbl_bank_user_role.id_role
								where 
								CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0
								and tbl_bank_role.tipe_user='nik'
							)
						");
		$query2 = $this->db->get_compiled_select();

		// $this->db->select("tbl_karyawan.nik");
		// $this->db->select("tbl_karyawan.nama");
		// $this->db->select("tbl_karyawan.email");
		// $this->db->from('tbl_karyawan');
		// $this->db->where('tbl_karyawan.na', 'n');
		// $this->db->where("tbl_karyawan.posst='LEGAL OFFICER'");
		// $query3 = $this->db->get_compiled_select();	 	

		// $query = $this->db->query($query1 . ' UNION ' . $query2 . ' UNION ' . $query3);
		$query = $this->db->query($query1 . ' UNION ' . $query2);
		$result = $query->result();

		// $query  = $this->db->get();
		// $result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_email_user($conn = NULL, $tipe_user = NULL, $id_role = NULL, $pabrik = NULL, $id_user = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_karyawan.nik");
		$this->db->select("tbl_karyawan.nama");
		$this->db->select("tbl_karyawan.email");
		$this->db->from('tbl_karyawan');
		$this->db->where('tbl_karyawan.na', 'n');
		if ($id_role !== NULL) {
			if ($tipe_user == 'nik') {
				$this->db->where("tbl_karyawan.nik in(select tbl_bank_user_role.[user] from tbl_bank_user_role where tbl_bank_user_role.id_role=$id_role and tbl_bank_user_role.pabrik in('" . $pabrik . "'))");
			} else {
				$this->db->where("tbl_karyawan.posst in(select tbl_bank_user_role.[user] from tbl_bank_user_role where tbl_bank_user_role.id_role=$id_role and tbl_bank_user_role.pabrik in('" . $pabrik . "'))");
			}
		}
		if ($pabrik !== NULL) {
			$this->db->where("CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE((select top 1 tbl_bank_user_role.pabrik from tbl_bank_user_role where tbl_bank_user_role.id_role=$id_role), RTRIM(','),''',''')+'''') > 0");
		}
		if ($id_user !== NULL) {
			$this->db->where("tbl_karyawan.nik in(select tbl_user.id_karyawan from tbl_user where tbl_user.id_user=$id_user)");
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	*/

	function get_data_email_user($param = NULL)
	{
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		$query = $this->db->query("EXEC SP_Kiranaku_Bank_Recipient_Email '" . $param['no'] . "'");
		$result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();
		return $result;
	}
	function get_data_user_role($conn = NULL, $nik = NULL, $posst = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select("tbl_bank_role.*");
		$this->db->select("tbl_bank_user_role.user");
		$this->db->select("tbl_bank_user_role.pabrik");
		$this->db->from('tbl_bank_user_role');
		$this->db->join('tbl_bank_role', 'tbl_bank_role.id_role = tbl_bank_user_role.id_role', 'left outer');
		if ($nik !== NULL) {
			$this->db->where('tbl_bank_user_role.user', $nik);
			$this->db->where('tbl_bank_user_role.na', 'n');
		}
		$query1 = $this->db->get_compiled_select();

		$this->db->select("tbl_bank_role.*");
		$this->db->select("tbl_bank_user_role.user");
		$this->db->select("tbl_bank_user_role.pabrik");
		$this->db->from('tbl_bank_user_role');
		$this->db->join('tbl_bank_role', 'tbl_bank_role.id_role = tbl_bank_user_role.id_role', 'left outer');
		if ($posst !== NULL) {
			$this->db->where('tbl_bank_user_role.user', $posst);
			$this->db->where('tbl_bank_user_role.na', 'n');
		}
		$query2 = $this->db->get_compiled_select();

		$query = $this->db->query($query1 . ' UNION ' . $query2);
		// $query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_user_role_status($conn = NULL, $status)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select("tbl_bank_role.*");
		$this->db->from('tbl_bank_role');
		if ($status !== NULL) {
			$this->db->where('tbl_bank_role.level', $status);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_nomor($conn = NULL, $pabrik = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select("right('0000'+convert(varchar(5), (count(*)+1)), 4) as nomor");
		$this->db->from('tbl_bank_data_temp');
		$this->db->where("CAST(DATEPART(YEAR,tanggal) AS VARCHAR(4))='" . date('Y') . "'");
		if ($pabrik !== NULL) {
			$this->db->where('tbl_bank_data_temp.pabrik', $pabrik);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_bank_auto($cari = NULL)
	{
		$this->general->connectDbPortal();

		$this->db->select('tbl_bank_data.id_data as id');
		$this->db->select('tbl_bank_data.id_data');
		$this->db->select('tbl_bank_data.nama_bank');
		$this->db->select('tbl_bank_data.cabang_bank');
		$this->db->select('tbl_bank_data.nomor_rekening');
		$this->db->from('tbl_bank_data');
		if ($cari !== NULL) {
			$this->db->where("(tbl_bank_data.nomor_rekening like '%" . $cari . "%' or tbl_bank_data.nama_bank like '%" . $cari . "%' or tbl_bank_data.cabang_bank like '%" . $cari . "%') ");
		}
		$this->db->where('tbl_bank_data.na', 'n');
		$this->db->where('tbl_bank_data.del', 'n');
		$this->db->order_by('tbl_bank_data.nama_bank', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_user_autocomplete($cari = NULL, $jenis = NULL, $pabrik = NULL)
	{
		$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.nik as id');
		$this->db->select('tbl_karyawan.nik');
		$this->db->select('tbl_karyawan.nama');
		$this->db->from('tbl_user');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan AND tbl_karyawan.na = \'n\'  AND tbl_karyawan.del = \'n\'', 'inner');
		if ($cari !== NULL) {
			$this->db->where("(tbl_karyawan.nik like '%" . $cari . "%' or tbl_karyawan.nama like '%" . $cari . "%') ");
		}
		if ($jenis !== NULL) {
			if ($jenis == 'prioritas1') {
				$this->db->where("
									tbl_karyawan.nik in(select tbl_bank_user_role.[user] from tbl_bank_user_role where id_role='2' and CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0 ) 
									or
									tbl_karyawan.posst in(select tbl_bank_user_role.[user] from tbl_bank_user_role where id_role='2' and CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0 ) 
								");
			}
			if ($jenis == 'prioritas2') {
				$this->db->where("
									tbl_karyawan.nik in(select tbl_bank_user_role.[user] from tbl_bank_user_role where id_role='1' and CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0 ) 
									or
									tbl_karyawan.posst in(select tbl_bank_user_role.[user] from tbl_bank_user_role where id_role='1' and CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0 ) 
								");
			}
			if ($jenis == 'pendamping') {
				$this->db->where("1=1");
				// $this->db->where("tbl_karyawan.gsber='$pabrik'");
				// $this->db->where("
									// tbl_karyawan.gsber='$pabrik'
									// or
									// tbl_karyawan.nik in(select tbl_bank_user_role.[user] from tbl_bank_user_role where id_role in (1,2,10) and CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0 ) 
									// or
									// tbl_karyawan.posst in(select tbl_bank_user_role.[user] from tbl_bank_user_role where id_role in (1,2,10) and CHARINDEX(''''+CONVERT(varchar(10), '$pabrik')+'''',''''+REPLACE(tbl_bank_user_role.pabrik, RTRIM(','),''',''')+'''') > 0 ) 
								// ");
			}
		}
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_rekening_autocomplete($cari = NULL, $pabrik = NULL, $id_data = NULL)
	{
		$this->general->connectDbPortal();

		$this->db->select('tbl_bank_data.id_data as id');
		$this->db->select('tbl_bank_data.id_data');
		$this->db->select('tbl_bank_data.nama_bank');
		$this->db->select('tbl_bank_data.cabang_bank');
		$this->db->select('tbl_bank_data.nomor_rekening');
		$this->db->from('tbl_bank_data');
		if ($cari !== NULL) {
			$this->db->where("(tbl_bank_data.nomor_rekening like '%" . $cari . "%' or tbl_bank_data.nama_bank like '%" . $cari . "%' or tbl_bank_data.cabang_bank like '%" . $cari . "%') ");
		}
		if ($pabrik !== NULL) {
			$this->db->where('tbl_bank_data.pabrik', $pabrik);
		}
		if ($id_data !== NULL) {
			$this->db->where("tbl_bank_data.id_data!='$id_data'");
		}
		// $this->db->where('tbl_bank_data.status_sap', 'y');
		$this->db->where('tbl_bank_data.na', 'n');
		$this->db->where('tbl_bank_data.del', 'n');
		$this->db->order_by('tbl_bank_data.nama_bank', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_mata_uang($conn = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('TCURC.WAERS as mata_uang');
		$this->db->from('SAPSYNC.dbo.TCURC');
		$this->db->order_by("TCURC.WAERS", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_karyawan($conn = NULL, $id_user = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.*');
		$this->db->from('tbl_karyawan');
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan', 'left outer');
		if ($id_user !== NULL) {
			$this->db->where('tbl_user.id_user', $id_user);
		}
		$this->db->order_by("tbl_karyawan.id_karyawan", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_kualifikasi_dokumen($conn = NULL, $id_kualifikasi_spk = NULL, $active = NULL, $deleted = 'n', $id_data = NULL, $id_data_temp = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_vendor_master_dokumen.id_master_dokumen');
		$this->db->select('tbl_vendor_master_dokumen.nama as nama_dokumen');
		$this->db->select("(select top 1 CONVERT(varchar, tbl_vendor_data_dokumen.tanggal_awal, 104) from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n'  order by tbl_vendor_data_dokumen.tanggal_buat desc) as tanggal_awal");
		$this->db->select("(select top 1 CONVERT(varchar, tbl_vendor_data_dokumen.tanggal_akhir, 104) from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n' order by tbl_vendor_data_dokumen.tanggal_buat desc) as tanggal_akhir");
		$this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.id_file=(select top 1 tbl_vendor_data_dokumen.id_file from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n'  order by tbl_vendor_data_dokumen.tanggal_buat desc) order by tbl_file.id_file desc)as link_file");
		$this->db->select("(select top 1 tbl_file.nama from tbl_file where tbl_file.id_file=(select top 1 tbl_vendor_data_dokumen.id_file from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n'  order by tbl_vendor_data_dokumen.tanggal_buat desc) order by tbl_file.id_file desc)as nama_dokumen_hide");

		$this->db->select("(select top 1 CONVERT(varchar, tbl_vendor_data_dokumen_temp.tanggal_awal, 104) from tbl_vendor_data_dokumen_temp where tbl_vendor_data_dokumen_temp.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen_temp.id_data_temp='$id_data_temp' and tbl_vendor_data_dokumen_temp.na='n'  order by tbl_vendor_data_dokumen_temp.tanggal_buat desc) as tanggal_awal_temp");
		$this->db->select("(select top 1 CONVERT(varchar, tbl_vendor_data_dokumen_temp.tanggal_akhir, 104) from tbl_vendor_data_dokumen_temp where tbl_vendor_data_dokumen_temp.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen_temp.id_data_temp='$id_data_temp' and tbl_vendor_data_dokumen_temp.na='n' order by tbl_vendor_data_dokumen_temp.tanggal_buat desc) as tanggal_akhir_temp");
		$this->db->select("(select top 1 tbl_file_temp.link from tbl_file_temp where tbl_file_temp.id_file=(select top 1 tbl_vendor_data_dokumen_temp.id_file from tbl_vendor_data_dokumen_temp where tbl_vendor_data_dokumen_temp.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen_temp.id_data_temp='$id_data_temp' and tbl_vendor_data_dokumen_temp.na='n'  order by tbl_vendor_data_dokumen_temp.tanggal_buat desc) order by tbl_file_temp.id_file desc)as link_file_temp");
		$this->db->select("(select top 1 tbl_file_temp.nama from tbl_file_temp where tbl_file_temp.id_file=(select top 1 tbl_vendor_data_dokumen_temp.id_file from tbl_vendor_data_dokumen_temp where tbl_vendor_data_dokumen_temp.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen_temp.id_data_temp='$id_data_temp' and tbl_vendor_data_dokumen_temp.na='n'  order by tbl_vendor_data_dokumen_temp.tanggal_buat desc) order by tbl_file_temp.id_file desc)as nama_dokumen_hide_temp");
		$this->db->from('tbl_vendor_kualifikasi_dokumen');
		$this->db->join('tbl_vendor_master_dokumen', 'tbl_vendor_master_dokumen.id_master_dokumen = tbl_vendor_kualifikasi_dokumen.id_master_dokumen', 'left outer');
		if ($id_kualifikasi_spk != NULL) {
			if (is_string($id_kualifikasi_spk)) $id_kualifikasi_spk = explode(",", $id_kualifikasi_spk);
			$this->db->where_in('tbl_vendor_kualifikasi_dokumen.id_kualifikasi_spk', $id_kualifikasi_spk);
		}
		if ($active != NULL) {
			$this->db->where('tbl_vendor_kualifikasi_dokumen.na', $active);
		}
		if ($deleted != NULL) {
			$this->db->where('tbl_vendor_kualifikasi_dokumen.del', $deleted);
		}

		$this->db->where("tbl_vendor_master_dokumen.na='n'");

		$this->db->group_by('tbl_vendor_master_dokumen.id_master_dokumen, tbl_vendor_master_dokumen.nama');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_jenis_vendor_dokumen($conn = NULL, $id_oto_vendor = NULL, $active = NULL, $deleted = 'n', $id_jenis_vendor = NULL, $id_data = NULL, $id_data_temp = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_master_dokumen.id_master_dokumen');
		$this->db->select('tbl_vendor_master_dokumen.nama as nama_dokumen');
		$this->db->select("(select top 1 CONVERT(varchar, tbl_vendor_data_dokumen.tanggal_awal, 104) from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n' order by tbl_vendor_data_dokumen.tanggal_buat desc) as tanggal_awal");
		$this->db->select("(select top 1 CONVERT(varchar, tbl_vendor_data_dokumen.tanggal_akhir, 104) from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n' order by tbl_vendor_data_dokumen.tanggal_buat desc) as tanggal_akhir");
		$this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.id_file=(select top 1 tbl_vendor_data_dokumen.id_file from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n'  order by tbl_vendor_data_dokumen.tanggal_buat desc) order by tbl_file.id_file desc)as link_file");
		$this->db->select("(select top 1 tbl_file.nama from tbl_file where tbl_file.id_file=(select top 1 tbl_vendor_data_dokumen.id_file from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data'  and tbl_vendor_data_dokumen.na='n' order by tbl_vendor_data_dokumen.tanggal_buat desc) order by tbl_file.id_file desc)as nama_dokumen_hide");
		$this->db->select("(select top 1 CONVERT(varchar, tbl_vendor_data_dokumen_temp.tanggal_awal, 104) from tbl_vendor_data_dokumen_temp where tbl_vendor_data_dokumen_temp.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen_temp.id_data_temp='$id_data_temp' and tbl_vendor_data_dokumen_temp.na='n' order by tbl_vendor_data_dokumen_temp.tanggal_buat desc) as tanggal_awal_temp");
		$this->db->select("(select top 1 CONVERT(varchar, tbl_vendor_data_dokumen_temp.tanggal_akhir, 104) from tbl_vendor_data_dokumen_temp where tbl_vendor_data_dokumen_temp.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen_temp.id_data_temp='$id_data_temp' and tbl_vendor_data_dokumen_temp.na='n' order by tbl_vendor_data_dokumen_temp.tanggal_buat desc) as tanggal_akhir_temp");
		$this->db->select("(select top 1 tbl_file_temp.link from tbl_file_temp where tbl_file_temp.id_file=(select top 1 tbl_vendor_data_dokumen_temp.id_file from tbl_vendor_data_dokumen_temp where tbl_vendor_data_dokumen_temp.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen_temp.id_data_temp='$id_data_temp' and tbl_vendor_data_dokumen_temp.na='n'  order by tbl_vendor_data_dokumen_temp.tanggal_buat desc) order by tbl_file_temp.id_file desc)as link_file_temp");
		$this->db->select("(select top 1 tbl_file_temp.nama from tbl_file_temp where tbl_file_temp.id_file=(select top 1 tbl_vendor_data_dokumen_temp.id_file from tbl_vendor_data_dokumen_temp where tbl_vendor_data_dokumen_temp.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen_temp.id_data_temp='$id_data_temp'  and tbl_vendor_data_dokumen_temp.na='n' order by tbl_vendor_data_dokumen_temp.tanggal_buat desc) order by tbl_file_temp.id_file desc)as nama_dokumen_hide_temp");
		$this->db->select('tbl_leg_oto_vendor.*');
		$this->db->from('tbl_leg_oto_vendor');
		$this->db->join('tbl_vendor_master_dokumen', 'tbl_vendor_master_dokumen.id_master_dokumen = tbl_leg_oto_vendor.id_master_dokumen', 'left outer');
		if ($id_oto_vendor !== NULL) {
			$this->db->where('tbl_leg_oto_vendor.id_oto_vendor', $id_oto_vendor);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_leg_oto_vendor.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_leg_oto_vendor.del', $deleted);
		}
		if ($id_jenis_vendor !== NULL) {
			$this->db->where('tbl_leg_oto_vendor.id_jenis_vendor', $id_jenis_vendor);
			$this->db->where('tbl_leg_oto_vendor.na', 'n');
		}
		$this->db->where('tbl_leg_oto_vendor.id_master_dokumen is not null');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_jenis($conn = NULL, $id_jenis_vendor = NULL, $active = NULL, $deleted = 'n')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_leg_jenis_vendor.*');
		$this->db->select('CASE
								WHEN tbl_leg_jenis_vendor.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_leg_jenis_vendor');
		if ($id_jenis_vendor !== NULL) {
			$this->db->where('tbl_leg_jenis_vendor.id_jenis_vendor', $id_jenis_vendor);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_leg_jenis_vendor.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_leg_jenis_vendor.del', $deleted);
		}
		$this->db->order_by("tbl_leg_jenis_vendor.jenis_vendor", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_kualifikasi($conn = NULL, $id_kualifikasi_spk = NULL, $active = NULL, $deleted = 'n')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_leg_kualifikasi_spk.*');
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
		$this->db->order_by("tbl_leg_kualifikasi_spk.kualifikasi_spk", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_nomorxx($conn = NULL, $id_item_group = NULL, $id_item_name = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		// $this->db->select("'$id_item_group-'+(select tbl_item_name.code from tbl_item_name where tbl_item_name.id_item_name='$id_item_name')+'-'+right('0000'+convert(varchar(4), (count(*)+1)), 4) as nomor");
		// $this->db->select("(select tbl_item_name.code from tbl_item_name where tbl_item_name.id_item_name='$id_item_name') as code");
		$this->db->select("
							'$id_item_group'+
							'-'+(select tbl_item_name.code from tbl_item_name where tbl_item_name.id_item_name='$id_item_name')+
							'-'+
								CASE
									WHEN count(*)=0 
									THEN '0001'
									ELSE right('0000'+convert(varchar(4), (select top 1 substring(tbl_item_spec2.code, 10, 4)+1 from tbl_item_spec as tbl_item_spec2 where tbl_item_spec2.id_item_group='$id_item_group' and tbl_item_spec2.id_item_name='$id_item_name' order by tbl_item_spec2.id_item_spec desc)), 4)
								END
							as nomor");
		$this->db->from('tbl_item_spec');
		if ($id_item_group !== NULL) {
			$this->db->where('tbl_item_spec.id_item_group', $id_item_group);
		}
		if ($id_item_name !== NULL) {
			$this->db->where('tbl_item_spec.id_item_name', $id_item_name);
		}
		$query  = $this->db->get();
		$result = $query->row();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	// function get_data_spec_bom($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n', $id_item_group = NULL, $id_item_name = NULL, $status = NULL, $filter_request_status = NULL) {
	// if ($conn !== NULL)
	// $this->general->connectDbPortal();
	// $this->datatables->select('tbl_item_spec.id_item_spec');
	// $this->datatables->select('tbl_item_spec.id_item_group');
	// $this->datatables->select('tbl_item_spec.id_item_name');
	// $this->datatables->select('tbl_item_spec.code');
	// $this->datatables->select('tbl_item_spec.description');
	// $this->datatables->select("tbl_item_name.description+' '+tbl_item_spec.description as description_detail");
	// $this->datatables->select('tbl_item_spec.purchase_type');
	// $this->datatables->select('tbl_item_spec.purchase_authorization');
	// $this->datatables->select('tbl_item_spec.beli_di_nsi2');
	// $this->datatables->select('tbl_item_spec.specification_check');
	// $this->datatables->select('tbl_item_spec.req');
	// $this->datatables->select('tbl_item_spec.na');
	// $this->datatables->select('tbl_item_group.description as group_description');
	// $this->datatables->select('tbl_item_group.mtart as group_mtart');
	// $this->datatables->select('tbl_item_name.code as name_code');
	// $this->datatables->select('tbl_item_name.description as name_description');
	// $this->datatables->from('tbl_item_spec');				
	// $this->datatables->join('tbl_item_group', 'tbl_item_group.id_item_group = tbl_item_spec.id_item_group', 'left outer');		
	// $this->datatables->join('tbl_item_name', 'tbl_item_name.id_item_name = tbl_item_spec.id_item_name', 'left outer');		
	// if ($id_item_spec !== NULL) {
	// $this->datatables->where('tbl_item_spec.id_item_spec', $id_item_spec);
	// }
	// if ($active !== NULL) {
	// $this->datatables->where('tbl_item_spec.na', $active);
	// }
	// if ($deleted !== NULL) {
	// $this->datatables->where('tbl_item_spec.del', $deleted);
	// }
	// if($id_item_group != NULL){
	// if(is_string($id_item_group)) $id_item_group = explode(",", $id_item_group);
	// $this->datatables->where_in('tbl_item_spec.id_item_group', $id_item_group);
	// }
	// if($id_item_name != NULL){
	// if(is_string($id_item_name)) $id_item_name = explode(",", $id_item_name);
	// $this->datatables->where_in('tbl_item_spec.id_item_name', $id_item_name);
	// }
	// if($status != NULL){
	// if(is_string($status)) $status = explode(",", $status);
	// $this->datatables->where_in('tbl_item_spec.na', $status);
	// }
	// if($filter_request_status != NULL){
	// if(is_string($filter_request_status)) $filter_request_status = explode(",", $filter_request_status);
	// $this->datatables->where_in('tbl_item_spec.req', $filter_request_status);
	// }
	// if ($conn !== NULL)
	// $this->general->closeDb();

	// $return = $this->datatables->generate();
	// $raw = json_decode($return, true);
	// // $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
	// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
	// return $this->general->jsonify($raw);
	// }

	function get_data_spec_bom($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n', $id_item_group = NULL, $id_item_name = NULL, $status = NULL, $filter_request_status = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->datatables->select(' id_item_spec,
									id_item_request,
									id_item_group,
									id_item_name,
									code,
									description,
									plant,
									plant_extend,
									lgort,
									msehi_uom,
									msehi_order,
									old_material_number,
									ekgrp,
									availability_check,
									mrp_group,
									mrp_type,
									dispo,
									service_level,
									disls,
									period_indicator,
									sales_plant,
									vtweg,
									spart,
									net_weight,
									gross_weight,
									gen_item_cat_group,
									material_pricing_group,
									material_statistic_group,
									acct_assignment_group,
									taxm1,
									login_buat,
									tanggal_buat,
									login_edit,
									tanggal_edit,
									purchase_type,
									purchase_authorization,
									beli_di_nsi2,
									specification_check,
									xchpf,
									req,
									na,
									del,
									detail,
									umrez,
									prmod,
									peran,
									anzpr,
									kzini,
									siggr,
									description_detail,
									group_description,
									group_mtart,
									name_code,
									name_description');
		$this->datatables->from('vw_material_spec');
		if ($id_item_spec !== NULL) {
			$this->datatables->where('id_item_spec', $id_item_spec);
		}
		if ($active !== NULL) {
			$this->datatables->where('na', $active);
		}
		if ($deleted !== NULL) {
			$this->datatables->where('del', $deleted);
		}
		if ($id_item_group != NULL) {
			if (is_string($id_item_group)) $id_item_group = explode(",", $id_item_group);
			$this->datatables->where_in('id_item_group', $id_item_group);
		}
		if ($id_item_name != NULL) {
			if (is_string($id_item_name)) $id_item_name = explode(",", $id_item_name);
			$this->datatables->where_in('id_item_name', $id_item_name);
		}
		if ($status != NULL) {
			if (is_string($status)) $status = explode(",", $status);
			$this->datatables->where_in('tbl_item_spec.na', $status);
		}
		if ($filter_request_status != NULL) {
			if (is_string($filter_request_status)) $filter_request_status = explode(",", $filter_request_status);
			$this->datatables->where_in('req', $filter_request_status);
		}
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
		return $this->general->jsonify($raw);
	}
	function get_data_bank_bom($conn = NULL, $id_data = NULL, $active = NULL, $deleted = 'n', $pabrik_filter = NULL, $status_sap = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$id_user	= base64_decode($this->session->userdata("-id_user-"));
		$nik		= base64_decode($this->session->userdata("-nik-"));
		$posst		= base64_decode($this->session->userdata("-posst-"));
		$user_role	= $this->get_data_user_role("open", $nik, $posst);
		$level 		= $user_role[0]->level;
		$this->datatables->select(" 
									$level as level,
									$id_user as id_user,
									id_data,
									asal_pengajuan,
									jenis_pengajuan,
									pabrik,
									nomor,
									tanggal_format,
									status,
									no_coa,
									nama_bank,
									cabang_bank,
									nomor_rekening,
									mata_uang,
									prioritas1,
									nama_prioritas1,
									prioritas2,
									nama_prioritas2,
									list_pendamping,
									caption_list_pendamping,
									caption_tujuan,
									caption_na,
									tujuan,
									status_sap,
									label_status,
									label_status_detail,
									list_pendamping,
									login_buat,
									na,
									del");
		$this->datatables->from('vw_bank_data');
		//filter_pabrik_by session login
		if (is_string($user_role[0]->pabrik)) $filter_pabrik = explode(",", $user_role[0]->pabrik);
		$this->datatables->where_in('pabrik', $filter_pabrik);

		if ($id_data !== NULL) {
			$this->datatables->where('id_data', $id_data);
		}

		if ($pabrik_filter != NULL) {
			if (is_string($pabrik_filter)) $pabrik_filter = explode(",", $pabrik_filter);
			$this->datatables->where_in('pabrik', $pabrik_filter);
		}

		if ($status_sap !== NULL) {
			$this->datatables->where('status_sap', $status_sap);
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_data"));
		return $this->general->jsonify($raw);
	}
	function get_data_bank($conn = NULL, $id_data = NULL, $active = NULL, $deleted = 'n', $nama_bank = NULL, $cabang_bank = NULL, $nomor_rekening = NULL, $pabrik_filter = NULL, $status_sap = NULL, $ck_pabrik = NULL, $ck_tujuan = NULL, $ck_tujuan_detail = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$id_user	= base64_decode($this->session->userdata("-id_user-"));
		$nik		= base64_decode($this->session->userdata("-nik-"));
		$posst		= base64_decode($this->session->userdata("-posst-"));
		$user_role	= $this->get_data_user_role("open", $nik, $posst);
		$level 		= $user_role[0]->level;

		$this->db->select('*');
		$this->db->from('vw_bank_data');
		//filter_pabrik_by session login
		if (is_string($user_role[0]->pabrik)) $filter_pabrik = explode(",", $user_role[0]->pabrik);
		$this->datatables->where_in('pabrik', $filter_pabrik);

		if ($id_data !== NULL) {
			$this->db->where('id_data', $id_data);
		}
		if ($active !== NULL) {
			$this->db->where('na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('del', $deleted);
		}
		if ($nama_bank !== NULL) {
			$this->db->where('nama_bank', $nama_bank);
		}
		if ($cabang_bank !== NULL) {
			$this->db->where('cabang_bank', $cabang_bank);
		}
		if ($nomor_rekening !== NULL) {
			$this->db->where('nomor_rekening', $nomor_rekening);
		}
		if ($pabrik_filter != NULL) {
			if (is_string($pabrik_filter)) $pabrik_filter = explode(",", $pabrik_filter);
			$this->datatables->where_in('pabrik', $pabrik_filter);
		}
		if ($status_sap !== NULL) {
			$this->db->where('status_sap', $status_sap);
		}
		if ($ck_pabrik !== NULL) {
			$this->db->where('pabrik', $ck_pabrik);
		}
		if ($ck_tujuan !== NULL) {
			$this->db->where('tujuan', $ck_tujuan);
		}
		if ($ck_tujuan_detail !== NULL) {
			$this->db->where('tujuan_detail', $ck_tujuan_detail);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_history($conn = NULL, $id_data_temp = NULL, $active = NULL, $deleted = 'n')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("CONVERT(VARCHAR(10), tbl_bank_data_temp_log.tanggal_buat, 104) as tanggal_format");
		$this->db->select("CONVERT(VARCHAR(8), tbl_bank_data_temp_log.tanggal_buat, 108) as jam_format");
		$this->db->select("tbl_bank_role.nama as role_approval");
		$this->db->select('tbl_karyawan.nama as nama_approval');
		$this->db->select('tbl_bank_data_temp.nomor as nomor_specimen');
		$this->db->select('tbl_bank_data_temp_log.*');
		$this->db->select("CASE
								WHEN tbl_bank_data_temp_log.catatan is null THEN '-'
								ELSE tbl_bank_data_temp_log.catatan
						   END as label_catatan");

		$this->db->from('tbl_bank_data_temp_log');
		$this->db->join('tbl_bank_data_temp', 'tbl_bank_data_temp.id_data_temp = tbl_bank_data_temp_log.id_data_temp', 'left outer');
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_bank_data_temp_log.login_buat', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		$this->db->join('tbl_bank_user_role', 'tbl_bank_user_role.user = tbl_karyawan.id_karyawan', 'left outer');
		$this->db->join('tbl_bank_role', 'tbl_bank_role.id_role = tbl_bank_user_role.id_role', 'left outer');
		if ($id_data_temp !== NULL) {
			$this->db->where('tbl_bank_data_temp_log.id_data_temp', $id_data_temp);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_bank_data_temp_log.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_bank_data_temp_log.del', $deleted);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_bank_temp_bom($conn = NULL, $id_data_temp = NULL, $active = NULL, $deleted = 'n', $jenis_pengajuan_filter = NULL, $pabrik_filter = NULL, $status_filter = NULL, $view_data = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$id_user	= base64_decode($this->session->userdata("-id_user-"));
		$nik		= base64_decode($this->session->userdata("-nik-"));
		$posst		= base64_decode($this->session->userdata("-posst-"));
		$user_role	= $this->get_data_user_role("open", $nik, $posst);
		$level 		= $user_role[0]->level;
		$this->datatables->select(" 
									$level as level,
									$id_user as id_user,
									id_data,
									id_data_temp,
									asal_pengajuan,
									jenis_pengajuan,
									pabrik,
									nomor,
									tanggal_format,
									status,
									no_coa,
									nomor_rekening,
									status_sap,
									label_status,
									label_status_detail,
									list_pendamping,
									login_buat,
									na,
									del");
		$this->datatables->from('vw_bank_data_temp');
		//filter_pabrik
		if (is_string($user_role[0]->pabrik)) $filter_pabrik = explode(",", $user_role[0]->pabrik);
		$this->datatables->where_in('pabrik', $filter_pabrik);

		if ($id_data_temp !== NULL) {
			$this->datatables->where('id_data_temp', $id_data_temp);
		}
		if ($active !== NULL) {
			$this->datatables->where('na', $active);
		}
		if ($deleted !== NULL) {
			$this->datatables->where('del', $deleted);
		}
		if ($jenis_pengajuan_filter != NULL) {
			if (is_string($jenis_pengajuan_filter)) $jenis_pengajuan_filter = explode(",", $jenis_pengajuan_filter);
			$this->datatables->where_in('jenis_pengajuan', $jenis_pengajuan_filter);
		}
		if ($pabrik_filter != NULL) {
			if (is_string($pabrik_filter)) $pabrik_filter = explode(",", $pabrik_filter);
			$this->datatables->where_in('pabrik', $pabrik_filter);
		}
		if ($status_filter != NULL) {
			if (is_string($status_filter)) $status_filter = explode(",", $status_filter);
			$this->datatables->where_in('caption_status', $status_filter);
		}
		if ($view_data !== NULL) {
			$this->datatables->where('status', $level);
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_data_temp"));
		return $this->general->jsonify($raw);
	}
	function get_data_bank_temp($conn = NULL, $id_data_temp = NULL, $active = NULL, $deleted = 'n', $nama_bank = NULL, $cabang_bank = NULL, $ck_pabrik = NULL, $ck_tujuan = NULL, $ck_tujuan_detail = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$id_user	= base64_decode($this->session->userdata("-id_user-"));
		$this->db->select("$id_user as id_user");
		$this->db->select("CONVERT(varchar, (select tbl_bank_data_temp_log.tanggal_buat from tbl_bank_data_temp_log where tbl_bank_data_temp_log.id_data_temp=vw_bank_data_temp.id_data_temp and tbl_bank_data_temp_log.status='99'), 104) as tanggal_approve_ceo");
		$this->db->select("(select 
							tbl_karyawan.nama
							from 
							tbl_bank_data_temp_log 
							left outer join tbl_user on tbl_user.id_user=tbl_bank_data_temp_log.login_buat
							left outer join tbl_karyawan on tbl_karyawan.id_karyawan=tbl_user.id_karyawan
							where 
							tbl_bank_data_temp_log.id_data_temp=vw_bank_data_temp.id_data_temp and tbl_bank_data_temp_log.status='99'
						) as approve_ceo");
		$this->db->select('vw_bank_data_temp.*');
		$this->db->from('vw_bank_data_temp');
		if ($id_data_temp !== NULL) {
			$this->db->where('id_data_temp', $id_data_temp);
		}
		if ($active !== NULL) {
			$this->db->where('na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('del', $deleted);
		}
		if ($nama_bank !== NULL) {
			$this->db->where('nama_bank', $nama_bank);
		}
		if ($cabang_bank !== NULL) {
			$this->db->where('cabang_bank', $cabang_bank);
		}
		if ($ck_pabrik !== NULL) {
			$this->db->where('pabrik', $ck_pabrik);
		}
		if ($ck_tujuan !== NULL) {
			$this->db->where('tujuan', $ck_tujuan);
		}
		if ($ck_tujuan_detail !== NULL) {
			$this->db->where('tujuan_detail', $ck_tujuan_detail);
		}
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	//======================================
	function get_data_vendor_temp($conn = NULL, $id_data = NULL, $jenis_pengajuan = NULL, $req = NULL, $id_data_temp = NULL, $na = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('*');
		$this->db->from('tbl_vendor_data_temp');
		// $this->db->where("na='n'");
		if ($id_data !== NULL) {
			$this->db->where('id_data', $id_data);
		}
		if ($jenis_pengajuan !== NULL) {
			$this->db->where('jenis_pengajuan', $jenis_pengajuan);
		}
		if ($req !== NULL) {
			$this->db->where('req', $req);
		}
		if ($id_data_temp !== NULL) {
			$this->db->where('id_data_temp', $id_data_temp);
		}
		if ($na !== NULL) {
			$this->db->where('na', $na);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_doc_temp($conn = NULL, $id_file = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('*');
		$this->db->from('tbl_vendor_data_dokumen_temp');
		$this->db->where("na='n'");
		if ($id_file !== NULL) {
			$this->db->where('id_file', $id_file);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_file($conn = NULL, $id_file = NULL, $id_folder = NULL, $nama = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('*');
		$this->db->from('tbl_file');
		$this->db->where("na='n'");
		if ($id_file !== NULL) {
			$this->db->where('id_file', $id_file);
		}
		if ($id_folder !== NULL) {
			$this->db->where('id_folder', $id_folder);
		}
		if ($nama !== NULL) {
			$this->db->where('nama', $nama);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}


	function get_data_file_temp($conn = NULL, $id_file = NULL, $id_folder = NULL, $nama = NULL, $id_data_temp = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_file_temp.*');
		$this->db->from('tbl_file_temp');
		$this->db->join('tbl_vendor_data_dokumen_temp', 'tbl_vendor_data_dokumen_temp.id_file = tbl_file_temp.id_file', 'left outer');
		$this->db->where("tbl_file_temp.na='n'");
		if ($id_file !== NULL) {
			$this->db->where('tbl_file_temp.id_file', $id_file);
		}
		if ($id_folder !== NULL) {
			$this->db->where('tbl_file_temp.id_folder', $id_folder);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_file_temp.nama', $nama);
		}
		if ($id_data_temp !== NULL) {
			$this->db->where("tbl_vendor_data_dokumen_temp.id_data_temp", $id_data_temp);
			$this->db->where("tbl_vendor_data_dokumen_temp.na='n'");
			$this->db->where("tbl_vendor_data_dokumen_temp.del='n'");
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}


	function get_data_spec_bom_test($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n', $id_item_group = NULL, $id_item_name = NULL, $status = NULL, $filter_request_status = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->datatables->select('tbl_karyawan.nama as id_item_spec');
		$this->datatables->select('tbl_karyawan.nama as id_item_group');
		$this->datatables->select('tbl_karyawan.nama as group_description');
		$this->datatables->select('tbl_karyawan.nama as name_description');
		$this->datatables->select('tbl_karyawan.nama as code');
		$this->datatables->select('tbl_karyawan.nama as description');
		$this->datatables->select('tbl_karyawan.nama as req');
		$this->datatables->from('tbl_karyawan');
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("nama"));
		return $this->general->jsonify($raw);
	}

	function get_data_spec_xx($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.id_karyawan as id_item_group');
		$this->db->select('tbl_karyawan.id_karyawan as id_item_spec');
		$this->db->select('tbl_karyawan.nama as description');
		$this->db->from('tbl_karyawan');
		$this->db->limit(1000);
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_spec($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n', $description = NULL, $req = NULL, $id_item_name = NULL, $code = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_spec.*');
		$this->db->select("(select right('0000'+convert(varchar(4), (count(*)+1)), 4) from tbl_item_spec as tbl_item_spec2 where tbl_item_spec2.id_item_group=tbl_item_spec.id_item_group and tbl_item_spec2.id_item_name=tbl_item_spec.id_item_name) as nomor");
		$this->db->select('tbl_item_spec.id_item_spec as id');
		$this->db->select('tbl_item_group.id_item_group');
		$this->db->select('tbl_item_group.mtart');
		$this->db->select('tbl_item_group.description as group_description');
		$this->db->select('tbl_item_name.classification');
		$this->db->select('tbl_item_name.id_item_name');
		$this->db->select('tbl_item_name.code as code_item_name');
		$this->db->select('tbl_item_name.description as name_description');
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR(MAX), ISNULL(tbl_item_gambar.file_location,0))+RTRIM(',')
					            FROM tbl_item_gambar
								WHERE tbl_item_gambar.id_item_spec = tbl_item_spec.id_item_spec
								and tbl_item_gambar.na='n'
								ORDER BY tbl_item_gambar.id_item_gambar
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_gambar,
						  ");
		$this->db->select('CASE
								WHEN tbl_item_spec.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_item_spec');
		$this->db->join('tbl_item_name', 'tbl_item_name.id_item_name = tbl_item_spec.id_item_name', 'left outer');
		$this->db->join('tbl_item_group', 'tbl_item_group.id_item_group = tbl_item_name.id_item_group', 'left outer');
		if ($id_item_spec !== NULL) {
			$this->db->where('tbl_item_spec.id_item_spec', $id_item_spec);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_spec.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_spec.del', $deleted);
		}
		if ($description !== NULL) {
			$this->db->where("(tbl_item_spec.code like '%$description%')or(tbl_item_spec.description like '%$description%')or(tbl_item_name.description like '%$description%')or(tbl_item_group.description like '%$description%')");
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_spec.del', $deleted);
		}
		if ($req !== NULL) {
			$this->db->where("tbl_item_spec.req", $req);
			$this->db->where("tbl_item_spec.purchase_type is not null");
			$this->db->where("tbl_item_spec.purchase_authorization is not null");
			// $this->db->where("tbl_item_spec.beli_di_nsi2 is not null");
			// $this->db->where("tbl_item_spec.specification_check is not null");
		}
		if ($id_item_name !== NULL) {
			$this->db->where('tbl_item_spec.id_item_name', $id_item_name);
		}
		if ($code !== NULL) {
			$this->db->where('tbl_item_spec.code', $code);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_plant($conn = NULL, $active = NULL, $deleted = 'n', $id_data = NULL, $plant = NULL, $status_sap = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_plant.plant');
		$this->db->select('ZDMMSPLANT.BUKRS');
		$this->db->from('tbl_vendor_plant');
		$this->db->join('DashBoardDev.dbo.ZDMMSPLANT as ZDMMSPLANT', 'ZDMMSPLANT.WERKS = tbl_vendor_plant.plant', 'left outer');

		if ($id_data !== NULL) {
			$this->db->where('tbl_vendor_plant.id_data', $id_data);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_plant.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_plant.del', $deleted);
		}
		if ($plant !== NULL) {
			$this->db->where('tbl_vendor_plant.plant', $plant);
		}
		if ($status_sap !== NULL) {
			$this->db->where('tbl_vendor_plant.status_sap', $status_sap);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_plant_new($conn = NULL, $active = NULL, $deleted = 'n', $id_data = NULL, $plant = NULL, $status_sap = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('ZDMMSPLANT.BUKRS');
		$this->db->select("(select top 1 ZDMMSPLANT2.WERKS from DashBoardDev.dbo.ZDMMSPLANT as ZDMMSPLANT2 where ZDMMSPLANT2.BUKRS=ZDMMSPLANT.BUKRS) as plant");
		$this->db->from('tbl_vendor_plant');
		$this->db->join('DashBoardDev.dbo.ZDMMSPLANT as ZDMMSPLANT', 'ZDMMSPLANT.WERKS = tbl_vendor_plant.plant', 'left outer');

		if ($id_data !== NULL) {
			$this->db->where('tbl_vendor_plant.id_data', $id_data);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_plant.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_plant.del', $deleted);
		}
		if ($plant !== NULL) {
			$this->db->where('tbl_vendor_plant.plant', $plant);
		}
		if ($status_sap !== NULL) {
			$this->db->where('tbl_vendor_plant.status_sap', $status_sap);
		}
		$this->db->group_by(array('ZDMMSPLANT.BUKRS'));

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_plant_temp($conn = NULL, $active = NULL, $deleted = 'n', $id_data = NULL, $plant = NULL, $status_delete = NULL, $status_sap = NULL, $jenis_pengajuan = NULL, $id_data_temp = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_plant_temp.plant');
		$this->db->from('tbl_vendor_plant_temp');
		if ($id_data !== NULL) {
			$this->db->where('tbl_vendor_plant_temp.id_data', $id_data);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_plant_temp.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_plant_temp.del', $deleted);
		}
		if ($plant !== NULL) {
			$this->db->where('tbl_vendor_plant_temp.plant', $plant);
		}
		if ($status_delete !== NULL) {
			$this->db->where('tbl_vendor_plant_temp.status_delete', $status_delete);
		}
		if ($status_sap !== NULL) {
			$this->db->where('tbl_vendor_plant_temp.status_sap', $status_sap);
		}
		if ($jenis_pengajuan !== NULL) {
			$this->db->where('tbl_vendor_plant_temp.jenis_pengajuan', $jenis_pengajuan);
		}
		if ($id_data_temp !== NULL) {
			$this->db->where('tbl_vendor_plant_temp.id_data_temp', $id_data_temp);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_history_($conn = NULL, $active = NULL, $deleted = 'n', $id_item_spec = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_spec_log.*');
		$this->db->select("CONVERT(VARCHAR(10), tbl_item_spec_log.tanggal_buat, 104) as tanggal_buat");
		$this->db->select("CONVERT(VARCHAR(8), tbl_item_spec_log.tanggal_buat, 108)) as jam_buat");
		$this->db->select('tbl_karyawan.nama as nama_user');
		$this->db->from('tbl_item_spec_log');
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_item_spec_log.login_buat', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		if ($id_item_spec !== NULL) {
			$this->db->where('tbl_item_spec_log.id_item_spec', $id_item_spec);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_request($conn = NULL, $id_item_request = NULL, $active = NULL, $deleted = 'n', $all = NULL, $req = NULL, $filter_from = NULL, $filter_to = NULL, $filter_request_status = NULL, $filter_status = NULL, $confirm = NULL, $pic = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_request.*');
		$this->db->select('tbl_item_spec.code as code_spec');
		$this->db->select('tbl_karyawan.gsber');
		$this->db->select('CONVERT(varchar(11),tbl_item_request.tanggal_buat,104) as tanggal');
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR(MAX), ISNULL(tbl_item_gambar.file_location,0))+RTRIM(',')
					            FROM tbl_item_gambar
								WHERE tbl_item_gambar.id_item_request = tbl_item_request.id_item_request
								and tbl_item_gambar.na='n'
								ORDER BY tbl_item_gambar.id_item_gambar
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_gambar,
						  ");
		$this->db->select('CASE
								WHEN tbl_item_request.req = \'o\' THEN \'<span class="label label-default">Pending Request</span>\'
								WHEN tbl_item_request.req = \'y\' THEN \'<span class="label label-warning">Requested</span>\'
								ELSE \'<span class="label label-success">Completed</span>\'
						   END as label_request');
		$this->db->select('CASE
								WHEN tbl_item_request.req = \'o\' THEN \'Pending Request\'
								WHEN tbl_item_request.req = \'y\' THEN \'Requested\'
								ELSE \'Completed\'
						   END as excel_request');
		$this->db->select('CASE
								WHEN tbl_item_request.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_status');
		$this->db->select('CASE
								WHEN tbl_item_request.na = \'n\' THEN \'AKTIF\'
								ELSE \'NON AKTIF\'
						   END as excel_status');
		// $this->db->select("select id_item_master_pic from tbl_item_setting_user where ");				   
		$this->db->from('tbl_item_request');
		$this->db->join('tbl_item_spec', 'tbl_item_spec.id_item_spec = tbl_item_request.id_item_spec', 'left outer');
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_item_request.login_buat', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		if ($id_item_request !== NULL) {
			$this->db->where('tbl_item_request.id_item_request', $id_item_request);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_request.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_request.del', $deleted);
		}
		if ($all == NULL) {
			$this->db->where('tbl_item_request.login_buat', base64_decode($this->session->userdata("-id_user-")));
		}
		if ($req !== NULL) {
			$this->db->where('tbl_item_request.req', $req);
		}
		if (($filter_from !== NULL) and ($filter_to !== NULL)) {
			$this->db->where("tbl_item_request.tanggal_buat between '" . $this->generate->regenerateDateFormat($filter_from) . "' and '" . $this->generate->regenerateDateFormat($filter_to) . "'");
		}
		if ($filter_request_status != NULL) {
			if (is_string($filter_request_status)) $filter_request_status = explode(",", $filter_request_status);
			$this->db->where_in('tbl_item_request.req', $filter_request_status);
		}
		if ($filter_status != NULL) {
			if (is_string($filter_status)) $filter_status = explode(",", $filter_status);
			$this->db->where_in('tbl_item_request.na', $filter_status);
		}
		if ($filter_status == NULL) {
			$this->db->where('tbl_item_request.na', 'n');
		}
		if ($confirm !== NULL) {
			$this->db->where("tbl_item_request.id_item_spec !='0'");
		}
		if ($pic !== NULL) {
			$this->db->where("tbl_item_request.id_item_master_pic", $pic);
		}
		// $this->db->where("1=1");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_inputxx($conn = NULL, $id_item_request = NULL, $active = NULL, $deleted = 'n')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_request.*');
		$this->db->select('CONVERT(varchar(11),tbl_item_request.tanggal_buat,104) as tanggal');
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR(MAX), ISNULL(tbl_item_gambar.file_location,0))+RTRIM(',')
					            FROM tbl_item_gambar
								WHERE tbl_item_gambar.id_item_request = tbl_item_request.id_item_request
								and tbl_item_gambar.na='n'
								ORDER BY tbl_item_gambar.id_item_gambar
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_gambar,
						  ");
		$this->db->select('CASE
								WHEN tbl_item_request.req = \'o\' THEN \'<span class="label label-default">Pending Request</span>\'
								WHEN tbl_item_request.req = \'y\' THEN \'<span class="label label-warning">Requested</span>\'
								ELSE \'<span class="label label-success">Completed</span>\'
						   END as label_request');
		$this->db->select('CASE
								WHEN tbl_item_request.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_status');
		$this->db->from('tbl_item_request');
		if ($id_item_request !== NULL) {
			$this->db->where('tbl_item_request.id_item_request', $id_item_request);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_request.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_request.del', $deleted);
		}
		$this->db->where('tbl_item_request.req', 'y');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_cek($conn = NULL, $tabel = NULL, $field = NULL, $value = NULL, $field2 = NULL, $value2 = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select($tabel . '.*');
		$this->db->from($tabel);
		if (($field !== NULL) and ($value !== NULL)) {
			$this->db->where($tabel . '.' . $field, $value);
		}
		if (($field2 !== NULL) and ($value2 !== NULL)) {
			$this->db->where($tabel . '.' . $field2, $value2);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_nilai_detail($conn = NULL, $id_data_nilai = NULL, $active = NULL, $deleted = 'n', $id_data = NULL, $id_kriteria = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_nilai.nilai');
		$this->db->select('(tbl_vendor_kriteria.bobot*tbl_vendor_nilai.nilai/100) as nilai_bobot');
		$this->db->select('(tbl_vendor_kriteria.bobot*100/100) as nilai_max');
		$this->db->select('tbl_vendor_data_nilai.*');
		$this->db->from('tbl_vendor_data_nilai');
		$this->db->join('tbl_vendor_nilai', 'tbl_vendor_nilai.id_nilai = tbl_vendor_data_nilai.id_nilai', 'left outer');
		$this->db->join('tbl_vendor_kriteria', 'tbl_vendor_kriteria.id_kriteria = tbl_vendor_data_nilai.id_kriteria', 'left outer');
		if ($id_data_nilai !== NULL) {
			$this->db->where('tbl_vendor_data_nilai.id_data_nilai', $id_data_nilai);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_data_nilai.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_data_nilai.del', $deleted);
		}
		if ($id_data !== NULL) {
			$this->db->where('tbl_vendor_data_nilai.id_data', $id_data);
		}
		if ($id_kriteria !== NULL) {
			$this->db->where('tbl_vendor_data_nilai.id_kriteria', $id_kriteria);
		}
		$this->db->order_by("tbl_vendor_data_nilai.id_data_nilai", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_master_plant_asis($conn = NULL, $id_data = NULL, $status_sap = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_plant.*');
		$this->db->from('tbl_vendor_plant');
		if ($id_data != NULL) {
			$this->db->where_in('tbl_vendor_plant.id_data', $id_data);
		}
		if ($status_sap != NULL) {
			$this->db->where('tbl_vendor_plant.status_sap', $status_sap);
		}


		$this->db->where('tbl_vendor_plant.na', 'n');
		$this->db->order_by('tbl_vendor_plant.plant', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();

		return $result;
	}
	function get_master_plant($plant_in = NULL, $as_array = false, $plant_not_in = NULL, $tipe = NULL)
	{
		$this->general->connectDbPortal();

		if ($tipe = 'ERP') {
			$this->db->select('CONVERT(INT, ZDMMSPLANT.PERSA) as id_pabrik,
                              ZDMMSPLANT.PERSA as plant_code,
                              ZDMMSPLANT.WERKS as plant,
                              ZDMMSPLANT.NAME1 as plant_name,
                              ZDMMSPLANT.NAME1 as nama');
			$this->db->from('SAPSYNC.dbo.ZDMMSPLANT');
			if ((base64_decode($this->session->userdata("-ho-")) == 'n')) {
				$this->db->where("ZDMMSPLANT.WERKS='" . base64_decode($this->session->userdata("-gsber-")) . "'");
			}
			if ($plant_in != NULL) {
				$this->db->where_in('ZDMMSPLANT.WERKS', $plant_in);
			}
			if ($plant_not_in != NULL) {
				$this->db->where_not_in('ZDMMSPLANT.WERKS', $plant_not_in);
			}
			$this->db->order_by('SAPSYNC.dbo.ZDMMSPLANT.WERKS ASC');
		} else {
			$this->db->select('tbl_wf_master_plant.id_plant as id_pabrik,
							   tbl_wf_master_plant.plant_code,
							   tbl_wf_master_plant.plant,
							   tbl_wf_master_plant.plant_name,
							   tbl_wf_master_plant.plant_name as nama,
							   tbl_wf_region.region_name');
			$this->db->from('tbl_wf_master_plant');
			$this->db->join('tbl_wf_region', 'tbl_wf_master_plant.plant_code = tbl_wf_region.plant_code
											  AND tbl_wf_region.na = \'n\' 
											  AND tbl_wf_region.del = \'n\'', 'left');
			if ((base64_decode($this->session->userdata("-ho-")) == 'n')) {
				$this->db->where("tbl_wf_master_plant.plant='" . base64_decode($this->session->userdata("-gsber-")) . "'");
			}
			if ($plant_in != NULL) {
				$this->db->where_in('tbl_wf_master_plant.plant', $plant_in);
			}
			if ($plant_not_in != NULL) {
				$this->db->where_not_in('tbl_wf_master_plant.plant_code', $plant_not_in);
			}
			$this->db->where('tbl_wf_master_plant.na', 'n');
			$this->db->where('tbl_wf_master_plant.del', 'n');
			$this->db->group_by(array(
				'tbl_wf_master_plant.id_plant',
				'tbl_wf_master_plant.plant_code',
				'tbl_wf_master_plant.plant',
				'tbl_wf_master_plant.plant_name',
				'tbl_wf_region.region_name'
			));
			$this->db->order_by('tbl_wf_master_plant.plant_name', 'ASC');
		}
		$query = $this->db->get();
		if ($as_array)
			$result = $query->result_array();
		else
			$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_master_plant_extend($id_data = NULL, $as_array = false)
	{
		$this->general->connectDbPortal();

		if ($tipe = 'ERP') {
			$this->db->select('CONVERT(INT, ZDMMSPLANT.PERSA) as id_pabrik,
                              ZDMMSPLANT.PERSA as plant_code,
                              ZDMMSPLANT.WERKS as plant,
                              ZDMMSPLANT.NAME1 as plant_name,
                              ZDMMSPLANT.NAME1 as nama');
			$this->db->from('SAPSYNC.dbo.ZDMMSPLANT');
			if ($id_data != NULL) {
				$this->db->where("ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS not in (select tbl_vendor_plant.plant from tbl_vendor_plant where tbl_vendor_plant.id_data='$id_data' and tbl_vendor_plant.status_sap='y')");
			}
			if ((base64_decode($this->session->userdata("-ho-")) == 'n')) {
				$this->db->where("ZDMMSPLANT.WERKS='" . base64_decode($this->session->userdata("-gsber-")) . "'");
			}
			$this->db->where("ZDMMSPLANT.WERKS NOT IN('KJP2','KMTR','NSI2','NSI3')");
			$this->db->order_by('SAPSYNC.dbo.ZDMMSPLANT.WERKS ASC');
		} else {
			$this->db->select('tbl_wf_master_plant.id_plant as id_pabrik,
							   tbl_wf_master_plant.plant_code,
							   tbl_wf_master_plant.plant,
							   tbl_wf_master_plant.plant_name,
							   tbl_wf_master_plant.plant_name as nama,
							   tbl_wf_region.region_name');
			$this->db->from('tbl_wf_master_plant');
			$this->db->join('tbl_wf_region', 'tbl_wf_master_plant.plant_code = tbl_wf_region.plant_code
											  AND tbl_wf_region.na = \'n\' 
											  AND tbl_wf_region.del = \'n\'', 'left');
			if ($id_data != NULL) {
				$this->db->where("tbl_wf_master_plant.plant not in (select tbl_vendor_plant.plant from tbl_vendor_plant where tbl_vendor_plant.id_data='$id_data' and tbl_vendor_plant.status_sap='y')");
			}
			$this->db->where('tbl_wf_master_plant.na', 'n');
			$this->db->where('tbl_wf_master_plant.del', 'n');
			if ((base64_decode($this->session->userdata("-ho-")) == 'n')) {
				$this->db->where("tbl_wf_master_plant.plant='" . base64_decode($this->session->userdata("-gsber-")) . "'");
			}
			$this->db->where("tbl_wf_master_plant.plant NOT IN('KJP2','KMTR','NSI2','NSI3')");
			$this->db->group_by(array(
				'tbl_wf_master_plant.id_plant',
				'tbl_wf_master_plant.plant_code',
				'tbl_wf_master_plant.plant',
				'tbl_wf_master_plant.plant_name',
				'tbl_wf_region.region_name'
			));
			$this->db->order_by('tbl_wf_master_plant.plant_name', 'ASC');
		}
		$query = $this->db->get();
		if ($as_array)
			$result = $query->result_array();
		else
			$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_historyxx($conn = NULL, $id_data = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("CONVERT(VARCHAR(10), tbl_vendor_data_log.tanggal_buat, 104) as tanggal_buat");
		$this->db->select("CONVERT(VARCHAR(5), tbl_vendor_data_log.tanggal_buat, 108) as jam_buat");
		$this->db->select("CASE
								WHEN tbl_vendor_data_log.id_status = 1  THEN 'Create Vendor'
								WHEN tbl_vendor_data_log.id_status = 99  THEN 'Completed Vendor'
								WHEN tbl_vendor_data_log.id_status = 100  THEN 'Reject Vendor'
								ELSE 'Approval '+ tbl_vendor_role.nama
						   END as nama_status");
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select('tbl_vendor_data.nama as nama_vendor');
		$this->db->select('tbl_karyawan.nik');
		$this->db->from('tbl_vendor_data_log');
		$this->db->join('tbl_vendor_data', 'tbl_vendor_data_log.id_data = tbl_vendor_data.id_data', 'left outer');
		// $this->db->join('tbl_vendor_status', 'tbl_vendor_data_log.id_status = tbl_vendor_status.id_status', 'left outer');
		$this->db->join('tbl_vendor_role', 'tbl_vendor_data_log.id_status = tbl_vendor_role.level', 'left outer');
		$this->db->join('tbl_user', 'tbl_vendor_data_log.login_buat = tbl_user.id_user', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan', 'left outer');
		if ($id_data !== NULL) {
			$this->db->where('tbl_vendor_data_log.id_data', $id_data);
		}
		$this->db->order_by("tbl_vendor_data_log.id_data_log", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_history_pengajuan($conn = NULL, $id_data = NULL, $jenis_pengajuan = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("CONVERT(VARCHAR(10), tbl_vendor_data_temp_log.tanggal_buat, 104) as tanggal_buat");
		$this->db->select("CONVERT(VARCHAR(5), tbl_vendor_data_temp_log.tanggal_buat, 108) as jam_buat");
		if ($jenis_pengajuan == 'extend') {
			$this->db->select("CASE
								WHEN tbl_vendor_data_temp_log.id_status = 1 THEN 'Request Extend'
								WHEN tbl_vendor_data_temp_log.id_status = 4  and tbl_vendor_data_temp.pengajuan_ho='y' THEN 'Request Extend HO'
								WHEN tbl_vendor_data_temp_log.id_status = 4  and tbl_vendor_data_temp.pengajuan_ho='n' THEN 'Approve Extend'
								WHEN tbl_vendor_data_temp_log.id_status = 100 THEN 'Reject Extend'
								WHEN tbl_vendor_data_temp_log.id_status = 99 THEN 'Completed Extend'
								ELSE tbl_vendor_status.nama
								END as nama_status");
			$this->db->select("
								CAST(
								 (SELECT CONVERT(VARCHAR(MAX), tbl_vendor_plant_temp.plant)+RTRIM(',')
									FROM tbl_vendor_plant_temp
									WHERE 
									tbl_vendor_plant_temp.id_data_temp=tbl_vendor_data_temp.id_data_temp
									and tbl_vendor_plant_temp.na='n'
								  FOR XML PATH ('')) as VARCHAR(MAX)
								)  AS list_plant_extend
							");
		}
		if ($jenis_pengajuan == 'delete') {
			$this->db->select("CASE
								WHEN tbl_vendor_data_temp_log.id_status = 1 THEN 'Request Delete'
								WHEN tbl_vendor_data_temp_log.id_status = 4 THEN 'Request Delete HO'
								WHEN tbl_vendor_data_temp_log.id_status = 100 THEN 'Reject Delete'
								WHEN tbl_vendor_data_temp_log.id_status = 99 THEN 'Completed Delete'
								ELSE tbl_vendor_status.nama
								END as nama_status");
			$this->db->select("
								CAST(
								 (SELECT CONVERT(VARCHAR(MAX), tbl_vendor_plant_temp.plant)+RTRIM(',')
									FROM tbl_vendor_plant_temp
									WHERE 
									tbl_vendor_plant_temp.id_data_temp=tbl_vendor_data_temp.id_data_temp
									and tbl_vendor_plant_temp.na='n'
								  FOR XML PATH ('')) as VARCHAR(MAX)
								)  AS list_plant_delete
							");
		}
		if ($jenis_pengajuan == 'undelete') {
			$this->db->select("CASE
								WHEN tbl_vendor_data_temp_log.id_status = 1 THEN 'Request Undelete'
								WHEN tbl_vendor_data_temp_log.id_status = 4 THEN 'Request Undelete HO'
								WHEN tbl_vendor_data_temp_log.id_status = 100 THEN 'Reject Undelete'
								WHEN tbl_vendor_data_temp_log.id_status = 99 THEN 'Completed Undelete'
								ELSE 'Approve Undelete'
								END as nama_status");
			$this->db->select("
								CAST(
								 (SELECT CONVERT(VARCHAR(MAX), tbl_vendor_plant_temp.plant)+RTRIM(',')
									FROM tbl_vendor_plant_temp
									WHERE 
									tbl_vendor_plant_temp.id_data_temp=tbl_vendor_data_temp.id_data_temp
									and tbl_vendor_plant_temp.na='n'
								  FOR XML PATH ('')) as VARCHAR(MAX)
								)  AS list_plant_undelete
							");
		}
		if ($jenis_pengajuan == 'change') {
			$this->db->select("CASE
								WHEN tbl_vendor_data_temp_log.id_status = 1 THEN 'Request Change'
								WHEN tbl_vendor_data_temp_log.id_status = 4 THEN 'Request Change HO'
								WHEN tbl_vendor_data_temp_log.id_status = 100 THEN 'Reject Change'
								WHEN tbl_vendor_data_temp_log.id_status = 99 THEN 'Completed Change'
								ELSE tbl_vendor_status.nama
								END as nama_status");
			$this->db->select("CASE
									WHEN tbl_vendor_data.nama != tbl_vendor_data_temp.nama 
									THEN '<b>Nama :</b> <br>'+ tbl_vendor_data.nama +' -> '+tbl_vendor_data_temp.nama
									ELSE ''
							   END as change_nama");
			$this->db->select("CASE
									WHEN tbl_vendor_data.ktp != tbl_vendor_data_temp.ktp 
									THEN '<b>KTP :</b> <br>'+ tbl_vendor_data.ktp +' -> '+tbl_vendor_data_temp.ktp
									ELSE ''
							   END as change_ktp");
			$this->db->select("CASE
									WHEN tbl_vendor_data.npwp != tbl_vendor_data_temp.npwp 
									THEN '<b>NPWP :</b> <br>'+ tbl_vendor_data.npwp +' -> '+tbl_vendor_data_temp.npwp
									ELSE ''
							   END as change_npwp");
			$this->db->select("CASE
									WHEN tbl_vendor_data.tax_type != tbl_vendor_data_temp.tax_type 
									THEN '<b>WH Tax Type :</b> <br>'+ tbl_vendor_data.tax_type +' -> '+tbl_vendor_data_temp.tax_type
									ELSE ''
							   END as change_tax_type");
			$this->db->select("CASE
									WHEN tbl_vendor_data.tax_code != tbl_vendor_data_temp.tax_code
									THEN '<b>WH Tax Code :</b> <br>'+ tbl_vendor_data.tax_code +' -> '+tbl_vendor_data_temp.tax_code
									ELSE ''
							   END as change_tax_code");
		}

		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select('tbl_karyawan.nik');
		$this->db->from('tbl_vendor_data_temp_log');
		$this->db->join('tbl_vendor_data_temp', 'tbl_vendor_data_temp.id_data_temp = tbl_vendor_data_temp_log.id_data_temp', 'left outer');
		$this->db->join('tbl_vendor_data', 'tbl_vendor_data.id_data = tbl_vendor_data_temp.id_data', 'left outer');
		$this->db->join('tbl_vendor_status', 'tbl_vendor_data_temp_log.id_status = tbl_vendor_status.id_status', 'left outer');
		$this->db->join('tbl_user', 'tbl_vendor_data_temp_log.login_buat = tbl_user.id_user', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan', 'left outer');
		if ($id_data !== NULL) {
			$this->db->where('tbl_vendor_data_temp.id_data', $id_data);
		}
		if ($jenis_pengajuan !== NULL) {
			$this->db->where('tbl_vendor_data_temp.jenis_pengajuan', $jenis_pengajuan);
		}
		$this->db->order_by("tbl_vendor_data_temp_log.id_data_temp_log", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
}
