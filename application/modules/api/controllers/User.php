<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application  : Kiranaku v2
    @author     : Akhmad Syaiful Yamang (8347)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */

class User extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "API";
        $this->load->helper('download');
        $this->load->helper('date');
        ini_set('memory_limit', '2000M');
        ini_set('max_execution_time', 14000);
        date_default_timezone_set("Asia/Jakarta");

		$this->load->model('dgeneral');
		$this->load->model('dhome');

        $this->load->model('spot/dtransaksipol', 'dtransaksipol');
    }

    public function index_get()
    {
        $json = array(
            "message" => "Portal Internal API"
        );

        return $this->response($json, 200);
    }

    public function index_post()
    {
        $json = array(
            "message" => "Portal Internal API"
        );

        return $this->response($json, 200);
    }
	public function get_menu_post($name = NULL) {
		$check_update     = $this->dhome->get_last_modified_menu(base64_decode($this->session->userdata("-nik-")),1);
		$menu_sess        = $this->session->userdata('kirana_menu');
		$menu_last_update = $this->session->userdata('kirana_menu_last_update');

		//			$menu_opened = json_decode($this->session->userdata('kirana_menu_opened'));
		//
		//			if ($menu_opened == NULL) {        //via login
		//				$menu_opened = array();
		//			}
		if (($menu_sess == NULL && $menu_last_update == NULL) || (isset($check_update) && strtotime($this->generate->regenerateDateTimeFormat($menu_last_update)) < strtotime($this->generate->regenerateDateTimeFormat($check_update->tanggal_edit)))) {
		// if (($menu_sess == NULL && $menu_last_update == NULL) || (isset($check_update) && $menu_last_update < $check_update->tanggal_edit)) { // || (!in_array($name, $menu_opened) && $name !== NULL)) {
			//				if ($name !== NULL) {
			//					array_push($menu_opened, $name);
			//				}
			//				$menu    = $this->general->get_menu($menu_opened);
			$menu    = $this->general->get_menu(NULL, 1);
			$newdata = array(
				'kirana_menu'             => $menu,
				'kirana_menu_last_update' => date('Y-m-d H:i:s'),
				//					'kirana_menu_opened'      => json_encode($menu_opened)
			);
			$this->session->set_userdata($newdata);
		}
		else {
			$menu = $menu_sess;
		}

		if ($name === NULL) {
			echo json_encode($menu);
		}
		else {
			return count($menu);
		}
	}

	function get_data_session_get($id_user) {
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
		
		return $this->response($result, 200);
		// return $result;
	}
	
	function get_data_menu_get($nik = NULL) {
		$this->general->connectDbPortal();

		$this->db->select('tbl_menu.id_menu, tbl_menu.id_parent, tbl_menu.nik_akses, tbl_menu.nama, tbl_menu.url, 
		tbl_menu.url_external, tbl_menu.kelas, tbl_menu.urutan, tbl_menu.na, tbl_menu.target, tbl_menu.notification_categories, tbl_menu.id_level');
		$this->db->from('tbl_menu');
		if ($nik !== NULL) {
			$this->db->where('CHARINDEX(\'\'\'\'+CONVERT(varchar(10), \'' . $nik . '\')+\'\'\'\',\'\'\'\'+REPLACE(tbl_menu.nik_akses, RTRIM(\'.\'),\'\'\',\'\'\')+\'\'\'\') > 0');
		}
		$this->db->where('tbl_menu.id_parent', 0);
		$this->db->where('tbl_menu.na', 'n');
		$this->db->where('tbl_menu.del', 'n');
		$this->db->where('tbl_menu.dmz', '1');
		$this->db->order_by('tbl_menu.urutan');
		$query = $this->db->get();

		$result = $query->result();

		$this->general->closeDb();
		return $this->response($result, 200);
		// return $result;
	}
    public function login_post()
    {
			$nik  = isset($_POST['username']) ? $_POST['username'] : NULL;
			$pass = isset($_POST['password']) ? $_POST['password'] : NULL;

			$data = $this->dgeneral->get_user_login($nik, $pass);

			$link = NULL;
			if(isset($_POST['ref']))
				$link = $_POST['ref'];

			if ($data) {
				$prod_server = json_decode(KIRANA_SERVER);

				if (array_search($_SERVER['SERVER_NAME'], $prod_server, true) === false) {
					$server = "dev";
				}
				else {
					$server = "prod";
				}

				$session = array(
					"-id_user-"          => base64_encode($data->id_user),
					"-id_ceo-"           => base64_encode($data->id_ceo),
					"-id_direktorat-"    => base64_encode($data->id_direktorat),
					"-id_divisi-"        => base64_encode($data->id_divisi),
					"-id_departemen-"    => base64_encode($data->id_departemen),
					"-id_level-"         => base64_encode($data->id_level),
					"-id_jabatan-"       => base64_encode($data->id_jabatan),
					"-id_golongan-"      => base64_encode($data->id_golongan),
					"-id_karyawan-"      => base64_encode($data->id_karyawan),
					"-nik-"              => base64_encode($data->nik),
					"-nama-"             => base64_encode($data->nama),
					"-ho-"               => base64_encode($data->ho),
					"-id_gedung-"        => base64_encode($data->id_gedung),
					"-status-"           => base64_encode($data->status),
					"-cr_level_id-"      => base64_encode($data->cr_level_id),
					"-persa-"            => base64_encode($data->persa),
					"-wf_level_id-"      => base64_encode($data->wf_level_id),
					"-gsber-"            => base64_encode($data->gsber),
					"-gem_level_id-"     => base64_encode($data->gem_level_id),
					"-leg_level_id-"     => base64_encode($data->leg_level_id),
					"-posst-"            => base64_encode($data->posst),
					"-id_seksi-"         => base64_encode($data->id_seksi),
					"-identity_session-" => base64_encode($server)
				);

				$this->session->set_userdata($session);
				$date     = date("Y-m-d");
				$datetime = date("Y-m-d H:i:s");
				$time     = date("H:i");

				$data_log = $this->dhome->get_log_user(base64_decode($this->session->userdata("-id_user-")));
				if (!$data_log) {
					$data_row = array(
						"id_user"   => base64_decode($this->session->userdata("-id_user-")),
						"tanggal"   => $date,
						"jam_login" => $time,
						"na"        => 'n',
						"del"       => 'n'
					);
					$this->dgeneral->insert("tbl_userlog", $data_row);
				}
				if (PASSWORD_EXPIRED_MODE)
					if (isset($data->tanggal_pass_update)) {
						// $next_pass_update = date('Y-m-d',strtotime('+1 month',strtotime($data->tanggal_pass_update)));
						$next_pass_update = date('Y-m-d', strtotime('+' . PASSWORD_EXPIRED_MONTH . ' month', strtotime($data->tanggal_pass_update)));
						if ($next_pass_update <= date('Y-m-d', time()))
							$link = 'home/expired/password/?key=' . $this->generate->kirana_encrypt($_POST['username']);
					}
					else {
						$link = 'home/expired/password/?key=' . $this->generate->kirana_encrypt($_POST['username']);
					}

				if ($data->pass_update !== 'y') {
					$link = 'home/reset/password/?key=' . $this->generate->kirana_encrypt($_POST['username']);
				}

				$sts = "OK";
			}
			else {
				$sts = "NotOK";
			}
			$return = array('sts' => $sts, 'link' => $link);
			echo json_encode($return);

		
    }
}
?>
