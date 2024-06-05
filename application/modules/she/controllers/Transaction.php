	<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  	: SHE
@author     	: Syah Jadianto (8604)
@contributor  	: 
      1. Airiza Yuddha (7849) 27 okt 2020
         add kategori on airlimbah bulanan dan harian         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Transaction extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		
	    $this->load->model('dmaster');
	    $this->load->model('dmastershe');
	    $this->load->model('dtransactionshe');
	    $this->load->model('dreportshe');
	}

	public function index(){
		show_404();
	}
	public function get($param = NULL) {
		switch ($param) {
			case 'produksi_sir':
				$id_pabrik   = ((isset($_POST['id_pabrik'])and($_POST['id_pabrik']!=0)) ? $_POST['id_pabrik'] : NULL);
				$tanggal 	 = (isset($_POST['tanggal'])? $_POST['tanggal'] : NULL);
				$this->get_produksi_sir(NULL, $id_pabrik, $tanggal);
				break;
			
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}
	
	private function get_produksi_sir($array = NULL, $id_pabrik = NULL, $tanggal = NULL) {
		$produksi_sir	= $this->dtransactionshe->get_data_produksi_sir("open", $id_pabrik, $tanggal);
		if ($array) {
			return $produksi_sir;
		} else {
			echo json_encode($produksi_sir);
		}
	}

	public function limbahair($param){
		switch ($param) {
			case 'bulanan':
				$this->tx_airlimbah_bulanan();
				break;
			case 'harian':
				$this->tx_airlimbah_harian();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function limbahudara($param){
		switch ($param) {
			case 'kualitas':
				$this->tx_kualitasudara();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function limbahb3($param){
		switch ($param) {
			case 'input':
				$this->tx_limbah_b3();
				break;
			case 'perpanjangan':
				$this->tx_perpanjang_masa_b3();
				break;
			case 'view':
				$this->view_tx_limbah_b3();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	private function tx_airlimbah_bulanan(){
		$this->general->connectDbPortal();
		$this->general->check_access();
		$data['title']    			= "Input Data Air Limbah Bulanan";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        // $data['pabrik'] 			= $this->dmaster->get_data_pabrik();

		if(base64_decode($this->session->userdata("-ho-")) == 'y'){
        	$idpabrik 						= $this->dmastershe->get_pabrik('ABL1');
		}else{
			$id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
        	$idpabrik 						= $this->dmastershe->get_pabrik($id_gedung);
		}
        $data['pabrik'] 					= $this->dmastershe->get_pabrik();

        // $data['jenis'] 				= $this->dmastershe->get_data_jenis();
        $data['jenis'] 						= $this->dmastershe->get_data_set_jenis(NULL, 1); //kategori 1 air limbah kategori

        $data['limbah_air_bulanan'] 		= $this->dtransactionshe->get_data_limbah_air_bulanan(NULL, $idpabrik[0]->id_pabrik, NULL, NULL);
        $data['kategori'] 					= $this->dmastershe->get_data_kategori_filter(array(1,7));
        
		$this->load->view("limbah_air/airlimbah_bulanan", $data);
	}

	private function tx_airlimbah_harian(){
		$this->general->check_access();
		$data['title']    					= "Input Data Air Limbah Harian";
		$data['title_form']    				= "";
        $filterpabrik    					= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
        $filterperiode    					= (empty($_POST['filterperiode']))? date('m.Y') : $_POST['filterperiode'];
        $filterkategori    					= (empty($_POST['filterkategori']))? 0 : $_POST['filterkategori'];
	    $data['module']     				= $this->router->fetch_module();
		$data['user']     					= $this->general->get_data_user();
		// $data['pabrik'] 					= $this->dmaster->get_data_pabrik();

		if(base64_decode($this->session->userdata("-ho-")) == 'y'){
        	$idpabrik 						= $this->dmastershe->get_pabrik('ABL1');
		}else{
			$id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
        	$idpabrik 						= $this->dmastershe->get_pabrik($id_gedung);
		}
        $data['pabrik'] 					= $this->dmastershe->get_pabrik();
        $data['kategori'] 					= $this->dmastershe->get_data_kategori_filter(array(2,8));

        $data['limbah_air_harian'] 			= $this->dtransactionshe->get_data_limbah_air_harian(NULL, ($filterpabrik == 0)?$idpabrik[0]->id_pabrik:$filterpabrik, $filterperiode, $filterkategori);
        $data['limbah_air_harian_ipal'] 	= $this->dtransactionshe->get_limbah_air_harian_ipal(($filterpabrik ==0 )?$idpabrik[0]->id_pabrik:$filterpabrik, $filterperiode, $filterkategori);
        // for filter selected
        $data['filterpabrik']				= $filterpabrik;
        $data['filterperiode']				= $filterperiode;
        $data['filterkategori']				= $filterkategori;
		//lha
		// $data['produksi_sir'] 				= $this->dtransactionshe->get_data_produksi_sir(NULL, ($filterpabrik == 0)?$idpabrik[0]->id_pabrik:$filterpabrik, $filterperiode, $filterkategori);
		$this->load->view("limbah_air/airlimbah_harian", $data);
	}

	public function tx_kualitasudara(){
		$this->general->check_access();
		$data['title']    					= "Input Data Kualitas Udara";
		$data['title_form']    				= "";
        $filterpabrik    					= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
        $filterkategori    					= (empty($_POST['filterkategori']))? 0 : $_POST['filterkategori'];
		$filterjenis    					= (empty($_POST['filterjenis']))? 0 : $_POST['filterjenis'];
		$from    							= (empty($_POST['from']))? "" : $_POST['from'];
		$to    								= (empty($_POST['to']))? "" : $_POST['to'];

	    $data['module']     				= $this->router->fetch_module();
		$data['user']     					= $this->general->get_data_user();
        // $data['pabrik'] 					= $this->dmaster->get_data_pabrik();

		// if(base64_decode($this->session->userdata("-ho-")) == 'y'){
        	// $idpabrik 						= $this->dmastershe->get_pabrik('ABL1');
		// }else{
			// $id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
        	// $idpabrik 						= $this->dmastershe->get_pabrik($id_gedung);
		// }
        // $data['pabrik'] 					= $this->dmastershe->get_pabrik($idpabrik[0]->kode);
		$data['pabrik'] 					= $this->dmastershe->get_pabrik();
		
        $data['kategori'] 					= $this->dtransactionshe->get_data_kategori_kualitasudara();
        $data['jenis'] 						= $this->dmastershe->get_data_jenis();
        $data['limbah_udara'] 				= $this->dtransactionshe->get_data_limbah_udara(NULL, $filterpabrik, $filterkategori, $filterjenis, $from, $to, NULL);
        // $data['limbah_udara'] 				= $this->dtransactionshe->get_data_limbah_udara(NULL, ($filterpabrik==0)?$idpabrik[0]->id_pabrik:$filterpabrik, $filterkategori, $filterjenis, $from, $to, NULL);
        // $data['limbah_air_harian_ipal'] 	= $this->dtransactionshe->get_limbah_air_harian_ipal(6, '2016-08');
        // echo $filterpabrik; exit();

        $data['filterpabrik']				= $filterpabrik;
        $data['filterkategori']				= $filterkategori;
        $data['filterjenis']				= $filterjenis;
        $data['from']						= $from;
        $data['to']							= $to;
		$this->load->view("limbah_udara/kualitasudara", $data);
	}

	public function tx_limbah_b3(){
		$this->general->check_access();
		$data['title']    					= "Input Data Limbah B3";
		$data['title_form']    				= "";
	    $data['module']     				= $this->router->fetch_module();
		$data['user']     					= $this->general->get_data_user();
		
		$filter_pabrik 						= (empty($_POST['filter_pabrik']))? NULL : $_POST['filter_pabrik']; 
		$filter_status 						= (empty($_POST['filter_status']))? 0 : $_POST['filter_status']; 
		// $status 							= (empty($_POST['status']))? 0 : $_POST['status']; 

        $data['status']    					= $filter_status;
        $data['pabrik'] 					= $this->dmastershe->get_pabrik(NULL);

        $data['limbah'] 					= $this->dmastershe->get_data_limbah($filter_pabrik, NULL, 'ALL');
        $data['sumberlimbah'] 				= $this->dmastershe->get_data_sumber_limbah();
        $data['limbah_b3'] 					= $this->dtransactionshe->get_data_limbah_b3(NULL, $filter_pabrik, $filter_status);
        // $data['limbah_air_harian_ipal'] 	= $this->dtransactionshe->get_limbah_air_harian_ipal(6, '2016-08');
		$this->load->view("limbah_b3/limbahb3_inputdata", $data);
	}

	public function tx_perpanjang_masa_b3(){
		$this->general->check_access();
		$data['title']    					= "Input Data Perpanjangan Masa Simpan Limbah B3";
		$data['title_form']    				= "";
	    $data['module']     				= $this->router->fetch_module();
		$data['user']     					= $this->general->get_data_user();
        // $data['pabrik'] 					= $this->dmaster->get_data_pabrik();
        $data['jenis'] 						= $this->dmastershe->get_data_jenis();
        $plant								= base64_decode($this->session->userdata("-id_gedung-"));
        // if(base64_decode($this->session->userdata("-ho-")) == "y"){
        // 	$data['idpabrik'] 				= 15;
        // }else{
        // 	$id 							= $this->dmastershe->get_data_idpabrik($plant);
        // 	$data['idpabrik'] 				= $id[0]->id_pabrik;
        // }
		if(base64_decode($this->session->userdata("-ho-")) == 'y'){
        	$idpabrik 						= $this->dmastershe->get_pabrik('ABL1');
		}else{
			$id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
        	$idpabrik 						= $this->dmastershe->get_pabrik($id_gedung);
		}
		$data['pabrik'] 					= $this->dmastershe->get_pabrik($idpabrik[0]->kode);
        $data['masa_b3'] 					= $this->dtransactionshe->get_data_perpanjang_masa_b3(NULL, $idpabrik[0]->id_pabrik);

		$this->load->view("limbah_b3/perpanjang_masa_b3", $data);
	}

	public function view_tx_limbah_b3(){
		$this->general->check_access();
        $filterpabrik    					= (empty($_POST['filterpabrik']))? array() : $_POST['filterpabrik'];
        $data['filterpabrik']    			= $filterpabrik;
		$string_pabrik 						= ""; 
		if(isset($filterpabrik)){
			foreach ($filterpabrik as $key => $pabrik) {
				$string_pabrik = $string_pabrik.$pabrik.",";
			}
			$string_pabrik = rtrim($string_pabrik,",");
			$string_pabrik = str_replace(",", "','", $string_pabrik);
		}
		$filterfrom    						= (empty($_POST['filterfrom']))? "" : $_POST['filterfrom'];
		$filterto    						= (empty($_POST['filterto']))? "" : $_POST['filterto'];
		$data['title']    					= "View Data Transaksi Limbah B3";
		$data['title_form']    				= "";
	    $data['module']     				= $this->router->fetch_module();
		$data['user']     					= $this->general->get_data_user();
		if(base64_decode($this->session->userdata("-ho-")) == 'y'){
        	$data['pabrik'] 				= $this->dmastershe->get_pabrik(NULL);
		}else{
			$id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
        	$idpabrik 						= $this->dmastershe->get_pabrik($id_gedung);
        	$data['pabrik'] 				= $this->dmastershe->get_pabrik($idpabrik[0]->kode);
		}
		$status 							= (empty($_POST['status']))? "" : $_POST['status'];
        $data['status']    					= $status;
		$data['filterfrom']    				= $filterfrom;
		$data['filterto']    				= $filterto;
        $data['limbah_b3'] 					= $this->dtransactionshe->get_view_data_limbah_b3(NULL, $string_pabrik, $status, $filterfrom, $filterto);


		$this->load->view("limbah_b3/limbahb3_viewinputdata", $data);
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
			case 'limbah_air_bulanan':
				$this->get_limbah_air_bulanan();
				break;
			case 'limbah_air_harian':
				$this->get_limbah_air_harian();
				break;
			case 'get_limbah_air_bulanan_parameter':
				$this->get_limbah_air_bulanan_parameter();
				break;
			case 'limbah_air_harian_filter':
				$this->get_limbah_air_harian_filter();
				break;
			case 'limbah_air_harian_ipal':
				$this->get_limbah_air_harian_ipal();
				break;
			case 'filter_air_bulanan_harian':
				$this->get_filter_air_bulanan_harian();
				break;
			case 'kualitasudara':
				$this->get_kualitasudara();
				break;
			case 'filter_kualitasudara_parameter':
				$this->get_filter_kualitasudara_parameter();
				break;
			case 'kualitasudara_filterjenis':
				$this->get_kualitasudara_filterjenis();
				break;
			case 'limbahB3':
				$this->get_limbahB3();
				break;
			case 'endingstock':
				$this->get_stock();
				break;
			case 'addperpanjang_masa_b3':
				$this->get_add_perpanjang_masa_b3();
				break;
			case 'cekperpanjang_masa_b3':
				$this->get_extend_perpanjang_masa_b3();
				break;
			case 'editperpanjang_masa_b3':
				$this->get_edit_perpanjang_masa_b3();
				break;
			case 'lasttrx':
				$this->get_lasttrx();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}
	
	public function excel($param){
		switch ($param) {
			case 'limbah_air_harian':
				$this->excel_limbah_air_harian();
				break;
			case 'limbah_air_bulanan':
				$this->excel_limbah_air_bulanan();
				break;
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function set_data($action, $param){
		switch ($param) {
			case 'limbah_air_bulanan':
				$this->set_limbah_air_bulanan($action);
				break;
			case 'postlimbahB3':
				$this->post_limbahB3($action);
				break;
			case 'deletelimbahB3':
				$this->set_limbahB3($action);
				break;
			case 'postba':
				$this->post_ba_limbahB3($action);
				break;
			case 'requestdeletelimbahB3':
				$this->request_del_limbahB3($action);
				break;
			case 'cancelrequestdeletelimbahB3':
				$this->cancel_request_del_limbahB3($action);
				break;
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}
	public function set($param = NULL) {
		switch ($param) {
			case 'kualitasudara':
				$this->general->connectDbPortal();
				$return = $this->general->set('delete_del0', "tbl_she_tr_kualitasudara", array(
					array(
						'kolom' => 'id',
						'value' => $_POST['id']
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

	public function save($param){
		switch ($param) {
			case 'limbah_air_bulanan':
				$this->save_limbah_air_bulanan();
				break;
			case 'limbah_air_harian':
				$this->save_limbah_air_harian();
				break;
			case 'kualitasudara':
				$this->save_kualitasudara();
				break;
			case 'perpanjang_masa_b3':
				$this->save_perpanjang_masab3();
				break;
			case 'limbahB3':
				$this->save_limbah_b3();
				break;
			case 'import_bulanan':
				$this->save_import_bulanan($param);
				break;
			case 'import_harian':
				$this->save_import_harian($param);
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
	private function get_limbah_air_bulanan(){
		$this->general->connectDbPortal();
		$limbahair = $this->dtransactionshe->get_data_limbah_air_bulanan($_POST['id'], NULL, NULL, NULL);
		echo json_encode($limbahair);
	}	

	private function get_limbah_air_bulanan_parameter(){
		$this->general->connectDbPortal();
		$limbahair = $this->dtransactionshe->get_data_parameter($_POST['pabrik'], $_POST['jenis'], $_POST['kategori']);
		echo json_encode($limbahair);
	}	

    private function set_limbah_air_bulanan($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_tr_airlimbah_bulanan", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_limbah_air_bulanan(){
		$datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$limbahair = $this->dtransactionshe->get_data_limbah_air_bulanan(NULL, $_POST['pabrik'], $_POST['lokasi'], NULL);

        // if(isset($_POST['pabrik']) && $_POST['pabrik'] != "" && (count($limbahair) == 0 || isset($_POST['id']) && $_POST['id'] != "")){
        if(isset($_POST['pabrik']) && $_POST['pabrik'] != ""){
        	

	        $uploaded1 = "";
	        $uploadError1 = "";

	        $uploaddir  = realpath('./') . '/assets/file/she/limbahb3';
	        if (!file_exists($uploaddir)) {
	            mkdir($uploaddir, 0777, true);
	        }
	        $config['upload_path']          = $uploaddir;
	        $config['allowed_types']        = 'pdf';
	        // $config['max_size']             = 100;
	        // $config['max_width']            = 1024;
	        // $config['max_height']           = 768;

	        $this->load->library('upload', $config);

			$_FILES['lampiran']['name'] = $_FILES['lampiran1']['name'];
			$_FILES['lampiran']['type'] = $_FILES['lampiran1']['type'];
			$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran1']['tmp_name'];
			$_FILES['lampiran']['error'] = $_FILES['lampiran1']['error'];
			$_FILES['lampiran']['size'] = $_FILES['lampiran1']['size'];

	        if ( ! $this->upload->do_upload('lampiran'))
	        {
	            $uploadError1= $this->upload->display_errors();
	        }else{
	            $upload_data1 = $this->upload->data();
	            $uploaded1 = 'assets/file/she/limbahb3/'.$upload_data1['file_name'];
	        }

		    $datalampiran = array();
		    if($uploaded1 != ""){
		    	$datalampiran = array('lampiran' => $uploaded1); 
		    }


        	if(isset($_POST['id']) && $_POST['id'] != ""){
                $data_row   = array(
                                  'fk_pabrik'     			=> $_POST['pabrik'],
                                  'fk_jenis'    			=> $_POST['lokasi'],
                                  'fk_kategori'    			=> $_POST['kategori'],
                                  'tanggal_sampling'    	=> $this->generate->regenerateDateFormat($_POST['tgl_sampling']),
                                  'tanggal_analisa'    		=> $this->generate->regenerateDateFormat($_POST['tgl_analisa']),                                  
                                  'fk_parameter'    		=> $_POST['idparam'],
                                  'hasil_uji'    			=> $_POST['hasil_uji'],
	                              'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_edit'      		=> $datetime
                             );
                $data_row = array_merge($data_row, $datalampiran);
                $this->dgeneral->update('tbl_she_tr_airlimbah_bulanan', $data_row, array( 
                                                                            array(
                                                                                'kolom'=>'id',
                                                                                'value'=>$_POST['id']
                                                                            )
                                                                        ));
            }else{
            	foreach ($_POST['idparam'] as $key => $idparam) {
	                $data_row   = array(
	                                  'fk_pabrik'     			=> $_POST['pabrik'],
	                                  'fk_jenis'    			=> $_POST['lokasi'],
	                                  'fk_kategori'    			=> $_POST['kategori'],
	                                  'tanggal_sampling'    	=> $this->generate->regenerateDateFormat($_POST['tgl_sampling']),
	                                  'tanggal_analisa'    		=> $this->generate->regenerateDateFormat($_POST['tgl_analisa']),
	                                  'fk_parameter'    		=> $idparam,
	                                  'hasil_uji'    			=> $_POST['hasil_uji'][$key],
	                                  'is_active'    			=> 1,
	                                  'del'    					=> 1,
		                              'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
		                              'tanggal_buat'      		=> $datetime
		                            );
	                $data_row = array_merge($data_row, $datalampiran);
	                $this->dgeneral->insert('tbl_she_tr_airlimbah_bulanan', $data_row);
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
        }else{
            $msg1    = "Periksa kembali data yang dimasukkan";
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//


	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function excel_limbah_air_bulanan(){
		// header("Content-type: application/octet-stream");
		// header("Content-Disposition: attachment; filename=Input Data Air Limbah Bulanan.xls");
		// header("Pragma: no-cache");
		// header("Expires: 0");
		// Redirect output to a client’s web browser (Excel5)

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Input Data Air Limbah Bulanan.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		if(base64_decode($this->session->userdata("-ho-")) == 'y'){
        	$idpabrik 		= $this->dmastershe->get_pabrik('ABL1');
		}else{
			$id_gedung		= base64_decode($this->session->userdata("-id_gedung-"));
        	$idpabrik 		= $this->dmastershe->get_pabrik($id_gedung);
		}
        $limbah_air_bulanan = $this->dtransactionshe->get_data_limbah_air_bulanan(NULL, $idpabrik[0]->id_pabrik, NULL, NULL);
        echo'
		           		<table border="1" width="100%" class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				                <tr>
						            <th class="text-center">Parameter</th>
						            <th class="text-center">Lokasi Sampling</th>
						            <th class="text-center">Tanggal Sampling</th>
						            <th class="text-center">Tanggal Analisa</th>
						            <th class="text-center">Baku Mutu Hasil Uji</th>
						            <th class="text-center">Hasil Uji (mg/l)</th>          
						            <th class="text-center">Debit Air (m&#179;/bln)</th>
						            <th class="text-center">Crumbing (ton/bln)</th>
						            <th class="text-center">Baku Mutu Beban Pencemaran</th>
						            <th class="text-center">Beban Pencemaran - kg/ton</th>            
						            <th class="text-center">Beban Pencemaran Aktual - ton/periode</th>
				                </tr>
				            </thead>
			              	<tbody>';
					                foreach($limbah_air_bulanan as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->parameter."</td>";
					                  echo "<td>".$dt->lokasi."</td>";
					                  echo "<td>".$this->generate->generateDateFormat($dt->tanggal_sampling)."</td>";
					                  echo "<td>".$this->generate->generateDateFormat($dt->tanggal_analisa)."</td>";
					                  echo "<td align='right'>".number_format($dt->bakumutu_hasilujilimit,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->hasil_uji,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->oi_debit,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->crumbing,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->bakumutu_bebancemar."</td>";
					                  echo "<td align='right'>".number_format($dt->bp,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->bpa,2,",",".")."</td>";
					                  echo "</tr>";
					                }
								echo'
			              	</tbody>
			            </table>
		';
	}
	private function excel_limbah_air_harian(){
		// header("Content-type: application/octet-stream");
		// header("Content-Disposition: attachment; filename=Input Data Air Limbah Harian.xls");
		// header("Pragma: no-cache");
		// header("Expires: 0");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Input Data Air Limbah Harian.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		
		
		// $this->general->check_access();
        $filterpabrik    = (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
        $filterperiode   = (empty($_POST['filterperiode']))? "" : $_POST['filterperiode'];
		if(base64_decode($this->session->userdata("-ho-")) == 'y'){
        	$idpabrik 	 = $this->dmastershe->get_pabrik('ABL1');
		}else{
			$id_gedung	 = base64_decode($this->session->userdata("-id_gedung-"));
        	$idpabrik 	 = $this->dmastershe->get_pabrik($id_gedung);
		}
        $limbah_air_harian 		 = $this->dtransactionshe->get_data_limbah_air_harian(NULL, ($filterpabrik == 0)?$idpabrik[0]->id_pabrik:$filterpabrik, $filterperiode);
		$limbah_air_harian_ipal	 = $this->dtransactionshe->get_limbah_air_harian_ipal(($filterpabrik ==0 )?$idpabrik[0]->id_pabrik:$filterpabrik, $filterperiode);
		           		echo'
						<table width="100%" border="1" class="table table-bordered table-striped">
		              		<thead>
						        <tr>
									<th rowspan="3" class="text-center">Tanggal</th>          
									<th colspan="6" class="text-center">Segmen Bak Aerasi</th>
									<th colspan="2" class="text-center">Segmen Denitrifikasi</th>
									<th colspan="2" class="text-center">Saluran Lumpur Balik</th>
									<th colspan="3" class="text-center">Outlet IPAL</th>
									<th colspan="2" class="text-center">Bak Indikator</th>        
									<th rowspan="3" class="text-center"></th>
						        </tr>
								<tr>
									<!-- Segmen Bak Aerasi -->
									<th colspan="2" class="text-center">DO (mg/l)</th>
									<th colspan="2" class="text-center">SV 30 (mg/l)</th>
									<th colspan="2" class="text-center">pH</th>
									<!-- Denitrifikasi -->
									<th colspan="2" class="text-center">DO (mg/l)</th>
									<!-- Lumpur balik -->
									<th colspan="2" class="text-center">SV 30 (mg/l)</th>
									<!-- Outlet Ipal -->
									<th class="text-center">Debit (m3)</th>
									<th colspan="2" class="text-center">pH</th>
									<!-- Bak Indikator -->
									<th colspan="2" class="text-center">Transparansi(cm)</th> 
								</tr>
								<tr>
									<!-- Segmen Bak Aerasi -->
									<!-- DO -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- SV -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- PH -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- Denitrifikasi -->
									<!-- DO -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- Lumpur balik -->
									<!-- SV -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- Outlet Ipal -->          
									<!-- Debit -->
									<th class="text-center">Hasil</th>
									<!-- PH -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- Bak Indikator -->
									<!-- Transparansi -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
								</tr>
				            </thead>
							
			              	<tbody id="table_trx">
							';
					                foreach($limbah_air_harian as $dt){
					                  echo "<tr>";
					                  echo "<td>".$this->generate->generateDateFormat($dt->tanggal)."</td>";
					                  echo "<td align='center'>".$dt->s1."</td>";
                    				  echo "<td align='right' ".$dt->red_texth1.">".number_format($dt->sba_do,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s2."</td>";
                    				  echo "<td align='right' ".$dt->red_texth2.">".number_format($dt->sba_sv,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s3."</td>";
                    				  echo "<td align='right' ".$dt->red_texth3.">".number_format($dt->sba_ph,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s4."</td>";
                    				  echo "<td align='right' ".$dt->red_texth4.">".number_format($dt->sd_do,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s5."</td>";
                    				  echo "<td align='right' ".$dt->red_texth5.">".number_format($dt->slb_sv,2,",",".")."</td>";
                    				  echo "<td align='right'>".number_format($dt->oi_debit,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s6."</td>";
                    				  echo "<td align='right' ".$dt->red_texth6.">".number_format($dt->oi_ph,2,",",".")."</td>";
					                  echo "<td align='right'>".$dt->s7."</td>";
                    				  echo "<td align='right' ".$dt->red_texth7.">".number_format($dt->bi_transparansi,2,",",".")."</td>";
                    
					                  echo "<td align='center'>-</td>";
					                  echo "</tr>";
					                }
					                foreach($limbah_air_harian_ipal as $keyipal => $dt2){			              		
										echo "<tr class='danger'>";
											echo "<td>Average</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_sbado' align=right>".number_format($dt2->sba_do_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_sbasv' align=right>".number_format($dt2->sba_sv_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_sbaph' align=right>".number_format($dt2->sba_ph_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_sddo' align=right>".number_format($dt2->sd_do_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_slbsv' align=right>".number_format($dt2->slb_sv_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_oidebit' align=right>".number_format($dt2->oi_debit_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_oiph' align=right>".number_format($dt2->oi_ph_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_bitrans' align=right>".number_format($dt2->bi_transparansi_avg,2,",",".")."</td>";
										echo "</tr>";
										echo "<tr class='danger'>";
											echo "<td>Total</td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td id='tot_oidebit' align=right>".number_format($dt2->oi_debit_sum,2,",",".")."</td>";
											echo "<td colspan='5'></td>";
										echo "</tr>";
					                }
								echo'
								</tbody>
			            </table>';
	}
	
	
	private function get_limbah_air_harian(){
		$this->general->connectDbPortal();
		$limbahair = $this->dtransactionshe->get_data_limbah_air_harian($_POST['id'], NULL, NULL);
		echo json_encode($limbahair);
	}	

	private function get_limbah_air_harian_filter(){
		$this->general->connectDbPortal();
		$limbahair = $this->dtransactionshe->get_data_limbah_air_harian(NULL, $_POST['filterpabrik'], $_POST['filterperiode']);
		echo json_encode($limbahair);
	}	

	private function get_limbah_air_harian_ipal(){
		$this->general->connectDbPortal();
		$limbahair = $this->dtransactionshe->get_limbah_air_harian_ipal($_POST['filterpabrik'], $_POST['filterperiode']);
		echo json_encode($limbahair);
	}	

	private function get_filter_air_harian(){
		$this->general->connectDbPortal();
		$limbahair = $this->dtransactionshe->get_filter_air_harian(NULL, $_POST['filterpabrik'], NULL);
		echo json_encode($limbahair);
	}	

    private function set_limbah_air_harian($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->setdel($action, "tbl_she_tr_airlimbah_harian", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_limbah_air_harian(){
		$datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();

		if(isset($_POST['pabrik']) && $_POST['pabrik'] != ""){
        	$this->dgeneral->begin_transaction();

        	if(isset($_POST['id']) && $_POST['id'] != ""){
        		//check data
        		$limbahair = $this->dtransactionshe->cek_data_limbah_air_harian(NULL, NULL, $_POST['pabrik'], date("Y-m-d", strtotime($_POST['tanggal']) ), $_POST['kategori'],'update',$_POST['id'] );

				if (count($limbahair) > 0) { //count($limbahair) == 0
					$msg    = "Data pada tanggal ".$_POST['tanggal']." sudah pernah diinput, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
                $data_row   = array(
                                  'debit_harian'     		=> $_POST['debit_harian'],
								  'satuan_produksi'     	=> $_POST['satuan_produksi'],
                                  'produksi_sir'     		=> $_POST['produksi_sir'],
                                  'fk_pabrik'     			=> $_POST['pabrik'],
                                  'tanggal'    				=> $this->generate->regenerateDateFormat($_POST['tanggal']),
                                  'sba_do'    				=> $_POST['bakaerasi_do'],
                                  'sba_sv'    				=> $_POST['bakaerasi_sv'],
                                  'sba_ph'    				=> $_POST['bakaerasi_ph'],
                                  'sd_do'    				=> $_POST['denitrifikasi_do'],
                                  'slb_sv'    				=> $_POST['lumpurbalik_sv'],
                                  'oi_debit'    			=> $_POST['ipal_debit'],
								  'oi_debit_standar'		=> $_POST['ipal_debit_standar'],
                                  'oi_ph'    				=> $_POST['ipal_ph'],
                                  'bi_transparansi'    		=> $_POST['bi_trans'],
                                  'fk_kategori'    			=> $_POST['kategori'],
	                              'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_edit'      		=> $datetime
                             );
                $this->dgeneral->update('tbl_she_tr_airlimbah_harian', $data_row, array( 
                                                                            array(
                                                                                'kolom'=>'id',
                                                                                'value'=>$_POST['id']
                                                                            )
                                                                        ));
            }else{
            	$limbahair = $this->dtransactionshe->cek_data_limbah_air_harian(NULL, NULL, $_POST['pabrik'], date("Y-m-d", strtotime($_POST['tanggal']) ), $_POST['kategori'],'insert' );

				if (count($limbahair) > 0) { //count($limbahair) == 0
					$msg    = "Data pada tanggal ".$_POST['tanggal']." sudah pernah diinput, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
                $data_row   = array(
                                  'debit_harian'     		=> $_POST['debit_harian'],
                                  'satuan_produksi'     	=> $_POST['satuan_produksi'],
                                  'produksi_sir'     		=> $_POST['produksi_sir'],
                                  'fk_pabrik'     			=> $_POST['pabrik'],
                                  'tanggal'    				=> $this->generate->regenerateDateFormat($_POST['tanggal']),
                                  'sba_do'    				=> $_POST['bakaerasi_do'],
                                  'sba_sv'    				=> $_POST['bakaerasi_sv'],
                                  'sba_ph'    				=> $_POST['bakaerasi_ph'],
                                  'sd_do'    				=> $_POST['denitrifikasi_do'],
                                  'slb_sv'    				=> $_POST['lumpurbalik_sv'],
                                  'oi_debit'    			=> $_POST['ipal_debit'],
                                  'oi_debit_standar'		=> $_POST['ipal_debit_standar'],
                                  'oi_ph'    				=> $_POST['ipal_ph'],
                                  'bi_transparansi'    		=> $_POST['bi_trans'],
                                  'fk_kategori'    			=> $_POST['kategori'],
                                  'del'    					=> 1,
	                              'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_buat'      		=> $datetime
	                            );
                $this->dgeneral->insert('tbl_she_tr_airlimbah_harian', $data_row);
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


	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_kualitasudara(){
		$this->general->connectDbPortal();
		$limbahudara = $this->dtransactionshe->get_data_limbah_udara($_POST['id'], NULL, NULL, NULL, NULL, NULL, NULL);
		echo json_encode($limbahudara);
	}	

	private function get_filter_kualitasudara(){
		$this->general->connectDbPortal();
		$limbahudara = $this->dtransactionshe->get_data_limbah_udara(NULL, $_POST['filterpabrik'], $_POST['filterkategori'], $_POST['filterjenis'], $_POST['from'], $_POST['to'], NULL);
		echo json_encode($limbahudara);
	}	

	private function get_filter_kualitasudara_parameter(){
		$this->general->connectDbPortal();
		$limbahudara = $this->dtransactionshe->get_data_limbah_udara_parameter($_POST['pabrik'], $_POST['kategori'], $_POST['jenis']);
		echo json_encode($limbahudara);
	}	

	private function get_kualitasudara_filterjenis(){
		$this->general->connectDbPortal();
		$limbahudara = $this->dtransactionshe->get_data_kualitasudara_filterjenis($_POST['filterpabrik'], $_POST['filterkategori']);
		echo json_encode($limbahudara);
	}	


	private function save_kualitasudara(){
		$datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();

		$limbahudara = $this->dtransactionshe->get_data_limbah_udara(NULL, $_POST['pabrik'], $_POST['kategori'], $_POST['jenis'], NULL, NULL, $this->generate->regenerateDateFormat($_POST['tglsampling']));

        if(count($limbahudara) == 0 || (isset($_POST['id']) && $_POST['id'] != "")){
        	$this->dgeneral->begin_transaction();

    		$laju_air = (empty($_POST['laju_air']))?null:$_POST['laju_air'];
    		$jam_operasi = (empty($_POST['jam_operasi']))?null:$_POST['jam_operasi'];

	        $uploaded1 = "";
	        $uploadError1 = "";

	        $uploaddir  = realpath('./') . '/assets/file/she/limbahb3';
	        if (!file_exists($uploaddir)) {
	            mkdir($uploaddir, 0777, true);
	        }
	        $config['upload_path']          = $uploaddir;
	        $config['allowed_types']        = 'pdf';
	        // $config['max_size']             = 100;
	        // $config['max_width']            = 1024;
	        // $config['max_height']           = 768;

	        $this->load->library('upload', $config);

			$_FILES['lampiran']['name'] = $_FILES['lampiran1']['name'];
			$_FILES['lampiran']['type'] = $_FILES['lampiran1']['type'];
			$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran1']['tmp_name'];
			$_FILES['lampiran']['error'] = $_FILES['lampiran1']['error'];
			$_FILES['lampiran']['size'] = $_FILES['lampiran1']['size'];

	        if ( ! $this->upload->do_upload('lampiran'))
	        {
	            $uploadError1= $this->upload->display_errors();
	        }else{
	            $upload_data1 = $this->upload->data();
	            $uploaded1 = 'assets/file/she/limbahb3/'.$upload_data1['file_name'];
	        }

		    $datalampiran = array();
		    if($uploaded1 != ""){
		    	$datalampiran = array('lampiran' => $uploaded1); 
		    }

        	if(isset($_POST['id']) && $_POST['id'] != ""){
            	foreach ($_POST['idparam'] as $key => $param) {
	                $data_row   = array(
	                                  'fk_pabrik'     			=> $_POST['pabrik'],
	                                  'fk_kategori'    			=> $_POST['kategori'],
	                                  'fk_jenis'    			=> $_POST['jenis'],
	                                  'tanggal_sampling'    	=> $this->generate->regenerateDateFormat($_POST['tglsampling']),
	                                  'tanggal_analisa'    		=> $this->generate->regenerateDateFormat($_POST['tglanalisa']),
	                                  'fk_parameter'    		=> $param,
	                                  'hasil_uji'    			=> $_POST['hasiluji'][$key],
	                                  'laju_air'    			=> $laju_air,
	                                  'jam_operasi'    			=> $jam_operasi,
		                              'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
		                              'tanggal_edit'      		=> $datetime
	                             );
	                $data_row = array_merge($data_row, $datalampiran);
	                $this->dgeneral->update('tbl_she_tr_kualitasudara', $data_row, array( 
	                                                                            array(
	                                                                                'kolom'=>'id',
	                                                                                'value'=>$_POST['id']
	                                                                            )
	                                                                        ));
	            }
            }else{
            	foreach ($_POST['idparam'] as $key2 => $param2) {
	                $data_row   = array(
	                                  'fk_pabrik'     			=> $_POST['pabrik'],
	                                  'fk_kategori'    			=> $_POST['kategori'],
	                                  'fk_jenis'    			=> $_POST['jenis'],
	                                  'tanggal_sampling'    	=> $this->generate->regenerateDateFormat($_POST['tglsampling']),
	                                  'tanggal_analisa'    		=> $this->generate->regenerateDateFormat($_POST['tglanalisa']),
	                                  'fk_parameter'    		=> $param2,
	                                  'hasil_uji'    			=> $_POST['hasiluji'][$key2],
	                                  'laju_air'    			=> $laju_air,
	                                  'jam_operasi'    			=> $jam_operasi,
	                                  'del'		    			=> 1,
		                              'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
		                              'tanggal_buat'      		=> $datetime
		                            );
	                $data_row = array_merge($data_row, $datalampiran);
	                $this->dgeneral->insert('tbl_she_tr_kualitasudara', $data_row);
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
        }else{
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//


	/**********************************/
	/*			  private  			  */
	/**********************************/

	private function get_limbahB3(){
		$this->general->connectDbPortal();
		$limbahB3 = $this->dtransactionshe->get_data_limbah_b3($_POST['id'], NULL, NULL);
		echo json_encode($limbahB3);
	}	

	private function get_stock(){
		$this->general->connectDbPortal();
		$stock = $this->dtransactionshe->get_data_latest_batch($_POST['pabrik'], $_POST['jenislimbah'], date("Y-m-d"));
		echo json_encode($stock);
	}	

	private function post_limbahB3(){
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$limbahB3 = $this->dtransactionshe->get_data_limbahB3($_POST['id']);

		$stok = 0;
		if($limbahB3[0]->type == "IN"){
			$stok = $limbahB3[0]->last_stock + $limbahB3[0]->quantity;
		}elseif($limbahB3[0]->type == "OUT"){
			$stok = $limbahB3[0]->last_stock - $limbahB3[0]->quantity;
		}


        $data_row   = array(
                          'stok'    				=> $stok,
                          'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
                          'tanggal_edit'      		=> $datetime
                     );
        $this->dgeneral->update('tbl_she_tr_b3_limbah', $data_row, array( 
                                                                    array(
                                                                        'kolom'=>'id',
                                                                        'value'=>$_POST['id']
                                                                    )
                                                                ));
		
    	if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            $msg    = "Data berhasil dipost";	
            $sts    = "OK";
        }	
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}	

	private function save_limbah_b3(){
		$datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
        $types = ($_POST['type']!= '') ? $_POST['type'] : 'normal';
        

    	if($_POST['id'] == "" && $types == 'normal'){ // Insert

	        // if($stok != -1){
	        	if($_POST['tipe'] == 'IN'){
					// Transaksi masuk hanya 50 qty per hari
					$cek_trx = $this->dtransactionshe->get_data_trx_in($_POST['pabrik'], $_POST['jenislimbah'], $this->generate->regenerateDateFormat($_POST['tanggal']));
					$qty_trx = $cek_trx[0]->quantity + $_POST['qty'];
					if($qty_trx > 50){
			            $msg    = "Input tidak bisa diproses, qty maximal per hari hanya 50 !";
			            $sts    = "NotOK";

			            $return = array('sts' => $sts, 'msg' => $msg);
		        		echo json_encode($return);
		        		exit();
					}

					$latest_batch = $this->dtransactionshe->get_data_latest_batch($_POST['pabrik'], $_POST['jenislimbah'], $this->generate->regenerateDateFormat($_POST['tanggal']));

		        	if($latest_batch[0]->stok != 0){
						$batch = $latest_batch[0]->batch;
						$stok = $latest_batch[0]->stok;        		
		        	}else{
						$batch = $latest_batch[0]->batch + 1;
						$stok = 0;        		
		        	}

		        	$this->dgeneral->begin_transaction();
		            $data_row   = array(
		                              'fk_pabrik'     		=> $_POST['pabrik'],
		                              'fk_limbah'    		=> $_POST['jenislimbah'],
		                              'fk_sumber_limbah'    => $_POST['sumberlimbah'],
		                              'type'    			=> $_POST['tipe'],
		                              'tanggal_masuk'    	=> $this->generate->regenerateDateFormat($_POST['tanggal']),
		                              'quantity'    		=> $_POST['qty'],
		                              'batch'    			=> $batch,
		                              'stok'    			=> -1,
		                              'dsimpan_max'    		=> $latest_batch[0]->dsimpan_max,                              
		                              'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
		                              'tanggal_buat'      	=> $datetime,
		                              'del'      			=> 1
		                            );
	            	$this->dgeneral->insert('tbl_she_tr_b3_limbah', $data_row);
	        	
	        	}elseif($_POST['tipe'] == 'OUT'){

	        		$no_beritaacara = $this->dtransactionshe->get_data_latest_beritaacara($_POST['pabrik'], $this->generate->regenerateDateFormat($_POST['tanggal']));

	        		foreach ($_POST['jenislimbahlist'] as $key => $limbah) {
						$latest_batch = $this->dtransactionshe->get_data_latest_batch($_POST['pabrik'], $limbah, date("Y-m-d"));

			        	if($latest_batch[0]->stok != 0){
							$batch = $latest_batch[0]->batch;
							$stok = $latest_batch[0]->stok;        		
			        	}else{
							$batch = $latest_batch[0]->batch + 1;
							$stok = 0;        		
			        	}

				        $uploaded1 = "";
				        $uploadError1 = "";
				        $uploaded2 = "";
				        $uploadError2 = "";
				        $uploaded3 = "";
				        $uploadError3 = "";

			            $uploaddir  = realpath('./') . '/assets/file/she/limbahb3';
			            if (!file_exists($uploaddir)) {
			                mkdir($uploaddir, 0777, true);
			            }
			            $config['upload_path']          = $uploaddir;
			            $config['allowed_types']        = 'pdf';
			            // $config['max_size']             = 100;
			            // $config['max_width']            = 1024;
			            // $config['max_height']           = 768;

			            $this->load->library('upload', $config);

	            		$_FILES['lampiran']['name'] = $_FILES['lampiran1']['name'][$key];
	            		$_FILES['lampiran']['type'] = $_FILES['lampiran1']['type'][$key];
	            		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran1']['tmp_name'][$key];
	            		$_FILES['lampiran']['error'] = $_FILES['lampiran1']['error'][$key];
	            		$_FILES['lampiran']['size'] = $_FILES['lampiran1']['size'][$key];

		                if ( ! $this->upload->do_upload('lampiran'))
		                {
		                    $uploadError1= $this->upload->display_errors();
		                }else{
		                    $upload_data1 = $this->upload->data();
		                    $uploaded1 = 'assets/file/she/limbahb3/'.$upload_data1['file_name'];
		                }

	            		$_FILES['lampiran']['name'] = $_FILES['lampiran2']['name'][$key];
	            		$_FILES['lampiran']['type'] = $_FILES['lampiran2']['type'][$key];
	            		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran2']['tmp_name'][$key];
	            		$_FILES['lampiran']['error'] = $_FILES['lampiran2']['error'][$key];
	            		$_FILES['lampiran']['size'] = $_FILES['lampiran2']['size'][$key];

		                if ( ! $this->upload->do_upload('lampiran'))
		                {
		                    $uploadError2= $this->upload->display_errors();
		                }else{
		                    $upload_data2 = $this->upload->data();
		                    $uploaded2 = 'assets/file/she/limbahb3/'.$upload_data2['file_name'];
		                }


	            		$_FILES['lampiran']['name'] = $_FILES['lampiran3']['name'][$key];
	            		$_FILES['lampiran']['type'] = $_FILES['lampiran3']['type'][$key];
	            		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran3']['tmp_name'][$key];
	            		$_FILES['lampiran']['error'] = $_FILES['lampiran3']['error'][$key];
	            		$_FILES['lampiran']['size'] = $_FILES['lampiran3']['size'][$key];

		                if ( ! $this->upload->do_upload('lampiran'))
		                {
		                    $uploadError3= $this->upload->display_errors();
		                }else{
		                    $upload_data3 = $this->upload->data();
		                    $uploaded3 = 'assets/file/she/limbahb3/'.$upload_data3['file_name'];
		                }


			            $data_row   = array(
			                              'fk_pabrik'     		=> $_POST['pabrik'],
			                              'fk_limbah'    		=> $limbah,
			                              'type'    			=> $_POST['tipe'],
			                              'tanggal_keluar'    	=> $this->generate->regenerateDateFormat($_POST['tanggal']),
			                              'quantity'    		=> $_POST['qtylist'][$key],
			                              'stok'    			=> -1,
			                              'fk_vendor'    		=> $_POST['vendor'],                              
			                              'no_manifest'    		=> $_POST['manifestlist'][$key],                              
			                              'no_berita_acara'    	=> $no_beritaacara[0]->ba,                              
			                              'jenis_kendaraan'    	=> $_POST['jeniskendaraan'],                              
			                              'nomor_kendaraan'    	=> $_POST['nomorkendaraan'],                              
			                              'nama_driver'    		=> $_POST['driver'], 
			                              'lampiran1'			=> $uploaded1,                        
			                              'lampiran2'			=> $uploaded2, 
			                              'lampiran3'			=> $uploaded3,                        
			                              'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
			                              'tanggal_buat'      	=> $datetime,
			                              'del'      			=> 1
			                            );
		            	$this->dgeneral->insert('tbl_she_tr_b3_limbah', $data_row);
	        		}
	        	}
	        // }else{
	        //     $msg    = "Input tidak bisa diproses, masih ada data yang belum dipost!";
	        //     $sts    = "NotOK";

	        //     $return = array('sts' => $sts, 'msg' => $msg);
        	// 	echo json_encode($return);
        	// 	exit();

	        // }
	    } else if($_POST['id'] != "" && $types == 'normal'){ // Update
	    	$this->dgeneral->begin_transaction();
	    	if($_POST['tipe'] == 'IN'){
	            $data_row   = array(
	                              'quantity'    		=> $_POST['qty'],
	                              'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_edit'      	=> $datetime
	                            );
	            $this->dgeneral->update('tbl_she_tr_b3_limbah', $data_row, array( 
	                                                                        array(
	                                                                            'kolom'=>'id',
	                                                                            'value'=>$_POST['id']
	                                                                        )
	                                                                    ));
	        }elseif($_POST['tipe'] == 'OUT'){
	            foreach ($_POST['qtylist'] as $keyqty => $qty) {

			        $uploaded1 = "";
			        $uploadError1 = "";
			        $uploaded2 = "";
			        $uploadError2 = "";
			        $uploaded3 = "";
			        $uploadError3 = "";

		            $uploaddir  = realpath('./') . '/assets/file/she/limbahb3';
		            if (!file_exists($uploaddir)) {
		                mkdir($uploaddir, 0777, true);
		            }
		            $config['upload_path']          = $uploaddir;
		            $config['allowed_types']        = 'pdf';
		            // $config['max_size']             = 100;
		            // $config['max_width']            = 1024;
		            // $config['max_height']           = 768;

		            $this->load->library('upload', $config);

            		$_FILES['lampiran']['name'] = $_FILES['lampiran1']['name'][$keyqty];
            		$_FILES['lampiran']['type'] = $_FILES['lampiran1']['type'][$keyqty];
            		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran1']['tmp_name'][$keyqty];
            		$_FILES['lampiran']['error'] = $_FILES['lampiran1']['error'][$keyqty];
            		$_FILES['lampiran']['size'] = $_FILES['lampiran1']['size'][$keyqty];

	                if ( ! $this->upload->do_upload('lampiran'))
	                {
	                    $uploadError1= $this->upload->display_errors();
	                }else{
	                    $upload_data1 = $this->upload->data();
	                    $uploaded1 = 'assets/file/she/limbahb3/'.$upload_data1['file_name'];
	                }

            		$_FILES['lampiran']['name'] = $_FILES['lampiran2']['name'][$keyqty];
            		$_FILES['lampiran']['type'] = $_FILES['lampiran2']['type'][$keyqty];
            		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran2']['tmp_name'][$keyqty];
            		$_FILES['lampiran']['error'] = $_FILES['lampiran2']['error'][$keyqty];
            		$_FILES['lampiran']['size'] = $_FILES['lampiran2']['size'][$keyqty];

	                if ( ! $this->upload->do_upload('lampiran'))
	                {
	                    $uploadError2= $this->upload->display_errors();
	                }else{
	                    $upload_data2 = $this->upload->data();
	                    $uploaded2 = 'assets/file/she/limbahb3/'.$upload_data2['file_name'];
	                }

            		$_FILES['lampiran']['name'] = $_FILES['lampiran3']['name'][$keyqty];
            		$_FILES['lampiran']['type'] = $_FILES['lampiran3']['type'][$keyqty];
            		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran3']['tmp_name'][$keyqty];
            		$_FILES['lampiran']['error'] = $_FILES['lampiran3']['error'][$keyqty];
            		$_FILES['lampiran']['size'] = $_FILES['lampiran3']['size'][$keyqty];

	                if ( ! $this->upload->do_upload('lampiran'))
	                {
	                    $uploadError3= $this->upload->display_errors();
	                }else{
	                    $upload_data3 = $this->upload->data();
	                    $uploaded3 = 'assets/file/she/limbahb3/'.$upload_data3['file_name'];
	                }

	                $datalampiran = array();
			        if($uploaded1 != ""){
			        	$datalampiran = array('lampiran1' => $uploaded1); 
			        }
			        if($uploaded2 != ""){
			        	$datalampiran = array('lampiran2' => $uploaded2); 
			        }
			        if($uploaded3 != ""){
			        	$datalampiran = array('lampiran3' => $uploaded3); 
			        }

		            $data_row   = array(
		                              'quantity'    		=> $qty,
		                              'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
		                              'tanggal_edit'      	=> $datetime
		                            );
		            $data_row = array_merge($data_row, $datalampiran);
	            }
	            $this->dgeneral->update('tbl_she_tr_b3_limbah', $data_row, array( 
	                                                                        array(
	                                                                            'kolom'=>'id',
	                                                                            'value'=>$_POST['id']
	                                                                        )
	                                                                    ));

	        }
        } else if($_POST['id'] != "" && $types == 'reupload'){ // Upload doc
        	$this->dgeneral->begin_transaction();
        	if($_POST['tipe'] == 'OUT'){
	            foreach ($_POST['qtylist'] as $keyqty => $qty) {

			        $uploaded1 = "";
			        $uploadError1 = "";
			        $uploaded2 = "";
			        $uploadError2 = "";
			        $uploaded3 = "";
			        $uploadError3 = "";

		            $uploaddir  = realpath('./') . '/assets/file/she/limbahb3';
		            if (!file_exists($uploaddir)) {
		                mkdir($uploaddir, 0777, true);
		            }
		            $config['upload_path']          = $uploaddir;
		            $config['allowed_types']        = 'pdf';
		            // $config['max_size']             = 100;
		            // $config['max_width']            = 1024;
		            // $config['max_height']           = 768;

		            $this->load->library('upload', $config);

            		$_FILES['lampiran']['name'] = $_FILES['lampiran1']['name'][$keyqty];
            		$_FILES['lampiran']['type'] = $_FILES['lampiran1']['type'][$keyqty];
            		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran1']['tmp_name'][$keyqty];
            		$_FILES['lampiran']['error'] = $_FILES['lampiran1']['error'][$keyqty];
            		$_FILES['lampiran']['size'] = $_FILES['lampiran1']['size'][$keyqty];

	                if ( ! $this->upload->do_upload('lampiran'))
	                {
	                    $uploadError1= $this->upload->display_errors();
	                }else{
	                    $upload_data1 = $this->upload->data();
	                    $uploaded1 = 'assets/file/she/limbahb3/'.$upload_data1['file_name'];
	                }

            		$_FILES['lampiran']['name'] = $_FILES['lampiran2']['name'][$keyqty];
            		$_FILES['lampiran']['type'] = $_FILES['lampiran2']['type'][$keyqty];
            		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran2']['tmp_name'][$keyqty];
            		$_FILES['lampiran']['error'] = $_FILES['lampiran2']['error'][$keyqty];
            		$_FILES['lampiran']['size'] = $_FILES['lampiran2']['size'][$keyqty];

	                if ( ! $this->upload->do_upload('lampiran'))
	                {
	                    $uploadError2= $this->upload->display_errors();
	                }else{
	                    $upload_data2 = $this->upload->data();
	                    $uploaded2 = 'assets/file/she/limbahb3/'.$upload_data2['file_name'];
	                }

            		$_FILES['lampiran']['name'] = $_FILES['lampiran3']['name'][$keyqty];
            		$_FILES['lampiran']['type'] = $_FILES['lampiran3']['type'][$keyqty];
            		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran3']['tmp_name'][$keyqty];
            		$_FILES['lampiran']['error'] = $_FILES['lampiran3']['error'][$keyqty];
            		$_FILES['lampiran']['size'] = $_FILES['lampiran3']['size'][$keyqty];

	                if ( ! $this->upload->do_upload('lampiran'))
	                {
	                    $uploadError3= $this->upload->display_errors();
	                }else{
	                    $upload_data3 = $this->upload->data();
	                    $uploaded3 = 'assets/file/she/limbahb3/'.$upload_data3['file_name'];
	                }

	                $datalampiran = array();
			        if($uploaded1 != ""){
			        	$datalampiran = array('lampiran1' => $uploaded1); 
			        }
			        if($uploaded2 != ""){
			        	$datalampiran = array('lampiran2' => $uploaded2); 
			        }
			        if($uploaded3 != ""){
			        	$datalampiran = array('lampiran3' => $uploaded3); 
			        }

		            $data_row   = array(
		                              'quantity'    		=> $qty,
		                              'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
		                              'tanggal_edit'      	=> $datetime
		                            );
		            $data_row = array_merge($data_row, $datalampiran);
	            }
	            $this->dgeneral->update('tbl_she_tr_b3_limbah', $data_row, array( 
	                                                                        array(
	                                                                            'kolom'=>'id',
	                                                                            'value'=>$_POST['id']
	                                                                        )
	                                                                    ));

	        }	
        }

        if($this->dgeneral->status_transaction() === FALSE && (count($uploadError1)>0 || count($uploadError2)>0 || count($uploadError3)>0)){
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

    private function set_limbahB3($action){
        $id = $_POST['id'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_she_tr_b3_limbah", array(
			                                                                array(
			                                                                    'kolom'=>'id',
			                                                                    'value'=>$id
			                                                                )
			                                                            ));
        
        // $this->general->connectDbPortal();
        // $this->db->query("EXEC SP_Kiranaku_SHE_RecalculateStock ".$pabrik[0]->fk_pabrik."");


        // $this->dtransactionshe->recalculate_stock($pabrik[0]->fk_pabrik);

        $pabrik 	= $this->dtransactionshe->get_data_plant_limbah_b3($_POST['id'], NULL, NULL);

        $this->db->query("EXEC SP_Kiranaku_SHE_RecalculateStock ".$pabrik[0]->fk_pabrik."");

        $this->general->closeDb();
        echo json_encode($delete);
    }

    private function request_del_limbahB3($action){
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();

        $data_row   = array(
                          'request_del'    		=> 1,
                          'login_request_del'   => base64_decode($this->session->userdata("-id_user-")),
                          'tgl_request_del'   	=> $datetime
                        );
        $this->dgeneral->update('tbl_she_tr_b3_limbah', $data_row, array( 
                                                                    array(
                                                                        'kolom'=>'id',
                                                                        'value'=>$_POST['id']
                                                                    )
                                                                ));

        if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            $msg    = "Data berhasil direquest";	
            $sts    = "OK";
        }	

        $return = array('sts' => $sts, 'msg' => $msg);
        $this->general->closeDb();
        echo json_encode($return);
    }
	
    private function cancel_request_del_limbahB3($action){
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();

        $data_row   = array(
                          'request_del'    		=> 0,
                          'login_request_del'   => base64_decode($this->session->userdata("-id_user-")),
                          'tgl_request_del'   	=> $datetime
                        );
        $this->dgeneral->update('tbl_she_tr_b3_limbah', $data_row, array( 
                                                                    array(
                                                                        'kolom'=>'id',
                                                                        'value'=>$_POST['id']
                                                                    )
                                                                ));

        if($this->dgeneral->status_transaction() === FALSE){
            $this->dgeneral->rollback_transaction();
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }else{
            $this->dgeneral->commit_transaction();
            $msg    = "Data berhasil direquest";	
            $sts    = "OK";
        }	

        $return = array('sts' => $sts, 'msg' => $msg);
        $this->general->closeDb();
        echo json_encode($return);
    }


	// private function post_ba_limbahB3(){
	// 	$datetime = date("Y-m-d H:i:s");

	// 	$this->general->connectDbPortal();
	// 	$this->dgeneral->begin_transaction();

 //        $data_row   = array(
 //                          'transfer_ba_sap'    		=> 'y',
 //                          'tanggal_transfer_sap'    => $datetime,
 //                          'login_edit'        		=> base64_decode($this->session->userdata("-id_user-")),
 //                          'tanggal_edit'      		=> $datetime
 //                     );
 //        $this->dgeneral->update('tbl_she_tr_b3_limbah', $data_row, array( 
 //                                                                    array(
 //                                                                        'kolom'=>'no_berita_acara',
 //                                                                        'value'=>str_replace('-', '/', $_POST['id'])
 //                                                                    )
 //                                                                ));
		
 //    	if($this->dgeneral->status_transaction() === FALSE){
 //            $this->dgeneral->rollback_transaction();
 //            $msg    = "Proses transfer berita acara ke SAP gagal !";
 //            $sts    = "NotOK";
 //        }else{
 //            $this->dgeneral->commit_transaction();
 //            $msg    = "Berita acara berhasil dipost";	
 //            $sts    = "OK";
 //        }	
 //        $return = array('sts' => $sts, 'msg' => $msg);
 //        echo json_encode($return);
	// }	

	//-------------------------------------------------//


	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_add_perpanjang_masa_b3(){
		$this->general->connectDbPortal();
		$limbahB3 = $this->dtransactionshe->get_data_limbah_masa_b3($_POST['pabrik'], NULL);
		echo json_encode($limbahB3);
	}	

	private function get_edit_perpanjang_masa_b3(){
		$this->general->connectDbPortal();
		$limbahB3 = $this->dtransactionshe->get_edit_data_limbah_masa_b3($_POST['id']);
		echo json_encode($limbahB3);
	}	

	private function get_lasttrx(){
		$this->general->connectDbPortal();
		$limbahB3 = $this->dtransactionshe->get_lasttrx($_POST['pabrik'], $_POST['limbah']);
		echo json_encode($limbahB3);
	}	

	private function save_perpanjang_masab3(){
		$datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();

        $uploaded1 = "";
        $uploadError1 = "";

        $uploaddir  = realpath('./') . '/assets/file/she/limbahb3';
        if (!file_exists($uploaddir)) {
            mkdir($uploaddir, 0777, true);
        }
        $config['upload_path']          = $uploaddir;
        $config['allowed_types']        = 'pdf';
        // $config['max_size']             = 100;
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

		$_FILES['lampiran']['name'] = $_FILES['lampiran1']['name'];
		$_FILES['lampiran']['type'] = $_FILES['lampiran1']['type'];
		$_FILES['lampiran']['tmp_name'] = $_FILES['lampiran1']['tmp_name'];
		$_FILES['lampiran']['error'] = $_FILES['lampiran1']['error'];
		$_FILES['lampiran']['size'] = $_FILES['lampiran1']['size'];

        if ( ! $this->upload->do_upload('lampiran'))
        {
            $uploadError1= $this->upload->display_errors();
        }else{
            $upload_data1 = $this->upload->data();
            $uploaded1 = 'assets/file/she/limbahb3/'.$upload_data1['file_name'];
        }

	    $datalampiran = array();
	    if($uploaded1 != ""){
	    	$datalampiran = array('lampiran1' => $uploaded1); 
	    }

        if(isset($_POST['id'])){
        	$this->dgeneral->begin_transaction();
        	if(isset($_POST['id']) && $_POST['id'] != ""){
                $data_row   = array(
                                  'ext_days'    		=> $_POST['masaperpanjangan'],
	                              'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
	                              'tanggal_edit'      	=> $datetime
                             );
                $data_row = array_merge($data_row, $datalampiran);
                $this->dgeneral->update('tbl_she_tr_b3_limbah', $data_row, array( 
                                                                            array(
                                                                                'kolom'=>'id',
                                                                                'value'=>$_POST['id']
                                                                            )
                                                                        ));
            }else{
        		$limbah = explode("|", $_POST['chklimbah']);
				
				$cek_trx = $this->dtransactionshe->get_data_trx_ext($_POST['pabrik'], $limbah[0], $this->generate->regenerateDateFormat($_POST['tgllimbahmasuk']));        		
				$stok = $limbah[2];

				foreach ($cek_trx as $key => $value) {
	                if($stok >= $value->quantity){
	                	$stok = $stok - $value->quantity;
	                	$stok_current = $value->quantity;
	                }else{
	                	$stok_current = $stok;
	                }
	                $data_row   = array(
	                                  'fk_pabrik'     		=> $_POST['pabrik'],
	                                  'type'     			=> 'EXT',
	                                  'tanggal_masuk'    	=> $this->generate->regenerateDateFormat($value->tanggal_masuk),
	                                  'dsimpan_max'    		=> $this->generate->regenerateDateFormat($value->dsimpan_max),
	                                  'ext_days'    		=> $_POST['masaperpanjangan'],
	                                  // 'stok'    			=> $limbah[2],
	                                  'fk_limbah'    		=> $limbah[0],
	                                  'stok'    			=> $stok_current,
	                                  'del'    				=> 1,
		                              'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
		                              'tanggal_buat'      	=> $datetime
		                            );
	                $data_row = array_merge($data_row, $datalampiran);
	                $this->dgeneral->insert('tbl_she_tr_b3_limbah', $data_row);
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
        }else{
            $msg    = "Periksa kembali data yang dimasukkan";
            $sts    = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//

	private function save_import_bulanan($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		if(!empty($_FILES['file_excel']['name'])){
			$target_dir = "./assets/temp";

			if (!file_exists($target_dir)) {
			    mkdir($target_dir, 0755, true);
			}

			$config['upload_path']          = $target_dir;
			$config['allowed_types']        = 'xls|xlsx';
	 
			$this->load->library('upload', $config);
	 
			if ( ! $this->upload->do_upload('file_excel')){
				$msg 	= $this->upload->display_errors();
				$sts 	= "NotOK";
			}else{
				$data 			= array('upload_data' => $this->upload->data());
				$objPHPExcel 	= PHPExcel_IOFactory::load($data['upload_data']['full_path']);
				$title_desc		= $objPHPExcel->getProperties()->getTitle();
				$objPHPExcel->setActiveSheetIndex(1);
				$data_excel		= $objPHPExcel->getActiveSheet();
				$highestRow 	= $data_excel->getHighestRow(); 
				$highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
				$datetime		= date("Y-m-d H:i:s");
				$data_row		= array();
				for($brs=3; $brs<=$highestRow; $brs++){
					$pabrik 			= $data_excel->getCellByColumnAndRow(1, $brs)->getCalculatedValue();
					$fk_pabrik 			= $data_excel->getCellByColumnAndRow(2, $brs)->getCalculatedValue();
					$fk_kategori		= $data_excel->getCellByColumnAndRow(4, $brs)->getCalculatedValue();
					$fk_jenis			= $data_excel->getCellByColumnAndRow(6, $brs)->getCalculatedValue();
					$tanggal_sampling	= PHPExcel_Style_NumberFormat::toFormattedString($data_excel->getCellByColumnAndRow(7, $brs)->getValue(), 'YYYY-MM-DD');
					$tanggal_analisa	= PHPExcel_Style_NumberFormat::toFormattedString($data_excel->getCellByColumnAndRow(8, $brs)->getValue(), 'YYYY-MM-DD');
					$fk_parameter		= $data_excel->getCellByColumnAndRow(10, $brs)->getCalculatedValue();
					$hasil_uji			= $data_excel->getCellByColumnAndRow(11, $brs)->getValue();
					$lampiran			= $data_excel->getCellByColumnAndRow(12, $brs)->getValue();
					$data_row	= array(
									'fk_pabrik'			=> $fk_pabrik,
									'fk_kategori'		=> $fk_kategori,
									'fk_jenis'	 		=> $fk_jenis,
									'tanggal_sampling'	=> $tanggal_sampling, 
									'tanggal_analisa' 	=> $tanggal_analisa,
									'fk_parameter'		=> $fk_parameter,
									'hasil_uji'			=> $hasil_uji,
									'lampiran'			=> $lampiran,
									'login_buat' 		=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'		=> $datetime,
									'login_edit' 		=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit' 		=> $datetime,
									'na' 				=> '1',
									'del'				=> '1'
								);	
					if(($fk_pabrik>=0)and($pabrik!='')){
						$ck_data 	= $this->dtransactionshe->cek_data_limbah_air_bulanan(NULL, NULL, $fk_pabrik, $fk_kategori, $fk_jenis, $tanggal_sampling, $tanggal_analisa, $fk_parameter);
						if(empty($ck_data[0]->id)){
							$this->dgeneral->insert('tbl_she_tr_airlimbah_bulanan', $data_row);	
						}
					}			
				}
				if($this->dgeneral->status_transaction() === FALSE){
					$this->dgeneral->rollback_transaction();
					$msg 	= "Periksa kembali data yang diunggah";
					$sts 	= "NotOK";
				}else{
					$this->dgeneral->commit_transaction();
					$msg 	= "Data berhasil ditambahkan";
					$sts 	= "OK";
				}
				
				unlink($data['upload_data']['full_path']);
			}
		}else{
			$msg 	= "Silahkan pilih file yang ingin diunggah";
			$sts 	= "NotOK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		
	}
	//import data limbah air harian
	private function save_import_harian($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		if(!empty($_FILES['file_excel']['name'])){
			$target_dir = "./assets/temp";

			if (!file_exists($target_dir)) {
			    mkdir($target_dir, 0755, true);
			}

			$config['upload_path']          = $target_dir;
			$config['allowed_types']        = 'xls|xlsx';
	 
			$this->load->library('upload', $config);
	 
			if ( ! $this->upload->do_upload('file_excel')){
				$msg 	= $this->upload->display_errors();
				$sts 	= "NotOK";
			}else{
				$data 			= array('upload_data' => $this->upload->data());
				$objPHPExcel 	= PHPExcel_IOFactory::load($data['upload_data']['full_path']);
				$title_desc		= $objPHPExcel->getProperties()->getTitle();
				$objPHPExcel->setActiveSheetIndex(0);
				$data_excel		= $objPHPExcel->getActiveSheet();
				$highestRow 	= $data_excel->getHighestRow(); 
				$highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
				$datetime		= date("Y-m-d H:i:s");
				$data_row		= array();
				for($brs=3; $brs<=$highestRow; $brs++){
					$pabrik 			= $data_excel->getCellByColumnAndRow(1, $brs)->getCalculatedValue();
					$fk_pabrik 			= $data_excel->getCellByColumnAndRow(2, $brs)->getCalculatedValue();
					$tanggal			= PHPExcel_Style_NumberFormat::toFormattedString($data_excel->getCellByColumnAndRow(3, $brs)->getValue(), 'YYYY-MM-DD');
					$sba_do				= (($data_excel->getCellByColumnAndRow(4, $brs)->getValue()=='-')or($data_excel->getCellByColumnAndRow(4, $brs)->getValue()==NULL))?0:$data_excel->getCellByColumnAndRow(4, $brs)->getValue();
					$sba_sv				= (($data_excel->getCellByColumnAndRow(5, $brs)->getValue()=='-')or($data_excel->getCellByColumnAndRow(5, $brs)->getValue()==NULL))?0:$data_excel->getCellByColumnAndRow(5, $brs)->getValue();
					$sba_ph				= (($data_excel->getCellByColumnAndRow(6, $brs)->getValue()=='-')or($data_excel->getCellByColumnAndRow(6, $brs)->getValue()==NULL))?0:$data_excel->getCellByColumnAndRow(6, $brs)->getValue();
					$sd_do				= (($data_excel->getCellByColumnAndRow(7, $brs)->getValue()=='-')or($data_excel->getCellByColumnAndRow(7, $brs)->getValue()==NULL))?0:$data_excel->getCellByColumnAndRow(7, $brs)->getValue();
					$slb_sv				= (($data_excel->getCellByColumnAndRow(8, $brs)->getValue()=='-')or($data_excel->getCellByColumnAndRow(8, $brs)->getValue()==NULL))?0:$data_excel->getCellByColumnAndRow(8, $brs)->getValue();
					$oi_debit			= (($data_excel->getCellByColumnAndRow(9, $brs)->getValue()=='-')or($data_excel->getCellByColumnAndRow(9, $brs)->getValue()==NULL))?0:$data_excel->getCellByColumnAndRow(9, $brs)->getValue();
					$oi_debit_standar	= 0;
					$oi_ph				= (($data_excel->getCellByColumnAndRow(10, $brs)->getValue()=='-')or($data_excel->getCellByColumnAndRow(10, $brs)->getValue()==NULL))?0:$data_excel->getCellByColumnAndRow(10, $brs)->getValue();
					$bi_transparansi	= (($data_excel->getCellByColumnAndRow(11, $brs)->getValue()=='-')or($data_excel->getCellByColumnAndRow(11, $brs)->getValue()==NULL))?0:$data_excel->getCellByColumnAndRow(11, $brs)->getValue();
					$data_row	= array(
									'fk_pabrik'			=> $fk_pabrik,
									'tanggal'			=> $tanggal,
									'sba_do'			=> $sba_do, 
									'sba_sv' 			=> $sba_sv, 
									'sba_ph'			=> $sba_ph, 
									'sd_do'				=> $sd_do, 
									'slb_sv'			=> $slb_sv, 
									'oi_debit'			=> $oi_debit, 
									'oi_debit_standar'	=> $oi_debit_standar, 
									'oi_ph'				=> $oi_ph, 
									'bi_transparansi'	=> $bi_transparansi, 
									'login_buat' 		=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'		=> $datetime,
									'login_edit' 		=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit' 		=> $datetime,
									'na' 				=> '1',
									'del'				=> '1'
								);	
					if(($fk_pabrik>=0)and($pabrik!='')){
						$ck_data 	= $this->dtransactionshe->cek_data_limbah_air_harian(NULL, NULL, $fk_pabrik, $tanggal);
						if(empty($ck_data[0]->id)){
							$this->dgeneral->insert('tbl_she_tr_airlimbah_harian', $data_row);	
						}
					}			
				}
				if($this->dgeneral->status_transaction() === FALSE){
					$this->dgeneral->rollback_transaction();
					$msg 	= "Periksa kembali data yang diunggah";
					$sts 	= "NotOK";
				}else{
					$this->dgeneral->commit_transaction();
					$msg 	= "Data berhasil ditambahkan";
					$sts 	= "OK";
				}
				
				unlink($data['upload_data']['full_path']);
			}
		}else{
			$msg 	= "Silahkan pilih file yang ingin diunggah";
			$sts 	= "NotOK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		
	}


}