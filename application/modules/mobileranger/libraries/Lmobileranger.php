<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lmobileranger
{
    protected $CI, $curl_base_url;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->library('generate');
        $this->CI->load->model('dgeneral');
        $this->CI->load->helper('cookie');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function connectDbRanger()
    {
        $this->CI->load->config('dbotf');
        $dbConfig = $this->CI->config->item('ranger_db');
        $dbName = "RANGER";
        $dbConfig['database'] = $dbName;

        return $this->CI->db = $this->CI->load->database($dbConfig, true);
    }

    public function connectDbTimbangan($db = "DWJ1")
    {
        $this->CI->load->config('dbotf');
        $dbConfig = $this->CI->config->item('ranger_db');
        $dbName = "TIMBANGAN";
        if ($db !== "NSI1")
            $dbName = "TIMBANGAN_" . $db;
        $dbConfig['database'] = $dbName;

        return $this->CI->db = $this->CI->load->database($dbConfig, true);
    }

    public function connectDbPabrik($db = null)
    {
        $this->CI->load->config('dbotf');
        if (isset($db) && strlen(ltrim($db)) > 0 && !empty($db)) {
            $dbConfig = $this->CI->config->item($db);
            return $this->CI->db = $this->CI->load->database($dbConfig, true);
        } else {
            return null;
        }
    }

    function prepend_log($string, $orig_filename) {
        $context = stream_context_create();
        $orig_file = fopen($orig_filename, 'c+', 1, $context);  

        $string = "\r\n\r\n/*==============".date('Y-m-d H:i:s')."==============*/\r\n\r\n" . $string; 

        $temp_filename = tempnam(sys_get_temp_dir(), 'php_prepend_');
        file_put_contents($temp_filename, $string);
        file_put_contents($temp_filename, $orig_file, FILE_APPEND);
        chmod($temp_filename, 0775);

        fclose($orig_file);
        unlink($orig_filename); 
        rename($temp_filename, $orig_filename);
    }
}