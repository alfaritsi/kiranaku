<?php
/*
@application  : 
@author       : Akhmad Syaiful Yamang (8347)
@date         : 21-Dec-18
@contributor  :
1. <insert your fullname> (<insert your nik>) <insert the date>
<insert what you have modified>
2. <insert your fullname> (<insert your nik>) <insert the date>
<insert what you have modified>
etc.
*/

class BaseControllers extends MX_Controller
{
	protected $data, $base_url_pi;

	public function __construct()
	{
		parent::__construct();

		/*load model*/
		$this->load->model('dgeneral');
		$this->load->model('dordernusira');

		/*load global attribute*/
		$this->base_url_pi      = "http://10.0.0.105/uat/k-air/";
		$this->data['generate'] = $this->generate;
		$this->data['module']   = $this->router->fetch_module();
		$this->data['user']     = $this->general->get_data_user();

		/*generate session k-air*/
		$user_id = $this->session->userdata("-id_user-");
		if (!empty($user_id)) {
			$data_ses   = $this->dordernusira->get_data_session("open", base64_decode($user_id));
			if ($data_ses) {
				//kode_role
				$kd = $this->dordernusira->get_arr_kode_role($data_ses->nik)->kode_role;
				$data_ses->kode_role = explode(",", rtrim($kd, ','));

				//nama_role & level
				$kode_roles = explode(",", rtrim($kd, ','));
				$data_baru = $this->dordernusira->get_datas_role($kode_roles);
				$arr1 = array();
				$arr2 = array();
				foreach ($data_baru as $dt) {
					$arr1[] = base64_encode($dt->nama_role);
					$arr2[] = base64_encode($dt->level);
				}

				$data_ses->nama_role = $arr1;
				$data_ses->level = $arr2;

				//menu
				$menu_baru = $this->dordernusira->get_datas_menu($kode_roles);
				$temp = "";
				foreach ($menu_baru as $dt) {
					if ($temp == "") {
						$temp = $dt->id_menus;
					} else {
						$temp = "{$temp},{$dt->id_menus}";
					}
				}
				$temp = array_unique(explode(',', $temp), SORT_NUMERIC);
				$data_ses->id_menus = implode(',', $temp);

				// plant
				$new_plant = $this->dordernusira->get_datas_plant(base64_decode($this->session->userdata('-nik-')));
				if (count($new_plant) > 1) {
					$arr3 = array();
					$arr4 = array();
					foreach ($new_plant as $dt) {
						$arr3[] = $dt->kode_pabrik;
						$arr4[] = $dt->nama;
					}

					$data_ses->pabrik_list_kode = implode(',', $arr3);
					$data_ses->pabrik_list = implode(',', $arr4);
				} else {
					$data_ses->pabrik_list = $new_plant[0]->nama;
					$data_ses->pabrik_list_kode = $new_plant[0]->kode_pabrik;
				}

				$session_pi = array(
					"-pi_nama-"       => base64_encode($data_ses->nama),
					"-pi_role-"       => $data_ses->kode_role,
					"-pi_nama_role-"  => $data_ses->nama_role,
					"-pi_level-"      => $data_ses->level,
					//						"-pi_approve-"    => base64_encode($data_ses->if_approve),
					//						"-pi_assign-"     => base64_encode($data_ses->if_assign),
					//						"-pi_decline-"    => base64_encode($data_ses->if_decline),
					//						"-pi_is_dual-"    => base64_encode($data_ses->dual_option_decline),
					//						"-pi_drop-"       => base64_encode($data_ses->if_drop),
					"-pi_menu-"       => base64_encode($data_ses->id_menus),
					"-pi_plant-"      => rtrim($data_ses->pabrik_list, ','),
					"-pi_plant_code-" => rtrim($data_ses->pabrik_list_kode, ','),
				);
				$this->session->set_userdata($session_pi);
			}
		}

		$this->load->library('sap');
	}

	public function index()
	{
		show_404();
	}

	public function get_master_plant($plant_in = NULL, $as_array = false, $plant_not_in = NULL, $return = NULL)
	{
		$plant = $this->dgeneral->get_master_plant($plant_in, $as_array, $plant_not_in);
		if (isset($_POST['tipe']) && $_POST['tipe'] == "array" || $return == "array") {
			return $plant;
		} else {
			echo json_encode($plant);
		}
	}

	public function get_master_depo($id_depo = NULL, $nama = NULL, $plant = NULL, $all = NULL, $return = NULL)
	{
		$depo = $this->dgeneral->get_data_depo($id_depo, $nama, $plant, $all);
		$depo = $this->general->generate_encrypt_json($depo, array("DEPID"));
		if (isset($_POST['tipe']) && $_POST['tipe'] == "array" || $return == "array") {
			return $depo;
		} else {
			echo json_encode($depo);
		}
	}

