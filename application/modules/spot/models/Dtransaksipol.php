<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
 
class Dtransaksipol extends CI_Model{
	function get_data_no_form($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select("
							CASE
								WHEN count(*)=0 
								THEN '0001/'+ convert(varchar(4),YEAR(GETDATE()))
								ELSE right('0000'+convert(varchar(4), (select top 1 substring(tbl_spot_sales_conf_head2.no_form, 1, 4)+1 from tbl_spot_sales_conf_head as tbl_spot_sales_conf_head2 where SUBSTRING(tbl_spot_sales_conf_head2.no_form, 6, 4)=YEAR(GETDATE()) order by tbl_spot_sales_conf_head2.no_form desc)), 4) +'/'+ convert(varchar(4),YEAR(GETDATE()))
							END
							as nomor");
		// $this->db->select("
							// CASE
								// WHEN count(*)=0 
								// THEN '0001'
								// ELSE right('0000'+convert(varchar(4), (select top 1 substring(tbl_spot_sales_conf_head2.no_form, 1, 4)+1 from tbl_spot_sales_conf_head as tbl_spot_sales_conf_head2 where SUBSTRING(tbl_spot_sales_conf_head2.no_form, 6, 4)=YEAR(GETDATE()) order by tbl_spot_sales_conf_head2.no_form desc)), 4)
							// END
							// as nomor");
		$this->db->from('tbl_spot_sales_conf_head');
		$this->db->where("SUBSTRING(tbl_spot_sales_conf_head.no_form, 6, 4)=YEAR(GETDATE())");
		$query  = $this->db->get();
		$result = $query->row();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
		
	}
	function get_data_last($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select("tbl_spot_simulate.libor_rate");
		$this->db->select("tbl_spot_simulate.interest_rate");
		$this->db->select("(tbl_spot_simulate.libor_rate+tbl_spot_simulate.interest_rate) as interest");
		$this->db->from("tbl_spot_simulate");
		$this->db->where("tbl_spot_simulate.na='n'");
		$this->db->order_by("tbl_spot_simulate.tanggal_buat", "desc");
		$this->db->limit(1); 
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	//get data 32-portal
	function get_data_currency($conn = NULL, $werks = NULL) {
		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		$string = '
					SELECT 
					--AVG(KURSK_U) as rate
					SUM(KURSK_I)/SUM(WRBTR_I) as rate
					FROM 
					SAPSYNC.dbo.ZKISSTT_0061
					WHERE 
					ZKISSTT_0061.KURSK_U>0 and ZKISSTT_0061.KURDT=(select top 1 ZKISSTT_0061.KURDT from SAPSYNC.dbo.ZKISSTT_0061 where ZKISSTT_0061.KURSK_U>0 order by ZKISSTT_0061.KURDT desc)					
					';
		$query = $this->db->query($string);
		return $query->result();
	}
	function get_data_currency_old($conn = NULL, $werks = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$this->db->select("AVG(KURSK_U) as rate");
		$this->db->from("SAPSYNC.dbo.ZKISSTT_0061");
		$this->db->where("ZKISSTT_0061.KURSK_U>0 and ZKISSTT_0061.KURDT=(select top 1 ZKISSTT_0061.KURDT from SAPSYNC.dbo.ZKISSTT_0061 where ZKISSTT_0061.KURSK_U>0 order by ZKISSTT_0061.KURDT desc)");
		// $this->db->where("ZKISSTT_0061.KURSK_U>0 and ZKISSTT_0061.KURDT='".date('Y-m-d')."'");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result; 
	}
	function get_data_email($conn = NULL, $buyer = NULL) {
		if ($conn !== NULL) 
			$this->general->connectDbPortal();

		$this->db->select("ZDMMKTSUPP54.PAVIP");
		$this->db->select("ZDMMKTSUPP54.NAME1 as nama_penerima");
		$this->db->select("ZDMMKTSUPP54.SMTP_ADDR as email_to");
		// $this->db->select("CAST( 
							// ( 
							  // SELECT ZDMMKTSUPP54a.SMTP_ADDR + RTRIM(',') 
								// FROM 
								// SAPSYNC.dbo.ZDMMKTSUPP54 ZDMMKTSUPP54a
								// WHERE 
								// ZDMMKTSUPP54a.KUNNR=ZDMMKTSUPP54.KUNNR AND
								// ZDMMKTSUPP54a.PAVIP='4'
							  // FOR XML PATH ('') 
							// ) as VARCHAR(MAX) 
							// ) as email_cc");		
		$this->db->from("SAPSYNC.dbo.ZDMMKTSUPP54");
		$this->db->join('SAPSYNC.dbo.ZDMMSCUSTMR', 'ZDMMSCUSTMR.KUNNR = ZDMMKTSUPP54.KUNNR', 'left outer');		
		if ($buyer !== NULL) {
			$this->db->where('ZDMMSCUSTMR.NAME2', $buyer);
			// $this->db->where('ZDMMSCUSTMR.KUNNR', $buyer);
		}
		$this->db->where("ZDMMKTSUPP54.PAVIP in ('5')");
		$this->db->where('ZDMMKTSUPP54.SMTP_ADDR is not null');
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	function get_data_email_buyer($conn = NULL, $buyer = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("ZDMMKTSUPP54.PAVIP");
		$this->db->select("ZDMMSCUSTMR.NAME2 as nama_buyer");
		$this->db->select("ZDMMSCUSTMR.NAME1 as nama_penerima");
		$this->db->select("ZDMMKTSUPP54.SMTP_ADDR as email_to");
		$this->db->select("CAST( 
							( 
							  SELECT ZDMMKTSUPP54a.SMTP_ADDR + RTRIM(',') 
								FROM 
								SAPSYNC.dbo.ZDMMKTSUPP54 ZDMMKTSUPP54a
								WHERE 
								ZDMMKTSUPP54a.KUNNR=ZDMMKTSUPP54.KUNNR AND
								ZDMMKTSUPP54a.PAVIP='4'
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as email_cc");		
		$this->db->from("SAPSYNC.dbo.ZDMMKTSUPP54");
		$this->db->join('SAPSYNC.dbo.ZDMMSCUSTMR', 'ZDMMSCUSTMR.KUNNR = ZDMMKTSUPP54.KUNNR', 'left outer');		
		if ($buyer !== NULL) {
			$this->db->where('ZDMMSCUSTMR.NAME2', $buyer);
			// $this->db->where('ZDMMSCUSTMR.KUNNR', $buyer);
		}
		$this->db->where("ZDMMKTSUPP54.PAVIP in ('3')");
		$this->db->where('ZDMMKTSUPP54.SMTP_ADDR is not null');
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	//data sales bom
	function get_data_sales_bom($conn = NULL, $no_form = NULL, $tahun = NULL, $buyer = NULL, $status = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("no_form, buyer, status, sicom, login_buat, tanggal_buat, login_edit, tanggal_edit, na, del, shipment_periode, nomor, no_contract");
		$this->datatables->from("vw_spot_header");
		if ($no_form !== NULL) {
			$this->datatables->where('no_form', $no_form);
		}
		if($tahun != NULL){
			if(is_string($tahun)) $tahun = explode(",", $tahun);
			$this->datatables->where_in('SUBSTRING(no_form, 6, 4)', $tahun);
		}
		if($buyer != NULL){
			if(is_string($buyer)) $buyer = explode(",", $buyer);
			$this->datatables->where_in('buyer', $buyer);
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->datatables->where_in('status', $status);
		}
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("no_form"));
		return $this->general->jsonify($raw);
		
	}
	function get_data_sales($conn = NULL, $no_form = NULL, $active = NULL, $deleted = 'n') {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_spot_simulate.*');
		$this->db->select('tbl_spot_sales_conf_detail.tppco');
		$this->db->select('tbl_spot_sales_conf_detail.shipment_term');
		$this->db->select('tbl_spot_sales_conf_detail.price');
		$this->db->select('tbl_spot_sales_conf_detail.qty');
		$this->db->select('tbl_spot_sales_conf_detail.note');
		$this->db->select('tbl_spot_sales_conf_detail.prod_grade');
		$this->db->select('tbl_spot_sales_conf_detail.distribution_channel');
		$this->db->select('tbl_spot_sales_conf_detail.contract_type');
		$this->db->select("
							CAST( 
							( 
							  SELECT tbl_spot_dc.dc+ RTRIM(',') 
								FROM 
								".DB_PORTAL.".dbo.tbl_spot_dc
								order by tbl_spot_dc.dc asc 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_dc
						");
		$this->db->from('tbl_spot_simulate');
		$this->db->join('tbl_spot_sales_conf_detail', 'tbl_spot_sales_conf_detail.no_form = tbl_spot_simulate.no_form', 'left outer');		
		if ($no_form !== NULL) {
			$this->db->where('tbl_spot_simulate.no_form', $no_form);
		}
		$this->db->order_by("tbl_spot_simulate.no_form", "asc");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	//data detail bom
	function get_data_detail_bom($conn = NULL, $no_form = NULL, $pabrik = NULL, $tahun = NULL, $buyer = NULL, $status = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->datatables->select("tanggal, id_simulate, tanggal_view, no_contract, no_form, buyer, factory, plant, mtd_price, selling_price_usc, selling_price, prod_cost, trucking_cost, carry_cost, margin, ocp, breakeven_price, cur_rate, pol, pol_value, libor_rate, interest_rate, days, prod_cost_type, login_buat, tanggal_buat, login_edit, tanggal_edit, na, del, tppco, qty, shipment_periode, prod_grade, note, nomor, status, amount, deal_harga_pembelian, sicom");
		$this->datatables->from("vw_spot_detail");
		if ($no_form !== NULL) {
			$this->datatables->where('no_form', $no_form);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->datatables->where_in('plant', $pabrik);
		}
		if($tahun != NULL){
			if(is_string($tahun)) $tahun = explode(",", $tahun);
			$this->datatables->where_in('SUBSTRING(no_form, 6, 4)', $tahun);
		}
		if($buyer != NULL){
			if(is_string($buyer)) $buyer = explode(",", $buyer);
			$this->datatables->where_in('buyer', $buyer);
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->datatables->where_in('status', $status);
		}
		if ($conn !== NULL)
			$this->general->closeDb();

		$return = $this->datatables->generate();
		$raw = json_decode($return, true);
		$raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_simulate"));
		return $this->general->jsonify($raw);
		
	}
	//data detail
	function get_data_detail($conn = NULL, $no_form = NULL, $pabrik = NULL, $tahun = NULL, $buyer = NULL, $status = NULL, $search = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		
		$this->db->select("tbl_spot_sales_conf_detail.*");
		$this->db->select("tbl_spot_sales_conf_detail.no_form as nomor");
		$this->db->select("tbl_spot_sales_conf_head.buyer");
		$this->db->select("tbl_spot_sales_conf_head.sicom");
		$this->db->from("tbl_spot_sales_conf_detail");
		$this->db->join('tbl_spot_sales_conf_head', 'tbl_spot_sales_conf_head.no_form = tbl_spot_sales_conf_detail.no_form', 'left outer');		
		if ($no_form !== NULL) {
			$this->db->where('tbl_spot_sales_conf_detail.no_form', $no_form);
		}
		if($pabrik != NULL){
			if(is_string($pabrik)) $pabrik = explode(",", $pabrik);
			$this->db->where_in('tbl_spot_sales_conf_detail.werks', $pabrik);
		}
		if($tahun != NULL){
			if(is_string($tahun)) $tahun = explode(",", $tahun);
			$this->db->where_in('SUBSTRING(tbl_spot_sales_conf_detail.no_form, 6, 4)', $tahun);
		}
		if($buyer != NULL){
			if(is_string($buyer)) $buyer = explode(",", $buyer);
			$this->db->where_in('tbl_spot_sales_conf_detail.buyer', $buyer);
		}
		if($status != NULL){
			if(is_string($status)) $status = explode(",", $status);
			$this->db->where_in('tbl_spot_sales_conf_detail.status', $status);
		}
		if($search != NULL){
			$this->db->where("tbl_spot_sales_conf_detail.no_formxxx like '%$search%'");
		}

		$this->db->limit(1); 
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
		
	}
	
	//data detail excel
	function get_data_detail_excel($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		
		$this->db->select("*");
		$this->db->from("vw_spot_detail");
		// $this->db->limit(1); 
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
		
	}
	
	function get_data_simulasi($conn = NULL, $id_simulate =null, $buyer = null) {
		$this->db->query("SET ANSI_NULLS ON");
		$this->db->query("SET ANSI_WARNINGS ON");
		
		$string = "
					select 
					tbl_spot_simulate.*,
					(select top 1 ZDMMSCUSTMR.KUNNR+' - '+ZDMMSCUSTMR.NAME2 from SAPSYNC.dbo.ZDMMSCUSTMR where ZDMMSCUSTMR.NAME2 COLLATE SQL_Latin1_General_CP1_CI_AS=tbl_spot_simulate.buyer) as buyer_detail,
					CAST( 
							( 
							  SELECT tbl_spot_simulate.factory+'|'+tbl_spot_simulate.plant+ RTRIM(',') 
								FROM 
								".DB_PORTAL.".dbo.tbl_spot_simulate
								where 
								tbl_spot_simulate.final='n'
								and tbl_spot_simulate.na='n'
								group by tbl_spot_simulate.factory+'|'+tbl_spot_simulate.plant
								order by tbl_spot_simulate.factory+'|'+tbl_spot_simulate.plant asc 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_plant,
					CAST( 
							( 
							  SELECT ZDMMSCUSTMR.KUNNR+'|'+ZDMMSCUSTMR.KUNNR+' - '+ZDMMSCUSTMR.NAME2+ RTRIM(',') 
								FROM 
								SAPSYNC.dbo.ZDMMSCUSTMR
								LEFT OUTER JOIN SAPSYNC.dbo.ZDMMKTSUPP54 ON ZDMMSCUSTMR.KUNNR=ZDMMKTSUPP54.KUNNR
								WHERE 
								ZDMMSCUSTMR.VTWEG in('01','02') and
								ZDMMSCUSTMR.NAME1!='' and
								ZDMMSCUSTMR.KTOKD = 'Y001' and 
								ZDMMKTSUPP54.PAVIP in ('3','4','5') and
								ZDMMKTSUPP54.SMTP_ADDR is not null
								group by ZDMMSCUSTMR.KUNNR+'|'+ZDMMSCUSTMR.KUNNR+' - '+ZDMMSCUSTMR.NAME2
								order by ZDMMSCUSTMR.KUNNR+'|'+ZDMMSCUSTMR.KUNNR+' - '+ZDMMSCUSTMR.NAME2 asc 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_buyer,
					CAST( 
							( 
							  SELECT tbl_spot_dc.dc+ RTRIM(',') 
								FROM 
								".DB_PORTAL.".dbo.tbl_spot_dc
								order by tbl_spot_dc.dc asc 
							  FOR XML PATH ('') 
							) as VARCHAR(MAX) 
							) as list_dc,
					CASE
						WHEN (select top 1 substring(tbl_spot_sales_conf_head.no_form, 1, 4) from ".DB_PORTAL.".dbo.tbl_spot_sales_conf_head where SUBSTRING(tbl_spot_sales_conf_head.no_form, 6, 4)=YEAR(GETDATE()) order by tbl_spot_sales_conf_head.no_form desc) is null
						THEN '0000'
						ELSE (select top 1 substring(tbl_spot_sales_conf_head.no_form, 1, 4) from ".DB_PORTAL.".dbo.tbl_spot_sales_conf_head where SUBSTRING(tbl_spot_sales_conf_head.no_form, 6, 4)=YEAR(GETDATE()) order by tbl_spot_sales_conf_head.no_form desc)
					END
					as last_nomor,
					tbl_wf_master_plant.plant_name
					from 
					".DB_PORTAL.".dbo.tbl_spot_simulate
					left outer join ".DB_PORTAL.".dbo.tbl_wf_master_plant on tbl_wf_master_plant.plant=tbl_spot_simulate.plant
					where 
					tbl_spot_simulate.na='n' 
		";			
		if ($id_simulate != NULL) {
			$string .= " and tbl_spot_simulate.id_simulate in($id_simulate)";
		}else{
			$string .= " and (tbl_spot_simulate.final='n' OR tbl_spot_simulate.final IS NULL)";
		}
		if ($buyer != NULL) {
			$string .= " and tbl_spot_simulate.buyer='$buyer'";
		}
		
		
		$query = $this->db->query($string);
		return $query->result();
	}
	
	function get_data_simulasi_old($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select("tbl_spot_simulate.plant as TPPCO");
		$this->db->select("tbl_spot_simulate.*");
		
		$this->db->from("tbl_spot_simulate");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_sim($conn = NULL, $id_simulate = NULL) {
		if ($conn !== NULL) 
			$this->general->connectDbPortal();

		$this->db->select("tbl_spot_simulate.buyer");
		$this->db->from("tbl_spot_simulate");
		if ($id_simulate !== NULL) {
			$this->db->where("tbl_spot_simulate.id_simulate in($id_simulate)");
		}
		$this->db->group_by('tbl_spot_simulate.buyer');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
}
?>