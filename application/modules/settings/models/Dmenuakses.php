<?php
/**
 * @application  : Jenis Sakit Model (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Dmenuakses extends CI_Model
{
    public $mainTable = "tbl_karyawan";
    public $mainPK = "id_karyawan";

    function get_all_data($id = NULL, $all = NULL)
    {
        $this->general->connectDbPortal();

        $this->db->select("$this->mainTable.*");
        $this->db->from($this->mainTable);
        if (isset($id)) {
            $this->db->where($this->mainTable . '.nik', ($id));
        }
        if (!isset($all)) {
            $this->db->where($this->mainTable . '.del', 'n');
        }

        $this->db->order_by($this->mainTable . '.' . $this->mainPK, 'DESC');
        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();
        return $result;
    }

    public function get_data($id)
    {

        if (isset($id)) {
            $this->general->connectDbPortal();

            $this->db->select($this->mainTable . '.*');
            $this->db->from($this->mainTable);
            $this->db->where($this->mainTable . '.' . $this->mainPK, $id);
            $query = $this->db->get();
            $result = $query->result();

            $this->general->closeDb();
        } else {
            $result = array();
        }

        return $result;
    }

    public function get_menu_akses($id = NULL, $parent=NULL)
    {
        $menu = "";

        if (isset($id)) {
            $this->general->connectDbPortal();
            $this->db->select('*');
            $this->db->from('tbl_menu m');
            $this->db->where('CHARINDEX(\''.$id.'\', CAST(nik_akses as VARCHAR(MAX))) >',0);
            if(empty($parent))
                $this->db->where('id_parent', 0);
            else
                $this->db->where('id_parent', $parent);

            $this->db->where('na', 'n');
            $this->db->order_by('urutan');

            $query = $this->db->get();
            $count = $query->num_rows();
            if($count>0)
            {
                $result =$query->result();
                if($count > 0)
                {
                    foreach ($result as $dt)
                    {
//                    $child = "";
                        $child = $this->get_menu_akses($id,$dt->id_menu);
                        $menu .= "<li>".$dt->nama." $child</li>";
                    }

                    $menu = "<ul>".$menu."</ul>";
                }
            }
        }
        return $menu;
    }

    public function get_menus ($id=null)
    {
        $query = $this->db->query("select * from tbl_menu where CHARINDEX('".$id."', nik_akses)>0 and na='n'");
        return $query->result();
    }

    public function get_karyawans($id=null,$except=null, $all=false)
    {
        $this->db->select('tbl_karyawan.*');
        $this->db->from('tbl_karyawan');
        $this->db->join('tbl_user','tbl_user.id_karyawan=tbl_karyawan.id_karyawan','left outer');

        $this->db->where('tbl_karyawan.na', 'n');
        $this->db->where('tbl_user.na', 'n');

        if(isset($except))
            $this->db->where('tbl_karyawan.id_karyawan !=', $except);

        $this->db->order_by('tbl_karyawan.nama');

        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }


    public function GetFields($_tbl, $_key, $_value, $_results, $_group = '', $_order = '', $_limit = '')
    {
        $this->general->connectDbPortal();

        $this->db->select("$_results");
        $this->db->from("$_tbl");
        $this->db->where("$_key='$_value'");
        if (isset($_group))
            $this->db->group_by("$_group");
        if (isset($_order))
            $this->db->order_by("$_order");
        if (isset($_limit))
            $this->db->limit("$_limit");

        $query = $this->db->get();

        if ($_limit != '')
            $result = $query->result();
        else
            $result = $query->row();

        $this->general->closeDb();

        return $result;
    }

}