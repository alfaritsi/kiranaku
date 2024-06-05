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

class Drealisasidepo extends CI_Model
{


	function get_data_penutupan_sdm($conn = NULL, $nomor = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_penutupan_sdm.*');
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select("CONVERT(varchar, tbl_depo_penutupan_sdm.tanggal_rencana, 104) as tanggal_rencana_format");
		$this->db->from('tbl_depo_penutupan_sdm');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_depo_penutupan_sdm.nik', 'left outer');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_penutupan_sdm.nomor', $nomor);
		}
		$this->db->where('tbl_depo_penutupan_sdm.na', 'n');

		$this->db->order_by('tbl_depo_penutupan_sdm.nik ASC');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_penutupan_asset($conn = NULL, $nomor = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_penutupan_asset.*');
		$this->db->select('vw_depo_asset.nama as nama_asset');
		$this->db->select("CONVERT(varchar, tbl_depo_penutupan_asset.tanggal_rencana, 104) as tanggal_rencana_format");
		$this->db->from('tbl_depo_penutupan_asset');
		$this->db->join('vw_depo_asset', 'vw_depo_asset.kode = tbl_depo_penutupan_asset.kode', 'left outer');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_penutupan_asset.nomor', $nomor);
		}
		$this->db->where('tbl_depo_penutupan_asset.na', 'n');

		$this->db->order_by('tbl_depo_penutupan_asset.kode ASC');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_penutupan_keuangan($conn = NULL, $nomor = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_penutupan_keuangan.*');
		$this->db->select('tbl_depo_keuangan.nama');
		$this->db->select("CONVERT(varchar, tbl_depo_penutupan_keuangan.tanggal_rencana, 104) as tanggal_rencana_format");
		$this->db->from('tbl_depo_penutupan_keuangan');
		$this->db->join('tbl_depo_keuangan', 'tbl_depo_keuangan.id_keuangan = tbl_depo_penutupan_keuangan.id_keuangan', 'left outer');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_penutupan_keuangan.nomor', $nomor);
		}
		$this->db->where('tbl_depo_penutupan_keuangan.na', 'n');

		$this->db->order_by('tbl_depo_keuangan.nama ASC');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_penutupan_bokar($conn = NULL, $nomor = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_penutupan_bokar.*');
		$this->db->select("CONVERT(varchar, tbl_depo_penutupan_bokar.tanggal_rencana, 104) as tanggal_rencana_format");
		$this->db->from('tbl_depo_penutupan_bokar');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_penutupan_bokar.nomor', $nomor);
		}
		$this->db->where('tbl_depo_penutupan_bokar.na', 'n');

		$this->db->order_by('tbl_depo_penutupan_bokar.nama ASC');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_penutupan_lain($conn = NULL, $nomor = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_penutupan_lain.*');
		$this->db->select("CONVERT(varchar, tbl_depo_penutupan_lain.tanggal_rencana, 104) as tanggal_rencana_format");
		$this->db->from('tbl_depo_penutupan_lain');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_penutupan_lain.nomor', $nomor);
		}
		$this->db->where('tbl_depo_penutupan_lain.na', 'n');

		$this->db->order_by('tbl_depo_penutupan_lain.nama ASC');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_penutupan($conn = NULL, $nomor = NULL, $na = NULL, $del = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_depo_penutupan.nomor as id_data');
		$this->db->select('UPPER(tbl_depo_data.jenis_depo) as jenis_depo_format');
		$this->db->select('UPPER(tbl_depo_data.nama) as nama_depo');
		$this->db->select('tbl_depo_data.pabrik');
		$this->db->select("(select top 1 tbl_depo_user_role.id_role from tbl_depo_user_role where [user]='".base64_decode($this->session->userdata("-nik-"))."' and na='n') as level");
		$this->db->select('tbl_depo_penutupan.*');
		$this->db->from('tbl_depo_penutupan');
		$this->db->join('tbl_depo_data', 'tbl_depo_data.id_depo_master = tbl_depo_penutupan.id_depo_master', 'left outer');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_penutupan.nomor', $nomor);
		}
		if ($na !== NULL) {
			$this->db->where('tbl_depo_penutupan.na', $na);
		}
		if ($del !== NULL) {
			$this->db->where('tbl_depo_penutupan.del', $del);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	

	function get_data_master_sdm($conn = NULL, $jenis_biaya_detail) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_biaya.id_biaya as id');
		$this->db->select('tbl_depo_biaya.*');
		$this->db->from('tbl_depo_biaya');
		if ($jenis_biaya_detail !== NULL) {
			$this->db->where('tbl_depo_biaya.jenis_biaya_detail', $jenis_biaya_detail);
		}
		$this->db->order_by("tbl_depo_biaya.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

    function get_data_karyawan($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_karyawan.nik as id');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');
        $this->db->select('tbl_karyawan.*');
        $this->db->from('tbl_karyawan');

        if (isset($param['pabrik']) && $param['pabrik'] !== NULL)
            $this->db->where('gsber', $param['pabrik']);
        if (isset($param['not_in_nik']) && $param['not_in_nik'] !== NULL)
            $this->db->where_not_in('nik', $param['not_in_nik']);
        if (isset($param['search']) && $param['search'] !== NULL) {
			$search = strtoupper($param['search']);
            $this->db->group_start();
            $this->db->like('nama', $search, 'both');
            $this->db->or_like('nik', $search, 'both');
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
	
    function get_data_asset($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('kode as id');
        $this->db->select('vw_depo_asset.*');
        $this->db->from('vw_depo_asset');

        // if (isset($param['pabrik']) && $param['pabrik'] !== NULL)
            // $this->db->where('pabrik', $param['pabrik']);
        if (isset($param['not_in_asset']) && $param['not_in_asset'] !== NULL)
            $this->db->where_not_in('kode', $param['not_in_asset']);
        if (isset($param['search']) && $param['search'] !== NULL) {
			$search = strtoupper($param['search']);
            $this->db->group_start();
            $this->db->like('kode', $search, 'both');
            $this->db->or_like('nama', $search, 'both');
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

        $query = $this->db->query("EXEC SP_Kiranaku_Realisasi_Depo_Recipient_Email '" . $param['nomor'] . "'");
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

		$this->db->select("CONVERT(VARCHAR(10), tbl_depo_penutupan_log.tanggal_buat, 104) as tanggal_format");
		$this->db->select("CONVERT(VARCHAR(8), tbl_depo_penutupan_log.tanggal_buat, 108) as jam_format");
		$this->db->select("tbl_depo_role.nama as role_approval");
		$this->db->select('tbl_karyawan.nama as nama_approval');
		$this->db->select('tbl_depo_penutupan.nomor as nomor_specimen');
		$this->db->select('tbl_depo_penutupan_log.*');
		$this->db->select("CASE
								WHEN tbl_depo_penutupan_log.catatan is null THEN '-'
								ELSE tbl_depo_penutupan_log.catatan
						   END as label_catatan");

		$this->db->from('tbl_depo_penutupan_log');
		$this->db->join('tbl_depo_penutupan', 'tbl_depo_penutupan.nomor = tbl_depo_penutupan_log.nomor', 'left outer');
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_depo_penutupan_log.login_buat', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		$this->db->join('tbl_depo_user_role', 'tbl_depo_user_role.user = tbl_karyawan.id_karyawan', 'left outer');
		$this->db->join('tbl_depo_role', 'tbl_depo_role.id_role = tbl_depo_user_role.id_role', 'left outer');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_penutupan_log.nomor', $nomor);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_penutupan_log.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_penutupan_log.del', $deleted);
		}
		$this->db->where("tbl_depo_penutupan_log.realisasi='y'");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_realisasi_depo_bom($conn = NULL, $nomor = NULL, $active = NULL, $deleted = 'n', $pabrik_filter = NULL, $status_filter = NULL, $view_data = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$nik		= base64_decode($this->session->userdata("-nik-"));
		$posst		= base64_decode($this->session->userdata("-posst-"));
		$user_role	= $this->get_data_user_role("open", $nik, $posst);
		$level 		= $user_role[0]->level;
		$this->datatables->select(" 
									$level as level,
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
									status_realisasi,
									main_status,
									label_status,
									label_status_detail,
									login_buat,
									na,
									del");
		$this->datatables->from('vw_depo_realisasi');
		//filter_pabrik_by session login
		if (is_string($user_role[0]->pabrik)) $filter_pabrik = explode(",", $user_role[0]->pabrik);
		$this->datatables->where_in('pabrik', $filter_pabrik);
		if ($nomor !== NULL) {
			$this->datatables->where('nomor', $nomor);
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
			$this->datatables->where('status_realisasi', $level);
		}
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_data"));
		return $this->general->jsonify($raw);
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
