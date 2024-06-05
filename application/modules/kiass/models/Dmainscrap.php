<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application  : K-IASS
    @author       : MATTHEW JODI (8944)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */

	class Dmainscrap extends CI_Model {

        function get_no_pp($param = NULL){
            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->connectDbDefault();

            $this->db->select('tbl_scrap_header.*');
            $this->db->from('tbl_scrap_header');
            $this->db->where('tbl_scrap_header.plant', $param['plant']);
            $this->db->where_in('YEAR(tbl_scrap_header.tanggal_pengajuan)', $param['year']);
            $this->db->like('no_pp', $param['plant'].'/'.$param['month'].'/'.$param['year'], 'before');
            
            $query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}
		
		function get_log_status($param = NULL){
            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->connectDbDefault();

			$this->db->select('tbl_scrap_log_status.*');
			$this->db->select("CONVERT(VARCHAR(20), tbl_scrap_log_status.tgl_status, 120) as format_tanggal_status");
            $this->db->select('tbl_scrap_role.nama_role');
            $this->db->select('tbl_karyawan.nama');
			$this->db->from('tbl_scrap_log_status');
			
			$this->db->join('tbl_scrap_role', 'CAST(tbl_scrap_role.level as VARCHAR(50)) = tbl_scrap_log_status.status and tbl_scrap_role.na = \'n\' and tbl_scrap_role.del = \'n\'', 'left');
			$this->db->join('tbl_user', 'tbl_user.id_user = tbl_scrap_log_status.login_edit');
			$this->db->join('tbl_karyawan', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan');
			
            
            if (isset($param['no_pp']) && $param['no_pp'] !== NULL)
                $this->db->where('tbl_scrap_log_status.no_pp', $param['no_pp']);
			
			$this->db->order_by('tbl_scrap_log_status.tanggal_edit', 'DESC');
            
            $query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
        }
        
        function get_scrap_header_tahun($param = NULL){
            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

            $this->db->select('YEAR(tbl_scrap_header.tanggal_buat) as tahun');
            $this->db->from('tbl_scrap_header');
            $this->db->where('tbl_scrap_header.na', 'n');
            $this->db->where('tbl_scrap_header.del', 'n');
            $this->db->order_by('YEAR(tbl_scrap_header.tanggal_buat)', 'ASC');
            $this->db->group_by('YEAR(tbl_scrap_header.tanggal_buat)');
            
            $query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}
		
		function get_flow_approval($param = NULL){
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_role_dtl.*");

			$this->db->from('tbl_scrap_role_dtl');
			
			if (isset($param['id_flow']) && $param['id_flow'] !== NULL)
				$this->db->where('tbl_scrap_role_dtl.id_flow', $param['id_flow']);
				
			if (isset($param['kode_role']) && $param['kode_role'] !== NULL)
                $this->db->where('tbl_scrap_role_dtl.no_pp', $param['kode_role']);

			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_kode_asset($param = NULL){
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_kode_asset.*");

			$this->db->from('tbl_scrap_kode_asset');
			
			if (isset($param['no_pp']) && $param['no_pp'] !== NULL)
				$this->db->where('tbl_scrap_kode_asset.no_pp', $param['no_pp']);
				
			if (isset($param['id_row_analisa']) && $param['id_row_analisa'] !== NULL)
                $this->db->where('tbl_scrap_kode_asset.id_row_analisa', $param['id_row_analisa']);

			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}
        
        function get_scrap_header($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

            $this->db->select("tbl_scrap_header.*");
			$this->db->select("ZDMMSPLANT.BUKRS");
            $this->db->select("CONVERT(VARCHAR(10), CONVERT(DATE, tbl_scrap_header.tanggal_pengajuan), 104) as format_tanggal_pengajuan");
            $this->db->select('CASE tbl_scrap_header.status
					       		WHEN \'drop\' THEN \'<span class="label label-danger">DROP</span>\'
					       		WHEN \'deleted\' THEN \'<span class="label label-danger">DELETED</span>\'
					       		WHEN \'finish\' THEN \'<span class="label label-success">FINISH</span>\'
					       		ELSE \'<span class="label label-warning">ON PROGRESS</span>\'
					       END as view_status');
			$this->db->select("tbl_scrap_role.nama_role");
            $this->db->select("tbl_scrap_mflow_approval.alias_flow");
			$this->db->select("tbl_scrap_file.location as filename");
			$this->db->select('(SELECT tb_file.location
								FROM tbl_scrap_file tb_file
								WHERE tb_file.id_file = tbl_scrap_header.lampiran_proc) as filename_proc');
			
			$this->db->select('(SELECT COUNT(tbl_scrap_deviasi_header.no_pp)
								FROM tbl_scrap_deviasi_header
								WHERE tbl_scrap_deviasi_header.no_pp = tbl_scrap_header.no_pp
								AND tbl_scrap_deviasi_header.del = tbl_scrap_header.del) as count_deviasi');

			$this->db->select('(SELECT SUM(CASE
											WHEN tbl_scrap_analisa_harga.no_so = \'\' then 1
											WHEN tbl_scrap_analisa_harga.no_so is null then 1
											ELSE 0
										END)
								FROM tbl_scrap_analisa_harga
								WHERE tbl_scrap_analisa_harga.no_pp = tbl_scrap_header.no_pp
								AND tbl_scrap_analisa_harga.del = tbl_scrap_header.del) as cek_so');

			$this->db->select('(SELECT SUM(tbl_scrap_analisa_harga.total_harga_nego)
					FROM tbl_scrap_analisa_harga
					WHERE tbl_scrap_analisa_harga.no_pp = tbl_scrap_header.no_pp
					AND tbl_scrap_analisa_harga.del = \'n\') as nilai_pengajuan');
			
			$this->db->select('CAST((SELECT DISTINCT tb1.no_so + RTRIM(\',\')
								FROM tbl_scrap_analisa_harga tb1
								WHERE tb1.no_pp = tbl_scrap_header.no_pp
								AND tb1.na = \'n\'
								AND tb1.del = \'n\'
								FOR XML PATH (\'\')) as VARCHAR(MAX)) as list_so');

			$this->db->from('tbl_scrap_header');
            $this->db->join('DashBoardDev.dbo.ZDMMSPLANT', '(ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_scrap_header.plant', 'inner');
            $this->db->join('tbl_scrap_role', 'CAST(tbl_scrap_role.level as VARCHAR(50)) = tbl_scrap_header.status
                                        AND tbl_scrap_role.del = \'n\'', 'left');
            $this->db->join('tbl_scrap_mflow_approval', 'tbl_scrap_mflow_approval.id_flow = tbl_scrap_header.id_flow', 'left');
            $this->db->join('tbl_scrap_file', 'tbl_scrap_file.id_file = tbl_scrap_header.id_lampiran', 'left');

			if (isset($param['no_pp']) && $param['no_pp'] !== NULL)
                $this->db->where('tbl_scrap_header.no_pp', $param['no_pp']);

            if (isset($param['plant']) && $param['plant'] !== NULL)
                // $this->db->where('tbl_scrap_header.plant', $param['plant']);
				$this->db->where_in('tbl_scrap_header.plant', $param['plant']);
			
			if((isset($param['status_in']) != NULL && $param['status_in'] != NULL) && (isset($param['in_not_in']) != NULL && $param['in_not_in'] != NULL))
				$this->db->where('status '.$param['in_not_in'].' ('.$param['status_in'].')');
				
			if (isset($param['statuss']) && $param['statuss'] !== NULL)
                $this->db->where_in('tbl_scrap_header.status', $param['statuss']);

            if (isset($param['approval']) && $param['approval'] !== NULL){
				// $this->db->where_in('tbl_scrap_header.status', $param['approval']);
				if (count($param['approval']) == 1) {
					$st = implode(',', $param['approval']);
					$this->db->where('tbl_scrap_header.status = ', $st);
				}else{
					
					$st = "'".implode("','", $param['approval'])."'";
					if (in_array("6", $param['approval'])){
						$this->db->group_start();
						$this->db->where('tbl_scrap_header.status IN ('.$st.')');
						// echo json_encode($st);exit();
						$this->db->or_group_start();
							$this->db->where('tbl_scrap_header.status', 'finish');
							$this->db->where('(SELECT SUM(CASE
																WHEN tbl_scrap_analisa_harga.no_so = \'\' then 1
																WHEN tbl_scrap_analisa_harga.no_so is null then 1
																ELSE 0
															END)
													FROM tbl_scrap_analisa_harga
													WHERE tbl_scrap_analisa_harga.no_pp = tbl_scrap_header.no_pp
													AND tbl_scrap_analisa_harga.del = tbl_scrap_header.del) !=', '0');
							$this->db->group_end();
						$this->db->group_end();
					}else{
						$st = "'".implode("','", $param['approval'])."'";
						$this->db->where('tbl_scrap_header.status IN ('.$st.')');		
					}


				}

			}

            if (isset($param['year']) && $param['year'] !== NULL)
				$this->db->where_in('YEAR(tbl_scrap_header.tanggal_buat)', $param['year']);
				
			if (isset($param['pic_ho']) && $param['pic_ho'] !== NULL)
                $this->db->where_in('tbl_scrap_header.pic_ho', $param['pic_ho']);
            
			if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_header.del', 'n');
				$this->db->where('tbl_scrap_header.na', 'n');
			}

			$this->db->order_by('tbl_scrap_header.tanggal_buat', 'DESC');


			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}
        
        function get_analisa_harga($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_analisa_harga.*");
			// $this->db->select("YEAR(tbl_scrap_analisa_harga.tahun_beli) as yearbeli");
			$this->db->select("ZDMFICO15.BUCHWERT as nilai_nbv_temp");
			$this->db->select("tbl_scrap_file.location as filename");
			$this->db->select("vw_material_spec_byplant.full_description");
			$this->db->select("vw_material_spec_byplant.classification");

			$this->db->from('tbl_scrap_analisa_harga');
			$this->db->join('tbl_scrap_header', 'tbl_scrap_analisa_harga.no_pp = tbl_scrap_header.no_pp', 'inner');
			$this->db->join('DashBoardDev.dbo.ZDMMSPLANT', '(ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_scrap_header.plant', 'inner');
			$this->db->join('tbl_scrap_file', 'tbl_scrap_file.id_file = tbl_scrap_analisa_harga.id_foto_kondisi', 'left');
			$this->db->join("(
				SELECT BUKRS, GSBER, ANLN1, ANLN2, MAX(DATUM) as tgl,
					CASE 
						WHEN ZDMFICO15.BUKRS NOT IN ('2132', '2122', '2111') THEN ZDMFICO15.BUKRS
						ELSE ZDMFICO15.GSBER
					END FILTER
				   	FROM DashBoardDev.dbo.ZDMFICO15
				  	GROUP BY BUKRS, GSBER, ANLN1, ANLN2
				) DATUM_ZDMFICO15",
				"CONVERT(BIGINT,DATUM_ZDMFICO15.ANLN1) = CONVERT(BIGINT,tbl_scrap_analisa_harga.kode_asset) 
				AND CONVERT(INT,DATUM_ZDMFICO15.ANLN2) = CONVERT(INT,tbl_scrap_analisa_harga.sno) 
				AND (DATUM_ZDMFICO15.FILTER = ZDMMSPLANT.BUKRS OR DATUM_ZDMFICO15.FILTER = ZDMMSPLANT.WERKS)
				",
				'left');
			$this->db->join("(
				SELECT BUKRS, GSBER, ANLN1, ANLN2, DATUM, BUCHWERT,
					CASE 
						WHEN ZDMFICO15.BUKRS NOT IN ('2132', '2122', '2111') THEN ZDMFICO15.BUKRS
						ELSE ZDMFICO15.GSBER
					END FILTER
				   	FROM DashBoardDev.dbo.ZDMFICO15
				) ZDMFICO15",
				"CONVERT(BIGINT,ZDMFICO15.ANLN1) = CONVERT(BIGINT,tbl_scrap_analisa_harga.kode_asset) 
				AND CONVERT(INT,ZDMFICO15.ANLN2) = CONVERT(INT,tbl_scrap_analisa_harga.sno)
				AND (ZDMFICO15.FILTER = ZDMMSPLANT.BUKRS OR ZDMFICO15.FILTER = ZDMMSPLANT.WERKS)
				AND ZDMFICO15.DATUM = DATUM_ZDMFICO15.tgl", 'left');
			// $this->db->join('vw_material_spec_byplant', 'vw_material_spec_byplant.id = tbl_scrap_analisa_harga.kode_material and vw_material_spec_byplant.plant = tbl_scrap_header.plant and vw_material_spec_byplant.na = \'n\'', 'left');
			$this->db->join("(
				SELECT DISTINCT tbl_item_spec.code as id,
					tbl_item_spec.purchase_authorization,
					tbl_item_name.description+' '+tbl_item_spec.description as full_description,
					tbl_item_group.description as group_description,
					tbl_item_name.classification,
					tbl_pi_setting_kode.tipe_setting
				FROM tbl_item_spec WITH(NOLOCK)
				INNER JOIN tbl_item_plant WITH(NOLOCK) ON tbl_item_plant.id_item_spec = tbl_item_spec.id_item_spec
					AND tbl_item_plant.status_sap = 'y'
					AND tbl_item_plant.na = 'n'
					AND tbl_item_plant.del = 'n'
				INNER JOIN tbl_item_name WITH(NOLOCK) ON tbl_item_name.id_item_group = tbl_item_spec.id_item_group
					AND tbl_item_name.id_item_name = tbl_item_spec.id_item_name
					AND tbl_item_name.na = 'n'
					AND tbl_item_name.del = 'n'
				INNER JOIN tbl_item_group WITH(NOLOCK) ON tbl_item_group.id_item_group = tbl_item_spec.id_item_group
					AND tbl_item_group.id_item_group = tbl_item_name.id_item_group
					AND tbl_item_group.na = 'n'
					AND tbl_item_group.del = 'n'
				LEFT JOIN tbl_pi_setting_kode WITH(NOLOCK) ON tbl_pi_setting_kode.spec_code = tbl_item_spec.code
					AND tbl_pi_setting_kode.tipe_setting = 'expense_io'
				UNION
				SELECT DISTINCT MATNR COLLATE SQL_Latin1_General_CP1_CS_AS as id,
					tbl_pi_setting_kode.purch_oto as purchase_authorization,
					ZDMP2PMATNR.MAKTX COLLATE SQL_Latin1_General_CP1_CI_AS as full_description,
					ZDMP2PMATNR.MAKTX COLLATE SQL_Latin1_General_CP1_CI_AS as group_description,
					'I' as [classification],
					tbl_pi_setting_kode.tipe_setting
				FROM SAPSYNC.dbo.ZDMP2PMATNR WITH(NOLOCK)
				LEFT JOIN tbl_pi_setting_kode WITH(NOLOCK) ON tbl_pi_setting_kode.spec_code = ZDMP2PMATNR.MATNR COLLATE SQL_Latin1_General_CP1_CI_AS
					AND tbl_pi_setting_kode.tipe_setting = 'inventory'
				) AS vw_material_spec_byplant",
				"vw_material_spec_byplant.id = tbl_scrap_analisa_harga.kode_material",
				"INNER" 
			);

			// $this->db->where('vw_material_spec_byplant.classification', 'A');

			//QUERY GET data terbaru nbv dari FICO15
			// $this->db->where('ZDMFICO15.DATUM = (SELECT TOP 1 ZDMFICO15s.DATUM
			// 										FROM DASHBOARDDEV.dbo.ZDMFICO15 ZDMFICO15s
			// 									   WHERE (SUBSTRING(ZDMFICO15s.ANLN1, 4, LEN(ZDMFICO15s.ANLN1)) 
			// 												COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_scrap_analisa_harga.kode_asset 
			// 											AND (SUBSTRING(ZDMFICO15s.ANLN2, 4, LEN(ZDMFICO15s.ANLN2)) 
			// 												COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_scrap_analisa_harga.sno
			// 									   ORDER BY ZDMFICO15s.DATUM DESC)');

			// if (isset($param['plant']) && $param['plant'] !== NULL)
            //     $this->db->where('vw_material_spec_byplant.plant', $param['plant']);

			if (isset($param['no_pp']) && $param['no_pp'] !== NULL)
                $this->db->where('tbl_scrap_analisa_harga.no_pp', $param['no_pp']);
            
			// if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_analisa_harga.del', 'n');
				$this->db->where('tbl_scrap_analisa_harga.na', 'n');
			// }

			$this->db->order_by('tbl_scrap_analisa_harga.no_tabel, tbl_scrap_analisa_harga.id_row_analisa', 'ASC');


			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
        }
        
        function get_calon_pembeli($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_calon_pembeli.*");
			$this->db->select("zdmmktsupp18.name1");
			$this->db->select("tbl_scrap_file.location as filename");

			$this->db->from('tbl_scrap_calon_pembeli');
			$this->db->join('DASHBOARDDEV.dbo.zdmmktsupp18 zdmmktsupp18', '(zdmmktsupp18.KUNNR COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_scrap_calon_pembeli.nama_pembeli', 'left');
			$this->db->join('tbl_scrap_file', 'tbl_scrap_file.id_file = tbl_scrap_calon_pembeli.id_lampiran_calon', 'left');

			if (isset($param['no_pp']) && $param['no_pp'] !== NULL)
                $this->db->where('tbl_scrap_calon_pembeli.no_pp', $param['no_pp']);

            if (isset($param['id_row_analisa']) && $param['id_row_analisa'] !== NULL)
                $this->db->where('tbl_scrap_calon_pembeli.id_row_analisa', $param['id_row_analisa']);
            
			// if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_calon_pembeli.del', 'n');
				$this->db->where('tbl_scrap_calon_pembeli.na', 'n');
			// }


			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_nilai_nbv($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select('ZDMFICO15.*');
			$this->db->from('ZDMFICO15');

			if (isset($param['gsber']) && $param['gsber'] !== NULL) {
				$this->db->where('ZDMFICO15.GSBER', $param['gsber']);
			}
			if (isset($param['bukrs']) && $param['bukrs'] !== NULL) {
				$this->db->where('ZDMFICO15.BUKRS', $param['bukrs']);
			}

			if (isset($param['kode_asset']) && $param['kode_asset'] !== NULL) {
				$this->db->where('CONVERT(BIGINT,ZDMFICO15.ANLN1) =', $param['kode_asset']);
			}

			if (isset($param['sno']) && $param['sno'] !== NULL) {
				$this->db->where('CONVERT(INT,ZDMFICO15.ANLN2) =', $param['sno']);
			}

			$this->db->order_by('ZDMFICO15.DATUM', 'DESC');

			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL)
				$result = $query->row();
			else
				$result = $query->result();

			if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
				$result = $this->general->generate_encrypt_json($result, $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_data_scrap_sap($param = NULL){
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_analisa_harga.*");
			$this->db->select("tbl_scrap_calon_pembeli.id_calon_pembeli");
			$this->db->select("tbl_scrap_calon_pembeli.no_hp");
			$this->db->select('CASE
									WHEN tbl_scrap_calon_pembeli.kode_customer = \'\' then tbl_scrap_kode_dummy.kode_dummy
									WHEN tbl_scrap_calon_pembeli.kode_customer is null then tbl_scrap_kode_dummy.kode_dummy
									ELSE tbl_scrap_calon_pembeli.kode_customer
								END as kode_customer');
			$this->db->select("tbl_scrap_header.perihal");
			$this->db->select('CONVERT(INT,
								CASE
								WHEN IsNumeric(CONVERT(VARCHAR(12), (CASE
																		WHEN tbl_scrap_calon_pembeli.kode_customer = \'\' then tbl_scrap_kode_dummy.kode_dummy
																		WHEN tbl_scrap_calon_pembeli.kode_customer is null then tbl_scrap_kode_dummy.kode_dummy
																	ELSE tbl_scrap_calon_pembeli.kode_customer
								END))) = 1 THEN CONVERT(VARCHAR(12),(CASE
																		WHEN tbl_scrap_calon_pembeli.kode_customer = \'\' then tbl_scrap_kode_dummy.kode_dummy
																		WHEN tbl_scrap_calon_pembeli.kode_customer is null then tbl_scrap_kode_dummy.kode_dummy
																		ELSE tbl_scrap_calon_pembeli.kode_customer
																	END))
								ELSE 0 END) as orderby');
			
			$this->db->from('tbl_scrap_analisa_harga');
			$this->db->join('tbl_scrap_calon_pembeli', 'tbl_scrap_calon_pembeli.no_urut = tbl_scrap_analisa_harga.pemenang and tbl_scrap_calon_pembeli.id_row_analisa = tbl_scrap_analisa_harga.id_row_analisa', 'left');
			$this->db->join('tbl_scrap_header', 'tbl_scrap_analisa_harga.no_pp = tbl_scrap_header.no_pp', 'left');
			$this->db->join('tbl_scrap_kode_dummy', 'tbl_scrap_kode_dummy.plant = tbl_scrap_header.plant', 'left');

			if (isset($param['no_pp']) && $param['no_pp'] !== NULL)
                $this->db->where('tbl_scrap_analisa_harga.no_pp', $param['no_pp']);

				
			$this->db->order_by('orderby', 'DESC');

			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;

		}

		function get_data_kunnr($param = NULL){
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("distinct(tbl_scrap_calon_pembeli.nama_pembeli)");
			$this->db->select("tbl_scrap_analisa_harga.id_row_analisa");
			$this->db->select("tbl_scrap_calon_pembeli.id_calon_pembeli");
			$this->db->select("tbl_scrap_calon_pembeli.kode_customer");
			$this->db->select("tbl_scrap_calon_pembeli.no_tabel");
			$this->db->select("tbl_scrap_calon_pembeli.no_row");
			$this->db->select("tbl_scrap_calon_pembeli.nama_pembeli");
			
			$this->db->from('tbl_scrap_analisa_harga');
			$this->db->join('tbl_scrap_calon_pembeli', 'tbl_scrap_calon_pembeli.no_urut = tbl_scrap_analisa_harga.pemenang and tbl_scrap_calon_pembeli.id_row_analisa = tbl_scrap_analisa_harga.id_row_analisa', 'left');

			if (isset($param['no_pp']) && $param['no_pp'] !== NULL){
                $this->db->where('tbl_scrap_analisa_harga.no_pp', $param['no_pp']);
			}

			$this->db->order_by('tbl_scrap_calon_pembeli.no_tabel, tbl_scrap_calon_pembeli.no_row', 'ASC');
			
			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;

		}

		function cek_invalid_kode($param = NULL){
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			
			$this->db->select('SUM(CASE
									WHEN tbl_scrap_calon_pembeli.kode_customer = \'\' then 1
									WHEN tbl_scrap_calon_pembeli.kode_customer is null then 1
									ELSE 0
								END) as cek_kode');
			$this->db->select('SUM(CASE
								WHEN tbl_scrap_analisa_harga.no_so = \'\' then 1
								WHEN tbl_scrap_analisa_harga.no_so is null then 1
								ELSE 0
							END) as cek_so');
			
			$this->db->from('tbl_scrap_analisa_harga');
			$this->db->join('tbl_scrap_calon_pembeli', 'tbl_scrap_calon_pembeli.no_urut = tbl_scrap_analisa_harga.pemenang and tbl_scrap_calon_pembeli.id_row_analisa = tbl_scrap_analisa_harga.id_row_analisa', 'left');
			$this->db->join('tbl_scrap_header', 'tbl_scrap_analisa_harga.no_pp = tbl_scrap_header.no_pp', 'left');

			if (isset($param['no_pp']) && $param['no_pp'] !== NULL)
                $this->db->where('tbl_scrap_analisa_harga.no_pp', $param['no_pp']);

			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;

		}

		function get_email_recipient($param = NULL){
			if ($param['conn'] !== NULL)
				$this->general->connectDbPortal();


			if(isset($param['pic_ho']) && $param['pic_ho'] !== NULL){
				$query	= $this->db->query('SELECT DISTINCT tbl_user.id_karyawan,
														tbl_karyawan.nama,
														tbl_karyawan.email,
													   CASE
															WHEN tbl_karyawan.gender = \'l\' THEN \'Bapak\'
															WHEN tbl_karyawan.gender = \'p\' THEN \'Ibu\'
															ELSE \'\'
												   		END as gender,
													   nilai = \'cc\'
												  FROM tbl_scrap_log_status
												 INNER JOIN tbl_user ON tbl_user.id_user = tbl_scrap_log_status.login_edit
												 INNER JOIN tbl_karyawan ON tbl_user.id_karyawan = tbl_karyawan.nik
												 INNER JOIN tbl_scrap_roleuser ON tbl_scrap_roleuser.[user] = tbl_karyawan.nik
																			AND tbl_scrap_roleuser.na = \'n\'
												 						    AND tbl_scrap_roleuser.del = \'n\'
												 INNER JOIN tbl_scrap_role on tbl_scrap_role.kode_role = tbl_scrap_roleuser.kode_role
												 CROSS APPLY fnSplitString(tbl_scrap_roleuser.pabrik, \',\')
												 WHERE tbl_scrap_log_status.no_pp = \''.$param['no_pp'].'\'
												   AND tbl_scrap_role.level != \'13\'
												   AND CONVERT(VARCHAR, splitdata) = \''.$param['plant'].'\'
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
												  INNER JOIN tbl_scrap_roleuser ON tbl_scrap_roleuser.[user] = tbl_karyawan.nik
																			AND tbl_scrap_roleuser.na = \'n\'
												 						    AND tbl_scrap_roleuser.del = \'n\'
												  INNER JOIN tbl_scrap_role on tbl_scrap_role.kode_role = tbl_scrap_roleuser.kode_role
												  CROSS APPLY fnSplitString(tbl_scrap_roleuser.pabrik, \',\')
												  INNER JOIN tbl_scrap_header ON tbl_scrap_header.status = CAST(tbl_scrap_role.level as varchar(20))
												  WHERE tbl_scrap_header.no_pp = \''.$param['no_pp'].'\'
												    AND tbl_scrap_role.level != \'13\'
													AND CONVERT(VARCHAR, splitdata) = \''.$param['plant'].'\'
													AND tbl_scrap_roleuser.caption = \''.$param['pic_ho'].'\'
												  ORDER BY nilai');
			}else{

				$query	= $this->db->query('SELECT DISTINCT tbl_user.id_karyawan,
															tbl_karyawan.nama,
															tbl_karyawan.email,
														   CASE
																WHEN tbl_karyawan.gender = \'l\' THEN \'Bapak\'
																WHEN tbl_karyawan.gender = \'p\' THEN \'Ibu\'
																ELSE \'\'
															   END as gender,
														   nilai = \'cc\'
													  FROM tbl_scrap_log_status
													 INNER JOIN tbl_user ON tbl_user.id_user = tbl_scrap_log_status.login_edit
													 INNER JOIN tbl_karyawan ON tbl_user.id_karyawan = tbl_karyawan.nik
													 INNER JOIN tbl_scrap_roleuser ON tbl_scrap_roleuser.[user] = tbl_karyawan.nik
																				AND tbl_scrap_roleuser.na = \'n\'
																				 AND tbl_scrap_roleuser.del = \'n\'
													 INNER JOIN tbl_scrap_role on tbl_scrap_role.kode_role = tbl_scrap_roleuser.kode_role
													 CROSS APPLY fnSplitString(tbl_scrap_roleuser.pabrik, \',\')
													 WHERE tbl_scrap_log_status.no_pp = \''.$param['no_pp'].'\'
													   AND tbl_scrap_role.level != \'13\'
													   AND CONVERT(VARCHAR, splitdata) = \''.$param['plant'].'\'
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
													  INNER JOIN tbl_scrap_roleuser ON tbl_scrap_roleuser.[user] = tbl_karyawan.nik
																				AND tbl_scrap_roleuser.na = \'n\'
																				 AND tbl_scrap_roleuser.del = \'n\'
													  INNER JOIN tbl_scrap_role on tbl_scrap_role.kode_role = tbl_scrap_roleuser.kode_role
													  CROSS APPLY fnSplitString(tbl_scrap_roleuser.pabrik, \',\')
													  INNER JOIN tbl_scrap_header ON tbl_scrap_header.status = CAST(tbl_scrap_role.level as varchar(20))
													  WHERE tbl_scrap_header.no_pp = \''.$param['no_pp'].'\'
														AND tbl_scrap_role.level != \'13\'
														AND CONVERT(VARCHAR, splitdata) = \''.$param['plant'].'\'
													  ORDER BY nilai');
			}

			$result = $query->result();

			if ($param['conn'] !== NULL)
				$this->general->closeDb();

			return $result;
		}

		function get_email_recipient_kunnr($param = NULL){
			if ($param['conn'] !== NULL)
				$this->general->connectDbPortal();


			
			$query	= $this->db->query('SELECT DISTINCT tbl_user.id_karyawan,
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
												INNER JOIN tbl_scrap_roleuser ON tbl_scrap_roleuser.[user] = tbl_karyawan.nik
																		AND tbl_scrap_roleuser.na = \'n\'
																		AND tbl_scrap_roleuser.del = \'n\'
												INNER JOIN tbl_scrap_role on tbl_scrap_role.kode_role = tbl_scrap_roleuser.kode_role
												CROSS APPLY fnSplitString(tbl_scrap_roleuser.pabrik, \',\')
												WHERE tbl_scrap_role.level = \'6\'
												AND CONVERT(VARCHAR, splitdata) = \''.$param['plant'].'\'
												ORDER BY nilai');
			

			$result = $query->result();

			if ($param['conn'] !== NULL)
				$this->general->closeDb();

			return $result;
		}

	}

?>
