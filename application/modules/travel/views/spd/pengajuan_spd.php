<?php $this->load->view('header') ?>
<style type="text/css">
    .legend2 {
      position: absolute;
      top: 0.7em;
      right: 20px;
      background: #fff;
      line-height:1.2em;
      
    }
    @-moz-document url-prefix() {
      .legend2 {
          position: absolute;
          top: -2.7em;
          right: 20px;
          background: #fff;
          line-height:1.2em;
          z-index:1;
        }
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="nav-tabs-custom tab-success">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active">
                            <a href="#tab-pengajuan" data-toggle="tab">Pengajuan</a>
                        </li>
                        <li class="">
                            <a href="#tab-pembatalan" data-toggle="tab">Pembatalan</a>
                        </li>
                        <li>
                            <a href="#tab-history" data-toggle="tab">History</a>
                        </li>
                        <li class="pull-left header"><?php echo $title; ?></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-pengajuan">
                            <?php $this->load->view('pengajuan/_tab_spd_pengajuan'); ?>
                        </div>
                        <div class="tab-pane" id="tab-pembatalan">
                            <?php $this->load->view('pengajuan/_tab_spd_pembatalan'); ?>
                        </div>
                        <div class="tab-pane" id="tab-history">
                            <?php $this->load->view('pengajuan/_tab_spd_pengajuan_history'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('pengajuan/_modal_spd_pengajuan', compact('approval', 'penginapan')) ?>
<?php $this->load->view('pengajuan/_modal_spd_um_tambah', compact('approval')) ?>
<?php $this->load->view('pembatalan/_modal_spd_pembatalan') ?>
<?php echo $modal_detail . $modal_chat . $modal_tujuan . $modal_history; ?>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/travel/spd_global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>

<script>
    let tanggal_travels = <?php echo json_encode($tanggal_travels)?>;
    $.each(tanggal_travels, function(i,v){
       tanggal_travels[i] = moment(v, 'DD/MM/YYYY');
    });
    let input_max_length = <?php echo TR_INPUT_MAXLENGTH ?>;
    let backdated_max = <?php echo TR_BACKDATED_DAYS_MAX?>;
</script>

<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_pengajuan.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_pembatalan.js?<?php echo time(); ?>"></script>

