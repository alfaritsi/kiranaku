<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : PM API
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */
Class Api extends REST_Controller
{
    private $ci;

    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        ini_set('memory_limit', '800M');
        ini_set('max_execution_time', 14000);
        $this->load->model('dmasterasset');
        $this->load->model('dtransaksiasset');
        $this->load->model('dmaintenance');
        $this->load->library('lmaintenance');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    /**
    private function save_status_maintenance($id_main = null, $jenis_tindakan = 'perawatan', $nik = 0)
    {
        if (isset($id_main)) {
            $last_status = $this->dmaintenance->get_pm_status(array('id_main' => $id_main, 'single_row' => true));
            $data_row = array(
                "id_main" => $id_main,
                "nik" => $nik,
            );

            if (!isset($last_status)) {
                $data_row["status"] = 'scheduled';
                $data_row["keterangan"] = 'Telah dibuat jadwal ' . $jenis_tindakan;

                $data_row = $this->dgeneral->basic_column("insert", $data_row);
                $this->dgeneral->insert("tbl_inv_main_status", $data_row);
            } else {
                switch ($last_status->status) {
                    case 'scheduled':
                        $data_row["status"] = 'onprogress';
                        $data_row["keterangan"] = $jenis_tindakan . ' sedang dikerjakan';
                        break;
                    case 'onprogress':
                        if (isset($main->pic)) {
                            $data_row["status"] = 'confirmpic';
                            $data_row["keterangan"] = $jenis_tindakan . ' sedang menunggu konfirmasi PIC';
                        } else {
                            $data_row["status"] = 'complete';
                            $data_row["keterangan"] = $jenis_tindakan . ' telah selesai';
                        }
                        break;
                    case 'confirmpic':
                        $data_row["status"] = 'complete';
                        $data_row["keterangan"] = $jenis_tindakan . ' telah selesai';
                        break;
                }
                if ($last_status->status != 'complete') {
                    $data_row = $this->dgeneral->basic_column("insert", $data_row);
                    $this->dgeneral->insert("tbl_inv_main_status", $data_row);
                }
            }

            return true;
        } else {
            return false;
        }
    }

    private function save_jadwal($data_row = null, $assets = array())
    {
        foreach ($assets as $id_aset) {
            $id_aset = $this->generate->kirana_decrypt($id_aset);

            $data_row['id_aset'] = $id_aset;

            $main_aset = $this->dmaintenance->get_pm_data(array(
                'ho' => null,
                'gsber' => null,
                'id_aset' => $id_aset,
                'single_row' => true
            ));

            $operator = $this->dmaintenance->get_agent(array('single_row' => true, 'kode' => $main_aset->kode));
            if (isset($operator))
                $data_row['operator'] = $operator->agent;
            else
                $data_row['operator'] = 'Tim HO';

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $this->dgeneral->insert("tbl_inv_main", $data_row);

            $id_main_terakhir = $this->db->insert_id();

            $save_status = $this->lmaintenance->save_status_maintenance($id_main_terakhir, $data_row['jenis_tindakan']);

            $periode_detail = $this->dmaintenance->get_periode_detail(
                array(
                    'id_periode' => $data_row['id_periode'],
                    'id_jenis' => $data_row['id_jenis']
                )
            );
            $data_ans_batch = array();
            foreach ($periode_detail as $dt) {
                $data_ans_row = array(
                    'id_main' => $id_main_terakhir,
                    'id_aset' => $id_aset,
                    "id_jenis" => $data_row['id_jenis'],
                    'id_jenis_detail' => $dt->id_jenis_detail,
                    'nama_jenis_detail' => $dt->nama_jenis_detail,
                    'id_periode' => $dt->id_periode,
                    'id_periode_detail' => $dt->id_periode_detail,
                    'nama_periode_detail' => $dt->nama

                );

                $data_ans_row = $this->dgeneral->basic_column('insert_full', $data_ans_row);
                array_push($data_ans_batch, $data_ans_row);
            }

            $this->dgeneral->insert_batch('tbl_inv_main_detail', $data_ans_batch);
        }
    }
    **/

    function send_konfirmasi_email($main = null)
    {
        if (isset($main)) {

            if (isset($main->pic) && !empty($main->pic)) {

                $email = PM_EMAIL_DEBUG_MODE ? json_decode(PM_EMAIL_TESTER) : $main->email_pic;
                $emailOri = $main->email_pic;
                $message = $this->load->view('emails/mail_konfirmasi_user', compact('main', 'emailOri'), true);

                $return = $this->general->send_email_new(
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

    public function index_get()
    {

        $json = array(
            "message" => "Asset Internal API"
        );

        return $this->response($json, 200);
    }

    public function login_post()
    {
        $params = $this->post();
        $nik = isset($params['nik']) ? $params['nik'] : "";
        $pass = isset($params['password']) ? $params['password'] : "";

        $this->general->connectDbPortal();
        $agentCheck = $this->dmaintenance->get_agent(array('nik' => $nik, 'single_row' => true));

        if (isset($agentCheck)) {
            $data = $this->dgeneral->get_user_login($nik, $pass);

            if (isset($data)) {

                $profile = array(
                    'nik' => $data->nik,
                    'email' => $data->email,
                    'nama' => $data->nama,
                );

                $token_data = array(
                    'id_user' => $data->id_user,
                    'nik' => $data->nik,
                    'id_karyawan' => $data->id_karyawan,
                    'ho' => $data->ho,
                    'email' => $data->email,
                    'nama' => $data->nama,
                    'id_plant' => $data->gsber,
                );

                $this->response(array(
                    'status' => true,
                    'message' => 'User ditemukan',
                    'profile' => $profile,
                    'token_data' => $token_data
                ));
            } else {
                $this->response(array(
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ));
            }
        } else {
            $this->response(array(
                'status' => false,
                'message' => 'User tidak memiliki akses aplikasi ini.'
            ));
        }
    }

    public function jadwal_post()
    {
        $params = $this->post();
        $this->general->connectDbPortal();
        $id_aset = (isset($params['id_aset']) ? $this->generate->kirana_decrypt($params['id_aset']) : NULL);
        $id_main = (isset($params['id_main']) ? $this->generate->kirana_decrypt($params['id_main']) : NULL);
        $active = (isset($params['active']) ? $params['active'] : NULL);
        $deleted = (isset($params['deleted']) ? $params['deleted'] : NULL);
        $nama = (isset($params['nama']) ? $params['nama'] : NULL);
        if (isset($params['jenis'])) {
            $jenis = array();
            foreach ($params['jenis'] as $dt) {
                array_push($jenis, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $jenis = NULL;
        }

        if (isset($params['merk'])) {
            $merk = array();
            foreach ($params['merk'] as $dt) {
                array_push($merk, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $merk = NULL;
        }
        if (isset($params['pabrik'])) {
            $pabrik = array();
            foreach ($params['pabrik'] as $dt) {
                array_push($pabrik, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $pabrik = NULL;
        }
        if (isset($params['lokasi'])) {
            $lokasi = array();
            foreach ($params['lokasi'] as $dt) {
                array_push($lokasi, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $lokasi = NULL;
        }
        if (isset($params['area'])) {
            $area = array();
            foreach ($params['area'] as $dt) {
                array_push($area, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $area = NULL;
        }

        if (isset($params['main_status'])) {
            $main_status = array();
            foreach ($params['main_status'] as $dt) {
                array_push($main_status, $dt);
            }
        } else {
            $main_status = NULL;
        }

        $dtRaw = $this->dmaintenance->get_pm_data(
            array(
                'id_aset' => $id_aset,
                'id_main' => $id_main,
                'nama' => $nama,
                'pengguna' => 'it',
                'id_jenis' => $jenis,
                'id_merk' => $merk,
                'id_pabrik' => $pabrik,
                'id_lokasi' => $lokasi,
                'id_area' => $area,
                'main_status' => $main_status,
                'ho' => $params['ho'],
                'nik' => $params['nik'],
                'gsber' => $params['gsber'],
            )
        );

        $this->response(array(
            'status' => true,
            'message' => 'Jadwal ditemukan',
            'data' => $dtRaw
        ));
    }

    public function barcode_post()
    {
        $params = $this->post();
        $this->general->connectDbPortal();
        $id_aset = (isset($params['id_aset']) ? $this->generate->kirana_decrypt($params['id_aset']) : NULL);
        $id_main = (isset($params['id_main']) ? $this->generate->kirana_decrypt($params['id_main']) : NULL);
        $active = (isset($params['active']) ? $params['active'] : NULL);
        $deleted = (isset($params['deleted']) ? $params['deleted'] : NULL);
        $barcode = (isset($params['barcode']) ? $params['barcode'] : NULL);
        $nama = (isset($params['nama']) ? $params['nama'] : NULL);
        if (isset($params['jenis'])) {
            $jenis = array();
            foreach ($params['jenis'] as $dt) {
                array_push($jenis, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $jenis = NULL;
        }

        if (isset($params['merk'])) {
            $merk = array();
            foreach ($params['merk'] as $dt) {
                array_push($merk, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $merk = NULL;
        }
        if (isset($params['pabrik'])) {
            $pabrik = array();
            foreach ($params['pabrik'] as $dt) {
                array_push($pabrik, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $pabrik = NULL;
        }
        if (isset($params['lokasi'])) {
            $lokasi = array();
            foreach ($params['lokasi'] as $dt) {
                array_push($lokasi, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $lokasi = NULL;
        }
        if (isset($params['area'])) {
            $area = array();
            foreach ($params['area'] as $dt) {
                array_push($area, $this->generate->kirana_decrypt($dt));
            }
        } else {
            $area = NULL;
        }

        if (isset($params['main_status'])) {
            $main_status = array();
            foreach ($params['main_status'] as $dt) {
                array_push($main_status, $dt);
            }
        } else {
            $main_status = NULL;
        }

        $dtRaw = $this->dmaintenance->get_pm_data(
            array(
                'id_aset' => $id_aset,
                'id_main' => $id_main,
                'nama' => $nama,
                'pengguna' => 'it',
                'id_jenis' => $jenis,
                'id_merk' => $merk,
                'id_pabrik' => $pabrik,
                'id_lokasi' => $lokasi,
                'id_area' => $area,
                'main_status' => $main_status,
                'barcode' => $barcode,
                'ho' => $params['ho'],
                'gsber' => $params['gsber']
            )
        );

        if (isset($dtRaw) && !empty($dtRaw)) {
            $dtRaw = $this->general->generate_encrypt_json($dtRaw, array('id_main', 'id_aset', 'id_kategori'));

            $this->response(array(
                'status' => true,
                'message' => 'Aset ditemukan',
                'data' => $dtRaw
            ));
        } else
            $this->response(array(
                'status' => false,
                'message' => 'Aset tidak ditemukan',
            ));

    }

    public function maintenance_post()
    {
        $params = $this->post();
        $this->general->connectDbPortal();
        $id_main = (isset($params['id_main']) ? $this->generate->kirana_decrypt($params['id_main']) : NULL);

        if (isset($params['main_status'])) {
            $main_status = array();
            foreach ($params['main_status'] as $dt) {
                array_push($main_status, $dt);
            }
        } else {
            $main_status = NULL;
        }

        $data = $this->dmaintenance->get_pm_data(
            array(
                'id_main' => $id_main,
                'main_status' => $main_status,
                'ho' => $params['ho'],
                'gsber' => $params['gsber'],
                'single_row' => true
            )
        );
        if (isset($data)) {
            if ($data->jenis_tindakan == 'perawatan') {
                $data->detail = $this->general->generate_encrypt_json(
                    $this->dmaintenance->get_pm_detail(
                        array(
                            'id_main' => $id_main
                        )
                    ),
                    array(
                        'id_main_detail',
                        'id_main',
                        'id_aset',
                        'id_jenis_detail',
                        'id_periode',
                        'id_periode_detail'
                    )
                );
            } else {
                $data->detail = $this->general->generate_encrypt_json(
                    $this->dmaintenance->get_pm_items(
                        array(
                            'id_main' => $id_main
                        )
                    ),
                    array(
                        'id_main_item',
                        'id_main',
                        'id_jenis',
                        'id_jenis_detail',
                    )
                );
            }

            $data = $this->general->generate_encrypt_json(
                $data,
                array('id_main', 'id_aset', 'id_kategori', 'id_kondisi')
            );
        }

        $kondisi = $this->general->generate_encrypt_json(
            $this->dmaintenance->get_kondisi(),
            array('id_kondisi')
        );

        $this->response(array(
            'status' => true,
            'message' => 'Aset ditemukan',
            'data' => $data,
            'kondisi' => $kondisi,
        ));
    }

    public function maintenance_history_post()
    {
        $params = $this->post();
        $this->general->connectDbPortal();
        $id_aset = (isset($params['id_aset']) ? $this->generate->kirana_decrypt($params['id_aset']) : NULL);
        $id_main = (isset($params['id_main']) ? $this->generate->kirana_decrypt($params['id_main']) : NULL);


        $dtRaw = json_decode(
            $this->dmaintenance->get_pm_history(
                array(
                    'id_aset' => $id_aset,
                    'id_main' => $id_main,
                    'ho' => $params['ho'],
                    'gsber' => $params['gsber']
                )
            ), true);

        if (isset($dtRaw['data'])) {
            foreach ($dtRaw['data'] as &$data) {
                if ($data['jenis_tindakan'] == 'perawatan') {
                    $data['detail'] = $this->dmaintenance->get_pm_detail(
                        array(
                            'id_main' => $data['id_main']
                        )
                    );
                } else {
                    $data['detail'] = $this->dmaintenance->get_pm_items(
                        array(
                            'id_main' => $data['id_main']
                        )
                    );
                }
            }
        }

        $dtRaw['data'] = $this->general->generate_encrypt_json($dtRaw['data'], array('id_main', 'id_aset', 'id_kategori'));

        $this->response(array(
            'status' => true,
            'message' => 'Aset History ditemukan',
            'data' => $dtRaw['data']
        ));
    }

    public function maintenance_items_post()
    {
        $this->general->connectDbPortal();

        $params = $this->post();
        $id_main = (isset($params['id_main']) ? $this->generate->kirana_decrypt($params['id_main']) : NULL);

        $data = $this->dmaintenance->get_main_items(
            array(
                'pengguna' => 'it',
                'id_main' => $id_main,
            )
        );

        foreach ($data as &$item) {
            $item->pilihan = array();
            if (isset($item->kolom_aset) && !empty($item->kolom_aset)) {
                $master = $this->dmasterasset->get_aset_detail_master(null, null, $item->kolom_aset);
                if(isset($master) && count($master)>0)
                {
                    $item->satuan = $master[0]->satuan;
                }
                $item->pilihan = $this->dmasterasset->get_aset_detail_opsi(null, null, $item->kolom_aset);
            }
        }

        $this->response(array(
            'status' => true,
            'message' => 'Main Items ditemukan',
            'data' => $data
        ));
    }

    public function maintenance_save_post()
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $params = $this->post();
        $tanggal_mulai = isset($params['tanggal_mulai']) ? $params['tanggal_mulai'] : null;
        $tanggal_selesai = isset($params['tanggal_selesai']) ? $params['tanggal_selesai'] : null;

//        var_dump($params);die();
        if (isset($params['id_main']) && trim($params['id_main']) !== "") {
            $id_main = $this->generate->kirana_decrypt($params['id_main']);

            $main = $this->dmaintenance->get_pm_data(array('ho' => $params['ho'], 'gsber' => $params['gsber'], 'id_main' => $id_main, 'single_row' => true));

            if (isset($main)) {
                // save status on progress
                $save_status = $this->lmaintenance->save_status_maintenance($id_main, $params['jenis_tindakan'], $params['nik']);

                $data_row = array(
                    'jenis_tindakan' => $params['jenis_tindakan'],
                    "tanggal_mulai" => date_create($tanggal_mulai)->format('Y-m-d'),
                    "tanggal_selesai" => date_create($tanggal_selesai)->format('Y-m-d'),
                    "catatan_service" => $params['catatan_service'],
                    "final" => 'n'
                );

                if ($data_row['jenis_tindakan'] != 'perawatan') {
                    $data_row['tanggal_rusak'] = date_create($params['tanggal_rusak'])->format('Y-m-d');
                }

                // jika tidak ada pic aset, maka langsung sudah approved
                if (empty($main->pic)) {
                    $data_row['pic_approve'] = 'y';
                }

                $data_row = $this->dgeneral->basic_column("update", $data_row);
                $this->dgeneral->update("tbl_inv_main", $data_row, array(
                    array(
                        'kolom' => 'id_main',
                        'value' => $id_main
                    )
                ));
                if ($data_row['jenis_tindakan'] == 'perawatan') {
                    $details = $params['details'];
                    foreach ($details as $detail) {
                        $cek = $detail['cek'];

                        $data_row_detail = array(
                            "keterangan" => $detail['keterangan'],
                            "cek" => $cek
                        );
                        $data_row_detail = $this->dgeneral->basic_column("update", $data_row_detail);
                        $this->dgeneral->update("tbl_inv_main_detail", $data_row_detail, array(
                            array(
                                'kolom' => 'id_main_detail',
                                'value' => $this->generate->kirana_decrypt($detail['id_main_detail'])
                            )
                        ));
                    }
                } else {
                    $items = $params['items'];

                    foreach ($items as $item) {
                        $keterangan = $item['keterangan'];
                        if ($item['upgrade']) {
                            if(is_array($item['upgrade_after']))
                            {
                                $this->dgeneral->update("tbl_inv_aset", array(
                                    $item['upgrade_kolom'] => $item['upgrade_after']['nilai_pilihan']
                                ), array(
                                    array(
                                        'kolom' => 'id_aset',
                                        'value' => $main->id_aset
                                    )
                                ));

                                $keterangan = "Upgrade/Replace " . $item['upgrade_kolom'] . " dari " . $item['upgrade_before']
                                    . " " . $item['upgrade_satuan']
                                    . " menjadi " . $item['upgrade_after']['nilai_pilihan']
                                    . " " . $item['upgrade_satuan']
                                    . ".<br/>" . $keterangan;
                            }else{
                                $this->dgeneral->update("tbl_inv_aset", array(
                                    $item['upgrade_kolom'] => $item['upgrade_after']
                                ), array(
                                    array(
                                        'kolom' => 'id_aset',
                                        'value' => $main->id_aset
                                    )
                                ));

                                $keterangan = "Upgrade/Replace " . $item['upgrade_kolom'] . " dari " . $item['upgrade_before']
                                    . " " . $item['upgrade_satuan']
                                    . " menjadi " . $item['upgrade_after']
                                    . " " . $item['upgrade_satuan']
                                    . ".<br/>" . $keterangan;
                            }
                        }

                        $data = array(
                            'id_main' => $id_main,
                            'id_jenis' => $item['id_jenis'],
                            'id_jenis_detail' => $item['id_jenis_detail'],
                            'nama' => $item['nama'],
                            'keterangan' => $keterangan,
                        );
                        $data = $this->dgeneral->basic_column("insert", $data);

                        $this->dgeneral->insert("tbl_inv_main_items", $data);
                    }
                }

                // update kondisi aset
                $id_kondisi = $this->generate->kirana_decrypt($params['id_kondisi']);
                $this->dgeneral->update("tbl_inv_aset", array('id_kondisi' => $id_kondisi), array(
                    array(
                        'kolom' => 'id_aset',
                        'value' => $main->id_aset
                    )
                ));
                // save status confirmation
                $save_status = $this->lmaintenance->save_status_maintenance($id_main, $params['jenis_tindakan'], $params['nik']);

                $generate_jadwal = true;
                if ($params['jenis_tindakan'] == 'perawatan')
                    $generate_jadwal = $this->lmaintenance->generate_jadwal($main->id_periode, $main);

                if (
                    $this->dgeneral->status_transaction() === false
                    or $save_status === false
                    or $generate_jadwal === false
                ) {
                    $this->dgeneral->rollback_transaction();
                    $msg = "Periksa kembali data yang dimasukkan";
                    $sts = false;
                } else {
                    $this->dgeneral->commit_transaction();
                    $this->lmaintenance->send_konfirmasi_email($main);
                    $msg = "Maintenance berhasil ditambahkan";
                    $sts = true;
                }
            } else {
                $msg = "PM tidak ditemukan / Sudah dikerjakan";
                $sts = false;
            }

        } else {

            $msg = "PM tidak ditemukan";
            $sts = false;
        }
        $this->general->closeDb();

        $this->response(array(
            'status' => $sts,
            'message' => $msg
        ));
    }

    public function update_get()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $this->general->connectDbPortal();
        $app = $this->dmaintenance->get_mobile_app(array('package' => 'com.kiranamegatara.ict.pm', 'single_row' => true));
        //create the xml document
        $xmlDoc = new DOMDocument();

        $root = $xmlDoc->appendChild($xmlDoc->createElement('update'));
        $root->appendChild($xmlDoc->createElement("version", $app->version));
        $root->appendChild($xmlDoc->createElement("name", $app->name));
        $root->appendChild($xmlDoc->createElement("url", $app->url));

        //make the output pretty
        $xmlDoc->formatOutput = true;

        //return xml file name
        echo $xmlDoc->saveHTML();
    }
}
