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
 
class Dlaporanasset extends CI_Model{
	function get_data_pengguna($conn=NULL){ 
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('distinct(tbl_inv_kategori.pengguna)');
		$this->db->from('tbl_inv_kategori');
		$this->db->where('tbl_inv_kategori.del', 'n');
		$this->db->where('tbl_inv_kategori.pengguna', 'it');

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	} 
	
    function get_data_problem($conn = NULL, $id_pabrik = NULL, $pengguna = NULL, $id_kategori = NULL)
    { 
		if ($conn !== NULL)
			$this->general->connectDbPortal();  
		
		
		$this->db->select("'Asset without name' as problem");
		$this->db->select("tbl_inv_jenis.nama as nama_jenis");
		$this->db->select("CASE
							WHEN (vw_asset.pic is not null) 
							THEN vw_asset.pic+' - '+tbl_karyawan.nama
							ELSE vw_asset.NAMA_USER
							END as pic_detail");
		$this->db->select("1 as jumlah");					
		$this->db->from('vw_asset'); 
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = vw_asset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = vw_asset.pic AND tbl_karyawan.na = \'n\'', 'left outer');		
		$this->db->where('vw_asset.na', 'n');
		$this->db->where("(vw_asset.kode_barang='N/A' or vw_asset.kode_barang='' or vw_asset.kode_barang is null)");
		// $this->db->where("id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='IT')");
		if ($id_kategori != NULL) { 
			// $filter_kategori	= ($id_kategori==12)?$id_kategori:"'".implode("','", $id_kategori)."'";	
			$this->db->where("vw_asset.id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and vw_asset.id_kondisi in(1,2) and tbl_inv_jenis.id_kategori='$id_kategori')");
		}else{
			$this->db->where("vw_asset.id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and vw_asset.id_kondisi in(1,2))");
		}
		
		$query1 = $this->db->get_compiled_select();
		
		$this->db->select("'No SAP asset number' as problem");
		$this->db->select("tbl_inv_jenis.nama as nama_jenis");
		$this->db->select("CASE
							WHEN (vw_asset.pic is not null) 
							THEN vw_asset.pic+' - '+tbl_karyawan.nama
							ELSE vw_asset.NAMA_USER
							END as pic_detail");
		$this->db->select("1 as jumlah");					
		$this->db->from('vw_asset'); 
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = vw_asset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = vw_asset.pic AND tbl_karyawan.na = \'n\'', 'left outer');		
		$this->db->where('vw_asset.na', 'n');
		$this->db->where("(vw_asset.nomor_sap='N/A' or vw_asset.nomor_sap='' or vw_asset.nomor_sap is null)");
		// $this->db->where("id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='IT')");
		if ($id_kategori != NULL) { 
			// $filter_kategori	= ($id_kategori==12)?$id_kategori:"'".implode("','", $id_kategori)."'";	
			$this->db->where("vw_asset.id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and vw_asset.id_kondisi in(1,2) and tbl_inv_jenis.id_kategori='$id_kategori')");
		}else{
			$this->db->where("vw_asset.id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and vw_asset.id_kondisi in(1,2))");
		}
		$query2 = $this->db->get_compiled_select();

		$this->db->select("'Duplicate SAP asset number' as problem");
		$this->db->select("tbl_inv_jenis.nama+' - '+vw_asset.nomor_sap as nama_jenis");
		$this->db->select("CASE
							WHEN (vw_asset.pic is not null) 
							THEN vw_asset.pic+' - '+tbl_karyawan.nama
							ELSE vw_asset.NAMA_USER
							END as pic_detail");
		$this->db->select("1 as jumlah");					
		$this->db->from('vw_asset'); 
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = vw_asset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = vw_asset.pic AND tbl_karyawan.na = \'n\'', 'left outer');		
		$this->db->where('vw_asset.na', 'n');
		$this->db->where("(select count(vw_asset2.id_aset) from vw_asset vw_asset2 where vw_asset2.na='n' and vw_asset2.nomor_sap=vw_asset.nomor_sap)>1");
		$this->db->where("nomor_sap is not null and nomor_sap!='NULL' and nomor_sap!='' and nomor_sap!='N/A'");
		// $this->db->where("id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='IT')");
		if ($id_kategori != NULL) { 
			// $filter_kategori	= ($id_kategori==12)?$id_kategori:"'".implode("','", $id_kategori)."'";	
			$this->db->where("vw_asset.id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and vw_asset.id_kondisi in(1,2) and tbl_inv_jenis.id_kategori='$id_kategori')");
		}else{
			$this->db->where("vw_asset.id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and vw_asset.id_kondisi in(1,2))");
		}
		$query3 = $this->db->get_compiled_select();		

		$this->db->select("'Duplicate asset name' as problem");
		$this->db->select("tbl_inv_jenis.nama+' - '+vw_asset.kode_barang as nama_jenis");
		$this->db->select("CASE
							WHEN (vw_asset.pic is not null) 
							THEN vw_asset.pic+' - '+tbl_karyawan.nama
							ELSE vw_asset.NAMA_USER
							END as pic_detail");
		$this->db->select("1 as jumlah");					
		$this->db->from('vw_asset');  
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = vw_asset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left outer');		
		$this->db->join('tbl_karyawan', 'tbl_karyawan.nik = vw_asset.pic AND tbl_karyawan.na = \'n\'', 'left outer');		
		$this->db->where('vw_asset.na', 'n');
		$this->db->where("(select count(vw_asset2.id_aset) from vw_asset vw_asset2 where vw_asset2.na='n' and vw_asset2.kode_barang=vw_asset.kode_barang)>1");
		$this->db->where("kode_barang is not null and kode_barang!='NULL' and kode_barang!='' and kode_barang!='N/A'");
		// $this->db->where("id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='IT')");
		if ($id_kategori != NULL) { 
			// $filter_kategori	= ($id_kategori==12)?$id_kategori:"'".implode("','", $id_kategori)."'";	
			$this->db->where("vw_asset.id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and vw_asset.id_kondisi in(1,2) and tbl_inv_jenis.id_kategori='$id_kategori')");
		}else{
			$this->db->where("vw_asset.id_pabrik='$id_pabrik' and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and vw_asset.id_kondisi in(1,2))");
		}
		$query4 = $this->db->get_compiled_select();	 	

		$query = $this->db->query($query1 . ' UNION ' . $query2. ' UNION ' . $query3. ' UNION ' . $query4);

		// $query = $this->db->get();
		$result    = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result; 
    } 
    function get_data_problem_new($conn = NULL, $id_pabrik = NULL, $pengguna = NULL, $id_kategori = NULL)
    { 
		if ($conn !== NULL)
			$this->general->connectDbPortal();  
		
		//satu	
		$this->db->select("top 1 'Asset without name' as problem");
		if ($id_kategori != NULL) { 
			$this->db->select("(select count(vw_asset2.id_aset) from vw_asset as vw_asset2 where vw_asset.id_pabrik='$id_pabrik' and (vw_asset2.kode_barang='N/A' or vw_asset2.kode_barang='' or vw_asset2.kode_barang is null) and vw_asset2.na='n' and vw_asset2.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and tbl_inv_jenis.id_kategori='$id_kategori')) as jumlah");					
		}else{
			$this->db->select("(select count(vw_asset2.id_aset) from vw_asset as vw_asset2 where vw_asset.id_pabrik='$id_pabrik' and (vw_asset2.kode_barang='N/A' or vw_asset2.kode_barang='' or vw_asset2.kode_barang is null) and vw_asset2.na='n' and vw_asset2.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna')) as jumlah");					
		}
		$this->db->from('vw_asset'); 
		$query1 = $this->db->get_compiled_select();
		
		//dua
		$this->db->select("top 1 'No SAP asset number' as problem");
		if ($id_kategori != NULL) { 
			$this->db->select("(select count(vw_asset2.id_aset) from vw_asset as vw_asset2 where vw_asset.id_pabrik='$id_pabrik' and (vw_asset2.nomor_sap='N/A' or vw_asset2.nomor_sap='' or vw_asset2.nomor_sap is null) and vw_asset2.na='n' and vw_asset2.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and tbl_inv_jenis.id_kategori='$id_kategori')) as jumlah");					
		}else{
			$this->db->select("(select count(vw_asset2.id_aset) from vw_asset as vw_asset2 where vw_asset.id_pabrik='$id_pabrik' and (vw_asset2.nomor_sap='N/A' or vw_asset2.nomor_sap='' or vw_asset2.nomor_sap is null) and vw_asset2.na='n' and vw_asset2.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna')) as jumlah");					
		}
		$this->db->from('vw_asset'); 
		$query2 = $this->db->get_compiled_select();

		//tiga
		$this->db->select("top 1 'Duplicate SAP asset number' as problem");
		if ($id_kategori != NULL) { 
			$this->db->select("(select count(distinct(vw_asset.nomor_sap)) from vw_asset where vw_asset.id_pabrik='$id_pabrik' and vw_asset.jumlah_nomor_sap>=2 and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and tbl_inv_jenis.id_kategori='$id_kategori')) as jumlah");					
		}else{
			$this->db->select("(select count(distinct(vw_asset.nomor_sap)) from vw_asset where vw_asset.id_pabrik='$id_pabrik' and vw_asset.jumlah_nomor_sap>=2 and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna')) as jumlah");
		}
		$this->db->from('vw_asset'); 
		$query3 = $this->db->get_compiled_select();		

		//empat
		$this->db->select("top 1 'Duplicate asset name' as problem");
		if ($id_kategori != NULL) { 
			$this->db->select("(select count(distinct(vw_asset.nomor_sap)) from vw_asset where vw_asset.id_pabrik='$id_pabrik' and vw_asset.jumlah_kode_barang>=2 and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna' and tbl_inv_jenis.id_kategori='$id_kategori')) as jumlah");					
		}else{
			$this->db->select("(select count(distinct(vw_asset.nomor_sap)) from vw_asset where vw_asset.id_pabrik='$id_pabrik' and vw_asset.jumlah_kode_barang>=2 and vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.na='n' and tbl_inv_jenis.pengguna='$pengguna')) as jumlah");
		}
		$this->db->from('vw_asset'); 
		$query4 = $this->db->get_compiled_select();	 	

		$query = $this->db->query($query1 . ' UNION ' . $query2. ' UNION ' . $query3. ' UNION ' . $query4);
		// $query = $this->db->query($query1 . ' UNION ' . $query2. ' UNION ' . $query3);

		// $query = $this->db->get();
		$result    = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result; 
    } 
    function get_data_area_jumlah($conn = NULL, $id_kategori = NULL, $id_pabrik = NULL, $pengguna = NULL)
    {  
		if ($conn !== NULL)
			$this->general->connectDbPortal(); 
		
		$this->db->select('vw_asset.id_area');
		// $this->db->select('tbl_inv_area.nama');
		$this->db->select("
				CASE
				WHEN (tbl_inv_area.nama is not null) 
				THEN tbl_inv_area.nama
				ELSE 'Not Set'
				END as nama
		");
		if ($id_kategori != NULL) {
			$this->db->select("
				CASE
				WHEN (tbl_inv_area.nama is not null) 
				THEN 
				  (
				  select 
				  count(vw_asset2.id_aset) 
				  from 
				  vw_asset vw_asset2 
				  where
				  vw_asset2.id_pabrik='$id_pabrik'
				  and vw_asset2.na='n' 
				  and vw_asset2.id_kondisi in(1,2) 
				  and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where id_kategori='$id_kategori' and na='n') 
				  and vw_asset2.id_area = vw_asset.id_area
				  )                  
				ELSE 
				  (
				  select 
				  count(vw_asset2.id_aset) 
				  from 
				  vw_asset vw_asset2 
				  where
				  vw_asset2.id_pabrik='$id_pabrik' 
				  and vw_asset2.na='n' 
				  and vw_asset2.id_kondisi in(1,2) 
				  and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where id_kategori='$id_kategori' and na='n') 
				  and vw_asset2.id_area is null
				  )                  
				END as jumlah_aset
			");
		}else{
			$this->db->select("
				CASE
				WHEN (tbl_inv_area.nama is not null) 
				THEN 
				  (
				  select 
				  count(vw_asset2.id_aset) 
				  from 
				  vw_asset vw_asset2 
				  where
				  vw_asset2.id_pabrik='$id_pabrik'
				  and vw_asset2.na='n' 
				  and vw_asset2.id_kondisi in(1,2) 
				  and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where pengguna='$pengguna' and na='n') 
				  and vw_asset2.id_area = vw_asset.id_area
				  )                  
				ELSE 
				  (
				  select 
				  count(vw_asset2.id_aset) 
				  from 
				  vw_asset vw_asset2 
				  where
				  vw_asset2.id_pabrik='$id_pabrik' 
				  and vw_asset2.na='n' 
				  and vw_asset2.id_kondisi in(1,2) 
				  and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where pengguna='$pengguna' and na='n') 
				  and vw_asset2.id_area is null
				  )                  
				END as jumlah_aset
			");
		}
		$this->db->from('vw_asset');
		$this->db->join('tbl_inv_area', 'tbl_inv_area.id_area=vw_asset.id_area', 'left outer');		
		$this->db->where('vw_asset.na', 'n');
		$this->db->where("vw_asset.id_kondisi in(1,2)");
		if ($id_kategori != NULL) {
			$this->db->where("vw_asset.id_jenis in(select id_jenis from tbl_inv_jenis where id_kategori='$id_kategori' and na='n')");	
		}else{
			$this->db->where("vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where pengguna='$pengguna')");
		}
		if ($id_pabrik != NULL) {  
			$this->db->where("vw_asset.id_pabrik='$id_pabrik'");  
		}
		$this->db->group_by(array('vw_asset.id_area','tbl_inv_area.nama'));

		$query = $this->db->get();
		$result    = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
	
    function get_data_lokasi_jumlah($conn = NULL, $id_kategori = NULL, $id_pabrik = NULL, $pengguna = NULL)
    {  
		if ($conn !== NULL)
			$this->general->connectDbPortal(); 
		
		$this->db->select('vw_asset.id_lokasi');
		// $this->db->select('tbl_inv_lokasi.nama');
		$this->db->select("
				CASE
				WHEN (tbl_inv_lokasi.nama is not null) 
				THEN tbl_inv_lokasi.nama
				ELSE 'Not Set'
				END as nama
		");
		if ($id_kategori != NULL) {
			// $this->db->select('1 as jumlah_aset');
			$this->db->select("
				CASE
				WHEN (tbl_inv_lokasi.nama is not null) 
				THEN 
				  (
				  select 
				  count(vw_asset2.id_aset) 
				  from 
				  vw_asset vw_asset2 
				  where
				  vw_asset2.id_pabrik='$id_pabrik'
				  and vw_asset2.na='n' 
				  and vw_asset2.id_kondisi in(1,2) 
				  and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where id_kategori='$id_kategori' and na='n') 
				  and vw_asset2.id_lokasi = vw_asset.id_lokasi
				  )                  


				ELSE 
				  (
				  select 
				  count(vw_asset2.id_aset) 
				  from 
				  vw_asset vw_asset2 
				  where
				  vw_asset2.id_pabrik='$id_pabrik' 
				  and vw_asset2.na='n' 
				  and vw_asset2.id_kondisi in(1,2) 
				  and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where id_kategori='$id_kategori' and na='n') 
				  and vw_asset2.id_lokasi is null
				  )                  
				END as jumlah_aset
			");
			// $this->db->select("
				// CASE
				// WHEN (tbl_inv_lokasi.nama is not null) 
				// THEN 
				  // (
				  // select 
				  // count(vw_asset2.id_aset) 
				  // from 
				  // vw_asset vw_asset2 
				  // where
				  // vw_asset2.id_pabrik='$id_pabrik'
				  // and vw_asset2.na='n' 
				  // and vw_asset2.id_kondisi in(1,2) 
				  // and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where id_kategori='$id_kategori' and na='n') 
				  // and vw_asset2.id_area = tbl_inv_lokasi.id_lokasi
				  // )                  
				// ELSE 
				  // (
				  // select 
				  // count(vw_asset2.id_aset) 
				  // from 
				  // vw_asset vw_asset2 
				  // where
				  // vw_asset2.id_pabrik='$id_pabrik' 
				  // and vw_asset2.na='n' 
				  // and vw_asset2.id_kondisi in(1,2) 
				  // and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where id_kategori='$id_kategori' and na='n') 
				  // and vw_asset2.id_lokasi is null
				  // )                  
				// END as jumlah_aset
			// ");
		}else{
			$this->db->select("
				CASE
				WHEN (tbl_inv_lokasi.nama is not null) 
				THEN 
				  (
				  select 
				  count(vw_asset2.id_aset) 
				  from 
				  vw_asset vw_asset2 
				  where
				  vw_asset2.id_pabrik='$id_pabrik'
				  and vw_asset2.na='n' 
				  and vw_asset2.id_kondisi in(1,2) 
				  and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where pengguna='$pengguna' and na='n') 
				  and vw_asset2.id_lokasi = vw_asset.id_lokasi
				  )                  
				ELSE 
				  (
				  select 
				  count(vw_asset2.id_aset) 
				  from 
				  vw_asset vw_asset2 
				  where
				  vw_asset2.id_pabrik='$id_pabrik' 
				  and vw_asset2.na='n' 
				  and vw_asset2.id_kondisi in(1,2) 
				  and vw_asset2.id_jenis in(select id_jenis from tbl_inv_jenis where pengguna='$pengguna' and na='n') 
				  and vw_asset2.id_lokasi is null
				  )                  
				END as jumlah_aset
			");
		}
		$this->db->from('vw_asset');
		$this->db->join('tbl_inv_lokasi', 'tbl_inv_lokasi.id_lokasi=vw_asset.id_lokasi', 'left outer');		
		$this->db->where('vw_asset.na', 'n');
		$this->db->where("vw_asset.id_kondisi in(1,2)");
		if ($id_kategori != NULL) {
			$this->db->where("vw_asset.id_jenis in(select id_jenis from tbl_inv_jenis where id_kategori='$id_kategori' and na='n')");	
		}else{
			$this->db->where("vw_asset.id_jenis in(select tbl_inv_jenis.id_jenis from tbl_inv_jenis where pengguna='$pengguna')");
		}
		if ($id_pabrik != NULL) {  
			$this->db->where("vw_asset.id_pabrik='$id_pabrik'");  
		}
		$this->db->group_by(array('vw_asset.id_lokasi','tbl_inv_lokasi.nama'));

		$query = $this->db->get();
		$result    = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
	
    function get_data_jenis_jumlah($conn = NULL, $id_kategori = NULL, $id_pabrik = NULL, $pengguna = NULL)
    {
		if ($conn !== NULL)
			$this->general->connectDbPortal(); 
		
		$this->db->select('tbl_inv_jenis.*');
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_jenis=tbl_inv_jenis.id_jenis and vw_asset.id_kondisi in(1,6) and vw_asset.id_pabrik='$id_pabrik' and na='n') as asset");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_jenis=tbl_inv_jenis.id_jenis and vw_asset.id_kondisi in(2,5) and vw_asset.id_pabrik='$id_pabrik' and na='n') as damage");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_jenis=tbl_inv_jenis.id_jenis and vw_asset.id_kondisi=4 and vw_asset.id_pabrik='$id_pabrik' and na='n') as repaired");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_jenis=tbl_inv_jenis.id_jenis and vw_asset.id_pabrik='$id_pabrik' and na='n') as jumlah_aset");
		$this->db->from('tbl_inv_jenis');
		$this->db->where('tbl_inv_jenis.na', 'n');
		$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		if ($id_kategori != NULL) {
			// $id_kategori	= ($id_kategori==12)?$id_kategori:"'".implode("','", $id_kategori)."'";	
			$this->db->where("tbl_inv_jenis.id_kategori=$id_kategori");
		}
		$query = $this->db->get();
		$result    = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result; 
    }
	
    function get_data_kategori_jumlah($conn = NULL, $id_pabrik = NULL, $pengguna = NULL) 
    {
		if ($conn !== NULL)
			$this->general->connectDbPortal(); 
		
		
		$this->db->select('tbl_inv_kategori.*'); 
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_jenis in (select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.id_kategori=tbl_inv_kategori.id_kategori) and vw_asset.id_kondisi in(1,6) and vw_asset.id_pabrik='$id_pabrik' and na='n') as asset");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_jenis in (select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.id_kategori=tbl_inv_kategori.id_kategori) and vw_asset.id_kondisi in(2,5) and vw_asset.id_pabrik='$id_pabrik' and na='n') as damage");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_jenis in (select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.id_kategori=tbl_inv_kategori.id_kategori) and vw_asset.id_kondisi=4 and vw_asset.id_pabrik='$id_pabrik' and na='n') as repaired");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_jenis in (select tbl_inv_jenis.id_jenis from tbl_inv_jenis where tbl_inv_jenis.id_kategori=tbl_inv_kategori.id_kategori) and vw_asset.id_pabrik='$id_pabrik' and vw_asset.na='n') as jumlah_aset");
		$this->db->from('tbl_inv_kategori');
		$this->db->where('tbl_inv_kategori.na', 'n');
		if ($pengguna != NULL) {
			$this->db->where('tbl_inv_kategori.pengguna', $pengguna);
		}
		$query = $this->db->get();
		$result    = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
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
		$this->db->join('tbl_program_budget', 'tbl_program_budget.id_program = tbl_program.id_program 
										 AND tbl_program_budget.na = \'n\' and tbl_program_budget.tahun = \'2018\'', 'left');		
		$this->db->where('tbl_program_batch.na', 'n');
		$this->db->where('tbl_program_batch.del', 'n');
		$this->db->limit(5);
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_data_jumlah($conn = NULL, $id_aset = NULL, $active = NULL, $deleted = 'n', $nama = NULL, $pengguna = NULL, $alat = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';

		$this->db->select('vw_asset.id_jenis');
		$this->db->select('vw_asset.id_kategori');
		$this->db->select('vw_asset.id_pabrik');
		$this->db->select('tbl_inv_jenis.nama as nama_jenis');
		$this->db->select('tbl_inv_pabrik.nama as nama_pabrik');
		$this->db->select('tbl_inv_jenis.pengguna');
		$this->db->select("(select tbl_inv_kategori.nama from tbl_inv_kategori where tbl_inv_kategori.id_kategori=vw_asset.id_kategori) as nama_kategori");
		//update by id_jenis
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_kondisi in('1','2') and  vw_asset.id_jenis=vw_asset.id_jenis and vw_asset.id_pabrik=vw_asset.id_pabrik) as tot_aset");
		$this->db->select("(select count(distinct(id_aset)) from tbl_inv_main where id_aset in (select vw_asset.id_aset from vw_asset where vw_asset.id_kondisi in('1','2') and  vw_asset.id_jenis=vw_asset.id_jenis and vw_asset.id_pabrik=vw_asset.id_pabrik)) as tot_update");
		$this->db->select("(select top 1 tbl_inv_main.tanggal_buat from tbl_inv_main left join vw_asset vw_asset2 on vw_asset2.id_aset=tbl_inv_main.id_aset where vw_asset2.id_pabrik=vw_asset.id_pabrik and tbl_inv_main.id_jenis=vw_asset.id_jenis and tbl_inv_main.na='n') as last_update");
		//status by id_jenis
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_kondisi='1' and vw_asset.id_jenis=vw_asset.id_jenis and vw_asset.id_pabrik=vw_asset.id_pabrik) as tot_beroperasi");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_kondisi='2' and vw_asset.id_jenis=vw_asset.id_jenis and vw_asset.id_pabrik=vw_asset.id_pabrik) as tot_rusak");
		if($pengguna=='fo'){		
			$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.id_kondisi='1' and vw_asset.id_jenis=vw_asset.id_jenis and vw_asset.id_pabrik=vw_asset.id_pabrik and vw_asset.aging>0) as tot_expired");
		}
		//status by id_jenis dan cop
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.tipe_aset='COP' and vw_asset.id_kondisi='1' and  vw_asset.id_jenis=vw_asset.id_jenis and vw_asset.id_pabrik=vw_asset.id_pabrik) as cop_beroperasi");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.tipe_aset='COP' and vw_asset.id_kondisi='2' and  vw_asset.id_jenis=vw_asset.id_jenis and vw_asset.id_pabrik=vw_asset.id_pabrik) as cop_rusak");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.tipe_aset='Perusahaan' and vw_asset.id_kondisi='1' and  vw_asset.id_jenis=vw_asset.id_jenis and vw_asset.id_pabrik=vw_asset.id_pabrik) as perusahaan_beroperasi");
		$this->db->select("(select count(vw_asset.id_aset) from vw_asset where vw_asset.tipe_aset='Perusahaan' and vw_asset.id_kondisi='2' and  vw_asset.id_jenis=vw_asset.id_jenis and vw_asset.id_pabrik=vw_asset.id_pabrik) as perusahaan_rusak");

		$this->db->from('vw_asset');
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = vw_asset.id_jenis AND tbl_inv_jenis.na = \'n\'', 'left');		
		$this->db->join('tbl_inv_kategori', 'tbl_inv_kategori.id_kategori = vw_asset.id_kategori AND tbl_inv_kategori.na = \'n\'', 'left');		
		$this->db->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = vw_asset.id_pabrik AND tbl_inv_pabrik.na = \'n\'', 'left');		
		if ($id_aset !== NULL) {
			$this->db->where('vw_asset.id_aset', $id_aset);
		}
		if ($active !== NULL) {
			$this->db->where('vw_asset.na', $active);
		}
		if ($nama !== NULL) {
			$this->db->where('vw_asset.nomor_sap', $nama);
		}
		if ($pengguna !== NULL) {
			$this->db->where('tbl_inv_jenis.pengguna', $pengguna);
		}
		if ($alat !== NULL) {
			if($alat=='berat'){
				$this->db->where("tbl_inv_jenis.berat='y'");	
			}
			if($alat=='lab'){
				$this->db->where("tbl_inv_jenis.berat='n'");	
			}
		}
		if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			$this->db->where("vw_asset.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
		}
		$this->db->where('vw_asset.del', $deleted);
		$this->db->where('vw_asset.id_pabrik!=', '');
		$this->db->group_by(array('vw_asset.id_jenis','vw_asset.id_kategori','vw_asset.id_pabrik','tbl_inv_jenis.nama','tbl_inv_pabrik.nama','tbl_inv_jenis.pengguna'));
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_transaksi($conn = NULL, $id_inv_doc_transaksi = NULL, $active = NULL, $deleted = 'n', $pabrik = NULL, $dokumen = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		if (is_null($deleted))
			$deleted = 'n';
		$this->db->select('tbl_inv_doc_transaksi.*');
		$this->db->select('tbl_inv_doc.nama as nama_dokumen');
		$this->db->select("CASE
								WHEN (tbl_inv_doc.periode = '0' or tbl_inv_doc.periode = '') 
								THEN '-'
								ELSE CONVERT(VARCHAR(MAX), ISNULL(tbl_inv_doc.periode,0))+RTRIM(' Bulan')	
								END as periode");
		$this->db->select('tbl_inv_pabrik.nama as nama_pabrik');
		$this->db->select("DATEDIFF(day, getdate(), tbl_inv_doc_transaksi.tanggal_berakhir) as selisih_hari");
		$this->db->select('vw_asset.nomor_polisi');
		$this->db->select('vw_asset.nomor_sap');
		$this->db->select('tbl_inv_jenis.nama as nama_jenis');
		$this->db->select('tbl_inv_merk.nama as nama_merk');
		$this->db->from('tbl_inv_doc_transaksi');
		$this->db->join('tbl_inv_doc', 'tbl_inv_doc.id_inv_doc = tbl_inv_doc_transaksi.id_inv_doc and tbl_inv_doc.na =\'n\'', 'left');		
		$this->db->join('vw_asset', 'vw_asset.id_aset = tbl_inv_doc_transaksi.id_aset', 'left');		
		$this->db->join('tbl_inv_pabrik', 'tbl_inv_pabrik.id_pabrik = vw_asset.id_pabrik', 'left');		
		$this->db->join('tbl_inv_jenis', 'tbl_inv_jenis.id_jenis = vw_asset.id_jenis', 'left');		
		$this->db->join('tbl_inv_merk', 'tbl_inv_merk.id_merk = vw_asset.id_merk', 'left');		
		if ($id_inv_doc_transaksi !== NULL) {
			$this->db->where('tbl_inv_doc_transaksi.id_inv_doc_transaksi', $id_inv_doc_transaksi);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_inv_doc_transaksi.na', $active);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->db->where_in('tbl_inv_pabrik.id_pabrik', $pabrik);
		}
		if($dokumen != NULL){
			if(is_string($dokumen)) $dokumen = explode(",", $dokumen);
			$this->db->where_in('tbl_inv_doc.id_inv_doc', $dokumen);
		}
		if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			$this->db->where("vw_asset.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
		}
		
		$this->db->where('tbl_inv_doc_transaksi.del', $deleted);
		$this->db->order_by("tbl_inv_doc_transaksi.id_inv_doc_transaksi", "asc");
		// $this->db->limit(5);
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	
	function get_data_asset_kategori($conn = NULL, $nama_kategori = NULL, $id_pabrik = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('nama_kategori');
		$this->db->select('nama_jenis');
		$this->db->select('nama_merk');
		$this->db->select('nama_merk_tipe');
		$this->db->select('id_merk_tipe');
		$this->db->select("(select count(vw_asset2.id_aset) from vw_asset vw_asset2 where vw_asset2.id_pabrik='$id_pabrik' and vw_asset2.id_kondisi in(6) and vw_asset2.nama_kategori=vw_asset.nama_kategori and  vw_asset2.nama_jenis=vw_asset.nama_jenis and  vw_asset2.nama_merk=vw_asset.nama_merk and vw_asset2.nama_merk_tipe=vw_asset.nama_merk_tipe and na='n') as jumlah_standby");
		$this->db->select("(select count(vw_asset2.id_aset) from vw_asset vw_asset2 where vw_asset2.id_pabrik='$id_pabrik' and vw_asset2.id_kondisi in(5) and vw_asset2.nama_kategori=vw_asset.nama_kategori and  vw_asset2.nama_jenis=vw_asset.nama_jenis and  vw_asset2.nama_merk=vw_asset.nama_merk and vw_asset2.nama_merk_tipe=vw_asset.nama_merk_tipe and na='n') as jumlah_scrap");
		$this->db->select("(select count(vw_asset2.id_aset) from vw_asset vw_asset2 where vw_asset2.id_pabrik='$id_pabrik' and vw_asset2.id_kondisi in(4) and vw_asset2.nama_kategori=vw_asset.nama_kategori and  vw_asset2.nama_jenis=vw_asset.nama_jenis and  vw_asset2.nama_merk=vw_asset.nama_merk and vw_asset2.nama_merk_tipe=vw_asset.nama_merk_tipe and na='n') as jumlah_dalam_perbaikan");
		$this->db->select("(select count(vw_asset2.id_aset) from vw_asset vw_asset2 where vw_asset2.id_pabrik='$id_pabrik' and vw_asset2.id_kondisi in(2) and vw_asset2.nama_kategori=vw_asset.nama_kategori and  vw_asset2.nama_jenis=vw_asset.nama_jenis and  vw_asset2.nama_merk=vw_asset.nama_merk and vw_asset2.nama_merk_tipe=vw_asset.nama_merk_tipe and na='n') as jumlah_tidak_beropersai");
		$this->db->select("(select count(vw_asset2.id_aset) from vw_asset vw_asset2 where vw_asset2.id_pabrik='$id_pabrik' and vw_asset2.id_kondisi in(1) and vw_asset2.nama_kategori=vw_asset.nama_kategori and  vw_asset2.nama_jenis=vw_asset.nama_jenis and  vw_asset2.nama_merk=vw_asset.nama_merk and vw_asset2.nama_merk_tipe=vw_asset.nama_merk_tipe and na='n') as jumlah_beroperasi");
		$this->db->from('vw_asset');
		if ($nama_kategori !== NULL) {
			$this->db->where("nama_kategori='$nama_kategori'");
		}
		$this->db->group_by(array('nama_kategori','nama_jenis','nama_merk','nama_merk_tipe','id_merk_tipe'));
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_pabrik($conn=NULL, $kode_pabrik=NULL){
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_inv_pabrik.*');
				
		$this->db->from('tbl_inv_pabrik');

		$this->db->where('tbl_inv_pabrik.del', 'n');
		$this->db->where('tbl_inv_pabrik.na', 'n');
		if (base64_decode($this->session->userdata("-ho-")) !== 'y') {
			$this->db->where("tbl_inv_pabrik.id_pabrik in (select id_pabrik from tbl_inv_pabrik where kode='".base64_decode($this->session->userdata("-gsber-"))."')");
		}
		if ($kode_pabrik !== NULL) {
			$this->db->where("tbl_inv_pabrik.kode='$kode_pabrik'");
		}

		$query 	= $this->db->get();
		$result	= $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	
}
?>