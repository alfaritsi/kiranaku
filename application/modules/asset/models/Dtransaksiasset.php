<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Asset Management
@author		  : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dtransaksiasset extends CI_Model{
	function get_data_nomor($conn = NULL, $id_jenis=NULL, $id_pabrik=NULL, $id_aset=NULL){
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		if($id_aset !== NULL){
			$this->db->select("tbl_inv_aset.nomor");	
		} else {
			$this->db->select("right('0000'+convert(varchar(5), (count(*)+1)), 4) as nomor");
		}
		$this->db->select("(select tbl_inv_jenis.kode from tbl_inv_jenis where tbl_inv_jenis.id_jenis='$id_jenis') as kode_jenis");
		$this->db->select("(select tbl_inv_pabrik.kode from tbl_inv_pabrik where tbl_inv_pabrik.id_pabrik='$id_pabrik') as kode_pabrik");
		$this->db->from('tbl_inv_aset');
		if ($id_jenis !== NULL) {
			$this->db->where('tbl_inv_aset.id_jenis', $id_jenis);
		}
		if ($id_pabrik !== NULL) {
			$this->db->where('tbl_inv_aset.id_pabrik', $id_pabrik);
		}
		if ($id_aset !== NULL) {
			$this->db->where('tbl_inv_aset.id_aset', $id_aset);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_cek($conn = NULL, $tabel = NULL, $field = NULL, $value = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select($tabel.'.*');
		$this->db->from($tabel);
		if (($field !== NULL) and ($value !== NULL)){
			$this->db->where($tabel.'.'.$field, $value);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_main($conn = NULL, $id_main = NULL, $active = NULL, $deleted = 'n', $id_aset = NULL, $final = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_main.*');
		$this->db->select('tbl_inv_pabrik.nama as nama_pabrik');
		$this->db->select('CASE
								WHEN tbl_inv_main.jenis_tindakan = \'perbaikan\' THEN \'Perbaikan Kerusakan\'
								WHEN tbl_inv_main.jenis_tindakan = \'perawatan\' THEN \'Perawatan Rutin\'
								ELSE \'Update Jam Jalan\'
						   END as nama_jenis_tindakan');
		if ($id_aset !== NULL) {
			$this->db->select('(select tbl_karyawan.nama from tbl_karyawan where id_karyawan=(select tbl_user.id_karyawan from tbl_user where tbl_user.id_user=tbl_inv_main.login_buat) ) as nama_karyawan');
			$this->db->select("convert(varchar, tbl_inv_main.tanggal_buat, 104) as tanggal_input");
			$this->db->select("convert(varchar, tbl_inv_main.tanggal_mulai, 104) as tanggal_mulai2");
			$this->db->select("convert(varchar, tbl_inv_main.tanggal_selesai, 104) as tanggal_selesai2");
			
		}					
		$this->db->from('tbl_inv_main');
		$this->db->join('tbl_inv_aset', 'tbl_inv_aset.id_aset = tbl_inv_main.id_aset', 'left outer');		
		$this->db->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = tbl_inv_aset.id_pabrik', 'left outer');		
		if ($id_main !== NULL) {
			$this->db->where('tbl_inv_main.id_main', $id_main);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_main.na', $active);
		}
		if ($id_aset !== NULL) {
			$this->db->where('tbl_inv_main.id_aset', $id_aset);
		}
		if ($final !== NULL) {
			$this->db->where('tbl_inv_main.final', $final);
		}
		$this->db->where('tbl_inv_main.del', $deleted);
		$this->db->order_by("tbl_inv_main.id_main", "desc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_maintenance($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_maintenance.*');
		$this->datatables->select("convert(varchar, tbl_inv_maintenance.tanggal, 104) as tanggal_konversi");
		$this->db->from('tbl_inv_maintenance');
		if ($id_aset !== NULL) {
			$this->db->where('tbl_inv_maintenance.id_aset', $id_aset);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_maintenance.na', $active);
		}
		$this->db->order_by("tbl_inv_maintenance.id_maintenance", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_main_detail($conn = NULL, $id_main_detail = NULL, $active = NULL, $deleted = 'n', $id_main = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_main_detail.*');
		$this->db->select("CASE
								WHEN tbl_inv_main_detail.keterangan is null THEN ''
								ELSE tbl_inv_main_detail.keterangan
						   END as label_keterangan");
		$this->db->select("CASE
								WHEN tbl_inv_main_detail.cek='y' THEN 'checked'
								ELSE ''
						   END as label_cek");
		$this->db->from('tbl_inv_main_detail');
		if ($id_main_detail !== NULL) {
			$this->db->where('tbl_inv_main_detail.id_main_detail', $id_main_detail);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_main_detail.na', $active);
		}
		if ($id_main !== NULL) {
			$this->db->where('tbl_inv_main_detail.id_main', $id_main);
		}
		
		$this->db->where('tbl_inv_main_detail.del', $deleted);
		$this->db->order_by("tbl_inv_main_detail.nama_jenis_detail", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_email($conn = NULL, $id_email = NULL, $active = NULL, $deleted = 'n', $id_karyawan = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_karyawan.*');
		$this->db->select('tbl_inv_email.id_email');
		$this->db->select('CASE
								WHEN tbl_inv_email.apar = \'y\' THEN \'<span class="label label-success">Yes</span>\'
								ELSE \'<span class="label label-danger">No</span>\'
						   END as label_apar');
		$this->db->select('CASE
								WHEN tbl_inv_email.apar = \'y\' THEN \'n\'
								ELSE \'y\'
						   END as value_apar');
		$this->db->select('CASE
								WHEN tbl_inv_email.alat_lab = \'y\' THEN \'<span class="label label-success">Yes</span>\'
								ELSE \'<span class="label label-danger">No</span>\'
						   END as label_lab');
		$this->db->select('CASE
								WHEN tbl_inv_email.alat_lab = \'y\' THEN \'n\'
								ELSE \'y\'
						   END as value_lab');
		$this->db->select('CASE
								WHEN tbl_karyawan.ho = \'y\' THEN \'HO\'
								ELSE tbl_karyawan.gsber
						   END as pabrik');
		$this->db->from('tbl_karyawan');
		$this->db->join('tbl_inv_email', 'tbl_inv_email.id_karyawan = tbl_karyawan.id_karyawan', 'left');		
		if ($id_email !== NULL) {
			$this->db->where('tbl_inv_email.id_email', $id_email);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_karyawan.na', $active);
		}
		if ($id_karyawan !== NULL) {
			$this->db->where('tbl_inv_email.id_karyawan', $id_karyawan);
		}
		$this->db->where('tbl_karyawan.del', $deleted);
		$this->db->where('tbl_karyawan.email!=', '');
		$this->db->where('tbl_karyawan.gsber!=', '');
		$this->db->order_by("tbl_karyawan.nama", "asc");
		// $this->db->limit(5);
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_dokumen($conn = NULL, $id_inv_doc = NULL, $active = NULL, $deleted = 'n', $id_aset= NULL, $id_jenis= NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_doc.*');
		$this->db->select('CASE
								WHEN tbl_inv_doc.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		if ($id_jenis !== NULL) {
			$this->db->select("(select tbl_inv_doc_transaksi.tanggal_berlaku from tbl_inv_doc_transaksi where tbl_inv_doc_transaksi.id_inv_doc=tbl_inv_doc.id_inv_doc and tbl_inv_doc_transaksi.id_aset='$id_aset') as tanggal_berlaku");
			$this->db->select("(select tbl_inv_doc_transaksi.tanggal_berakhir from tbl_inv_doc_transaksi where tbl_inv_doc_transaksi.id_inv_doc=tbl_inv_doc.id_inv_doc and tbl_inv_doc_transaksi.id_aset='$id_aset') as tanggal_berakhir");			
			$this->db->select("(select datediff(day,getdate(),tbl_inv_doc_transaksi.tanggal_berakhir) from tbl_inv_doc_transaksi where tbl_inv_doc_transaksi.id_inv_doc=tbl_inv_doc.id_inv_doc and tbl_inv_doc_transaksi.id_aset='$id_aset') as sisa_hari");			
		}
		
		$this->db->from('tbl_inv_doc');
		if ($id_inv_doc !== NULL) {
			$this->db->where('tbl_inv_doc.id_inv_doc', $id_inv_doc);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_doc.na', $active);
		}
		// if ($id_aset !== NULL) {
			// $this->db->where("tbl_inv_doc.id_inv_doc not in(select tbl_inv_doc_transaksi.id_inv_doc from tbl_inv_doc_transaksi where tbl_inv_doc_transaksi.id_aset='$id_aset')");
		// }
		if ($id_jenis !== NULL) {
			$this->db->where("CHARINDEX('$id_jenis', jenis)>0");
		}
		$this->db->where('tbl_inv_doc.del', $deleted);
		$this->db->order_by("tbl_inv_doc.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_dokumen_transaksi($conn = NULL, $id_inv_doc_transaksi = NULL, $active = NULL, $deleted = 'n', $id_aset = NULL, $id_inv_doc = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_doc_transaksi.*');
		$this->db->select('tbl_inv_doc.nama as nama_dokumen');
		$this->db->select('tbl_inv_doc.periode');
		$this->db->select('CASE
								WHEN tbl_inv_doc_transaksi.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_doc_transaksi');
		$this->db->join('tbl_inv_doc', 'tbl_inv_doc.id_inv_doc = tbl_inv_doc_transaksi.id_inv_doc AND tbl_inv_doc.na = \'n\'', 'left outer');		
		if ($id_inv_doc_transaksi !== NULL) {
			$this->db->where('tbl_inv_doc_transaksi.id_inv_doc_transaksi', $id_inv_doc_transaksi);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_doc_transaksi.na', $active);
		}
		if ($id_aset !== NULL) {
			$this->db->where('tbl_inv_doc_transaksi.id_aset', $id_aset);
		}
		if ($id_inv_doc !== NULL) {
			$this->db->where('tbl_inv_doc_transaksi.id_inv_doc', $id_inv_doc);
		}
		$this->db->where('tbl_inv_doc_transaksi.del', $deleted);
		$this->db->order_by("tbl_inv_doc_transaksi.nomor_dokumen", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_kategori($conn = NULL, $id_kategori = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_kategori.*');
		$this->db->select('CASE
								WHEN tbl_inv_kategori.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_kategori');
		if ($id_kategori !== NULL) {
			$this->db->where('tbl_inv_kategori.id_kategori', $id_kategori);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_kategori.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_kategori.nama', $nama);
		}
		if ($pengguna !== NULL) {
			$this->db->where('tbl_inv_kategori.pengguna', $pengguna);
		}
		$this->db->where('tbl_inv_kategori.del', $deleted);
		$this->db->order_by("tbl_inv_kategori.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_detail_opsi($conn = NULL, $id_detail_opsi = NULL, $active = NULL, $deleted = 'n', $nama_kolom = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_aset_detail_opsi.*');
		$this->db->from('tbl_inv_aset_detail_opsi');
		if ($id_detail_opsi !== NULL) {
			$this->db->where('tbl_inv_aset_detail_opsi.id_detail_opsi', $id_detail_opsi);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_aset_detail_opsi.na', $active);
		}
		if ($nama_kolom !== NULL) {
			$this->db->where('tbl_inv_aset_detail_opsi.nama_kolom', $nama_kolom);
		}
		$this->db->where('tbl_inv_aset_detail_opsi.del', $deleted);
		$this->db->order_by("tbl_inv_aset_detail_opsi.nama_kolom", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_jenis($conn = NULL, $id_jenis = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $id_kategori = NULL, $kategori = NULL, $alat = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_jenis.*');
		$this->db->select('CASE
								WHEN tbl_inv_jenis.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_jenis');
		$this->db->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_jenis.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		if ($id_jenis !== NULL) {
			$this->db->where('tbl_inv_jenis.id_jenis', $id_jenis);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_jenis.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_jenis.nama', $nama);
		}
		if ($pengguna !== NULL) {
			$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if ($id_kategori !== NULL) {
			$this->db->where('tbl_inv_jenis.id_kategori', $id_kategori);
		}
		if($kategori != NULL){
			if(is_string($kategori)) $kategori = explode(",", $kategori);
			$this->db->where_in('tbl_inv_jenis.id_kategori', $kategori);
		}
		if ($alat !== NULL) {
			if($alat=='berat'){
				$this->db->where("tbl_inv_jenis.berat='y'");	
				$this->db->where("tbl_inv_kategori.na='n'");	
			}
			if($alat=='lab'){
				$this->db->where("tbl_inv_jenis.berat='n'");	
				$this->db->where("tbl_inv_kategori.na='n'");
			}
		}
		
		$this->db->where('tbl_inv_jenis.del', $deleted);
		$this->db->order_by("tbl_inv_jenis.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_user($conn = NULL, $id_user = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_user.*');
		$this->db->select('tbl_karyawan.nama');
		$this->db->from('tbl_user');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan AND tbl_karyawan.na = \'n\'', 'left outer');		
		if ($id_user !== NULL) {
			$this->db->where('tbl_user.id_user', $id_user);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_user.na', $active);
		}
		$this->db->where('tbl_user.del', $deleted);
		$this->db->order_by("tbl_user.id_karyawan", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_jenis_detail($conn = NULL, $id_jenis_detail = NULL, $active = NULL, $deleted = 'n', $id_jenis = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_jenis_detail.*');
		$this->db->from('tbl_inv_jenis_detail');
		// $this->db->join('tbl_inv_main','tbl_inv_jenis_detail.id_jenis = tbl_inv_main.id_jenis');
		if ($id_jenis_detail !== NULL) {
			$this->db->where('tbl_inv_jenis_detail.id_jenis_detail', $id_jenis_detail);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_jenis_detail.na', $active);
		}
		if ($id_jenis !== NULL) {
			$this->db->where('tbl_inv_jenis_detail.id_jenis', $id_jenis);
		}
		
		$this->db->where('tbl_inv_jenis_detail.del', $deleted);
		$this->db->order_by("tbl_inv_jenis_detail.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_merk($conn = NULL, $id_merk = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $id_jenis = NULL, $jenis = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_merk.*');
		$this->db->select('tbl_inv_jenis.nama as nama_jenis');
		$this->db->select('CASE
								WHEN tbl_inv_merk.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_merk');
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_merk.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		if ($id_merk !== NULL) {
			$this->db->where('tbl_inv_merk.id_merk', $id_merk);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_merk.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_merk.nama', $nama);
		}
		if ($pengguna !== NULL) {
			$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if ($id_jenis !== NULL) {
			$this->db->where('tbl_inv_merk.id_jenis', $id_jenis);
		}
		if($jenis != NULL){
			if(is_string($jenis)) $jenis = explode(",", $jenis);
			$this->db->where_in('tbl_inv_jenis.id_jenis', $jenis);
		}
		
		$this->db->where('tbl_inv_merk.del', $deleted);
		$this->db->order_by("tbl_inv_jenis.nama,tbl_inv_merk.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();
		
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_sn_os($conn = NULL, $id_kategori = NULL, $sn_os = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();


		$this->db->select('tbl_inv_aset.kode_barang');
		$this->db->select('tbl_inv_aset.sn_os');
		$this->db->from('tbl_inv_aset');
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->db->where('tbl_inv_jenis.nama', 'Operating System (OS)');
		$this->db->where('tbl_inv_aset.na', 'n');
		$this->db->where('tbl_inv_aset.sn_os is not null');
		$this->db->where("tbl_inv_aset.sn_os!='N/A'");
		if ($id_kategori !== NULL) {
			$this->db->where("tbl_inv_aset.sn_os not in (select tbl_inv_aset2.sn_os from tbl_inv_aset tbl_inv_aset2 where tbl_inv_aset2.id_kategori='$id_kategori' AND tbl_inv_aset2.na = 'n' AND tbl_inv_aset2.sn_os is not null AND tbl_inv_aset2.sn_os != 'N/A' AND tbl_inv_aset2.sn_os != '-' AND tbl_inv_aset2.sn_os !='0' AND tbl_inv_aset2.sn_os !='' AND tbl_inv_aset2.sn_os !=' -' AND tbl_inv_aset2.sn_os!='n/a' AND tbl_inv_aset2.sn_os!=' n/a' and tbl_inv_aset2.SN_OS!='$sn_os')");
		}
		$query  = $this->db->get();
		$result = $query->result();
		
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_sn_office($conn = NULL, $id_kategori = NULL, $sn_office = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();


		$this->db->select('tbl_inv_aset.kode_barang');
		$this->db->select('tbl_inv_aset.sn_office');
		$this->db->from('tbl_inv_aset');
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->db->where('tbl_inv_jenis.nama', 'Office Application');
		$this->db->where('tbl_inv_aset.na', 'n');
		$this->db->where('tbl_inv_aset.sn_office is not null');
		$this->db->where("tbl_inv_aset.sn_office!='N/A'");
		if ($id_kategori !== NULL) {
			$this->db->where("tbl_inv_aset.sn_office not in (select tbl_inv_aset2.sn_office from tbl_inv_aset tbl_inv_aset2 where tbl_inv_aset2.id_kategori='$id_kategori' AND tbl_inv_aset2.na = 'n' AND tbl_inv_aset2.sn_office is not null AND tbl_inv_aset2.sn_office != 'N/A' AND tbl_inv_aset2.sn_office != '-' AND tbl_inv_aset2.sn_office !='0' AND tbl_inv_aset2.sn_office !='' AND tbl_inv_aset2.sn_office !=' -' AND tbl_inv_aset2.sn_office!='n/a' AND tbl_inv_aset2.sn_office!=' n/a' and tbl_inv_aset2.sn_office!='$sn_office')");
		}
		$query  = $this->db->get();
		$result = $query->result();
		
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_merk_tipe($conn = NULL, $id_merk_tipe = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $id_merk = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_merk_tipe.*');
		$this->db->select('CASE
								WHEN tbl_inv_merk_tipe.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_merk_tipe');
		$this->db->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = tbl_inv_merk_tipe.id_merk AND tbl_inv_merk.na = \'n\'', 'left outer');				
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_merk.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');				
		if ($id_merk_tipe !== NULL) {
			$this->db->where('tbl_inv_merk_tipe.id_merk_tipe', $id_merk_tipe);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_merk_tipe.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_merk_tipe.nama', $nama);
		}
		if ($pengguna !== NULL) {
			$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if ($id_merk !== NULL) {
			$this->db->where('tbl_inv_merk.id_merk', $id_merk);
		}
		$this->db->where('tbl_inv_merk_tipe.del', $deleted);
		$this->db->order_by("tbl_inv_merk_tipe.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();
		
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_periode($conn = NULL, $id_periode = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $id_jenis = NULL, $squence = NULL, $pengguna = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_periode.*');
		$this->db->select('CASE
								WHEN tbl_inv_periode.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_periode');
		if ($id_periode !== NULL) {
			$this->db->where('tbl_inv_periode.id_periode', $id_periode);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_periode.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_periode.nama', $nama);
		}
		if ($id_jenis !== NULL) {
			$this->db->where('tbl_inv_periode.id_jenis', $id_jenis);
		}

		if ($pengguna !== NULL) {
			$this->db->where('tbl_inv_periode.pengguna', $pengguna);
		}

		if ($squence !== NULL) {
			$this->db->where("tbl_inv_periode.squence>$squence");
		}

		$this->db->where('tbl_inv_periode.del', $deleted);
		$this->db->order_by("tbl_inv_periode.squence", "asc");
		$query  = $this->db->get();
		$result = $query->result();
		
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_periode_detail($conn = NULL, $id_periode_detail = NULL, $active = NULL, $deleted = 'n', $id_periode = NULL, $id_jenis = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_periode_detail.*');
		$this->db->select('tbl_inv_jenis_detail.nama as nama_jenis_detail');
		$this->db->from('tbl_inv_periode_detail');
		$this->db->join('tbl_inv_jenis_detail', 'tbl_inv_jenis_detail.id_jenis_detail = tbl_inv_periode_detail.id_jenis_detail AND tbl_inv_jenis_detail.na = \'n\'', 'left outer');				
		if ($id_periode_detail !== NULL) {
			$this->db->where('tbl_inv_periode_detail.id_periode_detail', $id_periode_detail);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_periode_detail.na', $active);
		}
		if ($id_periode !== NULL) {
			$this->db->where('tbl_inv_periode_detail.id_periode', $id_periode);
		}
		if ($id_jenis !== NULL) {
			$this->db->where('tbl_inv_periode_detail.id_jenis', $id_jenis);
		}

		$this->db->where('tbl_inv_periode_detail.del', $deleted);
		$query  = $this->db->get();
		$result = $query->result();
		
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_depo($conn = NULL, $id_depo = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $id_pabrik = NULL, $id_lokasi = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select("
							ZKISSTT_0113.*,  
							(select tbl_inv_lokasi.nama from ".DB_PORTAL.".dbo.tbl_inv_lokasi where tbl_inv_lokasi.id_lokasi='$id_lokasi') as nama_lokasi
							FROM ".DB_DEFAULT.".dbo.ZKISSTT_0113  
							LEFT OUTER JOIN ".DB_PORTAL.".dbo.tbl_inv_pabrik ON tbl_inv_pabrik.kode = ZKISSTT_0113.ekorg COLLATE SQL_Latin1_General_CP1_CS_AS 
							AND tbl_inv_pabrik.id_pabrik='$id_pabrik' and tbl_inv_pabrik.na = 'n' 
						");
		if ($id_depo !== NULL) {
			$this->db->where('ZKISSTT_0113.DEPID', $id_depo);
		}
		if ($active !== NULL) {
			$this->db->where('ZKISSTT_0113.activ', 'X');
		}
		$this->db->order_by("ZKISSTT_0113.DEPNM", "asc");
		$query  = $this->db->get();
		$result = $query->result();
		
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_pabrik($conn = NULL, $id_pabrik = NULL, $active = NULL, $deleted = 'n', $nama = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_pabrik.*');
		$this->db->select('CASE
								WHEN tbl_inv_pabrik.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_pabrik');
		if ($id_pabrik !== NULL) {
			$this->db->where('tbl_inv_pabrik.id_pabrik', $id_pabrik);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_pabrik.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_pabrik.nama', $nama);
		}
		if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			if((base64_decode($this->session->userdata("-gsber-"))=='KJP1')or(base64_decode($this->session->userdata("-gsber-"))=='KJP2')){
				$this->db->where("tbl_inv_pabrik.kode in ('KJP1','KJP2')");
			}else{
				$this->db->where('tbl_inv_pabrik.kode', base64_decode($this->session->userdata("-gsber-")) );
			}
		}

		
		$this->db->where('tbl_inv_pabrik.del', $deleted);
		$this->db->order_by("nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_plat($conn = NULL, $kode = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_plat.*');
		$this->db->from('tbl_plat');
		if ($kode !== NULL) {
			$this->db->where('tbl_plat.kode', $kode);
		}
		$this->db->order_by("kode", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_satuan($conn = NULL, $id_satuan = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_satuan.*');
		$this->db->from('tbl_inv_satuan');
		if ($id_satuan !== NULL) {
			$this->db->where('tbl_inv_satuan.id_satuan', $id_satuan);
		}
		$this->db->order_by("nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_buzzer($conn = NULL, $id_buzzer = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_buzzer.*');
		$this->db->from('tbl_inv_buzzer');
		if ($id_buzzer !== NULL) {
			$this->db->where('tbl_inv_satuan.buzzer', $buzzer);
		}
		$this->db->order_by("id_buzzer", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_status($conn = NULL, $id_status = NULL, $active = NULL, $deleted = 'n', $nama = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_status.*');
		$this->db->select('CASE
								WHEN tbl_inv_status.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_status');
		if ($id_status !== NULL) {
			$this->db->where('tbl_inv_status.id_status', $id_status);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_status.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_status.nama', $nama);
		}
		$this->db->where('tbl_inv_status.del', $deleted);
		$this->db->where('tbl_inv_status.id_status!=', 3);
		$this->db->order_by("nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_kondisi($conn = NULL, $id_kondisi = NULL, $active = NULL, $deleted = 'n', $nama = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_kondisi.*');
		$this->db->select('CASE
								WHEN tbl_inv_kondisi.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_kondisi');
		if ($id_kondisi !== NULL) {
			$this->db->where('tbl_inv_kondisi.id_kondisi', $id_kondisi);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_kondisi.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_kondisi.nama', $nama);
		}
		$this->db->where('tbl_inv_kondisi.del', $deleted);
		$this->db->where('tbl_inv_kondisi.id_kondisi!=', 3);
		$this->db->order_by("tbl_inv_kondisi.id_kondisi", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_lokasi($conn = NULL, $id_lokasi = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_lokasi.*');
		$this->db->select('CASE
								WHEN tbl_inv_lokasi.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_lokasi');
		if ($id_lokasi !== NULL) {
			$this->db->where('tbl_inv_lokasi.id_lokasi', $id_lokasi);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_lokasi.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_lokasi.nama', $nama);
		}
		if ($pengguna !== NULL) {
			$this->db->where("CHARINDEX('$pengguna', tbl_inv_lokasi.pengguna)>0");
		}
		$this->db->where('tbl_inv_lokasi.del', $deleted);
		$this->db->order_by("nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_sub_lokasi($conn = NULL, $id_sub_lokasi = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $id_lokasi = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_sub_lokasi.*');
		$this->db->select('CASE
								WHEN tbl_inv_sub_lokasi.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_sub_lokasi');
		if ($id_sub_lokasi !== NULL) {
			$this->db->where('tbl_inv_sub_lokasi.id_sub_lokasi', $id_sub_lokasi);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_sub_lokasi.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_sub_lokasi.nama', $nama);
		}
		if ($id_lokasi !== NULL) {
			$this->db->where('tbl_inv_sub_lokasi.id_lokasi', $id_lokasi);
		}
		$this->db->where('tbl_inv_sub_lokasi.del', $deleted);
		$this->db->order_by("nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_area($conn = NULL, $id_area = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $id_sub_lokasi = NULL, $lokasi = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_area.*');
		$this->db->select('tbl_inv_lokasi.nama as nama_lokasi');
		$this->db->select('CASE
								WHEN tbl_inv_area.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_inv_area');
		$this->db->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_inv_area.id_sub_lokasi AND tbl_inv_sub_lokasi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_sub_lokasi.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left outer');		
		if ($id_area !== NULL) {
			$this->db->where('tbl_inv_area.id_area', $id_area);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_area.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_area.nama', $nama);
		}
		if ($id_sub_lokasi !== NULL) {
			$this->db->where('tbl_inv_area.id_sub_lokasi', $id_sub_lokasi);
		}
		if($lokasi != NULL){
			if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			$this->db->where_in('tbl_inv_lokasi.id_lokasi', $lokasi);
		}
		
		$this->db->where('tbl_inv_area.del', $deleted);
		$this->db->order_by("tbl_inv_lokasi.nama, tbl_inv_area.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	//diganti dengan view sql
	function get_data_aset_bom_old($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $jam_mulai=NULL, $jam_selesai=NULL, $umur_mulai=NULL, $umur_selesai=NULL, $overdue=NULL, $kondisi=NULL, $status=NULL, $berat=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("tbl_inv_aset.id_aset");
		$this->datatables->select("tbl_inv_aset.id_kategori");
		$this->datatables->select("tbl_inv_aset.nomor");
		$this->datatables->select("tbl_inv_aset.kode_barang");
		$this->datatables->select("tbl_inv_aset.nomor_sap");
		$this->datatables->select("tbl_inv_aset.nomor_rangka");
		$this->datatables->select("tbl_inv_aset.nomor_mesin");
		$this->datatables->select("tbl_inv_aset.nomor_polisi");
		$this->datatables->select("tbl_inv_aset.tipe_aset");
		$this->datatables->select("tbl_inv_aset.id_kondisi");
		$this->datatables->select("convert(varchar, tbl_inv_aset.tanggal_perolehan, 104) as tanggal_perolehan");
		
		$this->datatables->select("tbl_inv_aset.nama_user");
		$this->datatables->select("tbl_inv_aset.nama_vendor");
		// $this->datatables->select("tbl_inv_aset.tanggal_edit");
		$this->datatables->select("CONVERT(varchar(11),tbl_inv_aset.tanggal_edit,104) as tanggal_edit");
		$this->datatables->select("tbl_inv_aset.na");
		
		$this->datatables->select('tbl_inv_kategori.nama as nama_kategori');
		$this->datatables->select('tbl_inv_jenis.nama as nama_jenis');
		$this->datatables->select('tbl_inv_merk.nama as nama_merk');
		$this->datatables->select('tbl_inv_merk_tipe.nama as nama_merk_tipe');
		$this->datatables->select('tbl_inv_status.nama as nama_status');
		$this->datatables->select('tbl_inv_kondisi.nama as nama_kondisi');
		$this->datatables->select('tbl_inv_pabrik.nama as nama_pabrik');
		$this->datatables->select('tbl_inv_lokasi.nama as nama_lokasi');
		$this->datatables->select('tbl_inv_sub_lokasi.nama as nama_sub_lokasi');
		$this->datatables->select('tbl_inv_area.nama as nama_area');
		
		$this->datatables->select('tbl_inv_aset.tipe_aset as cop');
	

		// // $this->datatables->select('tbl_karyawan.nama as nama_karyawan');
		// $this->datatables->select('(select ZKISSTT_0113.DEPNM from '.DB_DEFAULT.'.dbo.ZKISSTT_0113 where ZKISSTT_0113.DEPID=tbl_inv_aset.id_depo) as nama_depo');
		// $this->datatables->select("
							// CAST( 
							// ( 
							  // SELECT tbl_karyawan.nama + RTRIM(',') 
								// FROM tbl_karyawan 
							   // WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_inv_aset.pic, RTRIM(','),''',''')+'''') > 0 
							  // FOR XML PATH ('') 
							// ) as VARCHAR(MAX) 
							// ) as nama_pic
		// ");
		// $this->datatables->select('CASE
								// WHEN tbl_inv_aset.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								// ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   // END as label_active');
		// //untuk asset FO				   
		if($pengguna=='fo'){
			//xx
			$this->datatables->select("tbl_inv_aset.jam_jalan_terakhir as jam_jalan");
			// $this->datatables->select("CONVERT(VARCHAR, ISNULL((select top 1 tbl_inv_main.jam_jalan from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset and tbl_inv_main.na='n' order by tbl_inv_main.jam_jalan desc),0))as jam_jalan");
			// $this->datatables->select('CASE
									// WHEN (select top 1 tbl_inv_periode.nama from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc)) is null 
									// THEN (select top 1 tbl_inv_periode.nama from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>0)
									// ELSE (select top 1 tbl_inv_periode.nama from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc))
							   // END as service_next');
			// $this->datatables->select('CASE
									// WHEN (select top 1 tbl_inv_periode.jam from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc)) is null 
									// THEN (select top 1 tbl_inv_periode.jam from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>0)
									// ELSE (select top 1 tbl_inv_periode.jam from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc))
							   // END as jam_jalan_next');
			// $this->datatables->select("datediff(month,tbl_inv_aset.tanggal_perolehan,getdate()) as umur_aset");
			// $this->datatables->select('CASE
									// WHEN (select top 1 tbl_inv_main.tanggal_buat from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_main.tanggal_buat desc) is null 
									// THEN tbl_inv_aset.tanggal_perolehan
									// ELSE (select top 1 tbl_inv_main.tanggal_buat from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_main.tanggal_buat desc)
							   // END as tanggal_ck');
			// $this->datatables->select("(select top 1 tbl_inv_periode.bulan from tbl_inv_main left join tbl_inv_periode on tbl_inv_periode.id_periode=tbl_inv_main.id_periode where tbl_inv_main.id_aset=tbl_inv_aset.id_aset and tbl_inv_main.na='n' order by tbl_inv_main.id_periode desc)as main_bulan");
			// $this->datatables->select("(select top 1 tbl_inv_periode.bulan from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc)) as periode_bulan");
			
			//apar
			$this->datatables->select("CASE
									WHEN (select top 1 tbl_inv_maintenance.tanggal_buat from tbl_inv_maintenance where tbl_inv_maintenance.id_aset=tbl_inv_aset.id_aset order by tbl_inv_maintenance.tanggal_buat desc) is null 
									THEN convert(varchar, tbl_inv_aset.tanggal_perolehan, 104)
									ELSE (select top 1 convert(varchar, tbl_inv_maintenance.tanggal_buat, 104) from tbl_inv_maintenance where tbl_inv_maintenance.id_aset=tbl_inv_aset.id_aset order by tbl_inv_maintenance.tanggal_buat desc)
							   END as tanggal_ck_lab");
			$this->datatables->select('CASE
									WHEN (select top 1 tbl_inv_maintenance.tanggal_buat from tbl_inv_maintenance where tbl_inv_maintenance.id_aset=tbl_inv_aset.id_aset order by tbl_inv_maintenance.tanggal_buat desc) is null 
									THEN convert(varchar, DATEADD(year,tbl_inv_jenis.periode,tbl_inv_aset.tanggal_perolehan), 104)
									ELSE convert(varchar, DATEADD(year,tbl_inv_jenis.periode,(select top 1 tbl_inv_maintenance.tanggal_buat from tbl_inv_maintenance where tbl_inv_maintenance.id_aset=tbl_inv_aset.id_aset order by tbl_inv_maintenance.tanggal_buat desc)), 104)
							   END as tanggal_next_lab');
			
			
		}
		// //untuk asset HRGA
		// if($pengguna=='hrga'){
			// $this->datatables->select("
								// CAST( 
								// ( 
								  // SELECT COUNT(tbl_inv_doc.id_inv_doc)
									// FROM tbl_inv_doc 
								   // WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_inv_aset.id_jenis)+'''',''''+REPLACE(tbl_inv_doc.jenis, RTRIM('.'),''',''')+'''') > 0 
								  // FOR XML PATH ('') 
								// ) as VARCHAR(MAX) 
								// ) as total_dokumen
			// ");
			// $this->datatables->select("(select count(tbl_inv_doc_transaksi.id_inv_doc_transaksi) from tbl_inv_doc_transaksi where tbl_inv_doc_transaksi.id_aset=tbl_inv_aset.id_aset and tbl_inv_doc_transaksi.na='n') as jumlah_dokumen");
			// $this->datatables->select("(select COUNT(*) as jumlah from tbl_inv_doc_transaksi t left join tbl_inv_doc d on d.id_inv_doc=t.id_inv_doc where DATEDIFF(day, getdate(), t.tanggal_berakhir)<='0' and t.id_aset=tbl_inv_aset.id_aset and d.doc_expired='1' and t.na='n') as jumlah_expired");
		// }
		//untuk asset IT
		if($pengguna=='it'){
			$this->datatables->select("tbl_inv_aset.pic");
			$this->datatables->select("tbl_karyawan.nama as nama_pic");
		}
		
		$this->datatables->from("tbl_inv_aset");
		$this->datatables->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_aset.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = tbl_inv_aset.id_merk AND tbl_inv_merk.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_merk_tipe', 'tbl_inv_merk_tipe.id_merk_tipe = tbl_inv_aset.id_merk_tipe AND tbl_inv_merk_tipe.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = tbl_inv_aset.id_pabrik AND tbl_inv_pabrik.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_aset.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_inv_aset.id_sub_lokasi AND tbl_inv_sub_lokasi.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_area', 'tbl_inv_area.id_area = tbl_inv_aset.id_area AND tbl_inv_area.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_kondisi', 'tbl_inv_kondisi.id_kondisi = tbl_inv_aset.id_kondisi AND tbl_inv_kondisi.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_status', 'tbl_inv_status.id_status = tbl_inv_aset.id_status AND tbl_inv_status.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_inv_aset.pic AND tbl_karyawan.na = \'n\'', 'left outer');		

		if ($id_aset !== NULL) {
			$this->datatables->where('tbl_inv_aset.id_aset', $id_aset);
		}
		if ($active !== NULL) {
			$this->datatables->where('tbl_inv_aset.na', $active);
		}
		if ($nama !== NULL) {
			$this->datatables->where('tbl_inv_aset.nomor_sap', $nama);
		}
		if ($pengguna !== NULL) {
			$this->datatables->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if($jenis != NULL){
			if(is_string($jenis)) $jenis = explode(",", $jenis);
			$this->datatables->where_in('tbl_inv_jenis.id_jenis', $jenis);
		}
		if($merk != NULL){
			if(is_string($merk)) $merk = explode(",", $merk);
			$this->datatables->where_in('tbl_inv_merk.id_merk', $merk);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->datatables->where_in('tbl_inv_pabrik.id_pabrik', $pabrik);
		}
		if($lokasi != NULL){
			if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			$this->datatables->where_in('tbl_inv_lokasi.id_lokasi', $lokasi);
		}
		if($area != NULL){
			if(is_string($area)) $area = explode(",", $area);
			$this->datatables->where_in('tbl_inv_area.id_area', $area);
		}
		if(($jam_mulai != NULL)and($jam_selesai != NULL)and($pengguna='fo')){
			$this->datatables->where("jam_jalan<='$jam_mulai' and  jam_jalan>='$jam_selesai'");
		}
		if(($umur_mulai != NULL)and($umur_selesai != NULL)and($pengguna='fo')){
			$this->datatables->where("umur_aset<='$umur_mulai' and  umur_aset>='$umur_selesai'");
		}
		if($overdue != NULL){
			if(is_string($overdue)) $overdue = explode(",", $overdue);
			foreach($overdue as $dt) {
				if($dt=='jam'){
					$this->datatables->where("jam_jalan_next-jam_jalan < 0");		
				}
				if($dt=='bulan'){
					$this->datatables->where("periode_bulan-main_bulan < 0");		
				}
			}			
		}
		if($kondisi != NULL){
			if(is_string($kondisi)) $kondisi = explode(",", $kondisi);
			$this->datatables->where_in('tbl_inv_aset.id_kondisi', $kondisi);
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->datatables->where_in('tbl_inv_aset.na', $status);
		}
		if (($berat !== NULL)and($berat=='berat')) {
			$this->datatables->where("tbl_inv_jenis.id_kategori!='5'");
		}
		if (($berat !== NULL)and($berat=='lab')) {
			$this->datatables->where("tbl_inv_jenis.id_kategori='5'");
		}
		if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			$this->datatables->where("tbl_inv_aset.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
		}
		if($pengguna=='it'){
			$this->datatables->where("tbl_inv_aset.na='n'");
		}
		
		$this->datatables->where("tbl_inv_aset.id_pabrik!=''");
		// $this->datatables->where("tbl_inv_aset.id_pabrik='1'");

		if ($conn !== NULL)
			$this->general->closeDb();

		// return $this->datatables->generate();
		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_aset"));
		return $this->general->jsonify($raw);
		
	}

	//diganti dengan view sql
	function get_data_aset_bom2($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $jam_mulai=NULL, $jam_selesai=NULL, $umur_mulai=NULL, $umur_selesai=NULL, $overdue=NULL, $kondisi=NULL, $status=NULL, $berat=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		
	
		$this->datatables->select("id_aset");
		$this->datatables->select("nomor");
		$this->datatables->from("vw_asset");
		
		// $this->datatables->select("tbl_inv_aset.id_aset");
		// $this->datatables->select("tbl_inv_aset.id_kategori");
		// $this->datatables->select("tbl_inv_aset.nomor");
		// $this->datatables->select("tbl_inv_aset.kode_barang");
		// $this->datatables->select("tbl_inv_aset.nomor_sap");
		// $this->datatables->select("tbl_inv_aset.nomor_rangka");
		// $this->datatables->select("tbl_inv_aset.nomor_mesin");
		// $this->datatables->select("tbl_inv_aset.nomor_polisi");
		// $this->datatables->select("tbl_inv_aset.tipe_aset");
		// $this->datatables->select("tbl_inv_aset.id_kondisi");
		// $this->datatables->select("convert(varchar, tbl_inv_aset.tanggal_perolehan, 104) as tanggal_perolehan");
		
		// $this->datatables->select("tbl_inv_aset.nama_user");
		// $this->datatables->select("tbl_inv_aset.nama_vendor");
		// $this->datatables->select("CONVERT(varchar(11),tbl_inv_aset.tanggal_edit,104) as tanggal_edit");
		// $this->datatables->select("tbl_inv_aset.na");
		
		// $this->datatables->select('tbl_inv_kategori.nama as nama_kategori');
		// $this->datatables->select('tbl_inv_jenis.nama as nama_jenis');
		// $this->datatables->select('tbl_inv_merk.nama as nama_merk');
		// $this->datatables->select('tbl_inv_merk_tipe.nama as nama_merk_tipe');
		// $this->datatables->select('tbl_inv_status.nama as nama_status');
		// $this->datatables->select('tbl_inv_kondisi.nama as nama_kondisi');
		// $this->datatables->select('tbl_inv_pabrik.nama as nama_pabrik');
		// $this->datatables->select('tbl_inv_lokasi.nama as nama_lokasi');
		// $this->datatables->select('tbl_inv_sub_lokasi.nama as nama_sub_lokasi');
		// $this->datatables->select('tbl_inv_area.nama as nama_area');
		// $this->datatables->select('tbl_inv_aset.tipe_aset as cop');
		// $this->datatables->from("tbl_inv_aset");
		// $this->datatables->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_aset.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = tbl_inv_aset.id_merk AND tbl_inv_merk.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_inv_merk_tipe', 'tbl_inv_merk_tipe.id_merk_tipe = tbl_inv_aset.id_merk_tipe AND tbl_inv_merk_tipe.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = tbl_inv_aset.id_pabrik AND tbl_inv_pabrik.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_aset.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_inv_aset.id_sub_lokasi AND tbl_inv_sub_lokasi.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_inv_area', 'tbl_inv_area.id_area = tbl_inv_aset.id_area AND tbl_inv_area.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_inv_kondisi', 'tbl_inv_kondisi.id_kondisi = tbl_inv_aset.id_kondisi AND tbl_inv_kondisi.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_inv_status', 'tbl_inv_status.id_status = tbl_inv_aset.id_status AND tbl_inv_status.na = \'n\'', 'left outer');		
		// $this->datatables->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_inv_aset.pic AND tbl_karyawan.na = \'n\'', 'left outer');		

		// if ($id_aset !== NULL) {
			// $this->datatables->where('tbl_inv_aset.id_aset', $id_aset);
		// }
		// if ($active !== NULL) {
			// $this->datatables->where('tbl_inv_aset.na', $active);
		// }
		// if ($nama !== NULL) {
			// $this->datatables->where('tbl_inv_aset.nomor_sap', $nama);
		// }
		// if ($pengguna !== NULL) {
			// $this->datatables->where('tbl_inv_jenis.pengguna', $pengguna);
		// }
		// if($jenis != NULL){
			// if(is_string($jenis)) $jenis = explode(",", $jenis);
			// $this->datatables->where_in('tbl_inv_jenis.id_jenis', $jenis);
		// }
		// if($merk != NULL){
			// if(is_string($merk)) $merk = explode(",", $merk);
			// $this->datatables->where_in('tbl_inv_merk.id_merk', $merk);
		// }
		// if($pabrik != NULL){
			// if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			// $this->datatables->where_in('tbl_inv_pabrik.id_pabrik', $pabrik);
		// }
		// if($lokasi != NULL){
			// if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			// $this->datatables->where_in('tbl_inv_lokasi.id_lokasi', $lokasi);
		// }
		// if($area != NULL){
			// if(is_string($area)) $area = explode(",", $area);
			// $this->datatables->where_in('tbl_inv_area.id_area', $area);
		// }
		// if(($jam_mulai != NULL)and($jam_selesai != NULL)and($pengguna='fo')){
			// $this->datatables->where("jam_jalan<='$jam_mulai' and  jam_jalan>='$jam_selesai'");
		// }
		// if(($umur_mulai != NULL)and($umur_selesai != NULL)and($pengguna='fo')){
			// $this->datatables->where("umur_aset<='$umur_mulai' and  umur_aset>='$umur_selesai'");
		// }
		// if($overdue != NULL){
			// if(is_string($overdue)) $overdue = explode(",", $overdue);
			// foreach($overdue as $dt) {
				// if($dt=='jam'){
					// $this->datatables->where("jam_jalan_next-jam_jalan < 0");		
				// }
				// if($dt=='bulan'){
					// $this->datatables->where("periode_bulan-main_bulan < 0");		
				// }
			// }			
		// }
		// if($kondisi != NULL){
			// if(is_string($kondisi)) $kondisi = explode(",", $kondisi);
			// $this->datatables->where_in('tbl_inv_aset.id_kondisi', $kondisi);
		// }
		// if($status != NULL){
			// if(is_string($status)) $status = explode(",", $status);
			// $this->datatables->where_in('tbl_inv_aset.na', $status);
		// }
		// if (($berat !== NULL)and($berat=='berat')) {
			// $this->datatables->where("tbl_inv_jenis.id_kategori!='5'");
		// }
		// if (($berat !== NULL)and($berat=='lab')) {
			// $this->datatables->where("tbl_inv_jenis.id_kategori='5'");
		// }
		// if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			// $this->datatables->where("tbl_inv_aset.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
		// }
		// if($pengguna=='it'){
			// $this->datatables->where("tbl_inv_aset.na='n'");
		// }
		
		// $this->datatables->where("tbl_inv_aset.id_pabrik!=''");
		// // $this->datatables->where("tbl_inv_aset.id_pabrik='1'");

		if ($conn !== NULL)
			$this->general->closeDb();

		// return $this->datatables->generate();
		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		// $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_aset"));
		return $this->general->jsonify($raw);
		
	}
	function get_data_aset_bom($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $jam_mulai=NULL, $jam_selesai=NULL, $umur_mulai=NULL, $umur_selesai=NULL, $overdue=NULL, $kondisi=NULL, $status=NULL, $berat=NULL, $flag=NULL, $problem=NULL, $id_merk_tipe=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("id_aset");
		$this->datatables->select("id_kategori");
		$this->datatables->select("nomor");
		$this->datatables->select("kode_barang");
		$this->datatables->select("nomor_sap");
		$this->datatables->select("nomor_rangka");
		$this->datatables->select("nomor_mesin");
		$this->datatables->select("nomor_polisi");
		$this->datatables->select("tipe_aset");
		$this->datatables->select("id_kondisi");
		$this->datatables->select("pic");
		$this->datatables->select("nama_pic");
		$this->datatables->select("pic_detail");
		$this->datatables->select("id_jenis");
		$this->datatables->select("id_merk");
		$this->datatables->select("id_pabrik");
		$this->datatables->select("id_lokasi");
		$this->datatables->select("id_area");
		$this->datatables->select("pengguna");
		$this->datatables->select("berat");
		$this->datatables->select("tanggal_perolehan");
		$this->datatables->select("nama_user");
		$this->datatables->select("nama_vendor");
		$this->datatables->select("tanggal_edit");
		$this->datatables->select("na");
		$this->datatables->select("nama_kategori");
		$this->datatables->select("nama_jenis");
		$this->datatables->select("nama_merk");
		$this->datatables->select("nama_merk_tipe");
		$this->datatables->select("nama_status");
		$this->datatables->select("nama_kondisi");
		$this->datatables->select("nama_pabrik");
		$this->datatables->select("nama_lokasi");
		$this->datatables->select("nama_sub_lokasi");
		$this->datatables->select("nama_area");
		$this->datatables->select("cop");
		$this->datatables->select("jam_jalan");
		$this->datatables->select("service_next");
		$this->datatables->select("jam_jalan_next");
		$this->datatables->select("tanggal_ck");
		$this->datatables->select("umur_aset");
		$this->datatables->select("main_bulan");
		$this->datatables->select("periode_bulan");
		$this->datatables->select("tanggal_next_lab");
		$this->datatables->select("tanggal_ck_lab");
		$this->datatables->select("aging");
		$this->datatables->select("total_dokumen");
		$this->datatables->select("ratio");
		$this->datatables->select("kerusakan");
		$this->datatables->select("id_kerusakan");
		$this->datatables->select("gambar_fo");
		$this->datatables->select("kategori");
		$this->datatables->select("have_ratio");
		$this->datatables->from("vw_asset");
		if ($id_aset !== NULL) {
			$this->datatables->where('id_aset', $id_aset);
		}
		if ($nama !== NULL) {
			$this->datatables->where('nomor_sap', $nama);
		}
		if ($pengguna !== NULL) {
			$this->datatables->where('pengguna', $pengguna);
		}
		if($jenis != NULL){
			if(is_string($jenis)) $jenis = explode(",", $jenis);
			$this->datatables->where_in('id_jenis', $jenis);
		}
		if($merk != NULL){
			if(is_string($merk)) $merk = explode(",", $merk);
			$this->datatables->where_in('id_merk', $merk);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->datatables->where_in('id_pabrik', $pabrik);
		}
		if($lokasi != NULL){
			if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			$this->datatables->where_in('id_lokasi', $lokasi);
		}
		if($area != NULL){
			if(is_string($area)) $area = explode(",", $area);
			$this->datatables->where_in('id_area', $area);
		}
		if(($jam_mulai != NULL)and($jam_selesai != NULL)and($pengguna='fo')){
			$this->datatables->where("jam_jalan<='$jam_mulai' and  jam_jalan>='$jam_selesai'");
		}
		if(($umur_mulai != NULL)and($umur_selesai != NULL)and($pengguna='fo')){
			$this->datatables->where("umur_aset<='$umur_mulai' and  umur_aset>='$umur_selesai'");
		}
		if($overdue != NULL){
			if(is_string($overdue)) $overdue = explode(",", $overdue);
			foreach($overdue as $dt) {
				if($dt=='jam'){
					$this->datatables->where("jam_jalan_next-jam_jalan < 0");		
				}
				if($dt=='bulan'){
					$this->datatables->where("periode_bulan-main_bulan < 0");		
				}
			}			
		}
		if($kondisi != NULL){
			if(is_string($kondisi)) $kondisi = explode(",", $kondisi);
			$this->datatables->where_in('id_kondisi', $kondisi);
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->datatables->where_in('na', $status);
		}
		if (($berat !== NULL)and($berat=='berat')) {
			$this->datatables->where("berat='y'");
			// $this->datatables->where("id_kategori!='5'");
		}
		if (($berat !== NULL)and($berat=='lab')) {
			$this->datatables->where("berat!='y'");
			// $this->datatables->where("id_kategori='5'");
		}
		if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			if((base64_decode($this->session->userdata("-gsber-"))=='KJP1')or(base64_decode($this->session->userdata("-gsber-"))=='KJP2')){
				$this->datatables->where("id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode in('KJP1','KJP2'))");
			}else{
				$this->datatables->where("id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
			}
		}
		if($pengguna=='it'){
			$this->datatables->where("na='n'");
		}
		if($flag != NULL){
			if(is_string($flag)) $flag = explode(",", $flag);
			$this->datatables->where_in('flag', $flag);
		}
		
		$this->datatables->where("id_pabrik!=''");
		// $this->datatables->where("tbl_inv_aset.id_pabrik='1'");
		
		//lha cr-2314
		if(($problem != NULL)and($problem != 0)){
			if($problem==1){
				$this->datatables->where("(kode_barang='N/A' or kode_barang='' or kode_barang is null)");
			}
			if($problem==2){
				$this->datatables->where("jumlah_kode_barang>=2");
			}
			if($problem==3){
				$this->datatables->where("jumlah_nomor_sap>=2");
			}
			if($problem==4){
				$this->datatables->where("(nomor_sap='N/A' or nomor_sap='' or nomor_sap is null)");
			}
			
		}
		if(($id_merk_tipe != NULL)and($id_merk_tipe != 0)){
			$this->datatables->where("id_merk_tipe='$id_merk_tipe'");
		}
		
						 
		if ($conn !== NULL)
			$this->general->closeDb();

		// return $this->datatables->generate();
		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_aset","id_jenis"));
		return $this->general->jsonify($raw);
		
	}
	
	function get_data_aset_bom_excel($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $jam_mulai=NULL, $jam_selesai=NULL, $umur_mulai=NULL, $umur_selesai=NULL, $overdue=NULL, $kondisi=NULL, $status=NULL, $berat=NULL, $flag=NULL, $problem=NULL, $id_merk_tipe=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("*");
		$this->db->from("vw_asset");
		if ($id_aset !== NULL) {
			$this->db->where('id_aset', $id_aset);
		}
		if ($nama !== NULL) {
			$this->db->where('nomor_sap', $nama);
		}
		if ($pengguna !== NULL) {
			$this->db->where('pengguna', $pengguna);
		}
		if($jenis != NULL){
			if(is_string($jenis)) $jenis = explode(",", $jenis);
			$this->db->where_in('id_jenis', $jenis);
		}
		if($merk != NULL){
			if(is_string($merk)) $merk = explode(",", $merk);
			$this->db->where_in('id_merk', $merk);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->db->where_in('id_pabrik', $pabrik);
		}
		if($lokasi != NULL){
			if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			$this->db->where_in('id_lokasi', $lokasi);
		}
		if($area != NULL){
			if(is_string($area)) $area = explode(",", $area);
			$this->db->where_in('id_area', $area);
		}
		if(($jam_mulai != NULL)and($jam_selesai != NULL)and($pengguna='fo')){
			$this->db->where("jam_jalan<='$jam_mulai' and  jam_jalan>='$jam_selesai'");
		}
		if(($umur_mulai != NULL)and($umur_selesai != NULL)and($pengguna='fo')){
			$this->db->where("umur_aset<='$umur_mulai' and  umur_aset>='$umur_selesai'");
		}
		if($overdue != NULL){
			if(is_string($overdue)) $overdue = explode(",", $overdue);
			foreach($overdue as $dt) {
				if($dt=='jam'){
					$this->db->where("jam_jalan_next-jam_jalan < 0");		
				}
				if($dt=='bulan'){
					$this->db->where("periode_bulan-main_bulan < 0");		
				}
			}			
		}
		if($kondisi != NULL){
			if(is_string($kondisi)) $kondisi = explode(",", $kondisi);
			$this->db->where_in('id_kondisi', $kondisi);
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->db->where_in('na', $status);
		}
		if (($berat !== NULL)and($berat=='berat')) {
			$this->db->where("berat='y'");
			// $this->db->where("id_kategori!='5'");
		}
		if (($berat !== NULL)and($berat=='lab')) {
			$this->db->where("berat!='y'");
			// $this->db->where("id_kategori='5'");
		}
		if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			$this->db->where("id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
		}
		if($pengguna=='it'){
			$this->db->where("na='n'");
		}
		
		$this->db->where("id_pabrik!=''");
		// $this->db->where("tbl_inv_aset.id_pabrik='1'");

		//lha cr-2314
		if($problem != NULL){
			if($problem==1){
				$this->db->where("(kode_barang='N/A' or kode_barang='' or kode_barang is null)");
			}
			if($problem==2){
				$this->db->where("jumlah_kode_barang>=2");
			}
			if($problem==3){
				$this->db->where("jumlah_nomor_sap>=2");
			}
			if($problem==4){
				$this->db->where("(nomor_sap='N/A' or nomor_sap='' or nomor_sap is null)");
			}
			
		}
		if($id_merk_tipe != NULL){
			$this->db->where("id_merk_tipe='$id_merk_tipe'");
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		$query  = $this->db->get();
		$result = $query->result();
		return $result;
	}
	
	function get_data_aset($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $kondisi=NULL, $status=NULL, $ratio=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_aset.*');
		$this->db->select("
							CAST( 
							( 
							  SELECT tbl_karyawan.nama + RTRIM(',') 
								FROM tbl_karyawan 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_inv_aset.pic, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as nama_pic
		");
		$this->db->select('CASE
								WHEN tbl_inv_aset.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('tbl_inv_kategori.nama as nama_kategori');
		$this->db->select('tbl_inv_jenis.nama as nama_jenis');
		$this->db->select('tbl_inv_jenis.berat');
		$this->db->select('tbl_inv_merk.nama as nama_merk');
		$this->db->select('tbl_inv_merk_tipe.nama as nama_merk_tipe');
		$this->db->select('tbl_inv_status.nama as nama_status');
		$this->db->select('tbl_inv_kondisi.nama as nama_kondisi');
		$this->db->select('CASE
								WHEN tbl_inv_kondisi.id_kondisi = \'1\' THEN \'<span class="label label-success">Beroperasi</span>\'
								WHEN tbl_inv_kondisi.id_kondisi = \'2\' THEN \'<span class="label label-danger">Tidak Beroperasi</span>\'
								WHEN tbl_inv_kondisi.id_kondisi = \'4\' THEN \'<span class="label label-warning">Dalam Perbaikan</span>\'
								WHEN tbl_inv_kondisi.id_kondisi = \'5\' THEN \'<span class="label label-danger">Scrap</span>\'
								WHEN tbl_inv_kondisi.id_kondisi = \'6\' THEN \'<span class="label label-primary">Standby</span>\'
								ELSE \'<span class="label label-danger">Tidak Beroperasi</span>\'
						   END as label_nama_kondisi');
		$this->db->select('tbl_inv_pabrik.nama as nama_pabrik');
		$this->db->select('tbl_inv_lokasi.nama as nama_lokasi');
		$this->db->select('tbl_inv_sub_lokasi.nama as nama_sub_lokasi');
		$this->db->select('tbl_inv_area.nama as nama_area');
		$this->db->select('tbl_inv_aset.tipe_aset as cop');
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		// $this->db->select('(select ZKISSTT_0113.DEPNM from '.DB_DEFAULT.'.dbo.ZKISSTT_0113 where ZKISSTT_0113.DEPID=tbl_inv_aset.id_depo) as nama_depo');
		$this->db->select('CASE
								WHEN tbl_inv_kondisi.id_kondisi = \'1\' THEN \'<span class="label label-success">Beroperasi</span>\'
								ELSE \'<span class="label label-danger">Tidak Beroperasi</span>\'
						   END as label_kondisi');
		//untuk asset FO				   
		if($pengguna=='fo'){
			$this->db->select("CONVERT(VARCHAR, ISNULL((select top 1 tbl_inv_main.jam_jalan from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset and tbl_inv_main.na='n' order by tbl_inv_main.jam_jalan desc),0))as jam_jalan");
			$this->db->select('CASE
									WHEN (select top 1 tbl_inv_periode.nama from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc)) is null 
									THEN (select top 1 tbl_inv_periode.nama from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>0)
									ELSE (select top 1 tbl_inv_periode.nama from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc))
							   END as service_next');
			$this->db->select('CASE
									WHEN (select top 1 tbl_inv_periode.jam from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc)) is null 
									THEN (select top 1 tbl_inv_periode.jam from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>0)
									ELSE (select top 1 tbl_inv_periode.jam from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc))
							   END as jam_jalan_next');
			$this->db->select("datediff(month,tbl_inv_aset.tanggal_perolehan,getdate()) as umur_aset");
			$this->db->select('CASE
									WHEN (select top 1 tbl_inv_main.tanggal_buat from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_main.tanggal_buat desc) is null 
									THEN tbl_inv_aset.tanggal_perolehan
									ELSE (select top 1 tbl_inv_main.tanggal_buat from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_main.tanggal_buat desc)
							   END as tanggal_ck');
			// $this->db->select('CASE
									// WHEN (select top 1 tbl_inv_main.tanggal_buat from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_main.tanggal_buat desc) is null 
									// THEN tbl_inv_aset.tanggal_perolehan
									// ELSE (select top 1 tbl_inv_main.tanggal_buat from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_main.tanggal_buat desc)
							   // END as tanggal_next');
							   
			$this->db->select("(select top 1 tbl_inv_periode.bulan from tbl_inv_main left join tbl_inv_periode on tbl_inv_periode.id_periode=tbl_inv_main.id_periode where tbl_inv_main.id_aset=tbl_inv_aset.id_aset and tbl_inv_main.na='n' order by tbl_inv_main.id_periode desc)as main_bulan");
			$this->db->select("(select top 1 tbl_inv_periode.bulan from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_aset.id_jenis and tbl_inv_periode.id_periode>(select top 1 tbl_inv_main.id_periode from tbl_inv_main where tbl_inv_main.id_aset=tbl_inv_aset.id_aset order by tbl_inv_periode.squence desc)) as periode_bulan");
			$this->db->select("tbl_inv_aset.ratio");
			$this->db->select("tbl_inv_aset.gambar_fo");
			$this->db->select("tbl_inv_aset.id_kerusakan");
			$this->db->select("tbl_inv_kerusakan.kerusakan");
			$this->db->select("tbl_inv_kategori.nama kategori");
		}
		//untuk asset HRGA
		if($pengguna=='hrga'){
			$this->db->select("
								CAST( 
								( 
								  SELECT COUNT(tbl_inv_doc.id_inv_doc)
									FROM tbl_inv_doc 
								   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_inv_aset.id_jenis)+'''',''''+REPLACE(tbl_inv_doc.jenis, RTRIM('.'),''',''')+'''') > 0 
								  FOR XML PATH ('') 
								) as VARCHAR(MAX) 
								) as total_dokumen
			");
			$this->db->select("(select count(tbl_inv_doc_transaksi.id_inv_doc_transaksi) from tbl_inv_doc_transaksi where tbl_inv_doc_transaksi.id_aset=tbl_inv_aset.id_aset and tbl_inv_doc_transaksi.na='n') as jumlah_dokumen");
			$this->db->select("(select COUNT(*) as jumlah from tbl_inv_doc_transaksi t left join tbl_inv_doc d on d.id_inv_doc=t.id_inv_doc where DATEDIFF(day, getdate(), t.tanggal_berakhir)<='0' and t.id_aset=tbl_inv_aset.id_aset and d.doc_expired='1' and t.na='n') as jumlah_expired");
			// $this->db->select("(select tbl_inv_main.squence from tbl_inv_main where tbl_inv_main.id) as squence");
		}
		$this->db->select('tbl_inv_jenis.have_ratio');
		$this->db->from('tbl_inv_aset');
		$this->db->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_aset.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = tbl_inv_aset.id_merk AND tbl_inv_merk.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_merk_tipe', 'tbl_inv_merk_tipe.id_merk_tipe = tbl_inv_aset.id_merk_tipe AND tbl_inv_merk_tipe.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = tbl_inv_aset.id_pabrik AND tbl_inv_pabrik.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_aset.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_inv_aset.id_sub_lokasi AND tbl_inv_sub_lokasi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_area', 'tbl_inv_area.id_area = tbl_inv_aset.id_area AND tbl_inv_area.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_kondisi', 'tbl_inv_kondisi.id_kondisi = tbl_inv_aset.id_kondisi AND tbl_inv_kondisi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_status', 'tbl_inv_status.id_status = tbl_inv_aset.id_status AND tbl_inv_status.na = \'n\'', 'left outer');		
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_inv_aset.pic AND tbl_karyawan.na = \'n\'', 'left outer');
		$this->db->join('tbl_inv_kerusakan', 'tbl_inv_kerusakan.id_kerusakan = tbl_inv_aset.id_kerusakan AND tbl_inv_area.del = \'n\' AND tbl_inv_area.na = \'n\'', 'left outer');	
		// $this->db->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_jenis.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		
		if ($id_aset !== NULL) {
			$this->db->where('tbl_inv_aset.id_aset', $id_aset);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_aset.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_aset.nomor_sap', $nama);
		}
		if ($pengguna !== NULL) {
			$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if($jenis != NULL){
			if(is_string($jenis)) $jenis = explode(",", $jenis);
			$this->db->where_in('tbl_inv_jenis.id_jenis', $jenis);
		}
		if($merk != NULL){
			if(is_string($merk)) $merk = explode(",", $merk);
			$this->db->where_in('tbl_inv_merk.id_merk', $merk);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->db->where_in('tbl_inv_pabrik.id_pabrik', $pabrik);
		}
		if($lokasi != NULL){
			if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			$this->db->where_in('tbl_inv_lokasi.id_lokasi', $lokasi);
		}
		if($area != NULL){
			if(is_string($area)) $area = explode(",", $area);
			$this->db->where_in('tbl_inv_area.id_area', $area);
		}
		if($kondisi != NULL){
			if(is_string($kondisi)) $kondisi = explode(",", $kondisi);
			$this->datatables->where_in('tbl_inv_aset.id_kondisi', $kondisi);
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->datatables->where_in('tbl_inv_aset.na', $status);
		}
		if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			if((base64_decode($this->session->userdata("-gsber-"))=='KJP1')or(base64_decode($this->session->userdata("-gsber-"))=='KJP2')){
				$this->datatables->where("tbl_inv_aset.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode in('KJP1','KJP2'))");
			}else{
				$this->datatables->where("tbl_inv_aset.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
			}
			// $this->datatables->where("tbl_inv_aset.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
		}
		if($ratio != NULL){
			if(is_string($ratio)) $ratio = explode(",", $ratio);
			$this->datatables->where_in('tbl_inv_aset.ratio', $ratio);
		}
		// $this->db->where('tbl_inv_aset.nomor_polisi', 'B 770 KMG');	//buat test
		$this->db->where("tbl_inv_aset.id_pabrik!=''");
		$this->db->where('tbl_inv_aset.del', $deleted);
		$this->db->order_by("tbl_inv_aset.id_aset", "desc");
		// $this->db->limit(5);
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_aset_pic($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $pic = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		// $this->db->select('tbl_inv_aset.*');
		// $this->db->select('tbl_inv_jenis.nama as nama_jenis');
		// $this->db->from('tbl_inv_aset');
		// $this->db->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_aset.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		// $this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		// if ($id_aset !== NULL) {
			// $this->db->where('tbl_inv_aset.id_aset', $id_aset);
		// }
		// if ($active !== NULL) {
			// $this->db->where('tbl_inv_aset.na', $active);
		// }
		// if ($pic !== NULL) {
			// $this->db->where('tbl_inv_aset.pic', $pic);
		// }
		// $this->db->where("tbl_inv_aset.id_kondisi='1'");
		// $this->db->where("tbl_inv_jenis.nama in('Desktop','Laptop')");
		
		// $this->db->order_by("tbl_inv_aset.id_aset", "desc");
		// $this->db->limit(1);

		$this->db->select('tbl_karyawan.*');
		$this->db->select("(select top 1 tbl_inv_aset.nomor_sap from tbl_inv_aset left outer join tbl_inv_jenis on tbl_inv_jenis.id_jenis=tbl_inv_aset.id_jenis where tbl_inv_aset.na='n' and tbl_inv_aset.id_kondisi='1' and tbl_inv_aset.pic='$pic' and tbl_inv_aset.nomor_sap is not null order by tbl_inv_aset.nomor_sap desc) as nomor_sap");
		$this->db->select("(select top 1 tbl_inv_jenis.nama from tbl_inv_aset left outer join tbl_inv_jenis on tbl_inv_jenis.id_jenis=tbl_inv_aset.id_jenis where tbl_inv_aset.na='n' and tbl_inv_aset.id_kondisi='1' and tbl_inv_aset.pic='$pic' and tbl_inv_aset.nomor_sap is not null order by tbl_inv_aset.nomor_sap desc) as nama_jenis");
		$this->db->from('tbl_karyawan');
		if ($pic !== NULL) {
			$this->db->where('tbl_karyawan.id_karyawan', $pic);
		}
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_aset_temp($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $flag=NULL, $act=NULL, $proses=NULL, $id_aset_temp=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';
		
		$this->db->select("(select count(*) from tbl_inv_aset_temp) as jumlah");
		$this->db->select('tbl_inv_aset_temp.*');
		$this->db->select("
							CAST( 
							( 
							  SELECT tbl_karyawan.nama + RTRIM(',') 
								FROM tbl_karyawan 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_inv_aset_temp.pic, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as nama_pic
		");
		$this->db->select('CASE
								WHEN tbl_inv_aset_temp.proses = \'update\' 
								THEN \'<span class="label label-success">Perubahan</span>\'
								WHEN tbl_inv_aset_temp.proses = \'input\' 
								THEN \'<span class="label label-success">Penambahan</span>\'
								WHEN tbl_inv_aset_temp.proses = \'delete\' 
								THEN \'<span class="label label-success">Hapus</span>\'
								ELSE \'<span class="label label-warning"></span>\'
						   END as label_proses');
		$this->db->select('CASE
								WHEN tbl_inv_aset_temp.flag = \'proses\' 
								THEN \'<span class="label label-success">Disetujui</span>\'
								WHEN tbl_inv_aset_temp.flag = \'batal\' 
								THEN \'<span class="label label-danger">Ditolak</span>\'
								ELSE \'<span class="label label-warning">Menunggu Persetujuan</span>\'
						   END as label_flag');
		$this->db->select('CASE
								WHEN tbl_inv_aset_temp.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('tbl_inv_kategori.nama as nama_kategori');
		$this->db->select('tbl_inv_jenis.nama as nama_jenis');
		$this->db->select('tbl_inv_merk.nama as nama_merk');
		$this->db->select('tbl_inv_merk_tipe.nama as nama_merk_tipe');
		$this->db->select('tbl_inv_status.nama as nama_status');
		$this->db->select('tbl_inv_kondisi.nama as nama_kondisi');
		$this->db->select('tbl_inv_pabrik.nama as nama_pabrik');
		$this->db->select('tbl_inv_lokasi.nama as nama_lokasi');
		$this->db->select('tbl_inv_sub_lokasi.nama as nama_sub_lokasi');
		$this->db->select('tbl_inv_area.nama as nama_area');
		$this->db->select('tbl_inv_aset_temp.tipe_aset as cop');
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select('(select ZKISSTT_0113.DEPNM from SAPSYNC.dbo.ZKISSTT_0113 where ZKISSTT_0113.DEPID COLLATE SQL_Latin1_General_CP1_CI_AS = tbl_inv_aset_temp.id_depo) as nama_depo');
		$this->db->select('CASE
								WHEN tbl_inv_kondisi.id_kondisi = \'1\' THEN \'<span class="label label-success">Beroperasi</span>\'
								ELSE \'<span class="label label-danger">Tidak Beroperasi</span>\'
						   END as label_kondisi');
		// $this->db->select('tbl_inv_aset.KODE_BARANG');				   
		$this->db->from('tbl_inv_aset_temp');
		$this->db->join('tbl_inv_aset', 'tbl_inv_aset.id_aset = tbl_inv_aset_temp.id_aset AND tbl_inv_aset_temp.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_aset_temp.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset_temp.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = tbl_inv_aset_temp.id_merk AND tbl_inv_merk.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_merk_tipe', 'tbl_inv_merk_tipe.id_merk_tipe = tbl_inv_aset_temp.id_merk_tipe AND tbl_inv_merk_tipe.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = tbl_inv_aset_temp.id_pabrik AND tbl_inv_pabrik.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_aset_temp.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_inv_aset_temp.id_sub_lokasi AND tbl_inv_sub_lokasi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_area', 'tbl_inv_area.id_area = tbl_inv_aset_temp.id_area AND tbl_inv_area.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_kondisi', 'tbl_inv_kondisi.id_kondisi = tbl_inv_aset_temp.id_kondisi AND tbl_inv_kondisi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_status', 'tbl_inv_status.id_status = tbl_inv_aset_temp.id_status AND tbl_inv_status.na = \'n\'', 'left outer');		
		// $this->db->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_jenis.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_inv_aset_temp.pic AND tbl_karyawan.na = \'n\'', 'left outer');		
		if ($id_aset != NULL) {
			$this->db->where('tbl_inv_aset_temp.id_aset', $id_aset);
		}
		if ($active != NULL) {
			$this->db->where('tbl_inv_aset_temp.na', $active);
		}
		if ($nama != NULL) {
			$this->db->where('tbl_inv_aset_temp.nomor_sap', $nama);
		}
		if ($pengguna != NULL) {
			$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if($jenis != NULL){
			if(is_string($jenis)) $jenis = explode(",", $jenis);
			$this->db->where_in('tbl_inv_jenis.id_jenis', $jenis);
		}
		if($merk != NULL){
			if(is_string($merk)) $merk = explode(",", $merk);
			$this->db->where_in('tbl_inv_merk.id_merk', $merk);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->db->where_in('tbl_inv_pabrik.id_pabrik', $pabrik);
		}
		if($lokasi != NULL){
			if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			$this->db->where_in('tbl_inv_lokasi.id_lokasi', $lokasi);
		}
		if($area != NULL){
			if(is_string($area)) $area = explode(",", $area);
			$this->db->where_in('tbl_inv_area.id_area', $area);
		}
		if($flag != NULL){
			$this->db->where('tbl_inv_aset_temp.flag', $flag);
		}
		if ($id_aset_temp != NULL) {
			$this->db->where('tbl_inv_aset_temp.id', $id_aset_temp);
		}
		
		if (base64_decode($this->session->userdata("-ho-")) != 'y') {
			$this->db->where("tbl_inv_aset_temp.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
		}
		if($proses != NULL){
			$this->db->where('tbl_inv_aset_temp.proses', $proses);
		}else{
			$this->db->where("tbl_inv_aset_temp.proses!='set_retire'");
		}
		
		$this->db->where('tbl_inv_aset_temp.del', $deleted);
		$this->db->order_by("tbl_inv_aset_temp.id_aset", "desc");
		// $this->db->limit(5);
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	//aa
	function get_data_detail_bom($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $alat=NULL, $status=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("tbl_inv_main.id_main");
		$this->datatables->select("CONVERT(varchar(11),tbl_inv_main.tanggal_mulai,104) as tanggal_mulai");
		$this->datatables->select("CONVERT(varchar(11),tbl_inv_main.tanggal_selesai,104) as tanggal_selesai");
		$this->datatables->select("tbl_inv_main.jenis_tindakan");
		$this->datatables->select("tbl_inv_main.operator");
		$this->datatables->select("tbl_inv_main.catatan");
		$this->datatables->select("tbl_inv_main.final");
		$this->datatables->select("tbl_inv_aset.id_aset");
		$this->datatables->select("tbl_inv_aset.id_kategori");
		$this->datatables->select("tbl_inv_aset.nomor");
		$this->datatables->select("tbl_inv_aset.kode_barang");
		$this->datatables->select("tbl_inv_aset.nomor_sap");
		$this->datatables->select("tbl_inv_aset.nomor_rangka");
		$this->datatables->select("tbl_inv_aset.nomor_mesin");
		$this->datatables->select("tbl_inv_aset.nomor_polisi");
		$this->datatables->select("tbl_inv_aset.tipe_aset");
		$this->datatables->select("tbl_inv_aset.id_kondisi");
		
		$this->datatables->select("tbl_inv_aset.nama_user");
		$this->datatables->select("tbl_inv_aset.nama_vendor");
		$this->datatables->select("CONVERT(varchar(11),tbl_inv_aset.tanggal_edit,104) as tanggal_edit");
		$this->datatables->select("tbl_inv_aset.na");
		
		$this->datatables->select('tbl_inv_kategori.nama as nama_kategori');
		$this->datatables->select('tbl_inv_jenis.nama as nama_jenis');
		$this->datatables->select('tbl_inv_merk.nama as nama_merk');
		$this->datatables->select('tbl_inv_merk_tipe.nama as nama_merk_tipe');
		$this->datatables->select('tbl_inv_status.nama as nama_status');
		$this->datatables->select('tbl_inv_kondisi.nama as nama_kondisi');
		$this->datatables->select('tbl_inv_pabrik.nama as nama_pabrik');
		$this->datatables->select('tbl_inv_lokasi.nama as nama_lokasi');
		$this->datatables->select('tbl_inv_sub_lokasi.nama as nama_sub_lokasi');
		$this->datatables->select('tbl_inv_area.nama as nama_area');
		$this->datatables->select('tbl_inv_aset.tipe_aset as cop');
		$this->datatables->select("tbl_inv_aset.jam_jalan_terakhir as jam_jalan");
		
		// $this->datatables->from("tbl_inv_aset");
		$this->datatables->from("tbl_inv_main");
		$this->datatables->join('tbl_inv_aset', 'tbl_inv_aset.id_aset = tbl_inv_main.id_aset AND tbl_inv_aset.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_aset.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = tbl_inv_aset.id_merk AND tbl_inv_merk.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_merk_tipe', 'tbl_inv_merk_tipe.id_merk_tipe = tbl_inv_aset.id_merk_tipe AND tbl_inv_merk_tipe.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = tbl_inv_aset.id_pabrik AND tbl_inv_pabrik.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_aset.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_inv_aset.id_sub_lokasi AND tbl_inv_sub_lokasi.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_area', 'tbl_inv_area.id_area = tbl_inv_aset.id_area AND tbl_inv_area.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_kondisi', 'tbl_inv_kondisi.id_kondisi = tbl_inv_aset.id_kondisi AND tbl_inv_kondisi.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_status', 'tbl_inv_status.id_status = tbl_inv_aset.id_status AND tbl_inv_status.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_inv_aset.pic AND tbl_karyawan.na = \'n\'', 'left outer');		

		if ($id_aset !== NULL) {
			$this->datatables->where('tbl_inv_aset.id_aset', $id_aset);
		}
		if ($active !== NULL) {
			$this->datatables->where('tbl_inv_aset.na', $active);
		}
		if ($deleted !== NULL) {
			$this->datatables->where('tbl_inv_main.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->datatables->where('tbl_inv_aset.nomor_sap', $nama);
		}
		if ($pengguna !== NULL) {
			$this->datatables->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if($jenis != NULL){
			if(is_string($jenis)) $jenis = explode(",", $jenis);
			$this->datatables->where_in('tbl_inv_jenis.id_jenis', $jenis);
		}
		if($merk != NULL){
			if(is_string($merk)) $merk = explode(",", $merk);
			$this->datatables->where_in('tbl_inv_merk.id_merk', $merk);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->datatables->where_in('tbl_inv_pabrik.id_pabrik', $pabrik);
		}
		if($lokasi != NULL){
			if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			$this->datatables->where_in('tbl_inv_lokasi.id_lokasi', $lokasi);
		}
		if($area != NULL){
			if(is_string($area)) $area = explode(",", $area);
			$this->datatables->where_in('tbl_inv_area.id_area', $area);
		}
		if ($alat !== NULL) {
			if($alat=='berat'){
				$this->db->where("tbl_inv_jenis.id_kategori!='5'");	
			}
			if($alat=='lab'){
				$this->db->where("tbl_inv_jenis.id_kategori='5'");	
			}
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->datatables->where_in('tbl_inv_main.final', $status);
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		// return $this->datatables->generate();
		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_aset","id_main"));
		return $this->general->jsonify($raw);
	}

	//apar
	function get_data_detail_bom_apar($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $alat=NULL, $status=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("tbl_inv_maintenance.id_maintenance as id_main");
		$this->datatables->select("CONVERT(varchar(11),tbl_inv_maintenance.tanggal,104) as tanggal_mulai");
		$this->datatables->select("CONVERT(varchar(11),tbl_inv_maintenance.tanggal,104) as tanggal_selesai");
		$this->datatables->select("tbl_inv_maintenance.perbaikan as jenis_tindakan");
		$this->datatables->select("tbl_inv_maintenance.operator");
		$this->datatables->select("tbl_inv_maintenance.catatan");
		$this->datatables->select("tbl_inv_maintenance.na as final");
		$this->datatables->select("tbl_inv_aset.id_aset");
		$this->datatables->select("tbl_inv_aset.id_kategori");
		$this->datatables->select("tbl_inv_aset.nomor");
		$this->datatables->select("tbl_inv_aset.kode_barang");
		$this->datatables->select("tbl_inv_aset.nomor_sap");
		$this->datatables->select("tbl_inv_aset.nomor_rangka");
		$this->datatables->select("tbl_inv_aset.nomor_mesin");
		$this->datatables->select("tbl_inv_aset.nomor_polisi");
		$this->datatables->select("tbl_inv_aset.tipe_aset");
		$this->datatables->select("tbl_inv_aset.id_kondisi");
		
		$this->datatables->select("tbl_inv_aset.nama_user");
		$this->datatables->select("tbl_inv_aset.nama_vendor");
		$this->datatables->select("CONVERT(varchar(11),tbl_inv_aset.tanggal_edit,104) as tanggal_edit");
		$this->datatables->select("tbl_inv_aset.na");
		
		$this->datatables->select('tbl_inv_kategori.nama as nama_kategori');
		$this->datatables->select('tbl_inv_jenis.nama as nama_jenis');
		$this->datatables->select('tbl_inv_merk.nama as nama_merk');
		$this->datatables->select('tbl_inv_merk_tipe.nama as nama_merk_tipe');
		$this->datatables->select('tbl_inv_status.nama as nama_status');
		$this->datatables->select('tbl_inv_kondisi.nama as nama_kondisi');
		$this->datatables->select('tbl_inv_pabrik.nama as nama_pabrik');
		$this->datatables->select('tbl_inv_lokasi.nama as nama_lokasi');
		$this->datatables->select('tbl_inv_sub_lokasi.nama as nama_sub_lokasi');
		$this->datatables->select('tbl_inv_area.nama as nama_area');
		$this->datatables->select('tbl_inv_aset.tipe_aset as cop');
		$this->datatables->select("tbl_inv_aset.jam_jalan_terakhir as jam_jalan");
		
		// $this->datatables->from("tbl_inv_aset");
		$this->datatables->from("tbl_inv_maintenance");
		$this->datatables->join('tbl_inv_aset', 'tbl_inv_aset.id_aset = tbl_inv_maintenance.id_aset AND tbl_inv_aset.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_aset.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = tbl_inv_aset.id_merk AND tbl_inv_merk.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_merk_tipe', 'tbl_inv_merk_tipe.id_merk_tipe = tbl_inv_aset.id_merk_tipe AND tbl_inv_merk_tipe.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = tbl_inv_aset.id_pabrik AND tbl_inv_pabrik.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_aset.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_inv_aset.id_sub_lokasi AND tbl_inv_sub_lokasi.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_area', 'tbl_inv_area.id_area = tbl_inv_aset.id_area AND tbl_inv_area.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_kondisi', 'tbl_inv_kondisi.id_kondisi = tbl_inv_aset.id_kondisi AND tbl_inv_kondisi.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_inv_status', 'tbl_inv_status.id_status = tbl_inv_aset.id_status AND tbl_inv_status.na = \'n\'', 'left outer');		
		$this->datatables->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_inv_aset.pic AND tbl_karyawan.na = \'n\'', 'left outer');		

		if ($id_aset !== NULL) {
			$this->datatables->where('tbl_inv_aset.id_aset', $id_aset);
		}
		if ($active !== NULL) {
			$this->datatables->where('tbl_inv_aset.na', $active);
		}
		if ($nama !== NULL) {
			$this->datatables->where('tbl_inv_aset.nomor_sap', $nama);
		}
		if ($pengguna !== NULL) {
			$this->datatables->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if($jenis != NULL){
			if(is_string($jenis)) $jenis = explode(",", $jenis);
			$this->datatables->where_in('tbl_inv_jenis.id_jenis', $jenis);
		}
		if($merk != NULL){
			if(is_string($merk)) $merk = explode(",", $merk);
			$this->datatables->where_in('tbl_inv_merk.id_merk', $merk);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->datatables->where_in('tbl_inv_pabrik.id_pabrik', $pabrik);
		}
		if($lokasi != NULL){
			if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			$this->datatables->where_in('tbl_inv_lokasi.id_lokasi', $lokasi);
		}
		if($area != NULL){
			if(is_string($area)) $area = explode(",", $area);
			$this->datatables->where_in('tbl_inv_area.id_area', $area);
		}
		if ($alat !== NULL) {
			if($alat=='berat'){
				$this->db->where("tbl_inv_jenis.id_kategori!='5'");	
			}
			if($alat=='lab'){
				$this->db->where("tbl_inv_jenis.id_kategori='5'");	
			}
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->datatables->where_in('tbl_inv_maintenance.final', $status);
		}

		if ($conn !== NULL)
			$this->general->closeDb();

		// return $this->datatables->generate();
		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_aset","id_maintenance"));
		return $this->general->jsonify($raw);
		
	}
	
	function get_data_detail_bom_($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $alat=NULL, $jam_mulai=NULL, $jam_selesai=NULL, $umur_mulai=NULL, $umur_selesai=NULL,$overdue=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("tbl_inv_aset.id_aset");
		$this->datatables->select("tbl_inv_aset.id_kategori");
		$this->datatables->from("tbl_inv_aset");

		if ($conn !== NULL)
			$this->general->closeDb();

		// return $this->datatables->generate();
		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_aset"));
		return $this->general->jsonify($raw);
	}

	function get_data_detail($conn = NULL, $id_main = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $jenis = NULL, $merk=NULL, $pabrik=NULL, $lokasi=NULL, $area=NULL, $alat=NULL, $jam_mulai=NULL, $jam_selesai=NULL, $umur_mulai=NULL, $umur_selesai=NULL,$overdue=NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';
		$this->db->select("tbl_inv_main.tanggal_buat, tbl_inv_main.jenis_tindakan, tbl_inv_main.tanggal_rusak, tbl_inv_main.jam_jalan, tbl_inv_main.id_main, tbl_inv_main.tanggal_mulai, tbl_inv_main.tanggal_selesai,tbl_inv_main.jenis_tindakan,tbl_inv_main.operator,tbl_inv_main.catatan,tbl_inv_main.final");
		// $this->db->select("tbl_inv_aset.*");		
		$this->db->select('CASE
								WHEN tbl_inv_main.jenis_tindakan = \'perbaikan\' THEN \'Perbaikan Kerusakan\'
								WHEN tbl_inv_main.jenis_tindakan = \'perawatan\' THEN \'Perawatan Rutin\'
								ELSE \'Update Jam Jalan\'
						   END as nama_jenis_tindakan');
		$this->db->select("CASE
								WHEN tbl_inv_main.operator != '' THEN tbl_inv_main.operator
								ELSE '-'
						   END as operator");
		$this->db->select("CASE
								WHEN tbl_inv_main.catatan != '' THEN tbl_inv_main.catatan
								ELSE '-'
						   END as catatan");
		$this->db->select('CASE
								WHEN tbl_inv_main.final = \'y\' THEN \'<span class="label label-success">Done</span>\'
								ELSE \'<span class="label label-danger">On Progress</span>\'
						   END as label_status');
						   
		$this->db->select('tbl_inv_aset.*');
		$this->db->select("
							CAST( 
							( 
							  SELECT tbl_karyawan.nama + RTRIM(',') 
								FROM tbl_karyawan 
							   WHERE CHARINDEX(''''+CONVERT(varchar(10), tbl_karyawan.nik)+'''',''''+REPLACE(tbl_inv_aset.pic, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as nama_pic
		");
		$this->db->select('CASE
								WHEN tbl_inv_aset.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('tbl_inv_kategori.nama as nama_kategori');
		$this->db->select('tbl_inv_jenis.nama as nama_jenis');
		$this->db->select('tbl_inv_merk.nama as nama_merk');
		$this->db->select('tbl_inv_merk_tipe.nama as nama_merk_tipe');
		$this->db->select('tbl_inv_status.nama as nama_status');
		$this->db->select('tbl_inv_kondisi.nama as nama_kondisi');
		$this->db->select('tbl_inv_pabrik.nama as nama_pabrik');
		$this->db->select('tbl_inv_lokasi.nama as nama_lokasi');
		$this->db->select('tbl_inv_sub_lokasi.nama as nama_sub_lokasi');
		$this->db->select('tbl_inv_area.nama as nama_area');
		$this->db->select('tbl_inv_aset.tipe_aset as cop');
		$this->db->select('tbl_inv_aset.id_aset');
		$this->db->select('tbl_karyawan.id_karyawan');
		$this->db->select('tbl_karyawan.nama as nama_karyawan');
		$this->db->select('tbl_inv_satuan.nama as nama_satuan');
		// $this->db->select("(select tbl_inv_buzzer.nama from tbl_inv_buzzer where tbl_inv_buzzer.id_buzzer=tbl_inv_aset.aksesoris1) as nama_aksesoris1");
		// $this->db->select("(select tbl_inv_buzzer.nama from tbl_inv_buzzer where tbl_inv_buzzer.id_buzzer=tbl_inv_aset.aksesoris2) as nama_aksesoris2");
		$this->db->select('(select ZKISSTT_0113.DEPNM from '.DB_DEFAULT.'.dbo.ZKISSTT_0113 where ZKISSTT_0113.DEPID=tbl_inv_aset.id_depo) as nama_depo');
		$this->db->select('CASE
								WHEN tbl_inv_kondisi.id_kondisi = \'1\' THEN \'<span class="label label-success">Beroperasi</span>\'
								ELSE \'<span class="label label-danger">Tidak Beroperasi</span>\'
						   END as label_kondisi');
		// $this->db->select("(select top 1 tbl_inv_periode.nama from tbl_inv_periode where tbl_inv_periode.id_jenis=tbl_inv_main.id_jenis and tbl_inv_periode.id_periode>tbl_inv_main.id_periode) as periode_next");				   
						   
		$this->db->from('tbl_inv_main');
		$this->db->join('tbl_inv_aset', 'tbl_inv_aset.id_aset = tbl_inv_main.id_aset AND tbl_inv_aset.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = tbl_inv_aset.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_aset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = tbl_inv_aset.id_merk AND tbl_inv_merk.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_merk_tipe', 'tbl_inv_merk_tipe.id_merk_tipe = tbl_inv_aset.id_merk_tipe AND tbl_inv_merk_tipe.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = tbl_inv_aset.id_pabrik AND tbl_inv_pabrik.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_aset.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_inv_aset.id_sub_lokasi AND tbl_inv_sub_lokasi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_area', 'tbl_inv_area.id_area = tbl_inv_aset.id_area AND tbl_inv_area.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_kondisi', 'tbl_inv_kondisi.id_kondisi = tbl_inv_aset.id_kondisi AND tbl_inv_kondisi.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_status', 'tbl_inv_status.id_status = tbl_inv_aset.id_status AND tbl_inv_status.na = \'n\'', 'left outer');		
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_inv_main.login_buat AND tbl_user.na = \'n\'', 'left outer');		
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan AND tbl_karyawan.na = \'n\'', 'left outer');		
		$this->db->join('tbl_inv_satuan', 'tbl_inv_satuan.id_satuan = tbl_inv_aset.id_satuan AND tbl_inv_satuan.na = \'n\'', 'left outer');		
		
		if ($id_main !== NULL) {
			$this->db->where('tbl_inv_main.id_main', $id_main);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_aset.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_inv_aset.nomor_sap', $nama);
		}
		if ($pengguna !== NULL) {
			$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if($jenis != NULL){
			if(is_string($jenis)) $jenis = explode(",", $jenis);
			$this->db->where_in('tbl_inv_jenis.id_jenis', $jenis);
		}
		if($merk != NULL){
			if(is_string($merk)) $merk = explode(",", $merk);
			$this->db->where_in('tbl_inv_merk.id_merk', $merk);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->db->where_in('tbl_inv_pabrik.id_pabrik', $pabrik);
		}
		if($lokasi != NULL){
			if(is_string($lokasi)) $lokasi = explode(",", $lokasi);
			$this->db->where_in('tbl_inv_lokasi.id_lokasi', $lokasi);
		}
		if($area != NULL){
			if(is_string($area)) $area = explode(",", $area);
			$this->db->where_in('tbl_inv_area.id_area', $area);
		}
		if(($jam_mulai != NULL)and($jam_selesai != NULL)and($pengguna='fo')){
			$this->datatables->where("jam_jalan<='$jam_mulai' and  jam_jalan>='$jam_selesai'");
		}
		if(($umur_mulai != NULL)and($umur_selesai != NULL)and($pengguna='fo')){
			$this->datatables->where("umur_aset<='$umur_mulai' and  umur_aset>='$umur_selesai'");
		}
		if($overdue != NULL){
			if(is_string($overdue)) $overdue = explode(",", $overdue);
			foreach($overdue as $dt) {
				if($dt=='jam'){
					$this->datatables->where("jam_jalan_next-jam_jalan < 0");		
				}
				if($dt=='bulan'){
					$this->datatables->where("periode_bulan-main_bulan < 0");		
				}
			}			
		}
		if ($alat !== NULL) {
			if($alat=='berat'){
				$this->db->where("tbl_inv_jenis.id_kategori!='5'");	
			}
			if($alat=='lab'){
				$this->db->where("tbl_inv_jenis.id_kategori='5'");	
			}
		}
		if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			$this->db->where("tbl_inv_aset.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
		}
		
		$this->db->where('tbl_inv_main.del', $deleted);
		$this->db->order_by("tbl_inv_main.id_main", "desc");
		// $this->db->limit(10);
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	public function selisihBln($tglAwal, $tglAkhir, $konversi){
		// tanggal, bulan, tahun
		$pecah1 = explode("-", $tglAwal);
		$date1 = $pecah1[2];
		$month1 = $pecah1[1];
		$year1 = $pecah1[0];
		// tanggal, bulan, tahun
		$pecah2 = explode("-", $tglAkhir);
		$date2 = $pecah2[2];
		$month2 = $pecah2[1];
		$year2 =  $pecah2[0];
		// mencari total selisih hari dari tanggal awal dan akhir
		$jd1 = GregorianToJD($month1, $date1, $year1);
		$jd2 = GregorianToJD($month2, $date2, $year2);
		//selisih
		$selisih = $jd2 - $jd1;
		//view jumlah bulan atau konversi ketahun
		if($konversi=='char'){
			$tahun = floor($selisih/365);//menghitung usia tahun
			$tahun = ($tahun<1)?"":$tahun." Tahun ";
			$sisa=$selisih%365;//sisa pembagian dari tahun untuk menghitung bulan
			$bulan=floor($sisa/30);//menghitung usia bulan
			$bulan = ($bulan<1)?"":$bulan." Bulan";
			$selisih_bulan = $tahun.''.$bulan;
		}else{
			$selisih_bulan = floor($selisih/30);//menghitung usia bulan
		}
		return $selisih_bulan;
	}

	public function selisihHr($tglAwal, $tglAkhir){
		// tanggal, bulan, tahun
		$pecah1 = explode("-", $tglAwal);
		$date1 = $pecah1[2];
		$month1 = $pecah1[1];
		$year1 = $pecah1[0];
		// tanggal, bulan, tahun
		$pecah2 = explode("-", $tglAkhir);
		$date2 = $pecah2[2];
		$month2 = $pecah2[1];
		$year2 =  $pecah2[0];
		// mencari total selisih hari dari tanggal awal dan akhir
		$jd1 = GregorianToJD($month1, $date1, $year1);
		$jd2 = GregorianToJD($month2, $date2, $year2);
		//selisih
		$selisih = $jd2 - $jd1;
		return $selisih;
		
	}
	
}
