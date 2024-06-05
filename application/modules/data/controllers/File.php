<?php
/*
        @application  : 
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 30-Dec-19
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */

include_once APPPATH . "modules/data/controllers/BaseControllers.php";
class File extends BaseControllers
{
    public function transfer($param = NULL)
    {
        switch ($param) {
            case 'portal_to_pabrik':
                $param = new stdClass();
                $param->tanggal = '2019-10-16'; //date('Y-m-d', strtotime(date('Y-m-d') . ' -1 day'));
                $files = $this->dtransaksidata->get_transfer_file_list('open', $param);
                $destination = $this->dtransaksidata->get_file_server('open');
                $this->transfer_file($files, $destination);
                break;

            default:
                show_404();
                break;
        }
    }

    private function transfer_file($files, $destination)
    {
        $datetime = date("Y-m-d H:i:s");
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row_log = array();
        if ($files && $destination) {
            $list_files = array();
            $list_location = array();
            $files_detail = array();
            $post = array();
            foreach ($files as $key => $f) {
                $path = realpath('.') . '/' . $f->files;
                $ext = pathinfo($f->files, PATHINFO_EXTENSION);
                $filename = basename($f->files);
                $available = file_exists($path);
                if ($available) {                    
                    $filesize = filesize($path);
                    if ($f->jenis == 'tbl_file') {
                        $kolom_update = 'ukuran';
                        $kolom_key = 'id_file';
                    } else {
                        $kolom_update = 'size_files';
                        $kolom_key = 'id_materi';
                    }
                    $update_file = array(
                        $kolom_update => $filesize
                    );
                    $this->dgeneral->update(
                        $f->jenis,
                        $update_file,
                        array(
                            array(
                                'kolom' => $kolom_key,
                                'value' => $f->id_files
                            )
                        )
                    );

                    $files_detail[] = $f;
                    $list_files['file_contents[' . $key . ']'] = new CURLFile($path, $ext, $filename);
                    $list_location['path[' . $key . ']'] = str_replace($filename, "", $f->files);
                } else {
                    $data_row_log[] = array(
                        "jns_data" => $f->jenis,
                        "id_file" => $f->id_files,
                        "size" => $f->size_files,
                        "location_file" => $f->files,
                        "ip_asal" => $_SERVER['SERVER_ADDR'],
                        "status" => "Gagal",
                        "message" => "File " . $filename . " tidak ada di server " . $_SERVER['SERVER_ADDR'],
                        "login_buat" => 0,
                        "tanggal_buat" => $datetime
                    );
                }
            }
            $post = array_merge($list_files, $list_location, array('key' => base64_encode("kmgroup")));
            
            if (count($list_files) > 0) {
                foreach ($destination as $d) {
                    $starttime = date("Y-m-d H:i:s");
                    $url = 'http://' . $d->ip . '/file/accept.php';
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_NOBODY, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $connect = curl_exec($curl);
                    if ($connect) {
                        $url_upload = 'http://' . $d->ip . '/file/accept.php';
                        $ch         = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url_upload);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $result     = curl_exec($ch);
                        curl_close($ch);
                        
                        $result = json_decode($result, true);
                        if ($result) {
                            $endtime = date("Y-m-d H:i:s");

                            foreach ($result as $key => $r) {
                                $status = ($r === true ? 'Berhasil' : 'Gagal');
                                $message = ($r === true ? 'Berhasil mengirim file ' . basename($files_detail[$key]->files) . ' ke Pabrik ' . $d->WERKS . ' (' . $d->ip . ')' : $r);
                                $data_row_log[] = array(
                                    "jns_data" => $files_detail[$key]->jenis,
                                    "id_file" => $files_detail[$key]->id_files,
                                    "size" => $files_detail[$key]->size_files,
                                    "location_file" => $files_detail[$key]->files,
                                    "ip_asal" => $_SERVER['SERVER_ADDR'],
                                    "ip_tujuan" => $d->ip,
                                    "pabrik" => $d->WERKS,
                                    "status" => $status,
                                    "message" => $message,
                                    "login_buat" => 0,
                                    "tanggal_buat" => $datetime,
                                    "start" => $starttime,
                                    "end" => $endtime
                                );
                            }
                        }
                    } else {
                        $data_row_log[] = array(
                            "jns_data" => $files_detail[$key]->jenis,
                            "id_file" => $files_detail[$key]->id_files,
                            "size" => $files_detail[$key]->size_files,
                            "location_file" => $files_detail[$key]->files,
                            "ip_asal" => $_SERVER['SERVER_ADDR'],
                            "ip_tujuan" => $d->ip,
                            "pabrik" => $d->WERKS,
                            "status" => "Gagal", 
                            "message" => "Gagal connect ke server " . $_SERVER['SERVER_ADDR'],
                            "login_buat" => 0,
                            "tanggal_buat" => $datetime
                        );
                    }
                }
            }
        } else {
            $data_row_log[] = array(
                "ip_asal" => $_SERVER['SERVER_ADDR'],
                "status" => "Berhasil", 
                "message" => "Data file / server tujuan tidak ada",
                "login_buat" => 0,
                "tanggal_buat" => $datetime
            );
        }

        $this->dgeneral->insert_batch("tbl_log_transfer_file", $data_row_log);

        if ($this->dgeneral->status_transaction() === false) {
            $this->dgeneral->rollback_transaction();
        } else {
            $this->dgeneral->commit_transaction();
        }
        $this->general->closeDb();
        exit();
    }
}
