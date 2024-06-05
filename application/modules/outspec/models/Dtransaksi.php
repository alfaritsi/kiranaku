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

class Dtransaksi extends CI_Model
{
   function get_data_cek($param = NULL)
   {
      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->connectDbPortal();

      //=======================================================================//

      $this->db->select("tbl_outspec_cek.id,
            tbl_outspec_cek.plant,
            tbl_outspec_cek.tanggal,
            CONVERT(VARCHAR, tbl_outspec_cek.tanggal, 104) as tanggal_format,
            tbl_outspec_cek.no_si,
            tbl_outspec_cek.no_produksi,
            tbl_outspec_cek.tahun_produksi,
            tbl_outspec_cek.kondisi_pallet,
            tbl_outspec_cek.catatan,
            tbl_outspec_cek.tipe,
            tbl_outspec_cek.no_so,
            tbl_outspec_cek.id_buyer,
            tbl_outspec_cek.berat_bales,
            tbl_outspec_cek.suhu_bales,
            tbl_outspec_cek.potong_tengah,
            tbl_outspec_cek.sample,
            tbl_outspec_cek.id_pallet,
            tbl_outspec_pallet.berat AS berat_pallet,
            tbl_outspec_cek.files,
            tbl_outspec_cek.size_files,
            tbl_outspec_cek.tipe_files,
            tbl_outspec_cek.laygrp,
            vw_outspec_data_si.NMBYR
        ");
      $this->db->from('tbl_outspec_cek');
      $this->db->join('tbl_outspec_pallet', 'tbl_outspec_pallet.id_pallet = tbl_outspec_cek.id_pallet', 'left');
      $this->db->join(
         'vw_outspec_data_si',
         '(vw_outspec_data_si.VBELN COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_outspec_cek.no_so 
            AND (vw_outspec_data_si.BSTNK COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_outspec_cek.no_si
            AND (vw_outspec_data_si.VKORG COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_outspec_cek.plant',
         'left'
      );

      $this->db->where('tbl_outspec_cek.na', 'n');
      $this->db->where('tbl_outspec_cek.del', 'n');

      if (isset($param['id_cek']) && $param['id_cek'] !== NULL)
         $this->db->where('tbl_outspec_cek.id', $param['id_cek']);
      if (isset($param['plant']) && $param['plant'] !== NULL)
         $this->db->where('tbl_outspec_cek.plant', $param['plant']);
      if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
         $this->db->where_in('tbl_outspec_cek.plant', $param['IN_plant']);
      if (isset($param['tanggal_awal']) && $param['tanggal_awal'] !== NULL)
         $this->db->where('tbl_outspec_cek.tanggal >=', $param['tanggal_awal']);
      if (isset($param['tanggal_akhir']) && $param['tanggal_akhir'] !== NULL)
         $this->db->where('tbl_outspec_cek.tanggal <=', $param['tanggal_akhir']);
      if (isset($param['tipe']) && $param['tipe'] !== NULL)
         $this->db->where('tbl_outspec_cek.tipe', $param['tipe']);
      if (isset($param['tahun_produksi']) && $param['tahun_produksi'] !== NULL)
         $this->db->where('tbl_outspec_cek.tahun_produksi', $param['tahun_produksi']);

      if (isset($param['return']) && $param['return'] == "datatables") {

         $main_query = $this->db->get_compiled_select();
         $this->db->reset_query();

         $this->datatables->select("id,
            plant,
            tanggal,
            tanggal_format,
            no_si,
            no_produksi,
            tahun_produksi,
            kondisi_pallet,
            catatan,
            tipe,
            id_buyer,
            berat_bales,
            suhu_bales,
            potong_tengah,
            sample,
            id_pallet,
            berat_pallet,
            files");
         $this->datatables->from("($main_query) as vw_outspec_cek");
         $result = $this->datatables->generate();
         $raw = json_decode($result, true);

         if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

         $result = $this->general->jsonify($raw);
      } else {

         $query = $this->db->get();

         if ((isset($param['id_cek']) && $param['id_cek'] !== NULL) || (isset($param['single_row']) && $param['single_row'] == TRUE)) {
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

   function get_data_bales($param = NULL)
   {
      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->connectDbPortal();

      $this->db->select("tbl_outspec_cek_bales.*,
            tbl_outspec_parameter.nama AS nama_parameter,
            tbl_outspec_parameter.satuan
        ");
      $this->db->from('tbl_outspec_cek_bales');
      $this->db->join('tbl_outspec_parameter', 'tbl_outspec_parameter.id = tbl_outspec_cek_bales.id_parameter');

      $this->db->where('tbl_outspec_cek_bales.na', 'n');
      $this->db->where('tbl_outspec_cek_bales.del', 'n');
      $this->db->order_by('tbl_outspec_cek_bales.layer_ke');
      $this->db->order_by('tbl_outspec_cek_bales.bales_ke');
      $this->db->order_by('tbl_outspec_cek_bales.id_parameter');
      $this->db->order_by('tbl_outspec_parameter.urutan');

      if (isset($param['id_cek']) && $param['id_cek'] !== NULL)
         $this->db->where('tbl_outspec_cek_bales.id_cek', $param['id_cek']);

      $query = $this->db->get();
      $result = $query->result();

      if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
         $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->closeDb();

      return $result;
   }

   function get_data_label($param = NULL)
   {
      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->connectDbPortal();

      $this->db->select("tbl_outspec_cek_label.*");
      $this->db->from('tbl_outspec_cek_label');

      $this->db->where('tbl_outspec_cek_label.na', 'n');
      $this->db->where('tbl_outspec_cek_label.del', 'n');
      $this->db->order_by('tbl_outspec_cek_label.kode_label');

      if (isset($param['id_cek']) && $param['id_cek'] !== NULL)
         $this->db->where('tbl_outspec_cek_label.id_cek', $param['id_cek']);

      $query = $this->db->get();
      $result = $query->result();

      if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
         $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->closeDb();

      return $result;
   }

   function get_data_layer($param = NULL)
   {
      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->connectDbPortal();

      $this->db->select("tbl_outspec_cek_layer.*,
            tbl_outspec_layout.nama AS nama_layout,
            tbl_outspec_layout.jumlah_bales,
            tbl_outspec_layout.files AS image_layout
        ");
      $this->db->from('tbl_outspec_cek_layer');
      $this->db->join('tbl_outspec_layout', 'tbl_outspec_layout.id_layout = tbl_outspec_cek_layer.id_layout');

      $this->db->where('tbl_outspec_cek_layer.na', 'n');
      $this->db->where('tbl_outspec_cek_layer.del', 'n');
      $this->db->order_by('tbl_outspec_cek_layer.layer_ke');

      if (isset($param['id_cek']) && $param['id_cek'] !== NULL)
         $this->db->where('tbl_outspec_cek_layer.id_cek', $param['id_cek']);

      $query = $this->db->get();
      $result = $query->result();

      if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
         $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->closeDb();

      return $result;
   }

   function get_data_layout_kms($param = NULL)
   {
      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->connectDb('db_kms');

      $this->db->select("FOP_125_MLay.laygrp,
        FOP_125_MLay.laynm,
        FOP_125_MLay.laypath");
      $this->db->from('FOP_125_MLay');

      $this->db->where('FOP_125_MLay.mandt', '100');

      if (isset($param['laygrp']) && $param['laygrp'] !== NULL)
         $this->db->where('FOP_125_MLay.laygrp', $param['laygrp']);

      $query = $this->db->get();

      if (
         (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
      )
         $result = $query->row();
      else
         $result = $query->result();

      if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
         $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

      if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
         $this->general->closeDb();

      return $result;
   }
}
