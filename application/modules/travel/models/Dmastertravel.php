<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Travel 
@author       : Airiza Yuddha (7849)
@contributor  :
		1. <insert your fullname> (<insert your nik>) <insert the date>
			<insert what you have modified>
		2. <insert your fullname> (<insert your nik>) <insert the date>
			<insert what you have modified>
		etc.
*/

class Dmastertravel extends CI_Model
{

	/*================================approval====================================*/
	function get_data_user_program($user = NULL, $persa = NULL, $pabrik_in = NULL)
	{
		$this->general->connectDbPortal();
		$this->db->select('tbl_karyawan.nik as id');
		$this->db->select('tbl_user.*');
		$this->db->select('tbl_karyawan.*');
		$this->db->from('tbl_user');

		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'inner');
		$this->db->join('tbl_posisi', 'tbl_posisi.nama = tbl_karyawan.posst AND tbl_posisi.na = \'n\'', 'inner');
		if ($user !== NULL) {
			$this->db->like('tbl_karyawan.nama', $user, 'both');
			$this->db->or_like('tbl_karyawan.nik', $user, 'both');
		}

		if ($persa !== NULL) {
			if ($persa == "0001") {
				$this->db->where("tbl_karyawan.id_gedung", "ho");
			} else {
				$this->db->where("tbl_karyawan.persa", $persa);
				$this->db->where("tbl_karyawan.id_gedung <> 'ho' ");
			}
		}

