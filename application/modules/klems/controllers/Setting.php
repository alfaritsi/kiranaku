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

Class Setting extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmasterpcs');
	    $this->load->model('dsettingpcs');
	}

	public function index(){
		show_404();
	}

	public function pecoa(){
		$this->general->check_access();
		$data['title']    = "Setting PE Grup dan COA";
		$data['user']     = $this->general->get_data_user();
		$data['pegrup']   = $this->dmasterpcs->get_data_pegrup(NULL, 'all');
		$data['COA']   	  = $this->dmasterpcs->get_data_COA();
		$data['list'] 	  = $this->dsettingpcs->get_data_pecoa_gruping(NULL, 'all');
		$this->load->view("setting/pecoa", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_data($param){
		switch ($param) {
			case 'pecoa':
				$this->get_pecoa();
				break;
			
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function set_data($action, $param){
		switch ($param) {			
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function save($param){
		switch ($param) {
			case 'pecoa':
				$this->save_pecoa();
				break;
			
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_pecoa(){
		$pecoa         	= $this->dsettingpcs->get_data_pecoa_gruping($_POST['pegrup'], 'all');
		echo json_encode($pecoa);
	}

    private function save_pecoa(){
    	$datetime       = date("Y-m-d H:i:s");

    	$pecoa         	= $this->dsettingpcs->get_data_pecoa($_POST['pegrup'], 'all');
    	
    	if(isset($_POST['coa'])){
    		foreach($pecoa as $dt){
    			if(!in_array($dt->saknr, $_POST['coa'])){
    				$this->dgeneral->delete("tbl_pcs_setting_pecoa", array(
                                                                            array(
                                                                                'kolom'=>'id_mpegrup',
                                                                                'value'=>$db->id_mpegrup
                                                                            ),
                                                                            array(
                                                                                'kolom'=>'saknr',
                                                                                'value'=>$db->saknr
                                                                            )
                                                                       ));
    			}else{
    				unset($_POST['coa'][array_search($dt->saknr, $_POST['coa'])]);
    			}
    		}
    		echo json_encode($_POST['coa']);
    	}
    }
}

?>