<?php
/*
@application  : SPL
@author       : Benazi Sosro Bahari (10183)
@contributor  :
        1. <insert your fullname> (<insert your nik>) <insert the date>
            <insert what you have modified>
        2. <insert your fullname> (<insert your nik>) <insert the date>
            <insert what you have modified>
        etc.
*/

class BaseControllers extends MX_Controller
{
    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('sap');
    }

    public function index()
    {
        show_404();
    }

    protected function connectSAP($user)
    {
        $koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);
        $conn    = $koneksi[$user];
        $constr  = array(
            "logindata"   => array(
                "ASHOST" => $conn['ASHOST'],    // application server
                "SYSNR"  => $conn['SYSNR'],        // system number
                "CLIENT" => $conn['CLIENT'],    // client
                "USER"   => $conn['USER'],        // user
                "PASSWD" => $conn['PASSWD']        // password
            ),
            "show_errors" => $conn['DEBUG'],                // let class printout errors
            "debug"       => $conn['DEBUG']
        );            // detailed debugging information

        $this->data['sap'] = new saprfc($constr);
    }
}
