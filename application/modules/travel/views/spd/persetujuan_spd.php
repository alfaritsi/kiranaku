<?php $this->load->view('header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="nav-tabs-custom tab-success">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active">
                            <a href="#tab-pengajuan" data-toggle="tab">Pengajuan</a>
                        </li>
                        <li>
                            <a href="#tab-deklarasi" data-toggle="tab">Deklarasi</a>
                        </li>
                        <li>
                            <a href="#tab-pembatalan" data-toggle="tab">Pembatalan</a>
                        </li>
                        <li>
                            <a href="#tab-history" data-toggle="tab">History</a>
                        </li>
                        <li class="pull-left header"><?php echo $title; ?></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-pengajuan">
                            <?php $this->load->view('persetujuan/_tab_spd_persetujuan_pengajuan'); ?>
                        </div>
                        <div class="tab-pane" id="tab-deklarasi">
                            <?php $this->load->view('persetujuan/_tab_spd_persetujuan_deklarasi'); ?>
                        </div>
                        <div class="tab-pane" id="tab-pembatalan">
                            <?php $this->load->view('persetujuan/_tab_spd_persetujuan_pembatalan'); ?>
                        </div>                        
                        <div class="tab-pane" id="tab-history">
                            <?php $this->load->view('persetujuan/_tab_spd_persetujuan_history'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('footer') ?>
<?php $this->load->view('persetujuan/_modal_spd_persetujuan', compact('approval')) ?>
<?php echo $modal_detail . $modal_tujuan . $modal_history; ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/travel/spd_global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_persetujuan.js?<?php echo time(); ?>"></script>

