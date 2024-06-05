<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Plantation
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
include_once APPPATH . "modules/plantation/controllers/BaseControllers.php";

class Master extends BaseControllers
{
    private $access_plant;
    private $site_ktp = ['AAP1', 'AAP2', 'PKP1', 'PKP2', 'KGK1'];
    
    function __construct()
    {
        parent::__construct();
        $this->load->model('dmaster');

        $this->access_plant = base64_decode($this->session->userdata("-gsber-"));
    }

    public function barang()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        $this->load->view("master/barang/page", $data);
    }

    public function material()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }

        $this->load->view("master/material/page", $data);
    }

    public function vendor()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }

        $this->load->view("master/vendor/page", $data);
    }

    public function io()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
        //===============================================/

        if ($data['user']->ho == 'y') {
            $data['pabrik'] = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $data['pabrik'] = array("PKP1", "PKP2");
            else
                $data['pabrik'] = array($data['user']->gsber);
        }
        
        $this->load->view("master/io/page", $data);
    }

    //==================================================//
    /*                    Get data                      */
    //==================================================//
    public function get($param = NULL)
    {
        $data['user'] = $this->general->get_data_user();
        if ($data['user']->ho == 'y') {
            $in_plant = $this->site_ktp;
        } else {
            if ($data['user']->gsber === 'PKP')
                $in_plant = array("PKP1", "PKP2");
            else
                $in_plant = array($data['user']->gsber);
        }

        switch ($param) {
            case 'barang':
                $param_mat = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "code" => $this->input->post("code", TRUE),
                    "is_active" => $this->input->post("is_active", TRUE),
                );

                $this->get_data_material($param_mat);
                break;
            case 'material':
                // $in_plant = in_array($this->access_plant, $this->site_ktp) ? array($this->access_plant) : NULL;

                $param_mat = array(
                    "connect" => TRUE,
                    "data" => $this->input->post("data", TRUE),
                    "return" => $this->input->post("return", TRUE),
                    "code" => $this->input->post("code", TRUE),
                    "is_active" => $this->input->post("is_active", TRUE),
                    "plant" => empty($this->input->post_get('plant', TRUE)) ? null : $this->input->post_get('plant', TRUE),
                    "IN_plant" => empty($this->input->post("IN_plant", TRUE)) ? $in_plant : $this->input->post("IN_plant", TRUE)
                );

                $this->get_data_material_by_plant($param_mat);
                break;
            case 'asset_class':
                $post = $this->input->post_get(NULL, TRUE);
                $data = $this->get_data_asset_class(
                    array(
                        "connect" => TRUE,
                        "search" => @$post['search'],
                        "distinct" => @$post['distinct'],
                        "classification" => @$post['classification'],
                        "return" => 'array'
                    )
                );
                $return  = array(
                    "total_count" => count($data),
                    "incomplete_results" => false,
                    "items" => $data
                );
                echo json_encode($return);
                break;
            case 'gl_account':
                $post = $this->input->post_get(NULL, TRUE);
                $data = $this->get_data_gl_account(
                    array(
                        "connect" => TRUE,
                        "search" => @$post['search'],
                        "distinct" => @$post['distinct'],
                        "classification" => @$post['classification'],
                        "return" => 'array'
                    )
                );
                $return  = array(
                    "total_count" => count($data),
                    "incomplete_results" => false,
                    "items" => $data
                );
                echo json_encode($return);
                break;
            case 'cost_center':
                $param_bom['connect'] = TRUE;
                $param_bom['master'] = $this->input->post_get("master", TRUE);
                $param_bom['type'] = $this->input->post_get("type", TRUE);
                $param_bom['GSBER'] = $this->input->post_get("plant", TRUE);
                $param_bom['GJAHR'] = $this->input->post_get("tahun", TRUE);
                $param_bom['MONAT'] = $this->input->post_get("bulan", TRUE);
                $param_bom['search'] = $this->input->post_get("search", TRUE);
                $param_bom['matnr'] = $this->input->post_get("matnr", TRUE);
                $param_bom['kdmat'] = $this->input->post_get("kdmat", TRUE);
                $param_bom['return'] = $this->input->post_get("return", TRUE);
                $param_bom['single_row'] = $this->input->post("single", TRUE);
                $this->get_data_master_cost($param_bom);
                break;
            case 'vendor':
                // $in_plant = in_array($this->access_plant, $this->site_ktp) ? array($this->access_plant) : NULL;

                $pr = array(
                    "connect" => TRUE,
                    "type" => 'vendor',
                    "search" => $this->input->post_get('search', TRUE),
                    "distinct" => $this->input->post_get('distinct', TRUE),
                    "plant" => empty($this->input->post_get('plant', TRUE)) ? null : $this->input->post_get('plant', TRUE),
                    "IN_plant" => empty($this->input->post("IN_plant", TRUE)) ? $in_plant : $this->input->post("IN_plant", TRUE),
                    "return" => $this->input->post_get("return", TRUE)
                );
                $this->get_data_master_cost($pr);
                break;
            case 'io':
                // $in_plant = in_array($this->access_plant, $this->site_ktp) ? array($this->access_plant) : NULL;

                $pr = array(
                    "connect" => TRUE,
                    "type" => 'io',
                    "search" => $this->input->post_get('search', TRUE),
                    "distinct" => $this->input->post_get('distinct', TRUE),
                    "plant" => empty($this->input->post_get('plant', TRUE)) ? null : $this->input->post_get('plant', TRUE),
                    "IN_plant" => empty($this->input->post("IN_plant", TRUE)) ? $in_plant : $this->input->post("IN_plant", TRUE),
                    "return" => $this->input->post_get("return", TRUE)
                );
                $this->get_data_master_cost($pr);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    //==================================================//
    /*                    Set data                      */
    //==================================================//
    public function set($param = NULL)
    {
        switch ($param) {

            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    //==================================================//
    /*                   Save data                      */
    //==================================================//
    public function save($param = NULL)
    {
        switch ($param) {
            case 'barang':
                $this->save_barang();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    /**********************************/
    /*			  private  			  */
    /**********************************/
    private function get_data_material($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dmaster->get_material($param);
                // echo json_encode($result);
                // exit();
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {

                                    $newData[] = $val;
                                }
                            } else {
                                $newData = $data;
                            }
                            $newResult[$key] = $newData;
                        }

                        $result = $newResult;
                        if (isset($param['return']) && $param['return'] == "datatables")
                            $result = $this->general->jsonify($result);
                    }
                }
                break;
            case 'complete':
                $result = $this->dmaster->get_material($param);
                unset($param['encrypt']);
                if ($result) {
                }
                break;

            default:
                $result = $this->dmaster->get_material($param);
                break;
        }

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
    }

    private function get_data_material_by_plant($param = NULL)
    {
        switch ($param['data']) {
            case 'header':
                $result = $this->dmaster->get_data_material_by_plant($param);
                // echo json_encode($result);
                // exit();
                if ($result) {
                    if (isset($param['return']) && $param['return'] == "datatables")
                        $result = json_decode($result, true);

                    if (is_object($result) === TRUE) {
                    } else {
                        $newResult = array();
                        foreach ($result as $key => $data) {
                            $newData = array();
                            if ($key == 'data') {
                                foreach ($data as $val) {

                                    $newData[] = $val;
                                }
                            } else {
                                $newData = $data;
                            }
                            $newResult[$key] = $newData;
                        }

                        $result = $newResult;
                        if (isset($param['return']) && $param['return'] == "datatables")
                            $result = $this->general->jsonify($result);
                    }
                }
                break;
            case 'complete':
                $result = $this->dmaster->get_data_material_by_plant($param);
                unset($param['encrypt']);
                if ($result) {
                }
                break;

            default:
                $result = $this->dmaster->get_data_material_by_plant($param);
                break;
        }

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else {
            return $result;
        }
    }

    private function get_data_asset_class($param = NULL)
	{
		$data = $this->dmaster->get_data_asset_class(
			array(
				"connect" => $param['connect'],
				"search" => @$param['search'],
				// "distinct" => @$param['distinct'],
				// "classification" => @$param['classification']
			)
		);

		if ($param['return'])
			switch ($param['return']) {
				case 'autocomplete':
					$data_material  = array(
						"total_count" => count($data),
						"incomplete_results" => false,
						"items" => $data
					);
					echo json_encode($data_material);
					break;

				default:
					return $data;
					break;
			}
	}

    private function get_data_gl_account($param = NULL)
	{
		$data = $this->dmaster->get_data_gl_account(
			array(
				"connect" => $param['connect'],
				"search" => @$param['search'],
				// "distinct" => @$param['distinct'],
				// "classification" => @$param['classification']
			)
		);

		if ($param['return'])
			switch ($param['return']) {
				case 'autocomplete':
					$data_material  = array(
						"total_count" => count($data),
						"incomplete_results" => false,
						"items" => $data
					);
					echo json_encode($data_material);
					break;

				default:
					return $data;
					break;
			}
	}

    private function get_data_master_cost($param = NULL)
    {
        switch ($param['type']) {
            case 'gl_account':
                $result = $this->dmaster->get_data_gl_account($param);
                break;
            case 'cost_center':
                $result = $this->dmaster->get_data_cost_center($param);
                break;
            case 'asset_class':
                $result = $this->dmaster->get_data_asset_class($param);
                break;
            case 'vendor':
                $result = $this->dmaster->get_data_vendor($param);
                break;
            case 'io':
                $result = $this->dmaster->get_data_io($param);
                break;
            default:
                $result = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($result);
                break;
        }

        if (isset($param['return']) && $param['return'] == "json") {
            echo json_encode($result);
        } else if (isset($param['return']) && $param['return'] == "datatables") {
            echo $result;
        } else if (isset($param['return']) && $param['return'] == "autocomplete") {
            if (isset($param['search'])) {
                $data_json = array(
                    "total_count"        => count($result),
                    "incomplete_results" => false,
                    "items"              => $result
                );

                $return = json_encode($data_json);
                $return = $this->general->jsonify($data_json);

                echo $return;
            }
        } else {
            return $result;
        }
    }

    private function save_barang($param = NULL)
    {
        $post 			= $this->input->post(NULL, TRUE);
		$datetime 	= date("Y-m-d H:i:s");

        $kode_barang = $post['kode_barang'];
        $asset_class = (isset($_POST['asset_class']) && $_POST['asset_class'] != "") ? $_POST['asset_class'] : NULL;
        $gl_account = (isset($_POST['gl_account']) && $_POST['gl_account'] != "") ? $_POST['gl_account'] : NULL;

        // cek kode barang
        $cek_data = $this->dmaster->get_data_mapping_material(array(
            "connect" => TRUE,
            "kode_barang" => $kode_barang
        ));

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = array(
            "id"	=> $kode_barang,
            "classification" => $_POST['tipe_barang'],
            "asset_class"	=> $asset_class,
            "gl_account"	=> $gl_account,
            "cost_center"  => empty($post['cost_center']) ? NULL : implode(";", $post['cost_center']),
        );

        if ($cek_data) {
            unset($data_row['id']);
            // $data_row = $this->dgeneral->basic_column("update", $data_row);
			$this->dgeneral->update("tbl_ktp_material", $data_row, array(
				array(
					'kolom' => 'id',
					'value' => $kode_barang
				)
			));
        } else {
            // $data_row = $this->dgeneral->basic_column("insert", $data_row);
			$this->dgeneral->insert("tbl_ktp_material", $data_row);
        }

        if ($this->dgeneral->status_transaction() === false) {
			$this->dgeneral->rollback_transaction();
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		} else {
			$this->dgeneral->commit_transaction();
			$msg = "Data berhasil ditambahkan";
			$sts = "OK";
		}
		$this->general->closeDb();
		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
    }
}