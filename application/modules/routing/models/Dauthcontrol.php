<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Email Routing - Authorization Control Report Model
@author     : Octe Reviyanto Nugroho
@contributor	:
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dauthcontrol extends CI_Model
{
    private $portal_db = "";
    private $dashboard_db = "";

    public function __construct()
    {
        parent::__construct();
        $this->portal_db = DB_PORTAL;
        $this->dashboard_db = DB_DEFAULT;

    }

    public function get_roles($all = null, $id = null, $single_result = false)
    {
        $this->general->connectDbPortal();

        $this->db->select('*');
        $this->db->from('tbl_ac_roles');

        if (isset($all) && strtolower($all) == "all") {
            $this->db->where('na', 'y');
            $this->db->or_where('del', 'y');
        }else{
            $this->db->where('na', 'n');
            $this->db->where('del', 'n');

        }
        if (isset($id)) {
            $this->db->where('id_role', $id);
        }
        if ($single_result)
            $result = $this->db->get()->row();
        else
            $result = $this->db->get()->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_role_jabatans($id_role = null)
    {
        $this->general->connectDbPortal();

        $this->db->select('id_jabatan');

        $this->db->from('tbl_ac_roles_jabatans');

        $this->db->where('id_role',$id_role);

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_role_divisis($id_role = null)
    {
        $this->general->connectDbPortal();

        $this->db->select('id_divisi');

        $this->db->from('tbl_ac_roles_divisis');

        $this->db->where('id_role',$id_role);

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_role_departemens($id_role = null)
    {
        $this->general->connectDbPortal();

        $this->db->select('id_departemen');

        $this->db->from('tbl_ac_roles_departemens');

        $this->db->where('id_role',$id_role);

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_data($id_role = null, $all = null, $jabatans = array(), $divisis = array(), $departemens = array())
    {
        $this->general->connectDbPortal();

//        $filter = "AND acr.na = 'n' AND acr.del = 'n'";
//
//        if ($all == "ALL")
//            $filter = "";
//
//
//        $string = "
//            select
//                tk.nik,
//                tk.nama,
//                tj.nama as nama_jabatan,
//                tk.posst as nama_jabatan_karyawan,
//                tk.ho,
//                tk.gsber as plant,
//                wmp.plant_name
//            FROM tbl_ac_roles as acr
//            INNER JOIN tbl_karyawan as tk
//            ON  tk.del = 'n'
//            AND tk.na = 'n'
//            $filter
//            INNER JOIN tbl_user as tu
//            ON tu.id_karyawan = tk.id_karyawan AND tu.del = 'n' AND tu.na = 'n'
//            INNER JOIN tbl_jabatan as tj
//            ON tu.id_jabatan = tj.id_jabatan AND tj.del = 'n' AND tj.na = 'n'
//            INNER JOIN tbl_ac_roles_jabatans acrj
//            ON acrj.id_jabatan = tj.id_jabatan AND acrj.id_role = acr.id_role
//            LEFT OUTER JOIN tbl_divisi as tdiv
//            ON tu.id_divisi = tdiv.id_divisi AND tdiv.del = 'n' AND tdiv.na = 'n'
//            LEFT OUTER JOIN tbl_ac_roles_divisis acrdiv
//            ON tu.id_divisi = acrdiv.id_divisi AND acrj.id_role = acr.id_role
//            LEFT OUTER JOIN tbl_departemen as tdep
//            ON tu.id_departemen = tdep.id_departemen AND tdep.del = 'n' AND tdep.na = 'n'
//            LEFT OUTER JOIN tbl_ac_roles_departemens acrdep
//            ON tu.id_departemen = acrdep.id_departemen AND acrj.id_role = acr.id_role
//            LEFT JOIN tbl_wf_master_plant as wmp
//            ON wmp.plant=tk.gsber
//            WHERE acr.id_role = ? AND (tu.id_divisi <> 0 OR tu.id_jabatan <> 0 OR tu.id_departemen <> 0)
//            AND tk.id_karyawan!='73560001' and tk.id_karyawan!='73560002' and tu.id_karyawan!='6724' and tu.id_karyawan!='6725' and tk.id_karyawan !='73560003' and tk.id_karyawan !='10000001'
//            GROUP BY
//            tk.nik,
//            tk.nama,
//            tj.nama,
//            tk.posst,
//            tk.ho,
//            tk.gsber,
//            wmp.plant_name
//            ";
//
//        $query = $this->db->query($string, array($id_role));
//        $result = $query->result();

        // ----------------------------------------------------------------------------------------

        $this->db->select("
                tbl_karyawan.nik,
                tbl_karyawan.nama,
                tbl_jabatan.nama as nama_jabatan,
                tbl_karyawan.posst as nama_jabatan_karyawan,
                tbl_karyawan.ho,
                tbl_karyawan.gsber as plant,
                tbl_wf_master_plant.plant_name");
        $this->db->from("tbl_karyawan");
        $this->db->join("tbl_user",'tbl_karyawan.id_karyawan=tbl_user.id_karyawan');
        $this->db->join("tbl_jabatan",'tbl_user.id_jabatan=tbl_jabatan.id_jabatan','left outer');
        $this->db->join("tbl_wf_master_plant",'tbl_wf_master_plant.plant=tbl_karyawan.gsber','left outer');

        $this->db->where_not_in("tbl_karyawan.id_karyawan",array(
            '73560002',
            '6724',
            '6725',
            '73560003',
            '10000001'
        ));

        if(isset($jabatans) && !empty($jabatans))
        {
            $this->db->where_in("tbl_user.id_jabatan",$jabatans);
        }
        if(isset($divisis))
        {
            $divisis[] = "";
            $this->db->where_in("tbl_user.id_divisi",$divisis);
        }
        if(isset($departemens))
        {
            $departemens[] = "";
            $this->db->where_in("tbl_user.id_departemen",$departemens);
        }

        $this->db->order_by("tbl_karyawan.nama");

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }
}