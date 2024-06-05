<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KODE MATERIAL
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmastermaterial extends CI_Model{
	function get_data_nomor($conn = NULL, $id_item_group=NULL){
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		
		$this->db->select("right('0000'+convert(varchar(4), (count(*)+1)), 4) as nomor");
		$this->db->from('tbl_item_name');
		if ($id_item_group !== NULL) {
			$this->db->where('tbl_item_name.id_item_group', $id_item_group);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
		
	}
	
	function get_data_otoritas($conn = NULL, $id_posisi = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_setting_user.*');
		$this->db->from('tbl_item_setting_user');			
		$ho = base64_decode($this->session->userdata("-ho-"));
		if ($ho == 'y') {	
			$this->db->where("tbl_item_setting_user.tipe='Approver'");
			$this->db->where("tbl_item_setting_user.divisi like '%".base64_decode($this->session->userdata("-id_divisi-"))."%'");
			// $this->db->where("tbl_item_setting_user.seksi like '%".base64_decode($this->session->userdata("-id_seksi-"))."%'");
		}
		if ($ho == 'n') {	
			$this->db->where("tbl_item_setting_user.tipe='Requestor'");
			$this->db->where("tbl_item_setting_user.posisi like '%$id_posisi%'");
		}
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_profit($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_profit.*');
		$this->db->from('tbl_item_profit');
		$this->db->order_by("tbl_item_profit.prctr", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_div($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_div.*');
		$this->db->select('tbl_item_div.spart as kd');
		$this->db->select('tbl_item_div.vtext as nm');
		$this->db->from('tbl_item_div');
		$this->db->order_by("tbl_item_div.spart", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_dist($conn = NULL, $in_vtweg = NULL, $not_in_vtweg = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_dist.*');
		$this->db->select('tbl_item_dist.vtweg as kd');
		$this->db->select('tbl_item_dist.vtext as nm');
		$this->db->from('tbl_item_dist');
		if($in_vtweg != NULL){
			if(is_string($in_vtweg)) $in_vtweg = explode(",", $in_vtweg);
			foreach($in_vtweg as $val) {    
				$this->db->or_where("tbl_item_dist.vtweg='$val'");
			}			
		}
		if($not_in_vtweg != NULL){
			if(is_string($not_in_vtweg)) $not_in_vtweg = explode(",", $not_in_vtweg);
			foreach($not_in_vtweg as $val) {    
				$this->db->where("tbl_item_dist.vtweg!='$val'");
			}			
		}
		$this->db->order_by("tbl_item_dist.vtweg", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_lot($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_lot.*');
		$this->db->select('tbl_item_lot.disls as kd');
		$this->db->select('tbl_item_lot.loslt as nm');
		$this->db->from('tbl_item_lot');
		$this->db->order_by("tbl_item_lot.disls", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_dispo($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_dispo.dispo as kd');
		$this->db->select('tbl_item_dispo.dsnam as nm');
		$this->db->select('tbl_item_dispo.dispo');
		$this->db->select('tbl_item_dispo.dsnam');
		$this->db->from('tbl_item_dispo');
		$this->db->group_by(['dispo','dsnam']); 
		$this->db->order_by("tbl_item_dispo.dispo", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_lgort($conn = NULL, $plant = NULL, $lgort = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_lgort.lgort as kd');
		$this->db->select('tbl_item_lgort.lgobe as nm');
		$this->db->select('tbl_item_lgort.lgort');
		$this->db->select('tbl_item_lgort.lgobe');
		$this->db->from('tbl_item_lgort');
		if($plant != NULL){
			if(is_string($plant)) $plant = explode(",", $plant);
			$this->db->where_in('tbl_item_lgort.werks', $plant);
			// foreach($plant as $val) {    
				// $this->db->where('tbl_item_lgort.werks', $val);
			// }			
		}
		if($lgort != NULL){
			$this->db->where('tbl_item_lgort.lgort', $lgort);
		}
		
		$this->db->group_by(['lgort','lgobe']);
		$this->db->order_by("tbl_item_lgort.lgort", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_plant($conn = NULL, $plant = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		// $this->db->select('vw_tbl_acc_master_plant.*');
		// $this->db->from('vw_tbl_acc_master_plant');
		// if($plant != NULL){
			// if(is_string($plant)) $plant = explode(",", $plant);
			// foreach($plant as $val) {    
				// $this->db->where("vw_tbl_acc_master_plant.plant!='$val'");
			// }			
		// }
		// $this->db->where("vw_tbl_acc_master_plant.plant not in('NSI3')");
		// // $this->db->where("vw_tbl_acc_master_plant.id_plant is not null");
		// $this->db->order_by("vw_tbl_acc_master_plant.plant", "asc");
		
		$this->db->select('CONVERT(INT, ZDMMSPLANT.PERSA) as id_pabrik,
						  ZDMMSPLANT.PERSA as plant_code,
						  ZDMMSPLANT.WERKS as plant,
						  ZDMMSPLANT.NAME1 as plant_name,
						  ZDMMSPLANT.NAME1 as nama');
		$this->db->from('SAPSYNC.dbo.ZDMMSPLANT');
		if($plant != NULL){
			if(is_string($plant)) $plant = explode(",", $plant);
			foreach($plant as $val) {    
				$this->db->where("ZDMMSPLANT.WERKS!='$val'");
			}			
		}
		$this->db->order_by('SAPSYNC.dbo.ZDMMSPLANT.WERKS ASC');
		
															  
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_plant_($conn = NULL, $plant = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_wf_master_plant.*');
		$this->db->from('tbl_wf_master_plant');
		if($plant != NULL){
			if(is_string($plant)) $plant = explode(",", $plant);
			foreach($plant as $val) {    
				$this->db->where("tbl_wf_master_plant.plant!='$val'");
			}			
		}
		$this->db->order_by("tbl_wf_master_plant.plant", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_ekgrp($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_ekgrp.*');
		$this->db->select('tbl_item_ekgrp.ekgrp as kd');
		$this->db->select('tbl_item_ekgrp.eknam as nm');
		$this->db->from('tbl_item_ekgrp');
		$this->db->order_by("tbl_item_ekgrp.ekgrp", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_uom($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_uom.*');
		$this->db->select('tbl_item_uom.mseh3 as kd');
		$this->db->select('tbl_item_uom.msehl as nm');
		$this->db->from('tbl_item_uom');
		$this->db->order_by("tbl_item_uom.msehi", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_seksi($conn = NULL, $id_seksi = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_seksi.*');
		$this->db->from('tbl_seksi');
		$this->db->where("tbl_seksi.na='n'");
		$this->db->order_by("tbl_seksi.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_divisi($conn = NULL, $id_divisi = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_divisi.*');
		$this->db->from('tbl_divisi');
		$this->db->where("tbl_divisi.na='n'");
		$this->db->order_by("tbl_divisi.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_posisi($conn = NULL, $id_posisi = NULL, $nama = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_posisi.*');
		$this->db->from('tbl_posisi');
		$this->db->where("tbl_posisi.na='n'");
		if($nama !== NULL){
			$this->db->where("tbl_posisi.nama='$nama'");
		}
		
		$this->db->order_by("tbl_posisi.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_level($conn = NULL, $id_level = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_level.*');
		$this->db->from('tbl_level');
		$this->db->where("tbl_level.main='n'");
		$this->db->order_by("tbl_level.id_level", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_pic($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_pic.*');
		$this->db->from('tbl_item_master_pic');
		$this->db->order_by("tbl_item_master_pic.master_pic", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_mtart($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_mtart.*');
		$this->db->from('tbl_item_mtart');
		$this->db->where("SUBSTRING(mtart, 1, 1)='Z'");
		$this->db->order_by("tbl_item_mtart.mtart", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_kolom($conn = NULL, $kolom = NULL, $mtart = NULL, $classification = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_kolom.*');
		$this->db->select("(select tbl_item_master_matrix.required from tbl_item_master_matrix where tbl_item_master_matrix.kolom=tbl_item_master_kolom.kolom and tbl_item_master_matrix.mtart='$mtart' and classification='$classification') as req");
		$this->db->select("(select tbl_item_master_matrix.[default] from tbl_item_master_matrix where tbl_item_master_matrix.kolom=tbl_item_master_kolom.kolom and tbl_item_master_matrix.mtart='$mtart' and classification='$classification') as def");
		
		$this->db->from('tbl_item_master_kolom');
		if($kolom !== NULL){
			$this->db->where("tbl_item_master_kolom.kolom='$kolom'");
		}
		$this->db->where("tbl_item_master_kolom.kolom not in ('Item Group','Item Name','Material Code','Material Description')");
		$this->db->order_by("tbl_item_master_kolom.kolom", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_assign_group($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_assign_group.*');
		$this->db->select('tbl_item_master_assign_group.kode as kd');
		$this->db->select('tbl_item_master_assign_group.nama as nm');
		$this->db->from('tbl_item_master_assign_group');
		if($kode !== NULL){
			$this->db->where("tbl_item_master_assign_group.kode='$kode'");
		}
		
		$this->db->order_by("tbl_item_master_assign_group.kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_periode_indicator($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_periode_indicator.*');
		$this->db->select('tbl_item_master_periode_indicator.kode as kd');
		$this->db->select('tbl_item_master_periode_indicator.nama as nm');
		$this->db->from('tbl_item_master_periode_indicator');
		if($kode !== NULL){
			$this->db->where("tbl_item_master_periode_indicator.kode='$kode'");
		}
		
		$this->db->order_by("tbl_item_master_periode_indicator.kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_tax_class($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_tax_class.*');
		$this->db->select('tbl_item_master_tax_class.kode as kd');
		$this->db->select('tbl_item_master_tax_class.nama as nm');
		$this->db->from('tbl_item_master_tax_class');
		if($kode !== NULL){
			$this->db->where("tbl_item_master_tax_class.kode='$kode'");
		}
		
		$this->db->order_by("tbl_item_master_tax_class.kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_mrp_group($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_mrp_group.*');
		$this->db->select('tbl_item_master_mrp_group.kode as kd');
		$this->db->select('tbl_item_master_mrp_group.nama as nm');
		$this->db->from('tbl_item_master_mrp_group');
		if($kode !== NULL){
			$this->db->where("tbl_item_master_mrp_group.kode='$kode'");
		}
		
		$this->db->order_by("tbl_item_master_mrp_group.kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_mrp_type($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_mrp_type.*');
		$this->db->select('tbl_item_master_mrp_type.kode as kd');
		$this->db->select('tbl_item_master_mrp_type.nama as nm');
		$this->db->from('tbl_item_master_mrp_type');
		if($kode !== NULL){
			$this->db->where("tbl_item_master_mrp_type.kode='$kode'");
		}
		
		$this->db->order_by("tbl_item_master_mrp_type.kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_avail_check($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_avail_check.*');
		$this->db->select('tbl_item_master_avail_check.kode as kd');
		$this->db->select('tbl_item_master_avail_check.nama as nm');
		$this->db->from('tbl_item_master_avail_check');
		if($kode !== NULL){
			$this->db->where("tbl_item_master_avail_check.kode='$kode'");
		}
		
		$this->db->order_by("tbl_item_master_avail_check.kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_cat_group($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_cat_group.*');
		$this->db->select('tbl_item_master_cat_group.kode as kd');
		$this->db->select('tbl_item_master_cat_group.nama as nm');
		$this->db->from('tbl_item_master_cat_group');
		if($kode !== NULL){
			$this->db->where("tbl_item_master_cat_group.kode='$kode'");
		}
		
		$this->db->order_by("tbl_item_master_cat_group.kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_pricing_group($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_pricing_group.*');
		$this->db->select('tbl_item_master_pricing_group.kode as kd');
		$this->db->select('tbl_item_master_pricing_group.nama as nm');
		$this->db->from('tbl_item_master_pricing_group');
		if($kode !== NULL){
			$this->db->where("tbl_item_master_pricing_group.kode='$kode'");
		}
		
		$this->db->order_by("tbl_item_master_pricing_group.kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_statistic_group($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_statistic_group.*');
		$this->db->select('tbl_item_master_statistic_group.kode as kd');
		$this->db->select('tbl_item_master_statistic_group.nama as nm');
		$this->db->from('tbl_item_master_statistic_group');
		if($kode !== NULL){
			$this->db->where("tbl_item_master_statistic_group.kode='$kode'");
		}
		
		$this->db->order_by("tbl_item_master_statistic_group.kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_bklas($conn = NULL, $id_item_group = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_bklas.bklas, tbl_item_bklas.bkbez, tbl_item_bklas.kkref');
		$this->db->from('tbl_item_bklas');
		$this->db->join('tbl_item_mtart', 'tbl_item_bklas.kkref = tbl_item_mtart.kkref', 'left outer');	
		if($id_item_group !== NULL){
			$this->db->where("tbl_item_mtart.mtart=(select mtart from tbl_item_group where id_item_group='$id_item_group')");
		}
		$this->db->group_by(['tbl_item_bklas.bklas', 'tbl_item_bklas.bkbez', 'tbl_item_bklas.kkref']); 
		$this->db->order_by("tbl_item_bklas.bklas", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_matkl($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_matkl.*');
		$this->db->from('tbl_item_matkl');
		$this->db->order_by("tbl_item_matkl.matkl", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_group($conn = NULL, $id_item_group = NULL, $active = NULL, $deleted = 'n', $description = NULL, $mtart = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_group.id_item_group as kd');
		$this->db->select('tbl_item_group.description as nm');
		$this->db->select('tbl_item_group.*');
		$this->db->select('tbl_item_mtart.mtbez');
		$this->db->select("(select count(tbl_item_name.id_item_name) from tbl_item_name where tbl_item_name.id_item_group=tbl_item_group.id_item_group and tbl_item_name.na='n') as jumlah");
		$this->db->select('CASE
								WHEN tbl_item_group.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->join('tbl_item_mtart', 'tbl_item_mtart.mtart = tbl_item_group.mtart', 'left outer');						   
		$this->db->from('tbl_item_group');
		if ($id_item_group !== NULL) {
			$this->db->where('tbl_item_group.id_item_group', $id_item_group);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_group.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_group.del', $deleted);
		}
		if ($description !== NULL) {
			
			$this->db->where('tbl_item_group.description', LTRIM(RTRIM($description)));
		}
		if ($mtart !== NULL) {
			$this->db->where('tbl_item_group.mtart', $mtart);
		}
		$this->db->order_by("tbl_item_group.description", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_item($conn = NULL, $id_item_name = NULL, $active = NULL, $deleted = 'n', $description = NULL, $id_item_group = NULL, $bklas = NULL, $matkl = NULL, $classification = NULL, $id_item_group_filter = NULL, $filter_request_status = NULL, $req = NULL, $code = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_name.*');
		$this->db->select('tbl_item_name.id_item_name as kd');
		$this->db->select('tbl_item_name.description as nm');
		$this->db->select('tbl_item_bklas.bkbez');
		$this->db->select('tbl_item_matkl.wgbez');
		$this->db->select('tbl_item_group.id_item_group');
		$this->db->select('tbl_item_group.mtart');
		$this->db->select('tbl_item_mtart.mtbez');
		$this->db->select('tbl_item_group.description as group_description');
		$this->db->select("(select count(tbl_item_spec.id_item_spec) from tbl_item_spec where tbl_item_spec.id_item_name=tbl_item_name.id_item_name and tbl_item_spec.na='n') as jumlah");
		$this->db->select('CASE
								WHEN tbl_item_name.classification = \'A\' THEN \'Asset\'
								WHEN tbl_item_name.classification = \'E\' THEN \'Expense\'
								WHEN tbl_item_name.classification = \'I\' THEN \'Inventory\'
								ELSE \'Inventory Expense\'
						   END as classification_name');
		$this->db->select('CASE
								WHEN tbl_item_name.price_control = \'S\' THEN \'Standard Price\'
								ELSE \'Moving Price\'
						   END as price_control_name');
		$this->db->select('CASE
								WHEN tbl_item_name.req = \'n\' THEN \'<span class="label label-success">Completed</span>\'
								ELSE \'<span class="label label-warning">Requested</span>\'
						   END as label_req');
		$this->db->select('CASE
								WHEN tbl_item_name.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('CASE
								WHEN tbl_item_name.jasa = \'y\' THEN \'Ya\'
								WHEN tbl_item_name.jasa = \'n\' THEN \'Tidak\'
								ELSE \'-\'
						   END as label_jasa');
		$this->db->join('tbl_item_group', 'tbl_item_group.id_item_group = tbl_item_name.id_item_group', 'left outer');						   
		$this->db->join('tbl_item_mtart', 'tbl_item_mtart.mtart = tbl_item_group.mtart', 'left outer');						   
		$this->db->join('tbl_item_bklas', 'tbl_item_bklas.bklas = tbl_item_name.bklas', 'left outer');						   
		$this->db->join('tbl_item_matkl', 'tbl_item_matkl.matkl = tbl_item_name.matkl', 'left outer');						   
		$this->db->from('tbl_item_name');
		if ($id_item_name !== NULL) {
			$this->db->where('tbl_item_name.id_item_name', $id_item_name);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_name.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_name.del', $deleted);
		}
		if ($description !== NULL) {
			$this->db->where('tbl_item_name.description', LTRIM(RTRIM($description)));
		}
		if ($id_item_group !== NULL) {
			$this->db->where('tbl_item_group.id_item_group', $id_item_group);
		}
		if ($bklas !== NULL) {
			$this->db->where('tbl_item_name.bklas', $bklas);
		}
		if ($matkl !== NULL) {
			$this->db->where('tbl_item_name.matkl', $matkl);
		}
		if ($classification !== NULL) {
			$this->db->where('tbl_item_name.classification', $classification);
		}
		if($id_item_group_filter != NULL){
			if(is_string($id_item_group_filter)) $id_item_group_filter = explode(",", $id_item_group_filter);
			$this->db->where_in('tbl_item_name.id_item_group', $id_item_group_filter);
		}
		if($filter_request_status != NULL){
			if(is_string($filter_request_status)) $filter_request_status = explode(",", $filter_request_status);
			$this->db->where_in('tbl_item_name.req', $filter_request_status);
		}
		if ($req !== NULL) {
			$this->db->where('tbl_item_name.req', $req);
			$this->db->where("(tbl_item_name.price_control='S' or tbl_item_name.price_control='V')");
		}
		if ($code !== NULL) {
			$this->db->where('tbl_item_name.code', $code);
		}
		$this->db->order_by("tbl_item_name.description", "asc");
		
		$query  = $this->db->get();
		$result = $query->result();
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_matrix($conn = NULL, $id_item_master_matrix = NULL, $active = NULL, $deleted = 'n', $kolom = NULL, $mtart = NULL, $classification = NULL, $default = NULL, $required = NULL, $filter_mtart = NULL, $filter_class = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_matrix.*');
		$this->db->select('tbl_item_master_matrix.default as def');
		$this->db->select('tbl_item_master_matrix.required as requir');
		$this->db->select('tbl_item_mtart.mtbez');
		// $this->db->select('tbl_item_master_kolom.jenis');
		$this->db->select('tbl_item_master_kolom.tabel_sap');
		$this->db->select('CASE
								WHEN tbl_item_master_matrix.classification = \'A\' THEN \'Asset\'
								WHEN tbl_item_master_matrix.classification = \'E\' THEN \'Expense\'
								WHEN tbl_item_master_matrix.classification = \'I\' THEN \'Inventory\'
								ELSE \'Inventory Expense\'
						   END as classification_name');
		$this->db->join('tbl_item_master_kolom', 'tbl_item_master_kolom.kolom = tbl_item_master_matrix.kolom', 'left outer');						   
		$this->db->join('tbl_item_mtart', 'tbl_item_mtart.mtart = tbl_item_master_matrix.mtart', 'left outer');						   
		$this->db->from('tbl_item_master_matrix');
		if ($id_item_master_matrix !== NULL) {
			$this->db->where('tbl_item_master_matrix.id_item_master_matrix', $id_item_master_matrix);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_master_matrix.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_master_matrix.del', $deleted);
		}
		if ($kolom !== NULL) {
			$this->db->where('tbl_item_master_matrix.kolom', $kolom);
		}else{
			$this->db->where('tbl_item_master_matrix.kolom', 'Acct Assignment Group');	//set default
		}
		if ($mtart !== NULL) {
			$this->db->where('tbl_item_master_matrix.mtart', $mtart);
		}
		if ($classification !== NULL) {
			$this->db->where('tbl_item_master_matrix.classification', $classification);
		}
		if ($default !== NULL) {
			$this->db->where('tbl_item_master_matrix.default', $default);
		}
		if ($required !== NULL) {
			$this->db->where('tbl_item_master_matrix.required', $required);
		}
		if($filter_mtart != NULL){
			if(is_string($filter_mtart)) $filter_mtart = explode(",", $filter_mtart);
			$this->db->where_in('tbl_item_master_matrix.mtart', $filter_mtart);
		}
		if($filter_class != NULL){
			if(is_string($filter_class)) $filter_class = explode(",", $filter_class);
			$this->db->where_in('tbl_item_master_matrix.classification', $filter_class);
		}
		
		// $this->db->limit(10);
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_role($conn = NULL, $id_item_setting_user = NULL, $active = NULL, $deleted = 'n', $id_item_master_pic = NULL, $tipe = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_item_master_pic.master_pic');
		$this->db->select('tbl_item_setting_user.*');
		$this->db->select('CASE
								WHEN tbl_item_setting_user.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select("CAST( 
							( 
							  SELECT tbl_posisi.nama + RTRIM(',') 
								FROM tbl_posisi 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_posisi.id_posisi)+'''',''''+REPLACE(tbl_item_setting_user.posisi, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_posisi");
		$this->db->select("CAST( 
							( 
							  SELECT tbl_divisi.nama + RTRIM(',') 
								FROM tbl_divisi 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_divisi.id_divisi)+'''',''''+REPLACE(tbl_item_setting_user.divisi, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_divisi");
		$this->db->select("CAST( 
							( 
							  SELECT tbl_seksi.nama + RTRIM(',') 
								FROM tbl_seksi 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_seksi.id_seksi)+'''',''''+REPLACE(tbl_item_setting_user.seksi, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_seksi");
		$this->db->from('tbl_item_setting_user');
		$this->db->join('tbl_item_master_pic', 'tbl_item_master_pic.id_item_master_pic = tbl_item_setting_user.id_item_master_pic', 'left outer');		
		if ($id_item_setting_user !== NULL) {
			$this->db->where('tbl_item_setting_user.id_item_setting_user', $id_item_setting_user);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_item_setting_user.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_item_setting_user.del', $deleted);
		}
		if ($id_item_master_pic !== NULL) {
			$this->db->where('tbl_item_setting_user.id_item_master_pic', $id_item_master_pic);
		}
		if ($tipe !== NULL) {
			$this->db->where('tbl_item_setting_user.tipe', $tipe);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
}
?>