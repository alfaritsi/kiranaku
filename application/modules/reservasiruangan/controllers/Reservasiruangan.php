<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Reservasi Ruangan Meeting - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
class Reservasiruangan extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
//        $this->general->check_access();
        $this->data['module'] = "Reservasi Ruangan meeting";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dreservasiruangan');
        $this->load->helper('date');
    }

    public function index()
    {
        $this->load->library('pagination');
        $this->general->connectDbPortal();

        $tgl = date('Y-m-d', strtotime('today'));

        if (isset($_GET['tgl'])) {
            $tgl = $_GET['tgl'];
            $tgl = DateTime::createFromFormat('d.m.Y', $tgl)->format('Y-m-d');
        }

        $datas = $this->get_ruangan_detail($tgl);

        $this->data['title'] = "Reservasi Ruang Meeting";
        $this->data['tgl'] = $tgl;
        $this->data['datas'] = $datas;
        $this->data['jam'] = $this->dreservasiruangan->get_jam();

        $this->load->view('reservasi', $this->data);

        $this->general->closeDb();
    }

    public function save($param)
    {

        $data = $_POST;
        switch ($param) {
            case 'reservasi':

                $this->general->connectDbPortal();

                $return = $this->save_reservasi($data);

                $this->general->closeDb();
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
            case 'reservasi':
                $this->general->connectDbPortal();

                $return = $this->delete_reservasi($data);

                $this->general->closeDb();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    public function get($param)
    {
        switch ($param) {
            case 'reservasi':
                $this->general->connectDbPortal();
                $id = $this->generate->kirana_decrypt($_POST['id']);
                $id_ruang = $this->generate->kirana_decrypt($_POST['id_ruang']);
                $tgl = isset($_POST['tgl']) ? date('Y-m-d', strtotime($_POST['tgl'])) : date('Y-m-d');
                $jam_awal = $_POST['jam_awal'];
                $jam_akhir = $_POST['jam_akhir'];
                $return = $this->get_reservasi($id, $id_ruang, $tgl, $jam_awal, $jam_akhir);

                $this->general->closeDb();
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    private function get_ruangan_detail($tgl)
    {
        $ruangan = $this->dreservasiruangan->get_ruangan();

        $fasilitas = $this->dreservasiruangan->get_fasilitas();

        $jam_reservasi = $this->dreservasiruangan->get_jam();

        foreach ($ruangan as $index => $item) {
            $reserved = $this->dreservasiruangan->get_reservasi_ruangan($item->id_ruang, $tgl);

            $available_hours = array_map(function ($hour) use ($reserved) {
                $hourReserved = array_filter($reserved, function ($reservasi) use ($hour) {
                    $jam_awal_reservasi = DateTime::createFromFormat('H:i', $reservasi->jam_awal);
                    $jam_akhir_reservasi = DateTime::createFromFormat('H:i', $reservasi->jam_akhir);
                    $jam_awal_hour = DateTime::createFromFormat('H:i', $hour->jam_awal);
                    $jam_akhir_hour = DateTime::createFromFormat('H:i', $hour->jam_akhir);
                    if (
                        $jam_awal_hour >= $jam_awal_reservasi
                        && $jam_awal_hour < $jam_akhir_reservasi
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                });

                if (count($hourReserved) > 0) {
                    $reservasi = array_shift($hourReserved);
                    $hour->reserved = true;
                    $hour->jam_awal_reservasi = $reservasi->jam_awal;
                    $hour->jam_akhir_reservasi = $reservasi->jam_akhir;
                    $hour->reservasi = $reservasi;
                } else {
                    $hour->reserved = false;
                }

                return clone $hour;
            }, $jam_reservasi);

            $item->available_hours = $available_hours;

            $item->available_fasilitas = $this->print_fasilitas(
                $this->get_ruangan_fasilitas($item, $fasilitas)
            );

            $ruangan[$index] = $item;

        }

        return $ruangan;
    }

    private function get_ruangan_fasilitas($ruangan, $fasilitas)
    {
        $idFasilitasRuangan = explode('.', $ruangan->fasilitas);

        $fasilitasRuangan = array_map(function ($item) use ($idFasilitasRuangan) {
            $tmpItem = clone $item;
            $tmpItem->available = in_array($item->id_fasilitas, $idFasilitasRuangan);
            return $tmpItem;
        }, $fasilitas);

        return $fasilitasRuangan;
    }

    private function get_reservasi($id, $id_ruang = null, $tgl = null, $jam_awal = null, $jam_akhir = null)
    {
        $fasilitas = $this->dreservasiruangan->get_fasilitas();
        $ruangan = $this->dreservasiruangan->get_ruangan($id_ruang);
        $reservasi = null;
        if (isset($id) && !empty($id)) {
            $reservasi = $this->dreservasiruangan->get_reservasi($id);
            $reservasi->tanggal = date('d.m.Y', strtotime($reservasi->tanggal));
            if ($reservasi->gender == "l") {
                $image = base_url() . "assets/apps/img/avatar5.png";
            } else {
                $image = base_url() . "assets/apps/img/avatar2.png";
            }
            if ($reservasi->gambar) {
                $data_image = "http://kiranaku.kiranamegatara.com/home/" . strtolower($reservasi->gambar);
                $headers    = get_headers($data_image);
                if ($headers[0] == "HTTP/1.1 200 OK") {
                    $image = $data_image;
                } else {
                    $links      = explode("/", $reservasi->gambar);
                    $data_image = "http://kiranaku.kiranamegatara.com/home/" . $links[0] . "/" . $links[1] . "/" . strtoupper($links[2]);
                    $headers    = get_headers($data_image);
                    if ($headers[0] == "HTTP/1.1 200 OK") {
                        $image = $data_image;
                    }
                }

            }
            $reservasi->gambar = $image;
            $available_fasilitas = $this->print_fasilitas(
                $this->get_ruangan_fasilitas($reservasi, $fasilitas)
            );
        } else {
            $ruang = $this->dreservasiruangan->get_ruangan($id_ruang);
            $available_fasilitas = $this->print_fasilitas(
                $this->get_ruangan_fasilitas($ruang, $fasilitas)
            );
        }

        $reservasi_ruangans = $this->dreservasiruangan->get_reservasi_ruangan($id_ruang, $tgl, null, $jam_awal);

        $jam_reservasi_terdekat = "";

        foreach ($reservasi_ruangans as $reservasi_ruangan) {
            $jam_awal_check = DateTime::createFromFormat('H:i', $reservasi_ruangan->jam_awal);
            $jam_akhir_check = DateTime::createFromFormat('H:i', $reservasi_ruangan->jam_akhir);
            if (
                (
                    empty($jam_reservasi_terdekat) ||
                    $jam_awal_check < DateTime::createFromFormat('H:i', $jam_reservasi_terdekat)
                ) &&
                DateTime::createFromFormat('H:i', $jam_awal) < $jam_awal_check &&
                DateTime::createFromFormat('H:i', $jam_akhir) < $jam_akhir_check
            )
                $jam_reservasi_terdekat = $jam_awal_check->format('H:i');
        }

        $jams = $this->dreservasiruangan->get_jam_tersedia($jam_awal, $jam_reservasi_terdekat);

        $available_jam_akhir = array();

        foreach ($jams as $jam) {
            $available_jam_akhir[] = array(
                'id' => $jam->jam_akhir,
                'text' => $jam->jam_akhir
            );
        }

        return array(
            'reservasi' => $reservasi,
            'ruangan' => $ruangan,
            'available_fasilitas' => $available_fasilitas,
            'available_jam_akhir' => $available_jam_akhir
        );
    }

    protected function print_fasilitas($data)
    {
        return $this->load->view('_fasilitas', array(
            'datas' => $data
        ), true);
    }

    private function save_reservasi($data)
    {

        $data_row = $data;

        $this->dgeneral->begin_transaction();

        $jam_inserts = $this->dreservasiruangan->get_jam($data['jam_awal'], $data['jam_akhir']);

        $id_ruang = $this->generate->kirana_decrypt($data['id_ruang']);

        $this->dgeneral->delete('tbl_reservasi', array(
            array(
                'kolom' => 'id_ruang',
                'value' => $id_ruang
            ),
            array(
                'kolom' => 'tanggal',
                'value' => $data['tanggal']
            ),
            array(
                'kolom' => 'id_karyawan',
                'value' => base64_decode($this->session->userdata("-id_karyawan-"))
            ),
            array(
                'kolom' => 'jam_awal >=',
                'value' => $data['jam_awal']
            ),
            array(
                'kolom' => 'jam_akhir <=',
                'value' => $data['jam_akhir_reservasi']
            )
        ));

        unset($data_row['jam_akhir_reservasi']);

        $data_row['id_karyawan'] = base64_decode($this->session->userdata("-id_karyawan-"));

        $data_row['jumlah_kolom'] = count($jam_inserts);

        foreach ($jam_inserts as $jam_insert) {
            $data_row['id_ruang'] = $id_ruang;
            $data_row = $this->dgeneral->basic_column('insert', $data_row);
            $data_row['id_jam'] = $jam_insert->id_jam;
            $data_row['jam_awal'] = $jam_insert->jam_awal;
            $data_row['jam_akhir'] = $jam_insert->jam_akhir;

            $this->dgeneral->insert('tbl_reservasi', $data_row);
        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Reservasi ruangan berhasil";
            $sts = "OK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function delete_reservasi($data)
    {
        $data_row = $data;

        $this->dgeneral->begin_transaction();
        // delete old data

        $id_ruang = $this->generate->kirana_decrypt($data['id_ruang']);

        $this->dgeneral->delete('tbl_reservasi', array(
            array(
                'kolom' => 'id_ruang',
                'value' => $id_ruang
            ),
            array(
                'kolom' => 'tanggal',
                'value' => $data['tanggal']
            ),
            array(
                'kolom' => 'id_karyawan',
                'value' => base64_decode($this->session->userdata("-id_karyawan-"))
            ),
            array(
                'kolom' => 'jam_awal >=',
                'value' => $data['jam_awal']
            ),
            array(
                'kolom' => 'jam_akhir <=',
                'value' => $data['jam_akhir_reservasi']
            )
        ));

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Reservasi ruangan berhasil dibatalkan";
            $sts = "OK";
        }
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }
}