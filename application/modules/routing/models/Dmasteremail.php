<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : Email Routing
@author 		: Matthew Jodi
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dmasteremail extends CI_Model
{
    public $mainTable = "tb_email_report";
    public $mainPK = "id_report";
    private $portal_db = "";
    private $dashboard_db = "";


    public function __construct()
    {
        parent::__construct();
        $this->portal_db = DB_PORTAL;
        $this->dashboard_db = DB_DEFAULT;

    }

    function get_data_report(
        $id_report = NULL, $report_name = NULL, $all = NULL, $period = NULL
    )
    {
        $this->general->connectDbDefault();
        $string = "SELECT 
						tbl_karyawan.nik as id,
						tb_email_report.*,
					  	  tbl_karyawan.nik,
					 	  tbl_karyawan.nama,
					   	  CASE
					   		WHEN tb_email_report.is_active = 1 THEN '<span class=\"label label-success\">ACTIVE</span>'
					   		ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					      END as label_active,
						(SELECT CAST((
								SELECT CONVERT(VARCHAR, [$this->portal_db].dbo.tbl_karyawan.nik) + RTRIM(';')
									FROM tb_email_exclude
									inner join [$this->portal_db].dbo.tbl_karyawan on [$this->portal_db].dbo.tbl_karyawan.nik=tb_email_exclude.nik 
									WHERE tb_email_exclude.id_report = tb_email_report.id_report
									ORDER BY [$this->portal_db].dbo.tbl_karyawan.nama ASC
									FOR XML PATH ('')) as VARCHAR(MAX))) as exclude_nik_list,							  
						(SELECT CAST((
								SELECT CONVERT(VARCHAR, [$this->portal_db].dbo.tbl_karyawan.nama) + RTRIM(';')
									FROM tb_email_exclude
									inner join [$this->portal_db].dbo.tbl_karyawan on [$this->portal_db].dbo.tbl_karyawan.nik=tb_email_exclude.nik 
									WHERE tb_email_exclude.id_report = tb_email_report.id_report
									ORDER BY [$this->portal_db].dbo.tbl_karyawan.nama ASC
									FOR XML PATH ('')) as VARCHAR(MAX))) as exclude_nama_list
						  
                FROM [$this->dashboard_db].dbo.tb_email_report as tb_email_report
                LEFT JOIN [$this->portal_db].dbo.tbl_karyawan as tbl_karyawan 
                  ON CONVERT(VARCHAR(10), tbl_karyawan.nik) =  tb_email_report.requestor 
                  AND tbl_karyawan.na = 'n'
			 	WHERE 1=1";
        if ($id_report != NULL) {
            $string .= " AND tb_email_report.id_report = " . $id_report;
        }

        if ($report_name != NULL && trim($report_name) !== "") {
            $string .= " AND tb_email_report.report_name = " . $report_name;
        }
        if ($all == NULL) {
            $string .= " AND tb_email_report.is_active = 1";
        }
        if ($period != NULL) {
            $string .= " AND tb_email_report.report_type = '" . $period . "'";
        }
        $string .= " ORDER BY tb_email_report.id_report ASC";

        $query = $this->db->query($string);
        $result = $query->result();
        $this->general->closeDb();

        return $result;
    }

    function get_data_topic($id_topic = NULL, $all = null)
    {
        $this->general->connectDbDefault();
        $string = "SELECT tb_email_topic.*,
					   	  CASE
					   		WHEN tb_email_topic.is_active = 1 THEN '<span class=\"label label-success\">ACTIVE</span>'
					   		ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					      END as label_active,
					      CAST(
		 			           (SELECT DISTINCT CONVERT(VARCHAR, tb_email_auth.id_report) + RTRIM(',')
		 			              FROM [dashboarddev].dbo.tb_email_topic as tb2
		 			             INNER JOIN [dashboarddev].dbo.tb_email_auth as tb_email_auth ON tb_email_auth.id_topic = tb2.id_topic
		 			             WHERE tb2.id_topic = tb_email_topic.id_topic                                     
		 			            FOR XML PATH ('')) as VARCHAR(MAX)
		 			          )  AS report_kode_list
			 	 FROM [dashboarddev].dbo.tb_email_topic as tb_email_topic
			 	WHERE 1=1";
        if ($id_topic != NULL) {
            $string .= " AND tb_email_topic.id_topic = " . $id_topic;
        }
        if ($all == NULL) {
            $string .= " AND tb_email_topic.is_active = 1";
        }
        $string .= " ORDER BY tb_email_topic.id_topic ASC";

        $query = $this->db->query($string);
        $result = $query->result();
        $this->general->closeDb();

        return $result;
    }

    function set_report($data)
    {
        $datetime = date("Y-m-d H:i:s");

        $this->general->connectDbDefault();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_report'];

        if (!empty($id)) {

            unset($data_row['id_report']);

            $data = $this->dgeneral->update($this->mainTable, $data_row,array(
                array(
                    "kolom"=>"id_report",
                    "value"=>$id
                )
            ));

        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil dirubah";
            $sts = "OK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    function save_report($data)
    {
        $datetime = date("Y-m-d H:i:s");

        $this->general->connectDbDefault();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_report'];
		$exclude_niks = $data_row['exclude_nik'];        

        unset($data_row['id_report']);
		unset($data_row['exclude_nik']);
		// LHA exclude NIK 
        // if (!empty($id)) {

            // $data = $this->dgeneral->update($this->mainTable, $data_row,array(
                // array(
                    // "kolom"=>"id_report",
                    // "value"=>$id
                // )
            // ));

        // } else {
            // $basic_data = array(
                // 'is_active' => 1
            // );

            // $data_row = array_merge($data_row,$basic_data);

            // $data = $this->dgeneral->insert($this->mainTable, $data_row);
            // $id = $this->db->insert_id();
        // }
		//LHA
        $deleteWhere = array(
            array(
                "kolom" => "id_report",
                "value" => $id
            )
        );

        $deleteOldData = $this->dgeneral->delete("tb_email_exclude",$deleteWhere);

        if($deleteOldData)
        {
            foreach ($exclude_niks as $exclude_nik)
            {
                $data = $this->dgeneral->insert("tb_email_exclude",array(
                    "id_report" => $id,
                    "nik" => $exclude_nik
                ));
            }
        }

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

    function set_topic($data)
    {
        $datetime = date("Y-m-d H:i:s");

        $this->general->connectDbDefault();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_topic'];

        if (!empty($id)) {

            unset($data_row['id_topic']);

            $data = $this->dgeneral->update("tb_email_topic", $data_row,array(
                array(
                    "kolom"=>"id_topic",
                    "value"=>$id
                )
            ));

        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil dirubah";
            $sts = "OK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        $this->general->closeDb();
        return $return;
    }

    function save_topic($data)
    {
        $datetime = date("Y-m-d H:i:s");

        $this->general->connectDbDefault();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_topic'];
        $reports = $data_row['select_report'];

        unset($data_row['id_topic']);
        unset($data_row['select_report']);

        if (!empty($id)) {

            $data = $this->dgeneral->update("tb_email_topic", $data_row,array(
                array(
                    "kolom"=>"id_topic",
                    "value"=>$id
                )
            ));

        } else {
            $basic_data = array(
                'is_active' => 1
            );

            $data_row = array_merge($data_row,$basic_data);

            $data = $this->dgeneral->insert("tb_email_topic", $data_row);
            $id = $this->db->insert_id();
        }

        $deleteWhere = array(
            array(
                "kolom" => "id_topic",
                "value" => $id
            )
        );

        $deleteOldData = $this->dgeneral->delete("tb_email_auth",$deleteWhere);

        if($deleteOldData)
        {
            foreach ($reports as $report)
            {
                $data = $this->dgeneral->insert("tb_email_auth",array(
                    "id_topic" => $id,
                    "id_report" => $report
                ));
            }
        }

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
        $this->general->closeDb();
        return $return;

    }
}

?>