<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

	/*
    @application  : Kiranaku v2
    @author     : Akhmad Syaiful Yamang (8347)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */

class User extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "API";
        $this->load->helper('download');
        $this->load->helper('date');
        ini_set('memory_limit', '2000M');
        ini_set('max_execution_time', 14000);
        date_default_timezone_set("Asia/Jakarta");

        $this->load->model('spot/dtransaksipol', 'dtransaksipol');
    }

    public function index_get()
    {
        $json = array(
            "message" => "Portal Internal API"
        );

        return $this->response($json, 200);
    }

    public function index_post()
    {
        $json = array(
            "message" => "Portal Internal API"
        );

        return $this->response($json, 200);
    }

    public function login_post()
    {
        $nik  = @$this->input->post('username', true);
        $pass = @$this->input->post('password', true);

        $data = $this->dgeneral->get_user_login($nik, $pass);
        if ($data) {
			$link = 'home/expired/password/?key=' . $this->generate->kirana_encrypt($nik);
            $prod_server = json_decode(KIRANA_SERVER);

            if (array_search($_SERVER['SERVER_NAME'], $prod_server, true) === false) {
                $server = "dev";
            } else {
                $server = "prod";
            }

            $session = array(
                "sts"        		 => 'OK',
                "link"        		 => $link,
                "-id_user-"          => base64_encode($data->id_user),
                "-id_ceo-"           => base64_encode($data->id_ceo),
                "-id_direktorat-"    => base64_encode($data->id_direktorat),
                "-id_divisi-"        => base64_encode($data->id_divisi),
                "-id_departemen-"    => base64_encode($data->id_departemen),
                "-id_level-"         => base64_encode($data->id_level),
                "-id_jabatan-"       => base64_encode($data->id_jabatan),
                "-id_golongan-"      => base64_encode($data->id_golongan),
                "-id_karyawan-"      => base64_encode($data->id_karyawan),
                "-nik-"              => base64_encode($data->nik),
                "-nama-"             => base64_encode($data->nama),
                "-ho-"               => base64_encode($data->ho),
                "-id_gedung-"        => base64_encode($data->id_gedung),
                "-status-"           => base64_encode($data->status),
                "-cr_level_id-"      => base64_encode($data->cr_level_id),
                "-persa-"            => base64_encode($data->persa),
                "-wf_level_id-"      => base64_encode($data->wf_level_id),
                "-gsber-"            => base64_encode($data->gsber),
                "-gem_level_id-"     => base64_encode($data->gem_level_id),
                "-leg_level_id-"     => base64_encode($data->leg_level_id),
                "-posst-"            => base64_encode($data->posst),
                "-id_seksi-"         => base64_encode($data->id_seksi),
                "-identity_session-" => base64_encode($server)
            );
			$this->session->set_userdata($session);
            return $this->response($session, 200);
        } else {
            return $this->response(NULL, 404);
        }
		
    }
}
?>
