<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KODE MATERIAL
@author       : Lukman Hakim (7143)
@contributor  : 
      1. Airiza Yuddha (7849) 14 okt 2020
         modified function get_data_request - add field tanggal_conf & spec_desc         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dtransaksimaterial extends CI_Model{
	
	function get_data_nomor($conn = NULL, $id_item_group=NULL, $id_item_name=NULL){
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		// $this->db->select("'$id_item_group-'+(select tbl_item_name.code from tbl_item_name where tbl_item_name.id_item_name='$id_item_name')+'-'+right('0000'+convert(varchar(4), (count(*)+1)), 4) as nomor");
		// $this->db->select("(select tbl_item_name.code from tbl_item_name where tbl_item_name.id_item_name='$id_item_name') as code");
		$this->db->select("
							'$id_item_group'+
							'-'+(select tbl_item_name.code from tbl_item_name where tbl_item_name.id_item_name='$id_item_name')+
							'-'+
								CASE
									WHEN count(*)=0 
									THEN '0001'
									ELSE right('0000'+convert(varchar(4), (select top 1 substring(tbl_item_spec2.code, 10, 4)+1 from tbl_item_spec as tbl_item_spec2 where tbl_item_spec2.id_item_group='$id_item_group' and tbl_item_spec2.id_item_name='$id_item_name' order by tbl_item_spec2.code desc)), 4)
								END
							as nomor");
		$this->db->from('tbl_item_spec');
		if ($id_item_group !== NULL) {
			$this->db->where('tbl_item_spec.id_item_group', $id_item_group);
		}
		if ($id_item_name !== NULL) {
			$this->db->where('tbl_item_spec.id_item_name', $id_item_name);
		}
		$query  = $this->db->get();
		$result = $query->row();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
		
	}
	// function get_data_spec_bom($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n', $id_item_group = NULL, $id_item_name = NULL, $status = NULL, $filter_request_status = NULL) {
		// if ($conn !== NULL)
			// $this->general->connectDbPortal();
		// $this->datatables->select('tbl_item_spec.id_item_spec');
		// $this->datatables->select('tbl_item_spec.id_item_group');
		// $this->datatables->select('tbl_item_spec.id_item_name');
		// $this->datatables->select('tbl_item_spec.code');
		// $this->datatables->select('tbl_item_spec.description');
		// $this->datatables->select("tbl_item_name.description+' '+tbl_item_spec.description as description_detail");
		// $this->datatables->select('tbl_item_spec.purchase_type');
		// $this->datatables->select('tbl_item_spec.purchase_authorization');
		// $this->datatables->select('tbl_item_spec.beli_di_nsi2');
		// $this->datatables->select('tbl_item_spec.specification_check');
		// $this->datatables->select('tbl_item_spec.req');
		// $this->datatables->select('tbl_item_spec.na');
		// $this->datatables->select('tbl_item_group.description as group_description');
		// $this->datatables->select('tbl_item_group.mtart as group_mtart');
		// $this->datatables->select('tbl_item_name.code as name_code');
		// $this->datatables->select('tbl_item_name.description as name_description');
		// $this->datatables->from('tbl_item_spec');				
		// $this->datatables->join('tbl_item_group', 'tbl_item_group.id_item_group = tbl_item_spec.id_item_group', 'left outer');		
		// $this->datatables->join('tbl_item_name', 'tbl_item_name.id_item_name = tbl_item_spec.id_item_name', 'left outer');		
		// if ($id_item_spec !== NULL) {
			// $this->datatables->where('tbl_item_spec.id_item_spec', $id_item_spec);
		// }
		// if ($active !== NULL) {
			// $this->datatables->where('tbl_item_spec.na', $active);
		// }
		// if ($deleted !== NULL) {
			// $this->datatables->where('tbl_item_spec.del', $deleted);
		// }
		// if($id_item_group != NULL){
			// if(is_string($id_item_group)) $id_item_group = explode(",", $id_item_group);
			// $this->datatables->where_in('tbl_item_spec.id_item_group', $id_item_group);
		// }
		// if($id_item_name != NULL){
			// if(is_string($id_item_name)) $id_item_name = explode(",", $id_item_name);
			// $this->datatables->where_in('tbl_item_spec.id_item_name', $id_item_name);
		// }
		// if($status != NULL){
			// if(is_string($status)) $status = explode(",", $status);
			// $this->datatables->where_in('tbl_item_spec.na', $status);
		// }
		// if($filter_request_status != NULL){
			// if(is_string($filter_request_status)) $filter_request_status = explode(",", $filter_request_status);
			// $this->datatables->where_in('tbl_item_spec.req', $filter_request_status);
		// }
		// if ($conn !== NULL)
			// $this->general->closeDb();

		// $return = $this->datatables->generate();
		// $raw = json_decode($return, true);
		// // $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
		// return $this->general->jsonify($raw);
	// }
	
	function get_data_spec_bom($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n', $id_item_group = NULL, $id_item_name = NULL, $status = NULL, $filter_request_status = NULL, $filter_classification = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->datatables->select(' id_item_spec,
									id_item_request,
									id_item_group,
									id_item_name,
									classification,
									code,
									description,
									plant,
									plant_extend,
									lgort,
									msehi_uom,
									msehi_order,
									old_material_number,
									ekgrp,
									availability_check,
									mrp_group,
									mrp_type,
									dispo,
									service_level,
									disls,
									period_indicator,
									sales_plant,
									vtweg,
									spart,
									net_weight,
									gross_weight,
									gen_item_cat_group,
									material_pricing_group,
									material_statistic_group,
									acct_assignment_group,
									taxm1,
									login_buat,
									tanggal_buat,
									login_edit,
									tanggal_edit,
									purchase_type,
									purchase_authorization,
									beli_di_nsi2,
									specification_check,
									xchpf,
									req,
									na,
									del,
									detail,
									umrez,
									prmod,
									peran,
									anzpr,
									kzini,
									siggr,
									description_detail,
									group_description,
									group_mtart,
									name_code,
									name_description,
									label_classification');
		$this->datatables->from('vw_material_spec');				
		if ($id_item_spec !== NULL) {
			$this->datatables->where('id_item_spec', $id_item_spec);
		}
		if ($active !== NULL) {
			$this->datatables->where('na', $active);
		}
		if ($deleted !== NULL) {
			$this->datatables->where('del', $deleted);
		}
		if($id_item_group != NULL){
			if(is_string($id_item_group)) $id_item_group = explode(",", $id_item_group);
			$this->datatables->where_in('id_item_group', $id_item_group);
		}
		if($id_item_name != NULL){
			if(is_string($id_item_name)) $id_item_name = explode(",", $id_item_name);
			$this->datatables->where_in('id_item_name', $id_item_name);
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->datatables->where_in('tbl_item_spec.na', $status);
		}
		if($filter_request_status != NULL){
			if(is_string($filter_request_status)) $filter_request_status = explode(",", $filter_request_status);
			$this->datatables->where_in('req', $filter_request_status);
		}
		if($filter_classification != NULL){
			if(is_string($filter_classification)) $filter_classification = explode(",", $filter_classification);
			$this->datatables->where_in('classification', $filter_classification);
		}
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
		return json_encode($raw);
		// return $this->general->jsonify($raw);
	}
	
	function get_data_spec_bom_test($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n', $id_item_group = NULL, $id_item_name = NULL, $status = NULL, $filter_request_status = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->datatables->select('tbl_karyawan.nama as id_item_spec');
		$this->datatables->select('tbl_karyawan.nama as id_item_group');
		$this->datatables->select('tbl_karyawan.nama as group_description');
		$this->datatables->select('tbl_karyawan.nama as name_description');
		$this->datatables->select('tbl_karyawan.nama as code');
		$this->datatables->select('tbl_karyawan.nama as description');
		$this->datatables->select('tbl_karyawan.nama as req');
		$this->datatables->from('tbl_karyawan');				
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_item_spec"));
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("nama"));
		return $this->general->jsonify($raw);
	}
	
	function get_data_spec_xx($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.id_karyawan as id_item_group');
		$this->db->select('tbl_karyawan.id_karyawan as id_item_spec');
		$this->db->select('tbl_karyawan.nama as description');
		$this->db->from('tbl_karyawan');						   
		$this->db->limit(1000);  
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_spec($conn = NULL, $id_item_spec = NULL, $active = NULL, $deleted = 'n', $description = NULL, $req = NULL, $id_item_name = NULL, $code = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_spec.*');
		$this->db->select("(select right('0000'+convert(varchar(4), (count(*)+1)), 4) from tbl_item_spec as tbl_item_spec2 where tbl_item_spec2.id_item_group=tbl_item_spec.id_item_group and tbl_item_spec2.id_item_name=tbl_item_spec.id_item_name) as nomor");
		$this->db->select('tbl_item_spec.id_item_spec as id');
		$this->db->select('tbl_item_group.id_item_group');
		$this->db->select('tbl_item_group.mtart');
		$this->db->select('tbl_item_group.description as group_description');
		$this->db->select('tbl_item_name.classification');
		$this->db->select('tbl_item_name.id_item_name');
		$this->db->select('tbl_item_name.code as code_item_name');
		$this->db->select('tbl_item_name.description as name_description');
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR(MAX), ISNULL(tbl_item_gambar.file_location,0))+RTRIM('|')
					            FROM tbl_item_gambar
								WHERE tbl_item_gambar.id_item_spec = tbl_item_spec.id_item_spec
								and tbl_item_gambar.na='n'
								ORDER BY tbl_item_gambar.id_item_gambar
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_gambar,
						  ");
		$this->db->select('CASE
								WHEN tbl_item_spec.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_item_spec');						   
		$this->db->join('tbl_item_name', 'tbl_item_name.id_item_name = tbl_item_spec.id_item_name', 'left outer');						   
		$this->db->join('tbl_item_group', 'tbl_item_group.id_item_group = tbl_item_name.id_item_group', 'left outer');						   
		if ($id_item_spec !== NULL) {
			$this->db->where('tbl_item_spec.id_item_spec', $id_item_spec);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_spec.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_spec.del', $deleted);
		}
		if ($description !== NULL) {
			$this->db->where("(tbl_item_spec.code like '%$description%')or(tbl_item_spec.description like '%$description%')or(tbl_item_name.description like '%$description%')or(tbl_item_group.description like '%$description%')");
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_spec.del', $deleted);
		}
		if ($req !== NULL) {
			$this->db->where("tbl_item_spec.req", $req);
			$this->db->where("tbl_item_spec.purchase_type is not null");
			$this->db->where("tbl_item_spec.purchase_authorization is not null");
			// $this->db->where("tbl_item_spec.beli_di_nsi2 is not null");
			// $this->db->where("tbl_item_spec.specification_check is not null");
		}
		if ($id_item_name !== NULL) {
			$this->db->where('tbl_item_spec.id_item_name', $id_item_name);
		}
		if ($code !== NULL) {
			$this->db->where('tbl_item_spec.code', $code);
		}
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_plant($conn = NULL, $active = NULL, $deleted = 'n', $id_item_spec = NULL, $status_sap = NULL, $plant = NULL, $vtweg	 = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('DISTINCT(tbl_item_plant.plant)');
		$this->db->select('CASE
								WHEN tbl_item_plant.status_sap = \'y\' THEN \'Transferred\'
								ELSE \'Pending\'
						   END as label_sap');
		$this->db->select('CASE
								WHEN (select tbl_item_mmrc.MMSTA from tbl_item_mmrc where tbl_item_mmrc.matnr=tbl_item_spec.code and tbl_item_mmrc.werks=tbl_item_plant.plant) = \'X\' 
								THEN \'<span class="label label-success">v</span>\'
								ELSE \'\'
						   END as label_block');
		$this->db->select('CASE
								WHEN (select tbl_item_mmrc.LVORM from tbl_item_mmrc where tbl_item_mmrc.matnr=tbl_item_spec.code and tbl_item_mmrc.werks=tbl_item_plant.plant) = \'X\' 
								THEN \'<span class="label label-success">v</span>\'
								ELSE \'\'
						   END as label_del');
		$this->db->from('tbl_item_plant');		
		$this->db->join('tbl_item_spec', 'tbl_item_spec.id_item_spec = tbl_item_plant.id_item_spec', 'left outer');		
		if ($id_item_spec !== NULL) {
			$this->db->where('tbl_item_plant.id_item_spec', $id_item_spec);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_request.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_request.del', $deleted);
		}
		if ($status_sap !== NULL) {
			$this->db->where('tbl_item_plant.status_sap', $status_sap);
		}
		if ($plant !== NULL) {
			$this->db->where('tbl_item_plant.plant', $plant);
		}
		if ($vtweg !== NULL) {
			$this->db->where('tbl_item_plant.vtweg', $vtweg);
		}
		$this->db->where('tbl_item_plant.na', 'n');
		$this->db->where('tbl_item_plant.del', 'n');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_history($conn = NULL, $active = NULL, $deleted = 'n', $id_item_spec = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_spec_log.*');
		$this->db->select("CONVERT(VARCHAR(10), tbl_item_spec_log.tanggal_buat, 104) + ' ' + CONVERT(VARCHAR(8), tbl_item_spec_log.tanggal_buat, 108) as tanggal");
		$this->db->select('tbl_karyawan.nama as nama_user');
		$this->db->from('tbl_item_spec_log');		
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_item_spec_log.login_buat', 'left outer');		
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');		
		if ($id_item_spec !== NULL) {
			$this->db->where('tbl_item_spec_log.id_item_spec', $id_item_spec);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_request($conn = NULL, $id_item_request = NULL, $active = NULL, $deleted = 'n', $all = NULL, $req = NULL, $filter_from = NULL, $filter_to = NULL, $filter_request_status = NULL, $filter_status = NULL, $confirm = NULL, $pic = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_request.*');
		$this->db->select('tbl_item_spec.code as code_spec');
		$this->db->select('tbl_karyawan.gsber');
		$this->db->select('CONVERT(varchar(11),tbl_item_request.tanggal_buat,104) as tanggal');
		$this->db->select('CONVERT(varchar(11),tbl_item_request.tanggal_edit,104) as tanggal_conf');
		$this->db->select('tbl_item_spec.detail spec_desc');
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR(MAX), ISNULL(tbl_item_gambar.file_location,0))+RTRIM('|')
					            FROM tbl_item_gambar
								WHERE tbl_item_gambar.id_item_request = tbl_item_request.id_item_request
								and tbl_item_gambar.na='n'
								ORDER BY tbl_item_gambar.id_item_gambar
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_gambar,
						  ");
		$this->db->select('CASE 
								WHEN tbl_item_request.req = \'o\' THEN \'<span class="label label-default">Pending Request</span>\'
								WHEN tbl_item_request.req = \'y\' THEN \'<span class="label label-warning">Requested</span>\'
								WHEN tbl_item_request.req = \'x\' THEN \'<span class="label label-danger">Declined</span><br>\'+tbl_item_request.alasan
								ELSE \'<span class="label label-success">Completed</span>\'
						   END as label_request');
		$this->db->select('CASE
								WHEN tbl_item_request.req = \'o\' THEN \'Pending Request\'
								WHEN tbl_item_request.req = \'y\' THEN \'Requested\'
								WHEN tbl_item_request.req = \'x\' THEN \'Declined\'
								ELSE \'Completed\'
						   END as excel_request');
		$this->db->select('CASE
								WHEN tbl_item_request.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_status');
		$this->db->select('CASE
								WHEN tbl_item_request.na = \'n\' THEN \'AKTIF\'
								ELSE \'NON AKTIF\'
						   END as excel_status');
		$this->db->select('CASE
								WHEN tbl_item_request.req = \'o\' THEN (select tbl_karyawan.nik from tbl_karyawan left outer join tbl_user on tbl_user.id_karyawan=tbl_karyawan.id_karyawan where tbl_user.id_user=tbl_item_request.login_buat)
								WHEN tbl_item_request.req = \'y\' THEN (select tbl_karyawan.nik from tbl_karyawan left outer join tbl_user on tbl_user.id_karyawan=tbl_karyawan.id_karyawan where tbl_user.id_user=tbl_item_request.login_buat)
								ELSE (select tbl_karyawan.nik from tbl_karyawan left outer join tbl_user on tbl_user.id_karyawan=tbl_karyawan.id_karyawan where tbl_user.id_user=tbl_item_request.login_edit)
						   END as nik_pic');
		$this->db->select('CASE
								WHEN tbl_item_request.req = \'o\' THEN (select tbl_karyawan.nama from tbl_karyawan left outer join tbl_user on tbl_user.id_karyawan=tbl_karyawan.id_karyawan where tbl_user.id_user=tbl_item_request.login_buat)
								WHEN tbl_item_request.req = \'y\' THEN (select tbl_karyawan.nama from tbl_karyawan left outer join tbl_user on tbl_user.id_karyawan=tbl_karyawan.id_karyawan where tbl_user.id_user=tbl_item_request.login_buat)
								ELSE (select tbl_karyawan.nama from tbl_karyawan left outer join tbl_user on tbl_user.id_karyawan=tbl_karyawan.id_karyawan where tbl_user.id_user=tbl_item_request.login_edit)
						   END as nama_pic');
		$this->db->select('CONVERT(varchar(5),tbl_item_request.tanggal_buat,108) as jam_buat');				   
		$this->db->select('CONVERT(varchar(5),tbl_item_request.tanggal_edit,108) as jam_conf');			   
		$this->db->select('tbl_item_name.classification');
		$this->db->select('CASE 
								WHEN tbl_item_name.classification = \'A\' THEN \'<p style="color:blue; font-weight: bold;">Asset</span>\'
								WHEN tbl_item_name.classification = \'E\' THEN \'<p style="color:red;  font-weight: bold;">Expense</span>\'
								WHEN tbl_item_name.classification = \'IE\' THEN \'<p style="color:red; font-weight: bold;">Expense</span>\'
								ELSE \'<p style="color:orange; font-weight: bold;">Inventory</span>\'
						   END as label_classification');
		// $this->db->select("(select top 1 replace(ZDMMSMATNR.MAKTX,'\"',' &quot;') from SAPSYNC.dbo.ZDMMSMATNR where ZDMMSMATNR.MATNR COLLATE SQL_Latin1_General_CP1_CI_AS = tbl_item_request.code) as spec_desc_sap");
		$this->db->select('tbl_item_request.description_sap as spec_desc_sap');
		
		// $this->db->select("select id_item_master_pic from tbl_item_setting_user where ");				   
		$this->db->from('tbl_item_request');			
		$this->db->join('tbl_item_spec', 'tbl_item_spec.id_item_spec = tbl_item_request.id_item_spec', 'left outer');
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_item_request.login_buat', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		$this->db->join('tbl_item_name', 'tbl_item_name.id_item_name = tbl_item_spec.id_item_name', 'left outer');

		if ($id_item_request !== NULL) {
			$this->db->where('tbl_item_request.id_item_request', $id_item_request);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_request.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_request.del', $deleted);
		}
		if ($all == NULL) {	
			$this->db->where('tbl_item_request.login_buat', base64_decode($this->session->userdata("-id_user-")));
		}
		if ($req !== NULL) {	
			$this->db->where('tbl_item_request.req', $req);
		} 
		if (($filter_from !== NULL)and($filter_to !== NULL)) {	
		
			$this->db->where("CONVERT(date,tbl_item_request.tanggal_buat) between '".$this->generate->regenerateDateFormat($filter_from)."' and '".$this->generate->regenerateDateFormat($filter_to)."'");
		}
		if($filter_request_status != NULL){
			if(is_string($filter_request_status)) $filter_request_status = explode(",", $filter_request_status);
			$this->db->where_in('tbl_item_request.req', $filter_request_status);
		}
		if($filter_status != NULL){
			if(is_string($filter_status)) $filter_status = explode(",", $filter_status);
			$this->db->where_in('tbl_item_request.na', $filter_status);
		}
		if($filter_status == NULL){
			$this->db->where('tbl_item_request.na', 'n');
		}
		if ($confirm !== NULL) {	
			$this->db->where("tbl_item_request.id_item_spec !='0'");
		}
		if ($pic !== NULL) {	
			$this->db->where("tbl_item_request.id_item_master_pic",$pic);
		}
		if (base64_decode($this->session->userdata("-ho-")) == 'y') {	
			$this->db->where("tbl_item_request.req in('x','y','n')");
		}
		// $this->db->where("1=1");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_inputxx($conn = NULL, $id_item_request = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_request.*');
		$this->db->select('CONVERT(varchar(11),tbl_item_request.tanggal_buat,104) as tanggal');
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR(MAX), ISNULL(tbl_item_gambar.file_location,0))+RTRIM('|')
					            FROM tbl_item_gambar
								WHERE tbl_item_gambar.id_item_request = tbl_item_request.id_item_request
								and tbl_item_gambar.na='n'
								ORDER BY tbl_item_gambar.id_item_gambar
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_gambar,
						  ");
		$this->db->select('CASE
								WHEN tbl_item_request.req = \'o\' THEN \'<span class="label label-default">Pending Request</span>\'
								WHEN tbl_item_request.req = \'y\' THEN \'<span class="label label-warning">Requested</span>\'
								ELSE \'<span class="label label-success">Completed</span>\'
						   END as label_request');
		$this->db->select('CASE
								WHEN tbl_item_request.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_status');
		$this->db->from('tbl_item_request');						   
		if ($id_item_request !== NULL) {
			$this->db->where('tbl_item_request.id_item_request', $id_item_request);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_request.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_request.del', $deleted);
		}
		$this->db->where('tbl_item_request.req', 'y');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_cek($conn = NULL, $tabel = NULL, $field = NULL, $value = NULL, $field2 = NULL, $value2 = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select($tabel.'.*');
		$this->db->from($tabel);
		if (($field !== NULL) and ($value !== NULL)){
			$this->db->where($tabel.'.'.$field, $value);
		}
		if (($field2 !== NULL) and ($value2 !== NULL)){
			$this->db->where($tabel.'.'.$field2, $value2);
		}
		$this->db->where($tabel.'.del', 'n');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_ZDMMSMATNR($conn = NULL, $matnr = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('ZDMMSMATNR.*');
		$this->db->from("SAPSYNC.dbo.ZDMMSMATNR");
		if ($matnr !== NULL) {
			$this->db->where('ZDMMSMATNR.matnr', $matnr);
		}
		
		$query  = $this->db->get();
		$result = $query->result();
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_extend_sales($param = NULL){
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		// $this->db->select('vw_material_spec_byplant.plant as pabrik');
		$this->db->select('tbl_item_plant.plant as pabrik');
		$this->db->select('vw_material_spec.*');
		$this->db->from('vw_material_spec');
		// $this->db->join('vw_material_spec_byplant', 'vw_material_spec.code = vw_material_spec_byplant.id', 'left');
		$this->db->join("tbl_item_plant",
			"tbl_item_plant.id_item_spec = vw_material_spec.id_item_spec AND tbl_item_plant.status_sap = 'y' AND tbl_item_plant.na = 'n'",
			"left"
		);


		if (isset($param['id_item_spec']) && $param['id_item_spec'] !== NULL)
            $this->db->where('vw_material_spec.id_item_spec', $param['id_item_spec']);
		
		
		$query = $this->db->get();
		if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
			$result = $query->row();
		else $result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}

	
}
?>