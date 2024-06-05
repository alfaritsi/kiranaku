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

class Transaksi extends MX_Controller
// class Transaksi extends BaseControllers
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('PHPExcel');
		$this->load->helper(array('form', 'url'));

		$this->load->model('dmastermentor');
		$this->load->model('dtransaksimentor');
	}

	public function index()
	{
		show_404();
	}
	
	public function mentor($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data Mentor";
		$data['status']		= $this->dtransaksimentor->get_data_status("open",NULL,'n','n');
		$this->load->view("transaksi/mentor", $data);
	}
	
	public function mentee($param = NULL)
	{
		//====must be initiate in every view function====/
		// $this->general->check_access();
		$data['generate']   = $this->generate;
		$data['module']     = $this->router->fetch_module();
		$data['user']       = $this->general->get_data_user();
		//===============================================/

		$data['title']    	= "Data Mentee";
		$data['status']		= $this->dtransaksimentor->get_data_status("open",NULL,'n','n');
		$this->load->view("transaksi/mentee", $data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	
	public function get($param = NULL, $param2 = NULL)
	{
		switch ($param) {
            case 'range':
                $post = $this->input->post(NULL, TRUE);
				// echo json_encode($post);
				// exit;
                $param_ = array(
                    "connect" 	 	=> TRUE,
                    "tanggal"  	 	=> (isset($post['tanggal']) ? $post['tanggal'] : NULL),
                    "tanggal_buat"  => (isset($post['tanggal_buat']) ? $post['tanggal_buat'] : NULL),
                    "id_status"  	=> (isset($post['id_status']) ? $post['id_status'] : NULL),
                    "return"	 	=> @$post['return'],
					"encrypt" 	 	=> array("id")
                );
                $this->get_range($param_);
                break;
			case 'user_auto':
				$post = $this->input->post_get(NULL, TRUE);
				$this->get_user_autocomplete($post['jenis']);
				// $this->get_user_autocomplete();
				break;
			case 'user':
				$nik  		= (isset($_POST['nik']) ? $_POST['nik'] : NULL);
				$this->get_user(NULL, $nik, 'n', 'n');
				break;
			case 'mentor':
				$nomor  = (isset($_POST['nomor']) ? $this->generate->kirana_decrypt($_POST['nomor']) : NULL);
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
					$return = $this->dtransaksimentor->get_data_mentor_bom('open', $nomor, NULL, NULL, $filter_status);
					echo $return;
					break;
				} else {
					// $this->get_mentor(NULL, $nomor, 'n', 'n');
					$this->get_data(NULL, $nomor, 'n', 'n');
					break;
				}
			case 'mentee':
				// $nomor  		= (isset($_POST['nomor']) ? $_POST['nomor'] : NULL);
				$nomor  = (isset($_POST['nomor']) ? $this->generate->kirana_decrypt($_POST['nomor']) : NULL);
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
					$return = $this->dtransaksimentor->get_data_mentee_bom('open', $nomor, NULL, NULL, $filter_status);
					echo $return;
					break;
				} else {
					// $this->get_mentee(NULL, $nomor, 'n', 'n');
					$this->get_data(NULL, $nomor, 'n', 'n');
					break;
				}
			case 'history':
				$nomor  = (isset($_POST['nomor']) ? $this->generate->kirana_decrypt($_POST['nomor']) : NULL);
				$this->get_history(NULL, $nomor, 'n', NULL);
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
		}
		if ($action) {
			switch ($param) {
				case 'data':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_depo_data", array(
						array(
							'kolom' => 'id_data',
							'value' => $this->generate->kirana_decrypt($_POST['id_data'])
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
	public function save($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'mentee':
				$this->save_mentee($param2);
				break;
			case 'approve_mentee':
				$this->save_approve_mentee($param2);
				break;
			case 'approve_mentor':
				$this->save_approve_mentor($param2);
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
    private function get_range($param = NULL)
    {
        $result = $this->dtransaksimentor->get_data_range($param);

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "autocomplete") {
            $result  = array(
                "total_count" => count($result),
                "incomplete_results" => false,
                "items" => $result
            );
            echo json_encode($result);
        } else {
            return $result;
        }
    }
	
	private function get_history($array = NULL, $nomor = NULL, $active = NULL, $deleted = NULL)
	{
		$history 	= $this->dtransaksimentor->get_data_history("open", $nomor, $active, $deleted);
		// $history 	= $this->general->generate_encrypt_json($history, array("nomor"));
		if ($array) {
			return $history;
		} else {
			echo json_encode($history);
		}
	}
	
	private function get_user_autocomplete($jenis = NULL)
	{
		if (isset($_GET['q'])) {
			$data	= $this->dtransaksimentor->get_data_user_autocomplete($_GET['q'], $jenis);
			$data_json = array(
				"total_count"        => count($data),
				"incomplete_results" => false,
				"items"              => $data
			);
			echo json_encode($data_json);
		}
	}
	
	private function save_mentee($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);
		$act  = $post['act'];

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		//cek nomor
		$nomor  = (isset($post['nomor']) ? $this->generate->kirana_decrypt($post['nomor']) : NULL);
		if ($nomor!=NULL){	
			if($act=='sesi1'){
				$data_row = array(
					"nik_mentee"			=> $this->general->emptyconvert($post['nik_mentee']),
					"jabatan_mentee"		=> $this->general->emptyconvert($post['jabatan_mentee']),
					"departemen_mentee"		=> $this->general->emptyconvert($post['departemen_mentee']),
					"telepon_mentee"		=> $this->general->emptyconvert($post['telepon_mentee']),
					"tanggal_sesi1_rencana"	=> date_create($post['tanggal_sesi1_rencana'])->format('Y-m-d'),
					"tanggal_sesi2_rencana"	=> date_create($post['tanggal_sesi2_rencana'])->format('Y-m-d'),
					"tanggal_dmc1_rencana"	=> date_create($post['tanggal_dmc1_rencana'])->format('Y-m-d'),
					"tanggal_dmc2_rencana"	=> date_create($post['tanggal_dmc2_rencana'])->format('Y-m-d'),
					"tanggal_dmc3_rencana"	=> date_create($post['tanggal_dmc3_rencana'])->format('Y-m-d'),
				);			
			}			
			if($act=='sesi2'){
				$data_row = array(
					"tanggal_sesi2_rencana"	=> date_create($post['tanggal_sesi2_rencana'])->format('Y-m-d'),
					"tanggal_dmc1_rencana"	=> date_create($post['tanggal_dmc1_rencana'])->format('Y-m-d'),
					"tanggal_dmc2_rencana"	=> date_create($post['tanggal_dmc2_rencana'])->format('Y-m-d'),
					"tanggal_dmc3_rencana"	=> date_create($post['tanggal_dmc3_rencana'])->format('Y-m-d'),
				);			
			}			
			if($act=='dmc1'){
				$data_row = array(
					"tanggal_dmc1_rencana"	=> date_create($post['tanggal_dmc1_rencana'])->format('Y-m-d'),
					"tanggal_dmc2_rencana"	=> date_create($post['tanggal_dmc2_rencana'])->format('Y-m-d'),
					"tanggal_dmc3_rencana"	=> date_create($post['tanggal_dmc3_rencana'])->format('Y-m-d'),
				);			
			}			
			if($act=='dmc2'){
				$data_row = array(
					"tanggal_dmc2_rencana"	=> date_create($post['tanggal_dmc2_rencana'])->format('Y-m-d'),
					"tanggal_dmc3_rencana"	=> date_create($post['tanggal_dmc3_rencana'])->format('Y-m-d'),
				);			
			}			
			if($act=='dmc3'){
				$data_row = array(
					"tanggal_dmc3_rencana"	=> date_create($post['tanggal_dmc3_rencana'])->format('Y-m-d'),
				);			
			}			
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_mentor_data", $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 1,
				"author"	=> 'mentor',
				"action"	=> 'edit',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
		}else{
			$nomor = $this->generate_nomor(
				array(
					"connect" => TRUE
				)
			);
			$data_row = array(
				"nomor"					=> $nomor,
				"id_status"				=> 1,
				"nik_mentor"			=> base64_decode($this->session->userdata("-nik-")),
				"nik_mentee"			=> $this->general->emptyconvert($post['nik_mentee']),
				"jabatan_mentee"		=> $this->general->emptyconvert($post['jabatan_mentee']),
				"departemen_mentee"		=> $this->general->emptyconvert($post['departemen_mentee']),
				"telepon_mentee"		=> $this->general->emptyconvert($post['telepon_mentee']),
				"tanggal_sesi1_rencana"	=> date_create($post['tanggal_sesi1_rencana'])->format('Y-m-d'),
				"tanggal_sesi2_rencana"	=> date_create($post['tanggal_sesi2_rencana'])->format('Y-m-d'),
				"tanggal_dmc1_rencana"	=> date_create($post['tanggal_dmc1_rencana'])->format('Y-m-d'),
				"tanggal_dmc2_rencana"	=> date_create($post['tanggal_dmc2_rencana'])->format('Y-m-d'),
				"tanggal_dmc3_rencana"	=> date_create($post['tanggal_dmc3_rencana'])->format('Y-m-d'),
			);			
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_mentor_data", $data_row);

			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 1,
				"author"	=> 'mentor',
				"action"	=> 'create',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);

		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			// $data_mentor = $this->dtransaksimentor->get_data_mentor(NULL, '0001/KMG/10/2022');
			$data_mentor = $this->dtransaksimentor->get_data_mentor(NULL, $nomor);
			//send email
			$this->send_email(
				array(
					"post" 		=> $post,
					"header" 	=> $data_mentor,
					"act" 		=> 'create'
				)
			);
			
			$msg = "Penambahan Mentee Berhasil.";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	private function save_approve_mentee($param) {
		$datetime 	= date("Y-m-d H:i:s");
        $html = false;
        $post = $this->input->post(NULL, TRUE);

		$nomor  = (isset($post['nomor']) ? $this->generate->kirana_decrypt($post['nomor']) : NULL);
		$act 	= $post['act'];

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		
		if($act=='sesi1'){
			$file_lampiran_upload = 'dokumen_scraft';		
			$nama_lampiran_upload = str_replace('/','_',$nomor).'_SCRAFT';
			if($_FILES[$file_lampiran_upload]['name'][0]!=''){
				//buat upload lampiran
				$config['upload_path'] 		= $this->general->kirana_file_path($this->router->fetch_module());
				$config['allowed_types'] 	= 'pdf|PDF';			
				$newname	= array($nama_lampiran_upload);			
				$file		= $this->general->upload_files($_FILES[$file_lampiran_upload], $newname, $config);
				$nama_file	= $newname[0];
				$url_file	= $file[0]['url'];
				$tipe_file	= substr($file[0]['file_ext'], 1);
				$ukuran_file= $file[0]['size'];
				if($file === NULL){
					$msg        = "Upload files error";
					$sts        = "NotOK";
					$return     = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				//buat save db
				$data_dokumen    = array(
					"nomor" 		=> $nomor,
					"jenis"			=> 'scraft',
					"nama" 			=> $nama_file,
					"url" 			=> $url_file,
					"tipe" 			=> $tipe_file,
					"ukuran" 		=> $ukuran_file,
					"na" 			=> 'n',
					"del" 			=> 'n',
				);
				$ck_dokumen 	= $this->dtransaksimentor->get_data_dokumen(NULL, $nomor, 'scraft');
				if (count($ck_dokumen) == 0){
					$data_dokumen = $this->dgeneral->basic_column("insert", $data_dokumen);
					$this->dgeneral->insert("tbl_mentor_file", $data_dokumen);
					
				}else{
					$data_dokumen = $this->dgeneral->basic_column("update", $data_dokumen);
					$this->dgeneral->update("tbl_mentor_file", $data_dokumen, array(
						array(
							'kolom' => 'nomor',
							'value' => $nomor
						),
						array(
							'kolom' => 'jenis',
							'value' => 'scraft'
						)
					));
				}
			}
			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 1,
				"author"	=> 'mentee',
				"action"	=> 'upload',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
			
		}
		
		if($act=='dmc1'){
			//update header
			$data_row = array(
				"id_status"						=> 4,
				"sasaran_pengembangan_dmc1"		=> $post['sasaran_pengembangan_dmc1'],
				"kriteria_keberhasilan_dmc1"	=> $post['kriteria_keberhasilan_dmc1'],
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//======insert feedback======//
			$nik_mentor = ($post['nik_mentor_dmc1']!=null)?$post['nik_mentor_dmc1']:$post['nik_mentor'];
			$data_feedbacks = array();
			foreach ($post['id_feedback_dmc1'] as $index => $id_feedback) {	
				$jawaban = "feedback_dmc1_".$id_feedback;
				$data_feedback    = array(
					"nomor" 		=> $nomor,
					"id_feedback" 	=> $post['id_feedback_dmc1'][$index],
					"dmc" 			=> 1,
					"nik_mentor"	=> $nik_mentor,
					"jawaban" 		=> $post[$jawaban],
					"na" 			=> 'n',
					"del" 			=> 'n',
				);
				$data_feedback = $this->dgeneral->basic_column('insert', $data_feedback, $datetime);
				$data_feedbacks[] = $data_feedback;
			}
			$this->dgeneral->insert_batch('tbl_mentor_data_feedback', $data_feedbacks);

			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 4,
				"author"	=> 'mentee',
				"action"	=> 'input mentee rating dmc 1',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
			
		}
		if($act=='dmc2'){
			//update header
			$data_row = array(
				"id_status"						=> 5,
				"sasaran_pengembangan_dmc2"		=> $post['sasaran_pengembangan_dmc2'],
				"kriteria_keberhasilan_dmc2"	=> $post['kriteria_keberhasilan_dmc2'],
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//======insert feedback======//
			$nik_mentor = ($post['nik_mentor_dmc2']!=null)?$post['nik_mentor_dmc2']:$post['nik_mentor'];
			$data_feedbacks = array();
			foreach ($post['id_feedback_dmc2'] as $index => $id_feedback) {	
				$jawaban = "feedback_dmc2_".$id_feedback;
				$data_feedback    = array(
					"nomor" 		=> $nomor,
					"id_feedback" 	=> $post['id_feedback_dmc2'][$index],
					"dmc" 			=> 2,
					"nik_mentor"	=> $nik_mentor,
					"jawaban" 		=> $post[$jawaban],
					"na" 			=> 'n',
					"del" 			=> 'n',
				);
				$data_feedback = $this->dgeneral->basic_column('insert', $data_feedback, $datetime);
				$data_feedbacks[] = $data_feedback;
			}
			$this->dgeneral->insert_batch('tbl_mentor_data_feedback', $data_feedbacks);
			
			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 5,
				"author"	=> 'mentee',
				"action"	=> 'input mentee rating dmc 2',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
		}
		
		if($act=='dmc3'){
			//update header
			$data_row = array(
				"id_status"						=> 6,
				"sasaran_pengembangan_dmc3"		=> $post['sasaran_pengembangan_dmc3'],
				"kriteria_keberhasilan_dmc3"	=> $post['kriteria_keberhasilan_dmc3'],
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//======insert feedback======//
			$nik_mentor = ($post['nik_mentor_dmc3']!=null)?$post['nik_mentor_dmc3']:$post['nik_mentor'];
			$data_feedbacks = array();
			foreach ($post['id_feedback_dmc3'] as $index => $id_feedback) {	
				$jawaban = "feedback_dmc3_".$id_feedback;
				$data_feedback    = array(
					"nomor" 		=> $nomor,
					"id_feedback" 	=> $post['id_feedback_dmc3'][$index],
					"dmc" 			=> 3,
					"nik_mentor"	=> $nik_mentor,
					"jawaban" 		=> $post[$jawaban],
					"na" 			=> 'n',
					"del" 			=> 'n',
				);
				$data_feedback = $this->dgeneral->basic_column('insert', $data_feedback, $datetime);
				$data_feedbacks[] = $data_feedback;
			}
			$this->dgeneral->insert_batch('tbl_mentor_data_feedback', $data_feedbacks);

			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 6,
				"author"	=> 'mentee',
				"action"	=> 'Completed',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Input Data Berhasil.";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
    private function save_approve_mentor()
    {
        $datetime 	= date("Y-m-d H:i:s");
        $post 		= $this->input->post(NULL, TRUE);
		$nomor  	= (isset($post['nomor']) ? $this->generate->kirana_decrypt($post['nomor']) : NULL);
        $act	 	= $post['act'];

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

		//additional mentor
		if($act=='mentor_dmc1'){
			//update header
			$data_row = array(
				"nik_mentor_dmc1"		=> $this->general->emptyconvert($post['nik_mentor_dmc1']),
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 3,
				"author"	=> 'mentor',
				"action"	=> 'input additional mentor dmc1',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
			
		}
		if($act=='mentor_dmc2'){
			//update header
			$data_row = array(
				"nik_mentor_dmc2"		=> $this->general->emptyconvert($post['nik_mentor_dmc2']),
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
		}
		if($act=='mentor_dmc3'){
			//update header
			$data_row = array(
				"nik_mentor_dmc3"		=> $this->general->emptyconvert($post['nik_mentor_dmc3']),
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			
		}
		
		//save approval
		if($act=='sesi1'){
			$data_row = array(
				"id_status" 			=> 2,
				"tanggal_sesi1_aktual" 	=> date_create($post['tanggal_sesi1_aktual'])->format('Y-m-d'),
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 2,
				"author"	=> 'mentor',
				"action"	=> 'input tanggal sesi 1',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
			
		}
		if($act=='sesi2'){
			// save db
			$data_row = array(
				"id_status" 			=> 3,
				"tanggal_sesi2_aktual" 	=> date('Y-m-d'),
				"sasaran_pengembangan"	=> $post['sasaran_pengembangan'],
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 3,
				"author"	=> 'mentor',
				"action"	=> 'input tanggal sesi 2',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
		}
		if($act=='dmc1'){
			$data_row = array(
				// "id_status" 				=> 4,
				"tanggal_dmc1_aktual" 		=> date_create($post['tanggal_dmc1_aktual'])->format('Y-m-d'),
				"isu_dmc1" 					=> $post['isu_dmc1'],
				"tujuan_dmc1" 				=> $post['tujuan_dmc1'],
				"realitas_dmc1" 			=> $post['realitas_dmc1'],
				"opsi_dmc1" 				=> $post['opsi_dmc1'],
				"rencana_aksi_dmc1" 		=> $post['rencana_aksi_dmc1'],
				"waktu_dmc1" 				=> $post['waktu_dmc1'],
				"indikator_berhasil_dmc1" 	=> $post['indikator_berhasil_dmc1'],
				"catatan_dmc1" 				=> $post['catatan_dmc1'],
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 3,
				"author"	=> 'mentor',
				"action"	=> 'input jurnal dmc 1',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
			
		}
		if($act=='dmc2'){
			$data_row = array(
				// "id_status" 				=> 5,
				"tanggal_dmc2_aktual" 		=> date_create($post['tanggal_dmc2_aktual'])->format('Y-m-d'),
				"isu_dmc2" 					=> $post['isu_dmc2'],
				"tujuan_dmc2" 				=> $post['tujuan_dmc2'],
				"realitas_dmc2" 			=> $post['realitas_dmc2'],
				"opsi_dmc2" 				=> $post['opsi_dmc2'],
				"rencana_aksi_dmc2" 		=> $post['rencana_aksi_dmc2'],
				"waktu_dmc2" 				=> $post['waktu_dmc2'],
				"indikator_berhasil_dmc2" 	=> $post['indikator_berhasil_dmc2'],
				"catatan_dmc2" 				=> $post['catatan_dmc2'],
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 4,
				"author"	=> 'mentor',
				"action"	=> 'input jurnal dmc 2',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
			
		}
		if($act=='dmc3'){
			$data_row = array(
				// "id_status" 				=> 6,
				"tanggal_dmc3_aktual" 		=> date_create($post['tanggal_dmc3_aktual'])->format('Y-m-d'),
				"isu_dmc3" 					=> $post['isu_dmc3'],
				"tujuan_dmc3" 				=> $post['tujuan_dmc3'],
				"realitas_dmc3" 			=> $post['realitas_dmc3'],
				"opsi_dmc3" 				=> $post['opsi_dmc3'],
				"rencana_aksi_dmc3" 		=> $post['rencana_aksi_dmc3'],
				"waktu_dmc3" 				=> $post['waktu_dmc3'],
				"indikator_berhasil_dmc3" 	=> $post['indikator_berhasil_dmc3'],
				"catatan_dmc3" 				=> $post['catatan_dmc3'],
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row, $datetime);
			$this->dgeneral->update('tbl_mentor_data', $data_row, array(
				array(
					'kolom' => 'nomor',
					'value' => $nomor
				)
			));
			//save data temp log 
			$data_row_log = array(
				"nomor"		=> $nomor,
				"status"	=> 5,
				"author"	=> 'mentor',
				"action"	=> 'input jurnal dmc 3',
				"catatan"	=> ''
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_mentor_data_log_status", $data_row_log);
		}

        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
			$this->dgeneral->commit_transaction();
			// $data_depo = $this->dtransaksidepo->get_data_depo(NULL, $nomor);
			// //send email approval
			// $this->send_email(
				// array(
					// "post" => $post,
					// "header" => $data_depo
				// )
			// );
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
        }
        //============================================//

        $this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
        exit();
    } 
	
	

    private function send_email($param = NULL)
    {
        $post = (object) $param['post'];
        $header = $param['header'];
		if($param['act']=='create'){
			//sent to mentee
			$message = $this->generate_email_message(
				array(
					"post" 		=> $post,
					"header" 	=> $header,
					"sent_to" 	=> 'mentee',
				)
			);
			$subject 	= "Notifikasi Mentoring ".$header[0]->nomor;
			$from_alias	= "Mentoring-Kiranamegatara";
			$email_to	= "RAINHARD.RAHAKBAUW@KIRANAMEGATARA.COM";
			$email_cc	= NULL;
			$this->general->send_email($subject, $from_alias, $email_to, $email_cc, $message);

			//sent to mentor
			$message = $this->generate_email_message(
				array(
					"post" 		=> $post,
					"header" 	=> $header,
					"sent_to" 	=> 'mentor',
				)
			);
			$subject 	= "Notifikasi Mentoring ".$header[0]->nomor;
			$from_alias	= "Mentoring-Kiranamegatara";
			$email_to	= "RAINHARD.RAHAKBAUW@KIRANAMEGATARA.COM";
			$email_cc	= NULL;
			$this->general->send_email($subject, $from_alias, $email_to, $email_cc, $message);

			//sent to lpd
			$data_recipient = $this->dtransaksimentor->get_email_recipient(
				array(
					"connect" => TRUE,
					"nomor" => $post->nomor
				)
			);
			foreach ($data_recipient as $dt) {
				$message = $this->generate_email_message(
					array(
						"post" 		=> $post,
						"header" 	=> $header,
						"penerima" 	=> $dt->nama,
						"sent_to" 	=> 'lpd',
					)
				);
				$subject 	= "Notifikasi Mentoring ".$header[0]->nomor;
				$from_alias	= "Mentoring-Kiranamegatara";
				$email_to	= "RAINHARD.RAHAKBAUW@KIRANAMEGATARA.COM";
				$email_cc	= NULL;
				$this->general->send_email($subject, $from_alias, $email_to, $email_cc, $message);
			}
			
		}
        return true;
    }

    private function generate_email_message($param = NULL)
    {
        $post = (object) $param['post'];
        $header = $param['header'];
        if($param['sent_to']=='mentor'){
			$data_status 	 = $this->dmastermentor->get_data_status(
									array(
										"connect" 	=> TRUE,
										"na" 		=> 'n',
										"encrypt" 	=> array("id")
									));
			$list_status	= "";						
			foreach($data_status as $dt){
				if($dt->id_status<=5){
					$list_status	.= "
										<button type='button' style='
											background-color: ".$dt->warna.";
											border: none;
											color: white;
											padding: 10px 24px;
											text-align: center;
											text-decoration: none;
											display: inline-block;
											font-size: 16px;		
											border-radius: 8px;'>".$dt->nama."</button>";
					
				}
			}	
			$isi_email = "
				<p><strong>Kepada Yth.<br>Bapak /Ibu<br>".$header[0]->nama_mentor."<br>Peserta & Atasan Mentor<br><br>Salam Pemimpin!</strong></p>
				<p>Kami mengucapkan terima kasih atas kesediaan Bapak/Ibu dalam mendukung pengembangan Tim melalui Mentoring. Semoga pembekalan mentoring yang lalu dapat bermanfaat dalam proses pengembangan Tim Bapak/Ibu.</p>
				<p>Sebagai langkah tindak lanjut dari Pembekalan yang lalu, Bapak/Ibu  akan masuk dalam tahap implementasi di tempat kerja.  Bapak/Ibu diharapkan melakukan praktik 1 on 1 kepada tim dengan minimal 3 anggota  tim dalam waktu kurang lebih 2.5 Bulan kedepan.  Dibawah ini adalah tahapan dalam proses implementasi mentoring:</p>
				<div align='center'>
					".$list_status."
				</div>
				<p>Berikut adalah Jadwal Rencana Pengembangan Pribadi yang Bapak/Ibu telah susun sebelumnya. <b>Informasi ini pun paralel akan kami distribusikan  pada mentee Bapak/Ibu.</b></p>
				<table border='1' style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
					<tr>
						<th style='background-color:#b2dbf5;'>NIK</th>
						<th style='background-color:#b2dbf5;'>Nama Mentee</th>
						<th style='background-color:#b2dbf5;'>Jabatan Mentee</th>
						<th style='background-color:#b2dbf5;'>Persiapan 1</th>
						<th style='background-color:#b2dbf5;'>Persiapan 2</th>
						<th style='background-color:#b2dbf5;'>DMC 1</th>
						<th style='background-color:#b2dbf5;'>DMC 2</th>
						<th style='background-color:#b2dbf5;'>DMC 3</th>
					</tr>
					<tr>
						<td style='background-color:#fff;'>".$header[0]->nik_mentee."</td>
						<td style='background-color:#fff;'>".$header[0]->nama_mentee."</td>
						<td style='background-color:#fff;'>".$header[0]->nama_jabatan_mentee."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_sesi1_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_sesi2_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_dmc1_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_dmc2_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_dmc3_rencana_format."</td>
					</tr>
				</table>
				<p>* Sesi Introduction dapat dilakukan secara bersama-sama</p>
				<p>Demikian informasi jadwal dan komitmen Bapak/Ibu, jika ada yang dapat kami bantu terkait pertanyaan atau kendala dalam melakukan sesi mentoring, kami dapat dihubungi via email felix.mulyawan@kiranamegatara.com/ 081808220300. Kami akan dengan senang hati membantu Bapak/Ibu sebaik dan secepat yang kami bisa.</p>
				<p>Sukses selalu dan selamat menjalankan implementasi di tempat kerja!</p>
			";
		}else if($param['sent_to']=='mentee'){
			$isi_email = "
				<p><strong>Kepada Yth.<br>Bapak /Ibu<br>".$header[0]->nama_mentee."<br><br>Salam Pemimpin!</strong></p>
				<p>Sebagai bagaian dari proses pengembangan diri Bapak/Ibu, ijinkan kami melalui email ini menginformasikan bahwa Bapak/Ibu akan terlibat dalam proses pengembangan yaitu  mentoring session yang akan dilakukan oleh Bapak/Ibu <b>".$header[0]->nama_mentor."</b> yang selanjutnya akan menjadi Mentor Anda.</p>
				<p>Bapak/Ibu akan melakukan sesi mentoring sebanyak  5X. (2 Sesi persiapan dan 3 Sesi DMC). Berikut adalah Jadwal Rencana Jadwal Mentoring yang telah Mentor  Bapak/Ibu  susun sebelumnya : </p>
				<table border='1' style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
					<tr>
						<th style='background-color:#b2dbf5;'>NIK</th>
						<th style='background-color:#b2dbf5;'>Nama Mentee</th>
						<th style='background-color:#b2dbf5;'>Jabatan Mentee</th>
						<th style='background-color:#b2dbf5;'>Persiapan 1</th>
						<th style='background-color:#b2dbf5;'>Persiapan 2</th>
						<th style='background-color:#b2dbf5;'>DMC 1</th>
						<th style='background-color:#b2dbf5;'>DMC 2</th>
						<th style='background-color:#b2dbf5;'>DMC 3</th>
					</tr>
					<tr>
						<td style='background-color:#fff;'>".$header[0]->nik_mentee."</td>
						<td style='background-color:#fff;'>".$header[0]->nama_mentee."</td>
						<td style='background-color:#fff;'>".$header[0]->nama_jabatan_mentee."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_sesi1_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_sesi2_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_dmc1_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_dmc2_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_dmc3_rencana_format."</td>
					</tr>
				</table>
				<p>* Sesi Introduction dapat dilakukan secara bersama-sama</p>
				<p>Demikian informasi jadwal dan komitmen Bapak/Ibu, jika ada yang dapat kami bantu terkait pertanyaan atau kendala dalam melakukan sesi mentoring, kami dapat dihubungi via email felix.mulyawan@kiranamegatara.com/ 081808220300. Kami akan dengan senang hati membantu Bapak/Ibu sebaik dan secepat yang kami bisa.</p>
				<p>Sukses selalu dan selamat menjalankan implementasi di tempat kerja!</p>
			";
		}else if($param['sent_to']=='lpd'){
			$isi_email = "
				<p><strong>Kepada Yth.<br>Bapak /Ibu<br>".$param['penerima']."<br><br>Salam Pemimpin!</strong></p>
				<p>Bersama ini kami kirimkan detail mentoring yang akan dilakukan oleh <b>".$header[0]->nama_mentor."</b> dengan detail sebagai berikut:</p>
				<table border='1' style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
					<tr>
						<th style='background-color:#b2dbf5;'>NIK</th>
						<th style='background-color:#b2dbf5;'>Nama Mentee</th>
						<th style='background-color:#b2dbf5;'>Jabatan Mentee</th>
						<th style='background-color:#b2dbf5;'>Persiapan 1</th>
						<th style='background-color:#b2dbf5;'>Persiapan 2</th>
						<th style='background-color:#b2dbf5;'>DMC 1</th>
						<th style='background-color:#b2dbf5;'>DMC 2</th>
						<th style='background-color:#b2dbf5;'>DMC 3</th>
					</tr>
					<tr>
						<td style='background-color:#fff;'>".$header[0]->nik_mentee."</td>
						<td style='background-color:#fff;'>".$header[0]->nama_mentee."</td>
						<td style='background-color:#fff;'>".$header[0]->nama_jabatan_mentee."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_sesi1_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_sesi2_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_dmc1_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_dmc2_rencana_format."</td>
						<td style='background-color:#fff;'>".$header[0]->tanggal_dmc3_rencana_format."</td>
					</tr>
				</table>
			";
		}else{
			$isi_email = "";
		}

        $message = "<html>
                        <body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
                        <center style='width: 100%;'>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                Notifikasi Email Aplikasi Mentoring
                            </div>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
                            </div>
                            <div class='email-container' style='max-width: 800px; margin: 0 auto;'>
                                <table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'
                                       style='min-width:600px;'>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style='color: #fff; padding:20px;' align='center'>
                                            <div style='width: 50%; padding-bottom: 10px;''>
                                                <img src='" . base_url() . "/assets/apps/img/logo-lg.png'>
                                            </div>
                                            <h1 style='margin-bottom: 0;'>Mentoring</h1>
                                            <hr style='border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;'/>
                                            <h3 style='margin-top: 0;'>Notifikasi Email</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table style='background-color: #ffffff; margin: auto; -webkit-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); -moz-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4);'
                                                   role='presentation' border='0' width='100%' cellspacing='0'
                                                   cellpadding='0'
                                                   align='center'>
                                                <tbody>
                                                <tr>
                                                    <td style='padding: 20px;'>
														".$isi_email."
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align='left'
                                                        style='background-color: #ffffff; padding: 15px; border-top: 1px dashed #386d22;'>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align='center' style='padding-bottom: 10px; font-size:15px;'><b>Click Tombol dibawah ini untuk login pada Portal Kiranaku</b></td>
                                                </tr>
                                                <tr>
                                                    <td align='center' style='padding-bottom: 20px;'>
                                                        <a href='" . base_url() . 'mentor/transaksi/mentee' . "' style='color: #fff;
                                                            text-decoration: none; 
                                                            background-color: #008d4c;
                                                            border-color: #4cae4c; 
                                                            display: inline-block; 
                                                            margin-bottom: 0; 
                                                            font-weight: 400; 
                                                            text-align: center; 
                                                            white-space: nowrap; 
                                                            vertical-align: middle; 
                                                            cursor: pointer;
                                                            background-image: none;
                                                            border: 1px solid transparent;
                                                            padding: 6px 80px;
                                                            font-size: 17px;
                                                            letter-spacing: 2px;
                                                            line-height: 1.42857143;
                                                            border-radius: 4px;'>Login</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align='left'
                                                        style='background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;'>
														<p>Terima kasih atas perhatian dan kerja sama dari Bapak/Ibu.</p>
														<p>Salam,<br><br><b>LPD</b></p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='color: #fff; padding-top:20px;' align='center'>
                                            <small>Kiranaku Auto-Mail System</small><br/>
                                            <strong style='color: #214014; font-size: 10px;'>Terkirim pada " . date('d.m.Y H:i:s') . "</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </div>
                        </center>
                        </body>
                    </html>";

        return $message;
    }
	
	private function get_user($array = NULL, $nik = NULL, $na = NULL, $del = NULL)
	{
		//header
		$data	= $this->dtransaksimentor->get_data_user("open", $nik, $na, $del);
		$data 	= $this->general->generate_encrypt_json($data, array("id_user"));
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_data($array = NULL, $nomor = NULL, $na = NULL, $del = NULL)
	{
		//header
		$data	= $this->dtransaksimentor->get_data_mentor("open", $nomor, $na, $del);
		$data 	= $this->general->generate_encrypt_json($data, array("nomor"));
		
		//detail
		$data_feedback	= $this->get_feedback("array", $data[0]->nomor_mentoring, $data[0]->nik_mentor, $data[0]->nik_mentor_dmc1, $data[0]->nik_mentor_dmc2, $data[0]->nik_mentor_dmc3);
		$data[0]->arr_data_feedback = $data_feedback;

		//range_date
		$param_ = array(
			"connect" 	 => TRUE,
			"id_status"  => 1,
			"return"	 => 'array',
			"encrypt" 	 => array("id")
		);
		$data_range	= $this->get_range($param_);
		$data[0]->arr_data_range = $data_range;
		
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}
	private function get_feedback($array = NULL, $nomor_mentoring = NULL, $nik_mentor = NULL, $nik_mentor_dmc1 = NULL, $nik_mentor_dmc2 = NULL, $nik_mentor_dmc3 = NULL)
	{
		$data	= $this->dtransaksimentor->get_data_feedback("open", $nomor_mentoring, $nik_mentor, $nik_mentor_dmc1, $nik_mentor_dmc2, $nik_mentor_dmc3);
		$data 	= $this->general->generate_encrypt_json($data);
		if ($array) {
			return $data;
		} else {
			echo json_encode($data);
		}
	}

	private function generate_nomor($param = NULL)
	{
		$month      = date('m');
		$year       = date('Y');
		return $this->dtransaksimentor->get_nomor(
			array(
				"connect" => $param['connect'],
				"month" => $month,
				"year" => $year
			)
		)->nomor;
	}
	
	/*====================================================================*/
}
