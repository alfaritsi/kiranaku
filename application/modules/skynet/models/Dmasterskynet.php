<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : SKYNET
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dmasterskynet extends CI_Model
{

	function get_data_status()
	{
		$query = $this->db->get_where('tbl_hd_status', array('del' => 'n', 'na' => 'n'));
		return $query->result();
	}

	function get_data_status2($category = NULL)
	{
		$string	= "SELECT REPLACE(MAKTX,'Ø','coba') test,* FROM tbl_pi_rfc_bom";

		$query	= $this->db->query($string);
		$result	= $query->result();
		$this->general->closeDb();

		return $result;
	}

	function get_data_kategori()
	{
		$query = $this->db->get_where('tbl_hd_kategori', array('del' => 'n', 'na' => 'n'));
		return $query->result();
	}

	function get_data_pabrik($plant = NULL)
	{
		$ho = base64_decode($this->session->userdata("-ho-"));
		if ($plant != NULL) {
			$stringplant = " AND plant = '$plant'";
		} else {
			$stringplant = "";
		}
		$string	= "SELECT plant, plant_name FROM tbl_wf_master_plant
				WHERE na = 'n' AND del = 'n' $stringplant";
		if ($ho == 'y') {
			$string .= "UNION
				SELECT 'KTP' plant, 'PT. Kirana Triputra Persada' plant_name FROM tbl_wf_master_plant
				WHERE na = 'n' AND del = 'n'  $stringplant
				ORDER BY plant_name ";
		}
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_subkategori($category = NULL)
	{
		$string	= "SELECT * FROM tbl_hd_subkategori
				WHERE na = 'n' AND del = 'n'";
		if (isset($category) && $category != "") {
			$string .= " AND id_hd_kategori = " . $category . "";
		}

		$query	= $this->db->query($string);
		$result	= $query->result();

		$this->general->closeDb();

		return $result;
	}

	function get_data_user($gsber = NULL)
	{
		$string	= "SELECT nik, nama, id_user FROM tbl_karyawan a
				INNER JOIN tbl_user b 
					ON a.id_karyawan = b.id_karyawan
				WHERE a.na = 'n' AND a.del = 'n' and b.na = 'n' AND b.del = 'n' and gsber = '" . $gsber . "'";

		$query	= $this->db->query($string);
		$result	= $query->result();

		$this->general->closeDb();

		return $result;
	}


	function get_data_severity()
	{
		$query = $this->db->get_where('tbl_hd_severity', array('del' => 'n', 'na' => 'n'));
		return $query->result();
	}

	function get_data_agent($lokasi)
	{
		$string	= "SELECT DISTINCT lokasi,
				  CAST(
				  (
				  SELECT CAST(agent AS VARCHAR) + '.'
				  FROM tbl_hd_iploc b
				  WHERE b.lokasi = a.lokasi 
				  AND b.na = 'n' AND b.del = 'n'
				  FOR XML PATH ('')
				  ) 
				  AS VARCHAR(MAX)) agent
				FROM tbl_hd_iploc a
				WHERE a.lokasi = '" . $lokasi . "'
				AND a.na = 'n' AND a.del = 'n'";

		$query	= $this->db->query($string);
		$result	= $query->result();

		$this->general->closeDb();

		return $result;
	}

	function get_data_auto_agent($lokasi = NULL)
	{
		$string	= "EXEC SP_Kiranaku_Skynet_Autoagent @lokasi = '" . $lokasi . "'";
		$query	= $this->db->query($string);
		$result	= $query->result();

		$this->general->closeDb();

		return $result;
	}
}
