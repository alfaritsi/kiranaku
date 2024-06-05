<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS Medical - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dmedical extends CI_Model
{
    /**
     * Get Medical Plafon
     *
     * @param array $params
     * array(
     *  'nik' => string|int, default = null, filter by nik
     *  'single_row' => boolean, default = false, mengambil single result atau tidak
     * )
     * @return array|object|null
     */
    public function get_plafon($params = array())
    {
        $nik = isset($params['nik']) ? $params['nik'] : null;

        $this->db->select('*');
        $this->db->from('tbl_fbk_plafon');

        if (isset($nik))
            $this->db->where('tbl_fbk_plafon.nik', $nik);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    /**
     * Get Medical FBK Cek (Masih belum tau fungsi dari tbl tbl_fbk_cek)
     *
     * @param array $params
     * array(
     *  'nik' => string|int, default = null, filter by nik
     *  'single_row' => boolean, default = false, mengambil single result atau tidak
     * )
     * @return array|object|null
     */
    public function get_fbk_cek($params = array())
    {
        $nik = isset($params['nik']) ? $params['nik'] : null;

        $this->db->select('*');
        $this->db->from('tbl_fbk_cek');

        $this->db->where('tbl_fbk_cek.na', 'n');
        $this->db->where('tbl_fbk_cek.del', 'n');
        if (isset($nik))
            $this->db->where('tbl_fbk_cek.nik', $nik);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    /**
     * Get Medical Golongan
     *
     * @param array $params
     * array(
     *  'id_golongan' => string, default = null, filter by id_golongan
     *  'single_row' => boolean, default = false, mengambil single result atau tidak
     * )
     * @return array|object|null
     */
    public function get_golongan($params = array())
    {

        $id_golongan = isset($params['id_golongan']) ? $params['id_golongan'] : null;

        $this->db->select('*');
        $this->db->from('tbl_fbk_golongan');

        $this->db->where('tbl_fbk_golongan.na', 'n');
        $this->db->where('tbl_fbk_golongan.del', 'n');
        if (isset($id_golongan))
            $this->db->where('tbl_fbk_golongan.id_golongan', $id_golongan);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    /**
     * Get Medical Data Keluarga
     *
     * @param array $params
     * array(
     *  'nik' => string|int, default = null, filter by nik
     *  'single_row' => boolean, default = false, mengambil single result atau tidak
     * )
     * @return array|object|null
     */
    public function get_keluarga($params = array())
    {
        $nik = isset($params['nik']) ? $params['nik'] : null;

        $this->db->select('tbl_keluarga.nama,tbl_keluarga.kode');
        $this->db->from('tbl_keluarga');
        if (isset($nik) && !empty($nik))
            $this->db->where('tbl_keluarga.nik', $nik);
        $this->db->where('tbl_keluarga.na', 'n');
        $this->db->where('tbl_keluarga.del', 'n');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    /**
     * Get Medical Jenis Sakit
     *
     * @param array $params
     * array(
     *  'all' => boolean, default = false, ambil semua data atau tidak
     *  'id' => int, default = null, filter by id_fbk_sakit
     *  'single_row' => boolean, default = false, mengambil single result atau tidak
     * )
     * @return array|object|null
     */
    public function get_jenis_sakit($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_fbk_sakit.id_fbk_sakit,tbl_fbk_sakit.nama');
        $this->db->from('tbl_fbk_sakit');
        if (!$all) {
            $this->db->where('tbl_fbk_sakit.na', 'n');
            $this->db->where('tbl_fbk_sakit.del', 'n');
        }

        if (isset($id))
            $this->db->where('tbl_fbk_sakit.id_fbk_sakit', $id);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    /**
     * Get Medical Rumah Sakit
     *
     * @param array $params
     * array(
     *  'all' => boolean, default = false, ambil semua data atau tidak
     *  'id' => int, default = null, filter by id_rs
     *  'single_row' => boolean, default = false, mengambil single result atau tidak
     * )
     * @return array|object|null
     */
    public function get_rumah_sakit($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_rs.id_rs,tbl_rs.nama');
        $this->db->from('tbl_rs');
        if (!$all) {
            $this->db->where('tbl_rs.na', 'n');
            $this->db->where('tbl_rs.del', 'n');
        }

        if (isset($id))
            $this->db->where('tbl_rs.id_rs', $id);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    /**
     * Get Medical FBK
     *
     * @param array $params
     * array(
     *  'all' => boolean, default = false, ambil semua data atau tidak
     *  'id' => int, default = null, filter by id
     *  'jenis' => string, default = null, filter by jenis fbk ex: inap, jalan, lensa etc
     *  'tahun' => string, default = null, filter by tahun
     *  'id_fbk_status' => array|int, default = null, filter by id_fbk_status pakai constants
     *      ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP,
     *      ESS_MEDICAL_STATUS_LENGKAP, ESS_MEDICAL_STATUS_DISETUJUI
     *  'bulan_tahun' => string, default = null, filter field nomor by bulan & tahun format 'm/Y'
     *  'tanggal_awal' => date('Y-m-d'), default = null, filter by tanggal awal untuk data sesudah tanggal_awal
     *  'tanggal_akhir' => date('Y-m-d'), default = null, filter by tanggal untuk data sebelum tanggal_akhir
     *  'id_user' => int, default = null, filter by id_user
     *  'ho' => boolean, default = null, filter data by HO atau tidak
     *  'order_by' => string, default = 'id_fbk DESC', order by
     *  'single_row' => boolean, default = false, mengambil single result atau tidak
     * )
     * @return array|object|null
     */
    public function get_fbk($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;
        $jenis = isset($params['jenis']) && !empty($params['jenis']) ? $params['jenis'] : null;
        $tahun = isset($params['tahun']) ? $params['tahun'] : null;
        $id_fbk_status = isset($params['id_fbk_status']) ? $params['id_fbk_status'] : null;
        $bulan_tahun = isset($params['bulan_tahun']) ? $params['bulan_tahun'] : null;
        $tanggal_awal = isset($params['tanggal_awal']) ? $params['tanggal_awal'] : null;
        $tanggal_akhir = isset($params['tanggal_akhir']) ? $params['tanggal_akhir'] : null;
        $id_user = isset($params['id_user']) ? $params['id_user'] : null;
        $ho = isset($params['ho']) ? $params['ho'] : null;
        $manager = isset($params['manager']) ? $params['manager'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'id_fbk DESC';

        $this->db->select('tbl_fbk.*');
        $this->db->select('tbl_fbk_status.warna,tbl_fbk_status.nama as nama_status');
        $this->db->select('tbl_karyawan.nik,tbl_karyawan.nama as nama_karyawan');
        $this->db->from('tbl_fbk');
        $this->db->join('tbl_user', 'tbl_fbk.login_buat=tbl_user.id_user', 'left outer');
        $this->db->join('tbl_karyawan', 'tbl_user.id_karyawan=tbl_karyawan.id_karyawan', 'left outer');
        $this->db->join('tbl_fbk_status', 'tbl_fbk.id_fbk_status=tbl_fbk_status.id_fbk_status', 'left outer');

        if (!$all) {
            $this->db->where('tbl_fbk.na', 'n');
            $this->db->where('tbl_fbk.del', 'n');
            $this->db->where('tbl_fbk_status.na', 'n');
            $this->db->where('tbl_fbk_status.del', 'n');
            $this->db->where('tbl_karyawan.na', 'n');
            $this->db->where('tbl_karyawan.del', 'n');
            $this->db->where('tbl_user.na', 'n');
            $this->db->where('tbl_user.del', 'n');
        }

        if (isset($jenis) && !empty($jenis))
            $this->db->where('tbl_fbk.fbk_jenis', $jenis);

        if (isset($id) && !empty($id))
            $this->db->where('tbl_fbk.id_fbk', $id);

        if (isset($tahun))
            $this->db->where("cast(year(tbl_fbk.tanggal_buat) as varchar(4)) = ", $tahun);

        if (isset($bulan_tahun))
            $this->db->like("tbl_fbk.nomor", $bulan_tahun, 'before');

        if (isset($id_user))
            $this->db->where("tbl_fbk.login_buat", $id_user);

        if (isset($ho))
            $this->db->where("tbl_karyawan.ho", $ho);

        if (isset($id_fbk_status)) {
            if (is_array($id_fbk_status))
                $this->db->where_in("tbl_fbk.id_fbk_status", $id_fbk_status);
            else
                $this->db->where("tbl_fbk.id_fbk_status", $id_fbk_status);
        }

        if (
        (isset($tanggal_awal) && !empty($tanggal_awal) && isset($tanggal_akhir) && !empty($tanggal_akhir))
        ) {
            if (isset($tanggal_awal))
                $this->db->where('convert(date,tbl_fbk.tanggal_buat) >=', $tanggal_awal);
            if (isset($tanggal_akhir))
                $this->db->where('convert(date,tbl_fbk.tanggal_buat) <=', $tanggal_akhir);
        }

        if (isset($manager)) {
            $this->db->group_start();
            if($manager){
//                $this->db->like("tbl_karyawan.posst", 'ceo');
//                $this->db->or_like("tbl_karyawan.posst", 'direktur operasional');
//                $this->db->or_like("tbl_karyawan.posst", 'manager');
                $this->db->where('tbl_user.id_level <= ', 9102);
            } else {
//                $this->db->not_like("tbl_karyawan.posst", 'ceo');
//                $this->db->not_like("tbl_karyawan.posst", 'direktur operasional');
//                $this->db->not_like("tbl_karyawan.posst", 'manager');
                $this->db->where('tbl_user.id_level > ', 9102);
            }
            $this->db->group_end();
        }

        if (isset($order_by))
            $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();


        if (is_array($result)) {
            foreach ($result as $index => $list) {
                $result[$index] = $this->get_fbk_additional_column($list, $params, $all);;
            }
        } else {
            if (isset($result))
                $result = $this->get_fbk_additional_column($result, $params, $all);
        }


        return $result;
    }

    /**
     * Get Additional Column untuk data FBK
     *
     * @param $data
     *  Result data dari fungsi $this->get_fbk()
     * @param array $params
     * array(
     *  'kwitansi' => boolean, default = false, ambil data kwitansi atau tidak
     * )
     * @param null|boolean $all
     *  default = null, ambil semua data atau tidak
     * @return array|object|null
     */
    private function get_fbk_additional_column($data, $params, $all = null)
    {
        $jenis_nama = json_decode(ESS_MEDICAL_JENIS, true);

        /** Tambah Kolom ID yang terenkripsi **/
        $data->enId = $this->generate->kirana_encrypt($data->id_fbk);

        /** Tambah Kolom Detail Kwitansi **/
        $data->total_kwitansi_disetujui = 0;
        if (isset($params['kwitansi']) && $params['kwitansi']) {
            $kwitansi = $this->get_fbk_kwitansi(array(
                'id_fbk' => $data->id_fbk,
                'all' => $all
            ));
            $kwitansi_disetujui = array_filter($kwitansi, function ($k) {
                return ($k->disetujui == 'y');
            });
            $data->kwitansi = $kwitansi;
            $data->kwitansi_disetujui = $kwitansi_disetujui;

            foreach ($data->kwitansi_disetujui as $kwd)
                $data->total_kwitansi_disetujui += $kwd->amount_kwitansi;
        }

        /** Tambah Kolom Nama Jenis **/
        $data->fbk_jenis_nama = $jenis_nama[$data->kode];

        /** Tambah Kolom Hitungan Sisa Plafon **/

        switch ($data->fbk_jenis) {
            case "jalan":
                $data->sisa_plafon_awal = $data->plafon_medical;
                $data->sisa_plafon_akhir = $data->sisa_plafon_awal - $data->total_kwitansi;
                break;
            case "inap":
                if ($data->plafon_kamar != 999999999 && $data->plafon_kamar != null) {
                    $data->sisa_plafon_awal = $data->plafon_kamar;
                    $data->sisa_plafon_akhir = $data->plafon_kamar - $data->biaya_kamar;
                } else {
                    $data->sisa_plafon_awal = '-';
                    $data->sisa_plafon_akhir = '-';
                }
                break;
            case "lensa":
                $data->sisa_plafon_awal = $data->plafon_lensa;
                $data->sisa_plafon_akhir = $data->plafon_lensa - $data->total_kwitansi;
                break;
            case "frame":
                $data->sisa_plafon_awal = $data->plafon_frame;
                $data->sisa_plafon_akhir = $data->plafon_frame - $data->total_kwitansi;
                break;
            case "bersalin":
                $data->sisa_plafon_awal = $data->plafon_persalinan;
                $data->sisa_plafon_akhir = $data->plafon_persalinan - $data->total_kwitansi;
                break;
        }
        return $data;
    }

    /**
     * Get Medical FBK Kwitansi
     *
     * @param array $params
     * array(
     *  'all' => boolean, default = false, ambil semua data atau tidak
     *  'id' => int, default = null, filter by id_fbk_kwitansi
     *  'id_fbk' => int, default = null, filter by id_fbk
     *  'jenis' => string, default = null, filter by jenis fbk ex: inap, jalan, lensa etc
     *  'tahun' => string, default = null, filter by tahun
     *  'nomor_kwitansi' => string, default = null, filter by nomor_kwitansi
     *  'id_fbk_status' => array|int, default = null, filter by id_fbk_status pakai constants
     *      ESS_MEDICAL_STATUS_MENUNGGU, ESS_MEDICAL_STATUS_TDK_LENGKAP,
     *      ESS_MEDICAL_STATUS_LENGKAP, ESS_MEDICAL_STATUS_DISETUJUI
     *  'tahun' => string|int, default = null, filter field nomor by tahun format 'Y'
     *  'tanggal_after' => date('Y-m-d'), default = null, filter by tanggal awal untuk data sesudah tanggal_after
     *  'tanggal_before' => date('Y-m-d'), default = null, filter by tanggal untuk data sebelum tanggal_before
     *  'nik' => int, default = null, filter by nik
     *  'ho' => boolean, default = null, filter data by HO atau tidak
     *  'order_by' => string, default = 'tbl_fbk_kwitansi.id_fbk_kwitansi ASC', order by
     *  'single_row' => boolean, default = false, mengambil single result atau tidak
     * )
     * @return array|object|null
     */
    public function get_fbk_kwitansi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;
        $id_fbk = isset($params['id_fbk']) ? $params['id_fbk'] : null;
        $id_before = isset($params['id_before']) ? $params['id_before'] : null;
        $id_fbk_status = isset($params['id_fbk_status']) ? $params['id_fbk_status'] : null;
        $kode = isset($params['kode']) ? $params['kode'] : null;
        $nomor_kwitansi = isset($params['nomor_kwitansi']) ? $params['nomor_kwitansi'] : null;
        $amount_kwitansi = isset($params['amount_kwitansi']) ? $params['amount_kwitansi'] : null;
        $amount_ganti = isset($params['amount_ganti']) ? $params['amount_ganti'] : null;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $tahun = isset($params['tahun']) ? $params['tahun'] : null;
        $tanggal_kwitansi = isset($params['tanggal_kwitansi']) ? $params['tanggal_kwitansi'] : null;
        $tanggal_before = isset($params['tanggal_before']) ? $params['tanggal_before'] : null;
        $tanggal_after = isset($params['tanggal_after']) ? $params['tanggal_after'] : null;
        $disetujui = isset($params['disetujui']) ? $params['disetujui'] : null;
        $disetujui_hr = isset($params['disetujui_hr']) ? $params['disetujui_hr'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'tbl_fbk_kwitansi.id_fbk_kwitansi ASC';

        $this->db->select('tbl_fbk_kwitansi.*,tbl_fbk.id_fbk_status');
        $this->db->from('tbl_fbk_kwitansi');
        $this->db->join('tbl_fbk', 'tbl_fbk.id_fbk = tbl_fbk_kwitansi.id_fbk', 'left outer');

        if (!$all) {
            $this->db->where('tbl_fbk_kwitansi.na', 'n');
            $this->db->where('tbl_fbk_kwitansi.del', 'n');
            $this->db->where('tbl_fbk.na', 'n');
            $this->db->where('tbl_fbk.del', 'n');
        }

        if (isset($nik))
            $this->db->where("tbl_fbk_kwitansi.nik", $nik);

        if (isset($nomor_kwitansi))
            $this->db->where("LTRIM(RTRIM(tbl_fbk_kwitansi.nomor_kwitansi))", $nomor_kwitansi);

        if (isset($kode))
        {
            if(is_array($kode))
                $this->db->where_in("tbl_fbk_kwitansi.kode", $kode);
            else
                $this->db->where("tbl_fbk_kwitansi.kode", $kode);
        }

        if (isset($amount_kwitansi))
            $this->db->where("tbl_fbk_kwitansi.amount_kwitansi", $amount_kwitansi);

        if (isset($amount_ganti))
            $this->db->where("tbl_fbk_kwitansi.amount_ganti", $amount_ganti);

        if (isset($tanggal_kwitansi))
            $this->db->where("tbl_fbk_kwitansi.tanggal_kwitansi", $tanggal_kwitansi);

        if (isset($tanggal_before))
            $this->db->where("convert(date,tbl_fbk_kwitansi.tanggal_buat) <= ", $tanggal_before);

        if (isset($tanggal_after))
            $this->db->where("cast(tbl_fbk_kwitansi.tanggal_buat as date) >= ", $tanggal_after);

        if (isset($id_before))
            $this->db->where("tbl_fbk.id_fbk <= ", $id_before);

        if (isset($tahun))
            $this->db->where("cast(year(tbl_fbk_kwitansi.tanggal_buat) as varchar(4)) = ", $tahun);

        if (isset($id))
            $this->db->where("tbl_fbk_kwitansi.id_fbk_kwitansi", $id);

        if (isset($id_fbk))
            $this->db->where("tbl_fbk_kwitansi.id_fbk", $id_fbk);

        if (isset($id_fbk_status)) {
            if (is_array($id_fbk_status))
                $this->db->where_in("tbl_fbk.id_fbk_status", $id_fbk_status);
            else
                $this->db->where("tbl_fbk.id_fbk_status", $id_fbk_status);
        }

        if (isset($disetujui)) {
            if ($disetujui) {
                $this->db->where("tbl_fbk_kwitansi.disetujui", 'y');
            } else {
                $this->db->where("tbl_fbk_kwitansi.disetujui != 'y'", null, false);
            }
        }

        if (isset($disetujui_hr)) {
            $this->db->group_start();
            if ($disetujui_hr) {
                $this->db->where("tbl_fbk_kwitansi.disetujui", 'y');
                $this->db->where("tbl_fbk_kwitansi.status_migrasi", 'A');
            } else {
                $this->db->where("tbl_fbk_kwitansi.disetujui != ", 'y');
                $this->db->or_where("tbl_fbk_kwitansi.status_migrasi !=", 'A');
            }
            $this->db->group_end();
        }

        if (isset($order_by))
            $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    /**
     * Get Medical Plafon
     *
     * @param array $params
     * array(
     *  'nik' => string|int, default = null, filter by nik
     *  'single_row' => boolean, default = false, mengambil single result atau tidak
     * )
     * @return array|object|null
     */
    public function get_plafon_kamar($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;
        $id_golongan = isset($params['id_golongan']) ? $params['id_golongan'] : null;

        $this->db->select('tbl_fbk_plafon_kamar.*');
        $this->db->from('tbl_fbk_plafon_kamar');

        if (!$all) {
            $this->db->where('tbl_fbk_plafon_kamar.na', 'n');
            $this->db->where('tbl_fbk_plafon_kamar.del', 'n');
        }

        if (isset($id))
            $this->db->where("tbl_fbk_plafon_kamar.id_fbk_kwitansi", $id);

        if (isset($id_golongan))
            $this->db->where("tbl_fbk_plafon_kamar.id_golongan", $id_golongan);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_fbk_cutoff($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;
        $tahun = isset($params['tahun']) ? $params['tahun'] : null;

        $this->db->select('tbl_fbk_cutoff.*');
        $this->db->select('convert(varchar, tbl_fbk_cutoff.jadwal, 120) as jadwal');
        $this->db->from('tbl_fbk_cutoff');

        if (!$all) {
            $this->db->where('tbl_fbk_cutoff.na', 'n');
            $this->db->where('tbl_fbk_cutoff.del', 'n');
        }

        if (isset($id))
            $this->db->where("tbl_fbk_cutoff.id_fbk_cutoff", $id);

        if (isset($tahun))
            $this->db->where("tbl_fbk_cutoff.tahun", $tahun);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
}