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
            <div class="col-sm-8">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Master Jenis Pallet</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="false" data-bautowidth="true" data-pagelength="10">
                            <thead>
                                <th>Berat Bersih (Kg)</th>
                                <th>Jumlah Layer</th>
                                <th>Layer Pertama</th>
                                <th>Show Option</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title title-form">Form Jenis Pallet</h3>
                        <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Tambah Pallet Baru</button>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="form-master" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Berat Bersih</label>
                                <input type="number" class="form-control" name="berat" id="berat" required>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Layer</label>
                                <input type="number" class="form-control" name="jumlah_layer" id="jumlah_layer" required>
                            </div>
                            <div class="form-group">
                                <label>Layer Pertama</label>
                                <input type="number" class="form-control" name="layer_pertama" id="layer_pertama" min="0" required>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="is_show_option" name="is_show_option"> Muncul Sebagai Opsi
                                </label>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input id="id_pallet" name="id_pallet" type="hidden">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/outspec/master/pallet.js?<?php echo time(); ?>"></script>