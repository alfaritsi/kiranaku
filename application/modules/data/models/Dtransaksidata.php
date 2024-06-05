<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : KODE MATERIAL
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

	function get_data_pi_spk($conn = NULL, $orderid = NULL, $doc_year = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_pi_spk.*");
		$this->db->from('tbl_pi_spk');
		if ($orderid !== NULL) {
			$this->db->where('tbl_pi_spk.no_io', $orderid);
		}
		if ($doc_year !== NULL) {
			$this->db->where('tbl_pi_spk.doc_year', $doc_year);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_data_pi_spk_mts($conn = NULL, $orderid = NULL, $doc_year = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_pi_spk_mts.*");
		$this->db->from('tbl_pi_spk_mts');
		if ($orderid !== NULL) {
			$this->db->where('tbl_pi_spk_mts.no_io', $orderid);
		}
		if ($doc_year !== NULL) {
			$this->db->where('tbl_pi_spk_mts.doc_year', $doc_year);
		}
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function push_data_material_code($id_item_spec = null, $extend = null)
	{
		$string	= "
			select 
					tbl_item_group.mtart as MTART,
					tbl_item_plant.plant as WERKS,
					tbl_item_spec.lgort as LGORT,
					tbl_item_spec.code as MATNR,
					tbl_item_spec.detail_sap as MAKTX,
					--dbo.MyHTMLDecode(tbl_item_spec.detail) as MAKTX,
					--tbl_item_spec.msehi_uom as MEINS,
					tbl_item_uom.mseh3 as MEINS,
					--tbl_item_spec.msehi_order as BSTME,
					CASE
						WHEN tbl_item_spec.msehi_order = '0' THEN null
						ELSE tbl_item_spec.msehi_order
					END as BSTME,          
					tbl_item_spec.old_material_number as BISMT,
					tbl_item_spec.ekgrp as EKGRP,
					tbl_item_spec.availability_check as MTVFP,
					tbl_item_name.matkl as MATKL,
					----tbl_item_name.bklas as BKLAS,
					CASE
						WHEN tbl_item_name.bklas = 0 THEN null
						WHEN ((tbl_item_name.bklas = 5000 or tbl_item_name.bklas = 5100) and tbl_item_plant.plant='NSI2') THEN 5100
						WHEN ((tbl_item_name.bklas = 5000 or tbl_item_name.bklas = 5100) and tbl_item_plant.plant!='NSI2') THEN 5000
						ELSE tbl_item_name.bklas
					END as BKLAS,          
					tbl_item_name.price_control as VPRSV,
					tbl_item_spec.mrp_group as DISGR,
					tbl_item_spec.mrp_type as DISMM,
					
					tbl_item_spec.dispo as DISPO,
					tbl_item_spec.service_level as LGRAD,
					tbl_item_spec.disls as DISLS,
					tbl_item_spec.period_indicator as PERKZ,
					tbl_item_plant.vtweg as VTWEG,
					
					CASE
						WHEN tbl_item_spec.sales_plant = 'X' 
						THEN tbl_item_plant.plant
						ELSE null
					END as VKORG,          
					tbl_item_spec.spart as SPART,
					tbl_item_spec.gross_weight as BRGEW,
					tbl_item_spec.net_weight as NTGEW,
					tbl_item_spec.gen_item_cat_group as MTPOS_MARA,
					
					tbl_item_spec.material_pricing_group as KONDM,
					tbl_item_plant.plant as DWERK,
					tbl_item_spec.material_statistic_group as VERSG,
					tbl_item_spec.acct_assignment_group as KTGRM,
					tbl_item_profit.prctr as PRCTR,
					'Z' as MBRSH,
					tbl_item_spec.purchase_type as PTYPE,
					tbl_item_spec.purchase_authorization as PAUTH,
					--tbl_item_spec.xchpf as XCHPF,
					tbl_item_name.classification as CLASS,
					tbl_item_spec.specification_check as SCHECK,
					tbl_item_spec.umrez as UMREZ,
					tbl_item_spec.prmod as PRMOD,
					tbl_item_spec.peran as PERAN,
					tbl_item_spec.anzpr as ANZPR,
					tbl_item_spec.kzini as KZINI,
					tbl_item_spec.siggr as SIGGR
					from 
					tbl_item_plant
					left outer join tbl_item_spec on tbl_item_spec.id_item_spec=tbl_item_plant.id_item_spec
					left outer join tbl_item_name on tbl_item_name.id_item_name=tbl_item_spec.id_item_name
					left outer join tbl_item_group on tbl_item_group.id_item_group=tbl_item_spec.id_item_group
					left outer join tbl_wf_master_plant on tbl_wf_master_plant.plant=tbl_item_plant.plant
					left outer join tbl_item_profit on tbl_item_profit.kokrs=tbl_item_plant.plant
					left outer join tbl_item_uom on tbl_item_uom.mseh3=tbl_item_spec.msehi_uom
					where 
					1=1
					and tbl_item_plant.na='n' 
					and tbl_item_plant.status_sap='n' 
					and tbl_item_spec.id_item_spec='$id_item_spec' 
		";
		if ($id_item_spec == null) {
			$string	.= "and tbl_item_spec.id_item_spec='$id_item_spec'";
		}
		if ($extend == null) {
			$string	.= "and tbl_item_spec.req='y'";
		}
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function change_material_code($id_item_spec = null, $detail = null, $msehi_uom = null)
	{
		$string	= "
			select 
				tbl_item_spec.code as MATNR,
				'$detail' as MAKTX,
				(select top 1 tbl_item_uom.msehi from tbl_item_uom where tbl_item_uom.mseh3='$msehi_uom') as MEINS
				from 
				tbl_item_spec
				where 
				1=1
				and tbl_item_spec.id_item_spec='$id_item_spec'
		";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function generate_notif_sicom($date = NULL)
	{
		if ($date == NULL)
			$date = date('Y-m-d');

		$this->db->query("EXEC SP_Kiranaku_SICOM '" . $date . "'");
	}

	function get_data_nopel_pln($param = NULL)
	{
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbDefault();

		$this->db->select('*');
		$this->db->from('ZDMFICO12');
		$query  = $this->db->get();
		$result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}
	function push_data_margin()
	{
		// $string	= "
		// select 
		// tbl_spot_simulate.no_contract as VBELN, 
		// 0 as CODLV, 0 as COALL, 
		// ROUND(tbl_spot_sales_conf_detail.margin,0) as MARGN, 
		// 'IDR' as LCURR 
		// from tbl_spot_sales_conf_detail 
		// left outer join tbl_spot_simulate on tbl_spot_simulate.no_form=tbl_spot_sales_conf_detail.no_form 
		// where 
		// 1=1 
		// and tbl_spot_sales_conf_detail.status_sap='n'
		// ";
		$string	= "
			SELECT tbl_spot_simulate.no_contract as VBELN, 
				ROUND((tbl_spot_sales_conf_detail.qty*1000*tbl_spot_simulate.margin),0) as MARGN,
				ROUND((tbl_spot_simulate.margin-(0.005*tbl_spot_simulate.cur_rate)),0) as MARGI,
				ROUND((tbl_spot_sales_conf_detail.qty*1000)*(tbl_spot_simulate.margin-(0.005 * tbl_spot_simulate.cur_rate)),0) as TENTV,
				'IDR' as LCURR, 
				tbl_spot_simulate.total_cost as COTOT,
				tbl_spot_simulate.carry_cost as COCAR
			FROM tbl_spot_sales_conf_detail
			LEFT JOIN tbl_spot_simulate ON tbl_spot_simulate.no_form=tbl_spot_sales_conf_detail.no_form 
										AND tbl_spot_simulate.plant = tbl_spot_sales_conf_detail.werks
			WHERE 1=1 
			AND tbl_spot_sales_conf_detail.status_sap='n' 
			AND tbl_spot_simulate.no_contract is not null
		";

		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}
	function push_data_header_spot_xx($param = NULL)
	{
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbDefault();

		$this->db->select("'1000000020' as KUNNR");
		$this->db->from('tbl_karyawan');
		$this->db->limit(2);
		// $query 	= $this->db->get();
		// $result = $query->row();

		$query  = $this->db->get();
		$result = $query->result();

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}

	function push_data_header_spot($no_form = NULL)
	{
		$string	= "
				SELECT
				top 1
				ZDMMSCUSTMR.KUNNR,
				CONVERT(varchar, tbl_spot_sales_conf_head.tanggal_buat, 112) as BSTDK,
				'TEST' as BSTKD,
				tbl_spot_simulate.plant as VKORG,	 
				tbl_spot_sales_conf_detail.distribution_channel as VTWEG, 
				'00' as SPART,
				'' as VKGRP,
				'' as VKBUR,
				'' as AUGRU,
				tbl_spot_sales_conf_detail.contract_type as CNTTY,
				--CONVERT(varchar, (SELECT DATEADD(dd,-(DAY(DATEADD(mm,1,getdate()))-1),DATEADD(mm,0,getdate()))), 112) as GUEBG,
				--CONVERT(varchar, (SELECT DATEADD(dd,-DAY(DATEADD(mm,1,getdate())), DATEADD(mm,1,getdate()))), 112) as GUEEN,
				CONVERT(varchar, (SELECT DATEADD(dd,-(DAY(DATEADD(mm,1,convert(datetime, '01-'+tbl_spot_simulate.shipment_periode, 103)))-1),DATEADD(mm,0,convert(datetime, '01-'+tbl_spot_simulate.shipment_periode, 103)))), 112) as GUEBG,
				CONVERT(varchar, (SELECT DATEADD(dd,-DAY(DATEADD(mm,1,convert(datetime, '01-'+tbl_spot_simulate.shipment_periode, 103))), DATEADD(mm,1,convert(datetime, '01-'+tbl_spot_simulate.shipment_periode, 103)))), 112) as GUEEN,
				CONVERT(varchar, tbl_spot_sales_conf_head.tanggal_buat, 112) as AUDAT,
				'' as WAERK, 
				'' as KTEXT, 
				'FOB' as INCO1,
				tbl_spot_simulate.pol as INCO2,
				ZDMMSCUSTMR.KTGRD as KTGRD
				FROM tbl_spot_sales_conf_head 
				LEFT JOIN SAPSYNC.dbo.ZDMMSCUSTMR on SAPSYNC.dbo.ZDMMSCUSTMR.NAME2 COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_spot_sales_conf_head.buyer 
        LEFT JOIN tbl_spot_simulate on tbl_spot_simulate.no_form=tbl_spot_sales_conf_head.no_form
        LEFT JOIN tbl_spot_sales_conf_detail on tbl_spot_sales_conf_detail.no_form=tbl_spot_sales_conf_head.no_form
        where tbl_spot_sales_conf_head.no_form='$no_form' 
		";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function push_data_item_spot($no_form = NULL)
	{
		$string	= "
				SELECT  
				top 1 
				'000010' as POSNR,
				tbl_spot_sales_conf_detail.prod_grade as MATNR, 
				tbl_spot_sales_conf_detail.qty*1000 as ZMENG,
				'KG' as ZIEME,
				tbl_spot_simulate.selling_price_usc as NETPR,
				'USD' as WAERS
				FROM 
				tbl_spot_sales_conf_detail
				left outer join tbl_spot_simulate on tbl_spot_simulate.no_form=tbl_spot_sales_conf_detail.no_form
				where tbl_spot_sales_conf_detail.no_form='$no_form'
		";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_simulate($no_form = NULL)
	{

		$string	= " select no_form from tbl_spot_simulate where no_form is not null and no_contract is null";
		if ($no_form != null) {
			$string	.= " and no_form='$no_form'";
		}
		// $string	= " select no_form from tbl_spot_simulate where no_form in ('0030/2020','0031/2020')";
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}


	/*
	* =======================================
	* 				MODEL FILE
	* =======================================
	*/
	function get_file_server($conn = NULL, $param = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$string = "SELECT ZDMMSPLANT.WERKS,
						  tbl_inv_pabrik.ip
					 FROM DashBoardDev.dbo.ZDMMSPLANT
					 LEFT JOIN tbl_inv_pabrik ON tbl_inv_pabrik.kode = ZDMMSPLANT.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS
					WHERE tbl_inv_pabrik.ip IS NOT NULL
					  AND tbl_inv_pabrik.ip = '10.0.0.105'";

		$query  = $this->db->query($string);
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function get_transfer_file_list($conn = NULL, $param = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$query  = $this->db->query("EXEC SP_Kiranaku_Schedule_Transfer_File '" . $param->tanggal_buat . "'");

		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	//lha 12.01.2021(set htmlentities kode material)
	function get_data_request($conn = NULL)
	{
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$string = "select 
						distinct(tbl_item_request.code),
						(select top 1 ZDMMSMATNR.MAKTX from SAPSYNC.dbo.ZDMMSMATNR where ZDMMSMATNR.MATNR COLLATE SQL_Latin1_General_CP1_CI_AS = tbl_item_request.code) as description_sap						
					from tbl_item_request where tbl_item_request.code is not null";

		$query  = $this->db->query($string);
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

	function push_data_master_vendor($id_data = null)
	{
		$string	= "
			select 
			ZDMMSPLANT.BUKRS as BUKRS,
			tbl_vendor_data.plant as EKORG,
			tbl_vendor_data.title as ANRED,
			tbl_vendor_data.nama as NAME1,
			tbl_vendor_data.acc_group as KTOKK,
			--'NBVE' as KTOKK,
			--tbl_vendor_data.lang as LANGU,
			'EN' as LANGU,
			tbl_vendor_data.jenis_barang_jasa1 as SORT1,
			tbl_vendor_data.jenis_barang_jasa2 as SORT2,
			tbl_vendor_data.alamat as STREET,
			tbl_vendor_data.no as HOUSE_NUM1,
			tbl_vendor_data.kode_pos as POST_CODE1,
			--'55555' as POST_CODE1,
			tbl_vendor_data.kota as CITY1,
			tbl_vendor_data.negara as COUNTRY,
			tbl_vendor_data.provinsi as REGION,
			tbl_vendor_data.time_zone as TIME_ZONE,
			tbl_vendor_data.telepon as TEL_NUMBER,
			tbl_vendor_data.fax as FAX_NUMBER,
			tbl_vendor_data.email as SMTP_ADDR,
			'X' as XCPDK,
			--tbl_vendor_data.npwp as STCD1,
			--tbl_vendor_data.ktp as STCD4,
			'456791827390871' as STCD1,
			'327587129001201' as STCD4,
			tbl_vendor_data.industri as BRSCH,
			tbl_vendor_data.dlgrp as DLGRP,
			tbl_vendor_data.akont as AKONT,
			tbl_vendor_data.zterm as ZTERM,
			tbl_vendor_data.reprf as REPRF,
			tbl_vendor_data.qland as QLAND,
			--tbl_vendor_data.tax_type as WITHT,
			--tbl_vendor_data.tax_code as WT_WITHCD,
			'23' as WITHT,
			'52' as WT_WITHCD,
			tbl_vendor_data.curr as WAERS,
			tbl_vendor_data.schema_grup as KALSK, --2 digit
			tbl_vendor_data.sales_person as VERKF,
			tbl_vendor_data.sales_phone as TELF1,
			tbl_vendor_data.webre as WEBRE,
			tbl_vendor_data.status_pkp as ZZSTSPKP,
			tbl_vendor_data.status_do as ZZDOFLAG,
			'X' as LOEVM,
			'' as BUKRS_REF,
			'' as EKORG_REF
			from 
			tbl_vendor_data
			left outer join DashBoardDev.dbo.ZDMMSPLANT as ZDMMSPLANT on ZDMMSPLANT.WERKS=tbl_vendor_data.plant 
			where 
			1=1
		";
		// $string	= "
		// select 
		// '2133' as BUKRS,
		// 'KJP1' as EKORG,
		// 'COMPANY' as ANRED,
		// tbl_vendor_data.nama as NAME1,
		// 'NBVE' as KTOKK,
		// 'EN' as LANGU,
		// 'TEST1' as SORT1,
		// 'TEST2' as SORT2,
		// 'ALAMAT' as STREET,
		// '12345' as HOUSE_NUM1,
		// '12345' as POST_CODE1,
		// 'JAKARTA' as CITY1,
		// 'ID' as COUNTRY,
		// '04' REGION,
		// 'UTC+7' as TIME_ZONE,
		// '1234' as TEL_NUMBER,
		// '2345' as FAX_NUMBER,
		// 'a@a.a' as SMTP_ADDR,
		// 'X' as XCPDK,
		// '456791827390871' as STCD1,
		// '327587129001201' as STCD4,
		// 'ED' as BRSCH,
		// 'Z001' as DLGRP,
		// '2102001' as AKONT,
		// 'Y015' as ZTERM,
		// 'X' as REPRF,
		// 'ID' as QLAND,
		// '23' as WITHT,
		// '52' as WT_WITHCD,
		// 'IDR' as WAERS,
		// 'NB' as KALSK, --2 digit
		// 'EKA' as VERKF,
		// '11' as TELF1,
		// 'X' as WEBRE,
		// 'PKP' as ZZSTSPKP,
		// 'DO' as ZZDOFLAG,
		// 'X' as LOEVM,
		// '' as BUKRS_REF,
		// '' as EKORG_REF
		// from 
		// tbl_vendor_data
		// left outer join DashBoardDev.dbo.ZDMMSPLANT as ZDMMSPLANT on ZDMMSPLANT.WERKS=tbl_vendor_data.plant 
		// where 
		// 1=1
		// ";

		if ($id_data != null) {
			$string	.= "and tbl_vendor_data.id_data='$id_data'";
		}
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function push_data_extend_vendor($id_data = null)
	{
		$string	= "
			select 
			top 1
			ZDMMSPLANT.BUKRS as BUKRS,
			ZDMMSPLANT.WERKS as EKORG
			from 
			tbl_vendor_plant
			left outer join DashBoardDev.dbo.ZDMMSPLANT as ZDMMSPLANT on ZDMMSPLANT.WERKS=tbl_vendor_plant.plant 
			where 
			1=1
		";

		if ($id_data != null) {
			$string	.= "and tbl_vendor_plant.id_data='$id_data'";
		}
		$string	.= "and tbl_vendor_plant.status_sap='n'";

		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_rekom_detail($param = NULL)
	{
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		$this->db->select('tbl_pi_rekom_vendor_detail.*');
		$this->db->select('
            tbl_pi_detail.matnr, 
            tbl_pi_detail.perm_invest,
            tbl_pi_detail.spesifikasi,
			tbl_pi_rekom_vendor_content.nama_vendor
        ');
		$this->db->from('tbl_pi_rekom_vendor_detail');
		$this->db->join(
			"tbl_pi_detail",
			"tbl_pi_detail.plant = tbl_pi_rekom_vendor_detail.plant
             AND tbl_pi_detail.no_pi = tbl_pi_rekom_vendor_detail.no_pi
             AND tbl_pi_detail.no = tbl_pi_rekom_vendor_detail.no_detail_pi
             AND tbl_pi_detail.na = 'n'
             AND tbl_pi_detail.del = 'n'",
			"inner"
		);
		$this->db->join(
			"tbl_pi_header",
			"tbl_pi_header.plant = tbl_pi_rekom_vendor_detail.plant
             AND tbl_pi_header.no_pi = tbl_pi_rekom_vendor_detail.no_pi",
			"inner"
		);
		$this->db->join(
			"tbl_pi_rekom_vendor_content",
			"tbl_pi_rekom_vendor_content.no_pi = tbl_pi_rekom_vendor_detail.no_pi
             AND tbl_pi_rekom_vendor_content.no_rekom = tbl_pi_rekom_vendor_detail.no_rekom
             AND tbl_pi_rekom_vendor_content.urut_vendor = tbl_pi_rekom_vendor_detail.urut_vendor",
			"inner"
		);

		if (isset($param['app']) && $param['app'] !== NULL)
			$this->db->where('tbl_pi_header.app', $param['app']);
		if (isset($param['new_method']) && $param['new_method'] !== NULL)
			$this->db->where('tbl_pi_header.new_method', $param['new_method']);
		if (isset($param['IN_status']) && $param['IN_status'] !== NULL)
			$this->db->where_in('tbl_pi_header.status', $param['IN_status']);
		if (isset($param['IN_year']) && $param['IN_year'] !== NULL)
			$this->db->where_in('YEAR(tbl_pi_header.tanggal)', $param['IN_year']);
		if (isset($param['no_pi']) && $param['no_pi'] !== NULL)
			$this->db->where('tbl_pi_rekom_vendor_detail.no_pi', $param['no_pi']);
		if (isset($param['plant']) && $param['plant'] !== NULL)
			$this->db->where('tbl_pi_rekom_vendor_detail.plant', $param['plant']);
		if (isset($param['no_rekom']) && $param['no_rekom'] !== NULL)
			$this->db->where('tbl_pi_rekom_vendor_detail.no_rekom', $param['no_rekom']);
		if (isset($param['urut']) && $param['urut'] !== NULL)
			$this->db->where('tbl_pi_rekom_vendor_detail.urut_vendor', $param['urut']);
		if (isset($param['no_detail']) && $param['no_detail'] !== NULL)
			$this->db->where('tbl_pi_rekom_vendor_detail.no_detail_pi', $param['no_detail']);
		if (isset($param['selected']) && $param['selected'] !== NULL)
			$this->db->where('tbl_pi_rekom_vendor_content.is_selected', $param['selected']);
		if (isset($param['IN_no_deviasi']) && $param['IN_no_deviasi'] !== NULL)
			$this->db->where_in('tbl_pi_detail.no_deviasi', $param['IN_no_deviasi']);
		if (isset($param['NOT_IN_no_deviasi']) && $param['NOT_IN_no_deviasi'] !== NULL) {
			$this->db->group_start();
			$this->db->where_not_in('tbl_pi_detail.no_deviasi', $param['NOT_IN_no_deviasi']);
			$this->db->or_where('tbl_pi_detail.no_deviasi IS NULL');
			$this->db->group_end();
		}
		if (isset($param['NULL_no_po']) && $param['NULL_no_po'] !== NULL) {
			$this->db->group_start();
			$this->db->where('tbl_pi_rekom_vendor_detail.no_po IS NULL');
			if (isset($param['ORNULL_no_so']) && $param['ORNULL_no_so'] !== NULL)
				$this->db->or_where('tbl_pi_rekom_vendor_detail.no_so IS NULL');
			$this->db->group_end();
		}
		if (isset($param['NULL_no_so']) && $param['NULL_no_so'] !== NULL) {
			$this->db->group_start();
			$this->db->where('tbl_pi_rekom_vendor_detail.no_so IS NULL');
			if (isset($param['ORNULL_no_po']) && $param['ORNULL_no_po'] !== NULL)
				$this->db->or_where('tbl_pi_rekom_vendor_detail.no_po IS NULL');
			$this->db->group_end();
		}

		$this->db->where('tbl_pi_rekom_vendor_detail.na', 'n');
		$this->db->where('tbl_pi_rekom_vendor_detail.del', 'n');
		$this->db->order_by('tbl_pi_rekom_vendor_detail.plant', 'ASC');
		$this->db->order_by('tbl_pi_rekom_vendor_detail.no_pi', 'ASC');

		$query = $this->db->get();

		if (isset($param['single_row']) && !empty($param['single_row']) && $param['single_row'] === TRUE)
			$result = $query->row();
		else
			$result = $query->result();

		if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
			$result = $this->general->generate_encrypt_json($result, $param['encrypt'], $this->general->emptyconvert(@$param['exclude']));

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}

	function update_rekom_detail($param = NULL)
	{
		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->connectDbPortal();

		$query = "
			UPDATE tbl_pi_rekom_vendor_detail
			   SET tbl_pi_rekom_vendor_detail.login_edit = '" . $param['login_edit'] . "',
			       tbl_pi_rekom_vendor_detail.tanggal_edit = '" . $param['tanggal_edit'] . "'
		";
		if (isset($param['no_po']) && $param['no_po'] !== NULL)
			$query .= ", tbl_pi_rekom_vendor_detail.no_po = '" . $param['no_po'] . "'";
		if (isset($param['no_so']) && $param['no_so'] !== NULL)
			$query .= ", tbl_pi_rekom_vendor_detail.no_so = '" . $param['no_so'] . "'";
		if (isset($param['item_po']) && $param['item_po'] !== NULL)
			$query .= ", tbl_pi_rekom_vendor_detail.item_po = '" . $param['item_po'] . "'";

		$query .= "
		     FROM tbl_pi_rekom_vendor_detail
		    INNER JOIN tbl_pi_rekom_vendor_content ON tbl_pi_rekom_vendor_content.no_pi = tbl_pi_rekom_vendor_detail.no_pi
												AND tbl_pi_rekom_vendor_content.no_rekom = tbl_pi_rekom_vendor_detail.no_rekom
												AND tbl_pi_rekom_vendor_content.urut_vendor = tbl_pi_rekom_vendor_detail.urut_vendor
			WHERE 1 = 1
		";

		if (isset($param['no_pi']) && $param['no_pi'] !== NULL)
			$query .= "AND tbl_pi_rekom_vendor_detail.no_pi = '" . $param['no_pi'] . "'";
		if (isset($param['no_detail_pi']) && $param['no_detail_pi'] !== NULL)
			$query .= "AND tbl_pi_rekom_vendor_detail.no_detail_pi = '" . $param['no_detail_pi'] . "'";
		if (isset($param['nama_vendor']) && $param['nama_vendor'] !== NULL)
			$query .= "AND tbl_pi_rekom_vendor_content.nama_vendor = '" . $param['nama_vendor'] . "'";
		if (isset($param['no_rekom']) && $param['no_rekom'] !== NULL)
			$query .= "AND tbl_pi_rekom_vendor_content.no_rekom = '" . $param['no_rekom'] . "'";

		$result = $this->db->query($query);

		if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
			$this->general->closeDb();

		return $result;
	}

    function generate_header_summary($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $no_pi = (isset($param['no_pi']) && $param['no_pi'] !== NULL ? $param['no_pi'] : NULL);
        $tahun = (isset($param['tahun']) && $param['tahun'] !== NULL ? $param['tahun'] : date('Y'));

        $this->db->query("
            EXEC SP_KPRO_GENERATE_HEADER
                @no_pi = '" . $no_pi . "',
                @tahun = '" . $tahun . "' 
        ");

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();
        return TRUE;
    }
}
