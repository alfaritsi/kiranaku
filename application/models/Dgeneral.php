<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
    @application    : Kiranaku v2
    @author 		: Akhmad Syaiful Yamang (8347)
    @contributor	:
                1. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                2. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                etc.
    */

class Dgeneral extends CI_Model
{

	function begin_transaction()
	{
		return $this->db->trans_begin();
	}

	function rollback_transaction()
	{
		return $this->db->trans_rollback();
	}

	function status_transaction()
	{
		return $this->db->trans_status();
	}

	function commit_transaction()
	{
		return $this->db->trans_commit();
	}

	/**
	 * Basic Column Generator
	 *
	 * @param string     $type tipe basic column yg akan digenerate
	 * @param array|null $data array data yang akan di merge, null bila ingin hanya menggenerate basi columns saja
	 *
	 * @return array|null
	 */
	function basic_column($type = "", $data = NULL)
	{
		if (!empty($type)) {
			$datetime = date("Y-m-d H:i:s");
			switch ($type) {
				case "insert":
					$basic_columns = array(
						'login_buat'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat' => $datetime,
						'na'           => 'n',
						'del'          => 'n'
					);
					break;
				case "insert_full":
					$basic_columns = array(
						'login_buat'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat' => $datetime,
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime,
						'na'           => 'n',
						'del'          => 'n'
					);
					break;
				case "insert_simple":
					$basic_columns = array(
						'login_buat'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat' => $datetime
					);
					break;
				case "update":
					$basic_columns = array(
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime
					);
					break;
				case "delete":
					$basic_columns = array(
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime,
						'del'          => 'y',
						'na'           => 'y'
					);
					break;
				case "activate":
					$basic_columns = array(
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime,
						'na'           => 'n'
					);
					break;
				case "activate_all":
					$basic_columns = array(
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime,
						'na'           => 'n',
						'del'           => 'n'
					);
					break;
				case "deactivate":
					$basic_columns = array(
						'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit' => $datetime,
						'na'           => 'y'
					);
					break;
				default:
					$basic_columns = array();
					break;
			}

			if (isset($data) && !empty($data)) {
				return array_merge($data, $basic_columns);
			} else {
				return $basic_columns;
			}
		} else {
			return NULL;
		}
	}

	function insert($tabel, $data)
	{
		return $this->db->insert($tabel, $data);
	}

	function insert_batch($tabel, $data)
	{
		return $this->db->insert_batch($tabel, $data);
	}

	function update($tabel, $data, $key)
	{
		if ($key) {
			foreach ($key as $k) {
				$this->db->where($k['kolom'], $k['value']);
			}
		}
		return $this->db->update($tabel, $data);
	}

	function delete($tabel, $key = NULL, $customWhere = NULL)
	{
		if ($key) {
			foreach ($key as $k) {
				$this->db->where($k['kolom'], $k['value']);
			}
		} else {
			$this->db->where(1, 1);
		}

		if ($customWhere) {
			$this->db->where($customWhere);
		}

		return $this->db->delete($tabel);
	}

	function close()
	{
		return $this->db->close();
	}

	function get_data_session($id_user)
	{
		$this->general->connectDbPortal();

		$this->db->select('tbl_user.*');
		$this->db->select('tbl_karyawan.*');
		$this->db->from('tbl_user');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan 
										 AND tbl_karyawan.na = \'n\' 
										 AND tbl_karyawan.del = \'n\'', 'inner');
		$this->db->where('tbl_user.id_user', $id_user);
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$query  = $this->db->get();
		$result = $query->row();

