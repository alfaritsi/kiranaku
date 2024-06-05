<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dtransaksiassessment extends CI_Model{
	function get_data_assessment($conn = NULL, $nik = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_ass_data.*');
		$this->db->from('tbl_ass_data');						   
		if ($nik !== NULL) {
			$this->db->where('tbl_ass_data.nik', $nik);
			$this->db->where('tbl_ass_data.tanggal', date('Y-m-d'));
		}
		
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_karyawan($usernik = NULL) {
		$this->general->connectDbPortal();

		$this->db->select('tbl_user.*');
		$this->db->select('tbl_karyawan.*');
		$this->db->select('tbl_divisi.nama as nama_divisi');
		$this->db->select("CASE
								WHEN tbl_karyawan.ho = 'y' THEN 'PT. Kirana Megatara'
								ELSE tbl_wf_master_plant.plant_name
						   END as perusahaan");
		$this->db->from('tbl_user');
		$this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan
									 AND tbl_karyawan.na = \'n\' 
									 AND tbl_karyawan.del = \'n\'', 'inner');
		$this->db->join('tbl_level', 'tbl_level.id_level = tbl_user.id_level
									 AND tbl_level.na = \'n\' 
									 AND tbl_level.del = \'n\'', 'left');
		$this->db->join('tbl_divisi', 'tbl_divisi.id_divisi = tbl_user.id_divisi
									 AND tbl_divisi.na = \'n\' 
									 AND tbl_divisi.del = \'n\'', 'left');
		$this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant = tbl_karyawan.gsber
									 AND tbl_wf_master_plant.na = \'n\' 
									 AND tbl_wf_master_plant.del = \'n\'', 'left');
		if ($usernik !== NULL) {
			$this->db->where('tbl_karyawan.nik', $usernik);
		}
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$query = $this->db->get();
		if ($usernik !== NULL) {
			$result = $query->row();
		} else {
			$result = $query->result();
		}

		$this->general->closeDb();
		return $result;
	}

	function get_karyawan_nonSap($conn = NULL, $usernik = NULL) {
		
		if ($conn !== NULL)
		$this->general->connectDbPortal();

		$this->db->select("tbl_user.*, '' is_admin, tbl_wf_master_plant.plant_name plant ");
						//tbl_user.nik 'id_user', tbl_user.divisi 'nama_divisi
		$this->db->select("CASE
								WHEN tbl_user.ho = 'y' THEN 'PT. Kirana Megatara'
								ELSE tbl_wf_master_plant.plant_name
						   END as perusahaan");
		
		$this->db->from('tbl_ass_user tbl_user');
		$this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant = tbl_user.plant
									 ', 'left');
		if ($usernik !== NULL) {
			$this->db->where('tbl_user.nik', $usernik);
		}
		$this->db->where('tbl_user.na', 'n');
		$this->db->where('tbl_user.del', 'n');
		$query = $this->db->get();
		if ($usernik !== NULL) {
			$result = $query->row();
		} else {
			$result = $query->result();
		}

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
	}
	
	function get_data_pertanyaan($conn = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_ass_pertanyaan.id_pertanyaan as id');
		$this->db->select('tbl_ass_pertanyaan.*');
		$this->db->from('tbl_ass_pertanyaan');
		$this->db->where('tbl_ass_pertanyaan.na', 'n');
		$query  = $this->db->get();
		$result = $query->result();

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
		
		// //CURL
		// $return = $this->http_request("http://10.0.0.105/uat/kiranaku/spot/master/get/pol");
		// // ubah string JSON menjadi array
		// return json_decode($return, TRUE);
		
	}	
	function get_data_pertanyaan_ganda($conn = NULL) {
		if ($conn !== NULL) 
			$this->general->connectDbPortal();

		$this->db->select('tbl_ass_pertanyaan_ganda.id_pertanyaan as id');
		$this->db->select('tbl_ass_pertanyaan_ganda.*');
		$this->db->from('tbl_ass_pertanyaan_ganda');
		$this->db->where('tbl_ass_pertanyaan_ganda.na', 'n');
		$query  = $this->db->get();
		$result = $query->result(); 

		if ($conn !== NULL)
			$this->general->closeDb();
		return $result;
		
		// //CURL
		// $return = $this->http_request("http://10.0.0.105/uat/kiranaku/spot/master/get/pol");
		// // ubah string JSON menjadi array
		// return json_decode($return, TRUE);
		
	}	
	function get_data_berita_acara($conn = NULL, $nik = NULL) {
		if ($conn !== NULL)
			$this->general->connectDbPortal();

		$this->db->select('tbl_ass_berita_acara.id_berita_acara as id');
		$this->db->select('tbl_ass_berita_acara.tanggal_ba');
		$this->db->select('tbl_ass_berita_acara.gejala_ba');
		$this->db->select('tbl_ass_berita_acara.riwayat_ba');
		$this->db->select('tbl_ass_berita_acara.tindakan_ba');
		$this->db->from('tbl_ass_berita_acara');
		$this->db->where('tbl_ass_berita_acara.na', 'n');
		$this->db->where('tbl_ass_berita_acara.tanggal_ba IS NOT NULL');
		if($nik !== NULL){
			$this->db->where('tbl_ass_berita_acara.nik', $nik);
		}		
		$this->db->order_by('tbl_ass_berita_acara.tanggal_ba');
		$query  = $this->db->get();
		$result = $query->result();
 
		if ($conn !== NULL)
			$this->general->closeDb();
		return $result; 
		
		// //CURL
		// $return = $this->http_request("http://10.0.0.105/uat/kiranaku/spot/master/get/pol");
		// // ubah string JSON menjadi array
		// return json_decode($return, TRUE);
		
	}	
	
	//CURL
	function http_request($url){
		// persiapkan curl
		$ch = curl_init(); 

		// set url 
		curl_setopt($ch, CURLOPT_URL, $url);
		
		// set user agent    
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

		// return the transfer as a string 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

		// $output contains the output string 
		$output = curl_exec($ch); 

		// tutup curl 
		curl_close($ch);      

		// mengembalikan hasil curl
		return $output;
	}
	
	public function get_bak($params)
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $id = isset($params['id']) ? $params['id'] : null;
        $id_bak_status = isset($params['id_bak_status']) ? $params['id_bak_status'] : null;
        $tanggal_absen = isset($params['tanggal_absen']) ? $params['tanggal_absen'] : null;
        $tanggal_awal = isset($params['tanggal_awal']) ? $params['tanggal_awal'] : null;
        $tanggal_akhir = isset($params['tanggal_akhir']) ? $params['tanggal_akhir'] : null;
        $tipe = isset($params['tipe']) ? $params['tipe'] : null;
        $tipe_exclude = isset($params['tipe_exclude']) ? $params['tipe_exclude'] : null;
        $jenis = isset($params['jenis']) ? $params['jenis'] : null;
        $ho = isset($params['ho']) ? $params['ho'] : null;
        $manager = isset($params['manager']) ? $params['manager'] : null;
        $atasan = isset($params['atasan']) ? $params['atasan'] : null;
        $new_method = isset($params['new_method']) ? $params['new_method'] : null;
        $jadwal_absen = isset($params['jadwal_absen']) ? $params['jadwal_absen'] : null;
        $jadwal_absen_masuk = isset($params['jadwal_absen_masuk']) ? $params['jadwal_absen_masuk'] : null;
        $jadwal_absen_keluar = isset($params['jadwal_absen_keluar']) ? $params['jadwal_absen_keluar'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'tanggal_absen ASC';

        $lengkap = isset($params['lengkap']) ? $params['lengkap'] : null;

        $this->db->select('tbl_bak.*,tbl_bak_status.nama as nama_status, tbl_bak_status.warna');
        $this->db->select('tbl_karyawan.id_karyawan,tbl_karyawan.nama as nama_karyawan');
        $this->db->select('convert(varchar(10),tbl_bak.tanggal_migrasi,126) as tanggal_migrasi');
//        $this->db->select('DATUM,ENTUM,SOBEG,SOEND');
        $this->db->from('tbl_bak');
        $this->db->join('tbl_bak_status', 'tbl_bak.id_bak_status = tbl_bak_status.id_bak_status', 'left outer');
        $this->db->join('tbl_karyawan', 'tbl_bak.nik=tbl_karyawan.id_karyawan', 'left outer');
        $this->db->join('tbl_user', 'tbl_karyawan.id_karyawan=tbl_user.id_karyawan', 'left outer');
//        $this->db->join(DB_DEFAULT . '.dbo.ZDMTM0001', 'RIGHT(\'00000000\' + CAST(tbl_karyawan.nik AS VARCHAR(8)),8) = PERNR
//					AND CONVERT(CHAR(8),tanggal_absen,112) = CONVERT(CHAR(8),DATUM,112) AND CONVERT(CHAR(8),DATUM,112) >= \'20190101\'', 'left', false);
//        $this->db->join('[10.0.0.32].SAPSYNC.dbo.ZDMTM0001', 'RIGHT(\'00000000\' + CAST(tbl_karyawan.nik AS VARCHAR(8)),8) = PERNR
//					AND CONVERT(CHAR(8),tanggal_absen,112) = CONVERT(CHAR(8),DATUM,112) AND CONVERT(CHAR(8),DATUM,112) >= \'20181001\'', 'left outer', false);

//        $this->db->join('tbl_cuti', 'tbl_bak.nik = tbl_cuti.nik AND tbl_bak.tanggal_absen BETWEEN tbl_cuti.tanggal_awal AND tbl_cuti.tanggal_akhir', 'left outer', false);

        if (isset($lengkap) && !$lengkap) {
            $this->db->join('tbl_cuti', 'tbl_bak.nik = tbl_cuti.nik AND tbl_bak.tanggal_absen BETWEEN tbl_cuti.tanggal_awal AND tbl_cuti.tanggal_akhir', 'left outer', false);
        }

        if (isset($nik)) {
            if (is_array($nik))
                $this->db->where_in('tbl_bak.nik', $nik);
            else
                $this->db->where('tbl_bak.nik', $nik);
        }

        if (!$all) {
            $this->db->where('tbl_bak.na', 'n');
            $this->db->where('tbl_bak.del', 'n');
        }

        if (isset($id))
            $this->db->where('tbl_bak.id_bak', $id);

        if (isset($new_method))
            $this->db->where('tbl_bak.new_method', $new_method);

        if (isset($tanggal_absen))
            $this->db->where('tbl_bak.tanggal_absen', $tanggal_absen);

        if (isset($jadwal_absen)) {
            if (is_bool($jadwal_absen))
                $this->db->where('tbl_bak.jadwal_absen', null);
            else
                $this->db->where('tbl_bak.jadwal_absen', $jadwal_absen);
        }

        if (isset($jadwal_absen_masuk)) {
            if (is_bool($jadwal_absen_masuk))
                $this->db->where('tbl_bak.jadwal_absen_masuk', null);
            else
                $this->db->where('tbl_bak.jadwal_absen_masuk', $jadwal_absen_masuk);
        }

        if (isset($jadwal_absen_keluar)) {
            if (is_bool($jadwal_absen_keluar))
                $this->db->where('tbl_bak.jadwal_absen_keluar', null);
            else
                $this->db->where('tbl_bak.jadwal_absen_keluar', $jadwal_absen_keluar);
        }

        if (
            (isset($tanggal_awal) && !empty($tanggal_awal)) || (isset($tanggal_akhir) && !empty($tanggal_akhir))
        ) {
            if (isset($tanggal_awal))
                $this->db->where('tbl_bak.tanggal_absen >=', $tanggal_awal);
            if (isset($tanggal_akhir))
                $this->db->where('tbl_bak.tanggal_absen <=', $tanggal_akhir);
        }

        if (isset($id_bak_status)) {
            if (is_array($id_bak_status))
                $this->db->where_in('tbl_bak.id_bak_status', $id_bak_status);
            else
                $this->db->where('tbl_bak.id_bak_status', $id_bak_status);
        }

        if (isset($atasan))
			$this->db->where('CHARINDEX(\'\'\'\'+CONVERT(varchar(10), \'' . $atasan . '\')+\'\'\'\',\'\'\'\'+REPLACE(tbl_bak.atasan, RTRIM(\'.\'),\'\'\',\'\'\')+\'\'\'\') > 0', null, false);
			// $this->db->where("CHARINDEX('" . $atasan . "', tbl_bak.atasan)>0", null, false);

        if (isset($params['sap']) && $params['sap']) {
            $this->db->where('tbl_bak.login_buat != ', '');
            $this->db->where('tbl_bak.id_bak_alasan is not null ', '', false);
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('tbl_bak.absen_masuk != ', '-');
            $this->db->where('tbl_bak.absen_keluar != ', '-');
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where_in('tbl_bak.id_bak_status', array(ESS_BAK_STATUS_DISETUJUI, ESS_BAK_STATUS_DISETUJUI_OLEH_HR));
            $this->db->where('tbl_bak.id_bak_alasan', ESS_BAK_ALASAN_HAPUS_BAK);
            $this->db->group_end();
            $this->db->group_end();
        }

        if (isset($tipe))
            if (is_array($tipe))
                $this->db->where_in('tbl_bak.tipe', $tipe);
            else
                $this->db->where('tbl_bak.tipe', $tipe);

        if (isset($tipe_exclude))
            if (is_array($tipe_exclude))
                $this->db->where_not_in('tbl_bak.tipe', $tipe_exclude);
            else
                $this->db->where('tbl_bak.tipe <>', $tipe_exclude);

        if (isset($jenis))
            $this->db->where('tbl_bak.jenis', $jenis);

        if (isset($ho))
            $this->db->where("tbl_karyawan.ho", $ho);

        if (isset($manager)) {
            $this->db->group_start();
            if ($manager) {
//                $this->db->like("tbl_karyawan.posst", 'ceo');
//                $this->db->or_like("tbl_karyawan.posst", 'direktur operasional');
//                $this->db->or_like("tbl_karyawan.posst", 'manager');
                $this->db->where('tbl_user.id_level <= ', 9102);
            } else {
//                $this->db->not_like("tbl_karyawan.posst", 'ceo');
//                $this->db->not_like("tbl_karyawan.posst", 'direktur operasional');
//                $this->db->not_like("tbl_karyawan.posst", 'manager');
                $this->db->where('tbl_user.id_level > ', 9102);
            }
            $this->db->group_end();
        }

        if (isset($lengkap) && !$lengkap) {
            $this->db->where("(ISNULL(absen_masuk,'') IN ('','-') OR ISNULL(absen_keluar,'') IN ('','-')) 
                AND tipe NOT IN ('L','0120')", null, false);
            $this->db->where('tbl_cuti.id_cuti is null', null, false);
        }

        if (isset($order_by))
            $this->db->order_by($order_by);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        if (is_array($result)) {
            foreach ($result as $index => $list) {
                $result[$index] = $this->get_bak_additional_column($list, $params);;
            }
        } else if (isset($result))
            $result = $this->get_bak_additional_column($result, $params);

        return $result;
    }

    private function get_bak_additional_column($data, $params)
    {

        /** Tambah Kolom ID yang terenkripsi **/
        $data->enId = $this->generate->kirana_encrypt($data->id_bak);

        if ($data->absen_keluar != '-' && !empty($data->absen_keluar))
            $data->absen_keluar = date_format(date_create($data->absen_keluar), 'H:i');

        if ($data->absen_masuk != '-' && !empty($data->absen_masuk))
            $data->absen_masuk = date_format(date_create($data->absen_masuk), 'H:i');

        return $data;
    }

    public function get_karyawan_bak($id_karyawan = null, $ho = false)
    {
        $this->db->select('tbl_karyawan.*,tbl_user.id_golongan, tbl_user.id_level, tbl_karyawan.endact as tanggal_tetap');
        $this->db->from('tbl_karyawan');
        $this->db->join('tbl_user', 'tbl_user.id_karyawan=tbl_karyawan.id_karyawan', 'left outer');
        if (isset($id_karyawan) && !empty($id_karyawan))
            $this->db->where('tbl_karyawan.id_karyawan', $id_karyawan);
        $this->db->where('tbl_karyawan.na', 'n');
        $this->db->where('tbl_karyawan.del', 'n');
        $this->db->where('tbl_user.na', 'n');
        $this->db->where('tbl_user.del', 'n');
        if ($ho)
            $this->db->where('ho', 'y');

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    
	
}
?>