<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Permintaan - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dpengembalian extends CI_Model
{
    public function get_pengembalian($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_pengembalian = isset($params['id_pengembalian']) ? $params['id_pengembalian'] : null;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $id_status = isset($params['id_status']) ? $params['id_status'] : null;
        $id_status_not = isset($params['id_status_not']) ? $params['id_status_not'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_pengembalian_dokumen.tanggal_edit desc";

        $this->db->select('tbl_leg_pengembalian_dokumen.*');
        $this->db->select('tbl_leg_status.status,tbl_leg_status.warna');

        $this->db->from('tbl_leg_pengembalian_dokumen');
        $this->db->join('tbl_leg_status', 'tbl_leg_pengembalian_dokumen.id_status=tbl_leg_status.id_status');

        if (isset($nik)) {
            $this->db->join('tbl_karyawan', 'tbl_leg_pengembalian_dokumen.plant=tbl_karyawan.gsber', 'left outer');
            $this->db->where('tbl_karyawan.nik', $nik);
        }

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_pengembalian_dokumen.na', 'n');
            $this->db->where('tbl_leg_pengembalian_dokumen.del', 'n');
        }

        if (isset($id_pengembalian))
            $this->db->where('tbl_leg_pengembalian_dokumen.id_pengembalian', $id_pengembalian);

        if (isset($id_status)) {
            if (is_array($id_status))
                $this->db->where_in('tbl_leg_pengembalian_dokumen.id_status', $id_status);
            else
                $this->db->where('tbl_leg_pengembalian_dokumen.id_status', $id_status);
        }

        if (isset($id_status_not)) {
            if (is_array($id_status_not))
                $this->db->where_not_in('tbl_leg_pengembalian_dokumen.id_status', $id_status_not);
            else
                $this->db->where('tbl_leg_pengembalian_dokumen.id_status <>', $id_status_not);
        }

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_resi($params = array())
    {

        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_resi = isset($params['id_resi']) ? $params['id_resi'] : null;
        $id_pengembalian = isset($params['id_pengembalian']) ? $params['id_pengembalian'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_resi_return.tanggal_edit desc";

        $this->db->select('tbl_leg_resi_return.*');
        $this->db->select('tbl_leg_resi_return.id_resi_return as id_resi');
        $this->db->from('tbl_leg_resi_return');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_resi_return.na', 'n');
            $this->db->where('tbl_leg_resi_return.del', 'n');
        }

        if (isset($id_pengembalian))
            $this->db->where('tbl_leg_resi_return.id_pengembalian', $id_pengembalian);

        if (isset($id_resi))
            $this->db->where('tbl_leg_resi_return.id_resi', $id_resi);

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_kategori_dokumen($params = array())
    {

        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_kategori = isset($params['id_kategori']) ? $params['id_kategori'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_kategori_dokumen.tanggal_edit desc";

        $this->db->select('tbl_leg_kategori_dokumen.*');
        $this->db->from('tbl_leg_kategori_dokumen');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_kategori_dokumen.na', 'n');
            $this->db->where('tbl_leg_kategori_dokumen.del', 'n');
        }

        if (isset($id_kategori))
            $this->db->where('tbl_leg_kategori_dokumen.id_kategori', $id_kategori);

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_dokumen_pengembalian($params = array())
    {

        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $plant = isset($params['plant']) ? $params['plant'] : null;
        $id_kategori = isset($params['id_kategori']) ? $params['id_kategori'] : null;
        $id_permintaan = isset($params['id_permintaan']) ? $params['id_permintaan'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_permintaan_dokumen.nama_permintaan_dok desc";

        $this->db->distinct();
        $this->db->select('tbl_leg_permintaan_dokumen.id_permintaan');
        $this->db->select('tbl_leg_permintaan_dokumen.nama_permintaan_dok');
        $this->db->select('tbl_leg_permintaan_dokumen.nomor_dokumen');

        $this->db->from('tbl_leg_permintaan_dokumen');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_permintaan_dokumen.na', 'n');
            $this->db->where('tbl_leg_permintaan_dokumen.del', 'n');
        }

        if (isset($id_kategori))
            $this->db->where('tbl_leg_permintaan_dokumen.id_kategori', $id_kategori);

        if (isset($plant))
            $this->db->where('tbl_leg_permintaan_dokumen.plant', $plant);

        if (isset($id_permintaan))
            $this->db->where('tbl_leg_permintaan_dokumen.id_permintaan', $id_permintaan);

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_keterangan($params = array())
    {
//        tbl_leg_keterangan

        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_pengembalian = isset($params['id_pengembalian']) ? $params['id_pengembalian'] : null;
        $id_keterangan = isset($params['id_keterangan']) ? $params['id_keterangan'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_keterangan.tanggal_edit desc";

        $this->db->select('tbl_leg_keterangan.*');

        $this->db->from('tbl_leg_keterangan');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_keterangan.na', 'n');
            $this->db->where('tbl_leg_keterangan.del', 'n');
        }

        if (isset($id_pengembalian))
            $this->db->where('tbl_leg_keterangan.id_pengembalian', $id_pengembalian);

        if (isset($id_keterangan))
            $this->db->where('tbl_leg_keterangan.id_keterangan', $id_keterangan);

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_permintaan($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_permintaan = isset($params['id_permintaan']) ? $params['id_permintaan'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_permintaan_dokumen.tanggal_edit desc";

        $this->db->select('tbl_leg_permintaan_dokumen.*');
        $this->db->select('tbl_karyawan.email');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');

        $this->db->from('tbl_leg_pengembalian_dokumen');
        $this->db->join(
            'tbl_leg_permintaan_dokumen',
            'tbl_leg_permintaan_dokumen.id_permintaan=tbl_leg_pengembalian_dokumen.id_permintaan'
        );

        $this->db->join(
            'tbl_karyawan',
            'tbl_leg_permintaan_dokumen.plant=tbl_karyawan.gsber and tbl_leg_permintaan_dokumen.user_input=tbl_karyawan.nik',
            'left outer'
        );

        if (!$all) {
            if (!$list)
            {
                $this->db->where('tbl_leg_permintaan_dokumen.na', 'n');
                $this->db->where('tbl_leg_pengembalian_dokumen.na', 'n');
            }
            $this->db->where('tbl_leg_permintaan_dokumen.del', 'n');
            $this->db->where('tbl_leg_pengembalian_dokumen.del', 'n');
        }

        if (isset($id_permintaan))
            $this->db->where('tbl_leg_permintaan_dokumen.id_permintaan', $id_permintaan);

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
}