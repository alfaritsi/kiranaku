<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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

class Dreport extends CI_Model
{
    function get_data_report_ppb($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_report_ppb.*');
        $this->db->from("vw_ktp_report_ppb");

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_report_ppb.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_report_ppb.plant', $param['IN_plant']);
        if (isset($param['id_ppb']) && $param['id_ppb'] !== NULL)
            $this->db->where('vw_ktp_report_ppb.id', $param['id_ppb']);
        if (isset($param['no_ppb']) && $param['no_ppb'] !== NULL)
            $this->db->where('vw_ktp_report_ppb.no_ppb', $param['no_ppb']);
        if (isset($param['tanggal_awal']) && $param['tanggal_awal'] !== NULL)
            $this->db->where('vw_ktp_report_ppb.tanggal_ppb >=', $param['tanggal_awal']);
        if (isset($param['tanggal_akhir']) && $param['tanggal_akhir'] !== NULL)
            $this->db->where('vw_ktp_report_ppb.tanggal_ppb <=', $param['tanggal_akhir']);

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id_ppb,
                no_ppb,    
                plant,
                tanggal_ppb,
                tanggal_ppb_format,
                kode_barang,
                nama_barang,
                satuan,
                jumlah,
                tipe_po,
                no_po,
                no_gr,
                no_gr_sap");
            $this->datatables->from("($main_query) as vw_ktp_report_ppb");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $query = $this->db->get();

            if ((isset($param['id_ppb']) && $param['id_ppb'] !== NULL) || (isset($param['no_ppb']) && $param['no_ppb'] !== NULL)) {
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

    function get_data_report_gi($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_gi_detail.*');
        $this->db->from("vw_ktp_gi_detail");

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_gi_detail.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_gi_detail.plant', $param['IN_plant']);
        if (isset($param['id_gi']) && $param['id_gi'] !== NULL)
            $this->db->where('vw_ktp_gi_detail.id_gi', $param['id_gi']);
        if (isset($param['no_gi']) && $param['no_ppb'] !== NULL)
            $this->db->where('vw_ktp_gi_detail.no_gi', $param['no_gi']);
        if (isset($param['IN_year']) && $param['IN_year'] !== NULL)
            $this->db->where_in('YEAR(vw_ktp_gi_detail.tanggal)', $param['IN_year']);
        if (isset($param['tanggal_awal']) && $param['tanggal_awal'] !== NULL)
            $this->db->where('vw_ktp_gi_detail.tanggal >=', $param['tanggal_awal']);
        if (isset($param['tanggal_akhir']) && $param['tanggal_akhir'] !== NULL)
            $this->db->where('vw_ktp_gi_detail.tanggal <=', $param['tanggal_akhir']);

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id,
                no_gi,
                plant,
                tanggal,
                tanggal_format,
                kode_barang,
                nama_barang,
                gl_account,
                gl_account_desc,
                cost_center,
                cost_center_desc,
                no_io,
                satuan,
                jumlah,
                no_gi_sap");
            $this->datatables->from("($main_query) as vw_ktp_gi_detail");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $query = $this->db->get();

            if ((isset($param['id_ppb']) && $param['id_ppb'] !== NULL) || (isset($param['no_ppb']) && $param['no_ppb'] !== NULL)) {
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

    function get_data_report_rekap($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//
        $where_tanggal = "";
        if (isset($param['tanggal_awal']) && $param['tanggal_awal'] !== NULL)
            $where_tanggal .= " AND tanggal >= '" . $param['tanggal_awal'] . "'";
        if (isset($param['tanggal_akhir']) && $param['tanggal_akhir'] !== NULL)
            $where_tanggal .= " AND tanggal <= '" . $param['tanggal_akhir'] . "'";

        $this->db->select('vw_ktp_material_by_plant.*, ISNULL(data_gr.jumlah,0) AS jumlah_gr, ISNULL(data_gi.jumlah,0) AS jumlah_gi');
        $this->db->from("vw_ktp_material_by_plant");
        $this->db->join(
            "(
                SELECT plant, kode_barang, SUM(jumlah) AS jumlah 
                FROM tbl_ktp_gr_detail
                INNER JOIN tbl_ktp_gr_header ON tbl_ktp_gr_header.id = tbl_ktp_gr_detail.id_gr
                WHERE tbl_ktp_gr_header.na = 'n' AND tbl_ktp_gr_header.del = 'n' $where_tanggal
                GROUP BY plant, kode_barang
            ) data_gr",
            "data_gr.plant = (vw_ktp_material_by_plant.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)
            AND data_gr.kode_barang = (vw_ktp_material_by_plant.MATNR COLLATE SQL_Latin1_General_CP1_CI_AS)",
            "left"
        );
        $this->db->join(
            "(
                SELECT plant, kode_barang, SUM(jumlah) AS jumlah 
                FROM tbl_ktp_gi_detail
                INNER JOIN tbl_ktp_gi_header ON tbl_ktp_gi_header.id = tbl_ktp_gi_detail.id_gi
                WHERE tbl_ktp_gi_header.na = 'n' AND tbl_ktp_gi_header.del = 'n' $where_tanggal
                GROUP BY plant, kode_barang
            ) data_gi",
            "data_gi.plant = (vw_ktp_material_by_plant.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)
            AND data_gi.kode_barang = (vw_ktp_material_by_plant.MATNR COLLATE SQL_Latin1_General_CP1_CI_AS)",
            "left"
        );

        // $this->db->get();
        // echo json_encode($this->db->error());exit();

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
                cost_center_name,
                jumlah_gr,
                jumlah_gi");
            $this->datatables->from("($main_query) as vw_ktp_material");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

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

    function get_data_report_sap($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_data_to_sap.*');
        $this->db->from("vw_ktp_data_to_sap");

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_data_to_sap.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_data_to_sap.plant', $param['IN_plant']);
        if (isset($param['IN_jenis']) && $param['IN_jenis'] !== NULL)
            $this->db->where_in('vw_ktp_data_to_sap.jenis', $param['IN_jenis']);
        if (isset($param['IN_tipe_po']) && $param['IN_tipe_po'] !== NULL)
            $this->db->where_in('vw_ktp_data_to_sap.tipe_po', $param['IN_tipe_po']);
        if (isset($param['no_transaksi']) && $param['no_transaksi'] !== NULL)
            $this->db->where('vw_ktp_data_to_sap.no_transaksi', $param['no_transaksi']);
        if (isset($param['IN_year']) && $param['IN_year'] !== NULL)
            $this->db->where_in('YEAR(vw_ktp_data_to_sap.tanggal)', $param['IN_year']);

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("jenis,
                no_transaksi,
                plant,
                tanggal,
                tanggal_format,
                tipe_po,
                done_kirim_sap,
                status_sap,
                keterangan_sap,
                id_reference_sap,
                nomor_sap");
            $this->datatables->from("($main_query) as vw_ktp_data_to_sap");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {

            $query = $this->db->get();

            $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_report_po($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select("
            id_ppb,
            no_ppb,
            plant,
            tanggal_upload,
            tanggal_upload_format,
            tanggal_konfirmasi,
            tanggal_konfirmasi_format,
            kode_barang,
            nama_barang,
            satuan,
            jumlah,
            tipe_po,
            no_po,
            tanggal_kirim_sap,
            tanggal_kirim_sap_format,
            tanggal_gr_format 
        ");
        $this->db->from("vw_ktp_report_po");

        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('plant', $param['IN_plant']);
        if (isset($param['tipe_po']) && $param['tipe_po'] !== NULL)
            $this->db->where('tipe_po', $param['tipe_po']);
        if (isset($param['tanggal_awal']) && $param['tanggal_awal'] !== NULL)
            $this->db->where('tanggal_upload >=', $param['tanggal_awal']);
        if (isset($param['tanggal_akhir']) && $param['tanggal_akhir'] !== NULL)
            $this->db->where('tanggal_upload <=', $param['tanggal_akhir']);

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("
                id_ppb,
                no_ppb,
                plant,
                tanggal_upload,
                tanggal_upload_format,
                tanggal_konfirmasi,
                tanggal_konfirmasi_format,
                kode_barang,
                nama_barang,
                satuan,
                jumlah,
                tipe_po,
                no_po,
                tanggal_kirim_sap,
                tanggal_kirim_sap_format,
                tanggal_gr_format 
            ");
            $this->datatables->from("($main_query) as vw_ktp_report_po");
            $result = $this->datatables->generate();
            if (isset($param['encrypt']) && $param['encrypt'] !== NULL) {
                $raw = json_decode($result, true);

                $kolom = array();
                $kolom = array_merge(
                    $kolom,
                    array_map(function ($val) {
                        return array(
                            "tipe" => "encrypt",
                            "nama" => $val,
                        );
                    }, $param['encrypt'])
                );

                $raw['data'] = $this->general->generate_json(
                    array(
                        "data" => $raw['data'],
                        "kolom" => $kolom,
                        "exclude" => $this->general->emptyconvert(@$param['exclude'])
                    )
                );

                if (empty($raw['data']))
                    $raw['data'] = array();

                $result = json_encode($raw);
            }
        } else {
            $query = $this->db->get();
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

            $result = $this->general->generate_json(
                array(
                    "data" => $result,
                    "kolom" => $kolom,
                    "exclude" => $this->general->emptyconvert(@$param['exclude'])
                )
            );
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
}
