<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KLEMS (Kirana Learning Management System)
@author     : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dlaporanklems extends CI_Model{
	
	function get_data_detail_nilai($id_program_batch=NULL, $id_karyawan=NULL){
		$this->general->connectDbPortal();
		
		$this->db->select('tbl_batch.id_batch');
		$this->db->select('tbl_tahap.nama');
		$this->db->select("
							  (select 
								--cast(ROUND(avg(tbl_batch_score.score*tbl_batch_nilai.bobot/100) , 2) as numeric(36,2))
								cast(ROUND(avg(tbl_batch_score.score) , 2) as numeric(36,2))
								from tbl_batch_score 
								left join tbl_batch_nilai on tbl_batch_nilai.id_batch_nilai=tbl_batch_score.id_batch_nilai
								where
								tbl_batch_score.na='n' 
								and tbl_batch_score.id_batch=tbl_batch.id_batch
								and tbl_batch_score.id_karyawan=$id_karyawan
							  )as average 
						");
		$this->db->select("
							  (select 
									tbl_grade.nama 
									from tbl_batch_grade 
									left outer join tbl_grade on tbl_grade.id_grade=tbl_batch_grade.id_grade 
									where tbl_batch_grade.id_batch=tbl_batch.id_batch 
									and 
									(
										  (select 
											--cast(ROUND(avg(tbl_batch_score.score*tbl_batch_nilai.bobot/100) , 2) as numeric(36,2))
											cast(ROUND(avg(tbl_batch_score.score) , 2) as numeric(36,2))
											from tbl_batch_score 
											left join tbl_batch_nilai on tbl_batch_nilai.id_batch_nilai=tbl_batch_score.id_batch_nilai
											where
											tbl_batch_score.na='n' 
											and tbl_batch_score.id_batch=tbl_batch.id_batch
											and tbl_batch_score.id_karyawan=$id_karyawan
										  )>=tbl_batch_grade.grade_awal 
										and 
										tbl_batch_grade.grade_akhir>=(select 
											--cast(ROUND(avg(tbl_batch_score.score*tbl_batch_nilai.bobot/100) , 2) as numeric(36,2))
											cast(ROUND(avg(tbl_batch_score.score) , 2) as numeric(36,2))
											from tbl_batch_score 
											left join tbl_batch_nilai on tbl_batch_nilai.id_batch_nilai=tbl_batch_score.id_batch_nilai
											where
											tbl_batch_score.na='n' 
											and tbl_batch_score.id_batch=tbl_batch.id_batch
											and tbl_batch_score.id_karyawan=$id_karyawan
										  )										
									)
								) as grade
						");
		$this->db->from('tbl_batch');
		$this->db->join('tbl_tahap', 'tbl_tahap.id_tahap = tbl_batch.id_tahap
										 AND tbl_tahap.na = \'n\'', 'left');		
		$this->db->where('tbl_batch.na', 'n');
		$this->db->where('tbl_batch.del', 'n');
		if($id_program_batch!=null){
			$this->db->where('tbl_batch.id_program_batch', $id_program_batch);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_biaya($program=NULL, $tahun=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_program_batch.nama');
		$this->db->select('YEAR(tbl_program_batch.tanggal_awal) as tahun');
		$this->db->select('CONVERT(VARCHAR(10), tbl_program_batch.tanggal_awal, 105) as tanggal_awal');
		$this->db->select('CONVERT(VARCHAR(10), tbl_program_batch.tanggal_akhir, 105) as tanggal_akhir');
		$this->db->select("ISNULL((tbl_program_budget.budget_training)/(select count(*) from tbl_program_batch tbl_program_batch2 where tbl_program_batch2.id_program=tbl_program_batch.id_program and tbl_program_batch2.na='n'),0) as budget_training,");
		$this->db->select('ISNULL((tbl_program_batch.biaya_training),0) as aktual_training');
		$this->db->select("ISNULL((tbl_program_budget.budget_traveling)/(select count(*) from tbl_program_batch tbl_program_batch2 where tbl_program_batch2.id_program=tbl_program_batch.id_program and tbl_program_batch2.na='n'),0) as budget_traveling,");
		$this->db->select('ISNULL((tbl_program_batch.biaya_traveling),0) as aktual_traveling');
		$this->db->select("ISNULL((tbl_program_budget.budget_training+tbl_program_budget.budget_traveling)/(select count(*) from tbl_program_batch tbl_program_batch2 where tbl_program_batch2.id_program=tbl_program_batch.id_program and tbl_program_batch2.na='n'),0) as budget,");
		$this->db->select('ISNULL((tbl_program_batch.biaya_training+tbl_program_batch.biaya_traveling),0) as aktual');
		$this->db->select("ISNULL((tbl_program_budget.budget_training+tbl_program_budget.budget_traveling)/(select count(*) from tbl_program_batch tbl_program_batch2 where tbl_program_batch2.id_program=tbl_program_batch.id_program and tbl_program_batch2.na='n')-(tbl_program_batch.biaya_training+tbl_program_batch.biaya_traveling),0) as sisa");
		$this->db->select('tbl_program.nama as nama_program');
		$this->db->select('tbl_program.jenis as jenis_program');
		$this->db->select("'All Program' as root");
		$this->db->from('tbl_program_batch');
		$this->db->join('tbl_program', 'tbl_program.id_program = tbl_program_batch.id_program 
										 AND tbl_program.na = \'n\'', 'left');		
		$this->db->join('tbl_program_budget', "tbl_program_budget.id_program = tbl_program.id_program 
										 AND tbl_program_budget.na = 'n' and tbl_program_budget.tahun = '$tahun'", 'left');		
		$this->db->where('tbl_program_batch.na', 'n');
		$this->db->where('tbl_program_batch.del', 'n');
		if($tahun != NULL){
			$this->db->where("YEAR(tbl_program_batch.tanggal_awal)='".$tahun."'");
		}
		
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_tahun(){
		$this->general->connectDbPortal();
		$this->db->select('YEAR(tbl_batch.tanggal_buat) as tahun');
		$this->db->from('tbl_batch');
		$this->db->where('tbl_batch.na', 'n');
		$this->db->where('tbl_batch.del', 'n');
		$this->db->group_by('YEAR(tbl_batch.tanggal_buat)');
		$this->db->order_by('YEAR(tbl_batch.tanggal_buat)', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_posisi($id_posisi=NULL, $all=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_posisi.*');
		$this->db->from('tbl_posisi');
		$this->db->where('tbl_posisi.na', 'n');
		$this->db->where('tbl_posisi.del', 'n');
		if($id_posisi!=null){
			$this->db->where('tbl_posisi.id_posisi', $id_posisi);
		}
		$this->db->order_by('tbl_posisi.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_tahap($id_tahap=NULL, $all=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_tahap.*');
		$this->db->select('(select count(*) from tbl_tahap where na=\'n\' and del=\'n\') as rows');
		$this->db->from('tbl_tahap');
		$this->db->where('tbl_tahap.na', 'n');
		$this->db->where('tbl_tahap.del', 'n');
		if($id_tahap!=null){
			$this->db->where('tbl_tahap.id_tahap', $id_tahap);
		}
		$this->db->order_by('tbl_tahap.id_tahap', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_tahap_distinct($id_tahap=NULL, $all=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_tahap.nama');
		$this->db->select('(select count(distinct(tbl_tahap.nama)) from tbl_tahap where na=\'n\' and del=\'n\') as rows');
		$this->db->from('tbl_tahap');
		$this->db->where('tbl_tahap.na', 'n');
		$this->db->where('tbl_tahap.del', 'n');
		if($id_tahap!=null){
			$this->db->where('tbl_tahap.id_tahap', $id_tahap);
		}
		$this->db->order_by('tbl_tahap.nama', 'ASC');
		$this->db->group_by('tbl_tahap.nama');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_batch_nilai($id_program_batch=NULL, $all=NULL, $id_karyawan=NULL){
		$this->general->connectDbPortal();

		$this->db->select("tbl_program_batch.*,
							CASE
								WHEN tbl_program_batch.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
								ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
							END as label_active,
							CAST( 
							( 
							  SELECT tbl_karyawan.nama + RTRIM(',') 
								FROM tbl_karyawan 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_program_batch.peserta, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as nama_peserta,
							CAST( 
							( 
							  SELECT tbl_karyawan.nama + RTRIM(',') 
								FROM tbl_karyawan 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_program_batch.peserta_tambahan, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as nama_peserta_tambahan
							");
		$this->db->select('tbl_bpo.nama as nama_bpo');
		$this->db->select('tbl_program.nama as nama_program');				   
		$this->db->from('tbl_program_batch');
		$this->db->join('tbl_bpo', 'tbl_bpo.id_bpo = tbl_program_batch.id_bpo 
										 AND tbl_bpo.na = \'n\'', 'left');		
		$this->db->join('tbl_program', 'tbl_program.id_program = tbl_program_batch.id_program 
										 AND tbl_program.na = \'n\'', 'left');		
		
		if($kode != NULL){
			$this->db->where('tbl_program_batch.kode', $kode);
		}
		if($all == NULL){
			$this->db->where('tbl_program_batch.na', 'n');
		}
		if($id_program_batch != NULL){
			$this->db->where('tbl_program_batch.id_program_batch', $id_program_batch);
		}
		if($id_program_batch_in != NULL){
			$this->db->where_in('tbl_program_batch.id_program_batch', $id_program_batch_in);
		}
		if($peserta_in != NULL){
			$this->db->like('tbl_program_batch.peserta', $peserta_in);
		}
		if($peserta_in != NULL){
			$this->db->or_like('tbl_program_batch.peserta_tambahan', $peserta_in);
		}
		$this->db->order_by('tbl_program_batch.id_program_batch', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}
	
	function get_data_peserta($id_peserta=NULL, $all=NULL, $regional_in=NULL, $nik_in=NULL, $posisi_in=NULL, $program_in=NULL, $pabrik_in=NULL, $tahun=NULL, $awal=NULL, $akhir=NULL, $sertifikat=NULL){
//		$this->general->connectDbPortal();
		  $this->general->connectDbDefault();
		$this->db->select('tbl_karyawan.id_karyawan as nik');
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select('tbl_karyawan.gsber');
		$this->db->select('tbl_karyawan.posst as posisi_sekarang');
		$this->db->select("SUBSTRING(tbl_karyawan.tanggal_join, 7, 2)+'-'+SUBSTRING(tbl_karyawan.tanggal_join, 5, 2)+'-'+SUBSTRING(tbl_karyawan.tanggal_join, 0, 5) as tanggal_join");
		$this->db->select("SUBSTRING(tbl_karyawan.gbpas, 7, 2)+'-'+SUBSTRING(tbl_karyawan.gbpas, 5, 2)+'-'+SUBSTRING(tbl_karyawan.gbpas, 0, 5) as gbpas");
		$this->db->select('tbl_program_batch.id_program_batch');		
		$this->db->select('tbl_program_batch.kode as kode_program_batch');		
		$this->db->select('tbl_program_batch.nama as nama_program_batch');		
		$this->db->select('tbl_program_batch.tanggal_awal as tanggal_awal_batch');		
		$this->db->select('tbl_program_batch.tanggal_akhir as tanggal_akhir_batch');		
		$this->db->select('tbl_program.id_program');		
		$this->db->select('tbl_program.nama as nama_program');
		$this->db->select('tbl_peserta.nomor_sertifikat');
		$this->db->select('tbl_program_batch.ttd_kiri');
		$this->db->select("tbl_program_batch.ttd_kanan");
		$this->db->select('tbl_peserta.status_kiri');
		$this->db->select("tbl_peserta.status_kanan");
		$this->db->select("tbl_peserta.status_print");
		$this->db->select("
						   CASE
								WHEN tbl_peserta.status_kiri = 1 THEN '<span class=\"label label-success\">Approved</span>'
								ELSE '<span class=\"label label-default\">Not Approve</span>'
						   END as status1,
						   CASE
								WHEN tbl_peserta.status_kanan = 1 THEN '<span class=\"label label-success\">Approved</span>'
								ELSE '<span class=\"label label-default\">Not Approve</span>'
						   END as status2,
						   CASE
								WHEN tbl_peserta.status_print = 1 THEN '<span class=\"label label-success\">Printed</span>'
								ELSE '<span class=\"label label-default\">Not Print</span>'
						   END as status_print
						");
		$this->db->select("
							  (select 
								CAST(ROUND(avg(tbl_batch_score.score) , 2) as numeric(36,2))
								from ".DB_PORTAL.".dbo.tbl_batch_score 
								left join ".DB_PORTAL.".dbo.tbl_batch_nilai on tbl_batch_nilai.id_batch_nilai=tbl_batch_score.id_batch_nilai
								where
								tbl_batch_score.na='n' 
								and tbl_batch_score.id_batch in (select tbl_batch2.id_batch from ".DB_PORTAL.".dbo.tbl_batch tbl_batch2 where tbl_batch2.id_program_batch=tbl_program_batch.id_program_batch)
								and tbl_batch_score.id_peserta in (select tbl_peserta2.id_peserta from ".DB_PORTAL.".dbo.tbl_peserta tbl_peserta2 where tbl_peserta2.id_karyawan=tbl_karyawan.id_karyawan and tbl_peserta2.id_batch in(select tbl_batch3.id_batch from ".DB_PORTAL.".dbo.tbl_batch tbl_batch3 where tbl_batch3.id_program_batch=tbl_program_batch.id_program_batch))
							  )
							  as average 
						");
		$this->db->select("
							  (select 
									top 1 tbl_grade.nama 
									from ".DB_PORTAL.".dbo.tbl_program_grade 
									left outer join ".DB_PORTAL.".dbo.tbl_grade on tbl_grade.id_grade=tbl_program_grade.id_grade 
									where 1=1
									and 
									(
										  (select 
											CAST(ROUND(avg(tbl_batch_score.score) , 2) as numeric(36,2))
											from ".DB_PORTAL.".dbo.tbl_batch_score 
											left join ".DB_PORTAL.".dbo.tbl_batch_nilai on tbl_batch_nilai.id_batch_nilai=tbl_batch_score.id_batch_nilai
											where
											tbl_batch_score.na='n' 
											and tbl_batch_score.id_batch in (select tbl_batch2.id_batch from ".DB_PORTAL.".dbo.tbl_batch tbl_batch2 where tbl_batch2.id_program_batch=tbl_program_batch.id_program_batch)
											and tbl_batch_score.id_peserta in (select tbl_peserta2.id_peserta from ".DB_PORTAL.".dbo.tbl_peserta tbl_peserta2 where tbl_peserta2.id_karyawan=tbl_karyawan.id_karyawan and tbl_peserta2.id_batch in(select tbl_batch3.id_batch from ".DB_PORTAL.".dbo.tbl_batch tbl_batch3 where tbl_batch3.id_program_batch=tbl_program_batch.id_program_batch))
										  )
										  >=tbl_program_grade.grade_awal 
										and 
										tbl_program_grade.grade_akhir>=
										  (select 
											CAST(ROUND(avg(tbl_batch_score.score) , 2) as numeric(36,2))
											from ".DB_PORTAL.".dbo.tbl_batch_score 
											left join ".DB_PORTAL.".dbo.tbl_batch_nilai on tbl_batch_nilai.id_batch_nilai=tbl_batch_score.id_batch_nilai
											where
											tbl_batch_score.na='n' 
											and tbl_batch_score.id_batch in (select tbl_batch2.id_batch from ".DB_PORTAL.".dbo.tbl_batch tbl_batch2 where tbl_batch2.id_program_batch=tbl_program_batch.id_program_batch)
											and tbl_batch_score.id_peserta in (select tbl_peserta2.id_peserta from ".DB_PORTAL.".dbo.tbl_peserta tbl_peserta2 where tbl_peserta2.id_karyawan=tbl_karyawan.id_karyawan and tbl_peserta2.id_batch in(select tbl_batch3.id_batch from ".DB_PORTAL.".dbo.tbl_batch tbl_batch3 where tbl_batch3.id_program_batch=tbl_program_batch.id_program_batch))
										  )
									)
								) as grade
						");
		$this->db->select("
							CAST( (
									  SELECT
										CAST(
										(select
											(CASE 
												WHEN sum(tbl_batch_score.score*tbl_batch_nilai.bobot/100) IS NULL THEN 0 
												ELSE sum(tbl_batch_score.score*tbl_batch_nilai.bobot/100)
											 END)										
											from 
											".DB_PORTAL.".dbo.tbl_batch_score 
											left join ".DB_PORTAL.".dbo.tbl_batch_nilai on tbl_batch_nilai.id_batch_nilai=tbl_batch_score.id_batch_nilai
											where 
											tbl_batch_score.id_karyawan=tbl_karyawan.id_karyawan and 
											tbl_batch_score.id_batch in(
																			select tbl_batch2.id_batch 
																			from ".DB_PORTAL.".dbo.tbl_batch as tbl_batch2 
																			left join ".DB_PORTAL.".dbo.tbl_tahap as tbl_tahap2 on tbl_tahap2.id_tahap=tbl_batch2.id_tahap 
																			where 
																			tbl_batch2.id_program_batch=tbl_program_batch.id_program_batch and tbl_tahap2.nama=tbl_tahap3.nama
																		)
										)as varchar(250))+ RTRIM(',')            
									  FROM 
									  ".DB_PORTAL.".dbo.tbl_tahap as tbl_tahap3
									  WHERE 
									  tbl_tahap3.na='n'
									  group by tbl_tahap3.nama
							  FOR XML PATH ('')) as VARCHAR(MAX)) AS list_nilai_tahap
		
		");				  
						  
		$this->db->select("(select tbl_karyawan.nama from ".DB_PORTAL.".dbo.tbl_karyawan where tbl_karyawan.id_karyawan=tbl_program_batch.ttd_kiri) as nama_ttd_kiri");
		$this->db->select("(select tbl_karyawan.nama from ".DB_PORTAL.".dbo.tbl_karyawan where tbl_karyawan.id_karyawan=tbl_program_batch.ttd_kanan) as nama_ttd_kanan");
		$this->db->select("(select top 1 zdmom0001.posisi from ".DB_DEFAULT.".dbo.zdmom0001 where zdmom0001.pernr collate SQL_Latin1_General_CP1_CI_AS =right('00000000'+convert(varchar(8), tbl_karyawan.id_karyawan), 8) ) as posisi_batch");

		$this->db->from(''.DB_PORTAL.'.dbo.tbl_peserta as tbl_peserta');
		$this->db->join(''.DB_PORTAL.'.dbo.tbl_karyawan as tbl_karyawan', 'tbl_karyawan.nik = tbl_peserta.id_karyawan
										 ', 'left');		
		$this->db->join(''.DB_PORTAL.'.dbo.tbl_user as tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan
										 AND tbl_user.na = \'n\'', 'left');		
		$this->db->join(''.DB_PORTAL.'.dbo.tbl_batch as tbl_batch', 'tbl_batch.id_batch = tbl_peserta.id_batch
										 AND tbl_batch.na = \'n\'', 'left');	
		$this->db->join(''.DB_PORTAL.'.dbo.tbl_program_batch as tbl_program_batch', 'tbl_program_batch.id_program_batch = tbl_batch.id_program_batch 
										 AND tbl_program_batch.na = \'n\'', 'left');		
		$this->db->join(''.DB_PORTAL.'.dbo.tbl_program as tbl_program', 'tbl_program.id_program = tbl_program_batch.id_program 
										 AND tbl_program.na = \'n\'', 'left');		
		$this->db->join(''.DB_PORTAL.'.dbo.tbl_tahap as tbl_tahap', 'tbl_tahap.id_tahap = tbl_batch.id_tahap 
										 AND tbl_program.na = \'n\'', 'left');		
		$this->db->where('tbl_peserta.na', 'n');
		$this->db->where('tbl_peserta.del', 'n');
		$this->db->where("tbl_program_batch.status='Done'");
		
		
		if($id_peserta!=null){
			$this->db->where('tbl_peserta.id_peserta', $id_peserta);
		}
		if($regional_in != NULL){
			if(is_string($regional_in)) $regional_in = explode(",", $regional_in);
			$regional = "";
			foreach($regional_in as $key) {    
				$regional .="'".$key."',";    
			}			
			$filter_regional = substr($regional, 0, -1);
			$this->db->where("tbl_karyawan.persa in(select DISTINCT(plant_code) from tbl_wf_region WHERE region_code in ($filter_regional))");
		}
		if($nik_in != NULL){
			if(is_string($nik_in)) $nik_in = explode(",", $nik_in);
			$this->db->where_in('tbl_karyawan.id_karyawan', $nik_in);
		}
		if($program_in != NULL){
			if(is_string($program_in)) $program_in = explode(",", $program_in);
			$this->db->where_in('tbl_program_batch.id_program', $program_in);
		}
		if($posisi_in != NULL){
			if(is_string($posisi_in)) $posisi_in = explode(",", $posisi_in);
			$this->db->where_in('tbl_karyawan.posst', $posisi_in);
		}
		if($pabrik_in != NULL){
			if(is_string($pabrik_in)) $pabrik_in = explode(",", $pabrik_in);
			$this->db->where_in('tbl_karyawan.gsber', $pabrik_in);
		}
		if($tahun != NULL){
			$this->db->where("YEAR(tbl_batch.tanggal_awal)='".$tahun."'");
		}
		if($sertifikat != NULL){
			$this->db->where("tbl_peserta.nomor_sertifikat is not null");
		}
		// $filter_awal = ($awal==NULL)?date('Y-m-d', strtotime(date('Y-m-d').'-3 months')):$awal;
		// // // $filter_akhir = ($akhir==NULL)?date('Y-m-d'):$akhir;
		// $filter_akhir = ($akhir==NULL)?date('Y-m-d', strtotime(date('Y-m-d').'+3 months')):$akhir;
		// $this->db->where("tbl_batch.tanggal_awal between '$filter_awal' and '$filter_akhir'");	
		if(($awal != NULL)&&($akhir != NULL)){
			$this->db->where("tbl_batch.tanggal_awal between '$awal' and '$akhir'");
		}
		
		$this->db->group_by(array(

									'tbl_karyawan.id_karyawan', 
									'tbl_karyawan.nama', 
									'tbl_karyawan.gsber', 
									'tbl_karyawan.posst',
									'tbl_karyawan.tanggal_join', 
									'tbl_karyawan.gbpas', 
									'tbl_program_batch.id_program_batch', 
									'tbl_program_batch.kode', 
									'tbl_program_batch.nama', 
									'tbl_program_batch.tanggal_awal',
									'tbl_program_batch.tanggal_akhir',
									'tbl_program.id_program', 
									'tbl_program.nama',
									'tbl_peserta.nomor_sertifikat',
									'tbl_program_batch.ttd_kiri',
									'tbl_program_batch.ttd_kanan',
									'tbl_peserta.status_kiri',
									'tbl_peserta.status_kanan',
									'tbl_peserta.status_print'
								)
							);
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_batch_komentar($id_batch_komentar=NULL, $all=NULL, $id_batch=NULL, $id_karyawan=NULL, $jenis=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_batch_komentar.*');
		$this->db->from('tbl_batch_komentar');
		$this->db->where('tbl_batch_komentar.na', 'n');
		$this->db->where('tbl_batch_komentar.del', 'n');
		if($id_batch!=null){
			$this->db->where('tbl_batch_komentar.id_batch', $id_batch);
		}
		if($id_karyawan!=null){
			$this->db->where('tbl_batch_komentar.id_karyawan', $id_karyawan);
		}
		if($jenis!=null){
			$this->db->where('tbl_batch_komentar.jenis', $jenis);
		}
		$this->db->order_by('tbl_batch_komentar.id_batch_komentar', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_feedback_kategori($id_feedback_kategori=NULL, $all=NULL, $id_batch=NULL, $jenis=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_feedback_kategori.*');
		if($id_batch!=null){
		$this->db->select("
							  (select avg(convert(decimal(18,2),tbl_batch_feedback.id_feedback_nilai)) 
									from tbl_batch_feedback 
									where 
									tbl_batch_feedback.id_feedback_kategori=tbl_feedback_kategori.id_feedback_kategori and
									tbl_batch_feedback.id_batch='$id_batch' and tbl_batch_feedback.na='n' and tbl_batch_feedback.del='n'
							  )as average 
						");
		}
		$this->db->from('tbl_feedback_kategori');
		$this->db->where('tbl_feedback_kategori.na', 'n');
		$this->db->where('tbl_feedback_kategori.del', 'n');
		if($id_feedback_kategori!=null){
			$this->db->where('tbl_feedback_kategori.id_feedback_kategori', $id_feedback_kategori);
		}
		if($jenis!=null){
			$this->db->where('tbl_feedback_kategori.jenis', $jenis);
		}
		$this->db->order_by('tbl_feedback_kategori.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_feedback_nilai($id_feedback_nilai=NULL, $all=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_feedback_nilai.*');
		$this->db->select('(select count(*) from tbl_feedback_nilai where na=\'n\' and del=\'n\') as rows');
		$this->db->from('tbl_feedback_nilai');
		$this->db->where('tbl_feedback_nilai.na', 'n');
		$this->db->where('tbl_feedback_nilai.del', 'n');
		if($id_feedback_nilai!=null){
			$this->db->where('tbl_feedback_nilai.id_feedback_nilai', $id_feedback_nilai);
		}
		$this->db->order_by('tbl_feedback_nilai.nilai', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_feedback_pertanyaan($id_feedback_pertanyaan=NULL, $all=NULL, $id_karyawan=NULL, $jenis_kategori=NULL, $id_batch=NULL, $id_trainer=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_feedback_pertanyaan.*');
		$this->db->select('tbl_feedback_kategori.nama as nama_kategori');
		if(($id_karyawan!=null)and($id_batch!=null)){
			if($id_trainer!=null){
				$this->db->select("(select tbl_batch_feedback.id_batch from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n' and tbl_batch_feedback.id_trainer ='$id_trainer') as id_batch");
				$this->db->select("(select tbl_batch_feedback.id_feedback_nilai from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n' and tbl_batch_feedback.id_trainer ='$id_trainer') as id_feedback_nilai");
				$this->db->select("(select tbl_batch_feedback.id_karyawan from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n' and tbl_batch_feedback.id_trainer ='$id_trainer') as id_karyawan");
			}else{
				$this->db->select("(select tbl_batch_feedback.id_batch from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n') as id_batch");
				$this->db->select("(select tbl_batch_feedback.id_feedback_nilai from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n') as id_feedback_nilai");
				$this->db->select("(select tbl_batch_feedback.id_karyawan from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n') as id_karyawan");
			}
		}
		if($id_batch!=null){
			$this->db->select("
							COALESCE(		
							  (select avg(tbl_batch_feedback.id_feedback_nilai) 
									from tbl_batch_feedback 
									where 
									tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan and
									tbl_batch_feedback.id_batch='$id_batch' and tbl_batch_feedback.na='n' and tbl_batch_feedback.del='n'
							  ),0
							)as average 
						");
			
		}
		$this->db->from('tbl_feedback_pertanyaan');
		$this->db->join('tbl_feedback_kategori', 'tbl_feedback_kategori.id_feedback_kategori = tbl_feedback_pertanyaan.id_feedback_kategori
										 AND tbl_feedback_kategori.na = \'n\'', 'left');		
		$this->db->where('tbl_feedback_pertanyaan.na', 'n');
		$this->db->where('tbl_feedback_pertanyaan.del', 'n');
		if($id_feedback_pertanyaan!=null){
			$this->db->where('tbl_feedback_pertanyaan.id_feedback_pertanyaan', $id_feedback_pertanyaan);
		}
		if($jenis_kategori!=null){
			$this->db->where('tbl_feedback_kategori.jenis', $jenis_kategori);
		}
		$this->db->order_by('tbl_feedback_pertanyaan.pertanyaan', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_program($id_program=NULL, $all=NULL, $kode = NULL){
		$this->general->connectDbPortal();
		$string = "select p.*, 
					   CASE
							WHEN p.tipe_penyelenggara = 'Internal' THEN 'IN'
							ELSE 'EK'
					   END as kode_penyelenggara,
					   CASE
							WHEN p.jenis = 'Event' THEN 'EV'
							WHEN p.jenis = 'Sharing' THEN 'SH'
							ELSE 'TR'
					   END as kode_jenis,
					   (select  right('00000'+convert(varchar(5), (count(*)+1)), 5) from tbl_program_batch where na='n') as nomor,
					   CASE
							WHEN p.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_program p where 1=1";
		if($id_program!=null){
			$string .= " and p.id_program='$id_program'";	
		}
		if($kode!=null){
			$string .= " and p.kode='$kode'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	function get_data_batch($id_batch=NULL, $all=NULL, $kode=NULL, $peserta=NULL, $date=NULL,$program_in=NULL,$awal=NULL,$akhir=NULL){
		$this->general->connectDbPortal();

		$this->db->select("tbl_batch.*,
							CASE
								WHEN tbl_batch.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
								ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
							END as label_active,
							CASE
								WHEN tbl_batch.online = 'y' THEN '<span class=\"label label-success\">Online</span>'
								ELSE '<span class=\"label label-default\">Offline</span>'
							END as label_online,
							CAST( (SELECT CAST(tbl_batch_nilai.id_nilai as varchar(250)) + RTRIM('|')+CAST(tbl_batch_nilai.bobot as varchar(250)) + RTRIM(', ') FROM tbl_batch_nilai WHERE tbl_batch_nilai.na='n' and tbl_batch_nilai.id_batch=tbl_batch.id_batch order by tbl_batch_nilai.id_nilai asc FOR XML PATH ('')) as VARCHAR(MAX)) AS bobot,
							CAST( (SELECT CAST(tbl_batch_grade.id_grade as varchar(250)) + RTRIM('|')+CAST(tbl_batch_grade.grade_awal as varchar(250)) + RTRIM(', ') FROM tbl_batch_grade WHERE tbl_batch_grade.na='n' and tbl_batch_grade.id_batch=tbl_batch.id_batch FOR XML PATH ('')) as VARCHAR(MAX)) AS grade_awal,
							CAST( (SELECT CAST(tbl_batch_grade.id_grade as varchar(250)) + RTRIM('|')+CAST(tbl_batch_grade.grade_akhir as varchar(250)) + RTRIM(', ') FROM tbl_batch_grade WHERE tbl_batch_grade.na='n' and tbl_batch_grade.id_batch=tbl_batch.id_batch FOR XML PATH ('')) as VARCHAR(MAX)) AS grade_akhir,
							CAST( (SELECT CAST(tbl_soal_jumlah.id_soal_tipe as varchar(250)) + RTRIM('|')+CAST(tbl_soal_jumlah.jumlah_soal as varchar(250)) + RTRIM(', ') FROM tbl_soal_jumlah WHERE tbl_soal_jumlah.na='n' and tbl_soal_jumlah.id_batch=tbl_batch.id_batch FOR XML PATH ('')) as VARCHAR(MAX)) AS jumlah_soal,
							CAST( (SELECT CAST(tbl_topik.nama as varchar(250)) + RTRIM('|')+CAST(tbl_materi.id_materi as varchar(250)) + RTRIM('|')+CAST(tbl_materi.nama as varchar(250)) + RTRIM('|')+CAST(tbl_materi.files as varchar(250)) + RTRIM('|')+CAST(tbl_materi.tipe_files as varchar(250)) + RTRIM('|')+CAST(tbl_materi.size_files as varchar(250)) + RTRIM(', ') FROM tbl_materi left outer join tbl_topik on tbl_topik.id_topik=tbl_materi.id_topik WHERE tbl_materi.na='n' and CHARINDEX(''''+CONVERT(varchar(10), tbl_materi.id_topik)+'''',''''+REPLACE(tbl_tahap.topik, RTRIM(','),''',''')+'''') > 0  FOR XML PATH ('')) as VARCHAR(MAX)) AS materi_list, 
							CAST( 
								( 
								  SELECT 
									CASE
										WHEN tbl_topik_trainer.trainer = 'dalam' THEN (select nama from tbl_karyawan k where k.nik=tbl_topik_trainer.id_trainer)
										ELSE (select nama from tbl_trainer tr where tr.id_trainer=tbl_topik_trainer.id_trainer)
									END
									+ RTRIM('|') +
									CASE
										WHEN tbl_topik_trainer.trainer = 'dalam' THEN 'Internal'
										ELSE 'Eksternal'
									END
									+ RTRIM('|') +
									CAST(tbl_topik_trainer.id_trainer as varchar(250))
									+  RTRIM(',') 
									FROM tbl_topik_trainer 
								   WHERE CHARINDEX(''''+CONVERT(varchar(250), tbl_topik_trainer.id_topik_trainer)+'''',''''+REPLACE(tbl_batch.trainer, RTRIM(','),''',''')+'''') > 0 
								  FOR XML PATH ('') 
								) as VARCHAR(MAX) 
							) as list_trainer,							
							CAST( (SELECT CAST(tbl_topik.nama as varchar(250)) + RTRIM(',') FROM tbl_materi left outer join tbl_topik on tbl_topik.id_topik=tbl_materi.id_topik WHERE tbl_materi.na='n' and CHARINDEX(''''+CONVERT(varchar(10), tbl_materi.id_topik)+'''', ''''+REPLACE(tbl_tahap.topik, RTRIM(', '), ''', ''')+'''') > 0 group by tbl_topik.nama FOR XML PATH ('') ) as VARCHAR(MAX)) AS topik_list,
							(select count(*) from tbl_batch_feedback where tbl_batch_feedback.na='n' and tbl_batch_feedback.id_batch=tbl_batch.id_batch) as jumlah_evaluasi
						   ");
		$this->db->select('tbl_tahap.topik');		
		$this->db->select('tbl_program_batch.id_program_batch');		
		$this->db->select('tbl_program_batch.kode as kode_program_batch');		
		$this->db->select('tbl_program_batch.nama as nama_program_batch');		
		$this->db->select('tbl_program_batch.tanggal_awal as tanggal_awal_program_batch');		
		$this->db->select('tbl_program_batch.tanggal_akhir as tanggal_akhir_program_batch');		
		$this->db->select('tbl_program.id_program');		
		$this->db->select('tbl_program.nama as nama_program');	
		$this->db->select('tbl_tahap.nama as nama_tahap');			
		$this->db->from('tbl_batch');
		$this->db->join('tbl_program_batch', 'tbl_program_batch.id_program_batch = tbl_batch.id_program_batch 
										 AND tbl_program_batch.na = \'n\'', 'left');		
		$this->db->join('tbl_program', 'tbl_program.id_program = tbl_program_batch.id_program 
										 AND tbl_program.na = \'n\'', 'left');		
		$this->db->join('tbl_tahap', 'tbl_tahap.id_tahap = tbl_batch.id_tahap 
										 AND tbl_program.na = \'n\'', 'left');		
		if($id_batch != NULL){
			$this->db->where('tbl_batch.id_batch', $id_batch);
		}
		if($kode != NULL){
			$this->db->where('tbl_program_batch.kode', $kode);
		}
		if($all == NULL){
			$this->db->where('tbl_batch.na', 'n');
		}
		if($peserta != NULL){
			$this->db->where("(tbl_program_batch.peserta like'%".$peserta."%' or tbl_program_batch.peserta_tambahan like'%".$peserta."%')");
		}
		if($date != NULL){
			$this->db->where("tbl_batch.tanggal_awal>='".$date."' or tbl_batch.tanggal_akhir>= '".$date."'");
		}
		if($program_in != NULL){
			if(is_string($program_in)) $program_in = explode(",", $program_in);
			$this->db->where_in('tbl_program_batch.id_program', $program_in);
		}
		// $filter_awal = ($awal==NULL)?date('Y-m-d', strtotime(date('Y-m-d').'-3 months')):$awal;
		// $filter_akhir = ($akhir==NULL)?date('Y-m-d'):$akhir;
		// $this->db->where("tbl_batch.tanggal_awal between '$filter_awal' and '$filter_akhir'");	
		
		if(($awal != NULL)and($akhir != NULL)){
			$this->db->where("tbl_batch.tanggal_awal between '$awal' and '$akhir'");	
		}
		
		$this->db->order_by('tbl_batch.id_batch', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}
	
}
?>