		$this->db->where('tbl_karyawan.na', 'n');
		$this->db->where('tbl_karyawan.del', 'n');
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query 	= $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}

	function get_data_travel_approval($conn = NULL, $id = NULL, $all = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select(" id_travel_approval");
		$this->datatables->select(" jns_user");
		$this->datatables->select(" value_user");
		$this->datatables->select(" vw_travel_master_approval.level");
		$this->datatables->select(" jns_approval");
		$this->datatables->select(" value_approval");
		$this->datatables->select(" email_approval");

		$this->datatables->select(" user_name");
		$this->datatables->select(" approval_name");
		$this->datatables->select(" approval_email");
		$this->datatables->select(" approval_email_name");

		$this->datatables->select(" tgl_buat");
		$this->datatables->select(" tgl_edit");
		$this->datatables->select(" vw_travel_master_approval.login_buat");
		$this->datatables->select(" vw_travel_master_approval.tanggal_buat");
		$this->datatables->select(" vw_travel_master_approval.login_edit");
		$this->datatables->select(" vw_travel_master_approval.tanggal_edit");
		$this->datatables->select(" vw_travel_master_approval.na");
		$this->datatables->select(" vw_travel_master_approval.del");

		$this->datatables->select(" tr.role as role_nama");

		$this->datatables->from("vw_travel_master_approval");
		$this->datatables->join('tbl_travel_role tr', 'tr.level = vw_travel_master_approval.level', 'left');

		$where = '';
		if ($id != NULL) {
			$this->datatables->where('id_travel_approval', $id);
		}
		if ($all == NULL) {
			$this->datatables->where('vw_travel_master_approval.del', 'n');
		}
		if ($all != NULL) {
			$this->datatables->where('vw_travel_master_approval.na', 'n');
			$this->datatables->where('vw_travel_master_approval.del', 'n');
		}

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_travel_approval"));
		return $this->general->jsonify($raw);
	}

	function get_data_travel_approval_normal($conn = NULL, $id = NULL, $all = NULL, $typecheck = NULL, $datacheck_1 = NULL, $datacheck_2 = NULL, $datacheck_3 = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select(" id_travel_approval");
		$this->db->select(" jns_user");
		$this->db->select(" value_user");
		$this->db->select(" level");
		$this->db->select(" jns_approval");
		$this->db->select(" value_approval");
		$this->db->select(" email_approval");

		$this->db->select(" user_name");
		$this->db->select(" approval_name");
		$this->db->select(" approval_email");
		$this->db->select(" approval_email_name");

		$this->db->select(" tgl_buat");
		$this->db->select(" tgl_edit");
		$this->db->select(" login_buat");
		$this->db->select(" tanggal_buat");
		$this->db->select(" login_edit");
		$this->db->select(" tanggal_edit");
		$this->db->select(" na");
		$this->db->select(" del");

		$this->db->from("vw_travel_master_approval");

		if ($typecheck !== NULL) {

			if ($typecheck == "in") {
				$this->db->where(" jns_user ", trim($datacheck_1));
				$this->db->where(" value_user ", trim($datacheck_2));
				$this->db->where(" level ", trim($datacheck_3));
				$this->db->where(" del ", 'n');
			} else if ($typecheck == "up") {
				$this->db->where(" jns_user ", trim($datacheck_1));
				$this->db->where(" value_user ", trim($datacheck_2));
				$this->db->where(" level ", trim($datacheck_3));
				$this->db->where(" del ", 'n');
				$this->db->where_not_in(" id_travel_approval ", $id);
			}
		} else {
			if ($id != NULL) {
				$this->db->where('id_travel_approval', $id);
			}
			if ($all == NULL) {
				$this->db->where('del', 'n');
			}
			if ($all != NULL) {
				$this->db->where('na', 'n');
				$this->db->where('del', 'n');
			}
		}

		$query 	= $this->db->get();
		$result = $query->result();
		return $result;
	}

	function get_data_travel_approval_detail_normal($conn = NULL, $id = NULL, $all = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select(" id_travel_approval");
		$this->db->select(" jns_user");
		$this->db->select(" value_user");
		$this->db->select(" level");
		$this->db->select(" jns_approval");
		$this->db->select(" value_approval");
		$this->db->select(" email_approval");

		$this->db->select(" user_name");
		$this->db->select(" approval_name");
		$this->db->select(" approval_email");
		$this->db->select(" approval_email_name");

		$this->db->select(" tgl_buat");
		$this->db->select(" tgl_edit");
		$this->db->select(" login_buat");
		$this->db->select(" tanggal_buat");
		$this->db->select(" login_edit");
		$this->db->select(" tanggal_edit");
		$this->db->select(" na");
		$this->db->select(" del");

		$this->db->from("vw_travel_master_approval");

		$where = '';
		if ($id != NULL) {
			$this->db->where('id_travel_approval', $id);
		}
		if ($all == NULL) {
			$this->db->where('del', 'n');
		}
		if ($all != NULL) {
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');
		}

		$query 	= $this->db->get();
		$result = $query->row();

		return $result;
	}

	/*================================role====================================*/
	function get_data_travel_role($conn = NULL, $id = NULL, $all = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select(" id_travel_role");
		$this->datatables->select(" role");
		$this->datatables->select(" level");

		$this->datatables->select(" if_approve_spd");
		$this->datatables->select(" if_approve_spd_um");
		$this->datatables->select(" if_approve_declare");
		$this->datatables->select(" if_approve_cancel");
		$this->datatables->select(" if_decline_spd");
		$this->datatables->select(" if_decline_spd_um");
		$this->datatables->select(" if_decline_declare");
		$this->datatables->select(" if_decline_cancel");

		$this->datatables->select(" approve_spd");
		$this->datatables->select(" approve_declare");
		$this->datatables->select(" approve_spd_um");
		$this->datatables->select(" approve_cancel");
		$this->datatables->select(" decline_spd");
		$this->datatables->select(" decline_spd_um");
		$this->datatables->select(" decline_declare");
		$this->datatables->select(" decline_cancel");
		$this->datatables->select(" tgl_edit");
		$this->datatables->select(" login_buat");
		$this->datatables->select(" tanggal_buat");
		$this->datatables->select(" login_edit");
		$this->datatables->select(" tanggal_edit");
		$this->datatables->select(" na");
		$this->datatables->select(" del");

		$this->datatables->from("vw_travel_role");

		$where = '';
		if ($id != NULL) {
			$this->datatables->where('id_travel_role', $id);
		}
		if ($all == NULL) {
			$this->datatables->where('del', 'n');
		}
		if ($all != NULL) {
			$this->datatables->where('na', 'n');
			$this->datatables->where('del', 'n');
		}

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_travel_role"));
		return $this->general->jsonify($raw);
	}

	function get_data_travel_role_normal($conn = NULL, $id = NULL, $all = NULL, $typecheck = NULL, $datacheck_1 = NULL, $datacheck_2 = NULL, $id_temuan = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select(" id_travel_role");
		$this->db->select(" role");
		$this->db->select(" level");

		$this->db->select(" if_approve_spd");
		$this->db->select(" if_approve_declare");
		$this->db->select(" if_approve_spd_um");
		$this->db->select(" if_approve_cancel");
		$this->db->select(" if_decline_spd");
		$this->db->select(" if_decline_spd_um");
		$this->db->select(" if_decline_declare");
		$this->db->select(" if_decline_cancel");

		$this->db->select(" approve_spd");
		$this->db->select(" approve_spd_um");
		$this->db->select(" approve_declare");
		$this->db->select(" approve_cancel");
		$this->db->select(" decline_spd");
		$this->db->select(" decline_spd_um");
		$this->db->select(" decline_declare");
		$this->db->select(" decline_cancel");
		$this->db->select(" tgl_edit");
		$this->db->select(" login_buat");
		$this->db->select(" tanggal_buat");
		$this->db->select(" login_edit");
		$this->db->select(" tanggal_edit");
		$this->db->select(" na");
		$this->db->select(" del");

		$this->db->from(" vw_travel_role");

		if ($typecheck !== NULL) {

			if ($typecheck == "in") {
				$this->db->where(" LTRIM(RTRIM(role)) ", trim($datacheck_1));
				$this->db->where(" del ", 'n');
			} else if ($typecheck == "up") {
				$this->db->where(" LTRIM(RTRIM(role)) ", trim($datacheck_1));
				$this->db->where(" del ", 'n');
				$this->db->where_not_in(" id_travel_role ", $id);
			}
		} else {

			if ($id != NULL) {
				$this->db->where('id_travel_role', $id);
			}
			if ($all == NULL) {
				$this->db->where('del', 'n');
			}
			if ($all != NULL) {
				$this->db->where('na', 'n');
				$this->db->where('del', 'n');
			}
		}
		$this->db->order_by('role ASC');

		$query = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function check_data_travel_role_level($conn = NULL, $level = NULL, $type = NULL, $id_role = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select(" tbl_travel_role.level ");
		$this->db->from("tbl_travel_role");

		if ($level != NULL)
			$this->db->where('tbl_travel_role.level', $level);

		if ($type != NULL) {
			if ($type == 'up') {
				$this->db->where_not_in(" tbl_travel_role.id_travel_role ", $id_role);
			}
		}
		$this->db->where(" tbl_travel_role.del ", 'n');

		$query = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_travel_jabatan_normal($conn = NULL, $jabatan = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select(" * ");
		$this->db->from("tbl_jabatan");

		if ($jabatan != NULL)
			$this->db->where('tbl_jabatan.level', $level);

		$this->db->where(" tbl_jabatan.na ", 'n');
		$this->db->where(" tbl_jabatan.del ", 'n');

		$query = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	/*================================pic_book====================================*/
	function get_data_travel_pic_book($conn = NULL, $id = NULL, $all = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select(" nik");
		$this->datatables->select(" company_code");
		$this->datatables->select(" personal_area");
		$this->datatables->select(" personal_subarea");
		$this->datatables->select(" jns_user");
		$this->datatables->select(" value_user");
		$this->datatables->select(" nama_karyawan");
		$this->datatables->select(" level_name");
		$this->datatables->select(" subarea");

		$this->datatables->from("vw_travel_pic_book");

		$where = '';

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		return $this->general->jsonify($raw);
	}

	function get_data_travel_pic_book_detail_normal($conn = NULL, $id = NULL, $all = NULL, $dtedit = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select(" nik");
		$this->db->select(" company_code");
		$this->db->select(" personal_area");
		$this->db->select(" personal_subarea");
		$this->db->select(" jns_user");
		$this->db->select(" value_user");
		$this->db->select(" nama_karyawan");
		$this->db->select(" level_name");
		$this->db->select(" subarea");

		$this->db->from("vw_travel_pic_book");

		$where = '';
		if ($id != NULL) {
			$this->db->where('id_travel_pic_book', $id);
		}
		if ($dtedit != NULL) {
			$data_exp 		= explode('|', $dtedit);
			$nik 			= $data_exp[0];
			$company_code 	= $data_exp[1];
			$personal_area 	= $data_exp[2];
			$personal_sarea = $data_exp[3];
			$jns_user 		= $data_exp[4];
			$this->db->where('nik', $nik);
			$this->db->where('company_code', $company_code);
			$this->db->where('personal_area', $personal_area);
			$this->db->where('personal_subarea', $personal_sarea);
			$this->db->where('jns_user', $jns_user);
		}

		$query 	= $this->db->get();
		$result = $query->row();
		return $result;
	}

	function get_data_travel_pic_book_normal($conn = NULL, $id = NULL, $all = NULL, $typecheck = NULL, $datacheck_1 = NULL, $datacheck_2 = NULL, $datacheck_3 = NULL, $datacheck_4 = NULL, $datacheck_5 = NULL, $exceptdel = NULL, $all = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select(" id_travel_pic_book");
		$this->db->select(" nik");
		$this->db->select(" company_code");
		$this->db->select(" personal_area");
		$this->db->select(" personal_subarea");
		$this->db->select(" jns_user");
		$this->db->select(" value_user");
		$this->db->select(" nama_karyawan");
		$this->db->select(" level_name");
		$this->db->select(" subarea");

		$this->db->select(" tgl_buat");
		$this->db->select(" tgl_edit");
		$this->db->select(" login_buat");
		$this->db->select(" tanggal_buat");
		$this->db->select(" login_edit");
		$this->db->select(" tanggal_edit");
		$this->db->select(" na");
		$this->db->select(" del");

		$this->db->from("vw_travel_pic_book_normal");

		if ($typecheck !== NULL) {

			if ($typecheck == "in") {
				$this->db->where(" nik ", trim($datacheck_1));
				if ($all != $datacheck_2) {
					$this->db->where(" personal_area ", trim($datacheck_2));
				}
				if ($all != $datacheck_3) {
					$this->db->where(" personal_subarea ", trim($datacheck_3));
				}
				$this->db->where(" jns_user ", trim($datacheck_4));
				$this->db->where_in(" value_user ", $datacheck_5);
				if ($exceptdel == NULL) {
					$this->db->where(" del ", 'n');
				}
			} else if ($typecheck == "up") {
				$this->db->where(" nik ", trim($datacheck_1));
				$this->db->where(" personal_area ", trim($datacheck_2));
				$this->db->where(" personal_subarea ", trim($datacheck_3));
				$this->db->where(" jns_user ", trim($datacheck_4));
				$this->db->where_in(" value_user ", $datacheck_5);
				$this->db->where(" del ", 'n');
				$this->db->where_not_in(" id_travel_pic_book ", $id);
			}
		} else {
			if ($id != NULL) {
				$this->db->where('id_travel_pic_book', $id);
			}
			if ($all == NULL) {
				$this->db->where('del', 'n');
			}
			if ($all != NULL) {
				$this->db->where('na', 'n');
				$this->db->where('del', 'n');
			}
		}

		$query 	= $this->db->get();
		$result = $query->result();
		return $result;
	}

	/*================================pic_sync====================================*/
	function get_data_travel_pic_sync($conn = NULL, $id = NULL, $all = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select(" nik");
		$this->datatables->select(" company_code");
		$this->datatables->select(" personal_area");
		$this->datatables->select(" personal_subarea");
		$this->datatables->select(" jns_user");
		$this->datatables->select(" value_user");
		$this->datatables->select(" nama_karyawan");
		$this->datatables->select(" level_name");
		$this->datatables->select(" subarea");

		$this->datatables->from("vw_travel_pic_sync");

		$where = '';

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		return $this->general->jsonify($raw);
	}

	function get_data_travel_pic_sync_detail_normal($conn = NULL, $id = NULL, $all = NULL, $dtedit = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select(" nik");
		$this->db->select(" company_code");
		$this->db->select(" personal_area");
		$this->db->select(" personal_subarea");
		$this->db->select(" jns_user");
		$this->db->select(" value_user");
		$this->db->select(" nama_karyawan");
		$this->db->select(" level_name");
		$this->db->select(" subarea");

		$this->db->from("vw_travel_pic_sync");

		$where = '';
		if ($id != NULL) {
			$this->db->where('id_travel_pic_sync', $id);
		}
		if ($dtedit != NULL) {
			$data_exp 		= explode('|', $dtedit);
			$nik 			= $data_exp[0];
			$company_code 	= $data_exp[1];
			$personal_area 	= $data_exp[2];
			$personal_sarea = $data_exp[3];
			$jns_user 		= $data_exp[4];
			$this->db->where('nik', $nik);
			$this->db->where('company_code', $company_code);
			$this->db->where('personal_area', $personal_area);
			$this->db->where('personal_subarea', $personal_sarea);
			$this->db->where('jns_user', $jns_user);
		}

		$query 	= $this->db->get();
		$result = $query->row();
		return $result;
	}

	function get_data_travel_pic_sync_normal($conn = NULL, $id = NULL, $all = NULL, $typecheck = NULL, $datacheck_1 = NULL, $datacheck_2 = NULL, $datacheck_3 = NULL, $datacheck_4 = NULL, $datacheck_5 = NULL, $exceptdel = NULL, $all = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select(" id_travel_pic_sync");
		$this->db->select(" nik");
		$this->db->select(" company_code");
		$this->db->select(" personal_area");
		$this->db->select(" personal_subarea");
		$this->db->select(" jns_user");
		$this->db->select(" value_user");
		$this->db->select(" nama_karyawan");
		$this->db->select(" level_name");
		$this->db->select(" subarea");

		$this->db->select(" tgl_buat");
		$this->db->select(" tgl_edit");
		$this->db->select(" login_buat");
		$this->db->select(" tanggal_buat");
		$this->db->select(" login_edit");
		$this->db->select(" tanggal_edit");
		$this->db->select(" na");
		$this->db->select(" del");

		$this->db->from("vw_travel_pic_sync_normal");

		if ($typecheck !== NULL) {

			if ($typecheck == "in") {
				$this->db->where(" nik ", trim($datacheck_1));
				if ($all != $datacheck_2) {
					$this->db->where(" personal_area ", trim($datacheck_2));
				}
				if ($all != $datacheck_3) {
					$this->db->where(" personal_subarea ", trim($datacheck_3));
				}

				$this->db->where(" jns_user ", trim($datacheck_4));
				$this->db->where_in(" value_user ", $datacheck_5);
				if ($exceptdel == NULL) {
					$this->db->where(" del ", 'n');
				}
			} else if ($typecheck == "up") {
				$this->db->where(" nik ", trim($datacheck_1));
				$this->db->where(" personal_area ", trim($datacheck_2));
				$this->db->where(" personal_subarea ", trim($datacheck_3));
				$this->db->where(" jns_user ", trim($datacheck_4));
				$this->db->where_in(" value_user ", $datacheck_5);
				$this->db->where(" del ", 'n');
				$this->db->where_not_in(" id_travel_pic_sync ", $id);
			}
		} else {
			if ($id != NULL) {
				$this->db->where('id_travel_pic_sync', $id);
			}
			if ($all == NULL) {
				$this->db->where('del', 'n');
			}
			if ($all != NULL) {
				$this->db->where('na', 'n');
				$this->db->where('del', 'n');
			}
		}

		$query 	= $this->db->get();
		$result = $query->result();
		return $result;
	}

	/*================================Other====================================*/
	function get_data_personal_area($plant_in = NULL)
	{
		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		$string = 'SELECT DISTINCT ZDMMSPLANT.PERSA as plant_code,
			                    ZDMMSPLANT.WERKS as plant, 
			                    ZDMMSPLANT.NAME1 as plant_name,
			                    tbl_wf_region.region_name
			         FROM [10.0.0.32].SAPSYNC.dbo.ZDMMSPLANT
			         LEFT JOIN tbl_wf_region ON ZDMMSPLANT.PERSA = tbl_wf_region.plant_code COLLATE SQL_Latin1_General_CP1_CS_AS
			                          AND tbl_wf_region.na = \'n\' 
			                    AND tbl_wf_region.del = \'n\'
			         WHERE 1=1';
		if ($plant_in != NULL) {
			if (count($plant_in) <= 1) {
				$plant_in = "'" . $plant_in[0] . "'";
			} else
				$plant_in = "'" . implode("','", $plant_in) . "'";

			$string .= ' AND ZDMMSPLANT.WERKS IN (' . $plant_in . ')';
		}
		$string .= ' ORDER BY plant ASC';

		$query = $this->db->query($string);
		return $query->result();
	}

	function get_data_subarea($conn = NULL, $id = NULL, $all = NULL, $personal_area = NULL, $plant_in = NULL)
	{
		$this->general->connectDbPortal();
		$this->db->select('*');
		$this->db->from('tbl_travel_subarea');

		if ($id != NULL) {
			$this->db->where('company_code', $id);
		}
		if ($personal_area != NULL) {
			$this->db->where('personal_area', $personal_area);
		}

		$this->db->where("personal_subarea != '' ");
		$this->db->where("personal_area_text != ''");
		$this->db->where("RIGHT(company_code,2) <> 10");
		$query = $this->db->get();
		return $query->result();
	}
}
