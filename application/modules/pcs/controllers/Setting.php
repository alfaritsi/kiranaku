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
		//====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate; 
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

		$data['title']    = "Setting PE Grup dan COA";
		$data['user']     = $this->general->get_data_user();
		$data['pegrup']   = $this->dmasterpcs->get_data_pegrup(NULL, 'all');
		$data['COA']   	  = $this->dmasterpcs->get_data_COA();
		$data['list'] 	  = $this->dsettingpcs->get_data_pecoa_gruping(NULL, 'all');
		$this->load->view("setting/pecoa", $data);
	}

	public function formulacoa(){
		//====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate; 
        $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

		$data['title']    = "Setting Formula dan COA";
		$data['formula']  = $this->dmasterpcs->get_data_jns_formula(NULL, 'all');
		$data['COA']   	  = $this->dmasterpcs->get_data_COA();
		$data['list'] 	  = $this->dsettingpcs->get_data_formcoa_gruping(NULL, NULL, 'all');
		$this->load->view("setting/formcoa", $data);
	}

	public function historybackward(){
		//====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate']   = $this->generate; 
        $data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
        //===============================================/
        
        $data['title']    = "Setting Historical Backward";
		$data['COA']   	  = $this->dmasterpcs->get_data_COA();
		$data['setting']  = $this->dsettingpcs->get_data_setting_backward();
		$this->load->view("setting/historybackward", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_data($param){
		switch ($param) {
			case 'pecoa':
				$this->get_pecoa();
				break;

			case 'formcoa':
				$this->get_formcoa();
				break;

			case 'norma':
				echo json_encode($this->dmasterpcs->get_data_norma());
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
			case 'formcoa':
				$this->save_formcoa();
				break;

			case 'pecoa':
				$this->save_pecoa();
				break;

			case 'historybackward':
				$this->save_historybackward();
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
		$pecoa         	= $this->dsettingpcs->get_data_pecoa_gruping($this->generate->kirana_decrypt($_POST['pegrup']), 'all');
		echo json_encode($pecoa);
	}

    private function save_pecoa(){
    	$datetime       = date("Y-m-d H:i:s");

    	$pecoa         	= $this->dsettingpcs->get_data_pecoa($this->generate->kirana_decrypt($_POST['pegrup']), NULL, 'all');
    	
    	$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $list_coa   = isset($_POST['coa']) ? $_POST['coa'] : array();
		foreach($pecoa as $dt){
			if(!in_array($dt->saknr, $list_coa)){
				$this->dgeneral->delete("tbl_pcs_setting_pecoa", array(
                                                                        array(
                                                                            'kolom'=>'id_mpegrup',
                                                                            'value'=>$dt->id_mpegrup
                                                                        ),
                                                                        array(
                                                                            'kolom'=>'saknr',
                                                                            'value'=>$dt->saknr
                                                                        )
                                                                   ));
			}else{
				$data_row   = array(
                                    'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_edit'      => $datetime
                                );
                $this->dgeneral->update('tbl_pcs_setting_pecoa', $data_row, array( 
	                                                                            array(
	                                                                                'kolom'=>'id_mpegrup',
	                                                                                'value'=>$dt->id_mpegrup
	                                                                            ),
	                                                                            array(
	                                                                                'kolom'=>'saknr',
	                                                                                'value'=>$dt->saknr
	                                                                            )
	                                                                        ));

				unset($list_coa[array_search($dt->saknr, $_POST['coa'])]);
			}
		}

		foreach($list_coa as $dt){
			$data_row   = array(
                                'id_mpegrup'        => $this->generate->kirana_decrypt($_POST['pegrup']),
                                'saknr'        		=> $dt,
                                'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_buat'      => $datetime,
                                'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_edit'      => $datetime
                            );
            $this->dgeneral->insert('tbl_pcs_setting_pecoa', $data_row);
		}

		if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            $msg    = "Data berhasil ditambahkan";
            $sts    = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
    	echo json_encode($return);	
    }

    //-------------------------------------------------//

    private function get_formcoa(){
    	if(isset($_POST['norma'])){
    		$id_mnorma = $_POST['norma'];
    	}else{
    		$id_mnorma = NULL;
    	}

		$formcoa         	= $this->dsettingpcs->get_data_formcoa_gruping($this->generate->kirana_decrypt($_POST['formula']), $id_mnorma, 'all');
		echo json_encode($formcoa);
	}

    private function save_formcoa(){
    	$datetime       = date("Y-m-d H:i:s");

    	if(isset($_POST['norma'])){
    		$id_mnorma = $_POST['norma'];
    	}else{
    		$id_mnorma = NULL;
    	}

    	$formcoa         	= $this->dsettingpcs->get_data_formcoa($this->generate->kirana_decrypt($_POST['formula']), $id_mnorma, NULL, 'all');
    	
    	$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $list_coa   = isset($_POST['coa']) ? $_POST['coa'] : array();
		foreach($formcoa as $dt){
			if(!in_array($dt->saknr, $list_coa)){
				$this->dgeneral->delete("tbl_pcs_setting_formcoa", array(
                                                                        array(
                                                                            'kolom'=>'id_mjenis',
                                                                            'value'=>$dt->id_mjenis
                                                                        ),
                                                                        array(
                                                                            'kolom'=>'id_mnorma',
                                                                            'value'=>$dt->id_mnorma
                                                                        ),
                                                                        array(
                                                                            'kolom'=>'saknr',
                                                                            'value'=>$dt->saknr
                                                                        )
                                                                   ));
			}else{
				$data_row   = array(
                                    'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_edit'      => $datetime
                                );
                $this->dgeneral->update('tbl_pcs_setting_formcoa', $data_row, array( 
	                                                                            array(
	                                                                                'kolom'=>'id_mjenis',
	                                                                                'value'=>$dt->id_mjenis
	                                                                            ),
	                                                                            array(
	                                                                                'kolom'=>'id_mnorma',
	                                                                                'value'=>$dt->id_mnorma
	                                                                            ),
	                                                                            array(
	                                                                                'kolom'=>'saknr',
	                                                                                'value'=>$dt->saknr
	                                                                            )
	                                                                        ));

				unset($list_coa[array_search($dt->saknr, $_POST['coa'])]);
			}
		}

		foreach($list_coa as $dt){
			$data_row   = array(
                                'id_mjenis'         => $this->generate->kirana_decrypt($_POST['formula']),
                                'id_mnorma'         => $id_mnorma,
                                'saknr'        		=> $dt,
                                'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_buat'      => $datetime,
                                'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_edit'      => $datetime
                            );
            $this->dgeneral->insert('tbl_pcs_setting_formcoa', $data_row);
		}

		if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            $msg    = "Data berhasil ditambahkan";
            $sts    = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);
    	echo json_encode($return);	
    }

    //-------------------------------------------------//

    private function save_historybackward(){
    	$datetime       = date("Y-m-d H:i:s");

    	$backward     	= $this->dsettingpcs->get_data_setting_backward();

    	$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
    	if(isset($_POST['param'])){
    		$this->dgeneral->delete("tbl_pcs_setting_historical_backward", array(
    																			array(
	                                                                                'kolom'=>1,
	                                                                                'value'=>1
	                                                                            )
																			));
    		if($_POST['param'] == "bulan"){
    			$data_row   = array(
                                    'param'         	=> $_POST['param'],
                                    'value'         	=> $_POST['value'],
                                    'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_edit'      => $datetime
                                );
                $this->dgeneral->insert('tbl_pcs_setting_historical_backward', $data_row);
    		}

    		if($this->dgeneral->status_transaction() === FALSE){
                $this->dgeneral->rollback_transaction();
                $msg    = "Periksa kembali data yang dimasukkan";
                $sts    = "NotOK";
            }else{
                $this->dgeneral->commit_transaction();
                $msg    = "Data berhasil ditambahkan";
                $sts    = "OK";
            }
    	}else{
    		$msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
    	}
        $return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);	
    }
}

?>