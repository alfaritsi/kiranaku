<?php
/*
        @application  : 
        @author       : Lukman Hakim (7143) 
        @date         : 05.07.2021
        @contributor  :
              1. Lukman Hakim (7143) 05.07.2021
                 penambahan RFC untuk kode vendor
              etc.
    */

include_once APPPATH . "modules/data/controllers/BaseControllers.php";

class Rfc extends BaseControllers{
// Class Rfc extends MX_Controller{

	function __construct(){
	    parent::__construct();
	    $this->load->model('dmastervendor');
	    $this->load->model('dtransaksivendor');
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL)
	{
		switch ($param) {
			case 'vendor':
				/*
                     * Function Module : Z_RFC_P2P_SEARCH_VENDOR_BYNAME
                     * Used by :
                     * 		1. Kode Vendor
                     */
				// $this->get_master_kode_material();
				$nama_vendor  = (isset($_POST['nama_vendor']) ? $_POST['nama_vendor'] : NULL);
				$this->get_vendor($nama_vendor);
				break;
		}
	}

	public function set($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'create_vendor':
				/*
                     * Function Module : Z_RFC_P2P_CREATE_VENDOR
                     * Used by :
                     * 		1. Proc HO / Legal HO
                     */
				// $this->set_kode_material();
				$this->set_create_vendor();
				break;
			case 'extend_vendor':
				/*
                     * Function Module : Z_RFC_P2P_EXTEND_VENDOR
                     * Used by :
                     * 		1. Proc HO / Legal HO
                     */
				$this->set_extend_vendor();
				break;
			case 'change_vendor':
				/*
                     * Function Module : Z_RFC_P2P_CHANGE_VENDOR
                     * Used by :
                     * 		1. Proc HO / Legal HO
                     */
				$this->set_change_vendor();
				break;
			case 'delete_vendor':
				/*
                     * Function Module : Z_RFC_P2P_CHANGE_VENDOR
                     * Used by :
                     * 		1. Proc HO / Legal HO
                     */
				$this->set_delete_vendor($param2);
				break;
			case 'undelete_vendor':
				/*
                     * Function Module : Z_RFC_P2P_CHANGE_VENDOR
                     * Used by :
                     * 		1. Proc HO / Legal HO
                     */
				$this->set_undelete_vendor();
				break;
		}
	}

	/**********************************/
	/*			  private  			  */
	/**********************************/
	//get vendor
	private function get_vendor($nama_vendor)
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		// echo json_encode($arr_nama_vendor[0]);
		// exit();

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$arr_nama_vendor = explode(" ", $nama_vendor);
			$result = $this->data['sap']->callFunction(
				"Z_RFC_P2P_SEARCH_VENDOR_BYNAME",
				array(
					array("IMPORT", "I_VDRNAME", strtoupper(@$arr_nama_vendor[0])),
					array("IMPORT", "I_VDRNAME1", strtoupper(@$arr_nama_vendor[1])),
					array("TABLE", "T_VENDOR", array()),
				)
			);
			// echo json_encode(strtoupper($arr_nama_vendor[1]));
			// exit();
			// if ($result['E_RETURN']['TYPE'] == 'E') {
			// $msg = $code . " " . $result['E_RETURN']['MESSAGE'];
			// $sts = "NotOK";
			// } else {
			// $msg = "Master Vendor Tersedia";
			// $sts = "OK";
			// }
		}
		echo json_encode($result['T_VENDOR']);
		// $return = array('sts' => $sts, 'msg' => $msg);
		// echo json_encode($return);
	}

	//set create vendor
	private function set_create_vendor()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();


		$id_data 	  = $this->generate->kirana_decrypt($_POST['id_data']);
		$data_rfc     = $this->dtransaksidata->push_data_master_vendor($id_data);
		// echo json_encode($data_rfc);
		// // echo json_encode($_POST['I_KTOKK']);
		// exit();
		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_P2P_CREATE_VENDOR",
				array(
					array("IMPORT", "I_BUKRS", $_POST['I_BUKRS']),
					array("IMPORT", "I_EKORG", $_POST['I_EKORG']),
					array("IMPORT", "I_KTOKK", $_POST['I_KTOKK']),
					array("TABLE", "T_VENDOR", $data_rfc),
					array("TABLE", "T_RETURN", array()),
					array("EXPORT", "E_LIFNR", array()),
				)
			);
			//echo json_encode($_POST['I_BUKRS']);

			//exit();

			if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
				$message 	= $result['T_RETURN'][1]['MESSAGE'];
				$arr_lifnr	= explode(" ", $message);
				$lifnr 		= substr($arr_lifnr[1], 0, 10);
				// $lifnr 		= $result['E_LIFNR'];
				//update tbl_vendor_data
				$string = "
					update
					tbl_vendor_data
					set 
					lifnr='$lifnr',
					req='n',
					id_status='99'
					where id_data='$id_data'
				";
				$query  = $this->db->query($string);
				//update tbl_vendor_plant
				$string = "
					update
					tbl_vendor_plant
					set 
					status_sap='y',
					login_edit= '" . base64_decode($this->session->userdata("-id_user-")) . "',
					tanggal_edit= '$datetime'
					where id_data='$id_data'
				";
				$query  = $this->db->query($string);
				//log rfc
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_CREATE_VENDOR',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				//save log status
				$data_row_log = array(
					"id_data"	=> $id_data,
					"id_status"	=> 99
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_vendor_data_log", $data_row_log);
			} else {        //jika gagal
				$data_row = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_CREATE_VENDOR',
					'log_code'      => $result['T_RETURN'][1]['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_P2P_CREATE_VENDOR',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {
			$this->dgeneral->commit_transaction();
			$msg = "Pembuatan Master Vendor SAP berhasil.";
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	// //set change vendor
	// private function set_change_vendor_old()
	// {
	// // $this->connectSAP("ERP");            //prod
	// $this->connectSAP("ERP_310");            //310
	// // $this->connectSAP("ERP_KMTEMP");		//km_temp
	// $this->load->model('dtransaksidata');
	// $this->load->model('dtransaksivendor');
	// $this->general->connectDbPortal();
	// $this->dgeneral->begin_transaction();


	// $id_data 	  	= $this->generate->kirana_decrypt($_POST['id_data']);
	// $id_data_temp  	= $_POST['id_data_temp'];
	// $level  		= $_POST['level'];
	// $approval_legal	= $_POST['approval_legal'];
	// $pengajuan_ho_temp	= $_POST['pengajuan_ho_temp'];

	// if(($level==4)and($pengajuan_ho_temp=='y')){
	// $provinsi		= (@$_POST['provinsi']!=0) ? $_POST['provinsi'] : "";
	// $tax_type		= (@$_POST['tax_type']!=0) ? @$_POST['tax_type'] : "";
	// $tax_code		= (@$_POST['tax_code']!=0) ? @$_POST['tax_code'] : "";
	// $data_rfc = array(array(
	// "NAME1"		=> strtoupper($_POST['nama']),
	// "STREET"	=> $_POST['alamat'],
	// "HOUSE_NUM1"=> $_POST['no'],
	// "POST_CODE1"=> $_POST['kode_pos'],
	// "CITY1"		=> $_POST['kota'],
	// "COUNTRY"	=> $_POST['negara'],
	// "REGION"	=> $provinsi,
	// "STCD1"		=> $_POST['npwp'],
	// "STCD4"		=> $_POST['ktp'],
	// "WITHT"		=> $tax_type,
	// "WT_WITHCD"	=> $tax_code
	// ));
	// }else{
	// $data_rfc     	= $this->dtransaksidata->push_data_master_vendor_change($id_data_temp);
	// }
	// // echo json_encode('LIFNR:'.$_POST['I_LIFNR'].'-'); 
	// // echo json_encode('I_BUKRS:'.$_POST['I_BUKRS'].'-'); 
	// // echo json_encode('I_EKORG:'.$_POST['I_EKORG'].'-'); 
	// // echo json_encode($level); 
	// // echo json_encode($pengajuan_ho_temp); 
	// // echo json_encode($data_rfc); 
	// // // echo json_encode($id_data); 
	// // exit();
	// $datetime     = date("Y-m-d H:i:s");
	// if (empty($data_rfc)) {
	// $msg    = "Tidak Ada Data yang diproses";
	// $sts    = "NotOK";
	// $return = array('sts' => $sts, 'msg' => $msg);
	// echo json_encode($return);
	// exit();
	// }
	// //plant dengan status sap='y'
	// $data_plant 	= $this->dtransaksivendor->get_data_plant(NULL,'n', 'n',$id_data, NULL, 'y');
	// // echo json_encode($data_plant); 
	// // exit();
	// foreach($data_plant as $dt){
	// if ($this->data['sap']->getStatus() == SAPRFC_OK) {
	// // $this->loop_change($_POST['I_LIFNR'], $dt->BUKRS, $dt->plant, $data_rfc, $id_data, $id_data_temp);
	// $result = $this->data['sap']->callFunction(
	// "Z_RFC_P2P_CHANGE_VENDOR",
	// array(
	// array("IMPORT","I_LIFNR",$_POST['I_LIFNR']),				
	// // array("IMPORT","I_BUKRS",$_POST['I_BUKRS']),				
	// // array("IMPORT","I_EKORG",$_POST['I_EKORG']),				
	// array("IMPORT","I_BUKRS",$dt->BUKRS),				
	// array("IMPORT","I_EKORG",$dt->plant),				
	// array("TABLE", "T_CHANGED", $data_rfc),
	// array("TABLE", "T_RETURN", array()),
	// )
	// );

	// if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
	// // $cek_sukses = 'y';
	// // $message 	= $result['T_RETURN'][1]['MESSAGE'];
	// // $arr_lifnr	= explode(" ", $message);
	// // //jika change dr ho

	// // if(($level==4)and($pengajuan_ho_temp=='y')){
	// // $data_row = array(
	// // "perubahan_data"	=> 'y',
	// // "approval_legal"	=> 'n',
	// // "approval_proc"		=> 'n',
	// // "pengajuan_ho"		=> 'y',
	// // "jenis_pengajuan"	=> 'change',
	// // "id_data"			=> $id_data,
	// // "id_status"			=> 99,
	// // // "nama"	 			=> strtoupper($_POST['nama']),
	// // // "alamat"	 		=> $_POST['alamat'],
	// // // "no"	 			=> $_POST['no'],
	// // // "kode_pos"	 		=> $_POST['kode_pos'],
	// // // "provinsi"		 	=> $_POST['provinsi'],
	// // // "kota"			 	=> $_POST['kota'],
	// // // "negara"			=> $_POST['negara'],
	// // // "npwp"	 			=> $_POST['npwp'],
	// // // "ktp"	 			=> $_POST['ktp'],
	// // // "tax_type"	 		=> $tax_type,
	// // // "tax_code"	 		=> $tax_code,
	// // "nama"	 			=> $data_rfc->NAME1,
	// // "alamat"	 		=> $data_rfc[0]->STREET,
	// // "no"	 			=> $data_rfc[0]->HOUSE_NUM1,
	// // "kode_pos"	 		=> $data_rfc[0]->POST_CODE1,
	// // "provinsi"		 	=> $data_rfc[0]->REGION,
	// // "kota"			 	=> $data_rfc[0]->CITY1,
	// // "negara"			=> $data_rfc[0]->COUNTRY,
	// // "npwp"	 			=> $data_rfc[0]->STCD1,
	// // "ktp"	 			=> $data_rfc[0]->STCD4,
	// // "tax_type"	 		=> $data_rfc[0]->WITHT,
	// // "tax_code"	 		=> $data_rfc[0]->WT_WITHCD,
	// // "req"				=> 'n'
	// // );
	// // $data_row = $this->dgeneral->basic_column("insert", $data_row);
	// // $this->dgeneral->insert("tbl_vendor_data_tempxx", $data_row);

	// // $id_data_temp	= $this->db->insert_id();
	// // //save log extend
	// // $data_row_log = array(
	// // "id_data_temp"	=> $id_data_temp,
	// // "id_status"	=> 99
	// // );
	// // $data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
	// // $this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
	// // //update data vendor
	// // $string = "
	// // update
	// // tbl_vendor_data
	// // set 
	// // nama='".strtoupper($_POST['nama'])."',
	// // alamat='".$_POST['alamat']."',
	// // no='".$_POST['no']."',
	// // kode_pos='".$_POST['kode_pos']."',
	// // provinsi='".$_POST['provinsi']."',
	// // kota='".$_POST['kota']."',
	// // negara='".$_POST['negara']."',
	// // npwp='".$_POST['npwp']."',
	// // ktp='".$_POST['ktp']."',
	// // tax_type='".$tax_type."',
	// // tax_code='".$tax_code."'
	// // where 
	// // id_data='$id_data'
	// // ";
	// // $query  = $this->db->query($string);
	// // //update nama folder
	// // $nama_folder = $id_data.' - '.strtoupper($_POST['nama']);
	// // $string = "
	// // update
	// // tbl_folder
	// // set 
	// // nama='".strtoupper($_POST['nama'])."'
	// // where 
	// // nama='$nama_folder'
	// // ";
	// // $query  = $this->db->query($string);
	// }else{
	// // //update tbl_vendor_data_temp
	// // $string = "
	// // update
	// // tbl_vendor_data_temp
	// // set 
	// // req='n',
	// // id_status='99'
	// // where 
	// // id_data_temp='$id_data_temp'
	// // ";
	// // $query  = $this->db->query($string);
	// // //save log change
	// // $data_row_log = array(
	// // "id_data_temp"	=> $id_data_temp,
	// // "id_status"	=> 99
	// // );
	// // $data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
	// // $this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);

	// // //update data vendor
	// // $string = "
	// // update
	// // tbl_vendor_data
	// // set 
	// // nama	='".$data_rfc->NAME1."',
	// // alamat	='".$data_rfc[0]->STREET."',
	// // no		='".$data_rfc[0]->HOUSE_NUM1."',
	// // kode_pos='".$data_rfc[0]->POST_CODE1."',
	// // provinsi='".$data_rfc[0]->REGION."',
	// // kota	='".$data_rfc[0]->CITY1."',
	// // negara	='".$data_rfc[0]->COUNTRY."',
	// // npwp	='".$data_rfc[0]->STCD1."',
	// // ktp		='".$data_rfc[0]->STCD4."',
	// // tax_type='".$data_rfc[0]->WITHT."',
	// // tax_code='".$data_rfc[0]->WT_WITHCD."'
	// // where 
	// // id_data='$id_data'
	// // ";
	// // $query  = $this->db->query($string);
	// // //update nama folder
	// // $nama_folder = $id_data.' - '.strtoupper($data_rfc[0]->NAME1);
	// // $string = "
	// // update
	// // tbl_folder
	// // set 
	// // nama='".strtoupper($_POST['nama'])."'
	// // where 
	// // nama='$nama_folder'
	// // ";
	// // $query  = $this->db->query($string);

	// }

	// //log rfc
	// $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
	// $data_row_log = array(
	// 'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
	// 'rfc_name'      => 'Z_RFC_P2P_CHANGE_VENDOR',
	// 'log_code'      => '',
	// 'log_status'    => 'Berhasil '.$dt->plant,
	// 'log_desc'      => $status,
	// 'executed_by'   => 0,
	// 'executed_date' => $datetime
	// );
	// $this->dgeneral->insert("tbl_log_rfc", $data_row_log);					


	// } else {        //jika gagal
	// $cek_sukses = 'n';
	// $data_row = array(
	// 'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
	// 'rfc_name'      => 'Z_RFC_P2P_CHANGE_VENDOR',
	// 'log_code'      => $result['T_RETURN'][1]['TYPE'],
	// 'log_status'    => 'Gagal',
	// 'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
	// 'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
	// 'executed_date' => $datetime
	// );
	// $this->dgeneral->insert('tbl_log_rfc', $data_row);
	// }
	// } else {
	// $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
	// $data_row_log = array(
	// 'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
	// 'rfc_name'      => 'Z_RFC_P2P_CHANGE_VENDOR',
	// 'log_code'      => 'E',
	// 'log_status'    => 'Gagal',
	// 'log_desc'      => $status,
	// 'executed_by'   => 0,
	// 'executed_date' => $datetime
	// );
	// $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
	// }			
	// }


	// //================================SAVE ALL================================//
	// // if ($result['T_RETURN'][1]['TYPE'] == 'S') {
	// if ($cek_sukses == 'y') {
	// $this->dgeneral->commit_transaction();
	// // $msg = "Pembuatan Master Vendor SAP berhasil.";
	// $msg = $result['T_RETURN'][1]['MESSAGE'];
	// $sts = "OK";
	// } else {
	// $this->dgeneral->rollback_transaction();
	// $msg = $result['T_RETURN'][1]['MESSAGE'];
	// $sts = "NotOK";
	// }

	// $return = array('sts' => $sts, 'msg' => $msg);
	// echo json_encode($return);
	// }



	//set change vendor old
	private function set_change_vendor()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->load->model('dmastervendor');
		$this->load->model('dtransaksivendor');
		$this->general->connectDbPortal();
		// $this->dgeneral->begin_transaction();


		$id_data 	  	= $this->generate->kirana_decrypt($_POST['id_data']);
		$id_data_temp  	= (isset($_POST['id_data_temp']) ? $_POST['id_data_temp'] : NULL);
		$level  		= $_POST['level'];
		$approval_legal	= $_POST['approval_legal'];
		$pengajuan_ho	= $_POST['pengajuan_ho'];
		// echo json_encode($level); 
		// echo json_encode($id_data_temp); 
		// // echo json_encode($id_data); 
		// exit();

		if (($level == 4) and ($id_data_temp == null)) {
			$provinsi		= (@$_POST['provinsi'] != 0) ? $_POST['provinsi'] : "";
			$tax_type		= (@$_POST['tax_type'] != 0) ? @$_POST['tax_type'] : "";
			$tax_code		= (@$_POST['tax_code'] != 0) ? @$_POST['tax_code'] : "";
			$data_rfc = array(array(
				"NAME1"		=> strtoupper($_POST['nama']),
				"STREET"	=> $_POST['alamat'],
				"HOUSE_NUM1" => $_POST['no'],
				"POST_CODE1" => $_POST['kode_pos'],
				"CITY1"		=> $_POST['kota'],
				"COUNTRY"	=> $_POST['negara'],
				"REGION"	=> $provinsi,
				"STCD1"		=> $_POST['npwp'],
				"STCD4"		=> $_POST['ktp'],
				"WITHT"		=> $tax_type,
				"WT_WITHCD"	=> $tax_code,
				"ANRED"		=> $_POST['title']
			));
		} else {
			$data_rfc     	= $this->dtransaksidata->push_data_master_vendor_change($id_data_temp);
		}
		// echo json_encode('LIFNR:'.$_POST['I_LIFNR'].'-'); 
		// echo json_encode('I_BUKRS:'.$_POST['I_BUKRS'].'-'); 
		// echo json_encode('I_EKORG:'.$_POST['I_EKORG'].'-'); 
		// echo json_encode($data_rfc); 
		// // echo json_encode($id_data); 
		// exit();
		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		$types_all = array();
		$messages_all = array();
		$data_plant 	= $this->dtransaksivendor->get_data_plant_new(NULL, 'n', 'n', $id_data, NULL, 'y');
		$this->dgeneral->begin_transaction();
		foreach ($data_plant as $dt) {
			// $this->loop_change($_POST['I_LIFNR'], $dt->BUKRS, $dt->plant, $data_rfc, $id_data, $id_data_temp);
			$result = $this->data['sap']->callFunction(
				"Z_RFC_P2P_CHANGE_VENDOR",
				array(
					array("IMPORT", "I_LIFNR", $_POST['I_LIFNR']),
					array("IMPORT", "I_BUKRS", $dt->BUKRS),
					array("IMPORT", "I_EKORG", $dt->plant),
					array("TABLE", "T_CHANGED", $data_rfc),
					array("TABLE", "T_RETURN", array()),
				)
			);
			if (!empty($result["T_RETURN"])) {
				foreach ($result["T_RETURN"] as $return) {
					$type[]    = $return['TYPE'];
					$message[] = $return['MESSAGE'];
				}
			}
			if (in_array("S", $type)) {
				//update tbl_vendor_data_temp
				$string = "
					update
					tbl_vendor_data_temp
					set 
					req='n',
					id_status='99'
					where 
					id_data_temp='$id_data_temp'
				";
				$query  = $this->db->query($string);
				//save log change
				$data_row_log = array(
					"id_data_temp"	=> $id_data_temp,
					"id_status"	=> 99
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);

				//update data vendor
				if ($id_data_temp == NULL) {
					$string = "
						update
						tbl_vendor_data
						set 
						nama	='" . $data_rfc[0]['NAME1'] . "',
						alamat	='" . $data_rfc[0]['STREET'] . "',
						no		='" . $data_rfc[0]['HOUSE_NUM1'] . "',
						kode_pos='" . $data_rfc[0]['POST_CODE1'] . "',
						provinsi='" . $data_rfc[0]['REGION'] . "',
						kota	='" . $data_rfc[0]['CITY1'] . "',
						negara	='" . $data_rfc[0]['COUNTRY'] . "',
						npwp	='" . $data_rfc[0]['STCD1'] . "',
						ktp		='" . $data_rfc[0]['STCD4'] . "',
						tax_type='" . $data_rfc[0]['WITHT'] . "',
						tax_code='" . $data_rfc[0]['WT_WITHCD'] . "',
						title	='" . $data_rfc[0]['ANRED'] . "'
						where 
						id_data='$id_data'
					";
					$query  = $this->db->query($string);
					//update nama folder
					$nama_folder = $id_data . ' - ' . strtoupper($data_rfc[0]['NAME1']);
					$string = "
						update
						tbl_folder
						set 
						nama='$nama_folder'
						where 
						nama=(select nama from tbl_vendor_data where id_data='$id_data')
					";
					$query  = $this->db->query($string);
				} else {
					$string = "
						update
						tbl_vendor_data
						set 
						nama	='" . $data_rfc[0]->NAME1 . "',
						alamat	='" . $data_rfc[0]->STREET . "',
						no		='" . $data_rfc[0]->HOUSE_NUM1 . "',
						kode_pos='" . $data_rfc[0]->POST_CODE1 . "',
						provinsi='" . $data_rfc[0]->REGION . "',
						kota	='" . $data_rfc[0]->CITY1 . "',
						negara	='" . $data_rfc[0]->COUNTRY . "',
						npwp	='" . $data_rfc[0]->STCD1 . "',
						ktp		='" . $data_rfc[0]->STCD4 . "',
						tax_type='" . $data_rfc[0]->WITHT . "',
						tax_code='" . $data_rfc[0]->WT_WITHCD . "',
						title	='" . $data_rfc[0]->ANRED . "'
						where 
						id_data='$id_data'
					";
					$query  = $this->db->query($string);
					//update nama folder
					$nama_folder = $id_data . ' - ' . strtoupper($data_rfc[0]->NAME1);
					$string = "
						update
						tbl_folder
						set 
						nama='$nama_folder'
						where 
						nama=(select nama from tbl_vendor_data where id_data='$id_data')
					";
					$query  = $this->db->query($string);
				}

				$data_row = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_CHANGE_VENDOR',
					'log_code'      => implode(",", $type),
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil Change Vendor di '.$dt->plant,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
			} else {
				$data_row = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_CHANGE_VENDOR',
					'log_code'      => implode(",", $type),
					'log_status'    => 'Gagal ',
					'log_desc'      => 'Gagal Change Vendor di ' . $dt->plant . ' (' . implode(",", $message) . ')',
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
				
			}
			$types_all[] = $type;
			$messages_all[] = $data_row['log_desc'];
		}
		
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = implode(".", $messages_all);
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = implode(".", $messages_all);
			$sts = "OK";
			if (in_array('E', $type) === true) {
				$sts = "NotOK";
			}
		}
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		exit();

		// $msg = "Change Vendor SAP berhasil.";
		// $sts = "OK";
		// $return = array('sts' => $sts, 'msg' => $msg);
		// echo json_encode($return);
	}

	//looping change
	public function loop_change($I_LIFNR = NULL, $I_BUKRS = NULL, $I_EKORG = NULL, $data_rfc = NULL, $id_data = NULL, $id_data_temp = NULL)
	{
		$datetime    	= date("Y-m-d H:i:s");
		$result = $this->data['sap']->callFunction(
			"Z_RFC_P2P_CHANGE_VENDOR",
			array(
				array("IMPORT", "I_LIFNR", $I_LIFNR),
				array("IMPORT", "I_BUKRS", $I_BUKRS),
				array("IMPORT", "I_EKORG", $I_EKORG),
				array("TABLE", "T_CHANGED", $data_rfc),
				array("TABLE", "T_RETURN", array()),
			)
		);

		if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
			$message 	= $result['T_RETURN'][1]['MESSAGE'];
			$arr_lifnr	= explode(" ", $message);
			//update tbl_vendor_data_temp
			$string = "
				update
				tbl_vendor_data_temp
				set 
				req='n',
				id_status='99'
				where 
				id_data_temp='$id_data_temp'
			";
			$query  = $this->db->query($string);
			//save log change
			$data_row_log = array(
				"id_data_temp"	=> $id_data_temp,
				"id_status"	=> 99
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);

			//update data vendor
			if ($id_data_temp == NULL) {
				$string = "
					update
					tbl_vendor_data
					set 
					nama	='" . $data_rfc[0]['NAME1'] . "',
					alamat	='" . $data_rfc[0]['STREET'] . "',
					no		='" . $data_rfc[0]['HOUSE_NUM1'] . "',
					kode_pos='" . $data_rfc[0]['POST_CODE1'] . "',
					provinsi='" . $data_rfc[0]['REGION'] . "',
					kota	='" . $data_rfc[0]['CITY1'] . "',
					negara	='" . $data_rfc[0]['COUNTRY'] . "',
					npwp	='" . $data_rfc[0]['STCD1'] . "',
					ktp		='" . $data_rfc[0]['STCD4'] . "',
					tax_type='" . $data_rfc[0]['WITHT'] . "',
					tax_code='" . $data_rfc[0]['WT_WITHCD'] . "'
					where 
					id_data='$id_data'
				";
				$query  = $this->db->query($string);
				//update nama folder
				$nama_folder = $id_data . ' - ' . strtoupper($data_rfc[0]['NAME1']);
				$string = "
					update
					tbl_folder
					set 
					nama='$nama_folder'
					where 
					nama=(select nama from tbl_vendor_data where id_data='$id_data')
				";
				$query  = $this->db->query($string);
			} else {
				$string = "
					update
					tbl_vendor_data
					set 
					nama	='" . $data_rfc[0]->NAME1 . "',
					alamat	='" . $data_rfc[0]->STREET . "',
					no		='" . $data_rfc[0]->HOUSE_NUM1 . "',
					kode_pos='" . $data_rfc[0]->POST_CODE1 . "',
					provinsi='" . $data_rfc[0]->REGION . "',
					kota	='" . $data_rfc[0]->CITY1 . "',
					negara	='" . $data_rfc[0]->COUNTRY . "',
					npwp	='" . $data_rfc[0]->STCD1 . "',
					ktp		='" . $data_rfc[0]->STCD4 . "',
					tax_type='" . $data_rfc[0]->WITHT . "',
					tax_code='" . $data_rfc[0]->WT_WITHCD . "'
					where 
					id_data='$id_data'
				";
				$query  = $this->db->query($string);
				//update nama folder
				$nama_folder = $id_data . ' - ' . strtoupper($data_rfc[0]->NAME1);
				$string = "
					update
					tbl_folder
					set 
					nama='$nama_folder'
					where 
					nama=(select nama from tbl_vendor_data where id_data='$id_data')
				";
				$query  = $this->db->query($string);
			}

			//log rfc
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_P2P_CHANGE_VENDOR',
				'log_code'      => '',
				'log_status'    => 'Berhasil ' . $I_EKORG,
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		} else {        //jika gagal
			$data_row = array(
				'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_P2P_CHANGE_VENDOR',
				'log_code'      => $result['T_RETURN'][1]['TYPE'],
				'log_status'    => 'Gagal ' . $I_EKORG,
				'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
				'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
				'executed_date' => $datetime
			);
			$this->dgeneral->insert('tbl_log_rfc', $data_row);
			//jika gagal looping
			// $this->loop_change($I_LIFNR, $I_BUKRS, $I_EKORG, $data_rfc, $id_data, $id_data_temp);
		}
		//================================SAVE ALL================================//
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {
			//save log change
			$data_row_log = array(
				"id_data_temp"	=> $id_data_temp,
				"id_status"	=> 99
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);

			$this->dgeneral->commit_transaction();
			$msg = "Change Vendor SAP berhasil.";
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}
	}
	//set delete vendor
	private function set_delete_vendor($param2)
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->load->model('dtransaksivendor');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_data 	  = $this->generate->kirana_decrypt($_POST['id_data']);
		$id_data_temp = $_POST['id_data_temp'];
		$komentar 	  = (isset($_POST['komentar_delete']) ? $_POST['komentar_delete'] : NULL);

		if ($param2 == 'ho') {
			$arr_plant	  = array();
			foreach ($_POST['plant_delete'] as $dt) {
				array_push($arr_plant, $dt);
			}
			$data_rfc     = $this->dtransaksidata->push_data_vendor_delete_ho($arr_plant, $_POST['I_LIFNR']);
		} else {
			$data_rfc     = $this->dtransaksidata->push_data_vendor_delete($id_data_temp, $_POST['I_LIFNR']);
		}

		// echo json_encode($_POST['I_LIFNR']);
		// echo json_encode($_POST['I_BUKRS']);
		// echo json_encode($_POST['I_EKORG']); 
		// echo json_encode($data_rfc); 
		// exit();
		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_P2P_DELETE_VENDOR",
				array(
					array("IMPORT", "I_LIFNR", $_POST['I_LIFNR']),
					array("IMPORT", "I_BUKRS", $_POST['I_BUKRS']),
					array("IMPORT", "I_EKORG", $_POST['I_EKORG']),
					array("TABLE", "T_VENDOR", $data_rfc),
					array("TABLE", "T_RETURN", array()),
				)
			);

			// echo json_encode($result); 
			// exit();

			if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
				$message 	= $result['T_RETURN'][1]['MESSAGE'];
				$arr_lifnr	= explode(" ", $message);
				if ($param2 == 'ho') {
					//save plant_temp delete
					$plants		= (isset($_POST['plant_delete']) ? implode(",", $_POST['plant_delete']) : NULL);
					$arr_plant 	= explode(',', $plants);
					foreach ($arr_plant as $plant) {
						$ck_plant 	= $this->dtransaksivendor->get_data_plant_temp(NULL, NULL, NULL, $id_data, $plant, 'n', NULL, 'delete', $id_data_temp);
						if (count($ck_plant) == 0) {
							$data_delete = array(
								"id_data" 		=> $id_data,
								"id_data_temp" 	=> $id_data_temp,
								"status_sap"	=> 'y',
								"plant" 		=> $plant,
								"jenis_pengajuan"	=> 'delete',
								"status_delete"	=> 'y'
							);
							$data_delete = $this->dgeneral->basic_column("insert", $data_delete);
							if ($plant != '') {
								$data_delete = $this->dgeneral->basic_column("insert", $data_delete);
								$this->dgeneral->insert("tbl_vendor_plant_temp", $data_delete);
							}
						}
					}
				} else {
					//update tbl_vendor_data_temp
					$string = "
						update
						tbl_vendor_data_temp
						set 
						req='n',
						id_status='99'
						where 
						id_data_temp='$id_data_temp'
					";
					$query  = $this->db->query($string);
					//save log delete
					$data_row_log = array(
						"komentar"		=> $komentar,
						"id_data_temp"	=> $id_data_temp,
						"id_status"	=> 99
					);
					$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
					$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);
				}

				//update plant
				$list_plant_delete = "";
				foreach ($data_rfc as $dt) {
					$list_plant_delete .= $dt->EKORG.",";
					$data_update = array(
						"status_delete"		=> 'y'
					);
					$this->dgeneral->update("tbl_vendor_plant", $data_update, array(
						array(
							'kolom' => 'id_data',
							'value' => $id_data
						),
						array(
							'kolom' => 'plant',
							'value' => $dt->EKORG
						)
					));
				}

				//log rfc
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_DELETE_VENDOR',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				
				//send email
				$data_vendor_temp	= $this->dtransaksivendor->get_data_vendor_temp(NULL, NULL, NULL, NULL, $id_data_temp);
				$user_create		= $this->dtransaksivendor->get_data_karyawan(NULL, $data_vendor_temp[0]->login_buat);
				$content = '
					<table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="4" style="font-size: 12px;">
						<thead>
							<tr>
								<th align="left" width="20%">Tanggal Pengajuan</th>
								<th align="left">: '.date('d.m.Y',strtotime($data_vendor_temp[0]->tanggal_buat)).'</th>
							</tr>
							<tr>
								<th align="left" width="20%">Nama Vendor</th>
								<th align="left">: '.$_POST['nama'].'</th>
							</tr>
							<tr>
								<th align="left" width="20%">Jenis Pengajuan</th>
								<th align="left">: Delete Vendor</th>
							</tr>
							<tr>
								<th align="left" width="20%">Delete Plant</th>
								<th align="left">: '.$list_plant_delete.'</th>
							</tr>
							<tr>
								<th align="left" width="20%">Alasan Delete</th>
								<th align="left">: '.$data_vendor_temp[0]->alasan_delete.'</th>
							</tr>
							<tr>
								<th align="left" width="20%">Komentar</th>
								<th align="left">: '.$data_vendor_temp[0]->komentar.'</th>
							</tr>
							<tr>
								<th align="left" width="20%">Status Pengajuan</th>
								<th align="left">: Completed</th>
							</tr>
						</thead>
						
					</table>';	
				$subject = "Delete Master Vendor (".$_POST['nama'].")";	
				$data_temp 		= $this->dtransaksivendor->get_data_vendor_temp(NULL,NULL,NULL, NULL, $id_data_temp);	
				$email_pengaju 	= $this->dtransaksivendor->get_data_karyawan(NULL, $data_temp[0]->login_buat);
				$this->template_email_vendor('FITRI.PUDJININGTYAS@KIRANAMEGATARA.COM',$subject,$content,$email_pengaju[0]->nama);
				// $this->template_email_vendor($email_pengaju[0]->email,$subject,$content,$email_pengaju[0]->nama);
			} else {        //jika gagal
				$data_row = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_DELETE_VENDOR',
					'log_code'      => $result['T_RETURN'][1]['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_P2P_DELETE_VENDOR',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {
			$this->dgeneral->commit_transaction();
			// $msg = "Pembuatan Master Vendor SAP berhasil.";
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//set undelete vendor
	private function set_undelete_vendor()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->load->model('dtransaksivendor');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();


		$id_data 	  = $this->generate->kirana_decrypt($_POST['id_data']);
		$id_data_temp = $_POST['id_data_temp'];
		$data_rfc     = $this->dtransaksidata->push_data_vendor_undelete($id_data_temp, $_POST['I_LIFNR']);
		// echo json_encode($_POST['I_LIFNR']);
		// echo json_encode($_POST['I_BUKRS']);
		// echo json_encode($_POST['I_EKORG']); 
		// echo json_encode($data_rfc); 
		// exit();
		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_P2P_UNDO_DELETE_VENDOR",
				array(
					array("IMPORT", "I_LIFNR", $_POST['I_LIFNR']),
					array("IMPORT", "I_BUKRS", $_POST['I_BUKRS']),
					array("IMPORT", "I_EKORG", $_POST['I_EKORG']),
					array("TABLE", "T_VENDOR", $data_rfc),
					array("TABLE", "T_RETURN", array()),
				)
			);
			// echo json_encode($result); 
			// exit();

			if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
				$message 	= $result['T_RETURN'][1]['MESSAGE'];
				$arr_lifnr	= explode(" ", $message);
				//update tbl_vendor_data_temp
				$string = "
					update
					tbl_vendor_data_temp
					set 
					req='n',
					id_status='99'
					where 
					id_data_temp='$id_data_temp'
				";
				$query  = $this->db->query($string);
				//update plant
				foreach ($data_rfc as $dt) {
					$data_update = array(
						"status_delete"		=> 'n'
					);
					$this->dgeneral->update("tbl_vendor_plant", $data_update, array(
						array(
							'kolom' => 'id_data',
							'value' => $id_data
						),
						array(
							'kolom' => 'plant',
							'value' => $dt->EKORG
						)
					));
				}
				//log rfc
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_UNDO_DELETE_VENDOR',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {        //jika gagal
				$data_row = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_UNDO_DELETE_VENDOR',
					'log_code'      => $result['T_RETURN'][1]['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_P2P_UNDO_DELETE_VENDOR',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {
			$this->dgeneral->commit_transaction();
			// $msg = "Pembuatan Master Vendor SAP berhasil.";
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//set extend vendor
	private function set_extend_vendor_old()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();


		$id_data  		= $this->generate->kirana_decrypt($_POST['id_data']);
		$id_data_temp  	= $_POST['id_data_temp'];
		$data_rfc     = $this->dtransaksidata->push_data_extend_vendor($id_data);
		$data_rfc2     = $this->dtransaksidata->push_data_extend_vendor2($id_data);
		// echo json_encode('LIFNR: '.$_POST['I_LIFNR']);
		// echo json_encode('I_KTOKK: '.$_POST['I_KTOKK']);
		// echo json_encode('I_BUKRS_REF: '.$_POST['I_BUKRS_REF']);
		// echo json_encode('I_EKORG_REF: '.$_POST['I_EKORG_REF']);
		// echo json_encode($data_rfc2);
		// exit();
		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		$arr_ck = array();
		foreach ($data_rfc as $dt) {
			if ($this->data['sap']->getStatus() == SAPRFC_OK) {
				$result = $this->data['sap']->callFunction(
					"Z_RFC_P2P_EXTEND_VENDOR",
					array(
						array("IMPORT", "I_LIFNR", $_POST['I_LIFNR']),
						array("IMPORT", "I_BUKRS", $dt->BUKRS),
						array("IMPORT", "I_EKORG", $dt->EKORG),
						array("IMPORT", "I_KTOKK", $_POST['I_KTOKK']),
						array("IMPORT", "I_BUKRS_REF", $_POST['I_BUKRS_REF']),
						array("IMPORT", "I_EKORG_REF", $_POST['I_EKORG_REF']),
						array("TABLE", "T_RETURN", array())
					)
				);
				// echo json_encode($result);
				// exit();
				$arr_ck[] = $result;

				if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
					$cek_sukses = 'y';
					//update tbl_vendor_data_temp
					$string = "
						update
						tbl_vendor_data_temp
						set 
						req='n',
						id_status='99'
						where 
						id_data_temp='$id_data_temp'
					";
					$query  = $this->db->query($string);
					//update tbl_vendor_plant
					$string = "
						update
						tbl_vendor_plant
						set 
						status_sap='y',
						status_delete='n'
						where id_data='$id_data' and plant='" . $dt->EKORG . "' and status_sap='n'
					";
					$query  = $this->db->query($string);

					//log rfc
					$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
					$data_row_log = array(
						'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
						'log_code'      => '',
						'log_status'    => 'Berhasil' . $dt->EKORG,
						'log_desc'      => $status,
						'executed_by'   => 0,
						'executed_date' => $datetime
					);
					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				} else {        //jika gagal
					$cek_sukses = 'n';
					$data_row = array(
						'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
						'log_code'      => $result['T_RETURN'][1]['TYPE'],
						'log_status'    => 'Gagal' . $dt->EKORG,
						'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);
					$this->dgeneral->insert('tbl_log_rfc', $data_row);
					//========================
					//jika gagal proses ulang
					//========================
					$result = $this->data['sap']->callFunction(
						"Z_RFC_P2P_EXTEND_VENDOR",
						array(
							array("IMPORT", "I_LIFNR", $_POST['I_LIFNR']),
							array("IMPORT", "I_BUKRS", $dt->BUKRS),
							array("IMPORT", "I_EKORG", $dt->EKORG),
							array("IMPORT", "I_KTOKK", $_POST['I_KTOKK']),
							array("IMPORT", "I_BUKRS_REF", $_POST['I_BUKRS_REF']),
							array("IMPORT", "I_EKORG_REF", $_POST['I_EKORG_REF']),
							array("TABLE", "T_RETURN", array())
						)
					);
					// echo json_encode($result);
					// exit();
					$arr_ck[] = $result;

					if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
						$cek_sukses = 'y';
						//update tbl_vendor_data_temp
						$string = "
							update
							tbl_vendor_data_temp
							set 
							req='n',
							id_status='99'
							where 
							id_data_temp='$id_data_temp'
						";
						$query  = $this->db->query($string);
						//update tbl_vendor_plant
						$string = "
							update
							tbl_vendor_plant
							set 
							status_sap='y',
							status_delete='n'
							where id_data='$id_data' and plant='" . $dt->EKORG . "' and status_sap='n'
						";
						$query  = $this->db->query($string);

						//log rfc
						$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
						$data_row_log = array(
							'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
							'log_code'      => '',
							'log_status'    => 'Berhasil' . $dt->EKORG,
							'log_desc'      => $status,
							'executed_by'   => 0,
							'executed_date' => $datetime
						);
						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
					} else {        //jika gagal
						$cek_sukses = 'n';
						$data_row = array(
							'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
							'log_code'      => $result['T_RETURN'][1]['TYPE'],
							'log_status'    => 'Gagal' . $dt->EKORG,
							'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
						$this->dgeneral->insert('tbl_log_rfc', $data_row);
					}
					//========================
					//jika gagal proses ulang sampe sini
					//========================
				}
			}
			// echo json_encode($arr_ck);
			// exit();
		}
		//data 2
		foreach ($data_rfc2 as $dt) {
			if ($this->data['sap']->getStatus() == SAPRFC_OK) {
				$result = $this->data['sap']->callFunction(
					"Z_RFC_P2P_EXTEND_VENDOR",
					array(
						array("IMPORT", "I_LIFNR", $_POST['I_LIFNR']),
						array("IMPORT", "I_BUKRS", $dt->BUKRS),
						array("IMPORT", "I_EKORG", $dt->EKORG),
						array("IMPORT", "I_KTOKK", $_POST['I_KTOKK']),
						array("IMPORT", "I_BUKRS_REF", $_POST['I_BUKRS_REF']),
						array("IMPORT", "I_EKORG_REF", $_POST['I_EKORG_REF']),
						array("TABLE", "T_RETURN", array())
					)
				);
				// echo json_encode($result);
				// exit();
				$arr_ck[] = $result;

				if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
					$cek_sukses = 'y';
					//update tbl_vendor_data_temp
					$string = "
						update
						tbl_vendor_data_temp
						set 
						req='n',
						id_status='99'
						where 
						id_data_temp='$id_data_temp'
					";
					$query  = $this->db->query($string);
					//update tbl_vendor_plant
					$string = "
						update
						tbl_vendor_plant
						set 
						status_sap='y',
						status_delete='n'
						where id_data='$id_data' and plant='" . $dt->EKORG . "' and status_sap='n'
					";
					$query  = $this->db->query($string);

					//log rfc
					$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
					$data_row_log = array(
						'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
						'log_code'      => '',
						'log_status'    => 'Berhasil' . $dt->EKORG,
						'log_desc'      => $status,
						'executed_by'   => 0,
						'executed_date' => $datetime
					);
					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				} else {        //jika gagal
					$cek_sukses = 'n';
					$data_row = array(
						'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
						'log_code'      => $result['T_RETURN'][1]['TYPE'],
						'log_status'    => 'Gagal' . $dt->EKORG,
						'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);
					$this->dgeneral->insert('tbl_log_rfc', $data_row);
					//========================
					//jika gagal proses ulang
					//========================
					$result = $this->data['sap']->callFunction(
						"Z_RFC_P2P_EXTEND_VENDOR",
						array(
							array("IMPORT", "I_LIFNR", $_POST['I_LIFNR']),
							array("IMPORT", "I_BUKRS", $dt->BUKRS),
							array("IMPORT", "I_EKORG", $dt->EKORG),
							array("IMPORT", "I_KTOKK", $_POST['I_KTOKK']),
							array("IMPORT", "I_BUKRS_REF", $_POST['I_BUKRS_REF']),
							array("IMPORT", "I_EKORG_REF", $_POST['I_EKORG_REF']),
							array("TABLE", "T_RETURN", array())
						)
					);
					// echo json_encode($result);
					// exit();
					$arr_ck[] = $result;

					if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
						$cek_sukses = 'y';
						//update tbl_vendor_data_temp
						$string = "
							update
							tbl_vendor_data_temp
							set 
							req='n',
							id_status='99'
							where 
							id_data_temp='$id_data_temp'
						";
						$query  = $this->db->query($string);
						//update tbl_vendor_plant
						$string = "
							update
							tbl_vendor_plant
							set 
							status_sap='y',
							status_delete='n'
							where id_data='$id_data' and plant='" . $dt->EKORG . "' and status_sap='n'
						";
						$query  = $this->db->query($string);

						//log rfc
						$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
						$data_row_log = array(
							'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
							'log_code'      => '',
							'log_status'    => 'Berhasil' . $dt->EKORG,
							'log_desc'      => $status,
							'executed_by'   => 0,
							'executed_date' => $datetime
						);
						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
					} else {        //jika gagal
						$cek_sukses = 'n';
						$data_row = array(
							'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
							'log_code'      => $result['T_RETURN'][1]['TYPE'],
							'log_status'    => 'Gagal' . $dt->EKORG,
							'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
						$this->dgeneral->insert('tbl_log_rfc', $data_row);
					}
					//========================
					//jika gagal proses ulang sampe sini
					//========================
				}
			}
			// echo json_encode($arr_ck);
			// exit();
		}

		//================================SAVE ALL================================//
		// if ($result['T_RETURN'][1]['TYPE'] == 'S') {
		if ($cek_sukses == 'y') {
			//save log extend
			$data_row_log = array(
				"id_data_temp"	=> $id_data_temp,
				"id_status"	=> 99
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);

			$this->dgeneral->commit_transaction();
			$msg = "Extend Master Vendor SAP berhasil.";
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//set extend vendor
	public function set_extend_vendor()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();

		$id_data  		= $this->generate->kirana_decrypt($_POST['id_data']);
		$id_data_temp  	= $_POST['id_data_temp'];
		$data_rfc     	= $this->dtransaksidata->push_data_extend_vendor($id_data);
		$datetime    	= date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}

		$types_all = array();
		$messages_all = array();

		$this->dgeneral->begin_transaction();
		foreach ($data_rfc as $dt) {
			if ($this->data['sap']->getStatus() == SAPRFC_OK) {
				$type    = array();
				$message = array();

				$datetime    	= date("Y-m-d H:i:s");
				$result = $this->data['sap']->callFunction(
					"Z_RFC_P2P_EXTEND_VENDOR",
					array(
						array("IMPORT", "I_LIFNR", $_POST['I_LIFNR']),
						array("IMPORT", "I_BUKRS", $dt->BUKRS),
						array("IMPORT", "I_EKORG", $dt->EKORG),
						array("IMPORT", "I_KTOKK", $_POST['I_KTOKK']),
						array("IMPORT", "I_BUKRS_REF", $_POST['I_BUKRS_REF']),
						array("IMPORT", "I_EKORG_REF", $_POST['I_EKORG_REF']),
						array("TABLE", "T_RETURN", array())
					)
				);

				if (!empty($result["T_RETURN"])) {
					foreach ($result["T_RETURN"] as $return) {
						$type[]    = $return['TYPE'];
						$message[] = $return['MESSAGE'];
					}
				}

				if (in_array("S", $type)) {
					//update tbl_vendor_plant
					$string = "
						UPDATE tbl_vendor_plant
						   SET status_sap='y',
							   status_delete='n'
						 WHERE id_data='$id_data' 
						   AND plant='" . $dt->EKORG . "' and status_sap='n'
					";
					$this->db->query($string);

					$data_row_log = array(
						'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
						'log_code'      => implode(",", $type),
						'log_status'    => 'Berhasil',
						'log_desc'      => 'Berhasil Extend Vendor ' . $_POST['I_LIFNR'] . ' di ' . $dt->EKORG,
						'executed_by'   => 0,
						'executed_date' => $datetime
					);
					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
					
				} else {
					$data_row_log = array(
						'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
						'log_code'      => implode(",", $type),
						'log_status'    => 'Gagal',
						'log_desc'      => 'Gagal Extend Vendor ' . $_POST['I_LIFNR'] . ' di ' . $dt->EKORG . ' (' . implode(",", $message) . ')',
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);
					$this->dgeneral->insert('tbl_log_rfc', $data_row_log);
				}

				$types_all[] = $type;
				$messages_all[] = $data_row_log['log_desc'];
			}
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = implode(".", $messages_all);
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			//cek tbl_vendor_plant apakah sudah sync semua
			$ck_data     	= $this->dtransaksidata->ck_data_plant("open",$id_data);
			if ($ck_data[0]->jumlah == 0) {
				
				$string = "			
					UPDATE tbl_vendor_data_temp
					   SET req='n',
						   id_status='99'
					 WHERE id_data_temp='$id_data_temp'
				";
				$this->db->query($string);
			}

			$msg = implode(".", $messages_all);
			$sts = "OK";
			if (in_array('E', $type) === true) {
				$sts = "NotOK";
			}
		}
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		exit();
	}

	public function loop_extend($I_LIFNR = NULL, $I_BUKRS = NULL, $I_EKORG = NULL, $I_KTOKK = NULL, $I_BUKRS_REF = NULL, $I_EKORG_REF = NULL, $id_data = NULL, $id_data_temp = NULL)
	{
		$this->dgeneral->begin_transaction();

		$datetime    	= date("Y-m-d H:i:s");
		$result = $this->data['sap']->callFunction(
			"Z_RFC_P2P_EXTEND_VENDOR",
			array(
				array("IMPORT", "I_LIFNR", $I_LIFNR),
				array("IMPORT", "I_BUKRS", $I_BUKRS),
				array("IMPORT", "I_EKORG", $I_EKORG),
				array("IMPORT", "I_KTOKK", $I_KTOKK),
				array("IMPORT", "I_BUKRS_REF", $I_BUKRS_REF),
				array("IMPORT", "I_EKORG_REF", $I_EKORG_REF),
				array("TABLE", "T_RETURN", array())
			)
		);
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
			$ck_sukses = 'y';
			//update tbl_vendor_data_temp
			$string = "
				update
				tbl_vendor_data_temp
				set 
				req='n',
				id_status='99'
				where 
				id_data_temp='$id_data_temp'
			";
			$query  = $this->db->query($string);
			//update tbl_vendor_plant
			$string = "
				update
				tbl_vendor_plant
				set 
				status_sap='y',
				status_delete='n'
				where id_data='$id_data' and plant='" . $I_EKORG . "' and status_sap='n'
			";
			$query  = $this->db->query($string);

			//log rfc
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
				'log_code'      => '',
				'log_status'    => 'Berhasil' . $I_EKORG,
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			$arr_msg[] = 'Extend Plant ' . $I_EKORG . ' Berhasil<br>';
		} else {        //jika gagal
			$data_row = array(
				'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_P2P_EXTEND_VENDOR',
				'log_code'      => $result['T_RETURN'][1]['TYPE'],
				'log_status'    => 'Gagal' . $I_EKORG,
				'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
				'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
				'executed_date' => $datetime
			);
			$this->dgeneral->insert('tbl_log_rfc', $data_row);
			// //jika gagal looping
			// $this->loop_extend($I_LIFNR, $I_BUKRS, $I_EKORG, $I_KTOKK, $I_BUKRS_REF, $I_EKORG_REF, $id_data, $id_data_temp);
			$arr_msg[] = 'Extend Plant ' . $I_EKORG . ' Gagal<br>';
		}
		//================================SAVE ALL================================//
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {
			//save log extend
			$data_row_log = array(
				"id_data_temp"	=> $id_data_temp,
				"id_status"	=> 99
			);
			$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
			$this->dgeneral->insert("tbl_vendor_data_temp_log", $data_row_log);

			$this->dgeneral->commit_transaction();
			$msg = "Extend Master Vendor SAP berhasil.";
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}
	}

	public function template_email_vendor($to=NULL, $subject=NULL, $content=NULL, $nama_penerima=NULL){
		setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'mail.kiranamegatara.com';
		$config['smtp_user'] = 'no-reply@kiranamegatara.com';
		$config['smtp_pass'] = '1234567890';
		$config['smtp_port'] = '465';
		$config['smtp_crypto'] = 'ssl';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = true;
		$config['mailtype'] = 'html'; 

		$this->load->library('email', $config);
		$this->email->from('no-reply@kiranamegatara.com', 'PT. KIRANAMEGATARA');
		$this->email->subject($subject);
		$this->email->to($to);

			
		$message = '<html>
		<body style=" background-color: #386d22">
		<center style="width: 100%;">
		<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
			&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
		</div>
		<div class="email-container" style="max-width: 800px; margin: 0 auto;">
			<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="min-width:600px;">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td style="color: #fff; padding:20px;" align="center">
						<h1 style="margin-bottom: 0;">Master Vendor</h1>
						<hr style="border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;"/>
						<h3 style="margin-top: 0;">KIRANAKU</h3>
					</td>
				</tr>
				<tr>
					<td>
						<table style="background-color: #ffffff; margin: auto; -webkit-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); -moz-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4);" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
							<tbody>
							<tr>
								<td style="padding: 20px;">
									<p><strong>Kepada Bapak/Ibu '.$nama_penerima.',</strong></p>
									<p>Berikut adalah pemberitahuan dari Master Vendor Kiranaku</p>
									<table role="presentation" border="0" width="100%" cellpadding="0" cellspacing="0">
										<tbody>
											<tr><td><strong>Konfirmasi '.$subject.', Mohon untuk ditindaklanjuti.</strong></td></tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td align="left" style="background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;">
									'.$content.'
								</td>
							</tr>
							<tr>
								<td align="left"
									style="background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;">
									<p>
										Harap segera ditindak lanjuti,<br/>
										Terima kasih atas perhatiannya.
									</p>
								</td>
							</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td style="color: #fff; padding-top:20px;" align="center">
						<small>Kiranaku Auto-MailSystem</small><br/>
						<strong style="color: #214014; font-size: 10px;">Terkirim pada '.date('d.m.Y H:i:s').'</strong>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
		</div>
		</center>
		</body>
		</html>
		';		
		// echo $message;		
		$this->email->message($message);
		$this->email->send();
		
		
	}

}
