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

Class Master extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmasterpcs');
	}

	public function index(){
		show_404();
	}

	public function jenis(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Jenis Formula";
		$data['title_form']    	= "Formula";
		$data['jenis']     		= $this->dmasterpcs->get_data_jns_formula(NULL, 'all');
		$this->load->view("master/jenis", $data);
	}

	public function pegrup(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    = "PE Grup";
		$data['pegrup']   = $this->dmasterpcs->get_data_pegrup(NULL, 'all');
		$this->load->view("master/pegrup", $data);
	}

	public function lwbp(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    = "Norma Biaya Listrik LWBP";
		$data['plant']    = $this->general->get_master_plant();
		$data['lwbp']     = $this->dmasterpcs->get_data_lwbp(NULL, 'all');
		$this->load->view("master/lwbp", $data);
	}

	public function wbp(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    = "Norma Biaya Listrik WBP";
		$data['plant']    = $this->general->get_master_plant();
		$data['wbp']      = $this->dmasterpcs->get_data_wbp(NULL, 'all');
		$this->load->view("master/wbp", $data);
	}

	public function cangkang(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    = "Norma Cangkang Sawit";
		$data['plant']    = $this->general->get_master_plant();
		$data['cangkang'] = $this->dmasterpcs->get_data_cangkang(NULL, 'all');
		$this->load->view("master/cangkang", $data);
	}

	public function genset(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    = "Norma Solar Genset";
		$data['plant']    = $this->general->get_master_plant();
		$data['genset']   = $this->dmasterpcs->get_data_genset(NULL, 'all');
		$this->load->view("master/genset", $data);
	}

	public function drier(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    = "Norma Solar Drier";
		$data['plant']    = $this->general->get_master_plant();
		$data['drier']    = $this->dmasterpcs->get_data_drier(NULL, 'all');
		$this->load->view("master/drier", $data);
	}

	public function lain(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    = "Norma Solar Lain-lain";
		$data['plant']    = $this->general->get_master_plant(); 
		$data['lain']     = $this->dmasterpcs->get_data_lain(NULL, 'all');
		$this->load->view("master/lain", $data);
	}

	public function lembur(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    = "Norma Lembur";
		$data['plant']    = $this->general->get_master_plant(); 
		$data['lembur']   = $this->dmasterpcs->get_data_lembur(NULL, 'all');
		$this->load->view("master/lembur", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_master_COA(){
		if(isset($_GET['q'])){
			$not_gruping_table 	= isset($_GET['not_gruping']) ? 'tbl_pcs_setting_'.$_GET['not_gruping'] : NULL;
            $data       		= $this->dmasterpcs->get_data_COA(NULL, $_GET['q'], $not_gruping_table);
            $data_json  		= array(
			                            "total_count" => count($data),
			                            "incomplete_results"=>false,
			                            "items"=>$data
			                          );
            echo json_encode($data_json);
        }
	}

	public function get_data($param){
		switch ($param) {
			case 'jenis':
				$this->get_jenis();
				break;
			case 'pegrup':
				$this->get_pegrup();
				break;
			case 'lwbp':
				$this->get_lwbp();
				break;
			case 'wbp':
				$this->get_wbp();
				break;
			case 'cangkang':
				$this->get_cangkang();
				break;
			case 'genset':
				$this->get_genset();
				break;
			case 'drier':
				$this->get_drier();
				break;
			case 'lain':
				$this->get_lain();
				break;
			case 'lembur':
				$this->get_lembur();
				break;
			
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set_data($action, $param){
		switch ($param) {
			case 'jenis':
				$this->set_jenis($action);
				break;
			case 'pegrup':
				$this->set_pegrup($action);
				break;
			case 'lwbp':
				$this->set_lwbp($action);
				break;
			case 'wbp':
				$this->set_wbp($action);
				break;
			case 'cangkang':
				$this->set_cangkang($action);
				break;
			case 'genset':
				$this->set_genset($action);
				break;
			case 'drier':
				$this->set_drier($action);
				break;
			case 'lain':
				$this->set_lain($action);
				break;
			case 'lembur':
				$this->set_lembur($action);
				break;
			
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function save($param){
		switch ($param) {
			case 'jenis':
				$this->save_jenis();
				break;
			case 'pegrup':
				$this->save_pegrup();
				break;
			case 'lwbp':
				$this->save_lwbp();
				break;
			case 'wbp':
				$this->save_wbp();
				break;
			case 'cangkang':
				$this->save_cangkang();
				break;
			case 'genset':
				$this->save_genset();
				break;
			case 'drier':
				$this->save_drier();
				break;
			case 'lain':
				$this->save_lain();
				break;
			case 'lembur':
				$this->save_lembur();
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
	private function get_jenis(){
		$id_mjenis		= $this->generate->kirana_decrypt($_POST['id_mjenis']);
		$jenis         	= $this->dmasterpcs->get_data_jns_formula($id_mjenis, 'all');
		$array['id']	= $_POST['id_mjenis'];
		$array['data']	= $jenis;
		echo json_encode($array);
	}

    private function set_jenis($action){
        $id_mjenis 		= $this->generate->kirana_decrypt($_POST['id_mjenis']);
        $this->general->connectDbPortal();
        $delete     	= $this->general->set($action, "tbl_pcs_mjenis", array(
			                                                                array(
			                                                                    'kolom'=>'id_mjenis',
			                                                                    'value'=>$id_mjenis
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_jenis(){
		$datetime       = date("Y-m-d H:i:s");

		$jenis         	= $this->dmasterpcs->get_data_jns_formula(NULL, 'all', $_POST['jns_formula']);
        if(isset($_POST['jns_formula']) && $_POST['jns_formula'] !== "" && (count($jenis) == 0 || $jenis[0]->id_mjenis == $this->generate->kirana_decrypt($_POST['id_mjenis']))){
            $this->general->connectDbPortal();
        	$this->dgeneral->begin_transaction();

        	if(isset($_POST['id_mjenis']) && $_POST['id_mjenis'] != ""){
                $data_row   = array(
                                  'jns_formula'     => $_POST['jns_formula'],
                                  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                                  'tanggal_edit'    => $datetime
                             );
                $this->dgeneral->update('tbl_pcs_mjenis', $data_row, array( 
                                                                            array(
                                                                                'kolom'=>'id_mjenis',
                                                                                'value'=>$this->generate->kirana_decrypt($_POST['id_mjenis'])
                                                                            )
                                                                        ));
            }else{
                $data_row   = array(
                                    'jns_formula'       => $_POST['jns_formula'],
                                    'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_buat'      => $datetime,
                                    'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_edit'      => $datetime
                                );
                $this->dgeneral->insert('tbl_pcs_mjenis', $data_row);
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

	//-------------------------------------------------//

	private function get_pegrup(){
		$id_mpegrup		= $this->generate->kirana_decrypt($_POST['id_mpegrup']);
		$pegrup         = $this->dmasterpcs->get_data_pegrup($id_mpegrup, 'all');
		$array['id']	= $_POST['id_mpegrup'];
		$array['data']	= $pegrup;
		echo json_encode($array);
	}

    private function set_pegrup($action){
        $id_mpegrup		= $this->generate->kirana_decrypt($_POST['id_mpegrup']);
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_pcs_mpegrup", array(
			                                                                array(
			                                                                    'kolom'=>'id_mpegrup',
			                                                                    'value'=>$id_mpegrup
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_pegrup(){
		$datetime       = date("Y-m-d H:i:s");

		$pegrup         	= $this->dmasterpcs->get_data_pegrup(NULL, 'all', $_POST['nama_pegrup']);
        if(isset($_POST['nama_pegrup']) && $_POST['nama_pegrup'] !== "" && (count($pegrup) == 0 || $pegrup[0]->id_mpegrup == $this->generate->kirana_decrypt($_POST['id_mpegrup']))){
            $this->general->connectDbPortal();
        	$this->dgeneral->begin_transaction();

        	if(isset($_POST['id_mpegrup']) && $_POST['id_mpegrup'] != ""){
                $data_row   = array(
                                  'nama_grup'    	=> $_POST['nama_pegrup'],
                                  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                                  'tanggal_edit'    => $datetime
                             );
                $this->dgeneral->update('tbl_pcs_mpegrup', $data_row, array( 
                                                                            array(
                                                                                'kolom'=>'id_mpegrup',
                                                                                'value'=>$this->generate->kirana_decrypt($_POST['id_mpegrup'])
                                                                            )
                                                                        ));
            }else{
                $data_row   = array(
                                    'nama_grup'       	=> $_POST['nama_pegrup'],
                                    'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_buat'      => $datetime,
                                    'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_edit'      => $datetime
                                );
                $this->dgeneral->insert('tbl_pcs_mpegrup', $data_row);
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

	//-------------------------------------------------//

	private function get_lwbp(){
		$lwbp         	= $this->dmasterpcs->get_data_lwbp($_POST['plant'], 'all');
		echo json_encode($lwbp);
	}

    private function set_lwbp($action){
    	$id_mlwbp		= $this->generate->kirana_decrypt($_POST['id_mlwbp']);
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_pcs_mlwbp", array(
			                                                                array(
			                                                                    'kolom'=>'id_mlwbp',
			                                                                    'value'=>$id_mlwbp
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_lwbp(){
		$datetime       = date("Y-m-d H:i:s");

		$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $lwbp 	= $this->dmasterpcs->get_data_lwbp($_POST['plant'], 'all');
        if($lwbp){
        	$data_row   = array(
                              'norma'    		=> $_POST['norma'],
                              'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'    => $datetime
                         );
            $this->dgeneral->update('tbl_pcs_mlwbp', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'kode_pabrik',
                                                                            'value'=>$_POST['plant']
                                                                        )
                                                                    ));
        }else{
        	$data_row   = array(
                                'kode_pabrik'  		=> $_POST['plant'],
                                'norma'       		=> $_POST['norma'],
                                'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_buat'      => $datetime,
                                'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_edit'      => $datetime
                            );
            $this->dgeneral->insert('tbl_pcs_mlwbp', $data_row);
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

	private function get_wbp(){
		$wbp         	= $this->dmasterpcs->get_data_wbp($_POST['plant'], 'all');
		echo json_encode($wbp);
	}

    private function set_wbp($action){
    	$id_mwbp		= $this->generate->kirana_decrypt($_POST['id_mwbp']);
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_pcs_mwbp", array(
			                                                                array(
			                                                                    'kolom'=>'id_mwbp',
			                                                                    'value'=>$id_mwbp
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_wbp(){
		$datetime       = date("Y-m-d H:i:s");

		$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $wbp 	= $this->dmasterpcs->get_data_wbp($_POST['plant'], 'all');
        if($wbp){
        	$data_row   = array(
                              'norma'    		=> $_POST['norma'],
                              'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'    => $datetime
                         );
            $this->dgeneral->update('tbl_pcs_mwbp', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'kode_pabrik',
                                                                            'value'=>$_POST['plant']
                                                                        )
                                                                    ));
        }else{
        	$data_row   = array(
                                'kode_pabrik'  		=> $_POST['plant'],
                                'norma'       		=> $_POST['norma'],
                                'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_buat'      => $datetime,
                                'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_edit'      => $datetime
                            );
            $this->dgeneral->insert('tbl_pcs_mwbp', $data_row);
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

	private function get_cangkang(){
		$cangkang         	= $this->dmasterpcs->get_data_cangkang($_POST['plant'], 'all');
		echo json_encode($cangkang);
	}

    private function set_cangkang($action){
        $id_mcangkang = $this->generate->kirana_decrypt($_POST['id_mcangkang']);
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_pcs_mcangkang", array(
			                                                                array(
			                                                                    'kolom'=>'id_mcangkang',
			                                                                    'value'=>$id_mcangkang
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_cangkang(){
		$datetime       = date("Y-m-d H:i:s");

		$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $cangkang 	= $this->dmasterpcs->get_data_cangkang($_POST['plant'], 'all');
        if($cangkang){
        	$data_row   = array(
                              'norma'    		=> $_POST['norma'],
                              'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'    => $datetime
                         );
            $this->dgeneral->update('tbl_pcs_mcangkang', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'kode_pabrik',
                                                                            'value'=>$_POST['plant']
                                                                        )
                                                                    ));
        }else{
        	$data_row   = array(
                                'kode_pabrik'  		=> $_POST['plant'],
                                'norma'       		=> $_POST['norma'],
                                'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_buat'      => $datetime,
                                'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_edit'      => $datetime
                            );
            $this->dgeneral->insert('tbl_pcs_mcangkang', $data_row);
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

	private function get_genset(){
		$genset         	= $this->dmasterpcs->get_data_genset($_POST['plant'], 'all');
		echo json_encode($genset);
	}

    private function set_genset($action){
        $id_mgenset		= $this->generate->kirana_decrypt($_POST['id_mgenset']);
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_pcs_mgenset", array(
			                                                                array(
			                                                                    'kolom'=>'id_mgenset',
			                                                                    'value'=>$id_mgenset
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_genset(){
		$datetime       = date("Y-m-d H:i:s");

		$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $genset 	= $this->dmasterpcs->get_data_genset($_POST['plant'], 'all');
        if($genset){
        	$data_row   = array(
                              'norma'    		=> $_POST['norma'],
                              'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'    => $datetime
                         );
            $this->dgeneral->update('tbl_pcs_mgenset', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'kode_pabrik',
                                                                            'value'=>$_POST['plant']
                                                                        )
                                                                    ));
        }else{
        	$data_row   = array(
                                'kode_pabrik'  		=> $_POST['plant'],
                                'norma'       		=> $_POST['norma'],
                                'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_buat'      => $datetime,
                                'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_edit'      => $datetime
                            );
            $this->dgeneral->insert('tbl_pcs_mgenset', $data_row);
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

	private function get_drier(){
		$drier         	= $this->dmasterpcs->get_data_drier($_POST['plant'], 'all');
		echo json_encode($drier);
	}

    private function set_drier($action){
        $id_mdrier		= $this->generate->kirana_decrypt($_POST['id_mdrier']);
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_pcs_mdrier", array(
			                                                                array(
			                                                                    'kolom'=>'id_mdrier',
			                                                                    'value'=>$id_mdrier
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_drier(){
		$datetime       = date("Y-m-d H:i:s");

		$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $drier 	= $this->dmasterpcs->get_data_drier($_POST['plant'], 'all');
        if($drier){
        	$data_row   = array(
                              'norma'    		=> $_POST['norma'],
                              'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'    => $datetime
                         );
            $this->dgeneral->update('tbl_pcs_mdrier', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'kode_pabrik',
                                                                            'value'=>$_POST['plant']
                                                                        )
                                                                    ));
        }else{
        	$data_row   = array(
                                'kode_pabrik'  		=> $_POST['plant'],
                                'norma'       		=> $_POST['norma'],
                                'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_buat'      => $datetime,
                                'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_edit'      => $datetime
                            );
            $this->dgeneral->insert('tbl_pcs_mdrier', $data_row);
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

	private function get_lain(){
		$lain         	= $this->dmasterpcs->get_data_lain($_POST['plant'], 'all');
		echo json_encode($lain);
	}

    private function set_lain($action){
        $id_mlain		= $this->generate->kirana_decrypt($_POST['id_mlain']);
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_pcs_mlain", array(
			                                                                array(
			                                                                    'kolom'=>'id_mlain',
			                                                                    'value'=>$id_mlain
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_lain(){
		$datetime       = date("Y-m-d H:i:s");

		$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $lain 	= $this->dmasterpcs->get_data_lain($_POST['plant'], 'all');
        if($lain){
        	$data_row   = array(
                              'norma'    		=> $_POST['norma'],
                              'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'    => $datetime
                         );
            $this->dgeneral->update('tbl_pcs_mlain', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'kode_pabrik',
                                                                            'value'=>$_POST['plant']
                                                                        )
                                                                    ));
        }else{
        	$data_row   = array(
                                'kode_pabrik'  		=> $_POST['plant'],
                                'norma'       		=> $_POST['norma'],
                                'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_buat'      => $datetime,
                                'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_edit'      => $datetime
                            );
            $this->dgeneral->insert('tbl_pcs_mlain', $data_row);
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

	private function get_lembur(){
		$lembur         	= $this->dmasterpcs->get_data_lembur($_POST['plant'], 'all');
		echo json_encode($lembur);
	}

    private function set_lembur($action){
        $id_mlembur		= $this->generate->kirana_decrypt($_POST['id_mlembur']);
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_pcs_mlembur", array(
			                                                                array(
			                                                                    'kolom'=>'id_mlembur',
			                                                                    'value'=>$id_mlembur
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_lembur(){
		$datetime       = date("Y-m-d H:i:s");

		$this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $lembur 	= $this->dmasterpcs->get_data_lembur($_POST['plant'], 'all');
        if($lembur){
        	$data_row   = array(
                              'norma'    		=> $_POST['norma'],
                              'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'    => $datetime
                         );
            $this->dgeneral->update('tbl_pcs_mlembur', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'kode_pabrik',
                                                                            'value'=>$_POST['plant']
                                                                        )
                                                                    ));
        }else{
        	$data_row   = array(
                                'kode_pabrik'  		=> $_POST['plant'],
                                'norma'       		=> $_POST['norma'],
                                'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_buat'      => $datetime,
                                'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                'tanggal_edit'      => $datetime
                            );
            $this->dgeneral->insert('tbl_pcs_mlembur', $data_row);
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
}