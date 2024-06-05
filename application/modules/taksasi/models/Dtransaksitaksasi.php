<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : TAKSASI BOKAR
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dtransaksitaksasi extends CI_Model
{
	function get_data_tahap($conn = NULL, $id_tahap = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $ck_id_tahap = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_tahap.*');
		$this->db->select('CASE
								WHEN tbl_tahap.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_tahap');
		$this->db->join('tbl_program', 'tbl_program.id_program = tbl_tahap.id_program', 'left outer');
		if ($id_tahap !== NULL) {
			$this->db->where('tbl_tahap.id_tahap', $id_tahap);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_tahap.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_tahap.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_tahap.nama', $nama);
		}
		if ($ck_id_tahap !== NULL) {
			$this->db->where("tbl_tahap.id_tahap!='$ck_id_tahap'");
		}
		$this->db->where("tbl_program.abbreviation='BOKIN'");
		
		$this->db->order_by("tbl_tahap.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_bobot($conn = NULL, $id_jadwal = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_taksasi_jadwal_bobot.id_nilai as id');
		$this->db->select('tbl_taksasi_nilai.nama as nama_nilai');
		$this->db->select('tbl_taksasi_nilai.otomatis');
		$this->db->select("tbl_taksasi_jadwal_bobot.*");
		$this->db->from('tbl_taksasi_jadwal_bobot');
		$this->db->join('tbl_taksasi_nilai', 'tbl_taksasi_nilai.id_nilai = tbl_taksasi_jadwal_bobot.id_nilai', 'left outer');

		if ($id_jadwal !== NULL) {
			$this->db->where('tbl_taksasi_jadwal_bobot.id_jadwal', $id_jadwal);
		}
		$this->db->where("tbl_taksasi_jadwal_bobot.na='n'");
		$this->db->order_by("tbl_taksasi_jadwal_bobot.id_nilai", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_peserta($conn = NULL, $id_jadwal = NULL, $id_program_batch = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_peserta.*");
		$this->db->select('tbl_karyawan.id_karyawan as id');
		$this->db->select('tbl_karyawan.nik');
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select("
					       CAST(
					         (
								SELECT 
								CONVERT(VARCHAR, ISNULL(
									CASE
										WHEN tbl_taksasi_nilai.otomatis='y'
										THEN 
											--(select cast((cast(SUM(hasil) as float)/ cast(count(hasil) as float))*100 as decimal(18,2)) from vw_taksasi_nilai where vw_taksasi_nilai.id_jadwal=tbl_peserta.id_batch and vw_taksasi_nilai.nik=tbl_peserta.id_karyawan and tbl_batch.tanggal_awal<=vw_taksasi_nilai.tanggal and vw_taksasi_nilai.tanggal>=tbl_batch.tanggal_awal)
											(select AVG(vw_taksasi_nilai_harian.nilai_harian) from vw_taksasi_nilai_harian where vw_taksasi_nilai_harian.id_jadwal=tbl_peserta.id_batch and vw_taksasi_nilai_harian.nik=tbl_peserta.id_karyawan)
										ELSE
											(select sum(tbl_taksasi_jadwal_peserta_nilai.nilai) from tbl_taksasi_jadwal_peserta_nilai where tbl_taksasi_jadwal_peserta_nilai.id_nilai=tbl_taksasi_jadwal_bobot.id_nilai and tbl_taksasi_jadwal_peserta_nilai.nik=tbl_peserta.id_karyawan and tbl_taksasi_jadwal_peserta_nilai.id_jadwal=tbl_peserta.id_batch and tbl_taksasi_jadwal_peserta_nilai.na='n')	
									END								
								,0))+RTRIM('|')
					            FROM 
								tbl_taksasi_jadwal_bobot
								left outer join tbl_taksasi_nilai on tbl_taksasi_nilai.id_nilai=tbl_taksasi_jadwal_bobot.id_nilai
								WHERE tbl_taksasi_jadwal_bobot.id_jadwal = $id_jadwal
								 and tbl_taksasi_jadwal_bobot.na='n'
								ORDER BY tbl_taksasi_jadwal_bobot.id_nilai asc
								FOR XML PATH ('')
							  ) as VARCHAR(MAX)
					       )  AS list_nilai,
						  ");
		$this->db->select("
						CASE
							WHEN 
							(select top 1 tbl_peserta_batch.lulus_bokin from tbl_peserta tbl_peserta_batch where tbl_peserta_batch.id_karyawan=tbl_peserta.id_karyawan and tbl_peserta.id_batch > tbl_peserta_batch.id_batch and tbl_peserta_batch.id_program_batch=434) is not null
							or
							(select count(*) from tbl_peserta tbl_peserta_batch where tbl_peserta_batch.id_karyawan=tbl_peserta.id_karyawan and tbl_peserta.id_batch > tbl_peserta_batch.id_batch and tbl_peserta_batch.id_program_batch=434)=0
							THEN 	1
							ELSE	0
						END as lulus
						");				  
						  
		$this->db->from('tbl_peserta');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_peserta.id_karyawan', 'left outer');
		$this->db->join('tbl_batch', 'tbl_batch.id_batch = tbl_peserta.id_batch', 'left outer');

		if ($id_jadwal !== NULL) {
			$this->db->where('tbl_peserta.id_batch', $id_jadwal);
		}
		
		$this->db->where("tbl_peserta.na='n'");
		$this->db->order_by("tbl_karyawan.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_peserta_old($conn = NULL, $id_jadwal = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_taksasi_jadwal_peserta.*");
		$this->db->select('tbl_karyawan.id_karyawan as id');
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select("
					       CAST(
					         (
								SELECT 
								CONVERT(VARCHAR, ISNULL(
								(select sum(tbl_taksasi_jadwal_peserta_nilai.nilai) from tbl_taksasi_jadwal_peserta_nilai)
								,0))+RTRIM('|')
					            FROM tbl_taksasi_jadwal_bobot
								WHERE tbl_taksasi_jadwal_bobot.id_jadwal = $id_jadwal
								 and tbl_taksasi_jadwal_bobot.na='n'
								ORDER BY tbl_taksasi_jadwal_bobot.id_nilai asc
								FOR XML PATH ('')
							  ) as VARCHAR(MAX)
					       )  AS list_nilai,
						  ");
		$this->db->select("(select top 1 CAST( SUM(tbl_taksasi_jadwal_peserta_nilai.nilai*tbl_taksasi_jadwal_peserta_nilai.bobot/100) AS DECIMAL(18,2)) from tbl_taksasi_jadwal_peserta_nilai where tbl_taksasi_jadwal_peserta_nilai.id_jadwal='$id_jadwal' and tbl_taksasi_jadwal_peserta_nilai.nik=tbl_taksasi_jadwal_peserta.nik and tbl_taksasi_jadwal_peserta_nilai.na='n') as total");				  
		// $this->db->select("CASE
								// --WHEN tbl_taksasi_jadwal_peserta.bobot >= (select top 1 CAST( SUM(tbl_taksasi_jadwal_peserta_nilai.nilai*tbl_taksasi_jadwal_peserta_nilai.bobot/100) AS DECIMAL(18,2)) from tbl_taksasi_jadwal_peserta_nilai where tbl_taksasi_jadwal_peserta_nilai.id_jadwal='$id_jadwal' and tbl_taksasi_jadwal_peserta_nilai.nik=tbl_taksasi_jadwal_peserta.nik and tbl_taksasi_jadwal_peserta_nilai.na='n')
								// 1=1
								// THEN <span class='label label-success'>LULUS</span>
								// ELSE <span class='label label-danger'>TIDAK LULUS</span>
						   // END as label_status");
		$this->db->from('tbl_taksasi_jadwal_peserta');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_taksasi_jadwal_peserta.nik', 'left outer');

		if ($id_jadwal !== NULL) {
			$this->db->where('tbl_taksasi_jadwal_peserta.id_jadwal', $id_jadwal);
		}
		$this->db->where("tbl_taksasi_jadwal_peserta.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_jadwal($conn = NULL, $id_jadwal = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('vw_taksasi_data.*');
		$this->db->from('vw_taksasi_data');
		if ($id_jadwal !== NULL) {
			$this->db->where('vw_taksasi_data.id_jadwal', $id_jadwal);
		}
		if ($active !== NULL) {
			$this->db->where('vw_taksasi_data.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('vw_taksasi_data.del', $deleted);
		}
		
		$this->db->order_by("vw_taksasi_data.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_jadwal_old($conn = NULL, $id_jadwal = NULL, $active = NULL, $deleted = 'n', $ck_nama = NULL, $ck_id_tahap = NULL, $ck_id_jadwal = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_taksasi_jadwal.*');
		$this->db->select('tbl_taksasi_tahap.pra_syarat');
		$this->db->select("
							CAST( 
							  ( 
								SELECT CAST(tbl_karyawan.nik as varchar(50)) + RTRIM('|')+ tbl_karyawan.nama  + RTRIM(',')
								FROM tbl_karyawan 
								 WHERE 1=1
								 and  tbl_karyawan.nik in(select tbl_taksasi_jadwal_peserta.nik from tbl_taksasi_jadwal_peserta where tbl_taksasi_jadwal_peserta.id_jadwal=tbl_taksasi_jadwal.id_jadwal)
								 order by tbl_karyawan.nama 
								FOR XML PATH ('') 
							  ) as VARCHAR(MAX) 
							) as list_peserta
						");
		$this->db->select("
							CAST( 
							  ( 
								SELECT CAST(tbl_karyawan.nik as varchar(50)) +RTRIM(',')
								FROM tbl_karyawan 
								 WHERE 1=1
								 and  tbl_karyawan.nik in(select tbl_taksasi_jadwal_peserta.nik from tbl_taksasi_jadwal_peserta where tbl_taksasi_jadwal_peserta.id_jadwal=tbl_taksasi_jadwal.id_jadwal)
								 order by tbl_karyawan.nama 
								FOR XML PATH ('') 
							  ) as VARCHAR(MAX) 
							) as peserta
						");
		$this->db->select('CASE
								WHEN tbl_taksasi_jadwal.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_taksasi_jadwal');
		$this->db->join('tbl_taksasi_tahap', 'tbl_taksasi_tahap.id_tahap = tbl_taksasi_jadwal.id_tahap', 'left outer');
		if ($id_jadwal !== NULL) {
			$this->db->where('tbl_taksasi_jadwal.id_jadwal', $id_jadwal);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_taksasi_jadwal.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_taksasi_jadwal.del', $deleted);
		}
		if ($ck_nama !== NULL) {
			$this->db->where('tbl_taksasi_jadwal.nama', $ck_nama);
		}
		if ($ck_id_tahap !== NULL) {
			$this->db->where('tbl_taksasi_jadwal.id_tahap', $ck_id_tahap);
		}
		if ($ck_id_jadwal !== NULL) {
			$this->db->where("tbl_taksasi_jadwal.id_jadwal!='$ck_id_jadwal'");
		}
		
		$this->db->order_by("tbl_taksasi_jadwal.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_jadwal_bom($conn = NULL, $id_jadwal = NULL, $active = NULL, $deleted = 'n', $tahap_filter = NULL, $status_filter = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("*");
		$this->datatables->from('vw_taksasi_data');

		if ($id_jadwal !== NULL) {
			$this->datatables->where('id_jadwal', $id_jadwal);
		}

		if ($tahap_filter != NULL) {
			if (is_string($tahap_filter)) $tahap_filter = explode(",", $tahap_filter);
			$this->datatables->where_in('id_tahap', $tahap_filter);
		}
		
		if ($status_filter != NULL) {
			if (is_string($status_filter)) $status_filter = explode(",", $status_filter);
			$this->datatables->where_in('main_status', $status_filter);
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_data"));
		return $this->general->jsonify($raw);
	}
	function get_data_jadwal_bom_old($conn = NULL, $id_jadwal = NULL, $active = NULL, $deleted = 'n', $tahap_filter = NULL, $status_filter = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("*");
		$this->datatables->from('vw_taksasi_data');

		if ($id_jadwal !== NULL) {
			$this->datatables->where('id_jadwal', $id_jadwal);
		}

		if ($tahap_filter != NULL) {
			if (is_string($tahap_filter)) $tahap_filter = explode(",", $tahap_filter);
			$this->datatables->where_in('id_tahap', $tahap_filter);
		}
		
		if ($status_filter != NULL) {
			if (is_string($status_filter)) $status_filter = explode(",", $status_filter);
			$this->datatables->where_in('main_status', $status_filter);
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_data"));
		return $this->general->jsonify($raw);
	}

    function get_data_penilaian_auto($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_taksasi_nilai.*');
        $this->db->select('tbl_taksasi_nilai.id_nilai as id');
		$this->db->select('tbl_taksasi_nilai.nama as nama_nilai');
        $this->db->from('tbl_taksasi_nilai');
        if (isset($param['not_in_nilai']) && $param['not_in_nilai'] !== NULL)
            $this->db->where_not_in('id_nilai', $param['not_in_nilai']);
        if (isset($param['search']) && $param['search'] !== NULL) {
			$search = strtoupper($param['search']);
            $this->db->group_start();
            $this->db->like('nama', $search, 'both');
            $this->db->group_end();
        }
        

        $query = $this->db->get();

        if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
            $result = $query->row();
        else $result = $query->result();

        $kolom = array();
        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $kolom = array_merge(
                $kolom,
                array_map(function ($val) {
                    return array(
                        "tipe" => "encrypt",
                        "nama" => $val,
                    );
                }, $param['encrypt'])
            );

        $result = $this->general->generate_json(
            array(
                "data" => $result,
                "kolom" => $kolom,
                "exclude" => $this->general->emptyconvert(@$param['exclude'])
            )
        );

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }


	function get_data_user_autocomplete($cari = NULL, $pra_syarat = NULL)
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
		if (($pra_syarat !== NULL)and($pra_syarat != 0)) {
			$this->db->where("tbl_karyawan.nik in(select tbl_taksasi_peserta.nik from tbl_taksasi_peserta where id_tahap='".$pra_syarat."') ");
		}
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_depo_autocomplete($cari = NULL)
	{
		$this->general->connectDbPortal();

		$this->db->select('tbl_depo_data.id_depo_master as id');
		$this->db->select('tbl_depo_data.*');
		$this->db->from('tbl_depo_data');
		if ($cari !== NULL) {
			$this->db->where("(tbl_depo_data.nama like '%" . $cari . "%' or tbl_depo_data.id_depo_master like '%" . $cari . "%') ");
		}
		$this->db->where('tbl_depo_data.na', 'n');
		$this->db->where('tbl_depo_data.del', 'n');
		$this->db->order_by('tbl_depo_data.nama', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}
	function get_data_depo($conn = NULL, $id_depo_master = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_data.id_depo_master as id');
		$this->db->select('tbl_depo_data.*');
		$this->db->from('tbl_depo_data');
		if ($id_depo_master !== NULL) {
			$this->db->where('tbl_depo_data.id_depo_master', $id_depo_master);
		}
		$this->db->where('tbl_depo_data.na', 'n');
		$this->db->where('tbl_depo_data.del', 'n');
		$this->db->order_by('tbl_depo_data.nama', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}


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
		$this->db->from('tbl_depo_evaluasi');
		$this->db->join('tbl_depo_data', 'tbl_depo_data.id_depo_master = tbl_depo_evaluasi.id_depo_master', 'left outer');
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
	
	function generate_data_evaluasi($conn = NULL, $id_depo_master = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("DateDiff (Month,(select top 1 ZDMPURBKR01.BEDAT from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_depo_data.pabrik and ZDMPURBKR01.NMDPO COLLATE SQL_Latin1_General_CP1_CI_AS =tbl_depo_data.nama order by ZDMPURBKR01.BEDAT ASC),(select top 1 ZDMPURBKR01.BEDAT from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_depo_data.pabrik and ZDMPURBKR01.NMDPO COLLATE SQL_Latin1_General_CP1_CI_AS =tbl_depo_data.nama order by ZDMPURBKR01.BEDAT DESC))as jumlah_bulan");
		$this->db->select("(select top 1 ZDMPURBKR01.BEDAT from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_depo_data.pabrik and ZDMPURBKR01.NMDPO COLLATE SQL_Latin1_General_CP1_CI_AS =tbl_depo_data.nama order by ZDMPURBKR01.BEDAT DESC) as tanggal_akhir_transaksi");
		$this->db->select("(select count(*) from tbl_depo_evaluasi where tbl_depo_evaluasi.id_depo_master=tbl_depo_data.id_depo_master and tbl_depo_evaluasi.status<=999 and tbl_depo_evaluasi.na='n') as evaluasi_pending");
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
	function get_data_aktual($conn = NULL, $pabrik = NULL, $nama = NULL, $tahun_ke = NULL, $bulan_ke = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_depo_data.pabrik");
		$this->db->select("tbl_depo_data.nama");
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
		$this->db->select("(select sum(ZDMPURBKR01.QTBSH) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='$nama' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke') as berat_basah");
		$this->db->select("(select sum(ZDMPURBKR01.QTFAK) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='$nama' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke') as berat_kering");
		$this->db->select("(select sum(AVCS4)/sum(ZDMPURBKR01.QTFAK+ZDMPURBKR01.QTBSH) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke') as harga_notarin");
		$this->db->select("(select sum(AVCS4)/sum(ZDMPURBKR01.QTFAK+ZDMPURBKR01.QTBSH) from [10.0.0.32].SAPSYNC.dbo.ZDMPURBKR01 ZDMPURBKR01 where ZDMPURBKR01.EKORG='$pabrik' and ZDMPURBKR01.NMDPO='$nama' and ZDMPURBKR01.GJAHR='$tahun_ke' and ZDMPURBKR01.MONAT='$bulan_ke') as harga_beli_depo");
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

        $query = $this->db->query("EXEC SP_DEPO_Generate_Number 'penutupan', '" . $param['jenis_depo'] . "', '" . $param['pabrik'] . "', '" . $param['year'] . "', '" . $param['month'] . "'");
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
	
	
	
}
