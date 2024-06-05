<?php
$this->load->view('header')
?>
<!--devexpress-->
<!--<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.spa.css">-->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.common.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/devexpress/dx.light.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<style type="text/css">
    .text-table-center th {
        vertical-align: middle !important;
    }

    #gridContainer {
        height: 368px;
    }
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Periode Perjanjian: </label>
                                    <div class="input-group input-daterange">
                                        <input type="text" id="tanggal_perjanjian_awal" class="form-control datePicker" placeholder="dd.mm.yyyy" value="<?php echo '01.' . date('m.Y'); ?>" autocomplete="off" onkeypress="return false;">
                                        <label class="input-group-addon" for="tanggal-awal">-</label>
                                        <input type="text" id="tanggal_perjanjian_akhir" class="form-control datePicker" placeholder="dd.mm.yyyy" value="<?php echo date('d.m.Y'); ?>" autocomplete="off" onkeypress="return false;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Pabrik: </label>
                                    <select class="form-control select2" multiple="multiple" id="filter_plant" name="filter_plant[]" data-placeholder="Pilih Pabrik">
                                        <?php
                                        $arr_plant    = (empty($_POST['akses_plant'])) ? NULL : $_POST['akses_plant'];
                                        foreach ($akses_plant as $plant) :
                                            echo "<option value='" . $plant . "'>" . $plant . "</option>";
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="gridContainer"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spk/report_sla.js?<?php echo time(); ?>"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
<!--devexpress-->
<script src="<?php echo base_url() ?>assets/plugins/devexpress/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/devexpress/dx.all.js"></script>
<style type="text/css">
    #add_modal .dataTables_scroll div,
    #add_modal .dataTables_scroll div table {
        width: 100% !important;
    }

    .form-control[disabled],
    .form-control[readonly],
    fieldset[disabled] .form-control {
        background-color: #fff;
    }

    .select2-container--default.select2-container--disabled .select2-selection--single,
    .select2-container--default.select2-container--disabled .select2-selection--multiple {
        background-color: #fff;
    }

    button[name='action_btn'],
    .box-header .btn {
        margin: 5px 5px 5px 0px !important;
    }
</style>