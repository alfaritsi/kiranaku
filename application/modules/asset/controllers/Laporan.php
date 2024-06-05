<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Asset Management
@author		  : Lukman Hakim (7143)
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
		$this->load->model('dmasterasset');
	    $this->load->model('dtransaksiasset');
	    $this->load->model('dlaporanasset');
	}
 
	public function index(){
		show_404();
	} 
	
	public function problemxx($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/  
		
		$data['title']    	= "Laporan Data Problem";   
		$data['title_form']	= "Laporan Data Problem"; 
		$data['problem'] 	= $this->dlaporanasset->get_data_problem("open"); 
		$this->load->view("laporan/problem", $data);	
	}
    public function excel($param=NULL, $param2=NULL, $param3=NULL, $param4=NULL){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=Data Problem Asset.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$pabrik  = (isset($_GET['pabrik']) ? $_GET['pabrik'] : NULL);
		$problem  	= (isset($_GET['problem']) ? $_GET['problem'] : NULL);
		
		$list_data = $this->dtransaksiasset->get_data_aset_bom_excel('open', NULL, NULL, NULL, NULL, 'it', NULL, NULL, $pabrik, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $problem, NULL);
		$list = "";
		foreach ($list_data as $data) {
			if ($data->id_kondisi == 1) {
				$status =  '<label class="label label-success">Beroperasi</label>';
			} else if ($data->id_kondisi == 2) {
				$status = '<label class="label label-danger">Tidak Beroperasi</label>';
			} else if ($data->id_kondisi == 4) {
				$status = '<label class="label label-warning">Dalam Perbaikan</label>';
			} else if ($data->id_kondisi == 5) {
				$status = '<label class="label label-danger">Scrap</label>';
			} else if ($data->id_kondisi == 6) {
				$status = '<label class="label label-primary">Stand By</label>';
			} else {
				$status = '<label class="label label-danger">Tidak Beroperasi</label>';
			}
			
			$list.="<tr><td>".$data->kode_barang."</td><td>".$data->nomor_sap."</td><td>".$data->nama_jenis."</td><td>".$data->nama_merk."</td><td>".$data->nama_merk_tipe."</td><td>".$data->nama_pabrik."</td><td>".$data->nama_lokasi."</td><td>".$data->nama_area."</td><td>".$data->pic_detail."</td><td>".$status."</td></tr>";
		}
		echo"
		<table border='1'>
			<tr><th>Nama Asset</th><th>Nomor Asset SAP</th><th>Sub Kategori</th><th>Merk</th><th>Tipe</th><th>Pabrik</th><th>Lokasi</th><th>Area</th><th>Nama User</th><th>Status</th></tr>
			$list 
		</table>
		";
    }
	
	public function dashboard($param=NULL, $param2=NULL, $param3=NULL){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/  
		if($param!=null){
			if($param=='it'){
				if($param2==1){
					$data['title']    	 = "Problem Asset (Asset without Name)";
				}elseif($param2==2){
					$data['title']    	 = "Problem Asset (Duplicate Asset Name)";
				}elseif($param2==3){
					$data['title']    	 = "Problem Asset (Duplicate SAP Asset Number)";
				}else{
					$data['title']    	 = "Problem Asset (No SAP Asset Number)";
				}
				$data['pabrik']	 	 = $this->get_pabrik('array');									 
				$id_pabrik				= !empty($_POST['id_pabrik'])?$_POST['id_pabrik']:1;     
				$data['id_pabrik'] 	 	= $id_pabrik;	
				$data['kategori'] 	 = $this->get_kategori('array', NULL, NULL, NULL, NULL, $param);
				$data['jenis'] 	 	 = $this->get_jenis('array', NULL, NULL, NULL, NULL, $param, NULL, NULL, $param2);
				$data['plat']	 	 = $this->get_plat('array');
				$data['satuan']	 	 = $this->get_satuan('array');
				$data['buzzer']	 	 = $this->get_buzzer('array');
				$data['status']	 	 = $this->get_status('array');
				$data['kondisi'] 	 = $this->get_kondisi('array');
				$data['lokasi']	 	 = $this->get_lokasi('array', NULL, NULL, NULL, NULL, $param);
				$data['os'] 		 = $this->get_detail_opsi('array', NULL, NULL, NULL, 'OS');
				$data['office']		 = $this->get_detail_opsi('array', NULL, NULL, NULL, 'OFFICE');
				$data['problem'] 	 	= $param2;	
				$data['id_merk_tipe'] 	= $param3;	
				$this->load->view("laporan/problem_all", $data);	
			}else{
				$id_pabrik			 = !empty($_POST['id_pabrik'])?$_POST['id_pabrik']:1;     
				$data['title']    	 = "Summary Aset Sub-Category";
				$data['title_form']  = "Form Data Problem Asset";
				$data['aset_kategori'] 	= $this->get_asset_kategori('array', $param, $id_pabrik);
				$this->load->view("laporan/problem", $data);	
			}
		}else{
			$data['title']    	 	= "Dashboard Asset ICT";    
			$data['title_form']  	= "Dashboard Asset ICT";  
			
			// if($this->data['user']->ho == 'n'){
				// $id_pabrik		  		= !empty($_POST['id_pabrik'])?$_POST['id_pabrik']:$this->data['user']->id_pabrik;
				// $data['plant'] 		= $this->dgeneral->get_master_plant($plant);
			// }else{
				// $data['plant'] 		= $this->dgeneral->get_master_plant();
				// $id_pabrik	  			= !empty($_POST['id_pabrik'])?$_POST['plant']:1;
			// }
			
			// $data['plant'] 			= $this->dgeneral->get_master_plant(); 
			$ck_pabrik				= $this->dlaporanasset->get_pabrik("open",$data['user']->gsber); 
			$data['plant'] 			= $this->dlaporanasset->get_pabrik(); 
			if(base64_decode($this->session->userdata("-ho-")) == 'y'){
				$id_pabrik			= !empty($_POST['id_pabrik'])?$_POST['id_pabrik']:1;     
			}else{
				$id_pabrik			= !empty($_POST['id_pabrik'])?$_POST['id_pabrik']:$ck_pabrik[0]->id_pabrik;         
			}
			$data['pengguna'] 		= $this->dlaporanasset->get_data_pengguna('open'); 
			$pengguna  				= !empty($_POST['pengguna'])?$_POST['pengguna']:'IT';  
			
			$data['kategori'] 		= $this->dmasterasset->get_kategori('open', NULL, $pengguna);   
			
			$id_kategori  			= !empty($_POST['id_kategori'])?$_POST['id_kategori']:NULL;  
			$id_kategori			= ($id_kategori=='all')?NULL:$id_kategori;
			if($id_kategori!=NULL){
				$data['judul']			= "Asset by sub Category";
				if($id_pabrik==1){
					$data['lokasi_jumlah'] 	= $this->dlaporanasset->get_data_area_jumlah("open", $id_kategori, $id_pabrik, $pengguna);
				}else{
					$data['lokasi_jumlah'] 	= $this->dlaporanasset->get_data_lokasi_jumlah("open", $id_kategori, $id_pabrik, $pengguna);
				}
				$data['jenis_jumlah'] 	= $this->dlaporanasset->get_data_jenis_jumlah("open", $id_kategori, $id_pabrik, $pengguna);
				$data['problem'] 		= $this->dlaporanasset->get_data_problem_new("open", $id_pabrik, $pengguna, NULL);    
			}else{
				$data['judul']			= "Asset by Category";
				if($id_pabrik==1){
					$data['lokasi_jumlah'] 	= $this->dlaporanasset->get_data_area_jumlah("open", NULL, $id_pabrik, $pengguna);
				}else{
					$data['lokasi_jumlah'] 	= $this->dlaporanasset->get_data_lokasi_jumlah("open", NULL, $id_pabrik, $pengguna);
				}
				$data['jenis_jumlah'] 	= $this->dlaporanasset->get_data_kategori_jumlah("open", $id_pabrik, $pengguna);
				$data['problem'] 		= $this->dlaporanasset->get_data_problem_new("open", $id_pabrik, $pengguna, NULL);    
			} 
			 
			$this->load->view("laporan/dashboard", $data);	   
			
		}
	}
	public function update($param=NULL,$param2=NULL){ 
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Laporan Update Rutin";
		$data['title_form']  = "Laporan Update Rutin";
		$data['pabrik']	 	 = $this->get_pabrik('array');
		$data['jumlah']	 	 = $this->dlaporanasset->get_data_jumlah('array', NULL, NULL, NULL, NULL, $param, $param2);
		$this->load->view("laporan/update", $data);	
	}
	public function asset($param=NULL,$param2=NULL,$param3=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Laporan Ringkasan Asset";
		$data['title_form']  = "Laporan Ringkasan Asset";
		$data['param']       = $param;
		$data['param2']      = $param2;
		$data['pabrik']	 	 = $this->get_pabrik('array');
		$data['jumlah']	 	 = $this->dlaporanasset->get_data_jumlah('array', NULL, NULL, NULL, NULL, $param, $param2);
		// ============================================================
		$id_aset   = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
		$active    = (isset($_POST['active']) ? $_POST['active'] : NULL);
		$deleted   = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
		$nama  	   = (isset($_POST['nama']) ? $_POST['nama'] : NULL);
		if(isset($_POST['jenis'])){
			$jenis		= array();
			foreach ($_POST['jenis'] as $dt) {
				array_push($jenis, $this->generate->kirana_decrypt($dt));
			}
		}else{
			$jenis  = NULL;
		}
		if(isset($_POST['merk'])){
			$merk	= array();
			foreach ($_POST['merk'] as $dt) {
				array_push($merk, $this->generate->kirana_decrypt($dt));
			}
		}else{
			$merk  = NULL;
		}
		if(isset($_POST['pabrik'])){
			$pabrik	= array();
			foreach ($_POST['pabrik'] as $dt) {
				array_push($pabrik, $this->generate->kirana_decrypt($dt));
			}
		}else{
			$pabrik  = NULL;
		}
		if(isset($_POST['lokasi'])){
			$lokasi	= array();
			foreach ($_POST['lokasi'] as $dt) {
				array_push($lokasi, $this->generate->kirana_decrypt($dt));
			}
		}else{
			$lokasi  = NULL;
		}
		if(isset($_POST['area'])){
			$area	= array();
			foreach ($_POST['area'] as $dt) {
				array_push($area, $this->generate->kirana_decrypt($dt));
			}
		}else{
			$area  = NULL;
		}
		$jam_mulai  	   = (isset($_POST['jam_mulai']) ? $_POST['jam_mulai'] : 0);
		$jam_selesai  	   = (isset($_POST['jam_selesai']) ? $_POST['jam_selesai'] : 999999999);
		$umur_mulai  	   = (isset($_POST['umur_mulai']) ? $_POST['umur_mulai'] : 0);
		$umur_selesai  	   = (isset($_POST['umur_selesai']) ? $_POST['umur_selesai'] : 999999999);
		if(isset($_POST['overdue'])){
			$overdue	= array();
			foreach ($_POST['overdue'] as $dt) {
				array_push($overdue, $this->generate->kirana_decrypt($dt));
			}
		}else{
			$overdue  = NULL;
		}
		if(isset($_POST['kondisi'])){
			$kondisi	= array();
			foreach ($_POST['kondisi'] as $dt) {
				array_push($kondisi, $dt);
			}
		}else{
			$kondisi  = NULL;
		}
		if(isset($_POST['status'])){
			$status	= array();
			foreach ($_POST['status'] as $dt) {
				array_push($status, $dt);
			}
		}else{
			$status  = NULL;
		}

		if(isset($_POST['ratio'])){
			$ratio	= array();
			foreach ($_POST['ratio'] as $dt) {
				array_push($ratio, $dt);
			}
		}else{
			$ratio  = NULL;
		}
		// $pabrik = array('15');
		$data['asset_det'] 	= $this->get_aset_detail("return", $id_aset, $active, $deleted, $nama, 
								$param, $jenis, $merk, $pabrik, $lokasi, $area, $kondisi,NULL,$ratio);
		// echo json_encode($data['asset_det']);
		// ============================================================
		$data['lap']		= $param2;
		if($param3 != NULL && $param3 == "detail"){
			$this->load->view("laporan/asset_detail", $data);	
		} else {
			$this->load->view("laporan/asset", $data);	
		}
	}
	public function kelengkapan($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Laporan Kelengkapan Dokumen";
		$data['title_form']  = "Laporan Kelengkapan Dokumen";
		$data['pabrik']	 	 = $this->get_pabrik('array');
		$data['aset']  	 	 = $this->get_aset('array', NULL, NULL, NULL, NULL, $param);
		$this->load->view("laporan/kelengkapan", $data);	
	}
	public function dokumen($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Laporan Dokumen";
		$data['title_form']  = "Laporan Dokumen";
		$data['pabrik']	 	 = $this->get_pabrik('array');
		$data['dokumen'] 	 = $this->get_dokumen('array');
		$data['transaksi'] 	 = $this->get_transaksi('array', NULL, NULL, NULL);
		$this->load->view("laporan/dokumen", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL, $param2 = NULL) {
		switch ($param) {
			case 'detail':
				$id_aset	= $this->generate->kirana_decrypt($_POST['id_aset']);
				$id_jenis	= $this->generate->kirana_decrypt($_POST['id_jenis']);
				$this->get_dokumen(NULL, NULL, NULL, NULL, $id_aset, $id_jenis);
				break;
			case 'kelengkapan':
				if(isset($_POST['pabrik'])){
					$pabrik	= array();
					foreach ($_POST['pabrik'] as $dt) {
						array_push($pabrik, $this->generate->kirana_decrypt($dt));
					}
				}else{
					$pabrik  = NULL;
				}
				$this->get_aset(NULL, NULL, NULL, NULL, NULL, $param2, NULL, NULL, $pabrik);
				break;
			case 'dokumen':
				if(isset($_POST['pabrik'])){
					$pabrik	= array();
					foreach ($_POST['pabrik'] as $dt) {
						array_push($pabrik, $this->generate->kirana_decrypt($dt));
					}
				}else{
					$pabrik  = NULL;
				}
				if(isset($_POST['dokumen'])){
					$dokumen	= array();
					foreach ($_POST['dokumen'] as $dt) {
						array_push($dokumen, $this->generate->kirana_decrypt($dt));
					}
				}else{
					$dokumen  = NULL;
				}
				$this->get_transaksi(NULL, NULL, NULL, NULL, $pabrik, $dokumen);
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
	private function get_jumlah($array = NULL, $id_aset = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL) {
		$jumlah 		= $this->dlaporanasset->get_data_jumlah("open", $id_aset, $active, $deleted, $nama, $pengguna);
		$jumlah 		= $this->general->generate_encrypt_json($jumlah, array("id_jenis"));
		if ($array) {
			return $jumlah;
		} else {
			echo json_encode($jumlah);
		}
	}
	private function get_aset($array = NULL, $id_aset = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $jenis=NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL) {
		$aset 		= $this->dtransaksiasset->get_data_aset("open", $id_aset, $active, $deleted, $nama, $pengguna, $jenis, $merk, $pabrik, $lokasi, $area);
		$aset 		= $this->general->generate_encrypt_json($aset, array("id_aset","id_jenis"));
		if ($array) {
			return $aset;
		} else {
			echo json_encode($aset);
		}
	}
	
	private function get_pabrik($array = NULL, $id_pabrik = NULL, $active = NULL, $deleted = NULL, $nama = NULL) {
		$pabrik = $this->dtransaksiasset->get_data_pabrik("open", $id_pabrik, $active, $deleted, $nama);
		$pabrik = $this->general->generate_encrypt_json($pabrik, array("id_pabrik"));
		if ($array) {
			return $pabrik;
		} else {
			echo json_encode($pabrik);
		}
	}
	private function get_dokumen($array = NULL, $id_inv_doc = NULL, $active = NULL, $deleted = NULL, $id_aset = NULL, $id_jenis = NULL) {
		$dokumen = $this->dtransaksiasset->get_data_dokumen("open", $id_inv_doc, $active, $deleted, $id_aset, $id_jenis);
		$dokumen = $this->general->generate_encrypt_json($dokumen, array("id_inv_doc"));
		if ($array) {
			return $dokumen;
		} else {
			echo json_encode($dokumen);
		}
	}
	
	private function get_transaksi($array = NULL, $id_inv_doc_transaksi = NULL, $active = NULL, $deleted = NULL, $pabrik = NULL, $dokumen = NULL) {
		$transaksi 	= $this->dlaporanasset->get_data_transaksi("open", $id_inv_doc_transaksi, $active, $deleted, $pabrik, $dokumen);
		$transaksi	= $this->general->generate_encrypt_json($transaksi, array("id_inv_doc_transaksi"));
		
		if ($array) {
			return $transaksi;
		} else {
			echo json_encode($transaksi);
		}
	}

	private function get_aset_detail($array = NULL, $id_aset = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $jenis=NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $kondisi=NULL, $status=NULL, $ratio=NULL) {
		$aset 		= $this->dtransaksiasset->get_data_aset("open", $id_aset, $active, $deleted, $nama, $pengguna, $jenis, $merk, $pabrik, $lokasi, $area, $kondisi, $status,$ratio);
		$aset 		= $this->general->generate_encrypt_json($aset, array("id_aset","id_kategori","id_jenis","id_merk","id_merk_tipe","id_status","id_kondisi","id_pabrik","id_lokasi","id_sub_lokasi","id_area","id_kerusakan"));
		
		if ($array) {
			return $aset;
		} else {
			echo json_encode($aset);
		}
	}
	private function get_asset_kategori($array = NULL, $nama_kategori = NULL, $id_pabrik = NULL) {
		$asset_kategori 	= $this->dlaporanasset->get_data_asset_kategori("open", $nama_kategori, $id_pabrik);
		if ($array) {
			return $asset_kategori;
		} else {
			echo json_encode($asset_kategori);
		}
	}
	
	private function get_kategori($array = NULL, $id_kategori = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL) {
		$kategori 		= $this->dtransaksiasset->get_data_kategori("open", $id_kategori, $active, $deleted, $nama, $pengguna);
		$kategori 		= $this->general->generate_encrypt_json($kategori, array("id_kategori"));
		if ($array) {
			return $kategori;
		} else {
			echo json_encode($kategori);
		}
	}
	private function get_jenis($array = NULL, $id_jenis = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $id_kategori = NULL, $kategori = NULL, $alat = NULL) {
		$jenis 		= $this->dtransaksiasset->get_data_jenis("open", $id_jenis, $active, $deleted, $nama, $pengguna, $id_kategori, $kategori, $alat);
		$jenis 		= $this->general->generate_encrypt_json($jenis, array("id_jenis"));
		if ($array) {
			return $jenis;
		} else {
			echo json_encode($jenis);
		}
	}
	private function get_plat($array = NULL, $kode = NULL) {
		$plat = $this->dtransaksiasset->get_data_plat("open", $kode);
		if ($array) {
			return $plat;
		} else {
			echo json_encode($plat);
		}
	}
	private function get_satuan($array = NULL, $id_satuan = NULL) {
		$satuan = $this->dtransaksiasset->get_data_satuan("open", $id_satuan);
		if ($array) {
			return $satuan;
		} else {
			echo json_encode($satuan);
		}
	}
	private function get_buzzer($array = NULL, $id_buzzer = NULL) {
		$buzzer = $this->dtransaksiasset->get_data_buzzer("open", $id_buzzer);
		if ($array) {
			return $buzzer;
		} else {
			echo json_encode($buzzer);
		}
	}
	
	private function get_status($array = NULL, $id_status = NULL, $active = NULL, $deleted = NULL, $nama = NULL) {
		$status = $this->dtransaksiasset->get_data_status("open", $id_status, $active, $deleted, $nama);
		$status = $this->general->generate_encrypt_json($status, array("id_status"));
		if ($array) {
			return $status;
		} else {
			echo json_encode($status);
		}
	}
	private function get_kondisi($array = NULL, $id_kondisi = NULL, $active = NULL, $deleted = NULL, $nama = NULL) {
		$kondisi = $this->dtransaksiasset->get_data_kondisi("open", $id_kondisi, $active, $deleted, $nama);
		$kondisi = $this->general->generate_encrypt_json($kondisi, array("id_kondisi"));
		if ($array) {
			return $kondisi;
		} else {
			echo json_encode($kondisi);
		}
	}
	private function get_lokasi($array = NULL, $id_lokasi = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL) {
		$lokasi = $this->dtransaksiasset->get_data_lokasi("open", $id_lokasi, $active, $deleted, $nama, $pengguna);
		$lokasi = $this->general->generate_encrypt_json($lokasi, array("id_lokasi"));
		if ($array) {
			return $lokasi;
		} else {
			echo json_encode($lokasi);
		}
	}
	private function get_detail_opsi($array = NULL, $id_detail_opsi = NULL, $active = NULL, $deleted = NULL, $nama_kolom = NULL) {
		$detail_opsi	= $this->dtransaksiasset->get_data_detail_opsi("open", $id_detail_opsi, $active, $deleted, $nama_kolom);
		if ($array) {
			return $detail_opsi;
		} else {
			echo json_encode($detail_opsi);
		}
	}
	
	/*====================================================================*/
		
}