		$this->general->closeDb();
		return $result;
	}

	function get_data_user($user = NULL, $email = NULL, $isPosisi = NULL, $nik = NULL, $jabatan_in = NULL, $level_in = NULL, $posisi_in = NULL, $pabrik_in = NULL)
	{
		$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.nik as id');
		$this->db->select('tbl_user.*');
		$this->db->select('tbl_karyawan.*');
		$this->db->from('tbl_user');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan
										 AND tbl_karyawan.na = \'n\' 
										 AND tbl_karyawan.del = \'n\'', 'inner');
		if ($isPosisi !== NULL) {
			$this->db->join('tbl_posisi', 'tbl_posisi.nama = tbl_karyawan.posst 
										 AND tbl_posisi.na = \'n\'', 'inner');
			if ($posisi_in != NULL) {
				$this->db->where_in('tbl_posisi.id_posisi', $posisi_in);
			}
		}
		if ($user !== NULL) {
			$this->db->like('tbl_karyawan.nama', $user, 'after');
		}
		if ($email !== NULL) {
			$this->db->where('tbl_karyawan.email', $email);
		}
		if ($nik != NULL) {
			$this->db->where('tbl_karyawan.nik', $nik);
		}
		if ($jabatan_in != NULL) {
			$this->db->where_in('tbl_user.id_jabatan', $jabatan_in);
		}
		if ($level_in != NULL) {
			$this->db->where_in('tbl_user.id_level', $level_in);
		}
		if ($pabrik_in != NULL) {
			$this->db->where_in('tbl_karyawan.gsber', $pabrik_in);
		}
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_menu($menu = NULL, $id_menu = NULL, $id_parent = NULL, $nik = NULL, $link = NULL)
	{
		$this->general->connectDbPortal();

		$this->db->select('tbl_menu.id_menu, tbl_menu.id_parent, tbl_menu.nik_akses, tbl_menu.nama, tbl_menu.url, 
			tbl_menu.url_external, tbl_menu.kelas, tbl_menu.urutan, tbl_menu.na, tbl_menu.target, tbl_menu.notification_categories, tbl_menu.id_level');
		$this->db->from('tbl_menu');
		if ($menu !== NULL && count($menu) > 0) {
			$this->db->where_in('tbl_menu.nama', $menu);
		}
		if ($id_menu !== NULL) {
			$this->db->where('tbl_menu.id_menu', $id_menu);
		}
		if ($id_parent !== NULL) {
			$this->db->where('tbl_menu.id_parent', $id_parent);
		}
		if ($link !== NULL) {
			$this->db->where('tbl_menu.url_external', $link);
		}
		if ($nik !== NULL) {
			$this->db->where('CHARINDEX(\'\'\'\'+CONVERT(varchar(10), \'' . $nik . '\')+\'\'\'\',\'\'\'\'+REPLACE(tbl_menu.nik_akses, RTRIM(\'.\'),\'\'\',\'\'\')+\'\'\'\') > 0');
		}
		$this->db->where('tbl_menu.na', 'n');
		$this->db->where('tbl_menu.del', 'n');
		$this->db->order_by('tbl_menu.urutan');
		$query = $this->db->get();

		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_file($filename = NULL)
	{
		$this->general->connectDbPortal();

		$this->db->select('tbl_pi_file.*');
		$this->db->from('tbl_pi_file');
		if ($filename != NULL) {
			$this->db->where('tbl_pi_file.filename =', $filename);
		}
		$this->db->where('tbl_pi_file.na', 'n');
		$this->db->where('tbl_pi_file.del', 'n');
		$query  = $this->db->get();
		$result = $query->row();

		$this->general->closeDb();
		return $result;
	}

	function generate_id($table, $key, $index, $zero, $connection = NULL)
	{
		//REMARK APLIKASI MANAJEMEN ASSET
		// $this->db->select('MAX(' . $key . ')+1 as id');
		if ($connection == NULL)
			$this->general->connectDbPortal();

		$this->db->select("RIGHT('" . $zero . "'+CAST((MAX(" . $key . ")+1) as VARCHAR(" . $index . "))," . $index . ") as number");
		$this->db->from($table);
		$query  = $this->db->get();
		$result = $query->row();

		$this->general->closeDb();
		return $result;
	}

	function get_master_region()
	{
		$this->general->connectDbPortal();

		$this->db->select(array('region_code', 'region_name'));
		$this->db->from('tbl_wf_region');
		$this->db->where('na', 'n');
		$this->db->where('del', 'n');
		$this->db->group_by(array('region_code', 'region_name'));
		$this->db->order_by('region_name', 'ASC');
		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	/**
	 * @param array $plant_in
	 * @param bool  $as_array
	 *
	 * @return mixed
	 */
	function get_master_plant($plant_in = NULL, $as_array = false, $plant_not_in = NULL, $tipe = NULL)
	{
		$this->general->connectDbPortal();

		if($tipe='ERP'){
			$this->db->select('CONVERT(INT, ZDMMSPLANT.PERSA) as id_pabrik,
                              ZDMMSPLANT.PERSA as plant_code,
                              ZDMMSPLANT.WERKS as plant,
                              ZDMMSPLANT.NAME1 as plant_name,
                              ZDMMSPLANT.NAME1 as nama');
			$this->db->from('SAPSYNC.dbo.ZDMMSPLANT');
			if ($plant_in != NULL) {
				$this->db->where_in('ZDMMSPLANT.WERKS', $plant_in);
			}
			if ($plant_not_in != NULL) {
				$this->db->where_not_in('ZDMMSPLANT.WERKS', $plant_not_in);
			}
			$this->db->order_by('SAPSYNC.dbo.ZDMMSPLANT.WERKS ASC');
		}else{
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
	function get_master_msplant($plant_in = NULL, $as_array = false, $plant_not_in = NULL){
		$this->general->connectDbPortal();
		
		$string = "SELECT CONVERT(INT, ZDMMSPLANT.PERSA) as id_pabrik,
						  ZDMMSPLANT.PERSA as plant_code,
						  ZDMMSPLANT.WERKS as plant,
						  ZDMMSPLANT.NAME1 as plant_name,
						  ZDMMSPLANT.NAME1 as nama,
						  tbl_wf_region.region_name
					 FROM SAPSYNC.dbo.ZDMMSPLANT
					 LEFT JOIN tbl_wf_region ON ZDMMSPLANT.PERSA COLLATE SQL_Latin1_General_CP1_CI_AS = tbl_wf_region.plant_code
											AND tbl_wf_region.na = 'n' 
											AND tbl_wf_region.del = 'n'
					WHERE 1=1 ";
		
		if ($plant_in != NULL) {
			$plant = "'".join("','", $plant_in)."'";
			$string .= " AND ZDMMSPLANT.WERKS IN (".$plant.")";
		}    
		
		if ($plant_not_in != NULL) {
			$plant = "'".join("','", $plant_not_in)."'";
			$string .= " AND ZDMMSPLANT.WERKS NOT IN (".$plant.")";
		}
		
		$string .= " GROUP BY ZDMMSPLANT.PERSA,
							  ZDMMSPLANT.PERSA,
							  ZDMMSPLANT.WERKS,
							  ZDMMSPLANT.NAME1,
							  tbl_wf_region.region_name";
		
		$string .= " ORDER BY ZDMMSPLANT.NAME1";

		$query = $this->db->query($string);
		if ($as_array)
			$result = $query->result_array();
		else
			$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_user_login($usernik = NULL, $password = NULL)
	{
		$this->general->connectDbPortal();

		$this->db->select('tbl_user.*');
		$this->db->select('tbl_karyawan.*');
		// $this->db->select('tbl_level.*');
		$this->db->from('tbl_user');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan
										 AND tbl_karyawan.na = \'n\' 
										 AND tbl_karyawan.del = \'n\'', 'inner');
		$this->db->join('tbl_level', 'tbl_level.id_level = tbl_user.id_level
										 AND tbl_level.na = \'n\' 
										 AND tbl_level.del = \'n\'', 'left');
		if ($usernik !== NULL) {
			$this->db->where('tbl_karyawan.nik', $usernik);
		}
		if ($password !== NULL) {
			$this->db->where('tbl_user.pass', MD5($password));
		}
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$query = $this->db->get();
		if ($usernik !== NULL) {
			$result = $query->row();
		} else {
			$result = $query->result();
		}

		$this->general->closeDb();
		return $result;
	}

	function get_data_provinsi($id_prov = NULL, $nama = NULL, $all = NULL)
	{
		$this->general->connectDbSdo();

		$this->db->select('tb_provinsi.*');
		$this->db->from('tb_provinsi');
		if ($id_prov !== NULL) {
			$this->db->where('tb_provinsi.id', $id_prov);
		}
		if ($nama !== NULL) {
			$this->db->like('tb_provinsi.nama_provinsi', $nama, 'both');
		}
		if ($all == NULL) {
			$this->db->where('tb_provinsi.aktif', 1);
		}
		$this->db->order_by('tb_provinsi.nama_provinsi ASC');

		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_kabupaten($id_kab = NULL, $nama = NULL, $prov = NULL, $prov_in = array(), $all = NULL)
	{
		$this->general->connectDbSdo();

		$this->db->select('tb_kabupaten.*');
		$this->db->from('tb_kabupaten');
		if ($id_kab !== NULL) {
			$this->db->where('tb_kabupaten.id', $id_kab);
		}
		if ($nama !== NULL) {
			$this->db->like('tb_kabupaten.nama_kab', $nama, 'both');
		}
		if ($prov !== NULL) {
			$this->db->where('tb_kabupaten.id_provinsi', $prov);
		}
		if (count($prov_in) > 0) {
			$this->db->where_in('tb_kabupaten.id_provinsi', $prov_in);
		}
		if ($all == NULL) {
			$this->db->where('tb_kabupaten.aktif', 1);
		}
		$this->db->order_by('tb_kabupaten.nama_kab ASC');

		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_depo($id_depo = NULL, $nama = NULL, $plant = NULL, $all = NULL, $jns_depo = NULL)
	{
		$this->general->connectDbDefault();

		$this->db->select('ZKISSTT_0113.*');
		$this->db->from('ZKISSTT_0113');
		if ($id_depo !== NULL) {
			$this->db->where('ZKISSTT_0113.DEPID', $id_depo);
		}
		if ($nama !== NULL) {
			$this->db->like('ZKISSTT_0113.DEPNM', $nama, 'both');
		}
		if ($plant !== NULL) {
			$this->db->where('ZKISSTT_0113.EKORG', $plant);
		}
		if ($all !== NULL) {
			$this->db->where('ZKISSTT_0113.ACTIV', $all);
		}
		if ($jns_depo !== NULL) {
			$this->db->where('ZKISSTT_0113.JNSDP', $jns_depo);
		}
		$this->db->order_by('ZKISSTT_0113.EKORG, ZKISSTT_0113.DEPID ASC');

		$query  = $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_schedule_master($params = array())
	{

		$all = isset($params['all']) ? $params['all'] : false;
		$id = isset($params['id']) ? $params['id'] : null;
		$script = isset($params['script']) ? $params['script'] : null;

		$this->db->select('*');
		$this->db->from('tbl_running_master');

		if (!$all) {
			$this->db->where('tbl_running_master.na', 'n');
			$this->db->where('tbl_running_master.del', 'n');
		}

		if (isset($script))
			$this->db->where('script', $script);

		if (isset($id))
			$this->db->where('id', $id);

		$query = $this->db->get();

		if (isset($params['single_row']) && $params['single_row'])
			$result = $query->row();
		else
			$result = $query->result();

		return $result;
	}

	function get_schedule_running($params = array())
	{
		$id = isset($params['id']) ? $params['id'] : null;
		$script = isset($params['script']) ? $params['script'] : null;
		$rfc = isset($params['rfc']) ? $params['rfc'] : null;
		$source = isset($params['source']) ? $params['source'] : null;
		$destination = isset($params['destination']) ? $params['destination'] : null;
		$tanggal = isset($params['tanggal']) ? $params['tanggal'] : null;

		$this->db->select('*');
		$this->db->from('tbl_running_schedule');

		if (isset($rfc))
			$this->db->where('rfc', $rfc);

		if (isset($script))
			$this->db->where('script', $script);

		if (isset($id))
			$this->db->where('id', $id);

		if (isset($destination))
			$this->db->where('destination', $destination);

		if (isset($source))
			$this->db->where('source', $source);

		if (isset($tanggal))
			$this->db->where('tanggal', $tanggal);

		$query = $this->db->get();

		if (isset($params['single_row']) && $params['single_row'])
			$result = $query->row();
		else
			$result = $query->result();

		return $result;
	}

	function get_data_recipient_email($param = array())
	{
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbDefault();

		$this->db->select('tbl_recipient_email.*,
						   tbl_karyawan.email,
						   tbl_karyawan.nama');
		$this->db->from('tbl_recipient_email');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_recipient_email.nik
											 AND tbl_karyawan.na = \'n\'
											 AND tbl_karyawan.del = \'n\'');

		if (isset($param['app']) && $param['app'] !== NULL)
			$this->db->where('tbl_recipient_email.app', $param['app']);

		$this->db->where('tbl_recipient_email.na = \'n\'');
		$this->db->where('tbl_recipient_email.del = \'n\'');
		$query  = $this->db->get();
		$result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}

	function get_data_karyawan($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_karyawan.nik as id');
        $this->db->select('tbl_karyawan.*');
        $this->db->from('tbl_karyawan');

        if (isset($param['nik']) && $param['nik'] !== NULL)
            $this->db->where('tbl_karyawan.nik', $param['nik']);
        if (isset($param['nama']) && $param['nama'] !== NULL)
            $this->db->like('tbl_karyawan.nama', $param['nama'], 'both');
        if (isset($param['search']) && $param['search'] !== NULL) {
            $this->db->like('tbl_karyawan.nik', $param['search'], 'both');
            $this->db->or_like('tbl_karyawan.nama', $param['search'], 'both');
        }

        $this->db->where('tbl_karyawan.na', 'n');
        $this->db->where('tbl_karyawan.del', 'n');

        $query = $this->db->get();
        if (isset($param['nik']) && $param['nik'] !== NULL)
            $result = $query->row();
        else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_posisi($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select("tbl_posisi.nama as id");
        $this->db->select("tbl_posisi.nama");
        $this->db->select("(SELECT COUNT(nik)
                              FROM tbl_karyawan
                             WHERE tbl_karyawan.posst = tbl_posisi.nama
                               AND tbl_karyawan.na = 'n'
                               AND tbl_karyawan.del = 'n') as jml_karyawan");
        $this->db->select("CAST(
                            (SELECT DISTINCT '['+CONVERT(VARCHAR,tbl_karyawan.nik)+'] - '+tbl_karyawan.nama +RTRIM(';')
                               FROM tbl_karyawan
                              WHERE tbl_karyawan.posst = tbl_posisi.nama
                                AND tbl_karyawan.na = 'n'
                                AND tbl_karyawan.del = 'n'
                                FOR XML PATH ('')
                            ) as VARCHAR(MAX)
                          )  AS karyawan_list");
        $this->db->from('tbl_posisi');

        if (isset($param['search']) && $param['search'] !== NULL) {
            $this->db->like('tbl_posisi.nama', $param['search'], 'both');
        }

        $query = $this->db->get();
        if (isset($param['nik']) && $param['nik'] !== NULL)
            $result = $query->row();
        else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));


        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
}
