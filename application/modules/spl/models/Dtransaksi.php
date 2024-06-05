<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : SPL
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
    function rules_header() //tbl_spl_header
    {
        return array(
            array(
                'field' => 'plant',
                'label' => 'Pabrik',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'no_spl',
                'label' => 'Nomor SPL',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'tanggal_spl',
                'label' => 'Tanggal SPL',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'id_departemen',
                'label' => 'Departemen',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'id_seksie',
                'label' => 'Seksie',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'nik',
                'label' => 'Karyawan',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Data %s tidak boleh kosong.',
                )
            )
        );
    }

    function rules_detail() //tbl_spl_detail
    {
        return array(
            array(
                'field' => 'plant',
                'label' => 'Pabrik',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'no_spl',
                'label' => 'Nomor SPL',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'no',
                'label' => 'No Urut',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'nik',
                'label' => 'NIK',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'jam_mulai',
                'label' => 'Jam Mulai',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
            array(
                'field' => 'jam_selesai',
                'label' => 'Jam Selesai',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Kolom %s tidak boleh kosong.',
                )
            ),
        );
    }

    function get_nomor_spl($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_spl_header.*');
        $this->db->from('tbl_spl_header');
        $this->db->where('tbl_spl_header.plant', $param['plant']);
        $this->db->where_in('YEAR(tbl_spl_header.tanggal_spl)', $param['year']);
        $this->db->like('no_spl', $param['plant'] . '/' . $param['month'] . '/' . $param['year'], 'before');

        $query = $this->db->get();
        $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();
        return $result;
    }

    function get_spl_header_list($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//
        $tipe = (isset($param['tipe_list']) && $param['tipe_list'] !== NULL) ? $param['tipe_list'] : 'list';
        $qr = "
            EXEC SP_SPL_Data_Header
            @nik = '" . base64_decode($this->session->userdata('-nik-')) . "', 
            @tipe_view = '" . $tipe . "'
        ";

        if (isset($param['no_spl']) && $param['no_spl'] !== NULL)
            $qr .= ", @no_spl = '" . $param['no_spl'] . "'";
        if (isset($param['plant']) && $param['plant'] !== NULL)
            $qr .= ", @plant = '" . $param['plant'] . "'";
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $qr .= ", @plant_in = '" . implode(",", $param['IN_plant']) . "'";
        if (isset($param['tahun']) && $param['tahun'] !== NULL)
            $qr .= ", @tahun = '" . $param['tahun'] . "'";
        if (isset($param['IN_tahun']) && $param['IN_tahun'] !== NULL)
            $qr .= ", @tahun_in = '" . implode(",", $param['IN_tahun']) . "'";
        if (isset($param['id_departemen']) && $param['id_departemen'] !== NULL)
            $qr .= ", @departemen = '" . $param['id_departemen'] . "'";
        if (isset($param['IN_departemen']) && $param['IN_departemen'] !== NULL)
            $qr .= ", @departemen_in = '" . implode(",", $param['IN_departemen']) . "'";
        if (isset($param['status']) && $param['status'] !== NULL)
            $qr .= ", @status = '" . $param['status'] . "'";
        if (isset($param['IN_status']) && $param['IN_status'] !== NULL)
            $qr .= ", @status_in = '" . implode(",", $param['IN_status']) . "'";
        if (isset($param['NOT_IN_status']) && $param['NOT_IN_status'] !== NULL)
            $qr .= ", @status_not_in = '" . implode(",", $param['NOT_IN_status']) . "'";

        // $query = $this->db->get();
        $query = $this->db->query($qr);
        // echo $qr;
        // exit();

        if (isset($param['single_row']) && $param['single_row'] == TRUE) {
            $result = $query->row();
        } else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_spl_header($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select(
            "
            CASE 
                WHEN 
                    '" . base64_decode($this->session->userdata('-posst-')) . "' IN (
                        SELECT 
                            splitdata as position
                        FROM tbl_spl_role
                            CROSS APPLY fnSplitString(tbl_spl_role.posst,';')
                        WHERE na = 'n'
                            AND del = 'n'
                            AND CAST(level AS VARCHAR) = vw_spl_header.status
                    )
                    AND
                    1 = (
                        CASE 
                            WHEN
                                 vw_spl_header.status = '2' AND vw_spl_header.id_departemen = '" . base64_decode($this->session->userdata('-id_departemen-')) . "' THEN 1
                            WHEN vw_spl_header.status <> '2' THEN 1
                            ELSE 0
                        END
                    )
                THEN 1
                ELSE 0
            END AS access"
        );
        $this->db->select(
            "
            CASE 
                WHEN
                    ( 
                        '" . base64_decode($this->session->userdata('-posst-')) . "' IN (
                            SELECT 
                                splitdata as position
                            FROM tbl_spl_role
                                CROSS APPLY fnSplitString(tbl_spl_role.posst,';')
                            WHERE na = 'n'
                                AND del = 'n'
                                AND CAST(level AS VARCHAR) = 1
                        )
                        AND
                        vw_spl_header.status = 'finish'
                        AND
                        vw_spl_header.realisasi = 0
                    )
                    OR
                    ( 
                        '" . base64_decode($this->session->userdata('-posst-')) . "' IN (
                            SELECT 
                                splitdata as position
                            FROM tbl_spl_role
                                CROSS APPLY fnSplitString(tbl_spl_role.posst,';')
                            WHERE na = 'n'
                                AND del = 'n'
                                AND CAST(level AS VARCHAR) = 3
                        )
                        AND
                        vw_spl_header.status = 'finish'
                        AND
                        vw_spl_header.realisasi = 1
                    )
                THEN 1
                ELSE 0
            END AS access_realisasi"
        );
        $this->db->select("vw_spl_header.*");
        $this->db->from('vw_spl_header');

        $this->db->where('vw_spl_header.na', 'n');
        $this->db->where('vw_spl_header.del', 'n');

        if (isset($param['no_spl']) && $param['no_spl'] !== NULL)
            $this->db->where('vw_spl_header.no_spl', $param['no_spl']);
        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_spl_header.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_spl_header.plant', $param['IN_plant']);
        if (isset($param['IN_departemen']) && $param['IN_departemen'] !== NULL)
            $this->db->where_in('vw_spl_header.id_departemen', $param['IN_departemen']);
        if (isset($param['IN_year']) && $param['IN_year'] !== NULL)
            $this->db->where_in('YEAR(vw_spl_header.tanggal)', $param['IN_year']);
        if (isset($param['NOT_IN_status']) && $param['NOT_IN_status'] !== NULL)
            $this->db->where_not_in('vw_spl_header.status', $param['NOT_IN_status']);
        if (isset($param['IN_status']) && $param['IN_status'] !== NULL)
            $this->db->where_in('vw_spl_header.status', $param['IN_status']);

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("
                access,
                access_realisasi,
                realisasi,
                no_spl,
                plant,
                tanggal_buat,
                tanggal_buat_format,
                tanggal_spl,
                tanggal_spl_format,
                id_departemen,
                departemen,
                id_seksi,
                seksi,
                plan_lembur,
                status,
                status_spl,
                status_spl_reject,
                id_file
            ");
            $this->datatables->from("($main_query) as vw_spl_header");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {

            $query = $this->db->get();

            if (isset($param['single_row']) && $param['single_row'] == TRUE) {
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

    function get_spl_detail($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $qr = "
            EXEC SP_SPL_Data_Detail
            @nik = '" . base64_decode($this->session->userdata('-nik-')) . "'
        ";

        if (isset($param['no_spl']) && $param['no_spl'] !== NULL)
            $qr .= ", @no_spl = '" . $param['no_spl'] . "'";

        if (isset($param['tipe']) && $param['tipe'] !== NULL)
            $qr .= ", @tipe = '" . $param['tipe'] . "'";

        $query = $this->db->query($qr);
        $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_spl_detail_old($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select("
            tbl_spl_detail.no_spl,
            tbl_spl_detail.plant,
            tbl_spl_detail.no_urut,
            tbl_spl_detail.nik,
            tbl_karyawan.nama,
            tbl_karyawan.posst,
            tbl_spl_detail.jam_mulai,
            CONVERT(VARCHAR(5), tbl_spl_detail.jam_mulai, 108) AS jam_mulai_format,
            tbl_spl_detail.jam_selesai,
            CONVERT(VARCHAR(5), tbl_spl_detail.jam_selesai, 108) AS jam_selesai_format,
            tbl_spl_detail.tipe,
            tbl_spl_detail.status,
            tbl_spl_detail.keterangan
        ");
        $this->db->from("tbl_spl_detail");
        $this->db->join("tbl_karyawan", "tbl_karyawan.nik = tbl_spl_detail.nik", "inner");

        $this->db->where("tbl_spl_detail.na", "n");
        $this->db->where("tbl_spl_detail.del", "n");
        $this->db->order_by("tbl_spl_detail.no_urut");

        if (isset($param['no_spl']) && $param['no_spl'] !== NULL)
            $this->db->where('tbl_spl_detail.no_spl', $param['no_spl']);
        if (isset($param['tipe']) && $param['tipe'] !== NULL)
            $this->db->where('tbl_spl_detail.tipe', $param['tipe']);
        if (isset($param['status']) && $param['status'] !== NULL)
            $this->db->where('tbl_spl_detail.status', $param['status']);

        $query = $this->db->get();
        // echo json_encode($this->db->error());exit();
        $result = $query->result();
        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_history($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $query = $this->db->query("EXEC SP_SPL_Data_Supporting 
            @no_spl = '" . $param['no_spl'] . "', 
            @tipe_data = 'history'
        ");

        $result = $query->result();
        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_lembur_karyawan($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select("
            tbl_spl_detail.no_spl, 
            tbl_spl_detail.nik, 
            tbl_spl_detail.jam_mulai, 
            tbl_spl_detail.jam_selesai,
            DATEDIFF(hour,tbl_spl_detail.jam_mulai, tbl_spl_detail.jam_selesai) AS jam_lembur,
            DATEDIFF(minute,tbl_spl_detail.jam_mulai, tbl_spl_detail.jam_selesai) AS menit_lembur
        ");
        $this->db->from("tbl_spl_detail");
        $this->db->join("tbl_spl_header", "tbl_spl_header.no_spl = tbl_spl_detail.no_spl", "inner");

        $this->db->where("tbl_spl_detail.na", "n");
        $this->db->where("tbl_spl_detail.del", "n");
        $this->db->where("tbl_spl_detail.status", "ok");
        $this->db->where_not_in('tbl_spl_header.status', ['rejected']);
        $this->db->order_by("tbl_spl_detail.no_urut");

        if (isset($param['no_spl']) && $param['no_spl'] !== NULL)
            $this->db->where('tbl_spl_detail.no_spl', $param['no_spl']);
        if (isset($param['tipe']) && $param['tipe'] !== NULL)
            $this->db->where('tbl_spl_detail.tipe', $param['tipe']);
        if (isset($param['nik']) && $param['nik'] !== NULL)
            $this->db->where('tbl_spl_detail.nik', $param['nik']);

        if (isset($param['cek_data_exist']) && $param['cek_data_exist'] !== NULL) {
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('tbl_spl_detail.jam_mulai <=', $param['jam_mulai']);
            $this->db->where('tbl_spl_detail.jam_selesai >=', $param['jam_mulai']);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where('tbl_spl_detail.jam_mulai <=', $param['jam_selesai']);
            $this->db->where('tbl_spl_detail.jam_selesai >=', $param['jam_selesai']);
            $this->db->group_end();
            $this->db->group_end();
        }

        $query = $this->db->get();
        // echo json_encode($this->db->error());
        // echo $this->db->last_query();
        // exit();
        $result = $query->result();
        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_spl_header_tahun($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('YEAR(tbl_spl_header.tanggal_spl) as tahun');
        $this->db->from('tbl_spl_header');
        $this->db->where('tbl_spl_header.na', 'n');
        $this->db->where('tbl_spl_header.del', 'n');
        $this->db->order_by('YEAR(tbl_spl_header.tanggal_spl)', 'ASC');
        $this->db->group_by('YEAR(tbl_spl_header.tanggal_spl)');
        $query = $this->db->get();
        $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_karyawan($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        // $this->db->select('tbl_karyawan.nik AS id');
        // $this->db->select('tbl_karyawan.*');
        // $this->db->from("tbl_karyawan");
        // $this->db->join("tbl_user", "tbl_user.id_karyawan = tbl_karyawan.id_karyawan", "inner");

        // $this->db->select('vw_spl_data_karyawan.nik AS id');
        // $this->db->select('vw_spl_data_karyawan.*');
        // $this->db->from("vw_spl_data_karyawan");
        // // $this->db->where('vw_spl_data_karyawan.na', 'n');
        // // $this->db->where('vw_spl_data_karyawan.del', 'n');

        // if (isset($param['plant']) && $param['plant'] !== NULL)
        //     $this->db->where('vw_spl_data_karyawan.gsber', $param['plant']);
        // if (isset($param['id_departemen']) && $param['id_departemen'] !== NULL)
        //     $this->db->where('vw_spl_data_karyawan.id_departemen', $param['id_departemen']);
        // if (isset($param['id_seksie']) && $param['id_seksie'] !== NULL)
        //     $this->db->where('vw_spl_data_karyawan.id_seksie', $param['id_seksie']);
        // if (isset($param['IN_golongan']) && $param['IN_golongan'] !== NULL)
        //     $this->db->where_in('vw_spl_data_karyawan.id_golongan', $param['IN_golongan']);
        // if (isset($param['not_in_nik']) && $param['not_in_nik'] !== NULL)
        //     $this->db->where_not_in('vw_spl_data_karyawan.nik', $param['not_in_nik']);

        // if (isset($param['search']) && $param['search'] !== NULL) {
        //     $this->db->group_start();
        //     $this->db->like('vw_spl_data_karyawan.nik', $param['search'], 'both');
        //     $this->db->or_like('vw_spl_data_karyawan.nama', $param['search'], 'both');
        //     $this->db->group_end();
        // }

        // $query = $this->db->get();
        // echo json_encode($this->db->error());exit();
        $qr = "
            EXEC SP_SPL_Data_Supporting 
            @tipe_data = 'karyawan',
            @golongan = 'NS,HR'
            
        ";
        if (isset($param['id_departemen']) && $param['id_departemen'] !== NULL)
            $qr .= ", @departemen = '" . $param['id_departemen'] . "'";
        if (isset($param['id_seksie']) && $param['id_seksie'] !== NULL)
            $qr .= ", @seksie = '" . $param['id_seksie'] . "'";
        if (isset($param['search']) && $param['search'] !== NULL)
            $qr .= ", @search = '" . $param['search'] . "'";
        if (isset($param['not_in_nik']) && $param['not_in_nik'] !== NULL)
            $qr .= ", @not_in_nik = '" . implode(",", $param['not_in_nik']) . "'";
        if (isset($param['tanggal_spl']) && $param['tanggal_spl'] !== NULL)
            $qr .= ", @tanggal = '" . $param['tanggal_spl'] . "'";

        // echo $qr;exit();
        $query = $this->db->query($qr);

        if (isset($param['single_row']) && $param['single_row'] !== NULL)
            $result = $query->row();
        else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_notif_recipient($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        // $query = $this->db->query("
        //     SELECT DISTINCT 
        //         tb_karyawan.id_karyawan,
        //         tb_karyawan.nama,
        //         CASE tb_karyawan.gender
        //             WHEN 'l' THEN 'Bapak'
        //             ELSE 'Ibu'
        //         END gender,
        //         tb_karyawan.posst,
        //         tb_karyawan.email,
        //         tb_karyawan.telepon_pribadi,
        //         nilai = 'to',
        //         tb_role.nama_role
        //     FROM tbl_spl_header
        //     INNER JOIN (
        //         SELECT 
        //             level,
        //             nama_role,
        //             splitdata AS posst
        //         FROM tbl_spl_role
        //         CROSS APPLY fnSplitString(posst,';')
        //         WHERE na = 'n' AND del = 'n'
        //     ) tb_role ON CAST(tb_role.level AS VARCHAR) = tbl_spl_header.status
        //     INNER JOIN (
        //         SELECT tbl_user.id_departemen, tbl_karyawan.* 
        //         FROM tbl_karyawan
        //         INNER JOIN tbl_user ON tbl_user.id_karyawan = tbl_karyawan.id_karyawan
        //     ) tb_karyawan ON tb_karyawan.posst = tb_role.posst
        //                                 AND
        //                                 1 = CASE
        //                                         WHEN tb_karyawan.ho = 'y' AND tb_role.nama_role = 'DEPUTY CROO' THEN 1
        //                                         WHEN tb_role.nama_role <> 'Manager Terkait' AND tb_karyawan.ho = 'n' AND tb_karyawan.gsber = tbl_spl_header.plant THEN 1
        //                                         WHEN tb_role.nama_role = 'Manager Terkait' AND tb_karyawan.id_departemen = tbl_spl_header.id_departemen THEN 1
        //                                         ELSE 0
        //                                     END
        //     WHERE tbl_spl_header.no_spl = '" . $param['no_spl'] ."'
        // ");

        $query = $this->db->query(
            "EXEC SP_SPL_Recipient_Email 
            @no_spl = '" . $param['no_spl'] . "',
            @action = '" . $param['action'] . "'"
        );

        if (isset($param['single_row']) && $param['single_row'] !== NULL)
            $result = $query->row();
        else
            $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
}
