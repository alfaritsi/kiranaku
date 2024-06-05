<?php
/**
 * @application  : ESS Cuti & Ijin - View
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
                            <a href="#tab-pengajuan-cuti" data-toggle="tab">Pengajuan Cuti</a>
                        </li>
                        <li>
                            <a href="#tab-pengajuan-ijin" data-toggle="tab">Pengajuan Ijin</a>
                        </li>
                        <li>
                            <a href="#tab-history" data-toggle="tab">History Transaction</a>
                        </li>
                        <li>
                            <a href="#tab-saldo" data-toggle="tab">Saldo Cuti</a>
                        </li>
                        <li>
                            <a href="#tab-info" data-toggle="tab">Info Ijin</a>
                        </li>
                        <li class="pull-left header"><?php echo $title; ?></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-pengajuan-cuti">
                            <?php echo $tab_pengajuan_cuti; ?>
                        </div>
                        <div class="tab-pane" id="tab-pengajuan-ijin">
                            <?php echo $tab_pengajuan_ijin; ?>
                        </div>
                        <div class="tab-pane" id="tab-history">
                            <?php echo $tab_history; ?>
                        </div>
                        <div class="tab-pane" id="tab-saldo">
                            <?php echo $tab_saldo; ?>
                        </div>
                        <div class="tab-pane" id="tab-info">
                            <?php echo $tab_info; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('_modal_cuti') ?>
<?php $this->load->view('_modal_detail') ?>
<?php $this->load->view('_modal_history') ?>
<?php $this->load->view('footer') ?>
<script>
    let ho = '<?php echo base64_decode($this->session->userdata('-ho-'));?>';
    let tanggal_libur = <?php echo json_encode($tanggal_libur)?>;
    let tanggal_merah = <?php echo json_encode($tanggal_merah)?>;
    let tanggal_cuti = <?php echo json_encode($tanggal_cuti)?>;
    let tanggal_cuti_edit = [];
    let tanggal_dinas = <?php echo json_encode($tanggal_dinas)?>;
    let sakit_w_surat = '<?php echo ESS_CUTI_JENIS_SAKIT_W_SURAT?>';
    let sakit_t_surat = '<?php echo ESS_CUTI_SAKIT_T_SURAT?>';
</script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/ess-global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/cuti_ijin.js"></script>
