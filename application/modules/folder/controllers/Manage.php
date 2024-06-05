<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Folder Explorer
@author       : Matthew Jodi
@contributor  : 
      1. <Lukman Hakim> (7143) 05.03.2020
         Menambahkan module Dokumen Vendor         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Manage extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('dsettingfolder');
	}

	public function index()
	{
		show_404();
	}

	public function data()
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['divisis'] = $this->dsettingfolder->get_divisi('open');
		$data['level']   = $this->dsettingfolder->get_level('open');
		$data['nik']       = base64_decode($this->session->userdata('-id_karyawan-'));

		$this->load->view("folder", $data);
	}

	public function sop()
	{
		//====must be initiate in every view function====/
		$this->general->check_session();
		$data['generate']   = $this->generate;
		$data['module']     = 'SOP';
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['divisis'] = $this->dsettingfolder->get_divisi('open');
		$data['level']   = $this->dsettingfolder->get_level('open');
		$data['nik'] = base64_decode($this->session->userdata('-id_karyawan-'));

		// $key = isset($param) ? $this->generate->kirana_decrypt($param) : null;
		// $data['keys'] = $param;
		// $data['paramet'] = $key;
		// TlcvMmRpdDdEZW9iSFFoeWdKbmlIdz09

		$this->load->view("sop", $data);
	}

	public function knowledge()
	{
		//====must be initiate in every view function====/
		$this->general->check_session();
		$data['generate']   = $this->generate;
		$data['module']     = 'Knowledge Management';
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['divisis'] = $this->dsettingfolder->get_divisi('open');
		$data['level']   = $this->dsettingfolder->get_level('open');
		$data['nik'] = base64_decode($this->session->userdata('-id_karyawan-'));

		$this->load->view("knowledge", $data);
	}

	public function manual()
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = 'USER MANUAL';
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['divisis'] = $this->dsettingfolder->get_divisi('open');
		$data['level']   = $this->dsettingfolder->get_level('open');
		$data['nik'] = base64_decode($this->session->userdata('-id_karyawan-'));

		$this->load->view("manual", $data);
	}

	//add lha 05.03.2020
	public function vendor()
	{
		//====must be initiate in every view function====/
		$this->general->check_session();
		$data['generate']   = $this->generate;
		$data['module']     = 'Dokumen Vendor';
		$data['user']       = $this->general->get_data_user();
		//===============================================/
		$data['divisis'] = $this->dsettingfolder->get_divisi('open');
		$data['level']   = $this->dsettingfolder->get_level('open');
		$data['nik'] = base64_decode($this->session->userdata('-id_karyawan-'));

		$this->load->view("vendor", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get_data($param)
	{
		switch ($param) {
			case 'folder':
				$this->get_folder();
				break;

			case 'file':
				$this->get_file();
				break;

			case 'folder-file':
				$this->get_folder_file();
				break;

			default:
				$return = array();
				echo json_encode($return);
				break;
		}
	}

	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_folder_file()
	{
		if (isset($_POST['id_folder']) && $_POST['id_folder'] != 0) {
			$id_folder 	 		= $_POST['id_folder'];
			$id_level 	 		= base64_decode($this->session->userdata('-id_level-'));
			$divisi_read 		= base64_decode($this->session->userdata('-id_divisi-'));
			$department_read 	= base64_decode($this->session->userdata('-id_departemen-'));
			$nik 				= base64_decode($this->session->userdata('-id_karyawan-'));
			$admin_folder		= NULL;
			$folder_sign  		= NULL;

			$search_grandparent  = $this->dsettingfolder->get_grandparent('open', $id_folder);
			if (isset($search_grandparent->parent_folder) && $search_grandparent->parent_folder != '0') {
				$id_grandparent     = $search_grandparent->parent_folder;
				$queryy 			= $this->dsettingfolder->get_folder_by_id('open', $id_grandparent); // cari nama grandparent
				$nama_grandparent   = $queryy->nama;
			} else {
				$id_grandparent = '0';
				$nama_grandparent = 'KIRANA FOLDER';
			}


			if ($_POST['isAdmin'] != null) {
				$admin  	= $this->dsettingfolder->cek_folder_admins('open', $_POST['isAdmin'], $nik);
				if (isset($admin)) {
					$admin_folder = $_POST['isAdmin'];
				}
			}
		} else {
			$id_folder 			= 0;
			$id_level 	 		= base64_decode($this->session->userdata('-id_level-'));
			$divisi_read 		= base64_decode($this->session->userdata('-id_divisi-'));
			$department_read 	= base64_decode($this->session->userdata('-id_departemen-'));
			$nik 				= base64_decode($this->session->userdata('-id_karyawan-'));
			$admin_folder       = NULL;
			$id_grandparent 	= NULL;
			$nama_grandparent   = NULL;

			if ($_POST['folder'] == "sop") {
				$folder_sign = $_POST['folder'];
			} else if ($_POST['folder'] == "manual") {
				$folder_sign = $_POST['folder'];
			} else if ($_POST['folder'] == "knowledge") {
				$folder_sign = $_POST['folder'];
			} else {
				$folder_sign  = NULL;
			}
		}


		$folder = $this->dsettingfolder->get_data_folder('open', $id_folder, null, null, $divisi_read, $department_read, $admin_folder, $nik, 	      $id_level, $folder_sign);
		$file   = $this->dsettingfolder->get_data_file('open', $id_folder, null, null, $divisi_read, $department_read, $admin_folder, $nik, 		  $id_level);
		$grandparent 	= $this->dsettingfolder->get_grandparent('open', $id_folder, $department_read, $id_level, $admin_folder, $nik, $id_grandparent, $nama_grandparent);
		$array 	= new stdClass();
		$array->folder 	= $folder;
		$array->file 	= $file;
		$array->grandparent = (count($grandparent) > 0 ? $grandparent : NULL);
		echo json_encode($array);
	}

	private function get_folder()
	{

		if (isset($_POST['id_folder'])) {
			$id_folder = $_POST['id_folder'];
		} else {
			$id_folder = 0;
		}

		$folder         	= $this->dsettingfolder->get_data_folder_action('open', $id_folder);
		echo json_encode($folder);
	}

	private function get_file()
	{

		if (isset($_POST['id_folder'])) {
			$id_folder = $_POST['id_folder'];
		} else {
			$id_folder = 0;
		}

		$file         	= $this->dsettingfolder->get_data_file_action('open', $id_folder, 'all');
		echo json_encode($file);
	}


	/**********************************/
	/*			Transaction			  */
	/**********************************/
	public function cek_table_param()
	{
		$id_file = $this->generate->kirana_decrypt($_POST['key']);
		$id_level 	 		= base64_decode($this->session->userdata('-id_level-'));
		$divisi 			= base64_decode($this->session->userdata('-id_divisi-'));
		$department 		= base64_decode($this->session->userdata('-id_departemen-'));
		$nik 				= base64_decode($this->session->userdata('-id_karyawan-'));


		$file_data = $this->dsettingfolder->get_file_by_id('open', $id_file, null, $divisi, $department, $id_level);
		$akses 	   = $this->dsettingfolder->get_akses_notif('open', $file_data->id_folder, $id_level, $divisi, $department, $nik, $file_data->id_root_folder);

		$file_data->parent_admin_akses 	= $akses->akses_admin;
		$file_data->parent_div_read 	= $akses->akses_divisi_read;
		$file_data->parent_dept_read 	= $akses->akses_department_read;
		$file_data->parent_level_read 	= $akses->akses_level_read;
		$file_data->parent_div_write 	= $akses->akses_divisi_write;
		$file_data->parent_dept_write 	= $akses->akses_department_write;
		$file_data->parent_level_write 	= $akses->akses_level_write;

		echo json_encode($file_data);
	}

	public function cek_akses_root_sop()
	{
		//Khusus untuk sop. memungkinkan untuk menampilkan tombol toolbar jika merupakan admin
		$id_level 	 		= base64_decode($this->session->userdata('-id_level-'));
		$divisi 			= base64_decode($this->session->userdata('-id_divisi-'));
		$department 		= base64_decode($this->session->userdata('-id_departemen-'));
		$nik 				= base64_decode($this->session->userdata('-id_karyawan-'));

		$id_folder = $_POST['id_folder'];

		$cek_akses = $this->dsettingfolder->get_akses_root_sop('open', $id_folder, $id_level, $divisi, $department, $nik);
		echo json_encode($cek_akses);
	}

	public function cek_akses_root_knowledge()
	{
		//Khusus untuk sop. memungkinkan untuk menampilkan tombol toolbar jika merupakan admin
		$id_level 	 		= base64_decode($this->session->userdata('-id_level-'));
		$divisi 			= base64_decode($this->session->userdata('-id_divisi-'));
		$department 		= base64_decode($this->session->userdata('-id_departemen-'));
		$nik 				= base64_decode($this->session->userdata('-id_karyawan-'));

		$id_folder = $_POST['id_folder'];

		$cek_akses = $this->dsettingfolder->get_akses_root_sop('open', $id_folder, $id_level, $divisi, $department, $nik);
		echo json_encode($cek_akses);
	}
	public function cek_akses_root_vendor()
	{
		//Khusus untuk sop. memungkinkan untuk menampilkan tombol toolbar jika merupakan admin
		$id_level 	 		= base64_decode($this->session->userdata('-id_level-'));
		$divisi 			= base64_decode($this->session->userdata('-id_divisi-'));
		$department 		= base64_decode($this->session->userdata('-id_departemen-'));
		$nik 				= base64_decode($this->session->userdata('-id_karyawan-'));

		$id_folder = $_POST['id_folder'];

		$cek_akses = $this->dsettingfolder->get_akses_root_sop('open', $id_folder, $id_level, $divisi, $department, $nik);
		echo json_encode($cek_akses);
	}

	public function get_prev_name()
	{
		$id 		= $_POST['id_folder'];
		$tipe		= $_POST['tipe'];

		$files  = $this->dsettingfolder->get_folder_by_id('open', $id);

		if ($tipe != 'Folder') {
			$files  = $this->dsettingfolder->get_file_by_id('open', $id);
			$files->exists = false;
			$files->link = preg_replace('/\s+/', '%20', $files->link);
																	
											 
						  
	


			//ADD VIEW COUNT WHEN FILE EXISTS
			if (isset($_POST['log']) && $_POST['log'] == 'yes') {
				$ip_user = $_SERVER['REMOTE_ADDR'];
				$this->load->model('data/dtransaksidata', 'dtransaksidata');
				$param = new stdClass();
				$param->ip_user = explode('.', $ip_user)[2];
				$server = $this->dtransaksidata->get_file_server('open', $param);
				if (empty($server) || $server[0]->WERKS == 'KMTR')
					$link = BASE_URL() . 'assets/' . $files->link;
				else
					$link = 'http://' . $server[0]->ip . '/file/assets/' . $files->link;

				$file_exist = get_headers($link);
				if ($file_exist[0] == 'HTTP/1.1 200 OK') {
					$files->exists = true;
				} else {
					$file_exist = get_headers(BASE_URL() . 'assets/' . $files->link);
					if ($file_exist[0] == 'HTTP/1.1 200 OK') {
						$files->exists = true;
					}
				}

				if ($files->exists == true) {
					$data_row = array(
						"id_user" => base64_decode($this->session->userdata("-id_user-")),
						"id_file" => $id,
						"datetime" => date("Y-m-d H:i:s")
					);
					$this->dgeneral->insert('tbl_file_log_access', $data_row);
				}

				$files->log = $this->dsettingfolder->get_log_access('open', $id);
				$files->address = $link;
			}
		}

		echo json_encode($files);
	}

	//ROOT FOLDER KHUSUS IT
	public function new_root_folder()
	{

		$name		= $_POST['name'];
		$datetime   = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$folder         = $this->dsettingfolder->get_data_folder_action(null, '0', null, $name);
		if (isset($name) && $name !== "" && count($folder) == 0) {

			$data_row   = array(
				'nama'      		=> $name,
				'parent_folder'   => '0',
				'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
				'tanggal_buat'    => $datetime,
				'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
				'tanggal_edit'    => $datetime,
				'na'				=> "n",
				'del'				=> "n"
			);
			$this->dgeneral->insert('tbl_folder', $data_row);


			if ($this->dgeneral->status_transaction() === FALSE) {
				$this->dgeneral->rollback_transaction();
				$msg    = "Please re-check the submitted data";
				$sts    = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg    = "Succesfully added data";
				$sts    = "OK";
			}
		} else {
			$msg    = "This name has been used. Please choose a different name";
			$sts    = "NotOK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	public function set_root_folder_admin()
	{
		$datetime   = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		//cek data delete
		$ckd = $this->dsettingfolder->cek_root_admin(null, $_POST['folder'], null, null);
		// var_dump($ckd);
		foreach ($ckd as $cd) {
			// var_dump($cd->nik);
			if (!in_array($cd->nik, $_POST['admin'])) {
				$data_row   = array(
					'nik'      	    => $cd->nik,
					'id_folder'   	=> $_POST['folder'],
					'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'    => $datetime,
					'del'				=> 'y'
				);
				$this->dgeneral->update('tbl_folder_role', $data_row, array(
					array(
						'kolom' => 'id_folder_role',
						'value' => $cd->id_folder_role
					)
				));
			}
		}

		// cek add data
		foreach ($_POST['admin'] as $abc) {

			$cek = $this->dsettingfolder->cek_folder_admins(null, $_POST['folder'], $abc, 'all');

			if (isset($cek)) {
				// echo "sukses";
				$data_row   = array(
					'nik'      	    => $abc,
					'id_folder'   	=> $_POST['folder'],
					'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'    => $datetime,
					'del'				=> 'n',
				);
				$this->dgeneral->update('tbl_folder_role', $data_row, array(
					array(
						'kolom' => 'id_folder_role',
						'value' => $cek->id_folder_role
					)
				));
			} else {
				// echo"gagal";
				$data_row   = array(
					'nik'      		=> $abc,
					'id_folder'   	=> $_POST['folder'],
					'login_buat'      => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'    => $datetime,
					'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'    => $datetime,
					'del'				=> "n"
				);
				$this->dgeneral->insert('tbl_folder_role', $data_row);
			}
		}

		if ($this->dgeneral->status_transaction() === FALSE) {
			$this->dgeneral->rollback_transaction();
			$msg    = "Please re-check the submitted data";
			$sts    = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg    = "Successfuly added data";
			$sts    = "OK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	public function rename()
	{

		$id 		= $_POST['id_folder'];
		$name		= $_POST['name'];
		$tipe		= $_POST['tipe'];
		$parent		= $_POST['parent'];
		$datetime   = date("Y-m-d H:i:s");

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();


		if ($tipe != 'Folder') {
			//RENAME FILE

			//if rename == deleted file then rename deleted file with running number
			$validate =  $this->dsettingfolder->get_file_by_name(null, null, 'all', $name, 'deleted');
			if (isset($validate) && $validate != "") {

				$number = (count($this->dsettingfolder->get_data_file_action(null, null, 'all', null, 'deleted', $validate->nama)) + 1);
				$unik 	= "_beenDeleted";
				$very_unique = "_extra";
				$vid 	= $validate->id_file;
				$vtipe  = $validate->tipe;
				$vlink  = $validate->link;
				$new_name = $validate->nama . $unik . $number;
				$extraordinary_name = $validate->nama . $unik . $number . $very_unique;
				$validates =  $this->dsettingfolder->get_file_by_name(null, null, 'all', $new_name);
				if ($validates == "") {
					$oldname  = $vlink;
					$uploadir = realpath('./') . '/assets/file/folder/';

					if (!file_exists($uploadir)) {
						$msg    = "This File Location is Unknown";
						$sts    = "NotOK";
					} else {
						rename(realpath('./') . "/assets/$oldname", realpath('./') . "/assets/file/folder/$new_name.$vtipe");
						$data_row   = array(
							'nama'      		=> $new_name,
							'link' 			=> "file/folder/$new_name.$vtipe",
							'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'    => $datetime
						);
						$this->dgeneral->update('tbl_file', $data_row, array(
							array(
								'kolom' => 'id_file',
								'value' => $vid
							)
						));
					}
				} else {
					$new_name = $extraordinary_name;
					$oldname  = $vlink;
					$uploadir = realpath('./') . '/assets/file/folder/';

					if (!file_exists($uploadir)) {
						$msg    = "This File Location is Unknown";
						$sts    = "NotOK";
					} else {
						rename(realpath('./') . "/assets/$oldname", realpath('./') . "/assets/file/folder/$new_name.$vtipe");
						$data_row   = array(
							'nama'      		=> $new_name,
							'link' 			=> "file/folder/$new_name.$vtipe",
							'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'    => $datetime
						);
						$this->dgeneral->update('tbl_file', $data_row, array(
							array(
								'kolom' => 'id_file',
								'value' => $vid
							)
						));
					}
				}
			}
			//end

			if (preg_match('/[^A-Za-z0-9\s-_.,]/', $name)) {
				$msg    = "File name should only contain Alphabet, Number, and Special Char (Dot . | Comma , | Dash - | Underscore _ )";
				$sts    = "NotOK";
			} else {

				$file         = $this->dsettingfolder->get_data_file_action(null, null, 'all', $name);
				if (isset($name) && $name !== "" && count($file) == 0) {
					$filedata = $this->dsettingfolder->get_file_by_id(null, $id, 'all');
					$oldname  = $filedata->link;
					$uploadir = realpath('./') . '/assets/file/folder/';

					if (!file_exists($uploadir)) {
						$msg    = "This File Location is Unknown";
						$sts    = "NotOK";
					} else {
						rename(realpath('./') . "/assets/$oldname", realpath('./') . "/assets/file/folder/$name.$filedata->tipe");
						$data_row   = array(
							// 'id_file_encrypted'	=> $this->generate->kirana_encrypt($id),
							'nama'      			=> $name,
							'link' 				=> "file/folder/$name.$filedata->tipe",
							'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'    	=> $datetime
						);
						$this->dgeneral->update('tbl_file', $data_row, array(
							array(
								'kolom' => 'id_file',
								'value' => $id
							)
						));


						if ($this->dgeneral->status_transaction() === FALSE) {
							$this->dgeneral->rollback_transaction();
							$msg    = "Please re-check the submitted data";
							$sts    = "NotOK";
						} else {
							$this->dgeneral->commit_transaction();
							$msg    = "Succesfully added data";
							$sts    = "OK";
						}
					}
				} else {
					$msg    = "This name has been used. Please choose a different name";
					$sts    = "NotOK";
				}
			}
		} else {
			//RENAME FOLDER
			if (strpos($name, '\\') == TRUE) {
				$msg    = "Folder name can't contain backslash";
				$sts    = "NotOK";
			} else {

				$folder         = $this->dsettingfolder->get_data_folder_action(null, $parent, null, $name);
				if (isset($name) && $name !== "" && count($folder) == 0) {

					$data_row   = array(
						'nama'      		=> $name,
						'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'    => $datetime
					);
					$this->dgeneral->update('tbl_folder', $data_row, array(
						array(
							'kolom' => 'id_folder',
							'value' => $id
						)
					));

					$this->dsettingfolder->update_folder_path(null);

					if ($this->dgeneral->status_transaction() === FALSE) {
						$this->dgeneral->rollback_transaction();
						$msg    = "Please re-check the submitted data";
						$sts    = "NotOK";
					} else {
						$this->dgeneral->commit_transaction();
						$msg    = "Succesfully added data";
						$sts    = "OK";
					}
				} else {
					$msg    = "This name has been used. Please choose a different name";
					$sts    = "NotOK";
				}
			}
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	public function rename_root_folder()
	{
		$name   = $_POST['name'];
		$id     = $_POST['id_folder'];
		$parent = '0';
		$datetime   	 = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$folder         = $this->dsettingfolder->get_data_folder_action(null, $parent, null, $name);
		if (isset($name) && $name !== "" && count($folder) == 0) {

			$data_row   = array(
				'nama'      		=> $name,
				'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
				'tanggal_edit'    => $datetime
			);
			$this->dgeneral->update('tbl_folder', $data_row, array(
				array(
					'kolom' => 'id_folder',
					'value' => $id
				)
			));

			$this->dsettingfolder->update_folder_path(null);

			if ($this->dgeneral->status_transaction() === FALSE) {
				$this->dgeneral->rollback_transaction();
				$msg    = "Please re-check the submitted data";
				$sts    = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg    = "Succesfully added data";
				$sts    = "OK";
			}
		} else {
			$msg    = "This name has been used. Please choose a different name";
			$sts    = "NotOK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	public function new_folder()
	{

		$id 		 	 = $_POST['id_folder'];
		$name		 	 = $_POST['name'];
		$divisi 	 	 = base64_decode($this->session->userdata('-id_divisi-'));
		$id_level 	 	 = base64_decode($this->session->userdata('-id_level-'));
		$department 	 = base64_decode($this->session->userdata('-id_departemen-'));
		$datetime   	 = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$folder         = $this->dsettingfolder->get_data_folder_action(null, $id, null, $name);
		if (isset($name) && $name !== "" && count($folder) == 0) {

			$akses_parent = $this->dsettingfolder->get_folder_by_id(null, $id);

			$data_row   = array(
				'nama'      			=> $name,
				'parent_folder'   	=> $id,
				'divisi_akses'    	=> $akses_parent->divisi_akses,
				'departemen_akses'    => $akses_parent->departemen_akses,
				'divisi_write'    	=> $akses_parent->divisi_write,
				'departemen_write'    => $akses_parent->departemen_write,
				'level_akses'         => $akses_parent->level_akses,
				'level_write'         => $akses_parent->level_write,
				'login_buat'      	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_buat'    	=> $datetime,
				'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_edit'    	=> $datetime,
				'na'					=> "n",
				'del'					=> "n"
			);
			$this->dgeneral->insert('tbl_folder', $data_row);


			if ($this->dgeneral->status_transaction() === FALSE) {
				$this->dgeneral->rollback_transaction();
				$msg    = "Please re-check the submitted data";
				$sts    = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg    = "Succesfully added data";
				$sts    = "OK";
			}
		} else {
			$msg    = "This name has been used. Please choose a different name";
			$sts    = "NotOK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	public function cek_delete()
	{
		$id_folder = $_POST['id_folder'];
		$folder 		= $this->dsettingfolder->get_data_folder_action('open', $id_folder, null);
		$file 			= $this->dsettingfolder->get_data_file_action('open', $id_folder, null);

		if (count($folder) == 0 && count($file) == 0) {
			$msg    = "Folder Empty";
			$sts    = "OK";
		} else {
			$msg    = "Please empty the folder first before deleting";
			$sts    = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}


	public function delete()
	{
		$id		    = $_POST['id_folder'];
		$tipe		= $_POST['tipe'];
		$datetime   = date("Y-m-d H:i:s");
		$table 		= 'tbl_folder';
		$key		= 'id_folder';

		if ($tipe  != 'Folder') {
			$table  = 'tbl_file';
			$key	= 'id_file';
		}
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$data_row   = array(
			'na'				=> "y",
			'del'				=> "y",
			'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			'tanggal_edit'    => $datetime
		);
		$this->dgeneral->update($table, $data_row, array(
			array(
				'kolom' => $key,
				'value' => $id
			)
		));


		if ($this->dgeneral->status_transaction() === FALSE) {
			$this->dgeneral->rollback_transaction();
			$msg    = "Please re-check the submitted data";
			$sts    = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg    = "Data Deleted";
			$sts    = "OK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	public function reupload()
	{
		$datetime   = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		$id_file 		= $_POST['id_file'];
		$filename 		= $_POST['name'];

		// CEK DUPLICATE FILENAME dan TIPE
		$check = $this->dsettingfolder->get_file_by_id(null, $id_file, 'all');
		$check_nama = $check->nama.'.'.$check->tipe;

		
		$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
		$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|swf|vsd';
		// $config['max_size']      = 100;

		if (isset($_FILES['fileReupload']) && $check_nama == $filename) { //save the file
			$files = $this->general->upload_files($_FILES['fileReupload'], null, $config);
			
			$data_row   = array(
				'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_edit'    	=> $datetime
			);
			$this->dgeneral->update('tbl_file', $data_row, array(
				array(
					'kolom' => 'id_file',
					'value' => $id_file
				)
			));


			if ($this->dgeneral->status_transaction() === FALSE) { 
				$this->dgeneral->rollback_transaction();
				$msg    = "Please re-check the submitted data";
				$sts    = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg    = "Succesfully added data";
				$sts    = "OK";
			}
		} else {
			$msg    = "Please re-check the submitted data";
			$sts    = "NotOK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		
	}

	public function upload_new_file()
	{
		$datetime   = date("Y-m-d H:i:s");
		$divisi 	= base64_decode($this->session->userdata('-id_divisi-'));
		$department = base64_decode($this->session->userdata('-id_departemen-'));
		$id_level 	= base64_decode($this->session->userdata('-id_level-'));
		$id 		= $_POST['id_folder'];
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		//if upload == deleted file then rename deleted file with running number
		$uploaded_file = explode(',',  $_POST['name']);
		foreach ($uploaded_file as $d) {
			$validate =  $this->dsettingfolder->get_file_by_name(null, null, 'all', $d, 'deleted');

			if (isset($validate) && $validate != "") {
				$number = (count($this->dsettingfolder->get_data_file_action(null, null, 'all', null, 'deleted', $validate->nama)) + 1);
				$unik 	= "_beenDeleted";
				$very_unique = "_extra";
				$vid 	= $validate->id_file;
				$vtipe  = $validate->tipe;
				$vlink  = $validate->link;
				$new_name = $validate->nama . $unik . $number;
				$extraordinary_name = $validate->nama . $unik . $number . $very_unique;
				$validates =  $this->dsettingfolder->get_file_by_name(null, null, 'all', $new_name);
				if ($validates == "") {
					$oldname  = $vlink;
					$uploadir = realpath('./') . '/assets/file/folder/';

					if (!file_exists($uploadir)) {
						$msg    = "This File Location is Unknown";
						$sts    = "NotOK";
					} else {
						rename(realpath('./') . "/assets/$oldname", realpath('./') . "/assets/file/folder/$new_name.$vtipe");
						$data_row   = array(
							'nama'      		=> $new_name,
							'link' 			=> "file/folder/$new_name.$vtipe",
							'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'    => $datetime
						);
						$this->dgeneral->update('tbl_file', $data_row, array(
							array(
								'kolom' => 'id_file',
								'value' => $vid
							)
						));
					}
				} else {
					$new_name = $extraordinary_name;
					$oldname  = $vlink;
					$uploadir = realpath('./') . '/assets/file/folder/';

					if (!file_exists($uploadir)) {
						$msg    = "This File Location is Unknown";
						$sts    = "NotOK";
					} else {
						rename(realpath('./') . "/assets/$oldname", realpath('./') . "/assets/file/folder/$new_name.$vtipe");
						$data_row   = array(
							'nama'      		=> $new_name,
							'link' 				=> "file/folder/$new_name.$vtipe",
							'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
							'tanggal_edit'    	=> $datetime
						);
						$this->dgeneral->update('tbl_file', $data_row, array(
							array(
								'kolom' => 'id_file',
								'value' => $vid
							)
						));
					}
				}
			}
		}

		$name = explode(',',  $_POST['name']);

		//cek filename hanya alphanum dan underscore
		$itemss = array();
		foreach ($name as $n) {
			if (preg_match('/[^A-Za-z0-9\s-_.,]/', $n)) {
				$itemss[] = $n;
			}
		}

		// CEK DUPLICATE FILENAME
		$cek =  $this->dsettingfolder->get_data_file_action();
		$items = array();
		foreach ($cek as $c) {
			if (in_array($c->nama, $name)) {
				$items[] = $c->nama;
			}
		}

		if ($items != NULL || $itemss != NULL) { // Jika ada nama file yang sama
			if ($items != NULL) {
				$alert = implode(',', $items);
				$msg    = "Please Rename File [ $alert ] Before Uploading.";
				$sts    = "NotOK";

				$this->general->closeDb();
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
			} else {
				$alert = implode(',', $itemss);
				$msg    = "Please Rename File [ $alert ] before uploading. File name should only contain Alphabet, Number, and Special Char (Dot . | Comma , | Dash - | Underscore _ ) ";
				$sts    = "NotOK";

				$this->general->closeDb();
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
			}
		} else { // Jika nama file tidak ada yang sama

			$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
			$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|swf|vsd';
			// $config['max_size']      = 100;
			if (isset($_POST['apps']) && $_POST['apps'] == 'knowledge') {
				$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module()) . '/knowledge';
				$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx|ppt|pptx|swf|vsd|mp4|mp3';
				$config['max_size']      = 0;
			}

			if (isset($_FILES['fileUpload'])) { //save the file
				$akses_parent = $this->dsettingfolder->get_folder_by_id(null, $id);
				$count = $this->dsettingfolder->get_total_file();
				$indexs = $count->total + 1;
				$files = $this->general->upload_files($_FILES['fileUpload'], null, $config);
				foreach ($files as $file) {
					// $name_without_dot       = str_replace('.', '_', pathinfo($file['url'], PATHINFO_FILENAME));
					// $name_without_dot_space = preg_replace('/\s+/', '_', pathinfo($file['url'], PATHINFO_FILENAME));
					$name_without_dot_space = pathinfo($file['url'], PATHINFO_FILENAME);
					// $path = preg_replace('/\s+/', '_', preg_replace('/\.(?=.*\.)/', '_',  str_replace("assets/", "", $file['url'] )));
					$path = str_replace("assets/", "", $file['url']);

					$data_file      = array(
						'id_file_encrypted'	=> $this->generate->kirana_encrypt($indexs),
						'id_folder'     	=> $id,
						'nama'     		    => $name_without_dot_space,
						'ukuran'        	=> $file['size'],
						'tipe'          	=> pathinfo($file['url'], PATHINFO_EXTENSION),
						'link'      		=> $path,
						'divisi_akses'    	=> $akses_parent->divisi_akses,
						'departemen_akses'  => $akses_parent->departemen_akses,
						'divisi_write'    	=> $akses_parent->divisi_write,
						'departemen_write'  => $akses_parent->departemen_write,
						'level_akses'       => $akses_parent->level_akses,
						'level_write'       => $akses_parent->level_write,
						'sosialisasi'       => $_POST['sosialisasi'],
						'catatan_kaki'      => $_POST['catatan_kaki'],
						'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'  	=> $datetime,
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime,
						'na'				=> 'n',
						'del'				=> 'n',
						'lihat'				=> 'y'
					);

					$this->dgeneral->insert('tbl_file', $data_file);
					$indexs++;
				}
				$this->dsettingfolder->update_folder_path(null);

				if ($this->dgeneral->status_transaction() === FALSE) { 
					$this->dgeneral->rollback_transaction();
					$msg    = "Please re-check the submitted data";
					$sts    = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg    = "Succesfully added data";
					$sts    = "OK";
				}
			} else {
				$msg    = "Please re-check the submitted data";
				$sts    = "NotOK";
			}

			$this->general->closeDb();
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		}
	}

	//lha
	public function upload_new_file_lha()
	{
		$datetime   = date("Y-m-d H:i:s");
		$divisi 	= base64_decode($this->session->userdata('-id_divisi-'));
		$department = base64_decode($this->session->userdata('-id_departemen-'));
		$id_level 	= base64_decode($this->session->userdata('-id_level-'));
		$id 		= $_POST['id_folder'];
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$name = explode(',',  $_POST['name']);
		$count = $this->dsettingfolder->get_total_file();
		$indexs = $count->total + 1;
		//lha
		$config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module()) . '/vendor';
		$config['allowed_types'] = 'pdf|doc|docx|xls|xlsx';
		$config['max_size']      = 0;

		if (isset($_FILES['fileUpload'])) { //save the file
			//lha
			$files = $this->general->upload_files($_FILES['fileUpload'], str_replace(' ','_',$name), $config);
			
			foreach ($files as $file) {
				$name_without_dot_space = pathinfo(str_replace('_',' ',$file['url']), PATHINFO_FILENAME);
				$path = str_replace("assets/", "", $file['url']);
				$ck_file 		= $this->dsettingfolder->get_data_file(NULL, $id, NULL, $name_without_dot_space);	//set untuk go-live berbeda xxx
				//lha
				if (isset($_POST['apps']) && $_POST['apps'] == 'vendor' && (count($ck_file) == 0)) {
					$data_file      = array(
						'id_file_encrypted'	=> $this->generate->kirana_encrypt($indexs),
						'id_folder'     	=> $id,
						'nama'     		    => $name_without_dot_space,
						'ukuran'        	=> $file['size'],
						'tipe'          	=> pathinfo($file['url'], PATHINFO_EXTENSION),
						'link'      		=> $path,
						'divisi_akses'    	=> NULL, 
						'departemen_akses'  => NULL,
						'divisi_write'    	=> NULL,
						'departemen_write'  => NULL,
						'level_akses'       => NULL,
						'level_write'       => NULL,
						'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'  	=> $datetime,
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime,
						'na'				=> 'n',
						'del'				=> 'n',
						'lihat'				=> 'y' 
					);
					$this->dgeneral->insert('tbl_file', $data_file);
					$indexs++;
				} 
			}
			$this->dsettingfolder->update_folder_path(null);

			if ($this->dgeneral->status_transaction() === FALSE) {
				$this->dgeneral->rollback_transaction();
				$msg    = "Please re-check the submitted data";
				$sts    = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg    = "Succesfully added data";
				$sts    = "OK";
			}
		} else {
			$msg    = "Please re-check the submitted data";
			$sts    = "NotOK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	public function setting_akses()
	{
		$datetime   = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$divisi_write  		= null;
		$departemen_write  	= null;
		$divisi_read  		= null;
		$department_read  	= null;
		$level_write	  	= null;
		$level_akses	  	= null;


		if (isset($_POST['division_write'])) {
			$divisi_write     = implode(',', $_POST['division_write']);
		}
		if (isset($_POST['department_write'])) {
			$departemen_write = implode(',', $_POST['department_write']);
		}
		if (isset($_POST['division_read'])) {
			$divisi_read      = implode(',', $_POST['division_read']);
		}
		if (isset($_POST['department_read'])) {
			$department_read  = implode(',', $_POST['department_read']);
		}
		if (isset($_POST['level_write'])) {
			$level_write  = implode(',', $_POST['level_write']);
		}
		if (isset($_POST['level_akses'])) {
			$level_akses  = implode(',', $_POST['level_akses']);
		}


		if (isset($_POST['id_folder']) && $_POST['tipe'] == "Folder") {

			$data_row   = array(
				'divisi_write'    	=> $divisi_write,
				'departemen_write'    => $departemen_write,
				'divisi_akses'    	=> $divisi_read,
				'departemen_akses'    => $department_read,
				'level_write'   		=> $level_write,
				'level_akses'    		=> $level_akses,
				'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_edit'    	=> $datetime
			);
			$this->dgeneral->update('tbl_folder', $data_row, array(
				array(
					'kolom' => 'id_folder',
					'value' => $_POST['id_folder']
				)
			));


			if ($this->dgeneral->status_transaction() === FALSE) {
				$this->dgeneral->rollback_transaction();
				$msg    = "Please re-check the submitted data";
				$sts    = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg    = "Succesfully added data";
				$sts    = "OK";
			}
		} else if (isset($_POST['id_folder']) && $_POST['tipe'] != "Folder") {
			$data_row   = array(
				'divisi_akses'    	=> $divisi_read,
				'departemen_akses'    => $department_read,
				'divisi_write'    	=> $divisi_write,
				'departemen_write'    => $departemen_write,
				'level_write'   		=> $level_write,
				'level_akses'    		=> $level_akses,
				'login_edit'      	=> base64_decode($this->session->userdata("-id_user-")),
				'tanggal_edit'    	=> $datetime
			);
			$this->dgeneral->update('tbl_file', $data_row, array(
				array(
					'kolom' => 'id_file',
					'value' => $_POST['id_folder']
				)
			));


			if ($this->dgeneral->status_transaction() === FALSE) {
				$this->dgeneral->rollback_transaction();
				$msg    = "Please re-check the submitted data";
				$sts    = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$msg    = "Succesfully added data";
				$sts    = "OK";
			}
		} else {
			$msg    = "Please re-check the submitted data";
			$sts    = "NotOK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}


	public function get_departement()
	{

		$file = "";
		$sts  = "notOK";

		if ($_POST['id_divisi'] != "") {
			$sts 		= "OK";
			$file  		= $this->dsettingfolder->get_department_by_divisi('open', $_POST['id_divisi']);
		}

		$return = array('sts' => $sts, 'data' => $file);
		echo json_encode($return);
	}


	public function get_user_admin_folder()
	{

		$data = $_POST['data'];
		$search = isset($_POST['search']) ? $_POST['search'] : '';
		$filter = isset($_POST['filter']) ? $_POST['filter'] : null;

		if (isset($data)) {
			$karyawan = $this->dmenus->get_list_karyawan(
				isset($filter) ? $data['nik_akses'] : null,
				$search
			);
		} else {
			$karyawan = array();
		}

		echo json_encode(array('items' => $karyawan));
	}


	public function get_data_akses_folder()
	{
		$tipe		= $_POST['tipe'];
		$id 		= $_POST['id_folder'];

		if ($tipe != 'Folder') {
			$folder     = $this->dsettingfolder->get_file_by_id('open', $id, 'all');
		} else {
			$folder     = $this->dsettingfolder->get_folder_by_id('open', $id, 'all');
		}
		$array 	= new stdClass();
		$array->divisi_write 		= explode(',', $folder->divisi_write);
		$array->divisi_read 		= explode(',', $folder->divisi_akses);
		$array->department_write 	= explode(',', $folder->departemen_write);
		$array->department_read 	= explode(',', $folder->departemen_akses);
		$array->level_write		 	= explode(',', $folder->level_write);
		$array->level_akses		 	= explode(',', $folder->level_akses);

		echo json_encode($array);
	}

	public function user_data()
	{
		if (isset($_GET['q'])) {
			$user       = $this->dgeneral->get_data_user($_GET['q']);
			$data_user  = array(
				"total_count" => count($user),
				"incomplete_results" => false,
				"items" => $user
			);
			echo json_encode($data_user);
		}
	}


	public function get_nik()
	{

		$cek = $this->dsettingfolder->get_data_user_admin('open', $_POST['folder']);
		echo json_encode($cek);
	}

	public function delete_root()
	{
		$id		    = $_POST['id_folder'];
		$datetime   = date("Y-m-d H:i:s");

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$data_row   = array(
			'na'				=> "y",
			'del'				=> "y",
			'login_edit'      => base64_decode($this->session->userdata("-id_user-")),
			'tanggal_edit'    => $datetime
		);
		$this->dgeneral->update('tbl_folder', $data_row, array(
			array(
				'kolom' => 'id_folder',
				'value' => $id
			)
		));


		if ($this->dgeneral->status_transaction() === FALSE) {
			$this->dgeneral->rollback_transaction();
			$msg    = "Please re-check the submitted data";
			$sts    = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg    = "Data Deleted";
			$sts    = "OK";
		}

		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	public function download()
	{
		$file           = $_POST['link'];
		$this->general->download($file);
	}

	// public function test(){
	//  $count = $this->dsettingfolder->get_total_file('open'); 
	//    	echo ($count->total);
	//    	// echo ($this->generate->kirana_decrypt("VHozQkM3cWJScTJ5T1U0bGRnbXd3dz09"));
	//    	exit();
	// }

}
