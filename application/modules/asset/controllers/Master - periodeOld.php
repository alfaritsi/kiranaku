<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Equipment Management
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
	    $this->load->model('dmasterasset');
	}

	public function index(){
		show_404();
	}
	public function lokasi(){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	 = "Master Lokasi";
		$data['title_form']  = "Masukan Master Lokasi";
		$data['lokasi']  	 = $this->get_lokasi('array');
		$data['sub_lokasi']	 = $this->get_sub_lokasi('array');
		$data['area']		 = $this->get_area('array');
		$this->load->view("master/lokasi", $data);
	}
	

	//=================== MASTER ICT ====================//
	public function kategori($param=NULL){
		$this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

		$data['kategori']	= $this->get_data_kategori('all', null, null, $param);
		$data['jenis']		= $this->get_data_jenis('all', null, null, $param);
		$data['pengguna']	= $param;

		if ($param == 'IT' || $param == 'hrga' || $param == 'fo' ) {
			$this->load->view("master/ict/kategori", $data);
		}else{
			show_404();
		}

		// switch ($param) {
		// 	case 'IT':
		// 	case 'fo':
		// 		$this->load->view("master/ict/kategori", $data);
		// 		break;
			
		// 	default:
		// 		$this->load->view("master/hrga/jenis", $data);
		// 		break;
		// }		


	}

	public function komponen($id_jenis=NULL){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

        if(!isset($id_jenis)){
            show_404();
        }

		$data['id_jenis'] 	    = $id_jenis;
		$data['id_kategori'] 	= $this->get_data_jenis('all', $id_jenis, 'id_kategori');
		$data['judul'] 	        = $this->get_data_jenis('all', $id_jenis, 'nama');
		$data['kategori'] 	    = $this->get_data_kategori('all', $data['id_kategori'], 'nama');
		$data['komponen']		= $this->get_data_jenis_komponen('all', $id_jenis);


		$this->load->view("master/fo/komponen", $data);
	}


	public function merk($param=NULL){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

		$data['merk'] 		= $this->get_data_merk('all', null, null, $param);
		$data['jenis']		= $this->get_data_jenis('all', null, null, $param);
		// $data['test']		= $this->general->generate_max_number('tbl_inv_jenis', 'tbl_inv_jenis.kode', '3');
		if ($param == 'IT' || $param == 'hrga' || $param == 'fo' ) {
			$this->load->view("master/ict/merk", $data);
		}else{
			show_404();
		}

	}

	public function tipe_merk($id_merk=NULL){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

        if(!isset($id_merk)){
            show_404();
        }

		$data['id_merk'] 	    = $id_merk;
		$data['judul'] 	        = $this->get_data_merk('all', $id_merk, 'nama');
		$data['tipe_merk']		= $this->get_data_tipe_merk('all', $id_merk);


		$this->load->view("master/ict/tipe", $data);
	}


	public function jenis_instansi(){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

		$data['jenis_instansi'] 		= $this->get_data_jenis_instansi('all');
		$this->load->view("master/hrga/jenis", $data);
		
	}

	public function instansi($id_jenis_instansi=NULL){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

        if(!isset($id_jenis_instansi)){
            show_404();
        }

		$data['id_jenis_instansi'] 	= $id_jenis_instansi;
		$data['judul'] 	        	= $this->get_data_jenis_instansi('all', $id_jenis_instansi, 'nama');
		$data['instansi']			= $this->get_data_instansi('all', $id_jenis_instansi);


		$this->load->view("master/hrga/instansi", $data);
	}


	public function dokumen(){
		$this->general->check_session();
		$data['generate']        = $this->generate; 
		$data['module']          = $this->router->fetch_module();
		$data['user']            = $this->general->get_data_user();
		
		$data['jenis_kendaraan'] = $this->get_data_jenis('all', null, null, 'hrga');
		$data['jenis_instansi']  = $this->get_data_jenis_instansi('all');
		$data['dokumen']         = $this->get_data_dokumen('all');


		$this->load->view("master/hrga/dokumen", $data);
	}


	public function pabrik(){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

		$data['pabrik']		= $this->get_data_pabrik();

		$this->load->view("master/fo/pabrik", $data);
	}


	public function keterangan(){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

		$data['kegiatan']	= $this->get_data_kegiatan();
		$data['service']	= $this->get_data_service();
		$data['satuan']		= $this->get_data_satuan();

		$this->load->view("master/fo/keterangan", $data);
	}


	public function biaya(){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

		$data['biaya']		= $this->get_data_biaya();

		$this->load->view("master/hrga/biaya", $data);
	}

	public function periode(){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

		$data['jenis'] 		= $this->get_data_jenis('all', null, null, 'fo');
		$data['service']	= $this->get_data_service();
		$data['periode']	= $this->get_data_periode('all');

		// $data['nol'] 	 = $this->generate->kirana_encrypt('0');

		$this->load->view("master/fo/periode", $data);
	}

	public function periode_detail($id_periode=NULL){
		$this->general->check_session();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();

        if(!isset($id_periode)){
            show_404();
        }

		$data['id_jenis_instansi'] 	= $id_periode;
		$data['judul'] 	        	= $this->get_data_jenis_instansi('all', $id_jenis_instansi, 'nama');
		$data['instansi']			= $this->get_data_instansi('all', $id_jenis_instansi);


		$this->load->view("master/hrga/instansi", $data);
	}

		//=================================//
		//		  PRIVATE FUNCTION 		   //
		//=================================//

		private function get_data_kategori($array=NULL, $id_kategori=NULL, $wanted_data=NULL, $pengguna=NULL){
			$kategori = $this->dmasterasset->get_kategori('open', $id_kategori, $pengguna);
			$kategori = $this->general->generate_encrypt_json($kategori, array("id_kategori"));
			if($wanted_data == 'nama'){
				foreach ($kategori as $k) {
					$kategori = $k->nama;
				}
				return $kategori;
			}else{
				if ($array) {
					return $kategori;
				} else {
					echo json_encode($kategori);
				}
			}
		}

		private function get_data_jenis($array=NULL, $id_jenis=NULL, $wanted_data=NULL, $pengguna=NULL){
			if ($id_jenis != null) {
        		$id_jenis 			= $this->generate->kirana_decrypt($id_jenis);	
			}

			$jenis = $this->dmasterasset->get_jenis('open', $id_jenis, $pengguna);
			$jenis = $this->general->generate_encrypt_json($jenis, array("id_jenis"));
			
			if($wanted_data == 'nama'){
				foreach ($jenis as $j) {
					$jenis = $j->nama;
				}
				return $jenis;
			}else if($wanted_data == 'id_kategori'){
				foreach ($jenis as $j) {
					$jenis = $j->id_kategori;
				}
				return $jenis;
			}else{
				if ($array) {
					return $jenis;
				} else {
					echo json_encode($jenis);
				}			
			}

		}

		private function get_data_jenis_komponen($array=NULL, $id_jenis=NULL, $id_jenis_komponen=NULL){
			if ($id_jenis != null) {
        		$id_jenis 			= $this->generate->kirana_decrypt($id_jenis);	
			}
			if ($id_jenis_komponen != null) {
        		$id_jenis_komponen 	= $this->generate->kirana_decrypt($id_jenis_komponen);	
			}
			$jenis_komponen = $this->dmasterasset->get_jenis_detail('open', $id_jenis, $id_jenis_komponen);
			$jenis_komponen = $this->general->generate_encrypt_json($jenis_komponen, array("id_jenis_detail"));
			if ($array) {
				return $jenis_komponen;
			} else {
				echo json_encode($jenis_komponen);
			}
		}

		private function get_data_merk($array=NULL, $id_merk=NULL, $wanted_data=NULL, $pengguna=NULL){
			if ($id_merk != null) {
        		$id_merk 			= $this->generate->kirana_decrypt($id_merk);	
			}

			$merk = $this->dmasterasset->get_merk('open', $id_merk, $pengguna);
			$merk = $this->general->generate_encrypt_json($merk, array("id_merk"));
			if($wanted_data == 'nama'){
				foreach ($merk as $m) {
					$merk = $m->nama;
				}
				return $merk;
			}else{
				if ($array) {
					return $merk;
				} else {
					echo json_encode($merk);
				}				
			}
		}

		private function get_data_tipe_merk($array=NULL, $id_merk=NULL, $id_merk_tipe=NULL){
			if ($id_merk != null) {
        		$id_merk 			= $this->generate->kirana_decrypt($id_merk);	
			}
			if ($id_merk_tipe != null) {
        		$id_merk_tipe 			= $this->generate->kirana_decrypt($id_merk_tipe);	
			}
			$tipe_merk = $this->dmasterasset->get_tipe_merk('open', $id_merk, $id_merk_tipe);
			$tipe_merk = $this->general->generate_encrypt_json($tipe_merk, array("id_merk_tipe"));
			if ($array) {
				return $tipe_merk;
			} else {
				echo json_encode($tipe_merk);
			}
		}

		private function get_data_jenis_instansi($array=NULL, $id_jenis_instansi=NULL, $wanted_data=NULL){
			if ($id_jenis_instansi != null) {
        		$id_jenis_instansi 			= $this->generate->kirana_decrypt($id_jenis_instansi);	
			}

			$jenis_instansi = $this->dmasterasset->get_jenis_instansi('open', $id_jenis_instansi);
			$jenis_instansi = $this->general->generate_encrypt_json($jenis_instansi, array("id_jenis_instansi"));
			if($wanted_data == 'nama'){
				foreach ($jenis_instansi as $j) {
					$jenis_instansi = $j->nama;
				}
				return $jenis_instansi;
			}else{
				if ($array) {
					return $jenis_instansi;
				} else {
					echo json_encode($jenis_instansi);
				}				
			}
		}

		private function get_data_instansi($array=NULL, $id_jenis_instansi=NULL, $id_instansi=NULL){
			if ($id_jenis_instansi != null) {
        		$id_jenis_instansi 			= $this->generate->kirana_decrypt($id_jenis_instansi);	
			}
			if ($id_instansi != null) {
        		$id_instansi 			= $this->generate->kirana_decrypt($id_instansi);	
			}
			$instansi = $this->dmasterasset->get_instansi('open', $id_jenis_instansi, $id_instansi);
			$instansi = $this->general->generate_encrypt_json($instansi, array("id_instansi", "id_jenis_instansi"));
			if ($array) {
				return $instansi;
			} else {
				echo json_encode($instansi);
			}
		}

		private function get_data_dokumen($array=NULL, $id_inv_doc=NULL){
			if ($id_inv_doc != null) {
        		$id_inv_doc 			= $this->generate->kirana_decrypt($id_inv_doc);	
			}
			$dokumen = $this->dmasterasset->get_dokumen('open', $id_inv_doc);
			$dokumen = $this->general->generate_encrypt_json($dokumen, array("id_inv_doc"));

			foreach ($dokumen as $dt) {
				// uban id jenis instansi menjadi nama
				if ($dt->jenis_instansi != null) {
					$id_ke_nama = $this->dmasterasset->get_jenis_instansi('open', $dt->id_jenis_instansi);
					foreach ($id_ke_nama as $ubah) {
						$dt->jenis_instansi = $ubah->nama;
					}
				}else{
					$dt->jenis_instansi = null;
				}
				
				// Ubah id jenis kendaraan menjadi nama
				if ($dt->jenis != null) {
						# code...
					$arr_jenis = explode('.', substr($dt->jenis, 1, -1));
					$nama_jenis = array();
					foreach ($arr_jenis as $jen){
						// $cari = $this->get_data_jenis('all', $jen, 'nama');
						$id_to_nama = $this->dmasterasset->get_jenis('open', $jen);
						foreach ($id_to_nama as $src) {
							$nama_jenis[] = $src->nama;
						}
					}
					$dt->jenis_kendaraan = implode(" / ", $nama_jenis);
				}else{
					$dt->jenis_kendaraan = null;
				}
			}

			if ($array) {
				return $dokumen;
			} else {
				echo json_encode($dokumen);
			}
		}

		private function get_data_periode($array=NULL, $id_periode=NULL){
			$periode = $this->dmasterasset->get_periode('open', $id_periode);
			$periode = $this->general->generate_encrypt_json($periode, array("id_periode", "id_jenis"));
			
			foreach ($periode as $dt) {
				// uban id jenis instansi menjadi nama
				if ($dt->id_jenis != null) {
					$id_ke_nama = $this->dmasterasset->get_jenis('open', $this->generate->kirana_decrypt($dt->id_jenis));
					foreach ($id_ke_nama as $ubah) {
						$dt->jenis = $ubah->nama;
					}
				}else{
					$dt->jenis = null;
				}
				
				if ($dt->id_service != null) {
					$id_ke_namas = $this->dmasterasset->get_service('open', $dt->id_service);
					foreach ($id_ke_namas as $ubahs) {
						$dt->service = $ubahs->nama;
					}
				}else{
					$dt->service = null;
				}
				
				
			}


			if ($array) {
				return $periode;
			} else {
				echo json_encode($periode);
			}
			
		}

		private function get_data_biaya(){
			$biaya = $this->dmasterasset->get_biaya('open');
			$biaya = $this->general->generate_encrypt_json($biaya, array("id_inv_biaya"));
			return $biaya;
		}

		private function get_data_pabrik(){
			$pabrik = $this->dmasterasset->get_pabrik('open');
			return $pabrik;
		}

		private function get_data_kegiatan(){
			$kegiatan = $this->dmasterasset->get_kegiatan('open');
			$kegiatan = $this->general->generate_encrypt_json($kegiatan, array("id_kegiatan"));
			return $kegiatan;
		}

		private function get_data_service(){
			$service  = $this->dmasterasset->get_service('open');
			$service  = $this->general->generate_encrypt_json($service, array("id_service"));
			return $service;
		}

		private function get_data_satuan(){
			$satuan   = $this->dmasterasset->get_satuan('open');
			$satuan   = $this->general->generate_encrypt_json($satuan, array("id_satuan"));
			return $satuan;
		}


		//=================================//
		//		  PROCESS FUNCTION 		   //
		//=================================//
		public function get($param = NULL) {
			switch ($param) {
				case 'lokasi':
					$id_lokasi = (isset($_POST['id_lokasi']) ? $this->generate->kirana_decrypt($_POST['id_lokasi']) : NULL);
					$active    = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted   = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_lokasi(NULL, $id_lokasi, $active, $deleted);
					break;
				case 'sub_lokasi':
					$id_sub_lokasi = (isset($_POST['id_sub_lokasi']) ? $this->generate->kirana_decrypt($_POST['id_sub_lokasi']) : NULL);
					$active    = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted   = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_sub_lokasi(NULL, $id_sub_lokasi, $active, $deleted);
					break;
				case 'area':
					$id_area   = (isset($_POST['id_area']) ? $this->generate->kirana_decrypt($_POST['id_area']) : NULL);
					$active    = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted   = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$this->get_area(NULL, $id_area, $active, $deleted);
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		public function set($param = NULL) {
			$action = NULL;
			if (isset($_POST['type']) && $_POST['type'] == "non_active") {
				$action = "delete_na";
			} else if (isset($_POST['type']) && $_POST['type'] == "set_active") {
				$action = "activate_na";
			} else if (isset($_POST['type']) && $_POST['type'] == "delete") {
				$action = "delete_na_del";
			}

			if ($action) {
				switch ($param) {
					case 'lokasi':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_inv_lokasi", array(
							array(
								'kolom' => 'id_lokasi',
								'value' => $this->generate->kirana_decrypt($_POST['id_lokasi'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'sub_lokasi':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_inv_sub_lokasi", array(
							array(
								'kolom' => 'id_sub_lokasi',
								'value' => $this->generate->kirana_decrypt($_POST['id_sub_lokasi'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'area':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_inv_area", array(
							array(
								'kolom' => 'id_area',
								'value' => $this->generate->kirana_decrypt($_POST['id_area'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'kategori':
						$this->general->connectDbPortal();
						$id_kategori = $this->generate->kirana_decrypt($_POST['id_kategori']);
						$return = $this->general->set($action, "tbl_inv_kategori", array(
							array(
								'kolom' => 'id_kategori',
								'value' => $id_kategori
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'jenis_asset':
						$this->general->connectDbPortal();
						$id_jenis = $this->generate->kirana_decrypt($_POST['id_jenis']);
						$return = $this->general->set($action, "tbl_inv_jenis", array(
							array(
								'kolom' => 'id_jenis',
								'value' => $id_jenis
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'merk':
						$this->general->connectDbPortal();
						$id_merk = $this->generate->kirana_decrypt($_POST['id_merk']);
						$return = $this->general->set($action, "tbl_inv_merk", array(
							array(
								'kolom' => 'id_merk',
								'value' => $id_merk
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'merk_tipe':
						$this->general->connectDbPortal();
						$id_merk_tipe = $this->generate->kirana_decrypt($_POST['id_merk_tipe']);
						$return = $this->general->set($action, "tbl_inv_merk_tipe", array(
							array(
								'kolom' => 'id_merk_tipe',
								'value' => $id_merk_tipe
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'jenis_instansi':
						$this->general->connectDbPortal();
						$id_jenis_instansi = $this->generate->kirana_decrypt($_POST['id_jenis_instansi']);
						$return = $this->general->set($action, "tbl_inv_jenis_instansi", array(
							array(
								'kolom' => 'id_jenis_instansi',
								'value' => $id_jenis_instansi
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'instansi':
						$this->general->connectDbPortal();
						$id_instansi = $this->generate->kirana_decrypt($_POST['id_instansi']);
						$return = $this->general->set($action, "tbl_inv_instansi", array(
							array(
								'kolom' => 'id_instansi',
								'value' => $id_instansi
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;

					case 'dokumen':
						$this->general->connectDbPortal();
						$id_inv_doc = $this->generate->kirana_decrypt($_POST['id_inv_doc']);
						$return = $this->general->set($action, "tbl_inv_doc", array(
							array(
								'kolom' => 'id_inv_doc',
								'value' => $id_inv_doc
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;

					case 'kegiatan':
						$this->general->connectDbPortal();
						$id_kegiatan = $this->generate->kirana_decrypt($_POST['id_kegiatan']);
						$return = $this->general->set($action, "tbl_inv_kegiatan", array(
							array(
								'kolom' => 'id_kegiatan',
								'value' => $id_kegiatan
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;

					case 'service':
						$this->general->connectDbPortal();
						$id_service = $this->generate->kirana_decrypt($_POST['id_service']);
						$return = $this->general->set($action, "tbl_inv_service", array(
							array(
								'kolom' => 'id_service',
								'value' => $id_service
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;

					case 'satuan':
						$this->general->connectDbPortal();
						$id_satuan = $this->generate->kirana_decrypt($_POST['id_satuan']);
						$return = $this->general->set($action, "tbl_inv_satuan", array(
							array(
								'kolom' => 'id_satuan',
								'value' => $id_satuan
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'biaya':
						$this->general->connectDbPortal();
						$id_inv_biaya = $this->generate->kirana_decrypt($_POST['id_inv_biaya']);
						$return = $this->general->set($action, "tbl_inv_biaya", array(
							array(
								'kolom' => 'id_inv_biaya',
								'value' => $id_inv_biaya
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'periode':
						$this->general->connectDbPortal();
						$id_periode = $this->generate->kirana_decrypt($_POST['id_periode']);
						$return = $this->general->set($action, "tbl_inv_periode", array(
							array(
								'kolom' => 'id_periode',
								'value' => $id_periode
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
				case 'lokasi':
					$this->save_lokasi();
					break;
				case 'sub_lokasi':
					$this->save_sub_lokasi();
					break;
				case 'area':
					$this->save_area();
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
		private function get_lokasi($array = NULL, $id_lokasi = NULL, $active = NULL, $deleted = NULL, $nama = NULL) {
			$lokasi = $this->dmasterasset->get_master_lokasi("open", $id_lokasi, $active, $deleted, $nama);
			$lokasi = $this->general->generate_encrypt_json($lokasi, array("id_lokasi"));
			if ($array) {
				return $lokasi;
			} else {
				echo json_encode($lokasi);
			}
		}

		private function save_lokasi() {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if (isset($_POST['id_lokasi']) && trim($_POST['id_lokasi']) !== "") {
				
				$lokasi = $this->get_lokasi('array', NULL, NULL, 'n', $_POST['nama']);
				if ((count($lokasi) > 0)and($lokasi[0]->id_lokasi!=$_POST['id_lokasi'])) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				$data_row = array(
					"kode"           => $_POST['kode'],
					"nama"           => $_POST['nama'],
					"pengguna"       => implode(".", $_POST['pengguna']),
					"keterangan"     => $_POST['keterangan']
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_inv_lokasi", $data_row, array(
					array(
						'kolom' => 'id_lokasi',
						'value' => $this->generate->kirana_decrypt($_POST['id_lokasi'])
					)
				));

			} else {
				$lokasi = $this->get_lokasi('array', NULL, NULL, 'n', $_POST['nama']);
				if (count($lokasi) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"kode"           => $_POST['kode'],
					"nama"           => $_POST['nama'],
					"pengguna"       => implode(".", $_POST['pengguna']),
					"keterangan"     => $_POST['keterangan'],
					"login_edit"     => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"   => $datetime
				);

				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_inv_lokasi", $data_row);
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
		
		private function get_sub_lokasi($array = NULL, $id_sub_lokasi = NULL, $active = NULL, $deleted = NULL, $nama = NULL) {
			$sub_lokasi = $this->dmasterasset->get_master_sub_lokasi("open", $id_sub_lokasi, $active, $deleted, $nama);
			$sub_lokasi = $this->general->generate_encrypt_json($sub_lokasi, array("id_sub_lokasi"));
			if ($array) {
				return $sub_lokasi;
			} else {
				echo json_encode($sub_lokasi);
			}
		}

		private function save_sub_lokasi() {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if (isset($_POST['id_sub_lokasi']) && trim($_POST['id_sub_lokasi']) !== "") {
				
				$sub_lokasi = $this->get_sub_lokasi('array', NULL, NULL, 'n', $_POST['nama']);
				if ((count($sub_lokasi) > 0)and($sub_lokasi[0]->id_sub_lokasi!=$_POST['id_sub_lokasi'])) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				$data_row = array(
					"id_lokasi"      => $_POST['id_lokasi'],
					"kode"           => $_POST['kode'],
					"nama"           => $_POST['nama'],
					"keterangan"     => $_POST['keterangan']
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_inv_sub_lokasixx", $data_row, array(
					array(
						'kolom' => 'id_sub_lokasi',
						'value' => $this->generate->kirana_decrypt($_POST['id_sub_lokasi'])
					)
				));

			} else {
				$sub_lokasi = $this->get_sub_lokasi('array', NULL, NULL, 'n', $_POST['nama']);
				if (count($sub_lokasi) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"id_lokasi"      => $_POST['id_lokasi'],
					"kode"           => $_POST['kode'],
					"nama"           => $_POST['nama'],
					"keterangan"     => $_POST['keterangan'],
					"login_edit"     => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"   => $datetime
				);

				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_inv_sub_lokasixx", $data_row);
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

		private function get_area($array = NULL, $id_area = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $id_sub_lokasi = NULL) {
			$area = $this->dmasterasset->get_master_area("open", $id_area, $active, $deleted, $nama, $id_sub_lokasi);
			$area = $this->general->generate_encrypt_json($area, array("id_area"));
			if ($array) {
				return $area;
			} else {
				echo json_encode($area);
			}
		}

		private function save_area() {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			if (isset($_POST['id_area']) && trim($_POST['id_area']) !== "") {
				
				$area = $this->get_area('array', NULL, NULL, 'n', $_POST['nama'], $_POST['id_sub_lokasi']);
				if ((count($area) > 0)and($area[0]->id_area!=$_POST['id_area'])) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				
				$data_row = array(
					"id_sub_lokasi"  => $_POST['id_sub_lokasi'],
					"kode"           => $_POST['kode'],
					"nama"           => $_POST['nama'],
					"keterangan"     => $_POST['keterangan']
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_inv_area", $data_row, array(
					array(
						'kolom' => 'id_area',
						'value' => $this->generate->kirana_decrypt($_POST['id_area'])
					)
				));

			} else {
				$area = $this->get_area('array', NULL, NULL, 'n', $_POST['nama'], $_POST['id_sub_lokasi']);
				if (count($area) > 0) {
					$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$data_row = array(
					"id_sub_lokasi"  => $_POST['id_sub_lokasi'],
					"kode"           => $_POST['kode'],
					"nama"           => $_POST['nama'],
					"keterangan"     => $_POST['keterangan'],
					"login_edit"     => base64_decode($this->session->userdata("-id_user-")),
					"tanggal_edit"   => $datetime
				);

				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_inv_area", $data_row);
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


		//=================== MASTER LOKASI END ================================//

		//=================== MASTER KATEGORI & JENIS ASSET ===================//

		public function get_kategori(){
			$id_kategori 	= $this->generate->kirana_decrypt($_POST['id_kategori']);
			$kategori  		= $this->dmasterasset->get_kategori('open', $id_kategori);
			$kategori    	= $this->general->generate_encrypt_json($kategori, array("id_kategori"));
			echo json_encode($kategori);
		}

		public function save_kategori(){
			$this->general->connectDbPortal();
			$id     		 = $this->generate->kirana_decrypt($_POST['id_kategori']);
			$datetime   	 = date("Y-m-d H:i:s");
			$kode 			 = $this->general->generate_max_number('tbl_inv_kategori', 'tbl_inv_kategori.kode', '3');
	
		    if($id == null){
				$kategori 		 = $this->dmasterasset->get_kategori(null, null, $_POST['pengguna'], $_POST['kategori']);
				if (count($kategori) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['kategori'],
			                          'keterangan'      => $_POST['ket_kategori'],
			                          'pengguna'      	=> $_POST['pengguna'],
			                          'kode'	      	=> $kode,
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_kategori", $data_row);
			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		    	}else{
		    		$msg    = "Kategori ini sudah ada pada database. Silahkan input kategori lain.";
	        		$sts    = "NotOK";
		    	}
		    }else{
		    	$kategori 		 = $this->dmasterasset->get_kategori(null, null, $_POST['pengguna'], $_POST['kategori'], $id);
				if (count($kategori) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['kategori'],
			                          'keterangan'     	=> $_POST['ket_kategori'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_kategori", $data_row, array( 
		                                                                    array(
		                                                                        'kolom'=>'id_kategori',
		                                                                        'value'=>$id
		                                                                    )
		                                                                ));
		        	if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }
		        }else{
		    		$msg    = "Kategori ini sudah ada pada database. Silahkan input kategori lain.";
	        		$sts    = "NotOK";
		    	}
		    }				        

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}

		public function get_jenis(){
			$id_jenis 	= $this->generate->kirana_decrypt($_POST['id_jenis']);
			$jenis  	= $this->dmasterasset->get_jenis('open', $id_jenis);
			$jenis    	= $this->general->generate_encrypt_json($jenis, array("id_jenis","id_kategori"));
			echo json_encode($jenis);
		}

		public function save_jenis(){
			$this->general->connectDbPortal();

			$id     		 = $this->generate->kirana_decrypt($_POST['id_jenis']);
			$id_kategori 	 = $this->generate->kirana_decrypt($_POST['kategori_jen']);
			$datetime   	 = date("Y-m-d H:i:s");
			$kode 			 = $this->general->generate_max_number('tbl_inv_jenis', 'tbl_inv_jenis.kode', '3');

			if($id == null){
				$jenis 		 	 = $this->dmasterasset->get_jenis(null, null, $_POST['pengguna'], $_POST['input_jenis'], $id_kategori);
				if (count($jenis) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['input_jenis'],
			                          'keterangan'      => $_POST['ket_jenis'],
			                          'id_kategori'     => $id_kategori,
			                          'pengguna'      	=> $_POST['pengguna'],
			                          'kode'	      	=> $kode,
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_jenis", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

			    }else{
					$msg    = "Jenis ini sudah ada pada database. Silahkan input jenis lain.";
			        $sts    = "NotOK";
				}	

			}else{
				$jenis 		 	 = $this->dmasterasset->get_jenis(null, null, $_POST['pengguna'], $_POST['input_jenis'], $id_kategori, $id);
				if (count($jenis) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['input_jenis'],
			                          'id_kategori'    	=> $id_kategori,
			                          'keterangan'     	=> $_POST['ket_jenis'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_jenis", $data_row, array( 
		                                                                    array(
		                                                                        'kolom'=>'id_jenis',
		                                                                        'value'=>$id
		                                                                    )
		                                                                ));

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

			    }else{
					$msg    = "Jenis ini sudah ada pada database. Silahkan input jenis lain.";
			        $sts    = "NotOK";
				}
			}
			
			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}

		public function get_jenis_detail(){
			//KOMPONEN
			$id_jenis_detail 	= $this->generate->kirana_decrypt($_POST['id_jenis_detail']);
			$jenis  			= $this->dmasterasset->get_jenis_detail('open', null, $id_jenis_detail);
			$jenis    			= $this->general->generate_encrypt_json($jenis, array("id_jenis_detail"));
			echo json_encode($jenis);
		}

		public function save_jenis_detail(){
			$this->general->connectDbPortal();

			$id     		 = $this->generate->kirana_decrypt($_POST['id_jenis_detail']);
			$id_jenis 		 = $this->generate->kirana_decrypt($_POST['id_jenis']);
			$datetime   	 = date("Y-m-d H:i:s");
			$kode 			 = $this->general->generate_max_number('tbl_inv_jenis_detail', 'tbl_inv_jenis_detail.kode', '3');

		    if($id == null){
				$jenis 		 	 = $this->dmasterasset->get_jenis_detail(null, $id_jenis, null, $_POST['jenis_detail']);
				if (count($jenis) == 0){
		    		//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['jenis_detail'],
			                          'keterangan'      => $_POST['ket_komponen'],
			                          'id_jenis'	    => $id_jenis,
			                          'kode'	      	=> $kode,
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_jenis_detail", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		        }else{
					$msg    = "Jenis ini sudah ada pada database. Silahkan input jenis lain.";
			        $sts    = "NotOK";
				}	

		    }else{
		    	$jenis 		 	 = $this->dmasterasset->get_jenis_detail(null, $id_jenis, null, $_POST['jenis_detail'], $id);
				if (count($jenis) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['jenis_detail'],
			                          'id_jenis'    	=> $id_jenis,
			                          'keterangan'     	=> $_POST['ket_komponen'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_jenis_detail", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_jenis_detail',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));

			         if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		        }else{
					$msg    = "Jenis ini sudah ada pada database. Silahkan input jenis lain.";
			        $sts    = "NotOK";
				}	
		    }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}

		//=================== MASTER KATEGORI & JENIS ASSET END ===================//

		//=================== MASTER MERK & TIPE MERK =======================//

		public function get_merk(){
			$id_merk = $this->generate->kirana_decrypt($_POST['id_merk']);
			$merk    = $this->dmasterasset->get_merk('open', $id_merk);
			$merk    = $this->general->generate_encrypt_json($merk, array("id_merk", "id_jenis"));
			echo json_encode($merk);
		}

		public function save_merk(){

			$this->general->connectDbPortal();
			$id     		 = $this->generate->kirana_decrypt($_POST['id_merk']);
			$id_jenis 		 = $this->generate->kirana_decrypt($_POST['jenis_asset']);
			$datetime   	 = date("Y-m-d H:i:s");
			$kode 			 = $this->general->generate_max_number('tbl_inv_merk', 'tbl_inv_merk.kode', '3');


			$nama_jenis 	= $this->dmasterasset->get_jenis(null, $id_jenis);
			foreach ($nama_jenis as $nj) {
				$jenis = $nj->nama;
			}


		    if($id == null){
				$merk 		 	 = $this->dmasterasset->get_merk(null, null, null, $_POST['merk'], $id_jenis);
				if (count($merk) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['merk'],
			                          'id_jenis'   		=> $id_jenis,
			                          'nama_jenis'   	=> $jenis,
			                          'kode'   			=> $kode,
			                          'keterangan'      => $_POST['ket_merk'],
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_merk", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		        }else{
					$msg    = "Merk ini sudah ada pada database. Silahkan input merk lain.";
			        $sts    = "NotOK";
				}	
		    }else{
		    	$merk 		 	 = $this->dmasterasset->get_merk(null, null, null, $_POST['merk'], $id_jenis, $id);
				if (count($merk) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['merk'],
			                          'id_jenis'    	=> $id_jenis,
			                          'nama_jenis'    	=> $jenis,
			                          'keterangan'     	=> $_POST['ket_merk'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_merk", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_merk',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

				}else{
					$msg    = "Merk ini sudah ada pada database. Silahkan input merk lain.";
			        $sts    = "NotOK";
				}	
		    }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}

		public function get_merk_tipe(){
			$id_tipe = $this->generate->kirana_decrypt($_POST['id_tipe']);
			$tipe    = $this->dmasterasset->get_tipe_merk('open', NULL, $id_tipe);
			$tipe    = $this->general->generate_encrypt_json($tipe, array("id_merk_tipe"));
			echo json_encode($tipe);
		}

		public function save_merk_tipe(){

			$id     		 = $this->generate->kirana_decrypt($_POST['id_tipe']);
			$id_merk     	 = $this->generate->kirana_decrypt($_POST['id_merk']);
			$datetime   	 = date("Y-m-d H:i:s");
			$kode 			 = $this->general->generate_max_number('tbl_inv_merk_tipe', 'tbl_inv_merk_tipe.kode', '3');

			$this->general->connectDbPortal();

		    if($id == null){
				$merks 		 	 = $this->dmasterasset->get_tipe_merk(null, $id_merk, null, $_POST['tipe_merk']);
				if (count($merks) == 0){
		    		//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['tipe_merk'],
			                          'id_merk'      	=> $id_merk,
			                          'kode'   			=> $kode,
			                          'keterangan'      => $_POST['ket_tipe'],
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_merk", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		        }else{
					$msg    = "Tipe Merk ini sudah ada pada database. Silahkan input tipe merk lain.";
			        $sts    = "NotOK";
				}

		    }else{
		    	$merks 		 	 = $this->dmasterasset->get_tipe_merk(null, $id_merk, null, $_POST['tipe_merk'], $id);
				if (count($merks) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['tipe_merk'],
			                          'keterangan'     	=> $_POST['ket_tipe'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_merk", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_merk_tipe',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		        }else{
					$msg    = "Tipe Merk ini sudah ada pada database. Silahkan input tipe merk lain.";
			        $sts    = "NotOK";
				}
		    }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}
		
		//=================== MASTER MERK & TIPE MERK END ===================//

		//=================== MASTER INSTANSI & JENIS INSTANSI ===========================//

		public function get_jenis_instansi(){
			$id_jenis_instansi	= $this->generate->kirana_decrypt($_POST['id_jenis_instansi']);
			$jenis_instansi    	= $this->dmasterasset->get_jenis_instansi('open', $id_jenis_instansi);
			$jenis_instansi    	= $this->general->generate_encrypt_json($jenis_instansi, array("id_jenis_instansi"));
			echo json_encode($jenis_instansi);
		}

		public function save_jenis_instansi(){

			$this->general->connectDbPortal();
			$id			= $this->generate->kirana_decrypt($_POST['id_jenis_instansi']);
			$datetime   = date("Y-m-d H:i:s");
			$kode 		= $this->general->generate_max_number('tbl_inv_jenis_instansi', 'tbl_inv_jenis_instansi.kode', '3');



		    if($id == null){
				$jenis_instansi 	= $this->dmasterasset->get_jenis_instansi(null, null, $_POST['jenis_instansi']);
				if (count($jenis_instansi) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['jenis_instansi'],
			                          'kode'   			=> $kode,
			                          'keterangan'      => $_POST['ket_jenis_instansi'],
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_jenis_instansi", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		        }else{
					$msg    = "Jenis Instansi ini sudah ada pada database. Silahkan input jenis instansi lain.";
			        $sts    = "NotOK";
				}	
		    }else{
		    	$jenis_instansi 	= $this->dmasterasset->get_jenis_instansi(null, null, $_POST['jenis_instansi'], $id);
				if (count($jenis_instansi) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['jenis_instansi'],
			                          'keterangan'     	=> $_POST['ket_jenis_instansi'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_jenis_instansi", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_jenis_instansi',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

				}else{
					$msg    = "Jenis Instansi ini sudah ada pada database. Silahkan input jenis instansi lain.";
			        $sts    = "NotOK";
				}	
		    }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}

		public function get_instansi(){
			$id_instansi = $this->generate->kirana_decrypt($_POST['id_instansi']);
			$instansi    = $this->dmasterasset->get_instansi('open', NULL, $id_instansi);
			$instansi    = $this->general->generate_encrypt_json($instansi, array("id_instansi"));
			echo json_encode($instansi);
		}

		public function save_instansi(){

			$this->general->connectDbPortal();
			$id_jenis_instansi	= $this->generate->kirana_decrypt($_POST['id_jenis_instansi']);
			$id					= $this->generate->kirana_decrypt($_POST['id_instansi']);
			$datetime   	 	= date("Y-m-d H:i:s");
			$kode 			 	= $this->general->generate_max_number('tbl_inv_instansi', 'tbl_inv_instansi.kode', '3');

		    if($id == null){
				$instansi 		 	= $this->dmasterasset->get_instansi(null, $id_jenis_instansi, null, $_POST['instansi']);
				if (count($instansi) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'	      		=> $_POST['instansi'],
			                          'id_jenis_instansi'   => $id_jenis_instansi,
			                          'kode'   				=> $kode,
			                          'keterangan'      	=> $_POST['ket_instansi'],
			                          'login_buat'      	=> base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    	=> $datetime,
			                          'tanggal_edit'    	=> $datetime,
			                          'na'					=> 'n',
			                          'del'					=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_instansi", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }	

		        }else{
					$msg    = "Instansi ini sudah ada pada database. Silahkan input instansi lain.";
			        $sts    = "NotOK";
				}
		    }else{
		    	$instansi 		 	= $this->dmasterasset->get_instansi(null, $id_jenis_instansi, null, $_POST['instansi'], $id);
				if (count($instansi) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['instansi'],
			                          'keterangan'     	=> $_POST['ket_instansi'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_instansi", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_instansi',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		        }else{
					$msg    = "Instansi ini sudah ada pada database. Silahkan input instansi lain.";
			        $sts    = "NotOK";
				}
		    }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}


		//=================== MASTER INSTANSI & JENIS INSTANSI END =======================//
		

		//=================== MASTER DOKUMEN HRGA ===========================//\

		public function get_dokumen(){
			$id_inv_doc = $this->generate->kirana_decrypt($_POST['id_inv_doc']);
			$dokumen    = $this->dmasterasset->get_dokumen('open', $id_inv_doc);
			$dokumen    = $this->general->generate_encrypt_json($dokumen, array("id_inv_doc","id_jenis_instansi"));

			foreach ($dokumen as $dt) {
				// Ubah id jenis kendaraan menjadi nama
				$arr_jenis = explode('.', substr($dt->jenis, 1, -1));
				$jenis = array();
				foreach ($arr_jenis as $jen){
					// $cari = $this->get_data_jenis('all', $jen, 'nama');
					array_push($jenis, $this->generate->kirana_encrypt($jen));
				}
			}

			$dokumen = array($dokumen) + array("jenis_kendaraan" => $jenis);

			echo json_encode($dokumen);
		}


		public function save_dokumen(){

			$this->general->connectDbPortal();
			$datetime   	 	= date("Y-m-d H:i:s");
			$id					= $this->generate->kirana_decrypt($_POST['id_inv_doc']);
			$id_jenis_instansi	= $this->generate->kirana_decrypt($_POST['jenis_instansi']);

			$jenis_encrypted = $_POST['jenis_kendaraan'];
			
			$periode 	= $_POST['periode'];
			$reminder 	= $_POST['reminder'];

			$jenis = array();
			foreach ($jenis_encrypted as $dt) {
				array_push($jenis, $this->generate->kirana_decrypt($dt));
			}

			// ubah format dari koma ke .170. 
			$jenis = implode(',', $jenis);
			$jenis = str_replace(',', '.', $jenis);
			$jenis = ".".$jenis.".";

			if ($_POST['expired'] != "1") {
				$periode 	= "0";
				$reminder 	= "0";
			}


		    if($id == null){
				$doc 		 	 = $this->dmasterasset->get_dokumen(null, null, $_POST['dokumen'], $id_jenis_instansi);
				if (count($doc) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'	      		=> $_POST['dokumen'],
			                          'doc_expired'      	=> $_POST['expired'],
			                          'periode'     	 	=> $periode,
			                          'hari'		      	=> $reminder,
			                          'jenis'				=> $jenis,
			                          'id_jenis_instansi'   => $id_jenis_instansi,
			                          'login_buat'      	=> base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    	=> $datetime,
			                          'tanggal_edit'    	=> $datetime,
			                          'na'					=> 'n',
			                          'del'					=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_doc", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }
						
		        }else{
					$msg    = "Dokumen ini sudah ada pada database. Silahkan input dokumen lain.";
			        $sts    = "NotOK";
				}
		    }else{
		    	$doc 		 	 = $this->dmasterasset->get_dokumen(null, null, $_POST['dokumen'], $id_jenis_instansi, $id);
				if (count($doc) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'	      		=> $_POST['dokumen'],
			                          'doc_expired'      	=> $_POST['expired'],
			                          'periode'     	 	=> $periode,
			                          'hari'		      	=> $reminder,
			                          'jenis'				=> $jenis,
			                          'id_jenis_instansi'   => $id_jenis_instansi,
			                          'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    	=> $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_doc", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_inv_doc',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }
						
		        }else{
					$msg    = "Dokumen ini sudah ada pada database. Silahkan input dokumen lain.";
			        $sts    = "NotOK";
				}
		    }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);

		}

		public function sinkron_pabrik(){

			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$datetime   	 	= date("Y-m-d H:i:s");
			

			$data_table_wf    = $this->dmasterasset->get_sinkron();
			foreach ($data_table_wf as $wf) {
				$data_pabrik    = $this->dmasterasset->get_pabrik(null, $wf->plant);

			    if($data_pabrik){
			    	//insert
			        $data_row   = array(
			                          'kode'	      		=> $wf->plant,
			                          'nama'      			=> $wf->plant_name,
			                          'keterangan'     	 	=> $wf->address,
			                          'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    	=> $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_pabrik", $data_row, array( 
		                                                                    array(
		                                                                        'kolom'=>'kode',
		                                                                        'value'=>$wf->plant
		                                                                    )
		                                                                ));
			    }else{
			        $data_row   = array(
			                          'kode'	      		=> $wf->plant,
			                          'nama'      			=> $wf->plant_name,
			                          'keterangan'     	 	=> $wf->address,
			                          'login_buat'      	=> base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    	=> $datetime,
			                          'tanggal_edit'    	=> $datetime,
			                          'na'					=> 'n',
			                          'del'					=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_pabrik", $data_row);
			    }

			}


	        if($this->dgeneral->status_transaction() === FALSE){
	            $this->dgeneral->rollback_transaction();
	            $msg    = "Please re-check the submitted data";
	            $sts    = "NotOK";
	        }else{
	            $this->dgeneral->commit_transaction();
	            $msg    = "Succesfully Sync data";
	            $sts    = "OK";
	        }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);

		}

		//=================== MASTER DOKUMEN HRGA END =======================//


		public function get_kegiatan(){
			$id_kegiatan = NULL;
			if (isset($_POST['id_kegiatan'])) {
				$id_kegiatan	= $this->generate->kirana_decrypt($_POST['id_kegiatan']);
			}
			$kegiatan 		= $this->dmasterasset->get_kegiatan('open', $id_kegiatan);
			$kegiatan 		= $this->general->generate_encrypt_json($kegiatan, array("id_kegiatan"));
			echo json_encode($kegiatan);
		}

		public function save_kegiatan(){

			$this->general->connectDbPortal();
			$id			= $this->generate->kirana_decrypt($_POST['id_kegiatan']);
			$datetime   = date("Y-m-d H:i:s");

		    if($id == null){
				$kegiatan 	= $this->dmasterasset->get_kegiatan(null, null, $_POST['kegiatan']);
				if (count($kegiatan) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['kegiatan'],
			                          'keterangan'      => $_POST['ket_kegiatan'],
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_kegiatan", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }
		        }else{
					$msg    = "Kegiatan ini sudah ada pada database. Silahkan input kegiatan lain.";
			        $sts    = "NotOK";
				}	
		    }else{
		    	$kegiatan 	= $this->dmasterasset->get_kegiatan(null, null, $_POST['kegiatan'], $id);
				if (count($kegiatan) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['kegiatan'],
			                          'keterangan'     	=> $_POST['ket_kegiatan'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_kegiatan", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_kegiatan',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));
			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }
		        }else{
					$msg    = "Kegiatan ini sudah ada pada database. Silahkan input kegiatan lain.";
			        $sts    = "NotOK";
				}	
		    }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}

		public function get_service(){
			$id_service		= $this->generate->kirana_decrypt($_POST['id_service']);
			$service 		= $this->dmasterasset->get_service('open', $id_service);
			$service 		= $this->general->generate_encrypt_json($service, array("id_service"));
			echo json_encode($service);
		}

		public function save_service(){

			$this->general->connectDbPortal();
			$id			= $this->generate->kirana_decrypt($_POST['id_service']);
			$datetime   = date("Y-m-d H:i:s");


		    if($id == null){
				$service 	= $this->dmasterasset->get_service(null, null, $_POST['service']);
				if (count($service) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['service'],
			                          'keterangan'      => $_POST['ket_service'],
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_service", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }	

		        }else{
					$msg    = "Service ini sudah ada pada database. Silahkan input service lain.";
			        $sts    = "NotOK";
				}
		    }else{
		    	$service 	= $this->dmasterasset->get_service(null, null, $_POST['service'], $id);
				if (count($service) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['service'],
			                          'keterangan'     	=> $_POST['ket_service'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_service", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_service',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }	

		        }else{
					$msg    = "Service ini sudah ada pada database. Silahkan input service lain.";
			        $sts    = "NotOK";
				}
		    }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}

		public function get_satuan(){
			$id_satuan		= $this->generate->kirana_decrypt($_POST['id_satuan']);
			$satuan 		= $this->dmasterasset->get_satuan('open', $id_satuan);
			$satuan 		= $this->general->generate_encrypt_json($satuan, array("id_satuan"));
			echo json_encode($satuan);
		}

		public function save_satuan(){

			$this->general->connectDbPortal();
			$id			= $this->generate->kirana_decrypt($_POST['id_satuan']);
			$datetime   = date("Y-m-d H:i:s");

		    if($id == null){
				$satuan 	= $this->dmasterasset->get_satuan(null, null, $_POST['satuan']);
				if (count($satuan) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['satuan'],
			                          'keterangan'      => $_POST['ket_satuan'],
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_satuan", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		        }else{
					$msg    = "Satuan ini sudah ada pada database. Silahkan input satuan lain.";
			        $sts    = "NotOK";
				}
		    }else{
		    	$satuan 	= $this->dmasterasset->get_satuan(null, null, $_POST['satuan'], $id);
				if (count($satuan) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['satuan'],
			                          'keterangan'     	=> $_POST['ket_satuan'],
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_satuan", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_satuan',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		        }else{
					$msg    = "Satuan ini sudah ada pada database. Silahkan input satuan lain.";
			        $sts    = "NotOK";
				}
		    }	

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}


		public function get_biaya(){
			$id_inv_biaya	= $this->generate->kirana_decrypt($_POST['id_inv_biaya']);
			$biaya 			= $this->dmasterasset->get_biaya('open', $id_inv_biaya);
			$biaya	 		= $this->general->generate_encrypt_json($biaya, array("id_inv_biaya"));
			echo json_encode($biaya);
		}

		public function save_biaya(){

			$this->general->connectDbPortal();
			$id			= $this->generate->kirana_decrypt($_POST['id_inv_biaya']);
			$datetime   = date("Y-m-d H:i:s");

			if ($_POST['km'] == "1") {
				$km = "y";
			}else{
				$km = "n";
			}

		    if($id == null){
				$biaya 	= $this->dmasterasset->get_biaya(null, null, $_POST['biaya']);
				if (count($biaya) == 0){
			    	//insert
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['biaya'],
			                          'kode_sap'      	=> $_POST['kode_sap'],
			                          'km'      		=> $km,
			                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_buat'    => $datetime,
			                          'tanggal_edit'    => $datetime,
			                          'na'				=> 'n',
			                          'del'				=> 'n'
			                     );
			        $this->dgeneral->insert("tbl_inv_biaya", $data_row);

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

			    }else{
					$msg    = "Biaya ini sudah ada pada database. Silahkan input biaya lain.";
			        $sts    = "NotOK";
				}
		    }else{
		    	$biaya 	= $this->dmasterasset->get_biaya(null, null, $_POST['biaya'], $id);
				if (count($biaya) == 0){
					$this->dgeneral->begin_transaction();
			        $data_row   = array(
			                          'nama'      		=> $_POST['biaya'],
			                          'kode_sap'     	=> $_POST['kode_sap'],
			                          'km'     			=> $km,
			                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			                          'tanggal_edit'    => $datetime
			                     );
			        $this->dgeneral->update("tbl_inv_biaya", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_inv_biaya',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));

			        if($this->dgeneral->status_transaction() === FALSE){
			            $this->dgeneral->rollback_transaction();
			            $msg    = "Please re-check the submitted data";
			            $sts    = "NotOK";
			        }else{
			            $this->dgeneral->commit_transaction();
			            $msg    = "Succesfully added data";
			            $sts    = "OK";
			        }

		    	}else{
					$msg    = "Biaya ini sudah ada pada database. Silahkan input biaya lain.";
			        $sts    = "NotOK";
				}
		    }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}


		public function get_periode(){
			$id_periode		= $this->generate->kirana_decrypt($_POST['id_periode']);
			$periode 		= $this->dmasterasset->get_periode('open', $id_periode);
			$periode	 	= $this->general->generate_encrypt_json($periode, array("id_periode", "id_jenis", "id_service"));
			echo json_encode($periode);
		}

		public function save_periode(){

			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$id			= $this->generate->kirana_decrypt($_POST['id_periode']);
			$id_jenis	= $this->generate->kirana_decrypt($_POST['jenis_aset']);
			$id_service	= $this->generate->kirana_decrypt($_POST['service']);
			$datetime   = date("Y-m-d H:i:s");

			$service = $this->dmasterasset->get_service(NULL, $id_service);
			foreach ($service as $se) {
				$nama_service = $se->nama;
			}

		    if($id == null){
		    	//insert
		        $data_row   = array(
		                          'nama'      		=> $_POST['periode'],
		                          'kode'      		=> $_POST['kode'],
		                          'id_jenis'      	=> $id_jenis,
		                          'id_service'      => $id_service,
		                          'kategori'        => $nama_service,
		                          'keterangan'      => $_POST['ket_periode'],
		                          'jam'      		=> $_POST['jam'],
		                          'bulan'      		=> $_POST['bulan'],
		                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
		                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
		                          'tanggal_buat'    => $datetime,
		                          'tanggal_edit'    => $datetime,
		                          'na'				=> 'n',
		                          'del'				=> 'n'
		                     );
		        $this->dgeneral->insert("tbl_inv_periode", $data_row);

		    }else{
		        $data_row   = array(
		                          'nama'      		=> $_POST['periode'],
		                          'kode'      		=> $_POST['kode'],
		                          'id_jenis'      	=> $id_jenis,
		                          'id_service'      => $id_service,
		                          'kategori'        => $nama_service,
		                          'keterangan'      => $_POST['ket_periode'],
		                          'jam'      		=> $_POST['jam'],
		                          'bulan'      		=> $_POST['bulan'],
		                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
		                          'tanggal_edit'    => $datetime
		                     );
		        $this->dgeneral->update("tbl_inv_periode", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_periode',
	                                                                        'value'=>$id
	                                                                    )
	                                                                ));
		    }

	        if($this->dgeneral->status_transaction() === FALSE){
	            $this->dgeneral->rollback_transaction();
	            $msg    = "Please re-check the submitted data";
	            $sts    = "NotOK";
	        }else{
	            $this->dgeneral->commit_transaction();
	            $msg    = "Succesfully added data";
	            $sts    = "OK";
	        }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);
		}


		public function get_periode_detail_by_jenis(){
			$id_jenis		= $this->generate->kirana_decrypt($_POST['id_jenis']);
			$id_periode		= $this->generate->kirana_decrypt($_POST['id_periode']);
			$jenis_detail	= $this->dmasterasset->get_periode_detail_by_jenis('open', $id_jenis, $id_periode);
			$jenis_detail	= $this->general->generate_encrypt_json($jenis_detail, array("id_jenis_details", "id_periode_detail", "id_kegiatan"));

			$kegiatan 		= $this->dmasterasset->get_kegiatan('open');
			$list_kegiatan = array();
			foreach ($kegiatan as $keg) {
				$list_kegiatan[] = $this->generate->kirana_encrypt($keg->id_kegiatan)."-".$keg->nama;
			}

			foreach ($jenis_detail as $jen) {
				$jen->list_kegiatan = $list_kegiatan;
			}

			echo json_encode($jenis_detail);
		}

		public function save_periode_detail(){

			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$id_periode         = $this->generate->kirana_decrypt($_POST['fd_id_periode']);
			$id_jenis           = $this->generate->kirana_decrypt($_POST['fd_id_jenis']);
			// $id_jenis_detail = $this->generate->kirana_decrypt($_POST['id_jenis_detail']);
			$id                 = "";
			$datetime           = date("Y-m-d H:i:s");
			
			$total_row          = $_POST['total_row'];
			$nama_kegiatan      = "";


			for ($i=0; $i < $total_row ; $i++) {
				
				//decrypt general
				$id_jenis_detail = $this->generate->kirana_decrypt($_POST["id_jenis_detail$i"]);
				$_POST["pilih$i"] = isset($_POST["pilih$i"]) ? 'on' : 'off';
				if ($_POST["pilih$i"] == 'on') {
					
					$id_kegiatan.$i 	= $this->generate->kirana_decrypt($_POST["kegiatan$i"]);
					$get_nama 			= $this->dmasterasset->get_kegiatan(null, $id_kegiatan.$i);
					foreach ($get_nama as $get) {
						$nama_kegiatan = $get->nama;
					}


					//1. row di input
					if($_POST["id_periode_detail$i"] != ""){
						$id	= $this->generate->kirana_decrypt($_POST["id_periode_detail$i"]);
					}


					// 1.1  kalau ada id_periode_detail then update
					if ($id) {
						$data_row   = array(
	                          'id_kegiatan'     => $id_kegiatan.$i,
	                          'keterangan'      => $_POST["keterangan$i"],
	                          'nama'      		=> $nama_kegiatan.$i,
	                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
	                          'tanggal_edit'    => $datetime,
	                          'na'				=> 'n',
	                          'del'				=> 'n'
	                     );
	        			$this->dgeneral->update("tbl_inv_periode_detail", $data_row, array( 
                                                                    array(
                                                                        'kolom'=>'id_periode_detail',
                                                                        'value'=>$id
                                                                    )
                                                                ));
					}else{
					// 1.2 kalau ga ada id_periode_detail then  
						// 1.2.1 id_jenis id_periode id_jenis_detail cek kalau sudah ada tapi didelete
						$cek_existing	= $this->dmasterasset->get_periode_detail(null, $id_periode, $id_jenis_detail, $id_jenis, 'all');
						if ($cek_existing) {
							$data_row   = array(
		                          'id_kegiatan'     => $id_kegiatan.$i,
		                          'keterangan'      => $_POST["keterangan$i"],
		                          'nama'      		=> $nama_kegiatan,
		                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
		                          'tanggal_edit'    => $datetime,
		                          'na'				=> 'n',
		                          'del'				=> 'n'
		                    );
		        			$this->dgeneral->update("tbl_inv_periode_detail", $data_row, array( 
	                                                                    array(
	                                                                        'kolom'=>'id_jenis_detail',
	                                                                        'value'=>$id_jenis_detail
	                                                                    ),
																		array(
																			'kolom' => 'id_periode',
																			'value' => $id_periode
																		),
																		array(
																			'kolom' => 'id_jenis',
																			'value' => $id_jenis
																		)
	                                                                ));
						}else{
							// 1.2.2 insert baru
							$data_row   = array(
		                          'id_kegiatan'     => $id_kegiatan.$i,
		                          'keterangan'      => $_POST["keterangan$i"],
		                          'nama'      		=> $nama_kegiatan,
		                          'id_jenis'     	=> $id_jenis,
		                          'id_periode'      => $id_periode,
		                          'id_jenis_detail' => $id_jenis_detail,
		                          'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
		                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
		                          'tanggal_buat'    => $datetime,
		                          'tanggal_edit'    => $datetime,
		                          'na'				=> 'n',
		                          'del'				=> 'n'
		                    );
		        			$this->dgeneral->insert("tbl_inv_periode_detail", $data_row);
						}
					}
				}else{
				// echo 'gaoke';
				// 2 row ga di input
					// 2.1 check kalau del == n di tbl_inv-periode_detail then set del == y
					$cek_existing	= $this->dmasterasset->get_periode_detail(null, $id_periode, $id_jenis_detail, $id_jenis);
					if ($cek_existing) {
						$data_row   = array(
	                          'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
	                          'tanggal_edit'    => $datetime,
	                          'na'				=> 'y',
	                          'del'				=> 'y'
	                    );
	        			$this->dgeneral->update("tbl_inv_periode_detail", $data_row, array( 
                                                                    array(
                                                                        'kolom'=>'id_jenis_detail',
                                                                        'value'=>$id_jenis_detail
                                                                    ),
																	array(
																		'kolom' => 'id_periode',
																		'value' => $id_periode
																	),
																	array(
																		'kolom' => 'id_jenis',
																		'value' => $id_jenis
																	)
                                                                ));
					}
				}
			}

	        if($this->dgeneral->status_transaction() === FALSE){
	            $this->dgeneral->rollback_transaction();
	            $msg    = "Please re-check the submitted data";
	            $sts    = "NotOK";
	        }else{
	            $this->dgeneral->commit_transaction();
	            $msg    = "Succesfully added data";
	            $sts    = "OK";
	        }

			$this->general->closeDb();
		    $return = array('sts' => $sts, 'msg' => $msg);
	        echo json_encode($return);

		}

		public function get_ssp_periode(){
			header('Content-Type: application/json');
			$return = $this->dmasterasset->get_all_periode_datatables('open');
			echo $return;
		}
		
}