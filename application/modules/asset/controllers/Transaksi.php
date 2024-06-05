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

Class Transaksi extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmasterasset');
	    $this->load->model('dtransaksiasset');
	}

	public function index(){
		show_404();
	}
	
	public function email($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Maintenance Email";
		$data['title_form']  = "Maintenance Email";
		$data['email'] 	 	 = $this->get_email('array', NULL, NULL, NULL);
		$this->load->view("transaksi/email", $data);	
	}
	
	public function detail($param=NULL,$param2=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Detail Transaksi Maintenance";
		$data['title_form']  = "Detail Transaksi Maintenance";
		$data['kategori'] 	 = $this->get_kategori('array', NULL, NULL, NULL, NULL, $param);
		$data['jenis'] 	 	 = $this->get_jenis('array', NULL, NULL, NULL, NULL, $param, NULL, NULL, $param2);
		$data['plat']	 	 = $this->get_plat('array');
		$data['status']	 	 = $this->get_status('array');
		$data['kondisi'] 	 = $this->get_kondisi('array');
		$data['pabrik']	 	 = $this->get_pabrik('array');
		$data['lokasi']	 	 = $this->get_lokasi('array', NULL, NULL, NULL, NULL, $param);
		$data['alat']		 = $param2;
		// $data['detail']  	 = $this->get_detail('array', NULL, NULL, NULL, NULL, $param, NULL, NULL, NULL, NULL, NULL, $param2);
		if($param=='fo'){
			$this->load->view("transaksi/detail_fo", $data);	
		}
	}
	
	public function maintenance($param=NULL, $param2=NULL, $param3=NULL, $param4=NULL){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		if($param2=='lab'){
			$data['title']    	 = "Data Asset (Non Alat Berat)";
			$data['title_form']  = "Data Asset (Non Alat Berat)";
		}else{
			$data['title']    	 = "Maintenance Asset";
			$data['title_form']  = "Maintenance Asset";
		}
		$data['kategori'] 	 = $this->get_kategori('array', NULL, NULL, NULL, NULL, $param);
		$data['jenis'] 	 	 = $this->get_jenis('array', NULL, NULL, NULL, NULL, $param, NULL, NULL, $param2);
		$data['plat']	 	 = $this->get_plat('array');
		$data['satuan']	 	 = $this->get_satuan('array');
		$data['buzzer']	 	 = $this->get_buzzer('array');
		$data['status']	 	 = $this->get_status('array');
		$data['kondisi'] 	 = $this->get_kondisi('array');
		$data['pabrik']	 	 = $this->get_pabrik('array');
		$data['lokasi']	 	 = $this->get_lokasi('array', NULL, NULL, NULL, NULL, $param);
		$data['periode'] 	 = $this->get_kategori('array', NULL, NULL, NULL, NULL, $param);		
		$data['os'] 		 = $this->get_detail_opsi('array', NULL, NULL, NULL, 'OS');
																						
		$data['office']		 = $this->get_detail_opsi('array', NULL, NULL, NULL, 'OFFICE');
																						

		$kerusakan 			 = $this->dmasterasset->get_kerusakan('open',NULL, NULL, "fo");
        $kerusakan 			 = $this->general->generate_encrypt_json($kerusakan, array("id_kerusakan"));
		$data['kerusakan'] 	 = $kerusakan;
        
		
		if($param=='hrga'){
			// $data['aset']  	 	 = $this->get_aset('array', NULL, NULL, NULL, NULL, $param);
			$this->load->view("transaksi/maintenance_hrga", $data);	
		}
		if($param=='it'){
			// $data['aset']  	 	 = $this->get_aset('array', NULL, NULL, NULL, NULL, $param);
			// if($param2=='1'){
				// $this->load->view("transaksi/maintenance_lab", $data);	
			// }else{
				// $this->load->view("transaksi/maintenance_it", $data);	
			// }
			$data['problem'] 	 	= ($param2!=null)?$param2:0;	
			$data['id_merk_tipe'] 	= ($param3!=null)?$param3:0;	
			$jam_mulai  	   = (isset($_GET['jam_mulai']) ? $_GET['jam_mulai'] : 0);
			$this->load->view("transaksi/maintenance_it", $data);	
		}
		if($param=='fo'){
			// $data['aset']  	 	 = $this->get_aset('array', NULL, NULL, NULL, NULL, $param);
			if($param2=='lab'){
				$this->load->view("transaksi/maintenance_lab", $data);	
			}else{
				$this->load->view("transaksi/maintenance_fo", $data);	
			}
			
		}
	}

	public function approval($param=NULL, $param2=NULL){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		if($param2=='retire'){
			$data['title']    	 = "Retirement Asset";
			$data['title_form']  = "Masukan Retirement Asset";
		}else{
			$data['title']    	 = "Asset Approval";
			$data['title_form']  = "Masukan Asset Approval";
		}
		$data['jenis'] 	 	 = $this->get_jenis('array', NULL, NULL, NULL, NULL, $param);
		$data['plat']	 	 = $this->get_plat('array');
		$data['status']	 	 = $this->get_status('array');
		$data['kondisi'] 	 = $this->get_kondisi('array');
		$data['pabrik']	 	 = $this->get_pabrik('array');
		$data['lokasi']	 	 = $this->get_lokasi('array', NULL, NULL, NULL, NULL, $param);
		if($param2=='retire'){
			$data['aset_temp'] 	 = $this->get_aset_temp('array', NULL, NULL, NULL, NULL, $param, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'set_retire');
		}else{
			$data['aset_temp'] 	 = $this->get_aset_temp('array', NULL, NULL, NULL, NULL, $param, NULL, NULL, NULL, NULL, NULL, 'menunggu');	
		}
		if($param=='hrga'){
			$this->load->view("transaksi/approval_hrga", $data);	
		}
		if($param=='it'){
			if($param2=='retire'){
				$this->load->view("transaksi/retire_it", $data);
			}else{
				$this->load->view("transaksi/approval_it", $data);	
			}
		}
	}

	public function status($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$data['title']    	 = "Asset Status";
		$data['title_form']  = "Masukan Asset Status";
		$data['jenis'] 	 	 = $this->get_jenis('array', NULL, NULL, NULL, NULL, $param);
		$data['plat']	 	 = $this->get_plat('array');
		$data['status']	 	 = $this->get_status('array');
		$data['kondisi'] 	 = $this->get_kondisi('array');
		$data['pabrik']	 	 = $this->get_pabrik('array');
		$data['lokasi']	 	 = $this->get_lokasi('array', NULL, NULL, NULL, NULL, $param);
		$data['aset_temp'] 	 = $this->get_aset_temp('array', NULL, NULL, NULL, NULL, $param, NULL, NULL, NULL, NULL, NULL);
		if($param=='hrga'){
			$this->load->view("transaksi/status_hrga", $data);	
		}
		if($param=='it'){
			$this->load->view("transaksi/status_it", $data);	
		}
	}

	private function dokumen($param){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$id_aset   = (isset($param) ? $this->generate->kirana_decrypt($param) : NULL);
		$data['title']    			= "Pembaruan Dokumen";
		$data['title_form'] 		= "Pembaruan Dokumen";
		$data['aset']			 	= $this->get_aset('array', $id_aset);
		$data['dokumen'] 			= $this->get_dokumen('array', NULL, NULL, NULL);
		$data['dokumen_transaksi'] 	= $this->get_dokumen_transaksi('array', NULL, NULL, NULL, $id_aset);
		$this->load->view("transaksi/hrga_dokumen", $data);
	}

	public function pdf($param){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

		$id_main   				= (isset($param) ? $this->generate->kirana_decrypt($param) : NULL);
		$data['main']		 	= $this->get_main('array', $id_main, NULL, NULL);
		$data['main_detail'] 	= $this->get_main_detail('array', NULL, NULL, NULL, $id_main);
		// $this->load->view("transaksi/pdf", $data);
		$this->load->library('pdf');
	    $this->pdf->setPaper('A4', 'Portrait');
		// $this->pdf->setPaper('A4', 'Landscape');
	    $this->pdf->filename = "laporan.pdf";
	    $this->pdf->load_view("transaksi/pdf", $data);
		
	}	
    public function excel($param=NULL, $param2=NULL){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=Data Asset FO.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
					if(!empty($_GET['jenis'])){
						$arr_jenis	= explode(',',$_GET['jenis']);
						$jenis		= array();
						foreach ($arr_jenis as $dt) {
							array_push($jenis, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$jenis  = NULL;
					}
					if(!empty($_GET['merk'])){
						$arr_merk	= explode(',',$_GET['merk']);
						$merk	= array();
						foreach ($arr_merk as $dt) {
							array_push($merk, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$merk  = NULL;
					}
					if(!empty($_GET['pabrik'])){
						$arr_pabrik	= explode(',',$_GET['pabrik']);
						$pabrik		= array();
						foreach ($arr_pabrik as $dt) {
							array_push($pabrik, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$pabrik  = NULL;
					}
					if(!empty($_GET['lokasi'])){
						$arr_lokasi	= explode(',',$_GET['lokasi']);
						$lokasi	= array();
						foreach ($arr_lokasi as $dt) {
							array_push($lokasi, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$lokasi  = NULL;
					}
					if(!empty($_GET['area'])){
						$arr_area	= explode(',',$_GET['area']);
						$area	= array();
						foreach ($arr_area as $dt) {
							array_push($area, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$area  = NULL;
					}
					$jam_mulai  	   = (isset($_GET['jam_mulai']) ? $_GET['jam_mulai'] : 0);
					$jam_selesai  	   = (isset($_GET['jam_selesai']) ? $_GET['jam_selesai'] : 999999999);
					$umur_mulai  	   = (isset($_GET['umur_mulai']) ? $_GET['umur_mulai'] : 0);
					$umur_selesai  	   = (isset($_GET['umur_selesai']) ? $_GET['umur_selesai'] : 999999999);
					if(!empty($_GET['overdue'])){
						$arr_overdue	= explode(',',$_GET['overdue']);
						$overdue	= array();
						foreach ($arr_overdue as $dt) {
							array_push($overdue, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$overdue  = NULL;
					}
					if(!empty($_GET['kondisi'])){
						$arr_kondisi	= explode(',',$_GET['kondisi']);
						$kondisi	= array();
						foreach ($arr_kondisi as $dt) {
							array_push($kondisi, $dt);
						}
					}else{
						$kondisi  = NULL;
					}
					if(!empty($_GET['status'])){
						$arr_status	= explode(',',$_GET['status']);
						$status	= array();
						foreach ($arr_status as $dt) {
							array_push($status, $dt);
						}
					}else{
						$status  = NULL;
					}
		
		// $list_data = $this->get_aset('array', NULL, NULL, NULL, NULL, $param, $jenis, $merk, $pabrik, $lokasi, $area, $kondisi, $status);
		$list_data = $this->get_aset_bom_excel('open', NULL, NULL, NULL, NULL, $param, $jenis, $merk, $pabrik, $lokasi, $area, $jam_mulai, $jam_selesai, $umur_mulai, $umur_selesai, $overdue, $kondisi, $status, $param2);
		$list = "";
		foreach ($list_data as $data) {
			if($data->id_kondisi==1){
				if($data->aging>0){
					$kondisi 	= "Expired";
				}else{
					$kondisi 	= "Beroperasi";	
				}
			}else{
				$kondisi 	= "Tidak Beroperasi";
			}
			$status 	= ($data->na=='n')?"Aktif":"Non Aktif"; 
			$list.="<tr><td>".$data->nomor."</td><td>".$data->nama_pabrik."</td><td>".$data->nama_lokasi."</td><td>".$data->nama_sub_lokasi."</td><td>".$data->nama_area."</td><td>".$data->nama_jenis."</td><td>".$data->nama_merk."</td><td>".$data->nomor_sap."</td><td>".$data->jam_jalan."</td><td>".$data->tanggal_edit."</td><td>".$kondisi."</td><td>".$status."</td></tr>";
		}
		echo"
		<table border='1'>
			<tr><th>Nomor</th><th>Pabrik</th><th>Lokasi</th><th>Sub Lokasi</th><th>Area</th><th>Jenis</th><th>Merk</th><th>Nomor SAP</th><th>Jam Jalan</th><th>Last Update</th><th>Kondisi</th><th>Status</th></tr>
			$list 
		</table>
		";
    }

	public function retire($param=NULL, $param2=NULL, $param3=NULL, $param4=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		if($param2=='lab'){
			$data['title']    	 = "Retirement Asset (Non Alat Berat)";
			$data['title_form']  = "Retirement Asset (Non Alat Berat)";
		}else{
			$data['title']    	 = "Retirement Asset";
			$data['title_form']  = "Retirement Asset";
		}
		$data['kategori'] 	 = $this->get_kategori('array', NULL, NULL, NULL, NULL, $param);
		$data['jenis'] 	 	 = $this->get_jenis('array', NULL, NULL, NULL, NULL, $param, NULL, NULL, $param2);
		$data['plat']	 	 = $this->get_plat('array');
		$data['satuan']	 	 = $this->get_satuan('array');
		$data['buzzer']	 	 = $this->get_buzzer('array');
		$data['status']	 	 = $this->get_status('array');
		$data['kondisi'] 	 = $this->get_kondisi('array');
		$data['pabrik']	 	 = $this->get_pabrik('array');
		$data['lokasi']	 	 = $this->get_lokasi('array', NULL, NULL, NULL, NULL, $param);
		$data['periode'] 	 = $this->get_kategori('array', NULL, NULL, NULL, NULL, $param);
		
		if($param=='hrga'){
			$this->load->view("transaksi/retire_hrga", $data);	
		}
		if($param=='it'){
			$this->load->view("transaksi/retire_it", $data);	
		}
		if($param=='fo'){
			if($param2=='lab'){
				$this->load->view("transaksi/retire_lab", $data);	
			}else{
				$this->load->view("transaksi/retire_fo", $data);	
			}
			
		}
	}

	//=================================//
	//		  MIRRORING URL 		   //
	//=================================//
	public function data($url=NULL,$param=NULL){
		switch ($url) {
			case 'hrga':
				$this->dokumen($param);
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
		public function get($param = NULL,$param2 = NULL,$param3 = NULL,$param4 = NULL) {
			switch ($param) {
				// case 'maintenance':
					// $id_aset   = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
					// $this->get_maintenance(NULL, $id_aset);
					// break;
				case 'cek':
					$value   = (isset($_POST['value']) ? $_POST['value'] : NULL);
					$tabel   = (isset($_POST['tabel']) ? $_POST['tabel'] : NULL);
					$field   = (isset($_POST['field']) ? $_POST['field'] : NULL);
					$this->get_cek(NULL, $tabel, $field, $value);
					break;
				
				case 'approval':
					$id_aset_temp   = (isset($_POST['id_aset_temp']) ? $_POST['id_aset_temp'] : NULL);
					$id_aset   = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
					$act 	   = (isset($_POST['act']) ? $_POST['act'] : NULL);
					$proses    = (isset($_POST['proses']) ? $_POST['proses'] : NULL);
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
					if($proses=='set_retire'){
						$this->get_aset_temp(NULL, $id_aset, $active, $deleted, $nama, $param2, $jenis, $merk, $pabrik, $lokasi, $area, NULL,$act,$proses,$id_aset_temp);
					}else{
						$this->get_aset_temp(NULL, $id_aset, $active, $deleted, $nama, $param2, $jenis, $merk, $pabrik, $lokasi, $area, 'menunggu',$act,$proses,$id_aset_temp);
					}
					
					break;
					
				case 'hrga':
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
					if($param2=='bom'){
						header('Content-Type: application/json');
						$return = $this->dtransaksiasset->get_data_aset_bom('open', $id_aset, $active, 'n', $nama, $param, $jenis, $merk, $pabrik, $lokasi, $area);
						echo $return;
						// $this->get_aset_bom('open', $id_aset, 'n', 'n', $nama, $param, $jenis, $merk, $pabrik, $lokasi, $area);
						break;
					}else{
						$this->get_aset(NULL, $id_aset, $active, $deleted, $nama, $param, $jenis, $merk, $pabrik, $lokasi, $area);
						break;
					}
				case 'kategori':
					$id_kategori   = (isset($_POST['id_kategori']) ? $this->generate->kirana_decrypt($_POST['id_kategori']) : NULL);
					$this->get_kategori(NULL, $id_kategori, NULL, NULL, NULL, $param2);
					break;
				case 'jenis':
					$id_jenis      = (isset($_POST['id_jenis']) ? $this->generate->kirana_decrypt($_POST['id_jenis']) : NULL);
					$id_kategori   = (isset($_POST['id_kategori']) ? $this->generate->kirana_decrypt($_POST['id_kategori']) : NULL);
					if(isset($_POST['kategori'])){
						$kategori		= array();
						foreach ($_POST['jenis'] as $dt) {
							array_push($kategori, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$kategori  = NULL;
					}
					// $id_jenis   = (isset($_POST['id_jenis']) ? $_POST['id_jenis'] : NULL);
					$this->get_jenis(NULL, $id_jenis, NULL, NULL, NULL, $param2, $id_kategori, $kategori);
					break;
				case 'merk':
					$id_jenis   = (isset($_POST['id_jenis']) ? $this->generate->kirana_decrypt($_POST['id_jenis']) : NULL);
					if(isset($_POST['jenis'])){
						$jenis		= array();
						foreach ($_POST['jenis'] as $dt) {
							array_push($jenis, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$jenis  = NULL;
					}
					$this->get_merk(NULL, NULL, NULL, NULL, NULL, $param2, $id_jenis, $jenis);
					break;
				case 'sn_os':
					$id_kategori   = (isset($_POST['id_kategori']) ? $this->generate->kirana_decrypt($_POST['id_kategori']) : NULL);
					$this->get_sn_os(NULL, $id_kategori);
					break;
				case 'sn_office':
					$id_kategori   = (isset($_POST['id_kategori']) ? $this->generate->kirana_decrypt($_POST['id_kategori']) : NULL);
					$this->get_sn_office(NULL, $id_kategori);
					break;
				case 'tipe':
					$id_merk   = (isset($_POST['id_merk']) ? $this->generate->kirana_decrypt($_POST['id_merk']) : NULL);
					// $id_merk   = (isset($_POST['id_merk']) ? $_POST['id_merk'] : NULL);
					$this->get_merk_tipe(NULL, NULL, NULL, NULL, NULL, $param2, $id_merk);
					break;
				case 'depo':
					$id_pabrik   = (isset($_POST['id_pabrik']) ? $this->generate->kirana_decrypt($_POST['id_pabrik']) : NULL);
					$id_lokasi   = (isset($_POST['id_lokasi']) ? $this->generate->kirana_decrypt($_POST['id_lokasi']) : NULL);
					// $id_pabrik   = (isset($_POST['id_pabrik']) ? $_POST['id_pabrik'] : NULL);
					// $id_lokasi   = (isset($_POST['id_lokasi']) ? $_POST['id_lokasi'] : NULL);
					$this->get_depo(NULL, NULL, NULL, NULL, NULL, $param2, $id_pabrik, $id_lokasi);
					break;
				case 'lokasi':
					$id_lokasi   = (isset($_POST['id_lokasi']) ? $this->generate->kirana_decrypt($_POST['id_lokasi']) : NULL);
					// $id_lokasi   = (isset($_POST['id_lokasi']) ? $_POST['id_lokasi'] : NULL);
					$this->get_lokasi(NULL, $id_lokasi);
					break;
				case 'sublokasi':
					$id_lokasi   = (isset($_POST['id_lokasi']) ? $this->generate->kirana_decrypt($_POST['id_lokasi']) : NULL);
					// $id_lokasi   = (isset($_POST['id_lokasi']) ? $_POST['id_lokasi'] : NULL);
					$this->get_sub_lokasi(NULL, NULL, NULL, NULL, NULL, $id_lokasi);
					break;
				case 'area':
					$id_sub_lokasi   = (isset($_POST['id_sub_lokasi']) ? $this->generate->kirana_decrypt($_POST['id_sub_lokasi']) : NULL);
					if(isset($_POST['lokasi'])){
						$lokasi		= array();
						foreach ($_POST['lokasi'] as $dt) {
							array_push($lokasi, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$lokasi  = NULL;
					}

					$this->get_area(NULL, NULL, 'n', NULL, NULL, $id_sub_lokasi, $lokasi);
					break;
				case 'pic':
					$this->general->get_user_autocomplete();
					break;
				case 'dokumen':
					$id_aset			    = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
					$id_inv_doc			    = (isset($_POST['id_inv_doc']) ? $this->generate->kirana_decrypt($_POST['id_inv_doc']) : NULL);
					$id_inv_doc_transaksi   = (isset($_POST['id_inv_doc_transaksi']) ? $this->generate->kirana_decrypt($_POST['id_inv_doc_transaksi']) : NULL);
					$this->get_dokumen_transaksi(NULL, $id_inv_doc_transaksi, NULL, NULL, $id_aset, $id_inv_doc);
					break;
				case 'approval_hrga':
					$id_aset   = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
					$act 	   = (isset($_POST['act']) ? $_POST['act'] : NULL);
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
					$this->get_aset_temp(NULL, $id_aset, $active, $deleted, $nama, 'hrga', $jenis, $merk, $pabrik, $lokasi, $area, 'menunggu',$act);
					break;
				case 'status_hrga':
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
					$this->get_aset_temp(NULL, $id_aset, $active, $deleted, $nama, 'hrga', $jenis, $merk, $pabrik, $lokasi, $area);
					break;
				//ICT	
				case 'it':
					$id_aset   = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
					$active    = (isset($_POST['active']) ? $_POST['active'] : NULL);
					$deleted   = (isset($_POST['deleted']) ? $_POST['deleted'] : NULL);
					$nama  	   = (isset($_POST['nama']) ? $_POST['nama'] : NULL);
					$problem   = (isset($_POST['problem']) ? $_POST['problem'] : NULL);
					$id_merk_tipe   = (isset($_POST['id_merk_tipe']) ? $_POST['id_merk_tipe'] : NULL);
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
					if(isset($_POST['kondisi'])){
						$kondisi	= array();
						foreach ($_POST['kondisi'] as $dt) {
							array_push($kondisi, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$kondisi  = NULL;
					}
					if(isset($_POST['flag'])){
						$flag	= array();
						foreach ($_POST['flag'] as $dt) {
							array_push($flag, $dt);
						}
					}else{
						$flag  = NULL;
					}
					if($param2=='bom'){
						header('Content-Type: application/json');
						$return = $this->dtransaksiasset->get_data_aset_bom('open', $id_aset, $active, 'n', $nama, $param, $jenis, $merk, $pabrik, $lokasi, $area, NULL, NULL, NULL, NULL, NULL ,$kondisi, NULL, NULL, $flag, $problem, $id_merk_tipe);
						echo $return;
						break;
					}else{
						$this->get_aset(NULL, $id_aset, $active, $deleted, $nama, $param, $jenis, $merk, $pabrik, $lokasi, $area);
						break;
					}
				//FO	
				case 'fo':
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
					
					if($param2=='bom'){
						//xx
						header('Content-Type: application/json');
						$return = $this->dtransaksiasset->get_data_aset_bom('open', $id_aset, $active, 'n', $nama, $param, $jenis, $merk, $pabrik, $lokasi, $area, $jam_mulai, $jam_selesai, $umur_mulai, $umur_selesai,$overdue,$kondisi,$status,$param3,$ratio);
						echo $return;
						break;
					}else{
						$this->get_aset(NULL, $id_aset, $active, $deleted, $nama, $param, $jenis, $merk, $pabrik, $lokasi, $area, $kondisi,NULL,$ratio);
						break;
					}
					
				case 'detail':
					$id_main   = (isset($_POST['id_main']) ? $this->generate->kirana_decrypt($_POST['id_main']) : NULL);
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
					if(isset($_POST['status'])){
						$status	= array();
						foreach ($_POST['status'] as $dt) {
							array_push($status, $dt);
						}
					}else{
						$status  = NULL;
					}
					
					if($param4=='bom'){
						header('Content-Type: application/json');
						// $return = $this->dtransaksiasset->get_data_detail_bom('open', $id_main, $active, 'n', $nama, $param2, $jenis, $merk, $pabrik, $lokasi, $area, $param3, $jam_mulai, $jam_selesai, $umur_mulai, $umur_selesai, $overdue);
						$return = $this->dtransaksiasset->get_data_detail_bom('open', $id_aset, $active, 'n', $nama, $param2, $jenis, $merk, $pabrik, $lokasi, $area, $param3, $status);
						echo $return;
						break;
					}else{
						$this->get_detail(NULL, $id_main, $active, $deleted, $nama, $param2, $jenis, $merk, $pabrik, $lokasi, $area, $param3, $jam_mulai, $jam_selesai, $umur_mulai, $umur_selesai, $overdue);
						break;
					}
                case 'periode':
                    $id_jenis = (isset($_POST['id_jenis']) ? $this->generate->kirana_decrypt($_POST['id_jenis']) : NULL);
                    $this->get_periode(NULL, null, NULL, NULL, NULL, $id_jenis, null, $param2);
                    break;
				
				//RETIRE
				case 'retire':
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
					if(isset($_POST['kondisi'])){
						$kondisi	= array();
						foreach ($_POST['kondisi'] as $dt) {
							array_push($kondisi, $this->generate->kirana_decrypt($dt));
						}
					}else{
						$kondisi  = NULL;
					}
					if(isset($_POST['flag'])){
						$flag	= array();
						foreach ($_POST['flag'] as $dt) {
							array_push($flag, $dt);
						}
					}else{
						$flag  = NULL;
					}
					header('Content-Type: application/json');
					$return = $this->dtransaksiasset->get_data_aset_temp_bom('open', $id_aset, $active, 'n', $nama, $param, $jenis, $merk, $pabrik, $lokasi, $area, NULL, NULL, NULL, NULL, NULL ,$kondisi, NULL, NULL, $flag);
					echo $return;
					break;
					
                case 'aset_pic':
                    $pic 	= (isset($_POST['pic']) ? $_POST['pic'] : NULL);
                    $this->get_aset_pic(NULL, NULL, NULL, NULL, $pic);
                    break;
				
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}

		public function convert($param = NULL,$param2 = NULL,$param3 = NULL,$param4 = NULL) {
			switch ($param) {
				// case 'maintenance':
					// $id_aset   = (isset($_POST['id_aset']) ? $this->generate->kirana_decrypt($_POST['id_aset']) : NULL);
					// $this->get_maintenance(NULL, $id_aset);
					// break;
				case 'id':
					$id_convert 	= (isset($_POST['id']) 
										? $this->generate->kirana_decrypt($_POST['id']) 
										: "tidak ada id");
					echo json_encode($id_convert);
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
			} else if (isset($_POST['type']) && $_POST['type'] == "delete") {
				$action = "delete_na_del";
			}

			if ($action) {
				switch ($param) {
					case 'hrga':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_inv_aset", array(
							array(
								'kolom' => 'id_aset',
								'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'it':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_inv_aset", array(
							array(
								'kolom' => 'id_aset',
								'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
							)
						));
						echo json_encode($return);
						$this->general->closeDb();
						break;
					case 'fo':
						$this->general->connectDbPortal();
						$return = $this->general->set($action, "tbl_inv_aset", array(
							array(
								'kolom' => 'id_aset',
								'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
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

		public function save($param = NULL,$param2 = NULL) {
			switch ($param) {
				case 'email':
					$this->save_email();
					break;
				case 'approval':
					$this->save_approval($param2);
					break;
				case 'reject':
					$this->save_reject($param2);
					break;
				case 'reject_hrga':
					$this->save_reject_aset($param);
					break;
				case 'approval_hrga':
					$this->save_approval_aset($param);
					break;
				case 'hrga':
					$this->save_aset($param);
					break;
				case 'it':
					$this->save_aset($param);
					break;
				case 'fo':
					$this->save_aset($param);
					break;
				case 'dokumen':
					$this->save_dokumen($param);
					break;
				case 'main':
					$this->save_main($param);
					break;
				case 'proses_perbaikan':
					$this->save_proses_perbaikan($param);
					break;
				case 'proses_perawatan':
					$this->save_proses_perawatan($param);
					break;
				case 'main_detail':
					$this->save_main_detail($param);
					break;
				case 'set_pic':
					$this->save_set_pic($param);
					break;
				case 'set_kondisi':
					$this->save_set_kondisi($param);
					break;
				case 'set_retire':
					$this->save_set_retire($param);
					break;
				case 'batal_retire':
					$this->save_batal_retire($param);
					break;
				case 'proses_retire':
					$this->save_proses_retire($param);
					break;
				case 'perbaikan':
					$this->save_perbaikan($param);
					break;
				case 'perbaikan_complete':
					$this->save_perbaikan_complete($param);
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
		private function get_kategori($array = NULL, $id_kategori = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL) {
			$kategori 		= $this->dtransaksiasset->get_data_kategori("open", $id_kategori, $active, $deleted, $nama, $pengguna);
			$kategori 		= $this->general->generate_encrypt_json($kategori, array("id_kategori"));
			if ($array) {
				return $kategori;
			} else {
				echo json_encode($kategori);
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
		private function get_jenis($array = NULL, $id_jenis = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $id_kategori = NULL, $kategori = NULL, $alat = NULL) {
			$jenis 		= $this->dtransaksiasset->get_data_jenis("open", $id_jenis, $active, $deleted, $nama, $pengguna, $id_kategori, $kategori, $alat);
			$jenis 		= $this->general->generate_encrypt_json($jenis, array("id_jenis"));
			if ($array) {
				return $jenis;
			} else {
				echo json_encode($jenis);
			}
		}
		private function get_user($array = NULL, $id_user = NULL, $active = NULL, $deleted = NULL) {
			$user 		= $this->dtransaksiasset->get_data_user("open", $id_user, $active, $deleted);
			$user 		= $this->general->generate_encrypt_json($user, array("id_user"));
			if ($array) {
				return $user;
			} else {
				echo json_encode($user);
			}
		}
		private function get_jenis_detail($array = NULL, $id_jenis_detail = NULL, $active = NULL, $deleted = NULL, $id_jenis = NULL) {
			$jenis_detail 		= $this->dtransaksiasset->get_data_jenis_detail("open", $id_jenis_detail, $active, $deleted, $id_jenis);
			$jenis_detail 		= $this->general->generate_encrypt_json($jenis_detail, array("id_jenis_detail"));
			if ($array) {
				return $jenis_detail;
			} else {
				echo json_encode($jenis_detail);
			}
		}
		private function get_merk($array = NULL, $id_merk = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $id_jenis = NULL, $jenis = NULL) {
			$merk = $this->dtransaksiasset->get_data_merk("open", $id_merk, $active, $deleted, $nama, $pengguna, $id_jenis, $jenis);
			$merk = $this->general->generate_encrypt_json($merk, array("id_merk"));
			if ($array) {
				return $merk;
			} else {
				echo json_encode($merk);
			}
		}
		private function get_sn_os($array = NULL, $id_kategori = NULL, $sn_os = NULL) {
			$sn_os = $this->dtransaksiasset->get_data_sn_os("open", $id_kategori, $sn_os);
			if ($array) {
				return $sn_os;
			} else {
				echo json_encode($sn_os);
			}
		}
		private function get_sn_office($array = NULL, $id_kategori = NULL, $sn_office = NULL) {
			$sn_office = $this->dtransaksiasset->get_data_sn_office("open", $id_kategori, $sn_office);
			if ($array) {
				return $sn_office;
			} else {
				echo json_encode($sn_office);
			}
		}
		private function get_merk_tipe($array = NULL, $id_merk_tipe = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $id_merk = NULL) {
			$merk_tipe = $this->dtransaksiasset->get_data_merk_tipe("open", $id_merk_tipe, $active, $deleted, $nama, $pengguna, $id_merk);
			$merk_tipe = $this->general->generate_encrypt_json($merk_tipe, array("id_merk_tipe"));
			if ($array) {
				return $merk_tipe;
			} else {
				echo json_encode($merk_tipe);
			}
		}
		private function get_periode($array = NULL, $id_periode = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $id_jenis = NULL, $squence = NULL, $pengguna = NULL) {
			$periode = $this->dtransaksiasset->get_data_periode("open", $id_periode, $active, $deleted, $nama, $id_jenis, $squence, $pengguna);
			$periode = $this->general->generate_encrypt_json($periode, array("id_periode"));
			if ($array) {
				return $periode;
			} else {
				echo json_encode($periode);
			}
		}
		private function get_aset_pic($array = NULL, $id_aset = NULL, $active = NULL, $deleted = NULL, $pic = NULL) {
			$aset_pic = $this->dtransaksiasset->get_data_aset_pic("open", $id_aset, $active, $deleted, $pic);
			// $aset_pic = $this->general->generate_encrypt_json($aset_pic, array("id_aset"));
			if ($array) {
				return $aset_pic;
			} else {
				echo json_encode($aset_pic);
			}
		}
		private function get_maintenance($array = NULL, $id_aset = NULL, $active = NULL, $deleted = NULL) {
			$maintenance = $this->dtransaksiasset->get_data_maintenance("open", $id_aset, $active, $deleted);
			$maintenance = $this->general->generate_encrypt_json($maintenance, array("id_maintenance"));
			if ($array) {
				return $maintenance;
			} else {
				echo json_encode($maintenance);
			}
		}
		private function get_periode_detail($array = NULL, $id_periode_detail = NULL, $active = NULL, $deleted = NULL, $id_periode = NULL, $id_jenis = NULL){
			$periode_detail = $this->dtransaksiasset->get_data_periode_detail("open", $id_periode_detail, $active, $deleted, $id_periode, $id_jenis);
			$periode_detail = $this->general->generate_encrypt_json($periode_detail, array("id_periode_detail"));
			if ($array) {
				return $periode_detail;
			} else {
				echo json_encode($periode_detail);
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
		private function get_sub_lokasi($array = NULL, $id_sub_lokasi = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $id_lokasi = NULL) {
			$sub_lokasi = $this->dtransaksiasset->get_data_sub_lokasi("open", $id_sub_lokasi, $active, $deleted, $nama, $id_lokasi);
			$sub_lokasi = $this->general->generate_encrypt_json($sub_lokasi, array("id_sub_lokasi"));
			if ($array) {
				return $sub_lokasi;
			} else {
				echo json_encode($sub_lokasi);
			}
		}
		private function get_area($array = NULL, $id_area = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $id_sub_lokasi = NULL, $lokasi = NULL) {
			$area = $this->dtransaksiasset->get_data_area("open", $id_area, $active, $deleted, $nama, $id_sub_lokasi, $lokasi);
			$area = $this->general->generate_encrypt_json($area, array("id_area"));
			if ($array) {
				return $area;
			} else {
				echo json_encode($area);
			}
		}
		private function get_cek($array = NULL, $tabel = NULL, $field = NULL, $value = NULL) {
			$cek = $this->dtransaksiasset->get_data_cek("open", $tabel, $field, $value);
			$cek = $this->general->generate_encrypt_json($cek,array($field));
			if ($array) {
				return $cek;
			} else {
				echo json_encode($cek);
			}
		}
		private function get_depo($array = NULL, $id_depo = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $id_pabrik = NULL, $id_lokasi = NULL) {
			$depo = $this->dtransaksiasset->get_data_depo("open", $id_depo, $active, $deleted, $nama, $pengguna, $id_pabrik, $id_lokasi);
			$depo = $this->general->generate_encrypt_json($depo, array("id_depo"));
			if ($array) {
				return $depo;
			} else {
				echo json_encode($depo);
			}
		}
		private function get_aset_bom($array = NULL, $id_aset = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $jenis=NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL) {
			$aset_bom 		= $this->dtransaksiasset->get_data_aset_bom("open", $id_aset, $active, $deleted, $nama, $pengguna, $jenis, $merk, $pabrik, $lokasi, $area);
			$aset_bom 		= $this->general->generate_encrypt_json($aset_bom, array("id_aset"));
			
			if ($array) {
				return $aset_bom;
			} else {
				echo json_encode($aset_bom);
			}
		}
		private function get_aset_bom_excel($array = NULL, $id_aset = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $jenis=NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $jam_mulai=NULL, $jam_selesai=NULL, $umur_mulai=NULL, $umur_selesai=NULL, $overdue=NULL, $kondisi=NULL, $status=NULL, $berat=NULL) {
			$aset_bom 		= $this->dtransaksiasset->get_data_aset_bom_excel("open", $id_aset, $active, $deleted, $nama, $pengguna, $jenis, $merk, $pabrik, $lokasi, $area, $jam_mulai, $jam_selesai, $umur_mulai, $umur_selesai, $overdue, $kondisi, $status, $berat);
			$aset_bom 		= $this->general->generate_encrypt_json($aset_bom, array("id_aset"));
			
			if ($array) {
				return $aset_bom;
			} else {
				echo json_encode($aset_bom);
			}
		}
		private function get_aset($array = NULL, $id_aset = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $jenis=NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $kondisi=NULL, $status=NULL, $ratio=NULL) {
			$aset 		= $this->dtransaksiasset->get_data_aset("open", $id_aset, $active, $deleted, $nama, $pengguna, $jenis, $merk, $pabrik, $lokasi, $area, $kondisi, $status,$ratio);
			$aset 		= $this->general->generate_encrypt_json($aset, array("id_aset","id_kategori","id_jenis","id_merk","id_merk_tipe","id_status","id_kondisi","id_pabrik","id_lokasi","id_sub_lokasi","id_area","id_kerusakan"));
			$jenis 		= $this->get_jenis("array",NULL,NULL,NULL,NULL,NULL, $this->generate->kirana_decrypt($aset[0]->id_kategori));
			$merk 		= $this->get_merk("array",NULL,NULL,NULL,NULL,NULL, $this->generate->kirana_decrypt($aset[0]->id_jenis));
			$merk_tipe 	= $this->get_merk_tipe("array",NULL,NULL,NULL,NULL,NULL, $this->generate->kirana_decrypt($aset[0]->id_merk));
			$sub_lokasi = $this->get_sub_lokasi("array",NULL,NULL,NULL,NULL, $this->generate->kirana_decrypt($aset[0]->id_lokasi));
			$area 		= $this->get_area("array",NULL,'n',NULL,NULL, $this->generate->kirana_decrypt($aset[0]->id_sub_lokasi));
			$periode 	= $this->get_periode("array",NULL,NULL,NULL,NULL, $this->generate->kirana_decrypt($aset[0]->id_jenis),$aset[0]->squence);
			$maintenance= $this->get_maintenance("array", $this->generate->kirana_decrypt($aset[0]->id_aset));
			$main		= $this->get_main("array", NULL, NULL, NULL, $this->generate->kirana_decrypt($aset[0]->id_aset),'n');
			$jenis_detail = $this->get_jenis_detail("array", NULL, NULL, NULL, $this->generate->kirana_decrypt($aset[0]->id_jenis));
			if(!empty($main)){
				$main_detail  = $this->get_main_detail("array", NULL, NULL, NULL, $this->generate->kirana_decrypt($main[0]->id_main));
			}
			
		
			$aset[0]->arr_jenis 	 		= $jenis;
			$aset[0]->arr_merk 		 		= $merk;
			$aset[0]->arr_merk_tipe  		= $merk_tipe;
			$aset[0]->arr_sub_lokasi 		= $sub_lokasi;
			$aset[0]->arr_area		 		= $area;
			$aset[0]->arr_periode	 		= $periode;
			$aset[0]->arr_maintenance 		= $maintenance;
			$aset[0]->arr_main		  		= $main;
			$aset[0]->arr_jenis_detail	 	= $jenis_detail;
			if(!empty($main)){
				$aset[0]->arr_main_detail	= $main_detail;
			}
			$sn_os		= $this->get_sn_os("array",$this->generate->kirana_decrypt($aset[0]->id_kategori), $aset[0]->SN_OS);
			$aset[0]->arr_sn_os = $sn_os;
			$sn_office	= $this->get_sn_office("array",$this->generate->kirana_decrypt($aset[0]->id_kategori), $aset[0]->sn_office);
			$aset[0]->arr_sn_office = $sn_office;
			
			if ($array) {
				return $aset;
			} else {
				echo json_encode($aset);
			}
		}
		private function get_detail($array = NULL, $id_main = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $jenis=NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $alat=NULL, $jam_mulai=NULL, $jam_selesai=NULL, $umur_mulai=NULL, $umur_selesai=NULL, $overdue=NULL) {
			$detail 	= $this->dtransaksiasset->get_data_detail("open", $id_main, $active, $deleted, $nama, $pengguna, $jenis, $merk, $pabrik, $lokasi, $area, $alat, $jam_mulai, $jam_selesai, $umur_mulai, $umur_selesai, $overdue);
			$detail		= $this->general->generate_encrypt_json($detail, array("id_main","id_aset","id_kategori","id_jenis","id_merk","id_merk_tipe","id_status","id_kondisi","id_pabrik","id_lokasi","id_sub_lokasi","id_area"));
			$main_detail= $this->get_main_detail("array",NULL,NULL,NULL,$id_main);
			
			//add arr
			$detail[0]->arr_main_detail	= $main_detail;
			
			if ($array) {
				return $detail;
			} else {
				echo json_encode($detail);
			}
		}
		private function get_main($array = NULL, $id_main = NULL, $active = NULL, $deleted = NULL, $id_aset = NULL, $final = NULL) {
			$main 	= $this->dtransaksiasset->get_data_main("open", $id_main, $active, $deleted, $id_aset, $final);
			$main	= $this->general->generate_encrypt_json($main, array("id_main"));
			if ($array) {
				return $main;
			} else {
				echo json_encode($main);
			}
		}
		private function get_main_detail($array = NULL, $id_main_detail = NULL, $active = NULL, $deleted = NULL, $id_main = NULL) {
			$main_detail 	= $this->dtransaksiasset->get_data_main_detail("open", $id_main_detail, $active, $deleted, $id_main);
			$main_detail	= $this->general->generate_encrypt_json($main_detail, array("id_main_detail"));
			if ($array) {
				return $main_detail;
			} else {
				echo json_encode($main_detail);
			}
		}
		private function get_email($array = NULL, $id_email = NULL, $active = NULL, $deleted = NULL, $id_karyawan = NULL) {
			$email 	= $this->dtransaksiasset->get_data_email("open", $id_email, $active, $deleted, $id_karyawan);
			$email	= $this->general->generate_encrypt_json($email, array("id_email","id_karyawan"));
			
			if ($array) {
				return $email;
			} else {
				echo json_encode($email);
			}
		}
		
		private function get_aset_temp($array = NULL, $id_aset = NULL, $active = NULL, $deleted = NULL, $nama = NULL, $pengguna = NULL, $jenis=NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $flag=NULL, $act=NULL, $proses=NULL, $id_aset_temp=NULL) {
			$aset_temp 		= $this->dtransaksiasset->get_data_aset_temp("open", $id_aset, $active, $deleted, $nama, $pengguna, $jenis, $merk, $pabrik, $lokasi, $area, $flag, $act, $proses, $id_aset_temp);
			$aset_temp 		= $this->general->generate_encrypt_json($aset_temp, array("id_aset","id_kategori","id_jenis","id_merk","id_merk_tipe","id_status","id_kondisi","id_pabrik","id_lokasi","id_sub_lokasi","id_area"));
			if((!empty($aset_temp[0]->id_jenis))and($act!=NULL)) {
				$merk 			= $this->get_merk("array",NULL,NULL,NULL,NULL,NULL, $this->generate->kirana_decrypt($aset_temp[0]->id_jenis));
				$merk_tipe 		= $this->get_merk_tipe("array",NULL,NULL,NULL,NULL,NULL, $this->generate->kirana_decrypt($aset_temp[0]->id_merk));
				$sub_lokasi 	= $this->get_sub_lokasi("array",NULL,NULL,NULL,NULL, $this->generate->kirana_decrypt($aset_temp[0]->id_lokasi));
				$area 			= $this->get_area("array",NULL,'n',NULL,NULL, $this->generate->kirana_decrypt($aset_temp[0]->id_sub_lokasi));
				//jika update
				$aset			= $this->dtransaksiasset->get_data_aset("array", $this->generate->kirana_decrypt($aset_temp[0]->id_aset));

				$aset_temp[0]->arr_merk 		= $merk;
				$aset_temp[0]->arr_merk_tipe  	= $merk_tipe;
				$aset_temp[0]->arr_sub_lokasi 	= $sub_lokasi;
				$aset_temp[0]->arr_area		 	= $area;
				$aset_temp[0]->arr_aset		 	= $aset;
			}

			
			if ($array) {
				return $aset_temp;
			} else {
				echo json_encode($aset_temp);
			}
		}
		private function get_dokumen_transaksi($array = NULL, $id_inv_doc_transaksi = NULL, $active = NULL, $deleted = NULL, $id_aset = NULL, $id_inv_doc = NULL) {
			$dokumen_transaksi = $this->dtransaksiasset->get_data_dokumen_transaksi("open", $id_inv_doc_transaksi, $active, $deleted, $id_aset, $id_inv_doc);
			$dokumen_transaksi = $this->general->generate_encrypt_json($dokumen_transaksi, array("id_inv_doc_transaksi","id_inv_doc"));
			if ($array) {
				return $dokumen_transaksi;
			} else {
				echo json_encode($dokumen_transaksi);
			}
		}
		private function get_dokumen($array = NULL, $id_inv_doc = NULL, $active = NULL, $deleted = NULL, $id_aset = NULL) {
			$dokumen = $this->dtransaksiasset->get_data_dokumen("open", $id_inv_doc, $active, $deleted, $id_aset);
			$dokumen = $this->general->generate_encrypt_json($dokumen, array("id_inv_doc"));
			if ($array) {
				return $dokumen;
			} else {
				echo json_encode($dokumen);
			}
		}
		
		private function save_aset($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$id_depo = (!empty($_POST['id_depo']))?$this->generate->kirana_decrypt($_POST['id_depo']):0;
			if (isset($_POST['id_aset']) && trim($_POST['id_aset']) !== "") {
				// if($param=='hrga'){
					// $aset = $this->get_aset('array', NULL, NULL, 'n', $_POST['nama']);	
				// }
				// if ((count($aset) > 0)and($aset[0]->id_aset!=$_POST['id_aset'])) {
					// $msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					// $sts    = "NotOK";
					// $return = array('sts' => $sts, 'msg' => $msg);
					// echo json_encode($return);
					// exit();
				// }
				if($param=='hrga'){
					//upload gambar depan
					if($_FILES['gambar_depan']['name'][0]!=''){
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
						$config['allowed_types'] 	= 'png|jpg';			
						$newname 					= array($_POST['plat'].'_'.$_POST['no_pol'].'_'.$_POST['bel_nomor_polisi'].'_DEPAN');			
						$gambar_depan				= $this->general->upload_files($_FILES['gambar_depan'], $newname, $config);
						$url_gambar_depan			= base_url().$gambar_depan[0]['url'];
						if($gambar_depan === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
					}else{
						$url_gambar_depan			= $_POST['hidden_gambar_depan'];
					}
					//upload gambar belakang
					if($_FILES['gambar_belakang']['name'][0]!=''){
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
						$config['allowed_types'] 	= 'png|jpg';			
						$newname 					= array($_POST['plat'].'_'.$_POST['no_pol'].'_'.$_POST['bel_nomor_polisi'].'_BELAKANG');			
						$gambar_belakang			= $this->general->upload_files($_FILES['gambar_belakang'], $newname, $config);
						$url_gambar_belakang		= base_url().$gambar_belakang[0]['url'];
						if($gambar_belakang === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
					}else{
						$url_gambar_belakang	= $_POST['hidden_gambar_belakang'];
					}
					//upload gambar kanan
					if($_FILES['gambar_kanan']['name'][0]!=''){
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
						$config['allowed_types'] 	= 'png|jpg';			
						$newname 					= array($_POST['plat'].'_'.$_POST['no_pol'].'_'.$_POST['bel_nomor_polisi'].'_KANAN');			
						$gambar_kanan				= $this->general->upload_files($_FILES['gambar_kanan'], $newname, $config);
						$url_gambar_kanan			= base_url().$gambar_kanan[0]['url'];
						if($gambar_kanan === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
					}else{
						$url_gambar_kanan	= $_POST['hidden_gambar_kanan'];
					}
					//upload gambar kiri
					if($_FILES['gambar_kiri']['name'][0]!=''){
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
						$config['allowed_types'] 	= 'png|jpg';			
						$newname 					= array($_POST['plat'].'_'.$_POST['no_pol'].'_'.$_POST['bel_nomor_polisi'].'_KIRI');			
						$gambar_kiri				= $this->general->upload_files($_FILES['gambar_kiri'], $newname, $config);
						$url_gambar_kiri			= base_url().$gambar_kiri[0]['url'];
						if($gambar_kiri === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
					}else{
						$url_gambar_kiri	= $_POST['hidden_gambar_kiri'];
					}
				}

				if($param=='fo'){
					// $id_jenis 	= $this->generate->kirana_decrypt($_POST['id_jenis']);
					// $id_pabrik	= $this->generate->kirana_decrypt($_POST['id_pabrik']);
					$nomor  	= $this->dtransaksiasset->get_data_nomor("open", NULL, NULL, 
									$this->generate->kirana_decrypt($_POST['id_aset']) );
					$nomor_asset= $nomor[0]->nomor;
					//upload gambar depan
					$cek_jenis 	 	 = $this->get_jenis('array', $this->generate->kirana_decrypt($_POST['id_jenis']));
					if($cek_jenis[0]->berat=='y'){
						if($_FILES['gambar']['name'][0]!=''){
							$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/fo';
							$config['allowed_types'] 	= 'png|jpg';			
							$newname 					= array($nomor_asset);			
							$gambar_fo					= $this->general->upload_files($_FILES['gambar'], 
															$newname, $config);
							$url_gambar_fo				= base_url().$gambar_fo[0]['url'];
							if($gambar_fo === NULL){
								$msg        = "Upload files error";
								$sts        = "NotOK";
								$return     = array('sts' => $sts, 'msg' => $msg);
								echo json_encode($return);
								exit();
							}
						}else{
							$url_gambar_fo = $_POST['hidden_gambar_fo'];
						}
					}
				}
			
				$data_row = array(
					//all asset
					"nomor_sap"      	 => $_POST['nomor_sap'],
					"id_jenis"       	 => $this->generate->kirana_decrypt($_POST['id_jenis']),
					"id_merk"        	 => (!empty($_POST['id_merk']))?
												$this->generate->kirana_decrypt($_POST['id_merk']):NULL,
					"id_merk_tipe"   	 => (!empty($_POST['id_merk_tipe']))?
												$this->generate->kirana_decrypt($_POST['id_merk_tipe']):NULL,
					"id_status"		 	 => (!empty($_POST['id_status']))?
												$this->generate->kirana_decrypt($_POST['id_status']):NULL,
					// "id_kondisi"  	 	 => (!empty($_POST['id_kondisi']))?$this->generate->kirana_decrypt($_POST['id_kondisi']):NULL,	//diganti di line 1380-1389
					"tanggal_perolehan"  => $_POST['tanggal_perolehan'],
					
					//lokasi	
					"id_pabrik"			 => $this->generate->kirana_decrypt($_POST['id_pabrik']),
					"id_lokasi"			 => $this->generate->kirana_decrypt($_POST['id_lokasi']),
					"id_sub_lokasi"		 => (!empty($_POST['id_sub_lokasi']))?
												$this->generate->kirana_decrypt($_POST['id_sub_lokasi']):NULL,
					"id_area"			 => (!empty($_POST['id_area']))?
												$this->generate->kirana_decrypt($_POST['id_area']):NULL,
					"id_depo"			 => $id_depo,
					"keterangan"     	 => (!empty($_POST['keterangan']))?$_POST['keterangan']:NULL
				);
				// if($param=='it'){
					// $id_kondisi = (!empty($_POST['id_kondisi']))?$this->generate->kirana_decrypt($_POST['id_kondisi']):NULL;
					// // if($id_kondisi!=2){		//diupdate jika kondisi non tidak beroperasi
						// // $cek_user 	 	 = $this->get_user('array', base64_decode($this->session->userdata("-id_user-")));
						// // $cek_jenis 	 	 = $this->get_jenis('array', $this->generate->kirana_decrypt($_POST['id_jenis']));
						// // if($cek_jenis[0]->keep_it=='n'){
							// // $data_row["id_kondisi"] = 6;	//stand by
							// // // $data_row["pic"]		= $cek_user[0]->id_karyawan;
							// // // $data_row["nama_user"]	= $cek_user[0]->nama;
						// // }else{
							// // $data_row["id_kondisi"] = 1;	//beroperasi
							// // // $data_row["pic"]		= $cek_user[0]->id_karyawan;
							// // // $data_row["nama_user"]	= $cek_user[0]->nama;
						// // }
					// // }
				// }else{
					// $data_row["id_kondisi"] = (!empty($_POST['id_kondisi']))?
					// $this->generate->kirana_decrypt($_POST['id_kondisi']):NULL;
				// }
				
				if($param=='hrga'){
					//hrga
					$data_row["id_kategori"] 	 = 6;
					$data_row["plat"] 			 = $_POST['plat'];
					$data_row["tahun_pembuatan"] = $_POST['tahun_pembuatan'];
					$data_row["pic"]	 		 = implode(",", $_POST['pic']);
					$data_row["no_pol"] 		 = $_POST['no_pol'];
					$data_row["bel_nomor_polisi"]= $_POST['bel_nomor_polisi'];
					$data_row["nomor_polisi"] 	 = $_POST['plat'].' '.$_POST['no_pol'].' '.$_POST['bel_nomor_polisi'];
					$data_row["nomor_rangka"] 	 = $_POST['nomor_rangka'];
					$data_row["nomor_mesin"] 	 = $_POST['nomor_mesin'];
					$data_row["tipe_aset"] 		 = $_POST['tipe_aset'];
					$data_row["gambar_depan"] 	 = $url_gambar_depan;
					$data_row["gambar_belakang"] = $url_gambar_belakang;
					$data_row["gambar_kanan"] 	 = $url_gambar_kanan;
					$data_row["gambar_kiri"] 	 = $url_gambar_kiri;
				}
				if($param=='it'){
					//it
					$nama_user   		= (isset($_POST['nama_user']) ? $_POST['nama_user'] : NULL);
					$kode_barang   		= (isset($_POST['kode_barang']) ? $_POST['kode_barang'] : NULL);
					$nama_vendor   		= (isset($_POST['nama_vendor']) ? $_POST['nama_vendor'] : NULL);
					$ip_address   		= (isset($_POST['ip_address']) ? $_POST['ip_address'] : NULL);
					$os   				= (isset($_POST['os']) ? $_POST['os'] : NULL);
					// $sn_os   			= (isset($_POST['sn_os']) ? $_POST['sn_os'] : NULL);
					$office_apps   		= (isset($_POST['office_apps']) ? $_POST['office_apps'] : NULL);
					$mac_address   		= (isset($_POST['mac_address']) ? $_POST['mac_address'] : NULL);
					$tipe_processor   	= (isset($_POST['tipe_processor']) ? $_POST['tipe_processor'] : NULL);
					$processor_series   = (isset($_POST['processor_series']) ? $_POST['processor_series'] : NULL);
					$processor_spec   	= (isset($_POST['processor_spec']) ? $_POST['processor_spec'] : NULL);
					$ram   				= (isset($_POST['ram']) ? $_POST['ram'] : NULL);
					$hdd   				= (isset($_POST['hdd']) ? $_POST['hdd'] : NULL);
					$merk_monitor   	= (isset($_POST['merk_monitor']) ? $_POST['merk_monitor'] : NULL);
					$ukuran_monitor   	= (isset($_POST['ukuran_monitor']) ? $_POST['ukuran_monitor'] : NULL);
					//cr 2241
					$lisensi_os   		= (isset($_POST['lisensi_os']) ? "y": "n");
					$sn_os   			= (isset($_POST['sn_os']) ? $_POST['sn_os'] : NULL);
					$lisensi_office   	= (isset($_POST['lisensi_office']) ? "y": "n");
					$sn_office   		= (isset($_POST['sn_office']) ? $_POST['sn_office'] : NULL);
					//cr 2452
					$sticker_label   	= (isset($_POST['sticker_label']) ? "y": "n");
					
					$id_kondisi = (isset($_POST['id_kondisi']) ? $this->generate->kirana_decrypt($_POST['id_kondisi']) : NULL);
					$data_row["id_kondisi"] 	 = $id_kondisi;
					
					
					$data_row["id_kategori"] 	 = $this->generate->kirana_decrypt($_POST['id_kategori']);
					// $data_row["pic"]	 		 = $_POST['pic'];
					// $data_row["nama_user"]		 = $nama_user;
					$data_row["kode_barang"] 	 = $kode_barang;
					$data_row["nama_vendor"] 	 = $nama_vendor;
					$data_row["ip_address"] 	 = $ip_address;
					$data_row["os"] 			 = $os;
					// $data_row["sn_os"] 	 		 = $sn_os;
					$data_row["office_apps"] 	 = $office_apps;
					$data_row["mac_address"] 	 = $mac_address;
					$data_row["tipe_processor"]  = $tipe_processor;
					$data_row["processor_series"]= $processor_series;
					$data_row["processor_spec"]  = $processor_spec;
					$data_row["ram"] 	 		 = $ram;
					$data_row["hdd"] 	 		 = $hdd;
					$data_row["merk_monitor"] 	 = $merk_monitor;
					$data_row["ukuran_monitor"]  = $ukuran_monitor;
					//cr 2241
					$data_row["lisensi_os"]  	 = $lisensi_os;
					$data_row["sn_os"] 			 = $sn_os;
					$data_row["lisensi_office"]  = $lisensi_office;
					$data_row["sn_office"] 		 = $sn_office;
					//cr 2452
					$data_row["sticker_label"]   = $sticker_label;
				}
				if($param=='fo'){
					//fo
					$id_kondisi = (isset($_POST['id_kondisi']) ? $this->generate->kirana_decrypt($_POST['id_kondisi']) : NULL);
					$data_row["id_kondisi"] 	 = $id_kondisi;
					
					$data_row["id_kategori"] 	 = $this->generate->kirana_decrypt($_POST['id_kategori']);
					$data_row["tahun_pembuatan"] = (!empty($_POST['tahun_pembuatan']))?
														$_POST['tahun_pembuatan']:NULL;
					$data_row["spesifikasi"]	 = (!empty($_POST['spesifikasi']))?$_POST['spesifikasi']:NULL;
					$data_row["id_satuan"] 		 = (!empty($_POST['id_satuan']))?$_POST['id_satuan']:NULL;
					$data_row["nomor_rangka"]	 = (!empty($_POST['nomor_rangka']))?
														$_POST['nomor_rangka']:NULL;
					$data_row["nomor_mesin"]	 = (!empty($_POST['nomor_mesin']))?$_POST['nomor_mesin']:NULL;
					$data_row["aksesoris1"]	 	 = (!empty($_POST['aksesoris1']))?$_POST['aksesoris1']:NULL;
					$data_row["aksesoris2"]	 	 = (!empty($_POST['aksesoris2']))?$_POST['aksesoris2']:NULL;
					$data_row["ratio"]	 	 	 = (!empty($_POST['ratio']))?$_POST['ratio']:NULL;
					$data_row["id_kerusakan"]	 = (!empty($_POST['id_jenis_kerusakan']))?
														$this->generate->kirana_decrypt($_POST['id_jenis_kerusakan']):NULL;
					$data_row["gambar_fo"]	 	 = (!empty($url_gambar_fo))?$url_gambar_fo:NULL;
					
				}
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				if ((base64_decode($this->session->userdata("-ho-")) !== 'y')and($param!='fo')) {
					$data_row["id_aset"] 	= $this->generate->kirana_decrypt($_POST['id_aset']);
					$data_row["flag"]	 	= 'menunggu';
					$data_row["proses"] 	= 'update';
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_inv_aset_temp", $data_row);
					
				}else{
					$this->dgeneral->update("tbl_inv_aset", $data_row, array(
						array(
							'kolom' => 'id_aset',
							'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
						)
					));
				}

/*input*/	} else {	//input
				// $aset = $this->get_aset('array', NULL, NULL, 'n', $_POST['nama']);
				// if (count($aset) > 0) {
					// $msg    = "Duplicate data, periksa kembali data yang dimasukkan";
					// $sts    = "NotOK";
					// $return = array('sts' => $sts, 'msg' => $msg);
					// echo json_encode($return);
					// exit();
				// }
				
				//upload gambar depan
				if($param=='hrga'){
					if($_FILES['gambar_depan']['name'][0]!=''){
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
						$config['allowed_types'] 	= 'png|jpg';			
						$newname 					= array($_POST['plat'].'_'.$_POST['no_pol'].'_'.$_POST['bel_nomor_polisi'].'_DEPAN');			
						$gambar_depan				= $this->general->upload_files($_FILES['gambar_depan'], $newname, $config);
						$url_gambar_depan			= base_url().$gambar_depan[0]['url'];
						if($gambar_depan === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
					}else{
						$url_gambar_depan					= $_POST['hidden_gambar_depan'];
					}
					//upload gambar belakang
					if($_FILES['gambar_belakang']['name'][0]!=''){
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
						$config['allowed_types'] 	= 'png|jpg';			
						$newname 					= array($_POST['plat'].'_'.$_POST['no_pol'].'_'.$_POST['bel_nomor_polisi'].'_BELAKANG');			
						$gambar_belakang			= $this->general->upload_files($_FILES['gambar_belakang'], $newname, $config);
						$url_gambar_belakang		= base_url().$gambar_belakang[0]['url'];
						if($gambar_belakang === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
					}else{
						$url_gambar_belakang	= $_POST['hidden_gambar_belakang'];
					}
					//upload gambar kanan
					if($_FILES['gambar_kanan']['name'][0]!=''){
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
						$config['allowed_types'] 	= 'png|jpg';			
						$newname 					= array($_POST['plat'].'_'.$_POST['no_pol'].'_'.$_POST['bel_nomor_polisi'].'_KANAN');			
						$gambar_kanan				= $this->general->upload_files($_FILES['gambar_kanan'], $newname, $config);
						$url_gambar_kanan			= base_url().$gambar_kanan[0]['url'];
						if($gambar_kanan === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
					}else{
						$url_gambar_kanan	= $_POST['hidden_gambar_kanan'];
					}
					//upload gambar kiri
					if($_FILES['gambar_kiri']['name'][0]!=''){
						$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
						$config['allowed_types'] 	= 'png|jpg';			
						$newname 					= array($_POST['plat'].'_'.$_POST['no_pol'].'_'.$_POST['bel_nomor_polisi'].'_KIRI');			
						$gambar_kiri				= $this->general->upload_files($_FILES['gambar_kiri'], $newname, $config);
						$url_gambar_kiri			= base_url().$gambar_kiri[0]['url'];
						if($gambar_kiri === NULL){
							$msg        = "Upload files error";
							$sts        = "NotOK";
							$return     = array('sts' => $sts, 'msg' => $msg);
							echo json_encode($return);
							exit();
						}
					}else{
						$url_gambar_kiri	= $_POST['hidden_gambar_kiri'];
					}
				}
				if($param=='fo'){
					$id_jenis 	= $this->generate->kirana_decrypt($_POST['id_jenis']);
					$id_pabrik	= $this->generate->kirana_decrypt($_POST['id_pabrik']);
					$nomor  	= $this->dtransaksiasset->get_data_nomor("open", $id_jenis, $id_pabrik);
					$nomor_asset= $nomor[0]->kode_pabrik.'-'.$nomor[0]->kode_jenis.'-'.$nomor[0]->nomor;
					
					//upload gambar depan
					$cek_jenis 	 	 = $this->get_jenis('array', $this->generate->kirana_decrypt($_POST['id_jenis']));
					if($cek_jenis[0]->berat=='y'){
						if($_FILES['gambar']['name'][0]!=''){
							$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/fo';
							$config['allowed_types'] 	= 'png|jpg';			
							$newname 					= array($nomor_asset);			
							$gambar_fo					= $this->general->upload_files($_FILES['gambar'], 
															$newname, $config);
							$url_gambar_fo				= base_url().$gambar_fo[0]['url'];
							if($gambar_fo === NULL){
								$msg        = "Upload files error";
								$sts        = "NotOK";
								$return     = array('sts' => $sts, 'msg' => $msg);
								echo json_encode($return);
								exit();
							}
						}else{
							$url_gambar_fo = $_POST['hidden_gambar_fo'];
						}
					}
				}
				$data_row = array(
					//all asset
					"nomor_sap"      	 => $_POST['nomor_sap'],
					"id_jenis"       	 => $this->generate->kirana_decrypt($_POST['id_jenis']),
					"id_merk"        	 => $this->generate->kirana_decrypt($_POST['id_merk']),
					"id_merk_tipe"   	 => $this->generate->kirana_decrypt($_POST['id_merk_tipe']),
					"id_status"		 	 => $this->generate->kirana_decrypt($_POST['id_status']),
					// "id_kondisi"  	 	 => $this->generate->kirana_decrypt($_POST['id_kondisi']),	//diganti di baris 1569-1578
					"tanggal_perolehan"  => $_POST['tanggal_perolehan'],
					//lokasi	
					"id_pabrik"			 => $this->generate->kirana_decrypt($_POST['id_pabrik']),
					"id_lokasi"			 => $this->generate->kirana_decrypt($_POST['id_lokasi']),
					"id_sub_lokasi"		 => $this->generate->kirana_decrypt($_POST['id_sub_lokasi']),
					"id_area"			 => $this->generate->kirana_decrypt($_POST['id_area']),
					"id_depo"			 => $id_depo,
					"keterangan"     	 => $_POST['keterangan']
				);
				if($param=='it'){
					$cek_user 	 	 = $this->get_user('array', base64_decode($this->session->userdata("-id_user-")));
					$cek_jenis 	 	 = $this->get_jenis('array', $this->generate->kirana_decrypt($_POST['id_jenis']));
					if($cek_jenis[0]->keep_it=='n'){
						$data_row["id_kondisi"] = 6;	//stand by
						$data_row["pic"]		= $cek_user[0]->id_karyawan;
						$data_row["nama_user"]	= $cek_user[0]->nama;
					}else{
						$data_row["id_kondisi"] = 1;	//beroperasi
						$data_row["pic"]		= $cek_user[0]->id_karyawan;
						$data_row["nama_user"]	= $cek_user[0]->nama;
					}
				}else{
					$data_row["id_kondisi"] = (!empty($_POST['id_kondisi']))?
												$this->generate->kirana_decrypt($_POST['id_kondisi']):NULL;
				}
				
				if($param=='hrga'){
					//hrga
					$id_jenis 	= $this->generate->kirana_decrypt($_POST['id_jenis']);
					$id_pabrik	= $this->generate->kirana_decrypt($_POST['id_pabrik']);
					$nomor  	= $this->dtransaksiasset->get_data_nomor("open", $id_jenis, $id_pabrik);
					$nomor_asset= $nomor[0]->kode_pabrik.'-'.$nomor[0]->kode_jenis.'-'.$nomor[0]->nomor;
					
					$data_row["nomor"] 			 = $nomor_asset;
					$data_row["id_kategori"] 	 = 6;
					$data_row["pic"]	 		 = is_array($_POST['pic']) ? implode(",", $_POST['pic']) : $_POST['pic'];
			
					$data_row["plat"] 			 = $_POST['plat'];
					$data_row["tahun_pembuatan"] = $_POST['tahun_pembuatan'];
					$data_row["no_pol"] 		 = $_POST['no_pol'];
					$data_row["bel_nomor_polisi"]= $_POST['bel_nomor_polisi'];
					$data_row["nomor_polisi"] 	 = $_POST['plat'].' '.$_POST['no_pol'].' '.$_POST['bel_nomor_polisi'];
					$data_row["nomor_rangka"] 	 = $_POST['nomor_rangka'];
					$data_row["nomor_mesin"] 	 = $_POST['nomor_mesin'];
					$data_row["tipe_aset"] 		 = $_POST['tipe_aset'];
					$data_row["gambar_depan"] 	 = $url_gambar_depan;
					$data_row["gambar_belakang"] = $url_gambar_belakang;
					$data_row["gambar_kanan"] 	 = $url_gambar_kanan;
					$data_row["gambar_kiri"] 	 = $url_gambar_kiri;
				}
				if($param=='it'){
					//it
					$nama_user   		= (isset($_POST['nama_user']) ? $_POST['nama_user'] : NULL);
					$kode_barang   		= (isset($_POST['kode_barang']) ? $_POST['kode_barang'] : NULL);
					$nama_vendor   		= (isset($_POST['nama_vendor']) ? $_POST['nama_vendor'] : NULL);
					$ip_address   		= (isset($_POST['ip_address']) ? $_POST['ip_address'] : NULL);
					$os   				= (isset($_POST['os']) ? $_POST['os'] : NULL);
					// $sn_os   			= (isset($_POST['sn_os']) ? $_POST['sn_os'] : NULL);
					$office_apps   		= (isset($_POST['office_apps']) ? $_POST['office_apps'] : NULL);
					$mac_address   		= (isset($_POST['mac_address']) ? $_POST['mac_address'] : NULL);
					$tipe_processor   	= (isset($_POST['tipe_processor']) ? $_POST['tipe_processor'] : NULL);
					$processor_series   = (isset($_POST['processor_series']) ? $_POST['processor_series'] : NULL);
					$processor_spec   	= (isset($_POST['processor_spec']) ? $_POST['processor_spec'] : NULL);
					$ram   				= (isset($_POST['ram']) ? $_POST['ram'] : NULL);
					$hdd   				= (isset($_POST['hdd']) ? $_POST['hdd'] : NULL);
					$merk_monitor   	= (isset($_POST['merk_monitor']) ? $_POST['merk_monitor'] : NULL);
					$ukuran_monitor   	= (isset($_POST['ukuran_monitor']) ? $_POST['ukuran_monitor'] : NULL);
					//cr 2241
					$lisensi_os   		= (isset($_POST['lisensi_os']) ? "y": "n");
					$sn_os   			= (isset($_POST['sn_os']) ? $_POST['sn_os'] : NULL);
					$lisensi_office   	= (isset($_POST['lisensi_office']) ? "y": "n");
					$sn_office   		= (isset($_POST['sn_office']) ? $_POST['sn_office'] : NULL);
					//cr 2452
					$sticker_label   	= (isset($_POST['sticker_label']) ? "y": "n");
					
					$id_jenis 	= $this->generate->kirana_decrypt($_POST['id_jenis']);
					$id_pabrik	= $this->generate->kirana_decrypt($_POST['id_pabrik']);
					$nomor  	= $this->dtransaksiasset->get_data_nomor("open", $id_jenis, $id_pabrik);
					$nomor_asset= $nomor[0]->kode_pabrik.'-'.$nomor[0]->kode_jenis.'-'.$nomor[0]->nomor;
					
					$data_row["nomor"] 			 = $nomor_asset;
					$data_row["id_kategori"] 	 = $this->generate->kirana_decrypt($_POST['id_kategori']);
					// $data_row["pic"]	 		 = $_POST['pic'];
					// $data_row["nama_user"]		 = $nama_user;
					$data_row["kode_barang"] 	 = $kode_barang;
					$data_row["nama_vendor"] 	 = $nama_vendor;
					$data_row["ip_address"] 	 = $ip_address;
					$data_row["os"] 			 = $os;
					// $data_row["sn_os"] 	 		 = $sn_os;
					$data_row["office_apps"] 	 = $office_apps;
					$data_row["mac_address"] 	 = $mac_address;
					$data_row["tipe_processor"]  = $tipe_processor;
					$data_row["processor_series"]= $processor_series;
					$data_row["processor_spec"]  = $processor_spec;
					$data_row["ram"] 	 		 = $ram;
					$data_row["hdd"] 	 		 = $hdd;
					$data_row["merk_monitor"] 	 = $merk_monitor;
					$data_row["ukuran_monitor"]  = $ukuran_monitor;
					//cr 2241
					$data_row["lisensi_os"]  	 = $lisensi_os;
					$data_row["sn_os"] 			 = $sn_os;
					$data_row["lisensi_office"]  = $lisensi_office;
					$data_row["sn_office"] 		 = $sn_office;
					//cr 2452
					$data_row["sticker_label"]   = $sticker_label;
				}
				if($param=='fo'){
					//fo
					$id_jenis 	= $this->generate->kirana_decrypt($_POST['id_jenis']);
					$id_pabrik	= $this->generate->kirana_decrypt($_POST['id_pabrik']);
					$nomor  	= $this->dtransaksiasset->get_data_nomor("open", $id_jenis, $id_pabrik);
					$nomor_asset= $nomor[0]->kode_pabrik.'-'.$nomor[0]->kode_jenis.'-'.$nomor[0]->nomor;
					
					$id_kondisi = (isset($_POST['id_kondisi']) ? $this->generate->kirana_decrypt($_POST['id_kondisi']) : NULL);
					$data_row["id_kondisi"] 	 = $id_kondisi;
					$data_row["nomor"] 			 = $nomor_asset;
					$data_row["id_kategori"] 	 = $this->generate->kirana_decrypt($_POST['id_kategori']);
					$data_row["tahun_pembuatan"] = $_POST['tahun_pembuatan'];
					$data_row["spesifikasi"]	 = $_POST['spesifikasi'];
					$data_row["id_satuan"] 		 = $_POST['id_satuan'];
					$data_row["nomor_rangka"]	 = $_POST['nomor_rangka'];
					$data_row["nomor_mesin"]	 = $_POST['nomor_mesin'];
					$data_row["aksesoris1"]	 	 = $_POST['aksesoris1'];
					$data_row["aksesoris2"]	 	 = $_POST['aksesoris2'];
					$data_row["ratio"]	 	 	 = (!empty($_POST['ratio']))?$_POST['ratio']:NULL;
					$data_row["id_kerusakan"]	 = (!empty($_POST['id_jenis_kerusakan']))?
														$this->generate->kirana_decrypt($_POST['id_jenis_kerusakan']):NULL;
					$data_row["gambar_fo"]	 	 = (!empty($url_gambar_fo))?$url_gambar_fo:NULL;
				}
				
				if ((base64_decode($this->session->userdata("-ho-")) !== 'y')and($param!='fo')) {	//jika bukan HO masuk tabel temp
					$aset_temp = $this->get_aset_temp("open");
					$data_row["id_aset"] 	= $aset_temp[0]->jumlah+1;
					$data_row["flag"]	 	= 'menunggu';
					$data_row["proses"] 	= 'input';
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_inv_aset_temp", $data_row);
				}else{	//jika HO dan asset FO masuk tabel utama
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_inv_aset", $data_row);	
				}
				
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
		
		private function save_approval($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$id_depo = (!empty($_POST['id_depo']))?$this->generate->kirana_decrypt($_POST['id_depo']):0;
			$data_row = array(
				//all asset
				"nomor_sap"      	 => $_POST['nomor_sap'],
				"id_jenis"       	 => $this->generate->kirana_decrypt($_POST['id_jenis']),
				"id_merk"        	 => $this->generate->kirana_decrypt($_POST['id_merk']),
				"id_merk_tipe"   	 => $this->generate->kirana_decrypt($_POST['id_merk_tipe']),
				"id_status"		 	 => $this->generate->kirana_decrypt($_POST['id_status']),
				
				"tanggal_perolehan"  => $_POST['tanggal_perolehan'],
				
				//lokasi	
				"id_pabrik"			 => $this->generate->kirana_decrypt($_POST['id_pabrik']),
				"id_lokasi"			 => $this->generate->kirana_decrypt($_POST['id_lokasi']),
				"id_sub_lokasi"		 => $this->generate->kirana_decrypt($_POST['id_sub_lokasi']),
				"id_area"			 => $this->generate->kirana_decrypt($_POST['id_area']),
				"id_depo"			 => $id_depo,
				"keterangan"     	 => $_POST['keterangan']
			);
			if($param=='hrga'){
				//hrga
				$data_row["id_kondisi"] 	 = $this->generate->kirana_decrypt($_POST['id_kondisi']);
				$data_row["id_kategori"] 	 = 6;
				$data_row["pic"] 			 = implode(",", $_POST['pic']);
				$data_row["plat"] 			 = $_POST['plat'];
				$data_row["tahun_pembuatan"] = $_POST['tahun_pembuatan'];
				$data_row["no_pol"] 		 = $_POST['no_pol'];
				$data_row["bel_nomor_polisi"]= $_POST['bel_nomor_polisi'];
				$data_row["nomor_polisi"] 	 = $_POST['plat'].' '.$_POST['no_pol'].' '.$_POST['bel_nomor_polisi'];
				$data_row["nomor_rangka"] 	 = $_POST['nomor_rangka'];
				$data_row["nomor_mesin"] 	 = $_POST['nomor_mesin'];
				$data_row["tipe_aset"] 		 = $_POST['tipe_aset'];
				$data_row["gambar_depan"] 	 = $url_gambar_depan;
				$data_row["gambar_belakang"] = $url_gambar_belakang;
				$data_row["gambar_kanan"] 	 = $url_gambar_kanan;
				$data_row["gambar_kiri"] 	 = $url_gambar_kiri;
			}
			if($param=='it'){
				$id_jenis 	= $this->generate->kirana_decrypt($_POST['id_jenis']);
				$id_pabrik	= $this->generate->kirana_decrypt($_POST['id_pabrik']);
				$nomor  	= $this->dtransaksiasset->get_data_nomor("open", $id_jenis, $id_pabrik);
				$nomor_asset= $nomor[0]->kode_pabrik.'-'.$nomor[0]->kode_jenis.'-'.$nomor[0]->nomor;
				
				//it xxx
				$data_row["nomor"] 	 		 = $nomor_asset;
				$data_row["id_kondisi"] 	 = 6;	//set stand by
				$data_row["id_kategori"] 	 = $this->generate->kirana_decrypt($_POST['id_kategori']);
				// $data_row["pic"] 			 = $_POST['pic'];
				$data_row["kode_barang"] 	 = $_POST['kode_barang'];
				$data_row["nama_vendor"] 	 = $_POST['nama_vendor'];
				$data_row["ip_address"] 	 = $_POST['ip_address'];
				$data_row["os"] 			 = $_POST['os'];
				$data_row["sn_os"] 	 		 = $_POST['sn_os'];
				$data_row["office_apps"] 	 = $_POST['office_apps'];
				$data_row["mac_address"] 	 = $_POST['mac_address'];
				$data_row["tipe_processor"]  = $_POST['tipe_processor'];
				$data_row["processor_series"]= $_POST['processor_series'];
				$data_row["processor_spec"]  = $_POST['processor_spec'];
				$data_row["ram"] 	 		 = $_POST['ram'];
				$data_row["hdd"] 	 		 = $_POST['hdd'];
				$data_row["merk_monitor"] 	 = $_POST['merk_monitor'];
				$data_row["ukuran_monitor"]  = $_POST['ukuran_monitor'];
			}

			//add
			if(empty($_POST['id_aset'])){
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_inv_aset", $data_row);	
			}
			//perubahan
			if(!empty($_POST['id_aset'])){
				$this->dgeneral->update("tbl_inv_aset", $data_row, array(
					array(
					'kolom' => 'id_aset',
					'value' => $_POST['id_aset']
					)
				));
			}
	
			
			//UPDATE tbl_inv_aset_temp
			$data_temp["flag"]	 	= 'proses';
			$data_temp["komentar"] 	= $_POST['komentar'];
			$data_temp = $this->dgeneral->basic_column("update", $data_temp);
			$this->dgeneral->update("tbl_inv_aset_temp", $data_temp, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset_temp'])
				)
			));

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
		private function save_reject($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			//UPDATE tbl_inv_aset_temp
			$data_temp["flag"]	 	= 'batal';
			$data_temp["komentar"] 	= $_POST['komentar'];
			$data_temp = $this->dgeneral->basic_column("update", $data_temp);
			$this->dgeneral->update("tbl_inv_aset_temp", $data_temp, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset_temp'])
				)
			));

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
		
		// private function save_approval_aset($param) {
			// $datetime = date("Y-m-d H:i:s");
			// $this->general->connectDbPortal();
			// $this->dgeneral->begin_transaction();
			// $id_depo = (!empty($_POST['id_depo']))?$this->generate->kirana_decrypt($_POST['id_depo']):0;
			// $data_row = array(
				// //all asset
				// "nomor_sap"      	 => $_POST['nomor_sap'],
				// "id_jenis"       	 => $this->generate->kirana_decrypt($_POST['id_jenis']),
				// "id_merk"        	 => $this->generate->kirana_decrypt($_POST['id_merk']),
				// "id_merk_tipe"   	 => $this->generate->kirana_decrypt($_POST['id_merk_tipe']),
				// "id_status"		 	 => $this->generate->kirana_decrypt($_POST['id_status']),
				// "id_kondisi"  	 	 => $this->generate->kirana_decrypt($_POST['id_kondisi']),
				// "tanggal_perolehan"  => $_POST['tanggal_perolehan'],
				// "pic"  	 			 => implode(",", $_POST['pic']),
				// //lokasi	
				// "id_pabrik"			 => $this->generate->kirana_decrypt($_POST['id_pabrik']),
				// "id_lokasi"			 => $this->generate->kirana_decrypt($_POST['id_lokasi']),
				// "id_sub_lokasi"		 => $this->generate->kirana_decrypt($_POST['id_sub_lokasi']),
				// "id_area"			 => $this->generate->kirana_decrypt($_POST['id_area']),
				// "id_depo"			 => $id_depo,
				// "keterangan"     	 => $_POST['keterangan']
			// );
			// if($param2=='hrga'){
				// //hrga
				// $data_row["id_kategori"] 	 = 6;
				// $data_row["plat"] 			 = $_POST['plat'];
				// $data_row["tahun_pembuatan"] = $_POST['tahun_pembuatan'];
				// $data_row["no_pol"] 		 = $_POST['no_pol'];
				// $data_row["bel_nomor_polisi"]= $_POST['bel_nomor_polisi'];
				// $data_row["nomor_polisi"] 	 = $_POST['plat'].' '.$_POST['no_pol'].' '.$_POST['bel_nomor_polisi'];
				// $data_row["nomor_rangka"] 	 = $_POST['nomor_rangka'];
				// $data_row["nomor_mesin"] 	 = $_POST['nomor_mesin'];
				// $data_row["tipe_aset"] 		 = $_POST['tipe_aset'];
				// $data_row["gambar_depan"] 	 = $url_gambar_depan;
				// $data_row["gambar_belakang"] = $url_gambar_belakang;
				// $data_row["gambar_kanan"] 	 = $url_gambar_kanan;
				// $data_row["gambar_kiri"] 	 = $url_gambar_kiri;
			// }
			// if($param2=='it'){
				// //it
				// $data_row["id_kategori"] 	 = $_POST['id_kategori'];
				// $data_row["kode_barang"] 	 = $_POST['kode_barang'];
				// $data_row["nama_vendor"] 	 = $_POST['nama_vendor'];
				// $data_row["ip_address"] 	 = $_POST['ip_address'];
				// $data_row["os"] 			 = $_POST['os'];
				// $data_row["sn_os"] 	 		 = $_POST['sn_os'];
				// $data_row["office_apps"] 	 = $_POST['office_apps'];
				// $data_row["mac_address"] 	 = $_POST['mac_address'];
				// $data_row["tipe_processor"]  = $_POST['tipe_processor'];
				// $data_row["processor_series"]= $_POST['processor_series'];
				// $data_row["processor_spec"]  = $_POST['processor_spec'];
				// $data_row["ram"] 	 		 = $_POST['ram'];
				// $data_row["hdd"] 	 		 = $_POST['hdd'];
				// $data_row["merk_monitor"] 	 = $_POST['merk_monitor'];
				// $data_row["ukuran_monitor"]  = $_POST['ukuran_monitor'];
			// }

			// //add
			// if(empty($_POST['id_aset'])){
				// $data_row = $this->dgeneral->basic_column("insert", $data_row);
				// $this->dgeneral->insert("tbl_inv_aset", $data_row);	
			// }
			// //perubahan
			// if(!empty($_POST['id_aset'])){
				// $this->dgeneral->update("tbl_inv_aset", $data_row, array(
					// array(
					// 'kolom' => 'id_aset',
					// 'value' => $_POST['id_aset']
					// )
				// ));
			// }
	
			
			// //UPDATE tbl_inv_aset_temp
			// $data_temp["flag"]	 	= 'proses';
			// $data_temp["komentar"] 	= $_POST['komentar'];
			// $data_temp = $this->dgeneral->basic_column("update", $data_temp);
			// $this->dgeneral->update("tbl_inv_aset_temp", $data_temp, array(
				// array(
					// 'kolom' => 'id_aset',
					// 'value' => $this->generate->kirana_decrypt($_POST['id_aset_temp'])
				// )
			// ));

			// if ($this->dgeneral->status_transaction() === false) {
				// $this->dgeneral->rollback_transaction();
				// $msg = "Periksa kembali data yang dimasukkan";
				// $sts = "NotOK";
			// } else {
				// $this->dgeneral->commit_transaction();
				// $msg = "Data berhasil ditambahkan";
				// $sts = "OK";
			// }
			// $this->general->closeDb();
			// $return = array('sts' => $sts, 'msg' => $msg);
			// echo json_encode($return);
		// }
		
		// private function save_reject_aset($param) {
			// $datetime = date("Y-m-d H:i:s");
			// $this->general->connectDbPortal();
			// $this->dgeneral->begin_transaction();
			// //UPDATE tbl_inv_aset_temp
			// $data_temp["flag"]	 	= 'batal';
			// $data_temp["komentar"] 	= $_POST['komentar'];
			// $data_temp = $this->dgeneral->basic_column("update", $data_temp);
			// $this->dgeneral->update("tbl_inv_aset_temp", $data_temp, array(
				// array(
					// 'kolom' => 'id_aset',
					// 'value' => $this->generate->kirana_decrypt($_POST['id_aset_temp'])
				// )
			// ));

			// if ($this->dgeneral->status_transaction() === false) {
				// $this->dgeneral->rollback_transaction();
				// $msg = "Periksa kembali data yang dimasukkan";
				// $sts = "NotOK";
			// } else {
				// $this->dgeneral->commit_transaction();
				// $msg = "Data berhasil ditambahkan";
				// $sts = "OK";
			// }
			// $this->general->closeDb();
			// $return = array('sts' => $sts, 'msg' => $msg);
			// echo json_encode($return);
		// }
		
		private function save_dokumen($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			if (isset($_POST['id_inv_doc_transaksi']) && trim($_POST['id_inv_doc_transaksi']) !== "") {
				//upload gambar
				if($_FILES['gambar']['name'][0]!=''){
					$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
					$config['allowed_types'] 	= 'png|jpg';			
					$newname 					= array($_POST['id_inv_doc'].'_'.$_POST['nomor_dokumen']);			
					$gambar						= $this->general->upload_files($_FILES['gambar'], $newname, $config);
					$url_gambar					= base_url().$gambar[0]['url'];
					if($gambar === NULL){
						$msg        = "Upload files error";
						$sts        = "NotOK";
						$return     = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
				}else{
					$url_gambar			= $_POST['hidden_gambar'];
				}
				$data_row = array(
					"id_inv_doc"      	 => $this->generate->kirana_decrypt($_POST['id_inv_doc']),
					"id_aset"	    	 => $this->generate->kirana_decrypt($_POST['id_aset']),
					"nomor_dokumen"    	 => $_POST['nomor_dokumen'],
					"tanggal_berlaku" 	 => $_POST['tanggal_berlaku'],
					"gambar"			 => $url_gambar,
					"keterangan"     	 => $_POST['keterangan']
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_inv_doc_transaksi", $data_row, array(
					array(
						'kolom' => 'id_inv_doc_transaksi',
						'value' => $this->generate->kirana_decrypt($_POST['id_inv_doc_transaksi'])
					)
				));

			} else {
				//upload gambar
				if($_FILES['gambar']['name'][0]!=''){
					$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module()).'/hrga';
					$config['allowed_types'] 	= 'png|jpg';			
					$newname 					= array($_POST['id_inv_doc'].'_'.$_POST['nomor_dokumen']);			
					$gambar						= $this->general->upload_files($_FILES['gambar'], $newname, $config);
					$url_gambar					= base_url().$gambar[0]['url'];
					if($gambar === NULL){
						$msg        = "Upload files error";
						$sts        = "NotOK";
						$return     = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
				}else{
					$url_gambar			= $_POST['hidden_gambar'];
				}
				$data_row = array(
					"id_inv_doc"      	 => $this->generate->kirana_decrypt($_POST['id_inv_doc']),
					"id_aset"	    	 => $this->generate->kirana_decrypt($_POST['id_aset']),
					"nomor_dokumen"    	 => $_POST['nomor_dokumen'],
					"tanggal_berlaku" 	 => $_POST['tanggal_berlaku'],
					"gambar"			 => $url_gambar,
					"keterangan"     	 => $_POST['keterangan']
				);

				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_inv_doc_transaksi", $data_row);
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
		
		private function save_email() {
			$id_email 	 = $this->generate->kirana_decrypt($_POST['id_email']);
			$id_karyawan = $this->generate->kirana_decrypt($_POST['id_karyawan']);
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			if (isset($_POST['id_email']) && trim($_POST['id_email']) !== "") {	
				if($_POST['update']=='apar'){
					$data_row["apar"]	 		= $_POST['value_apar'];
				}
				if($_POST['update']=='lab'){
					$data_row["alat_lab"] 		= $_POST['value_lab'];
				}
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_inv_email", $data_row, array(
					array(
						'kolom' => 'id_email',
						'value' => $id_email
					)
				));

			} else {
				if($_POST['update']=='apar'){
					$data_row["id_karyawan"]	= $id_karyawan;
					$data_row["apar"]	 		= $_POST['value_apar'];
				}
				if($_POST['update']=='lab'){
					$data_row["id_karyawan"]	= $id_karyawan;
					$data_row["alat_lab"] 		= $_POST['value_lab'];
				}
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_inv_email", $data_row);
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
		
		private function save_main($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			if (isset($_POST['id_aset']) && trim($_POST['id_aset']) !== "") {
				if($_POST['pilihan']=="perubahan"){
					//input tabel main
					$data_row = array(
						"id_aset"	    	 => $this->generate->kirana_decrypt($_POST['id_aset']),
						"id_jenis"	    	 => $this->generate->kirana_decrypt($_POST['id_jenis']),
						"id_periode"   		 => 0,
						"tanggal_mulai"    	 => $datetime,
						"tanggal_selesai"  	 => $datetime,
						"jam_jalan"		 	 => $_POST['jam_jalan'],
						"jenis_tindakan"  	 => 'perubahan',
						"final"			 	 => 'y'
					);

					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_inv_main", $data_row);

					//update jam_jalan_terakhir di tbl_inv_aset
					$data_update = array(
						"jam_jalan_terakhir" => $_POST['jam_jalan']
					);
					$data_update = $this->dgeneral->basic_column("update", $data_update);
					$this->dgeneral->update("tbl_inv_aset", $data_update, array(
						array(
							'kolom' => 'id_aset',
							'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
						)
					));
					
				}
				if($_POST['pilihan']=="perbaikan"){
					$data_row = array(
						"id_aset"    		 => $this->generate->kirana_decrypt($_POST['id_aset']),
						"id_jenis"    		 => $this->generate->kirana_decrypt($_POST['id_jenis']),
						"id_periode"   		 => 0,
						"jam_jalan"   		 => $_POST['jam_jalan'],
						"operator"	    	 => $_POST['operator'],
						"catatan"  	 		 => $_POST['catatan'],
						"jenis_tindakan"  	 => 'perbaikan',
						"final"			 	 => 'n'
					);
					$data_row = $this->dgeneral->basic_column("insert", $data_row);
					$this->dgeneral->insert("tbl_inv_main", $data_row);
					
				}
				if($_POST['pilihan']=="perawatan"){
					// //cek jika kategori apar
					// $id_kategori = $this->generate->kirana_decrypt($_POST['id_kategori']);
					// if($id_kategori==5){
					//cek jika bukan alat berat	
					if(isset($_POST['berat'])=='n'){
						$data_row = array(
							"id_aset"    		 => $this->generate->kirana_decrypt($_POST['id_aset']),
							"tanggal"   		 => $_POST['tanggal'],
							"operator"	    	 => $_POST['operator'],
							"catatan"  	 		 => $_POST['catatan']
						);
						$data_row = $this->dgeneral->basic_column("insert", $data_row);
						$this->dgeneral->insert("tbl_inv_maintenance", $data_row);
					}else{
						$data_row = array(
							"id_aset"    		 => $this->generate->kirana_decrypt($_POST['id_aset']),
							"id_jenis"    		 => $this->generate->kirana_decrypt($_POST['id_jenis']),
							"id_periode"   		 => $this->generate->kirana_decrypt($_POST['id_periode']),
							"jam_jalan"   		 => $_POST['jam_jalan'],
							"operator"	    	 => $_POST['operator'],
							"catatan"  	 		 => $_POST['catatan'],
							"jenis_tindakan"  	 => 'perawatan',
							"cetak"			  	 => 'n',
							"final"			 	 => 'n'
						);
						$data_row = $this->dgeneral->basic_column("insert", $data_row);
						$this->dgeneral->insert("tbl_inv_main", $data_row);
						
						//insert tbl_inv_main_detail
						$id_main_terakhir 	= $this->db->insert_id();
						// $periode_detail 	= $this->get_periode_detail("array",NULL,NULL,NULL,$this->generate->kirana_decrypt($_POST['id_periode']), $this->generate->kirana_decrypt($_POST['id_jenis']));
						$periode_detail 	= $this->dtransaksiasset->get_data_periode_detail(NULL, NULL, NULL, NULL,$this->generate->kirana_decrypt($_POST['id_periode']), $this->generate->kirana_decrypt($_POST['id_jenis']));
						$data_ans_batch		= array();
						foreach($periode_detail as $dt){
							$data_ans_row   = array(
												'id_main' 		    	=> $id_main_terakhir,
												'id_aset'		 		=> $this->generate->kirana_decrypt($_POST['id_aset']),
												"id_jenis"    			=> $this->generate->kirana_decrypt($_POST['id_jenis']),
												'id_jenis_detail'  		=> $dt->id_jenis_detail,
												'nama_jenis_detail'		=> $dt->nama_jenis_detail,
												'id_periode'	  		=> $dt->id_periode,
												'id_periode_detail'		=> $this->generate->kirana_decrypt($dt->id_periode_detail),
												'nama_periode_detail'	=> $dt->nama,
												'na'     				=> 'n',
												'del'     				=> 'n',
												'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
												'tanggal_buat'      	=> $datetime,
												'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
												'tanggal_edit'      	=> $datetime
												
											);
							// $this->dgeneral->insert("tbl_inv_main_detail", $data_ans_row);						
							array_push($data_ans_batch, $data_ans_row);
						}
						// echo json_encode($data_ans_batch);
						// exit();
						$this->dgeneral->insert_batch('tbl_inv_main_detail', $data_ans_batch);
					}
				}
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

		private function save_proses_perbaikan($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			if (isset($_POST['id_main']) && trim($_POST['id_main']) !== "") {	
				$data_row = array(
					"tanggal_rusak"    	 => $_POST['tanggal_rusak'],
					"tanggal_mulai"    	 => $_POST['tanggal_mulai'],
					"tanggal_selesai"  	 => $_POST['tanggal_selesai'],
					"final"			 	 => 'y'
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_inv_main", $data_row, array(
					array(
						'kolom' => 'id_main',
						'value' => $this->generate->kirana_decrypt($_POST['id_main'])
					)
				));
				
				//update jam_jalan_terakhir di tbl_inv_aset
				$data_update = array(
					"jam_jalan_terakhir" => $_POST['jam_jalan']
				);
				$data_update = $this->dgeneral->basic_column("update", $data_update);
				$this->dgeneral->update("tbl_inv_aset", $data_update, array(
					array(
						'kolom' => 'id_aset',
						'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
					)
				));
				

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

		private function save_proses_perawatan($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			if (isset($_POST['id_main']) && trim($_POST['id_main']) !== "") {	
				$data_row = array(
					"tanggal_mulai"    	 => $_POST['tanggal_mulai'],
					"tanggal_selesai"  	 => $_POST['tanggal_selesai'],
					"final"			 	 => 'y'
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_inv_main", $data_row, array(
					array(
						'kolom' => 'id_main',
						'value' => $this->generate->kirana_decrypt($_POST['id_main'])
					)
				));
				
				//update jam_jalan_terakhir di tbl_inv_aset
				$data_update = array(
					"jam_jalan_terakhir" => $_POST['jam_jalan']
				);
				$data_update = $this->dgeneral->basic_column("update", $data_update);
				$this->dgeneral->update("tbl_inv_aset", $data_update, array(
					array(
						'kolom' => 'id_aset',
						'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
					)
				));
				

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

		private function save_main_detail($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			if (isset($_POST['id_main_detail']) && trim($_POST['id_main_detail']) !== "") {	
				if(!empty($_POST['keterangan'])){
					$data_row = array(
						"keterangan"    	 => $_POST['keterangan']
					);
				}
				if(!empty($_POST['cek'])){
					$data_row = array(
						"cek"    	 => $_POST['cek']
					);
				}
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_inv_main_detail", $data_row, array(
					array(
						'kolom' => 'id_main_detail',
						'value' => $this->generate->kirana_decrypt($_POST['id_main_detail'])
					)
				));

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
		
		private function save_set_pic($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			//UPDATE tbl_inv_aset
			$data["pic"]	 		= $_POST['pic'];
			$data["nama_user"] 		= $_POST['nama_user'];
			$data["alasan"] 		= $_POST['alasan'];
			$data["alasan_detail"] 	= (!empty($_POST['alasan_detail']))?$_POST['alasan_detail']:NULL;
			$data["id_kondisi"]		= (($_POST['alasan']=='Exit Clearance')or($_POST['alasan']=='Keep IT')) ? 6 : 1;		//jika move IT
			// $data["idle"] 			= isset($_POST["idle"]) ? 'yes' : 'no';
			// $data["id_sub_lokasi"] 	= $this->generate->kirana_decrypt($_POST['set_id_sub_lokasi']);
			// $data["id_area"] 		= $this->generate->kirana_decrypt($_POST['set_id_area']);
			$data = $this->dgeneral->basic_column("update", $data);
			$this->dgeneral->update("tbl_inv_aset", $data, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
				)
			));
			//INSERT tbl_inv_aset_temp
			$data_row["id_aset"] 	= $this->generate->kirana_decrypt($_POST['id_aset']);
			$data_row["pic"]	 	= $_POST['pic'];
			$data_row["nama_user"] 	= $_POST['nama_user'];
			$data_row["alasan"] 	= $_POST['alasan'];
			$data_row["id_kondisi"]	= (($_POST['alasan']=='Exit Clearance')or($_POST['alasan']=='Keep IT')) ? 6 : 1;		//jika move IT
			// $data_row["idle"] 		= isset($_POST["idle"]) ? 'yes' : 'no';
			$data_row["flag"] 		= 'proses';
			if($_POST['pic']!=$_POST['pic_awal']){
				$data_row["proses"]				= 'set_pic';
				$data_row["status_awal"]		= $_POST['pic_awal'];
				$data_row["status_akhir"]		= $_POST['pic'];
				
			}else{
				$data_row["proses"]				= 'set_lokasi';	
				$data_row["id_sub_lokasi_awal"]	= $this->generate->kirana_decrypt(@$_POST['id_sub_lokasi_awal']);
				$data_row["id_sub_lokasi_akhir"]= $this->generate->kirana_decrypt(@$_POST['set_id_sub_lokasi']);
				$data_row["id_area_awal"]		= $this->generate->kirana_decrypt(@$_POST['id_area_awal']);
				$data_row["id_area_akhir"]		= $this->generate->kirana_decrypt(@$_POST['set_id_area']);
			}
			$data_row["login_edit"]	 = base64_decode($this->session->userdata("-id_user-"));
			$data_row["tanggal_edit"]= $datetime;
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_inv_aset_temp", $data_row);


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
		
		private function save_set_kondisi($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			//UPDATE tbl_inv_aset
			$data["id_kondisi"]	 	= $this->generate->kirana_decrypt($_POST['id_kondisi']);
			$data = $this->dgeneral->basic_column("update", $data);
			$this->dgeneral->update("tbl_inv_aset", $data, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
				)
			));
			//INSERT tbl_inv_aset_temp
			$data_row["id_aset"] 	 = $this->generate->kirana_decrypt($_POST['id_aset']);
			$data_row["id_kondisi"]	 = $this->generate->kirana_decrypt($_POST['id_kondisi']);
			$data_row["flag"] 		 = 'proses';
			$data_row["proses"]		 = 'set_kondisi';
			$data_row["status_awal"] = $this->generate->kirana_decrypt($_POST['id_kondisi_awal']);
			$data_row["status_akhir"]= $this->generate->kirana_decrypt($_POST['id_kondisi']);
			$data_row["login_edit"]	 = base64_decode($this->session->userdata("-id_user-"));
			$data_row["tanggal_edit"]= $datetime;
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_inv_aset_temp", $data_row);


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
		
		private function save_proses_retire($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			//UPDATE tbl_inv_aset
			$data["id_kondisi"]	 	= 5;
			$data = $this->dgeneral->basic_column("update", $data);
			$this->dgeneral->update("tbl_inv_aset", $data, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
				)
			));
			//UPATE tbl_inv_aset_temp
			$data_row["flag"] 		 = 'proses';
			$data_row["keterangan"]	 = $_POST['catatan'];
			$data_row["login_edit"]	 = base64_decode($this->session->userdata("-id_user-"));
			$data_row["tanggal_edit"]= $datetime;
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_inv_aset_temp", $data_row, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
				),
				array(
					'kolom' => 'proses',
					'value' => 'set_retire'
				),
				array(
					'kolom' => 'flag',
					'value' => 'menunggu'
				)
				
			));


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
		
		private function save_batal_retire($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			//UPATE tbl_inv_aset_temp
			$data_row["na"] 		 = 'y';
			$data_row["del"]		 = 'y';
			$data_row["login_edit"]	 = base64_decode($this->session->userdata("-id_user-"));
			$data_row["tanggal_edit"]= $datetime;
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_inv_aset_temp", $data_row, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset_post'])
				),
				array(
					'kolom' => 'proses',
					'value' => 'set_retire'
				),
				array(
					'kolom' => 'flag',
					'value' => 'menunggu'
				)
				
			));


			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$msg = "Periksa kembali data yang dimasukkan";
				$sts = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg = "Pengajuan berhasil dibatalkan";
				$sts = "OK";
			}
			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}
		
		private function save_set_retire($param) {
			$datetime = date("Y-m-d H:i:s");

			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$id_aset = $this->generate->kirana_decrypt($this->input->post('id_aset'));
			$cek_aset	= $this->get_aset('array', $id_aset);

			$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
			$config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|xls';

			//================================SAVE SCORING HEADER================================//
			$dt_newname = NULL;
			if ($_FILES['lampiran']['name'][0] !== "") {
				if (count($_FILES['lampiran']['name']) > 1) {
					$msg    = "You can only upload maximum 1 file";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				$newname  = array("Lampiran-Retire-".$cek_aset[0]->KODE_BARANG);
				$file_lampiran = $this->general->upload_files($_FILES['lampiran'], $newname, $config);
				$dt_newname = $file_lampiran[0]['url'];
			}

			//INSERT tbl_inv_aset_temp
			$data_row["id_aset"] 	 = $this->generate->kirana_decrypt($cek_aset[0]->id_aset);
			$data_row["nomor"]	 	 = $cek_aset[0]->nomor;
			$data_row["id_kategori"] = $this->generate->kirana_decrypt($cek_aset[0]->id_kategori);
			$data_row["id_jenis"] 	 = $this->generate->kirana_decrypt($cek_aset[0]->id_jenis);
			$data_row["id_merk"]	 = $this->generate->kirana_decrypt($cek_aset[0]->id_merk);
			$data_row["id_merk_tipe"]= $this->generate->kirana_decrypt($cek_aset[0]->id_merk_tipe);
			$data_row["id_satuan"]	 = $this->generate->kirana_decrypt($cek_aset[0]->id_satuan);
			$data_row["id_pabrik"]	 = $this->generate->kirana_decrypt($cek_aset[0]->id_pabrik);
			$data_row["id_lokasi"]	 = $this->generate->kirana_decrypt($cek_aset[0]->id_lokasi);
			$data_row["id_area"]	 = $this->generate->kirana_decrypt($cek_aset[0]->id_area);
			$data_row["pic"]		 = $cek_aset[0]->pic;
			$data_row["nama_user"]   = $cek_aset[0]->NAMA_USER;
			$data_row["kode_barang"] = $cek_aset[0]->KODE_BARANG;
			$data_row["nomor_sap"]   = $cek_aset[0]->nomor_sap;
			$data_row["alasan"] 	 = $_POST['alasan'];
			$data_row["id_kondisi"]	 = 5;
			$data_row["flag"] 		 = 'menunggu';
			$data_row["proses"]		 = 'set_retire';
			$data_row["status_awal"] = $this->generate->kirana_decrypt($cek_aset[0]->id_kondisi);
			$data_row["status_akhir"]= 5;
			$data_row["tanggal_retire"] = $this->generate->regenerateDateFormat($this->input->post('tanggal_retire'));
			$data_row["login_edit"]	 = base64_decode($this->session->userdata("-id_user-"));
			$data_row["tanggal_edit"]= $datetime;

			// CR 2418
			$data_row["opt_opsi"]  = $_POST['opt_opsi'];
			$data_row["file_ba"]   = $dt_newname;
			$data_row["no_doc"]    = isset($_POST['no_doc']) &&  $_POST['no_doc'] !== "" ? $_POST['no_doc'] : NULL;

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_inv_aset_temp", $data_row);


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

		private function save_perbaikan($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			//UPDATE tbl_inv_aset
			$data["id_kondisi"]	 	= 4;	//dalam perbaikan
			$data = $this->dgeneral->basic_column("update", $data);
			$this->dgeneral->update("tbl_inv_aset", $data, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
				)
			));
			//INSERT tbl_inv_main
			$data_row["id_aset"] 			= $this->generate->kirana_decrypt($this->input->post('id_aset'));
			$data_row["id_jenis"] 			= $this->generate->kirana_decrypt($this->input->post('id_jenis'));
			$data_row["jenis_tindakan"] 	= 'perbaikan';
			$data_row["tanggal_rusak"]		= $datetime;
			$data_row["tanggal_estimasi"]	= $this->generate->regenerateDateFormat($this->input->post('tanggal_estimasi'));
			$data_row["final"]		 		= 'n';
			$data_row["login_edit"]	 		= base64_decode($this->session->userdata("-id_user-"));
			$data_row["tanggal_edit"]		= $datetime;
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_inv_main", $data_row);
			
			//INSERT tbl_inv_main_detail
			$id_main 			= $this->db->insert_id();
			$ceks 				= $this->input->post('cek');
			$namas 				= $this->input->post('nama');
			$pekerjaans			= $this->input->post('pekerjaan');
			$keterangans 		= $this->input->post('keterangan');
			$id_jenis_details 	= $this->input->post('id_jenis_detail');
			foreach ($keterangans as $id_jenis_detail => $keterangan) {
				if(isset($ceks[$id_jenis_detail])){
					$data_detail["id_main"] 			= $id_main;
					$data_detail["id_aset"]				= $this->generate->kirana_decrypt($_POST['id_aset']);
					$data_detail["id_jenis"]			= $this->generate->kirana_decrypt($_POST['id_jenis']);
					$data_detail["id_jenis_detail"] 	= $this->generate->kirana_decrypt($id_jenis_details[$id_jenis_detail]);
					$data_detail["nama_jenis_detail"]	= $namas[$id_jenis_detail];
					$data_detail["nama_periode_detail"]	= $pekerjaans[$id_jenis_detail];
					$data_detail["keterangan"]			= $keterangans[$id_jenis_detail];
					$data_detail["login_edit"]	 		= base64_decode($this->session->userdata("-id_user-"));
					$data_detail["tanggal_edit"]		= $datetime;
					$data_detail = $this->dgeneral->basic_column("insert", $data_detail);
					$this->dgeneral->insert("tbl_inv_main_detail", $data_detail);
				}
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
		
		private function save_perbaikan_complete($param) {
			$datetime = date("Y-m-d H:i:s");
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			//UPDATE tbl_inv_aset
			$data["id_kondisi"]	 	= 1;	//beroperasi
			$data = $this->dgeneral->basic_column("update", $data);
			$this->dgeneral->update("tbl_inv_aset", $data, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
				)
			));
			//UPDATE tbl_inv_main
			$data_row["final"]	 		= 'y';
			$data_row["catatan_service"]= $_POST['catatan_service'];
			$data_row["tanggal_selesai"]= $this->generate->regenerateDateFormat($this->input->post('tanggal_selesai'));
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_inv_main", $data_row, array(
				array(
					'kolom' => 'id_aset',
					'value' => $this->generate->kirana_decrypt($_POST['id_aset'])
				),
				array(
					'kolom' => 'jenis_tindakan',
					'value' => 'perbaikan'
				),
				array(
					'kolom' => 'final',
					'value' => 'n'
				)
			));

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