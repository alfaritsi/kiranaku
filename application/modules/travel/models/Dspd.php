<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dspd extends CI_Model
{
    public function get_role($params = array())
    {
        $this->db->from('tbl_travel_role');

        if (isset($params['level']) && !empty($params['level']))
            $this->db->where('level', $params['level']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_status($params = array())
    {
        $this->db->from('tbl_travel_status');
        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

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
            return $query->result();
    }

    //add ayy
    public function get_trans_book($params = array())
    {
        $this->db->select('vw_travel_transport_book.*');
        $this->db->from('vw_travel_transport_book');

        if (isset($params['id_travel_header']))
            $this->db->where('vw_travel_transport_book.id_travel_header', $params['id_travel_header']);

        if (isset($params['status_tiket_primary']))
            $this->db->where('vw_travel_transport_book.status_tiket_primary', $params['status_tiket_primary']);

        if (isset($params['id_travel_transport']))
            $this->db->where('vw_travel_transport_book.id_travel_transport', $params['id_travel_transport']);

        if (isset($params['id_travel_detail']))
            $this->db->where('vw_travel_transport_book.id_travel_detail', $params['id_travel_detail']);
        if (isset($params['start_date']))
            $this->db->where('vw_travel_transport_book.tanggal', $params['start_date']);
        if (isset($params['start_time']))
            $this->db->where('vw_travel_transport_book.jam', $params['start_time']);
        if (isset($params['jenis_kendaraan']))
            $this->db->where('vw_travel_transport_book.jenis_kendaraan', $params['jenis_kendaraan']);
        if (isset($params['trans_kembali']))
            $this->db->where('vw_travel_transport_book.transport_kembali', $params['trans_kembali']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_hotel_book($params = array())
    {
        $this->db->select('tbl_travel_hotel.*,
            CONVERT(VARCHAR, tbl_travel_hotel.start_date, 104) as start_date_format,
            CONVERT(VARCHAR, tbl_travel_hotel.end_date, 104) as end_date_format');
        $this->db->from('tbl_travel_hotel');

        if (isset($params['id_travel_header']))
            $this->db->where('id_travel_header', $params['id_travel_header']);

        if (isset($params['status_tiket_primary']))
            $this->db->where('status_tiket_primary', $params['status_tiket_primary']);

        if (isset($params['id_travel_hotel']))
            $this->db->where('id_travel_hotel', $params['id_travel_hotel']);

        if (isset($params['id_travel_detail']))
            $this->db->where('id_travel_detail', $params['id_travel_detail']);
        if (isset($params['start_date']))
            $this->db->where('start_date', $params['start_date']);
        if (isset($params['jenis_kendaraan']))
            $this->db->where('jenis_kendaraan', $params['jenis_kendaraan']);
        if (isset($params['trans_kembali']))
            $this->db->where('transport_kembali', $params['trans_kembali']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_booking_transport($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['finish'] = isset($params['finish']) ? $params['finish'] : false;
        if ($params['idheader'] != "" && $params['type'] != "") {
            $idheader = $params['idheader'];
            $query = $this->db->query("EXEC SP_Kiranaku_Travel_Transport '" . $idheader . "', '" . $params['type'] . "'");
        }

        if (isset($params['single']) && $params['single']) {
            $return =  $query->row();
        } else {
            $return = $query->result();
        }

        $array_dettrans = [];
        foreach ($return as $value) {
            if ($params['type'] == "trans") {
                $trans_kembali = $value->tiket_keperluan == "berangkat" ? 0 : 1;
                $check_status_trans = $this->dspd->get_trans_book(
                    array(
                        'id_travel_detail'  => $value->id_travel_detail,
                        'jenis_kendaraan'   => $value->tiket_trans_jenis,
                        'trans_kembali'     => $trans_kembali,
                        'single'            => true
                    )
                );

                if (count($check_status_trans) > 0) {
                    $stpsn      = isset($check_status_trans->no_tiket) && $check_status_trans->status_tiket == "Issued" ? "Issued" : $check_status_trans->status_tiket;
                    $tkpsn      = isset($check_status_trans->no_tiket) && $check_status_trans->no_tiket != null ? $check_status_trans->no_tiket : "";
                    $id_trans   = isset($check_status_trans->id_travel_transport) && $check_status_trans->id_travel_transport != null ? $check_status_trans->id_travel_transport : "";
                    $lampiran   = isset($check_status_trans->lampiran) ? $check_status_trans->lampiran : "";
                } else {
                    $stpsn      = "Belum dipesankan";
                    $tkpsn      = "";
                    $id_trans   = "";
                    $lampiran   = "";
                }
                $kotaasal_exp   = $value->asal_kota != "" ? explode("-", $value->asal_kota) : "";
                $kotatujuan_exp = $value->tujuan_kota != "" ? explode("-", $value->tujuan_kota) : "";
                $kotaasal       = $kotaasal_exp != "" ? $kotaasal_exp[1] : "";
                $kotatujuan     = $kotatujuan_exp != "" ? $kotatujuan_exp[1] : "";
                $dt = array(
                    'id_travel_detail'      => $this->generate->kirana_encrypt($value->id_travel_detail),
                    'id_travel_transport'   => $this->generate->kirana_encrypt($id_trans),
                    'tiket_keperluan'       => $value->tiket_keperluan,
                    'tiket_trans_jenis'     => $value->tiket_trans_jenis,
                    'nama_transportasi'     => $value->nama_transportasi,
                    'start_date'            => $value->start_date,
                    'start_time'            => $value->start_time,

                    'status_pesan'          => $stpsn,
                    'tiket_pesan'           => $tkpsn,
                    'tujuan'                => $value->asal . ", " . $kotaasal . " ke " . $value->tujuan . ", " . $kotatujuan,
                    'lampiran'              => $lampiran,
                    'tipe'                  => $value->tipe_transportasi
                );
            } else if ($params['type'] == 'hotel') {
                $check_status_hotel = $this->dspd->get_hotel_book(
                    array(
                        'id_travel_detail'  => $value->id_travel_detail,
                        'start_date'        => $value->start_date,
                        'single'            => true
                    )
                );

                if (count($check_status_hotel) > 0) {
                    $stpsn      = isset($check_status_hotel->nama_hotel) && $check_status_hotel->nama_hotel != null ? "Sudah dipesankan" : "Belum dipesankan";
                    $tkpsn      = isset($check_status_hotel->nama_hotel) && $check_status_hotel->nama_hotel != null ? $check_status_hotel->nama_hotel : "";
                    $id_trans   = isset($check_status_hotel->id_travel_hotel) && $check_status_hotel->id_travel_hotel != null ? $check_status_hotel->id_travel_hotel : "";
                    $lampiran   = isset($check_status_hotel->lampiran) ? $check_status_hotel->lampiran : "";
                } else {
                    $stpsn      = "Belum dipesankan";
                    $tkpsn      = "";
                    $id_trans   = "";
                    $lampiran   = "";
                }
                $kotaasal_exp   = $value->asal_kota != "" ? explode("-", $value->asal_kota) : "";
                $kotatujuan_exp = $value->tujuan_kota != "" ? explode("-", $value->tujuan_kota) : "";
                $kotaasal       = $kotaasal_exp != "" ? $kotaasal_exp[1] : "";
                $kotatujuan     = $kotatujuan_exp != "" ? $kotatujuan_exp[1] : "";
                $dt = array(
                    'id_travel_detail'      => $this->generate->kirana_encrypt($value->id_travel_detail),
                    'id_travel_hotel'       => $this->generate->kirana_encrypt($id_trans),
                    'tiket_keperluan'       => 'menginap',
                    'tiket_trans_jenis'     => 'hotel',
                    'start_date'            => $value->start_date,
                    'start_date_format'     => $value->start_date_format,
                    'start_time'            => "",
                    'end_date'              => $value->end_date,
                    'end_date_format'       => $value->end_date_format,
                    'end_time'              => "",

                    'status_pesan'          => $stpsn,
                    'tiket_pesan'           => $tkpsn,
                    'tujuan'                => $value->asal . ", " . $kotaasal . " ke " . $value->tujuan . ", " . $kotatujuan,
                    'lampiran'              => $lampiran,
                );
            } else {
                $dt = [];
            }


            $array_dettrans[] = $dt;
        }

        return $array_dettrans;
    }

    public function get_jenis_aktifitas($params = array())
    {
        $this->db->select('*');
        $this->db->from('tbl_travel_jenisaktifitas');
        $this->db->where('kode_jns_aktifitas <> \'\'', null, false);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_costcenter($params = array())
    {
        $this->db->from('tbl_travel_costcenter');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_costcenter_expenses($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;

        $this->db->from('vw_travel_master_expenses_um master_expenses_um');

        if (isset($params['id']) && !empty($params['id'])) {
            $this->db->where('id_travel_costcenter_expense', $params['id']);
        }
        if (isset($params['id_not']) && !empty($params['id_not'])) {
            $this->db->where('id_travel_costcenter_expense <>', $params['id_not']);
        }
        if (isset($params['domestik'])) {
            $this->db->where('domestik', $params['domestik']);
        }
        if (isset($params['kode_expense'])) {
            if (is_array($params['kode_expense'])) {
                $this->db->group_start();
                foreach ($params['kode_expense'] as $kode) {
                    $this->db->or_like('kode_expense', '.' . $kode . '.');
                }
                $this->db->group_end();
            } else {
                $this->db->like('kode_expense', '.' . $params['kode_expense'] . '.');
            }
        }
        if (isset($params['activity_type'])) {
            if (is_array($params['activity_type'])) {
                $this->db->group_start();
                foreach ($params['activity_type'] as $kode) {
                    $this->db->or_like('activity_type', '.' . $kode . '.');
                }
                $this->db->group_end();
            } else {
                $this->db->like('activity_type', '.' . $params['activity_type'] . '.');
            }
        }

        if (!$all) {
            $this->db->where('master_expenses_um.na', 'n');
            $this->db->where('master_expenses_um.del', 'n');
        }

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_costcenter_declare($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;

        $this->db->from('vw_travel_master_expenses_declare master_expenses_declare');

        if (isset($params['id']) && !empty($params['id'])) {
            $this->db->where('id_travel_costcenter_declare', $params['id']);
        }
        if (isset($params['id_not']) && !empty($params['id_not'])) {
            $this->db->where('id_travel_costcenter_declare <>', $params['id_not']);
        }
        if (isset($params['domestik'])) {
            $this->db->where('domestik', $params['domestik']);
        }
        if (isset($params['kode_expense'])) {
            if (is_array($params['kode_expense'])) {
                $this->db->group_start();
                foreach ($params['kode_expense'] as $kode) {
                    $this->db->or_like('kode_expense', '.' . $kode . '.');
                }
                $this->db->group_end();
            } else {
                $this->db->like('kode_expense', '.' . $params['kode_expense'] . '.');
            }
        }
        if (isset($params['activity_type'])) {
            if (is_array($params['activity_type'])) {
                $this->db->group_start();
                foreach ($params['activity_type'] as $kode) {
                    $this->db->or_like('activity_type', '.' . $kode . '.');
                }
                $this->db->group_end();
            } else {
                $this->db->like('activity_type', '.' . $params['activity_type'] . '.');
            }
        }

        if (!$all) {
            $this->db->where('master_expenses_declare.na', 'n');
            $this->db->where('master_expenses_declare.del', 'n');
        }

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_tipeexpenses_um($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;

        $this->db->distinct('');
        $this->db->select('*');
        $this->db->from('vw_travel_expenses_um');

        if (isset($params['persa']) && !empty($params['persa'])) {
            $this->db->where('persa', $params['persa']);
        }
        if (isset($params['kode_jns_aktifitas']) && !empty($params['kode_jns_aktifitas'])) {
            $this->db->where('kode_jns_aktifitas', $params['kode_jns_aktifitas']);
        }
        if (isset($params['costcenter']) && !empty($params['costcenter'])) {
            $this->db->where('costcenter', $params['costcenter']);
        }
        if (isset($params['golongan']) && !empty($params['golongan'])) {
            $this->db->group_start();
            $this->db->where('jabatan', $params['golongan']);
            $this->db->or_where('jabatan', '');
            $this->db->group_end();
        }
        if (isset($params['domestik'])) {
            $this->db->where('domestik', $params['domestik']);
        }
        if (isset($params['kode_expense'])) {
            $this->db->where('kode_expense', $params['kode_expense']);
        }

        if (!$all) {
            $this->db->where('na', 'n');
            $this->db->where('del', 'n');
        }
        $this->db->where("value > 0 ");

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_tipeexpenses_declare($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;

        $this->db->distinct('');
        $this->db->select('*');
        $this->db->from('vw_travel_expenses_um');

        if (isset($params['persa']) && !empty($params['persa'])) {
            $this->db->where('persa', $params['persa']);
        }
        if (isset($params['kode_jns_aktifitas']) && !empty($params['kode_jns_aktifitas'])) {
            $this->db->where('kode_jns_aktifitas', $params['kode_jns_aktifitas']);
        }
        if (isset($params['costcenter']) && !empty($params['costcenter'])) {
            $this->db->where('costcenter', $params['costcenter']);
        }
        if (isset($params['golongan']) && !empty($params['golongan'])) {
            $this->db->where('jabatan', $params['golongan']);
        }
        if (isset($params['domestik'])) {
            $this->db->where('domestik', $params['domestik']);
        }
        if (isset($params['kode_expense'])) {
            $this->db->where('kode_expense', $params['kode_expense']);
        }

        if (!$all) {
            $this->db->where('na', 'n');
            $this->db->where('del', 'n');
        }

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_tipeexpenses_kode($params = array())
    {
        $this->db->distinct();
        $this->db->select('kode_expense');
        $this->db->select('tipe_expense_text');
        $this->db->from('tbl_travel_tipeexpense');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_pabrik($params = array())
    {
        $this->db->distinct();
        $this->db->select('tsa.personal_area as value');
        $this->db->select('tsa.personal_area_text as label');
        $this->db->select('0 as free');
        $this->db->from('tbl_travel_subarea tsa');
        $this->db->join('tbl_travel_areaaktifitas taa', 'tsa.akses_jns_aktifitas = taa.akses_jns_aktifitas', 'inner');
        $this->db->where('tsa.personal_subarea <> \'\'', null, false);
        if (isset($params['company_code'])) {
            $this->db->where('tsa.company_code', $params['company_code']);
        } else {
            $this->db->not_like('tsa.company_code', '10', 'before');
        }
        if (isset($params['activity']))
            $this->db->where('taa.kode_jns_aktifitas', $params['activity']);

        $this->db->group_by('tsa.personal_area, personal_area_text');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_tujuan($params = array())
    {
        $params['company_code'] = isset($params['company_code']) ? $params['company_code'] : null;
        $params['personal_area'] = isset($params['personal_area']) ? $params['personal_area'] : null;

        $this->db->distinct();
        $this->db->select('*');
        $this->db->select('CASE WHEN substring(tsa.company_code,3,2)=\'10\' THEN \'Jakarta\' ELSE tsa.personal_subarea_text END as kota', false);
        $this->db->from('tbl_travel_subarea tsa');
        $this->db->join('tbl_travel_areaaktifitas taa', 'tsa.akses_jns_aktifitas = taa.akses_jns_aktifitas', 'inner');
        $this->db->join('DASHBOARDDEV.dbo.ZDMMSPLANT msplant', 'msplant.persa = (tsa.personal_area COLLATE SQL_Latin1_General_CP1_CS_AS)', 'left');
        $this->db->where('tsa.personal_subarea <> \'\'', null, false);
        if (isset($params['company_code'])) {
            $this->db->where('tsa.company_code', $params['company_code']);
        } else {
            $this->db->not_like('tsa.company_code', '10', 'before');
        }

        if (isset($params['personal_area']))
            $this->db->where('tsa.personal_area', $params['personal_area']);

        if (isset($params['activity']))
            $this->db->where('taa.kode_jns_aktifitas', $params['activity']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_tipeexpenses($params = array())
    {
        $params['company_code'] = isset($params['company_code']) ? $params['company_code'] : null;
        $params['amount_type'] = isset($params['amount_type']) ? $params['amount_type'] : null;
        $params['tipe_company'] = isset($params['tipe_company']) ? $params['tipe_company'] : null;

        $this->db->select('*');
        $this->db->from('tbl_travel_tipeexpense');

        if (isset($params['amount_type'])) {
            $this->db->where('amount_type', $params['amount_type']);
        }
        if (isset($params['golongan'])) {
            $this->db->group_start();
            $this->db->where('jabatan', $params['golongan']);
            $this->db->or_where('jabatan', '');
            $this->db->group_end();
        }
        if (isset($params['country'])) {
            $this->db->group_start();
            $this->db->where('country', $params['country']);
            $this->db->or_where('country', '');
            $this->db->group_end();
            if ($params['country'] === 'ID') {
                $this->db->like('kode_expense', 'D', 'after');
            } else {
                $this->db->like('kode_expense', 'O', 'after');
            }
        }
        if (isset($params['activity'])) {
            $this->db->where('tipe_aktifitas', $params['activity']);
        }
        if (isset($params['ho'])) {
            if ($params['ho'])
                $this->db->where('tipe_company', 'H');
            else
                $this->db->where('tipe_company', 'F');
        }
        if (isset($params['kode_expense'])) {
            if (is_array($params['kode_expense'])) {
                $this->db->where_in('kode_expense', $params['kode_expense']);
            } else {
                $this->db->where('kode_expense', $params['kode_expense']);
            }
        }

        $this->db->where('end_date >= GETDATE()', null, false);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_tipeexpenses_currency($params = array())
    {
        $this->db->distinct();
        $this->db->select('currency');
        $this->db->from('tbl_travel_currency');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_header_last($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id_header_format'] = isset($params['id_header_format']) ? $params['id_header_format'] : null;

        $this->db->select('id_travel_header');
        $this->db->from('tbl_travel_header');
        if (isset($params['id_header_format']))
            $this->db->like('id_travel_header', $params['id_header_format'], 'after');

        $this->db->order_by('id_travel_header', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function cek_approval_cancel($params = array())
    {
        $this->db->select('tbl_travel_cancel.*');

        $this->db->from('tbl_travel_cancel');

        $this->db->where('tbl_travel_cancel.id_travel_header', $params['id_travel_header']);
        $this->db->where('tbl_travel_cancel.na', 'n');
        $this->db->where('tbl_travel_cancel.del', 'n');

        $query = $this->db->get();

        return $query->row();
    }

    public function cek_approval_deklarasi($params = array())
    {
        $this->db->select('tbl_travel_deklarasi_header.*');

        $this->db->from('tbl_travel_deklarasi_header');

        $this->db->where('tbl_travel_deklarasi_header.id_travel_header', $params['id_travel_header']);
        $this->db->where('tbl_travel_deklarasi_header.na', 'n');
        $this->db->where('tbl_travel_deklarasi_header.del', 'n');

        $query = $this->db->get();

        return $query->row();
    }

    public function get_travel_header($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['non_cancel'] = isset($params['non_cancel']) ? $params['non_cancel'] : false;

        $this->db->select('tbl_travel_header.*');
        $this->db->select('CONVERT(VARCHAR, tbl_travel_header.tanggal_buat, 104) as tanggal_buat_format,');
        $this->db->select('case when tbl_travel_role.role is not null then 
              case tls.approval_status 
                WHEN 1 THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role 
                WHEN 2 THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role              
                ELSE tbl_travel_status.nama
              END
            else tsFinish.nama end as status', false);
        $this->db->select('case when tbl_travel_role.role is not null then 
              case  tls.approval_status
                WHEN 1  THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role
                WHEN 2  THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role             
                ELSE tbl_travel_status.nama
              END
            else tsFinish.nama end as status_label', false);
        $this->db->select('case when tbl_travel_status.color is not null
            then tbl_travel_status.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('k.nama as nama_karyawan');
        $this->db->select('k.email as email_karyawan');
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_travel_subarea.personal_area');
        $this->db->select('tbl_travel_role.v_transport_spd');
        $this->db->select('tbl_travel_role.v_transport_spd_um');
        //LHA 11.06.2020 ubah caption label status	
        $this->db->select("(select top 1 tbl_travel_role2.role from tbl_travel_role tbl_travel_role2 where tbl_travel_role2.level>tbl_travel_header.approval_level order by tbl_travel_role2.level asc) as nama_role");
        $this->db->select('case when tbl_travel_role.role is not null then 
              case  tls.approval_status
                WHEN 1  THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role
                WHEN 2  THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role             
                ELSE tbl_travel_status.nama
              END
            else tsFinish.nama end as caption_status_label');


        $this->db->from('tbl_travel_header');
        $this->db->join('tbl_karyawan k', 'k.nik = tbl_travel_header.nik');
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

        if ($params['non_cancel']) {
            $this->db->join('tbl_travel_cancel', 'tbl_travel_cancel.id_travel_header = tbl_travel_header.id_travel_header and tbl_travel_cancel.na = \'n\'', 'left');
            $this->db->where('tbl_travel_cancel.id_travel_header is null', null, false);
        }

        $this->db->where('tls2.id_travel_header is null', null, false);

        if (!$all) {
            $this->db->where('tbl_travel_header.na', 'n');
            $this->db->where('tbl_travel_header.del', 'n');
        }

        if (isset($params['nik'])) {
            if (is_array($params['nik'])) {
                $this->db->where_in("tbl_travel_header.nik", $params['nik']);
            } else {
                $this->db->where("tbl_travel_header.nik", $params['nik']);
            }
        }

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_header.id_travel_header', $params['id']);
        }

        if (isset($params['exclude_edit'])) {
            $this->db->where_not_in('tbl_travel_header.id_travel_header', $params['exclude_edit']);
        }

        if (isset($params['status'])) {
            if (is_array($params['status'])) {
                $this->db->where_in('tbl_travel_header.approval_status', $params['status']);
            } else {
                $this->db->where('tbl_travel_header.approval_status', $params['status']);
            }
        }

        if (isset($params['status_not'])) {
            if (is_array($params['status_not'])) {
                $this->db->where_not_in('tbl_travel_header.approval_status', $params['status_not']);
            } else {
                $this->db->where('tbl_travel_header.approval_status <> ', $params['status_not']);
            }
        }

        if (isset($params['level'])) {
            if (is_array($params['level'])) {
                $this->db->where_in('tbl_travel_header.approval_level', $params['level']);
            } else {
                $this->db->where('tbl_travel_header.approval_level', $params['level']);
            }
        }

        if (isset($params['level_not'])) {
            if (is_array($params['level_not'])) {
                $this->db->where_not_in('tbl_travel_header.approval_level', $params['level_not']);
            } else {
                $this->db->where('tbl_travel_header.approval_level <> ', $params['level_not']);
            }
        }
        //lha
        if (isset($params['status_transportasi'])) {
            if (is_array($params['status_transportasi'])) {
                $this->db->where_in('tbl_travel_header.status_transportasi', $params['status_transportasi']);
            } else {
                $this->db->where('tbl_travel_header.status_transportasi', $params['status_transportasi']);
            }
            $this->db->where("(select count(*) from tbl_travel_cancel where tbl_travel_cancel.id_travel_header=tbl_travel_header.id_travel_header and tbl_travel_cancel.approval_level!='" . TR_LEVEL_FINISH . "' and tbl_travel_cancel.na='n')=0");
        }
        $this->db->order_by('tbl_travel_header.tanggal_buat', 'DESC');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_header_deklarasi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['non_cancel'] = isset($params['non_cancel']) ? $params['non_cancel'] : false;

        $this->db->select('tbl_travel_deklarasi_header.*');
        $this->db->select('CONVERT(VARCHAR, tbl_travel_deklarasi_header.tanggal_buat, 104) as tanggal_buat_format,');
        $this->db->select('case when tbl_travel_role.role is not null then 
              case tls.approval_status 
                WHEN 1 THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role 
                WHEN 2 THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role              
                ELSE tbl_travel_status.nama
              END
            else tsFinish.nama end as status', false);
        $this->db->select('case when tbl_travel_role.role is not null then 
              case  tls.approval_status
                WHEN 1  THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role
                WHEN 2  THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role             
                ELSE tbl_travel_status.nama
              END
            else tsFinish.nama end as status_label', false);
        $this->db->select('case when tbl_travel_status.color is not null
            then tbl_travel_status.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('k.nama as nama_karyawan');
        $this->db->select('k.email as email_karyawan');
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_travel_subarea.personal_area');
        $this->db->select('tbl_travel_role.v_transport_spd');
        $this->db->select('tbl_travel_role.v_transport_spd_um');
        //LHA 11.06.2020 ubah caption label status	
        $this->db->select("(select top 1 tbl_travel_role2.role from tbl_travel_role tbl_travel_role2 where tbl_travel_role2.level>tbl_travel_deklarasi_header.approval_level order by tbl_travel_role2.level asc) as nama_role");
        $this->db->select('case when tbl_travel_role.role is not null then 
              case  tls.approval_status
                WHEN 1  THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role
                WHEN 2  THEN tbl_travel_status.nama+\' \'+tbl_travel_role.role             
                ELSE tbl_travel_status.nama
              END
            else tsFinish.nama end as caption_status_label');

        $this->db->from('tbl_travel_deklarasi_header');
        $this->db->join('tbl_karyawan k', 'k.nik = tbl_travel_deklarasi_header.nik');
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_deklarasi_header.activity'
        );

        $this->db->join(
            'tbl_travel_log_status tls',
            'tls.id_travel_header = tbl_travel_deklarasi_header.id_travel_header 
            and tbl_travel_deklarasi_header.approval_level <> 99',
            'left'
        );
        $this->db->join('tbl_travel_log_status tls2', 'tls.id_travel_header = tls2.id_travel_header and tls.tgl_status < tls2.tgl_status', 'left');

        $this->db->join('tbl_travel_status', 'tbl_travel_status.status = tls.approval_status', 'left outer');
        $this->db->join('tbl_travel_status tsFinish', 'tsFinish.status = tbl_travel_deklarasi_header.approval_status', 'left outer');
        $this->db->join('tbl_travel_role', 'tbl_travel_role.level = tls.approval_level', 'left outer');
        $this->db->join(
            'tbl_travel_subarea',
            'tbl_travel_subarea.company_code = tbl_travel_deklarasi_header.tujuan and tbl_travel_subarea.personal_subarea <> \'\'',
            'left'
        );

        if ($params['non_cancel']) {
            $this->db->join('tbl_travel_cancel', 'tbl_travel_cancel.id_travel_header = tbl_travel_deklarasi_header.id_travel_header and tbl_travel_cancel.na = \'n\'', 'left');
            $this->db->where('tbl_travel_cancel.id_travel_header is null', null, false);
        }

        $this->db->where('tls2.id_travel_header is null', null, false);

        if (!$all) {
            $this->db->where('tbl_travel_deklarasi_header.na', 'n');
            $this->db->where('tbl_travel_deklarasi_header.del', 'n');
        }

        if (isset($params['nik'])) {
            if (is_array($params['nik'])) {
                $this->db->where_in("tbl_travel_deklarasi_header.nik", $params['nik']);
            } else {
                $this->db->where("tbl_travel_deklarasi_header.nik", $params['nik']);
            }
        }

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_deklarasi_header.id_travel_header', $params['id']);
        }

        if (isset($params['status'])) {
            if (is_array($params['status'])) {
                $this->db->where_in('tbl_travel_deklarasi_header.approval_status', $params['status']);
            } else {
                $this->db->where('tbl_travel_deklarasi_header.approval_status', $params['status']);
            }
        }

        if (isset($params['status_not'])) {
            if (is_array($params['status_not'])) {
                $this->db->where_not_in('tbl_travel_deklarasi_header.approval_status', $params['status_not']);
            } else {
                $this->db->where('tbl_travel_deklarasi_header.approval_status <> ', $params['status_not']);
            }
        }

        if (isset($params['level'])) {
            if (is_array($params['level'])) {
                $this->db->where_in('tbl_travel_deklarasi_header.approval_level', $params['level']);
            } else {
                $this->db->where('tbl_travel_deklarasi_header.approval_level', $params['level']);
            }
        }

        if (isset($params['level_not'])) {
            if (is_array($params['level_not'])) {
                $this->db->where_not_in('tbl_travel_deklarasi_header.approval_level', $params['level_not']);
            } else {
                $this->db->where('tbl_travel_deklarasi_header.approval_level <> ', $params['level_not']);
            }
        }
        //lha
        if (isset($params['status_transportasi'])) {
            if (is_array($params['status_transportasi'])) {
                $this->db->where_in('tbl_travel_deklarasi_header.status_transportasi', $params['status_transportasi']);
            } else {
                $this->db->where('tbl_travel_deklarasi_header.status_transportasi', $params['status_transportasi']);
            }
            $this->db->where("(select count(*) from tbl_travel_cancel where tbl_travel_cancel.id_travel_header=tbl_travel_deklarasi_header.id_travel_header and tbl_travel_cancel.approval_level!='" . TR_LEVEL_FINISH . "' and tbl_travel_cancel.na='n')=0");
        }
        $this->db->order_by('tbl_travel_deklarasi_header.tanggal_buat', 'DESC');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_detail_transport($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['id_header'] = isset($params['id_header']) ? $params['id_header'] : null;
        $this->db->select("CASE 
                            WHEN (select top 1 x.tanggal from tbl_travel_transport x
                                    where x.id_travel_detail = tbl_travel_detail.id_travel_detail
                                    AND x.jenis_kendaraan =  'pesawat'
                                    AND 0 = x.transport_kembali
                                    AND x.na='n' AND x.del='n') IS NOT NULL
                            THEN (select top 1 x.tanggal from tbl_travel_transport x
                                    where x.id_travel_detail = tbl_travel_detail.id_travel_detail
                                    AND x.jenis_kendaraan =  'pesawat'
                                    AND 0 = x.transport_kembali
                                    AND x.na='n' AND x.del='n')       
                            ELSE 
                              tbl_travel_detail.start_date
                            END as start_date_,
                          CASE 
                            WHEN (select top 1 x.jam from tbl_travel_transport x
                                    where x.id_travel_detail = tbl_travel_detail.id_travel_detail
                                    AND x.jenis_kendaraan =  'pesawat'
                                    AND 0 = x.transport_kembali
                                    AND x.na='n' AND x.del='n') IS NOT NULL
                            THEN (select top 1 x.jam from tbl_travel_transport x
                                    where x.id_travel_detail = tbl_travel_detail.id_travel_detail
                                    AND x.jenis_kendaraan =  'pesawat'
                                    AND 0 = x.transport_kembali
                                    AND x.na='n' AND x.del='n')       
                            ELSE 
                              tbl_travel_detail.start_time
                            END as start_time_,
                          
                          CASE 
                            WHEN (select top 1 x.tanggal from tbl_travel_transport x
                                    where x.id_travel_detail = tbl_travel_detail.id_travel_detail
                                    AND x.jenis_kendaraan =  'pesawat'
                                    AND 1 = x.transport_kembali
                                    AND x.na='n' AND x.del='n') IS NOT NULL
                            THEN (select top 1 x.tanggal from tbl_travel_transport x
                                    where x.id_travel_detail = tbl_travel_detail.id_travel_detail
                                    AND x.jenis_kendaraan =  'pesawat'
                                    AND 1 = x.transport_kembali
                                    AND x.na='n' AND x.del='n')       
                            ELSE 
                              tbl_travel_detail.end_date
                            END as end_date_,
                          CASE 
                            WHEN (select top 1 x.jam from tbl_travel_transport x
                                    where x.id_travel_detail = tbl_travel_detail.id_travel_detail
                                    AND x.jenis_kendaraan =  'pesawat'
                                    AND 1 = x.transport_kembali
                                    AND x.na='n' AND x.del='n') IS NOT NULL
                            THEN (select top 1 x.jam from tbl_travel_transport x
                                    where x.id_travel_detail = tbl_travel_detail.id_travel_detail
                                    AND x.jenis_kendaraan =  'pesawat'
                                    AND 1 = x.transport_kembali
                                    AND x.na='n' AND x.del='n')       
                            ELSE 
                              tbl_travel_detail.end_time
                            END as end_time_,
                          
                          ");
        $this->db->select('tbl_travel_detail.*');
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity');
        $this->db->from('tbl_travel_detail');
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_detail.activity'
        );

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_detail.id_travel_detail', $params['id']);
        }

        if (isset($params['id_header'])) {
            $this->db->where('tbl_travel_detail.id_travel_header', $params['id_header']);
        }

        if (!$all) {
            $this->db->where('tbl_travel_detail.na', 'n');
            $this->db->where('tbl_travel_detail.del', 'n');
        }

        $this->db->order_by('tbl_travel_detail.no_urut', 'asc');

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
        $params['id_header'] = isset($params['id_header']) ? $params['id_header'] : null;
        $this->db->select('tbl_travel_detail.*');
        $this->db->select(
            'tbl_travel_jenisaktifitas.jenis_aktifitas as activity,
            travel_penjemput.nama AS transportasi_penjemput'
        );
        $this->db->from('tbl_travel_detail');
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_detail.activity'
        );
        $this->db->join(
            "(
                SELECT * 
                FROM tbl_travel_transport_master
                WHERE jenis = 'penjemputan' AND na = 'n' AND del = 'n'
            ) travel_penjemput",
            'travel_penjemput.kode = tbl_travel_detail.transport_pick',
            'left'
        );

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_detail.id_travel_detail', $params['id']);
        }

        if (isset($params['id_header'])) {
            $this->db->where('tbl_travel_detail.id_travel_header', $params['id_header']);
        }

        if (!$all) {
            $this->db->where('tbl_travel_detail.na', 'n');
            $this->db->where('tbl_travel_detail.del', 'n');
        }

        $this->db->order_by('tbl_travel_detail.no_urut', 'asc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    //lha
    public function get_travel_detail_deklarasi($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['id_header'] = isset($params['id_header']) ? $params['id_header'] : null;

        $this->db->select('tbl_travel_deklarasi_header_detail.*');
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity');
        $this->db->from('tbl_travel_deklarasi_header_detail');
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_deklarasi_header_detail.activity'
        );

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_deklarasi_header_detail.id_travel_detail', $params['id']);
        }

        if (isset($params['id_header'])) {
            $this->db->where('tbl_travel_deklarasi_header_detail.id_travel_header', $params['id_header']);
        }

        if (!$all) {
            $this->db->where('tbl_travel_deklarasi_header_detail.na', 'n');
            $this->db->where('tbl_travel_deklarasi_header_detail.del', 'n');
        }

        $this->db->order_by('tbl_travel_deklarasi_header_detail.no_urut', 'asc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_header_booking_transport($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['finish'] = isset($params['finish']) ? $params['finish'] : false;

        $this->db->distinct();
        $this->db->select('tbl_travel_header.*');
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');
        $this->db->select('tbl_travel_role.v_transport_spd');
        $this->db->select('tbl_travel_role.v_transport_spd_um');
        $this->db->select("(select count(*) from tbl_travel_discuss where tbl_travel_discuss.id_travel_header=tbl_travel_header.id_travel_header and CHARINDEX('" . base64_decode($this->session->userdata("-nik-")) . "', tbl_travel_discuss.status_read)=0) as jumlah_komentar");
        $this->db->from('tbl_travel_header');

        $this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_travel_header.nik', 'left');
        $this->db->join(
            'tbl_travel_transport',
            'tbl_travel_transport.id_travel_header = tbl_travel_header.id_travel_header',
            'left'
        );
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_header.activity'
        );

        $this->db->join(
            'tbl_travel_role',
            'tbl_travel_role.level = tbl_travel_header.approval_level',
            'left'
        );

        $this->db->group_start();
        $this->db->like("tbl_travel_header.transportasi", 'pesawat', 'both');
        $this->db->or_like("tbl_travel_header.transportasi", 'taxi', 'both');
        $this->db->or_like("tbl_travel_header.jenis_penginapan", 'hotel', 'both');
        $this->db->group_end();

        if ($params['finish']) {
            $this->db->group_start();
            $this->db->where('tbl_travel_header.approval_level', TR_LEVEL_FINISH);
            $this->db->where_in('tbl_travel_header.approval_status', array(TR_STATUS_SELESAI));
            $this->db->group_end();
        } else if ($params['finish'] == false) {
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where("tbl_travel_header.no_trip != '' AND tbl_travel_header.no_trip IS NOT NULL ");
            $this->db->group_end();
            $this->db->group_end();
        }

        if (!$all) {
            $this->db->where('tbl_travel_header.na', 'n');
            $this->db->where('tbl_travel_header.del', 'n');
        }

        if (isset($params['nik']))
            $this->db->where_in("tbl_travel_header.nik", $params['nik']);

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_header.id_travel_header', $params['id']);
        }

        if (isset($params['transport_kembali'])) {
            $this->db->where('tbl_travel_transport.transport_kembali', $params['transport_kembali']);
        }

        $this->db->order_by('id_travel_header', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_header_booking_penerimaan($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['check'] = isset($params['check']) ? $params['check'] : null;

        $this->db->select('td.*');
        $this->db->select('th.no_trip');
        $this->db->select('th.nik');
        $this->db->select('th.transportasi');
        $this->db->select('tja.jenis_aktifitas as activity_label');
        $this->db->select('k.nama as nama_karyawan');
        $this->db->from('tbl_travel_header th');
        $this->db->join('tbl_travel_detail td', 'th.id_travel_header = td.id_travel_header');
        $this->db->join('tbl_karyawan k', 'k.nik = th.nik', 'left');
        $this->db->join(
            'tbl_travel_jenisaktifitas tja',
            'tja.kode_jns_aktifitas = th.activity'
        );

        $this->db->where('td.country', 'ID');
        $this->db->where('th.no_trip is not null', null, false);

        if ($params['check'])
            $this->db->where('pic_check', $params['check']);

        if (!$all) {
            $this->db->where('th.na', 'n');
            $this->db->where('th.del', 'n');
            $this->db->where('td.na', 'n');
            $this->db->where('td.del', 'n');
        }

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_transport_options($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['persa_from'] = isset($params['persa_from']) ? $params['persa_from'] : null;
        $params['persa_to'] = isset($params['persa_to']) ? $params['persa_to'] : null;

        $this->db->select('tbl_travel_transport_options.*');
        $this->db->select('t_persa_from.personal_area_text as persa_from_text');
        $this->db->select('t_persa_to.personal_area_text as persa_to_text');
        $this->db->select('cast(stuff(
                (
                    select cast(\', \' as varchar(max))+cast(tbl_travel_transport_master.nama as varchar(max))
                    from tbl_travel_transport_master
                    where kode in (
                        select sd.splitdata from dbo.fnSplitString(tbl_travel_transport_options.transports,\',\') sd
                    ) 
                    and jenis = \'keberangkatan\'
                    for xml path(\'\')
                )
            ,1,2,\'\') as varchar(max)) as transportasi_label');
        $this->db->from('tbl_travel_transport_options');
        $this->db->join('(
            select personal_area, personal_area_text
            from tbl_travel_subarea
            group by personal_area, personal_area_text
        ) as t_persa_from', 't_persa_from.personal_area = tbl_travel_transport_options.persa_from', 'left');
        $this->db->join('(
            select personal_area, personal_area_text
            from tbl_travel_subarea
            group by personal_area, personal_area_text
        ) as t_persa_to', 't_persa_to.personal_area = tbl_travel_transport_options.persa_to', 'left');

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_transport_options.id_travel_transport_options', $params['id']);
        }
        if (isset($params['persa_from'])) {
            $this->db->where('tbl_travel_transport_options.persa_from', $params['persa_from']);
        }
        if (isset($params['persa_to'])) {
            $this->db->where('tbl_travel_transport_options.persa_to', $params['persa_to']);
        }

        if (!$all) {
            $this->db->where('tbl_travel_transport_options.na', 'n');
            $this->db->where('tbl_travel_transport_options.del', 'n');
        }

        $this->db->order_by('tbl_travel_transport_options.id_travel_transport_options', 'asc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_mess_options($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_travel_mess_options.*');
        $this->db->select('t_persa.personal_area_text as persa_text');
        $this->db->from('tbl_travel_mess_options');
        $this->db->join('(
            select personal_area, personal_area_text
            from tbl_travel_subarea
            group by personal_area, personal_area_text
        ) as t_persa', 't_persa.personal_area = tbl_travel_mess_options.persa', 'left');
        if (isset($params['id'])) {
            $this->db->where('tbl_travel_mess_options.id_travel_mess_option', $params['id']);
        }
        if (isset($params['persa'])) {
            $this->db->where('tbl_travel_mess_options.persa', $params['persa']);
        }
        if (isset($params['available'])) {
            $this->db->where('tbl_travel_mess_options.available', $params['available']);
        }

        if (!$all) {
            $this->db->where('tbl_travel_mess_options.na', 'n');
            $this->db->where('tbl_travel_mess_options.del', 'n');
        }

        $this->db->order_by('tbl_travel_mess_options.id_travel_mess_option', 'asc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_transport_master($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['kode'] = isset($params['kode']) ? $params['kode'] : null;

        $this->db->select('tbl_travel_transport_master.*');
        $this->db->from('tbl_travel_transport_master');

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_transport_master.id_travel_transport_master', $params['id']);
        }

        if (isset($params['kode'])) {
            $this->db->where('tbl_travel_transport_master.kode', $params['kode']);
        }

        if (isset($params['jenis'])) {
            $this->db->where('tbl_travel_transport_master.jenis', $params['jenis']);
        }

        if (!$all) {
            $this->db->where('tbl_travel_transport_master.na', 'n');
            $this->db->where('tbl_travel_transport_master.del', 'n');
        }

        $this->db->order_by('tbl_travel_transport_master.kode', 'asc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_transport($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['id_header'] = isset($params['id_header']) ? $params['id_header'] : null;

        $this->db->select('tbl_travel_transport.*');
        $this->db->from('tbl_travel_transport');

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_transport.id_travel_transport', $params['id']);
        }

        if (isset($params['id_header'])) {
            $this->db->where('tbl_travel_transport.id_travel_header', $params['id_header']);
        }

        if (isset($params['transport_kembali'])) {
            $this->db->where('tbl_travel_transport.transport_kembali', $params['transport_kembali']);
        }

        if (!$all) {
            $this->db->where('tbl_travel_transport.na', 'n');
            $this->db->where('tbl_travel_transport.del', 'n');
        }

        $this->db->order_by('tbl_travel_transport.id_travel_transport', 'asc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_hotel($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['id_header'] = isset($params['id_header']) ? $params['id_header'] : null;

        $this->db->select('tbl_travel_hotel.*');
        $this->db->from('tbl_travel_hotel');

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_hotel.id_travel_hotel', $params['id']);
        }

        if (isset($params['id_header'])) {
            $this->db->where('tbl_travel_hotel.id_travel_header', $params['id_header']);
        }

        if (!$all) {
            $this->db->where('tbl_travel_hotel.na', 'n');
            $this->db->where('tbl_travel_hotel.del', 'n');
        }

        $this->db->order_by('tbl_travel_hotel.id_travel_hotel', 'asc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_downpayment($params = array())
    {
        $params['id_header'] = isset($params['id_header']) ? $params['id_header'] : null;
        $params['company_code'] = isset($params['company_code']) ? $params['company_code'] : null;
        $params['amount_type'] = isset($params['amount_type']) ? $params['amount_type'] : null;

        $this->db->select('te.*');
        $this->db->select('dp.id_travel_downpayment as id');
        $this->db->select('dp.jumlah');
        $this->db->select('dp.durasi');
        $this->db->from('tbl_travel_tipeexpense te');
        $this->db->join(
            'tbl_travel_downpayment dp',
            'te.kode_expense = dp.kode_expense'
                . ' and te.amount_type = dp.amount_type'
                . ' and te.end_date = dp.end_date'
                . ' and te.tipe_travel = dp.tipe_travel'
                . ' and te.tipe_company = dp.tipe_company'
                . ' and te.tipe_aktifitas = dp.tipe_aktifitas'
                . ' and te.country = dp.country'
                . ' and te.region = dp.region'
                . ' and te.jabatan = dp.jabatan'
                . ' and te.statutory = dp.statutory',
            'left outer'
        );

        if (isset($params['id_header'])) {
            $this->db->where('id_travel_header', $params['id_header']);
        }

        if (isset($params['amount_type'])) {
            $this->db->where('te.amount_type', $params['amount_type']);
        }
        if (isset($params['golongan'])) {
            $this->db->where('te.jabatan', $params['golongan']);
        }
        if (isset($params['country'])) {
            $this->db->where('te.country', $params['country']);
        }
        if (isset($params['activity'])) {
            $this->db->where('te.tipe_aktifitas', $params['activity']);
        }
        if (isset($params['ho'])) {
            if ($params['ho'])
                $this->db->where('te.tipe_company', 'H');
            else
                $this->db->where('te.tipe_company', 'F');
        }
        if (isset($params['kode_expense'])) {
            if (is_array($params['kode_expense'])) {
                $this->db->where_in('te.kode_expense', $params['kode_expense']);
            } else {
                $this->db->where('te.kode_expense', $params['kode_expense']);
            }
        }

        $this->db->where('te.end_date >= GETDATE()', null, false);
        $this->db->where("te.value > 0.01 ");

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_cancel($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['id_header'] = isset($params['id_header']) ? $params['id_header'] : null;

        $this->db->select('vw_travel_list_cancel.*');

        $this->db->from('vw_travel_list_cancel');

        if (isset($params['id'])) {
            $this->db->where('vw_travel_list_cancel.id_travel_cancel', $params['id']);
        }

        if (isset($params['id_header'])) {
            $this->db->where('vw_travel_list_cancel.id_travel_header', $params['id_header']);
        }

        if (isset($params['nik'])) {
            if (is_array($params['nik'])) {
                $this->db->where_in("vw_travel_list_cancel.nik", $params['nik']);
            } else {
                $this->db->where("vw_travel_list_cancel.nik", $params['nik']);
            }
        }

        if (isset($params['status'])) {
            if (is_array($params['status'])) {
                $this->db->where_in('vw_travel_list_cancel.approval_status', $params['status']);
            } else {
                $this->db->where('vw_travel_list_cancel.approval_status', $params['status']);
            }
        }

        if (!$all) {
            $this->db->where('vw_travel_list_cancel.na', 'n');
            $this->db->where('vw_travel_list_cancel.del', 'n');
        }

        $this->db->order_by('vw_travel_list_cancel.id_travel_cancel', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_deklarasi_ready($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['id_travel_header'] = isset($params['id_travel_header']) ? $params['id_travel_header'] : null;
        $params['nik'] = isset($params['nik']) ? $params['nik'] : null;

        $this->db->select('th.*');
        $this->db->select('case when tr.role is not null then 
              case tls.approval_status
                WHEN 1 THEN ts.nama+\' \'+tr.role 
                WHEN 2 THEN ts.nama+\' \'+tr.role 
                -- WHEN 3 THEN ts.nama+\' \'+tr.role 
                ELSE ts.nama
              END
            else tsFinish.nama end as status', false);
        $this->db->select('case when tr.role is not null then 
              case tls.approval_status
                WHEN 1 THEN ts.nama+\' \'+tr.role 
                WHEN 2 THEN ts.nama+\' \'+tr.role 
                -- WHEN 3 THEN ts.nama+\' \'+tr.role 
                ELSE ts.nama
              END
            else tsFinish.nama end as status_label', false);
        //lha 23.06.2020
        $this->db->select("(select top 1 tbl_travel_deklarasi_header.approval_level from tbl_travel_deklarasi_header where tbl_travel_deklarasi_header.id_travel_header=th.id_travel_header) as deklarasi_approval_level");
        $this->db->select("(select top 1 tbl_travel_deklarasi_header.approval_status from tbl_travel_deklarasi_header where tbl_travel_deklarasi_header.id_travel_header=th.id_travel_header) as deklarasi_approval_status");

        $this->db->select('case when ts.color is not null
            then ts.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('tja.jenis_aktifitas as activity_label');
        $this->db->select('tsa.personal_area');
        $this->db->select('tdh.total_biaya');
        $this->db->select('tdh.total_bayar');

        $this->db->from('tbl_travel_header th', 'desc');

        $this->db->join(
            'tbl_travel_deklarasi_header tdh',
            'th.id_travel_header = tdh.id_travel_header and tdh.na=\'n\'',
            'left outer'
        );

        $this->db->join(
            'tbl_travel_jenisaktifitas tja',
            'tja.kode_jns_aktifitas = th.activity'
        );

        $this->db->join(
            'tbl_travel_log_status tls',
            'tls.id_travel_header = th.id_travel_header 
            and th.approval_level <> 99',
            'left'
        );
        $this->db->join(
            'tbl_travel_log_status tls2',
            'tls.id_travel_header = tls2.id_travel_header and tls.tgl_status < tls2.tgl_status',
            'left'
        );

        $this->db->join(
            'tbl_travel_status ts',
            'ts.status = tls.approval_status',
            'left outer'
        );
        $this->db->join(
            'tbl_travel_status tsFinish',
            'tsFinish.status = th.approval_status',
            'left outer'
        );
        $this->db->join(
            'tbl_travel_role tr',
            'tr.level = tls.approval_level',
            'left outer'
        );
        $this->db->join(
            'tbl_travel_subarea tsa',
            'tsa.company_code = th.tujuan and tsa.personal_subarea <> \'\'',
            'left'
        );

        $this->db->where('tls2.id_travel_header is null', null, false);

        $this->db->where('not exists (
            select null from tbl_travel_cancel tc where tc.id_travel_header = th.id_travel_header
        )', null, false);

        $this->db->where('th.no_trip is not null', null, false);
        $this->db->where('th.approval_level', TR_LEVEL_FINISH);
        $this->db->where('th.approval_status', TR_STATUS_SIAP);

        if (isset($params['nik']))
            $this->db->where('th.nik', $params['nik']);

        if (!$all) {
            $this->db->where('th.na', 'n');
            $this->db->where('th.del', 'n');
        }
        //lha
        $this->db->where('th.status_transportasi', 1);

        $this->db->order_by('th.id_travel_header', 'desc');

        if (isset($params['id_travel_header']))
            $this->db->where('tdh.id_travel_header', $params['id_travel_header']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_deklarasi_header($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['id_travel_header'] = isset($params['id_travel_header']) ? $params['id_travel_header'] : null;

        $this->db->select('tdh.*');
        $this->db->select('case when tr.role is not null then 
              case tls.approval_status 
                WHEN 1 THEN ts.nama+\' \'+tr.role 
                WHEN 2 THEN ts.nama+\' \'+tr.role 
                -- WHEN 3 THEN ts.nama+\' \'+tr.role 
                ELSE ts.nama
              END
            else tsFinish.nama end as status', false);
        $this->db->select('case when tr.role is not null then 
              case tls.approval_status  
                WHEN 1 THEN ts.nama+\' \'+tr.role 
                WHEN 2 THEN ts.nama+\' \'+tr.role 
                -- WHEN 3 THEN ts.nama+\' \'+tr.role 
                ELSE ts.nama
              END
            else tsFinish.nama end as status_label', false);
        //lha 23.06.2020
        $this->db->select("(select top 1 tbl_travel_deklarasi_header.approval_level from tbl_travel_deklarasi_header where tbl_travel_deklarasi_header.id_travel_header=th.id_travel_header) as deklarasi_approval_level");
        $this->db->select("(select top 1 tbl_travel_deklarasi_header.approval_status from tbl_travel_deklarasi_header where tbl_travel_deklarasi_header.id_travel_header=th.id_travel_header) as deklarasi_approval_status");
        $this->db->select('case when ts.color is not null
            then ts.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('th.no_trip');
        $this->db->select('th.nik');
        $this->db->select('th.start_date');
        $this->db->select('th.start_time');
        $this->db->select('th.end_date');
        $this->db->select('th.end_time');
        $this->db->select('th.country');
        $this->db->select('th.tujuan');
        $this->db->select('th.tujuan_lain');
        $this->db->select('th.tujuan_lain');
        $this->db->select('tja.jenis_aktifitas as activity_label');
        $this->db->select('k.nama as nama_karyawan');
        $this->db->from('tbl_travel_deklarasi_header tdh');
        $this->db->join('tbl_travel_header th', 'th.id_travel_header=tdh.id_travel_header');

        $this->db->join('tbl_karyawan k ', 'th.nik = k.nik', 'left');
        $this->db->join(
            'tbl_travel_jenisaktifitas tja',
            'tja.kode_jns_aktifitas = th.activity'
        );

        $this->db->join(
            'tbl_travel_deklarasi_log_status tls',
            'tls.id_travel_header  = tdh.id_travel_header  
            and tdh.approval_level <> 99',
            'left'
        );
        $this->db->join('tbl_travel_deklarasi_log_status tls2', 'tls.id_travel_header = tls2.id_travel_header and tls.tgl_status < tls2.tgl_status', 'left');

        $this->db->join('tbl_travel_status ts', 'ts.status = tls.approval_status', 'left outer');
        $this->db->join('tbl_travel_status tsFinish', 'tsFinish.status = tdh.approval_status', 'left outer');
        $this->db->join('tbl_travel_role tr', 'tr.level = tls.approval_level', 'left outer');

        if (!$all) {
            $this->db->where('tdh.na', 'n');
            $this->db->where('tdh.del', 'n');
        }

        $this->db->where('tls2.id_travel_header is null', null, false);

        if (isset($params['id_travel_header']))
            $this->db->where('tdh.id_travel_header', $params['id_travel_header']);

        if (isset($params['id_travel_deklarasi_header']))
            $this->db->where('tdh.id_travel_deklarasi_header', $params['id']);

        if (isset($params['nik']) && $params['nik'] !== NULL)
            $this->db->where('tdh.nik', $params['nik']);

        if (isset($params['status'])) {
            if (is_array($params['status'])) {
                $this->db->where_in('tdh.approval_status', $params['status']);
            } else {
                $this->db->where('tdh.approval_status', $params['status']);
            }
        }

        if (isset($params['status_not'])) {
            if (is_array($params['status_not'])) {
                $this->db->where_not_in('tdh.approval_status', $params['status_not']);
            } else {
                $this->db->where('tdh.approval_status <> ', $params['status_not']);
            }
        }

        if (isset($params['level'])) {
            if (is_array($params['level'])) {
                $this->db->where_in('tdh.approval_level', $params['level']);
            } else {
                $this->db->where('tdh.approval_level', $params['level']);
            }
        }

        if (isset($params['level_not'])) {
            if (is_array($params['level_not'])) {
                $this->db->where_not_in('tdh.approval_level', $params['level_not']);
            } else {
                $this->db->where('tdh.approval_level <> ', $params['level_not']);
            }
        }

        $this->db->order_by('tdh.id_travel_deklarasi_header', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_deklarasi_detail($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['id_travel_header'] = isset($params['id_travel_header']) ? $params['id_travel_header'] : null;

        $this->db->distinct();
        $this->db->select('tdd.*');
        $this->db->select('CONVERT(VARCHAR, tdd.tanggal, 104) as tanggal_format,');
        $this->db->select('tdh.id_travel_deklarasi_header');
        $this->db->select('te.tipe_expense_text');
        $this->db->from('tbl_travel_deklarasi_detail tdd');
        $this->db->join('tbl_travel_deklarasi_header tdh', 'tdd.id_travel_deklarasi_header = tdh.id_travel_deklarasi_header');
        $this->db->join('tbl_travel_tipeexpense te', 'te.kode_expense= tdd.kode_expense');

        if (!$all) {
            $this->db->where('tdd.na', 'n');
            $this->db->where('tdd.del', 'n');
        }

        if (isset($params['id_travel_header']))
            $this->db->where('tdh.id_travel_header', $params['id_travel_header']);

        if (isset($params['id_travel_deklarasi_header']))
            $this->db->where('tdh.id_travel_deklarasi_header', $params['id_travel_deklarasi_header']);

        if (isset($id))
            $this->db->where('tdd.id_travel_deklarasi_header_detail', $id);

        $this->db->order_by('tdh.id_travel_deklarasi_header', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_log_status($params = array())
    {
        $params['id_travel_header'] = isset($params['id_travel_header']) ? $params['id_travel_header'] : null;
        $params['order_by'] = isset($params['order_by']) ? $params['order_by'] : 'tgl_status desc';

        $this->db->select('tbl_travel_log_status.*, tbl_karyawan.nama as action_by_name');
        $this->db->select('convert(varchar,tbl_travel_log_status.tgl_status,120) as tgl_status_f');
        $this->db->from('tbl_travel_log_status');
        $this->db->join('tbl_karyawan', 'tbl_travel_log_status.action_by = tbl_karyawan.nik', 'left');
        if (isset($params['id_travel_header']))
            $this->db->where('id_travel_header', $params['id_travel_header']);
        if (isset($params['remark']))
            $this->db->where('remark', $params['remark']);

        $this->db->order_by($params['order_by']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_email_log($params = array())
    {
        $id_travel_header = isset($params['id_travel_header']) ? $params['id_travel_header'] : null;
        $query = $this->db->query("EXEC('
                                    SET NOCOUNT ON          
                                    SELECT id_travel_header,cast(splitdata as varchar(100)) approval_nik
                                    INTO #log_nik
                                    FROM tbl_travel_log_status
                                    CROSS APPLY dbo.fnSplitString(approval_nik, ''.'')

                                    SELECT DISTINCT a.* , tbl_karyawan.nama, tbl_karyawan.email 
                                    FROM #log_nik a
                                    LEFT JOIN tbl_karyawan ON tbl_karyawan.nik = a.approval_nik
                                    WHERE id_travel_header = ''" . $id_travel_header . "''
                                        AND approval_nik <> ''''

                                    DROP TABLE #log_nik
                                ')    
                            ");
        $result = $query->result();
        return $result;
    }

    public function get_travel_deklarasi_log_status($params = array())
    {
        $params['id_travel_header'] = isset($params['id_travel_header']) ? $params['id_travel_header'] : null;
        $params['order_by'] = isset($params['order_by']) ? $params['order_by'] : 'tgl_status desc';

        $this->db->select('tbl_travel_deklarasi_log_status.*, tbl_karyawan.nama as action_by_name');
        $this->db->select('convert(varchar,tbl_travel_deklarasi_log_status.tgl_status,120) as tgl_status_f');
        $this->db->from('tbl_travel_deklarasi_log_status');
        $this->db->join('tbl_karyawan', 'tbl_travel_deklarasi_log_status.action_by = tbl_karyawan.nik', 'left');
        if (isset($params['id_travel_header']))
            $this->db->where('id_travel_header', $params['id_travel_header']);

        $this->db->order_by($params['order_by']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_approval($params = array())
    {
        $nik = isset($params['nik']) ? $params['nik'] : base64_decode($this->session->userdata('-nik-'));
        $level = isset($params['level']) ? $params['level'] : 1;

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $query = $this->db->query("EXEC SP_Kiranaku_Travel_Spd_Approval NULL, '$nik', $level");

        $row = $query->row();

        $email_user = '';
        $nik_atasan = '';
        $nik_atasan_email = '';
        $list_atasan = array();
        $list_atasan_email = array();

        if (isset($row) && !empty($row)) {
            $email_user = $row->email;
            $nik_atasan = $row->atasan;
            $nik_atasan_email = $row->atasan_nik_email;
            $list_atasan = explode(', ', $row->atasan_nama);
            $list_atasan_email = explode(' | ', $row->atasan_email);
            foreach ($list_atasan_email as $i => $list_email) {
                $list_atasan_email[$i] = trim($list_email);
            }
        }

        return compact('nik_atasan', 'nik_atasan_email', 'list_atasan', 'list_atasan_email', 'email_user');
    }

    public function get_approval_next($params = array())
    {
        $id = isset($params['id']) ? $params['id'] : null;
        $mode = isset($params['mode']) ? $params['mode'] : 'pengajuan';
        /** @var integer $action 0 = revise/refuse 1 = accept */
        $action = isset($params['action']) ? $params['action'] : 1;

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");

        $query = $this->db->query("EXEC SP_Kiranaku_Travel_Spd_Approval_Next '$id', '$mode', $action");

        $row = $query->row();

        return $row;
    }

    public function get_approval_list($params = array())
    {
        $nik = isset($params['nik']) ? $params['nik'] : base64_decode($this->session->userdata('-nik-'));
        $level = isset($params['level']) ? $params['level'] : 1;
        $mode = isset($params['mode']) ? $params['mode'] : 'pengajuan';
        $id_travel_header = isset($params['id_travel_header']) ? $params['id_travel_header'] : '';
        $lv_role = isset($params['lv_role']) ? $params['lv_role'] : null;

        $this->db->query("SET ANSI_NULLS ON");
        $this->db->query("SET ANSI_WARNINGS ON");
        if ($lv_role == 4) {
            $query = $this->db->query("EXEC SP_Kiranaku_Travel_Spd_Approval_List2 '$nik', '$mode' , '$id_travel_header'");
        } else {
            $query = $this->db->query("EXEC SP_Kiranaku_Travel_Spd_Approval_List '$nik', '$mode' , '$id_travel_header'");
        }

        return $query->result();
    }

    public function get_travel_discuss($params = array())
    {
        $id = isset($params['id']) ? $params['id'] : null;

        $this->db->select('tbl_travel_discuss.*');
        $this->db->select('convert(varchar, tbl_travel_discuss.tanggal_buat, 104) tanggal_disc');
        $this->db->select('LEFT(convert(varchar, tbl_travel_discuss.tanggal_buat, 24),5) jam_disc');
        $this->db->select('k.nama');
        $this->db->select('k.nik');
        $this->db->select('k.gender');
        $this->db->select('k.gambar');
        $this->db->from('tbl_travel_discuss');
        $this->db->join('tbl_user u', 'u.id_user = tbl_travel_discuss.login_buat');
        $this->db->join('tbl_karyawan k', 'u.id_karyawan = k.id_karyawan');

        if (isset($id) && !empty($id))
            $this->db->where('id_travel_header', $id);

        $this->db->order_by('tanggal_buat', 'asc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_data_book_oto($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $nik = $params['nik'];
        $string = "
            SELECT DISTINCT cast(k.nik  AS int) nik, k.ho, tps.company_code, k.gsber, 
                        k.persa, k.btrtl, u.id_jabatan, tps.jns_user, tps.value_user
            FROM tbl_travel_pic_book tps
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
        $query = $this->db->query($string);
        $result = $query->result();

        return $result;
    }

    public function get_travel_approval_history($params = array())
    {
        $params['id_travel_header'] = isset($params['id_travel_header']) ? $params['id_travel_header'] : null;
        $params['order_by'] = isset($params['order_by']) ? $params['order_by'] : 'vh.tgl_status desc';
        $params['tipe'] = isset($params['tipe']) ? $params['tipe'] : null;
        $params['action_by'] = isset($params['action_by']) ? $params['action_by'] : null;
        $params['lv_role'] = isset($params['lv_role']) ? $params['lv_role'] : null;

        $this->db->select('vh.*');
        $this->db->select('case when tr.role is not null then 
              case vh.approval_status 
                WHEN 1 THEN ts.nama+\' \'+tr.role 
                WHEN 2 THEN ts.nama+\' \'+tr.role 
                ELSE ts.nama
              END
            else tsFinish.nama end as status', false);
        $this->db->select('case when tr.role is not null then 
              case  vh.approval_status
                WHEN 1  THEN ts.nama+\' \'+tr.role
                WHEN 2  THEN ts.nama+\' \'+tr.role               
                ELSE ts.nama
              END
            else tsFinish.nama end as status_label', false);
        $this->db->select('case when ts.color is not null
            then ts.color
            else tsFinish.color end
            as status_color', false);
        $this->db->select('tja.jenis_aktifitas as activity_label');
        $this->db->select('tsa.personal_area');
        $this->db->select('k.nama as nama_karyawan');

        $this->db->from('vw_travel_history_persetujuan vh');
        $this->db->join('tbl_karyawan k ', 'vh.nik = k.nik', 'left');
        $this->db->join(
            'tbl_travel_jenisaktifitas tja',
            'tja.kode_jns_aktifitas = vh.activity'
        );
        $this->db->join('tbl_travel_status ts', 'ts.status = vh.approval_status', 'left outer');
        $this->db->join('tbl_travel_status tsFinish', 'tsFinish.status = vh.approval_status', 'left outer');
        $this->db->join('tbl_travel_role tr', 'tr.level = vh.approval_level', 'left outer');
        $this->db->join(
            'tbl_travel_subarea tsa',
            'tsa.company_code = vh.tujuan and tsa.personal_subarea <> \'\'',
            'left'
        );

        if (isset($params['id_travel_header']))
            $this->db->where('vh.id_travel_header', $params['id_travel_header']);

        if (isset($params['tipe']))
            $this->db->where('vh.tipe', $params['tipe']);

        if (isset($params['action_by']))
            $this->db->where('vh.action_by', $params['action_by']);

        if (isset($params['tanggal_awal']))
            $this->db->where('convert(date,vh.tgl_status,112) >=', $params['tanggal_awal']);
        if (isset($params['tanggal_akhir']))
            $this->db->where('convert(date,vh.tgl_status,112) <=', $params['tanggal_akhir']);
        $this->db->order_by($params['order_by']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_travel_merk_trans($params = array())
    {
        $this->db->select('merk, jenis_merk, kode_merk');
        $this->db->from('tbl_travel_master_merk_transport');

        if (isset($params['transport']))
            $this->db->where('jenis_merk', $params['transport']);

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    // halaman awal refundable tiket
    public function get_travel_transport_refundable($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['finish'] = isset($params['finish']) ? $params['finish'] : false;

        $this->db->distinct();
        $this->db->select('tbl_travel_header.*');
        $this->db->select('tbl_travel_transport.*');
        $this->db->select('tbl_travel_transport.tanggal dt_start');
        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');
        $this->db->select('tbl_travel_role.v_transport_spd');
        $this->db->select('tbl_travel_role.v_transport_spd_um');
        $this->db->select("CASE 
                                WHEN (
                                        Select TOP 1 tbl_travel_detail.tujuan_lain 
                                        from tbl_travel_detail
                                        where tbl_travel_detail.id_travel_detail = tbl_travel_transport.id_travel_detail AND na='n'
                                    ) = ''
                                    THEN 
                                    (
                                        select TOP 1 msplant.NAME1+','+msplant.BEZEI+'-'+msplant.CITY1 COLLATE SQL_Latin1_General_CP1_CS_AS 
                                        from dashboarddev.dbo.ZDMMSPLANT msplant
                                        LEFT JOIN tbl_travel_detail 
                                            ON tbl_travel_detail.tujuan_persa = msplant.PERSA COLLATE SQL_Latin1_General_CP1_CS_AS
                                    )
                                ELSE(
                                    Select TOP 1 tbl_travel_detail.tujuan_lain 
                                        from tbl_travel_detail
                                        where tbl_travel_detail.id_travel_detail = tbl_travel_transport.id_travel_detail AND na='n'
                                )
                            END tujuan
                            ");
        $this->db->from('tbl_travel_transport');

        $this->db->join(
            "tbl_travel_header",
            "tbl_travel_transport.id_travel_header = tbl_travel_header.id_travel_header AND tbl_travel_transport.status_tiket = 'Cancel' AND
            tbl_travel_transport.status_tiket_refund = 'Refundable' ",
            'inner'
        );
        $this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_travel_header.nik', 'left');

        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_header.activity'
        );

        $this->db->join(
            'tbl_travel_role',
            'tbl_travel_role.level = tbl_travel_header.approval_level',
            'left'
        );

        $this->db->group_start();
        $this->db->like("tbl_travel_header.transportasi", 'pesawat', 'both');
        $this->db->or_like("tbl_travel_header.transportasi", 'taxi', 'both');
        $this->db->group_end();

        if ($params['finish']) {
            $this->db->group_start();
            $this->db->where('tbl_travel_header.approval_level', TR_LEVEL_FINISH);
            $this->db->where_in('tbl_travel_header.approval_status', array(TR_STATUS_SELESAI));
            $this->db->group_end();
        } else if ($params['finish'] == false) {
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('tbl_travel_header.approval_level', TR_LEVEL_FINISH);
            $this->db->where_in('tbl_travel_header.approval_status', array(TR_STATUS_SIAP));
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where_in('tbl_travel_header.approval_status', array(TR_STATUS_DISETUJUI));
            $this->db->group_start();
            $this->db->where('tbl_travel_role.v_transport_spd', 1);
            $this->db->or_where('tbl_travel_role.v_transport_spd_um', 1);
            $this->db->group_end();
            $this->db->group_end();
            $this->db->group_end();
        }

        if (!$all) {
            $this->db->where('tbl_travel_header.na', 'n');
            $this->db->where('tbl_travel_header.del', 'n');
        }

        if (isset($params['nik']))
            $this->db->where_in("tbl_travel_header.nik", $params['nik']);

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_header.id_travel_header', $params['id']);
        }

        if (isset($params['transport_kembali'])) {
            $this->db->where('tbl_travel_transport.transport_kembali', $params['transport_kembali']);
        }

        $this->db->order_by('tbl_travel_header.id_travel_header', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    public function get_lv_role($params = array())
    {
        $nik = isset($params['nik']) ? $params['nik'] : null;

        $this->db->select('tbl_travel_approval.*');
        $this->db->from('tbl_travel_approval');

        if (isset($nik) && !empty($nik))
            $this->db->where("tbl_travel_approval.value_approval like '%$nik%'");

        $this->db->order_by('tbl_travel_approval.level_role', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    function get_travel_header_booking_transport_bom($params = array())
    {
        // if ($conn !== NULL)
        $this->general->connectDbPortal();

        $this->datatables->select("id_travel_header");
        $this->datatables->select("no_trip");
        $this->datatables->select("activity");
        $this->datatables->select("approval_level");
        $this->datatables->select("approval_status");
        $this->datatables->select("approval_nik");
        $this->datatables->select("nik");
        $this->datatables->select("persa");
        $this->datatables->select("no_hp");
        $this->datatables->select("kota_asal");
        $this->datatables->select("tipe_travel");
        $this->datatables->select("tipe_trip");
        $this->datatables->select("country");
        $this->datatables->select("jenis_tujuan");
        $this->datatables->select("tujuan");
        $this->datatables->select("tujuan_persa");
        $this->datatables->select("tujuan_lain");
        $this->datatables->select("start_date");
        $this->datatables->select("start_time");
        $this->datatables->select("end_date");
        $this->datatables->select("end_time");
        $this->datatables->select("keperluan");
        $this->datatables->select("booking_brgkt");
        $this->datatables->select("booking_kembali");
        $this->datatables->select("login_buat");
        $this->datatables->select("tanggal_buat");
        $this->datatables->select("login_edit");
        $this->datatables->select("tanggal_edit");
        $this->datatables->select("na");
        $this->datatables->select("del");
        $this->datatables->select("jenis_penginapan");
        $this->datatables->select("transportasi");
        $this->datatables->select("total_um");
        $this->datatables->select("tanggal_migrasi");
        $this->datatables->select("sap_synced");
        $this->datatables->select("approval_catatan");
        $this->datatables->select("approval_lampiran");
        $this->datatables->select("status_transportasi");
        $this->datatables->select("activity_label");
        $this->datatables->select("nama_karyawan");
        $this->datatables->select("v_transport_spd");
        $this->datatables->select("v_transport_spd_um");
        $this->datatables->select("jumlah_komentar");
        $this->datatables->select("details");
        $this->datatables->select("kelengkapan_tiket");

        $this->datatables->from("vw_travel_book_header");

        if (isset($params['nik']))
            $this->datatables->where_in("nik", $params['nik']);

        if (isset($params['kelengkapan']))
            $this->datatables->where_in("kelengkapan_tiket  ", $params['kelengkapan']);

        // if ($conn !== NULL)
        $this->general->closeDb();

        $return = $this->datatables->generate();
        $raw = json_decode($return, true);

        $raw['data'] = $this->general->generate_encrypt_json($raw['data'], array("id_travel_header"));

        return $this->general->jsonify($raw);
    }

    public function get_travel_header_booking_transport____($params = array())
    {
        $all = isset($params['all']) ? $params['all'] : false;
        $params['id'] = isset($params['id']) ? $params['id'] : null;
        $params['finish'] = isset($params['finish']) ? $params['finish'] : false;

        $this->db->distinct();
        $this->db->select('tbl_travel_header.*');

        $this->db->select('tbl_travel_jenisaktifitas.jenis_aktifitas as activity_label');
        $this->db->select('tbl_karyawan.nama as nama_karyawan');
        $this->db->select('tbl_travel_role.v_transport_spd');
        $this->db->select('tbl_travel_role.v_transport_spd_um');
        $this->db->select("(select count(*) from tbl_travel_discuss where tbl_travel_discuss.id_travel_header=tbl_travel_header.id_travel_header and CHARINDEX('" . base64_decode($this->session->userdata("-nik-")) . "', tbl_travel_discuss.status_read)=0) as jumlah_komentar");
        $this->db->from('tbl_travel_header');

        $this->db->join('tbl_karyawan', 'tbl_karyawan.nik = tbl_travel_header.nik', 'left');
        $this->db->join(
            'tbl_travel_transport',
            'tbl_travel_transport.id_travel_header = tbl_travel_header.id_travel_header',
            'left'
        );
        $this->db->join(
            'tbl_travel_jenisaktifitas',
            'tbl_travel_jenisaktifitas.kode_jns_aktifitas = tbl_travel_header.activity'
        );

        $this->db->join(
            'tbl_travel_role',
            'tbl_travel_role.level = tbl_travel_header.approval_level',
            'left'
        );

        $this->db->group_start();
        $this->db->like("tbl_travel_header.transportasi", 'pesawat', 'both');
        $this->db->or_like("tbl_travel_header.transportasi", 'taxi', 'both');
        $this->db->or_like("tbl_travel_header.jenis_penginapan", 'hotel', 'both');
        $this->db->group_end();

        if ($params['finish']) {
            $this->db->group_start();
            $this->db->where('tbl_travel_header.approval_level', TR_LEVEL_FINISH);
            $this->db->where_in('tbl_travel_header.approval_status', array(TR_STATUS_SELESAI));
            $this->db->group_end();
        } else if ($params['finish'] == false) {
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where("tbl_travel_header.no_trip != '' AND tbl_travel_header.no_trip IS NOT NULL ");
            $this->db->group_end();
        }

        if (!$all) {
            $this->db->where('tbl_travel_header.na', 'n');
            $this->db->where('tbl_travel_header.del', 'n');
        }

        if (isset($params['nik']))
            $this->db->where_in("tbl_travel_header.nik", $params['nik']);

        if (isset($params['id'])) {
            $this->db->where('tbl_travel_header.id_travel_header', $params['id']);
        }

        if (isset($params['transport_kembali'])) {
            $this->db->where('tbl_travel_transport.transport_kembali', $params['transport_kembali']);
        }

        $this->db->order_by('id_travel_header', 'desc');

        $query = $this->db->get();

        if (isset($params['single']) && $params['single'])
            return $query->row();
        else
            return $query->result();
    }

    function get_rencana_aktifitas($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select('tbl_travel_rencana_aktifitas.*,
            CONVERT(VARCHAR, tbl_travel_rencana_aktifitas.tanggal_aktifitas, 104) as tanggal_aktifitas_format,
        ');
        $this->db->from('tbl_travel_rencana_aktifitas');

        if (isset($param['id_travel_header']) && $param['id_travel_header'] !== NULL)
            $this->db->where('tbl_travel_rencana_aktifitas.id_travel_header', $param['id_travel_header']);

        $this->db->where('tbl_travel_rencana_aktifitas.na', 'n');
        $this->db->where('tbl_travel_rencana_aktifitas.del', 'n');
        $this->db->order_by('tbl_travel_rencana_aktifitas.tanggal_aktifitas');

        $query = $this->db->get();
        $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }

    function get_cuti_pengganti($param = NULL)
    {
        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->connectDbPortal();

        $this->db->select("tbl_travel_cuti_pengganti.*,
            tbl_travel_deklarasi_header.nik,
            CONVERT(VARCHAR, tbl_travel_cuti_pengganti.tanggal_cuti, 104) as tanggal_cuti_format,
            CASE DATEPART(DW, tbl_travel_cuti_pengganti.tanggal_cuti) 
                WHEN 1 THEN 'Minggu' 
                WHEN 2 THEN 'Senin' 
                WHEN 3 THEN 'Selasa'
                WHEN 4 THEN 'Rabu'
                WHEN 5 THEN 'Kamis'
                WHEN 6 THEN 'Jumat'
                WHEN 7 THEN 'Sabtu' 
            END	hari
        ");
        $this->db->from('tbl_travel_cuti_pengganti');
        $this->db->join('tbl_travel_deklarasi_header', 'tbl_travel_deklarasi_header.id_travel_deklarasi_header = tbl_travel_cuti_pengganti.id_travel_deklarasi', 'inner');

        if (isset($param['id_travel_header']) && $param['id_travel_header'] !== NULL)
            $this->db->where('tbl_travel_cuti_pengganti.id_travel_header', $param['id_travel_header']);

        $this->db->where('tbl_travel_cuti_pengganti.na', 'n');
        $this->db->where('tbl_travel_cuti_pengganti.del', 'n');
        $this->db->order_by('tbl_travel_cuti_pengganti.tanggal_cuti');

        $query = $this->db->get();
        $result = $query->result();

        if (isset($param['encrypt']) && $param['encrypt'] !== NULL)
            $result = $this->general->generate_encrypt_json($result, $param['encrypt'], @$param['exclude']);

        if (isset($param['connect']) && !empty($param['connect']) && $param['connect'] === TRUE)
            $this->general->closeDb();

        return $result;
    }
}
