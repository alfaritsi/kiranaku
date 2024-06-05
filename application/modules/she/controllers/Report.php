<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  	: SHE 
@author     	: Syah Jadianto (8604)
@contributor  	: 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Report extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dmaster');
	    $this->load->model('dmastershe');
	    $this->load->model('dtransactionshe');
	    $this->load->model('dreportshe');
	}

	public function index(){
		show_404();
	}

    public function get_grafik_mutu(){
		$pabrik   = (isset($_POST['pabrik'])) ? $_POST['pabrik'] : "";
		$dari 	  = (isset($_POST['dari'])) ? $_POST['dari'] : date('d.m.Y', strtotime('-1 month'));
		$sampai	  = (isset($_POST['sampai'])) ? $_POST['sampai'] : date('d.m.Y');
        $data       = $this->dreportshe->get_data_grafik_mutu($pabrik,$dari,$sampai);
        echo json_encode($data);
    }
    public function get_grafik_cemar(){
		$pabrik   = (isset($_POST['pabrik'])) ? $_POST['pabrik'] : "";
		$dari 	  = (isset($_POST['dari'])) ? $_POST['dari'] : date('d.m.Y', strtotime('-1 month'));
		$sampai	  = (isset($_POST['sampai'])) ? $_POST['sampai'] : date('d.m.Y');
        $data       = $this->dreportshe->get_data_grafik_cemar($pabrik,$dari,$sampai);
        echo json_encode($data);
    }
    public function get_grafik_cemar_chart(){
		$pabrik   = (isset($_POST['pabrik'])) ? $_POST['pabrik'] : "";
		$dari 	  = (isset($_POST['dari'])) ? $_POST['dari'] : date('d.m.Y', strtotime('-1 month'));
		$sampai	  = (isset($_POST['sampai'])) ? $_POST['sampai'] : date('d.m.Y');
		$param	  = (isset($_POST['param'])) ? $_POST['param'] : NULL;
        $data       = $this->dreportshe->get_data_grafik_cemar_chart($pabrik,$dari,$sampai,$param);
        echo json_encode($data);
    }

	public function limbahair($param){
		switch ($param) {
			case 'hasiluji':
				$this->rpt_sum_hasilujiairlimbah();
				break;
			case 'bebancemar':
				$this->rpt_sum_bebancemar();
				break;
			case 'cemaraktual':
				$this->rpt_sum_cemaraktual();
				break;
			case 'grafikcemar':
				$this->rpt_grafik_cemar();
				break;
			case 'grafikmutu':
				$this->rpt_grafik_mutu();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function limbahudara($param){
		switch ($param) {
			case 'bpaemisiudara':
				$this->rpt_bpa_emisi_udara();
				break;
			case 'bpavssampleloc':
				$this->rpt_bpa_vs_sampleloc();
				break;
			case 'akk':
				$this->rpt_akk_kualitasudara();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function limbahb3($param){
		switch ($param) {
			case 'neraca':
				$this->rpt_neracalimbah_b3();
				break;
			case 'logbook':
				$this->rpt_logbook_b3();
				break;
			case 'beritaacara':
				$this->rpt_ba_logbook_b3();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	// public function rpt_sum_hasilujiairlimbah(){
	// 	$this->general->check_access();
	// 	$data['title']    			= "Report SHU Air Limbah Bulanan";
	// 	$data['title_form']    		= "";
	//     $data['module']     		= $this->router->fetch_module();  
	// 	$data['user']     			= $this->general->get_data_user();
 //        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
 //        $data['reporth'] 			= $this->dreportshe->get_data_sum_hasilujiairlimbah(1, 1, NULL, NULL);
 //        $data['report'] 			= $this->dreportshe->get_data_sum_hasilujiairlimbah(2, 1, NULL, NULL);
 //        $data['filterpabrik']    	= "";
	// 	$data['from']    			= "";
	// 	$data['to']    				= "";
	// 	$this->load->view("limbah_air/rpt_hasilujiairlimbah", $data);
	// }

	public function rpt_sum_hasilujiairlimbah(){
		$this->general->connectDbPortal();
        $filterpabrik    			= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
        $filterkategori    			= (empty($_POST['filterkategori']))? 0 : $_POST['filterkategori'];
		$from    					= (empty($_POST['from']))? "" : $_POST['from'];
		$to    						= (empty($_POST['to']))? "" : $_POST['to'];
		$data['title']    			= "Report SHU Air Limbah Bulanan";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['reporth'] 			= $this->dreportshe->get_data_sum_hasilujiairlimbah(1, ($filterpabrik==0)?15:$filterpabrik, $from, $to, $filterkategori);
		$data['report'] 			= $this->dreportshe->get_data_sum_hasilujiairlimbah(2, $filterpabrik, $from, $to, $filterkategori);
		$data['kategori'] 			= $this->dmastershe->get_data_kategori_filter(array(1,7));
        $data['filterpabrik']    	= $filterpabrik;
        $data['filterkategori']    	= $filterkategori;
		$data['from']    			= $from;
		$data['to']    				= $to;
		// echo json_encode($report);
		$this->load->view("limbah_air/rpt_hasilujiairlimbah", $data);
	}	

	// public function rpt_sum_bebancemar(){
	// 	$this->general->check_access();
	// 	$data['title']    			= "Report Beban Pencemaran Kg/Ton Produk";
	// 	$data['title_form']    		= "";
	//     $data['module']     		= $this->router->fetch_module();
	// 	$data['user']     			= $this->general->get_data_user();
 //        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
 //        $data['reporth'] 			= $this->dreportshe->get_data_sum_bebancemar(1, 0, NULL, NULL);
 //        $data['report'] 			= $this->dreportshe->get_data_sum_bebancemar(2, 0, NULL, NULL);
 //        $data['filterpabrik']    	= "";
	// 	$data['from']    			= "";
	// 	$data['to']    				= "";
	// 	$this->load->view("limbah_air/rpt_bebancemar", $data);
	// }

	public function rpt_sum_bebancemar(){
		$this->general->connectDbPortal();
        $filterpabrik    			= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
        $filterkategori    			= (empty($_POST['filterkategori']))? 0 : $_POST['filterkategori'];
		$from    					= (empty($_POST['from']))? "" : $_POST['from'];
		$to    						= (empty($_POST['to']))? "" : $_POST['to'];
		// $filter_parameter			= (empty($_POST['parameter']))? "" : $_POST['parameter'];
		if(isset($_POST['parameter'])){
			$list_parameter  = "";
			foreach ($_POST['parameter'] as $dt){
				$list_parameter  .= $dt.",";
			}
			$filter_parameter = substr($list_parameter, 0, -1);
		}else{
			$filter_parameter  = "";
		}
		
		// exit();
		$data['title']    			= "Report Beban Pencemaran Kg/Ton Produk";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['reporth'] 			= $this->dreportshe->get_data_sum_bebancemar(1, ($filterpabrik==0)?15:$filterpabrik, $from, $to, $filter_parameter, $filterkategori);
		$data['report'] 			= $this->dreportshe->get_data_sum_bebancemar(2, $filterpabrik, $from, $to, $filter_parameter, $filterkategori);
		$data['kategori'] 			= $this->dmastershe->get_data_kategori_filter(array(1,7));
        $data['filterpabrik']    	= $filterpabrik;
        $data['filterkategori']    	= $filterkategori;
		$data['from']    			= $from;
		$data['to']    				= $to;
		$data['parameter'] 			= $this->dreportshe->get_data_parameter();
		// echo json_encode($report);
		
		$this->load->view("limbah_air/rpt_bebancemar", $data);
	}	

	public function rpt_grafik_cemar(){
		$this->general->connectDbPortal();
        $filterpabrik    			= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
		$from    					= (empty($_POST['from']))? "" : $_POST['from'];
		$to    						= (empty($_POST['to']))? "" : $_POST['to'];
		// exit();
		$data['title']    			= "Grafik Beban Pencemaran";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['filterpabrik']    	= $filterpabrik;
		$data['from']    			= $from;
		$data['to']    				= $to;
		// echo json_encode($report);
		$this->load->view("limbah_air/rpt_grafik_cemar", $data);
	}	
	public function rpt_grafik_mutu(){
		$this->general->connectDbPortal();
        $filterpabrik    			= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
		$from    					= (empty($_POST['from']))? "" : $_POST['from'];
		$to    						= (empty($_POST['to']))? "" : $_POST['to'];
		// exit();
		$data['title']    			= "Grafik Baku Mutu";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['filterpabrik']    	= $filterpabrik;
		$data['from']    			= $from;
		$data['to']    				= $to;
		// echo json_encode($report);
		$this->load->view("limbah_air/rpt_grafik_mutu", $data);
	}	

	// public function rpt_sum_cemaraktual(){
	// 	$this->general->check_access();
	// 	$data['title']    			= "Report Beban Pencemaran Ton/Periode";
	// 	$data['title_form']    		= "";
	//     $data['module']     		= $this->router->fetch_module();
	// 	$data['user']     			= $this->general->get_data_user();
 //        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
 //        $data['reporth'] 			= $this->dreportshe->get_data_sum_cemaraktual(1, 0, NULL, NULL);
 //        $data['report'] 			= $this->dreportshe->get_data_sum_cemaraktual(3, 0, NULL, NULL);
 //        $data['filterpabrik']    	= "";
	// 	$data['from']    			= "";
	// 	$data['to']    				= "";
	// 	$this->load->view("limbah_air/rpt_cemaraktual", $data);
	// }

	public function rpt_sum_cemaraktual(){
		$this->general->connectDbPortal();
        $filterpabrik    			= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
        $filterkategori    			= (empty($_POST['filterkategori']))? 0 : $_POST['filterkategori'];
		$from    					= (empty($_POST['from']))? "" : $_POST['from'];
		$to    						= (empty($_POST['to']))? "" : $_POST['to'];
		$data['title']    			= "Report Beban Pencemaran Ton/Periode";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['reporth'] 			= $this->dreportshe->get_data_sum_cemaraktual(1, ($filterpabrik==0)?15:$filterpabrik, $from, $to, $filterkategori);
		$data['report'] 			= $this->dreportshe->get_data_sum_cemaraktual(2, $filterpabrik, $from, $to, $filterkategori);
		$data['kategori'] 			= $this->dmastershe->get_data_kategori_filter(array(1,7));
        $data['filterpabrik']    	= $filterpabrik;
        $data['filterkategori']    	= $filterkategori;
		$data['from']    			= $from;
		$data['to']    				= $to;
		// echo json_encode($report);
		$this->load->view("limbah_air/rpt_cemaraktual", $data);
	}

	public function rpt_bpa_emisi_udara(){
		$this->general->check_access();
		$pabrik 					= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
		$jenis 						= (empty($_POST['filterjenis']))? 0 : $_POST['filterjenis'];
		$periode 					= (empty($_POST['filterperiode']))? "" : $_POST['filterperiode'];
		$data['title']    			= "Report BPA Emisi Udara";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['jenis'] 				= $this->dreportshe->get_data_kualitasudara_filterjenis($pabrik, 3);
        if($pabrik == "" || $jenis == "" || $periode == ""){
        	$data['report'] 			= $this->dreportshe->get_data_bpa_emisi_udara(1, 0, NULL, NULL);
        }else{
        	$data['report'] 			= $this->dreportshe->get_data_bpa_emisi_udara(2, $pabrik, $jenis, $periode);
        }
        $data['filterpabrik']    	= $pabrik;
		$data['filterjenis']    	= $jenis;
		$data['filterperiode']    	= $periode;
		$this->load->view("limbah_udara/rpt_bpa_emisiudara", $data);
	}

	public function rpt_bpa_vs_sampleloc(){
		$this->general->check_access();
		$pabrik 					= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
		$periode 					= (empty($_POST['filterperiode']))? "" : $_POST['filterperiode'];
		$data['title']    			= "Report BPA Emisi Udara";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        if($pabrik == "" || $periode == ""){
        	$data['reporth'] 		= $this->dreportshe->get_data_bpa_vs_sample(1, $pabrik, $periode);
        	$data['report'] 		= $this->dreportshe->get_data_bpa_vs_sample(2, $pabrik, $periode);
        }else{
        	$data['reporth'] 		= $this->dreportshe->get_data_bpa_vs_sample(1, $pabrik, $periode);
        	$data['report'] 		= $this->dreportshe->get_data_bpa_vs_sample(3, $pabrik, $periode);
        }
        $data['filterpabrik']    	= $pabrik;
		$data['filterperiode']    	= $periode;
		$this->load->view("limbah_udara/rpt_bpa_vs_sample", $data);
	}

	public function rpt_akk_kualitasudara(){
		$this->general->check_access();
		$pabrik 					= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
		$kategori 					= (empty($_POST['filterkategori']))? 0 : $_POST['filterkategori'];
		$jenis 						= (empty($_POST['filterjenis']))? 0 : $_POST['filterjenis'];
		$periode 					= (empty($_POST['filterperiode']))? "" : $_POST['filterperiode'];
		$data['title']    			= "Report AKK";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['kategori'] 			= $this->dmastershe->get_data_kategori();
        $data['jenis'] 				= $this->dreportshe->get_data_kualitasudara_filterjenis($pabrik, $kategori);
        if($pabrik == "" || $kategori == "" || $jenis == "" || $periode == ""){
        	$data['report'] 		= $this->dreportshe->get_data_akk_kualitasudara(1, $pabrik, $kategori, $jenis, $periode);
        }else{
        	$data['report'] 		= $this->dreportshe->get_data_akk_kualitasudara(2, $pabrik, $kategori, $jenis, $periode);
        }
        $data['filterpabrik']    	= $pabrik;
		$data['filterkategori']    	= $kategori;
		$data['filterjenis']    	= $jenis;
		$data['filterperiode']    	= $periode;
		$this->load->view("limbah_udara/rpt_akk_kualitasudara", $data);
	}

	public function rpt_neracalimbah_b3(){
		$this->general->check_access();
		$pabrik 					= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
		$periode 					= (empty($_POST['filterperiode']))? "" : $_POST['filterperiode'];
		$tahun 						= (empty($_POST['filtertahun']))? "" : $_POST['filtertahun'];
		$data['title']    			= "Report Neraca Limbah B3";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
		
        $data['perijinan'] 			= $this->dreportshe->get_data_perijinan($pabrik, 8);
        if($pabrik == "" || $periode == "" || $tahun == ""){
        	$data['report1'] 		= $this->dreportshe->get_data_neracalimbah_b3(1, $pabrik, $periode, $tahun);
        	$data['report2'] 		= $this->dreportshe->get_data_neracalimbah_b3(2, $pabrik, $periode, $tahun);
        	$data['report3'] 		= $this->dreportshe->get_data_neracalimbah_b3(3, $pabrik, $periode, $tahun);
        	$data['report4'] 		= $this->dreportshe->get_data_neracalimbah_b3(4, $pabrik, $periode, $tahun);
        }else{
        	$data['report1'] 		= $this->dreportshe->get_data_neracalimbah_b3(1, $pabrik, $periode, $tahun);
        	$data['report2'] 		= $this->dreportshe->get_data_neracalimbah_b3(2, $pabrik, $periode, $tahun);
        	$data['report3'] 		= $this->dreportshe->get_data_neracalimbah_b3(3, $pabrik, $periode, $tahun);
        	$data['report4'] 		= $this->dreportshe->get_data_neracalimbah_b3(4, $pabrik, $periode, $tahun);
        }
        $data['filterpabrik']    	= $pabrik;
		$data['filterperiode']    	= $periode;
		$data['filtertahun']    	= $tahun;
		$this->load->view("limbah_b3/rpt_neracalimbah_b3", $data);
	}

	public function rpt_logbook_b3(){
		$this->general->check_access();
		$pabrik 					= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
		$limbah 					= (empty($_POST['filterlimbah']))? 0 : $_POST['filterlimbah'];
		$from 						= (empty($_POST['filterfrom']))? "" : $_POST['filterfrom'];
		$to 						= (empty($_POST['filterto']))? "" : $_POST['filterto'];
		$data['title']    			= "Report Log Book B3";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['limbah'] 			= $this->dmastershe->get_data_limbah($pabrik, NULL, NULL);
        if($limbah == 0){
	        $data['report'] 			= $this->dreportshe->get_data_logbook_b3(0, $pabrik, $limbah, $from, $to);
    	}else{
    		$data['report'] 			= $this->dreportshe->get_data_logbook_b3(1, $pabrik, $limbah, $from, $to);
    	}
        $data['filterpabrik']    	= $pabrik;
        $data['filterlimbah']    	= $limbah;
		$data['filterfrom']    		= $from;
		$data['filterto']    		= $to;
		$this->load->view("limbah_b3/rpt_logbook_b3", $data);
	}

	public function rpt_ba_logbook_b3(){
		$this->general->check_access();
		$pabrik 					= (empty($_POST['filterpabrik']))? 0 : $_POST['filterpabrik'];
		$from 						= (empty($_POST['filterfrom']))? "" : $_POST['filterfrom'];
		$to 						= (empty($_POST['filterto']))? "" : $_POST['filterto'];
		$data['title']    			= "Report Berita Acara Log Book B3";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['report'] 			= $this->dreportshe->get_data_ba_logbook_b3($pabrik, $from, $to);
        $data['filterpabrik']    	= $pabrik;
		$data['filterfrom']    		= $from;
		$data['filterto']    		= $to;
		$this->load->view("limbah_b3/rpt_ba_logbook_b3", $data);
	}
	public function excel($param){
		switch ($param) {
			case 'hasil_uji_air_bulanan':
				$this->excel_hasil_uji_air_bulanan();
				break;
			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}
	private function excel_hasil_uji_air_bulanan(){
		// header("Content-type: application/octet-stream");
		// header("Content-Disposition: attachment; filename=Report_SHU_Air_Limbah_Bulanan.xls");
		// header("Pragma: no-cache");
		// header("Expires: 0");
		
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Report_SHU_Air_Limbah_Bulanan.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
		
		
		// $this->general->check_access();
        $reporth 			= $this->dreportshe->get_data_sum_hasilujiairlimbah(1, $_GET['filterpabrik'], $_GET['from'], $_GET['to'], $_GET['filterkategori'] );
		$report 			= $this->dreportshe->get_data_sum_hasilujiairlimbah(2, $_GET['filterpabrik'], $_GET['from'], $_GET['to'], $_GET['filterkategori']);
		echo '
			<table border=1>
				<tr>
					<th rowspan=2>Parameter</th>
					<th colspan=3>pH</th>
					<th colspan=2>COD</th>
					<th colspan=2>BOD</th>
					<th colspan=2>TSS</th>
					<th colspan=2>Amonia</th>
					<th colspan=2>Nitrogen</th>
				</tr>
				<tr>
					<th>Min</th>
					<th>Max</th>
					<th>Hasil Uji</th>
					<th>Baku Mutu</th>
					<th>Hasil Uji</th>
					<th>Baku Mutu</th>
					<th>Hasil Uji</th>
					<th>Baku Mutu</th>
					<th>Hasil Uji</th>
					<th>Baku Mutu</th>
					<th>Hasil Uji</th>
					<th>Baku Mutu</th>
					<th>Hasil Uji</th>
				</tr>';
				foreach($report as $result){
					$dt = explode(';', $result['VALUE']);
					$dtred = explode(';', $result['red_texth']);
					echo "<tr>";
					echo "<td>".$result['PARAMETER']."</td>";
					echo "<td align='right'>".$dt[0]."</td>";
					echo "<td align='right'>".$dt[1]."</td>";
					echo "<td align='right' ".$dtred[0].">".$dt[2]."</td>";
					$i = 0;
					for ($i=1; $i < 10; $i++) { 
					  echo "<td align='right'>".$dt[$i+2]."</td>";
					  $i++;
					  echo "<td align='right' ".$dtred[$i+2].">".number_format($dt[$i+2],2,",",".")."</td>";
					}
					
					echo "</tr>";
				}		
				
				
		echo'</table>';
		
	}


	private function rpt_neraca_b3_pdf(){
		$this->general->connectDbPortal();
		$pabrik 					= (empty($_POST['printpabrik']))? 0 : $_POST['printpabrik'];
		$periode 					= (empty($_POST['printperiode']))? "" : $_POST['printperiode'];
		$tahun 						= (empty($_POST['printtahun']))? "" : $_POST['printtahun'];
		$data['user']     			= $this->general->get_data_user();
	    $data['module']     		= $this->router->fetch_module();
		$data['perijinan'] 			= $this->dreportshe->get_data_perijinan($pabrik, 8);
        if($pabrik == "" || $periode == "" || $tahun == ""){
        	$data['header'] 		= $this->dreportshe->get_data_neracalimbah_b3(0, $pabrik, $periode, $tahun);
        	$data['report1'] 		= $this->dreportshe->get_data_neracalimbah_b3(1, $pabrik, $periode, $tahun);
        	$data['report2'] 		= $this->dreportshe->get_data_neracalimbah_b3(2, $pabrik, $periode, $tahun);
        	$data['report3'] 		= $this->dreportshe->get_data_neracalimbah_b3(3, $pabrik, $periode, $tahun);
			$data['report4'] 		= $this->dreportshe->get_data_neracalimbah_b3(4, $pabrik, $periode, $tahun);
        }else{
        	$data['header'] 		= $this->dreportshe->get_data_neracalimbah_b3(0, $pabrik, $periode, $tahun);
        	$data['report1'] 		= $this->dreportshe->get_data_neracalimbah_b3(1, $pabrik, $periode, $tahun);
        	$data['report2'] 		= $this->dreportshe->get_data_neracalimbah_b3(2, $pabrik, $periode, $tahun);
        	$data['report3'] 		= $this->dreportshe->get_data_neracalimbah_b3(3, $pabrik, $periode, $tahun);
			$data['report4'] 		= $this->dreportshe->get_data_neracalimbah_b3(4, $pabrik, $periode, $tahun);
        }
		// $data['regionplant']		= $this->general->get_master_plant($data['header'][0]->kode);
		// $data['region']				= preg_replace("/\d+/", "", $data['regionplant'][0]->region_name);

		$region						= $this->dreportshe->get_kota($pabrik);
		$data['region']				= $region[0]->nama_kota;

		$this->load->library('pdf');
	    $this->pdf->setPaper('A4', 'Portrait');
	    $this->pdf->filename = "laporan.pdf";
	    $this->pdf->load_view('limbah_b3/rpt_neracalimbahb3_print', $data);	

	}

	private function rpt_logbook_b3_pdf(){
		$this->general->connectDbPortal();
		$pabrik 					= (empty($_POST['printpabrik']))? 0 : $_POST['printpabrik'];
		$limbah 					= (empty($_POST['printlimbah']))? 0 : $_POST['printlimbah'];
		$from 						= (empty($_POST['printfrom']))? "" : $_POST['printfrom'];
		$to 						= (empty($_POST['printto']))? "" : $_POST['printto'];
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['header'] 			= $this->dreportshe->get_data_logbook_b3(0, $pabrik, $limbah, $from, $to);
        $data['report'] 			= $this->dreportshe->get_data_logbook_b3(1, $pabrik, $limbah, $from, $to);
		// $data['regionplant']		= $this->general->get_master_plant($data['header'][0]->kode);
		// $data['region']				= preg_replace("/\d+/", "", $data['regionplant'][0]->region_name);

		$region						= $this->dreportshe->get_kota($pabrik);
		$data['region']				= $region[0]->nama_kota;

		$this->load->library('pdf');
	    // $this->pdf->setPaper('A4', 'Portrait');
	    $this->pdf->setPaper('A4', 'Landscape');
	    $this->pdf->filename = "laporan.pdf";
	    $this->pdf->load_view('limbah_b3/rpt_logbook_b3_print', $data);	
		// $this->load->view("limbah_b3/rpt_logbook_b3_print", $data);


	}

	public function detailberitaacara(){
		$this->general->connectDbPortal();
		// $pabrik 					= (empty($_POST['printpabrik']))? 0 : $_POST['printpabrik'];
		// $limbah 					= (empty($_POST['printlimbah']))? 0 : $_POST['printlimbah'];
		// $from 						= (empty($_POST['printfrom']))? "" : $_POST['printfrom'];
		// $to 						= (empty($_POST['printto']))? "" : $_POST['printto'];
		$beritaacara 				= $this->dreportshe->get_data_ba_logbook_b3_detail($_POST['beritaacara']);
		echo json_encode($beritaacara);

	}

	public function rpt_ba_logbook_b3_pdf($param){
		$this->general->connectDbPortal();
		// $pabrik 					= (empty($_POST['printpabrik']))? 0 : $_POST['printpabrik'];
		// $limbah 					= (empty($_POST['printlimbah']))? 0 : $_POST['printlimbah'];
		// $from 						= (empty($_POST['printfrom']))? "" : $_POST['printfrom'];
		// $to 						= (empty($_POST['printto']))? "" : $_POST['printto'];
	    $data['module']     		= $this->router->fetch_module();
		$data['report'] 			= $this->dreportshe->preview_data_ba_logbook_b3($param);
		$this->load->library('pdf');
	    $this->pdf->setPaper('A4', 'Portrait');
	    $this->pdf->filename = "laporan.pdf";
	    $this->pdf->load_view('limbah_b3/rpt_ba_logbook_b3_print', $data);	

	}

	public function pdf($param, $param2=NULL){
		switch ($param) {
			case 'logbookB3':
				$this->rpt_logbook_b3_pdf();
				break;
			case 'neracaB3':
				$this->rpt_neraca_b3_pdf();
				break;
			case 'beritaacara':
				$this->rpt_ba_logbook_b3_pdf(str_replace('-', '/', $param2));
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	public function get_data($param){
		switch ($param) {
			case 'hasil_uji_airlimbah':
				$this->rpt_sum_hasilujiairlimbah();
				break;
			case 'bebancemar':
				$this->rpt_sum_bebancemar_filter();
				break;
			case 'cemaraktual':
				$this->rpt_sum_cemaraktual_filter();
				break;
			case 'bpaemisiudara':
				$this->rpt_bpa_emisi_udara();
				break;
			case 'jenisemisiudara':
				$this->get_jenisemisiudara_filterjenis();
				break;
			case 'getlimbah':
				$this->get_limbah_filterplant();
				break;
			case 'beritaacara':
				$this->detailberitaacara();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	private function rpt_sum_hasilujiairlimbah_filter(){
		$this->general->connectDbPortal();
		$data['title']    			= "Report SHU Air Limbah Bulanan";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['reporth'] 			= $this->dreportshe->get_data_sum_hasilujiairlimbah(1, $_POST['filterpabrik'], $_POST['from'], $_POST['to']);
		$data['report'] 			= $this->dreportshe->get_data_sum_hasilujiairlimbah(2, $_POST['filterpabrik'], $_POST['from'], $_POST['to']);
        $data['filterpabrik']    	= $_POST['filterpabrik'];
		$data['from']    			= $_POST['from'];
		$data['to']    				= $_POST['to'];
		// echo json_encode($report);
		$this->load->view("limbah_air/rpt_hasilujiairlimbah", $data);
	}	

	private function rpt_sum_bebancemar_filter(){
		$this->general->connectDbPortal();
		$data['title']    			= "Report Beban Pencemaran Kg/Ton Produk";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['reporth'] 			= $this->dreportshe->get_data_sum_bebancemar(1, $_POST['filterpabrik'], $_POST['from'], $_POST['to']);
		$data['report'] 			= $this->dreportshe->get_data_sum_bebancemar(2, $_POST['filterpabrik'], $_POST['from'], $_POST['to']);
        $data['filterpabrik']    	= $_POST['filterpabrik'];
		$data['from']    			= $_POST['from'];
		$data['to']    				= $_POST['to'];
		// echo json_encode($report);
		$this->load->view("limbah_air/rpt_bebancemar", $data);
	}	

	private function rpt_sum_cemaraktual_filter(){
		$this->general->connectDbPortal();
		$data['title']    			= "Report Beban Pencemaran Ton/Periode";
		$data['title_form']    		= "";
	    $data['module']     		= $this->router->fetch_module();
		$data['user']     			= $this->general->get_data_user();
        $data['pabrik'] 			= $this->dmaster->get_data_pabrik();
        $data['reporth'] 			= $this->dreportshe->get_data_sum_cemaraktual(1, $_POST['filterpabrik'], $_POST['from'], $_POST['to']);
		$data['report'] 			= $this->dreportshe->get_data_sum_cemaraktual(2, $_POST['filterpabrik'], $_POST['from'], $_POST['to']);
        $data['filterpabrik']    	= $_POST['filterpabrik'];
		$data['from']    			= $_POST['from'];
		$data['to']    				= $_POST['to'];
		// echo json_encode($report);
		$this->load->view("limbah_air/rpt_cemaraktual", $data);
	}

	private function get_jenisemisiudara_filterjenis(){
		$this->general->connectDbPortal();
		$limbahudara = $this->dreportshe->get_data_kualitasudara_filterjenis($_POST['filterpabrik'], 3);
		echo json_encode($limbahudara);
	}	

	private function get_limbah_filterplant(){
		$this->general->connectDbPortal();
		$limbahb3 = $this->dmastershe->get_data_limbah($_POST['filterpabrik'], NULL, NULL);
		echo json_encode($limbahb3);
	}	

 

}