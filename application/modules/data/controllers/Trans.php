<?php
/*
	@application  : 
	@author       : Lukman Hakim (7143)
	@date         : 12.01.2021
	@contributor  :
		  1. <insert your fullname> (<insert your nik>) <insert the date>
			 <insert what you have modified>
		  etc.
*/

Class Trans extends MX_Controller{
	function __construct(){
	    parent::__construct();
	    $this->load->model('dtransaksidata');
	}

	public function index(){
		show_404();
	}

    public function set($param = NULL)
    {
        switch ($param) {
            case 'kode_material':
                $data = $this->dtransaksidata->get_data_request('open');
                $this->set_kode_material($data);
                break;

            default:
                show_404();
                break;
        }
    }

    private function set_kode_material($data)
    {
        $datetime = date("Y-m-d H:i:s");
		
        if ($data) {
            $this->general->connectDbPortal();
            $this->dgeneral->begin_transaction();
			foreach ($data as $dt) {
				$data_row = array(
					"description_sap"    => htmlentities($dt->description_sap)
					// "description_sap"    => htmlentities(str_replace(",", ".", ltrim(rtrim($dt->description_sap))), ENT_COMPAT, "UTF-8")
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_item_request", $data_row, array(
					array(
						'kolom' => 'code',
						'value' => $dt->code
					)
				));
			}
			$data_row_log = array(
				'app'           => 'DATA SCHEDULE SET KODE MATERIAL',
				'function_name' => current_url(),
				'log_code'      => 'S',
				'log_status'    => 'Success',
				'log_desc'      => "Data Berhasil Diupdate",
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
		} else {
			$data_row_log = array(
				'app'           => 'DATA SCHEDULE SET KODE MATERIAL',
				'function_name' => current_url(),
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Data Gagal Diupdate",
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
		}
		$this->dgeneral->insert("tbl_log_schedule", $data_row_log);

        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
        }
        else {
            $this->dgeneral->commit_transaction();
        }   
        $this->general->closeDb();
        $return = array('sts' => $data_row_log['log_status'], 'msg' => $data_row_log['app']);
        echo json_encode($return);
        exit();
    }
}
