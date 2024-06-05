<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Asset Management
 * @author          : Lukman Hakim (7143)
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dmaintenance extends CI_Model
{
    function get_kategori($params = array())
    {
        $params = array_merge(array('pengguna' => 'IT', 'all' => false), $params);

        $this->db->select('tbl_inv_kategori.*');
        $this->db->select('CASE
								WHEN tbl_inv_kategori.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
        $this->db->from('tbl_inv_kategori');

        if (isset($params['pengguna'])) {
            $this->db->where('tbl_inv_kategori.pengguna', $params['pengguna']);
        }

        if (isset($params['role']) && $params['role'] !== null) {
            // 1 alat berat && 5 apar
            $flag = $params['role'] == 'PIC ALAT BERAT' ? '1' : '5';
            $this->db->where('tbl_inv_kategori.id_kategori', $flag);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_kategori.del', 'n');
            $this->db->where('tbl_inv_kategori.na', 'n');
        }

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_aset($params = array())
    {
        $params = array_merge(array('pengguna' => 'IT', 'all' => false), $params);

        $this->db->select('tbl_inv_aset.*');

        $this->db->from('tbl_inv_aset');

        if (isset($params['id_aset'])) {
            $this->db->where('tbl_inv_aset.id_aset', $params['id_aset']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_aset.del', 'n');
            $this->db->where('tbl_inv_aset.na', 'n');
        }

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_main($params = array())
    {
        $params = array_merge(array('pengguna' => 'IT', 'all' => false), $params);

        $this->db->select('tbl_inv_main.*');

        $this->db->from('tbl_inv_main');

        if (isset($params['id_main'])) {
            $this->db->where('tbl_inv_main.id_main', $params['id_main']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_main.del', 'n');
            $this->db->where('tbl_inv_main.na', 'n');
        }

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_main_items($params = array())
    {
        $params = array_merge(array('pengguna' => 'IT', 'all' => false), $params);

        $this->db->distinct();
        $this->db->select('tbl_inv_jenis_detail.id_jenis');
        $this->db->select('tbl_inv_jenis_detail.id_jenis_detail');
        $this->db->select('tbl_inv_jenis_detail.nama');
        $this->db->select('tbl_inv_jenis_detail.kolom_aset');

        $this->db->from('tbl_inv_jenis_detail');
        $this->db->join('tbl_inv_main', 'tbl_inv_jenis_detail.id_jenis = tbl_inv_main.id_jenis');

        if (isset($params['id_main'])) {
            $this->db->where('tbl_inv_main.id_main', $params['id_main']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_jenis_detail.del', 'n');
            $this->db->where('tbl_inv_jenis_detail.na', 'n');
        }
        //		$this->db->select('tbl_inv_main_items.nama');
        //
        //		$this->db->from('tbl_inv_main_items');
        //		$this->db->join('tbl_inv_main','tbl_inv_main_items.id_main = tbl_inv_main.id_main','left outer');
        //		$this->db->join('tbl_inv_jenis','tbl_inv_jenis.id_jenis = tbl_inv_main.id_jenis','left outer');
        //
        //        if (isset($params['pengguna'])) {
        //            $this->db->where('tbl_inv_jenis.pengguna', $params['pengguna']);
        //        }
        //
        //        if (isset($params['all']) && !$params['all']) {
        //            $this->db->where('tbl_inv_main_items.del', 'n');
        //            $this->db->where('tbl_inv_main_items.na', 'n');
        //        }

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_jenis($params = array())
    {
        $params = array_merge(array('pengguna' => 'IT', 'all' => false), $params);
        $this->db->select('tbl_inv_jenis.*');
        $this->db->select('CASE
								WHEN tbl_inv_jenis.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
        $this->db->from('tbl_inv_jenis');

        if (isset($params['id_jenis'])) {
            $this->db->where('tbl_inv_jenis.id_jenis', $params['id_jenis']);
        }

        if (isset($params['role']) && $params['role'] !== null) {
            // 1 alat berat && 5 apar
            $flag = $params['role'] == 'PIC ALAT BERAT' ? '1' : '5';
            $this->db->where('tbl_inv_jenis.id_kategori', $flag);
        }

        if (isset($params['active'])) {
            $this->db->where('tbl_inv_jenis.na', $params['active']);
        }

        if (isset($params['id_kategori'])) {
            if (is_array($params['id_kategori']))
                $this->db->where_in('tbl_inv_jenis.id_kategori', $params['id_kategori']);
            else
                $this->db->where('tbl_inv_jenis.id_kategori', $params['id_kategori']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_jenis.del', 'n');
            $this->db->where('tbl_inv_jenis.na', 'n');
        }

        if (isset($params['pengguna'])) {
            $this->db->where('tbl_inv_jenis.pengguna', $params['pengguna']);
        }

        $this->db->order_by("tbl_inv_jenis.nama", "asc");

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_satuan($params = array())
    {
        $params = array_merge(array('pengguna' => 'IT', 'all' => false), $params);

        $this->db->select('tbl_inv_satuan.*');
        $this->db->from('tbl_inv_satuan');
        if (isset($params['id_satuan'])) {
            $this->db->where('tbl_inv_satuan.id_satuan', $params['id_satuan']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_satuan.del', 'n');
            $this->db->where('tbl_inv_satuan.na', 'n');
        }
        $this->db->order_by("nama", "asc");
        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_operator($params = array())
    {
        $params = array_merge(array('pengguna' => 'IT', 'all' => false), $params);

        $this->db->distinct();
        $this->db->select('tbl_karyawan.nama as nama_operator');
        $this->db->from('tbl_inv_main');
        $this->db->join('tbl_karyawan', 'CONVERT(VARCHAR, tbl_karyawan.nik) = tbl_inv_main.operator', 'inner');
        if (base64_decode($this->session->userdata("-ho-")) != 'y') {
            $this->db->where('tbl_karyawan.gsber', base64_decode($this->session->userdata("-gsber-")));
        }
        $this->db->order_by("nama_operator", "asc");

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_status($params = array())
    {
        $params = array_merge(array('all' => false), $params);

        $this->db->select('tbl_inv_status.*');
        $this->db->select('CASE
								WHEN tbl_inv_status.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
        $this->db->from('tbl_inv_status');
        if (isset($params['id_status'])) {
            $this->db->where('tbl_inv_status.id_status', $params['id_status']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_status.del', 'n');
            $this->db->where('tbl_inv_status.na', 'n');
        }
        $this->db->order_by("nama", "asc");
        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_pabrik($params = array())
    {
        $params = array_merge(array('all' => false), $params);

        $this->db->select('tbl_inv_pabrik.*');
        $this->db->select('CASE
								WHEN tbl_inv_pabrik.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
        $this->db->from('tbl_inv_pabrik');
        if (isset($params['id_pabrik'])) {
            $this->db->where('tbl_inv_pabrik.id_pabrik', $params['id_pabrik']);
        }

        if (isset($params['plant_in']) && $params['plant_in'] !== NULL) {
            $this->db->where_in('tbl_inv_pabrik.kode', $params['plant_in']);
        } else {
            if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
                if ((base64_decode($this->session->userdata("-gsber-")) == 'KJP1') or (base64_decode($this->session->userdata("-gsber-")) == 'KJP2')) {
                    $this->db->where("tbl_inv_pabrik.kode in ('KJP1','KJP2')");
                } else {
                    $this->db->where('tbl_inv_pabrik.kode', base64_decode($this->session->userdata("-gsber-")));
                }
            }
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_pabrik.del', 'n');
            $this->db->where('tbl_inv_pabrik.na', 'n');
        }

        $this->db->order_by("nama", "asc");
        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_lokasi($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'it'), $params);

        $this->db->select('tbl_inv_lokasi.*');
        $this->db->select('CASE
								WHEN tbl_inv_lokasi.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
        $this->db->from('tbl_inv_lokasi');
        if (isset($params['id_lokasi'])) {
            $this->db->where('tbl_inv_lokasi.id_lokasi', $params['id_lokasi']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_lokasi.del', 'n');
            $this->db->where('tbl_inv_lokasi.na', 'n');
        }

        if (isset($params['pengguna'])) {
            $this->db->like('tbl_inv_lokasi.pengguna', $params['pengguna']);
        }

        $this->db->order_by("nama", "asc");
        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_pm($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'it', 'active' => true, 'main_status' => array()), $params);

        $this->db->distinct();
        //        $this->datatables->select("v_aset_pm.id_main");
        //        $this->datatables->select("v_aset_pm.id_aset");
        //        $this->datatables->select("v_aset_pm.id_kategori");
        //        $this->datatables->select("v_aset_pm.id_jenis");
        //        $this->datatables->select("v_aset_pm.detail_aset_it");

        $this->db->from("v_aset_pm");

        if (isset($params['id_main'])) {
            $this->db->where('v_aset_pm.id_main', $params['id_main']);
            $params['single_row'] = true;
        }

        if (isset($params['id_aset'])) {
            $this->db->where('v_aset_pm.id_aset', $params['id_aset']);
        }
        if (isset($params['active'])) {
            if ($params['active'] !== NULL)
                $this->db->where('v_aset_pm.na', 'n');
            else
                $this->db->where('v_aset_pm.na', 'y');
        }
        if (isset($params['nama'])) {
            $this->db->group_start();
            $this->db->like('v_aset_pm.nomor', $params['nama']);
            $this->db->or_like('v_aset_pm.nomor_sap', $params['nama']);
            $this->db->or_like('v_aset_pm.kode_barang', $params['nama']);
            $this->db->group_end();
        }

        if (isset($params['pengguna'])) {
            $this->db->where('v_aset_pm.pengguna', $params['pengguna']);
        }

        if (isset($params['role']) && $params['role'] !== NULL) {
            $flag = $params['role'] == 'PIC ALAT BERAT' ? '1' : '5';
            $this->db->where('v_aset_pm.id_kategori', $flag);
        }

        if (isset($params['operator_fo']) && $params['operator_fo'] !== NULL) {
            $this->db->where('v_aset_pm.operator', $params['operator_fo']);
        }

        if (isset($params['jenis_tindakan'])) {
            $this->db->group_start();
            $this->db->where('v_aset_pm.jenis_tindakan', $params['jenis_tindakan']);
            $this->db->or_where('v_aset_pm.jenis_tindakan', null);
            $this->db->group_end();
        }

        if (isset($params['id_jenis'])) {
            if (is_array($params['id_jenis']))
                $this->db->where_in('v_aset_pm.id_jenis', $params['id_jenis']);
            else
                $this->db->where('v_aset_pm.id_jenis', $params['id_jenis']);
        }
        if (isset($params['id_merk'])) {
            if (is_array($params['id_merk']))
                $this->db->where_in('v_aset_pm.id_merk', $params['id_merk']);
            else
                $this->db->where('v_aset_pm.id_merk', $params['id_merk']);
        }
        if (isset($params['id_pabrik'])) {
            if (is_array($params['id_pabrik']))
                $this->db->where_in('v_aset_pm.id_pabrik', $params['id_pabrik']);
            else
                $this->db->where('v_aset_pm.id_pabrik', $params['id_pabrik']);
        }
        if (isset($params['id_lokasi'])) {
            if (is_array($params['id_lokasi']))
                $this->db->where_in('v_aset_pm.id_lokasi', $params['id_lokasi']);
            else
                $this->db->where('v_aset_pm.id_lokasi', $params['id_lokasi']);
        }
        if (isset($params['id_area'])) {
            if (is_array($params['id_area']))
                $this->db->where_in('v_aset_pm.id_area', $params['id_area']);
            else
                $this->db->where('v_aset_pm.id_area', $params['id_area']);
        }
        if (isset($params['main_status'])) {
            if (is_array($params['main_status']))
                $this->db->where_in('v_aset_pm.main_status', $params['main_status']);
            else
                $this->db->where('v_aset_pm.main_status', $params['main_status']);
        }

        if (!isset($params['main_status']) || !in_array('noschedule', $params['main_status'])) {
            if (isset($params['tanggal_awal']))
                $this->db->where('v_aset_pm.jadwal_service >=', $params['tanggal_awal']);
            if (isset($params['tanggal_akhir']))
                $this->db->where('v_aset_pm.jadwal_service <=', $params['tanggal_akhir']);
        }

        if (isset($params['filter_operator'])) {
            if (is_array($params['filter_operator']))
                $this->db->where_in('v_aset_pm.nama_operator', $params['filter_operator']);
            else
                $this->db->where('v_aset_pm.nama_operator', $params['filter_operator']);
        }

        if (isset($params['kode_pabrik'])) {
            $this->db->where_in('v_aset_pm.kode', $params['kode_pabrik']);
        } else {
            if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
                $this->db->where("v_aset_pm.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='" . base64_decode($this->session->userdata("-gsber-")) . "')");
            }
        }

        $this->db->where("v_aset_pm.id_pabrik!=''");

        $main_query = $this->db->get_compiled_select();
        $this->db->reset_query();
        $this->datatables->select("
            id_main,
            id_periode,
            tanggal_mulai,
            tanggal_selesai,
            jadwal_service,
            final,
            pic_approve,
            pic_nik,
            tanggal_approve,
            jenis_tindakan,
            jenis_maintenance,
            tanggal_refill,
            operator,
            nama_operator,
            main_pengguna,
            na,
            id_aset,
            id_kategori,
            nomor,
            nomor_sap,
            kode_barang,
            nomor_rangka,
            nomor_mesin,
            nomor_polisi,
            tipe_aset,
            id_kondisi,
            nama_user,
            detail_aset,
            detail_aset_it,
            nama_vendor,
            ip_address,
            mac_address,
            os,
            sn_os,
            office_apps,
            tipe_processor,
            processor_series,
            processor_spec,
            ram,
            hdd,
            jam_jalan,
            merk_monitor,
            ukuran_monitor,
            keterangan_aset,
            nama_kategori,
            id_jenis,
            pengguna,
            nama_jenis,
            id_merk,
            nama_merk,
            nama_merk_tipe,
            nama_status,
            nama_kondisi,
            id_pabrik,
            kode,
            nama_pabrik,
            id_lokasi,
            nama_lokasi,
            id_sub_lokasi,
            nama_sub_lokasi,
            id_area,
            nama_area,
            cop,
            pic,
            pic_asset,
            email_pic,
            nama_pic,
            main_status
        ");
        $this->datatables->from("($main_query) as v_aset_pm");
        $result = $this->datatables->generate();

        return $this->datatables->generate();
    }

    function get_pm_fo($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'fo', 'active' => true, 'main_status' => array()), $params);

        $this->db->distinct();
        //        $this->datatables->select("v_aset_pm.id_main");
        //        $this->datatables->select("v_aset_pm.id_aset");
        //        $this->datatables->select("v_aset_pm.id_kategori");
        //        $this->datatables->select("v_aset_pm.id_jenis");
        //        $this->datatables->select("v_aset_pm.detail_aset_it");

        $this->datatables->from("v_aset_pm_fo");

        if (isset($params['id_main'])) {
            $this->datatables->where('v_aset_pm_fo.id_main', $params['id_main']);
            $params['single_row'] = true;
        }

        if (isset($params['id_aset'])) {
            $this->datatables->where('v_aset_pm_fo.id_aset', $params['id_aset']);
        }
        if (isset($params['active'])) {
            if ($params['active'] !== NULL)
                $this->datatables->where('v_aset_pm_fo.na', 'n');
            else
                $this->datatables->where('v_aset_pm_fo.na', 'y');
        }
        if (isset($params['nama'])) {
            $this->datatables->group_start();
            $this->datatables->like('v_aset_pm_fo.nomor', $params['nama']);
            $this->datatables->or_like('v_aset_pm_fo.nomor_sap', $params['nama']);
            $this->datatables->or_like('v_aset_pm_fo.kode_barang', $params['nama']);
            $this->datatables->group_end();
        }

        if (isset($params['pengguna'])) {
            $this->datatables->where('v_aset_pm_fo.pengguna', $params['pengguna']);
        }

        if (isset($params['role']) && $params['role'] !== NULL) {
            $flag = $params['role'] == 'PIC ALAT BERAT' ? '1' : '5';
            $this->datatables->where('v_aset_pm_fo.id_kategori', $flag);
        }

        if (isset($params['operator_fo']) && $params['operator_fo'] !== NULL) {
            $this->datatables->where('v_aset_pm_fo.operator', $params['operator_fo']);
        }

        if (isset($params['jenis_tindakan'])) {
            $this->datatables->group_start();
            $this->datatables->where('v_aset_pm_fo.jenis_tindakan', $params['jenis_tindakan']);
            $this->datatables->or_where('v_aset_pm_fo.jenis_tindakan', null);
            $this->datatables->group_end();
        }

        if (isset($params['id_jenis'])) {
            if (is_array($params['id_jenis']))
                $this->datatables->where_in('v_aset_pm_fo.id_jenis', $params['id_jenis']);
            else
                $this->datatables->where('v_aset_pm_fo.id_jenis', $params['id_jenis']);
        }
        if (isset($params['id_merk'])) {
            if (is_array($params['id_merk']))
                $this->datatables->where_in('v_aset_pm_fo.id_merk', $params['id_merk']);
            else
                $this->datatables->where('v_aset_pm_fo.id_merk', $params['id_merk']);
        }
        if (isset($params['id_pabrik'])) {
            if (is_array($params['id_pabrik']))
                $this->datatables->where_in('v_aset_pm_fo.id_pabrik', $params['id_pabrik']);
            else
                $this->datatables->where('v_aset_pm_fo.id_pabrik', $params['id_pabrik']);
        }
        if (isset($params['id_lokasi'])) {
            if (is_array($params['id_lokasi']))
                $this->datatables->where_in('v_aset_pm_fo.id_lokasi', $params['id_lokasi']);
            else
                $this->datatables->where('v_aset_pm_fo.id_lokasi', $params['id_lokasi']);
        }
        if (isset($params['id_area'])) {
            if (is_array($params['id_area']))
                $this->datatables->where_in('v_aset_pm_fo.id_area', $params['id_area']);
            else
                $this->datatables->where('v_aset_pm_fo.id_area', $params['id_area']);
        }
        if (isset($params['main_status'])) {
            if (is_array($params['main_status']))
                $this->datatables->where_in('v_aset_pm_fo.main_status', $params['main_status']);
            else
                $this->datatables->where('v_aset_pm_fo.main_status', $params['main_status']);
        }

        if (!isset($params['main_status']) || !in_array('noschedule', $params['main_status'])) {
            if (isset($params['tanggal_awal']))
                $this->datatables->where('v_aset_pm_fo.jadwal_service >=', $params['tanggal_awal']);
            if (isset($params['tanggal_akhir']))
                $this->datatables->where('v_aset_pm_fo.jadwal_service <=', $params['tanggal_akhir']);
        }

        if (isset($params['filter_operator'])) {
            if (is_array($params['filter_operator']))
                $this->datatables->where_in('v_aset_pm_fo.nama_operator', $params['filter_operator']);
            else
                $this->datatables->where('v_aset_pm_fo.nama_operator', $params['filter_operator']);
        }

        if (isset($params['kode_pabrik'])) {
            $this->datatables->where_in('v_aset_pm_fo.kode', $params['kode_pabrik']);
        } else {
            if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
                $this->datatables->where("v_aset_pm_fo.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='" . base64_decode($this->session->userdata("-gsber-")) . "')");
            }
        }

        $this->datatables->where("v_aset_pm_fo.id_pabrik!=''");

        return $this->datatables->generate();
    }

    function get_list_asset($params = array())
    {
        $params = array_merge(array(
            'all' => false, 'active' => true
        ), $params);

        $this->db->select("t1.*");

        $this->db->from("v_aset_pm_fo t1");

        if (isset($params['active'])) {
            if ($params['active'])
                $this->db->where('t1.na', 'n');
            else
                $this->db->where('t1.na', 'y');
        }
        if (isset($params['id_jenis'])) {
            if (is_array($params['id_jenis']))
                $this->db->where_in('t1.id_jenis', $params['id_jenis']);
            else
                $this->db->where('t1.id_jenis', $params['id_jenis']);
        }
        if (isset($params['kode_pabrik'])) {
            $this->db->where_in('t1.kode', $params['kode_pabrik']);
        } else {
            if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
                $this->db->where("t1.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='" . base64_decode($this->session->userdata("-gsber-")) . "')");
            }
        }

        $this->db->where("t1.id_pabrik!=''");

        $this->db->where('t1.id_main = (SELECT MAX(t2.id_main)
        FROM v_aset_pm_fo t2
        WHERE t2.nomor = t1.nomor)');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }
    function get_pm_data_fo($params = array())
    {
        $params = array_merge(array(
            'all' => false, 'active' => true,
            'ho' => base64_decode($this->session->userdata("-ho-")),
            'gsber' => base64_decode($this->session->userdata("-gsber-"))
        ), $params);

        $this->db->select("v_aset_pm_fo.*");

        $this->db->from("v_aset_pm_fo");

        if (isset($params['id_main'])) {
            $this->db->where('v_aset_pm_fo.id_main', $params['id_main']);
            $params['single_row'] = true;
        }

        if (isset($params['id_aset'])) {
            $this->db->where('v_aset_pm_fo.id_aset', $params['id_aset']);
        }
        if (isset($params['active'])) {
            if ($params['active'])
                $this->db->where('v_aset_pm_fo.na', 'n');
            else
                $this->db->where('v_aset_pm_fo.na', 'y');
        }
        if (isset($params['nama'])) {
            $this->db->like('v_aset_pm_fo.nomor_sap', $params['nama']);
        }
        if (isset($params['pengguna'])) {
            $this->db->where('v_aset_pm_fo.pengguna', $params['pengguna']);
        }
        if (isset($params['nik'])) {
            $this->db->where('v_aset_pm_fo.operator', '\'' . $params['nik'] . '\'', false);
        }
        if (isset($params['id_jenis'])) {
            if (is_array($params['id_jenis']))
                $this->db->where_in('v_aset_pm_fo.id_jenis', $params['id_jenis']);
            else
                $this->db->where('v_aset_pm_fo.id_jenis', $params['id_jenis']);
        }
        if (isset($params['id_merk'])) {
            if (is_array($params['id_merk']))
                $this->db->where_in('v_aset_pm_fo.id_merk', $params['id_merk']);
            else
                $this->db->where('v_aset_pm_fo.id_merk', $params['id_merk']);
        }
        if (isset($params['id_pabrik'])) {
            if (is_array($params['id_pabrik']))
                $this->db->where_in('v_aset_pm_fo.id_pabrik', $params['id_pabrik']);
            else
                $this->db->where('v_aset_pm_fo.id_pabrik', $params['id_pabrik']);
        }
        if (isset($params['id_lokasi'])) {
            if (is_array($params['id_lokasi']))
                $this->db->where_in('v_aset_pm_fo.id_lokasi', $params['id_lokasi']);
            else
                $this->db->where('v_aset_pm_fo.id_lokasi', $params['id_lokasi']);
        }
        if (isset($params['id_area'])) {
            if (is_array($params['id_area']))
                $this->db->where_in('v_aset_pm_fo.id_area', $params['id_area']);
            else
                $this->db->where('v_aset_pm_fo.id_area', $params['id_area']);
        }
        if (isset($params['main_status'])) {
            if (is_array($params['main_status']))
                $this->db->where_in('v_aset_pm_fo.main_status', $params['main_status']);
            else
                $this->db->where('v_aset_pm_fo.main_status', $params['main_status']);
        }
        if (isset($params['barcode'])) {
            $this->db->group_start();
            $this->db->where('v_aset_pm_fo.nomor', $params['barcode']);
            $this->db->or_where('v_aset_pm_fo.nomor_sap', $params['barcode']);
            $this->db->or_where('v_aset_pm_fo.kode_barang', $params['barcode']);
            $this->db->group_end();
        }
        if (isset($params['ho']))
            if ($params['ho'] !== 'y') {
                $this->db->where("v_aset_pm_fo.id_pabrik in (
                    (
                        SELECT id_pabrik 
                          FROM tbl_inv_pabrik 
                         WHERE kode IN (
                            SELECT splitdata
                            FROM tbl_inv_mobile
                            CROSS APPLY fnSplitString(pabrik, ',')
                            WHERE nik = '" . base64_decode($this->session->userdata("-nik-")) . "'
                        )
                    )

                )");
            }

        $this->db->where("v_aset_pm_fo.id_pabrik!=''");

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_pm_data($params = array())
    {
        $params = array_merge(array(
            'all' => false, 'active' => true,
            'ho' => base64_decode($this->session->userdata("-ho-")),
            'gsber' => base64_decode($this->session->userdata("-gsber-"))
        ), $params);

        $this->db->select("v_aset_pm.*");

        $this->db->from("v_aset_pm");

        if (isset($params['id_main'])) {
            $this->db->where('v_aset_pm.id_main', $params['id_main']);
            $params['single_row'] = true;
        }

        if (isset($params['id_aset'])) {
            $this->db->where('v_aset_pm.id_aset', $params['id_aset']);
        }
        if (isset($params['active'])) {
            if ($params['active'])
                $this->db->where('v_aset_pm.na', 'n');
            else
                $this->db->where('v_aset_pm.na', 'y');
        }
        if (isset($params['nama'])) {
            $this->db->like('v_aset_pm.nomor_sap', $params['nama']);
        }
        if (isset($params['pengguna'])) {
            $this->db->where('v_aset_pm.pengguna', $params['pengguna']);
        }
        if (isset($params['nik'])) {
            $this->db->where('v_aset_pm.operator', '\'' . $params['nik'] . '\'', false);
        }
        if (isset($params['id_jenis'])) {
            if (is_array($params['id_jenis']))
                $this->db->where_in('v_aset_pm.id_jenis', $params['id_jenis']);
            else
                $this->db->where('v_aset_pm.id_jenis', $params['id_jenis']);
        }
        if (isset($params['id_merk'])) {
            if (is_array($params['id_merk']))
                $this->db->where_in('v_aset_pm.id_merk', $params['id_merk']);
            else
                $this->db->where('v_aset_pm.id_merk', $params['id_merk']);
        }
        if (isset($params['id_pabrik'])) {
            if (is_array($params['id_pabrik']))
                $this->db->where_in('v_aset_pm.id_pabrik', $params['id_pabrik']);
            else
                $this->db->where('v_aset_pm.id_pabrik', $params['id_pabrik']);
        }
        if (isset($params['id_lokasi'])) {
            if (is_array($params['id_lokasi']))
                $this->db->where_in('v_aset_pm.id_lokasi', $params['id_lokasi']);
            else
                $this->db->where('v_aset_pm.id_lokasi', $params['id_lokasi']);
        }
        if (isset($params['id_area'])) {
            if (is_array($params['id_area']))
                $this->db->where_in('v_aset_pm.id_area', $params['id_area']);
            else
                $this->db->where('v_aset_pm.id_area', $params['id_area']);
        }
        if (isset($params['main_status'])) {
            if (is_array($params['main_status']))
                $this->db->where_in('v_aset_pm.main_status', $params['main_status']);
            else
                $this->db->where('v_aset_pm.main_status', $params['main_status']);
        }
        if (isset($params['barcode'])) {
            $this->db->group_start();
            $this->db->where('v_aset_pm.nomor', $params['barcode']);
            $this->db->or_where('v_aset_pm.nomor_sap', $params['barcode']);
            $this->db->or_where('v_aset_pm.kode_barang', $params['barcode']);
            $this->db->group_end();
        }
        if (isset($params['ho']))
            if ($params['ho'] !== 'y') {
                $this->db->where("v_aset_pm.id_pabrik in (
                    (
                        SELECT id_pabrik 
                          FROM tbl_inv_pabrik 
                         WHERE kode IN (
                            SELECT splitdata
                            FROM tbl_inv_mobile
                            CROSS APPLY fnSplitString(pabrik, ',')
                            WHERE nik = '" . base64_decode($this->session->userdata("-nik-")) . "'
                        )
                    )

                )");
            }

        $this->db->where("v_aset_pm.id_pabrik!=''");

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_pm_detail($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'it', 'active' => true), $params);

        $this->db->select('tbl_inv_main_detail.*');
        $this->db->from('tbl_inv_main_detail');

        if (isset($params['id_main'])) {
            $this->db->where('tbl_inv_main_detail.id_main', $params['id_main']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_main_detail.del', 'n');
            $this->db->where('tbl_inv_main_detail.na', 'n');
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_pm_items($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'it', 'active' => true), $params);

        $this->db->select('tbl_inv_main_items.*');
        $this->db->from('tbl_inv_main_items');

        if (isset($params['id_main'])) {
            $this->db->where('tbl_inv_main_items.id_main', $params['id_main']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_main_items.del', 'n');
            $this->db->where('tbl_inv_main_items.na', 'n');
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_pm_history($params = array())
    {
        $params = array_merge(array(
            'all' => false, 'pengguna' => 'it', 'active' => true,
            'ho' => base64_decode($this->session->userdata("-ho-")),
        ), $params);

        $this->datatables->select("v_aset_pm_history.*");

        $this->datatables->from("v_aset_pm_history");

        if (isset($params['id_main'])) {
            $this->datatables->where('v_aset_pm_history.id_main', $params['id_main']);
            $params['single_row'] = true;
        }

        if (isset($params['id_aset'])) {
            $this->datatables->where('v_aset_pm_history.id_aset', $params['id_aset']);
        }
        if (isset($params['active'])) {
            if ($params['active'])
                $this->datatables->where('v_aset_pm_history.na', 'n');
            else
                $this->datatables->where('v_aset_pm_history.na', 'y');
        }
        if (isset($params['nama'])) {
            $this->datatables->like('v_aset_pm_history.nomor_sap', $params['nama']);
        }
        if (isset($params['pengguna'])) {
            $this->datatables->where('v_aset_pm_history.pengguna', $params['pengguna']);
        }
        if (isset($params['id_jenis'])) {
            if (is_array($params['id_jenis']))
                $this->datatables->where_in('v_aset_pm_history.id_jenis', $params['id_jenis']);
            else
                $this->datatables->where('v_aset_pm_history.id_jenis', $params['id_jenis']);
        }
        if (isset($params['id_merk'])) {
            if (is_array($params['id_merk']))
                $this->datatables->where_in('v_aset_pm_history.id_merk', $params['id_merk']);
            else
                $this->datatables->where('v_aset_pm_history.id_merk', $params['id_merk']);
        }
        if (isset($params['id_pabrik'])) {
            if (is_array($params['id_pabrik']))
                $this->datatables->where_in('v_aset_pm_history.id_pabrik', $params['id_pabrik']);
            else
                $this->datatables->where('v_aset_pm_history.id_pabrik', $params['id_pabrik']);
        }
        if (isset($params['id_lokasi'])) {
            if (is_array($params['id_lokasi']))
                $this->datatables->where_in('v_aset_pm_history.id_lokasi', $params['id_lokasi']);
            else
                $this->datatables->where('v_aset_pm_history.id_lokasi', $params['id_lokasi']);
        }
        if (isset($params['id_area'])) {
            if (is_array($params['id_area']))
                $this->datatables->where_in('v_aset_pm_history.id_area', $params['id_area']);
            else
                $this->datatables->where('v_aset_pm_history.id_area', $params['id_area']);
        }
        if (isset($params['main_status'])) {
            if (is_array($params['main_status']))
                $this->datatables->where_in('v_aset_pm_history.main_status', $params['main_status']);
            else
                $this->datatables->where('v_aset_pm_history.main_status', $params['main_status']);
        }

        if ($params['ho'] !== 'y') {
            $this->datatables->where("v_aset_pm_history.id_pabrik in (
                (
                    SELECT id_pabrik 
                      FROM tbl_inv_pabrik 
                     WHERE kode IN (
                        SELECT splitdata
                        FROM tbl_inv_mobile
                        CROSS APPLY fnSplitString(pabrik, ',')
                        WHERE nik = '" . base64_decode($this->session->userdata("-nik-")) . "'
                    )
                )

            )");
        }

        $this->datatables->where("v_aset_pm_history.id_pabrik!=''");

        return $this->datatables->generate();
    }

    function get_pm_approval($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'it', 'active' => true), $params);

        //        $this->datatables->select("v_aset_pm_approval.*");

        $this->datatables->from("v_aset_pm_approval");

        if (isset($params['id_main'])) {
            $this->datatables->where('v_aset_pm_approval.id_main', $params['id_main']);
            $params['single_row'] = true;
        }

        if (isset($params['id_aset'])) {
            $this->datatables->where('v_aset_pm_approval.id_aset', $params['id_aset']);
        }
        if (isset($params['active'])) {
            if ($params['active'])
                $this->datatables->where('v_aset_pm_approval.na', 'n');
            else
                $this->datatables->where('v_aset_pm_approval.na', 'y');
        }
        if (isset($params['nama'])) {
            $this->datatables->like('v_aset_pm_approval.nomor_sap', $params['nama']);
        }
        if (isset($params['pengguna'])) {
            $this->datatables->where('v_aset_pm_approval.pengguna', $params['pengguna']);
        }
        if (isset($params['id_jenis'])) {
            if (is_array($params['id_jenis']))
                $this->datatables->where_in('v_aset_pm_approval.id_jenis', $params['id_jenis']);
            else
                $this->datatables->where('v_aset_pm_approval.id_jenis', $params['id_jenis']);
        }
        if (isset($params['id_merk'])) {
            if (is_array($params['id_merk']))
                $this->datatables->where_in('v_aset_pm_approval.id_merk', $params['id_merk']);
            else
                $this->datatables->where('v_aset_pm_approval.id_merk', $params['id_merk']);
        }
        if (isset($params['id_pabrik'])) {
            if (is_array($params['id_pabrik']))
                $this->datatables->where_in('v_aset_pm_approval.id_pabrik', $params['id_pabrik']);
            else
                $this->datatables->where('v_aset_pm_approval.id_pabrik', $params['id_pabrik']);
        }
        if (isset($params['id_lokasi'])) {
            if (is_array($params['id_lokasi']))
                $this->datatables->where_in('v_aset_pm_approval.id_lokasi', $params['id_lokasi']);
            else
                $this->datatables->where('v_aset_pm_approval.id_lokasi', $params['id_lokasi']);
        }
        if (isset($params['id_area'])) {
            if (is_array($params['id_area']))
                $this->datatables->where_in('v_aset_pm_approval.id_area', $params['id_area']);
            else
                $this->datatables->where('v_aset_pm_approval.id_area', $params['id_area']);
        }
        if (isset($params['main_status'])) {
            if (is_array($params['main_status']))
                $this->datatables->where_in('v_aset_pm_approval.main_status', $params['main_status']);
            else
                $this->datatables->where('v_aset_pm_approval.main_status', $params['main_status']);
        }

        if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
            $this->datatables->where("v_aset_pm_approval.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='" . base64_decode($this->session->userdata("-gsber-")) . "')");
        }

        if (isset($params['filter_status'])) {
            $this->db->where('v_aset_pm_approval.final', $params['filter_status']);
        }

        $this->datatables->where("v_aset_pm_approval.id_pabrik!=''");

        return $this->datatables->generate();
    }

    function get_periode($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'it', 'active' => true), $params);

        $this->db->select('tbl_inv_periode.*');
        $this->db->from('tbl_inv_periode');
        $this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_periode.id_jenis AND tbl_inv_periode.na = \'n\'', 'inner');

        if (isset($params['id_periode'])) {
            $this->db->where('tbl_inv_periode.id_periode', $params['id_periode']);
        }
        if (isset($params['id_jenis'])) {
            $this->db->where('tbl_inv_periode.id_jenis', $params['id_jenis']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_periode.del', 'n');
            $this->db->where('tbl_inv_periode.na', 'n');
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_data_tanggal_kerja($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'it', 'active' => true), $params);

        $this->db->select('ZKISSTT_0138.ACTDT as tanggal_kerja');
        $this->db->from('SAPSYNC.dbo.ZKISSTT_0138');
        if (isset($params['tanggal_selesai'])) {
            $this->db->where("ZKISSTT_0138.ACTDT > '" . $params['tanggal_selesai'] . "'");
            $this->db->where("ZKISSTT_0138.ACTDT > '" . date('Y-m-d') . "'");
            $this->db->where("ZKISSTT_0138.HOLDT is null");
        }
        $this->db->order_by("ZKISSTT_0138.ACTDT", "asc");
        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_periode_detail($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'it', 'active' => true), $params);

        $this->db->select('tbl_inv_periode_detail.*');
        $this->db->select('tbl_inv_jenis_detail.nama as nama_jenis_detail');
        $this->db->from('tbl_inv_periode_detail');
        $this->db->join('tbl_inv_jenis_detail', 'tbl_inv_jenis_detail.id_jenis_detail = tbl_inv_periode_detail.id_jenis_detail AND tbl_inv_jenis_detail.na = \'n\'', 'inner');

        if (isset($params['id_periode_detail'])) {
            $this->db->where('tbl_inv_periode_detail.id_periode_detail', $params['id_periode_detail']);
        }
        if (isset($params['id_periode'])) {
            $this->db->where('tbl_inv_periode_detail.id_periode', $params['id_periode']);
        }
        if (isset($params['id_jenis'])) {
            $this->db->where('tbl_inv_periode_detail.id_jenis', $params['id_jenis']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_periode_detail.del', 'n');
            $this->db->where('tbl_inv_periode_detail.na', 'n');
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_pm_status($params = array())
    {
        $params = array_merge(array('all' => false, 'pengguna' => 'it', 'active' => true), $params);

        $this->db->select('tbl_inv_main_status.*');
        $this->db->from('tbl_inv_main_status');

        if (isset($params['id_main'])) {
            $this->db->where('tbl_inv_main_status.id_main', $params['id_main']);
        }

        if (isset($params['all']) && !$params['all']) {
            $this->db->where('tbl_inv_main_status.del', 'n');
            $this->db->where('tbl_inv_main_status.na', 'n');
        }

        if (isset($params['order'])) {
            $this->db->order_by($params['order']);
        } else
            $this->db->order_by('tbl_inv_main_status.id_main_status', 'desc');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_agent_fo($params = array())
    {
        $params = array_merge(array('active' => true), $params);

        $this->db->select('tbl_inv_mobile.nik as agent');
        $this->db->select('tbl_karyawan.nama');
        $this->db->from('tbl_inv_mobile');
        $this->db->join('tbl_karyawan', 'tbl_inv_mobile.nik = tbl_karyawan.nik');

        if (isset($params['kode'])) {
            $this->db->like('tbl_inv_mobile.pabrik', $params['kode']);
        }
        // test
        if (isset($params['akses_jadwal'])) {
            $this->db->where('tbl_inv_mobile.akses_jadwal', '1');
        }

        if (isset($params['akses_pm'])) {
            $this->db->where('tbl_inv_mobile.akses_pm', '1');
        }

        if (isset($params['akses_konfirm'])) {
            $this->db->where('tbl_inv_mobile.akses_konfirm', '1');
        }

        if (isset($params['order'])) {
            $this->db->order_by($params['order']);
        } else {
            $this->db->order_by('tbl_karyawan.nama', 'asc');
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_agent($params = array())
    {
        $params = array_merge(array('active' => true), $params);

        $this->db->select('v_aset_agent.*');
        $this->db->from('v_aset_agent');

        if (isset($params['nik'])) {
            $this->db->where('v_aset_agent.agent', $params['nik']);
        }

        if (isset($params['kode'])) {
            $this->db->where('v_aset_agent.lokasi_agent', $params['kode']);
        }

        if (isset($params['order'])) {
            $this->db->order_by($params['order']);
        } else {
            $this->db->order_by('v_aset_agent.onprogress', 'asc');
            $this->db->order_by('v_aset_agent.agent', 'asc');
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_kondisi($params = array())
    {
        $params = array_merge(array('active' => true), $params);

        $this->db->select('tbl_inv_kondisi.id_kondisi');
        $this->db->select('tbl_inv_kondisi.kode');
        $this->db->select('tbl_inv_kondisi.nama');
        $this->db->from('tbl_inv_kondisi');

        if (isset($params['id_kondisi'])) {
            $this->db->where('tbl_inv_kondisi.id_kondisi', $params['id_kondisi']);
        }

        if (isset($params['active'])) {
            $this->db->where('tbl_inv_kondisi.na', $params['active'] ? 'n' : 'y');
        }
        $this->db->where('tbl_inv_kondisi.id_kondisi!=', 3);
        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    function get_mobile_app($params = array())
    {
        $params = array_merge(array('active' => true), $params);

        $this->db->select('tbl_mobile_apps.*');
        $this->db->from('tbl_mobile_apps');

        if (isset($params['package'])) {
            $this->db->where('tbl_mobile_apps.package', $params['package']);
        }
        if (isset($params['active'])) {
            $this->db->where('tbl_mobile_apps.na', $params['active'] ? 'n' : 'y');
        }
        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_karyawan($params = array(), $param2 = NULL)
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $ho = isset($params['ho']) ? $params['ho'] : null;
        $search = isset($params['search']) ? $params['search'] : null;

        $this->db->select("tbl_karyawan.*,tbl_departemen.nama as nama_departemen");
        $this->db->select("tbl_divisi.nama as nama_divisi");
        $this->db->select("tbl_sub_divisi.nama as nama_sub_divisi");
        $this->db->select("tbl_seksi.nama as nama_seksi");
        $this->db->select("tbl_wf_master_plant.plant_name as nama_pabrik");
        $this->db->select("tbl_user.id_divisi");
        $this->db->from('tbl_karyawan');
        $this->db->join('tbl_user', 'tbl_user.id_karyawan=tbl_karyawan.id_karyawan', 'left outer');
        $this->db->join('tbl_level', 'tbl_user.id_level= tbl_level.id_level', 'left outer');
        $this->db->join('tbl_departemen', 'tbl_departemen.id_departemen= tbl_user.id_departemen', 'left outer');
        $this->db->join('tbl_divisi', 'tbl_divisi.id_divisi= tbl_user.id_divisi', 'left outer');
        $this->db->join('tbl_sub_divisi', 'tbl_sub_divisi.id_sub_divisi= tbl_user.id_sub_divisi', 'left outer');
        $this->db->join('tbl_seksi', 'tbl_seksi.id_seksi= tbl_user.id_seksi', 'left outer');
        $this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant= tbl_karyawan.gsber', 'left outer');

        $this->db->where('tbl_user.na', 'n');
        $this->db->where('tbl_user.del', 'n');
        $this->db->where('tbl_karyawan.na', 'n');
        $this->db->where('tbl_karyawan.del', 'n');

        if (isset($ho) && $ho)
            $this->db->where('tbl_karyawan.ho', 'y');
        if (isset($param2)) {
            if (base64_decode($this->session->userdata("-ho-")) == 'y') {
                $this->db->where("tbl_karyawan.id_karyawan in(select agent from v_aset_agent)");
            } else {
                $this->db->where("tbl_karyawan.id_karyawan in(select agent from v_aset_agent where lokasi_agent='" . base64_decode($this->session->userdata("-gsber-")) . "')");
            }
        }

        if (isset($search) && !empty($search)) {
            $this->db->group_start();
            $this->db->where('CONVERT(VARCHAR(MAX),tbl_karyawan.nik)', $search);
            $this->db->or_like('tbl_karyawan.nama', $search);
            $this->db->group_end();
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
    //LHA
    function get_asset_history($params = array())
    {
        $params = array_merge(array(
            'all' => false, 'pengguna' => 'it', 'active' => true,
            'ho' => base64_decode($this->session->userdata("-ho-")),
        ), $params);


        $this->db->select('CASE
								WHEN tbl_inv_aset_temp.proses = \'set_pic\' THEN \'USER\'
								WHEN tbl_inv_aset_temp.proses = \'set_kondisi\' THEN \'KONDISI\'
								WHEN tbl_inv_aset_temp.proses = \'set_perbaikan\' THEN \'PERBAIKAN\'
								WHEN tbl_inv_aset_temp.proses = \'set_lokasi\' THEN \'LOKASI\'
								ELSE \'-\'
						   END as jenis_perubahan');
        $this->db->select("CASE
								WHEN tbl_inv_aset_temp.proses = 'set_pic' THEN (select CONVERT(varchar, tbl_karyawan.nik)+' - '+tbl_karyawan.nama from tbl_karyawan where tbl_karyawan.nik=tbl_inv_aset_temp.status_awal)
								WHEN tbl_inv_aset_temp.proses = 'set_kondisi' THEN (select tbl_inv_kondisi.nama from tbl_inv_kondisi where tbl_inv_kondisi.id_kondisi=tbl_inv_aset_temp.status_awal)
								WHEN tbl_inv_aset_temp.proses = 'set_perbaikan' THEN (select tbl_inv_kondisi.nama from tbl_inv_kondisi where tbl_inv_kondisi.id_kondisi=tbl_inv_aset_temp.status_awal)
								WHEN tbl_inv_aset_temp.proses = 'set_lokasi' THEN '<b>Sub Lokasi</b> : '+(select tbl_inv_sub_lokasi.nama from tbl_inv_sub_lokasi where tbl_inv_sub_lokasi.id_sub_lokasi=tbl_inv_aset_temp.id_sub_lokasi_awal)+'<br><b>Area</b> : '+(select tbl_inv_area.nama from tbl_inv_area where tbl_inv_area.id_area=tbl_inv_aset_temp.id_area_awal)
								ELSE '-'
						   END as label_status_awal");
        $this->db->select("CASE
								WHEN tbl_inv_aset_temp.proses = 'set_pic' THEN (select CONVERT(varchar, tbl_karyawan.nik)+' - '+tbl_karyawan.nama from tbl_karyawan where tbl_karyawan.nik=tbl_inv_aset_temp.status_akhir)
								WHEN tbl_inv_aset_temp.proses = 'set_kondisi' THEN (select tbl_inv_kondisi.nama from tbl_inv_kondisi where tbl_inv_kondisi.id_kondisi=tbl_inv_aset_temp.status_akhir)
								WHEN tbl_inv_aset_temp.proses = 'set_perbaikan' THEN (select tbl_inv_kondisi.nama from tbl_inv_kondisi where tbl_inv_kondisi.id_kondisi=tbl_inv_aset_temp.status_akhir)
								WHEN tbl_inv_aset_temp.proses = 'set_lokasi' THEN '<b>Sub Lokasi</b> : '+(select tbl_inv_sub_lokasi.nama from tbl_inv_sub_lokasi where tbl_inv_sub_lokasi.id_sub_lokasi=tbl_inv_aset_temp.id_sub_lokasi_akhir)+'<br><b>Area</b> : '+(select tbl_inv_area.nama from tbl_inv_area where tbl_inv_area.id_area=tbl_inv_aset_temp.id_area_akhir)
								ELSE '-'
						   END as label_status_akhir");
        $this->db->select("tbl_inv_aset_temp.*");

        $this->db->from("tbl_inv_aset_temp");
        if (isset($params['id_aset'])) {
            $this->db->where('tbl_inv_aset_temp.id_aset', $params['id_aset']);
        }
        if (isset($params['active'])) {
            if ($params['active'])
                $this->db->where('tbl_inv_aset_temp.na', 'n');
            else
                $this->db->where('tbl_inv_aset_temp.na', 'y');
        }
        $this->db->order_by("tbl_inv_aset_temp.tanggal_buat", "desc");
        $datas =  $this->db->get();
        return $datas->result();
    }

    function get_asset_perbaikan($id_aset = NULL)
    {
        $this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR, ISNULL(tbl_inv_main_detail.nama_jenis_detail+' : '+tbl_inv_main_detail.keterangan,0))+RTRIM(',')
					            FROM tbl_inv_main_detail
								WHERE tbl_inv_main_detail.id_main = tbl_inv_main.id_main
								ORDER BY tbl_inv_main_detail.nama_jenis_detail
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_item,
						");
        $this->db->select("tbl_inv_main.id_main as id");
        $this->db->select("tbl_inv_main.*");
        $this->db->select("tbl_karyawan.nama as nama_karyawan");
        $this->db->select("tbl_karyawan.nik as nik_karyawan");
        $this->db->from("tbl_inv_main");
        $this->db->join('tbl_user', 'tbl_user.id_user = tbl_inv_main.login_buat');
        $this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan');
        if (isset($id_aset)) {
            $this->db->where('tbl_inv_main.id_aset', $id_aset);
        }
        $this->db->where('tbl_inv_main.na', 'n');
        $this->db->where('tbl_inv_main.del', 'n');
        $this->db->where('tbl_inv_main.jenis_tindakan', 'perbaikan');
        $this->db->order_by("tbl_inv_main.id_main", "desc");
        $datas =  $this->db->get();
        return $datas->result();
    }

    function get_pm_email($params = array())
    {
        if ($params['conn'] !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('v_aset_email.*');

        $this->db->from('v_aset_email');

        if ($params['id_main'] !== NULL) {
            $this->db->where('v_aset_email.id_main', $params['id_main']);
        }

        $query     = $this->db->get();
        $result    = $query->result();

        if ($params['conn'] !== NULL)
            $this->general->closeDb();
        return $result;
    }
}
