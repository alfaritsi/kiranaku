<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Pengembalian Dokumen - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Pengembalian extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->data['module'] = "Pengembalian Dokumen";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dmaster');
        $this->load->model('dpengembalian');
        $this->load->model('dpermintaan');
    }

    public function index()
    {
        $this->general->check_access();

        $this->data['title'] = "Manage Pengembalian Dokumen";

        $this->general->connectDbPortal();

        $nik = $this->data['user']->id_karyawan;
        $leg_level_id = $this->data['user']->leg_level_id;
        $plant = $this->data['user']->gsber;
        $this->data['leg_level_id'] = $leg_level_id;
        $this->data['plant'] = $plant;

        if ($leg_level_id == 1)
            $list_pengembalian = $this->dpengembalian->get_pengembalian(
                array(
                    'list' => true
                )
            );
        else
            $list_pengembalian = $this->dpengembalian->get_pengembalian(
                array(
                    'list' => true,
                    'nik' => $nik
                )
            );

        foreach ($list_pengembalian as $list) {

            $resis = $this->dpengembalian->get_resi(
                array(
                    'id_pengembalian' => $list->id_pengembalian
                )
            );

            $keterangans = $this->dpengembalian->get_keterangan(
                array(
                    'id_pengembalian' => $list->id_pengembalian
                )
            );

            $list->id_pengembalian = $this->generate->kirana_encrypt($list->id_pengembalian);

            $karyawan = $this->dmaster->get_karyawan(
                array(
                    'nik' => $list->user_input,
                    'single_row' => true
                )
            );

            $list->nama = $karyawan->nama;

            $linkView = "<li>"
                . "<a href='javascript:void(0)' class='pengembalian-result' data-id_pengembalian='"
                . $list->id_pengembalian . "'>View Result</a>"
                . "</li>";

            $linkApprove = "";
            $linkKirim = "";
            $linkDelete = "";
            $linkTerima = "";
            $linkEdit = "";
            if (in_array($leg_level_id, array(2))) {
                if ($list->id_status == 1)
                    $linkApprove = "<li>"
                        . "<a href='javascript:void(0)' class='pengembalian-approve' data-id_pengembalian='"
                        . $list->id_pengembalian . "'>Approval</a>"
                        . "</li>";
                if ($list->id_status == 3)
                    $linkKirim = "<li>"
                        . "<a href='javascript:void(0)' class='pengembalian-kirim' data-id_pengembalian='"
                        . $list->id_pengembalian . "'>Kirim Dokumen</a>"
                        . "</li>";
            } else {

                if ($list->id_status == 1) {
                    $linkDelete = "<li>"
                        . "<a href='javascript:void(0)' class='pengembalian-delete' data-id_pengembalian='"
                        . $list->id_pengembalian . "'>Delete</a>"
                        . "</li>";
                    $linkEdit = "<li>"
                        . "<a href='javascript:void(0)' class='pengembalian-edit' data-id_pengembalian='"
                        . $list->id_pengembalian . "'>Edit</a>"
                        . "</li>";
                }

                if ($list->id_status == 5)
                    $linkTerima = "<li>"
                        . "<a href='javascript:void(0)' class='pengembalian-terima' data-id_pengembalian='"
                        . $list->id_pengembalian . "'>Terima Dokumen</a>"
                        . "</li>";
            }

            $list->links = $linkView . $linkApprove . $linkKirim . $linkEdit . $linkTerima . $linkDelete;

            foreach ($resis as $resi) {
                $resi->id_resi = $this->generate->kirana_encrypt($resi->id_resi);
                $linkEdit = "";
                if ($leg_level_id == 5)
                    $linkEdit = "<li>"
                        . "<a href='javascript:void(0)' class='pengembalian-resi-edit' data-id_resi='"
                        . $resi->id_resi . "'>Edit</a>"
                        . "</li>";

                $resi->links = $linkEdit;
            }

            foreach ($keterangans as $keterangan) {
                $keterangan->id_keterangan = $this->generate->kirana_encrypt($keterangan->id_keterangan);
                $linkEdit = "";
                if ($leg_level_id == 2)
                    $linkEdit = "<li>"
                        . "<a href='javascript:void(0)' class='pengembalian-keterangan-edit' data-id_keterangan='"
                        . $keterangan->id_keterangan . "'>Edit</a>"
                        . "</li>";

                $keterangan->links = $linkEdit;
            }

            $list->table_resi = $this->get_table_resi($list->id_pengembalian, $resis, $leg_level_id);
            $list->table_keterangan = $this->get_table_keterangan($list->id_pengembalian, $keterangans, $leg_level_id);
        }

        $this->data['list'] = $list_pengembalian;

        $this->data['jenis_kategori'] = $this->general->generate_encrypt_json(
            $this->dpengembalian->get_kategori_dokumen(),
            array('id_kategori')
        );

        $this->data['plants'] = $this->dgeneral->get_master_plant(null, null, null, 'ERP');

        return $this->load->view('pengembalian/manage', $this->data);
    }

    private function get_table_resi($id_pengembalian = null, $resis = array(), $leg_level_id = null)
    {
        return $this->load->view('pengembalian/includes/table_resi', compact('resis', 'id_pengembalian', 'leg_level_id'), true);
    }

    private function get_table_keterangan($id_pengembalian = null, $keterangans = array(), $leg_level_id = null)
    {
        return $this->load->view('pengembalian/includes/table_keterangan', compact('keterangans', 'id_pengembalian', 'leg_level_id'), true);
    }

    public function get($param)
    {
        $data = $_POST;

        switch ($param) {
            case 'data':
                $return = $this->get_pengembalian($data);
                break;
            case 'resi':
                $return = $this->get_resi($data);
                break;
            case 'nomordokumen':
                $return = $this->get_nomor_dokumen($data);
                break;
            case 'namadokumen':
                $return = $this->get_nama_dokumen($data);
                break;
            case 'keterangan':
                $return = $this->get_keterangan($data);
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
                $return = $this->save_pengembalian($data);
                break;
            case 'resi':
                $return = $this->save_resi($data);
                break;
            case 'keterangan':
                $return = $this->save_keterangan($data);
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

                    $this->dgeneral->update('tbl_leg_pengembalian_dokumen', $data_row,
                        array(
                            array(
                                'kolom' => 'id_pengembalian',
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
                $this->dpengembalian->get_resi(array(
                    'id_resi' => $id,
                    'single_row' => true
                )),
                array('id_resi', 'id_pengembalian')
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

        $data['id_pengembalian'] = $this->generate->kirana_decrypt($data['id_pengembalian']);

        if (isset($data['id_resi']) && !empty($data['id_resi'])) {
            $id = $this->generate->kirana_decrypt($data['id_resi']);

            unset($data['id_resi']);

            $data_row = $this->dgeneral->basic_column('update', $data);

            $result = $this->dgeneral->update('tbl_leg_resi_return', $data_row, array(
                array(
                    'kolom' => 'id_resi',
                    'value' => $id
                )
            ));

        } else {
            unset($data['id_resi']);

            $data_row = $this->dgeneral->basic_column('insert', $data);

            $result = $this->dgeneral->insert('tbl_leg_resi_return', $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data resi berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function save_keterangan($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $data['id_pengembalian'] = $this->generate->kirana_decrypt($data['id_pengembalian']);

        if (isset($data['id_keterangan']) && !empty($data['id_keterangan'])) {
            $id = $this->generate->kirana_decrypt($data['id_keterangan']);

            unset($data['id_keterangan']);

            $data_row = $this->dgeneral->basic_column('update', $data);

            $result = $this->dgeneral->update('tbl_leg_keterangan', $data_row, array(
                array(
                    'kolom' => 'id_keterangan',
                    'value' => $id
                )
            ));

        } else {
            unset($data['id_keterangan']);

            $data_row = $this->dgeneral->basic_column('insert', $data);

            $result = $this->dgeneral->insert('tbl_leg_keterangan', $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Data keterangan berhasil ditambahkan";
            $sts = "OK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function get_pengembalian($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->general->generate_encrypt_json(
                $this->dpengembalian->get_pengembalian(array(
                    'id_pengembalian' => $id,
                    'single_row' => true
                )),
                array('id_pengembalian', 'id_kategori', 'id_permintaan')
            );

            $keterangan = $this->dpengembalian->get_keterangan(
                array(
                    'id_pengembalian' => $id
                )
            );

            $result->keterangan = $keterangan;

            if ($result->id_status == 1) {
                $result->status_result = "Menunggu Approval Legal Pabrik";
            } elseif ($result->id_status == 3) {
                $result->status_result = "Disetujui oleh Legal Pabrik";
            } elseif ($result->id_status == 4) {
                $result->status_result = "Ditolak oleh Legal Pabrik";
            } elseif ($result->id_status == 5) {
                $result->status_result = "Dikirim oleh Legal Pabrik";
            } elseif ($result->id_status == 6) {
                $result->status_result = "Diterima oleh Legal HO";
            } else {
                $result->status_result = "";
            }

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }

    private function save_pengembalian($data)
    {
        $this->general->connectDbPortal();

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $this->dgeneral->begin_transaction();

        $data['id_permintaan'] = $this->generate->kirana_decrypt($data['id_permintaan']);

        $permintaan = $this->dpermintaan->get_permintaan(
            array(
                'id_permintaan' => $data['id_permintaan'],
                'single_row' => true
            )
        );

        if (isset($permintaan)) {

            $plant = $this->general->get_master_plant(array($data['plant']), null, null, 'ERP');

            $data['id_pabrik'] = $plant[0]->id_pabrik;
            $data['id_kategori'] = $this->generate->kirana_decrypt($data['id_kategori']);

            $kategori = $this->dpengembalian->get_kategori_dokumen(
                array(
                    'id_kategori' => $data['id_kategori'],
                    'single_row' => true
                )
            );

            $data['nama_pengembalian_dok'] = $permintaan->nama_permintaan_dok;
            $data['nomor_dokumen'] = $permintaan->nomor_dokumen;
            $data['jenis_kategori'] = $kategori->jenis_kategori;
            $data['user_input'] = $this->data['user']->nik;

            if (isset($data['id_pengembalian']) && !empty($data['id_pengembalian'])) {
                $id = $this->generate->kirana_decrypt($data['id_pengembalian']);

                unset($data['id_pengembalian']);

                $data_row = $this->dgeneral->basic_column('update', $data);

                $result = $this->dgeneral->update('tbl_leg_pengembalian_dokumen', $data_row, array(
                    array(
                        'kolom' => 'id_pengembalian',
                        'value' => $id
                    )
                ));

            } else {
                unset($data['id_pengembalian']);

                $data['id_status'] = 1;

                $data_row = $this->dgeneral->basic_column('insert_full', $data);

                $result = $this->dgeneral->insert('tbl_leg_pengembalian_dokumen', $data_row);

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
                $this->send_email_pengembalian_dokumen($id);
            }
        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }


        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    public function test_email($id)
    {
        echo json_encode($this->send_email_pengembalian_dokumen($id));
    }

    private function send_email_pengembalian_dokumen($id)
    {
        if (isset($id)) {
            $pengembalian = $this->dpengembalian->get_pengembalian(array(
                'id_pengembalian' => $id,
                'single_row' => true
            ));

            if (isset($pengembalian)) {
                $owner = $this->dmaster->get_karyawan(
                    array(
                        'id_user' => $pengembalian->login_buat,
                        'single_row' => true
                    )
                );

                $subject = 'Konfirmasi Pengembalian Dokumen ' . $pengembalian->nama_pengembalian_dok . '.';

                $owner_permintaan = $this->dpengembalian->get_permintaan(
                    array(
                        'id_permintaan' => $pengembalian->id_permintaan,
                        'single_row' => true
                    )
                );
                $email = SPK_EMAIL_DEBUG_MODE ? json_decode(SPK_EMAIL_TESTER) : $owner_permintaan->email;
                $emailOri = $owner_permintaan->email;

                $message = $this->load->view('emails/pengembalian_dokumen', compact('pengembalian', 'owner','email','emailOri'), true);

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

            $result = $this->dgeneral->update('tbl_leg_pengembalian_dokumen', $data_row, array(
                array(
                    'kolom' => 'id_pengembalian',
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

            $result = $this->dgeneral->update('tbl_leg_pengembalian_dokumen', $data_row, array(
                array(
                    'kolom' => 'id_pengembalian',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Pengembalian dokumen berhasil di kirim.";
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

            $result = $this->dgeneral->update('tbl_leg_pengembalian_dokumen', $data_row, array(
                array(
                    'kolom' => 'id_pengembalian',
                    'value' => $id
                )
            ));

            if ($this->dgeneral->status_transaction() === FALSE) {
                $this->dgeneral->rollback_transaction();
                $msg = "Periksa kembali data yang dimasukkan";
                $sts = "NotOK";
            } else {
                $this->dgeneral->commit_transaction();
                $msg = "Pengembalian dokumen berhasil di terima.";
                $sts = "OK";
            }

        } else {
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        }

        $return = array('sts' => $sts, 'msg' => $msg);

        return $return;
    }

    private function get_nama_dokumen($data)
    {
        if (isset($data['plant']) && isset($data['id_kategori'])) {
            $id_kategori = $this->generate->kirana_decrypt($data['id_kategori']);
            $id_permintaan = $this->generate->kirana_decrypt($data['id_permintaan']);

            $this->general->connectDbPortal();

            $result = $this->general->generate_encrypt_json(
                $this->dpengembalian->get_dokumen_pengembalian(array(
                    'plant' => $data['plant'],
                    'id_kategori' => $id_kategori,
                    'id_permintaan' => $id_permintaan
                )),
                array('id_permintaan')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }

    }

    private function get_nomor_dokumen($data)
    {
        if (isset($data['plant']) && isset($data['id_kategori'])) {
            $id_kategori = $this->generate->kirana_decrypt($data['id_kategori']);

            $this->general->connectDbPortal();

            $result = $this->general->generate_encrypt_json(
                $this->dpengembalian->get_dokumen_pengembalian(array(
                    'plant' => $data['plant'],
                    'id_kategori' => $id_kategori
                )),
                array('id_permintaan')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }

    }

    private function get_keterangan($data)
    {
        if (isset($data['id'])) {
            $id = $this->generate->kirana_decrypt($data['id']);

            $this->general->connectDbPortal();

            $result = $this->general->generate_encrypt_json(
                $this->dpengembalian->get_keterangan(array(
                    'id_keterangan' => $id,
                    'single_row' => true
                )),
                array('id_pengembalian', 'id_keterangan')
            );

            return array('sts' => 'OK', 'data' => $result);
        } else {
            return array('sts' => 'NotOK', 'msg' => 'ID tidak ditemukan');
        }
    }


}