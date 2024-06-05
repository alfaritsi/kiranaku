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
                            <a href="#tab-cancelation" data-toggle="tab">Pembatalan</a>
                        </li>
                        <li>
                            <a href="#tab-declaration" data-toggle="tab">Deklarasi</a>
                        </li>
                        <li>
                            <a href="#tab-history" data-toggle="tab">History</a>
                        </li>
                        <li class="pull-left header"><?php echo $title; ?></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-pengajuan">
                            <?php $this->load->view('sync/tab_primary/_tab_spd_pengajuan'); ?>
                        </div>
                        <div class="tab-pane" id="tab-cancelation">
                            <?php $this->load->view('sync/tab_primary/_tab_spd_cancel'); ?>
                        </div>
                        <div class="tab-pane" id="tab-declaration">
                            <?php $this->load->view('sync/tab_primary/_tab_spd_deklarasi'); ?>
                        </div>
                        <div class="tab-pane" id="tab-history">
                            <?php $this->load->view('sync/tab_primary/_tab_spd_pengajuan_history', compact("tanggal_awal","tanggal_akhir")); ?>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('footer') ?>

<?php 
    // $this->load->view('modals/_modal_spd_pengajuan', compact('approval'));
    // $this->load->view('modals/_modal_detail_spd_pengajuan'); 
    // $this->load->view('modals/_modal_tujuan_spd'); 
    $this->load->view('modal/_modal_spd_revisi'); 
    echo $modal_tujuan . $modal_history . $modal_detail;
?>


<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/travel/spd_global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css"/>


<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.min.js"></script>

<script>
    let tanggal_travels = <?php echo json_encode($tanggal_travels)?>;
</script>

<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/sync/spd_pengajuan.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/sync/spd_pembatalan.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/sync/spd_deklarasi.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/sync/spd_history.js"></script>

