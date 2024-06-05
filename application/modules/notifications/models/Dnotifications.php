<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dnotifications extends CI_Model {
    public function get_apps ($id = null, $all = false)
    {
        $this->general->connectDbPortal();

        $this->db->select('*');
        $this->db->from('tbl_notifications_apps');
        if(isset($id))
            $this->db->where('notification_app_id',$id);

        $this->db->where('del','n');

        $query = $this->db->get();

        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_categories ($id = null, $all = false)
    {
        $this->general->connectDbPortal();

        $this->db->select('tbl_notifications_categories.*,tbl_notifications_apps.app_name');
        $this->db->from('tbl_notifications_categories');
        $this->db->join('tbl_notifications_apps','tbl_notifications_categories.app_id = tbl_notifications_apps.notification_app_id');
        if(isset($id))
            $this->db->where('notification_category_id',$id);

        $this->db->where('tbl_notifications_categories.del','n');

        $query = $this->db->get();

        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_categories_tree ($app_id = null, $id = null, $all = false)
    {
        $this->general->connectDbPortal();

        $query = $this->db->query("SELECT DISTINCT node.notification_category_id, node.category_name, node.notification_url_all, 
              node.lft, node.rgt, node.depth, node.parent_id
            FROM tbl_notifications_categories AS node,
                    tbl_notifications_categories AS parent
            WHERE node.lft BETWEEN parent.lft AND parent.rgt 
            AND parent.del = 'n' 
            AND node.del = 'n'
            ORDER BY node.lft");

        $result = $query->result_array();

        $this->general->closeDb();

        return $result;
    }

    public function get_notifications_apps()
    {
        $this->general->connectDbPortal();

        $this->db->select('
            tbl_notifications_apps.notification_app_id,
            tbl_notifications_apps.app_name,
            tbl_notifications_apps.app_icon,
            tbl_notifications_apps.label_name,
            count(tbl_notifications.notification_id) as notification_count
        ');
        $this->db->from('tbl_notifications');
        $this->db->join(
            'tbl_notifications_categories',
            "tbl_notifications_categories.notification_category_id = tbl_notifications.notification_category_id
            and tbl_notifications_categories.del = 'n'
            and tbl_notifications_categories.na = 'n'
            and tbl_notifications.status = 0"
        );
        $this->db->join(
            'tbl_notifications_apps',
            'tbl_notifications_apps.notification_app_id = tbl_notifications_categories.app_id'
        );

        $this->db->where('tbl_notifications.user_id',base64_decode($this->session->userdata("-id_user-")));

        $this->db->group_by('
            tbl_notifications_apps.notification_app_id, 
            tbl_notifications_apps.app_name, 
            tbl_notifications_apps.app_icon, 
            tbl_notifications_apps.label_name,
            tbl_notifications_apps.priority
        ');

        $this->db->order_by('tbl_notifications_apps.priority');

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_notifications_categories($app_id)
    {
        $this->general->connectDbPortal();

        $this->db->select('
            tbl_notifications_categories.app_id,
            tbl_notifications_categories.notification_category_id,
            tbl_notifications_categories.category_name,
            tbl_notifications_categories.notification_url_all,
            count(tbl_notifications.notification_id) as notification_count
        ');
        $this->db->from('tbl_notifications');
        $this->db->join(
            'tbl_notifications_categories',
            "tbl_notifications_categories.notification_category_id = tbl_notifications.notification_category_id
            and tbl_notifications_categories.del = 'n'
            and tbl_notifications_categories.na = 'n'
            and tbl_notifications_categories.app_id = $app_id
            and tbl_notifications.status = 0"
        );

        $this->db->where('tbl_notifications.user_id',base64_decode($this->session->userdata("-id_user-")));

        $this->db->group_by('
            tbl_notifications_categories.app_id,
            tbl_notifications_categories.notification_category_id, 
            tbl_notifications_categories.category_name, 
            tbl_notifications_categories.notification_url_all, 
            tbl_notifications_categories.priority
        ');

        $this->db->order_by('tbl_notifications_categories.priority');

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_notifications_categories2()
    {
        $this->general->connectDbPortal();

        $this->db->select('
            tbl_notifications_categories.app_id,
            tbl_notifications_categories.notification_category_id,
            tbl_notifications_categories.category_name,
            tbl_notifications_categories.notification_url_all,
            tbl_notifications_apps.app_icon,
            tbl_notifications_apps.app_name,
            count(tbl_notifications.notification_id) as notification_count
        ');
        $this->db->from('tbl_notifications');
        $this->db->join(
            'tbl_notifications_categories',
            "tbl_notifications_categories.notification_category_id = tbl_notifications.notification_category_id
            and tbl_notifications_categories.del = 'n'
            and tbl_notifications_categories.na = 'n'
            and tbl_notifications.status = 0"
        );
        $this->db->join(
            'tbl_notifications_apps',
            "tbl_notifications_categories.app_id = tbl_notifications_apps.notification_app_id
            and tbl_notifications_apps.del = 'n'
            and tbl_notifications_apps.na = 'n'"
        );

        $this->db->where('tbl_notifications.user_id',base64_decode($this->session->userdata("-id_user-")));

        $this->db->group_by('
            tbl_notifications_categories.app_id,
            tbl_notifications_categories.notification_category_id, 
            tbl_notifications_categories.category_name, 
            tbl_notifications_categories.notification_url_all, 
            tbl_notifications_apps.app_icon,
            tbl_notifications_apps.app_name,
            tbl_notifications_categories.priority
        ');

        $this->db->order_by('tbl_notifications_categories.priority');

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_notifications($category_id=null)
    {
        $this->general->connectDbPortal();

        $this->db->select('
            tbl_notifications.*,tbl_notifications_categories.alias_code
        ');
        $this->db->from('tbl_notifications');
        $this->db->join('tbl_notifications_categories','tbl_notifications.notification_category_id = tbl_notifications_categories.notification_category_id');

        $this->db->where('tbl_notifications.status',0);

        if(isset($category_id) && !empty($category_id))
            $this->db->where('tbl_notifications.notification_category_id',$category_id);

        $this->db->where('tbl_notifications.user_id',base64_decode($this->session->userdata("-id_user-")));

        $this->db->order_by('tbl_notifications.tanggal_buat');

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_notifications_count($category_id=null)
    {
        $this->general->connectDbPortal();

        $this->db->select('
            count(tbl_notifications.notification_id) as notification_count,
            tbl_notifications_categories.alias_code,
            tbl_notifications_apps.alias_code as app_alias_code,
            tbl_notifications_apps.app_name 
        ');
        $this->db->from('tbl_notifications');
        $this->db->join('tbl_notifications_categories','tbl_notifications.notification_category_id = tbl_notifications_categories.notification_category_id');
        $this->db->join('tbl_notifications_apps','tbl_notifications_apps.notification_app_id = tbl_notifications_categories.app_id');

        $this->db->where('tbl_notifications.status',0);

        if(isset($category_id) && !empty($category_id))
            $this->db->where('tbl_notifications.notification_category_id',$category_id);

        $this->db->where('tbl_notifications.user_id',base64_decode($this->session->userdata("-id_user-")));

        $this->db->group_by('tbl_notifications_apps.alias_code,tbl_notifications_categories.alias_code,tbl_notifications_apps.app_name');

        $query = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();

        return $result;
    }

    public function get_notification($notification_id)
    {
        $this->general->connectDbPortal();

        $this->db->select('
            tbl_notifications.*
        ');
        $this->db->from('tbl_notifications');

        $this->db->where('tbl_notifications.status',0);
        $this->db->where('tbl_notifications.notification_id',$notification_id);
        $this->db->where('tbl_notifications.user_id',base64_decode($this->session->userdata("-id_user-")));

        $this->db->order_by('tbl_notifications.tanggal_buat');

        $query = $this->db->get();
        $result = $query->row();

        $this->general->closeDb();

        return $result;
    }
}