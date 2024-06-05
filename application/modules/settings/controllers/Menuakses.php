<?php
/**
 * @application  : Menu Akses Karyawan (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Menuakses extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dmenuakses');
    }

    public function index()
    {
        $this->general->check_access();
        $data['module'] = "Menu Akses Karyawan";
        $data['title'] = "Menu Akses";
        $data['title_form'] = "Menu Akses";
        $data['user'] = $this->general->get_data_user();

        $nik=$this->session->userdata("-nik-");
        $nik = base64_decode($nik);
        if(isset($_POST['cari']))
            $nik = $_POST['cari'];

        $data['karyawans'] = $this->dmenuakses->get_karyawans(null,$nik);

        $data['menuakses'] = $this->dmenuakses->get_all_data($nik);

        $this->load->view('menuakses', $data);
    }

    public function get_data()
    {
        $formData = $_POST;
        $id = $this->generate->kirana_decrypt($formData['id']);
        $data = $this->djenissakit->get_data($id);

        echo json_encode(array('data'=>$data,'id'=>$formData['id']));
    }

    public function save($param)
    {

        $data = $_POST;
        switch ($param) {
            case 'compare':

                $this->general->connectDbPortal();

                $return = $this->save_compare($data);

                $this->general->closeDb();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    private function save_compare($data)
    {
        $data_row = $data;

        $this->dgeneral->begin_transaction();

        $id_karyawan = $this->generate->kirana_decrypt($data_row['id_karyawan']);

        $list_nik = join('.',$data_row['karyawans']).'.';

        $menus = $this->dmenuakses->get_menus($id_karyawan);

        foreach ($menus as $menu)
        {

            $nik_akses = $menu->nik_akses.''.$list_nik;
            $update = $this->dgeneral->basic_column('update',array(
                'nik_akses' => $nik_akses
            ));
            $data = $this->dgeneral->update('tbl_menu', $update, array(
                array(
                    "kolom" => "id_menu",
                    "value" => $menu->id_menu
                )
            ));
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Compare Menu berhasil";
            $sts = "OK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }
}