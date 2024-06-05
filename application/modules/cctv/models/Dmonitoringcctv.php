<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
   	@application  : Monitoring CCTV 
	@author       : Airiza Yuddha (7849)
    @contributor	:                
                1. Airiza Yuddha (7849) 26.11.2020
                   add function delete_data_cctv
                2. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                etc.
    */

	class Dmonitoringcctv extends CI_Model {
		// /*================================DOT CCTV====================================*/
		// function get_master_dot($conn = NULL, $id_mdot = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $dotcheck = NULL,$exceptioncheck = NULL) {
		// 	if ($conn !== NULL)
		// 		$this->general->connectDbPortal();

		// 	$this->db->select('tbl_cctv_mdot.*');
			
		// 	$this->db->select('CASE
		// 						WHEN tbl_cctv_mdot.na = \'n\' AND tbl_cctv_mdot.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
		// 						WHEN tbl_cctv_mdot.na = \'y\' AND tbl_cctv_mdot.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
		// 				   		WHEN tbl_cctv_mdot.na = \'y\' AND tbl_cctv_mdot.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
		// 				   		ELSE \'<span class="label label-danger"></span>\'
		// 					   END as label_active');
			
		// 	$this->db->from('tbl_cctv_mdot');
			
			
		// 	if($typecheck !== NULL){
	 //        	if($typecheck=="in"){
	 //        		$this->db->where(" tbl_cctv_mdot.dot ",$dotcheck);		
	 //        	} else if($typecheck=="up"){
	 //        		$this->db->where(" tbl_cctv_mdot.dot ",$dotcheck);
	 //        		$this->db->where_not_in(" tbl_cctv_mdot.id_mdot ",$exceptioncheck);
	 //        	}
	 //        } else {
	 //        	if ($id_mdot !== NULL) {
		// 		$this->db->where('tbl_cctv_mdot.id_mdot', $id_mdot);
		// 		}
		// 		if($active !== NULL){
		// 			$this->db->where_in('tbl_cctv_mdot.na', $active);
		// 			$this->db->where_in('tbl_cctv_mdot.del', 'n');									
		// 		}
	 //        }
			
			
		// 	$this->db->order_by('tbl_cctv_mdot.dot ASC');

		// 	$query  = $this->db->get();
		// 	$result = $query->result();

		// 	if ($conn !== NULL)
		// 		$this->general->closeDb();
		// 	return $result;
		// }

		/*================================Monitoring CCTV====================================*/
		function get_data_monitoring($conn = NULL, $id_monitoring = NULL, $active = NULL, $deleted = 'n', $typecheck = NULL, $exceptioncheck = NULL,$monitoringcheck1 = NULL, $monitoringcheck2 = NULL, $monitoringcheck3 = NULL, $monitoringcheck4 = NULL, $monitoringcheck5 = NULL,$monitoringtypedetail = NULL, $filtertahun = NULL, $filterplant = NULL, $filterbulan = NULL, $monthMin1=NULL) {
			// echo $monitoringcheck1 .",". $monitoringcheck2 .",". $monitoringcheck3 .",". $monitoringcheck4 .",".$monitoringcheck5."|";
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			$where = "";

			$this->db->select('tbl_cctv_monitoring.*');
			
			$this->db->select('CASE
								WHEN tbl_cctv_monitoring.na = \'n\' AND tbl_cctv_monitoring.del = \'n\' THEN \'<span class="label label-success">ACTIVE</span>\'
								WHEN tbl_cctv_monitoring.na = \'y\' AND tbl_cctv_monitoring.del = \'n\' THEN \'<span class="label label-danger">NOT ACTIVE</span>\'
						   		WHEN tbl_cctv_monitoring.na = \'y\' AND tbl_cctv_monitoring.del = \'y\' THEN \'<span class="label label-danger">DELETED</span>\'
						   		ELSE \'<span class="label label-danger"></span>\'
							   END as label_active');
			
			$this->db->from('tbl_cctv_monitoring');
			// $this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant = tbl_cctv_monitoring.plant', 'inner');
			
			if($typecheck !== NULL){
	        	if($typecheck=="in"){
	        		$this->db->where(" tbl_cctv_monitoring.plant",$monitoringcheck1);	        		
	        		$this->db->where(" tbl_cctv_monitoring.week",$monitoringcheck2);
	        		$this->db->where(" tbl_cctv_monitoring.month",$monitoringcheck3);	        		
	        		$this->db->where(" tbl_cctv_monitoring.year",$monitoringcheck4);
	        		if($monitoringcheck5 !== NULL){	
	        			$this->db->where(" tbl_cctv_monitoring.id_mdot",$monitoringcheck5);
	        			$where_add = "AND a.id_mdot = `".$monitoringcheck5."`";
	        		} else {
	        			$where_add = "";
	        		}
	        		$where .= " AND a.plant = `".$monitoringcheck1."`" 
	        				.(isset($monitoringcheck2)?"AND a.week = `".$monitoringcheck2."`" :"")
        					."AND a.month = `".$monitoringcheck3."`"
        					."AND a.year = `".$monitoringcheck4."` " 
        					.$where_add ;	
	        	} else if($typecheck=="up"){
	        		$this->db->where(" tbl_cctv_monitoring.plant",$monitoringcheck1);	        		
	        		$this->db->where(" tbl_cctv_monitoring.week",$monitoringcheck2);
	        		$this->db->where(" tbl_cctv_monitoring.month",$monitoringcheck3);	        		
	        		$this->db->where(" tbl_cctv_monitoring.year",$monitoringcheck4);
	        		$this->db->where(" tbl_cctv_monitoring.id_mdot",$monitoringcheck5);
	        		$this->db->where_not_in(" tbl_cctv_monitoring.id_monitoring ",$exceptioncheck);
	        		$where .= " AND a.plant = `".$monitoringcheck1."` 
	        					AND a.week = `".$monitoringcheck2."` 
	        					AND a.month = `".$monitoringcheck3."` 
	        					AND a.year = `".$monitoringcheck4."` 
								AND a.id_mdot = `".$monitoringcheck5."`
								AND a.year NOT IN (`".$exceptioncheck."`)
	        					";	
	        	}
	        } else {
	        	if ($id_monitoring !== NULL) {
					$this->db->where('tbl_cctv_monitoring.id_monitoring', $id_monitoring);
					$where .= "AND a.id_monitoring = `".$id_monitoring."`";
				}
				if($active !== NULL){
					$this->db->where_in('tbl_cctv_monitoring.na', $active);
					$this->db->where_in('tbl_cctv_monitoring.del', 'n');
					$where .= " AND a.del = `n` AND a.na IN (`".implode('`,`', $active)."`) ";								
				}
				if ($filtertahun !== NULL) {
					$this->db->where('tbl_cctv_monitoring.year', $filtertahun);
					if($monthMin1=="y"){
						$filtertahunMin1 = $filtertahun - 1;
						$m 		= date('m');
						$lastm	= date("t");
						// $mmin1 	= date("m", strtotime("- $lastm days"));
						$mmin1 	= date("m", strtotime("-1 months"));
						if($mmin1 > $m ){
							$filtertahunMin1 = $filtertahun - 1;
						} else {
							$filtertahunMin1 = $filtertahun;
						}
						$where .= "AND( (a.year = `".$filtertahunMin1."` AND a.month =`".$mmin1."` )";
						$where .= "OR (a.year = `".$filtertahun."` AND a.month =`".$m."` ) )";
					} else {
						$where .= "AND a.year = `".$filtertahun."`";
					}
				}
				if ($filterplant !== NULL) {
					$this->db->where('tbl_cctv_monitoring.plant', $filterplant);
					$where .= "AND a.plant = `".$filterplant."`";
				}
				if ($filterbulan !== NULL) {
					$this->db->where('tbl_cctv_monitoring.month', $filterbulan);
					$where .= "AND a.month = `".$filterbulan."`";
				}
	        }
			
			
			$this->db->order_by('tbl_cctv_monitoring.dot ASC');
			// $where .= " ORDER BY tbl_cctv_monitoring.dot ASC";
			// $where = "";
			 // echo $where;
			  
			if($monitoringtypedetail == 'all') {
				$query = $this->db->query("EXEC SP_CCTV_ShowMonitoring '1',NULL,NULL,NULL, '".$where."'" );
			} else if($monitoringtypedetail == 'sum') {
				$query = $this->db->query("EXEC SP_CCTV_ShowMonitoring '3',NULL,NULL,NULL, '".$where."'" );	
			}else {
				$query = $this->db->query("EXEC SP_CCTV_ShowMonitoring '2',NULL,NULL,NULL, '".$where."'" );	
			}
			
			
			//$query  = $this->db->get();
			$result = $query->result();

			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		function get_data_achv($conn = NULL, $type = NULL, $pabrik = NULL , $tahun = NULL, $bulan = NULL , $minggu = NULL, $nik = NULL  ) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();
			$hasil = "";
			if($type == 'triger'){
				$where_del	= " year = '".$tahun."' AND plant = '".$pabrik."' AND month = '".$bulan."' AND week = '".$minggu."' ";
				$delete 	= $this->db->query("DELETE tbl_cctv_achievement  WHERE ".$where_del."" );
				$where = "AND a.year = `".$tahun."` AND a.plant = `".$pabrik."` AND a.month = `".$bulan."` AND a.week = `".$minggu."` ";
				$query = $this->db->query("EXEC SP_CCTV_ShowMonitoring '4',NULL,'".$nik."',NULL, '".$where."'" );	
				$hasil .= $pabrik; 
			} else {
				$query = $this->db->query('SELECT WERKS FROM SAPSYNC.dbo.ZDMMSPLANT');
				$result = $query->result();

				$var_pabrik = array_map(function($dt){
					return $dt->WERKS;
				}, $result); //array('ABL1','DWJ1','DWJ2','KUT1','KMP1','KPT1','KPR1','KSP1','KWI1','KJP1','NKP1','NSI1','PSU1','TSS1');
				$hasil = "";
				if($tahun != NULL ){
					$tahun = $tahun;
				} else {
					$tahun = date("Y");
				}
				$where_del	= " year = '".$tahun."' ";
				$delete 	= $this->db->query("DELETE FROM tbl_cctv_achievement WHERE ".$where_del."" );
				for($i=0;$i<count($var_pabrik);$i++){
					$where = "AND a.year = `".$tahun."` AND a.plant = `".$var_pabrik[$i]."`";
					$query = $this->db->query("EXEC SP_CCTV_ShowMonitoring '4',NULL,'1',NULL, '".$where."'" );	
					$hasil .= $var_pabrik[$i]; 
				}
			}
			$result = $hasil;
			return $result;	
   
   
		}

		/*================================Monitoring CCTV====================================*/
		function get_week($conn = NULL, $date = NULL, $display= NULL, $month=NULL, $year=NULL) {
			if ($conn == 'SAPSYNC')
				$this->general->connectDbDefault();

			$where = "";
			if($display !== NULL){
				$this->db->select("ZKISSTT_0138.".$display);	
			} else {
				$this->db->select('ZKISSTT_0138.*');
			}
			
			$this->db->from('ZKISSTT_0138');
			// $this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant = tbl_cctv_monitoring.plant', 'inner');
			
			
        	if ($date !== NULL) {
				$this->db->where('ZKISSTT_0138.ACTDT', $date);
				$where .= "AND ZKISSTT_0138.ACTDT = '".$date."'";
			}
			if ($month !== NULL) {
				$this->db->where('month(ZKISSTT_0138.ACTDT)', $month);
				// $where .= "AND ZKISSTT_0138.ACTDT = '".$date."'";
			}
			if ($year !== NULL) {
				$this->db->where('year(ZKISSTT_0138.ACTDT)', $year);
				// $where .= "AND ZKISSTT_0138.ACTDT = '".$date."'";
			}
			

			if($display !== NULL){
				$this->db->where(" ZKISSTT_0138.WKMNT <= portal.dbo.GetWeekendDaysCount(CONVERT(DATE, CAST(YEAR(ZKISSTT_0138.ACTDT) as varchar(4))+'-'+ CAST(MONTH(ZKISSTT_0138.ACTDT) as varchar(2))+'-01')) ");
				$this->db->group_by('ZKISSTT_0138.'.$display);
				$this->db->order_by('ZKISSTT_0138.'.$display.' ASC');	
			} else {
				$this->db->order_by('ZKISSTT_0138.ACTDT ASC');
			}

			$query  = $this->db->get();
			
			if($display !== NULL){
				$result = $query->result();	
			} else {
				$result = $query->row();
			}
			
			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		/*================================report achv CCTV====================================*/
		function get_data_monitoring_achv($conn = NULL, $year = NULL, $distinct = NULL, $active = 'n', $plant=NULL, $month=NULL) {
			if ($conn !== NULL)
				$this->general->connectDbPortal();

			if ($distinct !== null) {
				$this->db->select('distinct (tbl_cctv_achievement.'.$distinct.')');
			} else if($distinct == null){
				$this->db->select('tbl_cctv_achievement.*,
						(select max(x.countmax_tran) from tbl_cctv_achievement x 
						where x.plant=tbl_cctv_achievement.plant AND x.year = tbl_cctv_achievement.year 
							AND x.month = tbl_cctv_achievement.month AND x.na=\'n\'
						) maxtran
					');

			}
			
			$this->db->from('tbl_cctv_achievement');			
        	if ($year !== NULL) {
				$this->db->where_in('tbl_cctv_achievement.year', $year);
			}			
			if($active !== NULL){
				$this->db->where_in('tbl_cctv_achievement.na', $active);													
			}			
			if($plant !== NULL){
				$this->db->where_in('tbl_cctv_achievement.plant', $plant);													
			}
			if($month !== NULL){
				$this->db->where_in('tbl_cctv_achievement.month', $month);													
			}
			$this->db->where_in('tbl_cctv_achievement.del', 'n');
							   
															
	   
			$query  = $this->db->get();
			$result = $query->result();
			if ($conn !== NULL)
				$this->general->closeDb();
			return $result;
		}

		// delete
		function delete_data_cctv($conn=NULL,$plant=NULL,$tahun=NULL,$bulan=NULL,$minggu=NULL){
			
			$this->db->where('plant', $plant);
			$this->db->where('year', $tahun);
			$this->db->where('month', $bulan);
			$this->db->where('week', $minggu);
			$this->db->delete('tbl_cctv_achievement');

			$this->db->where('plant', $plant);
			$this->db->where('year', $tahun);
			$this->db->where('month', $bulan);
			$this->db->where('week', $minggu);
			$this->db->delete('tbl_cctv_monitoring');

			return "success";
		}


	}

?>
