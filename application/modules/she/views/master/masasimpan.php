<!--
/*
@application    : SHE 
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
			<div class="col-sm-8">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong>Master <?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				                <tr>
				                  <th class="text-center">Pabrik</th>
				                  <th class="text-center">Jenis Limbah</th>
				                  <th class="text-center">Masa Simpan</th>
				                  <th></th>
				                </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($dtmasasimpan as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->pabrik."</td>";
					                  echo "<td>".$dt->jenis_limbah."</td>";
					                  echo "<td align='right'>".$dt->masa_simpan."</td>";
					                  echo "<td align='center'>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
				                        echo "<li><a href='#' class='edit' data-edit='".$dt->id."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
				                        // echo "<li><a href='#' class='delete' data-delete='".$dt->id."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
											  
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
			<div class="col-sm-4">
				<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title title-form">Edit <?php echo (isset($title_form) ? $title_form : $title); ?></h3>
		              	<!-- <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat <?php echo (isset($title_form) ? $title_form : $title); ?> Baru</button> -->
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-masasimpan">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="jns_formula">Pabrik</label>
				                <select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" disabled>
				                	<option value="" selected> </option>
				                    <?php
				                      foreach ($pabrik as $pabrik) {
				                        echo "<option value='".$pabrik->id_pabrik."'>".$pabrik->nama."</option>";
				                      }
				                    ?>
				                </select>
		             		</div>
		              		<div class="form-group">
		                		<label for="jns_limbah">Jenis Limbah</label>
				                <input type="text" name="jenis_limbah" id="jenis_limbah" class="form-control" readonly>
		             		</div>
		              		<div class="form-group">
		                		<label for="jns_formula">Masa Simpan</label>
				                <input type="text" name="masasimpan" id="masasimpan" class="form-control" required>
		             		</div>
		            	</div>
		            	<div class="box-footer">
		            		<input type="hidden" name="id" id="id">
		              		<button type="Submit" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>

		</div>


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/she/master/masasimpan.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>