<?php
/**
 * @application  : ESS BAK - Laporan BAK View
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */

$this->load->view('header')
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title ?></strong></h3>
                        <div class="btn-group btn-group-sm pull-right">
                            <a href="javascript:void(0)"
                               target="_blank" class="btn btn-sm btn-success"
                               id="btn-export-laporan-bak"
                            >
                                <i class="fa fa-download"></i> &nbsp;Export To Excel
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <form name="filter-bak-laporan" method="post">
                            <input type="hidden" name="lokasi" value="<?php echo $lokasi ?>" id="lokasi" />
                            <div class="row">
                                <div class="col-md-offset-6 col-md-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label class="input-group-addon">Status</label>
                                            <select class="select2 form-control" id="id_bak_status_filter"
                                                    name="id_bak_status">
                                                <option <?php if ($filter['id_bak_status'] == 'Semua') echo 'selected'; ?>
                                                    value="Semua">Semua
                                                </option>
                                                <?php foreach ($statuses as $status) : ?>
                                                    <option <?php if (isset($filter['id_bak_status']) && $filter['id_bak_status'] == $status->id_bak_status) echo 'selected'; ?>
                                                        value="<?php echo $status->id_bak_status ?>"><?php echo $status->nama; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group input-daterange" id="filter-date">
                                            <label class="input-group-addon" for="tanggal-awal_filter">Tanggal</label>
                                            <input type="text" id="tanggal_awal_filter" name="tanggal_awal"
                                                   value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>"
                                                   class="form-control" autocomplete="off">
                                            <label class="input-group-addon" for="tanggal-awal_filter">-</label>
                                            <input type="text" id="tanggal_akhir_filter" name="tanggal_akhir"
                                                   value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>"
                                                   class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-kelengkapan-menunggu"
                               data-page-length='10' data-order='[[2,"asc"]]' data-length-change="false">
                            <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tanggal absen</th>
                                <th>Absen masuk</th>
                                <th>Absen keluar</th>
                                <th>Tanggal pengajuan</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th data-orderable="false"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list_bak as $data): ?>
                                <tr>
                                    <td><?php echo $data->nik ?></td>
                                    <td><?php echo $data->nama_karyawan ?></td>
                                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_absen) ?></td>
                                    <td><?php echo $data->absen_masuk ?></td>
                                    <td><?php echo $data->absen_keluar ?></td>
                                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
                                    <td><?php echo $data->alasan ?></td>
                                    <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
                                    <td>
                                        <div class='input-group-btn'>
                                            <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span></button>
                                            <ul class='dropdown-menu pull-right'>
                                                <li><a href='javascript:void(0)' class='bak-detail' data-detail='<?php echo $data->enId ?>'><i class='fa fa-search'></i> Detail</a></li>
                                                <li><a href='javascript:void(0)' class='bak-history' data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('_modal_detail') ?>
<?php $this->load->view('_modal_history') ?>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/bak_laporan.js"></script>
