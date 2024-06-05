<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application    : SHE 
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/

class Dtransactionshe extends CI_Model{

	function get_data_limbah_air_bulanan($id=NULL, $plant=NULL, $jenis, $all=NULL){
		// $this->general->connectDbDefault();

		$string	= "SELECT a.id, a.fk_pabrik, a.fk_parameter, b.parameter, a.fk_jenis, c.jenis lokasi, CONVERT(VARCHAR(10),tanggal_sampling,104) tanggal_sampling, CONVERT(VARCHAR(10),tanggal_analisa,104) tanggal_analisa, d.bakumutu_hasilujilimit,
				a.hasil_uji, 
				--CAST(d.bakumutu_hasilujimin AS VARCHAR(5)) + ' - ' + CAST(d.bakumutu_hasilujimax AS VARCHAR(5)) bakumutu_hasiluji, 
				convert(varchar,convert(decimal(8,2),d.bakumutu_hasilujimin)) + ' - ' + convert(varchar,convert(decimal(8,2),d.bakumutu_hasilujimax))  bakumutu_hasiluji, 
				oi_debit, JMPRD / 1000 crumbing, CAST(d.bakumutu_bebancemarmin AS VARCHAR(5)) + ' - ' + CAST(d.bakumutu_bebancemarmax AS VARCHAR(5)) bakumutu_bebancemar,
				CASE WHEN b.parameter = 'pH' THEN hasil_uji ELSE (hasil_uji * oi_debit) / JMPRD end bp,
				CASE WHEN b.parameter = 'pH' THEN hasil_uji ELSE (hasil_uji * oi_debit) / 1000000 end bpa, a.lampiran, a.na, a.del,a.fk_kategori, tbl_she_master_kategori.kategori
				FROM tbl_she_tr_airlimbah_bulanan a
				INNER JOIN tbl_inv_pabrik p
				  on a.fk_pabrik = p.id_pabrik 
				LEFT JOIN tbl_she_master_parameter b 
				  ON a.fk_parameter = b.id
				LEFT JOIN tbl_she_master_jenis c 
				  ON a.fk_jenis = c.id                  
				LEFT JOIN tbl_she_bakumutu d
				  ON a.fk_kategori = d.fk_kategori
				  AND a.fk_jenis = d.fk_jenis
				  AND a.fk_parameter = d.fk_parameter
				  AND a.tanggal_sampling BETWEEN d.tanggal_mulai AND d.tanggal_akhir
				LEFT JOIN (SELECT e.fk_pabrik, SUM(oi_debit) oi_debit FROM tbl_she_tr_airlimbah_harian e 
				          GROUP BY e.fk_pabrik) e
				  ON e.fk_pabrik = a.fk_pabrik 
				LEFT JOIN (SELECT GJAHR + MONAT PERIOD, WERKS, SUM(JMPRD) JMPRD FROM SAPSYNC.dbo.ZDMFACOP01 f 
				          GROUP BY GJAHR + MONAT, WERKS) f
				  ON f.WERKS = p.kode COLLATE SQL_Latin1_General_CP1_CI_AS
				  AND f.PERIOD = CONVERT(CHAR(6),a.tanggal_sampling,112) COLLATE SQL_Latin1_General_CP1_CI_AS
				LEFT JOIN tbl_she_master_kategori 
					ON tbl_she_master_kategori.id = a.fk_kategori
				WHERE a.del=1 AND a.is_active=1 ";

		if(isset($id) && $id != ""){
			$string .= " AND a.id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND a.fk_pabrik = '".$plant."'";				
		}
		if(isset($jenis) && $jenis != ""){
			$string .= " AND a.fk_jenis = '".$jenis."'";				
		}
		$string	.= " order by a.tanggal_sampling desc";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		// $this->general->closeDB();

		return $result;


	}

