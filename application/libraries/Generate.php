<?php

    /*
    @application    : Kiranaku v2
    @author         : Akhmad Syaiful Yamang (8347)
    @contributor    :
                1. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                2. <insert your fullname> (<insert your nik>) <insert the date>
                   <insert what you have modified>
                etc.
    */

    defined('BASEPATH') OR exit('No direct script access allowed');

    class Generate {
        public function kirana_encrypt($data) {
            if(isset($data) && !empty($data))
            {
                $iv   = "1234123456785678";
                $data = openssl_encrypt($data, "aes256", "Kirana21", 0, $iv);
                $data = base64_encode($data);
            }
            return $data;
        }

        public function kirana_decrypt($data) {
            if(isset($data) && !empty($data))
            {
                $iv   = "1234123456785678";
                $data = base64_decode($data);
                $data = openssl_decrypt($data, "aes256", "Kirana21", 0, $iv);
            }
            return $data;
        }

        public function generateDateFormat($date) {
            return date_format(date_create($date), "d.m.Y");
        }

        public function generateDateTimeFormat($date) {
            return date_format(date_create($date), "d.m.Y H:i:s");
        }

        public function regenerateDateFormat($date) {
            return date_format(date_create($date), "Y-m-d");
        }

        public function regenerateDateTimeFormat($date) {
            return date_format(date_create($date), "Y-m-d H:i:s");
        }

        public function format_nilai($format="SAPSQL",$nilai){
            switch ($format)
            {
                case "SAPSQL" :
                    $minus = (substr($nilai, -1, 1)=='-')?'-':'';
                    $nilai = explode('.',$nilai);
                    $nilai = $nilai[0];
                    return $minus.''.$nilai;
                    break;
                case "SAP2SQLDATE" :
                    $tahun = substr($nilai, 0, 4);
                    $bulan = substr($nilai, 4, 2);
                    $tanggal = substr($nilai, 6, 2);
                    $tgl = $tahun.'-'.$bulan.'-'.$tanggal;
                    return $tgl;
                    break;
                case "SAP2SQLTIME" :
                    $jam = substr($nilai, 0, 2);
                    $menit = substr($nilai, 2, 2);
                    $detik = substr($nilai, 4, 2);
                    $data = $jam.':'.$menit.':'.$detik;
                    return $data;
                    break;
                default:
                    return $nilai;
                    break;
            }
        }

        public function add_space_after_comma($string){
        	return preg_replace('/(?<!\d),|,(?!\d{3})/', ', ', $string);
		}

        public function revert_rupiah($money)
        {
            $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
            $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

            $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

            $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
            $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '', $stringWithCommaOrDot);

            return (float)str_replace(',', '.', $removedThousendSeparator);
        }

        public function convert_rupiah($nilai, $pecahan = 0)
        {
            $rupiah = 'Rp. ' . number_format($nilai, $pecahan, ',', '.');
            return $rupiah;
        }
    }

?>
