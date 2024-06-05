<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : BANK SPECIMEN
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dtransaksidata extends CI_Model
{
	/*
	* =======================================
	* 				MODEL RFC 
	* =======================================
	*/
	function push_data_rekening(){
		$string	= "
			select 
			tbl_bank_data.nomor_rekening as BANKN,
			tbl_bank_data.no_coa as HKONT,
			ZDMMSPLANT.BUKRS as BUKRS
			from 
			tbl_bank_data
			left outer join SAPSYNC.dbo.ZDMMSPLANT as ZDMMSPLANT on ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_bank_data.pabrik
			where 
			1=1
			and (status_sap is null or status_sap='n')
			and na='n'
		";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}
	
}
