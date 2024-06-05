<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Email Routing - Authorization Control Report
@author     : Octe Reviyanto Nugroho
@contributor  :
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Authcontrol extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dauthcontrol');
    }

    function index()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        //===============================================/

        $data['user'] = $this->general->get_data_user();
        $data['roles'] = $this->dauthcontrol->get_roles();
        $role = isset($_POST['role'])?$_POST['role']:null;
        $data['auths'] = $this->get_data($role);
        $this->load->view('routing/auth/auth', $data);
    }

    function get_data($id_role=null,$all = null)
    {

        $result = array();
        $roleJabatans = $this->dauthcontrol->get_role_jabatans($id_role);
        foreach ($roleJabatans as $i => $jabatan)
        {
            $roleJabatans[$i] = $jabatan->id_jabatan;
        }
        $roleDivisis = $this->dauthcontrol->get_role_divisis($id_role);
        foreach ($roleDivisis as $i => $divisi)
        {
            $roleDivisis[$i] = $divisi->id_divisi;
        }
        $roleDepartemens = $this->dauthcontrol->get_role_departemens($id_role);
        foreach ($roleDepartemens as $i => $departemen)
        {
            $roleDepartemens[$i] = $departemen->id_departemen;
        }
        if(isset($id_role) && !empty($id_role))
            $result = $this->dauthcontrol->get_data($id_role,$all,$roleJabatans,$roleDivisis,$roleDepartemens);

        return $result;
    }

}