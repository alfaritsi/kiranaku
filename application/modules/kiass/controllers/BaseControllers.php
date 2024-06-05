<?php
	/*
    @application  : K-IASS
    @author       : MATTHEW JODI (8944)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */

	class BaseControllers extends MX_Controller {
		protected $data;

		public function __construct() {
			parent::__construct();

			/*load model*/
			$this->load->model('dgeneral');
			$this->load->model('dsettingscrap');

			/*load global attribute*/
			$this->data['generate']     = $this->generate;
			$this->data['module']       = $this->router->fetch_module();
			$this->data['user']         = $this->general->get_data_user();
			$this->data['session_role'] = $this->dsettingscrap->get_data_roleuser_format(array(
																									"connect" => NULL,
																									"app"   => 'kiass',
																									"user" => base64_decode($this->session->userdata("-nik-")),
																									"active" => "active"
																									
																								));
			if ($this->data['session_role'] == NULL) {
				$this->data['plant_kode'] = NULL;
				show_404();
			} else {
				$this->data['plant_kode'] = rtrim($this->data['session_role'][0]->pabrik, ",");
			}
		}

		public function index() {
			show_404();
		}

		public function get_master_plant($plant_in = NULL, $as_array = false, $plant_not_in = NULL, $return = NULL) {
			$plant = $this->dgeneral->get_master_plant($plant_in, $as_array, $plant_not_in);
			if (isset($_POST['tipe']) && $_POST['tipe'] == "array" || $return == "array") {
				return $plant;
			} else {
				echo json_encode($plant);
			}
		}

		public function get_master_depo($id_depo = NULL, $nama = NULL, $plant = NULL, $all = NULL, $return = NULL, $jns_depo = NULL) {
			$depo = $this->dgeneral->get_data_depo($id_depo, $nama, $plant, $all, $jns_depo);
			$depo = $this->general->generate_encrypt_json($depo, array("DEPID"));
			if (isset($_POST['tipe']) && $_POST['tipe'] == "array" || $return == "array") {
				return $depo;
			} else {
				echo json_encode($depo);
			}
		}

		public function get_user_autocomplete() {
			$this->general->get_user_autocomplete();
		}

		protected function connectSAP($user) {
			$koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);
			$conn    = $koneksi[$user];
			$constr  = array(
				"logindata"   => array(
					"ASHOST" => $conn['ASHOST'],    // application server
					"SYSNR"  => $conn['SYSNR'],        // system number
					"CLIENT" => $conn['CLIENT'],    // client
					"USER"   => $conn['USER'],        // user
					"PASSWD" => $conn['PASSWD']        // password
				),
				"show_errors" => $conn['DEBUG'],                // let class printout errors
				"debug"       => $conn['DEBUG']);            // detailed debugging information

			$this->data['sap'] = new saprfc($constr);
		}

		protected function get_data_karyawan()
	{
		$param = $this->input->post_get(NULL, TRUE);
		if (isset($param['autocomplete']) && $param['autocomplete'] == TRUE) {
			$user = $this->dgeneral->get_data_karyawan(array(
				"connect" => TRUE,
				"search" => $param['search'],
				"encrypt" => array("id"),
				"exclude" => array("id_karyawan", "login_buat", "login_edit"),
			));
			$data_user  = array(
				"total_count" => count($user),
				"incomplete_results" => false,
				"items" => $user
			);
			echo json_encode($data_user);
			exit();
		}
	}

	protected function get_data_posisi()
	{
		$param = $this->input->post_get(NULL, TRUE);
		if (isset($param['autocomplete']) && $param['autocomplete'] == TRUE) {
			$posisi = $this->dgeneral->get_data_posisi(array(
				"connect" => TRUE,
				"search" => $param['search'],
				"encrypt" => array("id"),
				"exclude" => array("login_buat", "login_edit"),
			));
			$data_posisi  = array(
				"total_count" => count($posisi),
				"incomplete_results" => false,
				"items" => $posisi
			);
			echo json_encode($data_posisi);
			exit();
		}
	}

	}

?>
