<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Reservasi Ruangan Meeting - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dreservasiruangan extends CI_Model
{

    public function get_ruangan($id = null)
    {

        $this->db->select('*');
        $this->db->from('tbl_ruang');

        if (isset($id) && !empty($id))
            $this->db->where('tbl_ruang.id_ruang', $id);

        $this->db->where('tbl_ruang.del', 'n');
        $this->db->where('tbl_ruang.na', 'n');

        $this->db->order_by('tbl_ruang.nama');

        $query = $this->db->get();

        if (isset($id) && !empty($id))
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_reservasi($id = null)
    {

        $this->db->select('tbl_reservasi.*');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');
        $this->db->select('tbl_ruang.kapasitas');
        $this->db->select('tbl_ruang.fasilitas');
        $this->db->select('tbl_karyawan.telepon');
        $this->db->select('tbl_karyawan.gambar');
        $this->db->select('tbl_karyawan.id_karyawan');
        $this->db->select('tbl_karyawan.gender');

        $this->db->from('tbl_reservasi');

        $this->db->join('tbl_ruang', 'tbl_ruang.id_ruang=tbl_reservasi.id_ruang');

        $this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan=tbl_reservasi.id_karyawan', 'left outer');

        $this->db->where('tbl_reservasi.id_reservasi', $id);

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_reservasi_ruangan($id_ruangan, $tgl, $id_karyawan = null, $jam_awal = null)
    {

//        $this->db->select('count(tbl_reservasi.id_reservasi) as total_reservasi');
        $this->db->select('tbl_reservasi.id_reservasi');
        $this->db->select('tbl_reservasi.jumlah_kolom');
        $this->db->select('tbl_reservasi.tanggal');
        $this->db->select('tbl_reservasi.id_karyawan');
        $this->db->select('tbl_reservasi.keperluan');
        $this->db->select('tbl_reservasi.jam_awal');
        $this->db->select('tbl_reservasi.jam_akhir');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');
        $this->db->select('tbl_karyawan.telepon');
        $this->db->select('tbl_karyawan.gambar');
        $this->db->from('tbl_reservasi');
        $this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan=tbl_reservasi.id_karyawan', 'left outer');

        $this->db->where('tbl_reservasi.id_ruang', $id_ruangan);

        $this->db->where('tbl_reservasi.tanggal', $tgl);

        if (isset($id_karyawan))
            $this->db->where('tbl_karyawan.id_karyawan', $id_karyawan);

        if (isset($jam_awal))
            $this->db->where('tbl_reservasi.jam_awal >', $jam_awal);

        $this->db->where('tbl_reservasi.del', 'n');

//        $this->db->group_by('tbl_reservasi.tanggal');
//        $this->db->group_by('tbl_reservasi.id_karyawan');
//        $this->db->group_by('tbl_reservasi.id_ruang');
//        $this->db->group_by('tbl_reservasi.keperluan');
//        $this->db->group_by('tbl_reservasi.jumlah_kolom');
//        $this->db->group_by('tbl_karyawan.nama');
        $this->db->order_by('tbl_reservasi.tanggal', 'asc');

        $query = $this->db->get();

        $result = $query->result();

//        var_dump($this->db->last_query());

        return $result;
    }

    public function get_fasilitas()
    {

        $this->db->select('id_fasilitas,nama');
        $this->db->from('tbl_fasilitas');

        $this->db->where('tbl_fasilitas.del', 'n');

        $this->db->order_by('tbl_fasilitas.nama');

        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function get_jam($jam_awal = null, $jam_akhir = null)
    {

        $this->db->select('id_jam,jam_awal,jam_akhir');
        $this->db->from('tbl_jam2');

        $this->db->where('tbl_jam2.na', 'n');

        if (isset($jam_awal))
            $this->db->where('tbl_jam2.jam_awal >=', $jam_awal);

        if (isset($jam_akhir))
            $this->db->where('tbl_jam2.jam_akhir <=', $jam_akhir);

        $this->db->order_by('tbl_jam2.id_jam');

        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    public function get_jam_tersedia($jam_awal = null, $jam_akhir = null)
    {

        $this->db->select('id_jam,jam_awal,jam_akhir');
        $this->db->from('tbl_jam2');

        $this->db->where('tbl_jam2.na', 'n');

        if (isset($jam_awal))
            $this->db->where('tbl_jam2.jam_awal >=', $jam_awal);

        if (isset($jam_akhir) && !empty($jam_akhir))
            $this->db->where('tbl_jam2.jam_akhir <=', $jam_akhir);

        $this->db->order_by('tbl_jam2.id_jam');

        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }
}