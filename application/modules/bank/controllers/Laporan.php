<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : BANK SPECIMEN
@author       : Lukman Hakim (7143)
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
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		
		$this->load->model('dmasterbank');
	    $this->load->model('dsettingbank');
	    $this->load->model('dtransaksibank');
	    $this->load->model('dlaporanbank');
	}

	public function index(){
		show_404();
	}

	public function rekening($param=NULL){
		//====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']  	= "Laporan Rekening";
		$nik				= base64_decode($this->session->userdata("-nik-"));
		$posst 				= base64_decode($this->session->userdata("-posst-"));
		$data['user_role']	= $this->dtransaksibank->get_data_user_role("open", $nik, $posst);;
		$this->load->view("laporan/rekening", $data);	
	}
	
    public function excel($param=NULL, $param2=NULL){
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=Data Bank Specimen.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		if(!empty($_GET['pabrik_filter'])){
			$arr_pabrik		= explode(',',$_GET['pabrik_filter']);
			$pabrik_filter	= array();
			foreach ($arr_pabrik as $dt) {
				array_push($pabrik_filter, $dt);
			}
		}else{
			$pabrik_filter  = NULL;
		}
		
		$list_data = $this->dtransaksibank->get_data_bank('open', NULL, NULL, NULL, NULL, NULL, NULL, $pabrik_filter, 'y');
		$list = "";
		foreach ($list_data as $data) {
			$list.="<tr><td>".$data->pabrik."</td><td>".$data->nama_bank."</td><td>".$data->cabang_bank."</td><td>".$data->nomor_rekening."</td><td>".$data->no_coa."</td><td>".$data->mata_uang."</td><td>".$data->caption_tujuan."</td><td>".$data->prioritas1." - ".$data->nama_prioritas1."</td><td>".$data->prioritas2." - ".$data->nama_prioritas2."</td><td>".$data->caption_list_pendamping."</td><td>".$data->caption_na."</td></tr>";
		}
		echo"
		<table border='1'>
			<tr>
				<th>Pabrik</th>
				<th>Nama Bank</th>
				<th>Cabang</th>
				<th>Nomor Rekening</th>
				<th>No COA</th>
				<th>Mata Uang</th>
				<th>Tujuan</th>
				<th>Prioritas 1</th>
				<th>Prioritas 2</th>
				<th>Pendamping</th>
				<th>Status</th>
			</tr>
			$list 
		</table>
		";
    }	
	
}