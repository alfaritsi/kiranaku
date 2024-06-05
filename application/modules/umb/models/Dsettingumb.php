<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application  	: UMB (Uang Muka Bokar)
    @author 		: Akhmad Syaiful Yamang (8347)
    @contributor	:
                1. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                2. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                etc.
    */

	class Dsettingumb extends CI_Model {
		function get_setting_user($conn = NULL, $id_rolenik = NULL, $nik = NULL, $kode_role = NULL, $active = NULL, $deleted = 'n') {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			if (is_null($deleted))
				$deleted = 'n';

			$this->db->select('tbl_umb_rolenik.id_rolenik as rolenik');
			$this->db->select('tbl_umb_rolenik.*');
			$this->db->select('CASE
									WHEN tbl_umb_rolenik.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
									ELSE \'<span class="label label-danger">NON AKTIF</span>\'
							   END as label_active');
			$this->db->select('tbl_karyawan.nama');
			$this->db->select('tbl_umb_role.nama_role');
			$this->db->select('tbl_umb_role.akses_plafon');
			$this->db->select('tbl_umb_role.is_rekom');
			$this->db->select('tbl_umb_role.is_renewal');
			$this->db->select('tbl_umb_role.level');
			$this->db->select('tbl_umb_role.if_approve');
			$this->db->select('tbl_umb_role.if_assign');
			$this->db->select('tbl_umb_role.if_decline');
			$this->db->select('tbl_umb_role.if_drop');
			$this->db->select('tbl_umb_role.disposisi_nik');
			$this->db->select('CAST(
						           (SELECT DISTINCT tbl_wf_master_plant.plant_name + RTRIM(\',\')
									  FROM tbl_umb_rolenik_pabrik as tbl2
								     INNER JOIN tbl_wf_master_plant ON tbl2.kode_pabrik = tbl_wf_master_plant.plant
									 WHERE tbl_umb_rolenik.id_rolenik = tbl2.id_rolenik
									   FOR XML PATH (\'\')) as VARCHAR(MAX))  AS pabrik_list');
			$this->db->select('CAST(
						           (SELECT DISTINCT tbl_wf_master_plant.plant + RTRIM(\',\')
									  FROM tbl_umb_rolenik_pabrik as tbl2
								     INNER JOIN tbl_wf_master_plant ON tbl2.kode_pabrik = tbl_wf_master_plant.plant
									 WHERE tbl_umb_rolenik.id_rolenik = tbl2.id_rolenik
									   FOR XML PATH (\'\')) as VARCHAR(MAX))  AS kode_pabrik_list');
			$this->db->from('tbl_umb_rolenik');
			$this->db->join('tbl_karyawan', "tbl_karyawan.nik = tbl_umb_rolenik.nik AND tbl_karyawan.na='n'AND tbl_karyawan.del='n'", 'inner');
			$this->db->join('tbl_umb_role', "tbl_umb_role.kode_role = tbl_umb_rolenik.kode_role AND tbl_umb_role.na='n'AND tbl_umb_role.del='n'", 'inner');
			if ($id_rolenik !== NULL) {
				$this->db->where('tbl_umb_rolenik.id_rolenik', $id_rolenik);
			}
			if ($nik !== NULL) {
				$this->db->where('tbl_umb_rolenik.nik', $nik);
			}
			if ($kode_role !== NULL) {
				$this->db->where('tbl_umb_rolenik.kode_role', $kode_role);
			}
			if ($active !== NULL) {
				$this->db->where('tbl_umb_rolenik.na', $active);
			}

			$this->db->where('tbl_umb_rolenik.del', $deleted);
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}
	}
