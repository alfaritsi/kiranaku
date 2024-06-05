<?php $this->load->view('header') ?>
<!-- link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.spa.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.common.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.light.css"> -->
<!-- <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script> -->

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="nav-tabs-custom tab-success">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active">
                            <a href="#tab-booking" data-toggle="tab">Booking</a>
                        </li>
                        <!-- <li>
                            <a href="#tab-history" data-toggle="tab">History</a>
                        </li> -->

                        <?php 
                        foreach ($list as $d):
                            if($d->nik != $session_nik){ 
                        ?>
                            <li>
                                <a href="#tab-refund" data-toggle="tab">Refund Tiket</a>
                            </li>
                        <?php 
                            }
                        break;
                        endforeach; 
                        ?>
                        <li class="pull-left header"><?php echo $title; ?></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-booking">
                            <?php $this->load->view('booking/_tab_booking'); ?>
                        </div>
                        <!-- <div class="tab-pane" id="tab-history">
                            <?php $this->load->view('booking/_tab_booking_history'); ?>
                        </div> -->
                        <div class="tab-pane" id="tab-refund">
                            <?php $this->load->view('booking/_tab_booking_refund'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('footer') ?>
<?php $this->load->view('booking/_modal_spd_booking') ?>
<?php echo $modal_chat . $modal_tujuan . $modal_history; ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/travel/spd_global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css"/>
<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/> -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
 <!-- plugin checkbox -->
<script src="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.min.js"></script>

<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"/> -->
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd_global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/spd/spd_booking.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>

<!-- <script src="<?php echo base_url() ?>assets/plugins/devexpress/jszip.min.js"></script> -->
<!-- <script src="<?php echo base_url() ?>assets/plugins/devexpress/jquery.min.js"></script> -->
<!-- <script src="<?php echo base_url() ?>assets/plugins/devexpress/dx.all.js"></script> -->

