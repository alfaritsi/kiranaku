<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : PCS (Production Cost Simulation)
@author 		: Akhmad Syaiful Yamang (8347)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dsettingpcs extends CI_Model{
	function get_data_pecoa($id_mpegrup=NULL, $saknr=NULL, $all=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_setting_pecoa.*');
		$this->db->from('tbl_pcs_setting_pecoa');
		if($id_mpegrup != NULL){
			$this->db->where('tbl_pcs_setting_pecoa.id_mpegrup', $id_mpegrup);
		}
		if($saknr != NULL){
			$this->db->where('tbl_pcs_setting_pecoa.saknr', $saknr);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_setting_pecoa.active', 1);
		}
		$this->db->order_by('tbl_pcs_setting_pecoa.kode_pabrik', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_pecoa_gruping($id_mpegrup=NULL, $all=NULL){
		$string	= "SELECT tbl_pcs_setting_pecoa.*,
					       CAST(
					         (SELECT DISTINCT ZDMFICO04.saknr + ' - ' + ZDMFICO04.gltxt + RTRIM(',')
					            FROM [dashboarddev].dbo.ZDMFICO04 as ZDMFICO04
					           WHERE ZDMFICO04.SAKNR = tbl_pcs_setting_pecoa.saknr
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS COA_list,
					       CAST(
					         (SELECT DISTINCT ZDMFICO04.saknr + RTRIM(',')
					            FROM [dashboarddev].dbo.ZDMFICO04 as ZDMFICO04
					           WHERE ZDMFICO04.SAKNR = tbl_pcs_setting_pecoa.saknr
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS no_COA_list,
					       tbl_pcs_mpegrup.nama_grup,
						   CASE
						   		WHEN tbl_pcs_setting_pecoa.active = 1 THEN '<span class=\"label label-success\">ACTIVE</span>'
						   		ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
						   END as label_active
				    FROM [portal_dev].dbo.tbl_pcs_setting_pecoa as tbl_pcs_setting_pecoa
				   INNER JOIN [portal_dev].dbo.tbl_pcs_mpegrup as tbl_pcs_mpegrup ON tbl_pcs_mpegrup.id_mpegrup = tbl_pcs_setting_pecoa.id_mpegrup
				   WHERE 1 = 1";
		if($id_mpegrup != NULL){
			$string	.= " AND tbl_pcs_setting_pecoa.id_mpegrup = $id_mpegrup";
		}
		if($all == NULL){
			$string	.= " AND tbl_pcs_setting_pecoa.active = 1";
		}
		$string	.= " ORDER BY tbl_pcs_setting_pecoa.id_mpegrup ASC ";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}
}

?>