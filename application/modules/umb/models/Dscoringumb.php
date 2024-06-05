<?php
	/*
        @application  : UMB (Uang Muka Bokar)
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 25-Sep-18
        @contributor  :
              1.<insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2.<insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc...
    */

	class Dscoringumb extends CI_Model {
		
		function get_historical_supply($conn = NULL, $lifnr = NULL, $depo = NULL, $plant = NULL, $tipe=NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL){
			if ($conn !== NULL)
				$this->general->connectDbDefault();
			
			
			$this->db->select('ZDMPURBKR01.LIFNR');
			$this->db->select('ZDMPURBKR01.NMDPO');
			$this->db->select('ZDMPURBKR01.EKORG');
			$this->db->select('DATENAME(month, ZDMPURBKR01.BEDAT) as bulan');
			$this->db->select('ZDMPURBKR01.BEDAT as tanggal_po');
			$this->db->select('ZDMPURBKR01.QTFAK as qty_kering');
			$this->db->select('ZDMPURBKR01.AVCS3 as nilai_po');
			$this->db->select('COALESCE(ZDMPURBKR01.AVCS3 / NULLIF(ZDMPURBKR01.QTFAK,0), 0) as harga');
			$this->db->from('ZDMPURBKR01');
			if ($plant !== NULL) {
				// $this->db->LIKE('ZDMPURBKR01.KTOKK', 'A', 'before');
				$this->db->where('ZDMPURBKR01.EKORG', $plant);
			}
			if ($lifnr !== NULL) {
				$this->db->where('ZDMPURBKR01.LIFNR', $lifnr);
				// $this->db->where('ZDMPURBKR01.NMDPO', $depo);

			}

			if ($depo !== NULL) {
				$this->db->where('ZDMPURBKR01.NMDPO', $depo);
			}

			if ($tanggal_awal !== NULL) {
				$this->db->where('ZDMPURBKR01.BEDAT >=', $tanggal_awal);
			}

			if ($tanggal_akhir !== NULL) {
				$this->db->where('ZDMPURBKR01.BEDAT <=', $tanggal_akhir);
			}

			$this->db->order_by('ZDMPURBKR01.BEDAT DESC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;

		}

		function get_sum_supply($conn = NULL, $lifnr = NULL, $depo = NULL, $plant = NULL, $tipe=NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL){
			if ($conn !== NULL)
				$this->general->connectDbDefault();
			
			
			$this->db->select('SUM(ZDMPURBKR01.QTFAK) as sum_qty');
			$this->db->select('SUM(ZDMPURBKR01.AVCS3) as sum_npo');
			$this->db->from('ZDMPURBKR01');
			if ($plant !== NULL) {
				// $this->db->LIKE('ZDMPURBKR01.KTOKK', 'A', 'before');
				$this->db->where('ZDMPURBKR01.EKORG', $plant);
			}
			if ($lifnr !== NULL) {
				$this->db->where('ZDMPURBKR01.LIFNR', $lifnr);
				// $this->db->where('ZDMPURBKR01.NMDPO', $depo);

			}

			if ($depo !== NULL) {
				$this->db->where('ZDMPURBKR01.NMDPO', $depo);
			}

			if ($tanggal_awal !== NULL) {
				$this->db->where('ZDMPURBKR01.BEDAT >=', $tanggal_awal);
			}

			if ($tanggal_akhir !== NULL) {
				$this->db->where('ZDMPURBKR01.BEDAT <=', $tanggal_akhir);
			}

			$query  = $this->db->get();
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;

		}

		function get_historical_um($conn = NULL, $lifnr = NULL, $depo = NULL, $plant = NULL, $tipe=NULL, $tanggal_awal = NULL, $tanggal_akhir = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("vw_umb_scoring_header.*");
			$this->db->from("vw_umb_scoring_header");

			$this->db->where("status in ('completed', 'finish')");

			if($plant !== NULL) {
				$this->db->where("plant", $plant);
				
			}
			if($tipe !== NULL) {
				$this->db->where("id_scoring_tipe", $tipe);
			}

			if($depo !== NULL) {
				$this->db->where("depo", $depo);
			}

			if($lifnr !== NULL) {
				$this->db->where("kode_supplier", $lifnr);
			}

			if ($tanggal_awal !== NULL) {
				$this->db->where('tanggal >=', $tanggal_awal);
			}

			if ($tanggal_akhir !== NULL) {
				$this->db->where('tanggal <=', $tanggal_akhir);
			}
	

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}
		
		function get_data_vendor($conn = NULL, $plant = NULL, $lifnr = NULL, $desc = NULL, $limit_suplai = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbDefault();

			$this->db->query("SET ANSI_NULLS ON");
			$this->db->query("SET ANSI_WARNINGS ON");

			$this->db->select('ZDMMSVENDOR.LIFNR as id');
			$this->db->select('ZDMMSVENDOR.*');
			$this->db->from('ZDMMSVENDOR');
			if ($plant !== NULL) {
				$this->db->LIKE('ZDMMSVENDOR.KTOKK', 'A', 'before');
				$this->db->where('ZDMMSVENDOR.EKORG', $plant);
			}
			if ($lifnr !== NULL) {
				$this->db->where('ZDMMSVENDOR.LIFNR', $lifnr);
			}
			if ($desc !== NULL) {
				$this->db->like('LOWER(ZDMMSVENDOR.NAME1)', $desc, 'both');
			}
			if ($limit_suplai !== NULL) {
				// $this->db->where('(SELECT COUNT(DISTINCT ZDMPURBKR01.LIFNR)
				// 					 FROM SAPSYNC.dbo.ZDMPURBKR01
				// 					WHERE ZDMPURBKR01.LIFNR = ZDMMSVENDOR.LIFNR
				// 				      AND ZDMPURBKR01.BEDAT >= DATEADD(MONTH,-' . $limit_suplai . ', CONVERT(DATE, GETDATE()))) > 0');
				$this->db->where('(SELECT COUNT(DISTINCT ZDMPURBKR01.LIFNR)
									 FROM ZDMPURBKR01
									WHERE ZDMPURBKR01.LIFNR = ZDMMSVENDOR.LIFNR
								      AND ZDMPURBKR01.BEDAT >= DATEADD(MONTH,-' . $limit_suplai . ', CONVERT(DATE, \'03-01-2020\'))) > 0');
			}

			// $this->db->where('ZDMMSVENDOR.LIFNR SQL_Latin1_General_CP1_CI_AS NOT IN (SELECT DISTINCT(t1.kode_supplier) 
			 											 //  FROM portal_dev.dbo.tbl_umb_scoring_header t1 
			 											 // WHERE t1.status NOT IN(\'completed\', \'drop\',\'stop\', \'finish\') AND t1.kode_supplier IS NOT NULL)');
			

			$this->db->order_by('ZDMMSVENDOR.NAME1 ASC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_vendor_nonbkr($conn = NULL, $plant = NULL, $lifnr = NULL, $desc = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbDefault();

			$this->db->select('ZDMNONBKRVENDOR.LIFNR as id');
			$this->db->select('ZDMNONBKRVENDOR.*');
			$this->db->from('ZDMNONBKRVENDOR');
			if ($plant !== NULL) {
				$this->db->where('ZDMNONBKRVENDOR.EKORG', $plant);
			}
			if ($lifnr !== NULL) {
				$this->db->where('ZDMNONBKRVENDOR.LIFNR', $lifnr);
			}
			if ($desc !== NULL) {
				$this->db->like('LOWER(ZDMNONBKRVENDOR.NAME1)', $desc, 'both');
			}
			$this->db->order_by('ZDMNONBKRVENDOR.NAME1 ASC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_ranger_dirops($conn = NULL, $plant = NULL, $lifnr = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbDefault();

			$this->db->query("SET ANSI_NULLS ON");
			$this->db->query("SET ANSI_WARNINGS ON");

			$this->db->select('ZDMMSVENDOR.LIFNR as id');
			$this->db->select('ZDMMSVENDOR.*');
			$this->db->from('ZDMMSVENDOR');
			if ($plant !== NULL) {
				$this->db->where('ZDMMSVENDOR.EKORG', $plant);
			}
			if ($lifnr !== NULL) {
				$this->db->where('ZDMMSVENDOR.LIFNR', $lifnr);
			}
			
			$this->db->like('ZDMMSVENDOR.NAME1', 'DIREKTUR OPERASIONAL', 'both');
			

			$this->db->order_by('ZDMMSVENDOR.NAME1 ASC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_supply($conn = NULL, $plant = NULL, $lifnr = NULL, $depo = NULL, $tipe_um = NULL, $tanggal = NULL, $first_supply = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->query("SET ANSI_NULLS ON");
			$this->db->query("SET ANSI_WARNINGS ON");
	
			$string = "EXEC SP_Kiranaku_UMB_DataSupply '" . $plant . "', '" . $lifnr . "', '" . $depo . "', '" . $tipe_um . "', '" . $first_supply . "', '" . $tanggal . "'";

			$query  = $this->db->query($string);
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_um_scoring($conn = NULL, $tipe_um = NULL, $score = NULL, $kelas = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			$string = "SELECT tbl_umb_mscoring_header.kelas,
							   tbl_umb_mscoring_header.min_bln_supply,
							   tbl_umb_mscoring_header.std_minimal,
							   tbl_umb_mscoring_detail.*
						 FROM tbl_umb_mscoring_header as tbl_umb_mscoring_header
						INNER JOIN tbl_umb_mscoring_detail ON tbl_umb_mscoring_header.id_mscoring_header = tbl_umb_mscoring_detail.id_mscoring_header
						WHERE 1=1
						  AND tbl_umb_mscoring_header.del = 'n' ";
			if ($tipe_um !== NULL) {
				$string .= "   AND tbl_umb_mscoring_header.id_scoring_tipe = '" . $tipe_um . "'";
			}
			if ($kelas !== NULL) {
				$string .= "   AND tbl_umb_mscoring_header.kelas = '" . $kelas . "'";
				// $string .= "   AND (tbl_umb_mscoring_header.kelas ==) tbl_umb_mscoring_header.kelas = '" . $kelas . "'";
			}
			if ($score !== NULL) {
				$string .= "   AND '" . $score . "' BETWEEN tbl_umb_mscoring_detail.score_awal AND tbl_umb_mscoring_detail.score_akhir";
			}
			$string .= " ORDER BY tbl_umb_mscoring_detail.id_mscoring_header,
							     tbl_umb_mscoring_detail.id_mscoring_detail,
						  	     tbl_umb_mscoring_detail.no_urut";

			$query  = $this->db->query($string);
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_scoring_header_tahun($conn = NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			
			$this->db->select('YEAR(tbl_umb_scoring_header.tanggal_buat) as tahun');
			$this->db->from('tbl_umb_scoring_header');
			$this->db->where('tbl_umb_scoring_header.active', '1');
			$this->db->order_by('YEAR(tbl_umb_scoring_header.tanggal_buat)', 'ASC');
			$this->db->group_by('YEAR(tbl_umb_scoring_header.tanggal_buat)');
			$query = $this->db->get();
			
			if ($conn !== NULL)
				$this->general->closeDb();

			return $query->result();
		}

		
		function get_vw_scoring_header($conn = NULL, $no_form_scoring = NULL, $plant = NULL, $year = NULL, $status_in = NULL, $in_not_in = NULL, $approval = NULL, $tipe=NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("vw_umb_scoring_header.*");
			$this->db->from("vw_umb_scoring_header");

			if($no_form_scoring !== NULL) {
				$this->db->where("no_form_scoring", $no_form_scoring);
				// $this->datatables->where("no_form_scoring", $no_form_scoring);
			}

			if($plant !== NULL) {
				$this->db->where_in("plant", $plant);
				// $this->datatables->where( function ( $q ) {
					// $q->where( 'orderstatus', '('.$plant.')', 'IN', false );
				// });
			}

			if($year !== NULL) {
				$this->db->where_in("YEAR(tanggal)", $year);
				// $this->datatables->where( function ( $q ) {
					// $q->where( 'YEAR(tanggal)', '('.$year.')', 'IN', false );
				// });
			}

			// if($status !== NULL) {
			// 	$this->db->where_in("status", $status);
			// 	// $this->datatables->where( function ( $q ) {
			// 	// 	$q->where( 'orderstatus', '(1,2,3)', 'IN', false );
			// 	// });
			// }
			if($status_in != NULL && $in_not_in != NULL){
				$this->db->where('status '.$in_not_in.' ('.$status_in.')');
			}

			if($approval !== NULL) {
				$this->db->where("(status = CONVERT(varchar(20),$approval) or status_mou = CONVERT(varchar(20),$approval))");
				// $this->db->or_where("status_mou", (string)$approval);
			}

			if($tipe !== NULL) {
				$this->db->where_in("id_scoring_tipe", $tipe);
				// $this->datatables->where( function ( $q ) {
					// $q->where( 'id_scoring_tipe', '('.$tipe.')', 'IN', false );
				// });
			}

			// if ($filter) {
	  //           $this->datatables->group_start();
	  //           foreach ($filter as $key => $val) {
	  //               if ($val == "mto") {
	  //                   $this->datatables->or_where('no_pi IS NOT NULL');
	  //               } 
	  //               if ($val == "mts") {
	  //                   $this->datatables->or_where('no_pi IS NULL');
	  //               } 
	  //           }
	  //           $this->datatables->group_end();
	  //       }

			// if ($conn !== NULL)
			// 	$this->general->closeDb();

			// $return = $this->datatables->generate();
			// return $return;

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_scoring_header($conn = NULL, $no_form = NULL, $tipe = NULL, $year = NULL, $get_no_form = NULL, $status = NULL, $plant=NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$string = "EXEC SP_Kiranaku_UMB_DataScoringHeader '" . $no_form . "', '" . $tipe . "', '" . $year . "', '" . $get_no_form . "', '".$status."'";

			$query  = $this->db->query($string);
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_scoring_kriteria($conn = NULL, $no_form = NULL, $kriteria = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_kriteria.*");
			$this->db->select("tbl_umb_mjenis_kriteria.nama as deskripsi");
			$this->db->select("tbl_umb_mkriteria_header.satuan");
			$this->db->from("tbl_umb_scoring_kriteria");
			$this->db->join("tbl_umb_mkriteria_header", "tbl_umb_scoring_kriteria.id_umb_mkriteria = tbl_umb_mkriteria_header.id_mkriteria_header", "left");
			$this->db->join("tbl_umb_mjenis_kriteria", "tbl_umb_mkriteria_header.id_mjenis_kriteria = tbl_umb_mjenis_kriteria.id_mjenis_kriteria", "left");

			if ($no_form !== NULL) {
				$this->db->where("tbl_umb_scoring_kriteria.no_form_scoring", $no_form);
			}
			if ($kriteria !== NULL) {
				$this->db->where("tbl_umb_scoring_kriteria.id_umb_mkriteria", $kriteria);
			}
			$this->db->where("tbl_umb_scoring_kriteria.active", 1);
			$this->db->order_by("tbl_umb_scoring_kriteria.id_scoring_kriteria");

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_scoring_kriteria_nilai($conn = NULL, $no_form = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_kriteria.no_form_scoring");
			$this->db->select("SUM(tbl_umb_scoring_kriteria.score) as sum_score");
			$this->db->from("tbl_umb_scoring_kriteria");
			if ($no_form !== NULL) {
				$this->db->where("tbl_umb_scoring_kriteria.no_form_scoring", $no_form);
			}

			$this->db->where("tbl_umb_scoring_kriteria.active", 1);
			$this->db->group_by("tbl_umb_scoring_kriteria.no_form_scoring");

			$query  = $this->db->get();
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_scoring_jaminan_header($conn = NULL, $no_form = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_jamin_header.*");
			$this->db->select("tbl_umb_mdokumen.status as status_penjamin");
			$this->db->select("tbl_umb_mdokumen.kepemilikan as kepemilikan_penjamin");
			$this->db->from("tbl_umb_scoring_jamin_header");
			$this->db->join("tbl_umb_mdokumen", "tbl_umb_scoring_jamin_header.id_umb_mdokumen = tbl_umb_mdokumen.id_mdokumen", "inner");
			if ($no_form !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_header.no_form_scoring", $no_form);
			}
			$this->db->where("tbl_umb_scoring_jamin_header.active", 1);
			$this->db->order_by("tbl_umb_scoring_jamin_header.id_scoring_jaminan_header");

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_nama_penjamin($conn = NULL, $id_scoring_jaminan_header = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_jamin_header.*");
			$this->db->from("tbl_umb_scoring_jamin_header");
			if ($id_scoring_jaminan_header !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_header.id_scoring_jaminan_header", $id_scoring_jaminan_header);
			}

			$query  = $this->db->get();
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_scoring_jaminan_dokumen($conn = NULL, $id_scoring_jaminan_header = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_jamin_dok.*");
			$this->db->from("tbl_umb_scoring_jamin_dok");
			if ($id_scoring_jaminan_header !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_dok.id_scoring_jaminan_header", $id_scoring_jaminan_header);
			}
			$this->db->where("tbl_umb_scoring_jamin_dok.active", 1);
			$this->db->order_by("tbl_umb_scoring_jamin_dok.id_scoring_jaminan_dok");

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_scoring_jaminan_detail($conn = NULL, $id_scoring_jaminan_header = NULL, $no_form_scoring=NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_jamin_detail.*");
			$this->db->select("tbl_umb_mjaminan_header.*");
			$this->db->select("tbl_umb_mjaminan_detail.*");
			$this->db->select("tbl_umb_scoring_jamin_nilai.id_scoring_jaminan_nilai");
			$this->db->from("tbl_umb_scoring_jamin_detail");
			$this->db->join("tbl_umb_mjaminan_header", "tbl_umb_mjaminan_header.id_mjaminan_header = tbl_umb_scoring_jamin_detail.id_mjaminan_header");
			$this->db->join("tbl_umb_mjaminan_detail", "tbl_umb_mjaminan_detail.id_mjaminan_detail = tbl_umb_scoring_jamin_detail.id_mjaminan_detail");
			$this->db->join("tbl_umb_scoring_jamin_nilai", "tbl_umb_scoring_jamin_nilai.id_scoring_jaminan_detail = tbl_umb_scoring_jamin_detail.id_scoring_jaminan_detail and tbl_umb_scoring_jamin_nilai.active = '1'", "left");
			if ($id_scoring_jaminan_header !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_detail.id_scoring_jaminan_header", $id_scoring_jaminan_header);
			}

			if ($no_form_scoring !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_detail.no_form_scoring", $no_form_scoring);
			}

			$this->db->where("tbl_umb_scoring_jamin_detail.active", 1);
			$this->db->order_by("tbl_umb_scoring_jamin_detail.id_scoring_jaminan_detail, tbl_umb_mjaminan_detail.id_mjaminan_detail, tbl_umb_mjaminan_detail.no_urut");

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_summary_scoring($conn = NULL, $no_form = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_header.*");
			$this->db->from("tbl_umb_scoring_header");

			if ($no_form !== NULL) {
				$this->db->where("tbl_umb_scoring_header.no_form_scoring", $no_form);
			}

			$query  = $this->db->get();
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_log_scoring($conn = NULL, $no_form = NULL, $order=NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_log_status.*");
			$this->db->select("CONVERT(VARCHAR(20), tbl_umb_scoring_log_status.tgl_status, 120) as tgl_status");
			$this->db->select("tbl_umb_role.nama_role");
			$this->db->select("tbl_karyawan.nama");
			$this->db->select("tbl_umb_scoring_rekom_um.rekom_um as rekom_um_app");
			$this->db->from("tbl_umb_scoring_log_status");
			$this->db->join("tbl_umb_role", "tbl_umb_role.level = tbl_umb_scoring_log_status.status AND tbl_umb_role.na = 'n'", "inner");
			$this->db->join("tbl_user", "tbl_user.id_user = tbl_umb_scoring_log_status.login_edit", "inner");
			$this->db->join("tbl_karyawan", "tbl_karyawan.id_karyawan = tbl_user.id_karyawan", "inner");
			$this->db->join("tbl_umb_rolenik", "tbl_umb_role.kode_role = tbl_umb_rolenik.kode_role AND tbl_umb_rolenik.nik = tbl_karyawan.nik", "inner");
			$this->db->join("tbl_umb_scoring_rekom_um", "tbl_umb_scoring_rekom_um.status = tbl_umb_scoring_log_status.status AND tbl_umb_scoring_rekom_um.no_form_scoring = tbl_umb_scoring_log_status.no_form_scoring AND tbl_umb_scoring_rekom_um.tanggal_edit = tbl_umb_scoring_log_status.tanggal_edit", "left");
			$this->db->where("tbl_umb_scoring_log_status.no_form_scoring", $no_form);
			
			if ($order == NULL) {
				$this->db->order_by('tbl_umb_scoring_log_status.tgl_status DESC');
			}else{
				$this->db->order_by('tbl_umb_scoring_log_status.tgl_status ASC');
			}


			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_last_action_scoring($conn = NULL, $no_form = NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$query = $this->db->query('
									SELECT TOP 1 tbl_umb_scoring_log_status.*, 
												   CONVERT(VARCHAR(20), tbl_umb_scoring_log_status.tgl_status, 120) as tgl_status, 
												   tbl_umb_role.nama_role, 
												   tbl_karyawan.nama 
									  FROM tbl_umb_scoring_log_status 
									 INNER JOIN tbl_umb_role ON tbl_umb_role.level = tbl_umb_scoring_log_status.status AND tbl_umb_role.na = \'n\' 
									 INNER JOIN tbl_user ON tbl_user.id_user = tbl_umb_scoring_log_status.login_edit 
									 INNER JOIN tbl_karyawan ON tbl_karyawan.id_karyawan = tbl_user.id_karyawan 
									 INNER JOIN tbl_umb_rolenik ON tbl_umb_role.kode_role = tbl_umb_rolenik.kode_role AND tbl_umb_rolenik.nik = tbl_karyawan.nik 
				             	   	 WHERE tbl_umb_scoring_log_status.no_form_scoring = \''.$no_form.'\'
 									 ORDER BY tbl_umb_scoring_log_status.tgl_status DESC
								  ');
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}


		function get_data_jaminan_detail_nilai($conn=NULL, $id_scoring_jaminan_header=NULL, $id_scoring_jaminan_detail=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_jamin_nilai.*");
			// $this->db->select("tbl_umb_scoring_jamin_metode.*");
			$this->db->from("tbl_umb_scoring_jamin_nilai");
			// $this->db->join("tbl_umb_scoring_jamin_metode", "tbl_umb_scoring_jamin_metode.id_scoring_jaminan_nilai = tbl_umb_scoring_jamin_nilai.id_scoring_jaminan_nilai AND tbl_umb_scoring_jamin_metode.active = '1'", "LEFT");
			if ($id_scoring_jaminan_header !== NULL && $id_scoring_jaminan_detail !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_nilai.id_scoring_jaminan_header", $id_scoring_jaminan_header);
				$this->db->where("tbl_umb_scoring_jamin_nilai.id_scoring_jaminan_detail", $id_scoring_jaminan_detail);
			}

			$this->db->where("tbl_umb_scoring_jamin_nilai.active", "1");			

			$query  = $this->db->get();
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_jaminan_detail_metode($conn=NULL, $id_scoring_jaminan_nilai=NULL, $tipe=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_jamin_metode.*");
			$this->db->from("tbl_umb_scoring_jamin_metode");
			if ($id_scoring_jaminan_nilai !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_metode.id_scoring_jaminan_nilai", $id_scoring_jaminan_nilai);
			}

			if ($tipe !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_metode.tipe", $tipe);
			}

			$this->db->where("tbl_umb_scoring_jamin_metode.active", "1");

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_jaminan_detail($conn=NULL, $id_scoring_jaminan_detail=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_jamin_detail.*");
			$this->db->from("tbl_umb_scoring_jamin_detail");
			if ($id_scoring_jaminan_detail !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_detail.id_scoring_jaminan_detail", $id_scoring_jaminan_detail);
			}

			$this->db->where("tbl_umb_scoring_jamin_detail.active", "1");

			$query  = $this->db->get();
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_dok_mou($conn=NULL, $no_form_scoring=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_mou.*");
			$this->db->from("tbl_umb_scoring_mou");
			if ($no_form_scoring !== NULL) {
				$this->db->where("tbl_umb_scoring_mou.no_form_scoring", $no_form_scoring);
			}

			$this->db->where("tbl_umb_scoring_mou.active", "1");

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_jaminan_scoring($conn=NULL, $no_form_scoring=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_scoring_jamin_detail.*");
			$this->db->select("tbl_umb_mjaminan_detail.detail");
			$this->db->select("tbl_umb_mjaminan_detail.persen_discount");
			$this->db->select("tbl_umb_mjaminan_header.jenis");
			$this->db->from("tbl_umb_scoring_jamin_detail");
			$this->db->join("tbl_umb_scoring_jamin_header", "tbl_umb_scoring_jamin_header.id_scoring_jaminan_header = tbl_umb_scoring_jamin_detail.id_scoring_jaminan_header");
			$this->db->join("tbl_umb_mjaminan_header", "tbl_umb_mjaminan_header.id_mjaminan_header = tbl_umb_scoring_jamin_detail.id_mjaminan_header");
			$this->db->join("tbl_umb_mjaminan_detail", "tbl_umb_mjaminan_detail.id_mjaminan_detail = tbl_umb_scoring_jamin_detail.id_mjaminan_detail");
			$this->db->where("tbl_umb_scoring_jamin_detail.active", '1');
			$this->db->where("tbl_umb_scoring_jamin_header.active", '1');

			if ($no_form_scoring !== NULL) {
				$this->db->where("tbl_umb_scoring_jamin_detail.no_form_scoring", $no_form_scoring);
			}
			
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_email_umb($conn = NULL, $no_form_scoring=NULL, $plant=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$query	= $this->db->query('SELECT DISTINCT tbl_user.id_karyawan,
														tbl_karyawan.nama,
														tbl_karyawan.email,
													   CASE
															WHEN tbl_karyawan.gender = \'l\' THEN \'Bapak\'
															WHEN tbl_karyawan.gender = \'p\' THEN \'Ibu\'
															ELSE \'\'
												   		END as gender,
													   nilai = \'cc\'
												  FROM tbl_umb_scoring_log_status
												 INNER JOIN tbl_user ON tbl_user.id_user = tbl_umb_scoring_log_status.login_edit
												 INNER JOIN tbl_karyawan ON tbl_user.id_karyawan = tbl_karyawan.nik
												 INNER JOIN tbl_umb_rolenik ON tbl_umb_rolenik.nik = tbl_karyawan.nik
																			AND tbl_umb_rolenik.na = \'n\'
												 						    AND tbl_umb_rolenik.del = \'n\'
												 INNER JOIN tbl_umb_rolenik_pabrik on tbl_umb_rolenik_pabrik.id_rolenik = tbl_umb_rolenik.id_rolenik
												 INNER JOIN tbl_umb_role on tbl_umb_role.kode_role = tbl_umb_rolenik.kode_role
												 WHERE tbl_umb_scoring_log_status.no_form_scoring = \''.$no_form_scoring.'\'
												   AND tbl_umb_role.level != \'9\'
												   AND tbl_umb_rolenik_pabrik.kode_pabrik = \''.$plant.'\'
												 UNION
												 SELECT DISTINCT tbl_user.id_karyawan,
												        tbl_karyawan.nama,
												        tbl_karyawan.email,
												        CASE
															WHEN tbl_karyawan.gender = \'l\' THEN \'Bapak\'
															WHEN tbl_karyawan.gender = \'p\' THEN \'Ibu\'
															ELSE \'\'
													   	END as gender,
												        nilai = \'to\'
												   FROM tbl_karyawan
												  INNER JOIN tbl_user ON tbl_user.id_karyawan = tbl_karyawan.nik
												  INNER JOIN tbl_umb_rolenik ON tbl_umb_rolenik.nik = tbl_karyawan.nik
												 						  AND tbl_umb_rolenik.na = \'n\'
												 						  AND tbl_umb_rolenik.del = \'n\'
												  INNER JOIN tbl_umb_rolenik_pabrik on tbl_umb_rolenik_pabrik.id_rolenik = tbl_umb_rolenik.id_rolenik
												  INNER JOIN tbl_umb_role ON tbl_umb_role.kode_role = tbl_umb_rolenik.kode_role
												  INNER JOIN tbl_umb_scoring_header ON tbl_umb_scoring_header.status = CAST(tbl_umb_role.level as varchar(20))
												  WHERE tbl_umb_scoring_header.no_form_scoring = \''.$no_form_scoring.'\'
												    AND tbl_umb_role.level != \'9\'
												    AND tbl_umb_rolenik_pabrik.kode_pabrik = \''.$plant.'\'
												  ORDER BY nilai');
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		function get_sum_umb_pabrik($conn=NULL, $plant=NULL, $id_scoring_tipe=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("sum(
									CASE
										WHEN tbl_umb_scoring_header.um_setuju > 0 THEN tbl_umb_scoring_header.um_setuju
										ELSE tbl_umb_scoring_header.um_minta
								   	END
									) as plafon_terpakai");
			$this->db->from("tbl_umb_scoring_header");
			$this->db->join("(SELECT Max(tbl_umb_scoring_header.tanggal_edit) date, kode_supplier, depo
			   					FROM tbl_umb_scoring_header
			  				   GROUP BY kode_supplier, depo) AS tb_time", 
			  				   "(
									(tbl_umb_scoring_header.kode_supplier = tb_time.kode_supplier AND tb_time.depo IS NULL)
									OR
									(tbl_umb_scoring_header.depo = tb_time.depo AND tb_time.kode_supplier IS NULL)
			   					) 
		   						AND tbl_umb_scoring_header.tanggal_edit = tb_time.date", "inner", false);

			if ($plant !== NULL) {
				$this->db->where("tbl_umb_scoring_header.plant", $plant);
			}

			if ($id_scoring_tipe !== NULL) {
				$this->db->where("tbl_umb_scoring_header.id_scoring_tipe", $id_scoring_tipe);
			}

			$this->db->where("tbl_umb_scoring_header.status NOT IN ('drop','stop')");

			$query  = $this->db->get();
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		function get_data_pemakaian_plafon($conn = NULL, $plant = NULL, $tipe = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$string = "EXEC SP_Kiranaku_UMB_Data_Plafon '" . $plant . "', '" . $tipe . "'";

			$query  = $this->db->query($string);
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_role_umb($conn = NULL, $plant = NULL, $kode_role = NULL, $nik = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_umb_rolenik.nik");
			// $this->db->select("tbl_umb_role.*");
			// $this->db->select("tbl_umb_rolenik_pabrik.*");
			$this->db->select("tbl_karyawan.nama as nama_karyawan");
			$this->db->from("tbl_umb_rolenik");
			$this->db->join("tbl_umb_role", "tbl_umb_role.kode_role = tbl_umb_rolenik.kode_role");
			$this->db->join("tbl_umb_rolenik_pabrik", "tbl_umb_rolenik_pabrik.id_rolenik = tbl_umb_rolenik.id_rolenik");
			$this->db->join("tbl_karyawan", "tbl_karyawan.nik = tbl_umb_rolenik.nik");
			$this->db->where("tbl_umb_rolenik.na", 'n');
			$this->db->where("tbl_umb_rolenik.del", 'n');

			if ($plant !== NULL) {
				$this->db->where("tbl_umb_rolenik_pabrik.kode_pabrik", $plant);
			}
			
			if ($kode_role !== NULL) {
				$this->db->where("tbl_umb_role.kode_role", $kode_role);
			}
			
			if ($nik !== NULL) {
				$this->db->where("tbl_karyawan.nik", $nik);
			}
			
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

	}

?>
