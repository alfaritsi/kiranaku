<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  	: Travel
 * author     		: Airiza Yuddha (7849)
 * @contributor  	:
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Sync extends MX_Controller
{

	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data['module'] = "Travel";
		$this->data['user'] = $this->general->get_data_user();
		// $this->load->library('less');
		$this->load->model('dsync');
		$this->load->model('dspd');
		$this->load->library('lspd');
		// $this->load->model('dcutiijin');
	}

	public function rfc($param, $dataid, $finalappaction = NULL)
	{

		$this->load->library('sap');
		$datetime 		= date("Y-m-d H:i:s");
		$gen 			= $this->generate;
		$this->general->connectDbPortal();
		$filter 		= $this->input->get();

		$koneksi 		= parse_ini_file(FILE_KONEKSI_SAP, true);
		$data_koneksi 	= $koneksi['ESS']; //710 HRIS

		$constr = array(
			"logindata" 	=> array(
				"ASHOST" 	=> $data_koneksi['ASHOST'],
				"SYSNR" 	=> $data_koneksi['SYSNR'],
				"CLIENT" 	=> $data_koneksi['CLIENT'],
				"USER" 		=> $data_koneksi['USER'],
				"PASSWD" 	=> $data_koneksi['PASSWD']
			),
			"show_errors" 	=> $data_koneksi['DEBUG'],
			"debug" 		=> $data_koneksi['DEBUG']
		);

		$sap = new saprfc($constr);
		if ($filter != null) {

			$id 			= isset($filter['id']) 				? $filter['id'] 			: "";
			$nik 			= isset($filter['nik']) 			? $filter['nik'] 			: "";
			$lokasi 		= isset($filter['lokasi']) 			? $filter['lokasi'] 		: "";
			$tanggal_awal 	= isset($filter['tanggal_awal']) 	? $filter['tanggal_awal'] 	: "";
			$tanggal_akhir 	= isset($filter['tanggal_akhir']) 	? $filter['tanggal_akhir'] 	: "";
			$type_output 	= isset($filter['type']) 			? $filter['type'] 			: "";
			$approval_level	= isset($filter['approval_level']) 	? $filter['approval_level'] : "";
			$notrip			= isset($filter['notrip']) 			? $filter['notrip'] 		: "";

			$ho 			= '';
			if ($lokasi == 'ho')
				$ho = 'X';

			if (isset($nik)) {
				$check_nik 	= $this->dsync->get_karyawan(array('nik' => $nik));

				$checkapl 		= $sap->callFunction(
					"Z_RFC_CHECKTRIPLOCK",
					array(
						array("IMPORT", "P_PERNR", $nik),
						array("TABLE", "T_RETURN", array()),
					)
				);
				if ($sap->getStatus() == SAPRFC_OK) {

					if ($checkapl['T_RETURN'][1]['TYPE'] != 'E') {    //val:S(jika sukses)
						$notrip = $checkapl['T_HASIL'][1]['REINR'] != "" ? $checkapl['T_HASIL'][1]['REINR'] : "";

						//log rfc
						$status       = preg_replace('/\/n$/', '', $sap->GetStatusText());
						$data_row_log = array(
							'app'           => 'DATA RFC SPD PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_CHECKTRIPLOCK',
							'log_code'      => '',
							'log_status'    => 'Berhasil',
							'log_desc'      => $status,
							'executed_by'   => 0,
							'executed_date' => $datetime
						);
						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
					} else {        //jika gagal

						$data_row = array(
							'app'           => 'DATA RFC SPD PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_CHECKTRIPLOCK',
							'log_code'      => $checkapl['T_RETURN'][1]['TYPE'],
							'log_status'    => 'Gagal',
							'log_desc'      => $checkapl['T_RETURN'][1]['MESSAGE'],
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
						$this->dgeneral->insert('tbl_log_rfc', $data_row);

						$msg    = "Aplikasi pada SAP ada yang menggunakan";
						$sts    = "NotOK";
						$return = array('sts' => $sts, 'msg' => $msg);
						echo json_encode($return);
						exit();
					}
				}
				if (empty($check_nik)) {
					$return = array('sts' => 'NotOK', 'msg' => 'NIK tidak ditemukan');
					echo json_encode($return);
					return;
				}
			}
		} else {
			$nik 			= NULL;
			$lokasi 		= NULL;
			$tanggal_awal 	= NULL;
			$tanggal_akhir 	= NULL;
			$type_output 	= NULL;
			$approval_level	= NULL;
			$notrip			= NULL;
			$id 			= $dataid;
		}
		// var_dump($id);
		if ($finalappaction == "byfinalapprove") {
			$approval_level_filter = "byfinalapprove";
		} else {
			$approval_level_filter = null;
		}

		switch ($param) {
			case "sync_travel_main":
				// =======================================

				$this->dgeneral->begin_transaction();

				$data_detail 	= $this->dsync->push_data_spd($id, $nik, $tanggal_awal, $approval_level_filter);

				foreach ($data_detail as $dt) {
					$pernr = $dt->PERNR;
					if ($pernr) {
						$checkapl 		= $sap->callFunction(
							"Z_RFC_CHECKTRIPLOCK",
							array(
								array("IMPORT", "P_PERNR", $pernr),
								array("TABLE", "T_RETURN", array()),
							)
						);
						if ($sap->getStatus() == SAPRFC_OK) {

							if ($checkapl['T_RETURN'][1]['TYPE'] != 'E') {    //val:S(jika sukses)

								//log rfc
								$status       = preg_replace('/\/n$/', '', $sap->GetStatusText());
								$data_row_log = array(
									'app'           => 'DATA RFC SPD PORTAL TO SAP',
									'rfc_name'      => 'Z_RFC_CHECKTRIPLOCK',
									'log_code'      => '',
									'log_status'    => 'Berhasil',
									'log_desc'      => $status,
									'executed_by'   => 0,
									'executed_date' => $datetime
								);
								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
							} else {        //jika gagal

								$data_row = array(
									'app'           => 'DATA RFC SPD PORTAL TO SAP',
									'rfc_name'      => 'Z_RFC_CHECKTRIPLOCK',
									'log_code'      => $checkapl['T_RETURN'][1]['TYPE'],
									'log_status'    => 'Gagal',
									'log_desc'      => $checkapl['T_RETURN'][1]['MESSAGE'],
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
								$this->dgeneral->insert('tbl_log_rfc', $data_row);

								$msg    = "Aplikasi TRIP pada SAP ada yang sedang menggunakan";
								$sts    = "NotOK";
								$return = array('sts' => $sts, 'msg' => $msg);
								echo json_encode($return);
								exit();
							}
						}
					}
					break;
				}
				if (empty($data_detail)) {
					$msg    = "Tidak Ada Data yang diproses";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				$id_header		= $id;
				$data_dpayment  = $this->dsync->push_data_downpayment($id, $nik, NULL, $approval_level);

				$result 		= $sap->callFunction(
					"Z_RFC_CRTTRVL_REQUEST",
					array(
						array("TABLE", "T_DTLDES", $data_detail),
						array("TABLE", "T_DTLADV", $data_dpayment),
						// array("TABLE", "T_DTLADV", $data_dpayment),
						array("TABLE", "T_HASIL", array()),
						array("TABLE", "T_RETURN", array()),
					)
				);
				if ($sap->getStatus() == SAPRFC_OK) {

					if ($result['T_RETURN'][1]['TYPE'] != 'E') {    //val:S(jika sukses)
						$notrip = $result['T_HASIL'][1]['REINR'] != "" ? $result['T_HASIL'][1]['REINR'] : "";

						$string = "
                            update
                            tbl_travel_header
                            set 
                            	no_trip='" . $notrip . "' ,
                           	 	tanggal_migrasi='" . $datetime . "',
                           	 	sap_synced=1,
                           	 	approval_status='" . TR_STATUS_DISETUJUI . "',
                           	 	approval_level=4 
                            from tbl_travel_header
                            where id_travel_header='" . $id_header . "' and na='n'
                        ";
						$query  = $this->db->query($string);

						//log rfc
						$status       = preg_replace('/\/n$/', '', $sap->GetStatusText());
						$data_row_log = array(
							'app'           => 'DATA RFC SPD PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_CRTTRVL_REQUEST',
							'log_code'      => '',
							'log_status'    => 'Berhasil',
							'log_desc'      => $status,
							'executed_by'   => 0,
							'executed_date' => $datetime
						);
						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
					} else {        //jika gagal

						$data_row = array(
							'app'           => 'DATA RFC SPD PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_CRTTRVL_REQUEST',
							'log_code'      => $result['T_RETURN'][1]['TYPE'],
							'log_status'    => 'Gagal',
							'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
						$this->dgeneral->insert('tbl_log_rfc', $data_row);
					}
				}

				//================================SAVE ALL================================//
				if ($result['T_RETURN'][1]['TYPE'] != 'E') {
					$this->dgeneral->commit_transaction();
					$msg = "Pembuatan Kode Trip SAP berhasil.";
					$sts = "OK";
				} else {
					$this->dgeneral->rollback_transaction();
					$msg = $result['T_RETURN'][1]['MESSAGE'];
					$sts = "NotOK";
				}

				$this->general->closeDb();
				$sap->logoff();
				$return = array('sts' => $sts, 'msg' => $msg);

				break;

			case "sync_travel_cancel":
				// =======================================

				$this->dgeneral->begin_transaction();

				$data_detail 	= $this->dsync->push_data_cancel($id, $nik, $tanggal_awal, $approval_level);
				if (!isset($data_detail)) {
					$msg    = "Tidak Ada Data yang diproses";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				$nik_karyawan	= $data_detail->EMPLOYEENUMBER;
				$trip_number	= $data_detail->TRIPNUMBER;
				$id_header		= $id;

				if ($nik_karyawan != "") {
					$pernr = $nik_karyawan;
					if ($pernr) {
						$checkapl 		= $sap->callFunction(
							"Z_RFC_CHECKTRIPLOCK",
							array(
								array("IMPORT", "P_PERNR", $pernr),
								array("TABLE", "T_RETURN", array()),
							)
						);
						if ($sap->getStatus() == SAPRFC_OK) {

							if ($checkapl['T_RETURN'][1]['TYPE'] != 'E') {    //val:S(jika sukses)

								//log rfc
								$status       = preg_replace('/\/n$/', '', $sap->GetStatusText());
								$data_row_log = array(
									'app'           => 'DATA RFC SPD PORTAL TO SAP',
									'rfc_name'      => 'Z_RFC_CHECKTRIPLOCK',
									'log_code'      => '',
									'log_status'    => 'Berhasil',
									'log_desc'      => $status,
									'executed_by'   => 0,
									'executed_date' => $datetime
								);
								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
							} else {        //jika gagal

								$data_row = array(
									'app'           => 'DATA RFC SPD PORTAL TO SAP',
									'rfc_name'      => 'Z_RFC_CHECKTRIPLOCK',
									'log_code'      => $checkapl['T_RETURN'][1]['TYPE'],
									'log_status'    => 'Gagal',
									'log_desc'      => $checkapl['T_RETURN'][1]['MESSAGE'],
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
								$this->dgeneral->insert('tbl_log_rfc', $data_row);

								$msg    = "Aplikasi TRIP pada SAP ada yang sedang menggunakan";
								$sts    = "NotOK";
								$return = array('sts' => $sts, 'msg' => $msg);
								echo json_encode($return);
								exit();
							}
						}
					}
				}

				$result = $sap->callFunction(
					"BAPI_TRIP_CANCEL",
					array(
						array("IMPORT", "EMPLOYEENUMBER", $nik_karyawan),
						array("IMPORT", "TRIPNUMBER", $trip_number),
						array("EXPORT", "RETURN", array()),
						// array("TABLE", "T_DTLADV", $data_dpayment),
						// // array("TABLE", "T_DTLADV", $data_dpayment),
						// array("TABLE", "T_HASIL", array()),
						// array("TABLE", "RETURN", array()),
					)
				);

				if ($sap->getStatus() == SAPRFC_OK) {

					if ($result['RETURN']['TYPE'] != 'E') {    //val:S(jika sukses)
						$string = "
                            update
                            tbl_travel_cancel
                            set 
                            	tanggal_migrasi='" . $datetime . "',
                           	 	sap_synced=1,
                           	 	approval_status='" . TR_STATUS_SELESAI . "',
                           	 	approval_level=99
                            from tbl_travel_cancel
                            where id_travel_header='" . $id_header . "' and na='n'
                        ";
						$query  = $this->db->query($string);

						$string2 = "
                            update
                            tbl_travel_header
                            set 
                            	approval_status='" . TR_STATUS_DIBATALKAN . "'
                           	from tbl_travel_header
                            where id_travel_header='" . $id_header . "' and na='n'
                        ";
						$query2  = $this->db->query($string2);
						// input log
						$this->lspd->travel_log(
							array(
								'id_travel_header' 	=> $id_header,
								'action' 			=> 'pembatalan',
								'remark' 			=> 'sync',
								'comment' 			=> 'By HR',
								'actor' 			=> base64_decode($this->session->userdata("-nik-")),
							)
						);
						//log rfc
						$status       = preg_replace('/\/n$/', '', $sap->GetStatusText());
						$data_row_log = array(
							'app'           => 'DATA RFC SPD CANCEL PORTAL TO SAP',
							'rfc_name'      => 'BAPI_TRIP_CANCEL',
							'log_code'      => '',
							'log_status'    => 'Berhasil',
							'log_desc'      => $status,
							'executed_by'   => 0,
							'executed_date' => $datetime
						);
						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
					} else {        //jika gagal

						$data_row = array(
							'app'           => 'DATA RFC SPD CANCEL PORTAL TO SAP',
							'rfc_name'      => 'BAPI_TRIP_CANCEL',
							'log_code'      => $result['RETURN']['TYPE'],
							'log_status'    => 'Gagal',
							'log_desc'      => $result['RETURN']['MESSAGE'],
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
						$this->dgeneral->insert('tbl_log_rfc', $data_row);
					}
				}

				//================================SAVE ALL================================//
				if ($result['RETURN']['TYPE'] != 'E') {
					$this->dgeneral->commit_transaction();
					$msg = "Pembatalan Trip SAP berhasil.";
					$sts = "OK";
				} else {
					$this->dgeneral->rollback_transaction();
					// $msg = $code . ' ' . $result['T_RETURN'][1]['MESSAGE'];
					$msg = $result['RETURN']['MESSAGE'];
					$sts = "NotOK";
				}

				$this->general->closeDb();
				$sap->logoff();
				$return = array('sts' => $sts, 'msg' => $msg);
				break;

			case "sync_travel_declare":
				// =======================================
				$type = array();
				$message = array();

				$this->dgeneral->begin_transaction();

				$data_detail 	= $this->dsync->push_data_declare($id, $nik, $tanggal_awal, $approval_level);

				if (empty($data_detail)) {
					$msg    = "Tidak Ada Data yang diproses";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				foreach ($data_detail as $dt) {
					$pernr = $dt->PERNR;
					if ($pernr) {
						$checkapl 		= $sap->callFunction(
							"Z_RFC_CHECKTRIPLOCK",
							array(
								array("IMPORT", "P_PERNR", $pernr),
								array("TABLE", "T_RETURN", array()),
							)
						);
						if ($sap->getStatus() == SAPRFC_OK) {

							if ($checkapl['T_RETURN'][1]['TYPE'] != 'E') {    //val:S(jika sukses)

								//log rfc
								$status       = preg_replace('/\/n$/', '', $sap->GetStatusText());
								$data_row_log = array(
									'app'           => 'DATA RFC SPD PORTAL TO SAP',
									'rfc_name'      => 'Z_RFC_CHECKTRIPLOCK',
									'log_code'      => '',
									'log_status'    => 'Berhasil',
									'log_desc'      => $status,
									'executed_by'   => 0,
									'executed_date' => $datetime
								);
								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
							} else {        //jika gagal

								$data_row = array(
									'app'           => 'DATA RFC SPD PORTAL TO SAP',
									'rfc_name'      => 'Z_RFC_CHECKTRIPLOCK',
									'log_code'      => $checkapl['T_RETURN'][1]['TYPE'],
									'log_status'    => 'Gagal',
									'log_desc'      => $checkapl['T_RETURN'][1]['MESSAGE'],
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
								$this->dgeneral->insert('tbl_log_rfc', $data_row);

								$msg    = "Aplikasi TRIP pada SAP ada yang sedang menggunakan";
								$sts    = "NotOK";
								$return = array('sts' => $sts, 'msg' => $msg);
								echo json_encode($return);
								exit();
							}
						}
					}
					$sap->logoff();
					break;
				}

				$data_cuti = $this->dspd->get_cuti_pengganti(
					array(
						"connect" => FALSE,
						'id_travel_header' => $id,
						"encrypt" => array("id")
					)
				);

				$sap = new saprfc($constr);

				$t_cuti = array();
				if (!empty($data_cuti)) {
					$t_cuti[] = array(
						"PERNR" => $data_cuti[0]->nik,
						"BEGDA" => date_format(date_create($data_cuti[0]->tanggal_cuti), "Ymd"),
						"JUMLAH" => count($data_cuti)
					);
				}

				$id_header		= $id;

				$param_rfc = array(
					array("TABLE", "T_RCEIP", $data_detail),
					array("TABLE", "T_CUTI", $t_cuti),
					// array("IMPORT", "TRIPNUMBER", $trip_number),
					// array("EXPORT", "RETURN", array()),
					// array("TABLE", "T_DTLADV", $data_dpayment),
					// // array("TABLE", "T_DTLADV", $data_dpayment),
					// array("TABLE", "T_HASIL", array()),
					array("TABLE", "T_RETURN", array()),
				);
				$result = $sap->callFunction("Z_RFC_CRTTRVL_EXPENSE", $param_rfc);
				$iserror = false;
				//cek kalo ada error
				if (!empty($result["T_RETURN"])) {
					foreach ($result["T_RETURN"] as $return) {
						if ("E" == $return['TYPE']) {
							$iserror = true;
							break;
						}
					}
				}

				if (
					$sap->getStatus() == SAPRFC_OK
					&& empty($result["T_RETURN"])
					&& !$iserror
				) {    //val:S(jika sukses)
					$string = "
                                update
                                tbl_travel_deklarasi_header
                                set 
                                	tanggal_migrasi='" . $datetime . "',
                               	 	sap_synced=1,
                               	 	approval_status='" . TR_STATUS_SELESAI . "',
                   	 				approval_level='" . TR_LEVEL_FINISH . "'
                                from tbl_travel_deklarasi_header
                                where id_travel_header='" . $id_header . "' and na='n'
                            ";
					// approval_status='".TR_STATUS_SIAP."',
					// approval_level=99
					$query  = $this->db->query($string);

					$string2 = "
                                update
                                tbl_travel_header
                                set 
                                	approval_status='" . TR_STATUS_SELESAI . "'
                               	from tbl_travel_header
                                where id_travel_header='" . $id_header . "' and na='n'
                            ";
					$query2  = $this->db->query($string2);

					//log rfc
					$status       = preg_replace('/\/n$/', '', $sap->GetStatusText());
					$data_row_log = array(
						'app'           => 'DATA RFC SPD DEKLARASI PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_CRTTRVL_EXPENSE',
						'log_code'      => '',
						'log_status'    => 'Berhasil',
						'log_desc'      => $status,
						'executed_by'   => 0,
						'executed_date' => $datetime
					);
					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				} else {        //jika gagal
					$msg_fail = array();
					$type_fail = array();
					if ($result["T_RETURN"]) {
						foreach ($result["T_RETURN"] as $return) {
							$type[]    = $return['TYPE'];
							$message[] = $return['MESSAGE'];
							$type_fail[] = $return['TYPE'];
							$msg_fail[] = $return['MESSAGE'];
						}
					} else {
						$type[]    = 'E';
						$message[] = "Error tanpa message"; //$result;
						$type_fail[] = 'E';
						$msg_fail[] = "Error tanpa message"; //$result;
					}
					$data_row = array(
						'app'           => 'DATA RFC SPD DEKLARASI PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_CRTTRVL_EXPENSE',
						'log_code'      => implode(" , ", $type_fail),
						'log_status'    => 'Gagal',
						'log_desc'      => implode(" , ", $msg_fail),
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);
					$this->dgeneral->insert('tbl_log_rfc', $data_row);
				}

				$sap->logoff();
				if (in_array('E', $type) === true) {
					$sts = "NotOK";
					$msg = implode(", ", $message);
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}
				//================================SAVE ALL================================//
				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				} else {
					$this->dgeneral->commit_transaction();
					$msg = "Deklarasi Trip SAP berhasil.";
					$sts = "OK";
				}

				$this->general->closeDb();

				$return = array('sts' => $sts, 'msg' => $msg);
				break;

			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				break;
		}

		return $return;
	}

	public function data()
	{
		$this->general->connectDbPortal();

		$filter = $this->input->post();

		$tanggal_akhir 	= date('Y-m-d', strtotime('+1 month'));
		$tanggal_awal 	= date('Y-m-d');

		if (isset($filter['tanggal_awal']))
			$tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

		if (isset($filter['tanggal_akhir']))
			$tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');


		$this->data['module'] = "Travel - Pengajuan SPD";
		$this->data['title'] = "Sinkronisasi Data Perjalanan Dinas";
		$this->data['countries'] = $this->dspd->get_countries();
		$this->data['jenis_aktifitas'] = $this->dspd->get_jenis_aktifitas();
		$this->data['tanggal_travels'] = $this->lspd->get_all_travel_dates(
			array(
				'user' => $this->data['user']
			)
		);

		$list_nik_sync 	=  $this->dsync->get_data_sync_oto(array(
			'nik' => $this->data['user']->nik
		));

		$oto_nik 		= [];
		foreach ($list_nik_sync as $dt) {
			if (!in_array($dt->nik, $oto_nik)) {
				array_push($oto_nik, $dt->nik);
			}
		}
		$oto_nik = $oto_nik == null ? array(0) : $oto_nik;
		$listSpd = $this->dsync->get_travel_header(array(
			// 'nik' => $this->data['user']->nik
			'nik' 				=> $oto_nik,
			'approval_level' 	=> 99,
			'isno_trip'			=> "kosong"
		));

		$listSpd_cancel = $this->dsync->get_travel_header_cancel(array(
			'nik' 				=> $oto_nik,
			'approval_status' 	=> 1,
			'approval_level' 	=> 99,
			'isno_trip'			=> "exist"
		));

		$listSpd_declare = $this->dsync->get_travel_header_declare(array(
			'nik'  				=> $oto_nik,
			'approval_status' 	=> 1,
			'approval_level' 	=> 99,
			'isno_trip'			=> "exist",
		));

		$listSpd_history = $this->dsync->get_travel_header_history(array(
			'nik' 				=> $oto_nik,
			'tanggal_awal' 		=> $tanggal_awal,
			'tanggal_akhir' 	=> $tanggal_akhir,
			// 'approval_status' 	=> 1,
			// 'approval_level' 	=> 99,
		));


		/** Reproses list array SPD */
		$listSpd 		= $this->lspd->proses_spd_list($listSpd);
		$listSpd_cancel = $this->lspd->proses_spd_list($listSpd_cancel);
		$listSpd_declare = $this->lspd->proses_spd_list($listSpd_declare);
		$listSpd_history = $this->lspd->proses_spd_list($listSpd_history, "history");
		$listSpd 		= $this->general->generate_encrypt_json($listSpd, array("id_travel_header"));
		$listSpd_cancel = $this->general->generate_encrypt_json($listSpd_cancel, array("id_travel_header"));
		$listSpd_declare = $this->general->generate_encrypt_json($listSpd_declare, array("id_travel_header"));
		$listSpd_history = $this->general->generate_encrypt_json($listSpd_history, array("id_travel_header"));
		$this->data['list'] 		= $listSpd;
		$this->data['list_cancel'] 	= $listSpd_cancel;
		$this->data['list_declare'] = $listSpd_declare;
		$this->data['list_history'] = $listSpd_history;
		$this->data['approval'] = $this->dspd->get_approval();

		$this->data['modal_detail'] = $this->lspd->load_spd_detail();
		$this->data['modal_tujuan'] = $this->lspd->load_spd_tujuan();
		$this->data['modal_history'] = $this->lspd->load_spd_history();
		$this->data['tanggal_awal'] = $tanggal_awal;
		$this->data['tanggal_akhir'] = $tanggal_akhir;

		$this->load->view('sync/spd_sync', $this->data);
	}

	public function save($param)
	{
		$data = $_POST;

		switch ($param) {
			case 'approve_pengajuan':
				$return = $this->save_sync_pengajuan($param, $data);
				break;
			case 'approve_cancel':
				$return = $this->save_sync_cancel($param, $data);
				break;
			case 'approve_declare':
				$return = $this->save_sync_declare($param, $data);
				break;
				// case 'batal':
				//     $return = $this->save_pembatalan($param, $data);
				//     break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				break;
		}

		echo json_encode($return);
	}

	public function get($param = null)
	{
		switch ($param) {
			case 'notrip':
				$id = isset($_POST['idheader']) ?
					$this->generate->kirana_decrypt($_POST['idheader']) : "x";
				// get no trip
				$listSpd = $this->dsync->get_travel_header(array(
					'id' 				=> $id,
					'single' 			=> "single",

				));

				$result['data'] = $listSpd->no_trip;
				$result['sts'] = 'OK';
				$result['msg'] = '';
				break;
			default:
				$result = array(
					'sts' => 'NotOK',
					'msg' => 'Data tidak ditemukan',
				);
				break;
		}

		echo json_encode($result);
	}

	public function edit($param = null)
	{
		$datetime = date("Y-m-d H:i:s");
		switch ($param) {
			case 'notrip':

				$this->general->connectDbPortal();
				$this->dgeneral->begin_transaction();
				$id 		= isset($_POST['id']) ?
					$this->generate->kirana_decrypt($_POST['id']) : "x";
				$notrip 	= isset($_POST['nomor']) ?
					$_POST['nomor'] : "x";
				$notrip_ex 	= isset($_POST['nomor_ex']) ?
					$_POST['nomor_ex'] : "x";
				// check data no trip if exist
				$checkdata 	=  $this->dsync->get_travel_header();
				$list_notrip = [];
				foreach ($checkdata as $dt) {
					if (!in_array($dt->no_trip, $list_notrip)) {
						array_push($list_notrip, $dt->no_trip);
					}
				}
				$list_notrip = $list_notrip == null ? array(0) : $list_notrip;
				if (in_array($notrip, $list_notrip)) {
					$msg    = "Nomor trip telah digunakan";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				// set no trip
				$data_row =
					array(
						'no_trip' 		=> $notrip,
						'login_edit'    => base64_decode($this->session->userdata("-nik-")),
						'tanggal_edit'  => $datetime
					);

				$this->dgeneral->update("tbl_travel_header", $data_row, array(
					array(
						'kolom' => 'id_travel_header',
						'value' => $id
					)
				));

				// input log
				$this->lspd->travel_log(
					array(
						'id_travel_header' 	=> $id,
						'action' 			=> 'Revisi no trip',
						'remark' 			=> 'Revisi no trip ' . $notrip_ex . ' menjadi ' . $notrip,
						'comment' 			=> '',
						'actor' 			=> base64_decode($this->session->userdata("-nik-")),
					)
				);

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

				break;
			default:
				$result = array(
					'sts' => 'NotOK',
					'msg' => 'Data tidak ditemukan',
				);
				break;
		}

		echo json_encode($return);
	}

	public function save_sync_pengajuan($param, $data, $appbyfinal = NULL)
	{
		if (isset($data['approvals'])) {
			if (!is_array($data['approvals']))
				$approvals = json_decode($data['approvals']);
			else {
				$approvals = json_decode(json_encode($data['approvals'], false));
			}
			if (count($approvals) > 0 && $appbyfinal != '1') {
				foreach ($approvals as $approval) {
					if (isset($approval->id)) {
						$id 	= $this->generate->kirana_decrypt($approval->id);
						$data 	= $this->rfc("sync_travel_main", $id, NULL);
						$msg 	= $data['msg'];
						$sts 	= $data['sts'];
					}
				}
			} elseif ($appbyfinal == '1') {
				foreach ($approvals as $approval) {
					$id 	= $this->generate->kirana_decrypt($approval);
					$data 	= $this->rfc("sync_travel_main", $id, "byfinalapprove");
					$msg 	= $data['msg'];
					$sts 	= $data['sts'];
				}
			} else {
				$msg = "Tidak ada data yang disimpan";
				$sts = "OK";
			}
		} else {
			$msg = "Tidak ada data yang disimpan";
			$sts = "OK";
		}
		$return = array('sts' => $sts, 'msg' => $msg);

		return $return;
	}

	public function save_sync_cancel($param, $data, $appbyfinal = NULL)
	{
		if (isset($data['approvals'])) {
			if (!is_array($data['approvals']))
				$approvals = json_decode($data['approvals']);
			else {
				$approvals = json_decode(json_encode($data['approvals'], false));
			}
			if (count($approvals) > 0 && $appbyfinal != '1') {
				foreach ($approvals as $approval) {
					if (isset($approval->id)) {
						$id 	= $this->generate->kirana_decrypt($approval->id);
						$data 	= $this->rfc("sync_travel_cancel", $id, NULL);
						$msg 	= $data['msg'];
						$sts 	= $data['sts'];
					}
				}
			} elseif ($appbyfinal == '1') {
				foreach ($approvals as $approval) {
					$id 	= $this->generate->kirana_decrypt($approval);
					$data 	= $this->rfc("sync_travel_cancel", $id, "byfinalapprove");

					$msg 	= $data['msg'];
					$sts 	= $data['sts'];
				}
			} else {
				$msg = "Tidak ada data yang disimpan1";
				$sts = "OK";
			}
		} else {
			$msg = "Tidak ada data yang disimpan2";
			$sts = "OK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);

		return $return;
	}

	public function save_sync_declare($param, $data, $appbyfinal = NULL)
	{
		if (isset($data['approvals'])) {
			if (!is_array($data['approvals']))
				$approvals = json_decode($data['approvals']);
			else {
				$approvals = json_decode(json_encode($data['approvals'], false));
			}

			if (count($approvals) > 0 && $appbyfinal != '1') {
				foreach ($approvals as $approval) {
					if (isset($approval->id)) {
						$id 	= $this->generate->kirana_decrypt($approval->id);
						$data 	= $this->rfc("sync_travel_declare", $id, NULL);
						$msg 	= $data['msg'];
						$sts 	= $data['sts'];
					}
				}
			} elseif ($appbyfinal == '1') {
				foreach ($approvals as $approval) {
					$id 	= $this->generate->kirana_decrypt($approval);	//PSU1-07-2020-0032
					$data 	= $this->rfc("sync_travel_declare", $id, "byfinalapprove");

					$msg 	= $data['msg'];
					$sts 	= $data['sts'];
				}
			} else {
				$msg = "Tidak ada data yang disimpan";
				$sts = "OK";
			}
		} else {
			$msg = "Tidak ada data yang disimpan";
			$sts = "OK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);

		return $return;
	}

	public function excel($param = NULL)
	{

		$this->load->library('PHPExcel');
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		if (PHP_SAPI == 'cli')
			die('This example should only be run from a Web Browser');
		$objPHPExcel = new PHPExcel();

		// get otorisasi
		$list_nik_sync 	=  $this->dsync->get_data_sync_oto(array(
			'nik' => $this->data['user']->nik
		));

		$oto_nik 		= [];
		foreach ($list_nik_sync as $dt) {
			if (!in_array($dt->nik, $oto_nik)) {
				array_push($oto_nik, $dt->nik);
			}
		}

		switch ($param) {
			case 'sync_pengajuan':
				//header ========================
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'NIK');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Tgl Berangkat');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Jam Berangkat');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Tgl Kembali');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Jam Kembali');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Tujuan');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Negara');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Aktifitas');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Alasan');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Kode ');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Jumlah');
				//content

				$oto_nik = $oto_nik == null ? array(0) : $oto_nik;
				$listSpd = $this->dsync->get_travel_detail(array(
					// 'nik' => $this->data['user']->nik
					'nik' 				=> $oto_nik,
					'approval_level' 	=> 99,
					'isno_trip'			=> "kosong"
				));
				$listSpd 		= $this->lspd->proses_spd_list($listSpd);
				$list_data 		= $listSpd;
				$baris = 1;
				foreach ($list_data as $data) {
					// Get Country
					$country = $this->dsync->get_countries(array('country_code' => $data->header_country, 'single' => true));

					// Get tujuan
					if ($data->tujuan !== 'lain') {
						$tujuan = $this->dspd->get_travel_tujuan(
							array(
								'company_code' => $data->tujuan,
								'single' => true
							)
						);
						if (isset($tujuan)) {
							if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
								$tujuanArray = explode('-', $tujuan->personal_subarea_text);
								$tujuan = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
							} else {
								$tujuan = $tujuan->personal_area_text . ', ' . $tujuan->kota;
							}
						}
					} else {
						$tujuan = $data->tujuan_lengkap;
					}

					$baris++;
					// $objPHPExcel->getActiveSheet()->getRowDimension($baris)->setRowHeight(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);


					$objPHPExcel->getActiveSheet()->setCellValue("A" . $baris, $data->header_nik);
					$objPHPExcel->getActiveSheet()->setCellValue("B" . $baris, date_create($data->header_start_date)->format('d.m.Y'));
					$objPHPExcel->getActiveSheet()->setCellValue("C" . $baris, date_create($data->header_start_time)->format('H:i'));
					$objPHPExcel->getActiveSheet()->setCellValue("D" . $baris, date_create($data->header_end_date)->format('d.m.Y'));
					$objPHPExcel->getActiveSheet()->setCellValue("E" . $baris, date_create($data->header_end_time)->format('H:i'));
					$objPHPExcel->getActiveSheet()->setCellValue("F" . $baris, $tujuan);
					$objPHPExcel->getActiveSheet()->setCellValue("G" . $baris, $country->country_name);
					$objPHPExcel->getActiveSheet()->setCellValue("H" . $baris, $data->activity_label);
					$objPHPExcel->getActiveSheet()->setCellValue("I" . $baris, $data->keperluan);
					$objPHPExcel->getActiveSheet()->setCellValue("J" . $baris, $data->kode_expense);
					$objPHPExcel->getActiveSheet()->setCellValue("K" . $baris, $data->jumlah);

					$lebar = 0;
				}


				// header ====================================================================================
				// set text bold
				$from 	= "A1"; // or any value
				$to 	= "A10000"; // or any value	
				// $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );

				// set header border table
				$styleArray = array(
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				);
				$from_header 	= "A1"; // or any value
				$to_header 		= "K1"; // or any value	
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->applyFromArray($styleArray);
				// set align left
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				// Set collor name header table
				$objPHPExcel->getActiveSheet()->getStyle("A1:$to_header")->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => '#18CA06')
						),
						'font'  => array(
							'bold'  => true,
							'color' => array('rgb' => '#FFFFFF'),
							'size'  => 13,
							// 'name'  => 'Verdana'
						)
					)
				);
				// header ====================================================================================
				$objPHPExcel->setActiveSheetIndex(0);
				$this->cetak_excel_prop("pengajuan");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save('php://output');
				break;
			case 'sync_deklarasi':
				//header ========================
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'No. Trip');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'NIK');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Nama');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Tgl Berangkat');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Jam Berangkat');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Tgl Kembali');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Jam Kembali');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Alasan');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Tujuan');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Negara');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Area');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L1', 'Group');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M1', 'Aktifitas');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N1', 'Service Provider');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O1', 'Kode ');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P1', 'Jumlah');
				//content

				$oto_nik = $oto_nik == null ? array(0) : $oto_nik;
				$listSpd = $this->dsync->get_travel_detail_declare(array(
					// 'nik' => $this->data['user']->nik
					'nik' 				=> $oto_nik,
					'approval_level' 	=> 99,
					'isno_trip'			=> "exist"
				));
				$listSpd 		= $this->lspd->proses_spd_list($listSpd);

				$list_data 		= $listSpd;
				$baris = 1;
				foreach ($list_data as $data) {
					// Get Country
					$country = $this->dsync->get_countries(array('country_code' => $data->header_country, 'single' => true));

					// Get tujuan
					if ($data->tujuan !== 'lain') {
						$tujuan = $this->dspd->get_travel_tujuan(
							array(
								'company_code' 	=> $data->tujuan,
								'single' 		=> true
							)
						);
						if (isset($tujuan)) {
							if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
								$tujuanArray = explode('-', $tujuan->personal_subarea_text);
								$tujuan = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
							} else {
								$tujuan = $tujuan->personal_area_text . ', ' . $tujuan->kota;
							}
						}
					} else {
						$tujuan = $data->tujuan_lengkap;
					}

					$baris++;
					// $objPHPExcel->getActiveSheet()->getRowDimension($baris)->setRowHeight(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
					$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);

					$objPHPExcel->getActiveSheet()->setCellValue("A" . $baris, $data->no_trip);
					$objPHPExcel->getActiveSheet()->setCellValue("B" . $baris, $data->header_nik);
					$objPHPExcel->getActiveSheet()->setCellValue("C" . $baris, $data->nama_karyawan);
					$objPHPExcel->getActiveSheet()->setCellValue("D" . $baris, date_create($data->header_start_date)->format('d.m.Y'));
					$objPHPExcel->getActiveSheet()->setCellValue("E" . $baris, date_create($data->header_start_time)->format('H:i'));
					$objPHPExcel->getActiveSheet()->setCellValue("F" . $baris, date_create($data->header_end_date)->format('d.m.Y'));
					$objPHPExcel->getActiveSheet()->setCellValue("G" . $baris, date_create($data->header_end_time)->format('H:i'));
					$objPHPExcel->getActiveSheet()->setCellValue("H" . $baris, $data->keperluan);
					$objPHPExcel->getActiveSheet()->setCellValue("I" . $baris, $tujuan);
					$objPHPExcel->getActiveSheet()->setCellValue("J" . $baris, $country->country_name);
					$objPHPExcel->getActiveSheet()->setCellValue("K" . $baris, $data->tipe_travel);

					$activity = $data->activity == 'T' ? "T" : "P";
					$provider = $activity == 'T' ? "TR" : "";

					$objPHPExcel->getActiveSheet()->setCellValue("L" . $baris, $data->employee_group);
					$objPHPExcel->getActiveSheet()->setCellValue("M" . $baris, $activity);
					$objPHPExcel->getActiveSheet()->setCellValue("N" . $baris, $provider);

					$objPHPExcel->getActiveSheet()->setCellValue("O" . $baris, $data->kode_expense);
					$objPHPExcel->getActiveSheet()->setCellValue("P" . $baris, $data->jumlah);

					$lebar = 0;
				}

				// header ====================================================================================
				// set text bold
				$from 	= "A1"; // or any value
				$to 	= "A10000"; // or any value	
				// $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );

				// set header border table
				$styleArray = array(
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				);
				$from_header 	= "A1"; // or any value
				$to_header 		= "P1"; // or any value	
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->applyFromArray($styleArray);
				// set align left
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				// Set collor name header table
				$objPHPExcel->getActiveSheet()->getStyle("A1:$to_header")->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => '#18CA06')
						),
						'font'  => array(
							'bold'  => true,
							'color' => array('rgb' => '#FFFFFF'),
							'size'  => 13,
							// 'name'  => 'Verdana'
						)
					)
				);
				// header ====================================================================================
				$objPHPExcel->setActiveSheetIndex(0);
				$this->cetak_excel_prop("deklarasi");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save('php://output');
				break;
			case 'sync_pembatalan':
				//header ========================
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'NIK');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'Tgl Berangkat');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'Jam Berangkat');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Tgl Kembali');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'Jam Kembali');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F1', 'Tujuan');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G1', 'Negara');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H1', 'Aktifitas');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I1', 'Alasan');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J1', 'Kode ');
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K1', 'Jumlah');
				//content

				$oto_nik = $oto_nik == null ? array(0) : $oto_nik;
				$listSpd = $this->dsync->get_travel_detail(array(
					// 'nik' => $this->data['user']->nik
					'nik' 				=> $oto_nik,
					'approval_level' 	=> 99,
					// 'isno_trip'			=> "kosong"
				));
				$listSpd 		= $this->lspd->proses_spd_list($listSpd);
				$list_data 		= $listSpd;
				$baris = 1;
				foreach ($list_data as $data) {
					// Get Country
					$country = $this->dsync->get_countries(array('country_code' => $data->header_country, 'single' => true));

					// Get tujuan
					if ($data->tujuan !== 'lain') {
						$tujuan = $this->dspd->get_travel_tujuan(
							array(
								'company_code' => $data->tujuan,
								'single' => true
							)
						);
						if (isset($tujuan)) {
							if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
								$tujuanArray = explode('-', $tujuan->personal_subarea_text);
								$tujuan = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
							} else {
								$tujuan = $tujuan->personal_area_text . ', ' . $tujuan->kota;
							}
						}
					} else {
						$tujuan = $data->tujuan_lengkap;
					}

					$baris++;
					// $objPHPExcel->getActiveSheet()->getRowDimension($baris)->setRowHeight(40);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(16);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);

					$objPHPExcel->getActiveSheet()->setCellValue("A" . $baris, $data->header_nik);
					$objPHPExcel->getActiveSheet()->setCellValue("B" . $baris, date_create($data->header_start_date)->format('d.m.Y'));
					$objPHPExcel->getActiveSheet()->setCellValue("C" . $baris, date_create($data->header_start_time)->format('H:i'));
					$objPHPExcel->getActiveSheet()->setCellValue("D" . $baris, date_create($data->header_end_date)->format('d.m.Y'));
					$objPHPExcel->getActiveSheet()->setCellValue("E" . $baris, date_create($data->header_end_time)->format('H:i'));
					$objPHPExcel->getActiveSheet()->setCellValue("F" . $baris, $tujuan);
					$objPHPExcel->getActiveSheet()->setCellValue("G" . $baris, $country->country_name);
					$objPHPExcel->getActiveSheet()->setCellValue("H" . $baris, $data->activity_label);
					$objPHPExcel->getActiveSheet()->setCellValue("I" . $baris, $data->keperluan);
					$objPHPExcel->getActiveSheet()->setCellValue("J" . $baris, $data->kode_expense);
					$objPHPExcel->getActiveSheet()->setCellValue("K" . $baris, $data->jumlah);

					$lebar = 0;
				}

				// header ====================================================================================
				// set text bold
				$from 	= "A1"; // or any value
				$to 	= "A10000"; // or any value	
				// $objPHPExcel->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold( true );

				// set header border table
				$styleArray = array(
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				);
				$from_header 	= "A1"; // or any value
				$to_header 		= "K1"; // or any value	
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->applyFromArray($styleArray);
				// set align left
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				$objPHPExcel->getActiveSheet()->getStyle("$from_header:$to_header")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
				// Set collor name header table
				$objPHPExcel->getActiveSheet()->getStyle("A1:$to_header")->applyFromArray(
					array(
						'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb' => '#18CA06')
						),
						'font'  => array(
							'bold'  => true,
							'color' => array('rgb' => '#FFFFFF'),
							'size'  => 13,
							// 'name'  => 'Verdana'
						)
					)
				);
				// header ====================================================================================
				$objPHPExcel->setActiveSheetIndex(0);
				$this->cetak_excel_prop("pembatalan");
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save('php://output');
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				break;
		}

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet

	}

	function cetak_excel_prop($tipe = "pengajuan")
	{
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Sync_' . $tipe . '.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0
	}
}
