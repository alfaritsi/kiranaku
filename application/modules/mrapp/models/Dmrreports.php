<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Management Reporting App Settings (Models)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */
class Dmrreports extends CI_Model
{
    public $mainTable = "tbl_mrapp_reports";
    public $mainPK = "id_report";
    private $portal_db = DB_PORTAL;
    private $dashboard_db = DB_DEFAULT;

    public function __construct()
    {
        parent::__construct();

    }

    public function get_reports($id = null, $all = null, $exclude = false)
    {
        $this->general->connectDbPortal();

        $filter = "AND tr.del = 'n' AND tr.na = 'n'";
        if ($all == "ALL")
            $filter = "";

        if (isset($id)) {
            if ($exclude)
                $filter .= " AND tr.id_report<>'$id'";
            else
                $filter .= " AND tr.id_report='$id'";
        }

        $string = "
                SELECT 
                *
                FROM 
                tbl_mrapp_reports tr                
			 	WHERE 1=1
			 	$filter";

        try {
            $query = $this->db->query($string);
            $result = $query->result();
        } catch (Exception $error) {
            $result = null;
        }
        $this->general->closeDb();

        return $result;
    }

    public function delete_report($id)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $this->dgeneral->basic_column('delete');

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
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    public function get_type($id = null, $all = null)
    {
        $this->general->connectDbPortal();

        $filter = "AND tt.del = 'n'";
        if ($all == "ALL")
            $filter = "";

        if (isset($id))
            $filter .= "AND tt.id_report_type=$id";

        $string = "
                SELECT 
                *
                FROM 
                tbl_mrapp_reports_types tt                
			 	WHERE 1=1
			 	$filter";
        $query = $this->db->query($string);
        $result = $query->result();
        $this->general->closeDb();

        return $result;
    }

    public function delete_type($id)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $data_row = $this->dgeneral->basic_column('delete');

