<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Outspec Confirmation
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmaster extends CI_Model
{
    function get_data_parameter($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('*');
        $this->db->from('tbl_outspec_parameter');
        $this->db->where('del', 'n');

        if (isset($param['id_parameter']) && $param['id_parameter'] !== NULL)
            $this->db->where('tbl_outspec_parameter.id', $param['id_parameter']);
        if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no")) {
            $this->db->where('na', 'n');
        }

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("
                id,
                nama,
                satuan,
                urutan,
                na");
            $this->datatables->from("($main_query) as vw_outspec_parameter");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            if (isset($param['cek_duplikat']) && $param['cek_duplikat'] !== NULL) {
                $this->db->group_start();
                    if (isset($param['cek_nama_parameter']) && $param['cek_nama_parameter'] !== NULL) 
                        $this->db->or_where('tbl_outspec_parameter.nama', $param['cek_nama_parameter']);
                    if (isset($param['LIKE_nama_parameter']) && $param['LIKE_nama_parameter'] !== NULL)
                        $this->db->or_like('tbl_outspec_parameter.nama', $param['LIKE_nama_parameter'], 'both');
                $this->db->group_end();

                if (isset($param['NOT_IN_id_parameter']) && $param['NOT_IN_id_parameter'] !== NULL) {
                    $this->db->where_not_in('tbl_outspec_parameter.id', $param['NOT_IN_id_parameter']);
                }
            }

            $query = $this->db->get();

            if ((isset($param['id_parameter']) && $param['id_parameter'] !== NULL)) {
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

    function get_data_layout($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('*');
        $this->db->from('tbl_outspec_layout');
        $this->db->where('del', 'n');

        if (isset($param['id_layout']) && $param['id_layout'] !== NULL)
            $this->db->where('tbl_outspec_layout.id_layout', $param['id_layout']);
        if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no")) {
            $this->db->where('na', 'n');
        }

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("
                id_layout,
                nama,
                files,
                urutan,
                jumlah_bales,
                na");
            $this->datatables->from("($main_query) as vw_outspec_layout");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {

            $query = $this->db->get();

            if ((isset($param['id_layout']) && $param['id_layout'] !== NULL)) {
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

    function get_data_pallet($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('*');
        $this->db->from('tbl_outspec_pallet');
        $this->db->where('del', 'n');

        if (isset($param['id_pallet']) && $param['id_pallet'] !== NULL)
            $this->db->where('tbl_outspec_pallet.id_pallet', $param['id_pallet']);
        if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no")) {
            $this->db->where('na', 'n');
        }

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("
                id_pallet,
                berat,
                jumlah_layer,
                layer_pertama,
                show_option,
                na");
            $this->datatables->from("($main_query) as vw_outspec_pallet");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {

            $query = $this->db->get();

            if ((isset($param['id_pallet']) && $param['id_pallet'] !== NULL)) {
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
