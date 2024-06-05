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

class Dapi extends CI_Model{
	
    function get_data_user($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

            $this->db->select("tbl_user.id_user as id");
			$this->db->select("tbl_karyawan.nik");
			$this->db->select("tbl_karyawan.nama as nama_karyawan");
            $this->db->select("tbl_user.*");
            $this->db->from("tbl_user");
            $this->db->join("tbl_karyawan", "tbl_karyawan.id_karyawan = tbl_user.id_karyawan", "left outer");

			if (isset($param['username']) && $param['username'] !== NULL)
                $this->db->where('tbl_user.username', $param['username']);

			if (isset($param['pass']) && $param['pass'] !== NULL)
                $this->db->where('tbl_user.pass', md5($param['pass']));
			
            $query = $this->db->get();

            if (isset($param['id_user']) && $param['id_user'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}
	//ambil dari tbl_batch(klems)
    function get_data_jadwal($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

            $this->db->select("tbl_peserta.id_peserta as id");
			$this->db->select("tbl_peserta.id_karyawan as nik");
			$this->db->select("tbl_batch.id_batch as id_jadwal");
            $this->db->select("tbl_peserta.*");
            $this->db->select("tbl_karyawan.nama as nama_karyawan");
            $this->db->select("tbl_program_batch.nama as nama_program");
            $this->db->select("tbl_tahap.nama as nama_tahap");
            $this->db->select("(select tbl_user.id_karyawan from tbl_user where username='".$param['username']."' and pass='".md5($param['pass'])."') as ck_nik");
            $this->db->from("tbl_peserta");
            $this->db->join("tbl_karyawan", "tbl_karyawan.nik = tbl_peserta.id_karyawan", "left outer");
            $this->db->join("tbl_batch", "tbl_batch.id_batch = tbl_peserta.id_batch", "left outer");
            $this->db->join("tbl_tahap", "tbl_tahap.id_tahap = tbl_batch.id_tahap", "left outer");
			$this->db->join("tbl_program_batch", "tbl_program_batch.id_program_batch = tbl_batch.id_program_batch", "left outer");
			$this->db->join("tbl_program", "tbl_program.id_program = tbl_program_batch.id_program", "left outer");
            $this->db->where('tbl_peserta.na', 'n');

			if (isset($param['username']) && $param['username'] !== NULL)
                $this->db->where('tbl_peserta.id_karyawan', $param['username']);

            if (isset($param['tanggal']) && $param['tanggal'] !== NULL)
                $this->db->where("tbl_batch.tanggal_awal <='".$param['tanggal']."' and tbl_batch.tanggal_akhir>= '".$param['tanggal']."'");

			$this->db->where("tbl_program_batch.status", "On Progress");
			$this->db->where("tbl_program.abbreviation", "BOKIN");
			$this->db->where("tbl_program_batch.na", "n");
			
            $query = $this->db->get();

            if (isset($param['id_jadwal']) && $param['id_jadwal'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}
    function get_data_jadwal_old($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

            $this->db->select("tbl_taksasi_jadwal_peserta.id_jadwal as id");
            $this->db->select("tbl_taksasi_jadwal_peserta.*");
            $this->db->select("tbl_taksasi_jadwal.nama as nama_tahap");
            $this->db->select("tbl_karyawan.nama as nama_karyawan");
            $this->db->select("(select tbl_user.id_karyawan from tbl_user where username='".$param['username']."' and pass='".md5($param['pass'])."') as ck_nik");
            $this->db->from("tbl_taksasi_jadwal_peserta");
            $this->db->join("tbl_karyawan", "tbl_karyawan.nik = tbl_taksasi_jadwal_peserta.nik", "left outer");
            $this->db->join("tbl_taksasi_jadwal", "tbl_taksasi_jadwal.id_jadwal = tbl_taksasi_jadwal_peserta.id_jadwal", "left outer");
            $this->db->where('tbl_taksasi_jadwal_peserta.na', 'n');
            $this->db->where('tbl_taksasi_jadwal.tanggal_final is null');
			$this->db->where('tbl_taksasi_jadwal.na', 'n');

            if (isset($param['username']) && $param['username'] !== NULL)
                $this->db->where('tbl_taksasi_jadwal_peserta.nik', $param['username']);

            $query = $this->db->get();

            if (isset($param['id_jadwal']) && $param['id_jadwal'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}
	
    function get_data_nilai($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

            $this->db->select("tbl_taksasi_jadwal_peserta_nilai_detail.no_sample as id");
            $this->db->select("tbl_taksasi_jadwal_peserta_nilai_detail.*");
            $this->db->from("tbl_taksasi_jadwal_peserta_nilai_detail");
            $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.na', 'n');

            if (isset($param['pabrik']) && $param['pabrik'] !== NULL)
                $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.pabrik', $param['pabrik']);

            if (isset($param['id_jadwal']) && $param['id_jadwal'] !== NULL)
                $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.id_jadwal', $param['id_jadwal']);

            if (isset($param['nik']) && $param['nik'] !== NULL)
                $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.nik', $param['nik']);

            if (isset($param['tanggal']) && $param['tanggal'] !== NULL)
                $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.tanggal', $param['tanggal']);

            $query = $this->db->get();

            // if (isset($param['tanggal']) && $param['tanggal'] !== NULL) {
                // $result = $query->row();
            // } else
				// $result = $query->result();

                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}
	
    function get_data_nilai_detail($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

            $this->db->select("vw_taksasi_nilai.no_sample as id");
            $this->db->select("vw_taksasi_nilai.*");
            $this->db->from("vw_taksasi_nilai");

            if (isset($param['nik']) && $param['nik'] !== NULL)
                $this->db->where('vw_taksasi_nilai.nik', $param['nik']);

            if (isset($param['tanggal']) && $param['tanggal'] !== NULL)
                $this->db->where('vw_taksasi_nilai.tanggal', $param['tanggal']);

            $query = $this->db->get();

            $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}
	
    function get_data_nilai_old($param = NULL) 
    {
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

            $this->db->select("tbl_taksasi_jadwal_peserta_nilai_detail.no_sample as id");
            $this->db->select("tbl_taksasi_jadwal_peserta_nilai_detail.*");
            $this->db->from("tbl_taksasi_jadwal_peserta_nilai_detail");
            $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.na', 'n');

            if (isset($param['pabrik']) && $param['pabrik'] !== NULL)
                $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.pabrik', $param['pabrik']);

            if (isset($param['id_jadwal']) && $param['id_jadwal'] !== NULL)
                $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.id_jadwal', $param['id_jadwal']);

            if (isset($param['nik']) && $param['nik'] !== NULL)
                $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.nik', $param['nik']);

            if (isset($param['tanggal']) && $param['tanggal'] !== NULL)
                $this->db->where('tbl_taksasi_jadwal_peserta_nilai_detail.tanggal', $param['tanggal']);

            $query = $this->db->get();

            // if (isset($param['tanggal']) && $param['tanggal'] !== NULL) {
                // $result = $query->row();
            // } else
				// $result = $query->result();

                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}

}
?>