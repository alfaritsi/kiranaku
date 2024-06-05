<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Mapping Plant Header
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmapping extends CI_Model{
	function get_data_plant_header($conn = NULL, $apps = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_mapping_plant_header.*');
		$this->db->select('CASE
								WHEN tbl_mapping_plant_header.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_mapping_plant_header');
		if ($apps !== NULL) {
			$this->db->where('tbl_mapping_plant_header.apps', $apps);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_mapping_plant_header.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_mapping_plant_header.del', $deleted);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_plant_detail($conn = NULL, $apps = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_mapping_plant_detail.*');
		$this->db->select('CASE
								WHEN tbl_mapping_plant_detail.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_mapping_plant_detail');
		if ($apps !== NULL) {
			$this->db->where('tbl_mapping_plant_detail.apps', $apps);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_mapping_plant_detail.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_mapping_plant_detail.del', $deleted);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	//
}
?>