<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KLEMS (Kirana Learning Management System)
@author     : Lukman Hakim (7143)
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
	    $this->load->model('dmasterklems');
	}

	public function index(){
		show_404();
	}
	public function soal(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Soal";
		$data['title_form'] = "Masukan Soal";
		$data['bpo']   		= $this->dmasterklems->get_opt_bpo(NULL, 'all');
		$data['topik'] 		= $this->dmasterklems->get_opt_topik(NULL, 'all');
		$data['soal_tipe']	= $this->dmasterklems->get_opt_soal_tipe(NULL, 'all');
		$data['soal']     	= $this->dmasterklems->get_data_soal('open',NULL, 'all');
		$this->load->view("master/soal", $data);
	}
	public function tipesoal(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Tipe Soal";
		$data['title_form']    	= "Masukan Tipe Soal";
		$data['soal_tipe']     	= $this->dmasterklems->get_data_soal_tipe('open',NULL, 'all');
		$this->load->view("master/tipesoal", $data);
	}
	public function tahap(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Tahap";
		$data['title_form'] = "Masukan Tahap";
		$data['bpo']   		= $this->dmasterklems->get_opt_bpo(NULL, 'all');
		$data['program']	= $this->dmasterklems->get_opt_program(NULL, 'all');
		$data['topik'] 		= $this->dmasterklems->get_opt_topik(NULL, 'all');
		$data['tahap'] 		= $this->dmasterklems->get_data_tahap('open',NULL, 'all');
		$this->load->view("master/tahap", $data);
	}	
	public function signature(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Tanda Tangan";
		$data['title_form']    	= "Masukan Tanda Tangan";
		$data['signature'] 		= $this->dmasterklems->get_data_signature('open',NULL, 'all');
		$this->load->view("master/signature", $data);
	}	
	public function topik(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Topik";
		$data['title_form']    	= "Masukan Topik";
		$data['bpo']   			= $this->dmasterklems->get_opt_bpo(NULL, 'all');
		$data['topik']   		= $this->dmasterklems->get_data_topik('open',NULL, 'all');
		$this->load->view("master/topik", $data);
	}
	private function topiktrainer($topik=NULL){
		if($topik==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    			= "Trainer Untuk Topik";
		$data['title_form']    		= "Masukan Topik Trainner";
		$data['trainer_internal']	= $this->dmasterklems->get_opt_karyawan(NULL, 'all');
		$data['trainer_eksternal']	= $this->dmasterklems->get_opt_trainer(NULL, 'all');
		$data['topik_trainer']		= $this->dmasterklems->get_data_topik_trainer('open',$topik, 'all');
		$data['topik']				= $this->dmasterklems->get_data_topik('open',NULL, 'all',$topik);
		$this->load->view("master/topiktrainer", $data);
	}
	private function topikmateri($topik=NULL){
		if($topik==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    			= "Materi Topik";
		$data['title_form']    		= "Masukan Materi Topik";
		$data['topik_materi']		= $this->dmasterklems->get_data_topik_materi('open',$topik, 'all');
		$data['topik']				= $this->dmasterklems->get_data_topik('open',NULL, 'all',$topik);
		$this->load->view("master/topikmateri", $data);
	}
	public function program(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Program";
		$data['title_form']    	= "Masukan Program";
		$data['sertifikat']		= $this->dmasterklems->get_opt_sertifikat(NULL, 'all');
		$data['program']   		= $this->dmasterklems->get_data_program('open',NULL, 'all');
		$this->load->view("master/program", $data);
	}
	private function programbudget($program=NULL){
		if($program==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    			= "Budget Program";
		$data['title_form']    		= "Masukan Budget Program";
		$data['program_budget']		= $this->dmasterklems->get_data_program_budget('open',$program, 'all');
		$data['program']			= $this->dmasterklems->get_data_program('open',NULL, 'all',$program);
		$this->load->view("master/programbudget", $data);
	}
	private function programmatrix($program=NULL){
		if($program==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		  = "Program Matrix";
		$data['title_form']    	  = "Masukan Program Matrix";
		$data['program']		  = $this->dmasterklems->get_data_program('open',NULL, 'all',$program);
		$data['level']			  = $this->dmasterklems->get_opt_jabatan(NULL, 'all');
		$data['organisasi_level'] = $this->dmasterklems->get_opt_level(NULL, 'all');
		$data['posisi']			  = $this->dmasterklems->get_opt_posisi(NULL, 'all');
		$data['divisi'] 		  = $this->dmasterklems->get_opt_level(NULL, 'all');
		// $data['level_nama']   	  = $this->dmasterklems->get_data_level_nama(NULL,"n");
		$data['program_matrix']	  = $this->dmasterklems->get_data_program_matrix('open',$program, 'all');
		$this->load->view("master/programmatrix", $data);
	}
	public function evitem(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Item Kuesioner";
		$data['title_form']    	= "Masukan Item Kuesioner";
		$data['kategori']		= $this->dmasterklems->get_opt_kategori(NULL, 'all');
		$data['evitem']			= $this->dmasterklems->get_data_evitem('open',NULL, 'all');
		$this->load->view("master/evitem", $data);
	}
	public function nil_nilai(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Penilaian";
		$data['title_form']    	= "Masukan Penilaian";
		$data['nil_kategori']	= $this->dmasterklems->get_data_nil_kategori('open',NULL, 'all');
		$data['nil_nilai']		= $this->dmasterklems->get_data_nil_nilai('open',NULL, 'all');
		$this->load->view("master/nil_nilai", $data);
	}
	public function evkategori(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Jenis & Kategori";
		$data['title_form']    	= "Masukan Jenis & Kategori";
		$data['evkategori']		= $this->dmasterklems->get_data_evkategori('open',NULL, 'all');
		$this->load->view("master/evkategori", $data);
	}
	public function nil_kategori(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Kategori Penilaian";
		$data['title_form']    	= "Masukan Kategori Penilaian";
		$data['nil_kategori']	= $this->dmasterklems->get_data_nil_kategori('open',NULL, 'all');
		$this->load->view("master/nil_kategori", $data);
	}
	public function evskala(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Skala Nilai";
		$data['title_form']    	= "Masukan Skala Nilai";
		$data['evskala']	= $this->dmasterklems->get_data_evskala('open',NULL, 'all');
		$this->load->view("master/evskala", $data);
	}
	public function evindex(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Targeted Index";
		$data['title_form']    	= "Masukan Targeted Index";
		$data['evindex']		= $this->dmasterklems->get_data_evindex('open',NULL, 'all');
		$this->load->view("master/evindex", $data);
	}
	public function trainer(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Trainer Eksternal";
		$data['title_form']    	= "Masukan Trainer Eksternal";
		$data['institusi'] 		= $this->dmasterklems->get_master_institusi();
		$data['trainer']     	= $this->dmasterklems->get_data_trainer('open',NULL, 'all');
		$this->load->view("master/trainer", $data);
	}
	public function institusi(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Institusi";
		$data['title_form']    	= "Masukan Institusi";
		$data['spesialis']		= $this->dmasterklems->get_master_spesialis();
		$data['institusi']     	= $this->dmasterklems->get_data_institusi('open',NULL, 'all');
		$this->load->view("master/institusi", $data);
	}
	
	public function spesialis(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Spesialis";
		$data['title_form']    	= "Masukan Spesialis";
		$data['spesialis']     	= $this->dmasterklems->get_data_spesialis('open',NULL, 'all');
		$this->load->view("master/spesialis", $data);
	}

	public function bpo(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "BPO";
		$data['title_form']    	= "Masukan BPO";
		$data['bpo']     		= $this->dmasterklems->get_data_bpo('open',NULL, 'all');
		$this->load->view("master/bpo", $data);
	}
	public function grade(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Grade";
		$data['title_form']    	= "Masukan Grade";
		$data['grade']     		= $this->dmasterklems->get_data_grade('open',NULL, 'all');
		$this->load->view("master/grade", $data);
	}

	//=================================//
	//		  MIRRORING URL 		   //
	//=================================//
	public function data($url=NULL,$param=NULL){
		switch ($url) {
			case 'trainer':
				$this->topiktrainer($param);
				break;
			case 'materi':
				$this->topikmateri($param);
				break;
			case 'budget':
				$this->programbudget($param);
				break;
			case 'matrix':
				$this->programmatrix($param);
				break;
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_user(){
		return $this->general->get_user_autocomplete();
	}

	public function get_data($param){
		switch ($param) {
			case 'posisi_cek':
				$this->get_posisi_cek();
				break;
			case 'soal':
				$this->get_soal();
				break;
			case 'soal_tipe':
				$this->get_soal_tipe();
				break;
			case 'tahap_cek':
				$this->get_tahap_cek();
				break;
			case 'tahap':
				$this->get_tahap();
				break;
			case 'signature':
				$this->get_signature();
				break;
			case 'topik':
				$this->get_topik();
				break;
			case 'topik_cek':
				$this->get_topik_cek();
				break;
			case 'topik_trainer':
				$this->get_topik_trainer();
				break;
			case 'topik_materi':
				$this->get_topik_materi();
				break;
			case 'program':
				$this->get_program();
				break;
			case 'program_budget':
				$this->get_program_budget();
				break;
			case 'program_matrix':
				$this->get_program_matrix();
				break;
			case 'evitem':
				$this->get_evitem();
				break;
			case 'evkategori':
				$this->get_evkategori();
				break;
			case 'nil_kategori_cek':
				$this->get_nil_kategori_cek();
				break;
			case 'nil_kategori':
				$this->get_nil_kategori();
				break;
			case 'nil_nilai':
				$this->get_nil_nilai();
				break;
			case 'nil_nilai_cek':
				$this->get_nil_nilai_cek();
				break;
			case 'evskala':
				$this->get_evskala();
				break;
			case 'evindex':
				$this->get_evindex();
				break;
			case 'trainer':
				$this->get_trainer();
				break;
			case 'institusi':
				$this->get_institusi();
				break;
			case 'spesialis':
				$this->get_spesialis();
				break;
			case 'bpo':
				$this->get_bpo();
				break;
			case 'grade':
				$this->get_grade();
				break;
			case 'program_budget_cek':
				$this->get_program_budget_cek();
				break;
			case 'evskala_cek':
				$this->get_evskala_cek();
				break;
			case 'posisi':
				$this->get_posisi();
				break;
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function set_data($action, $param){
		switch ($param) {
			case 'soal':
				$this->set_soal($action);
				break;
			case 'soal_tipe':
				$this->set_soal_tipe($action);
				break;
			case 'tahap':
				$this->set_tahap($action);
				break;
			case 'signature':
				$this->set_signature($action);
				break;
			case 'topik':
				$this->set_topik($action);
				break;
			case 'topik_trainer':
				$this->set_topik_trainer($action);
				break;
			case 'topik_materi':
				$this->set_topik_materi($action);
				break;
			case 'program':
				$this->set_program($action);
				break;
			case 'program_budget':
				$this->set_program_budget($action);
				break;
			case 'program_matrix':
				$this->set_program_matrix($action);
				break;
			case 'evitem':
				$this->set_evitem($action);
				break;
			case 'evkategori':
				$this->set_evkategori($action);
				break;
			case 'nil_kategori':
				$this->set_nil_kategori($action);
				break;
			case 'nil_nilai':
				$this->set_nil_nilai($action);
				break;
			case 'evskala':
				$this->set_evskala($action);
				break;
			case 'evindex':
				$this->set_evindex($action);
				break;
			case 'trainer':
				$this->set_trainer($action);
				break;
			case 'institusi':
				$this->set_institusi($action);
				break;
			case 'spesialis':
				$this->set_spesialis($action);
				break;
			case 'bpo':
				$this->set_bpo($action);
				break;
			case 'grade':
				$this->set_grade($action);
				break;
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function save($param){
		switch ($param) {
			case 'soal':
				$this->save_soal();
				break;
			case 'soal_tipe':
				$this->save_soal_tipe();
				break;
			case 'tahap':
				$this->save_tahap();
				break;
			case 'signature':
				$this->save_signature();
				break;
			case 'topik':
				$this->save_topik();
				break;
			case 'topik_trainer':
				$this->save_topik_trainer();
				break;
			case 'topik_materi':
				$this->save_topik_materi();
				break;
			case 'program':
				$this->save_program();
				break;
			case 'program_budget':
				$this->save_program_budget();
				break;
			case 'program_matrix':
				$this->save_program_matrix();
				break;
			case 'evitem':
				$this->save_evitem();
				break;
			case 'evkategori':
				$this->save_evkategori();
				break;
			case 'nil_kategori':
				$this->save_nil_kategori();
				break;
			case 'nil_nilai':
				$this->save_nil_nilai();
				break;
			case 'evskala':
				$this->save_evskala();
				break;
			case 'evindex':
				$this->save_evindex();
				break;
			case 'trainer':
				$this->save_trainer();
				break;
			case 'institusi':
				$this->save_institusi();
				break;
			case 'spesialis':
				$this->save_spesialis();
				break;
			case 'bpo':
				$this->save_bpo();
				break;
			case 'grade':
				$this->save_grade();
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

	private function get_posisi_cek(){
		if((isset($_POST['jabatan']))or(isset($_POST['level']))){
			$jabatan 	= (empty($_POST['jabatan']))?null:$_POST['jabatan'];
			$level 		= (empty($_POST['level']))?null:$_POST['level'];
			$posisi	= $this->dmasterklems->get_data_posisi('open',null,$jabatan,$level);
			if(!empty($posisi[0]->id_posisi)){
				$data_json  = array(
										"total_count" => count($data),
										"incomplete_results"=>false,
										"items"=>$data
									  );
				echo json_encode($data_json);					  
			}
		}
		
		$nil_kategori	= $this->dmasterklems->get_data_nil_kategori('open',null, null, $_POST['nama']);
		if(!empty($nil_kategori[0]->id_nilai_kategori)){
			$nil_kategori[0]->id_nilai_kategori = $this->generate->kirana_encrypt($nil_kategori[0]->id_nilai_kategori);
			echo json_encode($nil_kategori);	
		}
		
	}
	
	private function get_posisi(){
		if(isset($_GET['q'])){
			$jabatan 	= (empty($_GET['level']))?null:$_GET['level'];
			$level 		= (empty($_GET['organisasi_level']))?null:$_GET['organisasi_level'];
            $data       = $this->dmasterklems->get_data_posisi('open',$_GET['q'],$jabatan,$level);
            $data_json  = array(
									"total_count" => count($data),
									"incomplete_results"=>false,
									"items"=>$data
								  );
            echo json_encode($data_json);
        }
	}
	
	private function get_soal(){
		if(isset($_POST['id_soal'])){
			$soal	= $this->dmasterklems->get_data_soal('open',$this->generate->kirana_decrypt($_POST['id_soal']), 'all');
			$soal[0]->id_soal	= $_POST['id_soal'];
			echo json_encode($soal);
		}
	}

    private function set_soal($action){
        $id_soal = $this->generate->kirana_decrypt($_POST['id_soal']);
		$this->general->connectDbPortal();
        $delete  = $this->general->set($action, "tbl_soal", array(
																	array(
																		'kolom'=>'id_soal',
																		'value'=>$id_soal
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_soal(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		//==========================UPLOAD GAMBAR SOAL==============================//
		if(count($_FILES['gambar']['name']) > 1){
			$msg        = "You can only upload maximum 1 file";
			$sts        = "NotOK";
			$return     = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		if(!empty($_FILES['gambar']['name'][0])){
			$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/soal';
			$config['allowed_types'] 	= 'png|jpg|gif';			
			$newname 					= array($_POST['kode']);			
			$gambar						= $this->general->upload_files($_FILES['gambar'], $newname, $config);
			if($gambar === NULL){
				$msg        = "Upload files error";
				$sts        = "NotOK";
				$return     = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
		}

		//========================================================================//
		if(isset($_POST['id_soal']) && $_POST['id_soal'] != ""){
			
			// if(count($_FILES['gambar']['name'][0]) > 1){
			if(!empty($_FILES['gambar']['name'][0])){	
				$data_row   = array(
							'id_bpo' 		    => $_POST['id_bpo'],
							'id_topik'	   	 	=> $_POST['id_topik'],
							'id_soal_tipe'    	=> $_POST['id_soal_tipe'],
							'kode' 		    	=> $_POST['kode'],
							'soal'     			=> $_POST['soal'],
							'gambar'   			=> base_url().''.$gambar[0]['url'],
							'login_edit'     	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'    	=> $datetime
						 );
			}else{
				$data_row   = array(
							'id_bpo' 		    => $_POST['id_bpo'],
							'id_topik'	   	 	=> $_POST['id_topik'],
							'id_soal_tipe'    	=> $_POST['id_soal_tipe'],
							'kode' 		    	=> $_POST['kode'],
							'soal'     			=> $_POST['soal'],
							'login_edit'     	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'    	=> $datetime
						 );
			}			 
			$id_soal = $this->generate->kirana_decrypt($_POST['id_soal']);
			$this->dgeneral->update('tbl_soal', $data_row, array( 
																array(
																	'kolom'=>'id_soal',
																	'value'=>$id_soal
																)
															));
															
			for($i=0;$i<=3;$i++){
				$data_ans_row   = array(
									'id_soal' 		    => $id_soal,
									'nama'		   	 	=> $i+1,
									'jawaban' 	 	  	=> $_POST['jawaban'.($i+1)],
									'benar'		    	=> ($i==0?'y':'n'),
									'na'     			=> 'n',
									'del'     			=> 'n',
									'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'      => $datetime,
									'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit'      => $datetime
								);
				$this->dgeneral->update('tbl_soal_jawaban', $data_ans_row, array( 
															array(
																'kolom'=>'id_soal',
																'value'=>$id_soal
															),
															array(
																'kolom'=>'nama',
																'value'=>$i+1
															)														
														));
			}
		}else{
			// if(count($_FILES['gambar']['name']) > 1){
			if(!empty($_FILES['gambar']['name'][0])){	
				$data_row   = array(
								'id_bpo' 		    => $_POST['id_bpo'],
								'id_topik'	   	 	=> $_POST['id_topik'],
								'id_soal_tipe'    	=> $_POST['id_soal_tipe'],
								'kode' 		    	=> $_POST['kode'],
								'soal'     			=> $_POST['soal'],
								'gambar'    		=> base_url().''.$gambar[0]['url'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			}else{
				$data_row   = array(
								'id_bpo' 		    => $_POST['id_bpo'],
								'id_topik'	   	 	=> $_POST['id_topik'],
								'id_soal_tipe'    	=> $_POST['id_soal_tipe'],
								'kode' 		    	=> $_POST['kode'],
								'soal'     			=> $_POST['soal'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			}				
			$this->dgeneral->insert('tbl_soal', $data_row);
			$id_soal = $this->db->insert_id();
			$data_ans_batch	= array();
			for($i=0;$i<=3;$i++){
					
				$data_ans_row   = array(
									'id_soal' 		    => $id_soal,
									'nama'		   	 	=> $i+1,
									'jawaban' 	 	  	=> $_POST['jawaban'.($i+1)],
									'benar'		    	=> ($i==0?'y':'n'),
									'na'     			=> 'n',
									'del'     			=> 'n',
									'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'      => $datetime,
									'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit'      => $datetime
								);
				array_push($data_ans_batch, $data_ans_row);// 
				// echo json_encode($data_ans_row);
			}
			$this->dgeneral->insert_batch('tbl_soal_jawaban', $data_ans_batch);
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

	//-------------------------------------------------//
	
	
	private function get_soal_tipe(){
		if(isset($_POST['id_soal_tipe'])){
			$soal_tipe         	= $this->dmasterklems->get_data_soal_tipe('open',$this->generate->kirana_decrypt($_POST['id_soal_tipe']), 'all');
			$soal_tipe[0]->id_soal_tipe	= $_POST['id_soal_tipe'];
			echo json_encode($soal_tipe);
		}
	}

    private function set_soal_tipe($action){
        $id_soal_tipe = $this->generate->kirana_decrypt($_POST['id_soal_tipe']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_soal_tipe", array(
																	array(
																		'kolom'=>'id_soal_tipe',
																		'value'=>$id_soal_tipe
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_soal_tipe(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_soal_tipe']) && $_POST['id_soal_tipe'] != ""){
			$id_soal_tipe = $this->generate->kirana_decrypt($_POST['id_soal_tipe']);
			$data_row   = array(
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'waktu'     		=> $_POST['waktu'],
							  'keterangan'     	=> $_POST['keterangan'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_soal_tipe', $data_row, array( 
																array(
																	'kolom'=>'id_soal_tipe',
																	'value'=>$id_soal_tipe
																)
															));
		}else{
			$data_row   = array(
								'kode'    			=> $_POST['kode'],
								'nama'     			=> $_POST['nama'],
								'waktu'    			=> $_POST['waktu'],
								'keterangan'     	=> $_POST['keterangan'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_soal_tipe', $data_row);
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

	//-------------------------------------------------//
	private function get_tahap_cek(){
		$tahap	= $this->dmasterklems->get_data_tahap('open',null, null, $_POST['id_bpo'], $_POST['id_program'], $_POST['nama']);
		if(!empty($tahap[0]->id_tahap)){
			$tahap[0]->id_tahap = $this->generate->kirana_encrypt($tahap[0]->id_tahap);
			echo json_encode($tahap);
		}
	}
	
	private function get_tahap(){
		if(isset($_POST['id_tahap'])){
			$id_tahap 			= $this->generate->kirana_decrypt($_POST['id_tahap']);
			$tahap				= $this->dmasterklems->get_data_tahap('open',$id_tahap, 'all');
			$tahap[0]->id_tahap = $_POST['id_tahap'];
			echo json_encode($tahap);
		}
	}

    private function set_tahap($action){
        $id_tahap = $this->generate->kirana_decrypt($_POST['id_tahap']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_tahap", array(
																	array(
																		'kolom'=>'id_tahap',
																		'value'=>$id_tahap
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_tahap(){
		$datetime	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_tahap']) && $_POST['id_tahap'] != ""){
			$id_tahap = $this->generate->kirana_decrypt($_POST['id_tahap']);
			$data_row   = array(
							  'id_bpo' 	    	=> $_POST['id_bpo'],
							  'id_program' 	    => $_POST['id_program'],
							  'kode'		    => $_POST['kode'],
							  'nama'		    => $_POST['nama'],
							  'keterangan'	    => $_POST['keterangan'],
							  'topik'			=> implode(",", $_POST['topik']),
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_tahap', $data_row, array( 
																array(
																	'kolom'=>'id_tahap',
																	'value'=>$id_tahap
																)
															));
		}else{
			$data_row   = array(
								'id_bpo' 	    	=> $_POST['id_bpo'],
								'id_program' 	    => $_POST['id_program'],
								'kode'		  	  	=> $_POST['kode'],
								'nama'		    	=> $_POST['nama'],
								'keterangan'	    => $_POST['keterangan'],
								'topik'				=> implode(",", $_POST['topik']),
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_tahap', $data_row);
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

	//-------------------------------------------------//
	
	private function get_signature(){
		if(isset($_POST['id_tandatangan'])){
			$id_tandatangan = $this->generate->kirana_decrypt($_POST['id_tandatangan']);
			$signature	= $this->dmasterklems->get_data_signature('open',$id_tandatangan, 'all');
			$signature[0]->id_tandatangan	= $_POST['id_tandatangan'];
			echo json_encode($signature);
		}
	}

    private function set_signature($action){
        $id_tandatangan = $this->generate->kirana_decrypt($_POST['id_tandatangan']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_tandatangan", array(
																	array(
																		'kolom'=>'id_tandatangan',
																		'value'=>$id_tandatangan
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_signature(){
		$datetime  = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		//==========================UPLOAD TTD==============================//
		if(count($_FILES['gambar']['name']) > 1){
			$msg        = "You can only upload maximum 1 file";
			$sts        = "NotOK";
			$return     = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		if($_FILES['gambar']['name'][0]!=''){
			$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/ttd';
			$config['allowed_types'] 	= 'png';			
			$newname 					= array($_POST['nik']);			
			$gambar						= $this->general->upload_files($_FILES['gambar'], $newname, $config);
			$url_gambar					= $gambar[0]['url'];
			if($gambar === NULL){
				$msg        = "Upload files error";
				$sts        = "NotOK";
				$return     = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
		}else{
			$url_gambar					= $_POST['gambar_url'];
		}
		//========================================================================//
					
		if(isset($_POST['id_tandatangan']) && $_POST['id_tandatangan'] != ""){
			$id_tandatangan = $this->generate->kirana_decrypt($_POST['id_tandatangan']);	
			$data_row   = array(
							  'nik' 		    	=> $_POST['nik'],
							  'gambar'     			=> $url_gambar,
							  'posisi_sertifikat'	=> $_POST['posisi_sertifikat'],
							  'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    	=> $datetime
						 );
			$this->dgeneral->update('tbl_tandatangan', $data_row, array( 
																array(
																	'kolom'=>'id_tandatangan',
																	'value'=>$id_tandatangan
																)
															));
			
		}else{
			$data_row   = array(
								'nik'	 		    => $_POST['nik'],
								'gambar'     		=> $url_gambar,
								'posisi_sertifikat'	=> $_POST['posisi_sertifikat'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_tandatangan', $data_row);
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

	//-------------------------------------------------//


	private function get_topik_trainer(){
		if(isset($_POST['id_topik_trainer'])){
			$id_topik_trainer = $this->generate->kirana_decrypt($_POST['id_topik_trainer']);
			$topik_trainer	= $this->dmasterklems->get_data_topik_trainer('open','', 'all',$id_topik_trainer);
			$topik_trainer[0]->id_topik_trainer	= $_POST['id_topik_trainer'];
			echo json_encode($topik_trainer);
		}
	}

    private function set_topik_trainer($action){
        $id_topik_trainer = $this->generate->kirana_decrypt($_POST['id_topik_trainer']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_topik_trainer", array(
																	array(
																		'kolom'=>'id_topik_trainer',
																		'value'=>$id_topik_trainer
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_topik_trainer(){
		$datetime  = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		// echo json_encode($_POST['id_topik']);
		// exit();	
		if((isset($_POST['trainer']))){
			$id_trainer = $_POST['id_trainer_eksternal'];	
			$trainer 	= 'luar';
		}else{
			$id_trainer = $_POST['id_trainer_internal'];	
			$trainer 	= 'dalam';
		}
		// echo json_encode($_POST['trainer']);
		// exit();
		if(isset($_POST['id_topik_trainer']) && $_POST['id_topik_trainer'] != ""){
			$id_topik_trainer = $this->generate->kirana_decrypt($_POST['id_topik_trainer']);
			$data_row   = array(
							  'id_topik' 	   	=> $_POST['id_topik'],
							  'id_trainer'	    => $id_trainer,
							  'trainer'    		=> $trainer,
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_topik_trainer', $data_row, array( 
																array(
																	'kolom'=>'id_topik_trainer',
																	'value'=>$id_topik_trainer
																)
															));
		}else{
			$data_row   = array(
								'id_topik'	 	   	=> $_POST['id_topik'],
								'id_trainer'	    => $id_trainer,
								'trainer'    		=> $trainer,
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_topik_trainer', $data_row);
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

	//-------------------------------------------------//
	private function get_topik_cek(){
		$topik	= $this->dmasterklems->get_data_topik('open',null, null, null, $_POST['nama'], $_POST['id_bpo']);
		if(!empty($topik[0]->id_topik)){
			$topik[0]->id_topik	= $this->generate->kirana_encrypt($topik[0]->id_topik); 
			echo json_encode($topik);	
		}
	}
	private function get_topik(){
		if(isset($_POST['id_topik'])){
			$id_topik = $this->generate->kirana_decrypt($_POST['id_topik']);
			$topik	= $this->dmasterklems->get_data_topik('open',$id_topik, 'all');
			$topik[0]->id_topik = $_POST['id_topik'];
			echo json_encode($topik);
		}
	}

    private function set_topik($action){
        $id_topik = $this->generate->kirana_decrypt($_POST['id_topik']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_topik", array(
																	array(
																		'kolom'=>'id_topik',
																		'value'=>$id_topik
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_topik(){
		$datetime  = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		// echo json_encode($_POST['id_topik']);
		// exit();	
		if(isset($_POST['id_topik']) && $_POST['id_topik'] != ""){
			$id_topik = $this->generate->kirana_decrypt($_POST['id_topik']);
			$data_row   = array(
							  'id_bpo' 	   		=> $_POST['id_bpo'],
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'abbreviation' 	=> $_POST['abbreviation'],
							  'minimal_soal'	=> $_POST['minimal_soal'],
							  'tujuan'			=> $_POST['tujuan'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_topik', $data_row, array( 
																array(
																	'kolom'=>'id_topik',
																	'value'=>$id_topik
																)
															));
		}else{
			$data_row   = array(
								'id_bpo' 		    => $_POST['id_bpo'],
								'kode' 		    	=> $_POST['kode'],
								'nama'     			=> $_POST['nama'],
								'abbreviation' 		=> $_POST['abbreviation'],
								'minimal_soal'		=> $_POST['minimal_soal'],
								'tujuan'			=> $_POST['tujuan'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_topik', $data_row);
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

	//-------------------------------------------------//

	private function get_topik_materi(){
		if(isset($_POST['id_materi'])){
			$id_materi = $this->generate->kirana_decrypt($_POST['id_materi']);
			$topik_materi	= $this->dmasterklems->get_data_topik_materi('open','', 'all',$id_materi);
			$topik_materi[0]->id_materi	= $_POST['id_materi'];
			echo json_encode($topik_materi);
		}
	}

    private function set_topik_materi($action){
		$id_materi = $this->generate->kirana_decrypt($_POST['id_materi']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_materi", array(
																	array(
																		'kolom'=>'id_materi',
																		'value'=>$id_materi
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_topik_materi(){
		$datetime  = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		//==========================UPLOAD MATERI==============================//
		if(count($_FILES['gambar']['name']) > 1){
			$msg        = "You can only upload maximum 1 file";
			$sts        = "NotOK";
			$return     = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/materi';
		$config['allowed_types'] 	= 'pdf|zip|mp4';
		$config['max_size'] 		= 200000;		
		$newname					= $_POST['id_topik'].'_'.$_POST['nama'];
		$newname 					= array(str_replace(' ','_',$newname));			
		$gambar						= $this->general->upload_files($_FILES['gambar'], $newname, $config);
		if($gambar === NULL){
			$msg        = "Upload files error";
			$sts        = "NotOK";
			$return     = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		//========================================================================//
		
		if(isset($_POST['id_materi']) && $_POST['id_materi'] != ""){
			$id_materi = $this->generate->kirana_decrypt($_POST['id_materi']);
			$data_row   = array(
							  'id_topik' 	   	=> $_POST['id_topik'],
							  'nama'		    => $_POST['nama'],
							  'files'		    => $gambar[0]['url'],
							  'tipe_files'		=> str_replace('.','',$gambar[0]['file_ext']),
							  'size_files'		=> $gambar[0]['file_size'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_materi', $data_row, array( 
																array(
																	'kolom'=>'id_materi',
																	'value'=>$id_materi
																)
															));
		}else{
			$data_row   = array(
								'id_topik' 	   	=> $_POST['id_topik'],
								'nama'		    => str_replace(' ','_',$_POST['nama']),
								'files'		    => $gambar[0]['url'],
								'tipe_files'	=> str_replace('.','',$gambar[0]['file_ext']),
								'size_files'	=> $gambar[0]['file_size'],
								'na'     		=> 'n',
								'del'     		=> 'n',
								'login_buat'    => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'  => $datetime,
								'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'  => $datetime
							);
			$this->dgeneral->insert('tbl_materi', $data_row);
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

	//-------------------------------------------------//


	private function get_program(){
		if(isset($_POST['id_program'])){
			$id_program 			= $this->generate->kirana_decrypt($_POST['id_program']);
			$program				= $this->dmasterklems->get_data_program('open',$id_program, 'all');
			$program[0]->id_program = $_POST['id_program'];
			echo json_encode($program);
		}
	}

    private function set_program($action){
        $id_program = $this->generate->kirana_decrypt($_POST['id_program']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_program", array(
																	array(
																		'kolom'=>'id_program',
																		'value'=>$id_program
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_program(){
		$datetime      		  = date("Y-m-d H:i:s");
		$sertifikat_keahlian  = isset($_POST['sertifikat_keahlian']) ? 1 : 0;
		$id_sertifikat  	  = empty($_POST['id_sertifikat']) ? NULL : $_POST['id_sertifikat'];
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_program']) && $_POST['id_program'] != ""){
			$id_program = $this->generate->kirana_decrypt($_POST['id_program']);
			$data_row   = array(
							  'jenis' 		    => $_POST['jenis'],
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'abbreviation' 	=> $_POST['abbreviation'],
							  'sertifikat_keahlian'	=> $sertifikat_keahlian,
							  'id_sertifikat'	=> $id_sertifikat,
							  'kategori'		=> $_POST['kategori'],
							  'tipe_program'	=> $_POST['tipe_program'],
							  'tipe_penyelenggara'	=> $_POST['tipe_penyelenggara'],
							  'jenis_sertifikat'	=> $_POST['jenis_sertifikat'],
							  'keterangan'		=> $_POST['keterangan'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_program', $data_row, array( 
																array(
																	'kolom'=>'id_program',
																	'value'=>$id_program
																)
															));
		}else{
			$data_row   = array(
								'jenis' 		    => $_POST['jenis'],
								'kode' 			    => $_POST['kode'],
								'nama'     			=> $_POST['nama'],
								'abbreviation' 		=> $_POST['abbreviation'],
								'sertifikat_keahlian'	=> $sertifikat_keahlian,
								'id_sertifikat'		=> $id_sertifikat,
								'kategori'			=> $_POST['kategori'],
								'tipe_program'		=> $_POST['tipe_program'],
								'tipe_penyelenggara'=> $_POST['tipe_penyelenggara'],
								'jenis_sertifikat'	=> $_POST['jenis_sertifikat'],
								'keterangan'		=> $_POST['keterangan'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_program', $data_row);
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

	//-------------------------------------------------//

	private function get_program_budget_cek(){
		$program_budget	= $this->dmasterklems->get_data_program_budget('open',null, null, null,$_POST['id_program'],$_POST['tahun']);
		if(!empty($program_budget[0]->id_program_budget)){
			$program_budget[0]->id_program_budget = $this->generate->kirana_encrypt($program_budget[0]->id_program_budget);
			echo json_encode($program_budget);	
		}
		
	}

	
	private function get_program_budget(){
		if(isset($_POST['id_program_budget'])){
			$id_program_budget 						= $this->generate->kirana_decrypt($_POST['id_program_budget']);
			$program_budget							= $this->dmasterklems->get_data_program_budget('open',null, 'all', $id_program_budget);
			$program_budget[0]->id_program_budget	= $_POST['id_program_budget'];
			echo json_encode($program_budget);
		}
	}


    private function set_program_budget($action){
        $id_program_budget = $this->generate->kirana_decrypt($_POST['id_program_budget']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_program_budget", array(
																	array(
																		'kolom'=>'id_program_budget',
																		'value'=>$id_program_budget
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_program_budget(){
		$datetime      		  = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_program_budget']) && $_POST['id_program_budget'] != ""){
			$id_program_budget = $this->generate->kirana_decrypt($_POST['id_program_budget']);
			$data_row   = array(
							  'id_program' 	    => $_POST['id_program'],
							  'tahun' 		    => $_POST['tahun'],
							  'budget_training'	=> str_replace(',','',$_POST['budget_training']),
							  'budget_traveling'=> str_replace(',','',$_POST['budget_traveling']),
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_program_budget', $data_row, array( 
																array(
																	'kolom'=>'id_program_budget',
																	'value'=>$id_program_budget
																)
															));
		}else{
			$data_row   = array(
								'id_program' 	    => $_POST['id_program'],
								'tahun' 		    => $_POST['tahun'],
								'budget_training'	=> str_replace(',','',$_POST['budget_training']),
								'budget_traveling'	=> str_replace(',','',$_POST['budget_traveling']),
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_program_budget', $data_row);
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

	//-------------------------------------------------//

	private function get_program_matrix(){
		if(isset($_POST['id_program_matrix'])){
			$id_program_matrix 						= $this->generate->kirana_decrypt($_POST['id_program_matrix']);
			$program_matrix							= $this->dmasterklems->get_data_program_matrix('open',null, 'all', $id_program_matrix);
			$program_matrix[0]->id_program_matrix	= $_POST['id_program_matrix'];
			echo json_encode($program_matrix);
		}
	}

    private function set_program_matrix($action){
        $id_program_matrix = $this->generate->kirana_decrypt($_POST['id_program_matrix']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_program_matrix", array(
																	array(
																		'kolom'=>'id_program_matrix',
																		'value'=>$id_program_matrix
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_program_matrix(){
		$datetime	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_program_matrix']) && $_POST['id_program_matrix'] != ""){
			$id_program_matrix 						= $this->generate->kirana_decrypt($_POST['id_program_matrix']);
			$data_row   = array(
							  'id_program' 	    => $_POST['id_program'],
							  'tanggal_awal'    => $_POST['tanggal_awal'],
							  'tanggal_akhir'   => $_POST['tanggal_akhir'],
							  'level'			=> implode(",", $_POST['level']),
							  'organisasi_level'=> implode(",", $_POST['organisasi_level']),
							  'posisi'			=> implode(",", $_POST['posisi']),
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_program_matrix', $data_row, array( 
																array(
																	'kolom'=>'id_program_matrix',
																	'value'=>$id_program_matrix
																)
															));
		}else{
			$data_row   = array(
								'id_program' 	    => $_POST['id_program'],
								'tanggal_awal'   	=> $_POST['tanggal_awal'],
								'tanggal_akhir'   	=> $_POST['tanggal_akhir'],
								'level'				=> implode(",", $_POST['level']),
								'organisasi_level'	=> implode(",", $_POST['organisasi_level']),
								'posisi'			=> implode(",", $_POST['posisi']),
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_program_matrix', $data_row);
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

	//-------------------------------------------------//
	
	
	private function get_evitem(){
		if(isset($_POST['id_feedback_pertanyaan'])){
			$id_feedback_pertanyaan = $this->generate->kirana_decrypt($_POST['id_feedback_pertanyaan']);
			$evitem	= $this->dmasterklems->get_data_evitem('open',$id_feedback_pertanyaan, 'all');
			$evitem[0]->id_feedback_pertanyaan	= $_POST['id_feedback_pertanyaan'];
			echo json_encode($evitem);
		}
	}

    private function set_evitem($action){
        $id_feedback_pertanyaan = $this->generate->kirana_decrypt($_POST['id_feedback_pertanyaan']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_feedback_pertanyaan", array(
																	array(
																		'kolom'=>'id_feedback_pertanyaan',
																		'value'=>$id_feedback_pertanyaan
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_evitem(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_feedback_pertanyaan']) && $_POST['id_feedback_pertanyaan'] != ""){
			$id_feedback_pertanyaan = $this->generate->kirana_decrypt($_POST['id_feedback_pertanyaan']);
			$data_row   = array(
							  'id_feedback_kategori'    => $_POST['id_feedback_kategori'],
							  'kode' 		    		=> $_POST['kode'],
							  'pertanyaan' 				=> $_POST['pertanyaan'],
							  'login_edit'      		=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    		=> $datetime
						 );
			$this->dgeneral->update('tbl_feedback_pertanyaan', $data_row, array( 
																array(
																	'kolom'=>'id_feedback_pertanyaan',
																	'value'=>$id_feedback_pertanyaan
																)
															));
		}else{
			$data_row   = array(
							    'id_feedback_kategori'  => $_POST['id_feedback_kategori'],
							    'kode' 		    		=> $_POST['kode'],
							    'pertanyaan' 			=> $_POST['pertanyaan'],
								'na'     				=> 'n',
								'del'     				=> 'n',
								'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      	=> $datetime,
								'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      	=> $datetime
							);
			$this->dgeneral->insert('tbl_feedback_pertanyaan', $data_row);
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
	//-------------------------------------------------//
	
	private function get_nil_kategori_cek(){
		$nil_kategori	= $this->dmasterklems->get_data_nil_kategori('open',null, null, $_POST['nama']);
		if(!empty($nil_kategori[0]->id_nilai_kategori)){
			$nil_kategori[0]->id_nilai_kategori = $this->generate->kirana_encrypt($nil_kategori[0]->id_nilai_kategori);
			echo json_encode($nil_kategori);	
		}
	}
	
	private function get_nil_kategori(){
		if(isset($_POST['id_nilai_kategori'])){
			$id_nilai_kategori = $this->generate->kirana_decrypt($_POST['id_nilai_kategori']);
			$nil_kategori	= $this->dmasterklems->get_data_nil_kategori('open',$id_nilai_kategori, 'all');
			$nil_kategori[0]->id_nilai_kategori = $_POST['id_nilai_kategori'];
			echo json_encode($nil_kategori);
		}
	}

    private function set_nil_kategori($action){
        $id_nilai_kategori = $this->generate->kirana_decrypt($_POST['id_nilai_kategori']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_nilai_kategori", array(
																	array(
																		'kolom'=>'id_nilai_kategori',
																		'value'=>$id_nilai_kategori
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_nil_kategori(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_nilai_kategori']) && $_POST['id_nilai_kategori'] != ""){
			$id_nilai_kategori = $this->generate->kirana_decrypt($_POST['id_nilai_kategori']);
			$ck_nil_kategori	= $this->dmasterklems->get_data_nil_kategori(NULL,NULL, 'all', $_POST['nama']);
			if ((count($ck_nil_kategori) > 0)and($ck_nil_kategori[0]->id_nilai_kategori!=$id_nilai_kategori)) {
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			
			$data_row   = array(
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'keterangan'		=> $_POST['keterangan'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_nilai_kategori', $data_row, array( 
																array(
																	'kolom'=>'id_nilai_kategori',
																	'value'=>$id_nilai_kategori
																)
															));
		}else{
			$ck_nil_kategori	= $this->dmasterklems->get_data_nil_kategori(NULL,NULL, 'all', $_POST['nama']);
			if (count($ck_nil_kategori) > 0) {
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row   = array(
								'kode' 		    	=> $_POST['kode'],
								'nama'  	   		=> $_POST['nama'],
								'keterangan'		=> $_POST['keterangan'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_nilai_kategori', $data_row);
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
	
	//-------------------------------------------------//
	
	
	private function get_nil_nilai_cek(){
		$nil_nilai	= $this->dmasterklems->get_data_nil_nilai('open',null, null, $_POST['id_nilai_kategori'], $_POST['nama']);
		if(!empty($nil_nilai[0]->id_nilai)){
			$nil_nilai[0]->id_nilai = $this->generate->kirana_encrypt($nil_nilai[0]->id_nilai);
			echo json_encode($nil_nilai);	
		}
		
	}
	private function get_nil_nilai(){
		if(isset($_POST['id_nilai'])){
			$id_nilai 	= $this->generate->kirana_decrypt($_POST['id_nilai']);
			$nil_nilai	= $this->dmasterklems->get_data_nil_nilai('open',$id_nilai, 'all');
			$nil_nilai[0]->id_nilai	= $_POST['id_nilai'];
			echo json_encode($nil_nilai);
		}
	}

    private function set_nil_nilai($action){
        $id_nilai = $this->generate->kirana_decrypt($_POST['id_nilai']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_nilai", array(
																	array(
																		'kolom'=>'id_nilai',
																		'value'=>$id_nilai
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_nil_nilai(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_nilai']) && $_POST['id_nilai'] != ""){
			$id_nilai = $this->generate->kirana_decrypt($_POST['id_nilai']);
			$data_row   = array(
							  'id_nilai_kategori'   => $_POST['id_nilai_kategori'],
							  'kode' 		    	=> $_POST['kode'],
							  'nama' 				=> $_POST['nama'],
							  'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    	=> $datetime
						 );
			$this->dgeneral->update('tbl_nilai', $data_row, array( 
																array(
																	'kolom'=>'id_nilai',
																	'value'=>$id_nilai
																)
															));
		}else{
			$data_row   = array(
							    'id_nilai_kategori' => $_POST['id_nilai_kategori'],
							    'kode' 		    	=> $_POST['kode'],
							    'nama' 				=> $_POST['nama'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'     	=> $datetime,
								'login_edit'       	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'     	=> $datetime
							);
			$this->dgeneral->insert('tbl_nilai', $data_row);
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
	

	//-------------------------------------------------//
	
	
	private function get_evkategori(){
		if(isset($_POST['id_feedback_kategori'])){
			$id_feedback_kategori = $this->generate->kirana_decrypt($_POST['id_feedback_kategori']);
			$evkategori	= $this->dmasterklems->get_data_evkategori('open',$id_feedback_kategori, 'all');
			$evkategori[0]->id_feedback_kategori	= $_POST['id_feedback_kategori'];
			echo json_encode($evkategori);
		}
	}

    private function set_evkategori($action){
        $id_feedback_kategori = $this->generate->kirana_decrypt($_POST['id_feedback_kategori']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_feedback_kategori", array(
																	array(
																		'kolom'=>'id_feedback_kategori',
																		'value'=>$id_feedback_kategori
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_evkategori(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_feedback_kategori']) && $_POST['id_feedback_kategori'] != ""){
			$id_feedback_kategori = $this->generate->kirana_decrypt($_POST['id_feedback_kategori']);
			$data_row   = array(
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'keterangan'		=> $_POST['keterangan'],
							  'jenis'    		=> $_POST['jenis'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_feedback_kategori', $data_row, array( 
																array(
																	'kolom'=>'id_feedback_kategori',
																	'value'=>$id_feedback_kategori
																)
															));
		}else{
			$data_row   = array(
								'kode' 		    	=> $_POST['kode'],
								'nama'  	   		=> $_POST['nama'],
								'keterangan'		=> $_POST['keterangan'],
								'jenis'    			=> $_POST['jenis'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_feedback_kategori', $data_row);
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

	//-------------------------------------------------//

	private function get_evskala(){
		if(isset($_POST['id_feedback_nilai'])){
			$id_feedback_nilai = $this->generate->kirana_decrypt($_POST['id_feedback_nilai']);
			$evskala	= $this->dmasterklems->get_data_evskala('open',$id_feedback_nilai, 'all');
			$evskala[0]->id_feedback_nilai	=  $_POST['id_feedback_nilai'];
			echo json_encode($evskala);
		}
	}

	private function get_evskala_cek(){
		$evskala	= $this->dmasterklems->get_data_evskala('open',null, null, $_POST['nilai']);
		if(empty($evskala)){
			$kode = $this->dmasterklems->get_data_evskala('open',NULL, 'all');
			$evskala[]	= array('kode'=>str_pad(count($kode)+1, 4, 0, STR_PAD_LEFT),'nama'=>null,'keterangan'=>null,'id_feedback_nilai'=>$_POST['nilai']);
		}
		echo json_encode($evskala);
	}
	
    private function set_evskala($action){
        $id_feedback_nilai = $this->generate->kirana_decrypt($_POST['id_feedback_nilai']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_feedback_nilai", array(
																	array(
																		'kolom'=>'id_feedback_nilai',
																		'value'=>$id_feedback_nilai
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_evskala(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_feedback_nilai']) && $_POST['id_feedback_nilai'] != ""){
			$id_feedback_nilai = $this->generate->kirana_decrypt($_POST['id_feedback_nilai']);
			$data_row   = array(
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'keterangan'		=> $_POST['keterangan'],
							  'nilai'    		=> $_POST['nilai'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_feedback_nilai', $data_row, array( 
																array(
																	'kolom'=>'id_feedback_nilai',
																	'value'=>$id_feedback_nilai
																)
															));
		}else{
			$data_row   = array(
								'kode' 		    => $_POST['kode'],
								'nama'  	   		=> $_POST['nama'],
								'keterangan'		=> $_POST['keterangan'],
								'nilai'    		=> $_POST['nilai'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_feedback_nilai', $data_row);
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

	//-------------------------------------------------//

	private function get_evindex(){
		if(isset($_POST['id_feedback_index'])){
			$id_feedback_index = $this->generate->kirana_decrypt($_POST['id_feedback_index']);
			$evindex	= $this->dmasterklems->get_data_evindex('open',$id_feedback_index, 'all');
			$evindex[0]->id_feedback_index	= $_POST['id_feedback_index'];
			echo json_encode($evindex);
		}
	}

    private function set_evindex($action){
        $id_feedback_index = $this->generate->kirana_decrypt($_POST['id_feedback_index']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_feedback_index", array(
																	array(
																		'kolom'=>'id_feedback_index',
																		'value'=>$id_feedback_index
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_evindex(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_feedback_index']) && $_POST['id_feedback_index'] != ""){
			$id_feedback_index = $this->generate->kirana_decrypt($_POST['id_feedback_index']);
			$data_row   = array(
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'tanggal_awal' 	=> $_POST['tanggal_awal'],
							  'tanggal_akhir'	=> $_POST['tanggal_akhir'],
							  'nilai'    		=> $_POST['nilai'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_feedback_index', $data_row, array( 
																array(
																	'kolom'=>'id_feedback_index',
																	'value'=>$id_feedback_index
																)
															));
		}else{
			$data_row   = array(
								'kode' 		    => $_POST['kode'],
								'nama'  	   	=> $_POST['nama'],
								'tanggal_awal' 	=> $_POST['tanggal_awal'],
								'tanggal_akhir'	=> $_POST['tanggal_akhir'],
								'nilai'    		=> $_POST['nilai'],
								'na'   			=> 'n',
								'del'  			=> 'n',
								'login_buat'    => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'  => $datetime,
								'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'  => $datetime
							);
			$this->dgeneral->insert('tbl_feedback_index', $data_row);
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

	//-------------------------------------------------//

	private function get_trainer(){
		if(isset($_POST['id_trainer'])){
			$id_trainer = $this->generate->kirana_decrypt($_POST['id_trainer']);
			$trainer	= $this->dmasterklems->get_data_trainer('open',$id_trainer, 'all');
			$trainer[0]->id_trainer	= $_POST['id_trainer'];
			echo json_encode($trainer);
		}
	}

    private function set_trainer($action){
        $id_trainer = $this->generate->kirana_decrypt($_POST['id_trainer']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_trainer", array(
																	array(
																		'kolom'=>'id_trainer',
																		'value'=>$id_trainer
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_trainer(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_trainer']) && $_POST['id_trainer'] != ""){
			$id_trainer = $this->generate->kirana_decrypt($_POST['id_trainer']);
			$data_row   = array(
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'id_institusi'	=> $_POST['id_institusi'],
							  'telepon'    		=> $_POST['telepon'],
							  'email'     		=> $_POST['email'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_trainer', $data_row, array( 
																array(
																	'kolom'=>'id_trainer',
																	'value'=>$id_trainer
																)
															));
		}else{
			$data_row   = array(
								'kode' 		    => $_POST['kode'],
								'nama'  	   		=> $_POST['nama'],
								'id_institusi'		=> $_POST['id_institusi'],
								'telepon'    		=> $_POST['telepon'],
								'email'     		=> $_POST['email'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_trainer', $data_row);
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

	//-------------------------------------------------//
	private function get_institusi(){
		if(isset($_POST['id_institusi'])){
			$id_institusi 	= $this->generate->kirana_decrypt($_POST['id_institusi']);
			$institusi      = $this->dmasterklems->get_data_institusi('open',$id_institusi, 'all');
			$institusi[0]->id_institusi = $_POST['id_institusi'];
			echo json_encode($institusi);
		}
	}

    private function set_institusi($action){
        $id_institusi = $this->generate->kirana_decrypt($_POST['id_institusi']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_institusi", array(
																	array(
																		'kolom'=>'id_institusi',
																		'value'=>$id_institusi
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_institusi(){
		$datetime       = date("Y-m-d H:i:s");
            $this->general->connectDbPortal();
        	$this->dgeneral->begin_transaction();
        	if(isset($_POST['id_institusi']) && $_POST['id_institusi'] != ""){
				$id_institusi 	= $this->generate->kirana_decrypt($_POST['id_institusi']);
                $data_row   = array(
                                  'kode' 		    => $_POST['kode'],
								  'nama'     		=> $_POST['nama'],
								  'alamat'     		=> $_POST['alamat'],
								  'id_spesialis'	=> $_POST['id_spesialis'],
								  'telepon'    		=> $_POST['telepon'],
								  'email'     		=> $_POST['email'],
                                  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                                  'tanggal_edit'    => $datetime
                             );
                $this->dgeneral->update('tbl_institusi', $data_row, array( 
																	array(
																		'kolom'=>'id_institusi',
																		'value'=>$id_institusi
																	)
																));
            }else{
                $data_row   = array(
									'kode' 			    => $_POST['kode'],
									'nama'  	   		=> $_POST['nama'],
									'alamat'     		=> $_POST['alamat'],
									'id_spesialis'		=> $_POST['id_spesialis'],
									'telepon'    		=> $_POST['telepon'],
									'email'     		=> $_POST['email'],
									'na'     			=> 'n',
									'del'     			=> 'n',
                                    'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_buat'      => $datetime,
                                    'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_edit'      => $datetime
                                );
                $this->dgeneral->insert('tbl_institusi', $data_row);
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

	//-------------------------------------------------//
	private function get_spesialis(){
		if(isset($_POST['id_spesialis'])){
			$id_spesialis				= $this->generate->kirana_decrypt($_POST['id_spesialis']);
			$spesialis         			= $this->dmasterklems->get_data_spesialis('open',$id_spesialis, 'all');
			$spesialis[0]->id_spesialis	= $_POST['id_spesialis'];
			echo json_encode($spesialis);
		}
	}

    private function set_spesialis($action){
        $id_spesialis = $this->generate->kirana_decrypt($_POST['id_spesialis']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_spesialis", array(
																	array(
																		'kolom'=>'id_spesialis',
																		'value'=>$id_spesialis
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_spesialis(){
		$datetime       = date("Y-m-d H:i:s");
            $this->general->connectDbPortal();
        	$this->dgeneral->begin_transaction();
        	if(isset($_POST['id_spesialis']) && $_POST['id_spesialis'] != ""){
				$id_spesialis				= $this->generate->kirana_decrypt($_POST['id_spesialis']);
                $data_row   = array(
                                  'kode' 		    => $_POST['kode'],
								  'nama'     		=> $_POST['nama'],
								  'keterangan'     	=> $_POST['keterangan'],
                                  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
                                  'tanggal_edit'    => $datetime
                             );
                $this->dgeneral->update('tbl_spesialis', $data_row, array( 
																	array(
																		'kolom'=>'id_spesialis',
																		'value'=>$id_spesialis
																	)
																));
            }else{
                $data_row   = array(
                                    'kode'    			=> $_POST['kode'],
									'nama'     			=> $_POST['nama'],
									'keterangan'     	=> $_POST['keterangan'],
									'na'     			=> 'n',
									'del'     			=> 'n',
                                    'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_buat'      => $datetime,
                                    'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
                                    'tanggal_edit'      => $datetime
                                );
                $this->dgeneral->insert('tbl_spesialis', $data_row);
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

	//-------------------------------------------------//
	private function get_bpo(){
		if (isset($_POST['id_bpo'])){
			$bpo         	= $this->dmasterklems->get_data_bpo('open',$this->generate->kirana_decrypt($_POST['id_bpo']), 'all');
			$bpo[0]->id_bpo	= $_POST['id_bpo'];
			echo json_encode($bpo);
		}
	}

    private function set_bpo($action){
        $id_bpo = $this->generate->kirana_decrypt($_POST['id_bpo']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_bpo", array(
																	array(
																		'kolom'=>'id_bpo',
																		'value'=>$id_bpo
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_bpo(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_bpo']) && $_POST['id_bpo'] != ""){
			$id_bpo = $this->generate->kirana_decrypt($_POST['id_bpo']);
			$data_row   = array(
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'keterangan'     	=> $_POST['keterangan'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_bpo', $data_row, array( 
																array(
																	'kolom'=>'id_bpo',
																	'value'=>$id_bpo
																)
															));
		}else{
			$data_row   = array(
								'kode'    			=> $_POST['kode'],
								'nama'     			=> $_POST['nama'],
								'keterangan'     	=> $_POST['keterangan'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_bpo', $data_row);
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
	//-------------------------------------------------//
	private function get_grade(){
		if(isset($_POST['id_grade'])){
			$grade	= $this->dmasterklems->get_data_grade('open',$this->generate->kirana_decrypt($_POST['id_grade']), 'all');
			$grade[0]->id_grade	= $_POST['id_grade'];
			echo json_encode($grade);
		}
	}

    private function set_grade($action){
        $id_grade = $this->generate->kirana_decrypt($_POST['id_grade']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_grade", array(
																	array(
																		'kolom'=>'id_grade',
																		'value'=>$id_grade
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }

	private function save_grade(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_grade']) && $_POST['id_grade'] != ""){
			$id_grade = $this->generate->kirana_decrypt($_POST['id_grade']);
			$data_row   = array(
							  'kode' 		    => $_POST['kode'],
							  'nama'     		=> $_POST['nama'],
							  'keterangan'     	=> $_POST['keterangan'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_grade', $data_row, array( 
																array(
																	'kolom'=>'id_grade',
																	'value'=>$id_grade
																)
															));
		}else{
			$data_row   = array(
								'kode'    			=> $_POST['kode'],
								'nama'     			=> $_POST['nama'],
								'keterangan'     	=> $_POST['keterangan'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_grade', $data_row);
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

	//-------------------------------------------------//
	private function get_jenis(){
		$jenis         	= $this->dmasterklems->get_data_jns_formula($_POST['id_mjenis'], 'all');
		echo json_encode($jenis);
	}

    private function set_jenis($action){
        $id_mjenis = $_POST['id_mjenis'];
        $this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_pcs_mjenis", array(
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

		$jenis         	= $this->dmasterklems->get_data_jns_formula(NULL, 'all', $_POST['jns_formula']);
        if(isset($_POST['jns_formula']) && $_POST['jns_formula'] !== "" && count($jenis) == 0){
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
                                                                                'value'=>$_POST['id_mjenis']
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
                $msg    = "Transaksi Berhasil";
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
		$pegrup         	= $this->dmasterklems->get_data_pegrup($_POST['id_mpegrup'], 'all');
		echo json_encode($pegrup);
	}

    private function set_pegrup($action){
        $id_mpegrup = $_POST['id_mpegrup'];
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

		$pegrup         	= $this->dmasterklems->get_data_pegrup(NULL, 'all', $_POST['nama_pegrup']);
        if(isset($_POST['nama_pegrup']) && $_POST['nama_pegrup'] !== "" && count($pegrup) == 0){
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
                                                                                'value'=>$_POST['id_mpegrup']
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
                $msg    = "Transaksi Berhasil";
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
		$lwbp         	= $this->dmasterklems->get_data_lwbp($_POST['plant'], 'all');
		echo json_encode($lwbp);
	}

    private function set_lwbp($action){
        $id_mlwbp = $_POST['id_mlwbp'];
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

        $lwbp 	= $this->dmasterklems->get_data_lwbp($_POST['plant'], 'all');
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
            $msg    = "Transaksi Berhasil";
            $sts    = "OK";
        }	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//

	private function get_wbp(){
		$wbp         	= $this->dmasterklems->get_data_wbp($_POST['plant'], 'all');
		echo json_encode($wbp);
	}

    private function set_wbp($action){
        $id_mwbp = $_POST['id_mwbp'];
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

        $wbp 	= $this->dmasterklems->get_data_wbp($_POST['plant'], 'all');
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
            $msg    = "Transaksi Berhasil";
            $sts    = "OK";
        }	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//

	private function get_cangkang(){
		$cangkang         	= $this->dmasterklems->get_data_cangkang($_POST['plant'], 'all');
		echo json_encode($cangkang);
	}

    private function set_cangkang($action){
        $id_mcangkang = $_POST['id_mcangkang'];
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

        $cangkang 	= $this->dmasterklems->get_data_cangkang($_POST['plant'], 'all');
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
            $msg    = "Transaksi Berhasil";
            $sts    = "OK";
        }	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//

	private function get_genset(){
		$genset         	= $this->dmasterklems->get_data_genset($_POST['plant'], 'all');
		echo json_encode($genset);
	}

    private function set_genset($action){
        $id_mgenset = $_POST['id_mgenset'];
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

        $genset 	= $this->dmasterklems->get_data_genset($_POST['plant'], 'all');
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
            $msg    = "Transaksi Berhasil";
            $sts    = "OK";
        }	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//

	private function get_drier(){
		$drier         	= $this->dmasterklems->get_data_drier($_POST['plant'], 'all');
		echo json_encode($drier);
	}

    private function set_drier($action){
        $id_mdrier = $_POST['id_mdrier'];
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

        $drier 	= $this->dmasterklems->get_data_drier($_POST['plant'], 'all');
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
            $msg    = "Transaksi Berhasil";
            $sts    = "OK";
        }	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//

	private function get_lain(){
		$lain         	= $this->dmasterklems->get_data_lain($_POST['plant'], 'all');
		echo json_encode($lain);
	}

    private function set_lain($action){
        $id_mlain = $_POST['id_mlain'];
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

        $lain 	= $this->dmasterklems->get_data_lain($_POST['plant'], 'all');
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
            $msg    = "Transaksi Berhasil";
            $sts    = "OK";
        }	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	//-------------------------------------------------//
}