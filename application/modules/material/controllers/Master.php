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

Class Master extends MX_Controller{
	function __construct(){
	    parent::__construct();
		$this->load->library('PHPExcel');
        $this->load->helper(array('form', 'url'));
		
	    $this->load->model('dmastermaterial');
	}

	public function index(){
		show_404();
	}
	
	public function matrix($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Matrix";
		$data['title_form']  = "Form Master Matrix";
		$data['mtart'] 		 = $this->get_mtart('array');
		$data['kolom'] 		 = $this->get_kolom('array');
		$data['default']	 = $this->get_assign_group('array');
		$data['matrix'] 	 = $this->get_matrix('array', NULL, NULL, NULL);
		$this->load->view("master/matrix", $data);	
	}
	
	public function item($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Item Name";
		$data['title_form']  = "Form Master Item Name";
		$data['group'] 	 	 = $this->get_group('array',NULL,'n');
		$data['bklas'] 	 	 = $this->get_bklas('array');
		$data['matkl'] 	 	 = $this->get_matkl('array');
		$data['item'] 	 	 = $this->get_item('array', NULL, NULL, NULL);
		$this->load->view("master/item", $data);	
	}
	public function role($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Role";
		$data['title_form']  = "Form Master Role";
		$data['pic'] 	 	 = $this->get_pic('array');
		$data['posisi'] 	 = $this->get_posisi('array');
		$data['divisi'] 	 = $this->get_divisi('array');
		$data['seksi']	 	 = $this->get_seksi('array');
		$data['role'] 	 	 = $this->get_role('array', NULL, NULL, NULL);
		$this->load->view("master/role", $data);	
	}
	public function group($param=NULL){
		//====must be initiate in every view function====/
	    $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
	    //===============================================/
		
		$data['title']    	 = "Master Item Group";
		$data['title_form']  = "Form Master Item Group";
		$data['mtart'] 		 = $this->get_mtart('array');
		$data['group'] 	 	 = $this->get_group('array', NULL, NULL, NULL);
		$this->load->view("master/group", $data);	
	}
	
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL,$param2 = NULL) {
		switch ($param) {
			case 'nomor':
				$id_item_group  = ((isset($_POST['id_item_group'])and($_POST['id_item_group']!=0)) ? $_POST['id_item_group'] : NULL);
				$this->get_nomor(NULL, $id_item_group);	
				break;
			
			case 'role':
				$id_item_setting_user = (isset($_POST['id_item_setting_user']) ? $this->generate->kirana_decrypt($_POST['id_item_setting_user']) : NULL);
				$this->get_role(NULL, $id_item_setting_user, NULL, NULL);
				break;
			case 'group':
				$id_item_group = (isset($_POST['id_item_group']) ? $_POST['id_item_group'] : NULL);
				$description   = (isset($_POST['description']) ? $_POST['description'] : NULL);
				$mtart   	   = (isset($_POST['mtart']) ? $_POST['mtart'] : NULL);
				$this->get_group(NULL, $id_item_group, 'n', NULL,$description,$mtart);
				break;
			case 'item':
				$id_item_name  = (isset($_POST['id_item_name']) ? $_POST['id_item_name'] : NULL);
				$description   = (isset($_POST['description']) ? $_POST['description'] : NULL);
				$req   		   = (isset($_POST['req']) ? $_POST['req'] : NULL);
				if(isset($_POST['id_item_group_filter'])){
					$id_item_group_filter		= array();
					foreach ($_POST['id_item_group_filter'] as $dt) {
						array_push($id_item_group_filter, $dt);
					}
				}else{
					$id_item_group_filter  = NULL;
				}
				if(isset($_POST['filter_request_status'])){
					$filter_request_status		= array();
					foreach ($_POST['filter_request_status'] as $dt) {
						array_push($filter_request_status, $dt);
					}
				}else{
					$filter_request_status  = NULL;
				}
				
				$this->get_item(NULL, $id_item_name, NULL, NULL,$description, NULL, NULL, NULL, NULL, $id_item_group_filter, $filter_request_status, $req);
				break;
			case 'bklas':
				$id_item_group  = (isset($_POST['id_item_group']) ? $_POST['id_item_group'] : NULL);
				// echo $id_item_group.'xxxx'; 
				$this->get_bklas(NULL, $id_item_group);
				break;
			case 'matrix':
				$filter_kolom = (isset($_POST['filter_kolom']) ? $_POST['filter_kolom'] : NULL);
				if(isset($_POST['filter_mtart'])){
					$filter_mtart		= array();
					foreach ($_POST['filter_mtart'] as $dt) {
						array_push($filter_mtart, $dt);
					}
				}else{
					$filter_mtart  = NULL;
				}
				if(isset($_POST['filter_class'])){
					$filter_class		= array();
					foreach ($_POST['filter_class'] as $dt) {
						array_push($filter_class, $dt);
					}
				}else{
					$filter_class  = NULL;
				}
				
				$this->get_matrix(NULL, NULL, NULL, NULL, $filter_kolom, NULL, NULL, NULL, NULL, $filter_mtart, $filter_class);
				break;
				
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function set($param = NULL) {
		$action = NULL;
		if (isset($_POST['type']) && $_POST['type'] == "nonactive") {
			$action = "delete_na";
		} else if (isset($_POST['type']) && $_POST['type'] == "setactive") {
			$action = "activate_na";
		}
		if ($action) {
			switch ($param) {
				case 'role':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_item_setting_user", array(
						array(
							'kolom' => 'id_item_setting_user',
							'value' => $this->generate->kirana_decrypt($_POST['id_item_setting_user'])
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'group':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_item_group", array(
						array(
							'kolom' => 'id_item_group',
							'value' => $_POST['id_item_group']
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				case 'item':
					$this->general->connectDbPortal();
					$return = $this->general->set($action, "tbl_item_name", array(
						array(
							'kolom' => 'id_item_name',
							'value' => $_POST['id_item_name']
						)
					));
					echo json_encode($return);
					$this->general->closeDb();
					break;
				default:
					$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
					echo json_encode($return);
					break;
			}
		}
	}
	public function save($param = NULL) {
		switch ($param) {
			case 'role':
				$this->save_role($param);
				break;
			case 'group':
				$this->save_group($param);
				break;
			case 'item':
				$this->save_item($param);
				break;
			case 'matrix':
				$this->save_matrix($param);
				break;
			case 'required':
				$this->save_required($param);
				break;
			case 'default':
				$this->save_default($param);
				break;
			case 'excel_group':
				$this->save_excel_group($param);
				break;
			case 'excel_item':
				$this->save_excel_item($param);
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}
	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_nomor($array = NULL, $id_item_group = NULL) {
		$nomor	= $this->dmastermaterial->get_data_nomor("open", $id_item_group);
		if ($array) {
			return $nomor;
		} else {
			echo json_encode($nomor);
		}
	}
	
	private function get_role($array = NULL, $id_item_setting_user = NULL, $active = NULL, $deleted = NULL) {
		$role 		= $this->dmastermaterial->get_data_role("open", $id_item_setting_user, $active, $deleted);
		$role 		= $this->general->generate_encrypt_json($role, array("id_item_setting_user","id_item_master_pic"));
		if ($array) {
			return $role;
		} else {
			echo json_encode($role);
		}
	}
	private function get_group($array = NULL, $id_item_group = NULL, $active = NULL, $deleted = NULL, $description = NULL, $mtart = NULL) {
		$group 		= $this->dmastermaterial->get_data_group("open", $id_item_group, $active, $deleted, $description, $mtart);
		// $group 		= $this->general->generate_encrypt_json($group, array("id_item_group"));
		if ($array) {
			return $group;
		} else {
			echo json_encode($group);
		}
	}
	private function get_item($array = NULL, $id_item_name = NULL, $active = NULL, $deleted = NULL, $description = NULL, $id_item_group = NULL, $bklas = NULL, $matkl = NULL, $classification = NULL, $id_item_group_filter = NULL, $filter_request_status = NULL, $req = NULL) {
		$item 		= $this->dmastermaterial->get_data_item("open", $id_item_name, $active, $deleted, $description, $id_item_group, $bklas, $matkl, $classification, $id_item_group_filter, $filter_request_status, $req);
		// $item 		= $this->general->generate_encrypt_json($item, array("id_item_name"));
		if(!empty($item)){
			$bklas		= $this->dmastermaterial->get_data_bklas("array",$item[0]->id_item_group);
			$item[0]->arr_bklas	= $bklas;
		}
		
		if ($array) {
			return $item;
		} else {
			echo json_encode($item);
		}
	}
	private function get_matrix($array = NULL, $id_item_master_matrix = NULL, $active = NULL, $deleted = NULL, $kolom = NULL, $mtart = NULL, $classification = NULL, $default = NULL, $required = NULL, $filter_mtart = NULL, $filter_class = NULL) {
		$matrix	= $this->dmastermaterial->get_data_matrix("open", $id_item_master_matrix, $active, $deleted, $kolom, $mtart, $classification, $default, $required, $filter_mtart, $filter_class);
		if((!empty($matrix))and($matrix[0]->tabel_sap!=null)){
			$get_data	= $matrix[0]->tabel_sap;
			$default	= $this->$get_data('array');
			$result	= array();
			foreach($matrix as $dt){
				$dt->arr_default	= $default;	
				$result[] = $dt;
			}
		}else{
			$result = $matrix;
		}
		if ($array) {
			return $result;
		} else {
			echo json_encode($result);
		}
	}
	private function get_mtart($array = NULL) {
		$mtart 		= $this->dmastermaterial->get_data_mtart("open");
		// $mtart 		= $this->general->generate_encrypt_json($mtart, array("mtart"));
		if ($array) {
			return $mtart;
		} else {
			echo json_encode($mtart);
		}
	}
	private function get_kolom($array = NULL) {
		$kolom	= $this->dmastermaterial->get_data_kolom("open");
		if ($array) {
			return $kolom;
		} else {
			echo json_encode($kolom);
		}
	}
	private function get_assign_group($array = NULL) {
		$assign_group	= $this->dmastermaterial->get_data_assign_group("open");
		if ($array) {
			return $assign_group;
		} else {
			echo json_encode($assign_group);
		}
	}
	private function get_avail_check($array = NULL) {
		$avail_check	= $this->dmastermaterial->get_data_avail_check("open");
		if ($array) {
			return $avail_check;
		} else {
			echo json_encode($avail_check);
		}
	}
	private function get_dist($array = NULL) {
		$dist 		= $this->dmastermaterial->get_data_dist("open");
		if ($array) {
			return $dist;
		} else {
			echo json_encode($dist);
		}
	}
	private function get_div($array = NULL) {
		$div 		= $this->dmastermaterial->get_data_div("open");
		if ($array) {
			return $div;
		} else {
			echo json_encode($div);
		}
	}
	private function get_lot($array = NULL) {
		$lot 		= $this->dmastermaterial->get_data_lot("open");
		if ($array) {
			return $lot;
		} else {
			echo json_encode($lot);
		}
	}
	
	private function get_cat_group($array = NULL) {
		$cat_group 		= $this->dmastermaterial->get_data_cat_group("open");
		if ($array) {
			return $cat_group;
		} else {
			echo json_encode($cat_group);
		}
	}
	private function get_pricing_group($array = NULL) {
		$pricing_group 		= $this->dmastermaterial->get_data_pricing_group("open");
		if ($array) {
			return $pricing_group;
		} else {
			echo json_encode($pricing_group);
		}
	}
	private function get_statistic_group($array = NULL) {
		$statistic_group 		= $this->dmastermaterial->get_data_statistic_group("open");
		if ($array) {
			return $statistic_group;
		} else {
			echo json_encode($statistic_group);
		}
	}
	private function get_dispo($array = NULL) {
		$dispo 		= $this->dmastermaterial->get_data_dispo("open");
		if ($array) {
			return $dispo;
		} else {
			echo json_encode($dispo);
		}
	}
	private function get_mrp_group($array = NULL) {
		$mrp_group 		= $this->dmastermaterial->get_data_mrp_group("open");
		if ($array) {
			return $mrp_group;
		} else {
			echo json_encode($mrp_group);
		}
	}
	private function get_mrp_type($array = NULL) {
		$mrp_type 		= $this->dmastermaterial->get_data_mrp_type("open");
		if ($array) {
			return $mrp_type;
		} else {
			echo json_encode($mrp_type);
		}
	}
	private function get_uom($array = NULL) {
		$uom 		= $this->dmastermaterial->get_data_uom("open");
		if ($array) {
			return $uom;
		} else {
			echo json_encode($uom);
		}
	}
	private function get_periode_indicator($array = NULL) {
		$periode_indicator 		= $this->dmastermaterial->get_data_periode_indicator("open");
		if ($array) {
			return $periode_indicator;
		} else {
			echo json_encode($periode_indicator);
		}
	}
	private function get_ekgrp($array = NULL) {
		$ekgrp 		= $this->dmastermaterial->get_data_ekgrp("open");
		if ($array) {
			return $ekgrp;
		} else {
			echo json_encode($ekgrp);
		}
	}
	private function get_lgort($array = NULL, $plant = NULL) {
		$lgort 		= $this->dmastermaterial->get_data_lgort("open", $plant);
		if ($array) {
			return $lgort;
		} else {
			echo json_encode($lgort);
		}
	}
	private function get_tax_class($array = NULL) {
		$tax_class 		= $this->dmastermaterial->get_data_tax_class("open");
		if ($array) {
			return $tax_class;
		} else {
			echo json_encode($tax_class);
		}
	}

	private function get_bklas($array = NULL, $id_item_group = NULL) {
		$bklas 		= $this->dmastermaterial->get_data_bklas("open", $id_item_group);
		// $bklas 		= $this->general->generate_encrypt_json($bklas, array("bklas"));
		if ($array) {
			return $bklas;
		} else {
			echo json_encode($bklas);
		}
	}
	private function get_matkl($array = NULL) {
		$matkl 		= $this->dmastermaterial->get_data_matkl("open");
		// $matkl 		= $this->general->generate_encrypt_json($matkl, array("matkl"));
		if ($array) {
			return $matkl;
		} else {
			echo json_encode($matkl);
		}
	}
	private function get_pic($array = NULL) {
		$pic 		= $this->dmastermaterial->get_data_pic("open");
		$pic 		= $this->general->generate_encrypt_json($pic, array("id_item_master_pic"));
		if ($array) {
			return $pic;
		} else {
			echo json_encode($pic);
		}
	}
	private function get_level($array = NULL, $id_level = NULL) {
		$level 		= $this->dmastermaterial->get_data_level("open", $id_level);
		// $level 		= $this->general->generate_encrypt_json($level, array("id_level"));
		if ($array) {
			return $level;
		} else {
			echo json_encode($level);
		}
	}
	private function get_posisi($array = NULL, $id_posisi = NULL) {
		$posisi	= $this->dmastermaterial->get_data_posisi("open", $id_posisi);
		// $posisi	= $this->general->generate_encrypt_json($posisi, array("id_posisi"));
		if ($array) {
			return $posisi;
		} else {
			echo json_encode($posisi);
		}
	}
	private function get_divisi($array = NULL, $id_divisi = NULL) {
		$divisi	= $this->dmastermaterial->get_data_divisi("open", $id_divisi);
		// $divisi	= $this->general->generate_encrypt_json($divisi, array("id_divisi"));
		if ($array) {
			return $divisi;
		} else {
			echo json_encode($divisi);
		}
	}
	private function get_seksi($array = NULL, $id_seksi = NULL) {
		$seksi	= $this->dmastermaterial->get_data_seksi("open", $id_seksi);
		// $seksi	= $this->general->generate_encrypt_json($seksi, array("id_seksi"));
		if ($array) {
			return $seksi;
		} else {
			echo json_encode($seksi);
		}
	}
		
	private function save_role($param) {
		$datetime 				= date("Y-m-d H:i:s");
		$id_item_setting_user 	= (isset($_POST['id_item_setting_user']) ? $this->generate->kirana_decrypt($_POST['id_item_setting_user']) : NULL);
		$posisi				 	= (isset($_POST['posisi']) ? implode(",", $_POST['posisi']) : NULL);
		$divisi				 	= (isset($_POST['divisi']) ? implode(",", $_POST['divisi']) : NULL);
		$seksi				 	= (isset($_POST['seksi']) ? implode(",", $_POST['seksi']) : NULL);
		
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		$role = $this->dmastermaterial->get_data_role(NULL, $id_item_setting_user);
		if (count($role) != 0){
			$data_row = array(
				"id_item_master_pic"  	=> $this->generate->kirana_decrypt($_POST['id_item_master_pic']),
				"tipe"			 		=> $_POST['tipe'],
				"posisi"		 		=> $posisi,
				"divisi"		 		=> $divisi,
				"seksi"			 		=> $seksi
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_item_setting_user", $data_row, array(
				array(
					'kolom' => 'id_item_setting_user',
					'value' => $id_item_setting_user
				)
			));
		}else{
			$ck_role = $this->dmastermaterial->get_data_role(NULL, NULL, NULL, NULL, $this->generate->kirana_decrypt($_POST['id_item_master_pic']), $_POST['tipe']);
			if (count($ck_role) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}else{
				$data_row = array(
					"id_item_master_pic"  	=> $this->generate->kirana_decrypt($_POST['id_item_master_pic']),
					"tipe"			 		=> $_POST['tipe'],
					"posisi"		 		=> $posisi,
					"divisi"		 		=> $divisi,
					"seksi"			 		=> $seksi
				);
				$data_row = $this->dgeneral->basic_column("insert", $data_row);
				$this->dgeneral->insert("tbl_item_setting_user", $data_row);
			}
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	private function save_group($param) {
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$ck_group   = $this->dmastermaterial->get_data_group(NULL, $_POST['id_item_group']);
		if (count($ck_group) != 0){	
			$group 		= $this->dmastermaterial->get_data_group(NULL, NULL, NULL, NULL, $_POST['description']);
			if ((count($group) != 0)and($_POST['id_item_group']!=$group[0]->id_item_group)){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
		
			$data_row = array(
				"description"	 => strtoupper($_POST['description']),
				"mtart"    		 => strtoupper($_POST['mtart'])
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_item_group", $data_row, array(
				array(
					'kolom' => 'id_item_group',
					'value' => $_POST['id_item_group']
				)
			));
		}else{
			$group 		= $this->dmastermaterial->get_data_group(NULL, NULL, NULL, NULL, $_POST['description']);
			if (count($group) != 0){ 
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$data_row = array(
				"id_item_group"  => $_POST['id_item_group'],
				"description"	 => strtoupper($_POST['description']),
				"mtart"    		 => strtoupper($_POST['mtart'])
			);

			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_item_group", $data_row);
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	private function save_item($param) {
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$ck_item 	= $this->dmastermaterial->get_data_item(NULL, $_POST['id_item_name']);	
		if (count($ck_item) != 0){		
			// $item 		= $this->dmastermaterial->get_data_item(NULL, NULL, NULL, NULL, $_POST['description'], $_POST['id_item_group'], $_POST['bklas'], $_POST['matkl'], $_POST['classification']);
			$item 		= $this->dmastermaterial->get_data_item(NULL, NULL, NULL, NULL, $_POST['description']);
			if ((count($item) != 0)and($_POST['id_item_name']!=$item[0]->id_item_name)){
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$bklas  			= (isset($_POST['bklas']))?$_POST['bklas'] : NULL;
			$classification  	= (isset($_POST['classification']))?$_POST['classification'] : NULL;
			$data_row = array(
				"id_item_group"  => $_POST['id_item_group'],
				"code"			 => strtoupper($_POST['code']),
				"description"	 => strtoupper($_POST['description']),
				"bklas"			 => strtoupper($bklas),
				"matkl"			 => strtoupper($_POST['matkl']),
				"classification" => strtoupper($classification),
				"jasa" 			 => $_POST['jasa']
			);
			$data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_item_name", $data_row, array(
				array(
					'kolom' => 'id_item_name',
					'value' => $_POST['id_item_name']
				)
			));
		}else{
			// $item 		= $this->dmastermaterial->get_data_item(NULL, NULL, NULL, NULL, $_POST['description'], $_POST['id_item_group'], $_POST['bklas'], $_POST['matkl'], $_POST['classification']);
			$item 		= $this->dmastermaterial->get_data_item(NULL, NULL, 'n', NULL, $_POST['description']);
			if (count($item) != 0){
				$msg    = "Duplicate data, periksa kembali data yang dimasukkan";
				$sts    = "NotOK";
				$return = array('sts' => $sts, 'msg' => $msg);
				echo json_encode($return);
				exit();
			}
			$bklas  			= (isset($_POST['bklas']))?$_POST['bklas'] : NULL;
			$classification  	= (isset($_POST['classification']))?$_POST['classification'] : NULL;
			$data_row = array(
				"id_item_group"  => $_POST['id_item_group'],
				"code"			 => strtoupper($_POST['code']),
				"description"	 => strtoupper($_POST['description']),
				"bklas"			 => strtoupper($bklas),
				"matkl"			 => strtoupper($_POST['matkl']),
				"classification" => strtoupper($classification),
				"jasa" 			 => $_POST['jasa'],
			);
			$data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_item_name", $data_row);
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	private function save_matrix($param) {
		$datetime = date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$arr_class = array('A', 'E', 'I', 'IE');
		$kolom	   = $this->get_kolom('array');
		foreach($kolom as $aa){
			$mtart	= $this->get_mtart('array');
			foreach($mtart as $bb){
				foreach ($arr_class as &$cc) {
					$ck_matrix 		= $this->dmastermaterial->get_data_matrix(NULL, NULL, NULL, NULL, $aa->kolom, $bb->mtart, $cc);
					if (count($ck_matrix) == 0){
						$data_row = array(
							"kolom"  		 => $aa->kolom,
							"mtart"  		 => $bb->mtart,
							"classification" => $cc
						);
						$data_row = $this->dgeneral->basic_column("insert", $data_row);
						$this->dgeneral->insert("tbl_item_master_matrix", $data_row);
					}
				}				
			}
		}
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}
	
	private function save_required($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$id 		= isset($_POST['id']) ? $_POST['id'] : NULL;
		$req     	= ($_POST["stat"]=='true') ? 'y' : 'n';	
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$data_row = array(
			"required"	=> $req
		);
		$data_row = $this->dgeneral->basic_column("update", $data_row);
		$this->dgeneral->update("tbl_item_master_matrix", $data_row, array(
			array(
				'kolom' => 'id_item_master_matrix',
				'value' => $id
			)
		));

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		
	}
	private function save_default($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$id 		= isset($_POST['id']) ? $_POST['id'] : NULL;
		$def     	= isset($_POST['def']) ? $_POST['def'] : NULL;
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$data_row = array(
			"default"	=> $def
		);
		$data_row = $this->dgeneral->basic_column("update", $data_row);
		$this->dgeneral->update("tbl_item_master_matrix", $data_row, array(
			array(
				'kolom' => 'id_item_master_matrix',
				'value' => $id
			)
		));

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		
	}
	private function save_excel_group($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		if(!empty($_FILES['file_excel']['name'])){
			$target_dir = "./assets/temp";

			if (!file_exists($target_dir)) {
			    mkdir($target_dir, 0755, true);
			}

			$config['upload_path']          = $target_dir;
			$config['allowed_types']        = 'xls|xlsx';
	 
			$this->load->library('upload', $config);
	 
			if ( ! $this->upload->do_upload('file_excel')){
				$msg 	= $this->upload->display_errors();
				$sts 	= "NotOK";
			}else{
				$data 			= array('upload_data' => $this->upload->data());
				$objPHPExcel 	= PHPExcel_IOFactory::load($data['upload_data']['full_path']);
				$title_desc		= $objPHPExcel->getProperties()->getTitle();
				$objPHPExcel->setActiveSheetIndex(0);
				$data_excel		= $objPHPExcel->getActiveSheet();
				$highestRow 	= $data_excel->getHighestRow(); 
				$highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
				$datetime		= date("Y-m-d H:i:s");
				$data_row		= array();
				for($brs=3; $brs<=$highestRow; $brs++){
					$data_row	= array(
									'id_item_group'	=> $data_excel->getCellByColumnAndRow(0, $brs)->getValue(),
									'description'	=> $data_excel->getCellByColumnAndRow(1, $brs)->getValue(),
									'mtart' 		=> $data_excel->getCellByColumnAndRow(2, $brs)->getValue(),
									'login_buat' 	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'	=> $datetime,
									'login_edit' 	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit' 	=> $datetime,
									'na' 			=> 'n',
									'del'			=> 'n'
								);
					$ck_group = $this->dmastermaterial->get_data_group(NULL, $data_excel->getCellByColumnAndRow(0, $brs)->getValue());
					if(count($ck_group) != 0){
						unset($data_row['id_item_group']);
						unset($data_row['login_buat']);
						unset($data_row['tanggal_buat']);
						$this->dgeneral->update('tbl_item_group', $data_row, 
													array(
														array(
															'kolom'=>'id_item_group',
															'value'=>$data_excel->getCellByColumnAndRow(0, $brs)->getValue()
														)
													));
					}else{
						
						$this->dgeneral->insert('tbl_item_group', $data_row);
						// var_dump($this->db->last_query());
					}
				}
				if($this->dgeneral->status_transaction() === FALSE){
					$this->dgeneral->rollback_transaction();
					$msg 	= "Periksa kembali data yang diunggah";
					$sts 	= "NotOK";
				}else{
					$this->dgeneral->commit_transaction();
					$msg 	= "Data berhasil ditambahkan";
					$sts 	= "OK";
				}
				
				unlink($data['upload_data']['full_path']);
			}
		}else{
			$msg 	= "Silahkan pilih file yang ingin diunggah";
			$sts 	= "NotOK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		
	}
	private function save_excel_item($param) {
		$datetime 	= date("Y-m-d H:i:s");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		
		if(!empty($_FILES['file_excel']['name'])){
			$target_dir = "./assets/temp";

			if (!file_exists($target_dir)) {
			    mkdir($target_dir, 0755, true);
			}

			$config['upload_path']          = $target_dir;
			$config['allowed_types']        = 'xls|xlsx';
	 
			$this->load->library('upload', $config);
	 
			if ( ! $this->upload->do_upload('file_excel')){
				$msg 	= $this->upload->display_errors();
				$sts 	= "NotOK";
			}else{
				$data 			= array('upload_data' => $this->upload->data());
				$objPHPExcel 	= PHPExcel_IOFactory::load($data['upload_data']['full_path']);
				$title_desc		= $objPHPExcel->getProperties()->getTitle();
				$objPHPExcel->setActiveSheetIndex(1);
				$data_excel		= $objPHPExcel->getActiveSheet();
				$highestRow 	= $data_excel->getHighestRow(); 
				$highestColumn 	= PHPExcel_Cell::columnIndexFromString($data_excel->getHighestColumn(4));
				$datetime		= date("Y-m-d H:i:s");
				$data_row		= array();
				for($brs=3; $brs<=$highestRow; $brs++){
					$id_item_group	= $data_excel->getCellByColumnAndRow(1, $brs)->getValue();
					$code			= $data_excel->getCellByColumnAndRow(0, $brs)->getValue();
					$data_row	= array(
									'id_item_group'	=> $id_item_group,
									'code'			=> $code,
									'description'	=> $data_excel->getCellByColumnAndRow(3, $brs)->getValue(),
									'bklas'	 		=> $data_excel->getCellByColumnAndRow(4, $brs)->getValue(),
									'matkl'	 		=> $data_excel->getCellByColumnAndRow(5, $brs)->getValue(),
									'classification'=> $data_excel->getCellByColumnAndRow(6, $brs)->getValue(),
									'login_buat' 	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_buat'	=> $datetime,
									'login_edit' 	=> base64_decode($this->session->userdata("-id_user-")),
									'tanggal_edit' 	=> $datetime,
									'price_control'	=> $data_excel->getCellByColumnAndRow(11, $brs)->getValue(),
									'req' 			=> 'n',
									'na' 			=> 'n',
									'del'			=> 'n'
								);	
					$ck_item = $this->dmastermaterial->get_data_item(NULL, NULL, NULL, NULL, NULL, $id_item_group,NULL, NULL, NULL, NULL, NULL, NULL, $code);
					if(count($ck_item) != 0){
						unset($data_row['id_item_group']);
						unset($data_row['code']);
						unset($data_row['login_buat']);
						unset($data_row['tanggal_buat']);
						$this->dgeneral->update('tbl_item_name', $data_row, 
													array(
														array(
															'kolom'=>'id_item_group',
															'value'=>$id_item_group
														),
														array(
															'kolom'=>'code',
															'value'=>$code
														)
													));
					}else{
						
						$this->dgeneral->insert('tbl_item_name', $data_row);
						// var_dump($this->db->last_query());
					}
				}
				if($this->dgeneral->status_transaction() === FALSE){
					$this->dgeneral->rollback_transaction();
					$msg 	= "Periksa kembali data yang diunggah";
					$sts 	= "NotOK";
				}else{
					$this->dgeneral->commit_transaction();
					$msg 	= "Data berhasil ditambahkan";
					$sts 	= "OK";
				}
				
				unlink($data['upload_data']['full_path']);
			}
		}else{
			$msg 	= "Silahkan pilih file yang ingin diunggah";
			$sts 	= "NotOK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		
	}
	/*====================================================================*/
		
}