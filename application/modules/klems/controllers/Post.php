<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KLEMS (Kirana Learning Management System)
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Post extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->model('dtransaksiklems');
		ini_set('max_execution_time', 3000000); 
	}

	public function index(){
		show_404();
	}

	public function upload() {
		$tbl_materi = $this->dtransaksiklems->get_data_materi(NULL, NULL, 1);
		// $tbl_materi = $this->dtransaksiklems->get_data_materi(NULL, NULL, NULL); //set null jika tidak dibatasi tanggal upload materi
		foreach ($tbl_materi as $dt) {
			$data      = $dt;

			$ch = curl_init(base_url() . $data->files);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if ($code == 200) {
				$tbl_plant = $this->dtransaksiklems->get_data_plant('NSI1');
				// $tbl_plant = $this->dtransaksiklems->get_data_plant(null);
				foreach ($tbl_plant as $plant) {
					$this->upload_files($data, NULL, NULL,$plant->ip);	
				}
			}
		}
	}

	public function upload_files($data = NULL, $path = NULL, $newname = NULL, $ip = NULL) {
		// $url        = 'http://10.0.9.37:8080/get_file/accept.php';
		$url        = $ip.'/kiranaku/assets/file/klems/materi/accept.php';
		$file_name_with_full_path = realpath($_SERVER['DOCUMENT_ROOT'] . parse_url( base_url() . $data->files, PHP_URL_PATH ));
		if (function_exists('curl_file_create')) { // php 5.5+
			$cFile = curl_file_create($file_name_with_full_path, NULL, basename($file_name_with_full_path));
		} else {
			$cFile = '@' . realpath($file_name_with_full_path);
		}
		$filename = basename($file_name_with_full_path);

		$post = array('key' => base64_encode("kmgroup"),
					  'path' => $path,
					  'file_contents' => $cFile,
					  'newname' => $filename);

		$ch   = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);

		if ($result == false) {
			echo '<pre>Curl error: ' . curl_error($ch);
		} else {
			$datetime       = date("Y-m-d H:i:s");
			$data_row   = array(
								'id_materi'	    => $data->id_materi,
								'ip_asal'		=> '10.0.9.37',
								'ip_tujuan'		=> $ip,
								'status'		=> 'Berhasil',
								'na'     		=> 'n',
								'del'     		=> 'n',
								'login_buat'    => '1',
								'tanggal_buat'	=> $datetime,
								'login_edit'    => '1',
								'tanggal_edit'  => $datetime
							);
			$this->dgeneral->insert('tbl_materi_log', $data_row);
			// echo json_encode($result);
		}
		curl_close($ch);
	}
	
}
