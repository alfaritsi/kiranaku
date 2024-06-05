<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
@application    : Nusira Workshop
@author         : Akhmad Syaiful Yamang (8347)
@date           : 02-Jun-20
@contributor    :
            1. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            2. <insert your fullname> (<insert your nik>) <insert the date>
               <insert what you have modified>
            etc.
*/

class Transaksi extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('dtransaksinusira');
    }

    public function index()
    {
        show_404();
    }

    public function approve($param = NULL)
    {
        //====must be initiate in every view function====/
        $this->general->check_access();
        $data['generate'] = $this->generate;
        $data['module']   = $this->router->fetch_module();
        $data['user']     = $this->general->get_data_user();
        //===============================================/

        $data['title']      = "Konfirmasi Delivery Date";
        $data['title_form'] = "Konfirmasi Delivery Date";
        $data['pabrik']     = $this->dgeneral->get_master_plant(NULL, false, NULL, 'ERP');
        $this->load->view("transaksi/approve", $data);
    }

    //=================================//
    //		  PROCESS FUNCTION 		   //
    //=================================//
    public function get($param = NULL)
    {
        switch ($param) {
            case 'approve':
                $post = $this->input->post(NULL, TRUE);
                $status_filter = $this->input->post("status_filter", TRUE);
                $result = $this->dtransaksinusira->get_pi_header(array(
                    "connect" => TRUE,
                    "app" => "nusira",
                    "no_pi" => $this->input->post("no_pi", TRUE),
                    "IN_plant" => $this->input->post("plant_filter", TRUE),
                    "IN_nsw_check" => $status_filter,
                    "NOT_IN_status" => array('finish', 'drop', 'deleted'),
                    "NOT_IN_status_pi" => array('admin PR', 'Atasan Pertama PR', 'Atasan Kedua PR'),
                    "encrypt" => array("tujuan_inv"),
                    "return" => $this->input->post("return", TRUE),
                ));
                if (isset($post['return']) && $post['return'] == "json") {
                    $result->detail = $this->dtransaksinusira->get_pi_detail(array(
                        "connect" => TRUE,
                        "app" => "nusira",
                        "no_pi" => $this->input->post("no_pi", TRUE)
                    ));
                    $result->reason = $this->dtransaksinusira->get_data_pi_reason(array(
                        "connect" => TRUE
                    ));
                    $result->history = $this->dtransaksinusira->get_pi_detail(array(
                        "connect" => TRUE,
                        "app" => "nusira",
                        "no_pi" => $this->input->post("no_pi", TRUE),
                        "all" => "y"
                    ));
                    echo json_encode($result);
                } else if (isset($post['return']) && $post['return'] == "datatables") {
                    echo $result;
                } else {
                    return $result;
                }
                break;
        }
    }

    public function save($param = NULL)
    {
        switch ($param) {
            case 'approve':
                $this->save_approve();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                echo json_encode($return);
                break;
        }
    }

    /**********************************/
    /*			  private  			  */
    /**********************************/
    private function save_approve()
    {
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $post = $this->input->post(NULL, TRUE);
        if (!empty($post['no_pi'])) {
            foreach ($post['no'] as $key => $val) {
                $data_detail = $this->dtransaksinusira->get_pi_detail(array(
                    "connect" => TRUE,
                    "app" => "nusira",
                    "no_pi" => $post['no_pi'],
                    "no" => $val
                ));

                $data_delete = $this->dgeneral->basic_column("delete", NULL);
                $this->dgeneral->update(
                    "tbl_pi_detail",
                    $data_delete,
                    array(
                        array(
                            'kolom' => 'no_pi',
                            'value' => $post['no_pi']
                        ),
                        array(
                            'kolom' => 'no',
                            'value' => $val
                        ),
                        array(
                            'kolom' => 'na',
                            'value' => 'n'
                        ),
                        array(
                            'kolom' => 'del',
                            'value' => 'n'
                        )
                    )
                );

                $data_insert = array(
                    'plant'          => $data_detail->plant,
                    'no_pi'          => $post['no_pi'],
                    'no'             => $val,
                    'itnum'          => $data_detail->itnum,
                    'matnr'          => $data_detail->matnr,
                    'kdmat'          => $data_detail->kdmat,
                    'tipe_pi'        => $data_detail->tipe_pi,
                    'perm_invest'    => $data_detail->perm_invest,
                    'spesifikasi'    => $data_detail->spesifikasi,
                    'jumlah'         => $data_detail->jumlah,
                    'satuan'         => $data_detail->satuan,
                    'harga'          => $data_detail->harga,
                    'total'          => $data_detail->total,
                    'status'         => $data_detail->status,
                    'req_deliv_date' => $data_detail->req_deliv_date,
                    'is_done_recom'  => $data_detail->is_done_recom,
                    'status_nsw'     => isset($post['status_nsw'][$key]) ? 1 : 0,
                    'nsw_durasi_mgg' => empty($post['durasi'][$key]) ? NULL : $post['durasi'][$key],
                    'nsw_entry_date' => date('Y-m-d'),
                    'nsw_reason'     => empty($post['reason'][$key]) ? NULL : $post['reason'][$key],
                    'acc_assign'     => $data_detail->acc_assign,
                    'asset_class'    => $data_detail->asset_class,
                    'asset_desc'     => $data_detail->asset_desc,
                    'cost_center'    => $data_detail->cost_center,
                    'gl_account'     => $data_detail->gl_account,
                    'ntgew'          => $data_detail->ntgew
                );
                $data_insert = $this->dgeneral->basic_column("insert_full", $data_insert);
                $this->dgeneral->insert("tbl_pi_detail", $data_insert);
            }

            $data_header = array(
                'nsw_check'    => 1,
                'login_edit'   => base64_decode($this->session->userdata("-id_user-")),
                'tanggal_edit' => $datetime
            );
            $this->dgeneral->update(
                "tbl_pi_header",
                $data_header,
                array(
                    array(
                        'kolom' => 'no_pi',
                        'value' => $post['no_pi']
                    )
                )
            );
        }

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
        $return = array('sts' => $sts, 'msg' => $msg);
        echo json_encode($return);
    }
}
