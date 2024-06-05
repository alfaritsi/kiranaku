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
                               id="btn-export-laporan-bak-ktp"
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
                                            <select class="select2 form-control" id="cico"
                                                    name="cico">
                                                <option <?php if ($cico == 'semua') echo 'selected'; ?>
                                                    value="semua">Semua
                                                </option>
                                                <option <?php if ($cico == 'lengkapcico') echo 'selected'; ?>
                                                    value="lengkapcico">Lengkap CICO
                                                </option>
                                                <option <?php if ($cico == 'nonci') echo 'selected'; ?>
                                                    value="nonci">Tanpa CI
                                                </option>
                                                <option <?php if ($cico == 'nonco') echo 'selected'; ?>
                                                    value="nonco">Tanpa CO
                                                </option>
                                                <option <?php if ($cico == 'noncico') echo 'selected'; ?>
                                                    value="noncico">Tanpa CICO
                                                </option>
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
                               data-page-length='10' data-order='[[2,"desc"]]' data-length-change="false">
                            <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tanggal Absen</th>
                                <th>Absen Masuk</th>
                                <th>Absen Keluar</th>
                                <th>Durasi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list_bak as $data):
                                $durasi 	= ($data->all_duration_in_minutes) ? ($data->duration_hour." jam ".$data->duration_minutes." menit") : "";
                            ?>
                                <tr>
                                    <td><?php echo $data->fcidno ?></td>
                                    <td><?php echo $data->Nama ?></td>
                                    <td><?php echo $this->generate->generateDateFormat($data->tanggal) ?></td>
                                    <td><?php echo $data->tanggal_absen_in ?></td>
                                    <td><?php echo $data->tanggal_absen_out ?></td>
                                    <td><?php echo $durasi ?></td>
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
<script src="<?php echo base_url() ?>assets/apps/js/ess/bak_laporan.js"></script>
