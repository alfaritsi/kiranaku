<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : BUKU TAMU (Event)
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Event extends MX_Controller{
    private $kiranaku_dmz_url = 'http://10.0.0.105/uat/kiranaku_dmz/';
    //private $kiranaku_dmz_url = 'http://127.0.0.1/kiranaku_dmz/';

	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		
	    $this->load->model('devent');
	}

	public function index(){
		show_404();
	}
	
	public function data($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$filter_from		 = date("d.m.Y");
		$filter_to 			 = date("d.m.Y");
		$data['title']    	 = "Data Event";
		$data['title_form']  = "Data Event";

        $param_ev = array(
            "connect" => TRUE,
            "data" => 'complete',
            // "filter_from" => $filter_from,
            "encrypt" => array("id")
        );
		$data['event']  	 = $this->get_data_event($param_ev);
		$this->load->view("event/page", $data);	
	}

    public function detail($key = NULL)
    {
        //====must be initiate in every view function====/
	    // $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/

        $data['title']    	 = "Data Event";
		$data['title_form']  = "Data Event";

        $param_ev = array(
            "connect" => TRUE,
            "data" => 'header',
            "id_event" => $this->generate->kirana_decrypt($key),
            "encrypt" => array("id")
        );
		$data['event'] = $this->get_data_event($param_ev);

        if (empty($data['event']))
            show_404();

        // echo json_encode($data);
        // exit();
		$this->load->view("event/detail", $data);
    }

    // public function kirim_email()
    // {
    //     $this->send_email(array("id_event" => 2));
    // }

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) 
    {
		switch ($param) {
			case 'event':
				$id_event 		  = (isset($_POST['id_event']) ? $this->generate->kirana_decrypt($_POST['id_event']) : NULL);
				$filter_from 	  = (isset($_POST['filter_from'])) ? $_POST['filter_from'] : NULL;
				$filter_to 		  = (isset($_POST['filter_to'])) ? $_POST['filter_to'] : NULL;
				$param_ev = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "id_event" => $id_event,
                    // "page" => $this->input->post("page", TRUE),
                    "encrypt" => array("id")
                );
				$this->get_data_event($param_ev);
				break;
            case 'peserta':
                $id_event 		  = (isset($_POST['id_event']) ? $this->generate->kirana_decrypt($_POST['id_event']) : NULL);
				$id_peserta 	  = (isset($_POST['id_peserta']) ? $this->generate->kirana_decrypt($_POST['id_peserta']) : NULL);
				$param_dt = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "id_event" => $id_event,
                    "id_peserta" => $id_peserta,
                    "encrypt" => array("id", "id_event")
                );
				$this->get_data_peserta($param_dt);
				break;
            case 'karyawan':
                $this->general->connectDbPortal();
                $list = $this->devent->get_karyawan(
                    array(
                        'search' => $this->input->post('q')
                    )
					,$param2
                );
                echo json_encode(array('data' => $list));
                break;
				
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set($param = NULL) 
    {
        switch ($param) {
            case 'event':
                $action = (isset($_POST['type']) ? $_POST['type'] : NULL);
                $id_event = (isset($_POST['id_event']) ? $this->generate->kirana_decrypt($_POST['id_event']) : NULL);
                if ($action && $id_event) {
                    if ($action == 'delete') { //$action = 'delete_na_del';
                        $data_row = array(
                            'na'  => 'y',
                            'del' => 'y'
                        );
                    }

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column("update", $data_row);
                    $this->dgeneral->update("tbl_tamu_event", $data_row , array(
                        array(
                            'kolom' => 'id',
                            'value' => $id_event
                        )
                    ));

                    $this->dgeneral->update("tbl_tamu_peserta", $data_row , array(
                        array(
                            'kolom' => 'id_event',
                            'value' => $id_event
                        )
                    ));

                    if ($this->db->trans_status() === false) {
                        $this->dgeneral->rollback_transaction();
                        $msg 	= "Gagal Menghapus";
                        $sts 	= "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg 	= "Data berhasil dihapus";
                        $sts 	= "OK";
                    }
                    $this->general->closeDb();
                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                }
                break;
            case 'peserta':
                $action = (isset($_POST['type']) ? $_POST['type'] : NULL);
                $id_peserta = (isset($_POST['id_peserta']) ? $this->generate->kirana_decrypt($_POST['id_peserta']) : NULL);
                if ($action && $id_peserta) {
                    if ($action == 'delete') { //$action = 'delete_na_del';
                        $data_row = array(
                            'na'  => 'y',
                            'del' => 'y'
                        );
                    }
                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();
                    
                    $data_row = $this->dgeneral->basic_column("update", $data_row);
                    $this->dgeneral->update("tbl_tamu_peserta", $data_row , array(
                        array(
                            'kolom' => 'id',
                            'value' => $id_peserta
                        )
                    ));

                    if ($this->db->trans_status() === false) {
                        $this->dgeneral->rollback_transaction();
                        $msg 	= "Gagal Menghapus";
                        $sts 	= "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg 	= "Data berhasil dihapus";
                        $sts 	= "OK";
                    }
                    $this->general->closeDb();
                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                }
                break;
            case 'send_email':
                $id_event = (isset($_POST['id_event']) ? $this->generate->kirana_decrypt($_POST['id_event']) : NULL);
                $id_peserta = (isset($_POST['id_peserta']) ? $this->generate->kirana_decrypt($_POST['id_peserta']) : NULL);
                if($id_event) {
                    $return = $this->send_email(array(
                        'id_event' => $id_event,
                        'id_peserta' => $id_peserta
                    ));
                    
                    if ($return === true) {
                        $msg = "Berhasil";
                        $sts = "OK";
                    } else {
                        $msg = "Gagal Kirim Email";
                        $sts = "NotOK";
                    }
                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                }
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
	}

	public function save($param = NULL, $param2 = NULL) {
		switch ($param) {
			case 'event':
				$this->save_event($param);
				break;
            case 'upload':
                if ($param2 == "peserta")
                    $data = $this->save_upload_peserta($param);
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
	private function get_data_event($param = NULL) 
    {
		switch ($param['data']) {
            case 'header':
                $result = $this->devent->get_data_event($param);
                // if ($result) {
                //     if (isset($param['return']) && $param['return'] == "datatables")
                //         $result = json_decode($result, true);

                //     if (is_object($result) === TRUE) {
                //     } else {
                //         $newResult = array();
                //         foreach ($result as $key => $data) {
                //             $newData = array();
                //             if ($key == 'data') {
                //                 foreach ($data as $val) {
                //                     $newData[] = $val;
                //                 }
                //             } else {
                //                 $newData = $data;
                //             }
                //             $newResult[$key] = $newData;
                //         }

                //         $result = $newResult;
                //         if (isset($param['return']) && $param['return'] == "datatables")
                //             $result = $this->general->jsonify($result);
                //     }
                // }
                break;
            case 'complete':
                $result = $this->devent->get_data_event($param);
                unset($param['encrypt']);
                if ($result) {
                    
                }
                break;

            default:
                $result = $this->devent->get_data_event($param);
                break;
        }

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
	}

    private function get_data_peserta($param = NULL) 
    {
		switch ($param['data']) {
            case 'header':
                $result = $this->devent->get_data_peserta($param);
                // if ($result) {
                //     if (isset($param['return']) && $param['return'] == "datatables")
                //         $result = json_decode($result, true);

                //     if (is_object($result) === TRUE) {
                //     } else {
                //         $newResult = array();
                //         foreach ($result as $key => $data) {
                //             $newData = array();
                //             if ($key == 'data') {
                //                 foreach ($data as $val) {
                //                     $newData[] = $val;
                //                 }
                //             } else {
                //                 $newData = $data;
                //             }
                //             $newResult[$key] = $newData;
                //         }

                //         $result = $newResult;
                //         if (isset($param['return']) && $param['return'] == "datatables")
                //             $result = $this->general->jsonify($result);
                //     }
                // }
                break;
            case 'complete':
                $result = $this->devent->get_data_peserta($param);
                unset($param['encrypt']);
                if ($result) {
                    
                }
                break;

            default:
                $result = $this->devent->get_data_peserta($param);
                break;
        }

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
	}

    private function save_event($param)
    {
        $datetime = date("Y-m-d H:i:s");
        $id_event  = (isset($_POST['id_event']) ? $this->generate->kirana_decrypt($_POST['id_event']) : NULL);
        $this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

        $tanggal_event = $this->generate->regenerateDateFormat($this->input->post("tanggal_event", TRUE));
        $jam_mulai = $this->input->post("jam_mulai", TRUE);
        $jam_selesai = $this->input->post("jam_selesai", TRUE);
        $waktu_mulai = $tanggal_event . ' ' . $jam_mulai;
        $waktu_selesai = $tanggal_event . ' ' . $jam_selesai; 

        if ($id_event != NULL){
			$data_row = array(
				"nama_event"	=> $_POST['nama_event'],
				"waktu_mulai" => $waktu_mulai,
				"waktu_selesai"	=> $waktu_selesai,
                "nik_pic"	=> $_POST['nik_pic'],
                "pesan" => $_POST['pesan']
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_tamu_event", $data_row, array(
				array(
					'kolom' => 'id',
					'value' => $id_event
				)
			));
			
		} else {
			$data_row = array(
				"nama_event"	=> $_POST['nama_event'],
				"waktu_mulai" => $waktu_mulai,
				"waktu_selesai"	=> $waktu_selesai,
                "nik_pic"	=> $_POST['nik_pic'],
                "pesan" => $_POST['pesan']
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_tamu_event", $data_row);

            // $return = array('sts' => "N", 'msg' => $data_row);
		    // echo json_encode($return);
            // exit();
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

    private function save_upload_peserta($param = NULL)
	{
        $post 			= $this->input->post(NULL, TRUE);
		$datetime 	= date("Y-m-d H:i:s");
		$id_event  = (isset($_POST['id_event']) ? $this->generate->kirana_decrypt($_POST['id_event']) : NULL);
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        if ($id_event) {
            if(!empty($_FILES['file_excel']['name']) && !empty($_FILES['file_excel']['name']) != ""){
                $target_dir = "./assets/temp";

                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0755, true);
                }

                $config['upload_path']          = $target_dir;
                $config['allowed_types']        = 'xls|xlsx';
        
                $this->load->library('upload', $config);
        
                if ( ! $this->upload->do_upload('file_excel')){
                    $msg 	= $this->upload->display_errors();
                    $sts 	= "NotOK";
                } else {
                    $data 			= array('upload_data' => $this->upload->data());
                    $objPHPExcel 	= PHPExcel_IOFactory::load($data['upload_data']['full_path']);
                    $title_desc		= $objPHPExcel->getProperties()->getTitle();
                    // $objPHPExcel->setActiveSheetIndex(2);
                    $data_excel		= $objPHPExcel->getActiveSheet();
                    $highestRow 	= $data_excel->getHighestRow(); 
                    $highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                    $datetime		= date("Y-m-d H:i:s");
                    $data_row		= array();
                    
                    for ($brs = 2; $brs <= $highestRow; $brs++) {
                        $nama		= $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                        $email	    = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                        $perusahaan	= $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                        $telepon	= $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                        $nik_peserta= $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                        if ($nama == "" || $email == "") {
                            // $return = array('sts' => 'NotOk', 'msg' => "Nama Peserta Harus Terisi");
                            // echo json_encode($return);
                            // exit();
                            break;
                        }
                        // $nik = intval($nik);
                        $data_row	= array(
                            'id_event'          => $id_event,
                            'nik'				=> $nik_peserta,
                            'email'				=> $email,
                            'nama'				=> $nama,
                            'perusahaan'		=> $perusahaan,
                            'telepon'			=> $telepon,
                            // 'login_buat' 		=> $user,
                            // 'tanggal_buat'		=> $datetime,
                            // 'login_edit' 		=> $user,
                            // 'tanggal_edit' 		=> $datetime,
                        );
                        $ck_data 	= $this->devent->get_data_peserta(array(
                            "connect" => false,
                            "id_event" => $id_event,
                            "email" => $email
                        ));
                        if ($ck_data) {
                            // unset($data_row['login_buat']);
                            // unset($data_row['tanggal_buat']);
                            $data_row = $this->dgeneral->basic_column("update", $data_row);
                            $this->dgeneral->update('tbl_tamu_peserta', $data_row, array(
                                array(
                                    'kolom' => 'id_event',
                                    'value' => $id_event
                                ),
                                array(
                                    'kolom' => 'email',
                                    'value' => $email
                                )
                            ));
                        } else {  
                            $data_row = $this->dgeneral->basic_column("insert", $data_row);                          
                            $this->dgeneral->insert("tbl_tamu_peserta", $data_row);
                        }
                    }
                    unlink($data['upload_data']['full_path']);
                    if ($this->db->trans_status() === false) {
                        $this->dgeneral->rollback_transaction();
                        $msg 	= "Periksa kembali data yang diunggah";
                        $sts 	= "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg 	= "Data berhasil diunggah";
                        $sts 	= "OK";
                        //kirim_email
                        $param_email = array(
                            "id_event" => $id_event,
                        );
                        $this->send_email($param_email);
                    }
                }
            } else {
                $msg 	= "Silahkan pilih file yang ingin diunggah";
                $sts 	= "NotOK";
            }
        } else {
            $msg 	= "Silahkan pilih event kegiatan terlebih dahulu";
            $sts 	= "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

		echo json_encode($return);
		exit();
	}

    private function send_email($param) 
    {
        $this->general->connectDbPortal();
        
        $datetime = date('Y-m-d H:i:s');

        $data_peserta = $this->devent->get_data_peserta(array(
            "connect" => TRUE,
            "id_event" => $param['id_event'],           
            "id_peserta" => $param['id_peserta'],
            "encrypt" => array("id", "id_event")
        ));

        $data_event = $this->devent->get_data_event(array(
            "connect" => TRUE,
            "id_event" => $param['id_event'],
            "encrypt" => array("id")
        ));

        $pesan_event = ($data_event->pesan) ? $data_event->pesan : "";
        
        if (is_object($data_peserta) === TRUE) $data_recipient[] = $data_peserta;
        else $data_recipient = $data_peserta;

        // $email_cc = array();
        // $email_to = array();
        // $email_bcc = array();
        foreach ($data_recipient as $dt) {
            if ($dt->email && $dt->has_assessment == 0) {
                $email_cc = ENVIRONMENT == 'development' ? "benazi.bahari@kiranamegatara.com" : $dt->email;
                // $email_to[] = ENVIRONMENT == 'development' ? "syaiful.yamang@kiranamegatara.com" : $dt->email;
                $email_to = $dt->email;
    
                // if (empty($email_to))
                //     $email_to = $email_cc;
        
                $message = $this->generate_email_message(array(
                    "nama_peserta" => $dt->nama,
                    "nama_event" => $dt->nama_event,
                    "tanggal_event" => $dt->tanggal_format,
                    "waktu_mulai" => $dt->waktu_mulai_format,
                    "waktu_selesai" => $dt->waktu_selesai_format,
                    "pesan" => $pesan_event,
                    "link_assessment" => $this->kiranaku_dmz_url.'tamu/transaksi/input/'.$dt->id
                ));
        
                $return = $this->general->send_email_new(
                    array(
                        'subject' => "Registrasi dan Self Assessment",
                        'from_alias' => 'Kirana Megatara',
                        'message' => $message,
                        'to' => $email_to
                    )
                );

                $status_email = "success";
                if ($return['sts'] == 'NotOK') {
                    $status_email = "fail";
                    // break;
                }
                $data_status_email = array(
                    "is_email_sent" => 1,
                    "status_email" => $status_email,
                    "tanggal_email_sent" => $datetime
                );
                $data_row = $this->dgeneral->basic_column("update", $data_status_email);
                $this->dgeneral->update("tbl_tamu_peserta", $data_status_email, array(
                    array(
                        'kolom' => 'id',
                        'value' => $this->generate->kirana_decrypt($dt->id)
                    )
                ));
            }
        }        

        return true;
    }

    private function generate_email_message($param = NULL)
    {
        $pesan = "";
        if ($param['pesan'] != '') {
            $pesan = '<tr>
                <td style="padding:10px;font-family:helvetica,arial;font-size:14px;line-height:20px;color:#333333">
                    ' . $param['pesan'] . '
                </td>
            </tr>';
        }
        $message = '<html>
            <body>
                <table border="0" cellpadding="0" cellspacing="0" style="width:100%;height:100%;background-color:#f0f2f4">
                    <tbody>
                        <tr>
                            <td style="vertical-align:top;background-color:#f0f2f4">
                                <table border="0" cellpadding="0" cellspacing="0" align="center" style="width:600px">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:center;padding-top:40px;padding-bottom:20px">
                                            <a href="https://kiranamegatara.com/" target="_blank">
                                                <img src="https://shipment.kiranamegatara.com/kiranaku_dmz/assets/apps/img/Logo_KM_horizontal.png" style="width:220px;height:auto;border-width:0;border-style:solid" class="CToWUd">
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color:#ffffff;padding-bottom:40px">
                                            <table border="0" cellpadding="0" cellspacing="0" align="center" style="width:520px">
                                                <tbody>
                                                    <tr>
                                                        <td style="padding:40px 10px 10px;font-family:helvetica,arial;font-size:14px;line-height:20px;color:#333333">
                                                            Kepada Yth. Bpk/Ibu <b>' . $param['nama_peserta'] . '</b>,
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:10px;font-family:helvetica,arial;font-size:14px;line-height:20px;color:#333333">
                                                            Anda terdaftar sebagai peserta pada acara <b>' . $param['nama_event'] . '</b>.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:10px;font-family:helvetica,arial;font-size:14px;line-height:20px;color:#333333">
                                                            Pada tanggal, <b>' . $param['tanggal_event'] . '</b>, Jam <b>' . $param['waktu_mulai'] . ' - ' . $param['waktu_selesai'] . '</b>
                                                        </td>
                                                    </tr>' . $pesan . '
                                                    <tr>
                                                        <td style="padding:10px;font-family:helvetica,arial;font-size:14px;line-height:20px;color:#333333">
                                                            Sebelum memasuki lokasi PT Kirana Megatara Tbk, mohon Bapak/Ibu dapat melakukan registrasi dan pengisian Self Assesment Covid-19.<br>
                                                            Klik <b><a href="' . $param['link_assessment'] . '" target="_blank">link ini</a></b> untuk melakukan registrasi.
                                                            Anda bisa melakukan registrasi mulai dari H-1 acara.
                                                            Mohon abaikan email ini jika anda sudah melakukan registrasi pada link di atas.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:10px;font-family:helvetica,arial;font-size:14px;line-height:20px;color:#333333">
                                                            Terima kasih atas perhatian dan kerjasamanya.<br>
                                                            <br>
                                                            Hormat kami,<br>
                                                            Management<br>
                                                            PT Kirana Megatara Tbk.<br>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top:30px;padding-bottom:30px;background-color:#ffffff;border-top-color:#f0f2f4;border-top-style:solid;border-top-width:1px">
                                            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-family:helvetica,arial;font-size:12px;line-height:18px;color:#999999;text-align:center;padding-top:10px">
                                                            Copyright &copy; ' . date('Y') . ' PT Kirana Megatara Tbk. All Rights Reserved.
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </body>
        </html>';

        return $message;
    }
	/*====================================================================*/
		
}