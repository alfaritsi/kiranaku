<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
@application  : Email Routing
@author     : Matthew Jodi
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

Class Master extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dmasteremail');
    }

    public function index()
    {
        show_404();
    }

    public function report()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        //===============================================/

        $data['user'] = $this->general->get_data_user();
        $data['report'] = $this->dmasteremail->get_data_report(NULL, NULL, 'all');
        $this->load->view('routing/master/report', $data);
    }

    public function topic()
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module'] = $this->router->fetch_module();
        //===============================================/

        $data['user'] = $this->general->get_data_user();
        $data['topic'] = $this->dmasteremail->get_data_topic(NULL, 'all');
        $this->load->view('routing/master/topic', $data);
    }

    //=================================//
    //         PROCESS FUNCTION        //
    //=================================//
    public function get_data($param)
    {
        switch ($param) {
            case 'report':
                $this->get_report();
                break;
            case 'topic':
                $this->get_topic();
                break;
            case 'report_by_periode':
                $this->get_report_by_periode();
                break;
            case 'user':
                $this->general->get_user_autocomplete();
                break;
			case 'users':
				$this->data_users();
				break;

            default:
                $return = array();
                echo json_encode($return);
                break;
        }
    }

    public function set_data($action, $param)
    {
        switch ($param) {
            case 'report':
                $this->set_report($action);
                break;
            case 'topic':
                $this->set_topic($action);
                break;

            default:
                $return = array();
                echo json_encode($return);
                break;
        }
    }

    public function save($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'report':
                $this->save_report($data);
                break;
            case 'topic':
                $this->save_topic($data);
                break;

            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    /**********************************/
    /*            private             */
    /**********************************/
    private function get_report()
    {
        $report = $this->dmasteremail->get_data_report($_POST['id_report'], NULL, 'all');
        echo json_encode($report);
    }
	
    private function data_users()
    {
        $data = $this->dmasteremail->get_data_report(NULL, NULL, NULL, $_POST['periode']);
        echo json_encode($data);
    }
    private function get_report_by_periode()
    {
        $data = $this->dmasteremail->get_data_report(NULL, NULL, NULL, $_POST['periode']);
        echo json_encode($data);
    }

    private function save_report($data)
    {
        $return = $this->dmasteremail->save_report($data);
        echo json_encode($return);
    }

    private function set_report($action)
    {
        $data = $_POST;
        switch ($action)
        {
            case "activate" :
                $return = $this->dmasteremail->set_report(
                    array(
                        "is_active" => 1,
                        "id_report" => $data['id_report']
                    )
                );
                echo json_encode($return);
                break;
            case "deactivate" :
                $return = $this->dmasteremail->set_report(
                    array(
                        "is_active" => 0,
                        "id_report" => $data['id_report']
                    )
                );
                echo json_encode($return);
                break;
        }


    }

    private function save_topic($data)
    {
        $return = $this->dmasteremail->save_topic($data);
        echo json_encode($return);
    }

    private function set_topic($action)
    {
        $data = $_POST;
        switch ($action)
        {
            case "activate" :
                $return = $this->dmasteremail->set_topic(
                    array(
                        "is_active" => 1,
                        "id_topic" => $data['id_topic']
                    )
                );
                echo json_encode($return);
                break;
            case "delete" :
                $return = $this->dmasteremail->set_topic(
                    array(
                        "is_active" => 0,
                        "id_topic" => $data['id_topic']
                    )
                );
                echo json_encode($return);
                break;
        }


    }

    //-------------------------------------------------//

    private function get_topic()
    {
        $topic = $this->dmasteremail->get_data_topic($_POST['id_topic'], NULL, 'all');
        echo json_encode($topic);
    }
}

?>