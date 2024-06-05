<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

class Devaluasidepo extends CI_Model
{

	function get_email_recipient($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $query = $this->db->query("EXEC SP_Kiranaku_Evaluasi_Depo_Recipient_Email '" . $param['nomor'] . "'");
        $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

	function get_data_evaluasi($conn = NULL, $nomor = NULL, $na = NULL, $del = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_data.nomor as nomor_depo');
		$this->db->select('tbl_depo_data.jenis_depo');
		$this->db->select('tbl_depo_data.pabrik');
		$this->db->select('tbl_depo_data.nama');
		$this->db->select('tbl_depo_data.alamat_rumah');
		$this->db->select('tbl_depo_data.alamat_depo');
		$this->db->select('tbl_depo_data.kabupaten');
		$this->db->select('tbl_depo_data.propinsi');
		$this->db->select('tbl_depo_evaluasi.nomor as id_data');
		$this->db->select("(select top 1 tbl_depo_user_role.id_role from tbl_depo_user_role where [user]='".base64_decode($this->session->userdata("-nik-"))."' and na='n') as level");
		$this->db->select('tbl_depo_evaluasi.*');
		$this->db->select('tb_provinsi.nama_provinsi as nama_propinsi');
		$this->db->select('tb_kabupaten.nama_kab as nama_kabupaten');
		$this->db->from('tbl_depo_evaluasi');
		$this->db->join('tbl_depo_data', 'tbl_depo_data.id_depo_master = tbl_depo_evaluasi.id_depo_master', 'left outer');
		$this->db->join('sdo_dev.dbo.tb_provinsi', 'tb_provinsi.id=tbl_depo_data.propinsi', 'left outer');
		$this->db->join('sdo_dev.dbo.tb_kabupaten', 'tb_kabupaten.id=tbl_depo_data.kabupaten', 'left outer');
		
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_evaluasi.nomor', $nomor);
		}
		if ($na !== NULL) {
			$this->db->where('tbl_depo_evaluasi.na', $na);
		}
		if ($del !== NULL) {
			$this->db->where('tbl_depo_evaluasi.del', $del);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	function get_data_user_role($conn = NULL, $nik = NULL, $posst = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select("tbl_depo_role.*");
		$this->db->select("tbl_depo_user_role.user");
		$this->db->select("tbl_depo_user_role.pabrik");
		$this->db->from('tbl_depo_user_role');
		$this->db->join('tbl_depo_role', 'tbl_depo_role.id_role = tbl_depo_user_role.id_role', 'left outer');
		if ($nik !== NULL) {
			$this->db->where('tbl_depo_user_role.user', $nik);
		}
		$query1 = $this->db->get_compiled_select();

		$this->db->select("tbl_depo_role.*");
		$this->db->select("tbl_depo_user_role.user");
		$this->db->select("tbl_depo_user_role.pabrik");
		$this->db->from('tbl_depo_user_role');
		$this->db->join('tbl_depo_role', 'tbl_depo_role.id_role = tbl_depo_user_role.id_role', 'left outer');
		if ($posst !== NULL) {
			$this->db->where('tbl_depo_user_role.user', $posst);
		}
		$query2 = $this->db->get_compiled_select();

		$query = $this->db->query($query1 . ' UNION ' . $query2);
		// $query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_history($conn = NULL, $nomor = NULL, $active = NULL, $deleted = 'n')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("CONVERT(VARCHAR(10), tbl_depo_evaluasi_log.tanggal_buat, 104) as tanggal_format");
		$this->db->select("CONVERT(VARCHAR(8), tbl_depo_evaluasi_log.tanggal_buat, 108) as jam_format");
		$this->db->select("tbl_depo_role.nama as role_approval");
		$this->db->select('tbl_karyawan.nama as nama_approval');
		$this->db->select('tbl_depo_evaluasi.nomor as nomor_specimen');
		$this->db->select('tbl_depo_evaluasi_log.*');
		$this->db->select("CASE
								WHEN tbl_depo_evaluasi_log.catatan is null THEN '-'
								ELSE tbl_depo_evaluasi_log.catatan
						   END as label_catatan");

		$this->db->from('tbl_depo_evaluasi_log');
		$this->db->join('tbl_depo_evaluasi', 'tbl_depo_evaluasi.nomor = tbl_depo_evaluasi_log.nomor', 'left outer');
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_depo_evaluasi_log.login_buat', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		$this->db->join('tbl_depo_user_role', 'tbl_depo_user_role.user = tbl_karyawan.id_karyawan', 'left outer');
		$this->db->join('tbl_depo_role', 'tbl_depo_role.id_role = tbl_depo_user_role.id_role', 'left outer');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_evaluasi_log.nomor', $nomor);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_evaluasi_log.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_evaluasi_log.del', $deleted);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_evaluasi_depo_bom($conn = NULL, $nomor = NULL, $active = NULL, $deleted = 'n', $jenis_depo_filter = NULL, $pabrik_filter = NULL, $status_filter = NULL, $view_data = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$nik		= base64_decode($this->session->userdata("-nik-"));
		$posst		= base64_decode($this->session->userdata("-posst-"));
		$user_role	= $this->get_data_user_role("open", $nik, $posst);
		$level 		= $user_role[0]->level;
		$this->datatables->select(" 
									nama_role,
									nomor as id_data,
									jenis_depo,
									jenis_depo_format,
									nama_depo as nama,
									pabrik,
									nomor,
									nomor_format,
									tanggal_format,
									status,
									main_status,
									label_status,
									label_status_detail,
									login_buat,
									na,
									del");
		$this->datatables->from('vw_depo_evaluasi');
		//filter_pabrik_by session login
		if (is_string($user_role[0]->pabrik)) $filter_pabrik = explode(",", $user_role[0]->pabrik);
		$this->datatables->where_in('pabrik', $filter_pabrik);

		if ($nomor !== NULL) {
			$this->datatables->where('nomor', $nomor);
		}

		if ($jenis_depo_filter != NULL) {
			if (is_string($jenis_depo_filter)) $jenis_depo_filter = explode(",", $jenis_depo_filter);
			$this->datatables->where_in('jenis_depo', $jenis_depo_filter);
		}
		
		if ($pabrik_filter != NULL) {
			if (is_string($pabrik_filter)) $pabrik_filter = explode(",", $pabrik_filter);
			$this->datatables->where_in('pabrik', $pabrik_filter);
		}
		
		if ($status_filter != NULL) {
			if (is_string($status_filter)) $status_filter = explode(",", $status_filter);
			$this->datatables->where_in('main_status', $status_filter);
		}

		if ($view_data !== NULL) {
			$this->datatables->where('status', $level);
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_data"));
		return $this->general->jsonify($raw);
	}
	
	function generate_data_evaluasi_biaya($conn = NULL, $id_depo_master = NULL, $jenis_depo = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		
		$this->db->select('tbl_depo_evaluasi_biaya.*');
		$this->db->select("
							CASE 
							--fee
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 1 
							THEN ISNULL((select SUM(tbl_depo_data_biaya_depo.biaya) from tbl_depo_data_biaya_depo where tbl_depo_data_biaya_depo.id_biaya in(1) and tbl_depo_data_biaya_depo.na='n' and tbl_depo_data_biaya_depo.nomor='$nomor' ),0) 
							--operasional
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 2 
							THEN ISNULL((select SUM(tbl_depo_data_biaya_depo.biaya) from tbl_depo_data_biaya_depo where tbl_depo_data_biaya_depo.id_biaya in(3) and tbl_depo_data_biaya_depo.na='n' and tbl_depo_data_biaya_depo.nomor='$nomor'),0) 
							--opex
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 3 and ((select top 1 tbl_depo_data.jenis_depo from  tbl_depo_data where tbl_depo_data.id_depo_master='83' and tbl_depo_data.na='n')='tetap')
							THEN ISNULL((select SUM(tbl_depo_data_biaya_depo.biaya) from tbl_depo_data_biaya_depo where tbl_depo_data_biaya_depo.id_biaya in(select tbl_depo_biaya.id_biaya from tbl_depo_biaya where tbl_depo_biaya.jenis_depo='tetap' and tbl_depo_biaya.jenis_biaya_detail='transaksi' and tbl_depo_biaya.id_biaya not in(1,3)) and tbl_depo_data_biaya_depo.na='n' and tbl_depo_data_biaya_depo.nomor='$nomor'),0) 
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 3 and ((select top 1 tbl_depo_data.jenis_depo from  tbl_depo_data where tbl_depo_data.id_depo_master='83' and tbl_depo_data.na='n')='mitra')
							THEN ISNULL((select SUM(tbl_depo_data_biaya_depo.biaya) from tbl_depo_data_biaya_depo where tbl_depo_data_biaya_depo.id_biaya in(select tbl_depo_biaya.id_biaya from tbl_depo_biaya where tbl_depo_biaya.jenis_depo='mitra' and tbl_depo_biaya.jenis_biaya_detail='transaksi' and tbl_depo_biaya.id_biaya not in(1,3)) and tbl_depo_data_biaya_depo.na='n' and tbl_depo_data_biaya_depo.nomor='$nomor'),0) 
							--biaya angkut
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 4
							THEN ISNULL((select SUM(tbl_depo_data_biaya_trans.biaya_per_kg) from tbl_depo_data_biaya_trans where tbl_depo_data_biaya_trans.na='n' and tbl_depo_data_biaya_trans.nomor='$nomor'),0) 
							--gapok
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 5
							THEN ISNULL((select SUM(tbl_depo_data_biaya_sdm.gaji_pokok) from tbl_depo_data_biaya_sdm where tbl_depo_data_biaya_sdm.na='n' and tbl_depo_data_biaya_sdm.nomor='$nomor'),0) 
							--tunjangan
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 6
							THEN ISNULL((select SUM(tbl_depo_data_biaya_sdm.tunjangan) from tbl_depo_data_biaya_sdm where tbl_depo_data_biaya_sdm.na='n' and tbl_depo_data_biaya_sdm.nomor='$nomor'),0) 
							ELSE 0 
							END as biaya_kgb_pembukaan
						   ");		
		$this->db->select("
							CASE 
							--fee
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 1 
							THEN ISNULL((select top 1 tbl_depo_evaluasi_biaya_detail.biaya_kgb_evaluasi from tbl_depo_evaluasi_biaya_detail where tbl_depo_evaluasi_biaya_detail.id_depo_master='83' and tbl_depo_evaluasi_biaya_detail.id_evaluasi_biaya=1 and tbl_depo_evaluasi_biaya_detail.na='n'),0) 
							--operasional
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 2
							THEN ISNULL((select top 1 tbl_depo_evaluasi_biaya_detail.biaya_kgb_evaluasi from tbl_depo_evaluasi_biaya_detail where tbl_depo_evaluasi_biaya_detail.id_depo_master='83' and tbl_depo_evaluasi_biaya_detail.id_evaluasi_biaya=2 and tbl_depo_evaluasi_biaya_detail.na='n'),0) 
							--opex
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 3
							THEN ISNULL((select top 1 tbl_depo_evaluasi_biaya_detail.biaya_kgb_evaluasi from tbl_depo_evaluasi_biaya_detail where tbl_depo_evaluasi_biaya_detail.id_depo_master='83' and tbl_depo_evaluasi_biaya_detail.id_evaluasi_biaya=3 and tbl_depo_evaluasi_biaya_detail.na='n'),0) 
							--biaya angkut
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 4
							THEN ISNULL((select top 1 tbl_depo_evaluasi_biaya_detail.biaya_kgb_evaluasi from tbl_depo_evaluasi_biaya_detail where tbl_depo_evaluasi_biaya_detail.id_depo_master='83' and tbl_depo_evaluasi_biaya_detail.id_evaluasi_biaya=4 and tbl_depo_evaluasi_biaya_detail.na='n'),0) 
							--gapok
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 5
							THEN ISNULL((select top 1 tbl_depo_evaluasi_biaya_detail.biaya_kgb_evaluasi from tbl_depo_evaluasi_biaya_detail where tbl_depo_evaluasi_biaya_detail.id_depo_master='83' and tbl_depo_evaluasi_biaya_detail.id_evaluasi_biaya=5 and tbl_depo_evaluasi_biaya_detail.na='n'),0) 
							--tunjangan
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 6
							THEN ISNULL((select top 1 tbl_depo_evaluasi_biaya_detail.biaya_kgb_evaluasi from tbl_depo_evaluasi_biaya_detail where tbl_depo_evaluasi_biaya_detail.id_depo_master='83' and tbl_depo_evaluasi_biaya_detail.id_evaluasi_biaya=6 and tbl_depo_evaluasi_biaya_detail.na='n'),0) 
							ELSE 0 
							END as biaya_kgb_evaluasi						   
						");		
		$this->db->from('tbl_depo_evaluasi_biaya');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	
	function generate_data_evaluasi($conn = NULL, $id_depo_master = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("DateDiff (Month,(select top 1 ZDMPURBKR01.BEDAT from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_depo_data.pabrik and ZDMPURBKR01.NMDPO COLLATE SQL_Latin1_General_CP1_CI_AS = UPPER(tbl_depo_data.nama) order by ZDMPURBKR01.BEDAT ASC),(select top 1 ZDMPURBKR01.BEDAT from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_depo_data.pabrik and ZDMPURBKR01.NMDPO COLLATE SQL_Latin1_General_CP1_CI_AS =UPPER(tbl_depo_data.nama) order by ZDMPURBKR01.BEDAT DESC))as jumlah_bulan");
		$this->db->select("(select top 1 ZDMPURBKR01.BEDAT from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_depo_data.pabrik and ZDMPURBKR01.NMDPO COLLATE SQL_Latin1_General_CP1_CI_AS = UPPER(tbl_depo_data.nama) order by ZDMPURBKR01.BEDAT DESC) as tanggal_akhir_transaksi");
		$this->db->select("(select count(*) from tbl_depo_evaluasi where tbl_depo_evaluasi.id_depo_master=tbl_depo_data.id_depo_master and tbl_depo_evaluasi.status<999 and tbl_depo_evaluasi.na='n') as evaluasi_pending");
		$this->db->select('tbl_depo_data.*');
		$this->db->from('tbl_depo_data');
		if ($id_depo_master !== NULL) {
			$this->db->where('id_depo_master', $id_depo_master);
		}
		$this->db->where('status', 999);

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	function get_data_aktual($conn = NULL, $pabrik = NULL, $nama = NULL, $tahun_ke = NULL, $bulan_ke = NULL, $jumlah_bulan = NULL, $ck_bulan)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$tahun_lalu	= $tahun_ke-1;
		$periode 	= $tahun_ke.'-'.$bulan_ke;
		$this->db->select("tbl_depo_data.pabrik");
		$this->db->select("tbl_depo_data.nama");
		if($jumlah_bulan <= 12){
			$bulan_ke = ($bulan_ke>=10)?$bulan_ke:substr($bulan_ke,1,1);
			$target = 'tbl_depo_data_target.m'.$bulan_ke;
			$this->db->select("$target as target");
		}else{
			$target = 'ZKISSTT_0389.PJM'.$bulan_ke;
			$this->db->select("ISNULL((select top 1 $target from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.ZZSUBA='$nama'),0) as target");
		}
		$this->db->select("ISNULL((select sum(ZDMPURBKR01.QTBSH) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='$nama' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke'),0) as berat_basah");
		$this->db->select("ISNULL((select sum(ZDMPURBKR01.QTFAK) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='$nama' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke'),0) as berat_kering");
		$this->db->select("ISNULL((select ( SUM(AVCS4) / (SUM(QTCNT) + SUM(QTFAK)) ) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke'),0) as harga_notarin");
		$this->db->select("ISNULL((select  SUM(AVCS4) / (SUM(QTCNT) + SUM(QTFAK))  from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='$nama' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke'),0) as harga_beli_depo");

		//tbl_depo_sp_sicom_aktual
		$this->db->select("ISNULL((select top 1 mixed_sicom from [10.0.0.32].portal.dbo.tbl_depo_sp_sicom_aktual where tahun='$tahun_ke' and RIGHT('00'+CAST(bulan AS VARCHAR(2)),2)='$bulan_ke' and pabrik='$pabrik'),0) as mixed_sicom");
		$this->db->select("ISNULL((select top 1 biaya_produksi from [10.0.0.32].portal.dbo.tbl_depo_sp_sicom_aktual where tahun='$tahun_ke' and RIGHT('00'+CAST(bulan AS VARCHAR(2)),2)='$bulan_ke' and pabrik='$pabrik'),0) as biaya_produksi");
		//tbl_depo_sp_harga_beli_pabrik_aktual
		$this->db->select("ISNULL((select top 1 harga_beli from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_pabrik_aktual where periode='$periode' and pabrik='$pabrik'),0) as harga_beli_batch_pabrik");
		$this->db->select("ISNULL((select top 1 persen_susut from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_pabrik_aktual where periode='$periode' and pabrik='$pabrik'),0) as persen_susut_batch_pabrik");
		//tbl_depo_sp_harga_beli_batch_depo_aktual
		$this->db->select("ISNULL((select top 1 harga_beli from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_depo_aktual where periode='$periode' and pabrik='$pabrik'),0) as harga_beli_batch_depo");
		$this->db->select("ISNULL((select top 1 persen_susut from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_depo_aktual where periode='$periode' and pabrik='$pabrik'),0) as persen_susut_batch_depo");
		$this->db->from('tbl_depo_data');
		$this->db->join('tbl_depo_data_target', "tbl_depo_data_target.nomor=tbl_depo_data.nomor and tbl_depo_data_target.na='n'", 'left outer');
		if ($pabrik !== NULL) {
			$this->db->where('pabrik', $pabrik);
		}
		if ($nama !== NULL) {
			$this->db->where('nama', $nama);
		}
		$this->db->where('status', 999);
		$this->db->limit(1);
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	function get_data_biaya_aktual($conn = NULL, $pabrik = NULL, $tanggal_awal = NULL, $tanggal_hitung = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		// $this->db->select("(select harga_beli from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_depo_aktual where periode='$periode' and pabrik='$pabrik') as harga_beli_batch_depo");
		// $this->db->select("(select persen_susut from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_depo_aktual where periode='$periode' and pabrik='$pabrik') as persen_susut_batch_depo");
		$this->db->select("*");
		$this->db->select("(select top 1 tbl_depo_sp_biaya_aktual2.total_biaya from tbl_depo_sp_biaya_aktual tbl_depo_sp_biaya_aktual2 where tbl_depo_sp_biaya_aktual2.pabrik=tbl_depo_sp_biaya_aktual.pabrik and tbl_depo_sp_biaya_aktual2.tanggal=CONVERT(VARCHAR(25),DATEADD(dd,-(DAY(DATEADD(mm,1,tbl_depo_sp_biaya_aktual.tanggal))-1),DATEADD(mm,1,tbl_depo_sp_biaya_aktual.tanggal)),101) AND tbl_depo_sp_biaya_aktual2.jumlah_pembelian is not null) as total_biaya_next");
		$this->db->select("(select top 1 tbl_depo_sp_biaya_aktual2.biaya_depo from tbl_depo_sp_biaya_aktual tbl_depo_sp_biaya_aktual2 where tbl_depo_sp_biaya_aktual2.pabrik=tbl_depo_sp_biaya_aktual.pabrik and tbl_depo_sp_biaya_aktual2.tanggal=CONVERT(VARCHAR(25),DATEADD(dd,-(DAY(DATEADD(mm,1,tbl_depo_sp_biaya_aktual.tanggal))-1),DATEADD(mm,1,tbl_depo_sp_biaya_aktual.tanggal)),101) AND tbl_depo_sp_biaya_aktual2.jumlah_pembelian is not null) as biaya_depo_next");
		$this->db->from("tbl_depo_sp_biaya_aktual");
		if ($pabrik !== NULL) {
			$this->db->where('pabrik', $pabrik);
		}
		if ($tanggal_awal !== NULL) {
			$this->db->where("tanggal between '$tanggal_awal' and '$tanggal_hitung'");
		}
		$this->db->where("jumlah_pembelian is not null");
		
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	function get_data_aktual_xx($conn = NULL, $pabrik = NULL, $nama_depo = NULL, $tahun_ke = NULL, $bulan_ke = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

        $string	= " 
					select 
					top 6
					ZDMPURBKR01.EKORG pabrik, ZDMPURBKR01.NMDPO nama_depo, ZDMPURBKR01.GJAHR tahun, ZDMPURBKR01.MONAT bulan
					,(select sum(ZDMPURBKR01_A.QTFAK) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01_A where ZDMPURBKR01_A.EKORG=ZDMPURBKR01.EKORG AND ZDMPURBKR01_A.NMDPO=ZDMPURBKR01.NMDPO AND ZDMPURBKR01_A.GJAHR=ZDMPURBKR01.GJAHR AND ZDMPURBKR01_A.MONAT=ZDMPURBKR01.MONAT) as aktual_basah
					,(select sum(ZDMPURBKR01_A.QTBSH) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01_A where ZDMPURBKR01_A.EKORG=ZDMPURBKR01.EKORG AND ZDMPURBKR01_A.NMDPO=ZDMPURBKR01.NMDPO AND ZDMPURBKR01_A.GJAHR=ZDMPURBKR01.GJAHR AND ZDMPURBKR01_A.MONAT=ZDMPURBKR01.MONAT) as aktual_kering
					from 
					[10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 
					where 
					ZDMPURBKR01.NMDPO!='' 
					and ZDMPURBKR01.NMDPO='DM BLAMBANGAN (MINA)' 
					and ZDMPURBKR01.EKORG='KJP2' 
					group by 
					ZDMPURBKR01.EKORG, ZDMPURBKR01.NMDPO, ZDMPURBKR01.GJAHR, ZDMPURBKR01.MONAT
					order by
					ZDMPURBKR01.GJAHR DESC, ZDMPURBKR01.MONAT DESC
					";
		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");			
		$query 	= $this->db->query($string);
        $result = $query->result();

        $this->general->closeDb();
        return $result;
	}	
	function get_nomor($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $query = $this->db->query("EXEC SP_DEPO_Generate_Number 'evaluasi', '" . $param['jenis_depo'] . "', '" . $param['pabrik'] . "', '" . $param['year'] . "', '" . $param['month'] . "'");
        $result = $query->row();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();
        return $result;
    }
	function get_data_evaluasi_detail($conn = NULL, $nomor = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_evaluasi_detail.*');
		$this->db->from('tbl_depo_evaluasi_detail');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_evaluasi_detail.nomor', $nomor);
		}
		$this->db->where('tbl_depo_evaluasi_detail.na', 'n');

		$this->db->order_by('tbl_depo_evaluasi_detail.tahun ASC, tbl_depo_evaluasi_detail.bulan ASC');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_evaluasi_biaya($conn = NULL, $id_depo_master = NULL, $nomor = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_evaluasi_biaya_detail.*');
		$this->db->from('tbl_depo_evaluasi_biaya_detail');
		if ($id_depo_master !== NULL) {
			$this->db->where('tbl_depo_evaluasi_biaya_detail.id_depo_master', $id_depo_master);
		}
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_evaluasi_biaya_detail.nomor', $nomor);
		}
		$this->db->where('tbl_depo_evaluasi_biaya_detail.na', 'n');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	
	
}
