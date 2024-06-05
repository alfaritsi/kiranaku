<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : PLANTATION
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class DMaster extends CI_Model
{
    function get_material($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_material.*');
        $this->db->from("vw_ktp_material");

        if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
            $this->db->where('vw_ktp_material.MATNR', $param['kode_barang']);
        if (isset($param['is_active']) && $param['is_active'] !== NULL)
            $this->db->where('vw_ktp_material.is_active', $param['is_active']);

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("vw_ktp_material.MATNR,
                vw_ktp_material.MANDT,
                vw_ktp_material.LGORT,
                vw_ktp_material.MAKTX,
                vw_ktp_material.MEINS,
                vw_ktp_material.MATKL,
                vw_ktp_material.MTART,
                vw_ktp_material.GROES,
                vw_ktp_material.LVORM,
                vw_ktp_material.is_active,
                vw_ktp_material.classification,
                vw_ktp_material.asset_class,
                vw_ktp_material.asset_class_desc,
                vw_ktp_material.gl_account,
                vw_ktp_material.gl_account_desc,
                vw_ktp_material.cost_center,
                vw_ktp_material.cost_center_name");
            $this->datatables->from("($main_query) as vw_ktp_material");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'],@$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $query = $this->db->get();

            if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_material_by_plant($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_material_by_plant.*');
        $this->db->from("vw_ktp_material_by_plant");

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_material_by_plant.WERKS', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_material_by_plant.WERKS', $param['IN_plant']);
        if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
            $this->db->where('vw_ktp_material_by_plant.MATNR', $param['kode_barang']);
        if (isset($param['is_active']) && $param['is_active'] !== NULL)
            $this->db->where('vw_ktp_material_by_plant.is_active', $param['is_active']);

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("WERKS,
                MATNR,
                MANDT,
                LGORT,
                MAKTX,
                MEINS,
                MATKL,
                MTART,
                LABST,
                GROES,
                LVORM,
                is_active,
                classification,
                asset_class,
                asset_class_desc,
                gl_account,
                gl_account_desc,
                cost_center,
                cost_center_name");
            $this->datatables->from("($main_query) as vw_ktp_material");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'],@$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $query = $this->db->get();

            if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_mapping_material($param = NULL)
	{
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

		$this->db->select("tbl_ktp_material.*");
		$this->db->from("tbl_ktp_material");
		// $this->db->where('tbl_ktp_material.na', 'n');
		// $this->db->where('tbl_ktp_material.del', 'n');

		if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
			$this->db->where('tbl_ktp_material.id', $param['kode_barang']);

		$query = $this->db->get();

		if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL) {
			$result = $query->row();
		} else
			$result = $query->result();

		if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
			$result = $this->general->generate_encrypt_json($result, $param['encrypt']);

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
	}

    function get_data_asset_class($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();
            
            $this->db->distinct();
            $this->db->select('ANLKL as id,
            TXK20,
            TXK50');
        $this->db->from('tbl_item_asset_class');

        // if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no"))
        //     $this->db->where('na', 'n');
        if (isset($param['classification']) && $param['classification'] !== NULL)
            $this->db->where('SPRAS', $param['classification']);
        if (isset($param['code']) && $param['code'] !== NULL)
            $this->db->where('ANLKL', $param['code']);
        if (isset($param['search']) && $param['search'] !== NULL) {
            $this->db->group_start();
            $this->db->like('ANLKL', $param['search'], 'both');
            $this->db->or_like('TXK50', $param['search'], 'both');
            $this->db->group_end();
        }

        $query = $this->db->get();

        if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
            $result = $query->row();
        else $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_gl_account($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();
            
        $this->db->distinct();
        $this->db->select('id,
            GLTXT');
        $this->db->from('vw_ktp_gl_account');
        // $this->db->group_by('id', 'GLTXT');

        // if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no"))
        //     $this->db->where('na', 'n');
        if (isset($param['code']) && $param['code'] !== NULL)
            $this->db->where('id', $param['code']);
        if (isset($param['GSBER']) && $param['GSBER'] !== NULL)
            $this->db->where('GSBER', $param['GSBER']);
        if (isset($param['search']) && $param['search'] !== NULL) {
            $this->db->group_start();
            $this->db->like('id', $param['search'], 'both');
            $this->db->or_like('GLTXT', $param['search'], 'both');
            $this->db->group_end();
        }

        $query = $this->db->get();

        if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
            $result = $query->row();
        else $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_cost_center($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_item_cost_center.KOSTL as id');
        $this->db->select('tbl_item_cost_center.*');
        $this->db->from('tbl_item_cost_center');
        $this->db->where_in('GSBER', ['AAP1', 'AAP2', 'PKP1', 'PKP2', 'KGK1']);

        if ((isset($param['matnr']) && $param['matnr'] !== NULL) || (isset($param['kdmat']) && $param['kdmat'] !== NULL)) {
            if (isset($param['matnr']) && $param['matnr'] !== NULL && $param['matnr'] !== "")
                $this->db->join("(
                                    SELECT splitdata
                                    FROM tbl_ktp_material
                                    CROSS APPLY fnSplitString(cost_center, ';')
                                    WHERE id = '" . $param['matnr'] . "'
                                ) list", 'tbl_item_cost_center.KOSTL IN (list.splitdata)', 'inner');
            if (isset($param['kdmat']) && $param['kdmat'] !== NULL && $param['kdmat'] !== "")
                $this->db->join('(
                                    SELECT splitdata
                                    FROM vw_pi_katalog_bom
                                    CROSS APPLY fnSplitString(cost_center,\';\')
                                    WHERE KDMAT = \'' . $param['kdmat'] . '\'
                                ) list', 'tbl_item_cost_center.KOSTL IN (list.splitdata)', 'inner');
        }

        if (isset($param['search']) && $param['search'] !== NULL) {
            $this->db->group_start();
            $this->db->like('KOSTL', $param['search'], 'both');
            $this->db->or_like('MCTXT', $param['search'], 'both');
            $this->db->group_end();
        }
        if (isset($param['code']) && $param['code'] !== NULL)
            $this->db->where('KOSTL', $param['code']);
        if (isset($param['GSBER']) && $param['GSBER'] !== NULL)
            $this->db->where('GSBER', $param['GSBER']);

        $query = $this->db->get();
        if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
            $result = $query->row();
        else $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_vendor($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        if (isset($param['return']) && $param['return'] == "datatables") {
            $this->db->select('vw_ktp_zdmvendor.*');
            $this->db->from("vw_ktp_zdmvendor");

            if (isset($param['plant']) && $param['plant'] !== NULL)
                $this->db->where('vw_ktp_zdmvendor.EKORG', $param['plant']);
            if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
                $this->db->where_in('vw_ktp_zdmvendor.EKORG', $param['IN_plant']);
            if (isset($param['LIFNR']) && $param['LIFNR'] !== NULL)
                $this->db->where('vw_ktp_zdmvendor.LIFNR', $param['LIFNR']);

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("MANDT,
                LIFNR,
                EKORG,
                NAME1,
                CITY1,
                STRAS,
                PSTLZ,
                TELF1,
                SORTL,
                KALSK,
                KTOKK,
                PKPST,
                status_pkp,
                EKORG1");
            $this->datatables->from("($main_query) as vw_ktp_zdmvendor");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'],@$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $this->db->select('vw_ktp_zdmvendor.*,
            vw_ktp_zdmvendor.LIFNR AS id');
            $this->db->from("vw_ktp_zdmvendor");

            if (isset($param['plant']) && $param['plant'] !== NULL)
                $this->db->where('vw_ktp_zdmvendor.EKORG', $param['plant']);
            if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
                $this->db->where_in('vw_ktp_zdmvendor.EKORG', $param['IN_plant']);
            if (isset($param['LIFNR']) && $param['LIFNR'] !== NULL)
                $this->db->where('vw_ktp_zdmvendor.LIFNR', $param['LIFNR']);
            if (isset($param['search']) && $param['search'] !== NULL) {
                $this->db->group_start();
                $this->db->like('vw_ktp_zdmvendor.LIFNR', $param['search'], 'both');
                $this->db->or_like('vw_ktp_zdmvendor.NAME1', strtoupper($param['search']), 'both');
                $this->db->group_end();
            }

            $query = $this->db->get();

            if (isset($param['LIFNR']) && $param['LIFNR'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_io($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_list_io.*');
        $this->db->from("vw_ktp_list_io");

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_list_io.GSBER', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_list_io.GSBER', $param['IN_plant']);
        if (isset($param['AUFNR']) && $param['AUFNR'] !== NULL)
            $this->db->where('vw_ktp_list_io.AUFNR', $param['AUFNR']);
        if (isset($param['status']) && $param['status'] !== NULL)
            $this->db->where('vw_ktp_list_io.status', $param['status']);

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("MANDT,
                AUFNR,
                GSBER,
                KTEXT,
                PHAS3,
                status");
            $this->datatables->from("($main_query) as vw_ktp_list_io");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'],@$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            if (isset($param['search']) && $param['search'] !== NULL) {
                $this->db->group_start();
                $this->db->like('vw_ktp_list_io.AUFNR', $param['search'], 'both');
                $this->db->or_like('vw_ktp_list_io.KTEXT', strtoupper($param['search']), 'both');
                $this->db->group_end();
            }
            
            $query = $this->db->get();

            if (isset($param['AUFNR']) && $param['AUFNR'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
}