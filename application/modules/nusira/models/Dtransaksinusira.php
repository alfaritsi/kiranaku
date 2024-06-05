<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : Nusira Workshop
@author         : Akhmad Syaiful Yamang (8347)
@date           : 02-Jun-20
@contributor    :
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

class Dtransaksinusira extends CI_Model
{
    function get_pi_header($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        if (isset($param['return']) && $param['return'] == "datatables") {
            $this->db->select(" approve,
                                assign,
                                decline,
                                drops,
                                approve_ho,
                                assign_ho,
                                decline_ho,
                                drops_ho,
                                approve_capex,
                                assign_capex,
                                decline_capex,
                                drops_capex,
                                approve_capex_ho,
                                assign_capex_ho,
                                decline_capex_ho,
                                drops_capex_ho,
                                app_lim_val_pabrik_money,
                                app_lim_val_ho_money,
                                app_lim_val_capex_money,
                                app_lim_val_capex_ho_money,
                                kode_role,
                                app,
                                kategori_pi,
                                jenis_pi,
                                level,
                                if_approve_pabrik,
                                if_assign_pabrik,
                                if_decline_pabrik,
                                if_drop_pabrik,
                                is_app_lim_pabrik,
                                app_lim_val_pabrik,
                                attach_file_pabrik,
                                if_approve_ho,
                                if_assign_ho,
                                if_decline_ho,
                                if_drop_ho,
                                is_app_lim_ho,
                                app_lim_val_ho,
                                attach_file_ho,
                                if_approve_capex,
                                if_assign_capex,
                                if_decline_capex,
                                if_drop_capex,
                                is_app_lim_capex,
                                app_lim_val_capex,
                                attach_file_capex,
                                if_approve_capex_ho,
                                if_assign_capex_ho,
                                if_decline_capex_ho,
                                if_drop_capex_ho,
                                is_app_lim_capex_ho,
                                app_lim_val_capex_ho,
                                attach_file_capex_ho,
                                plant,
                                no_pi,
                                app_pi_header,
                                kepada,
                                tujuan_inv,
                                tanggal,
                                tanggal_format,
                                perihal,
                                pic_pemb,
                                pic_proj,
                                quest1,
                                quest2,
                                quest3,
                                quest4,
                                quest5,
                                quest6,
                                tipe_pi,
                                id_file,
                                id_file_div_head,
                                id_file_dept_head,
                                id_file_fincon,
                                div_head_vendor,
                                status,
                                tgl_submit_adm_proc,
                                adm_proc,
                                tgl_app_mnjr_kantor,
                                mnjr_kantor,
                                tgl_app_dirops,
                                dirops,
                                tgl_app_ceo_reg,
                                ceo_reg,
                                tgl_app_div_head,
                                div_head,
                                tgl_app_dept_head,
                                dept_head,
                                tgl_app_proc_ho,
                                proc_ho,
                                tgl_app_coo,
                                coo,
                                tgl_review_fincon,
                                fincon,
                                tgl_app_md,
                                md,
                                tgl_app_cfo,
                                cfo,
                                tgl_app_ceo_group,
                                ceo_group,
                                login_buat,
                                tanggal_buat,
                                login_edit,
                                tanggal_edit,
                                na,
                                del,
                                dept_head_check,
                                nsw_check,
                                no_po,
                                no_so,
                                id_divisi,
                                id_file_div_head_ho,
                                id_file_dept_head_ho,
                                note_pi,
                                filename,
                                tujuan_investasi,
                                status_pi,
                                status_pi_delete,
                                akses_delete,
                                jml_belum_rekom,
                                jml_sdh_rekom,
                                sum_detail,
                                current_total_rekom_vendor,
                                capex_value,
                                capex_active,
                                new_method");
            $this->db->select('CASE vw_pi_header.status
                                        WHEN \'drop\' THEN \'<span class="label label-danger">DROP</span>\'
                                        WHEN \'deleted\' THEN \'<span class="label label-danger">DELETED</span>\'
                                        WHEN \'finish\' THEN \'<span class="label label-success">FINISH</span>\'
                                        ELSE \'<span class="label label-warning">ON PROGRESS</span>\'
                                END as view_status');
            $this->db->from("vw_pi_header");

            if (isset($param['app']) && $param['app'] !== NULL)
                $this->db->where('vw_pi_header.app_pi_header', $param['app']);
            if (isset($param['new_method']) && $param['new_method'] !== NULL)
                $this->db->where('vw_pi_header.new_method', $param['new_method']);
            if (isset($param['plant']) && $param['plant'] !== NULL)
                $this->db->where('vw_pi_header.plant', $param['plant']);
            if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
                $this->db->where_in('vw_pi_header.plant', $param['IN_plant']);
            if (isset($param['no_pi']) && $param['no_pi'] !== NULL)
                $this->db->where('vw_pi_header.no_pi', $param['no_pi']);
            if (isset($param['IN_year']) && $param['IN_year'] !== NULL)
                $this->db->where_in('YEAR(vw_pi_header.tanggal)', $param['IN_year']);
            if (isset($param['IN_jenis']) && $param['IN_jenis'] !== NULL)
                $this->db->where_in('vw_pi_header.jenis_pi', $param['IN_jenis']);
            if (isset($param['NOT_IN_status']) && $param['NOT_IN_status'] !== NULL)
                $this->db->where_not_in('vw_pi_header.status', $param['NOT_IN_status']);
            if (isset($param['IN_status']) && $param['IN_status'] !== NULL)
                $this->db->where_in('vw_pi_header.status', $param['IN_status']);
            if (isset($param['NOT_IN_status_pi']) && $param['NOT_IN_status_pi'] !== NULL)
                $this->db->where_not_in('vw_pi_header.status_pi', $param['NOT_IN_status_pi']);
            if (isset($param['IN_status_pi']) && $param['IN_status_pi'] !== NULL)
                $this->db->where_in('vw_pi_header.status_pi', $param['IN_status_pi']);
            if (isset($param['IN_nsw_check']) && $param['IN_nsw_check'] !== NULL)
                $this->db->where_in('vw_pi_header.nsw_check', $param['IN_nsw_check']);
            if (isset($param['NULL_no_po']) && $param['NULL_no_po'] !== NULL) {
                $this->db->group_start();
                $this->db->where('vw_pi_header.no_po IS NULL');
                if (isset($param['ORNULL_no_so']) && $param['ORNULL_no_so'] !== NULL)
                    $this->db->or_where('vw_pi_header.no_so IS NULL');
                $this->db->group_end();
            }
            if (isset($param['NULL_no_so']) && $param['NULL_no_so'] !== NULL) {
                $this->db->group_start();
                $this->db->where('vw_pi_header.no_so IS NULL');
                if (isset($param['ORNULL_no_po']) && $param['ORNULL_no_po'] !== NULL)
                    $this->db->or_where('vw_pi_header.no_po IS NULL');
                $this->db->group_end();
            }

            $main_query = $this->db->get_compiled_select();
            // echo "<pre>" . $main_query;
            // exit();
            $this->db->reset_query();

            $this->datatables->select(" approve,
                                        assign,
                                        decline,
                                        drops,
                                        approve_ho,
                                        assign_ho,
                                        decline_ho,
                                        drops_ho,
                                        approve_capex,
                                        assign_capex,
                                        decline_capex,
                                        drops_capex,
                                        approve_capex_ho,
                                        assign_capex_ho,
                                        decline_capex_ho,
                                        drops_capex_ho,
                                        app_lim_val_pabrik_money,
                                        app_lim_val_ho_money,
                                        app_lim_val_capex_money,
                                        app_lim_val_capex_ho_money,
                                        kode_role,
                                        app,
                                        kategori_pi,
                                        jenis_pi,
                                        level,
                                        if_approve_pabrik,
                                        if_assign_pabrik,
                                        if_decline_pabrik,
                                        if_drop_pabrik,
                                        is_app_lim_pabrik,
                                        app_lim_val_pabrik,
                                        attach_file_pabrik,
                                        if_approve_ho,
                                        if_assign_ho,
                                        if_decline_ho,
                                        if_drop_ho,
                                        is_app_lim_ho,
                                        app_lim_val_ho,
                                        attach_file_ho,
                                        if_approve_capex,
                                        if_assign_capex,
                                        if_decline_capex,
                                        if_drop_capex,
                                        is_app_lim_capex,
                                        app_lim_val_capex,
                                        attach_file_capex,
                                        if_approve_capex_ho,
                                        if_assign_capex_ho,
                                        if_decline_capex_ho,
                                        if_drop_capex_ho,
                                        is_app_lim_capex_ho,
                                        app_lim_val_capex_ho,
                                        attach_file_capex_ho,
                                        plant,
                                        no_pi,
                                        app_pi_header,
                                        kepada,
                                        tujuan_inv,
                                        tanggal,
                                        tanggal_format,
                                        perihal,
                                        pic_pemb,
                                        pic_proj,
                                        quest1,
                                        quest2,
                                        quest3,
                                        quest4,
                                        quest5,
                                        quest6,
                                        tipe_pi,
                                        id_file,
                                        id_file_div_head,
                                        id_file_dept_head,
                                        id_file_fincon,
                                        div_head_vendor,
                                        status,
                                        tgl_submit_adm_proc,
                                        adm_proc,
                                        tgl_app_mnjr_kantor,
                                        mnjr_kantor,
                                        tgl_app_dirops,
                                        dirops,
                                        tgl_app_ceo_reg,
                                        ceo_reg,
                                        tgl_app_div_head,
                                        div_head,
                                        tgl_app_dept_head,
                                        dept_head,
                                        tgl_app_proc_ho,
                                        proc_ho,
                                        tgl_app_coo,
                                        coo,
                                        tgl_review_fincon,
                                        fincon,
                                        tgl_app_md,
                                        md,
                                        tgl_app_cfo,
                                        cfo,
                                        tgl_app_ceo_group,
                                        ceo_group,
                                        login_buat,
                                        tanggal_buat,
                                        login_edit,
                                        tanggal_edit,
                                        na,
                                        del,
                                        dept_head_check,
                                        nsw_check,
                                        no_po,
                                        no_so,
                                        id_divisi,
                                        id_file_div_head_ho,
                                        id_file_dept_head_ho,
                                        note_pi,
                                        filename,
                                        tujuan_investasi,
                                        status_pi,
                                        status_pi_delete,
                                        akses_delete,
                                        jml_belum_rekom,
                                        jml_sdh_rekom,
                                        sum_detail,
                                        current_total_rekom_vendor,
                                        capex_value,
                                        capex_active,
                                        new_method,
                                        view_status");
            $this->datatables->from("($main_query) as vw_pi_header");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt']);

            $result = $this->general->jsonify($raw);
        } else {
            $this->db->select("*");
            $this->db->select('CASE vw_pi_header.status
                    WHEN \'drop\' THEN \'<span class="label label-danger">DROP</span>\'
                    WHEN \'deleted\' THEN \'<span class="label label-danger">DELETED</span>\'
                    WHEN \'finish\' THEN \'<span class="label label-success">FINISH</span>\'
                    ELSE \'<span class="label label-warning">ON PROGRESS</span>\'
            END as view_status');
            $this->db->from("vw_pi_header");

            if (isset($param['app']) && $param['app'] !== NULL)
                $this->db->where('vw_pi_header.app_pi_header', $param['app']);
            if (isset($param['new_method']) && $param['new_method'] !== NULL)
                $this->db->where('vw_pi_header.new_method', $param['new_method']);
            if (isset($param['plant']) && $param['plant'] !== NULL)
                $this->db->where('vw_pi_header.plant', $param['plant']);
            if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
                $this->db->where_in('vw_pi_header.plant', $param['IN_plant']);
            if (isset($param['no_pi']) && $param['no_pi'] !== NULL)
                $this->db->where('vw_pi_header.no_pi', $param['no_pi']);
            if (isset($param['IN_year']) && $param['IN_year'] !== NULL)
                $this->db->where('YEAR(vw_pi_header.tanggal)', $param['IN_year']);
            if (isset($param['IN_jenis']) && $param['IN_jenis'] !== NULL)
                $this->db->where_in('vw_pi_header.jenis_pi', $param['IN_jenis']);
            if (isset($param['NOT_IN_status']) && $param['NOT_IN_status'] !== NULL)
                $this->db->where_not_in('vw_pi_header.status', $param['NOT_IN_status']);
            if (isset($param['IN_status']) && $param['IN_status'] !== NULL)
                $this->db->where_in('vw_pi_header.status', $param['IN_status']);
            if (isset($param['NOT_IN_status_pi']) && $param['NOT_IN_status_pi'] !== NULL)
                $this->db->where_not_in('vw_pi_header.status_pi', $param['NOT_IN_status_pi']);
            if (isset($param['IN_status_pi']) && $param['IN_status_pi'] !== NULL)
                $this->db->where_in('vw_pi_header.status_pi', $param['IN_status_pi']);
            if (isset($param['NULL_no_po']) && $param['NULL_no_po'] !== NULL) {
                $this->db->group_start();
                $this->db->where('vw_pi_header.no_po IS NULL');
                $this->db->or_where('vw_pi_header.no_po = \'\'');
                if (isset($param['ORNULL_no_so']) && $param['ORNULL_no_so'] !== NULL) {
                    $this->db->or_where('vw_pi_header.no_so IS NULL');
                    $this->db->or_where('vw_pi_header.no_so = \'\'');
                }
                $this->db->group_end();
            }
            if (isset($param['NULL_no_so']) && $param['NULL_no_so'] !== NULL) {
                $this->db->group_start();
                $this->db->where('vw_pi_header.no_so IS NULL');
                $this->db->or_where('vw_pi_header.no_so = \'\'');
                if (isset($param['ORNULL_no_po']) && $param['ORNULL_no_po'] !== NULL) {
                    $this->db->or_where('vw_pi_header.no_po IS NULL');
                    $this->db->or_where('vw_pi_header.no_po = \'\'');
                }
                $this->db->group_end();
            }

            $query = $this->db->get();

            if (isset($param['no_pi']) && $param['no_pi'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt']);
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_pi_detail($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('
            CONVERT(VARCHAR, tbl_pi_detail.req_deliv_date, 104) as req_deliv_date_format,
            tbl_pi_detail.no_pi + \' - \' + CAST(tbl_pi_detail.[no] AS VARCHAR(100)) as ID,
            tbl_pi_detail.plant,
            tbl_pi_detail.no_pi,
            tbl_pi_detail.no,
            tbl_pi_detail.itnum,
            tbl_pi_detail.matnr,
            tbl_pi_detail.kdmat,
            tbl_pi_detail.tipe_pi,
            tbl_item_cost_center.KTEXT,
            tbl_pi_detail.perm_invest,
            tbl_pi_detail.spesifikasi,
            tbl_pi_detail.jumlah,
            tbl_pi_detail.satuan,
            CONVERT(DECIMAL(18,2), tbl_pi_detail.harga) as harga,
            CONVERT(DECIMAL(18,2), tbl_pi_detail.total) as total,
            tbl_pi_detail.ntgew,
            tbl_pi_detail.status_nsw,
            tbl_pi_detail.req_deliv_date,
            tbl_pi_detail.nsw_durasi_mgg,
            tbl_pi_detail.nsw_entry_date,
            tbl_pi_detail.nsw_reason,
            tbl_pi_detail.acc_assign,
            tbl_pi_detail.asset_class,
            tbl_pi_detail.asset_desc,
            tbl_pi_detail.cost_center,
            tbl_pi_detail.gl_account,
            tbl_pi_detail.login_buat,
            tbl_pi_detail.tanggal_buat,
            tbl_pi_detail.login_edit,
            tbl_pi_detail.tanggal_edit,
            tbl_pi_detail.status,
            tbl_pi_detail.na,
            tbl_pi_detail.del,
            tbl_pi_detail.is_done_recom,
            tbl_pi_rekom_vendor_detail.no_po,
            tbl_pi_rekom_vendor_detail.no_so,
            tbl_pi_rekom_vendor_header.vendor_rekomendasi as nama_vendor,
            tbl_pi_header.tujuan_inv,
            vw_pi_katalog_bom.MAKTX
        ');
        $this->db->from('tbl_pi_detail');
        $this->db->join('tbl_pi_header', 'tbl_pi_header.no_pi = tbl_pi_detail.no_pi', 'inner');
        $this->db->join('vw_pi_katalog_bom', 'vw_pi_katalog_bom.MATNR = tbl_pi_detail.matnr', 'left');
        $this->db->join('tbl_item_cost_center', 'tbl_item_cost_center.KOSTL = tbl_pi_detail.cost_center', 'left');
        $this->db->join('tbl_pi_rekom_vendor_header', 'tbl_pi_rekom_vendor_header.no_pi = tbl_pi_detail.no_pi', 'left');
        $this->db->join(
            'tbl_pi_rekom_vendor_content',
            'tbl_pi_rekom_vendor_content.no_pi = tbl_pi_rekom_vendor_header.no_pi
            AND tbl_pi_rekom_vendor_content.no_rekom = tbl_pi_rekom_vendor_header.no_rekom
            AND tbl_pi_rekom_vendor_content.nama_vendor = CONVERT(VARCHAR, tbl_pi_rekom_vendor_header.vendor_rekomendasi)
            AND tbl_pi_rekom_vendor_content.is_selected = 1',
            'left'
        );
        $this->db->join(
            'tbl_pi_rekom_vendor_detail',
            'tbl_pi_rekom_vendor_detail.no_pi = tbl_pi_rekom_vendor_content.no_pi
            AND tbl_pi_rekom_vendor_detail.no_rekom = tbl_pi_rekom_vendor_content.no_rekom
            AND tbl_pi_rekom_vendor_detail.urut_vendor = tbl_pi_rekom_vendor_content.urut_vendor
            AND tbl_pi_rekom_vendor_detail.no_detail_pi = tbl_pi_detail.no',
            'left'
        );

        if (isset($param['app']) && $param['app'] !== NULL)
            $this->db->where('tbl_pi_header.app', $param['app']);
        if (isset($param['new_method']) && $param['new_method'] !== NULL)
            $this->db->where('tbl_pi_header.new_method', $param['new_method']);
        if (isset($param['IN_status']) && $param['IN_status'] !== NULL)
            $this->db->where_in('tbl_pi_header.status', $param['IN_status']);
        if (isset($param['IN_year']) && $param['IN_year'] !== NULL)
            $this->db->where('YEAR(tbl_pi_header.tanggal)', $param['IN_year']);
        if (isset($param['no_pi']) && $param['no_pi'] !== NULL)
            $this->db->where('tbl_pi_detail.no_pi', $param['no_pi']);
        if (isset($param['no']) && $param['no'] !== NULL)
            $this->db->where('tbl_pi_detail.no', $param['no']);
        if (isset($param['matnr']) && $param['matnr'] !== NULL)
            $this->db->where('tbl_pi_detail.matnr', $param['matnr']);
        if (isset($param['NULL_no_po']) && $param['NULL_no_po'] !== NULL) {
            $this->db->group_start();
            $this->db->where('tbl_pi_rekom_vendor_detail.no_po IS NULL');
            if (isset($param['ORNULL_no_so']) && $param['ORNULL_no_so'] !== NULL)
                $this->db->or_where('tbl_pi_rekom_vendor_detail.no_so IS NULL');
            $this->db->group_end();
        }
        if (isset($param['NULL_no_so']) && $param['NULL_no_so'] !== NULL) {
            $this->db->group_start();
            $this->db->where('tbl_pi_rekom_vendor_detail.no_so IS NULL');
            if (isset($param['ORNULL_no_po']) && $param['ORNULL_no_po'] !== NULL)
                $this->db->or_where('tbl_pi_rekom_vendor_detail.no_po IS NULL');
            $this->db->group_end();
        }
        if (!isset($param['all'])) {
            $this->db->where('tbl_pi_detail.na', 'n');
            $this->db->where('tbl_pi_detail.del', 'n');
        }

        $this->db->order_by('tbl_pi_detail.tanggal_edit', 'DESC');
        $this->db->order_by('tbl_pi_detail.no');
        $this->db->order_by('tbl_pi_detail.no_pi');

        $query = $this->db->get();

        if (
            isset($param['no_pi']) && $param['no_pi'] !== NULL &&
            (isset($param['no']) && $param['no'] !== NULL ||
                isset($param['matnr']) && $param['matnr'] !== NULL)
        ) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_pi_reason($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_pi_reason.*');
        $this->db->select('CASE
								WHEN tbl_pi_reason.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
        $this->db->from('tbl_pi_reason');

        if (isset($param['id_reason']) && $param['id_reason'] !== NULL)
            $this->db->where('tbl_pi_reason.id_reason', $param['id_reason']);
        if (isset($param['na']) && $param['na'] !== NULL)
            $this->db->where('tbl_pi_reason.na', $param['na']);
        if (isset($param['del']) && $param['del'] !== NULL)
            $this->db->where('tbl_pi_reason.del', $param['del']);

        $this->db->order_by('tbl_pi_reason.reason', 'ASC');
        $query = $this->db->get();
        $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();
        return $result;
    }

    function get_data_spk($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        if (isset($param['return']) && $param['return'] == "datatables") {
            $this->datatables->select("plant,
                                        no_po,
                                        no_so,
                                        no_pi,
                                        no_io,
                                        no_mat,
                                        deskripsi,
                                        prod_qty,
                                        uom,
                                        prod_schedule_start,
                                        prod_schedule_end,
                                        overdue");
            $this->datatables->from("vw_pi_spk");

            if (isset($param['overdue']) && $param['overdue'] !== NULL)
                $this->datatables->where("overdue > 0");
            if (isset($param['jenis']) && $param['jenis'] !== NULL) {
                $this->datatables->group_start();
                foreach ($param['jenis'] as $key => $val) {
                    if ($val == "mto") {
                        $this->datatables->or_where('no_pi IS NOT NULL');
                    }
                    if ($val == "mts") {
                        $this->datatables->or_where('no_pi IS NULL');
                    }
                }
                $this->datatables->group_end();
            }

            $result = $this->datatables->generate();
        } else {
            $this->db->select("plant,
                                no_po,
                                no_so,
                                no_pi,
                                no_io,
                                no_mat,
                                deskripsi,
                                prod_qty,
                                uom,
                                prod_schedule_start,
                                prod_schedule_end,
                                overdue");
            $this->db->from("vw_pi_spk");

            if (isset($param['overdue']) && $param['overdue'] !== NULL)
                $this->db->where("overdue > 0");
            if (isset($param['jenis']) && $param['jenis'] !== NULL) {
                $this->db->group_start();
                foreach ($param['jenis'] as $key => $val) {
                    if ($val == "mto") {
                        $this->db->or_where('no_pi IS NOT NULL');
                    }
                    if ($val == "mts") {
                        $this->db->or_where('no_pi IS NULL');
                    }
                }
                $this->db->group_end();
            }

            $query = $this->db->get();
            $result = $query->result();
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();
        return $result;
    }

    function get_data_cetak($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        if ($param['jenis'] == "mto") {
            $this->db->select('tbl_pi_rfc_bom.MATNR as MATNR_ITEM');
            $this->db->select('tbl_pi_rfc_bom.MAKTX as MAKTX_ITEM');
            $this->db->select('tbl_pi_spk.no_so');
            $this->db->select('tbl_pi_spk.no_po');
            $this->db->select('tbl_pi_spk.no_io');
            $this->db->select('tbl_pi_spk.prod_inhouse');
            $this->db->select('tbl_pi_spk.prod_qty');
            $this->db->select('CONVERT(VARCHAR(10), tbl_pi_spk.prod_schedule_start, 104) as prod_schedule_start');
            $this->db->select('CONVERT(VARCHAR(10), tbl_pi_spk.prod_schedule_end, 104) as prod_schedule_end');
            $this->db->select('tbl_pi_rfc_bom_engineering.*');
            $this->db->from('tbl_pi_spk');
            $this->db->join('tbl_pi_rfc_bom', 'tbl_pi_spk.no_mat = tbl_pi_rfc_bom.MATNR', 'left outer');
            $this->db->join('tbl_pi_rfc_bom_engineering', 'tbl_pi_spk.no_mat = tbl_pi_rfc_bom_engineering.MATNR', 'left outer');

            if (isset($param['no_io']) && $param['no_io'] !== NULL)
                $this->db->where('tbl_pi_spk.no_io', $param['no_io']);

            $this->db->order_by('tbl_pi_rfc_bom_engineering.IDNRK', 'ASC');
            $query = $this->db->get();
            $result = $query->result();
        } else {
            $this->db->select('tbl_pi_rfc_bom.MATNR as MATNR_ITEM');
            $this->db->select('tbl_pi_rfc_bom.MAKTX as MAKTX_ITEM');
            $this->db->select('NULL as no_so', false);
            $this->db->select('NULL as no_po', false);
            $this->db->select('tbl_pi_spk_mts.no_io');
            $this->db->select('tbl_pi_spk_mts.prod_qty');
            $this->db->select('CONVERT(VARCHAR(10), tbl_pi_spk_mts.prod_schedule_start, 104) as prod_schedule_start');
            $this->db->select('CONVERT(VARCHAR(10), tbl_pi_spk_mts.prod_schedule_end, 104) as prod_schedule_end');
            $this->db->select('tbl_pi_rfc_bom_engineering.*');
            $this->db->from('tbl_pi_spk_mts');
            $this->db->join('tbl_pi_rfc_bom', 'tbl_pi_spk_mts.no_mat = tbl_pi_rfc_bom.MATNR', 'left outer');
            $this->db->join('tbl_pi_rfc_bom_engineering', 'tbl_pi_spk_mts.no_mat = tbl_pi_rfc_bom_engineering.MATNR', 'left outer');

            if (isset($param['no_io']) && $param['no_io'] !== NULL)
                $this->db->where('tbl_pi_spk_mts.no_io', $param['no_io']);

            $this->db->order_by('tbl_pi_rfc_bom_engineering.SPOSN', 'ASC');
            $this->db->order_by('tbl_pi_rfc_bom_engineering.IDNRK', 'ASC');
            $query = $this->db->get();
            $result = $query->result();
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();
        return $result;
    }
}
