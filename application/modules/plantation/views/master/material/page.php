<?php $this->load->view('header') ?>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">Kode Material</h1>
                    </div>
                    <div class="box-body">
                        <div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
				                	<select class="form-control select2" multiple="multiple" id="pabrik_filter" name="pabrik_filter[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
											if(!empty($pabrik)){
												// $arr_pabrik = explode(",", $pabrik);
												foreach ($pabrik as $pabrik) {
													if($pabrik!=''){
														echo "<option value='$pabrik'>$pabrik</option>";
													}
												}
											}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
                            <div class="col-sm-9">
                                <div class="form-group" style="float: right;">
                                    <label>&nbsp;</label>
                                    <div class="input-group" id="action-button-datatable"></div>
                                </div>
                            </div>
		            	</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
                                    <thead>
                                        <th>Plant</th>
                                        <th>Kode</th>
                                        <th>Deskripsi 1</th>
                                        <th>Deskripsi 2</th>
                                        <th>Stok</th>
                                        <th>Satuan</th>
                                        <th>LGORT</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/master/kodematerial.js?<?php echo time(); ?>"></script>