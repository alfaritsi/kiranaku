<?php
/**
 * @application  : Kirana Menus Model (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Dmenus extends CI_Model
{
    public $mainTable = "tbl_menu";
    public $mainPK = "id_menu";

    function get_all_data($id = NULL, $all = NULL)
    {
        $this->general->connectDbPortal();

        $this->db->select("tbl_menu.*");
        $this->db->from('tbl_menu');

        if (!isset($all)) {
            $this->db->where($this->mainTable . '.del', 'n');
        }

        if (empty($id)) {
            $this->db->where($this->mainTable . '.id_parent', 0);
        } else {
            $this->db->where($this->mainTable . '.id_menu', $id);
        }
        $this->db->order_by($this->mainTable . '.urutan', 'ASC');
        $query = $this->db->get();

        if (isset($id))
            $result = $query->row();
        else
            $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_menu_tree($params = array())
    {
        $id_menu = isset($params['id_menu']) ? $params['id_menu'] : null;
        $parent_id = isset($params['parent_id']) ? $params['parent_id'] : null;
        $search_name = isset($params['search_name']) ? $params['search_name'] : null;
        $search_link = isset($params['search_link']) ? $params['search_link'] : null;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $na = isset($params['na']) ? $params['na'] : null;
        $del = isset($params['del']) ? $params['del'] : 'n';

        $query = $this->db->query("EXEC dbo.SP_Portal_ShowMenu ?,?,?,?,?,?,?", array($search_name, $id_menu, $parent_id, $nik, $search_link, $na, $del));

        $result = $query->result();

        return $result;
    }

    public function set_data($id, $action)
    {

        $this->general->connectDbPortal();
        $return = $this->general->set($action, $this->mainTable, array(
            array(
                'kolom' => $this->mainPK,
                'value' => $id
            )
        ));
        $this->general->closeDb();
        return $return;
    }

    public function update_data($id, $data, $where = NULL)
    {
        $datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        if (isset($id)) {
            $data_row = array(
                'login_edit' => base64_decode($this->session->userdata("-id_user-")),
                'tanggal_edit' => $datetime
            );

            $data_row = array_merge($data_row, $data);

            $this->dgeneral->update($this->mainTable, $data_row, $where);

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Data berhasil ditambahkan";
                $sts = "OK";
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    public function save_data($data)
    {
        $datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();


        $data_row = array(
            'login_buat' => base64_decode($this->session->userdata("-id_user-")),
            'tanggal_buat' => $datetime,
            'login_edit' => base64_decode($this->session->userdata("-id_user-")),
            'tanggal_edit' => $datetime,
            'na' => 'n',
            'del' => 'n'
        );

        $data_row = array_merge($data_row, $data);

        $data = $this->dgeneral->insert($this->mainTable, $data_row);

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    public function delete_data($id)
    {
        $datetime = date("Y-m-d H:i:s");

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = array(
            'login_edit' => base64_decode($this->session->userdata("-id_user-")),
            'tanggal_edit' => $datetime,
            'del' => 'y'
        );

        $data_row = array_merge($data_row);

        $this->dgeneral->update($this->mainTable, $data_row,
            array(
                array(
                    'kolom' => $this->mainPK,
                    'value' => $id
                )
            )
        );

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil dihapus";
            $sts = "OK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
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

    public function get_level($id = null)
    {
        $this->general->connectDbPortal();

        $this->db->select("tbl_level.*");
        $this->db->from("tbl_level");
        $this->db->where("na='n'");
        if (isset($id) && !empty($id))
            $this->db->where('id_level', $id);
        $this->db->order_by("id_level");

        $query = $this->db->get();
        if (isset($id) && !empty($id))
            $result = $query->row();
        else
            $result = $query->result();
        $this->general->closeDb();
        return $result;
    }

    public function get_divisi()
    {
        $this->general->connectDbPortal();

        $this->db->select("tbl_divisi.*");
        $this->db->from("tbl_divisi");
        $this->db->where("na='n'");
        $this->db->order_by("nama");

        $query = $this->db->get();
        $result = $query->result();
        $this->general->closeDb();
        return $result;
    }

    public function get_department()
    {
        $this->general->connectDbPortal();

        $this->db->select("tbl_departemen.*");
        $this->db->from("tbl_departemen");
        $this->db->where("na='n'");
        $this->db->order_by("nama");

        $query = $this->db->get();
        $result = $query->result();
        $this->general->closeDb();
        return $result;
    }

    public function get_karyawans($selected = null, $akses_level = null, $akses_divisi = null, $akses_departemen = null, $search = null)
    {
        $this->general->connectDbPortal();

        $this->db->select("tbl_karyawan.id_karyawan, tbl_karyawan.nama+' ('+convert(varchar,tbl_karyawan.id_karyawan)+')' as nama_karyawan");
        $this->db->from("tbl_karyawan");
        $this->db->join("tbl_user", 'tbl_karyawan.id_karyawan=tbl_user.id_karyawan', 'left outer');
        if (isset($selected)) {
            $this->db->group_start();
            $arr_selected = array_map('trim', explode('.', $selected));
            $arr_selected_chunk = array_chunk($arr_selected, 100);
            foreach ($arr_selected_chunk as $value) {
                $this->db->or_where_in("tbl_karyawan.id_karyawan", $value);
            }
            $this->db->group_end();
        }
        if (isset($akses_level)) {
            $this->db->group_start();
//            $arr_level = array_map('trim', explode('.', $akses_level));
            $arr_level = explode('.', $akses_level);
            $arr_level_chunk = array_chunk($arr_level, 100);
            foreach ($arr_level_chunk as $value) {
                foreach ($value as $v)
                    $this->db->or_where("tbl_user.id_level", $v);
            }

            $this->db->group_end();
        }
        if (isset($akses_divisi)) {
            $this->db->group_start();
//            $arr_divisi = array_map('trim',explode('.', $akses_divisi));
            $arr_divisi = explode('.', $akses_divisi);
            $arr_divisi_chunk = array_chunk($arr_divisi, 100);
            foreach ($arr_divisi_chunk as $value) {
                foreach ($value as $v)
                $this->db->or_where("tbl_user.id_divisi", $v);
            }
            $this->db->group_end();
        }
        if (isset($akses_departemen)) {
            $this->db->group_start();
//            $arr_departemen = array_map('trim',explode('.', $akses_departemen));
            $arr_departemen = explode('.', $akses_departemen);
            $arr_departemen_chunk = array_chunk($arr_departemen, 100);
            foreach ($arr_departemen_chunk as $value) {
                foreach ($value as $v)
                $this->db->or_where("tbl_user.id_departemen", $v);
            }
            $this->db->group_end();
        }
        if (isset($search)) {
            $this->db->like("tbl_karyawan.nik", $search);
            $this->db->like("tbl_karyawan.nama", $search);
        }
        $this->db->where('tbl_karyawan.na','n');

        $this->db->order_by("tbl_karyawan.nama");

        $query = $this->db->get();
        $result = $query->result();
        $this->general->closeDb();
        return $result;
    }

	function get_karyawan_ktp(){
		$this->db->select("tbl_karyawan.nik, tbl_karyawan.nama+' ('+convert(varchar,tbl_karyawan.id_karyawan)+')' as nama");
		$this->db->from("tbl_karyawan");
		$this->db->join("tbl_user", "tbl_user.id_karyawan = tbl_karyawan.id_karyawan", "left");
		$this->db->where("tbl_karyawan.na", "n");
		$this->db->where("LEN(tbl_karyawan.nik)", "5");
		$this->db->like("tbl_karyawan.nik", "9", "after");

		$query = $this->db->get();
		$result = $query->result();
		$this->general->closeDb();
		return $result;
	}

}
