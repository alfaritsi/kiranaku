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
				              	<th>COA</th>
				              	<th>Last Update</th>
				            </thead>
			              	<tbody>
			              		<?php
			              			foreach($list as $l){
                                       $data_COA = explode(",",rtrim($l->COA_list,","));
                                       echo "<tr>";
                                       echo "  <td>".$l->jns_formula;
                                       if($l->norma !== NULL){
                                               echo "<br><button class='btn btn-xs btn-success'>".$l->norma."</button>";
                                       }
                                       echo "  </td>";
                                       echo "  <td>";
                                               foreach($data_COA as $p){
                                                       echo "<button class='btn btn-sm btn-info btn-role'>".$p."</button>";
                                               }
                                       echo "  </td>";
                                       echo "  <td>".$generate->generateDateTimeFormat($l->tanggal_edit)."</td>";
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
		              	<h3 class="box-title title-form"><?php echo (isset($title_form) ? $title_form : $title); ?></h3>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-setting-formcoa">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="formula">Formula</label>
		                		<select class="form-control select2" name="formula" id="formula" required="required">
		                			<?php
		                				echo "<option value='0'>Silahkan pilih grup</option>";
		                				foreach($formula as $dt){
				                			echo "<option value='".$generate->kirana_encrypt($dt->id_mjenis)."'";
				                			echo ">".$dt->jns_formula."</option>";
				                		}
		                			?>
		                		</select>
		             		</div>
		              		<div class="form-group" id="container-norma">
		             		</div>
		              		<div class="form-group">
		                		<label for="coa">COA</label>
		                		<select class="form-control select2" name="coa[]" id="coa" multiple="multiple">
		                		</select>
		             		</div>
		            	</div>
		            	<div class="box-footer">
		              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
		            	</div>
		          	</form>
		        </div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pcs/setting/formcoa.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>