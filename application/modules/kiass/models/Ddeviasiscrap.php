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

	class Ddeviasiscrap extends CI_Model {
        
        function get_no_deviasi($param = NULL){
            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
                $this->general->connectDbDefault();


            $kmtr = 'KMTR';

            $this->db->select('tbl_scrap_deviasi_header.*');
            $this->db->from('tbl_scrap_deviasi_header');
            $this->db->where('tbl_scrap_deviasi_header.plant', $param['plant']);
            $this->db->where_in('YEAR(tbl_scrap_deviasi_header.tanggal_pengajuan)', $param['year']);
            $this->db->like('no_deviasi', $param['plant'].'/'.$kmtr.'/'.$param['month'].'/'.$param['year'], 'before');
            
            $query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_deviasi_header_tahun($param = NULL){
            if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

            $this->db->select('YEAR(tbl_scrap_deviasi_header.tanggal_buat) as tahun');
            $this->db->from('tbl_scrap_deviasi_header');
            $this->db->where('tbl_scrap_deviasi_header.na', 'n');
            $this->db->where('tbl_scrap_deviasi_header.del', 'n');
            $this->db->order_by('YEAR(tbl_scrap_deviasi_header.tanggal_buat)', 'ASC');
            $this->db->group_by('YEAR(tbl_scrap_deviasi_header.tanggal_buat)');
            
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

			$this->db->select('tbl_scrap_log_status_deviasi.*');
			$this->db->select("CONVERT(VARCHAR(20), tbl_scrap_log_status_deviasi.tgl_status, 120) as format_tanggal_status");
            $this->db->select('tbl_scrap_role.nama_role');
            $this->db->select('tbl_karyawan.nama');
			$this->db->from('tbl_scrap_log_status_deviasi');
			
			$this->db->join('tbl_scrap_role', 'CAST(tbl_scrap_role.level as VARCHAR(50)) = tbl_scrap_log_status_deviasi.status', 'left');
			$this->db->join('tbl_user', 'tbl_user.id_user = tbl_scrap_log_status_deviasi.login_edit');
			$this->db->join('tbl_karyawan', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan');
			
            
            if (isset($param['no_deviasi']) && $param['no_deviasi'] !== NULL)
                $this->db->where('tbl_scrap_log_status_deviasi.no_deviasi', $param['no_deviasi']);
			
			$this->db->order_by('tbl_scrap_log_status_deviasi.tanggal_edit', 'DESC');
            
            $query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
        }

		// function get_deviasi_header($param = NULL)
		// {
		// 	if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
		// 		$this->general->connectDbDefault();

		// 	$this->db->select("tbl_scrap_deviasi_header.*");
		// 	$this->db->select("tbl_scrap_header.pembeli");
		// 	$this->db->select("tbl_scrap_file.location as filename");

		// 	$this->db->from('tbl_scrap_deviasi_header');
		// 	$this->db->join('tbl_scrap_header', 'tbl_scrap_deviasi_header.no_pp = tbl_scrap_header.no_pp', 'left');
		// 	$this->db->join('tbl_scrap_file', 'tbl_scrap_file.id_file = tbl_scrap_deviasi_header.id_lampiran_deviasi', 'left');


		// 	if (isset($param['no_deviasi']) && $param['no_deviasi'] !== NULL)
		// 		$this->db->where('tbl_scrap_deviasi_header.no_deviasi', $param['no_deviasi']);

		// 	$query = $this->db->get();
		// 	if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
		// 		$result = $query->row();
		// 	else $result = $query->result();

		// 	if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
		// 		$this->general->closeDb();

		// 	return $result;
		// }

		function get_deviasi_header($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

            $this->db->select("tbl_scrap_deviasi_header.*");
            $this->db->select("CONVERT(VARCHAR(10), CONVERT(DATE, tbl_scrap_deviasi_header.tanggal_pengajuan), 104) as format_tanggal_pengajuan");
            $this->db->select('CASE tbl_scrap_deviasi_header.status
					       		WHEN \'drop\' THEN \'<span class="label label-danger">DROP</span>\'
					       		WHEN \'deleted\' THEN \'<span class="label label-danger">DELETED</span>\'
					       		WHEN \'finish\' THEN \'<span class="label label-success">FINISH</span>\'
					       		ELSE \'<span class="label label-warning">ON PROGRESS</span>\'
					       END as view_status');
			$this->db->select("tbl_scrap_role.nama_role");
			$this->db->select("tbl_scrap_header.perihal");
			$this->db->select("tbl_scrap_header.pembeli");
			$this->db->select("tbl_scrap_header.is_spk");
            $this->db->select("tbl_scrap_mflow_approval.alias_flow");
			$this->db->select("tbl_scrap_file.location as filename");
			$this->db->select("tbl_file_fincon.location as filename_fincon");
			$this->db->select('(SELECT SUM(tbl_scrap_deviasi_detail.total_deviasi)
					FROM tbl_scrap_deviasi_detail
					WHERE tbl_scrap_deviasi_detail.no_deviasi = tbl_scrap_deviasi_header.no_deviasi
					AND tbl_scrap_deviasi_detail.del = \'n\') as nilai_pengajuan');

			$this->db->from('tbl_scrap_deviasi_header');
            $this->db->join('tbl_scrap_header', 'tbl_scrap_header.no_pp = tbl_scrap_deviasi_header.no_pp', 'left');
            $this->db->join('tbl_scrap_role', 'CAST(tbl_scrap_role.level as VARCHAR(50)) = tbl_scrap_deviasi_header.status
                                        AND tbl_scrap_role.na = \'n\'', 'left');
            $this->db->join('tbl_scrap_mflow_approval', 'tbl_scrap_mflow_approval.id_flow = tbl_scrap_deviasi_header.id_flow', 'left');
            $this->db->join('tbl_scrap_file', 'tbl_scrap_file.id_file = tbl_scrap_deviasi_header.id_lampiran_deviasi', 'left');
			$this->db->join('tbl_scrap_file tbl_file_fincon', 'tbl_file_fincon.id_file = tbl_scrap_deviasi_header.lampiran_fincon', 'left');

			if (isset($param['no_deviasi']) && $param['no_deviasi'] !== NULL)
                $this->db->where('tbl_scrap_deviasi_header.no_deviasi', $param['no_deviasi']);

            if (isset($param['plant']) && $param['plant'] !== NULL)
                // $this->db->where('tbl_scrap_header.plant', $param['plant']);
				$this->db->where_in('tbl_scrap_deviasi_header.plant', $param['plant']);
			
			if((isset($param['status_in']) != NULL && $param['status_in'] != NULL) && (isset($param['in_not_in']) != NULL && $param['in_not_in'] != NULL))
				$this->db->where('tbl_scrap_deviasi_header.status '.$param['in_not_in'].' ('.$param['status_in'].')');
				
			if (isset($param['statuss']) && $param['statuss'] !== NULL)
                $this->db->where_in('tbl_scrap_deviasi_header.status', $param['statuss']);

            if (isset($param['approval']) && $param['approval'] !== NULL)
				$this->db->where_in('tbl_scrap_deviasi_header.status', $param['approval']);
				
			if (isset($param['approval']) && $param['approval'] !== NULL){
				if (count($param['approval']) == 1) {
					$st = implode(',', $param['approval']);
					$this->db->where('tbl_scrap_deviasi_header.status = ', $st);
				}else{	
					$st = "'".implode("','", $param['approval'])."'";
					$this->db->where('tbl_scrap_deviasi_header.status IN ('.$st.')');		
				}

			}

            if (isset($param['year']) && $param['year'] !== NULL)
                // $this->db->where('YEAR(tbl_scrap_header.tanggal_buat)', $param['year']);
                $this->db->where_in('YEAR(tbl_scrap_deviasi_header.tanggal_buat)', $param['year']);
            
			if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_deviasi_header.del', 'n');
				$this->db->where('tbl_scrap_deviasi_header.na', 'n');
			}

			$this->db->order_by('tbl_scrap_deviasi_header.tanggal_buat', 'DESC');


			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_deviasi_detail($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_deviasi_detail.*");
			$this->db->select("tbl_scrap_analisa_harga.*");
			$this->db->select("vw_material_spec_byplant.full_description");
            $this->db->select("vw_material_spec_byplant.classification");
			$this->db->select("zdmmktsupp18.name1");

			$this->db->from('tbl_scrap_deviasi_detail');
			$this->db->join('tbl_scrap_analisa_harga', 'tbl_scrap_analisa_harga.id_row_analisa = tbl_scrap_deviasi_detail.id_row_analisa', 'left');
			$this->db->join("tbl_scrap_calon_pembeli", "tbl_scrap_calon_pembeli.id_row_analisa = tbl_scrap_analisa_harga.id_row_analisa and tbl_scrap_calon_pembeli.no_pp = tbl_scrap_analisa_harga.no_pp and tbl_scrap_calon_pembeli.no_urut = tbl_scrap_analisa_harga.pemenang", "left");
			$this->db->join('vw_material_spec_byplant', 'vw_material_spec_byplant.id = tbl_scrap_analisa_harga.kode_material and vw_material_spec_byplant.plant = tbl_scrap_deviasi_detail.plant and vw_material_spec_byplant.na = \'n\'', 'left');
            $this->db->join('DASHBOARDDEV.dbo.zdmmktsupp18 zdmmktsupp18', '(zdmmktsupp18.KUNNR COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_scrap_deviasi_detail.kode_customer and (zdmmktsupp18.vkorg COLLATE SQL_Latin1_General_CP1_CI_AS) = tbl_scrap_deviasi_detail.plant', 'left');

			if (isset($param['no_deviasi']) && $param['no_deviasi'] !== NULL)
				$this->db->where('tbl_scrap_deviasi_detail.no_deviasi', $param['no_deviasi']);
			
			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

        function get_data_pengajuan($param = NULL)
		{
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_calon_pembeli.id_calon_pembeli");
			$this->db->select("tbl_scrap_calon_pembeli.kode_customer");
			$this->db->select("tbl_scrap_calon_pembeli.nama_pembeli");
			$this->db->select("tbl_scrap_calon_pembeli.no_hp");
			$this->db->select("tbl_scrap_header.id_flow");
            $this->db->select("tbl_scrap_analisa_harga.*");
                        			
            
            $this->db->from('tbl_scrap_analisa_harga');
			$this->db->join('tbl_scrap_header', 'tbl_scrap_analisa_harga.no_pp = tbl_scrap_header.no_pp', 'left');
            $this->db->join("tbl_scrap_calon_pembeli", "tbl_scrap_calon_pembeli.id_row_analisa = tbl_scrap_analisa_harga.id_row_analisa and tbl_scrap_calon_pembeli.no_pp = tbl_scrap_analisa_harga.no_pp and tbl_scrap_calon_pembeli.no_urut = tbl_scrap_analisa_harga.pemenang", "left");
            
			if (isset($param['no_pp']) && $param['no_pp'] !== NULL)
				$this->db->where('tbl_scrap_analisa_harga.no_pp', $param['no_pp']);

			// if (isset($param['active']) && $param['active'] !== NULL){
				$this->db->where('tbl_scrap_analisa_harga.del', 'n');
				$this->db->where('tbl_scrap_analisa_harga.na', 'n');
			// }

			$query = $this->db->get();
			if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
				$result = $query->row();
			else $result = $query->result();

			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->closeDb();

			return $result;
		}

		function get_data_scrap_sap($param = NULL){
			if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
				$this->general->connectDbDefault();

			$this->db->select("tbl_scrap_deviasi_detail.*");
			$this->db->select("tbl_scrap_analisa_harga.kode_material");
			$this->db->select("tbl_scrap_analisa_harga.uom");
			$this->db->select("tbl_scrap_calon_pembeli.id_calon_pembeli");
			$this->db->select("tbl_scrap_calon_pembeli.no_hp");
			$this->db->select("tbl_scrap_header.perihal");
			
			$this->db->from('tbl_scrap_deviasi_detail');
			$this->db->join('tbl_scrap_analisa_harga', 'tbl_scrap_deviasi_detail.id_row_analisa = tbl_scrap_analisa_harga.id_row_analisa', 'left');
			$this->db->join('tbl_scrap_calon_pembeli', 'tbl_scrap_calon_pembeli.no_urut = tbl_scrap_analisa_harga.pemenang and tbl_scrap_calon_pembeli.id_row_analisa = tbl_scrap_analisa_harga.id_row_analisa', 'left');
			$this->db->join('tbl_scrap_header', 'tbl_scrap_analisa_harga.no_pp = tbl_scrap_header.no_pp', 'left');

			if (isset($param['no_deviasi']) && $param['no_deviasi'] !== NULL)
                $this->db->where('tbl_scrap_deviasi_detail.no_deviasi', $param['no_deviasi']);

				
			$this->db->order_by('tbl_scrap_deviasi_detail.no_so', 'ASC');

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

			$query	= $this->db->query('SELECT DISTINCT tbl_user.id_karyawan,
														tbl_karyawan.nama,
														tbl_karyawan.email,
													   CASE
															WHEN tbl_karyawan.gender = \'l\' THEN \'Bapak\'
															WHEN tbl_karyawan.gender = \'p\' THEN \'Ibu\'
															ELSE \'\'
												   		END as gender,
													   nilai = \'cc\'
												  FROM tbl_scrap_log_status_deviasi
												 INNER JOIN tbl_user ON tbl_user.id_user = tbl_scrap_log_status_deviasi.login_edit
												 INNER JOIN tbl_karyawan ON tbl_user.id_karyawan = tbl_karyawan.nik
												 INNER JOIN tbl_scrap_roleuser ON tbl_scrap_roleuser.[user] = tbl_karyawan.nik
																			AND tbl_scrap_roleuser.na = \'n\'
												 						    AND tbl_scrap_roleuser.del = \'n\'
												 INNER JOIN tbl_scrap_role on tbl_scrap_role.kode_role = tbl_scrap_roleuser.kode_role
												 CROSS APPLY fnSplitString(tbl_scrap_roleuser.pabrik, \',\')
												 WHERE tbl_scrap_log_status_deviasi.no_deviasi = \''.$param['no_deviasi'].'\'
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
												  INNER JOIN tbl_scrap_deviasi_header ON tbl_scrap_deviasi_header.status = CAST(tbl_scrap_role.level as varchar(20))
												  WHERE tbl_scrap_deviasi_header.no_deviasi = \''.$param['no_deviasi'].'\'
												    AND tbl_scrap_role.level != \'13\'
												    AND CONVERT(VARCHAR, splitdata) = \''.$param['plant'].'\'
												  ORDER BY nilai');
			$result = $query->result();

			if ($param['conn'] !== NULL)
				$this->general->closeDb();

			return $result;
		}

	}

?>
