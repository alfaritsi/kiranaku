<!--
/*
@application    : PCS (Production Cost Simulation)
@author 		: Akhmad Syaiful Yamang (8347)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<?php $this->load->view('header') ?>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-6">
				<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title title-form"><?php echo (isset($title_form) ? $title_form : $title); ?></h3>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<div class="box-body">
		          		<?php
		          			if(count($setting) > 0){
		          				$setting_aktif = $setting->value." ".$setting->param." ke belakang";
		          			}else{
		          				$setting_aktif = "YTD";
		          			}
		          		?>
		          		<h5>Setting yang sedang aktif : <strong><?php echo $setting_aktif; ?></strong></h5>
		          	</div>
		          	<form role="form" class="form-setting-historybackward">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="param">Setting</label>
		                		<select class="form-control select2" name="param" id="param" required="required">
		                			<option value="0">Silahkan pilih</option>
		                			<option value="ytd">YTD</option>
		                			<option value="bulan">Bulan</option>
		                		</select>
		             		</div>
		              		<div class="form-group" id="container-value">
		             		</div>
		            	</div>
		            	<div class="box-footer">
		              		<button type="button" name="action_btn" class="btn btn-success">Set</button>
		            	</div>
		          	</form>
		        </div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pcs/setting/historybackward.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>