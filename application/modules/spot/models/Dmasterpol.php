<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Simulasi Penjualan SPOT
@author       : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

class Dmasterpol extends CI_Model
{
	// function get_master_plant($plant_in=NULL){
	// // $this->db->query("SET ANSI_NULLS ON");
	// // $this->db->query("SET ANSI_WARNINGS ON");
	// $string = '
	// SELECT DISTINCT ZDMMSPLANT.PERSA as plant_code,
	// ZDMMSPLANT.WERKS as plant,
	// ZDMMSPLANT.TPPCO as factory,	
	// ZDMMSPLANT.NAME1 as plant_name,
	// tbl_wf_region.region_name
	// --FROM SAPSYNC.dbo.ZDMMSPLANT
	// FROM SAPSYNC.dbo.ZDMMSPLANT
	// LEFT JOIN tbl_wf_region ON ZDMMSPLANT.PERSA = tbl_wf_region.plant_code COLLATE SQL_Latin1_General_CP1_CS_AS AND tbl_wf_region.na = \'n\'  AND tbl_wf_region.del = \'n\'
	// LEFT JOIN tbl_spot_prod_cost ON tbl_spot_prod_cost.werks = tbl_wf_region.plant_code COLLATE SQL_Latin1_General_CP1_CS_AS AND tbl_wf_region.na = \'n\'  AND tbl_wf_region.del = \'n\'
	// WHERE 1=1';
	// if($plant_in != NULL){
	// if(count($plant_in) <= 1){
	// $plant_in = "'".$plant_in[0]."'";
	// }
	// else
	// $plant_in = "'".implode("','", $plant_in)."'";

	// $string .= ' AND ZDMMSPLANT.WERKS IN ('.$plant_in.')';
	// }
	// $string .= ' ORDER BY plant ASC';
	// $query = $this->db->query($string);
	// return $query->result();
	// }

