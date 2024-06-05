<?php
/**
 * @application  : Info Kirana (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */


class Menus extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dmenus');

        $this->load->model('notifications/dnotifications');
    }

    public function index()
    {
        $this->general->check_access();
        $data['module'] = "Management Menu";
        $data['title'] = "Management Menu";
        $data['title_form'] = "Menu";
        $data['user'] = $this->general->get_data_user();
        $data['divisis'] = $this->dmenus->get_divisi();
        $data['levels'] = $this->dmenus->get_level();
        $data['notif_categories'] = $this->dnotifications->get_categories();
        $data['departments'] = $this->dmenus->get_department();
        $data['menus'] = $this->dmenus->get_menu_tree();

        $this->load->view('menus', $data);
    }

    public function get_menu()
    {
        $this->general->connectDbPortal();

        $menus = $this->dmenus->get_menu_tree();

        $this->general->closeDb();

        $menu_view = $this->load->view('components/menu_table', compact('menus'), true);

        echo json_encode(array(
            'menus' => $menus,
            'view' => $menu_view
        ));
    }

    public function delete()
    {
        $data = $_POST;
        $id = $this->generate->kirana_decrypt($data['id']);
        unset($data['id']);
        $result = $this->dmenus->delete_data(
            $id
        );

        echo json_encode($result);
    }

    public function set_data($method, $action = null)
    {
        $result = array();
        if (isset($method)) {
            $data = $_POST;
            switch ($method) {
                case "publish" :
                    if (isset($data['id']) && !empty($data['id'])) {
                        $id = $this->generate->kirana_decrypt($data['id']);
                        unset($data['id']);
                        $result = $this->dmenus->set_data(
                            $id,
                            $action
                        );
                    }
                    break;
                case "save" :
                    if (isset($data['id']) && !empty($data['id'])) {
                        $id = $this->generate->kirana_decrypt($data['id']);
                        unset($data['id']);
                        if (isset($data['divisi_akses']))
                            $data['divisi_akses'] = '.' . $data['divisi_akses'];
                        if (isset($data['departemen_akses']))
                            $data['departemen_akses'] = '.' . $data['departemen_akses'];
                        if (isset($data['nik_akses']))
                            $data['nik_akses'] = '.' . $data['nik_akses'];

                        if (isset($data['id_level'])) {
                            $level_akses = ".1.2.";

                            $level_akses = $level_akses . $this->level_akses($data['id_level']);
                            $data['level_akses'] = $level_akses;
                        }

                        $data['main'] = 'n';

                        $result = $this->dmenus->update_data(
                            $id,
                            $data,
                            array(
                                array(
                                    'kolom' => 'id_menu',
                                    'value' => $id
                                )
                            )
                        );
                    } else {
                        unset($data['id']);
                        if (isset($data['divisi_akses']))
                            $data['divisi_akses'] = '.' . $data['divisi_akses'];
                        if (isset($data['departemen_akses']))
                            $data['departemen_akses'] = '.' . $data['departemen_akses'];
                        if (isset($data['nik_akses']))
                            $data['nik_akses'] = '.' . $data['nik_akses'];

                        if (isset($data['id_level'])) {
                            $level_akses = ".1.2.";

                            $level_akses = $level_akses . $this->level_akses($data['id_level']);
                            $data['level_akses'] = $level_akses;
                        }

                        $data['main'] = 'n';

                        $result = $this->dmenus->save_data(
                            $data
                        );
                    }

                    break;
                case "save_hak" :
                    if (isset($data['id']) && !empty($data['id'])) {
                        $id = $this->generate->kirana_decrypt($data['id']);
                        unset($data['id']);
                        if (isset($data['nik_akses']))
                            $data['nik_akses'] = '.' . $data['nik_akses'];

                        $result = $this->dmenus->update_data(
                            $id,
                            $data,
                            array(
                                array(
                                    'kolom' => 'id_menu',
                                    'value' => $id
                                )
                            )
                        );
                    }else{

                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                        $result = array('sts' => $sts, 'msg' => $msg);
                    }
                    break;
            }
        }

        echo json_encode($result);
    }

    private function level_akses($id_level = 0)
    {
        $level_akses = '';
        if ($id_level != 0) {
            $level = $this->dmenus->get_level($id_level);
            $level_akses .= $level->id_level . '.';
            if (isset($level->parent_level) && $level->parent_level != 0)
                $level_akses .= $this->level_akses($level->parent_level);
        } else {
            $level = $this->dmenus->get_level($id_level);
            $level_akses .= $level->id_level . '.';
        }

        return $level_akses;
    }


    public function get_data()
    {
        $formData = $_POST;
        $id = $this->generate->kirana_decrypt($formData['id']);
        $data = $this->dmenus->get_data($id);

        echo json_encode(array('data' => $data, 'id' => $formData['id']));
    }

    public function get_nik_akses()
    {
        $id = $this->generate->kirana_decrypt($_POST['id']);

        $menu = $this->dmenus->get_all_data($id);

        $data = $menu;

        $search = isset($_POST['search']) ? $_POST['search'] : null;
        $filter = isset($_POST['filter']) ? $_POST['filter'] : null;

        if (isset($data)) {
            $karyawan = $this->dmenus->get_karyawans(
                null,
                $data->level_akses,
                $data->divisi_akses,
                $data->departemen_akses,
                null
            );
//            $selected = $this->dmenus->get_karyawans(
//                $data->nik_akses,
//                $data->level_akses,
//                $data->divisi_akses,
//                $data->departemen_akses,
//                null
//            );

//            foreach ($selected as $index => $select)
//            {
//                $selected[$index] = intval($select->id_karyawan);
//            }

            $selected = explode('.',$data->nik_akses);

            foreach ($selected as $index => $select)
            {
                $selected[$index] = intval($select);
            }
        } else {
            $selected = array();
            $karyawan = array();
        }

        $addition = $this->dmenus->get_karyawan_ktp();

//        $addition	= array(
//            array("nik"=>90070, "nama"=>"Agus Nugroho"),
//            array("nik"=>91163, "nama"=>"Edit Setiantono"),
//            array("nik"=>91962, "nama"=>"Rica Rosita")
//        );

        echo json_encode(array(
            'menu' => $data,
            'karyawan' => $karyawan,
            'selected' => $selected,
//            'selected_arr' => $selected_arr,
            'user_ktp' => $addition
        ));
    }
}
