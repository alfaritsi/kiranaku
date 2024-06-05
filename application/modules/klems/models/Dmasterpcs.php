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

class Dmasterpcs extends CI_Model{
	function get_data_jns_formula($id_mjenis=NULL, $all=NULL, $jenis=NULL, $id_mjenis_in=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_mjenis.*,
						   CASE
						   		WHEN tbl_pcs_mjenis.active = 1 THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_pcs_mjenis');
		if($id_mjenis != NULL){
			$this->db->where('tbl_pcs_mjenis.id_mjenis', $id_mjenis);
		}
		if($id_mjenis_in != NULL){
			$this->db->where_in('tbl_pcs_mjenis.id_mjenis', $id_mjenis_in);
		}
		if($jenis != NULL && trim($jenis) !== ""){
			$this->db->where('tbl_pcs_mjenis.jns_formula', $jenis);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_mjenis.active', 1);
		}
		$this->db->order_by('tbl_pcs_mjenis.id_mjenis', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}

	function get_data_pegrup($id_mpegrup=NULL, $all=NULL, $nama=NULL, $id_mpegrup_in=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_mpegrup.*,
						   CASE
						   		WHEN tbl_pcs_mpegrup.active = 1 THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_pcs_mpegrup');
		if($id_mpegrup != NULL){
			$this->db->where('tbl_pcs_mpegrup.id_mpegrup', $id_mpegrup);
		}
		if($id_mpegrup_in != NULL){
			$this->db->where_in('tbl_pcs_mpegrup.id_mpegrup', $id_mpegrup_in);
		}
		if($nama != NULL && trim($nama) !== ""){
			$this->db->where('tbl_pcs_mpegrup.nama_grup', $nama);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_mpegrup.active', 1);
		}
		$this->db->order_by('tbl_pcs_mpegrup.id_mpegrup', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_lwbp($plant=NULL, $all=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_mlwbp.*,
						   CASE
						   		WHEN tbl_pcs_mlwbp.active = 1 THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_pcs_mlwbp');
		if($plant != NULL){
			$this->db->where('tbl_pcs_mlwbp.kode_pabrik', $plant);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_mlwbp.active', 1);
		}
		$this->db->order_by('tbl_pcs_mlwbp.kode_pabrik', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_wbp($plant=NULL, $all=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_mwbp.*,
						   CASE
						   		WHEN tbl_pcs_mwbp.active = 1 THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_pcs_mwbp');
		if($plant != NULL){
			$this->db->where('tbl_pcs_mwbp.kode_pabrik', $plant);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_mwbp.active', 1);
		}
		$this->db->order_by('tbl_pcs_mwbp.kode_pabrik', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_cangkang($plant=NULL, $all=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_mcangkang.*,
						   CASE
						   		WHEN tbl_pcs_mcangkang.active = 1 THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_pcs_mcangkang');
		if($plant != NULL){
			$this->db->where('tbl_pcs_mcangkang.kode_pabrik', $plant);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_mcangkang.active', 1);
		}
		$this->db->order_by('tbl_pcs_mcangkang.kode_pabrik', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_genset($plant=NULL, $all=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_mgenset.*,
						   CASE
						   		WHEN tbl_pcs_mgenset.active = 1 THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_pcs_mgenset');
		if($plant != NULL){
			$this->db->where('tbl_pcs_mgenset.kode_pabrik', $plant);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_mgenset.active', 1);
		}
		$this->db->order_by('tbl_pcs_mgenset.kode_pabrik', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_drier($plant=NULL, $all=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_mdrier.*,
						   CASE
						   		WHEN tbl_pcs_mdrier.active = 1 THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_pcs_mdrier');
		if($plant != NULL){
			$this->db->where('tbl_pcs_mdrier.kode_pabrik', $plant);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_mdrier.active', 1);
		}
		$this->db->order_by('tbl_pcs_mdrier.kode_pabrik', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_lain($plant=NULL, $all=NULL){
		$this->general->connectDbPortal();

		$this->db->select('tbl_pcs_mlain.*,
						   CASE
						   		WHEN tbl_pcs_mlain.active = 1 THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_pcs_mlain');
		if($plant != NULL){
			$this->db->where('tbl_pcs_mlain.kode_pabrik', $plant);
		}
		if($all == NULL){
			$this->db->where('tbl_pcs_mlain.active', 1);
		}
		$this->db->order_by('tbl_pcs_mlain.kode_pabrik', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_COA($saknr=NULL, $gltxt=NULL){
		$this->general->connectDbDefault();

		$this->db->distinct(0);
		$this->db->select('ZDMFICO04.SAKNR as id,
						   ZDMFICO04.SAKNR,
					       ZDMFICO04.SAKNR + \' - \' + ZDMFICO04.GLTXT AS FULL_GLTXT,
					       ZDMFICO04.GLTXT');
		$this->db->from('ZDMFICO04');
		if($saknr !== NULL){
			$this->db->where('ZDMFICO04.SAKNR', $saknr);
		}
		if($gltxt !== NULL){
			$this->db->like('ZDMFICO04.GLTXT', $gltxt, 'both');
		}
		$this->db->order_by('ZDMFICO04.GLTXT', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();

		$this->general->closeDb();
		return $result;
	}
}

?>