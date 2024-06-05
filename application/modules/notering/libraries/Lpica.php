<?php

class Lpica {
	 protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
    }
	public
    function send_email($params = array())
    {
        $judul = isset($params['judul']) ? $params['judul'] : null;
        $email_pengirim = isset($params['email_pengirim']) ? $params['email_pengirim'] : null;
        $email_tujuan = isset($params['email_tujuan']) ? $params['email_tujuan'] : array();
        $email_cc = isset($params['email_cc']) ? $params['email_cc'] : array();
        $view = isset($params['view']) ? $params['view'] : null;
        $data = isset($params['data']) ? $params['data'] : null;

        $message = "";
        if (isset($view) && !empty($view))
            $message = $this->CI->load->view($view, compact('data'), true);

        // echo $message;
        // die();

        $result = $this->process_send_email(
            array(
                'subject' => $judul,
                'from_alias' => $email_pengirim,
                'to' => $email_tujuan,
                'cc' => $email_cc,
                'message' => $message
            )
        );

        return $result;
    }

    private
    function process_send_email($params = array())
    {
        $subject = isset($params['subject']) ? $params['subject'] : null;
        $from_alias = isset($params['from_alias']) ? $params['from_alias'] : null;
        $message = isset($params['message']) ? $params['message'] : null;
        $to = isset($params['to']) ? $params['to'] : null;
        $cc = isset($params['cc']) ? $params['cc'] : null;

        if (!empty($subject) && !empty($from_alias) && !empty($to) && !empty($message)) {
            setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = KIRANA_EMAIL_HOST;
            $config['smtp_user'] = KIRANA_EMAIL_USER;
            $config['smtp_pass'] = KIRANA_EMAIL_PASS;
            $config['smtp_port'] = KIRANA_EMAIL_PORT;
            $config['smtp_crypto'] = 'ssl';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = true;
            $config['mailtype'] = 'html';

            try {
                $open_socket = @fsockopen(KIRANA_EMAIL_HOST, KIRANA_EMAIL_PORT, $errno, $errstr, 30);
                if (!$open_socket) {
                    $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                    $sts = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                } else {
                    $this->CI->load->library('email', $config);

                    $this->CI->email->from('no-reply@kiranamegatara.com', $from_alias);
                    $this->CI->email->to($to);
                    if (isset($cc) && !empty($cc)) {
                        $this->CI->email->cc($cc);
                    }

                    $this->CI->email->subject($subject);
                    $this->CI->email->message($message);

                    if (!$this->CI->email->send()) {
                        $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                        $sts = "NotOK";
                        $return = array('sts' => $sts, 'msg' => $msg);
                    } else {
                        $sts = "OK";
                        $return = array('sts' => $sts);
                    }
                }
            } catch (Exception $e) {
                $msg = $e->getMessage();
                $sts = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
            }
        } else {
            $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
            $sts = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
        }

        return $return;
    }
}