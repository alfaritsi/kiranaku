<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS Cuti & Ijin - Library
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Less
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        // $this->CI->load->model('dessgeneral');
        // $this->CI->load->model('dbak');
        // $this->CI->load->model('dcutiijin');
        // $this->CI->load->model('dmedical');
    }

   

    public function revert_rupiah($money)
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot);

        return (float)str_replace(',', '.', $removedThousendSeparator);
    }

    public
    function convert_rupiah($nilai, $pecahan = 0)
    {
        $rupiah = 'Rp. ' . number_format($nilai, $pecahan, ',', '.');
        return $rupiah;
    }

    

    public
    function get_atasan($params = array())
    {
        $id_departemen = isset($params['id_departemen']) ? $params['id_departemen'] : base64_decode($this->CI->session->userdata('-id_departemen-'));
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : base64_decode($this->CI->session->userdata('-id_divisi-'));
        $id_direktorat = isset($params['id_direktorat']) ? $params['id_direktorat'] : base64_decode($this->CI->session->userdata('-id_direktorat-'));
        $id_ceo = isset($params['id_ceo']) ? $params['id_ceo'] : base64_decode($this->CI->session->userdata('-id_ceo-'));
        $id_level = isset($params['id_level']) ? $params['id_level'] : base64_decode($this->CI->session->userdata('-id_level-'));
        $nik = isset($params['nik']) ? $params['nik'] : base64_decode($this->CI->session->userdata('-nik-'));

        $query = $this->CI->db->query("EXEC dbo.SP_Kiranaku_Approval NULL,'$nik'");

        $row = $query->row();

        $nik_atasan = '';
        $nik_atasan_email = '';
        $list_atasan = array();
        $list_atasan_email = array();

        if (isset($row) && !empty($row)) {
            $nik_atasan = $row->atasan;
            $nik_atasan_email = $row->atasan_nik_email;
            $list_atasan = explode(', ', $row->atasan_nama);
            $list_atasan_email = explode(' | ', $row->atasan_email);
            foreach ($list_atasan_email as $i => $list_email) {
                $list_atasan_email[$i] = trim($list_email);
            }
        }

//        $nik_departemen = $this->CI->dessgeneral->nik_bagian($id_departemen);
//        $nik_divisi = $this->CI->dessgeneral->nik_bagian($id_divisi);
//        $nik_direktorat = $this->CI->dessgeneral->nik_bagian($id_direktorat);
//        $nik_ceo = $this->CI->dessgeneral->nik_bagian($id_ceo);
//
//        if ($id_level == 9100) {
//            $nik_atasan = $nik_ceo;
//            $nik_atasan_email = $nik_ceo;
//        } else if ($id_level == 9101) {
//            if ($id_direktorat == 0) {
//                $nik_atasan = $nik_ceo;
//                $nik_atasan_email = $nik_ceo;
//            } else {
//                $nik_atasan = $nik_direktorat . '' . $nik_ceo;
//                $nik_atasan_email = $nik_direktorat;
//            }
//        } elseif ($id_level == 9102) {    //ka dept
//            if ($id_divisi == 0) {
//                $nik_atasan = $nik_direktorat . '' . $nik_ceo;
//                $nik_atasan_email = $nik_direktorat;
//            } elseif ($id_direktorat == 0) {
//                $nik_atasan = $nik_divisi . '' . $nik_ceo;
//                $nik_atasan_email = $nik_divisi;
//            } else {
//                $nik_atasan = $nik_divisi . '' . $nik_direktorat;
//                $nik_atasan_email = $nik_divisi;
//            }
//        } elseif ($id_level == 9103) {    //staff
//            if ($id_departemen == 0) {
//                $nik_atasan = $nik_divisi . '' . $nik_direktorat;
//                $nik_atasan_email = $nik_divisi;
//            } elseif ($id_divisi == 0) {
//                $nik_atasan = $nik_direktorat . '' . $nik_ceo;
//                $nik_atasan_email = $nik_direktorat;
//            } else {
////                $ck = GetaField('tbl_atasan', 'id_departemen', base64_decode($_SESSION['-id_departemen-']), 'id_atasan');
//                $ck = $this->CI->dessgeneral->nik_atasan($id_departemen);
//                $nik_atasan = $nik_departemen . '' . $nik_divisi;
//                $nik_atasan_email = (empty($ck)) ? $nik_divisi : $nik_departemen;
//            }
//        }
//
//        //pengalihan approval CEO ke Kadep HR Ops
//        $user_hr_div = $this->CI->dessgeneral->get_user(9101, 766);
//        $user_hr_op = $this->CI->dessgeneral->get_user(9102, 797);
//        $nik_hr_division = isset($user_hr_div) ? $user_hr_div->id_karyawan : ''; //get nik untuk kadiv hr operation
//        $nik_hr_operation = isset($user_hr_op) ? $user_hr_op->id_karyawan : '';    //get nik untuk kadep hr operation
//        $nik_atasan = str_replace(5530, $nik_hr_operation, $nik_atasan);    //jika CEO(Pak martinus) dialihkan ke HR Operation
//        $nik_atasan = str_replace('6724.', '', $nik_atasan);    //jika CEO(Pak Toddy) dihilangkan
//        $nik_atasan = str_replace('6725.', '', $nik_atasan);    //jika CEO(Pak Toddy) dihilangkan
//        $nik_atasan_email = str_replace(5530, $nik_hr_operation, $nik_atasan_email);    //jika CEO(Pak martinus) dialihkan ke HR Operation
//        $nik_atasan_email = str_replace('6724.', $nik_hr_operation, $nik_atasan_email);    //jika CEO(Pak Toddy) dihilangkan
//        $nik_atasan_email = str_replace('6725.', $nik_hr_operation, $nik_atasan_email);    //jika CEO(Pak Toddy) dihilangkan
//        $nik_atasan_email = str_replace('8892.', '', $nik_atasan_email);    //jika bu jenny send email dihilangkan
//
//        //pengecualian untuk nik 7041(pak bani) approval hardcode
////        $nik_atasan = ($nik == 7041) ? $nik_hr_division . '.' . $nik_hr_operation . '.' : $nik_atasan;
////        $nik_atasan_email = ($nik == 7041) ? $nik_hr_division . '.' : $nik_atasan_email;
//
//        //ambil dari tbl_atasan_master
//        $atasan_master = $this->CI->dessgeneral->get_atasan_master($nik);
//        $nik_atasan = (!empty($atasan_master->id_atasan_master)) ? $atasan_master->atasan : $nik_atasan;
//        $nik_atasan_email = (!empty($atasan_master->id_atasan_master)) ? $atasan_master->atasan_email : $nik_atasan_email;
//
//        $atasan = str_replace('.' . $nik, '', $nik_atasan);
//        $atasan = substr($atasan, 0, -1);
//
//        $atasan = explode(".", $atasan);
//        $list_atasan = array();
//        foreach ($atasan as $val) {
//            $karyawan = $this->CI->dessgeneral->get_karyawan($val);
//            $list_atasan[] = ucwords(strtolower(isset($karyawan) ? $karyawan->nama : ''));
//        }
//        $atasan_email = str_replace('.' . $nik, '', $nik_atasan_email);
//        $atasan_email = substr($atasan_email, 0, -1);
//        $atasan_email = explode(".", $atasan_email);
//        $list_atasan_email = array();
//        foreach ($atasan_email as $val) {
//            $karyawan = $this->CI->dessgeneral->get_karyawan($val);
//            $list_atasan_email[] = strtolower(isset($karyawan) ? $karyawan->email : '');
//        }
//        $this->CI->general->closeDb();

        return compact('nik_atasan', 'nik_atasan_email', 'list_atasan', 'list_atasan_email');
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