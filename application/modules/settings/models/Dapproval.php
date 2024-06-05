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


class Dapproval extends CI_Model
{
    public $mainTable = "tbl_atasan_master";
    public $mainPK = "id_atasan_master";

    function get_all_data($id = NULL, $all = NULL)
    {
        $this->general->connectDbPortal();

        $this->db->select("$this->mainTable.*");
		$this->db->select("
						   CASE
						   		WHEN tbl_atasan_master.login_edit is null 
								THEN (select tbl_user.id_karyawan from tbl_user where tbl_user.id_user=tbl_atasan_master.login_buat)
						   		ELSE (select tbl_user.id_karyawan from tbl_user where tbl_user.id_user=tbl_atasan_master.login_edit)
						   END as nama");
		$this->db->select("
						   CASE
						   		WHEN tbl_atasan_master.tanggal_edit is null 
								THEN CONVERT(varchar(10),tbl_atasan_master.tanggal_buat,104)+' '+CONVERT(VARCHAR(10), tanggal_buat, 108) 
						   		ELSE CONVERT(varchar(10),tbl_atasan_master.tanggal_edit,104)+' '+CONVERT(VARCHAR(10), tanggal_edit, 108)  
						   END as tanggal");
        $this->db->from($this->mainTable);
        if (isset($id)) {
            $this->db->where($this->mainTable . '.' . $this->mainPK, base64_decode($id));
        }
        if (!isset($all)) {
            $this->db->where($this->mainTable . '.na', 'n');
        }
        $this->db->where($this->mainTable . '.' . $this->mainPK . ' <>', 999);
        $this->db->order_by($this->mainTable . '.' . $this->mainPK, 'DESC');

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();
        return $result;
    }

    function get_data_detail($id = NULL)
    {
        if (isset($id)) {
            $this->general->connectDbPortal();

            $this->db->select('tbl_karyawan.*,tbl_departemen.nama as nama_departemen');
            $this->db->from('tbl_karyawan');
            $this->db->join('tbl_user','tbl_karyawan.id_karyawan = tbl_user.id_karyawan','left outer');
            $this->db->join('tbl_level','tbl_user.id_level= tbl_level.id_level','left outer');
            $this->db->join('tbl_departemen','tbl_departemen.id_departemen= tbl_user.id_departemen','left outer');
            $this->db->where('tbl_karyawan.id_karyawan', $id);
            $query = $this->db->get();
            $result = $query->result();

            $this->general->closeDb();
        } else {
            $result = array();
        }

        foreach ($result as $user)
        {
            if($user->gender == "l"){
                $image 	= base_url()."assets/apps/img/avatar5.png";
            }else{
                $image 	= base_url()."assets/apps/img/avatar2.png";
            }


            if($user->gambar){
                $data_image	= "http://kiranaku.kiranamegatara.com/home/".strtolower($user->gambar);
                $headers    = get_headers($data_image);
                if($headers[0] == "HTTP/1.1 200 OK"){
                    $image 	= $data_image;
                }else{
                    $links 		= explode("/", $user->gambar);
                    $data_image	= "http://kiranaku.kiranamegatara.com/home/".$links[0]."/".$links[1]."/".strtoupper($links[2]);
                    $headers    = get_headers($data_image);
                    if($headers[0] == "HTTP/1.1 200 OK"){
                        $image 	= $data_image;
                    }
                }
            }

            $user->user_image = $image;
        }

        return $result;
    }

    function get_all_data_detail($id = NULL, $pabrik = NULL, $ho = true, $all = NULL)
    {
        $this->general->connectDbPortal();

        $filterPabrik = !empty($pabrik)?'\''.join('.',$pabrik).'\'':'\'KMTR\'';

//        $filterHo = ($ho)?',1':',0';
//
//        if(empty($filterPabrik))
//            $filterHo = '\'NULL\'';

        $q = $this->db->query('EXEC dbo.SP_Kiranaku_Approval '.$filterPabrik);

        $result = $q->result();

        return $result;

//        $this->db->select("p.nama as nama_pabrik, k.*,d.nama as nama_departemen,dv.nama as nama_divisi,
//			s.nama as nama_seksi, sd.nama as nama_sub_divisi");
//        $this->db->from("tbl_karyawan k");
//        $this->db->join("tbl_user u", "u.id_karyawan=k.id_karyawan", "left outer");
//        $this->db->join("tbl_departemen d", "d.id_departemen=u.id_departemen", "left outer");
//        $this->db->join("tbl_divisi dv", "dv.id_divisi=u.id_divisi", "left outer");
//        $this->db->join("tbl_sub_divisi sd", "sd.id_sub_divisi=u.id_sub_divisi", "left outer");
//        $this->db->join("tbl_inv_pabrik p", "p.kode=k.gsber", "left outer");
//        $this->db->join("tbl_seksi s", "s.id_seksi=u.id_seksi", "left outer");
//        if (isset($id)) {
//            $this->db->where('k.id_karyawan', base64_decode($id));
//        }
//        if (!isset($all)) {
//            $this->db->where('k.del', 'n');
//        }
//        $this->db->like('k.email', 'kiranamegatara');
//        if (isset($pabrik) && count($pabrik)>0) {
//            $this->db->where_in('k.gsber',$pabrik);
//        }
//        $this->db->where('k.ho', 'y');
//        $this->db->where('k.na', 'n');
//
//        $this->db->where_not_in('k.id_karyawan', array('73560001', '73560002', '73560003', '10000001'));
//        $this->db->where_not_in('u.id_karyawan', array('6724', '6725'));
//        $this->db->order_by('k.id_karyawan', 'DESC');
//        $query = $this->db->get();
//        $result = $query->result();
//
//        $this->general->closeDb();
//        return $result;
    }

    public function get_list_pabrik()
    {
        $this->general->connectDbPortal();

        $this->db->select("tbl_inv_pabrik.nama, tbl_inv_pabrik.kode");
        $this->db->from("tbl_inv_pabrik");
        if (isset($id)) {
            $this->db->where('tbl_inv_pabrik.id_pabrik', base64_decode($id));
        }
        if (!isset($all)) {
            $this->db->where('tbl_inv_pabrik.na', 'n');
            $this->db->where('tbl_inv_pabrik.del', 'n');
        }
        $this->db->order_by('tbl_inv_pabrik.id_pabrik', 'DESC');

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
            'na' => 'n'
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
            'na' => 'y'
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

    public function GetFields($_tbl, $_key, $_value, $_results, $_group = '', $_order = '', $_onerow = false, $_limit = '')
    {
        $this->general->connectDbPortal();

        $this->db->select("$_results");
        if (is_array($_tbl)) {
            foreach ($_tbl as $i => $table) {
                if ($i == 0) {
                    $this->db->from("$table");
                } else {
                    if (is_array($table)) {
                        $this->db->join($table[0], $table[1], @$table[2]);
                    } else
                        $this->db->join($table);
                }
            }
        } else {
            $this->db->from("$_tbl");
        }
        $this->db->where("$_key='$_value'");
        if (isset($_group))
            $this->db->group_by("$_group");
        if (isset($_order))
            $this->db->order_by("$_order");
        if (isset($_limit))
            $this->db->limit("$_limit");

        $query = $this->db->get();

        if (!$_onerow)
            $result = $query->result();
        else {
            $result = $query->row();
        }


        $this->general->closeDb();

        return $result;
    }

    function nik_departemen($id_departemen)
    {
        $this->general->connectDbPortal();
        $this->db->select('nik');
        $this->db->from('tbl_atasan');

        $this->db->where('id_departemen', $id_departemen);

        $query = $this->db->get();

        $results = $query->result();

        $data = array();

        foreach ($results as $result) {
            $data[] = $result->nik;
        }

        $this->general->closeDb();

        return join(".", $data);

//        $s = "select * from tbl_atasan where id_departemen='$id_departemen'";
//        $q = _query($s);
//        $data = "";
//        while($d = _fetch_array($q)){
//            $data .= $d['nik'].'.';
//        }
//        return $data;
    }

    function nik_divisi($id_divisi)
    {
        $this->general->connectDbPortal();
        $this->db->select('*');
        $this->db->from('tbl_atasan');

        $this->db->where('id_departemen', $id_divisi);

        $query = $this->db->get();

        $results = $query->result();

        $data = array();

        foreach ($results as $result) {
            $data[] = $result->nik;
        }
        $this->general->closeDb();

        return join(".", $data);

//        $s = "select * from tbl_atasan where id_departemen='$id_divisi'";
//        $q = _query($s);
//        $data = "";
//        while($d = _fetch_array($q)){
//            $data .= $d['nik'].'.';
//        }
//        return $data;
    }

    function nik_direktorat($direktorat)
    {
        $this->general->connectDbPortal();
        $this->db->select('*');
        $this->db->from('tbl_atasan');

        $this->db->where('id_departemen', $direktorat);

        $query = $this->db->get();

        $results = $query->result();

        $data = array();

        foreach ($results as $result) {
            $data[] = $result->nik;
        }
        $this->general->closeDb();

        return join(".", $data);
//        $s = "select * from tbl_atasan where id_departemen='$direktorat'";
//        $q = _query($s);
//        $data = "";
//        while($d = _fetch_array($q)){
//            $data .= $d['nik'].'.';
//        }
//        return $data;
    }

    function nik_ceo($id_ceo)
    {
        $this->general->connectDbPortal();
        $this->db->select('*');
        $this->db->from('tbl_atasan');

        $this->db->where('id_departemen', $id_ceo);

        $query = $this->db->get();

        $results = $query->result();

        $data = array();

        foreach ($results as $result) {
            $data[] = $result->nik;
        }
        $this->general->closeDb();

        return join(".", $data);
//        $s = "select * from tbl_atasan where id_departemen='$id_ceo'";
//        $q = _query($s);
//        $data = "";
//        while($d = _fetch_array($q)){
//            $data .= $d['nik'].'.';
//        }
//        return $data;
    }


    function atasan($id)
    {
        $d = $this->GetFields(
            array(
                "tbl_user u",
                array(
                    "tbl_level l",
                    "l.id_level=u.id_level",
                    "left outer"
                ),
                array(
                    "tbl_karyawan k",
                    "k.id_karyawan=u.id_karyawan",
                    "left outer"
                )
            ),
            "u.na='n' and k.id_karyawan",
            $id,
            'u.id_user,
            u.id_level,
            u.id_karyawan,
            u.id_direktorat,
            u.id_ceo,
            u.id_divisi,
            u.id_departemen,
            u.id_golongan,
            u.cr_level_id,
            u.wf_level_id,
            u.gem_level_id,
            l.nama as nama_level,
            k.nik,
            k.nama as nama_karyawan,
            k.gambar,
            k.gender,
            k.ho,
            k.id_gedung,
            k.status,
            k.persa,
            k.gsber',
            '',
            '',
            true
        );

        //buat atasan
        $nik_atasan = "";
        $nik_atasan_email = "";

        $nik_departemen = $this->nik_departemen($d->id_departemen);
        $nik_divisi = $this->nik_divisi($d->id_divisi);
        $nik_direktorat = $this->nik_direktorat($d->id_direktorat);
        $nik_ceo = $this->nik_ceo($d->id_ceo);

        if ($d->id_level == 9100) {        //ka dir
            $nik_atasan = $nik_ceo;
            $nik_atasan_email = $nik_ceo;
        } elseif ($d->id_level == 9101) {    //ka div
            if ($d->id_direktorat == 0) {
                $nik_atasan = $nik_ceo;
                $nik_atasan_email = $nik_ceo;
            } else {
                $nik_atasan = $nik_direktorat . '' . $nik_ceo;
                $nik_atasan_email = $nik_direktorat;
            }
        } elseif ($d->id_level == 9102) {    //ka dept
            if ($d->id_divisi == 0) {
                $nik_atasan = $nik_direktorat . '' . $nik_ceo;
                $nik_atasan_email = $nik_direktorat;
            } elseif ($d->id_direktorat == 0) {
                $nik_atasan = $nik_divisi . '' . $nik_ceo;
                $nik_atasan_email = $nik_divisi;
            } else {
                $nik_atasan = $nik_divisi . '' . $nik_direktorat;
                $nik_atasan_email = $nik_divisi;
            }
        } elseif ($d->id_level == 9103) {    //staff
            if ($d->id_departemen == 0) {
                $nik_atasan = $nik_divisi . '' . $nik_direktorat;
                $nik_atasan_email = $nik_divisi;
            } elseif ($d->id_divisi == 0) {
                $nik_atasan = $nik_direktorat . '' . $nik_ceo;
                $nik_atasan_email = $nik_direktorat;
            } else {
                $ck = $this->GetFields(
                    'tbl_atasan',
                    'id_departemen',
                    $d->id_departemen,
                    'id_atasan', '', '',
                    true);
                $nik_atasan = $nik_departemen . '' . $nik_divisi;
                $nik_atasan_email = (empty($ck)) ? $nik_divisi : $nik_departemen;
            }
        }

        //pengalihan approval CEO ke Kadep HR Ops
        $nik_hr_division = $this->GetFields(
            'tbl_user',
            "id_divisi='766' and id_level",
            9101,
            'id_karyawan',
            '',
            '', true
        );
        //get nik untuk kadiv hr operation
        $nik_hr_operation = $this->GetFields(
            'tbl_user',
            "id_departemen='797' and id_level",
            9102,
            'id_karyawan',
            '',
            '',
            true
        );    //get nik untuk kadep hr operation
        if (isset($nik_hr_operation))
            $nik_atasan = str_replace(5530, $nik_hr_operation->id_karyawan, $nik_atasan);    //jika CEO(Pak martinus) dialihkan ke HR Operation
        $nik_atasan = str_replace('6724.', '', $nik_atasan);    //jika CEO(Pak Toddy) dihilangkan
        $nik_atasan = str_replace('6725.', '', $nik_atasan);    //jika CEO(Pak Toddy) dihilangkan
        if (isset($nik_hr_operation)) {
            $nik_atasan_email = str_replace(5530, $nik_hr_operation->id_karyawan, $nik_atasan_email);    //jika CEO(Pak martinus) dialihkan ke HR Operation
            $nik_atasan_email = str_replace('6724.', $nik_hr_operation->id_karyawan, $nik_atasan_email);    //jika CEO(Pak Toddy) dihilangkan
            $nik_atasan_email = str_replace('6725.', $nik_hr_operation->id_karyawan, $nik_atasan_email);    //jika CEO(Pak Toddy) dihilangkan
        }
        $nik_atasan_email = str_replace('5572.', '', $nik_atasan_email);    //jika bu jenny send email dihilangkan

        //sampe sini
        //pengecualian untuk nik 7041(pak bani) approval hardcode
        if (isset($nik_hr_division) && isset($nik_hr_operationn)) {
            $nik_atasan = ($d->id_karyawan == 7041) ? $nik_hr_division->id_karyawan . '.' . $nik_hr_operation->id_karyawan . '.' : $nik_atasan;
        }
        if (isset($nik_hr_division) && isset($nik_hr_operationn)) {
            $nik_atasan_email = ($d->id_karyawan == 7041) ? $nik_hr_division->id_karyawan . '.' : $nik_atasan_email;
        }

        //ambil dari tbl_atasan_master
        $atasan_master = $this->GetFields(
            'tbl_atasan_master',
            "na='n' and nik",
            $d->id_karyawan,
            '*',
            '',
            '',
            true
        );

        if (isset($atasan_master)) {
            $nik_atasan = (!empty($atasan_master->id_atasan_master)) ? $atasan_master->atasan : $nik_atasan;
            $nik_atasan_email = (!empty($atasan_master->id_atasan_master)) ? $atasan_master->atasan_email : $nik_atasan_email;
        }

        $atasan = str_replace('.' . $d->id_karyawan, '', $nik_atasan);
        $atasan = substr($atasan, 0, -1);
        $atasan = explode(".", $atasan);
        $list_atasan = "";
        if (isset($atasan))
            foreach ($atasan as $val) {
                $_atasan = $this->GetFields(
                    'tbl_karyawan',
                    'id_karyawan',
                    $val,
                    'nama',
                    '',
                    '',
                    true
                );
                if (isset($_atasan))
                    $list_atasan .= '<li>' . ucwords(strtolower(
                            $_atasan->nama
                        )) . '</li>';
            }

        $atasan_email = str_replace('.' . $d->id_karyawan, '', $nik_atasan_email);
        $atasan_email = substr($atasan_email, 0, -1);
        $atasan_email = explode(".", $atasan_email);
        $list_atasan_email = "";
        if (isset($atasan_email))
            foreach ($atasan_email as $val) {
                $_atasan_email = $this->GetFields(
                    'tbl_karyawan',
                    'id_karyawan',
                    $val,
                    'email', '', '',
                    true
                );

                if (isset($_atasan_email))
                    $list_atasan_email .= '<li>' . ucwords(strtolower($_atasan_email->email)) . '</li>';
            }


        return "<ul>" . $list_atasan . "</ul>";
    }
}