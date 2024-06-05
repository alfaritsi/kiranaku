<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Equipment Management
@author     : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmasterasset extends CI_Model
{
	function get_master_lokasi($conn = NULL, $id_lokasi = NULL, $active = NULL, $deleted = 'n', $nama = NULL)
	{
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
		$this->db->where('tbl_inv_lokasi.del', $deleted);

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_master_sub_lokasi($conn = NULL, $id_sub_lokasi = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $id_lokasi = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_sub_lokasi.*');
		$this->db->select('CASE
								WHEN tbl_inv_sub_lokasi.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('tbl_inv_lokasi.nama as nama_lokasi');
		$this->db->select('tbl_inv_lokasi.pengguna');
		$this->db->from('tbl_inv_sub_lokasi');
		$this->db->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_sub_lokasi.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left');
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
			$this->db->where('tbl_inv_lokasi.id_lokasi', $id_lokasi);
		}
		$this->db->where('tbl_inv_sub_lokasi.del', $deleted);

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_master_area($conn = NULL, $id_area = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $id_sub_lokasi = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('tbl_inv_area.id_area');
		$this->db->select('tbl_inv_area.nama');
		$this->db->select('tbl_inv_area.keterangan');
		$this->db->select('tbl_inv_area.na');
		$this->db->select('CASE
								WHEN tbl_inv_area.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select('tbl_inv_sub_lokasi.nama as nama_sub_lokasi');
		$this->db->select('tbl_inv_sub_lokasi.id_sub_lokasi as id_sub_lokasi');
		$this->db->select('tbl_inv_lokasi.nama as nama_lokasi');
		$this->db->select('tbl_inv_lokasi.id_lokasi as id_lokasi');
		$this->db->select('tbl_inv_lokasi.pengguna');
		$this->db->from('tbl_inv_area');
		$this->db->join('tbl_inv_sub_lokasi', 'tbl_inv_sub_lokasi.id_sub_lokasi = tbl_inv_area.id_sub_lokasi AND tbl_inv_sub_lokasi.na = \'n\'', 'left');
		$this->db->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi = tbl_inv_sub_lokasi.id_lokasi AND tbl_inv_lokasi.na = \'n\'', 'left');
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
			$this->db->where('tbl_inv_sub_lokasi.id_sub_lokasi', $id_sub_lokasi);
		}
		$this->db->where('tbl_inv_area.del', $deleted);

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}



	// =====================================================================================================

	function get_kategori($conn = NULL, $id_kategori = NULL, $pengguna = NULL, $nama = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_kategori.*');

		$this->db->from('tbl_inv_kategori');

		$this->db->where('tbl_inv_kategori.del', 'n');

		if ($pengguna != NULL) {
			$this->db->where('tbl_inv_kategori.pengguna', $pengguna);
		}

		if ($id_kategori != NULL) {
			$this->db->where('tbl_inv_kategori.id_kategori', $id_kategori);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_kategori.id_kategori !=', $not);
		}

		if ($nama != NULL) {
			$this->db->where('tbl_inv_kategori.nama', $nama);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_pic($conn = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_karyawan.*');
		$this->db->from('tbl_karyawan');
		$this->db->where("tbl_karyawan.nik in('8491','7848','7039')");

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_jenis($conn = NULL, $id_jenis = NULL, $pengguna = NULL, $nama = NULL, $id_kategori = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_jenis.*');
		$this->db->select('tbl_inv_kategori.nama as kategori');

		$this->db->from('tbl_inv_jenis');
		$this->db->join('tbl_inv_kategori', 'tbl_inv_jenis.id_kategori = tbl_inv_kategori.id_kategori');


		$this->db->where('tbl_inv_jenis.del', 'n');

		if ($pengguna != NULL) {
			$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		}

		if ($id_jenis != NULL) {
			$this->db->where('tbl_inv_jenis.id_jenis', $id_jenis);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_jenis.id_jenis !=', $not);
		}

		if ($nama != NULL && $id_kategori != NULL) {
			$this->db->where('tbl_inv_jenis.nama', $nama);
			$this->db->where('tbl_inv_jenis.id_kategori', $id_kategori);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_jenis_detail($conn = NULL, $id_jenis = NULL, $id_jenis_komponen = NULL, $nama = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_jenis_detail.*');

		$this->db->from('tbl_inv_jenis_detail');


		$this->db->where('tbl_inv_jenis_detail.del', 'n');
		// $this->db->where('tbl_inv_jenis.na', 'n');

		if ($id_jenis != NULL) {
			$this->db->where('tbl_inv_jenis_detail.id_jenis', $id_jenis);
		}

		if ($id_jenis_komponen != NULL) {
			$this->db->where('tbl_inv_jenis_detail.id_jenis_detail', $id_jenis_komponen);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_jenis_detail.id_jenis_detail !=', $not);
		}

		if ($nama != NULL) {
			$this->db->where('tbl_inv_jenis_detail.nama', $nama);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_merk($conn = NULL, $id_merk = NULL, $pengguna = NULL, $nama = NULL, $id_jenis = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_merk.*');
		$this->db->select('tbl_inv_jenis.nama as jenis_asset');

		$this->db->from('tbl_inv_merk');
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = tbl_inv_merk.id_jenis');


		$this->db->where('tbl_inv_merk.del', 'n');
		$this->db->where('tbl_inv_jenis.del', 'n');

		if ($pengguna != NULL) {
			$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		}

		if ($id_merk != NULL) {
			$this->db->where('tbl_inv_merk.id_merk', $id_merk);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_merk.id_merk !=', $not);
		}

		if ($nama != NULL && $id_jenis != NULL) {
			$this->db->where('tbl_inv_merk.nama', $nama);
			$this->db->where('tbl_inv_merk.id_jenis', $id_jenis);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_tipe_merk($conn = NULL, $id_merk = NULL, $id_merk_tipe = NULL, $nama = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_merk_tipe.*');
		$this->db->select('tbl_inv_merk.nama as merk');

		$this->db->from('tbl_inv_merk_tipe');
		$this->db->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = tbl_inv_merk_tipe.id_merk');


		$this->db->where('tbl_inv_merk_tipe.del', 'n');
		// $this->db->where('tbl_inv_jenis.na', 'n');

		if ($id_merk != NULL) {
			$this->db->where('tbl_inv_merk_tipe.id_merk', $id_merk);
		}

		if ($id_merk_tipe != NULL) {
			$this->db->where('tbl_inv_merk_tipe.id_merk_tipe', $id_merk_tipe);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_merk_tipe.id_merk_tipe !=', $not);
		}

		if ($nama != NULL) {
			$this->db->where('tbl_inv_merk_tipe.nama', $nama);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}


	function get_jenis_instansi($conn = NULL, $id_jenis_instansi = NULL, $nama = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_jenis_instansi.*');

		$this->db->from('tbl_inv_jenis_instansi');


		$this->db->where('tbl_inv_jenis_instansi.del', 'n');
		// $this->db->where('tbl_inv_jenis.na', 'n');

		if ($id_jenis_instansi != NULL) {
			$this->db->where('tbl_inv_jenis_instansi.id_jenis_instansi', $id_jenis_instansi);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_jenis_instansi.id_jenis_instansi !=', $not);
		}

		if ($nama != NULL) {
			$this->db->where('tbl_inv_jenis_instansi.nama', $nama);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}


	function get_instansi($conn = NULL, $id_jenis_instansi = NULL, $id_instansi = NULL, $nama = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_instansi.*');

		$this->db->from('tbl_inv_instansi');


		$this->db->where('tbl_inv_instansi.del', 'n');

		if ($id_jenis_instansi != NULL) {
			$this->db->where('tbl_inv_instansi.id_jenis_instansi', $id_jenis_instansi);
		}

		if ($id_instansi != NULL) {
			$this->db->where('tbl_inv_instansi.id_instansi', $id_instansi);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_instansi.id_instansi !=', $not);
		}

		if ($nama != NULL) {
			$this->db->where('tbl_inv_instansi.nama', $nama);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}


	function get_dokumen($conn = NULL, $id_inv_doc = NULL, $nama = NULL, $id_jenis_instansi = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_doc.*');
		// $this->db->select('tbl_inv_jenis_instansi.nama');
		$this->db->select("'jenis kendaraan' as jenis_kendaraan");
		$this->db->select("'jenis instansi' as jenis_instansi");

		$this->db->from('tbl_inv_doc');
		// $this->db->join('tbl_inv_jenis_instansi', 'tbl_inv_doc.id_jenis_instansi = tbl_inv_jenis_instansi.id_jenis_instansi');

		$this->db->where('tbl_inv_doc.del', 'n');

		if ($id_inv_doc != NULL) {
			$this->db->where('tbl_inv_doc.id_inv_doc', $id_inv_doc);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_doc.id_inv_doc !=', $not);
		}

		if ($nama != NULL && $id_jenis_instansi != NULL) {
			$this->db->where('tbl_inv_doc.nama', $nama);
			$this->db->where('tbl_inv_doc.id_jenis_instansi', $id_jenis_instansi);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}


	function get_pabrik($conn = NULL, $kode_plant = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_pabrik.*');

		$this->db->from('tbl_inv_pabrik');

		$this->db->where('tbl_inv_pabrik.del', 'n');
		$this->db->where('tbl_inv_pabrik.na', 'n');

		if ($kode_plant != NULL) {
			$this->db->where('tbl_inv_pabrik.kode', $kode_plant);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_biaya($conn = NULL, $id_inv_biaya = NULL, $nama = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_biaya.*');

		$this->db->from('tbl_inv_biaya');

		$this->db->where('tbl_inv_biaya.del', 'n');

		if ($id_inv_biaya != NULL) {
			$this->db->where('tbl_inv_biaya.id_inv_biaya', $id_inv_biaya);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_biaya.id_inv_biaya !=', $not);
		}

		if ($nama != NULL) {
			$this->db->where('tbl_inv_biaya.nama', $nama);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_sinkron($conn = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_wf_master_plant.*');

		$this->db->from('tbl_wf_master_plant');

		$this->db->where('tbl_wf_master_plant.del', 'n');
		$this->db->where('tbl_wf_master_plant.na', 'n');

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_kegiatan($conn = NULL, $id_kegiatan = NULL, $nama = NULL, $not = NULL, $pengguna = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_kegiatan.*');

		$this->db->from('tbl_inv_kegiatan');

		$this->db->where('tbl_inv_kegiatan.del', 'n');

		if ($id_kegiatan != NULL) {
			$this->db->where('tbl_inv_kegiatan.id_kegiatan', $id_kegiatan);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_kegiatan.id_kegiatan !=', $not);
		}

		if ($nama != NULL) {
			$this->db->where('tbl_inv_kegiatan.nama', $nama);
		}

		if (isset($pengguna) && $pengguna != NULL) {
			$this->db->where('tbl_inv_kegiatan.pengguna', $pengguna);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_service($conn = NULL, $id_service = NULL, $nama = NULL, $not = NULL, $pengguna = 'fo')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_service.*');

		$this->db->from('tbl_inv_service');

		$this->db->where('tbl_inv_service.del', 'n');

		if (!isset($id_service))
			$this->db->where('tbl_inv_service.pengguna', $pengguna);

		if ($id_service != NULL) {
			$this->db->where('tbl_inv_service.id_service', $id_service);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_service.id_service !=', $not);
		}

		if ($nama != NULL) {
			$this->db->where('tbl_inv_service.nama', $nama);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_satuan($conn = NULL, $id_satuan = NULL, $nama = NULL, $not = NULL, $pengguna = 'fo')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_satuan.*');

		$this->db->from('tbl_inv_satuan');

		$this->db->where('tbl_inv_satuan.del', 'n');

		if ($id_satuan != NULL) {
			$this->db->where('tbl_inv_satuan.id_satuan', $id_satuan);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_satuan.id_satuan !=', $not);
		}

		if ($nama != NULL) {
			$this->db->where('tbl_inv_satuan.nama', $nama);
		}

		if (isset($pengguna) && $pengguna != NULL) {
			$this->db->where('tbl_inv_satuan.pengguna', $pengguna);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_periode($conn = NULL, $id_periode = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_periode.*');
		$this->db->select("'jenis' as jenis");
		$this->db->select("'service' as service");
		$this->db->select("tbl_inv_jenis.id_kategori");

		$this->db->from('tbl_inv_periode');
		$this->db->join('tbl_inv_jenis', 'tbl_inv_periode.id_jenis = tbl_inv_jenis.id_jenis');

		$this->db->where('tbl_inv_periode.del', 'n');

		if ($id_periode != NULL) {
			$this->db->where('tbl_inv_periode.id_periode', $id_periode);
		} else {
			$this->db->limit('30');
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_aset_detail_opsi($conn = NULL, $id_aset_detail_opsi = NULL, $kolom = NULL, $opsi = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_aset_detail_opsi.*');

		$this->db->from('tbl_inv_aset_detail_opsi');

		$this->db->where('tbl_inv_aset_detail_opsi.del', 'n');

		if ($id_aset_detail_opsi != NULL) {
			$this->db->where('tbl_inv_aset_detail_opsi.id_aset_detail_opsi', $id_aset_detail_opsi);
		}

		if (!empty($kolom) && !is_null($kolom)) {
			$this->db->where('tbl_inv_aset_detail_opsi.nama_kolom', $kolom);
		}

		if (!empty($opsi) && !is_null($opsi)) {
			$this->db->where('tbl_inv_aset_detail_opsi.nilai_pilihan', $opsi);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_aset_detail_master($conn = NULL, $id_aset_detail_master = NULL, $kolom = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_aset_detail_master.*');

		$this->db->from('tbl_inv_aset_detail_master');

		$this->db->where('tbl_inv_aset_detail_master.del', 'n');

		if ($id_aset_detail_master != NULL) {
			$this->db->where('tbl_inv_aset_detail_master.id_aset_detail_master', $id_aset_detail_master);
		}

		if (!empty($kolom) && !is_null($kolom)) {
			$this->db->where('tbl_inv_aset_detail_master.nama_kolom', $kolom);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	// function get_psr_side($conn=NULL, $awal=NULL, $range=NULL){
	// 	if ($conn !== NULL)
	// 		$this->general->connectDbPortal();

	// 	$this->db->select('tbl_inv_periode.*');


	// 	$this->db->from('tbl_inv_periode');


	// 	$this->db->where('tbl_inv_periode.del', 'n');

	// 	if ($awal != NULL && $range != NULL) {
	// 		$this->db->limit($awal, $range);
	// 	}

	// 	$query 	= $this->db->get();
	// 	$result	= $query->result();

	// 	if ($conn !== NULL)
	// 		$this->general->closeDb();
	// 	return $result;
	// }

	function get_periode_detail($conn = NULL, $id_periode = NULL, $id_jenis_detail = NULL, $id_jenis = NULL, $all = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_periode_detail.*');

		$this->db->from('tbl_inv_periode_detail');

		if ($all == NULL) {
			$this->db->where('tbl_inv_periode_detail.del', 'n');
		}

		if ($id_periode != NULL) {
			$this->db->where('tbl_inv_periode_detail.id_periode', $id_periode);
		}

		if ($id_jenis_detail != NULL) {
			$this->db->where('tbl_inv_periode_detail.id_jenis_detail', $id_jenis_detail);
		}

		if ($id_jenis != NULL) {
			$this->db->where('tbl_inv_periode_detail.id_jenis', $id_jenis);
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_periode_detail_by_jenis($conn = NULL, $id_jenis = NULL, $id_periode = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_jenis_detail.nama as jenis_detail');
		$this->db->select('tbl_inv_jenis_detail.id_jenis_detail as id_jenis_details');
		$this->db->select('tbl_inv_jenis_detail.na as na_jd');
		$this->db->select("'list' as list_kegiatan");
		$this->db->select('tbl_inv_periode_detail.*');

		$this->db->from('tbl_inv_jenis_detail');
		$this->db->join('tbl_inv_periode_detail', "tbl_inv_jenis_detail.id_jenis_detail = tbl_inv_periode_detail.id_jenis_detail and tbl_inv_periode_detail.id_periode  = $id_periode  and tbl_inv_periode_detail.na = 'n' ", 'left');


		$this->db->where('tbl_inv_jenis_detail.del', 'n');
		$this->db->where('tbl_inv_jenis_detail.id_jenis', $id_jenis);

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_all_periode_datatables($conn = NULL, $pengguna = 'fo')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("tbl1.id_periode, 
								   tbl1.id_jenis,
								   tbl2.nama as nama_jenis,
								   tbl1.kode,
								   tbl1.nama,
								   tbl1.keterangan,
								   tbl1.squence,
								   tbl1.jam,
								   tbl1.bulan,
								   tbl1.periode,
								   tbl1.periode_jumlah,
								   tbl1.lama,
								   tbl1.lama_jumlah,
								   tbl1.delay_hari,
								   tbl1.kategori,
								   tbl1.na,
								   tbl1.del");
		// $this->datatables->add_column('id_periode_new', '$1' , $this->generate->kirana_encrypt('id_periode'));
		$this->datatables->from("tbl_inv_periode as tbl1");
		$this->datatables->join("tbl_inv_jenis as tbl2", "tbl1.id_jenis = tbl2.id_jenis");
		$this->datatables->where('tbl2.pengguna', $pengguna);
		$this->datatables->where('tbl1.del', 'n');
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_periode", "id_jenis"));
		return $this->general->jsonify($raw);
	}

	function get_kerusakan($conn = NULL, $id_kerusakan = NULL, $kerusakan = NULL, $pengguna = NULL, $not = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_kerusakan.*');
		$this->db->from('tbl_inv_kerusakan');
		$this->db->where('tbl_inv_kerusakan.del', 'n');

		if ($pengguna != NULL) {
			$this->db->where('tbl_inv_kerusakan.pengguna', $pengguna);
		}

		if ($id_kerusakan != NULL) {
			$this->db->where('tbl_inv_kerusakan.id_kerusakan', $id_kerusakan);
		}

		if ($not != NULL) {
			$this->db->where('tbl_inv_kerusakan.id_kerusakan !=', $not);
		}

		if ($kerusakan != NULL) {
			$this->db->where('tbl_inv_kerusakan.kerusakan', ltrim(rtrim($kerusakan)));
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_mobile_user($param = NULL)
	{
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		$this->db->select("tbl_inv_mobile.*");
		$this->db->select("tbl_karyawan.nama");

		$this->db->from('tbl_inv_mobile');
		$this->db->join("tbl_karyawan", "tbl_karyawan.nik = tbl_inv_mobile.nik and tbl_karyawan.del = 'n'", "left");

		if (isset($param['id_mobile']) && $param['id_mobile'] !== NULL)
			$this->db->where('tbl_inv_mobile.id_mobile', $param['id_mobile']);

		if (isset($param['pengguna']) && $param['pengguna'] !== NULL)
			$this->db->where('tbl_inv_mobile.pengguna', $param['pengguna']);

		if (isset($param['nik']) && $param['nik'] !== NULL)
			$this->db->where('tbl_inv_mobile.nik', $param['nik']);

		if (isset($param['active']) && $param['active'] !== NULL)
			$this->db->where('tbl_inv_mobile.na', 'n');


		if (isset($param['check']) && $param['check'] !== NULL)
			$this->db->where('tbl_inv_mobile.id_mobile !=', $param['check']);


		$this->db->where('tbl_inv_mobile.del', 'n');
		$query = $this->db->get();
		if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
			$result = $query->row();
		else $result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}
}
