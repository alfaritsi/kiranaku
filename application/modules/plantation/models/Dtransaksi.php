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

class Dtransaksi extends CI_Model
{
    function get_ppb_header($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_ppb_header.*');
        $this->db->from("vw_ktp_ppb_header");

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_ppb_header.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_ppb_header.plant', $param['IN_plant']);
        if (isset($param['id_ppb']) && $param['id_ppb'] !== NULL)
            $this->db->where('vw_ktp_ppb_header.id', $param['id_ppb']);
        if (isset($param['no_ppb']) && $param['no_ppb'] !== NULL)
            $this->db->where('vw_ktp_ppb_header.no_ppb', $param['no_ppb']);
        if (isset($param['tanggal_awal']) && $param['tanggal_awal'] !== NULL)
            $this->db->where('vw_ktp_ppb_header.tanggal >=', $param['tanggal_awal']);
        if (isset($param['tanggal_akhir']) && $param['tanggal_akhir'] !== NULL)
            $this->db->where('vw_ktp_ppb_header.tanggal <=', $param['tanggal_akhir']);
        if (isset($param['IN_status_konfirmasi']) && $param['IN_status_konfirmasi'] !== NULL)
            $this->db->where_in('vw_ktp_ppb_header.status_konfirmasi', $param['IN_status_konfirmasi']);
        if (isset($param['IN_status_po_ho']) && $param['IN_status_po_ho'] !== NULL)
            $this->db->where_in('vw_ktp_ppb_header.status_po_ho', $param['IN_status_po_ho']);
        if (isset($param['IN_status_po_site']) && $param['IN_status_po_site'] !== NULL)
            $this->db->where_in('vw_ktp_ppb_header.status_po_site', $param['IN_status_po_site']);
        if (isset($param['NOT_IN_status_ppb']) && $param['NOT_IN_status_ppb'] !== NULL)
            $this->db->where_not_in('vw_ktp_ppb_header.status_ppb', $param['NOT_IN_status_ppb']);
        if (isset($param['IN_status_ppb']) && $param['IN_status_ppb'] !== NULL)
            $this->db->where_in('vw_ktp_ppb_header.status_ppb', $param['IN_status_ppb']);
        if (isset($param['is_closed']) && $param['is_closed'] !== NULL) {
            if ($param['is_closed']) {
                $this->db->group_start();
                    $this->db->where('vw_ktp_ppb_header.is_closed', $param['is_closed']);
                    $this->db->where('vw_ktp_ppb_header.jumlah_hari_berjalan >', 30);
                $this->db->group_end();
            } else {
                $this->db->group_start();
                    $this->db->where('vw_ktp_ppb_header.is_closed', $param['is_closed']);
                    $this->db->where('vw_ktp_ppb_header.jumlah_hari_berjalan <=', 30);
                $this->db->group_end();
            }
        }

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id,
                plant,
                no_ppb,
                perihal,
                tanggal,
                tanggal_format,
                tanggal_diperlukan,
                tanggal_diperlukan_format,
                login_buat,
                tanggal_buat,
                tanggal_buat_format,
                login_edit,
                tanggal_edit,
                is_closed,
                tanggal_konfirmasi,
                tanggal_konfirmasi_format,
                jumlah_detail,
                jumlah_konfirmasi,
                jumlah_po_ho,
                jumlah_po_ho_complete,
                status_po_ho,
                jumlah_po_site,
                jumlah_po_site_complete,
                status_po_site,
                jumlah_hari_berjalan,
                status_ppb");
            $this->datatables->from("($main_query) as vw_ktp_ppb_header");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'],@$param['exclude']);

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

    function get_ppb_detail($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_ktp_ppb_header.plant,
            tbl_ktp_ppb_header.no_ppb,
            tbl_ktp_ppb_detail.id,
            tbl_ktp_ppb_detail.id_ppb,
            tbl_ktp_ppb_detail.no_detail,
            tbl_ktp_ppb_detail.kode_barang,
            vw_ktp_material_by_plant.MAKTX as nama_barang,
            vw_ktp_material_by_plant.GROES as deskripsi2,
            tbl_ktp_ppb_detail.satuan,
            tbl_ktp_ppb_detail.jumlah,
            tbl_ktp_ppb_detail.jumlah_disetujui,
            CONVERT(DECIMAL(18,2), tbl_ktp_ppb_detail.harga) as harga,
            tbl_ktp_ppb_detail.keterangan,
            tbl_ktp_ppb_detail.tipe_po,
            tbl_ktp_ppb_detail.status,
            tbl_ktp_ppb_detail.stok,
            tbl_ktp_ppb_detail.classification,
            vw_ktp_material_by_plant.classification AS classification_master,
            vw_ktp_material_by_plant.asset_class,
            vw_ktp_material_by_plant.asset_class_desc,
            vw_ktp_material_by_plant.gl_account,
            vw_ktp_material_by_plant.gl_account_desc,
            vw_ktp_material_by_plant.cost_center,
            vw_ktp_material_by_plant.cost_center_name,
            ISNULL(tb_po.jumlah,0) as jumlah_po');
        $this->db->from('tbl_ktp_ppb_detail');
        $this->db->join('tbl_ktp_ppb_header', 'tbl_ktp_ppb_header.id = tbl_ktp_ppb_detail.id_ppb', 'inner');
        $this->db->join('vw_ktp_material_by_plant', 'tbl_ktp_ppb_detail.kode_barang = (vw_ktp_material_by_plant.MATNR COLLATE SQL_Latin1_General_CP1_CI_AS) 
            AND tbl_ktp_ppb_header.plant = (vw_ktp_material_by_plant.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)', 
            'left');
        $this->db->join("(SELECT id_ppb, no_detail_ppb, SUM(jumlah) AS jumlah 
            FROM tbl_ktp_po_detail
            WHERE na = 'n' AND del = 'n'
            GROUP BY id_ppb, no_detail_ppb) tb_po", 'tbl_ktp_ppb_detail.id_ppb = tb_po.id_ppb AND tbl_ktp_ppb_detail.no_detail = tb_po.no_detail_ppb', 'left');

        if (isset($param['id_ppb']) && $param['id_ppb'] !== NULL)
            $this->db->where('tbl_ktp_ppb_detail.id_ppb', $param['id_ppb']);
        if (isset($param['no_ppb']) && $param['no_ppb'] !== NULL)
            $this->db->where('tbl_ktp_ppb_header.no_ppb', $param['no_ppb']);
        if (isset($param['id_ppb_detail']) && $param['id_ppb_detail'] !== NULL)
            $this->db->where('tbl_ktp_ppb_detail.id', $param['id_ppb_detail']);
        if (isset($param['no_detail']) && $param['no_detail'] !== NULL)
            $this->db->where('tbl_ktp_ppb_detail.no_detail', $param['no_detail']);
        if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
            $this->db->where('tbl_ktp_ppb_detail.kode_barang', $param['kode_barang']);
        if (isset($param['tipe_po']) && $param['tipe_po'] !== NULL)
            $this->db->where('tbl_ktp_ppb_detail.tipe_po', $param['tipe_po']);

        $this->db->where('tbl_ktp_ppb_detail.na', 'n');
        $this->db->where('tbl_ktp_ppb_detail.del', 'n');
        $this->db->order_by('tbl_ktp_ppb_detail.id_ppb');
        $this->db->order_by('tbl_ktp_ppb_detail.no_detail');

        $query = $this->db->get();

        if (
            isset($param['id_ppb']) && $param['id_ppb'] !== NULL
            // && isset($param['id_ppb_detail']) && $param['id_ppb_detail'] !== NULL
            && isset($param['no_detail']) && $param['no_detail'] !== NULL
        ) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_po_header($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_po_header.*');
        $this->db->from("vw_ktp_po_header");

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_po_header.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_po_header.plant', $param['IN_plant']);
        if (isset($param['id_po']) && $param['id_po'] !== NULL)
            $this->db->where('vw_ktp_po_header.id', $param['id_po']);
        if (isset($param['no_po']) && $param['no_po'] !== NULL)
            $this->db->where('vw_ktp_po_header.no_po', $param['no_po']);
        if (isset($param['no_ppb']) && $param['no_ppb'] !== NULL)
            $this->db->where('vw_ktp_po_header.no_ppb', $param['no_ppb']);
        if (isset($param['tanggal_awal']) && $param['tanggal_awal'] !== NULL)
            $this->db->where('vw_ktp_po_header.tanggal >=', $param['tanggal_awal']);
        if (isset($param['tanggal_akhir']) && $param['tanggal_akhir'] !== NULL)
            $this->db->where('vw_ktp_po_header.tanggal <=', $param['tanggal_akhir']);
        if (isset($param['NOT_IN_tipe']) && $param['NOT_IN_tipe'] !== NULL)
            $this->db->where_not_in('vw_ktp_po_header.tipe_po', $param['NOT_IN_tipe']);
        if (isset($param['IN_tipe']) && $param['IN_tipe'] !== NULL)
            $this->db->where_in('vw_ktp_po_header.tipe_po', $param['IN_tipe']);
        if (isset($param['IN_status_sap']) && $param['IN_status_sap'] !== NULL)
            $this->db->where_in('vw_ktp_po_header.status_sap', $param['IN_status_sap']);
        if (isset($param['status_sap']) && $param['status_sap'] !== NULL) {
            if ($param['status_sap'] == 'completed') {
                $this->db->where('vw_ktp_po_header.done_kirim_sap', true);
                $this->db->where('vw_ktp_po_header.status_sap', 'success');
            } else if ($param['status_sap'] == 'not_completed') {
                $this->db->group_start();
                    $this->db->where('vw_ktp_po_header.done_kirim_sap', false);
                    $this->db->or_group_start();
                        $this->db->where('vw_ktp_po_header.done_kirim_sap', true);
                        $this->db->where('vw_ktp_po_header.status_sap', 'fail');
                    $this->db->group_end();
                $this->db->group_end();
            }
        }
        if (isset($param['no_sap']) && $param['no_sap'] !== NULL) {
            if ($param['no_sap'] == 'completed') {
                $this->db->where('vw_ktp_po_header.done_kirim_sap', true);
                $this->db->where('vw_ktp_po_header.no_po IS NOT NULL');
            } else if ($param['no_sap'] == 'not_completed') {
                $this->db->where('vw_ktp_po_header.done_kirim_sap', true);
                $this->db->where('vw_ktp_po_header.id_reference_sap IS NOT NULL');
                $this->db->where('vw_ktp_po_header.no_po', NULL);
            }
        }

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id,
                plant,
                id_ppb,
                no_ppb,
                no_po,
                tipe_po,
                vendor,
                nama_vendor,
                tanggal,
                tanggal_format,
                done_kirim_sap,
                tanggal_kirim_sap,
                tanggal_kirim_sap_format,
                status_sap,
                keterangan_sap,
                list_ppb");
            $this->datatables->from("($main_query) as vw_ktp_po_header");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'],@$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $query = $this->db->get();

            if (
                (isset($param['id_po']) && $param['id_po'] !== NULL) 
                // || (isset($param['no_po']) && $param['no_po'] !== NULL)
            ) {
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

    function get_po_detail($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('vw_ktp_po_detail.*');
        $this->db->from('vw_ktp_po_detail');
        $this->db->join('vw_ktp_po_header', 'vw_ktp_po_detail.id_po = vw_ktp_po_header.id', 'inner');

        if (isset($param['id_po']) && $param['id_po'] !== NULL)
            $this->db->where('vw_ktp_po_detail.id_po', $param['id_po']);
        if (isset($param['no_po']) && $param['no_po'] !== NULL)
            $this->db->where('vw_ktp_po_detail.no_po', $param['no_po']);
        if (isset($param['id_ppb']) && $param['id_ppb'] !== NULL)
            $this->db->where('vw_ktp_po_header.id_ppb', $param['id_ppb']);
        if (isset($param['no_detail']) && $param['no_detail'] !== NULL)
            $this->db->where('vw_ktp_po_detail.no_detail', $param['no_detail']);
        if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
            $this->db->where('vw_ktp_po_detail.kode_barang', $param['kode_barang']);
        if (isset($param['tipe_po']) && $param['tipe_po'] !== NULL)
            $this->db->where('vw_ktp_po_header.tipe_po', $param['tipe_po']);
        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_po_header.plant', $param['plant']);

        // $this->db->where('vw_ktp_po_detail.na', 'n');
        // $this->db->where('vw_ktp_po_detail.del', 'n');
        $this->db->order_by('vw_ktp_po_detail.id_po');
        $this->db->order_by('vw_ktp_po_detail.no_detail');

        $query = $this->db->get();

        if (
            isset($param['id_po']) && $param['id_po'] !== NULL &&
            isset($param['no_detail']) && $param['no_detail'] !== NULL
        ) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_po_detail_by_no_po($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('vw_ktp_po_detail_by_no_po.*');
        $this->db->from('vw_ktp_po_detail_by_no_po');
        // $this->db->join('vw_ktp_po_header', 'vw_ktp_po_detail_by_no_po.id_po = vw_ktp_po_header.id', 'inner');

        if (isset($param['no_po']) && $param['no_po'] !== NULL)
            $this->db->where('vw_ktp_po_detail_by_no_po.po_reff', $param['no_po']);
        if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
            $this->db->where('vw_ktp_po_detail_by_no_po.kode_barang', $param['kode_barang']);
        if (isset($param['tipe_po']) && $param['tipe_po'] !== NULL)
            $this->db->where('vw_ktp_po_detail_by_no_po.tipe_po', $param['tipe_po']);
        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_po_detail_by_no_po.plant', $param['plant']);

        $query = $this->db->get();

        // echo json_encode($this->db->error());
        // exit();

        if (
            isset($param['id_po']) && $param['id_po'] !== NULL &&
            isset($param['no_detail']) && $param['no_detail'] !== NULL
        ) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_po_detail_by_line_item($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('*');
        $this->db->from('vw_ktp_po_detail_by_line_item');
        

        if (isset($param['id_po']) && $param['id_po'] !== NULL)
            $this->db->where('vw_ktp_po_detail_by_line_item.id_po', $param['id_po']);
        if (isset($param['no_po']) && $param['no_po'] !== NULL)
            $this->db->where('vw_ktp_po_detail_by_line_item.po_reff', $param['no_po']);
        if (isset($param['no_detail']) && $param['no_detail'] !== NULL)
            $this->db->where('vw_ktp_po_detail_by_line_item.no_detail', $param['no_detail']);
        if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
            $this->db->where('vw_ktp_po_detail_by_line_item.kode_barang', $param['kode_barang']);
        if (isset($param['tipe_po']) && $param['tipe_po'] !== NULL)
            $this->db->where('vw_ktp_po_detail_by_line_item.tipe_po', $param['tipe_po']);
        if (isset($param['sisa']) && $param['sisa'] !== NULL)
            $this->db->where('(vw_ktp_po_detail_by_line_item.jumlah - vw_ktp_po_detail_by_line_item.jumlah_gr) >', 0);

        // $this->db->where('vw_ktp_po_detail_by_line_item.na', 'n');
        // $this->db->where('vw_ktp_po_detail_by_line_item.del', 'n');
        $this->db->order_by('vw_ktp_po_detail_by_line_item.po_reff');
        $this->db->order_by('vw_ktp_po_detail_by_line_item.kode_barang');
        $this->db->order_by('vw_ktp_po_detail_by_line_item.item_po');

        $query = $this->db->get();

        // echo json_encode($this->db->error());
        // exit();

        if (
            isset($param['id_gr']) && $param['id_gr'] !== NULL &&
            isset($param['no_detail']) && $param['no_detail'] !== NULL
        ) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_gr_header($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_gr_header.*');
        $this->db->from("vw_ktp_gr_header");

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_gr_header.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_gr_header.plant', $param['IN_plant']);
        if (isset($param['id_gr']) && $param['id_gr'] !== NULL)
            $this->db->where('vw_ktp_gr_header.id', $param['id_gr']);
        if (isset($param['no_gr']) && $param['no_gr'] !== NULL)
            $this->db->where('vw_ktp_gr_header.no_gr', $param['no_gr']);
        if (isset($param['no_po']) && $param['no_gr'] !== NULL)
            $this->db->where('vw_ktp_gr_header.no_gr', $param['no_gr']);
        if (isset($param['id_po']) && $param['id_po'] !== NULL)
            $this->db->where('vw_ktp_gr_header.id_po', $param['id_po']);
        if (isset($param['no_po']) && $param['no_po'] !== NULL)
            $this->db->where('vw_ktp_gr_header.no_po', $param['no_po']);
        // if (isset($param['id_ppb']) && $param['id_ppb'] !== NULL)
        //     $this->db->where('vw_ktp_gr_header.id_ppb', $param['id_ppb']);
        // if (isset($param['no_ppb']) && $param['no_ppb'] !== NULL)
        //     $this->db->where('vw_ktp_gr_header.no_ppb', $param['no_ppb']);
        if (isset($param['tanggal_awal']) && $param['tanggal_awal'] !== NULL)
            $this->db->where('vw_ktp_gr_header.tanggal >=', $param['tanggal_awal']);
        if (isset($param['tanggal_akhir']) && $param['tanggal_akhir'] !== NULL)
            $this->db->where('vw_ktp_gr_header.tanggal <=', $param['tanggal_akhir']);
        if (isset($param['NOT_IN_tipe']) && $param['NOT_IN_tipe'] !== NULL)
            $this->db->where_not_in('vw_ktp_gr_header.tipe_po', $param['NOT_IN_tipe']);
        if (isset($param['IN_tipe']) && $param['IN_tipe'] !== NULL)
            $this->db->where_in('vw_ktp_gr_header.tipe_po', $param['IN_tipe']);
        if (isset($param['IN_status_sap']) && $param['IN_status_sap'] !== NULL)
            $this->db->where_in('vw_ktp_gr_header.status_sap', $param['IN_status_sap']);
        if (isset($param['status_sap']) && $param['status_sap'] !== NULL) {
            if ($param['status_sap'] == 'completed') {
                $this->db->where('vw_ktp_gr_header.done_kirim_sap', true);
                $this->db->where('vw_ktp_gr_header.status_sap', 'success');
            } else if ($param['status_sap'] == 'not_completed') {
                $this->db->group_start();
                $this->db->where('vw_ktp_gr_header.done_kirim_sap', false);
                    $this->db->or_group_start();
                        $this->db->where('vw_ktp_gr_header.done_kirim_sap', true);
                        $this->db->where('vw_ktp_gr_header.status_sap', 'fail');
                    $this->db->group_end();
                $this->db->group_end();
            }
        }
        if (isset($param['no_sap']) && $param['no_sap'] !== NULL) {
            if ($param['no_sap'] == 'completed') {
                $this->db->where('vw_ktp_gr_header.done_kirim_sap', true);
                $this->db->where('vw_ktp_gr_header.no_gr_sap IS NOT NULL');
            } else if ($param['no_sap'] == 'not_completed') {
                $this->db->where('vw_ktp_gr_header.done_kirim_sap', true);
                $this->db->where('vw_ktp_gr_header.id_reference_sap IS NOT NULL');
                $this->db->where('vw_ktp_gr_header.no_gr_sap', NULL);
            }
        }

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id,
                no_gr,
                plant,
                id_po,
                no_po,
                tipe_po,
                vendor,
                nama_vendor,
                tanggal,
                tanggal_format,
                tanggal_po,
                tanggal_po_format,
                done_kirim_sap,
                tanggal_kirim_sap,
                tanggal_kirim_sap_format,
                status_sap,
                keterangan_sap,
                no_gr_sap,
                id_reference_sap");
            $this->datatables->from("($main_query) as vw_ktp_gr_header");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'],@$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $query = $this->db->get();

            if ((isset($param['id_gr']) && $param['id_gr'] !== NULL) || (isset($param['no_gr']) && $param['no_gr'] !== NULL)) {
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

    function get_gr_detail($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('vw_ktp_gr_detail.*');
        $this->db->from('vw_ktp_gr_detail');

        if (isset($param['id_gr']) && $param['id_gr'] !== NULL)
            $this->db->where('vw_ktp_gr_detail.id_gr', $param['id_gr']);
        if (isset($param['no_po']) && $param['no_po'] !== NULL)
            $this->db->where('vw_ktp_gr_detail.no_po', $param['no_po']);
        if (isset($param['id_po']) && $param['id_po'] !== NULL)
            $this->db->where('vw_ktp_gr_detail.id_po', $param['id_po']);
        if (isset($param['no_detail']) && $param['no_detail'] !== NULL)
            $this->db->where('vw_ktp_gr_detail.no_detail', $param['no_detail']);
        if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
            $this->db->where('vw_ktp_gr_detail.kode_barang', $param['kode_barang']);
        if (isset($param['tipe_po']) && $param['tipe_po'] !== NULL)
            $this->db->where('vw_ktp_gr_detail.tipe_po', $param['tipe_po']);

        // $this->db->where('tbl_ktp_gr_detail.na', 'n');
        // $this->db->where('tbl_ktp_gr_detail.del', 'n');
        $this->db->order_by('vw_ktp_gr_detail.id_gr');
        $this->db->order_by('vw_ktp_gr_detail.no_detail');

        $query = $this->db->get();

        // echo json_encode($this->db->error());
        // exit();

        if (
            isset($param['id_gr']) && $param['id_gr'] !== NULL &&
            isset($param['no_detail']) && $param['no_detail'] !== NULL
        ) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_gr_detail_sum($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('vw_ktp_gr_detail_sum.*');
        $this->db->from('vw_ktp_gr_detail_sum');

        if (isset($param['id_gr']) && $param['id_gr'] !== NULL)
            $this->db->where('vw_ktp_gr_detail_sum.id_gr', $param['id_gr']);
        if (isset($param['no_po']) && $param['no_po'] !== NULL)
            $this->db->where('vw_ktp_gr_detail_sum.no_po', $param['no_po']);
        if (isset($param['id_po']) && $param['id_po'] !== NULL)
            $this->db->where('vw_ktp_gr_detail_sum.id_po', $param['id_po']);
        if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
            $this->db->where('vw_ktp_gr_detail_sum.kode_barang', $param['kode_barang']);
        if (isset($param['tipe_po']) && $param['tipe_po'] !== NULL)
            $this->db->where('vw_ktp_gr_detail.tipe_po', $param['tipe_po']);

        $this->db->order_by('vw_ktp_gr_detail_sum.id_gr');
        $this->db->order_by('vw_ktp_gr_detail_sum.kode_barang');

        $query = $this->db->get();

        // echo json_encode($this->db->error());
        // exit();

        $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_gr_detail_ho($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('vw_ktp_gr_header.plant,
            vw_ktp_gr_header.no_gr,
            tbl_ktp_gr_detail.id_gr,
            vw_ktp_gr_header.id_po,
            vw_ktp_gr_header.no_po,
            vw_ktp_gr_header.po_reff,
            vw_ktp_gr_header.tanggal,
            vw_ktp_gr_header.tanggal_format,
            vw_ktp_gr_header.no_gr_sap,
            tbl_ktp_gr_detail.no_detail,
            tbl_ktp_gr_detail.kode_barang,
            vw_ktp_material_by_plant.MAKTX AS nama_barang,
            tbl_ktp_gr_detail.satuan,
            vw_ktp_po_detail_by_no_po.jumlah AS jumlah_po,
            tbl_ktp_gr_detail.jumlah,
            vw_ktp_po_detail_by_no_po.classification,
            tbl_ktp_gr_detail.sloc,
            tbl_ktp_gr_detail.keterangan,
            vw_ktp_po_detail_by_no_po.asset_class,
            vw_ktp_po_detail_by_no_po.gl_account,
            vw_ktp_po_detail_by_no_po.cost_center');
        $this->db->from('tbl_ktp_gr_detail');
        $this->db->join('vw_ktp_gr_header', 'vw_ktp_gr_header.id = tbl_ktp_gr_detail.id_gr', 'inner');
        $this->db->join('vw_ktp_material_by_plant', 'tbl_ktp_gr_detail.kode_barang = (vw_ktp_material_by_plant.MATNR COLLATE SQL_Latin1_General_CP1_CI_AS) 
            AND vw_ktp_gr_header.plant = (vw_ktp_material_by_plant.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)', 
            'left');
        $this->db->join('vw_ktp_po_detail_by_no_po', 'vw_ktp_gr_header.po_reff = vw_ktp_po_detail_by_no_po.po_reff
        AND tbl_ktp_gr_detail.kode_barang = vw_ktp_po_detail_by_no_po.kode_barang', 'left');

        if (isset($param['id_gr']) && $param['id_gr'] !== NULL)
            $this->db->where('tbl_ktp_gr_detail.id_gr', $param['id_gr']);
        if (isset($param['no_po']) && $param['no_po'] !== NULL)
            $this->db->where('vw_ktp_gr_header.po_reff', $param['no_po']);
        if (isset($param['no_detail']) && $param['no_detail'] !== NULL)
            $this->db->where('tbl_ktp_gr_detail.no_detail', $param['no_detail']);
        if (isset($param['kode_barang']) && $param['kode_barang'] !== NULL)
            $this->db->where('tbl_ktp_gr_detail.kode_barang', $param['kode_barang']);
        if (isset($param['tipe_po']) && $param['tipe_po'] !== NULL)
            $this->db->where('tbl_ktp_gr_header.tipe_po', $param['tipe_po']);

        $this->db->where('tbl_ktp_gr_detail.na', 'n');
        $this->db->where('tbl_ktp_gr_detail.del', 'n');
        $this->db->order_by('vw_ktp_gr_detail.id_gr');
        $this->db->order_by('vw_ktp_gr_detail.no_detail');

        $query = $this->db->get();

        // echo json_encode($this->db->error());
        // exit();

        if (
            isset($param['id_gr']) && $param['id_gr'] !== NULL &&
            isset($param['no_detail']) && $param['no_detail'] !== NULL
        ) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_gi_header($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_ktp_gi_header.*');
        $this->db->from("vw_ktp_gi_header");

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_gi_header.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_gi_header.plant', $param['IN_plant']);
        if (isset($param['id_gi']) && $param['id_gi'] !== NULL)
            $this->db->where('vw_ktp_gi_header.id', $param['id_gi']);
        if (isset($param['no_gi']) && $param['no_gi'] !== NULL)
            $this->db->where('vw_ktp_gi_header.no_gi', $param['no_gi']);
        if (isset($param['tanggal_awal']) && $param['tanggal_awal'] !== NULL)
            $this->db->where('vw_ktp_gi_header.tanggal >=', $param['tanggal_awal']);
        if (isset($param['tanggal_akhir']) && $param['tanggal_akhir'] !== NULL)
            $this->db->where('vw_ktp_gi_header.tanggal <=', $param['tanggal_akhir']);
        if (isset($param['IN_status_sap']) && $param['IN_status_sap'] !== NULL)
            $this->db->where_in('vw_ktp_gi_header.status_sap', $param['IN_status_sap']);
        if (isset($param['status_sap']) && $param['status_sap'] !== NULL) {
            if ($param['status_sap'] == 'completed') {
                $this->db->where('vw_ktp_gi_header.done_kirim_sap', true);
                $this->db->where('vw_ktp_gi_header.status_sap', 'success');
            } else if ($param['status_sap'] == 'not_completed') {
                $this->db->group_start();
                $this->db->where('vw_ktp_gi_header.done_kirim_sap', false);
                    $this->db->or_group_start();
                        $this->db->where('vw_ktp_gi_header.done_kirim_sap', true);
                        $this->db->where('vw_ktp_gi_header.status_sap', 'fail');
                    $this->db->group_end();
                $this->db->group_end();
            }
        }
        if (isset($param['no_sap']) && $param['no_sap'] !== NULL) {
            if ($param['no_sap'] == 'completed') {
                $this->db->where('vw_ktp_gi_header.done_kirim_sap', true);
                $this->db->where('vw_ktp_gi_header.no_gi_sap IS NOT NULL');
            } else if ($param['no_sap'] == 'not_completed') {
                $this->db->where('vw_ktp_gi_header.done_kirim_sap', true);
                $this->db->where('vw_ktp_gi_header.id_reference_sap IS NOT NULL');
                $this->db->where('vw_ktp_gi_header.no_gi_sap', NULL);
            }
        }

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id,
                no_gi,
                plant,
                tanggal,
                tanggal_format,
                done_kirim_sap,
                tanggal_kirim_sap,
                tanggal_kirim_sap_format,
                status_sap,
                keterangan_sap,
                id_reference_sap,
                no_gi_sap");
            $this->datatables->from("($main_query) as vw_ktp_gi_header");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'],@$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $query = $this->db->get();

            if ((isset($param['id_gi']) && $param['id_gi'] !== NULL) || (isset($param['no_gi']) && $param['no_gi'] !== NULL)) {
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

    function get_gi_detail($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('vw_ktp_gi_detail.*');
        $this->db->from('vw_ktp_gi_detail');

        if (isset($param['id_gi']) && $param['id_gi'] !== NULL)
            $this->db->where('vw_ktp_gi_detail.id_gi', $param['id_gi']);
        if (isset($param['no_gi']) && $param['no_gi'] !== NULL)
            $this->db->where('vw_ktp_gi_detail.no_gi', $param['no_gi']);

        $this->db->order_by('vw_ktp_gi_detail.id_gi');
        $this->db->order_by('vw_ktp_gi_detail.no_detail');

        $query = $this->db->get();

        // echo json_encode($this->db->error());
        // exit();

        if (
            isset($param['id_gi']) && $param['id_gi'] !== NULL &&
            isset($param['no_detail']) && $param['no_detail'] !== NULL
        ) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    // function get_data_material_spec($param = NULL)
    // {
    //     if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
    //         $this->general->connectDbPortal();

    //     $this->db->select('*');
    //     $this->db->from('vw_material_spec_byplant');

    //     if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no"))
    //         $this->db->where('na', 'n');
    //     if (isset($param['req']) && $param['req'] !== NULL)
    //         $this->db->where('req', $param['req']);
    //     if (isset($param['plant']) && $param['plant'] !== NULL)
    //         $this->db->where('plant', $param['plant']);
    //     if (isset($param['classification']) && $param['classification'] !== NULL)
    //         $this->db->where('classification', $param['classification']);
    //     if (isset($param['search']) && $param['search'] !== NULL) {
    //         $this->db->like('full_description', $param['search'], 'both');
    //     }

    //     $query = $this->db->get();

    //     if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
    //         $result = $query->row();
    //     else $result = $query->result();

    //     if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
    //         $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

    //     if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
    //         $this->general->closeDb();

    //     return $result;
    // }

    function get_queue_data_to_sap($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('vw_ktp_queue_data_to_sap.*');
        $this->db->from('vw_ktp_queue_data_to_sap');

        if (isset($param['id_transaksi']) && $param['id_transaksi'] !== NULL)
            $this->db->where('vw_ktp_queue_data_to_sap.id_transaksi', $param['id_transaksi']);
        if (isset($param['tipe']) && $param['tipe'] !== NULL)
            $this->db->where('vw_ktp_queue_data_to_sap.tipe', $param['tipe']);
        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_ktp_queue_data_to_sap.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_ktp_queue_data_to_sap.plant', $param['IN_plant']);

        $this->db->order_by('vw_ktp_queue_data_to_sap.tanggal');
        $this->db->order_by('vw_ktp_queue_data_to_sap.tanggal_buat');
        // $this->db->order_by('vw_ktp_queue_data_to_sap.urut');

        $query = $this->db->get();

        // echo json_encode($this->db->error());
        // exit();

        if (
            isset($param['id_transaksi']) && $param['id_transaksi'] !== NULL
        ) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
}