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


class Dkirananews extends CI_Model
{
    function get_data_kirana_news($id = NULL, $all = NULL)
    {
        $this->general->connectDbPortal();

        $this->db->select('tbl_kirananews.*');
        $this->db->from('tbl_kirananews');
        if (isset($id)) {
            $this->db->where('tbl_kirananews.id_kirananews', base64_decode($id));
        }
        if (!isset($all)) {
            $this->db->where('tbl_kirananews.del', 'n');
        }

        $this->db->order_by('tbl_kirananews.id_kirananews', 'DESC');
        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();
        return $result;
    }

    public function set_data($id,$action)
    {

        $this->general->connectDbPortal();
        $return = $this->general->set($action, "tbl_kirananews", array(
            array(
                'kolom' => 'id_kirananews',
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

                $uploaddir  = realpath('./') . '/assets/file/kirananews/';
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

                        $uploaded[$input_name] = 'assets/file/kirananews/'.$upload_data['file_name'];
                    }
                }
            }

            $data_row = array_merge($data_row, $data, $uploaded);

            $this->dgeneral->update('tbl_kirananews', $data_row, $where);

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

            $uploaddir  = realpath('./') . '/assets/file/kirananews/';
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

                    $uploaded[$input_name] = 'assets/file/kirananews/'.$upload_data['file_name'];
                }
            }
        }

        $data_row = array_merge($data_row, $data,$uploaded);

        $data = $this->dgeneral->insert('tbl_kirananews', $data_row);

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

        $this->dgeneral->update('tbl_kirananews', $data_row,
            array(
                array(
                    'kolom' => 'id_kirananews',
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

            $this->db->select('tbl_kirananews.*');
            $this->db->from('tbl_kirananews');
            $this->db->where('tbl_kirananews.id_kirananews', $id);
            $query = $this->db->get();
            $result = $query->result();

            $this->general->closeDb();
        } else {
            $result = array();
        }

        return $result;
    }

}