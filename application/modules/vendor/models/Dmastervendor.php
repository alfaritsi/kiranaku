<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KODE VENDOR
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmastervendor extends CI_Model{
	function get_data_master_dokumen($conn = NULL, $id_master_dokumen = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $ck_id_master_dokumen = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		//buat migrasi file
		if($nama=='KTP Pengurus'){
			$id_master_dokumen_upload = 3;
		}else if($nama=='KTP'){
			$id_master_dokumen_upload = 3;
		}else if($nama=='NPWP'){
			$id_master_dokumen_upload = 5;
		}else if($nama=='Akta Pendirian atau Perubahan Pengurus Terakhir'){
			$id_master_dokumen_upload = 15;
		}else if($nama=='SIUP'){
			$id_master_dokumen_upload = 6;
		}else if($nama=='SKDP'){
			$id_master_dokumen_upload = 21;
		}else if($nama=='TDP'){
			$id_master_dokumen_upload = 54;	//NIB
		}else if($nama=='SPK Pengangkutan Barang Lainnya-Badan Usaha'){
			$id_master_dokumen_upload = 70;
		}else if($nama=='Dokumen Izin Kapal'){
			$id_master_dokumen_upload = 67;
		}else if($nama=='Dokumen Izin Pengangkutan'){
			$id_master_dokumen_upload = 28;
		}else if($nama=='Izin Pengangkutan Limbah B3'){
			$id_master_dokumen_upload = 12;
		}else if($nama=='Kartu Keluarga'){
			$id_master_dokumen_upload = 58;
		}else if($nama=='Kepemilikan-Perorangan'){
			$id_master_dokumen_upload = 59;
		}else if($nama=='Konstruksi-Perorangan'){	//cek ulang TDUP
			$id_master_dokumen_upload = 11;
		}else if($nama=='Konstuksi-Badan Usaha'){	//cek ulang SIUJK
			$id_master_dokumen_upload = 10;
		}else if($nama=='Konsultan ANDALALIN-Badan Usaha'){
			$id_master_dokumen_upload = 60;
		}else if($nama=='Pemilik-Perorangan'){		//cek ulang
			$id_master_dokumen_upload = 59;
		}else if($nama=='Pengangkutan Bokar Darat-Badan Usaha'){
			$id_master_dokumen_upload = 61;
		}else if($nama=='Pengangkutan Bokar Darat-Perorangan'){
			$id_master_dokumen_upload = 62;
		}else if($nama=='Pengangkutan Bokar Perairan-Badan Usaha'){
			$id_master_dokumen_upload = 67;
		}else if($nama=='Pengangkutan SIR-Badan Usaha'){
			$id_master_dokumen_upload = 28;
		}else if($nama=='SIUJK'){
			$id_master_dokumen_upload = 10;
		}else if($nama=='SIUP'){
			$id_master_dokumen_upload = 6;
		}else if($nama=='SKDP'){
			$id_master_dokumen_upload = 21;
		}else if($nama=='SPK Pengangkutan Barang Lainnya-Perorangan'){
			$id_master_dokumen_upload = 70;
		}else if($nama=='SPPKP'){
			$id_master_dokumen_upload = 7;
		}else{
			$id_master_dokumen_upload = 0;
		}
		$this->db->select('tbl_vendor_master_dokumen.*');
		$this->db->select('CASE
								WHEN tbl_vendor_master_dokumen.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		if($id_master_dokumen_upload!=0){
			$this->db->select("CASE
									WHEN (select count(*) from tbl_leg_oto_vendor where tbl_leg_oto_vendor.id_master_dokumen=$id_master_dokumen_upload)!=0
									THEN 'jenis_vendor'
									ELSE 'kualifikasi_spk'
							   END as tipe_referensi");
			$this->db->select("(select top 1 tbl_leg_oto_vendor.mandatory from tbl_leg_oto_vendor where tbl_leg_oto_vendor.id_master_dokumen=$id_master_dokumen_upload) as mandatory");				   
		}
		$this->db->from('tbl_vendor_master_dokumen');
		if ($id_master_dokumen !== NULL) {
			$this->db->where('tbl_vendor_master_dokumen.id_master_dokumen', $id_master_dokumen);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_master_dokumen.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_master_dokumen.del', $deleted);
		}
		// if ($id_master_dokumen_upload !== NULL) {
			// $this->db->where('tbl_vendor_master_dokumen.id_master_dokumen', $id_master_dokumen_upload);
		// }
		if($id_master_dokumen_upload!=0){
			if ($id_master_dokumen_upload !== NULL) {
				$this->db->where('tbl_vendor_master_dokumen.id_master_dokumen', $id_master_dokumen_upload);
			}
		}else{
			if ($nama !== NULL) {
				$this->db->where('tbl_vendor_master_dokumen.nama', $nama);
			}
		}

		if ($ck_id_master_dokumen !== NULL) {
			$this->db->where("tbl_vendor_master_dokumen.id_master_dokumen!='$ck_id_master_dokumen'");
		}

		$this->db->order_by("tbl_vendor_master_dokumen.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_karyawan($conn = NULL, $nik = NULL, $active = NULL, $deleted = 'n', $search = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.*');
		$this->db->select('CASE
								WHEN tbl_karyawan.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_karyawan');						   
		if ($nik !== NULL) {
			$this->db->where('tbl_karyawan.nik', $nik);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_karyawan.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_karyawan.del', $deleted);
		}
		if ($search !== NULL) {
			$this->db->where("(tbl_karyawan.nik like '%$search%')or(tbl_karyawan.nama like '%$search%')");
		}
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_role($conn = NULL, $id_role = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $level = NULL, $ck_id_role = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_role.*');
		$this->db->select('CASE
								WHEN tbl_vendor_role.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_vendor_role');
		if ($id_role !== NULL) {
			$this->db->where('tbl_vendor_role.id_role', $id_role);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_role.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_role.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_vendor_role.nama', $nama);
		}
		if ($level !== NULL) {
			$this->db->where('tbl_vendor_role.level', $level);
		}
		if ($ck_id_role !== NULL) {
			$this->db->where("tbl_vendor_role.id_role!='$ck_id_role'");
		}
		
		$this->db->order_by("tbl_vendor_role.level", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_user_role($conn = NULL, $id_user_role = NULL, $active = NULL, $deleted = 'n', $nik = NULL, $level = NULL, $gsber = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_user_role.id_user_role');
		$this->db->select('tbl_vendor_user_role.id_role');
		$this->db->select('tbl_vendor_user_role.nik');
		$this->db->select('CASE
								WHEN tbl_vendor_user_role.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('tbl_vendor_role.nama as nama_role');				   
		$this->db->select('tbl_vendor_role.*');
		$this->db->select('tbl_karyawan.gsber');				   
		$this->db->select('tbl_karyawan.nama as nama_karyawan');				   
		$this->db->select('tbl_karyawan.email as email_karyawan');				   
		$this->db->from('tbl_vendor_user_role');
		$this->db->join('tbl_vendor_role', 'tbl_vendor_role.id_role = tbl_vendor_user_role.id_role', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_vendor_user_role.nik', 'left outer');
		if ($id_user_role !== NULL) {
			$this->db->where('tbl_vendor_user_role.id_user_role', $id_user_role);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_user_role.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_user_role.del', $deleted);
		}
		if ($nik !== NULL) {
			$this->db->where('tbl_vendor_user_role.nik', $nik);
		}
		if ($level !== NULL) {
			$this->db->where('tbl_vendor_role.level', $level);
		}
		if ($gsber !== NULL) {
			$this->db->where('tbl_karyawan.gsber', $gsber);
		}
		$this->db->order_by("tbl_vendor_user_role.nik", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_tipe($conn = NULL, $id_tipe = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $not_id_tipe = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_tipe.*');
		$this->db->select("(select count(tbl_vendor_tipe_dokumen.id_tipe) from tbl_vendor_tipe_dokumen where tbl_vendor_tipe_dokumen.id_tipe=tbl_vendor_tipe.id_tipe and tbl_vendor_tipe_dokumen.na='n') as jumlah");
		$this->db->select('CASE
								WHEN tbl_vendor_tipe.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_vendor_tipe');
		if ($id_tipe !== NULL) {
			$this->db->where('tbl_vendor_tipe.id_tipe', $id_tipe);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_tipe.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_tipe.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_vendor_tipe.nama', $nama);
		}
		if ($not_id_tipe !== NULL) {
			$this->db->where("tbl_vendor_tipe.id_tipe!=$not_id_tipe");
		}
		$this->db->order_by("tbl_vendor_tipe.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_tipe_dokumen($conn = NULL, $id_tipe_dokumen = NULL, $active = NULL, $deleted = 'n', $id_tipe_filter = NULL, $id_tipe = NULL, $nama = NULL, $mandatory = NULL, $not_id_tipe_dokumen = NULL, $id_data = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		// if ($id_tipe !== NULL) {
			// $this->db->select("(select top 1 tbl_file.id_file from tbl_file where tbl_file.nama=$nama_file) as id_file");
			// $this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.nama=$nama_file) as link");
			// $this->db->select("(select top 1 tbl_file.tipe from tbl_file where tbl_file.nama=$nama_file) as tipe_file");
		// }
		
		$this->db->select('tbl_vendor_tipe_dokumen.id_tipe_dokumen as id_tipe_dokumen_post');
		$this->db->select('tbl_vendor_tipe.nama as nama_tipe');
		$this->db->select('tbl_vendor_tipe_dokumen.*');
		$this->db->select('CASE
								WHEN tbl_vendor_tipe_dokumen.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('CASE
								WHEN tbl_vendor_tipe_dokumen.mandatory = \'y\' THEN \'<span class="label label-success">Ya</span>\'
								ELSE \'<span class="label label-danger">Tidak</span>\'
						   END as label_mandatory');
		if($id_data!==NULL){
			$this->db->select("(select top 1 tbl_vendor_data_dokumen.[file] from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.id_tipe_dokumen=tbl_vendor_tipe_dokumen.id_tipe_dokumen) as link_file");				   
							   
		}
		$this->db->from('tbl_vendor_tipe_dokumen');
		$this->db->join('tbl_vendor_tipe', 'tbl_vendor_tipe.id_tipe = tbl_vendor_tipe_dokumen.id_tipe', 'left outer');
		if ($id_tipe_dokumen !== NULL) {
			$this->db->where('tbl_vendor_tipe_dokumen.id_tipe_dokumen', $id_tipe_dokumen);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_tipe_dokumen.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_tipe_dokumen.del', $deleted);
		}
		if($id_tipe_filter != NULL){
			if(is_string($id_tipe_filter)) $id_tipe_filter = explode(",", $id_tipe_filter);
			$this->db->where_in('tbl_vendor_tipe_dokumen.id_tipe', $id_tipe_filter);
		}
		if ($id_tipe !== NULL) {
			$this->db->where('tbl_vendor_tipe_dokumen.id_tipe', $id_tipe);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_vendor_tipe_dokumen.nama', $nama);
		}
		if ($mandatory !== NULL) {
			$this->db->where('tbl_vendor_tipe_dokumen.mandatory', $mandatory);
		}
		if ($not_id_tipe_dokumen !== NULL) {
			$this->db->where("tbl_vendor_tipe_dokumen.id_tipe!=$not_id_tipe_dokumen");
		}
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_kategori($conn = NULL, $id_kategori = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $not_id_kategori = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_kategori.*');
		$this->db->select("(select count(tbl_vendor_kategori_dokumen.id_kategori) from tbl_vendor_kategori_dokumen where tbl_vendor_kategori_dokumen.id_kategori=tbl_vendor_kategori.id_kategori and tbl_vendor_kategori_dokumen.na='n') as jumlah");
		$this->db->select('CASE
								WHEN tbl_vendor_kategori.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_vendor_kategori');
		if ($id_kategori !== NULL) {
			$this->db->where('tbl_vendor_kategori.id_kategori', $id_kategori);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_kategori.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_kategori.del', $deleted);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_vendor_kategori.nama', $nama);
		}
		if ($not_id_kategori !== NULL) {
			$this->db->where("tbl_vendor_kategori.id_kategori!=$not_id_kategori");
		}
		
		$this->db->order_by("tbl_vendor_kategori.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_acc_group($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('acc_group.*');
		$this->db->from('SAPSYNC.dbo.T077K as acc_group');
		$this->db->where("acc_group.KTOKK in ('NBVE','ONVE')");
		$this->db->order_by("acc_group.ktokk", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_title($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('title.TITLE_MEDI as title');
		$this->db->from('SAPSYNC.dbo.TSAD3T as title');
		$this->db->order_by("title.TITLE_MEDI", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_payment_term($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('T052.ZTERM as payment_term');
		$this->db->select("(select top 1 TVZBT.VTEXT from SAPSYNC.dbo.TVZBT where TVZBT.ZTERM=T052.ZTERM) as payment_term_detail");
		$this->db->from('SAPSYNC.dbo.T052');
		$this->db->order_by("T052.ZTERM", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_cur($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('TCURC.WAERS as cur');
		$this->db->from('SAPSYNC.dbo.TCURC');
		$this->db->order_by("TCURC.WAERS", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_tax_type($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('T059U.WITHT as tax_type');
		$this->db->select('T059U.TEXT40 as tax_type_name');
		$this->db->from('SAPSYNC.dbo.T059U');
		$this->db->where("T059U.SPRAS='E'");
		$this->db->where("T059U.LAND1='ID'");
		$this->db->order_by("T059U.WITHT", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_tax_code($conn = NULL, $tax_type) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('T059ZT.WT_WITHCD as tax_code');
		$this->db->select('T059ZT.TEXT40 as tax_code_name');
		$this->db->from('SAPSYNC.dbo.T059ZT');
		$this->db->where("T059ZT.SPRAS='E'");
		$this->db->where("T059ZT.LAND1='ID'");
		if ($tax_type !== NULL) {
			$this->db->where('T059ZT.WITHT', $tax_type);
		}
		$this->db->order_by("T059ZT.WITHT", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_negara($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('t005T.LAND1 as id');
		$this->db->select('t005T.LANDX as negara');
		$this->db->from('SAPSYNC.dbo.t005T');
		$this->db->where("t005T.SPRAS='E'");
		$this->db->order_by("t005T.LAND1", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_term1($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_term1.*');
		$this->db->from('tbl_vendor_term1');
		$this->db->where("tbl_vendor_term1.na='n'");
		$this->db->order_by("tbl_vendor_term1.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_term2($conn = NULL, $term1 = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_term2.*');
		$this->db->from('tbl_vendor_term2');
		$this->db->where("tbl_vendor_term2.na='n'");
		$this->db->where("tbl_vendor_term2.id_term1=(select tbl_vendor_term1.id_term1 from tbl_vendor_term1 where tbl_vendor_term1.nama='$term1')");
		$this->db->order_by("tbl_vendor_term2.nama", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_provinsi($conn = NULL, $id_provinsi = NULL, $negara = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('T005U.BLAND as id_provinsi');
		$this->db->select('T005U.BEZEI as nama_provinsi');
		$this->db->from('SAPSYNC.dbo.T005U');
		if ($id_provinsi !== NULL) {
			$this->db->where('T005U.BLAND', $id_provinsi);
		}
		if ($negara !== NULL) {
			$this->db->where('T005U.LAND1', $negara);
		}
		
		$this->db->order_by("T005U.BLAND", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_industri($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('T016T.BRSCH as id_industri');
		$this->db->select('T016T.BRTXT as nama_industri');
		$this->db->from('SAPSYNC.dbo.T016T');
		$this->db->where("T016T.SPRAS='E'");
		$this->db->order_by("T016T.BRSCH", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_kategori_dokumen($conn = NULL, $id_kategori_dokumen = NULL, $active = NULL, $deleted = 'n', $id_kategori_filter = NULL, $id_kategori = NULL, $nama = NULL, $mandatory = NULL, $not_id_kategori_dokumen = NULL, $id_data = NULL) {
	// function get_data_kategori_dokumen($conn = NULL, $id_kategori_dokumen = NULL, $active = NULL, $deleted = 'n', $id_kategori_filter = NULL, $id_kategori = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_kategori_dokumen.id_kategori_dokumen as id_kategori_dokumen_post');
		$this->db->select('tbl_vendor_kategori.nama as nama_kategori');
		$this->db->select('tbl_vendor_kategori_dokumen.*');
		$this->db->select('CASE
								WHEN tbl_vendor_kategori_dokumen.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('CASE
								WHEN tbl_vendor_kategori_dokumen.mandatory = \'y\' THEN \'<span class="label label-success">Ya</span>\'
								ELSE \'<span class="label label-danger">Tidak</span>\'
						   END as label_mandatory');
		if($id_data!==NULL){
			$this->db->select("(select tbl_vendor_data_dokumen.[file] from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.id_kategori_dokumen=tbl_vendor_kategori_dokumen.id_kategori_dokumen) as link_file");				   
		}
						   
		$this->db->from('tbl_vendor_kategori_dokumen');
		$this->db->join('tbl_vendor_kategori', 'tbl_vendor_kategori.id_kategori = tbl_vendor_kategori_dokumen.id_kategori', 'left outer');
		if ($id_kategori_dokumen !== NULL) {
			$this->db->where('tbl_vendor_kategori_dokumen.id_kategori_dokumen', $id_kategori_dokumen);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_kategori_dokumen.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_kategori_dokumen.del', $deleted);
		}
		if($id_kategori_filter != NULL){
			if(is_string($id_kategori_filter)) $id_kategori_filter = explode(",", $id_kategori_filter);
			$this->db->where_in('tbl_vendor_kategori_dokumen.id_kategori', $id_kategori_filter);
		}
		if ($id_kategori !== NULL) {
			$this->db->where('tbl_vendor_kategori_dokumen.id_kategori', $id_kategori);
		}
		if ($nama !== NULL) {
			$this->db->where('tbl_vendor_kategori_dokumen.nama', $nama);
		}
		if ($mandatory !== NULL) {
			$this->db->where('tbl_vendor_kategori_dokumen.mandatory', $mandatory);
		}
		if ($not_id_kategori_dokumen !== NULL) {
			$this->db->where("tbl_vendor_kategori_dokumen.id_kategori!=$not_id_kategori_dokumen");
		}
		
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_kriteria($conn = NULL, $id_kriteria = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_kriteria.*');
		$this->db->select('CASE
								WHEN tbl_vendor_kriteria.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select("
					       CAST(
					         (SELECT 
								CONVERT(VARCHAR(MAX), ISNULL(tbl_vendor_nilai.id_nilai,0))+RTRIM('#')
								+tbl_vendor_nilai.nama_in+RTRIM('#')
								+tbl_vendor_nilai.keterangan_in+RTRIM('#')
								+tbl_vendor_nilai.nama_en+RTRIM('#')
								+tbl_vendor_nilai.keterangan_en+RTRIM('#')
								+CONVERT(VARCHAR(MAX), ISNULL(tbl_vendor_nilai.nilai,0))+RTRIM('#')
								+RTRIM('|')
					            FROM tbl_vendor_nilai
								WHERE tbl_vendor_nilai.id_kriteria = tbl_vendor_kriteria.id_kriteria
								and tbl_vendor_nilai.na='n'
								ORDER BY tbl_vendor_nilai.id_nilai
					          FOR XML PATH ('')) as VARCHAR(MAX)
					       )  AS list_nilai,
						  ");
		$this->db->from('tbl_vendor_kriteria');
		if ($id_kriteria !== NULL) {
			$this->db->where('tbl_vendor_kriteria.id_kriteria', $id_kriteria);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_kriteria.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_kriteria.del', $deleted);
		}
		$this->db->order_by("tbl_vendor_kriteria.id_kriteria", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_nilai($conn = NULL, $id_nilai = NULL, $active = NULL, $deleted = 'n', $id_kriteria = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_vendor_nilai.*');
		$this->db->select('CASE
								WHEN tbl_vendor_nilai.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->from('tbl_vendor_nilai');
		if ($id_nilai !== NULL) {
			$this->db->where('tbl_vendor_nilai.id_nilai', $id_nilai);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_vendor_nilai.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_vendor_nilai.del', $deleted);
		}
		if ($id_kriteria !== NULL) {
			$this->db->where('tbl_vendor_nilai.id_kriteria', $id_kriteria);
		}
		$this->db->order_by("tbl_vendor_nilai.id_nilai", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	
}
?>