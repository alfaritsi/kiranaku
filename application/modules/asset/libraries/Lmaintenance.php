<?php

defined('BASEPATH') or exit('No direct script access allowed');

const ASET_PERIODE_CONV = array('hari' => 'days', 'minggu' => 'weeks', 'bulan' => 'months');

class Lmaintenance
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('dmaintenance');
    }

    public function save_status_maintenance($id_main = null, $jenis_tindakan = 'perawatan', $nik = 0, $complete = true)
    {
        if (isset($id_main)) {
            $last_status = $this->CI->dmaintenance->get_pm_status(array('id_main' => $id_main, 'single_row' => true));
            $data_row = array(
                "id_main" => $id_main,
                "nik" => $nik,
            );

            if (!isset($last_status)) {
                $data_row["status"] = 'scheduled';
                $data_row["keterangan"] = 'Telah dibuat jadwal ' . $jenis_tindakan;

                $data_row = $this->CI->dgeneral->basic_column("insert", $data_row);
                $this->CI->dgeneral->insert("tbl_inv_main_status", $data_row);
            } else {
                $data_main = $this->CI->dmaintenance->get_main(array('id_main' => $id_main, 'single_row' => true));

                switch ($last_status->status) {
                    case 'scheduled':
                        $data_row["status"] = 'onprogress';
                        $data_row["keterangan"] = ucfirst($jenis_tindakan) . ' sedang dikerjakan';
                        break;
                    case 'onprogress':
                        if ($data_main->pengguna == 'fo') {
                            $data_row["status"] = 'confirmpic';
                            $data_row["keterangan"] = ucfirst($jenis_tindakan) . ' sedang menunggu konfirmasi PIC';
                        } else if ($data_main->pengguna !== 'fo' && isset($data_main->pic_nik)) {
                            $data_row["status"] = 'confirmpic';
                            $data_row["keterangan"] = ucfirst($jenis_tindakan) . ' sedang menunggu konfirmasi PIC';
                        } else {
                            $data_row["status"] = 'complete';
                            $data_row["keterangan"] = ucfirst($jenis_tindakan) . ' telah selesai';
                        }
                        break;
                    case 'confirmpic':
                        $data_row["status"] = 'complete';
                        $data_row["keterangan"] = ucfirst($jenis_tindakan) . ' telah selesai';
                        break;
                }
                if ($data_main->pengguna !== 'fo' && ($complete) && (empty(@$data_main->pic_nik))) {
                    $data_row["status"] = 'complete';
                    $data_row["keterangan"] = ucfirst($jenis_tindakan) . ' telah selesai';
                }

                if ($last_status->status != 'complete') {
                    $data_row = $this->CI->dgeneral->basic_column("insert", $data_row);
                    $this->CI->dgeneral->insert("tbl_inv_main_status", $data_row);
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function send_konfirmasi_email($main = null)
    {
        if (isset($main)) {

            if (isset($main->pic) && !empty($main->pic)) {

                $email = PM_EMAIL_DEBUG_MODE ? json_decode(PM_EMAIL_TESTER) : $main->email_pic;
                $emailOri = $main->email_pic;
                $message = $this->CI->load->view('emails/mail_konfirmasi_user', compact('main', 'emailOri'), true);

                $return = $this->CI->general->send_email_new(
                    array(
                        'subject' => 'Konfirmasi Maintenance User',
                        'from_alias' => 'KiranaKu Asset Maintenance',
                        'message' => $message,
                        'to' => $email
                    )
                );

                if ($return['sts'] == 'NotOK') {
                    return $return;
                } else
                    return false;
            } else
                // Tidak ada PIC
                return true;
        } else
            return false;
    }

    public function generate_jadwal($id_periode = null, $main = null, $tanggal_selesai = null, $pic_check = false)
    {
        if (isset($id_periode)) {
            $periode = $this->CI->dmaintenance->get_periode(array(
                'id_periode' => $id_periode,
                'single_row' => true
            ));
            // cek auto gen & bikin data main baru
            if (isset($periode) && $periode->auto_gen == 'y') {
                if ((!$pic_check and empty($main->pic)) or $pic_check) {
                    // //lha 
                    // if(($periode->periode_jumlah==1)and($periode->periode=='hari')) {
                    // $cek_tanggal_kerja	= $this->CI->dmaintenance->get_data_tanggal_kerja(array(
                    // 'tanggal_selesai'	=> $tanggal_selesai,
                    // 'single_row' 		=> true
                    // ));
                    // // echo json_encode($cek_tanggal_kerja->tanggal_kerja);
                    // // exit();

                    // $jadwal_service = $cek_tanggal_kerja->tanggal_kerja;
                    // }else{
                    // $jadwal_service = date_create($this->CI->generate->regenerateDateFormat($tanggal_selesai))
                    // ->modify('+' . $periode->periode_jumlah . ' ' . ASET_PERIODE_CONV[$periode->periode])
                    // ->format('Y-m-d');
                    // }
                    $jadwal_service = date_create($this->CI->generate->regenerateDateFormat($tanggal_selesai))
                        ->modify('+' . $periode->periode_jumlah . ' ' . ASET_PERIODE_CONV[$periode->periode])
                        ->format('Y-m-d');

                    return $this->save_jadwal(
                        array(
                            'id_jenis' => $main->id_jenis,
                            'id_periode' => $main->id_periode,
                            'jenis_tindakan' => $main->jenis_tindakan,
                            'jadwal_service' => $jadwal_service,
                            'pengguna' => $main->pengguna,
                            'operator' => $main->operator,
                            'final' => 'n'
                        ),
                        array(
                            $this->CI->generate->kirana_encrypt($main->id_aset)
                        )
                    );
                } else
                    return true;
            } else
                return true;
        } else
            return false;
    }

    public function save_jadwal($data_row = null, $assets = array(), $finish = false)
    {
        $periode_detail = $this->CI->dmaintenance->get_periode_detail(
            array(
                'id_periode' => $data_row['id_periode'],
                'id_jenis' => $data_row['id_jenis']
            )
        );

        if (count($periode_detail) > 0) {
            foreach ($assets as $id_aset) {
                $id_aset = $this->CI->generate->kirana_decrypt($id_aset);
                $data_aset = $this->CI->dmaintenance->get_aset(array('id_aset' => $id_aset, 'single_row' => true));

                $data_row['id_aset'] = $id_aset;

                if (isset($data_aset->pic) && is_numeric($data_aset->pic))
                    $data_row['pic_nik'] = $data_aset->pic;

                $main_aset = $this->CI->dmaintenance->get_pm_data(array(
                    'ho' => null,
                    'gsber' => null,
                    'id_aset' => $id_aset,
                    'single_row' => true
                ));

                //lha cr 2149
                if ($data_row['pengguna'] == 'fo') {
                    $data_row['operator'] = isset($data_row['operator']) && $data_row['operator'] !== null ? $data_row['operator'] : base64_decode($this->CI->session->userdata("-nik-"));
                } else {
                    $ck_jenis         = $this->CI->dtransaksiasset->get_data_jenis(NULL, $data_row['id_jenis']);
                    if ($ck_jenis[0]->pic > 0) {
                        $data_row['operator'] = $ck_jenis[0]->pic;
                    } else {
                        $operator = $this->CI->dmaintenance->get_agent(array('single_row' => true, 'kode' => $main_aset->kode));
                        if (isset($operator))
                            $data_row['operator'] = $operator->agent;
                        else
                            $data_row['operator'] = null;
                    }
                }

                $data_row = $this->CI->dgeneral->basic_column('insert', $data_row);

                $this->CI->dgeneral->insert("tbl_inv_main", $data_row);

                $id_main_terakhir = $this->CI->db->insert_id();

                $save_status = $this->save_status_maintenance($id_main_terakhir);

                $data_ans_batch = array();
                foreach ($periode_detail as $dt) {
                    $data_ans_row = array(
                        'id_main' => $id_main_terakhir,
                        'id_aset' => $id_aset,
                        "id_jenis" => $data_row['id_jenis'],
                        "pengguna" => $data_row['pengguna'],
                        'id_jenis_detail' => $dt->id_jenis_detail,
                        'nama_jenis_detail' => $dt->nama_jenis_detail,
                        'id_periode' => $dt->id_periode,
                        'id_periode_detail' => $dt->id_periode_detail,
                        'nama_periode_detail' => $dt->nama
                    );

                    if ($finish) {
                        $data_ans_row['keterangan'] = 'Selesai.';
                        $data_ans_row['cek'] = 'y';
                    }

                    $data_ans_row = $this->CI->dgeneral->basic_column('insert_full', $data_ans_row);
                    array_push($data_ans_batch, $data_ans_row);
                }

                $this->CI->dgeneral->insert_batch('tbl_inv_main_detail', $data_ans_batch);

                if ($finish) {
                    $save_status = $this->save_status_maintenance($id_main_terakhir, $data_row['jenis_tindakan'], 0, true);
                }
            }
            return true;
        } else
            return false;
    }
}
