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

class Dmasterklems extends CI_Model{
	function get_data_soal($conn=NULL, $id_soal=NULL, $all=NULL, $soal=NULL, $id_soal_in=NULL, $kode=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_soal.*, 
								CASE WHEN tbl_soal.na = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\' ELSE \'<span class="label label-danger">NOT ACTIVE</span>\' END as label_active,
							    CAST(
									(SELECT tbl_soal_jawaban.jawaban + RTRIM(\';\')
									FROM tbl_soal_jawaban
									WHERE tbl_soal_jawaban.id_soal = tbl_soal.id_soal
									FOR XML PATH (\'\')) as VARCHAR(MAX) 
								) as jawaban,
								CAST(
									(SELECT tbl_soal_jawaban.nama + RTRIM(\';\')
									FROM tbl_soal_jawaban
									WHERE tbl_soal_jawaban.id_soal = tbl_soal.id_soal
									FOR XML PATH (\'\')) as VARCHAR(MAX)
								) as nama_jawaban       
						  ');
		$this->db->select('tbl_soal_tipe.nama as tipe_soal');
		$this->db->select('tbl_bpo.nama as nama_bpo');
		$this->db->select('tbl_topik.nama as nama_topik');
		$this->db->from('tbl_soal');
		$this->db->join('tbl_soal_tipe', 'tbl_soal_tipe.id_soal_tipe = tbl_soal.id_soal_tipe 
										 AND tbl_soal_tipe.na = \'n\'', 'left');		
		$this->db->join('tbl_bpo', 'tbl_bpo.id_bpo = tbl_soal.id_bpo 
										 AND tbl_bpo.na = \'n\'', 'left');		
		$this->db->join('tbl_topik', 'tbl_topik.id_topik = tbl_soal.id_topik 
										 AND tbl_topik.na = \'n\'', 'left');		
		if($id_soal!=null){
			$this->db->where('tbl_soal.id_soal', $id_soal);
		}
		if($kode!=null){
			$this->db->where('tbl_soal.kode', $kode);
		}
		$query 	= $this->db->get();
		if($kode==null){
			$result	= $query->result();
		}else{
			$result	= $query->row();
		}
		$this->general->closeDb();
		return $result;
		
	}
	function get_data_soal_tipe($conn=NULL, $id_soal_tipe=NULL, $all=NULL, $nama=NULL, $id_soal_tipe_in=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_soal_tipe.*,
						   CASE
						   		WHEN tbl_soal_tipe.na = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active
						   ');
		$this->db->from('tbl_soal_tipe');
		if($id_soal_tipe != NULL){
			$this->db->where('tbl_soal_tipe.id_soal_tipe', $id_soal_tipe);
		}
		if($id_soal_tipe_in != NULL){
			$this->db->where_in('tbl_soal_tipe.id_soal_tipe', $id_soal_tipe_in);
		}
		if($nama != NULL && trim($nama) !== ""){
			$this->db->where('tbl_soal_tipe.nama', $nama);
		}
		if($all == NULL){
			$this->db->where('tbl_soal_tipe.na', 'n');
		}
		$this->db->order_by('tbl_soal_tipe.id_soal_tipe', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}
	function get_data_tahap($conn=NULL, $id_tahap=NULL, $all=NULL, $id_bpo=NULL, $id_program=NULL, $nama=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select t.*, b.nama as nama_bpo, p.nama as nama_program,
						CAST(
						(
							SELECT tbl_topik.nama + RTRIM(',')
							FROM tbl_topik
							WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_topik.id_topik)+'''', ''''+REPLACE(t.[topik],',',''',''')+'''') > 0
							FOR XML PATH ('')
							) as VARCHAR(MAX)
						) as topik_list,
					   CASE
							WHEN t.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_tahap t 
					left outer join tbl_bpo b on b.id_bpo=t.id_bpo 
					left outer join tbl_program p on p.id_program=t.id_program 
					where 1=1";
		if($id_tahap!=null){
			$string .= " and t.id_tahap='$id_tahap'";	
		}
		if($id_bpo!=null){
			$string .= " and t.id_bpo='$id_bpo'";	
		}
		if($id_program!=null){
			$string .= " and t.id_program='$id_program'";	
		}
		if($nama!=null){
			$string .= " and t.nama='$nama'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}

	function get_opt_trainer($id_trainer=NULL){
		$this->db->select('tbl_trainer.id_trainer');
		$this->db->select('tbl_trainer.nama');
		$this->db->from('tbl_trainer');
		$this->db->where('tbl_trainer.na', 'n');
		$this->db->where('tbl_trainer.del', 'n');
		if($id_trainer!=null){
			$this->db->where('tbl_trainer.id_trainer', $id_trainer);
		}
		$this->db->order_by('tbl_trainer.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_opt_karyawan($id_trainer=NULL){
		$this->db->select('tbl_karyawan.nik');
		$this->db->select('tbl_karyawan.nama');
		$this->db->select("
					   CASE
							WHEN tbl_karyawan.ho = 'y' THEN 'Head Office'
							ELSE tbl_karyawan.GSBER
					   END as nama_pabrik
		");
		$this->db->from('tbl_karyawan');
		// $this->db->where('tbl_karyawan.ho', 'y');
		$this->db->where('tbl_karyawan.del', 'n');
		$this->db->where('tbl_karyawan.del', 'n');
		if($id_trainer!=null){
			$this->db->where('tbl_karyawan.nik', $id_trainer);
		}
		$this->db->order_by('tbl_karyawan.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_signature($conn=NULL, $id_tandatangan=NULL, $all=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select t.*, k.nama, k.posst,
					   CASE
							WHEN t.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_tandatangan t left outer join tbl_karyawan k on k.nik=t.nik where 1=1";
		if($id_tandatangan!=null){
			$string .= " and t.id_tandatangan='$id_tandatangan'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
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
	function get_data_topik($conn=NULL, $id_topik=NULL, $all=NULL, $kode = NULL, $nama = NULL, $id_bpo = NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select t.*, b.nama as nama_bpo, 
					   CASE
							WHEN t.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active,
					   CAST(
						 (SELECT 
							CASE
								WHEN tbl_topik_trainer.trainer = 'dalam' THEN (select nama from tbl_karyawan k where k.nik=tbl_topik_trainer.id_trainer)
								ELSE (select nama from tbl_trainer tr where tr.id_trainer=tbl_topik_trainer.id_trainer)
							END
							+RTRIM('|')+
							CASE
								WHEN tbl_topik_trainer.trainer = 'dalam' THEN 'Internal'
								ELSE 'Eksternal'
							END
							+RTRIM(',')
							FROM tbl_topik_trainer
							WHERE tbl_topik_trainer.id_topik = t.id_topik and tbl_topik_trainer.na='n'
							ORDER BY tbl_topik_trainer.nama
						  FOR XML PATH ('')) as VARCHAR(MAX)
					   )  AS list_trainer,
					   CAST(
						 (SELECT 
							tbl_materi.nama
							+RTRIM('|')+
							tbl_materi.tipe_files
							+RTRIM(',')
							FROM tbl_materi
							WHERE tbl_materi.id_topik = t.id_topik and tbl_materi.na='n'
							ORDER BY tbl_materi.nama
						  FOR XML PATH ('')) as VARCHAR(MAX)
					   )  AS list_materi
					from tbl_topik t left outer join tbl_bpo b on b.id_bpo=t.id_bpo where 1=1";
		if($id_topik!==null){
			$string .= " and t.id_topik='$id_topik'";	
		}
		if($kode!==null){
			$string .= " and t.kode='$kode'";	
		}
		if($nama!==null){
			$string .= " and t.nama='$nama'";	
		}
		if($id_bpo!==null){
			$string .= " and t.id_bpo='$id_bpo'";	
		}
		
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	function get_data_topik_trainer($conn=NULL, $kode_topik=NULL, $all=NULL, $id_topik_trainer=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select tt1.*, t.kode as kode_topik, t.nama as nama_topik, 
					   CASE
							WHEN (select trainer from tbl_topik_trainer tt2 where tt2.id_topik_trainer=tt1.id_topik_trainer) = 'dalam' THEN (select nama from tbl_karyawan k where k.nik=tt1.id_trainer)
							ELSE (select nama from tbl_trainer tr where tr.id_trainer=tt1.id_trainer)
					   END as nama_trainer,
					   CASE
							WHEN tt1.trainer = 'dalam' THEN 'Internal'
							ELSE 'Eksternal'
					   END as asal_trainer,
					   CASE
							WHEN tt1.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_topik_trainer tt1 
					left outer join tbl_topik t on t.id_topik=tt1.id_topik 
					where 1=1";
		if($id_topik_trainer!=null){
			$string .= " and tt1.id_topik_trainer='$id_topik_trainer'";	
		}
		if($kode_topik!=null){
			$string .= " and t.kode='$kode_topik'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	function get_data_topik_materi($conn=NULL, $kode_topik=NULL, $all=NULL, $id_materi=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select m.*, t.kode as kode_topik, t.nama as nama_topik, 
					   CASE
							WHEN m.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_materi m 
					left outer join tbl_topik t on t.id_topik=m.id_topik 
					where 1=1";
		if($id_materi!=null){
			$string .= " and m.id_materi='$id_materi'";	
		}
		if($kode_topik!=null){
			$string .= " and t.kode='$kode_topik'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	
	function get_opt_sertifikat($id_sertifikat=NULL){
		$this->db->select('tbl_sertifikat.id_sertifikat');
		$this->db->select('tbl_sertifikat.nama');
		$this->db->from('tbl_sertifikat');
		$this->db->where('tbl_sertifikat.na', 'n');
		$this->db->where('tbl_sertifikat.del', 'n');
		if($id_sertifikat!=null){
			$this->db->where('tbl_sertifikat.id_sertifikat', $id_sertifikat);
		}
		$this->db->order_by('tbl_sertifikat.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_program($conn=NULL, $id_program=NULL, $all=NULL, $kode = NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select p.*, 
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
	function get_data_program_budget($conn=NULL, $kode_program=NULL, $all=NULL, $id_program_budget=NULL, $id_program=NULL, $tahun=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select b.*, p.kode as kode_program, p.nama as nama_program, 
						PARSENAME(CONVERT(varchar, CAST(b.budget_training AS money), 1), 2) as budget_training_cur, 
						PARSENAME(CONVERT(varchar, CAST(b.budget_traveling AS money), 1), 2) as budget_traveling_cur, 
						CASE
							WHEN b.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
						END as label_active
					from tbl_program_budget b 
					left outer join tbl_program p on p.id_program=b.id_program 
					where 1=1";
		if($id_program_budget!=null){
			$string .= " and b.id_program_budget='$id_program_budget'";	
		}
		if($id_program!=null){
			$string .= " and b.id_program='$id_program'";	
		}
		if($tahun!=null){
			$string .= " and b.tahun='$tahun'";	
		}
		if($kode_program!=null){
			$string .= " and p.kode='$kode_program'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	function get_data_program_matrix($conn=NULL, $kode_program=NULL, $all=NULL, $id_program_matrix=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "
			SELECT m.*, p.jenis as jenis_program, p.kode as kode_program, p.nama as nama_program,
				CAST(
				(
					SELECT tbl_jabatan.nama + RTRIM(',')
					FROM tbl_jabatan
					WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_jabatan.id_jabatan)+'''', ''''+REPLACE(m.[level],',',''',''')+'''') > 0
					FOR XML PATH ('')
					) as VARCHAR(MAX)
				) as level_list,
				CAST(
				(
					SELECT tbl_level.nama  + RTRIM(',')
					FROM tbl_level
					WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_level.id_level)+'''', ''''+REPLACE(m.organisasi_level,',',''',''')+'''') > 0
					FOR XML PATH ('')
					) as VARCHAR(MAX)
				) as organisasi_level_list,
				CAST(
				(
					SELECT tbl_posisi.nama + RTRIM(',')
					FROM tbl_posisi
					WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_posisi.id_posisi)+'''', ''''+REPLACE(m.[posisi],',',''',''')+'''') > 0
          FOR XML PATH ('')
					) as VARCHAR(MAX)
				) as posisi_list,
				CASE
					WHEN m.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
					ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
				END as label_active
			FROM tbl_program_matrix m
			left outer join tbl_program p on p.id_program=m.id_program
			where 1=1		
		";			
		if($id_program_matrix!=null){
			$string .= " and m.id_program_matrix='$id_program_matrix'";	
		}
		if($kode_program!=null){
			$string .= " and p.kode='$kode_program'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	function get_data_level_nama($id=NULL, $sts=NULL){
		$this->db->select("*");
		$this->db->from('tbl_jabatan');
		if($id !== NULL){
			$this->db->where('id_jabatan', $id);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	
	function get_opt_jabatan($id_jabatan=NULL){
		$this->db->select('tbl_jabatan.id_jabatan');
		$this->db->select('tbl_jabatan.nama');
		$this->db->from('tbl_jabatan');
		$this->db->where('tbl_jabatan.na', 'n');
		$this->db->where('tbl_jabatan.del', 'n');
		$this->db->where("tbl_jabatan.nama!='General Description'");
		if($id_jabatan!=null){
			$this->db->where('tbl_jabatan.id_jabatan', $id_jabatan);
		}
		$this->db->order_by('tbl_jabatan.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_opt_level($id_level=NULL){
		$this->db->select('tbl_level.id_level');
		$this->db->select('tbl_level.nama');
		$this->db->from('tbl_level');
		$this->db->where('tbl_level.id_level not in(1,2,3)');
		$this->db->where('tbl_level.na', 'n');
		$this->db->where('tbl_level.del', 'n');
		if($id_level!=null){
			$this->db->where('tbl_level.id_level', $id_level);
		}
		$this->db->order_by('tbl_level.id_level', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_posisi($conn=NULL, $nama=NULL,$jabatan_in=NULL,$level_in=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_posisi.id_posisi as id');
		$this->db->select('tbl_posisi.id_posisi');
		$this->db->select('tbl_posisi.nama');
		$this->db->from('tbl_karyawan');
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan
										 AND tbl_karyawan.na = \'n\' 
										 AND tbl_karyawan.del = \'n\'', 'inner');
		$this->db->join('tbl_posisi', 'tbl_posisi.nama = tbl_karyawan.posst
										 AND tbl_posisi.na = \'n\' 
										 AND tbl_posisi.del = \'n\'', 'inner');
		$this->db->where('tbl_karyawan.del', 'n');
		$this->db->where('tbl_karyawan.del', 'n');
		if($nama !== NULL){
			$this->db->like('tbl_karyawan.posst', $nama, 'both');		
		}
		if($jabatan_in != NULL){
			$this->db->where_in('tbl_user.id_jabatan', $jabatan_in);
		}
		if($level_in != NULL){
			$this->db->where_in('tbl_user.id_level', $level_in);
		}
		$this->db->group_by(array(
									'tbl_posisi.id_posisi',
									'tbl_posisi.nama'
								)
							);		
		$this->db->order_by('tbl_posisi.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_posisi_($nama=NULL,$jabatan_in=NULL,$level_in=NULL){
		$this->general->connectDbPortal();
		$this->db->select('tbl_posisi.id_posisi');
		$this->db->select('tbl_posisi.nama');
		$this->db->from('tbl_posisi');
		$this->db->where('tbl_posisi.del', 'n');
		$this->db->where('tbl_posisi.del', 'n');
		if($nama !== NULL){
			$this->db->like('tbl_posisi.nama', $nama, 'after');		
		}
		$this->db->order_by('tbl_posisi.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_opt_topik($id_topik=NULL){
		$this->db->select('tbl_topik.id_topik');
		$this->db->select('tbl_topik.nama');
		$this->db->from('tbl_topik');
		$this->db->where('tbl_topik.na', 'n');
		$this->db->where('tbl_topik.del', 'n');
		if($id_topik!=null){
			$this->db->where('tbl_topik.id_topik', $id_topik);
		}
		$this->db->order_by('tbl_topik.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_opt_soal_tipe($id_soal_tipe=NULL){
		$this->db->select('tbl_soal_tipe.id_soal_tipe');
		$this->db->select('tbl_soal_tipe.nama');
		$this->db->from('tbl_soal_tipe');
		$this->db->where('tbl_soal_tipe.na', 'n');
		$this->db->where('tbl_soal_tipe.del', 'n');
		if($id_soal_tipe!=null){
			$this->db->where('tbl_soal_tipe.id_soal_tipe', $id_soal_tipe);
		}
		$this->db->order_by('tbl_soal_tipe.kode', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_opt_posisi($id_posisi=NULL, $all=NULL, $jabatan_in=NULL, $level_in=NULL){
		$this->db->select('tbl_posisi.id_posisi');
		$this->db->select('tbl_posisi.nama');
		$this->db->from('tbl_posisi');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.posisi = tbl_posisi.nama
										 AND tbl_karyawan.na = \'n\'', 'left');		
		$this->db->join('tbl_user', 'tbl_user.id_karyawan = tbl_karyawan.id_karyawan
										 AND tbl_user.na = \'n\'', 'left');		
		$this->db->where('tbl_posisi.del', 'n');
		$this->db->where('tbl_posisi.del', 'n');
		if($id_posisi!=null){
			$this->db->where('tbl_posisi.id_posisi', $id_posisi);
		}
		if($jabatan_in != NULL){
			$this->db->where_in('tbl_user.id_jabatan', $jabatan_in);
		}
		if($level_in != NULL){
			$this->db->where_in('tbl_user.id_level', $level_in);
		}
		$this->db->order_by('tbl_posisi.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_opt_kategori($id_feedback_kategori=NULL){
		$this->db->select('tbl_feedback_kategori.id_feedback_kategori');
		$this->db->select('tbl_feedback_kategori.nama');
		$this->db->from('tbl_feedback_kategori');
		$this->db->where('tbl_feedback_kategori.na', 'n');
		$this->db->where('tbl_feedback_kategori.del', 'n');
		if($id_feedback_kategori!=null){
			$this->db->where('tbl_feedback_kategori.id_feedback_kategori', $id_feedback_kategori);
		}
		$this->db->order_by('tbl_feedback_kategori.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_evitem($conn=NULL, $id_feedback_pertanyaan=NULL, $all=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select fp.*, fk.nama as nama_kategori, 
					   CASE
							WHEN fp.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_feedback_pertanyaan fp left outer join tbl_feedback_kategori fk on fk.id_feedback_kategori=fp.id_feedback_kategori where 1=1";
		if($id_feedback_pertanyaan!=null){
			$string .= " and fp.id_feedback_pertanyaan='$id_feedback_pertanyaan'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	
	function get_data_nil_kategori($conn=NULL, $id_nilai_kategori=NULL, $all=NULL, $nama=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_nilai_kategori.*');
		$this->db->select("
					   CASE
							WHEN tbl_nilai_kategori.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
		");
		$this->db->from('tbl_nilai_kategori');
		$this->db->where('tbl_nilai_kategori.del', 'n');
		if($id_nilai_kategori!=null){
			$this->db->where('tbl_nilai_kategori.id_nilai_kategori', $id_nilai_kategori);
		}
		if($nama!=null){
			$this->db->where('tbl_nilai_kategori.nama', $nama);
		}
		$query = $this->db->get();
		return $query->result();
	}
	function get_data_nil_nilai($conn=NULL, $id_nilai=NULL, $all=NULL, $id_nilai_kategori=NULL, $nama=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$this->db->select('tbl_nilai.*');
		$this->db->select('tbl_nilai_kategori.nama as nama_kategori');
		$this->db->select("
					   CASE
							WHEN tbl_nilai.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
		");
		$this->db->from('tbl_nilai');
		$this->db->join('tbl_nilai_kategori', 'tbl_nilai_kategori.id_nilai_kategori = tbl_nilai.id_nilai_kategori
										 AND tbl_nilai_kategori.na = \'n\'', 'left');		
		
		$this->db->where('tbl_nilai.del', 'n');
		if($id_nilai!=null){
			$this->db->where('tbl_nilai.id_nilai', $id_nilai);
		}
		if($id_nilai_kategori!=null){
			$this->db->where('tbl_nilai.id_nilai_kategori', $id_nilai_kategori);
		}
		if($nama!=null){
			$this->db->where('tbl_nilai.nama', $nama);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_evkategori($conn=NULL, $id_feedback_kategori=NULL, $all=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select fk.*, 
					   CASE
							WHEN fk.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_feedback_kategori fk where 1=1";
		if($id_feedback_kategori!=null){
			$string .= " and fk.id_feedback_kategori='$id_feedback_kategori'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	function get_data_evskala($conn=NULL, $id_feedback_nilai=NULL, $all=NULL, $nilai=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();		
		$string = "select fn.*, 
					   CASE
							WHEN fn.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_feedback_nilai fn where 1=1";
		if($id_feedback_nilai!=null){
			$string .= " and fn.id_feedback_nilai='$id_feedback_nilai'";	
		}
		if($nilai!=null){
			$string .= " and fn.nilai='$nilai'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	function get_data_evindex($conn=NULL, $id_feedback_index=NULL, $all=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select fi.*, 
					   CASE
							WHEN fi.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_feedback_index fi where 1=1";
		if($id_feedback_index!=null){
			$string .= " and fi.id_feedback_index='$id_feedback_index'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	function get_master_institusi($id_institusi=NULL){
		$this->db->select('tbl_institusi.id_institusi');
		$this->db->select('tbl_institusi.nama');
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
	function get_data_trainer($conn=NULL, $id_trainer=NULL, $all=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select t.*, i.nama as institusi, 
					   CASE
							WHEN t.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_trainer t left outer join tbl_institusi i on i.id_institusi=t.id_institusi where 1=1";
		if($id_trainer!=null){
			$string .= " and t.id_trainer='$id_trainer'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}
	function get_master_spesialis($id_spesialis=NULL){
		$this->db->select('tbl_spesialis.id_spesialis');
		$this->db->select('tbl_spesialis.nama');
		$this->db->from('tbl_spesialis');
		$this->db->where('tbl_spesialis.na', 'n');
		$this->db->where('tbl_spesialis.del', 'n');
		if($id_spesialis!=null){
			$this->db->where('tbl_spesialis.id_spesialis', $id_spesialis);
		}
		$this->db->order_by('tbl_spesialis.nama', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_institusi($conn=NULL, $id_institusi=NULL, $all=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();
		$string = "select i.*, s.nama as nama_spesialis, 
					   CASE
							WHEN i.na = 'n' THEN '<span class=\"label label-success\">ACTIVE</span>'
							ELSE '<span class=\"label label-danger\">NOT ACTIVE</span>'
					   END as label_active
					from tbl_institusi i left outer join tbl_spesialis s on s.id_spesialis=i.id_spesialis where 1=1";		   
		if($id_institusi!=null){
			$string .= " and i.id_institusi='$id_institusi'";	
		}
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}
	function get_data_spesialis($conn=NULL, $id_spesialis=NULL, $all=NULL, $nama=NULL, $id_spesialis_in=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();

		$this->db->select('tbl_spesialis.*,
						   CASE
						   		WHEN tbl_spesialis.na = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_spesialis');
		if($id_spesialis != NULL){
			$this->db->where('tbl_spesialis.id_spesialis', $id_spesialis);
		}
		if($id_spesialis_in != NULL){
			$this->db->where_in('tbl_spesialis.id_spesialis', $id_spesialis_in);
		}
		if($nama != NULL && trim($nama) !== ""){
			$this->db->where('tbl_spesialis.nama', $nama);
		}
		if($all == NULL){
			$this->db->where('tbl_spesialis.na', 'n');
		}
		$this->db->order_by('tbl_spesialis.nama', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}
	function get_data_bpo($conn=NULL, $id_bpo=NULL, $all=NULL, $nama=NULL, $id_bpo_in=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();

		$this->db->select('tbl_bpo.*,
						   CASE
						   		WHEN tbl_bpo.na = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_bpo');
		if($id_bpo != NULL){
			$this->db->where('tbl_bpo.id_bpo', $id_bpo);
		}
		if($id_bpo_in != NULL){
			$this->db->where_in('tbl_bpo.id_bpo', $id_bpo_in);
		}
		if($nama != NULL && trim($nama) !== ""){
			$this->db->where('tbl_bpo.nama', $nama);
		}
		if($all == NULL){
			$this->db->where('tbl_bpo.na', 'n');
		}
		$this->db->order_by('tbl_bpo.id_bpo', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}
	function get_data_grade($conn=NULL, $id_grade=NULL, $all=NULL){
		if($conn!==NULL)
		$this->general->connectDbPortal();

		$this->db->select('tbl_grade.*,
						   CASE
						   		WHEN tbl_grade.na = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
						   		ELSE \'<span class="label label-danger">NOT ACTIVE</span>\'
						   END as label_active');
		$this->db->from('tbl_grade');
		if($id_grade != NULL){
			$this->db->where('tbl_grade.id_grade', $id_grade);
		}
		$this->db->order_by('tbl_grade.id_grade', 'ASC');
		$query 	= $this->db->get();
		$result	= $query->result();
		
		$this->general->closeDb();
		return $result;
	}
	
}
?>