<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lspd
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('dspd');
    }

    /** Generate ID Header, format : ABL1-09-2019-0001 */
    public function generate_header_code($user = null)
    {
        if (isset($user)) {
            $plant = $user->gsber;
            $lastTravel = $this->CI->dspd->get_travel_header_last(
                array(
                    'id_header_format' => $plant . date('-m-Y'),
                    'single' => true
                )
            );

            if (isset($lastTravel)) {
                $nextNumber = intval(substr($lastTravel->id_travel_header, -4, 4)) + 1;
                $nextIdHeader = $plant . date('-m-Y-') . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            } else {
                $nextIdHeader = $plant . date('-m-Y-0001');
            }
            return $nextIdHeader;
        } else {
            return null;
        }
    }

    public function get_default_expenses($params = array())
    {
        $user = $params['user'];
        $jenis_aktifitas = $params['jenis_aktifitas'];
        $idTravelHeader = isset($params['id_header']) ? $this->CI->generate->kirana_decrypt($params['id_header']) : null;
        $type           = isset($params['type']) ? $params['type'] : 'um';

        $cost_center    = substr($user->kostl, -4, 4);
        $golongan       = $user->id_golongan;
        $domestik       = $params['country'] == 'ID' ? 1 : 0;
        $kode_expense   = isset($params['kode_expense']) ? $params['kode_expense'] : null;

        if (isset($idTravelHeader) && !empty($idTravelHeader)) {
            if ($type === 'um') {
                $expenses = $this->CI->dspd->get_travel_downpayment(
                    array(
                        'id_header' => $idTravelHeader,
                        'kode_expense' => $kode_expense,
                        'golongan' => $golongan,
                        'ho' => $user->ho === 'y',
                    )
                );
                if (count($expenses) === 0) {
                    $expenses = $this->CI->dspd->get_travel_tipeexpenses_um(
                        array(
                            'costcenter' => $cost_center,
                            'domestik' => $domestik,
                            'kode_jns_aktifitas' => $jenis_aktifitas,
                            'persa' => $user->persa,
                            'golongan' => $golongan,
                            'kode_expense' => $kode_expense,
                        )
                    );
                }
            } else {
                $expenses = $this->CI->dspd->get_travel_tipeexpenses_declare(
                    array(
                        'costcenter' => $cost_center,
                        'domestik' => $domestik,
                        'kode_jns_aktifitas' => $jenis_aktifitas,
                        'persa' => $user->persa,
                        'golongan' => $golongan,
                        'kode_expense' => $kode_expense,
                    )
                );
            }
        } else {
            if (!isset($user->kostl) || empty($user->kostl)) {
                $expenses = array();
            } else {
                $expenses = $this->CI->dspd->get_travel_tipeexpenses_um(
                    array(
                        'costcenter' => $cost_center,
                        'domestik' => $domestik,
                        'kode_jns_aktifitas' => $jenis_aktifitas,
                        'persa' => $user->persa,
                        'golongan' => $golongan,
                        'kode_expense' => $kode_expense,
                    )
                );
            }
        }
        return $expenses;
    }

    public function get_all_expenses($params = array())
    {
        $user = $params['user'];

        $params['amount_type'] = isset($params['amount_type']) ? $params['amount_type'] : null;
        $cost_center = substr($user->kostl, -4, 4);
        $golongan = $user->id_golongan;
        $tipeCompany = in_array($golongan, array(10, 20, 30, 40, 41, 50, 60, 70)) ? 'H' : 'O';
        $expenses = $this->CI->dspd->get_travel_tipeexpenses(
            array(
                'golongan' => $golongan,
                'tipe_company' => $tipeCompany,
                'country' => $params['country'],
                'amount_type' => $params['amount_type'],
            )
        );

        return $expenses;
    }

    public function get_all_travel_dates($params = array())
    {
        $user = $params['user'];

        $travels = $this->CI->dspd->get_travel_header(
            array(
                'nik' => $user->nik
            )
        );

        $tanggalTravels = array();

        foreach ($travels as $travel) {
            $begin = new DateTime($travel->start_date);
            if (isset($travel->start_date)) {
                $end = new DateTime($travel->end_date);
            } else {
                $end = new DateTime($travel->start_date);
            }
            $end->setTime(0, 0, 1);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            foreach ($period as $dt) {
                if (!in_array($dt->format('d/m/Y'), $tanggalTravels))
                    $tanggalTravels[] = $dt->format("d/m/Y");
            }
        }

        return $tanggalTravels;
    }

    public function get_all_travel_dates_edit($params = array())
    {
        $user = $params['user'];

        $travels = $this->CI->dspd->get_travel_header(
            array(
                'nik' => $user->nik,
                'exclude_edit' => $params['id_travel_header']
            )
        );

        $tanggalTravels = array();

        foreach ($travels as $travel) {
            $begin = new DateTime($travel->start_date);
            if (isset($travel->start_date)) {
                $end = new DateTime($travel->end_date);
            } else {
                $end = new DateTime($travel->start_date);
            }
            $end->setTime(0, 0, 1);

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            foreach ($period as $dt) {
                if (!in_array($dt->format('d/m/Y'), $tanggalTravels))
                    $tanggalTravels[] = $dt->format("d/m/Y");
            }
        }

        return $tanggalTravels;
    }

    public function proses_spd_list($list = array(), $mode = 'pengajuan')
    {
        if (is_array($list)) {
            foreach ($list as $d) {
                switch ($mode) {
                    case 'pengajuan':
                    case 'persetujuan':
                    case 'persetujuan_deklarasi':
                    case 'deklarasi':
                        $this->proses_spd_list_header($d, $mode);
                        break;
                    case 'booking':
                        $this->proses_spd_transport_list_header($d);
                        break;
                    case 'penerimaan':
                        $this->proses_spd_mess_list_header($d);
                        break;
                    case 'history':
                        $this->proses_spd_history_list_header($d);
                        break;
                    case 'history-approval':
                        $this->proses_spd_approval_history_list_header($d);
                        break;
                }
            }
        } else {
            switch ($mode) {
                case 'pengajuan':
                case 'persetujuan':
                case 'persetujuan_deklarasi':
                case 'deklarasi':
                    $this->proses_spd_list_header($list, $mode);
                    break;
                case 'booking':
                    $this->proses_spd_transport_list_header($list);
                    break;
                case 'penerimaan':
                    $this->proses_spd_mess_list_header($list);
                    break;
                case 'history':
                    $this->proses_spd_history_list_header($list);
                    break;
                case 'history-approval':
                    $this->proses_spd_approval_history_list_header($list);
                    break;
            }
        }

        return $list;
    }

    public function proses_spd_list_header($data = null, $mode = 'pengajuan')
    {
        if (isset($data)) {

            $idHeader = $this->CI->generate->kirana_encrypt($data->id_travel_header);

            $data->no_trip = isset($data->no_trip) ? $data->no_trip : '-';
            $data->tanggal_berangkat = date_create($data->start_date . ' ' . $data->start_time)->format('d.m.Y H:i');
            if (isset($data->end_date)) {
                $data->tanggal_kembali = date_create($data->end_date . ' ' . $data->end_time)->format('d.m.Y H:i');
            } else {
                //LHA 11.06.2020 ubah caption label status
                $data->tanggal_kembali = 'Belum ditentukan';
            }

            /** Label Tujuan */
            $details = $this->CI->dspd->get_travel_detail(
                array(
                    'id_header' => $data->id_travel_header
                )
            );
            if (count($details) > 1) {
                $data->tujuan_lengkap = '<i class="fa fa-map-marker"></i>&nbsp;' . count($details) . ' tujuan <a class="pull-right btn btn-xs btn btn-default spd-tujuan" data-id="' . $idHeader . '"><i class="fa fa-search"></i>&nbsp;lihat</a>';
                $data->details = $details;
                foreach ($data->details as &$detail) {
                    $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
                    $detail->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
                    if ($detail->tujuan !== 'lain') {
                        $tujuan = $this->CI->dspd->get_travel_tujuan(
                            array(
                                'company_code' => $detail->tujuan,
                                'single' => true
                            )
                        );
                        if (isset($tujuan)) {
                            if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                                $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                                $detail->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                            } else {
                                $detail->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                            }
                        }
                    }
                }
            } else {
                $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
                $data->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
                if ($data->tujuan !== 'lain') {
                    $tujuan = $this->CI->dspd->get_travel_tujuan(
                        array(
                            'company_code' => $data->tujuan,
                            'single' => true
                        )
                    );
                    if (isset($tujuan)) {
                        if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                            $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                            $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                        } else {
                            $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                        }
                    }
                }
            }
            /** Label Uang muka */
            $expenses = $this->CI->dspd->get_travel_downpayment(
                array(
                    'id_header' => $data->id_travel_header,
                )
            );
            $data->totalUM = array_reduce($expenses, function ($total, $expense) {
                return $total + floatval($expense->jumlah);
            }, 0);

            $data->keperluan = isset($data->keperluan) && !empty($data->keperluan) ? $data->keperluan : '-';

            /** Label Transportasi */
            $transports = explode(',', $data->transportasi);

            $transportMasters = $this->CI->dspd->get_travel_transport_master(
                array(
                    'jenis' => 'keberangkatan',
                )
            );
            $transport_label = array();
            foreach ($transports as $transport) {
                array_filter($transportMasters, function ($t) use ($transport, &$transport_label) {
                    if ($t->kode === $transport) {
                        array_push($transport_label, $t->nama);
                    }
                });
            }
            $data->transportasi_label = $transport_label;

            $booked_transports = $this->CI->dspd->get_travel_header_booking_transport(array(
                'id' => $data->id_travel_header,
                'transport_kembali' => 0
                /** Transport berangkat */
            ));

            /** Label Badge Status */
            $data->status_label = '<span class="badge ' . $data->status_color . '">' . $data->status_label . '</span>';

            /** Cek pengajuan pembatalan */
            $pembatalan = $this->CI->dspd->get_travel_cancel(array('id_header' => $data->id_travel_header, 'single' => true));
            if (isset($pembatalan) && $pembatalan->approval_status !== TR_STATUS_SELESAI) {
                $data->status_label = '<span class="badge ' . $pembatalan->status_color . '">' . $pembatalan->status_label . '</span>';
            }
            /** Cek pengajuan deklarasi */
            $deklarasi = $this->CI->dspd->get_travel_deklarasi_header(array('id_travel_header' => $data->id_travel_header, 'single' => true));

            if (isset($deklarasi) && $deklarasi->approval_status !== TR_STATUS_SELESAI) {
                $data->status_label = '<span class="badge ' . $deklarasi->status_color . '">' . $deklarasi->status_label . '</span>';
            }

            $data->actions = array();

            if ($mode === 'pengajuan') {

                /** Link detail pengajuan */
                $link_baru = base_url() . "travel/spd/detail/" . $this->CI->generate->kirana_decrypt($idHeader);
                $btnDetail = '<li>
                                <a class="action" href="' . $link_baru . '" target="_blank">
                                    <i class="fa fa-search"></i>&nbsp;Detail
                                </a>
                            </li>';

                $btnEdit = "";
                $btnDelete = "";
                $btnTambahUM = "";
                $btnTambahUMEdit = "";
                $btnTambahUMDelete = "";
                $btnCancel = "";
                $btnCancelEdit = "";
                $btnCancelDelete = "";
                $btnCetak = "";
                if ($data->approval_level <= TR_LEVEL_1 && in_array($data->approval_status, array(TR_STATUS_MENUNGGU, TR_STATUS_REVISI))) {
                    $link_edit = base_url() . "travel/spd/edit/" . $this->CI->generate->kirana_decrypt($idHeader);
                    $btnEdit = '<li>
                            <a class="action" href="' . $link_edit . '" target="_blank">
                                <i class="fa fa-edit"></i>&nbsp;Edit
                            </a>
                        </li>';

                    /** Enable jika belum ada pengajuan pembatalan */
                    if (!isset($pembatalan)) {
                        $btnDelete = '<li>
                            <a class="spd-delete" data-id="' . $idHeader . '" href="javascript:void(0)">
                                <i class="fa fa-trash"></i>&nbsp;Hapus
                            </a>
                        </li>';
                    }
                }

                /** Cancel & Tambah UM dropdown menu links
                 ** Hide ketika sudah memiliki pengajuan pembatalan atau deklarasi
                 */
                if (
                    $data->approval_level == TR_LEVEL_FINISH && $data->approval_status <> TR_STATUS_SELESAI
                    && !isset($pembatalan)
                    // && !isset($deklarasi)
                ) {
                    $btnCetak = '<li>
                        <a href="' . base_url() . 'travel/spd/cetak/pengajuan/' . $this->CI->generate->kirana_decrypt($idHeader) . '" target="_blank">
                            <i class="fa fa-print"></i>&nbsp;Cetak
                        </a>
                    </li>';
                    if (!isset($deklarasi)) {
                        $btnTambahUM = '<li>
                                <a class="spd-tambah-um" data-id="' . $idHeader . '" href="javascript:void(0)">
                                    <i class="fa fa-money"></i>&nbsp;Tambah UM
                                </a>
                            </li>';
                        $btnCancel = '';
                        $btnCancel .= '<li>
                                <a href="' . base_url() . 'travel/spd/cancel_pengajuan/' . str_replace("=", "xyz", $idHeader) . '">
                                    <i class="fa fa-times"></i>&nbsp;Batalkan
                                </a>
                            </li>';
                    }
                }
                if (isset($pembatalan) && $pembatalan->approval_level <= TR_LEVEL_1) {
                    $idCancel = $this->CI->generate->kirana_encrypt($pembatalan->id_travel_cancel);

                    $btnEdit = '';
                    $btnCancelDelete = '<li>
                            <a class="spd-cancel-delete" data-id="' . $idCancel . '" href="javascript:void(0)">
                                <i class="fa fa-trash"></i>&nbsp;Hapus Pembatalan
                            </a>
                        </li>';

                    $btnCancelEdit = '';
                    $btnCancelEdit .= '<li>
							<a href="' . base_url() . 'travel/spd/cancel_pengajuan/' . str_replace("=", "xyz", $idHeader) . '/' . $idCancel . '">	
                                <i class="fa fa-pencil"></i>&nbsp;Edit Pembatalan
                            </a>
                        </li>';
                }

                $data->actions = array(
                    $btnEdit,
                    $btnDelete,
                    $btnTambahUM,
                    $btnCancel,
                    $btnCancelEdit,
                    $btnCancelDelete,
                    $btnCetak
                );
            } else if ($mode === 'persetujuan') {

                /** Link detail pengajuan */
                $link_baru = base_url() . "travel/spd/detail/" . $this->CI->generate->kirana_decrypt($idHeader);
                $btnDetail = '<li>
                                <a class="action" href="' . $link_baru . '" target="_blank">
                                    <i class="fa fa-search"></i>&nbsp;Detail
                                </a>
                            </li>';

                /** Validasi pemesanan transportasi sudah dibelikan jika flow role dicentang validasi */
                $allowApproval = true;
                if ($data->totalUM > 0) {
                    if ($data->totalUM > 0 && $data->v_transport_spd_um && count($booked_transports) === 0) {
                        $allowApproval = false;
                    }
                } else {
                    if ($data->v_transport_spd && count($booked_transports) === 0) {
                        $allowApproval = false;
                    }
                }

                /** Button approval pengajuan */
                $btnApproval = '';
                $flagApproval = '';
                if ($allowApproval && (!isset($data->is_superuser) || (isset($data->is_superuser) && !$data->is_superuser))) {
                    $btnApproval = '';
                    $flagApproval = 'true';
                }
                /** Button approval pengajuan diwakili */
                $btnApprovalBy = '';
                $flagApprovalBy = '';
                if ((isset($data->is_superuser) && $data->is_superuser && empty($btnApproval))) {
                    $flagApprovalBy = 'true';
                }

                $data->flagApproval = $flagApproval;
                $data->flagApprovalBy = $flagApprovalBy;
                $data->actions = array(
                    $btnApproval,
                    $btnApprovalBy
                );
            } else if ($mode === 'persetujuan_deklarasi') {
                $btnDetail = '<li>
								<a href="' . base_url() . 'travel/spd/detail_deklarasi/' . $this->CI->generate->kirana_decrypt($idHeader) . '">
                                    <i class="fa fa-search"></i>&nbsp;Detail
                                </a>
                            </li>';

                /** Validasi pemesanan transportasi sudah dibelikan jika flow role dicentang validasi */
                $allowApproval = true;
                if ($data->totalUM > 0) {
                    if ($data->totalUM > 0 && $data->v_transport_spd_um && count($booked_transports) === 0) {
                        $allowApproval = false;
                    }
                } else {
                    if ($data->v_transport_spd && count($booked_transports) === 0) {
                        $allowApproval = false;
                    }
                }
                /** Button approval pengajuan */
                $btnApproval = '';
                $flagApproval = '';
                if ($allowApproval && (!isset($data->is_superuser) || (isset($data->is_superuser) && !$data->is_superuser))) {
                    $flagApproval = 'true';
                }

                /** Button approval pengajuan diwakili */
                $btnApprovalBy = '';
                $flagApprovalBy = '';
                if ((isset($data->is_superuser) && $data->is_superuser && empty($btnApproval))) {
                    $btnApprovalBy .= '<li>
                            <a href="' . base_url() . 'travel/spd/app_deklarasi/' . str_replace("=", "xyz", $idHeader) . '/1">
                                <i class="fa fa-check"></i>&nbsp;Approval Mewakili
                            </a>
                        </li>';
                    $flagApprovalBy = 'true';
                }

                $data->flagApproval = $flagApproval;
                $data->flagApprovalBy = $flagApprovalBy;
                $data->actions = array(
                    $btnApproval,
                    $btnApprovalBy
                );
            } else if ($mode === 'deklarasi') {
                $btnDetail = '';
                $btnDeklarasi = "";
                $btnDeleteDeklarasi = "";
                if (isset($deklarasi)) {
                    $btnDetail .= '<li>
                        <a href="' . base_url() . 'travel/spd/detail_deklarasi/' . $this->CI->generate->kirana_decrypt($idHeader) . '">
                            <i class="fa fa-search"></i>&nbsp;Detail
                        </a>
                    </li>';

                    $idDeklarasi = $this->CI->generate->kirana_encrypt($deklarasi->id_travel_deklarasi_header);
                    if ($deklarasi->approval_level <= TR_LEVEL_1 && in_array($deklarasi->approval_status, array(TR_STATUS_MENUNGGU, TR_STATUS_REVISI, TR_STATUS_DITOLAK))) {
                        $btnDeklarasi = "";
                        $btnDeklarasi .= '<li>
                            <a href="' . base_url() . 'travel/spd/edit_deklarasi/' . $this->CI->generate->kirana_decrypt($idHeader) . '">
                                <i class="fa fa-pencil"></i>&nbsp;Edit
                            </a>
                        </li>';
                    }
                } else {
                    /** Cek Tanggal saat ini apa sudah melebihi tanggal akhir spd */
                    $btnDeklarasiEnabled = false;
                    if (date_create($data->end_date) <= date_create()) {
                        $btnDeklarasiEnabled = true;
                    }
                    $btnDeklarasi = "";
                    $cek_status = ($data->total_um == 0 ? 0 : 1);
                    $btnDeklarasi = '<li class="' . ($btnDeklarasiEnabled ? '' : 'disable') . '">
                            <a class="dropdown-item add_deklarasi" href="javascript:void(0)" data-id_travel_header="' . $this->CI->generate->kirana_decrypt($idHeader) . '" data-no_trip="' . $data->no_trip . '" data-nik="' . $data->nik . '" data-cek_status="' . $cek_status . '">
                            <i class="fa fa-plus-circle"></i>&nbsp;Deklarasi
                            </a>
                        </li>';
                }
                $data->actions = array(
                    $btnDeklarasi,
                    $btnDeleteDeklarasi,
                );
            }

            $data->actions[] = $btnDetail;

            /** Link chat transportasi */
            if (
                $mode === 'pengajuan'
                && !isset($pembatalan)
                && $data->approval_level == TR_LEVEL_FINISH
                && $data->approval_status == TR_STATUS_SIAP
            ) {
                $btnChat = '<li class="divider"></li> <li>
                                <a class="spd-chat" data-id="' . $idHeader . '" href="javascript:void(0)">
                                    <i class="fa fa-comments"></i>&nbsp;Chat Personalia
                                </a>
                            </li>';
                $data->actions[] = $btnChat;
            }
        }

        return $data;
    }

    public function proses_spd_history_list_header($data = null)
    {
        if (isset($data)) {

            $idHeader = $this->CI->generate->kirana_encrypt($data->id_travel_header);

            $data->no_trip = isset($data->no_trip) ? $data->no_trip : '-';
            $data->tanggal_berangkat = date_create($data->start_date . ' ' . $data->start_time)->format('d.m.Y H:i');
            if (isset($data->end_date)) {
                $data->tanggal_kembali = date_create($data->end_date . ' ' . $data->end_time)->format('d.m.Y H:i');
            } else {
                $data->tanggal_kembali = '<span class="badge bg-red">Belum ditentukan</span>';
            }

            /** Label Tujuan */
            $details = $this->CI->dspd->get_travel_detail(
                array(
                    'id_header' => $data->id_travel_header
                )
            );
            if (count($details) > 1) {
                $data->tujuan_lengkap = '<i class="fa fa-map-marker"></i>&nbsp;' . count($details) . ' tujuan <a class="pull-right btn btn-xs btn btn-default spd-tujuan" data-id="' . $idHeader . '"><i class="fa fa-search"></i>&nbsp;lihat</a>';
                $data->details = $details;
                foreach ($data->details as &$detail) {
                    $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
                    $detail->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
                    if ($detail->tujuan !== 'lain') {
                        $tujuan = $this->CI->dspd->get_travel_tujuan(
                            array(
                                'company_code' => $detail->tujuan,
                                'single' => true
                            )
                        );
                        if (isset($tujuan)) {
                            if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                                $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                                $detail->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                            } else {
                                $detail->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                            }
                        }
                    }
                }
            } else {
                $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
                $data->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
                if ($data->tujuan !== 'lain') {
                    $tujuan = $this->CI->dspd->get_travel_tujuan(
                        array(
                            'company_code' => $data->tujuan,
                            'single' => true
                        )
                    );
                    if (isset($tujuan)) {
                        if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                            $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                            $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                        } else {
                            $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                        }
                    }
                }
            }
            /** Label Uang muka */
            $expenses = $this->CI->dspd->get_travel_downpayment(
                array(
                    'id_header' => $data->id_travel_header,
                )
            );
            $data->totalUM = array_reduce($expenses, function ($total, $expense) {
                return $total + floatval($expense->jumlah);
            }, 0);

            $data->keperluan = isset($data->keperluan) && !empty($data->keperluan) ? $data->keperluan : '-';

            /** Label Badge Status */
            $data->status_label = '<span class="badge ' . $data->status_color . '">' . $data->status_label . '</span>';

            /** Cek pengajuan pembatalan */
            $pembatalan = $this->CI->dspd->get_travel_cancel(array('id_header' => $data->id_travel_header, 'single' => true));
            if (isset($pembatalan) && $pembatalan->approval_status !== TR_STATUS_SELESAI) {
                $data->status_label = '<span class="badge ' . $pembatalan->status_color . '">' . $pembatalan->status_label . '</span>';
            }

            $btnNoTrip = '';
            if (isset($data->jenis_history) && $data->jenis_history === 'Pengajuan' && !isset($pembatalan)) {
                $btnNoTrip = '<li>
                            <a class="spd-revisi-trip" data-id="' . $idHeader . '" href="javascript:void(0)">
                                <i class="fa fa-edit"></i>&nbsp;Revisi No Trip
                            </a>
                        </li>';
            }
            $btnDetail = '';
            $btnDetail .= '<li>
							<a href="' . base_url() . 'travel/spd/detail_deklarasi/' . $this->CI->generate->kirana_decrypt($idHeader) . '">
								<i class="fa fa-search"></i>&nbsp;Detail
							</a>
						</li>';
            /** Link history pengajuan */
            $btnHistory = '';


            $btnCetak = '<li>
                <a href="' . base_url() . 'travel/spd/cetak/deklarasi/' . $this->CI->generate->kirana_decrypt($idHeader) . '" target="_blank">
                    <i class="fa fa-print"></i>&nbsp;Cetak
                </a>
            </li>';

            $data->actions = array(
                $btnNoTrip,
                $btnDetail,
                $btnHistory,
                $btnCetak
            );
        }

        return $data;
    }

    public function proses_spd_approval_history_list_header($data = null)
    {
        if (isset($data)) {
            $idHeader = $this->CI->generate->kirana_encrypt($data->id_travel_header);

            $data->no_trip = isset($data->no_trip) ? $data->no_trip : '-';
            $data->tanggal_berangkat = date_create($data->start_date . ' ' . $data->start_time)->format('d.m.Y H:i');
            if (isset($data->end_date)) {
                $data->tanggal_kembali = date_create($data->end_date . ' ' . $data->end_time)->format('d.m.Y H:i');
            } else {
                $data->tanggal_kembali = '<span class="badge bg-red">Belum ditentukan</span>';
            }

            /** Label Tujuan */
            $details = $this->CI->dspd->get_travel_detail(
                array(
                    'id_header' => $data->id_travel_header
                )
            );
            if (count($details) > 1) {
                $data->tujuan_lengkap = '<i class="fa fa-map-marker"></i>&nbsp;' . count($details) . ' tujuan <a class="pull-right btn btn-xs btn btn-default spd-tujuan" data-id="' . $idHeader . '"><i class="fa fa-search"></i>&nbsp;lihat</a>';
                $data->details = $details;
                foreach ($data->details as &$detail) {
                    $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
                    $detail->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
                    if ($detail->tujuan !== 'lain') {
                        $tujuan = $this->CI->dspd->get_travel_tujuan(
                            array(
                                'company_code' => $detail->tujuan,
                                'single' => true
                            )
                        );
                        if (isset($tujuan)) {
                            if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                                $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                                $detail->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                            } else {
                                $detail->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                            }
                        }
                    }
                }
            } else {
                $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
                $data->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
                if ($data->tujuan !== 'lain') {
                    $tujuan = $this->CI->dspd->get_travel_tujuan(
                        array(
                            'company_code' => $data->tujuan,
                            'single' => true
                        )
                    );
                    if (isset($tujuan)) {
                        if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                            $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                            $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                        } else {
                            $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                        }
                    }
                }
            }
            /** Label Uang muka */
            $expenses = $this->CI->dspd->get_travel_downpayment(
                array(
                    'id_header' => $data->id_travel_header,
                )
            );
            $data->totalUM = array_reduce($expenses, function ($total, $expense) {
                return $total + floatval($expense->jumlah);
            }, 0);

            $data->keperluan = isset($data->keperluan) && !empty($data->keperluan) ? $data->keperluan : '-';

            /** Label Badge Status */
            $data->status_label = '<span class="badge ' . $data->status_color . '">' . $data->status_label . '</span>';

            $btnNoTrip = '';
            if ($data->jenis_history === 'Pengajuan' && !isset($pembatalan)) {
                $btnNoTrip = '<li>
                            <a class="spd-revisi-trip" data-id="' . $idHeader . '" href="javascript:void(0)">
                                <i class="fa fa-edit"></i>&nbsp;Revisi No Trip
                            </a>
                        </li>';
            }
            if ($data->tipe === 'pengajuan') {
                $btnDetail = '<li>
								<a href="' . base_url() . 'travel/spd/detail/' . $this->CI->generate->kirana_decrypt($idHeader) . '">
                                    <i class="fa fa-search"></i>&nbsp;Detail Perjalanan Dinas
                                </a>
                            </li>';
            } else {
                $btnDetail = '<li>
								<a href="' . base_url() . 'travel/spd/detail_deklarasi/' . $this->CI->generate->kirana_decrypt($idHeader) . '">
                                    <i class="fa fa-search"></i>&nbsp;Detail Deklarasi
                                </a>
                            </li>';
            }

            /** Link history pengajuan */
            $btnHistory = "";

            $data->actions = array(
                $btnNoTrip,
                $btnDetail,
                $btnHistory
            );
        }

        return $data;
    }

    public function proses_spd_transport_list_header($data = null)
    {
        if (isset($data)) {

            $idHeader = $this->CI->generate->kirana_encrypt($data->id_travel_header);
            $isFinish = $data->approval_level == TR_LEVEL_FINISH && $data->approval_status == TR_STATUS_SELESAI ? true : false;
            $isReady = $data->approval_level == TR_LEVEL_FINISH && $data->approval_status == TR_STATUS_SIAP ? true : false;
            if (($data->v_transport_spd || $data->v_transport_spd_um) && $data->approval_status == TR_STATUS_DISETUJUI)
                $isReady = true;

            $data->no_trip = isset($data->no_trip) ? $data->no_trip : '-';
            $data->tanggal_berangkat = date_create($data->start_date . ' ' . $data->start_time)->format('d.m.Y H:i');
            if (isset($data->end_date)) {
                $data->tanggal_kembali = date_create($data->end_date . ' ' . $data->end_time)->format('d.m.Y H:i');
            } else {
                $data->tanggal_kembali = '<span class="badge bg-red">Belum ditentukan</span>';
            }

            $details = $this->CI->dspd->get_travel_detail_transport(
                array(
                    'id_header' => $data->id_travel_header
                )
            );
            if (count($details) > 1) {
                $data->tujuan_lengkap = '<i class="fa fa-map-marker"></i>&nbsp;' . count($details) . ' tujuan <a class="pull-right btn btn-xs btn btn-default spd-tujuan" data-id="' . $idHeader . '"><i class="fa fa-search"></i>&nbsp;lihat</a>';
                $data->details = $details;
                foreach ($data->details as &$detail) {
                    $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
                    $detail->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
                    if ($detail->tujuan !== 'lain') {
                        $tujuan = $this->CI->dspd->get_travel_tujuan(
                            array(
                                'company_code' => $detail->tujuan,
                                'single' => true
                            )
                        );
                        if (isset($tujuan)) {
                            if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                                $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                                $detail->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                            } else {
                                $detail->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                            }
                        }
                    }
                }
            } else {
                $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
                $data->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
                if ($data->tujuan !== 'lain') {
                    $tujuan = $this->CI->dspd->get_travel_tujuan(
                        array(
                            'company_code' => $data->tujuan,
                            'single' => true
                        )
                    );
                    if (isset($tujuan)) {
                        if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                            $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                            $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                        } else {
                            $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                        }
                    }
                }
            }

            /** Label transportasi */
            $transports = explode(',', $data->transportasi);

            $transportMasters = $this->CI->dspd->get_travel_transport_master(
                array(
                    'jenis' => 'keberangkatan',
                )
            );
            $transport_label = array();
            foreach ($transports as $transport) {
                array_filter($transportMasters, function ($t) use ($transport, &$transport_label) {
                    if ($t->kode === $transport) {
                        array_push($transport_label, $t->nama);
                    }
                });
            }
            $data->transportasi_label = $transport_label;

            /** Status transportasi */
            $data->transportasi_status = 0;
            $data->transportasi_status_label = '<br/><span class="badge">' . 0 . ' transportasi</span>';
            $dataTransportasi = $this->CI->dspd->get_travel_transport(
                array(
                    'id_header' => $data->id_travel_header
                )
            );
            if (count($dataTransportasi) > 0) {
                $data->transportasi_status = count($dataTransportasi);
                $e = 1;
                $tiket_pesawat = "";
                foreach ($dataTransportasi as $dt_trans) {
                    $jenis_trv = $dt_trans->jenis_kendaraan;
                    $jenis_pesawat  = $dt_trans->transport_kembali == 1 ? "kembali" : "berangkat";
                    $tiket_primary  = $dt_trans->status_tiket_primary;
                    $tanggal_trans  = date('d-m-Y', strtotime($dt_trans->tanggal));
                    $jam_trans      = date('h:i', strtotime($dt_trans->jam));
                    $tgl_gabung     = $dt_trans->tanggal . " " . $dt_trans->jam;
                    $trans_datetime = date('d-m-Y h:i', strtotime($tgl_gabung));
                    if ($jenis_trv == 'pesawat' && isset($dt_trans->status_tiket) && $dt_trans->status_tiket != "") {
                        // set refund able tiket pesawat
                        $dataRefundable = $dt_trans->status_tiket == 'Cancel' && $dt_trans->status_tiket_refund == 'Refundable' ? 'tiket ' . $dt_trans->no_tiket : "";
                        $availableRefund = date('d-m-Y') > date('d-m-Y', strtotime($dt_trans->tanggal)) && $dt_trans->status_tiket == 'Cancel' && $dt_trans->status_tiket_refund == 'Refundable'  ? 'Bisa refund ' . $dataRefundable : 'Tidak bisa refund';
                        $statement_date = strtotime(date('d-m-Y')) < strtotime(date('d-m-Y', strtotime($dt_trans->tanggal))) ? 'true' : "false";
                        if ($statement_date == 'true' && $dt_trans->status_tiket == 'Cancel' && $dt_trans->status_tiket_refund == 'Refundable') {
                            $availableRefund = 'Bisa refund ' . $dataRefundable;
                        } else if ($statement_date == 'false' && $dt_trans->status_tiket == 'Cancel' && $dt_trans->status_tiket_refund == 'Refundable') {
                            $availableRefund = 'Tidak bisa refund sudah melewati tanggal berangkat';
                        } else if ($dt_trans->status_tiket == 'Cancel' && $dt_trans->status_tiket_refund == 'Unrefundable') {
                            $availableRefund = 'Tidak bisa direfund karena tiket bersifat unrefundable';
                        } else {
                            $availableRefund = '';
                        }

                        $tiket_pesawat .= $dt_trans->status_tiket . "|" . $jenis_trv . "|" . $jenis_pesawat . "|" . $dt_trans->id_travel_detail . "|" . $dt_trans->status_tiket_refund . "|" . $tiket_primary . "|" . $availableRefund . "|" . $dt_trans->no_tiket . "|" . $trans_datetime . ",";
                    } else 
                    if ($jenis_trv == 'pesawat' && ($dt_trans->lampiran != null || $dt_trans->lampiran != "") && ($dt_trans->no_tiket != null || $dt_trans->no_tiket != "") && $dt_trans->status_tiket == "") {
                        $tiket_pesawat .= "Issued|" . $jenis_trv . "|" . $jenis_pesawat . "|" . $dt_trans->id_travel_detail . "|refundable|" . $tiket_primary . "|||" . $trans_datetime . ",";
                    } else {
                        // $tiket_pesawat = "";
                    }
                    $e++;
                }
                $data->transportasi_tiket_pesawat = rtrim($tiket_pesawat, ', ');
                $data->transportasi_status_label = '<br/><span class="badge">' . $data->transportasi_status . ' transportasi |' . $tiket_pesawat . '</span>';
            }

            /** Status penginapan */
            $data->penginapan_status = 0;
            $data->penginapan_status_label = '<br/><span class="badge">' . 0 . ' hotel</span>';

            $dataPenginapan = $this->CI->dspd->get_travel_hotel(
                array(
                    'id_header' => $data->id_travel_header
                )
            );
            if (count($dataPenginapan) > 0) {
                $data->penginapan_status = count($dataPenginapan);
                $data->penginapan_status_label = '<br/><span class="badge">' . $data->penginapan_status . ' hotel</span>';
            }

            /** status booking */
            $data->booking_status = "<span class=\"badge bg-red\">Belum dipesankan</span>";
            if (count($dataTransportasi) > 0 || count($dataPenginapan) || $isFinish) {
                $data->booking_status = '<span class="badge bg-green">Sudah dipesankan</span>'
                    . $data->transportasi_status_label
                    . $data->penginapan_status_label;
            }

            $data->actions = "";

            $btnDetail = '<li>
                            <a class="spd-detail-transport" data-id="' . $idHeader . '" href="javascript:void(0)">
                                <i class="fa fa-search"></i>&nbsp;Detail
                            </a>
                        </li>';

            /** Link history pengajuan */
            $btnHistory = '<li>
                            <a class="spd-history" data-id="' . $idHeader . '" href="javascript:void(0)">
                                <i class="fa fa-bookmark"></i>&nbsp;History
                            </a>
                        </li>';

            /** Link chat pengajuan */
            $pembatalan = $this->CI->dspd->get_travel_cancel(array('id_header' => $data->id_travel_header, 'single' => true));
            $btnChat = '';
            $btnBooking = '';
            $btnBooking2 = '';

            $btnBooking = '<li>
                            <a class="spd-booking" data-id="' . $idHeader . '" href="javascript:void(0)">
                                <i class="fa fa-plus-circle"></i>&nbsp;Booking
                            </a>
                        </li>';

            $btnChat = '<li>
                                <a class="spd-chat" data-id="' . $idHeader . '" href="javascript:void(0)">
                                    <i class="fa fa-comments"></i>&nbsp;Chat Personalia
                                </a>
                            </li>';
            $btnBooking2 = '<li>
                            <a data-id="' . $idHeader . '" href="' . base_url() . 'travel/booking/add/' . str_replace("=", "xyz", $idHeader) . '" >
                                <i class="fa fa-search"></i>&nbsp;Detail
                            </a>
                        </li>';

            $data->actions = $btnBooking2 . $btnChat;
            $data->details_trip = $details;
        }

        return $data;
    }

    public function proses_spd_mess_list_header($data = null)
    {
        if (isset($data)) {

            $idHeader = $this->CI->generate->kirana_encrypt($data->id_travel_header);
            $idDetail = $this->CI->generate->kirana_encrypt($data->id_travel_detail);

            $data->no_trip = isset($data->no_trip) ? $data->no_trip : '-';
            $data->tanggal_berangkat = date_create($data->start_date . ' ' . $data->start_time)->format('d.m.Y H:i');
            if (isset($data->end_date)) {
                $data->tanggal_kembali = date_create($data->end_date . ' ' . $data->end_time)->format('d.m.Y H:i');
            } else {
                $data->tanggal_kembali = '<span class="badge bg-red">Belum ditentukan</span>';
            }

            $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
            $data->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
            if ($data->tujuan !== 'lain') {
                $tujuan = $this->CI->dspd->get_travel_tujuan(
                    array(
                        'company_code' => $data->tujuan,
                        'single' => true
                    )
                );
                if (isset($tujuan)) {
                    if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                        $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                        $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                    } else {
                        $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                    }
                }
            }

            /** Label transportasi */
            $transports = explode(',', $data->transportasi);

            $transportMasters = $this->CI->dspd->get_travel_transport_master(
                array(
                    'jenis' => 'keberangkatan',
                )
            );
            $transport_label = array();
            foreach ($transports as $transport) {
                array_filter($transportMasters, function ($t) use ($transport, &$transport_label) {
                    if ($t->kode === $transport) {
                        array_push($transport_label, $t->nama);
                    }
                });
            }
            $data->transportasi_label = $transport_label;

            /** Status transportasi */
            $data->transportasi_status = 0;
            $data->transportasi_status_label = '';
            $dataTransportasi = $this->CI->dspd->get_travel_transport(
                array(
                    'id_header' => $data->id_travel_header
                )
            );
            if (count($dataTransportasi) > 0) {
                $data->transportasi_status = count($dataTransportasi);
                $data->transportasi_status_label = '<br/><span class="badge">' . $data->transportasi_status . ' transportasi</span>';
            }

            /** status penerimaan */
            $data->booking_status = "<span class=\"badge bg-red\">Belum konfirmasi</span>";
            if ($data->pic_check) {
                $data->booking_status = '<span class="badge bg-green">Sudah konfirmasi</span>';
            }

            $data->actions = "";

            $btnTerima = '<li>
                            <a class="spd-penerimaan" data-id="' . $idDetail . '" data-id-header="' . $idHeader . '" href="javascript:void(0)">
                                <i class="fa fa-check-square"></i>&nbsp;Terima
                            </a>
                        </li>';

            $btnDetail = '';

            /** Link chat pengajuan */
            $btnChat = '';

            $data->actions = $btnTerima . $btnDetail . $btnChat;
        }

        return $data;
    }

    public function proses_spd_list_detail($data = null)
    {
        $idHeader = $this->CI->generate->kirana_encrypt($data->id_travel_header);

        $data->tanggal_berangkat = date_create($data->start_date . ' ' . $data->start_time)->format('d.m.Y H:i');
        if (isset($data->end_date)) {
            $data->tanggal_kembali = date_create($data->end_date . ' ' . $data->end_time)->format('d.m.Y H:i');
        } else {
            $data->tanggal_kembali = 'Belum ditentukan';
        }

        $country = $this->CI->dspd->get_countries(array('country_code' => $data->country, 'single' => true));
        $data->tujuan_lengkap = $country->country_name . ', ' . $data->tujuan_lain;
        if ($data->tujuan !== 'lain') {
            $tujuan = $this->CI->dspd->get_travel_tujuan(
                array(
                    'company_code' => $data->tujuan,
                    'single' => true
                )
            );
            if (isset($tujuan)) {
                if (strpos($tujuan->personal_subarea_text, 'Depo Mitra') !== false) {
                    $tujuanArray = explode('-', $tujuan->personal_subarea_text);
                    $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuanArray[0] . ', ' . $data->tujuan_lain;
                } else {
                    $data->tujuan_lengkap = $tujuan->personal_area_text . ', ' . $tujuan->kota;
                }
            }
        }

        $data->keperluan = isset($data->keperluan) && !empty($data->keperluan) ? $data->keperluan : '-';

        return $data;
    }

    public function travel_log($params = array())
    {
        $db = $this->CI->db;
        $id_travel_header = isset($params['id_travel_header']) ? $params['id_travel_header'] : null;
        $data = isset($params['data']) ? $params['data'] : null;

        /** Jika param data pengajuan tidak tersedia tetapi id travel header tersedia,
         * maka dicoba untuk mengambil data pengajuan melalui id tersebut
         */
        if (!isset($data) && isset($id_travel_header)) {
            $data = $this->CI->dspd->get_travel_header(array('id' => $id_travel_header, 'single' => true));
        } else {
            if (is_array($data)) {
                /** Convert array to object */
                $data = json_decode(json_encode($data), FALSE);
            }
        }

        $action = isset($params['action']) ? $params['action'] : 'pengajuan';
        $remark = isset($params['remark']) ? $params['remark'] : '';
        $status = isset($params['status']) ? $params['status'] : $data->approval_status;
        $level = isset($params['level']) ? $params['level'] : $data->approval_level;
        $nik = isset($params['nik']) ? $params['nik'] : $data->approval_nik;
        $comment = isset($params['comment']) ? $params['comment'] : '';
        $actor = isset($params['actor']) ? $params['actor'] : null;
        /** @var boolean $actor_by Flag approval perwakilan */
        $actor_by = isset($params['actor_by']) ? $params['actor_by'] : false;
        $tipe = isset($params['tipe']) ? $params['tipe'] : 'pengajuan';

        if (isset($data) && isset($id_travel_header)) {
            switch ($action) {
                case 'deklarasi':
                    switch ($remark) {
                        case 'new':
                            $action = 'Pengajuan Deklarasi';
                            $remark = 'Deklarasi SPD';
                            break;
                    }
                    break;
                case 'pembatalan':
                    $tipe = 'pembatalan';
                    switch ($remark) {
                        case 'new':
                            $action = 'Pengajuan Pembatalan';
                            $remark = 'Pembatalan SPD';
                            break;
                        case 'delete':
                            $action = 'Hapus Pengajuan Pembatalan';
                            $remark = '';
                            break;
                        case 'sync':
                            $action = 'Sinkronisasi Pengajuan Pembatalan';
                            $remark = '';
                            break;
                    }
                    break;
                case 'pengajuan':
                    switch ($remark) {
                        case 'new':
                            $action = 'Pengajuan SPD';
                            $remark = '';
                            break;
                        case 'delete':
                            $action = 'Hapus Pengajuan SPD';
                            $remark = '';
                            break;
                        case 'sync':
                            $action = 'Sinkronisasi Pengajuan SPD';
                            $remark = '';
                            break;
                        case 'new_um':
                            $action = 'Pengajuan UM Tambahan SPD';
                            $remark = 'Tambahan UM';
                            break;
                    }
                    break;
                case 'approval':
                    $role = $this->CI->dspd->get_role(array('level' => $data->approval_level, 'single' => true));
                    switch ($remark) {
                        case 'revise':
                            $action = 'Kembali ke User';
                            $remark = 'Ask to Revise. ';
                            if ($actor_by) {
                                $remark .= 'Diwakili oleh ' . $params['data_user']->nama;
                            }
                            break;
                        case 'disapprove':
                            $action = 'Kembali ke User';
                            $remark = 'Ditolak. ';
                            if ($actor_by) {
                                $remark .= 'Diwakili oleh ' . $params['data_user']->nama;
                            }
                            break;
                        case 'approve':
                            $action = 'Disetujui oleh ' . $role->role;
                            if ($level == '4') {
                                $action = 'Disetujui oleh ' . $role->role . ' (Sinkronisasi Pengajuan SPD)';
                            }
                            if ($actor_by) {
                                $remark = 'Diwakili oleh ' . $params['data_user']->nama;
                            } else {
                                $remark = '';
                            }
                            break;
                    }
                    break;
                default:

                    break;
            }
            $dataLog = array(
                'id_travel_header' => $id_travel_header,
                'tgl_status' => date('Y-m-d H:i:s'),
                'action' => $action,
                'remark' => $remark,
                'action_by' => $actor,
                'approval_level' => $level,
                'approval_status' => $status,
                'approval_nik' => $nik,
                'comment' => $comment,
                'tipe' => $tipe,
            );

            $dataLog = $this->CI->dgeneral->basic_column('insert_simple', $dataLog);

            return $db->insert('tbl_travel_log_status', $dataLog);
        } else {
            return false;
        }
    }

    public function travel_deklarasi_log($params = array())
    {
        $db = $this->CI->db;
        $id_travel_header = isset($params['id_travel_header']) ? $params['id_travel_header'] : null;
        $data = isset($params['data']) ? $params['data'] : null;

        /** Jika param data pengajuan tidak tersedia tetapi id travel header tersedia,
         * maka dicoba untuk mengambil data pengajuan melalui id tersebut
         */
        if (!isset($data) && isset($id_travel_header)) {
            $data = $this->CI->dspd->get_travel_deklarasi_header(
                array('id_travel_header' => $id_travel_header, 'single' => true)
            );
        } else {
            if (is_array($data)) {
                /** Convert array to object */
                $data = json_decode(json_encode($data), FALSE);
            }
        }
        $action = isset($params['action']) ? $params['action'] : 'pengajuan';
        $remark = isset($params['remark']) ? $params['remark'] : '';
        $status = isset($params['status']) ? $params['status'] : $data->approval_status;
        $level = isset($params['level']) ? $params['level'] : $data->approval_level;
        $nik = isset($params['nik']) ? $params['nik'] : $data->approval_nik;
        $comment = isset($params['comment']) ? $params['comment'] : '';
        $actor = isset($params['actor']) ? $params['actor'] : null;

        if (isset($data) && isset($id_travel_header)) {
            switch ($action) {
                case 'pengajuan':
                    switch ($remark) {
                        case 'new':
                            $action = 'Pengajuan Deklarasi';
                            $remark = '';
                            break;
                        case 'delete':
                            $action = 'Hapus Pengajuan Deklarasi';
                            $remark = '';
                            break;
                        case 'sync':
                            $action = 'Sinkronisasi Pengajuan Deklarasi';
                            $remark = '';
                            break;
                    }
                    break;
                case 'approval':
                    $role = $this->CI->dspd->get_role(array('level' => $data->approval_level, 'single' => true));
                    switch ($remark) {
                        case 'revise':
                            $action = 'Kembali ke User';
                            $remark = 'Ask to Revise';
                            break;
                        case 'disapprove':
                            $action = 'Kembali ke User';
                            $remark = 'Ditolak';
                            break;
                        case 'approve':
                            $action = 'Disetujui oleh ' . $role->role;
                            $remark = '';
                            break;
                    }
                    break;
                default:

                    break;
            }
            $dataLog = array(
                'id_travel_header' => $id_travel_header,
                'tgl_status' => date('Y-m-d H:i:s'),
                'action' => $action,
                'remark' => $remark,
                'action_by' => $actor,
                'approval_level' => $level,
                'approval_status' => $status,
                'approval_nik' => $nik,
                'comment' => $comment,
            );

            $dataLog = $this->CI->dgeneral->basic_column('update', $dataLog);
            return $db->insert('tbl_travel_deklarasi_log_status', $dataLog);
        } else {
            return false;
        }
    }

    public function approval_next($params = array())
    {
    }

    public function get_penginapan_options($params = array())
    {
        $dataUser = $params['user'];

        $messAvailable = $this->CI->dspd->get_travel_mess_options(array(
            'persa' => $params['persa'],
            'single' => true
        ));

        $options = array();
        $messOption = 'mess';
        $hotelOption = 'hotel';

        if (isset($messAvailable)) {
            if ($messAvailable->available) {
                $options[] = $messOption;
            }
        }

        if ($dataUser->id_level < 9102 || count($options) == 0) {
            $options[] = $hotelOption;
        }

        return join(',', $options);
    }

    public function load_spd_chat()
    {
        return $this->CI->load->view('chat/_modal_chat', null, true);
    }

    public function load_spd_detail()
    {
        return $this->CI->load->view('spd/_modal_detail_spd_pengajuan', null, true);
    }

    public function load_spd_tujuan()
    {
        return $this->CI->load->view('spd/_modal_tujuan_spd', null, true);
    }

    public function load_spd_history()
    {
        return $this->CI->load->view('spd/_modal_history_spd', null, true);
    }

    public function proses_send_email($params = array())
    {
        $subject = isset($params['subject']) ? $params['subject'] : null;
        $from_alias = isset($params['from_alias']) ? $params['from_alias'] : null;
        $message = isset($params['message']) ? $params['message'] : null;
        $to = isset($params['to']) ? $params['to'] : null;
        $cc = isset($params['cc']) ? $params['cc'] : null;

        if (!empty($subject) && !empty($from_alias) && !empty($to) && !empty($message)) {
            setlocale(LC_ALL, 'id_ID', 'IND', 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID');
            $config['protocol'] = 'smtp';
            $config['smtp_host'] = KIRANA_EMAIL_HOST;
            $config['smtp_user'] = KIRANA_EMAIL_USER;
            $config['smtp_pass'] = KIRANA_EMAIL_PASS;
            $config['smtp_port'] = KIRANA_EMAIL_PORT;
            $config['smtp_crypto'] = 'ssl';
            $config['charset'] = 'iso-8859-1';
            $config['wordwrap'] = true;
            $config['mailtype'] = 'html';

            try {
                $open_socket = @fsockopen(KIRANA_EMAIL_HOST, KIRANA_EMAIL_PORT, $errno, $errstr, 30);
                if (!$open_socket) {
                    $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                    $sts = "NotOK";
                    $return = array('sts' => $sts, 'msg' => $msg);
                } else {
                    $this->CI->load->library('email', $config);

                    $this->CI->email->from('no-reply@kiranamegatara.com', $from_alias);
                    $this->CI->email->to($to);
                    if (isset($cc) && !empty($cc)) {
                        $this->CI->email->cc($cc);
                    }

                    $this->CI->email->subject($subject);
                    $this->CI->email->message($message);

                    if (!$this->CI->email->send()) {
                        $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
                        $sts = "NotOK";
                        $return = array('sts' => $sts, 'msg' => $msg);
                    } else {
                        $sts = "OK";
                        $return = array('sts' => $sts);
                    }
                }
            } catch (Exception $e) {
                $msg = $e->getMessage();
                $sts = "NotOK";
                $return = array('sts' => $sts, 'msg' => $msg);
            }
        } else {
            $msg = "Terjadi kesalahan pada sistem pengiriman email, silahkan hubungi admin (IT Staff Kirana).";
            $sts = "NotOK";
            $return = array('sts' => $sts, 'msg' => $msg);
        }

        return $return;
    }
}
