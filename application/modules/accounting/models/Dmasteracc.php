<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  	: Attachment Accounting
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dmasteracc extends CI_Model{

	function get_data_jenis($id=NULL, $all=NULL){

		$this->db->select('*');
		$this->db->from('tbl_acc_jenis');
		if($id != NULL){
			$this->db->where('id_jenis', $id);
		}
		if($all == NULL){
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');
		}
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get();

		return $query->result();

	}


	function get_tipe_doc($id=NULL){
		$this->general->connectDbPortal();

		$this->db->select('*');
		$this->db->from('tbl_acc_tipe_doc_jurnal');
		if($id != NULL){
			$this->db->where('id', $id);
		}
		$this->db->order_by('tipe_doc');
		$query = $this->db->get();

		return $query->result();

	}

	function get_account_number($id=NULL){
		$this->general->connectDbPortal();

		$this->db->select('*');
		$this->db->from('tbl_acc_account_number');
		if($id != NULL){
			$this->db->where('id', $id);
		}
		$query = $this->db->get();

		return $query->result();

	}

	function get_pabrik($plant=NULL, $all=NULL){
		// $query = $this->db->get_where('tbl_inv_pabrik',array('del' => 'n', 'na' => 'n'));

		$this->db->select('id_plant id_pabrik, plant kode, plant_code2 plant_code, plant_name nama');
		$this->db->from('vw_tbl_acc_master_plant');
		if($plant != NULL){
			$this->db->where('plant', $plant);
		}
		if($all == NULL){
			$this->db->where('na', 'n');
			$this->db->where('del', 'n');
		}
		$this->db->order_by('nama', 'ASC');
		$query = $this->db->get();

		return $query->result();
	}

	function get_data_pabrik($nik=NULL){
		$this->general->connectDbPortal();
		$string	= "Select id_plant id_pabrik, plant kode, plant_code2 plant_code, plant_name nama
			from vw_tbl_acc_master_plant
			where 1 = 1";
		if(isset($nik) && $nik != ""){
			$string .= " AND plant in (
										select werks from tbl_acc_approve_edit_upload where nik_alow = '".$nik."' and aktif = 1 union
										select pabrik from tb_acc_user_pabrik where id_karyawan = '".$nik."' and aktif = 1
									  )
						";				
		}

		$string .= " AND na = 'n' AND del = 'n'";
		$string .= " order by nama";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $query->result();
	}

	function get_data_gl($param = NULL){
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		$this->db->select('tbl_acc_master_gl.*');
		$this->db->select("CONVERT(VARCHAR(10), CONVERT(DATE, tbl_acc_master_gl.tanggal_buat), 104) as format_tanggal_buat");
		$this->db->select('CASE tbl_acc_master_gl.na
							WHEN \'n\' THEN \'<span class="label label-success">Active</span>\'
							WHEN \'y\' THEN \'<span class="label label-danger">Not Active</span>\'
							ELSE \'<span class="label label-success">Active</span>\'
						END as view_status');
		
		$this->db->from('tbl_acc_master_gl');
		
		if (isset($param['gl_account']) && $param['gl_account'] !== NULL)
            $this->db->where('tbl_acc_master_gl.gl_account', $param['gl_account']);
		
		$query = $this->db->get();
		if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
			$result = $query->row();
		else $result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}

	function get_dropdown_gl($param = NULL){
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbDefault();

		$this->db->select('ZDMFICO14.SAKNR as id');
		$this->db->select('ZDMFICO14.SAKNR');
		$this->db->select('ZDMFICO14.TXT50');
		$this->db->from('ZDMFICO14');
		$this->db->group_start();
		$this->db->like("SUBSTRING(SAKNR, PATINDEX('%[^0]%', SAKNR+'.'), LEN(SAKNR))", '1', 'after');
		$this->db->or_like("SUBSTRING(SAKNR, PATINDEX('%[^0]%', SAKNR+'.'), LEN(SAKNR))", '2', 'after');
		$this->db->group_end();
		
		if (isset($param['search']) && $param['search'] !== NULL) {
			$this->db->group_start();
				$this->db->like('ZDMFICO14.SAKNR', $param['search'], 'both');
				$this->db->or_like('ZDMFICO14.TXT50', $param['search'], 'both');
			$this->db->group_end();
		}
		
		$query = $this->db->get();
		if (isset($param['single_row']) && $param['single_row'] !== NULL && $param['single_row'] == TRUE)
			$result = $query->row();
		else $result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}

}

?>