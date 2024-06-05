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
                        <strong class="box-title">DASHBOARD BERITA ACARA KEHADIRAN</strong>
                    </div>
                    <div class="box-body">
                        <form name="filter-bak-laporan" method="post">
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
                        <table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-kelengkapan-menunggu"
                                   data-page-length='25' data-order='[[2,"desc"],[3,"desc"],[4,"desc"],[5,"desc"],[6,"desc"],[7,"desc"]]' data-length-change="false">
                            <thead>
                            <tr>
                                <th width="100">NIK</th>
                                <th>Nama</th>
                                <th width="100">Absensi Tidak Lengkap</th>
                                <th width="100">Datang Terlambat</th>
                                <th width="100">Pulang Cepat</th>
                                <th width="100">Ijin</th>
                                <th width="100">Cuti</th>
                                <th width="100">Perjalanan Dinas</th>
                                <th width="10" data-orderable="false" data-searchable="false">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list_data as $data): ?>
                                <tr>
                                    <td><?php echo $data->nik ?></td>
                                    <td><?php echo $data->nama?></td>
                                    <td>
                                        <a href="<?php echo base_url('ess/dashboard/bak?tipe=0&nik='.$data->nik.'&tanggal_awal='.$this->generate->generateDateFormat($tanggal_awal).'&tanggal_akhir='.$this->generate->generateDateFormat($tanggal_akhir)) ?>">
                                            <?php echo $data->bak ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('ess/dashboard/bak?tipe=1&nik='.$data->nik.'&tanggal_awal='.$this->generate->generateDateFormat($tanggal_awal).'&tanggal_akhir='.$this->generate->generateDateFormat($tanggal_akhir)) ?>">
                                            <?php echo $data->datang_telat ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('ess/dashboard/bak?tipe=2&nik='.$data->nik.'&tanggal_awal='.$this->generate->generateDateFormat($tanggal_awal).'&tanggal_akhir='.$this->generate->generateDateFormat($tanggal_akhir)) ?>">
                                            <?php echo $data->pulang_cepat ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('ess/dashboard/ijin?nik='.$data->nik.'&tanggal_awal='.$this->generate->generateDateFormat($tanggal_awal).'&tanggal_akhir='.$this->generate->generateDateFormat($tanggal_akhir)) ?>">
                                            <?php echo $data->ijin ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('ess/dashboard/cuti?nik='.$data->nik.'&tanggal_awal='.$this->generate->generateDateFormat($tanggal_awal).'&tanggal_akhir='.$this->generate->generateDateFormat($tanggal_akhir)) ?>">
                                            <?php echo $data->cuti ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('ess/dashboard/dinas?nik='.$data->nik.'&tanggal_awal='.$this->generate->generateDateFormat($tanggal_awal).'&tanggal_akhir='.$this->generate->generateDateFormat($tanggal_akhir)) ?>">
                                            <?php echo $data->dinas ?>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('ess/dashboard/bak?nik='.$data->nik.'&tanggal_awal='.$this->generate->generateDateFormat($tanggal_awal).'&tanggal_akhir='.$this->generate->generateDateFormat($tanggal_akhir)) ?>">
                                            <i class="fa fa-list"></i>
                                        </a>
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
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/bak_dashboard.js"></script>
