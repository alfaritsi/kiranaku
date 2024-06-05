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
                    </div>
                    <div class="box-body">
                        <form name="filter-laporan-cuti" method="post">
                            <div class="row">
                                <div class="col-md-offset-6 col-md-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label class="input-group-addon">Status</label>
                                            <select class="select2 form-control" id="lengkap_filter"
                                                    name="lengkap">
                                                <option <?php if ($filter['lengkap'] == 1) echo 'selected'; ?>
                                                        value="1">Semua
                                                </option>
                                                <option <?php if ($filter['lengkap'] == 0) echo 'selected'; ?>
                                                        value="0">Tidak Lengkap
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
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
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-responsive my-datatable-extends-order table-striped"
                                       id="table-history"
                                       data-page-length='10' data-order='[[0,"desc"],[1,"desc"]]'>
                                    <thead>
                                    <tr>
                                        <th>Tanggal Absen</th>
                                        <th>Absen Masuk</th>
                                        <th>Absen Keluar</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th data-orderable="false" width="10"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($list_bak as $data): ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php
                                                echo $this->generate->generateDateFormat($data->tanggal_absen);
                                                ?>
                                            </td>
                                            <td><?php echo $data->absen_masuk ?></td>
                                            <td><?php echo $data->absen_keluar ?></td>
                                            <td><?php echo $data->keterangan_label ?></td>
                                            <td><?php echo $data->status ?></td>
                                            <td>
                                                <div class='input-group-btn'>
                                                    <button type='button' class='btn btn-xs btn-default dropdown-toggle'
                                                            data-toggle='dropdown'><span class='fa fa-th-large'></span>
                                                    </button>
                                                    <ul class='dropdown-menu pull-right'>
                                                        <?php echo $data->btn; ?>
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
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('_modal_pengajuan') ?>
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
<script src="<?php echo base_url() ?>assets/apps/js/ess/bak_pengajuan.js"></script>