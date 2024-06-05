<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Folder Explorer
@author       : Matthew Jodi
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dsettingfolder extends CI_Model
{
	function cek_root_admin($conn = NULL, $id_folder = NULL, $nik = NULL, $all = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_folder_role.*');
		$this->db->from('tbl_folder_role');

		if ($id_folder != NULL) {
			$this->db->where('tbl_folder_role.id_folder', $id_folder);
		}

		if ($nik != NULL) {
			$this->db->where('tbl_folder_role.nik', $nik);
		}

		if ($all == NULL) {
			$this->db->where('tbl_folder_role.del', 'n');
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	function cek_folder_admins($conn = NULL, $id_folder = NULL, $nik = NULL, $all = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_folder_role.*');
		$this->db->from('tbl_folder_role');

		if ($id_folder != NULL) {
			$this->db->where('tbl_folder_role.id_folder', $id_folder);
		}

		if ($nik != NULL) {
			$this->db->where('tbl_folder_role.nik', $nik);
		}

		if ($all == NULL) {
			$this->db->where('tbl_folder_role.del', 'n');
		}

		$query 	= $this->db->get();
		$result	= $query->row();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	function get_folder_by_id($conn = NULL, $id_folder = NULL, $all = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_folder.*');

		$this->db->from('tbl_folder');

		$this->db->where('tbl_folder.id_folder', $id_folder);

		if ($all == NULL) {
			$this->db->where('tbl_folder.del', 'n');
			$this->db->where('tbl_folder.na', 'n');
		}

		$query 	= $this->db->get();
		$result	= $query->row();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	function get_grandparent($conn = NULL, $id_folder = NULL, $departemen = NULL, $id_level = NULL, $admin_folder = null, $nik = NULL, $id_grandparent = NULL, $nama_grandparent = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_folder.*');

		if ($id_grandparent != null) {
			$this->db->select("'$id_grandparent' as grandparent");
			$this->db->select("'$nama_grandparent' as nama_grandparent");
			$this->db->select("CASE 
						       	WHEN (SELECT tbl_folder.id_folder
						       			FROM tbl_folder
						       		   WHERE tbl_folder.id_folder = $id_grandparent
						       		   	 AND ISNULL(CHARINDEX('''$departemen''', ''''+REPLACE(tbl_folder.departemen_write,RTRIM(','),''',''')+''''),0) > 0
						       			 AND tbl_folder.na = 'n'
						       			 AND tbl_folder.del = 'n') IS NULL  
						       		THEN 'no'
						       	ELSE 'yes'
					           END as akses_write");

			$this->db->select("CASE 
						       	WHEN (SELECT tbl_folder.id_folder
						       			FROM tbl_folder
						       		   WHERE tbl_folder.id_folder = $id_grandparent
						       		   	 AND ISNULL(CHARINDEX('''$id_level''', ''''+REPLACE(tbl_folder.level_write,RTRIM(','),''',''')+''''),0) > 0
						       			 AND tbl_folder.na = 'n'
						       			 AND tbl_folder.del = 'n') IS NULL  
						       		THEN 'no'
						       	ELSE 'yes'
					           END as level");


			$departemen = NULL;
			$id_level = NULL;
		} else {

			if ($departemen != null) {
				//ambil akses parentnya
				$this->db->select("CASE 
							       	WHEN ISNULL(CHARINDEX('''$departemen''', ''''+REPLACE(tbl_folder.departemen_write,RTRIM(','),''',''')+''''),0) > 0  
							       		THEN 'yes'
							       	ELSE 'no'
							       END as akses_write");
			}

			if ($id_level != null) {
				//ambil akses parentnya
				$this->db->select("CASE 
							       	WHEN ISNULL(CHARINDEX('''$id_level''', ''''+REPLACE(tbl_folder.level_write,RTRIM(','),''',''')+''''),0) > 0  
							       		THEN 'yes'
							       	ELSE 'no'
							       END as level");
			}
		}



		if ($admin_folder != null && $nik != null) {
			$this->db->select("CASE 
							       	WHEN (SELECT tbl_folder_role.id_folder_role
							       			FROM tbl_folder_role
							       		   WHERE tbl_folder_role.id_folder = $admin_folder
							       			 AND tbl_folder_role.nik = $nik
							       			 AND tbl_folder_role.del = 'n') IS NULL  
							       		THEN 'no'
							       	ELSE 'yes'
						        END as is_admin");
		}

		$this->db->from('tbl_folder');

		$this->db->where('tbl_folder.id_folder', $id_folder);
		$this->db->where('tbl_folder.del', 'n');
		$this->db->where('tbl_folder.na', 'n');

		$query 	= $this->db->get();
		$result	= $query->row();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	function get_data_folder($conn = NULL, $id_folder = NULL, $all = NULL, $name = NULL, $divisi_read = null, $department_read = null, $admin_folder = null, $nik = NULL, $id_level = NULL, $folder_sign = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_folder.*');

		//BACA AKSES ADMIN AND WRITE
		if ($admin_folder != NULL) {
			$this->db->select("CASE 
							       	WHEN (SELECT tbl_folder_role.id_folder_role
							       			FROM tbl_folder_role
							       		   WHERE tbl_folder_role.id_folder = " . $admin_folder . "
							       			 AND tbl_folder_role.nik = " . $nik . "
							       			 AND tbl_folder_role.del = 'n') IS NULL  
							       		THEN 'no'
							       	ELSE 'yes'
							       END as isAdmin");
		} else {
			$this->db->select("CASE 
							       	WHEN tbl_folder_role.id_folder_role IS NULL  
							       		THEN 'no'
							       	ELSE 'yes'
							       END as isAdmin");
		}

		if ($divisi_read != NULL) {
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $divisi_read . "''', ''''+REPLACE(tbl_folder.divisi_write,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	ELSE 'no'
						       END as akses_divisi_write");
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $divisi_read . "''', ''''+REPLACE(tbl_folder.divisi_akses,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	WHEN " . $divisi_read . " = 0 then 'yes'
						       	ELSE 'no'
						       END as akses_divisi_read");
		}
		if ($department_read != NULL) {
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $department_read . "''', ''''+REPLACE(tbl_folder.departemen_write,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	ELSE 'no'
						       END as akses_department_write");
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $department_read . "''', ''''+REPLACE(tbl_folder.departemen_akses,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	WHEN " . $department_read . " = 0 then 'yes'
						       	ELSE 'no'
						       END as akses_department_read");
		}
		if ($id_level != NULL) {
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $id_level . "''', ''''+REPLACE(tbl_folder.level_write,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	ELSE 'no'
						       END as akses_level_write");
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $id_level . "''', ''''+REPLACE(tbl_folder.level_akses,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	WHEN " . $id_level . " = 0 then 'yes'
						       	ELSE 'no'
						       END as akses_level_read");
		}
		// END

		$this->db->from('tbl_folder');

		// BACA AKSES ISADMIN FOLDER ROOT
		if ($admin_folder == NULL) {
			$this->db->join("tbl_folder_role", "tbl_folder.id_folder = tbl_folder_role.id_folder AND tbl_folder_role.nik = " . $nik . " AND tbl_folder.del = tbl_folder_role.del", "left");
		}
		// END

		$this->db->where('tbl_folder.parent_folder', $id_folder);

		if ($folder_sign == 'sop') {
			$this->db->where('tbl_folder.id_folder', "134");
		} else if ($folder_sign == 'manual') {
			$this->db->where('tbl_folder.id_folder', "177");
		} else if ($folder_sign == 'knowledge') {
			$this->db->where('tbl_folder.id_folder', "326");
		} else {
			$this->db->where('tbl_folder.id_folder !=', "134");
			$this->db->where('tbl_folder.id_folder !=', "177");
			$this->db->where('tbl_folder.id_folder !=', "326");
		}

		if ($name != NULL) {
			$this->db->where('tbl_folder.nama', $name);
		}

		if ($all == NULL) {
			$this->db->where('tbl_folder.del', 'n');
			$this->db->where('tbl_folder.na', 'n');
		}

		// BACA FOLDER YANG MAU DITAMPILIN
		if ($admin_folder  != NULL) {
			$divisi_read     == NULL;
			$department_read == NULL;
			$id_level 		 == NULL;
		} else {
			// BUKAN ADMIN
			if ($divisi_read != NULL && $id_folder != 0 && base64_decode($this->session->userdata('-id_divisi-')) != '0') {
				$this->db->where("ISNULL(CHARINDEX('''" . $divisi_read . "''', ''''+REPLACE(tbl_folder.divisi_akses,RTRIM(','),''',''')+''''),0) >", "0");
			}

			if ($department_read != NULL  && $id_folder != 0 && base64_decode($this->session->userdata('-id_departemen-')) != '0') {
				$this->db->where("ISNULL(CHARINDEX('''" . $department_read . "''', ''''+REPLACE(tbl_folder.departemen_akses,RTRIM(','),''',''')+''''),0) >", "0");
			}
			if ($id_level != NULL  && $id_folder != 0 && base64_decode($this->session->userdata('-id_level-')) != '0') {
				$this->db->where("ISNULL(CHARINDEX('''" . $id_level . "''', ''''+REPLACE(tbl_folder.level_akses,RTRIM(','),''',''')+''''),0) >", "0");
			}
		}

		// END

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}


	function get_data_folder_action($conn = NULL, $id_folder = NULL, $all = NULL, $name = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_folder.*');
		$this->db->from('tbl_folder');
		$this->db->where('tbl_folder.parent_folder', $id_folder);

		if ($name != NULL) {
			$this->db->where('tbl_folder.nama', $name);
		}

		if ($all == NULL) {
			$this->db->where('tbl_folder.del', 'n');
			$this->db->where('tbl_folder.na', 'n');
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	function get_file_by_id($conn = NULL, $id_file = NULL, $all = NULL, $divisi = NULL, $dept = NULL, $level = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_file.*');

		//temp untuk validasi notifikasi & cek akses
		if ($divisi != NULL && $dept != NULL && $level != NULL) {
			$this->db->select("'parent_admin_akses' as parent_admin_akses");
			$this->db->select("'parent_div_read' as parent_div_read");
			$this->db->select("'parent_dept_read' as parent_dept_read");
			$this->db->select("'parent_level_read' as parent_level_read");
			$this->db->select("'parent_div_write' as parent_div_write");
			$this->db->select("'parent_dept_write' as parent_dept_write");
			$this->db->select("'parent_level_write' as parent_level_write");

			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $divisi . "''', ''''+REPLACE(tbl_file.divisi_akses,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	WHEN " . $divisi . " = 0 then 'yes'
						       	ELSE 'no'
						       END as file_div_read");

			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $dept . "''', ''''+REPLACE(tbl_file.departemen_akses,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	WHEN " . $dept . " = 0 then 'yes'
						       	ELSE 'no'
						       END as file_dept_read");

			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $level . "''', ''''+REPLACE(tbl_file.level_akses,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	WHEN " . $level . " = 0 then 'yes'
						       	ELSE 'no'
						       END as file_level_read");
		}
		//end

		$this->db->from('tbl_file');

		$this->db->where('tbl_file.id_file', $id_file);


		if ($all == NULL) {
			$this->db->where('tbl_file.del', 'n');
			$this->db->where('tbl_file.na', 'n');
		}

		$query 	= $this->db->get();
		$result	= $query->row();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	function get_file_by_name($conn = NULL, $id_folder = NULL, $all = NULL, $name = NULL, $deleted = NULL, $running_number = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_file.*');
		$this->db->from('tbl_file');

		if ($id_folder != NULL) {
			$this->db->where('tbl_file.id_folder', $id_folder);
		}

		if ($all == NULL) {
			$this->db->where('tbl_file.del', 'n');
			$this->db->where('tbl_file.na', 'n');
		}

		if ($deleted != NULL) {
			$this->db->where('tbl_file.del', 'y');
			$this->db->where('tbl_file.na', 'y');
		}

		if ($name != NULL) {
			$this->db->where('tbl_file.nama', $name);
		}

		if ($running_number != NULL) {
			$this->db->like('tbl_file.nama', $running_number);
		}

		$query 	= $this->db->get();
		$result	= $query->row();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	function get_data_file($conn = NULL, $id_folder = NULL, $all = NULL, $name = NULL, $divisi_read = null, $department_read = null, $admin_folder = null, $nik = NULL, $id_level = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_file.*');

		if ($admin_folder != NULL) {
			$this->db->select("CASE 
						       	WHEN (SELECT tbl_folder_role.id_folder_role
						       			FROM tbl_folder_role
						       		   WHERE tbl_folder_role.id_folder = " . $admin_folder . "
						       			 AND tbl_folder_role.nik = " . $nik . "
						       			 AND tbl_folder_role.del = 'n') IS NULL  
						       		THEN 'no'
						       	ELSE 'yes'
						       END as isAdmin");
		} else {
			$this->db->select("'no' as isAdmin");
		}

		if ($divisi_read != NULL) {
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $divisi_read . "''', ''''+REPLACE(tbl_file.divisi_write,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	ELSE 'no'
						       END as akses_divisi_write");
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $divisi_read . "''', ''''+REPLACE(tbl_file.divisi_akses,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	WHEN " . $divisi_read . " = 0 then 'yes'
						       	ELSE 'no'
						       END as akses_divisi_read");
		}
		if ($department_read != NULL) {
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $department_read . "''', ''''+REPLACE(tbl_file.departemen_write,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	ELSE 'no'
						       END as akses_department_write");
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $department_read . "''', ''''+REPLACE(tbl_file.departemen_akses,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	WHEN " . $department_read . " = 0 then 'yes'
						       	ELSE 'no'
						       END as akses_department_read");
		}
		if ($id_level != NULL) {
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $id_level . "''', ''''+REPLACE(tbl_file.level_write,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	ELSE 'no'
						       END as akses_level_write");
			$this->db->select("CASE 
						       	WHEN ISNULL(CHARINDEX('''" . $id_level . "''', ''''+REPLACE(tbl_file.level_akses,RTRIM(','),''',''')+''''),0) > 0  
						       		THEN 'yes'
						       	WHEN " . $id_level . " = 0 then 'yes'
						       	ELSE 'no'
						       END as akses_level_read");
		}
		// END

		$this->db->from('tbl_file');

		$this->db->where('tbl_file.id_folder', $id_folder);


		if ($all == NULL) {
			$this->db->where('tbl_file.del', 'n');
			$this->db->where('tbl_file.na', 'n');
		}

		if ($name != NULL) {
			$this->db->where('tbl_file.nama', $name);
		}

		if ($admin_folder != NULL) {
			$divisi_read     = NULL;
			$department_read = NULL;
			$id_level 		 = NULL;
		} else {
			// BUKAN ADMIN
			if ($divisi_read != NULL && base64_decode($this->session->userdata('-id_divisi-')) !== '0') {
				$this->db->where("ISNULL(CHARINDEX('''" . $divisi_read . "''', ''''+REPLACE(tbl_file.divisi_akses,RTRIM(','),''',''')+''''),0) >", "0");
			}

			if ($department_read != NULL && base64_decode($this->session->userdata('-id_departemen-')) !== '0') {
				$this->db->where("ISNULL(CHARINDEX('''" . $department_read . "''', ''''+REPLACE(tbl_file.departemen_akses,RTRIM(','),''',''')+''''),0) >", "0");
			}

			if ($id_level != NULL && base64_decode($this->session->userdata('-id_level-')) !== '0') {
				$this->db->where("ISNULL(CHARINDEX('''" . $id_level . "''', ''''+REPLACE(tbl_file.level_akses,RTRIM(','),''',''')+''''),0) >", "0");
			}
		}


		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	function get_data_file_action($conn = NULL, $id_folder = NULL, $all = NULL, $name = NULL, $deleted = NULL, $running_number = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_file.*');
		$this->db->from('tbl_file');

		if ($id_folder != NULL) {
			$this->db->where('tbl_file.id_folder', $id_folder);
		}
		
		if ($all == NULL) {
			$this->db->where('tbl_file.del', 'n');
			$this->db->where('tbl_file.na', 'n');
		}

		if ($deleted != NULL) {
			$this->db->where('tbl_file.del', 'y');
			$this->db->where('tbl_file.na', 'y');
		}

		if ($name != NULL) {
			$this->db->where('tbl_file.nama', $name);
		}

		if ($running_number != NULL) {
			$this->db->like('tbl_file.nama', $running_number);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	public function get_level($conn = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select("tbl_level.*");
		$this->db->from("tbl_level");
		$this->db->where("tbl_level.main", "n");
		$this->db->where("tbl_level.na", "n");
		$this->db->where("tbl_level.del", "n");
		$this->db->order_by("id_level");

		$query = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}

		return $result;
	}

	public function get_divisi($conn = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select("tbl_divisi.*");
		$this->db->from("tbl_divisi");
		$this->db->where("tbl_divisi.na", "n");
		$this->db->where("tbl_divisi.del", "n");
		$this->db->order_by("nama");

		$query = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	public function get_department($conn = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select("tbl_departemen.*");
		$this->db->from("tbl_departemen");
		$this->db->where("tbl_departemen.na", "n");
		$this->db->where("tbl_departemen.del", "n");
		$this->db->order_by("nama");

		$query = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}


	public function get_department_by_divisi($conn = NULL, $id_divisi = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$get_depart = "select distinct tbl_departemen.id_departemen, tbl_departemen.nama, tbl_departemen.gsber  
						 from tbl_user 
						 join tbl_divisi on tbl_divisi.id_divisi = dbo.tbl_user.id_divisi 
						 join tbl_departemen on tbl_departemen.id_departemen = tbl_user.id_departemen
						where ";

		// $ids = explode(",", $id_divisi);
		$first = true;
		foreach ($id_divisi as $id) {
			if ($first) {
				$get_depart .= "tbl_divisi.id_divisi = $id";
				$first = !$first;
			} else $get_depart .= " OR tbl_divisi.id_divisi = $id";
		}

		$get_depart .= ";";

		$query	= $this->db->query($get_depart);
		$result	= $query->result();


		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}

	function get_data_user($conn = NULL, $user = NULL, $nik = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_karyawan.nik as id');
		$this->db->select('tbl_user.*');
		$this->db->select('tbl_karyawan.*');
		$this->db->from('tbl_user');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan
										 AND tbl_karyawan.na = \'n\' 
										 AND tbl_karyawan.del = \'n\'', 'inner');
		if ($user != null) {
			$this->db->like('tbl_karyawan.nama', $user, 'after');
		}
		if ($nik != null) {
			$this->db->where_in('tbl_karyawan.nik', $nik);
		}
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');

		$this->db->order_by('tbl_karyawan.nama', 'ASC');

		$query = $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}


	function get_data_user_admin($conn = NULL, $folder = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('tbl_karyawan.nik as id');
		$this->db->select('tbl_karyawan.nik');
		$this->db->select('tbl_karyawan.nama');
		$this->db->select('tbl_karyawan.nama+\' - [\'+CONVERT(VARCHAR,tbl_karyawan.nik)+\']\' as text');

		$this->db->from('tbl_folder_role');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_folder_role.nik');

		if ($folder != NULL) {
			$this->db->where('tbl_folder_role.id_folder', $folder);
		}

		$this->db->where('tbl_folder_role.del', 'n');

		$this->db->order_by('tbl_karyawan.nama', 'ASC');

		$query = $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		return $result;
	}


	function get_akses_root_sop($conn = NULL, $id_folder = NULL, $id_level = NULL, $divisi = NULL, $department = NULL, $nik = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select("CASE 
						       	WHEN (SELECT tbl_folder_role.id_folder_role
						       			FROM tbl_folder_role
						       		   WHERE tbl_folder_role.id_folder = " . $id_folder . "
						       			 AND tbl_folder_role.nik = " . $nik . "
						       			 AND tbl_folder_role.del = 'n') IS NULL  
						       		THEN 'no'
						       	ELSE 'yes'
						       END as akses_admin");

		$this->db->select("CASE 
					       	WHEN ISNULL(CHARINDEX('''" . $divisi . "''', ''''+REPLACE(tbl_folder.divisi_write,RTRIM(','),''',''')+''''),0) > 0  
					       		THEN 'yes'
					       	ELSE 'no'
					       END as akses_divisi_write");

		$this->db->select("CASE 
					       	WHEN ISNULL(CHARINDEX('''" . $department . "''', ''''+REPLACE(tbl_folder.departemen_write,RTRIM(','),''',''')+''''),0) > 0  
					       		THEN 'yes'
					       	ELSE 'no'
					       END as akses_department_write");

		$this->db->select("CASE 
					       	WHEN ISNULL(CHARINDEX('''$id_level''', ''''+REPLACE(tbl_folder.level_write,RTRIM(','),''',''')+''''),0) > 0  
					       		THEN 'yes'
					       	ELSE 'no'
					       END as akses_level");

		$this->db->from('tbl_folder');

		$this->db->where('tbl_folder.id_folder', $id_folder);

		$query 	= $this->db->get();
		$result	= $query->row();

		if ($conn !== NULL)
			$this->general->closeDb();

		return $result;
	}

	function update_folder_path($conn = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$query 	= $this->db->query("EXEC SP_Kiranaku_Folder_Update_Path");
		// $result	= $query->row();

		if ($conn !== NULL) {
			$this->general->closeDb();
		}
		// return $result;	
	}

	function get_akses_notif($conn = NULL, $id_folder = NULL, $id_level = NULL, $divisi = NULL, $department = NULL, $nik = NULL, $id_root = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select("CASE 
						       	WHEN (SELECT tbl_folder_role.id_folder_role
						       			FROM tbl_folder_role
						       		   WHERE tbl_folder_role.id_folder = " . $id_root . "
						       			 AND tbl_folder_role.nik = " . $nik . "
						       			 AND tbl_folder_role.del = 'n') IS NULL  
						       		THEN 'no'
						       	ELSE 'yes'
						       END as akses_admin");

		$this->db->select("CASE 
					       	WHEN ISNULL(CHARINDEX('''" . $divisi . "''', ''''+REPLACE(tbl_folder.divisi_write,RTRIM(','),''',''')+''''),0) > 0  
					       		THEN 'yes'
					       	ELSE 'no'
					       END as akses_divisi_write");

		$this->db->select("CASE 
					       	WHEN ISNULL(CHARINDEX('''" . $divisi . "''', ''''+REPLACE(tbl_folder.divisi_akses,RTRIM(','),''',''')+''''),0) > 0  
					       		THEN 'yes'
					       	ELSE 'no'
					       END as akses_divisi_read");

		$this->db->select("CASE 
					       	WHEN ISNULL(CHARINDEX('''" . $department . "''', ''''+REPLACE(tbl_folder.departemen_write,RTRIM(','),''',''')+''''),0) > 0  
					       		THEN 'yes'
					       	ELSE 'no'
					       END as akses_department_write");

		$this->db->select("CASE 
					       	WHEN ISNULL(CHARINDEX('''" . $department . "''', ''''+REPLACE(tbl_folder.departemen_akses,RTRIM(','),''',''')+''''),0) > 0  
					       		THEN 'yes'
					       	ELSE 'no'
					       END as akses_department_read");

		$this->db->select("CASE 
					       	WHEN ISNULL(CHARINDEX('''$id_level''', ''''+REPLACE(tbl_folder.level_write,RTRIM(','),''',''')+''''),0) > 0  
					       		THEN 'yes'
					       	ELSE 'no'
					       END as akses_level_write");

		$this->db->select("CASE 
					       	WHEN ISNULL(CHARINDEX('''$id_level''', ''''+REPLACE(tbl_folder.level_akses,RTRIM(','),''',''')+''''),0) > 0  
					       		THEN 'yes'
					       	ELSE 'no'
					       END as akses_level_read");

		$this->db->from('tbl_folder');

		$this->db->where('tbl_folder.id_folder', $id_folder);

		$query 	= $this->db->get();
		$result	= $query->row();

		if ($conn !== NULL)
			$this->general->closeDb();

		return $result;
	}

	function get_total_file($conn = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$this->db->select('max(id_file) as total');
		$this->db->from('tbl_file');

		$query = $this->db->get();
		$result	= $query->row();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_log_access($conn = NULL, $id_file = NULL)
	{
		if ($conn !== NULL) {
			$this->general->connectDbPortal();
		}

		$string = "
			DECLARE @total_count INT

			SELECT @total_count = COUNT(id_user)
			FROM tbl_file_log_access";
		if ($id_file !== NULL)
			$string .= "
				WHERE tbl_file_log_access.id_file = '" . $id_file . "'";

		$string .= "	
			SELECT tbl_file_log_access.id_user,
				   tbl_karyawan.nama,
				   tbl_karyawan.nik,
				   COUNT(tbl_file_log_access.id_user) as total_view_user,
				   @total_count as total_count
			  FROM tbl_file_log_access
			  LEFT JOIN tbl_user ON tbl_user.id_user = tbl_file_log_access.id_user
			  LEFT JOIN tbl_karyawan ON tbl_karyawan.nik = tbl_user.id_karyawan";
		if ($id_file !== NULL)
			$string .= "
			 WHERE tbl_file_log_access.id_file = '" . $id_file . "'";
		$string .= "
			 GROUP BY tbl_file_log_access.id_user,
			 		  tbl_karyawan.nama,
			 		  tbl_karyawan.nik
		";
		$query	= $this->db->query($string);
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
}