	function get_data_port($conn = NULL, $port = NULL, $active = NULL, $deleted = 'n')
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_spot_setting_port.*');
		$this->db->select('tbl_spot_setting_pol.selisih');
		$this->db->from('tbl_spot_setting_port');
		$this->db->join('tbl_spot_setting_pol', 'tbl_spot_setting_pol.port = tbl_spot_setting_port.port', 'left outer');
		if ($port !== NULL) {
			$this->db->where('tbl_spot_setting_port.port', $port);
		}
		$this->db->order_by("tbl_spot_setting_pol.no_urut", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_buyer($conn = NULL, $NAME1 = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('ZDMMSCUSTMR.KUNNR');
		$this->db->select('ZDMMSCUSTMR.NAME2 as NMBYR');
		$this->db->from('SAPSYNC.dbo.ZDMMSCUSTMR');
		$this->db->join('SAPSYNC.dbo.ZDMMKTSUPP54', 'ZDMMSCUSTMR.KUNNR=ZDMMKTSUPP54.KUNNR', 'left outer');
		if ($NAME1 !== NULL) {
			$this->db->where('ZDMMSCUSTMR.KUNNR', $NAME1);
		}
		$this->db->where("ZDMMSCUSTMR.NAME2!=''");
		$this->db->where("ZDMMSCUSTMR.VTWEG in('01','02','03')");
		$this->db->where("ZDMMSCUSTMR.KTOKD = 'Y001'");
		$this->db->where("ZDMMKTSUPP54.PAVIP in ('3','4','5')");
		$this->db->where("ZDMMKTSUPP54.SMTP_ADDR is not null");

		$this->db->order_by("ZDMMSCUSTMR.NAME2", "asc");
		$this->db->group_by('ZDMMSCUSTMR.KUNNR,ZDMMSCUSTMR.NAME2');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_plant($conn = NULL, $plant = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('ZDMMSPLANT.*');
		$this->db->select('ZDMMSPLANT.WERKS as plant');
		$this->db->select('ZDMMSPLANT.TPPCO as factory');
		$this->db->select('ZDMMSPLANT.NAME1 as plant_name');
		$this->db->select('ZDMMSPLANT.REGION as region_name');
		$this->db->from('SAPSYNC.dbo.ZDMMSPLANT');
		if ($plant !== NULL) {
			$this->db->where('ZDMMSPLANT.werks', $plant);
		}
		$this->db->where("CHARINDEX(ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS, (select plant_exclude from tbl_mapping_plant_header where apps='spot'))=0");
		$this->db->order_by("ZDMMSPLANT.werks", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_pol($conn = NULL, $id_spot_setting_pol = NULL, $active = NULL, $deleted = 'n', $port = NULL, $werks = NULL, $name = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_spot_setting_pol.*');
		$this->db->select('tbl_spot_setting_port.name as nama_port');
		$this->db->select('CASE
								WHEN tbl_spot_setting_pol.na = \'n\' THEN \'<span class="label label-success">AKTIF</span>\'
								ELSE \'<span class="label label-danger">NON AKTIF</span>\'
						   END as label_active');
		$this->db->select("CAST( 
							( 
							  SELECT ZDMMSPLANT.NAME1 + RTRIM(',') 
								FROM SAPSYNC.dbo.ZDMMSPLANT 
							   WHERE  CHARINDEX(''''+CONVERT(varchar(10), ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)+'''',''''+REPLACE(tbl_spot_setting_pol.werks, RTRIM(','),''',''')+'''') > 0 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_plant");
		$this->db->from('tbl_spot_setting_pol');
		$this->db->join('tbl_spot_setting_port', 'tbl_spot_setting_pol.port = tbl_spot_setting_port.port', 'left outer');
		if ($id_spot_setting_pol !== NULL) {
			$this->db->where('tbl_spot_setting_pol.id_spot_setting_pol', $id_spot_setting_pol);
		}
		if ($active !== NULL) {
			$this->db->where('tbl_spot_setting_pol.na', $active);
		}
		if ($deleted !== NULL) {
			$this->db->where('tbl_spot_setting_pol.del', $deleted);
		}
		if ($port !== NULL) {
			$this->db->where('tbl_spot_setting_pol.port', $port);
		}
		if ($werks !== NULL) {
			$this->db->where("tbl_spot_setting_pol.werks like '%$werks%'");
		}
		if ($name !== NULL) {
			$this->db->where("tbl_spot_setting_port.name='$name'");
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_cost($conn = NULL, $werks = NULL, $tahun = NULL, $type = NULL, $port_1 = NULL)
	{
		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		$thisdate = date("Y-m-d");
		$port_1 = $port_1 == NULL ? 'null' : $port_1;
		// // query diganti SP
		$string = "EXEC portal_dev.dbo.SP_Spot_Data $port_1, '$thisdate' ";
		// $string = "EXEC portal.dbo.SP_Spot_Data $port_1, '$thisdate' ";	
		// var_dump($string);
		// $string = "
		// SELECT 
		// ZDMMSPLANT.*,
		// ZDMMSPLANT.WERKS as plant,
		// ZDMMSPLANT.NAME1 as plant_name,
		// (select top 1 tbl_spot_prod_cost.cost from ".DB_PORTAL.".dbo.tbl_spot_prod_cost where tbl_spot_prod_cost.werks=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS) as cost,
		// (select top 1 tbl_spot_prod_cost.note from ".DB_PORTAL.".dbo.tbl_spot_prod_cost where tbl_spot_prod_cost.werks=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS) as note,
		// --(select top 1 tb_harga_beli_blended.HargaBlended from SAPSYNC.dbo.tb_harga_beli_blended where tb_harga_beli_blended.Plant=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS order by tb_harga_beli_blended.periode DESC) as mtd,
		// (select top 1 t_hargaspot.hrgspot from reporttemplate.rpt.t_hargaspot  where t_hargaspot.name_coy=ZDMMSPLANT.WERKS) as mtd,
		// (select count(tbl_spot_setting_pol.port) from ".DB_PORTAL.".dbo.tbl_spot_setting_pol where tbl_spot_setting_pol.WERKS like '%'+ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS+'%') as jumlah_pol,
		// CAST( 
		// ( 
		// SELECT tbl_spot_setting_port.port+'|'+tbl_spot_setting_port.name+' - '+CONVERT(varchar(255), $port_1-tbl_spot_setting_pol.selisih) + RTRIM(',') 
		// FROM ".DB_PORTAL.".dbo.tbl_spot_setting_port 
		// left outer join ".DB_PORTAL.".dbo.tbl_spot_setting_pol on tbl_spot_setting_pol.port = tbl_spot_setting_port.port
		// WHERE  
		// tbl_spot_setting_port.na='n'
		// and CHARINDEX(''''+CONVERT(varchar(10), ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)+'''',''''+REPLACE(tbl_spot_setting_pol.werks, RTRIM(','),''',''')+'''') > 0 
		// and tbl_spot_setting_pol.WERKS like '%'+ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS+'%'
		// order by tbl_spot_setting_port.port asc  
		// FOR XML PATH ('') 
		// ) as VARCHAR(MAX) 
		// ) as list_port,
		// CAST( 
		// ( 
		// SELECT ZDMMSCUSTMR.KUNNR+'|'+ZDMMSCUSTMR.KUNNR+' - '+ZDMMSCUSTMR.NAME1+ RTRIM(',') 
		// FROM 
		// SAPSYNC.dbo.ZDMMSCUSTMR
		// LEFT OUTER JOIN SAPSYNC.dbo.ZDMMKTSUPP54 ON ZDMMSCUSTMR.KUNNR=ZDMMKTSUPP54.KUNNR
		// WHERE 
		// ZDMMSCUSTMR.VTWEG in('01','02') and
		// ZDMMSCUSTMR.NAME1!='' and
		// ZDMMSCUSTMR.KTOKD = 'Y001' and 
		// ZDMMKTSUPP54.PAVIP in ('3','4','5') and
		// ZDMMKTSUPP54.SMTP_ADDR is not null
		// group by ZDMMSCUSTMR.KUNNR+'|'+ZDMMSCUSTMR.KUNNR+' - '+ZDMMSCUSTMR.NAME1
		// order by ZDMMSCUSTMR.KUNNR+'|'+ZDMMSCUSTMR.KUNNR+' - '+ZDMMSCUSTMR.NAME1 asc 
		// FOR XML PATH ('') 
		// ) as VARCHAR(MAX) 
		// ) as list_buyer,
		// (select top 1 tbl_spot_setting_pol.selisih from ".DB_PORTAL.".dbo.tbl_spot_setting_pol where tbl_spot_setting_pol.na='n' and tbl_spot_setting_pol.WERKS like '%'+ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS+'%' order by tbl_spot_setting_pol.port asc) as selisih,		
		// (select SUM(ZDMFICO02.DMBTR) from SAPSYNC.dbo.ZDMFICO02 where ZDMFICO02.GJAHR='2019' and ZDMFICO02.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO02.COGRP not in('24. Biaya Bank','24. Biaya Bank dan Provisi','25. Biaya Bunga Bank dan Pihak Ketiga','26. Selisih Kurs Transaksi / Realized','27. Selisih Kurs Translasi / Unrealized','Biaya Pajak Penghasilan','Biaya Pajak Penghasilan - Tangguhan','Number of Employee','Qty. Production','Qty. Purchase','Qty. Sales')) + 
		// (select SUM(tbl_pdca_acc_hocost.TARGET_MTD) from ".DB_PORTAL.".dbo.tbl_pdca_acc_hocost where tbl_pdca_acc_hocost.GJAHR='2019' and tbl_pdca_acc_hocost.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)
		// as biaya_tahun,
		// (select SUM(ZDMFICO02.DMBTR) from SAPSYNC.dbo.ZDMFICO02 where ZDMFICO02.GJAHR='2019' and ZDMFICO02.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO02.COGRP='Qty. Production') as qty_tahun,
		// (select ZDMPURBKR04.QTOUM from SAPSYNC.dbo.ZDMPURBKR04 where ZDMPURBKR04.EKORG=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMPURBKR04.BEDAT=(select top 1 ZKISSTT_0138.ACTDT from SAPSYNC.dbo.ZKISSTT_0138 where ZKISSTT_0138.ACTDT< convert(varchar(10), getdate(),112) and (ZKISSTT_0138.HOLDT!='X' or ZKISSTT_0138.HOLDT is null) order by ZKISSTT_0138.ACTDT DESC)) * -1 as ocp
		// FROM
		// SAPSYNC.dbo.ZDMMSPLANT	
		// WHERE 
		// ZDMMSPLANT.WERKS!=''
		// AND ZDMMSPLANT.WERKS!='KPK1'
		// AND ZDMMSPLANT.TPPCO!=''
		// AND ZDMMSPLANT.TPPCO!='KJP2'
		// AND ZDMMSPLANT.WERKS!='KUT2'
		// ";
		// if ($werks !== NULL) {
		// $string .= " AND ZDMMSPLANT.WERKS='$werks'";
		// }

		$query = $this->db->query($string);
		// var_dump($query->result());
		return $query->result();
	}


	function get_data_cost_old($conn = NULL, $werks = NULL, $tahun = NULL, $type = NULL, $port_1 = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		// $yesterday = date("Y-m-d", strtotime('-1 days'));

		$this->db->select("ZDMMSPLANT.*");
		$this->db->select("ZDMMSPLANT.WERKS as plant");
		$this->db->select("ZDMMSPLANT.NAME1 as plant_name");
		$this->db->select("(select top 1 tbl_spot_prod_cost.cost from tbl_spot_prod_cost where tbl_spot_prod_cost.werks=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS) as cost");
		$this->db->select("(select top 1 tbl_spot_prod_cost.note from tbl_spot_prod_cost where tbl_spot_prod_cost.werks=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS) as note");
		$this->db->select("(select top 1 tb_harga_beli_blended.HargaBlended from SAPSYNC.dbo.tb_harga_beli_blended where tb_harga_beli_blended.Plant=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS order by tb_harga_beli_blended.periode DESC) as mtd");
		$this->db->select("(select count(tbl_spot_setting_pol.port) from tbl_spot_setting_pol where tbl_spot_setting_pol.WERKS like '%'+ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS+'%') as jumlah_pol");
		$this->db->select("CAST( 
							( 
							  SELECT tbl_spot_setting_port.port+'|'+tbl_spot_setting_port.name+' - '+CONVERT(varchar(255), $port_1-tbl_spot_setting_pol.selisih) + RTRIM(',') 
								FROM tbl_spot_setting_port 
								left outer join tbl_spot_setting_pol on tbl_spot_setting_pol.port = tbl_spot_setting_port.port
							   WHERE  
							   tbl_spot_setting_port.na='n'
								and CHARINDEX(''''+CONVERT(varchar(10), ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)+'''',''''+REPLACE(tbl_spot_setting_pol.werks, RTRIM(','),''',''')+'''') > 0 
							   and tbl_spot_setting_pol.WERKS like '%'+ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS+'%'
							   order by tbl_spot_setting_port.port asc  
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_port");
		$this->db->select("CAST( 
							( 
							  SELECT ZDMMSCUSTMR.NAME1+'|'+ZDMMSCUSTMR.NAME1 + RTRIM(',') 
								FROM 
								SAPSYNC.dbo.ZDMMSCUSTMR
								LEFT OUTER JOIN SAPSYNC.dbo.ZDMMKTSUPP54 ON ZDMMSCUSTMR.KUNNR=ZDMMKTSUPP54.KUNNR
								WHERE 
								ZDMMSCUSTMR.VTWEG in('01','02') and
								ZDMMSCUSTMR.NAME1!='' and
								ZDMMSCUSTMR.KTOKD = 'Y001' and 
								ZDMMKTSUPP54.PAVIP in ('3','4','5') and
								ZDMMKTSUPP54.SMTP_ADDR is not null
								group by ZDMMSCUSTMR.NAME1+'|'+ZDMMSCUSTMR.NAME1
								order by ZDMMSCUSTMR.NAME1+'|'+ZDMMSCUSTMR.NAME1 asc 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_buyer");
		$this->db->select("(select top 1 tbl_spot_setting_pol.selisih from tbl_spot_setting_pol where tbl_spot_setting_pol.na='n' and tbl_spot_setting_pol.WERKS like '%'+ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS+'%' order by tbl_spot_setting_pol.port asc) as selisih");
		//untuk UAT diset GJAHR 2019
		if ($type == 'budget') {
			$this->db->select("
								(select SUM(ZDMFICO02.DMBTR) from SAPSYNC.dbo.ZDMFICO02 where ZDMFICO02.GJAHR='2019' and ZDMFICO02.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO02.COGRP not in('24. Biaya Bank','24. Biaya Bank dan Provisi','25. Biaya Bunga Bank dan Pihak Ketiga','26. Selisih Kurs Transaksi / Realized','27. Selisih Kurs Translasi / Unrealized','Biaya Pajak Penghasilan','Biaya Pajak Penghasilan - Tangguhan','Number of Employee','Qty. Production','Qty. Purchase','Qty. Sales')) + 
								(select SUM(tbl_pdca_acc_hocost.TARGET_MTD) from tbl_pdca_acc_hocost where tbl_pdca_acc_hocost.GJAHR='2019' and tbl_pdca_acc_hocost.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)
								as biaya_tahun");
			$this->db->select("(select SUM(ZDMFICO02.DMBTR) from SAPSYNC.dbo.ZDMFICO02 where ZDMFICO02.GJAHR='2019' and ZDMFICO02.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO02.COGRP='Qty. Production') as qty_tahun");
		}
		if ($type == 'outlook') {
			$this->db->select("
								(select SUM(ZDMFICO10.DMBTR) from SAPSYNC.dbo.ZDMFICO10 where ZDMFICO10.GJAHR='2019' and ZDMFICO10.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO10.COGRP not in('24. Biaya Bank','25. Biaya Bunga Bank dan Pihak Ketiga','26. Selisih Kurs Transaksi / Realized','27. Selisih Kurs Translasi / Unrealized','Biaya Bank - Citibank FC','Biaya Pajak Penghasilan','Biaya Pajak Penghasilan - Tangguhan','Number of Employee','Qty. Production','Qty. Purchase','Qty. Sales')) + 
								(select SUM(tbl_pdca_acc_hocost_outlook.TARGET_MTD) from tbl_pdca_acc_hocost_outlook where tbl_pdca_acc_hocost_outlook.GJAHR='2019' and tbl_pdca_acc_hocost_outlook.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)
								as biaya_tahun");
			$this->db->select("(select top 1 ZDMFICO10.YTD from SAPSYNC.dbo.ZDMFICO10 where ZDMFICO10.GJAHR='2019' and ZDMFICO10.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO10.COGRP='Qty. Production' order by ZDMFICO10.MONAT DESC) as qty_tahun");
		}


		// if($type=='budget'){
		// $this->db->select("
		// (select SUM(ZDMFICO02.DMBTR) from SAPSYNC.dbo.ZDMFICO02 where ZDMFICO02.GJAHR='$tahun' and ZDMFICO02.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO02.COGRP not in('24. Biaya Bank','24. Biaya Bank dan Provisi','25. Biaya Bunga Bank dan Pihak Ketiga','26. Selisih Kurs Transaksi / Realized','27. Selisih Kurs Translasi / Unrealized','Biaya Pajak Penghasilan','Biaya Pajak Penghasilan - Tangguhan','Number of Employee','Qty. Production','Qty. Purchase','Qty. Sales')) + 
		// (select SUM(tbl_pdca_acc_hocost.TARGET_MTD) from tbl_pdca_acc_hocost where tbl_pdca_acc_hocost.GJAHR='$tahun' and tbl_pdca_acc_hocost.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)
		// as biaya_tahun");
		// $this->db->select("(select SUM(ZDMFICO02.DMBTR) from SAPSYNC.dbo.ZDMFICO02 where ZDMFICO02.GJAHR='$tahun' and ZDMFICO02.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO02.COGRP='Qty. Production') as qty_tahun");
		// }
		// if($type=='outlook'){
		// $this->db->select("
		// (select SUM(ZDMFICO10.DMBTR) from SAPSYNC.dbo.ZDMFICO10 where ZDMFICO10.GJAHR='$tahun' and ZDMFICO10.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO10.COGRP not in('24. Biaya Bank','25. Biaya Bunga Bank dan Pihak Ketiga','26. Selisih Kurs Transaksi / Realized','27. Selisih Kurs Translasi / Unrealized','Biaya Bank - Citibank FC','Biaya Pajak Penghasilan','Biaya Pajak Penghasilan - Tangguhan','Number of Employee','Qty. Production','Qty. Purchase','Qty. Sales')) + 
		// (select SUM(tbl_pdca_acc_hocost_outlook.TARGET_MTD) from tbl_pdca_acc_hocost_outlook where tbl_pdca_acc_hocost_outlook.GJAHR='$tahun' and tbl_pdca_acc_hocost_outlook.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS)
		// as biaya_tahun");	
		// $this->db->select("(select top 1 ZDMFICO10.YTD from SAPSYNC.dbo.ZDMFICO10 where ZDMFICO10.GJAHR='$tahun' and ZDMFICO10.GSBER=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMFICO10.COGRP='Qty. Production' order by ZDMFICO10.MONAT DESC) as qty_tahun");
		// }
		$this->db->select("(select ZDMPURBKR04.QTOUM from SAPSYNC.dbo.ZDMPURBKR04 where ZDMPURBKR04.EKORG=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS and ZDMPURBKR04.BEDAT=(select top 1 ZKISSTT_0138.ACTDT from SAPSYNC.dbo.ZKISSTT_0138 where ZKISSTT_0138.ACTDT< convert(varchar(10), getdate(),112) and (ZKISSTT_0138.HOLDT!='X' or ZKISSTT_0138.HOLDT is null) order by ZKISSTT_0138.ACTDT DESC)) * -1 as ocp");
		// $this->db->select("(select top 1 ZDMPURBKR04.QTOUM from SAPSYNC.dbo.ZDMPURBKR04 where ZDMPURBKR04.EKORG=ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS order by ZDMPURBKR04.BEDAT DESC) as ocp");	
		$this->db->from("SAPSYNC.dbo.ZDMMSPLANT");
		if ($werks !== NULL) {
			$this->db->where('ZDMMSPLANT.WERKS', $werks);
		}
		$this->db->where("ZDMMSPLANT.WERKS!=''");
		$this->db->where("ZDMMSPLANT.WERKS!='KPK1'");
		$this->db->where("ZDMMSPLANT.TPPCO!=''");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_cost_log($conn = NULL, $werks = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_spot_prod_cost_log.*");
		$this->db->select("CONVERT(VARCHAR(10), tbl_spot_prod_cost_log.tanggal_buat, 104) + ' ' + CONVERT(VARCHAR(8), tbl_spot_prod_cost_log.tanggal_buat, 108) as tanggal_input");
		$this->db->select("tbl_karyawan.nama as nama_karyawan");
		$this->db->select("tbl_karyawan.nik");
		$this->db->from("tbl_spot_prod_cost_log");
		$this->db->join('tbl_user', 'tbl_user.id_user = tbl_spot_prod_cost_log.login_buat', 'left outer');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
		if ($werks !== NULL) {
			$this->db->where('tbl_spot_prod_cost_log.WERKS', $werks);
		}
		$this->db->order_by("tbl_spot_prod_cost_log.tanggal_buat", "desc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_ck_cost($conn = NULL, $werks = NULL, $cost = NULL, $note = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_spot_prod_cost.*");
		$this->db->from("tbl_spot_prod_cost");
		if ($werks !== NULL) {
			$this->db->where('tbl_spot_prod_cost.werks', $werks);
		}
		if ($cost !== NULL) {
			$this->db->where('tbl_spot_prod_cost.cost', $cost);
		}
		if ($note !== NULL) {
			$this->db->where('tbl_spot_prod_cost.note', $note);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_list_upload_deal_beli_bom($params = array())
	{
		// if ($conn !== NULL)
		$this->general->connectDbPortal();

		$this->datatables->select("id_deal_beli");
		$this->datatables->select("plant_deal");
		$this->datatables->select("tanggal_deal");
		$this->datatables->select("qty_deal");
		$this->datatables->select("harga_deal");

		$this->datatables->select("login_buat");
		$this->datatables->select("tanggal_buat");
		$this->datatables->select("login_edit");
		$this->datatables->select("tanggal_edit");
		$this->datatables->select("na");
		$this->datatables->select("del");


		$this->datatables->from("vw_spot_deal_pembelian");

		if (isset($params['tanggal']))
			$this->datatables->where_in("nik", $params['nik']);

		if (isset($params['plant']))
			$this->datatables->where_in("kelengkapan_tiket  ", $params['kelengkapan']);

		$this->datatables->where_in("del  ", 'n');
		$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);

		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_deal_beli"));

		return $this->general->jsonify($raw);
	}

	function get_data_cek_deal_beli($conn = NULL, $plant = NULL, $tanggal = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_spot_setting_deal_beli.*");
		$this->db->from("tbl_spot_setting_deal_beli");
		if ($plant !== NULL) {
			$this->db->where('tbl_spot_setting_deal_beli.plant_deal', $plant);
		}
		if ($tanggal !== NULL) {
			$this->db->where('tbl_spot_setting_deal_beli.tanggal_deal', $tanggal);
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();

		return $result;
	}

	function get_data_pol_sap($param = NULL)
	{
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		$this->db->select("
			vw_spot_pol.INCO2,
			vw_spot_pol.PLANT,
			vw_spot_pol.TPPCO,
			vw_spot_pol.SELISIH,
			vw_spot_pol.DATUM,
			vw_spot_pol.FLAG
		");
		$this->db->from("vw_spot_pol");

		$this->db->order_by('
			vw_spot_pol.INCO2,
			vw_spot_pol.PLANT
		');
		$query  = $this->db->get();
		$result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();
		return $result;
	}

	// function get_data_deal_beli($conn = NULL, $id = NULL) {
	// 	$this->general->connectDbPortal();

	// 	$this->db->select('tbl_spot_setting_deal_beli.*');
	// 	$this->db->select('convert(varchar, tbl_spot_setting_deal_beli.tanggal_deal, 104) as tgl');
	// 	$this->db->from('tbl_spot_setting_deal_beli');
	// 	if ($id != NULL) {
	// 		$this->db->where('tbl_spot_setting_deal_beli.id_deal_beli', $id);
	// 	}
	// 	$this->db->order_by("tbl_spot_setting_deal_beli.tanggal_deal", "Desc");
	// 	$query  = $this->db->get();
	// 	$result = $query->result();

	// 	$this->general->closeDb();
	// 	return $result;
	// }
}
