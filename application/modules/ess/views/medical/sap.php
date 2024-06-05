<?php
/**
 * @application  : ESS Medical SAP - View
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

                <div class="nav-tabs-custom tab-success">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active">
                            <a href="#tab-kelengkapan" data-toggle="tab">SAP Medical</a>
                        </li>
                        <li>
                            <a href="#tab-history" data-toggle="tab">History</a>
                        </li>
                        <li class="pull-left header"><?php echo $title; ?></li>
                    </ul>
                    <form name="filter-fbk-sap" method="post" class="pad">
                        <input type="hidden" name="lokasi" value="<?php echo $lokasi ?>" />
                        <div class="row">
                            <div class="col-md-offset-8 col-md-4">
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
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-kelengkapan">
                            <?php echo $tab_sap_medical; ?>
                        </div>
                        <div class="tab-pane" id="tab-history">
                            <?php echo $tab_sap_history; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('_modal_history') ?>
<?php $this->load->view('_modal_detail') ?>
<?php $this->load->view('_modal_kwitansi') ?>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/numeric/autonumeric.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/medical_sap.js"></script>
