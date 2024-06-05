<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : Kirana News - Controller
 * @author     : Octe Reviyanto Nugroho
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */

class Kirananews extends MX_Controller
{
    private $data;

    public function __construct()
    {
        parent::__construct();
        $this->general->check_access();
        $this->data['module'] = "Kirana News";
        $this->data['user'] = $this->general->get_data_user();
        $this->load->model('dkirananews');
        $this->load->helper('text');
        $this->load->helper('date');
    }

    public function index()
    {
        $this->load->library('pagination');

//        $tgl_awal = date('Y-m-d',strtotime('first day of -3 months'));
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

        $this->data['title'] = "Kirana News";
        $this->data['news'] = $news;
        $this->data['tgl_awal'] = $tgl_awal;
        $this->data['tgl_akhir'] = $tgl_akhir;

        $this->load->view('news', $this->data);
    }

    private function get_news($tgl_awal = null, $tgl_akhir = null)
    {
        $news = $this->dkirananews->get_news($tgl_awal,$tgl_akhir);

        foreach ($news as $index => $kirananews)
        {
            $kirananews->tanggal = DateTime::createFromFormat('Y-m-d',$kirananews->tanggal)->format('d.m.Y');

            if(isset($kirananews->gambar))
                $kirananews->gambar = 'http://kiranaku.kiranamegatara.com/home/'.$kirananews->gambar;
            else
                $kirananews->gambar = 'http://via.placeholder.com/150x200?text=COVER';

            $kirananews->files = 'http://kiranaku.kiranamegatara.com/home/'.$kirananews->files;

            $news[$index] = $kirananews;
        }

        return $news;
    }
}