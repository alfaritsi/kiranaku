	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  	: Attachment Accounting
@author     	: Syah Jadianto (8604)
@contributor  	: 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Setting extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmasteracc');
	    $this->load->model('dtransactionacc');
	    $this->load->model('dsettingacc');
	}

	public function index(){
		show_404();
	}

	public function gl(){
		$this->general->check_access();
		$data['title']    					= "Master G/L Account HO";
		$data['title_form']    				= "Master G/L Account HO";
	    $data['module']     				= $this->router->fetch_module();
		$data['user']     					= $this->general->get_data_user();

		$data['list'] = $this->dmasteracc->get_data_gl(array(
			"connect" 	=> TRUE,
			"app"   	=> 'acc'
		));

		// echo json_encode($data['list']);exit();

		$this->load->view("setting/master_gl", $data);
	}

	public function access($param){
		switch ($param) {
			case 'user':
				$this->user_akses();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	private function user_akses(){
		$this->general->check_access();
		$data['title']    					= "Set Menu Attachment Accounting";
		$data['title_form']    				= "";
	    $data['module']     				= $this->router->fetch_module();
		$data['user']     					= $this->general->get_data_user();
		$data['dt_user']     				= $this->dsettingacc->get_data_user(NULL);
        $data['pabrik'] 					= $this->dmasteracc->get_pabrik(NULL);
        $data['dt_user_akses'] 				= $this->dsettingacc->get_data_user_akses(NULL);

		$this->load->view("setting/user_akses", $data);
	}


	public function get_data($param){
		switch ($param) {
			case 'user':
				$this->get_user_akses();
				break;
			case 'nik':
				$this->get_user_nik();
				break;
			case 'gl':
				$this->get_dropdown_gl();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function set_data($action, $param){
		switch ($param) {
			case 'user':
				$this->set_user_akses($action);
				break;
			
			case 'gl':
				$actions = NULL;
				if (isset($_POST['type']) && $_POST['type'] == "nonactive") {
					$actions = "delete_na";
				}
				else if (isset($_POST['type']) && $_POST['type'] == "setactive") {
					$actions = "activate_na";
				}

				$this->general->connectDbPortal();
				$return = $this->general->set($actions, "tbl_acc_master_gl", array(
					array(
						'kolom' => 'gl_account',
						'value' => $_POST['gl_account']
					)
				));
				echo json_encode($return);
				$this->general->closeDb();
				break;
			
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function save($param){
		switch ($param) {
			case 'access':
				$this->save_user_akses();
				break;
			case 'gl':
				$this->save_data_gl();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	private function get_dropdown_gl()
	{
		// $data['dropdown'] = $this->dmasteracc->get_dropdown_gl(array(
		// 	"connect" 	=> TRUE,
		// 	"app"   	=> 'acc'
		// ));
		$param = $this->input->post_get(NULL, TRUE);
		if (isset($param['autocomplete']) && $param['autocomplete'] == TRUE) {
			$gl = $this->dmasteracc->get_dropdown_gl(array(
				"connect" => NULL,
				"search" => $param['search']
			));
			$data_gl  = array(
				"total_count" => count($gl),
				"incomplete_results" => false,
				"items" => $gl
			);
			echo json_encode($data_gl);
			exit();
		}
	}

	private function get_user_akses(){
		$this->general->connectDbPortal();
		$data = $this->dsettingacc->get_data_user_akses($_POST['id']);
		echo json_encode($data);
	}	

	private function get_user_nik(){
		$this->general->connectDbPortal();
		$data = $this->dsettingacc->get_data_user($_POST['id']);
		echo json_encode($data);
	}	

    private function set_user_akses($action){
        $id = $_POST['id'];
        $aktif = $action == "activate" ? 1 : 0;

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row   = array(
                          'aktif'	=> $aktif
                     );
        $this->dgeneral->update('tbl_acc_approve_edit_upload', $data_row, array( 
                                                                    array(
                                                                        'kolom'=>'nik_alow',
                                                                        'value'=>$id
                                                                    )
                                                                ));

        $data_row   = array(
                          'aktif'	=> $aktif
                     );
        $this->dgeneral->update('tb_acc_user_pabrik', $data_row, array( 
                                                                    array(
                                                                        'kolom'=>'id_karyawan',
                                                                        'value'=>$id
                                                                    )
                                                                ));

    	if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            $msg    = "User akses telah terupdate";	
            $sts    = "OK";
        }	
        $return = array('sts' => $sts, 'msg' => $msg);
        $this->general->closeDb();
        echo json_encode($return);
    }

	private function save_user_akses(){
		$datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();


		if($_POST['tipe'] == "HO"){
	        $data_row   = array(
	                          'aktif'    				=> 0
	                     );
	        $this->dgeneral->update('tbl_acc_approve_edit_upload', $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'nik_alow',
	                                                                        'value'=>$_POST['nik']
	                                                                    )
	                                                                ));

	        $nama 		= $this->dsettingacc->get_nama_user($_POST['nik']);
	        
	        foreach ($_POST['pabrik'] as $pabrik) {
	        	$exists 	= $this->dsettingacc->get_user_akses_ho($_POST['nik'], $pabrik);
	        	$bukrs 		= $this->dmasteracc->get_pabrik($pabrik, "ALL");

		        if(empty($exists)){
		            $data_row   = array(
		                              'bukrs'     				=> $bukrs[0]->plant_code,
		                              'werks'     				=> $pabrik,
		                              'nik_alow'    			=> $_POST['nik'],
		                              'nama'    				=> $nama[0]->nama,
		                              'aktif'    				=> 1
		                            );
		            $this->dgeneral->insert('tbl_acc_approve_edit_upload', $data_row);
		        }else{
			        $data_row   = array(
			                          'aktif'    				=> 1
			                     );
			        $this->dgeneral->update('tbl_acc_approve_edit_upload', $data_row, array( 
			                                                                    array(
			                                                                        'kolom'=>'id',
			                                                                        'value'=>$exists[0]->id
			                                                                    )
			                                                                ));
		        }
	        }
		}else{
	        $data_row   = array(
	                          'aktif'    				=> 0
	                     );
	        $this->dgeneral->update('tb_acc_user_pabrik', $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_karyawan',
	                                                                        'value'=>$_POST['nik']
	                                                                    )
	                                                                ));

	        $nama 		= $this->dsettingacc->get_nama_user($_POST['nik']);
	        
	        foreach ($_POST['pabrik'] as $pabrik) {
	        	$exists 	= $this->dsettingacc->get_user_akses_pabrik($_POST['nik'], $pabrik);

		        if(empty($exists)){
		            $data_row   = array(
		                              'id_karyawan'     		=> $_POST['nik'],
		                              'nama_karyawan'     		=> $nama[0]->nama,
		                              'pabrik'     				=> $pabrik,
		                              'aktif'    				=> 1
		                            );
		            $this->dgeneral->insert('tb_acc_user_pabrik', $data_row);
		        }else{
			        $data_row   = array(
			                          'aktif'    				=> 1
			                     );
			        $this->dgeneral->update('tb_acc_user_pabrik', $data_row, array( 
			                                                                    array(
			                                                                        'kolom'=>'id',
			                                                                        'value'=>$exists[0]->id
			                                                                    )
			                                                                ));
		        }
	        }
		}
	
    	if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            $msg    = "User akses telah terupdate";	
            $sts    = "OK";
        }	
        $return = array('sts' => $sts, 'msg' => $msg);
        $this->general->closeDb();
        echo json_encode($return);
	}

	private function save_data_gl(){
		$datetime = date("Y-m-d H:i:s");
		$param = $this->input->post();
		
		$cekExisting = $this->dmasteracc->get_data_gl(array(
			"connect" 		=> TRUE,
			"app"   		=> 'acc',
			"gl_account" 	=> $param['gl_account'],
			"single_row"	=> TRUE
		));

		
		if(!$cekExisting){
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

	        $data_row   = array(
				'gl_account'     => $param['gl_account'],
				'account'        => $param['gl_account'].' - '.$param['deskripsi']
			  );
			$data_row_format     = $this->dgeneral->basic_column("insert_full", $data_row);
	        $this->dgeneral->insert('tbl_acc_master_gl', $data_row_format);
	       
		}else{
	        $msg    = "Duplicate Entry, Periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
	
    	if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            $msg    = "G/L Account Berhasil ditambahkan";	
            $sts    = "OK";
        }	
        $return = array('sts' => $sts, 'msg' => $msg);
        $this->general->closeDb();
        echo json_encode($return);
	}
	


}