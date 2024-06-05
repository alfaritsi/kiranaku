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

	class Dmasterumb extends CI_Model {
		function get_master_scoring_detail($conn = NULL, $id_mscoring_detail = NULL, $id_mscoring_header = NULL, $urutan = NULL, $na = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_umb_mscoring_detail.*');
			$this->db->from('tbl_umb_mscoring_detail');
			if ($id_mscoring_detail !== NULL) {
				$this->db->where('tbl_umb_mscoring_detail.id_mscoring_detail', $id_mscoring_detail);
			}
			if ($id_mscoring_header !== NULL) {
				$this->db->where('tbl_umb_mscoring_detail.id_mscoring_header', $id_mscoring_header);
			}
			if ($urutan !== NULL) {
				$this->db->where('tbl_umb_mscoring_detail.no_urut', $urutan);
			}
			if ($na !== NULL) {
				$this->db->where('tbl_umb_mscoring_detail.na', $na);
			}
			$this->db->order_by('tbl_umb_mscoring_detail.tanggal_edit DESC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_kriteria_detail($conn = NULL, $id_mkriteria_detail = NULL, $id_mkriteria_header = NULL, $urutan = NULL, $na = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_umb_mkriteria_detail.*');
			$this->db->from('tbl_umb_mkriteria_detail');
			if ($id_mkriteria_detail !== NULL) {
				$this->db->where('tbl_umb_mkriteria_detail.id_mkriteria_detail', $id_mkriteria_detail);
			}
			if ($id_mkriteria_header !== NULL) {
				$this->db->where('tbl_umb_mkriteria_detail.id_mkriteria_header', $id_mkriteria_header);
			}
			if ($urutan !== NULL) {
				$this->db->where('tbl_umb_mkriteria_detail.no_urut', $urutan);
			}
			if ($na !== NULL) {
				$this->db->where('tbl_umb_mkriteria_detail.na', $na);
			}
			$this->db->order_by('tbl_umb_mkriteria_detail.tanggal_edit DESC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_jaminan_detail($conn = NULL, $id_mjaminan_detail = NULL, $id_mjaminan_header = NULL, $urutan = NULL, $na = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_umb_mjaminan_detail.*');
			$this->db->from('tbl_umb_mjaminan_detail');
			if ($id_mjaminan_detail !== NULL) {
				$this->db->where('tbl_umb_mjaminan_detail.id_mjaminan_detail', $id_mjaminan_detail);
			}
			if ($id_mjaminan_header !== NULL) {
				$this->db->where('tbl_umb_mjaminan_detail.id_mjaminan_header', $id_mjaminan_header);
			}
			if ($urutan !== NULL) {
				$this->db->where('tbl_umb_mjaminan_detail.no_urut', $urutan);
			}
			if ($na !== NULL) {
				$this->db->where('tbl_umb_mjaminan_detail.na', $na);
			}
			$this->db->order_by('tbl_umb_mjaminan_detail.tanggal_edit DESC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_plafon($conn = NULL, $plant = NULL, $active = NULL, $started = NULL, $ended = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_umb_mplafon.*');
			$this->db->select('tbl_wf_master_plant.plant_name');
			$this->db->from('tbl_umb_mplafon');
			$this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant = tbl_umb_mplafon.kode_pabrik', 'inner');
			if ($plant !== NULL) {
				$this->db->where('tbl_umb_mplafon.kode_pabrik', $plant);
			}
			if ($active !== NULL) {
				$this->db->where('tbl_umb_mplafon.active', $active);
			}
			if ($started == "NOT NULL") {
				$this->db->where('tbl_umb_mplafon.start_date IS NOT NULL');
			} else if ($started == "NULL") {
				$this->db->where('tbl_umb_mplafon.start_date IS NULL');
			}
			if ($ended == "NOT NULL") {
				$this->db->where('tbl_umb_mplafon.end_date IS NOT NULL');
			} else if ($ended == "NULL") {
				$this->db->where('tbl_umb_mplafon.end_date IS NULL');
			}
			$this->db->order_by('tbl_umb_mplafon.tanggal_edit DESC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_role($conn = NULL, $kode = NULL, $level = NULL, $active = NULL, $deleted = 'n', $name = NULL, $exclude=NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			if (is_null($deleted))
				$deleted = 'n';

			$this->db->select('tbl_umb_role.*');
			$this->db->select('CAST(
								   (SELECT DISTINCT app_join.nama_role + RTRIM(\', \')
									  FROM tbl_umb_role as app_join
									 WHERE tbl_umb_role.if_approve = app_join.[level]
									   AND app_join.na = \'n\'
									   AND app_join.del = \'n\'
									   FOR XML PATH (\'\')) as VARCHAR(MAX))  AS approve_role');
			$this->db->select('CAST(
								   (SELECT DISTINCT assign_join.nama_role + RTRIM(\', \')
									  FROM tbl_umb_role as assign_join
									 WHERE tbl_umb_role.if_assign = assign_join.[level]
									   AND assign_join.na = \'n\'
									   AND assign_join.del = \'n\'
									   FOR XML PATH (\'\')) as VARCHAR(MAX))  AS assign_role');
			$this->db->select('CAST(
								   (SELECT DISTINCT decline_join.nama_role + RTRIM(\', \')
									  FROM tbl_umb_role as decline_join
									 WHERE tbl_umb_role.if_decline = decline_join.[level]
									   AND decline_join.na = \'n\'
									   AND decline_join.del = \'n\'
									   FOR XML PATH (\'\')) as VARCHAR(MAX))  AS decline_role');
			$this->db->select('CAST(
								   (SELECT DISTINCT drop_join.nama_role + RTRIM(\', \')
									  FROM tbl_umb_role as drop_join
									 WHERE tbl_umb_role.if_drop = drop_join.[level]
									   AND drop_join.na = \'n\'
									   AND drop_join.del = \'n\'
									   FOR XML PATH (\'\')) as VARCHAR(MAX))  AS drop_role');
			$this->db->select('tbl_karyawan.nama as disposisi_nama');
			$this->db->select('tbl_karyawan.nik as disposisi_nik');
			$this->db->select('CASE
									WHEN tbl_umb_role.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
									ELSE \'<span class="label label-danger">NON AKTIF</span>\'
							   END as label_active');
			$this->db->from('tbl_umb_role');
			$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_umb_role.disposisi_nik', 'left');
			if ($kode !== NULL) {
				$this->db->where('tbl_umb_role.kode_role', $kode);
			}
			if ($level !== NULL) {
				$this->db->where('tbl_umb_role.level', $level);
			}
			if ($active !== NULL) {
				$this->db->where('tbl_umb_role.na', $active);
			}
			if ($name !== NULL) {
				$this->db->where('tbl_umb_role.nama_role', $name);
			}
			if ($exclude !== NULL) {
				$this->db->where_not_in('tbl_umb_role.level', $exclude);
			}
			$this->db->where('tbl_umb_role.del', $deleted);
			$this->db->order_by('tbl_umb_role.level ASC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_kelas($conn = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			$this->db->select('tbl_umb_kelas.*');
			$this->db->from('tbl_umb_kelas');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_jenis_kriteria($conn = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			$this->db->select('tbl_umb_mjenis_kriteria.*');
			$this->db->from('tbl_umb_mjenis_kriteria');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_jenis_jaminan($conn = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			$this->db->select('tbl_umb_mjenis_jaminan.*');
			$this->db->from('tbl_umb_mjenis_jaminan');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_satuan($conn = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			$this->db->select('tbl_umb_msatuan.*');
			$this->db->from('tbl_umb_msatuan');
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_tipe_scoring($conn = NULL, $id = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			$this->db->select('tbl_umb_scoring_tipe.*');
			$this->db->select('tbl_umb_scoring_tipe.id_scoring_tipe as id');
			$this->db->from('tbl_umb_scoring_tipe');
			if ($id !== NULL) {
				$this->db->where('tbl_umb_scoring_tipe.id_scoring_tipe', $id);
				$query  = $this->db->get();
				$result = $query->row();
			} else {
				$query  = $this->db->get();
				$result = $query->result();
			}

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_scoring($conn = NULL, $id_mscoring_header = NULL, $active = NULL, $deleted = 'n', $id_scoring_tipe = NULL, $kelas = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			if (is_null($deleted))
				$deleted = 'n';

			$this->db->select('tbl_umb_mscoring_header.*');
			$this->db->select('tbl_umb_scoring_tipe.tipe_scoring');
			$this->db->select('CASE
									WHEN tbl_umb_mscoring_header.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
									ELSE \'<span class="label label-danger">NON AKTIF</span>\'
							   END as label_active');
			$this->db->select("
								CAST( 
								  (
									SELECT 
										CAST(tbl_umb_mscoring_detail.no_urut as varchar(250)) + RTRIM('|')+
										CAST(tbl_umb_mscoring_detail.score_awal as varchar(250))+RTRIM('|')+
										CAST(tbl_umb_mscoring_detail.score_akhir as varchar(250))+RTRIM('|')+
										CAST(tbl_umb_mscoring_detail.um as varchar(250)) + RTRIM(', ') 
									FROM 
									tbl_umb_mscoring_detail 
									WHERE 
									tbl_umb_mscoring_detail.id_mscoring_header=tbl_umb_mscoring_header.id_mscoring_header FOR XML PATH ('')
								  ) as VARCHAR(MAX)
								) AS list_detail,
							");
			$this->db->from('tbl_umb_mscoring_header');
			$this->db->join('tbl_umb_scoring_tipe', 'tbl_umb_scoring_tipe.id_scoring_tipe = tbl_umb_mscoring_header.id_scoring_tipe', 'left');
			if ($id_mscoring_header !== NULL) {
				$this->db->where('tbl_umb_mscoring_header.id_mscoring_header', $id_mscoring_header);
			}
			if ($active !== NULL) {
				$this->db->where('tbl_umb_mscoring_header.na', $active);
			}
			if ($id_scoring_tipe !== NULL) {
				$this->db->where('tbl_umb_mscoring_header.id_scoring_tipe', $id_scoring_tipe);
			}
			if ($kelas !== NULL) {
				$this->db->where('tbl_umb_mscoring_header.kelas', $kelas);
			}
			$this->db->where('tbl_umb_mscoring_header.del', $deleted);
			$this->db->order_by('tbl_umb_mscoring_header.id_mscoring_header ASC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}
		function cek_master_kriteria($conn = NULL, $id_mkriteria_header = NULL, $nilai = NULL, $active = 'n', $deleted = 'n') {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('sum(tbl_umb_mkriteria_header.persen_bobot) as total_persen_bobot');
			$this->db->from('tbl_umb_mkriteria_header');
			if ($id_mkriteria_header !== NULL) {
				$this->db->where_not_in('tbl_umb_mkriteria_header.id_mkriteria_header', $id_mkriteria_header);
			}
			if ($active !== NULL) {
				$this->db->where('tbl_umb_mkriteria_header.na', $active);
			}
			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_kriteria($conn = NULL, $id_mkriteria_header = NULL, $active = NULL, $deleted = 'n', $id_mjenis_kriteria = NULL, $satuan = NULL, $kelas = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			if (is_null($deleted))
				$deleted = 'n';

			$this->db->select('tbl_umb_mkriteria_header.*');
			$this->db->select('tbl_umb_mkriteria_header.id_mkriteria_header as id');
			$this->db->select('tbl_umb_mjenis_kriteria.id_mjenis_kriteria as id_jenis');
			$this->db->select('tbl_umb_mjenis_kriteria.alias');
			$this->db->select('tbl_umb_mjenis_kriteria.nama');
			$this->db->select('CASE
									WHEN tbl_umb_mkriteria_header.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
									ELSE \'<span class="label label-danger">NON AKTIF</span>\'
							   END as label_active');
			$this->db->select("
								CAST( 
								  (
									SELECT 
										CAST(tbl_umb_mkriteria_detail.no_urut as varchar(250)) + RTRIM('|')+
										CAST(tbl_umb_mkriteria_detail.param_awal as varchar(250))+RTRIM('|')+
										CAST(tbl_umb_mkriteria_detail.param_akhir as varchar(250))+RTRIM('|')+
										CAST(tbl_umb_mkriteria_detail.nilai as varchar(250)) + RTRIM(', ') 
									FROM 
									tbl_umb_mkriteria_detail 
									WHERE 
									tbl_umb_mkriteria_detail.id_mkriteria_header=tbl_umb_mkriteria_header.id_mkriteria_header FOR XML PATH ('')
								  ) as VARCHAR(MAX)
								) AS list_detail,
							");
			$this->db->from('tbl_umb_mkriteria_header');
			$this->db->join('tbl_umb_mjenis_kriteria', 'tbl_umb_mjenis_kriteria.id_mjenis_kriteria = tbl_umb_mkriteria_header.id_mjenis_kriteria', 'left');
			if ($id_mkriteria_header !== NULL) {
				$this->db->where('tbl_umb_mkriteria_header.id_mkriteria_header', $id_mkriteria_header);
			}
			if ($active !== NULL) {
				$this->db->where('tbl_umb_mkriteria_header.na', $active);
			}
			if ($id_mjenis_kriteria !== NULL) {
				$this->db->where('tbl_umb_mkriteria_header.id_mjenis_kriteria', $id_mjenis_kriteria);
			}
			if ($satuan !== NULL) {
				$this->db->where('tbl_umb_mkriteria_header.satuan', $satuan);
			}
			if ($kelas !== NULL) {
				$this->db->where('tbl_umb_mkriteria_header.kelas', $kelas);
			}
			$this->db->where('tbl_umb_mkriteria_header.del', $deleted);
			$this->db->order_by('tbl_umb_mkriteria_header.id_mjenis_kriteria ASC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_master_jaminan($conn = NULL, $id_mjaminan_header = NULL, $active = NULL, $deleted = 'n', $jenis = NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			if (is_null($deleted))
				$deleted = 'n';

			$this->db->select('tbl_umb_mjaminan_header.*');
			$this->db->select('CASE
									WHEN tbl_umb_mjaminan_header.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
									ELSE \'<span class="label label-danger">NON AKTIF</span>\'
							   END as label_active');
			$this->db->select("
								CAST( 
								  (
									SELECT 
										CAST(tbl_umb_mjaminan_detail.no_urut as varchar(250)) + RTRIM('|')+
										CAST(tbl_umb_mjaminan_detail.detail as varchar(250))+RTRIM('|')+
										CAST(tbl_umb_mjaminan_detail.persen_discount as varchar(250)) + RTRIM(', ') 
									FROM 
									tbl_umb_mjaminan_detail 
									WHERE 
									tbl_umb_mjaminan_detail.id_mjaminan_header=tbl_umb_mjaminan_header.id_mjaminan_header FOR XML PATH ('')
								  ) as VARCHAR(MAX)
								) AS list_detail,
							");
			$this->db->from('tbl_umb_mjaminan_header');
			if ($id_mjaminan_header !== NULL) {
				$this->db->where('tbl_umb_mjaminan_header.id_mjaminan_header', $id_mjaminan_header);
			}
			if ($active !== NULL) {
				$this->db->where('tbl_umb_mjaminan_header.na', $active);
			}
			if ($jenis !== NULL) {
				$this->db->where('tbl_umb_mjaminan_header.jenis', $jenis);
			}
			$this->db->where('tbl_umb_mjaminan_header.del', $deleted);
			$this->db->order_by('tbl_umb_mjaminan_header.id_mjaminan_header ASC');

			$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}
		function get_master_dokumen($conn=NULL, $id_mdokumen=NULL, $active=NULL, $deleted=NULL, $status=NULL, $kepemilikan=NULL){
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$this->db->select('tbl_umb_mdokumen.*');
			$this->db->select('tbl_umb_mdokumen.document as list_detail');
			$this->db->select('CASE
									WHEN tbl_umb_mdokumen.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
									ELSE \'<span class="label label-danger">NON AKTIF</span>\'
							   END as label_active');
			$this->db->from('tbl_umb_mdokumen');
			if($id_mdokumen !== NULL){
				$this->db->where('tbl_umb_mdokumen.id_mdokumen', $id_mdokumen);
			}
			if($active !== NULL) {
				$this->db->where('tbl_umb_mdokumen.na', $active);
			}
			if($deleted !== NULL) {
				$this->db->where('tbl_umb_mdokumen.del', $deleted);
			}
			if($status !== NULL) {
				$this->db->where('tbl_umb_mdokumen.status', $status);
			}
			if($kepemilikan !== NULL) {
				$this->db->where('tbl_umb_mdokumen.kepemilikan', $kepemilikan);
			}
			$this->db->order_by('tbl_umb_mdokumen.id_mdokumen ASC');

			$query 	= $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}


	}

?>
