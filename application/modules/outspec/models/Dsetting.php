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

class Dsetting extends CI_Model
{
   function get_data_user($param = NULL)
   {
      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->connectDbPortal();

      //=======================================================================//

      $this->db->select('tbl_outspec_user.*');
      $this->db->select('tbl_karyawan.nama, tbl_karyawan.gsber');
      $this->db->from('tbl_outspec_user');
      $this->db->join('tbl_karyawan', 'tbl_outspec_user.nik = tbl_karyawan.nik', 'inner');
      $this->db->where('tbl_outspec_user.del', 'n');

      if (isset($param['id_user']) && $param['id_user'] !== NULL)
         $this->db->where('tbl_outspec_user.id_user', $param['id_user']);
      if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no")) {
         $this->db->where('tbl_outspec_user.na', 'n');
      }

      if (isset($param['return']) && $param['return'] == "datatables") {

         $main_query = $this->db->get_compiled_select();
         $this->db->reset_query();

         $this->datatables->select("
            id_user, 
            nik,
            nama,
            gsber,
            plant,
            na");
         $this->datatables->from("($main_query) as vw_outspec_user");
         $result = $this->datatables->generate();
         $raw = json_decode($result, true);

         if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

         $result = $this->general->jsonify($raw);
      } else {

         $query = $this->db->get();

         if ((isset($param['id_user']) && $param['id_user'] !== NULL)) {
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

   function get_data_karyawan($param = NULL)
   {
      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->connectDbPortal();

      $this->db->select('tbl_karyawan.nik as id');
      $this->db->select('tbl_karyawan.*');
      $this->db->from('tbl_karyawan');

      if (isset($param['nik']) && $param['nik'] !== NULL)
         $this->db->where('tbl_karyawan.nik', $param['nik']);
      if (isset($param['nama']) && $param['nama'] !== NULL)
         $this->db->like('tbl_karyawan.nama', $param['nama'], 'both');
      if (isset($param['search']) && $param['search'] !== NULL) {
         $this->db->like('tbl_karyawan.nik', $param['search'], 'both');
         $this->db->or_like('tbl_karyawan.nama', $param['search'], 'both');
      }

      $this->db->where('tbl_karyawan.na', 'n');
      $this->db->where('tbl_karyawan.del', 'n');

      $query = $this->db->get();
      if (isset($param['nik']) && $param['nik'] !== NULL)
         $result = $query->row();
      else
         $result = $query->result();

      $kolom = array();
      if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
         $kolom = array_merge(
            $kolom,
            array_map(function ($val) {
               return array(
                  "tipe" => "encrypt",
                  "nama" => $val,
               );
            }, $param['encrypt'])
         );

      if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
         $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->closeDb();

      return $result;
   }
}
