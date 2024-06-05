<?php
/*
        @application  : BANK SPECIMEN
        @author       : Lukman Hakim (7143) 
        @date         : 05.07.2021
        @contributor  :
              1. Lukman Hakim (7143) 05.07.2021
                 penambahan RFC untuk kode vendor
              etc.
    */

include_once APPPATH . "modules/data/controllers/BaseControllers.php";

class Rfc extends BaseControllers
{
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function set($param = NULL)
	{
		switch ($param) {
			case 'rekening':
				/*
                     * Function Module : Z_RFC_BANKSPECIMENT
                     * Used by :
                     * 		1. Proc HO / Legal HO
                     */
				// $this->set_create_vendor();
				$this->set_rekening();
				break;
				
		}
	}

	/**********************************/
	/*			  private  			  */
	/**********************************/
	//set rekening
	private function set_rekening(){
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();


		$data_rfc     = $this->dtransaksidata->push_data_rekening();
		// echo json_encode($data_rfc);
		// exit();
		$datetime     = date("Y-m-d H:i:s");
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_BANKSPECIMENT",
				array(
					array("TABLE", "T_BANK", $data_rfc)
				)
			);
			$jumlah_data = 0;
			foreach ($result['T_BANK'] as $dt) {
				$nomor_rekening = $dt['BANKN'];
				$no_coa 		= $dt['HKONT'];
				$bukrs 			= $dt['BUKRS'];
				// $status_sap 	= ($dt['FLAG']=='X')?'y':'n';
				if($dt['FLAG']=='X'){
					$jumlah_data++;
					//update tbl_bank_data
					$string = "
						update
						tbl_bank_data
						set 
						status_sap='y'
						where 
						nomor_rekening='$nomor_rekening'
						and no_coa='$no_coa'
						and pabrik COLLATE SQL_Latin1_General_CP1_CI_AS in (select ZDMMSPLANT.WERKS from SAPSYNC.dbo.ZDMMSPLANT as ZDMMSPLANT where ZDMMSPLANT.BUKRS ='$bukrs')
					";
					$query  = $this->db->query($string);
					//update tbl_bank_data_temp
					$string = "
						update
						tbl_bank_data_temp
						set 
						status_sap='y'
						where 
						nomor_rekening='$nomor_rekening'
						and no_coa='$no_coa'
						and pabrik COLLATE SQL_Latin1_General_CP1_CI_AS in (select ZDMMSPLANT.WERKS from SAPSYNC.dbo.ZDMMSPLANT as ZDMMSPLANT where ZDMMSPLANT.BUKRS ='$bukrs')
					";
					$query  = $this->db->query($string);
				}
			}			
			// echo json_encode($result);
			// echo json_encode($result['T_BANK'][1]['BANKN']);
			// exit();
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC BANK SPECIMEN PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_BANKSPECIMENT',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$this->dgeneral->commit_transaction();
			$msg = "Sync COA dan Nomor Rekening selesai, ".$jumlah_data." data yang diproses.";
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = "Sync SAP Gagal.";
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	
}

