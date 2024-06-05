<?php
/**
 * @application  : Nusira Workshop
 * @author       : Octe Reviyanto N (8731)
 * @contributor  :
 * 1. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * 2. <insert your fullname> (<insert your nik>) <insert the date>
 * <insert what you have modified>
 * etc.
 */
?>

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
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
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label> Plant: </label>
                                    <select class="form-control select2" multiple="multiple" id="plant_filter"
                                            name="plant[]" style="width: 100%;" data-placeholder="Filter Plant">
                                        <?php
                                        foreach ($pabrik as $dt) {
                                            echo "<option value='" . $dt->plant . "'>" . $dt->plant . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Entry Status: </label>
                                    <select class="form-control select2" multiple="multiple" id="status_filter" name="status[]" style="width: 100%;" data-placeholder="Filter Status">
                                        <?php
                                        echo "<option value='1'>Complete</option>";
                                        echo "<option value='0' selected>Waiting</option>";
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-filter -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped"
                               id="sspTable">
                            <thead>
                            <tr>
                                <th>Plant</th>
                                <th>Nomor PI</th>
                                <!--<th>Tujuan</th>-->
                                <th>Perihal</th>
                                <th>Tanggal</th>
                                <th>Status PI</th>
                                <th>Entry Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <!--modal edit-->
            <div class="modal fade" id="confirm_modal" data-backdrop="static" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <form role="form" class="form-transaksi-confirmaset">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Form <?php echo $title_form; ?></h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <label for="plant">Pabrik Pemesan</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <input type="text" class="form-control" name="plant" id="plant"
                                                   placeholder="Plant" required="required" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <label for="nomor_pi">Nomor PI</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <input type="text" class="form-control" name="nomor_pi"
                                                   id="nomor_pi" placeholder="Nomor PI" required="required"
                                                   disabled>
                                        </div>
                                        <div class="col-xs-2">
                                            <label for="tanggal_pi">Tanggal PI</label>
                                        </div>
                                        <div class="col-xs-3">
                                            <input type="text" class="form-control" name="tanggal_pi"
                                                   id="tanggal_pi" placeholder="Tanggal PI" required="required"
                                                   disabled>
                                        </div>
                                    </div>
                                </div>
                                <div id='show_detail'>
                                    <table id="table-detail-assets" class='table table-bordered my-datatable-extends'>
                                        <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Material</th>
                                            <th width="5%">Order Qty</th>
                                            <th>Acc Assign</th>
                                            <th>Asset Class</th>
                                            <th>Cost Center</th>
                                            <th>Asset Desc.</th>
                                            <th>G/L Account</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="template hide">
                                            <input type='hidden' name='no' class="no" disabled/>
                                            <td class="mat_no">1</td>
                                            <td class="mat_name">Material</td>
                                            <td class="order_qty text-right">10</td>
                                            <td>
                                                <div class="form-group">
                                                    <div>
                                                        <select name="acc_assign" class="s2m acc_assign"
                                                                required disabled>
                                                            <option value="A">(A) Asset</option>
                                                            <option value="K">(K) Expense</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <div>
                                                        <select name="asset_class" class="s2m asset_class"
                                                                required disabled data-placeholder="Pilih Asset Class">
                                                            <option></option>
                                                            <?php foreach ($asset_classes as $asset_class) : ?>
                                                                	<option value="<?php echo $asset_class->ANLKL ?>"><?php echo '('.$asset_class->ANLKL.') '.$asset_class->TXK20 ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <div>
                                                        <select name="cost_center" class="s2m cost_center"
                                                                required disabled data-placeholder="Pilih Cost Center">
                                                            <option></option>
                                                            <?php foreach ($cost_centers as $cost_center) : ?>
                                                                	<option data-plant="<?php echo $cost_center->GSBER ?>" value="<?php echo $cost_center->KOSTL ?>"><?php echo '('.$cost_center->KOSTL.') '.$cost_center->KTEXT ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <div>
                                                        <input name="asset_desc" class="form-control asset_desc form-control-inline"
                                                               type="text" required
                                                               disabled placeholder="Ketik deskripsi aset">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <div>

                                                        <select name="gl_account" class="s2m gl_account"
                                                                required disabled data-placeholder="Pilih GL Account">
                                                            <option></option>
                                                            <?php foreach ($gl_accounts as $gl_account) : ?>
                                                                	<option value="<?php echo $gl_account->SAKNR ?>"><?php echo '('.$gl_account->SAKNR.') '.$gl_account->TXT50 ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input id="plant" name="plant" type="hidden">
                                <input id="no_pi" name="no_pi" type="hidden">
                                <button id="btn_save" type="button" class="btn btn-primary" name="action_btn">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/transaksi/confirmaset.js"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>

<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>

<style>
    .small-box .icon {
        top: -13px;
    }
    .form-control-inline {
        min-width: 0;
        width: auto;
        display: inline;
    }
</style>
