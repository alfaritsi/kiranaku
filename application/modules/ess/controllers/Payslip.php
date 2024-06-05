<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS Payslip
 * @author     : Akhmad Syaiful Yamang
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Payslip extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        show_404();
    }

    public function set($param = NULL)
    {
        switch ($param) {
            case 'password':
                $this->change_pass();
                break;

            default:
                show_404();
                break;
        }
    }

    public function save($param = NULL)
    {
        switch ($param) {
            case 'password':
                $this->save_pass();
                break;

            default:
                show_404();
                break;
        }
    }

    private function change_pass()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        $data['user'] = $this->general->get_data_user();
        //===============================================/

        $check = $this->rfc_sap(
            array(
                "nik" => base64_decode($this->session->userdata('-nik-')),
                "flag" => ""
            )
        );

        $data['title'] = "E-Payslip";
        $data['title_form'] = "E-Payslip";
        $data['new'] = $check; //true if pass is empty
        $this->load->view("payslip/pass", $data);
    }

    private function save_pass()
    {
        $post = $this->input->post(NULL, TRUE);

        if ($post['new_pass'] === $post['new_pass_conf']) {
            $this->rfc_sap(
                array(
                    "nik" => base64_decode($this->session->userdata('-nik-')),
                    "new_pass" => $this->general->emptyconvert($post['new_pass'], ""),
                    "old_pass" => $this->general->emptyconvert(@$post['old_pass'], ""),
                    "flag" => "X"
                )
            );
        } else {
            $result = array(
                'sts' => 'NotOK',
                'msg' => 'Password tidak sesuai'
            );
            echo json_encode($result);
            exit();
        }
    }

    private function rfc_sap($param = NULL)
    {
        $this->data['sap'] = $this->general->connectSAP("ESS");
        $param_rfc = array(
            array("IMPORT", "I_PERNR", $param['nik']),
            array("IMPORT", "I_NEWPAS", $this->general->emptyconvert(@$param['new_pass'], "")),
            array("IMPORT", "I_LASTPAS", $this->general->emptyconvert(@$param['old_pass'], "")),
            array("IMPORT", "I_FLAG", $param['flag']),
            array("EXPORT", "E_RETURN", array()),
        );
        $result = $this->data['sap']->callFunction("Z_RFC_PASSEPAYSLIP", $param_rfc);

        if (empty($param['flag'])) {
            return $result['E_RETURN']['TYPE'] === 'S';
        } else {
            $result = array(
                'sts' => ($result['E_RETURN']['TYPE'] == 'S' ? 'OK' : 'NotOK'),
                'msg' => $result['E_RETURN']['MESSAGE']
            );
            echo json_encode($result);
            exit();
        }
    }
}
