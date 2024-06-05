<!--
/*
@application    : Attachment Accounting 
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<style type="text/css">
  .listframe { border:1px solid #ccc; padding: 4px; width:100%; min-height: 50px; overflow-y: }
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
			            	

	            		<div class="clearfix" style="margin-bottom: 30px;"></div>


					    <form method="POST" id="filterform" class="filter-transaction-upload-sync" role="form">
			              	<div class="col-md-12" style="padding-left:0px;">
				              	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Plant</label>
					                  <select data-placeholder="Pilih Plant" name="filterpabrik" id="filterpabrik" action="<?php echo base_url() ?>accounting/transaction/upload/sync" class="form-control select2" style="width: 100%;" required>
					                    <option value=""></option>
					                    <?php		
					                      foreach ($pabrik as $pabrik) {
					                      	if($pabrik->kode == $filterpabrik){
					                      		$selected = "selected";
					                      	}else{
					                      		$selected = "";
					                      	}
						                    echo "<option value='".$pabrik->kode."' ".$selected.">".$pabrik->nama."</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>From</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
						                  <input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" id="filterfrom" name="filterfrom" value="<?php echo $filterfrom; ?>" readonly required>
					                  </div>
					                </div>
				            	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>To</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
						                  <input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" id="filterto" name="filterto" value="<?php echo $filterto; ?>" readonly required>
					                  </div>
					                </div>
				            	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Account No.</label>
						              <input type="text" class="form-control" style="padding: 10px;" id="filteraccount" name="filteraccount" value="<?php echo $filteraccount; ?>" autocomplete="off">
					                </div>
				            	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Doc No.</label>
					                  <input type="text" class="form-control" style="padding: 10px;" id="filterdoc" name="filterdoc" value="<?php echo $filterdoc; ?>" autocomplete="off">
					                </div>
				            	</div>
				            	<div class="col-md-1">
					                <div class="form-group" style="margin-top: 25px;">
					                	<button type="submit" name="view_btn" class="btn btn-default"> <i class="fa fa-refresh"></i> Sync </button>
					                	<!-- <a href="" class="btn btn-primary" name="sync" value="Sync"> <i class="fa fa-upload"></i> Synchronize </a> -->
					                </div>
				            	</div>
							</div>
					    </form>
			            
	          		</div>
	          		<!-- /.box-header -->

		          	<div class="box-body">
			        	<legend style="font-size: 14px;"><label> List Data </label></legend>
		           		<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
						        <tr>
									<th class='text-center'>No</th>          
									<th class='text-center'>Doc. Number</th>          
									<th class='text-center'>Header Text</th>          
									<th class='text-center'>Reference</th>          
									<th class='text-center'>G/L Account</th>          
									<th class='text-center'>Create Date</th>          
									<th class='text-center'>Journal Date</th>          
						        </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($jurnal as $key => $dt){
					                  $no = $key + 1;
					                  echo "<tr>";
						              echo "<td align='center'>".$no."</td>";
						              echo "<td align='center'>".$dt->no_doc."</td>";
						              echo "<td>".$dt->text."</td>";
						              echo "<td>".$dt->reff."</td>";
						              echo "<td>".$dt->account."</td>";
						              $uploaddate = empty($dt->upload_date)?'':$this->generate->generateDateFormat($dt->upload_date);
						              echo "<td align='center'>".$uploaddate."</td>";
						              echo "<td align='center'>".$this->generate->generateDateFormat($dt->in_datefirst)."</td>";
					                  echo "</tr>";
					                }
				                ?>
			              	</tbody>
			            </table>
			        </div>
				</div>
			</div>
		</div>

	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/accounting/transaction/upload_sync.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>
