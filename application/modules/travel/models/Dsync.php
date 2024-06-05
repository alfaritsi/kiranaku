<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dsync extends CI_Model
{
    public function get_karyawan($params = array())
    {
        $this->db->select("tbl_karyawan.*,tbl_departemen.nama as nama_departemen");
        $this->db->select("tbl_divisi.nama as nama_divisi");
        $this->db->select("tbl_sub_divisi.nama as nama_sub_divisi");
        $this->db->select("tbl_seksi.nama as nama_seksi");
        $this->db->select("tbl_wf_master_plant.plant_name as nama_pabrik");
        $this->db->select("right(tbl_karyawan.kostl, 4) as cost_center");
        $this->db->from('tbl_karyawan');
        $this->db->join('tbl_user', 'tbl_karyawan.id_karyawan = tbl_user.id_karyawan', 'left outer');
        $this->db->join('tbl_level', 'tbl_user.id_level= tbl_level.id_level', 'left outer');
        $this->db->join('tbl_departemen', 'tbl_departemen.id_departemen= tbl_user.id_departemen', 'left outer');
        $this->db->join('tbl_divisi', 'tbl_divisi.id_divisi= tbl_user.id_divisi', 'left outer');
        $this->db->join('tbl_sub_divisi', 'tbl_sub_divisi.id_sub_divisi= tbl_user.id_sub_divisi', 'left outer');
        $this->db->join('tbl_seksi', 'tbl_seksi.id_seksi= tbl_user.id_seksi', 'left outer');
        $this->db->join('tbl_wf_master_plant', 'tbl_wf_master_plant.plant= tbl_karyawan.gsber', 'left outer');

        if (isset($params['nik'])) {
            $this->db->where('tbl_karyawan.nik', $params['nik']);
        }

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_countries($params = array())
    {
        $this->db->select('country_code, country_name');
        $this->db->from('tbl_wf_hiring_country');

        if (isset($params['country_code']))
            $this->db->where('tbl_wf_hiring_country.country_code', $params['country_code']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->row();
    }

    function push_data_spd($id = null, $nik = null, $tanggal_awal = null, $status = null)
    {
        $string = "
            select 
                tbl_travel_header.nik PERNR,
                tbl_travel_header.id_travel_header NTRIP,
                convert(varchar, tbl_travel_detail.start_date, 112) BEGDA,
                CASE
                    WHEN tbl_travel_detail.end_date is null THEN null
                    ELSE convert(varchar, tbl_travel_detail.end_date, 112)
                END ENDDA,

                REPLACE(convert(varchar, tbl_travel_detail.start_time, 114),':','') BEGUZ,
                
                CASE 
                    WHEN tbl_travel_detail.end_time is null THEN null
                    ELSE REPLACE(convert(varchar, tbl_travel_detail.end_time, 114),':','')
                END ENDUZ,
                
                tbl_travel_detail.country LAND1,
                CASE 
                    WHEN tbl_travel_detail.tujuan = 'lain' then tbl_travel_detail.tujuan_lain 
                    ELSE (
                        SELECT personal_area_text+','+personal_subarea_text FROM tbl_travel_subarea
                        WHERE company_code = tbl_travel_detail.tujuan AND personal_subarea <> ''
                    )
                    END
                LOEND,
                tbl_travel_header.activity ACTIV,
                tbl_travel_detail.keperluan REQUE,
                'IDR' WAERS

                --tbl_travel_header.start_date tanggal_header,
                --tbl_travel_header.id_travel_header id_header

                FROM tbl_travel_detail
                INNER JOIN tbl_travel_header ON tbl_travel_detail.id_travel_header = tbl_travel_header.id_travel_header
                
                WHERE 1=1
                AND tbl_travel_header.no_trip is null 
                
                AND tbl_travel_header.na = 'n' AND tbl_travel_header.del = 'n'
        ";
        if ($id != null) {
            $string .= "AND tbl_travel_header.id_travel_header='" . $id . "'";
        }
        if ($nik != null) {
            $string .= "AND tbl_travel_header.nik='" . $nik . "'";
        }
        if ($tanggal_awal != null) {
            $string .= "AND tbl_travel_detail.start_date='" . $tanggal_awal . "'";
        }
        if ($status != null) {
            if ($status != "byfinalapprove") {
                //$string .= "AND tbl_travel_header.approval_level='".$status."'"; 
            }
        } else {
            $string .= "AND tbl_travel_header.approval_level='" . TR_LEVEL_FINISH . "' ";
        }
        $query  = $this->db->query($string);
        $result = $query->result();

        return $result;
    }

    function push_data_downpayment($id = null, $nik = null, $tanggal_awal = null, $status = null)
    {
        $string = "
            select 
                tbl_travel_header.nik pernr,
                tbl_travel_header.id_travel_header NTRIP,
                tbl_travel_downpayment.kode_expense spkzl,
                '' sptxt,
                tbl_travel_downpayment.rate betrg,
                tbl_travel_downpayment.durasi trdur,
                tbl_travel_downpayment.jumlah vorsc,
                'IDR' waers

                FROM tbl_travel_downpayment
                LEFT JOIN tbl_travel_header ON tbl_travel_downpayment.id_travel_header = tbl_travel_header.id_travel_header
                
                WHERE 1=1
                AND tbl_travel_header.no_trip is null 
                AND tbl_travel_header.na = 'n' AND tbl_travel_header.del = 'n'
                AND tbl_travel_downpayment.na = 'n' AND tbl_travel_downpayment.del = 'n'
        ";
        if ($id != null) {
            $string .= "AND tbl_travel_header.id_travel_header='" . $id . "'";
        }
        if ($nik != null) {
            $string .= "AND tbl_travel_header.nik='" . $nik . "'";
        }
        if ($tanggal_awal != null) {
            $string .= "AND tbl_travel_header.start_date='" . $tanggal_awal . "'";
        }

        $query  = $this->db->query($string);
        $result = $query->result();

        return $result;
    }

    function push_data_cancel($id = null, $nik = null, $tanggal_awal = null, $status = null)
    {
        $string = "
            select 
                tbl_travel_header.nik EMPLOYEENUMBER,
                tbl_travel_header.no_trip TRIPNUMBER
                --convert(varchar, tbl_travel_detail.start_date, 112) BEGDA,
               
                FROM tbl_travel_cancel
                INNER JOIN tbl_travel_header ON tbl_travel_cancel.id_travel_header = tbl_travel_header.id_travel_header
                
                WHERE 1=1
                AND tbl_travel_header.no_trip is not null                 
                AND tbl_travel_header.na = 'n' AND tbl_travel_header.del = 'n'
        ";
        if ($id != null) {
            $string .= "AND tbl_travel_header.id_travel_header='" . $id . "'";
        }
        if ($nik != null) {
            $string .= "AND tbl_travel_header.nik='" . $nik . "'";
        }
        if ($tanggal_awal != null) {
            $string .= "AND tbl_travel_detail.start_date='" . $tanggal_awal . "'";
        }
        if ($status != null) {
            $string .= "AND tbl_travel_cancel.approval_level='" . $status . "'";
        } else {
            $string .= "AND tbl_travel_cancel.approval_level='" . TR_LEVEL_FINISH . "' ";
        }
        $query  = $this->db->query($string);
        $result = $query->row();

        return $result;
    }

    function push_data_declare($id = null, $nik = null, $tanggal_awal = null, $status = null)
    {
        $string = "
            select 
                tbl_travel_deklarasi_header.nik PERNR,
                CAST(tbl_travel_deklarasi_header.id_travel_header as varchar(17) ) NTRIP,
                tbl_travel_deklarasi_header.no_trip REINR,
                tbl_travel_deklarasi_detail.kode_expense EXP_TYPE,
                tbl_travel_deklarasi_detail.jumlah REC_AMOUNT,
                tbl_travel_deklarasi_detail.currency REC_CURR,
                '1' REC_RATE,
                convert(varchar, tbl_travel_deklarasi_detail.tanggal, 112) REC_DATE,
                tbl_travel_deklarasi_detail.keterangan SHORTTXT,
                '' PROVIDER
                   

                FROM tbl_travel_deklarasi_detail
                INNER JOIN tbl_travel_deklarasi_header 
                    ON tbl_travel_deklarasi_detail.id_travel_deklarasi_header = tbl_travel_deklarasi_header.id_travel_deklarasi_header
											 
																										
                
                WHERE 1=1
                AND tbl_travel_deklarasi_header.no_trip is not null                 
                AND tbl_travel_deklarasi_header.na = 'n' AND tbl_travel_deklarasi_header.del = 'n'
        ";
        if ($id != null) {
            $string .= "AND tbl_travel_deklarasi_header.id_travel_header='" . $id . "'";
        }
        if ($nik != null) {
            $string .= "AND tbl_travel_deklarasi_header.nik='" . $nik . "'";
        }
        if ($tanggal_awal != null) {
            $string .= "AND tbl_travel_detail.start_date='" . $tanggal_awal . "'";
        }
        $query  = $this->db->query($string);
        $result = $query->result();

        return $result;
    }

    public function get_data_sync_oto($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $nik    = $params['nik'];
        $string = "
            SELECT DISTINCT cast(k.nik  AS int) nik, k.ho, tps.company_code, k.gsber, 
                        k.persa, k.btrtl, u.id_jabatan, tps.jns_user, tps.value_user
            FROM tbl_travel_pic_sync tps
            INNER JOIN tbl_karyawan k ON 
                CASE WHEN tps.company_code = 'KMTR'
                THEN
                  CASE WHEN k.ho = 'y' THEN 1 ELSE 0 END
                ELSE
                  CASE WHEN tps.company_code = k.gsber AND tps.personal_area = k.persa 
                            --AND tps.personal_subarea = k.btrtl
                  THEN 1 ELSE 0 END
                END = 1 
                AND k.na = 'n'
            INNER JOIN tbl_user u on u.id_karyawan = k.id_karyawan AND
                CASE WHEN tps.jns_user = 'jabatan' THEN
                  CASE WHEN u.id_jabatan = tps.value_user THEN 1 ELSE 0 END
                ELSE
                  CASE WHEN k.nik = tps.value_user THEN 1 ELSE 0 END
                END = 1
            INNER JOIN tbl_travel_header ON tbl_travel_header.nik = k.nik
            WHERE tps.nik = '" . $nik . "' AND tps.na = 'n' AND tps.del='n'
            ORDER by k.nik ASC

        ";

        $query  = $this->db->query($string);
        $result = $query->result();

        return $result;
    }

    public function get_travel_header($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_travel_header.*');

        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status', false);
        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status_label', false);
        $this->db->select('case when tbl_travel_status.color is not null
            then tbl_travel_status.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_travel_subarea.personal_area');
        $this->db->from('tbl_travel_header');
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_header.activity'
        );

        $this->db->join(
            'tbl_travel_log_status tls',
            'tls.id_travel_header = tbl_travel_header.id_travel_header 
            and tbl_travel_header.approval_level <> 99',
            'left'
        );
        $this->db->join('tbl_travel_log_status tls2', 'tls.id_travel_header = tls2.id_travel_header and tls.tgl_status < tls2.tgl_status', 'left');

        $this->db->join('tbl_travel_status', 'tbl_travel_status.status = tls.approval_status', 'left outer');
        $this->db->join('tbl_travel_status tsFinish', 'tsFinish.status = tbl_travel_header.approval_status', 'left outer');
        $this->db->join('tbl_travel_role', 'tbl_travel_role.level = tls.approval_level', 'left outer');
        $this->db->join(
            'tbl_travel_subarea',
            'tbl_travel_subarea.company_code = tbl_travel_header.tujuan and tbl_travel_subarea.personal_subarea <> \'\'',
            'left'
        );

        $this->db->where('tls2.id_travel_header is null', null, false);

        if (!$all) {
            $this->db->where('tbl_travel_header.na', 'n');
            $this->db->where('tbl_travel_header.del', 'n');
        }

        if (isset($params['nik']))
            $this->db->where("tbl_travel_header.nik IN (" . implode(',', $params['nik']) . ")");

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_header.id_travel_header', $params['id']);
        }

        if (isset($params['approval_level'])) {
            $this->db->where('tbl_travel_header.approval_level', $params['approval_level']);
        }

        if (isset($params['isno_trip'])) {
            if ($params['isno_trip'] == 'kosong') {
                $this->db->where('tbl_travel_header.no_trip IS null');
            } else if ($params['isno_trip'] == 'exist') {
                $this->db->where('tbl_travel_header.no_trip IS NOT null');
            }
        }
        $this->db->order_by('tbl_travel_header.id_travel_header', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_detail($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_travel_header.*, 
                            tbl_travel_header.nik header_nik, 
                            tbl_travel_header.start_date header_start_date, tbl_travel_header.start_time header_start_time,
                            tbl_travel_header.end_date header_end_date, tbl_travel_header.end_time header_end_time,
                            tbl_travel_header.country header_country

                        ');
        $this->db->select('tbl_travel_downpayment.*');

        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status', false);
        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status_label', false);
        $this->db->select('case when tbl_travel_status.color is not null
            then tbl_travel_status.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_travel_subarea.personal_area');
        $this->db->from('tbl_travel_header');
        $this->db->join(
            'tbl_travel_downpayment',
            'tbl_travel_downpayment.id_travel_header = tbl_travel_header.id_travel_header ',
            'left'
        );
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_header.activity'
        );

        $this->db->join(
            'tbl_travel_log_status tls',
            'tls.id_travel_header = tbl_travel_header.id_travel_header 
            and tbl_travel_header.approval_level <> 99',
            'left'
        );
        $this->db->join('tbl_travel_log_status tls2', 'tls.id_travel_header = tls2.id_travel_header and tls.tgl_status < tls2.tgl_status', 'left');

        $this->db->join('tbl_travel_status', 'tbl_travel_status.status = tls.approval_status', 'left outer');
        $this->db->join('tbl_travel_status tsFinish', 'tsFinish.status = tbl_travel_header.approval_status', 'left outer');
        $this->db->join('tbl_travel_role', 'tbl_travel_role.level = tls.approval_level', 'left outer');
        $this->db->join(
            'tbl_travel_subarea',
            'tbl_travel_subarea.company_code = tbl_travel_header.tujuan and tbl_travel_subarea.personal_subarea <> \'\'',
            'left'
        );

        $this->db->where('tls2.id_travel_header is null', null, false);

        if (!$all) {
            $this->db->where('tbl_travel_header.na', 'n');
            $this->db->where('tbl_travel_header.del', 'n');
        }

        if (isset($params['nik']))
            $this->db->where("tbl_travel_header.nik IN (" . implode(',', $params['nik']) . ")");

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_header.id_travel_header', $params['id']);
        }

        if (isset($params['approval_level'])) {
            $this->db->where('tbl_travel_header.approval_level', $params['approval_level']);
        }

        if (isset($params['isno_trip'])) {
            if ($params['isno_trip'] == 'kosong') {
                $this->db->where('tbl_travel_header.no_trip IS null');
            } else if ($params['isno_trip'] == 'exist') {
                $this->db->where('tbl_travel_header.no_trip IS NOT null');
            }
        }
        $this->db->order_by('tbl_travel_header.id_travel_header', 'desc');

        $query = $this->db->get();


        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_header_cancel($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_travel_header.*');
        $this->db->select('tbl_travel_cancel.approval_status, tbl_travel_cancel.approval_level, 
                            tbl_travel_cancel.na,tbl_travel_cancel.del');

        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status', false);
        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status_label', false);
        $this->db->select('case when tbl_travel_status.color is not null
            then tbl_travel_status.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_travel_subarea.personal_area');
        $this->db->from('tbl_travel_cancel');
        $this->db->join(
            'tbl_travel_header',
            'tbl_travel_cancel.id_travel_header = tbl_travel_header.id_travel_header'
        );
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_header.activity'
        );

        $this->db->join(
            'tbl_travel_log_status tls',
            'tls.id_travel_header = tbl_travel_header.id_travel_header 
            and tbl_travel_header.approval_level <> 99',
            'left'
        );
        $this->db->join('tbl_travel_log_status tls2', 'tls.id_travel_header = tls2.id_travel_header and tls.tgl_status < tls2.tgl_status', 'left');

        $this->db->join('tbl_travel_status', 'tbl_travel_status.status = tls.approval_status', 'left outer');
        $this->db->join('tbl_travel_status tsFinish', 'tsFinish.status = tbl_travel_header.approval_status', 'left outer');
        $this->db->join('tbl_travel_role', 'tbl_travel_role.level = tls.approval_level', 'left outer');
        $this->db->join(
            'tbl_travel_subarea',
            'tbl_travel_subarea.company_code = tbl_travel_header.tujuan and tbl_travel_subarea.personal_subarea <> \'\'',
            'left'
        );

        $this->db->where('tls2.id_travel_header is null', null, false);

        if (!$all) {
            $this->db->where('tbl_travel_header.na', 'n');
            $this->db->where('tbl_travel_header.del', 'n');
            $this->db->where('tbl_travel_cancel.na', 'n');
            $this->db->where('tbl_travel_cancel.del', 'n');
        }

        if (isset($params['nik']))
            $this->db->where("tbl_travel_header.nik IN (" . implode(',', $params['nik']) . ")");

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_header.id_travel_header', $params['id']);
        }

        if (isset($params['approval_status'])) {
            $this->db->where('tbl_travel_cancel.approval_status', $params['approval_status']);
        }

        if (isset($params['approval_level'])) {
            $this->db->where('tbl_travel_cancel.approval_level', $params['approval_level']);
        }

        if (isset($params['isno_trip'])) {
            if ($params['isno_trip'] == 'kosong') {
                $this->db->where('tbl_travel_header.no_trip IS null');
            } else if ($params['isno_trip'] == 'exist') {
                $this->db->where('tbl_travel_header.no_trip IS NOT null');
            }
        }
        $this->db->order_by('tbl_travel_header.id_travel_header', 'desc');

        $query = $this->db->get();


        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_header_declare($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_travel_header.*');
        $this->db->select('tbl_travel_deklarasi_header.approval_status, tbl_travel_deklarasi_header.approval_level, 
                            tbl_travel_deklarasi_header.na,tbl_travel_deklarasi_header.del');

        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status', false);
        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status_label', false);
        $this->db->select('case when tbl_travel_status.color is not null
            then tbl_travel_status.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_travel_subarea.personal_area');
        $this->db->from('tbl_travel_deklarasi_header');
        $this->db->join(
            'tbl_travel_header',
            'tbl_travel_deklarasi_header.id_travel_header = tbl_travel_header.id_travel_header'
        );
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_header.activity'
        );

        $this->db->join(
            'tbl_travel_log_status tls',
            'tls.id_travel_header = tbl_travel_header.id_travel_header 
            and tbl_travel_header.approval_level <> 99',
            'left'
        );
        $this->db->join('tbl_travel_log_status tls2', 'tls.id_travel_header = tls2.id_travel_header and tls.tgl_status < tls2.tgl_status', 'left');

        $this->db->join('tbl_travel_status', 'tbl_travel_status.status = tls.approval_status', 'left outer');
        $this->db->join('tbl_travel_status tsFinish', 'tsFinish.status = tbl_travel_header.approval_status', 'left outer');
        $this->db->join('tbl_travel_role', 'tbl_travel_role.level = tls.approval_level', 'left outer');
        $this->db->join(
            'tbl_travel_subarea',
            'tbl_travel_subarea.company_code = tbl_travel_header.tujuan and tbl_travel_subarea.personal_subarea <> \'\'',
            'left'
        );

        $this->db->where('tls2.id_travel_header is null', null, false);

        if (!$all) {
            $this->db->where('tbl_travel_header.na', 'n');
            $this->db->where('tbl_travel_header.del', 'n');
            $this->db->where('tbl_travel_deklarasi_header.na', 'n');
            $this->db->where('tbl_travel_deklarasi_header.del', 'n');
        }

        if (isset($params['nik']))
            $this->db->where("tbl_travel_header.nik IN (" . implode(',', $params['nik']) . ")");

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_deklarasi_header.id_travel_header', $params['id']);
        }

        if (isset($params['approval_status'])) {
            $this->db->where('tbl_travel_deklarasi_header.approval_status', $params['approval_status']);
        }

        if (isset($params['approval_level'])) {
            $this->db->where('tbl_travel_deklarasi_header.approval_level', $params['approval_level']);
        }

        if (isset($params['isno_trip'])) {
            if ($params['isno_trip'] == 'kosong') {
                $this->db->where('tbl_travel_header.no_trip IS null');
            } else if ($params['isno_trip'] == 'exist') {
                $this->db->where('tbl_travel_header.no_trip IS NOT null');
            }
        }
        $this->db->order_by('tbl_travel_header.id_travel_header', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_detail_declare($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_travel_header.*, 
                            tbl_travel_header.nik header_nik, 
                            tbl_travel_header.start_date header_start_date, tbl_travel_header.start_time header_start_time,
                            tbl_travel_header.end_date header_end_date, tbl_travel_header.end_time header_end_time,
                            tbl_travel_header.country header_country

                        ');
        $this->db->select('tbl_travel_deklarasi_detail.*');
        $this->db->select('tbl_travel_deklarasi_header.*');
        $this->db->select('tbl_karyawan.nama nama_karyawan, tbl_user.id_golongan');

        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status', false);
        $this->db->select('case when tbl_travel_role.role is not null 
            then tbl_travel_status.nama+\' \'+tbl_travel_role.role
            else tsFinish.nama end
            as status_label', false);
        $this->db->select('case when tbl_travel_status.color is not null
            then tbl_travel_status.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_travel_subarea.personal_area');
        $this->db->from('tbl_travel_deklarasi_detail');
        $this->db->join(
            'tbl_travel_deklarasi_header',
            'tbl_travel_deklarasi_detail.id_travel_deklarasi_header = tbl_travel_deklarasi_header.id_travel_deklarasi_header 
                AND tbl_travel_deklarasi_header.approval_level = 99
                AND tbl_travel_deklarasi_header.approval_status = 1',
            'INNER'
        );
        $this->db->join(
            'tbl_travel_header',
            'tbl_travel_header.id_travel_header = tbl_travel_deklarasi_header.id_travel_header ',
            'INNER'
        );
        $this->db->join(
            'tbl_karyawan',
            'tbl_karyawan.nik = tbl_travel_header.nik ',
            'INNER'
        );
        $this->db->join(
            'tbl_user',
            'tbl_user.id_karyawan = tbl_travel_header.nik ',
            'INNER'
        );
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_header.activity'
        );

        $this->db->join(
            'tbl_travel_log_status tls',
            'tls.id_travel_header = tbl_travel_header.id_travel_header 
            and tbl_travel_header.approval_level <> 99',
            'left'
        );
        $this->db->join('tbl_travel_log_status tls2', 'tls.id_travel_header = tls2.id_travel_header and tls.tgl_status < tls2.tgl_status', 'left');

        $this->db->join('tbl_travel_status', 'tbl_travel_status.status = tls.approval_status', 'left outer');
        $this->db->join('tbl_travel_status tsFinish', 'tsFinish.status = tbl_travel_header.approval_status', 'left outer');
        $this->db->join('tbl_travel_role', 'tbl_travel_role.level = tls.approval_level', 'left outer');
        $this->db->join(
            'tbl_travel_subarea',
            'tbl_travel_subarea.company_code = tbl_travel_header.tujuan and tbl_travel_subarea.personal_subarea <> \'\'',
            'left'
        );

        $this->db->where('tls2.id_travel_header is null', null, false);

        if (!$all) {
            $this->db->where('tbl_travel_header.na', 'n');
            $this->db->where('tbl_travel_header.del', 'n');
        }

        if (isset($params['nik']))
            $this->db->where("tbl_travel_header.nik IN (" . implode(',', $params['nik']) . ")");

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_header.id_travel_header', $params['id']);
        }

        if (isset($params['isno_trip'])) {
            if ($params['isno_trip'] == 'kosong') {
                $this->db->where('tbl_travel_header.no_trip IS null');
            } else if ($params['isno_trip'] == 'exist') {
                $this->db->where('tbl_travel_header.no_trip IS NOT null');
            }
        }

        $this->db->order_by('tbl_travel_header.id_travel_header', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_header_history($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;

        $this->db->select('*');
        $this->db->from('vw_travel_history_sync');

        if (isset($params['nik']))
            $this->db->where("nik IN (" . implode(',', $params['nik']) . ")");

        if (isset($params['tanggal_awal']))
            $this->db->where("start_date >= '" . $params['tanggal_awal'] . "'  ");

        if (isset($params['tanggal_akhir']))
            $this->db->where("start_date <= '" . $params['tanggal_akhir'] . "'  ");

        $this->db->order_by('tanggal_migrasi', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }
}