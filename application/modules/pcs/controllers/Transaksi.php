<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : PCS (Production Cost Simulation)
@author     : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Transaksi extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dtransaksipcs');
	}

	public function index(){
		show_404();
	}

	public function listrik(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Konsumsi Listrik LWBP dan WBP";
		$data['title_form']    	= "Konsumsi Listrik";
		$bulan					= (isset($_POST['bulan']) ? $_POST['bulan'] : date('m.Y'));
		$data['listrik']     	= $this->dtransaksipcs->get_data_listrik(NULL, 'all', $bulan);
		$this->load->view("transaksi/listrik", $data);
	}
	public function listrik_filter(){
		$bulan		= (isset($_POST['bulan']) ? $_POST['bulan'] : date('m.Y'));
		$data		= $this->dtransaksipcs->get_data_listrik(NULL, 'all', $bulan);
        echo json_encode($data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_data($param){
		switch ($param) {
			case 'listrik':
				$this->get_listrik();
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set_data($action, $param){
		switch ($param) {
			case 'listrik':
				$this->set_listrik($action);
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function save($param){
		switch ($param) {
			case 'listrik_lwbp':
				$this->save_listrik_lwbp();
				break;
			case 'listrik_wbp':
				$this->save_listrik_wbp();
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
	//save listrik lwbp
	private function save_listrik_lwbp(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$plant	= (isset($_POST['plant']) ? $_POST['plant'] : NULL);
		$bulan	= (isset($_POST['bulan']) ? $_POST['bulan'] : NULL);
		$nilai	= (isset($_POST['nilai']) ? str_replace(',','',$_POST['nilai']) : NULL);
		$data	= $this->dtransaksipcs->cek_data_listrik($bulan, $plant);
		if(empty($data[0]->id_mlistrik)){
			$data_row   = array(
								'kode_pabrik'	=> $plant,
								'bulan'			=> $bulan,
								'lwbp'   		=> $nilai,
								'active'   		=> '1',
								'login_buat'   	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat' 	=> $datetime,
								'login_edit'  	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit' 	=> $datetime
							);
			$this->dgeneral->insert('tbl_pcs_mlistrik', $data_row);
		}else{
			$data_row   = array(
								'lwbp'   		=> $nilai,
								'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'  => $datetime
							);
			$this->dgeneral->update('tbl_pcs_mlistrik', $data_row, array( 
																array(
																	'kolom'=>'id_mlistrik',
																	'value'=>$data[0]->id_mlistrik
																)
															));
		}
		
		if($this->dgeneral->status_transaction() === FALSE){
			$this->dgeneral->rollback_transaction();
			$msg    = "Periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
		}else{
			$this->dgeneral->commit_transaction();
			$msg    = "Transaksi Berhasil";
			$sts    = "OK";
		}	
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}
	
	//save listrik wbp
	private function save_listrik_wbp(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$plant	= (isset($_POST['plant']) ? $_POST['plant'] : NULL);
		$bulan	= (isset($_POST['bulan']) ? $_POST['bulan'] : NULL);
		$nilai	= (isset($_POST['nilai']) ? str_replace(',','',$_POST['nilai']) : NULL);
		$data	= $this->dtransaksipcs->cek_data_listrik($bulan, $plant);
		if(empty($data[0]->id_mlistrik)){
			$data_row   = array(
								'kode_pabrik'	=> $plant,
								'bulan'			=> $bulan,
								'wbp'   		=> $nilai,
								'active'   		=> '1',
								'login_buat'   	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat' 	=> $datetime,
								'login_edit'  	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit' 	=> $datetime
							);
			$this->dgeneral->insert('tbl_pcs_mlistrik', $data_row);
		}else{
			$data_row   = array(
								'wbp'   		=> $nilai,
								'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'  => $datetime
							);
			$this->dgeneral->update('tbl_pcs_mlistrik', $data_row, array( 
																array(
																	'kolom'=>'id_mlistrik',
																	'value'=>$data[0]->id_mlistrik
																)
															));
		}
		
		if($this->dgeneral->status_transaction() === FALSE){
			$this->dgeneral->rollback_transaction();
			$msg    = "Periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
		}else{
			$this->dgeneral->commit_transaction();
			$msg    = "Transaksi Berhasil";
			$sts    = "OK";
		}	
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}


}