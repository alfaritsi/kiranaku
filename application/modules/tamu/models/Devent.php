<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : BUKU TAMU (Event)
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Devent extends CI_Model{
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
	
	function get_data_event($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        // if (isset($param['return']) && $param['return'] == "datatables") {
        //     $this->db->select(" tbl_tamu_event.*,
        //         CONVERT(VARCHAR, tbl_tamu_event.waktu_mulai, 104) as tanggal_format,
        //         CONVERT(VARCHAR(5), tbl_tamu_event.waktu_mulai, 108) as waktu_datang_format,
        //         CONVERT(VARCHAR(5), tbl_tamu_event.waktu_selesai, 108) as waktu_selesai_format");
        //     $this->db->from("tbl_tamu_event");
        //     // $this->db->where('tbl_tamu_event.na', 'n');
        //     // $this->db->where('tbl_tamu_event.del', 'n');

        //     if (isset($param['id_event']) && $param['id_event'] !== NULL)
        //         $this->db->where('tbl_tamu_event.id', $param['id']);

        //     $main_query = $this->db->get_compiled_select();
        //     $this->db->reset_query();
            
        //     $this->datatables->select("id,
        //         nama,
        //         waktu_mulai,
        //         waktu_selesai,
        //         nik_pic,
        //         tanggal_format,
        //         waktu_mulai_format,
        //         waktu_selesai_format");
        //     $this->datatables->from("($main_query) as vw_event");
        //     $result = $this->datatables->generate();
        //     $raw = json_decode($result, true);

        //     if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
        //         $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

        //     $result = $this->general->jsonify($raw);
        // } else {
            $this->db->select(" tbl_tamu_event.*,
                CONVERT(VARCHAR, tbl_tamu_event.waktu_mulai, 104) as tanggal_format,
                CONVERT(VARCHAR(5), tbl_tamu_event.waktu_mulai, 108) as waktu_mulai_format,
                CONVERT(VARCHAR(5), tbl_tamu_event.waktu_selesai, 108) as waktu_selesai_format,
                tbl_karyawan.nama AS nama_pic");
            $this->db->from("tbl_tamu_event");
            $this->db->join("tbl_karyawan", "tbl_tamu_event.nik_pic = tbl_karyawan.nik", "left");
            $this->db->where('tbl_tamu_event.na', 'n');
            $this->db->where('tbl_tamu_event.del', 'n');

            if (isset($param['id_event']) && $param['id_event'] !== NULL)
                $this->db->where('tbl_tamu_event.id', $param['id_event']);
            if (isset($param['filter_from']) && $param['filter_from'] !== NULL)
                $this->db->where('tbl_tamu_event.waktu_mulai >=', $this->generate->regenerateDateFormat($param['filter_from']));
            if (isset($param['filter_to']) && $param['filter_to'] !== NULL)
                $this->db->where('tbl_tamu_event.waktu_mulai <=', $this->generate->regenerateDateFormat($param['filter_to']));

            $query = $this->db->get();

            if (isset($param['id_event']) && $param['id_event'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt']);
        // }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}

    function get_data_peserta($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        // if (isset($param['return']) && $param['return'] == "datatables") {
        //     $this->db->select(" tbl_tamu_event.*,
        //         CONVERT(VARCHAR, tbl_tamu_event.waktu_mulai, 104) as tanggal_format,
        //         CONVERT(VARCHAR(5), tbl_tamu_event.waktu_mulai, 108) as waktu_datang_format,
        //         CONVERT(VARCHAR(5), tbl_tamu_event.waktu_selesai, 108) as waktu_selesai_format");
        //     $this->db->from("tbl_tamu_event");
        //     // $this->db->where('tbl_tamu_event.na', 'n');
        //     // $this->db->where('tbl_tamu_event.del', 'n');

        //     if (isset($param['id_event']) && $param['id_event'] !== NULL)
        //         $this->db->where('tbl_tamu_event.id', $param['id']);

        //     $main_query = $this->db->get_compiled_select();
        //     $this->db->reset_query();
            
        //     $this->datatables->select("id,
        //         nama,
        //         waktu_mulai,
        //         waktu_selesai,
        //         nik_pic,
        //         tanggal_format,
        //         waktu_mulai_format,
        //         waktu_selesai_format");
        //     $this->datatables->from("($main_query) as vw_event");
        //     $result = $this->datatables->generate();
        //     $raw = json_decode($result, true);

        //     if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
        //         $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

        //     $result = $this->general->jsonify($raw);
        // } else {
            $this->db->select("tbl_tamu_peserta.*,
                tbl_tamu_event.nama_event, 
                tbl_tamu_event.waktu_mulai, 
                tbl_tamu_event.waktu_selesai,
                tbl_tamu_event.nik_pic,
                CONVERT(VARCHAR, tbl_tamu_event.waktu_mulai, 104) as tanggal_format,
                CONVERT(VARCHAR(5), tbl_tamu_event.waktu_mulai, 108) as waktu_mulai_format,
                CONVERT(VARCHAR(5), tbl_tamu_event.waktu_selesai, 108) as waktu_selesai_format,
                tbl_karyawan.nama AS nama_pic");
            $this->db->select('CASE 
                    WHEN 
                        EXISTS (SELECT * FROM tbl_tamu_ass_data WHERE tbl_tamu_ass_data.id_peserta = tbl_tamu_peserta.id)
                    THEN 1
                    ELSE 0
                END AS has_assessment');
            $this->db->from("tbl_tamu_peserta");
            $this->db->join("tbl_tamu_event", "tbl_tamu_peserta.id_event = tbl_tamu_event.id", "inner");
            $this->db->join("tbl_karyawan", "tbl_tamu_event.nik_pic = tbl_karyawan.nik", "left");
            $this->db->where('tbl_tamu_peserta.na', 'n');
            $this->db->where('tbl_tamu_peserta.del', 'n');

            if (isset($param['id_event']) && $param['id_event'] !== NULL)
                $this->db->where('tbl_tamu_peserta.id_event', $param['id_event']);
            if (isset($param['id_peserta']) && $param['id_peserta'] !== NULL)
                $this->db->where('tbl_tamu_peserta.id', $param['id_peserta']);
                if (isset($param['email']) && $param['email'] !== NULL)
                $this->db->where('tbl_tamu_peserta.email', $param['email']);

            $query = $this->db->get();

            if (isset($param['id_peserta']) && $param['id_peserta'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt']);
        // }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}

    function get_data_pertanyaan($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_tamu_ass_pertanyaan.id_pertanyaan as id');
		$this->db->select('tbl_tamu_ass_pertanyaan.*');
		$this->db->from('tbl_tamu_ass_pertanyaan');
		$this->db->where('tbl_tamu_ass_pertanyaan.na', 'n');
        $this->db->where('tbl_tamu_ass_pertanyaan.del', 'n');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
		
	}	
}
?>