<!--
/*
@application    : SHE 
@author 		: Lukman Hakim (7143)
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
				                  <th class="text-center">Jenis</th>
				                  <th class="text-center">Keterangan</th>
				                  <th></th>
				                </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($jenis as $dt){
										echo "<tr>";
										echo 	"<td>".$dt->jenis."</td>";
										echo 	"<td>".$dt->keterangan."</td>";
										echo 	"<td>
													<div class='input-group-btn'>
														<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
														<ul class='dropdown-menu pull-right'>";
															if($dt->na == null){ 
																echo "<li><a href='#' class='edit' data-edit='".$dt->id."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
																echo "<li><a href='#' class='delete' data-delete='".$dt->id."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
															}
															if($dt->na == 'y'){
																echo "<li><a href='#' class='set_active-kategori' data-activate='".$dt->id."'><i class='fa fa-check'></i> Set Aktif</a></li>";
															}
					                    echo 			"</ul>
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
		          	</div>
		          	<!-- form start -->
		          	<form role="form" class="form-master-mjenis">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="jenis">Jenis</label>
								<input type="text" name="jenis" id="jenis" class="form-control" required>
		             		</div>
		              		<div class="form-group">
		                		<label for="keterangan">Keterangan</label>
								<input type="text" name="keterangan" id="keterangan" class="form-control" required>
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
<script src="<?php echo base_url() ?>assets/apps/js/she/master/mjenis.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>