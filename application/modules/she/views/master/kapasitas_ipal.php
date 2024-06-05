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
				                  <th class="text-center">Kapasitas IPAL (M&#179;)</th>
				                  <th></th>
				                </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($kapasitas_ipal as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->pabrik." (".$dt->kode_pabrik.")</td>";
					                  echo "<td align='right'>".number_format($dt->kapasitas_ipal,2,",",".")."</td>";
					                  echo "<td align='center'>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
					                      if($dt->na == null){ 
					                        echo "<li><a href='#' class='edit' data-edit='".$dt->id."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
					                              <li><a href='#' class='delete' data-delete='".$dt->id."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
					                      }
					                      if($dt->na != null){
					                        echo "<li><a href='#' class='set_active-kategori' data-activate='".$dt->id."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			<div class="col-sm-4">
				<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title title-form">Buat <?php echo (isset($title_form) ? $title_form : $title); ?></h3>
		              	<!-- <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat <?php echo (isset($title_form) ? $title_form : $title); ?> Baru</button> -->
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-kapasitas_ipal">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="jns_formula">Pabrik</label>
				                <select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" required>
				                	<option value="" selected> </option>
				                    <?php
				                      foreach ($pabrik as $pabrik) {
				                        echo "<option value='".$pabrik->id_pabrik."'>".$pabrik->nama." (".$pabrik->kode.")</option>";
				                      }
				                    ?>
				                </select>
		             		</div>
		              		<div class="form-group">
		                		<label for="jns_formula">Kapasitas IPAL (M&#179;)</label>
				                <input type="text" name="kapasitas_ipal" id="kapasitas_ipal" style="width:100%;height:32px;padding:10px;'" required>
		             		</div>
		            	</div>
		            	<div class="box-footer">
		            		<input type="hidden" name="id" id="id" style="width:100%">
		              		<button type="Submit" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>

		</div>


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/she/master/kapasitas_ipal.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>