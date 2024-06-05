<!--
/*
@application  : SPL
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Master Plan Lembur</strong></h3>
                        <div class="btn-group pull-right">
                            <button class="btn btn-sm btn-success" id="btn-upload">Upload</button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form name="filter-data-spk" method="post">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pabrik: </label>
										<select class="form-control select2" multiple="multiple" id="filter_pabrik" name="filter_pabrik[]" style="width: 100%;" data-placeholder="Pilih Pabrik"  required="required">
											<?php
												foreach($pabrik as $dt){
													echo"<option value='".$dt->plant."'>".$dt->plant."</option>";
												}
											?>
										</select>
										
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Bulan: </label>
										<div class="input-group">
											<input type="text" class="form-control" name="filter_bulan" id="filter_bulan" data-placeholder="Pilih Bulan" required="required">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>							
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Unit: </label>
										<select class="form-control select2" multiple="multiple" id="filter_unit" name="filter_unit[]" style="width: 100%;" data-placeholder="Pilih Unit"  required="required">
											<?php
												echo"<option value='UNIT MILLING'>UNIT MILLING</option>";
												echo"<option value='UNIT CRUMBING'>UNIT CRUMBING</option>";
											?>
										</select>
										
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
                                    <th>Departemen</th>
                                    <th>Seksie</th>
                                    <th>Unit</th>
                                    <th>Shift</th>
                                    <th>Jumlah Jam Lembur</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modal_master_plan" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sg" role="document">
        <div class="modal-content">
            <div class="col-sm-12">
                <div class="modal-content">
                    <form role="form" class="form-master-spl-imp" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Form Master Plan Lembur</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="pabrik">Pabrik</label>
								<select class="form-control select2" id="pabrik" name="pabrik" style="width: 100%;" data-placeholder="Pilih Pabrik"  required="required">
									<?php
										echo"<option value=''>Pilih Pabrik</option>";
										foreach($pabrik as $dt){
											echo"<option value='".$dt->plant."'>".$dt->plant."</option>";
										}
									?>
								</select>
                            </div>
                            <div class="form-group">
                                <label for="bulan">Bulan</label>
								<div class="input-group">
									<input type="text" class="form-control" name="bulan_tahun" id="bulan_tahun" required="required">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>							
                            </div>
						   
                            <div class="form-group">
                                <label for="file_excel">Import MPL</label>
								<input type="file" class="form-control" name="file_excel" id="file_excel" required>
                           </div>
                        </div>
                        <div class="modal-footer">
                            <input name="id_barang" type="hidden" value="">
                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-primary" name="action_btn_imp">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spl/master/plan.js?<?php echo time(); ?>"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js" ></script>
