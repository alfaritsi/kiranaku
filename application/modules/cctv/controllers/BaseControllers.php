<?php
	/*
    @application  : UMB (Uang Muka Bokar)
    @author       : Akhmad Syaiful Yamang (8347)
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
			$this->load->model('dsettingumb');

			/*load global attribute*/
			$this->data['generate']     = $this->generate;
			$this->data['module']       = $this->router->fetch_module();
			$this->data['user']         = $this->general->get_data_user();
			//$this->data['session_role'] = $this->dsettingumb->get_setting_user("open", NULL, base64_decode($this->session->userdata("-nik-")));
			// if ($this->data['session_role'] == NULL) {
			// 	show_404();
			// }
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

		public function get_master_depo($id_depo = NULL, $nama = NULL, $plant = NULL, $all = NULL, $return = NULL) {
			$depo = $this->dgeneral->get_data_depo($id_depo, $nama, $plant, $all);
			$depo = $this->general->generate_encrypt_json($depo, "DEPID");
			if (isset($_POST['tipe']) && $_POST['tipe'] == "array" || $return == "array") {
				return $depo;
			} else {
				echo json_encode($depo);
			}
		}

		public function get_user_autocomplete() {
			$this->general->get_user_autocomplete();
		}
	}

?>
