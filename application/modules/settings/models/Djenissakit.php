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


class Djenissakit extends CI_Model
{
    public $mainTable = "tbl_fbk_sakit";
    public $mainPK = "id_fbk_sakit";

    function get_all_data($id = NULL, $all = NULL)
    {
        $this->general->connectDbPortal();

        $this->db->select("$this->mainTable.*");
        $this->db->from($this->mainTable);
        if (isset($id)) {
            $this->db->where($this->mainTable.'.'.$this->mainPK, base64_decode($id));
        }
        if (!isset($all)) {
            $this->db->where($this->mainTable.'.del', 'n');
        }
        $this->db->where($this->mainTable.'.'.$this->mainPK.' <>', 999);
        $this->db->order_by($this->mainTable.'.'.$this->mainPK, 'DESC');
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

            if ($this->dgeneral->status_transaction() === FALSE ) {
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

        $dataExist = $this->GetFields($this->mainTable,$this->mainPK.'!','999','MAX(id_fbk_sakit) as aggregate');
        $nextId = ($dataExist->aggregate+1);
        if($nextId==999) $nextId += 1;
        $data_row = array(
            'id_fbk_sakit' => $nextId,
            'login_buat' => base64_decode($this->session->userdata("-id_user-")),
            'tanggal_buat' => $datetime,
            'login_edit' => base64_decode($this->session->userdata("-id_user-")),
            'tanggal_edit' => $datetime,
            'na' => 'n',
            'del' => 'n'
        );

        $data_row = array_merge($data_row, $data);

        $data = $this->dgeneral->insert($this->mainTable, $data_row);

        if ($this->dgeneral->status_transaction() === FALSE ) {
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

            $this->db->select($this->mainTable.'.*');
            $this->db->from($this->mainTable);
            $this->db->where($this->mainTable.'.'.$this->mainPK, $id);
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

        if($_limit != '')
            $result = $query->result();
        else
            $result = $query->row();

        $this->general->closeDb();

        return $result;
    }

}