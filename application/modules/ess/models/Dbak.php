<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : ESS BAK - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dbak extends CI_Model
{
    public function get_bak($params)
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $id = isset($params['id']) ? $params['id'] : null;
        $id_bak_status = isset($params['id_bak_status']) ? $params['id_bak_status'] : null;
        $tanggal_absen = isset($params['tanggal_absen']) ? $params['tanggal_absen'] : null;
        $tanggal_awal = isset($params['tanggal_awal']) ? $params['tanggal_awal'] : null;
        $tanggal_akhir = isset($params['tanggal_akhir']) ? $params['tanggal_akhir'] : null;
        $tipe = isset($params['tipe']) ? $params['tipe'] : null;
        $tipe_exclude = isset($params['tipe_exclude']) ? $params['tipe_exclude'] : null;
        $jenis = isset($params['jenis']) ? $params['jenis'] : null;
        $ho = isset($params['ho']) ? $params['ho'] : null;
        $manager = isset($params['manager']) ? $params['manager'] : null;
        $atasan = isset($params['atasan']) ? $params['atasan'] : null;
        $new_method = isset($params['new_method']) ? $params['new_method'] : null;
        $jadwal_absen = isset($params['jadwal_absen']) ? $params['jadwal_absen'] : null;
        $jadwal_absen_masuk = isset($params['jadwal_absen_masuk']) ? $params['jadwal_absen_masuk'] : null;
        $jadwal_absen_keluar = isset($params['jadwal_absen_keluar']) ? $params['jadwal_absen_keluar'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'tanggal_absen ASC';

        $lengkap = isset($params['lengkap']) ? $params['lengkap'] : null;

        $this->db->select('tbl_bak.*,tbl_bak_status.nama as nama_status, tbl_bak_status.warna');
        $this->db->select('tbl_karyawan.id_karyawan,tbl_karyawan.nama as nama_karyawan');
        $this->db->select('convert(varchar(10),tbl_bak.tanggal_migrasi,126) as tanggal_migrasi');
//        $this->db->select('DATUM,ENTUM,SOBEG,SOEND');
        $this->db->from('tbl_bak');
        $this->db->join('tbl_bak_status', 'tbl_bak.id_bak_status = tbl_bak_status.id_bak_status', 'left outer');
        $this->db->join('tbl_karyawan', 'tbl_bak.nik=tbl_karyawan.id_karyawan', 'left outer');
        $this->db->join('tbl_user', 'tbl_karyawan.id_karyawan=tbl_user.id_karyawan', 'left outer');
//        $this->db->join(DB_DEFAULT . '.dbo.ZDMTM0001', 'RIGHT(\'00000000\' + CAST(tbl_karyawan.nik AS VARCHAR(8)),8) = PERNR
//					AND CONVERT(CHAR(8),tanggal_absen,112) = CONVERT(CHAR(8),DATUM,112) AND CONVERT(CHAR(8),DATUM,112) >= \'20190101\'', 'left', false);
//        $this->db->join('[10.0.0.32].SAPSYNC.dbo.ZDMTM0001', 'RIGHT(\'00000000\' + CAST(tbl_karyawan.nik AS VARCHAR(8)),8) = PERNR
//					AND CONVERT(CHAR(8),tanggal_absen,112) = CONVERT(CHAR(8),DATUM,112) AND CONVERT(CHAR(8),DATUM,112) >= \'20181001\'', 'left outer', false);

//        $this->db->join('tbl_cuti', 'tbl_bak.nik = tbl_cuti.nik AND tbl_bak.tanggal_absen BETWEEN tbl_cuti.tanggal_awal AND tbl_cuti.tanggal_akhir', 'left outer', false);

        if (isset($lengkap) && !$lengkap) {
            $this->db->join('tbl_cuti', 'tbl_bak.nik = tbl_cuti.nik AND tbl_bak.tanggal_absen BETWEEN tbl_cuti.tanggal_awal AND tbl_cuti.tanggal_akhir', 'left outer', false);
        }

        if (isset($nik)) {
            if (is_array($nik))
                $this->db->where_in('tbl_bak.nik', $nik);
            else
                $this->db->where('tbl_bak.nik', $nik);
        }

        if (!$all) {
            $this->db->where('tbl_bak.na', 'n');
            $this->db->where('tbl_bak.del', 'n');
        }

        if (isset($id))
            $this->db->where('tbl_bak.id_bak', $id);

        if (isset($new_method))
            $this->db->where('tbl_bak.new_method', $new_method);

        if (isset($tanggal_absen))
            $this->db->where('tbl_bak.tanggal_absen', $tanggal_absen);

        if (isset($jadwal_absen)) {
            if (is_bool($jadwal_absen))
                $this->db->where('tbl_bak.jadwal_absen', null);
            else
                $this->db->where('tbl_bak.jadwal_absen', $jadwal_absen);
        }

        if (isset($jadwal_absen_masuk)) {
            if (is_bool($jadwal_absen_masuk))
                $this->db->where('tbl_bak.jadwal_absen_masuk', null);
            else
                $this->db->where('tbl_bak.jadwal_absen_masuk', $jadwal_absen_masuk);
        }

        if (isset($jadwal_absen_keluar)) {
            if (is_bool($jadwal_absen_keluar))
                $this->db->where('tbl_bak.jadwal_absen_keluar', null);
            else
                $this->db->where('tbl_bak.jadwal_absen_keluar', $jadwal_absen_keluar);
        }

        if (
            (isset($tanggal_awal) && !empty($tanggal_awal)) || (isset($tanggal_akhir) && !empty($tanggal_akhir))
        ) {
            if (isset($tanggal_awal))
                $this->db->where('tbl_bak.tanggal_absen >=', $tanggal_awal);
            if (isset($tanggal_akhir))
                $this->db->where('tbl_bak.tanggal_absen <=', $tanggal_akhir);
        }

        if (isset($id_bak_status)) {
            if (is_array($id_bak_status))
                $this->db->where_in('tbl_bak.id_bak_status', $id_bak_status);
            else
                $this->db->where('tbl_bak.id_bak_status', $id_bak_status);
        }

        if (isset($atasan))
			$this->db->where('CHARINDEX(\'\'\'\'+CONVERT(varchar(10), \'' . $atasan . '\')+\'\'\'\',\'\'\'\'+REPLACE(tbl_bak.atasan, RTRIM(\'.\'),\'\'\',\'\'\')+\'\'\'\') > 0', null, false);
			// $this->db->where("CHARINDEX('" . $atasan . "', tbl_bak.atasan)>0", null, false);

        if (isset($params['sap']) && $params['sap']) {
            // $this->db->where('tbl_bak.login_buat != ', ''); 
            $this->db->where('tbl_bak.id_bak_alasan is not null ', '', false);
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('tbl_bak.absen_masuk != ', '-');
            $this->db->where('tbl_bak.absen_keluar != ', '-');
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where_in('tbl_bak.id_bak_status', array(ESS_BAK_STATUS_DISETUJUI, ESS_BAK_STATUS_DISETUJUI_OLEH_HR));
            $this->db->where('tbl_bak.id_bak_alasan', ESS_BAK_ALASAN_HAPUS_BAK);
            $this->db->group_end();
            $this->db->group_end();
        }

        if (isset($tipe))
            if (is_array($tipe))
                $this->db->where_in('tbl_bak.tipe', $tipe);
            else
                $this->db->where('tbl_bak.tipe', $tipe);

        if (isset($tipe_exclude))
            if (is_array($tipe_exclude))
                $this->db->where_not_in('tbl_bak.tipe', $tipe_exclude);
            else
                $this->db->where('tbl_bak.tipe <>', $tipe_exclude);

        if (isset($jenis))
            $this->db->where('tbl_bak.jenis', $jenis);

        if (isset($ho))
            $this->db->where("tbl_karyawan.ho", $ho);

        if (isset($manager)) {
            $this->db->group_start();
            if ($manager) {
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

        if (isset($lengkap) && !$lengkap) {
            $this->db->where("(ISNULL(absen_masuk,'') IN ('','-') OR ISNULL(absen_keluar,'') IN ('','-')) 
                AND tipe NOT IN ('L','0120')", null, false);
            $this->db->where('tbl_cuti.id_cuti is null', null, false);
        }
		//lha cr-2451 Penambahan data time untuk pak Yan Biring (NIK dummy 90120053) di portal Kiranaku
		$this->db->where("tbl_bak.nik!='90120053'");

        if (isset($order_by))
            $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        if (is_array($result)) {
            foreach ($result as $index => $list) {
                $result[$index] = $this->get_bak_additional_column($list, $params);;
            }
        } else if (isset($result))
            $result = $this->get_bak_additional_column($result, $params);

        return $result;
    }

    private function get_bak_additional_column($data, $params)
    {

        /** Tambah Kolom ID yang terenkripsi **/
        $data->enId = $this->generate->kirana_encrypt($data->id_bak);

        if ($data->absen_keluar != '-' && !empty($data->absen_keluar))
            $data->absen_keluar = date_format(date_create($data->absen_keluar), 'H:i');

        if ($data->absen_masuk != '-' && !empty($data->absen_masuk))
            $data->absen_masuk = date_format(date_create($data->absen_masuk), 'H:i');

        return $data;
    }

    public function get_bak_ktp($params = array())
    {
        $query = $this->db->query("EXEC dbo.SP_KIranaku_ESS_View_LapBakTkp " .
            "'" . $params['tanggal_awal'] . "','" .
            $params['tanggal_akhir'] . "','" .
            $params['filter_ktp'] . "','" . $params['filter_cico'] . "'");

        $result = $query->result();

        return $result;
    }

    public function get_bak_alasan($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'id_bak_alasan ASC';

        $this->db->select('tbl_bak_alasan.*');
        $this->db->from('tbl_bak_alasan');

        if (isset($id))
            $this->db->where('tbl_bak_alasan.id_bak_alasan', $id);

        if (!$all) {
            $this->db->where('tbl_bak_alasan.na', 'n');
            $this->db->where('tbl_bak_alasan.del', 'n');
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

    public function get_bak_status($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'id_bak_status ASC';
        $except = isset($params['except']) ? $params['except'] : null;

        $this->db->select('tbl_bak_status.*');
        $this->db->from('tbl_bak_status');

        if (isset($id)) {
            if (is_array($id))
                $this->db->where_in('tbl_bak_status.id_bak_status', $id);
            else
                $this->db->where('tbl_bak_status.id_bak_status', $id);
        }


        if (isset($except))
            $this->db->where_not_in('tbl_bak_status.id_bak_status', $except);

        if (!$all) {
            $this->db->where('tbl_bak_status.na', 'n');
            $this->db->where('tbl_bak_status.del', 'n');
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

    public function get_bak_massal($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id = isset($params['id']) ? $params['id'] : null;
        $tanggal_awal = isset($params['tanggal_awal']) ? $params['tanggal_awal'] : null;
        $tanggal_akhir = isset($params['tanggal_akhir']) ? $params['tanggal_akhir'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'id_bak_massal ASC';

        $this->db->select('tbl_bak_massal.*');
        $this->db->from('tbl_bak_massal');

        if (isset($id))
            $this->db->where('tbl_bak_massal.id_bak_massal', $id);

        if (isset($tanggal_awal))
            $this->db->where('tbl_bak_massal.tanggal_bak >=', $tanggal_awal);

        if (isset($tanggal_akhir))
            $this->db->where('tbl_bak_massal.tanggal_bak <=', $tanggal_akhir);

        if (!$all) {
            $this->db->where('tbl_bak_massal.na', 'n');
            $this->db->where('tbl_bak_massal.del', 'n');
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

    public function get_check_cuti($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $id_cuti_status = isset($params['id_cuti_status']) ? $params['id_cuti_status'] : null;
        $tanggal_awal = isset($params['tanggal_awal']) ? $params['tanggal_awal'] : null;
        $tanggal_akhir = isset($params['tanggal_akhir']) ? $params['tanggal_akhir'] : null;
        $tanggal_absen_masuk = isset($params['tanggal_absen_masuk']) ? $params['tanggal_absen_masuk'] : null;
        $tanggal_absen_keluar = isset($params['tanggal_absen_keluar']) ? $params['tanggal_absen_keluar'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'id_cuti DESC';

        $this->db->select('tbl_cuti.*, tbl_cuti_status.nama as nama_status , tbl_cuti_status.warna');
        $this->db->from('tbl_cuti');
        $this->db->join('tbl_cuti_status', 'tbl_cuti.id_cuti_status = tbl_cuti_status.id_cuti_status', 'left outer');

        if (isset($nik))
            $this->db->where('tbl_cuti.nik', $nik);

        if (!$all) {
            $this->db->where('tbl_cuti.na', 'n');
            $this->db->where('tbl_cuti.del', 'n');
        }

        if (isset($id_cuti_status))
            if (is_array($id_cuti_status))
                $this->db->where_in('tbl_cuti.id_cuti_status', $id_cuti_status);
            else
                $this->db->where('tbl_cuti.id_cuti_status', $id_cuti_status);

        if (
        (isset($tanggal_awal) && !empty($tanggal_awal) && isset($tanggal_akhir) && !empty($tanggal_akhir))
        ) {
            if (isset($tanggal_awal) && $tanggal_akhir) {
                $tanggal_awal = date('Ymd', strtotime($tanggal_awal));
                $tanggal_akhir = date('Ymd', strtotime($tanggal_akhir));
                $this->db->group_start();
                $this->db->group_start();
                $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_awal,112) >=', $tanggal_awal);
                $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_akhir,112) <=', $tanggal_akhir);
                $this->db->group_end();
                $this->db->or_group_start();
                $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_awal,112) <=', $tanggal_awal);
                $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_akhir,112) >=', $tanggal_awal);
                $this->db->group_end();
                $this->db->or_group_start();
                $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_awal,112) <=', $tanggal_akhir);
                $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_akhir,112) >=', $tanggal_akhir);
                $this->db->group_end();
                $this->db->group_end();
            } else {
                if (isset($tanggal_awal)) {
                    $tanggal_awal = date('Ymd', strtotime($tanggal_awal));
                    $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_awal,112) >=', $tanggal_awal);
                }
                if (isset($tanggal_akhir)) {
                    $tanggal_akhir = date('Ymd', strtotime($tanggal_akhir));
                    $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_akhir,112) <=', $tanggal_akhir);
                }
            }
        }

        if (isset($tanggal_absen_masuk)) {
            $tanggal_absen_masuk = date('Ymd', strtotime($tanggal_absen_masuk));
            $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_masuk,112) =', $tanggal_absen_masuk);
        }

        if (isset($tanggal_absen_keluar)) {
            $tanggal_absen_keluar = date('Ymd', strtotime($tanggal_absen_keluar));
            $this->db->where('CONVERT(char(8),tbl_cuti.tanggal_keluar,112) =', $tanggal_absen_keluar);
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

    public function get_detail_hari($params = array())
    {
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $single_row = isset($params['single_row']) ? $params['single_row'] : false;
        $tanggal_absen = isset($params['tanggal_absen']) ? $params['tanggal_absen'] : null;

        $this->db->select('ZDMTM0001.SOBEG as absen_masuk');
        $this->db->select('ZDMTM0001.SOEND as absen_keluar');
        $this->db->from('ZDMTM0001');

        if (isset($nik) && !empty($nik))
            $this->db->like('ZDMTM0001.PERNR', $nik, 'before');

        if (isset($tanggal_absen) && !empty($tanggal_absen))
            $this->db->where('ZDMTM0001.ENTUM', $tanggal_absen);

        $query = $this->db->get();

        if ($single_row)
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_karyawan($params = array())
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $ho = isset($params['ho']) ? $params['ho'] : null;

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

        $this->db->where('tbl_user.na', 'n');
        $this->db->where('tbl_user.del', 'n');
        $this->db->where('tbl_karyawan.na', 'n');
        $this->db->where('tbl_karyawan.del', 'n');

        if (isset($ho) && $ho)
            $this->db->where('tbl_karyawan.ho', 'y');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_divisi($params = array())
    {

        $this->db->select("tbl_divisi.*");
        $this->db->from("tbl_divisi");
        $this->db->where("na='n'");
        $this->db->order_by("nama");

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    public function get_department($params = array())
    {
        $this->db->select("tbl_departemen.*");
        $this->db->from("tbl_departemen");
        $this->db->where("na='n'");
        $this->db->order_by("nama");

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    public function get_work_schedule($params = array())
    {
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $tanggal = isset($params['tanggal']) ?
            date_create($params['tanggal'])->format('Ymd') :
            null;
//        $tanggal = isset($params['tanggal']) ?
//            DateTime::createFromFormat('Y-m-d', $params['tanggal'])->format('Ymd') :
//            null;

        if (!isset($nik))
            return null;
        else {
            $gen = $this->generate;

            $koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);

            $data_koneksi = $koneksi['ESS'];

            $constr = array(
                "logindata" => array(
                    "ASHOST" => $data_koneksi['ASHOST'],
                    "SYSNR" => $data_koneksi['SYSNR'],
                    "CLIENT" => $data_koneksi['CLIENT'],
                    "USER" => $data_koneksi['USER'],
                    "PASSWD" => $data_koneksi['PASSWD']
                ),
                "show_errors" => $data_koneksi['DEBUG'],
                "debug" => $data_koneksi['DEBUG']
            );

            $sap = new saprfc($constr);

            $result = $sap->callFunction("Z_GET_EMPLOYEE_TIME_EVENT",
                array(
                    array("IMPORT", "I_BEGDA", $tanggal),
                    array("IMPORT", "I_ENDDA", $tanggal),
                    array("IMPORT", "I_PERNR", $nik),
                    array("TABLE", "T_DATA", array())
                )
            );

            if ($sap->getStatus() == SAPRFC_OK) {
                foreach ($result['T_DATA'] as $data)
                    return array(
                        'SOBEG' => $data['SOBEG'],
                        'SOEND' => $data['SOEND'],
                        'TIPE' => $data['TIPE']
                    );
            } else
                return null;
        }

    }

    public function get_dashboard($params = array())
    {
        $query = $this->db->query("EXEC dbo.SP_Kiranaku_ESS_Dashboard " .
            "'" . $params['nik'] . "','" .
            $params['tanggal_awal'] . "','" . $params['tanggal_akhir'] . "'");

        $result = $query->result();

        return $result;
    }

    public function get_bak_min_date($params = array())
    {
        $query = $this->db->query("EXEC dbo.SP_Portal_BAK_ESS_GetMinDate " .
            "'" . $params['nik'] . "'");

        $result = $query->row();
        return $result;
    }
}