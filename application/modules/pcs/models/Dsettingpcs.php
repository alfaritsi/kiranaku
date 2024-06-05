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
		$this->db->order_by('tbl_pcs_setting_pecoa.id_mpegrup', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_pecoa_gruping($id_mpegrup=NULL, $all=NULL){
		$string	= "SELECT tbl_pcs_setting_pecoa.id_mpegrup,
					       CAST(
					         (SELECT DISTINCT ZDMFICO04.saknr + ' - ' + ZDMFICO04.gltxt + RTRIM(',')
					            FROM [".DB_DEFAULT."].dbo.ZDMFICO04 as ZDMFICO04
				               INNER JOIN [".DB_PORTAL."].dbo.tbl_pcs_setting_pecoa as tbl_pcs_setting_pecoa2 
				                       ON tbl_pcs_setting_pecoa2.id_mpegrup = tbl_pcs_setting_pecoa.id_mpegrup
				                      AND ZDMFICO04.SAKNR = tbl_pcs_setting_pecoa2.saknr COLLATE SQL_Latin1_General_CP1_CS_AS
				                      AND tbl_pcs_setting_pecoa2.active = 1
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS COA_list,
					       CAST(
					         (SELECT DISTINCT ZDMFICO04.saknr + RTRIM(',')
					            FROM [".DB_DEFAULT."].dbo.ZDMFICO04 as ZDMFICO04
				               INNER JOIN [".DB_PORTAL."].dbo.tbl_pcs_setting_pecoa as tbl_pcs_setting_pecoa2 
				                       ON tbl_pcs_setting_pecoa2.id_mpegrup = tbl_pcs_setting_pecoa.id_mpegrup
				                      AND ZDMFICO04.SAKNR = tbl_pcs_setting_pecoa2.saknr COLLATE SQL_Latin1_General_CP1_CS_AS
				                      AND tbl_pcs_setting_pecoa2.active = 1
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS no_COA_list,
					       tbl_pcs_mpegrup.nama_grup,
					       tbl_pcs_setting_pecoa.tanggal_edit,
						   CASE
						   		WHEN tbl_pcs_setting_pecoa.active = 1 THEN '<span class=\"label label-success\">ACTIVE</span>'
						   		ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
						   END as label_active
				    FROM [".DB_PORTAL."].dbo.tbl_pcs_setting_pecoa as tbl_pcs_setting_pecoa
				   INNER JOIN [".DB_PORTAL."].dbo.tbl_pcs_mpegrup as tbl_pcs_mpegrup ON tbl_pcs_mpegrup.id_mpegrup = tbl_pcs_setting_pecoa.id_mpegrup
				   WHERE 1 = 1";
		if($id_mpegrup != NULL){
			$string	.= " AND tbl_pcs_setting_pecoa.id_mpegrup = $id_mpegrup";
		}
		if($all == NULL){
			$string	.= " AND tbl_pcs_setting_pecoa.active = 1";
		}
		$string	.= " GROUP BY tbl_pcs_setting_pecoa.id_mpegrup, 
					          tbl_pcs_mpegrup.nama_grup,
					          tbl_pcs_setting_pecoa.tanggal_edit, 
					          tbl_pcs_setting_pecoa.active ";
		$string	.= " ORDER BY tbl_pcs_setting_pecoa.id_mpegrup ASC ";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	//====================================================//

	function get_data_formcoa($id_mjenis=NULL, $id_mnorma=NULL, $saknr=NULL, $all=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_setting_formcoa.*');
		$this->db->from('tbl_pcs_setting_formcoa');
		if($id_mjenis != NULL){
			$this->db->where('tbl_pcs_setting_formcoa.id_mjenis', $id_mjenis);
		}
		if($id_mnorma != NULL){
			$this->db->where('tbl_pcs_setting_formcoa.id_mnorma', $id_mnorma);
		}
		if($saknr != NULL){
			$this->db->where('tbl_pcs_setting_formcoa.saknr COLLATE SQL_Latin1_General_CP1_CS_AS', $saknr);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_setting_formcoa.active', 1);
		}
		$this->db->order_by('tbl_pcs_setting_formcoa.id_mjenis', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_formcoa_gruping($id_mjenis=NULL, $id_mnorma=NULL, $all=NULL){
		$string	= "SELECT tbl_pcs_setting_formcoa.id_mjenis,
		 				   tbl_pcs_setting_formcoa.id_mnorma,
					       CAST(
					         (SELECT DISTINCT ZDMFICO04.saknr + ' - ' + ZDMFICO04.gltxt + RTRIM(',')
					            FROM [".DB_DEFAULT."].dbo.ZDMFICO04 as ZDMFICO04
				               INNER JOIN [".DB_PORTAL."].dbo.tbl_pcs_setting_formcoa as tbl_pcs_setting_formcoa2 
				                       ON tbl_pcs_setting_formcoa2.id_mjenis = tbl_pcs_setting_formcoa.id_mjenis
				                      AND (tbl_pcs_setting_formcoa2.id_mnorma = tbl_pcs_setting_formcoa.id_mnorma OR tbl_pcs_setting_formcoa2.id_mnorma IS NULL)
				                      AND ZDMFICO04.SAKNR = tbl_pcs_setting_formcoa2.saknr  COLLATE SQL_Latin1_General_CP1_CS_AS
				                      AND tbl_pcs_setting_formcoa2.active = 1
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS COA_list,
					       CAST(
					         (SELECT DISTINCT ZDMFICO04.saknr + RTRIM(',')
					            FROM [".DB_DEFAULT."].dbo.ZDMFICO04 as ZDMFICO04
				               INNER JOIN [".DB_PORTAL."].dbo.tbl_pcs_setting_formcoa as tbl_pcs_setting_formcoa2 
				                       ON tbl_pcs_setting_formcoa2.id_mjenis = tbl_pcs_setting_formcoa.id_mjenis
				                      AND (tbl_pcs_setting_formcoa2.id_mnorma = tbl_pcs_setting_formcoa.id_mnorma OR tbl_pcs_setting_formcoa2.id_mnorma IS NULL)
				                      AND ZDMFICO04.SAKNR = tbl_pcs_setting_formcoa2.saknr COLLATE SQL_Latin1_General_CP1_CS_AS
				                      AND tbl_pcs_setting_formcoa2.active = 1
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS no_COA_list,
					       tbl_pcs_mjenis.jns_formula,
					       tbl_pcs_mnorma.norma,
					       tbl_pcs_setting_formcoa.tanggal_edit,
						   CASE
						   		WHEN tbl_pcs_setting_formcoa.active = 1 THEN '<span class=\"label label-success\">ACTIVE</span>'
						   		ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
						   END as label_active
				    FROM [".DB_PORTAL."].dbo.tbl_pcs_setting_formcoa as tbl_pcs_setting_formcoa
				   INNER JOIN [".DB_PORTAL."].dbo.tbl_pcs_mjenis as tbl_pcs_mjenis ON tbl_pcs_mjenis.id_mjenis = tbl_pcs_setting_formcoa.id_mjenis
				    LEFT JOIN [".DB_PORTAL."].dbo.tbl_pcs_mnorma as tbl_pcs_mnorma ON tbl_pcs_mnorma.id_mnorma = tbl_pcs_setting_formcoa.id_mnorma
				   WHERE 1 = 1";
		if($id_mjenis !== NULL){
			$string	.= " AND tbl_pcs_setting_formcoa.id_mjenis = $id_mjenis";
		}
		if($id_mnorma !== NULL){
			$string	.= " AND tbl_pcs_setting_formcoa.id_mnorma = $id_mnorma";
		}
		if($all == NULL){
			$string	.= " AND tbl_pcs_setting_formcoa.active = 1";
		}
		$string	.= " GROUP BY tbl_pcs_setting_formcoa.id_mjenis,
							  tbl_pcs_setting_formcoa.id_mnorma, 
					          tbl_pcs_mjenis.jns_formula,
					          tbl_pcs_mnorma.norma,
					          tbl_pcs_setting_formcoa.tanggal_edit, 
					          tbl_pcs_setting_formcoa.active ";
		$string	.= " ORDER BY tbl_pcs_setting_formcoa.id_mjenis ASC ";

		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	//====================================================//

	function get_data_setting_backward(){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_setting_historical_backward.*');
		$this->db->from('tbl_pcs_setting_historical_backward');
		$query 	= $this->db->get();
		$result	= $query->row();

		$this->general->closeDb();
		return $result;
	}
}

?>