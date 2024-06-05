<?php
/**
 * @application  : Kirana News Model (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Dinfokirana extends CI_Model
{
    public $mainTable = "tbl_news";
    public $mainPK = "id_news";

    function get_all_data($id = NULL, $all = NULL)
    {
        $this->general->connectDbPortal();

        $this->db->select("$this->mainTable.*");
        $this->db->from($this->mainTable);
        if (isset($id)) {
            $this->db->where($this->mainTable.'.id_news', base64_decode($id));
        }
        if (!isset($all)) {
            $this->db->where($this->mainTable.'.del', 'n');
        }
        $this->db->order_by($this->mainTable.'.id_news', 'DESC');
        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();
        return $result;
    }

    public function get_all_komentar_data($id_news = NULL, $all = NULL)
    {
        $this->general->connectDbPortal();

        $id_news = $this->generate->kirana_decrypt($id_news);

        $this->db->select('
            tbl_komentar.*,
            tbl_karyawan.nama as nama_karyawan
        ');
        $this->db->from('tbl_komentar');
        $this->db->join('tbl_user','tbl_komentar.login_buat = tbl_user.id_user','left');
        $this->db->join('tbl_karyawan','tbl_karyawan.id_karyawan = tbl_user.id_karyawan','left');

        if (isset($id_news)) {
            $this->db->where('tbl_komentar.id_news', $id_news);
        }
        if (!isset($all)) {
            $this->db->where('tbl_komentar.del', 'n');
        }

        $this->db->order_by('tbl_komentar.id_komentar', 'DESC');
        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();
        return $result;
    }

    public function set_data($id,$action)
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

    public function komentar_set_data($id,$action)
    {

        $this->general->connectDbPortal();
        $return = $this->general->set($action, "tbl_komentar", array(
            array(
                'kolom' => "id_komentar",
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

                $uploaddir  = realpath('./') . '/assets/file/infokirana/';
                if (!file_exists($uploaddir)) {
                    mkdir($uploaddir, 0777, true);
                }

                $config['upload_path']          = $uploaddir;
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 100;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;

                $this->load->library('upload', $config);
                foreach ($_FILES as $input_name => $file) {

                    if ( ! $this->upload->do_upload($input_name))
                    {
                        $uploadError= $this->upload->display_errors();

                    }else{
                        $upload_data = $this->upload->data();

                        $uploaded[$input_name] = 'assets/file/infokirana/'.$upload_data['file_name'];
                    }
                }
            }

            $data_row = array_merge($data_row, $data, $uploaded);

            $this->dgeneral->update('tbl_news', $data_row, $where);

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

        $uploaded = array();
        $uploadError = array();
        if (isset($_FILES)) {

            $uploaddir  = realpath('./') . '/assets/file/infokirana/';
            if (!file_exists($uploaddir)) {
                mkdir($uploaddir, 0777, true);
            }
            $config['upload_path']          = $uploaddir;
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 100;
            $config['max_width']            = 1024;
            $config['max_height']           = 768;

            $this->load->library('upload', $config);
            foreach ($_FILES as $input_name => $file) {

                if ( ! $this->upload->do_upload($input_name))
                {
                    $uploadError= $this->upload->display_errors();

                }else{
                    $upload_data = $this->upload->data();

                    $uploaded[$input_name] = 'assets/file/infokirana/'.$upload_data['file_name'];
                }
            }
        }

        $data_row = array_merge($data_row, $data,$uploaded);

        $data = $this->dgeneral->insert('tbl_news', $data_row);

        if ($this->dgeneral->status_transaction() === FALSE && count($uploadError)>0) {
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

        $this->dgeneral->update('tbl_news', $data_row,
            array(
                array(
                    'kolom' => 'id_news',
                    'value' => $id
                )
            )
        );

        if ($this->dgeneral->status_transaction() === FALSE ) {
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

            $this->db->select('tbl_news.*');
            $this->db->from('tbl_news');
            $this->db->where('tbl_news.id_news', $id);
            $query = $this->db->get();
            $result = $query->result();

            $this->general->closeDb();
        } else {
            $result = array();
        }

        return $result;
    }

    public function GetFields($_tbl, $_key, $_value, $_results, $_group='', $_order='', $_limit= '') {
        $this->general->connectDbPortal();

        $this->db->select("$_results");
        $this->db->from("$_tbl");
        $this->db->where("$_key='$_value'");
        if(isset($_group))
            $this->db->group_by("$_group");
        if(isset($_order))
            $this->db->order_by("$_order");
        if(isset($_limit))
            $this->db->limit("$_limit");

        $query = $this->db->get();

        $result = $query->row();

        $this->general->closeDb();

        return $result;
    }

}