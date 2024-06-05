<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : PCS (Production Cost Simulation)
@author 		: Lukman Hakim (7143)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dtransaksipcs extends CI_Model{
	function get_data_listrik($id_mlistrik=NULL, $all=NULL, $bulan=NULL, $plant=NULL){
		$this->general->connectDbPortal();
		$this->db->select('ZDMMSPLANT.*');
		$this->db->select('ZDMMSPLANT.NAME1 as plant_name');
		$this->db->select('ZDMMSPLANT.WERKS as plant');
		$this->db->select("'$bulan' as bulan");
		$this->db->select("(select tbl_pcs_mlistrik.lwbp 
							  from tbl_pcs_mlistrik 
							 where tbl_pcs_mlistrik.bulan='$bulan' 
							   and tbl_pcs_mlistrik.kode_pabrik COLLATE SQL_Latin1_General_CP1_CI_AS = ZDMMSPLANT.WERKS 
							   and tbl_pcs_mlistrik.active='1') as lwbp");
		$this->db->select("(select tbl_pcs_mlistrik.wbp 
							  from tbl_pcs_mlistrik 
							 where tbl_pcs_mlistrik.bulan='$bulan' 
							   and tbl_pcs_mlistrik.kode_pabrik COLLATE SQL_Latin1_General_CP1_CI_AS = ZDMMSPLANT.WERKS 
							   and tbl_pcs_mlistrik.active='1') as wbp");
		$this->db->from("[".DB_DEFAULT."].dbo.ZDMMSPLANT"); 
		if($plant != NULL){
			$this->db->where('ZDMMSPLANT.WERKS', $plant);
		}
		
		$this->db->order_by('ZDMMSPLANT.WERKS', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}
	function cek_data_listrik($bulan=NULL, $plant=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_pcs_mlistrik.*');
		$this->db->from('tbl_pcs_mlistrik');
		if($plant != NULL){
			$this->db->where('tbl_pcs_mlistrik.kode_pabrik', $plant);
		}
		if($bulan != NULL && trim($bulan) !== ""){
			$this->db->where('tbl_pcs_mlistrik.bulan', $bulan);
		}
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}

}

?>