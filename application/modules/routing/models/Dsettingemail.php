<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application    : Email Routing
    @author 		: Matthew Jodi
    @contributor	:
                1. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                2. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                etc.
    */

	class Dsettingemail extends CI_Model {
		public  $mainTable    = "tbl_ac_roles";
		public  $mainPK       = "id_role";
		private $portal_db    = "";
		private $dashboard_db = "";

		public function __construct() {
			parent::__construct();
			$this->portal_db    = DB_PORTAL;
			$this->dashboard_db = DB_DEFAULT;

		}

		function get_data_user(
			$id_user = NULL, $id_unit = null, $all = NULL
		) {
			$this->general->connectDbPortal();
			$filter = "";

			if (isset($id_unit))
				$filter .= "AND tk.id_gedung = '$id_unit'";

			if (isset($id_user))
				$filter .= "AND tu.id_user= $id_user";

			$string = "            
            SELECT 
            tu.id_user,
            CASE WHEN tk.id_gedung='ho' 
              THEN 'HO' 
            ELSE 
              CASE WHEN tk.id_gedung=''
                THEN null
              ELSE tk.id_gedung 
              END
            END as \"business_unit\",
            tk.nik,
            tk.nama,
            tk.posst as jabatan,
            tk.email,
            tl.nama as status,
            CASE WHEN tk.ho = 'y' THEN 'HO' ELSE 'PABRIK' END as tipe_karyawan,
            (
              SELECT TOP 1 CAST(tanggal as varchar(max))+' '+tul.jam_login as jam_login
              FROM tbl_userlog tul 
              WHERE tul.id_user = tu.id_user 
              ORDER BY tul.tanggal DESC, tul.jam_login DESC
            ) as last_login,
            tu.na,
            tu.del,
            tk.ho,     
            tauc.companies,
            tauc.buyers
            FROM tbl_user tu 
            INNER JOIN tbl_karyawan tk
            ON tu.id_karyawan = tk.id_karyawan AND tk.del = 'n' AND tk.na = 'n'
            INNER JOIN tbl_level tl
            ON tu.id_level = tl.id_level AND tl.del = 'n' AND tl.na = 'n'
            LEFT JOIN tbl_ac_users_companies tauc 
            ON tu.id_user = tauc.id_user
            WHERE  tu.del = 'n' AND tu.na = 'n' 
            AND tk.id_gedung is not null
            AND tk.id_gedung <> ''
            $filter
        ";

			$query  = $this->db->query($string);
			$result = $query->result();
			$this->general->closeDb();

			return $result;
		}

		function get_data_role(
			$id_role = NULL, $all = NULL
		) {

			$this->general->connectDbPortal();
			$filter = "";


			if ($id_role != NULL) {
				$filter .= " AND roles.id_role = " . $id_role;
			}

			if ($all == NULL) {
				$filter .= " AND roles.del = 'n'";
			}

			$string = "
                SELECT 
                 *,                 
                  RTRIM(
                    SUBSTRING(
                      menusD
                    ,2,LEN(menusD))
                  ) AS menus,
                  RTRIM(
                    SUBSTRING(
                      topicsD
                    ,2,LEN(topicsD))
                  ) AS topics,
                  RTRIM(
                    SUBSTRING(
                      jabatansD
                    ,2,LEN(jabatansD))
                  ) AS jabatans,
                  RTRIM(
                    SUBSTRING(
                      divisisD
                    ,2,LEN(divisisD))
                  ) AS divisis,
                  RTRIM(
                    SUBSTRING(
                      departemensD
                    ,2,LEN(departemensD))
                  ) AS departemens
                 FROM (SELECT
                  *,
                  CASE
                    WHEN roles.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
                    ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
                  END as label_active,
                  CAST(
                    (select ','+convert(varchar,e.id_topic)
                    from [$this->portal_db].dbo.tbl_ac_roles_topics e
                    where e.id_role = roles.id_role
                    for xml path('')) as varchar(max)
                  ) as topicsD,                  
                  CAST(
                    (select ','+CONVERT(varchar,e.id_jabatan)
                    from [$this->portal_db].dbo.tbl_ac_roles_jabatans e
                    where e.id_role = roles.id_role
                    for xml path('')) as varchar(max)
                  ) as jabatansD,                    
                  CAST(
                    (select ','+CONVERT(varchar,e.id_divisi)
                    from [$this->portal_db].dbo.tbl_ac_roles_divisis e
                    where e.id_role = roles.id_role
                    for xml path('')) as varchar(max)
                  ) as divisisD,                       
                  CAST(
                    (select ','+CONVERT(varchar,e.id_departemen)
                    from [$this->portal_db].dbo.tbl_ac_roles_departemens e
                    where e.id_role = roles.id_role
                    for xml path('')) as varchar(max)
                  ) as departemensD,                  
                  CAST(
                    (select ','+CONVERT(varchar,e.id_menu)
                    from [$this->portal_db].dbo.tbl_ac_roles_menus e
                    where e.id_role = roles.id_role
                    for xml path('')) as varchar(max)
                  ) as menusD,
                  (
                    SELECT nama from tbl_user user_add 
                    INNER JOIN tbl_karyawan karyawan_add ON user_add.id_karyawan = karyawan_add.id_karyawan
                    WHERE roles.login_buat = user_add.id_user
                  ) AS login_buat_nama,
                  (
                    SELECT nama from tbl_user 
                    INNER JOIN tbl_karyawan ON tbl_user.id_karyawan = tbl_karyawan.id_karyawan
                    WHERE roles.login_edit = tbl_user.id_user
                  ) AS login_edit_nama
                FROM
                [$this->portal_db].dbo.tbl_ac_roles roles
                
                WHERE roles.na = 'n' $filter ) as dataS
                ";

			$string .= " ORDER BY dataS.id_role ASC";

			$query  = $this->db->query($string);
			$result = $query->result();
			$this->general->closeDb();

			return $result;
		}

		function save_role($data) {

		}

		function save_user($data) {

		}

		function get_kiranalytics_menu(
			$id = NULL, $all = NULL
		) {
			$this->general->connectDbDefault();

			//        $string = "
			//                SELECT
			//                  sm.id_submenu,
			//                  sm.submenu,
			//                  sm.id_menu,
			//                  m.menu
			//                FROM
			//                  dbo.tb_submenu sm
			//                  INNER JOIN dbo.tb_menu m ON sm.id_menu = m.id_menu
			//                WHERE m.is_active= 1 AND sm.is_active = 1
			//                ";

			$string = "
                SELECT
                  node.id_menu,
                  node.parent_id,
                  ISNULL(parent.nama_menu,'Root Menu') as parent_menu,
                  node.nama_menu as menu                  
                FROM
                  dbo.tb_menu_baru node
                  LEFT OUTER JOIN dbo.tb_menu_baru parent ON node.parent_id = parent.id_menu
                   AND parent.is_active = 1            
                WHERE node.is_active= 1
                ";

			$query  = $this->db->query($string);
			$result = $query->result();

			return $result;
		}

		function get_jabatan(
			$id = NULL, $all = NULL
		) {
			$this->general->connectDbPortal();

			$string = "
                SELECT
                  *                
                FROM
                  dbo.tbl_jabatan j                                    
                WHERE j.na = 'n' AND id_jabatan <> 1
                ";

			$query  = $this->db->query($string);
			$result = $query->result();

			return $result;
		}

		function get_divisi(
			$id = NULL, $all = false
		) {
			$this->general->connectDbPortal();

			$string = "
                SELECT
                  *                
                FROM
                  dbo.tbl_divisi                                   
                WHERE tbl_divisi.na = 'n' AND tbl_divisi.del = 'n'
                ";

			$query  = $this->db->query($string);
			$result = $query->result();

			return $result;
		}

		function get_departemen(
			$id = NULL, $all = false
		) {
			$this->general->connectDbPortal();

			$string = "
                SELECT
                  tbl_departemen.*, tbl_wf_master_plant.plant_name                
                FROM
                  dbo.tbl_departemen   
                INNER JOIN
                  dbo.tbl_wf_master_plant ON tbl_departemen.gsber = tbl_wf_master_plant.plant                         
                WHERE tbl_departemen.na = 'n' AND tbl_departemen.del = 'n'
                ";

			$query  = $this->db->query($string);
			$result = $query->result();

			return $result;
		}

		function get_company(
			$id_company = NULL, $all = NULL
		) {
			$this->general->connectDbPortal();

			$string = "
                SELECT
                  *                
                FROM
                  dbo.tbl_wf_master_plant j                                    
                WHERE j.na = 'n'
                ";

			$query  = $this->db->query($string);
			$result = $query->result();

			return $result;
		}

		function get_buyer(
			$id_buyer = NULL, $all = NULL
		) {
			$this->general->connectDbDefault();

			$string = "
                SELECT
                  j.KUNNR as id,
                  j.NAME1 as name
                FROM
                  dbo.ZDMMSCUSTMR j
                  ORDER BY name
                ";

			$query  = $this->db->query($string);
			$result = $query->result();

			return $result;
		}

		public function delete_data($id) {

		}

		public function count_user_companies($id = null) {

			$this->general->connectDbPortal();

			$num = $this->db->query("select * from tbl_ac_users_companies where id_user = ?", array($id))
							->num_rows();

			$this->general->closeDb();

			return $num;
		}

		function get_menu_kiranalytics($parent = NULL, $id_menu=NULL, $all = NULL) {
			$this->general->connectDbDefault();
      if(empty($parent)){
        $parent = "NULL";
      }
      if(empty($id_menu)){
        $id_menu = "NULL";
        $id_menu_encrypt = "NULL";
      }else{
        $id_menu_encrypt = $this->generate->kirana_encrypt($id_menu);
      }
      if(empty($all)){
        $all = "NULL";
      }

			$string	= "EXEC SP_MENU_KIRANALYTICS2 ".$parent.", ".$id_menu.", ".$all.", ".$id_menu_encrypt.""; 

			$query 	= $this->db->query($string);
			$result	= $query->result();

			$this->general->closeDb();
			return $result;
		}

	}


?>
