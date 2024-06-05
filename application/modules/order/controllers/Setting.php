<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

Class Setting extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dsettingfolder');

	}

	public function index(){
		show_404();
	}

	public function form(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$this->load->view("page", $data);
	    
	}

	public function quotation(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$this->load->view("form", $data);
	    
	}

	public function po(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$this->load->view("form", $data);
	    
	}
}

?>