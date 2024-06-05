<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : SHE
@author 		: Stah Jadianto(8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dmaster extends CI_Model{

	function get_data_bakumutu($kategori=NULL, $jenis=NULL, $parameter=NULL, $tgl_mulai=NULL, $tgl_akhir=NULL, $all=NULL){
		$this->db->select('a.id, convert(varchar, a.tanggal_mulai, 105) tgl_mulai, convert(varchar, a.tanggal_akhir, 105) tgl_akhir, 
                  b.kategori, c.jenis, d.parameter, a.bakumutu_hasilujilimit, 
                  a.bakumutu_hasilujimin, a.bakumutu_hasilujimax, a.bakumutu_bebancemarlimit, a.bakumutu_bebancemarmin, a.bakumutu_bebancemarmax ,
                  e.nama as namabuat, convert(varchar, a.tanggal_buat, 106) as tanggalbuat,f.nama as namaedit,
                  convert(varchar, a.tanggal_edit, 106) as tanggaledit, a.na, a.del');
		$this->db->from('tbl_she_bakumutu as a');
		$this->db->join('tbl_she_master_kategori as b', 'a.fk_kategori = b.id', 'inner');
		$this->db->join('tbl_she_master_jenis as c', 'a.fk_jenis = c.id', 'inner');
		$this->db->join('tbl_she_master_parameter as d', 'a.fk_parameter = d.id', 'inner');
		$this->db->join('tbl_karyawan as e', 'a.login_buat = e.id_karyawan', 'inner');
		$this->db->join('tbl_karyawan as f', 'a.login_edit = f.id_karyawan', 'left');
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
			$this->db->where('a.tanggal_mulai <=', $tgl_mulai);
			$this->db->where('a.tanggal_akhir >=', $tgl_mulai);
		}
		if($tgl_akhir != NULL){
			$this->db->where('a.tanggal_mulai <=', $tgl_akhir);
			$this->db->where('a.tanggal_akhir >=', $tgl_akhir);
		}
		if($all == NULL){
			$this->db->where('a.na', NULL);
			$this->db->where('a.del', 1);
		}
		// $this->db->order_by('id_mtujuan_inv', 'ASC');
		$query = $this->db->get();

		return $query->result();	
		
	}

	function get_data_kategori(){
		$this->general->connectDbPortal();
		$query = $this->db->get_where('tbl_she_master_kategori',array('del' => 1, 'na' => null));
		$this->general->closeDb();
		return $query->result();
	}

	function get_data_jenis(){
		$this->general->connectDbPortal();
		$query = $this->db->get_where('tbl_she_master_jenis',array('del' => 1, 'na' => null));
		$this->general->closeDb();
		return $query->result();
	}

	function get_data_parameter(){
		$this->general->connectDbPortal();
		$query = $this->db->get_where('tbl_she_master_parameter',array('del' => 1, 'na' => null));
		$this->general->closeDb();
		return $query->result();
	}

	function get_data_plant(){
		$this->general->connectDbPortal();
		$query = $this->db->get_where('tbl_wf_master_plant',array('del' => 'n', 'na' => 'n'));
		$this->general->closeDb();
		return $query->result();
	}

	function get_data_pabrik(){
		if(base64_decode($this->session->userdata("-ho-")) == 'n'){
			$query = $this->db->get_where('tbl_inv_pabrik',array('del' => 'n', 'na' => 'n', 'kode' => base64_decode($this->session->userdata("-gsber-"))));
		}else{
			$query = $this->db->get_where('tbl_inv_pabrik',array('del' => 'n', 'na' => 'n'));
		}
		
		return $query->result();	
	}

	function get_data_dtjenis($pabrik=NULL, $kategori=NULL, $jenis=NULL, $all=NULL){
		$this->db->select('a.id, d.nama as pabrik, b.kategori, c.jenis ,
			e.nama as namabuat, convert(varchar, a.tanggal_buat, 106) as tanggalbuat,f.nama as namaedit,
			convert(varchar, a.tanggal_edit, 106) as tanggaledit');
		$this->db->from('tbl_she_jenis as a');
		$this->db->join('tbl_she_master_kategori as b', 'a.fk_kategori = b.id', 'inner');
		$this->db->join('tbl_she_master_jenis as c', 'a.fk_jenis = c.id', 'inner');
		$this->db->join('tbl_inv_pabrik as d', 'a.fk_pabrik = d.id_pabrik', 'inner');
		$this->db->join('tbl_karyawan as e', 'a.login_buat = e.id_karyawan', 'inner');
		$this->db->join('tbl_karyawan as f', 'a.login_edit = f.id_karyawan', 'left');
		if($pabrik != NULL){
			$this->db->where('a.fk_pabrik', $pabrik);
		}
		if($kategori != NULL){
			$this->db->where('a.fk_kategori', $kategori);
		}
		if($jenis != NULL){
			$this->db->where_in('a.fk_jenis', $jenis);
		}
		if($all == NULL){
			$this->db->where('a.na IS NULL');
			$this->db->where('a.del', 1);
		}
		// $this->db->order_by('id_mtujuan_inv', 'ASC');
		$query = $this->db->get();

		return $query->result();	
	}

	function get_data_material($param)
	{
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		$this->db->select('vw_material_spec.code AS id');
		$this->db->select('vw_material_spec.*');
		$this->db->from('vw_material_spec');

		$this->db->where('vw_material_spec.na', 'n');
		$this->db->where('vw_material_spec.del', 'n');

		if (isset($param['code']) && $param['code'] !== NULL)
			$this->db->where('vw_material_spec.code', $param['code']);
		
		if (isset($param['search']) && $param['search'] !== NULL) {
			$this->db->group_start();
				$this->db->like('vw_material_spec.code', $param['search'], 'both');
				$this->db->or_like('vw_material_spec.description_detail', $param['search'], 'both');
				$this->db->or_like('vw_material_spec.group_description', $param['search'], 'both');
			$this->db->group_end();
		}

		$query = $this->db->get();
		if (isset($param['single_row']) && $param['single_row'] !== NULL)
			$result = $query->row();
		else
			$result = $query->result();

		if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
			$result = $this->general->generate_encrypt_json($result, $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}
}

?>