        $this->dgeneral->update("tbl_mrapp_reports_types", $data_row,
            array(
                array(
                    'kolom' => "id_report_type",
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
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    public function get_parameter($id_report = null, $id = null, $all = null)
    {
        $this->general->connectDbPortal();

        $filter = "AND rp.id_report = '$id_report' AND rp.del = 'n'";
        if ($all == "ALL")
            $filter = "AND rp.id_report = '$id_report' ";

        if (isset($id))
            $filter .= "AND rp.id_report_parameter=$id";

        $string = "
                SELECT 
                *
                FROM 
                tbl_mrapp_reports_parameters rp                
			 	WHERE 1=1
			 	$filter";
        $query = $this->db->query($string);
        $result = $query->result();
        $this->general->closeDb();

        return $result;
    }

    public function delete_parameter($id)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $this->dgeneral->basic_column('delete');

        $this->dgeneral->update("tbl_mrapp_reports_parameters", $data_row,
            array(
                array(
                    'kolom' => "id_report_parameter",
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
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    public function get_link($id_report = null, $id = null, $all = null)
    {
        $this->general->connectDbPortal();

        $filter = "AND rp.id_report = '$id_report' AND rp.del = 'n'";
        if ($all == "ALL")
            $filter = "AND rp.id_report = '$id_report' ";

        if (isset($id))
            $filter .= "AND rp.id_report_link=$id";

        $string = "
                SELECT 
                rp.*,
                tmr.nama_report
                FROM 
                tbl_mrapp_reports_links rp    
                INNER JOIN tbl_mrapp_reports tmr 
                ON rp.id_report_link = tmr.id_report           
			 	WHERE 1=1
			 	$filter";
        $query = $this->db->query($string);
        $result = $query->result();
        $this->general->closeDb();

        return $result;
    }

    public function get_link_report_options($id_report = null)
    {
        $this->general->connectDbPortal();
        $id_report = $this->generate->kirana_decrypt($id_report);

        $string = "
                SELECT 
                rp.id_report,
                rp.nama_report
                FROM 
                tbl_mrapp_reports rp
                LEFT JOIN tbl_mrapp_reports_links rl
                ON rl.id_report_link = rp.id_report AND rl.id_report = ?        
			 	WHERE rp.id_report <> ? AND rl.id_report is null
			 	";
        $query = $this->db->query($string, array($id_report, $id_report));
        $result = $query->result();
        $this->general->closeDb();

        return $result;

    }


    public function get_link_count($id_report = null, $id = null, $all = null)
    {
        $this->general->connectDbPortal();

        $filter = "AND rp.id_report = '$id_report' AND rp.del = 'n' ";
        if ($all == "ALL")
            $filter = "AND rp.id_report = '$id_report' ";

        if (isset($id))
            $filter .= "AND rp.id_report_link='$id' ";

        $string = "
                SELECT 
                *
                FROM 
                tbl_mrapp_reports_links rp                
			 	WHERE 1=1
			 	$filter";
        $query = $this->db->query($string);
        $result = $query->num_rows();
        $this->general->closeDb();

        return $result;
    }

    public function delete_link($id_report,$id)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $data_row = $this->dgeneral->basic_column('delete');

        $this->dgeneral->delete("tbl_mrapp_reports_links",
            array(
                array(
                    'kolom' => "id_report",
                    'value' => $id_report
                ),
                array(
                    'kolom' => "id_report_link",
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
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }


    public function get_subscriber($id_report = null, $id = null, $all = null)
    {
        $this->general->connectDbPortal();

        $filter = "AND rp.id_report = '$id_report' AND rp.del = 'n'";
        if ($all == "ALL")
            $filter = "AND rp.id_report = '$id_report' ";

        if (isset($id))
            $filter .= "AND rp.id_role=$id";

        $string = "
                SELECT 
                rp.*,
                aroles.nama_role
                FROM 
                tbl_mrapp_reports_subscribers rp  
                INNER JOIN tbl_ac_roles aroles
                ON rp.id_role = aroles.id_role     
			 	WHERE 1=1
			 	$filter";
        $query = $this->db->query($string);
        $result = $query->result();
        $this->general->closeDb();

        return $result;
    }

    public function get_subscriber_role_options($id_report=null)
    {
        $this->general->connectDbPortal();
        $id_report = $this->generate->kirana_decrypt($id_report);

        $string = "
                SELECT 
                arole.id_role,
                arole.nama_role
                FROM 
                tbl_ac_roles arole
                LEFT JOIN tbl_mrapp_reports_subscribers rs
                ON arole.id_role = rs.id_role AND rs.id_report = ?
                WHERE rs.id_report is null AND arole.del <> 'y' AND arole.na <> 'y'
			 	";
        $query = $this->db->query($string, array($id_report));
        $result = $query->result();
        $this->general->closeDb();

        return $result;
    }

    public function get_subscriber_count($id_report = null, $id = null, $all = null)
    {
        $this->general->connectDbPortal();

        $filter = "AND rp.id_report = '$id_report' AND rp.del = 'n' ";
        if ($all == "ALL")
            $filter = "AND rp.id_report = '$id_report' ";

        if (isset($id))
            $filter .= "AND rp.id_role='$id' ";

        $string = "
                SELECT 
                *
                FROM 
                tbl_mrapp_reports_subscribers rp                
			 	WHERE 1=1
			 	$filter";
        $query = $this->db->query($string);
        $result = $query->num_rows();
        $this->general->closeDb();

        return $result;
    }

    public function delete_subscriber($id_report,$id)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        $data_row = $this->dgeneral->basic_column('delete');

        $this->dgeneral->delete("tbl_mrapp_reports_subscribers",
            array(
                array(
                    'kolom' => "id_report",
                    'value' => $id_report
                ),
                array(
                    'kolom' => "id_role",
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
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
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

    public function delete_threshold($id)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $this->dgeneral->basic_column('delete');

        $this->dgeneral->update("tbl_mrapp_reports_thresholds", $data_row,
            array(
                array(
                    'kolom' => "id_report_threshold",
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
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

}