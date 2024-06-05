<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Info Kirana - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */

class Infokirana extends MX_Controller
{

    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->general->check_access();
        $this->data['module'] = "Info Kirana";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dinfokirana');
        $this->load->helper('text');
        $this->load->helper('date');
    }

    public function index()
    {
        $this->load->library('pagination');

        $tgl_awal = date('Y-m-d',strtotime('2012-01-01'));
        $tgl_akhir = date('Y-m-d');

        if(isset($_GET['awal']) && !empty($_GET['awal']))
        {
            $tgl_awal = $_GET['awal'];
            $tgl_awal = DateTime::createFromFormat('d.m.Y',$tgl_awal)->format('Y-m-d');
        }
        if(isset($_GET['akhir']) && !empty($_GET['akhir']))
        {
            $tgl_akhir = $_GET['akhir'];
            $tgl_akhir = DateTime::createFromFormat('d.m.Y',$tgl_akhir)->format('Y-m-d');
        }

        $news = $this->get_news($tgl_awal,$tgl_akhir);

        $this->data['title'] = "Info kirana";
        $this->data['news'] = $news;
        $this->data['tgl_awal'] = $tgl_awal;
        $this->data['tgl_akhir'] = $tgl_akhir;

        $this->load->view('news', $this->data);
    }

    public function detail($id=null)
    {
        $id = $this->generate->kirana_decrypt($id);

        if(isset($id))
        {

            $this->data['title'] = "Info kirana";
            $this->data['news'] = $this->get_new($id);
            $this->data['komentars'] = $this->get_komentar($id);

            $this->load->view('news-detail', $this->data);
        }else{
            redirect('infokirana');
        }
    }

    public function save($param)
    {
        $data = $_POST;
        switch ($param) {
            case 'komentar':
                $data['id_news'] = $this->generate->kirana_decrypt($data['id_news']);
                $return = $this->save_komentar($data);
                break;
            default:
                $return = array('sts' => 'NotOK', 'msg' => 'Link tidak ditemukan');
                break;
        }
        echo json_encode($return);
    }

    private function save_komentar($data = null)
    {
        $this->general->connectDbPortal();
        $this->dgeneral->begin_transaction();

        $data_row = $data;

        $id = $data_row['id_news'];

        if (!empty($id)) {

            $data_row = $this->dgeneral->basic_column('insert',$data_row);
            $data_row['tanggal'] = date("Y-m-d H:i:s");
            $data_row['jam'] = date("H:i:s");

            $data = $this->dgeneral->insert('tbl_komentar', $data_row);
            $id = $this->db->insert_id();

        }

        if ($this->dgeneral->status_transaction() === FALSE) {
            $this->dgeneral->rollback_transaction();
            $msg = "Periksa kembali data yang dimasukkan";
            $sts = "NotOK";
        } else {
            $this->dgeneral->commit_transaction();
            $msg = "Komentar berhasil ditambahkan";
            $sts = "OK";
        }
        $this->general->closeDb();
        $return = array('sts' => $sts, 'msg' => $msg);
        return $return;
    }

    private function get_new($id)
    {

        $new = $this->dinfokirana->get_new($id);
        $new->id_news = $this->generate->kirana_encrypt($new->id_news);
        if(isset($new->nama_karyawan))
            $new->id_karyawan = $this->generate->kirana_encrypt($new->id_karyawan);
        else
            $new->nama_karyawan = "Administrator";
        $new->tanggal = DateTime::createFromFormat('Y-m-d',$new->tanggal)->format('d.m.Y');
        $jam = new DateTime($new->jam);
        $new->jam = date_format($jam,'H:i:s');

        return $new;
    }

    private function get_komentar($id)
    {
        $komentars = $this->dinfokirana->get_komentar($id);
        foreach ($komentars as $index => $komentar)
        {
            $komentar->tanggal = DateTime::createFromFormat('Y-m-d',$komentar->tanggal)->format('d.m.Y');
            $jam = new DateTime($komentar->jam);
            $komentar->jam = date_format($jam,'H:i:s');
            $komentars[$index] = $komentar;
        }
        return $komentars;
    }

    private function get_news($tgl_awal = null, $tgl_akhir = null)
    {
        $news = [
            'terbaru' => [],
            'terkomentar' => []
        ];

        $news['terbaru'] = $this->dinfokirana->get_news('terbaru',$tgl_awal,$tgl_akhir);
        $news['terkomentar']= $this->dinfokirana->get_news('terkomentar',$tgl_awal,$tgl_akhir);

        foreach ($news as $section => $newSection)
        {
            foreach ($newSection as $index => $new)
            {
                $new->isi = word_limiter($new->isi,50);
                $new->id_news = $this->generate->kirana_encrypt($new->id_news);
                if(isset($new->nama_karyawan))
                    $new->id_karyawan = $this->generate->kirana_encrypt($new->id_karyawan);
                else
                    $new->nama_karyawan = "Administrator";
                $new->tanggal = DateTime::createFromFormat('Y-m-d',$new->tanggal)->format('d.m.Y');
                $jam = new DateTime($new->jam);
                $new->jam = date_format($jam,'H:i:s');
                $newSection[$index] = $new;
            }
            $news[$section] = $newSection;
        }

        return $news;
    }


}