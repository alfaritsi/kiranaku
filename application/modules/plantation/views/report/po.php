<?php $this->load->view('header') ?>

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">Data PPB-PO-TTG</h1>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <div class="input-group input-daterange" id="filter-date">
                                        <input type="text" id="tanggal_awal" name="tanggal_awal" value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>" class="form-control kiranadatepicker" data-autoclose="true" readonly autocomplete="off">
                                        <label class="input-group-addon" for="tanggal-awal">-</label>
                                        <input type="text" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>" class="form-control kiranadatepicker" data-autoclose="true" readonly autocomplete="off">
                                    </div>
                                </div>
                            </div>
			          		<div class="col-sm-2">
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
			          		<div class="col-sm-2">
			            		<div class="form-group">
				                	<label> Jenis PO: </label>
				                	<select class="form-control select2" name="jenis_po" style="width: 100%;" data-placeholder="Pilih Pabrik" data-allowclear="true">
				                  		<option></option>
				                  		<option value="ho">HO</option>
				                  		<option value="site">SITE</option>
				                  	</select>
				            	</div>
			            	</div>
                            <div class="col-sm-5">
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
                                        <th>Pabrik</th>
                                        <th>No PPB</th>
                                        <th>Tanggal Upload</th>
                                        <th>Jenis PO (HO/SITE)</th>
                                        <th>Tanggal Konfirmasi</th>
                                        <th>Kode Barang</th>
                                        <th>Barang</th>
                                        <th>No PO</th>
                                        <th>Tanggal PO</th>
                                        <th>Tanggal MIGO/GR</th>
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/report/po.js?<?php echo time(); ?>"></script>