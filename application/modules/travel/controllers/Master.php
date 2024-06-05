<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Travel
@author       : Airiza Yuddha (7849)
@contributor  :
1. <insert your fullname> (<insert your nik>) <insert the date>
<insert what you have modified>
2. <insert your fullname> (<insert your nik>) <insert the date>
<insert what you have modified>
etc.
*/

include_once APPPATH . "modules/travel/controllers/BaseControllers.php";
class Master extends BaseControllers
{
	// private $data;
	function __construct()
	{
		parent::__construct();
		$this->load->model('dmastertravel');

		/*load model*/
		$this->load->model('dgeneral');
	}

	public function index()
	{
		show_404();
	}

	/*================================ Role ====================================*/
	public function role()
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		//===============================================/
		$data['module'] 		= "Travel";
		$data['title']      	= "Master Role";
		$data['title_form'] 	= "Tambah Role";

		/*load global attribute*/
		$data['generate']     	= $this->generate;
		$data['module']       	= $this->router->fetch_module();
		$data['user']       	= $this->general->get_data_user();
		$data['role'] 			= $this->dmastertravel->get_data_travel_role_normal();
		$this->load->view("master/role", $data);
	}

	/*================================ Approval ====================================*/
	public function approval()
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		//===============================================/
		$data['module'] 		= "Travel";
		$data['title']      	= "Master Approval";
		$data['title_form'] 	= "Tambah Approval";

		/*load global attribute*/
		$data['generate']     	= $this->generate;
		$data['module']       	= $this->router->fetch_module();
		$data['user']       	= $this->general->get_data_user();
		$data['role'] 			= $this->dmastertravel->get_data_travel_role_normal();
		$data['jabatan']		= $this->dmastertravel->get_data_travel_jabatan_normal();

		$this->load->view("master/approval", $data);
	}

	/*================================ PIC ====================================*/
	public function pic($param = NULL)
	{
		switch ($param) {
			case 'booking':
				$this->booking($param);
				break;

			case 'sync':
				$this->sync($param);
				break;

			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	/*================================ Booking ====================================*/
	public function booking()
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		//===============================================/
		$data['module'] 		= "Booking";
		$data['title']      	= "Master Pic Booking";
		$data['title_form'] 	= "Tambah Pic Booking";

		/*load global attribute*/
		$data['generate']     	= $this->generate;
		$data['module']       	= $this->router->fetch_module();
		$data['user']       	= $this->general->get_data_user();
		// $data['role'] 			= $this->dmastertravel->get_data_travel_role_normal();
		$data['jabatan']		= $this->dmastertravel->get_data_travel_jabatan_normal();
		$data['personal_area']	= $this->dmastertravel->get_data_personal_area();
		// $data['persub']			= $this->dmastertravel->get_data_subarea();
		$this->load->view("master/pic_book", $data);
	}

	/*================================ Sync ====================================*/
	public function sync()
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		//===============================================/
		$data['module'] 		= "Sync";
		$data['title']      	= "Master Pic Sync";
		$data['title_form'] 	= "Tambah Pic Sync";

		/*load global attribute*/
		$data['generate']     	= $this->generate;
		$data['module']       	= $this->router->fetch_module();
		$data['user']       	= $this->general->get_data_user();
		// $data['role'] 			= $this->dmastertravel->get_data_travel_role_normal();
		$data['jabatan']		= $this->dmastertravel->get_data_travel_jabatan_normal();
		$data['personal_area']	= $this->dmastertravel->get_data_personal_area();
		// $data['persub']			= $this->dmastertravel->get_data_subarea();
		$this->load->view("master/pic_sync", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//

	public function get($param = NULL)
	{
		switch ($param) {
			case 'role': // role master
				$id_role 		= (isset($_POST['id_role']) ? $this->generate->kirana_decrypt($_POST['id_role']) : NULL);
				$this->travel_role_normal('portal', $id_role);
				break;

			case 'approval': // approval master
				$id_role 		= (isset($_POST['id_approval']) ? $this->generate->kirana_decrypt($_POST['id_approval']) : NULL);
				$this->travel_approval_normal('portal', $id_role);
				break;

			case 'approval_detail': // approval master
				$id_role 		= (isset($_POST['id_approval']) ? $this->generate->kirana_decrypt($_POST['id_approval']) : NULL);
				$this->travel_approval_detail_normal('portal', $id_role);
				break;

			case 'pic_book': // pic_book master
				$id_pic_book 		= (isset($_POST['id_pic_book']) ? $this->generate->kirana_decrypt($_POST['id_pic_book']) : NULL);
				$this->travel_pic_book_normal('portal', $id_pic_book);
				break;

			case 'pic_book_detail': // pic_book master
				$id_pic_book 		= (isset($_POST['id_pic_book']) ? $this->generate->kirana_decrypt($_POST['id_pic_book']) : NULL);
				$dataedit 			= (isset($_POST['dataedit']) ? $_POST['dataedit'] : NULL);
				$this->travel_pic_book_detail_normal('portal', $id_pic_book, NULL, $dataedit);
				break;

			case 'pic_sync': // pic_sync master
				$id_pic_sync 		= (isset($_POST['id_pic_sync']) ? $this->generate->kirana_decrypt($_POST['id_pic_sync']) : NULL);
				$this->travel_pic_sync_normal('portal', $id_pic_sync);
				break;

			case 'pic_sync_detail': // pic_sync master
				$id_pic_sync 		= (isset($_POST['id_pic_sync']) ? $this->generate->kirana_decrypt($_POST['id_pic_sync']) : NULL);
				$dataedit 			= (isset($_POST['dataedit']) ? $_POST['dataedit'] : NULL);
				$this->travel_pic_sync_detail_normal('portal', $id_pic_sync, NULL, $dataedit);
				break;

			case 'nik': // nik master
				$this->get_nik();
				break;

			case 'jabatan': // nik master
				$this->get_jabatan();
				break;

			case 'subarea': // nik master
				$this->get_subarea();
				break;

			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set($param = NULL)
	{
		$action = NULL;
		if (isset($_POST['type']) && $_POST['type'] == "nonactive") {
			$action = "delete_na";
		} else if (isset($_POST['type']) && $_POST['type'] == "setactive") {
			$action = "activate_na";
		} else if (isset($_POST['type']) && $_POST['type'] == "delete") {
			$action = "delete_del";
		}

		if ($action) {
			switch ($param) {
				case 'role':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_travel_role", array(
						array(
							'kolom' => 'id_travel_role',
							'value' => $this->generate->kirana_decrypt($_POST['id'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;

				case 'approval':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_travel_approval", array(
						array(
							'kolom' => 'id_travel_approval',
							'value' => $this->generate->kirana_decrypt($_POST['id'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;

				case 'pic_book':
					$this->general->connectDbPortal();
					$datadetail		= explode("|", $_POST['id']);
					$nik 			= $datadetail[0];
					$company_code 	= $datadetail[1];
					$personal_area 	= $datadetail[2];
					$personal_sarea = $datadetail[3];
					$jns_user 		= $datadetail[4];
					$return = $this->general->set("delete_na_del", "tbl_travel_pic_book", array(
						array(
							'kolom' => 'nik',
							'value' => $nik
						),
						array(
							'kolom' => 'company_code',
							'value' => $company_code
						),
						array(
							'kolom' => 'personal_area',
							'value' => $personal_area
						),
						array(
							'kolom' => 'personal_subarea',
							'value' => $personal_sarea
						),
						array(
							'kolom' => 'jns_user',
							'value' => $jns_user
						),

					));
					echo json_encode($return);
					$this->general->closeDb();
					break;

				case 'pic_sync':
					$this->general->connectDbPortal();
					$datadetail		= explode("|", $_POST['id']);
					$nik 			= $datadetail[0];
					$company_code 	= $datadetail[1];
					$personal_area 	= $datadetail[2];
					$personal_sarea = $datadetail[3];
					$jns_user 		= $datadetail[4];
					$return = $this->general->set("delete_na_del", "tbl_travel_pic_sync", array(
						array(
							'kolom' => 'nik',
							'value' => $nik
						),
						array(
							'kolom' => 'company_code',
							'value' => $company_code
						),
						array(
							'kolom' => 'personal_area',
							'value' => $personal_area
						),
						array(
							'kolom' => 'personal_subarea',
							'value' => $personal_sarea
						),
						array(
							'kolom' => 'jns_user',
							'value' => $jns_user
						),
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

	public function save($param = NULL)
	{
		switch ($param) {
			case 'role':
				$this->save_role($param);
				break;

			case 'approval':
				$this->save_approval($param);
				break;

			case 'pic_book':
				$this->save_pic_book($param);
				break;

			case 'pic_sync':
				$this->save_pic_sync($param);
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


	/*================================ Role ====================================*/
	public function travel_role($conn = NULL, $id = NULL, $all = NULL)
	{
		$data = $this->dmastertravel->get_data_travel_role('portal', $id, $all);
		echo $data;
	}

	public function travel_role_normal($conn = NULL, $id = NULL, $all = NULL)
	{	// for get role
		$data = $this->dmastertravel->get_data_travel_role_normal("portal", $id, "only active");
		$data = $this->general->generate_encrypt_json($data, array("id_travel_role"));
		echo json_encode($data);
	}

	private function save_role($param)
	{
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$nama_role 			= (!empty($_POST['nama_role'])) ? $_POST['nama_role'] : 0;
		$level 				= (!empty($_POST['level'])) ? $_POST['level'] : '0';
		$if_approve_spd 	= (!empty($_POST['if_approve_spd'])) ? $_POST['if_approve_spd'] : 0;
		$if_approve_spd_um 	= (!empty($_POST['if_approve_spd_um'])) ? $_POST['if_approve_spd_um'] : 0;
		$if_approve_declare = (!empty($_POST['if_approve_dec'])) ? $_POST['if_approve_dec'] : 0;
		$if_approve_cancel 	= (!empty($_POST['if_approve_cancel'])) ? $_POST['if_approve_cancel'] : 0;
		$if_decline_spd 	= (!empty($_POST['if_decline_spd'])) ? $_POST['if_decline_spd'] : 0;
		$if_decline_spd_um 	= (!empty($_POST['if_decline_spd_um'])) ? $_POST['if_decline_spd_um'] : 0;
		$if_decline_declare = (!empty($_POST['if_decline_dec'])) ? $_POST['if_decline_dec'] : 0;
		$if_decline_cancel 	= (!empty($_POST['if_decline_cancel'])) ? $_POST['if_decline_cancel'] : 0;
		$if_approve_modify 	= (!empty($_POST['if_decline_cancel'])) ? $_POST['if_approve_modify'] : 0;
		$if_decline_modify 	= (!empty($_POST['if_decline_cancel'])) ? $_POST['if_decline_modify'] : 0;
		$v_transport_spd 	= (!empty($_POST['v_transport_spd'])) ? $_POST['v_transport_spd'] : 0;
		$v_transport_spd_um 	= (!empty($_POST['v_transport_spd_um'])) ? $_POST['v_transport_spd_um'] : 0;

		$data_row = array(
			//all 
			"role" 					=> $nama_role,
			"level" 				=> $level,
			"if_approve_spd" 		=> $if_approve_spd,
			"if_approve_spd_um" 		=> $if_approve_spd_um,
			"if_approve_declare" 	=> $if_approve_declare,
			"if_approve_cancel" 	=> $if_approve_cancel,
			"if_decline_spd" 		=> $if_decline_spd,
			"if_decline_spd_um" 		=> $if_decline_spd_um,
			"if_decline_declare" 	=> $if_decline_declare,
			"if_decline_cancel" 	=> $if_decline_cancel,
			"if_approve_modify" 	=> $if_approve_modify,
			"if_decline_modify" 	=> $if_decline_modify,
			"v_transport_spd" 	=> $v_transport_spd,
			"v_transport_spd_um" 	=> $v_transport_spd_um,

			'na'     				=> 'n',
			'del'     				=> 'n',
			'login_buat'        	=> base64_decode($this->session->userdata("-id_user-")),
			'tanggal_buat'      	=> $datetime,
			'login_edit'        	=> base64_decode($this->session->userdata("-id_user-")),
			'tanggal_edit'      	=> $datetime
		);

		if (isset($_POST['id_role']) && trim($_POST['id_role']) !== "") { // edit	

			$id_edit 	= $this->generate->kirana_decrypt($_POST['id_role']);
			// check if exist 
			$datacheck 	= $nama_role;

			$checkdata 	= $this->dmastertravel->get_data_travel_role_normal(NULL, $id_edit, NULL, 'up', $datacheck);
			if (count($checkdata) > 0) {
				$msg    = "Duplicate data role " . $nama_role . ", periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			// check data level
			$checkdata 	= $this->dmastertravel->check_data_travel_role_level(NULL, $level, 'up', $id_edit);
			if (count($checkdata) > 0) {
				$msg    = "Level sudah terpakai, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}

			unset($data_row['login_buat'], $data_row['tanggal_buat']);
			$this->dgeneral->update("tbl_travel_role", $data_row, array(
				array(
					'kolom' => 'id_travel_role',
					'value' => $this->generate->kirana_decrypt($_POST['id_role'])
				)
			));
		} else {	//input

			// check if exist 
			$datacheck 	= $nama_role;
			$checkdata 	= $this->dmastertravel->get_data_travel_role_normal(NULL, NULL, NULL, 'in', $datacheck);
			if (count($checkdata) > 0) {
				$msg    = "Duplicate data role " . $nama_role . " , periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			// check data level
			$checkdata 	= $this->dmastertravel->check_data_travel_role_level(NULL, $level, 'in', NULL);
			if (count($checkdata) > 0) {
				$msg    = "Level sudah terpakai, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_travel_role", $data_row);
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

	/*================================ Approval ====================================*/
	public function travel_approval($conn = NULL, $id = NULL, $all = NULL)
	{
		$data = $this->dmastertravel->get_data_travel_approval('portal', $id, $all);
		echo $data;
	}

	public function travel_approval_normal($conn = NULL, $id = NULL, $all = NULL)
	{	// for get role
		$data = $this->dmastertravel->get_data_travel_approval_normal("portal", $id, "only active");
		$data = $this->general->generate_encrypt_json($data, array("id_travel_approval"));
		echo json_encode($data);
	}

	public function travel_approval_detail_normal($conn = NULL, $id = NULL, $all = NULL)
	{	// for get role
		$opt_jabatan		= $this->dmastertravel->get_data_travel_jabatan_normal();

		// set array
		$data 				= $this->dmastertravel->get_data_travel_approval_detail_normal("portal", $id, "only active");
		$data 				= $this->general->generate_encrypt_json($data, array("id_travel_approval"));
		$data->opt_jabatan 	= $opt_jabatan;

		echo json_encode($data);
	}

	private function save_approval($param)
	{
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$jns_user 			= (!empty($_POST['jenis_input1'])) ? $_POST['jenis_input1'] : 0;
		$value_user 		= (!empty($_POST['user'])) ? $_POST['user'] : 0;
		$level_role			= (!empty($_POST['role'])) ? $_POST['role'] : 0;
		$jns_approval		= (!empty($_POST['jenis_input2'])) ? $_POST['jenis_input2'] : 0;
		$value_approval 	= (!empty($_POST['user_app'])) ? implode('.', $_POST['user_app']) : 0;
		$value_approval 	= "." . $value_approval . ".";
		$email_approval 	= (!empty($_POST['user_app_email'])) ? implode('.', $_POST['user_app_email']) : 0;
		$email_approval 	= "." . $email_approval . ".";

		$data_row = array(
			//all 
			"jns_user" 			=> $jns_user,
			"value_user" 		=> $value_user,
			"level_role" 		=> $level_role,
			"jns_approval" 		=> $jns_approval,
			"value_approval" 	=> $value_approval,
			"email_approval" 	=> $email_approval,

			'na'     			=> 'n',
			'del'     			=> 'n',
			'login_buat'        => base64_decode($this->session->userdata("-id_user-")),
			'tanggal_buat'      => $datetime,
			'login_edit'        => base64_decode($this->session->userdata("-id_user-")),
			'tanggal_edit'      => $datetime
		);

		if (isset($_POST['id_approval']) && trim($_POST['id_approval']) !== "") { // edit	

			$id_edit 	= $this->generate->kirana_decrypt($_POST['id_approval']);
			// check if exist 
			$datacheck1	= $jns_user;
			$datacheck2	= $value_user;
			$datacheck3	= $level_role;

			$checkdata 	= $this->dmastertravel->get_data_travel_approval_normal(NULL, $id_edit, NULL, 'up', $datacheck1, $datacheck2, $datacheck3);
			if (count($checkdata) > 0) {
				$msg    = "Duplicate data , periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}

			unset($data_row['login_buat'], $data_row['tanggal_buat']);
			$this->dgeneral->update("tbl_travel_approval", $data_row, array(
				array(
					'kolom' => 'id_travel_approval',
					'value' => $this->generate->kirana_decrypt($_POST['id_approval'])
				)
			));
		} else {	//input

			// check if exist 
			$datacheck1	= $jns_user;
			$datacheck2	= $value_user;
			$datacheck3	= $level_role;
			$checkdata 	= $this->dmastertravel->get_data_travel_approval_normal(NULL, NULL, NULL, 'in', $datacheck1, $datacheck2, $datacheck3);
			if (count($checkdata) > 0) {
				$msg    = "Duplicate data , periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_travel_approval", $data_row);
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

	/*================================ pic_book ====================================*/
	public function travel_pic_book($conn = NULL, $id = NULL, $all = NULL)
	{
		$data = $this->dmastertravel->get_data_travel_pic_book('portal', $id, $all);
		echo $data;
	}

	public function travel_pic_book_normal($conn = NULL, $id = NULL, $all = NULL)
	{	// for get role
		$data = $this->dmastertravel->get_data_travel_pic_book_normal("portal", $id, "only active");
		$data = $this->general->generate_encrypt_json($data, array("id_travel_approval"));
		echo json_encode($data);
	}

	public function travel_pic_book_detail_normal($conn = NULL, $id = NULL, $all = NULL, $dataedit = NULL)
	{	// for get role
		$opt_jabatan		= $this->dmastertravel->get_data_travel_jabatan_normal();

		// set array
		$data 				= $this->dmastertravel->get_data_travel_pic_book_detail_normal("portal", $id, "only active", $dataedit);

		$personal_area 		= !isset($data->personal_area) ? NULL : $data->personal_area;
		$opt_subarea 		= $this->dmastertravel->get_data_subarea(NULL, NULL, NULL, $personal_area);

		$data->opt_jabatan 	= $opt_jabatan;
		$data->opt_subarea 	= $opt_subarea;

		echo json_encode($data);
	}

	private function save_pic_book($param)
	{
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$nik 					= (!empty($_POST['user'])) ? $_POST['user'] : 0;
		$data_personal_area 	= (!empty($_POST['personal_area'])) ? explode("|", $_POST['personal_area']) : 0;
		if ($data_personal_area != 0 && count($data_personal_area) > 1) {
			$personal_area 		= $data_personal_area[0];
			$company_code 		= $data_personal_area[1];
		} else {
			$personal_area 		= 0;
			$company_code 		= 0;
		}
		$personal_subarea		= (!empty($_POST['personal_subarea'])) ? $_POST['personal_subarea'] : 0;
		$jns_user				= (!empty($_POST['jenis_input2'])) ? $_POST['jenis_input2'] : 0;
		$value_user 			= (!empty($_POST['user_app'])) ? $_POST['user_app'] : 0;

		$data_row = array(
			//all 
			"nik" 				=> $nik,
			"company_code" 		=> $company_code,
			"personal_area" 	=> $personal_area,
			"personal_subarea" 	=> $personal_subarea,
			"jns_user" 			=> $jns_user,
			"value_user" 		=> $value_user,

			'na'     			=> 'n',
			'del'     			=> 'n',
			'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
			'tanggal_buat'  	=> $datetime,
			'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
			'tanggal_edit'  	=> $datetime
		);

		if (isset($_POST['id_pic_book']) && trim($_POST['id_pic_book']) !== "") { // edit
			// check if exist 
			$datacheck1	= $nik;
			$datacheck2	= $personal_area;
			$datacheck3	= $personal_subarea;
			$datacheck4	= $jns_user;
			$datacheck5	= $value_user;

			$checkdata2 	=  $this->dmastertravel->get_data_travel_pic_book_normal(NULL, NULL, NULL, 'in', $datacheck1, $datacheck2, $datacheck3, $datacheck4, NULL, "all");
			$data_valuser = [];
			foreach ($checkdata2 as $dt) {
				array_push($data_valuser, $dt->value_user);
			}
			foreach ($value_user as $value) {
				if (!in_array($value, $data_valuser)) {
					$data_rowx = array(
						//all 
						"nik" 				=> $nik,
						"company_code" 		=> $company_code,
						"personal_area" 	=> $personal_area,
						"personal_subarea" 	=> $personal_subarea,
						"jns_user" 			=> $jns_user,
						"value_user" 		=> $value,


						'na'     			=> 'n',
						'del'     			=> 'n',
						'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'  	=> $datetime,
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime
					);
					$this->dgeneral->insert("tbl_travel_pic_book", $data_rowx);
				} else {
					$data_rowx = array(
						"company_code" 		=> $company_code,
						"personal_area" 	=> $personal_area,
						"personal_subarea" 	=> $personal_subarea,
						'na'     			=> 'n',
						'del'     			=> 'n',
						// 'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						// 'tanggal_buat'  	=> $datetime,
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime
					);
					$this->dgeneral->update("tbl_travel_pic_book", $data_rowx, array(
						array(
							'kolom' => 'nik',
							'value' => $nik
						),
						array(
							'kolom' => 'jns_user',
							'value' => $jns_user
						),
						array(
							'kolom' => 'value_user',
							'value' => $value
						),
					));
				}
			}
			$checkdata3 	=  $this->dmastertravel->get_data_travel_pic_book_normal(NULL, NULL, NULL, 'in', $datacheck1, $datacheck2, $datacheck3, $datacheck4, NULL, "all data");
			foreach ($checkdata3 as $dt) {
				if (!in_array($dt->value_user, $value_user)) {
					if ($dt->del == 'n') {
						$return = $this->general->set("delete_na_del", "tbl_travel_pic_book", array(
							array(
								'kolom' => 'id_travel_pic_book',
								'value' => $dt->id_travel_pic_book
							)
						));
					}
				}
			}
		} else {	//input
			// check if exist 
			$datacheck1	= $nik;
			$datacheck2	= $personal_area;
			$datacheck3	= $personal_subarea;
			$datacheck4	= $jns_user;
			$datacheck5	= $value_user;
			$checkdata 	= $this->dmastertravel->get_data_travel_pic_book_normal(NULL, NULL, NULL, 'in', $datacheck1, $datacheck2, $datacheck3, $datacheck4, $datacheck5);
			if (count($checkdata) > 0) {
				$msg    = "Duplicate data , periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			foreach ($value_user as $dt) {
				$data_rowx = array(
					//all 
					"nik" 			=> $nik,
					"company_code" 		=> $company_code,
					"personal_area" 		=> $personal_area,
					"personal_subarea" 		=> $personal_subarea,
					"jns_user" 		=> $jns_user,
					"value_user" 	=> $dt,
					'na'     		=> 'n',
					'del'     		=> 'n',
					'login_buat'    => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'  => $datetime,
					'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'  => $datetime
				);
				$this->dgeneral->insert("tbl_travel_pic_book", $data_rowx);
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

	/*================================ pic_sync ====================================*/
	public function travel_pic_sync($conn = NULL, $id = NULL, $all = NULL)
	{
		$data = $this->dmastertravel->get_data_travel_pic_sync('portal', $id, $all);
		echo $data;
	}

	public function travel_pic_sync_normal($conn = NULL, $id = NULL, $all = NULL)
	{	// for get role
		$data = $this->dmastertravel->get_data_travel_pic_sync_normal("portal", $id, "only active");
		$data = $this->general->generate_encrypt_json($data, array("id_travel_approval"));
		echo json_encode($data);
	}

	public function travel_pic_sync_detail_normal($conn = NULL, $id = NULL, $all = NULL, $dataedit = NULL)
	{
		$opt_jabatan		= $this->dmastertravel->get_data_travel_jabatan_normal();

		// set array
		$data 				= $this->dmastertravel->get_data_travel_pic_sync_detail_normal("portal", $id, "only active", $dataedit);

		$personal_area 		= !isset($data->personal_area) ? NULL : $data->personal_area;
		$opt_subarea 		= $this->dmastertravel->get_data_subarea(NULL, NULL, NULL, $personal_area);

		$data->opt_jabatan 	= $opt_jabatan;
		$data->opt_subarea 	= $opt_subarea;

		echo json_encode($data);
	}

	private function save_pic_sync($param)
	{
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$nik 				= (!empty($_POST['user'])) ? $_POST['user'] : 0;
		$data_personal_area = (!empty($_POST['personal_area'])) ? explode("|", $_POST['personal_area']) : 0;
		if ($data_personal_area != 0 && count($data_personal_area) > 1) {
			$personal_area 	= $data_personal_area[0];
			$company_code 	= $data_personal_area[1];
		} else {
			$personal_area 	= 0;
			$company_code 	= 0;
		}
		$personal_subarea	= (!empty($_POST['personal_subarea'])) ? $_POST['personal_subarea'] : 0;
		$jns_user			= (!empty($_POST['jenis_input2'])) ? $_POST['jenis_input2'] : 0;
		$value_user 		= (!empty($_POST['user_app'])) ? $_POST['user_app'] : 0;

		$data_row = array(
			//all 
			"nik" 				=> $nik,
			"company_code" 		=> $company_code,
			"personal_area" 	=> $personal_area,
			"personal_subarea" 	=> $personal_subarea,
			"jns_user" 			=> $jns_user,
			"value_user" 		=> $value_user,
			'na'     			=> 'n',
			'del'     			=> 'n',
			'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
			'tanggal_buat'  	=> $datetime,
			'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
			'tanggal_edit'  	=> $datetime
		);

		if (isset($_POST['id_pic_sync']) && trim($_POST['id_pic_sync']) !== "") { // edit
			// check if exist 
			$datacheck1	= $nik;
			$datacheck2	= $personal_area;
			$datacheck3	= $personal_subarea;
			$datacheck4	= $jns_user;
			$datacheck5	= $value_user;

			$checkdata2 	=  $this->dmastertravel->get_data_travel_pic_sync_normal(NULL, NULL, NULL, 'in', $datacheck1, NULL, NULL, $datacheck4, NULL, "all");
			$data_valuser = [];
			foreach ($checkdata2 as $dt) {
				array_push($data_valuser, $dt->value_user);
			}
			foreach ($value_user as $value) {
				if (!in_array($value, $data_valuser)) {
					$data_rowx = array(
						//all 
						"nik" 				=> $nik,
						"company_code" 		=> $company_code,
						"personal_area" 	=> $personal_area,
						"personal_subarea" 	=> $personal_subarea,
						"jns_user" 			=> $jns_user,
						"value_user" 		=> $value,
						'na'     			=> 'n',
						'del'     			=> 'n',
						'login_buat'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_buat'  	=> $datetime,
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime
					);
					$this->dgeneral->insert("tbl_travel_pic_sync", $data_rowx);
				} else {
					$data_rowx = array(
						"company_code" 		=> $company_code,
						"personal_area" 	=> $personal_area,
						"personal_subarea" 	=> $personal_subarea,
						'na'     			=> 'n',
						'del'     			=> 'n',
						'login_edit'    	=> base64_decode($this->session->userdata("-id_user-")),
						'tanggal_edit'  	=> $datetime
					);
					$this->dgeneral->update("tbl_travel_pic_sync", $data_rowx, array(
						array(
							'kolom' => 'nik',
							'value' => $nik
						),
						array(
							'kolom' => 'jns_user',
							'value' => $jns_user
						),
						array(
							'kolom' => 'value_user',
							'value' => $value
						),
					));
				}
			}
			$checkdata3 	=  $this->dmastertravel->get_data_travel_pic_sync_normal(NULL, NULL, NULL, 'in', $datacheck1, NULL, NULL, $datacheck4, NULL, "all data");
			foreach ($checkdata3 as $dt) {
				if (!in_array($dt->value_user, $value_user)) {
					if ($dt->del == 'n' && $dt->na == 'n') {
						$return = $this->general->set("delete_na_del", "tbl_travel_pic_sync", array(
							array(
								'kolom' => 'id_travel_pic_sync',
								'value' => $dt->id_travel_pic_sync
							)
						));
					}
				}
			}
		} else {	//input
			// check if exist 
			$datacheck1	= $nik;
			$datacheck2	= $personal_area;
			$datacheck3	= $personal_subarea;
			$datacheck4	= $jns_user;
			$datacheck5	= $value_user;
			$checkdata 	= $this->dmastertravel->get_data_travel_pic_sync_normal(NULL, NULL, NULL, 'in', $datacheck1, $datacheck2, $datacheck3, $datacheck4, $datacheck5);
			if (count($checkdata) > 0) {
				$msg    = "Duplicate data , periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			foreach ($value_user as $dt) {
				$data_rowx = array(
					//all 
					"nik" 			=> $nik,
					"company_code" 		=> $company_code,
					"personal_area" 		=> $personal_area,
					"personal_subarea" 		=> $personal_subarea,
					"jns_user" 		=> $jns_user,
					"value_user" 	=> $dt,
					'na'     		=> 'n',
					'del'     		=> 'n',
					'login_buat'    => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_buat'  => $datetime,
					'login_edit'    => base64_decode($this->session->userdata("-id_user-")),
					'tanggal_edit'  => $datetime
				);
				$this->dgeneral->insert("tbl_travel_pic_sync", $data_rowx);
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

	/*================================ Other ====================================*/

	public function get_jabatan($conn = NULL, $id = NULL, $all = NULL)
	{	// for get role
		$data = $this->dmastertravel->get_data_travel_jabatan_normal();
		echo json_encode($data);
	}

	public function get_nik()
	{
		if (isset($_GET['q'])) {
			$persasplit	  	= $_GET['persa'] != 0 && isset($_GET['persa'])
				? explode("|", $_GET['persa']) : "kosong";
			$persa 			= $persasplit != "kosong" ? $persasplit[0] : NULL;
			$data       	  = $this->dmastertravel->get_data_user_program($_GET['q'], $persa);
			$data_json  	  = array(
				"total_count" => count($data),
				"incomplete_results" => false,
				"items" => $data
			);
			echo json_encode($data_json);
		}
	}

	public function get_subarea($conn = NULL, $personal_subarea = NULL, $all = NULL)
	{	// for get role
		$personal_area = !isset($_POST['personal_area']) ? NULL : $_POST['personal_area'];
		$data =  $this->dmastertravel->get_data_subarea(NULL, NULL, NULL, $personal_area);
		echo json_encode($data);
	}
}
