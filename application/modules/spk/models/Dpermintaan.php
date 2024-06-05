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
class Dpermintaan extends CI_Model
{
    public function get_permintaan($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_permintaan = isset($params['id_permintaan']) ? $params['id_permintaan'] : null;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $id_status = isset($params['id_status']) ? $params['id_status'] : null;
        $id_status_not = isset($params['id_status_not']) ? $params['id_status_not'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_permintaan_dokumen.tanggal_edit desc";

        $this->db->select('tbl_leg_permintaan_dokumen.*');
        $this->db->select('tbl_leg_status.status,tbl_leg_status.warna');

        $this->db->from('tbl_leg_permintaan_dokumen');
        $this->db->join('tbl_leg_status', 'tbl_leg_permintaan_dokumen.id_status=tbl_leg_status.id_status');

        if (isset($nik)) {
            $this->db->join('tbl_karyawan', 'tbl_leg_permintaan_dokumen.plant=tbl_karyawan.gsber', 'left outer');
            $this->db->where('tbl_karyawan.nik', $nik);
        }

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_permintaan_dokumen.na', 'n');
            $this->db->where('tbl_leg_permintaan_dokumen.del', 'n');
        }

        if (isset($id_permintaan))
            $this->db->where('tbl_leg_permintaan_dokumen.id_permintaan', $id_permintaan);

        if (isset($id_status)) {
            if (is_array($id_status))
                $this->db->where_in('tbl_leg_permintaan_dokumen.id_status', $id_status);
            else
                $this->db->where('tbl_leg_permintaan_dokumen.id_status', $id_status);
        }

        if (isset($id_status_not)) {
            if (is_array($id_status_not))
                $this->db->where_not_in('tbl_leg_permintaan_dokumen.id_status', $id_status_not);
            else
                $this->db->where('tbl_leg_permintaan_dokumen.id_status <>', $id_status_not);
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
        $id_permintaan = isset($params['id_permintaan']) ? $params['id_permintaan'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_resi.tanggal_edit desc";

        $this->db->select('tbl_leg_resi.*');
        $this->db->from('tbl_leg_resi');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_resi.na', 'n');
            $this->db->where('tbl_leg_resi.del', 'n');
        }

        if (isset($id_permintaan))
            $this->db->where('tbl_leg_resi.id_permintaan', $id_permintaan);

        if (isset($id_resi))
            $this->db->where('tbl_leg_resi.id_resi', $id_resi);

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
}