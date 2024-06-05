<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : MASTER DEPO
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
	
	    $this->load->model('dmasterdepo');
	}

	public function index(){
		show_404();
	}
	public function role($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Role Master Depo";
		$data['title_form']  = "Form Role Master Depo";
		$data['role'] 	 	 = $this->get_role('array', NULL, NULL, 'n');
		$data['divisi']	 	 = $this->get_divisi('array', NULL, 'n', 'n');
		// $this->data['divisi'] = $this->dmasterdepo->get_master_divisi();
		$this->load->view("master/role", $data);	
	}
	public function nilai($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Grade Nilai";
		$data['title_form']  = "Form Master Nilai";
		$data['nilai']	 	 = $this->get_nilai('array', NULL, NULL, NULL);
		$this->load->view("master/nilai", $data);	
	}
	public function matrix($param=NULL) {
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['title']      	 = "Master Matrix Depo";
		$data['title_form'] 	 = "Setting Master Matrix Depo";
		// $data['master_matrix'] 	 = $this->dmasterdepo->get_data_master_matrix("open");
		$data['master_matrix'] 	 = $this->get_master_matrix('array');
		$data['matrix_header'] 	 = $this->get_matrix_header('array');
		$this->load->view("master/matrix", $data);
	}
	public function dokumen($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Dokumen";
		$data['title_form']  = "Form Master Dokumen";
		// $data['jenis_dokumen'] 	= $this->get_jenis_dokumen('array', NULL, 'n', 'n');
		$data['dokumen']	 	= $this->get_dokumen('array', NULL, NULL, NULL);
		$this->load->view("master/dokumen", $data);	
	}
	public function biaya($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Biaya";
		$data['title_form']  = "Form Master Biaya";
		$data['biaya']	 	 = $this->get_biaya('array', NULL, NULL, NULL);
		$this->load->view("master/biaya", $data);	
	}
	public function lokasi($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Lokasi Depo";
		$data['title_form']  = "Form Master Lokasi Depo";
		$data['lokasi']	 	 = $this->get_lokasi('array', NULL, NULL, NULL);
		$this->load->view("master/lokasi", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'nilai':
				$keterangan = (isset($_POST['keterangan']) ? $_POST['keterangan'] : NULL);
				$this->get_nilai(NULL, NULL, NULL, NULL, $keterangan);
				break;
			case 'matrix_header':
				$id_matrix_header = (isset($_POST['id_matrix_header']) ? $this->generate->kirana_decrypt($_POST['id_matrix_header']) : NULL);
				$this->get_matrix_header(NULL, $id_matrix_header);
				break;
			case 'master_matrix':
				$id_matrix = (isset($_POST['id_matrix']) ? $this->generate->kirana_decrypt($_POST['id_matrix']) : NULL);
				$this->get_master_matrix(NULL, $id_matrix);
				break;
			case 'role':
				$id_role = (isset($_POST['id_role']) ? $this->generate->kirana_decrypt($_POST['id_role']) : NULL);
				$this->get_role(NULL, $id_role);
				break;
			case 'dokumen':
				$id_dokumen = (isset($_POST['id_dokumen']) ? $this->generate->kirana_decrypt($_POST['id_dokumen']) : NULL);
				$na 		= (isset($_POST['na']) ? $_POST['na'] : NULL);
				$jenis_depo = (isset($_POST['jenis_depo']) ? $_POST['jenis_depo'] : NULL);
				$this->get_dokumen(NULL, $id_dokumen, $na, NULL, NULL, NULL, $jenis_depo);
				break;
			case 'biaya':
				$id_biaya = (isset($_POST['id_biaya']) ? $this->generate->kirana_decrypt($_POST['id_biaya']) : NULL);
				if(isset($_POST['filter_jenis_depo'])){
					$filter_jenis_depo		= array();
					foreach ($_POST['filter_jenis_depo'] as $dt) {
						array_push($filter_jenis_depo, $dt);
					}
				}else{
					$filter_jenis_depo  = NULL;
				}
				if(isset($_POST['filter_jenis_biaya'])){
					$filter_jenis_biaya		= array();
					foreach ($_POST['filter_jenis_biaya'] as $dt) {
						array_push($filter_jenis_biaya, $dt);
					}
				}else{
					$filter_jenis_biaya  = NULL;
				}
			
				$this->get_biaya(NULL, $id_biaya, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $filter_jenis_depo, $filter_jenis_biaya);
				break;
			case 'lokasi':
				$id_lokasi 	= (isset($_POST['id_lokasi']) ? $this->generate->kirana_decrypt($_POST['id_lokasi']) : NULL);
				$na 		= (isset($_POST['na']) ? $_POST['na'] : NULL);
				$this->get_lokasi(NULL, $id_lokasi, $na);
				break;
			case 'gambar':
				$id_gambar 	= (isset($_POST['id_gambar']) ? $this->generate->kirana_decrypt($_POST['id_gambar']) : NULL);
				$na 		= (isset($_POST['na']) ? $_POST['na'] : NULL);
				$this->get_gambar(NULL, $id_gambar, $na);
				break;
			case 'keuangan':
				$id_keuangan 	= (isset($_POST['id_keuangan']) ? $this->generate->kirana_decrypt($_POST['id_keuangan']) : NULL);
				$na 		= (isset($_POST['na']) ? $_POST['na'] : NULL);
				$this->get_keuangan(NULL, $id_keuangan, $na);
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
				case 'role':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_depo_role", array(
						array(
							'kolom' => 'id_role',
							'value' => $this->generate->kirana_decrypt($_POST['id_role'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'dokumen':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_depo_dokumen", array(
						array(
							'kolom' => 'id_dokumen',
							'value' => $this->generate->kirana_decrypt($_POST['id_dokumen'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'biaya':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_depo_biaya", array(
						array(
							'kolom' => 'id_biaya',
							'value' => $this->generate->kirana_decrypt($_POST['id_biaya'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'lokasi':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_depo_lokasi", array(
						array(
							'kolom' => 'id_lokasi',
							'value' => $this->generate->kirana_decrypt($_POST['id_lokasi'])
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
			case 'nilai':
				$this->save_nilai($param);
				break;
			case 'matrix':
				$this->save_matrix($param);
				break;
			case 'role':
				$this->save_role($param);
				break;
			case 'dokumen':
				$this->save_dokumen($param);
				break;
			case 'biaya':
				$this->save_biaya($param);
				break;
			case 'lokasi':
				$this->save_lokasi($param);
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
	private function get_master_matrix($array = NULL, $id_matrix = NULL, $active = NULL, $deleted = NULL) {
		$master_matrix 	= $this->dmasterdepo->get_data_master_matrix("open", $id_matrix, $active, $deleted);
		$master_matrix 	= $this->general->generate_encrypt_json($master_matrix);
		if ($array) {
			return $master_matrix;
		} else {
			echo json_encode($master_matrix);
		}
	}
	private function get_role($array = NULL, $id_role = NULL, $active = NULL, $deleted = NULL) {
		$role 	= $this->dmasterdepo->get_data_role("open", $id_role, $active, $deleted);
		$role 	= $this->general->generate_encrypt_json($role, array("id_role"));
		if ($array) {
			return $role;
		} else {
			echo json_encode($role);
		}
	}
	private function get_dokumen($array = NULL, $id_dokumen = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $ck_id_dokumen = NULL, $jenis_depo = NULL) {
		$dokumen 		= $this->dmasterdepo->get_data_dokumen("open", $id_dokumen, $active, $deleted, $nama, $ck_id_dokumen, $jenis_depo);
		$dokumen 		= $this->general->generate_encrypt_json($dokumen, array("id_dokumen"));
		if ($array) {
			return $dokumen;
		} else {
			echo json_encode($dokumen);
		}
	}
	private function get_nilai($array = NULL, $id_nilai = NULL, $active = NULL, $deleted = NULL, $keterangan = NULL) {
		$nilai 		= $this->dmasterdepo->get_data_nilai("open", $id_nilai, $active, $deleted, $keterangan);
		$nilai 		= $this->general->generate_encrypt_json($nilai, array("id_nilai"));
		if ($array) {
			return $nilai;
		} else {
			echo json_encode($nilai);
		}
	}
	private function get_matrix_header($array = NULL, $id_matrix_header = NULL, $active = NULL, $deleted = NULL) {
		//header
		$matrix_header = $this->dmasterdepo->get_data_matrix_header("open", $id_matrix_header, $active, $deleted);
		$matrix_header = $this->general->generate_encrypt_json($matrix_header, array("id_matrix_header"));
		//detail
		if (count($matrix_header) != 0){
			$matrix_detail = $this->get_matrix_detail("array", $this->generate->kirana_decrypt($matrix_header[0]->id_matrix_header));		
			$matrix_header[0]->arr_data_detail = $matrix_detail;
		} 
		if ($array) {
			return $matrix_header;
		}
		else {
			echo json_encode($matrix_header);
		}
	}
	
	private function get_matrix_detail($array = NULL, $id_matrix_header = NULL){
		$matrix_detail	= $this->dmasterdepo->get_data_matrix_detail("open", $id_matrix_header);
		$matrix_detail 	= $this->general->generate_encrypt_json($matrix_detail, array("id_matrix_detail"));
		if ($array) {
			return $matrix_detail;
		} else {
			echo json_encode($matrix_detail);
		}
	}
	
	private function get_biaya($array = NULL, $id_biaya = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $ck_jenis_depo = NULL, $ck_jenis_biaya = NULL, $ck_jenis_biaya_detail = NULL, $ck_nama = NULL, $filter_jenis_depo = NULL, $filter_jenis_biaya = NULL) {
		$biaya 		= $this->dmasterdepo->get_data_biaya("open", $id_biaya, $active, $deleted, $nama, $ck_jenis_depo, $ck_jenis_biaya, $ck_jenis_biaya_detail, $ck_nama, $filter_jenis_depo, $filter_jenis_biaya);
		$biaya 		= $this->general->generate_encrypt_json($biaya, array("id_biaya"));
		if ($array) {
			return $biaya;
		} else {
			echo json_encode($biaya);
		}
	}
	private function get_lokasi($array = NULL, $id_lokasi = NULL, $active = NULL, $deleted = NULL) {
		$lokasi 		= $this->dmasterdepo->get_data_lokasi("open", $id_lokasi, $active, $deleted);
		$lokasi 		= $this->general->generate_encrypt_json($lokasi, array("id_lokasi"));
		if ($array) {
			return $lokasi;
		} else {
			echo json_encode($lokasi);
		}
	}
	private function get_gambar($array = NULL, $id_gambar = NULL, $active = NULL, $deleted = NULL) {
		$gambar 		= $this->dmasterdepo->get_data_gambar("open", $id_gambar, $active, $deleted);
		$gambar 		= $this->general->generate_encrypt_json($gambar, array("id_gambar"));
		if ($array) {
			return $gambar;
		} else {
			echo json_encode($gambar);
		}
	}
	private function get_keuangan($array = NULL, $id_keuangan = NULL, $active = NULL, $deleted = NULL) {
		$keuangan 		= $this->dmasterdepo->get_data_keuangan("open", $id_keuangan, $active, $deleted);
		$keuangan 		= $this->general->generate_encrypt_json($keuangan, array("id_keuangan"));
		if ($array) {
			return $keuangan;
		} else {
			echo json_encode($keuangan);
		}
	}
	
	private function get_divisi($array = NULL, $id_divisi = NULL, $active = NULL, $deleted = NULL) {
		$divisi 	= $this->dmasterdepo->get_data_divisi("open", $id_divisi, $active, $deleted);
		// $divisi 	= $this->general->generate_encrypt_json($divisi, array("id_divisi"));
		if ($array) {
			return $divisi;
		} else {
			echo json_encode($divisi);
		}
	}
	
	private function save_matrix() {
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		//======set non aktif matrix header======//
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_matrix_header", $data, array(
			array(
				'kolom' => 'id_matrix',
				'value' => $_POST['id_matrix']
			),
			array(
				'kolom' => 'na',
				'value' => 'n'
			),
			array(
				'kolom' => 'del',
				'value' => 'n'
			)
		));
		//======set non aktif matrix detail======//
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_matrix_detail", $data, array(
			array(
				'kolom' => 'id_matrix_header',
				'value' => $this->generate->kirana_decrypt($_POST['id_matrix_header'])
			),
			array(
				'kolom' => 'na',
				'value' => 'n'
			),
			array(
				'kolom' => 'del',
				'value' => 'n'
			)
		));
		//insert matrix header
		$data_row = array(
			"id_matrix"		=> $_POST['id_matrix'],
			"bobot"			=> $_POST['bobot'],
		);
		$data_row = $this->dgeneral->basic_column("insert", $data_row);
		$this->dgeneral->insert("tbl_depo_matrix_header", $data_row);
		
		//insert matrix detail
		$id_matrix_header = $this->db->insert_id();
		$data_matrix_details = array();
		foreach ($_POST['nilai'] as $index => $nilai) {	
			$data_matrix_detail    = array(
				"id_matrix_header" 	=> $id_matrix_header,
				"param_text" 		=> $this->general->emptyconvert($_POST['param_text'][$index], NULL),
				"param_awal" 		=> (float)str_replace(',','',$this->general->emptyconvert($_POST['param_awal'][$index], NULL)),
				"param_akhir" 		=> (float)str_replace(',','',$this->general->emptyconvert($_POST['param_akhir'][$index], NULL)),
				"nilai" 			=> (float)str_replace(',','',$this->general->emptyconvert($_POST['nilai'][$index], NULL)),
				"na" 				=> 'n',
				"del" 				=> 'n',
			);
			$data_matrix_detail = $this->dgeneral->basic_column('insert', $data_matrix_detail, $datetime);
			$data_matrix_details[] = $data_matrix_detail;
		}
		$this->dgeneral->insert_batch('tbl_depo_matrix_detail', $data_matrix_details);


		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		}
		else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	private function save_nilai() {
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		//======set non aktif data nilai======//
		$data = $this->dgeneral->basic_column('delete', NULL, $datetime);
		$this->dgeneral->update("tbl_depo_nilai", $data, array(
			array(
				'kolom' => 'keterangan',
				'value' => $_POST['keterangan']
			),
			array(
				'kolom' => 'na',
				'value' => 'n'
			),
			array(
				'kolom' => 'del',
				'value' => 'n'
			)
		));
		//insert data nilai
		$data_row = array(
			"nilai_awal"	=> $_POST['nilai_awal'],
			"nilai_akhir"	=> $_POST['nilai_akhir'],
			"keterangan"	=> $_POST['keterangan'],
		);
		$data_row = $this->dgeneral->basic_column("insert", $data_row);
		$this->dgeneral->insert("tbl_depo_nilai", $data_row);
		
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		}
		else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	private function save_role($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_role				 = (isset($post['id_role']) ? $this->generate->kirana_decrypt($post['id_role']) : NULL);
		$nama					 = (isset($post['nama']) ? $post['nama'] : NULL);
		$level					 = (isset($post['level']) ? $post['level'] : NULL);
		$tipe_user				 = (isset($post['tipe_user']) ? $post['tipe_user'] : NULL);
		$is_paralel 			 = (isset($post['is_paralel']) && $post['is_paralel'] == 'on' ? 1 : 0);
		
		$if_approve_pembukaan_tetap	 = (isset($post['if_approve_pembukaan_tetap']) ? $post['if_approve_pembukaan_tetap'] : NULL);
		$if_decline_pembukaan_tetap	 = (isset($post['if_decline_pembukaan_tetap']) ? $post['if_decline_pembukaan_tetap'] : NULL);
		$divisi_pembukaan_tetap	 	 = (($is_paralel == 1)? implode(",", $post['divisi_pembukaan_tetap']) : NULL);
		$if_approve_evaluasi_tetap	 = (isset($post['if_approve_evaluasi_tetap']) ? $post['if_approve_evaluasi_tetap'] : NULL);
		$if_decline_evaluasi_tetap	 = (isset($post['if_decline_evaluasi_tetap']) ? $post['if_decline_evaluasi_tetap'] : NULL);
		$divisi_evaluasi_tetap	 	 = (($is_paralel == 1)? implode(",", $post['divisi_evaluasi_tetap']) : NULL);
		$if_approve_penutupan_tetap	 = (isset($post['if_approve_penutupan_tetap']) ? $post['if_approve_penutupan_tetap'] : NULL);
		$if_decline_penutupan_tetap	 = (isset($post['if_decline_penutupan_tetap']) ? $post['if_decline_penutupan_tetap'] : NULL);
		$divisi_penutupan_tetap	 	 = (($is_paralel == 1)? implode(",", $post['divisi_penutupan_tetap']) : NULL);
		$if_approve_realisasi_tetap	 = (isset($post['if_approve_realisasi_tetap']) ? $post['if_approve_realisasi_tetap'] : NULL);
		$if_decline_realisasi_tetap	 = (isset($post['if_decline_realisasi_tetap']) ? $post['if_decline_realisasi_tetap'] : NULL);
		$divisi_realisasi_tetap	 	 = (($is_paralel == 1)? implode(",", $post['divisi_realisasi_tetap']) : NULL);
		$if_approve_pembukaan_trial	 = (isset($post['if_approve_pembukaan_trial']) ? $post['if_approve_pembukaan_trial'] : NULL);
		$if_decline_pembukaan_trial	 = (isset($post['if_decline_pembukaan_trial']) ? $post['if_decline_pembukaan_trial'] : NULL);
		$divisi_pembukaan_trial	 	 = (($is_paralel == 1)? implode(",", $post['divisi_pembukaan_trial']) : NULL);
		$if_approve_evaluasi_trial	 = (isset($post['if_approve_evaluasi_trial']) ? $post['if_approve_evaluasi_trial'] : NULL);
		$if_decline_evaluasi_trial	 = (isset($post['if_decline_evaluasi_trial']) ? $post['if_decline_evaluasi_trial'] : NULL);
		$divisi_evaluasi_trial	 	 = (($is_paralel == 1)? implode(",", $post['divisi_evaluasi_trial']) : NULL);
		$if_approve_penutupan_trial	 = (isset($post['if_approve_penutupan_trial']) ? $post['if_approve_penutupan_trial'] : NULL);
		$if_decline_penutupan_trial	 = (isset($post['if_decline_penutupan_trial']) ? $post['if_decline_penutupan_trial'] : NULL);
		$divisi_penutupan_trial	 	 = (($is_paralel == 1)? implode(",", $post['divisi_penutupan_trial']) : NULL);
		$if_approve_realisasi_trial	 = (isset($post['if_approve_realisasi_trial']) ? $post['if_approve_realisasi_trial'] : NULL);
		$if_decline_realisasi_trial	 = (isset($post['if_decline_realisasi_trial']) ? $post['if_decline_realisasi_trial'] : NULL);
		$divisi_realisasi_trial	 	 = (($is_paralel == 1)? implode(",", $post['divisi_realisasi_trial']) : NULL);
		
		if ($id_role!=NULL){	
			$ck_nama_role	= $this->dmasterdepo->get_data_role(NULL, NULL, NULL, NULL, NULL, $nama, $id_role);
			if (count($ck_nama_role) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$ck_level_role	= $this->dmasterdepo->get_data_role(NULL, NULL, NULL, NULL, $level, NULL, $id_role);
			if (count($ck_level_role) != 0){ 
				$msg    = "Duplicate Level, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"								=> $nama,
				"level"								=> $level,
				"tipe_user"							=> $tipe_user,
				"is_paralel"						=> $is_paralel,
				"if_approve_pembukaan_tetap"		=> $if_approve_pembukaan_tetap,
				"if_decline_pembukaan_tetap"		=> $if_decline_pembukaan_tetap,
				"divisi_pembukaan_tetap"			=> $divisi_pembukaan_tetap,
				"if_approve_evaluasi_tetap"			=> $if_approve_evaluasi_tetap,
				"if_decline_evaluasi_tetap"			=> $if_decline_evaluasi_tetap,
				"divisi_evaluasi_tetap"				=> $divisi_evaluasi_tetap,
				"if_approve_penutupan_tetap"		=> $if_approve_penutupan_tetap,
				"if_decline_penutupan_tetap"		=> $if_decline_penutupan_tetap,
				"divisi_penutupan_tetap"			=> $divisi_penutupan_tetap,
				"if_approve_realisasi_tetap"		=> $if_approve_realisasi_tetap,
				"if_decline_realisasi_tetap"		=> $if_decline_realisasi_tetap,
				"divisi_realisasi_tetap"			=> $divisi_realisasi_tetap,
				"if_approve_pembukaan_trial"		=> $if_approve_pembukaan_trial,
				"if_decline_pembukaan_trial"		=> $if_decline_pembukaan_trial,
				"divisi_pembukaan_trial"			=> $divisi_pembukaan_trial,
				"if_approve_evaluasi_trial"			=> $if_approve_evaluasi_trial,
				"if_decline_evaluasi_trial"			=> $if_decline_evaluasi_trial,
				"divisi_evaluasi_trial"				=> $divisi_evaluasi_trial,
				"if_approve_penutupan_trial"		=> $if_approve_penutupan_trial,
				"if_decline_penutupan_trial"		=> $if_decline_penutupan_trial,
				"divisi_penutupan_trial"			=> $divisi_penutupan_trial,
				"if_approve_realisasi_trial"		=> $if_approve_realisasi_trial,
				"if_decline_realisasi_trial"		=> $if_decline_realisasi_trial,
				"divisi_realisasi_trial"			=> $divisi_realisasi_trial,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_depo_role", $data_row, array(
				array(
					'kolom' => 'id_role',
					'value' => $id_role
				)
			));
		}else{
			$ck_nama_role	= $this->dmasterdepo->get_data_role(NULL, NULL, NULL, NULL, NULL, $nama);
			if (count($ck_nama_role) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$ck_level_role	= $this->dmasterdepo->get_data_role(NULL, NULL, NULL, NULL, $level, NULL);
			if (count($ck_level_role) != 0){ 
				$msg    = "Duplicate Level, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"								=> $nama,
				"level"								=> $level,
				"tipe_user"							=> $tipe_user,
				"is_paralel"						=> $is_paralel,
				"if_approve_pembukaan_tetap"		=> $if_approve_pembukaan_tetap,
				"if_decline_pembukaan_tetap"		=> $if_decline_pembukaan_tetap,
				"divisi_pembukaan_tetap"			=> $divisi_pembukaan_tetap,
				"if_approve_evaluasi_tetap"			=> $if_approve_evaluasi_tetap,
				"if_decline_evaluasi_tetap"			=> $if_decline_evaluasi_tetap,
				"divisi_evaluasi_tetap"				=> $divisi_evaluasi_tetap,
				"if_approve_penutupan_tetap"		=> $if_approve_penutupan_tetap,
				"if_decline_penutupan_tetap"		=> $if_decline_penutupan_tetap,
				"divisi_penutupan_tetap"			=> $divisi_penutupan_tetap,
				"if_approve_realisasi_tetap"		=> $if_approve_realisasi_tetap,
				"if_decline_realisasi_tetap"		=> $if_decline_realisasi_tetap,
				"divisi_realisasi_tetap"			=> $divisi_realisasi_tetap,
				"if_approve_pembukaan_trial"		=> $if_approve_pembukaan_trial,
				"if_decline_pembukaan_trial"		=> $if_decline_pembukaan_trial,
				"divisi_pembukaan_trial"			=> $divisi_pembukaan_trial,
				"if_approve_evaluasi_trial"			=> $if_approve_evaluasi_trial,
				"if_decline_evaluasi_trial"			=> $if_decline_evaluasi_trial,
				"divisi_evaluasi_trial"				=> $divisi_evaluasi_trial,
				"if_approve_penutupan_trial"		=> $if_approve_penutupan_trial,
				"if_decline_penutupan_trial"		=> $if_decline_penutupan_trial,
				"divisi_penutupan_trial"			=> $divisi_penutupan_trial,
				"if_approve_realisasi_trial"		=> $if_approve_realisasi_trial,
				"if_decline_realisasi_trial"		=> $if_decline_realisasi_trial,
				"divisi_realisasi_trial"			=> $divisi_realisasi_trial,
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_depo_role", $data_row);
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}	
	
	private function save_dokumen($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_dokumen	= (isset($post['id_dokumen']) ? $this->generate->kirana_decrypt($post['id_dokumen']) : NULL);
		$jenis_depo	= (isset($post['jenis_depo']) ? $post['jenis_depo'] : NULL);
		$nama 		= (isset($post['nama']) ? $post['nama'] : NULL);
		$mandatory	= (isset($post['mandatory']) ? $post['mandatory'] : NULL);
		
		if ($id_dokumen!=NULL){	
			$ck_nama_dokumen	= $this->dmasterdepo->get_data_dokumen(NULL, NULL, NULL, NULL, $nama, $id_dokumen);
			if (count($ck_nama_dokumen) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"jenis_depo"	=> $jenis_depo,
				"nama"				=> $nama,
				"mandatory"			=> $mandatory,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_depo_dokumen", $data_row, array(
				array(
					'kolom' => 'id_dokumen',
					'value' => $id_dokumen
				)
			));
		}else{
			$ck_nama_dokumen	= $this->dmasterdepo->get_data_dokumen(NULL, NULL, NULL, NULL, $nama);
			if (count($ck_nama_dokumen) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"jenis_depo"	=> $jenis_depo,
				"nama"				=> $nama,
				"mandatory"			=> $mandatory,
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_depo_dokumen", $data_row);
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}	
	private function save_biaya($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_biaya	 = (isset($post['id_biaya']) ? $this->generate->kirana_decrypt($post['id_biaya']) : NULL);
		$jenis_depo	 = (isset($post['jenis_depo']) ? $post['jenis_depo'] : NULL);
		$jenis_biaya = (isset($post['jenis_biaya']) ? $post['jenis_biaya'] : NULL);
		$jenis_biaya_detail = (isset($post['jenis_biaya_detail']) ? $post['jenis_biaya_detail'] : NULL);
		$nama 		 = (isset($post['nama']) ? $post['nama'] : NULL);
		$satuan		 = (isset($post['satuan']) ? $post['satuan'] : NULL);
		
		if ($id_biaya!=NULL){	
			$ck_nama_biaya	= $this->dmasterdepo->get_data_biaya(NULL, NULL, NULL, NULL, $nama, $jenis_depo, $jenis_biaya, $jenis_biaya_detail, $nama, NULL, NULL, $id_biaya);
			if (count($ck_nama_biaya) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"jenis_depo"	=> $jenis_depo,
				"jenis_biaya"	=> $jenis_biaya,
				"jenis_biaya_detail"	=> $jenis_biaya_detail,
				"nama"			=> $nama,
				"satuan"		=> $satuan,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_depo_biaya", $data_row, array(
				array(
					'kolom' => 'id_biaya',
					'value' => $id_biaya
				)
			));
		}else{
			$ck_nama_biaya	= $this->dmasterdepo->get_data_biaya(NULL, NULL, NULL, NULL, $nama, $jenis_depo, $jenis_biaya, $jenis_biaya_detail, $nama);
			if (count($ck_nama_biaya) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"jenis_depo"	=> $jenis_depo,
				"jenis_biaya"	=> $jenis_biaya,
				"jenis_biaya_detail"	=> $jenis_biaya_detail,
				"nama"			=> $nama,
				"satuan"		=> $satuan,
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_depo_biaya", $data_row);
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}	
	
	private function save_lokasi($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_lokasi	 = (isset($post['id_lokasi']) ? $this->generate->kirana_decrypt($post['id_lokasi']) : NULL);
		$nama 		 = (isset($post['nama']) ? $post['nama'] : NULL);
		
		if ($id_lokasi!=NULL){	
			$ck_nama_lokasi	= $this->dmasterdepo->get_data_lokasi(NULL, NULL, NULL, NULL, $nama, $id_lokasi);
			if (count($ck_nama_lokasi) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"		=> $nama,
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_depo_lokasi", $data_row, array(
				array(
					'kolom' => 'id_lokasi',
					'value' => $id_lokasi
				)
			));
		}else{
			$ck_nama_lokasi	= $this->dmasterdepo->get_data_lokasi(NULL, NULL, NULL, NULL, $nama);
			if (count($ck_nama_lokasi) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"nama"		=> $nama,
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_depo_lokasi", $data_row);
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}	
	
	/*====================================================================*/
		
}