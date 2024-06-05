<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Info Karyawan - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Infokaryawan extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        // $this->general->check_access();
        $this->data['module'] = "Info Karyawan";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dinfokaryawan');
    }

    public function index($nik=null)
    {
        $selected_lokasi = array('KMTR');
        if (isset($_POST['lokasi']) && !empty($_POST['lokasi'])) {
            $selected_lokasi = $_POST['lokasi'];
        }

        // $karyawans = $this->get_karyawan($selected_lokasi);

        // foreach ($karyawans as $i => $karyawan) {
            // if($karyawan->ho =='y'){
                // $bagian = (empty($karyawan->nama_departemen))?$karyawan->nama_divisi:$karyawan->nama_departemen;
            // }else{
                // $bagian = (empty($karyawan->nama_seksi))?$karyawan->nama_departemen:$karyawan->nama_seksi;
                // $bagian = (empty($bagian))?$karyawan->nama_sub_divisi:$bagian;
                // $bagian = (empty($bagian))?$karyawan->nama_pabrik:$bagian;
            // }
            // $karyawan->nama_bagian = $bagian;
            // $karyawans[$i] = $karyawan;
        // }

        $this->data['title'] = "Info Karyawan";
        // $this->data['karyawans'] = $karyawans;
        $this->data['lokasi_options'] = $this->dgeneral->get_master_plant();
        $this->data['selected_lokasi'] = $selected_lokasi;

        $this->load->view('infokaryawan', $this->data);
    }
	
	public function get($param = NULL, $param2 = NULL) {
		switch ($param) {
			case 'karyawan':
				if(isset($_POST['lokasi'])){
					$lokasi	= array();
					foreach ($_POST['lokasi'] as $dt) {
						array_push($lokasi, $dt);
					}
				}else{
					$lokasi  = NULL;
				}
				if($param2=='bom'){
					header('Content-Type: application/json');
					$return = $this->dinfokaryawan->get_data_karyawan_bom('open', $lokasi);
					echo $return;
					break;
				}else{
					$this->get_karyawan($lokasi);
					break;
				}
			default:
				$return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
				echo json_encode($return);
				break;
		}
	}
	

    private function get_karyawan($pabrik = null)
    {
        $karyawans = $this->dinfokaryawan->get_karyawan($pabrik);

        return $karyawans;
    }
}