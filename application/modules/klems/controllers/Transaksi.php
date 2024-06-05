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

Class Transaksi extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
	    $this->load->model('dtransaksiklems');
		$this->load->model('dlaporanklems');
	}

	public function index(){
		show_404();
	}
	public function approval(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Approval Program";
		$data['title_form'] = "Approval Program";
		$data['regional']	= $this->dgeneral->get_master_region(NULL, 'all');
		$data['pabrik']		= $this->dgeneral->get_master_plant(NULL);
		$data['posisi']		= $this->dlaporanklems->get_data_posisi(NULL, 'all');
		$data['program']	= $this->dlaporanklems->get_data_program(NULL, 'all');
		$data['tahun']		= $this->dlaporanklems->get_data_tahun();
		$data['tahap']		= $this->dlaporanklems->get_data_tahap(NULL, NULL);
		$tahun				= date('Y');
		$data['peserta']	= $this->dlaporanklems->get_data_peserta(NULL, 'all', NULL, NULL, NULL, NULL, NULL, $tahun, NULL, NULL, 'Sertifikat');
		$this->load->view("transaksi/approval", $data);

	}
	public function approval_filter(){
        $regional = !isset($_POST['regional']) ? NULL : $_POST['regional'];
        $nik  	  = !isset($_POST['nik']) ? NULL : $_POST['nik'];
        $posisi   = !isset($_POST['posisi']) ? NULL : $_POST['posisi'];
        $program  = !isset($_POST['program']) ? NULL : $_POST['program'];
        $pabrik   = !isset($_POST['pabrik']) ? NULL : $_POST['pabrik'];
        $tahun    = !isset($_POST['tahun']) ? NULL : $_POST['tahun'];
		$data		= $this->dlaporanklems->get_data_peserta(NULL, NULL,$regional,$nik,$posisi,$program,$pabrik,$tahun);
        echo json_encode($data);
	}
	
	public function program_batch(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    			= "Input Batch Program";
		$data['title_form']    		= "Input Batch Program";
		$data['bpo']   				= $this->dtransaksiklems->get_opt_bpo(NULL, 'all');
		$data['program']			= $this->dtransaksiklems->get_opt_program(NULL, 'all');
		$data['pabrik']				= $this->dtransaksiklems->get_opt_pabrik(NULL, 'all');
		$data['institusi']			= $this->dtransaksiklems->get_opt_institusi(NULL, 'all');
		// $data['peserta']			= $this->dtransaksiklems->get_opt_peserta(NULL, 'all', '9002','9103','308','DWJ2');
		// $data['peserta_tambahan']	= $this->dtransaksiklems->get_opt_peserta(NULL, 'all');
		$data['ttd']				= $this->dtransaksiklems->get_opt_ttd(NULL, 'all');
		$data['grade']				= $this->dtransaksiklems->get_data_grade('open');
		
		// $data['program_batch']		= $this->dtransaksiklems->get_data_program_batch(NULL, 'all', NULL, NULL, NULL, date('Y-m-d', strtotime(date('Y-m-d').'-3 months')), date('Y-m-d'));
		$data['program_batch']		= $this->dtransaksiklems->get_data_program_batch(NULL, 'all', NULL, NULL, NULL, 'on_progress');
		$this->load->view("transaksi/program_batch", $data);
	}
	public function nilai_program(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    			= "Penilaian Batch Program";
		$data['title_form']    		= "Penilaian Batch Program";
		$data['program_batch']		= $this->dtransaksiklems->get_data_program_batch(NULL, 'all', NULL, NULL, NULL, 'on_progress');
		$this->load->view("transaksi/nilai_program", $data);
	}
	
	private function batch($program_batch=NULL){
		if($program_batch==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    		= "Input Tahap Batch Program";
		$data['title_form'] 	= "Input Tahap Batch Program";
		$data['tahap'] 			= $this->dtransaksiklems->get_opt_tahap(NULL, str_replace('_','/',$program_batch));
		$data['program_batch']	= $this->dtransaksiklems->get_data_program_batch(NULL, 'all', str_replace('_','/',$program_batch));
		$data['pabrik']			= $this->dtransaksiklems->get_opt_pabrik(NULL, 'all');
		$data['topik_trainer']	= $this->dtransaksiklems->get_opt_topik_trainer(NULL, 'all');
		$data['nilai']			= $this->dtransaksiklems->get_data_nilai(NULL, NULL, NULL, NULL, 'open');
		$data['grade']			= $this->dtransaksiklems->get_data_grade('open');
		$data['soal_tipe']		= $this->dtransaksiklems->get_data_soal_tipe('open');
		$data['batch']	  		= $this->dtransaksiklems->get_data_batch(NULL, NULL, str_replace('_','/',$program_batch));
		$this->load->view("transaksi/batch", $data);
	}
	private function soal($id_batch=NULL){
		if($id_batch==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$id_batch = $this->generate->kirana_decrypt($id_batch);
		$data['title']    		= "Soal Batch Program";
		$data['title_form'] 	= "Soal Batch Program";
		$data['batch']	  		= $this->dtransaksiklems->get_data_batch($id_batch);
		$data['soal']	  		= $this->dtransaksiklems->get_data_batch_soal($id_batch);
		$this->load->view("transaksi/soal", $data);
	}
	public function materi(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Materi Training";
		$data['title_form'] = "Materi Training";
		$data['batch']		= $this->dtransaksiklems->get_data_batch(NULL, NULL, NULL, base64_decode($this->session->userdata("-id_karyawan-")),date('Y-m-d'));
		$this->load->view("transaksi/materi", $data);
	}
	public function upload(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Upload Evalusi Training";
		$data['title_form'] = "Upload Evalusi Training";
		$data['program']	= $this->dtransaksiklems->get_data_program(NULL, 'all');
		$data['batch']		= $this->dtransaksiklems->get_data_batch(NULL, NULL, NULL, NULL, NULL);
		$this->load->view("transaksi/upload", $data);
	}
	public function upload_filter(){
        $program  = !isset($_POST['program']) ? NULL : $_POST['program'];
        $awal     = !isset($_POST['awal']) ? NULL : $_POST['awal'];
        $akhir    = !isset($_POST['akhir']) ? NULL : $_POST['akhir'];
		
		$data		= $this->dtransaksiklems->get_data_batch(NULL, NULL, NULL, NULL, NULL,$program,$awal,$akhir);
        echo json_encode($data);
	}
	
	private function upload_batch($id_batch=NULL){
		if($id_batch==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    			= "Upload Feedback";
		$data['title_form'] 		= "Upload Feedback";
		$data['peserta']  			= $this->dtransaksiklems->get_data_peserta_batch($id_batch, NULL);
		$data['batch']	  			= $this->dtransaksiklems->get_data_batch($id_batch, NULL);
		$this->load->view("transaksi/upload_batch", $data);
	}
	
	public function nilai(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Penilaian Training";
		$data['title_form'] = "Penilaian Training";
		$data['program']	= $this->dtransaksiklems->get_data_program(NULL, 'all');
		$data['batch']		= $this->dtransaksiklems->get_data_batch(NULL, NULL, NULL, NULL, NULL);
		$this->load->view("transaksi/nilai", $data);
	}
	private function nilai_batch($id_batch=NULL){
		if($id_batch==NULL){
			show_404();
		}
		//====must be initiate in every view function====/
	    $this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    			= "Input Nilai Tahap";
		$data['title_form'] 		= "Input Nilai Tahap";
		$data['nilai_akademik']		= $this->dtransaksiklems->get_data_nilai(NULL, NULL, $id_batch,	1, 'open');//nilai akademik
		$data['nilai_non_akademik']	= $this->dtransaksiklems->get_data_nilai(NULL, NULL, $id_batch,	2, 'open');//nilai non akademik
		$data['peserta']  			= $this->dtransaksiklems->get_data_peserta_batch($id_batch, NULL);
		$data['batch']	  			= $this->dtransaksiklems->get_data_batch($id_batch, NULL);
		$this->load->view("transaksi/nilai_batch", $data);
	}
	public function nilai_filter(){
        $program  = !isset($_POST['program']) ? NULL : $_POST['program'];
        $awal     = !isset($_POST['awal']) ? NULL : $_POST['awal'];
        $akhir    = !isset($_POST['akhir']) ? NULL : $_POST['akhir'];
		
		$data		= $this->dtransaksiklems->get_data_batch(NULL, NULL, NULL, NULL, NULL,$program,$awal,$akhir);
        echo json_encode($data);
	}
	public function evaluasi(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	= "Evaluasi Training";
		$data['title_form'] = "Evaluasi Training";
		$data['batch']		= $this->dtransaksiklems->get_data_batch(NULL, NULL, NULL, base64_decode($this->session->userdata("-id_karyawan-")),NULL,NULL,NULL,NULL,NULL,NULL,'n');
		$this->load->view("transaksi/evaluasi", $data);
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

		$data['title']    			= "Evaluasi Sesi";
		$data['title_form'] 		= "Evaluasi Sesi";
		$data['topik_trainer']		= $this->dtransaksiklems->get_data_topik_trainer($id_trainer, NULL, $trainer);
		$data['feedback_nilai']		= $this->dtransaksiklems->get_data_feedback_nilai(NULL, NULL);
		$data['feedback_pertanyaan']= $this->generate_pertanyaan('Sesi', $id_batch, $id_trainer); //$this->dtransaksiklems->get_data_feedback_pertanyaan(NULL, NULL);
		$data['peserta']  			= $this->dtransaksiklems->get_data_peserta_batch($id_batch, NULL);
		$data['batch_komentar']		= $this->dtransaksiklems->get_data_batch_komentar(NULL, NULL, $id_batch, base64_decode($this->session->userdata("-id_karyawan-")),'sesi');
		$data['batch']	  			= $this->dtransaksiklems->get_data_batch($id_batch, NULL);
		$this->load->view("transaksi/evaluasi_sesi", $data);
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

		$data['title']    			= "Evaluasi Program";
		$data['title_form'] 		= "Evaluasi Program";
		$data['feedback_nilai']		= $this->dtransaksiklems->get_data_feedback_nilai(NULL, NULL);
		$data['feedback_pertanyaan']= $this->generate_pertanyaan('Program', $id_batch, NULL); //$this->dtransaksiklems->get_data_feedback_pertanyaan(NULL, NULL);
		$data['peserta']  			= $this->dtransaksiklems->get_data_peserta_batch($id_batch, NULL);
		$data['batch_komentar']		= $this->dtransaksiklems->get_data_batch_komentar(NULL, NULL, $id_batch, base64_decode($this->session->userdata("-id_karyawan-")),'program');
		$data['batch']	  			= $this->dtransaksiklems->get_data_batch($id_batch, NULL);
		$this->load->view("transaksi/evaluasi_program", $data);
	}
	private function generate_pertanyaan($jenis, $id_batch, $id_trainer){
		$data	= $this->dtransaksiklems->get_data_feedback_pertanyaan(NULL, NULL, base64_decode($this->session->userdata("-nik-")),$jenis,$id_batch,NULL,$id_trainer);
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
			case 'program':
				$this->get_program($param);
				break;
			case 'batch':
				$this->batch($param);
				break;
			case 'soal':
				$this->soal($param);
				break;
			case 'tahap':
				$this->tahap($param);
				break;
			case 'upload_batch':
				$this->upload_batch($param);
				break;
			case 'nilai_batch':
				$this->nilai_batch($param);
				break;
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
			case 'nomor':
				$this->get_nomor();
				break;
			case 'materi':
				$this->get_materi();
				break;
			case 'batch_cek':
				$this->get_batch_cek();
				break;
			case 'soal_cek':
				$this->get_soal_cek();
				break;
			case 'batch':
				$this->get_batch();
				break;
			case 'program_batch':
				$this->get_program_batch();
				break;
			case 'peserta_program_batch':
				$this->get_peserta_program_batch();
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
			case 'program_batch':
				$this->set_program_batch($action);
				break;
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function save($param){
		switch ($param) {
			case 'batch_generate_soal':
				$this->save_batch_generate_soal();
				break;
			case 'set_status':
				$this->save_set_status();
				break;
			case 'set_done':
				$this->save_set_done();
				break;
			case 'upload':
				$this->save_upload();
				break;
			case 'cancel':
				$this->save_cancel_approve();
				break;
			case 'approve':
				$this->save_approve();
				break;
			case 'komentar':
				$this->save_komentar();
				break;
			case 'evaluasi':
				$this->save_evaluasi();
				break;
			case 'score':
				$this->save_score();
				break;
			case 'alasan':
				$this->save_alasan();
				break;
			case 'batch_jumlah_soal':
				$this->save_batch_jumlah_soal();
				break;
			case 'program_grade':
				$this->save_program_grade();
				break;
			case 'batch_grade':
				$this->save_batch_grade();
				break;
			case 'batch_persen_grade':
				$this->save_batch_persen_grade();
				break;
			case 'batch_trainer':
				$this->save_batch_trainer();
				break;
			case 'batch':
				$this->save_batch();
				break;
			case 'program_batch':
				$this->save_program_batch();
				break;
			case 'program_batch_biaya':
				$this->save_program_batch_biaya();
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

	private function get_program_batch(){
		$program_batch	= $this->dtransaksiklems->get_data_program_batch($this->generate->kirana_decrypt($_POST['id_program_batch']), 'all');
		echo json_encode($program_batch);
	}
	private function get_peserta_program_batch(){
		if(isset($_GET['q'])){
			$pabrik 		  = $_GET['pabrik'];
			$program 		  = $_GET['program'];
			$peserta_tambahan = $_GET['peserta_tambahan'];
			$peserta		  = isset($_GET['peserta'])?$_GET['peserta']:null;
            // $data       	  = $this->dtransaksiklems->get_data_user_program($_GET['q'],$program,$pabrik,$peserta_tambahan);
            $data       	  = $this->dtransaksiklems->get_data_user_program($_GET['q'],$program,$pabrik,$peserta_tambahan,$peserta);
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
	private function save_set_status(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$id_program_batch = $this->generate->kirana_decrypt($_POST['id_program_batch']);
		if(isset($_POST['id_program_batch']) && $_POST['id_program_batch'] != ""){
			$data_row   = array(
							  'status'					=> $_POST['status'],
							  'login_edit'		      	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    		=> $datetime
						 );
			$this->dgeneral->update('tbl_program_batch', $data_row, array( 
																array(
																	'kolom'=>'id_program_batch',
																	'value'=>$id_program_batch
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
	private function save_set_done(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		// $id_program_batch = $this->generate->kirana_decrypt($_POST['id_program_batch']);
		$id_program_batch = $_POST['id_program_batch'];
		if(isset($_POST['id_program_batch']) && $_POST['id_program_batch'] != ""){
			//update nomor sertifikat
			$list_peserta 	= $_POST['list_peserta'].",".$_POST['list_peserta_tambahan'];
			$list_peserta 	= explode(",", str_replace(",,",",",$list_peserta));
			$datas			= $this->dtransaksiklems->get_nomor_sertifikat($_POST['tahun']);
			$nomor			= (empty($datas))?0:$datas[0]->jumlah;

			$array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");
			$bulan = $array_bulan[$_POST['bulan']];
			foreach ($list_peserta as $peserta){
				if($_POST['jenis_sertifikat']=='Achievement'){
					$ck_nilai =  $this->dtransaksiklems->get_cek_nilai($peserta, $id_program_batch);
					if(($ck_nilai[0]->grade!='0005')and($ck_nilai[0]->grade!=null)){
						$nomor++;
						$nomor_sertifikat = "KMT/".$array_bulan[$_POST['bulan']]."/".$_POST['tahun']."/".str_pad($nomor, 5, 0, STR_PAD_LEFT);
						$data_row   = array(
										  'nomor_sertifikat'		=> $nomor_sertifikat,
										  'login_edit'		      	=> base64_decode($this->session->userdata("-id_user-")),
										  'tanggal_edit'    		=> $datetime
									 );
						$this->dgeneral->update('tbl_peserta', $data_row, array( 
																			array(
																				'kolom'=>'id_program_batch',
																				'value'=>$id_program_batch
																			),
																			array(
																				'kolom'=>'id_karyawan',
																				'value'=>$peserta
																			)
																		));
					}
				}else if($_POST['jenis_sertifikat']=='Attendance'){
					$nomor++;
					$nomor_sertifikat = "KMT/".$array_bulan[$_POST['bulan']]."/".$_POST['tahun']."/".str_pad($nomor, 5, 0, STR_PAD_LEFT);
					$data_row   = array(
									  'nomor_sertifikat'		=> $nomor_sertifikat,
									  'login_edit'		      	=> base64_decode($this->session->userdata("-id_user-")),
									  'tanggal_edit'    		=> $datetime
								 );
					$this->dgeneral->update('tbl_peserta', $data_row, array( 
																		array(
																			'kolom'=>'id_program_batch',
																			'value'=>$id_program_batch
																		),
																		array(
																			'kolom'=>'id_karyawan',
																			'value'=>$peserta
																		)
																	));
				}else{
					
				}
			}
			//update status done
			$data_row   = array(
							  'status'					=> 'Done',
							  'login_edit'		      	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    		=> $datetime
						 );
			$this->dgeneral->update('tbl_program_batch', $data_row, array( 
																array(
																	'kolom'=>'id_program_batch',
																	'value'=>$id_program_batch
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
	
	private function save_program_batch_biaya(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$id_program_batch = $_POST['id_program_batch'];
		if(isset($_POST['id_program_batch']) && $_POST['id_program_batch'] != ""){
			$data_row   = array(
							  'biaya_training'			=> str_replace(',','',$_POST['biaya_training']),
							  'biaya_traveling'			=> str_replace(',','',$_POST['biaya_traveling']),
							  'login_edit'		      	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    		=> $datetime
						 );
			$this->dgeneral->update('tbl_program_batch', $data_row, array( 
																array(
																	'kolom'=>'id_program_batch',
																	'value'=>$id_program_batch
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
	
	private function save_program_batch(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$ck_ttd_kiri = (isset($_POST['ck_ttd_kiri']))?'y':'n';
		$ck_ttd_kanan = (isset($_POST['ck_ttd_kanan']))?'y':'n';
		$sertifikat_keahlian = (!isset($_POST['sertifikat_keahlian']))?0:$_POST['sertifikat_keahlian'];
		$peserta_tambahan = (!isset($_POST['peserta_tambahan']))?null:implode(",", $_POST['peserta_tambahan']);
		if(isset($_POST['id_program_batch']) && $_POST['id_program_batch'] != ""){
			$data_row   = array(
							  'id_bpo' 		    		=> $_POST['bpo'],
							  'id_program'    			=> $_POST['program'],
							  'nama'     				=> $_POST['nama'],
							  'sertifikat_keahlian'		=> $sertifikat_keahlian,
							  'tanggal_awal_sertifikat'	=> $_POST['tanggal_awal_sertifikat'],
							  'tanggal_akhir_sertifikat'=> $_POST['tanggal_akhir_sertifikat'],
							  'oleh'					=> $_POST['oleh'],
							  'tanggal_awal'			=> $_POST['tanggal_awal'],
							  'tanggal_akhir'			=> $_POST['tanggal_akhir'],
							  'lokasi'					=> $_POST['lokasi'],
							  'kota'					=> $_POST['kota'],
							  'pabrik'					=> implode(",", $_POST['pabrik']),
							  'peserta'					=> implode(",", $_POST['peserta']),
							  'peserta_tambahan'		=> $peserta_tambahan,
							  'ck_ttd_kiri'				=> $ck_ttd_kiri,
							  'ttd_kiri'				=> $_POST['ttd_kiri'],
							  'ck_ttd_kanan'			=> $ck_ttd_kanan,
							  'ttd_kanan'				=> $_POST['ttd_kanan'],
							  'status'					=> $_POST['status'],
							  'login_edit'		      	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    		=> $datetime
						 );
			$this->dgeneral->update('tbl_program_batch', $data_row, array( 
																array(
																	'kolom'=>'id_program_batch',
																	'value'=>$_POST['id_program_batch']
																)
															));
		}else{
			$data_row   = array(
								'id_bpo'	    			=> $_POST['bpo'],
								'id_program'    			=> $_POST['program'],
								'kode' 		    			=> $_POST['kode'],
								'nama'     					=> $_POST['nama'],
								'sertifikat_keahlian'		=> $sertifikat_keahlian,
								'tanggal_awal_sertifikat'	=> $_POST['tanggal_awal_sertifikat'],
								'tanggal_akhir_sertifikat'	=> $_POST['tanggal_akhir_sertifikat'],
								'oleh'						=> $_POST['oleh'],
								'tanggal_awal'				=> $_POST['tanggal_awal'],
								'tanggal_akhir'				=> $_POST['tanggal_akhir'],
								'lokasi'					=> $_POST['lokasi'],
								'kota'						=> $_POST['kota'],
								'pabrik'					=> implode(",", $_POST['pabrik']),
								'peserta'					=> implode(",", $_POST['peserta']),
								'peserta_tambahan'			=> $peserta_tambahan,
							    'ck_ttd_kiri'				=> $ck_ttd_kiri,
							    'ttd_kiri'					=> $_POST['ttd_kiri'],
							    'ck_ttd_kanan'				=> $ck_ttd_kanan,
							    'ttd_kanan'					=> $_POST['ttd_kanan'],
								'status'					=> $_POST['status'],
							    'biaya_training'			=> str_replace(',','',$_POST['biaya_training']),
							    'biaya_traveling'			=> str_replace(',','',$_POST['biaya_traveling']),
								'na'     					=> 'n',
								'del'     					=> 'n',
								'login_buat'        		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      		=> $datetime,
								'login_edit'       		 	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      		=> $datetime
							);
			$this->dgeneral->insert('tbl_program_batch', $data_row);
			
			// //input ke tbl_peserta untuk test online xxxx
			// $id_program_batch = $this->db->insert_id();
			// $data_program_batch	= $this->dtransaksiklems->get_data_program_batch($id_program_batch);
			// $list_peserta = explode(",", $data_program_batch[0]->peserta);
			// foreach ($list_peserta as $peserta) {
				// $data	= $this->dtransaksiklems->get_data_peserta($_POST['id_batch'],$peserta);
				// if(empty($data[0]->id_peserta)){
					// $data_row   = array(
										// 'id_program_batch' 		=> $data_program_batch[0]->id_program_batch,
										// 'id_batch'	    		=> $_POST['id_batch'],
										// 'id_karyawan'			=> $peserta,
										// 'na'     				=> 'n',
										// 'del'     				=> 'n',
										// 'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
										// 'tanggal_buat'      	=> $datetime,
										// 'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
										// 'tanggal_edit'      	=> $datetime
									// );
					// $this->dgeneral->insert('tbl_peserta_', $data_row);
				// }
			// }	
			// $list_peserta_tambahan= explode(",", $_POST['peserta_tambahan_batch']);
			// foreach ($list_peserta_tambahan as $peserta_tambahan) {
				// $data_tambahan	= $this->dtransaksiklems->get_data_peserta($_POST['id_batch'],$peserta_tambahan);
				// if(empty($data_tambahan[0]->id_peserta)){
					// $data_row   = array(
										// 'id_program_batch' 		=> $_POST['id_program_batch'],
										// 'id_batch'	    		=> $_POST['id_batch'],
										// 'id_karyawan'			=> $peserta_tambahan,
										// 'na'     				=> 'n',
										// 'del'     				=> 'n',
										// 'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
										// 'tanggal_buat'      	=> $datetime,
										// 'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
										// 'tanggal_edit'      	=> $datetime
									// );
					// $this->dgeneral->insert('tbl_peserta_', $data_row);
				// }
			// }				
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
	
	private function get_program(){
		$program	= $this->dtransaksiklems->get_data_program($_POST['id_program']);
		echo json_encode($program);
	}
	
	//-------------------------------------------------//
	
	private function get_batch_cek(){
		$batch	= $this->dtransaksiklems->get_data_batch(null,null,null,null,null,null,null,null,$_POST['id_program_batch'],$_POST['id_tahap']);
		echo json_encode($batch);
	}
	private function get_soal_cek(){
		$id_batch 	  = $this->generate->kirana_decrypt($_POST['id_batch']);
		$id_soal_tipe = $this->generate->kirana_decrypt($_POST['id_soal_tipe']);
		$topik		  = $this->generate->kirana_decrypt($_POST['topik']);
		$soal_cek	  = $this->dtransaksiklems->get_data_soal_cek($id_batch,$id_soal_tipe,$topik);
		echo json_encode($soal_cek);
	}
	private function get_batch(){
		$batch	= $this->dtransaksiklems->get_data_batch($this->generate->kirana_decrypt($_POST['id_batch']), 'all');
		echo json_encode($batch);
	}
    private function set_batch($action){
        $id_batch = $this->generate->kirana_decrypt($_POST['id_batch']);
		$this->general->connectDbPortal();
        $delete     = $this->general->set($action, "tbl_batch", array(
																	array(
																		'kolom'=>'id_batch',
																		'value'=>$id_batch
																	)
																));
        $this->general->closeDb();
        echo json_encode($delete);
    }
	private function save_batch(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$online = (isset($_POST['online']))?'y':'n';
		if(isset($_POST['id_batch']) && $_POST['id_batch'] != ""){
			$data_row   = array(
							  'id_program_batch'		=> $_POST['id_program_batch'],
							  'id_tahap'	    		=> $_POST['id_tahap'],
							  'tanggal_awal'			=> $_POST['tanggal_awal'],
							  'tanggal_akhir'			=> $_POST['tanggal_akhir'],
							  'tanggal'					=> $_POST['tanggal'],
							  'jam_awal'				=> $_POST['jam_awal'],
							  'jam_akhir'				=> $_POST['jam_akhir'],
							  'tempat'					=> $_POST['lokasi'],
							  'online'					=> $online,
							  'login_edit'		      	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    		=> $datetime
						 );
			$this->dgeneral->update('tbl_batch', $data_row, array( 
																array(
																	'kolom'=>'id_batch',
																	'value'=>$_POST['id_batch']
																)
															));
		}else{
			$data_row   = array(
								'id_program_batch'		=> $_POST['id_program_batch'],
								'id_tahap'	    		=> $_POST['id_tahap'],
								'tanggal_awal'			=> $_POST['tanggal_awal'],
								'tanggal_akhir'			=> $_POST['tanggal_akhir'],
								'tanggal'				=> $_POST['tanggal'],
								'jam_awal'				=> $_POST['jam_awal'],
								'jam_akhir'				=> $_POST['jam_akhir'],
								'tempat'				=> $_POST['lokasi'],
								'online'				=> $online,
								'na'     				=> 'n',
								'del'     				=> 'n',
								'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      	=> $datetime,
								'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      	=> $datetime
							); 
			$this->dgeneral->insert('tbl_batch', $data_row);
			//tambahan input data ke tbl_kurikulum untuk kebutuhan test online
			$id_batch = $this->db->insert_id();
			$tbl_tahap = $this->dtransaksiklems->get_opt_tahap($_POST['id_tahap'], NULL);

			$data_kurikulum   = array(
								'id_kurikulum'	=> $id_batch,
								'nama'	   		=> $_POST['nama_batch'].'-'.$tbl_tahap[0]->nama,
								'na'    		=> 'n',
								'del'   		=> 'n',
								'login_buat'   	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat' 	=> $datetime,
								'login_edit'  	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit' 	=> $datetime
							);
			$this->dgeneral->insert('tbl_kurikulum', $data_kurikulum);
			//update id_kurikulum di tbl_batch
			$data_batch   = array(
							  'id_kurikulum'	=> $id_batch,
							  'login_edit'		=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_batch', $data_batch, array( 
																array(
																	'kolom'=>'id_batch',
																	'value'=>$id_batch
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

	private function save_batchxx(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$online = (isset($_POST['online']))?'y':'n';
		if(isset($_POST['id_batch']) && $_POST['id_batch'] != ""){
			$data_row   = array(
							  'id_program_batch'		=> $_POST['id_program_batch'],
							  'id_tahap'	    		=> $_POST['id_tahap'],
							  'tanggal_awal'			=> $_POST['tanggal_awal'],
							  'tanggal_akhir'			=> $_POST['tanggal_akhir'],
							  'tanggal'					=> $_POST['tanggal'],
							  'jam_awal'				=> $_POST['jam_awal'],
							  'jam_akhir'				=> $_POST['jam_akhir'],
							  'tempat'					=> $_POST['lokasi'],
							  'online'					=> $online,
							  'login_edit'		      	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    		=> $datetime
						 );
			$this->dgeneral->update('tbl_batch', $data_row, array( 
																array(
																	'kolom'=>'id_batch',
																	'value'=>$_POST['id_batch']
																)
															));
		}else{
			$data_row   = array(
								'id_program_batch'		=> $_POST['id_program_batch'],
								'id_tahap'	    		=> $_POST['id_tahap'],
								'tanggal_awal'			=> $_POST['tanggal_awal'],
								'tanggal_akhir'			=> $_POST['tanggal_akhir'],
								'tanggal'				=> $_POST['tanggal'],
								'jam_awal'				=> $_POST['jam_awal'],
								'jam_akhir'				=> $_POST['jam_akhir'],
								'tempat'				=> $_POST['lokasi'],
								'online'				=> $online,
								'na'     				=> 'n',
								'del'     				=> 'n',
								'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      	=> $datetime,
								'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      	=> $datetime
							); 
			$this->dgeneral->insert('tbl_batch', $data_row);
			//tambahan input data ke tbl_kurikulum untuk kebutuhan test online
			$id_batch = $this->db->insert_id();
			
			//update id_kurikulum di tbl_batch
			$data_batch   = array(
							  'id_kurikulum'	=> $id_batch,
							  'login_edit'		=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_batch', $data_batch, array( 
																array(
																	'kolom'=>'id_batch',
																	'value'=>$id_batch
																)
															));
															
			$tbl_tahap = $this->dlaporanklems->get_data_tahap($_POST['id_tahap'], NULL);
			$data_kurikulum   = array(
								'id_kurikulum'	=> $id_batch,
								'nama'	   		=> $_POST['nama_batch'].'('.$tbl_tahap[0]->nama.')',
								'na'    		=> 'n',
								'del'   		=> 'n',
								'login_buat'   	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat' 	=> $datetime,
								'login_edit'  	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit' 	=> $datetime
							);
			$this->dgeneral->insert('tbl_kurikulum', $data_kurikulum);
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
	//save score
	private function save_score(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$data	= $this->dtransaksiklems->get_data_batch_score(NULL, NULL, $_POST['id_batch'],$_POST['id_peserta'],$_POST['id_batch_nilai'],$_POST['id_karyawan']);
		if(empty($data[0]->id_batch_score)){
			$data_row   = array(
								'id_batch'	    		=> $_POST['id_batch'],
								'id_peserta'			=> $_POST['id_peserta'],
								'id_batch_nilai'   		=> $_POST['id_batch_nilai'],
								'id_karyawan'   		=> $_POST['id_karyawan'],
								'score'			   		=> $_POST['score'],
								'na'     				=> 'n',
								'del'     				=> 'n',
								'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      	=> $datetime,
								'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      	=> $datetime
							);
			$this->dgeneral->insert('tbl_batch_score', $data_row);
		}else{
			$data_row   = array(
								'id_batch'	    		=> $_POST['id_batch'],
								'id_peserta'			=> $_POST['id_peserta'],
								'id_batch_nilai'   		=> $_POST['id_batch_nilai'],
								'id_karyawan'   		=> $_POST['id_karyawan'],
								'score'			   		=> $_POST['score'],
								'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      	=> $datetime
							);
			$this->dgeneral->update('tbl_batch_score', $data_row, array( 
																array(
																	'kolom'=>'id_batch_score',
																	'value'=>$data[0]->id_batch_score
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
	//save alasan
	private function save_alasan(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(!empty($_POST['id_peserta'])){
			$data_row   = array(
								'alasan'	    		=> $_POST['alasan'],
								'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      	=> $datetime
							);
			$this->dgeneral->update('tbl_peserta', $data_row, array( 
																array(
																	'kolom'=>'id_peserta',
																	'value'=>$_POST['id_peserta']
																),
																array(
																	'kolom'=>'id_batch',
																	'value'=>$_POST['id_batch']
																),
																array(
																	'kolom'=>'id_karyawan',
																	'value'=>$_POST['id_karyawan']
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
	//set approve
	private function save_approve(){
		$datetime   = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if($_POST['posisi']=='kiri'){
			$data_row   = array(
							'ttd_kiri'	 	=> base64_decode($this->session->userdata("-id_karyawan-")),
							'status_kiri'	=> 1,
							'tanggal_kiri' 	=> $datetime
						);
		}
		if($_POST['posisi']=='kanan'){
			$data_row   = array(
							'ttd_kanan'	 	=> base64_decode($this->session->userdata("-id_karyawan-")),
							'status_kanan'	=> 1,
							'tanggal_kanan'	=> $datetime
						);
		}
		$this->dgeneral->update('tbl_peserta', $data_row, array( 
															array(
																'kolom'=>'id_karyawan',
																'value'=>$_POST['id_karyawan']
															),
															array(
																'kolom'=>'id_program_batch',
																'value'=>$_POST['id_program_batch']
															)
														));
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
	//cancel approve
	private function save_cancel_approve(){
		$datetime   = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if($_POST['posisi']=='kiri'){
			$data_row   = array(
							'ttd_kiri'	 	=> base64_decode($this->session->userdata("-id_karyawan-")),
							'status_kiri'	=> 0,
							'tanggal_kiri' 	=> $datetime
						);
		}
		if($_POST['posisi']=='kanan'){
			$data_row   = array(
							'ttd_kanan'	 	=> base64_decode($this->session->userdata("-id_karyawan-")),
							'status_kanan'	=> 0,
							'tanggal_kanan'	=> $datetime
						);
		}
		$this->dgeneral->update('tbl_peserta', $data_row, array( 
															array(
																'kolom'=>'id_karyawan',
																'value'=>$_POST['id_karyawan']
															),
															array(
																'kolom'=>'id_program_batch',
																'value'=>$_POST['id_program_batch']
															)
														));
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
	//cetak sertifikat
    public function cetak($id_karyawan=NULL,$id_program_batch=NULL,$posisi=NULL){
        $this->general->check_session($_SERVER['REQUEST_URI']);
		$program_batch = $this->dtransaksiklems->get_data_program_batch($id_program_batch);
		$tbl_peserta   = $this->dtransaksiklems->get_data_peserta(null,$id_karyawan,null,$id_program_batch);
		$peserta   	   = $this->dtransaksiklems->get_data_karyawan($id_karyawan);
		$ttd_kiri  	   = $this->dtransaksiklems->get_data_karyawan($program_batch->ttd_kiri);
		$ttd_kanan 	   = $this->dtransaksiklems->get_data_karyawan($program_batch->ttd_kanan);
        if(isset($id_karyawan) && trim($id_karyawan) !== "" && $program_batch!==""){
            $this->load->library('Mypdf');
			
			$this->mypdf->AddPage('L','A4');
			$this->mypdf->setAutoPageBreak(0, 0.5);
			$this->mypdf->Ln(63);
			$this->mypdf->AddFont('BaskOldFace','','BASKVILL.php');
			$this->mypdf->SetFont('BaskOldFace', '', 12);
			$this->mypdf->Cell(0, 8, $tbl_peserta->nomor_sertifikat, 0, 0, 'C');
			$this->mypdf->Ln(8);
			$this->mypdf->AddFont('BaskOldFace','','BASKVILL.php');
			$this->mypdf->SetFont('BaskOldFace', '', 16);
			$this->mypdf->Cell(0, 12, 'Is awarded to', 0, 0, 'C');
			$this->mypdf->Ln(12);
			$this->mypdf->SetFont('Times', 'B', 40);
			$this->mypdf->Cell(0, 12, ucwords(strtolower($peserta->nama)), 0, 0, 'C');
			$this->mypdf->Ln(18);
			$this->mypdf->AddFont('BaskOldFace','','BASKVILL.php');
			$this->mypdf->SetFont('BaskOldFace', '', 18);
			$this->mypdf->Cell(0, 12, 'NIK : '.$peserta->id_karyawan, 0, 0, 'C');
			$this->mypdf->Ln(15);
			$this->mypdf->AddFont('Calibri','','calibri.php');
			$this->mypdf->SetFont('Calibri', '', 18);
			$this->mypdf->Cell(0, 12, 'Has '.strtolower($program_batch->jenis_sertifikat).' and successfully completed the program of ', 0, 0, 'C');
			$this->mypdf->Ln(15);
			$this->mypdf->AddFont('Calibri-Bold','','CALIBRIB.php');
			$this->mypdf->SetFont('Calibri-Bold', '', 34);
			$this->mypdf->Cell(0, 12, $program_batch->nama_program, 0, 0, 'C');
			$this->mypdf->Ln(13);
			$this->mypdf->AddFont('BaskOldFace','','BASKVILL.php');
			$this->mypdf->SetFont('BaskOldFace', '', 14);
			$this->mypdf->Cell(0, 12, 'In '.$program_batch->lokasi.', on '.date_format(date_create($program_batch->tanggal_awal_program_batch),"d F Y").' - '.date_format(date_create($program_batch->tanggal_akhir_program_batch),"d F Y"), 0, 0, 'C');
			$this->mypdf->Ln(35);
			if($posisi=='center'){
				$this->mypdf->SetFont('Times', 'BU', 16);
				if($program_batch->ck_ttd_kiri=='y'){
					$this->mypdf->Image(base_url().''.$ttd_kiri->ttd, 135, 165, 30);
				}
				$this->mypdf->Cell(277, 10, ucwords(strtolower($ttd_kiri->nama)), 0, 0, 'C');
				$this->mypdf->Ln(6);
			}else{
				$this->mypdf->SetFont('Times', 'BU', 16);
				if($program_batch->ck_ttd_kiri=='y'){
					$this->mypdf->Image(base_url().''.$ttd_kiri->ttd, 65, 165, 30);
				}
				$this->mypdf->Cell(138, 10, ucwords(strtolower($ttd_kiri->nama)), 0, 0, 'C');
				
				if($program_batch->ck_ttd_kanan=='y'){
					$this->mypdf->Image(base_url().''.$ttd_kanan->ttd, 200, 165, 30);
				}	
				$this->mypdf->Cell(138, 10, ucwords(strtolower($ttd_kanan->nama)), 0, 0, 'C');
				$this->mypdf->Ln(6);
				$this->mypdf->SetFont('Times', 'B', 16);
				$this->mypdf->Cell(138, 10, $ttd_kiri->posisi_sertifikat, 0, 0, 'C');
				$this->mypdf->Cell(138, 10, $ttd_kanan->posisi_sertifikat, 0, 0, 'C');
			}
			$this->mypdf->Output();
			//update tbl peserta status print
			$data_row   = array(
								'status_print'			=> 1,
								'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      	=> $datetime
							);
			$this->dgeneral->update('tbl_peserta', $data_row, array( 
																array(
																	'kolom'=>'nomor_sertifikat',
																	'value'=>$tbl_peserta->nomor_sertifikat
																)
															));
			
        }else{
            show_404();
        }

    }
	
	
	//save evaluasi
	private function save_evaluasi(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$data	= $this->dtransaksiklems->get_data_batch_feedback(NULL, NULL, $_POST['id_batch'],$_POST['id_karyawan'],$_POST['id_feedback_pertanyaan'],$_POST['id_trainer']);
		if(empty($data[0]->id_batch_feedback)){
			$data_row   = array(
								'id_batch'	    		=> $_POST['id_batch'],
								'id_karyawan'			=> $_POST['id_karyawan'],
								'id_feedback_pertanyaan'=> $_POST['id_feedback_pertanyaan'],
								'id_feedback_kategori'	=> $_POST['id_feedback_kategori'],
								'id_feedback_nilai'		=> $_POST['id_feedback_nilai'],
								'id_trainer'			=> $_POST['id_trainer'],
								'na'     				=> 'n',
								'del'     				=> 'n',
								'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      	=> $datetime,
								'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      	=> $datetime
							);
			$this->dgeneral->insert('tbl_batch_feedback', $data_row);
		}else{
			$data_row   = array(
								'id_batch'	    		=> $_POST['id_batch'],
								'id_karyawan'			=> $_POST['id_karyawan'],
								'id_feedback_pertanyaan'=> $_POST['id_feedback_pertanyaan'],
								'id_feedback_kategori'	=> $_POST['id_feedback_kategori'],
								'id_feedback_nilai'		=> $_POST['id_feedback_nilai'],
								'id_trainer'			=> $_POST['id_trainer'],
								'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      	=> $datetime
							);
			$this->dgeneral->update('tbl_batch_feedback', $data_row, array( 
																array(
																	'kolom'=>'id_batch_feedback',
																	'value'=>$data[0]->id_batch_feedback
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
	
	private function save_batch_trainer(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_program_batch']) && $_POST['id_program_batch'] != ""){
			$data_row   = array(
							  'trainer'		=> implode(",", $_POST['batch_trainer']),
							  'login_edit'	=> base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit' => $datetime
						 );
			$this->dgeneral->update('tbl_batch', $data_row, array( 
																array(
																	'kolom'=>'id_batch',
																	'value'=>$_POST['id_batch']
																)
															));
		}
		
		//input ke tbl_peserta untuk test online
		$status_kiri = ($_POST['ck_ttd_kiri']=='n')?1:null;
		$status_kanan = ($_POST['ck_ttd_kanan']=='n')?1:null;
		$list_peserta 	= $_POST['peserta_batch'].",".$_POST['peserta_tambahan_batch'];
		$list_peserta 	= explode(",", str_replace(",,",",",$list_peserta));
		foreach ($list_peserta as $peserta) {
			$data	= $this->dtransaksiklems->get_data_peserta_cek($_POST['id_batch'],$peserta);
			if(empty($data[0]->id_peserta)){
				$data_row   = array(
									'id_program_batch' 		=> $_POST['id_program_batch'],
									'id_batch'	    		=> $_POST['id_batch'],
									'id_karyawan'			=> $peserta,
									'ttd_kiri'				=> $_POST['ttd_kiri'],
									'ttd_kanan'				=> $_POST['ttd_kanan'],
									'status_kiri'			=> $status_kiri,
									'status_kanan'			=> $status_kanan,
									'is_fnsh'    			=> 'n',
									'na'     				=> 'n',
									'del'     				=> 'n',
									'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'      	=> $datetime,
									'login_edit'       		=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit'      	=> $datetime
								);
				$this->dgeneral->insert('tbl_peserta', $data_row);
			}
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
	private function save_batch_persen_grade(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_program_batch']) && $_POST['id_program_batch'] != ""){
			$data	= $this->dtransaksiklems->get_data_nilai();
			foreach($data as $dt){
				$this->db->delete('tbl_batch_nilai', array('id_batch' => $_POST['id_batch'],'id_nilai'=>$dt->id_nilai)); 												
				$bobot = "bobot_".$dt->id_nilai;
				$data_row   = array(
									'id_batch'	    	=> $_POST['id_batch'],
									'id_nilai_kategori'	=> $dt->id_nilai_kategori,
									'id_nilai' 	    	=> $dt->id_nilai,
									'bobot'    			=> $_POST[$bobot],
									'na'     			=> 'n',
									'del'     			=> 'n',
									'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'      => $datetime,
									'login_edit'       	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit'      => $datetime
								);
				$this->dgeneral->insert('tbl_batch_nilai', $data_row);
			}
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
	
	private function save_batch_jumlah_soal(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_program_batch']) && $_POST['id_program_batch'] != ""){
			$data	= $this->dtransaksiklems->get_data_soal_tipe(null);
			foreach($data as $dt){
				$this->db->delete('tbl_soal_jumlah', array('id_batch' => $_POST['id_batch'],'id_soal_tipe'=>$dt->id_soal_tipe)); 												
				$jum_soal = "jumlah_soal_".$dt->id_soal_tipe;
				$data_row   = array(
									'id_batch'	    	=> $_POST['id_batch'],
									'id_soal_tipe'    	=> $dt->id_soal_tipe,
									'jumlah_soal' 		=> $_POST[$jum_soal],
									'na'     			=> 'n',
									'del'     			=> 'n',
									'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'      => $datetime,
									'login_edit'       	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit'      => $datetime
								);
				$this->dgeneral->insert('tbl_soal_jumlah', $data_row);
			}
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
	private function save_program_grade(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_program_batch']) && $_POST['id_program_batch'] != ""){
			$data	= $this->dtransaksiklems->get_data_grade(null);
			foreach($data as $dt){
				// $ck = $this->db->delete('tbl_program_grade', array('id_program_batch' => $_POST['id_program_batch'],'id_grade'=>$dt->id_grade));
				$ck = $this->dtransaksiklems->get_data_program_grade(null, $_POST['id_program_batch'], $dt->id_grade);	
				$grade_awal  = "grade_awal_".$dt->id_grade;
				$grade_akhir = "grade_akhir_".$dt->id_grade;
				if(empty($ck)){
					$data_row    = array(
										'id_program_batch' 	=> $_POST['id_program_batch'],
										'id_grade' 	    	=> $dt->id_grade,
										'grade_awal'		=> $_POST[$grade_awal],
										'grade_akhir'		=> $_POST[$grade_akhir],
										'na'     			=> 'n',
										'del'     			=> 'n',
										'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
										'tanggal_buat'      => $datetime,
										'login_edit'       	=> base64_decode($this->session->userdata("-id_user-")),
										'tanggal_edit'      => $datetime
									);
					$this->dgeneral->insert('tbl_program_grade', $data_row);
				}else{
					$data_row    = array(
										'grade_awal'		=> $_POST[$grade_awal],
										'grade_akhir'		=> $_POST[$grade_akhir],
										'login_edit'       	=> base64_decode($this->session->userdata("-id_user-")),
										'tanggal_edit'      => $datetime
									);
					$this->dgeneral->update('tbl_program_grade', $data_row, array( 
																array(
																	'kolom'=>'id_program_grade',
																	'value'=>$ck[0]->id_program_grade
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
			$msg    = "Transaksi Berhasil";
			$sts    = "OK";
		}	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}
	
	private function save_batch_grade(){
		$datetime       = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_program_batch']) && $_POST['id_program_batch'] != ""){
			$data	= $this->dtransaksiklems->get_data_grade(null);
			foreach($data as $dt){
				// $this->db->delete('tbl_batch_grade', array('id_batch' => $_POST['id_batch'],'id_grade'=>$dt->id_grade)); 												
				$ck = $this->dtransaksiklems->get_data_batch_grade(null, $_POST['id_batch'], $dt->id_grade);	
				$grade_awal  = "grade_awal_".$dt->id_grade;
				$grade_akhir = "grade_akhir_".$dt->id_grade;
				if(empty($ck)){
					$data_row    = array(
										'id_batch'	    	=> $_POST['id_batch'],
										'id_grade' 	    	=> $dt->id_grade,
										'grade_awal'		=> $_POST[$grade_awal],
										'grade_akhir'		=> $_POST[$grade_akhir],
										'na'     			=> 'n',
										'del'     			=> 'n',
										'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
										'tanggal_buat'      => $datetime,
										'login_edit'       	=> base64_decode($this->session->userdata("-id_user-")),
										'tanggal_edit'      => $datetime
									);
					$this->dgeneral->insert('tbl_batch_grade', $data_row);
				}else{
					$data_row    = array(
										'grade_awal'		=> $_POST[$grade_awal],
										'grade_akhir'		=> $_POST[$grade_akhir],
										'login_edit'       	=> base64_decode($this->session->userdata("-id_user-")),
										'tanggal_edit'      => $datetime
									);
					$this->dgeneral->update('tbl_batch_grade', $data_row, array( 
																array(
																	'kolom'=>'id_batch_grade',
																	'value'=>$ck[0]->id_batch_grade
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
			$msg    = "Transaksi Berhasil";
			$sts    = "OK";
		}	

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
	}

	
	private function save_batch_generate_soal(){
		$datetime  = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_batch']) && $_POST['id_batch'] != ""){
			$list_peserta 	= $_POST['peserta'].",".$_POST['peserta_tambahan'];
			$list_peserta 	= explode(",", str_replace(",,",",",$list_peserta));
			$list_jumlah_soal 	= explode(",", substr($_POST['jumlah_soal'], 0, -1));
			//list peserta
			foreach ($list_peserta as $peserta){
				//list jumlah_soal
				foreach ($list_jumlah_soal as $jumlah_soal){
					$ex_jumlah_soal	= explode("|", $jumlah_soal);
					$tbl_soal		= $this->dtransaksiklems->get_data_soal($ex_jumlah_soal['0'],$ex_jumlah_soal['1'],$_POST['topik']);
					foreach($tbl_soal as $dt){
						$data_row   = array(
											'id_batch' 			=> $_POST['id_batch'],
											'id_karyawan'	    => $peserta,
											'id_soal'			=> $dt->id_soal,
											'jawaban_random'	=> $dt->jawaban_random,
											'id_soal_jawaban'	=> 0,
											'soal_ke'			=> 0,
											'na'     			=> 'n',
											'del'     			=> 'n',
											'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
											'tanggal_buat'      => $datetime,
											'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
											'tanggal_edit'      => $datetime
										);
						$this->dgeneral->insert('tbl_batch_soal', $data_row);
					}
				}
			}
			//list jumlah_soal buat cetak
			foreach ($list_jumlah_soal as $jumlah_soal){
				$ex_jumlah_soal	= explode("|", $jumlah_soal);
				$tbl_soal		= $this->dtransaksiklems->get_data_soal($ex_jumlah_soal['0'],$ex_jumlah_soal['1'],$_POST['topik']);
				foreach($tbl_soal as $dt){
					$data_row   = array(
										'id_batch' 			=> $_POST['id_batch'],
										'id_karyawan'	    => 0,
										'id_soal'			=> $dt->id_soal,
										'jawaban_random'	=> $dt->jawaban_random,
										'id_soal_jawaban'	=> 0,
										'soal_ke'			=> 1,
										'na'     			=> 'n',
										'del'     			=> 'n',
										'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
										'tanggal_buat'      => $datetime,
										'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
										'tanggal_edit'      => $datetime
									);
					$this->dgeneral->insert('tbl_batch_soal', $data_row);
				}
			}
			
			$data_row2   = array(
								'generate_soal'		=> 'y',
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->update('tbl_batch', $data_row2, array( 
																array(
																	'kolom'=>'id_batch',
																	'value'=>$_POST['id_batch']
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
	
	private function save_komentar(){
		$datetime  = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		if(isset($_POST['id_batch_komentar']) && $_POST['id_batch_komentar'] != ""){
			$data_row   = array(
							  'id_batch' 		=> $_POST['id_batch'],
							  'id_karyawan'	    => base64_decode($this->session->userdata("-nik-")),
							  'jenis'			=> $_POST['jenis'],
							  'komentar_positif'=> $_POST['komentar_positif'],
							  'komentar_negatif'=> $_POST['komentar_negatif'],
							  'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							  'tanggal_edit'    => $datetime
						 );
			$this->dgeneral->update('tbl_batch_komentar', $data_row, array( 
																array(
																	'kolom'=>'id_batch_komentar',
																	'value'=>$_POST['id_batch_komentar']
																)
															));
		}else{
			$data_row   = array(
							    'id_batch' 			=> $_POST['id_batch'],
							    'id_karyawan'	    => base64_decode($this->session->userdata("-nik-")),
							    'jenis'				=> $_POST['jenis'],
								'komentar_positif'	=> $_POST['komentar_positif'],
							    'komentar_negatif'	=> $_POST['komentar_negatif'],
								'na'     			=> 'n',
								'del'     			=> 'n',
								'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_buat'      => $datetime,
								'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
								'tanggal_edit'      => $datetime
							);
			$this->dgeneral->insert('tbl_batch_komentar', $data_row);
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
	
	private function get_persen_grade(){
		// $persen_grade	= $this->dtransaksiklems->get_persen_grade($this->generate->kirana_decrypt($_POST['id_batch']), 'all');
		$persen_grade	= $this->dtransaksiklems->get_data_batch($this->generate->kirana_decrypt($_POST['id_batch']), 'all');
		echo json_encode($persen_grade);
	}
	//-------------------------------------------------//
	

	private function get_nomor(){
		$nomor	= $this->dtransaksiklems->get_data_program_batch_nomor($_POST['id_bpo'],$_POST['id_program']);
		echo json_encode($nomor);
	}
	private function get_materi(){
		$materi	= $this->dtransaksiklems->get_data_materi($this->generate->kirana_decrypt($_POST['id_materi']), 'all');
		echo json_encode($materi);
	}
	
	//-------------------------------------------------//


	public function save_upload(){
		if(!empty($_FILES['excelData']['name'])){
			$target_dir = "./assets/temp";

			if (!file_exists($target_dir)) {
			    mkdir($target_dir, 0755, true);
			}

			$config['upload_path']          = $target_dir;
			$config['allowed_types']        = 'xls|xlsx';
	 
			$this->load->library('upload', $config);
	 
			if ( ! $this->upload->do_upload('excelData')){
				echo $this->upload->display_errors();
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
				
				$this->dgeneral->begin_transaction();
				// echo json_encode("masuk");

				for($brs=5; $brs<=$highestRow; $brs++){
					$data_pertanyaan = $this->dtransaksiklems->get_data_feedback_pertanyaan(NULL,NULL,NULL,NULL,NULL, $data_excel->getCellByColumnAndRow(0, $brs)->getValue());
					$data_batch_feedback = $this->dtransaksiklems->get_data_batch_feedback(NULL, NULL, $_POST['id_batch'], 0, $data_pertanyaan[0]->id_feedback_pertanyaan, $_POST['id_trainer']);
					$data_row	= array(
									'id_batch' 				=> $_POST['id_batch'],
									'id_trainer' 			=> $_POST['id_trainer'],
									// 'id_karyawan' 			=> $_POST['id_karyawan'],
									'id_karyawan' 			=> 0,
									'id_feedback_pertanyaan'=> $data_pertanyaan[0]->id_feedback_pertanyaan,
									'id_feedback_kategori'	=> $data_pertanyaan[0]->id_feedback_kategori,
									'id_feedback_nilai' 	=> $data_excel->getCellByColumnAndRow(2, $brs)->getValue(),
									'login_buat' 			=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'			=> $datetime,
									'login_edit' 			=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit' 			=> $datetime,
									'na' 					=> 'n',
									'del'					=> 'n'
								);
					// if($data_batch_feedback[0]->id_batch_feedback==null){
					if(!empty($data_batch_feedback[0]->id_batch_feedback)){
						$this->dgeneral->update('tbl_batch_feedback', $data_row, array(
																			array(
																				'kolom'=>'id_batch_feedback',
																				'value'=>$data_batch_feedback[0]->id_batch_feedback
																			)
																		   ));
						
					}else{
						$this->dgeneral->insert('tbl_batch_feedback', $data_row);
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

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	
}