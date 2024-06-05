<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : MENTORING
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Master extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
	    $this->load->model('dmastermentor');
	}

	public function index(){
		show_404();
	}
	public function status($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Status Mentoring";
		$data['title_form']  = "Form Status Mentoring";
        $data['status'] 	 = $this->dmastermentor->get_data_status(
								array(
									"connect" 	=> TRUE,
									"na" 		=> 'n',
									"encrypt" 	=> array("id")
								));
		$this->load->view("master/status", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL) {
		switch ($param) {
            case 'status':
                $post = $this->input->post(NULL, TRUE);
                $param_ = array(
                    "connect" 	 => TRUE,
                    "id_status"  => (isset($post['id_status']) ? $this->generate->kirana_decrypt($post['id_status']) : NULL),
                    "return"	 => @$post['return'],
					"encrypt" 	 => array("id")
                );
                $this->get_status($param_);
                break;
				
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set($param = NULL) {
		$action = NULL;
		if (isset($_POST['type']) && $_POST['type'] == "nonactive") {
			$action = "delete_na";
		} else if (isset($_POST['type']) && $_POST['type'] == "setactive") {
			$action = "activate_na";
		}
		if ($action) {
			switch ($param) {
				case 'status':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_mentor_status", array(
						array(
							'kolom' => 'id_status',
							'value' => $this->generate->kirana_decrypt($_POST['id_status'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}
	}
	public function save($param = NULL) {
		switch ($param) {
			case 'status':
				$this->save_status($param);
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
    private function get_status($param = NULL)
    {
        $result = $this->dmastermentor->get_data_status($param);

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "autocomplete") {
            $result  = array(
                "total_count" => count($result),
                "incomplete_results" => false,
                "items" => $result
            );
            echo json_encode($result);
        } else {
            return $result;
        }
    }
	
	private function save_status($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_status	= (isset($post['id_status']) ? $this->generate->kirana_decrypt($post['id_status']) : NULL);
		if ($id_status!=NULL){	
			$data_row = array(
				"nama"		=> $post['nama'],
				"warna"		=> $post['warna'],
				"max_day"	=> $post['max_day'],
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_mentor_status", $data_row, array(
				array(
					'kolom' => 'id_status',
					'value' => $id_status
				)
			));
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil diedit";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}	
	/*====================================================================*/
		
}