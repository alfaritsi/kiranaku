<?php $this->load->view('header') ?>


<div class="content-wrapper">
	<section class="content">
	    <div class="row">
	    	<div class="col-sm-12">
	    		<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title"><strong>Form Order</strong></h3>
		          	</div>


		          	<div class="box-body">
	              		<div class="row">
	              			<div class="col-sm-12">
	              				<div class="table-responsive">
		              				<table class="table table-hover table-form">
					            		<thead>
					            			<th class="text-center">NO</th>
					            			<th class="text-center">Item</th>
					            			<th class="text-center">Qty</th>
					            			<th class="text-center">Request Date</th>
					            			<th class="text-center">Delivery Date</th>
					            			<th class="text-center">Price</th>
					            		</thead>
					            		<tbody>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">1</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321 - Mesin A" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>" readonly="readonly">
					            				</td>
					            				<td class="text-center">
										        	<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            			</tr>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">2</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321 - Mesin A" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>" readonly="readonly">
					            				</td>
					            				<td class="text-center">
										        	<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            			</tr>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">3</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321 - Mesin A" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>" readonly="readonly">
					            				</td>
					            				<td class="text-center">
										        	<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            			</tr>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">4</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321 - Mesin A" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>" readonly="readonly">
					            				</td>
					            				<td class="text-center">
										        	<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            			</tr>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">5</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321 - Mesin A" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>" readonly="readonly">
					            				</td>
					            				<td class="text-center">
										        	<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            			</tr>
					            		</tbody>
					            	</table>
				            	</div>
				            </div>
				        </div>
				        <div class="row pull-right">
		              		<div class="col-sm-12">
								<a href="#" class="btn btn-success" id="btn-submit" style="width: 70px;">Submit</a> 
							</div>
	              		</div>
				    </div><!--end box-body-->


		        </div>	
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>