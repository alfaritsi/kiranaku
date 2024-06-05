<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application  : PICA 
	@author       : Airiza Yuddha (7849)
	@contributor  :
		  1. Airiza Yuddha (7849) 14 oct 2020
			 modified function get_data_pica_buyer_so & get_data_pica_buyer_si 
			 	- change logic from ZDMMKTSUPP01.KUNNR to ZDMMKTSUPP01.SHPTO
		  2. <insert your fullname> (<insert your nik>) <insert the date>
			 <insert what you have modified>
		  etc.

	*/

	class Dmasterpica extends CI_Model {
		/*================================temuan====================================*/
		function get_data_pica_temuan($conn=NULL,$id_pica_jenis_temuan=NULL,$all=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->datatables->select("tbl_pica_jenis_temuan.id_pica_jenis_temuan");
			$this->datatables->select("tbl_pica_jenis_temuan.jenis_temuan");			
			$this->datatables->select("tbl_pica_jenis_temuan.kode_temuan");			
			$this->datatables->select("tbl_pica_jenis_temuan.requestor");			
			$this->datatables->select("tbl_pica_jenis_temuan.login_buat");
			$this->datatables->select("tbl_pica_jenis_temuan.tanggal_buat");
			$this->datatables->select("tbl_pica_jenis_temuan.login_edit");
			$this->datatables->select("tbl_pica_jenis_temuan.tanggal_edit");
			$this->datatables->select("tbl_pica_jenis_temuan.na");
			$this->datatables->select("tbl_pica_jenis_temuan.del");
			
			$this->datatables->from("tbl_pica_jenis_temuan");
			
			if($id_pica_jenis_temuan != NULL){
				$this->datatables->where('tbl_pica_jenis_temuan.id_pica_jenis_temuan', $id_pica_jenis_temuan);
			}
			if($all == NULL){
				// $this->datatables->where('tbl_pica_jenis_temuan.na', 'n');
				$this->datatables->where('tbl_pica_jenis_temuan.del', 'n');				
			}
			
			$return = $this->datatables->generate();
			$raw = json_decode($return, true);
			$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_pica_jenis_temuan"));
			return $this->general->jsonify($raw);

		}

		function get_data_pica_temuan_normal($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL,$datacheck_2=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(" tbl_pica_jenis_temuan.id_pica_jenis_temuan, tbl_pica_jenis_temuan.jenis_temuan, 
								tbl_pica_jenis_temuan.requestor, tbl_pica_jenis_temuan.kode_temuan, tbl_pica_jenis_temuan.login_buat,tbl_pica_jenis_temuan.tanggal_buat,
								tbl_pica_jenis_temuan.login_edit,tbl_pica_jenis_temuan.tanggal_edit,tbl_pica_jenis_temuan.na,
								tbl_pica_jenis_temuan.del");
						
			$this->db->from("tbl_pica_jenis_temuan");

			if($typecheck !== NULL){
	        	
	        	if($typecheck=="in"){
	        		$this->db->where(" LTRIM(RTRIM(tbl_pica_jenis_temuan.jenis_temuan)) ",trim($datacheck_1));
	        		$this->db->where(" LTRIM(RTRIM(tbl_pica_jenis_temuan.requestor)) ",trim($datacheck_2));
	        		$this->db->where(" del ", 'n');
	        	} else if($typecheck=="up"){
	        		$this->db->where(" LTRIM(RTRIM(tbl_pica_jenis_temuan.jenis_temuan)) ",trim($datacheck_1));
	        		$this->db->where(" LTRIM(RTRIM(tbl_pica_jenis_temuan.requestor)) ",trim($datacheck_2));
	        		$this->db->where(" del ", 'n');
	        		$this->db->where_not_in(" tbl_pica_jenis_temuan.id_pica_jenis_temuan ",$id);
	        	}
	        } else {
				if($id != NULL){
					$this->db->where('tbl_pica_jenis_temuan.id_pica_jenis_temuan', $id);
				}
				if($all == NULL){
					$this->db->where('tbl_pica_jenis_temuan.del', 'n');				
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

		/*================================role====================================*/
		function get_data_pica_role($conn=NULL,$id=NULL,$all=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->datatables->select(" id_pica_role");
			$this->datatables->select(" id_pica_jenis_temuan");

			$this->datatables->select(" nama_role");
			$this->datatables->select(" nama_temuan");
			$this->datatables->select(" jenis_temuan");
			$this->datatables->select(" level");
			$this->datatables->select(" if_approve");
			$this->datatables->select(" if_decline");	
			$this->datatables->select(" isresponder");	
			$this->datatables->select(" akses_delete");	
			$this->datatables->select(" multiple_plan");	
			$this->datatables->select(" approve");
			$this->datatables->select(" decline");
			$this->datatables->select(" tgl_edit");		
			$this->datatables->select(" login_buat");
			$this->datatables->select(" tanggal_buat");
			$this->datatables->select(" login_edit");
			$this->datatables->select(" tanggal_edit");
			$this->datatables->select(" na");
			$this->datatables->select(" del");
			
			$this->datatables->from("vw_pica_role");
			
			$where = '';
			if($id != NULL){
				$this->datatables->where('id_pica_role', $id);
			}
			if($all == NULL){
				// $this->datatables->where('tbl_pica_jenis_temuan.na', 'n');
				$this->datatables->where('del', 'n');				
			}
			if($all != NULL){
				$this->datatables->where('na', 'n');
				$this->datatables->where('del', 'n');				
			}
			
			$return = $this->datatables->generate();
			$raw = json_decode($return, true);
			$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_pica_role"));
			return $this->general->jsonify($raw);
		}

		function get_data_pica_role_normal($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL,$datacheck_2=NULL,$id_temuan=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(" id_pica_role");
			$this->db->select(" id_pica_jenis_temuan");
			$this->db->select(" nama_role");	
			$this->db->select(" nama_temuan");
			$this->db->select(" jenis_temuan");
			$this->db->select(" level");
			$this->db->select(" if_approve");
			$this->db->select(" if_decline");	
			$this->db->select(" isresponder");	
			$this->db->select(" akses_delete");	
			$this->db->select(" multiple_plan");	
			$this->db->select(" approve");
			$this->db->select(" decline");
			$this->db->select(" tgl_edit");		
			$this->db->select(" login_buat");
			$this->db->select(" tanggal_buat");
			$this->db->select(" login_edit");
			$this->db->select(" tanggal_edit");
			$this->db->select(" na");
			$this->db->select(" del");
			
			$this->db->from("vw_pica_role");

			if($typecheck !== NULL){
	        	
	        	if($typecheck=="in"){
	        		$this->db->where 		(" LTRIM(RTRIM(nama_role)) ",trim($datacheck_1));
	        		$this->db->where 		(" id_pica_jenis_temuan "	,trim($datacheck_2));
	        		$this->db->where 		(" del ", 'n');
	        	} else if($typecheck=="up"){
	        		$this->db->where 		(" LTRIM(RTRIM(nama_role)) ",trim($datacheck_1));
	        		$this->db->where 		(" id_pica_jenis_temuan "	,trim($datacheck_2));
	        		$this->db->where 		(" del ", 'n');
	        		$this->db->where_not_in (" id_pica_role "	,$id);
	        	}
	        } else {

				if($id != NULL){
					$this->db->where('id_pica_role', $id);
				}
				if($id_temuan != NULL){
					$this->db->where('id_pica_jenis_temuan', $id_temuan);
				}
				if($all == NULL){
					$this->db->where('del', 'n');				
				}
				if($all != NULL){
					$this->db->where('na', 'n');
					$this->db->where('del', 'n');				
				}
			}
			$this->db->order_by('nama_role ASC');
			
			$query = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function check_data_pica_role_level($conn=NULL,$id_temuan=NULL,$level=NULL,$type=NULL,$id_role=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(" tbl_pica_jenis_temuan.id_pica_jenis_temuan ,tbl_pica_jenis_temuan.jenis_temuan, tbl_pica_role.level ");
			
			$this->db->from("tbl_pica_role");
			$this->db->join('tbl_pica_jenis_temuan', 'tbl_pica_jenis_temuan.id_pica_jenis_temuan = tbl_pica_role.id_pica_jenis_temuan 
								AND tbl_pica_jenis_temuan.na=\'n\' AND tbl_pica_jenis_temuan.del=\'n\' ', 'inner');
			if($id_temuan != NULL)
				$this->db->where('tbl_pica_jenis_temuan.id_pica_jenis_temuan', $id_temuan);

			if($level != NULL)
				$this->db->where('tbl_pica_role.level', $level);

			if($type != NULL){
				if($type == 'up'){
					$this->db->where_not_in(" tbl_pica_role.id_pica_role "	,$id_role);
				}
			}
			$this->db->where(" tbl_pica_role.del ", 'n');
			
			$query = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function check_data_pica_role_responder($conn=NULL,$id_temuan=NULL,$isresponder=NULL,$type=NULL,$id_role=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(" tbl_pica_jenis_temuan.id_pica_jenis_temuan ,tbl_pica_jenis_temuan.jenis_temuan, tbl_pica_role.level ");
			
			$this->db->from("tbl_pica_role");
			$this->db->join('tbl_pica_jenis_temuan', 'tbl_pica_jenis_temuan.id_pica_jenis_temuan = tbl_pica_role.id_pica_jenis_temuan 
								AND tbl_pica_jenis_temuan.na=\'n\' AND tbl_pica_jenis_temuan.del=\'n\' ', 'inner');
			if($id_temuan != NULL)
				$this->db->where('tbl_pica_jenis_temuan.id_pica_jenis_temuan', $id_temuan);

			// if($level != NULL)
				$this->db->where('tbl_pica_role.isresponder', 1);

			if($type != NULL){
				if($type == 'up'){
					$this->db->where_not_in(" tbl_pica_role.id_pica_role "	,$id_role);
				}
			}
			$this->db->where(" tbl_pica_role.del ", 'n');
			
			$query = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*================================role====================================*/
		function get_data_pica_roleposisi($conn=NULL,$id=NULL,$all=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->datatables->select(" id_pica_role_posisi");
			$this->datatables->select(" id_pica_jenis_temuan");
			$this->datatables->select(" id_pica_role");
			$this->datatables->select(" id_posisi");
			$this->datatables->select(" nama_role");	
			$this->datatables->select(" nama_temuan");	
			$this->datatables->select(" jenis_temuan");	
			$this->datatables->select(" posisi");
			$this->datatables->select(" pabrik");
			$this->datatables->select(" multiple_plan");
			
			// $this->datatables->select(" tgl_edit");		
			$this->datatables->select(" login_buat");
			$this->datatables->select(" tanggal_buat");
			$this->datatables->select(" login_edit");
			$this->datatables->select(" tanggal_edit");
			$this->datatables->select(" na");
			$this->datatables->select(" del");
			
			$this->datatables->from("vw_pica_role_posisi");
			
			$where = '';
			if($id != NULL){
				$this->datatables->where('id_pica_role_posisi', $id);
			}
			if($all == NULL){
				// $this->datatables->where('tbl_pica_jenis_temuan.na', 'n');
				$this->datatables->where('del', 'n');				
			}
			if($all != NULL){
				$this->datatables->where('na', 'n');
				$this->datatables->where('del', 'n');				
			}
			
			$return = $this->datatables->generate();
			$raw = json_decode($return, true);
			$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_pica_role_posisi"));
			return $this->general->jsonify($raw);
		}

		function get_data_pica_roleposisi_normal($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL,$datacheck_2=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(" id_pica_role_posisi, id_pica_role, id_posisi, nama_role, posisi, pabrik, login_buat, tanggal_buat, login_edit, tanggal_edit, na, del, nama_temuan, jenis_temuan, id_pica_jenis_temuan");
						
			$this->db->from("vw_pica_role_posisi");

			if($typecheck !== NULL){
	        	
	        	if($typecheck=="in"){
	        		$this->db->where(" LTRIM(RTRIM(id_pica_role)) ",trim($datacheck_1));
	        		$this->db->where(" id_pica_jenis_temuan ",$datacheck_2);
	        		$this->db->where(" del ",'n');

	        	} else if($typecheck=="up"){
	        		$this->db->where(" LTRIM(RTRIM(id_pica_role)) ",trim($datacheck_1));
	        		$this->db->where(" id_pica_jenis_temuan ",$datacheck_2);
	        		$this->db->where(" del ",'n');
	        		$this->db->where_not_in(" id_pica_role_posisi ",$id);
	        	}
	        } else {

				if($id != NULL){
				$this->db->where('id_pica_role_posisi', $id);
				}
				if($all == NULL){
					// $this->datatables->where('tbl_pica_jenis_temuan.na', 'n');
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

		function delete_data_pica_rolepabrik($conn=NULL,$id=NULL){
			$this->db->where('id_pica_role_posisi', $id);
			$this->db->delete('tbl_pica_role_pabrik');
			return "success";
		}

	/*================================role====================================*/
		function get_data_pica_jenisreport($conn=NULL,$id=NULL,$all=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->datatables->select(" id_pica_jenis_report");
			$this->datatables->select(" id_pica_jenis_temuan");
			$this->datatables->select(" responder_id");
			// $this->datatables->select(" verificator_id");
			
			$this->datatables->select(" jenis_report");	
			$this->datatables->select(" jenis_temuan");
			$this->datatables->select(" temuan");
			$this->datatables->select(" requestor");
			$this->datatables->select(" responder");
			$this->datatables->select(" lama_duedate");
			// $this->datatables->select(" verificator");
			
			$this->datatables->select(" login_edit");
			$this->datatables->select(" tgl_edit");
			$this->datatables->select(" na");
			$this->datatables->select(" del");

			$this->datatables->from("vw_pica_jenis_report");
			
			$where = '';
			if($id != NULL){
				$this->datatables->where('id_pica_jenis_report', $id);
			}
			if($all == NULL){
				// $this->datatables->where('tbl_pica_jenis_temuan.na', 'n');
				$this->datatables->where('del', 'n');				
			}
			
			$return = $this->datatables->generate();
			$raw = json_decode($return, true);
			$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_pica_jenis_report"));
			return $this->general->jsonify($raw);
		}

		function get_data_pica_jenisreport_normal($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL,$datacheck_2=NULL,$idtemuan=NULL,$report=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(" id_pica_jenis_report, id_pica_jenis_temuan, responder_id, jenis_report, jenis_temuan,
								requestor, responder, lama_duedate ,login_buat, tgl_edit, na, del");
						
			$this->db->from("vw_pica_jenis_report");

			if($typecheck !== NULL){
	        	
	        	if($typecheck=="in"){
	        		$this->db->where(" LTRIM(RTRIM(jenis_report)) ",trim($datacheck_1));
	        		$this->db->where(" LTRIM(RTRIM(id_pica_jenis_temuan)) ",trim($datacheck_2));
	        	} else if($typecheck=="up"){
	        		$this->db->where(" LTRIM(RTRIM(jenis_report)) ",trim($datacheck_1));
	        		$this->db->where(" LTRIM(RTRIM(id_pica_jenis_temuan)) ",trim($datacheck_2));
	        		$this->db->where_not_in(" id_pica_jenis_report ",$id);
	        	}
	        } else {

				if($id != NULL){
					$this->db->where('id_pica_jenis_report', $id);
				}
				if($idtemuan != NULL){
					$this->db->where('id_pica_jenis_temuan', $idtemuan);
				}
				if($report != NULL){
					$this->db->where('jenis_report', $report);
				}
				if($all == NULL){
					$this->db->where('del', 'n');				
				}
			}
			$this->db->order_by('jenis_report', 'ASC');
			$query = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function delete_data_pica_reportresponse($conn=NULL,$id=NULL){
			$this->db->where('id_pica_jenis_report', $id);
			$this->db->delete('tbl_pica_jenis_report_dtl');
			return "success";
		}

		function delete_data_pica_templatereportdetail($conn=NULL,$id=NULL){
			$this->db->where('id_pica_template_header', $id);
			$this->db->delete('tbl_pica_template_detail');
			return "success";
		}

		function get_data_pica_posisi($conn=NULL,$id=NULL,$all=NULL){
			$this->general->connectDbPortal();

			$this->db->select(" id_posisi");
			$this->db->select(" LTRIM(RTRIM(posisi)) posisi");	
			$this->db->select(" na");
			$this->db->select(" del");
			
			$this->db->from("vw_pica_posisi");

			if($id != NULL){
				$this->db->where('id_posisi', $id);
			}
			if($all == NULL){
				$this->db->where('del', 'n');				
			}
			if($all != NULL){
				$this->db->where('na', 'n');
				$this->db->where('del', 'n');				
			}
			$this->db->order_by('posisi', 'ASC');
			$query = $this->db->get();
			return $query->result();
		}

		function get_data_user($user=NULL){
			$this->general->connectDbPortal();
			$this->db->select('tbl_karyawan.nik as id');
			$this->db->select('tbl_user.*');
			$this->db->select('tbl_karyawan.*');
			$this->db->from('tbl_user');
			$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'inner');
			$this->db->where('tbl_karyawan.nik', $user);		
			$this->db->where('tbl_user.na', 'n');
			$this->db->where('tbl_user.del', 'n');
			$query = $this->db->get();
			return $query->result();
		}

		function get_data_posisi($posisi=NULL){
			$this->general->connectDbPortal();
			
			$this->db->select('tbl_posisi.id_posisi as id');
			$this->db->select('tbl_karyawan.posst');
			
			$this->db->from('tbl_karyawan');
			$this->db->join('tbl_posisi', 'tbl_posisi.nama = tbl_karyawan.posst', 'inner');
			
			$this->db->like('tbl_karyawan.posst', $posisi, 'both');	
			$this->db->where('tbl_karyawan.na', 'n');
			$this->db->where('tbl_karyawan.del', 'n');
			$this->db->where('tbl_karyawan.posst IS NOT NULL');
			$this->db->where('tbl_karyawan.posst <> \'\' ');
			$this->db->where('tbl_posisi.nama IS NOT NULL ');
			$this->db->group_by('tbl_posisi.id_posisi,tbl_karyawan.posst');
			
			$query = $this->db->get();
			return $query->result();
		}


		/*function get_pabrik_oto($ho=NULL, $gsber=NULL, $except_plant=NULL){
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
		}*/

		function get_master_plant($plant_in=NULL){
			$this->db->query("SET ANSI_NULLS ON");
			$this->db->query("SET ANSI_WARNINGS ON");
			$string = 'SELECT DISTINCT ZDMMSPLANT.PERSA as plant_code,
			                    ZDMMSPLANT.WERKS as plant, 
			                    ZDMMSPLANT.NAME1 as plant_name,
			                    tbl_wf_region.region_name
			         FROM [10.0.0.32].SAPSYNC.dbo.ZDMMSPLANT
			         --FROM SAPSYNC.dbo.ZDMMSPLANT
			         LEFT JOIN tbl_wf_region ON ZDMMSPLANT.PERSA = tbl_wf_region.plant_code COLLATE SQL_Latin1_General_CP1_CS_AS
			                          AND tbl_wf_region.na = \'n\' 
			                    AND tbl_wf_region.del = \'n\'
			         WHERE 1=1';
			if($plant_in != NULL){
					if(count($plant_in) <= 1){
						$plant_in = "'".$plant_in[0]."'";
					}
					else
			       		$plant_in = "'".implode("','", $plant_in)."'";

			        $string .= ' AND ZDMMSPLANT.WERKS IN ('.$plant_in.')';
			}
			      $string .= ' ORDER BY plant ASC';

			      $query = $this->db->query($string);
			return $query->result();
		}

		function get_data_pica_detail_form($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL){
			$this->general->connectDbPortal();

			$this->db->select(" id_pica_mst_input");
			$this->db->select(" nama_form");	
			$this->db->select(" desc_form");	
			$this->db->select(" type_input");
			$this->db->select(" code_form");
			$this->db->select(" urutan_form");	
					
			$this->db->select(" login_buat");
			$this->db->select(" tgl_buat");
			$this->db->select(" login_edit");
			$this->db->select(" tgl_edit");
			$this->db->select(" na");
			$this->db->select(" del");
			
			$this->db->from("vw_pica_mst_templateDetail");

			if($typecheck !== NULL){
	        	
	        	if($typecheck=="in"){
	        		$this->db->where(" LTRIM(RTRIM(nama_role)) ",trim($datacheck_1));
	        	} else if($typecheck=="up"){
	        		$this->db->where(" LTRIM(RTRIM(nama_role)) ",trim($datacheck_1));
	        		$this->db->where_not_in(" id_pica_role ",$id);
	        	}
	        } else {

				if($id != NULL){
					$this->db->where('id_pica_role', $id);
				}
				if($all == NULL){
					$this->db->where('del', 'n');				
				}
				if($all != NULL){
					$this->db->where('na', 'n');
					$this->db->where('del', 'n');				
				}
			}
			$this->db->order_by('urutan_form', 'ASC');			
			
			$query = $this->db->get();
			return $query->result();
		}

		function get_data_pica_template($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL,$datacheck_2=NULL,$datacheck_3=NULL){
			$this->general->connectDbPortal();

			$this->datatables->select(" id_pica_template_header");
			$this->datatables->select(" id_pica_jenis_temuan");
			$this->datatables->select(" temuan");	
			$this->datatables->select(" jenis_report");
			$this->datatables->select(" buyer");
			$this->datatables->select(" jumlah_tipe");	
			$this->datatables->select(" detail_form");	
					
			$this->datatables->select(" login_buat");
			$this->datatables->select(" tgl_buat");
			$this->datatables->select(" login_edit");
			$this->datatables->select(" tgl_edit");
			$this->datatables->select(" na");
			$this->datatables->select(" del");
			
			$this->datatables->from("vw_pica_mst_templateHeader");

			if($typecheck !== NULL){
	        	
	        	if($typecheck=="in"){
	        		$this->datatables->where(" LTRIM(RTRIM(jenis_report)) ",trim($datacheck_1));
	        		$this->datatables->where(" LTRIM(RTRIM(id_pica_jenis_temuan)) ",trim($datacheck_2));
	        		$this->datatables->where(" LTRIM(RTRIM(buyer)) ",trim($datacheck_3));
	        	} else if($typecheck=="up"){
	        		$this->datatables->where(" LTRIM(RTRIM(jenis_report)) ",trim($datacheck_1));
	        		$this->datatables->where(" LTRIM(RTRIM(id_pica_jenis_temuan)) ",trim($datacheck_2));
	        		$this->datatables->where(" LTRIM(RTRIM(buyer)) ",trim($datacheck_3));
	        		$this->datatables->where_not_in(" id_pica_role ",$id);
	        	}
	        } else {

				if($id != NULL){
					$this->datatables->where('id_pica_template_header', $id);
				}
				if($all == NULL){
					$this->datatables->where('del', 'n');				
				}
				if($all != NULL){
					$this->datatables->where('na', 'n');
					$this->datatables->where('del', 'n');				
				}
			}

			$return = $this->datatables->generate();
			$raw = json_decode($return, true);
			$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_pica_template_header"));
			return $this->general->jsonify($raw);
			
		}

		function get_data_pica_template_normal($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL,$datacheck_2=NULL,$datacheck_3=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(" id_pica_template_header");
			$this->db->select(" id_pica_jenis_temuan");
			$this->db->select(" temuan");	
			$this->db->select(" jenis_report");
			$this->db->select(" buyer");
			$this->db->select(" jumlah_tipe");	
			$this->db->select(" detail_form");	
					
			$this->db->select(" login_buat");
			$this->db->select(" tgl_buat");
			$this->db->select(" login_edit");
			$this->db->select(" tgl_edit");
			$this->db->select(" na");
			$this->db->select(" del");
			
			$this->db->from("vw_pica_mst_templateHeader");

			if($typecheck !== NULL){
	        	
	        	if($typecheck=="in"){
	        		$this->db->where(" LTRIM(RTRIM(jenis_report)) ",trim($datacheck_1));
	        		$this->db->where(" LTRIM(RTRIM(id_pica_jenis_temuan)) ",trim($datacheck_2));
	        		$this->db->where(" LTRIM(RTRIM(buyer)) ",trim($datacheck_3));
	        	} else if($typecheck=="up"){
	        		$this->db->where(" LTRIM(RTRIM(jenis_report)) ",trim($datacheck_1));
	        		$this->db->where(" LTRIM(RTRIM(id_pica_jenis_temuan)) ",trim($datacheck_2));
	        		$this->db->where(" LTRIM(RTRIM(buyer)) ",trim($datacheck_3));
	        		$this->db->where_not_in(" id_pica_template_header ",$id);
	        	}
	        } else {

				if($id != NULL){
					$this->db->where('id_pica_template_header', $id);
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

		// function get_data_pica_buyer($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL){
			
		// 	$this->general->connectDbDefault();

		// 	$this->db->select(" DISTINCT (ZDMMSCUSTMR.KUNNR), ZDMMSCUSTMR.NAME1, ZDMMSCUSTMR.KUNNR + ' - ' + ZDMMSCUSTMR.NAME1 as label");
						
		// 	$this->db->from("ZDMMKTSUPP41");
		// 	$this->db->join('ZDMMSCUSTMR', 'ZDMMSCUSTMR.KUNNR = ZDMMKTSUPP41.KUNAG', 'INNER');
			
		// 	$this->db->where('ZDMMSCUSTMR.KTOKD', 'Y001');
		// 	$this->db->order_by('ZDMMSCUSTMR.NAME1', 'ASC');
			
		// 	$query = $this->db->get();
		// 	return $query->result();
		// }

		function get_data_pica_buyer($conn=NULL,$id=NULL,$all=NULL,$typecheck=NULL,$datacheck_1=NULL){
			
			$this->general->connectDbDefault();

			$this->db->select(" DISTINCT (UPPER(ZKISSTT_0127.nmbyr)) label");
						
			$this->db->from("ZKISSTT_0127");
			
			$this->db->where("ZKISSTT_0127.nmbyr != ''");
			$this->db->order_by('label', 'ASC');
			
			$query = $this->db->get();
			$result = $query->result();
			$this->general->closeDb();
			return $result;
		}

		function get_data_pica_buyer_so($conn=NULL,$id=NULL,$pabrik=NULL,$buyer=NULL,$type=NULL,$so=NULL,$lot=NULL,$pallet=NULL){
			
			$this->general->connectDbDefault();

			if($type == 'so'){
				$this->db->select( "DISTINCT (ZDMMKTSUPP47.VBELN) no_so" );
			}
			if($type == 'lot'){
				$this->db->select( "DISTINCT (ZDMMKTSUPP47.NOLOT) no_lot" );
			}
			if($type == 'pallet'){
				$this->db->select( " DISTINCT (ZDMMKTSUPP47.NOPLT) no_pallet" );
			} else if($type == NULL) {
				$this->db->select(" ZDMMKTSUPP47.VBELN as no_so,
								   ZDMMKTSUPP47.NOLOT as no_lot,
								   ZDMMKTSUPP47.NOPLT as no_pallet,
								   ZDMMKTSUPP47.PRDDT as tanggal_prod
								   --ZDMMKTSUPP01.VDATU as tgl_create_so,
								   --ZDMMKTSUPP01.FGRDT as tgl_kirim
								   ");						
			}


			$this->db->from("ZDMMKTSUPP47"); 
			
			$this->db->where("ZDMMKTSUPP47.VBELN IN (select ZDMMKTSUPP01.VBELN from ZDMMKTSUPP01 WHERE ZDMMKTSUPP01.SHPTO 
							IN (SELECT DISTINCT ZKISSTT_0127.KUNNR FROM ZKISSTT_0127 WHERE UPPER(ZKISSTT_0127.NMBYR) = '$buyer')) ");
			$this->db->where("ZDMMKTSUPP47.VBELN IS NOT NULL");
			$this->db->where("ZDMMKTSUPP47.NOLOT IS NOT NULL");
			$this->db->where("ZDMMKTSUPP47.NOLOT != '000000'");
			$this->db->where("ZDMMKTSUPP47.NOPLT IS NOT NULL");
			$this->db->where("ZDMMKTSUPP47.NOPLT != '000000'");

			if($so != ''){
				$this->db->where('ZDMMKTSUPP47.VBELN', $so);
			}

			if($lot != ''){
				$this->db->where('ZDMMKTSUPP47.NOLOT', $lot);
			}

			if($pallet != ''){
				$this->db->where('ZDMMKTSUPP47.NOPLT', $pallet);
			}

			if($pabrik != ''){
				$this->db->where('ZDMMKTSUPP47.WERKS', $pabrik);
			}
			// aktifkan ketika golive
			// $this->db->where("YEAR(ZDMMKTSUPP47.PRDDT) >= '2018' ");

			if($type == 'so'){
				$this->db->order_by('ZDMMKTSUPP47.VBELN', 'ASC');
			}
			if($type == 'lot'){
				$this->db->order_by('ZDMMKTSUPP47.NOLOT', 'ASC');
			}
			if($type == 'pallet'){
				$this->db->order_by('ZDMMKTSUPP47.NOPLT', 'ASC');
			}
			// 
			
			$query = $this->db->get();
			return $query->result();
		}

		function get_data_pica_buyer_si($conn=NULL,$id=NULL,$all=NULL,$buyer=NULL,$pabrik=NULL,$si=NULL,$lot=NULL,$pallet=NULL,$so=NULL){
			
			$this->general->connectDbDefault();
			$this->db->select("DISTINCT (ZDMMKTSUPP47.VBELN) no_so, ZDMMKTSUPP01.BSTNK no_si, ZDMMKTSUPP47.WERKS so_plant");
			if($pallet != "" && $lot != ""){
				$this->db->select("ZDMMKTSUPP47.PRDDT as date_prod");
			}	
			$this->db->from("ZDMMKTSUPP01");
			$this->db->join('ZDMMKTSUPP47','ZDMMKTSUPP47.VBELN = ZDMMKTSUPP01.VBELN', 'INNER');
			$this->db->join('ZKISSTT_0127','ZKISSTT_0127.KUNNR = ZDMMKTSUPP01.SHPTO', 'LEFT');
			if($buyer != NULL){
				$this->db->where("ZDMMKTSUPP01.SHPTO 
								IN (SELECT DISTINCT ZKISSTT_0127.KUNNR FROM ZKISSTT_0127 WHERE UPPER(ZKISSTT_0127.NMBYR) = '$buyer') ");
			}
			if($pabrik != NULL){
				$this->db->where("ZDMMKTSUPP47.WERKS = '$pabrik' ");
			}
			if($si != NULL){
				$this->db->where("ZDMMKTSUPP01.BSTNK = '$si' ");
			}
			if($pallet != "" && $lot != "" && $so != ""){
				$this->db->where("ZDMMKTSUPP47.NOLOT = '$lot' ");
				$this->db->where("ZDMMKTSUPP47.NOPLT = '$pallet' ");
				$this->db->where("ZDMMKTSUPP47.VBELN = '$so' ");
				
			}
			// aktifkan ketika golive
			// $this->db->where("YEAR(ZDMMKTSUPP47.PRDDT) >= '2018' ");
			$this->db->order_by('ZDMMKTSUPP01.BSTNK', 'ASC');			
			$query = $this->db->get();
			return $query->result();
		}

		function get_data_pica_kategori($conn=NULL,$id=NULL,$all=NULL){
			$this->general->connectDbPortal();

			$this->db->select(" id_pica_mst_kategori");
			$this->db->select(" kategori");
			$this->db->select(" desc");	
					
			$this->db->select(" login_buat");
			$this->db->select(" tanggal_buat");
			$this->db->select(" login_edit");
			$this->db->select(" tanggal_edit");
			$this->db->select(" na");
			$this->db->select(" del");
			
			$this->db->from("tbl_pica_mst_kategori");

			if($id != NULL){
				$this->db->where('tbl_pica_mst_kategori', $id);
			}
			if($all == NULL){
				$this->db->where('del', 'n');				
			}
			if($all != NULL){
				$this->db->where('na', 'n');
				$this->db->where('del', 'n');				
			}
			

			$query = $this->db->get();
			return $query->result();
		}

		function get_data_pica_workflow($user=NULL, $id_temuan=NULL, $type=NULL){
			$this->general->connectDbPortal();
			$this->db->select('*');
			$this->db->from('vw_pica_role_posisi');
			if($id_temuan != NULL){
				$this->db->where('id_pica_jenis_temuan', $id_temuan);
			}
			if($type != NULL){
				if($type == 'Responder'){
					$this->db->where('isresponder', 1);		
				} else {
					$this->db->where('nama_role', $type);
				}
			}
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');
			$query = $this->db->get();
			return $query->result();
		}

		function get_data_rolename($rolename=NULL){
			$this->general->connectDbPortal();
			$this->db->select('*');
			$this->db->from('tbl_pica_mst_rolename');
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');
			$query = $this->db->get();
			return $query->result();
		}

	}

?>
