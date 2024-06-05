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
include_once APPPATH . "modules/nusira/controllers/BaseControllers.php";

class Dashboard extends BaseControllers
{
	public function __construct()
	{
		parent::__construct();
		$this->data['module'] = "NUSIRA WORKSHOP";
		$this->load->model('dmasternusira');
		$this->load->model('dordernusira');
		$this->load->model('dtransaksinusira');
		$this->load->model('dmonitoringsonusira');
	}

	public function page()
	{
		//====must be initiate in every view function====//
		$this->general->check_access();
		//===============================================//
		$this->data['title'] = "Dashboard Nusira Workshop";
		$this->load->view("dashboard/page", $this->data);
	}

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//
	public function get($param = NULL)
	{
		switch ($param) {
			case 'pi_pagination':
				$this->get_data_pi(
					array(
						"connect" => TRUE,
						"return" => "datatables"
					)
				);
				break;
			case 'pi':
				$this->get_data_pi(
					array(
						"connect" => TRUE,
						"return" => "json"
					)
				);
				break;
			case 'spk_late_pagination':
				$this->get_data_spk(
					array(
						"connect" => TRUE,
						"overdue" => TRUE,
						"jenis" => empty($this->input->post('filter')) ? NULL : $this->input->post('filter'),
						"return" => "datatables"
					)
				);
				break;
			case 'spk_late':
				$this->get_data_spk(
					array(
						"connect" => TRUE,
						"overdue" => TRUE,
						"jenis" => empty($this->input->post('filter')) ? NULL : $this->input->post('filter'),
						"return" => "json"
					)
				);
				break;
			case 'report_dashboard':
				header('Content-Type: application/json');
				$return = $this->dmonitoringsonusira->get_data_report_dashboard("open", $_POST['filter']);
				echo $return;
				break;
			case 'report_dashboard_detail':
				header('Content-Type: application/json');

				$return = $this->dmonitoringsonusira->get_data_report_dashboard_detail("open", $_POST['no_io']);
				echo $return;
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
	private function get_data_pi($param = NULL)
	{
		$return = $this->dtransaksinusira->get_pi_header(
			array(
				"connect" => $param['connect'],
				"app" => "nusira",
				"NOT_IN_status" => array('finish', 'drop', 'deleted'),
				"encrypt" => array("tujuan_inv"),
				"return" => $param['return']
			)
		);
		if (isset($param['return']) && $param['return'] == 'array') {
			return $return;
		} else if (isset($param['return']) && $param['return'] == 'datatables') {
			echo $return;
		} else {
			echo json_encode($return);
			exit();
		}
	}

	private function get_data_spk($param = NULL)
	{
		$return = $this->dtransaksinusira->get_data_spk(
			array(
				"connect" => $param['connect'],
				"overdue" => TRUE,
				"jenis" => $param['jenis'],
				"return" => $param['return']
			)
		);
		if (isset($param['return']) && $param['return'] == 'array') {
			return $return;
		} else if (isset($param['return']) && $param['return'] == 'datatables') {
			echo $return;
		} else {
			echo json_encode($return);
			exit();
		}
	}

	private function get_report_dashboard($conn = 'open', $array = NULL)
	{
		$return = $this->dmonitoringsonusira->get_data_report_dashboard($conn);
	}
}
