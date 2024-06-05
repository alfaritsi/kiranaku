<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Travel - Transportasi - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Booking extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Travel Transportasi";
        $this->data['user'] = $this->general->get_data_user();

        $this->load->model('dspd');
        $this->load->library('lspd');
    }

    public function transport_options()
    {
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Master";
        $this->data['title'] = "Master Opsi Transportasi";
        $this->data['title_form'] = "Tambah Opsi Transportasi";
        $this->data['destinations'] = $this->dspd->get_travel_pabrik();
        $this->data['transports'] = $this->dspd->get_travel_transport_master(array('jenis' => 'keberangkatan'));
        $this->data['list'] = $this->general->generate_encrypt_json(
            $this->dspd->get_travel_transport_options(),
            array('id_travel_transport_options')
        );

        $this->load->view('master/transport_options', $this->data);
    }

    public function transport_master()
    {
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Master";
        $this->data['title'] = "Master Transportasi";
        $this->data['title_form'] = "Tambah Transportasi";

        $this->data['list'] = $this->general->generate_encrypt_json(
            $this->dspd->get_travel_transport_master(),
            array('id_travel_transport_master')
        );

        $this->load->view('master/transport_master', $this->data);
    }

    public function mess_options()
    {
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel - Master";
        $this->data['title'] = "Master Ketersediaan Mess";
        $this->data['title_form'] = "Tambah Ketersediaan Mess";
        $this->data['destinations'] = $this->dspd->get_travel_pabrik();
        $this->data['list'] = $this->general->generate_encrypt_json(
            $this->dspd->get_travel_mess_options(),
            array('id_travel_mess_option')
        );

        $this->load->view('master/penginapan_options', $this->data);
    }

    public function transportasi()
    {
        $this->general->check_access();

        $this->general->connectDbPortal();
        $this->data['module'] = "Travel Transportasi - Booking";
        $this->data['title'] = "Pemesanan Transportasi dan Penginapan";

        $listAuthNiks = $this->dspd->get_data_book_oto(array(
            'nik' => $this->data['user']->nik
        ));

        if (count($listAuthNiks) == 0) {
            $listAuthNiks = array($this->data['user']->nik);
            $tipesc       = ['user'];
        } else {
            $listAuthNiks = array_map(function ($v) {
                return $v->nik;
            }, $listAuthNiks);
            $tipesc       = ['pemesan'];
        }

        $listSpd = $this->dspd->get_travel_header_booking_transport(array(
            'nik' => $listAuthNiks
        ));

        function moveElement(&$array, $a, $b)
        {
            $out = array_splice($array, $a, 1);
            array_splice($array, $b, 0, $out);
        }
        $d = 1;
        $array_list = [];
        foreach ($listSpd as $listdata) {
            if ((substr($d, -1) == '0' || substr($d, -1) == '9' || substr($d, -1) == '8' || substr($d, -1) == '7') && $listdata->tipe_trip == 'multi') {
                moveElement($listSpd, $d, ($d - 3));
            }
            $d++;
        }

        $listHistorySpd = $this->dspd->get_travel_header_booking_transport(array(
            'nik' => $listAuthNiks,
            'finish' => true
        ));

        /** Reproses list array SPD */
        $listSpd = $this->lspd->proses_spd_list($listSpd, 'booking');
        $listHistorySpd = $this->lspd->proses_spd_list($listHistorySpd, 'booking');

        $listRefundableTiket = $this->dspd->get_travel_transport_refundable(array(
            // 'status'    => "Issued",
            'nik'       => $listAuthNiks,
            // 'finish'    => true
            // 'finish' => true
        ));
        $listRefundableTiket        = $this->general->generate_encrypt_json($listRefundableTiket, array("id_travel_transport"));

        $listRefundableTiket = $this->lspd->proses_spd_list($listRefundableTiket, 'booking');

        $this->data['list'] = $listSpd;
        $this->data['history'] = $listHistorySpd;
        $this->data['refund'] = $listRefundableTiket;
        $this->data['tipe_screen'] = $tipesc;
        $this->data['modal_chat'] = $this->lspd->load_spd_chat();
        $this->data['modal_tujuan'] = $this->lspd->load_spd_tujuan();
        $this->data['modal_history'] = $this->lspd->load_spd_history();
        $this->data['session_nik'] = $this->data['user']->nik;
        $this->data['pesawat_merk'] = $this->dspd->get_travel_merk_trans(array(
            'transport' => "pesawat",
        ));
        $this->data['taxi_merk'] = $this->dspd->get_travel_merk_trans(array(
            'transport' => "taxi",
        ));
        $this->load->view('spd/booking', $this->data);
    }

    public function get_book($show = NULL)
    {
        $this->general->connectDbPortal();
        $data['module'] = "Travel Transportasi - Booking";
        $data['title'] = "Pemesanan Transportasi SPD";

        $listAuthNiks = $this->dspd->get_data_book_oto(array(
            'nik' => $this->data['user']->nik
        ));

        if (count($listAuthNiks) == 0) {
            $listAuthNiks = array($this->data['user']->nik);
            $tipesc       = ['user'];
        } else {
            $listAuthNiks = array_map(function ($v) {
                return $v->nik;
            }, $listAuthNiks);
            $tipesc       = ['pemesan'];
        }

        $listSpd = $this->dspd->get_travel_header_booking_transport(array(
            'nik' => $listAuthNiks
        ));

        $listHistorySpd = $this->dspd->get_travel_header_booking_transport(array(
            'nik' => $listAuthNiks,
            'finish' => true
        ));

        /** Reproses list array SPD */
        $listSpd = $this->lspd->proses_spd_list($listSpd, 'booking');
        $listHistorySpd = $this->lspd->proses_spd_list($listHistorySpd, 'booking');

        $data['list'] = $listSpd;
        $data['history'] = $listHistorySpd;
        $data['tipe_screen'] = $tipesc;
        $data['modal_chat'] = $this->lspd->load_spd_chat();
        $data['modal_tujuan'] = $this->lspd->load_spd_tujuan();
        $data['modal_history'] = $this->lspd->load_spd_history();
        $data['session_nik'] = $this->data['user']->nik;
        $data['pesawat_merk'] = $this->dspd->get_travel_merk_trans(array(
            'transport' => "pesawat",
        ));
        $data['taxi_merk'] = $this->dspd->get_travel_merk_trans(array(
            'transport' => "taxi",
        ));

        // =============================================================================================
        $arraydata      = "";
        $session_nik    = $this->data['user']->nik;
        foreach ($listSpd as $v) {
            $filtered   = [];
            $arrayd     = "";
            $tujuan     = "";
            $id_detail  = "";
            $berangkat  = "";
            $kembali    = "";
            $id_detail  = "";
            $data = explode('</li>', $v->actions);

            if ($v->nik == $session_nik) {
                $action = (array_filter(
                    $data,
                    function ($var) {
                        return (stripos($var, 'spd-booking') === false);
                    }
                ));
                $action = implode('</li>', $action);
            } else {
                $action = $v->actions;
            }

            foreach ($v->details_trip as $dt) {
                $tanggal_berangkatx  = date('d-m-Y', strtotime($dt->start_date));
                $jam_berangkatx      = date('h:m', strtotime($dt->start_time));
                $tanggal_kembalix    = date('d-m-Y', strtotime($dt->end_date));
                $jam_kembalix        = date('h:m', strtotime($dt->end_time));
                $tanggal_berangkat  = $tanggal_berangkatx . " " . $jam_berangkatx;
                $tanggal_kembali    = $tanggal_kembalix . " " . $jam_kembalix;

                $id_detail          = $dt->id_travel_detail;

                $berangkat  = $tanggal_berangkat;
                $kembali    = $tanggal_kembali;
                if ($v->tipe_trip == "single") {
                    $tujuan = $v->tujuan_lengkap;
                } else {
                    $tujuan = $dt->tujuan_lengkap;
                }
                break 1;
            }
            $datatiket = isset($v->transportasi_tiket_pesawat) ? $v->transportasi_tiket_pesawat : "";
            $explode_dt_tiket = $datatiket != "" ? explode(',', $datatiket) : "";
            $status_tiket_berangkat = "";
            $status_tiket_kembali = "";
            $status_tipe1 = "";
            $status_tipe2 = "";
            if ($datatiket != "") {
                foreach ($explode_dt_tiket as $dttiket) {
                    $exp_dt = explode("|", $dttiket);
                    // keberangkatan
                    if ($id_detail == $exp_dt[3] && $exp_dt[2] == "berangkat") {
                        $status_tiket_berangkat = $exp_dt[0];
                        $status_tipe1 = $exp_dt[2];
                    }

                    // kepulangan
                    if ($id_detail == $exp_dt[3] && $exp_dt[2] == "kembali") {
                        $status_tiket_kembali = $exp_dt[0];
                        $status_tipe2 = $exp_dt[2];
                    }
                }
            }

            $berangkat   = 'Tanggal      : ' . $berangkat . '<br/><span class="label label-success">'
                . $status_tiket_berangkat . '</span>';
            $kembali     = 'Tanggal      : ' . $kembali . '<br/><span class="label label-success">'
                . $status_tiket_kembali . "</span>";
            $actions      = "<div class='input-group-btn'>
                                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span>
                                    </button>
                                    <ul class='dropdown-menu pull-right'>"
                . $action .
                "</ul>
                                </div>";
            $arrayd      .= "['" . $v->nik . "', '" . $v->nama_karyawan . "', '" . $v->no_trip . "<span style='display:none'>" . $v->id_travel_header . "</span>', '" . $v->activity_label . "', '" . $tujuan . "', '" . $berangkat . "', '" . $kembali . "'],";
            $arraydata .= $arrayd;

            // add for multitrip 
            if ($v->tipe_trip == "multi") {
                $arrayd2 = "";
                $exclude = 0;
                foreach ($v->details_trip as $dt) {
                    $status_tiket_berangkat = "";
                    $status_tiket_kembali = "";
                    $status_tipe1 = "";
                    $status_tipe2 = "";
                    if ($exclude > 0) {
                        $status = "<span class='badge'>Issued</span>";
                        $id_detail          = $dt->id_travel_detail;

                        $tanggal_berangkatx  = date('d-m-Y', strtotime($dt->start_date));
                        $jam_berangkatx      = date('h:m', strtotime($dt->start_time));
                        $tanggal_kembalix    = date('d-m-Y', strtotime($dt->end_date));
                        $jam_kembalix        = date('h:m', strtotime($dt->end_time));
                        $tanggal_berangkat  = $tanggal_berangkatx . " " . $jam_berangkatx;
                        $tanggal_kembali    = $tanggal_kembalix . " " . $jam_kembalix;

                        $berangkat  = $tanggal_berangkat;
                        $kembali    = $tanggal_kembali;

                        if ($datatiket != "") {
                            foreach ($explode_dt_tiket as $dttiket) {
                                $exp_dt = explode("|", $dttiket);
                                // keberangkatan
                                if ($id_detail == $exp_dt[3] && $exp_dt[2] == "berangkat") {
                                    $status_tiket_berangkat  = $exp_dt[0];
                                    $status_tipe1            = $exp_dt[2];
                                }

                                // kepulangan
                                if ($id_detail == $exp_dt[3] && $exp_dt[2] == "kembali") {
                                    $status_tiket_kembali    = $exp_dt[0];
                                    $status_tipe2            = $exp_dt[2];
                                }
                            }
                        }

                        $berangkat   = "Tanggal      : " . $berangkat . "<br/>"
                            . '<span class="label label-success">'
                            .   $status_tiket_berangkat
                            . "</span>";
                        $kembali     = "Tanggal      : " . $kembali . "<br/>"
                            . '<span class="label label-success">'
                            .   $status_tiket_kembali
                            . "</span>";
                        $arrayd2      .= "['" . $v->nik . "', '" . $v->nama_karyawan . "', '" . $v->no_trip . "<span style='display:none'>" . $v->id_travel_header . "</span>', '" . $v->activity_label . "', '" . $tujuan . "', '" . $berangkat . "', '" . $kembali . "'],";
                    }
                    $exclude++;
                }
                $arraydata .= $arrayd2;
            }
        }
        $DataforTable = rtrim($arraydata, ",");
        // ================================================================================================
        echo json_encode($DataforTable);
    }

    public function penerimaan()
    {
        $this->general->check_access();
        $this->general->connectDbPortal();
        $this->data['module'] = "Travel Penginapan Mess";
        $this->data['title'] = "Penerimaan Kedatangan SPD";

        $listSpd = $this->dspd->get_travel_header_booking_penerimaan(array(
            'pic' => $this->data['user']->nik
        ));

        /** Reproses list array SPD */
        $listSpd = $this->lspd->proses_spd_list($listSpd, 'penerimaan');

        $this->data['list'] = $listSpd;
        $transports = $this->dspd->get_travel_transport_master(array('jenis' => 'penjemputan'));

        $this->data['transports'] = $transports;
        $this->load->view('spd/penerimaan', $this->data);
    }

    public function add($param)
    {
        $this->general->check_session();
        $this->general->connectDbPortal();
        $this->data['module']   = "Travel Transportasi - Booking";
        $this->data['title']    = "Pemesanan Transportasi SPD";

        $idHeader = $this->generate->kirana_decrypt(str_replace("xyz", "=", $param));

        // ===================================================================================
        $listSpd = $this->dspd->get_travel_header_booking_transport(array(
            'id' => $idHeader
        ));
        $listBookTrans = $this->dspd->get_travel_booking_transport(array(
            'idheader' => $idHeader,
            'type'     => 'trans'
        ));

        $listBookHotel = $this->dspd->get_travel_booking_transport(array(
            'idheader' => $idHeader,
            'type'     => 'hotel'
        ));
        $listAuthNiks = $this->dspd->get_data_book_oto(array(
            'nik' => $this->data['user']->nik
        ));

        if (count($listAuthNiks) == 0) {
            $listAuthNiks = array($this->data['user']->nik);
            $tipesc       = ['user'];
        } else {
            $listAuthNiks = array_map(function ($v) {
                return $v->nik;
            }, $listAuthNiks);
            $tipesc       = ['pemesan'];
        }

        $transport_primary_booked = $this->dspd->get_trans_book(array(
            'id_travel_header' => $idHeader,
            'status_tiket_primary' => 'primary',

        ));

        $hotel_booked = $this->dspd->get_hotel_book(array(
            'id_travel_header' => $idHeader,
            'status_tiket_primary' => 'primary',

        ));

        $jumlah_trans = count($listBookTrans);
        //hitung jumlah transportasi primary
        $jumlah_trans_primary = 0;
        foreach ($listBookTrans as $dt) {
            if ($dt['tipe'] == 'primary' && in_array($dt['tiket_trans_jenis'], ['pesawat', 'taxi']))
                $jumlah_trans_primary++;
        }
        $jumlah_trans_primary_booked = count($transport_primary_booked);

        $this->data['list'] = $listSpd;
        $this->data['list_trans'] = $listBookTrans;
        $this->data['list_hotel'] = $listBookHotel;
        $this->data['tipe_screen'] = $tipesc;
        $this->data['jumlah_trans'] = $jumlah_trans;
        $this->data['jumlah_trans_primary'] = $jumlah_trans_primary;
        $this->data['jumlah_trans_primary_booked'] = $jumlah_trans_primary_booked;
        $this->data['jumlah_hotel'] = count($listBookHotel);
        $this->data['jumlah_hotel_booked'] = count($hotel_booked);

        $this->data['session_nik'] = $this->data['user']->nik;

        $this->data['pesawat_merk'] = $this->dspd->get_travel_merk_trans(array(
            'transport' => "pesawat",
        ));
        $this->data['taxi_merk'] = $this->dspd->get_travel_merk_trans(array(
            'transport' => "taxi",
        ));
        // ===============================================================================

        $this->load->view('spd/booking/add_booking', $this->data);
    }

    public function save_booking_spd($data = null)
    {
        $data = $this->input->post();
        $dataUser = $this->general->get_data_user();
        $sendEmail = false;
        if (isset($data['id_travel_header'])) {
            $id = $this->generate->kirana_decrypt($data['id_travel_header']);

            $this->general->connectDbPortal();

            $pengajuan = $this->dspd->get_travel_header(
                array(
                    'id' => $id,
                    'single' => true
                )
            );

            /** Cek kirim email approval ke role selanjutnya jika ada validasi spd yang harus dipesankan
             * transportasi terlebih dahulu
             */
            $role       = $this->dspd->get_role(array('level' => $pengajuan->approval_level, 'single' => true));
            $last_book  = $this->dspd->get_travel_transport(array('id_header' => $id, 'single' => true));
            if (isset($role)) {
                if ($role->v_transport_spd || $role->v_transport_spd_um) {
                    $sendEmail = true;
                }
            }


            if (!isset($pengajuan)) {
                $msg = "Tidak ada data yang disimpan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->begin_transaction();
                $upload_error = null;

                $last_bookid = "";
                /** Save transportasi */
                if (isset($data['transport'])) {
                    // var_dump($data['transport']);
                    if (isset($last_book)) {
                        $last_bookid = $last_book->id_travel_detail;
                    }
                    $uploaded_lampiran = array();

                    if (isset($_FILES)) {
                        $uploaddir = TR_PATH_FILE . TR_BOOKING_UPLOAD_FOLDER;
                        if (!file_exists($uploaddir)) {
                            mkdir($uploaddir, 0777, true);
                        }

                        $uploaded_gambar = null;

                        $lampirans = $_FILES['transport'];

                        $config['upload_path'] = $uploaddir;
                        $config['allowed_types'] = TR_UPLOAD_ALLOWED;
                        $config['max_size'] = TR_UPLOAD_MAX;
                        $config['mod_mime_fix'] = false;

                        $this->load->library('upload', $config);

                        try {
                            foreach ($lampirans['name'] as $i => $lampiran) {
                                $_FILES['lampiran_' . $i]['name'] = $id . "_" . date('YmdHis') . "_" . $lampirans['name'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['type'] = $lampirans['type'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['tmp_name'] = $lampirans['tmp_name'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['error'] = $lampirans['error'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['size'] = $lampirans['size'][$i]['lampiran'];

                                if ($_FILES['lampiran_' . $i]['error'] != 0) {
                                    switch ($_FILES['lampiran_' . $i]['error']) {
                                        case UPLOAD_ERR_INI_SIZE:
                                            $upload_error[] = 'Lampiran atau berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                            break;
                                        case UPLOAD_ERR_EXTENSION:
                                            $upload_error[] = 'Lampiran atau berkas yang Anda coba untuk unggah tidak diperbolehkan.';
                                            break;
                                    }
                                }

                                if ($_FILES['lampiran_' . $i]['size'] > 0) {
                                    $this->upload->initialize($config, true);
                                    if ($this->upload->do_upload('lampiran_' . $i)) {
                                        $upload_data = $this->upload->data();
                                        $uploaded_lampiran[$i] = TR_BOOKING_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                        $data['transport'][$i]['lampiran'] = TR_BOOKING_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                    } else {
                                        $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. ' . $this->upload->display_errors('', '');
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            $upload_error[] = $e->getMessage();
                        }

                        if (count($upload_error) > 0) {
                            foreach ($uploaded_lampiran as $lampiran) {
                                unlink(TR_PATH_FILE . $lampiran);
                            }
                            if (count($upload_error) > 0)
                                $msg = join('<br/>', $upload_error);
                            else
                                $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            echo json_encode($return);
                            return;
                        }
                    }

                    $transports = $data['transport'];
                    $iddet      = "";
                    foreach ($transports as $transport) {
                        if (isset($transport['jadwal']) && !empty($transport['jadwal'])) {
                            $jadwal = date_create($transport['jadwal']);
                            unset($transport['jadwal']);
                            $transport['tanggal'] = $jadwal->format('Y-m-d');
                            $transport['jam'] = $jadwal->format('H:i:s');
                        }
                        $transport['id_travel_header'] = $id;
                        $iddet                         = $transport['id_travel_detail'] == "kembali" ? $last_bookid : $transport['id_travel_detail'];
                        if (isset($transport['harga'])) {
                            $transport['harga'] = $this->generate->revert_rupiah($transport['harga']);
                        }

                        if (strpos($transport['id_travel_detail'], "kembali") !== false) {
                            $id_det = explode("_", $transport['id_travel_detail']);
                            $transport['id_travel_detail'] = $this->generate->kirana_decrypt($id_det[0]) != "kembali" && $this->generate->kirana_decrypt($id_det[0]) != "" ? $this->generate->kirana_decrypt($id_det[0]) : $iddet;
                        } else {
                            $transport['id_travel_detail'] = $this->generate->kirana_decrypt($transport['id_travel_detail']);
                        }
                        $transport['status_tiket'] = isset($transport['status_tiket']) ? $transport['status_tiket'] : " ";
                        if ($transport['status_tiket'] == "on" && $transport['status_tiket'] != "") {
                            $transport['status_tiket'] = "Issued";
                        } else if ($transport['status_tiket'] != "on" && $transport['status_tiket'] != "") {
                            $transport['status_tiket'] = "Cancel";
                        } else {
                            $transport['status_tiket'] = "";
                        }
                        $transport['status_tiket_refund'] = isset($transport['status_tiket_refund']) ? $transport['status_tiket_refund'] : " ";
                        $transport['keterangan'] = isset($transport['keterangan']) ? htmlentities($transport['keterangan']) : "";
                        $transport['alasan_cancel'] = isset($transport['alasan_cancel']) ? htmlentities($transport['alasan_cancel']) : "";
                        if (isset($transport['id_travel_transport']) && !empty($transport['id_travel_transport'])) {
                            $idTransport = $this->generate->kirana_decrypt($transport['id_travel_transport']);
                            unset($transport['id_travel_transport']);
                            $dataBooking = $this->dgeneral->basic_column('update', $transport);
                            $this->db->update(
                                'tbl_travel_transport',
                                $dataBooking,
                                array(
                                    'id_travel_transport' => $idTransport
                                )
                            );
                        } else {
                            unset($transport['id_travel_transport']);
                            $dataBooking = $this->dgeneral->basic_column('insert_full', $transport);
                            $this->db->insert(
                                'tbl_travel_transport',
                                $dataBooking
                            );
                        }
                    }
                }

                if (isset($data['penginapan'])) {
                    $uploaded_lampiran = array();

                    if (isset($_FILES)) {
                        $uploaddir = TR_PATH_FILE . TR_BOOKING_UPLOAD_FOLDER;
                        if (!file_exists($uploaddir)) {
                            mkdir($uploaddir, 0777, true);
                        }

                        $uploaded_gambar = null;

                        $lampirans = $_FILES['penginapan'];

                        $config['upload_path'] = $uploaddir;
                        $config['allowed_types'] = TR_UPLOAD_ALLOWED;
                        $config['max_size'] = TR_UPLOAD_MAX;
                        $config['mod_mime_fix'] = false;

                        $this->load->library('upload', $config);

                        try {
                            foreach ($lampirans['name'] as $i => $lampiran) {
                                $_FILES['lampiran_' . $i]['name'] = $id . "_" . date('YmdHis') . "_" . $lampirans['name'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['type'] = $lampirans['type'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['tmp_name'] = $lampirans['tmp_name'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['error'] = $lampirans['error'][$i]['lampiran'];
                                $_FILES['lampiran_' . $i]['size'] = $lampirans['size'][$i]['lampiran'];

                                if ($_FILES['lampiran_' . $i]['error'] != 0) {
                                    switch ($_FILES['lampiran_' . $i]['error']) {
                                        case UPLOAD_ERR_INI_SIZE:
                                            $upload_error[] = 'Lampiran atau berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                            break;
                                        case UPLOAD_ERR_EXTENSION:
                                            $upload_error[] = 'Lampiran atau berkas yang Anda coba untuk unggah tidak diperbolehkan.';
                                            break;
                                    }
                                }

                                if ($_FILES['lampiran_' . $i]['size'] > 0) {
                                    $this->upload->initialize($config, true);
                                    if ($this->upload->do_upload('lampiran_' . $i)) {
                                        $upload_data = $this->upload->data();
                                        $uploaded_lampiran[$i] = TR_BOOKING_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                        $data['penginapan'][$i]['lampiran'] = TR_BOOKING_UPLOAD_FOLDER
                                            . $upload_data['file_name'];
                                    } else {
                                        $upload_error[] = 'Lampiran ke ' . ($i + 1) . '. ' . $this->upload->display_errors('', '');
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            $upload_error[] = $e->getMessage();
                        }

                        if (count($upload_error) > 0) {
                            foreach ($uploaded_lampiran as $lampiran) {
                                unlink(TR_PATH_FILE . $lampiran);
                            }
                            if (count($upload_error) > 0)
                                $msg = join('<br/>', $upload_error);
                            else
                                $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";

                            $return = array('sts' => $sts, 'msg' => $msg);
                            echo json_encode($return);
                            return;
                        }
                    }

                    $penginapans = $data['penginapan'];
                    if ($penginapans[1]['id_travel_detail'] != "") {
                        foreach ($penginapans as $penginapan) {
                            $penginapan['id_travel_header'] = $id;
                            $penginapan['id_travel_detail'] = $this->generate->kirana_decrypt($penginapan['id_travel_detail']);

                            $penginapan['start_date'] = $this->generate->regenerateDateFormat($penginapan['start_date']);
                            $penginapan['end_date'] = $this->generate->regenerateDateFormat($penginapan['end_date']);

                            $penginapan['keterangan'] = isset($penginapan['keterangan']) ? htmlentities($penginapan['keterangan']) : "";
                            if (isset($penginapan['id_travel_hotel']) && !empty($penginapan['id_travel_hotel'])) {
                                $idHotel = $this->generate->kirana_decrypt($penginapan['id_travel_hotel']);
                                unset($penginapan['id_travel_hotel']);
                                $dataBooking = $this->dgeneral->basic_column('update', $penginapan);
                                $this->db->update(
                                    'tbl_travel_hotel',
                                    $dataBooking,
                                    array(
                                        'id_travel_hotel' => $idHotel
                                    )
                                );
                            } else {
                                unset($penginapan['id_travel_hotel']);
                                $dataBooking = $this->dgeneral->basic_column('insert_full', $penginapan);
                                $this->db->insert(
                                    'tbl_travel_hotel',
                                    $dataBooking
                                );
                            }
                        }
                    }
                }

                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                } else {
                    $this->dgeneral->commit_transaction();
                    $msg = "Booking berhasil disimpan";
                    $sts = "OK";

                    if ($sendEmail) {
                        try {
                            $result = $this->send_approval_email_spd($pengajuan);
                            if ($result['sts'] == "NotOK")
                                $sendEmailResult = false;
                        } catch (Exception $exception) {
                            $sendEmailResult = false;
                        }
                    }
                }
            }
        }

        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }

    public function save_refund($param, $data, $appbyfinal = NULL)
    { // outstanding
        if (isset($data['approvals'])) {
            if (!is_array($data['approvals']))
                $approvals = json_decode($data['approvals']);
            else {
                $approvals = json_decode(json_encode($data['approvals'], false));
            }

            if (count($approvals) > 0) {
                foreach ($approvals as $approval) {
                    if (isset($approval->id)) {
                        $this->general->connectDbPortal();
                        $this->dgeneral->begin_transaction();
                        $id     = $this->generate->kirana_decrypt($approval->id);
                        $updateData = array(
                            'has_refund' => '1',
                        );
                        $updateData = $this->dgeneral->basic_column('update', $updateData);

                        $this->db->update(
                            'tbl_travel_transport',
                            $updateData,
                            array(
                                'id_travel_transport' => $id
                            )
                        );
                        if ($this->dgeneral->status_transaction() === false) {
                            $this->dgeneral->rollback_transaction();
                            $msg = "Periksa kembali data yang dimasukkan";
                            $sts = "NotOK";
                            $return = array('sts' => $sts, 'msg' => $msg);

                            return $return;
                            exit();
                        } else {
                            $this->dgeneral->commit_transaction();
                            $msg = "Data berhasil ditambahkan";
                            $sts = "OK";
                        }
                        $this->general->closeDb();
                        // $data   = $this->rfc("sync_travel_main",$id,NULL);
                        // $msg    = $data['msg'];
                        // $sts    = $data['sts'];
                    }
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

    public function save_final_booking($param, $data)
    { // outstanding
        if (isset($data['complete_trans_hotel']) && $data['complete_trans_hotel'] == '1') {

            if (isset($data['id_header'])) {
                $this->general->connectDbPortal();
                $this->dgeneral->begin_transaction();
                $id     = $this->generate->kirana_decrypt($data['id_header']);
                $updateDataHeader = array(
                    'approval_level' => '99',
                    'approval_status' => '4',
                    'status_transportasi' => '1',

                );
                $updateData = $this->dgeneral->basic_column('update', $updateDataHeader);

                $this->db->update(
                    'tbl_travel_header',
                    $updateData,
                    array(
                        'id_travel_header' => $id
                    )
                );
                if ($this->dgeneral->status_transaction() === false) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);

                    return $return;
                    exit();
                } else {

                    $check_hist_tiket = $this->dspd->get_travel_log_status(array(
                        'remark'               => 'Booking Transportasi dan Penginapan',
                        'id_travel_header'     => $id,
                    ));

                    if (count($check_hist_tiket) == 0) {
                        $this->lspd->travel_log(
                            array(
                                'id_travel_header'  => $id,
                                'action'            => 'Pemesanan',
                                'remark'            => 'Booking Transportasi dan Penginapan',
                                'comment'           => 'By HR',
                                'actor'             => base64_decode($this->session->userdata("-nik-")),
                            )
                        );
                    }
                    $this->dgeneral->commit_transaction();
                    $msg = "Data berhasil ditambahkan";
                    $sts = "OK";
                }
                $this->general->closeDb();
                // $data   = $this->rfc("sync_travel_main",$id,NULL);
                // $msg    = $data['msg'];
                // $sts    = $data['sts'];
            }
        } else {
            $msg = "Tidak ada data yang disimpan";
            $sts = "OK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    public function gets($param = NULL, $param2 = NULL, $param3 = NULL, $param4 = NULL)
    {
        switch ($param) {
            case 'list_transportasi_header':
                $listAuthNiks = $this->dspd->get_data_book_oto(array(
                    'nik' => $this->data['user']->nik
                ));

                if (count($listAuthNiks) == 0) {
                    $listAuthNiks = array($this->data['user']->nik);
                    $tipesc       = ['user'];
                } else {
                    $listAuthNiks = array_map(function ($v) {
                        return $v->nik;
                    }, $listAuthNiks);
                    $tipesc       = ['pemesan'];
                }

                $inputs      = $_POST;
                $kelengkapan = isset($inputs['kelengkapan']) ? $inputs['kelengkapan'] : null;

                $listSpd = $this->dspd->get_travel_header_booking_transport_bom(array(
                    'nik'           => $listAuthNiks,
                    'kelengkapan'   => $kelengkapan,
                ));

                $this->data['list'] = $listSpd;
                header('Content-Type: application/json');
                $return = $listSpd;
                echo $return;

                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    public function get($param = null)
    {
        $inputs = $this->input->post();
        switch ($param) {
            case "booking":
                $inputs = $this->input->post();
                $idHeader = ($inputs['id']);
                $pengajuan = $this->dspd->get_travel_header(
                    array(
                        'id' => $idHeader,
                        'single' => true
                    )
                );
                $pengajuan = $this->lspd->proses_spd_list_header($pengajuan);

                $pengajuan = $this->general->generate_encrypt_json($pengajuan, array('id_travel_header'));

                $transports = $this->dspd->get_travel_transport(
                    array(
                        'id_header' => $idHeader,
                    )
                );
                $transports = $this->general->generate_encrypt_json(
                    $transports,
                    array(
                        'id_travel_header', 'id_travel_detail', 'id_travel_transport'
                    )
                );
                $hotels = $this->dspd->get_travel_hotel(
                    array(
                        'id_header' => $idHeader,
                    )
                );
                $hotels = $this->general->generate_encrypt_json(
                    $hotels,
                    array(
                        'id_travel_header', 'id_travel_detail', 'id_travel_hotel'
                    )
                );

                $details = $this->dspd->get_travel_detail(
                    array(
                        'id_header' => $idHeader,
                    )
                );
                $lspd = $this->lspd;
                $details = array_map(function ($detail) use ($lspd) {
                    return $lspd->proses_spd_list_detail($detail);
                }, $details);

                $details = $this->general->generate_encrypt_json($details, array('id_travel_detail', 'id_travel_header'));
                $transport_pesawat = $this->dspd->get_travel_merk_trans(array(
                    'transport' => "pesawat",
                ));
                $transport_taxi = $this->dspd->get_travel_merk_trans(array(
                    'transport' => "taxi",
                ));
                $personel = $this->dspd->get_karyawan(array('nik' => $pengajuan->nik, 'single' => true));
                $result['data'] = compact(
                    'pengajuan',
                    'transports',
                    'hotels',
                    'details',
                    'personel',
                    'transport_pesawat',
                    'transport_taxi'

                );
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case "booking_trans":
                $inputs         = $this->input->post();
                $id_travel_transport = $this->generate->kirana_decrypt($inputs['id_travel_transport']);
                $data_booking   = $this->dspd->get_trans_book(
                    array(
                        'id_travel_transport'  => $id_travel_transport,
                        'single'            => true
                    )
                );
                $result['data'] = $data_booking;
                $result['sts'] = 'OK';
                $result['msg'] = '';
                break;
            case "booking_hotel":
                $inputs             = $this->input->post();
                $id_travel_hotel    = $this->generate->kirana_decrypt($inputs['id_travel_hotel']);
                $data_booking       = $this->dspd->get_hotel_book(
                    array(
                        'id_travel_hotel'  => $id_travel_hotel,
                        'single'            => true
                    )
                );
                $result['data'] = $data_booking;
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

    public function save($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'refund':
                $return = $this->save_refund($param, $data);
                break;
            case 'final':
                $return = $this->save_final_booking($param, $data);
                break;

            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }
}
