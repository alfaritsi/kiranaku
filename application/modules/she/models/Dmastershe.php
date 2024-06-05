<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : SHE
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. Lukman Hakim (7143) 04.07.2019
			   CR 1916 dan 1917			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dmastershe extends CI_Model{

	function get_data_bakumutu_log($id=NULL, $kategori=NULL, $jenis=NULL, $parameter=NULL, $tgl_mulai=NULL, $tgl_akhir=NULL, $all=NULL){
		$this->db->select('a.regulasi, a.id, convert(varchar, a.tanggal_mulai, 104) tgl_mulai, convert(varchar, a.tanggal_akhir, 104) tgl_akhir, 
                  a.fk_kategori, b.kategori, a.fk_jenis, c.jenis, a.fk_parameter, d.parameter, a.bakumutu_hasilujilimit, 
                  a.bakumutu_hasilujimin, a.bakumutu_hasilujimax, a.bakumutu_bebancemarlimit, a.bakumutu_bebancemarmin, a.bakumutu_bebancemarmax ,
                  e.nama as namabuat, convert(varchar, a.tanggal_buat, 106) as tanggalbuat,
                  convert(varchar, a.tanggal_edit, 106) as tanggaledit, a.na, a.del');
		$this->db->from('tbl_she_bakumutu_log as a');
		$this->db->join('tbl_she_master_kategori as b', 'a.fk_kategori = b.id', 'inner');
		$this->db->join('tbl_she_master_jenis as c', 'a.fk_jenis = c.id', 'inner');
		$this->db->join('tbl_she_master_parameter as d', 'a.fk_parameter = d.id', 'inner');
		$this->db->join('tbl_user as u', 'u.id_user = a.login_buat', 'left outer join');
		$this->db->join('tbl_karyawan as e', 'e.nik = u.id_karyawan', 'left outer join');
		if($id != NULL){
			$this->db->where('a.id', $id);
		}
		if($kategori != NULL){
			$this->db->where('a.fk_kategori', $kategori);
		}
		if($jenis != NULL){
			$this->db->where_in('a.fk_jenis', $jenis);
		}
		if($parameter != NULL){
			$this->db->where('a.fk_parameter', $parameter);
		}
		if($tgl_mulai != NULL){
			$this->db->where('a.tanggal_mulai <=', $this->generate->regenerateDateFormat($tgl_mulai));
			$this->db->where('a.tanggal_akhir >=', $this->generate->regenerateDateFormat($tgl_mulai));
		}
		if($tgl_akhir != NULL){
			$this->db->where('a.tanggal_mulai <=', $this->generate->regenerateDateFormat($tgl_akhir));
			$this->db->where('a.tanggal_akhir >=', $this->generate->regenerateDateFormat($tgl_akhir));
		}
		if($all == NULL){
			// $this->db->where("a.tanggal_akhir>=getdate()");
			$this->db->where('a.na', NULL);
			$this->db->where('a.del', 1);
		}
		// $this->db->order_by('id_mtujuan_inv', 'ASC');
		$query = $this->db->get();

		return $query->result();	
		
	}
	
	function get_data_bakumutu($id=NULL, $kategori=NULL, $jenis=NULL, $parameter=NULL, $tgl_mulai=NULL, $tgl_akhir=NULL, $all=NULL){
		$this->db->select('a.regulasi, a.id, convert(varchar, a.tanggal_mulai, 104) tgl_mulai, convert(varchar, a.tanggal_akhir, 104) tgl_akhir, 
                  a.fk_kategori, b.kategori, a.fk_jenis, c.jenis, a.fk_parameter, d.parameter, a.bakumutu_hasilujilimit, 
                  a.bakumutu_hasilujimin, a.bakumutu_hasilujimax, a.bakumutu_bebancemarlimit, a.bakumutu_bebancemarmin, a.bakumutu_bebancemarmax ,
                  e.nama as namabuat, convert(varchar, a.tanggal_buat, 106) as tanggalbuat,f.nama as namaedit,
                  convert(varchar, a.tanggal_edit, 106) as tanggaledit, a.na, a.del');
		$this->db->from('tbl_she_bakumutu as a');
		$this->db->join('tbl_she_master_kategori as b', 'a.fk_kategori = b.id', 'inner');
		$this->db->join('tbl_she_master_jenis as c', 'a.fk_jenis = c.id', 'inner');
		$this->db->join('tbl_she_master_parameter as d', 'a.fk_parameter = d.id', 'inner');
		$this->db->join('tbl_karyawan as e', 'a.login_buat = e.id_karyawan', 'inner');
		$this->db->join('tbl_karyawan as f', 'a.login_edit = f.id_karyawan', 'left');
		if($id != NULL){
			$this->db->where('a.id', $id);
		}
		if($kategori != NULL){
			$this->db->where('a.fk_kategori', $kategori);
		}
		if($jenis != NULL){
			$this->db->where_in('a.fk_jenis', $jenis);
		}
		if($parameter != NULL){
			$this->db->where('a.fk_parameter', $parameter);
		}
		if($tgl_mulai != NULL){
			$this->db->where('a.tanggal_mulai <=', $this->generate->regenerateDateFormat($tgl_mulai));
			$this->db->where('a.tanggal_akhir >=', $this->generate->regenerateDateFormat($tgl_mulai));
		}
		if($tgl_akhir != NULL){
			$this->db->where('a.tanggal_mulai <=', $this->generate->regenerateDateFormat($tgl_akhir));
			$this->db->where('a.tanggal_akhir >=', $this->generate->regenerateDateFormat($tgl_akhir));
		}
		if($all == NULL){
			// $this->db->where("a.tanggal_akhir>=getdate()");
			$this->db->where('a.na', NULL);
			$this->db->where('a.del', 1);
		}
		// $this->db->order_by('id_mtujuan_inv', 'ASC');
		$query = $this->db->get();

		return $query->result();	
		
	}
	
	function get_data_kategori(){
		$query = $this->db->get_where('tbl_she_master_kategori',array('del' => 1, 'na' => null));
		return $query->result();
	}

	function get_data_kategori_filter($kategori=NULL){
		// $query = $this->db->get_where('tbl_she_master_kategori',array('del' => 1, 'na' => null));
		$this->db->select('tbl_she_master_kategori.*');
		$this->db->from('tbl_she_master_kategori');
		
		if($kategori != NULL){
			$this->db->where_in('tbl_she_master_kategori.id', $kategori);
		}
		$this->db->order_by('tbl_she_master_kategori.id', 'ASC');
		$query = $this->db->get();
		return $query->result();	
	}

	function get_data_jenis($id=NULL, $jenis=NULL){
		// $query = $this->db->get_where('tbl_she_master_jenis',array('del' => 1, 'na' => null));
		// return $query->result();
		$this->db->select('tbl_she_master_jenis.*');
		$this->db->from('tbl_she_master_jenis');
		if($id != NULL){
			$this->db->where('tbl_she_master_jenis.id', $id);
		}
		if($jenis != NULL){
			$this->db->where('tbl_she_master_jenis.jenis', $jenis);
		}
		$this->db->where('tbl_she_master_jenis.del', 1);
		$this->db->order_by('tbl_she_master_jenis.jenis', 'ASC');
		$query = $this->db->get();

		return $query->result();	
	}
	function get_data_set_jenis($id=NULL, $fk_kategori = NULL){
		
		$this->db->select('tbl_she_master_jenis.id');
		$this->db->select('tbl_she_master_jenis.jenis');
		$this->db->from('tbl_she_jenis');
		$this->db->join('tbl_she_master_kategori', 'tbl_she_master_kategori.id = tbl_she_jenis.fk_kategori', 'inner');
		$this->db->join('tbl_she_master_jenis', 'tbl_she_master_jenis.id = tbl_she_jenis.fk_jenis', 'inner');
		if($id != NULL){
			$this->db->where('tbl_she_jenis.id', $id);
		}
		if($fk_kategori != NULL){
			$this->db->where('tbl_she_jenis.fk_kategori', $fk_kategori);
			$this->db->where('tbl_she_master_jenis.del', 1);
		}
		$this->db->group_by('tbl_she_master_jenis.id,tbl_she_master_jenis.jenis');
		$this->db->order_by('tbl_she_master_jenis.jenis', 'ASC');
		$query = $this->db->get();

		return $query->result();	
	}

	function get_data_sumber_limbah(){
		$query = $this->db->get_where('tbl_she_master_sumber_limbah',array('del' => 1, 'na' => null));
		return $query->result();
	}

	function get_data_parameter($id=NULL, $parameter=NULL){
		// $query = $this->db->get_where('tbl_she_master_parameter',array('del' => 1, 'na' => null));
		// return $query->result();
		$this->db->select('tbl_she_master_parameter.*');
		$this->db->from('tbl_she_master_parameter');
		if($id != NULL){
			$this->db->where('tbl_she_master_parameter.id', $id);
		}
		if($parameter != NULL){
			$this->db->where('tbl_she_master_parameter.parameter', $parameter);
		}
		$this->db->where('tbl_she_master_parameter.del', 1);
		$this->db->order_by('tbl_she_master_parameter.parameter', 'ASC');
		$query = $this->db->get();
		return $query->result();	
	}

	function get_data_plant(){
		$query = $this->db->get_where('tbl_wf_master_plant',array('del' => 'n', 'na' => 'n'));
		return $query->result();
	}

	function get_pabrik($plant=NULL, $all=NULL){
		$this->general->connectDbPortal();
		// $query = $this->db->get_where('tbl_inv_pabrik',array('del' => 'n', 'na' => 'n'));

		$this->db->select('id_pabrik, kode, kode2, plant_code, nama');
		$this->db->from('tbl_inv_pabrik');
		if($plant != NULL){
			$this->db->where('kode', $plant);
		}
		if($all == NULL){
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');
		}
		if(base64_decode($this->session->userdata("-ho-"))=='n'){
			$this->db->where('kode', base64_decode($this->session->userdata("-gsber-")));
		}
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get();

		return $query->result();
	}

	function get_data_idpabrik($plant=NULL){
		$this->db->select('id_pabrik');
		$this->db->from('tbl_inv_pabrik');
		$this->db->where('na', 'n');
		$this->db->where('del', 'n');
		if($plant != NULL){
			$this->db->where('kode', $plant);
		}
		$query = $this->db->get();

		return $query->result();	

	}

	function get_data_satuan(){
		$query = $this->db->get_where('tbl_she_master_satuan',array('del' => 'n', 'na' => 'n'));
		return $query->result();
	}


	function get_data_dtjenis($id=NULL, $pabrik=NULL, $kategori=NULL, $jenis=NULL, $all=NULL){
		$this->db->select('a.id, a.fk_pabrik, d.kode as kode_pabrik, d.nama as pabrik, a.fk_kategori, b.kategori, a.fk_jenis, c.jenis ,
			e.nama as namabuat, convert(varchar, a.tanggal_buat, 106) as tanggalbuat,f.nama as namaedit,
			convert(varchar, a.tanggal_edit, 106) as tanggaledit, a.na, a.del');
		$this->db->from('tbl_she_jenis as a');
		$this->db->join('tbl_she_master_kategori as b', 'a.fk_kategori = b.id', 'inner');
		$this->db->join('tbl_she_master_jenis as c', 'a.fk_jenis = c.id', 'inner');
		$this->db->join('tbl_inv_pabrik as d', 'a.fk_pabrik = d.id_pabrik', 'inner');
		$this->db->join('tbl_karyawan as e', 'a.login_buat = e.id_karyawan', 'inner');
		$this->db->join('tbl_karyawan as f', 'a.login_edit = f.id_karyawan', 'left');
		if($id != NULL){
			$this->db->where('a.id', $id);
		}
		if($pabrik != NULL){
			$this->db->where('a.fk_pabrik', $pabrik);
		}
		if($kategori != NULL){
			$this->db->where('a.fk_kategori', $kategori);
		}
		if($jenis != NULL){
			$this->db->where('a.fk_jenis', $jenis);
		}
		if($all == NULL){
			$this->db->where('a.na IS NULL');
			$this->db->where('a.del', 1);
		}
		// $this->db->order_by('id_mtujuan_inv', 'ASC');
		$query = $this->db->get();

		return $query->result();	
		
	}

	function get_data_kapasitas_ipal($id=NULL, $pabrik=NULL, $all=NULL){
		$this->db->select('a.id, a.fk_pabrik, b.kode as kode_pabrik, b.nama as pabrik, a.kapasitas_ipal,
              c.nama as namabuat, convert(varchar, a.tanggal_buat, 106) as tanggalbuat,d.nama as namaedit,
              convert(varchar, a.tanggal_edit, 106) as tanggaledit, a.na, a.del');
		$this->db->from('tbl_she_kapasitas_ipal as a');
		$this->db->join('tbl_inv_pabrik as b', 'a.fk_pabrik = b.id_pabrik', 'inner');
		$this->db->join('tbl_karyawan as c', 'a.login_buat = c.id_karyawan', 'inner');
		$this->db->join('tbl_karyawan as d', 'a.login_edit = d.id_karyawan', 'left');
		if($id != NULL){
			$this->db->where('a.id', $id);
		}
		if($pabrik != NULL){
			$this->db->where('a.fk_pabrik', $pabrik);
		}
		if($all == NULL){
			$this->db->where('a.na IS NULL');
			$this->db->where('a.del', 1);
		}
		
		$this->db->order_by('a.id', 'DESC');
		// $this->db->order_by('id_mtujuan_inv', 'ASC');
		$query = $this->db->get();

		return $query->result();	
		
	}

	function get_data_dtparameter($id=NULL, $pabrik=NULL, $kategori=NULL, $jenis=NULL, $parameter=NULL, $all=NULL){
		$this->db->select('a.id, a.fk_pabrik, d.kode as kode_pabrik, d.nama as pabrik, a.fk_kategori, b.kategori as kategori, a.fk_jenis, e.jenis as jenis, 
				  a.fk_parameter, c.parameter as parameter,
                  f.nama as namabuat, convert(varchar, a.tanggal_buat, 106) as tanggalbuat, g.nama as namaedit,
                  convert(varchar, a.tanggal_edit, 106) as tanggaledit, a.na, a.del');
		$this->db->from('tbl_she_parameter as a');
		$this->db->join('tbl_she_master_kategori as b', 'a.fk_kategori = b.id', 'left');
		$this->db->join('tbl_she_master_parameter as c', 'a.fk_parameter = c.id', 'left');
		$this->db->join('tbl_inv_pabrik as d', 'a.fk_pabrik = d.id_pabrik', 'left');
		$this->db->join('tbl_she_master_jenis as e', 'a.fk_jenis = e.id', 'left');
		$this->db->join('tbl_karyawan as f', 'a.login_buat = f.id_karyawan', 'left');
		$this->db->join('tbl_karyawan as g', 'a.login_edit = g.id_karyawan', 'left');
		if($id != NULL){
			$this->db->where('a.id', $id);
		}
		if($pabrik != NULL){
			$this->db->where('a.fk_pabrik', $pabrik);
		}
		if($kategori != NULL){
			$this->db->where('a.fk_kategori', $kategori);
		}
		if($jenis != NULL){
			$this->db->where('a.fk_jenis', $jenis);
		}
		if($parameter != NULL){
			$this->db->where('a.fk_parameter', $parameter);
		}
		if($all == NULL){
			// $this->db->where('a.na IS NULL');
			$this->db->where("a.fk_kategori!=''");
			$this->db->where('a.del', 1);
		}
		$this->db->order_by('a.id', 'DESC');
		$query = $this->db->get();

		return $query->result();	
	}

	function get_data_limbah_($pabrik=NULL, $jenislimbah=NULL, $all=NULL){
		$this->db->select('a.id, a.jenis_limbah, a.kode_material, a.kode_reglimbah, a.konversi_satuan_pengiriman, form_log_book_number, masa_simpan, a.na, a.del');
		$this->db->from('tbl_she_jenis as b');
		$this->db->join('tbl_she_limbah as a', 'a.id = b.fk_jenis', 'left outer');
		if($pabrik != NULL){
			$this->db->where('a.fk_pabrik', $pabrik);
		}
		if($jenislimbah != NULL){
			$this->db->where('a.jenis_limbah', $jenislimbah);
		}
		if($all == NULL){
			$this->db->where('a.na IS NULL');
			$this->db->where('a.del', 1);
		}
		$this->db->order_by('a.jenis_limbah');
		$query = $this->db->get();

		return $query->result();	
		
	}
	
	function get_data_limbah($pabrik=NULL, $jenislimbah=NULL, $all=NULL){
		$this->db->select('a.id, a.jenis_limbah, a.kode_material, a.kode_reglimbah, a.konversi_satuan_pengiriman, form_log_book_number, masa_simpan, a.na, a.del');
		$this->db->from('tbl_she_limbah as a');
		if($pabrik != NULL){
			$this->db->where('a.fk_pabrik', $pabrik);
		}
		if($jenislimbah != NULL){
			$this->db->where('a.jenis_limbah', $jenislimbah);
		}
		if($all == NULL){
			$this->db->where('a.na IS NULL');
			$this->db->where('a.del', 1);
		}
		$this->db->order_by('a.jenis_limbah');
		$query = $this->db->get();

		return $query->result();	
		
	}

	function get_data_dtlimbah_old($id=NULL, $pabrik=NULL, $jenislimbah=NULL, $all=NULL){
		$this->db->distinct();
		$this->db->select('a.jenis_limbah, a.kode_material, a.kode_reglimbah, a.fk_satuan, c.nama as satuan,
                konversi_ton, a.fk_satuan_pengiriman, d.nama as satuan_pengiriman, a.konversi_satuan_pengiriman, form_log_book_number, a.na, a.del');
		$this->db->from('tbl_she_jenis as z');
		$this->db->join('tbl_inv_pabrik as a', 'a.id = z.fk_jenis', 'left outer');
		$this->db->join('tbl_inv_pabrik as b', 'a.fk_pabrik = b.id_pabrik', 'left');
		$this->db->join('tbl_she_master_satuan as c', 'a.fk_satuan = c.id_uom', 'left');
		$this->db->join('tbl_she_master_satuan as d', 'a.fk_satuan_pengiriman = d.id_uom', 'left');
		$this->db->join('tbl_karyawan as e', 'a.login_buat = e.id_karyawan', 'left');
		$this->db->join('tbl_karyawan as f', 'a.login_edit = f.id_karyawan', 'left');
		if($id != NULL){
			$this->db->where('a.id', $id);
		}
		if($pabrik != NULL){
			$this->db->where('a.fk_pabrik', $pabrik);
		}
		if($jenislimbah != NULL){
			$this->db->where('a.jenis_limbah', $jenislimbah);
		}
		if($all == NULL){
			$this->db->where('a.na IS NULL');
			$this->db->where('a.del', 1);
		}
		$this->db->order_by('a.jenis_limbah');
		$query = $this->db->get();

		return $query->result();	
		
	}
	
	function get_data_dtlimbah($id=NULL, $pabrik=NULL, $jenislimbah=NULL, $all=NULL){
		$this->db->distinct();
		$this->db->select('a.jenis_limbah, a.kode_material, vw_material_spec.description_detail, a.kode_reglimbah, a.fk_satuan, c.nama as satuan,
                konversi_ton, a.fk_satuan_pengiriman, d.nama as satuan_pengiriman, a.konversi_satuan_pengiriman, form_log_book_number, a.na, a.del');
		$this->db->select('CASE
								WHEN a.del = \'1\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_she_limbah as a');
		$this->db->join('tbl_inv_pabrik as b', 'a.fk_pabrik = b.id_pabrik', 'left');
		$this->db->join('tbl_she_master_satuan as c', 'a.fk_satuan = c.id_uom', 'left');
		$this->db->join('tbl_she_master_satuan as d', 'a.fk_satuan_pengiriman = d.id_uom', 'left');
		$this->db->join('tbl_karyawan as e', 'a.login_buat = e.id_karyawan', 'left');
		$this->db->join('tbl_karyawan as f', 'a.login_edit = f.id_karyawan', 'left');
		$this->db->join('vw_material_spec', 'vw_material_spec.code = a.kode_material', 'left');
		if($id != NULL){
			$this->db->where('a.id', $id);
		}
		if($pabrik != NULL){
			$this->db->where('a.fk_pabrik', $pabrik);
		}
		if($jenislimbah != NULL){
			$this->db->where('a.jenis_limbah', $jenislimbah);
		}
		if($all == NULL){
			$this->db->where('a.na IS NULL');
			// $this->db->where('a.del', 1);
		}
		$this->db->order_by('a.jenis_limbah');
		$query = $this->db->get();

		return $query->result();	
		
	}

	function get_data_dtlimbahpabrik($id=NULL, $pabrik=NULL, $jenislimbah=NULL, $all=NULL){
		$this->db->select('a.id, a.jenis_limbah, a.kode_material, vw_material_spec.description_detail, a.kode_reglimbah, a.fk_satuan, c.nama as satuan,
                konversi_ton, a.fk_satuan_pengiriman, d.nama as satuan_pengiriman, a.konversi_satuan_pengiriman, form_log_book_number, a.na, a.del');
		$this->db->from('tbl_she_limbah as a');
		$this->db->join('tbl_inv_pabrik as b', 'a.fk_pabrik = b.id_pabrik', 'left');
		$this->db->join('tbl_she_master_satuan as c', 'a.fk_satuan = c.id_uom', 'left');
		$this->db->join('tbl_she_master_satuan as d', 'a.fk_satuan_pengiriman = d.id_uom', 'left');
		$this->db->join('tbl_karyawan as e', 'a.login_buat = e.id_karyawan', 'left');
		$this->db->join('tbl_karyawan as f', 'a.login_edit = f.id_karyawan', 'left');
		$this->db->join('vw_material_spec', 'vw_material_spec.code = a.kode_material', 'left');
		if($id != NULL){
			$this->db->where('a.id', $id);
		}
		if($pabrik != NULL){
			$this->db->where('a.fk_pabrik', $pabrik);
		}
		if($jenislimbah != NULL){
			$this->db->where('a.jenis_limbah', $jenislimbah);
		}
		if($all == NULL){
			$this->db->where('a.na IS NULL');
			$this->db->where('a.del', 1);
		}
		$this->db->order_by('a.jenis_limbah');
		$query = $this->db->get();

		return $query->result();	
		
	}

	function get_data_dtmasasimpan_old($pabrik=NULL){
		// echo $pabrik; exit();
		$string	= "SELECT distinct a.fk_pabrik, b.nama as pabrik, masa_simpan
				FROM tbl_she_limbah a
				LEFT JOIN tbl_inv_pabrik as b
				  on a.fk_pabrik = b.id_pabrik
				WHERE a.del=1 AND masa_simpan > 0";

		if(isset($pabrik) && $pabrik != ""){
			$string .= " AND a.fk_pabrik = '".$pabrik."'";				
		}
		$string	.= " order by b.nama";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $query->result();		
	}
	
	function get_data_dtmasasimpan($id=NULL){
		// echo $pabrik; exit();
		$string	= "SELECT a.id, a.jenis_limbah, a.fk_pabrik, b.nama as pabrik, masa_simpan
				FROM tbl_she_limbah a
				LEFT JOIN tbl_inv_pabrik as b
				  on a.fk_pabrik = b.id_pabrik
				WHERE a.del=1 and b.nama!='' ";

		if(isset($id) && $id != ""){
			$string .= " AND a.id = '".$id."'";				
		}
		$string	.= " order by b.nama";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $query->result();		
	}

	function get_data_mastervendor($vendor=NULL, $plant=NULL){
        $this->general->connectDbPortal();		
		$this->db->select('*');
		$this->db->from('tbl_she_vendor');
		if($vendor != NULL){
			$this->db->where('id', $vendor);
		}
		if($plant != NULL){
			$this->db->where('fk_pabrik', $plant);
		}
		// $this->db->order_by('id_mtujuan_inv', 'ASC');
		$query = $this->db->get();

		return $query->result();	
		
	}

	function get_nama_vendor($vendor=NULL){
		$this->general->connectDbDefault();

		$this->db->select('a.LIFNR, a.NAME1');
		$this->db->from('ZDMNONBKRVENDOR as a');
		$this->db->where('a.LIFNR', $vendor);
		// $this->db->order_by('id_mtujuan_inv', 'ASC');
		$query = $this->db->get();

		$result = $query->result();

		$this->general->closeDB();
		$this->general->connectDbPortal();

		return $result;		
	}

	function get_data_vendor($id=NULL,$pabrik=NULL, $vendor=NULL){
		$this->general->connectDbDefault();

		$this->db->select("a.LIFNR, a.NAME1, 
			b.id, b.kode_vendor, b.fk_pabrik, b.nama_vendor, b.nama_pengumpul, b.nama_pemanfaat, b.izin_kumpul_jenislimbah, 
			CONVERT(VARCHAR(10),b.izin_kumpul_expdate,104) izin_kumpul_expdate, b.file_ikumpul, b.pihak_ketiga_jenislimbah, 
			CONVERT(VARCHAR(10),b.pihak_ketiga_expdate,104) pihak_ketiga_expdate, b.file_ipihak_ketiga, 
			CONVERT(VARCHAR(10),b.pihak_ketiga_spbp_expdate,104) pihak_ketiga_spbp_expdate, b.file_pihak_ketiga_spbp, 
			CONVERT(VARCHAR(10),b.pihak_ketiga_spbp_expdate2,104) pihak_ketiga_spbp_expdate2, b.file_pihak_ketiga_spbp2, 
			CONVERT(VARCHAR(10),b.pihak_ketiga_spbp_expdate3,104) pihak_ketiga_spbp_expdate3, b.file_pihak_ketiga_spbp3, 
			CONVERT(VARCHAR(10),b.pemanfaat_expdate,104) pemanfaat_expdate, b.file_pemanfaat, 
			CONVERT(VARCHAR(10),b.pengumpulpemanfaat_expdate,104) pengumpulpemanfaat_expdate, b.file_pengumpulpemanfaat, b.angkut_klhk_jenislimbah, 
			CONVERT(VARCHAR(10),b.angkut_klhk_expdate,104) angkut_klhk_expdate, b.file_angkut_klhk, b.angkut_dhd_jenislimbah, 
			CONVERT(VARCHAR(10),b.angkut_dhd_expdate,104) angkut_dhd_expdate, b.file_angkut_dhd, 
			CONVERT(VARCHAR(10),b.angkut_dhd_spbp_expdate,104) angkut_dhd_spbp_expdate, b.file_angkut_dhd_spbp, 
			b.login_buat, b.tanggal_buat, b.login_edit, b.tanggal_edit, b.del, b.na,
			'[' + REPLACE(LEFT(SUBSTRING(izin_kumpul_jenislimbah,2,150), LEN(SUBSTRING(izin_kumpul_jenislimbah,2,150)) -1),'.',',') + ']' kumpul_jenislimbah,
			'[' + REPLACE(LEFT(SUBSTRING(pihak_ketiga_jenislimbah,2,150), LEN(SUBSTRING(pihak_ketiga_jenislimbah,2,150)) -1),'.',',') + ']' mou_jenislimbah,
			'[' + REPLACE(LEFT(SUBSTRING(angkut_klhk_jenislimbah,2,150), LEN(SUBSTRING(angkut_klhk_jenislimbah,2,150)) -1),'.',',') + ']' klhk_jenislimbah,
			'[' + REPLACE(LEFT(SUBSTRING(angkut_dhd_jenislimbah,2,150), LEN(SUBSTRING(angkut_dhd_jenislimbah,2,150)) -1),'.',',') + ']' dhd_jenislimbah");
		$this->db->from('ZDMNONBKRVENDOR as a');
		$this->db->join(DB_PORTAL.'.dbo.tbl_inv_pabrik as c', 'a.EKORG = c.kode COLLATE SQL_Latin1_General_CP1_CI_AS', 'left', false);
		$this->db->join(DB_PORTAL.'.dbo.tbl_she_vendor as b', 'a.LIFNR = b.kode_vendor COLLATE SQL_Latin1_General_CP1_CI_AS AND b.fk_pabrik = c.id_pabrik AND b.del = 1', 'left', false);
		if($id != NULL){
			$this->db->where('b.id', $id);
		}else{
			// $this->db->where('b.kode_vendor', NULL);
		}
		if($pabrik != NULL){
			$this->db->where('c.id_pabrik', $pabrik);
		}
		if($vendor != NULL){
			$this->db->where('a.LIFNR', $vendor);
		}
		$this->db->order_by('a.NAME1', 'ASC');
		$query = $this->db->get();
		$result = $query->result();

		$this->general->closeDB();
		$this->general->connectDbPortal();

		return $result;	
		
	}

	function get_data_dtvendor($pabrik=NULL, $vendor=NULL, $all=NULL){
		// $this->db->select('a.id,c.kode as pabrik, a.nama_vendor,a.kode_vendor,a.spk_no, 
		// 		convert(varchar, a.spk_expdate, 105) as spk_expdate,
  //               a.angkut_klhk_jenislimbah as klkh, convert(varchar, a.angkut_klhk_expdate, 105) klhk_expdate,
  //               a.angkut_dhd_jenislimbah as dhd, a.angkut_dhd_area, 
  //               convert(varchar, a.angkut_dhd_expdate, 105) dhd_expdate, a.izin_kumpul_jenislimbah as kumpul, 
  //               convert(varchar, a.izin_kumpul_expdate, 105) kumpul_expdate, a.bebas_cemar_jenislimbah as cemar,
  //               convert(varchar, a.bebas_cemar_expdate, 105) cemar_expdate, a.pihak_ketiga_nama,a.pihak_ketiga_ijin, 
  //               a.pihak_ketiga_jenislimbah as pk,convert(varchar, a.pihak_ketiga_expdate, 105) pihak_ketiga_expdate ,
  //               c.nama as namabuat, convert(varchar, a.tanggal_buat, 105) as tanggalbuat, d.nama as namaedit,
  //               convert(varchar, a.tanggal_edit, 105) as tanggaledit, a.na, a.del');
		// $this->db->from('tbl_she_vendor as a');
		// $this->db->join('tbl_she_limbah as b', 'a.angkut_klhk_jenislimbah = b.id', 'inner');
		// $this->db->join('tbl_inv_pabrik as c', 'a.fk_pabrik = c.id_pabrik', 'inner');
		// $this->db->join('tbl_karyawan as d', 'a.login_buat = d.id_karyawan', 'inner');
		// $this->db->join('tbl_karyawan as e', 'a.login_edit = e.id_karyawan', 'left');
		// if($pabrik != NULL){
		// 	$this->db->where('a.fk_pabrik', $pabrik);
		// }
		// if($vendor != NULL){
		// 	$this->db->where('a.kode_vendor', $vendor);
		// }
		// if($all == NULL){
		// 	$this->db->where('a.na IS NULL');
		// 	$this->db->where('a.del', 1);
		// }
		// // $this->db->order_by('id_mtujuan_inv', 'ASC');
		// $query = $this->db->get();

		// $this->db->output('SP_Kiranaku_SHE_View_Vendor', array('Param1'=>1, 'SELECT');
		// $query = $this->db->get();

		$query = $this->db->query("EXEC SP_Kiranaku_SHE_View_Vendor 1");
	    return $query->result();
		
	}


}

?>