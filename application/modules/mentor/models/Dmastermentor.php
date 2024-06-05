<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : MENTORING
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmastermentor extends CI_Model{
    function get_data_status($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_mentor_status.id_status AS id');
		$this->db->select('CASE
								WHEN tbl_mentor_status.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
        $this->db->select('tbl_mentor_status.*');
        $this->db->from("tbl_mentor_status");
        if (isset($param['id_status']) && $param['id_status'] !== NULL)
            $this->db->where('tbl_mentor_status.id_status', $param['id_status']);

        if (isset($param['search']) && $param['search'] !== NULL) {
            $this->db->group_start();
            $this->db->like('tbl_mentor_status.nik', $param['search'], 'both');
            $this->db->or_like('tbl_mentor_status.nama', $param['search'], 'both');
            $this->db->group_end();
        }
		$query = $this->db->get();

        // $query = $this->db->query($qr);

        if (isset($param['single_row']) && $param['single_row'] !== NULL)
            $result = $query->row();
        else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
	
}
?>