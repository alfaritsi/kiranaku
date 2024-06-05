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

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-9">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong>Master <?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				                <tr>
				                  <th class="text-center">Type User</th>
				                  <th class="text-center">NIK</th>
				                  <th class="text-center">Nama</th>
				                  <th class="text-center">Pabrik</th>
				                  <th width="1px"></th>
				                </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($dt_user_akses as $key => $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->tipe."</td>";
					                  echo "<td>".$dt->nik."</td>";
					                  echo "<td>".$dt->nama."</td>";
					                  echo "<td>".$dt->pabrik."</td>";
					                  echo "<td>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
					                      if($dt->aktif == 1){ 
					                        echo "<li><a href='#' class='edit' data-edit='".$dt->nik."'><i class='fa fa-pencil-square-o'></i> Set Access</a></li>";
					                      }
					                      if($dt->aktif == 1){
					                        echo "<li><a href='#' class='delete' data-delete='".$dt->nik."'><i class='fa fa-check'></i> Set Not Active</a></li>";
					                      }else{
					                        // echo "<li><a href='#' class='set_active' data-activate='".$dt->nik."'><i class='fa fa-check'></i> Set Active</a></li>";
					                      }
					                      echo "</ul>
					                          </div>
					                        </td>";
					                  echo "</tr>";
					                }
				                ?>
			              	</tbody>
			            </table>
			        </div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title title-form">Buat Akses User <?php echo (isset($title_form) ? $title_form : $title); ?></h3>
		              	<!-- <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat <?php echo (isset($title_form) ? $title_form : $title); ?> Baru</button> -->
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-user">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="jns_formula">Type</label>
				                <input type="text" name="tipe" id="tipe" class="form-control" readonly required>
		             		</div>
		              		<div class="form-group">
		                		<label for="jns_formula">NIK</label>
				                <select data-placeholder="Pilih NIK" name="nik" id="nik" class="form-control select2" style="width: 100%;" required>
				                    <option value=""></option>
				                    <?php
				                      foreach ($dt_user as $nik) {
				                        echo "<option value='".$nik->nik."'>".$nik->nama." (".$nik->nik.")</option>";
				                      }
				                    ?>
				                </select>
		             		</div>
		              		<div class="form-group">
		                		<label for="jns_formula">Pabrik</label>
				                <select data-placeholder="Silahkan pilih" name="pabrik[]" id="pabrik" class="form-control" multiple style="width: 100%;" required>
				                    <?php
				                      foreach ($pabrik as $pabrik) {
				                        echo "<option value='".$pabrik->kode."'>".$pabrik->kode."</option>";
				                      }
				                    ?>
				                </select>
		             		</div>
		            	</div>
		            	<div class="box-footer">
		              		<button type="Submit" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>

		</div>


	</section>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.custom.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.theme.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/prettify.css"/>

<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/prettify.js"></script>

<script src="<?php echo base_url() ?>assets/apps/js/accounting/setting/user_akses.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>