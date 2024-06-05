<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : Attachment Accounting
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dsettingacc extends CI_Model{

	function get_data_user_akses($nik=NULL){
		// $this->general->connectDbPortal();

		// if(isset($nik) && $nik != ""){
		// 	$nik = " AND isnull(ho.nik_alow, pabrik.id_karyawan) = '".$nik."'";				
		// }

		$string	= "EXEC dbo.SP_Kiranaku_ACC_View_User ".$nik."";
		// echo "<pre>".$string."</pre>"; die();
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_user($nik=NULL){
		// $this->general->connectDbPortal();

		$this->db->select('*');
		$this->db->from('tbl_karyawan');
		$this->db->where('na', 'n');
		$this->db->where('del', 'n');
		if(isset($nik) && $nik != ""){
			$this->db->where('nik', $nik);
		}
		$query = $this->db->get();

		return $query->result();	
	}

	function get_user_akses_ho($nik=NULL, $plant=NULL){

		$string	= "Select id, bukrs, werks, nik_alow, nama, aktif
			from tbl_acc_approve_edit_upload 
			where 1 = 1";
		if(isset($nik) && $nik != ""){
			$string .= " AND nik_alow = '".$nik."'";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND werks = '".$plant."'";				
		}
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_user_akses_pabrik($nik=NULL, $plant=NULL){
		// $this->general->connectDbPortal();

		$string	= "Select id, id_karyawan, nama_karyawan, pabrik, aktif
			from tb_acc_user_pabrik 
			where 1 = 1";
		if(isset($nik) && $nik != ""){
			$string .= " AND id_karyawan = '".$nik."'";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND pabrik = '".$plant."'";				
		}
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_nama_user($nik=NULL){

		$string	= "Select nik, nama
			from tbl_karyawan
			where 1 = 1";
		if(isset($nik) && $nik != ""){
			$string .= " AND nik = ".$nik."";				
		}
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}




}

?>