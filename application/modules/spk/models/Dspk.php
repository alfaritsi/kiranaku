<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @application  : SPK Transaksi - Model
 * @author     : Octe Reviyanto Nugroho
 * @contributor  : 
 * 1. Lukman Hakim (7143) 28.03.2019
 * CR#1883 -> http://10.0.0.18/home/pdfviewer.php?q=crpdf/cr/CR_1883.pdf&n=CR_1883.pdf
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified> 
 * etc.  
 */
class Dspk extends CI_Model
{
    function get_data_email($conn = NULL, $plant = NULL, $id_status = NULL, $id_spk = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('tbl_karyawan.nama');
        $this->db->select('tbl_karyawan.email');
        $this->db->from('tbl_user');
        $this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
        $this->db->where("tbl_user.na='n'");

        if ($plant != NULL) {
            if ($id_status == 18) {    //drop 
                // $this->db->where("tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')");
                if ($id_spk != NULL) {
                    $this->db->where("
						tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')
						or
						tbl_user.leg_level_id in (
						SELECT 
						tbl_leg_divisi.id_divisi
						FROM tbl_leg_divisi
						LEFT OUTER JOIN tbl_leg_oto_divisi ON tbl_leg_divisi.id_divisi = tbl_leg_oto_divisi.id_divisi 
						LEFT OUTER JOIN tbl_leg_approval ON tbl_leg_approval.id_oto_div = tbl_leg_oto_divisi.id_oto_divisi 
						WHERE tbl_leg_divisi.na = 'n' AND tbl_leg_divisi.del = 'n' AND tbl_leg_approval.id_spk = $id_spk					
					)");
                }
            } else if ($id_status == 17) {    //cancel
                // $this->db->where("tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')");
                if ($id_spk != NULL) {
                    $this->db->where("
						tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')
						or
						tbl_user.leg_level_id in (
						SELECT 
						tbl_leg_divisi.id_divisi
						FROM tbl_leg_divisi
						LEFT OUTER JOIN tbl_leg_oto_divisi ON tbl_leg_divisi.id_divisi = tbl_leg_oto_divisi.id_divisi 
						LEFT OUTER JOIN tbl_leg_approval ON tbl_leg_approval.id_oto_div = tbl_leg_oto_divisi.id_oto_divisi 
						WHERE tbl_leg_divisi.na = 'n' AND tbl_leg_divisi.del = 'n' AND tbl_leg_approval.id_spk = $id_spk					
					)");
                }
            } else {
                $this->db->where("tbl_user.leg_level_id=1 or (tbl_user.leg_level_id=2 and tbl_karyawan.gsber='$plant')");
            }
        } else {
            $this->db->where("tbl_user.leg_level_id=1");
        }
        $this->db->where("tbl_karyawan.email!=''");
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }

    function get_data_spec($conn = NULL, $vendor = NULL, $id_jenis_vendor = NULL, $plant = NULL, $id_jenis_spk = NULL, $lifnr = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('vw_spk_zdmvendor.lifnr as id');
        $this->db->select('vw_spk_zdmvendor.*');
        $this->db->from('vw_spk_zdmvendor');
        $this->db->where("vw_spk_zdmvendor.ekorg like '%" . base64_decode($this->session->userdata('-gsber-')) . "%'");
        if ($vendor !== NULL) {
            $this->db->where("(vw_spk_zdmvendor.name1 like '%" . strtoupper($vendor) . "%')");
        }
        if ($id_jenis_vendor !== NULL) {
            $this->db->where("vw_spk_zdmvendor.id_jenis_vendor='$id_jenis_vendor'");
        }
        // if ($plant !== NULL) { 
        // $this->db->where("vw_spk_zdmvendor.ekorg='$plant'");
        // }
        if ($id_jenis_spk !== NULL) {
            $this->db->where("vw_spk_zdmvendor.id_jenis_spk='$id_jenis_spk'");
            $this->db->where("vw_spk_zdmvendor.status='Completed'");
        }
        if ($lifnr !== NULL) {
            $this->db->where("vw_spk_zdmvendor.lifnr='$lifnr'");
        }

        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
    function get_data_master_vendor($conn = NULL, $vendor = NULL, $id_jenis_vendor = NULL, $plant = NULL, $lifnr = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('vw_vendor_data.lifnr as id');
        $this->db->select('vw_vendor_data.nama as NAME1');
        $this->db->select('vw_vendor_data.lifnr as LIFNR');
        $this->db->select('vw_vendor_data.kota as CITY1');
        $this->db->select('vw_vendor_data.alamat as STRAS');
        $this->db->select("CONVERT(varchar,(vw_vendor_data.kualifikasi_spk)) +',' as list_id_kualifikasi");
        // $this->db->select("vw_vendor_data.kualifikasi_spk as list_id_kualifikasi");
        // $this->db->select("vw_vendor_data.list_kualifikasi_spk as list_kualifikasi");
        $this->db->select("
				CAST(
				 (SELECT CONVERT(VARCHAR(MAX), tbl_leg_kualifikasi_spk.kualifikasi_spk)+RTRIM(',')
					FROM tbl_leg_kualifikasi_spk
					WHERE 
					tbl_leg_kualifikasi_spk.id_kualifikasi_spk in(SELECT * FROM StringSplit(vw_vendor_data.kualifikasi_spk,','))
					and tbl_leg_kualifikasi_spk.na='n'
				  FOR XML PATH ('')) as VARCHAR(MAX)
				)  AS list_kualifikasi
		");

        $this->db->select('vw_vendor_data.*');
        $this->db->from('vw_vendor_data');
        $this->db->where("vw_vendor_data.list_plant like '%" . $plant . "%'");
        $this->db->where("vw_vendor_data.kualifikasi_spk is not null");
        if ($vendor !== NULL) {
            $this->db->where("(vw_vendor_data.nama like '%" . strtoupper($vendor) . "%')");
        }
        if ($id_jenis_vendor !== NULL) {
            $this->db->where("vw_vendor_data.id_jenis_vendor='$id_jenis_vendor'");
        }
        if ($lifnr !== NULL) {
            $this->db->where("vw_vendor_data.lifnr='$lifnr'");
        }

        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }
    function get_data_user($vendor = NULL)
    {
        $this->general->connectDbPortal();

        $this->db->select('vw_spk_zdmvendor.*');
        $this->db->from('vw_spk_zdmvendor');
        if ($vendor !== NULL) {
            $this->db->like('vw_spk_zdmvendor.name1', $vendor);
        }
        $this->db->order_by('vw_spk_zdmvendor.name1', 'ASC');
        $query  = $this->db->get();
        $result = $query->result();

        $this->general->closeDb();
        return $result;
    }

    function get_data_vendor($conn = NULL, $id_jenis_vendor = NULL, $id_jenis_spk = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('tbl_leg_zdmvendor_matrix.*');
        $this->db->from('tbl_leg_zdmvendor_matrix');
        if ($id_jenis_vendor !== NULL) {
            $this->db->where('tbl_leg_zdmvendor_matrix.id_jenis_vendor', $id_jenis_vendor);
        }
        if ($id_jenis_spk !== NULL) {
            $this->db->where('tbl_leg_zdmvendor_matrix.id_jenis_spk', $id_jenis_spk);
        }
        $this->db->where('tbl_leg_zdmvendor_matrix.na', 'n');
        $this->db->where('tbl_leg_zdmvendor_matrix.del', 'n');
        $this->db->order_by("tbl_leg_zdmvendor_matrix.lifnr", "asc");
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }

    public function get_spk($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $nik = isset($params['nik']) ? $params['nik'] : null;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;
        $id_status = isset($params['id_status']) ? $params['id_status'] : null;
        $id_status_not = isset($params['id_status_not']) ? $params['id_status_not'] : null;
        $id_plant = isset($params['id_plant']) ? $params['id_plant'] : null;
        $tanggal_berlaku_spk = isset($params['tanggal_berlaku_spk']) ? $params['tanggal_berlaku_spk'] : null;
        $order_by = isset($params['order_by']) ? $params['order_by'] : "tbl_leg_spk.tanggal_edit desc";

        $filter_plant = isset($params['filter_plant']) ? $params['filter_plant'] : null;
        $filter_jenis = isset($params['filter_jenis']) ? $params['filter_jenis'] : null;
        $filter_tanggal_berlaku_awal = isset($params['filter_tanggal_berlaku_awal']) ? $params['filter_tanggal_berlaku_awal'] : null;
        $filter_tanggal_berlaku_akhir = isset($params['filter_tanggal_berlaku_akhir']) ? $params['filter_tanggal_berlaku_akhir'] : null;
        $filter_tanggal_berakhir_awal = isset($params['filter_tanggal_berakhir_awal']) ? $params['filter_tanggal_berakhir_awal'] : null;
        $filter_tanggal_berakhir_akhir = isset($params['filter_tanggal_berakhir_akhir']) ? $params['filter_tanggal_berakhir_akhir'] : null;
        $filter_status = isset($params['filter_status']) ? $params['filter_status'] : null;

        $this->db->distinct();
        $this->db->select('tbl_leg_spk.*');
        $this->db->select('tbl_leg_status.status,tbl_leg_status.warna');
        $this->db->select("(select count(*) from tbl_leg_komentar where tbl_leg_komentar.id_spk=tbl_leg_spk.id_spk and tbl_leg_komentar.na='n' and CHARINDEX('" . base64_decode($this->session->userdata("-nik-")) . "', tbl_leg_komentar.user_read)=0) as jumlah_komentar");
        if (isset($id_spk)) {
            $this->db->select("(select top 1 CITY1 from vw_spk_zdmvendor where vw_spk_zdmvendor.lifnr='0000100084') as CITY1");
            $this->db->select("(select top 1 STRAS from vw_spk_zdmvendor where vw_spk_zdmvendor.lifnr='0000100084') as STRAS");
        }
        $this->db->from('tbl_leg_spk');
        $this->db->join('tbl_leg_status', 'tbl_leg_spk.id_status=tbl_leg_status.id_status');

        if (isset($nik)) {
            $this->db->join('tbl_karyawan', 'tbl_leg_spk.plant=tbl_karyawan.gsber', 'left outer');
            $this->db->where('tbl_karyawan.nik', $nik);
        }

        if (isset($id_divisi)) {
            $this->db->join('tbl_leg_oto_divisi', 'tbl_leg_oto_divisi.id_nama_spk=tbl_leg_spk.id_nama_spk', 'left outer');
            $this->db->where('tbl_leg_oto_divisi.id_divisi', $id_divisi);
        }

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_spk.na', 'n');
            $this->db->where('tbl_leg_spk.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        if (isset($id_plant))
            $this->db->where('tbl_leg_spk.id_plant', $id_plant);

        if (isset($id_status)) {
            if (is_array($id_status))
                $this->db->where_in('tbl_leg_spk.id_status', $id_status);
            else
                $this->db->where('tbl_leg_spk.id_status', $id_status);
        }

        if (isset($id_status_not)) {
            if (is_array($id_status_not))
                $this->db->where_not_in('tbl_leg_spk.id_status', $id_status_not);
            else
                $this->db->where('tbl_leg_spk.id_status <>', $id_status_not);
        }

        if (isset($tanggal_berlaku_spk)) {
            if (is_array($tanggal_berlaku_spk)) {
                $this->db->where('tbl_leg_spk.tanggal_berlaku_spk between \'' . $tanggal_berlaku_spk[0] . '\' and \'' . $tanggal_berlaku_spk[1] . '\'');
            } else
                $this->db->where('tbl_leg_spk.tanggal_berlaku_spk =', $tanggal_berlaku_spk);
        }
        //lha
        if ($filter_plant != NULL) {
            if (is_string($filter_plant)) $filter_plant = explode(",", $filter_plant);
            $this->db->where_in('tbl_leg_spk.plant', $filter_plant);
        }
        if ($filter_jenis != NULL) {
            if (is_string($filter_jenis)) $filter_jenis = explode(",", $filter_jenis);
            $this->db->where_in('tbl_leg_spk.id_jenis_spk', $filter_jenis);
        }
        if (($filter_tanggal_berlaku_awal != NULL) and ($filter_tanggal_berlaku_akhir != NULL)) {
            // $this->db->where('tbl_leg_spk.tanggal_berlaku_spk <= \'' . $filter_tanggal_berlaku . '\'');
            $this->db->where("tbl_leg_spk.tanggal_berlaku_spk between '$filter_tanggal_berlaku_awal' and '$filter_tanggal_berlaku_akhir'");
        }
        if (($filter_tanggal_berakhir_awal != NULL) and ($filter_tanggal_berakhir_akhir != NULL)) {
            // $this->db->where('tbl_leg_spk.tanggal_berlaku_spk <= \'' . $filter_tanggal_berakhir . '\'');
            $this->db->where("tbl_leg_spk.tanggal_berakhir_spk between '$filter_tanggal_berakhir_awal' and '$filter_tanggal_berakhir_akhir'");
        }
        if ($filter_status != NULL) {
            if (is_string($filter_status)) $filter_status = explode(",", $filter_status);
            $this->db->where_in('tbl_leg_spk.id_status', $filter_status);
        }

        $this->db->order_by($order_by);
        // $this->db->limit(5);
        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_spk_divisi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;
        $id_oto_div = isset($params['id_oto_div']) ? $params['id_oto_div'] : null;

        $this->db->select('tbl_leg_divisi.*');
        $this->db->select('tbl_leg_approval.approve');
        $this->db->select('tbl_leg_approval.tanggal_approve');
        $this->db->select('tbl_leg_approval.login_edit');
        $this->db->from('tbl_leg_divisi');
        $this->db->join('tbl_leg_oto_divisi', 'tbl_leg_divisi.id_divisi = tbl_leg_oto_divisi.id_divisi', 'left outer');
        $this->db->join('tbl_leg_approval', 'tbl_leg_approval.id_oto_div = tbl_leg_oto_divisi.id_oto_divisi', 'left outer');
        $this->db->join('tbl_leg_nama_spk', 'tbl_leg_nama_spk.id_nama_spk = tbl_leg_oto_divisi.id_nama_spk', 'left outer');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_divisi.na', 'n');
            $this->db->where('tbl_leg_divisi.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_approval.id_spk', $id_spk);

        if (isset($id_oto_div))
            $this->db->where('tbl_leg_approval.id_oto_div', $id_oto_div);

        if (isset($id_divisi))
            $this->db->where('tbl_leg_oto_divisi.id_divisi', $id_divisi);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_approval($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_divisi = isset($params['id_divisi']) ? $params['id_divisi'] : null;
        $id_oto_div = isset($params['id_oto_div']) ? $params['id_oto_div'] : null;
        $approve = isset($params['approve']) ? $params['approve'] : null;

        $this->db->select('tbl_leg_approval.*');
        $this->db->from('tbl_leg_approval');
        $this->db->join('tbl_leg_oto_divisi', 'tbl_leg_approval.id_oto_div = tbl_leg_oto_divisi.id_oto_divisi', 'left outer');
        $this->db->join('tbl_leg_spk', 'tbl_leg_spk.id_spk = tbl_leg_approval.id_spk', 'left outer');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_approval.na', 'n');
            $this->db->where('tbl_leg_approval.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_approval.id_spk', $id_spk);

        if (isset($id_oto_div))
            $this->db->where('tbl_leg_approval.id_oto_div', $id_oto_div);

        if (isset($id_divisi))
            $this->db->where('tbl_leg_oto_divisi.id_divisi', $id_divisi);

        if (isset($approve))
            $this->db->where('tbl_leg_approval.approve', $approve);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_oto_divisi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;

        $this->db->select('tbl_leg_oto_divisi.*');
        $this->db->from('tbl_leg_oto_divisi');
        $this->db->join('tbl_leg_nama_spk', 'tbl_leg_nama_spk.id_nama_spk = tbl_leg_oto_divisi.id_nama_spk', 'left outer');
        $this->db->join('tbl_leg_spk', 'tbl_leg_spk.id_nama_spk = tbl_leg_nama_spk.id_nama_spk', 'left outer');

        if (!$all) {
            if (!$list)
                $this->db->where('tbl_leg_oto_divisi.na', 'n');
            $this->db->where('tbl_leg_oto_divisi.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_spk_vendor($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_oto_vendor = isset($params['id_oto_vendor']) ? $params['id_oto_vendor'] : null;

        $this->db->distinct();
        $this->db->select('tbl_leg_spk.nama_spk');
        $this->db->select('tbl_leg_oto_vendor.nama_dokumen_vendor as nama_doc');
        $this->db->select('tbl_leg_oto_vendor.mandatory as mandatory_doc');
        $this->db->select('tbl_leg_oto_vendor.id_oto_vendor');
        $this->db->select('tbl_leg_oto_vendor.id_jenis_vendor');
        $this->db->select('tbl_leg_upload_vendor.id_upload_vendor');
        $this->db->select('tbl_leg_upload_vendor.files');
        $this->db->select('tbl_leg_upload_vendor.mandatory');
        $this->db->select('convert(varchar, tbl_leg_upload_vendor.tanggal_edit, 104) as tanggal_edit');
        $this->db->select('tbl_leg_spk.plant');
        $this->db->select('tbl_leg_spk.jenis_spk');
        $this->db->select('tbl_leg_spk.perihal');
        $this->db->select('tbl_leg_spk.SPPKP');
        $this->db->from('tbl_leg_spk');
        $this->db->join('tbl_leg_oto_vendor', 'tbl_leg_spk.id_jenis_vendor = tbl_leg_oto_vendor.id_jenis_vendor');
        $this->db->join(
            'tbl_leg_upload_vendor',
            'tbl_leg_upload_vendor.id_oto_vendor = tbl_leg_oto_vendor.id_oto_vendor and tbl_leg_upload_vendor.id_spk=tbl_leg_spk.id_spk',
            'left outer'
        );

        if (!$all) {
            if (!$list) {
                $this->db->where('tbl_leg_spk.na', 'n');
                $this->db->where('tbl_leg_oto_vendor.na', 'n');
            }
            $this->db->where('tbl_leg_spk.del', 'n');
            $this->db->where('tbl_leg_oto_vendor.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        if (isset($id_oto_vendor))
            $this->db->where('tbl_leg_oto_vendor.id_oto_vendor', $id_oto_vendor);

        $this->db->order_by('tbl_leg_oto_vendor.mandatory');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_spk_vendor_dokumen($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_oto_vendor = isset($params['id_oto_vendor']) ? $params['id_oto_vendor'] : null;

        $lifnr = isset($params['lifnr']) ? $params['lifnr'] : null;
        $nama_file = "tbl_vendor_master_dokumen.nama+' - " . $lifnr . "'";

        $this->db->distinct();
        $this->db->select('tbl_leg_spk.nama_spk');
        //lha
        // $this->db->select('tbl_leg_oto_vendor.nama_dokumen_vendor as nama_doc');
        $this->db->select('tbl_vendor_master_dokumen.nama as nama_doc');
        $this->db->select('tbl_leg_oto_vendor.mandatory as mandatory_doc');
        $this->db->select('tbl_leg_oto_vendor.id_oto_vendor');
        $this->db->select('tbl_leg_oto_vendor.id_jenis_vendor');
        $this->db->select('tbl_leg_upload_vendor.id_upload_vendor');
        $this->db->select('tbl_leg_upload_vendor.files');
        $this->db->select('tbl_leg_upload_vendor.mandatory');
        // $this->db->select('convert(varchar, tbl_leg_upload_vendor.tanggal_edit, 104) as tanggal_edit');
        $this->db->select('tbl_leg_spk.plant');
        $this->db->select('tbl_leg_spk.jenis_spk');
        $this->db->select('tbl_leg_spk.perihal');
        $this->db->select('tbl_leg_spk.SPPKP');
        $this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as link");
        $this->db->select("(select top 1 convert(varchar, tbl_file.tanggal_buat, 104) from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as tanggal_edit");
        $this->db->from('tbl_leg_spk');
        $this->db->join('tbl_leg_oto_vendor', 'tbl_leg_spk.id_jenis_vendor = tbl_leg_oto_vendor.id_jenis_vendor');
        $this->db->join(
            'tbl_leg_upload_vendor',
            'tbl_leg_upload_vendor.id_oto_vendor = tbl_leg_oto_vendor.id_oto_vendor and tbl_leg_upload_vendor.id_spk=tbl_leg_spk.id_spk',
            'left outer'
        );
        //lha
        $this->db->join('tbl_vendor_master_dokumen', 'tbl_vendor_master_dokumen.id_master_dokumen = tbl_leg_oto_vendor.id_master_dokumen');
        if (!$all) {
            if (!$list) {
                $this->db->where('tbl_leg_spk.na', 'n');
                $this->db->where('tbl_leg_oto_vendor.na', 'n');
            }
            $this->db->where('tbl_leg_spk.del', 'n');
            $this->db->where('tbl_leg_oto_vendor.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        if (isset($id_oto_vendor))
            $this->db->where('tbl_leg_oto_vendor.id_oto_vendor', $id_oto_vendor);

        $this->db->order_by('tbl_leg_oto_vendor.mandatory');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_spk_vendor_dokumen_kualifikasi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_jenis_spk = isset($params['id_jenis_spk']) ? $params['id_jenis_spk'] : null;
        // $id_kualifikasi = isset($params['id_kualifikasi']) ? $params['id_kualifikasi'] : null;
        $kualifikasi = isset($params['kualifikasi']) ? $params['kualifikasi'] : null;

        $lifnr = isset($params['lifnr']) ? $params['lifnr'] : null;
        $nama_file = "tbl_leg_kualifikasi_spk.kualifikasi_spk+' - " . $lifnr . "'";

        $this->db->select('tbl_leg_kualifikasi_spk.*');
        $this->db->select('tbl_leg_jenis_spk.jenis_spk');
        $this->db->select('tbl_leg_kualifikasi_spk.kualifikasi_spk as nama_doc');
        $this->db->select("'Mandatory' as mandatory_doc");
        $this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as link");
        $this->db->select("(select top 1 convert(varchar, tbl_file.tanggal_buat, 104) from tbl_file where tbl_file.nama=$nama_file order by tbl_file.id_file desc) as tanggal_edit");
        $this->db->from('tbl_leg_kualifikasi_spk');
        $this->db->join('tbl_leg_jenis_spk', 'tbl_leg_kualifikasi_spk.id_jenis_spk=tbl_leg_jenis_spk.id_jenis_spk', 'left outer');

        // if (isset($id_jenis_spk))
        // $this->db->where('tbl_leg_kualifikasi_spk.id_jenis_spk', $id_jenis_spk);
        // if (isset($id_kualifikasi))
        // $this->db->where('tbl_leg_kualifikasi_spk.id_kualifikasi_spk', $id_kualifikasi);
        if ($kualifikasi != NULL) {
            if (is_string($kualifikasi)) $kualifikasi = explode(",", $kualifikasi);
            $this->db->where_in('tbl_leg_kualifikasi_spk.id_kualifikasi_spk', $kualifikasi);
        }

        $this->db->where('tbl_leg_kualifikasi_spk.na', 'n');

        $this->db->order_by('tbl_leg_kualifikasi_spk.kualifikasi_spk');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }
    //ambil dari master vendor
    public function get_spk_vendor_dokumen_kualifikasi_vendor($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $kualifikasi = isset($params['kualifikasi']) ? $params['kualifikasi'] : null;
        $id_jenis_vendor = isset($params['id_jenis_vendor']) ? $params['id_jenis_vendor'] : null;
        $id_data = isset($params['id_data']) ? $params['id_data'] : null;

        $this->db->select('tbl_vendor_master_dokumen.id_master_dokumen');
        $this->db->select('tbl_vendor_master_dokumen.nama as nama_doc');
        $this->db->select("'Mandatory' as mandatory_doc");
        $this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.id_file=(select top 1 tbl_vendor_data_dokumen.id_file from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n'  order by tbl_vendor_data_dokumen.tanggal_buat desc) order by tbl_file.id_file desc)as link");
        $this->db->select("(select top 1 convert(varchar, tbl_file.tanggal_buat, 104) from tbl_file where tbl_file.id_file=(select top 1 tbl_vendor_data_dokumen.id_file from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n'  order by tbl_vendor_data_dokumen.tanggal_buat desc) order by tbl_file.id_file desc)as tanggal_edit");
        $this->db->from('tbl_vendor_kualifikasi_dokumen');
        $this->db->join('tbl_vendor_master_dokumen', 'tbl_vendor_master_dokumen.id_master_dokumen = tbl_vendor_kualifikasi_dokumen.id_master_dokumen', 'left outer');

        if ($kualifikasi != NULL) {
            if (is_string($kualifikasi)) $kualifikasi = explode(",", $kualifikasi);
            $this->db->where_in('tbl_vendor_kualifikasi_dokumen.id_kualifikasi_spk', $kualifikasi);
        }

        $this->db->where('tbl_vendor_kualifikasi_dokumen.na', 'n');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    function get_data_jenis_vendor_dokumen($conn = NULL, $id_oto_vendor = NULL, $active = NULL, $deleted = 'n', $id_jenis_vendor = NULL, $id_data = NULL, $id_data_temp = NULL)
    {
        if ($conn !== NULL)
            $this->general->connectDbPortal();

        $this->db->select('tbl_vendor_master_dokumen.id_master_dokumen');
        $this->db->select('tbl_vendor_master_dokumen.nama as nama_doc');
        $this->db->select("tbl_leg_oto_vendor.mandatory as mandatory_doc");
        $this->db->select("(select top 1 tbl_file.link from tbl_file where tbl_file.id_file=(select top 1 tbl_vendor_data_dokumen.id_file from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data' and tbl_vendor_data_dokumen.na='n'  order by tbl_vendor_data_dokumen.tanggal_buat desc) order by tbl_file.id_file desc)as link");
        $this->db->select("(select top 1 convert(varchar, tbl_file.tanggal_edit, 104) from tbl_file where tbl_file.id_file=(select top 1 tbl_vendor_data_dokumen.id_file from tbl_vendor_data_dokumen where tbl_vendor_data_dokumen.id_master_dokumen=tbl_vendor_master_dokumen.id_master_dokumen and tbl_vendor_data_dokumen.id_data='$id_data'  and tbl_vendor_data_dokumen.na='n' order by tbl_vendor_data_dokumen.tanggal_buat desc) order by tbl_file.id_file desc)as tanggal_edit");
        // $this->db->select('tbl_leg_oto_vendor.*');
        $this->db->from('tbl_leg_oto_vendor');
        $this->db->join('tbl_vendor_master_dokumen', 'tbl_vendor_master_dokumen.id_master_dokumen = tbl_leg_oto_vendor.id_master_dokumen', 'left outer');
        if ($id_oto_vendor !== NULL) {
            $this->db->where('tbl_leg_oto_vendor.id_oto_vendor', $id_oto_vendor);
        }
        if ($active !== NULL) {
            $this->db->where('tbl_leg_oto_vendor.na', $active);
        }
        if ($deleted !== NULL) {
            $this->db->where('tbl_leg_oto_vendor.del', $deleted);
        }
        if ($id_jenis_vendor !== NULL) {
            $this->db->where('tbl_leg_oto_vendor.id_jenis_vendor', $id_jenis_vendor);
            $this->db->where('tbl_leg_oto_vendor.na', 'n');
        }
        $this->db->where('tbl_leg_oto_vendor.id_master_dokumen is not null');
        $query  = $this->db->get();
        $result = $query->result();

        if ($conn !== NULL)
            $this->general->closeDb();
        return $result;
    }

    public function get_spk_template($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_oto_jenis = isset($params['id_oto_jenis']) ? $params['id_oto_jenis'] : null;

        // set ambil list template dari master atau dari table upload
        $tipe_join = "LEFT";
        if (isset($params['from_upload']) && $params['from_upload'] !== NULL && $params['from_upload'] == true)
            $tipe_join = "RIGHT";

        $this->db->distinct();
        $this->db->select('tbl_leg_spk.nama_spk');
        $this->db->select('tbl_leg_oto_jenis_spk.nama_dokumen as nama_doc');
        $this->db->select('tbl_leg_oto_jenis_spk.id_oto_jenis');
        $this->db->select('tbl_leg_oto_jenis_spk.id_jenis_spk');
        $this->db->select('tbl_leg_upload_template.id_upload_template');
        $this->db->select('tbl_leg_upload_template.files');
        $this->db->select('convert(varchar, tbl_leg_upload_template.tanggal_edit, 104) as tanggal_edit');
        $this->db->select('tbl_leg_spk.plant');
        $this->db->select('tbl_leg_jenis_spk.jenis_spk');
        $this->db->select('tbl_leg_spk.perihal');
        $this->db->select('tbl_leg_spk.SPPKP');
        $this->db->from('tbl_leg_spk');
        $this->db->join('tbl_leg_oto_jenis_spk', 'tbl_leg_spk.id_jenis_spk = tbl_leg_oto_jenis_spk.id_jenis_spk', 'inner');
        $this->db->join('tbl_leg_jenis_spk', 'tbl_leg_spk.id_jenis_spk = tbl_leg_jenis_spk.id_jenis_spk', 'left');
        $this->db->join(
            'tbl_leg_upload_template',
            'tbl_leg_upload_template.id_oto_jenis = tbl_leg_oto_jenis_spk.id_oto_jenis and tbl_leg_upload_template.id_spk=tbl_leg_spk.id_spk',
            $tipe_join
        );

        if (!$all) {
            if (!$list) {
                $this->db->where('tbl_leg_spk.na', 'n');
                $this->db->where('tbl_leg_oto_jenis_spk.na', 'n');
            }
            $this->db->where('tbl_leg_spk.del', 'n');
            $this->db->where('tbl_leg_oto_jenis_spk.del', 'n');
        }

        if (isset($id_spk))
            $this->db->where('tbl_leg_spk.id_spk', $id_spk);

        if (isset($id_oto_jenis))
            $this->db->where('tbl_leg_oto_jenis_spk.id_oto_jenis', $id_oto_jenis);

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_total_spk_template($params = array())
    {
        $id_jenis_spk = isset($params['id_jenis_spk']) ? $params['id_jenis_spk'] : null;

        $this->db->select('count(*) as totaldok');
        $this->db->from('tbl_leg_oto_jenis_spk');

        $this->db->where('tbl_leg_oto_jenis_spk.na', 'n');
        $this->db->where('tbl_leg_oto_jenis_spk.del', 'n');

        if (isset($id_jenis_spk))
            $this->db->where('tbl_leg_oto_jenis_spk.id_jenis_spk', $id_jenis_spk);

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_total_spk_template_uploaded($params = array())
    {
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_jenis_spk = isset($params['id_jenis_spk']) ? $params['id_jenis_spk'] : null;

        $this->db->select('count(*) as totaldokup');
        $this->db->from('tbl_leg_upload_template');

        $this->db->where('tbl_leg_upload_template.na', 'n');
        $this->db->where('tbl_leg_upload_template.del', 'n');

        if (isset($id_spk))
            $this->db->where('tbl_leg_upload_template.id_spk', $id_spk);

        if (isset($id_jenis_spk))
            $this->db->where('tbl_leg_upload_template.id_jenis_spk', $id_jenis_spk);

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_total_spk_vendor($params = array())
    {
        $id_jenis_vendor = isset($params['id_jenis_vendor']) ? $params['id_jenis_vendor'] : null;

        $this->db->select('COUNT(*) as totalven');
        $this->db->select('COUNT(tb2.id_oto_vendor) as total_ven_mandatory');
        $this->db->from('tbl_leg_oto_vendor');
        $this->db->join(
            'tbl_leg_oto_vendor tb2',
            'tbl_leg_oto_vendor.id_oto_vendor = tb2.id_oto_vendor AND tb2.mandatory = \'Mandatory\'',
            'left'
        );

        $this->db->where('tbl_leg_oto_vendor.na', 'n');
        $this->db->where('tbl_leg_oto_vendor.del', 'n');

        if (isset($id_jenis_vendor))
            $this->db->where('tbl_leg_oto_vendor.id_jenis_vendor', $id_jenis_vendor);

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_total_spk_vendor_uploaded($params = array())
    {
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;
        $id_jenis_vendor = isset($params['id_jenis_vendor']) ? $params['id_jenis_vendor'] : null;

        $this->db->select('COUNT(*) as totalven');
        $this->db->select('COUNT(ov.id_oto_vendor) as total_ven_mandatory');
        $this->db->from('tbl_leg_upload_vendor');
        $this->db->join(
            'tbl_leg_oto_vendor ov',
            'tbl_leg_upload_vendor.id_oto_vendor = ov.id_oto_vendor AND tbl_leg_upload_vendor.mandatory = \'Mandatory\'',
            'left'
        );

        $this->db->where('tbl_leg_upload_vendor.na', 'n');
        $this->db->where('tbl_leg_upload_vendor.del', 'n');

        if (isset($id_spk))
            $this->db->where('tbl_leg_upload_vendor.id_spk', $id_spk);

        if (isset($id_jenis_vendor))
            $this->db->where('tbl_leg_upload_vendor.id_jenis_vendor', $id_jenis_vendor);

        $query = $this->db->get();

        $result = $query->row();

        return $result;
    }

    public function get_komentar($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $list = isset($params['list']) ? $params['list'] : false;
        $id_spk = isset($params['id_spk']) ? $params['id_spk'] : null;

        $this->db->select('tbl_leg_komentar.*');
        $this->db->select('tbl_karyawan.gambar,tbl_karyawan.gender,tbl_karyawan.nik,tbl_karyawan.nama');
        $this->db->from('tbl_leg_komentar');
        $this->db->join('tbl_karyawan', 'tbl_leg_komentar.user_input = tbl_karyawan.nik', 'left');

        if (isset($id_spk))
            $this->db->where('tbl_leg_komentar.id_spk', $id_spk);

        $this->db->order_by('tbl_leg_komentar.id_komentar asc');

        $query = $this->db->get();

        if (isset($params['single_row']) && $params['single_row'])
            $result = $query->row();
        else
            $result = $query->result();

        return $result;
    }

    public function get_data_spk($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        //=======================================================================//

        $this->db->select("
            CASE
                WHEN
                    CONVERT(VARCHAR, vw_leg_spk.status) IN ('" . implode("','", $param['user_level']) . "')
                    AND (
                        vw_leg_spk.paralel = 0
                        OR
                        (vw_leg_spk.paralel = 1 
                            AND CHARINDEX(
                                '|'+CONVERT(VARCHAR, '" . base64_decode($this->session->userdata('-id_divisi-')) . "')+'|',
                                '|'+REPLACE(vw_leg_spk.divisi_terkait, RTRIM(','),'|')+'|'
                            ) > 0
                            AND 0 = (
                                SELECT COUNT(*) 
                                FROM tbl_leg_log_status tb_sts 
                                WHERE tb_sts.id_spk = vw_leg_spk.id_spk
                                    AND tb_sts.status = vw_leg_spk.status
                                    AND tb_sts.tgl_status > vw_leg_spk.tanggal_paralel
                                    AND tb_sts.id_divisi = " . base64_decode($this->session->userdata('-id_divisi-')) . "
                            )
                        )
                    )
                THEN 1
                ELSE 0
            END as akses,
            (SELECT TOP 1 
                CASE
                    WHEN tbl_leg_log_status.status IS NOT NULL 
                        AND CONVERT(VARCHAR, tbl_leg_log_status.status) IN ('" . implode("','", $param['user_level']) . "')
                    THEN 1
                    ELSE 0
                END
                FROM tbl_leg_log_status
                WHERE tbl_leg_log_status.id_spk = vw_leg_spk.id_spk
                ORDER BY tbl_leg_log_status.tgl_status
            ) as akses_edit");
        $this->db->select('vw_leg_spk.*');
        $this->db->select("(select count(*) from tbl_leg_komentar where tbl_leg_komentar.id_spk = vw_leg_spk.id_spk and tbl_leg_komentar.na='n' and CHARINDEX('" . base64_decode($this->session->userdata("-nik-")) . "', tbl_leg_komentar.user_read)=0) as jumlah_komentar");
        $this->db->from('vw_leg_spk');

        if (isset($param['plant']) && $param['plant'] !== NULL)
            $this->db->where('vw_leg_spk.plant', $param['plant']);
        if (isset($param['IN_plant']) && $param['IN_plant'] !== NULL)
            $this->db->where_in('vw_leg_spk.plant', $param['IN_plant']);
        if (isset($param['IN_jenis_spk']) && $param['IN_jenis_spk'] !== NULL)
            $this->db->where_in('vw_leg_spk.id_jenis_spk', $param['IN_jenis_spk']);
        if (isset($param['IN_status']) && $param['IN_status'] !== NULL)
            $this->db->where_in('vw_leg_spk.status', $param['IN_status']);
        if (isset($param['NOT_IN_status']) && $param['NOT_IN_status'] !== NULL)
            $this->db->where_not_in('vw_leg_spk.status', $param['NOT_IN_status']);
        if (isset($param['id_spk']) && $param['id_spk'] !== NULL)
            $this->db->where('vw_leg_spk.id_spk', $param['id_spk']);
        if (isset($param['id_spk']) && $param['id_spk'] !== NULL)
            $this->db->where('vw_leg_spk.id_spk', $param['id_spk']);
        if (isset($param['tanggal_perjanjian_awal']) && $param['tanggal_perjanjian_awal'] !== NULL)
            $this->db->where('vw_leg_spk.tanggal_perjanjian >=', $param['tanggal_perjanjian_awal']);
        if (isset($param['tanggal_perjanjian_akhir']) && $param['tanggal_perjanjian_akhir'] !== NULL)
            $this->db->where('vw_leg_spk.tanggal_perjanjian <=', $param['tanggal_perjanjian_akhir']);
        if (isset($param['tanggal_submit_awal']) && $param['tanggal_submit_awal'] !== NULL)
            $this->db->where('vw_leg_spk.tanggal_submit >=', $param['tanggal_submit_awal']);
        if (isset($param['tanggal_submit_akhir']) && $param['tanggal_submit_akhir'] !== NULL)
            $this->db->where('vw_leg_spk.tanggal_submit <=', $param['tanggal_submit_akhir']);

        // filter list
        $this->db->group_start();
        $this->db->where('vw_leg_spk.tanggal_submit IS NOT NULL');
        $this->db->or_where_in('vw_leg_spk.status', array('completed', 'cancelled'));
        if ($param['user_level']) {
            $this->db->or_where_in('vw_leg_spk.level_owner', implode("','", $param['user_level']));
        }
        $this->db->group_end();

        // echo json_encode($this->db->get_compiled_select());
        // exit();

        if (isset($param['return']) && $param['return'] == "datatables") {
            $main_query = $this->db->get_compiled_select();
            $this->db->reset_query();

            $this->datatables->select("id_spk,
                plant,
                jenis_spk,
                id_jenis_spk,
                id_jenis_vendor,
                nama_spk,
                perihal,
                SPPKP,
                files,
                id_kualifikasi,
                file_cancel,
                tanggal_perjanjian,
                tanggal_perjanjian_format,
                tanggal_berlaku_spk,
                tanggal_berlaku_format,
                tanggal_berakhir_spk,
                tanggal_berakhir_format,
                tanggal_buat,
                tanggal_buat_format,
                tanggal_submit,
                tanggal_submit_format,
                tanggal_approve,
                tanggal_final,
                jenis_vendor,
                nama_vendor,
                status,
                status_spk,
                jumlah_komentar,
                akses,
                akses_edit,
                paralel,
                divisi_terkait,
                nama_divisi_terkait");
            $this->datatables->from("($main_query) as vw_leg_spk");
            $result = $this->datatables->generate();
            $raw = json_decode($result, true);

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $raw['data'] = $this->general->generate_encrypt_json($raw['data'], $param['encrypt'], @$param['exclude']);

            $result = $this->general->jsonify($raw);
        } else {
            $query = $this->db->get();

            if (isset($param['id_spk']) && $param['id_spk'] !== NULL) {
                $result = $query->row();
            } else
                $result = $query->result();

            if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
                $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);
        }

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    public function get_data_role_user($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select("vw_leg_role_user.*");
        $this->db->from("vw_leg_role_user");

        if (isset($param['level']) && $param['level'] !== NULL)
            $this->db->where('vw_leg_role_user.user', $param['level']);
        if (isset($param['akses_buat']) && $param['akses_buat'] !== NULL)
            $this->db->where('vw_leg_role_user.akses_buat', $param['akses_buat']);
        if ((isset($param['nik']) && $param['nik'] !== NULL) || (isset($param['posst']) && $param['posst'] !== NULL)) {
            $this->db->group_start();
            if (isset($param['nik']) && $param['nik'] !== NULL)
                $this->db->or_where('vw_leg_role_user.user', $param['nik']);
            if (isset($param['posst']) && $param['posst'] !== NULL)
                $this->db->or_where('vw_leg_role_user.user', $param['posst']);
            $this->db->group_end();
        }

        $query = $this->db->get();

        $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], $param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_spk_log($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_leg_role.nama_role,
            tbl_karyawan.nama,
            tbl_leg_log_status.id_spk,
            CONVERT(VARCHAR, tbl_leg_log_status.tgl_status, 104) as tgl_status_format,
            CONVERT(VARCHAR, tbl_leg_log_status.tgl_status, 108) as jam_status_format,
            CONVERT(VARCHAR, tbl_leg_log_status.tgl_status, 120) as tgl_status,
            tbl_leg_log_status.action,
            tbl_leg_log_status.comment,
            tbl_divisi.nama AS nama_divisi');
        $this->db->from('tbl_leg_log_status');
        $this->db->join('tbl_leg_spk', 'tbl_leg_log_status.id_spk = tbl_leg_spk.id_spk', 'inner');
        // $this->db->join('tbl_leg_role_dtl', 'CONVERT(VARCHAR, tbl_leg_log_status.status) = CONVERT(VARCHAR, tbl_leg_role_dtl.level) 
        // AND tbl_leg_role_dtl.kategori_pi = \'project\'
        // AND tbl_leg_role_dtl.app = \'project\'', 'inner');
        $this->db->join('tbl_leg_role', 'CONVERT(VARCHAR, tbl_leg_log_status.status) = CONVERT(VARCHAR, tbl_leg_role.level)
                                        AND tbl_leg_role.na = \'n\'', 'inner');
        $this->db->join('tbl_user', 'tbl_user.id_user = tbl_leg_log_status.login_edit', 'inner');
        $this->db->join('tbl_karyawan', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'inner');
        $this->db->join('tbl_divisi', 'tbl_divisi.id_divisi = tbl_leg_log_status.id_divisi', 'left');

        if (isset($param['id_spk']) && $param['id_spk'] !== NULL)
            $this->db->where('tbl_leg_log_status.id_spk', $param['id_spk']);

        $this->db->order_by('tbl_leg_log_status.tgl_status DESC');
        $query  = $this->db->get();

        $result = $query->result();

        $kolom = array();
        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $kolom = array_merge(
                $kolom,
                array_map(function ($val) {
                    return array(
                        "tipe" => "encrypt",
                        "nama" => $val,
                    );
                }, $param['encrypt'])
            );

        $result = $this->general->generate_json(
            array(
                "data" => $result,
                "kolom" => $kolom,
                "exclude" => $this->general->emptyconvert(@$param['exclude'])
            )
        );

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_spk_last_action($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $where_jenis_spk = "";
        if (isset($param['id_jenis_spk']) && $param['id_jenis_spk'] !== NULL)
            $where_jenis_spk = ' AND tbl_leg_role_dtl.id_jenis_spk = ' . $param['id_jenis_spk'] . ' ';

        $query = $this->db->query('
            SELECT TOP 1 
                tbl_leg_log_status.action,
                tbl_leg_role.id_role,
                tbl_leg_role_dtl.level,
                tbl_leg_role.nama_role
            FROM tbl_leg_log_status
            INNER JOIN tbl_leg_spk ON tbl_leg_log_status.id_spk = tbl_leg_spk.id_spk
            INNER JOIN tbl_leg_role_dtl 
                ON CONVERT(VARCHAR, tbl_leg_log_status.status) = CONVERT(VARCHAR, tbl_leg_role_dtl.level)
                ' . $where_jenis_spk . '
            INNER JOIN tbl_leg_role ON tbl_leg_role_dtl.id_role = tbl_leg_role.id_role
            WHERE tbl_leg_log_status.id_spk = \'' . $param['id_spk'] . '\'
            ORDER BY tbl_leg_log_status.tgl_status DESC
        ');

        $result = $query->row();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_approval($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $where_jenis_spk = "";
        if (isset($param['id_jenis_spk']) && $param['id_jenis_spk'] !== NULL)
            $where_jenis_spk = ' AND tbl_leg_role_dtl.id_jenis_spk = ' . $param['id_jenis_spk'] . ' ';

        $query = $this->db->query("
            SELECT COUNT(*) AS jumlah_approval 
            FROM tbl_leg_log_status 
            LEFT JOIN tbl_leg_spk ON tbl_leg_spk.id_spk = tbl_leg_log_status.id_spk
            WHERE tbl_leg_log_status.id_spk = " . $param['id_spk'] . "
                AND tbl_leg_log_status.status = " . $param['level'] . "
                AND tbl_leg_log_status.tgl_status > tbl_leg_spk.tanggal_paralel
        ");

        $result = $query->row();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_email_recipient($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $query = $this->db->query("
            SELECT DISTINCT 
                tbl_user.id_karyawan,
                tbl_karyawan.nama,
                CASE tbl_karyawan.gender
                WHEN 'l' THEN 'Bapak'
                ELSE 'Ibu'
                END gender,
                tbl_karyawan.posst,
                tbl_karyawan.email,
                nilai = 'cc',
                vw_leg_master_role.nama_role
            FROM tbl_leg_log_status
            INNER JOIN tbl_leg_spk ON tbl_leg_spk.id_spk = tbl_leg_log_status.id_spk
            INNER JOIN tbl_user ON tbl_user.id_user = tbl_leg_log_status.login_edit
            INNER JOIN tbl_karyawan ON tbl_user.id_karyawan = tbl_karyawan.nik
            INNER JOIN vw_leg_master_role ON vw_leg_master_role.id_jenis_spk = tbl_leg_spk.id_jenis_spk
                AND vw_leg_master_role.level = tbl_leg_log_status.status
            WHERE tbl_leg_spk.id_spk = " . $param['id_spk'] . "
            AND tbl_leg_spk.status <> CAST(vw_leg_master_role.level as varchar(20))
            AND tbl_karyawan.email IS NOT NULL
            AND tbl_karyawan.email <> ''
            
            UNION
            
            SELECT DISTINCT 
                tbl_karyawan.id_karyawan,
                tbl_karyawan.nama,
                CASE tbl_karyawan.gender
                WHEN 'l' THEN 'Bapak'
                ELSE 'Ibu'
                END gender,
                tbl_karyawan.posst,
                tbl_karyawan.email,
                nilai = 'to',
                tbl_leg_role.nama_role
            FROM vw_leg_spk
            INNER JOIN vw_leg_master_role ON vw_leg_master_role.id_jenis_spk = vw_leg_spk.id_jenis_spk
                AND CAST(vw_leg_master_role.level as varchar(20)) = 
                    (CASE
                        WHEN vw_leg_spk.status = 'confirmed' THEN '1'
                        WHEN vw_leg_spk.status = 'finaldraft' THEN vw_leg_spk.level_owner
                        ELSE vw_leg_spk.status
                    END)
            RIGHT JOIN (
                SELECT tbl_leg_role.nama_role,
                    tbl_leg_role.id_role as kode,
                    tbl_leg_role.tipe_user,
                    tbl_leg_role.akses_hapus,
                    tbl_leg_role.ho,
                    CASE
                        WHEN tbl_leg_role.na = 'n' AND tbl_leg_role.del = 'n' THEN 'success'
                        WHEN tbl_leg_role.na = 'y' AND tbl_leg_role.del = 'n' THEN 'danger'
                    END as label_active,
                    CASE
                        WHEN tbl_leg_role.na = 'n' AND tbl_leg_role.del = 'n' THEN 'AKTIF'
                        WHEN tbl_leg_role.na = 'y' AND tbl_leg_role.del = 'n' THEN 'NON AKTIF'
                    END as status_active,
                    tbl_leg_role.na
                FROM tbl_leg_role
                WHERE tbl_leg_role.del = 'n'
            ) tbl_leg_role ON vw_leg_master_role.id_role = tbl_leg_role.kode
                AND vw_leg_master_role.id_jenis_spk = vw_leg_spk.id_jenis_spk
            INNER JOIN tbl_leg_user_role ON tbl_leg_role.kode = tbl_leg_user_role.id_role
                AND tbl_leg_user_role.na = 'n'
                AND tbl_leg_user_role.del = 'n'
            INNER JOIN tbl_karyawan ON tbl_karyawan.na = 'n'
                AND tbl_karyawan.del = 'n'
                AND 1 = (
                        CASE
                            WHEN tbl_leg_role.tipe_user = 'nik' 
                            AND tbl_leg_user_role.[user] = CONVERT(VARCHAR, tbl_karyawan.nik) THEN 1
                            WHEN tbl_leg_role.tipe_user = 'posisi' 
                            AND tbl_leg_user_role.[user] = tbl_karyawan.posst
                            THEN 1
                            ELSE 0
                        END
                        )
            INNER JOIN tbl_user ON tbl_user.id_karyawan = tbl_karyawan.id_karyawan
            AND 1 = (
                    CASE
                        WHEN vw_leg_master_role.paralel = 0 THEN 1
                        WHEN  vw_leg_master_role.paralel = 1 AND tbl_user.id_divisi IN (SELECT splitdata FROM dbo.fnSplitString(vw_leg_master_role.divisi_terkait, ',')) THEN 1
                        ELSE 0
                        END
                    )
            CROSS APPLY fnSplitString(tbl_leg_user_role.pabrik,',')
            WHERE vw_leg_spk.id_spk = " . $param['id_spk'] . "
                AND tbl_karyawan.email IS NOT NULL
                AND tbl_karyawan.email <> ''
                AND vw_leg_spk.plant IN (splitdata)
        ");

        $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_data_divisi_terkait($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_divisi.id_divisi,
            tbl_divisi.nama AS nama_divisi,
            tb_approval.id_spk,
            CONVERT(VARCHAR, tb_approval.tgl_status, 104) as tgl_status_format,
            CONVERT(VARCHAR, tb_approval.tgl_status, 108) as jam_status_format,
            CONVERT(VARCHAR, tb_approval.tgl_status, 120) as tgl_status,
            tb_approval.action,
            tb_approval.comment');
        $this->db->from('tbl_divisi');
        $this->db->join("(
            SELECT * 
            FROM tbl_leg_log_status
            WHERE id_spk = " . $param['id_spk'] ." AND status = '" . $param['status_spk'] . "' AND tgl_status > '" . $param['tanggal_paralel'] . "'
            ) tb_approval", 'tb_approval.id_divisi = tbl_divisi.id_divisi', 'left');

        if (isset($param['IN_divisi']) && $param['IN_divisi'] !== NULL)
            $this->db->where_in('tbl_divisi.id_divisi', $param['IN_divisi']);

        $query = $this->db->get();
        $result = $query->result();

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
}
