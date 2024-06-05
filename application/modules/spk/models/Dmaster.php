<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Master - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dmaster extends CI_Model
{

    function get_data_role($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $join_role = 'T1.id_role = T2.id_role';
        if (!isset($param['id_jenis_spk'])) $join_role .= ' AND T2.id_jenis_spk = 0';
        else $join_role .= ' AND T2.id_jenis_spk = ' . $param['id_jenis_spk'];

        $this->db->select('T1.*,
            T2.id_jenis_spk,
            T2.jenis_spk,
            T2.if_approve,
            T2.approve,
            T2.if_decline,
            T2.decline,
            T2.is_limit,
            T2.divisi_terkait,
            T2.nama_divisi_terkait');
        $this->db->from("tbl_leg_role T1");
        $this->db->join(
            "vw_leg_master_role T2",
            $join_role,
            'left'
        );
        $this->db->where('T1.del', 'n');

        if (isset($param['id_role']) && $param['id_role'] !== NULL)
            $this->db->where('T1.id_role', $param['id_role']);
        // if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
        //     $this->db->where_in('vw_leg_master_role.plant', $param['IN_plant']);
        if (isset($param['level']) && $param['level'] !== NULL)
            $this->db->where('T1.level', $param['level']);
        if (isset($param['IN_level']) && $param['IN_level'] !== NULL)
            $this->db->where_in('T1.level', $param['IN_level']);
        if (isset($param['nama_role']) && $param['nama_role'] !== NULL)
            $this->db->where('T1.nama_role', $param['nama_role']);

        if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no")) {
            $this->db->where('T1.na', 'n');
        }

        // $query = $this->db->get();
        // echo json_encode($this->db->error());
        // exit();

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id_role,
                nama_role,
                level,
                tipe_user,
                ho,
                akses_buat,
                akses_hapus,
                paralel,
                na,
                del,
                id_jenis_spk,
                jenis_spk,
                if_approve,
                approve,
                if_decline,
                decline,
                is_limit,
                divisi_terkait,
                nama_divisi_terkait");
            $this->datatables->from("($main_query) as vw_leg_master_role");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            // if (isset($param['cek_nama_role']) && $param['cek_nama_role'] !== NULL) {
            //     $this->db->where('T1.nama_role', $param['nama_role']);

            //     if (isset($param['NOT_IN_id_role']) && $param['NOT_IN_id_role'] !== NULL) {
            //         $this->db->group_start();
            //         $this->db->where_not_in('T1.id_role', $param['NOT_IN_id_role']);
            //         // $this->db->or_where_not_in('T2.kode', $param['NOT_IN_kode_role']);
            //         $this->db->group_end();
            //     }
            // }
            if (isset($param['cek_duplikat']) && $param['cek_duplikat'] !== NULL) {
                $this->db->group_start();
                    if (isset($param['cek_nama_role']) && $param['cek_nama_role'] !== NULL) 
                        $this->db->or_where('T1.nama_role', $param['cek_nama_role']);
                    if (isset($param['LIKE_nama_role']) && $param['LIKE_nama_role'] !== NULL)
                        $this->db->or_like('T1.nama_role', $param['LIKE_nama_role'], 'both');
                    if (isset($param['cek_level']) && $param['cek_level'] !== NULL)
                        $this->db->or_where('T1.level', $param['cek_level']);
                $this->db->group_end();

                if (isset($param['NOT_IN_id_role']) && $param['NOT_IN_id_role'] !== NULL) {
                    $this->db->where_not_in('T1.id_role', $param['NOT_IN_id_role']);
                }
            }

            $query = $this->db->get();

            // echo json_encode($this->db->get_compiled_select());
            // exit();

            if (
                (isset($param['id_role']) && $param['id_role'] !== NULL)
                || (isset($param['single_row']) && $param['single_row'] !== NULL)
            ) {
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

    function get_data_role_old($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $join_role = 'T1.id_role = T2.id_role';
        if (!isset($param['id_jenis_spk'])) $join_role .= ' AND T2.id_jenis_spk = 0';
        else $join_role .= ' AND T2.id_jenis_spk = ' . $param['id_jenis_spk'];

        $this->db->select('T1.*,
            T2.id_jenis_spk,
            T2.jenis_spk,
            T2.if_approve,
            T2.approve,
            T2.if_decline,
            T2.decline,
            T2.is_limit');
        $this->db->from("tbl_leg_role T1");
        $this->db->join(
            "vw_leg_master_role T2",
            $join_role,
            'left'
        );
        $this->db->where('T1.del', 'n');

        if (isset($param['id_role']) && $param['id_role'] !== NULL)
            $this->db->where('T1.id_role', $param['id_role']);
        // if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
        //     $this->db->where_in('vw_leg_master_role.plant', $param['IN_plant']);
        if (isset($param['level']) && $param['level'] !== NULL)
            $this->db->where('T1.level', $param['level']);
        if (isset($param['IN_level']) && $param['IN_level'] !== NULL)
            $this->db->where_in('T1.level', $param['IN_level']);
        if (isset($param['nama_role']) && $param['nama_role'] !== NULL)
            $this->db->where('T1.nama_role', $param['nama_role']);

        if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no")) {
            $this->db->where('T1.na', 'n');
        }

        // $query = $this->db->get();
        // echo json_encode($this->db->error());
        // exit();

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id_role,
                nama_role,
                level,
                tipe_user,
                ho,
                akses_buat,
                akses_hapus,
                na,
                del,
                id_jenis_spk,
                jenis_spk,
                if_approve,
                approve,
                if_decline,
                decline,
                is_limit");
            $this->datatables->from("($main_query) as vw_leg_master_role");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            if (isset($param['nama_role']) && $param['nama_role'] !== NULL) {
                $this->db->where('T1.nama_role', $param['nama_role']);

                if (isset($param['NOT_IN_id_role']) && $param['NOT_IN_id_role'] !== NULL) {
                    $this->db->group_start();
                    $this->db->where_not_in('T1.id_role', $param['NOT_IN_id_role']);
                    // $this->db->or_where_not_in('T2.kode', $param['NOT_IN_kode_role']);
                    $this->db->group_end();
                }
            }
            if (isset($param['LIKE_nama_role']) && $param['LIKE_nama_role'] !== NULL) {
                $this->db->like('T1.nama_role', $param['LIKE_nama_role'], 'both');

                if (isset($param['NOT_IN_id_role']) && $param['NOT_IN_id_role'] !== NULL) {
                    $this->db->group_start();
                    $this->db->where_not_in('T1.id_role', $param['NOT_IN_id_role']);
                    // $this->db->or_where_not_in('T2.kode', $param['NOT_IN_kode_role']);
                    $this->db->group_end();
                }
            }

            $query = $this->db->get();

            //     echo json_encode($this->db->error());
            // exit();

            if (
                (isset($param['id_role']) && $param['id_role'] !== NULL)
                || (isset($param['single_row']) && $param['single_row'] !== NULL)
            ) {
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

    function get_data_userrole($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('vw_leg_master_user_role.*,
            user as id');
        $this->db->from('vw_leg_master_user_role');

        if (isset($param['id_user_role']) && $param['id_user_role'] !== NULL)
            $this->db->where('vw_leg_master_user_role.id_user_role', $param['id_user_role']);
        if (isset($param['user']) && $param['user'] !== NULL)
            $this->db->where('user', $param['user']);
        if (isset($param['nama_role']) && $param['nama_role'] !== NULL)
            $this->db->where('nama_role', $param['nama_role']);
        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->like('pabrik', $param['plant'], 'both');
        if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no")) {
            $this->db->where('na', 'n');
        }

        // $query = $this->db->get();
        // echo json_encode($this->db->get_compiled_select());
        // exit();

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("
                id_user_role,
                id_role,
                nama_role,
                user,
                id,
                jml_karyawan,
                caption,
                pabrik,
                pabrik_name,
                label_active,
                status_active,
                na");
            $this->datatables->from("($main_query) as vw_leg_master_role");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $query = $this->db->get();

            if ((isset($param['id_user_role']) && $param['id_user_role'] !== NULL)) {
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

    function get_data_karyawan($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_karyawan.nik as id');
        $this->db->select('tbl_karyawan.*');
        $this->db->from('tbl_karyawan');

        if (isset($param['nik']) && $param['nik'] !== NULL)
            $this->db->where('tbl_karyawan.nik', $param['nik']);
        if (isset($param['nama']) && $param['nama'] !== NULL)
            $this->db->like('tbl_karyawan.nama', $param['nama'], 'both');
        if (isset($param['search']) && $param['search'] !== NULL) {
            $this->db->like('tbl_karyawan.nik', $param['search'], 'both');
            $this->db->or_like('tbl_karyawan.nama', $param['search'], 'both');
        }

        $this->db->where('tbl_karyawan.na', 'n');
        $this->db->where('tbl_karyawan.del', 'n');

        $query = $this->db->get();
        if (isset($param['nik']) && $param['nik'] !== NULL)
            $result = $query->row();
        else
            $result = $query->result();

        $kolom = array();
        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $kolom = array_merge(
                $kolom,
                array_map(function ($val) {
                    return array(
                        "tipe" => "encrypt",
                        "nama" => $val,
                    );
                }, $param['encrypt'])
            );

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);
        // $result = $this->general->generate_json(
        //     array(
        //         "data" => $result,
        //         "kolom" => $kolom,
        //         "exclude" => $this->general->emptyconvert(@$param['exclude'])
        //     )
        // );

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_posisi($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select("tbl_posisi.nama as id");
        $this->db->select("tbl_posisi.nama");
        $this->db->select("(SELECT COUNT(nik)
                              FROM tbl_karyawan
                             WHERE tbl_karyawan.posst = tbl_posisi.nama
                               AND tbl_karyawan.na = 'n'
                               AND tbl_karyawan.del = 'n') as jml_karyawan");
        $this->db->select("CAST(
                            (SELECT DISTINCT '['+CONVERT(VARCHAR,tbl_karyawan.nik)+'] - '+tbl_karyawan.nama +RTRIM(';')
                               FROM tbl_karyawan
                              WHERE tbl_karyawan.posst = tbl_posisi.nama
                                AND tbl_karyawan.na = 'n'
                                AND tbl_karyawan.del = 'n'
                                FOR XML PATH ('')
                            ) as VARCHAR(MAX)
                          )  AS karyawan_list");
        $this->db->from('tbl_posisi');

        if (isset($param['search']) && $param['search'] !== NULL) {
            $this->db->like('tbl_posisi.nama', $param['search'], 'both');
        }

        $query = $this->db->get();
        if (isset($param['nik']) && $param['nik'] !== NULL)
            $result = $query->row();
        else
            $result = $query->result();

        // $kolom = array();
        // if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
        //     $kolom = array_merge(
        //         $kolom,
        //         array_map(function ($val) {
        //             return array(
        //                 "tipe" => "encrypt",
        //                 "nama" => $val,
        //             );
        //         }, $param['encrypt'])
        //     );

        // $result = $this->general->generate_json(
        //     array(
        //         "data" => $result,
        //         "kolom" => $kolom,
        //         "exclude" => $this->general->emptyconvert(@$param['exclude'])
        //     )
        // );

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    public function get_master_divisi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;

        $this->db->select('*');
        $this->db->from('tbl_divisi');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_divisi.na', 'n');
            $this->db->where('tbl_divisi.del', 'n');
        }

        if (isset($id_divisi))
            $this->db->where('tbl_divisi.id_divisi', $id_divisi);

        $this->db->order_by('tbl_divisi.nama');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_divisi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;

        $this->db->select('*');
        $this->db->from('tbl_leg_divisi');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_divisi.na', 'n');
            $this->db->where('tbl_leg_divisi.del', 'n');
        }

        if (isset($id_divisi))
            $this->db->where('tbl_leg_divisi.id_divisi', $id_divisi);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_nama_spk($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_nama_spk = isset($params['id_nama_spk']) ? $params['id_nama_spk'] : null;
        $id_jenis_spk = isset($params['id_jenis_spk']) ? $params['id_jenis_spk'] : null;

        $this->db->select('tbl_leg_nama_spk.*');
        $this->db->select('tbl_leg_jenis_spk.jenis_spk');
        $this->db->from('tbl_leg_nama_spk');
        $this->db->join('tbl_leg_jenis_spk', 'tbl_leg_jenis_spk.id_jenis_spk=tbl_leg_nama_spk.id_jenis_spk');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_nama_spk.na', 'n');
            $this->db->where('tbl_leg_nama_spk.del', 'n');
        }

        if (isset($id_nama_spk))
            $this->db->where('tbl_leg_nama_spk.id_nama_spk', $id_nama_spk);

        if (isset($id_jenis_spk))
            $this->db->where('tbl_leg_nama_spk.id_jenis_spk', $id_jenis_spk);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        if (is_array($result)) {
            foreach ($result as $index => $list) {
                $result[$index] = $this->get_nama_spk_additional($list, $params);;
            }
        } else if (isset($result))
            $result = $this->get_nama_spk_additional($result, $params);

        return $result;
    }
    public function get_kualifikasi_spk($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_kualifikasi_spk = isset($params['id_kualifikasi_spk']) ? $params['id_kualifikasi_spk'] : null;
        $id_jenis_spk = isset($params['id_jenis_spk']) ? $params['id_jenis_spk'] : null;

        $this->db->select('tbl_leg_kualifikasi_spk.*');
        $this->db->select('tbl_leg_jenis_spk.jenis_spk');
        $this->db->from('tbl_leg_kualifikasi_spk');
        $this->db->join('tbl_leg_jenis_spk', 'tbl_leg_jenis_spk.id_jenis_spk=tbl_leg_kualifikasi_spk.id_jenis_spk');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_kualifikasi_spk.na', 'n');
            $this->db->where('tbl_leg_kualifikasi_spk.del', 'n');
        }

        if (isset($id_kualifikasi_spk))
            $this->db->where('tbl_leg_kualifikasi_spk.id_kualifikasi_spk', $id_kualifikasi_spk);

        if (isset($id_jenis_spk))
            $this->db->where('tbl_leg_kualifikasi_spk.id_jenis_spk', $id_jenis_spk);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        // if (is_array($result)) {
        // foreach ($result as $index => $list) {
        // $result[$index] = $this->get_nama_spk_additional($list, $params);;
        // }
        // } else if (isset($result))
        // $result = $this->get_nama_spk_additional($result, $params);

        return $result;
    }
    //add lha 17.02.2020
    //data matrix vendor bom 
    function get_data_matrix_bom($conn = NULL, $lifnr = NULL, $ekorg = NULL, $plant = NULL, $status_pkp = NULL, $jenis_vendor = NULL, $status = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->datatables->select('LIFNR, EKORG, NAME1, CITY1, STRAS, PSTLZ, TELF1, SORTL, KALSK, KTOKK, PKPST, status_pkp, id_jenis_vendor, jenis_vendor, id_jenis_spk, jenis_spk, kualifikasi, status, list_kualifikasi, nama_upload');
        $this->datatables->from("vw_spk_zdmvendor");
        if (base64_decode($this->session->userdata("-ho-")) == 'n') {
            $this->datatables->where("ekorg like '%" . base64_decode($this->session->userdata('-gsber-')) . "%'");
        }
        if ($lifnr !== NULL) {
            $this->datatables->where('lifnr', $lifnr);
        }
        if ($ekorg != NULL) {
            $this->datatables->where('ekorg', $ekorg);
        }
        if ($plant != NULL) {
            if (is_string($plant)) $plant = explode(",", $plant);
            $this->datatables->where_in('ekorg', $plant);
        }
        if ($status_pkp != NULL) {
            if (is_string($status_pkp)) $status_pkp = explode(",", $status_pkp);
            $this->datatables->where_in('status_pkp', $status_pkp);
        }
        if ($jenis_vendor != NULL) {
            if (is_string($jenis_vendor)) $jenis_vendor = explode(",", $jenis_vendor);
            $this->datatables->where_in('id_jenis_vendor', $jenis_vendor);
        }
        if ($status != NULL) {
            if (is_string($status)) $status = explode(",", $status);
            $this->datatables->where_in('status', $status);
        }
        if ($conn !== NULL)
            $this->general->closeDb();

        $return = $this->datatables->generate();
        $raw = json_decode($return, true);
        $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("LIFNR"));
        return $this->general->jsonify($raw);
    }

    public function get_jenis_spk($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select('tbl_leg_jenis_spk.*');
        // $this->db->select("CAST(
        //     (SELECT tb_role.nama_role + RTRIM(',')
        //        FROM tbl_leg_jenis_spk tb_set
        //       CROSS APPLY fnSplitString(tb_set.roles_pic,',')
        //        LEFT JOIN tbl_leg_role tb_role ON tb_role.id_role = splitdata
        //       WHERE tbl_leg_jenis_spk.id_jenis_spk = tb_set.id_jenis_spk
        //       ORDER BY splitdata
        //      FOR XML PATH ('')) as VARCHAR(MAX)
        //    )  AS roles_pic_name");
        $this->db->from("tbl_leg_jenis_spk");
        $this->db->where('tbl_leg_jenis_spk.del', 'n');

        if (isset($param['id_jenis_spk']) && $param['id_jenis_spk'] !== NULL)
            $this->db->where('tbl_leg_jenis_spk.id_jenis_spk', $param['id_jenis_spk']);
        // if (isset($param['jenis_spk']) && $param['jenis_spk'] !== NULL)
        //     $this->db->where('tbl_leg_jenis_spk.nama_role', $param['nama_role']);

        if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no")) {
            $this->db->where('tbl_leg_jenis_spk.na', 'n');
        }

        // $query = $this->db->get();
        // echo json_encode($this->db->error());
        // exit();

        if (isset($param['return']) && $param['return'] == "datatables") {

            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id_role,
                nama_role,
                level,
                tipe_user,
                ho,
                akses_buat,
                akses_hapus,
                na,
                del");
            $this->datatables->from("($main_query) as vw_leg_jenis_spk");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            if (isset($param['jenis_spk']) && $param['jenis_spk'] !== NULL) {
                $this->db->where('tbl_leg_jenis_spk.jenis_spk', $param['jenis_spk']);

                if (isset($param['NOT_IN_id_jenis_spk']) && $param['NOT_IN_id_jenis_spk'] !== NULL) {
                    $this->db->group_start();
                    $this->db->where_not_in('tbl_leg_jenis_spk.id_jenis_spk', $param['NOT_IN_id_jenis_spk']);
                    $this->db->group_end();
                }
            }
            if (isset($param['LIKE_jenis_spk']) && $param['LIKE_jenis_spk'] !== NULL) {
                $this->db->like('tbl_leg_jenis_spk.jenis_spk', $param['LIKE_jenis_spk'], 'both');

                if (isset($param['NOT_IN_id_role']) && $param['NOT_IN_id_role'] !== NULL) {
                    $this->db->group_start();
                    $this->db->where_not_in('tbl_leg_jenis_spk.id_role', $param['NOT_IN_id_role']);
                    $this->db->group_end();
                }
            }

            $query = $this->db->get();

            // echo json_encode($this->db->error());
            // exit();

            if (
                (isset($param['id_jenis_spk']) && $param['id_jenis_spk'] !== NULL)
                || (isset($param['single_row']) && $param['single_row'] !== NULL)
            ) {
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

    function get_status_spk($id_status_in = NULL, $as_array = false)
    {
        $this->general->connectDbPortal();

        $this->db->select('tbl_leg_status.*');
        $this->db->from('tbl_leg_status');
        if ($id_status_in != NULL) {
            $this->db->where_in('tbl_leg_status.id_status', $id_status_in);
        }
        $this->db->where('tbl_leg_status.na', 'n');
        $this->db->where('tbl_leg_status.del', 'n');
        $this->db->where("tbl_leg_status.status not in('Delivered','Received','Pending Activity','Waiting Return Doc')");
        $this->db->order_by('tbl_leg_status.status', 'ASC');
        $query = $this->db->get();
        if ($as_array)
            $result = $query->result_array();
        else
            $result = $query->result();

        $this->general->closeDb();
        return $result;
    }

    public function get_jenis_vendor($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_jenis_vendor = isset($params['id_jenis_vendor']) ? $params['id_jenis_vendor'] : null;

        $this->db->select('tbl_leg_jenis_vendor.*');
        $this->db->from('tbl_leg_jenis_vendor');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_jenis_vendor.na', 'n');
            $this->db->where('tbl_leg_jenis_vendor.del', 'n');
        }

        if (isset($id_jenis_vendor))
            $this->db->where('tbl_leg_jenis_vendor.id_jenis_vendor', $id_jenis_vendor);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        if (is_array($result)) {
            foreach ($result as $index => $list) {
                $result[$index] = $this->get_jenis_vendor_additional($list, $params);;
            }
        } else if (isset($result))
            $result = $this->get_jenis_vendor_additional($result, $params);

        return $result;
    }

    public function get_privileges($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_user = isset($params['id_user']) ? $params['id_user'] : null;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;

        $this->db->distinct();
        $this->db->select('tbl_user.id_user');
        $this->db->select('tbl_user.leg_level_id');
        $this->db->select('tbl_karyawan.nama');
        $this->db->select('tbl_karyawan.nik');
        $this->db->select('tbl_level.nama as level');
        $this->db->select('tbl_leg_divisi.nama_divisi');
        $this->db->select('tbl_user.na');
        $this->db->select('tbl_user.del');
        $this->db->from('tbl_user');
        $this->db->join('tbl_karyawan', 'tbl_user.id_karyawan=tbl_karyawan.id_karyawan');
        $this->db->join('tbl_level', 'tbl_user.id_level=tbl_level.id_level');
        $this->db->join('tbl_leg_divisi', 'tbl_user.leg_level_id=tbl_leg_divisi.id_divisi and tbl_leg_divisi.na=\'n\'', 'left outer');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_user.na', 'n');
            $this->db->where('tbl_user.del', 'n');
        }

        if (isset($id_user))
            $this->db->where('tbl_user.id_user', $id_user);

        if (isset($id_divisi))
            $this->db->where('tbl_user.id_divisi', $id_divisi);
        else {
            $this->db->group_start();
            $this->db->where('tbl_user.leg_level_id <>', 0);
            $this->db->or_where('tbl_user.leg_level_id <>', null);
            $this->db->group_end();
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_jenis_spk_additional($data = array(), $params = array())
    {
        $oto_jenis_spk = $this->get_oto_jenis_spk(array(
            'id_jenis_spk' => $data->id_jenis_spk,
            'list' => true
        ));

        $data->templates = $oto_jenis_spk;

        return $data;
    }

    public function get_jenis_vendor_additional($data = array(), $params = array())
    {
        $oto_jenis_vendor = $this->get_oto_vendor(array(
            'id_jenis_vendor' => $data->id_jenis_vendor,
            'list' => true
        ));

        $data->dokumens = $oto_jenis_vendor;

        return $data;
    }

    public function get_nama_spk_additional($data = array(), $params = array())
    {
        $data->divisis = $this->get_oto_divisi(array(
            'id_nama_spk' => $data->id_nama_spk
        ));

        return $data;
    }

    public function get_oto_jenis_spk($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_oto_jenis = isset($params['id_oto_jenis']) ? $params['id_oto_jenis'] : null;
        $id_jenis_spk = isset($params['id_jenis_spk']) ? $params['id_jenis_spk'] : null;

        $this->db->select('tbl_leg_oto_jenis_spk.*');
        $this->db->from('tbl_leg_oto_jenis_spk');

        $this->db->join('tbl_leg_jenis_spk', 'tbl_leg_oto_jenis_spk.id_jenis_spk = tbl_leg_jenis_spk.id_jenis_spk');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_oto_jenis_spk.na', 'n');
            $this->db->where('tbl_leg_oto_jenis_spk.del', 'n');
        }

        if (isset($id_oto_jenis))
            $this->db->where('tbl_leg_oto_jenis_spk.id_oto_jenis', $id_oto_jenis);

        if (isset($id_jenis_spk))
            $this->db->where('tbl_leg_oto_jenis_spk.id_jenis_spk', $id_jenis_spk);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        if (is_array($result)) {
            foreach ($result as $index => $list) {
                $result[$index] = $this->get_oto_jenis_spk_additional($list, $params);;
            }
        } else if (isset($result))
            $result = $this->get_oto_jenis_spk_additional($result, $params);

        return $result;
    }

    public function get_oto_vendor($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_oto_vendor = isset($params['id_oto_vendor']) ? $params['id_oto_vendor'] : null;
        $id_jenis_vendor = isset($params['id_jenis_vendor']) ? $params['id_jenis_vendor'] : null;


        // //lha 27.04.2021 diubah untuk diiket ke master vendor dokumen
        // $this->db->select('tbl_leg_oto_vendor.*');
        // $this->db->from('tbl_leg_oto_vendor');
        // $this->db->join('tbl_leg_jenis_vendor', 'tbl_leg_oto_vendor.id_jenis_vendor= tbl_leg_jenis_vendor.id_jenis_vendor');
        $this->db->select('tbl_leg_oto_vendor.*');
        $this->db->select('tbl_vendor_master_dokumen.nama as nama_dokumen');
        $this->db->from('tbl_leg_oto_vendor');
        $this->db->join('tbl_vendor_master_dokumen', 'tbl_vendor_master_dokumen.id_master_dokumen= tbl_leg_oto_vendor.id_master_dokumen');
        $this->db->join('tbl_leg_jenis_vendor', 'tbl_leg_oto_vendor.id_jenis_vendor= tbl_leg_jenis_vendor.id_jenis_vendor');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_oto_vendor.na', 'n');
            $this->db->where('tbl_leg_oto_vendor.del', 'n');
        }

        if (isset($id_oto_vendor))
            $this->db->where('tbl_leg_oto_vendor.id_oto_vendor', $id_oto_vendor);

        if (isset($id_jenis_vendor))
            $this->db->where('tbl_leg_oto_vendor.id_jenis_vendor', $id_jenis_vendor);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_oto_divisi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_oto_divisi = isset($params['id_oto_divisi']) ? $params['id_oto_divisi'] : null;
        $id_nama_spk = isset($params['id_nama_spk']) ? $params['id_nama_spk'] : null;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;

        $this->db->select('tbl_leg_oto_divisi.*');
        $this->db->select('tbl_leg_divisi.nama_divisi');
        $this->db->from('tbl_leg_oto_divisi');

        $this->db->join('tbl_leg_divisi', 'tbl_leg_oto_divisi.id_divisi = tbl_leg_divisi.id_divisi');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_oto_divisi.na', 'n');
            $this->db->where('tbl_leg_oto_divisi.del', 'n');
        }

        if (isset($id_oto_divisi))
            $this->db->where('tbl_leg_oto_divisi.id_oto_divisi', $id_oto_divisi);

        if (isset($id_nama_spk))
            $this->db->where('tbl_leg_oto_divisi.id_nama_spk', $id_nama_spk);

        if (isset($id_divisi))
            $this->db->where('tbl_leg_oto_divisi.id_divisi', $id_divisi);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_oto_jenis_spk_additional($data = array(), $params = array())
    {
        $data->url_files = site_url(
            'spk/view_file?file=' .
                $data->files
        );

        return $data;
    }

    public function get_karyawan($params = array())
    {
        $id_user = isset($params['id_user']) ? $params['id_user'] : null;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $ho = isset($params['ho']) ? $params['ho'] : null;
        $plant = isset($params['plant']) ? $params['plant'] : null;
        $leg_level_id = isset($params['leg_level_id']) ? $params['leg_level_id'] : null;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;

        $this->db->select("tbl_karyawan.*,tbl_user.*");
        $this->db->from('tbl_karyawan');
        $this->db->join('tbl_user', 'tbl_user.id_karyawan=tbl_karyawan.id_karyawan', 'left outer');

        if (isset($id_spk)) {
            $this->db->join('tbl_leg_divisi', 'tbl_user.leg_level_id=tbl_leg_divisi.id_divisi', 'inner');
            $this->db->join('tbl_leg_oto_divisi', 'tbl_leg_divisi.id_divisi=tbl_leg_oto_divisi.id_divisi', 'inner');
            $this->db->join('tbl_leg_approval', 'tbl_leg_approval.id_oto_div=tbl_leg_oto_divisi.id_oto_divisi', 'inner');

            $this->db->where('tbl_leg_approval.id_spk', $id_spk);
        }

        $this->db->where('tbl_user.na', 'n');
        $this->db->where('tbl_user.del', 'n');
        $this->db->where('tbl_karyawan.na', 'n');
        $this->db->where('tbl_karyawan.del', 'n');

        if (isset($ho) && $ho)
            $this->db->where('tbl_karyawan.ho', 'y');

        if (isset($id_user))
            $this->db->where('tbl_user.id_user', $id_user);

        if (isset($nik))
            $this->db->where('tbl_karyawan.nik', $nik);

        if (isset($leg_level_id)) {
            if (is_array($leg_level_id))
                $this->db->where_in('tbl_user.leg_level_id', $leg_level_id);
            else
                $this->db->where('tbl_user.leg_level_id', $leg_level_id);
        }

        if (isset($plant)) {
            if (is_array($plant))
                $this->db->where_in('tbl_karyawan.gsber', $plant);
            else
                $this->db->where('tbl_karyawan.gsber', $plant);
        }

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    function get_data_plant($conn = NULL, $plant = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('ZDMMSPLANT.*');
        $this->db->select('ZDMMSPLANT.WERKS as plant');
        $this->db->select('ZDMMSPLANT.TPPCO as factory');
        $this->db->select('ZDMMSPLANT.NAME1 as plant_name');
        $this->db->select('ZDMMSPLANT.REGION as region_name');
        $this->db->from('SAPSYNC.dbo.ZDMMSPLANT');
        if ($plant !== NULL) {
            $this->db->where('ZDMMSPLANT.werks', $plant);
        }
        $this->db->order_by("ZDMMSPLANT.werks", "asc");
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
    function get_data_jenis_vendor($conn = NULL, $id_jenis_vendor = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('tbl_leg_jenis_vendor.*');
        $this->db->from('tbl_leg_jenis_vendor');
        if ($id_jenis_vendor !== NULL) {
            $this->db->where('tbl_leg_jenis_vendor.id_jenis_vendor', $id_jenis_vendor);
        }
        $this->db->where('tbl_leg_jenis_vendor.na', 'n');
        $this->db->where('tbl_leg_jenis_vendor.del', 'n');
        $this->db->order_by("tbl_leg_jenis_vendor.jenis_vendor", "asc");
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
    function get_data_jenis_spk($conn = NULL, $id_jenis_spk = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('tbl_leg_jenis_spk.*');
        $this->db->from('tbl_leg_jenis_spk');
        if ($id_jenis_spk !== NULL) {
            $this->db->where('tbl_leg_jenis_spk.id_jenis_spk', $id_jenis_spk);
        }
        $this->db->where('tbl_leg_jenis_spk.na', 'n');
        $this->db->where('tbl_leg_jenis_spk.del', 'n');
        $this->db->order_by("tbl_leg_jenis_spk.jenis_spk", "asc");
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
    function get_data_kualifikasi_spk($conn = NULL, $id_kualifikasi_spk = NULL, $id_jenis_spk = NULL, $kualifikasi = NULL, $lifnr = NULL, $vendor = NULL, $kualifikasi_spk = NULL)
    {
        $nama_folder = $lifnr . ' - ' . $vendor;
        $nama_file = "tbl_leg_kualifikasi_spk.kualifikasi_spk+' - " . $lifnr . "'";

        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select("(select top 1 tbl_folder.id_folder from tbl_folder where tbl_folder.nama='$nama_folder' and tbl_folder.na='n' order by tbl_folder.id_folder desc) as id_folder");
        $this->db->select("(select top 1 tbl_file.id_file from tbl_file where tbl_file.nama=$nama_file) as id_file");
        $this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.nama=$nama_file) as link");
        $this->db->select("(select top 1 tbl_file.tipe from tbl_file where tbl_file.nama=$nama_file) as tipe_file");
        $this->db->select('tbl_leg_kualifikasi_spk.*');
        $this->db->select('tbl_leg_jenis_spk.jenis_spk');
        $this->db->from('tbl_leg_kualifikasi_spk');
        $this->db->join('tbl_leg_jenis_spk', 'tbl_leg_kualifikasi_spk.id_jenis_spk=tbl_leg_jenis_spk.id_jenis_spk', 'left outer');
        if ($id_kualifikasi_spk !== NULL) {
            $this->db->where('tbl_leg_kualifikasi_spk.id_kualifikasi_spk', $id_kualifikasi_spk);
        }
        if ($id_jenis_spk !== NULL) {
            $this->db->where('tbl_leg_kualifikasi_spk.id_jenis_spk', $id_jenis_spk);
        }
        if ($kualifikasi != NULL) {
            if (is_string($kualifikasi)) $kualifikasi = explode(",", $kualifikasi);
            $this->db->where_in('tbl_leg_kualifikasi_spk.id_kualifikasi_spk', $kualifikasi);
        }
        if ($kualifikasi_spk !== NULL) {
            $this->db->where('tbl_leg_kualifikasi_spk.kualifikasi_spk', $kualifikasi_spk);
        }

        $this->db->where('tbl_leg_kualifikasi_spk.na', 'n');
        $this->db->where('tbl_leg_kualifikasi_spk.del', 'n');
        $this->db->order_by("tbl_leg_kualifikasi_spk.kualifikasi_spk", "asc");
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
    function get_data_matrix($conn = NULL, $lifnr = NULL, $ekorg = NULL, $search = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('LIFNR, EKORG, NAME1, CITY1, STRAS, PSTLZ, TELF1, SORTL, KALSK, KTOKK, PKPST, status_pkp, id_jenis_vendor, jenis_vendor, id_jenis_spk, jenis_spk, kualifikasi, status, list_kualifikasi');
        $this->db->from('vw_spk_zdmvendor');
        if ($lifnr !== NULL) {
            $this->db->where('lifnr', $lifnr);
        }
        if ($ekorg !== NULL) {
            $this->db->where('ekorg', $ekorg);
        }
        if ($search !== NULL) {
            $this->db->where("(ekorg like '%$search%' or ekorg like '%" . strtoupper($search) . "%')or(name1 like '%$search%' or name1 like '%" . strtoupper($search) . "%')or(city1 like '%$search%')or(jenis_spk like '%$search%')");
        }
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
    function ck_data_matrix($conn = NULL, $lifnr = NULL, $ekorg = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('tbl_leg_zdmvendor_matrix.*');
        $this->db->from('tbl_leg_zdmvendor_matrix');
        if ($lifnr !== NULL) {
            $this->db->where('tbl_leg_zdmvendor_matrix.lifnr', $lifnr);
        }
        if ($ekorg !== NULL) {
            $this->db->where('tbl_leg_zdmvendor_matrix.ekorg', $ekorg);
        }
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
    function get_data_dokumen_vendor($conn = NULL, $id_jenis_vendor = NULL, $lifnr = NULL, $vendor = NULL)
    {
        $nama_folder = $lifnr . ' - ' . $vendor;
        $nama_file = "tbl_vendor_master_dokumen.nama+' - " . $lifnr . "'";

        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select("(select top 1 tbl_folder.id_folder from tbl_folder where tbl_folder.nama='$nama_folder' and tbl_folder.na='n' order by tbl_folder.id_folder desc) as id_folder");
        $this->db->select("(select top 1 tbl_file.id_file from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as id_file");
        $this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as link");
        $this->db->select("(select top 1 tbl_file.tipe from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as tipe_file");
        // // $this->db->select('tbl_leg_oto_vendor.*');
        $this->db->select('tbl_leg_oto_vendor.id_oto_vendor');
        $this->db->select('tbl_leg_oto_vendor.id_jenis_vendor');
        $this->db->select('tbl_leg_oto_vendor.id_status');
        $this->db->select('tbl_leg_oto_vendor.mandatory');
        $this->db->select('tbl_vendor_master_dokumen.nama as nama_dokumen_vendor');
        $this->db->from('tbl_leg_oto_vendor');
        // //lha
        $this->db->join('tbl_vendor_master_dokumen', 'tbl_vendor_master_dokumen.id_master_dokumen = tbl_leg_oto_vendor.id_master_dokumen');
        if ($id_jenis_vendor !== NULL) {
            $this->db->where('tbl_leg_oto_vendor.id_jenis_vendor', $id_jenis_vendor);
        }
        $this->db->where('tbl_leg_oto_vendor.na', 'n');
        $this->db->where('tbl_leg_oto_vendor.del', 'n');
        $this->db->order_by("tbl_leg_oto_vendor.nama_dokumen_vendor", "asc");
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }

    function get_data_file($conn = NULL, $lifnr = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('DISTINCT(tbl_file.nama)');
        $this->db->from('tbl_file');
        $this->db->join('tbl_folder', 'tbl_folder.id_folder = tbl_file.id_folder', 'left outer');
        if ($lifnr !== NULL) {
            // $this->db->where("tbl_folder.nama like'%".$lifnr."%'");
        }
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
    function get_data_folder($conn = NULL, $parent_folder = NULL, $nama = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('tbl_folder.*');
        $this->db->from('tbl_folder');
        if ($parent_folder !== NULL) {
            $this->db->where('tbl_folder.parent_folder', $parent_folder);
        }
        if ($nama !== NULL) {
            $this->db->where('tbl_folder.nama', $nama);
        }
        $this->db->where('tbl_folder.na', 'n');
        $this->db->where('tbl_folder.del', 'n');
        $this->db->order_by("tbl_folder.nama", "asc");

        $query  = $this->db->get();
        $result = $query->result();
        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }

    //lha go live master vendor	
    function get_data_master_vendor($conn = NULL, $lifnr = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('tbl_vendor_data.*');
        $this->db->from('tbl_vendor_data');
        if ($lifnr !== NULL) {
            $this->db->where('lifnr', $lifnr);
        }
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
}
