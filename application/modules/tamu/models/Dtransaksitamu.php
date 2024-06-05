<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : BUKU TAMU
@author       : Lukman Hakim (7143)
@contributor  : 
      1. Benazi S. Bahari (10183) 17-06-2021
         tambah fungsi untuk get data self assessment dan peserta event         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dtransaksitamu extends CI_Model{
    public function get_karyawan($params = array(), $param2=NULL)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $ho = isset($params['ho']) ? $params['ho'] : null;
        $search = isset($params['search']) ? $params['search'] : null;

        $this->db->select("tbl_karyawan.*,tbl_departemen.nama as nama_departemen");
        $this->db->select("tbl_divisi.nama as nama_divisi");
        $this->db->select("tbl_sub_divisi.nama as nama_sub_divisi");
        $this->db->select("tbl_seksi.nama as nama_seksi");
        $this->db->select("tbl_wf_master_plant.plant_name as nama_pabrik");
		$this->db->select("tbl_user.id_divisi");
        $this->db->from('tbl_karyawan');
        $this->db->join('tbl_user', 'tbl_user.id_karyawan=tbl_karyawan.id_karyawan', 'left outer');
        $this->db->join('tbl_level', 'tbl_user.id_level= tbl_level.id_level', 'left outer');
        $this->db->join('tbl_departemen', 'tbl_departemen.id_departemen= tbl_user.id_departemen', 'left outer');
        $this->db->join('tbl_divisi', 'tbl_divisi.id_divisi= tbl_user.id_divisi', 'left outer');
        $this->db->join('tbl_sub_divisi', 'tbl_sub_divisi.id_sub_divisi= tbl_user.id_sub_divisi', 'left outer');
        $this->db->join('tbl_seksi', 'tbl_seksi.id_seksi= tbl_user.id_seksi', 'left outer');
        $this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant= tbl_karyawan.gsber', 'left outer');

        $this->db->where('tbl_user.na', 'n');
        $this->db->where('tbl_user.del', 'n');
        $this->db->where('tbl_karyawan.na', 'n');
        $this->db->where('tbl_karyawan.del', 'n');

        if (isset($ho) && $ho)
            $this->db->where('tbl_karyawan.ho', 'y');
        if (isset($param2)){
			if(base64_decode($this->session->userdata("-ho-"))=='y'){
				$this->db->where("tbl_karyawan.id_karyawan in(select agent from v_aset_agent)");
			}else{
				$this->db->where("tbl_karyawan.id_karyawan in(select agent from v_aset_agent where lokasi_agent='".base64_decode($this->session->userdata("-gsber-"))."')");
			}
		}

        if (isset($search) && !empty($search))
        {
            $this->db->group_start();
            $this->db->where('CONVERT(VARCHAR(MAX),tbl_karyawan.nik)', $search);
            $this->db->or_like('tbl_karyawan.nama', $search);
            $this->db->group_end();
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
	
	function get_data_tamu($conn = NULL, $id_tamu = NULL, $active = NULL, $deleted = 'n', $filter_from = NULL, $filter_to = NULL, $filter_status = NULL, $cari = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_tamu.*');
		$this->db->select("CONVERT(varchar, tbl_tamu.tanggal_kunjungan, 104) as caption_tanggal_kunjungan");
		$this->db->select("CONVERT(VARCHAR(5), tbl_tamu.waktu_datang, 108) as caption_waktu_datang");
		$this->db->select("CONVERT(VARCHAR(5), tbl_tamu.waktu_pulang, 108) as caption_waktu_pulang");
		$this->db->select('CASE
								WHEN tbl_tamu.completed = \'y\' THEN \'<span class="label label-success">Completed</span>\'
								ELSE \'<span class="label label-danger">Not Complete</span>\'
						   END as label_status');
		$this->db->from('tbl_tamu');			
		if ($id_tamu !== NULL) {
			$this->db->where('tbl_tamu.id_tamu', $id_tamu);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_tamu.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_tamu.del', $deleted);
		}
		if (($filter_from !== NULL)and($filter_to !== NULL)) {	
			$this->db->where("tbl_tamu.tanggal_kunjungan between '".$this->generate->regenerateDateFormat($filter_from)."' and '".$this->generate->regenerateDateFormat($filter_to)."'");
		}
		if($filter_status != NULL){
			if(is_string($filter_status)) $filter_status = explode(",", $filter_status);
			$this->db->where_in('tbl_tamu.completed', $filter_status);
		}else{
			$this->db->where("tbl_tamu.completed='n'");
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

    function get_data_assessment($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//
        $this->db->select("tbl_tamu_ass_data.*, tbl_tamu_ass_pertanyaan.pertanyaan");
        $this->db->from("tbl_tamu_ass_data");
        $this->db->join("tbl_tamu", "tbl_tamu.id_tamu = tbl_tamu_ass_data.id_tamu", "inner");
        $this->db->join("tbl_tamu_ass_pertanyaan", "tbl_tamu_ass_pertanyaan.id_pertanyaan = tbl_tamu_ass_data.id_pertanyaan", "left");
        $this->db->where('tbl_tamu_ass_data.na', 'n');
        $this->db->where('tbl_tamu_ass_data.del', 'n');

        if (isset($param['id_tamu']) && $param['id_tamu'] !== NULL)
            $this->db->where('tbl_tamu_ass_data.id_tamu', $param['id_tamu']);
        if (isset($param['id_peserta']) && $param['id_peserta'] !== NULL)
            $this->db->where('tbl_tamu_ass_data.id_peserta', $param['id_peserta']);

        $query = $this->db->get();

        // if (isset($param['id_peserta']) && $param['id_peserta'] !== NULL) {
        //     $result = $query->row();
        // } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}

    function get_data_peserta($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//
        $this->db->select("tbl_tamu_event.*");
        $this->db->from("tbl_tamu_ass_data");
        $this->db->join("tbl_tamu_peserta", "tbl_tamu_peserta.id = tbl_tamu_ass_data.id_peserta", "inner");
        $this->db->join("tbl_tamu_event", "tbl_tamu_event.id = tbl_tamu_peserta.id_event", "inner");
        $this->db->where('tbl_tamu_ass_data.na', 'n');
        $this->db->where('tbl_tamu_ass_data.del', 'n');

        if (isset($param['id_tamu']) && $param['id_tamu'] !== NULL)
            $this->db->where('tbl_tamu_ass_data.id_tamu', $param['id_tamu']);

        $query = $this->db->get();

        // if (isset($param['id_peserta']) && $param['id_peserta'] !== NULL) {
            $result = $query->row();
        // } else
            // $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}
}
?>