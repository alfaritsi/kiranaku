<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application  : Notering 
	@author       : Airiza Yuddha (7849)
	@contributor  :
		  1. <insert your fullname> (<insert your nik>) <insert the date>
			 <insert what you have modified>
		  2. <insert your fullname> (<insert your nik>) <insert the date>
			 <insert what you have modified>
		  etc.
    */

	class Dmasternotering extends CI_Model {
		/*================================user====================================*/
		function get_data_role($conn=NULL,$id=NULL,$all=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->db->select("*");
			
			$this->db->from("tbl_notering_role");
			
			if($id != NULL){
				$this->db->where('kode_role', $id);
			}
			if($all == NULL){
				// $this->datatables->where('tbl_pica_jenis_temuan.na', 'n');
				$this->db->where('del', 'n');				
			}
			
			$query = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;

		}

		function get_data_karyawan($conn=NULL,$nik=NULL,$all=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->db->select(" *, 
							CASE 
							  WHEN tbl_karyawan.nik in (select distinct nik from tbl_wf_region) 
							  		-- ceo region 
							    THEN 
							  CAST((SELECT DISTINCT y.plant + RTRIM(',')   
							  	     FROM tbl_wf_region x
							         LEFT JOIN tbl_wf_master_plant y ON y.plant_code = x.plant_code
							  	     WHERE x.nik = tbl_karyawan.nik AND tbl_karyawan.na='n' AND x.na = 'n' 
							          AND x.del = 'n' AND y.na = 'n' AND y.del = 'n'
							  	     FOR XML PATH ('')) as VARCHAR(MAX))
							  
							  WHEN tbl_karyawan.id_gedung = 'HO' THEN 'Semua Pabrik'

							  WHEN tbl_karyawan.id_gedung <> 'HO' THEN tbl_karyawan.id_gedung
							  
							  ELSE ''
							END
							plant_group

				");
			
			$this->db->from("tbl_karyawan");
			
			if($nik != NULL){
				$this->db->where('nik', $nik);
			}
			if($all == NULL){
				// $this->datatables->where('tbl_pica_jenis_temuan.na', 'n');
				$this->db->where('del', 'n');				
			}
			
			$query = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;

		}

		/*================================temuan====================================*/
		function get_data_user_paging($conn=NULL,$id=NULL,$all=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->datatables->select("id_user");
			$this->datatables->select("nik");			
			$this->datatables->select("deviceId");			
			$this->datatables->select("tempDeviceId");			
			$this->datatables->select("login_buat");
			$this->datatables->select("tanggal_buat");
			$this->datatables->select("login_edit");
			$this->datatables->select("tanggal_edit");
			$this->datatables->select("na");
			$this->datatables->select("del");
			$this->datatables->select("verify");
			$this->datatables->select("nama_role");
			$this->datatables->select("kode_role");
			$this->datatables->select("nama_karyawan");
			$this->datatables->select("plant_group");
			
			$this->datatables->from("vw_notering_user");
			
			if($id != NULL){
				$this->datatables->where('id_user', $id);
			}
			if($all == NULL){
				// $this->datatables->where('tbl_pica_jenis_temuan.na', 'n');
				$this->datatables->where('del', 'n');				
			}
			
			$return = $this->datatables->generate();
			$raw = json_decode($return, true);
			$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_user"));
			return $this->general->jsonify($raw);

		}

		function get_data_user_normal($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL,$datacheck_2=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(" id_user, nik,
								deviceId, tempDeviceId,
								login_buat,tanggal_buat,
								login_edit,tanggal_edit,
								na,del, verify, nama_karyawan, plant_group,
								nama_role,kode_role");
						
			$this->db->from("vw_notering_user");

			if($typecheck !== NULL){
	        	
	        	if($typecheck=="in"){
	        		$this->db->where(" LTRIM(RTRIM(nik)) ",trim($datacheck_1));
	        		// $this->db->where(" LTRIM(RTRIM(tbl_pica_jenis_temuan.requestor)) ",trim($datacheck_2));
	        		$this->db->where(" del ", 'n');
	        	} else if($typecheck=="up"){
	        		$this->db->where(" LTRIM(RTRIM(nik)) ",trim($datacheck_1));
	        		// $this->db->where(" LTRIM(RTRIM(tbl_pica_jenis_temuan.requestor)) ",trim($datacheck_2));
	        		$this->db->where(" del ", 'n');
	        		$this->db->where_not_in(" id_user ",$id);
	        	}
	        } else {
				if($id != NULL){
					$this->db->where('id_user', $id);
				}
				if($all == NULL){
					$this->db->where('del', 'n');				
				}
				if($all != NULL){
					$this->db->where('na', 'n');
					$this->db->where('del', 'n');				
				}
			}
			
			$query = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function delete_data($table=NULL,$field=NULL,$id=NULL){
			$this->db->where($field, $id);
			$this->db->delete($table);
			return "success";

		}



		
	}

?>
