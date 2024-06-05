<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : MENTORING
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

include_once APPPATH . "modules/depo/controllers/BaseControllers.php";

class Laporan extends MX_Controller
// class Transaksi extends BaseControllers
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('PHPExcel');
		$this->load->helper(array('form', 'url'));

		$this->load->model('dmastermentor');
		$this->load->model('dtransaksimentor');
		$this->load->model('dlaporanmentor');
	}

	public function index()
	{
		show_404();
	}
	
	public function ass($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data Assessment VIA";
		$data['status']		= $this->dtransaksimentor->get_data_status("open",NULL,'n','n');
		$this->load->view("laporan/ass", $data);
	}
	public function aim($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data AIM";
		$data['status']		= $this->dtransaksimentor->get_data_status("open",NULL,'n','n');
		$this->load->view("laporan/aim", $data);
	}
	public function dmc($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data DMC";
		$data['status']		= $this->dtransaksimentor->get_data_status("open",NULL,'n','n');
		$this->load->view("laporan/dmc", $data);
	}
	public function rating($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data Mentee Rating";
		$data['status']		= $this->dtransaksimentor->get_data_status("open",NULL,'n','n');
		$this->load->view("laporan/rating", $data);
	}
    public function excel($param=NULL){
		if($param=='ass'){
			$file_name = "Data Assessment VIA.xls";
		}else if(($param=='aim')){
			$file_name = "Data AIM.xls";
		}else if(($param=='dmc')){
			$file_name = "Data DMC.xls";
		}else if(($param=='dmc')){
			$file_name = "Data Rating.xls";
		}else{
			$file_name = "Data Excel.xls";
		}
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$file_name");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		if(!empty($_GET['filter_status'])){
			$arr_status	= explode(',',$_GET['filter_status']);
			$filter_status	= array();
			foreach ($arr_status as $dt) {
				array_push($filter_status, $dt);
			}
		}else{
			$filter_status  = NULL;
		}
		
		$list_data = $this->dlaporanmentor->get_data_all('open', NULL, NULL, NULL, $filter_status, $param);
		$list = "";
		foreach ($list_data as $data) {
			$list.= "<tr>";
			$list.= "<td>".$data->nomor."</td>";
			$list.= "<td>".$data->nik_mentor."</td>";
			$list.= "<td>".$data->nama_mentor."</td>";
			$list.= "<td>".$data->nik_mentor_additional."</td>";
			$list.= "<td>".$data->nama_mentor_additional."</td>";
			$list.= "<td>".$data->tanggal_sesi2_rencana_format."</td>";
			$list.= "<td>".$data->tanggal_sesi2_aktual_format."</td>";
			$list.= "<td>".$data->nama_status."</td>";
			$list.= "<td>".$data->nik_mentee."</td>";
			$list.= "<td>".$data->nama_mentee."</td>";
			$list.= "<td>".$data->nama_departemen_mentee."</td>";
			if($param=='ass'){
				$list.= "<td><a href='./".$data->url_file."' target='_blank'>Dokumen AIM</a></td>";
			}else if($param=='aim'){
				$list.= "<td>".$data->sasaran_pengembangan."</td>";	
			}else if($param=='dmc'){
				if($data->status==3){
					$list.= "<td>".$data->tujuan_dmc1."</td>";	
					$list.= "<td>".$data->realitas_dmc1."</td>";	
					$list.= "<td>".$data->opsi_dmc1."</td>";	
					$list.= "<td>".$data->rencana_aksi_dmc1."</td>";	
					$list.= "<td>".$data->waktu_dmc1."</td>";	
					$list.= "<td>".$data->indikator_berhasil_dmc1."</td>";	
					$list.= "<td>".$data->catatan_dmc1."</td>";	
				}
				if($data->status==4){
					$list.= "<td>".$data->tujuan_dmc2."</td>";	
					$list.= "<td>".$data->realitas_dmc2."</td>";	
					$list.= "<td>".$data->opsi_dmc2."</td>";	
					$list.= "<td>".$data->rencana_aksi_dmc2."</td>";	
					$list.= "<td>".$data->waktu_dmc2."</td>";	
					$list.= "<td>".$data->indikator_berhasil_dmc2."</td>";	
					$list.= "<td>".$data->catatan_dmc2."</td>";	
				}
				if($data->status==5){
					$list.= "<td>".$data->tujuan_dmc3."</td>";	
					$list.= "<td>".$data->realitas_dmc3."</td>";	
					$list.= "<td>".$data->opsi_dmc3."</td>";	
					$list.= "<td>".$data->rencana_aksi_dmc3."</td>";	
					$list.= "<td>".$data->waktu_dmc3."</td>";	
					$list.= "<td>".$data->indikator_berhasil_dmc3."</td>";	
					$list.= "<td>".$data->catatan_dmc3."</td>";	
				}
			}else if($param=='rating'){
				if($data->nama_mentor_additional!=null){
					$list.= "<td>".$data->mantee_rate_mentor_additional."</td>";	
					$list.= "<td>".$data->comm_rate_mentor_additional."</td>";	
					$list.= "<td>".($data->mantee_rate_mentor_additional + $data->comm_rate_mentor_additional)/2 ."</td>";	
				}else{
					$list.= "<td>".$data->mantee_rate_mentor."</td>";	
					$list.= "<td>".$data->comm_rate_mentor."</td>";	
					$list.= "<td>".($data->mantee_rate_mentor + $data->comm_rate_mentor)/2 ."</td>";	
				}
			}else{
				
			}
			
			$list.= "</tr>";
		}
		echo"
		<table border='1'>
			<tr>
				<th>Nomor</th>
				<th>NIK Mentor</th>
				<th>Nama Mentor</th>
				<th>NIK Mentor<br>(Additional)</th>
				<th>Nama Mentor<br>(Additional)</th>
				<th>Tanggal Sesi (Rencana)</th>
				<th>Tanggal Sesi (Aktual)</th>
				<th>Jenis Sesi</th>
				<th>NIK Mentee</th>
				<th>Nama Mentee</th>
				<th>Departemen Mentee</th>";
				if($param=='ass'){
					echo "<th>Assessment VIA</th>";	
				}else if($param=='aim'){
					echo "<th>Narasi AIM</th>";	
				}else if($param=='dmc'){
					echo "<th>Tujuan Sesi</th>";	
					echo "<th>Realitas</th>";	
					echo "<th>Opsi</th>";	
					echo "<th>Rencana Aksi</th>";	
					echo "<th>Waktu</th>";	
					echo "<th>Indikator Keberhasilan</th>";	
					echo "<th>Catatan</th>";	
				}else if($param=='rating'){
					echo "<th>Metee Rate</th>";	
					echo "<th>Comm Rate</th>";	
					echo "<th>Over All</th>";	
				}else{
					
				}
		echo"
			</tr>
			$list 
		</table>
		";
    }	
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'all':
				$nomor  = (isset($_POST['nomor']) ? $this->generate->kirana_decrypt($_POST['nomor']) : NULL);
				$jenis  = (isset($_POST['jenis']) ? $_POST['jenis'] : NULL);
				//filter status
				if (isset($_POST['filter_status'])) {
					$filter_status	= array();
					foreach ($_POST['filter_status'] as $dt) {
						array_push($filter_status, $dt);
					}
				} else {
					$filter_status  = NULL;
				}

				if ($param2 == 'bom') {
					header('Content-Type: application/json');
					$return = $this->dlaporanmentor->get_data_all_bom('open', $nomor, NULL, NULL, $filter_status, $jenis);
					echo $return;
					break;
				} else {
					$this->get_dmc(NULL, $nomor, 'n', 'n');
					break;
				}
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_dmc($array = NULL, $nomor = NULL, $na = NULL, $del = NULL)
	{
		//header
		$data	= $this->dlaporanmentor->get_data_dmc("open", $nomor, $na, $del);
		$data 	= $this->general->generate_encrypt_json($data, array("nomor"));
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	/*====================================================================*/
}
