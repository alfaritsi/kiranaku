<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Ranger API - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Api extends REST_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Reservasi Ruangan meeting";
        $this->load->library('general', 'dgeneral');
        $this->load->library('lmobileranger');
        $this->load->model('dapi');
        $this->load->helper('download');
        $this->load->helper('date');
        ini_set('memory_limit', '800M');
        ini_set('max_execution_time', 14000);
        date_default_timezone_set("Asia/Jakarta");
    }

    public function index_get()
    {
        $this->lmobileranger->connectDbTimbangan();
        $json = array(
            "message" => "Mobile Ranger Internal API"
        );

        return $this->response($json, 200);
    }

    public function index_post()
    {

        $json = array(
            "message" => "Mobile Ranger Internal API"
        );

        return $this->response($json, 200);
    }

    public function action_post()
    {
        $params = $this->post();

        $action = $params['action'];

        $result = null;

        switch ($action) {
            case "app_download":
                return $this->app_download_get();
                break;
            default:
                return $this->response($params);
                break;
        }
    }

    public function login_post()
    {
        $params = $this->post();
        $username = isset($params['username']) ? $params['username'] : null;
        $password = isset($params['password']) ? $params['password'] : null;
        $pabrik = isset($params['id_pabrik']) ? $params['id_pabrik'] : null;
        $depo = isset($params['lokasi']['nama']) ? $params['lokasi']['nama'] : null;

        $this->lmobileranger->connectDbRanger();

        $userAvailable = $this->dapi->get_user_login(array(
            'username' => $username,
            'password' => $password,
            'single_row' => true
        ));

        $data = $this->dapi->get_user_login(array(
            'username' => $username,
            'password' => $password,
            'id_pabrik' => $pabrik,
            'single_row' => true
        ));

        if (isset($data)) {
            $profile = array(
                'id_pabrik' => $data->factory_cd,
                'depo' => $params['depo'],
                'jenis' => $data->jenis,
                'kode_pabrik' => $data->kode_pabrik,
                'nama' => $data->user_nm,
                'ppnob' => $data->ppnob,
                'client' => $params['client'],
            );

            $token_data = array(
                'id_pabrik' => $data->factory_cd,
                'id_user' => $data->uid,
                'jenis' => $data->jenis,
                'kode_pabrik' => $data->kode_pabrik,
                'depo' => $params['lokasi'],
                'client' => $params['client'],
            );

            $this->response(array(
                'status' => true,
                'message' => 'User ditemukan',
                'data' => $profile,
                'token_data' => $token_data
            ));
        } else {
            if (isset($userAvailable)) {
                $this->response(array(
                    'status' => false,
                    'message' => 'User tidak terdaftar pada lokasi yang dipilih'
                ));
            } else {
                $this->response(array(
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ));
            }
        }
    }

    public function users_post()
    {
        $params = $this->post();
        $username = isset($params['username']) ? $params['username'] : "";

        $this->lmobileranger->connectDbRanger();

        $data = $this->dapi->get_user_ranger(array(
            'username' => $username
        ));

        if (isset($data) && count($data) > 0) {
            $listPabrik = array_reduce($data, function ($pabrik = array(), $cData) {
                $pabrik[] = array(
                    'id' => $cData->id_pabrik,
                    'nama' => $cData->id_pabrik
                );
                return $pabrik;
            }, array());

            //            $listDepo = $this->dapi->get_depo(array(
            //                'id_pabrik' => $listPabrik
            //            ));

            $this->response(array(
                'status' => true,
                'message' => 'User ditemukan',
                'data' => array(
                    'user_found' => true,
                    'pabrik' => $listPabrik
                ),
            ));
        } else {
            $this->response(array(
                'status' => false,
                'message' => 'User tidak ditemukan'
            ));
        }
    }

    public function masters_post()
    {
        $json = array(
            "status" => false,
            "message" => "Error. Forbidden access"
        );

        $params = $this->post();

        if (!isset($params['id_pabrik'])) {
            $json['status'] = false;
            $json['message'] = "Harap pilih pabrik terlebih dahulu";
            return $this->response($json);
        }

        /**=======================================
         * ADDED BY ASY
         * - add function write data into log file
         *=======================================*/
        $logdir = KIRANA_PATH_LOGS . 'mobile_ranger/';
        if (!file_exists($logdir)) {
            @mkdir($logdir, 0777, true);
        }
        @chmod($logdir, 0775);
        $logfile = $logdir . $params['id_pabrik'] . '-master-' . date('Ymd') . ".txt";
        $this->lmobileranger->prepend_log(JSON_encode($params), $logfile);
        /**=======================================*/

        $this->lmobileranger->connectDbTimbangan($params['id_pabrik']);

        if (is_array($params['parts'])) {
            foreach ($params['parts'] as $part) {
                $partEx = explode('.', $part);
                if ($partEx[0] == 'master') {
                    if (isset($params['updates'])) {
                        $json['sync'][$partEx[0]][$partEx[1]] = $this->save_master(array(
                            'part' => $partEx[1],
                            'client' => $params['client'],
                            'id_pabrik' => $params['id_pabrik'],
                            'id_user' => $params['id_user'],
                            'depo' => $params['depo'],
                            'updates' => $params['updates'][$part]
                        ));
                    }
                    $json['data'][$partEx[0]][$partEx[1]] = $this->get_master(
                        array(
                            'part' => $partEx[1],
                            'client' => $params['client'],
                            'id_pabrik' => $params['id_pabrik'],
                            'depo' => @$params['depo'],
                            'lastSync' => @$params['lastSync'],
                        )
                    );
                    $json['total'][$partEx[0]][$partEx[1]] = count(
                        $this->get_master(
                            array(
                                'part' => $partEx[1],
                                'client' => $params['client'],
                                'id_pabrik' => $params['id_pabrik'],
                                'depo' => @$params['depo'],
                            )
                        )
                    );
                }
            }
        } else {
            $partEx = explode('.', $params['parts']);
            if ($partEx[0] == 'master') {
                if (isset($params['updates'])) {
                    $json['sync'][$partEx[0]][$partEx[1]] = $this->save_master(array(
                        'part' => $partEx[1],
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'id_user' => $params['id_user'],
                        'depo' => $params['depo'],
                        'updates' => $params['updates']
                    ));
                }
                $json['data'][$partEx[0]][$partEx[1]] = $this->get_master(
                    array(
                        'part' => $partEx[1],
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'depo' => @$params['depo'],
                        'lastSync' => @$params['lastSync'],
                    )
                );

                $json['total'] = count(
                    $this->get_master(
                        array(
                            'part' => $partEx[1],
                            'client' => $params['client'],
                            'id_pabrik' => $params['id_pabrik'],
                            'depo' => @$params['depo'],
                            'lastSync' => null,
                        )
                    )
                );
            }
        }
        $json['lq'] = $this->db->last_query();

        $json['clear'] = !isset($params['lastSync']);
        $json['status'] = true;
        $json['message'] = "Data found 105";
        $json['t'] = time();

        $this->response($json);
    }

    public function transactions_post()
    {
        $json = array(
            "status" => false,
            "message" => "Error. Forbidden access"
        );

        $params = $this->post();

        /**=======================================
         * ADDED BY ASY
         * - add function write data into log file
         *=======================================*/
        $logdir = KIRANA_PATH_LOGS . 'mobile_ranger/';
        if (!file_exists($logdir)) {
            @mkdir($logdir, 0777, true);
        }
        @chmod($logdir, 0775);
        $logfile = $logdir . $params['id_pabrik'] . '-transaksi-' . date('Ymd') . ".txt";
        $this->lmobileranger->prepend_log(JSON_encode($params), $logfile);
        /**=======================================*/

        $this->lmobileranger->connectDbTimbangan($params['id_pabrik']);

        $partEx = explode('.', $params['parts']);
        if ($partEx[0] == 'transaction') {
            if (isset($params['transactions'])) {
                $json['data'][$partEx[0]][$partEx[1]]['sync'] = $this->save_transaction(array(
                    'part' => $partEx[1],
                    'client' => $params['client'],
                    'id_pabrik' => $params['id_pabrik'],
                    'id_user' => $params['id_user'],
                    'depo' => $params['depo'],
                    'transactions' => $params['transactions']
                ));
            }

            $checkUpdatedTransaksi = $this->check_transaction(
                array(
                    'part' => $partEx[1],
                    'client' => $params['client'],
                    'id_pabrik' => $params['id_pabrik'],
                    'id_user' => $params['id_user'],
                    'depo' => $params['depo'],
                    'nextSeq' => &$params['nextSeq'],
                    'nextSeqMonthly' => &$params['nextSeqMonthly'],
                )
            );

            $transaksi = $this->get_transaction(
                array(
                    'part' => $partEx[1],
                    'client' => $params['client'],
                    'id_pabrik' => $params['id_pabrik'],
                    'id_user' => $params['id_user'],
                    'depo' => $params['depo'],
                )
            );

            $this->get_transaction_sequence(
                array(
                    'part' => $partEx[1],
                    'client' => $params['client'],
                    'id_pabrik' => $params['id_pabrik'],
                    'id_user' => $params['id_user'],
                    'depo' => $params['depo'],
                    'nextSeq' => &$params['nextSeq'],
                    'nextSeqMonthly' => &$params['nextSeqMonthly'],
                )
            );

            $json['data'][$partEx[0]][$partEx[1]]['updated'] = $checkUpdatedTransaksi;
            $json['data'][$partEx[0]][$partEx[1]]['list'] = $transaksi;
            $json['data'][$partEx[0]][$partEx[1]]['nextSeq'] = $params['nextSeq'];
            $json['data'][$partEx[0]][$partEx[1]]['nextSeqMonthly'] = $params['nextSeqMonthly'];
        }

        $json['lq'] = $this->db->last_query();

        $json['status'] = true;
        $json['message'] = "Data found 105";
        $json['t'] = time();

        $this->response($json);
    }

    private function get_transaction($params = array())
    {
        $result = null;
        switch ($params['part']) {
            case 'pembelian':
                $result = $this->dapi->get_pembelian(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                    )
                );
                $result = array_map(function ($res) {
                    $res->tanggal_transaksi = date_create($res->tanggal_transaksi)->format('Y-m-d');
                    return $res;
                }, $result);
                break;
            case 'faktur':
                $result = $this->dapi->get_faktur_compact(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                    )
                );
                $result = array_map(function ($res) {
                    $res->tanggal_transaksi = date_create($res->tanggal_transaksi)->format('Y-m-d');
                    return $res;
                }, $result);

                foreach ($result as $f) {
                    $getPembelians = $this->dapi->get_faktur_pembelian(array(
                        'nomor_faktur' => $f->nomor_faktur,
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                    ));
                    $f->pembelians = array_reduce($getPembelians, function ($pembelians = array(), $cPembelian = null) {
                        if (isset($cPembelian)) {
                            $pembelians[] = array(
                                'id_faktur' => $cPembelian->id_faktur,
                                'id_transaksi' => $cPembelian->id_transaksi,
                                'nota_timbang' => $cPembelian->nota_timbang,
                                'biaya_bongkar' => $cPembelian->biaya_bongkar,
                            );
                        }
                        return $pembelians;
                    }, array());
                }
                break;
            case 'fakturSql':
                $result = $this->dapi->get_faktur(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                    )
                );
                $result = array_map(function ($res) {
                    $res->tanggal_transaksi = date_create($res->tanggal_transaksi)->format('Y-m-d');
                    return $res;
                }, $result);

                //                foreach ($result as $f) {
                //                    $getPembelians = $this->dapi->get_faktur_pembelian(array(
                //                        'nomor_faktur' => $f->nomor_faktur,
                //                        'client' => $params['client'],
                //                        'id_pabrik' => $params['id_pabrik'],
                //                        'depo' => $params['depo']['nama'],
                //                    ));
                //                    $f->pembelians = array_reduce($getPembelians, function ($pembelians = array(), $cPembelian = null) {
                //                        if (isset($cPembelian)) {
                //                            $pembelians[] = array(
                //                                'id_faktur' => $cPembelian->id_faktur,
                //                                'id_transaksi' => $cPembelian->id_transaksi,
                //                                'nota_timbang' => $cPembelian->nota_timbang,
                //                                'biaya_bongkar' => $cPembelian->biaya_bongkar,
                //                            );
                //                        }
                //                        return $pembelians;
                //                    }, array());
                //                }
                break;
            case 'pengiriman':
                //                return $params;
                $result = $this->dapi->get_pengiriman(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                    )
                );
                $dapi = $this->dapi;
                $result = array_map(function ($res) use ($params, $dapi) {
                    //                    $res->tanggal_transaksi = date_create($res->tanggal_transaksi)->format('Y-m-d');
                    $getPembelians = $dapi->get_pengiriman_pembelian(array(
                        'id_pengiriman' => $res->id_pengiriman,
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                    ));
                    $res->pembelians = array_reduce($getPembelians, function ($pembelians = array(), $cPembelian = null) {
                        if (isset($cPembelian)) {
                            $pembelians[] = array(
                                'id_transaksi' => $cPembelian->id_transaksi,
                                'nota_timbang' => $cPembelian->nota_timbang,
                                'berat_sample' => $cPembelian->berat_sample,
                            );
                        }
                        return $pembelians;
                    }, array());
                    $res->tanggal_transaksi = date_create($res->tanggal_transaksi)->format('Y-m-d');
                    return $res;
                }, $result);
                //                foreach ($result as $f) {
                //                    $getPembelians = $this->dapi->get_faktur_pembelian(array(
                //                        'nomor_faktur' => $f->nomor_faktur
                //                    ));
                //                    $f->pembelians = array_reduce($getPembelians, function ($pembelians = array(), $cPembelian = null) {
                //                        if (isset($cPembelian)) {
                //                            $pembelians[] = array(
                //                                'nota_timbang' => $cPembelian->nota_timbang,
                //                                'biaya_bongkar' => $cPembelian->biaya_bongkar,
                //                            );
                //                        }
                //                        return $pembelians;
                //                    }, array());
                //                }
                break;
        }
        return $result;
    }

    private function get_transaction_sequence($params = array())
    {
        switch ($params['part']) {
            case 'pembelian':
                $lastTransaction = $this->dapi->get_pembelian(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                        'order_by' => 'YEAR(TRekap_Timbang_Depo.trans_date) DESC, RIGHT(TRekap_Timbang_Depo.no_kpb, 5) desc',
                        'single_row' => true
                    )
                );

                if (isset($lastTransaction)) {
                    $params['nextSeq'] = intval(substr($lastTransaction->nota_timbang, -5)) + 1;
                    if (date_format(date_create($lastTransaction->tanggal_transaksi), "Y") !== date("Y"))
                        $params['nextSeq'] = 1;
                } else {
                    $params['nextSeq'] = 1;
                }
                break;
            case 'faktur':
                $lastTransaction = $this->dapi->get_faktur(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                        // 'order_by' => 'RIGHT(TFaktur.Trans_No, 4) desc',
                        // 'order_by' => 'id_faktur DESC, Tgl_Bukti DESC',
                        'order_by' => 'YEAR(Tgl_Bukti) DESC,MONTH(Tgl_Bukti) DESC,No_Fak DESC,id_faktur DESC',
                        'single_row' => true
                    )
                );

                if (isset($lastTransaction)) {
                    $params['nextSeq'] = intval(substr($lastTransaction->id_faktur, -4)) + 1;
                    if (date_format(date_create($lastTransaction->tanggal_transaksi), "Y-m") !== date("Y-m"))
                        $params['nextSeq'] = 1;
                } else {
                    $params['nextSeq'] = 1;
                }

                $lastTransaction = $this->dapi->get_faktur(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                        // 'order_by' => 'SUBSTRING(trans_no, 7, 12) desc',
                        // 'order_by' => 'Tgl_Bukti DESC, id_faktur DESC',
                        'order_by' => 'YEAR(Tgl_Bukti) DESC,MONTH(Tgl_Bukti) DESC,No_Fak DESC,id_faktur DESC',
                        'single_row' => true
                    )
                );

                if (isset($lastTransaction)) {
                    $params['nextSeqMonthly'] = intval(substr($lastTransaction->nomor_faktur, 0, 5)) + 1;
                    if (date_format(date_create($lastTransaction->tanggal_transaksi), "Y-m") !== date("Y-m"))
                        $params['nextSeqMonthly'] = 1;
                } else {
                    $params['nextSeqMonthly'] = 1;
                }
                break;
            case 'pengiriman':
                $lastTransaction = $this->dapi->get_pengiriman(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                        'order_by' => 'YEAR(trans_date) DESC, RIGHT(no_srt, 5) desc',
                        'single_row' => true
                    )
                );

                if (isset($lastTransaction)) {
                    $params['nextSeq'] = abs(intval(substr($lastTransaction->id_pengiriman, -5))) + 1;
                    if (date_format(date_create($lastTransaction->tanggal_transaksi), "Y") !== date("Y"))
                        $params['nextSeq'] = 1;
                } else {
                    $params['nextSeq'] = 1;
                }
                break;
        }
    }

    private function get_master($params = array())
    {
        $result = null;
        switch ($params['part']) {
            case 'pabrik':
                $result = $this->dapi->get_plant();
                break;
            case 'depo':
                $result = $this->dapi->get_depo(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'lastSync' => $params['lastSync'],
                    )
                );
                break;
            case 'vendor':
                $result = $this->dapi->get_vendor(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'lastSync' => $params['lastSync']
                    )
                );
                break;
            case 'vendor_cat':
                $result = $this->dapi->get_vendor_cat(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                    )
                );
                break;
            case 'vendor_eks':
                $result = $this->dapi->get_vendor_eks(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'lastSync' => $params['lastSync'],
                    )
                );
                break;
            case 'vendor_rel':
                $result = $this->dapi->get_vendor_rel(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'lastSync' => $params['lastSync'],
                    )
                );
                break;
            case 'angkutan':
                $result = $this->dapi->get_angkutan(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'lastSync' => $params['lastSync'],
                    )
                );
                break;
            case 'provinsi':
                $result = $this->dapi->get_provinsi(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                    )
                );
                break;
            case 'kabupaten':
                $result = $this->dapi->get_kabupaten(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                    )
                );
                break;
            case 'subarea':
                $result = $this->dapi->get_subarea(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'depo' => $params['depo']['nama'],
                        'lastSync' => $params['lastSync'],
                    )
                );
                break;
            case 'vendor_contract':
                $result = $this->dapi->get_vendor_contract(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'depo' => $params['depo']['nama']
                    )
                );
                break;
        }
        return $result;
    }

    private function save_master($params = array())
    {
        $result = true;
        switch ($params['part']) {
            case 'vendor':
                $this->dgeneral->begin_transaction();
                $result = array();
                try {
                    $transactions = $params['updates'];
                    foreach ($transactions as $index => $transaction) {

                        $checkExist = $this->dapi->get_vendor(array(
                            'single_row' => true,
                            'kode_vendor' => $transaction['kode'],
                            'client' => $params['client'],
                            'id_pabrik' => $params['id_pabrik'],
                        ));

                        $result[$index]['kode_vendor'] = $transaction['kode'];
                        if (isset($checkExist)) {
                            $linkKtp = null;
                            if (isset($transaction['foto'])) {
                                try {
                                    $uploaddir = KIRANA_PATH_FILE . 'mobileranger/ktp/';
                                    if (!file_exists($uploaddir)) {
                                        mkdir($uploaddir, 0777, true);
                                    }

                                    $filename = $transaction['kode'] . '.jpeg';

                                    $img = imagecreatefromstring(base64_decode($transaction['foto']));
                                    $uploadResult = false;
                                    if ($img != false) {
                                        $uploadResult = imagejpeg($img, $uploaddir . $filename);
                                    }

                                    //                                    $uploadResult = file_put_contents($uploaddir . $filename, base64_decode($transaction['foto']));

                                    if ($uploadResult) {
                                        $linkKtp = 'http://shipment.kiranamegatara.com/ranger_api/api/image/' .
                                            $filename;
                                    }
                                } catch (Error $e) {
                                }
                            }

                            $dataUpdate = array(
                                'vendor_nm' => $transaction['nama'],
                                'city' => @$transaction['city'],
                                'address1' => @$transaction['street'],
                                'house_no' => @$transaction['house_no'],
                                'postal_cd' => @$transaction['postal_code'],
                                'pph22' => @$transaction['pph22'],
                                'status_pkp' => @$transaction['status_pkp'],
                                'vendor_cat' => @$transaction['vendorCat']['kode'],
                                'title' => @$transaction['title'],
                                'npwp' => @$transaction['npwp'],
                                'link_poto' => $linkKtp,
                                'is_update' => 1,
                            );
                            $result[$index]['data'] = $dataUpdate;

                            $this->dgeneral->update('mvendor', $dataUpdate, array(
                                array(
                                    'kolom' => 'vendor_cd',
                                    'value' => $transaction['kode']
                                ),
                                array(
                                    'kolom' => 'factory_cd',
                                    'value' => $params['id_pabrik']
                                ),
                                array(
                                    'kolom' => 'client_sap',
                                    'value' => $params['client']
                                ),
                            ));

                            $result[$index]['sts'] = true;
                        } else {
                            $result[$index]['sts'] = false;
                            $result[$index]['msg'] = 'Vendor tidak ada di database';
                        }
                    }

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $result = array(
                            'sts' => false,
                            'msg' => 'Koneksi database sedang error'
                        );
                    } else {
                        $this->dgeneral->commit_transaction();
                    }
                } catch (Exception $e) {
                    $this->dgeneral->rollback_transaction();
                    $result = array(
                        'sts' => false,
                        'msg' => $e->getMessage()
                    );
                }
                break;
            case 'vendor_rel':
                $this->dgeneral->begin_transaction();
                $result = array();
                try {
                    $transactions = $params['updates'];
                    foreach ($transactions as $index => $transaction) {

                        $checkExist = $this->dapi->get_vendor_rel(array(
                            'single_row' => true,
                            'kode_vendor_a' => $transaction['kode_a'],
                            'kode_vendor_b' => $transaction['kode_b'],
                            'client' => $params['client'],
                            'id_pabrik' => $params['id_pabrik'],
                        ));

                        if (!isset($checkExist)) {
                            $dataUpdate = array(
                                'vendor_cd_a' => $transaction['kode_a'],
                                'vendor_cd_b' => $transaction['kode_b'],
                                'client_sap' => $params['client'],
                                'factory_cd' => $params['id_pabrik'],
                                'uid' => $params['id_user'],
                                'date_input' => date_create()->format('Y-m-d H:i:s'),
                            );
                            $result[$index]['data'] = $dataUpdate;

                            $this->dgeneral->insert('mvendor_rel', $dataUpdate);

                            $result[$index]['sts'] = true;
                        } else {
                            $result[$index]['sts'] = false;
                            $result[$index]['msg'] = 'Vendor rel sudah ada di database';
                        }
                    }

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $result = array(
                            'sts' => false,
                            'msg' => 'Koneksi database sedang error'
                        );
                    } else {
                        $this->dgeneral->commit_transaction();
                    }
                } catch (Exception $e) {
                    $this->dgeneral->rollback_transaction();
                    $result = array(
                        'sts' => false,
                        'msg' => $e->getMessage()
                    );
                }
                break;
        }
        return $result;
    }

    private function check_transaction($params = array())
    {
        $result = null;
        switch ($params['part']) {
            case 'faktur':
                // if (!isset($params['nextSeqMonthly'])) {
                $lastTrans = $this->dapi->get_faktur(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                        'single_row' => true,
                        'not_null_faktur' => true,
                        // 'order_by' => 'no_fak desc'
                        // 'order_by' => 'Tgl_Bukti DESC, id_faktur DESC'
                        'order_by' => 'YEAR(Tgl_Bukti) DESC,MONTH(Tgl_Bukti) DESC,No_Fak DESC,id_faktur DESC',
                    )
                );
                if ($lastTrans) {
                    $params['nextSeqMonthly'] = intval(substr($lastTrans->nomor_faktur, 0, 5)) + 1;
                } else {
                    $params['nextSeqMonthly'] = 1;
                }
                // }

                $resultCheck = $this->dapi->get_faktur(
                    array(
                        'client' => $params['client'],
                        'id_pabrik' => $params['id_pabrik'],
                        'uid' => $params['id_user'],
                        'depo' => $params['depo']['nama'],
                        'null_faktur' => true,
                        'onlyMonthly' => false,
                        // 'order_by' => 'Tgl_Bukti, id_faktur'
                        'order_by' => 'YEAR(Tgl_Bukti),MONTH(Tgl_Bukti),No_Fak',
                    )
                );

                $updateFaktur = array();

                foreach ($resultCheck as $f) {
                    if (!isset($f->nomor_faktur) || !strlen(ltrim($f->nomor_faktur))) {
                        // $nomorFaktur = str_pad($params['nextSeqMonthly'], 5, '0', STR_PAD_LEFT) . date('/m/Y');
                        $lastTransByTanggalTransaksi = $this->dapi->get_faktur(
                            array(
                                'client' => $params['client'],
                                'id_pabrik' => $params['id_pabrik'],
                                'uid' => $params['id_user'],
                                'depo' => $params['depo']['nama'],
                                'single_row' => true,
                                'not_null_faktur' => true,
                                'tanggal_transaksi' => $f->tanggal_transaksi,
                                // 'order_by' => 'Tgl_Bukti DESC, id_faktur DESC'
                                'order_by' => 'YEAR(Tgl_Bukti) DESC,MONTH(Tgl_Bukti) DESC,No_Fak DESC,id_faktur DESC',
                            )
                        );
                        if ($lastTransByTanggalTransaksi)
                            $sequence = intval(substr($lastTransByTanggalTransaksi->nomor_faktur, 0, 5)) + 1;
                        else
                            $sequence = $params['nextSeqMonthly'];

                        $month = date_create($f->tanggal_transaksi)->format('/m/Y');
                        $nomorFaktur = str_pad($sequence, 5, '0', STR_PAD_LEFT) . $month;
                        $f->nomor_faktur = $nomorFaktur;
                        if (!isset($updateFaktur[$f->nomor_faktur_tkr])) {
                            $updateFaktur[$f->nomor_faktur_tkr] = (array)$f;
                            $params['nextSeqMonthly']++;
                        }
                    }
                }

                if (count($updateFaktur) > 0) {
                    $params['transactions'] = $updateFaktur;
                    $result = $this->update_transaction($params);
                }
                break;
        }

        return $result;
    }

    private function save_transaction($params = array())
    {
        $result = true;
        switch ($params['part']) {
            case 'pembelian':
                $this->dgeneral->begin_transaction();
                try {
                    $transactions = $params['transactions'];
                    $result = array();
                    $insertTotal = 0;
                    foreach ($transactions as $index => $transaction) {

                        $checkExist = $this->dapi->get_pembelian(array(
                            'single_row' => true,
                            'id_pabrik' => $params['id_pabrik'],
                            'client' => $params['client'],
                            'uid' => $params['id_user'],
                            'depo' => $params['depo']['nama'],
                            'nota_timbang' => $transaction['nota_timbang'],
                            'id_transaksi' => $transaction['id_transaksi']
                        ));

                        $result[$index]['id_transaksi'] = $transaction['id_transaksi'];
                        if (isset($checkExist)) {
                            $result[$index]['sts'] = false;
                            $result[$index]['msg'] = 'Sudah ada di database';
                        } else {
                            $dataInsert = array(
                                'factory_cd' => $params['id_pabrik'],
                                'client_sap' => $params['client'],
                                'trans_no' => $transaction['id_transaksi'],
                                'no_kpb' => $transaction['nota_timbang'],
                                'trans_date' => $this->generate->regenerateDateFormat($transaction['tanggal']),
                                'vendor_hdr_cd' => $transaction['pic']['kode'],
                                'vendor_cld_cd' => $transaction['vendor']['kode'],
                                'plat_no' => @$transaction['nomor_mobil'],
                                'metode_pmbyr' => $transaction['metode_pembayaran'],
                                'type_trn' => $transaction['jenis_po'],
                                'Contract_No' => @$transaction['no_kontrak'],
                                'item_cd' => $transaction['kode_barang'],
                                'asal_getah' => $transaction['kabupaten']['nama'] . ', ' . $transaction['provinsi']['nama'],
                                'getah' => $transaction['jenis_bokar'],
                                'drc' => $transaction['drc'],
                                'qty_wet' => $transaction['berat_basah'],
                                'price_wet' => $transaction['harga_basah'],
                                'qty_dry' => $transaction['berat_kering'],
                                'price_dry' => $transaction['harga_kering'],
                                'nm_depo' => $transaction['depo']['nama'],
                                'nm_rngr' => $transaction['depo']['nama'],
                                'sub_area' => @$transaction['subarea']['nama'],
                                'plant_afl' => @$transaction['pabrik_afliasi'],
                                'uid' => $params['id_user'],
                                'batch' => $transaction['no_batch'],
                                'geo_tag' => @$transaction['koordinat'],
                                'date_input' => date_create($transaction['timestamp'])->format('Y-m-d H:i:s'),
                            );
                            $result[$index]['sts'] = true;
                            $result[$index]['data'] = $dataInsert;
                            $insertTotal++;
                            $this->dgeneral->insert('trekap_timbang_depo', $dataInsert);
                        }
                    }

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $result = array(
                            'sts' => false,
                            'msg' => 'Koneksi database sedang error'
                        );
                    } else {
                        /** Log transaksi ranger */
                        $params['total'] = $insertTotal;
                        $this->save_transaction_log($params);

                        $this->dgeneral->commit_transaction();
                    }
                } catch (Exception $e) {
                    $this->dgeneral->rollback_transaction();
                    $result = array(
                        'sts' => false,
                        'msg' => $e->getMessage()
                    );
                }
                break;
            case 'faktur':
                $this->dgeneral->begin_transaction();
                $result = array();
                try {
                    $insertTotal = 0;
                    $transactions = $params['transactions'];
                    foreach ($transactions as $index => $transaction) {

                        $checkExist = $this->dapi->get_faktur(array(
                            'single_row' => true,
                            'id_pabrik' => $params['id_pabrik'],
                            'client' => $params['client'],
                            'uid' => $params['id_user'],
                            'depo' => $params['depo']['nama'],
                            'nomor_faktur' => $transaction['nomor_faktur'],
                        ));

                        $result[$index]['nomor_faktur'] = $transaction['nomor_faktur'];
                        if (isset($checkExist)) {
                            $result[$index]['sts'] = false;
                            $result[$index]['msg'] = 'Sudah ada di database';
                        } else {
                            foreach ($transaction['pembelians'] as $indexP => $pembelianFaktur) {
                                $pembelian = $this->dapi->get_pembelian(array(
                                    'id_transaksi' => $pembelianFaktur['id_transaksi'],
                                    'nota_timbang' => $pembelianFaktur['nota_timbang'],
                                    'id_pabrik' => $params['id_pabrik'],
                                    'client' => $params['client'],
                                    'uid' => $params['id_user'],
                                    'depo' => $params['depo']['nama'],
                                    'single_row' => true
                                ));

                                $dataInsert = array(
                                    'factory_cd' => $params['id_pabrik'],
                                    'client_sap' => $params['client'],
                                    'trans_no' => $pembelianFaktur['id_faktur'],
                                    'nm_depo' => $params['depo']['nama'],
                                    'trans_no_kpb' => $pembelian->id_transaksi,
                                    'tgl_bukti' => $pembelian->tanggal_transaksi, //$this->generate->regenerateDateFormat($transaction['tanggal']),
                                    'trans_no_by' => $pembelian->id_transaksi,
                                    'no_fak' => $transaction['nomor_faktur'],
                                    'no_kpb' => $pembelianFaktur['nota_timbang'],
                                    'vendor_hdr_cd' => $pembelian->id_pic,
                                    'vendor_cld_cd' => $pembelian->id_vendor,
                                    'materai' => $transaction['biaya_materai'],
                                    'ongkos_bongkar' => $pembelianFaktur['biaya_bongkar'],
                                    'ppn_ongkos_bongkar' => $transaction['ppn_biaya_bongkar'],
                                    'uid' => $params['id_user'],
                                    'date_input' => date_create($transaction['timestamp'])->format('Y-m-d H:i:s'),
                                );
                                $result[$index]['data'][$indexP] = $dataInsert;
                                $insertTotal++;
                                $this->dgeneral->insert('tfaktur', $dataInsert);
                            }
                            $result[$index]['sts'] = true;
                        }
                    }

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $result = array(
                            'sts' => false,
                            'msg' => 'Koneksi database sedang error'
                        );
                    } else {
                        /** Log transaksi ranger */
                        $params['total'] = $insertTotal;
                        $this->save_transaction_log($params);
                        $this->dgeneral->commit_transaction();
                    }
                } catch (Exception $e) {
                    $this->dgeneral->rollback_transaction();
                    $result = array(
                        'sts' => false,
                        'msg' => $e->getMessage()
                    );
                }
                break;
            case 'pengiriman':
                $this->dgeneral->begin_transaction();
                $result = array();;
                try {
                    $insertTotal = 0;
                    $transactions = $params['transactions'];
                    $pabrik = $this->dapi->get_plant(array('kode_pabrik' => $params['id_pabrik'], 'single_row' => true));

                    foreach ($transactions as $index => $transaction) {

                        $checkExist = $this->dapi->get_pengiriman(array(
                            'single_row' => true,
                            'id_pabrik' => $params['id_pabrik'],
                            'client' => $params['client'],
                            'uid' => $params['id_user'],
                            'depo' => $params['depo']['nama'],
                            'id_pengiriman' => $transaction['id_pengiriman'],
                            'tahun' => date_create($transaction['tanggal_berangkat'])->format('Y')
                        ));

                        $result[$index]['id_pengiriman'] = $transaction['id_pengiriman'];
                        if (isset($checkExist)) {
                            $result[$index]['sts'] = false;
                            $result[$index]['msg'] = 'Sudah ada di database';
                        } else {
                            $pembelianBatch = @$transaction['no_batch'];
                            if (empty($pembelianBatch)) {
                                $checkPembelian = $this->dapi->get_pembelian(array(
                                    'nota_timbang' => $transaction['pembelians'][0]['nota_timbang'],
                                    'id_transaksi' => $transaction['pembelians'][0]['id_transaksi'],
                                    'id_pabrik' => $params['id_pabrik'],
                                    'client' => $params['client'],
                                    'uid' => $params['id_user'],
                                    'depo' => $params['depo']['nama'],
                                    'single_row' => true
                                ));
                                $pembelianBatch = $checkPembelian->no_batch;
                            }

                            $subarea = null;
                            if (isset($transaction['subarea']['nama'])) {
                                $subarea = $transaction['subarea']['nama'];
                            }
                            $dataInsert = array(
                                'factory_cd' => $params['id_pabrik'],
                                'client_sap' => $params['client'],
                                'nm_depo' => $params['depo']['nama'],
                                'no_srt' => $transaction['id_pengiriman'],
                                'factory_cd_dst' => $pabrik->id,
                                'factory_nm_dst' => $pabrik->name,
                                'batch' => $pembelianBatch,
                                'jenis_getah' => $transaction['jenis_getah'],
                                'jns_kend' => $transaction['jenis_kendaraan'],
                                'nm_sopir' => $transaction['nama_supir'],
                                'plat_no' => $transaction['nomor_polisi'],
                                'qty' => $transaction['tonase_kirim'],
                                'vendor_cd' => $transaction['vendor_ekspedisi']['kode'],
                                'status' => 1,
                                'uid' => $params['id_user'],
                                'date_input' => date_create($transaction['tanggal_berangkat'])->format('Y-m-d'),
                                'jam_krm' => date_create($transaction['jam_berangkat'])->format('H:i:s'),
                                'sub_area' => $subarea,
                            );
                            $result[$index]['main_data'] = $dataInsert;

                            $this->dgeneral->insert('tsrt_jln', $dataInsert);
                            $insertTotal++;
                            foreach ($transaction['pembelians'] as $indexP => $pembelianPengiriman) {
                                $pembelian = $this->dapi->get_pembelian(array(
                                    'id_transaksi' => $pembelianPengiriman['id_transaksi'],
                                    'nota_timbang' => $pembelianPengiriman['nota_timbang'],
                                    'id_pabrik' => $params['id_pabrik'],
                                    'client' => $params['client'],
                                    'uid' => $params['id_user'],
                                    'depo' => $params['depo']['nama'],
                                    'single_row' => true
                                ));
                                if (isset($pembelian)) {
                                    $dataInsert = array(
                                        'factory_cd' => $params['id_pabrik'],
                                        'client_sap' => $params['client'],
                                        'nm_depo' => $params['depo']['nama'],
                                        'trans_no_kpb' => $pembelianPengiriman['id_transaksi'],
                                        'no_kpb' => $pembelianPengiriman['nota_timbang'],
                                        'no_srt' => $transaction['id_pengiriman'],
                                        'qty' => $pembelianPengiriman['berat_sample'],
                                        'status' => 1,
                                        'uid' => $params['id_user'],
                                        'trans_date' => date_create()->format('Y-m-d H:i:s'),
                                        'date_input' => date_create($transaction['tanggal_berangkat'])->format('Y-m-d H:i:s'),
                                    );
                                    $result[$index]['data'][$indexP] = $dataInsert;

                                    $this->dgeneral->insert('tsrt_jln_dtl', $dataInsert);
                                }
                            }
                            $result[$index]['sts'] = true;
                        }
                    }

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $result = array(
                            'sts' => false,
                            'msg' => 'Koneksi database sedang error'
                        );
                    } else {
                        /** Log transaksi ranger */
                        $params['total'] = $insertTotal;
                        $this->save_transaction_log($params);
                        $this->dgeneral->commit_transaction();
                    }
                } catch (Exception $e) {
                    $this->dgeneral->rollback_transaction();
                    $result = array(
                        'sts' => false,
                        'msg' => $e->getMessage()
                    );
                }
                break;
        }
        return $result;
    }

    private function update_transaction($params = array())
    {
        $result = null;
        switch ($params['part']) {
            case 'faktur':
                $result = array();

                /** update data ke 28 */
                $this->lmobileranger->connectDbTimbangan($params['id_pabrik']);
                $this->dgeneral->begin_transaction();
                try {
                    $transactions = $params['transactions'];
                    foreach ($transactions as $index => $transaction) {
                        $checkExist = $this->dapi->get_faktur_pembelian(array(
                            'single_row' => true,
                            'id_pabrik' => $params['id_pabrik'],
                            'client' => $params['client'],
                            'depo' => $params['depo']['nama'],
                            'id_faktur' => $transaction['id_faktur'],
                        ));

                        if (!isset($checkExist)) {
                            $result['list'][$index]['sts'] = false;
                            $result['list'][$index]['msg'] = 'Update tidak ada di database';
                        } else {
                            $dataUpdate = array(
                                'no_fak' => $transaction['nomor_faktur'],
                            );
                            $result['list'][$index]['data'] = $dataUpdate;

                            $this->dgeneral->update('tfaktur', $dataUpdate, array(
                                array(
                                    'kolom' => 'no_fak_tkr',
                                    'value' => $index
                                )
                            ));

                            $result['list'][$index]['sts'] = true;
                        }
                    }

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                        $result = array(
                            'sts' => false,
                            'msg' => 'Koneksi database sedang error'
                        );
                    } else {
                        $this->dgeneral->commit_transaction();
                        $result = array('sts' => true);
                    }
                } catch (Exception $e) {
                    $this->dgeneral->rollback_transaction();
                    $result = array(
                        'sts' => false,
                        'msg' => $e->getMessage()
                    );
                }

                /** update data ke pabrik */
                if ($result['sts']) {
                    $dbPabrik = $this->lmobileranger->connectDbPabrik($params['id_pabrik']);
                    if (isset($dbPabrik)) {
                        $this->dgeneral->begin_transaction();
                        try {
                            $transactions = $params['transactions'];
                            foreach ($transactions as $index => $transaction) {
                                $checkExist = $this->dapi->get_faktur_pembelian(array(
                                    'single_row' => true,
                                    'id_pabrik' => $params['id_pabrik'],
                                    'client' => $params['client'],
                                    'depo' => $params['depo']['nama'],
                                    'id_faktur' => $transaction['id_faktur'],
                                ));

                                if (!isset($checkExist)) {
                                    $result['listPb'][$index]['sts'] = false;
                                    $result['listPb'][$index]['msg'] = 'Data faktur tidak ada di database';
                                } else {
                                    $dataUpdate = array(
                                        'no_fak' => $transaction['nomor_faktur'],
                                    );

                                    $this->dgeneral->update('tfaktur', $dataUpdate, array(
                                        array(
                                            'kolom' => 'no_fak_tkr',
                                            'value' => $index
                                        )
                                    ));

                                    $result['listPb'][$index]['sts'] = true;
                                }
                            }

                            if ($this->dgeneral->status_transaction() === FALSE) {
                                $this->dgeneral->rollback_transaction();
                                $result = array(
                                    'sts' => false,
                                    'msg' => 'Koneksi database sedang error'
                                );
                            } else {
                                $this->dgeneral->commit_transaction();
                                $result = array('sts' => true);
                            }
                        } catch (Exception $e) {
                            $this->dgeneral->rollback_transaction();
                            $result = array(
                                'sts' => false,
                                'msg' => $e->getMessage()
                            );
                        }
                    } else {
                        $result = array(
                            'sts' => false,
                            'msg' => 'Tidak ada koneksi database ke pabrik'
                        );
                    }
                }
                break;
        }
        $this->lmobileranger->connectDbTimbangan($params['id_pabrik']);
        return $result;
    }

    private function save_transaction_log($params = array())
    {
        if ($params['total'] > 0) {
            $table_name = null;
            switch ($params['part']) {
                case 'pembelian':
                    $table_name = "TRekap_Timbang_Depo";
                    break;
                case 'faktur':
                    $table_name = "TFaktur";
                    break;
                case 'pengiriman':
                    $table_name = "TSRT_JLN";
                    break;
            }

            $dataInsert = array(
                'factory_cd' => $params['id_pabrik'],
                'client_sap' => $params['client'],
                'nm_depo' => $params['depo']['nama'],
                'nm_tbl' => $table_name,
                'qty_rngr' => $params['total'],
                'date_input_rngr' => date('Y-m-d H:i:s'),
            );
            $this->dgeneral->insert('tlog_trfr_rngr', $dataInsert);
        }
    }

    public function app_update_get()
    {
        $this->general->connectDbPortal();

        $app = $this->dapi->get_app_version();

        $json['status'] = true;
        $json['data'] = $app;
        $json['message'] = "Data found";
        $json['t'] = time();

        $this->response($json);
    }

    public function app_download_get()
    {
        force_download(realpath('assets/file/apks') . '/mobile-ranger.apk', NULL);
    }

    public function app_upload_post()
    {
        $uploaddir = realpath('./') . '/assets/file/apks/';
        if (!file_exists($uploaddir)) {
            mkdir($uploaddir, 0777, true);
        }

        $uploaded_gambar = null;

        $params = $this->post();

        $config['file_name'] = 'mobile-ranger.apk';
        $config['upload_path'] = $uploaddir;
        $config['allowed_types'] = '*';
        $config['max_size'] = 20000;
        $config['mod_mime_fix'] = false;
        $config['overwrite'] = true;

        $this->load->library('upload', $config);

        $this->upload->initialize($config, true);

        try {
            if ($this->upload->do_upload('apk_update')) {
                $this->general->connectDbPortal();

                $checkApp = $this->dapi->get_app_version();

                if (isset($checkApp)) {
                    $this->dgeneral->begin_transaction();

                    $apkUpdate = array(
                        'version' => $params['version']
                    );

                    $this->dgeneral->update(
                        'tbl_mobile_apps',
                        $apkUpdate,
                        array(
                            array(
                                'kolom' => 'package',
                                'value' => 'com.kiranamegatara.ict.mobileRanger'
                            )
                        )
                    );

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();

                        $this->response(array(
                            'status' => false,
                            'message' => 'Gagal update app'
                        ));
                    } else {
                        $this->dgeneral->commit_transaction();

                        $this->response(array(
                            'status' => true
                        ));
                    }
                } else {
                    $this->response(array(
                        'status' => false
                    ));
                }
            } else {
                $this->response(array(
                    'message' => $this->upload->display_errors('', ''),
                    'status' => false
                ));
            }
        } catch (Exception $e) {
            $this->dgeneral->rollback_transaction();
            $this->response(array(
                'message' => 'Gagal Upload App',
                'status' => false
            ));
        }
    }

    public function image_get($image = null)
    {
        if ($image && file_exists(realpath('assets/file/mobileranger/ktp/') . '/' . $image)) {
            $filename = realpath('assets/file/mobileranger/ktp/') . '/' . $image;
            $mime = mime_content_type($filename); //<-- detect file type
            header('Content-Length: ' . filesize($filename)); //<-- sends filesize header
            header("Content-Type: $mime"); //<-- send mime-type header
            header('Content-Disposition: inline; filename="' . $filename . '";');
            readfile($filename);
        } else {
            return $this->response(null);
        }
    }
}
