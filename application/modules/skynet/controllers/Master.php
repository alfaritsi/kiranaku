	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  	: SKYNET
@author     	: Syah Jadianto (8604)
@contributor  	: 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Master extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmasterskynet');
	}

	public function index(){
		show_404();
	}



	public function get_data($param){
		switch ($param) {
			case 'category':
				$this->get_data_kategori();
				break;
			case 'subcategory':
				$this->get_data_subkategori();
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
	private function get_data_kategori(){
		$this->general->connectDbPortal();
		$category = $this->dmasterskynet->get_data_kategori();
		$this->general->closeDb();
		echo json_encode($category);
	}	

	private function get_data_subkategori(){
		$this->general->connectDbPortal();

		$category = $this->dmasterskynet->get_data_subkategori($_POST['id']);
		$this->general->closeDb();
		echo json_encode($category);
	}	
	/**********************************/



}