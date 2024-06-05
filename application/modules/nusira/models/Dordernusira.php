<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
        @application  : 
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 22-Jan-19
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */

	class Dordernusira extends CI_Model {
		/*
		 * GET DATA BUDGET
		 */
		function get_data_budget($conn = NULL, $tahun = NULL, $plant = NULL, $no_budget = NULL, $like = NULL, $budget_pi = NULL, $no_pi = NULL, $edit = NULL, $second_use_sipil = NULL, $is_main = NULL, $switch_range = NULL, $sisa_maintenance = NULL, $non_maintenance = NULL, $id_divisi = NULL, $not_in = NULL, $is_maintenance = NULL, $status = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_budget.no_budget as id,
							   tbl_pi_budget.plant,
							   tbl_pi_budget.no_budget,
							   tbl_pi_budget.investasi,
							   tbl_pi_mtujuan_inv.tujuan_inv,
							   tbl_pi_budget.spesifikasi,
							   tbl_pi_budget.detail_pekerjaan,
							   tbl_pi_budget.justifikasi,
							   tbl_pi_mdepart.departemen,
							   tbl_pi_mtipe_inv.tipe_inv,
							   tbl_pi_mkategori.kategori,
							   tbl_pi_budget.masa_manfaat,
							   tbl_pi_mjenis_asset.jenis_asset,
							   tbl_pi_budget.budget,
							   tbl_pi_budget.remaining,
							   CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_budget.budget), 1) as budget_money,
							   CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_budget.remaining), 1) as remaining_money,
							   tbl_pi_budget.start_date,
							   tbl_pi_budget.end_date,
							   tbl_pi_budget.nilai_depresiasi,
							   CONVERT(VARCHAR(15), tbl_pi_budget.nilai_depresiasi) as nilai_depresiasi_str,
							   tbl_pi_budget.coa,
							   tbl_pi_budget.status,
							   tbl_pi_budget.id_mtujuan_inv,
							   CASE 
									WHEN (tbl_pi_budget.status = 0 OR tbl_pi_budget.is_available =\'n\') THEN \'<span class="label label-danger">DROP</span>\'
									WHEN tbl_pi_budget.status = 1 THEN \'<span class="label label-success">ACTIVE</span>\'
							   END as view_status');
			if ($no_pi !== NULL) {
				$this->db->select(',(
						        SELECT value_budget_referensi 
						        FROM tbl_pi_referensi_budget
						        WHERE dbo.tbl_pi_referensi_budget.no_budget = dbo.tbl_pi_budget.no_budget 
						        AND no_pi = \'' . $no_pi . '\'
						       ) as value_when_select');
				$this->db->select(',(
						        SELECT remaining_budget_referensi 
						        FROM tbl_pi_referensi_budget
						        WHERE dbo.tbl_pi_referensi_budget.no_budget = dbo.tbl_pi_budget.no_budget 
						        AND no_pi = \'' . $no_pi . '\'
						       ) as remaining_when_select');
			}
			$this->db->from('tbl_pi_budget');
			$this->db->join('tbl_pi_mtujuan_inv', 'tbl_pi_budget.id_mtujuan_inv = tbl_pi_mtujuan_inv.id_mtujuan_inv 
										   	   AND tbl_pi_mtujuan_inv.na = \'n\' 
										   	   AND tbl_pi_mtujuan_inv.del = \'n\'', 'inner');
			$this->db->join('tbl_pi_mdepart', 'tbl_pi_budget.id_mdepart = tbl_pi_mdepart.id_mdepart 
									   	   AND tbl_pi_mdepart.na = \'n\' 
									   	   AND tbl_pi_mdepart.del = \'n\'', 'inner');
			$this->db->join('tbl_pi_mtipe_inv', 'tbl_pi_budget.id_mtipe_inv = tbl_pi_mtipe_inv.id_mtipe_inv
									     	 AND tbl_pi_mtipe_inv.na = \'n\' 
									     	 AND tbl_pi_mtipe_inv.del = \'n\'', 'inner');
			$this->db->join('tbl_pi_mkategori', 'tbl_pi_budget.id_mkategori = tbl_pi_mkategori.id_mkategori
									     	 AND tbl_pi_mkategori.na = \'n\' 
									     	 AND tbl_pi_mkategori.del = \'n\'', 'inner');
			$this->db->join('tbl_pi_mjenis_asset', 'tbl_pi_budget.id_mjenis_asset = tbl_pi_mjenis_asset.id_mjenis_asset
									     		AND tbl_pi_mjenis_asset.na = \'n\' 
									     		AND tbl_pi_mjenis_asset.del = \'n\'', 'inner');
			$this->db->where('YEAR(tbl_pi_budget.start_date)', $tahun);
			if ($plant !== NULL) {
				$this->db->where_in('tbl_pi_budget.plant', $plant);
			}
			if ($no_budget !== NULL) {
				$this->db->where('tbl_pi_budget.no_budget', $no_budget);
			}
			if ($like !== NULL) {
				$this->db->where('( tbl_pi_budget.no_budget LIKE \'%' . $like . '%\' ESCAPE \'!\' OR tbl_pi_budget.investasi LIKE \'%' . $like . '%\' ESCAPE \'!\' )');
			}
			if ($second_use_sipil != NULL) {
				$this->db->where('tbl_pi_budget.status != 0');
				if ($edit == NULL) {
					$this->db->where('tbl_pi_budget.no_budget NOT IN (SELECT tbl_pi_referensi_budget.no_budget
																	FROM tbl_pi_referensi_budget
																   INNER JOIN tbl_pi_header ON tbl_pi_header.no_pi = tbl_pi_referensi_budget.no_pi
																   WHERE tbl_pi_header.status <> \'finish\'
																     AND tbl_pi_header.status <> \'deleted\')'); // remark cr 1836
				}
			}
			if ($second_use_sipil == "unbudgeted" || $is_main == 'no') {
				if ($no_pi !== NULL) {
					$query = "OR tbl_pi_budget.budget = (
				                                      SELECT value_budget_referensi 
				                                      FROM tbl_pi_referensi_budget
				                                      WHERE dbo.tbl_pi_referensi_budget.no_budget = dbo.tbl_pi_budget.no_budget 
				                                      AND no_pi = '" . $no_pi . "'
				                                     )";
				}
				else $query = "";
				$this->db->where('(tbl_pi_budget.budget = tbl_pi_budget.remaining ' . $query . ')');
				$this->db->not_like('tbl_pi_mtujuan_inv.tujuan_inv', 'Top Down', 'after');
				$this->db->not_like('tbl_pi_mtujuan_inv.tujuan_inv', 'Carry Forward', 'after');
			}
			if ($budget_pi !== NULL) {
				if ($edit !== NULL) {
					$query = "OR (tbl_pi_mkategori.kategori = 'Bangunan' AND tbl_pi_budget.remaining > 0)";
					if ($no_pi !== NULL) {
						$query .= " OR tbl_pi_budget.budget = (
				                                      SELECT value_budget_referensi 
				                                      FROM tbl_pi_referensi_budget
				                                      WHERE dbo.tbl_pi_referensi_budget.no_budget = dbo.tbl_pi_budget.no_budget 
				                                      AND no_pi = '" . $no_pi . "'
				                                     )";
					}
				}
				else $query = "";
				$this->db->where('( tbl_pi_budget.remaining > 0 ' . $query . ')');
				$this->db->where('tbl_pi_budget.is_available = \'y\'');
			}

			if ($switch_range !== NULL) {
				$this->db->where($switch_range, '10000000');
			}

			if ($sisa_maintenance == NULL) {
				$this->db->not_like('tbl_pi_budget.no_budget', 'sisa', 'both');
			}

			if($non_maintenance !== NULL) {
				$this->db->group_start();
				$this->db->where('tbl_pi_budget.is_maintenance', 'n');
				$this->db->or_where('tbl_pi_budget.is_maintenance IS NULL');
				$this->db->group_end();
			}else if ($is_maintenance !== NULL) {
				$this->db->where('tbl_pi_budget.is_maintenance', 'y');
			}

			if($id_divisi !== NULL){
				$this->db->where_in('tbl_pi_budget.id_divisi', $id_divisi);
			}

			if ($not_in !== NULL) {
				$this->db->where_not_in('tbl_pi_budget.no_budget', $not_in);
			}

			if ($status == NULL) {
				$this->db->where('tbl_pi_budget.status', 1);
			}

			$this->db->where('tbl_pi_budget.na', 'n');
			$this->db->where('tbl_pi_budget.del', 'n');
			$this->db->order_by('tbl_pi_budget.no_budget', 'ASC');

			$query = $this->db->get();

			if ($no_budget !== NULL) {
				$result = $query->row();
			}
			else
				$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*
		 * GET DATA USER ROLE PI
		 */
		function get_data_userrole($conn = NULL, $user = NULL, $level = NULL, $all = NULL, $nik = NULL, $limit = NULL, $division_name = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_rolenik.*,
						   CASE
						   		WHEN tbl_pi_rolenik.na = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active, 
						   tbl_pi_role.level,
						   tbl_pi_rolenik.na as role_nik_na');
			$this->db->select('tbl_user.*');
			$this->db->select('tbl_karyawan.*');
			$this->db->select('tbl_pi_role.*');
			$this->db->from('tbl_pi_rolenik');
			$this->db->join('tbl_pi_role', 'tbl_pi_role.kode_role = tbl_pi_rolenik.kode_role', 'inner');
			$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_pi_rolenik.nik', 'inner');
			$this->db->join('tbl_user', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'inner');
			$this->db->join('tbl_divisi', 'tbl_divisi.id_divisi = tbl_user.id_divisi', 'left');
			if ($user != NULL) {
				$this->db->where('tbl_pi_rolenik.id_rolenik', $user);
			}
			if ($level != NULL) {
				$this->db->where('tbl_pi_role.nama_role', $level);
			}
			if ($all == NULL) {
				$this->db->where('tbl_pi_rolenik.na', 'n');
				$this->db->where('tbl_pi_rolenik.del', 'n');
			}
			if ($nik != NULL) {
				$this->db->where('tbl_pi_rolenik.nik', $nik);
			}
			if ($division_name != NULL) {
				$this->db->where('tbl_divisi.nama', $division_name);
			}
			if ($limit != NULL) {
				$this->db->limit($limit);
			}

			$this->db->order_by('tbl_pi_rolenik.kode_role', 'ASC');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*
		 * GET DATA TUJUAN INVESTASI PI
		 */
		function get_data_tujuan($conn = NULL, $id_mtujuan_inv = NULL, $all = NULL, $tujuan = NULL, $id_mtujuan_inv_in = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('*,
						   CASE
						   		WHEN tbl_pi_mtujuan_inv.na = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
			$this->db->from('tbl_pi_mtujuan_inv');
			if ($id_mtujuan_inv != NULL) {
				$this->db->where('tbl_pi_mtujuan_inv.id_mtujuan_inv', $id_mtujuan_inv);
			}
			if ($id_mtujuan_inv_in != NULL) {
				$this->db->where_in('tbl_pi_mtujuan_inv.id_mtujuan_inv', $id_mtujuan_inv_in);
			}
			if ($tujuan != NULL && trim($tujuan) !== "") {
				$this->db->where('tbl_pi_mtujuan_inv.tujuan_inv', $tujuan);
			}
			if ($all == NULL) {
				$this->db->where('na', 'n');
				$this->db->where('del', 'n');
			}
			$this->db->where('nsw', 1);
			$this->db->order_by('id_mtujuan_inv', 'ASC');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*
		 * GET DATA SESSION PI
		 */
		function get_data_session($conn = NULL, $id_user) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_user.*');
			$this->db->select('tbl_karyawan.*');
			$this->db->select('tbl_pi_rolenik.*');
			$this->db->select('tbl_pi_role_menu.*');
			$this->db->select('tbl_pi_role.*');
			$this->db->select('CAST(
						   (SELECT DISTINCT tbl_inv_pabrik.nama +RTRIM(\',\')
						    FROM tbl_pi_rolenik_pabrik as tbl2
						    INNER JOIN tbl_inv_pabrik ON tbl2.kode_pabrik = dbo.tbl_inv_pabrik.kode
						    WHERE tbl_pi_rolenik.id_rolenik = tbl2.id_rolenik
						    AND tbl2.na = \'n\'
						    AND tbl2.del = \'n\'
						    FOR XML PATH (\'\')) as VARCHAR(MAX))  AS pabrik_list');
			$this->db->select('CAST(
						   (SELECT DISTINCT tbl_inv_pabrik.kode +RTRIM(\',\')
						    FROM tbl_pi_rolenik_pabrik as tbl2
						    INNER JOIN tbl_inv_pabrik ON tbl2.kode_pabrik = dbo.tbl_inv_pabrik.kode
						    WHERE tbl_pi_rolenik.id_rolenik = tbl2.id_rolenik
						    AND tbl2.na = \'n\'
						    AND tbl2.del = \'n\'
						    FOR XML PATH (\'\')) as VARCHAR(MAX))  AS pabrik_list_kode');
			$this->db->from('tbl_user');
			$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan 
										 AND tbl_karyawan.na = \'n\' 
										 AND tbl_karyawan.del = \'n\'', 'inner');
			$this->db->join('tbl_pi_rolenik', 'tbl_pi_rolenik.nik = tbl_karyawan.nik 
										   AND tbl_pi_rolenik.na = \'n\' 
										   AND tbl_pi_rolenik.del = \'n\'', 'inner');
			$this->db->join('tbl_pi_role_menu', 'tbl_pi_role_menu.kode_role = tbl_pi_rolenik.kode_role 
											 AND tbl_pi_role_menu.na = \'n\' 
											 AND tbl_pi_role_menu.del = \'n\'', 'inner');
			$this->db->join('tbl_pi_role', 'tbl_pi_role.kode_role = tbl_pi_rolenik.kode_role
										AND tbl_pi_role.na = \'n\'
										AND tbl_pi_role.del = \'n\'', 'inner');
			$this->db->where('tbl_user.id_user', $id_user);
			$this->db->where('tbl_user.na', 'n');
			$this->db->where('tbl_user.del', 'n');
			$query  = $this->db->get();
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*
		 * GENERATE NO PI
		 */
		function get_pi_no_pi($conn = NULL, $plant, $semester, $year) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_header.*');
			$this->db->from('tbl_pi_header');
			$this->db->where('tbl_pi_header.plant', $plant);
			$this->db->where_in('YEAR(tbl_pi_header.tanggal)', $year);
			$this->db->like('no_pi', $plant . '/' . $semester . '/' . $year, 'before');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*
		 * GET DATA PIC PROYEK
		 */
		function get_pic_proyek($conn = NULL, $plant) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_user.*');
			$this->db->select('tbl_karyawan.*');
			$this->db->from('tbl_karyawan');
			$this->db->join('tbl_user', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'inner');
			$this->db->where('(tbl_karyawan.posst = \'manager pembelian\'
						  OR tbl_karyawan.posst = \'manager kantor\'
						  OR tbl_karyawan.posst = \'manager pabrik\')');
			$this->db->where_in('tbl_karyawan.gsber', $plant);
			$this->db->where('tbl_karyawan.na', 'n');
			$this->db->where('tbl_karyawan.del', 'n');
			$this->db->order_by('tbl_karyawan.nama', 'ASC');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA HEADER PI
		 */
		function get_pi_header($conn = NULL, $plant = NULL, $thn = NULL, $no_pi = NULL, $plant_in = NULL, $status = NULL, $in_not_in = NULL, $status_in = NULL, $tujuan_in = NULL, $array_nodoc = NULL, $nsw = NULL, $need_confirm_daliv_date = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_header.*');
			$this->db->select('tbl_pi_file.filename');
			$this->db->select('tbl_pi_file.size');
			$this->db->select('tbl_pi_file.ext');
			$this->db->select('tbl_pi_file.location');
			$this->db->select('tbl_pi_mtujuan_inv.tujuan_inv as tujuan_investasi');
			$this->db->select('tbl_pi_role.nama_role as status_pi');
			$this->db->select('CASE tbl_pi_header.status
                                    WHEN \'deleted\' THEN (SELECT top 1(tbl_pi_role.nama_role) 
                                                             FROM tbl_pi_role			 
                                                             JOIN tbl_pi_log_status on tbl_pi_log_status.[status] = tbl_pi_role.[level]
                                                            WHERE tbl_pi_log_status.no_pi = tbl_pi_header.no_pi order by tbl_pi_log_status.tgl_status desc
                                                           )
                                    ELSE NULL
                               END as status_pi_delete');
			$this->db->select('tbl_pi_role.akses_delete');
			$this->db->select('(SELECT COUNT(*)
					          FROM tbl_pi_detail
					         WHERE tbl_pi_detail.plant = tbl_pi_header.plant
					           AND tbl_pi_detail.no_pi = tbl_pi_header.no_pi
					           AND tbl_pi_detail.is_done_recom = 0
					           AND tbl_pi_detail.na = \'n\'
					           AND tbl_pi_detail.del = \'n\'
					       ) as jml_belum_rekom');
			$this->db->select('(SELECT COUNT(*)
					          FROM tbl_pi_detail
					         WHERE tbl_pi_detail.plant = tbl_pi_header.plant
					           AND tbl_pi_detail.no_pi = tbl_pi_header.no_pi
					           AND tbl_pi_detail.is_done_recom = 1
					           AND tbl_pi_detail.na = \'n\'
					           AND tbl_pi_detail.del = \'n\'
					       ) as jml_sdh_rekom');
			$this->db->select('(SELECT COUNT(*)
					          FROM tbl_pi_detail
					         WHERE tbl_pi_detail.plant = tbl_pi_header.plant
					           AND tbl_pi_detail.no_pi = tbl_pi_header.no_pi
					           AND tbl_pi_detail.acc_assign IS NULL
					           AND tbl_pi_detail.tipe_pi IS NOT NULL
					           AND tbl_pi_detail.na = \'n\'
					           AND tbl_pi_detail.del = \'n\'
					       ) as jml_blm_no_asset');
			$this->db->select('(SELECT COUNT(*)
					          FROM tbl_pi_detail
					         WHERE tbl_pi_detail.plant = tbl_pi_header.plant
					           AND tbl_pi_detail.no_pi = tbl_pi_header.no_pi
					           AND tbl_pi_detail.nsw_durasi_mgg IS NULL
					           AND tbl_pi_detail.tipe_pi IS NOT NULL
							   AND perm_invest NOT LIKE \'Biaya pengiriman%\'
							   AND spesifikasi NOT LIKE \'Biaya pengiriman%\'
					           AND tbl_pi_detail.status_nsw IS NULL
					           AND tbl_pi_detail.na = \'n\'
					           AND tbl_pi_detail.del = \'n\'
					       ) as jml_blm_req_delivdate');
			$this->db->select('(SELECT SUM(tbl_pi_detail.total) 
				              FROM tbl_pi_detail 
				             WHERE tbl_pi_detail.plant = tbl_pi_header.plant 
				               AND tbl_pi_detail.no_pi = tbl_pi_header.no_pi 
				               AND tbl_pi_detail.na = \'n\' 
				               AND tbl_pi_detail.del = \'n\' ) as sum_detail');
			$this->db->select('(SELECT TOP 1 tbl_pi_dev_capex.nilai_terbaru
					          FROM tbl_pi_dev_capex
					         WHERE tbl_pi_dev_capex.plant = tbl_pi_header.plant 
					           AND tbl_pi_dev_capex.no_pi = tbl_pi_header.no_pi
					           AND tbl_pi_dev_capex.status = \'finish\'
					         ORDER BY tbl_pi_dev_capex.tanggal_buat DESC) as capex_value');
			$this->db->select('(SELECT SUM(tbl_pi_rekom_vendor_header.harga)
					          FROM tbl_pi_rekom_vendor_header
					         WHERE tbl_pi_rekom_vendor_header.plant = tbl_pi_header.plant
					           AND tbl_pi_rekom_vendor_header.no_pi = tbl_pi_header.no_pi) as current_total_rekom_vendor');
			$this->db->select('(SELECT CONVERT(VARCHAR, CONVERT(MONEY, SUM(tbl_pi_rekom_vendor_header.harga)), 1)
					          FROM tbl_pi_rekom_vendor_header
					         WHERE tbl_pi_rekom_vendor_header.plant = tbl_pi_header.plant
					           AND tbl_pi_rekom_vendor_header.no_pi = tbl_pi_header.no_pi) as total_rekom_vendor_money');
			$this->db->select('(SELECT tbl_pi_role.app_lim_val 
					          FROM tbl_pi_role
					         WHERE tbl_pi_role.nama_role = \'CEO Region\'
					           AND tbl_pi_role.na = \'n\' 
				               AND tbl_pi_role.del = \'n\') as ceo_reg_app_limit');
			$this->db->select('(SELECT tbl_pi_dev_capex.no_dev_capex
					          FROM tbl_pi_dev_capex
					         WHERE tbl_pi_dev_capex.plant = tbl_pi_header.plant
					           AND tbl_pi_dev_capex.no_pi = tbl_pi_header.no_pi
					           AND tbl_pi_dev_capex.status != \'finish\'
					           AND tbl_pi_dev_capex.status != \'drop\'
					           AND tbl_pi_dev_capex.na = \'n\' 
					           AND tbl_pi_dev_capex.del = \'n\') as capex_active');
			$this->db->select('CASE tbl_pi_header.status
					       		WHEN \'drop\' THEN \'<span class="label label-danger">DROP</span>\'
					       		WHEN \'deleted\' THEN \'<span class="label label-danger">DELETED</span>\'
					       		WHEN \'finish\' THEN \'<span class="label label-success">FINISH</span>\'
					       		ELSE \'<span class="label label-warning">ON PROGRESS</span>\'
					       END as view_status');
			$this->db->from('tbl_pi_header');
			$this->db->join('tbl_pi_role', 'CAST(tbl_pi_role.level as VARCHAR(50)) = tbl_pi_header.status
										AND tbl_pi_role.na = \'n\'', 'left');
			$this->db->join('tbl_pi_file', 'tbl_pi_file.id_file = tbl_pi_header.id_file', 'left');
			$this->db->join('tbl_pi_mtujuan_inv', 'tbl_pi_mtujuan_inv.id_mtujuan_inv = tbl_pi_header.tujuan_inv', 'left');
			if ($plant != NULL) {
				$this->db->where('tbl_pi_header.plant', $plant);
			}
			else if ($plant_in != NULL) {
				if (is_string($plant_in))
					$plant_in = explode(",", $plant_in);
				$this->db->where_in('tbl_pi_header.plant', $plant_in);
			}
			if ($thn != NULL) {
				$this->db->where_in('YEAR(tbl_pi_header.tanggal)', $thn);
			}
			if ($status !== NULL && $array_nodoc == NULL) {

				$this->db->where('tbl_pi_header.status = CAST(' . $status . ' as VARCHAR(50))');
			}
			else if ($array_nodoc !== NULL && $status !== NULL) {
				$this->db->where('( (tbl_pi_header.status = CAST(' . $status . ' as VARCHAR(50)) ) OR (tbl_pi_header.no_pi in(' . $array_nodoc . ')) )');
			}
			if ($status_in != NULL) {
				$this->db->where('tbl_pi_header.status ' . $in_not_in . ' (' . $status_in . ')');
			}
			if ($tujuan_in != NULL) {
				$this->db->where('tbl_pi_header.tujuan_inv IN (' . $tujuan_in . ')');
			}
			if ($nsw != NULL) {
				$this->db->where('tbl_pi_header.tipe_pi IS NULL');
				if ($need_confirm_daliv_date != NULL) {
					$this->db->where('(CASE
										WHEN isnumeric(tbl_pi_header.status) = 1 THEN tbl_pi_header.status
										ELSE 4
									   END) > 3');
					$this->db->where('tbl_pi_header.nsw_check NOT IN (1)');
				}
			}
			$this->db->where('tbl_pi_header.na', 'n');
			$this->db->where('tbl_pi_header.del', 'n');

			if ($no_pi != NULL) {
				$this->db->where('tbl_pi_header.no_pi', $no_pi);
				$query  = $this->db->get();
				$result = $query->row();
			}
			else {
				$this->db->order_by('tbl_pi_header.tanggal_buat', 'DESC');
				$query  = $this->db->get();
				$result = $query->result();
			}

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA DETAIL PI
		 */
		function get_pi_detail($conn = NULL, $no_pi = NULL, $not_rekom = NULL, $no = NULL, $no_rekom = NULL, $no_so = NULL, $matnr = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_detail.no_pi + \'-\' + CAST(tbl_pi_detail.[no] AS VARCHAR(100)) as ID');
			$this->db->select('tbl_pi_detail.*');
			if ($no_so != NULL) {
				$this->db->select('tbl_pi_header.no_so');
				$this->db->select('tbl_pi_header.no_po');
			}
			$this->db->from('tbl_pi_detail');
			if ($no_so != NULL) {
				$this->db->from('tbl_pi_header', 'tbl_pi_header.no_pi = tbl_pi_detail.no_pi', 'inner');
				$this->db->where('tbl_pi_header.no_so', $no_so);
				$this->db->where('tbl_pi_detail.matnr <> \'\'');
			}
			if ($no_pi != NULL) {
				$this->db->where('tbl_pi_detail.no_pi', $no_pi);
			}
			if ($no != NULL) {
				$this->db->where('tbl_pi_detail.no', $no);
			}
			if ($not_rekom != NULL) {
				$this->db->where('tbl_pi_detail.is_done_recom = 0');
			}
			if ($no_rekom != NULL) {
				$this->db->where('(tbl_pi_detail.no IN (SELECT tbl_pi_rekom_vendor_detail.no_detail_pi 
				                                   	 FROM tbl_pi_rekom_vendor_detail 
				                                    WHERE tbl_pi_rekom_vendor_detail.no_pi = tbl_pi_detail.no_pi
				                                      AND tbl_pi_rekom_vendor_detail.no_detail_pi= tbl_pi_detail.no
                           							  AND no_rekom = ' . $no_rekom . ')');
				$this->db->or_where('tbl_pi_detail.is_done_recom = 0)');
			}
			if ($matnr != NULL) {
				$this->db->where('tbl_pi_detail.matnr', $matnr);
			}
			$this->db->where('tbl_pi_detail.na', 'n');
			$this->db->where('tbl_pi_detail.del', 'n');
			$this->db->order_by('tbl_pi_detail.no');
			$query = $this->db->get();
			if (($no_pi != NULL && $no != NULL) || ($no_pi != NULL && $matnr != NULL)) {
				$result = $query->row();
			}
			else {
				$result = $query->result();
			}

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA BUDGET PI
		 */
		function get_pi_budget($conn = NULL, $no_pi = NULL, $no_budget = NULL, $not_pi = NULL, $no_detail = NULL, $status_in = NULL, $status_not_in = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_referensi_budget.*');
			$this->db->select('CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_referensi_budget.value_budget_referensi), 1) as value_budget_referensi_money');
			$this->db->select('tbl_pi_referensi_budget.login_buat as login_buat_referensi');
			$this->db->select('tbl_pi_referensi_budget.tanggal_buat as tanggal_buat_referensi');
			$this->db->select('tbl_pi_referensi_budget.login_edit as login_edit_referensi');
			$this->db->select('tbl_pi_referensi_budget.tanggal_edit as tanggal_edit_referensi');
			$this->db->select('(SELECT SUM(tbl_pi_detail.total)
                                  FROM tbl_pi_detail
                                 WHERE tbl_pi_detail.no_pi = tbl_pi_referensi_budget.no_pi
                                   AND tbl_pi_detail.na = \'n\'
                                   AND tbl_pi_detail.del = \'n\'
                                   AND tbl_pi_detail.no = tbl_pi_referensi_budget.no_detail) as total_pi');
			$this->db->select('tbl_pi_budget.*,
						   CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_budget.budget), 1) as budget_money');
			$this->db->select('tbl_pi_mkategori.*');
			$this->db->select('tbl_pi_detail.matnr');
			$this->db->from('tbl_pi_referensi_budget');
			$this->db->join('tbl_pi_header', 'tbl_pi_referensi_budget.no_pi = tbl_pi_header.no_pi', 'inner');
			$this->db->join('tbl_pi_detail', 'tbl_pi_referensi_budget.no_pi = tbl_pi_detail.no_pi 
			                                  AND tbl_pi_header.no_pi = tbl_pi_detail.no_pi
			                                  AND tbl_pi_detail.na=\'n\'
			                                  AND tbl_pi_referensi_budget.no_detail = tbl_pi_detail.no', 'inner');
			$this->db->join('tbl_pi_budget', 'tbl_pi_referensi_budget.no_budget = tbl_pi_budget.no_budget', 'inner');
			$this->db->join('tbl_pi_mkategori', 'tbl_pi_mkategori.id_mkategori = tbl_pi_budget.id_mkategori', 'inner');
			if ($no_pi !== NULL) {
				$this->db->where('tbl_pi_referensi_budget.no_pi', $no_pi);
			}
			if ($no_budget !== NULL) {
				$this->db->where('tbl_pi_referensi_budget.no_budget', $no_budget);
			}
			if ($not_pi !== NULL) {
				$this->db->where('tbl_pi_referensi_budget.no_pi <> \'' . $not_pi . '\'');
			}
			if ($no_detail !== NULL) {
				$this->db->where('tbl_pi_referensi_budget.no_detail', $no_detail);
			}
			if ($status_in !== NULL) {
				$this->db->where_in('tbl_pi_header.status', $status_in);
			}
			if ($status_not_in !== NULL) {
				$this->db->where_not_in('tbl_pi_header.status', $status_not_in);
			}
			$this->db->where('tbl_pi_referensi_budget.na', 'n');
			$this->db->where('tbl_pi_referensi_budget.del', 'n');
			$this->db->order_by('ISNULL(tbl_pi_referensi_budget.no_pi, 999), ISNULL(tbl_pi_referensi_budget.no_detail, 999), tbl_pi_referensi_budget.no_urut');
			if ($no_pi != NULL && $no_budget != NULL) {
				$query = $this->db->get();
				return $query->row();
			}
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA LOG PI
		 */
		function get_pi_log($conn = NULL, $no_pi = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_log_status.no_pi,
			                   CONVERT(VARCHAR, tbl_pi_log_status.tgl_status, 120) as tgl_status,
			                   tbl_pi_log_status.action,
			                   tbl_pi_log_status.comment');
			$this->db->select('tbl_pi_role.*');
			$this->db->select('tbl_karyawan.*');
			$this->db->from('tbl_pi_log_status');
			$this->db->join('tbl_pi_role', 'tbl_pi_role.level = tbl_pi_log_status.status
										AND tbl_pi_role.na = \'n\'', 'inner');
			$this->db->join('tbl_user', 'tbl_user.id_user = tbl_pi_log_status.login_edit', 'inner');
			$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'inner');
			if ($no_pi != NULL) {
				$this->db->where('tbl_pi_log_status.no_pi', $no_pi);
			}
			$this->db->order_by('tbl_pi_log_status.tgl_status DESC');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA ATTACHMENT PI
		 */
		function get_pi_attachment($conn = NULL, $no_pi = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_file.*');
			$this->db->from('tbl_pi_header');
			$this->db->join('tbl_pi_file', '(tbl_pi_file.id_file = tbl_pi_header.id_file_div_head
			                             OR tbl_pi_file.id_file = tbl_pi_header.id_file_dept_head
			                             OR tbl_pi_file.id_file = tbl_pi_header.id_file_fincon)', 'inner');
			if ($no_pi != NULL) {
				$this->db->where('tbl_pi_header.no_pi', $no_pi);
			}
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA BERITA ACARA REKOM VENDOR
		 */
		function get_data_rekom_berita_acara($conn = NULL, $no_pi = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("tbl_pi_rekom_vendor_header.no_pi,
						   tbl_pi_rekom_vendor_header.no_rekom,
						   tbl_pi_file.filename,
						   tbl_pi_file.location");
			$this->db->from('tbl_pi_rekom_vendor_header');
			$this->db->join('tbl_pi_file', 'tbl_pi_file.id_file = tbl_pi_rekom_vendor_header.id_file', 'inner');
			if ($no_pi != NULL) {
				$this->db->where('tbl_pi_rekom_vendor_header.no_pi', $no_pi);
			}
			$this->db->where('tbl_pi_rekom_vendor_header.na', 'n');
			$this->db->where('tbl_pi_rekom_vendor_header.del', 'n');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET TOTAL REKOM VENDOR
		 */
		function get_data_total_rekom($conn = NULL, $no_pi = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select("SUM(tbl_pi_rekom_vendor_header.harga) as total_rekom");
			$this->db->from('tbl_pi_rekom_vendor_header');
			if ($no_pi != NULL) {
				$this->db->where('tbl_pi_rekom_vendor_header.no_pi', $no_pi);
			}
			$this->db->where('tbl_pi_rekom_vendor_header.na', 'n');
			$this->db->where('tbl_pi_rekom_vendor_header.del', 'n');
			$query = $this->db->get();
			if ($no_pi != NULL) {
				$result = $query->row();
			}
			else
				$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA CAPEX
		 */
		function get_capex($conn = NULL, $plant = NULL, $year = NULL, $no_pi = NULL, $no_dev_capex = NULL, $plant_in = NULL, $status = NULL, $onprogress = NULL, $all = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_dev_capex.*');
			$this->db->select('tbl_pi_header.kepada');
			$this->db->select('tbl_pi_header.tujuan_inv');
			$this->db->select('tbl_pi_mtujuan_inv.tujuan_inv as tujuan_investasi');
			$this->db->select('tbl_pi_header.perihal');
			$this->db->select('tbl_pi_role.nama_role as status_dev_capex');
			$this->db->select('( 
							SELECT tbl_pi_role.app_lim_val 
					          FROM tbl_pi_role
					         WHERE tbl_pi_role.nama_role = \'CEO Region\'
					           AND tbl_pi_role.na = \'n\' 
				               AND tbl_pi_role.del = \'n\') as ceo_reg_app_limit');
			$this->db->select('CASE tbl_pi_dev_capex.status
					       		WHEN \'finish\' THEN \'<span class="label label-success">FINISH</span>\'
					       		ELSE \'<span class="label label-warning">ON PROGRESS</span>\'
					       END as view_status');
			$this->db->select('(SELECT COUNT(*)
					          FROM tbl_pi_detail
					         WHERE tbl_pi_detail.plant = tbl_pi_header.plant
					           AND tbl_pi_detail.no_pi = tbl_pi_header.no_pi
					           AND tbl_pi_detail.is_done_recom = 1
					           AND tbl_pi_detail.na = \'n\'
					           AND tbl_pi_detail.del = \'n\'
					       ) as jml_sdh_rekom');
			$this->db->from('tbl_pi_dev_capex');
			$this->db->join('tbl_pi_header', 'tbl_pi_header.no_pi = tbl_pi_dev_capex.no_pi', 'inner');
			$this->db->join('tbl_pi_role', 'CAST(tbl_pi_role.level as VARCHAR(50)) = tbl_pi_dev_capex.status
										AND tbl_pi_role.na = \'n\'', 'left');
			$this->db->join('tbl_pi_mtujuan_inv', 'tbl_pi_mtujuan_inv.id_mtujuan_inv = tbl_pi_header.tujuan_inv', 'left');
			if ($plant != NULL) {
				$this->db->where('tbl_pi_dev_capex.plant', $plant);
			}
			else if ($plant_in != NULL) {
				$plant_in = explode(",", $plant_in);
				$this->db->where_in('tbl_pi_dev_capex.plant', $plant_in);
			}
			else {
				$this->db->where('tbl_pi_dev_capex.plant', $plant);
			}

			if ($year != NULL) {
				$this->db->where('YEAR(tbl_pi_dev_capex.tgl_dev_capex)', $year);
			}
			if ($no_dev_capex != NULL) {
				$this->db->where('tbl_pi_dev_capex.no_dev_capex', $no_dev_capex);
			}
			if ($no_pi != NULL) {
				$this->db->where('tbl_pi_dev_capex.no_pi', $no_pi);
			}
			if ($onprogress != NULL) {
				$this->db->where('tbl_pi_dev_capex.status != \'finish\'');
			}
			if ($status !== NULL) {
				$this->db->where('tbl_pi_dev_capex.status = CAST(' . $status . ' as VARCHAR(50))');
			}

			if ($all == NULL) {
				$this->db->where('tbl_pi_dev_capex.na = \'n\'');
				$this->db->where('tbl_pi_dev_capex.del = \'n\'');
			}
			$this->db->order_by('tbl_pi_dev_capex.tanggal_buat', 'DESC');
			$this->db->order_by('tbl_pi_dev_capex.no_pi', 'ASC');
			$query = $this->db->get();
			if ($no_dev_capex != NULL) {
				$result = $query->row();
			}
			else {
				$result = $query->result();
			}

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA LAST ACTION PI
		 */
		function get_last_action_pi($conn = NULL, $no_pi = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$query  = $this->db->query('
									SELECT TOP 1 tbl_pi_log_status.action,
										   tbl_pi_role.kode_role,
										   tbl_pi_role.level,
				             			   tbl_pi_role.nama_role
				             		  FROM tbl_pi_log_status
				             		  LEFT JOIN tbl_pi_role ON CAST(tbl_pi_role.level AS VARCHAR(100)) = tbl_pi_log_status.status
				             	   	 WHERE tbl_pi_log_status.no_pi = \'' . $no_pi . '\'
				             	   	 ORDER BY tbl_pi_log_status.tanggal_edit DESC
								  ');
			$result = $query->row();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA DEPT HEAD BY DIVISION
		 */
		function get_dept_head_in_division($conn = NULL, $divisi = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_user.*');
			$this->db->select('tbl_karyawan.*');
			$this->db->select('tbl_departemen.nama as dept');
			$this->db->from('tbl_user');
			$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'inner');
			$this->db->join('tbl_departemen', 'tbl_departemen.id_departemen = tbl_user.id_departemen', 'inner');
			$this->db->join('tbl_pi_rolenik', 'tbl_pi_rolenik.nik = tbl_karyawan.nik', 'inner');
			$this->db->join('tbl_pi_role', 'tbl_pi_role.kode_role = tbl_pi_rolenik.kode_role AND tbl_pi_role.nama_role = \'Department Head\'', 'inner');
			if ($divisi != NULL) {
				$this->db->where('tbl_user.id_divisi', $divisi);
				$this->db->where('tbl_user.id_departemen !=', '0');
				$this->db->where('tbl_user.id_seksi', '0');
			}
			$this->db->where('tbl_karyawan.na', 'n');
			$this->db->where('tbl_karyawan.del', 'n');
			$this->db->order_by('tbl_karyawan.nama', 'ASC');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA DISPOSISI PI
		 */
		function get_disposisi_pi($conn = NULL, $nik = NULL, $no_pi = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$where_noPi = $no_pi != NULL ? ' AND tbl_pi_header.no_pi = \'' . $no_pi . '\' ' : "";
			$query      = $this->db->query('SELECT tbl_pi_header.no_pi,
							        tbl_pi_disposisi.tanggal_awal,
							        tbl_pi_disposisi.tanggal_akhir,
							        tbl_pi_role.app_lim_val_disposisi,
							        tbl_pi_role.nama_role as role_pengaju_disposisi,
							        tbl_pi_role.level as lvl_pengaju_disposisi,
							        tbl_pi_disposisi.nik as pengaju_disposisi,
							        tbl_pi_rolenik_pabrik.kode_pabrik as pabrik_pengaju_disposisi,
							        tbl_user.id_user as id_user_pengaju_disposisi,
							        tbl_pi_role.if_disposisi,
							        tbl_pi_role.if_approve,
							        tbl_pi_role.if_assign,
							        tbl_pi_role.if_decline,
							        tbl_pi_role.if_drop,
							        tbl_pi_role2.nama_role as role_penerima_disposisi,
							        tbl_pi_rolenik2.nik as penerima_disposisi,
							        tbl_pi_role2.level as lvl_penerima_disposisi,
							        tbl_pi_rolenik_pabrik2.kode_pabrik as pabrik_penerima_disposisi,
							        tbl_user2.id_user as id_user_penerima_disposisi
							  FROM tbl_pi_header
								LEFT JOIN tbl_pi_disposisi 
								         ON tbl_pi_header.status = CONVERT(VARCHAR,tbl_pi_disposisi.[level])
								LEFT JOIN tbl_pi_role 
								         ON tbl_pi_disposisi.[level] = tbl_pi_role.level
								INNER JOIN tbl_pi_rolenik 
								         ON tbl_pi_rolenik.kode_role = tbl_pi_role.kode_role
								         --AND tbl_pi_rolenik.nik = \'' . $nik . '\'	
								INNER JOIN tbl_user ON tbl_user.id_karyawan = tbl_pi_rolenik.nik						        
								INNER JOIN tbl_pi_rolenik_pabrik 
								         ON tbl_pi_rolenik_pabrik.id_rolenik = tbl_pi_rolenik.id_rolenik
								        AND tbl_pi_rolenik_pabrik.kode_pabrik = tbl_pi_header.plant
								LEFT JOIN tbl_pi_role as tbl_pi_role2 
								         ON tbl_pi_role2.level = tbl_pi_role.if_disposisi   

								INNER JOIN tbl_pi_rolenik as tbl_pi_rolenik2 
								         ON tbl_pi_rolenik2.kode_role = tbl_pi_role2.kode_role
								         AND tbl_pi_rolenik2.nik = \'' . $nik . '\'
								INNER JOIN tbl_user as tbl_user2 ON tbl_user2.id_karyawan = tbl_pi_rolenik2.nik
								INNER JOIN tbl_pi_rolenik_pabrik as tbl_pi_rolenik_pabrik2 
								         ON tbl_pi_rolenik_pabrik2.id_rolenik = tbl_pi_rolenik2.id_rolenik
								        AND tbl_pi_rolenik_pabrik2.kode_pabrik = tbl_pi_rolenik_pabrik.kode_pabrik
								
								WHERE tbl_pi_disposisi.tanggal_awal <= CONVERT(VARCHAR(10), getdate(), 120)
								   	AND tbl_pi_disposisi.tanggal_akhir >= CONVERT(VARCHAR(10), getdate(), 120)
								   	--AND tbl_pi_header.no_pi = \'' . $no_pi . '\'
								   	' . $where_noPi . '
								   	AND tbl_pi_disposisi.nik = tbl_user.id_karyawan
								   	--AND tbl_pi_role.disposisi_departemen = tbl_user2.id_departemen
								   	--AND tbl_pi_disposisi.nik = tbl_pi_disposisi.nik 
									AND UPPER(tbl_pi_disposisi.status) = \'DISETUJUI\'
									AND tbl_pi_disposisi.na = \'n\' AND tbl_pi_disposisi.del = \'n\' 
								   	AND 
									  (
									    ( (tbl_pi_role.disposisi_divisi <> \'\' OR tbl_pi_role.disposisi_divisi <> null) 
									      AND tbl_pi_role.disposisi_divisi = tbl_user2.id_divisi  
									    ) 
									    OR
									    ( (tbl_pi_role.disposisi_departemen <> \'\' OR tbl_pi_role.disposisi_departemen <> null)     
									      AND tbl_pi_role.disposisi_departemen = tbl_user2.id_departemen 
									    )
									    OR ( tbl_pi_rolenik2.nik = \'' . $nik . '\' 
									    	AND tbl_pi_role2.nama_role NOT IN( \'Division Head\',\'Department Head\') )
									  )


							');
			if ($no_pi != NULL) {
				$result = $query->row();
			}
			else {
				$result = $query->result();
			}

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA MENU PI
		 */
		function get_data_menu($conn = NULL, $id = NULL, $parent = NULL, $byrole = NULL, $controller = NULL, $all = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_menu.*,
      					   CASE 
						   		WHEN menu_join.menu IS NULL THEN \'ROOT\'
						   		ELSE menu_join.menu
						   END as parent,
						   CASE
						   		WHEN tbl_pi_menu.na = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active
      					   	');
			$this->db->from('tbl_pi_menu');
			$this->db->join('tbl_pi_menu as menu_join', 'tbl_pi_menu.id_parent = menu_join.id_menu', 'left');
			if ($id !== NULL) {
				$this->db->where('tbl_pi_menu.id_menu', $id);
			}
			if ($parent !== NULL) {
				$this->db->where('tbl_pi_menu.id_parent', $parent);
			}
			if ($byrole !== NULL) {
				$this->db->where_in('tbl_pi_menu.id_menu', $byrole);
			}
			if ($controller !== NULL) {
				$this->db->where('tbl_pi_menu.link', $controller);
			}
			if ($all == NULL) {
				$this->db->where('tbl_pi_menu.na', 'n');
				$this->db->where('tbl_pi_menu.del', 'n');
			}
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA REKOM HEADER
		 */
		function get_data_rekom_header($conn = NULL, $no_pi = NULL, $plant = NULL, $no_rekom = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_pi_rekom_vendor_header.*');
			$this->db->select('tbl_pi_rekom_vendor_header.login_buat as vendor_header_login_buat');
			$this->db->select('tbl_pi_rekom_vendor_header.tanggal_buat as vendor_header_tanggal_buat');
			$this->db->select('tbl_pi_rekom_vendor_header.login_edit as vendor_header_login_edit');
			$this->db->select('tbl_pi_rekom_vendor_header.tanggal_edit as vendor_header_tanggal_edit');
			$this->db->select('tbl_pi_file.*');
			$this->db->select('attach.id_file as attach_id_file, 
						   attach.filename as attach_filename,
						   attach.location as attach_location');
			if ($no_pi != NULL && $plant != NULL && $no_rekom != NULL) {
				$this->db->select('(
								SELECT MAX(tbl_pi_rekom_vendor_detail.no_detail_pi)
								  FROM tbl_pi_rekom_vendor_detail
								 WHERE tbl_pi_rekom_vendor_detail.plant =  tbl_pi_rekom_vendor_header.plant
								   AND tbl_pi_rekom_vendor_detail.no_pi =  tbl_pi_rekom_vendor_header.no_pi
								   AND tbl_pi_rekom_vendor_detail.no_rekom =  tbl_pi_rekom_vendor_header.no_rekom
								) as max_row');
			}
			$this->db->select('(SELECT tbl_pi_header.status 
					          FROM tbl_pi_header
					         WHERE dbo.tbl_pi_header.no_pi = dbo.tbl_pi_rekom_vendor_header.no_pi
					           AND tbl_pi_header.na = \'n\'
					           AND tbl_pi_header.del = \'n\') as status_pi');
			$this->db->select('(SELECT tbl_pi_dev_capex.status 
					          FROM tbl_pi_dev_capex 
					         WHERE tbl_pi_dev_capex.no_pi = tbl_pi_rekom_vendor_header.no_pi
					           AND tbl_pi_dev_capex.na = \'n\'
					           AND tbl_pi_dev_capex.del = \'n\') as status_capex');
			$this->db->select('(SELECT SUM(tbl_pi_detail.total) 
					          FROM tbl_pi_detail 
					         WHERE tbl_pi_detail.plant = tbl_pi_rekom_vendor_header.plant 
					           AND tbl_pi_detail.no_pi = tbl_pi_rekom_vendor_header.no_pi 
					           AND tbl_pi_detail.na = \'n\' 
					           AND tbl_pi_detail.del = \'n\' ) as sum_pi_detail,
					       (SELECT SUM(tbl_pi_dev_capex.nilai_terbaru) 
					          FROM tbl_pi_dev_capex 
					         WHERE tbl_pi_dev_capex.plant = tbl_pi_rekom_vendor_header.plant 
					           AND tbl_pi_dev_capex.no_pi = tbl_pi_rekom_vendor_header.no_pi 
					           AND tbl_pi_dev_capex.na = \'n\' 
					           AND tbl_pi_dev_capex.del = \'n\' ) as sum_capex,
					       (SELECT tbl_pi_role.app_lim_val 
					          FROM tbl_pi_role
					         WHERE tbl_pi_role.nama_role = \'CEO Region\') as ceo_reg_app_limit');
			$this->db->from('tbl_pi_rekom_vendor_header');
			$this->db->join('tbl_pi_file', 'tbl_pi_file.id_file = tbl_pi_rekom_vendor_header.id_file', 'left');
			$this->db->join('tbl_pi_file as attach', 'attach.id_file = tbl_pi_rekom_vendor_header.id_file_attach', 'left');
			if ($no_pi != NULL) {
				$this->db->where('tbl_pi_rekom_vendor_header.no_pi', $no_pi);
			}
			if ($plant != NULL) {
				$this->db->where('tbl_pi_rekom_vendor_header.plant', $plant);
			}
			if ($no_rekom != NULL) {
				$this->db->where('tbl_pi_rekom_vendor_header.no_rekom', $no_rekom);
			}
			$this->db->where('tbl_pi_rekom_vendor_header.na', 'n');
			$this->db->where('tbl_pi_rekom_vendor_header.del', 'n');
			$this->db->order_by('tbl_pi_rekom_vendor_header.plant', 'ASC');
			$this->db->order_by('tbl_pi_rekom_vendor_header.no_pi', 'ASC');
			$this->db->order_by('tbl_pi_rekom_vendor_header.no_rekom', 'ASC');
			$query = $this->db->get();
			if ($no_pi != NULL && $plant != NULL && $no_rekom != NULL) {
				$result = $query->row();
			}
			else {
				$result = $query->result();
			}

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA REKOM CONTENT
		 */
		function get_data_rekom_content($conn = NULL, $no_pi = NULL, $plant = NULL, $no_rekom = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('*');
			$this->db->select('CAST(tbl_pi_rekom_vendor_content.discount AS VARCHAR(MAX)) as disc_text');
			$this->db->from('tbl_pi_rekom_vendor_content');
			if ($no_pi != NULL) {
				$this->db->where('no_pi', $no_pi);
			}
			if ($plant != NULL) {
				$this->db->where('plant', $plant);
			}
			if ($no_rekom != NULL) {
				$this->db->where('no_rekom', $no_rekom);
			}
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');
			$this->db->order_by('plant', 'ASC');
			$this->db->order_by('no_pi', 'ASC');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA REKOM DETAIL
		 */
		function get_data_rekom_detail($conn = NULL, $no_pi = NULL, $plant = NULL, $no_rekom = NULL, $vendor = NULL, $no = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('*');
			$this->db->from('tbl_pi_rekom_vendor_detail');
			if ($no_pi != NULL) {
				$this->db->where('no_pi', $no_pi);
			}
			if ($plant != NULL) {
				$this->db->where('plant', $plant);
			}
			if ($no_rekom != NULL) {
				$this->db->where('no_rekom', $no_rekom);
			}
			if ($vendor != NULL) {
				$this->db->where('urut_vendor', $vendor);
			}
			if ($no != NULL) {
				$this->db->where('no_detail_pi', $no);
			}
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');
			$this->db->order_by('plant', 'ASC');
			$this->db->order_by('no_pi', 'ASC');
			$query = $this->db->get();
			if ($no_pi != NULL && $plant != NULL && $no_rekom != NULL && $vendor != NULL && $no != NULL) {
				$result = $query->row();
			}
			else {
				$result = $query->result();
			}

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		/*
		 * GET DATA MASTER
		 */
		function get_data_master($conn = NULL, $table = NULL, $select = NULL, $param = NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('*');
			if($select !== NULL) {
				foreach($select as $s) {
					$this->db->select($s['select']);
				}
			}
			$this->db->from($table);

			if($param !== NULL){
				foreach($param as $p){
					switch($p['method']) {
						case 'where' :
							$this->db->where($p['kolom'], $p['value']);
							break;
						case 'where_in' :
							$this->db->where_in($p['kolom'], $p['value']);
							break;
						case 'like' :
							$this->db->like($p['kolom'], $p['value'], $p['option']);
							break;
						case 'order' :
							$this->db->order_by($p['kolom'], $p['value']);
							break;
					}
				}
			}

			$query = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();

			return $result;
		}

		// =============session data baru untuk KHO (double role)=========================
		function get_arr_kode_role($nik=NULL){
			$this->db->select('CAST(
						   (SELECT DISTINCT CONVERT(VARCHAR,tbl_pi_rolenik.kode_role) +RTRIM(\',\')
						    FROM tbl_pi_rolenik as tbl2
						    INNER JOIN tbl_pi_rolenik ON tbl2.kode_role = dbo.tbl_pi_rolenik.kode_role 
							WHERE tbl_pi_rolenik.nik = '.$nik.'
							AND tbl_pi_rolenik.na = \'n\'
						    AND tbl_pi_rolenik.del = \'n\'
							FOR XML PATH (\'\')) as VARCHAR(MAX)) AS kode_role');
			$this->db->from('tbl_pi_rolenik');

			$this->db->where('tbl_pi_rolenik.nik', $nik);
			$this->db->where('tbl_pi_rolenik.na', 'n');
			$this->db->where('tbl_pi_rolenik.del', 'n');
			$query = $this->db->get();
			return $query->row();
		}

		function get_datas_role($kode_roles=NULL){
			$this->db->select('tbl_pi_role.*');
			$this->db->from('tbl_pi_role');

			$this->db->where_in('tbl_pi_role.kode_role', $kode_roles);
			$query = $this->db->get();
			return $query->result();
		}

		function get_datas_menu($kode_roles=NULL){
			$this->db->select('tbl_pi_role_menu.*');
			$this->db->from('tbl_pi_role_menu');

			$this->db->where_in('tbl_pi_role_menu.kode_role', $kode_roles);
			$query = $this->db->get();
			return $query->result();
		}

		function get_datas_plant($nik=NULL){
			$this->db->select('DISTINCT(tbl_pi_rolenik_pabrik.kode_pabrik)');
			$this->db->select('tbl_inv_pabrik.nama');
			$this->db->from('tbl_pi_rolenik_pabrik');
			$this->db->join('tbl_inv_pabrik', 'tbl_pi_rolenik_pabrik.kode_pabrik = tbl_inv_pabrik.kode', 'inner');
			$this->db->join('tbl_pi_rolenik', 'tbl_pi_rolenik_pabrik.id_rolenik = tbl_pi_rolenik.id_rolenik', 'inner');

			$this->db->where('tbl_pi_rolenik_pabrik.na', 'n');
			$this->db->where('tbl_pi_rolenik_pabrik.del', 'n');
			$this->db->where('tbl_pi_rolenik.nik', $nik);
			$query = $this->db->get();
			return $query->result();
		}

		// *KHO if_HO & tambah param level untuk get kode role by level(status pi)
		function get_data_role($kode=NULL, $nama=NULL, $all=NULL, $level=NULL){
			$this->db->select('tbl_pi_role.*');
			$this->db->select('CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_role.app_lim_val), 1) as app_lim_val_money');
			$this->db->select('CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_role.app_lim_val_ho), 1) as app_lim_val_ho_money');
			$this->db->select('CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_role.app_lim_val_capex), 1) as app_lim_val_capex_money');
			$this->db->select('CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_role.app_lim_val_capex_ho), 1) as app_lim_val_capex_ho_money');
			$this->db->select('CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_role.app_lim_val_disposisi), 1) as app_lim_val_disposisi_money');
			$this->db->select('app_join.nama_role as approve');
			$this->db->select('assign_join.nama_role as assign');
			$this->db->select('dec_join.nama_role as decline');
			$this->db->select('CASE tbl_pi_role.if_drop
					          WHEN \'drop\' THEN \'PERMANEN DROP\'
					          ELSE drop_join.nama_role
					       END as drops,
						   CASE
						   		WHEN tbl_pi_role.na = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
			$this->db->select('app_join_capex.nama_role as approve_capex');
			$this->db->select('assign_join_capex.nama_role as assign_capex');
			$this->db->select('dec_join_capex.nama_role as decline_capex');
			$this->db->select('CASE tbl_pi_role.if_drop
					          WHEN \'drop\' THEN \'PERMANEN DROP\'
					          ELSE drop_join_capex.nama_role
					       END as drops_capex');
			$this->db->select('app_join_ho.nama_role as approve_ho');
			$this->db->select('assign_join_ho.nama_role as assign_ho');
			$this->db->select('dec_join_ho.nama_role as decline_ho');
			$this->db->select('CASE tbl_pi_role.if_drop_ho
					          WHEN \'drop\' THEN \'PERMANEN DROP\'
					          ELSE drop_join_ho.nama_role
					       END as drops_ho');
			$this->db->select('app_join_capex_ho.nama_role as approve_capex_ho');
			$this->db->select('assign_join_capex_ho.nama_role as assign_capex_ho');
			$this->db->select('dec_join_capex_ho.nama_role as decline_capex_ho');
			$this->db->select('CASE tbl_pi_role.if_drop_ho
					          WHEN \'drop\' THEN \'PERMANEN DROP\'
					          ELSE drop_join_capex_ho.nama_role
					       END as drops_capex_ho');
			$this->db->from('tbl_pi_role');
			$this->db->join('tbl_pi_role as app_join', 'tbl_pi_role.if_approve = app_join.level', 'left');
			$this->db->join('tbl_pi_role as assign_join', 'tbl_pi_role.if_assign = assign_join.level', 'left');
			$this->db->join('tbl_pi_role as dec_join', 'tbl_pi_role.if_decline = dec_join.level', 'left');
			$this->db->join('tbl_pi_role as drop_join', 'tbl_pi_role.if_drop = CAST(drop_join.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_pi_role as app_join_capex', 'tbl_pi_role.if_approve_capex = app_join_capex.level', 'left');
			$this->db->join('tbl_pi_role as assign_join_capex', 'tbl_pi_role.if_assign_capex = assign_join_capex.level', 'left');
			$this->db->join('tbl_pi_role as dec_join_capex', 'tbl_pi_role.if_decline_capex = dec_join_capex.level', 'left');
			$this->db->join('tbl_pi_role as drop_join_capex', 'tbl_pi_role.if_drop_capex = CAST(drop_join_capex.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_pi_role as app_join_ho', 'tbl_pi_role.if_approve_ho = app_join_ho.level', 'left');
			$this->db->join('tbl_pi_role as assign_join_ho', 'tbl_pi_role.if_assign_ho = assign_join_ho.level', 'left');
			$this->db->join('tbl_pi_role as dec_join_ho', 'tbl_pi_role.if_decline_ho = dec_join_ho.level', 'left');
			$this->db->join('tbl_pi_role as drop_join_ho', 'tbl_pi_role.if_drop_ho = CAST(drop_join_ho.level AS VARCHAR(15))', 'left');
			$this->db->join('tbl_pi_role as app_join_capex_ho', 'tbl_pi_role.if_approve_capex_ho = app_join_capex_ho.level', 'left');
			$this->db->join('tbl_pi_role as assign_join_capex_ho', 'tbl_pi_role.if_assign_capex_ho = assign_join_capex_ho.level', 'left');
			$this->db->join('tbl_pi_role as dec_join_capex_ho', 'tbl_pi_role.if_decline_capex_ho = dec_join_capex_ho.level', 'left');
			$this->db->join('tbl_pi_role as drop_join_capex_ho', 'tbl_pi_role.if_drop_capex_ho = CAST(drop_join_capex_ho.level AS VARCHAR(15))', 'left');
			if($kode != NULL){
				$this->db->where('tbl_pi_role.kode_role', $kode);
			}
			if($level != NULL){
				$this->db->where('tbl_pi_role.level', $level);
			}
			if($nama != NULL){
				$this->db->like('tbl_pi_role.nama_role', $nama, 'after');
			}
			if($all == NULL){
				$this->db->where('tbl_pi_role.na', 'n');
				$this->db->where('tbl_pi_role.del', 'n');
			}
			$this->db->order_by('tbl_pi_role.level', 'ASC');
			$query = $this->db->get();
			if($kode != NULL || $nama != NULL) return $query->row();
			else return $query->result();
		}

		// ====================================================================================
	}
