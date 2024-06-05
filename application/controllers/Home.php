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

class Home extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('dgeneral');
		$this->load->model('dhome');
	}

	public function index()
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['module'] 	= "Kiranaku";
		$data['user']  		= $this->general->get_data_user();
		//===============================================/
		$data['newrole'] 	= $this->dhome->get_data_new_role(10);
		// $data['birth_list'] = $this->dhome->get_data_karyawan(' TOP 12 ',base64_decode($this->session->userdata("-id_gedung-")));

		$data['milad_today'] = $this->get_milad('array', NULL, NULL, 'n', base64_decode($this->session->userdata("-id_gedung-")), date('Y-m-d'));
		// $data['milad'] 		 = $this->get_milad('array', NULL, NULL, 'n', base64_decode($this->session->userdata("-id_gedung-")));
		$data['berita_suka'] = $this->get_berita('array', NULL, NULL, 'n', 'suka');
		$data['berita_duka'] = $this->get_berita('array', NULL, NULL, 'n', 'duka');
		$this->load->view('page', $data);
	}

	private function get_berita($array = NULL, $id_notif_berita = NULL, $active = NULL, $deleted = NULL, $jenis = NULL)
	{
		$berita	= $this->dhome->get_data_berita("open", $id_notif_berita, $active, $deleted, $jenis);
		$berita	= $this->general->generate_encrypt_json($berita, array("id_notif_berita"));
		if ($array) {
			return $berita;
		} else {
			echo json_encode($berita);
		}
	}
	private function get_milad($array = NULL, $id_karyawan = NULL, $active = NULL, $deleted = NULL, $gedung = NULL, $date = NULL, $start = NULL, $end = NULL)
	{
		$gedung = base64_decode($this->session->userdata("-id_gedung-"));
		$milad	= $this->dhome->get_data_milad("open", $id_karyawan, $active, $deleted, $gedung, $date, $start, $end);
		// $milad	= $this->general->generate_encrypt_json($milad, array("id_karyawan"));
		if ($array) {
			return $milad;
		} else {
			echo json_encode($milad);
		}
	}

	// last edit ayy
	public function home2()
	{
		//====must be initiate in every view function====/
		$this->general->check_access();
		$data['module'] 	= "Kiranaku";
		$data['user']   	= $this->general->get_data_user();
		//===============================================/
		$data['newrole'] 	= $this->dhome->get_data_new_role(5);

		$this->load->view('page_new', $data);
	}

	public function login()
	{
		//====must be initiate in every view function====/
		$data['module'] = "Kiranaku";
		//===============================================/

		if ($this->session->userdata("-id_user-")) {
			redirect(base_url() . "home");
		}

		$this->load->view('login', $data);
	}

	// TRIAL LOGIN *jodi
	public function login_new()
	{
		//====must be initiate in every view function====/
		$data['module'] = "Kiranaku";
		//===============================================/

		if ($this->session->userdata("-id_user-")) {
			redirect(base_url() . "home");
		}

		$this->load->view('login10', $data);
	}

	public function logout()
	{

		if ($this->session->userdata("-id_user-") !== NULL) {
			$date     = date("Y-m-d");
			$datetime = date("Y-m-d H:i:s");
			$time     = date("H:i");

			$data = $this->dhome->get_log_user(base64_decode($this->session->userdata("-id_user-")));
			if ($data) {
				$data_row = array(
					"jam_logout" => $time
				);
				$this->dgeneral->update("tbl_userlog", $data_row, array(
					array(
						'kolom' => 'id_user',
						'value' => $data->id_user
					),
					array(
						'kolom' => 'tanggal',
						'value' => $data->tanggal
					)
				));
			}
		}

		$this->session->sess_destroy();
		$cookie = array(
			'name'   => 'portal_cookies',
			'value'  => '',
			'expire' => '0'
		);
		$this->input->set_cookie($cookie);

		if ($this->input->is_ajax_request()) {
			echo json_encode(array("msg" => "OK"));
		} else {
			redirect(base_url('home'));
		}
	}

	public function set($param = NULL)
	{
		switch ($param) {
			case 'password':
				$this->reset_password();
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	public function get($param = NULL)
	{
		switch ($param) {
			case 'milad':
				$start	= (isset($_POST['start']) ? $_POST['start'] : NULL);
				$end	= (isset($_POST['end']) ? $_POST['end'] : NULL);
				$this->get_milad(NULL, NULL, NULL, NULL, NULL, NULL, $start, $end);
				break;
			case 'news':
				$this->get_news();
				break;
			case 'gallery':
				$this->get_gallery();
				break;
			case 'newrole':
				$this->get_new_role();
				break;
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}

	//=================================//
	//		EXAMPLE FOR UPLOAD FILE    //
	//=================================//
	//        public function upload() {
	//            $config['upload_path']   = $this->general->kirana_file_path($this->router->fetch_module());
	//            $config['allowed_types'] = 'gif|jpg|png';
	//
	//            $newname = array();
	//            $i       = 0;
	//            foreach ($_FILES['upload'] as $dt) {
	//                array_push($newname, $i);
	//                $i++;
	//            }
	//            echo json_encode($this->general->upload_files($_FILES['upload'], $newname, $config));
	//        }

	//=================================//
	//		  PROCESS FUNCTION 		   //
	//=================================//

	public function checking()
	{
		$nik  = isset($_POST['username']) ? $_POST['username'] : NULL;
		$pass = isset($_POST['password']) ? $_POST['password'] : NULL;

		$data = $this->dgeneral->get_user_login($nik, $pass);

		$link = NULL;
		if (isset($_POST['ref']))
			$link = $_POST['ref'];

		if ($data) {
			$prod_server = json_decode(KIRANA_SERVER);

			if (array_search($_SERVER['SERVER_NAME'], $prod_server, true) === false) {
				$server = "dev";
			} else {
				$server = "prod";
			}

			$session = array(
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
			$cookie  = array(
				'name'   => 'portal_cookies',
				'value'  => base64_encode(json_encode($session)),
				'expire' => '7200'
			);
			$this->input->set_cookie($cookie);

			$date     = date("Y-m-d");
			$datetime = date("Y-m-d H:i:s");
			$time     = date("H:i");

			$data_log = $this->dhome->get_log_user(base64_decode($this->session->userdata("-id_user-")));
			if (!$data_log) {
				$data_row = array(
					"id_user"   => base64_decode($this->session->userdata("-id_user-")),
					"tanggal"   => $date,
					"jam_login" => $time,
					"na"        => 'n',
					"del"       => 'n'
				);
				$this->dgeneral->insert("tbl_userlog", $data_row);
			}
			if (PASSWORD_EXPIRED_MODE)
				if (isset($data->tanggal_pass_update)) {
					// $next_pass_update = date('Y-m-d',strtotime('+1 month',strtotime($data->tanggal_pass_update)));
					$next_pass_update = date('Y-m-d', strtotime('+' . PASSWORD_EXPIRED_MONTH . ' month', strtotime($data->tanggal_pass_update)));
					if ($next_pass_update <= date('Y-m-d', time()))
						$link = 'home/expired/password/?key=' . $this->generate->kirana_encrypt($_POST['username']);
				} else {
					$link = 'home/expired/password/?key=' . $this->generate->kirana_encrypt($_POST['username']);
				}

			if ($data->pass_update !== 'y') {
				$link = 'home/reset/password/?key=' . $this->generate->kirana_encrypt($_POST['username']);
			}

			$sts = "OK";
		} else {
			$sts = "NotOK";
		}
		$return = array('sts' => $sts, 'link' => $link);
		echo json_encode($return);
	}

	public function validation()
	{
		if (isset($_GET['key'])) {
			$cookies = $_GET['key'];
			$cookie  = array(
				'name'   => 'portal_cookies',
				'value'  => $cookies,
				'expire' => '7200'
			);
			$this->input->set_cookie($cookie);

			if (isset($_GET['option'])) {
				$this->general->check_access(base64_decode(json_decode($_GET['option'])));
				$count = $this->get_menu(base64_decode(json_decode($_GET['option'])));
				if ($count > 0)
					redirect('http://' . $_SERVER['HTTP_HOST'] . '/kiranaku/home');
			} else {
				redirect('http://' . $_SERVER['HTTP_HOST'] . '/kiranaku/home');
			}
		} else {
			redirect('http://' . $_SERVER['HTTP_HOST'] . '/git/kiranaku/home/');
		}
	}

	public function get_menu($name = NULL)
	{
		$check_update     = $this->dhome->get_last_modified_menu(base64_decode($this->session->userdata("-nik-")));
        $menu_sess        = $this->session->userdata('kirana_menu');
        $menu_last_update = $this->session->userdata('kirana_menu_last_update');

        if (($menu_sess == NULL && $menu_last_update == NULL) || (isset($check_update) && strtotime($this->generate->regenerateDateTimeFormat($menu_last_update)) < strtotime($this->generate->regenerateDateTimeFormat($check_update->tanggal_edit)))) {
            $menu    = $this->general->get_menu();
            $newdata = array(
                'kirana_menu'             => $menu,
                'kirana_menu_last_update' => date('Y-m-d H:i:s')
            );
            $this->session->set_userdata($newdata);
        } else {
            $menu = $menu_sess;
        }

        if ($name === NULL) {
            echo json_encode($menu);
        } else {
            return count($menu);
        }
	}

	public function sendemail($apps = NULL, $action = NULL)
	{
		switch (true) {
			case ($apps == 'forward' && $action == 'contract'):
				$data = $this->input->post_get(NULL, TRUE);
				$data_recipient = array(
					array(
						"nama" => "Sylviani",
						"email" => "sylviani.oktarista@kiranamegatara.com"
					),
					array(
						"nama" => "Mia Eka",
						"email" => "mia.eka@kiranamegatara.com"
					),
					array(
						"nama" => "Ahmad Lutfi",
						"email" => "lutfi.arief@kiranamegatara.com"
					),
					array(
						"nama" => "Akhmad Syaiful Yamang",
						"email" => "syaiful.yamang@kiranamegatara.com"
					)
				);

				$message = "<html>
							<body style='background-color: #008d4c ; margin:0; font-family: \"Source Sans Pro\",\"Helvetica Neue\",Helvetica,Arial,sans-serif;'>
							<center style='width: 100%;'>
								<div style='display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;'>
									Notifikasi Email Forwarding
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
												<h1 style='margin-bottom: 0;'>Forwarding</h1>
												<hr style='border-color: #ffffff; margin-bottom: 4px; margin-top: 4px;'/>
												<h3 style='margin-top: 0;'>Notifikasi Email</h3>
											</td>
										</tr>
										<tr>
											<td>
												<table style='background-color: #ffffff; margin: auto; -webkit-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); -moz-box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4); box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.4);' role='presentation' border='0' width='100%' cellspacing='0' cellpadding='0'align='center'>
													<tbody>
													<tr>
														<td style='padding: 20px;'>
															<p><strong>Kepada :<br><br> Bapak/Ibu</strong></p>
															<p>Email ini menandakan bahwa ada perubahan contract yang membutuhkan perhatian anda.</p>
															<table style='background: #fff1d0; border-radius: 4px; padding: 10px; width: 100%;'>
																<tr>
																	<td width='20%'>Contract 1</td>
																	<td width='5%'>:</td>
																	<td>" . $data['contract1'] . "</td>
																</tr>
																<tr>
																	<td width='20%'>Contract 2</td>
																	<td width='5%'>:</td>
																	<td>" . $data['contract2'] . "</td>
																</tr>
																<tr>
																	<td width='20%'>Forward 1</td>
																	<td width='5%'>:</td>
																	<td>" . $data['forward1'] . "</td>
																</tr>
																<tr>
																	<td width='20%'>Forward 2</td>
																	<td width='5%'>:</td>
																	<td>" . $data['forward2'] . "</td>
																</tr>
															</table>
															<p>Selanjutnya anda dapat melakukan review pada T-CODE di SAP</p>
														</td>
													</tr>
													<tr>
														<td align='left'
															style='background-color: #ffffff; padding: 20px; border-top: 1px dashed #386d22;'>
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
												<small>Kiranaku Auto-Mail System</small><br/>
												<strong style='color: #214014; font-size: 10px;'>Terkirim pada " . date('d.m.Y H:i:s') . "</strong>
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

				$email_cc     = array();
				$email_to     = array();
				foreach ($data_recipient as $dt) {
					$email_to[] = $dt['email'];
				}

				$this->general->send_email('Notifikasi Email Forwarding', 'Kiranaku', $email_to, $email_cc, $message);

				break;
		}
	}

	public function forgotpass()
	{
		$nik             = $_POST['nik'];
		$email           = $_POST['email'];
		$email_no_domain = explode("@", $email)[0]; //str_replace("@kiranamegatara.com", "", $email);

		if (strpos($email, 'kiranamegatara.com') !== false && strlen($email_no_domain) > 0) {
			$pass = 'abcefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ123456789';
			$pass = substr(str_shuffle($pass), 0, 8);

			$user = $this->dgeneral->get_data_user(NULL, $email, NULL, $nik);
			if ($user) {
				$param = $this->generate->kirana_encrypt($user[0]->nik . "=>" . $pass . "=>" . date('Y-m-d H:i'));
				$url   = base_url() . 'home/reset/?key=' . urlencode($param);

				$message = "<html>";
				$message .= "<body>";
				$message .= "<b>Kepada Bapak/Ibu " . ucwords(strtolower($user[0]->nama)) . ",</b><br><br>";
				$message .= "Berikut adalah pemberitahuan dari KIRANAKU.<br><br>";
				$message .= "<b><u>Konfirmasi Reset Password</u></b><br><br>";
				$message .= "Anda telah menggunakan fasilitas reset password pada Portal KIRANAKU, ";
				$message .= "Untuk melakukan Reset silahkan ikuti langkah-langkah berikut:<br>";
				$message .= "1.	Klik link berikut ini : <a href='$url'>$url</a><br>";
				$message .= "2.	Silahkan login kembali menggunakan username NIK dan Password <b>" . $pass . "</b><br><br>";
				$message .= "<small>Note : link reset password diatas hanya berlaku 1 jam sejak email ini dikirim.</small><br><br>";
				$message .= "Terima kasih,<br>";
				$message .= "KIRANAKU APPS";
				$message .= "<br><br><br><br>";
				$message .= "<em>sent by <b>KIRANAKU Auto-Mail System</b></em><br><img src='" . base_url() . 'assets/apps/img/logo-email.jpg' . "'/>";
				$message .= "</body>";
				$message .= "</html>";

				$email = "syaiful@kiranamegatara.com";

				$this->general->send_email("Konfirmasi Reset Password Untuk Login KiranaKu", "KiranaKu", $email, "", $message);
				$msg = "Silahkan check email Anda";
				$sts = "OK";
			} else {
				$msg = "Email tidak terdaftar";
				$sts = "NotOK";
			}
		} else {
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
		exit();
	}

	public function reset($param = NULL)
	{
		if ($param == NULL && isset($_GET['key'])) {
			$data = $this->generate->kirana_decrypt($_GET['key']);
			$data = explode("=>", $data);
			if (count($data) == 3 && is_array($data) && strtotime(date("Y-m-d H:i")) < strtotime("+1 hours", strtotime($data[2]))) {
				$nik  = $data[0];
				$pass = MD5($data[1]);

				$this->general->connectDbPortal();
				$data_row = array(
					"pass"        => $pass,
					"pass_update" => ''
				);
				$this->dgeneral->update("tbl_user", $data_row, array(
					array(
						'kolom' => 'id_karyawan',
						'value' => $nik
					)
				));
				$this->general->closeDb();

				redirect(base_url());
			} else {
				echo "Sorry, your link has been expired.";
			}
		} else if ($param == "password" && isset($_GET['key'])) {
			//====must be initiate in every view function====/
			$data['module'] = "Kiranaku";
			$data['id']     = $_GET['key'];
			//===============================================/

			$this->load->view('reset', $data);
		} else {
			show_404();
		}
	}


	/**********************************/
	/*			  private  			  */
	/**********************************/

	public function expired($param = NULL)
	{
		if ($param == NULL && isset($_GET['key'])) {
			$data = $this->generate->kirana_decrypt($_GET['key']);
			$data = explode("=>", $data);
			if (count($data) == 3 && is_array($data) && strtotime(date("Y-m-d H:i")) < strtotime("+1 hours", strtotime($data[2]))) {
				$nik  = $data[0];
				$pass = MD5($data[1]);

				$this->general->connectDbPortal();
				$data_row = array(
					"pass"        => $pass,
					"pass_update" => ''
				);
				$this->dgeneral->update("tbl_user", $data_row, array(
					array(
						'kolom' => 'id_karyawan',
						'value' => $nik
					)
				));
				$this->general->closeDb();

				redirect(base_url());
			} else {
				echo "Sorry, your link has been expired.";
			}
		} else if ($param == "password" && isset($_GET['key'])) {
			//====must be initiate in every view function====/
			$data['module'] = "Kiranaku";
			$data['id']     = $_GET['key'];
			//===============================================/

			$this->load->view('expired-password', $data);
		} else {
			show_404();
		}
	}

	public function oldportal()
	{
		$url = $_GET['url'];
		if ($url == NULL)
			show_404();

		$url = str_replace(" ", "&", $url);

		$id_user = $this->session->userdata("-id_user-");
		$nik     = $this->session->userdata("-nik-");
		$ho      = $this->session->userdata("-ho-");
		$gsber   = $this->session->userdata("-gsber-");
		$session = $_SESSION;
		unset($session['kirana_menu']);
		unset($session['kirana_menu_last_update']);
		unset($session['kirana_menu_opened']);
		$page 	 = (isset($_GET['page']) && $_GET['page'] != '' && $_GET['page'] != NULL) ? $_GET['page'] : null;

		$cookie  = array(
			'name'   => 'portal_cookies',
			'value'  => base64_encode(json_encode($session)),
			'expire' => '7200'
		);
		$this->input->set_cookie($cookie);

		switch ($url) {
			case 'k-air':
				$real_uri = 'http://10.0.0.105/uat/k-air/home/validation?key=' . base64_encode(json_encode($session));
				if (isset($_GET['redirect'])) {
					$key = isset($_GET['key']) ? str_replace('/', '-', $_GET['key']) : '';
					$real_uri .= "&redirect=" . base64_encode($_GET['redirect'] . $key);
				}
				redirect($real_uri);
				// redirect('http://10.0.9.171:8080/git/k-air/home/validation?key=' . base64_encode(json_encode($session)));
				// redirect('http://127.0.0.1/uat/k-air/home/validation?key=' . base64_encode(json_encode($session)));
				break;
			case 'k-pro':
																											 
				$real_uri = 'http://10.0.0.105/uat/k-pro/home/validation?key=' . base64_encode(json_encode($session));
				if (isset($_GET['redirect'])) {
					$key = isset($_GET['key']) ? str_replace('/', '-', $_GET['key']) : '';
					$real_uri .= "&redirect=" . base64_encode($_GET['redirect'] . $key);
				}
				redirect($real_uri);
				// redirect('http://10.0.9.171:8080/git/k-air/home/validation?key=' . base64_encode(json_encode($session)));
				// redirect('http://127.0.0.1/uat/k-air/home/validation?key=' . base64_encode(json_encode($session)));
				break;

			case 'tpm':
				redirect('http://10.0.0.105/uat/tpm/home/validation?key=' . base64_encode(json_encode($session)));
				// redirect('http://127.0.0.1/uat/tpm/home/validation?key=' . base64_encode(json_encode($session)));
				break;

			case 'iats':
				redirect('http://10.0.0.105/uat/audit/index.php?r=site%2Flogin&a=' . base64_encode(json_encode($session))) . '&b=1&c=' . $nik;
				// redirect('http://127.0.0.1/uat/audit/index.php?r=site%2Flogin&a=' . base64_encode(json_encode($session))) . '&b=1&c=' . $nik;
				break;

			case 'kiranalytics':
				redirect('http://10.0.0.105/uat/kiranalytics2/validation.php?key=' . base64_encode(json_encode($session)) . '&nik=' . $nik . '&ho=' . $ho . '&gsber=' . $gsber);
				break;

			case 'klise':
				redirect('http://10.0.0.105/uat/klise/home/validation?key=' . base64_encode(json_encode($session)) . '&page=' . $page);
				// redirect('http://127.0.0.1/uat/klise/home/validation?key=' . base64_encode(json_encode($session)) . '&page='. $page);
				// redirect('http://127.0.0.1/uat/testing/klise/home/validation?key=' . base64_encode(json_encode($session)));
				break;

			default:
				redirect('http://10.0.0.249/dev/kiranaku/home/getcookie_portal.php?key=' . base64_encode(json_encode($session)) . '&nik=' . $nik . '&id_user=' . $id_user . '&url=' . base64_encode($url));
				break;
		}
	}

	private function reset_password()
	{
		$pass          = $_POST['password'];
		$konf_password = $_POST['konf_password'];
		$nik           = $this->generate->kirana_decrypt($_POST['id']);
		if ($pass === $konf_password) {
			$this->general->connectDbPortal();
			$this->dgeneral->begin_transaction();

			$data_row = array(
				"pass"                => MD5($pass),
				"pass_update"         => 'y',
				"tanggal_pass_update" => date('Y-m-d H:i:s')
			);
			$this->dgeneral->update("tbl_user", $data_row, array(
				array(
					'kolom' => 'id_karyawan',
					'value' => $nik
				)
			));

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
		} else {
			$msg = "Periksa kembali data yang dimasukkan";
			$sts = "NotOK";
		}

		$return = array('sts' => $sts, 'msg' => $msg);
		echo json_encode($return);
	}

	private function get_news()
	{
		$data = $this->dhome->get_data_berita_web("news", 5);
		echo json_encode($data);
	}

	private function get_gallery()
	{
		$data = $this->dhome->get_data_berita_web("news", 20);
		echo json_encode($data);
	}



	/**********************************/
	/*      Direct to Old Portal      */
	/**********************************/

	private function get_new_role()
	{
		$data = $this->dhome->get_data_new_role(5);
		echo json_encode($data);
	}
}
