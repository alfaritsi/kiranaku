<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : KIRANAKU
@author 		: Benazi Sosro Bahari (10183)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dreport extends CI_Model
{
    function get_data_sla($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select("*");
        $this->db->from("vw_leg_report_sla");

        if (isset($param['tanggal_perjanjian_awal']) && $param['tanggal_perjanjian_awal'] !== NULL)
            $this->db->where('vw_leg_report_sla.tanggal_perjanjian >=', $param['tanggal_perjanjian_awal']);
        if (isset($param['tanggal_perjanjian_akhir']) && $param['tanggal_perjanjian_akhir'] !== NULL)
            $this->db->where('vw_leg_report_sla.tanggal_perjanjian <=', $param['tanggal_perjanjian_akhir']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_leg_report_sla.plant', $param['IN_plant']);
        
        $query = $this->db->get();

        if (isset($param['id_spk']) && $param['id_spk'] !== NULL) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
}
