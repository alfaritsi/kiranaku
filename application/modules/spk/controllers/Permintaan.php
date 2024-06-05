<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Permintaan Dokumen - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Permintaan extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Permintaan Dokumen";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dmaster');
        $this->load->model('dpermintaan');
    }

    public function index()
    {
        $this->general->check_access();

        $this->data['title'] = "Manage Permintaan Dokumen";

        $this->general->connectDbPortal();

        $nik = $this->data['user']->id_karyawan;
        $leg_level_id = $this->data['user']->leg_level_id;
        $plant = $this->data['user']->gsber;
        $this->data['leg_level_id'] = $leg_level_id;
        $this->data['plant'] = $plant;

        if ($leg_level_id == 1)
            $list_permintaan = $this->dpermintaan->get_permintaan(
                array(
                    'list' => true
                )
            );
        else
            $list_permintaan = $this->dpermintaan->get_permintaan(
                array(
                    'list' => true,
                    'nik' => $nik
                )
            );

        foreach ($list_permintaan as $list) {

            $resis = $this->dpermintaan->get_resi(
                array(
                    'id_permintaan' => $list->id_permintaan
                )
            );

            $list->id_permintaan = $this->generate->kirana_encrypt($list->id_permintaan);

            $karyawan = $this->dmaster->get_karyawan(
                array(
                    'nik' => $list->user_input,
                    'single_row' => true
                )
            );

            $list->nama = $karyawan->nama;

            $linkView = "<li>"
                . "<a href='javascript:void(0)' class='permintaan-result' data-id_permintaan='"
                . $list->id_permintaan . "'>View Result</a>"
                . "</li>";

            $linkApprove = "";
            $linkKirim = "";
            $linkDelete = "";
            $linkTerima = "";
            $linkEdit = "";
            if (in_array($leg_level_id, array(1, 3))) {
                if ($list->id_status == 1)
                    $linkApprove = "<li>"
                        . "<a href='javascript:void(0)' class='permintaan-approve' data-id_permintaan='"
                        . $list->id_permintaan . "'>Approval</a>"
                        . "</li>";
                if ($list->id_status == 3)
                    $linkKirim = "<li>"
                        . "<a href='javascript:void(0)' class='permintaan-kirim' data-id_permintaan='"
                        . $list->id_permintaan . "'>Kirim Dokumen</a>"
                        . "</li>";
            } else {

                if ($list->id_status == 1) {
                    $linkDelete = "<li>"
                        . "<a href='javascript:void(0)' class='permintaan-delete' data-id_permintaan='"
                        . $list->id_permintaan . "'>Delete</a>"
                        . "</li>";
                    $linkEdit = "<li>"
                        . "<a href='javascript:void(0)' class='permintaan-edit' data-id_permintaan='"
                        . $list->id_permintaan . "'>Edit</a>"
                        . "</li>";
                }

                if ($list->id_status == 4)
                    $linkDelete = "<li>"
                        . "<a href='javascript:void(0)' class='permintaan-delete' data-id_permintaan='"
                        . $list->id_permintaan . "'>Delete</a>"
                        . "</li>";

                if ($list->id_status == 5)
                    $linkTerima = "<li>"
                        . "<a href='javascript:void(0)' class='permintaan-terima' data-id_permintaan='"
                        . $list->id_permintaan . "'>Terima Dokumen</a>"
                        . "</li>";
            }

            $list->links = $linkView . $linkApprove . $linkKirim . $linkEdit . $linkTerima . $linkDelete;

            foreach ($resis as $resi) {
                $resi->id_resi = $this->generate->kirana_encrypt($resi->id_resi);
                $linkEdit = "";
                if ($leg_level_id == 5)
                    $linkEdit = "<li>"
                        . "<a href='javascript:void(0)' class='permintaan-resi-edit' data-id_resi='"
                        . $resi->id_resi . "'>Edit</a>"
                        . "</li>";

                $resi->links = $linkEdit;
            }

            $list->table_resi = $this->get_table_resi($list->id_permintaan, $resis, $leg_level_id);
        }

        $this->data['list'] = $list_permintaan;

        $jenis_kategori = $this->general->generate_encrypt_json(
            $this->dpermintaan->get_kategori_dokumen(),
            array('id_kategori')
        );
        $this->data['jenis_kategori'] = $jenis_kategori;

        return $this->load->view('permintaan/manage', $this->data);
    }

    private function get_table_resi($id_permintaan = null, $resis = array(), $leg_level_id = null)
    {
        return $this->load->view('permintaan/includes/table_resi', compact('resis', 'id_permintaan', 'leg_level_id'), true);
    }

    public function get($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'data':
                $return = $this->get_permintaan($data);
                break;
            case 'resi':
                $return = $this->get_resi($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function save($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'data':
                $return = $this->save_permintaan($data);
                break;
            case 'resi':
                $return = $this->save_resi($data);
                break;
            case 'approval':
                $return = $this->save_approval($data);
                break;
            case 'kirimdokumen':
                $return = $this->save_kirim($data);
                break;
            case 'terimadokumen':
                $return = $this->save_terima($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    public function delete($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'data':
                if (isset($data['id'])) {
                    $id = $this->generate->kirana_decrypt($data['id']);

                    $this->general->connectDbPortal();
                    $this->dgeneral->begin_transaction();

                    $data_row = $this->dgeneral->basic_column('delete');

                    $this->dgeneral->update('tbl_leg_permintaan_dokumen', $data_row,
                        array(
                            array(
                                'kolom' => 'id_permintaan',
                                'value' => $id
                            )
                        )
                    );

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
                } else {
                    $sts = "NotOK";
                    $msg = "Tidak ada data yang akan di hapus.";
                }
                $return = array('sts' => $sts, 'msg' => $msg);

                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }

        echo json_encode($return);
    }

    private function get_resi($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->general->generate_encrypt_json(
                $this->dpermintaan->get_resi(array(
                    'id_resi' => $id,
                    'single_row' => true
                )),
                array('id_resi', 'id_permintaan')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function save_resi($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $data['id_permintaan'] = $this->generate->kirana_decrypt($data['id_permintaan']);

        if (isset($data['id_resi']) && !empty($data['id_resi'])) {
            $id = $this->generate->kirana_decrypt($data['id_resi']);

            unset($data['id_resi']);

            $data_row = $this->dgeneral->basic_column('update', $data);

            $result = $this->dgeneral->update('tbl_leg_resi', $data_row, array(
                array(
                    'kolom' => 'id_resi',
                    'value' => $id
                )
            ));

        } else {
            unset($data['id_resi']);

            $data_row = $this->dgeneral->basic_column('insert', $data);

            $result = $this->dgeneral->insert('tbl_leg_resi', $data_row);
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

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function get_permintaan($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->general->generate_encrypt_json(
                $this->dpermintaan->get_permintaan(array(
                    'id_permintaan' => $id,
                    'single_row' => true
                )),
                array('id_permintaan', 'id_kategori')
            );

            if ($result->id_status == 1) {
                $result->status_result = "Menunggu Approval Legal HO";
            } elseif ($result->id_status == 3) {
                $result->status_result = "Disetujui oleh Legal HO";
            } elseif ($result->id_status == 4) {
                $result->status_result = "Ditolak oleh Legal HO";
            } elseif ($result->id_status == 5) {
                $result->status_result = "Dikirim oleh Legal HO";
            } elseif ($result->id_status == 6) {
                $result->status_result = "Diterima oleh Legal Pabrik";
            } elseif ($result->id_status == 14) {
                $result->status_result = "Request pengembalian dokumen";
            } elseif ($result->id_status == 13) {
                $result->status_result = "Request pengembalian dokumen";
            } else {
                $result->status_result = "";
            }

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function save_permintaan($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $plant = $this->general->get_master_plant(array($this->data['user']->gsber), null, null, 'ERP');

        $data['plant'] = $plant[0]->plant;
        $data['id_pabrik'] = $plant[0]->id_pabrik;
        $data['id_kategori'] = $this->generate->kirana_decrypt($data['id_kategori']);

        $kategori = $this->dpermintaan->get_kategori_dokumen(
            array(
                'id_kategori' => $data['id_kategori'],
                'single_row' => true
            )
        );
        $data['jenis_kategori'] = $kategori->jenis_kategori;
        $data['user_input'] = $this->data['user']->nik;

        if (isset($data['id_permintaan']) && !empty($data['id_permintaan'])) {
            $id = $this->generate->kirana_decrypt($data['id_permintaan']);

            unset($data['id_permintaan']);

            $data_row = $this->dgeneral->basic_column('update', $data);

            $result = $this->dgeneral->update('tbl_leg_permintaan_dokumen', $data_row, array(
                array(
                    'kolom' => 'id_permintaan',
                    'value' => $id
                )
            ));

        } else {
            unset($data['id_permintaan']);

            $data['id_status'] = 1;

            $data_row = $this->dgeneral->basic_column('insert_full', $data);

            $result = $this->dgeneral->insert('tbl_leg_permintaan_dokumen', $data_row);

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
            $this->send_email_permintaan_dokumen($id);
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    public function test_email($id)
    {
        echo json_encode($this->send_email_permintaan_dokumen($id));
    }

    private function send_email_permintaan_dokumen($id)
    {
        if (isset($id)) {
            $permintaan = $this->dpermintaan->get_permintaan(array(
                'id_permintaan' => $id,
                'single_row' => true
            ));

            if (isset($permintaan)) {
                $owner = $this->dmaster->get_karyawan(
                    array(
                        'id_user' => $permintaan->login_buat,
                        'single_row' => true
                    )
                );

                $subject = 'Konfirmasi Permintaan Dokumen ' . $permintaan->nama_permintaan_dok . '.';

                $karyawans = $this->dmaster->get_karyawan(
                    array(
                        'leg_level_id' => 1
                    )
                );

                foreach ($karyawans as $karyawan) {
                    if (isset($karyawan->email)) {
                        $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $karyawan->email;
                        $emailOri = $karyawan->email;
                        $message = $this->load->view(
                            'emails/permintaan_dokumen',
                            compact('permintaan', 'owner', 'emailOri'),
                            true
                        );
                        $return = $this->general->send_email_new(
                            array(
                                'subject' => $subject,
                                'from_alias' => 'KiranaKu SPK',
                                'message' => $message,
                                'to' => $email
                            )
                        );
                        if ($return['sts'] == 'NotOK') {
                            return $return;
                            break;
                        }
                    }
                }
                return true;
            } else
                return false;
        } else
            return false;
    }

    private function save_approval($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $this->dgeneral->begin_transaction();

            $id = $this->generate->kirana_decrypt($data['id']);

            $status = 4;
            if ($data['action'] == 'approve')
                $status = 3;

            $data_row = $this->dgeneral->basic_column(
                'update',
                array('id_status' => $status)
            );

            $result = $this->dgeneral->update('tbl_leg_permintaan_dokumen', $data_row, array(
                array(
                    'kolom' => 'id_permintaan',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Permintaan berhasil di " . $data['action'] . ".";
                $sts = "OK";
            }

        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_kirim($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $this->dgeneral->begin_transaction();

            $id = $this->generate->kirana_decrypt($data['id']);

            $data_row = $this->dgeneral->basic_column(
                'update',
                array(
                    'id_status' => 5,
                    'tanggal_kirim' => date_create()->format('Y-m-d')
                )
            );

            $result = $this->dgeneral->update('tbl_leg_permintaan_dokumen', $data_row, array(
                array(
                    'kolom' => 'id_permintaan',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Permintaan dokumen berhasil di kirim.";
                $sts = "OK";
            }

        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_terima($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        if (isset($data['id']) && !empty($data['id'])) {

            $this->dgeneral->begin_transaction();

            $id = $this->generate->kirana_decrypt($data['id']);

            $data_row = $this->dgeneral->basic_column(
                'update',
                array(
                    'id_status' => 6,
                    'tanggal_terima' => date_create()->format('Y-m-d')
                )
            );

            $result = $this->dgeneral->update('tbl_leg_permintaan_dokumen', $data_row, array(
                array(
                    'kolom' => 'id_permintaan',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Permintaan dokumen berhasil di terima.";
                $sts = "OK";
            }

        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }


}