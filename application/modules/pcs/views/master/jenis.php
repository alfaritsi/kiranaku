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
	          		<div class="box-header">
	            		<h3 class="box-title"><strong>Master <?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Formula</th>
				              	<th>Last Update</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($jenis as $dt){
				              		echo "<tr>";
				              		echo "<td>".$dt->jns_formula."<br>".$dt->label_active."</td>";
				              		echo "<td>".$generate->generateDateTimeFormat($dt->tanggal_edit)."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
				                      if($dt->active == 1){ 
				                        echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_mjenis)."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
				                              <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_mjenis)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
				                      }
				                      if($dt->active == 0){
				                        echo "<li><a href='javascript:void(0)' class='set_active-jenis' data-activate='".$generate->kirana_encrypt($dt->id_mjenis)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
				                      }
				                  echo "    </ul>
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
			<div class="col-sm-6">
				<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title title-form">Buat <?php echo (isset($title_form) ? $title_form : $title); ?></h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat <?php echo (isset($title_form) ? $title_form : $title); ?> Baru</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-formula">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="jns_formula">Formula</label>
		                		<input type="text" class="form-control" name="jns_formula" id="jns_formula" placeholder="Masukkkan Nama Formula" required="required">
		             		</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_mjenis">
		              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pcs/master/jenis.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>