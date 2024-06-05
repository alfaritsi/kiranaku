<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Mrapp_master
{

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('general');
        $this->CI->load->model('dgeneral');
        $this->CI->load->model('Dapi');
    }

    public function group_by_multiple($items, array $keySelectors, $valueSelector = null)
    {
        $resultSelector = null;
        foreach (array_reverse($keySelectors) as $keySelector) {
            $keySelector = '$v["' . $keySelector . '"]';
            $resultSelector = function ($subitems) use ($keySelector, $valueSelector, $resultSelector) {
                return from($subitems)->groupBy(
                    $keySelector,
                    $resultSelector == null ? $valueSelector : null,
                    $resultSelector);
            };
        }
        return $resultSelector($items)->toArrayDeep();
    }

    public function master_gudang()
    {
        return [
            [
                "id" => "HL01",
                "label" => "HL01",
            ], [
                "id" => "RJ01",
                "label" => "RJ01",
            ]
        ];
    }

    public function master_plant()
    {
        $plants = $this->CI->dgeneral->get_master_plant(null, true);

        $plantOptions = [];
        foreach ($plants as $plant) {
            $plantOptions[] = [
                "id" => $plant['plant'],
                "label" => $plant['plant']
            ];
        }

        return $plantOptions;
    }

    public function auth_plant($id_user = null)
    {
        if (isset($id_user)) {
            $plants = array();
            $query = $this->CI->db->query("SELECT kode
					FROM portal_dev..tbl_ac_users_companies a
					CROSS APPLY portal_dev.dbo.fnSplitString(companies,',')
					INNER JOIN portal_dev..tbl_inv_pabrik b
						ON splitdata = id_pabrik
					WHERE id_user = ? AND a.na = 'n' AND a.del = 'n' AND b.na = 'n' AND b.del = 'n'
					", array($id_user));

            $result = $query->result();
            if (!isset($result))
                $plants = array();

            foreach ($result as $plant) {
                $plants[] = $plant->kode;
            }

            return $plants;
        } else
            return array();
    }

    public function sendFCM($params = array())
    {
        $message = isset($params['message']) ? $params['message'] : null;
        $data = isset($params['data']) ? $params['data'] : null;
        $title = isset($params['title']) ? $params['title'] : null;
        $body = isset($params['body']) ? $params['body'] : $message;
        $code = isset($params['code']) ? $params['code'] : FCM_CODE_ALERT;
        $to = isset($params['to']) ? $params['to'] : null;
        $topic = isset($params['topic']) ? $params['topic'] : null;

        if (isset($topic))
            $to = '/topics/' . $topic;

        if (isset($to)) {
            $url = 'https://fcm.googleapis.com/fcm/send';

            $fcmMsg = array(
                'body' => $body,
                'title' => $title,
                'sound' => "default",
                'color' => "#FF0000"
            );

            $fields = array(
                'data' => array(
                    "message" => $message,
                    "data" => $data,
                    "code" => $code
                ),
                'notification' => $fcmMsg
            );

            if (is_array($to))
                $fields['registration_ids'] = $to;
            else
                $fields['to'] = $to;

            $fields = json_encode($fields);

            $headers = array(
                'Authorization: key=' . FCM_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } else
            return null;

    }
}