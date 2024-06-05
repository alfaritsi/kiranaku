<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Setting (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */

Class Settings extends MX_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        redirect('/');
    }


}