	function get_data_parameter($pabrik=NULL, $jenis=NULL, $kategori=NULL ){
		$string	= "SELECT a.id, c.parameter as data,c.id as idparam 
            FROM tbl_she_parameter a 
            LEFT JOIN tbl_inv_pabrik b ON a.fk_pabrik = b.id_pabrik
            LEFT JOIN tbl_she_master_parameter c ON a.fk_parameter = c.id
            WHERE a.fk_pabrik='".$pabrik."' AND a.fk_jenis=".$jenis." AND a.fk_kategori='".$kategori."' AND a.del=1 ";
            
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_limbah_air_harian($id=NULL, $plant=NULL, $tanggal=NULL, $kategori=NULL){
		// $tanggal = substr($tanggal, -4).substr($tanggal, 3, 2).substr($tanggal, 0, 2);
		$tanggal = substr($tanggal, -4).substr($tanggal, 0, 2);
		$string	= "EXEC SP_Kiranaku_SHE_TrxAirLimbahHarian";

		if(isset($id) && $id != ""){
			$string .= " ".$id."";				
		}else{
			$string .= " NULL";
		}
		if(isset($plant) && $plant != ""){
			$string .= ", ".$plant."";				
		}else{
			$string .= ", NULL";				
		}
		if(isset($tanggal) && $tanggal != ""){
			$string .= ", '".$tanggal."'";				
		}else{
			$string .= ", NULL";				
		}

		if(isset($kategori) && $kategori != ""){
			$string .= ", ".$kategori."";				
		}else{
			$string .= ", NULL";				
		}
		// echo $string;
		$query	= $this->db->query($string);
		$result	= $query->result();
		return $result;
	}

	function get_limbah_air_harian_ipal($pabrik=NULL, $tanggal=NULL,$kategori=NULL){
		$tanggal = substr($tanggal, -4).substr($tanggal, 0, 2);
		$filter_kategori = ($kategori!=NULL)?"and fk_kategori = '".$kategori."'":"";
		$string	= "select kapasitas_ipal, CAST(sba_do_avg AS DECIMAL(8,2)) sba_do_avg, CAST(sba_sv_avg AS DECIMAL(8,2)) sba_sv_avg, 
				CAST(sba_ph_avg AS DECIMAL(8,2)) sba_ph_avg, CAST(sd_do_avg AS DECIMAL(8,2)) sd_do_avg, 
				CAST(slb_sv_avg AS DECIMAL(8,2)) slb_sv_avg, CAST(oi_debit_avg AS DECIMAL(8,2)) oi_debit_avg, 
				CAST(oi_ph_avg AS DECIMAL(8,2)) oi_ph_avg, CAST(bi_transparansi_avg AS DECIMAL(8,2)) bi_transparansi_avg, 
				CAST(sba_do_sum AS DECIMAL(8,2)) sba_do_sum, CAST(oi_debit_sum AS DECIMAL(8,2)) oi_debit_sum
				, CAST(satuan_produksi_sum AS DECIMAL(8,2)) satuan_produksi_sum, CAST(produksi_sir_sum AS DECIMAL(8,2)) produksi_sir_sum, CAST(debit_harian_sum AS DECIMAL(8,2)) debit_harian_sum
			from 
			(
				select ISNULL(AVG(sba_do),0) sba_do_avg, ISNULL(AVG(sba_sv),0) sba_sv_avg, ISNULL(AVG(sba_ph),0) sba_ph_avg, 
				ISNULL(AVG(sd_do),0) sd_do_avg, ISNULL(AVG(slb_sv),0) slb_sv_avg, ISNULL(AVG(oi_debit),0) oi_debit_avg, 
				ISNULL(AVG(oi_ph),0) oi_ph_avg, ISNULL(AVG(bi_transparansi),0) bi_transparansi_avg, 
				ISNULL(SUM(sba_do),0) sba_do_sum, ISNULL(SUM(oi_debit),0) oi_debit_sum
				, ISNULL(SUM(satuan_produksi),0) satuan_produksi_sum, ISNULL(SUM(produksi_sir),0) produksi_sir_sum, ISNULL(SUM(debit_harian),0) debit_harian_sum
				from 
				(
				SELECT a.id, a.fk_pabrik, a.tanggal, a.sba_do,a.sba_sv,a.sba_ph, a.sd_do,
				  a.slb_sv, a.oi_debit,a.oi_ph,a.bi_transparansi, convert(varchar, a.tanggal, 105) as tnggl, a.na, a.del
				  ,a.satuan_produksi, a.produksi_sir, a.debit_harian
			  		FROM tbl_she_tr_airlimbah_harian a 
			  		LEFT JOIN tbl_inv_pabrik b ON a.fk_pabrik = b.id_pabrik
					WHERE a.del=1
					and fk_pabrik = ".$pabrik." 
					$filter_kategori
					and CONVERT(char(6),tanggal,112) = '".$tanggal."' 
				) a
			) avg_sum
			,
			(
			SELECT TOP 1 kapasitas_ipal
			FROM tbl_she_kapasitas_ipal 
			WHERE fk_pabrik = ".$pabrik." AND del=1
			) ipal";
		// echo "<pre>$string</pre>";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}


	function get_filter_limbah_air_harian($id=NULL, $plant=NULL, $tanggal=NULL){
		$string	= "SELECT TOP 15 a.id, a.fk_pabrik, a.tanggal, a.sba_do,a.sba_sv,a.sba_ph, a.sd_do,
      a.slb_sv, a.oi_debit,a.oi_ph,a.bi_transparansi ,
		-- SBA 10-12 
      (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 30) 
        as bakuhasillimitsba_do,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 30) 
        as bakuhasilminsba_do,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 30) 
        as bakuhasilmaxsba_do,
		--  13-15 
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 31) 
        as bakuhasillimitsba_sv,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 31) 
        as bakuhasilminsba_sv,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 31) 
        as bakuhasilmaxsba_sv,
		-- 16-18 
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 1) 
        as bakuhasillimitsba_ph,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 1) 
        as bakuhasilminsba_ph,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 1) 
        as bakuhasilmaxsba_ph,
		-- SD 19-21
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 8 AND x.fk_parameter = 30) 
        as bakuhasillimitsd_do,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 8 AND x.fk_parameter = 30) 
        as bakuhasilminsd_do,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 8 AND x.fk_parameter = 30) 
        as bakuhasilmaxsd_do,
		-- SLB 22-24
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 9 AND x.fk_parameter = 31) 
        as bakuhasillimitslb_sv,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 9 AND x.fk_parameter = 31) 
        as bakuhasilminslb_sv,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 9 AND x.fk_parameter = 31) 
        as bakuhasilmaxslb_sv,
		-- OI 25-27
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 1 AND x.fk_parameter = 1) 
        as bakuhasillimitoi_ph,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 1 AND x.fk_parameter = 1) 
        as bakuhasilminoi_ph,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 1 AND x.fk_parameter = 1) 
        as bakuhasilmaxoi_ph,
		-- BI 28-30 
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 10 AND x.fk_parameter = 32) 
        as bakuhasillimitbi_t,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 10 AND x.fk_parameter = 32) 
        as bakuhasilminbi_t,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 10 AND x.fk_parameter = 32) 
        as bakuhasilmaxbi_t, convert(varchar, a.tanggal, 105) as tnggl, a.na, a.del
      	FROM tbl_she_tr_airlimbah_harian a 
      	LEFT JOIN tbl_inv_pabrik b ON a.fk_pabrik = b.id_pabrik
		WHERE a.del=1 ";

		if(isset($id) && $id != ""){
			$string .= " AND a.id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND a.fk_pabrik = '".$plant."'";				
		}
		if(isset($tanggal) && $tanggal != ""){
			$string .= " AND a.tanggal = '".$tanggal."'";				
		}
		$string	.= " order by a.tanggal desc";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_filter_limbah_air_avg_sum($id=NULL, $plant=NULL, $tanggal=NULL){
		$string	= "SELECT TOP 15 a.id, a.fk_pabrik, a.tanggal, a.sba_do,a.sba_sv,a.sba_ph, a.sd_do,
      a.slb_sv, a.oi_debit,a.oi_ph,a.bi_transparansi ,
		-- SBA 10-12 
      (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 30) 
        as bakuhasillimitsba_do,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 30) 
        as bakuhasilminsba_do,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 30) 
        as bakuhasilmaxsba_do,
		--  13-15 
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 31) 
        as bakuhasillimitsba_sv,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 31) 
        as bakuhasilminsba_sv,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 31) 
        as bakuhasilmaxsba_sv,
		-- 16-18 
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 1) 
        as bakuhasillimitsba_ph,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 1) 
        as bakuhasilminsba_ph,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 7 AND x.fk_parameter = 1) 
        as bakuhasilmaxsba_ph,
		-- SD 19-21
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 8 AND x.fk_parameter = 30) 
        as bakuhasillimitsd_do,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 8 AND x.fk_parameter = 30) 
        as bakuhasilminsd_do,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 8 AND x.fk_parameter = 30) 
        as bakuhasilmaxsd_do,
		-- SLB 22-24
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 9 AND x.fk_parameter = 31) 
        as bakuhasillimitslb_sv,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 9 AND x.fk_parameter = 31) 
        as bakuhasilminslb_sv,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 9 AND x.fk_parameter = 31) 
        as bakuhasilmaxslb_sv,
		-- OI 25-27
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 1 AND x.fk_parameter = 1) 
        as bakuhasillimitoi_ph,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 1 AND x.fk_parameter = 1) 
        as bakuhasilminoi_ph,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 1 AND x.fk_parameter = 1) 
        as bakuhasilmaxoi_ph,
		-- BI 28-30 
        (SELECT x.bakumutu_hasilujilimit FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 10 AND x.fk_parameter = 32) 
        as bakuhasillimitbi_t,
        (SELECT x.bakumutu_hasilujimin FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 10 AND x.fk_parameter = 32) 
        as bakuhasilminbi_t,
        (SELECT x.bakumutu_hasilujimax FROM tbl_she_bakumutu x WHERE x.tanggal_mulai <= a.tanggal
        AND x.tanggal_akhir >= a.tanggal AND x.del=1 AND x.fk_kategori=2 AND x.fk_jenis = 10 AND x.fk_parameter = 32) 
        as bakuhasilmaxbi_t, convert(varchar, a.tanggal, 105) as tnggl, a.na, a.del
      	FROM tbl_she_tr_airlimbah_harian a 
      	LEFT JOIN tbl_inv_pabrik b ON a.fk_pabrik = b.id_pabrik
		WHERE a.del=1 ";

		if(isset($id) && $id != ""){
			$string .= " AND a.id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND a.fk_pabrik = '".$plant."'";				
		}
		if(isset($tanggal) && $tanggal != ""){
			$string .= " AND a.tanggal = '".$tanggal."'";				
		}
		$string	.= " order by a.tanggal desc";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}


	function get_data_limbah_udara($id=NULL, $plant=NULL, $kategori=NULL, $jenis=NULL, $from=NULL, $to=NULL, $tgl_sampling=NULL){
		$from = substr($from, -4).substr($from, 0, 2);
		$to = substr($to, -4).substr($to, 0, 2);

		$string	= "SELECT "; 
		if(empty($id) && empty($plant) && empty($kategori) && empty($jenis) && empty($from) && empty($to)){
			$string .= "TOP 0 ";				
		}
		$string	.= "a.tanggal_buat, a.id, a.fk_pabrik, a.fk_kategori, a.fk_jenis, a.fk_parameter, a.hasil_uji, 
				CAST(a.tanggal_sampling AS DATE) as 'tanggal_sampling', 
				CAST(a.tanggal_analisa AS DATE) as 'tanggal_analisa',c.kategori,d.jenis, b.parameter as 'parameter', 
				a.hasil_uji,a.laju_air,a.jam_operasi, a.lampiran, a.na,
				e.nama as nama_pabrik,
				e.kode as kode_pabrik
				FROM tbl_she_tr_kualitasudara a 
				LEFT JOIN tbl_she_master_parameter b ON a.fk_parameter = b.id  
				LEFT JOIN tbl_she_master_kategori c ON a.fk_kategori = c.id 
				LEFT JOIN tbl_she_master_jenis d ON a.fk_jenis = d.id                                    
				LEFT JOIN tbl_inv_pabrik e ON a.fk_pabrik = e.id_pabrik 
				WHERE a.del=1";

		if(isset($id) && $id != ""){
			$string .= " AND a.id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND a.fk_pabrik = '".$plant."'";				
		}
		if(isset($kategori) && $kategori != ""){
			$string .= " AND a.fk_kategori = '".$kategori."'";				
		}
		if(isset($jenis) && $jenis != ""){
			$string .= " AND a.fk_jenis = '".$jenis."'";				
		}
		if(isset($from) && $from != "" && isset($to) && $to != ""){
			$string .= " AND CONVERT(CHAR(6),a.tanggal_sampling,112) BETWEEN '".$from."' AND '".$to."'";				
		}
		if(isset($tgl_sampling) && $tgl_sampling != ""){
			$string .= " AND CAST(a.tanggal_sampling AS DATE) =  '".$tgl_sampling."'";				
		}
		$string	.= " ORDER BY a.tanggal_sampling DESC";

		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_kategori_kualitasudara(){
		$string	= "SELECT * FROM tbl_she_master_kategori WHERE id in (3,4,5,6) ORDER BY kategori";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;	
	}

	function get_data_limbah_udara_parameter($plant=NULL, $kategori=NULL, $jenis=NULL){
		$string	= "SELECT c.*
			FROM tbl_she_parameter a 
			LEFT JOIN tbl_inv_pabrik b ON a.fk_pabrik = b.id_pabrik
			LEFT JOIN tbl_she_master_parameter c ON a.fk_parameter = c.id
			WHERE 1=1";  
		if($plant!=NULL){
			$string .= " AND a.fk_pabrik = '$plant'";				
		}
		if($kategori!=NULL){
			$string .= " AND a.fk_kategori = '$kategori'";				
		}
		if($jenis!=NULL){
			$string .= " AND a.fk_jenis = '$jenis'";				
		}
		// $string = "Select * from tbl_she_master_parameter where del = 1 and isnull(na,1) = 1";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_kualitasudara_filterjenis($plant=NULL, $kategori=NULL){
		$string	= "select b.id, b.jenis from tbl_she_jenis a
					inner join tbl_she_master_jenis b
						on a.fk_jenis = b.id
					where fk_pabrik = ".$plant." and fk_kategori = ".$kategori."
					order by b.jenis";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;	
	}


	function get_data_limbah_b3($id=NULL, $plant=NULL, $status=NULL){
		$string	= "SELECT a.id, a.fk_pabrik, a.fk_limbah, a.fk_sumber_limbah, a.tanggal_buat ,b.nama pabrik, a.type, 
				CASE WHEN a.type = 'IN' THEN CONVERT(VARCHAR(10),a.tanggal_masuk,104) ELSE CONVERT(VARCHAR(10),a.tanggal_keluar,104) END tanggal_transaksi, 
				CASE WHEN a.type = 'IN' THEN CAST(a.tanggal_masuk AS DATE) ELSE CAST(a.tanggal_keluar AS DATE) END tanggal_order, 
				c.jenis_limbah, c.kode_material, d.sumber_limbah, a.quantity, a.stok, a.id, CAST(a.dsimpan_max AS DATE) tgl_exp, 
				e.nama satuan, c.konversi_ton, a.lampiran1, a.lampiran2, a.lampiran3, a.fk_vendor, f.nama_vendor, 
				a.jenis_kendaraan, a.nomor_kendaraan, a.nama_driver, a.no_manifest, a.request_del, a.tgl_request_del,
				case when a.request_del = 0 and a.stok <> -1 then 'Posted' 
					when a.request_del = 1 then 'Requested'
				else ''
				end status
				FROM tbl_she_tr_b3_limbah a 
				LEFT JOIN tbl_inv_pabrik b 	ON a.fk_pabrik = b.id_pabrik
				LEFT JOIN tbl_she_limbah c ON c.id = a.fk_limbah
				LEFT JOIN tbl_she_master_sumber_limbah d ON d.id = a.fk_sumber_limbah
				LEFT JOIN tbl_she_master_satuan e ON e.id_uom = c.fk_satuan
				LEFT JOIN tbl_she_vendor f ON a.fk_vendor = f.id
				-- LEFT JOIN tbl_she_konversi_limbah g ON a.fk_limbah = g.fk_limbah AND a.fk_pabrik = g.fk_pabrik
				WHERE a.del=1 AND a.type IN ('IN', 'OUT')";

		if(isset($id) && $id != ""){
			$string .= " AND a.id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND a.fk_pabrik = '".$plant."'";				
		}
		if(isset($status) && $status != "" && $status == "1"){
			$string .= " AND a.stok = -1 AND a.request_del <> 1";				
		}elseif(isset($status) && $status != "" && $status == "2"){
			$string .= " AND a.stok <> -1 AND a.request_del <> 1";				
		}elseif(isset($status) && $status != "" && $status == "3"){
			$string .= " AND a.request_del = 1";
		}
		$string .= " ORDER BY tanggal_order DESC, a.type";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_plant_limbah_b3($id=NULL, $plant=NULL, $status=NULL){
		$string	= "SELECT *
				FROM tbl_she_tr_b3_limbah a 
				WHERE a.type IN ('IN', 'OUT')";

		if(isset($id) && $id != ""){
			$string .= " AND a.id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND a.fk_pabrik = '".$plant."'";				
		}
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_view_data_limbah_b3($id=NULL, $plant=NULL, $status=NULL, $datefrom=NULL, $dateto=NULL){
		$string	= "SELECT a.id, a.fk_pabrik, a.fk_limbah, a.fk_sumber_limbah, a.tanggal_buat ,b.nama pabrik, a.type, 
				CASE WHEN a.type = 'IN' THEN CONVERT(VARCHAR(10),a.tanggal_masuk,104) ELSE CONVERT(VARCHAR(10),a.tanggal_keluar,104) END tanggal_transaksi, 
				CASE WHEN a.type = 'IN' THEN CAST(a.tanggal_masuk AS DATE) ELSE CAST(a.tanggal_keluar AS DATE) END tanggal_order, 
				c.jenis_limbah, c.kode_material, d.sumber_limbah, a.quantity, a.stok, a.id, CAST(a.dsimpan_max AS DATE) tgl_exp, 
				e.nama satuan, g.konversi_ton, a.lampiran1, a.lampiran2, a.lampiran3, a.fk_vendor, f.nama_vendor, 
				a.jenis_kendaraan, a.nomor_kendaraan, a.nama_driver, a.no_manifest, a.request_del, a.tgl_request_del,
				case when a.request_del = 0 and a.stok <> -1 then 'Posted' 
					when a.request_del = 1 then 'Requested'
				else ''
				end status
				FROM tbl_she_tr_b3_limbah a 
				LEFT JOIN tbl_inv_pabrik b 	ON a.fk_pabrik = b.id_pabrik
				LEFT JOIN tbl_she_limbah c ON c.id = a.fk_limbah
				LEFT JOIN tbl_she_master_sumber_limbah d ON d.id = a.fk_sumber_limbah
				LEFT JOIN tbl_she_master_satuan e ON e.id_uom = c.fk_satuan
				LEFT JOIN tbl_she_vendor f ON a.fk_vendor = f.id
				LEFT JOIN tbl_she_konversi_limbah g ON a.fk_limbah = g.fk_limbah AND a.fk_pabrik = g.fk_pabrik
				WHERE a.del=1 AND a.type IN ('IN', 'OUT')";

		if(isset($id) && $id != ""){
			$string .= " AND a.id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND a.fk_pabrik in ('".$plant."')";				
		}
		if(isset($status) && $status != "" && $status == "1"){
			$string .= " AND a.stok = -1 AND a.request_del <> 1";				
		}elseif(isset($status) && $status != "" && $status == "2"){
			$string .= " AND a.stok <> -1 AND a.request_del <> 1";				
		}elseif(isset($status) && $status != "" && $status == "3"){
			$string .= " AND a.request_del = 1";
		}
		if(isset($datefrom) && $datefrom != "" && isset($dateto) && $dateto != ""){
			$datefrom = $this->generate->regenerateDateFormat($datefrom);
			$dateto = $this->generate->regenerateDateFormat($dateto);
			$string .= " AND CAST(ISNULL(a.tanggal_masuk,a.tanggal_keluar) AS DATE) BETWEEN '".$datefrom."' AND '".$dateto."'";				
		}
		$string .= " ORDER BY tanggal_order DESC, a.type";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_limbahB3($id=NULL, $plant=NULL){
		$string	= "SELECT quantity, type, batch, fk_limbah, fk_pabrik,
					ISNULL((
						SELECT TOP 1 stok FROM tbl_she_tr_b3_limbah 
						WHERE fk_limbah = a.fk_limbah AND fk_pabrik = a.fk_pabrik 
						AND type IN ('IN','OUT') AND stok <> -1 and del = 1
						ORDER BY id DESC
					),0) last_stock
					FROM tbl_she_tr_b3_limbah a 
					WHERE id = ".$id."";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_perpanjang_masa_b3($id=NULL, $plant=NULL){
		$string	= "SELECT a.id, b.nama as pabrik, c.jenis_limbah as limbah, a.type, 
				CAST(a.dsimpan_max AS DATE) dsimpan_max, a.ext_days,a.stok, a.lampiran1,
				CAST(a.tanggal_masuk AS DATE) as tanggal_masuk, d.nama as satuan, 
				DATEADD(D, ext_days, dsimpan_max) dsimpan_ext, a.id, a.na
				FROM tbl_she_tr_b3_limbah a 
				LEFT JOIN tbl_inv_pabrik b ON a.fk_pabrik = b.id_pabrik
				LEFT JOIN tbl_she_limbah c ON a.fk_limbah = c.id  
				LEFT JOIN tbl_she_master_satuan d ON c.fk_satuan = d.id_uom                             
				WHERE a.del=1 AND a.type='EXT'";

		if(isset($id) && $id != ""){
			$string .= " AND a.id = ".$id."";				
		}
		if(isset($plant) && $plant != ""){
			$string .= " AND a.fk_pabrik = '".$plant."'";				
		}
		$string	.= " ORDER BY a.id DESC";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_limbah_masa_b3($plant=NULL){
		$string	= "EXEC dbo.SP_Kiranaku_SHE_GetExtendLimbahB3 ".$plant."";
		// $string	= "SELECT fk_pabrik, limbah, satuan, id_uom, id, batch, stock,
		// 			(
		// 				SELECT TOP 1 CAST(tanggal_masuk AS DATE) tanggal_masuk
		// 				FROM tbl_she_tr_b3_limbah 
		// 				WHERE fk_pabrik = a.fk_pabrik AND del = 1 AND fk_limbah = a.id
		// 				AND batch = a.batch AND (type='IN' OR type='EXT')
		// 				ORDER BY tanggal_masuk ASC
		// 			) tanggal_masuk,
		// 			(
		// 				SELECT TOP 1 CAST(dsimpan_max AS DATE) dsimpan_max
		// 				FROM tbl_she_tr_b3_limbah 
		// 				WHERE fk_pabrik = a.fk_pabrik AND del = 1 AND fk_limbah = a.id
		// 				AND batch = a.batch AND (type='IN' OR type='EXT')
		// 				ORDER BY tanggal_masuk ASC
		// 			) dsimpan_max
		// 			FROM 
		// 			(
		// 				SELECT fk_pabrik, limbah, satuan, id_uom, id, batch, stock 
		// 				FROM 
		// 				(
		// 					SELECT fk_pabrik, limbah, satuan, id_uom, id, batch,
		// 					ISNULL((
		// 						SELECT TOP 1 stok FROM tbl_she_tr_b3_limbah                           
		// 						WHERE fk_pabrik = a.fk_pabrik AND del = 1 AND fk_limbah = a.id AND batch = a.batch AND stok >= 0
		// 						ORDER BY tanggal_masuk,tanggal_keluar DESC
		// 					),0) stock
		// 					FROM 
		// 					(
		// 						SELECT a.fk_pabrik, a.jenis_limbah as limbah, b.nama as satuan, b.id_uom, a.id, 
		// 						ISNULL((
		// 							SELECT max(batch) FROM tbl_she_tr_b3_limbah
		// 							WHERE fk_pabrik = a.fk_pabrik AND del = 1 AND fk_limbah = a.id
		// 						),0) batch
		// 						FROM tbl_she_limbah a 
		// 						LEFT JOIN tbl_she_master_satuan b ON a.fk_satuan = b.id_uom
		// 						WHERE a.del = 1";
		// if($plant != NULL){
		// 	$string .= "AND a.fk_pabrik = ".$plant."";
		// }
		// $string .= "
		// 					) a
		// 				) a
		// 				WHERE stock > 0
		// 			) a
		// 			ORDER BY limbah";

		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_edit_data_limbah_masa_b3($id=NULL){
		$string	= "select a.id, a.fk_pabrik, a.fk_limbah, b.jenis_limbah, b.kode_material, CONVERT(VARCHAR(10),a.tanggal_masuk,104) tanggal_masuk, 
			CONVERT(VARCHAR(10),CAST(CAST(a.tanggal_masuk AS DATETIME) + CAST(b.masa_simpan AS INT) AS DATE),104) dmasasimpan_max, 
			CONVERT(VARCHAR(10),a.dsimpan_max,104) dsimpan_max, a.ext_days, a.stok, c.nama satuan
			from tbl_she_tr_b3_limbah a
			left join tbl_she_limbah b
				on a.fk_limbah = b.id
			left join tbl_she_master_satuan c
				on b.fk_satuan = c.id_uom
			where a.id = ".$id."";

		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_lasttrx($pabrik=NULL, $jenislimbah=NULL){
		$string	= "select CASE WHEN ISNULL(MAX(tanggal_masuk),'1900-01-01') > ISNULL(MAX(tanggal_keluar),'1900-01-01') THEN MAX(tanggal_masuk) ELSE MAX(tanggal_keluar) END tanggal
			from tbl_she_tr_b3_limbah
			where del = 1";

		if($pabrik != NULL && $pabrik != ""){
			$string .= " and fk_pabrik = ".$pabrik."";
		}
		if($jenislimbah != NULL && $jenislimbah != ""){
			$string .= " and fk_limbah = ".$jenislimbah."";
		}
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_trx_ext($plant=NULL, $jenislimbah=NULL, $tgl_masuk=NULL){
		$string = "SELECT tanggal_masuk, dsimpan_max, quantity, stok 
			FROM tbl_she_tr_b3_limbah
			WHERE del = 1 AND type = 'IN' AND fk_pabrik = ".$plant." AND fk_limbah = ".$jenislimbah."
			AND tanggal_masuk >= '".$tgl_masuk."'
			ORDER BY id DESC";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_trx_in($plant=NULL, $jenislimbah=NULL, $tgl_masuk=NULL){
		$string = "SELECT ISNULL(SUM(quantity),0) quantity
			FROM tbl_she_tr_b3_limbah 
			WHERE fk_limbah = ".$jenislimbah." AND fk_pabrik = ".$plant." AND tanggal_masuk = '".$tgl_masuk."'
			AND stok <> -1 AND del = 1";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_latest_batch($plant=NULL, $jenislimbah=NULL, $tgl_masuk=NULL){
		// $string	= "SELECT stok, batch, tanggal_buat, masa_simpan, uom, qty_konversi, 
		// 		DATEADD(Day, CAST(masa_simpan AS INT), tanggal_buat) dsimpan_max
		// 		FROM 
		// 		(
		// 			SELECT TOP 1 stok, MAX(batch) batch, MAX(tanggal_buat) tanggal_buat,
		// 			(SELECT masa_simpan FROM tbl_she_limbah WHERE id = ".$jenislimbah.") masa_simpan,
		// 			(SELECT nama FROM tbl_she_limbah INNER JOIN tbl_she_master_satuan ON fk_satuan = id_uom WHERE id = ".$jenislimbah.") uom,
		// 			(SELECT konversi_ton FROM tbl_she_konversi_limbah WHERE fk_limbah = ".$jenislimbah.") qty_konversi
		// 			FROM tbl_she_tr_b3_limbah
		// 			WHERE del = 1 AND fk_limbah = ".$jenislimbah." AND fk_pabrik = ".$plant."
		// 			GROUP BY stok ORDER BY MAX(tanggal_buat) DESC
		// 		) a";
		// $string = "SELECT TOP 1 ISNULL(d.stok,0) stok, b.nama uom, ISNULL(MAX(d.batch),0) batch, MAX(d.tanggal_buat) tanggal_buat, 
		// 		DATEADD(DAY, CAST(masa_simpan AS INT), GETDATE()) dsimpan_max, c.konversi_ton qty_konversi,
		// 		(SELECT MAX(ISNULL(tanggal_masuk, tanggal_keluar)) FROM tbl_she_tr_b3_limbah
		// 		WHERE fk_pabrik = ".$plant." AND fk_limbah = ".$jenislimbah.") last_transaction
		// 		FROM tbl_she_limbah a
		// 		INNER JOIN tbl_she_master_satuan b
		// 			ON fk_satuan = id_uom 
		// 		LEFT JOIN tbl_she_konversi_limbah c
		// 			ON a.id = c.fk_limbah and c.fk_pabrik = ".$plant."
		// 		LEFT JOIN tbl_she_tr_b3_limbah d
		// 			ON a.id = d.fk_limbah and d.fk_pabrik = ".$plant."
		// 		WHERE a.id = ".$jenislimbah."
		// 		GROUP BY stok, masa_simpan, c.konversi_ton, b.nama
		// 		ORDER BY MAX(d.tanggal_buat) DESC";
		$string = "SELECT TOP 1 ISNULL(d.stok,0) stok, b.nama uom, d.batch, d.tanggal_buat, 
			DATEADD(DAY, CAST(masa_simpan AS INT), '".$tgl_masuk."') dsimpan_max, a.konversi_ton qty_konversi,
			ISNULL(tanggal_masuk, tanggal_keluar) last_transaction,  d.lampiran1, d.lampiran2, d.lampiran3, d.type
			FROM tbl_she_limbah a
			INNER JOIN tbl_she_master_satuan b
				ON fk_satuan = id_uom 
			--LEFT JOIN tbl_she_konversi_limbah c
			--	ON a.id = c.fk_limbah and c.fk_pabrik = ".$plant."
			LEFT JOIN tbl_she_tr_b3_limbah d
				ON a.id = d.fk_limbah and d.fk_pabrik = ".$plant." and d.del = 1 and d.type <> 'EXT'
			WHERE a.id = ".$jenislimbah." 
			ORDER BY d.id DESC";
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function get_data_latest_beritaacara($plant=NULL, $tanggal=NULL){
		$string	= "SELECT RIGHT('000' + CAST(ISNULL(CAST(LEFT(MAX(no_berita_acara),3) AS INT),0) + 1 AS VARCHAR(3)),3) + '/' + (SELECT kode FROM tbl_inv_pabrik where id_pabrik = ".$plant.") + '/' + CAST(YEAR('".$tanggal."') AS CHAR(4))  ba
				FROM tbl_she_tr_b3_limbah
				where fk_pabrik = ".$plant." AND [type] = 'OUT' AND YEAR(tanggal_keluar) = YEAR('".$tanggal."')";

		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}

	function push_ba_saprfc($no_ba){
		// $string	= "
		// 	SELECT '1' POSNR, '0000240000' LIFNR, 'OLI BEKAS' MAKTX, 20 MENGE, 'L' MEINS, 'ABL1' EKORG, 'BA-0018' BANUM, 'TRUK | B 87687 SJ | JONO' TXT01, '20180802' DATE1
		// 	UNION
		// 	SELECT '2' POSNR, '0000240000' LIFNR, 'FILTER OLI BEKAS' MAKTX, 30 MENGE, 'KG' MEINS, 'ABL1' EKORG, 'BA-0018' BANUM, 'TRUK | B 87687 SJ | JONO' TXT01, '20180802' DATE1
		// 	UNION
		// 	SELECT '3' POSNR, '0000240000' LIFNR, 'BAHAN KIMIA KADALUARSA' MAKTX, 10 MENGE, 'L' MEINS, 'ABL1' EKORG, 'BA-0018' BANUM, 'TRUK | B 87687 SJ | JONO' TXT01, '20180802' DATE1";

		$string	= "select ROW_NUMBER() OVER (ORDER BY a.id) AS POSNR, RIGHT('0000000000' + RTRIM(CAST(b.kode_vendor AS VARCHAR(10))),10) LIFNR, c.jenis_limbah MAKTX, a.quantity MENGE,
			d.nama MEINS, e.kode EKORG, a.no_berita_acara BANUM, a.jenis_kendaraan + ' | ' + a.nomor_kendaraan + ' | ' + a.nama_driver TXT01,
			CONVERT(CHAR(8),a.tanggal_keluar,112) DATE1
			FROM tbl_she_tr_b3_limbah a
			INNER JOIN tbl_she_vendor b
				ON a.fk_vendor = b.id
			INNER JOIN tbl_she_limbah c
				ON a.fk_limbah = c.id
			INNER JOIN tbl_she_master_satuan d
				ON c.fk_satuan = d.id_uom
			INNER JOIN tbl_inv_pabrik e
				ON a.fk_pabrik = e.id_pabrik
			WHERE a.no_berita_acara = '".$no_ba."' and a.del=1";
		
		$query	= $this->db->query($string);
		$result	= $query->result();

		return $result;
	}


	function recalculate_stock($plant=NULL){

		// $this->db->query("exec dbo.SP_Kiranaku_SHE_RecalculateStock ".$plant."");

		$string	= "EXEC dbo.SP_Kiranaku_SHE_RecalculateStock ".$plant."";
		$query	= $this->db->query($string);
		$result	= $query->result();

		if ($this->CI->dgeneral->status_transaction() === false) {
			$this->CI->dgeneral->rollback_transaction();
			$msg = "Transaksi gagal";
			$sts = "NotOK";
		} else {
			$this->CI->dgeneral->commit_transaction();
			$msg = "Transaksi berhasil";
			$sts = "OK";
		}
		$return = array('sts' => $sts, 'msg' => $msg);
		return $return;
	}

	//lha
	function cek_data_limbah_air_bulanan($conn = NULL, $id = NULL, $fk_pabrik = NULL, $fk_kategori = NULL, $fk_jenis = NULL, $tanggal_sampling = NULL, $tanggal_analisa = NULL, $fk_parameter = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_she_tr_airlimbah_bulanan.*');
		$this->db->from('tbl_she_tr_airlimbah_bulanan');						   
		if ($id !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_bulanan.id', $id);
		}
		if ($fk_pabrik !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_bulanan.fk_pabrik', $fk_pabrik);
		}
		if ($fk_kategori !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_bulanan.fk_kategori', $fk_kategori);
		}
		if ($fk_jenis !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_bulanan.fk_jenis', $fk_jenis);
		}
		if ($tanggal_sampling !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_bulanan.tanggal_sampling', $tanggal_sampling);
		}
		if ($tanggal_analisa !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_bulanan.tanggal_analisa', $tanggal_analisa);
		}
		if ($fk_parameter !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_bulanan.fk_parameter', $fk_parameter);
		}
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function cek_data_limbah_air_harian($conn = NULL, $id = NULL, $fk_pabrik = NULL, $tanggal = NULL, $kategori = NULL,$type=NULL, $id_exclude = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_she_tr_airlimbah_harian.*');
		$this->db->from('tbl_she_tr_airlimbah_harian');						   
		if ($id !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_harian.id', $id);
		}
		if ($fk_pabrik !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_harian.fk_pabrik', $fk_pabrik);
		}
		if ($tanggal !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_harian.tanggal', $tanggal);
		}
		if ($kategori !== NULL) {
			$this->db->where('tbl_she_tr_airlimbah_harian.fk_kategori', $kategori);
		}
		if ($type !== NULL) {
			if($type == 'update'){
				$this->db->where("tbl_she_tr_airlimbah_harian.id !='".$id_exclude."'");
			}
		}

		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_produksi_sir($conn = NULL, $id_pabrik = NULL, $tanggal = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();
		$tanggal	= $this->generate->regenerateDateFormat($tanggal);
		$this->db->select('tbl_inv_pabrik.kode');
		$this->db->select("(select top 1 ZDMFACOP17.SIR_CRUMB/1000 from SAPSYNC.dbo.ZDMFACOP17 where ZDMFACOP17.BUDAT='$tanggal' and  ZDMFACOP17.WERKS COLLATE SQL_Latin1_General_CP1_CI_AS = tbl_inv_pabrik.kode and ZDMFACOP17.SIR_CRUMB!=0 order by ZDMFACOP17.BUDAT DESC) as produksi_sir");
		$this->db->from('tbl_inv_pabrik');			

		if ($id_pabrik !== NULL) {
			$this->db->where('tbl_inv_pabrik.id_pabrik', $id_pabrik);
		}

		// $this->db->where("1=1");
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}

}

?>