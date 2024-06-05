<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : PCS (Production Cost Simulation)
@author     : Akhmad Syaiful Yamang (8347)
@contributor  : 
      1. Lukman Hakim (7143) 04.07.2019
         CR 1916 dan 1917         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Master extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmaster');
	    $this->load->model('dmastershe');
	}

	public function index(){
		show_404();
	}

	public function baku_mutu(){
		$this->general->check_access();
		$data['title']    		= "Baku Mutu";
		$data['title_form']    	= "";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
        $data['kategori'] 		= $this->dmastershe->get_data_kategori(NULL, NULL);
        $data['jenis'] 			= $this->dmastershe->get_data_jenis();
        $data['parameter'] 		= $this->dmastershe->get_data_parameter();
        $data['bakumutu'] 		= $this->dmastershe->get_data_bakumutu(NULL, NULL, NULL, NULL, NULL, NULL, NULL);
		// $data['jenis']     		= $this->dmastershe->get_data_jns_formula(NULL, 'all');
		$this->load->view("master/baku_mutu", $data);
	}

	public function jenis(){
		$this->general->check_access();
		$data['title']    		= "Jenis";
		$data['title_form']    	= "Mapping Jenis";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
        $data['kategori'] 		= $this->dmastershe->get_data_kategori(NULL, NULL);
        $data['jenis'] 			= $this->dmastershe->get_data_jenis();
        $data['pabrik'] 		= $this->dmaster->get_data_pabrik();
        $data['dtjenis'] 		= $this->dmastershe->get_data_dtjenis(NULL, NULL, NULL, NULL, NULL);
		$this->load->view("master/jenis", $data);
	}
	
	public function mjenis(){
		$this->general->check_access();
		$data['title']    		= "Jenis";
		$data['title_form']    	= "Jenis";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
        $data['jenis'] 			= $this->dmastershe->get_data_jenis();
		$this->load->view("master/mjenis", $data);
	}
	
	public function mparameter(){
		$this->general->check_access();
		$data['title']    		= "Parameter";
		$data['title_form']    	= "Parameter";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
        $data['parameter']		= $this->dmastershe->get_data_parameter();
		$this->load->view("master/mparameter", $data);
	}

	public function kapasitasipal(){
		$this->general->check_access();
		$data['title']    		= "Kapasitas IPAL";
		$data['title_form']    	= "";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
        $data['pabrik'] 		= $this->dmaster->get_data_pabrik();
        $data['kapasitas_ipal'] = $this->dmastershe->get_data_kapasitas_ipal(NULL, NULL, NULL);
		$this->load->view("master/kapasitas_ipal", $data);
	}

	public function parameter(){
		$this->general->check_access();
		$data['title']    		= "Parameter";
		$data['title_form']    	= "";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
        $data['pabrik'] 		= $this->dmaster->get_data_pabrik();
        $data['kategori'] 		= $this->dmastershe->get_data_kategori(NULL, NULL);
        $data['jenis'] 			= $this->dmastershe->get_data_jenis();
        $data['parameter'] 		= $this->dmastershe->get_data_parameter();
        $data['dtparameter'] 	= $this->dmastershe->get_data_dtparameter(NULL, NULL, NULL, NULL, NULL, NULL);
		$this->load->view("master/parameter", $data);
	}

	public function limbah(){
		$this->general->check_access();
		$data['title']    		= "Limbah";
		$data['title_form']    	= "";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
        $data['satuan'] 		= $this->dmastershe->get_data_satuan();
        $data['limbah'] 		= $this->dmastershe->get_data_dtlimbah(NULL, NULL, NULL, NULL);
		$this->load->view("master/limbah", $data);
	}

	public function masasimpan(){
		$this->general->check_access();
		$data['title']    		= "Masa Simpan Limbah";
		$data['title_form']    	= "";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
        $data['pabrik'] 		= $this->dmaster->get_data_pabrik();
        $data['dtmasasimpan'] 	= $this->dmastershe->get_data_dtmasasimpan(NULL, NULL);
		$this->load->view("master/masasimpan", $data);
	}

	public function vendor(){
		$this->general->check_access();
		$data['title']    		= "Transporter";
		$data['title_form']    	= "";
	    $data['module']     	= $this->router->fetch_module();
		$data['user']     		= $this->general->get_data_user();
		// if(base64_decode($this->session->userdata("-ho-")) == 'y'){
  //       	$id_gedung 			= 'ABL1';
        	$data['pabrik'] 	= $this->dmaster->get_data_pabrik();
		// }else{
		// 	$id_gedung			= $this->session->userdata("-id_gedung-");
  //       	$data['pabrik'] 	= $this->dmastershe->get_pabrik($id_gedung);
		// }		
        // $data['vendor'] 		= $this->dmastershe->get_data_vendor(NULL, NULL, NULL);   
        $data['limbah'] 		= $this->dmastershe->get_data_limbah(15, NULL, 'all');
        $data['dtvendor'] 		= $this->dmastershe->get_data_dtvendor(NULL, NULL, 'all');
		
		$this->load->view("master/vendor", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_master_baku_mutu(){
		if(isset($_GET['q'])){
            $data       = $this->dmastershe->get_data_bakumutu(NULL, $_GET['q']);
            $data_json  = array(
                            "total_count" => count($data),
                            "incomplete_results"=>false,
                            "items"=>$data
                          );
            echo json_encode($data_json);
        }
	}

	public function get_data($param){
		switch ($param) {
			case 'mparameter':
				$id  = (isset($_POST['id'])? $_POST['id'] : NULL);
				$this->get_mparameter($id);
				break;
			case 'mjenis':
				$id  = (isset($_POST['id'])? $_POST['id'] : NULL);
				$this->get_mjenis($id);
				break;
			case 'history':
				$id  = (isset($_POST['id'])? $_POST['id'] : NULL);
				$this->get_history($id);
				break;
			case 'bakumutu':
				$this->get_bakumutu();
				break;
			case 'jenis':
				$this->get_jenis();
				break;
			case 'kapasitas_ipal':
				$this->get_kapasitas_ipal();
				break;
			case 'parameter':
				$this->get_parameter();
				break;
			case 'loadlimbah':
				$this->load_limbah();
				break;
			case 'limbah':
				$this->get_limbah();
				break;
			case 'masasimpan':
				$this->get_masasimpan();
				break;
			case 'sumberlimbah':
				$this->get_sumberlimbah();
				break;
			case 'vendor':
				$this->get_vendor();
				break;
			case 'mastervendor':
				$this->get_mastervendor();
				break;
			case 'material':
				$post = $this->input->post_get(NULL, TRUE);
                $param_material = array(
					"connect" => TRUE,
					"search" => @$post['search'],
					// "classification" => @$post['classification'],
					"return" => @$post['return']
				);
				$this->get_material($param_material);
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function set_data($action, $param){
		switch ($param) {
			case 'bakumutu':
				$this->set_bakumutu($action);
				break;
			case 'jenis':
				$this->set_jenis($action);
				break;
			case 'mjenis':
				$this->set_mjenis($action);
				break;
			case 'mparameter':
				$this->set_mparameter($action);
				break;
			case 'kapasitas_ipal':
				$this->set_kapasitas_ipal($action);
				break;
			case 'parameter':
				$this->set_parameter($action);
				break;
			case 'limbah':
				$this->set_limbah($action);
				break;
			case 'vendor':
				$this->set_vendor($action);
				break;
			
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function save($param){
		switch ($param) {
			case 'bakumutu':
				$this->save_bakumutu();
				break;
			case 'mparameter':
				$this->save_mparameter();
				break;
			case 'mjenis':
				$this->save_mjenis();
				break;
			case 'jenis':
				$this->save_jenis();
				break;
			case 'kapasitas_ipal':
				$this->save_kapasitas_ipal();
				break;
			case 'parameter':
				$this->save_parameter();
				break;
			case 'limbah':
				$this->save_limbah();
				break;
			case 'masasimpan':
				$this->save_masasimpan();
				break;
			case 'vendor':
				$this->save_vendor();
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
	private function get_history($id){
		$this->general->connectDbPortal();
		$bakumutu = $this->dmastershe->get_data_bakumutu($id, NULL, NULL, NULL, NULL, NULL, 'all');
		$history  = $this->dmastershe->get_data_bakumutu_log($id, NULL, NULL, NULL, NULL, NULL, 'all');
		$bakumutu[0]->arr_history = $history;
		
		echo json_encode($bakumutu);
	}	

	private function get_bakumutu(){
		$this->general->connectDbPortal();
		$bakumutu = $this->dmastershe->get_data_bakumutu($_POST['id'], NULL, NULL, NULL, NULL, NULL, 'all');
		echo json_encode($bakumutu);
	}	

    private function set_bakumutu($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_bakumutu", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_bakumutu(){
		$datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();

		$bakumutu = $this->dmastershe->get_data_bakumutu($_POST['id'], $_POST['kategori'], $_POST['jenis'], $_POST['parameter'], $_POST['tgl_awal'], $_POST['tgl_akhir'], 'all');
        
        if(isset($_POST['kategori']) && $_POST['kategori'] != "" && (count($bakumutu) == 0 || isset($_POST['id']) && $_POST['id'] != "")){
        	$this->dgeneral->begin_transaction();

        	if(isset($_POST['id']) && $_POST['id'] != ""){
                $data_row   = array(
                                  'tanggal_mulai'     			=> $this->generate->regenerateDateFormat($_POST['tgl_awal']),
                                  'Tanggal_akhir'     			=> $this->generate->regenerateDateFormat($_POST['tgl_akhir']),
                                  'fk_kategori'    				=> $_POST['kategori'],
                                  'fk_jenis'    				=> $_POST['jenis'],
                                  'fk_parameter'    			=> $_POST['parameter'],
                                  'bakumutu_hasilujilimit'    	=> $_POST['limit_uji'],
                                  'bakumutu_hasilujimin'    	=> $_POST['min_uji'],
                                  'bakumutu_hasilujimax'    	=> $_POST['max_uji'],
                                  'bakumutu_bebancemarlimit'    => $_POST['limit_cemar'],
                                  'bakumutu_bebancemarmin'    	=> $_POST['min_cemar'],
                                  'bakumutu_bebancemarmax'    	=> $_POST['max_cemar'],
                                  'regulasi'	  				=> $_POST['regulasi'],
                                  'del'    						=> 1,
	                              'login_edit'        			=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_edit'      			=> $datetime
                             );
                $this->dgeneral->update('tbl_she_bakumutu', $data_row, array( 
                                                                            array(
                                                                                'kolom'=>'id',
                                                                                'value'=>$_POST['id']
                                                                            )
                                                                        ));
				//insert log
                $data_log   = array(
                                  'id' 			   				=> $_POST['id'],
								  'tanggal_mulai'     			=> $this->generate->regenerateDateFormat($_POST['tgl_awal']),
                                  'Tanggal_akhir'     			=> $this->generate->regenerateDateFormat($_POST['tgl_akhir']),
                                  'fk_kategori'    				=> $_POST['kategori'],
                                  'fk_jenis'    				=> $_POST['jenis'],
                                  'fk_parameter'    			=> $_POST['parameter'],
                                  'bakumutu_hasilujilimit'    	=> $_POST['limit_uji'],
                                  'bakumutu_hasilujimin'    	=> $_POST['min_uji'],
                                  'bakumutu_hasilujimax'    	=> $_POST['max_uji'],
                                  'bakumutu_bebancemarlimit'    => $_POST['limit_cemar'],
                                  'bakumutu_bebancemarmin'    	=> $_POST['min_cemar'],
                                  'bakumutu_bebancemarmax'    	=> $_POST['max_cemar'],
								  'regulasi'	  				=> $_POST['regulasi'],
                                  'del'    						=> 1,
	                              'login_buat'        			=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_buat'      			=> $datetime
                             );
				$this->dgeneral->insert('tbl_she_bakumutu_log', $data_log);		
            }else{
                $data_row   = array(
                                  'tanggal_mulai'     			=> $this->generate->regenerateDateFormat($_POST['tgl_awal']),
                                  'Tanggal_akhir'     			=> $this->generate->regenerateDateFormat($_POST['tgl_akhir']),
                                  'fk_kategori'    				=> $_POST['kategori'],
                                  'fk_jenis'    				=> $_POST['jenis'],
                                  'fk_parameter'    			=> $_POST['parameter'],
                                  'bakumutu_hasilujilimit'    	=> $_POST['limit_uji'],
                                  'bakumutu_hasilujimin'    	=> $_POST['min_uji'],
                                  'bakumutu_hasilujimax'    	=> $_POST['max_uji'],
                                  'bakumutu_bebancemarlimit'    => $_POST['limit_cemar'],
                                  'bakumutu_bebancemarmin'    	=> $_POST['min_cemar'],
                                  'bakumutu_bebancemarmax'    	=> $_POST['max_cemar'],
								  'regulasi'	  				=> $_POST['regulasi'],
								  'del'    						=> 1,
	                              'login_buat'        			=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_buat'      			=> $datetime
	                            );
                $this->dgeneral->insert('tbl_she_bakumutu', $data_row);
				//insert log
				$last_id 	= $this->db->insert_id();
                $data_log   = array(
                                  'id' 			   				=> $last_id,
								  'tanggal_mulai'     			=> $this->generate->regenerateDateFormat($_POST['tgl_awal']),
                                  'Tanggal_akhir'     			=> $this->generate->regenerateDateFormat($_POST['tgl_akhir']),
                                  'fk_kategori'    				=> $_POST['kategori'],
                                  'fk_jenis'    				=> $_POST['jenis'],
                                  'fk_parameter'    			=> $_POST['parameter'],
                                  'bakumutu_hasilujilimit'    	=> $_POST['limit_uji'],
                                  'bakumutu_hasilujimin'    	=> $_POST['min_uji'],
                                  'bakumutu_hasilujimax'    	=> $_POST['max_uji'],
                                  'bakumutu_bebancemarlimit'    => $_POST['limit_cemar'],
                                  'bakumutu_bebancemarmin'    	=> $_POST['min_cemar'],
                                  'bakumutu_bebancemarmax'    	=> $_POST['max_cemar'],
								  'regulasi'	  				=> $_POST['regulasi'],
                                  'del'    						=> 1,
	                              'login_buat'        			=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_buat'      			=> $datetime
                             );
				$this->dgeneral->insert('tbl_she_bakumutu_log', $data_log);		
            }

        	if($this->dgeneral->status_transaction() === FALSE){
                $this->dgeneral->rollback_transaction();
                $msg    = "Periksa kembali data yang dimasukkan";
                $sts    = "NotOK";
            }else{
                $this->dgeneral->commit_transaction();
                if(isset($_POST['id']) && $_POST['id'] != ""){
                	$msg    = "Data berhasil diupdate";	
                }else{
                	$msg    = "Data berhasil ditambahkan";	
                }
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

	private function get_mparameter($id){
		$this->general->connectDbPortal();
		$parameter         	= $this->dmastershe->get_data_parameter($id);
		echo json_encode($parameter);
	}
	
	private function get_mjenis($id){
		$this->general->connectDbPortal();
		$jenis         	= $this->dmastershe->get_data_jenis($id);
		echo json_encode($jenis);
	}
	
	private function get_jenis(){
		$this->general->connectDbPortal();
		$jenis		= $this->dmastershe->get_data_dtjenis($_POST['id'], NULL, NULL, NULL, NULL);
		$mjenis  	= $this->dmastershe->get_data_jenis();
		$jenis[0]->arr_mjenis = $mjenis;
		
		echo json_encode($jenis);
	}

    private function set_jenis($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_jenis", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
    private function set_mjenis($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_master_jenis", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
    private function set_mparameter($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_master_parameter", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_jenis(){
		$datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
		if(isset($_POST['id']) && $_POST['id'] != ""){
			$data_row   = array(
							  'fk_pabrik'     				=> $_POST['pabrik'],
							  'fk_kategori'    				=> $_POST['kategori'],
							  'fk_jenis'    				=> $_POST['jenis'],
							  'del'    						=> 1,
							  'login_edit'        			=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'      			=> $datetime
						 );
			$this->dgeneral->update('tbl_she_jenis', $data_row, array( 
																		array(
																			'kolom'=>'id',
																			'value'=>$_POST['id']
																		)
																	));
		}else{
			$arr_jenis	   = $_POST['jenis'];
			foreach($arr_jenis as $jns){
				$jenis 		= $this->dmastershe->get_data_dtjenis(NULL, $_POST['pabrik'], $_POST['kategori'], $jns, 'all');
				//cek data duplikat
				if (count($jenis) != 0){
					$this->dgeneral->rollback_transaction();
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}else{
					$data_row   = array(
									  'fk_pabrik'     				=> $_POST['pabrik'],
									  'fk_kategori'    				=> $_POST['kategori'],
									  'fk_jenis'    				=> $jns,
									  'del'    						=> 1,
									  'login_buat'        			=> base64_decode($this->session->userdata("-id_user-")),
									  'tanggal_buat'      			=> $datetime
									);
					$this->dgeneral->insert('tbl_she_jenis', $data_row);
				}
			}
		}

		if($this->dgeneral->status_transaction() === FALSE){
			$this->dgeneral->rollback_transaction();
			$msg    = "Periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
		}else{
			$this->dgeneral->commit_transaction();
			if(isset($_POST['id']) && $_POST['id'] != ""){
				$msg    = "Data berhasil diupdate";	
			}else{
				$msg    = "Data berhasil ditambahkan";
			}
			$sts    = "OK";
		}	
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}


	//-------------------------------------------------//
	private function save_mjenis(){
		$datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
		$jenis = $this->dmastershe->get_data_jenis($_POST['id']);
		$ck_jenis = $this->dmastershe->get_data_jenis(NULL,$_POST['jenis']);
		//cek data duplikat
		if ((count($ck_jenis) != 0)and($_POST['id']!=$jenis[0]->id)){
			$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		$this->dgeneral->begin_transaction();

		if(isset($_POST['id']) && $_POST['id'] != ""){
			$data_row   = array(
							  'jenis'     	=> $_POST['jenis'],
							  'keterangan'  => $_POST['keterangan'],
							  'del' 		=> 1,
							  'login_edit'	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'=> $datetime
						 );
			$this->dgeneral->update('tbl_she_master_jenis', $data_row, array( 
																		array(
																			'kolom'=>'id',
																			'value'=>$_POST['id']
																		)
																	));
		}else{
			$data_row   = array(
							  'jenis'     	=> $_POST['jenis'],
							  'keterangan'  => $_POST['keterangan'],
							  'del' 		=> 1,
							  'login_buat'  => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_buat'=> $datetime
							);
			$this->dgeneral->insert('tbl_she_master_jenis', $data_row);
		}

		if($this->dgeneral->status_transaction() === FALSE){
			$this->dgeneral->rollback_transaction();
			$msg    = "Periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
		}else{
			$this->dgeneral->commit_transaction();
			if(isset($_POST['id']) && $_POST['id'] != ""){
				$msg    = "Data berhasil diupdate";	
			}else{
				$msg    = "Data berhasil ditambahkan";
			}
			$sts    = "OK";
		}	
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}


	//-------------------------------------------------//
	private function save_mparameter(){
		$datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
		$parameter 		= $this->dmastershe->get_data_parameter($_POST['id']);
		$ck_parameter 	= $this->dmastershe->get_data_parameter(NULL,$_POST['parameter']);
		//cek data duplikat
		if ((count($ck_parameter) != 0)and($_POST['id']!=$parameter[0]->id)){
			$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		$this->dgeneral->begin_transaction();

		if(isset($_POST['id']) && $_POST['id'] != ""){
			$data_row   = array(
							  'parameter'  	=> $_POST['parameter'],
							  'keterangan'  => $_POST['keterangan'],
							  'del' 		=> 1,
							  'login_edit'	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'=> $datetime
						 );
			$this->dgeneral->update('tbl_she_master_parameter', $data_row, array( 
																		array(
																			'kolom'=>'id',
																			'value'=>$_POST['id']
																		)
																	));
		}else{
			$data_row   = array(
							  'parameter'  	=> $_POST['parameter'],
							  'keterangan'  => $_POST['keterangan'],
							  'del' 		=> 1,
							  'login_buat'  => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_buat'=> $datetime
							);
			$this->dgeneral->insert('tbl_she_master_parameter', $data_row);
		}

		if($this->dgeneral->status_transaction() === FALSE){
			$this->dgeneral->rollback_transaction();
			$msg    = "Periksa kembali data yang dimasukkan";
			$sts    = "NotOK";
		}else{
			$this->dgeneral->commit_transaction();
			if(isset($_POST['id']) && $_POST['id'] != ""){
				$msg    = "Data berhasil diupdate";	
			}else{
				$msg    = "Data berhasil ditambahkan";
			}
			$sts    = "OK";
		}	
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}


	//-------------------------------------------------//

	private function get_kapasitas_ipal(){
		$this->general->connectDbPortal();
		$kapasitas_ipal = $this->dmastershe->get_data_kapasitas_ipal($_POST['id'], NULL, NULL);
		echo json_encode($kapasitas_ipal);
	}

    private function set_kapasitas_ipal($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_kapasitas_ipal", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_kapasitas_ipal(){
		$datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
		$kapasitas_ipal = $this->dmastershe->get_data_kapasitas_ipal($_POST['pabrik'], 'all');
        $this->general->closeDb();
        
        
        if(isset($_POST['pabrik']) && $_POST['pabrik'] != "" && (count($kapasitas_ipal) == 0 || isset($_POST['id']) && $_POST['id'] != "")){
            $this->general->connectDbPortal();
        	$this->dgeneral->begin_transaction();

        	if(isset($_POST['id']) && $_POST['id'] != ""){
                $data_row   = array(
                                  'fk_pabrik'     				=> $_POST['pabrik'],
                                  'kapasitas_ipal'    			=> $_POST['kapasitas_ipal'],
	                              'login_edit'        			=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_edit'      			=> $datetime
                             );
                $this->dgeneral->update('tbl_she_kapasitas_ipal', $data_row, array( 
                                                                            array(
                                                                                'kolom'=>'id',
                                                                                'value'=>$_POST['id']
                                                                            )
                                                                        ));
            }else{
                $data_row   = array(
                                  'fk_pabrik'     				=> $_POST['pabrik'],
                                  'kapasitas_ipal'    			=> $_POST['kapasitas_ipal'],
                                  'del'    						=> 1,
	                              'login_buat'        			=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_buat'      			=> $datetime
	                            );
                $this->dgeneral->insert('tbl_she_kapasitas_ipal', $data_row);
            }

        	if($this->dgeneral->status_transaction() === FALSE){
                $this->dgeneral->rollback_transaction();
                $msg    = "Periksa kembali data yang dimasukkan";
                $sts    = "NotOK";
            }else{
                $this->dgeneral->commit_transaction();
                if(isset($_POST['id']) && $_POST['id'] != ""){
                	$msg    = "Data berhasil diupdate";	
                }else{
                	$msg    = "Data berhasil ditambahkan";
                }
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

	private function get_parameter(){
		$this->general->connectDbPortal();
		$parameter = $this->dmastershe->get_data_dtparameter($_POST['id'], NULL, NULL, NULL, NULL, 'all');
		echo json_encode($parameter);
	}

    private function set_parameter($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_parameter", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_parameter(){
		$datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
		$parameter = $this->dmastershe->get_data_dtparameter(NULL, $_POST['pabrik'], $_POST['kategori'], $_POST['jenis'], $_POST['parameter'], NULL);
        $this->general->closeDb();
        
        
        if(isset($_POST['pabrik']) && $_POST['pabrik'] != "" && (count($parameter) == 0 || isset($_POST['id']) && $_POST['id'] != "")){
            $this->general->connectDbPortal();
        	$this->dgeneral->begin_transaction();

        	if(isset($_POST['id']) && $_POST['id'] != ""){
                $data_row   = array(
                                  'fk_pabrik'     				=> $_POST['pabrik'],
                                  'fk_kategori'     			=> $_POST['kategori'],
                                  'fk_jenis'     				=> $_POST['jenis'],
                                  'fk_parameter'     			=> $_POST['parameter'],
	                              'login_edit'        			=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_edit'      			=> $datetime
                             );
                $this->dgeneral->update('tbl_she_parameter', $data_row, array( 
                                                                            array(
                                                                                'kolom'=>'id',
                                                                                'value'=>$_POST['id']
                                                                            )
                                                                        ));
            }else{
                $data_row   = array(
                                  'fk_pabrik'     				=> $_POST['pabrik'],
                                  'fk_kategori'     			=> $_POST['kategori'],
                                  'fk_jenis'     				=> $_POST['jenis'],
                                  'fk_parameter'     			=> $_POST['parameter'],
	                              'del'        					=> 1,
	                              'login_buat'        			=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_buat'      			=> $datetime
	                            );
                $this->dgeneral->insert('tbl_she_parameter', $data_row);
            }

        	if($this->dgeneral->status_transaction() === FALSE){
                $this->dgeneral->rollback_transaction();
                $msg    = "Periksa kembali data yang dimasukkan";
                $sts    = "NotOK";
            }else{
                $this->dgeneral->commit_transaction();
                if(isset($_POST['id']) && $_POST['id'] != ""){
                	$msg    = "Data berhasil diupdate";
                }else{
                	$msg    = "Data berhasil ditambahkan";
                }
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

	private function get_sumberlimbah(){
        $this->general->connectDbPortal();
		$sumberlimbah = $this->dmastershe->get_data_sumber_limbah();
		echo json_encode($sumberlimbah);
	}

	private function get_limbah(){
        $this->general->connectDbPortal();

        if(!empty($_POST['jenislimbah']) || $_POST['jenislimbah'] != ""){
        	$limbah = $this->dmastershe->get_data_dtlimbah(NULL, NULL, $_POST['jenislimbah'], NULL);
        }elseif(!empty($_POST['pabrik']) || $_POST['pabrik'] != ""){
        	$limbah = $this->dmastershe->get_data_dtlimbahpabrik(NULL, $_POST['pabrik'], NULL, NULL, NULL);
        }
		echo json_encode($limbah);
	}

	private function load_limbah(){
        $this->general->connectDbPortal();
		$limbah = $this->dmastershe->get_data_dtlimbahpabrik(NULL, $_POST['pabrik'], NULL, NULL);
		echo json_encode($limbah);
	}

    private function set_limbah($action){
		$datetime       = date("Y-m-d H:i:s");
        // $id = $_POST['id'];
        $jenislimbah = $_POST['jenislimbah'];
        $this->general->connectDbPortal();
		$delete     = $this->general->set($action, "tbl_she_limbah", array(
																		array(
																			'kolom'=>'jenis_limbah',
																			'value'=>$jenislimbah
																		)
																	));
		
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_limbah(){
		$datetime       = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
		$limbah = $this->dmastershe->get_data_dtlimbah(NULL, NULL, $_POST['jenislimbah'], 'all');
        $this->general->closeDb();
                
        if(isset($_POST['id']) && $_POST['id'] != ""){
            $this->general->connectDbPortal();
        	$this->dgeneral->begin_transaction();

			$pabrik = $this->dmaster->get_data_pabrik();
			foreach ($pabrik as $dt) {
				$ck_limbah = $this->dmastershe->get_data_dtlimbah(NULL, $dt->id_pabrik, $_POST['jenislimbah'], 'all');
				if(count($ck_limbah) == 0){
					$data_row   = array(
									  'fk_pabrik'    				=> $dt->id_pabrik,
									  'jenis_limbah'      			=> $_POST['jenislimbah'],
									  'kode_material'    			=> $_POST['kodematerial'],
									  'kode_reglimbah'    			=> $_POST['kodelimbahregulasi'],
									  'fk_satuan'    				=> $_POST['satuan'],
									  'konversi_ton'    			=> $_POST['konversiton'],
									  'fk_satuan_pengiriman'		=> $_POST['satuanpengiriman'],
									  'konversi_satuan_pengiriman'	=> $_POST['konversisatuanpengiriman'],
									  'form_log_book_number'		=> $_POST['formlog'],
									  'sumber_limbah'    			=> '-',
									  'login_buat'        			=> base64_decode($this->session->userdata("-id_user-")),
									  'tanggal_buat'      			=> $datetime,
									  'del'      					=> 1,
									);
					$this->dgeneral->insert('tbl_she_limbah', $data_row);
				}else{
					$data_row   = array(
									  'jenis_limbah'      			=> $_POST['jenislimbah'],
									  'kode_material'    			=> $_POST['kodematerial'],
									  'kode_reglimbah'    			=> $_POST['kodelimbahregulasi'],
									  'fk_satuan'    				=> $_POST['satuan'],
									  'konversi_ton'    			=> $_POST['konversiton'],
									  'fk_satuan_pengiriman'		=> $_POST['satuanpengiriman'],
									  'konversi_satuan_pengiriman'	=> $_POST['konversisatuanpengiriman'],
									  'form_log_book_number'		=> $_POST['formlog'],
									  'sumber_limbah'    			=> '-',
									  'login_edit'        			=> base64_decode($this->session->userdata("-id_user-")),
									  'tanggal_edit'      			=> $datetime
								 );
					$this->dgeneral->update('tbl_she_limbah', $data_row, array( 
																				array(
																					'kolom'=>'jenis_limbah',
																					'value'=>$_POST['jenislimbah']
																				)
																			));
				}
			}

        }else{
        	if(count($limbah) == 0){
	            $this->general->connectDbPortal();
	        	$this->dgeneral->begin_transaction();

        		$pabrik = $this->dmaster->get_data_pabrik();
	        	foreach ($pabrik as $dt) {
		        	$data_row   = array(
		                              'fk_pabrik'    				=> $dt->id_pabrik,
		                              'jenis_limbah'      			=> $_POST['jenislimbah'],
		                              'kode_material'    			=> $_POST['kodematerial'],
		                              'kode_reglimbah'    			=> $_POST['kodelimbahregulasi'],
		                              'fk_satuan'    				=> $_POST['satuan'],
		                              'konversi_ton'    			=> $_POST['konversiton'],
		                              'fk_satuan_pengiriman'		=> $_POST['satuanpengiriman'],
		                              'konversi_satuan_pengiriman'	=> $_POST['konversisatuanpengiriman'],
		                              'form_log_book_number'		=> $_POST['formlog'],
		                              'sumber_limbah'    			=> '-',
		                           	  'login_buat'        			=> base64_decode($this->session->userdata("-id_user-")),
		                              'tanggal_buat'      			=> $datetime,
		                              'del'      					=> 1,
		                            );
		            $this->dgeneral->insert('tbl_she_limbah', $data_row);
	        	}
	        }else{
	            $msg    = "Periksa kembali data yang dimasukkan";
	            $sts    = "NotOK";
	            goto finish;
	        }
        }

        if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            if(isset($_POST['id']) && $_POST['id'] != ""){
            	$msg    = "Data berhasil diupdate";	
            }else{
            	$msg    = "Data berhasil ditambahkan";
            }
            $sts    = "OK";
        }	
	    
	    finish:{
	        $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
	    }
	}
	
	private function save_limbah_old(){
		$datetime       = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
		$limbah = $this->dmastershe->get_data_dtlimbah(NULL, NULL, $_POST['jenislimbah'], 'all');
        $this->general->closeDb();
                
        if(isset($_POST['id']) && $_POST['id'] != ""){
            $this->general->connectDbPortal();
        	$this->dgeneral->begin_transaction();

        	$data_row   = array(
                              'jenis_limbah'      			=> $_POST['jenislimbah'],
                              'kode_material'    			=> $_POST['kodematerial'],
                              'kode_reglimbah'    			=> $_POST['kodelimbahregulasi'],
                              'fk_satuan'    				=> $_POST['satuan'],
                              'konversi_ton'    			=> $_POST['konversiton'],
                              'fk_satuan_pengiriman'		=> $_POST['satuanpengiriman'],
                              'konversi_satuan_pengiriman'	=> $_POST['konversisatuanpengiriman'],
                              'form_log_book_number'		=> $_POST['formlog'],
                              'sumber_limbah'    			=> '-',
                              'login_edit'        			=> base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'      			=> $datetime
                         );
            $this->dgeneral->update('tbl_she_limbah', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'jenis_limbah',
                                                                            'value'=>$_POST['jenislimbah']
                                                                        )
                                                                    ));
        }else{
        	if(count($limbah) == 0){
	            $this->general->connectDbPortal();
	        	$this->dgeneral->begin_transaction();

        		$pabrik = $this->dmaster->get_data_pabrik();
	        	foreach ($pabrik as $dt) {
		        	$data_row   = array(
		                              'fk_pabrik'    				=> $dt->id_pabrik,
		                              'jenis_limbah'      			=> $_POST['jenislimbah'],
		                              'kode_material'    			=> $_POST['kodematerial'],
		                              'kode_reglimbah'    			=> $_POST['kodelimbahregulasi'],
		                              'fk_satuan'    				=> $_POST['satuan'],
		                              'konversi_ton'    			=> $_POST['konversiton'],
		                              'fk_satuan_pengiriman'		=> $_POST['satuanpengiriman'],
		                              'konversi_satuan_pengiriman'	=> $_POST['konversisatuanpengiriman'],
		                              'form_log_book_number'		=> $_POST['formlog'],
		                              'sumber_limbah'    			=> '-',
		                           	  'login_buat'        			=> base64_decode($this->session->userdata("-id_user-")),
		                              'tanggal_buat'      			=> $datetime,
		                              'del'      					=> 1,
		                            );
		            $this->dgeneral->insert('tbl_she_limbah', $data_row);
	        	}
	        }else{
	            $msg    = "Periksa kembali data yang dimasukkan";
	            $sts    = "NotOK";
	            goto finish;
	        }
        }

        if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            if(isset($_POST['id']) && $_POST['id'] != ""){
            	$msg    = "Data berhasil diupdate";	
            }else{
            	$msg    = "Data berhasil ditambahkan";
            }
            $sts    = "OK";
        }	
	    
	    finish:{
	        $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
	    }
	}
	

	//-------------------------------------------------//


	private function get_masasimpan(){
        $this->general->connectDbPortal();
		$masasimpan = $this->dmastershe->get_data_dtmasasimpan($_POST['id']);
		echo json_encode($masasimpan);
	}

    private function set_masasimpan($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_limbah", array(
			                                                                array(
			                                                                    'kolom'=>'fk_pabrik',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_masasimpan(){
		$datetime       = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
    	$this->dgeneral->begin_transaction();
        
        if(isset($_POST['id']) && $_POST['id'] != ""){
        	$data_row   = array(
                              'masa_simpan'    				=> $_POST['masasimpan'],
                              'login_edit'        			=> base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'      			=> $datetime
                         );
            $this->dgeneral->update('tbl_she_limbah', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'id',
                                                                            'value'=>$_POST['id']
                                                                        )
                                                                    ));
        }

        if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            $msg    = "Data berhasil disimpan";
            $sts    = "OK";
        }	
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//	

	private function get_mastervendor(){
		$vendor         	= $this->dmastershe->get_data_mastervendor(NULL, $_POST['pabrik']);
		echo json_encode($vendor);
	}

	private function get_vendor(){
		$this->general->connectDbPortal();
		// if(base64_decode($this->session->userdata("-ho-")) == 'y'){
  //       	$id_gedung 			= 'ABL1';
  //       	$data['pabrik'] 	= $this->dmastershe->get_pabrik('ABL1');
		// }else{
		// 	$id_gedung			= $this->session->userdata("-id_gedung-");
  //       	$data['pabrik'] 	= $this->dmastershe->get_pabrik($id_gedung);
		// }		
		if(!empty($_POST['id']) || $_POST['id'] != ""){
			$vendor         	= $this->dmastershe->get_data_vendor($_POST['id'], NULL, NULL);
		}elseif(!empty($_POST['pabrik'] || $_POST['pabrik'] != "")){
			$vendor         	= $this->dmastershe->get_data_vendor(NULL, $_POST['pabrik'], NULL);
		}
		echo json_encode($vendor);
	}

    private function set_vendor($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_vendor", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function save_vendor(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();

		$exppengumpul = (empty($_POST['exppengumpul']))?NULL:$this->generate->regenerateDateFormat($_POST['exppengumpul']);
		$expbebascemar2 = (empty($_POST['expbebascemar2']))?NULL:$this->generate->regenerateDateFormat($_POST['expbebascemar2']);
		$expbebascemar3 = (empty($_POST['expbebascemar3']))?NULL:$this->generate->regenerateDateFormat($_POST['expbebascemar3']);
		$exppemanfaat = (empty($_POST['exppemanfaat']))?"":$this->generate->regenerateDateFormat($_POST['exppemanfaat']);
		$exppengumpulpemanfaat = (empty($_POST['exppengumpulpemanfaat']))?"":$this->generate->regenerateDateFormat($_POST['exppengumpulpemanfaat']);

		$jenislimbah_pengumpul = "";
		foreach ($_POST['jenislimbah_pengumpul'] as $keykumpul => $limbahkumpul) {
			$jenislimbah_pengumpul .= ".".$limbahkumpul; 
		}
		$jenislimbah_pengumpul .= "."; 

		$jenislimbah_mou = "";
		foreach ($_POST['jenislimbah_mou'] as $keymou => $limbahmou) {
			$jenislimbah_mou .= ".".$limbahmou; 
		}
		$jenislimbah_mou .= "."; 

		$jenislimbah_rekom = "";
		foreach ($_POST['jenislimbah_rekom'] as $keyrekom => $limbahrekom) {
			$jenislimbah_rekom .= ".".$limbahrekom; 
		}
		$jenislimbah_rekom .= "."; 

		$jenislimbah_hubdar = "";
		foreach ($_POST['jenislimbah_hubdar'] as $keyhubdar => $limbahhubdar) {
			$jenislimbah_hubdar .= ".".$limbahhubdar; 
		}
		$jenislimbah_hubdar .= "."; 

        $this->dgeneral->begin_transaction();


        $uploaded = array();
        $uploadError = array();
        $data = $_FILES;

		// var_dump($data); exit();

        if (isset($_FILES)) {
            $uploaddir  = realpath('./').'/assets/file/she/mastervendor';
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777, true);
            }
            $config['upload_path']          = $uploaddir;
            $config['allowed_types']        = 'pdf';
            // $config['max_size']             = 100;
            // $config['max_width']            = 1024;
            // $config['max_height']           = 768;

            $this->load->library('upload', $config);
    	    // $datalampiran = array();

            foreach ($_FILES as $input_name => $file) {
                if ( ! $this->upload->do_upload($input_name))
                {
                    // $uploadError= $this->upload->display_errors();
                    $uploaded[$input_name] = "";

                }else{
                    $upload_data = $this->upload->data();
                    $uploaded[$input_name] = 'assets/file/she/mastervendor/'.$upload_data['file_name'];
                }      
            }
        }


        // echo ($_FILES['file_pihak_ketiga_spbp2']); exit();

        $vendor 		= $this->dmastershe->get_data_vendor(NULL, $_POST['pabrik'], $_POST['kodevendor']);
        $exists_vendor 	= "";
        $namavendor 	= $this->dmastershe->get_nama_vendor($_POST['vendor']);

        if($_POST['id'] != ""){
        	$data_row   = array(
                              'nama_vendor'      			=> $namavendor[0]->NAME1,
                              'nama_pengumpul'      		=> $_POST['namapengumpul'],
                              'nama_pemanfaat'      		=> $_POST['namapemanfaat'],
                              'izin_kumpul_jenislimbah' 	=> $jenislimbah_pengumpul,
                              'izin_kumpul_expdate'    		=> $exppengumpul,
                              'pihak_ketiga_jenislimbah'    => $jenislimbah_mou,
                              'pihak_ketiga_expdate'    	=> $this->generate->regenerateDateFormat($_POST['expmou']),
                              'pihak_ketiga_spbp_expdate'   => $this->generate->regenerateDateFormat($_POST['expbebascemar']),
                              'pihak_ketiga_spbp_expdate2'  => $expbebascemar2,
                              'pihak_ketiga_spbp_expdate3'  => $expbebascemar3,
                              'pemanfaat_expdate'    		=> $exppemanfaat,
                              'pengumpulpemanfaat_expdate'  => $exppengumpulpemanfaat,
                              'angkut_klhk_jenislimbah'    	=> $jenislimbah_rekom,
                              'angkut_klhk_expdate'    		=> $this->generate->regenerateDateFormat($_POST['exprekom']),
                              'angkut_dhd_jenislimbah'  	=> $jenislimbah_hubdar,
                              'angkut_dhd_expdate'    		=> $this->generate->regenerateDateFormat($_POST['exphubdar']),
                              'angkut_dhd_spbp_expdate'    	=> $this->generate->regenerateDateFormat($_POST['exphubdarspbp']),
                              'login_edit'    				=> base64_decode($this->session->userdata("-id_user-")),
                              'tanggal_edit'    			=> $datetime
                         );
        	$datafile = "";
        	$datafile = array();
        	$data = array_merge($data, $uploaded);
        	foreach ($data as $keydata => $value) {
        		if($value != ""){
        			$datafile[$keydata] = $value;
        		}
        	}
        	$data_row = array_merge($data_row, $datafile);

            $this->dgeneral->update('tbl_she_vendor', $data_row, array( 
                                                                        array(
                                                                            'kolom'=>'id',
                                                                            'value'=>$_POST['id']
                                                                        )
                                                                    ));
        }else{
			foreach ($vendor as $vendor) {
				if($vendor->kode_vendor != "" || $vendor->kode_vendor != null){ 
					$exists_vendor = $vendor->kode_vendor;
				}
			} 
        	if($exists_vendor == ""){
	        	$data_row   = array(
	                              'kode_vendor'    				=> $_POST['kodevendor'],
	                              'fk_pabrik'    				=> $_POST['pabrik'],
	                              'nama_vendor'      			=> $namavendor[0]->NAME1,
	                              'nama_pengumpul'      		=> $_POST['namapengumpul'],
	                              'nama_pemanfaat'      		=> $_POST['namapemanfaat'],
	                              'izin_kumpul_jenislimbah' 	=> $jenislimbah_pengumpul,
	                              'izin_kumpul_expdate'    		=> $exppengumpul,
	                              'pihak_ketiga_jenislimbah'    => $jenislimbah_mou,
	                              'pihak_ketiga_expdate'    	=> $this->generate->regenerateDateFormat($_POST['expmou']),
	                              'pihak_ketiga_spbp_expdate'   => $this->generate->regenerateDateFormat($_POST['expbebascemar']),
	                              'pihak_ketiga_spbp_expdate2'  => $expbebascemar2,
	                              'pihak_ketiga_spbp_expdate3'  => $expbebascemar3,
	                              'pemanfaat_expdate'    		=> $exppemanfaat,
	                              'pengumpulpemanfaat_expdate'  => $exppengumpulpemanfaat,
	                              'angkut_klhk_jenislimbah'    	=> $jenislimbah_rekom,
	                              'angkut_klhk_expdate'    		=> $this->generate->regenerateDateFormat($_POST['exprekom']),
	                              'angkut_dhd_jenislimbah'  	=> $jenislimbah_hubdar,
	                              'angkut_dhd_expdate'    		=> $this->generate->regenerateDateFormat($_POST['exphubdar']),
	                              'angkut_dhd_spbp_expdate'    	=> $this->generate->regenerateDateFormat($_POST['exphubdarspbp']),
	                              'del'    						=> 1,
	                              'login_buat'    				=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_buat'    			=> $datetime
	                            );
	        	$data_row = array_merge($data_row, $data, $uploaded);
	        	        	// var_dump($uploaded); die();

	            $this->dgeneral->insert('tbl_she_vendor', $data_row);
	        }
        }

        if($exists_vendor != "" || !empty($exists_vendor)){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan, data Vendor sudah ada";
            $sts    = "NotOK";
        }elseif($this->dgeneral->status_transaction() === FALSE || count($uploadError)>0){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            if($_POST['id'] != ""){
            	$msg    = "Data berhasil diupdate";	
            }else{
            	$msg    = "Data berhasil ditambahkan";	
            }
            $sts    = "OK";
        }	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	private function get_material($param = NULL)
    {
		$result = $this->dmaster->get_data_material($param);

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
	//-------------------------------------------------//


}