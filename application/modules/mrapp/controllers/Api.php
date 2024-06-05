<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Management Reporting App API
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
        $this->load->library('mrapp_functions_purchasing');
        $this->load->library('mrapp_master');
        $this->load->library('general', 'dgeneral');
        $this->load->model('dapi');
        $this->load->model('dmrreports');
        $this->load->helper('download');
        ini_set('memory_limit', '800M');
        ini_set('max_execution_time', 14000);
    }

    public function index_get()
    {

        $json = array(
            "message" => "Mobile Reporting Internal API"
        );

        return $this->response($json, 200);
    }

    public function index_post()
    {

        $json = array(
            "message" => "Mobile Reporting Internal API"
        );

        return $this->response($json, 200);
    }

    public function action_post()
    {
        $params = $this->post();

        $action = $params['action'];

        $result = null;

        switch ($action) {
            case "report" :
                return $this->report_post();
                break;
            case "alert" :
                return $this->alert_post();
                break;
            case "app_download" :
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
        $nik = isset($params['nik']) ? $params['nik'] : "";
        $pass = isset($params['password']) ? $params['password'] : "";
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
            );

            if (isset($params['firebase_token'])) {
                $tokens = $this->dapi->get_fcm(array(
                    'nik' => $data->nik,
                    'token' => $params['firebase_token'],
                    'single_row' => true
                ));

                if (!isset($tokens)) {
                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();
                    $data = $this->dgeneral->basic_column('insert', array(
                        'id_user' => $data->id_user,
                        'nik' => $data->nik,
                        'token' => $params['firebase_token']
                    ));
                    $this->dgeneral->insert('tbl_mrapp_fcm', $data);

                    if ($this->dgeneral->status_transaction() === FALSE) {
                        $this->dgeneral->rollback_transaction();
                    } else {
                        $this->dgeneral->commit_transaction();
                    }
                }
            }

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
    }

    public function report_post()
    {
        $_params = $this->post();

        $id_report = $this->post('id_report');
        $kode_report = $this->post('kode_report');

        $json = array(
            "status" => false,
            "message" => "Error. Forbidden access"
        );


        $report = $this->dapi->get_reports(array(
            'id_report' => $id_report,
            'kode_report' => $kode_report,
            'single_row' => true
        ));

        $json['report'] = $report;

        if (isset($report)) {
            $reportParameters = $this->dapi->get_report_parameters($id_report);
            $functionController = "mrapp_functions_" . $report->module;
            $result = $this->$functionController->runFunction($report->report_function, $report, $_params, $reportParameters);
//            $json = array_merge($json, $result);
//            return $this->response($result);
        } else {
            $result = array(
                'status' => false,
                'message' => 'Report tidak tersedia.'
            );
        }

        if ($result !== false) {
            $json = array_merge($json, $result);
        } else {
            $json['message'] = "Method not available";
        }

        return $this->response($json);
    }

    public function alert_post()
    {
        $this->general->connectDbPortal();

        $params = $this->post();

        $json = array(
            'status' => true,
            'data' => array()
        );

        $auth_plants = $this->mrapp_master->auth_plant($params['id_user']);

        $alerts = $this->dapi->get_alerts(array(
            'id_user' => $params['id_user'],
            'sent' => true
        ));

        $list_alert = array();

        foreach ($alerts as $ialert => $alert) {
            $data = json_decode($alert->data);
            $alert->alert_sent_at = date('Y-m-d H:i:s',strtotime($alert->alert_sent_at));

            if (isset($data->plants)) {
                $alerted_plants = array();
                foreach ($auth_plants as $plant)
                    if (in_array($plant, $data->plants))
                        $alerted_plants[] = $plant;
                if (count($alerted_plants) > 0) {
                    $alert->alerted_plants = $alerted_plants;
                    $list_alert[$ialert] = $alert;
                    unset($alert->data);
                }
            }
        }

        $json['data'] = $list_alert;

        return $this->response(
            $json
        );

    }

    public function schedule_send_alert_get()
    {
        $this->general->connectDbPortal();

        $alerts = $this->dapi->get_alerts();

//        return $this->response($alerts);

        $result = array();

        foreach ($alerts as $alert) {
            $report = $this->dapi->get_reports(
                array(
                    'id' => $alert->id_report,
                    'single_row' => true
                )
            );
            $alert->report = $report;

            $resultFCM = $this->mrapp_master->sendFCM(array(
                'topic' => 'alert-' . $alert->id_report,
                'title' => 'Mobile Reporting Alert',
                'body' => $alert->title,
                'code' => FCM_CODE_ALERT,
                'message' => $alert->title,
                'data' => $alert,
            ));

            $fcmReturn = json_decode($resultFCM);

            if (isset($fcmReturn->message_id)) {
                $this->general->connectDbPortal();
                $this->dgeneral->begin_transaction();

                $data_update_report = array(
                    'alert_sent_at' => date('Y-m-d H:i:s'),
                    'fcm_message_id' => number_format($fcmReturn->message_id, 0, '', '')
                );

                $this->dgeneral->update('tbl_mrapp_alerts', $data_update_report, array(
                    array(
                        'kolom' => 'id_alert',
                        'value' => $alert->id_alert
                    )
                ));

                if ($this->dgeneral->status_transaction() === FALSE) {
                    $this->dgeneral->rollback_transaction();
                    $result[] = array(
                        'id_alert' => $alert->id_alert,
                        'status' => false
                    );
                } else {
                    $this->dgeneral->commit_transaction();


                    $result[] = array(
                        'id_alert' => $alert->id_alert,
                        'status' => true,
                        'message_id' => number_format($fcmReturn->message_id, 0, '', '')
                    );
                }
            }
        }

        return $this->response($result);
    }

    public function schedule_generate_alert_get()
    {
        $_params = $this->post();

        $this->general->connectDbPortal();

        $reports = $this->dapi->get_reports();

        $json = array(
            'status' => true,
            'data' => array()
        );

        foreach ($reports as $report) {
            $currentDate = date('Y-m-d');
            $lastsentDate = isset($report->schedule_last_sent) ?
                date('Y-m-d', strtotime($report->schedule_last_sent)) :
                null;
            if ($report->scheduled) {
//                return $this->response(array($report,$lastsentDate,$lastsentDate < $currentDate));
                if (!isset($lastsentDate) || $lastsentDate < $currentDate) {

                    $thresholds = $this->dapi->get_threshold($report->id_report);

                    $method = $report->report_function;
                    $functionController = "mrapp_functions_" . $report->module;// . $this->modules[$report->report_function];

                    $result = $this->$functionController->runAlert(
                        array(
                            'report' => $report,
                            'method' => $method,
                            'thresholds' => $thresholds,
                            'post_data' => $_params
                        )
                    );

                    if (isset($result) && is_array($result)) {
                        $this->general->connectDbPortal();
                        $this->dgeneral->begin_transaction();

                        foreach ($result as $alert) {

                            $data = array(
                                'id_report' => $alert['id_report'],
                                'id_report_threshold' => $alert['id_report_threshold'],
                                'title' => $alert['title'],
                                'data' => json_encode($alert['data'])
                            );

                            $data = $this->dgeneral->basic_column(
                                'insert', $data

                            );

                            $this->dgeneral->insert('tbl_mrapp_alerts', $data);

                        }

                        $data_update_report = array(
                            'schedule_last_sent' => date('Y-m-d H:i:s')
                        );

                        $this->dgeneral->update('tbl_mrapp_reports', $data_update_report, array(
                            array(
                                'kolom' => 'id_report',
                                'value' => $report->id_report
                            )
                        ));

                        if ($this->dgeneral->status_transaction() === FALSE) {
                            $this->dgeneral->rollback_transaction();
                            $json['message'] = "Gagal simpan alert ke database";
                        } else {
                            $this->dgeneral->commit_transaction();
                        }
                    }

                    $json['data'] = $result;
                }
            }
        }

        return $this->response($json);
    }

    public function masters_post()
    {
        $json = array(
            "status" => false,
            "message" => "Error. Forbidden access"
        );

        $params = $this->post();

        $plants = $this->mrapp_master->master_plant();
        $auth_plants = $this->mrapp_master->auth_plant($params['id_user']);

        if (isset($plants)) {
            $json['data']['plants']['list'] = $plants;
            $json['data']['plants']['authorized'] = $auth_plants;
        }

        $json['status'] = true;
        $json['message'] = "Data found";

        $this->response($json);
    }

    public function categories_get()
    {
        $json = array(
            "status" => false,
            "message" => "Error. Forbidden access"
        );

        $categories = $this->dapi->get_categories();

        foreach ($categories as $icategory => $category) {
            $reports = $this->dapi->get_category_reports(array(
                'id_category' => $category->id_category
            ));

            $category->reports = $reports;

            $categories[$icategory] = $category;
        }

        if (isset($categories)) {
            $json['data'] = $categories;
            $json['status'] = true;
            $json['message'] = "Data found";
        }

        $this->response($json);
    }

    public function reports_post()
    {
        $json = array(
            "status" => false,
            "message" => "Error. Forbidden access"
        );

        $id_category = $this->post('id_category');

        if (!isset($id_category)) {
            $json['message'] = 'Category Id not found';
            return $this->set_response($json);
        }

        $reports = $this->dapi->get_category_reports(array(
            'id_category' => $id_category
        ));

        if (isset($reports)) {
            $json['data'] = $reports;
            $json['status'] = true;
            $json['message'] = "Data found";
        }

        return $this->response($json);
    }

    public function app_download_get()
    {
//        echo realpath('assets/file/apks/') . '/mobile-reporting.apk';die();
        force_download(realpath('assets/file/apks') . '/mobile-reporting.apk', NULL);
    }
}