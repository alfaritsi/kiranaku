<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Plantation
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
include_once APPPATH . "modules/plantation/controllers/BaseControllers.php";

class Balance extends BaseControllers
{
    private $access_plant;
    private $site_ktp = ['AAP1', 'AAP2', 'PKP1', 'KGK1'];

    function __construct()
    {
        parent::__construct();
        $this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));

        $this->load->model('dtransaksi');
        $this->load->model('dmaster');

        $this->access_plant = base64_decode($this->session->userdata("-gsber-"));
    }

    public function unggah()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $this->load->view("transaksi/unggahbalance", $data);
    }

    //==================================================//
    /*                   Save data                      */
    //==================================================//
    public function save($param = NULL)
    {
        switch ($param) {
            case 'upload':
                $tipe = $_POST['tipe'];
                switch ($tipe) {
                    case 'ppb':
                        $this->save_upload_ppb();
                        break;
                    case 'po_ho':
                        $this->save_upload_po_ho();
                        break;
                    case 'po_site':
                        $this->save_upload_po_site();
                        break;
                    case 'gr_ho':
                        $this->save_upload_gr_ho();
                        break;
                    case 'gi':
                        $this->save_upload_gi();
                        break;
                    default:
                        $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                        echo json_encode($return);
                        break;
                }
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
    private function save_upload_ppb($param = NULL)
    {
        $post 			= $this->input->post(NULL, TRUE);
		$datetime 	= date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
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
                $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
                $sheet_name     = $objWorksheet->getTitle();
                $data_excel		= $objPHPExcel->getActiveSheet();
                $highestRow 	= $data_excel->getHighestRow(); 
                $highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                $data_row		= array();

                if ($sheet_name != "PPBB") {
                    $return = array('sts' => 'NotOk', 'msg' => "File yang diupload tidak sesuai.");

                    unlink($data['upload_data']['full_path']);
                    echo json_encode($return);
                    exit();
                }

                $this->dgeneral->begin_transaction();

                $current_ppb = "";
                $id_ppb = "";
                $i = 1;
                $list_barang = array();
                
                for ($brs = 5; $brs <= $highestRow; $brs++) {
                    $no_ppb		    = $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                    $plant	        = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                    $tanggal	    = $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                    $kode_barang	= $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                    $jumlah         = $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                    $harga          = $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();
                    $tanggal_perlu  = $data_excel->getCellByColumnAndRow(6, $brs)->getFormattedValue();
                    $keterangan     = $data_excel->getCellByColumnAndRow(7, $brs)->getFormattedValue();
                    $perihal        = $data_excel->getCellByColumnAndRow(8, $brs)->getFormattedValue();
                    $jumlah_disetujui = $data_excel->getCellByColumnAndRow(9, $brs)->getFormattedValue();
                    $tipe_po        = $data_excel->getCellByColumnAndRow(10, $brs)->getFormattedValue();

                    if ((!$kode_barang || $kode_barang == "") && (!$no_ppb || $no_ppb == "")) {
                        break;
                    }

                    if (
                        !$no_ppb || $no_ppb == ""
                        || !$plant || $plant == ""
                        || !$tanggal || $tanggal == ""
                        || !$kode_barang || $kode_barang == ""
                        || !$jumlah || $jumlah == "" || !is_numeric($jumlah)
                        || !$harga || $harga == ""
                        // || ((!$jumlah_disetujui || $jumlah_disetujui == "" || !is_numeric($jumlah_disetujui)) && !in_array(strtoupper($tipe_po), ['HO', 'SITE']))
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data untuk barang $kode_barang pada No PPB $no_ppb tidak lengkap/valid.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    // if (!in_array($this->access_plant, [$plant])) {
                    //     $return = array('sts' => 'NotOk', 'msg' => "Tidak memiliki akses untuk plant $plant");

                    //     unlink($data['upload_data']['full_path']);
                    //     echo json_encode($return);
                    //     exit();
                    // }

                    $validasi_tanggal = $this->validasi_tanggal($tanggal);
                    if (!$validasi_tanggal) {
                        $msg 	= "Tanggal Tidak Valid pada Barang $kode_barang pada No PPB $no_ppb. Gunakan format 'dd.mm.yyyy'";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $tanggal = $this->generate->regenerateDateFormat($tanggal);
                    $tanggal_perlu = $this->generate->regenerateDateFormat($tanggal_perlu);

                    // cek kode barang
                    $barang = $this->dmaster->get_data_material_by_plant(array(
                        "connect" => false,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant,
                        "is_active" => 1
                    ));

                    if (!$barang || ($barang && $barang->classification == "")) {
                        $return = array('sts' => 'NotOk', 'msg' => "Barang dengan kode $kode_barang belum terdaftar untuk pabrik ini.");

                        if ($barang && $barang->classification == "") {
                            $return = array('sts' => 'NotOk', 'msg' => "Barang dengan kode $kode_barang belum dilakukan konfigurasi. Harap menghubungi Admin HO.");
                        }

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    //======Data Header======//
                    if($no_ppb != $current_ppb) {
                        // cek nomor ppb
                        $ck_data_header = $this->dtransaksi->get_ppb_header(array(
                            "connect" => false,
                            "no_ppb" => $no_ppb,
                            "plant" => $plant
                        ));

                        if ($ck_data_header) {
                            $msg 	= "No PPB $no_ppb sudah digunakan";
                            $sts 	= "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            unlink($data['upload_data']['full_path']);

                            echo json_encode($return);
                            exit();
                        } else {
                            $data_header	= array(
                                'no_ppb'                => $no_ppb,
                                'plant'				    => $plant,
                                'tanggal'				=> $tanggal,
                                'tanggal_diperlukan'	=> $tanggal_perlu,
                                "perihal"               => $perihal,
                                'tanggal_konfirmasi'	=> $tanggal,
                            );

                            $data_header = $this->dgeneral->basic_column("insert", $data_header);                          
                            $this->dgeneral->insert("tbl_ktp_ppb_header", $data_header);
                            $id_ppb = $this->db->insert_id();

                            $data_log = array(
                                "id_transaksi"  => $id_ppb,
                                "tipe"          => "ppb",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
                        }
                        $i = 1;
                        $list_barang = [];
                        $current_ppb = $no_ppb;
                    }

                    //======Data Detail======//
                    //cek double kode barang
                    // $cek_double = $this->dtransaksi->get_ppb_detail(array(
                    //     "connect" => false,
                    //     "kode_barang" => $kode_barang,
                    //     "plant" => $plant,
                    //     "id_ppb" => $id_ppb,
                    // ));

                    // if ($cek_double) {
                    if (in_array($kode_barang, $list_barang)){
                        $msg 	= "Barang $kode_barang pada No PPB $no_ppb double.";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    if (!in_array(strtoupper($tipe_po), ['HO', 'SITE', 'REJECT']))
                        $tipe_po = NULL;

                    $data_detail = array(
                        'id_ppb'                => $id_ppb,
                        'no_ppb'                => $no_ppb,
                        'no_detail'				=> $i,
                        'kode_barang'			=> $kode_barang,
                        'satuan'                => $barang->MEINS,
                        'jumlah'	            => $jumlah,
                        'harga'	                => $harga,
                        'keterangan'            => $keterangan,
                        'stok'                  => $barang->LABST,
                        'classification'        => $barang->classification,
                        "tipe_po"               => strtoupper($tipe_po),
                        "jumlah_disetujui"      => $jumlah_disetujui,
                    );
                    $data_detail = $this->dgeneral->basic_column("insert", $data_detail);                          
                    $this->dgeneral->insert("tbl_ktp_ppb_detail", $data_detail);
                    $i++;
                    $list_barang[] = $kode_barang;
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
                }
            }
        } else {
            $msg 	= "Silahkan pilih file yang ingin diunggah";
            $sts 	= "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

		echo json_encode($return);
		exit();
    }

    private function save_upload_po_ho($param = NULL)
    {
        $post 		= $this->input->post(NULL, TRUE);
		$datetime 	= date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
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
                // $title_desc		= $objPHPExcel->getProperties()->getTitle();
                $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
                $sheet_name     = $objWorksheet->getTitle();
                $data_excel		= $objPHPExcel->getActiveSheet();
                $highestRow 	= $data_excel->getHighestRow(); 
                $highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                $data_row		= array();

                if ($sheet_name != "POHO") {
                    $return = array('sts' => 'NotOk', 'msg' => "File yang diupload tidak sesuai.");

                    unlink($data['upload_data']['full_path']);
                    echo json_encode($return);
                    exit();
                }

                $this->dgeneral->begin_transaction();

                $current_po = "";
                $jenis_po = "";
                $current_ppb = "";
                $current_vendor = "";
                $current_tanggal = "";
                $id_po = "";
                $id_gr = "";
                $no_detail_po = 1;
                $no_detail_gr = 1;
                $total_diskon = 0;
                $ppn = "";
                $nilai_ppn = 0;
                $list_barang = array();
                
                for ($brs = 5; $brs <= $highestRow; $brs++) {
                    $no_po		    = $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                    $plant	        = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                    $tanggal	    = $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                    $kode_barang	= $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                    $vendor	        = $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                    $cost_center	= $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();
                    $jumlah         = $data_excel->getCellByColumnAndRow(6, $brs)->getFormattedValue();
                    $harga          = $data_excel->getCellByColumnAndRow(7, $brs)->getFormattedValue();
                    $diskon         = $data_excel->getCellByColumnAndRow(8, $brs)->getFormattedValue();
                    $ppn_item       = $data_excel->getCellByColumnAndRow(9, $brs)->getFormattedValue();
                    $no_ppb		    = $data_excel->getCellByColumnAndRow(10, $brs)->getFormattedValue();
                    $keterangan     = $data_excel->getCellByColumnAndRow(11, $brs)->getFormattedValue();

                    if ((!$no_po || $no_po == "") && (!$kode_barang || $kode_barang == "")) {
                        break;
                    }

                    if (
                        !$no_ppb || $no_ppb == ""
                        || !$no_po || $no_po == ""
                        || !$plant || $plant == ""
                        || !$tanggal || $tanggal == ""
                        || !$kode_barang || $kode_barang == ""
                        || !$vendor || $vendor == ""
                        // || !$cost_center || $cost_center == ""
                        || !$jumlah || $jumlah == "" || !is_numeric($jumlah)
                        || !$harga || $harga == "" || !is_numeric($harga)
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data untuk barang $kode_barang pada No TTG $no_po tidak lengkap/valid.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $validasi_tanggal = $this->validasi_tanggal($tanggal);
                    if (!$validasi_tanggal) {
                        $msg 	= "Tanggal Tidak Valid pada Barang $kode_barang pada No PO $no_po. Gunakan format 'dd.mm.yyyy'";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $tanggal = $this->generate->regenerateDateFormat($tanggal);
                    $vendor = str_pad($vendor,10,0, STR_PAD_LEFT);
                    $cost_center = str_pad($cost_center,10,0, STR_PAD_LEFT);

                    // cek PPB kode barang
                    $data_ppb = $this->dtransaksi->get_ppb_detail(array(
                        "connect" => false,
                        "no_ppb" => $no_ppb,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant,
                        "tipe_po" => 'HO',
                        "is_closed" => false
                    ));

                    if (!$data_ppb) {
                        $return = array('sts' => 'NotOk', 'msg' => "Barang dengan kode $kode_barang tidak termasuk dalam PPB $no_ppb.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    if (($data_ppb[0]->jumlah_po + $jumlah) > $data_ppb[0]->jumlah_disetujui) {
                        unlink($data['upload_data']['full_path']);

                        $return = array('sts' => 'NotOk', 'msg' => "PO untuk item $kode_barang pada No PO $no_po melebihi jumlah yang disetujui.");
                        echo json_encode($return);
                        exit();
                    }

                    // validasi vendor
                    $data_vendor = $this->dmaster->get_data_vendor(array(
                        "connect" => false,
                        "LIFNR" => $vendor,
                        "plant" => $plant,
                    ));

                    // validasi cost center
                    $data_cost_center = true;
                    if (in_array($data_ppb[0]->classification, ['A', 'K'])) {
                        if (!$cost_center || $cost_center == "") $data_cost_center = false;
                        else {
                            $data_cost_center = $this->dmaster->get_data_cost_center(array(
                                "connect" => false,
                                "code" => $cost_center,
                                "GSBER" => $plant,
                                "matnr" => $kode_barang,
                            ));
                        }
                    } else {
                        $cost_center = "";
                    }

                    if (!$data_vendor || !$data_cost_center) {
                        if (!$data_vendor) $text = "Kode Vendor $vendor tidak terdaftar untuk pabrik ini.";
                        else if (!$data_cost_center) $text = "Cost Center $cost_center belum terdaftar untuk item $kode_barang.";

                        $return = array('sts' => 'NotOk', 'msg' => $text);

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $id_ppb = $data_ppb[0]->id_ppb;

                    //======Data Header======//
                    if($no_po != $current_po) {
                        // cek nomor po
                        // $ck_data_header = $this->dtransaksi->get_po_header(array(
                        //     "connect" => false,
                        //     "no_ppb" => $no_ppb,
                        //     "plant" => $plant
                        // ));
                        
                        // if ($ck_data_header) {
                        //     $msg 	= "No PO $no_po sudah digunakan";
                        //     $sts 	= "NotOK";

                        //     $return = array('sts' => $sts, 'msg' => $msg);
                        //     unlink($data['upload_data']['full_path']);

                        //     echo json_encode($return);
                        //     exit();
                        // } else {
                            $data_header_po = array(
                                "id_ppb" => $id_ppb,
                                "no_ppb" => $no_ppb,
                                "plant"  => $plant,
                                "tanggal" => $tanggal,
                                "tipe_po" => "HO",
                                "vendor" => $vendor,
                                // "diskon" => $total_diskon,
                                // "ppn" => $ppn,
                            );

                            $data_header_po = $this->dgeneral->basic_column("insert", $data_header_po);
                            $this->dgeneral->insert("tbl_ktp_po_header", $data_header_po);
                            $id_po = $this->db->insert_id();

                            // $po_reff = "POS".$id_po;

                            // $data_header = array(
                            //     "po_reff" => $po_reff,
                            // );
                            // $data_header = $this->dgeneral->basic_column('update', $data_header, $datetime);
                            // $this->dgeneral->update("tbl_ktp_po_header", $data_header, array(
                            //     array(
                            //         'kolom' => 'id',
                            //         'value' => $id_po
                            //     )
                            // ));

                            //-----log PO------//
                            $data_log = array(
                                "id_transaksi"  => $id_po,
                                "tipe"          => "po",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);

                        // }
                        $no_detail_po = 1;
                        $no_detail_gr = 1;
                        $total_diskon = 0;
                        $ppn = "";
                        $nilai_ppn = 0;
                        $current_po = $no_po;
                        $current_ppb = $no_ppb;
                        $list_barang = [];
                        $jenis_po = $data_ppb[0]->classification;
                        $current_vendor = $vendor;
                        $current_tanggal = $tanggal;
                    }

                    //======Data Detail======//
                    if (in_array($kode_barang, $list_barang)){
                        $msg 	= "Barang $kode_barang pada No PO $no_po double.";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    /*--tidak boleh ada lebih dari satu tipe barang dan vendor dalam setiap ttg--*/
                    if (
                        $jenis_po != $data_ppb[0]->classification 
                        || $current_vendor != $vendor 
                        || $current_ppb != $no_ppb
                        || $current_tanggal != $tanggal
                    ) {
                        if ($jenis_po != $data_ppb[0]->classification) $tipe_error = "tipe barang";
                        else if ($current_vendor != $vendor) $tipe_error = "vendor";
                        else if ($current_ppb != $no_ppb) $tipe_error = "NO PPB";
                        else if ($current_tanggal != $tanggal) $tipe_error = "Tanggal";

                        $msg 	= "Terdapat lebih dari satu $tipe_error pada No PO $no_po .";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $data_detail_po = array(
                        'id_po'             => $id_po,
                        'no_detail'			=> $no_detail_po,
                        'no_detail_ppb'     => $data_ppb[0]->no_detail,
                        'id_ppb'            => $data_ppb[0]->id_ppb,
                        "kode_barang"       => $kode_barang,
                        "jumlah"            => (float) $jumlah,
                        "satuan"            => $data_ppb[0]->satuan,
                        "harga"             => (float) $harga,
                        "diskon"            => (float) $diskon,
                        "total"             => (float) (($jumlah*$harga) - $diskon),
                        "asset_class"       => $data_ppb[0]->asset_class,
                        "cost_center"       => $cost_center,
                        "gl_account"        => $data_ppb[0]->gl_account,
                        "classification"    => $data_ppb[0]->classification
                    );
                    $data_detail_po = $this->dgeneral->basic_column("insert", $data_detail_po);                          
                    $this->dgeneral->insert("tbl_ktp_po_detail", $data_detail_po);

                    //set diskon header
                    $total_diskon += $diskon;
                    //set ppn header
                    if ($ppn_item == 'B5') {
                        $ppn = $ppn_item;
                        $nilai_ppn = 10;
                    }

                    $data_header = array(
                        "diskon" => $total_diskon,
                        "ppn" => $ppn,
                        "nilai_ppn" => $nilai_ppn,
                    );
                    $data_header = $this->dgeneral->basic_column('update', $data_header, $datetime);
                    $this->dgeneral->update("tbl_ktp_po_header", $data_header, array(
                        array(
                            'kolom' => 'id',
                            'value' => $id_po
                        )
                    ));

                    $no_detail_po++;
                    $list_barang[] = $kode_barang;
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
                }
            }
        } else {
            $msg 	= "Silahkan pilih file yang ingin diunggah";
            $sts 	= "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

		echo json_encode($return);
		exit();
    }

    private function save_upload_po_site($param = NULL)
    {
        $post 		= $this->input->post(NULL, TRUE);
		$datetime 	= date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
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
                // $title_desc		= $objPHPExcel->getProperties()->getTitle();
                $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
                $sheet_name     = $objWorksheet->getTitle();
                $data_excel		= $objPHPExcel->getActiveSheet();
                $highestRow 	= $data_excel->getHighestRow(); 
                $highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                $data_row		= array();

                if ($sheet_name != "PO") {
                    $return = array('sts' => 'NotOk', 'msg' => "File yang diupload tidak sesuai.");

                    unlink($data['upload_data']['full_path']);
                    echo json_encode($return);
                    exit();
                }

                $this->dgeneral->begin_transaction();

                $current_ttg = "";
                $jenis_po = "";
                $current_ppb = "";
                $current_vendor = "";
                $id_po = "";
                $id_gr = "";
                $no_detail_po = 1;
                $no_detail_gr = 1;
                $total_diskon = 0;
                $ppn = "";
                $nilai_ppn = 0;
                $list_barang = array();
                
                for ($brs = 5; $brs <= $highestRow; $brs++) {
                    $no_ttg		    = $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                    $plant	        = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                    $tanggal	    = $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                    $kode_barang	= $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                    $vendor	        = $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                    $cost_center	= $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();
                    $jumlah         = $data_excel->getCellByColumnAndRow(6, $brs)->getFormattedValue();
                    $harga          = $data_excel->getCellByColumnAndRow(7, $brs)->getFormattedValue();
                    $diskon         = $data_excel->getCellByColumnAndRow(8, $brs)->getFormattedValue();
                    $ppn_item       = $data_excel->getCellByColumnAndRow(9, $brs)->getFormattedValue();
                    $sloc           = $data_excel->getCellByColumnAndRow(10, $brs)->getFormattedValue();
                    $no_ppb		    = $data_excel->getCellByColumnAndRow(11, $brs)->getFormattedValue();
                    $keterangan     = $data_excel->getCellByColumnAndRow(12, $brs)->getFormattedValue();

                    if ((!$no_ttg || $no_ttg == "") && (!$kode_barang || $kode_barang == "")) {
                        break;
                    }

                    if (
                        !$no_ppb || $no_ppb == ""
                        || !$no_ttg || $no_ttg == ""
                        || !$plant || $plant == ""
                        || !$tanggal || $tanggal == ""
                        || !$kode_barang || $kode_barang == ""
                        || !$vendor || $vendor == ""
                        // || !$cost_center || $cost_center == ""
                        || !$jumlah || $jumlah == "" || !is_numeric($jumlah)
                        || !$harga || $harga == "" || !is_numeric($harga)
                        || !$sloc || $sloc == ""
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data untuk barang $kode_barang pada No TTG $no_ttg tidak lengkap/valid.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $validasi_tanggal = $this->validasi_tanggal($tanggal);
                    if (!$validasi_tanggal) {
                        $msg 	= "Tanggal Tidak Valid pada Barang $kode_barang pada No TTG $no_ttg. Gunakan format 'dd.mm.yyyy'";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $tanggal = $this->generate->regenerateDateFormat($tanggal);
                    $vendor = str_pad($vendor,10,0, STR_PAD_LEFT);
                    $cost_center = str_pad($cost_center,10,0, STR_PAD_LEFT);

                    // cek PPB kode barang
                    $data_ppb = $this->dtransaksi->get_ppb_detail(array(
                        "connect" => false,
                        "no_ppb" => $no_ppb,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant,
                        "tipe_po" => 'SITE',
                        "is_closed" => false
                    ));

                    if (!$data_ppb) {
                        $return = array('sts' => 'NotOk', 'msg' => "Barang dengan kode $kode_barang tidak termasuk dalam PPB $no_ppb.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    if (($data_ppb[0]->jumlah_po + $jumlah) > $data_ppb[0]->jumlah_disetujui) {
                        unlink($data['upload_data']['full_path']);

                        $return = array('sts' => 'NotOk', 'msg' => "PO untuk item $kode_barang pada No TTG $no_ttg melebihi jumlah yang disetujui.");
                        echo json_encode($return);
                        exit();
                    }

                    // validasi vendor
                    $data_vendor = $this->dmaster->get_data_vendor(array(
                        "connect" => false,
                        "LIFNR" => $vendor,
                        "plant" => $plant,
                    ));

                    // validasi cost center
                    $data_cost_center = true;
                    if (in_array($data_ppb[0]->classification, ['A', 'K'])) {
                        if (!$cost_center || $cost_center == "") $data_cost_center = false;
                        else {
                            $data_cost_center = $this->dmaster->get_data_cost_center(array(
                                "connect" => false,
                                "code" => $cost_center,
                                "GSBER" => $plant,
                                "matnr" => $kode_barang,
                            ));
                        }
                    } else {
                        $cost_center = "";
                    }

                    if (!$data_vendor || !$data_cost_center) {
                        if (!$data_vendor) $text = "Kode Vendor $vendor tidak terdaftar untuk pabrik ini.";
                        else if (!$data_cost_center) $text = "Cost Center $cost_center belum terdaftar untuk item $kode_barang.";

                        $return = array('sts' => 'NotOk', 'msg' => $text);

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $id_ppb = $data_ppb[0]->id_ppb;

                    //======Data Header======//
                    if($no_ttg != $current_ttg) {
                        // cek nomor ttg
                        $ck_data_header = $this->dtransaksi->get_gr_header(array(
                            "connect" => false,
                            "no_gr" => $no_ttg,
                            "plant" => $plant
                        ));
                        
                        if ($ck_data_header) {
                            $msg 	= "No TTG $no_ttg sudah digunakan";
                            $sts 	= "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            unlink($data['upload_data']['full_path']);

                            echo json_encode($return);
                            exit();
                        } else {
                            $data_header_po = array(
                                "id_ppb" => 0,
                                // "no_ppb" => $no_ppb,
                                "plant"  => $plant,
                                "tanggal" => $tanggal,
                                "tipe_po" => "SITE",
                                "vendor" => $vendor,
                                // "diskon" => $total_diskon,
                                // "ppn" => $ppn,
                            );

                            $data_header_po = $this->dgeneral->basic_column("insert", $data_header_po);
                            $this->dgeneral->insert("tbl_ktp_po_header", $data_header_po);
                            $id_po = $this->db->insert_id();

                            $po_reff = "POS".$id_po;

                            $data_header = array(
                                "po_reff" => $po_reff,
                            );
                            $data_header = $this->dgeneral->basic_column('update', $data_header, $datetime);
                            $this->dgeneral->update("tbl_ktp_po_header", $data_header, array(
                                array(
                                    'kolom' => 'id',
                                    'value' => $id_po
                                )
                            ));

                            //-----log PO------//
                            $data_log = array(
                                "id_transaksi"  => $id_po,
                                "tipe"          => "po",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);

                            $data_header_gr = array(
                                'no_gr'         => $no_ttg,
                                'id_po'         => $id_po,
                                'plant'			=> $plant,
                                'tanggal'		=> $tanggal,
                                "po_reff"       => $po_reff,
                                "tipe_po"       => 'SITE',
                            );

                            $data_header_gr = $this->dgeneral->basic_column("insert", $data_header_gr);
                            $this->dgeneral->insert("tbl_ktp_gr_header", $data_header_gr);
                            $id_gr = $this->db->insert_id();

                            //-----log GR------//
                            $data_log = array(
                                "id_transaksi"  => $id_gr,
                                "tipe"          => "gr",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
                        }
                        $no_detail_po = 1;
                        $no_detail_gr = 1;
                        $total_diskon = 0;
                        $ppn = "";
                        $nilai_ppn = 0;
                        $current_ttg = $no_ttg;
                        $current_ppb = $no_ppb;
                        $list_barang = [];
                        $jenis_po = $data_ppb[0]->classification;
                        $current_vendor = $vendor;
                    }

                    //======Data Detail======//
                    if (in_array($kode_barang, $list_barang)){
                        $msg 	= "Barang $kode_barang pada No TTG $no_ttg double.";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    /*--tidak boleh ada lebih dari satu tipe barang dan vendor dalam setiap ttg--*/
                    if (
                        $jenis_po != $data_ppb[0]->classification 
                        || $current_vendor != $vendor 
                        // || $current_ppb != $no_ppb
                    ) {
                        if ($jenis_po != $data_ppb[0]->classification) $tipe_error = "tipe barang";
                        else if ($current_vendor != $vendor) $tipe_error = "vendor";
                        // else if ($current_ppb != $no_ppb) $tipe_error = "NO PPB";

                        $msg 	= "Terdapat lebih dari satu $tipe_error pada No TTG $no_ttg .";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $data_detail_po = array(
                        'id_po'             => $id_po,
                        'no_detail'			=> $no_detail_po,
                        'no_detail_ppb'     => $data_ppb[0]->no_detail,
                        'id_ppb'            => $data_ppb[0]->id_ppb,
                        "kode_barang"       => $kode_barang,
                        "jumlah"            => (float) $jumlah,
                        "satuan"            => $data_ppb[0]->satuan,
                        "harga"             => (float) $harga,
                        "diskon"            => (float) $diskon,
                        "total"             => (float) (($jumlah*$harga) - $diskon),
                        "asset_class"       => $data_ppb[0]->asset_class,
                        "cost_center"       => $cost_center,
                        "gl_account"        => $data_ppb[0]->gl_account,
                        "classification"    => $data_ppb[0]->classification
                    );
                    $data_detail_po = $this->dgeneral->basic_column("insert", $data_detail_po);                          
                    $this->dgeneral->insert("tbl_ktp_po_detail", $data_detail_po);

                    //set diskon header
                    $total_diskon += $diskon;
                    //set ppn header
                    if ($ppn_item == 'B5') {
                        $ppn = $ppn_item;
                        $nilai_ppn = 10;
                    }

                    $data_header = array(
                        "diskon" => $total_diskon,
                        "ppn" => $ppn,
                        "nilai_ppn" => $nilai_ppn,
                    );
                    $data_header = $this->dgeneral->basic_column('update', $data_header, $datetime);
                    $this->dgeneral->update("tbl_ktp_po_header", $data_header, array(
                        array(
                            'kolom' => 'id',
                            'value' => $id_po
                        )
                    ));

                    if ($data_ppb[0]->classification == "A") {
                        for ($i=0; $i < $jumlah; $i++) { 
                            $data_detail_gr	= array(
                                'id_gr'             => $id_gr,
                                'no_gr'             => $no_ttg,
                                'no_detail'			=> $no_detail_gr,
                                'kode_barang'		=> $kode_barang,
                                'satuan'            => $data_ppb[0]->satuan,
                                'jumlah'	        => 1,
                                'keterangan'        => $keterangan,
                                'sloc'              => $sloc,
                                'id_po'             => $id_po
                            );
                            $data_detail_gr = $this->dgeneral->basic_column("insert", $data_detail_gr);                          
                            $this->dgeneral->insert("tbl_ktp_gr_detail", $data_detail_gr);
                            $no_detail_gr++;
                        }
                    } else {
                        $data_detail_gr	= array(
                            'id_gr'             => $id_gr,
                            'no_gr'             => $no_ttg,
                            'no_detail'			=> $no_detail_gr,
                            'kode_barang'		=> $kode_barang,
                            'satuan'            => $data_ppb[0]->satuan,
                            'jumlah'	        => $jumlah,
                            'keterangan'        => $keterangan,
                            'sloc'              => $sloc,
                            'id_po'             => $id_po
                        );
                        $data_detail_gr = $this->dgeneral->basic_column("insert", $data_detail_gr);                          
                        $this->dgeneral->insert("tbl_ktp_gr_detail", $data_detail_gr);
                        $no_detail_gr++;
                    }
                    $no_detail_po++;
                    $list_barang[] = $kode_barang;
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
                }
            }
        } else {
            $msg 	= "Silahkan pilih file yang ingin diunggah";
            $sts 	= "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

		echo json_encode($return);
		exit();
    }

    private function save_upload_gr_ho($param = NULL)
    {
        $post 			= $this->input->post(NULL, TRUE);
		$datetime 	= date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
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
                // $title_desc		= $objPHPExcel->getProperties()->getTitle();
                $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
                $sheet_name     = $objWorksheet->getTitle();
                $data_excel		= $objPHPExcel->getActiveSheet();
                $highestRow 	= $data_excel->getHighestRow(); 
                $highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                $data_row		= array();

                if ($sheet_name != "GR") {
                    $return = array('sts' => 'NotOk', 'msg' => "File yang diupload tidak sesuai.");

                    unlink($data['upload_data']['full_path']);
                    echo json_encode($return);
                    exit();
                }

                $this->dgeneral->begin_transaction();

                $current_ttg = "";
                $current_po = "";
                $id_gr = "";
                $i = 1;
                $list_barang = array();
                
                for ($brs = 5; $brs <= $highestRow; $brs++) {
                    $no_ttg		    = $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                    $plant	        = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                    $tanggal	    = $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                    $no_po	        = $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                    $kode_barang	= $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                    $jumlah         = $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();
                    $keterangan     = $data_excel->getCellByColumnAndRow(6, $brs)->getFormattedValue();
                    $sloc           = $data_excel->getCellByColumnAndRow(7, $brs)->getFormattedValue();
                    if ((!$no_ttg || $no_ttg == "") && (!$kode_barang || $kode_barang == "")) {
                        break;
                    }

                    if (
                        !$no_po || $no_po == ""
                        || !$no_ttg || $no_ttg == ""
                        || !$plant || $plant == ""
                        || !$tanggal || $tanggal == ""
                        || !$kode_barang || $kode_barang == ""
                        || !$jumlah || $jumlah == "" || !is_numeric($jumlah)
                        || !$sloc || $sloc == ""
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data untuk barang $kode_barang pada No TTG $no_ttg tidak lengkap/valid.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $validasi_tanggal = $this->validasi_tanggal($tanggal);
                    if (!$validasi_tanggal) {
                        $msg 	= "Tanggal Tidak Valid pada Barang $kode_barang pada No TTG $no_ttg. Gunakan format 'dd.mm.yyyy'";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $tanggal = $this->generate->regenerateDateFormat($tanggal);

                    // cek PO kode barang
                    $data_po = $this->dtransaksi->get_po_detail_by_no_po(array(
                        "connect" => false,
                        "no_po" => $no_po,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant,
                        "tipe_po" => 'HO',
                    ));

                    if (!$data_po) {
                        $return = array('sts' => 'NotOk', 'msg' => "Barang dengan kode $kode_barang pada No TTG $no_ttg tidak termasuk dalam PO $no_po.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    // validasi jumlah po dan ttg
                    if (($data_po[0]->jumlah_gr + $jumlah) > $data_po[0]->jumlah) {
                        unlink($data['upload_data']['full_path']);

                        $return = array('sts' => 'NotOk', 'msg' => "TTG untuk item $kode_barang pada No TTG $no_ttg melebihi jumlah PO.");
                        echo json_encode($return);
                        exit();
                    }

                    // $id_po = $data_po[0]->id_po;
                    $id_po = 0;

                    //======Data Header======//
                    if($no_ttg != $current_ttg) {
                        // cek nomor ttg
                        $ck_data_header = $this->dtransaksi->get_gr_header(array(
                            "connect" => false,
                            "no_gr" => $no_ttg,
                            "plant" => $plant
                        ));
                        
                        if ($ck_data_header) {
                            $msg 	= "No TTG $no_ttg sudah digunakan";
                            $sts 	= "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            unlink($data['upload_data']['full_path']);

                            echo json_encode($return);
                            exit();
                        } else {
                            $data_header	= array(
                                'no_gr'     => $no_ttg,
                                'id_po'     => $id_po,
                                'plant'		=> $plant,
                                'tanggal'	=> $tanggal,
                                "po_reff"   => $no_po,
                                "no_po"     => $no_po,
                                "tipe_po"   => 'HO'
                            );

                            $data_header = $this->dgeneral->basic_column("insert", $data_header);                          
                            $this->dgeneral->insert("tbl_ktp_gr_header", $data_header);
                            $id_gr = $this->db->insert_id();

                            //-----log GR------//
                            $data_log = array(
                                "id_transaksi"  => $id_gr,
                                "tipe"          => "gr",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
                        }
                        $i = 1;
                        $current_ttg = $no_ttg;
                        $current_po = $no_po;
                        $list_barang = [];
                    }

                    //======Data Detail======//
                    if (in_array($kode_barang, $list_barang)){
                        $msg 	= "Barang $kode_barang pada No TTG $no_ttg double.";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                     /*--tidak boleh ada lebih dari satu tipe barang dan vendor dalam setiap ttg--*/
                     if (
                        $current_po != $no_po 
                    ) {
                        $msg 	= "Terdapat lebih dari satu NO PO pada No TTG $no_ttg .";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $jumlah_masuk = $jumlah;
                    $item = $this->dtransaksi->get_po_detail_by_line_item(array(
                        "connect" => false,
                        "no_po" => $no_po,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant,
                        "tipe_po" => 'HO',
                        "sisa" => true
                    ));

                    for ($j = 0; $j < count($item); $j++) {
                        if ($jumlah_masuk > 0) {
                            if ($jumlah_masuk < $item[$j]->sisa) {
                                $qty = $jumlah_masuk;
                                $jumlah_masuk = 0;
                            } else {
                                $qty = $item[$j]->sisa;
                                $jumlah_masuk -= $item[$j]->sisa;
                            }

                            if (($item[$j]->sisa - $qty) < 0) {
                                $return = array('sts' => "NotOK", 'msg' => "melebihi stok");
                                unlink($data['upload_data']['full_path']);

                                echo json_encode($return);
                                exit();
                            }

                            $data_detail	= array(
                                'id_gr'             => $id_gr,
                                'no_gr'             => $no_ttg,
                                'no_detail'			=> $i,
                                'kode_barang'		=> $kode_barang,
                                'satuan'            => $data_po[0]->satuan,
                                'jumlah'	        => $qty,
                                'keterangan'        => $keterangan,
                                'sloc'              => $sloc,
                                'id_po'             => $item[$j]->id_po,
                                'item_po'           => $item[$j]->no_item_po,
                            );
                            $data_detail = $this->dgeneral->basic_column("insert", $data_detail);                          
                            $this->dgeneral->insert("tbl_ktp_gr_detail", $data_detail);
                            $i++;
                        }
                    }
                    
                    $list_barang[] = $kode_barang;
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
                }
            }
        } else {
            $msg 	= "Silahkan pilih file yang ingin diunggah";
            $sts 	= "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

		echo json_encode($return);
		exit();
    }

    private function save_upload_gi($param = NULL)
    {
        $post 			= $this->input->post(NULL, TRUE);
		$datetime 	= date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
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
                // $title_desc		= $objPHPExcel->getProperties()->getTitle();
                $objWorksheet   = $objPHPExcel->setActiveSheetIndex(0);
                $sheet_name     = $objWorksheet->getTitle();
                $data_excel		= $objPHPExcel->getActiveSheet();
                $highestRow 	= $data_excel->getHighestRow(); 
                $highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
                $data_row		= array();

                if ($sheet_name != "GI") {
                    $return = array('sts' => 'NotOk', 'msg' => "File yang diupload tidak sesuai.");

                    unlink($data['upload_data']['full_path']);
                    echo json_encode($return);
                    exit();
                }

                $this->dgeneral->begin_transaction();

                $current_bkb = "";
                $current_tipe = "";
                $id_gi = "";
                $i = 1;
                $list_barang = array();
                
                for ($brs = 5; $brs <= $highestRow; $brs++) {
                    $no_bkb		    = $data_excel->getCellByColumnAndRow(0, $brs)->getFormattedValue();
                    $plant	        = $data_excel->getCellByColumnAndRow(1, $brs)->getFormattedValue();
                    $tanggal	    = $data_excel->getCellByColumnAndRow(2, $brs)->getFormattedValue();
                    $no_spb	        = $data_excel->getCellByColumnAndRow(3, $brs)->getFormattedValue();
                    $kode_barang	= $data_excel->getCellByColumnAndRow(4, $brs)->getFormattedValue();
                    $gl_account	    = $data_excel->getCellByColumnAndRow(5, $brs)->getFormattedValue();
                    $no_io          = $data_excel->getCellByColumnAndRow(6, $brs)->getFormattedValue();
                    $cost_center	= $data_excel->getCellByColumnAndRow(7, $brs)->getFormattedValue();
                    $afd	        = $data_excel->getCellByColumnAndRow(8, $brs)->getFormattedValue();
                    $blok	        = $data_excel->getCellByColumnAndRow(9, $brs)->getFormattedValue();
                    $kategori	    = $data_excel->getCellByColumnAndRow(10, $brs)->getFormattedValue();
                    $kode_vra	    = $data_excel->getCellByColumnAndRow(11, $brs)->getFormattedValue();
                    $no_polisi	    = $data_excel->getCellByColumnAndRow(12, $brs)->getFormattedValue();
                    $jumlah         = $data_excel->getCellByColumnAndRow(13, $brs)->getFormattedValue();
                    $keterangan     = $data_excel->getCellByColumnAndRow(14, $brs)->getFormattedValue();
                    $sloc           = $data_excel->getCellByColumnAndRow(15, $brs)->getFormattedValue();
                    
                    if ((!$no_bkb || $no_bkb == "") && (!$kode_barang || $kode_barang == "")) {
                        break;
                    }

                    if (!$gl_account || $gl_account == ""
                        || !$no_bkb || $no_bkb == ""
                        || !$tanggal || $tanggal == ""
                        || !$no_spb || $no_spb == ""
                        || !$plant || $plant == ""
                        || !$kode_barang || $kode_barang == ""
                        // || ((!$no_io || $no_io == "") && (!$cost_center || $cost_center == ""))
                        || !$jumlah || $jumlah == "" || !is_numeric($jumlah)
                    ) {
                        $return = array('sts' => 'NotOk', 'msg' => "Data untuk barang $kode_barang pada No BKB $no_bkb tidak lengkap/valid.");

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    $validasi_tanggal = $this->validasi_tanggal($tanggal);
                    if (!$validasi_tanggal) {
                        $msg 	= "Tanggal Tidak Valid pada Barang $kode_barang pada No BKB $no_bkb. Gunakan format 'dd.mm.yyyy'";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $tanggal = $this->generate->regenerateDateFormat($tanggal);
                    // $cost_center = str_pad($cost_center,10,0, STR_PAD_LEFT);
                    $gl_account = str_pad($gl_account,10,0, STR_PAD_LEFT);

                    // cek kode barang
                    $cek_barang = $this->dmaster->get_data_material_by_plant(array(
                        "connect" => false,
                        "kode_barang" => $kode_barang,
                        "plant" => $plant
                    ));

                    //======Tipe BKB======//
                    //gl_account + cost_center = tipe 1
                    if(($cost_center && $cost_center != "") && (!$no_io || $no_io == ""))
                        $tipe_bkb = 1;
                    //gl_account only
                    else if((!$cost_center && $cost_center == "") && (!$no_io || $no_io == ""))
                        $tipe_bkb = 2;
                    //gl_account + no io
                    else if($no_io || $no_io != "")
                        $tipe_bkb = 3;

                    // cek gl account
                    $cek_gl_account = $this->dmaster->get_data_gl_account(array(
                        "connect" => false,
                        "code" => $gl_account,
                        "GSBER" => $plant,
                    ));
                    
                    $cek_io = 1;
                    // cek io
                    if ($no_io && $no_io != "") {
                        $no_io = str_pad($no_io,12,0, STR_PAD_LEFT);
                        $cek_io = $this->dmaster->get_data_io(array(
                            "connect" => false,
                            "AUFNR" => $no_io,
                            "plant" => $plant,
                            "status" => "open"
                        ));
                    }

                    // cek cost center
                    $cek_cost_center = 1;
                    if ($cost_center && $cost_center != "") {
                        $cost_center = str_pad($cost_center,10,0, STR_PAD_LEFT);
                        $cek_cost_center = $this->dmaster->get_data_cost_center(array(
                            "connect" => false,
                            "code" => $cost_center,
                            "GSBER" => $plant,
                        ));
                    }

                    if (!$cek_barang || !$cek_gl_account || !$cek_cost_center || !$cek_io) {
                        if (!$cek_barang) $text = "Barang dengan kode $kode_barang belum terdaftar untuk pabrik ini.";
                        else if (!$cek_gl_account) $text = "COA $gl_account belum terdaftar untuk item pabrik ini.";
                        else if (!$cek_cost_center) $text = "Cost Center $cost_center belum terdaftar untuk item pabrik ini.";
                        else if (!$cek_io) $text = "IO $no_io tidak terdaftar / berstatus closed untuk pabrik ini.";

                        $return = array('sts' => 'NotOk', 'msg' => $text);

                        unlink($data['upload_data']['full_path']);
                        echo json_encode($return);
                        exit();
                    }

                    //======Data Header======//
                    if($no_bkb != $current_bkb) {
                        // cek nomor bkb
                        $ck_data_header = $this->dtransaksi->get_gi_header(array(
                            "connect" => false,
                            "no_gi" => $no_bkb,
                            "plant" => $plant
                        ));

                        if ($ck_data_header) {
                            $msg 	= "No BKB $no_bkb sudah digunakan";
                            $sts 	= "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            unlink($data['upload_data']['full_path']);

                            echo json_encode($return);
                            exit();
                        } else {
                            $data_header = array(
                                'no_gi'     => $no_bkb,
                                'plant'		=> $plant,
                                'tanggal'	=> $tanggal,
                                'afd'       => $afd,
                                'no_spb'    => $no_spb,
                            );

                            $data_header = $this->dgeneral->basic_column("insert", $data_header);                          
                            $this->dgeneral->insert("tbl_ktp_gi_header", $data_header);
                            $id_gi = $this->db->insert_id();

                            //-----log GI------//
                            $data_log = array(
                                "id_transaksi"  => $id_gi,
                                "tipe"          => "gi",
                                "tgl_status"    => $datetime,
                                // "status"        => "",
                                "action"        => 'submit'
                            );
                            $data_log = $this->dgeneral->basic_column('update', $data_log, $datetime);
                            $data_log['tanggal_edit'] = $datetime;
                            $this->dgeneral->insert('tbl_ktp_transaksi_log_status', $data_log);
                        }
                        $i = 1;
                        $current_bkb = $no_bkb;
                        $current_tipe = $tipe_bkb;
                        $list_barang = [];
                    }

                    //======Data Detail======//
                    if (in_array($kode_barang, $list_barang)){
                        $msg 	= "Barang $kode_barang pada No BKB $no_bkb double.";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    /*--tidak boleh ada lebih dari satu tipe bkb dalam setiap bkb--*/
                    if (
                        $current_tipe != $tipe_bkb 
                    ) {
                        $msg 	= "Terdapat lebih dari satu tipe bkb pada No BKB $no_bkb .";
                        $sts 	= "NotOK";

                        $return = array('sts' => $sts, 'msg' => $msg);
                        unlink($data['upload_data']['full_path']);

                        echo json_encode($return);
                        exit();
                    }

                    $data_detail = array(
                        'id_gi'             => $id_gi,
                        'no_gi'             => $no_bkb,
                        'no_detail'			=> $i,
                        'kode_barang'		=> $kode_barang,
                        'satuan'            => $cek_barang->MEINS,
                        'jumlah'	        => $jumlah,
                        'gl_account'	    => $gl_account,
                        'cost_center'	    => $cost_center,
                        // 'afd'	            => $afd,
                        'blok'              => $blok,
                        'kategori'	        => $kategori,
                        'kode_vra'	        => $kode_vra,
                        'no_polisi'	        => $no_polisi,
                        'keterangan'        => $keterangan,
                        'sloc'              => $sloc,
                        'no_io'             => $no_io,
                    );
                    $data_detail = $this->dgeneral->basic_column("insert", $data_detail);                          
                    $this->dgeneral->insert("tbl_ktp_gi_detail", $data_detail);
                    $i++;
                    $list_barang[] = $kode_barang;
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
                }
            }
        } else {
            $msg 	= "Silahkan pilih file yang ingin diunggah";
            $sts 	= "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

		echo json_encode($return);
		exit();
    }

    private function validasi_tanggal($date, $format = "d.m.Y")
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}