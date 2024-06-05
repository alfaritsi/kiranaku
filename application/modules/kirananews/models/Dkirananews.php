<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Kirana News - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */

class Dkirananews extends CI_Model {

    public function get_news($tgl_awal = null, $tgl_akhir = null,  $all = false)
    {
        $this->general->connectDbPortal();

        $this->db->select('*');
        $this->db->from('tbl_kirananews');
        if (isset($id))
            $this->db->where('tbl_kirananews.id_kirananews', $id);

        if(!$all)
            $this->db->where('tbl_kirananews.del', 'n');

        $this->db->where('tbl_kirananews.na', 'n');

        if(isset($tgl_awal) && isset($tgl_akhir))
        {
            $this->db->where("tbl_kirananews.tanggal between '$tgl_awal' AND '$tgl_akhir'",null,false);
        }

        $query = $this->db->order_by('tanggal','desc');
        $query = $this->db->get();

        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

}