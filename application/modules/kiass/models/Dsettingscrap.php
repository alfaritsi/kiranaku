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

	class Dsettingscrap extends CI_Model {
		function get_data_roleuser($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_roleuser.*");

			$this->db->from('tbl_scrap_roleuser');

			if (isset($param['kode_role']) && $param['kode_role'] !== NULL)
                $this->db->where('tbl_scrap_roleuser.kode_role', $param['kode_role']);
            
            if (isset($param['user']) && $param['user'] !== NULL)
				$this->db->where('tbl_scrap_roleuser.user', $param['user']);

			if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_roleuser.na', 'n');
			}
			$this->db->where('tbl_scrap_roleuser.del', 'n');


			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}
        
        function get_data_roleuser_format($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("vw_scrap_setting_userrole.*");

			$this->db->from('vw_scrap_setting_userrole');

			if (isset($param['kode_role']) && $param['kode_role'] !== NULL)
                $this->db->where('vw_scrap_setting_userrole.kode_role', $param['kode_role']);
            
            if (isset($param['user']) && $param['user'] !== NULL)
				$this->db->where('vw_scrap_setting_userrole.user', $param['user']);

			if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('vw_scrap_setting_userrole.na', 'n');
			}
			$this->db->where('vw_scrap_setting_userrole.del', 'n');

			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_data_customer($param = NULL)
    	{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select('zdmmktsupp18.KUNNR as id');
			$this->db->select('zdmmktsupp18.*');
			$this->db->from('zdmmktsupp18');
			
			$this->db->where('zdmmktsupp18.VTWEG', '05');
			$this->db->where('zdmmktsupp18.SPART', '05');

			if (isset($param['plant']) && $param['plant'] !== NULL) {
				$this->db->where('zdmmktsupp18.vkorg', $param['plant']);
			}

			if (isset($param['search']) && $param['search'] !== NULL) {
				$this->db->group_start();
				$this->db->like('zdmmktsupp18.kunnr', $param['search'], 'both');
				$this->db->or_like('zdmmktsupp18.name1', $param['search'], 'both');
				$this->db->group_end();
			}

			$query = $this->db->get();
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
		
		function get_data_material($param = NULL)
    	{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			
			$this->db->select('vw_material_spec_byplant.*');
			$this->db->from('vw_material_spec_byplant');

			$this->db->where('vw_material_spec_byplant.vtweg', '05');
			$this->db->where('vw_material_spec_byplant.sales', '1');
			$this->db->where('vw_material_spec_byplant.na', 'n');
			$this->db->where('vw_material_spec_byplant.del', 'n');

			if (isset($param['plant']) && $param['plant'] !== NULL) {
				$this->db->where('vw_material_spec_byplant.plant', $param['plant']);
			}
			
			if (isset($param['search']) && $param['search'] !== NULL) {
				$this->db->group_start();
					$this->db->like('vw_material_spec_byplant.id', $param['search'], 'both');
					$this->db->or_like('vw_material_spec_byplant.full_description', $param['search'], 'both');
					$this->db->or_like('vw_material_spec_byplant.group_description', $param['search'], 'both');
                $this->db->group_end();
			}

			$query = $this->db->get();
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
