<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dapi extends CI_Model
{

    public function count_report($id = null)
    {
        $this->general->connectDbPortal();
        if (isset($id))
            $this->db->where('id_report', $id);
        $query = $this->db->get('tbl_mrapp_reports');
        $result = $query->num_rows();
        $this->general->closeDb();
        return $result;
    }

    public function get_categories($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;

        $this->general->connectDbPortal();
        $this->db->select('*');
        $this->db->from('tbl_mrapp_categories');

        if (!$all) {
            $this->db->where('na', 'n');
            $this->db->where('del', 'n');
        }

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();
        $this->general->closeDb();
        return $result;
    }

    public function get_category_reports($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $id_category = isset($params['id_category']) ? $params['id_category'] : null;

        $this->general->connectDbPortal();
        $this->db->select('id_report,id_report_type,kode_report,nama_report,deskripsi,report_function');
        $this->db->from('tbl_mrapp_reports');

        if (!$all) {
            $this->db->where('na', 'n');
            $this->db->where('del', 'n');
        }

        if (isset($id_category))
            $this->db->where('id_category', $id_category);

        $query = $this->db->get();
        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();
        $this->general->closeDb();
        return $result;
    }

    public function get_reports($param = array())
    {
        $this->general->connectDbPortal();

        $id = isset($param['id_report']) && !empty($param['id_report']) ? $param['id_report'] : null;
        $all = isset($param['all']) ? $param['all'] : false;
        $kode_report = isset($param['kode_report']) && !empty($param['kode_report']) ? $param['kode_report'] : null;

        $this->db->select('tbl_mrapp_reports.*,tbl_mrapp_categories.module');
        $this->db->from('tbl_mrapp_reports');
        $this->db->join('tbl_mrapp_categories', 'tbl_mrapp_reports.id_category=tbl_mrapp_categories.id_category');
        $this->db->join('tbl_mrapp_reports_types', 'tbl_mrapp_reports.id_report_type=tbl_mrapp_reports_types.id_report_type');

        if (isset($id))
            $this->db->where('tbl_mrapp_reports.id_report', $id);

        if (isset($kode_report))
            $this->db->where('tbl_mrapp_reports.kode_report', $kode_report);

        if (!$all) {
            $this->db->where('tbl_mrapp_reports.na', 'n');
            $this->db->where('tbl_mrapp_reports.del', 'n');
        }

        $query = $this->db->get();

        if (isset($param['single_row']) && $param['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_alerts($params = array())
    {
        $id_report = isset($params['id_report']) ? $params['id_report'] : null;
        $id_user = isset($params['id_user']) ? $params['id_user'] : null;

        $all = isset($params['all']) ? $params['all'] : false;
        $sent = isset($params['sent']) ? $params['sent'] : false;

        if (isset($id_user)) {
            $subquery = "select a.id_report, b.id_role, d.id_user
                from tbl_mrapp_reports a
                inner join tbl_mrapp_reports_subscribers b
                  on a.id_report = b.id_report 
                inner join tbl_ac_roles_departemens c
                  on b.id_role = c.id_role
                inner join tbl_user d
                    ON c.id_departemen = d.id_departemen
                    AND d.na = 'n' AND d.del = 'n'
                UNION
                select a.id_report, b.id_role, d.id_user
                from tbl_mrapp_reports a
                inner join tbl_mrapp_reports_subscribers b
                  on a.id_report = b.id_report 
                inner join tbl_ac_roles_divisis c
                  on b.id_role = c.id_role
                inner join tbl_user d
                    ON c.id_divisi = d.id_divisi
                    AND d.na = 'n' AND d.del = 'n'
                UNION
                select a.id_report, b.id_role, d.id_user
                from tbl_mrapp_reports a
                inner join tbl_mrapp_reports_subscribers b
                  on a.id_report = b.id_report 
                inner join tbl_ac_roles_jabatans c
                  on b.id_role = c.id_role
                inner join tbl_user d
                    ON c.id_jabatan = d.id_jabatan
                    AND d.na = 'n' AND d.del = 'n'";
        }

        $this->db->select('tbl_mrapp_alerts.id_alert');
        $this->db->select('tbl_mrapp_alerts.id_report');
        $this->db->select('tbl_mrapp_alerts.id_report_threshold');
        $this->db->select('tbl_mrapp_alerts.title');
        $this->db->select('tbl_mrapp_alerts.data');
        $this->db->select('tbl_mrapp_alerts.alert_sent_at');
        $this->db->select('tbl_mrapp_alerts.fcm_message_id');
        $this->db->select('tbl_mrapp_categories.nama_category');
        $this->db->select('tbl_mrapp_reports.nama_report');
        if (isset($id_user)) {
            $this->db->select('auth.id_user,auth.id_role');
        }
        $this->db->from('tbl_mrapp_alerts');
        $this->db->join('tbl_mrapp_reports','tbl_mrapp_reports.id_report=tbl_mrapp_alerts.id_report');
        $this->db->join('tbl_mrapp_categories','tbl_mrapp_categories.id_category = tbl_mrapp_reports.id_category');

        if (isset($id_user)) {

            $this->db->join(
                "($subquery) auth",
                "auth.id_report = tbl_mrapp_alerts.id_report and auth.id_user = $id_user"
            );
        }

        if (isset($id_report))
            $this->db->where('tbl_mrapp_alerts.id_report', $id_report);

        if (!$all) {
            $this->db->where('tbl_mrapp_alerts.na', 'n');
            $this->db->where('tbl_mrapp_alerts.del', 'n');
        }
        if ($sent)
            $this->db->where('tbl_mrapp_alerts.alert_sent_at is not null', null, false);
        else
            $this->db->where('tbl_mrapp_alerts.alert_sent_at is null', null, false);

//        $this->db->group_by('tbl_mrapp_alerts.id_report_threshold');
        $this->db->order_by('tbl_mrapp_alerts.tanggal_buat','desc');

        $query = $this->db->get();

        if (isset($parasm['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_threshold($id_report = null, $id = null, $all = null)
    {
        $this->general->connectDbPortal();

        $filter = "AND rp.id_report = '$id_report' AND rp.del = 'n'";
        if ($all == "ALL")
            $filter = "AND rp.id_report = '$id_report' ";

        if (isset($id))
            $filter .= "AND rp.id_report_threshold=$id";

        $string = "
                SELECT 
                *
                FROM 
                tbl_mrapp_reports_thresholds rp                
			 	WHERE 1=1
			 	$filter";
        $query = $this->db->query($string);
        $result = $query->result();
        $this->general->closeDb();

        return $result;
    }

    public function get_report_parameters($id_report = null, $all = null)
    {
        $this->general->connectDbPortal();

        if (isset($id_report))
            $this->db->where('id_report', $id_report);
        if (!isset($all)) {
            $this->db->where('na', 'n');
            $this->db->where('del', 'n');
        }
        $this->db->from('tbl_mrapp_reports_parameters');
        $this->db->order_by('urutan');
        $query = $this->db->get();

        $result = $query->result();
        $this->general->closeDb();
        return $result;
    }

    public function get_fcm($params = array())
    {
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $token = isset($params['token']) ? $params['token'] : null;

        $this->db->select('*');
        $this->db->from('tbl_mrapp_fcm');

        if (isset($nik))
            $this->db->where('nik', $nik);
        if (isset($token))
            $this->db->where('token', $token);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
}