<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

    /*
    @application  	: Attachment Accounting
    @author     	: Syah Jadianto (8604)
    @contributor  	:
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */

    Class Transaction extends MX_Controller {
        function __construct() {
            parent::__construct();
            $this->load->model('dmasteracc');
            $this->load->model('dtransactionacc');
        }

        public function index() {
            show_404();
        }

        public function upload($param = NULL) {
            if ($param) {
                switch ($param) {
                    case 'jurnal':
                        $this->upload_jurnal();
                        break;
                    case 'approve':
                        $this->approve_request_upload();
                        break;
                    case 'etc':
                        $this->upload_etc();
                        break;
                    case 'laporan':
                        $this->upload_laporan();
                        break;
                    case 'sync':
                        $this->upload_sync();
                        break;
                    case 'persentase':
                        $this->persentase_laporan();
                        break;
                    default:
                        show_404();
                        break;
                }
            }
            else {
                show_404();
            }
        }

        public function upload_jurnal() {
            $this->general->check_access();
            $filterpabrik   = (empty($_POST['filterpabrik'])) ? NULL : $_POST['filterpabrik'];
            $filterpabrik   = $this->generate->kirana_decrypt($filterpabrik);
            $filtertanggal  = (empty($_POST['filtertanggal'])) ? NULL : $_POST['filtertanggal'];
            $filteraccount  = (empty($_POST['filteraccount'])) ? NULL : $_POST['filteraccount'];
            $filterdoc      = (empty($_POST['filterdoc'])) ? NULL : $_POST['filterdoc'];
            $filternoupload = (empty($_POST['chknoupload'])) ? NULL : $_POST['chknoupload'];

            $data['title']      = "Data Upload Attachment Jurnal Accounting";
            $data['title_form'] = "";
            $data['module']     = $this->router->fetch_module();
            $data['user']       = $this->general->get_data_user();
            // if(base64_decode($this->session->userdata("-ho-")) == 'y'){
            // 	$ho								= "y";
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik(NULL);
            // }else{
            // 	$ho								= "n";
            // 	$id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
            //       	$idpabrik 						= $this->dmasteracc->get_pabrik($id_gedung);
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik($idpabrik[0]->kode);
            // }

            $data['pabrik']         = $this->dmasteracc->get_data_pabrik(base64_decode($this->session->userdata("-nik-")));
            $data['filterpabrik']   = $filterpabrik;
            $data['filtertanggal']  = $filtertanggal;
            $data['filteraccount']  = $filteraccount;
            $data['filterdoc']      = $filterdoc;
            $data['filternoupload'] = $filternoupload;
            $data['jurnal']         = $this->dtransactionacc->get_data_uploadjurnal(NULL, $filterpabrik, empty($filtertanggal) ? NULL : $this->generate->regenerateDateFormat($filtertanggal), $filteraccount, $filterdoc, $filternoupload);

            $this->load->view("transaction/upload_jurnal", $data);
        }

        public function approve_request_upload() {
            $this->general->check_access();
            $filterpabrik       = (empty($_POST['filterpabrik'])) ? array() : $_POST['filterpabrik'];
            $data['title']      = "Data Request Re-Upload Attachment Jurnal Accounting";
            $data['title_form'] = "";
            $data['module']     = $this->router->fetch_module();
            $data['user']       = $this->general->get_data_user();
            // if(base64_decode($this->session->userdata("-ho-")) == 'y'){
            // 	$ho								= "y";
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik(NULL);
            // }else{
            // 	$ho								= "n";
            // 	$id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
            //       	$idpabrik 						= $this->dmasteracc->get_pabrik($id_gedung);
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik($idpabrik[0]->kode);
            // }
            $data['pabrik']       = $this->dmasteracc->get_data_pabrik(base64_decode($this->session->userdata("-nik-")));
            $data['filterpabrik'] = $filterpabrik;
            $string_pabrik        = "";
            if (isset($filterpabrik)) {
                foreach ($filterpabrik as $key => $pabrik) {
                    $string_pabrik = $string_pabrik . $this->generate->kirana_decrypt($pabrik) . ",";
                }
                $string_pabrik = rtrim($string_pabrik, ",");
                $string_pabrik = str_replace(",", "','", $string_pabrik);
            }
            $data['jurnal'] = $this->dtransactionacc->get_request_upload(NULL, $string_pabrik);

            $this->load->view("transaction/approve_request", $data);
        }


        public function upload_etc() {
            $this->general->check_access();
            $filterpabrik     = (empty($_POST['filterpabrik'])) ? NULL : $_POST['filterpabrik'];
            $filterpabrik     = $this->generate->kirana_decrypt($filterpabrik);
            $filterjenis      = (empty($_POST['filterjenis'])) ? NULL : $_POST['filterjenis'];
            $filterjenis      = $this->generate->kirana_decrypt($filterjenis);
            $filterfrom       = (empty($_POST['filterfrom'])) ? NULL : $_POST['filterfrom'];
            $filterto         = (empty($_POST['filterto'])) ? NULL : $_POST['filterto'];
            $filterchknocheck = (empty($_POST['chknocheck'])) ? NULL : $_POST['chknocheck'];

            $data['title']      = "Data Upload Laporan Accounting";
            $data['title_form'] = "";
            $data['module']     = $this->router->fetch_module();
            $data['user']       = $this->general->get_data_user();
            // if(base64_decode($this->session->userdata("-ho-")) == 'y'){
            // 	$ho								= "y";
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik(NULL);
            // }else{
            // 	$ho								= "n";
            // 	$id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
            //       	$idpabrik 						= $this->dmasteracc->get_pabrik($id_gedung);
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik($idpabrik[0]->kode);
            // }
            $data['pabrik']           = $this->dmasteracc->get_data_pabrik(base64_decode($this->session->userdata("-nik-")));
            $data['jenis']            = $this->dmasteracc->get_data_jenis();
            $data['filterpabrik']     = $filterpabrik;
            $data['filterjenis']      = $filterjenis;
            $data['filterfrom']       = $filterfrom;
            $data['filterto']         = $filterto;
            $data['filterchknocheck'] = $filterchknocheck;
            $data['upload']           = $this->dtransactionacc->get_data_uploadetc(NULL, $filterpabrik, empty($filterfrom) ? NULL : $this->generate->regenerateDateFormat($filterfrom), empty($filterto) ? NULL : $this->generate->regenerateDateFormat($filterto), $filterchknocheck);

            $this->load->view("transaction/upload_etc", $data);
        }

        public function upload_laporan() {
            $this->general->check_access();
            $filterpabrik   = (empty($_POST['filterpabrik'])) ? NULL : $_POST['filterpabrik'];
            $filterpabrik   = $this->generate->kirana_decrypt($filterpabrik);
            $filtersource   = (empty($_POST['filtersource'])) ? NULL : $_POST['filtersource'];
            $filtersource   = $this->generate->kirana_decrypt($filtersource);
            $filterjenis    = (empty($_POST['filterjenis'])) ? NULL : $_POST['filterjenis'];
            $filterjenis    = $this->generate->kirana_decrypt($filterjenis);
            $filterfrom     = (empty($_POST['filterfrom'])) ? NULL : $_POST['filterfrom'];
            $filterto       = (empty($_POST['filterto'])) ? NULL : $_POST['filterto'];
            $filtersearch   = (empty($_POST['filtersearch'])) ? NULL : $_POST['filtersearch'];
            $filtersearch   = $this->generate->kirana_decrypt($filtersearch);
            $filterparam    = (empty($_POST['filterparam'])) ? NULL : $_POST['filterparam'];
            $filternoupload = (empty($_POST['chknoupload'])) ? NULL : $_POST['chknoupload'];
            // CR 2352
            $filterisupload = (empty($_POST['chkisupload'])) ? NULL : $_POST['chkisupload'];
            $filtertype = (empty($_POST['filtertype'])) ? NULL : $_POST['filtertype'];

            $filterjenis = ($filtersource != 1) ? NULL : $filterjenis;

            $data['title']      = "Data Laporan Upload";
            $data['title_form'] = "";
            $data['module']     = $this->router->fetch_module();
            $data['user']       = $this->general->get_data_user();
            // if(base64_decode($this->session->userdata("-ho-")) == 'y'){
            // 	$ho								= "y";
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik(NULL);
            // }else{
            // 	$ho								= "n";
            // 	$id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
            //       	$idpabrik 						= $this->dmasteracc->get_pabrik($id_gedung);
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik($idpabrik[0]->kode);
            // }
            $data['pabrik']         = $this->dmasteracc->get_data_pabrik(base64_decode($this->session->userdata("-nik-")));
            $data['jenis']          = $this->dmasteracc->get_data_jenis();
            $data['filterpabrik']   = $filterpabrik;
            $data['filtersource']   = $filtersource;
            $data['filterjenis']    = $filterjenis;
            $data['filterfrom']     = $filterfrom;
            $data['filterto']       = $filterto;
            $data['filtersearch']   = $filtersearch;
            $data['filterparam']    = $filterparam;
            $data['filternoupload'] = $filternoupload;
            $data['filterisupload'] = $filterisupload;
            $data['filtertype'] = $filtertype;
            $data['upload']         = $this->dtransactionacc->get_data_uploadlaporan(NULL, $filterpabrik, $filtersource, $filterjenis, empty($filterfrom) ? NULL : $this->generate->regenerateDateFormat($filterfrom), empty($filterto) ? NULL : $this->generate->regenerateDateFormat($filterto), $filtersearch, $filterparam, $filternoupload, $filterisupload, $filtertype);

            $this->load->view("transaction/upload_laporan", $data);
        }

        // CR 2352
        public function persentase_laporan() {
            $this->general->check_access();
            
            $data['title']      = "Laporan Persentase Jurnal Uploaded";
            $data['title_form'] = "";
            $data['module']     = $this->router->fetch_module();
            $data['user']       = $this->general->get_data_user();
          
            $data['tahun']    = $this->dtransactionacc->get_data_tahun(array(
                "connect" 		=> TRUE,
                "app"   		=> 'acc',
            ));

            $this->load->view("transaction/persentase_laporan", $data);
        }

        public function upload_sync() {
            $this->general->check_access();
            $filterpabrik       = (empty($_POST['filterpabrik'])) ? NULL : $_POST['filterpabrik'];
            $filterfrom         = (empty($_POST['filterfrom'])) ? NULL : $_POST['filterfrom'];
            $filterto           = (empty($_POST['filterto'])) ? NULL : $_POST['filterto'];
            $filteraccount      = (empty($_POST['filteraccount'])) ? NULL : $_POST['filteraccount'];
            $filterdoc          = (empty($_POST['filterdoc'])) ? NULL : $_POST['filterdoc'];
            $data['title']      = "Synchronize Data Upload Attachment Jurnal Accounting";
            $data['title_form'] = "";
            $data['module']     = $this->router->fetch_module();
            $data['user']       = $this->general->get_data_user();
            // if(base64_decode($this->session->userdata("-ho-")) == 'y'){
            // 	$ho								= "y";
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik(NULL);
            // }else{
            // 	$ho								= "n";
            // 	$id_gedung						= base64_decode($this->session->userdata("-id_gedung-"));
            //       	$idpabrik 						= $this->dmasteracc->get_pabrik($id_gedung);
            //       	$data['pabrik'] 				= $this->dmasteracc->get_pabrik($idpabrik[0]->kode);
            // }
            $data['pabrik']        = $this->dmasteracc->get_data_pabrik(base64_decode($this->session->userdata("-nik-")));
            $data['filterpabrik']  = $filterpabrik;
            $data['filterfrom']    = $filterfrom;
            $data['filterto']      = $filterto;
            $data['filteraccount'] = $filteraccount;
            $data['filterdoc']     = $filterdoc;

            $data['jurnal'] = $this->dtransactionacc->get_data_uploadsync(NULL, $filterpabrik, empty($filterfrom) ? NULL : $this->generate->regenerateDateFormat($filterfrom), empty($filterto) ? NULL : $this->generate->regenerateDateFormat($filterto), $filteraccount, $filterdoc);

            $this->load->view("transaction/upload_sync", $data);
        }

        public function get_data($param) {
            switch ($param) {
                case 'upload_jurnal':
                    $this->get_upload_jurnal();
                    break;
                case 'upload_etc':
                    $this->get_upload_etc();
                    break;
                case 'upload_rfc':
                    $this->get_upload_rfc();
                    break;
                case 'persentase':
                    $this->get_laporan_persentase();
                    break;
                default:
                    $return = array();
                    echo json_encode($return);
                    break;
            }
        }


        //=================================//
        //		  PROCESS FUNCTION 		   //
        //=================================//

        public function set_data($action, $param) {
            switch ($param) {
                case 'request_upload':
                    $this->set_request_upload($action);
                    break;
                case 'add_upload':
                    $this->set_add_upload($action);
                    break;
                case 'approve_request':
                    $this->set_approve_request($action);
                    break;
                case 'reject_request':
                    $this->set_reject_request($action);
                    break;
                case 'check_upload_etc':
                    $this->set_check_upload_etc($action);
                    break;
                case 'upload_etc':
                    $this->set_upload_etc($action);
                    break;
                case 'check_upload_laporan':
                    $this->set_check_upload_laporan($action);
                    break;
                default:
                    $return = array();
                    echo json_encode($return);
                    break;
            }
        }

        public function save($param) {
            switch ($param) {
                case 'upload_jurnal':
                    $this->save_upload_jurnal();
                    break;
                case 'request_upload':
                    $this->save_request_upload();
                    break;
                case 'upload_etc':
                    $this->save_upload_etc();
                    break;

                default:
                    $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                    echo json_encode($return);
                    break;
            }
        }

        private function get_upload_rfc() {
            $filterpabrik  = (empty($_POST['filterpabrik'])) ? NULL : $_POST['filterpabrik'];
            $filterfrom    = (empty($_POST['filterfrom'])) ? NULL : $_POST['filterfrom'];
            $filterto      = (empty($_POST['filterto'])) ? NULL : $_POST['filterto'];
            $filteraccount = (empty($_POST['filteraccount'])) ? NULL : $_POST['filteraccount'];
            $filterdoc     = (empty($_POST['filterdoc'])) ? NULL : $_POST['filterdoc'];

            $datetime = date("Y-m-d H:i:s");
            $this->general->connectDbPortal();

            // Call rfc
            if ($filterpabrik != "") {
                $this->load->library('sap');

                $koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);
                // $data    = $koneksi['ERP'];
                $data    = $koneksi['ERP_310'];
                $constr  = array("logindata"     => array(
                    "ASHOST"   => $data['ASHOST']        // application server
                    , "SYSNR"  => $data['SYSNR']                // system number
                    , "CLIENT" => $data['CLIENT']            // client
                    , "USER"   => $data['USER']            // user
                    , "PASSWD" => $data['PASSWD']        // password
                )
                                 , "show_errors" => false            // let class printout errors
                                 , "debug"       => false);                // detailed debugging information
                $sap     = new saprfc($constr);

                $bukrs = $this->dmasteracc->get_pabrik($filterpabrik);
                $tahun = date_format(date_create($this->generate->regenerateDateFormat($filterfrom)), "Y");
                $from  = date_format(date_create($this->generate->regenerateDateFormat($filterfrom)), "Ymd");
                $to    = date_format(date_create($this->generate->regenerateDateFormat($filterto)), "Ymd");

                $tipe      = "";
                $no_jurnal = "";

                $dttipe = $this->dmasteracc->get_tipe_doc(NULL);
                foreach ($dttipe as $dttipe) {
                    $tipe .= $dttipe->tipe_doc . "|";
                }

                $dtjurnal  = $this->dmasteracc->get_account_number(NULL);
                $no_jurnal = $dtjurnal[0]->acc_number_low . "+" . $dtjurnal[0]->acc_number_high;
                $accno     = $_POST['filteraccount'];
                $nodoc     = $_POST['filterdoc'];

                $result = $sap->callFunction("Z_FI_ACCOUNTING_JURNAL",
                                             array(
                                                 //array("IMPORT","I_BUKRS",$_SESSION['-id_gedung-']),
                                                 array("IMPORT", "I_BUKRS", $bukrs[0]->plant_code),
                                                 array("IMPORT", "I_GSBER", $filterpabrik),
                                                 array("IMPORT", "I_GJAHR", $tahun),
                                                 array("IMPORT", "I_BUDAT", $from),
                                                 array("IMPORT", "I_ENDDA", $to),
                                                 array("IMPORT", "I_BLART", $tipe),
                                                 array("IMPORT", "I_HKONT", $no_jurnal),
                                                 array("IMPORT", "S_HKONT", $accno),
                                                 array("IMPORT", "S_BELNR", $nodoc),
                                                 array("TABLE", "T_DATA", array())
                                             )
                );
				// echo json_encode($result);
				// exit();

                if((base64_decode($this->session->userdata("-nik-")) == 8347)or(base64_decode($this->session->userdata("-nik-")) == 7143)){
                    $param = array(
                        //array("IMPORT","I_BUKRS",$_SESSION['-id_gedung-']),
                        array("IMPORT", "I_BUKRS", $bukrs[0]->plant_code),
                        array("IMPORT", "I_GSBER", $filterpabrik),
                        array("IMPORT", "I_GJAHR", $tahun),
                        array("IMPORT", "I_BUDAT", $from),
                        array("IMPORT", "I_ENDDA", $to),
                        array("IMPORT", "I_BLART", $tipe),
                        array("IMPORT", "I_HKONT", $no_jurnal),
                        array("IMPORT", "S_HKONT", $accno),
                        array("IMPORT", "S_BELNR", $nodoc),
                        array("TABLE", "T_DATA", array())
                    );
                    // echo json_encode(compact('result', 'param'));
                    // exit();
                }
				
				/**=======================================
				 * - add function write data into log file
				 *=======================================*/
				$logdir = KIRANA_PATH_LOGS . 'accounting/';
				if (!file_exists($logdir)) {
					@mkdir($logdir, 0777, true);
				}
				@chmod($logdir, 0775);
				$logfile = $logdir . $filterpabrik . '-' . date('Ymd') . ".txt";
				$this->general->prepend_log("HASIL : ".JSON_encode($result), $logfile);
				$this->general->prepend_log("PARAM : ".JSON_encode($param), $logfile);
				/**=======================================*/


                if ($sap->getStatus() == SAPRFC_OK) {
                    // $data['rfc'] = $result["T_DATA"];
                    //				 echo json_encode($result["T_DATA"]); exit();
                    // $this->dgeneral->begin_transaction();
                    foreach ($result["T_DATA"] as $key => $dt) {
                        $exists = $this->dtransactionacc->get_uploadjurnal(NULL, $dt["GSBER"], $dt["BUDAT"], $dt["BELNR"]);

                        if (empty($exists)) {
                            $data_row = array(
                                'no_doc'       => $dt["BELNR"],
                                'text'         => $dt["SGTXT"],
                                'tipe'         => $dt["BLART"],
                                'account'      => $dt["HKONT"],
                                'tgl'          => $dt["BUDAT"],
                                'bukrs'        => $bukrs[0]->plant_code,
                                'gsber'        => $dt["GSBER"],
                                'dmbtr'        => $dt["DMBTR"],
                                'reff'         => $dt["REFF"],
                                'cancel'       => $dt["CANCEL"],
                                'in_date'      => $datetime,
                                'in_by'        => base64_decode($this->session->userdata("-nik-")),
                                'in_datefirst' => $datetime,
                            );
                            $this->dgeneral->insert('tbl_acc_upload_jurnal', $data_row);
                        }
                        else {
                            $id = $exists[0]->id;

                            if ($dt["CANCEL"] == "X") {
                                $data_row = array(
                                    'cancel'    => "X",
                                    'edit_date' => $datetime,
                                    'edit_by'   => base64_decode($this->session->userdata("-nik-"))
                                );
                                $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                                    array(
                                        'kolom' => 'id',
                                        'value' => $id
                                    )
                                ));
                            }else{
                                $data_row = array(
                                    'dmbtr'     => $dt["DMBTR"],
                                    'edit_date' => $datetime,
                                    'edit_by'   => base64_decode($this->session->userdata("-nik-"))
                                );
                                $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                                    array(
                                        'kolom' => 'id',
                                        'value' => $id
                                    )
                                ));
								
							}

                        }
                    }
                }
                else {
                    $msg = "Proses Synchronize gagal, silahkan mencoba kembali";
                    $sts = "NotOK";

                    $return = array('sts' => $sts, 'msg' => $msg);
                    echo json_encode($return);
                    exit();
                }
            }

            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }
            else {
                $this->dgeneral->commit_transaction();
                $msg = "Synchronize data berhasil";
                $sts = "OK";
            }

            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);

        }

        /**********************************/
        /*			  private  			  */
        /**********************************/

        private function get_laporan_persentase() {
            $filtertype   = (empty($_POST['filtertype'])) ? 'HO' : $_POST['filtertype'];
            $filteryear   = (empty($_POST['filteryear'])) ? date("Y") : $_POST['filteryear'];          
            $data   = $this->dtransactionacc->get_laporan_persentase(array(
                "connect" 		=> TRUE,
                "app"   		=> 'acc',
                "type" 	        => $filtertype,
                "year"	        => $filteryear
            ));
            echo json_encode($data);
        }

        private function get_upload_jurnal() {
            $id = $this->generate->kirana_decrypt($_POST['id']);

            $this->general->connectDbPortal();
            $data = $this->dtransactionacc->get_uploadjurnal($id, NULL, NULL, NULL);
            echo json_encode($data);
        }

        private function set_request_upload() {
            $datetime = date("Y-m-d H:i:s");
            $id       = $this->generate->kirana_decrypt($_POST['id']);
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = array(
                'data'      => "-",
                'edit_date' => $datetime,
                'alow_edit' => 0,
                'info'      => $_POST['info']
            );
            $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                array(
                    'kolom' => 'id',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }
            else {
                $this->dgeneral->commit_transaction();
                $msg = "Permintaan Re-Upload Berhasil Diajukan";
                $sts = "OK";
            }
            $return = array('sts' => $sts, 'msg' => $msg);
            $this->general->closeDb();
            echo json_encode($return);
        }

        private function set_add_upload() {
            $datetime = date("Y-m-d H:i:s");
            $id       = $this->generate->kirana_decrypt($_POST['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $id_uploadjurnal = $this->dtransactionacc->get_uploadjurnal($id, NULL, NULL, NULL);

            $uploaddir = realpath('./') . '/assets/file/acc/uploadjurnal';
            if (isset($_FILES['file'])) {
                if (!file_exists($uploaddir)) {
                    mkdir($uploaddir, 0777, true);
                }
                $config['upload_path']   = $uploaddir;
                $config['allowed_types'] = 'pdf';
                $config['max_size']      = 3097152; // 3mb
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;

                $this->load->library('upload', $config);
                // $datalampiran = array();

                $uploaded = "";
                $files    = $_FILES;
                $cpt      = count($_FILES ['file'] ['name']);

                $count = $id_uploadjurnal[0]->num_data;

                for ($i = 0; $i < $cpt; $i++) {

                    // $name = time().$files ['file'] ['name'] [$i];
                    $temp      = explode(".", $files ['file'] ['name'] [$i]);
                    $extension = end($temp);

                    $count++;

                    $name = $id_uploadjurnal[0]->filename . "_" . $count . "." . $extension;

                    $_FILES ['file'] ['name']     = $name;
                    $_FILES ['file'] ['type']     = $files ['file'] ['type'] [$i];
                    $_FILES ['file'] ['tmp_name'] = $files ['file'] ['tmp_name'] [$i];
                    $_FILES ['file'] ['error']    = $files ['file'] ['error'] [$i];
                    $_FILES ['file'] ['size']     = $files ['file'] ['size'] [$i];

                    if (file_exists(realpath('./') . "/" . "assets/file/acc/uploadjurnal/" . $name)) {
                        unlink(realpath('./') . "/" . "assets/file/acc/uploadjurnal/" . $name);
                    }

                    // $this->upload->initialize($this->set_upload_options($file_path));
                    if (!($this->upload->do_upload('file')) || $files ['file'] ['error'] [$i] != 0) {
                        // print_r($this->upload->display_errors());
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        echo json_encode($return);
                        exit;
                    }
                    else {
                        $upload_data = $this->upload->data();
                        $uploaded    .= 'assets/file/acc/uploadjurnal/' . $name . "|";
                    }
                }
            }

            $num_data       = 0;
            $explode_upload = explode("|", $uploaded);
            foreach ($explode_upload as $key => $value) {
                if ($value != "") {
                    $num_data++;
                }
            }
            $num_data += $id_uploadjurnal[0]->num_data;

            if (isset($id) && $id != "") {
                $data_row = array(
                    'data'        => $id_uploadjurnal[0]->data . $uploaded,
                    'num_data'    => $num_data,
                    'in_by'       => base64_decode($this->session->userdata("-nik-")),
                    'upload_date' => $datetime
                );
                $data_row = array_merge($data_row);
                $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $id
                    )
                ));

                if ($this->dgeneral->status_transaction() === false) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                }
                else {
                    $this->dgeneral->commit_transaction();
                    if (isset($_POST['id']) && $_POST['id'] != "") {
                        $msg = "Data berhasil diupload";
                    }
                    else {
                        $msg = "Data berhasil ditambahkan";
                    }
                    $sts = "OK";
                }

            }
            else {
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }

            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);
        }

        private function set_approve_request() {
            $datetime = date("Y-m-d H:i:s");

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            foreach ($_POST['chkdok'] as $key => $post) {
                $id       = $this->generate->kirana_decrypt($post);
                $data_row = array(
                    'alow_edit' => 1,
                    'appv_date' => $datetime
                );
                $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $id
                    )
                ));
            }

            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }
            else {
                $this->dgeneral->commit_transaction();
                $msg = "Permintaan Re-Upload Berhasil Diapprove";
                $sts = "OK";
            }
            $return = array('sts' => $sts, 'msg' => $msg);
            $this->general->closeDb();
            echo json_encode($return);
        }

        private function set_reject_request() {
            $datetime = date("Y-m-d H:i:s");
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            foreach ($_POST['chkdok'] as $key => $post) {
                $id       = $this->generate->kirana_decrypt($post);
                $data_row = array(
                    'alow_edit'   => NULL,
                    'reject_edit' => 1,
                    'appv_date'   => $datetime
                );
                $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $id
                    )
                ));
            }

            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }
            else {
                $this->dgeneral->commit_transaction();
                $msg = "Permintaan Re-Upload Berhasil Direject";
                $sts = "OK";
            }
            $return = array('sts' => $sts, 'msg' => $msg);
            $this->general->closeDb();
            echo json_encode($return);
        }

        private function set_check_upload_etc() {
            $datetime = date("Y-m-d H:i:s");
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            foreach ($_POST['checkjurnal'] as $key => $post) {
                $id       = $this->generate->kirana_decrypt($post);
                $data_row = array(
                    'checklist' => 'y',
                    'edit_by'   => base64_decode($this->session->userdata("-nik-")),
                    'edit_date' => $datetime
                );
                $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $id
                    )
                ));
            }
            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }
            else {
                $this->dgeneral->commit_transaction();
                $msg = "Data upload laporan berhasil dicheck";
                $sts = "OK";
            }
            $return = array('sts' => $sts, 'msg' => $msg);
            $this->general->closeDb();
            echo json_encode($return);
        }

        private function set_check_upload_laporan() {
            $datetime = date("Y-m-d H:i:s");
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            foreach ($_POST['checkjurnal'] as $key => $post) {
                $id_check = $this->generate->kirana_decrypt($post);
                $data_row = array(
                    'checklist' => 'y',
                    'edit_by'   => base64_decode($this->session->userdata("-nik-")),
                    'edit_date' => $datetime
                );
                $id       = explode("|", $id_check);
                $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $id[0]
                    )
                ));
            }
            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }
            else {
                $this->dgeneral->commit_transaction();
                $msg = "Data upload laporan berhasil dicheck";
                $sts = "OK";
            }
            $return = array('sts' => $sts, 'msg' => $msg);
            $this->general->closeDb();
            echo json_encode($return);
        }

        private function set_upload_etc() {
            $id       = $this->generate->kirana_decrypt($_POST['id']);
            $datetime = date("Y-m-d H:i:s");
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $data_row = array(
                'del'       => 'y',
                'edit_by'   => base64_decode($this->session->userdata("-nik-")),
                'edit_date' => $datetime
            );
            $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                array(
                    'kolom' => 'id',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }
            else {
                $this->dgeneral->commit_transaction();
                $msg = "Data upload laporan berhasil hapus";
                $sts = "OK";
            }
            $return = array('sts' => $sts, 'msg' => $msg);
            $this->general->closeDb();
            echo json_encode($return);
        }

        private function save_upload_jurnal() {
            $datetime = date("Y-m-d H:i:s");
            $id       = $this->generate->kirana_decrypt($_POST['id']);

            $this->general->connectDbPortal();

            $id_uploadjurnal = $this->dtransactionacc->get_uploadjurnal($id, NULL, NULL, NULL);

            $attach = explode("|", $id_uploadjurnal[0]->data);
            foreach ($attach as $key => $file) {
                if ($file != "") {
                    if (file_exists(realpath('./') . "/" . $file)) {
                        unlink(realpath('./') . "/" . $file);
                    }
                }
            }


            $uploaddir = realpath('./') . '/assets/file/acc/uploadjurnal';
            if (isset($_FILES['file'])) {
                if (!file_exists($uploaddir)) {
                    mkdir($uploaddir, 0777, true);
                }
                $config['upload_path']   = $uploaddir;
                $config['allowed_types'] = 'pdf';
                $config['max_size']      = 3097152; // 3mb
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;

                $this->load->library('upload', $config);
                // $datalampiran = array();

                $uploaded = "";
                $files    = $_FILES;
                $cpt      = count($_FILES ['file'] ['name']);

                for ($i = 0; $i < $cpt; $i++) {

                    // $name = time().$files ['file'] ['name'] [$i];
                    $temp      = explode(".", $files ['file'] ['name'] [$i]);
                    $extension = end($temp);

                    if ($i == 0) {
                        $name = $id_uploadjurnal[0]->filename . "." . $extension;
                    }
                    else {
                        $count = $i + 1;
                        $name  = $id_uploadjurnal[0]->filename . "_" . $count . "." . $extension;
                    }
                    $_FILES ['file'] ['name']     = $name;
                    $_FILES ['file'] ['type']     = $files ['file'] ['type'] [$i];
                    $_FILES ['file'] ['tmp_name'] = $files ['file'] ['tmp_name'] [$i];
                    $_FILES ['file'] ['error']    = $files ['file'] ['error'] [$i];
                    $_FILES ['file'] ['size']     = $files ['file'] ['size'] [$i];

                    // $this->upload->initialize($this->set_upload_options($file_path));
                    if (!($this->upload->do_upload('file')) || $files ['file'] ['error'] [$i] != 0) {
                        // print_r($this->upload->display_errors());
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        echo json_encode($return);
                        exit;
                    }
                    else {
                        $upload_data = $this->upload->data();
                        $uploaded    .= 'assets/file/acc/uploadjurnal/' . $name . "|";
                    }
                }
            }

            $num_data       = 0;
            $explode_upload = explode("|", $uploaded);
            foreach ($explode_upload as $key => $value) {
                if ($value != "") {
                    $num_data++;
                }
            }

            if (isset($id) && $id != "") {
                $this->dgeneral->begin_transaction();

                $data_row = array(
                    'data'        => $uploaded,
                    'num_data'    => $num_data,
                    'in_by'       => base64_decode($this->session->userdata("-nik-")),
                    'upload_date' => $datetime
                );
                $data_row = array_merge($data_row);
                $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $id
                    )
                ));

                if ($this->dgeneral->status_transaction() === false) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                }
                else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil diupload";
                    $sts = "OK";
                }

            }
            else {
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }

            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);
        }


        //-------------------------------------------------//


        /*	       private Upload etc     */
        /**********************************/
        private function get_upload_etc() {
            $id = $this->generate->kirana_decrypt($_POST['id']);
            $this->general->connectDbPortal();
            $data = $this->dtransactionacc->get_uploadetc($id, NULL, NULL, NULL, NULL, NULL);
            echo json_encode($data);
        }

        private function set_add_uploadetc() {
            $datetime = date("Y-m-d H:i:s");
            $id       = $this->generate->kirana_decrypt($_POST['id']);

            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            $id_uploadjurnal = $this->dtransactionacc->get_uploadjurnal($id, NULL, NULL, NULL);

            $uploaddir = realpath('./') . '/assets/file/acc/uploadjurnal';
            if (isset($_FILES['file'])) {
                if (!file_exists($uploaddir)) {
                    mkdir($uploaddir, 0777, true);
                }
                $config['upload_path']   = $uploaddir;
                $config['allowed_types'] = 'pdf';
                $config['max_size']      = 3097152; // 3mb
                // $config['max_width']            = 1024;
                // $config['max_height']           = 768;

                $this->load->library('upload', $config);
                // $datalampiran = array();

                $uploaded = "";
                $files    = $_FILES;
                $cpt      = count($_FILES ['file'] ['name']);

                $count = $id_uploadjurnal[0]->num_data;

                for ($i = 0; $i < $cpt; $i++) {

                    // $name = time().$files ['file'] ['name'] [$i];
                    $temp      = explode(".", $files ['file'] ['name'] [$i]);
                    $extension = end($temp);

                    $count++;

                    $name = $id_uploadjurnal[0]->filename . "_" . $count . "." . $extension;

                    $_FILES ['file'] ['name']     = $name;
                    $_FILES ['file'] ['type']     = $files ['file'] ['type'] [$i];
                    $_FILES ['file'] ['tmp_name'] = $files ['file'] ['tmp_name'] [$i];
                    $_FILES ['file'] ['error']    = $files ['file'] ['error'] [$i];
                    $_FILES ['file'] ['size']     = $files ['file'] ['size'] [$i];

                    if (file_exists(realpath('./') . "/" . "assets/file/acc/uploadjurnal/" . $name)) {
                        unlink(realpath('./') . "/" . "assets/file/acc/uploadjurnal/" . $name);
                    }

                    // $this->upload->initialize($this->set_upload_options($file_path));
                    if (!($this->upload->do_upload('file')) && $files ['file'] ['error'] [$i] != 0) {
                        // print_r($this->upload->display_errors());
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        echo json_encode($return);
                        exit;
                    }
                    else {
                        $upload_data = $this->upload->data();
                        $uploaded    .= 'assets/file/acc/uploadjurnal/' . $name . "|";
                    }
                }
            }

            $num_data       = 0;
            $explode_upload = explode("|", $uploaded);
            foreach ($explode_upload as $key => $value) {
                if ($value != "") {
                    $num_data++;
                }
            }
            $num_data += $id_uploadjurnal[0]->num_data;

            if (isset($id) && $id != "") {
                $this->dgeneral->begin_transaction();

                $data_row = array(
                    'data'        => $id_uploadjurnal[0]->data . $uploaded,
                    'num_data'    => $num_data,
                    'in_by'       => base64_decode($this->session->userdata("-nik-")),
                    'upload_date' => $datetime
                );
                $data_row = array_merge($data_row);
                $this->dgeneral->update('tbl_acc_upload_jurnala', $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $id
                    )
                ));

                if ($this->dgeneral->status_transaction() === false) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                }
                else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil diupload";
                    $sts = "OK";
                }

            }
            else {
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }

            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);
        }

        private function save_upload_etc() {
            $datetime = date("Y-m-d H:i:s");
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();

            if (empty($_POST['id'])) {
                $new_number = $this->dtransactionacc->get_new_number($_POST['pabrik'], $this->generate->regenerateDateFormat($_POST['date']));
            }
            else {
                $number = $this->dtransactionacc->get_uploadetc($_POST['id'], NULL, NULL, NULL, NULL);
            }

            $uploaddir = realpath('./') . '/assets/file/acc/uploadjurnal';
            if (isset($_FILES['file']['name'])) {
                if ($_FILES['file']['name'] != "") {
                    if (!file_exists($uploaddir)) {
                        mkdir($uploaddir, 0777, true);
                    }
                    $config['upload_path']   = $uploaddir;
                    $config['allowed_types'] = 'pdf';
                    $config['max_size']      = 3097152; // 3mb
                    // $config['max_width']            = 1024;
                    // $config['max_height']           = 768;

                    $this->load->library('upload', $config);
                    // $datalampiran = array();

                    $uploaded = "";
                    $files    = $_FILES;
                    $cpt      = count($_FILES ['file'] ['name']);

                    // $name = time().$files ['file'] ['name'] [$i];
                    $temp = explode(".", $files ['file'] ['name']);

                    $extension = end($temp);
                    $yymm      = date_format(date_create($_POST['date']), "ym");

                    if (empty($_POST['id'])) {
                        $name = $yymm . $_POST['pabrik'] . substr($new_number[0]->new_number, -3) . "." . $extension;
                    }
                    else {
                        $name = $yymm . $_POST['pabrik'] . substr($number[0]->no_doc, -3) . "." . $extension;
                    }

                    if (file_exists(realpath('./') . "/" . "assets/file/acc/uploadjurnal/" . $name)) {
                        unlink(realpath('./') . "/" . "assets/file/acc/uploadjurnal/" . $name);
                    }

                    $_FILES ['file'] ['name'] = $name;
                    // $_FILES ['file'] ['type'] = $files ['file'] ['type'];
                    // $_FILES ['file'] ['tmp_name'] = $files ['file'] ['tmp_name'];
                    // $_FILES ['file'] ['error'] = $files ['file'] ['error'];
                    // $_FILES ['file'] ['size'] = $files ['file'] ['size'];


                    // $this->upload->initialize($this->set_upload_options($file_path));
                    if (!($this->upload->do_upload('file')) && $_FILES ['file'] ['error'] != 0) {
                        // print_r($this->upload->display_errors());
                        $msg = "Periksa kembali data yang dimasukkan, proses upload gagal";
                        $sts = "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        echo json_encode($return);
                        exit;
                    }
                    else {
                        $upload_data = $this->upload->data();
                        $uploaded    .= 'assets/file/acc/uploadjurnal/' . $name;
                    }
                }
                else {
                    $uploaded = $number[0]->data;
                }
            }

            $num_data       = 0;
            $explode_upload = explode("|", $uploaded);
            foreach ($explode_upload as $key => $value) {
                if ($value != "") {
                    $num_data++;
                }
            }

            $nama_jenis = $this->dmasteracc->get_data_jenis($_POST['jenis'], NULL);
            $bukrs      = $this->dmasteracc->get_pabrik($_POST['pabrik'], NULL);

            if (isset($_POST['id']) && $_POST['id'] != "") {

                $data_row = array(
                    'id_jenis'   => $_POST['jenis'],
                    'nama_jenis' => $nama_jenis[0]->nama,
                    'text'       => $_POST['judul'],
                    'data'       => $uploaded,
                    'tgl'        => $this->generate->regenerateDateFormat($_POST['date']),
                    'info'       => $_POST['info'],
                    'bukrs'      => $bukrs[0]->plant_code,
                    'gsber'      => $_POST['pabrik'],
                    'in_date'    => $datetime,
                    'in_by'      => base64_decode($this->session->userdata("-nik-")),
                    'edit_date'  => $datetime,
                    'edit_by'    => base64_decode($this->session->userdata("-nik-"))
                );
                $data_row = array_merge($data_row);
                $this->dgeneral->update('tbl_acc_upload_jurnal', $data_row, array(
                    array(
                        'kolom' => 'id',
                        'value' => $_POST['id']
                    )
                ));

            }
            else {
                $data_row = array(
                    'id_jenis'   => $_POST['jenis'],
                    'nama_jenis' => $nama_jenis[0]->nama,
                    'no_doc'     => $new_number[0]->new_number,
                    'text'       => $_POST['judul'],
                    'data'       => $uploaded,
                    'num_data'   => 1,
                    'tgl'        => $this->generate->regenerateDateFormat($_POST['date']),
                    'info'       => $_POST['info'],
                    'bukrs'      => $bukrs[0]->plant_code,
                    'gsber'      => $_POST['pabrik'],
                    'in_date'    => $datetime,
                    'in_by'      => base64_decode($this->session->userdata("-nik-")),
                    'lap_lain'   => 1
                );
                $this->dgeneral->insert('tbl_acc_upload_jurnal', $data_row);
            }

            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            }
            else {
                $this->dgeneral->commit_transaction();
                if (isset($_POST['id']) && $_POST['id'] != "") {
                    $msg = "Data berhasil diupdate";
                }
                else {
                    $msg = "Data berhasil diupload";
                }
                $sts = "OK";
            }

            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);
        }


        //-------------------------------------------------//


    }