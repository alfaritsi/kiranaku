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

class Dtransaksiklems extends CI_Model{
	function get_data_batch_soal($id_batch=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_batch_soal.*');
		$this->db->select('tbl_soal.soal as nama_soal');
		$this->db->select('tbl_soal.gambar');
		$this->db->select("(select jawaban from tbl_soal_jawaban where tbl_soal_jawaban.id_soal=tbl_soal.id_soal and tbl_soal_jawaban.benar='y') as jawaban_benar");
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR(MAX), ISNULL(tbl_soal_jawaban.jawaban,0))+RTRIM('|')
					            FROM tbl_soal_jawaban
								WHERE tbl_soal_jawaban.id_soal = tbl_soal.id_soal
								ORDER BY NEWID()
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS nama_jawaban_random
						");
		$this->db->from('tbl_batch_soal');
		$this->db->join('tbl_soal', 'tbl_soal.id_soal = tbl_batch_soal.id_soal AND tbl_soal.na = \'n\'', 'left');		
		$this->db->where('tbl_batch_soal.na', 'n');
		$this->db->where('tbl_batch_soal.del', 'n');
		$this->db->where('tbl_batch_soal.soal_ke', '1');
		if($id_batch!=null){
			$this->db->where('tbl_batch_soal.id_batch', $id_batch);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_batch_grade($id_batch_grade=NULL, $id_batch=NULL, $id_grade=NULL, $conn=NULL){
		if ($conn !== NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_batch_grade.*');
		$this->db->from('tbl_batch_grade');
		$this->db->where('tbl_batch_grade.na', 'n');
		$this->db->where('tbl_batch_grade.del', 'n');
		if($id_batch_grade!=null){
			$this->db->where('tbl_batch_grade.id_batch_grade', $id_batch_grade);
		}
		if($id_batch!=null){
			$this->db->where('tbl_batch_grade.id_batch', $id_batch);
		}
		if($id_grade!=null){
			$this->db->where('tbl_batch_grade.id_grade', $id_grade);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_program_grade($id_program_grade=NULL, $id_program_batch=NULL, $id_grade=NULL, $conn=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_program_grade.*');
		$this->db->from('tbl_program_grade');
		$this->db->where('tbl_program_grade.na', 'n');
		$this->db->where('tbl_program_grade.del', 'n');
		if($id_program_grade!=null){
			$this->db->where('tbl_program_grade.id_program_grade', $id_program_grade);
		}
		if($id_program_batch!=null){
			$this->db->where('tbl_program_grade.id_program_batch', $id_program_batch);
		}
		if($id_grade!=null){
			$this->db->where('tbl_program_grade.id_grade', $id_grade);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_topik_trainer($id_trainer=NULL, $all=NULL, $trainer=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_topik_trainer.*');
		$this->db->select("
						CASE
							WHEN tbl_topik_trainer.trainer = 'dalam' THEN (select nama from tbl_karyawan k where k.nik=tbl_topik_trainer.id_trainer)
							ELSE (select nama from tbl_trainer tr where tr.id_trainer=tbl_topik_trainer.id_trainer)
						END
						as nama_trainer,
					    CASE
							WHEN tbl_topik_trainer.trainer = 'dalam' THEN 'Internal'
							ELSE 'Eksternal'
					    END as asal_trainer
		");
		$this->db->from('tbl_topik_trainer');
		$this->db->where('tbl_topik_trainer.na', 'n');
		$this->db->where('tbl_topik_trainer.del', 'n');
		if($id_trainer!=null){
			$this->db->where('tbl_topik_trainer.id_trainer', $id_trainer);
		}
		if($trainer!=null){
			if($trainer=='Internal'){
				$this->db->where('tbl_topik_trainer.trainer', 'dalam');	
			}else{
				$this->db->where('tbl_topik_trainer.trainer', 'luar');
			}
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_karyawan($id_karyawan=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_karyawan.*');
		$this->db->select('tbl_jabatan.nama as nama_jabatan');
		$this->db->select("(select tbl_tandatangan.gambar from tbl_tandatangan where tbl_tandatangan.nik=tbl_karyawan.nik and tbl_tandatangan.na='n') as ttd");
		$this->db->select("(select tbl_tandatangan.posisi_sertifikat from tbl_tandatangan where tbl_tandatangan.nik=tbl_karyawan.nik and tbl_tandatangan.na='n') as posisi_sertifikat");
		$this->db->from('tbl_karyawan');
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan
										 AND tbl_user.na = \'n\'', 'left');		
		$this->db->join('tbl_jabatan', 'tbl_jabatan.id_jabatan = tbl_user.id_jabatan
										 AND tbl_jabatan.na = \'n\'', 'left');		
		
		$this->db->where('tbl_karyawan.na', 'n');
		$this->db->where('tbl_karyawan.del', 'n');
		if($id_karyawan!=null){
			$this->db->where('tbl_karyawan.id_karyawan', $id_karyawan);
		}
		$this->db->order_by('tbl_karyawan.id_karyawan', 'ASC');
		$query = $this->db->get();
		return $query->row();
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
	function get_data_batch_feedback($id_batch_feedback=NULL, $all=NULL, $id_batch=NULL, $id_karyawan=NULL, $id_feedback_pertanyaan=NULL, $id_trainer=NULL, $conn=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_batch_feedback.*');
		$this->db->from('tbl_batch_feedback');
		$this->db->where('tbl_batch_feedback.na', 'n');
		$this->db->where('tbl_batch_feedback.del', 'n');
		if($id_batch!=null){
			$this->db->where('tbl_batch_feedback.id_batch', $id_batch);
		}
		if($id_karyawan!=null){
			$this->db->where('tbl_batch_feedback.id_karyawan', $id_karyawan);
		}
		if($id_feedback_pertanyaan!=null){
			$this->db->where('tbl_batch_feedback.id_feedback_pertanyaan', $id_feedback_pertanyaan);
		}
		if($id_trainer!=null){
			$this->db->where('tbl_batch_feedback.id_trainer', $id_trainer);
		}
		$this->db->order_by('tbl_batch_feedback.id_batch_feedback', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_batch_score($id_batch_score=NULL, $all=NULL, $id_batch=NULL, $id_peserta=NULL, $id_batch_nilai=NULL, $id_karyawan=NULL){
		// $this->general->connectDbPortal();
		$this->db->select('tbl_batch_score.*');
		$this->db->from('tbl_batch_score');
		$this->db->where('tbl_batch_score.na', 'n');
		$this->db->where('tbl_batch_score.del', 'n');
		if($id_batch!=null){
			$this->db->where('tbl_batch_score.id_batch', $id_batch);
		}
		if($id_peserta!=null){
			$this->db->where('tbl_batch_score.id_peserta', $id_peserta);
		}
		if($id_batch_nilai!=null){
			$this->db->where('tbl_batch_score.id_batch_nilai', $id_batch_nilai);
		}
		if($id_karyawan!=null){
			$this->db->where('tbl_batch_score.id_karyawan', $id_karyawan);
		}
		$this->db->order_by('tbl_batch_score.id_batch_score', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_grade($conn=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_grade.*');
		$this->db->from('tbl_grade');
		$this->db->where('tbl_grade.na', 'n');
		$this->db->where('tbl_grade.del', 'n');
		$this->db->order_by('tbl_grade.id_grade', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_nilai($id_nilai=NULL, $all=NULL, $id_batch=NULL, $id_nilai_kategori=NULL, $conn=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_nilai.*');
		if($id_nilai_kategori!=null){
			$this->db->select('(select count(*) from tbl_nilai where id_nilai_kategori='.$id_nilai_kategori.' and na=\'n\' and del=\'n\')*2 as rows');
			$this->db->select('(select bobot from tbl_batch_nilai where tbl_batch_nilai.id_batch='.$id_batch.' and tbl_batch_nilai.id_nilai=tbl_nilai.id_nilai and tbl_batch_nilai.na=\'n\' and tbl_batch_nilai.del=\'n\') as bobot');
			$this->db->select('(select id_batch_nilai from tbl_batch_nilai where tbl_batch_nilai.id_batch='.$id_batch.' and tbl_batch_nilai.id_nilai=tbl_nilai.id_nilai and tbl_batch_nilai.na=\'n\' and tbl_batch_nilai.del=\'n\') as id_batch_nilai');
		}
		$this->db->from('tbl_nilai');
		$this->db->where('tbl_nilai.na', 'n');
		$this->db->where('tbl_nilai.del', 'n');
		if($id_nilai!=null){
			$this->db->where('tbl_nilai.id_nilai', $id_nilai);
		}
		if($id_nilai_kategori!=null){
			$this->db->where('tbl_nilai.id_nilai_kategori', $id_nilai_kategori);
		}
		$this->db->order_by('tbl_nilai.nama', 'ASC');
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
	function get_data_feedback_pertanyaan($id_feedback_pertanyaan=NULL, $all=NULL, $id_karyawan=NULL, $jenis_kategori=NULL, $id_batch=NULL, $kode=NULL, $id_trainer=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_feedback_pertanyaan.*');
		$this->db->select('tbl_feedback_kategori.id_feedback_kategori');
		$this->db->select('tbl_feedback_kategori.nama as nama_kategori');
		if(($id_karyawan!=null)and($id_batch!=null)){
			if($id_trainer!=null){
				$this->db->select("(select tbl_batch_feedback.id_batch from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n' and tbl_batch_feedback.id_trainer ='$id_trainer') as id_batch");
				$this->db->select("(select tbl_batch_feedback.id_feedback_nilai from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n'  and tbl_batch_feedback.id_trainer ='$id_trainer') as id_feedback_nilai");
				$this->db->select("(select tbl_batch_feedback.id_karyawan from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n'  and tbl_batch_feedback.id_trainer ='$id_trainer') as id_karyawan");
			}else{
				$this->db->select("(select tbl_batch_feedback.id_batch from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n') as id_batch");
				$this->db->select("(select tbl_batch_feedback.id_feedback_nilai from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n') as id_feedback_nilai");
				$this->db->select("(select tbl_batch_feedback.id_karyawan from tbl_batch_feedback where tbl_batch_feedback.id_feedback_pertanyaan=tbl_feedback_pertanyaan.id_feedback_pertanyaan AND tbl_batch_feedback.id_karyawan ='$id_karyawan' AND tbl_batch_feedback.id_batch ='$id_batch' AND tbl_batch_feedback.na = 'n') as id_karyawan");
			}
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
		if($kode!=null){
			$this->db->where('tbl_feedback_pertanyaan.kode', $kode);
		}
		$this->db->order_by('tbl_feedback_pertanyaan.pertanyaan', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_soal_cek($id_batch=NULL, $id_soal_tipe=NULL, $topik=NULL){
		$this->general->connectDbPortal();
		$this->db->select("count(tbl_soal.id_soal) as jumlah");
		$this->db->from('tbl_soal');
		$this->db->where("tbl_soal.id_topik in ($topik)");
		$this->db->where('tbl_soal.na', 'n');
		$this->db->where('tbl_soal.del', 'n');
		if($id_soal_tipe!=null){
			$this->db->where('tbl_soal.id_soal_tipe', $id_soal_tipe);
		}
		$query = $this->db->get();
		return $query->row();
	}	
	
	function get_data_soal($id_soal_tipe=NULL, $jumlah_soal=NULL, $id_topik_in=NULL, $conn=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select("tbl_soal.*");
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR, ISNULL(tbl_soal_jawaban.id_soal_jawaban,0))+RTRIM(',')
					            FROM tbl_soal_jawaban
								WHERE tbl_soal_jawaban.id_soal = tbl_soal.id_soal
								ORDER BY NEWID()
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS jawaban_random,
						");
		$this->db->select("(select tbl_soal_jawaban.id_soal_jawaban from tbl_soal_jawaban where tbl_soal_jawaban.id_soal=tbl_soal.id_soal and tbl_soal_jawaban.benar='y' and tbl_soal_jawaban.na='n') as id_jawaban_benar");				
		$this->db->from('tbl_soal');
		$this->db->where('tbl_soal.na', 'n');
		$this->db->where('tbl_soal.del', 'n');
		if($id_soal_tipe!=null){
			$this->db->where('tbl_soal.id_soal_tipe', $id_soal_tipe);
		}
		if($id_topik_in != NULL){
			if(is_string($id_topik_in)) $id_topik_in = explode(",", $id_topik_in);
			$this->db->where_in('tbl_soal.id_topik', $id_topik_in);
		}
	
		$this->db->order_by('NEWID()');
		$this->db->limit($jumlah_soal); 
		$query = $this->db->get();
		return $query->result();
	}	
	function get_data_soal_tipe($conn=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_soal_tipe.*');
		$this->db->from('tbl_soal_tipe');
		$this->db->where('tbl_soal_tipe.na', 'n');
		$this->db->where('tbl_soal_tipe.del', 'n');
		$this->db->order_by('tbl_soal_tipe.id_soal_tipe', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_peserta_batch($id_batch=NULL, $id_karyawan=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_batch.id_batch');
		$this->db->select('tbl_batch.online');
		$this->db->select('tbl_batch.trainer');
		$this->db->select('tbl_peserta.id_peserta');
		$this->db->select('tbl_peserta.alasan');
		$this->db->select('tbl_karyawan.id_karyawan');
		$this->db->select('tbl_karyawan.nama');
		$this->db->select('tbl_divisi.nama as nama_divisi');
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR, ISNULL(tbl_batch_nilai.id_batch_nilai,0))+RTRIM(',')
					            FROM tbl_batch_nilai
								LEFT JOIN tbl_batch_score ON tbl_batch_score.id_batch_nilai=tbl_batch_nilai.id_batch_nilai and tbl_batch_score.id_peserta=tbl_peserta.id_peserta
								WHERE tbl_batch_nilai.id_batch = tbl_batch.id_batch
								ORDER BY tbl_batch_nilai.id_nilai
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_batch_nilai,
						  ");
		$this->db->select("
					       CAST(
					         (SELECT CONVERT(VARCHAR, ISNULL(tbl_batch_score.score,0))+RTRIM(',')
					            FROM tbl_batch_nilai
								LEFT JOIN tbl_batch_score ON tbl_batch_score.id_batch_nilai=tbl_batch_nilai.id_batch_nilai and tbl_batch_score.id_peserta=tbl_peserta.id_peserta
								WHERE tbl_batch_nilai.id_batch = tbl_batch.id_batch
								ORDER BY tbl_batch_nilai.id_nilai
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_score,
						  ");
		// $this->db->select("
						   // (SELECT CONVERT(VARCHAR, ISNULL(tbl_batch_nilai.id_batch_nilai,0))+RTRIM(',')
							  // FROM tbl_batch_nilai
							  // LEFT JOIN tbl_batch_score ON tbl_batch_score.id_batch_nilai=tbl_batch_nilai.id_batch_nilai and tbl_batch_score.id_peserta=tbl_peserta.id_peserta
							 // WHERE tbl_batch_nilai.id_batch = tbl_batch.id_batch
							 // ORDER BY tbl_batch_nilai.id_nilai
							  // FOR XML PATH ('')) as list_batch_nilai
						  // ");
		// $this->db->select("
						   // (SELECT CONVERT(VARCHAR, ISNULL(tbl_batch_score.score,0))+RTRIM(',')
							  // FROM tbl_batch_nilai
							  // LEFT JOIN tbl_batch_score ON tbl_batch_score.id_batch_nilai=tbl_batch_nilai.id_batch_nilai and tbl_batch_score.id_peserta=tbl_peserta.id_peserta
							 // WHERE tbl_batch_nilai.id_batch = tbl_batch.id_batch
							 // ORDER BY tbl_batch_nilai.id_nilai
							  // FOR XML PATH ('')) as list_score
						  // ");
		$this->db->select("
       (SELECT  
          (SUM(tbl_batch_nilai.bobot*tbl_batch_score.score/100))
          FROM tbl_batch_nilai
          LEFT JOIN tbl_batch_score ON tbl_batch_score.id_batch_nilai=tbl_batch_nilai.id_batch_nilai and tbl_batch_score.id_peserta=tbl_peserta.id_peserta
         WHERE tbl_batch_nilai.id_batch = tbl_batch.id_batch
          ) as grand_total
						  ");
		$this->db->select('tbl_divisi.nama as nama_divisi');
		$this->db->from('tbl_batch');
		$this->db->join('tbl_peserta', 'tbl_peserta.id_batch = tbl_batch.id_batch
										 AND tbl_peserta.na = \'n\'', 'inner');		
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_peserta.id_karyawan', 'left');		
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan', 'left');		
		$this->db->join('tbl_divisi', 'tbl_divisi.id_divisi = tbl_user.id_divisi
										 AND tbl_divisi.na = \'n\'', 'left');		
		$this->db->where('tbl_peserta.na', 'n');
		$this->db->where('tbl_peserta.del', 'n');
		if($id_batch!=null){
			$this->db->where('tbl_peserta.id_batch', $id_batch);
		}
		if($id_karyawan!=null){
			$this->db->where('tbl_peserta.id_karyawan', $id_karyawan);
		}
		$this->db->order_by('tbl_peserta.id_karyawan', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_peserta($id_batch=NULL, $id_karyawan=NULL, $id_peserta=NULL, $id_program_batch=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_peserta.*');
		$this->db->select('tbl_karyawan.nama');
		// $this->db->select("CAST( (SELECT CAST(tbl_batch_nilai.id_nilai as varchar(250)) + RTRIM('|')+CAST(tbl_batch_score.score as varchar(250)) + RTRIM(', ') FROM tbl_batch_nilai left outer join tbl_batch_score on tbl_batch_score.id_batch_nilai=tbl_batch_nilai.id_batch_nilai WHERE tbl_batch_score.id_batch='2' and tbl_batch_score.id_peserta=tbl_peserta.id_peserta FOR XML PATH ('')) as VARCHAR(MAX)) AS list_score");
		// $this->db->select("(SELECT CONVERT(VARCHAR, ISNULL(tbl_batch_score.score,0))+RTRIM(',')
							  // FROM tbl_batch_nilai
							  // LEFT JOIN tbl_batch_score ON tbl_batch_score.id_batch_nilai=tbl_batch_nilai.id_batch_nilai
							 // WHERE tbl_batch_nilai.id_batch = tbl_batch.id_batch
							 // ORDER BY tbl_batch_nilai.id_nilai
							  // FOR XML PATH ('')) as list_score");
		$this->db->select('tbl_divisi.nama as nama_divisi');
		$this->db->from('tbl_peserta');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_peserta.id_karyawan
										 AND tbl_karyawan.na = \'n\'', 'left');		
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan
										 AND tbl_user.na = \'n\'', 'left');		
		$this->db->join('tbl_divisi', 'tbl_divisi.id_divisi = tbl_user.id_divisi
										 AND tbl_divisi.na = \'n\'', 'left');		
		$this->db->where('tbl_peserta.na', 'n');
		$this->db->where('tbl_peserta.del', 'n');
		if($id_batch!=null){
			$this->db->where('tbl_peserta.id_batch', $id_batch);
		}
		if($id_karyawan!=null){
			$this->db->where('tbl_peserta.id_karyawan', $id_karyawan);
		}
		if($id_peserta!=null){
			$this->db->where('tbl_peserta.id_peserta', $id_peserta);
		}
		if($id_program_batch!=null){
			$this->db->where('tbl_peserta.id_program_batch', $id_program_batch);
		}
		if($id_program_batch!=null){
			$query = $this->db->get();
			return $query->row();
		}else{
			$this->db->order_by('tbl_peserta.id_karyawan', 'ASC');
			$query = $this->db->get();
			return $query->result();
		}
	}
	function get_data_peserta_cek($id_batch=NULL, $id_karyawan=NULL, $id_peserta=NULL){
		// $this->general->connectDbPortal();
		$this->db->select('tbl_peserta.*');
		$this->db->select('tbl_karyawan.nama');
		// $this->db->select("CAST( (SELECT CAST(tbl_batch_nilai.id_nilai as varchar(250)) + RTRIM('|')+CAST(tbl_batch_score.score as varchar(250)) + RTRIM(', ') FROM tbl_batch_nilai left outer join tbl_batch_score on tbl_batch_score.id_batch_nilai=tbl_batch_nilai.id_batch_nilai WHERE tbl_batch_score.id_batch='2' and tbl_batch_score.id_peserta=tbl_peserta.id_peserta FOR XML PATH ('')) as VARCHAR(MAX)) AS list_score");
		// $this->db->select("(SELECT CONVERT(VARCHAR, ISNULL(tbl_batch_score.score,0))+RTRIM(',')
							  // FROM tbl_batch_nilai
							  // LEFT JOIN tbl_batch_score ON tbl_batch_score.id_batch_nilai=tbl_batch_nilai.id_batch_nilai
							 // WHERE tbl_batch_nilai.id_batch = tbl_batch.id_batch
							 // ORDER BY tbl_batch_nilai.id_nilai
							  // FOR XML PATH ('')) as list_score");
		$this->db->select('tbl_divisi.nama as nama_divisi');
		
		$this->db->from('tbl_peserta');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_peserta.id_karyawan
										 AND tbl_karyawan.na = \'n\'', 'left');		
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan
										 AND tbl_user.na = \'n\'', 'left');		
		$this->db->join('tbl_divisi', 'tbl_divisi.id_divisi = tbl_user.id_divisi
										 AND tbl_divisi.na = \'n\'', 'left');		
		$this->db->where('tbl_peserta.na', 'n');
		$this->db->where('tbl_peserta.del', 'n');
		if($id_batch!=null){
			$this->db->where('tbl_peserta.id_batch', $id_batch);
		}
		if($id_karyawan!=null){
			$this->db->where('tbl_peserta.id_karyawan', $id_karyawan);
		}
		if($id_peserta!=null){
			$this->db->where('tbl_peserta.id_peserta', $id_peserta);
		}
		$this->db->order_by('tbl_peserta.id_karyawan', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_materi($id_materi=NULL, $all=NULL, $date=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_materi.*');
		$this->db->from('tbl_materi');
		$this->db->where('tbl_materi.na', 'n');
		$this->db->where('tbl_materi.del', 'n');
		if($id_materi!=null){
			$this->db->where('tbl_materi.id_materi', $id_materi);
		}
		if($date!=null){
			$this->db->where("convert(date,tbl_materi.tanggal_edit) ='".date('Y-m-d')."'");
		}
		$this->db->order_by('tbl_materi.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_plant($plant=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_wf_master_plant.*');
		$this->db->from('tbl_wf_master_plant');
		$this->db->where('tbl_wf_master_plant.na', 'n');
		$this->db->where('tbl_wf_master_plant.del', 'n');
		if($plant!=null){
			$this->db->where('tbl_wf_master_plant.plant', $plant);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function get_persen_grade($id_batch=NULL, $all=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_nilai.*');
		$this->db->from('tbl_nilai');
		$this->db->join('tbl_batch_nilai', 'tbl_batch_nilai.id_nilai = tbl_nilai.id_nilai 
										 AND tbl_batch_nilai.na = \'n\'', 'left');		
		$this->db->where('tbl_nilai.na', 'n');
		$this->db->where('tbl_nilai.del', 'n');
		if($id_batch!=null){
			$this->db->where('tbl_nilai.id_batch', $id_batch);
		}
		$this->db->order_by('tbl_nilai.nama', 'ASC');
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
	function get_opt_tahap($id_tahap=NULL, $kode_program_batch=NULL){
		$this->db->select('tbl_tahap.id_tahap');
		$this->db->select('tbl_tahap.nama');
		$this->db->from('tbl_tahap');
		$this->db->join('tbl_program_batch', 'tbl_program_batch.id_program = tbl_tahap.id_program AND tbl_program_batch.id_bpo = tbl_tahap.id_bpo
										 AND tbl_program_batch.na = \'n\'', 'left');		
		$this->db->where('tbl_tahap.na', 'n');
		$this->db->where('tbl_tahap.del', 'n');
		if($id_tahap!=null){
			$this->db->where('tbl_tahap.id_tahap', $id_tahap);
		}
		if($kode_program_batch!=null){
			$this->db->where('tbl_program_batch.kode', $kode_program_batch);
		}
		$this->db->order_by('tbl_tahap.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	} 
	
	function get_data_batch($id_batch=NULL, $all=NULL, $kode=NULL, $peserta=NULL, $date=NULL,$program_in=NULL,$awal=NULL,$akhir=NULL,$id_program_batch=NULL,$id_tahap=NULL,$na=NULL){
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
							tbl_program_batch.peserta,
							tbl_program_batch.peserta_tambahan,
							tbl_program_batch.id_bpo,
							tbl_tahap.topik,
						   ");
		$this->db->select('CONVERT(VARCHAR(10), tbl_batch.tanggal_awal, 105) as tanggal_awal_batch');
		$this->db->select('CONVERT(VARCHAR(10), tbl_batch.tanggal_akhir, 105) as tanggal_akhir_batch');
		$this->db->select('CONVERT(VARCHAR(10), tbl_batch.tanggal, 105) as tanggal_test');
		$this->db->select('tbl_program_batch.id_program_batch');		
		$this->db->select('tbl_program_batch.kode as kode_program_batch');		
		$this->db->select('tbl_program_batch.nama as nama_program_batch');		
		$this->db->select('CONVERT(VARCHAR(10), tbl_program_batch.tanggal_awal, 105) as tanggal_awal_program_batch');		
		$this->db->select('CONVERT(VARCHAR(10), tbl_program_batch.tanggal_akhir, 105) as tanggal_akhir_program_batch');		

		$this->db->select('tbl_program.id_program');		
		$this->db->select('tbl_program.nama as nama_program');	
		$this->db->select('tbl_tahap.nama as nama_tahap');
		$this->db->select('tbl_tahap.topik as topik');		
		$this->db->select('(select sum(bobot) from tbl_batch_nilai where tbl_batch_nilai.id_batch=tbl_batch.id_batch) as total_grade');			
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
		if($na != NULL){
			$this->db->where('tbl_batch.na', 'n');
		}
		if($all == NULL){
			$this->db->where('tbl_batch.del', 'n');
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
		if($awal != NULL){
			$this->db->where("tbl_batch.tanggal_awal>='".$awal."' or tbl_batch.tanggal_akhir>= '".$awal."'");
		}
		if($akhir != NULL){
			$this->db->where("tbl_batch.tanggal_awal>='".$akhir."' or tbl_batch.tanggal_akhir>= '".$akhir."'");
		}
		if($id_program_batch != NULL){
			$this->db->where('tbl_batch.id_program_batch', $id_program_batch);
		}
		if($id_tahap != NULL){
			$this->db->where('tbl_batch.id_tahap', $id_tahap);
		}
		
		$this->db->order_by('tbl_batch.id_batch', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}
	function get_nomor_sertifikat($tahun){
		$this->general->connectDbPortal();
		$this->db->select("count(distinct(tbl_peserta.nomor_sertifikat)) as jumlah");
		$this->db->from('tbl_peserta');
		$this->db->like('tbl_peserta.nomor_sertifikat', '/'.$tahun.'/', 'both');
		
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}
	function get_data_program_batch_nomor($id_bpo=NULL, $id_program=NULL){
		$this->general->connectDbPortal();
		$this->db->select("
							tbl_program.abbreviation,
						   CASE
								WHEN tbl_program.tipe_penyelenggara = 'Internal' THEN 'IN'
								ELSE 'EK'
						   END as kode_penyelenggara,
						   CASE
								WHEN tbl_program.jenis = 'Event' THEN 'EV'
								WHEN tbl_program.jenis = 'Sharing' THEN 'SH'
								ELSE 'TR'
						   END as kode_jenis
						");
		$this->db->select("(select  right('00000'+convert(varchar(5), (count(*)+1)), 5) from tbl_program_batch where id_program='$id_program') as nomor");
		$this->db->from('tbl_program');
		if($id_program != NULL){
			$this->db->where('tbl_program.id_program', $id_program);
		}
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
		
	}
	
	function get_data_program_batch($id_program_batch=NULL, $all=NULL, $kode=NULL, $id_program_batch_in=NULL, $peserta_in=NULL, $status=NULL){
		$this->general->connectDbPortal();

		$this->db->select( "tbl_program_batch.*,
							CASE
								WHEN tbl_program_batch.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
								ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
							END as label_active,
							CAST( 
								(SELECT CAST(tbl_program_grade.id_grade as varchar(250)) + RTRIM('|')+CAST(tbl_program_grade.grade_awal as varchar(250)) + RTRIM(', ') FROM tbl_program_grade WHERE tbl_program_grade.na='n' and tbl_program_grade.id_program_batch=tbl_program_batch.id_program_batch FOR XML PATH ('')) as VARCHAR(MAX)
								) AS grade_awal,
							CAST( 
								(SELECT CAST(tbl_program_grade.id_grade as varchar(250)) + RTRIM('|')+CAST(tbl_program_grade.grade_akhir as varchar(250)) + RTRIM(', ') FROM tbl_program_grade WHERE tbl_program_grade.na='n' and tbl_program_grade.id_program_batch=tbl_program_batch.id_program_batch FOR XML PATH ('')) as VARCHAR(MAX)
								) AS grade_akhir,
							CAST( 
							( 
							  SELECT CAST(tbl_karyawan.nik as varchar(250)) + RTRIM('|')+
									tbl_karyawan.nama + RTRIM('|')+
									CASE
										WHEN tbl_karyawan.ho = 'y' THEN 'Head Office' + RTRIM(',')
										ELSE tbl_karyawan.GSBER + RTRIM(',')
									END							  
								FROM tbl_karyawan 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_program_batch.peserta, RTRIM(','),''',''')+'''') > 0 order by tbl_karyawan.nama 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as nama_peserta,
							CAST( 
							( 
							  SELECT 
							   CASE
									WHEN tbl_karyawan.ho = 'y' THEN 'Head Office' + RTRIM(',')
									ELSE tbl_karyawan.GSBER + RTRIM(',')
							   END							  
								FROM tbl_karyawan 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_program_batch.peserta, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as nama_peserta_pabrik,
							CAST( 
							( 
							  SELECT CAST(tbl_karyawan.nik as varchar(250)) + RTRIM('|')+
									tbl_karyawan.nama + RTRIM('|')+
									CASE
										WHEN tbl_karyawan.ho = 'y' THEN 'Head Office' + RTRIM(',')
										ELSE tbl_karyawan.GSBER + RTRIM(',')
									END							  
								FROM tbl_karyawan 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_program_batch.peserta_tambahan, RTRIM(','),''',''')+'''') > 0 order by tbl_karyawan.nama 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as nama_peserta_tambahan,
							CAST( 
							( 
							  SELECT 
							   CASE
									WHEN tbl_karyawan.ho = 'y' THEN 'Head Office' + RTRIM(',')
									ELSE tbl_karyawan.GSBER + RTRIM(',')
							   END							  
								FROM tbl_karyawan 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_program_batch.peserta_tambahan, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as nama_peserta_tambahan_pabrik,
							CAST( 
								(
									SELECT 
									CAST(tbl_batch.id_batch as varchar(250)) 
									+ RTRIM('|')+
									CAST(tbl_tahap.nama as varchar(250)) 
									+ RTRIM('|')+
									CAST((select sum(bobot) from tbl_batch_nilai where tbl_batch_nilai.id_batch=tbl_batch.id_batch) as varchar(250)) 
									+ RTRIM(', ') 
									FROM tbl_batch 
									left outer join tbl_tahap on tbl_batch.id_tahap=tbl_tahap.id_tahap
									WHERE tbl_batch.na='n' and tbl_batch.id_program_batch=tbl_program_batch.id_program_batch FOR XML PATH ('')) as VARCHAR(MAX)
								) AS list_batch
							");
		$this->db->select('tbl_bpo.nama as nama_bpo');
		$this->db->select('tbl_program.nama as nama_program');				   
		$this->db->select('CONVERT(VARCHAR(10), tbl_program_batch.tanggal_awal, 105) as tanggal_awal_program_batch');
		$this->db->select('CONVERT(VARCHAR(10), tbl_program_batch.tanggal_akhir, 105) as tanggal_akhir_program_batch');
		$this->db->select('year(tbl_program_batch.tanggal_awal) as tahun');
		$this->db->select('month(tbl_program_batch.tanggal_awal) as bulan');
		$this->db->select('tbl_program.jenis_sertifikat');
		$this->db->from('tbl_program_batch');
		$this->db->join('tbl_bpo', 'tbl_bpo.id_bpo = tbl_program_batch.id_bpo 
										 AND tbl_bpo.na = \'n\'', 'left');		
		$this->db->join('tbl_program', 'tbl_program.id_program = tbl_program_batch.id_program 
										 AND tbl_program.na = \'n\'', 'left');		
		
		if($id_program_batch != NULL){
			$this->db->where('tbl_program_batch.id_program_batch', $id_program_batch);
		}
		if($kode != NULL){
			$this->db->where('tbl_program_batch.kode', $kode);
		}
		if($all == NULL){
			$this->db->where('tbl_program_batch.na', 'n');
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
		if($status != NULL){
			$this->db->where('tbl_program_batch.status', 'On Progress');
		}
		if($id_program_batch != NULL){
			$this->db->where('tbl_program_batch.id_program_batch', $id_program_batch);
			$query 	= $this->db->get();
			$result	= $query->row();
			
			$this->general->closeDb();
			return $result;
		}
		$this->db->order_by('tbl_program_batch.id_program_batch', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
		
	}
	function get_opt_program($id_program=NULL){
		$this->db->select('tbl_program.id_program');
		$this->db->select('tbl_program.nama');
		$this->db->from('tbl_program');
		$this->db->where('tbl_program.na', 'n');
		$this->db->where('tbl_program.del', 'n');
		if($id_program!=null){
			$this->db->where('tbl_program.id_program', $id_program);
		}
		$this->db->order_by('tbl_program.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_opt_bpo($id_bpo=NULL){
		$this->db->select('tbl_bpo.id_bpo');
		$this->db->select('tbl_bpo.nama');
		$this->db->from('tbl_bpo');
		$this->db->where('tbl_bpo.na', 'n');
		$this->db->where('tbl_bpo.del', 'n');
		if($id_bpo!=null){
			$this->db->where('tbl_bpo.id_bpo', $id_bpo);
		}
		$this->db->order_by('tbl_bpo.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_opt_institusi($id_institusi=NULL){
		$this->db->select('tbl_institusi.*');
		$this->db->from('tbl_institusi');
		$this->db->where('tbl_institusi.na', 'n');
		$this->db->where('tbl_institusi.del', 'n');
		if($id_institusi!=null){
			$this->db->where('tbl_institusi.id_institusi', $id_institusi);
		}
		$this->db->order_by('tbl_institusi.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_opt_pabrik($plant=NULL){
		$this->db->select('tbl_wf_master_plant.plant');
		$this->db->select('tbl_wf_master_plant.plant_name');
		$this->db->from('tbl_wf_master_plant');
		$this->db->where('tbl_wf_master_plant.na', 'n');
		$this->db->where('tbl_wf_master_plant.del', 'n');
		if($plant!=null){
			$this->db->where('tbl_wf_master_plant.plant', $plant);
		}
		$this->db->order_by('tbl_wf_master_plant.plant', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_opt_topik_trainer($id_batch=NULL){
		$this->db->select('tt1.*');
		$this->db->select('tbl_topik.nama as nama_topik');
		$this->db->select('
					   CASE
							WHEN (select trainer from tbl_topik_trainer tt2 where tt2.id_topik_trainer=tt1.id_topik_trainer) = \'dalam\' 
							THEN (select nama from tbl_karyawan k where k.nik=tt1.id_trainer)
							ELSE (select nama from tbl_trainer tr where tr.id_trainer=tt1.id_trainer)
					   END as nama
					');
		$this->db->select('
					   CASE
							WHEN (select trainer from tbl_topik_trainer tt2 where tt2.id_topik_trainer=tt1.id_topik_trainer) = \'dalam\' 
							THEN \'Internal\'
							ELSE \'Eksternal\'
					   END as caption
					');
		$this->db->from('tbl_topik_trainer tt1');
		$this->db->join('tbl_topik', 'tbl_topik.id_topik = tt1.id_topik', 'left outer');
		$this->db->where('tt1.na', 'n');
		if($id_batch!=null){
			$this->db->where('tt1.plant', $plant);
		}
		$this->db->order_by('tt1.id_topik_trainer', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_user_program($user=NULL, $program=NULL, $pabrik_in=NULL, $peserta_tambahan=NULL, $peserta_in=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_karyawan.nik as id');
		$this->db->select('tbl_user.*');
		$this->db->select('tbl_karyawan.*');
		$this->db->from('tbl_user');
		// $this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan
										 // AND tbl_karyawan.na = \'n\' 
										 // AND tbl_karyawan.del = \'n\'', 'inner');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan
										 ', 'inner');
		$this->db->join('tbl_posisi', 'tbl_posisi.nama = tbl_karyawan.posst 
									 AND tbl_posisi.na = \'n\'', 'inner');
		if(($user !== NULL)and($peserta_tambahan=='n')){
			$this->db->like('tbl_karyawan.nama', $user, 'after');		
		}
		if(($pabrik_in != NULL)and($peserta_tambahan=='n')){
			if (in_array("KMTR", $pabrik_in)){
				$this->db->where('(tbl_karyawan.ho = \'y\' OR tbl_karyawan.gsber IN (\''.implode("','",$pabrik_in).'\'))');
			}else{
				$this->db->where_in('tbl_karyawan.gsber', $pabrik_in);
			}			
		}
		if(($user !== NULL)and($peserta_tambahan=='y')){
			$this->db->like('tbl_karyawan.nama', $user, 'after');		
		}
		if(($pabrik_in != NULL)and($peserta_tambahan=='y')and($peserta_in!=NULL)){
			$this->db->where_not_in('tbl_karyawan.nik', $peserta_in);
		}
		// $this->db->where('tbl_user.na', 'n');
		// $this->db->where('tbl_user.del', 'n');
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query 	= $this->db->get();
		$result = $query->result();

		$this->general->closeDb();
		return $result;
	}
	function get_opt_ttd($nik=NULL){
		$this->db->select('tbl_tandatangan.nik');
		$this->db->select('tbl_karyawan.nama');
		$this->db->from('tbl_tandatangan');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_tandatangan.nik
										 AND tbl_karyawan.na = \'n\'', 'left');		
		$this->db->where('tbl_tandatangan.na', 'n');
		$this->db->where('tbl_tandatangan.del', 'n');
		if($nik!=null){
			$this->db->where('tbl_tandatangan.nik', $nik);
		}
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_cek_nilai($id_karyawan=NULL, $id_program_batch=NULL){
		$this->general->connectDbPortal();
		$this->db->select("
							  (select 
									top 1 tbl_grade.kode 
									from tbl_program_grade 
									left outer join tbl_grade on tbl_grade.id_grade=tbl_program_grade.id_grade 
									where 1=1
									and 
									(
										  (select 
											CAST(ROUND(avg(tbl_batch_score.score) , 2) as numeric(36,2))
											from tbl_batch_score 
											left join tbl_batch_nilai on tbl_batch_nilai.id_batch_nilai=tbl_batch_score.id_batch_nilai
											where
											tbl_batch_score.na='n' 
											and tbl_batch_score.id_batch in (select tbl_batch2.id_batch from tbl_batch tbl_batch2 where tbl_batch2.id_program_batch=tbl_program_batch.id_program_batch)
											and tbl_batch_score.id_peserta in (select tbl_peserta2.id_peserta from tbl_peserta tbl_peserta2 where tbl_peserta2.id_karyawan=tbl_karyawan.id_karyawan and tbl_peserta2.id_batch in(select tbl_batch3.id_batch from tbl_batch tbl_batch3 where tbl_batch3.id_program_batch=tbl_program_batch.id_program_batch))
										  )
										  >=tbl_program_grade.grade_awal 
										and 
										tbl_program_grade.grade_akhir>=
										  (select 
											CAST(ROUND(avg(tbl_batch_score.score) , 2) as numeric(36,2))
											from tbl_batch_score 
											left join tbl_batch_nilai on tbl_batch_nilai.id_batch_nilai=tbl_batch_score.id_batch_nilai
											where
											tbl_batch_score.na='n' 
											and tbl_batch_score.id_batch in (select tbl_batch2.id_batch from tbl_batch tbl_batch2 where tbl_batch2.id_program_batch=tbl_program_batch.id_program_batch)
											and tbl_batch_score.id_peserta in (select tbl_peserta2.id_peserta from tbl_peserta tbl_peserta2 where tbl_peserta2.id_karyawan=tbl_karyawan.id_karyawan and tbl_peserta2.id_batch in(select tbl_batch3.id_batch from tbl_batch tbl_batch3 where tbl_batch3.id_program_batch=tbl_program_batch.id_program_batch))
										  )
									)
								) as grade
						");
		
		$this->db->from('tbl_peserta');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_peserta.id_karyawan
										 AND tbl_karyawan.na = \'n\'', 'left');		
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan
										 AND tbl_user.na = \'n\'', 'left');		
		$this->db->join('tbl_batch', 'tbl_batch.id_batch = tbl_peserta.id_batch
										 AND tbl_batch.na = \'n\'', 'left');	
		$this->db->join('tbl_program_batch', 'tbl_program_batch.id_program_batch = tbl_batch.id_program_batch 
										 AND tbl_program_batch.na = \'n\'', 'left');		
		$this->db->join('tbl_program', 'tbl_program.id_program = tbl_program_batch.id_program 
										 AND tbl_program.na = \'n\'', 'left');		
		$this->db->join('tbl_tahap', 'tbl_tahap.id_tahap = tbl_batch.id_tahap 
										 AND tbl_program.na = \'n\'', 'left');		
		$this->db->where('tbl_peserta.na', 'n');
		$this->db->where('tbl_peserta.del', 'n');
		
		if($id_karyawan != NULL){
			$this->db->where('tbl_peserta.id_karyawan', $id_karyawan);
		}
		if($id_program_batch != NULL){
			$this->db->where('tbl_peserta.id_program_batch', $id_program_batch);
		}
		
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	
}
?>