<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS Cuti & Ijin - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 *
 */
class Cutiijin extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
//        $this->general->check_access();
        $this->data['module'] = "Employee Self Service";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->library('form_validation');
        $this->load->library('less');
        $this->load->model('dbak');
        $this->load->model('dessgeneral');
        $this->load->model('dcutiijin');
    }

    public function index()
    {
        redirect(base_url('ess/cutiijin/pengajuan'));
    }

    public function saldo_all()
    {
        $this->load->library('PHPExcel');

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Jakarta');

        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Kiranaku")
            ->setLastModifiedBy("Kiranaku")
            ->setTitle("Saldo cuti karyawan all (" . date('d-m-Y') . ")")
            ->setSubject("Saldo cuti karyawan all (" . date('d-m-Y') . ")")
            ->setDescription("Saldo cuti karyawan all (" . date('d-m-Y') . ")")
            ->setCategory("Saldo cuti karyawan all");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'NIK');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'SALDO AWAL');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'PENGAJUAN');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'SALDO AKHIR');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E1', 'NEGATIF');

        $this->general->connectDbPortal();
        $karyawans = $this->dbak->get_karyawan();

        $baris = 1;
        foreach ($karyawans as $karyawan) {
            $baris++;
            $sisa_cuti = $this->less->get_cuti_sisa(null, $karyawan->nik);

            $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $karyawan->nik, PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $sisa_cuti['sisa_awal'], PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, $sisa_cuti['pengajuan'], PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $sisa_cuti['sisa'], PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('E' . $baris, $sisa_cuti['negatif'], PHPExcel_Cell_DataType::TYPE_STRING);
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Cuti_SAP');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Export Cuti HO to SAP (' . date('d-m-Y') . ').xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function pengajuan()
    {
        $this->general->check_access();
        $this->data['title'] = "Data Pengajuan Cuti Atau Ijin";

        // filter start
        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        if (isset($filter['form'])) {
            if ($filter['form'] == 'Semua')
                $form = array('Cuti', 'Ijin');
            else
                $form = $filter['form'];
        } else {
            $form = array('Cuti', 'Ijin');
            $filter['form'] = 'Semua';
        }

        if (isset($filter['id_cuti_status'])) {
            if ($filter['id_cuti_status'] == 'Semua')
                $id_cuti_status = array(ESS_CUTI_STATUS_DISETUJUI_HR, ESS_CUTI_STATUS_DITOLAK, ESS_CUTI_STATUS_DIBATALKAN);
            else
                $id_cuti_status = $filter['id_cuti_status'];
        } else {
            $id_cuti_status = array(ESS_CUTI_STATUS_DISETUJUI_HR, ESS_CUTI_STATUS_DITOLAK, ESS_CUTI_STATUS_DIBATALKAN);
            $filter['id_cuti_status'] = 'Semua';
        }
        // filter end
        $nik = base64_decode($this->session->userdata('-nik-'));
        $this->general->connectDbPortal();
        $sisa_cuti = $this->less->get_cuti_sisa(null, $nik);
        $this->general->closeDb();
        $tanggal_libur = $this->get_tanggal_libur();
        $tanggal_merah = $this->get_tanggal_merah();
        $jenis_ijin = $this->get_list_cuti_ijin();
        $tanggal_dinas = $this->get_tanggal_dinas();

        $tanggal_cuti = $this->get_tanggal_cuti();

        $atasan = $this->less->get_atasan();

        $this->data['jenis_ijin'] = $jenis_ijin;
        $this->data['tanggal_libur'] = $tanggal_libur;
        $this->data['tanggal_merah'] = $tanggal_merah;
        $this->data['tanggal_dinas'] = $tanggal_dinas;
        $this->data['tanggal_cuti'] = $tanggal_cuti;
        $this->data['atasan'] = $atasan;
        $this->data['sisa_cuti'] = $sisa_cuti;

        $this->data['tab_info'] = $this->load->view('cuti_ijin/_tab_info', array(
            'list_cuti_ijin' => $jenis_ijin
        ), true);
        $this->data['tab_history'] = $this->load->view('cuti_ijin/_tab_history', array(
            'list_history' => $this->get_list_history($filter, $tanggal_awal, $tanggal_akhir, $nik),
            'filter' => $filter,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'cuti_status' => $this->dessgeneral->get_cuti_status(
                array(
                    ESS_CUTI_STATUS_DISETUJUI_HR,
                    ESS_CUTI_STATUS_DITOLAK,
                    ESS_CUTI_STATUS_DIBATALKAN
                )
            )
        ), true);

        $list_saldo = $this->get_list_saldo($nik);

        $this->data['tab_pengajuan_cuti'] = $this->load->view('cuti_ijin/_tab_pengajuan_cuti', array(
            'list_pengajuan_cuti' => $this->get_list_pengajuan(array('Cuti'), array(ESS_CUTI_STATUS_MENUNGGU, ESS_CUTI_STATUS_DISETUJUI_ATASAN)),
            'sisa_cuti' => $sisa_cuti,
            'list_saldo' => $list_saldo
        ), true);

        $this->data['tab_pengajuan_ijin'] = $this->load->view('cuti_ijin/_tab_pengajuan_ijin', array(
            'list_pengajuan_ijin' => $this->get_list_pengajuan(array('Ijin'), array(ESS_CUTI_STATUS_MENUNGGU, ESS_CUTI_STATUS_DISETUJUI_ATASAN))
        ), true);

        $this->data['tab_saldo'] = $this->load->view('cuti_ijin/_tab_saldo', array(
            'list_saldo' => $list_saldo,
        ), true);

        $this->load->view('cuti_ijin/pengajuan', $this->data);
    }

    public function persetujuan()
    {
        $this->general->check_access();
        $this->data['title'] = "Persetujuan Cuti/ Ijin";

        // filter start
        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('-3 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
//            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
//            $tanggal_akhir = date('Y-m-d', strtotime($filter['tanggal_akhir']));
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        if (isset($filter['form'])) {
            if ($filter['form'] == 'Semua')
                $form = array('Cuti', 'Ijin');
            else
                $form = $filter['form'];
        } else {
            $form = array('Cuti', 'Ijin');
            $filter['form'] = 'Semua';
        }

        if (isset($filter['id_cuti_status'])) {
            if ($filter['id_cuti_status'] == 'Semua')
                $id_cuti_status = array(
                    ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                    ESS_CUTI_STATUS_DISETUJUI_HR,
                    ESS_CUTI_STATUS_DITOLAK
                );
            else
                $id_cuti_status = $filter['id_cuti_status'];
        } else {
            $id_cuti_status = array(
                ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                ESS_CUTI_STATUS_DISETUJUI_HR,
                ESS_CUTI_STATUS_DITOLAK
            );
            $filter['id_cuti_status'] = 'Semua';
        }
        // filter end

        $nik = base64_decode($this->session->userdata('-nik-'));

        $tanggal_libur = $this->get_tanggal_libur();

        $this->general->connectDbPortal();

        $list_history = $this->dcutiijin->get_cuti(
            array(
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'tipe' => $form,
                'id_tipe_status' => $id_cuti_status,
                'atasan' => $nik
            )
        );

        $list_data_cuti = $this->dcutiijin->get_cuti(
            array(
                'tipe' => 'Cuti',
                'id_tipe_status' => array(ESS_CUTI_STATUS_MENUNGGU),
                'atasan' => $nik
            )
        );

        $list_data_ijin = $this->dcutiijin->get_cuti(
            array(
                'tipe' => 'Ijin',
                'id_tipe_status' => array(ESS_CUTI_STATUS_MENUNGGU),
                'atasan' => $nik
            )
        );

        foreach ($list_history as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            if (!empty($list->gambar)) {
                $data_image = site_url(
                    'assets/file/ess/' .
                    $list->gambar
                );

                $headers = get_headers($data_image);
                if ($headers[0] != "HTTP/1.1 200 OK") {
                    $data_image = "http://10.0.0.18/home/" . $list->gambar;
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $list->gambar = $data_image;
                    } else
                        $list->gambar = null;
                } else
                    $list->gambar = $data_image;
            } else
                $list->gambar = null;
            $list_history[$index] = $list;
        }
        foreach ($list_data_cuti as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            if (!empty($list->gambar)) {
                $data_image = site_url(
                    'assets/file/ess/' .
                    $list->gambar
                );

                $headers = get_headers($data_image);
                if ($headers[0] != "HTTP/1.1 200 OK") {
                    $data_image = "http://10.0.0.18/home/" . $list->gambar;
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $list->gambar = $data_image;
                    } else
                        $list->gambar = null;
                } else
                    $list->gambar = $data_image;
            } else
                $list->gambar = null;
            $list->saldo = $this->less->get_cuti_sisa(null, $list->nik);
            $list_data_cuti[$index] = $list;
        }
        foreach ($list_data_ijin as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            if (!empty($list->gambar)) {
                $data_image = site_url(
                    'assets/file/ess/' .
                    $list->gambar
                );

                $headers = get_headers($data_image);
                if ($headers[0] != "HTTP/1.1 200 OK") {
                    $data_image = "http://10.0.0.18/home/" . $list->gambar;
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $list->gambar = $data_image;
                    } else
                        $list->gambar = null;
                } else
                    $list->gambar = $data_image;
            } else
                $list->gambar = null;
            $list_data_ijin[$index] = $list;
        }
        $this->data['tab_persetujuan_cuti'] = $this->load->view('cuti_ijin/_tab_persetujuan_cuti', array(
            'list_data_cuti' => $list_data_cuti,
            'tanggal_libur' => $tanggal_libur,
            'searchNik' => $this->input->get('nik')
        ), true);
        $this->data['tab_persetujuan_ijin'] = $this->load->view('cuti_ijin/_tab_persetujuan_ijin', array(
            'list_data_ijin' => $list_data_ijin,
            'tanggal_libur' => $tanggal_libur,
            'searchNik' => $this->input->get('nik')
        ), true);

        $this->data['tab_history'] = $this->load->view('cuti_ijin/_tab_history_persetujuan', array(
            'list_history' => $list_history,
            'filter' => $filter,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'cuti_status' => $this->dessgeneral->get_cuti_status(array(2, 3, 4))
        ), true);

        $this->general->closeDb();

        $this->load->view('cuti_ijin/persetujuan', $this->data);
    }

    public function persetujuan_hr()
    {
        $this->general->check_access();
        $this->data['title'] = "Persetujuan Cuti/ Ijin HROGA";

        // filter start
        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('-3 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
//            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
//            $tanggal_akhir = date('Y-m-d', strtotime($filter['tanggal_akhir']));
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        if (isset($filter['form'])) {
            if ($filter['form'] == 'Semua')
                $form = array('Cuti', 'Ijin');
            else
                $form = $filter['form'];
        } else {
            $form = array('Cuti', 'Ijin');
            $filter['form'] = 'Semua';
        }

        if (isset($filter['id_cuti_status'])) {
            if ($filter['id_cuti_status'] == 'Semua')
                $id_cuti_status = array(
                    ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                    ESS_CUTI_STATUS_DISETUJUI_HR,
                    ESS_CUTI_STATUS_DITOLAK
                );
            else
                $id_cuti_status = $filter['id_cuti_status'];
        } else {
            $id_cuti_status = array(
                ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                ESS_CUTI_STATUS_DISETUJUI_HR,
                ESS_CUTI_STATUS_DITOLAK
            );
            $filter['id_cuti_status'] = 'Semua';
        }
        // filter end

        $nik = base64_decode($this->session->userdata('-nik-'));

        $tanggal_libur = $this->get_tanggal_libur();

        $this->general->connectDbPortal();

        $list_history = $this->dcutiijin->get_cuti(
            array(
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'tipe' => $form,
                'id_tipe_status' => $id_cuti_status
            )
        );

        $list_data_cuti = $this->dcutiijin->get_cuti(
            array(
                'tipe' => 'Cuti',
                'id_tipe_status' => array(ESS_CUTI_STATUS_MENUNGGU)
            )
        );

        $list_data_ijin = $this->dcutiijin->get_cuti(
            array(
                'tipe' => 'Ijin',
                'id_tipe_status' => array(ESS_CUTI_STATUS_MENUNGGU)
            )
        );

        foreach ($list_history as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            if (!empty($list->gambar)) {
                $data_image = site_url(
                    'assets/file/ess/' .
                    $list->gambar
                );

                $headers = get_headers($data_image);
                if ($headers[0] != "HTTP/1.1 200 OK") {
                    $data_image = "http://10.0.0.18/home/" . $list->gambar;
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $list->gambar = $data_image;
                    } else
                        $list->gambar = null;
                } else
                    $list->gambar = $data_image;
            } else
                $list->gambar = null;
            $list_history[$index] = $list;
        }
        foreach ($list_data_cuti as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            $list->saldo = $this->less->get_cuti_sisa(null, $list->nik);
            $list_data_cuti[$index] = $list;
        }
        foreach ($list_data_ijin as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            if (!empty($list->gambar)) {
                $data_image = site_url(
                    'assets/file/ess/' .
                    $list->gambar
                );

                $headers = get_headers($data_image);
                if ($headers[0] != "HTTP/1.1 200 OK") {
                    $data_image = "http://10.0.0.18/home/" . $list->gambar;
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $list->gambar = $data_image;
                    } else
                        $list->gambar = null;
                } else
                    $list->gambar = $data_image;
            } else
                $list->gambar = null;
            $list_data_ijin[$index] = $list;
        }
        $this->data['tab_persetujuan_cuti'] = $this->load->view('cuti_ijin/_tab_persetujuan_cuti', array(
            'list_data_cuti' => $list_data_cuti,
            'tanggal_libur' => $tanggal_libur,
        ), true);
        $this->data['tab_persetujuan_ijin'] = $this->load->view('cuti_ijin/_tab_persetujuan_ijin', array(
            'list_data_ijin' => $list_data_ijin,
            'tanggal_libur' => $tanggal_libur,
        ), true);

        $this->data['tab_history'] = $this->load->view('cuti_ijin/_tab_history_persetujuan', array(
            'list_history' => $list_history,
            'filter' => $filter,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'cuti_status' => $this->dessgeneral->get_cuti_status(array(2, 3, 4))
        ), true);

        $this->general->closeDb();

        $this->load->view('cuti_ijin/persetujuan', $this->data);
    }

    public function saldocuti()
    {
        $this->general->check_access();
        $this->data['title'] = "Saldo Cuti Karyawan";

        $this->general->connectDbPortal();

        $karyawans = $this->dessgeneral->get_karyawans(
            base64_decode($this->session->userdata('-id_karyawan-')),
            true,
            base64_decode($this->session->userdata('-id_departemen-')),
            base64_decode($this->session->userdata('-id_divisi-')),
            base64_decode($this->session->userdata('-id_direktorat-')),
            base64_decode($this->session->userdata('-id_level-'))
        );

        foreach ($karyawans as $index => $karyawan) {
            $karyawan->sisa_cuti = $this->less->get_cuti_sisa(null, $karyawan->nik);
            $karyawan->saldo_cuti = $this->dessgeneral->get_saldo_nik($karyawan->nik);
            $karyawans[$index] = $karyawan;
        }

        $this->general->closeDb();

        $this->data['karyawans'] = $karyawans;

        $this->load->view('cuti_ijin/saldo_cuti', $this->data);
    }

    public function sapho()
    {
        $this->general->check_access();
        $this->data['title'] = "Proses SAP Cuti/ Ijin (HO)";

        $list_history = $this->dcutiijin->get_cuti(
            array(
                'tipe' => array('Cuti', 'Ijin'),
                'id_tipe_status' => array(ESS_CUTI_STATUS_DISETUJUI_HR, ESS_CUTI_STATUS_DIBATALKAN),
                'ho' => true
            )
        );

        $list_proses = $this->dcutiijin->get_cuti(
            array(
                'tipe' => array('Cuti', 'Ijin'),
                'id_tipe_status' => array(ESS_CUTI_STATUS_DISETUJUI_ATASAN),
                'ho' => true
            )
        );

        foreach ($list_history as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            $list_history[$index] = $list;
        }
        foreach ($list_proses as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            $list_proses[$index] = $list;
        }

        $this->data['tab_history'] = $this->load->view('cuti_ijin/_tab_history_sapho', array(
            'list_history' => $list_history
        ), true);

        $this->data['tab_proses'] = $this->load->view('cuti_ijin/_tab_proses_sapho', array(
            'list_proses' => $list_proses,
            'lokasi' => 'ho'
        ), true);

        $this->load->view('cuti_ijin/sap_cuti_ho', $this->data);
    }

    public function sappabrik($level = 'kasi')
    {
        $this->general->check_access();
        $managerUp = $level == 'mg';

        if ($managerUp)
            $this->data['title'] = "Proses SAP Cuti/ Ijin (Pabrik Mg Up)";
        else
            $this->data['title'] = "Proses SAP Cuti/ Ijin (Pabrik Kasie Down)";

        $list_history = $this->dcutiijin->get_cuti(
            array(
                'tipe' => array('Cuti', 'Ijin'),
                'id_tipe_status' => array(ESS_CUTI_STATUS_DISETUJUI_HR, ESS_CUTI_STATUS_DIBATALKAN),
                'ho' => false,
                'manager' => $managerUp
            )
        );

        $list_proses = $this->dcutiijin->get_cuti(
            array(
                'tipe' => array('Cuti', 'Ijin'),
                'id_tipe_status' => array(ESS_CUTI_STATUS_DISETUJUI_ATASAN),
                'ho' => false,
                'manager' => $managerUp
            )
        );

        foreach ($list_history as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            $list_history[$index] = $list;
        }
        foreach ($list_proses as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            $list_proses[$index] = $list;
        }

        $this->data['tab_history'] = $this->load->view('cuti_ijin/_tab_history_sappabrik', array(
            'list_history' => $list_history
        ), true);

        $this->data['tab_proses'] = $this->load->view('cuti_ijin/_tab_proses_sappabrik', array(
            'list_proses' => $list_proses,
            'lokasi' => 'pabrik',
            'manager' => $managerUp
        ), true);

        $this->load->view('cuti_ijin/sap_cuti_pabrik', $this->data);
    }

    public function laporancuti()
    {
        $this->general->check_access();
        $this->data['title'] = "Laporan Cuti Karyawan";

        $filter = $this->input->post();

        $tanggal_awal = date('Y-m-d', strtotime('-1 month'));
        $tanggal_akhir = date('Y-m-d');

        if (isset($filter['tanggal_awal']))
//            $tanggal_awal = date('Y-m-d', strtotime($filter['tanggal_awal']));
            $tanggal_awal = date_create($filter['tanggal_awal'])->format('Y-m-d');

        if (isset($filter['tanggal_akhir']))
//            $tanggal_akhir = date('Y-m-d', strtotime($filter['tanggal_akhir']));
            $tanggal_akhir = date_create($filter['tanggal_akhir'])->format('Y-m-d');

        if (isset($filter['form'])) {
            if ($filter['form'] == 'Semua')
                $form = array('Cuti', 'Ijin');
            else
                $form = $filter['form'];
        } else {
            $form = array('Cuti', 'Ijin');
            $filter['form'] = 'Semua';
        }

        if (isset($filter['id_cuti_status'])) {
            if ($filter['id_cuti_status'] == 'Semua')
                $id_cuti_status = null;
            else
                $id_cuti_status = $filter['id_cuti_status'];
        } else {
            $id_cuti_status = null;
            $filter['id_cuti_status'] = 'Semua';
        }

        $this->general->connectDbPortal();

        $list_cuti = $this->dcutiijin->get_cuti(
            array(
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'tipe' => $form,
                'id_tipe_status' => $id_cuti_status,
                'ho' => true
            )
        );

        $cuti_status = $this->dessgeneral->get_cuti_status();

        $this->general->closeDb();

        foreach ($list_cuti as $index => $list) {
            $list->enId = $this->generate->kirana_encrypt($list->id_cuti);
            $list_cuti[$index] = $list;
        }
        $this->data['cuti_status'] = $cuti_status;
        $this->data['list_cuti'] = $list_cuti;
        $this->data['filter'] = $filter;
        $this->data['tanggal_awal'] = $tanggal_awal;
        $this->data['tanggal_akhir'] = $tanggal_akhir;

        $this->load->view('cuti_ijin/laporan_cuti', $this->data);
    }

    private function get_list_cuti_ijin()
    {
        $this->general->connectDbPortal();

        $user = $this->data['user'];

        $karyawan = $this->dessgeneral->get_karyawan($user->id_karyawan);

        $cuti_ijin = $this->dessgeneral->get_cuti_ijin(
            array(
                'kode_grouping_area' => $karyawan->moabw
            )
        );

        $this->general->closeDb();

        foreach ($cuti_ijin as $index => $ijin) {
            // declare virtual variable cetak & jarak
            $ijin->jumlah_label = "-";
            $ijin->cetak = true;
            $ijin->jarak = 0;
            if ($ijin->jumlah < 90)
                $ijin->jumlah_label = $ijin->jumlah . " Hari";

            if ($ijin->kode == "0250" || $ijin->kode == "0520")
                $ijin->jumlah_label = '-';

            if ($ijin->kode == "0610")
                $ijin->cetak = false;

            if (in_array($ijin->kode, array('0310', '0320', '0330', '0340', '0350')))
                $ijin->jarak = true;

            $cuti_ijin[$index] = $ijin;
        }
        return $cuti_ijin;
    }

    private function get_list_pengajuan($tipe = array('Cuti', 'Ijin'), $status = array(ESS_CUTI_STATUS_MENUNGGU, ESS_CUTI_STATUS_DISETUJUI_ATASAN), $limit = 0)
    {

        $result = array();

        $this->general->connectDbPortal();

        $result = $this->dcutiijin->get_cuti(
            array(
                'tipe' => $tipe,
                'id_tipe_status' => $status,
                'nik' => base64_decode($this->session->userdata('-nik-')),
                'limit' => $limit,
            )
        );

        $this->general->closeDb();

        foreach ($result as $index => $pengajuan) {
            $pengajuan->enId = $this->generate->kirana_encrypt($pengajuan->id_cuti);
            if (!empty($pengajuan->gambar)) {
                $data_image = site_url(
                    'assets/file/ess/' .
                    $pengajuan->gambar
                );

                $headers = get_headers($data_image);
                if ($headers[0] != "HTTP/1.1 200 OK") {
                    $data_image = "http://10.0.0.18/home/" . $pengajuan->gambar;
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $pengajuan->gambar = $data_image;
                    } else
                        $pengajuan->gambar = null;
                } else
                    $pengajuan->gambar = $data_image;
            } else
                $pengajuan->gambar = null;


            $result[$index] = $pengajuan;
        }

        return $result;
    }

    private function get_list_history($filter, $tanggal_awal, $tanggal_akhir, $nik = null)
    {
        // filter manipulation start

        if (isset($filter['form'])) {
            if ($filter['form'] == 'Semua')
                $form = array('Cuti', 'Ijin');
            else
                $form = $filter['form'];
        } else {
            $form = array('Cuti', 'Ijin');
            $filter['form'] = 'Semua';
        }

        if (isset($filter['id_cuti_status'])) {
            if ($filter['id_cuti_status'] == 'Semua')
                $id_cuti_status = array(ESS_CUTI_STATUS_DISETUJUI_HR, ESS_CUTI_STATUS_DITOLAK, ESS_CUTI_STATUS_DIBATALKAN);
            else
                $id_cuti_status = $filter['id_cuti_status'];
        } else {
            $id_cuti_status = array(ESS_CUTI_STATUS_DISETUJUI_HR, ESS_CUTI_STATUS_DITOLAK, ESS_CUTI_STATUS_DIBATALKAN);
            $filter['id_cuti_status'] = 'Semua';
        }

        // filter manipulation end

        $this->general->connectDbPortal();

        $result = $this->dcutiijin->get_cuti(
            array(
                'tanggal_awal' => $tanggal_awal,
                'tanggal_akhir' => $tanggal_akhir,
                'tipe' => $form,
                'id_tipe_status' => $id_cuti_status,
                'nik' => $nik
            )
        );

        foreach ($result as $index => $history) {
            $history->enId = $this->generate->kirana_encrypt($history->id_cuti);
            $result[$index] = $history;
        }

        $this->general->closeDb();

        return $result;
    }

    private function get_list_saldo($nik = null)
    {
        $result = array();

        $total_pengajuan = 0;

        $result = $this->dessgeneral->get_saldo_nik($nik);

        $pengajuan_menunggu = $this->dcutiijin->get_cuti(
            array(
                'tipe' => array('Cuti'),
                'id_tipe_status' => array(ESS_CUTI_STATUS_MENUNGGU, ESS_CUTI_STATUS_DISETUJUI_ATASAN),
                'nik' => $nik
            )
        );

        // perhitungan total pengajuan

        foreach ($pengajuan_menunggu as $pengajuan) {
            $total_pengajuan += $pengajuan->jumlah;
        }

        $sisa_total_pengajuan = $total_pengajuan;

        // perhitungan saldo total cuti dikurangi total pengajuan

        foreach ($result as $i => $saldo) {
            if ($saldo->sisa > 0) {
                if ($saldo->sisa >= $sisa_total_pengajuan) {
                    $saldo->pengajuan = $sisa_total_pengajuan;
                } else {
                    $saldo->pengajuan = $saldo->sisa;
                }
                if ($sisa_total_pengajuan > 0)
                    $sisa_total_pengajuan = $sisa_total_pengajuan - $saldo->pengajuan;

            } else {
                $saldo->pengajuan = 0;
            }
            $result[$i] = $saldo;
        }

        if ($sisa_total_pengajuan > 0) {
            $cuti_tahunan = array_filter($result, function ($cuti) {
                if ($cuti->kode == ESS_CUTI_TAHUNAN)
                    return true;
                else
                    return false;
            });

            usort($cuti_tahunan, function ($cuti1, $cuti2) {
                if (strtotime($cuti1->tanggal_akhir) < strtotime($cuti2->tanggal_akhir))
                    return 1;
                else if (strtotime($cuti1->tanggal_akhir) > strtotime($cuti2->tanggal_akhir))
                    return -1;
                else
                    return 0;
            });

            foreach ($cuti_tahunan as $i => $cuti) {
                if ($cuti->batas_negatif < 0 && $sisa_total_pengajuan > 0) {
                    $cuti->pengajuan += $sisa_total_pengajuan;
                    $sisa_total_pengajuan = 0;
                }
                $result[$i] = $cuti;
            }

            usort($result, function ($cuti1, $cuti2) {
                if (strtotime($cuti1->tanggal_akhir) > strtotime($cuti2->tanggal_akhir))
                    return 1;
                else if (strtotime($cuti1->tanggal_akhir) < strtotime($cuti2->tanggal_akhir))
                    return -1;
                else
                    return 0;
            });
        }
        return $result;
    }

    private function get_tanggal_libur($nik = null)
    {
        $tanggal_libur = array();

        if (!isset($nik))
            $nik = base64_decode($this->session->userdata('-nik-'));

        $this->general->connectDbDefault();

        $dates = $this->dessgeneral->get_libur($nik);

        $this->general->closeDb();

        foreach ($dates as $date) {
            $tanggal_libur[] = $date['tanggal'];
        }

        return $tanggal_libur;
    }

    private function get_tanggal_merah($nik = null)
    {
        $tanggal_libur = array();

        if (!isset($nik))
            $nik = base64_decode($this->session->userdata('-nik-'));

        $this->general->connectDbDefault();

        /** filter flag “02”, Start Time dan End Time adalah yang kosong **/
        $dates = $this->dessgeneral->get_libur($nik, null, null, true);

        $this->general->closeDb();

        foreach ($dates as $date) {
            $tanggal_libur[] = $date['tanggal'];
        }

        return $tanggal_libur;
    }

    private function get_tanggal_dinas()
    {
        $tanggal_dinas = array();

        $this->general->connectDbPortal();

        $dates = $this->dessgeneral->get_dinas(base64_decode($this->session->userdata('-nik-')));

        foreach ($dates as $date) {
            if (date_create($date->tanggal_masuk) < date_create($date->tanggal_absen))
                $tanggal_dinas[] = $date->tanggal_masuk;
            else
                $tanggal_dinas[] = $date->tanggal_absen;
        }

        return $tanggal_dinas;
    }

    private function get_tanggal_cuti($tanggal_awal = null, $tanggal_akhir = null, $id_exclude = null, $id = null)
    {
        $tanggal_cuti = array();

        $tanggal_libur = $this->get_tanggal_libur();

        $nik = base64_decode($this->session->userdata('-nik-'));

        $this->general->connectDbPortal();

        if (isset($id))
            $cutis = $this->dcutiijin->get_cuti(
                array(
                    'id' => $id,
                    'tipe' => array('Cuti', 'Ijin'),
                    'id_tipe_status' => array(
                        ESS_CUTI_STATUS_MENUNGGU,
                        ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                        ESS_CUTI_STATUS_DISETUJUI_HR,
                        ESS_CUTI_STATUS_DITOLAK
                    )
                )
            );
        else
            $cutis = $this->dcutiijin->get_cuti(
                array(
                    'id' => $id,
                    'tanggal_awal' => array($tanggal_awal, $tanggal_akhir),
                    'tipe' => array('Cuti', 'Ijin'),
                    'id_tipe_status' => array(
                        ESS_CUTI_STATUS_MENUNGGU,
                        ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                        ESS_CUTI_STATUS_DISETUJUI_HR
                    ),
                    'nik' => $nik
                )
            );

        $excludes = array();

        if (isset($id_exclude) && !empty($id_exclude)) {
            $excludeDatas = $this->dcutiijin->get_cuti(
                array(
                    'id' => $id_exclude,
                    'tanggal_awal' => array($tanggal_awal, $tanggal_akhir),
                    'tipe' => array('Cuti', 'Ijin'),
                    'id_tipe_status' => array(
                        ESS_CUTI_STATUS_MENUNGGU, ESS_CUTI_STATUS_DISETUJUI_ATASAN, ESS_CUTI_STATUS_DISETUJUI_HR
                    ),
                    'nik' => $nik
                )
            );

            foreach ($excludeDatas as $index => $excludeData) {
                $begin = new DateTime($excludeData->tanggal_awal);
                $end = new DateTime($excludeData->tanggal_akhir);
                $end->setTime(0, 0, 1);

                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                foreach ($period as $dt) {
                    if (!in_array($dt->format('Y-m-d'), $tanggal_libur))
                        $excludes[] = $dt->format("Y-m-d");
                }
            }
        }

        foreach ($cutis as $cuti) {

            $begin = new DateTime($cuti->tanggal_awal);
            $end = new DateTime($cuti->tanggal_akhir);
            $end->setTime(0, 0, 1);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            foreach ($period as $dt) {
                if (!in_array($dt->format('Y-m-d'), $tanggal_libur) && !in_array($dt->format('Y-m-d'), $excludes))
                    $tanggal_cuti[] = $dt->format("Y-m-d");
            }
        }

        return $tanggal_cuti;
    }

    public function cek_periode_cuti($tanggal_awal = null, $tanggal_akhir = null, $id_exclude = null, $id = null)
    {
        $tanggal_libur = $this->get_tanggal_libur();

        $this->general->connectDbPortal();
        $cuti_cuti = $this->dessgeneral->get_cuti_cuti(array(
            'nik' => $this->data['user']->nik
        ));

        $excludes = array();
        $tanggal_cuti = array();

        if (isset($id_exclude) && !empty($id_exclude)) {
            $excludeDatas = $this->dcutiijin->get_cuti(
                array(
                    'id' => $id_exclude,
                    'tanggal_awal' => array($tanggal_awal, $tanggal_akhir),
                    'tipe' => array('Cuti', 'Ijin'),
                    'id_tipe_status' => array(
                        ESS_CUTI_STATUS_MENUNGGU, ESS_CUTI_STATUS_DISETUJUI_ATASAN, ESS_CUTI_STATUS_DISETUJUI_HR
                    ),
                    'nik' => $this->data['user']->nik
                )
            );

            foreach ($excludeDatas as $index => $excludeData) {
                $begin = new DateTime($excludeData->tanggal_awal);
                $end = new DateTime($excludeData->tanggal_akhir);
                $end->setTime(0, 0, 1);

                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                foreach ($period as $dt) {
                    if (!in_array($dt->format('Y-m-d'), $tanggal_libur))
                        $excludes[] = $dt->format("Y-m-d");
                }
            }
        }

        $tanggalOutPeriode = array();

        $begin = new DateTime($tanggal_awal);
        $end = new DateTime($tanggal_akhir);
        $end->setTime(0, 0, 1);

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        foreach ($period as $dt) {
            if (!in_array($dt->format('Y-m-d'), $tanggal_libur) && !in_array($dt->format('Y-m-d'), $excludes))
                $tanggal_cuti[] = $dt->format("Y-m-d");
        }

        foreach ($tanggal_cuti as $tanggal) {
            $outPeriode = true;
            foreach ($cuti_cuti as $periode) {
                $periodeAwal = date_create($periode->tanggal_awal);
                $periodeAkhir = date_create($periode->tanggal_akhir);
                $periodeAkhir->setTime(0, 0, 1);
                $tanggalCek = date_create($tanggal);
                if ($periodeAwal <= $tanggalCek && $tanggalCek <= $periodeAkhir) {
                    $outPeriode = false;
                    break;
                }
            }
            if ($outPeriode) {
                $tanggalOutPeriode[] = $tanggal;
            }
        }

        foreach ($tanggalOutPeriode as $tanggal) {
            $this->general->connectDbPortal();
            $sisa = $this->less->get_cuti_sisa($tanggal, $this->data['user']->nik);
            if ($sisa['sisa'] - $sisa['negatif'] > 0) {
                unset($tanggal);
            }
        }

        return $tanggalOutPeriode;
    }

    public function save($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'pengajuan':
                $return = $this->save_pengajuan($data);
                break;
            case 'approve':
                $return = $this->save_persetujuan($param, $data);
                break;
            case 'disapprove':
                $return = $this->save_persetujuan($param, $data);
                break;
            case 'batal':
                $return = $this->save_pembatalan($param, $data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function get($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'pengajuan':
                $return = $this->get_pengajuan($data);
                break;
            case 'history':
                $return = $this->get_history_persetujuan($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    public function delete($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'pengajuan':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $data_cuti = $this->dcutiijin->get_cuti(array(
                        'id' => $id,
                        'single_row' => true
                    ));

                    $this->dgeneral->update('tbl_cuti', $data_row,
                        array(
                            array(
                                'kolom' => 'id_cuti',
                                'value' => $id
                            )
                        )
                    );

                    if (isset($data_cuti->id_cuti_parent)) {
                        $this->dgeneral->update('tbl_cuti', $data_row,
                            array(
                                array(
                                    'kolom' => 'id_cuti_parent',
                                    'value' => $id
                                )
                            )
                        );
                    }

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil dihapus";
                        $sts = "OK";
                    }
                    $this->general->closeDb();
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function rfc($param)
    {
        $this->load->library('sap');
        $gen = $this->generate;

        $filter = $this->input->post();

        $nik = isset($filter['nik']) ? $filter['nik'] : "";
        $lokasi = $filter['lokasi'];

        $ho = '';
        if ($lokasi == 'ho')
            $ho = 'X';

        if (isset($nik)) {
            $check_nik = $this->dessgeneral->get_karyawan($nik);
            if (!isset($check_nik)) {
                $return = array('sts' => 'NotOK', 'msg' => 'NIK tidak ditemukan');
                echo json_encode($return);
                return;
            }
        }

        $koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);

        $data_koneksi = $koneksi['ESS'];

        $constr = array(
            "logindata" => array(
                "ASHOST" => $data_koneksi['ASHOST'],
                "SYSNR" => $data_koneksi['SYSNR'],
                "CLIENT" => $data_koneksi['CLIENT'],
                "USER" => $data_koneksi['USER'],
                "PASSWD" => $data_koneksi['PASSWD']
            ),
            "show_errors" => $data_koneksi['DEBUG'],
            "debug" => $data_koneksi['DEBUG']
        );

        $sap = new saprfc($constr);

        switch ($param) {
            case "cuti_import":
                $personal_area = $this->dessgeneral->get_personal_area();

                $this->general->connectDbPortal();
                $this->dgeneral->begin_transaction();

                foreach ($personal_area as $area) {
                    if (isset($area->moabw)) {
                        $this->db->query("DELETE FROM tbl_cuti_ijin where kode_grouping_area= ?", array($area->moabw));
                        $resultArea = $sap->callFunction("Z_LIST_ABSENCE_TYPE",
                            array(
                                array("IMPORT", "I_MOABW", $area->moabw),
                                array("TABLE", "T_DATA", array())
                            )
                        );

                        if ($sap->getStatus() == SAPRFC_OK) {
                            foreach ($resultArea["T_DATA"] as $d) {
                                $check = $this->dessgeneral->get_cuti_ijin(
                                    array(
                                        'kode_grouping_area' => $d['MOABW'],
                                        'kode' => $d['SUBTY'],
                                        'single_row' => true
                                    )
                                );

                                if (isset($check)) {
                                    $data_row = $this->dgeneral->basic_column('update',
                                        array(
                                            'nama' => $d['ATEXT'],
                                            'tanggal_awal' => date_create($d['BEGDA'])->format('Y-m-d'),
                                            'tanggal_akhir' => date_create($d['ENDDA'])->format('Y-m-d'),
                                            'jumlah' => $d['MAXTG']
                                        )
                                    );

                                    $this->dgeneral->update('tbl_cuti_ijin',
                                        $data_row,
                                        array(
                                            array(
                                                'kolom' => 'id_cuti_ijin',
                                                'value' => $check->id_cuti_ijin
                                            )
                                        )
                                    );
                                } else {
                                    $data_row = $this->dgeneral->basic_column('insert',
                                        array(
                                            'kode' => $d['SUBTY'],
                                            'kode_grouping_area' => $d['MOABW'],
                                            'nama' => $d['ATEXT'],
                                            'tanggal_awal' => date('Y-m-d', strtotime($d['BEGDA'])),
                                            'tanggal_akhir' => date('Y-m-d', strtotime($d['ENDDA'])),
                                            'jumlah' => $d['MAXTG']
                                        )
                                    );
                                    $this->dgeneral->insert('tbl_cuti_ijin', $data_row);
                                }
                            }
                        }
                    }
                }

                $result = $sap->callFunction("Z_LIST_ABSENCE_QUOTAS",
                    array(
                        array("IMPORT", "I_HO", $ho),
                        array("IMPORT", "I_PERNR", $nik),
                        array("TABLE", "T_DETAIL", array()),
                        array("TABLE", "T_DATA", array())
                    )
                );

                if ($sap->getStatus() == SAPRFC_OK) {

                    foreach ($result['T_DETAIL'] as $data) {
                        $cek_cuti = $this->dcutiijin->get_cuti(
                            array(
                                'tanggal_awal' => array(
                                    date_format(date_create_from_format('Ymd', $data['BEGDA']), 'Y-m-d'),
                                    date_format(date_create_from_format('Ymd', $data['ENDDA']), 'Y-m-d')
                                ),
                                'tipe' => array('Cuti', 'Ijin'),
                                'id_tipe_status' => array(ESS_CUTI_STATUS_DISETUJUI_ATASAN),
                                'nik' => $data['PERNR'],
                                'extra' => false
                            )
                        );

                        if (count($cek_cuti) > 0) {
                            foreach ($cek_cuti as $cuti) {
                                $data_row = array();
                                $data_row['id_cuti_status'] = ESS_CUTI_STATUS_DISETUJUI_HR;
                                $data_row['tanggal_migrasi'] = date("Y-m-d H:i:s");

                                $this->dgeneral->update('tbl_cuti', $data_row,
                                    array(
                                        array(
                                            'kolom' => 'id_cuti',
                                            'value' => $cuti->id_cuti
                                        )
                                    )
                                );

                                $cek_history = $this->dessgeneral->get_history_persetujuan($cuti->id_cuti, ESS_CUTI_STATUS_DISETUJUI_HR);

                                if (count($cek_history) == 0) {
                                    $data_history = array(
                                        'id_cuti' => $cuti->id_cuti,
                                        'id_cuti_status' => ESS_CUTI_STATUS_DISETUJUI_HR
                                    );

                                    $data_history = $this->dgeneral->basic_column('insert_simple', $data_history);

                                    $this->dgeneral->insert('tbl_cuti_history', $data_history);
                                }
                            }


                        }
                    }

                    if (isset($result['T_DATA']) && count($result['T_DATA'])) {

                        if (!empty($nik))
                            $this->db->query('delete from tbl_cuti_cuti where nik = ?', array($nik));
                        else
                            $this->db->query('delete from tbl_cuti_cuti');

                        foreach ($result['T_DATA'] as $data) {
                            $sisa = $gen->format_nilai("SAPSQL", $data['ANZHL']) - $gen->format_nilai("SAPSQL", $data['KVERB']);

                            $data_cuti = array(
                                'nik' => $data['PERNR'],
                                'kode' => $data['KTART'],
                                'nama' => $data['KTEXT'],
                                'tanggal_awal' => $data['BEGDA'],
                                'tanggal_akhir' => $data['ENDDA'],
                                'jumlah' => $gen->format_nilai("SAPSQL", $data['ANZHL']),
                                'terpakai' => $gen->format_nilai("SAPSQL", $data['KVERB']),
                                'sisa' => $sisa,
                                'batas_negatif' => $gen->format_nilai("SAPSQL", $data['QTNEG'])
                            );

                            $data_cuti = $this->dgeneral->basic_column('insert', $data_cuti);

                            $this->dgeneral->insert('tbl_cuti_cuti', $data_cuti);
                        }
                    }

                }

                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else if ($sap->getStatus() != SAPRFC_OK) {
                    $this->dgeneral->rollback_transaction();
                    $msg = @saprfc_exception($sap->func_id);
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Import Cuti dari SAP berhasil";
                    $sts = "OK";
                }

                $this->general->closeDb();

                $sap->logoff();

                $return = array('sts' => $sts, 'msg' => $msg);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);

    }

    public function excel($param, $level = 'kasi')
    {
        $this->load->library('PHPExcel');

        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        switch ($param) {
            case "cuti-ho":
                $objPHPExcel = new PHPExcel();

                // Set document properties
                $objPHPExcel->getProperties()->setCreator("Kiranaku")
                    ->setLastModifiedBy("Kiranaku")
                    ->setTitle("Export Cuti HO to SAP (" . date('d-m-Y') . ")")
                    ->setSubject("Export Cuti HO to SAP (" . date('d-m-Y') . ")")
                    ->setDescription("Export Cuti HO to SAP (" . date('d-m-Y') . ")")
                    ->setCategory("EXPORT SAP CUTI");

                // Add some data
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'PERNR');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'BEGDA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'ENDDA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'SUBTY');

                $list_proses = $this->dcutiijin->get_cuti(
                    array(
                        'tipe' => array('Cuti', 'Ijin'),
                        'id_tipe_status' => array(ESS_CUTI_STATUS_DISETUJUI_ATASAN),
                        'ho' => true,
                        'extra' => false
                    )
                );
                $baris = 1;
                foreach ($list_proses as $data) {
                    $baris++;
                    $tipe = ($data->form == 'Cuti') ? '0110' : $data->kode;
                    $tanggal_awal = $this->generate->generateDateFormat($data->tanggal_awal);
                    $tanggal_akhir = $this->generate->generateDateFormat($data->tanggal_akhir);

                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $data->nik, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $tanggal_awal, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, $tanggal_akhir, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $tipe, PHPExcel_Cell_DataType::TYPE_STRING);
                }

                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle('Cuti_SAP');


                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);


                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Export Cuti HO to SAP (' . date('d-m-Y') . ').xls"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                break;
            case "cuti-pabrik":
                $managerUp = ($level == 'mg');
                $objPHPExcel = new PHPExcel();

                // Set document properties
                $objPHPExcel->getProperties()->setCreator("Kiranaku")
                    ->setLastModifiedBy("Kiranaku")
                    ->setTitle("Export Cuti (Pabrik " . ($managerUp ? "Mg Up" : "Kasie Down") . ") to SAP (" . date('d-m-Y') . ")")
                    ->setSubject("Export Cuti (Pabrik " . ($managerUp ? "Mg Up" : "Kasie Down") . ") to SAP (" . date('d-m-Y') . ")")
                    ->setDescription("Export Cuti (Pabrik " . ($managerUp ? "Mg Up" : "Kasie Down") . ") to SAP (" . date('d-m-Y') . ")")
                    ->setCategory("EXPORT SAP CUTI");

                // Add some data
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'PERNR');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'BEGDA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'ENDDA');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'SUBTY');

                $list_proses = $this->dcutiijin->get_cuti(
                    array(
                        'tipe' => array('Cuti', 'Ijin'),
                        'id_tipe_status' => array(ESS_CUTI_STATUS_DISETUJUI_ATASAN),
                        'ho' => false,
                        'manager' => $managerUp,
                        'extra' => false
                    )
                );
                $baris = 1;
                foreach ($list_proses as $data) {
                    $baris++;
                    $tipe = ($data->form == 'Cuti') ? '0110' : $data->kode;
                    $tanggal_awal = $this->generate->generateDateFormat($data->tanggal_awal);
                    $tanggal_akhir = $this->generate->generateDateFormat($data->tanggal_akhir);

                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('A' . $baris, $data->nik, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('B' . $baris, $tanggal_awal, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('C' . $baris, $tanggal_akhir, PHPExcel_Cell_DataType::TYPE_STRING);
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit('D' . $baris, $tipe, PHPExcel_Cell_DataType::TYPE_STRING);
                }

                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle('Cuti_SAP');


                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);


                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Export Cuti (Pabrik ' . ($managerUp ? "Mg Up" : "Kasie Down") . ') to SAP (' . date('d-m-Y') . ').xls"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

                // If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
                break;
            default:
                break;
        }
    }

    private function save_pengajuan($data)
    {
        $user = $this->data['user'];

        $send_mail = false;

        $this->form_validation->set_data($data);
        if ($data['form'] == 'Ijin') {
            $this->form_validation->set_rules('kode', 'Jenis Ijin', 'required');
            $this->form_validation->set_rules('alasan', 'Catatan', 'required');
        }
        $this->form_validation->set_rules('tanggal_awal', 'Tanggal Awal', 'required');
        $this->form_validation->set_rules('tanggal_akhir', 'Tanggal Akhir', 'required');


        if ($this->form_validation->run() !== FALSE) {

            $gambar_old = $data['gambar_old'];
            unset($data['gambar_old']);

            $data['tanggal_awal'] = date_create($data['tanggal_awal'])->format('Y-m-d');
            $data['tanggal_akhir'] = date_create($data['tanggal_akhir'])->format('Y-m-d');


            $this->general->connectDbDefault();
            $holiday = $this->dessgeneral->get_libur(base64_decode($this->session->userdata('-nik-')), $data['tanggal_awal'], $data['tanggal_akhir']);
            $holiday = array_map(function ($h) {
                return $h['tanggal'];
            }, $holiday);

            $this->general->connectDbPortal();
            $dates = $this->dessgeneral->get_jumlah_hari($data['tanggal_awal'], $data['tanggal_akhir']);
            $dinas = $this->dessgeneral->get_dinas(base64_decode($this->session->userdata('-nik-')), $data['tanggal_awal'], $data['tanggal_akhir']);

            if (isset($data['id_cuti']))
                $cuti = $this->get_tanggal_cuti($data['tanggal_awal'], $data['tanggal_akhir'], $data['id_cuti']);
            else
                $cuti = $this->get_tanggal_cuti($data['tanggal_awal'], $data['tanggal_akhir']);

            $dates = array_values(array_diff($dates, $holiday, $cuti));

            $data['jumlah'] = count($dates);

            $cek_ijin_sakit_t_surat = array();
            if ($data['kode'] == ESS_CUTI_SAKIT_T_SURAT) {
                // Cek ijin sakit tanpa surat H+1 dan H-1
                $hMin1 = date_create($data['tanggal_awal'])
                    ->sub(DateInterval::createFromDateString('1 day'))
                    ->format('Y-m-d');
                $hPlus1 = date_create($data['tanggal_awal'])
                    ->add(DateInterval::createFromDateString('1 day'))
                    ->format('Y-m-d');
                $cek_ijin_sakit_t_surat = $this->dcutiijin->get_cuti(array(
                    'tanggal_awal' => array($hMin1, $hPlus1),
                    'tipe' => 'Ijin',
                    'id_tipe_status' => array(ESS_CUTI_STATUS_MENUNGGU, ESS_CUTI_STATUS_DISETUJUI_ATASAN, ESS_CUTI_STATUS_DISETUJUI_HR),
                    'kode' => ESS_CUTI_SAKIT_T_SURAT,
                    'nik' => base64_decode($this->session->userdata('-nik-')),
                ));
            }

            $cek_periode_cuti = array();
            if ($data['form'] == 'Cuti') {
                if (isset($data['id_cuti']))
                    $cek_periode_cuti = $this->cek_periode_cuti($data['tanggal_awal'], $data['tanggal_akhir'], $data['id_cuti']);
                else
                    $cek_periode_cuti = $this->cek_periode_cuti($data['tanggal_awal'], $data['tanggal_akhir']);
            }

            if (count($cek_ijin_sakit_t_surat) > 0) {
                $msg = "Periksa kembali tanggal yang di ajukan. Sudah ada ijin sakit tanpa surat dokter yang diajukan pada tanggal sebelum/sesudah tanggal tersebut.";
                $sts = "NotOK";
            } else if (count($cuti) > 0) {
                $msg = "Periksa kembali tanggal yang di ajukan. Sudah ada cuti/ijin yang diajukan pada tanggal tersebut.";
                $sts = "NotOK";
            } else if (count($dinas) > 0) {
                $msg = "Periksa kembali tanggal yang di ajukan. Sudah ada BAK yang diajukan pada tanggal tersebut.";
                $sts = "NotOK";
            } else if (count($cek_periode_cuti) > 0) {
                $gen = $this->generate;
                $cek_periode_cuti = array_map(function ($tanggal) use ($gen) {
                    return $gen->generateDateFormat($tanggal);
                }, $cek_periode_cuti);
                $msg = "Periksa kembali tanggal yang di ajukan. Tanggal pengajuan " . join(', ', $cek_periode_cuti) . " tidak pada periode cuti yang tersedia.";
                $sts = "NotOK";
            } else {
                $this->general->connectDbPortal();
                $this->dgeneral->begin_transaction();

                $data['nik'] = base64_decode($this->session->userdata('-nik-'));

                $upload_error = null;

                $ijin_data = null;
                if ($data['form'] == 'Ijin') {
                    $karyawan = $this->dessgeneral->get_karyawan($data['nik']);
                    $ijin_data = $this->dessgeneral->get_cuti_ijin(
                        array(
                            'kode_grouping_area' => $karyawan->moabw,
                            'kode' => $data['kode'],
                            'single_row' => true
                        )
                    );
                }

                if ($data['form'] == 'Ijin' && $data['kode'] == ESS_CUTI_JENIS_SAKIT_W_SURAT) {

                    if (isset($_FILES['lampiran']) and $_FILES['lampiran']['size'] > 0) {
                        $uploaddir = ESS_PATH_FILE . KIRANA_PATH_APPS_IMAGE_FOLDER . ESS_CUTI_UPLOAD_FOLDER;
                        if (!file_exists($uploaddir)) {
                            mkdir($uploaddir, 0777, true);
                        }

                        $config['upload_path'] = $uploaddir;
                        $config['allowed_types'] = 'jpeg|jpg|png|pdf';
                        $config['max_size'] = 5000;

                        $this->load->library('upload', $config);

                        $filename = $user->nik . "_" . date('YmdHis') . "_" . $_FILES['lampiran']['name'];
                        $_FILES['lampiran']['name'] = $filename;

                        if ($this->upload->do_upload('lampiran')) {
                            $upload_data = $this->upload->data();
                            $data['gambar'] = KIRANA_PATH_APPS_IMAGE_FOLDER .
                                ESS_CUTI_UPLOAD_FOLDER . $upload_data['file_name'];
                        } else {
                            $upload_error = $this->upload->display_errors();
                        }
                    } else if (empty($gambar_old)) {
                        if ($_FILES['lampiran']['error'] != 0) {
                            switch ($_FILES['lampiran']['error']) {
                                case UPLOAD_ERR_INI_SIZE:
                                    $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                    break;
                                case UPLOAD_ERR_FORM_SIZE:
                                    $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                    break;
                                case UPLOAD_ERR_PARTIAL:
                                    $upload_error = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                                    break;
                                case UPLOAD_ERR_EXTENSION:
                                    $upload_error = 'Upload berkas dihentikan oleh ekstensi. Harap pilih file lain.';
                                    break;
                                default:
                                    $upload_error = "Error upload gambar.";
                                    break;
                            }
                        } else
                            $upload_error = "Lampiran tidak ada, harap pilih file lampiran.";
                    }
                }

                if (isset($data['id_cuti']) && !empty($data['id_cuti'])) {
                    $id = $data['id_cuti'];
                    unset($data['id_cuti']);

                    $data_parent_update = $this->dcutiijin->get_cuti(array(
                        'id_cuti_parent' => $id,
                        'single_row' => true,
                        'extra' => false
                    ));

                    $data_update_extra = null;

                    if (isset($ijin_data) && $data['jumlah'] > $ijin_data->jumlah) {
                        $data_update_extra = new ArrayObject($data);
                        $data_update_extra = $data_update_extra->getArrayCopy();
                        $data_update_extra['jarak'] = $data['jumlah'] - $ijin_data->jumlah;

                        $data['jarak'] = 0;
                        $data['jumlah'] -= $data_update_extra['jarak'];
                        $data_update_extra['jumlah'] = $data_update_extra['jarak'];
                        $data['tanggal_akhir'] = $dates[$data['jumlah'] - 1];
                        $data_update_extra['tanggal_awal'] = $dates[$data['jumlah']];
                    }

                    $data_update = $this->dgeneral->basic_column("update", $data);

                    $result = $this->dgeneral->update('tbl_cuti', $data_update, array(
                        array(
                            "kolom" => "id_cuti",
                            "value" => $id
                        )
                    ));

                    if (isset($data_update_extra)) {
                        if (isset($data_parent_update)) {
                            $data_update_extra = $this->dgeneral->basic_column("update", $data_update_extra);

                            $result_extra = $this->dgeneral->update('tbl_cuti', $data_update_extra, array(
                                array(
                                    "kolom" => "id_cuti",
                                    "value" => $data_parent_update->id_cuti
                                )
                            ));
                        } else {
                            $data_update_extra['id_cuti_parent'] = $id;
                            $data_update_extra = $this->dgeneral->basic_column("insert", $data_update_extra);
                            $result_extra = $this->dgeneral->insert('tbl_cuti', $data_update_extra);
                        }

                    } else if (isset($data_parent_update)) {
                        $result_extra = $this->dgeneral->delete('tbl_cuti', array(
                            array(
                                'kolom' => "id_cuti",
                                'value' => $data_parent_update->id_cuti
                            )
                        ));
                    }
                } else {
                    unset($data['id_cuti']);

                    if ($data['form'] == 'Cuti') {
                        /** Hardcode kode cuti langsung pakai Cuti Tahunan */
                        $data['kode'] = '94';
                    }
                    $data_insert_extra = null;

                    if (isset($ijin_data) && $data['jumlah'] > $ijin_data->jumlah) {
                        $data_insert_extra = new ArrayObject($data);
                        $data_insert_extra = $data_insert_extra->getArrayCopy();
                        $data_insert_extra['jarak'] = $data['jumlah'] - $ijin_data->jumlah;

                        $data['jarak'] = 0;
                        $data['jumlah'] -= $data_insert_extra['jarak'];
                        $data_insert_extra['jumlah'] = $data_insert_extra['jarak'];


                        $data['tanggal_akhir'] = $dates[$data['jumlah'] - 1];
                        $data_insert_extra['tanggal_awal'] = $dates[$data['jumlah']];

//                        $data['tanggal_akhir'] = date_create($data['tanggal_akhir'])
//                            ->modify('-' . $data_insert_extra['jarak'] . ' days')->format('Y-m-d');
//                        $data_insert_extra['tanggal_awal'] = date_create($data_insert_extra['tanggal_akhir'])
//                            ->modify('-' . ($data_insert_extra['jumlah'] - 1) . ' days')->format('Y-m-d');
                    }

                    $data_insert = $this->dgeneral->basic_column("insert", $data);

                    $result = $this->dgeneral->insert('tbl_cuti', $data_insert);

                    $id = $this->db->insert_id();

                    if (isset($data_insert_extra)) {
                        $data_insert_extra['id_cuti_parent'] = $id;
                        $data_insert_extra = $this->dgeneral->basic_column("insert", $data_insert_extra);

                        $result_extra = $this->dgeneral->insert('tbl_cuti', $data_insert_extra);
                    }

                    $data_history = array(
                        'id_cuti' => $id,
                        'id_cuti_status' => ESS_CUTI_STATUS_MENUNGGU
                    );

                    $data_history = $this->dgeneral->basic_column('insert_simple', $data_history);

                    $this->dgeneral->insert('tbl_cuti_history', $data_history);

                    $send_mail = true;
                }

                $send_mail_result = true;

                if ($send_mail) {
                    try {
                        $cuti_ijin = $this->dcutiijin->get_cuti(
                            array(
                                'id' => $id,
                                'single_row' => true,
                                'id_tipe_status' => array(1, 2, 3, 4)
                            )
                        );

                        $karyawan = $this->dessgeneral->get_karyawan($cuti_ijin->id_karyawan);

                        $atasan = $this->less->get_atasan(array(
                            'nik' => $karyawan->nik
                        ));

                        $sisacuti = $this->less->get_cuti_sisa(null, $karyawan->nik);

//                        array_push($atasan['list_atasan_email'], strtolower($karyawan->email));

//                        $email_tujuan = $atasan['list_atasan_email'];
                        $email_tujuan = !ESS_EMAIL_DEBUG_MODE ? $atasan['list_atasan_email'] : json_decode(ESS_EMAIL_TESTER);

                        $result = $this->less->send_email(
                            array(
                                'judul' => "Konfirmasi Pengajuan Cuti/Ijin " . ucwords(strtolower($karyawan->nama)),
                                'email_pengirim' => "KiranaKu",
                                'email_tujuan' => $email_tujuan,
                                'view' => 'emails/pengajuan_cuti_ijin_new',
                                'data' => array(
                                    'data' => $cuti_ijin,
                                    'sisa_cuti' => $sisacuti
                                )
                            )
                        );
                        if ($result['sts'] == "NotOK")
                            $send_mail_result = false;
                    } catch (Exception $exception) {
                        $send_mail_result = false;
                    }
                }

                if (
                isset($upload_error)
                ) {
                    $this->dgeneral->rollback_transaction();
                    $msg = $upload_error;
                    $sts = "NotOK";
                } else if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    if (isset($data['gambar']))
                        unlink(ESS_PATH_FILE . $data['gambar']);
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else if ($send_mail && !$send_mail_result) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil ditambahkan";
                    $sts = "OK";
                }
                $this->general->closeDb();
            }


        } else {
            $msg = "Periksa kembali data yang dimasukkan.";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_pembatalan($param, $data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id_cuti']) && !empty($data['id_cuti'])) {

            $id = $this->generate->kirana_decrypt($data['id_cuti']);

            $data_cuti = $this->dcutiijin->get_cuti(array(
                'id' => $id,
                'id_tipe_status' => array(ESS_CUTI_STATUS_DISETUJUI_ATASAN, ESS_CUTI_STATUS_DISETUJUI_HR),
                'single_row' => true
            ));

            unset($data['id_cuti']);

            $upload_error = array();
            $uploaded = null;

            if (isset($data_cuti) and (in_array($data_cuti->id_cuti_status, array(ESS_CUTI_STATUS_DISETUJUI_ATASAN, ESS_CUTI_STATUS_DISETUJUI_HR)))) {

                /** Upload untuk file bukti dari pembatalan oleh HR **/
                if (isset($_FILES['gambar_bukti']) && $_FILES['gambar_bukti']['size'] > 0) {
                    $uploaddir = ESS_PATH_FILE . KIRANA_PATH_APPS_IMAGE_FOLDER . ESS_CUTI_UPLOAD_FOLDER;
                    if (!file_exists($uploaddir)) {
                        mkdir($uploaddir, 0777, true);
                    }
                    $config['upload_path'] = $uploaddir;
                    $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                    $config['max_size'] = 5000;
                    $config['overwrite'] = true;

                    $this->load->library('upload', $config);

                    $_FILES['gambar_bukti']['name'] = "BUKTI_BATAL_" . $id . "." . pathinfo($_FILES['gambar_bukti']['name'], PATHINFO_EXTENSION);
                    if ($this->upload->do_upload('gambar_bukti')) {
                        $upload_data = $this->upload->data();
                        $uploaded = KIRANA_PATH_APPS_IMAGE_FOLDER .
                            ESS_CUTI_UPLOAD_FOLDER . $upload_data['file_name'];
                    } else {
                        $upload_error[] = $this->upload->display_errors();
                    }
                } else {
                    if ($_FILES['gambar_bukti']['error'] != 0) {
                        switch ($_FILES['gambar_bukti']['error']) {
                            case UPLOAD_ERR_INI_SIZE:
                                $upload_error[] = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                break;
                            case UPLOAD_ERR_FORM_SIZE:
                                $upload_error[] = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                break;
                            case UPLOAD_ERR_PARTIAL:
                                $upload_error[] = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                                break;
                            case UPLOAD_ERR_EXTENSION:
                                $upload_error[] = 'Upload berkas dihentikan oleh ekstensi. Harap pilih file lain.';
                                break;
                            default:
                                $upload_error[] = "Error upload gambar.";
                                break;
                        }
                    } else
                        $upload_error[] = "File upload bukti tidak tersedia, harap pilih file.";
                }

                if (isset($uploaded)) {
                    $this->dgeneral->begin_transaction();

                    $data_update = $this->dgeneral->basic_column("update", $data);
                    $data_update['gambar_bukti'] = $uploaded;
                    $data_update['id_cuti_status'] = ESS_CUTI_STATUS_DIBATALKAN;

                    $data_cuti = $this->dcutiijin->get_cuti(array(
                        'id' => $id,
                        'single_row' => true
                    ));

                    $result = $this->dgeneral->update('tbl_cuti', $data_update, array(
                        array(
                            "kolom" => "id_cuti",
                            "value" => $id
                        )
                    ));

                    if (isset($data_cuti->id_cuti_parent)) {
                        $result_extra = $this->dgeneral->update('tbl_cuti', $data_update, array(
                            array(
                                "kolom" => "id_cuti_parent",
                                "value" => $id
                            )
                        ));
                    }

                    $data_history = array(
                        'id_cuti' => $id,
                        'id_cuti_status' => ESS_CUTI_STATUS_DIBATALKAN
                    );

                    $data_history = $this->dgeneral->basic_column('insert_simple', $data_history);

                    $this->dgeneral->insert('tbl_cuti_history', $data_history);

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();

                        unlink(ESS_PATH_FILE . $uploaded);

                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    } else {
                        $this->dgeneral->commit_transaction();
                        $msg = "Data berhasil ditambahkan";
                        $sts = "OK";
                    }
                    $this->general->closeDb();

                } else if (count($upload_error) > 0) {
                    $msg = join(', ', $upload_error);
                    $sts = "NotOK";
                } else {
                    $msg = "File upload bukti tidak tersedia, harap pilih file.";
                    $sts = "NotOK";
                }
            } else {
                $msg = "Tidak ada data Cuti/Ijin yg tersedia.";
                $sts = "NotOK";
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function get_pengajuan($data)
    {
        if (isset($data['id']))
            $id = $this->generate->kirana_decrypt($data['id']);
        else
            $id = null;

        $this->general->connectDbPortal();
        $detailCuti = $this->dcutiijin->get_cuti(
            array(
                'id' => $id,
                'tipe' => array('Cuti', 'Ijin'),
                'id_tipe_status' => array(
                    ESS_CUTI_STATUS_MENUNGGU,
                    ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                    ESS_CUTI_STATUS_DISETUJUI_HR,
                    ESS_CUTI_STATUS_DITOLAK,
                    ESS_CUTI_STATUS_DIBATALKAN
                ),
                'single_row' => true
            )
        );

        $tanggal_cuti = $this->get_tanggal_cuti(null, null, null, $id);
//        $this->general->closeDb();
        $nik = null;
        $tanggal_libur = array();
        if (isset($detailCuti)) {
            $nik = $detailCuti->nik;
            if (!empty($detailCuti->gambar)) {
                $data_image = site_url("assets/file/ess/" . $detailCuti->gambar);
                $headers = get_headers($data_image);
                if ($headers[0] != "HTTP/1.1 200 OK") {
                    $data_image = "http://10.0.0.18/home/" . $detailCuti->gambar;
                    $headers = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $detailCuti->gambar = $data_image;
                    } else
                        $detailCuti->gambar = null;
                } else
                    $detailCuti->gambar = $data_image;
            }
//            $this->general->connectDbDefault();
            $tanggal_libur = $this->get_tanggal_libur($nik);
//            $this->general->closeDb();
        }

        if (!empty($detailCuti->gambar_bukti)) {
            $data_image = site_url(
                'assets/file/ess/' .
                $detailCuti->gambar_bukti
            );
            $headers = get_headers($data_image);
            if ($headers[0] != "HTTP/1.1 200 OK") {
                $data_image = "http://10.0.0.18/home/" . $detailCuti->gambar_bukti;
                $headers = get_headers($data_image);
                if ($headers[0] == "HTTP/1.1 200 OK") {
                    $detailCuti->gambar_bukti = $data_image;
                } else
                    $detailCuti->gambar_bukti = null;
            } else
                $detailCuti->gambar_bukti = $data_image;
        }

        $result = array(
            'detail' => $detailCuti,
            'tanggal' => $tanggal_cuti,
            'tanggal_libur' => $tanggal_libur
        );

        if (isset($nik)) {
            $this->general->connectDbPortal();
            $result['saldo'] = $this->less->get_cuti_sisa(array(null, $detailCuti->tanggal_akhir), $nik);
//            $this->general->closeDb();
        }

        return $result;
    }

    private function get_history_persetujuan($data)
    {
        if (isset($data['id']))
            $id = $this->generate->kirana_decrypt($data['id']);
        else
            $id = null;
        $result = $this->dessgeneral->get_history_persetujuan($id);

        foreach ($result as $i => $dt) {
            if ($dt->ho == 'y') {
                $bagian = (empty($dt->nama_departemen)) ? $dt->nama_divisi : $dt->nama_departemen;
            } else {
                $bagian = (empty($dt->nama_seksi)) ? $dt->nama_departemen : $dt->nama_seksi;
                $bagian = (empty($bagian)) ? $dt->nama_sub_divisi : $bagian;
                $bagian = (empty($bagian)) ? $dt->nama_pabrik : $bagian;
            }
            $dt->nama_bagian = $bagian;
            $result[$i] = $dt;
        }

        return $result;
    }

    private function save_persetujuan($param, $data)
    {
        $cuti_status = ESS_CUTI_STATUS_DISETUJUI_ATASAN;
        if ($param == "disapprove")
            $cuti_status = ESS_CUTI_STATUS_DITOLAK;

        if (isset($data['approvals'])) {
            if (!is_array($data['approvals']))
                $approvals = json_decode($data['approvals']);
            else {
                $approvals = json_decode(json_encode($data['approvals'], false));
            }

            if (count($approvals) > 0) {
                $this->general->connectDbPortal();
                $this->dgeneral->begin_transaction();

                foreach ($approvals as $approval) {
                    if (isset($approval->id)) {
                        $id = $this->generate->kirana_decrypt($approval->id);
                        $catatan = "-";
                        if (isset($approval->catatan))
                            $catatan = $approval->catatan;

                        $data_row = $this->dgeneral->basic_column('update');
                        $data_row['id_cuti_status'] = $cuti_status;
                        $data_row['catatan'] = $catatan;

                        $data_cuti = $this->dcutiijin->get_cuti(array(
                            'id' => $id,
                            'single_row' => true
                        ));

                        $this->dgeneral->update('tbl_cuti', $data_row,
                            array(
                                array(
                                    'kolom' => 'id_cuti',
                                    'value' => $id
                                )
                            )
                        );

                        if (isset($data_cuti->id_cuti_parent)) {
                            $this->dgeneral->update('tbl_cuti', $data_row,
                                array(
                                    array(
                                        'kolom' => 'id_cuti_parent',
                                        'value' => $id
                                    )
                                )
                            );
                        }

                        $data_history = array(
                            'id_cuti' => $id,
                            'id_cuti_status' => $cuti_status
                        );

                        $data_history = $this->dgeneral->basic_column('insert_simple', $data_history);

                        $this->dgeneral->insert('tbl_cuti_history', $data_history);
                    }
                }

                $send_email_result = true;

                try {
                    if ($cuti_status == ESS_CUTI_STATUS_DITOLAK) {
                        foreach ($approvals as $approval) {
                            if (isset($approval->id)) {
                                $id = $this->generate->kirana_decrypt($approval->id);

                                $cuti_ijin = $this->dcutiijin->get_cuti(
                                    array(
                                        'id' => $id,
                                        'single_row' => true,
                                        'id_tipe_status' => array(ESS_CUTI_STATUS_DITOLAK)
                                    )
                                );

                                $karyawan = $this->dessgeneral->get_karyawan($cuti_ijin->id_karyawan);

//                                $atasan = $this->less->get_atasan(array(
//                                    'nik' => $cuti_ijin->id_karyawan
//                                ));
                                $today = date_create();

                                /** Proses otomatis rubah ijin menjadi cuti jika ditolak */
                                if (
                                    date_create($cuti_ijin->tanggal_awal) < $today
                                    && $cuti_ijin->form == 'Ijin'
                                ) {
                                    $sisaCuti = $this->less->get_cuti_sisa(null, $cuti_ijin->nik);

                                    if ($sisaCuti['sisa'] > $sisaCuti['negatif']) {
                                        $dataOtomatisCuti = array(
                                            'id_cuti_status' => ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                                            'atasan' => $cuti_ijin->atasan,
                                            'atasan_email' => $cuti_ijin->atasan_email,
                                            'form' => 'Cuti',
                                            'nik' => $cuti_ijin->nik,
                                            'tanggal_awal' => date_create($cuti_ijin->tanggal_awal)->format('Y-m-d'),
                                            'tanggal_akhir' => date_create($cuti_ijin->tanggal_akhir)->format('Y-m-d'),
                                            'jumlah' => $cuti_ijin->jumlah,
                                            'alasan' => $cuti_ijin->alasan,
                                            'gambar' => $cuti_ijin->gambar,
                                            'by_system' => 1
                                        );
                                    } else {
                                        $dataOtomatisCuti = array(
                                            'id_cuti_status' => ESS_CUTI_STATUS_DISETUJUI_ATASAN,
                                            'atasan' => $cuti_ijin->atasan,
                                            'atasan_email' => $cuti_ijin->atasan_email,
                                            'form' => 'Ijin',
                                            'kode' => ESS_CUTI_JENIS_TDK_DIBAYAR,
                                            'nik' => $cuti_ijin->nik,
                                            'tanggal_awal' => date_create($cuti_ijin->tanggal_awal)->format('Y-m-d'),
                                            'tanggal_akhir' => date_create($cuti_ijin->tanggal_akhir)->format('Y-m-d'),
                                            'jumlah' => $cuti_ijin->jumlah,
                                            'alasan' => $cuti_ijin->alasan,
                                            'gambar' => $cuti_ijin->gambar,
                                            'by_system' => 1
                                        );
                                    }

                                    $data_insert = $this->dgeneral->basic_column("insert", $dataOtomatisCuti);

                                    $result = $this->dgeneral->insert('tbl_cuti', $data_insert);

                                    $id = $this->db->insert_id();

                                    $data_history = array(
                                        'id_cuti' => $id,
                                        'id_cuti_status' => ESS_CUTI_STATUS_DISETUJUI_HR
                                    );

                                    $data_history = $this->dgeneral->basic_column('insert_simple', $data_history);

                                    $this->dgeneral->insert('tbl_cuti_history', $data_history);

                                    /** Kirim Email jika sudah insert cuti/ijin baru otomatis */
                                    try {
                                        $cuti_ijin_baru = $this->dcutiijin->get_cuti(
                                            array(
                                                'id' => $id,
                                                'single_row' => true
                                            )
                                        );

                                        $karyawan = $this->dessgeneral->get_karyawan($cuti_ijin_baru->nik);

                                        $atasan = $this->less->get_atasan(array(
                                            'nik' => $karyawan->nik
                                        ));

                                        $sisacuti = $this->less->get_cuti_sisa(null, $karyawan->nik);

                                        $email_tujuan = !ESS_EMAIL_DEBUG_MODE ? $atasan['list_atasan_email'] : json_decode(ESS_EMAIL_TESTER);

                                        $result = $this->less->send_email(
                                            array(
                                                'judul' => "Konfirmasi Pengajuan Cuti/Ijin " . ucwords(strtolower($karyawan->nama)),
                                                'email_pengirim' => "KiranaKu",
                                                'email_tujuan' => $email_tujuan,
                                                'view' => 'emails/pengajuan_cuti_ijin_new',
                                                'data' => array(
                                                    'data' => $cuti_ijin_baru,
                                                    'sisa_cuti' => $sisacuti
                                                )
                                            )
                                        );
                                        if ($result['sts'] == "NotOK")
                                            $send_email_result = false;
                                    } catch (Exception $exception) {
                                        $send_email_result = false;
                                    }
                                }

                                $sisacuti = $this->less->get_cuti_sisa(array(null, date('Y-m-d')), $karyawan->nik);

                                $email_tujuan = !ESS_EMAIL_DEBUG_MODE ? $karyawan->email : json_decode(ESS_EMAIL_TESTER);
//                                $email_tujuan = $karyawan->email;

                                $result = $this->less->send_email(
                                    array(
                                        'judul' => "Status Pengajuan Cuti/Ijin " . ucwords(strtolower($karyawan->nama)),
                                        'email_pengirim' => "KiranaKu",
                                        'email_tujuan' => $email_tujuan,
                                        'view' => 'emails/pengajuan_cuti_ijin_new',
                                        'data' => array(
                                            'data' => $cuti_ijin,
                                            'sisa_cuti' => $sisacuti
                                        )
                                    )
                                );
                                if ($result['sts'] == "NotOK")
                                    $send_email_result = false;
                            }
                        }
                    }
                } catch (Exception $exception) {
                    $send_email_result = false;
                }

                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else if (!$send_email_result) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil disimpan";
                    $sts = "OK";
                }
                $this->general->closeDb();
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

}