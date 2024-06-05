<!--
/*
@application  : Outspec Confirmation
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Data Cek Random</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form name="filter-data-spk" method="post">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pabrik: </label>
                                        <select class="form-control select2" multiple="multiple" id="filter_plant" name="filter_plant[]" data-placeholder="Pilih Pabrik">
                                            <?php
                                            foreach ($pabrik as $plant) :
                                                echo "<option value='" . $plant->plant . "'>" . $plant->plant . "</option>";
                                            endforeach;
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Periode Tanggal Pengecekan: </label>
                                        <div class="input-group input-daterange" id="filter-date">
                                            <input type="text" id="filter_tanggal_awal" name="filter_tanggal_awal" class="form-control" readonly autocomplete="off" onkeypress="return false;">
                                            <label class="input-group-addon" for="tanggal-awal">-</label>
                                            <input type="text" id="filter_tanggal_akhir" name="filter_tanggal_akhir" class="form-control" readonly autocomplete="off" onkeypress="return false;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-filter -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="false" data-bautowidth="true" data-pagelength="10">
                            <thead>
                                <tr>
                                    <th>Pabrik</th>
                                    <th>Tanggal</th>
                                    <th>No. SI</th>
                                    <th>Tahun Produksi</th>
                                    <th>No. Produksi</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/outspec/transaksi/random/page.js?<?php echo time(); ?>"></script>