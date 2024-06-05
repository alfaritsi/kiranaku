<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : MENTORING
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dtransaksimentor extends CI_Model
{
    function get_data_range($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();
		
		$id_status 		= (isset($param['id_status']))? $param['id_status'] : 1;
		$tanggal 		= (isset($param['tanggal']))? date_create($param['tanggal'])->format('Y-m-d') : date('Y-m-d');
		$tanggal_buat	= (isset($param['tanggal_buat']))? date_create($param['tanggal_buat'])->format('Y-m-d') : date('Y-m-d');
        $query = $this->db->query("EXEC SP_Mentor_Range_Date ".$id_status.", '".$tanggal."', '".$tanggal_buat."'");
        $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

	function get_data_feedback($conn = NULL, $nomor_mentoring = NULL, $nik_mentor = NULL, $nik_mentor_dmc1 = NULL, $nik_mentor_dmc2 = NULL, $nik_mentor_dmc3 = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_mentor_feedback.id_feedback as id');
		if($nik_mentor_dmc1!=null){
			$this->db->select("(select top 1 jawaban from tbl_mentor_data_feedback where tbl_mentor_data_feedback.id_feedback=tbl_mentor_feedback.id_feedback and tbl_mentor_data_feedback.nomor='$nomor_mentoring' and tbl_mentor_data_feedback.dmc='1' and tbl_mentor_data_feedback.nik_mentor='$nik_mentor_dmc1' and tbl_mentor_data_feedback.na='n') as jawaban_dmc1");
		}else{
			$this->db->select("(select top 1 jawaban from tbl_mentor_data_feedback where tbl_mentor_data_feedback.id_feedback=tbl_mentor_feedback.id_feedback and tbl_mentor_data_feedback.nomor='$nomor_mentoring' and tbl_mentor_data_feedback.dmc='1' and tbl_mentor_data_feedback.nik_mentor='$nik_mentor' and tbl_mentor_data_feedback.na='n') as jawaban_dmc1");
		}
		if($nik_mentor_dmc2!=null){
			$this->db->select("(select top 1 jawaban from tbl_mentor_data_feedback where tbl_mentor_data_feedback.id_feedback=tbl_mentor_feedback.id_feedback and tbl_mentor_data_feedback.nomor='$nomor_mentoring' and tbl_mentor_data_feedback.dmc='2' and tbl_mentor_data_feedback.nik_mentor='$nik_mentor_dmc2' and tbl_mentor_data_feedback.na='n') as jawaban_dmc2");
		}else{
			$this->db->select("(select top 1 jawaban from tbl_mentor_data_feedback where tbl_mentor_data_feedback.id_feedback=tbl_mentor_feedback.id_feedback and tbl_mentor_data_feedback.nomor='$nomor_mentoring' and tbl_mentor_data_feedback.dmc='2' and tbl_mentor_data_feedback.nik_mentor='$nik_mentor' and tbl_mentor_data_feedback.na='n') as jawaban_dmc2");
		}
		if($nik_mentor_dmc3!=null){
			$this->db->select("(select top 1 jawaban from tbl_mentor_data_feedback where tbl_mentor_data_feedback.id_feedback=tbl_mentor_feedback.id_feedback and tbl_mentor_data_feedback.nomor='$nomor_mentoring' and tbl_mentor_data_feedback.dmc='3' and tbl_mentor_data_feedback.nik_mentor='$nik_mentor_dmc3' and tbl_mentor_data_feedback.na='n') as jawaban_dmc3");
		}else{
			$this->db->select("(select top 1 jawaban from tbl_mentor_data_feedback where tbl_mentor_data_feedback.id_feedback=tbl_mentor_feedback.id_feedback and tbl_mentor_data_feedback.nomor='$nomor_mentoring' and tbl_mentor_data_feedback.dmc='3' and tbl_mentor_data_feedback.nik_mentor='$nik_mentor' and tbl_mentor_data_feedback.na='n') as jawaban_dmc3");
		}
		$this->db->select('tbl_mentor_feedback.*');
		$this->db->from('tbl_mentor_feedback');

		$this->db->where("tbl_mentor_feedback.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_user_autocomplete($cari = NULL, $jenis = NULL)
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
		if($jenis=='mentor'){
			$this->db->where("tbl_user.id_level < 9102 ");
		}else{
			$this->db->where("tbl_karyawan.nik not in(select tbl_mentor_data.nik_mentee from tbl_mentor_data where tbl_mentor_data.id_status not in(6,7) and tbl_mentor_data.na='n')");
			$this->db->where("tbl_user.id_divisi = '".base64_decode($this->session->userdata("-id_divisi-"))."'");
			$this->db->where("tbl_user.id_departemen = '".base64_decode($this->session->userdata("-id_departemen-"))."'");
			$this->db->where("tbl_user.id_level > ".base64_decode($this->session->userdata("-id_level-"))." ");
		}
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}
	
	function get_data_status($conn = NULL, $id_status = NULL, $na = NULL, $del = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_mentor_status.*');
		$this->db->from('tbl_mentor_status');
		if ($id_status !== NULL) {
			$this->db->where('tbl_mentor_status.id_status', $id_status);
		}
		if ($na !== NULL) {
			$this->db->where('tbl_mentor_status.na', $na);
		}
		if ($del !== NULL) {
			$this->db->where('tbl_mentor_status.del', $del);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	function get_data_mentor_bom($conn = NULL, $nomor = NULL, $active = NULL, $deleted = 'n', $filter_status = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$id_user	= base64_decode($this->session->userdata("-id_user-"));
		$nik_login	= base64_decode($this->session->userdata("-nik-"));
		$this->datatables->select(" 
									$id_user as id_user,
									$nik_login as nik_login,
									nomor,
									nomor_mentoring,
									nik_mentor_dmc1,
									nama_mentor_dmc1,
									nik_mentor_dmc2,
									nama_mentor_dmc2,
									nik_mentor_dmc3,
									nama_mentor_dmc3,
									nik_mentor,
									nik_mentee,
									nama_mentor,
									jabatan_mentor,
									departemen_mentor,
									tanggal_sesi1_rencana_format,
									tanggal_sesi1_aktual_format,
									tanggal_sesi2_rencana_format,
									tanggal_sesi2_aktual_format,
									tanggal_dmc1_rencana_format,
									tanggal_dmc1_aktual_format,
									tanggal_dmc2_rencana_format,
									tanggal_dmc2_aktual_format,
									tanggal_dmc3_rencana_format,
									tanggal_dmc3_aktual_format,
									sla,
									id_status,
									nama_status,
									nama_status_group,
									warna_status,
									detail_status,
									url_scraft,
									login_buat,
									na,
									del");
		$this->datatables->from('vw_mentor_data_mentor');

		if ($nomor !== NULL) {
			$this->datatables->where('nomor', $nomor);
		}

		if ($filter_status != NULL) {
			if (is_string($filter_status)) $filter_status = explode(",", $filter_status);
			// $this->datatables->where_in('id_status', $filter_status);
			$this->datatables->where_in('nama_status_group', $filter_status);
		}
		$this->datatables->where('nik_mentee', base64_decode($this->session->userdata("-nik-")));
		
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("nomor"));
		return $this->general->jsonify($raw);
	}
	function get_data_user($conn = NULL, $nik = NULL, $na = NULL, $del = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_user.id_user");
		$this->db->select("tbl_jabatan.id_jabatan");
		$this->db->select("tbl_jabatan.nama as nama_jabatan");
		$this->db->select("tbl_departemen.id_departemen");
		$this->db->select("tbl_departemen.nama as nama_departemen");
		$this->db->select('tbl_karyawan.*');
		$this->db->from('tbl_karyawan');
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan', 'left outer');
		$this->db->join('tbl_jabatan', 'tbl_jabatan.id_jabatan = tbl_user.id_jabatan', 'left outer');
		$this->db->join('tbl_departemen', 'tbl_departemen.id_departemen = tbl_user.id_departemen', 'left outer');
		
		if ($nik !== NULL) {
			$this->db->where('tbl_karyawan.nik', $nik);
		}
		if ($na !== NULL) {
			$this->db->where('tbl_karyawan.na', $na);
		}
		if ($del !== NULL) {
			$this->db->where('tbl_karyawan.del', $del);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	
	function get_data_mentee_bom($conn = NULL, $nomor = NULL, $active = NULL, $deleted = 'n', $filter_status = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$id_user	= base64_decode($this->session->userdata("-id_user-"));
		$nik_login	= base64_decode($this->session->userdata("-nik-"));
		$this->datatables->select(" 
									$id_user as id_user,
									$nik_login as nik_login,
									getdate() as hari_ini,
									tanggal_sesi1_rencana,
									tanggal_sesi2_rencana,
									tanggal_dmc1_rencana,
									tanggal_dmc2_rencana,
									tanggal_dmc3_rencana,
									nomor,
									nomor_mentoring,
									nik_mentor_dmc1,
									nama_mentor_dmc1,
									nik_mentor_dmc2,
									nama_mentor_dmc2,
									nik_mentor_dmc3,
									nama_mentor_dmc3,
									nik_mentor,
									nik_mentee,
									nama_mentee,
									nama_jabatan_mentee,
									nama_departemen_mentee,
									telepon_mentee,
									tanggal_sesi1_rencana_format,
									tanggal_sesi1_aktual_format,
									tanggal_sesi2_rencana_format,
									tanggal_sesi2_aktual_format,
									tanggal_dmc1_rencana_format,
									tanggal_dmc1_aktual_format,
									tanggal_dmc2_rencana_format,
									tanggal_dmc2_aktual_format,
									tanggal_dmc3_rencana_format,
									tanggal_dmc3_aktual_format,
									sla,
									id_status,
									nama_status,
									nama_status_group,
									warna_status,
									detail_status,
									url_scraft,
									login_buat,
									na,
									del");
		$this->datatables->from('vw_mentor_data_mentee');

		if ($nomor !== NULL) {
			$this->datatables->where('nomor', $nomor);
		}

		if ($filter_status != NULL) {
			if (is_string($filter_status)) $filter_status = explode(",", $filter_status);
			// $this->datatables->where_in('id_status', $filter_status);
			$this->datatables->where_in('nama_status_group', $filter_status);
		}
		// $this->datatables->where('nik_mentor', base64_decode($this->session->userdata("-nik-")));
		$this->datatables->where("(nik_mentor='".base64_decode($this->session->userdata("-nik-"))."' or nik_mentor_dmc1='".base64_decode($this->session->userdata("-nik-"))."' or nik_mentor_dmc2='".base64_decode($this->session->userdata("-nik-"))."'   or nik_mentor_dmc3='".base64_decode($this->session->userdata("-nik-"))."')");
		
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("nomor"));
		return $this->general->jsonify($raw);
	}
	
	function get_data_mentor($conn = NULL, $nomor = NULL, $na = NULL, $del = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("
							tbl_karyawan_mentor.nik as nik_mentor,
							tbl_karyawan_mentor.nama as nama_mentor,
							tbl_karyawan_mentor.email as email_mentor,
							tbl_karyawan_dmc1.nik as nik_mentor_dmc1,
							tbl_karyawan_dmc1.nama as nama_mentor_dmc1,
							tbl_karyawan_dmc1.email as email_mentor_dmc1,
							tbl_karyawan_dmc2.nik as nik_mentor_dmc2,
							tbl_karyawan_dmc2.nama as nama_mentor_dmc2,
							tbl_karyawan_dmc2.email as email_mentor_dmc2,
							tbl_karyawan_dmc3.nik as nik_mentor_dmc3,
							tbl_karyawan_dmc3.nama as nama_mentor_dmc3,
							tbl_karyawan_dmc3.email as email_mentor_dmc3,
							tbl_karyawan.nama as nama_mentee,
							tbl_jabatan.nama as nama_jabatan_mentee,
							tbl_departemen.nama as nama_departemen_mentee,
							tbl_mentor_status.nama as nama_status,
							tbl_mentor_data.nomor as nomor_mentoring,
							CONVERT(varchar, tbl_mentor_data.tanggal_buat, 104) as tanggal_buat_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_sesi1_rencana, 104) as tanggal_sesi1_rencana_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_sesi1_aktual, 104) as tanggal_sesi1_aktual_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_sesi2_rencana, 104) as tanggal_sesi2_rencana_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_sesi2_aktual, 104) as tanggal_sesi2_aktual_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_dmc1_rencana, 104) as tanggal_dmc1_rencana_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_dmc1_aktual, 104) as tanggal_dmc1_aktual_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_dmc2_rencana, 104) as tanggal_dmc2_rencana_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_dmc2_aktual, 104) as tanggal_dmc2_aktual_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_dmc3_rencana, 104) as tanggal_dmc3_rencana_format,
							CONVERT(varchar, tbl_mentor_data.tanggal_dmc3_aktual, 104) as tanggal_dmc3_aktual_format,
							
							");
		$this->db->select('tbl_mentor_data.*');
		$this->db->from('tbl_mentor_data');
		$this->db->join('tbl_mentor_status', 'tbl_mentor_status.id_status = tbl_mentor_data.id_status', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_mentor_data.nik_mentee', 'left outer');
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan', 'left outer');
		$this->db->join('tbl_jabatan', 'tbl_jabatan.id_jabatan = tbl_user.id_jabatan', 'left outer');
		$this->db->join('tbl_departemen', 'tbl_departemen.id_departemen = tbl_user.id_departemen', 'left outer');
		$this->db->join('tbl_karyawan tbl_karyawan_mentor', 'tbl_karyawan_mentor.nik = tbl_mentor_data.nik_mentor', 'left outer');
		$this->db->join('tbl_karyawan tbl_karyawan_dmc1', 'tbl_karyawan_dmc1.nik = tbl_mentor_data.nik_mentor_dmc1', 'left outer');
		$this->db->join('tbl_karyawan tbl_karyawan_dmc2', 'tbl_karyawan_dmc2.nik = tbl_mentor_data.nik_mentor_dmc2', 'left outer');
		$this->db->join('tbl_karyawan tbl_karyawan_dmc3', 'tbl_karyawan_dmc3.nik = tbl_mentor_data.nik_mentor_dmc3', 'left outer');
		
		if ($nomor !== NULL) {
			$this->db->where('tbl_mentor_data.nomor', $nomor);
		}
		if ($na !== NULL) {
			$this->db->where('tbl_mentor_data.na', $na);
		}
		if ($del !== NULL) {
			$this->db->where('tbl_mentor_data.del', $del);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	
	function get_data_dokumen($conn = NULL, $nomor = NULL, $jenis = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_mentor_file.*');
		$this->db->from('tbl_mentor_file');
	
		if ($nomor !== NULL) {
			$this->db->where('tbl_mentor_file.nomor', $nomor);
		}
		if ($jenis !== NULL) {
			$this->db->where('tbl_mentor_file.jenis', $jenis);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	
	
// ================
	function get_email_recipient($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        // $query = $this->db->query("EXEC SP_Kiranaku_Evaluasi_Depo_Recipient_Email 'EV/1/DWJ1/10/2022'");
        $query = $this->db->query("EXEC SP_Kiranaku_Mentor_Recipient_Email");
        $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
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

		$this->db->select("CONVERT(VARCHAR(10), tbl_mentor_data_log_status.tanggal_buat, 104) as tanggal_format");
		$this->db->select("CONVERT(VARCHAR(8), tbl_mentor_data_log_status.tanggal_buat, 108) as jam_format");
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select('tbl_mentor_data_log_status.*');
		$this->db->from('tbl_mentor_data_log_status');
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_mentor_data_log_status.login_buat', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		if ($nomor !== NULL) {
			$this->db->where('tbl_mentor_data_log_status.nomor', $nomor);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_mentor_data_log_status.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_mentor_data_log_status.del', $deleted);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
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
							THEN ISNULL((select SUM(tbl_depo_data_biaya_depo.biaya) from tbl_depo_data_biaya_depo where tbl_depo_data_biaya_depo.id_biaya in(1) and tbl_depo_data_biaya_depo.na='n' and tbl_depo_data_biaya_depo.nomor='OP/1/KJP2/07/2022' ),0) 
							--operasional
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 2 
							THEN ISNULL((select SUM(tbl_depo_data_biaya_depo.biaya) from tbl_depo_data_biaya_depo where tbl_depo_data_biaya_depo.id_biaya in(3) and tbl_depo_data_biaya_depo.na='n' and tbl_depo_data_biaya_depo.nomor='OP/1/KJP2/07/2022'),0) 
							--opex
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 3 and ((select top 1 tbl_depo_data.jenis_depo from  tbl_depo_data where tbl_depo_data.id_depo_master='83' and tbl_depo_data.na='n')='tetap')
							THEN ISNULL((select SUM(tbl_depo_data_biaya_depo.biaya) from tbl_depo_data_biaya_depo where tbl_depo_data_biaya_depo.id_biaya in(select tbl_depo_biaya.id_biaya from tbl_depo_biaya where tbl_depo_biaya.jenis_depo='tetap' and tbl_depo_biaya.jenis_biaya_detail='transaksi' and tbl_depo_biaya.id_biaya not in(1,3)) and tbl_depo_data_biaya_depo.na='n' and tbl_depo_data_biaya_depo.nomor='OP/1/KJP2/07/2022'),0) 
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 3 and ((select top 1 tbl_depo_data.jenis_depo from  tbl_depo_data where tbl_depo_data.id_depo_master='83' and tbl_depo_data.na='n')='mitra')
							THEN ISNULL((select SUM(tbl_depo_data_biaya_depo.biaya) from tbl_depo_data_biaya_depo where tbl_depo_data_biaya_depo.id_biaya in(select tbl_depo_biaya.id_biaya from tbl_depo_biaya where tbl_depo_biaya.jenis_depo='mitra' and tbl_depo_biaya.jenis_biaya_detail='transaksi' and tbl_depo_biaya.id_biaya not in(1,3)) and tbl_depo_data_biaya_depo.na='n' and tbl_depo_data_biaya_depo.nomor='OP/1/KJP2/07/2022'),0) 
							--biaya angkut
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 4
							THEN ISNULL((select SUM(tbl_depo_data_biaya_trans.biaya_per_kg) from tbl_depo_data_biaya_trans where tbl_depo_data_biaya_trans.na='n' and tbl_depo_data_biaya_trans.nomor='OP/1/KJP2/07/2022'),0) 
							--gapok
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 5
							THEN ISNULL((select SUM(tbl_depo_data_biaya_sdm.gaji_pokok) from tbl_depo_data_biaya_sdm where tbl_depo_data_biaya_sdm.na='n' and tbl_depo_data_biaya_sdm.nomor='OP/1/KJP2/07/2022'),0) 
							--tunjangan
							WHEN tbl_depo_evaluasi_biaya.id_evaluasi_biaya = 6
							THEN ISNULL((select SUM(tbl_depo_data_biaya_sdm.tunjangan) from tbl_depo_data_biaya_sdm where tbl_depo_data_biaya_sdm.na='n' and tbl_depo_data_biaya_sdm.nomor='OP/1/KJP2/07/2022'),0) 
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

		$this->db->select("DateDiff (Month,(select top 1 ZDMPURBKR01.BEDAT from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_depo_data.pabrik and ZDMPURBKR01.NMDPO COLLATE SQL_Latin1_General_CP1_CI_AS =tbl_depo_data.nama order by ZDMPURBKR01.BEDAT ASC),(select top 1 ZDMPURBKR01.BEDAT from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_depo_data.pabrik and ZDMPURBKR01.NMDPO COLLATE SQL_Latin1_General_CP1_CI_AS =tbl_depo_data.nama order by ZDMPURBKR01.BEDAT DESC))as jumlah_bulan");
		$this->db->select("(select top 1 ZDMPURBKR01.BEDAT from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_depo_data.pabrik and ZDMPURBKR01.NMDPO COLLATE SQL_Latin1_General_CP1_CI_AS =tbl_depo_data.nama order by ZDMPURBKR01.BEDAT DESC) as tanggal_akhir_transaksi");
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
	function get_data_aktual($conn = NULL, $pabrik = NULL, $nama = NULL, $tahun_ke = NULL, $bulan_ke = NULL, $bulan_ke_digit = NULL, $jumlah_bulan = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$tahun_lalu	= $tahun_ke-1;
		$periode 	= $tahun_ke.'-'.$bulan_ke;
		$this->db->select("tbl_depo_data.pabrik");
		$this->db->select("tbl_depo_data.nama");
		if($jumlah_bulan <= 12){
			$this->db->select("tbl_depo_data_target.m1");
			$this->db->select("tbl_depo_data_target.m2");
			$this->db->select("tbl_depo_data_target.m3");
			$this->db->select("tbl_depo_data_target.m4");
			$this->db->select("tbl_depo_data_target.m5");
			$this->db->select("tbl_depo_data_target.m6");
			$this->db->select("tbl_depo_data_target.m7");
			$this->db->select("tbl_depo_data_target.m8");
			$this->db->select("tbl_depo_data_target.m9");
			$this->db->select("tbl_depo_data_target.m10");
			$this->db->select("tbl_depo_data_target.m11");
			$this->db->select("tbl_depo_data_target.m12");
		}else{
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM12 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_lalu' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m1");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM01 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m2");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM02 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m3");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM03 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m4");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM04 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m5");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM05 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m6");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM06 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m7");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM07 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m8");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM08 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m9");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM09 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m10");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM10 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m11");
			$this->db->select("ISNULL((select top 1 ZKISSTT_0389.PJM11 from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0389 ZKISSTT_0389 where ZKISSTT_0389.EKORG='$pabrik' and ZKISSTT_0389.MJAHR='$tahun_ke' AND ZKISSTT_0389.BOD like '%WORKSHOP V%' AND ZKISSTT_0389.SOURCE='$nama'),0) as m12");
		}
		$this->db->select("(select sum(ZDMPURBKR01.QTBSH) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='$nama' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke') as berat_basah");
		$this->db->select("(select sum(ZDMPURBKR01.QTFAK) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='$nama' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke') as berat_kering");
		$this->db->select("(select ( SUM(AVCS4) / (SUM(QTCNT) + SUM(QTFAK)) ) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke') as harga_notarin");
		$this->db->select("(select  SUM(AVCS4) / (SUM(QTCNT) + SUM(QTFAK))  from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='$nama' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke') as harga_beli_depo");

		//tbl_depo_sp_sicom_aktual
		$this->db->select("(select mixed_sicom from [10.0.0.32].portal.dbo.tbl_depo_sp_sicom_aktual where tahun='$tahun_ke' and bulan='$bulan_ke_digit' and pabrik='$pabrik') as mixed_sicom");
		$this->db->select("(select biaya_produksi from [10.0.0.32].portal.dbo.tbl_depo_sp_sicom_aktual where tahun='$tahun_ke' and bulan='$bulan_ke_digit' and pabrik='$pabrik') as biaya_produksi");
		//tbl_depo_sp_harga_beli_pabrik_aktual
		$this->db->select("(select harga_beli from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_pabrik_aktual where periode='$periode' and pabrik='$pabrik') as harga_beli_batch_pabrik");
		$this->db->select("(select persen_susut from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_pabrik_aktual where periode='$periode' and pabrik='$pabrik') as persen_susut_batch_pabrik");
		//tbl_depo_sp_harga_beli_batch_depo_aktual
		$this->db->select("(select harga_beli from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_depo_aktual where periode='$periode' and pabrik='$pabrik') as harga_beli_batch_depo");
		$this->db->select("(select persen_susut from [10.0.0.32].portal.dbo.tbl_depo_sp_harga_beli_depo_aktual where periode='$periode' and pabrik='$pabrik') as persen_susut_batch_depo");
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
	function get_data_biaya_aktual($conn = NULL, $pabrik = NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL)
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
			$this->db->where("tanggal between '$tanggal_awal' and '$tanggal_akhir'");
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

        // $query = $this->db->query("EXEC SP_DEPO_Generate_Number 'evaluasi', '" . $param['jenis_depo'] . "', '" . $param['pabrik'] . "', '" . $param['year'] . "', '" . $param['month'] . "'");
        $query = $this->db->query("EXEC SP_Mentor_Generate_Number 'KMG', '" . $param['year'] . "', '" . $param['month'] . "' ");
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
