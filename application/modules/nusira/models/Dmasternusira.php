<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
        @application  : 
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 02-Jan-19
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */

class Dmasternusira extends CI_Model
{
	function get_all_bom($conn = NULL, $limit = NULL, $start = NULL, $material = NULL, $kode = NULL, $type = NULL, $idnrk = NULL, $itnum = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$string = "SELECT *,
							  X.RowNumber as id 
						 FROM (
								 SELECT ROW_NUMBER() OVER(ORDER BY tbl_pi_rfc_bom.ITNUM) AS RowNumber,
									    tbl_pi_rfc_bom.*,
									    tbl_pi_setting_material.spesifikasi,
									    tbl_pi_rfc_bom.KBETR as harga,
									    tbl_pi_setting_material.login_buat,
									    tbl_pi_setting_material.tanggal_buat,
									    CONVERT(VARCHAR(17), tbl_pi_setting_material.tanggal_edit, 113) as last_update,
									    CONVERT(VARCHAR, CONVERT(MONEY, tbl_pi_rfc_bom.KBETR), 1) as harga_money,
									    CAST(
										 (SELECT DISTINCT tbl_pi_setting_material_file.file_location + RTRIM(';')
											FROM tbl_pi_setting_material_file
										   WHERE tbl_pi_setting_material_file.matnr = tbl_pi_setting_material.matnr
										  FOR XML PATH ('')) as VARCHAR(MAX)
									    )  AS img_list
								   FROM tbl_pi_rfc_bom
								   LEFT JOIN tbl_pi_setting_material ON tbl_pi_rfc_bom.MATNR = tbl_pi_setting_material.matnr
								   									AND tbl_pi_setting_material.na = 'n'
								   									AND tbl_pi_setting_material.del = 'n' 
								  WHERE 1 = 1";

		if ($material !== NULL) {
			$string .= " 		AND (tbl_pi_rfc_bom.MAKTX LIKE '%" . $material . "%' ESCAPE '!'";
			$string .= " 		     OR tbl_pi_rfc_bom.MATNR LIKE '%" . $material . "%' ESCAPE '!')";
		}

		if ($kode !== NULL)
			$string .= " 		AND tbl_pi_rfc_bom.MATNR = '" . $kode . "'";

		if ($type !== NULL)
			$string .= " 		AND tbl_pi_rfc_bom.ITCAT = '" . $type . "'";

		if ($idnrk !== NULL)
			$string .= " 		AND tbl_pi_rfc_bom.IDNRK = '" . $idnrk . "'";

		if ($itnum !== NULL)
			$string .= " 		AND tbl_pi_rfc_bom.ITNUM = '" . $itnum . "'";

		$string .= " 	) X 
						WHERE 1=1 ";
		if ($limit !== NULL && $start !== NULL)
			$string .= " AND X.RowNumber > " . $start . "
				  			 AND X.RowNumber <= " . $limit;

		$query  = $this->db->query($string);
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	/**
	 * Get data using datatables library
	 * Rules:
	 * if you need to get data from more than 1 table,
	 *        don't forget to use alias table name in command "from" and "select" ex: tb1.column
	 */
	function get_all_bom_datatables($conn = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("ITNUM,
									   ITCAT,
									   MATNR,
									   MAKTX,
									   VALFR,
									   VALTO,
									   MENGE,
									   MEINS,
									   IDNRK,
									   spesifikasi,
									   harga,
									   img_list");
		$this->datatables->from('vw_pi_katalog_bom');

		if ($conn !== NULL)
			$this->general->closeDb();

		return $this->datatables->generate();
	}

	function get_setting_bom_file($conn = NULL, $id_setting = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_pi_setting_material_file.*');
		$this->db->from('tbl_pi_setting_material_file');
		if ($id_setting !== NULL) {
			$this->db->where('tbl_pi_setting_material_file.id_pi_setting_material', $id_setting);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();

		return $result;
	}

	function get_bom_engineering($conn = NULL, $matnr = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("MATNR,
							   BASMN,
							   BASME,
							   SPOSN,
							   IDNRK,
							   MAKTX,
							   KMPMG,
							   KMPME,
							   WERKS");
		$this->db->from("tbl_pi_rfc_bom_engineering");

		if ($matnr !== NULL) {
			$this->db->where("MATNR", $matnr);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
}
