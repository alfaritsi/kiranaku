<?php
/*
        @application  : Data
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 04-Dec-18
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */

class BaseControllers extends MX_Controller
{
	protected $data;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('sap');
	}

	public function index()
	{
		show_404();
	}

	protected function connectSAP($user)
	{
		$koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);
		$conn    = $koneksi[$user];
		$constr  = array(
			"logindata"   => array(
				"ASHOST" => $conn['ASHOST'],    // application server
				"SYSNR"  => $conn['SYSNR'],        // system number
				"CLIENT" => $conn['CLIENT'],    // client
				"USER"   => $conn['USER'],        // user
				"PASSWD" => $conn['PASSWD']        // password
			),
			"show_errors" => $conn['DEBUG'],                // let class printout errors
			"debug"       => $conn['DEBUG']
		);            // detailed debugging information

		$this->data['sap'] = new saprfc($constr);
	}

	protected function curl($param = NULL)
	{
		if ($param) {
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, $param['method']);
			curl_setopt($curl_handle, CURLOPT_URL, @$param['url']);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_handle, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl_handle, CURLOPT_FAILONERROR, true);
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 20);
			curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
			curl_setopt($curl_handle, CURLOPT_TIMEOUT, 20);
			curl_setopt($curl_handle, CURLOPT_AUTOREFERER, true);
			curl_setopt($curl_handle, CURLOPT_COOKIEFILE, "");
			curl_setopt($curl_handle, CURLOPT_VERBOSE, true);
			curl_setopt($curl_handle, CURLOPT_HTTPHEADER, @$param['header']);
			if ($param['data'])
				curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $param['data']);
			$res = curl_exec($curl_handle);
			if (curl_error($curl_handle)) {
				$res = curl_error($curl_handle);
			}
			$httpcode = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
			curl_close($curl_handle);

			return $res;
		}
	}
}
