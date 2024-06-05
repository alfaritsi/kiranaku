<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Info Kirana - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Dinfokirana extends CI_Model
{
    public function get_new($id=null)
    {
        $this->general->connectDbPortal();

        $this->db->select('tbl_news.*');
        $this->db->select('tbl_karyawan.id_karyawan');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');
        $this->db->select('(select count(*) from tbl_komentar where id_news = tbl_news.id_news) as komentar');
        $this->db->select('(select count(*) from tbl_komentar where id_news = tbl_news.id_news and na=\'n\') as komentar_publish');
        $this->db->from('tbl_news');
        $this->db->join('tbl_user','tbl_news.id_user=tbl_user.id_user','left outer');
        $this->db->join('tbl_karyawan','tbl_karyawan.id_karyawan=tbl_user.id_karyawan','left outer');

        $this->db->where('tbl_news.id_news', $id);

        $this->db->where('tbl_news.del', 'n');
        $this->db->where('tbl_news.na', 'n');

        $query = $this->db->get();

        $result = $query->row();

        $this->general->closeDb();

        return $result;
    }

    public function get_komentar($id_news = null)
    {
        $result = [];

        if(isset($id_news))
        {
            $this->general->connectDbPortal();

            $this->db->select('tbl_komentar.*');
            $this->db->select('tbl_karyawan.id_karyawan');
            $this->db->select('tbl_karyawan.nama as nama_karyawan');
            $this->db->from('tbl_komentar');
            $this->db->where('tbl_komentar.id_news',$id_news);
            $this->db->join('tbl_user','tbl_komentar.login_buat=tbl_user.id_user','left outer');
            $this->db->join('tbl_karyawan','tbl_karyawan.id_karyawan=tbl_user.id_karyawan','left outer');

            $query = $this->db->get();

            $result = $query->result();

            $this->general->closeDb();
        }

        return $result;
    }

    public function get_news($type = 'terbaru',$tgl_awal = null, $tgl_akhir = null,  $all = false)
    {
        $this->general->connectDbPortal();

        $this->db->select('tbl_news.*');
        $this->db->select('tbl_karyawan.id_karyawan');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');
        $this->db->select('(select count(*) from tbl_komentar where id_news = tbl_news.id_news) as komentar');
        $this->db->select('(select count(*) from tbl_komentar where id_news = tbl_news.id_news and na=\'n\') as komentar_publish');
        $this->db->from('tbl_news');
        $this->db->join('tbl_user','tbl_news.id_user=tbl_user.id_user','left outer');
        $this->db->join('tbl_karyawan','tbl_karyawan.id_karyawan=tbl_user.id_karyawan','left outer');
        if (isset($id))
            $this->db->where('tbl_news.id_news', $id);

        $this->db->where('tbl_news.del', 'n');
        $this->db->where('tbl_news.na', 'n');

        if(isset($tgl_awal) && isset($tgl_akhir))
        {
            $this->db->where("tbl_news.tanggal between '$tgl_awal' AND '$tgl_akhir'",null,false);
        }

        if($type=='terbaru')
        {
            $this->db->order_by('tbl_news.tanggal','desc');
        }else{
            $this->db->order_by('komentar','desc');
        }

        $query = $this->db->get();

        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }
}