<?php
/**
 * @application  : Admin staff Model (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Dadminstaff extends CI_Model
{
    public $mainTable = "tbl_karyawan";
    public $mainPK = "id_karyawan";

    function get_all_data($id = NULL, $nik = NULL, $all = NULL)
    {
        $this->general->connectDbPortal();

        $this->db->select("$this->mainTable.*,tbl_departemen.nama as nama_departemen");
        $this->db->from($this->mainTable);
        $this->db->join('tbl_user', $this->mainTable . '.id_karyawan = tbl_user.id_karyawan', 'left outer');
        $this->db->join('tbl_level', 'tbl_user.id_level= tbl_level.id_level', 'left outer');
        $this->db->join('tbl_departemen', 'tbl_departemen.id_departemen= tbl_user.id_departemen', 'left outer');
        if (isset($id)) {
            $this->db->where($this->mainTable . '.' . $this->mainPK, $id);
        }
        if (isset($nik)) {
            $this->db->where($this->mainTable . '.nik', $nik);
        }
        if (!isset($all)) {
            $this->db->where($this->mainTable . '.del', 'n');
        }

        $this->db->where($this->mainTable . '.na', 'n');
        $this->db->order_by($this->mainTable . '.' . $this->mainPK, 'DESC');
        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();
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
            $uploaded = array();
            $uploadError = array();
            if (isset($_FILES)) {

                $uploaddir = KIRANA_PATH_APPS . KIRANA_PATH_APPS_IMAGE_FOLDER . SETTINGS_FOTO_PATH;
                if (!file_exists($uploaddir)) {
                    mkdir($uploaddir, 0777, true);
                }

                $config['upload_path'] = $uploaddir;
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 5000;
                $config['file_ext_tolower'] = true;
                $config['overwrite'] = true;
                $config['file_name'] = str_pad($id, 8, "0", STR_PAD_LEFT);

                $this->load->library('upload', $config);
                foreach ($_FILES as $input_name => $file) {

                    if (!$this->upload->do_upload($input_name)) {
                        $uploadError = $this->upload->display_errors();

                    } else {
                        $upload_data = $this->upload->data();

                        $uploaded[$input_name] = KIRANA_PATH_APPS_IMAGE_FOLDER . SETTINGS_FOTO_PATH . $upload_data['file_name'];
                    }
                }
            }

//            $uploaded = $this->general->upload_files($_FILES,'foto');

            $data_row = array_merge($data_row, $data, $uploaded);

            $this->dgeneral->update($this->mainTable, $data_row, $where);

            if ($this->dgeneral->status_transaction() === FALSE && count($uploadError) > 0) {
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

    public function get_data($id)
    {

        if (isset($id)) {
            $this->general->connectDbPortal();

            $this->db->select($this->mainTable . '.*,tbl_departemen.nama as nama_departemen');
            $this->db->from($this->mainTable);
            $this->db->join('tbl_user', $this->mainTable . '.id_karyawan = tbl_user.id_karyawan', 'left outer');
            $this->db->join('tbl_level', 'tbl_user.id_level= tbl_level.id_level', 'left outer');
            $this->db->join('tbl_departemen', 'tbl_departemen.id_departemen= tbl_user.id_departemen', 'left outer');
            $this->db->where($this->mainTable . '.' . $this->mainPK, $id);
            $query = $this->db->get();
            $result = $query->result();

            $this->general->closeDb();
        } else {
            $result = array();
        }

        foreach ($result as $user) {

            $user->user_image = $this->get_user_image($user);
        }

        return $result;
    }

    public function get_user_image($user)
    {
        if ($user->gender == "l") {
            $image = base_url() . "assets/apps/img/avatar5.png";
        } else {
            $image = base_url() . "assets/apps/img/avatar2.png";
        }


        if ($user->gambar) {
            $links = explode("/", $user->gambar);
            $data_image = site_url()."/assets/apps/" . $links[0] . "/" . $links[1] . "/" . strtoupper($links[2]);
            $headers = get_headers($data_image);
            if ($headers[0] == "HTTP/1.1 200 OK") {
                $image = $data_image;
            }
            $data_image = site_url()."/assets/apps/" . strtolower($user->gambar);
            $headers = get_headers($data_image);
            if ($headers[0] == "HTTP/1.1 200 OK") {
                $image = $data_image;
            }
        }

        return $image;
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

        $result = $query->row();

        $this->general->closeDb();

        return $result;
    }


}