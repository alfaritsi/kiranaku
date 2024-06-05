<?php
/**
 * @application  : ESS Cuti & Ijin - Saldo Cuti View
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
                            <a href="#tab-proses" data-toggle="tab">Proses SAP</a>
                        </li>
                        <li>
                            <a href="#tab-history" data-toggle="tab">History</a>
                        </li>
                        <li class="pull-left header"><?php echo $title; ?></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-proses">
                            <?php echo $tab_proses?>
                        </div>
                        <div class="tab-pane" id="tab-history">
                            <?php echo $tab_history; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('_modal_detail') ?>
<?php $this->load->view('_modal_history') ?>
<?php $this->load->view('_modal_pembatalan') ?>
<?php $this->load->view('footer') ?>
<script>
    let ho = '<?php echo base64_decode($this->session->userdata('-ho-'));?>';
</script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/cuti_sap_ho.js"></script>
