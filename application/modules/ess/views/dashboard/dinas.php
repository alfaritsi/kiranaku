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
                        <div class="pull-left" style="margin-right: 10px;">
                            <a href="<?php echo base_url('ess/dashboard')?>" class="btn btn-success btn-xs">Kembali</a>
                        </div>
                        <strong class="box-title"><?php echo $title?></strong>
                    </div>
                    <div class="box-body">
                        <form name="filter-bak-laporan" method="get">
                            <input type="hidden" name="nik" value="<?php echo $nik ?>" />
                            <div class="row">
                                <div class="col-md-4 col-md-offset-8">
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
                        <table class="table table-bordered table-responsive table-striped my-datatable-extends-order" id="table-bak-menunggu"
                               data-page-length='10' data-order='[[0,"desc"]]' data-length-change="false">
                            <thead>
                            <tr>
                                <!--        <th></th>-->
                                <th>Tanggal Absen</th>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Absen Masuk</th>
                                <th>Absen Keluar</th>
                                <th>Tanggal Input</th>
                                <th>Alasan</th>
                                <!--        <th width="20">Catatan Atasan</th>-->
                                <th>Status</th>
                                <th data-orderable="false"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list_data as $data): ?>
                                <tr>
                                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_absen) ?></td>
                                    <td><?php echo $data->nik ?></td>
                                    <td><?php echo $data->nama_karyawan ?></td>
                                    <td><?php echo $data->absen_masuk ?></td>
                                    <td><?php echo $data->absen_keluar ?></td>
                                    <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
                                    <td><?php echo $data->alasan ?></td>
                                    <td>
                                        <span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
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
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/bak_dashboard.js"></script>
