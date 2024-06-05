<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mrapp_functions
{
    protected $CI, $curl_base_url;

    protected $function_modules = array(
        "purchasing"
    );
    protected $function_excludes = array(
        "master"
    );

    protected $report = null;
    protected $post_data = null;
    protected $tanggal = null;
    protected $thresholds = null;
    protected $filters = array();
    protected $parameters = array();

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('general');
        $this->CI->load->library('mrapp_master');
        $this->CI->load->library('mrapp_format');
        $this->CI->load->model('dmrapp');
    }

    public function group_by_multiple($items, array $keySelectors, $valueSelector = null)
    {
        return $this->CI->mrapp_master->group_by_multiple($items, $keySelectors, $valueSelector);
    }

    public function prepare_filter($parameters = array(), $filters = array())
    {
        $preparedFilter = array();
        foreach ($parameters as $parameter) {
            $p = $parameter->parameter_alias;
            if (isset($filters->$p)) {
                if (is_array($filters->$p))
                    $preparedFilter[] = implode(',', $filters->$p);
                else
                    $preparedFilter[] = $filters->$p;
            } else if (!empty($parameter->parameter_default)) {
                $preparedFilter[] = $parameter->parameter_default;
            } else {
                return false;
            }
        }
        return $preparedFilter;
    }

    function cek_hari_kerja($tanggal_kerja)
    {
        //cek hari kerja untuk alert ocp
        $tanggal_kerja = date('Y-m-d', strtotime($tanggal_kerja . '-1 days'));
        $s = "select * from [10.0.0.32].SAPSYNC.dbo.ZKISSTT_0138 where ACTDT='$tanggal_kerja'";
        $q = $this->CI->db->query($s);
        $d = $q->row();
        if ($d->HOLDT == 'X') {
            $tanggal_kerja = date('Y-m-d', strtotime($tanggal_kerja . '-1 days'));
            $this->cek_hari_kerja($tanggal_kerja);
        }
        return $tanggal_kerja;
    }

    public function prepare_links($report = null)
    {
        $links = [];
        if (isset($report)) {
            $dataLinks = $this->CI->dmrapp->get_links($report->id_report);
            foreach ($dataLinks as $dataLink) {
                $links[] = [
                    "name" => $dataLink->nama_report,
                    "id_report" => $dataLink->id_report,
                    "params" => $this->CI->dmrapp->get_report_parameters($dataLink->id_report),
                    "type" => $dataLink->kode_type
                ];
            }
        }
        return $links;
    }

    public function getFunctionList()
    {
        $lists = array();
        foreach ($this->function_modules as $function_module) {
            $module = "mrapp_functions_" . $function_module;
            $this->CI->load->library($module);
            $module = strtolower($module);
            $methods = get_class_methods($this->CI->$module);
            foreach ($methods as $method) {
                $ref = new ReflectionMethod($this->CI->$module, $method);
                if ($ref->isProtected() && !in_array($ref->name, $this->function_excludes))
                    $lists[$ref->name] = strtoupper($ref->name);
            }
        }

        return $lists;
    }

    public function runFunction($method = null, $report = null, $data = null, $parameters = [])
    {
        if (method_exists($this, $method) && $method != "index") {

            if (isset($data['filters']))
                $filters = json_decode($data['filters']);
            else
                $filters = [];

            $this->report = $report;
            $this->post_data = $data;
            $this->filters = $filters;
            $this->parameters = $parameters;
            return $this->$method('report');
        } else {
            return false;
        }
    }

    public function runAlert($params = array())
    {
        $method = isset($params['method']) ? $params['method'] : null;
        $post_data = isset($params['post_data']) ? $params['post_data'] : null;
        $report = isset($params['report']) ? $params['report'] : null;
        $thresholds = isset($params['thresholds']) ? $params['thresholds'] : [];
        $tanggal_jalan = isset($params['tanggal_jalan']) ? $params['threshold'] : date('Y-m-d');

        if (method_exists($this, $method) && $method != "index") {

            $this->report = $report;
            $this->post_data = $post_data;
            $this->thresholds = $thresholds;
            $this->tanggal = $tanggal_jalan;

            return $this->$method('alert');
        } else {
            return false;
        }
    }

}