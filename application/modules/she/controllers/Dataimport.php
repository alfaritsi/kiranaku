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

Class Dataimport extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmastershe');
	    $this->load->model('dtransactionshe');
	}

	public function index(){
		show_404();
	}

	public function data_import(){
		$this->general->check_access();
		$data['title']    		= "Import Data";
		$data['title_form']    	= "";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
        // $data['kategori'] 		= $this->dmastershe->get_data_kategori(NULL, NULL);
        // $data['jenis'] 			= $this->dmastershe->get_data_jenis();
        // $data['parameter'] 		= $this->dmastershe->get_data_parameter();
        // $data['bakumutu'] 		= $this->dmastershe->get_data_bakumutu(NULL, NULL, NULL, NULL, NULL, NULL, NULL);
		$this->load->view("data_import/data_import", $data);
	}


}