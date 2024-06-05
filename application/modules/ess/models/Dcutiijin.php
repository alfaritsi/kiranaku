<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS Cuti Ijin - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dcutiijin extends CI_Model
{

    /** Function ambil data cuti
     * $params [
     *      $id = null
     *      $tanggal_awal = null
     *      $tanggal_akhir = null
     *      $tipe = array('Cuti', 'Ijin')
     *      $kode = null
     *      $id_tipe_status = array('1', '2')
     *      $nik = null
     *      $limit = 0
     *      $ho = null
     *      $atasan = null
     * )]
     * @param array $params
     * @return mixed
     */
    public function get_cuti(
        $params = array()
    )
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $id_cuti_parent = isset($params['id_cuti_parent']) ? $params['id_cuti_parent'] : null;
        $tanggal_awal = isset($params['tanggal_awal']) ? $params['tanggal_awal'] : null;
        $tanggal_akhir = isset($params['tanggal_akhir']) ? $params['tanggal_akhir'] : null;
        $tipe = isset($params['tipe']) ? $params['tipe'] : array('Cuti', 'Ijin');
        $kode = isset($params['kode']) ? $params['kode'] : null;
        $id_tipe_status = isset($params['id_tipe_status']) ? $params['id_tipe_status'] : array(ESS_CUTI_STATUS_MENUNGGU, ESS_CUTI_STATUS_DISETUJUI_ATASAN);
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $limit = isset($params['limit']) ? $params['limit'] : 0;
        $ho = isset($params['ho']) ? $params['ho'] : null;
        $manager = isset($params['manager']) ? $params['manager'] : null;
        $atasan = isset($params['atasan']) ? $params['atasan'] : null;
        $extra = isset($params['extra']) ? $params['extra'] : true;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'id_cuti DESC';

        $this->db->select('tbl_cuti.*');
        if ($extra) {
            $this->db->select('CASE WHEN cuti_parent.id_cuti_parent IS NULL THEN tbl_cuti.tanggal_akhir ELSE cuti_parent.tanggal_akhir END as tanggal_akhir', false);
            $this->db->select('CASE WHEN cuti_parent.id_cuti_parent IS NULL THEN tbl_cuti.jumlah ELSE cuti_parent.jumlah+tbl_cuti.jumlah END as jumlah', false);
            $this->db->select('CASE WHEN cuti_parent.id_cuti_parent IS NULL THEN tbl_cuti.jarak ELSE cuti_parent.jarak END as jarak', false);
            $this->db->select('cuti_parent.id_cuti_parent as id_cuti_parent');
        }
        $this->db->select('tbl_cuti_status.warna');
        $this->db->select('tbl_cuti_status.nama as nama_status');
        $this->db->select('tbl_cuti_ijin.nama as nama_jenis');
        $this->db->select('tbl_karyawan.id_karyawan');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');
        $this->db->select('tbl_departemen.nama as nama_departemen');
        $this->db->select('tbl_level.nama as nama_level');
        $this->db->select('tbl_karyawan.ho as ho');
        $this->db->from('tbl_cuti');
        $this->db->join(
            'tbl_cuti_status',
            'tbl_cuti.id_cuti_status = tbl_cuti_status.id_cuti_status',
            'left outer'
        );
        $this->db->join(
            'tbl_user',
            'tbl_user.id_karyawan=tbl_cuti.nik',
            'left outer'
        );
        $this->db->join(
            'tbl_karyawan',
            'tbl_cuti.nik = tbl_karyawan.id_karyawan',
            'left outer'
        );
        $this->db->join(
            'tbl_departemen',
            'tbl_user.id_departemen=tbl_departemen.id_departemen',
            'left outer'
        );
        $this->db->join(
            'tbl_level',
            'tbl_user.id_level=tbl_level.id_level',
            'left outer'
        );
        $this->db->join(
            'tbl_cuti_ijin',
            'tbl_cuti.kode = tbl_cuti_ijin.kode and tbl_cuti_ijin.kode_grouping_area = tbl_karyawan.moabw',
            'left outer'
        );
        $this->db->join(
            'tbl_cuti as cuti_parent',
            'tbl_cuti.id_cuti = cuti_parent.id_cuti_parent and cuti_parent.na = \'n\'',
            'left outer'
        );
        $this->db->where('tbl_cuti.na', 'n');
        $this->db->where('tbl_cuti.del', 'n');
        $this->db->where('tbl_cuti_status.del', 'n');
        $this->db->where('tbl_cuti_status.na', 'n');

        if($extra){
            $this->db->where('tbl_cuti.id_cuti_parent is null', null, false);
        }

        if (isset($tipe)) {
            if (is_array($tipe))
                $this->db->where_in('tbl_cuti.form', $tipe);
            else
                $this->db->where('tbl_cuti.form', $tipe);
        }

        if (isset($kode)) {
            if (is_array($kode))
                $this->db->where_in('tbl_cuti.kode', $kode);
            else
                $this->db->where('tbl_cuti.kode', $kode);
        }

        if (
            (isset($tanggal_awal) && !empty($tanggal_awal) && isset($tanggal_akhir) && !empty($tanggal_akhir)) ||
            (isset($tanggal_awal) && is_array($tanggal_awal))
        ) {
            if (is_array($tanggal_awal)) {
                if (isset($tanggal_awal[0]))
                    $this->db->where('tbl_cuti.tanggal_awal >=', $tanggal_awal[0]);
                if (isset($tanggal_awal[1]))
                    $this->db->where('tbl_cuti.tanggal_akhir <=', $tanggal_awal[1]);
            } else {
                $this->db->where('tbl_cuti.tanggal_buat >=', $tanggal_awal);
            }
            if (isset($tanggal_akhir))
                if (is_array($tanggal_akhir)) {
                    if (isset($tanggal_akhir[0]))
                        $this->db->where('tbl_cuti.tanggal_awal >=', $tanggal_akhir[0]);
                    if (isset($tanggal_akhir[1]))
                        $this->db->where('tbl_cuti.tanggal_akhir <=', $tanggal_akhir[1]);
                } else {
                    $this->db->where('tbl_cuti.tanggal_buat <=', $tanggal_akhir);
                }
        }

        if (isset($id_tipe_status)) {
            if (is_array($id_tipe_status))
                $this->db->where_in('tbl_cuti_status.id_cuti_status', $id_tipe_status);
            else
                $this->db->where('tbl_cuti_status.id_cuti_status', $id_tipe_status);
        }

        if (isset($nik) && !empty($nik))
            $this->db->where('tbl_cuti.nik', $nik);


        if (isset($id) && !empty($id))
            $this->db->where('tbl_cuti.id_cuti', $id);

        if (isset($id_cuti_parent) && !empty($id_cuti_parent))
            $this->db->where('tbl_cuti.id_cuti_parent', $id_cuti_parent);

        if (isset($ho)) {
            if ($ho)
                $this->db->where('tbl_karyawan.ho', 'y');
            else
                $this->db->where('tbl_karyawan.ho', 'n');
        }

        if (isset($atasan)) {
            $this->db->where('CHARINDEX(\'\'\'\'+CONVERT(varchar(10), \'' . $atasan . '\')+\'\'\'\',\'\'\'\'+REPLACE(tbl_cuti.atasan, RTRIM(\'.\'),\'\'\',\'\'\')+\'\'\'\') > 0', null, false);
            // $this->db->where("CHARINDEX('" . $atasan . "', tbl_cuti.atasan)>0", null, false);
        }
		//lha cr-2451 Penambahan data time untuk pak Yan Biring (NIK dummy 90120053) di portal Kiranaku
		$this->db->where("tbl_cuti.nik!='90120053'");

        if (isset($manager)) {
            $this->db->group_start();
            if ($manager) {
                $this->db->where('tbl_user.id_level <= ', 9102);
            } else {
                $this->db->where('tbl_user.id_level > ', 9102);
            }
            $this->db->group_end();
        }

        $this->db->order_by($order_by);

        if ($limit > 0)
            $this->db->limit($limit);

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;

    }

    public function get_history(
        $id = null,
        $tanggal_awal = null,
        $tanggal_akhir = null,
        $id_tipe_status = null,
        $tipe = array('Cuti', 'Ijin'),
        $limit = 0
    )
    {
//        $tanggal_awal = date('d.m.Y', strtotime('-1 month'));
//        $tanggal_akhir = date('d.m.Y');

        $this->db->select('tbl_cuti.*');
        $this->db->select('tbl_cuti_status.warna');
        $this->db->select('tbl_cuti_status.nama as nama_status');
        $this->db->from('tbl_cuti');
        $this->db->join(
            'tbl_cuti_status',
            'tbl_cuti.id_cuti_status = tbl_cuti_status.id_cuti_status',
            'left outer'
        );
        $this->db->where('tbl_cuti.na', 'n');
        $this->db->where('tbl_cuti.del', 'n');
        $this->db->where('tbl_cuti_status.del', 'n');
        $this->db->where('tbl_cuti_status.na', 'n');

        if (isset($tipe))
            $this->db->where_in('tbl_cuti.form', $tipe);

        if (isset($id_tipe_status) && !empty($id_tipe_status)) {
            if (is_array($id_tipe_status))
                $this->db->where_in('tbl_cuti_status.id_cuti_status', $id_tipe_status);
            else
                $this->db->where('tbl_cuti_status.id_cuti_status', $id_tipe_status);
        } else
            $this->db->where_in('tbl_cuti_status.id_cuti_status', array(3, 4));

        $this->db->where('tbl_cuti.nik', base64_decode($this->session->userdata('-nik-')));

        if (isset($id) && !empty($id))
            $this->db->where('tbl_cuti.id_cuti', $id);

        if ($limit <> 0)
            $this->db->limit($limit);

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

}