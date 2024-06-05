<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : PCS (Production Cost Simulation)
@author     : Akhmad Syaiful Yamang (8347)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Calculate extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmasterpcs');
	    $this->load->model('dsettingpcs');
	    $this->load->model('dcalculatepcs');
		$this->load->model('dtransaksipcs');
	}

	public function index(){
		show_404();
	}

	public function data(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    = "Perhitungan Simulasi Biaya Produksi";
		$data['plant']    = $this->general->get_master_plant(NULL, NULL, false, NULL, 'ERP');
		$this->load->view("calculate", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_data($param){
		switch ($param) {
			case 'simulation':
				$this->get_simulation();
				break;
			case 'listrik':
				$this->get_listrik();
				break;
			
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_simulation(){
		if(isset($_POST['plant']) && isset($_POST['bln_thn'])){
			$plant 				= $_POST['plant'];
			$grouping 			= $_POST['grouping'];
			$periode			= explode(".", $_POST['bln_thn']);
			$month 				= $periode[0];
			$year 				= $periode[1];
			$qty_prod_sim 		= isset($_POST['jml_prod_SIR']) && trim($_POST['jml_prod_SIR']) !== "" ? str_replace(",", "", $_POST['jml_prod_SIR']) : 0;
			$lwbp_sim			= isset($_POST['listrik_lwbp']) && trim($_POST['listrik_lwbp']) !== "" ? str_replace(",", "", $_POST['listrik_lwbp']) : 0;
			$wbp_sim			= isset($_POST['listrik_wbp']) && trim($_POST['listrik_wbp']) !== "" ? str_replace(",", "", $_POST['listrik_wbp']) : 0;

			$result 			= $this->dcalculatepcs->simulate_data($plant, $month, $year, $qty_prod_sim, $lwbp_sim, $wbp_sim, $grouping);
			echo json_encode($result);
		}else{
			$msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
        	echo json_encode($return);
        	exit();
		}
	}
	//-------------------------------------------------------
	private function get_listrik(){
		if(isset($_POST['plant']) && isset($_POST['bulan'])){
			$bulan	= "01.".$_POST['bulan'];
			$bulan0 = date("m.Y", strtotime("$bulan -1 month"));			
			$plant	= $_POST['plant'];
			$data	= $this->dtransaksipcs->get_data_listrik(NULL, 'all', $bulan0, $plant);
			echo json_encode($data);
		}else{
			$msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
        	echo json_encode($return);
        	exit();
		}
	}
	
}

?>
