<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dmrapp extends CI_Model
{
    public function get_links($id_report = null, $all = null)
    {
        $this->general->connectDbPortal();
        $this->db->from('tbl_mrapp_reports_links');
        $this->db->join('tbl_mrapp_reports rlink', 'rlink.id_report = tbl_mrapp_reports_links.id_report_link');
        $this->db->join('tbl_mrapp_reports_types rtype', 'rtype.id_report_type = rlink.id_report_type');
        if (!isset($all)) {
            $this->db->where('tbl_mrapp_reports_links.na', 'n');
            $this->db->where('tbl_mrapp_reports_links.del', 'n');
        }
        if (isset($id))
            $this->db->where('tbl_mrapp_reports_links.id_report', $id_report);

        $query = $this->db->get();

        $result = $query->result();
        $this->general->closeDb();
        return $result;
    }

    public function get_report_parameters($id_report = null, $all = null)
    {
        $this->general->connectDbPortal();
        $this->db->select(array(
            'tbl_mrapp_reports_parameters.id_report_parameter',
            'tbl_mrapp_reports_parameters.parameter_alias',
            'tbl_mrapp_reports_parameters.parameter_kolom',
            'tbl_mrapp_reports_parameters.parameter_default',
        ));
        if (isset($id_report))
            $this->db->where('id_report', $id_report);
        if (!isset($all)) {
            $this->db->where('tbl_mrapp_reports_parameters.na', 'n');
            $this->db->where('tbl_mrapp_reports_parameters.del', 'n');
        }
        $this->db->from('tbl_mrapp_reports_parameters');
        $this->db->order_by('urutan');
        $query = $this->db->get();

        $result = $query->result();
        $this->general->closeDb();
        return $result;
    }
}