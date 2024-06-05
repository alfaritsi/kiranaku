<?php
/**
 * @application  : Admin Staff (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */

class Adminstaff extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dadminstaff');
    }

    public function index()
    {
        $this->general->check_access();
        $data['module'] = "Info Karyawan";
        $data['title'] = "Info Karyawan";
        $data['title_form'] = "Info Karwayan";
        $data['user'] = $this->general->get_data_user();

        $post = $_POST;
        $user = $this->general->get_data_user();
        if (!empty($post['cari'])) {
            $data['cari'] = $post['cari'];
            $data['staffs'] = $this->dadminstaff->get_all_data($post['cari']);
        } else
            $data['staffs'] = $this->dadminstaff->get_all_data(
                null,
                $user->nik
            );


        $this->load->view('adminstaff', $data);
    }

    public function get_data()
    {
        $formData = $_POST;
        $id = $this->generate->kirana_decrypt($formData['id']);
        $data = $this->dadminstaff->get_data($id);

        echo json_encode(array('data' => $data, 'id' => $formData['id']));
    }

    public function set_data($method)
    {
        $result = array();
        if (isset($method)) {
            $data = $_POST;
            switch ($method) {
                case "save" :
                    $id = $this->generate->kirana_decrypt($data['id']);
                    unset($data['id']);

                    $result = $this->dadminstaff->update_data(
                        $id,
                        $data,
                        array(
                            array(
                                'kolom' => 'id_karyawan',
                                'value' => $id
                            )
                        )
                    );

                    break;
                case "import" :

                    $this->load->library('PHPExcel');
                    try {
                        $inputFileType = PHPExcel_IOFactory::identify($_FILES['excel']['tmp_name']);
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($_FILES['excel']['tmp_name']);

                        $sheet = $objPHPExcel->getSheet(0);
                        $highestRow = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();

                        $datetime = date("Y-m-d H:i:s");

                        $this->general->connectDbPortal();
                        $this->dgeneral->begin_transaction();

                        $data_row = array(
                            'login_edit' => base64_decode($this->session->userdata("-id_user-")),
                            'tanggal_edit' => $datetime
                        );


                        for ($row = 2; $row <= $highestRow; $row++) {
                            //  Read a row of data into an array
                            $id = $sheet->getCell('A' . $row)->getValue();
                            $extTelpon = $sheet->getCell('B' . $row)->getValue();
                            //  Insert row data array into your database of choice here

                            $data_row = array_merge($data_row,
                                array(
                                    'telepon' => $extTelpon
                                ));
                            $this->dgeneral->update('tbl_karyawan',
                                $data_row,
                                array(
                                    array(
                                        'kolom' => 'id_karyawan',
                                        'value' => $id
                                    )
                                )
                            );
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

                    } catch (Exception $e) {
                        $msg = "Periksa kembali data yang dimasukkan";
                        $sts = "NotOK";
                    }

                    $result = array('sts' => $sts, 'msg' => $msg);
                    break;
            }
        }

        echo json_encode($result);
    }
}