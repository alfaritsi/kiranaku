<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application  : Kirana Event
		@author       : Airiza Yuddha (7849)
		@contributor  :
			  1. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
    */

	class Dmasterkiranaevent extends CI_Model {
		/*================================Hubungan Kirana Event====================================*/
		function get_master_relationship($conn = NULL, $id_relationship = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $exceptioncheck = NULL, $relationcheck1 = NULL, $relationcheck2 = NULL) {

			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_eventhr_mst_hubungan.*');			
			$this->db->select('CASE
								WHEN tbl_eventhr_mst_hubungan.na = \'n\' AND tbl_eventhr_mst_hubungan.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
								WHEN tbl_eventhr_mst_hubungan.na = \'y\' AND tbl_eventhr_mst_hubungan.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
						   		WHEN tbl_eventhr_mst_hubungan.na = \'y\' AND tbl_eventhr_mst_hubungan.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
						   		ELSE \'<span class="label label-danger"></span>\'
							   END as label_active');
			
			$this->db->from('tbl_eventhr_mst_hubungan');
			if($typecheck !== NULL){
	        	if($typecheck=="in"){
	        		$this->db->where(" tbl_eventhr_mst_hubungan.hubungan ",$relationcheck1);
	        	} else if($typecheck=="up"){
	        		$this->db->where(" tbl_eventhr_mst_hubungan.hubungan ",$relationcheck1);	        		
	        		$this->db->where_not_in(" tbl_eventhr_mst_hubungan.id_hubungan ",$exceptioncheck);
	        	}
	        } else {
	        	if ($id_relationship !== NULL) {
					$this->db->where('tbl_eventhr_mst_hubungan.id_hubungan', $id_relationship);
				}
				if($active !== NULL){
					$this->db->where_in('tbl_eventhr_mst_hubungan.na', $active);											
				}			
			}
			
			$this->db->where('tbl_eventhr_mst_hubungan.del ', 'n');
			$this->db->order_by('tbl_eventhr_mst_hubungan.hubungan ASC ');
			
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*================================Type Kirana Event====================================*/
		function get_master_type($conn = NULL, $id_type = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $exceptioncheck = NULL, $typeberitacheck1 = NULL, $typeberitacheck2 = NULL) {

			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_eventhr_mst_typeberita.*');			
			$this->db->select('CASE
								WHEN tbl_eventhr_mst_typeberita.na = \'n\' AND tbl_eventhr_mst_typeberita.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
								WHEN tbl_eventhr_mst_typeberita.na = \'y\' AND tbl_eventhr_mst_typeberita.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
						   		WHEN tbl_eventhr_mst_typeberita.na = \'y\' AND tbl_eventhr_mst_typeberita.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
						   		ELSE \'<span class="label label-danger"></span>\'
							   END as label_active');
			
			$this->db->from('tbl_eventhr_mst_typeberita');
			if($typecheck !== NULL){
	        	if($typecheck=="in"){
	        		$this->db->where(" tbl_eventhr_mst_typeberita.type_berita ",$typeberitacheck1);
	        	} else if($typecheck=="up"){
	        		$this->db->where(" tbl_eventhr_mst_typeberita.type_berita ",$typeberitacheck1);	        		
	        		$this->db->where_not_in(" tbl_eventhr_mst_typeberita.id_typeberita ",$exceptioncheck);
	        	}
	        } else {
	        	if ($id_type !== NULL) {
					$this->db->where('tbl_eventhr_mst_typeberita.id_typeberita', $id_type);
				}
				if($active !== NULL){
					$this->db->where_in('tbl_eventhr_mst_typeberita.na', $active);											
				}			
			}
			
			$this->db->where('tbl_eventhr_mst_typeberita.del ', 'n');
			$this->db->order_by('tbl_eventhr_mst_typeberita.type_berita ASC ');
			
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*================================DOT CCTV====================================*/
		function get_master_dot($conn = NULL, $id_mdot = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $exceptioncheck = NULL, $dotcheck1 = NULL, $dotcheck2 = NULL, $dotcheck3 = NULL, $pabrik= NULL) {

			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_cctv_mdot.*,REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(tbl_cctv_mdot.dot, \' \', \'\'), \'-\', \'\'),\'/\', \'\'), \'(\', \'\'),\')\',\'\'),\'.\',\'\') as dot_fieldname, tbl_inv_sub_lokasi.nama as lokasi,tbl_wf_master_plant.plant_name ');
			
			$this->db->select('CASE
								WHEN tbl_cctv_mdot.na = \'n\' AND tbl_cctv_mdot.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
								WHEN tbl_cctv_mdot.na = \'y\' AND tbl_cctv_mdot.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
						   		WHEN tbl_cctv_mdot.na = \'y\' AND tbl_cctv_mdot.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
						   		ELSE \'<span class="label label-danger"></span>\'
							   END as label_active');
			
			$this->db->from('tbl_cctv_mdot');
			$this->db->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_cctv_mdot.id_sublokasi', 'LEFT');
			$this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant = tbl_cctv_mdot.plant', 'LEFT');
			if($typecheck !== NULL){
	        	if($typecheck=="in"){
	        		$this->db->where(" tbl_cctv_mdot.dot ",$dotcheck1);
	        		$this->db->where(" tbl_cctv_mdot.id_sublokasi ",$dotcheck2);
	        		$this->db->where(" tbl_cctv_mdot.plant ",$dotcheck3);		
	        	} else if($typecheck=="up"){
	        		$this->db->where(" tbl_cctv_mdot.dot ",$dotcheck1);
	        		$this->db->where(" tbl_cctv_mdot.id_sublokasi ",$dotcheck2);
	        		$this->db->where(" tbl_cctv_mdot.plant ",$dotcheck3);
	        		$this->db->where_not_in(" tbl_cctv_mdot.id_mdot ",$exceptioncheck);
	        	}
	        } else {
	        	if ($id_mdot !== NULL) {
				$this->db->where('tbl_cctv_mdot.id_mdot', $id_mdot);
				}
				if($active !== NULL){
					$this->db->where_in('tbl_cctv_mdot.na', $active);														
				}
				if ($pabrik !== NULL) {
					$this->db->where('tbl_cctv_mdot.plant', $pabrik);
				}

	        }
			
			$this->db->where('tbl_cctv_mdot.del ', 'n');
			$this->db->order_by('tbl_cctv_mdot.plant ASC, tbl_cctv_mdot.order_number ASC');
			// $this->db->order_by('tbl_cctv_mdot.dot ASC , tbl_cctv_mdot.plant ASC, tbl_inv_sub_lokasi.nama ASC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}
		/*================================Criteria CCTV====================================*/
		function get_master_criteria($conn = NULL, $id_criteriaAchv = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $exceptioncheck = NULL, $criteriacheck1 = NULL,  $criteriacheck2 = NULL,  $criteriacheck3 = NULL, $persen = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_cctv_criteria_achv.*,tbl_cctv_criteria_achv.na as na_achv,tbl_cctv_criteria_achv.del as del_achv');
			$this->db->select('tbl_cctv_css_color.*');			
			$this->db->select('CASE
								WHEN tbl_cctv_criteria_achv.na = \'n\' AND tbl_cctv_criteria_achv.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
								WHEN tbl_cctv_criteria_achv.na = \'y\' AND tbl_cctv_criteria_achv.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
						   		WHEN tbl_cctv_criteria_achv.na = \'y\' AND tbl_cctv_criteria_achv.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
						   		ELSE \'<span class="label label-danger"></span>\'
							   END as label_active');
			
			$this->db->from('tbl_cctv_criteria_achv');
			$this->db->join('tbl_cctv_css_color', 'tbl_cctv_css_color.id_cssColor = tbl_cctv_criteria_achv.id_css', 'LEFT');
			
			if($typecheck !== NULL){
				// val_min <= 7 AND val_max >= 7 AND id_criteriaAchv != 1
	        	if($typecheck=="in"){
	        		$this->db->where(" ((tbl_cctv_criteria_achv.val_min <= '".$criteriacheck1."' AND tbl_cctv_criteria_achv.val_max >= '".$criteriacheck1."') OR (tbl_cctv_criteria_achv.val_min <= '".$criteriacheck2."' AND tbl_cctv_criteria_achv.val_max >= '".$criteriacheck2."')) ");
	        		// $this->db->where(" tbl_cctv_criteria_achv.val_max >= '".$criteriacheck2."'");
	        	} else if($typecheck=="up"){
	        		$this->db->where(" ((tbl_cctv_criteria_achv.val_min <= '".$criteriacheck1."' AND tbl_cctv_criteria_achv.val_max >= '".$criteriacheck1."') OR (tbl_cctv_criteria_achv.val_min <= '".$criteriacheck2."' AND tbl_cctv_criteria_achv.val_max >= '".$criteriacheck2."')) ");
	        		$this->db->where_not_in(" tbl_cctv_criteria_achv.id_criteriaAchv ",$exceptioncheck);
	        	}
	        } else {
	        	if ($id_criteriaAchv !== NULL) {
				$this->db->where('tbl_cctv_criteria_achv.id_criteriaAchv', $id_criteriaAchv);
				}
				if($active !== NULL){
					$this->db->where_in('tbl_cctv_criteria_achv.na', $active);
					// $this->db->where_in('tbl_cctv_criteria_achv.del', 'n');									
				}
				if($persen !== NULL){
					$this->db->where(" (tbl_cctv_criteria_achv.val_min <= '".$persen."' AND tbl_cctv_criteria_achv.val_max >= '".$persen."')  ");									
				}
	        }			
			
			
			$this->db->where_in('tbl_cctv_criteria_achv.del ', 'n');	
			$this->db->order_by('tbl_cctv_criteria_achv.val_min ASC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}
		/*================================sublokasi CCTV====================================*/
		function get_master_lokasi($conn = NULL, $id_lokasi = NULL, $active = NULL, $deleted = 'n') {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			// select * from tbl_inv_sub_lokasi where id_lokasi = '001'
			
			$this->db->select('tbl_inv_lokasi.*');
						
			$this->db->select('CASE
								WHEN tbl_inv_lokasi.na = \'n\' AND tbl_inv_lokasi.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
								WHEN tbl_inv_lokasi.na = \'y\' AND tbl_inv_lokasi.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
						   		WHEN tbl_inv_lokasi.na = \'y\' AND tbl_inv_lokasi.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
						   		ELSE \'<span class="label label-danger"></span>\'
							   END as label_active');			
			$this->db->from('tbl_inv_lokasi');
			
        	if ($id_lokasi !== NULL) {
				$this->db->where_in('tbl_inv_lokasi.id_lokasi', $id_lokasi);
			}
			
			if($active !== NULL){
				$this->db->where_in('tbl_inv_lokasi.na', $active);
													
			}
			$this->db->where_in('tbl_inv_lokasi.del', 'n');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}
		/*================================sublokasi CCTV====================================*/
		function get_master_sublokasi($conn = NULL, $id_sub = NULL, $active = NULL, $deleted = 'n', $typesub = NULL, $forpage = NULL, $groupby = NULL,$pabrik = NULL, $lokasi = NULL) {
			// ("portal", NULL, NULL, NULL, "pabrik","tran","tbl_inv_sub_lokasi.nama",$data_pabrik)
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			// select * from tbl_inv_sub_lokasi where id_lokasi = '001'
			if($groupby == NULL){
				$this->db->select('tbl_inv_sub_lokasi.*');
			} else {
				$this->db->select($groupby);
			}			
			$this->db->select('CASE
								WHEN tbl_inv_sub_lokasi.na = \'n\' AND tbl_inv_sub_lokasi.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
								WHEN tbl_inv_sub_lokasi.na = \'y\' AND tbl_inv_sub_lokasi.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
						   		WHEN tbl_inv_sub_lokasi.na = \'y\' AND tbl_inv_sub_lokasi.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
						   		ELSE \'<span class="label label-danger"></span>\'
							   END as label_active');			
			$this->db->from('tbl_inv_sub_lokasi');
			if($forpage == 'tran'){
				$this->db->join('tbl_cctv_mdot', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_cctv_mdot.id_sublokasi
						AND tbl_cctv_mdot.del = \'n\' ', 'INNER');
			}
			
        	if ($id_sub !== NULL) {
				$this->db->where('tbl_inv_sub_lokasi.id_sub_lokasi', $id_sub);
			}
			if ($pabrik !== NULL) {
				$this->db->where('tbl_cctv_mdot.plant', $pabrik);
			}
			if($active !== NULL){
				$this->db->where_in('tbl_inv_sub_lokasi.na', $active);
													
			}
			if($typesub !== NULL){
				switch ($typesub) {
				
					case 'pabrik':
						$valsub = '1';
						break;
					case 'depo':
						$valsub = '2';
						break;
					case 'ho':
						$valsub = '3';
						break;
					default:
						$valsub = '0';
						$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
						echo json_encode($return);
					break;
				}
				$this->db->where('tbl_inv_sub_lokasi.id_lokasi', $valsub);
			}
			if($lokasi !== NULL){
				$this->db->where('tbl_inv_sub_lokasi.id_lokasi', $lokasi);
			}
	        if($groupby == NULL){
				$this->db->order_by('tbl_inv_sub_lokasi.nama ASC');
			} else {
				$this->db->group_by($groupby.' ,tbl_inv_sub_lokasi.na,tbl_inv_sub_lokasi.del ');
				//$this->db->order_by( $groupby.' ASC');
				
			}
			$this->db->where_in('tbl_inv_sub_lokasi.del', 'n');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}
		/*================================area CCTV====================================*/
		function get_master_area($conn = NULL, $id_sublokasi = NULL, $active = NULL, $deleted = 'n') {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			// select * from tbl_inv_sub_lokasi where id_lokasi = '001'
			
			$this->db->select('tbl_inv_area.*');
						
			$this->db->select('CASE
								WHEN tbl_inv_area.na = \'n\' AND tbl_inv_area.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
								WHEN tbl_inv_area.na = \'y\' AND tbl_inv_area.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
						   		WHEN tbl_inv_area.na = \'y\' AND tbl_inv_area.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
						   		ELSE \'<span class="label label-danger"></span>\'
							   END as label_active');			
			$this->db->from('tbl_inv_area');
			
        	if ($id_sublokasi !== NULL) {
				$this->db->where_in('tbl_inv_area.id_sub_lokasi', $id_sublokasi);
			}
			
			if($active !== NULL){
				$this->db->where_in('tbl_inv_area.na', $active);													
			}
			$this->db->where_in('tbl_inv_area.del', 'n');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}
		/*================================sublokasi CCTV====================================*/
		function get_master_csscolor($conn = NULL, $id_css = NULL, $active = NULL, $deleted = 'n', $typecss = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			// select * from tbl_inv_sub_lokasi where id_lokasi = '001'
			$this->db->select('tbl_cctv_css_color.*');
						
			$this->db->select('CASE
								WHEN tbl_cctv_css_color.na = \'n\' AND tbl_cctv_css_color.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
								WHEN tbl_cctv_css_color.na = \'y\' AND tbl_cctv_css_color.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
						   		WHEN tbl_cctv_css_color.na = \'y\' AND tbl_cctv_css_color.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
						   		ELSE \'<span class="label label-danger"></span>\'
							   END as label_active');			
			$this->db->from('tbl_cctv_css_color');

			// if($forpage == 'tran'){
			// 	$this->db->join('tbl_cctv_mdot', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_cctv_mdot.id_sublokasi', 'INNER');
			// }

			
        	if ($id_css !== NULL) {
				$this->db->where('tbl_cctv_css_color.id_cssColor', $id_css);
			}
			if($active !== NULL){
				$this->db->where_in('tbl_cctv_css_color.na', $active);
				$this->db->where_in('tbl_cctv_css_color.del', 'n');									
			}
			

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_pabrik_oto($ho=NULL, $gsber=NULL, $except_plant=NULL){
			$this->general->connectDbPortal();
			// $query = $this->db->get_where('tbl_inv_pabrik',array('del' => 'n', 'na' => 'n'));
			
			$this->db->select('plant');
			$this->db->from('tbl_wf_master_plant');
			if($ho == 'n') {
				if($gsber != NULL){
					$this->db->where('plant', $gsber);
				}
			}
			if($except_plant == NULL){
				$array_ex = array('KMTR','KPK1');
				$this->db->where_not_in('plant', $array_ex);
			}
			
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');
			
			$this->db->order_by('plant', 'ASC');
			$query = $this->db->get();

			return $query->result();
		}

		
	}

?>
