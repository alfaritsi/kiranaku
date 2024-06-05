<?php $this->load->view('header') ?>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">List IO</h1>
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
		            	</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
                                    <thead>
                                        <th>Pabrik</th>
                                        <th>No IO</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/master/io.js"></script>