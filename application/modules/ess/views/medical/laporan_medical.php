<?php
/**
 * @application  : ESS Cuti & Ijin - Laporan Cuti View
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
                                <div class="col-md-offset-4 col-md-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label class="input-group-addon" for="jenis">Jenis</label>
                                            <select class="select2 form-control" id="jenis" name="jenis">
                                                <option <?php if($filter['jenis']=='Semua') echo 'selected'; ?> value="Semua">Semua</option>
                                                <option <?php if($filter['jenis']=='jalan') echo 'selected'; ?> value="jalan">Rawat Jalan</option>
                                                <option <?php if($filter['jenis']=='inap') echo 'selected'; ?> value="inap">Rawat Inap</option>
                                                <option <?php if($filter['jenis']=='bersalin') echo 'selected'; ?> value="bersalin">Persalinan</option>
                                                <option <?php if($filter['jenis']=='frame') echo 'selected'; ?> value="frame">Frame</option>
                                                <option <?php if($filter['jenis']=='lensa') echo 'selected'; ?> value="lensa">Lensa</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label class="input-group-addon">Status</label>
                                            <select class="select2 form-control" id="id_fbk_status" name="id_fbk_status">
                                                <option <?php if($filter['id_fbk_status'] == 'Semua') echo 'selected'; ?> value="Semua">Semua</option>
                                                <?php foreach ($fbk_status as $status) : ?>
                                                <option <?php if(isset($filter['id_fbk_status']) && $filter['id_fbk_status'] == $status->id_fbk_status) echo 'selected'; ?> value="<?php echo $status->id_fbk_status?>"><?php echo $status->nama; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group input-daterange" id="filter-date">
                                            <label class="input-group-addon" for="tanggal-awal">Tanggal</label>
                                            <input type="text" id="tanggal_awal" name="tanggal_awal" value="<?php echo $this->generate->generateDateFormat($tanggal_awal);?>" class="form-control" autocomplete="off">
                                            <label class="input-group-addon" for="tanggal-awal">-</label>
                                            <input type="text" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo $this->generate->generateDateFormat($tanggal_akhir);?>" class="form-control" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-responsive my-datatable-extends-order table-striped" id="table-history"
                                       data-page-length='25' data-order='[[0,"asc"]]'>
                                    <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Jenis Klaim</th>
                                        <th>Sisa Plafon Awal</th>
                                        <th>Total Klaim</th>
                                        <th>Kwitansi</th>
                                        <th>Status</th>
                                        <th data-orderable="false"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($list_fbk as $data): ?>
                                        <tr>
                                            <td><?php echo $this->generate->generateDateFormat($data->tanggal_buat) ?></td>
                                            <td><?php echo $data->nik ?></td>
                                            <td><?php echo $data->nama_karyawan ?></td>
                                            <td><?php echo $data->fbk_jenis_nama ?></td>
                                            <td><?php echo $this->less->convert_rupiah($data->sisa_plafon_awal) ?></td>
                                            <td><?php echo $this->less->convert_rupiah($data->total_kwitansi) ?></td>
                                            <td>
                                                <a class="detail-kwitansi" href="javascript:void(0)" data-kwitansi='<?php echo $data->enId ?>'>
                                                    <span class="badge bg-green"><?php echo count($data->kwitansi); ?> Kwitansi</span>
                                                </a>
                                            </td>
                                            <td><span class="badge <?php echo $data->warna ?>"><?php echo $data->nama_status ?></span></td>
                                            <td>
                                                <div class='input-group-btn'>
                                                    <button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span class='fa fa-th-large'></span></button>
                                                    <ul class='dropdown-menu pull-right'>
                                                        <li><a href='javascript:void(0)' class='detail-medical' data-detail='<?php echo $data->enId ?>'><i class='fa fa-search'></i> Detail</a></li>
                                                        <li><a href='javascript:void(0)' class='history' data-history='<?php echo $data->enId ?>'><i class='fa fa-history'></i> History</a></li>
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
<?php $this->load->view('_modal_detail') ?>
<?php $this->load->view('_modal_history') ?>
<?php $this->load->view('_modal_kwitansi') ?>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/medical_laporan.js"></script>
