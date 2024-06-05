<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application  : K-IASS
    @author       : MATTHEW JODI (8944)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */

	class Dmasterscrap extends CI_Model {
		function get_master_flow($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_mflow_approval.*");

			$this->db->from('tbl_scrap_mflow_approval');

			if (isset($param['id_flow']) && $param['id_flow'] !== NULL)
				$this->db->where('tbl_scrap_mflow_approval.id_flow', $param['id_flow']);
			
			if (isset($param['alias_flow']) && $param['alias_flow'] !== NULL)
				$this->db->where('tbl_scrap_mflow_approval.alias_flow', $param['alias_flow']);
			
			if (isset($param['lokasi']) && $param['lokasi'] !== NULL)
				$this->db->where('tbl_scrap_mflow_approval.lokasi', $param['lokasi']);

			if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_mflow_approval.del', 'n');
				$this->db->where('tbl_scrap_mflow_approval.na', 'n');
			}


			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_master_role($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_role.kode_role");
			$this->db->select("tbl_scrap_role.level");
			$this->db->select("tbl_scrap_role.nama_role");
			$this->db->select("tbl_scrap_role.is_limit_pabrik");
			$this->db->select("tbl_scrap_role.dual_option_decline");
			$this->db->select("tbl_scrap_role.akses_delete");
			$this->db->select("tbl_scrap_role.tipe_user");
			$this->db->select("tbl_scrap_role.na");
			$this->db->select("tbl_scrap_role.del");

			$this->db->from('tbl_scrap_role');

			if (isset($param['kode_role']) && $param['kode_role'] !== NULL)
				$this->db->where('tbl_scrap_role.kode_role', $param['kode_role']);
			
			if (isset($param['checker']) && $param['checker'] !== NULL)
				$this->db->where('tbl_scrap_role.kode_role !=', $param['checker']);
			
			if (isset($param['nama_role']) && $param['nama_role'] !== NULL)
				$this->db->where('tbl_scrap_role.nama_role', $param['nama_role']);

			if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_role.del', 'n');
				$this->db->where('tbl_scrap_role.na', 'n');
			}


			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_master_role_detail($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_role.kode_role");
			$this->db->select("tbl_scrap_role.level");
			$this->db->select("tbl_scrap_role.nama_role");
			$this->db->select("tbl_scrap_role.is_limit_pabrik");
			$this->db->select("tbl_scrap_role.dual_option_decline");
			$this->db->select("tbl_scrap_role.akses_delete");
			$this->db->select("tbl_scrap_role.tipe_user");
			//detail
			$this->db->select("tbl_scrap_role_dtl.if_approve");
			$this->db->select("tbl_scrap_role_dtl.if_decline");
			$this->db->select("tbl_scrap_role_dtl.if_assign");
			$this->db->select("tbl_scrap_role_dtl.if_drop");
			$this->db->select("tbl_scrap_role_dtl.is_app_lim");
			$this->db->select("tbl_scrap_role_dtl.app_lim_val");
			$this->db->select("tbl_scrap_role_dtl.attach_file");
			$this->db->select("tbl_scrap_role_dtl.if_approve_capex");
			$this->db->select("tbl_scrap_role_dtl.if_decline_capex");
			$this->db->select("tbl_scrap_role_dtl.if_assign_capex");
			$this->db->select("tbl_scrap_role_dtl.if_drop_capex");
			$this->db->select("tbl_scrap_role_dtl.is_app_lim_capex");
			$this->db->select("tbl_scrap_role_dtl.app_lim_val_capex");
			$this->db->select("tbl_scrap_role_dtl.attach_file_capex");
			$this->db->select("tbl_scrap_role_dtl.id_flow");
			$this->db->select("tbl_scrap_mflow_approval.keterangan as flow");

			$this->db->from('tbl_scrap_role');

			$this->db->join("tbl_scrap_role_dtl", "tbl_scrap_role_dtl.kode_role = tbl_scrap_role.kode_role", "left");
			$this->db->join("tbl_scrap_mflow_approval", "tbl_scrap_mflow_approval.id_flow = tbl_scrap_role_dtl.id_flow and tbl_scrap_mflow_approval.del = 'n'", "left");

			if (isset($param['kode_role']) && $param['kode_role'] !== NULL)
				$this->db->where('tbl_scrap_role.kode_role', $param['kode_role']);

			if (isset($param['id_flow']) && $param['id_flow'] !== NULL)
				$this->db->where('tbl_scrap_role_dtl.id_flow', $param['id_flow']);
			
			if (isset($param['level']) && $param['level'] !== NULL)
				$this->db->where('tbl_scrap_role.level', $param['level']);

			if (isset($param['level']) && $param['level'] !== NULL)
				$this->db->where('tbl_scrap_role.level', $param['level']);

			if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_role.del', 'n');
				$this->db->where('tbl_scrap_role.na', 'n');
			}


			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_master_role_list($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_role_dtl.*");
			$this->db->select("main.*");
			
			//detail
			$this->db->select('app_join.nama_role as approve');
			$this->db->select('assign_join.nama_role as assign');
			$this->db->select('dec_join.nama_role as decline');
			$this->db->select('CASE tbl_scrap_role_dtl.if_drop
								WHEN \'drop\' THEN \'PERMANEN DROP\'
								ELSE drop_join.nama_role
							END as drops');
			$this->db->select('app_join_capex.nama_role as approve_capex');
			$this->db->select('assign_join_capex.nama_role as assign_capex');
			$this->db->select('dec_join_capex.nama_role as decline_capex');
			$this->db->select('CASE tbl_scrap_role_dtl.if_drop
								WHEN \'drop\' THEN \'PERMANEN DROP\'
								ELSE drop_join_capex.nama_role
							END as drops_capex');
			
			$this->db->from('tbl_scrap_role_dtl');
			$this->db->join("tbl_scrap_role as main", "tbl_scrap_role_dtl.kode_role. = main.kode_role", "left");
			$this->db->join('tbl_scrap_role as app_join', 'tbl_scrap_role_dtl.if_approve = CAST(app_join.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_scrap_role as assign_join', 'tbl_scrap_role_dtl.if_assign = CAST(assign_join.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_scrap_role as dec_join', 'tbl_scrap_role_dtl.if_decline = CAST(dec_join.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_scrap_role as drop_join', 'tbl_scrap_role_dtl.if_drop = CAST(drop_join.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_scrap_role as app_join_capex', 'tbl_scrap_role_dtl.if_approve_capex = CAST(app_join_capex.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_scrap_role as assign_join_capex', 'tbl_scrap_role_dtl.if_assign_capex = CAST(assign_join_capex.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_scrap_role as dec_join_capex', 'tbl_scrap_role_dtl.if_decline_capex = CAST(dec_join_capex.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_scrap_role as drop_join_capex', 'tbl_scrap_role_dtl.if_drop_capex = CAST(drop_join_capex.level AS VARCHAR(15))', 'left');


			if (isset($param['id_flow']) && $param['id_flow'] !== NULL)
				$this->db->where('tbl_scrap_role_dtl.id_flow', $param['id_flow']);

			if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_role.del', 'n');
				$this->db->where('tbl_scrap_role.na', 'n');
			}
			$this->db->order_by('tbl_scrap_role_dtl.kode_role', 'ASC');

			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}


	}

?>
