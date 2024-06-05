<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS General - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dessgeneral extends CI_Model
{
    public function get_cuti_status($filter_id = null)
    {
        $this->db->select('tbl_cuti_status.*');
        $this->db->from('tbl_cuti_status');
        if (isset($filter_id)) {
            if (is_array($filter_id))
                $this->db->where_in('id_cuti_status', $filter_id);
            else
                $this->db->where('id_cuti_status', $filter_id);
        }

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    public function get_fbk_status($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_fbk_status.id_fbk_status,tbl_fbk_status.nama,tbl_fbk_status.warna');
        $this->db->from('tbl_fbk_status');
        if (!$all) {
            $this->db->where('tbl_fbk_status.na', 'n');
            $this->db->where('tbl_fbk_status.del', 'n');
        }

        if (isset($id))
            $this->db->where('tbl_fbk_status.id_fbk_status', $id);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_history_persetujuan($id = null, $id_cuti_status = null)
    {
        $result = array();
        if (isset($id)) {
            $this->db->select('h.*, k.nama as nama_author, cs.nama as nama_status');
            $this->db->select('k.ho');
            $this->db->select("tbl_departemen.nama as nama_departemen");
            $this->db->select("tbl_divisi.nama as nama_divisi");
            $this->db->select("tbl_sub_divisi.nama as nama_sub_divisi");
            $this->db->select("tbl_seksi.nama as nama_seksi");
            $this->db->from('tbl_cuti_history h');
            $this->db->join('tbl_user u', 'u.id_user = h.login_buat', 'left outer');
            $this->db->join('tbl_departemen', 'tbl_departemen.id_departemen= u.id_departemen', 'left outer');
            $this->db->join('tbl_divisi', 'tbl_divisi.id_divisi= u.id_divisi', 'left outer');
            $this->db->join('tbl_sub_divisi', 'tbl_sub_divisi.id_sub_divisi= u.id_sub_divisi', 'left outer');
            $this->db->join('tbl_seksi', 'tbl_seksi.id_seksi= u.id_seksi', 'left outer');
            $this->db->join('tbl_karyawan k', 'k.id_karyawan=u.id_karyawan', 'left outer');
            $this->db->join('tbl_cuti_status cs', 'cs.id_cuti_status=h.id_cuti_status', 'left outer');
            $this->db->where('h.id_cuti', $id);
            if (isset($id_cuti_status)) {
                if (is_array($id_cuti_status))
                    $this->db->where_in('h.id_cuti_status', $id_cuti_status);
                else
                    $this->db->where('h.id_cuti_status', $id_cuti_status);
            }

            $query = $this->db->get();
            $result = $query->result();
        }
        return $result;
    }

    public function get_medical_history($params = array())
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $id_fbk = isset($params['id_fbk']) ? $params['id_fbk'] : null;
        $id_fbk_status = isset($params['id_fbk_status']) ? $params['id_fbk_status'] : null;

        $this->db->select('h.*, k.nama as nama_author, s.nama as nama_status');
        $this->db->select('k.ho');
        $this->db->select("tbl_departemen.nama as nama_departemen");
        $this->db->select("tbl_divisi.nama as nama_divisi");
        $this->db->select("tbl_sub_divisi.nama as nama_sub_divisi");
        $this->db->select("tbl_seksi.nama as nama_seksi");
        $this->db->from('tbl_fbk_history h');
        $this->db->join('tbl_fbk_status s', 's.id_fbk_status= h.id_fbk_status', 'left outer');
        $this->db->join('tbl_user u', 'u.id_user = h.login_buat', 'left outer');
        $this->db->join('tbl_departemen', 'tbl_departemen.id_departemen= u.id_departemen', 'left outer');
        $this->db->join('tbl_divisi', 'tbl_divisi.id_divisi= u.id_divisi', 'left outer');
        $this->db->join('tbl_sub_divisi', 'tbl_sub_divisi.id_sub_divisi= u.id_sub_divisi', 'left outer');
        $this->db->join('tbl_seksi', 'tbl_seksi.id_seksi= u.id_seksi', 'left outer');
        $this->db->join('tbl_karyawan k', 'k.id_karyawan=u.id_karyawan', 'left outer');

        if (isset($id))
            $this->db->where("h.id_fbk_history", $id);

        if (isset($id_fbk))
            $this->db->where("h.id_fbk", $id_fbk);

        if (isset($id_fbk_status))
            $this->db->where("h.id_fbk_status", $id_fbk_status);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_bak_history($params = array())
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $id_bak = isset($params['id_bak']) ? $params['id_bak'] : null;
        $id_bak_status = isset($params['id_fbk_status']) ? $params['id_bak_status'] : null;

        $this->db->select('h.*, k.nama as nama_author, s.nama as nama_status');
        $this->db->select('k.ho');
        $this->db->select("tbl_departemen.nama as nama_departemen");
        $this->db->select("tbl_divisi.nama as nama_divisi");
        $this->db->select("tbl_sub_divisi.nama as nama_sub_divisi");
        $this->db->select("tbl_seksi.nama as nama_seksi");
        $this->db->from('tbl_bak_history h');
        $this->db->join('tbl_bak_status s', 's.id_bak_status= h.id_bak_status', 'left outer');
        $this->db->join('tbl_user u', 'u.id_user = h.login_buat', 'left outer');
        $this->db->join('tbl_departemen', 'tbl_departemen.id_departemen= u.id_departemen', 'left outer');
        $this->db->join('tbl_divisi', 'tbl_divisi.id_divisi= u.id_divisi', 'left outer');
        $this->db->join('tbl_sub_divisi', 'tbl_sub_divisi.id_sub_divisi= u.id_sub_divisi', 'left outer');
        $this->db->join('tbl_seksi', 'tbl_seksi.id_seksi= u.id_seksi', 'left outer');
        $this->db->join('tbl_karyawan k', 'k.id_karyawan=u.id_karyawan', 'left outer');

        if (isset($id))
            $this->db->where("h.id_bak_history", $id);

        if (isset($id_bak))
            $this->db->where("h.id_bak", $id_bak);

        if (isset($id_bakstatus))
            $this->db->where("h.id_bak_status", $id_bak_status);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_cuti_ijin($params = array())
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $all = isset($params['all']) ? $params['all'] : false;
        $exclude = isset($params['exclude']) ? $params['exclude'] : array(
            '0530', // Mangkir
            '0610', // Perjalanan Dinas
            '0620', // Training
            '0630', // Meeting
            '0110', // Cuti Tahunan
            '0120', // Cuti Bersama
            '0240', // Sakit berkepanjangan
            '0250', // Keguguran
            '0260', // Kecelakaan Kerja
            '0370', // Force Majeure
            '0400', // Ditahan pihak berwajib
            '0410' // Skorsing
        );
        $kode = isset($params['kode']) ? $params['kode'] : null;
        $kode_grouping_area = isset($params['kode_grouping_area']) ? $params['kode_grouping_area'] : ESS_CUTI_GROUP_AREA_KMTR;

        $this->db->select('tbl_cuti_ijin.id_cuti_ijin');
        $this->db->select('tbl_cuti_ijin.kode');
        $this->db->select('tbl_cuti_ijin.kode_grouping_area');
        $this->db->select('tbl_cuti_ijin.nama');
        $this->db->select('tbl_cuti_ijin.jumlah');
        $this->db->select('tbl_cuti_ijin.na');
        $this->db->from('tbl_cuti_ijin');
        if (!$all) {
            $this->db->where('del', 'n');
            $this->db->where('na', 'n');
        }
        if (isset($id) && !empty($id))
            $this->db->where('id_cuti_ijin', $id);

        if (isset($kode))
            $this->db->where('tbl_cuti_ijin.kode', $kode);

        if (isset($kode_grouping_area))
            $this->db->where('tbl_cuti_ijin.kode_grouping_area', $kode_grouping_area);

        if (isset($exclude) && $exclude !== false)
            $this->db->where_not_in('kode', $exclude);

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_cuti_cuti($params = array())
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $kode = isset($params['kode']) ? $params['kode'] : null;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $all = isset($params['all']) ? $params['all'] : false;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'tanggal_akhir asc';

        $this->db->select('tbl_cuti_cuti.*');
        $this->db->from('tbl_cuti_cuti');

        if (!$all) {
            $this->db->where('tbl_cuti_cuti.del', 'n');
            $this->db->where('tbl_cuti_cuti.na', 'n');
        }
        if (isset($id) && !empty($id))
            $this->db->where('tbl_cuti_cuti.id_cuti_cuti', $id);

        if (isset($kode))
            $this->db->where('tbl_cuti_cuti.kode', $kode);

        if (isset($nik))
            $this->db->where('tbl_cuti_cuti.nik', $nik);

        $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_saldo_nik($nik = null, $order_by = 'tanggal_akhir', $order = 'asc', $tanggal_akhir = null)
    {
        $this->db->select('*');
        $this->db->from('tbl_cuti_cuti');
        $this->db->where('tbl_cuti_cuti.na', 'n');
        $this->db->where('tbl_cuti_cuti.del', 'n');
        if (isset($nik) && !empty($nik))
            $this->db->where('tbl_cuti_cuti.nik', $nik);
        else
            $this->db->where('tbl_cuti_cuti.nik', base64_decode($this->session->userdata('-nik-')));

        if (isset($tanggal_akhir) && !empty($tanggal_akhir))
            $this->db->where('tbl_cuti_cuti.tanggal_akhir >=', $tanggal_akhir);

        $this->db->order_by($order_by, $order);

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    public function nik_departemen($id_departemen)
    {
        $this->db->select('tbl_atasan.*');
        $this->db->from('tbl_atasan');
        $this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_atasan.nik', 'left outer');
        $this->db->where('tbl_atasan.id_departemen', $id_departemen);
        $this->db->where('tbl_atasan.na', 'n');

        $query = $this->db->get();
        $result = $query->result();

        $nik = "";

        foreach ($result as $value) {
            $nik .= $value->nik . '.';
        }

        return $nik;
    }

    public function nik_bagian($id_bagian)
    {
        $this->db->select('tbl_atasan.*');
        $this->db->from('tbl_atasan');
        $this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_atasan.nik', 'left outer');
        $this->db->where('tbl_atasan.id_departemen', $id_bagian);
        $this->db->where('tbl_atasan.na', 'n');

        $query = $this->db->get();
        $result = $query->result();

        $nik = "";

        foreach ($result as $value) {
            $nik .= $value->nik . '.';
        }

        return $nik;
    }

    public function nik_atasan($id_bagian)
    {
        $this->db->select('tbl_atasan.*');
        $this->db->from('tbl_atasan');
        $this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_atasan.nik', 'left outer');
        $this->db->where('tbl_atasan.id_departemen', $id_bagian);
        $this->db->where('tbl_atasan.na', 'n');

        $query = $this->db->get();
        $result = $query->row();
        if (isset($result->id_atasan))
            return $result->id_atasan;
        else
            return null;
    }

    public function get_atasan_master($nik)
    {
        $this->db->select('tbl_atasan_master.*');
        $this->db->from('tbl_atasan_master');
        if (isset($nik) && !empty($nik))
            $this->db->where('tbl_atasan_master.nik', $nik);
        $this->db->where('tbl_atasan_master.na', 'n');

        $query = $this->db->get();
        $result = $query->row();

        return $result;
    }

    public function get_user($id_level = null, $id_departemen = null, $id_user = null, $nik = null)
    {
        $this->db->select('tbl_user.*');
        $this->db->select('tbl_karyawan.nik');
        $this->db->select('tbl_karyawan.moabw');
        $this->db->from('tbl_user');
        $this->db->join('tbl_karyawan', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan', 'left outer');
        if (isset($id_departemen) && !empty($id_departemen))
            $this->db->where('tbl_user.id_departemen', $id_departemen);
        if (isset($id_level) && !empty($id_level))
            $this->db->where('tbl_user.id_level', $id_level);
        if (isset($id_user) && !empty($id_user))
            $this->db->where('tbl_user.id_user', $id_user);
        if (isset($nik) and !empty($nik))
            $this->db->where('tbl_user.id_karyawan', $nik);
        $this->db->where('tbl_user.na', 'n');

        $query = $this->db->get();
        $result = $query->row();

        return $result;
    }

    public function get_karyawan($id_karyawan = null, $ho = false)
    {
        $this->db->select('tbl_karyawan.*,tbl_user.id_golongan, tbl_user.id_level, tbl_karyawan.endact as tanggal_tetap');
        $this->db->from('tbl_karyawan');
        $this->db->join('tbl_user', 'tbl_user.id_karyawan=tbl_karyawan.id_karyawan', 'left outer');
        if (isset($id_karyawan) && !empty($id_karyawan))
            $this->db->where('tbl_karyawan.id_karyawan', $id_karyawan);
        $this->db->where('tbl_karyawan.na', 'n');
        $this->db->where('tbl_karyawan.del', 'n');
        $this->db->where('tbl_user.na', 'n');
        $this->db->where('tbl_user.del', 'n');
        if ($ho)
            $this->db->where('ho', 'y');

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_karyawans(
        $id_karyawan = null,
        $ho = false,
        $id_departemen = null,
        $id_divisi = null,
        $id_direktorat = null,
        $id_level = null
    )
    {

        $this->db->select("tbl_karyawan.*,tbl_departemen.nama as nama_departemen");
        $this->db->select("tbl_divisi.nama as nama_divisi");
        $this->db->select("tbl_sub_divisi.nama as nama_sub_divisi");
        $this->db->select("tbl_seksi.nama as nama_seksi");
        $this->db->select("tbl_wf_master_plant.plant_name as nama_pabrik");
        $this->db->from('tbl_karyawan');
        $this->db->join('tbl_user', 'tbl_user.id_karyawan=tbl_karyawan.id_karyawan', 'left outer');
        $this->db->join('tbl_level', 'tbl_user.id_level= tbl_level.id_level', 'left outer');
        $this->db->join('tbl_departemen', 'tbl_departemen.id_departemen= tbl_user.id_departemen', 'left outer');
        $this->db->join('tbl_divisi', 'tbl_divisi.id_divisi= tbl_user.id_divisi', 'left outer');
        $this->db->join('tbl_sub_divisi', 'tbl_sub_divisi.id_sub_divisi= tbl_user.id_sub_divisi', 'left outer');
        $this->db->join('tbl_seksi', 'tbl_seksi.id_seksi= tbl_user.id_seksi', 'left outer');
        $this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant= tbl_karyawan.gsber', 'left outer');

        $this->db->where('tbl_karyawan.na', 'n');

        if (!in_array($id_karyawan, array(5508, 5691, 5640))) {
            if (
                $id_level == 3
                or $id_level == 1
                or $id_departemen == 797
//                or base64_decode($this->session->userdata('-nik-')) == 7143
            ) {

            } else if ($id_level == 9100) {
                $this->db->where('tbl_user.id_direktorat', $id_direktorat);
            } else if ($id_level == 9101) {
                $this->db->where('tbl_user.id_divisi', $id_divisi);
            } else if ($id_level == 9102) {
                $this->db->where('tbl_user.id_departemen', $id_departemen);
            } else {
                $this->db->where('tbl_user.id_karyawan', $id_karyawan);
            }
        }

        if ($ho)
            $this->db->where('tbl_karyawan.ho', 'y');

        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function get_personal_area($params = array())
    {
        $this->db->select('moabw');
        $this->db->from('tbl_karyawan');
        $this->db->group_by('moabw');
        $this->db->order_by('moabw');

        $query = $this->db->get();

        return $query->result();
    }

    public function get_libur($nik = null, $tanggal_awal = null, $tanggal_akhir = null, $tanggal_merah = false)
    {
        $this->db->select('ZDMTM0002.DATUM as tanggal');
        $this->db->from('ZDMTM0002');

        if(!$tanggal_merah)
        {
            $this->db->where('ZDMTM0002.TPKLA', '0');
        }else{
            $this->db->join('ZDMTM0001','ZDMTM0001.PERNR = ZDMTM0002.PERNR AND ZDMTM0001.DATUM = ZDMTM0002.DATUM', 'left outer');
            $this->db->where('ZDMTM0002.VTART', '02');
            $this->db->where('ZDMTM0002.DATUM >=', date_create()->modify('-3 month')->format('Y-m-d'));
            $this->db->where('ZDMTM0002.DATUM <=', date_create()->modify('+12 month')->format('Y-m-d'));
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('ZDMTM0001.SOBEG', '00:00:00');
            $this->db->where('ZDMTM0001.SOEND', '00:00:00');
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where('ZDMTM0001.SOBEG', NULL);
            $this->db->where('ZDMTM0001.SOEND', NULL);
            $this->db->group_end();
            $this->db->group_end();
        }

        $this->db->where('ZDMTM0002.PERNR', str_pad($nik, 8, 0, STR_PAD_LEFT), 'before');

        if (isset($tanggal_awal) && !empty($tanggal_awal))
            $this->db->where('ZDMTM0002.DATUM >=', $tanggal_awal);

        if (isset($tanggal_akhir) && !empty($tanggal_akhir))
            $this->db->where('ZDMTM0002.DATUM <=', $tanggal_akhir);

        $this->db->group_by('ZDMTM0002.DATUM');
        $query = $this->db->get();

        $result = $query->result_array();

        return $result;
    }

    public function get_dinas($nik = null, $tanggal_awal = null, $tanggal_akhir = null)
    {
        $this->db->select('tbl_bak.*');
        $this->db->from('tbl_bak');

        $this->db->where('tbl_bak.nik', $nik);
        $this->db->group_start();
        $this->db->where_in('tbl_bak.tipe', array(ESS_CUTI_JENIS_DINAS, ESS_CUTI_JENIS_TRAINING, ESS_CUTI_JENIS_MEETING));
        $this->db->group_end();

        if (isset($tanggal_awal) && !empty($tanggal_awal))
            $this->db->where('tbl_bak.tanggal_absen >=', $tanggal_awal);

        if (isset($tanggal_akhir) && !empty($tanggal_akhir))
            $this->db->where('tbl_bak.tanggal_absen <=', $tanggal_akhir);

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    public function get_jumlah_hari($tanggal_awal = null, $tanggal_akhir = null)
    {
        $begin = new DateTime($tanggal_awal);
        $end = new DateTime($tanggal_akhir);
        $end->setTime(0, 0, 1);

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $dates = array();
        foreach ($period as $dt) {
            $dates[] = $dt->format("Y-m-d");
        }

        return $dates;
    }

}