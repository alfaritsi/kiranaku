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

class Dlaporanmentor extends CI_Model
{
	function get_data_all($conn = NULL, $nomor = NULL, $active = NULL, $deleted = 'n', $filter_status = NULL, $jenis = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('*');
		$this->db->from('vw_mentor_data_detail');

		if ($filter_status != NULL) {
			if (is_string($filter_status)) $filter_status = explode(",", $filter_status);
			$this->datatables->where_in('status', $filter_status);
		}
		if ($jenis !== NULL) {
			if($jenis=='ass'){
				$this->datatables->where("status in(1)");
			}
			if($jenis=='aim'){
				$this->datatables->where("status in(2)");
			}
			if(($jenis=='dmc')or($jenis=='rating')){
				$this->datatables->where("status in(3,4,5)");
			}
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_all_bom($conn = NULL, $nomor = NULL, $active = NULL, $deleted = 'n', $filter_status = NULL, $jenis = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$id_user	= base64_decode($this->session->userdata("-id_user-"));
		$nik_login	= base64_decode($this->session->userdata("-nik-"));
		$this->datatables->select(" 
									$id_user as id_user,
									$nik_login as nik_login,
									mantee_rate_mentor,
									mantee_rate_mentor_additional,
									comm_rate_mentor,
									comm_rate_mentor_additional,
									nomor,
									nik_mentor,
									nama_mentor,
									nik_mentor_additional,
									nama_mentor_additional,
									status,
									nama_status,
									nik_mentee,
									nama_mentee,
									nama_departemen_mentee,
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
									sasaran_pengembangan,
									isu_dmc1,
									tujuan_dmc1,
									realitas_dmc1,
									opsi_dmc1,
									rencana_aksi_dmc1,
									waktu_dmc1,
									indikator_berhasil_dmc1,
									catatan_dmc1,
									isu_dmc2,
									tujuan_dmc2,
									realitas_dmc2,
									opsi_dmc2,
									rencana_aksi_dmc2,
									waktu_dmc2,
									indikator_berhasil_dmc2,
									catatan_dmc2,
									isu_dmc3,
									tujuan_dmc3,
									realitas_dmc3,
									opsi_dmc3,
									rencana_aksi_dmc3,
									waktu_dmc3,
									indikator_berhasil_dmc3,
									catatan_dmc3,
									url_file,
									login_buat,
									na,
									del");
		$this->datatables->from('vw_mentor_data_detail');

		if ($nomor !== NULL) {
			$this->datatables->where('nomor', $nomor);
		}

		if ($filter_status != NULL) {
			if (is_string($filter_status)) $filter_status = explode(",", $filter_status);
			$this->datatables->where_in('status', $filter_status);
		}
		
		if ($jenis !== NULL) {
			if($jenis=='ass'){
				$this->datatables->where("status in(1)");
			}
			if($jenis=='aim'){
				$this->datatables->where("status in(2)");
			}
			if(($jenis=='dmc')or($jenis=='rating')){
				$this->datatables->where("status in(3,4,5)");
			}
		}
		
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("nomor"));
		return $this->general->jsonify($raw);
	}
}
