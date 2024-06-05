<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application    : PCS (Production Cost Simulation)
    @author 		: Akhmad Syaiful Yamang (8347)
    @contributor	:
                1. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                2. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                etc.
    */

	class Dcalculatepcs extends CI_Model {
		function simulate_data($plant = NULL, $month = NULL, $year = NULL, $qty_prod_sim = NULL, $lwbp_sim = NULL, $wbp_sim = NULL, $grouping = NULL) {
			$this->general->connectDbPortalLive();
			define('DB_PORTAL_TES', 'portal');
			define('DB_DEFAULT_TES', 'SAPSYNC');
            $grouping = ($grouping == 'standart' ? 'grup1' : 'grup2');
			$query  = $this->db->query("DECLARE @qty_prod_sim INT,
											@qty_prod_sim_ton INT,
											@historical_count INT,
											@historical INT,
											@lwbp_sim INT,
											@wbp_sim INT,
											@norma_output NUMERIC(18,2),
											@month VARCHAR(2),
											@year VARCHAR(4),
											@plant VARCHAR(5)
											
										SET @qty_prod_sim = ".($qty_prod_sim*1000)."
										SET @qty_prod_sim_ton = ".($qty_prod_sim)."
										SET @historical_count = (SELECT COUNT(*)
																   FROM [".DB_PORTAL_TES."].dbo.tbl_pcs_setting_historical_backward as tbl_pcs_setting_historical_backward)
										SET @historical = (SELECT tbl_pcs_setting_historical_backward.[value]
															 FROM [".DB_PORTAL_TES."].dbo.tbl_pcs_setting_historical_backward as tbl_pcs_setting_historical_backward)
										SET @lwbp_sim = ".$lwbp_sim."
										SET @wbp_sim = ".$wbp_sim."
										SET @month = '".$month."'
										SET @year = '".$year."'
										SET @plant = '".$plant."'
										SET @norma_output = (SELECT tbl_pcs_mlembur.norma
																				   FROM [".DB_PORTAL_TES."].dbo.tbl_pcs_mlembur as tbl_pcs_mlembur
																				  WHERE tbl_pcs_mlembur.kode_pabrik = @plant)
										
									
										SELECT X.grup1,
											   X.grup2,
											   X.SAKNR,
											   X.GLTXT,
											   X.jns_formula,
											   X.norma,
											   X.COA,
											   X.COA_perKG,
											   X.jumlah,
											   X.satuan_jumlah
										INTO #tbl_pcs_result
										FROM 
										(
                                        SELECT DISTINCT ZDMFICO04.COGRP as grup1,
                                                        ISNULL(tbl_pcs_mpegrup.nama_grup, 'Others') as grup2,
                                                        ZDMFICO04.SAKNR,
                                                        ZDMFICO04.GLTXT,
                                                        tbl_pcs_mjenis.jns_formula,
                                                        tbl_pcs_mnorma.norma,
                                                        CONVERT(VARCHAR,
                                                          CONVERT(MONEY, 
                                                            CASE
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Last Month' 
                                                                      THEN (SELECT ZDMFICO04_2.AKTUAL_MTD
                                                                              FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                             WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                               AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                               AND ZDMFICO04_2.GSBER = @plant
                                                                               --AND ZDMFICO04_2.GJAHR = @year
                                                                               --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                               AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Budget'
                                                                      THEN (SELECT ZDMFICO04_2.TARGET_MTD * 1000000
                                                                              FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                             WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                               AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                               AND ZDMFICO04_2.GSBER = @plant
                                                                               AND ZDMFICO04_2.GJAHR = @year
                                                                               AND ZDMFICO04_2.MONAT = @month)
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Proporsional 1'
                                                                      THEN CASE @historical_count                           
                                                                              WHEN 0 THEN ( (SELECT ZDMFICO04_2.AKTUAL_YTD
                                                                                               FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                              WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                                AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                                AND ZDMFICO04_2.GSBER = @plant
                                                                                                AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                                             /
                                                                                             (SELECT SUM((COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0)))
                                                                                                FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                               WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                 AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = @year
                                                                                                 AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS <= MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                             * @qty_prod_sim 
                                                                                          )
                                                                              ELSE (SELECT (SUM(ZDMFICO04_2.AKTUAL_MTD) / SUM(COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0))) * @qty_prod_sim
                                                                                      FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                     INNER JOIN [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602 ON ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                              AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GJAHR
                                                                                                                                              AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.MONAT
                                                                                     WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                       AND ZDMFICO04_2.GSBER = @plant
                                                                                       AND CONVERT(DATE, ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR) BETWEEN DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)) AND DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                           END
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Proporsional 2'
                                                                      THEN CASE @historical_count                           
                                                                              WHEN 0 THEN ( (SELECT ZDMFICO04_2.AKTUAL_YTD
                                                                                               FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                              WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                                AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                                AND ZDMFICO04_2.GSBER = @plant
                                                                                                AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                                             /
                                                                                             (SELECT SUM(ZKISSTT_006602.QTYSALES)
                                                                                                FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                               WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                 AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = @year
                                                                                                 AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS <= MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                             * @qty_prod_sim 
                                                                                          )
                                                                              ELSE (SELECT (SUM(ZDMFICO04_2.AKTUAL_MTD) / SUM(ZKISSTT_006602.QTYSALES)) * @qty_prod_sim
                                                                                      FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                     INNER JOIN [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602 ON ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                              AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GJAHR
                                                                                                                                              AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.MONAT
                                                                                     WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                       AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                       AND ZDMFICO04_2.GSBER = @plant
                                                                                       AND CONVERT(DATE, ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR) BETWEEN DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)) AND DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                           END
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Norma'
                                                                      THEN CASE
                                                                              WHEN tbl_pcs_mnorma.norma = 'Listrik LWBP'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mlwbp.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(@lwbp_sim+@wbp_sim))
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mlwbp as tbl_pcs_mlwbp ON tbl_pcs_mlwbp.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Cangkang Sawit'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mcangkang.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(SELECT SUM(ZDMFACOP03.CKGOU)
                                                                                                                                                                              FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP03 as ZDMFACOP03
                                                                                                                                                                             WHERE ZDMFACOP03.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                                               AND YEAR(ZDMFACOP03.BUDAT) = ZDMFICO04_2.GJAHR
                                                                                                                                                                               AND MONTH(ZDMFACOP03.BUDAT) = ZDMFICO04_2.MONAT ))
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mcangkang as tbl_pcs_mcangkang ON tbl_pcs_mcangkang.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Solar Genset'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mgenset.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(SELECT SUM(ZDMFACOP03.SOLGE+ZDMFACOP03.SOLDR+ZDMFACOP03.SOLOT)
                                                                                                                                                                            FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP03 as ZDMFACOP03
                                                                                                                                                                           WHERE ZDMFACOP03.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                                             AND YEAR(ZDMFACOP03.BUDAT) = ZDMFICO04_2.GJAHR
                                                                                                                                                                             AND MONTH(ZDMFACOP03.BUDAT) = ZDMFICO04_2.MONAT ))
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mgenset as tbl_pcs_mgenset ON tbl_pcs_mgenset.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Solar Drier'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mdrier.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(SELECT SUM(ZDMFACOP03.SOLGE+ZDMFACOP03.SOLDR+ZDMFACOP03.SOLOT)
                                                                                                                                                                           FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP03 as ZDMFACOP03
                                                                                                                                                                          WHERE ZDMFACOP03.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                                            AND YEAR(ZDMFACOP03.BUDAT) = ZDMFICO04_2.GJAHR
                                                                                                                                                                            AND MONTH(ZDMFACOP03.BUDAT) = ZDMFICO04_2.MONAT ))
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mdrier as tbl_pcs_mdrier ON tbl_pcs_mdrier.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Solar Lain-lain'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mlain.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(SELECT SUM(ZDMFACOP03.SOLGE+ZDMFACOP03.SOLDR+ZDMFACOP03.SOLOT)
                                                                                                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP03 as ZDMFACOP03
                                                                                                                                                                         WHERE ZDMFACOP03.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                                           AND YEAR(ZDMFACOP03.BUDAT) = ZDMFICO04_2.GJAHR
                                                                                                                                                                           AND MONTH(ZDMFACOP03.BUDAT) = ZDMFICO04_2.MONAT ))
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mlain as tbl_pcs_mlain ON tbl_pcs_mlain.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Lembur'
                                                                                  THEN CASE @historical_count
                                                                                          WHEN 0 THEN (SELECT ((SELECT ZDMFICO04_2.AKTUAL_YTD
                                                                                                                  FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                                                 WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                                                   AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                                                   AND ZDMFICO04_2.GSBER = @plant
                                                                                                                   AND ZDMFICO04_2.GJAHR = @year
                                                                                                                   AND ZDMFICO04_2.MONAT = @month) / (SELECT SUM((COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0)))
                                                                                                                                                        FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                                                                                       WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                                                                         AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = @year
                                                                                                                                                         AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS <= @month)
                                                                                                              ) * (
                                                                                                                    (
                                                                                                                      (SELECT SUM((COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0)))
                                                                                                                         FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                                                        WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                                          AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = @year
                                                                                                                          AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS <= @month) / 
                                                                                                                      (SELECT SUM(ZDMFACOP07.ISM01)
                                                                                                                         FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP07 as ZDMFACOP07 
                                                                                                                        WHERE ZDMFACOP07.WERKS = @plant
                                                                                                                          AND YEAR(ZDMFACOP07.BUDAT) = @year
                                                                                                                          AND MONTH(ZDMFACOP07.BUDAT) <= @month
                                                                                                                          AND ZDMFACOP07.ORIND = 2)
                                                                                                                    ) / @norma_output
                                                                                                                  ) * @qty_prod_sim_ton
                                                                                                                                                                             
                                                                                                     )
                                                                                          ELSE ((SELECT SUM(ZDMFICO04_2.AKTUAL_MTD) / SUM(COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0))
                                                                                                   FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                                  INNER JOIN [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602 ON ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                           AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GJAHR
                                                                                                                                                           AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.MONAT
                                                                                                  WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                                    AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                                    AND ZDMFICO04_2.GSBER = @plant
                                                                                                    AND CONVERT(DATE, ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR) BETWEEN DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)) AND DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))) *
                                                                                                (SELECT (CONVERT(DECIMAL(18,5),(SUM(COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0))) / (SELECT SUM(ZDMFACOP07.ISM01)
                                                                                                                                                                                                                 FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP07 as ZDMFACOP07
                                                                                                                                                                                                                WHERE ZDMFACOP07.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                                                                                                                                  AND CONVERT(VARCHAR(7), ZDMFACOP07.BUDAT, 120) BETWEEN CONVERT(VARCHAR(7), DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)), 120) AND CONVERT(VARCHAR(7), DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)), 120)
                                                                                                                                                                                                                  AND ZDMFACOP07.ORIND = 2)
                                                                                                         ) / @norma_output)
                                                                                                   FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                                  WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                    AND CONVERT(DATE, ZKISSTT_006602.MONAT +'/01/'+ ZKISSTT_006602.GJAHR) BETWEEN DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)) AND DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))
                                                                                                   )
                                                                                               ) *
                                                                                                @qty_prod_sim_ton
                                                                                        END
                                                                              ELSE 0
                                                                           END
                                                              ELSE 0
                                                            END
                                                          )
                                                        ) as COA,
                                                        CONVERT(VARCHAR,
                                                          CONVERT(MONEY, 
                                                            CASE
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Last Month' 
                                                                      THEN (SELECT ZDMFICO04_2.AKTUAL_MTD / @qty_prod_sim
                                                                              FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                             WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                               AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                               AND ZDMFICO04_2.GSBER = @plant
                                                                               --AND ZDMFICO04_2.GJAHR = @year
                                                                               --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                               AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Budget'
                                                                      THEN (SELECT (ZDMFICO04_2.TARGET_MTD * 1000000) / @qty_prod_sim
                                                                              FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                             WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                               AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                               AND ZDMFICO04_2.GSBER = @plant
                                                                               AND ZDMFICO04_2.GJAHR = @year
                                                                               AND ZDMFICO04_2.MONAT = @month)
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Proporsional 1'
                                                                      THEN CASE @historical_count                           
                                                                              WHEN 0 THEN ( (SELECT ZDMFICO04_2.AKTUAL_YTD
                                                                                               FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                              WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                                AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                                AND ZDMFICO04_2.GSBER = @plant
                                                                                                AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                                             /
                                                                                             (SELECT SUM((COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0)))
                                                                                                FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                               WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                 AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = @year
                                                                                                 AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS <= MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                          )
                                                                              ELSE (SELECT (SUM(ZDMFICO04_2.AKTUAL_MTD) / SUM(COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0)))
                                                                                      FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                     INNER JOIN [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602 ON ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                              AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GJAHR
                                                                                                                                              AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.MONAT
                                                                                     WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                       AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                       AND ZDMFICO04_2.GSBER = @plant
                                                                                       AND CONVERT(DATE, ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR) BETWEEN DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)) AND DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                           END
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Proporsional 2'
                                                                      THEN CASE @historical_count                           
                                                                              WHEN 0 THEN ( (SELECT ZDMFICO04_2.AKTUAL_YTD
                                                                                               FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                              WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                                AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                                AND ZDMFICO04_2.GSBER = @plant
                                                                                                AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                                             /
                                                                                             (SELECT SUM(ZKISSTT_006602.QTYSALES)
                                                                                                FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                               WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                 AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = @year
                                                                                                 AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS <= MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))) 
                                                                                          )
                                                                              ELSE (SELECT (SUM(ZDMFICO04_2.AKTUAL_MTD) / SUM(ZKISSTT_006602.QTYSALES))
                                                                                      FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                     INNER JOIN [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602 ON ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                              AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GJAHR
                                                                                                                                              AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.MONAT
                                                                                     WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                       AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                       AND ZDMFICO04_2.GSBER = @plant
                                                                                       AND CONVERT(DATE, ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR) BETWEEN DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)) AND DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                           END
                                                              WHEN tbl_pcs_mjenis.jns_formula = 'Norma'
                                                                      THEN CASE
                                                                              WHEN tbl_pcs_mnorma.norma = 'Listrik LWBP'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mlwbp.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(@lwbp_sim+@wbp_sim)) / @qty_prod_sim
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mlwbp as tbl_pcs_mlwbp ON tbl_pcs_mlwbp.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Cangkang Sawit'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mcangkang.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(SELECT SUM(ZDMFACOP03.CKGOU)
                                                                                                                                                                              FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP03 as ZDMFACOP03
                                                                                                                                                                             WHERE ZDMFACOP03.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                                               AND YEAR(ZDMFACOP03.BUDAT) = ZDMFICO04_2.GJAHR
                                                                                                                                                                               AND MONTH(ZDMFACOP03.BUDAT) = ZDMFICO04_2.MONAT )) / @qty_prod_sim
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mcangkang as tbl_pcs_mcangkang ON tbl_pcs_mcangkang.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Solar Genset'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mgenset.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(SELECT SUM(ZDMFACOP03.SOLGE+ZDMFACOP03.SOLDR+ZDMFACOP03.SOLOT)
                                                                                                                                                                            FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP03 as ZDMFACOP03
                                                                                                                                                                           WHERE ZDMFACOP03.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                                             AND YEAR(ZDMFACOP03.BUDAT) = ZDMFICO04_2.GJAHR
                                                                                                                                                                             AND MONTH(ZDMFACOP03.BUDAT) = ZDMFICO04_2.MONAT )) / @qty_prod_sim
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mgenset as tbl_pcs_mgenset ON tbl_pcs_mgenset.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Solar Drier'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mdrier.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(SELECT SUM(ZDMFACOP03.SOLGE+ZDMFACOP03.SOLDR+ZDMFACOP03.SOLOT)
                                                                                                                                                                           FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP03 as ZDMFACOP03
                                                                                                                                                                          WHERE ZDMFACOP03.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                                            AND YEAR(ZDMFACOP03.BUDAT) = ZDMFICO04_2.GJAHR
                                                                                                                                                                            AND MONTH(ZDMFACOP03.BUDAT) = ZDMFICO04_2.MONAT )) / @qty_prod_sim
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mdrier as tbl_pcs_mdrier ON tbl_pcs_mdrier.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Solar Lain-lain'
                                                                                  THEN (SELECT ISNULL(tbl_pcs_mlain.norma,0)*@qty_prod_sim_ton*(ZDMFICO04_2.AKTUAL_MTD/(SELECT SUM(ZDMFACOP03.SOLGE+ZDMFACOP03.SOLDR+ZDMFACOP03.SOLOT)
                                                                                                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP03 as ZDMFACOP03
                                                                                                                                                                         WHERE ZDMFACOP03.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                                           AND YEAR(ZDMFACOP03.BUDAT) = ZDMFICO04_2.GJAHR
                                                                                                                                                                           AND MONTH(ZDMFACOP03.BUDAT) = ZDMFICO04_2.MONAT )) / @qty_prod_sim
                                                                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mlain as tbl_pcs_mlain ON tbl_pcs_mlain.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                         WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                           AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                           AND ZDMFICO04_2.GSBER = @plant
                                                                                           --AND ZDMFICO04_2.GJAHR = @year
                                                                                           --AND ZDMFICO04_2.MONAT = MONTH(DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))))
                                                                                           AND ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR = DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)))
                                                                              WHEN tbl_pcs_mnorma.norma = 'Lembur'
                                                                                  THEN CASE @historical_count
                                                                                          WHEN 0 THEN (SELECT ((SELECT ZDMFICO04_2.AKTUAL_YTD
                                                                                                                  FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                                                 WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                                                   AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                                                   AND ZDMFICO04_2.GSBER = @plant
                                                                                                                   AND ZDMFICO04_2.GJAHR = @year
                                                                                                                   AND ZDMFICO04_2.MONAT = @month) / (SELECT SUM((COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0)))
                                                                                                                                                        FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                                                                                       WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                                                                         AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = @year
                                                                                                                                                         AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS <= @month)
                                                                                                              ) * (
                                                                                                                    (
                                                                                                                      (SELECT SUM((COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0)))
                                                                                                                         FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                                                        WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                                          AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = @year
                                                                                                                          AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS <= @month) / 
                                                                                                                      (SELECT SUM(ZDMFACOP07.ISM01)
                                                                                                                         FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP07 as ZDMFACOP07 
                                                                                                                        WHERE ZDMFACOP07.WERKS = @plant
                                                                                                                          AND YEAR(ZDMFACOP07.BUDAT) = @year
                                                                                                                          AND MONTH(ZDMFACOP07.BUDAT) <= @month
                                                                                                                          AND ZDMFACOP07.ORIND = 2)
                                                                                                                    ) / @norma_output
                                                                                                                  ) * @qty_prod_sim_ton/ @qty_prod_sim
                                                                                                                                                                             
                                                                                                     )
                                                                                          ELSE ((SELECT SUM(ZDMFICO04_2.AKTUAL_MTD) / SUM(COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0))
                                                                                                   FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04_2
                                                                                                  INNER JOIN [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602 ON ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GSBER
                                                                                                                                                           AND ZKISSTT_006602.GJAHR COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.GJAHR
                                                                                                                                                           AND ZKISSTT_006602.MONAT COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04_2.MONAT
                                                                                                  WHERE ZDMFICO04_2.SAKNR = ZDMFICO04.SAKNR
                                                                                                    AND ZDMFICO04_2.COGRP = ZDMFICO04.COGRP
                                                                                                    AND ZDMFICO04_2.GSBER = @plant
                                                                                                    AND CONVERT(DATE, ZDMFICO04_2.MONAT +'/01/'+ ZDMFICO04_2.GJAHR) BETWEEN DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)) AND DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))) *
                                                                                                (SELECT (CONVERT(DECIMAL(18,5),(SUM(COALESCE(ZKISSTT_006602.QTYSIR,0)+COALESCE(ZKISSTT_006602.QTYREPRO,0))) / (SELECT SUM(ZDMFACOP07.ISM01)
                                                                                                                                                                                                                 FROM [".DB_DEFAULT_TES."].dbo.ZDMFACOP07 as ZDMFACOP07
                                                                                                                                                                                                                WHERE ZDMFACOP07.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                                                                                                                                  AND CONVERT(VARCHAR(7), ZDMFACOP07.BUDAT, 120) BETWEEN CONVERT(VARCHAR(7), DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)), 120) AND CONVERT(VARCHAR(7), DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year)), 120)
                                                                                                                                                                                                                  AND ZDMFACOP07.ORIND = 2)
                                                                                                         ) / @norma_output)
                                                                                                   FROM [".DB_DEFAULT_TES."].dbo.ZKISSTT_006602 as ZKISSTT_006602
                                                                                                  WHERE ZKISSTT_006602.WERKS COLLATE SQL_Latin1_General_CP1_CS_AS = @plant
                                                                                                    AND CONVERT(DATE, ZKISSTT_006602.MONAT +'/01/'+ ZKISSTT_006602.GJAHR) BETWEEN DATEADD(MONTH, -@historical, CONVERT(date, @month+'/01/'+@year)) AND DATEADD(MONTH, -1, CONVERT(date, @month+'/01/'+@year))
                                                                                                   )
                                                                                               ) *
                                                                                                @qty_prod_sim_ton/ @qty_prod_sim
                                                                                        END
                                                                              ELSE 0
                                                                           END
                                                              ELSE 0
                                                            END
                                                          )
                                                        ) as COA_perKG,
                                                        (CASE 
                                                                   WHEN tbl_pcs_mnorma.norma IS NOT NULL 
                                                                    AND tbl_pcs_mnorma.norma = 'Listrik LWBP'
                                                                      THEN @qty_prod_sim_ton * (SELECT tbl_pcs_mlwbp.norma
                                                                                                  FROM [".DB_PORTAL_TES."].dbo.tbl_pcs_mlwbp as tbl_pcs_mlwbp
                                                                                                 WHERE tbl_pcs_mlwbp.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = @plant)
                                                                   WHEN tbl_pcs_mnorma.norma IS NOT NULL 
                                                                    AND tbl_pcs_mnorma.norma = 'Listrik WBP'
                                                                      THEN @qty_prod_sim_ton * (SELECT tbl_pcs_mwbp.norma
                                                                                                  FROM [".DB_PORTAL_TES."].dbo.tbl_pcs_mwbp as tbl_pcs_mwbp
                                                                                                 WHERE tbl_pcs_mwbp.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = @plant)
                                                                   WHEN tbl_pcs_mnorma.norma IS NOT NULL 
                                                                    AND tbl_pcs_mnorma.norma = 'Cangkang Sawit'
                                                                      THEN @qty_prod_sim_ton * (SELECT tbl_pcs_mcangkang.norma
                                                                                                  FROM [".DB_PORTAL_TES."].dbo.tbl_pcs_mcangkang as tbl_pcs_mcangkang
                                                                                                 WHERE tbl_pcs_mcangkang.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = @plant)
                                                                   WHEN tbl_pcs_mnorma.norma IS NOT NULL 
                                                                    AND tbl_pcs_mnorma.norma = 'Solar Genset'
                                                                      THEN @qty_prod_sim_ton * (SELECT tbl_pcs_mgenset.norma
                                                                                                  FROM [".DB_PORTAL_TES."].dbo.tbl_pcs_mgenset as tbl_pcs_mgenset
                                                                                                 WHERE tbl_pcs_mgenset.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = @plant)
                                                                   WHEN tbl_pcs_mnorma.norma IS NOT NULL 
                                                                    AND tbl_pcs_mnorma.norma = 'Solar Drier'
                                                                      THEN @qty_prod_sim_ton * (SELECT tbl_pcs_mdrier.norma
                                                                                                  FROM [".DB_PORTAL_TES."].dbo.tbl_pcs_mdrier as tbl_pcs_mdrier
                                                                                                 WHERE tbl_pcs_mdrier.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = @plant)
                                                                   WHEN tbl_pcs_mnorma.norma IS NOT NULL 
                                                                    AND tbl_pcs_mnorma.norma = 'Solar Lain-lain'
                                                                      THEN @qty_prod_sim_ton * (SELECT tbl_pcs_mlain.norma
                                                                                                  FROM [".DB_PORTAL_TES."].dbo.tbl_pcs_mlain as tbl_pcs_mlain
                                                                                                 WHERE tbl_pcs_mlain.kode_pabrik COLLATE SQL_Latin1_General_CP1_CS_AS = @plant)
                                                                   ELSE 0
                                                                END) as jumlah,
                                                        (CASE 
                                                                   WHEN tbl_pcs_mnorma.norma IS NOT NULL 
                                                                    AND tbl_pcs_mnorma.norma LIKE 'Listrik%'
                                                                      THEN 'kWH'
                                                                   WHEN tbl_pcs_mnorma.norma IS NOT NULL 
                                                                    AND tbl_pcs_mnorma.norma = 'Cangkang Sawit'
                                                                      THEN 'Kg'
                                                                   WHEN tbl_pcs_mnorma.norma IS NOT NULL 
                                                                    AND tbl_pcs_mnorma.norma LIKE 'Solar%'
                                                                      THEN 'Liter'
                                                                   ELSE ''
                                                                END) as satuan_jumlah
                                          FROM [".DB_DEFAULT_TES."].dbo.ZDMFICO04 as ZDMFICO04
                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_setting_formcoa as tbl_pcs_setting_formcoa 
                                            ON tbl_pcs_setting_formcoa.saknr COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04.SAKNR
                                         INNER JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mjenis as tbl_pcs_mjenis
                                            ON tbl_pcs_mjenis.id_mjenis = tbl_pcs_setting_formcoa.id_mjenis
                                          LEFT JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mnorma as tbl_pcs_mnorma
                                            ON tbl_pcs_mnorma.id_mnorma = tbl_pcs_setting_formcoa.id_mnorma
                                          LEFT JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_setting_pecoa as tbl_pcs_setting_pecoa
                                                 ON tbl_pcs_setting_pecoa.saknr COLLATE SQL_Latin1_General_CP1_CS_AS = ZDMFICO04.SAKNR
                                          LEFT JOIN [".DB_PORTAL_TES."].dbo.tbl_pcs_mpegrup as tbl_pcs_mpegrup
                                                 ON tbl_pcs_mpegrup.id_mpegrup = tbl_pcs_setting_pecoa.id_mpegrup
                                         WHERE ZDMFICO04.GSBER = @plant
										) X
										ORDER BY X.$grouping,
												 X.SAKNR 
												 
										SELECT #tbl_pcs_result.$grouping, 
											   SUM(CONVERT(MONEY,#tbl_pcs_result.COA)) as summ_COA,
											   SUM(CONVERT(MONEY,#tbl_pcs_result.COA_perKG)) as summ_COA_perKG
										  INTO #tbl_pcs_result_summary
										  FROM #tbl_pcs_result
										 GROUP BY #tbl_pcs_result.$grouping
												  
										SELECT #tbl_pcs_result.*,
											   #tbl_pcs_result_summary.summ_COA,
											   #tbl_pcs_result_summary.summ_COA_perKG
										  FROM #tbl_pcs_result
										 INNER JOIN #tbl_pcs_result_summary ON #tbl_pcs_result_summary.$grouping = #tbl_pcs_result.$grouping
												 
										DROP TABLE #tbl_pcs_result,#tbl_pcs_result_summary");
			$result = $query->result();
			$this->general->closeDb();
			return $result;
		}
	}

?>
