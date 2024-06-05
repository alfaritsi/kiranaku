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

class Dtransaksidepo extends CI_Model
{
  
	function get_data_provinsi($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tb_provinsi.id as id_provinsi');
		$this->db->select('tb_provinsi.nama_provinsi');
		$this->db->from('sdo_dev.dbo.tb_provinsi');
		
		$this->db->order_by("tb_provinsi.nama_provinsi", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_kabupaten($conn = NULL, $id_provinsi = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tb_kabupaten.id as id_kabupaten');
		$this->db->select('tb_kabupaten.nama_kab as nama_kabupaten');
		$this->db->from('sdo_dev.dbo.tb_kabupaten');
		
		if ($id_provinsi !== NULL) {
			$this->db->where('tb_kabupaten.id_provinsi', $id_provinsi);
		}
		
		$this->db->order_by("tb_kabupaten.nama_kab", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_cek_depo($conn = NULL, $id_depo_master = NULL, $kode_sj = NULL, $pabrik = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('vw_depo_master.*');
		$this->db->from('vw_depo_master');
		if ($id_depo_master !== NULL) {
			$this->db->where('vw_depo_master.DEPID', $id_depo_master);
		}
		if ($kode_sj !== NULL) {
			$this->db->where('vw_depo_master.SJCOD', $kode_sj);
		}
		if ($pabrik !== NULL) {
			$this->db->where('vw_depo_master.EKORG', $pabrik);
		}
		
		$query  = $this->db->get();
		$result = $query->result();
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_history($conn = NULL, $nomor = NULL, $active = NULL, $deleted = 'n')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("CONVERT(VARCHAR(10), tbl_depo_data_log.tanggal_buat, 104) as tanggal_format");
		$this->db->select("CONVERT(VARCHAR(8), tbl_depo_data_log.tanggal_buat, 108) as jam_format");
		$this->db->select("tbl_depo_role.nama as role_approval");
		$this->db->select('tbl_karyawan.nama as nama_approval');
		$this->db->select('tbl_depo_data.nomor as nomor_specimen');
		$this->db->select('tbl_depo_data_log.*');
		$this->db->select("CASE
								WHEN tbl_depo_data_log.catatan is null THEN '-'
								ELSE tbl_depo_data_log.catatan
						   END as label_catatan");

		$this->db->from('tbl_depo_data_log');
		$this->db->join('tbl_depo_data', 'tbl_depo_data.nomor = tbl_depo_data_log.nomor', 'left outer');
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_depo_data_log.login_buat', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		$this->db->join('tbl_depo_user_role', 'tbl_depo_user_role.user = tbl_karyawan.id_karyawan', 'left outer');
		$this->db->join('tbl_depo_role', 'tbl_depo_role.id_role = tbl_depo_user_role.id_role', 'left outer');
		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_data_log.nomor', $nomor);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_data_log.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_data_log.del', $deleted);
		}
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

        $query = $this->db->query("EXEC SP_Kiranaku_Depo_Recipient_Email '" . $param['nomor'] . "'");
        $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

	function get_data_user_role_status($conn = NULL, $status)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select("tbl_depo_role.*");
		$this->db->from('tbl_depo_role');
		if ($status !== NULL) {
			$this->db->where('tbl_depo_role.level', $status);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_nomor($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $query = $this->db->query("EXEC SP_DEPO_Generate_Number 'pembukaan', '" . $param['jenis_depo'] . "', '" . $param['pabrik'] . "', '" . $param['year'] . "', '" . $param['month'] . "'");
        $result = $query->row();

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
	
    function get_data_master_depo($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

            $this->db->select('vw_depo_master.*');

        $this->db->from('vw_depo_master');

        // if (isset($param['pabrik']) && $param['pabrik'] !== NULL)
            // $this->db->where('pabrik', $param['pabrik']);
        if (isset($param['not_in_depo']) && $param['not_in_depo'] !== NULL)
            $this->db->where_not_in('id', $param['not_in_depo']);
        if (isset($param['search']) && $param['search'] !== NULL) {
			$search = strtoupper($param['search']);
            $this->db->group_start();
            $this->db->like('nama_depo', $search, 'both');
            $this->db->or_like('id', $search, 'both');
            $this->db->group_end();
        }
        
        $this->db->where("ACTIV='X'");

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
	
    function get_data_biaya($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_depo_biaya.id_biaya as id');
        $this->db->select('tbl_depo_biaya.nama as nama_biaya');
        $this->db->select('tbl_depo_biaya.*');

        $this->db->from('tbl_depo_biaya');

        if (isset($param['jenis_depo']) && $param['jenis_depo'] !== NULL)
            $this->db->where('jenis_depo', $param['jenis_depo']);
        if (isset($param['jenis_biaya']) && $param['jenis_biaya'] !== NULL)
            $this->db->where('jenis_biaya', $param['jenis_biaya']);
        if (isset($param['jenis_biaya_detail']) && $param['jenis_biaya_detail'] !== NULL)
            $this->db->where('jenis_biaya_detail', $param['jenis_biaya_detail']);
        // if (isset($param['not_in_biaya']) && $param['not_in_biaya'] !== NULL)
            // $this->db->where_not_in('id', $param['not_in_biaya']);
        if (isset($param['search']) && $param['search'] !== NULL) {
			$search = strtoupper($param['search']);
            $this->db->group_start();
            $this->db->like('nama', $search, 'both');
            $this->db->or_like('id_biaya', $search, 'both');
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
	
    function get_data_depo_xx($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_depo_data.id_data as id');
        $this->db->select('tbl_depo_data.*');

        $this->db->from('tbl_depo_data');

        if (isset($param['nomor']) && $param['nomor'] !== NULL)
            $this->db->where('nomor', $param['nomor']);
        
		$query  = $this->db->get();
		$result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
	function get_data_depo($conn = NULL, $nomor = NULL, $na = NULL, $del = NULL, $id_depo_master = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		
		$this->db->select('tbl_depo_data.nomor as id_data');
		$this->db->select("(select top 1 tbl_depo_user_role.id_role from tbl_depo_user_role where [user]='".base64_decode($this->session->userdata("-nik-"))."' and na='n') as level");
		$this->db->select('tbl_depo_data.*');
		$this->db->select('tb_provinsi.nama_provinsi as nama_propinsi');
		$this->db->select('tb_kabupaten.nama_kab as nama_kabupaten');
		$this->db->from('tbl_depo_data');
		$this->db->join('sdo_dev.dbo.tb_provinsi', 'tb_provinsi.id=tbl_depo_data.propinsi', 'left outer');
		$this->db->join('sdo_dev.dbo.tb_kabupaten', 'tb_kabupaten.id=tbl_depo_data.kabupaten', 'left outer');
		
		if ($nomor !== NULL) {
			$this->db->where('nomor', $nomor);
		}
		if ($na !== NULL) {
			$this->db->where('na', $na);
		}
		if ($del !== NULL) {
			$this->db->where('del', $del);
		}
		if ($id_depo_master !== NULL) {
			$this->db->where('id_depo_master', $id_depo_master);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}	
	
	function get_data_depo_bom($conn = NULL, $nomor = NULL, $active = NULL, $deleted = 'n', $jenis_depo_filter = NULL, $pabrik_filter = NULL, $status_filter = NULL, $view_data = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$nik		= base64_decode($this->session->userdata("-nik-"));
		$posst		= base64_decode($this->session->userdata("-posst-"));
		$user_role	= $this->get_data_user_role("open", $nik, $posst);
		$level 		= $user_role[0]->level;
		$this->datatables->select(" 
									nama_role,
									id_data,
									jenis_depo,
									jenis_depo_format,
									nama,
									pabrik,
									nomor,
									nomor_format,
									tanggal_format,
									nama,
									status,
									main_status,
									label_status,
									label_status_detail,
									login_buat,
									na,
									del");
		$this->datatables->from('vw_depo_data');
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
	
	function get_data_biaya_depo($conn = NULL, $id_data = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_biaya.nama as nama_biaya');
		$this->db->select('tbl_depo_biaya.id_biaya as id');
		$this->db->select('tbl_depo_data_biaya_depo.*');
		$this->db->from('tbl_depo_data_biaya_depo');
		$this->db->join('tbl_depo_biaya', 'tbl_depo_biaya.id_biaya = tbl_depo_data_biaya_depo.id_biaya', 'left outer');

		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_data_biaya_depo.nomor', $nomor);
		}
		$this->db->where("tbl_depo_data_biaya_depo.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_biaya_detail($conn = NULL, $id_data = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_biaya.nama as nama_biaya');
		$this->db->select('tbl_depo_biaya.id_biaya as id');
		$this->db->select('tbl_depo_biaya.jenis_depo');
		$this->db->select('tbl_depo_biaya.jenis_biaya');
		$this->db->select('tbl_depo_biaya.jenis_biaya_detail');
		$this->db->select("CASE
								WHEN tbl_depo_biaya.jenis_biaya='operational' and tbl_depo_biaya.jenis_biaya_detail='transaksi' THEN ISNULL((select top 1 tbl_depo_data_biaya_depo.total from tbl_depo_data_biaya_depo where tbl_depo_data_biaya_depo.id_biaya=tbl_depo_biaya.id_biaya and tbl_depo_data_biaya_depo.nomor='$nomor' and tbl_depo_data_biaya_depo.na='n'), 0 )
								WHEN tbl_depo_biaya.jenis_biaya='operational' and tbl_depo_biaya.jenis_biaya_detail='sdm' THEN ISNULL((select top 1 (tbl_depo_data_biaya_sdm.gaji_pokok+tbl_depo_data_biaya_sdm.tunjangan) from tbl_depo_data_biaya_sdm where tbl_depo_data_biaya_sdm.id_biaya=tbl_depo_biaya.id_biaya and tbl_depo_data_biaya_sdm.nomor='$nomor' and tbl_depo_data_biaya_sdm.na='n'), 0 )
								WHEN tbl_depo_biaya.jenis_biaya='investasi' THEN ISNULL((select top 1 tbl_depo_data_biaya_investasi.total from tbl_depo_data_biaya_investasi where tbl_depo_data_biaya_investasi.id_biaya=tbl_depo_biaya.id_biaya and tbl_depo_data_biaya_investasi.nomor='$nomor' and tbl_depo_data_biaya_investasi.na='n'), 0 )
								ELSE 0
						   END as biaya");
		$this->db->from('tbl_depo_biaya');
		$this->db->where("tbl_depo_biaya.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_biaya_investasi($conn = NULL, $id_data = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_biaya.nama as nama_biaya');
		$this->db->select('tbl_depo_biaya.id_biaya as id');
		$this->db->select('tbl_depo_data_biaya_investasi.*');
		$this->db->from('tbl_depo_data_biaya_investasi');
		$this->db->join('tbl_depo_biaya', 'tbl_depo_biaya.id_biaya = tbl_depo_data_biaya_investasi.id_biaya', 'left outer');

		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_data_biaya_investasi.nomor', $nomor);
		}
		$this->db->where("tbl_depo_data_biaya_investasi.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_biaya_sdm($conn = NULL, $id_data = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_biaya.nama as nama_biaya');
		$this->db->select('tbl_depo_biaya.id_biaya as id');
		$this->db->select('tbl_depo_data_biaya_sdm.*');
		$this->db->from('tbl_depo_data_biaya_sdm');
		$this->db->join('tbl_depo_biaya', 'tbl_depo_biaya.id_biaya = tbl_depo_data_biaya_sdm.id_biaya', 'left outer');

		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_data_biaya_sdm.nomor', $nomor);
		}
		$this->db->where("tbl_depo_data_biaya_sdm.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_biaya_trans($conn = NULL, $id_data = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_data_biaya_trans.*');
		$this->db->from('tbl_depo_data_biaya_trans');

		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_data_biaya_trans.nomor', $nomor);
		}
		$this->db->where("tbl_depo_data_biaya_trans.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_desa($conn = NULL, $id_data = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_data_desa.*');
		$this->db->from('tbl_depo_data_desa');

		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_data_desa.nomor', $nomor);
		}
		$this->db->where("tbl_depo_data_desa.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_dokumen($conn = NULL, $id_data = NULL, $nomor = NULL, $jenis_depo = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_dokumen.id_dokumen');
		$this->db->select('tbl_depo_dokumen.nama as nama_dokumen');
		$this->db->select('tbl_depo_dokumen.mandatory');
		$this->db->select("(select top 1 tbl_depo_data_dokumen.url from tbl_depo_data_dokumen where tbl_depo_data_dokumen.id_dokumen=tbl_depo_dokumen.id_dokumen and tbl_depo_data_dokumen.nomor='$nomor' and tbl_depo_data_dokumen.na='n') as url");
		$this->db->from('tbl_depo_dokumen');

		$this->db->where("tbl_depo_dokumen.na='n'");
		if ($jenis_depo !== NULL) {
			$this->db->where("(tbl_depo_dokumen.jenis_depo = '$jenis_depo' or tbl_depo_dokumen.jenis_depo='all')");
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_gambar($conn = NULL, $id_data = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select("tbl_depo_gambar.nama as nama_gambar");
		$this->db->select("'$nomor' as nomor");
		$this->db->select("tbl_depo_gambar.id_gambar");
		$this->db->select("(select top 1 tbl_depo_data_gambar.nama from tbl_depo_data_gambar where tbl_depo_data_gambar.id_gambar=tbl_depo_gambar.id_gambar and tbl_depo_data_gambar.nomor='$nomor' and tbl_depo_data_gambar.na='n') as nama");
		$this->db->select("(select top 1 tbl_depo_data_gambar.url from tbl_depo_data_gambar where tbl_depo_data_gambar.id_gambar=tbl_depo_gambar.id_gambar and tbl_depo_data_gambar.nomor='$nomor' and tbl_depo_data_gambar.na='n') as url");
		$this->db->select("(select top 1 tbl_depo_data_gambar.tipe from tbl_depo_data_gambar where tbl_depo_data_gambar.id_gambar=tbl_depo_gambar.id_gambar and tbl_depo_data_gambar.nomor='$nomor' and tbl_depo_data_gambar.na='n') as tipe");
		$this->db->from('tbl_depo_gambar');
		$this->db->where('tbl_depo_gambar.na', 'n');
		$this->db->order_by("tbl_depo_gambar.nama", "asc");

		// $this->db->select("'$id_data' as id_data");
		// $this->db->select('tbl_depo_gambar.nama as nama_gambar');
		// $this->db->select('tbl_depo_data_gambar.*');
		// $this->db->join('tbl_depo_gambar', 'tbl_depo_gambar.id_gambar = tbl_depo_data_gambar.id_gambar', 'inner outer');
		// $this->db->from('tbl_depo_data_gambar');

		// if ($nomor !== NULL) {
			// $this->db->where('tbl_depo_data_gambar.nomor', $nomor);
		// }
		// $this->db->where("tbl_depo_data_gambar.na='n'");
		// $this->db->order_by('tbl_depo_data_gambar.id_gambar');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_lokasi($conn = NULL, $id_data = NULL, $nomor = NULL, $pabrik = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_lokasi.nama as nama_lokasi');
		//$this->db->select('vw_depo_master.nama_depo');
		$this->db->select("(select top 1 vw_depo_master.nama_depo from vw_depo_master where vw_depo_master.id COLLATE SQL_Latin1_General_CP1_CI_AS = tbl_depo_data_lokasi.id_depo) as nama_depo");
		$this->db->select('tbl_depo_data_lokasi.*');
		$this->db->from('tbl_depo_data_lokasi');
		$this->db->join('tbl_depo_lokasi', 'tbl_depo_lokasi.id_lokasi = tbl_depo_data_lokasi.id_lokasi', 'left outer');
		// $this->db->join('vw_depo_master', "(vw_depo_master.id COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_depo_data_lokasi.id_depo and vw_depo_master.pabrik='$pabrik'", 'left outer');
		// $this->db->join('vw_depo_master', "(vw_depo_master.id COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_depo_data_lokasi.id_depo", 'left outer');

		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_data_lokasi.nomor', $nomor);
		}
		$this->db->where("tbl_depo_data_lokasi.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_survei($conn = NULL, $id_data = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_data_survei.*');
		$this->db->from('tbl_depo_data_survei');

		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_data_survei.nomor', $nomor);
		}
		$this->db->where("tbl_depo_data_survei.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_target($conn = NULL, $id_data = NULL, $nomor = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("'$id_data' as id_data");
		$this->db->select('tbl_depo_data_target.*');
		$this->db->from('tbl_depo_data_target');

		if ($nomor !== NULL) {
			$this->db->where('tbl_depo_data_target.nomor', $nomor);
		}
		$this->db->where("tbl_depo_data_target.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	
	
}
