<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
        @application  :
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 21-Dec-18
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */

	class Tes extends MX_Controller {
		protected $maintenance;

		public function __construct() {
			parent::__construct();
			$this->load->model('dmasternusira');
			$this->load->model('dordernusira');
			$this->maintenance = 0;
		}

		public function set_sisa_budget() {
			$pi_budget = $this->dordernusira->get_pi_budget("open", $_POST['no_pi']);
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$tahun       = $_POST['tahun'];
			$plant       = $_POST['plant'];
			$no          = $_POST['no'];
			$ongkir      = $_POST['ongkir'];
			$budget_sisa = $this->dordernusira->get_data_budget(NULL, $tahun, $plant, $no, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "sisa", NULL, NULL, NULL, 'y', NULL);

			if ($pi_budget && $budget_sisa) {
				$budget_sisa_remaining  = $budget_sisa->remaining;
				$budget_sisa_before     = $budget_sisa->budget;
				$budget_sisa_before_ori = $budget_sisa->budget;
				$budget_sisa_all        = $budget_sisa_remaining;
				$no_detail              = NULL;

				$tes   = array();
				$halo  = array();
				$valid = array();
				$b     = 0;
				foreach ($pi_budget as $budg) {
					$b++;
					if ($budg->no_budget == $budget_sisa->no_budget) {
						$type = "reduce";
					}
					else {
						$type = "add";
						if ($budg->remaining <= 0) {
							$budget_sisa_remaining = $budg->remaining_budget_referensi;
							$budget_addition       = $budg->value_budget_referensi;
						}
						else {
							$budget_sisa_remaining += $budg->value_budget_referensi;
							$budget_addition       = $budg->value_budget_referensi;
						}
					}

					$array = array(
						"remaining" => 0
					);
					$this->dgeneral->update("tbl_pi_budget", $array, array(
						array(
							'kolom' => 'no_budget',
							'value' => $budg->no_budget
						)
					));

					$data_after = array(
						"no_budget_sisa"  => $budget_sisa->no_budget,
						"no_budget_add"   => $budg->no_budget,
						"no_pi"           => $budg->no_pi,
						"budget_addition" => $budget_addition, //penambahan ke sisa
						"before"          => $budget_sisa_before, //sebelum penambahan
						"after"           => $budget_sisa_all, //setelah penambahan
						"type"            => $type,
						"tanggal"         => date("Y-m-d H:i:s"),
					);
					$this->dgeneral->insert("tbl_pi_budget_maintenance_log", $data_after);

					$tes[] = $data_after;
					if ($no_detail !== $budg->no_detail || $b == count($pi_budget)) {
						$halo[] = $budget_sisa_all . "=>" . $budget_sisa_before . "=>" . $budget_sisa_remaining . "=>" . $b . "=>" . count($pi_budget);
						if ($no_detail !== $budg->no_detail && $budget_sisa_before_ori <= 0) {
							$valid['a'][]    = $budget_sisa_all . "+" . $budget_sisa_before;
							$budget_sisa_all += $budget_sisa_before;
						}
						if ($b == count($pi_budget) && $budget_sisa_before_ori > 0 || $budget_sisa_remaining > 0) {
							$valid['b'][]    = $budget_sisa_all . "+" . $budget_sisa_remaining;
							$budget_sisa_all += $budget_sisa_remaining;
						}
					}
					$budget_sisa_before = $budget_sisa_remaining;
					$no_detail          = $budg->no_detail;
				}

				$update = array(
					"remaining"    => (($budget_sisa_all - $ongkir) <= 0 ? 0 : ($budget_sisa_all - $ongkir)),
					"budget"       => (($budget_sisa_all - $ongkir) <= 0 ? 0 : ($budget_sisa_all - $ongkir)),
					'login_edit'   => 0,
					'tanggal_edit' => date("Y-m-d H:i:s")
				);
				$this->dgeneral->update("tbl_pi_budget", $update, array(
					array(
						'kolom' => 'no_budget',
						'value' => $no
					)
				));

				//update referensi budget sisa pada pi on progress
				$budget_sisa_on_progress = $this->dordernusira->get_pi_budget(NULL, NULL, $budget_sisa->no_budget, NULL, NULL, NULL, array("finish", "drop", "delete"));
				if ($budget_sisa_on_progress) {
					foreach ($budget_sisa_on_progress as $dt) {
						$this->dgeneral->insert('tbl_pi_referensi_budget_log', $dt);

						$array = array(
							"value_budget_referensi"     => $dt->value_budget_referensi + ($budget_sisa_remaining - $ongkir),
							"remaining_budget_referensi" => $dt->remaining_budget_referensi + ($budget_sisa_remaining - $ongkir)
						);

						$this->dgeneral->update("tbl_pi_referensi_budget", $array, array(
							array(
								'kolom' => 'no_budget',
								'value' => $budg->no_budget
							)
						));
					}
				}

				$data_after = array(
					"no_budget_sisa" => $budget_sisa->no_budget,
					"no_pi"          => $_POST['no_pi'],
					"before"         => $budget_sisa_all,
					"after"          => ($budget_sisa_all - $ongkir),
					"type"           => "reduce",
					"tanggal"        => date("Y-m-d H:i:s"),
				);
				$this->dgeneral->insert("tbl_pi_budget_maintenance_log", $data_after);
				$tes[] = $data_after;

				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				}
				else {
//					$this->dgeneral->commit_transaction();
					$this->dgeneral->rollback_transaction();
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}
				$this->general->closeDb();

				$return = array('sts' => $sts, 'msg' => $msg, 'data' => compact('valid', 'tes', 'halo', 'pi_budget'));
				echo json_encode($return);
				exit();
			}
		}

        public function set_sisa_budget_new() {
			$pi_budget = $this->dordernusira->get_pi_budget("open", $_POST['no_pi']);
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();
			$tahun       = $_POST['tahun'];
			$plant       = $_POST['plant'];
			$no          = $_POST['no'];
			$ongkir      = $_POST['ongkir'];
			$budget_sisa = $this->dordernusira->get_data_budget(NULL, $tahun, $plant, $no, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "sisa", NULL, NULL, NULL, 'y', NULL);

			if ($pi_budget && $budget_sisa) {
				$saldo_sisa				= $budget_sisa->remaining;
                $budget_sisa_remaining  = $budget_sisa->remaining;
                $budget_sisa_budget     = ($budget_sisa->budget != $budget_sisa->remaining ? $budget_sisa->remaining : $budget_sisa->budget);
                $budget_sisa_budget_ori = ($budget_sisa->budget != $budget_sisa->remaining ? $budget_sisa->remaining : $budget_sisa->budget);
                $budget_sisa_all		= $budget_sisa_remaining;
                $no_detail              = NULL;

                $test 	= array();
                $halo 	= array();
                $valid 	= array();
                $b     	= 0;

                $ongkir_budget = false;

                foreach ($pi_budget as $budg) {
                	$b++;
                    if ($budg->no_budget == $budget_sisa->no_budget) {
                        $type = "reduce";
                        //ketika budget sisa sedang dipakai PI lain dan remaining diupdate
						$budget_sisa_remaining += $budg->remaining_budget_referensi;
						$budget_addition       = $budg->value_budget_referensi;
						$budget_sisa_budget = $budget_sisa_remaining;
                    }
                    else {
                        $type = "add";
                        if ($budg->remaining  <= 0) {
                            $budget_sisa_remaining = $budg->remaining_budget_referensi;
                            $budget_addition       = $budg->value_budget_referensi;
                        }
                        else {
                            $budget_sisa_remaining += $budg->value_budget_referensi;
                            $budget_addition       = $budg->value_budget_referensi;
                            $budget_sisa_budget = $budget_sisa_remaining;
                        }
                    }

                    $matnr = preg_replace('/\s/', '', $budg->matnr);
                    if(empty($matnr)){
                    	$ongkir_budget = true;
                        $type = "add + reduce";
                    }

                    $array = array(
                        "remaining" => 0
                    );
                    $this->dgeneral->update("tbl_pi_budget", $array, array(
                        array(
                            'kolom' => 'no_budget',
                            'value' => $budg->no_budget
                        )
                    ));

                    if ($no_detail !== $budg->no_detail || $b == count($pi_budget)) {
                        $halo[] = $budg->no_budget . "=>" . $budget_sisa_all . "=>" . $budget_sisa_budget . "=>" . $budget_sisa_remaining . "=>" . $b . "=>" . count($pi_budget);
                        if ($no_detail !== $budg->no_detail && $budget_sisa_budget <= 0) {
                            $valid['a'][]    = $budget_sisa_all . "+" . $budget_sisa_budget;
                            $budget_sisa_all += $budget_sisa_budget;
                        }
                        if ($b == count($pi_budget) && $budget_sisa_budget_ori > 0 || $budget_sisa_remaining > 0) {
                            $valid['b'][]    = $budget_sisa_all . "+" . $budget_sisa_remaining;
                            $budget_sisa_all += $budget_sisa_remaining;
                        }
                        $no_detail          = $budg->no_detail;
                    }

                    $data_after[] = array(
                        "no_budget_sisa"  => $budget_sisa->no_budget,
                        "no_budget_add"   => $budg->no_budget,
                        "no_pi"           => $budg->no_pi,
                        "budget_addition" => $budget_addition, //penambahan ke sisa
                        "before"          => $budget_sisa_budget, //sebelum penambahan
                        "after"           => $budget_sisa_all, //setelah penambahan
                        "type"            => $type,
                        "tanggal"         => date("Y-m-d H:i:s"),
                        "no_detail"       => $no_detail
                    );
                    $test[] = $budget_sisa_remaining;
                }

                $update = array(
                    "remaining"    => (($budget_sisa_all - $ongkir) <= 0 ? 0 : ($budget_sisa_all - $ongkir)),
                    "budget"       => (($budget_sisa_all - $ongkir) <= 0 ? 0 : ($budget_sisa_all - $ongkir)),
                    'login_edit'   => 0,
                    'tanggal_edit' => date("Y-m-d H:i:s")
                );
                if($budget_sisa->budget != $budget_sisa->remaining){ //apabila budget sisa sedang dipakai sebagai referensi PI lain
                	unset($update['budget']);
				}
                $this->dgeneral->update("tbl_pi_budget", $update, array(
                    array(
                        'kolom' => 'no_budget',
                        'value' => $no
                    )
                ));

				//update referensi budget sisa pada pi on progress
				$budget_sisa_on_progress = $this->dordernusira->get_pi_budget(NULL, NULL, $budget_sisa->no_budget, NULL, NULL, NULL, array("finish", "drop", "delete"));
				if ($budget_sisa_on_progress) {
					foreach ($budget_sisa_on_progress as $dt) {
                    	$log	= array(
							"plant" => $dt->plant,
							"no_pi" => $dt->no_pi,
							"no_detail" => $dt->no_detail,
							"no_budget" => $dt->no_budget,
							"status_budget" => $dt->status_budget,
							"no_urut" => $dt->no_urut,
							"value_budget_referensi" => $dt->value_budget_referensi,
							"remaining_budget_referensi" => $dt->remaining_budget_referensi,
							"login_buat" => $dt->login_buat_referensi,
							"tanggal_buat" => $dt->tanggal_buat_referensi,
							"login_edit" => $dt->login_edit_referensi,
							"tanggal_edit" => $dt->tanggal_edit_referensi,
                    		"na" => 'n',
                    		"del" => 'n'
						);

						$this->dgeneral->insert('tbl_pi_referensi_budget_log', $log);

						$array = array(
							"value_budget_referensi"     => $dt->value_budget_referensi + ($budget_sisa_remaining - $ongkir),
							"remaining_budget_referensi" => $dt->remaining_budget_referensi + ($budget_sisa_remaining - $ongkir)
						);

						$this->dgeneral->update("tbl_pi_referensi_budget", $array, array(
							array(
								'kolom' => 'no_budget',
								'value' => $budg->no_budget
							)
						));
					}
                    $budget_sisa_on_progress = $this->dordernusira->get_pi_budget(NULL, NULL, $budget_sisa->no_budget, NULL, NULL, NULL, array("finish", "drop", "delete"));
				}

				if($ongkir_budget == false) {
                    $data_after[] = array(
					"no_budget_sisa" => $budget_sisa->no_budget,
					"no_pi"          => $_POST['no_pi'],
					"before"         => $budget_sisa_all,
					"after"          => ($budget_sisa_all - $ongkir),
					"type"           => "reduce",
					"tanggal"        => date("Y-m-d H:i:s"),
				);
                }
//                $this->dgeneral->insert("tbl_pi_budget_maintenance_log", $data_after);

				if ($this->dgeneral->status_transaction() === false) {
					$this->dgeneral->rollback_transaction();
					$msg = "Periksa kembali data yang dimasukkan";
					$sts = "NotOK";
				}
				else {
					$this->dgeneral->rollback_transaction();
					$msg = "Data berhasil ditambahkan";
					$sts = "OK";
				}
				$this->general->closeDb();

                $return = array('sts' => $sts, 'msg' => $msg, 'data' => compact('pi_budget', 'budget_sisa', 'test', 'data_after', 'halo', 'valid', 'budget_sisa_on_progress'));
				echo json_encode($return);
				exit();
			}
		}
	}

?>