	public function get_user_autocomplete()
	{
		$this->general->get_user_autocomplete();
	}

	public function pagination($object = NULL, $config = NULL)
	{
		if ($object == NULL) {
			return false;
		}
		// load Pagination library
		$this->load->library('pagination');

		// init params
		$params         = array();
		$limit_per_page = $object->limit;
		$start_index    = $object->start;
		$total_records  = $object->total_records;

		if ($total_records > 0) {
			$config['base_url']      = base_url() . $object->link;
			$config['total_rows']    = $total_records;
			$config['per_page']      = $limit_per_page;
			$config["uri_segment"]   = $object->segment;
			$config["num_tag_open"]  = '<li>';
			$config["num_tag_close"] = '</li>';

			$config['first_link']      = 'First Page';
			$config['first_tag_open']  = '<li class="firstlink">';
			$config['first_tag_close'] = '</li>';

			$config['last_link']      = 'Last Page';
			$config['last_tag_open']  = '<li class="lastlink">';
			$config['last_tag_close'] = '</li>';

			$config['next_link']      = 'Next Page';
			$config['next_tag_open']  = '<li class="nextlink">';
			$config['next_tag_close'] = '</li>';

			$config['prev_link']      = 'Prev Page';
			$config['prev_tag_open']  = '<li class="prevlink">';
			$config['prev_tag_close'] = '</li>';

			$config['cur_tag_open']  = '<li class="active"><a href="javascript:void(0)">';
			$config['cur_tag_close'] = '</li>';

			$this->pagination->initialize($config);

			// build paging links
			$params["links"] = $this->pagination->create_links();
			$params["limit"] = $limit_per_page;
			$params["start"] = $start_index;
		}

		return $params;
	}

	public function send_data($link, $data = NULL)
	{
		if ($link == NULL || $link == "")
			show_404();

		/*generate session k-air*/
		$user_id = $this->session->userdata("-id_user-");
		if (!empty($user_id)) {
			$session = $this->set_session_param();

			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($curl_handle, CURLOPT_URL, $this->base_url_pi . 'home/validation?redirect=' . base64_encode($link) . '&key=' . base64_encode(json_encode($session)));
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_handle, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl_handle, CURLOPT_FAILONERROR, true);
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 20);
			curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt($curl_handle, CURLOPT_TIMEOUT, 20);
			curl_setopt($curl_handle, CURLOPT_AUTOREFERER, true);
			curl_setopt($curl_handle, CURLOPT_COOKIEFILE, "");
			curl_setopt($curl_handle, CURLOPT_VERBOSE, true);
			if ($data)
				curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode($data));
			$res = curl_exec($curl_handle);
			if (curl_error($curl_handle)) {
				$res = curl_error($curl_handle);
			}
			$httpcode = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
			curl_close($curl_handle);

			return $res;
		}
	}

	protected function connectSAP($user)
	{
		$koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);
		$conn    = (empty($koneksi[$user]) ? NULL : $koneksi[$user]);
		if ($conn) {
			$constr = array(
				"logindata"   => array(
					"ASHOST" => $conn['ASHOST'],    // application server
					"SYSNR"  => $conn['SYSNR'],        // system number
					"CLIENT" => $conn['CLIENT'],    // client
					"USER"   => $conn['USER'],        // user
					"PASSWD" => $conn['PASSWD']        // password
				),
				"show_errors" => $conn['DEBUG'],                // let class printout errors
				"debug"       => $conn['DEBUG']
			);            // detailed debugging information

			$this->data['sap'] = new saprfc($constr);
		} else {
			$return = array('sts' => 'notOK', 'msg' => 'Connection [RFC] configuration is not valid');
			echo json_encode($return);
			exit();
		}
	}

	protected function set_session_param()
	{
		$session = $_SESSION;
		unset($session['kirana_menu']);
		unset($session['kirana_menu_last_update']);
		unset($session['kirana_menu_opened']);
		unset($session['-pi_nama-']);
		unset($session['-pi_role-']);
		unset($session['-pi_nama_role-']);
		unset($session['-pi_level-']);
		unset($session['-pi_approve-']);
		unset($session['-pi_assign-']);
		unset($session['-pi_decline-']);
		unset($session['-pi_is_dual-']);
		unset($session['-pi_drop-']);
		unset($session['-pi_menu-']);
		unset($session['-pi_plant-']);
		unset($session['-pi_plant_code-']);

		return $session;
	}
}
