<?php
/*
        @application  : 
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 04-Dec-18
        @contributor  :
              1. Lukman Hakim (7143) 17.01.2019
                 penambahan RFC untuk master kode material
              2. Lukman Hakim (7143) 05.03.2019
                 Penambahan RFC untuk master tbl_pi_rfc_bom_engineering
              3. Lukman Hakim (7143) 16.02.2021
                 Penambahan RFC master vendor
              etc.
    */

include_once APPPATH . "modules/data/controllers/BaseControllers.php";

class Rfc extends BaseControllers
{
	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL)
	{
		switch ($param) {
			case 'bom_material':
				/*
                     * Function Module : Z_RFC_WORKSHOP_MAT_BOM_READ
                     * Used by :
                     * 		1. Nusira Workshop
                     */
				$this->get_workshop_mat_bom();
				break;
			case 'kode_material':
				/*
                     * Function Module : Z_RFC_MASTERITEM
                     * Used by :
                     * 		1. Material Code FO
                     */
				$this->get_master_kode_material();
				// $this->get_uom();
				// $this->get_matkl();
				break;
			case 'spk':
				/*
                     * Function Module : Z_RFC_MATDOC_WORKSHOP_GET
                     * Used by :
                     * 		1. Nusira Workshop(SPK)
                     */
				$this->get_master_spk();
				break;
			case 'ongkir':
				/*
					 * Function Module : Z_RFC_MASTERITEM
					 * Used by :
					 * 		1. Nusira Workshop(SPK)
					 */
				$this->get_master_ongkir();
				break;
			case 'sapbgjob':
				$this->get_sapbgjob();
				break;
			case 'spd':
				/*
                     * Function Module : Z_RFC_MASTERITEM
                     * Used by :
                     *      1. Nusira Workshop(SPK)
                     */
				$this->get_master_spd();
				break;
			case 'document_number':
				/*
							 * Function Module : Z_RFC_MASTERITEM
							 * Used by :
							 *      1. Nusira Workshop(SPK)
							 */
				$this->get_document_number();
				break;
			case 'vendor':
				/*
                     * Function Module : Z_RFC_P2P_SEARCH_VENDOR_BYNAME
                     * Used by :
                     * 		1. Kode Vendor
                     */
				// $this->get_master_kode_material();
				$nama_vendor  = (isset($_POST['nama_vendor']) ? $_POST['nama_vendor'] : NULL);
				$this->get_vendor($nama_vendor);
				break;
			case 'plantation_document_number':
				/*
					* Function Module : Z_PLINTERFACE_STATUS
					* Used by :
					*      1. Plantation
					*/
				$this->get_plantation_document_number();
				break;
		}
	}

	public function set($param = NULL, $param2 = NULL)
	{
		switch ($param) {
			case 'kode_material':
				/*
                     * Function Module : Z_RFC_CREATEMATERIAL
                     * Used by :
                     * 		1. Material Code FO
                     */
				$this->set_kode_material();
				break;
			case 'extend_kode_material':
				/*
                     * Function Module : Z_RFC_CREATEMATERIAL
                     * Used by :
                     * 		1. Material Code FO
                     */
				$this->extend_kode_material();
				break;
			case 'change_kode_material':
				/*
                     * Function Module : Z_RFC_CREATEMATERIAL
                     * Used by :
                     * 		1. Material Code FO
                     */
				$this->change_kode_material();
				break;
			case 'cek_kode_material':
				/*
                     * Function Module : Z_RFC_CHECK_MATERIALCODE
                     * Used by :
                     * 		1. Material Code FO
                     */
				$this->cek_kode_material();
				break;
			case 'sicom':
				/*
                     * Function Module : Z_RFC_SICOM_PRICE
                     * Used by :
                     *      1. SICOM(SALES-MARKETING)
                     */
				$this->set_data_sicom();
				break;
			case 'pln':
				/*
                    * Function Module : Z_RFC_TAGIHAN_PLN
                    * Used by :
                    *      1. PLN Jurnal
                    */
				$this->set_data_tagihan_pln();
				break;
			case 'margin':
				/*
                     * Function Module : Z_RFC_UPD_SPOTSALES_MARGIN
                     * Used by :
                     * 		1. Similasi Penjualan Spot
                     */
				$this->set_data_margin();
				break;
			case 'spot':
				/*
                     * Function Module : Z_RFC_CREATE_SD_SPOTCONTRACT
                     * Used by :
                     * 		1. Simulasi Penjualan Spot
                     */
				// $this->set_data_spot(str_replace('-','/',$param2));	 
				if ($param2 != NULL) {
					$no_form = str_replace('-', '/', $param2);
					$no_form = $this->generate->kirana_decrypt($param2);
					// echo $no_form;
					// exit();
					$this->set_data_spot($no_form, 'y');
				} else {
					$this->load->model('dtransaksidata');
					$this->general->connectDbPortal();

					$data_simulate		= $this->dtransaksidata->get_data_simulate();
					foreach ($data_simulate as $dt) {
						$this->set_data_spot($dt->no_form);
					}
				}
				break;
			case 'create_vendor':
				/*
                     * Function Module : Z_RFC_P2P_CREATE_VENDOR
                     * Used by :
                     * 		1. Proc HO / Legal HO
                     */
				// $this->set_kode_material();
				$this->set_create_vendor();
				break;
			case 'extend_vendor':
				/*
                     * Function Module : Z_RFC_P2P_EXTEND_VENDOR
                     * Used by :
                     * 		1. Proc HO / Legal HO
                     */
				$this->set_extend_vendor();
				break;
			case 'plantation_po_ho':
				/*
						* Function Module : Z_RFC_PAS_PODATA_SUBMIT
						* Used by :
						* 		1. Plantation PO HO
						*/
				$this->set_plantation_po_ho();
				break;
			case 'plantation_ttg':
				/*
						* Function Module : Z_RFC_PAS_GRDATA_SUBMIT, Z_RFC_PAS_TTGDATA_SUBMIT
						* Used by :
						* 		1. Plantation GR/TTG
						*/
				$this->set_plantation_ttg();
				break;
			case 'plantation_gi':
				/*
						* Function Module : Z_RFC_PAS_BKBDATA_SUBMIT
						* Used by :
						* 		1. Plantation GI/BKB
						*/
				$this->set_plantation_gi();
				break;
			case 'plantation_transaction':
				/*
						* Function Module : Z_RFC_PAS_BKBDATA_SUBMIT
						* Used by :
						* 		1. Plantation All Transaction
						*/
				$this->set_plantation_transaction();
				break;
		}
	}

	/**********************************/
	/*			  private  			  */
	/**********************************/
	private function get_workshop_mat_bom()
	{
		$this->connectSAP("ERP_310"); 			//310
		// $this->connectSAP("ERP_KMTEMP");        //km_temp

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$datetime = date("Y-m-d H:i:s");

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result  = $this->data['sap']->callFunction(
				"Z_RFC_WORKSHOP_MAT_BOM_READ",
				array(
					array("TABLE", "T_BOMDATASLS", array()),
					array("TABLE", "T_BOMDATAENG", array()),
					array("EXPORT", "RETURN", array()),
				)
			);

			$code    = array();
			$message = array();

			//T_BOMDATASLS
			if (!empty($result["T_BOMDATASLS"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();

				foreach ($result["T_BOMDATASLS"] as $dt) {
					$data_row = array();
					// $data_batch[$key] = $value;
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							case 'VALTO':
								$data_row[$key] = $this->generate->regenerateDateFormat(ltrim(rtrim($value)));
								break;
							case 'MENGE':
								$data_row[$key] = floatval(str_replace(",", ".", ltrim(rtrim($value))));
								break;
							case 'MAKTX':
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))), ENT_COMPAT, "UTF-8");
								break;
							default:
								$data_row[$key] = ltrim(rtrim($value));
								break;
						}
					}

					if ($data_row['ITCAT'] == 'B')
						$data_row['IDNRK'] = '0';

					$data_batch[] = $data_row;
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_WORKSHOP_MAT_BOM_READ',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_rfc_bom ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

				$this->dgeneral->delete("tbl_pi_rfc_bom");
				$this->dgeneral->insert_batch("tbl_pi_rfc_bom", $data_batch);
			} else {
				$status = "although " . preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				if (!empty($result["RETURN"])) {
					$status = $result["RETURN"]["MESSAGE"];
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_WORKSHOP_MAT_BOM_READ',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => "Reading RFC : reading data FAILED [T_BOMDATASLS], " . $status . " ",
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			$code[]    = $data_row_log['log_code'];
			$message[] = $data_row_log['log_desc'];

			//T_BOMDATAENG
			if (!empty($result["T_BOMDATAENG"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_BOMDATAENG"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'MAKTX':
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))), ENT_COMPAT, "UTF-8");
								break;
							default:
								$data_row[$key] = ltrim(rtrim($value));
								break;
						}
					}

					$data_batch[] = $data_row;
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_WORKSHOP_MAT_BOM_READ',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_pi_rfc_bom_engineering ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

				$this->dgeneral->delete("tbl_pi_rfc_bom_engineering");
				$this->dgeneral->insert_batch("tbl_pi_rfc_bom_engineering", $data_batch);
			} else {
				$status = "although " . preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				if (!empty($result["RETURN"])) {
					$status = $result["RETURN"]["MESSAGE"];
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_WORKSHOP_MAT_BOM_READ',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => "Reading RFC : reading data FAILED [T_BOMDATAENG], " . $status . " ",
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			$code[]    = $data_row_log['log_code'];
			$message[] = $data_row_log['log_desc'];
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_WORKSHOP_MAT_BOM_READ',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Connecting : " . $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$code[]       = $data_row_log['log_code'];
			$message[]    = $data_row_log['log_desc'];
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = $data_row_log['log_desc'];
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Transaksi BOM berhasil";
			$sts = "OK";
			if (in_array('E', $code) === true) {
				$msg = implode(" | ", $message);
				$sts = "NotOK";
			}
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function get_master_kode_material()
	{
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$datetime = date("Y-m-d H:i:s");

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_MASTERITEM",
				array(
					array("TABLE", "T_MTART", array()),
					array("TABLE", "T_UOM", array()),
					array("TABLE", "T_MATKL", array()),
					array("TABLE", "T_BKLAS", array()),
					array("TABLE", "T_LGORT", array()),
					array("TABLE", "T_EKGRP", array()),
					array("TABLE", "T_DISPO", array()),
					array("TABLE", "T_LOT", array()),
					array("TABLE", "T_DIST", array()),
					array("TABLE", "T_DIV", array()),
					array("TABLE", "T_PROFIT", array()),
					array("TABLE", "T_MMRC", array()),
					array("TABLE", "T_COST", array()),
					array("TABLE", "T_ASSETCLASS", array()),
					array("TABLE", "T_COA", array()),
				)
			);

			//T_MTART
			if (!empty($result["T_MTART"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_MTART"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}

					$data_batch[] = $data_row;
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_mtart ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->delete("tbl_item_mtart");
				$this->dgeneral->insert_batch("tbl_item_mtart", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_UOM
			if (!empty($result["T_UOM"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_UOM"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_uom ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_uom");
				$this->dgeneral->insert_batch("tbl_item_uom", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_MATKL
			if (!empty($result["T_MATKL"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_MATKL"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_matkl ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_matkl");
				$this->dgeneral->insert_batch("tbl_item_matkl", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_BKLAS
			if (!empty($result["T_BKLAS"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_BKLAS"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_bklas ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_bklas");
				$this->dgeneral->insert_batch("tbl_item_bklas", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_LGORT
			if (!empty($result["T_LGORT"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_LGORT"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_lgort ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_lgort");
				$this->dgeneral->insert_batch("tbl_item_lgort", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_EKGRP
			if (!empty($result["T_EKGRP"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_EKGRP"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_ekgrp ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_ekgrp");
				$this->dgeneral->insert_batch("tbl_item_ekgrp", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_DISPO
			if (!empty($result["T_DISPO"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_DISPO"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_dispo ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_dispo");
				$this->dgeneral->insert_batch("tbl_item_dispo", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_LOT
			if (!empty($result["T_LOT"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_LOT"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_lot ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_lot");
				$this->dgeneral->insert_batch("tbl_item_lot", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_DIST
			if (!empty($result["T_DIST"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_DIST"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_dist ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_dist");
				$this->dgeneral->insert_batch("tbl_item_dist", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_DIV
			if (!empty($result["T_DIV"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_DIV"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_div ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_div");
				$this->dgeneral->insert_batch("tbl_item_div", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_PROFIT
			if (!empty($result["T_PROFIT"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_PROFIT"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_profit ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_profit");
				$this->dgeneral->insert_batch("tbl_item_profit", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_MMRC
			if (!empty($result["T_MMRC"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_MMRC"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_mmrc ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_mmrc");
				$this->dgeneral->insert_batch("tbl_item_mmrc", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_COST
			if (!empty($result["T_COST"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_COST"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_cost_center ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_cost_center");
				$this->dgeneral->insert_batch("tbl_item_cost_center", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_ASSETCLASS
			if (!empty($result["T_ASSETCLASS"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_ASSETCLASS"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_asset_class ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_asset_class");
				$this->dgeneral->insert_batch("tbl_item_asset_class", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_COA
			if (!empty($result["T_COA"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_COA"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							default:
								// $data_row[$key] = ltrim(rtrim($value));
								$data_row[$key] = htmlentities(str_replace(",", ".", ltrim(rtrim($value))));
								break;
						}
					}
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_item_coa ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->delete("tbl_item_coa");
				$this->dgeneral->insert_batch("tbl_item_coa", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_MASTERITEM',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = $data_row_log['log_desc'];
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Transaksi Master Item berhasil";
			if ($data_row_log['log_code'] == 'E')
				$msg = $data_row_log['log_desc'];
			$sts = "OK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//
	private function get_master_spk()
	{
		$this->connectSAP("ERP_KMTEMP");
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$datetime = date("Y-m-d H:i:s");
		//tbl_pi_spk_detail
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_MATDOC_WORKSHOP_GET",
				array(
					array("IMPORT", "I_BEGDA", date('Ymd', strtotime('-2 month'))),
					array("IMPORT", "I_ENDDA", date('Ymd')),
					array("IMPORT", "I_MOVE_TYPE", '261'),    //for GI
					array("TABLE", "T_DATA", array())
				)
			);
			//T_DATA
			if (!empty($result["T_DATA"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_DATA"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								$data_row[$key] = ltrim(rtrim($value));
								break;
						}
					}
					$data_batch[] = $data_row;
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MATDOC_WORKSHOP_GET',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_pi_spk_detail ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				// $this->dgeneral->delete("tbl_pi_spk_detail");
				$this->dgeneral->delete("tbl_pi_spk_detail", "", "pstng_date between '" . date('Y-m-d', strtotime('-2 month')) . "' and '" . date('Y-m-d') . "'");
				$this->dgeneral->insert_batch("tbl_pi_spk_detail", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_MATDOC_WORKSHOP_GET',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}
		//tbl_pi_spk
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_MATDOC_WORKSHOP_GET",
				array(
					array("IMPORT", "I_BEGDA", date('Ymd', strtotime('-2 month'))),
					array("IMPORT", "I_ENDDA", date('Ymd')),
					array("IMPORT", "I_MOVE_TYPE", '101'),    //for GR
					array("TABLE", "T_DATA", array())
				)
			);
			//T_DATA
			if (!empty($result["T_DATA"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_DATA"] as $dt) {
					// SPK MTO
					$pi_spk = $this->dtransaksidata->get_data_pi_spk(NULL, $dt['ORDERID']);
					if ($pi_spk) {
						$data_row = array(
							"mat_doc"    => $dt['MAT_DOC'],
							"doc_year"   => $dt['DOC_YEAR'],
							"doc_date"   => $dt['DOC_DATE'],
							"pstng_date" => $dt['PSTNG_DATE']
						);
						$data_row = $this->dgeneral->basic_column("update", $data_row);
						$this->dgeneral->update("tbl_pi_spk", $data_row, array(
							array(
								'kolom' => 'no_io',
								'value' => $dt['ORDERID']
							)
						));

						$data_batch[] = $data_row;
					}

					// SPK MTS
					$pi_spk = $this->dtransaksidata->get_data_pi_spk_mts(NULL, $dt['ORDERID']);
					if ($pi_spk) {
						$data_row = array(
							"mat_doc"    => $dt['MAT_DOC'],
							"doc_year"   => $dt['DOC_YEAR'],
							"doc_date"   => $dt['DOC_DATE'],
							"pstng_date" => $dt['PSTNG_DATE']
						);
						$data_row = $this->dgeneral->basic_column("update", $data_row);
						$this->dgeneral->update("tbl_pi_spk_mts", $data_row, array(
							array(
								'kolom' => 'no_io',
								'value' => $dt['ORDERID']
							)
						));

						$data_batch[] = $data_row;
					}
				}
				//input log
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MATDOC_WORKSHOP_GET',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_pi_spk ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MATDOC_WORKSHOP_GET',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => "Data kosong. " . $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_MATDOC_WORKSHOP_GET',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}


		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = $data_row_log['log_desc'];
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Transaksi Master Item berhasil";
			$sts = "OK";
			if ($data_row_log['log_code'] == 'E') {
				$msg = $data_row_log['log_desc'];
				$sts = "NotOK";
			}
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//ongkir
	private function get_master_ongkir()
	{
		$this->connectSAP("ERP_310");
		// $this->connectSAP("ERP_KMTEMP");
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$datetime = date("Y-m-d H:i:s");
		//tbl_pi_master_ongkir
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_MASTERITEM",
				array(
					array("TABLE", "T_RATE", array())
				)
			);
			//T_RATE
			if (!empty($result["T_RATE"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				foreach ($result["T_RATE"] as $dt) {
					$data_row = array();
					foreach ($dt as $key => $value) {
						switch ($key) {
							case 'VALFR':
							default:
								$data_row[$key] = ltrim(rtrim($value));
								break;
						}
					}
					$data_batch[] = $data_row;
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_pi_master_ongkir ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->delete("tbl_pi_master_ongkir");
				$this->dgeneral->insert_batch("tbl_pi_master_ongkir", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERITEM',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => 'Data Kosong',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_MASTERITEM',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = $data_row_log['log_desc'];
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Transaksi Master Item berhasil";
			if ($data_row_log['log_code'] == 'E')
				$msg = $data_row_log['log_desc'];
			$sts = "OK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//set kode material
	private function set_kode_material()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		// if (isset($_POST['ck']) && count($_POST['ck']) > 0) {
		if (count($_POST['ck']) > 0) {

			foreach ($_POST['ck'] as $dt) {
				$id_item_spec = $this->generate->kirana_decrypt($_POST['id_item_spec'][$dt]);
				$code         = $_POST['code'][$dt];
				$data_rfc     = $this->dtransaksidata->push_data_material_code($id_item_spec);
				$datetime     = date("Y-m-d H:i:s");
				if (empty($data_rfc)) {
					$msg    = "Tidak Ada Data yang diproses";
					$sts    = "NotOK";
					$return = array('sts' => $sts, 'msg' => $msg);
					echo json_encode($return);
					exit();
				}

				if ($this->data['sap']->getStatus() == SAPRFC_OK) {
					$result = $this->data['sap']->callFunction(
						"Z_RFC_CREATEMATERIAL",
						array(
							array("TABLE", "T_DATA", $data_rfc),
							array("TABLE", "T_RETURN", array()),
						)
					);
					if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
						//update tbl_item_plant
						$string = "
							update
							tbl_item_plant
							set status_sap='y'
							from tbl_item_plant
							where id_item_spec='$id_item_spec' and na='n'
						";
						$query  = $this->db->query($string);
						//update tbl_item_spec
						$string = "
							update
							tbl_item_spec
							set req='n'
							from tbl_item_spec
							where id_item_spec='$id_item_spec' and na='n'
						";
						$query  = $this->db->query($string);
						//log rfc
						$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
						$data_row_log = array(
							'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
							'log_code'      => '',
							'log_status'    => 'Berhasil',
							'log_desc'      => $status,
							'executed_by'   => 0,
							'executed_date' => $datetime
						);
						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
					} else {        //jika gagal

						$data_row = array(
							'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
							'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
							'log_code'      => $result['T_RETURN'][1]['TYPE'],
							'log_status'    => 'Gagal',
							'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
						$this->dgeneral->insert('tbl_log_rfc', $data_row);
					}
				} else {
					$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
					$data_row_log = array(
						'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
						'log_code'      => 'E',
						'log_status'    => 'Gagal',
						'log_desc'      => $status,
						'executed_by'   => 0,
						'executed_date' => $datetime
					);
					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				}
			}

			//================================SAVE ALL================================//
			if ($result['T_RETURN'][1]['TYPE'] == 'S') {
				$this->dgeneral->commit_transaction();
				$msg = "Pembuatan Kode Material SAP berhasil.";
				$sts = "OK";
			} else {
				$this->dgeneral->rollback_transaction();
				$msg = $code . ' ' . $result['T_RETURN'][1]['MESSAGE'];
				$sts = "NotOK";
			}
		} else {
			$msg = "Silahkan pilih item yang ingin dikirim ke SAP";
			$sts = "notOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//extend kode material
	private function extend_kode_material()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_item_spec = $this->generate->kirana_decrypt($_POST["id_item_spec"]);
		$data_rfc     = $this->dtransaksidata->push_data_material_code($id_item_spec, 'extend');
		// $data_rfc     = $this->dtransaksidata->push_data_material_code(NULL, 'extend');
		// echo json_encode($data_rfc);
		// exit();

		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_CREATEMATERIAL",
				array(
					array("IMPORT", "I_FLAG", ""),
					array("TABLE", "T_DATA", $data_rfc),
					array("TABLE", "T_RETURN", array()),
				)
			);
			if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
				//update tbl_item_plant
				$string = "
					update
					tbl_item_plant
					set 
					status_sap='y',
					login_edit='" . base64_decode($this->session->userdata("-id_user-")) . "',
					tanggal_edit='" . $datetime . "'
					from tbl_item_plant 
					where id_item_spec='$id_item_spec' and na='n'
				";
				$query  = $this->db->query($string);
				//log rfc
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {        //jika gagal

				$data_row = array(
					'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
					'log_code'      => $result['T_RETURN'][1]['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}


		//================================SAVE ALL================================//
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {
			$this->dgeneral->commit_transaction();
			$msg = "Transaksi Push Material Code berhasil";
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}
		// echo json_encode($result['T_RETURN']);
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//change kode material
	private function change_kode_material()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$id_item_spec = $this->generate->kirana_decrypt($_POST["id_item_spec"]);
		$msehi_uom    = (isset($_POST['msehi_uom']) ? $_POST['msehi_uom'] : NULL);
		$data_rfc     = $this->dtransaksidata->change_material_code($id_item_spec, strtoupper(str_replace("'", "''", $_POST['detail'])),$msehi_uom);
		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_CREATEMATERIAL",
				array(
					array("IMPORT", "I_FLAG", "X"),
					array("TABLE", "T_MAKTX", $data_rfc),
					// array("TABLE", "T_DATA", $data_rfc),
					array("TABLE", "T_RETURN", array()),
				)
			);
			if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
				//update tbl_item_spec	
				$data_row = array(
					"msehi_uom" 	=> $_POST['msehi_uom'],
					"description" 	=> strtoupper($_POST['description']),
					"detail"      	=> strtoupper($_POST['detail'])
				);
				$data_row = $this->dgeneral->basic_column("update", $data_row);
				$this->dgeneral->update("tbl_item_spec", $data_row, array(
					array(
						'kolom' => 'id_item_spec',
						'value' => $id_item_spec
					)
				));
				//input data log
				$data_log = array(
					"id_item_spec"    => $id_item_spec,
					"description_old" => strtoupper($_POST['description_awal']),
					"description_new" => strtoupper($_POST['description']),
					"msehi_uom_old" => $_POST['msehi_uom_awal'],
					"msehi_uom_new" => $_POST['msehi_uom']
				);
				$data_log = $this->dgeneral->basic_column("insert", $data_log);
				$this->dgeneral->insert("tbl_item_spec_log", $data_log);

				//log rfc
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {        //jika gagal
				$data_row = array(
					'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
					'log_code'      => $result['T_RETURN'][1]['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC KODE MATERIAL PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_CREATEMATERIAL',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}


		//================================SAVE ALL================================//
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {
			$this->dgeneral->commit_transaction();
			$msg = "Transaksi Push Material Code berhasil";
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}
		// echo json_encode($result['T_RETURN']);
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//cek_kode_material
	private function cek_kode_material()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$code   = $_POST["code"];
			$result = $this->data['sap']->callFunction(
				"Z_RFC_CHECK_MATERIALCODE",
				array(
					array("IMPORT", "I_MATNR", $code),
					array("EXPORT", "E_RETURN", array()),
				)
			);
			if ($result['E_RETURN']['TYPE'] == 'E') {
				$msg = $code . " " . $result['E_RETURN']['MESSAGE'];
				$sts = "NotOK";
			} else {
				$msg = "Kode Material Tersedia";
				$sts = "OK";
			}
		}
		// echo json_encode($result);
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function get_master_spd()
	{
		$this->connectSAP("ESS");            //710 HR
		// $this->connectSAP("ERP_KMTEMP");     //km_temp

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$datetime = date("Y-m-d H:i:s");

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_MASTERDATA_SPD",
				array(
					array("TABLE", "T_TYPE", array()),
					array("TABLE", "T_ACTT", array()),
					array("TABLE", "T_EXPE", array()),
					array("TABLE", "T_PLANT", array()),
					array("TABLE", "T_KURS", array()),
					array("TABLE", "T_SUPG", array()),

				)
			);
			// echo json_encode($result);
			// exit();

			//T_TYPE
			if (!empty($result["T_TYPE"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				$this->dgeneral->delete("tbl_travel_jenisaktifitas");
				foreach ($result["T_TYPE"] as $dt) {
					$data_row = array(
						"client"             => $dt['MANDT'],
						"kode_jns_aktifitas" => $dt['ACTIVITY'], //ACTIVITY ACTICITY
						"language"           => $dt['SPRAS'],
						"jenis_aktifitas"    => $dt['NAME']
					);
					$this->dgeneral->insert("tbl_travel_jenisaktifitas", $data_row);
					$data_batch[] = $data_row;
				}

				//input log
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERDATA_SPD',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_travel_jenisaktifitas ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_ACTT
			if (!empty($result["T_ACTT"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				$this->dgeneral->delete("tbl_travel_areaaktifitas");
				foreach ($result["T_ACTT"] as $dt) {
					$data_row = array(
						"akses_jns_aktifitas" => $dt['TVTTA_FIND'],
						"kode_jns_aktifitas"  => $dt['ACTIVITY'],
						"area_aktifitas"      => $dt['NAME'],
						"default_value"       => $dt['DEFAULT']

					);
					$this->dgeneral->insert("tbl_travel_areaaktifitas", $data_row);
					$data_batch[] = $data_row;
				}
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERDATA_SPD',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_travel_areaaktifitas ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_EXPE
			if (!empty($result["T_EXPE"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				// $data_batch =array();
				// foreach ($result["T_EXPE"] as $dt) {
				//     $data_row = array();
				//     foreach ($dt as $key => $value) {
				//         switch ($key) {
				//             case 'BEGDA' :
				//             case 'ENDDA' :
				//                 $data_row[$key] = date_create($value)->format("Y-m-d");
				//                 break;
				//             case 'BETRG' :
				//                 $data_row[$key] = floatval($value);
				//                 break;
				//             default :
				//                 // $data_row[$key] = ltrim(rtrim($value));
				//                 $data_row[$key] = $value;
				//                 break;
				//         }
				//     }
				//     $data_batch[] = $data_row;
				// }

				$data_batch = array();
				$this->dgeneral->delete("tbl_travel_tipeexpense");
				foreach ($result["T_EXPE"] as $dt) {
					$data_row = array(
						"kode_expense"      => $dt['SPKZL'],
						"amount_type"       => $dt['ATYPE'],
						"end_date"          => date_create($dt['ENDDA'])->format("Y-m-d"),
						"tipe_travel"       => $dt['KZREA'],
						"tipe_company"      => $dt['BEREI'],
						"tipe_aktifitas"    => $dt['KZTKT'],
						"country"           => $dt['LNDGR'],
						"region"            => $dt['RGION'],
						"jabatan"           => $dt['ERGRU'],
						"statutory"         => $dt['ERKLA'],
						"tipe_expense_text" => $dt['SPTXT'],
						"value"             => floatval($dt['BETRG']),
						"currency"          => $dt['WAERS'],
						"start_date"        => date_create($dt['BEGDA'])->format("Y-m-d")
					);
					$this->dgeneral->insert("tbl_travel_tipeexpense", $data_row);
					$data_batch[] = $data_row;
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERDATA_SPD',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_travel_tipeexpense ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				// $this->dgeneral->delete("tbl_travel_tipeexpense");
				// $this->dgeneral->insert_batch("tbl_travel_tipeexpense", $data_batch);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_PLANT
			if (!empty($result["T_PLANT"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				$this->dgeneral->delete("tbl_travel_subarea");
				foreach ($result["T_PLANT"] as $dt) {
					$data_row = array(
						"company_code"          => $dt['BTRTL'],
						"personal_area"         => $dt['WERKS'],
						"personal_subarea"      => $dt['BUKRS'],
						"personal_subarea_text" => $dt['BTEXT'],
						"personal_area_text"    => $dt['NAME1'],
						"akses_jns_aktifitas"   => $dt['TVTTA_FIND'],

					);
					$this->dgeneral->insert("tbl_travel_subarea", $data_row);
					$data_batch[] = $data_row;
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERDATA_SPD',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_travel_subarea ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_KURS
			if (!empty($result["T_KURS"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				$this->dgeneral->delete("tbl_travel_currency");
				foreach ($result["T_KURS"] as $dt) {
					$data_row = array(
						"client_sap"    => $dt['MANDT'],
						"currency" 		=> $dt['WAERS'],
						"iso"      		=> $dt['ISOCD'],
						"alt_key" 		=> $dt['ALTWR'],

					);
					$this->dgeneral->insert("tbl_travel_currency", $data_row);
					$data_batch[] = $data_row;
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERDATA_SPD',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_travel_currency ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
			//T_SUPG
			if (!empty($result["T_SUPG"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$data_batch = array();
				$this->dgeneral->delete("tbl_travel_golongan");
				foreach ($result["T_SUPG"] as $dt) {
					$data_row = array(
						"client_sap"        => $dt['MANDT'],
						"kode_golongan"     => $dt['PERSK'],
						"nama_golongan" 	=> $dt['PTEXT'],

					);
					$this->dgeneral->insert("tbl_travel_golongan", $data_row);
					$data_batch[] = $data_row;
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_MASTERDATA_SPD',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => 'Berhasil menambahkan ke SQLServer tbl_travel_golongan ' . count($data_batch) . ' baris data',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_MASTERDATA_SPD',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = $data_row_log['log_desc'];
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Transaksi Master Travel berhasil";
			if ($data_row_log['log_code'] == 'E')
				$msg = $data_row_log['log_desc'];
			$sts = "OK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function set_data_sicom()
	{
		$datetime = date("Y-m-d H:i:s");
		$date     = (isset($_GET['date']) ? $_GET['date'] : date("Y-m-d"));
		$limit    = 1;
		if (date("l", strtotime(date("Y-m-d"))) == "Monday")
			$limit = 3;
		$min_date = date("Y-m-d", strtotime(date("Y-m-d") . ' - ' . $limit . ' days'));
		$column   = (isset($_GET['column']) ? $_GET['column'] : 'daily-settlement-price-adj');
		$day      = date('l', strtotime($date));
		if ($date < $min_date || $date > date("Y-m-d") || in_array($day, array("Saturday", "Sunday")) == true) {
			$sts    = "NotOK";
			$msg    = "Parameter tanggal tidak sesuai";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}

		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl_handle, CURLOPT_URL, 'https://api.sgx.com/derivatives/v1.0/contract-code/TF?order=asc&orderby=delivery-month&category=futures&session=-1&showTAICTrades=false');
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_handle, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl_handle, CURLOPT_FAILONERROR, true);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 20);
		curl_setopt($curl_handle, CURLOPT_AUTOREFERER, true);
		curl_setopt($curl_handle, CURLOPT_COOKIEFILE, "");
		curl_setopt($curl_handle, CURLOPT_VERBOSE, true);
		$res = curl_exec($curl_handle);
		if (curl_error($curl_handle)) {
			$res = curl_error($curl_handle);
		}
		$httpcode = curl_getinfo($curl_handle, CURLINFO_EFFECTIVE_URL);
		curl_close($curl_handle);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		if ($res) {
			$res = json_decode($res, true);
			if ($res['meta']['code'] == 200) {
				if (!empty($res['data'])) {
					$data_batch = array();
					$data_sap   = array();
					foreach ($res['data'] as $key => $dt) {
						$data_row     = array(
							"date"             => $date,
							"deliv_month"      => $dt['delivery-month'],
							"daily_settlement" => $dt[$column],
							"date_input"       => $datetime
						);
						$data_batch[] = $data_row;
						if ($key == 0 && $dt[$column])
							$data_sap = $data_row;
					}
					$this->dgeneral->delete("tbl_sicom_price", array(
						array(
							"kolom" => "date",
							"value" => $date
						)
					));
					$this->dgeneral->insert_batch("tbl_sicom_price", $data_batch);

					$data_row_log = array(
						'app'           => 'DATA RFC PORTAL',
						'rfc_name'      => 'Z_RFC_SICOM_PRICE',
						'log_code'      => 'S',
						'log_status'    => 'Berhasil',
						'log_desc'      => count($data_batch) . ' data berhasil ditambahkan di SQL',
						'executed_by'   => 0,
						'executed_date' => $datetime
					);
				} else {
					$data_row_log = array(
						'app'           => 'DATA RFC PORTAL',
						'rfc_name'      => 'Z_RFC_SICOM_PRICE',
						'log_code'      => 'E',
						'log_status'    => 'Gagal',
						'log_desc'      => 'SGX: Data kosong',
						'executed_by'   => 0,
						'executed_date' => $datetime
					);
					// $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				}
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_SICOM_PRICE',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => empty($res['meta']['message']) ? "Gagal mengambil data ke SGX" : "SGX: " . $res['meta']['message'],
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				// $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_SICOM_PRICE',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Gagal connect ke SGX",
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			// $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$this->general->closeDb();
			$msg = $data_row_log['log_desc'];
			$sts = "NotOK";

			$this->general->connectDbPortal();
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			$this->dtransaksidata->generate_notif_sicom($date);

			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		} else {
			$this->dgeneral->commit_transaction();
			$this->general->closeDb();
			$this->set_data_sicom_sap($data_sap, $datetime, $data_row_log['log_desc']);
		}
	}

	private function set_data_sicom_sap($data_sap = NULL, $datetime = NULL, $api_msg = NULL)
	{
		$this->connectSAP("ERP_310");

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$date_input = (empty($data_sap) ? $data_sap['date'] : date("Y-m-d"));
		$notif      = true;

		if ($this->data['sap']->getStatus() == SAPRFC_OK && isset($data_sap) && !empty($data_sap)) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_SICOM_PRICE",
				array(
					array("IMPORT", "I_SICDT", date_format(date_create($data_sap['date']), "Ymd")),
					array("IMPORT", "I_SICPR", $data_sap['daily_settlement']),
					array("EXPORT", "E_RETURN", array())
				)
			);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && !empty($result["E_RETURN"])) {
				$status       = $result["E_RETURN"]['TYPE'] == 'E' ? 'Gagal' : 'Berhasil';
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_SICOM_PRICE',
					'log_code'      => $result["E_RETURN"]['TYPE'],
					'log_status'    => $status,
					'log_desc'      => $api_msg . ', ' . $result["E_RETURN"]['MESSAGE'] . ', Data SICOM tanggal ' . $data_sap['date'] . ' ' . $status . ' ditambahkan',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);

				if ($result["E_RETURN"]['MESSAGE'] == "Data SICOM sudah ada") {
					$notif = false;
				}
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_SICOM_PRICE',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => $api_msg . ', Silahkan periksa kembali data RFC yang dimasukkan',
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
			}
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_SICOM_PRICE',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Data kosong atau " . (empty($status) ? $api_msg : $status),
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$this->general->closeDb();
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$this->general->closeDb();
			$sts = "OK";
			if ($data_row_log['log_code'] == 'E')
				$sts = "NotOK";
		}

		if ($notif == true)
			$this->dtransaksidata->generate_notif_sicom($date_input);

		$msg    = $data_row_log['log_desc'];
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		exit();
	}

	private function set_data_tagihan_pln()
	{
		$this->load->helper('url');
		$datetime = date("Y-m-d H:i:s");
		$starttime = $datetime;
		$data_pelanggan = $this->dtransaksidata->get_data_nopel_pln(
			array(
				"connect" => TRUE
			)
		);

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		if ($data_pelanggan) {
			$data_batch = array();
			foreach ($data_pelanggan as $dt) {
				$username   = "08131351760";
				$apiKey   = "9035e7d889e6179b903";
				$ref_id  = mt_rand();
				$code  = 'PLNPOSTPAID';
				$hp  = $dt->IDPEL;
				$signature  = md5($username . $apiKey . $ref_id);
				$json = '{
							"commands" : "inq-pasca",
							"username" : "' . $username . '",
							"code"     : "' . $code . '",
							"ref_id"   : "' . $ref_id . '",
							"hp"       : "' . $hp . '",
							"sign"     : "' . $signature . '"
						}';
				$data_param = array(
					'method' => 'POST',
					'data' => $json,
					'url' => 'https://mobilepulsa.net/api/v1/bill/check',
					'header' => array('Content-Type: application/json')
				);

				$tagihan = $this->curl($data_param);
				$tagihan = json_decode($tagihan, TRUE);
				if ($tagihan) {
					if (!empty($tagihan['data']) && $tagihan['data']['response_code'] == "00") {
						$data_batch[] = array(
							'WERKS' => $dt->WERKS,
							'IDPEL' => $dt->IDPEL,
							'MONAT' => date('m'),
							'GJAHR' => date('Y'),
							'BUDAT' => date('Ymd'),
							'NETWR' => $tagihan['data']['nominal'],
							'WAERS' => 'IDR'
						);

						$data_row_log[] = array(
							'app'           => 'DATA SCHEDULE PORTAL',
							'function_name' => current_url(),
							'log_code'      => 'S',
							'log_status'    => 'Success',
							'log_desc'      => "Data tagihan PLN " . $dt->WERKS . " [" . $dt->IDPEL . "] berhasil",
							'executed_by'   => 0,
							'executed_date' => $datetime
						);
					} else {
						$data_row_log[] = array(
							'app'           => 'DATA SCHEDULE PORTAL',
							'function_name' => current_url(),
							'log_code'      => 'E',
							'log_status'    => 'Gagal',
							'log_desc'      => "Data tagihan PLN " . $dt->WERKS . " [" . $dt->IDPEL . "] gagal diambil, error : " . $tagihan['data']['message'],
							'executed_by'   => 0,
							'executed_date' => $datetime
						);

						$data_row_msg[] = "Data tagihan " . $dt->WERKS . " [" . $dt->IDPEL . "] gagal diambil, error : " . $tagihan['data']['message'];
					}
				}
			}

			$result_sap = array();
			if (!empty($data_batch)) {
				$result_sap = $this->set_data_tagihan_pln_sap($data_batch, $datetime);
				if (!empty($result_sap['data_success'])) {
					//message email
					$recipient = array();
					$data_recipient = $this->dgeneral->get_data_recipient_email(
						array(
							"connect" => FALSE,
							"app" => "jurnal_pln"
						)
					);
					foreach ($data_recipient as $dt) {
						$recipient[] = $dt->email;
					}

					$notif_msg = "<table style='background: #fff1d0; border-radius: 4px; padding: 10px 0px; width: 100%;'>
									<thead>
										<th width='20%'>Pabrik</th>
										<th width='40%'>ID Pelanggan</th>
										<th width='20%'>Periode</th>
										<th width='20%'>Status</th>
									</thead>
									<tbody>";
					foreach ($result_sap['data_success'] as $dt) {
						$notif_msg .= "
										<tr>
											<td style='text-align: center;'>" . $dt['plant'] . "</td>
											<td style='text-align: center;'>" . $dt['idpel'] . "</td>
											<td style='text-align: center;'>" . date('m-Y') . "</td>
											<td style='text-align: center; color: #008d4c;'>Ready to Post</td>
										</tr>
						";
					}
					$notif_msg .= "</tbody>
								 </table>";

					$message = $this->generate_email_message(
						array(
							"app" => "jurnal_pln",
							"content" => $notif_msg
						)
					);
					$this->general->send_email("Posting Jurnal PLN", "KiranaKu", $recipient, "", $message);
				}

				$data_row_msg[] = $result_sap['msg'];
			} else {
				$data_row_log[] = array(
					'app'           => 'DATA SCHEDULE PORTAL',
					'function_name' => current_url(),
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => "Data tagihan PLN kosong",
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$data_row_msg[] = "Data tagihan kosong";
			}

			if (!empty($data_row_log))
				$this->dgeneral->insert_batch("tbl_log_schedule", $data_row_log);

			if ($this->dgeneral->status_transaction() === false) {
				$this->dgeneral->rollback_transaction();
				$this->general->closeDb();
				$sts = "NotOK";
			} else {
				$this->dgeneral->commit_transaction();
				$this->general->closeDb();

				//===write to log scheduler===//
				$script = str_replace(base_url(), "", current_url());
				$this->general->log_schedule_master($script, array(
					"keterangan" => "Scheduler Jurnal PLN",
					"sesi" => "21:00",
					"source" => "API",
					"terminal" => "-",
					"destination" => "SAP",
				));
				$this->general->log_schedule_running($script, array(
					"rfc" => "Z_RFC_TAGIHAN_PLN",
					'tanggal'  => date("Y-m-d"),
					'start'    => $starttime,
					'end_time' => date("Y-m-d H:i:s")
				));
				//===write to log scheduler===//

				$sts = "OK";
			}

			$msg    = implode(" => ", $data_row_msg);
			$return = array('sts' => $sts, 'msg' => $msg, 'result' => compact('result_sap', 'data_batch'));
			echo json_encode($return);
			exit();
		}
	}

	private function set_data_tagihan_pln_sap($data_sap = NULL, $datetime = NULL)
	{
		$this->connectSAP("ERP_KMTEMP");
		$type    = array();
		$message = array();
		$data_success = array();

		if ($this->data['sap']->getStatus() == SAPRFC_OK && isset($data_sap) && !empty($data_sap)) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_TAGIHAN_PLN",
				array(
					array("TABLE", "T_DATA", $data_sap),
					array("TABLE", "T_RETURN", array())
				)
			);

			if ($this->data['sap']->getStatus() == SAPRFC_OK && !empty($result["T_RETURN"])) {
				foreach ($result["T_RETURN"] as $return) {
					$type[]    = $return['TYPE'];
					$message[] = $return['MESSAGE'];
					if ($return['TYPE'] == "S")
						$data_success[] = array(
							"plant" => $return['MESSAGE_V1'],
							"idpel" => $return['MESSAGE_V2']
						);
				}

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_TAGIHAN_PLN',
					'log_code'      => implode(" , ", $type),
					'log_status'    => in_array("E", $type) === true ? 'Gagal' : 'Berhasil',
					'log_desc'      => 'Data tagihan PLN : ' . implode(" , ", $message),
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
			} else {
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_TAGIHAN_PLN',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => 'Data tagihan PLN : ' . $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_SICOM_PRICE',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Data kosong atau " . $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
		}
		$count = array_count_values($type);

		if (count($type) > 0 && isset($count["E"]) && $count["E"] !== count($type))
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

		return array(
			"msg" => $data_row_log['log_desc'],
			"data_success" => $data_success,
			"duplicate_all" => count($type) > 0 && isset($count["E"]) && $count["E"] == count($type) ? TRUE : FALSE,
			"compact" => compact('type', 'count', 'data_row_log')
		);
	}

	private function generate_email_message($param = NULL)
	{
		switch ($param['app']) {
			case 'jurnal_pln':
				$data = array(
					"hidden_msg" => "Notifikasi Email Aplikasi Posting Jurnal PLN",
					"title" => "Posting Jurnal PLN",
					"nama_to" => "Bapak/Ibu,<br><br>",
					"content" => "<p>Email ini menandakan bahwa ada Tagihan PLN baru yang membutuhkan perhatian anda. Mohon untuk segera diproses di SAP menggunakan TCODE : ZFII16.</p><br>" . $param['content']
				);
				break;
			case 'sapbgjob':
				$data = array(
					"hidden_msg" => "Notifikasi Email Background Job SAP",
					"title" => "Background Job SAP",
					"nama_to" => "Bapak/Ibu,<br><br>",
					"content" => "<p>Email ini menandakan bahwa ada Background Job SAP yang membutuhkan perhatian anda. Mohon bantuannya untuk segera diperiksa : </p><br>" . $param['content']
				);
				break;

			default:
				$data = array();
				break;
		}

		$message = "<html>
                        <body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
                        <center style='width: 100%;'>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                " . $data['hidden_msg'] . "
                            </div>
                            <div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
                                &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
                            </div>
                            <div class='email-container' style='max-width: 800px; margin: 0 auto;'>
                                <table align='center' role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'
                                       style='min-width:600px;'>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style='color: #fff; padding:20px;' align='center'>
                                            <div style='width: 50%; padding-bottom: 10px;''>
                                                <img src='" . base_url() . "/assets/apps/img/logo-lg.png'>
                                            </div>
                                            <h1 style='margin-bottom: 0;'>" . $data['title'] . "</h1>
                                            <hr style='border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;'/>
                                            <h3 style='margin-top: 0;'>Notifikasi Email</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table style='background-color: #ffffff; margin: auto; -webkit-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); -moz-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4);'
                                                   role='presentation' border='0' width='100%' cellspacing='0'
                                                   cellpadding='0'
                                                   align='center'>
                                                <tbody>
                                                <tr>
                                                    <td style='padding: 20px;'>";

		$message .= "<p><strong>Kepada :<br>" . $data['nama_to'] . "</strong></p>";
		$message .= $data['content'];
		$message .= "								</td>
												</tr>
												<tr>
													<br><br>
													<td align='left'
														style='background-color: #ffffff; padding: 20px;'>
														<p>
															Terima kasih atas perhatiannya.
														</p>
													</td>
												</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td style='color: #fff; padding-top:20px;' align='center'>
										<small>Kiranaku Auto-MailSystem</small><br/>";
		$message .= "						<strong style='color: #214014; font-size: 10px;'>Terkirim pada " . date('d.m.Y H:i:s') . "</strong>"; // TANGGAL KIRIM EMAIL
		$message .= "
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
								</table>
							</div>
						</center>
						</body>
					</html>";


		return $message;
	}

	private function get_document_number()
	{
		$this->connectSAP("ERP_310"); 			//310
		// $this->connectSAP("ERP_KMTEMP");        //km_temp

		$this->load->model('nusira/dtransaksinusira', 'dtransaksinusira');

		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$datetime = date("Y-m-d H:i:s");
		$type    = array();
		$message = array();
		$empty_po = 0;
		$empty_so = 0;
		$empty_item = 0;

		$data_nusira = $this->dtransaksidata->get_data_rekom_detail(
			array(
				"connect" => FALSE,
				"NULL_no_po" => TRUE,
				"ORNULL_no_so" => TRUE,
				"app" => "nusira",
				"IN_status" => array("finish"),
				"IN_year" => array(date('Y') - 1, date('Y')),
				"new_method" => 1,
				"selected" => TRUE
			)
		);
		$data_kpro = $this->dtransaksidata->get_data_rekom_detail(
			array(
				"connect" => FALSE,
				"NULL_no_po" => TRUE,
				"app" => "k-pro",
				"IN_status" => array("finish"),
				"IN_year" => array(date('Y') - 1, date('Y')),
				"new_method" => 1,
				"selected" => TRUE
			)
		);
		$data_pi = array_merge($data_kpro, $data_nusira);

		if ($data_pi) {
			if ($this->data['sap']->getStatus() == SAPRFC_OK) {

				$no_pr = array_map(function ($value) {
					return array(
						"XBLNR" => $value->no_pi,
						"PIITM" => $value->no_detail_pi,
					);
				}, $data_pi);

				$result  = $this->data['sap']->callFunction(
					"Z_RFC_P2P_DOCFLOW_GET1",
					array(
						array("TABLE", "T_XBLNR", $no_pr),
						array("TABLE", "T_DOCFLOW", array()),
						array("TABLE", "T_RETURN", array()),
					)
				);

				if ($this->data['sap']->getStatus() == SAPRFC_OK) {
					if (!empty($result["T_RETURN"])) {
						foreach ($result["T_RETURN"] as $return) {
							$type[]    = $return['TYPE'];
							$message[] = $return['MESSAGE'];
						}
					}

					$data_docflow = array();
					if (!empty($result['T_DOCFLOW'])) {
						foreach ($result["T_DOCFLOW"] as $return) {
							$type[]    = 'S';
							$message[] = $return['XBLNR'] . ' Berhasil';
							$lifnr = ltrim($return['LIFNR'], '0');
							$rvnum = ltrim($return['RVNUM'], '0');
							$idx = $return['XBLNR'] . '&&' . $return['PIITM'] . '&&' . $lifnr . '&&' . $rvnum;
							if (array_key_exists($idx, $data_docflow) === FALSE) {
								$data_docflow[$idx]['no_po'] = array();
								$data_docflow[$idx]['no_so'] = array();
								$data_docflow[$idx]['item_po'] = array();
							}

							if (!empty($return['EBELN'])) {
								$data_docflow[$idx]['no_po'][] = $return['EBELN'];
							}
							if (!empty($return['VBELN'])) {
								$data_docflow[$idx]['no_so'][] = $return['VBELN'];
							}
							if (!empty($return['EBELP'])) {
								$data_docflow[$idx]['item_po'][] = $return['EBELP'];
							}
						}

						foreach ($data_docflow as $key => $value) {
							if (empty($value['no_po'])) {
								$empty_po++;
							}
							if (empty($value['no_so'])) {
								$empty_so++;
							}
							if (empty($value['item_po'])) {
								$empty_item++;
							}

							$no_po = array_filter($value['no_po'], function ($v) {
								return trim($v);
							});
							$no_so = array_filter($value['no_so'], function ($v) {
								return trim($v);
							});
							$item_po = array_filter($value['item_po'], function ($v) {
								return trim($v);
							});
							$data_row = array(
								"no_po" => empty($no_po) ? NULL : implode(",", $no_po),
								"no_so" => empty($no_so) ? NULL : implode(",", $no_so),
								"item_po" => empty($item_po) ? NULL : implode(",", $item_po)
							);
							$data_row = $this->dgeneral->basic_column("update", $data_row);

							$index = explode("&&", $key);
							//=======update data=======//
							$this->dtransaksidata->update_rekom_detail(
								array_merge(
									$data_row,
									array(
										"no_pi" => $index[0],
										"no_detail_pi" => $index[1],
										"nama_vendor" => $index[2],
										"no_rekom" => $index[3],
									)
								)
							);
							//=========================//
						}
					}

					$count_row = count($data_docflow);
					$status = in_array("E", $type) === true ? 'Gagal' : 'Berhasil';
					$data_row_log = array(
						'app'           => 'DATA RFC PORTAL',
						'rfc_name'      => 'Z_RFC_P2P_DOCFLOW_GET',
						'log_code'      => implode(",", array_unique($type)),
						'log_status'    => $status,
						'log_desc'      => 'Data Document Number : ' . $count_row . ' row sukses terupdate, namun ' . $empty_po . ' dari ' . $count_row . ' row memiliki no PO kosong dan ' . $empty_so . ' dari ' . $count_row . ' row memiliki SO kosong',
						'executed_by'   => 0,
						'executed_date' => $datetime
					);

					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				} else {
					$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
					$data_row_log = array(
						'app'           => 'DATA RFC PORTAL',
						'rfc_name'      => 'Z_RFC_P2P_DOCFLOW_GET',
						'log_code'      => 'E',
						'log_status'    => 'Gagal',
						'log_desc'      => "Connecting : " . $status,
						'executed_by'   => 0,
						'executed_date' => $datetime
					);
					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				}
			} else {
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_P2P_DOCFLOW_GET',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => "Connecting : " . $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_P2P_DOCFLOW_GET',
				'log_code'      => 'S',
				'log_status'    => 'Berhasil',
				'log_desc'      => 'Tidak ada data PR',
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = $data_row_log['log_desc'];
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			//====================UPDATE DATA SUMMARY====================//                
			$this->dtransaksidata->generate_header_summary(
				array(
					"connect" => TRUE
				)
			);
			//==========================================================//
			$msg = $data_row_log['log_desc'];
			$sts = "OK";
			if (in_array('E', $type) === true) {
				$msg = implode(" | ", $message);
				$sts = "NotOK";
			}
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//set margin (simulasi penjualan spot)
	private function set_data_margin()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		// $data_rfc     = $this->dtransaksidata->push_data_material_code($id_item_spec);
		$data_rfc     = $this->dtransaksidata->push_data_margin();
		// echo json_encode($data_rfc);
		// exit();	
		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_UPD_SPOTSALES_MARGIN",
				array(
					array("TABLE", "T_DATA", $data_rfc),
					array("TABLE", "T_RETURN", array()),
				)
			);

			if (empty($result['T_RETURN'])) {
				//update tbl_item_plant
				$string = "
					update
					tbl_spot_sales_conf_detail
					set status_sap='y'
					from tbl_spot_sales_conf_detail
					where status_sap='n'
				";
				$query  = $this->db->query($string);
				//log rfc
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC SIMULASI PENJUALAN SPOT PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_UPD_SPOTSALES_MARGIN',
					'log_code'      => 'S',
					'log_status'    => 'Berhasil',
					'log_desc'      => "RFC Margin berhasil",
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {        //jika gagal
				$data_row = array(
					'app'           => 'DATA RFC SIMULASI PENJUALAN SPOT PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_UPD_SPOTSALES_MARGIN',
					'log_code'      => $result['T_RETURN'][1]['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC SIMULASI PENJUALAN SPOT PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_UPD_SPOTSALES_MARGIN',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "RFC Margin gagal.";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "RFC Margin berhasil.";
			$sts = "OK";
			if ($data_row_log['log_status'] !== 'Berhasil') {
				$msg = $data_row_log['log_desc'];
				$sts = "NotOK";
			}
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//spot
	private function set_data_spot($no_form = NULL, $validasi = NULL)
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		$datetime     	= date("Y-m-d H:i:s");

		$data_header_spot	= $this->dtransaksidata->push_data_header_spot($no_form);
		foreach ($data_header_spot as $dt) {
			$data_header = array(
				"KUNNR"          => $dt->KUNNR,
				"BSTDK"          => $dt->BSTDK,
				"BSTKD"          => $dt->BSTKD,
				"VKORG"          => $dt->VKORG,
				"VTWEG"          => $dt->VTWEG,
				"SPART"          => $dt->SPART,
				"VKGRP"          => $dt->VKGRP,
				"VKBUR"          => $dt->VKBUR,
				"AUGRU"          => $dt->AUGRU,
				"CNTTY"          => $dt->CNTTY,
				"GUEBG"          => $dt->GUEBG,
				"GUEEN"          => $dt->GUEEN,
				"AUDAT"          => $dt->AUDAT,
				"WAERK"          => $dt->WAERK,
				"KTEXT"          => $dt->KTEXT,
				"INCO1"          => $dt->INCO1,
				"INCO2"          => $dt->INCO2,
				"KTGRD"          => $dt->KTGRD
			);
		}


		$data_item		= $this->dtransaksidata->push_data_item_spot($no_form);

		// echo json_encode($data_header);
		// echo '<hr>';
		// echo json_encode($data_item);
		// exit();

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_CREATE_SD_SPOTCONTRACT",
				array(
					array("IMPORT", "I_SPOTNO", $no_form),
					array("IMPORT", "I_HEADER", $data_header),
					array("TABLE", "T_DATAITM", $data_item),
					array("TABLE", "T_RETURN", array()),
					array("EXPORT", "E_VBELN", array()),
				)
			);
			// echo json_encode(array(
			// "T_RETURN" =>$result['T_RETURN'],
			// "E_VBELN" =>$result['E_VBELN'],
			// ));
			// exit();
			if ($result['E_VBELN'] != '') {
				//update tbl_spot_simulate
				$string = " 
					update 
					tbl_spot_simulate
					set no_contract= $result[E_VBELN]
					where no_form='$no_form'
				";
				$query  = $this->db->query($string);
				//log rfc
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC SIMULASI PENJUALAN SPOT PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CREATE_SD_SPOTCONTRACT',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {        //jika gagal

				$data_row = array(
					'app'           => 'DATA RFC SIMULASI PENJUALAN SPOT PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_CREATE_SD_SPOTCONTRACT',
					'log_code'      => $result['T_RETURN'][1]['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC SIMULASI PENJUALAN SPOT PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_CREATE_SD_SPOTCONTRACT',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($validasi == 'y') {
			if ($result['E_VBELN'] != '') {
				$this->dgeneral->commit_transaction();
				$msg = "Nomor Contract SAP berhasil.";
				$sts = "OK";
			} else {
				$this->dgeneral->rollback_transaction();
				$msg = $result['T_RETURN'][1]['MESSAGE'];
				$sts = "NotOK";
			}
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
		} else {
			$this->dgeneral->commit_transaction();
		}
	}

	private function get_sapbgjob()
	{
		$this->connectSAP("ERP_310");
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();
		$datetime = date("Y-m-d H:i:s");

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result  = $this->data['sap']->callFunction(
				"Z_RFC_LOG_JOBSAP",
				array(
					array("TABLE", "T_LOG", array())
				)
			);

			if (!empty($result["T_LOG"]) && $this->data['sap']->getStatus() == SAPRFC_OK) {
				$recipient = array();
				$data_recipient = $this->dgeneral->get_data_recipient_email(
					array(
						"connect" => FALSE,
						"app" => "sapbgjob"
					)
				);
				foreach ($data_recipient as $dt) {
					$recipient[] = $dt->email;
				}

				$notif_msg = "<table style='background: #fff1d0; border-radius: 4px; padding: 10px 10px; width: 100%;'>
                                <thead>
                                    <th width='5%'>MANDT</th>
                                    <th width='55%'>JOB<br>NAME</th>
                                    <th width='15%'>START<br>DATE</th>
                                    <th width='15%'>END<br>DATE</th>
                                    <th width='10%'>STATUS</th>
                                </thead>
                                <tbody>";
				foreach ($result["T_LOG"] as $key => $value) {
					$notif_msg .= "
                                    <tr>
                                        <td style='text-align: center;'>" . $value['MANDT'] . "</td>
                                        <td>" . $value['JOBNAME'] . "</td>
                                        <td style='text-align: center;'>" . $this->generate->generateDateFormat(ltrim(rtrim($value['STRTDATE']))) . "</td>
                                        <td style='text-align: center;'>" . $this->generate->generateDateFormat(ltrim(rtrim($value['ENDDATE']))) . "</td>
                                        <td style='text-align: center;'>" . $value['STATUS'] . "</td>
                                    </tr>
                    ";
				}
				$notif_msg .= "</tbody>
                             </table>";

				$message = $this->generate_email_message(
					array(
						"app" => "sapbgjob",
						"content" => $notif_msg
					)
				);
				$this->general->send_email("Background Job SAP", "KiranaKu", $recipient, "", $message);

				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_LOG_JOBSAP',
					'log_code'      => 'S',
					'log_status'    => 'Berhasil',
					'log_desc'      => "Ada " . count($result["T_LOG"]) . " background JOB SAP yang perlu diperiksa.",
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			} else {
				$data_row_log = array(
					'app'           => 'DATA RFC PORTAL',
					'rfc_name'      => 'Z_RFC_LOG_JOBSAP',
					'log_code'      => 'S',
					'log_status'    => 'Berhasil',
					'log_desc'      => "Semua background JOB SAP berjalan dengan lancar.",
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC PORTAL',
				'rfc_name'      => 'Z_RFC_LOG_JOBSAP',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => "Connecting : " . $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Check log JOB SAP gagal. " . $data_row_log['log_desc'];
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Check log JOB SAP berhasil. " . $data_row_log['log_desc'];
			$sts = "OK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//get vendor
	private function get_vendor($nama_vendor)
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_P2P_SEARCH_VENDOR_BYNAME",
				array(
					array("IMPORT", "I_VDRNAME", "$nama_vendor"),
					array("TABLE", "T_VENDOR", array()),
				)
			);
			// if ($result['E_RETURN']['TYPE'] == 'E') {
			// $msg = $code . " " . $result['E_RETURN']['MESSAGE'];
			// $sts = "NotOK";
			// } else {
			// $msg = "Kode Material Tersedia";
			// $sts = "OK";
			// }
		}
		echo json_encode($result['T_VENDOR']);
		// $return = array('sts' => $sts, 'msg' => $msg);
		// echo json_encode($return);
	}

	//set create vendor
	private function set_create_vendor()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();


		$id_data 	  = $this->generate->kirana_decrypt($_POST['id_data']);
		$data_rfc     = $this->dtransaksidata->push_data_master_vendor($id_data);
		// echo json_encode($data_rfc);
		// exit();
		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}

		if ($this->data['sap']->getStatus() == SAPRFC_OK) {
			$result = $this->data['sap']->callFunction(
				"Z_RFC_P2P_CREATE_VENDOR",
				array(
					array("IMPORT", "I_BUKRS", $_POST['I_BUKRS']),
					array("IMPORT", "I_EKORG", $_POST['I_EKORG']),
					array("IMPORT", "I_KTOKK", $_POST['I_KTOKK']),
					array("TABLE", "T_VENDOR", $data_rfc),
					array("TABLE", "T_RETURN", array()),
				)
			);
			// echo json_encode($result);
			// exit();

			if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
				$message 	= $result['T_RETURN'][1]['MESSAGE'];
				$arr_lifnr	= explode(" ", $message);
				//update tbl_vendor_data
				$string = "
					update
					tbl_vendor_data
					set 
					lifnr='" . $arr_lifnr[1] . "',
					req='n',
					id_status='99'
					where id_data='$id_data'
				";
				$query  = $this->db->query($string);
				//update tbl_vendor_plant
				$string = "
					update
					tbl_vendor_plant
					set 
					status_sap='y',
					login_edit= '" . base64_decode($this->session->userdata("-id_user-")) . "',
					tanggal_edit= '$datetime'
					where id_data='$id_data'
				";
				$query  = $this->db->query($string);
				//log rfc
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_CREATE_VENDOR',
					'log_code'      => '',
					'log_status'    => 'Berhasil',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				//save log status
				$data_row_log = array(
					"id_data"	=> $id_data,
					"id_status"	=> 3
				);
				$data_row_log = $this->dgeneral->basic_column("insert", $data_row_log);
				$this->dgeneral->insert("tbl_vendor_data_log", $data_row_log);
			} else {        //jika gagal
				$data_row = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_CREATE_VENDOR',
					'log_code'      => $result['T_RETURN'][1]['TYPE'],
					'log_status'    => 'Gagal',
					'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
					'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
					'executed_date' => $datetime
				);
				$this->dgeneral->insert('tbl_log_rfc', $data_row);
			}
		} else {
			$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
			$data_row_log = array(
				'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
				'rfc_name'      => 'Z_RFC_P2P_CREATE_VENDOR',
				'log_code'      => 'E',
				'log_status'    => 'Gagal',
				'log_desc'      => $status,
				'executed_by'   => 0,
				'executed_date' => $datetime
			);
			$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		}

		//================================SAVE ALL================================//
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {
			$this->dgeneral->commit_transaction();
			$msg = "Pembuatan Kode Material SAP berhasil.";
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//set extend vendor
	private function set_extend_vendor()
	{
		// $this->connectSAP("ERP");            //prod
		$this->connectSAP("ERP_310");            //310
		// $this->connectSAP("ERP_KMTEMP");		//km_temp
		$this->load->model('dtransaksidata');
		$this->general->connectDbPortal();
		$this->dgeneral->begin_transaction();


		$id_data  	= $this->generate->kirana_decrypt($_POST['id_data']);
		// $lifnr	  	= $_POST['lifnr'];
		// $acc_group	= $_POST['acc_group'];
		// $bukrs		= $_POST['bukrs'];
		// $plant		= $_POST['plant'];
		// // $data_rfc     = $this->dtransaksidata->push_data_master_vendor($id_data);
		$data_rfc     = $this->dtransaksidata->push_data_extend_vendor($id_data);
		// echo json_encode($lifnr);
		// exit();
		$datetime     = date("Y-m-d H:i:s");
		if (empty($data_rfc)) {
			$msg    = "Tidak Ada Data yang diproses";
			$sts    = "NotOK";
			$return = array('sts' => $sts, 'msg' => $msg);
			echo json_encode($return);
			exit();
		}
		foreach ($data_rfc as $dt) {
			if ($this->data['sap']->getStatus() == SAPRFC_OK) {
				$result = $this->data['sap']->callFunction(
					"Z_RFC_P2P_EXTEND_VENDOR",
					array(
						array("IMPORT", "I_LIFNR", $_POST['I_LIFNR']),
						array("IMPORT", "I_BUKRS", $dt->BUKRS),
						array("IMPORT", "I_EKORG", $dt->EKORG),
						array("IMPORT", "I_KTOKK", $_POST['I_KTOKK']),
						array("IMPORT", "I_BUKRS_REF", $_POST['I_BUKRS_REF']),
						array("IMPORT", "I_EKORG_REF", $_POST['I_EKORG_REF']),
						array("TABLE", "T_RETURN", array())
					)
				);

				// echo json_encode($result);
				// exit();

				if ($result['T_RETURN'][1]['TYPE'] == 'S') {    //val:S(jika sukses)
					//update tbl_vendor_data
					$string = "
						update
						tbl_vendor_data
						set 
						extend='n'
						where id_data='$id_data'
					";
					$query  = $this->db->query($string);
					//update tbl_vendor_plant
					$string = "
						update
						tbl_vendor_plant
						set 
						status_sap='y'
						where id_data='$id_data' and plant='" . $dt->EKORG . "' and status_sap='n'
					";
					$query  = $this->db->query($string);
					//log rfc
					$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
					$data_row_log = array(
						'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_P2P_CREATE_VENDOR',
						'log_code'      => '',
						'log_status'    => 'Berhasil',
						'log_desc'      => $status,
						'executed_by'   => 0,
						'executed_date' => $datetime
					);
					$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
				} else {        //jika gagal
					$data_row = array(
						'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
						'rfc_name'      => 'Z_RFC_P2P_CREATE_VENDOR',
						'log_code'      => $result['T_RETURN'][1]['TYPE'],
						'log_status'    => 'Gagal',
						'log_desc'      => $result['T_RETURN'][1]['MESSAGE'],
						'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
						'executed_date' => $datetime
					);
					$this->dgeneral->insert('tbl_log_rfc', $data_row);
				}
			} else {
				$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
				$data_row_log = array(
					'app'           => 'DATA RFC MASTER VENDOR PORTAL TO SAP',
					'rfc_name'      => 'Z_RFC_P2P_CREATE_VENDOR',
					'log_code'      => 'E',
					'log_status'    => 'Gagal',
					'log_desc'      => $status,
					'executed_by'   => 0,
					'executed_date' => $datetime
				);
				$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
			}
		}
		//================================SAVE ALL================================//
		if ($result['T_RETURN'][1]['TYPE'] == 'S') {
			$this->dgeneral->commit_transaction();
			$msg = "Pembuatan Kode Material SAP berhasil.";
			$sts = "OK";
		} else {
			$this->dgeneral->rollback_transaction();
			$msg = $code . ' ' . $result['T_RETURN'][1]['MESSAGE'];
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	//plantation
    //send po ho
    private function set_plantation_po_ho($param = NULL)
    {
        $this->load->model('plantation/dtransaksi', 'dtransaksi');

        $datetime = date("Y-m-d H:i:s");

        $post = $this->input->post(NULL, TRUE);

        $type = array();
        $message = array();
        $data_send = array();
        $iserror = false;

        $msg = "OK";
        $sts = "OK";

        $this->general->connectDbPortal();

        $list = $this->dtransaksi->get_queue_data_to_sap(array(
			"tipe" => 'POHO',
        ));

        if (!empty($list)) {
            $this->dgeneral->begin_transaction();
            foreach ($list as $data) {
                $this->connectSAP("ERP_310");
                if ($this->data['sap']->getStatus() == SAPRFC_OK) {
					$header = $this->dtransaksi->get_po_header(array(
						"id_po" => $data->id_transaksi,
						"IN_tipe" => ['HO'],
						"status_sap" => 'not_completed',
					));

                    $detail = $this->dtransaksi->get_po_detail(array(
                        'id_po' => $header->id
                    ));

                    $table = array();
                    foreach ($detail as $i => $item) {
                        if ($item->classification == "A") {
                            for ($i = 0; $i < $item->jumlah; $i++) {
                                $detail  = array(
                                    "PPBNU" => $header->no_ppb,
                                    "PPBIT" => $item->no_detail,
                                    "LIFNR" => $header->vendor,
                                    "MATNR" => $item->kode_barang,
                                    "MENGE" => 1,
                                    "MEINS" => $item->satuan,
                                    "EKORG" => $header->plant,
                                    "BEDAT" => date_format(date_create($header->tanggal), "Ymd"),
                                    "EDATU" => date_format(date_create($header->tanggal), "Ymd"),
                                    "NETPR" => (float) $item->harga,
                                    "WAERS" => "IDR",
                                    "PEINH" => 1,
                                    "EKGRP" => "OTH",
                                    "KNTTP" => ($item->classification == "A" || $item->classification == "K") ? $item->classification : "",
                                    "ANLKL" => $item->asset_class,
                                    "KOSTL" => $item->cost_center,
                                    "TXT50" => $item->nama_barang,
                                    "SAKNR" => $item->gl_account,
                                    "BUKRS" => "",
                                    "ANLN1" => "",
                                    "ANLN2" => "",
                                    "AUFNR" => "",
                                    "HDDIS" => ($item->diskon && $item->diskon > 0) ? ($item->diskon / $item->jumlah) : 0,
                                    "MWSKZ" => ($header->ppn && $header->ppn !== 0) ? $header->ppn : "",
                                );
                                $table[] = $detail;
                            }
                        } else {
                            $detail  = array(
                                "PPBNU" => $header->no_ppb,
                                "PPBIT" => $item->no_detail,
                                "LIFNR" => $header->vendor,
                                "MATNR" => $item->kode_barang,
                                "MENGE" => $item->jumlah,
                                "MEINS" => $item->satuan,
                                "EKORG" => $header->plant,
                                "BEDAT" => date_format(date_create($header->tanggal), "Ymd"),
                                "EDATU" => date_format(date_create($header->tanggal), "Ymd"),
                                "NETPR" => (float) $item->harga,
                                "WAERS" => "IDR",
                                "PEINH" => 1,
                                "EKGRP" => "OTH",
                                "KNTTP" => ($item->classification == "A" || $item->classification == "K") ? $item->classification : "",
                                "ANLKL" => $item->asset_class,
                                "KOSTL" => $item->cost_center,
                                "TXT50" => $item->nama_barang,
                                "SAKNR" => $item->gl_account,
                                "BUKRS" => "",
                                "ANLN1" => "",
                                "ANLN2" => "",
                                "AUFNR" => "",
                                "HDDIS" => $item->diskon,
                                "MWSKZ" => ($header->ppn && $header->ppn !== 0) ? $header->ppn : "",
                            );
                            $table[] = $detail;
                        }
                    }

                    $param_rfc = array(
                        array("IMPORT", "I_PPBNU", $header->no_ppb),
                        array("IMPORT", "I_EKORG", $header->plant),
                        array("TABLE", "T_RETURN", array()),
                        array("TABLE", "T_DATAPO", $table),
                        array("EXPORT", "E_INFNU", array()),
                        array("EXPORT", "E_EBELN", array()),
                    );

                    $iserror = false;
                    $result = $this->data['sap']->callFunction('Z_RFC_PAS_PODATA_SUBMIT', $param_rfc);

					

                    //cek kalo ada error
                    if (!empty($result["T_RETURN"])) {
                        foreach ($result["T_RETURN"] as $return) {
                            if ("E" == $return['TYPE']) {
                                $iserror = true;
                                break;
                            }
                        }
                    }

                    if (
						$this->data['sap']->getStatus() == SAPRFC_OK
						&& ($result["E_INFNU"] && $result["E_INFNU"] !== 0)
						&& ($result["E_EBELN"] && $result["E_EBELN"] !== "")
						// && empty($result["T_RETURN"])
						&& !$iserror
					) {
						$data_row_log = array(
							'app'           => 'DATA RFC CREATE PO HO',
							'rfc_name'      => 'Z_RFC_PAS_PODATA_SUBMIT',
							'log_code'      => 'S',
							'log_status'    => 'Berhasil',
							'log_desc'      => "Berhasil Create PO",
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);

						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

						$message_sap = [];

						if ($result["T_RETURN"]) {
							foreach ($result["T_RETURN"] as $return) {
								if($return['TYPE'] === "S")
									$message_sap[] = $return['MESSAGE'];
							}
						}

						$data_sap = array(
							'done_kirim_sap' => true,
							'tanggal_kirim_sap' => $datetime,
							'status_sap' => 'success',
							'keterangan_sap' => implode(" , ", $message_sap),
							'id_reference_sap' => $result["E_INFNU"],
							'no_po' => $result["E_EBELN"],
							"po_reff" => $result['E_EBELN'],
						);

						$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
						$this->dgeneral->update("tbl_ktp_po_header", $data_sap, array(
							array(
								'kolom' => 'id',
								'value' => $header->id
							)
						));

						//======Set line item PO======//
						$tb_item_po = array();
						foreach ($result["T_DATAPO"] as $dt) {
							$key = $header->id . '**' . $dt['MATNR'];
	
							if (array_key_exists($key, $tb_item_po) === FALSE) {
								$tb_item_po[$key]['item_po'] = array();
							}
							if ($dt['EBELP'] && $dt['EBELP'] != "") {
								$tb_item_po[$key]['item_po'][] = $dt['EBELP'];
							}
						}
	
						foreach ($tb_item_po as $key => $value) {
							$item_po = array_filter($value['item_po'], function ($v) {
								return trim($v);
							});
	
	
							$data_sap_detail = array(
								'item_po' => empty($item_po) ? NULL : implode(",", $item_po),
								'id_reference_sap' => $result["E_INFNU"]
							);
	
							$index = explode("**", $key);
							$data_sap_detail = $this->dgeneral->basic_column('update', $data_sap_detail, $datetime);
							$this->dgeneral->update("tbl_ktp_po_detail", $data_sap_detail, array(
								array(
									'kolom' => 'id_po',
									'value' => $header->id
								),
								array(
									'kolom' => 'kode_barang',
									'value' => $index[1]
								)
							));
						}
					} else {
						$msg_fail = array();
						$type_fail = array();
						if ($result["T_RETURN"]) {
							foreach ($result["T_RETURN"] as $return) {
								$type[]    = $return['TYPE'];
								$message[] = $return['MESSAGE'];
								$type_fail[] = $return['TYPE'];
								$msg_fail[] = $return['MESSAGE'];
							}
						} else {
							$type[]    = 'E';
							$message[] = "Error tanpa message";//$result;
							$type_fail[] = 'E';
							$msg_fail[] = "Error tanpa message";//$result;
						}
						$data_row_log = array(
							'app'           => 'DATA RFC CREATE PO HO',
							'rfc_name'      => 'Z_RFC_PAS_PODATA_SUBMIT',
							'log_code'      => implode(" , ", $type_fail),
							'log_status'    => 'Gagal',
							'log_desc'      => "Create PO Failed [T_RETURN]: " . implode(" , ", $msg_fail),
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);

						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

						$data_sap = array(
							'done_kirim_sap' => true,
							'tanggal_kirim_sap' => $datetime,
							'status_sap' => 'fail',
							'keterangan_sap' => implode(" , ", $msg_fail),
						);

						$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
						$this->dgeneral->update("tbl_ktp_po_header", $data_sap, array(
							array(
								'kolom' => 'id',
								'value' => $header->id
							)
						));
						break;
					}
                    $data_send[] = $param_rfc;
                } else {
                    $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
                    $data_row_log = array(
                        'app'           => 'DATA RFC CREATE PO HO',
                        'rfc_name'      => 'Z_RFC_PAS_PODATA_SUBMIT',
                        'log_code'      => 'E',
                        'log_status'    => 'Gagal',
                        'log_desc'      => "Connecting Failed: " . $status,
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );
    
                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
					break;
                }

                $this->data['sap']->logoff();
            }

            //================================SAVE ALL================================//
            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "OK";
                $sts = "OK";
                if (in_array('E', $type) === true) {
                    $sts = "NotOK";
                    $msg = implode(", ", $message);
                }
            }
        }

        $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        } else {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        }
        // return $return;
        echo json_encode($return);
        exit();
    }

    //send ttg dari po ho
    private function set_plantation_gr_ho($param = NULL)
    {
        $this->load->model('plantation/dtransaksi', 'dtransaksi');

        $datetime = date("Y-m-d H:i:s");

        $post = $this->input->post(NULL, TRUE);

        $type = array();
        $message = array();
        $data_send = array();
        $iserror = false;

        $msg = "OK";
        $sts = "OK";

        $this->general->connectDbPortal();

        $data = $this->dtransaksi->get_gr_header(array(
            "IN_tipe" => ['HO'],
            "status_sap" => "not_completed",
        ));

        if (!empty($data)) {
            $this->dgeneral->begin_transaction();
            foreach ($data as $header) {
                $this->connectSAP("ERP_310");
                if ($this->data['sap']->getStatus() == SAPRFC_OK) {
                    $detail = $this->dtransaksi->get_gr_detail(array(
                        'id_gr' => $header->id
                    ));

                    $table = array();
                    foreach ($detail as $item) {
                        $detail  = array(
                            "BUDAT" => date_format(date_create($header->tanggal), "Ymd"),
                            "MATNR" => $item->kode_barang,
                            "WERKS" => $header->plant,
                            "LGORT" => $item->sloc,
                            "MENGE" => $item->jumlah,
                            "MEINS" => $item->satuan,
                            "EBELN" => $header->no_po,
                            "EBELP" => $item->item_po,
                        );
                        $table[] = $detail;
                    }

                    $param_rfc = array(
                        array("IMPORT", "I_TTGNU", $header->no_gr),
                        array("IMPORT", "I_PPBNU", ""),
                        array("IMPORT", "I_WERKS", $header->plant),
                        array("TABLE", "T_RETURN", array()),
                        array("TABLE", "T_DATAGR", $table),
                        array("EXPORT", "E_INFNU", array()),
						array("EXPORT", "E_MBLNR", array()),
                        array("EXPORT", "E_MJAHR", array()),
                    );

                    $iserror = false;
                    $result = $this->data['sap']->callFunction('Z_RFC_PAS_GRDATA_SUBMIT', $param_rfc);

                    //cek kalo ada error
                    if (!empty($result["T_RETURN"])) {
                        foreach ($result["T_RETURN"] as $return) {
                            if ("E" == $return['TYPE']) {
                                $iserror = true;
                                break;
                            }
                        }
                    }

                    if (
                        $this->data['sap']->getStatus() == SAPRFC_OK
                        && ($result["E_INFNU"] && $result["E_INFNU"] != 0)
                        && empty($result["T_RETURN"])
                        && !$iserror
                    ) {
                        $data_row_log = array(
                            'app'           => 'DATA RFC CREATE GR PO HO',
                            'rfc_name'      => 'Z_RFC_PAS_GRDATA_SUBMIT',
                            'log_code'      => 'S',
                            'log_status'    => 'Berhasil',
                            'log_desc'      => "Berhasil Create GR PO HO",
                            'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                            'executed_date' => $datetime
                        );

                        $this->dgeneral->insert("tbl_log_rfc", $data_row_log);

                        $data_sap = array(
                            'done_kirim_sap' => true,
                            'tanggal_kirim_sap' => $datetime,
                            'status_sap' => 'success',
                            'keterangan_sap' => '',
                            'id_reference_sap' => $result["E_INFNU"]
                        );

                        $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                        $this->dgeneral->update("tbl_ktp_gr_header", $data_sap, array(
                            array(
                                'kolom' => 'id',
                                'value' => $header->id
                            )
                        ));

                        $data_sap_detail = array(
                            'id_reference_sap' => $result["E_INFNU"]
                        );
                        $data_sap_detail = $this->dgeneral->basic_column('update', $data_sap_detail, $datetime);
                        $this->dgeneral->update("tbl_ktp_gr_detail", $data_sap_detail, array(
                            array(
                                'kolom' => 'id_gr',
                                'value' => $header->id
                            )
                        ));
                    } else {
                        $msg_fail = array();
                        $type_fail = array();
                        if ($result["T_RETURN"]) {
                            foreach ($result["T_RETURN"] as $return) {
                                $type[]    = $return['TYPE'];
                                $message[] = $return['MESSAGE'];
                                $type_fail[] = $return['TYPE'];
                                $msg_fail[] = $return['MESSAGE'];
                            }
                        } else {
                            $type[]    = 'E';
                            $message[] = "Error tanpa message";//$result;
                            $type_fail[] = 'E';
                            $msg_fail[] = "Error tanpa message";//$result;
                        }
                        $data_row_log = array(
                            'app'           => 'DATA RFC CREATE GR PO HO',
                            'rfc_name'      => 'Z_RFC_PAS_GRDATA_SUBMIT',
                            'log_code'      => implode(" , ", $type_fail),
                            'log_status'    => 'Gagal',
                            'log_desc'      => "Create GR Failed [T_RETURN]: " . implode(" , ", $msg_fail),
                            'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                            'executed_date' => $datetime
                        );

                        $this->dgeneral->insert("tbl_log_rfc", $data_row_log);

                        $data_sap = array(
                            'done_kirim_sap' => true,
                            'tanggal_kirim_sap' => $datetime,
                            'status_sap' => 'fail',
                            'keterangan_sap' => implode(" , ", $msg_fail),
                        );

                        $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                        $this->dgeneral->update("tbl_ktp_gr_header", $data_sap, array(
                            array(
                                'kolom' => 'id',
                                'value' => $header->id
                            )
                        ));
                    }
                    $data_send[] = $data_row_log;
                } else {
                    $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
                    $data_row_log = array(
                        'app'           => 'DATA RFC CREATE GR PO HO',
                        'rfc_name'      => 'Z_RFC_PAS_GRDATA_SUBMIT',
                        'log_code'      => 'E',
                        'log_status'    => 'Gagal',
                        'log_desc'      => "Connecting Failed: " . $status,
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );
    
                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                }

                $this->data['sap']->logoff();
            }

            //================================SAVE ALL================================//
            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "OK";
                $sts = "OK";
                if (in_array('E', $type) === true) {
                    $sts = "NotOK";
                    $msg = implode(", ", $message);
                }
            }
        }

        $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        } else {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        }
        return $return;
    }

    //send po + ttg site
    private function set_plantation_po_site($param = NULL)
    {
        $this->load->model('plantation/dtransaksi', 'dtransaksi');

        $datetime = date("Y-m-d H:i:s");

        $post = $this->input->post(NULL, TRUE);

        $type = array();
        $message = array();
        $data_send = array();
        $param_send = array();
        $iserror = false;

        $msg = "OK";
        $sts = "OK";

        $this->general->connectDbPortal();

        $data = $this->dtransaksi->get_gr_header(array(
            "IN_tipe" => ['SITE'],
            "status_sap" => 'not_completed',
        ));

        if (!empty($data)) {
            $this->dgeneral->begin_transaction();
            foreach ($data as $header) {
                $this->connectSAP("ERP_310");
                if ($this->data['sap']->getStatus() == SAPRFC_OK) {
                    $header_po = $this->dtransaksi->get_po_header(array(
                        'id_po' => $header->id_po
                    ));

                    $detail = $this->dtransaksi->get_gr_detail(array(
                        'id_gr' => $header->id
                    ));

                    $table = array();
                    foreach ($detail as $i => $item) {
                        $detail  = array(
                            "TTGNU" => $header->no_gr,
                            "WERKS" => $header->plant,
                            "BUDAT" => date_format(date_create($header->tanggal), "Ymd"),
                            "LIFNR" => $header->vendor,
                            "MATNR" => $item->kode_barang,
                            "MENGE" => $item->jumlah,
                            "MEINS" => $item->satuan,
                            "NETPR" => (float) $item->harga,
                            "WAERS" => "IDR",
                            "PEINH" => 1,
                            "LGORT" => $item->sloc,
                            "EKGRP" => "OTH",
                            "KNTTP" => ($item->classification == "A" || $item->classification == "K") ? $item->classification : "",
                            "HDDIS" => ($item->classification == "A") ? ($item->diskon / $item->jumlah_po) : $item->diskon,
                            "MWSKZ" => ($header_po->ppn && $header_po->ppn !== 0) ? $header_po->ppn : "",
                            "ANLKL" => $item->asset_class,
                            "KOSTL" => $item->cost_center,
                            "TXT50" => $item->nama_barang,
                            "SAKNR" => $item->gl_account,
                        );
                        $table[] = $detail;
                    }

                    $param_rfc = array(
                        array("IMPORT", "I_WERKS", $header->plant),
                        array("IMPORT", "I_TTGNU", $header->no_gr),
                        array("TABLE", "T_RETURN", array()),
                        array("TABLE", "T_TTGDATA", $table),
                        array("EXPORT", "E_INFNU", array()),
						array("EXPORT", "E_MBLNR", array()),
                        array("EXPORT", "E_MJAHR", array()),
                    );

                    $iserror = false;
                    $result = $this->data['sap']->callFunction('Z_RFC_PAS_TTGDATA_SUBMIT', $param_rfc);

                    //cek kalo ada error
                    if (!empty($result["T_RETURN"])) {
                        foreach ($result["T_RETURN"] as $return) {
                            if ("E" == $return['TYPE']) {
                                $iserror = true;
                                break;
                            }
                        }
                    }

                    if (
                        $this->data['sap']->getStatus() == SAPRFC_OK
                        && ($result["E_INFNU"] && $result["E_INFNU"] != 0)
                        && empty($result["T_RETURN"])
                        && !$iserror
                    ) {
                        $data_row_log = array(
                            'app'           => 'DATA RFC CREATE PO SITE',
                            'rfc_name'      => 'Z_RFC_PAS_TTGDATA_SUBMIT',
                            'log_code'      => 'S',
                            'log_status'    => 'Berhasil',
                            'log_desc'      => "Berhasil Create PO",
                            'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                            'executed_date' => $datetime
                        );

                        $this->dgeneral->insert("tbl_log_rfc", $data_row_log);

                        $data_sap = array(
                            'done_kirim_sap' => true,
                            'tanggal_kirim_sap' => $datetime,
                            'status_sap' => 'success',
                            'keterangan_sap' => '',
                            'id_reference_sap' => $result["E_INFNU"]
                        );

                        $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                        $this->dgeneral->update("tbl_ktp_po_header", $data_sap, array(
                            array(
                                'kolom' => 'id',
                                'value' => $header->id_po
                            )
                        ));

                        $this->dgeneral->update("tbl_ktp_gr_header", $data_sap, array(
                            array(
                                'kolom' => 'id',
                                'value' => $header->id
                            )
                        ));

                        $data_sap_detail = array(
                            'id_reference_sap' => $result["E_INFNU"]
                        );
                        $data_sap_detail = $this->dgeneral->basic_column('update', $data_sap_detail, $datetime);
                        $this->dgeneral->update("tbl_ktp_po_detail", $data_sap_detail, array(
                            array(
                                'kolom' => 'id_po',
                                'value' => $header->id_po
                            )
                        ));

                        $this->dgeneral->update("tbl_ktp_gr_detail", $data_sap_detail, array(
                            array(
                                'kolom' => 'id_gr',
                                'value' => $header->id
                            )
                        ));
                    } else {
                        $msg_fail = array();
                        $type_fail = array();
                        if ($result["T_RETURN"]) {
                            foreach ($result["T_RETURN"] as $return) {
                                $type[]    = $return['TYPE'];
                                $message[] = $return['MESSAGE'];
                                $type_fail[] = $return['TYPE'];
                                $msg_fail[] = $return['MESSAGE'];
                            }
                        } else {
                            $type[]    = 'E';
                            $message[] = "Error tanpa message";//$result;
                            $type_fail[] = 'E';
                            $msg_fail[] = "Error tanpa message";//$result;
                        }
                        $data_row_log = array(
                            'app'           => 'DATA RFC CREATE PO SITE',
                            'rfc_name'      => 'Z_RFC_PAS_TTGDATA_SUBMIT',
                            'log_code'      => implode(" , ", $type_fail),
                            'log_status'    => 'Gagal',
                            'log_desc'      => "Create PO from " . $header->no_gr . " Failed [T_RETURN]: " . implode(" , ", $msg_fail),
                            'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                            'executed_date' => $datetime
                        );

                        $this->dgeneral->insert("tbl_log_rfc", $data_row_log);

                        $data_sap = array(
                            'done_kirim_sap' => true,
                            'tanggal_kirim_sap' => $datetime,
                            'status_sap' => 'fail',
                            'keterangan_sap' => implode(" , ", $msg_fail),
                        );

                        $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                        $this->dgeneral->update("tbl_ktp_gr_header", $data_sap, array(
                            array(
                                'kolom' => 'id',
                                'value' => $header->id
                            )
                        ));
                    }
                    $data_send[] = $data_row_log;
                    $param_send[] = $result;
                    // echo json_encode(['data'=>$result, 'status' => $status_io]);
                    // exit();
                } else {
                    $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
                    $data_row_log = array(
                        'app'           => 'DATA RFC CREATE PO SITE',
                        'rfc_name'      => 'Z_RFC_PAS_TTGDATA_SUBMIT',
                        'log_code'      => 'E',
                        'log_status'    => 'Gagal',
                        'log_desc'      => "Connecting Failed: " . $status,
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );
    
                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                }

                $this->data['sap']->logoff();
            } 

            //================================SAVE ALL================================//
            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "OK";
                $sts = "OK";
                if (in_array('E', $type) === true) {
                    $sts = "NotOK";
                    $msg = implode(", ", $message);
                }
            }
        }

        $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send, 'param' => $param_send]);
        } else {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send, 'param' => $param_send]);
        }
        return $return;
    }

    //send bkb
    private function set_plantation_gi($param = NULL)
    {
        $this->load->model('plantation/dtransaksi', 'dtransaksi');

        $datetime = date("Y-m-d H:i:s");

        $post = $this->input->post(NULL, TRUE);

        $type = array();
        $message = array();
        $data_send = array();
        $iserror = false;

        $msg = "OK";
        $sts = "OK";

        $this->general->connectDbPortal();

        $data = $this->dtransaksi->get_gi_header(array(
            "status_sap" => 'not_completed',
        ));

        if (!empty($data)) {
            $this->dgeneral->begin_transaction();
            foreach ($data as $header) {
                $this->connectSAP("ERP_310");
                if ($this->data['sap']->getStatus() == SAPRFC_OK) {
                    $detail = $this->dtransaksi->get_gi_detail(array(
                        'id_gi' => $header->id
                    ));

                    $table = array();
                    foreach ($detail as $item) {
                        $detail  = array(
                            // "PPBIT" => $item->no_detail,
                            "MATNR" => $item->kode_barang,
                            "MENGE" => $item->jumlah,
                            "MEINS" => $item->satuan,
                            "EKORG" => "",
                            "LGORT" => $item->sloc,
                            "KOSTL" => $item->cost_center,
                            "TXT50" => $item->nama_barang,
                            "SAKNR" => $item->gl_account,
                            "WERKS" => $header->plant,
                            "BUDAT" => date_format(date_create($header->tanggal), "Ymd"),
                            "BKBNU" => $header->no_gi,
                            "AUFNR" => $item->no_io
                        );
                        $table[] = $detail;
                    }

                    $param_rfc = array(
                        array("IMPORT", "I_WERKS", $header->plant),
                        array("IMPORT", "I_BKBNU", $header->no_gi),
                        array("TABLE", "T_RETURN", array()),
                        array("TABLE", "T_DATABKB", $table),
                        array("EXPORT", "E_INFNU", array()),
                        array("EXPORT", "E_MBLNR", array()),
                        array("EXPORT", "E_MJAHR", array()),
                    );

                    $iserror = false;
                    $result = $this->data['sap']->callFunction('Z_RFC_PAS_BKBDATA_SUBMIT', $param_rfc);

                    //cek kalo ada error
                    if (!empty($result["T_RETURN"])) {
                        foreach ($result["T_RETURN"] as $return) {
                            if ("E" == $return['TYPE']) {
                                $iserror = true;
                                break;
                            }
                        }
                    }

                    if (
                        $this->data['sap']->getStatus() == SAPRFC_OK
                        && ($result["E_INFNU"] && $result["E_INFNU"] != 0)
                        && empty($result["T_RETURN"])
                        && !$iserror
                    ) {
                        $data_row_log = array(
                            'app'           => 'DATA RFC CREATE GI',
                            'rfc_name'      => 'Z_RFC_PAS_BKBDATA_SUBMIT',
                            'log_code'      => 'S',
                            'log_status'    => 'Berhasil',
                            'log_desc'      => "Berhasil Create PO",
                            'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                            'executed_date' => $datetime
                        );

                        $this->dgeneral->insert("tbl_log_rfc", $data_row_log);

                        $data_sap = array(
                            'done_kirim_sap' => true,
                            'tanggal_kirim_sap' => $datetime,
                            'status_sap' => 'success',
                            'keterangan_sap' => '',
                            'id_reference_sap' => $result["E_INFNU"]
                        );

                        $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                        $this->dgeneral->update("tbl_ktp_gi_header", $data_sap, array(
                            array(
                                'kolom' => 'id',
                                'value' => $header->id
                            )
                        ));
                    } else {
                        $msg_fail = array();
                        $type_fail = array();
                        if ($result["T_RETURN"]) {
                            foreach ($result["T_RETURN"] as $return) {
                                $type[]    = $return['TYPE'];
                                $message[] = $return['MESSAGE'];
                                $type_fail[] = $return['TYPE'];
                                $msg_fail[] = $return['MESSAGE'];
                            }
                        } else {
                            $type[]    = 'E';
                            $message[] = "Error tanpa message";//$result;
                            $type_fail[] = 'E';
                            $msg_fail[] = "Error tanpa message";//$result;
                        }
                        $data_row_log = array(
                            'app'           => 'DATA RFC CREATE GI',
                            'rfc_name'      => 'Z_RFC_PAS_BKBDATA_SUBMIT',
                            'log_code'      => implode(" , ", $type_fail),
                            'log_status'    => 'Gagal',
                            'log_desc'      => "Create GI Failed [T_RETURN]: " . implode(" , ", $msg_fail),
                            'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                            'executed_date' => $datetime
                        );

                        $this->dgeneral->insert("tbl_log_rfc", $data_row_log);

                        $data_sap = array(
                            'done_kirim_sap' => true,
                            'tanggal_kirim_sap' => $datetime,
                            'status_sap' => 'fail',
                            'keterangan_sap' => implode(" , ", $msg_fail),
                        );

                        $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                        $this->dgeneral->update("tbl_ktp_gi_header", $data_sap, array(
                            array(
                                'kolom' => 'id',
                                'value' => $header->id
                            )
                        ));
                    }
                    $data_send[] = $result;
                    // echo json_encode(['data'=>$result, 'status' => $status_io]);
                    // exit();
                } else {
                    $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
                    $data_row_log = array(
                        'app'           => 'DATA RFC CREATE GI',
                        'rfc_name'      => 'Z_RFC_PAS_BKBDATA_SUBMIT',
                        'log_code'      => 'E',
                        'log_status'    => 'Gagal',
                        'log_desc'      => "Connecting Failed: " . $status,
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );
    
                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                }

                $this->data['sap']->logoff();
            }

            //================================SAVE ALL================================//
            if ($this->dgeneral->status_transaction() === false) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "OK";
                $sts = "OK";
                if (in_array('E', $type) === true) {
                    $sts = "NotOK";
                    $msg = implode(", ", $message);
                }
            }
        }

        $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        } else {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        }
        // return $return;
        echo json_encode($return);
        exit();
    }

    //send all ttg
    private function set_plantation_ttg($param = NULL)
    {
        $ttg_site = $this->set_plantation_po_site();
        $ttg_ho = $this->set_plantation_gr_ho();

        echo json_encode(['site' => $ttg_site, 'ho' => $ttg_ho]);
        exit();
    }

    //get document no
    private function get_plantation_document_number($param = NULL)
    {
        $this->load->model('plantation/dtransaksi', 'dtransaksi');

        $datetime = date("Y-m-d H:i:s");

        $post = $this->input->post(NULL, TRUE);

        $type = array();
        $message = array();
        $data_send = array();
        $iserror = false;

        $msg = "OK";
        $sts = "OK";

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();
        
        //=====PO====//
        $data_po = $this->dtransaksi->get_po_header(array(
            // "IN_tipe" => ['HO'],
            "no_sap" => 'not_completed',
        ));

        if (!empty($data_po)) {
            $this->connectSAP("ERP_310");
            if ($this->data['sap']->getStatus() == SAPRFC_OK) {
                $table = array();
                foreach ($data_po as $data) {
                    $list = array(
                        "INFNU" => $data->id_reference_sap
                    );
                    $table[] = $list;
                }

                $param_rfc = array(
                    array("TABLE", "T_DATA", $table),
                    array("TABLE", "T_RETURN", array()),
                    array("TABLE", "T_DETAIL", array()),
                );

                $iserror = false;
                $result = $this->data['sap']->callFunction('Z_PLINTERFACE_STATUS', $param_rfc);

                //cek kalo ada error
                if (!empty($result["T_RETURN"])) {
                    foreach ($result["T_RETURN"] as $return) {
                        if ("E" == $return['TYPE']) {
                            $iserror = true;
                            break;
                        }
                    }
                }

                if (
                    $this->data['sap']->getStatus() == SAPRFC_OK
                    // && ($result["E_INFNU"] && $result["E_INFNU"] != 0)
                    // && empty($result["T_RETURN"])
                    && !$iserror
                ) {
                    $data_row_log = array(
                        'app'           => 'DATA RFC GET DOCUMENT NO',
                        'rfc_name'      => 'Z_PLINTERFACE_STATUS',
                        'log_code'      => 'S',
                        'log_status'    => 'Berhasil',
                        'log_desc'      => "Berhasil GET NO PO",
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );

                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);

                    foreach ($result["T_DATA"] as $dt) {
                        if ($dt['EBELN'] && $dt['EBELN'] != "") {
                            if ($dt['INFTY'] == "P1") { //PO HO
                                $data_sap = array(
                                    'no_po' => $dt['EBELN'],
                                    "po_reff" => $dt['EBELN'],
                                );
                            } else {
                                $data_sap = array(
                                    'no_po' => $dt['EBELN'],
                                );
                            }

                            $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                            $this->dgeneral->update("tbl_ktp_po_header", $data_sap, array(
                                array(
                                    'kolom' => 'id_reference_sap',
                                    'value' => (int) $dt['INFNU']
                                )
                            ));
                        }
                    }

                    //======Set line item PO======//
                    $tb_item_po = array();
                    foreach ($result["T_DETAIL"] as $dt) {
                        $key = $dt['INFNU'] . '**' . $dt['MATNR'] . '**' . $dt['EKORG'];

                        if (array_key_exists($key, $tb_item_po) === FALSE) {
                            $tb_item_po[$key]['item_po'] = array();
                        }
                        if ($dt['EBELP'] && $dt['EBELP'] != "") {
                            $tb_item_po[$key]['item_po'][] = $dt['EBELP'];
                        }
                    }

                    foreach ($tb_item_po as $key => $value) {
                        $item_po = array_filter($value['item_po'], function ($v) {
                            return trim($v);
                        });


                        $data_sap = array(
                            'item_po' => empty($item_po) ? NULL : implode(",", $item_po),
                        );

                        $index = explode("**", $key);
                        $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                        $this->dgeneral->update("tbl_ktp_po_detail", $data_sap, array(
                            array(
                                'kolom' => 'id_reference_sap',
                                'value' => $index[0]
                            ),
                            array(
                                'kolom' => 'kode_barang',
                                'value' => $index[1]
                            )
                        ));
                    }
                } else {
                    $msg_fail = array();
                    $type_fail = array();
                    if ($result["T_RETURN"]) {
                        foreach ($result["T_RETURN"] as $return) {
                            $type[]    = $return['TYPE'];
                            $message[] = $return['MESSAGE'];
                            $type_fail[] = $return['TYPE'];
                            $msg_fail[] = $return['MESSAGE'];
                        }
                    } else {
                        $type[]    = 'E';
                        $message[] = "Error tanpa message";//$result;
                        $type_fail[] = 'E';
                        $msg_fail[] = "Error tanpa message";//$result;
                    }
                    $data_row_log = array(
                        'app'           => 'DATA RFC GET DOCUMENT NO',
                        'rfc_name'      => 'Z_PLINTERFACE_STATUS',
                        'log_code'      => implode(" , ", $type_fail),
                        'log_status'    => 'Gagal',
                        'log_desc'      => "GET NO PO Failed [T_RETURN]: " . implode(" , ", $msg_fail),
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );

                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                }
                $data_send[] = $data_row_log;
            } else {
                $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
                $data_row_log = array(
                    'app'           => 'DATA RFC GET DOCUMENT NO',
                    'rfc_name'      => 'Z_PLINTERFACE_STATUS',
                    'log_code'      => 'E',
                    'log_status'    => 'Gagal',
                    'log_desc'      => "Connecting Failed: " . $status,
                    'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                    'executed_date' => $datetime
                );
    
                $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
            }

            $this->data['sap']->logoff();
        }

        //=====GR====//
        $data_gr = $this->dtransaksi->get_gr_header(array(
            // "IN_tipe" => ['HO'],
            "no_sap" => 'not_completed',
        ));

        if (!empty($data_gr)) {
            $this->connectSAP("ERP_310");
            if ($this->data['sap']->getStatus() == SAPRFC_OK) {
                $table = array();
                foreach ($data_gr as $data) {
                    $list = array(
                        "INFNU" => $data->id_reference_sap
                    );
                    $table[] = $list;
                }

                $param_rfc = array(
                    array("TABLE", "T_DATA", $table),
                    array("TABLE", "T_RETURN", array()),
                );

                $iserror = false;
                $result = $this->data['sap']->callFunction('Z_PLINTERFACE_STATUS', $param_rfc);

                //cek kalo ada error
                if (!empty($result["T_RETURN"])) {
                    foreach ($result["T_RETURN"] as $return) {
                        if ("E" == $return['TYPE']) {
                            $iserror = true;
                            break;
                        }
                    }
                }

                if (
                    $this->data['sap']->getStatus() == SAPRFC_OK
                    && !$iserror
                ) {
                    $data_row_log = array(
                        'app'           => 'DATA RFC GET DOCUMENT NO',
                        'rfc_name'      => 'Z_PLINTERFACE_STATUS',
                        'log_code'      => 'S',
                        'log_status'    => 'Berhasil',
                        'log_desc'      => "Berhasil GET NO GR",
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );

                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);

                    foreach ($result["T_DATA"] as $dt) {
                        if ($dt['MBLNR'] && $dt['MBLNR'] != "") {
                            if ($dt['INFTY'] == "M2") { //TTG SITE
                                $data_sap = array(
                                    "no_gr_sap" => $dt['MBLNR'],
                                    //"po_reff" => $dt['EBELN'],
                                    "no_po" => $dt['EBELN'],
                                );
                            } else {
                                $data_sap = array(
                                    "no_gr_sap" => $dt['MBLNR'],
                                );
                            }

                            $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                            $this->dgeneral->update("tbl_ktp_gr_header", $data_sap, array(
                                array(
                                    'kolom' => 'id_reference_sap',
                                    'value' => (int) $dt['INFNU']
                                )
                            ));
                        }
                    }
                } else {
                    $msg_fail = array();
                    $type_fail = array();
                    if ($result["T_RETURN"]) {
                        foreach ($result["T_RETURN"] as $return) {
                            $type[]    = $return['TYPE'];
                            $message[] = $return['MESSAGE'];
                            $type_fail[] = $return['TYPE'];
                            $msg_fail[] = $return['MESSAGE'];
                        }
                    } else {
                        $type[]    = 'E';
                        $message[] = "Error tanpa message";//$result;
                        $type_fail[] = 'E';
                        $msg_fail[] = "Error tanpa message";//$result;
                    }
                    $data_row_log = array(
                        'app'           => 'DATA RFC GET DOCUMENT NO',
                        'rfc_name'      => 'Z_PLINTERFACE_STATUS',
                        'log_code'      => implode(" , ", $type_fail),
                        'log_status'    => 'Gagal',
                        'log_desc'      => "GET NO GR Failed [T_RETURN]: " . implode(" , ", $msg_fail),
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );

                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                }
                $data_send[] = $data_row_log;
            } else {
                $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
                $data_row_log = array(
                    'app'           => 'DATA RFC GET DOCUMENT NO',
                    'rfc_name'      => 'Z_PLINTERFACE_STATUS',
                    'log_code'      => 'E',
                    'log_status'    => 'Gagal',
                    'log_desc'      => "Connecting Failed: " . $status,
                    'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                    'executed_date' => $datetime
                );
    
                $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
            }

            $this->data['sap']->logoff();
        }

        //=====GI====//
        $data_gi = $this->dtransaksi->get_gi_header(array(
            // "IN_tipe" => ['HO'],
            "no_sap" => 'not_completed',
        ));

        if (!empty($data_gi)) {
            $this->connectSAP("ERP_310");
            if ($this->data['sap']->getStatus() == SAPRFC_OK) {
                $table = array();
                foreach ($data_gi as $data) {
                    $list = array(
                        "INFNU" => $data->id_reference_sap
                    );
                    $table[] = $list;
                }

                $param_rfc = array(
                    array("TABLE", "T_DATA", $table),
                    array("TABLE", "T_RETURN", array()),
                );

                $iserror = false;
                $result = $this->data['sap']->callFunction('Z_PLINTERFACE_STATUS', $param_rfc);

                //cek kalo ada error
                if (!empty($result["T_RETURN"])) {
                    foreach ($result["T_RETURN"] as $return) {
                        if ("E" == $return['TYPE']) {
                            $iserror = true;
                            break;
                        }
                    }
                }

                if (
                    $this->data['sap']->getStatus() == SAPRFC_OK
                    // && ($result["E_INFNU"] && $result["E_INFNU"] != 0)
                    // && empty($result["T_RETURN"])
                    && !$iserror
                ) {
                    $data_row_log = array(
                        'app'           => 'DATA RFC GET DOCUMENT NO',
                        'rfc_name'      => 'Z_PLINTERFACE_STATUS',
                        'log_code'      => 'S',
                        'log_status'    => 'Berhasil',
                        'log_desc'      => "Berhasil GET NO GI",
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );

                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);

                    foreach ($result["T_DATA"] as $dt) {
                        if ($dt['MBLNR'] && $dt['MBLNR'] != "") {
                            $data_sap = array(
                                'no_gi_sap' => $dt['MBLNR']
                            );

                            $data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
                            $this->dgeneral->update("tbl_ktp_gi_header", $data_sap, array(
                                array(
                                    'kolom' => 'id_reference_sap',
                                    'value' => (int) $dt['INFNU']
                                )
                            ));
                        }
                    }
                } else {
                    $msg_fail = array();
                    $type_fail = array();
                    if ($result["T_RETURN"]) {
                        foreach ($result["T_RETURN"] as $return) {
                            $type[]    = $return['TYPE'];
                            $message[] = $return['MESSAGE'];
                            $type_fail[] = $return['TYPE'];
                            $msg_fail[] = $return['MESSAGE'];
                        }
                    } else {
                        $type[]    = 'E';
                        $message[] = "Error tanpa message";//$result;
                        $type_fail[] = 'E';
                        $msg_fail[] = "Error tanpa message";//$result;
                    }
                    $data_row_log = array(
                        'app'           => 'DATA RFC GET DOCUMENT NO',
                        'rfc_name'      => 'Z_PLINTERFACE_STATUS',
                        'log_code'      => implode(" , ", $type_fail),
                        'log_status'    => 'Gagal',
                        'log_desc'      => "GET NO GI Failed [T_RETURN]: " . implode(" , ", $msg_fail),
                        'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                        'executed_date' => $datetime
                    );

                    $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
                }
                $data_send[] = $data_row_log;
            } else {
                $status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
                $data_row_log = array(
                    'app'           => 'DATA RFC GET DOCUMENT NO',
                    'rfc_name'      => 'Z_PLINTERFACE_STATUS',
                    'log_code'      => 'E',
                    'log_status'    => 'Gagal',
                    'log_desc'      => "Connecting Failed: " . $status,
                    'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
                    'executed_date' => $datetime
                );
    
                $this->dgeneral->insert("tbl_log_rfc", $data_row_log);
            }

            $this->data['sap']->logoff();
        } 

        //================================SAVE ALL================================//
        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "OK";
            $sts = "OK";
            if (in_array('E', $type) === true) {
                $sts = "NotOK";
                $msg = implode(", ", $message);
            }
        }

        $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        } else {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        }
        // return $return;
        echo json_encode($return);
        exit();
    }

	//send all transaction
    private function set_plantation_transaction($param = NULL)
    {
        $this->load->model('plantation/dtransaksi', 'dtransaksi');

        $datetime = date("Y-m-d H:i:s");

        $post = $this->input->post(NULL, TRUE);

        $type = array();
        $message = array();
        $data_send = array();
        $iserror = false;

        $msg = "OK";
        $sts = "OK";

        $this->general->connectDbPortal();

		$plant = ['AAP1', 'AAP2', 'PKP1', 'PKP2', 'KGK1'];
        // $list = $this->dtransaksi->get_queue_data_to_sap(array());

        // if (!empty($list)) {
		$this->dgeneral->begin_transaction();
		foreach($plant as $plant){
			$list = $this->dtransaksi->get_queue_data_to_sap(array(
				"connect" => false,
				"plant" => $plant,
			));

			if (!empty($list)) {
				foreach ($list as $data) {
					$this->connectSAP("ERP_310");
					if ($this->data['sap']->getStatus() == SAPRFC_OK) {
						if ($data->tipe === 'POHO') {
							$header = $this->dtransaksi->get_po_header(array(
								"id_po" => $data->id_transaksi,
								"IN_tipe" => ['HO'],
								"status_sap" => 'not_completed',
							));

							$detail = $this->dtransaksi->get_po_detail(array(
								'id_po' => $header->id
							));
		
							$table = array();
							foreach ($detail as $i => $item) {
								if ($item->classification == "A") {
									for ($i = 0; $i < $item->jumlah; $i++) {
										$detail  = array(
											"PPBNU" => $header->no_ppb,
											"PPBIT" => $item->no_detail,
											"LIFNR" => $header->vendor,
											"MATNR" => $item->kode_barang,
											"MENGE" => 1,
											"MEINS" => $item->satuan,
											"EKORG" => $header->plant,
											"BEDAT" => date_format(date_create($header->tanggal), "Ymd"),
											"EDATU" => date_format(date_create($header->tanggal), "Ymd"),
											"NETPR" => (float) $item->harga,
											"WAERS" => "IDR",
											"PEINH" => 1,
											"EKGRP" => "OTH",
											"KNTTP" => ($item->classification == "A" || $item->classification == "K") ? $item->classification : "",
											"ANLKL" => $item->asset_class,
											"KOSTL" => $item->cost_center,
											"TXT50" => $item->nama_barang,
											"SAKNR" => $item->gl_account,
											"BUKRS" => "",
											"ANLN1" => "",
											"ANLN2" => "",
											"AUFNR" => "",
											"HDDIS" => ($item->diskon && $item->diskon > 0) ? ($item->diskon / $item->jumlah) : 0,
											"MWSKZ" => ($header->ppn && $header->ppn !== 0) ? $header->ppn : "",
										);
										$table[] = $detail;
									}
								} else {
									$detail  = array(
										"PPBNU" => $header->no_ppb,
										"PPBIT" => $item->no_detail,
										"LIFNR" => $header->vendor,
										"MATNR" => $item->kode_barang,
										"MENGE" => $item->jumlah,
										"MEINS" => $item->satuan,
										"EKORG" => $header->plant,
										"BEDAT" => date_format(date_create($header->tanggal), "Ymd"),
										"EDATU" => date_format(date_create($header->tanggal), "Ymd"),
										"NETPR" => (float) $item->harga,
										"WAERS" => "IDR",
										"PEINH" => 1,
										"EKGRP" => "OTH",
										"KNTTP" => ($item->classification == "A" || $item->classification == "K") ? $item->classification : "",
										"ANLKL" => $item->asset_class,
										"KOSTL" => $item->cost_center,
										"TXT50" => $item->nama_barang,
										"SAKNR" => $item->gl_account,
										"BUKRS" => "",
										"ANLN1" => "",
										"ANLN2" => "",
										"AUFNR" => "",
										"HDDIS" => $item->diskon,
										"MWSKZ" => ($header->ppn && $header->ppn !== 0) ? $header->ppn : "",
									);
									$table[] = $detail;
								}
							}
		
							$param_rfc = array(
								array("IMPORT", "I_PPBNU", $header->no_ppb),
								array("IMPORT", "I_EKORG", $header->plant),
								array("TABLE", "T_RETURN", array()),
								array("TABLE", "T_DATAPO", $table),
								array("EXPORT", "E_INFNU", array()),
								array("EXPORT", "E_EBELN", array()),
							);
		
							$iserror = false;
							$result = $this->data['sap']->callFunction('Z_RFC_PAS_PODATA_SUBMIT', $param_rfc);
							
							$data_send[] = array('param' => @$param_rfc, "result" => @$result);
		
							//cek kalo ada error
							if (!empty($result["T_RETURN"])) {
								foreach ($result["T_RETURN"] as $return) {
									if ("E" == $return['TYPE']) {
										$iserror = true;
										break;
									}
								}
							}
		
							if (
								$this->data['sap']->getStatus() == SAPRFC_OK
								&& ($result["E_INFNU"] && $result["E_INFNU"] !== 0)
								&& ($result["E_EBELN"] && $result["E_EBELN"] !== "")
								// && empty($result["T_RETURN"])
								&& !$iserror
							) {
								$data_row_log = array(
									'app'           => 'DATA RFC CREATE PO HO',
									'rfc_name'      => 'Z_RFC_PAS_PODATA_SUBMIT',
									'log_code'      => 'S',
									'log_status'    => 'Berhasil',
									'log_desc'      => "Berhasil Create PO",
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
		
								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

								$message_sap = [];

								if ($result["T_RETURN"]) {
									foreach ($result["T_RETURN"] as $return) {
										if ($return['TYPE'] === "S")
											$message_sap[] = $return['MESSAGE'];
									}
								}
		
								$data_sap = array(
									'done_kirim_sap' => true,
									'tanggal_kirim_sap' => $datetime,
									'status_sap' => 'success',
									'keterangan_sap' => implode(" , ", $message_sap),
									'id_reference_sap' => $result["E_INFNU"],
									'no_po' => $result["E_EBELN"],
									"po_reff" => $result['E_EBELN'],
								);
		
								$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
								$this->dgeneral->update("tbl_ktp_po_header", $data_sap, array(
									array(
										'kolom' => 'id',
										'value' => $header->id
									)
								));

								//======Set line item PO======//
								$tb_item_po = array();
								foreach ($result["T_DATAPO"] as $dt) {
									$key = $header->id . '**' . $dt['MATNR'];
			
									if (array_key_exists($key, $tb_item_po) === FALSE) {
										$tb_item_po[$key]['item_po'] = array();
									}
									if ($dt['EBELP'] && $dt['EBELP'] != "") {
										$tb_item_po[$key]['item_po'][] = $dt['EBELP'];
									}
								}
			
								foreach ($tb_item_po as $key => $value) {
									$item_po = array_filter($value['item_po'], function ($v) {
										return trim($v);
									});
			
			
									$data_sap_detail = array(
										'item_po' => empty($item_po) ? NULL : implode(",", $item_po),
										'id_reference_sap' => $result["E_INFNU"]
									);
			
									$index = explode("**", $key);
									$data_sap_detail = $this->dgeneral->basic_column('update', $data_sap_detail, $datetime);
									$this->dgeneral->update("tbl_ktp_po_detail", $data_sap_detail, array(
										array(
											'kolom' => 'id_po',
											'value' => $header->id
										),
										array(
											'kolom' => 'kode_barang',
											'value' => $index[1]
										)
									));
								}
							} else {
								$msg_fail = array();
								$type_fail = array();
								if ($result["T_RETURN"]) {
									foreach ($result["T_RETURN"] as $return) {
										$type[]    = $return['TYPE'];
										$message[] = $return['MESSAGE'];
										$type_fail[] = $return['TYPE'];
										$msg_fail[] = $return['MESSAGE'];
									}
								} else {
									$type[]    = 'E';
									$message[] = "Error tanpa message"; //$result;
									$type_fail[] = 'E';
									$msg_fail[] = "Error tanpa message"; //$result;
								}
								$data_row_log = array(
									'app'           => 'DATA RFC CREATE PO HO',
									'rfc_name'      => 'Z_RFC_PAS_PODATA_SUBMIT',
									'log_code'      => implode(" , ", $type_fail),
									'log_status'    => 'Gagal',
									'log_desc'      => "Create PO Failed [T_RETURN]: " . implode(" , ", $msg_fail),
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
		
								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		
								$data_sap = array(
									'done_kirim_sap' => true,
									'tanggal_kirim_sap' => $datetime,
									'status_sap' => 'fail',
									'keterangan_sap' => implode(" , ", $msg_fail),
								);
		
								$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
								$this->dgeneral->update("tbl_ktp_po_header", $data_sap, array(
									array(
										'kolom' => 'id',
										'value' => $header->id
									)
								));
								break;
							}
							// $data_send[] = $result;
						} else if ($data->tipe === 'TTGHO') {
							$header = $this->dtransaksi->get_gr_header(array(
								"id_gr" => $data->id_transaksi,
								"IN_tipe" => ['HO'],
								"status_sap" => "not_completed",
							));

							$detail = $this->dtransaksi->get_gr_detail(array(
								'id_gr' => $header->id
							));
		
							$table = array();
							foreach ($detail as $item) {
								$detail  = array(
									"BUDAT" => date_format(date_create($header->tanggal), "Ymd"),
									"MATNR" => $item->kode_barang,
									"WERKS" => $header->plant,
									"LGORT" => $item->sloc,
									"MENGE" => $item->jumlah,
									"MEINS" => $item->satuan,
									"EBELN" => $header->no_po,
									"EBELP" => $item->item_po,
								);
								$table[] = $detail;
							}
		
							$param_rfc = array(
								array("IMPORT", "I_TTGNU", $header->no_gr),
								array("IMPORT", "I_PPBNU", ""),
								array("IMPORT", "I_WERKS", $header->plant),
								array("TABLE", "T_RETURN", array()),
								array("TABLE", "T_DATAGR", $table),
								array("EXPORT", "E_INFNU", array()),
								array("EXPORT", "E_MBLNR", array()),
								array("EXPORT", "E_MJAHR", array()),
							);
		
							$iserror = false;
							$result = $this->data['sap']->callFunction('Z_RFC_PAS_GRDATA_SUBMIT', $param_rfc);

							$data_send[] = array('param' => @$param_rfc, "result" => @$result);
		
							//cek kalo ada error
							if (!empty($result["T_RETURN"])) {
								foreach ($result["T_RETURN"] as $return) {
									if ("E" == $return['TYPE']) {
										$iserror = true;
										break;
									}
								}
							}
		
							if (
								$this->data['sap']->getStatus() == SAPRFC_OK
								&& ($result["E_INFNU"] && $result["E_INFNU"] !== 0)
								&& ($result["E_MBLNR"] && $result["E_MBLNR"] !== "")
								// && empty($result["T_RETURN"])
								&& !$iserror
							) {
								$data_row_log = array(
									'app'           => 'DATA RFC CREATE GR PO HO',
									'rfc_name'      => 'Z_RFC_PAS_GRDATA_SUBMIT',
									'log_code'      => 'S',
									'log_status'    => 'Berhasil',
									'log_desc'      => "Berhasil Create GR PO HO",
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
		
								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

								$message_sap = [];

								if ($result["T_RETURN"]) {
									foreach ($result["T_RETURN"] as $return) {
										if ($return['TYPE'] === "S")
											$message_sap[] = $return['MESSAGE'];
									}
								}
		
								$data_sap = array(
									'done_kirim_sap' => true,
									'tanggal_kirim_sap' => $datetime,
									'status_sap' => 'success',
									'keterangan_sap' => implode(" , ", $message_sap),
									'id_reference_sap' => $result["E_INFNU"],
									'no_gr_sap' => $result["E_MBLNR"],
								);
		
								$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
								$this->dgeneral->update("tbl_ktp_gr_header", $data_sap, array(
									array(
										'kolom' => 'id',
										'value' => $header->id
									)
								));
		
								$data_sap_detail = array(
									'id_reference_sap' => $result["E_INFNU"]
								);
								$data_sap_detail = $this->dgeneral->basic_column('update', $data_sap_detail, $datetime);
								$this->dgeneral->update("tbl_ktp_gr_detail", $data_sap_detail, array(
									array(
										'kolom' => 'id_gr',
										'value' => $header->id
									)
								));
							} else {
								$msg_fail = array();
								$type_fail = array();
								if ($result["T_RETURN"]) {
									foreach ($result["T_RETURN"] as $return) {
										$type[]    = $return['TYPE'];
										$message[] = $return['MESSAGE'];
										$type_fail[] = $return['TYPE'];
										$msg_fail[] = $return['MESSAGE'];
									}
								} else {
									$type[]    = 'E';
									$message[] = "Error tanpa message"; //$result;
									$type_fail[] = 'E';
									$msg_fail[] = "Error tanpa message"; //$result;
								}
								$data_row_log = array(
									'app'           => 'DATA RFC CREATE GR PO HO',
									'rfc_name'      => 'Z_RFC_PAS_GRDATA_SUBMIT',
									'log_code'      => implode(" , ", $type_fail),
									'log_status'    => 'Gagal',
									'log_desc'      => "Create GR Failed [T_RETURN]: " . implode(" , ", $msg_fail),
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
		
								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		
								$data_sap = array(
									'done_kirim_sap' => true,
									'tanggal_kirim_sap' => $datetime,
									'status_sap' => 'fail',
									'keterangan_sap' => implode(" , ", $msg_fail),
								);
		
								$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
								$this->dgeneral->update("tbl_ktp_gr_header", $data_sap, array(
									array(
										'kolom' => 'id',
										'value' => $header->id
									)
								));
								break;
							}
							// $data_send[] = $data_row_log;
						} else if ($data->tipe === 'TTGSITE') {
							$header = $this->dtransaksi->get_gr_header(array(
								"id_gr" => $data->id_transaksi,
								"IN_tipe" => ['SITE'],
								"status_sap" => 'not_completed',
							));

							$header_po = $this->dtransaksi->get_po_header(array(
								'id_po' => $header->id_po
							));
		
							$detail = $this->dtransaksi->get_gr_detail(array(
								'id_gr' => $header->id
							));
		
							$table = array();
							foreach ($detail as $i => $item) {
								$detail  = array(
									"TTGNU" => $header->no_gr,
									"WERKS" => $header->plant,
									"BUDAT" => date_format(date_create($header->tanggal), "Ymd"),
									"LIFNR" => $header->vendor,
									"MATNR" => $item->kode_barang,
									"MENGE" => $item->jumlah,
									"MEINS" => $item->satuan,
									"NETPR" => (float) $item->harga,
									"WAERS" => "IDR",
									"PEINH" => 1,
									"LGORT" => $item->sloc,
									"EKGRP" => "OTH",
									"KNTTP" => ($item->classification == "A" || $item->classification == "K") ? $item->classification : "",
									"HDDIS" => ($item->classification == "A") ? ($item->diskon / $item->jumlah_po) : $item->diskon,
									"MWSKZ" => ($header_po->ppn && $header_po->ppn !== 0) ? $header_po->ppn : "",
									"ANLKL" => $item->asset_class,
									"KOSTL" => $item->cost_center,
									"TXT50" => $item->nama_barang,
									"SAKNR" => $item->gl_account,
								);
								$table[] = $detail;
							}
		
							$param_rfc = array(
								array("IMPORT", "I_WERKS", $header->plant),
								array("IMPORT", "I_TTGNU", $header->no_gr),
								array("TABLE", "T_RETURN", array()),
								array("TABLE", "T_TTGDATA", $table),
								array("EXPORT", "E_INFNU", array()),
								array("EXPORT", "E_MBLNR", array()),
								array("EXPORT", "E_EBELN", array()),
							);
		
							$iserror = false;
							$result = $this->data['sap']->callFunction('Z_RFC_PAS_TTGDATA_SUBMIT', $param_rfc);

							$data_send[] = array('param' => @$param_rfc, "result" => @$result);

							//cek kalo ada error
							if (!empty($result["T_RETURN"])) {
								foreach ($result["T_RETURN"] as $return) {
									if ("E" == $return['TYPE']) {
										$iserror = true;
										break;
									}
								}
							}
		
							if (
								$this->data['sap']->getStatus() == SAPRFC_OK
								&& ($result["E_INFNU"] && $result["E_INFNU"] !== 0)
								&& ($result["E_EBELN"] && $result["E_EBELN"] !== "")
								&& ($result["E_MBLNR"] && $result["E_MBLNR"] !== "")
								// && empty($result["T_RETURN"])
								&& !$iserror
							) {
								$data_row_log = array(
									'app'           => 'DATA RFC CREATE PO SITE',
									'rfc_name'      => 'Z_RFC_PAS_TTGDATA_SUBMIT',
									'log_code'      => 'S',
									'log_status'    => 'Berhasil',
									'log_desc'      => "Berhasil Create PO",
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
		
								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

								$message_sap = [];

								if ($result["T_RETURN"]) {
									foreach ($result["T_RETURN"] as $return) {
										if ($return['TYPE'] === "S")
											$message_sap[] = $return['MESSAGE'];
									}
								}
		
								$data_sap = array(
									'done_kirim_sap' => true,
									'tanggal_kirim_sap' => $datetime,
									'status_sap' => 'success',
									'keterangan_sap' => implode(" , ", $message_sap),
									'id_reference_sap' => $result["E_INFNU"],
									"no_gr_sap" => $result['E_MBLNR'],
									"no_po" => $result['E_EBELN'],

								);
		
								$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
								$this->dgeneral->update("tbl_ktp_gr_header", $data_sap, array(
									array(
										'kolom' => 'id',
										'value' => $header->id
									)
								));

								unset($data_sap["no_gr_sap"]);
								$this->dgeneral->update("tbl_ktp_po_header", $data_sap, array(
									array(
										'kolom' => 'id',
										'value' => $header->id_po
									)
								));
		
								// $data_sap_detail = array(
								// 	'id_reference_sap' => $result["E_INFNU"]
								// );
								// $data_sap_detail = $this->dgeneral->basic_column('update', $data_sap_detail, $datetime);
								// $this->dgeneral->update("tbl_ktp_po_detail", $data_sap_detail, array(
								// 	array(
								// 		'kolom' => 'id_po',
								// 		'value' => $header->id_po
								// 	)
								// ));
		
								// $this->dgeneral->update("tbl_ktp_gr_detail", $data_sap_detail, array(
								// 	array(
								// 		'kolom' => 'id_gr',
								// 		'value' => $header->id
								// 	)
								// ));
							} else {
								$msg_fail = array();
								$type_fail = array();
								if ($result["T_RETURN"]) {
									foreach ($result["T_RETURN"] as $return) {
										$type[]    = $return['TYPE'];
										$message[] = $return['MESSAGE'];
										$type_fail[] = $return['TYPE'];
										$msg_fail[] = $return['MESSAGE'];
									}
								} else {
									$type[]    = 'E';
									$message[] = "Error tanpa message"; //$result;
									$type_fail[] = 'E';
									$msg_fail[] = "Error tanpa message"; //$result;
								}
								$data_row_log = array(
									'app'           => 'DATA RFC CREATE PO SITE',
									'rfc_name'      => 'Z_RFC_PAS_TTGDATA_SUBMIT',
									'log_code'      => implode(" , ", $type_fail),
									'log_status'    => 'Gagal',
									'log_desc'      => "Create PO from " . $header->no_gr . " Failed [T_RETURN]: " . implode(" , ", $msg_fail),
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);
		
								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
		
								$data_sap = array(
									'done_kirim_sap' => true,
									'tanggal_kirim_sap' => $datetime,
									'status_sap' => 'fail',
									'keterangan_sap' => implode(" , ", $msg_fail),
								);
		
								$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
								$this->dgeneral->update("tbl_ktp_gr_header", $data_sap, array(
									array(
										'kolom' => 'id',
										'value' => $header->id
									)
								));
								break;
							}
							// $data_send[] = $data_row_log;
							// $param_send[] = $result;
						} else if ($data->tipe === 'BKB') {
							$header = $this->dtransaksi->get_gi_header(array(
								"id_gi" => $data->id_transaksi,
								"status_sap" => 'not_completed',
							));
							$detail = $this->dtransaksi->get_gi_detail(array(
								'id_gi' => $header->id
							));

							$table = array();
							foreach ($detail as $item) {
								$detail  = array(
									// "PPBIT" => $item->no_detail,
									"MATNR" => $item->kode_barang,
									"MENGE" => $item->jumlah,
									"MEINS" => $item->satuan,
									"EKORG" => "",
									"LGORT" => $item->sloc,
									"KOSTL" => $item->cost_center,
									"TXT50" => $item->nama_barang,
									"SAKNR" => $item->gl_account,
									"WERKS" => $header->plant,
									"BUDAT" => date_format(date_create($header->tanggal), "Ymd"),
									"BKBNU" => $header->no_gi,
									"AUFNR" => $item->no_io,
									"NOVRA" => $item->kode_vra
								);
								$table[] = $detail;
							}

							$param_rfc = array(
								array("IMPORT", "I_WERKS", $header->plant),
								array("IMPORT", "I_BKBNU", $header->no_gi),
								array("TABLE", "T_RETURN", array()),
								array("TABLE", "T_DATABKB", $table),
								array("EXPORT", "E_INFNU", array()),
								array("EXPORT", "E_MBLNR", array()),
								array("EXPORT", "E_MJAHR", array()),
							);

							$iserror = false;
							$result = $this->data['sap']->callFunction('Z_RFC_PAS_BKBDATA_SUBMIT', $param_rfc);

							$data_send[] = array('param' => @$param_rfc, "result" => @$result);

							//cek kalo ada error
							if (!empty($result["T_RETURN"])) {
								foreach ($result["T_RETURN"] as $return) {
									if ("E" == $return['TYPE']) {
										$iserror = true;
										break;
									}
								}
							}

							if (
								$this->data['sap']->getStatus() == SAPRFC_OK
								&& ($result["E_INFNU"] && $result["E_INFNU"] != 0)
								&& ($result["E_MBLNR"] && $result["E_MBLNR"] !== "")
								// && empty($result["T_RETURN"])
								&& !$iserror
							) {
								$data_row_log = array(
									'app'           => 'DATA RFC CREATE GI',
									'rfc_name'      => 'Z_RFC_PAS_BKBDATA_SUBMIT',
									'log_code'      => 'S',
									'log_status'    => 'Berhasil',
									'log_desc'      => "Berhasil Create PO",
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);

								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

								$message_sap = [];

								if ($result["T_RETURN"]) {
									foreach ($result["T_RETURN"] as $return) {
										if ($return['TYPE'] === "S")
											$message_sap[] = $return['MESSAGE'];
									}
								}

								$data_sap = array(
									'done_kirim_sap' => true,
									'tanggal_kirim_sap' => $datetime,
									'status_sap' => 'success',
									'keterangan_sap' => implode(" , ", $message_sap),
									'id_reference_sap' => $result["E_INFNU"],
									'no_gi_sap' => $result["E_MBLNR"]
								);

								$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
								$this->dgeneral->update("tbl_ktp_gi_header", $data_sap, array(
									array(
										'kolom' => 'id',
										'value' => $header->id
									)
								));
							} else {
								$msg_fail = array();
								$type_fail = array();
								if ($result["T_RETURN"]) {
									foreach ($result["T_RETURN"] as $return) {
										$type[]    = $return['TYPE'];
										$message[] = $return['MESSAGE'];
										$type_fail[] = $return['TYPE'];
										$msg_fail[] = $return['MESSAGE'];
									}
								} else {
									$type[]    = 'E';
									$message[] = "Error tanpa message"; //$result;
									$type_fail[] = 'E';
									$msg_fail[] = "Error tanpa message"; //$result;
								}
								$data_row_log = array(
									'app'           => 'DATA RFC CREATE GI',
									'rfc_name'      => 'Z_RFC_PAS_BKBDATA_SUBMIT',
									'log_code'      => implode(" , ", $type_fail),
									'log_status'    => 'Gagal',
									'log_desc'      => "Create GI Failed [T_RETURN]: " . implode(" , ", $msg_fail),
									'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
									'executed_date' => $datetime
								);

								$this->dgeneral->insert("tbl_log_rfc", $data_row_log);

								$data_sap = array(
									'done_kirim_sap' => true,
									'tanggal_kirim_sap' => $datetime,
									'status_sap' => 'fail',
									'keterangan_sap' => implode(" , ", $msg_fail),
								);

								$data_sap = $this->dgeneral->basic_column('update', $data_sap, $datetime);
								$this->dgeneral->update("tbl_ktp_gi_header", $data_sap, array(
									array(
										'kolom' => 'id',
										'value' => $header->id
									)
								));
								//stop execute
								break;
							}
						}
						// $data_send[] = array('param' => @$param_rfc, "result" => @$result);
					} else {
						$status       = preg_replace('/\/n$/', '', $this->data['sap']->GetStatusText());
						$data_row_log = array(
							'app'           => 'DATA RFC SEND ALL KTP TRANSACTION',
							'rfc_name'      => 'Z_RFC_PAS_BKBDATA_SUBMIT',
							'log_code'      => 'E',
							'log_status'    => 'Gagal',
							'log_desc'      => "Connecting Failed: " . $status,
							'executed_by'   => base64_decode($this->session->userdata("-id_user-")),
							'executed_date' => $datetime
						);
						
						$this->dgeneral->insert("tbl_log_rfc", $data_row_log);
						break;
					}

					$this->data['sap']->logoff();
				}
			}
        }

		//================================SAVE ALL================================//
		if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "OK";
			$sts = "OK";
			if (in_array('E', $type) === true) {
				$sts = "NotOK";
				$msg = implode(", ", $message);
			}
		}

        $this->general->closeDb();

        if (isset($param['return']) && $param['return'] == 'array') {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        } else {
            $return = array('sts' => $sts, 'msg' => $msg, 'data' => ['type' => $type, 'message' => $message, 'data' => $data_send]);
        }
        // return $return;
        echo json_encode($return);
        exit();
    }
}
