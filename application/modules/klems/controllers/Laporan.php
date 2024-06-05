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

Class Laporan extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->model('dtransaksiklems');
	    $this->load->model('dlaporanklems');
	}

	public function index(){
		show_404();
	}
	public function biaya(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Laporan Budget & Aktual Biaya";
		$data['title_form'] = "Laporan Budget & Aktual Biaya";
		$data['biaya']		= $this->dlaporanklems->get_data_biaya(NULL, date('Y'));
		$this->load->view("laporan/biaya", $data);
	}
	public function history(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Laporan Historikal Data Training";
		$data['title_form'] = "Laporan Historikal Data Training";
		$data['regional']	= $this->dgeneral->get_master_region(NULL, 'all');
		$data['pabrik']		= $this->dgeneral->get_master_plant(NULL);
		$data['posisi']		= $this->dlaporanklems->get_data_posisi(NULL, 'all');
		$data['program']	= $this->dlaporanklems->get_data_program(NULL, 'all');
		$data['tahun']		= $this->dlaporanklems->get_data_tahun();
		$data['tahap']		= $this->dlaporanklems->get_data_tahap_distinct(NULL, NULL);
		$tahun				= date('Y');
		$data['peserta']	= $this->dlaporanklems->get_data_peserta(NULL, 'all', NULL, NULL, NULL, NULL, NULL, $tahun);
		$this->load->view("laporan/history", $data);
	}
	public function history_filter(){
        $regional = !isset($_POST['regional']) ? NULL : $_POST['regional'];
        $nik  	  = !isset($_POST['nik']) ? NULL : $_POST['nik'];
        $posisi   = !isset($_POST['posisi']) ? NULL : $_POST['posisi'];
        $program  = !isset($_POST['program']) ? NULL : $_POST['program'];
        $pabrik   = !isset($_POST['pabrik']) ? NULL : $_POST['pabrik'];
        // $tahun    = !isset($_POST['tahun']) ? NULL : $_POST['tahun'];
        $awal     = !isset($_POST['awal']) ? NULL : $_POST['awal'];
        $akhir    = !isset($_POST['akhir']) ? NULL : $_POST['akhir'];
		$data		= $this->dlaporanklems->get_data_peserta(NULL, NULL,$regional,$nik,$posisi,$program,$pabrik,NULL,$awal,$akhir);
        echo json_encode($data);
	}
	public function nilai(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	 = "Laporan Nilai Batch Training";
		$data['title_form']  = "Laporan Nilai Batch Training";
		$data['program']	 = $this->dlaporanklems->get_data_program(NULL, 'all');
		$data['pabrik']		 = $this->dgeneral->get_master_plant(NULL);
		$awal = (empty($_POST['awal']))?date('Y-m-d', strtotime(date('Y-m-d').'-3 months')):$_POST['awal'];
		$akhir = (empty($_POST['akhir']))?date('Y-m-d'):$_POST['akhir'];
		
		$data['peserta']	 = $this->dlaporanklems->get_data_peserta(NULL, 'all', NULL, NULL, NULL, NULL, NULL, NULL, $awal, $akhir);
		// $data['detail_nilai']= $this->dlaporanklems->get_data_detail_nilai(null, null);
		// $data['detail_nilai']= $this->dlaporanklems->get_data_detail_nilai(3, null);
		$this->load->view("laporan/nilai", $data);
	}
	public function nilai_filter(){
        $program  = !isset($_POST['program']) ? NULL : $_POST['program'];
        $pabrik   = !isset($_POST['pabrik']) ? NULL : $_POST['pabrik'];
        $awal     = !isset($_POST['awal']) ? NULL : $_POST['awal'];
        $akhir    = !isset($_POST['akhir']) ? NULL : $_POST['akhir'];
		
		$data		= $this->dlaporanklems->get_data_peserta(NULL, NULL, NULL, NULL, NULL,$program,$pabrik,NULL,$awal,$akhir);
        echo json_encode($data);
	}
	public function evaluasi(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Laporan Evaluasi";
		$data['title_form'] = "Laporan Evaluasi";
		$data['program']	= $this->dlaporanklems->get_data_program(NULL, 'all');

		$awal = (empty($_POST['awal']))?date('Y-m-d', strtotime(date('Y-m-d').'-3 months')):$_POST['awal'];
		$akhir = (empty($_POST['akhir']))?date('Y-m-d'):$_POST['akhir'];
		$data['batch']		= $this->dlaporanklems->get_data_batch(NULL, NULL, NULL, NULL, NULL, NULL, $awal, $akhir);
		$this->load->view("laporan/evaluasi", $data);
	}
	public function evaluasi_filter(){
        $program  = !isset($_POST['program']) ? NULL : $_POST['program'];
        $awal     = !isset($_POST['awal']) ? NULL : $_POST['awal'];
        $akhir    = !isset($_POST['akhir']) ? NULL : $_POST['akhir'];
		
		$data		= $this->dlaporanklems->get_data_batch(NULL, NULL, NULL, NULL, NULL,$program,$awal,$akhir);
        echo json_encode($data);
	}
	private function evaluasi_sesi($id_batch=NULL,$id_trainer=NULL,$trainer=NULL){
		if($id_batch==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    			= "Laporan Feedback Trainer";
		$data['title_form'] 		= "Laporan Feedback Trainer";
		$data['topik_trainer']		= $this->dtransaksiklems->get_data_topik_trainer($id_trainer, NULL, $trainer);
		$data['feedback_nilai']		= $this->dlaporanklems->get_data_feedback_nilai(NULL, NULL);
		$data['feedback_pertanyaan']= $this->generate_pertanyaan('Sesi', $id_batch, $id_trainer); //$this->dlaporanklems->get_data_feedback_pertanyaan(NULL, NULL);
		$data['feedback_kategori']	= $this->dlaporanklems->get_data_feedback_kategori(NULL,NULL,$id_batch,'Sesi');
		$data['batch_komentar']		= $this->dlaporanklems->get_data_batch_komentar(NULL, NULL, $id_batch, base64_decode($this->session->userdata("-id_karyawan-")), NULL);
		$data['batch']	  			= $this->dlaporanklems->get_data_batch($id_batch, NULL);
		$this->load->view("laporan/evaluasi_sesi", $data);
	}
	private function evaluasi_program($id_batch=NULL){
		if($id_batch==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    			= "Laporan Evaluasi Program";
		$data['title_form'] 		= "Laporan Evaluasi Program";
		$data['feedback_nilai']		= $this->dlaporanklems->get_data_feedback_nilai(NULL, NULL);
		$data['feedback_pertanyaan']= $this->generate_pertanyaan('Program', $id_batch, NULL); //$this->dlaporanklems->get_data_feedback_pertanyaan(NULL, NULL);
		$data['feedback_kategori']	= $this->dlaporanklems->get_data_feedback_kategori(NULL,NULL,$id_batch,'Program');
		$data['batch_komentar']		= $this->dlaporanklems->get_data_batch_komentar(NULL, NULL, $id_batch, base64_decode($this->session->userdata("-id_karyawan-")), NULL);
		$data['batch']	  			= $this->dlaporanklems->get_data_batch($id_batch, NULL);
		$this->load->view("laporan/evaluasi_program", $data);
	}
	private function generate_pertanyaan($jenis, $id_batch, $id_trainer){
		$data	= $this->dlaporanklems->get_data_feedback_pertanyaan(NULL, NULL, base64_decode($this->session->userdata("-nik-")),$jenis,$id_batch,$id_trainer);
		foreach($data as $d){
			if(!isset($array[$d->id_feedback_kategori])) $array[$d->id_feedback_kategori] = array();
			array_push($array[$d->id_feedback_kategori],$d);
		}
		return $array;
	}
	
	//=================================//
	//		  MIRRORING URL 		   //
	//=================================//
	public function data($url=NULL,$param=NULL,$param2=NULL,$param3=NULL){
		switch ($url) {
			case 'sesi':
				$this->evaluasi_sesi($param,$param2,$param3);
				break;
			case 'prog':
				$this->evaluasi_program($param);
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
			case 'detail':
				$this->get_detail_nilai();
				break;
			case 'batch_nilai':
				$this->get_batch_nilai();
				break;
			case 'nilai':
				$this->get_nilai_batch();
				break;
			case 'materi':
				$this->get_materi();
				break;
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function set_data($action, $param){
		switch ($param) {
			case 'batch':
				$this->set_batch($action);
				break;
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function save($param){
		switch ($param) {
			case 'evaluasi':
				$this->save_evaluasi();
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
	
	private function get_detail_nilai(){
		$detail_nilai	= $this->dlaporanklems->get_data_detail_nilai($_POST['id_program_batch'], $_POST['id_karyawan']);
		echo json_encode($detail_nilai);
	}

	private function get_batch_nilai(){
		$batch_nilai	= $this->dlaporanklems->get_data_batch_nilai($this->generate->kirana_decrypt($_POST['id_program_batch']), 'all', $_POST['id_karyawan']);
		echo json_encode($batch_nilai);
	}

	private function get_program_batch(){
		$program_batch	= $this->dlaporanklems->get_data_program_batch($this->generate->kirana_decrypt($_POST['id_program_batch']), 'all');
		echo json_encode($program_batch);
	}
	private function get_peserta_program_batch(){
		if(isset($_GET['q'])){
			$pabrik 		  = $_GET['pabrik'];
			$program 		  = $_GET['program'];
			$peserta_tambahan = $_GET['peserta_tambahan'];
            $data       	  = $this->dlaporanklems->get_data_user_program($_GET['q'],$program,$pabrik,$peserta_tambahan);
            $data_json  	  = array(
									"total_count" => count($data),
									"incomplete_results"=>false,
									"items"=>$data
								  );
            echo json_encode($data_json);
        }
	}

    private function set_program_batch($action){
        $id_program_batch = $this->generate->kirana_decrypt($_POST['id_program_batch']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_program_batch", array(
																	array(
																		'kolom'=>'id_program_batch',
																		'value'=>$id_program_batch
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	
	private function get_program(){
		$program	= $this->dlaporanklems->get_data_program($_POST['id_program']);
		echo json_encode($program);
	}
	
	//-------------------------------------------------//
	
}