<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notifications extends MX_Controller
{

    private $data;

    public function __construct()
    {
        parent::__construct();
//        $this->general->check_access();
        $this->data['module'] = "Management Notification";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dnotifications');
    }

    public function apps()
    {
        $this->data['title'] = "Notification Apps";
        $this->data['title_form'] = "App";
        $this->data['datas'] = $this->get_app();

        $this->load->view('apps', $this->data);
    }

    public function categories($mode = "table")
    {
        $this->data['title'] = "Notification Categories";
        $this->data['title_form'] = "Category";
        if ($mode == "nested") {
            $this->data['apps'] = $this->get_app();
            $this->data['datas'] = $this->get_category_tree();
            $trees = $this->renderTree($this->data['datas'], 0);
            $this->data['trees'] = $trees;
            $this->load->view('categories-nested', $this->data);
        } else {
            $this->data['apps'] = $this->get_app();
            $this->data['datas'] = $this->get_category();
            $this->load->view('categories', $this->data);
        }
    }

    public function get($param)
    {
        switch ($param) {
            case 'app':
                $return = $this->get_app($_POST['id']);
                break;
            case 'category':
                $return = $this->get_category($_POST['id']);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    public function delete($param)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data = $_POST;
        $id = $this->generate->kirana_decrypt($data['id']);
        unset($data['id']);
        switch ($param) {
            case 'app':
                $this->general->set('delete_del', "tbl_notifications_apps",
                    array(
                        array(
                            'kolom' => "notification_app_id",
                            'value' => $id
                        )
                    )
                );
                break;
            case 'category':
                $this->general->set('delete_del', "tbl_notifications_categories",
                    array(
                        array(
                            'kolom' => "notification_category_id",
                            'value' => $id
                        )
                    )
                );
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil dihapus";
            $sts = "OK";
        }

        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);

        echo json_encode($return);

    }

    public function set($param, $action)
    {
        $data = $_POST;
        $id = $this->generate->kirana_decrypt($data['id']);
        unset($data['id']);
        $this->general->connectDbPortal();
        switch ($param) {
            case 'app':
                $return = $this->general->set($action, "tbl_notifications_apps", [
                    [
                        "kolom" => "notification_app_id",
                        "value" => $id
                    ]
                ]);
                break;
            case 'category':
                $return = $this->general->set($action, "tbl_notifications_categories", [
                    [
                        "kolom" => "notification_category_id",
                        "value" => $id
                    ]
                ]);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        $this->general->closeDb();

        echo json_encode($return);
    }

    public function save($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'app':
                $return = $this->save_app($data);
                break;
            case 'category':
                $return = $this->save_category($data);
                break;
            case 'category-tree':
                $return = $this->save_category_tree($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    private function get_app($id = null, $all = false)
    {
        $id = isset($id) ? $this->generate->kirana_decrypt($id) : null;
        $apps = $this->dnotifications->get_apps($id, $all);;
        return $apps;
    }

    private function get_category($id = null, $all = false)
    {
        $id = isset($id) ? $this->generate->kirana_decrypt($id) : null;
        $categories = $this->dnotifications->get_categories($id, $all);
        return $categories;
    }

    private function get_category_tree($id = null, $all = false)
    {
        $id = isset($id) ? $this->generate->kirana_decrypt($id) : null;
        $categories = $this->dnotifications->get_categories_tree($id, $all);
        return $categories;
    }

    private function renderTree($tree = array(array('name' => '', 'depth' => '')))
    {

        $current_depth = 0;
        $counter = 0;

        $result = '<ol class="sortable">';

        foreach ($tree as $node) {
            $node_depth = $node['depth'];
            $node_name = $node['category_name'];
            $node_id = $node['notification_category_id'];

            if ($node_depth == $current_depth) {
                if ($counter > 0) $result .= '</li>';
            } elseif ($node_depth > $current_depth) {
                $result .= '<ol>';
                $current_depth = $current_depth + ($node_depth - $current_depth);
            } elseif ($node_depth < $current_depth) {
                $result .= str_repeat('</li></ol>', $current_depth - $node_depth) . '</li>';
                $current_depth = $current_depth - ($current_depth - $node_depth);
            }
            $result .= '<li id="category_' . $node_id . '"';
            $result .= $node_depth < 2 ? ' class="open"' : '';
            $result .= '><div>' . $node_name . '</div>';
            ++$counter;
        }
        $result .= str_repeat('</li></ol>', $node_depth) . '</li>';

        $result .= '</ol>';

        return $result;
    }

    private function save_app($data)
    {

        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['notification_app_id'];
        unset($data_row['notification_app_id']);

        if (!empty($id)) {

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $data = $this->dgeneral->update('tbl_notifications_apps', $data_row, array(
                array(
                    "kolom" => "notification_app_id",
                    "value" => $id
                )
            ));

        } else {

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $data = $this->dgeneral->insert('tbl_notifications_apps', $data_row);
            $id = $this->db->insert_id();
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_category($data)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['notification_category_id'];
        unset($data_row['notification_category_id']);

        if (!empty($id)) {

            $data_row = $this->dgeneral->basic_column('update', $data_row);

            $data = $this->dgeneral->update('tbl_notifications_categories', $data_row, array(
                array(
                    "kolom" => "notification_category_id",
                    "value" => $id
                )
            ));

        } else {

            $data_row = $this->dgeneral->basic_column('insert', $data_row);

            $data = $this->dgeneral->insert('tbl_notifications_categories', $data_row);
            $id = $this->db->insert_id();
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function save_category_tree($data)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $result = json_decode($data_row['result']);

        foreach ($result as $item) {

            if (!empty($item->id)) {

                $id = $item->id;

                $data_row = $this->dgeneral->basic_column('update', [
                    'lft' => $item->left,
                    'rgt' => $item->right,
                    'parent_id' => $item->parent_id,
                    'depth' => $item->depth
                ]);

                $data = $this->dgeneral->update('tbl_notifications_categories', $data_row, array(
                    array(
                        "kolom" => "notification_category_id",
                        "value" => $id
                    )
                ));

            }
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data berhasil ditambahkan";
            $sts = "OK";
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    public function get_notifications()
    {

        $notifications_apps = $this->get_notifications_apps();
        $data['notifications'] = $this->load->view('components/notifications', array(
            'notifications_apps' => $notifications_apps
        ), true);

        $data['data'] = $this->dnotifications->get_notifications_count();

        echo json_encode($data);

    }

    public function get_notifications2()
    {
        $notifications_apps = $this->get_notifications_apps();
        $data['notifications'] = $this->load->view('components/notifications2', array(
            'notifications_apps' => $notifications_apps
        ), true);

        $data['data'] = $this->dnotifications->get_notifications_count();

        echo json_encode($data);
    }

    public function get_notifications3_old()
    {

        $notifications_cats = $this->get_notifications_cats();
        $data['notifications'] = $this->load->view('components/notifications3', array(
            'notifications_cats' => $notifications_cats
        ), true);

        $data['data'] = $this->dnotifications->get_notifications_count();

        echo json_encode($data);

    }
    public function get_notifications3()
    {


    }

    public function get_notifications_apps()
    {
        $apps = $this->dnotifications->get_notifications_apps();
        foreach ($apps as $index => $app) {
            $apps[$index]->categories = $this->dnotifications->get_notifications_categories($app->notification_app_id);

            foreach ($apps[$index]->categories as $indexCategory => $category) {
                $notifications = $this->dnotifications->get_notifications($category->notification_category_id);
                foreach ($notifications as $indexNotification => $notification) {
                    $url = $notification->url;
                    if (!isset($notification->url) && !empty($notification->url)) {
                        $url = str_replace(':key:', $notification->notification_key, $category->notification_url);
                    }

                    if ($notification->permanent == "0") {
                        $url = base_url('notifications/redirect/' . $notification->notification_id);
                    }

                    $notification->url = $url;

                    $notifications[$indexNotification] = $notification;
                }
                $apps[$index]->categories[$indexCategory]->notifications = $notifications;
            }
        }
        return $apps;
    }

    public function redirect($notification_id)
    {
        if (isset($notification_id)) {
            $notification = $this->dnotifications->get_notification($notification_id);
            $this->general->connectDbPortal();
            $update = $this->dgeneral->basic_column('update', array('status' => 1));
            $dataUpdate = $this->dgeneral->update('tbl_notifications', $update, array(
                array(
                    "kolom" => "notification_id",
                    "value" => $notification_id
                )
            ));
            $this->general->closeDb();
            if ($dataUpdate)
                redirect($notification->url);
            else
                redirect(base_url('home'));
        } else
            redirect(base_url('home'));
    }

    public function get_notifications_cats()
    {
        $cats = $this->dnotifications->get_notifications_categories2();
        foreach ($cats as $index => $cat) {
            $notifications = $this->dnotifications->get_notifications($cat->notification_category_id);
            foreach ($notifications as $indexNotification => $notification) {
                $url = $notification->url;
                if (!isset($notification->url) && !empty($notification->url)) {
                    $url = str_replace(':key:', $notification->notification_key, $cat->notification_url);
                }

                if ($notification->permanent == "0") {
                    $url = base_url('notifications/redirect/' . $notification->notification_id);
                }

                $notification->url = $url;

                $notifications[$indexNotification] = $notification;
            }
            $cats[$index]->notifications = $notifications;
        }
        return $cats;
    }

}

