<?php

/*
@application    : Kiranaku v2
@author         : Akhmad Syaiful Yamang (8347)
@contributor    :
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

defined('BASEPATH') or exit('No direct script access allowed');

class General
{
    protected $CI, $curl_base_url;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('session');
        $this->CI->load->library('generate');
        $this->CI->load->model('dgeneral');
        $this->CI->load->helper('cookie');
        date_default_timezone_set('Asia/Jakarta');
    }

    //==================================================//
    /*            Connection db DashBoardDev            */
    //==================================================//
    public function connectDbDefault()
    {
        return $this->CI->db = $this->CI->load->database('default', true);
    }

    //==================================================//
    /*              Connection db portal                */
    //==================================================//
    public function connectDbPortal()
    {
        return $this->CI->db = $this->CI->load->database('db_portal', true);
    }

    //==================================================//
    /*              Connection db portal                */
    //==================================================//
    public function connectDbPortalLive()
    {
        return $this->CI->db = $this->CI->load->database('db_portal_live', true);
    }

    //==================================================//
    /*              Connection db web                   */
    //==================================================//
    public function connectDbWeb()
    {
        return $this->CI->db = $this->CI->load->database('db_web', true);
    }

    //==================================================//
    /*              Connection db sdo                   */
    //==================================================//
    public function connectDbSdo()
    {
        return $this->CI->db = $this->CI->load->database('db_sdo', true);
    }

    //==================================================//
    /*              Connection db                       */
    //==================================================//
    public function connectDb($db_conf = 'default')
    {
        return $this->CI->db = $this->CI->load->database($db_conf, true);
    }

    //==================================================//
    /*              Close connection db                 */
    //==================================================//
    public function closeDb()
    {
        return $this->CI->dgeneral->close();
    }

    //==================================================//
    /*                  Connect SAP                     */
    //==================================================//
    public function connectSAP($user)
    {
        $koneksi = parse_ini_file(FILE_KONEKSI_SAP, true);
        $conn    = $koneksi[$user];
        $constr  = array(
            "logindata"   => array(
                "ASHOST" => $conn['ASHOST'],    // application server
                "SYSNR"  => $conn['SYSNR'],        // system number
                "CLIENT" => $conn['CLIENT'],    // client
                "USER"   => $conn['USER'],        // user
                "PASSWD" => $conn['PASSWD']        // password
            ),
            "show_errors" => $conn['DEBUG'],                // let class printout errors
            "debug"       => $conn['DEBUG']
        );            // detailed debugging information

        return new saprfc($constr);
    }

    //==================================================//
    /*              File location Path                  */
    /*--------------------------------------------------*/
    /* @param string module name */
    //==================================================//
    public function kirana_file_path($module = 'home')
    {
        if (!$module || trim($module) == "") {
            $module = 'home';
        }
        return KIRANA_PATH_FILE . $module;
    }

    /**
     * Get user login data
     *
     * @return array data user based on session
     **/
    public function get_data_user()
    {
        $this->CI->load->library('user_agent');
        if (isset($_COOKIE['portal_cookies']) || $this->CI->session->userdata()) {
            $cookie = isset($_COOKIE['portal_cookies']) ? $_COOKIE['portal_cookies'] : NULL;
            $data   = json_decode(base64_decode($cookie), true);
            if (!isset($data)) {
                $data = $this->CI->session->userdata();
            } else {
                //check server session
                $prod_server = json_decode(KIRANA_SERVER);
                $name_server = $_SERVER['SERVER_NAME'];

                if (in_array($name_server, $prod_server) === FALSE) {
                    $server = "dev";
                } else {
                    $server = "prod";
                }
                $id_session = isset($data['-identity_session-']) ? base64_decode($data['-identity_session-']) : NULL;
                if (!$id_session || $id_session !== $server) {
                    setcookie("portal_cookies", NULL, time() - 7200, "/");
                    redirect(base_url() . "home/login");
                }
            }

            if (isset($data['-id_user-'])) {
                $user     = base64_decode($this->CI->session->userdata('-id_user-'));
                $data_ses = $this->CI->dgeneral->get_data_session($user);

                return $data_ses;
            } else {
                $this->CI->session->sess_destroy();
                $ref = "";
                if (!empty(uri_string()))
                    $ref = '?ref=' . uri_string();
                redirect(base_url() . "home/login" . $ref);
            }
        } else {
            $ref = "";
            if (!empty(uri_string()))
                $ref = '?ref=' . uri_string();
            redirect(base_url() . "home/login" . $ref);
        };
    }

    /**
     * Check access logged user
     *
     * @param array string nama menu
     *
     * @return boolean
     **/
    public function check_access($menuname = NULL)
    {
        $this->CI->load->library('user_agent');
        $param = str_replace("/uat/kiranaku/", "", strtok($_SERVER['REQUEST_URI'], '?'));
        if ($param == "" || $param == "/")
            $param = "home";
        if (substr($param, 0, 1) == "/")
            $param = ltrim($param, "/");
        if (substr($param, -1) == "/")
            $param = rtrim($param, "/");
        if (isset($menuname))
            $param = NULL;

        if (!isset($_COOKIE['portal_cookies']) && $this->CI->session->userdata()) {
            $session = $this->CI->session->userdata();
            // setcookie("portal_cookies", base64_encode(json_encode($session)), '7200', "/");
            $cookie  = array(
                'name'   => 'portal_cookies',
                'value'  => base64_encode(json_encode($session)),
                'expire' => '7200'
            );
            $this->CI->input->set_cookie($cookie);
        }

        //handle akses ess for kasie down
        $data_url = explode("/", $param);
        if (isset($data_url) && $data_url[0] == 'ess' && base64_decode($this->CI->session->userdata('-ho-')) == 'n' && in_array(base64_decode($this->CI->session->userdata('-id_level-')), array("9103", "9105", "9106")) == true && strpos(base64_decode($this->CI->session->userdata('-posst-')), 'MANAGER') < 0) {
            show_404();
        }

        if (isset($_COOKIE['portal_cookies']) || $this->CI->session->userdata()) {
            $cookie = isset($_COOKIE['portal_cookies']) ? $_COOKIE['portal_cookies'] : NULL;
            $data   = json_decode(base64_decode($cookie), true);
            if (!isset($data)) {
                $data = $this->CI->session->userdata();
            } else {
                //check server session
                $prod_server = json_decode(KIRANA_SERVER);

                if (array_search($_SERVER['SERVER_NAME'], $prod_server, true) === false) {
                    $server = "dev";
                } else {
                    $server = "prod";
                }
                $id_session = isset($data['-identity_session-']) ? base64_decode($data['-identity_session-']) : NULL;

                if (!$id_session || $id_session !== $server) {
                    setcookie("portal_cookies", NULL, time() - 7200, "/");
                    redirect(base_url() . "home/login");
                }
            }

            if (isset($data['-id_user-'])) {
                $this->CI->session->set_userdata($data);
                $user     = base64_decode($this->CI->session->userdata('-id_user-'));
                $data_ses = $this->CI->dgeneral->get_data_session($user);
                if ($data_ses) {
                    $nik_access = $this->CI->dgeneral->get_data_menu(NULL, NULL, NULL, base64_decode($this->CI->session->userdata('-nik-')), $param);
                    if ($nik_access) {
                        $nik_access = explode(".", $nik_access[0]->nik_akses);
                        if (in_array($data_ses->nik, $nik_access) == true) {
                            return true;
                        } else {
                            show_404();
                        }
                    } else {
                        show_404();
                    }
                } else {
                    show_404();
                }
            } else {
                $this->CI->session->sess_destroy();
                $ref = "";
                if (!empty(uri_string()))
                    $ref = '?ref=' . uri_string();
                redirect(base_url() . "home/login" . $ref);
            }
        } else {
            $ref = "";
            if (!empty(uri_string()))
                $ref = '?ref=' . uri_string();
            redirect(base_url() . "home/login" . $ref);
        }
    }
    public function check_access2($menuname = NULL)
    {
        $this->CI->load->library('user_agent');
        $param = str_replace("/dev/kiranaku_dmz/", "", strtok($_SERVER['REQUEST_URI'], '?'));
        if ($param == "")
            $param = "home";
        if (substr($param, -1) == "/")
            $param = rtrim($param, "/");
        if (isset($menuname))
            $param = NULL;

        if (isset($_COOKIE['portal_cookies']) || $this->CI->session->userdata()) {
            $cookie = isset($_COOKIE['portal_cookies']) ? $_COOKIE['portal_cookies'] : NULL;
            $data   = json_decode(base64_decode($cookie), true);
            if (!isset($data)) {
                $data = $this->CI->session->userdata();
            } else {
                //check server session
                $prod_server = json_decode(KIRANA_SERVER);

                if (array_search($_SERVER['SERVER_NAME'], $prod_server, true) === false) {
                    $server = "dev";
                } else {
                    $server = "prod";
                }
                $id_session = isset($data['-identity_session-']) ? base64_decode($data['-identity_session-']) : NULL;

                if (!$id_session || $id_session !== $server) {
                    setcookie("portal_cookies", NULL, time() - 7200, "/");
                    redirect(base_url() . "home/login");
                }
            }

            if (isset($data['-id_user-'])) {
                $this->CI->session->set_userdata($data);
                $user     = base64_decode($this->CI->session->userdata('-id_user-'));
                $data_ses = $this->CI->dgeneral->get_data_session($user);
                if ($data_ses) {
                    return true;
                } else {
                    show_404();
                }
            } else {
                $this->CI->session->sess_destroy();
                $ref = "";
                if (!empty(uri_string()))
                    $ref = '?ref=' . uri_string();
                redirect(base_url() . "assessment/transaksi/login" . $ref);
            }
        } else {
            $ref = "";
            if (!empty(uri_string()))
                $ref = '?ref=' . uri_string();
            redirect(base_url() . "assessment/transaksi/login" . $ref);
        }
    }

    /**
     * Check session logged user
     *
     * @return boolean
     **/
    public function check_session()
    {
        if ($this->CI->session->userdata('-id_user-')) {
            return true;
        } else {
            show_404();
        }
    }

    /**
     * Get list menu by user login nik
     *
     * @param array string nama menu
     *
     * @return array
     **/
    public function get_menu($name = NULL, $dmz = NULL)
    {
        $parent = 0;

        if ($this->CI->session->userdata('-akses_menu_parent-') && base64_decode($this->CI->session->userdata('-akses_menu_parent-')) !== "")
            $parent = base64_decode($this->CI->session->userdata('-akses_menu_parent-'));

        $menu     = $this->CI->dgeneral->get_data_menu($name, NULL, $parent, base64_decode($this->CI->session->userdata('-nik-')), NULL, $dmz);
        $datamenu = array();
        $data     = array();
        $view     = "";
        if ($menu) {
            $i    = 0;
            $view .= "<li class='header'>MENU KIRANAKU</li>";
            foreach ($menu as $m) {
                if ($m->nama == "PERSIS") {
                    $check_akses = $this->CI->dgeneral->get_data_user(NULL, NULL, NULL, base64_decode($this->CI->session->userdata('-nik-')));
                    if (isset($check_akses) && ($check_akses[0]->wf_level_id !== NULL && $check_akses[0]->wf_level_id != 0)) {
                        $child = $this->get_menu_child($m->id_menu, $dmz);

                        if ($dmz == 1) {
                            $url_ext    = ($m->url_external !== NULL && trim($m->url_external) !== "-" ? 'http://shipment.kiranamegatara.com/kiranaku_dmz/' . $m->url_external : 'javascript:void(0)');
                        } else {
                            $url_ext    = ($m->url_external !== NULL && trim($m->url_external) !== "-" ? base_url() . $m->url_external : 'javascript:void(0)');
                        }

                        // $url_ext    = ($m->url_external !== NULL && trim($m->url_external) !== "-" ? base_url() . $m->url_external : 'javascript:void(0)');
                        $check_url  = strlen(strpos($m->url_external, "http://"));
                        $check_urls  = strlen(strpos($m->url_external, "https://"));
                        if ($check_url > 0 || $check_urls > 0)
                            $url_ext = $m->url_external;

                        $target     = $m->target == NULL ? "" : "target='" . $m->target . "'";
                        $view       .= "<li class='treeview'>";
                        $view       .= "	<a href='" . $url_ext . "' " . $target . ">";
                        $view       .= "		<i class='fa " . ($m->kelas !== '-' ? $m->kelas : 'fa-circle-o') . "'></i> <span style='position: relative;display: inline-grid;width: 80%;white-space: normal;'>" . $m->nama . "</span>";
                        $view       .= "		<span class='pull-right-container'>";
                        $view_child = "";
                        if ($child['view'] !== "") {
                            $view       .= "<i class='fa fa-angle-left pull-right'></i>";
                            $view_child .= $child['view'];
                        }
                        $view .= "		<span class='label label-primary pull-right hide notification-badge' data-code='" . $m->notification_categories . "'>0</span>";
                        $view .= "		</span>";
                        $view .= "	</a>";
                        $view .= $view_child;
                        $view .= "</li>";

                        $i++;
                    }
                } else {
                    $child = $this->get_menu_child($m->id_menu, $dmz);

                    if ($dmz == 1) {
                        $url_ext    = ($m->url_external !== NULL && trim($m->url_external) !== "-" ? 'http://shipment.kiranamegatara.com/kiranaku_dmz/' . $m->url_external : 'javascript:void(0)');
                    } else {
                        $url_ext    = ($m->url_external !== NULL && trim($m->url_external) !== "-" ? base_url() . $m->url_external : 'javascript:void(0)');
                    }

                    // $url_ext    = ($m->url_external !== NULL && trim($m->url_external) !== "-" ? base_url() . $m->url_external : 'javascript:void(0)');
                    $check_url  = strlen(strpos($m->url_external, "http://"));
                    $check_urls  = strlen(strpos($m->url_external, "https://"));
                    if ($check_url > 0)
                        $url_ext = $m->url_external;

                    $target     = $m->target == NULL ? "" : "target='" . $m->target . "'";
                    $view       .= "<li class='treeview'>";
                    $view       .= "	<a href='" . $url_ext . "' " . $target . ">";
                    $view       .= "		<i class='fa " . ($m->kelas !== '-' ? $m->kelas : 'fa-circle-o') . "'></i> <span style='position: relative;display: inline-grid;width: 80%;white-space: normal;'>" . $m->nama . "</span>";
                    $view       .= "		<span class='pull-right-container'>";
                    $view_child = "";
                    if ($child['view'] !== "") {
                        $view       .= "<i class='fa fa-angle-left pull-right'></i>";
                        $view_child .= $child['view'];
                    }
                    $view .= "		<span class='label label-primary pull-right hide notification-badge' data-code='" . $m->notification_categories . "'>0</span>";
                    $view .= "		</span>";
                    $view .= "	</a>";
                    $view .= $view_child;
                    $view .= "</li>";

                    $i++;
                }
            }
        }
        $data['view'] = $view;

        return $data;
    }

    public function get_menu_child($id_menu, $dmz = NULL)
    {
        $data  = array();
        $view  = "";
        $child = $this->CI->dgeneral->get_data_menu(NULL, NULL, $id_menu, base64_decode($this->CI->session->userdata('-nik-')), NULL, $dmz);
        if ($child) {
            $i    = 0;
            $view .= "<ul class='treeview-menu'>";
            foreach ($child as $c) {
                if ($c->nama == "PERSIS") {
                    $check_akses = $this->CI->dgeneral->get_data_user(NULL, NULL, NULL, base64_decode($this->CI->session->userdata('-nik-')), $dmz);
                    if (isset($check_akses) && ($check_akses[0]->wf_level_id !== NULL && $check_akses[0]->wf_level_id != 0)) {
                        $data_child = $this->get_menu_child($c->id_menu, $dmz);
                        if ($dmz == 1) {
                            $url_ext    = ($c->url_external !== NULL && trim($c->url_external) !== "-" ? 'http://shipment.kiranamegatara.com/kiranaku_dmz/' . $c->url_external : 'javascript:void(0)');
                        } else {
                            $url_ext    = ($c->url_external !== NULL && trim($c->url_external) !== "-" ? base_url() . $c->url_external : 'javascript:void(0)');
                        }

                        $check_url  = strlen(strpos($c->url_external, "http://"));
                        $check_urls  = strlen(strpos($c->url_external, "https://"));
                        if ($check_url > 0 || $check_urls > 0)
                            $url_ext = $c->url_external;

                        $target     = $c->target == NULL ? "" : "target='" . $c->target . "'";
                        $view       .= "<li>";
                        $view       .= "	<a href='" . $url_ext . "' " . $target . ">";
                        $view       .= "		<i class='fa " . ($c->kelas !== '-' ? $c->kelas : 'fa-circle-o') . "'></i> <span style='position: relative;display: inline-grid;width: 80%;white-space: normal;'>" . $c->nama . "</span>";
                        $view       .= "		<span class='pull-right-container'>";
                        $view_child = "";
                        if ($data_child['view'] !== "") {
                            $view       .= "<i class='fa fa-angle-left pull-right'></i>";
                            $view_child .= $data_child['view'];
                        }
                        $view .= "		<span class='label label-primary pull-right hide notification-badge' data-code='" . $c->notification_categories . "'>0</span>";
                        $view .= "		</span>";
                        $view .= "	</a>";
                        $view .= $view_child;
                        $view .= "</li>";

                        $i++;
                    }
                } else {
                    if ($c->nama == "Employee Self Service" && base64_decode($this->CI->session->userdata('-ho-')) == 'n' && in_array(base64_decode($this->CI->session->userdata('-id_level-')), array("9103", "9105", "9106")) == true  && strpos(base64_decode($this->CI->session->userdata('-posst-')), 'MANAGER') < 0) {
                    } else {
                        $data_child = $this->get_menu_child($c->id_menu, $dmz);
                        if ($dmz == 1) {
                            $url_ext    = ($c->url_external !== NULL && trim($c->url_external) !== "-" ? 'http://shipment.kiranamegatara.com/kiranaku_dmz/' . $c->url_external : 'javascript:void(0)');
                        } else {
                            $url_ext    = ($c->url_external !== NULL && trim($c->url_external) !== "-" ? base_url() . $c->url_external : 'javascript:void(0)');
                        }

                        // $url_ext   = ($c->url_external !== NULL && trim($c->url_external) !== "-" ? base_url() . $c->url_external : 'javascript:void(0)');
                        $check_url = strlen(strpos($c->url_external, "http://"));
                        $check_urls = strlen(strpos($c->url_external, "https://"));
                        if ($check_url > 0 || $check_urls > 0)
                            $url_ext = $c->url_external;

                        $target     = $c->target == NULL ? "" : "target='" . $c->target . "'";
                        $view       .= "<li>";
                        $view       .= "	<a href='" . $url_ext . "' " . $target . ">";
                        $view       .= "		<i class='fa " . ($c->kelas !== '-' ? $c->kelas : 'fa-circle-o') . "'></i> <span style='position: relative;display: inline-grid;width: 80%;white-space: normal;'>" . $c->nama . "</span>";
                        $view       .= "		<span class='pull-right-container'>";
                        $view_child = "";
                        if ($data_child['view'] !== "") {
                            $view       .= "<i class='fa fa-angle-left pull-right'></i>";
                            $view_child .= $data_child['view'];
                        }
                        $view .= "		<span class='label label-primary pull-right hide notification-badge' data-code='" . $c->notification_categories . "'>0</span>";
                        $view .= "		</span>";
                        $view .= "	</a>";
                        $view .= $view_child;
                        $view .= "</li>";
                    }

                    $i++;
                }
            }
            $view .= "</ul>";
        }
        $data['view'] = $view;

        return $data;
    }

    /**
     * Get list master plant
     *
     * @param array string plant kode
     * @param string -> return type -> NULL means array , else means json
     *
     * @return array / json
     **/
    public function get_master_plant($plant_in = NULL, $json = NULL, $as_array = false, $plant_not_in = NULL, $tipe = NULL)
    {
        $plant = $this->CI->dgeneral->get_master_plant($plant_in, $as_array, $plant_not_in, $tipe);
        if ($json !== NULL) {
            echo json_encode($plant);
        } else {
            return $plant;
        }
    }

    /**
     * Get list user autocomplete by fullname
     *
     * @param string fullname
     *
     * @return json
     **/
    public function get_user_autocomplete()
    {
        if (isset($_GET['q'])) {
            $data      = $this->CI->dgeneral->get_data_user($_GET['q']);
            $data_json = array(
                "total_count"        => count($data),
                "incomplete_results" => false,
                "items"              => $data
            );
            echo json_encode($data_json);
        }
    }

    public function isJson($string)
    {
        json_encode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    //==================================================//
    /*                  convert charset                 */
    //==================================================//
    public function convertCharset($value, $from = NULL, $to = NULL)
    {
        if (empty($from))
            $from = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1'], true);

        if (empty($value))
            $result = NULL;
        else
            $result = iconv(
                ($from ? $from : 'UTF-8'),
                ($to ? $to : 'UTF-8') . '//IGNORE',
                $value
            );

        return $result;
    }

    /**
     * Upload file method
     *
     * @param file data
     * @param array string newname
     * @param array config
     *
     * @return array
     **/
    public function upload_files($data, $newname = NULL, $config = NULL)
    {
        $content = $data;
        $files   = array();
        $this->CI->load->library('upload'); // edit by ayy 
        if ($this->CI->session->userdata('-id_user-')) {
            for ($i = 0; $i < count($content['name']); $i++) {
                if ($content['error'][$i] == 0) {
                    if (isset($newname) && $newname !== NULL) {
                        $ext      = strtolower(pathinfo($content['name'][$i], PATHINFO_EXTENSION));
                        $filename = $newname[$i] . "." . $ext;
                    } else {
                        $filename = $content['name'][$i];
                    }

                    $_FILES['userfile']['name']     = $filename;
                    $_FILES['userfile']['type']     = $content['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $content['tmp_name'][$i];
                    $_FILES['userfile']['error']    = $content['error'][$i];
                    $_FILES['userfile']['size']     = $content['size'][$i];

                    if (!file_exists($config['upload_path'])) {
                        @mkdir($config['upload_path'], 0777, true);
                    }

                    $config['overwrite']        = true;
                    $config['detect_mime']      = true;
                    $config['remove_spaces']    = false;
                    $config['mod_mime_fix']     = false;
                    $config['file_ext_tolower'] = true;

                    $this->CI->upload->initialize($config); // edit by ayy 

                    if (!$this->CI->upload->do_upload()) {
                        $return = array('sts' => "NotOK", 'msg' => $this->CI->upload->display_errors());
                        echo json_encode($return);
                        exit();
                    } else {
                        $file             = $this->CI->upload->data();
                        $file['url']      = str_replace(realpath("./") . "/", "", $config['upload_path']) . "/" . $file['file_name'];
                        $file['filename'] = $file['file_name']; //remark jodi - folder SOP
                        $file['size']     = $content['size'][$i]; //remark jodi - folder SOP
                        $files[]          = $file;
                    }
                } else {
                    if ($content['error'][$i] !== 4) {
                        $upload_error = array();
                        switch ($content['error'][$i]) {
                            case UPLOAD_ERR_INI_SIZE:
                                $upload_error[] = 'File ke ' . ($i + 1) . '. Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                break;
                            case UPLOAD_ERR_FORM_SIZE:
                                $upload_error[] = 'File ke ' . ($i + 1) . '. Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                                break;
                            case UPLOAD_ERR_PARTIAL:
                                $upload_error[] = 'File ke ' . ($i + 1) . '. File ini hanya sebagian terunggah. Harap pilih file lain.';
                                break;
                            case UPLOAD_ERR_EXTENSION:
                                $upload_error[] = 'File ke ' . ($i + 1) . '.Jenis berkas yang Anda coba untuk mengunggah tidak diperbolehkan.';
                                break;
                        }

                        $return = array('sts' => "NotOK", 'msg' => $upload_error);
                        echo json_encode($return);
                        exit();
                    }
                }
            }
            return $files;
        }
    }

    /** Check upload file
     *
     * @param string $upload_name
     * @param bool   $required
     *
     * @return null|string
     */
    public function check_upload_file($upload_name = 'file', $required = false)
    {
        $upload_error = NULL;

        if ($_FILES[$upload_name]['error'] != 0) {
            switch ($_FILES[$upload_name]['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $upload_error = 'Berkas yang diunggah melebihi ukuran maksimum yang diperbolehkan.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $upload_error = 'File ini hanya sebagian terunggah. Harap pilih file lain.';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $upload_error = 'Upload berkas dihentikan oleh ekstensi. Harap pilih file lain.';
                    break;
            }
        } else if ($required and $_FILES[$upload_name]['size'] <= 0)
            $upload_error = "File tidak ada, harap pilih file.";

        return $upload_error;
    }

    /**
     * Download file method
     *
     * @param string url
     *
     * @return file
     **/
    public function download($url)
    {
        $this->CI->load->helper('download');
        $url = urldecode($url);
        $data = file_get_contents($url);
        force_download(basename($url), $data);
    }

    /**
     * Set status data
     *
     * @param string action
     * @param string table
     * @param array key
     *
     * @return array
     **/
    public function set($action, $table, $key)
    {
        $datetime = date("Y-m-d H:i:s");
        $this->CI->dgeneral->begin_transaction();
        switch ($action) {
            case 'delete':
                $active = array(0);
                $kolom  = array("active");
                break;
            case 'activate':
                $active = array(1);
                $kolom  = array("active");
                break;
            case 'activate_na':
                $active = array("n");
                $kolom  = array("na");
                break;
            case 'delete_na':
                $active = array("y");
                $kolom  = array("na");
                break;
            case 'delete_del':
                $active = array("y");
                $kolom  = array("del");
                break;
            case 'activate_na_del':
                $active = array("n", "n");
                $kolom  = array("na", "del");
                break;
            case 'delete_na_del':
                $active = array("y", "y");
                $kolom  = array("na", "del");
                break;
            case 'delete_del0':
                $active = array(0);
                $kolom  = array("del");
                break;
            case 'delete_del1':
                $active = array(1);
                $kolom  = array("del");
                break;
            default:
                $active = NULL;
                $kolom  = NULL;
                break;
        }
        $data_row = array(
            'login_edit'   => base64_decode($this->CI->session->userdata("-id_user-")),
            'tanggal_edit' => $datetime
        );

        for ($i = 0; $i < count($kolom); $i++) {
            $data_row[$kolom[$i]] = $active[$i];
        }

        $this->CI->dgeneral->update($table, $data_row, $key);

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

    /**
     * Send email method
     *
     * @param string subject
     * @param string from alias
     * @param string to
     * @param string cc
     * @param string message
     *
     * @return boolean true or json error
     **/
    public function send_email($subject, $from_alias, $to, $cc, $message)
    {
        if (!empty($subject) && !empty($from_alias) && !empty($to) && !empty($message)) {
            setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');

            $config['protocol']    = 'smtp';
            $config['smtp_host']   = 'mail.kiranamegatara.com';
            $config['smtp_user']   = 'no-reply@kiranamegatara.com';
            $config['smtp_pass']   = '1234567890';
            $config['smtp_port']   = '465';
            $config['smtp_crypto'] = 'ssl';
            $config['charset']     = 'iso-8859-1';
            $config['wordwrap']    = true;
            $config['mailtype']    = 'html';

            try {
                $this->CI->load->library('email', $config);

                $this->CI->email->from('no-reply@kiranamegatara.com', $from_alias);
                $this->CI->email->to($to);
                if (isset($cc) && !empty($cc)) {
                    $this->CI->email->cc($cc);
                }

                $this->CI->email->subject($subject);
                $this->CI->email->message($message);

                $this->CI->email->send();
            } catch (Exception $e) {
                $msg    = $e->getMessage();
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
                echo json_encode($return);
                exit();
            }
        } else {
            $msg    = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
            $sts    = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
            echo json_encode($return);
            exit();
        }
    }

    /** Send Email New
     *
     * @param array $params
     *  array(
     *  'subject' => text, default = null, subject email
     *  'from_alias' => text, default = null, alias email dari noreply@kiranamegatara.com
     *  'message' => text, default = null, message body html untuk email
     *  'to' => text|array, default = null, email tujuan
     *  'cc' => text|array, default = null, email tujuan cc
     *  )
     *
     * @return array
     */
    public function send_email_new($params = array())
    {
        $subject    = isset($params['subject']) ? $params['subject'] : NULL;
        $from_alias = isset($params['from_alias']) ? $params['from_alias'] : NULL;
        $message    = isset($params['message']) ? $params['message'] : NULL;
        $to         = isset($params['to']) ? $params['to'] : NULL;
        $cc         = isset($params['cc']) ? $params['cc'] : NULL;
        $attachment = isset($params['attachment']) ? $params['attachment'] : NULL;

        if (!empty($subject) && !empty($from_alias) && !empty($to) && !empty($message)) {
            setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');

            $config['protocol']    = 'smtp';
            $config['debug']       = true;
            $config['smtp_host']   = KIRANA_EMAIL_HOST;
            $config['smtp_user']   = KIRANA_EMAIL_USER;
            $config['smtp_pass']   = KIRANA_EMAIL_PASS;
            $config['smtp_port']   = KIRANA_EMAIL_PORT;
            $config['smtp_crypto'] = 'ssl';
            $config['charset']     = 'iso-8859-1';
            $config['wordwrap']    = true;
            $config['mailtype']    = 'html';

            try {
                $open_socket = @fsockopen(KIRANA_EMAIL_HOST, KIRANA_EMAIL_PORT, $errno, $errstr, 30);
                if (!$open_socket) {
                    $msg    = "Terjadi kesalahan pada sistem pengiriman email, email server tidak terhubung. Silahkan hubungi admin (IT Staff Kirana).";
                    $sts    = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                } else {
                    $this->CI->load->library('email', $config);

                    $this->CI->email->clear(true);

                    $this->CI->email->from('no-reply@kiranamegatara.com', $from_alias);
                    $this->CI->email->to($to);
                    if (isset($cc) && !empty($cc)) {
                        $this->CI->email->cc($cc);
                    }

                    $this->CI->email->subject($subject);
                    $this->CI->email->message($message);

                    if (isset($attachment))
                        $this->CI->email->attach($attachment);

                    $status = $this->CI->email->send(false);

                    if ($status === false) {
                        $msg    = "Terjadi kesalahan pada sistem pengiriman email, email gagal terkirim. Silahkan hubungi admin (IT Staff Kirana).";
                        $sts    = "NotOK";
                        $return = array('sts' => $sts, 'msg' => $msg);
                    } else {
                        $sts    = "OK";
                        $return = array('sts' => $sts);
                    }
                }
            } catch (Exception $e) {
                $msg    = $e->getMessage();
                $sts    = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
            }
        } else {
            $msg    = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
            $sts    = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
        }

        return $return;
    }

    /**
     * Get data provinsi method
     *
     * @param string -> return type -> NULL means array , else means json
     * @param id provinsi
     * @param string nama provinsi
     * @param string option all -> NULL means active data only else means all data
     *
     * @return array / json
     **/
    public function get_data_provinsi($array = NULL, $id_prov = NULL, $provinsi = NULL, $provinsi_in = NULL, $all = NULL)
    {
        $data = $this->CI->dgeneral->get_data_provinsi($id_prov, $provinsi, $provinsi_in, $all);
        $data = $this->generate_encrypt_json($data, array("id"));
        if ($array) {
            return $data;
        } else {
            echo json_encode($data);
        }
    }

    /**
     * Get data kabupaten/kota method
     *
     * @param string -> return type -> NULL means array , else means json
     * @param id kabupaten
     * @param string nama kabupaten
     * @param id provinsi
     * @param string option all -> NULL means active data only else means all data
     *
     * @return array / json
     **/
    public function get_data_kabupaten($array = NULL, $id_kab = NULL, $kab = NULL, $provinsi = NULL, $provinsi_in = NULL, $all = NULL)
    {
        $data = $this->CI->dgeneral->get_data_kabupaten($id_kab, $kab, $provinsi, $provinsi_in, $all);
        if (isset($_GET['q'])) {
            $data_json = array(
                "total_count"        => count($data),
                "incomplete_results" => false,
                "items"              => $this->generate_encrypt_json($data, array("id"))
            );
            echo json_encode($data_json);
        } else {
            if ($array) {
                return $data;
            } else {
                echo json_encode($data);
            }
        }
    }

    /**
     * Get data generate_encrypt_json method
     *
     * @param array data
     * @param string nama kolom yang di encrypt
     *
     * @return array
     **/
    public function generate_encrypt_json($data, $kolom = array("id"))
    {
        if (isset($data)) {
            if (is_array($data)) {
                $data_enc = array();
                foreach ($data as $dt) {
                    if (is_array($dt))
                        $dt = (object) $dt;
                    foreach ($kolom as $k) {
                        if ($dt->$k !== NULL) {
                            $dt->$k = $this->CI->generate->kirana_encrypt($dt->$k);
                        }
                    }
                    array_push($data_enc, $dt);
                }
                $data = $data_enc;
            } else {
                foreach ($kolom as $k) {
                    if ($data->$k !== NULL) {
                        $data->$k = $this->CI->generate->kirana_encrypt($data->$k);
                    }
                }
            }
        }
        return $data;
    }

    public function emptyconvert($value, $tobe = NULL)
    {
        return (!isset($value) || empty($value)) ? $tobe : $value;
    }


    public function generate_max_number($table, $key, $index, $connection = NULL)
    {
        $zero = '0';
        for ($i = 0; $i < $index - 1; $i++) {
            $zero = $zero . '0';
        }

        $data = $this->CI->dgeneral->generate_id($table, $key, $index, $zero, $connection);

        return $data->number;
    }


    /**
     * Workaround for json_encode's UTF-8 encoding if a different charset needs to be used
     *
     * @param mixed $result
     *
     * @return string
     **/
    public function jsonify($result = false)
    {
        $isJson = $this->isJson($result);
        if ($isJson === FALSE) {
            if (is_null($result))
                return 'null';

            if ($result === false)
                return 'false';

            if ($result === true)
                return 'true';

            if (is_scalar($result)) {
                if (is_float($result))
                    return floatval(str_replace(',', '.', strval($result)));

                if (is_string($result)) {
                    static $jsonReplaces = array(array('\\', '/', '\n', '\t', '\r', '\b', '\f', '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                    return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $result) . '"';
                } else
                    return $result;
            }

            $isList = true;

            for ($i = 0, reset($result); $i < count($result); $i++, next($result)) {
                if (key($result) !== $i) {
                    $isList = false;
                    break;
                }
            }

            $json = array();

            if ($isList) {
                foreach ($result as $value)
                    $json[] = $this->jsonify($value);

                return '[' . join(',', $json) . ']';
            } else {
                foreach ($result as $key => $value)
                    $json[] = $this->jsonify($key) . ':' . $this->jsonify($value);

                return '{' . join(',', $json) . '}';
            }
        } else {
            return json_encode($result);
        }
    }

    public function log_schedule_master($script = NULL, $params = array())
    {

        $this->connectDbPortal();

        $master = NULL;
        if (isset($script)) {
            $master = $this->CI->dgeneral->get_schedule_master(
                array(
                    'script'     => $script,
                    'single_row' => true
                )
            );
        } else {
            $msg = "Script not found";
            $sts = false;

            return compact('msg', 'sts');
        }

        $this->CI->dgeneral->begin_transaction();

        $params = array_merge(
            array(
                'sesi'        => date('H:i'),
                'source'      => '',
                'terminal'    => '',
                'destination' => '',
                'keterangan'  => '',
            ),
            $params
        );

        if (isset($master)) {
            $data = array(
                'sesi'        => $params['sesi'],
                'source'      => $params['source'],
                'terminal'    => $params['terminal'],
                'destination' => $params['destination']
            );

            $data = $this->CI->dgeneral->basic_column('update', $data);

            $this->CI->dgeneral->update('tbl_running_master', $data, array(
                array(
                    'kolom' => 'id',
                    'value' => $master->id
                )
            ));
        } else {
            $data = array(
                'script'      => $script,
                'keterangan'  => $params['keterangan'],
                'sesi'        => $params['sesi'],
                'source'      => $params['source'],
                'terminal'    => $params['terminal'],
                'destination' => $params['destination']
            );

            $data = $this->CI->dgeneral->basic_column('insert', $data);

            $this->CI->dgeneral->insert('tbl_running_master', $data);
        }
        if ($this->CI->dgeneral->status_transaction() === false) {
            $this->CI->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = false;
        } else {
            $this->CI->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = true;
        }

        $this->closeDb();

        return compact('msg', 'sts');
    }

    public function log_schedule_running($script = NULL, $params = array())
    {

        $this->connectDbPortal();

        $master = NULL;
        if (isset($script)) {
            $master = $this->CI->dgeneral->get_schedule_running(
                array(
                    'script'     => $script,
                    'tanggal'    => date('Y-m-d'),
                    'single_row' => true
                )
            );
        } else {
            $msg = "Script not found";
            $sts = false;

            return compact('msg', 'sts');
        }

        $this->CI->dgeneral->begin_transaction();

        if (isset($master)) {
            $data = $params;

            if (count($data) > 0)
                $this->CI->dgeneral->update('tbl_running_schedule', $data, array(
                    array(
                        'kolom' => 'id',
                        'value' => $master->id
                    )
                ));
        } else {
            $params = array_merge(
                array(
                    'rfc'      => NULL,
                    'tanggal'  => date('Y-m-d'),
                    'start'    => date('Y-m-d H:i:s'),
                    'end_time' => NULL,
                ),
                $params
            );

            $data = array(
                'script'   => $script,
                'rfc'      => $params['rfc'],
                'tanggal'  => $params['tanggal'],
                'start'    => $params['start'],
                'end_time' => $params['end_time'],
            );

            $this->CI->dgeneral->insert('tbl_running_schedule', $data);
        }
        if ($this->CI->dgeneral->status_transaction() === false) {
            $this->CI->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = false;
        } else {
            $this->CI->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = true;
        }

        $this->closeDb();

        return compact('msg', 'sts');
    }

    public function prepend_log($string, $orig_filename)
    {
        $context = stream_context_create();
        $orig_file = fopen($orig_filename, 'c+', 1, $context);

        $string = "\r\n\r\n/*==============" . date('Y-m-d H:i:s') . "==============*/\r\n\r\n" . $string;

        $temp_filename = tempnam(sys_get_temp_dir(), 'php_prepend_');
        file_put_contents($temp_filename, $string);
        file_put_contents($temp_filename, $orig_file, FILE_APPEND);
        chmod($temp_filename, 0775);

        fclose($orig_file);
        unlink($orig_filename);
        rename($temp_filename, $orig_filename);
    }

    //lha for master depo
    public function generate_json($param) //$data, $kolom = array("id"), $exclude = array())
    {
        $data = $this->emptyconvert(@$param['data'], (is_array(@$param['data']) ? array() : NULL));
        $kolom = $this->emptyconvert(@$param['kolom']);
        $exclude = $this->emptyconvert(@$param['exclude']);
        if (isset($data)) {
            $isJson = $this->isJson($data);
            if ($isJson === FALSE || $kolom || $exclude) {
                if (is_array($data)) {
                    $data_enc = array();
                    foreach ($data as $dt) {
                        $dt_array = (array) $dt;

                        if (is_array($dt))
                            $dt = (object) $dt;

                        if ($isJson === FALSE)
                            foreach (array_keys($dt_array) as $i => $v) {
                                $dt->$v = $dt->$v && (is_string($dt->$v) || floatval($dt->$v) == 0) ? $this->convertCharset($dt->$v) : $dt->$v;
                            }

                        if ($kolom)
                            foreach ($kolom as $k) {
                                if (isset($dt->$k['nama']) && $dt->$k['nama'] !== NULL) {
                                    if ($k['tipe'] == 'encrypt')
                                        $dt->$k['nama'] = $this->CI->generate->kirana_encrypt($dt->$k['nama']);
                                }
                            }

                        if ($exclude)
                            foreach ($exclude as $x) {
                                if (array_key_exists($x, $dt) === true) {
                                    unset($dt->$x);
                                }
                            }
                        array_push($data_enc, $dt);
                    }
                    $data = $data_enc;
                } else {
                    $data_array = (array) $data;

                    foreach (array_keys($data_array) as $i => $v) {
                        $data->$v = $data->$v && (is_string($data->$v) || floatval($data->$v) == 0) ? $this->convertCharset($data->$v) : $data->$v;
                    }

                    if ($kolom)
                        foreach ($kolom as $k) {
                            if (isset($data->$k['nama']) && $data->$k['nama'] !== NULL) {
                                if ($k['tipe'] == 'encrypt')
                                    $data->$k['nama'] = $this->CI->generate->kirana_encrypt($data->$k['nama']);
                            }
                        }
                    if ($exclude)
                        foreach ($exclude as $x) {
                            if (array_key_exists($x, $data) === true) {
                                unset($data->$x);
                            }
                        }
                }
            }
        }
        return $data;
    }
    //end lha for master depo		
}
