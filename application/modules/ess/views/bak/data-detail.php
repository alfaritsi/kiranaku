<?php
/**
 * @application  : ESS BAK Pengajuan - View
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
                        <a class="btn btn-success pull-right btn-sm" href="<?php echo $_SERVER['HTTP_REFERER'] ?>">
                            <i class="fa fa-arrow-left"></i> &nbsp; Kembali
                        </a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form name="filter-laporan-cuti" method="post">
                                    <div class="row">
                                        <div class="col-md-4 col-md-offset-8">
                                            <div class="form-group">
                                                <div class="input-group input-daterange" id="filter-date">
                                                    <label class="input-group-addon" for="tanggal-awal">Tanggal</label>
                                                    <input type="text" id="tanggal_awal" name="tanggal_awal"
                                                           value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>"
                                                           class="form-control" autocomplete="off">
                                                    <label class="input-group-addon" for="tanggal-awal">-</label>
                                                    <input type="text" id="tanggal_akhir" name="tanggal_akhir"
                                                           value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>"
                                                           class="form-control" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table table-bordered table-responsive my-datatable-extends-order table-striped"
                               id="table-bak-history"
                               data-page-length='10' data-order='[[0,"desc"]]' data-length-change="false">
                            <thead>
                            <tr>
                                <th>Tanggal Absen</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Absen Masuk</th>
                                <th>Absen Keluar</th>
                                <th>Tanggal Input</th>
                                <th>Alasan</th>
                                <th>Status</th>
                                <th data-orderable="false"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list_detail as $data): ?>
                                <tr>
                                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_absen) ?></td>
                                    <td><?php echo $data->nik ?></td>
                                    <td><?php echo $data->nama_karyawan ?></td>
                                    <td><?php echo $data->absen_masuk ?></td>
                                    <td><?php echo $data->absen_keluar ?></td>
                                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
                                    <td><?php echo $data->alasan ?></td>
                                    <td>
                                        <span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span>
                                    </td>
                                    <td>
                                        <div class='input-group-btn'>
                                            <button type='button' class='btn btn-xs btn-default dropdown-toggle'
                                                    data-toggle='dropdown'><span class='fa fa-th-large'></span></button>
                                            <ul class='dropdown-menu pull-right'>
                                                <li><a href='javascript:void(0)' class='bak-detail'
                                                       data-detail='<?php echo $data->enId ?>'><i
                                                                class='fa fa-search'></i> Detail</a></li>
                                                <li><a href='javascript:void(0)' class='bak-history'
                                                       data-history='<?php echo $data->enId ?>'><i
                                                                class='fa fa-history'></i> History</a></li>
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/bak_data.js"></script>