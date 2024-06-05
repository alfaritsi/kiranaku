<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application  : PICA 
	@author       : Airiza Yuddha (7849)
	@contributor  :
		  1. <insert your fullname> (<insert your nik>) <insert the date>
			 <insert what you have modified>
		  2. <insert your fullname> (<insert your nik>) <insert the date>
			 <insert what you have modified>
		  etc.
    */

	class Dtranspica extends CI_Model {

		function get_data_pica_detail_template($conn=NULL,$id=NULL,$all=NULL,$id_header=NULL,$baris=NULL){
			$this->general->connectDbPortal();


			$this->db->select(" tbl_pica_template_detail.id_pica_template_detail,tbl_pica_template_detail.id_pica_template_header, 	   tbl_pica_template_detail.id_pica_mst_input,tbl_pica_template_detail.desc, tbl_pica_template_detail.baris ");
			$this->db->select(" tbl_pica_mst_input.nama_form, tbl_pica_mst_input.type_input, tbl_pica_mst_input.code_form,
					tbl_pica_mst_input.urutan_form");	
			$this->db->select(" tbl_pica_template_header.id_pica_jenis_temuan, tbl_pica_template_header.jenis_report, 
					tbl_pica_template_header.jumlah_tipe");
			
			$this->db->select(" tbl_pica_template_detail.login_buat");
			$this->db->select(" convert(varchar, tbl_pica_template_detail.tanggal_buat, 23) tgl_buat");
			$this->db->select(" tbl_pica_template_detail.login_edit");
			$this->db->select(" convert(varchar, tbl_pica_template_detail.tanggal_edit, 23) tgl_edit");
			$this->db->select(" tbl_pica_template_detail.na");
			$this->db->select(" tbl_pica_template_detail.del");
			
			$this->db->from("tbl_pica_template_detail");
			$this->db->join('tbl_pica_mst_input', 
							'tbl_pica_mst_input.id_pica_mst_input = tbl_pica_template_detail.id_pica_mst_input', 'INNER');
			$this->db->join('tbl_pica_template_header', 
							'tbl_pica_template_header.id_pica_template_header = tbl_pica_template_detail.id_pica_template_header', 'INNER');			
			
			if($id_header != NULL){
				$this->db->where('tbl_pica_template_detail.id_pica_template_header', $id_header);
			}
			if($baris != NULL){
				$this->db->where('tbl_pica_template_detail.baris', $baris);
			}
			$this->db->where('tbl_pica_template_detail.del', 'n');	
			$this->db->order_by('tbl_pica_template_detail.baris ASC, tbl_pica_mst_input.urutan_form ASC');			
			
			$query = $this->db->get();
			return $query->result();
		}

		function get_data_pica_detail_transaksi($conn=NULL,$id=NULL,$all=NULL,$id_header=NULL,$baris=NULL){
			$this->general->connectDbPortal();
			

			$this->db->select('DISTINCT(tbl_pica_transaksi_detail.id_pica_transaksi_detail), 
					tbl_pica_transaksi_detail.id_pica_transaksi_header, tbl_pica_transaksi_detail.id_pica_mst_input, 
					tbl_pica_transaksi_detail.desc, tbl_pica_transaksi_detail.baris,
					tbl_pica_transaksi_detail.label ');
			$this->db->select(' tbl_pica_mst_input.nama_form, tbl_pica_mst_input.type_input, 
					tbl_pica_mst_input.code_form, tbl_pica_mst_input.urutan_form');	
			$this->db->select('tbl_pica_transaksi_header.id_pica_jenis_temuan,tbl_pica_transaksi_header.jenis_report, 
					tbl_pica_transaksi_header.jumlah_baris, tbl_pica_transaksi_header.requestor');
			$this->db->select(' tbl_pica_transaksi_finding_approval.level_app posisi_finding');	
			$this->db->select(' tbl_pica_role.nama_role nama_posisi_finding');	
			$this->db->select(' tbl_posisi.nama nama_posisi_finding');	

			$this->db->select(" tbl_pica_transaksi_detail.login_buat");
			$this->db->select(" convert(varchar, tbl_pica_transaksi_detail.tanggal_buat, 23) tgl_buat");
			$this->db->select(" tbl_pica_transaksi_detail.login_edit");
			$this->db->select(" convert(varchar, tbl_pica_transaksi_detail.tanggal_edit, 23) tgl_edit");
			$this->db->select(" tbl_pica_transaksi_detail.na");
			$this->db->select(" tbl_pica_transaksi_detail.del ");
			
			$this->db->from('tbl_pica_transaksi_detail');
			$this->db->join('tbl_pica_mst_input', 
							'tbl_pica_mst_input.id_pica_mst_input = tbl_pica_transaksi_detail.id_pica_mst_input ', 'INNER');
			$this->db->join('tbl_pica_transaksi_header', 
							'tbl_pica_transaksi_header.id_pica_transaksi_header = tbl_pica_transaksi_detail.id_pica_transaksi_header ', 'INNER');
			$this->db->join('tbl_pica_transaksi_finding_approval',
							'tbl_pica_transaksi_detail.id_pica_transaksi_header = tbl_pica_transaksi_finding_approval.id_pica_transaksi_header 
							AND tbl_pica_transaksi_finding_approval.baris = tbl_pica_transaksi_detail.baris ', 'LEFT');	
			$this->db->join('tbl_pica_role',
							'tbl_pica_role.level = tbl_pica_transaksi_finding_approval.level_app 
							AND tbl_pica_role.id_pica_jenis_temuan = tbl_pica_transaksi_header.id_pica_jenis_temuan', 'LEFT');
			$this->db->join('tbl_pica_role_posisi',
							'tbl_pica_role_posisi.id_pica_role = tbl_pica_role.id_pica_role 
							AND tbl_pica_role_posisi.id_pica_jenis_temuan = tbl_pica_transaksi_header.id_pica_jenis_temuan 
							AND tbl_pica_role_posisi.na = \'n\' AND tbl_pica_role_posisi.del=\'n\'', 'LEFT');
			$this->db->join('tbl_posisi',
							'tbl_posisi.id_posisi = tbl_pica_role_posisi.id_posisi', 'LEFT');			
			
			if($id_header != NULL){
				$this->db->where('tbl_pica_transaksi_detail.id_pica_transaksi_header', $id_header);
			}
			$this->db->where('tbl_pica_transaksi_detail.del', 'n');	
			$this->db->order_by('tbl_pica_transaksi_detail.baris ASC, tbl_pica_mst_input.urutan_form ASC');			
			
			$query = $this->db->get();
			return $query->result();
		}

		function get_data_pica_normal($conn=NULL,$id=NULL,$active=NULL, $deleted=NULL, $tahun=NULL, $bulan=NULL, $pabrik=NULL,$order=NULL){
			$this->general->connectDbPortal();

			$this->db->select(" * ");
			
			$this->db->from("tbl_pica_transaksi_header");
			
			if($id != NULL){
				$this->db->where('id_pica_transaksi_header', $id);
			}
			if($tahun != NULL){
				$this->db->where('YEAR(date_from) ', $tahun);
			}
			if($bulan != NULL){
				$this->db->where('MONTH(date_from) ', $bulan);
			}
			if($pabrik != NULL){
				$this->db->where('pabrik', $pabrik);
			}
			$this->db->where('del', 'n');	
			$this->db->order_by('id_pica_transaksi_header '.$order);		
			
			$query = $this->db->get();
			return $query->result();
		}

		function check_data_pica($conn=NULL,$id=NULL,$active=NULL, $typecheck=NULL,$datacheck_1=NULL,$datacheck_2=NULL,$datacheck_3=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(" * ");
			
			$this->db->from("tbl_pica_transaksi_header");

			if($typecheck !== NULL){
	        	
	        	if($typecheck=="in"){
	        		$this->db->where(" LTRIM(RTRIM(number)) ",trim($datacheck_1));
	        		$this->db->where(" LTRIM(RTRIM(pabrik)) ",trim($datacheck_2));
	        		$this->db->where(" LTRIM(RTRIM(buyer)) ",trim($datacheck_3));
	        	} else if($typecheck=="up"){
	        		$this->db->where(" LTRIM(RTRIM(number)) ",trim($datacheck_1));
	        		$this->db->where(" LTRIM(RTRIM(pabrik)) ",trim($datacheck_2));
	        		$this->db->where(" LTRIM(RTRIM(buyer)) ",trim($datacheck_3));
	        		$this->db->where_not_in(" id_pica_transaksi_header ",$id);
	        	}
	        }
			
			$this->db->where('del', 'n');	
			
			$query = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_pica_list_data_app($conn=NULL,$id=NULL,$all=NULL, $number=NULL, $pica_status=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			
			$this->datatables->select(" id_pica_transaksi_header");
			$this->datatables->select(" id_pica_jenis_temuan");
			$this->datatables->select(" id_pica_kategori");
			// $this->datatables->select(" verificator_id");
			
			$this->datatables->select(" number");	
			$this->datatables->select(" requestor");	
			$this->datatables->select(" pabrik");
			$this->datatables->select(" jenis_report");
			$this->datatables->select(" pica_file");
			$this->datatables->select(" date_from");
			$this->datatables->select(" jumlah_baris");
			$this->datatables->select(" temuan");
			$this->datatables->select(" kategori");
			$this->datatables->select(" buyer");
			$this->datatables->select(" next_nik");
			$this->datatables->select(" pica_status");
			$this->datatables->select(" verificator");
			$this->datatables->select(" nama_role");
			$this->datatables->select(" role_posisi");
			// $this->datatables->select(" verifikator");
			// $this->datatables->select(" verificator");
			
			$this->datatables->select(" login_buat");
			$this->datatables->select(" login_edit");
			$this->datatables->select(" tgl_edit");
			$this->datatables->select(" na");
			$this->datatables->select(" del");

			$this->datatables->from("vw_pica_show_list_pica");
			
			$where = '';
			if($id != NULL){
				$this->datatables->where('id_pica_transaksi_header', $id);
			}
			
			if($number != NULL){
				$this->datatables->where_in('number', $number);
			}
			if($all == NULL){
				// $this->datatables->where('tbl_pica_jenis_temuan.na', 'n');
				$this->datatables->where('del', 'n');				
			} else {
				$this->datatables->where('na', 'n');
				$this->datatables->where('del', 'n');
			}
			
			$return 		= $this->datatables->generate();
			$raw 			= json_decode($return, true);
			$raw['data'] 	= $this->general->generate_encrypt_json($raw['data'], array("id_pica_transaksi_header"));
			$result 		= $this->general->jsonify($raw);
			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*================================role====================================*/
		function get_data_pica_list_data($conn=NULL,$id=NULL,$all=NULL, $pabrik=NULL, $pica_status=NULL, $role=NULL,$oto_temuan=NULL,$gedung=NULL ,$number=NULL,$filter_pabrik=NULL,$filter_report=NULL,$filter_temuan=NULL,$filter_buyer=NULL,$filter_no=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			
			$this->datatables->select(" id_pica_transaksi_header");
			$this->datatables->select(" id_pica_jenis_temuan");
			$this->datatables->select(" id_pica_kategori");
			// $this->datatables->select(" verificator_id");
			
			$this->datatables->select(" number");	
			$this->datatables->select(" requestor");	
			$this->datatables->select(" pabrik");
			$this->datatables->select(" jenis_report");
			$this->datatables->select(" pica_file");
			$this->datatables->select(" date_from");
			$this->datatables->select(" jumlah_baris");
			$this->datatables->select(" temuan");
			$this->datatables->select(" kategori");
			$this->datatables->select(" buyer");
			$this->datatables->select(" next_nik");
			$this->datatables->select(" pica_status");
			$this->datatables->select(" verificator");
			$this->datatables->select(" nama_role");
			$this->datatables->select(" role_posisi");
			$this->datatables->select(" finding");
			// $this->datatables->select(" verifikator");
			// $this->datatables->select(" verificator");
			
			$this->datatables->select(" login_buat");
			$this->datatables->select(" login_edit");
			$this->datatables->select(" tgl_edit");
			$this->datatables->select(" na");
			$this->datatables->select(" del");

			$this->datatables->from("vw_pica_show_list_pica");
			
			$where = '';
			if($id != NULL){
				$this->datatables->where('id_pica_transaksi_header', $id);
			}
			if($pabrik != NULL){
				if($gedung != NULL){
					if($gedung == 'n'){
						$this->datatables->where_in('pabrik', base64_decode($this->session->userdata("-gsber-")));	
					} else {
						$this->datatables->where_in('pabrik', explode(",", $pabrik ));
					}
				} else {
					$this->datatables->where_in('pabrik', explode(",", $pabrik ));	
				}
				
			}
			if($pica_status != NULL){
				$this->datatables->where_in('pica_status', $pica_status);
			}
			if($oto_temuan != NULL){
				$this->datatables->where_in('id_pica_jenis_temuan', explode(",", $oto_temuan ));
			}
			if($number != NULL){
				$this->db->where_in('number', $number);
			}
			if($all == NULL){
				$this->datatables->where('del', 'n');				
			} else {
				$this->datatables->where('na', 'n');
				$this->datatables->where('del', 'n');
			}
			if($filter_pabrik != NULL){
				$this->datatables->where_in('pabrik', $filter_pabrik);
			}
			if($filter_report != NULL){
				$this->datatables->where_in('jenis_report', $filter_report);
			}
			if($filter_temuan != NULL){
				$this->datatables->where_in('temuan', $filter_temuan);
			}
			if($filter_buyer != NULL){
				$this->datatables->where_in('buyer', $filter_buyer);
			}
			if($filter_no != NULL){
				$this->datatables->where_in('number', $filter_no);
			}


			
			$return 		= $this->datatables->generate();
			$raw 			= json_decode($return, true);
			$raw['data'] 	= $this->general->generate_encrypt_json($raw['data'], array("id_pica_transaksi_header"));
			$result 		= $this->general->jsonify($raw);
			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_pica_list_data_normal($conn=NULL,$id=NULL,$all=NULL, $pabrik=NULL, $pica_status=NULL, $role=NULL,$oto_temuan=NULL,$gedung=NULL,$number=NULL,$filter_pabrik=NULL,$filter_report=NULL,$filter_temuan=NULL,$filter_buyer=NULL,$filter_no=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->db->select(" id_pica_transaksi_header");
			$this->db->select(" id_pica_transaksi_header id_header");
			$this->db->select(" id_pica_jenis_temuan");
			$this->db->select(" id_pica_kategori");
			// $this->db->select(" verificator_id");
			
			$this->db->select(" number");	
			$this->db->select(" requestor");	
			$this->db->select(" pabrik");
			$this->db->select(" jenis_report");
			$this->db->select(" pica_file");
			$this->db->select(" date_from");
			$this->db->select(" jumlah_baris");
			$this->db->select(" temuan");
			$this->db->select(" kategori");
			$this->db->select(" buyer");
			// $this->db->select(" verifikator");
			$this->db->select(" verificator_posisi");
			$this->db->select(" verificator");
			// $this->db->select(" verificator");
			$this->db->select(" si");
			$this->db->select(" lot");
			$this->db->select(" pallet");
			$this->db->select(" so");
			$this->db->select(" date_prod");
			$this->db->select(" desc");
			$this->db->select(" next_nik");
			$this->db->select(" pica_status");
			
			$this->db->select(" login_buat");
			$this->db->select(" login_edit");
			$this->db->select(" tgl_edit");
			$this->db->select(" na");
			$this->db->select(" del");

			$this->db->from("vw_pica_show_list_pica");
			
			$where = '';
			if($id != NULL){
				$this->db->where('id_pica_transaksi_header', $id);
			}
			if($pabrik != NULL){
				$this->db->where_in('pabrik', explode(",", $pabrik ));
			}
			if($pica_status != NULL){
				$this->db->where('pica_status', $pica_status);
			}
			if($oto_temuan != NULL){
				$this->db->where_in('id_pica_jenis_temuan', explode(",", $oto_temuan ));
			}
			if($number != NULL){
				$this->db->where_in('number', explode(",", $number ));
			}
			
			if($all == NULL){
				// $this->db->where('tbl_pica_jenis_temuan.na', 'n');
				$this->db->where('del', 'n');				
			} else {
				$this->db->where('na', 'n');
				$this->db->where('del', 'n');
			}
			
			$query = $this->db->get();
			
				return $query->result();


		}

		function get_data_pica_list_data_normal_finding($conn=NULL,$id=NULL,$all=NULL, $pabrik=NULL, $pica_status=NULL, $role=NULL,$oto_temuan=NULL,$gedung=NULL,$number=NULL,$filter_pabrik=NULL,$filter_report=NULL,$filter_temuan=NULL,$filter_buyer=NULL,$filter_no=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->db->select(" vw_pica_show_list_pica.id_pica_transaksi_header");
			$this->db->select(" vw_pica_show_list_pica.id_pica_transaksi_header id_header");
			$this->db->select(" vw_pica_show_list_pica.id_pica_jenis_temuan");
			$this->db->select(" vw_pica_show_list_pica.id_pica_kategori");
			// $this->db->select(" verificator_id");
			
			$this->db->select(" vw_pica_show_list_pica.number");	
			$this->db->select(" vw_pica_show_list_pica.requestor");	
			$this->db->select(" vw_pica_show_list_pica.pabrik");
			$this->db->select(" vw_pica_show_list_pica.jenis_report");
			$this->db->select(" vw_pica_show_list_pica.pica_file");
			$this->db->select(" vw_pica_show_list_pica.date_from");
			$this->db->select(" vw_pica_show_list_pica.jumlah_baris");
			$this->db->select(" vw_pica_show_list_pica.temuan");
			$this->db->select(" vw_pica_show_list_pica.kategori");
			$this->db->select(" vw_pica_show_list_pica.buyer");
			// $this->db->select(" verifikator");
			$this->db->select(" vw_pica_show_list_pica.verificator_posisi");
			$this->db->select(" vw_pica_show_list_pica.verificator");
			// $this->db->select(" verificator");
			$this->db->select(" vw_pica_show_list_pica.si");
			$this->db->select(" vw_pica_show_list_pica.lot");
			$this->db->select(" vw_pica_show_list_pica.pallet");
			$this->db->select(" vw_pica_show_list_pica.so");
			$this->db->select(" vw_pica_show_list_pica.date_prod");
			$this->db->select(" vw_pica_show_list_pica.desc");
			$this->db->select(" vw_pica_show_list_pica.next_nik");
			$this->db->select(" vw_pica_show_list_pica.pica_status");
			
			$this->db->select(" vw_pica_show_list_pica.login_buat");
			$this->db->select(" vw_pica_show_list_pica.login_edit");
			$this->db->select(" vw_pica_show_list_pica.tgl_edit");
			$this->db->select(" vw_pica_show_list_pica.na");
			$this->db->select(" vw_pica_show_list_pica.del");

			$this->db->select(" tbl_pica_transaksi_finding_approval.id_pica_transaksi_finding_approval");
			$this->db->select(" tbl_pica_transaksi_finding_approval.baris");
			$this->db->select(" tbl_pica_transaksi_finding_approval.status");
			$this->db->select(" tbl_pica_transaksi_finding_approval.level_app");

			$this->db->from("vw_pica_show_list_pica");
			$this->db->join('tbl_pica_transaksi_finding_approval', 
				'tbl_pica_transaksi_finding_approval.id_pica_transaksi_header = vw_pica_show_list_pica.id_pica_transaksi_header', 'LEFT');
			
			
			$where = '';
			if($id != NULL){
				$this->db->where('vw_pica_show_list_pica.id_pica_transaksi_header', $id);
			}
			if($pabrik != NULL){
				$this->db->where_in('vw_pica_show_list_pica.pabrik', explode(",", $pabrik ));
			}
			if($pica_status != NULL){
				$this->db->where('vw_pica_show_list_pica.pica_status', $pica_status);
			}
			if($oto_temuan != NULL){
				$this->db->where_in('vw_pica_show_list_pica.id_pica_jenis_temuan', explode(",", $oto_temuan ));
			}
			if($number != NULL){
				$this->db->where_in('vw_pica_show_list_pica.number', explode(",", $number ));
			}
			
			if($all == NULL){
				// $this->db->where('tbl_pica_jenis_temuan.na', 'n');
				$this->db->where('vw_pica_show_list_pica.del', 'n');				
			} else {
				$this->db->where('vw_pica_show_list_pica.na', 'n');
				$this->db->where('vw_pica_show_list_pica.del', 'n');
			}
			
			$query = $this->db->get();
			
				return $query->result();


		}

		function get_data_pica_list_data_detail($conn=NULL,$id=NULL,$all=NULL){
			if ($conn !== NULL)
			$this->general->connectDbPortal();
			
			$this->db->select(" id_pica_transaksi_header");
			$this->db->select(" id_pica_transaksi_header id_header");
			$this->db->select(" id_pica_jenis_temuan");
			$this->db->select(" id_pica_kategori");
			// $this->db->select(" verificator_id");
			
			$this->db->select(" number");	
			$this->db->select(" requestor");	
			$this->db->select(" pabrik");
			$this->db->select(" jenis_report");
			$this->db->select(" pica_file");
			$this->db->select(" date_from");
			$this->db->select(" jumlah_baris");
			$this->db->select(" temuan");
			$this->db->select(" kategori");
			$this->db->select(" buyer");
			// $this->db->select(" verifikator");
			$this->db->select(" verificator_posisi");
			$this->db->select(" verificator");
			// $this->db->select(" verificator");
			$this->db->select(" si");
			$this->db->select(" lot");
			$this->db->select(" pallet");
			$this->db->select(" so");
			$this->db->select(" date_prod");
			$this->db->select(" desc");
			$this->db->select(" next_nik");
			$this->db->select(" pica_status");
			
			$this->db->select(" login_buat");
			$this->db->select(" login_edit");
			$this->db->select(" tgl_edit");
			$this->db->select(" na");
			$this->db->select(" del");

			$this->db->from("vw_pica_show_list_pica");
			
			$where = '';
			if($id != NULL){
				$this->db->where('id_pica_transaksi_header', $id);
			}
			if($all == NULL){
				// $this->db->where('tbl_pica_jenis_temuan.na', 'n');
				$this->db->where('del', 'n');				
			} else {
				$this->db->where('na', 'n');
				$this->db->where('del', 'n');
			}
			
			$query = $this->db->get();			
			return $query->row();
		}

		function get_data_pica_list_data_log($conn=NULL,$id=NULL,$all=NULL, $not_in=NULL, $var_not_in=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			
			$this->db->select(" *, convert(varchar, date_action, 104) date, b.nama user_log, UPPER(tbl_pica_log.action) action  ");
			

			$this->db->from("tbl_pica_log");
			$this->db->join('tbl_karyawan b', 'b.id_karyawan = tbl_pica_log.pic', 'inner');
			
			$where = '';
			if($id != NULL){
				$this->db->where('tbl_pica_log.id_pica_header', $id);
			}
			if($not_in != NULL){
				$this->db->where_not_in('tbl_pica_log.'.$var_not_in, $not_in) ;
			}
			
			if($all == NULL){
				$this->db->where('tbl_pica_log.del', 'n');				
			} else {
				$this->db->where('tbl_pica_log.na', 'n');
				$this->db->where('tbl_pica_log.del', 'n');
			}

			$this->db->order_by('tbl_pica_log.id_pica_log DESC');
			
			$query 	= $this->db->get();
			$result = $query->result();
			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;

		}

		function get_data_posisi($status_pica=NULL,$id_verifikator=NULL,$id_temuan=NULL,$requestor=NULL,$jenis_report=NULL){
			
			$this->db->select(" id_pica_jenis_report, id_pica_jenis_temuan, responder_id, jenis_report, jenis_temuan,
								requestor, responder, lama_duedate ,login_buat, tgl_edit, na, del");
						
			$this->db->from("vw_pica_jenis_report");
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');

			if($id_temuan != NULL){
				$this->db->where_in('id_pica_jenis_temuan', $id_temuan);
			}
			if($requestor != NULL){
				$this->db->where_in('requestor', $requestor);
			}
			if($jenis_report != NULL){
				$this->db->where_in('jenis_report', $jenis_report);
			}

			$query 	= $this->db->get();
			return $query->row();
		}

		function get_data_pica_otorisasi($conn=NULL,$posisi=NULL,$id_temuan=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			
			$this->db->select(' b.nama_role, b.level, b.id_pica_jenis_temuan, a.id_posisi, c.nama, b.if_approve, b.if_decline,
								d.kode_pabrik pabrik
								/*CAST( 
							  			( 
							  			  SELECT CAST((y.kode_pabrik) as varchar(10) ) + RTRIM(\',\') 
							  				FROM tbl_pica_role_pabrik y
							          WHERE  y.id_pica_role_posisi = a.id_pica_role_posisi
							          AND y.id_pica_role_posisi = a.id_pica_role_posisi
							  			  FOR XML PATH (\'\') 
							  			) as VARCHAR(MAX) 
								  	) as pabrik*/
								');
			$this->db->from('tbl_pica_role_posisi a');
			$this->db->join('tbl_pica_role b', 'b.id_pica_role = a.id_pica_role', 'inner');
			$this->db->join('tbl_posisi c', 'c.id_posisi = a.id_posisi', 'inner');
			$this->db->join('tbl_pica_role_pabrik d', 'd.id_pica_role_posisi = a.id_pica_role_posisi
							AND d.id_pica_role_posisi = a.id_pica_role_posisi', 'LEFT');
							
			if($posisi != NULL){
				$this->db->where_in('c.nama', $posisi);
			}
			if($id_temuan != NULL){
				$this->db->where('b.id_pica_jenis_temuan', $id_temuan);
			}

			$this->db->where('a.na', 'n');
			$this->db->where('a.del', 'n');
			$this->db->where('b.na', 'n');
			$this->db->where('b.del', 'n');
				
						
			$query 	= $this->db->get();
			$result = $query->result();
			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_pica_otorisasi_session($conn=NULL,$posisi=NULL,$id_temuan=NULL,$gedung=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			
				$this->db->select(' b.nama_role, b.level, b.id_pica_jenis_temuan, a.id_posisi, c.nama, b.if_approve, b.if_decline,
									--d.kode_pabrik pabrik
									CAST( 
								  			( 
								  			  SELECT CAST((y.kode_pabrik) as varchar(10) ) + RTRIM(\',\') 
								  				FROM tbl_pica_role_pabrik y
								          WHERE  y.id_pica_role_posisi = a.id_pica_role_posisi
								          AND y.id_pica_role_posisi = a.id_pica_role_posisi
								  			  FOR XML PATH (\'\') 
								  			) as VARCHAR(MAX) 
									  	) as pabrik
									');
				// $this->db->select('tbl_karyawan.posst, tbl_karyawan.nik');				
				$this->db->from('tbl_pica_role_posisi a');
				$this->db->join('tbl_pica_role b', 'b.id_pica_role = a.id_pica_role', 'inner');
				$this->db->join('tbl_posisi c', 'c.id_posisi = a.id_posisi', 'inner');
				/*$this->db->join('tbl_pica_role_pabrik d', 'd.id_pica_role_posisi = a.id_pica_role_posisi
								AND d.id_pica_role_posisi = a.id_pica_role_posisi', 'LEFT');*/
								
				if($posisi != NULL){
					$this->db->where_in('c.nama', $posisi);
				}
				if($id_temuan != NULL){
					$this->db->where('b.id_pica_jenis_temuan', $id_temuan);
				}
				$this->db->where('a.na', 'n');
				$this->db->where('a.del', 'n');
				$this->db->where('b.na', 'n');
				$this->db->where('b.del', 'n');
				
						
			$query 	= $this->db->get();
			$result = $query->result();
			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_akses_pabrik($conn=NULL, $posisi=NULL, $id_temuan=NULL, $level=NULL,$show=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(' DISTINCT (tbl_pica_role_posisi.id_pica_role_posisi)');
			$this->db->select(" tbl_pica_role_posisi.id_pica_jenis_temuan, tbl_pica_role.level,
								tbl_posisi.id_posisi , tbl_posisi.nama posisi, 
								tbl_pica_role.id_pica_role id_pica_role, tbl_pica_role.nama_role nama_role,
								CAST((SELECT DISTINCT y.kode_pabrik + RTRIM(',') FROM tbl_pica_role_posisi x
								            LEFT JOIN tbl_pica_role_pabrik y ON y.id_pica_role_posisi = x.id_pica_role_posisi
											WHERE x.id_pica_role_posisi = tbl_pica_role_posisi.id_pica_role_posisi 
								                AND x.del = 'n' AND y.na = 'n' AND y.del = 'n'
									FOR XML PATH ('')) as VARCHAR(MAX)) AS pabrik,
								tbl_pica_role_posisi.tanggal_buat, tbl_pica_role_posisi.login_buat, 
								tbl_pica_role_posisi.tanggal_edit, tbl_pica_role_posisi.login_edit,
								tbl_pica_role_posisi.na, tbl_pica_role_posisi.del,
								temuan.jenis_temuan+' - '+temuan.requestor nama_temuan, temuan.jenis_temuan 
							");
					
			$this->db->from('tbl_pica_role_posisi');
			$this->db->join('tbl_posisi', 'tbl_posisi.id_posisi = tbl_pica_role_posisi.id_posisi', 'inner');
			$this->db->join('tbl_pica_role', "tbl_pica_role.id_pica_role = tbl_pica_role_posisi.id_pica_role 
												AND tbl_pica_role.na='n' AND tbl_pica_role.del='n' ", 'inner');
			$this->db->join('tbl_pica_jenis_temuan temuan', "temuan.id_pica_jenis_temuan = tbl_pica_role_posisi.id_pica_jenis_temuan 
																AND temuan.na='n' AND temuan.del='n' ", 'LEFT');
								
			if($posisi != NULL){
				$this->db->where('tbl_posisi.nama ', $posisi);
			}
			if($id_temuan != NULL){
				$this->db->where('tbl_pica_role_posisi.id_pica_jenis_temuan', $id_temuan);
			}
			if($level != NULL){
				$this->db->where('tbl_pica_role.level', $level);
			}	
			$this->db->where('tbl_pica_role_posisi.na', 'n');
			$this->db->where('tbl_pica_role_posisi.del', 'n');
			$query = $this->db->get();

			if($show != 'single'){
				// echo "aaa";
				$result = $query->result();
			} else {
				// echo "bbb";
				$result = $query->row();
			}
			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
			// return $query->row();
			
		}

		function get_data_karyawan($status_pica=NULL,$id_posisi=NULL,$pabrik=NULL){
			// $this->general->connectDbPortal();
			$this->db->select('tbl_posisi.id_posisi as id');
			$this->db->select('tbl_karyawan.posst, tbl_karyawan.nik, tbl_karyawan.nama nama_karyawan, tbl_karyawan.email');				
			$this->db->from('tbl_karyawan');
			$this->db->join('tbl_posisi', 'tbl_posisi.nama = tbl_karyawan.posst', 'inner');
			
			if($id_posisi != NULL){
				$this->db->where_in('tbl_posisi.id_posisi', $id_posisi);
			}
			if($pabrik != NULL){
				$this->db->where_in('tbl_karyawan.id_gedung', $pabrik);
			}

			$this->db->where('tbl_karyawan.na', 'n');
			$this->db->where('tbl_karyawan.del', 'n');
			$this->db->where('tbl_karyawan.posst IS NOT NULL');
			$this->db->where('tbl_karyawan.posst <> \'\' ');
			$this->db->where('tbl_posisi.nama IS NOT NULL ');			
						
			$query = $this->db->get();
			return $query->result();
		}

		function check_data_posisi($conn=NULL,$id_posisi=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select(' DISTINCT (tbl_posisi.nama) ');
			$this->db->select(' tbl_karyawan.id_gedung ');
			$this->db->from("tbl_posisi");
			$this->db->join('tbl_karyawan', 'tbl_karyawan.posst = tbl_posisi.nama', 'inner');
			if($id_posisi != NULL){
				$this->db->where_in('tbl_posisi.id_posisi', $id_posisi);
			}    		
			$this->db->where('tbl_posisi.na', 'n');	
			$this->db->where('tbl_posisi.del', 'n');	
			$query 	= $this->db->get();
			$result	= $query->result();
			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_app_finding($conn=NULL,$id_header=NULL){
			// if ($conn !== NULL)
				// $this->general->connectDbPortal();
			$this->db->select(' baris , status, date_approval, level_app ');
			// $this->db->select(' tbl_karyawan.id_gedung ');
			$this->db->from("tbl_pica_transaksi_finding_approval");
			// $this->db->join('tbl_karyawan', 'tbl_karyawan.posst = tbl_posisi.nama', 'inner');
			if($id_header != NULL){
				$this->db->where_in('id_pica_transaksi_header', $id_header);
			}    		
			$this->db->where('na', 'n');	
			$this->db->where('del', 'n');
			$this->db->order_by('baris ASC');	
			$query 	= $this->db->get();
			$result	= $query->result();
			// if ($conn !== NULL)
				// $this->general->closeDb();
			return $result;
		}

		function delete_data_pica_detail($conn=NULL,$id=NULL){
			$this->db->where_in('id_pica_transaksi_detail', $id);
			$this->db->delete('tbl_pica_transaksi_detail');
			return "success";
		}

		function delete_data_pica_finding($conn=NULL,$id=NULL,$baris=NULL,$id_except=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			$this->db->where_in(' id_pica_transaksi_header', $id);
			if($baris !== NULL)
				$this->db->where('baris', $baris);
			if($id_except !== NULL)
				$this->db->where_not_in(' id_pica_transaksi_finding_approval', $id_except);
			$this->db->delete('tbl_pica_transaksi_finding_approval');
			if ($conn !== NULL)
				$this->general->closeDb();

			return "success";
		}

		function get_data_pica_app_last_hist($conn=NULL,$id=NULL,$all=NULL, $id_header=NULL, $baris=NULL, $field=NULL){
			// if ($conn !== NULL)
			// $this->general->connectDbPortal();
			
			$this->db->select(' TOP 1 ('.$field.') ');
			// $this->db->select(" id_pica_transaksi_header id_header");
			// $this->db->select(" id_pica_jenis_temuan");
			// $this->db->select(" id_pica_kategori");
			// $this->db->select(" verificator_id");
			
			// $this->db->select(" login_buat");
			// $this->db->select(" login_edit");
			// $this->db->select(" tgl_edit");
			// $this->db->select(" na");
			// $this->db->select(" del");

			$this->db->from("tbl_pica_transaksi_finding_approval_history");
			
			if($id != NULL){
				$this->db->where('id_pica_transaksi_finding_approval_history', $id);
			}
			if($id_header != NULL){
				$this->db->where('id_pica_transaksi_header', $id_header);
			}
			if($baris != NULL){
				$this->db->where('baris', $baris);
			}
			
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');

			$this->db->order_by('id_pica_transaksi_finding_approval_history DESC');
			
			$query = $this->db->get();			
			return $query->row();
		}



	}
?>
