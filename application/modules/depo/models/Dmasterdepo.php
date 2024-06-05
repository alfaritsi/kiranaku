<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : MASTER DEPO
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmasterdepo extends CI_Model{
	function get_data_role($conn = NULL, $id_role = NULL, $active = NULL, $deleted = 'n', $level = NULL, $nama = NULL, $ck_id_role = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_role.*');
		$this->db->select('CASE
								WHEN tbl_depo_role.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_depo_role');
		if ($id_role !== NULL) {
			$this->db->where('tbl_depo_role.id_role', $id_role);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_role.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_role.del', $deleted);
		}
		if ($level !== NULL) {
			$this->db->where('tbl_depo_role.level', $level);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_depo_role.nama', $nama);
		}
		if ($ck_id_role !== NULL) {
			$this->db->where("tbl_depo_role.id_role!='$ck_id_role'");
		}
		$this->db->order_by("tbl_depo_role.level", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_dokumen($conn = NULL, $id_dokumen = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $ck_id_dokumen = NULL, $jenis_depo = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_depo_dokumen.*');
		$this->db->select('CASE
								WHEN tbl_depo_dokumen.mandatory = \'y\' THEN \'<span class="label label-success">Mandatory</span>\'
								ELSE \'<span class="label label-danger">Tidak Mandatory</span>\'
						   END as label_mandatory');
		$this->db->select('CASE
								WHEN tbl_depo_dokumen.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_depo_dokumen');
		if ($id_dokumen !== NULL) {
			$this->db->where('tbl_depo_dokumen.id_dokumen', $id_dokumen);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_depo_dokumen.nama', $nama);
		}
		if ($ck_id_dokumen !== NULL) {
			$this->db->where("tbl_depo_dokumen.id_dokumen!='$ck_id_dokumen'");
		}else{
			if ($active !== NULL) {
				$this->db->where('tbl_depo_dokumen.na', $active);
			}
			if ($deleted !== NULL) {
				$this->db->where('tbl_depo_dokumen.del', $deleted);
			}
		}
		if ($jenis_depo !== NULL) {
			// $this->db->where('tbl_depo_dokumen.jenis_depo', $jenis_depo);
			$this->db->where("(tbl_depo_dokumen.jenis_depo = '$jenis_depo' or tbl_depo_dokumen.jenis_depo='all')");
		}
		$this->db->order_by("tbl_depo_dokumen.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_nilai($conn = NULL, $id_nilai = NULL, $active = NULL, $deleted = 'n', $keterangan = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_depo_nilai.*');
		$this->db->select('CASE
								WHEN tbl_depo_nilai.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_depo_nilai');
		if ($id_nilai !== NULL) {
			$this->db->where('tbl_depo_nilai.id_nilai', $id_nilai);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_nilai.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_nilai.del', $deleted);
		}
		if ($keterangan !== NULL) {
			$this->db->where('tbl_depo_nilai.keterangan', $keterangan);
		}
		$this->db->order_by("tbl_depo_nilai.nilai_awal", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_master_matrix($conn = NULL, $id_matrix = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_depo_matrix.*');
		$this->db->select('tbl_depo_matrix.id_matrix as id');
		$this->db->select('tbl_depo_matrix.jenis as jenis_matrix');
		$this->db->from('tbl_depo_matrix');
		if ($id_matrix !== NULL) {
			$this->db->where('tbl_depo_matrix.id_matrix', $id_matrix);
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
	function get_data_matrix_mitra($conn = NULL) {
		if ($conn !== NULL)
		$this->general->connectDbPortal();

		$this->db->select('tbl_depo_matrix_detail.*');
		$this->db->select('tbl_depo_matrix.id_matrix');
		$this->db->select('tbl_depo_matrix_detail.id_matrix_detail as id');
		$this->db->from('tbl_depo_matrix_detail');
		$this->db->join('tbl_depo_matrix_header', 'tbl_depo_matrix_header.id_matrix_header = tbl_depo_matrix_detail.id_matrix_header', 'left outer');
		$this->db->join('tbl_depo_matrix', 'tbl_depo_matrix.id_matrix = tbl_depo_matrix_header.id_matrix', 'left outer');
		$this->db->where('tbl_depo_matrix.jenis', 'mitra');
		$this->db->where('tbl_depo_matrix_header.na', 'n');
		$this->db->where('tbl_depo_matrix_detail.na', 'n');

		$query  = $this->db->get();
		$result = $query->result();
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_matrix_header($conn = NULL, $id_matrix_header = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_matrix_header.*');
		$this->db->select('tbl_depo_matrix.jenis as jenis_matrix');
		$this->db->select('tbl_depo_matrix.nama as nama_matrix');
		$this->db->select('CASE
								WHEN tbl_depo_matrix_header.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select("
							 (
							 select top 1 
							 ISNULL(tbl_depo_matrix_detail.param_text,'-')+ RTRIM('|')+ 
							 ISNULL(CAST(tbl_depo_matrix_detail.param_awal as varchar(250)),0) + RTRIM('|')+
							 ISNULL(CAST(tbl_depo_matrix_detail.param_akhir as varchar(250)),0) + RTRIM('|')
							 from 
							 tbl_depo_matrix_detail 
							 where 
							 1=1
							 and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
							 and tbl_depo_matrix_detail.nilai='1'
							 ) as nilai_1
						   ");
		$this->db->select("
							 (
							 select top 1 
							 ISNULL(tbl_depo_matrix_detail.param_text,'-')+ RTRIM('|')+ 
							 ISNULL(CAST(tbl_depo_matrix_detail.param_awal as varchar(250)),0) + RTRIM('|')+
							 ISNULL(CAST(tbl_depo_matrix_detail.param_akhir as varchar(250)),0) + RTRIM('|')
							 from 
							 tbl_depo_matrix_detail 
							 where 
							 1=1
							 and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
							 and tbl_depo_matrix_detail.nilai='2'
							 ) as nilai_2
						   ");
		$this->db->select("
							 (
							 select top 1 
							 ISNULL(tbl_depo_matrix_detail.param_text,'-')+ RTRIM('|')+ 
							 ISNULL(CAST(tbl_depo_matrix_detail.param_awal as varchar(250)),0) + RTRIM('|')+
							 ISNULL(CAST(tbl_depo_matrix_detail.param_akhir as varchar(250)),0) + RTRIM('|')
							 from 
							 tbl_depo_matrix_detail 
							 where 
							 1=1
							 and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
							 and tbl_depo_matrix_detail.nilai='3'
							 ) as nilai_3
						   ");
		$this->db->select("
							 (
							 select top 1 
							 ISNULL(tbl_depo_matrix_detail.param_text,'-')+ RTRIM('|')+ 
							 ISNULL(CAST(tbl_depo_matrix_detail.param_awal as varchar(250)),0) + RTRIM('|')+
							 ISNULL(CAST(tbl_depo_matrix_detail.param_akhir as varchar(250)),0) + RTRIM('|')
							 from 
							 tbl_depo_matrix_detail 
							 where 
							 1=1
							 and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
							 and tbl_depo_matrix_detail.nilai='4'
							 ) as nilai_4
						   ");
		$this->db->from('tbl_depo_matrix_header');
		$this->db->join('tbl_depo_matrix', 'tbl_depo_matrix.id_matrix = tbl_depo_matrix_header.id_matrix', 'left');
		if ($id_matrix_header !== NULL) {
			$this->db->where('tbl_depo_matrix_header.id_matrix_header', $id_matrix_header);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_matrix_header.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_matrix_header.del', $deleted);
		}
		$this->db->where('tbl_depo_matrix_header.na', 'n');
		$this->db->order_by('tbl_depo_matrix_header.id_matrix_header ASC');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_matrix_scoring($conn = NULL, $id_matrix_header = NULL, $active = NULL, $deleted = 'n', $nomor = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_depo_matrix_header.*');
		$this->db->select('tbl_depo_matrix.jenis as jenis_matrix');
		$this->db->select('tbl_depo_matrix.nama as nama_matrix');
		$this->db->select("
								CASE
								WHEN tbl_depo_matrix.id_matrix = 1 THEN 
									(select top 1 CAST(tbl_depo_data.estimasi_tonase_kering as varchar(250)) from tbl_depo_data where tbl_depo_data.nomor='$nomor') + RTRIM('|')+
									(select top 1 CAST(tbl_depo_matrix_detail.nilai as varchar(250))
										from tbl_depo_matrix_detail 
										where
										1=1	
										and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
										and tbl_depo_matrix_detail.param_awal <= (select tbl_depo_data.estimasi_tonase_kering from tbl_depo_data where tbl_depo_data.nomor='$nomor')
										and tbl_depo_matrix_detail.param_akhir >= (select tbl_depo_data.estimasi_tonase_kering from tbl_depo_data where tbl_depo_data.nomor='$nomor')
										and tbl_depo_matrix_detail.na='n'
									)
								WHEN tbl_depo_matrix.id_matrix = 2 THEN 
									(select CAST(count(tbl_depo_data_lokasi.nomor) as varchar(250)) from tbl_depo_data_lokasi where tbl_depo_data_lokasi.na='n' and tbl_depo_data_lokasi.nomor='$nomor' and (tbl_depo_data_lokasi.gudang_kompetitor is not null or tbl_depo_data_lokasi.pabrik_kompetitor is not null) and tbl_depo_data_lokasi.jarak>=30) + RTRIM('|')+
									(select top 1 CAST(tbl_depo_matrix_detail.nilai as varchar(250))
										from tbl_depo_matrix_detail 
										where
										1=1	
										and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
										and tbl_depo_matrix_detail.param_awal <= (select count(tbl_depo_data_lokasi.gudang_kompetitor) from tbl_depo_data_lokasi where tbl_depo_data_lokasi.na='n' and tbl_depo_data_lokasi.nomor='$nomor' and tbl_depo_data_lokasi.gudang_kompetitor is not null)
										and tbl_depo_matrix_detail.param_akhir >= (select count(tbl_depo_data_lokasi.gudang_kompetitor) from tbl_depo_data_lokasi where tbl_depo_data_lokasi.na='n' and tbl_depo_data_lokasi.nomor='$nomor' and tbl_depo_data_lokasi.gudang_kompetitor is not null)
										and tbl_depo_matrix_detail.na='n'
									)
								WHEN tbl_depo_matrix.id_matrix = 3 THEN 
									(select CAST(sum(tbl_depo_data_desa.luas) as varchar(250)) from tbl_depo_data_desa where tbl_depo_data_desa.na='n' and tbl_depo_data_desa.nomor='$nomor') + RTRIM('|')+
									(select top 1 CAST(tbl_depo_matrix_detail.nilai as varchar(250))
										from tbl_depo_matrix_detail 
										where
										1=1	
										and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
										and tbl_depo_matrix_detail.param_awal <= (select sum(tbl_depo_data_desa.luas) from tbl_depo_data_desa where tbl_depo_data_desa.na='n' and tbl_depo_data_desa.nomor='$nomor')
										and tbl_depo_matrix_detail.param_akhir >= (select sum(tbl_depo_data_desa.luas) from tbl_depo_data_desa where tbl_depo_data_desa.na='n' and tbl_depo_data_desa.nomor='$nomor')
										and tbl_depo_matrix_detail.na='n'
									)
								WHEN tbl_depo_matrix.id_matrix = 4 THEN 
									(select top 1 CAST(tbl_depo_data.jumlah_pelelangan as varchar(250)) from tbl_depo_data where tbl_depo_data.nomor='$nomor') + RTRIM('|')+
									(select top 1 CAST(tbl_depo_matrix_detail.nilai as varchar(250))
										from tbl_depo_matrix_detail 
										where
										1=1	
										and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
										and tbl_depo_matrix_detail.param_awal <= (select tbl_depo_data.jumlah_pelelangan from tbl_depo_data where tbl_depo_data.nomor='$nomor')
										and tbl_depo_matrix_detail.param_akhir >= (select tbl_depo_data.jumlah_pelelangan from tbl_depo_data where tbl_depo_data.nomor='$nomor')
										and tbl_depo_matrix_detail.na='n'
									)
								WHEN tbl_depo_matrix.id_matrix = 5 THEN 
									(select top 1 CAST(tbl_depo_data.jumlah_tronton_per_minggu as varchar(250)) from tbl_depo_data where tbl_depo_data.nomor='$nomor') + RTRIM('|')+
									(select top 1 CAST(tbl_depo_matrix_detail.nilai as varchar(250))
										from tbl_depo_matrix_detail 
										where
										1=1	
										and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
										and tbl_depo_matrix_detail.param_awal <= (select tbl_depo_data.jumlah_tronton_per_minggu from tbl_depo_data where tbl_depo_data.nomor='$nomor')
										and tbl_depo_matrix_detail.param_akhir >= (select tbl_depo_data.jumlah_tronton_per_minggu from tbl_depo_data where tbl_depo_data.nomor='$nomor')
										and tbl_depo_matrix_detail.na='n'
									)
								WHEN tbl_depo_matrix.id_matrix = 6 THEN 
									(select 
										top 1 CAST(tbl_depo_matrix_detail.param_text as varchar(250)) 
										from tbl_depo_data 
										left outer join tbl_depo_matrix_detail on tbl_depo_matrix_detail.nilai=tbl_depo_data.modal_kerja and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
										where tbl_depo_data.nomor='$nomor'
									)
									+ RTRIM('|')+
									(select 
										top 1 CAST(tbl_depo_matrix_detail.nilai as varchar(250)) 
										from tbl_depo_data 
										left outer join tbl_depo_matrix_detail on tbl_depo_matrix_detail.nilai=tbl_depo_data.modal_kerja and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
										where tbl_depo_data.nomor='$nomor'
									)
								WHEN tbl_depo_matrix.id_matrix = 7 THEN 
									(select top 1 CAST(tbl_depo_matrix_detail.param_text as varchar(250))
										from tbl_depo_matrix_detail
										where
										1=1	
										and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
										and tbl_depo_matrix_detail.na='n'
									)
									+ RTRIM('|')+
									(select top 1 CAST(tbl_depo_matrix_detail.nilai as varchar(250))
										from tbl_depo_matrix_detail
										where
										1=1	
										and tbl_depo_matrix_detail.id_matrix_header=tbl_depo_matrix_header.id_matrix_header
										and tbl_depo_matrix_detail.na='n'
									)
								WHEN tbl_depo_matrix.id_matrix = 8 THEN 
									'99|99'
								ELSE 
									''
						   END as nilai
						   ");
		$this->db->from('tbl_depo_matrix_header');
		$this->db->join('tbl_depo_matrix', 'tbl_depo_matrix.id_matrix = tbl_depo_matrix_header.id_matrix', 'left');
		if ($id_matrix_header !== NULL) {
			$this->db->where('tbl_depo_matrix_header.id_matrix_header', $id_matrix_header);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_matrix_header.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_matrix_header.del', $deleted);
		}
		$this->db->where('tbl_depo_matrix_header.na', 'n');
		$this->db->order_by('tbl_depo_matrix_header.id_matrix_header ASC');

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_matrix_detail($conn = NULL, $id_matrix_header = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_depo_matrix_detail.id_matrix_detail as id");
		$this->db->select('tbl_depo_matrix_detail.*');
		$this->db->from('tbl_depo_matrix_detail');

		if ($id_matrix_header !== NULL) {
			$this->db->where('tbl_depo_matrix_detail.id_matrix_header', $id_matrix_header);
		}
		$this->db->where("tbl_depo_matrix_detail.na='n'");

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_biaya($conn = NULL, $id_biaya = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $ck_jenis_depo = NULL, $ck_jenis_biaya = NULL, $ck_jenis_biaya_detail = NULL, $ck_nama = NULL, $filter_jenis_depo = NULL, $filter_jenis_biaya = NULL, $ck_id_biaya = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_depo_biaya.*');
		$this->db->select('CASE
								WHEN tbl_depo_biaya.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_depo_biaya');
		if ($id_biaya !== NULL) {
			$this->db->where('tbl_depo_biaya.id_biaya', $id_biaya);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_depo_biaya.nama', $nama);
		}
		if ($ck_id_biaya !== NULL) {
			$this->db->where("tbl_depo_biaya.id_biaya!='$ck_id_biaya'");
		}else{
			if ($active !== NULL) {
				$this->db->where('tbl_depo_biaya.na', $active);
			}
			if ($deleted !== NULL) {
				$this->db->where('tbl_depo_biaya.del', $deleted);
			}
			if ($ck_jenis_depo !== NULL) {
				$this->db->where("tbl_depo_biaya.jenis_depo='$ck_jenis_depo'");
			}
			if ($ck_jenis_biaya !== NULL) {
				$this->db->where("tbl_depo_biaya.jenis_biaya='$ck_jenis_biaya'");
			}
			if ($ck_jenis_biaya_detail !== NULL) {
				$this->db->where("tbl_depo_biaya.jenis_biaya_detail='$ck_jenis_biaya_detail'");
			}
			if ($ck_nama !== NULL) {
				$this->db->where("tbl_depo_biaya.nama='$ck_nama'");
			}
			if($filter_jenis_depo != NULL){
				if(is_string($filter_jenis_depo)) $filter_jenis_depo = explode(",", $filter_jenis_depo);
				$this->db->where_in('tbl_depo_biaya.jenis_depo', $filter_jenis_depo);
			}
			if($filter_jenis_biaya != NULL){
				if(is_string($filter_jenis_biaya)) $filter_jenis_biaya = explode(",", $filter_jenis_biaya);
				$this->db->where_in('tbl_depo_biaya.jenis_biaya', $filter_jenis_biaya);
			}
		}
		
		$this->db->order_by("tbl_depo_biaya.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_lokasi($conn = NULL, $id_lokasi = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $ck_id_lokasi = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_depo_lokasi.*');
		$this->db->select('CASE
								WHEN tbl_depo_lokasi.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_depo_lokasi');
		if ($id_lokasi !== NULL) {
			$this->db->where('tbl_depo_lokasi.id_lokasi', $id_lokasi);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_lokasi.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_lokasi.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_depo_lokasi.nama', $nama);
		}
		if ($ck_id_lokasi !== NULL) {
			$this->db->where("tbl_depo_lokasi.id_lokasi!='$ck_id_lokasi'");
		}
		$this->db->order_by("tbl_depo_lokasi.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_keuangan($conn = NULL, $id_keuangan = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_depo_keuangan.*');
		$this->db->select('CASE
								WHEN tbl_depo_keuangan.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_depo_keuangan');
		if ($id_keuangan !== NULL) {
			$this->db->where('tbl_depo_keuangan.id_keuangan', $id_keuangan);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_keuangan.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_keuangan.del', $deleted);
		}
		$this->db->order_by("tbl_depo_keuangan.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_gambar($conn = NULL, $id_gambar = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select('tbl_depo_gambar.*');
		$this->db->select('CASE
								WHEN tbl_depo_gambar.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_depo_gambar');
		if ($id_gambar !== NULL) {
			$this->db->where('tbl_depo_gambar.id_gambar', $id_gambar);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_depo_gambar.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_depo_gambar.del', $deleted);
		}
		$this->db->order_by("tbl_depo_gambar.urutan", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_divisi($conn = NULL, $id_divisi = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_divisi.*');
		$this->db->select('CASE
								WHEN tbl_divisi.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_divisi');
		if ($id_divisi !== NULL) {
			$this->db->where('tbl_divisi.id_divisi', $id_divisi);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_divisi.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_divisi.del', $deleted);
		}
		$this->db->order_by("tbl_divisi.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
    public function get_master_divisixx($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;

        $this->db->select('*');
        $this->db->from('tbl_divisi');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_divisi.na', 'n');
            $this->db->where('tbl_divisi.del', 'n');
        }

        if (isset($id_divisi))
            $this->db->where('tbl_divisi.id_divisi', $id_divisi);

        $this->db->order_by('tbl_divisi.nama');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
	
}
?>