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

class Dmaster extends CI_Model
{
    function get_data_plant($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $query = $this->db->query("EXEC SP_SPL_Data_Supporting 
            @nik = '" . base64_decode($this->session->userdata('-nik-')) . "', 
            @tipe_data = 'plant'
        ");

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

    function get_data_departemen($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        // $this->db->select('tbl_departemen.id_departemen AS id,
        //     tbl_departemen.id_departemen,
        //     tbl_departemen.nama AS departemen
        // ');
        // $this->db->from('tbl_departemen');

        // $this->db->where('tbl_departemen.na', 'n');
        // $this->db->where('tbl_departemen.del', 'n');

        // if (isset($param['plant']) && $param['plant'] !== NULL)
        //     $this->db->where('tbl_departemen.gsber', $param['plant']);

        // if (isset($param['search']) && $param['search'] !== NULL) {
        //     $this->db->group_start();
        //     $this->db->like('tbl_departemen.nama', $param['search'], 'both');
        //     // $this->db->or_like('tbl_departemen.nama', $param['search'], 'both');
        //     $this->db->group_end();
        // }

        // $query = $this->db->get();

        $qr = "EXEC SP_SPL_Data_Supporting 
            @nik = '" . base64_decode($this->session->userdata('-nik-')) . "', 
            @tipe_data = 'departemen'
        ";
        if (isset($param['id_departemen']) && $param['id_departemen'] !== NULL)
            $qr .= ", @departemen = '" . $param['id_departemen'] . "'";

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

    function get_data_seksie($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        // $this->db->distinct();
        // $this->db->select('vw_spl_data_karyawan.id_seksi AS id,
        //     vw_spl_data_karyawan.id_seksi,
        //     vw_spl_data_karyawan.seksi
        // ');
        // $this->db->from('vw_spl_data_karyawan');
        // $this->db->where('vw_spl_data_karyawan.id_seksi <>', 0);

        // if (isset($param['plant']) && $param['plant'] !== NULL)
        //     $this->db->where('vw_spl_data_karyawan.gsber', $param['plant']);
        // if (isset($param['id_departemen']) && $param['id_departemen'] !== NULL)
        //     $this->db->where('vw_spl_data_karyawan.id_departemen', $param['id_departemen']);
        // if (isset($param['id_seksi']) && $param['id_seksi'] !== NULL)
        //     $this->db->where('vw_spl_data_karyawan.id_seksi', $param['id_seksi']);

        // if (isset($param['search']) && $param['search'] !== NULL) {
        //     $this->db->group_start();
        //     $this->db->like('vw_spl_data_karyawan.seksi', $param['search'], 'both');
        //     // $this->db->or_like('tbl_departemen.nama', $param['search'], 'both');
        //     $this->db->group_end();
        // }

        // $query = $this->db->get();
        // echo json_encode($this->db->error());exit();
        // echo $this->db->last_query();exit;
        $query = $this->db->query("
            EXEC SP_SPL_Data_Supporting 
            @nik = '" . base64_decode($this->session->userdata('-nik-')) . "', 
            @tipe_data = 'seksie',
            @departemen = '" . $param['id_departemen'] . "'
        ");
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

    function get_data_unit($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $query = $this->db->query("
            EXEC SP_SPL_Data_Supporting 
            @nik = '" . base64_decode($this->session->userdata('-nik-')) . "', 
            @tipe_data = 'unit',
            @seksie = '" . $param['id_seksie'] . "'
        ");

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

    function get_data_role($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_spl_role.*');
        $this->db->from('tbl_spl_role');

        $this->db->where('tbl_spl_role.na', 'n');
        $this->db->where('tbl_spl_role.del', 'n');

        if (isset($param['level']) && $param['level'] !== NULL)
            $this->db->where('tbl_spl_role.level', $param['level']);

        $query = $this->db->get();

        if (isset($param['single_row']) && $param['single_row'] !== NULL)
            $result = $query->row();
        else
            $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_keterangan_lembur($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_spl_mketerangan.*');
        $this->db->from('tbl_spl_mketerangan');

        if (isset($param['tipe']) && $param['tipe'] !== NULL)
            $this->db->where('tbl_spl_mketerangan.tipe', $param['tipe']);
        if (!isset($param['all']) || (isset($param['all']) && $param['all'] == "no"))
            $this->db->where('tbl_spl_mketerangan.na', 'n');

        $query = $this->db->get();

        if (isset($param['single_row']) && $param['single_row'] !== NULL)
            $result = $query->row();
        else
            $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    // function get_data_mpl($conn = NULL, $plant = NULL, $id_unit = NULL, $tanggal = NULL, $shift = NULL)
    function get_data_mpl($param = NULL)
    {
        // if ($conn !== NULL)
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('
            tbl_spl_master_plan.*,
            tbl_departemen.nama AS departemen,
            tbl_seksi.nama AS seksie,
            tbl_unit.nama AS unit,
        ');
        $this->db->from('tbl_spl_master_plan');
        $this->db->join('tbl_departemen', "tbl_departemen.id_departemen = tbl_spl_master_plan.id_departemen AND tbl_departemen.na = 'n'", 'left');
        $this->db->join('tbl_seksi', "tbl_seksi.id_seksi = tbl_spl_master_plan.id_seksie AND tbl_seksi.na = 'n'", 'left');
        $this->db->join('tbl_unit', "tbl_unit.id_unit = tbl_spl_master_plan.id_unit AND tbl_unit.na = 'n'", 'left');

        // if ($plant !== NULL) {
        //     $this->db->where('tbl_spl_master_plan.plant', $plant);
        // }
        // if ($id_unit !== NULL) {
        //     $this->db->where('tbl_spl_master_plan.id_unit', $id_unit);
        // }
        // if ($tanggal !== NULL) {
        //     $this->db->where('tbl_spl_master_plan.tanggal', $tanggal);
        // }
        // if ($shift !== NULL) {
        //     $this->db->where('tbl_spl_master_plan.shift', $shift);
        // }
        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('tbl_spl_master_plan.plant', $param['plant']);
        if (isset($param['id_departemen']) && $param['id_departemen'] !== NULL)
            $this->db->where('tbl_spl_master_plan.id_departemen', $param['id_departemen']);
        if (isset($param['id_seksi']) && $param['id_seksi'] !== NULL)
            $this->db->where('tbl_spl_master_plan.id_seksie', $param['id_seksi']);
        if (isset($param['id_unit']) && $param['id_unit'] !== NULL)
            $this->db->where('tbl_spl_master_plan.id_unit', $param['id_unit']);
        if (isset($param['tanggal_spl']) && $param['tanggal_spl'] !== NULL)
            $this->db->where('tbl_spl_master_plan.tanggal', $param['tanggal_spl']);
        if (isset($param['shift']) && $param['shift'] !== NULL)
            $this->db->where('tbl_spl_master_plan.shift', $param['shift']);
        if (isset($param['is_lembur']) && $param['is_lembur'] !== NULL)
            $this->db->where('tbl_spl_master_plan.jumlah_jam_lembur > 0');

        $query  = $this->db->get();
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

    function get_data_mpl_bom($conn = NULL, $filter_pabrik = NULL, $filter_bulan = NULL, $filter_unit = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();
        $this->datatables->select("
            id_data,
            bulan_tahun,
            plant,
            tanggal_buat,
            tanggal_format,
            nama_departemen,
            nama_seksie,
            nama_unit,
            shift,
            jumlah_jam_lembur
        ");
        $this->datatables->from('vw_spl_data');

        if ($filter_pabrik != NULL) {
            if (is_string($filter_pabrik)) $filter_pabrik = explode(",", $filter_pabrik);
            $this->datatables->where_in('plant', $filter_pabrik);
        }

        if ($filter_bulan != NULL) {
            $this->datatables->where('bulan_tahun', $filter_bulan);
        }

        if ($filter_unit != NULL) {
            if (is_string($filter_unit)) $filter_unit = explode(",", $filter_unit);
            $this->datatables->where_in('nama_unit', $filter_unit);
        }

        if ($conn !== NULL)
            $this->general->closeDb();

        $return = $this->datatables->generate();
        $raw = json_decode($return, true);
        $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_data"));
        return $this->general->jsonify($raw);
    }

    function get_data_detail($conn = NULL, $plant = NULL, $unit = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('tbl_unit.id_unit');
        $this->db->select('tbl_seksi.id_seksi');
        $this->db->select('tbl_departemen.id_departemen');
        $this->db->from('tbl_unit');
        $this->db->join('tbl_seksi', "tbl_seksi.id_seksi=tbl_unit.id_seksi and tbl_seksi.na='n'", 'left outer');
        $this->db->join('tbl_departemen', "tbl_departemen.id_departemen=tbl_seksi.id_departemen  and tbl_departemen.na='n'", 'left outer');
        $this->db->join('tbl_user', "tbl_user.id_departemen=tbl_departemen.id_departemen and tbl_user.na='n'", 'left outer');
        $this->db->join('tbl_karyawan', "tbl_karyawan on tbl_karyawan.id_karyawan=tbl_user.id_karyawan and tbl_karyawan.na='n'", 'left outer');
        if ($plant !== NULL) {
            $this->db->where('tbl_karyawan.gsber', $plant);
        }
        if ($unit !== NULL) {
            // $this->db->where('tbl_unit.nama', $unit);
            $this->db->where("tbl_unit.nama like '%$unit%'");
        }
        $this->db->limit(1);
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
}
