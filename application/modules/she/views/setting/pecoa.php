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
		<div class="col-sm-6">
    		<div class="box box-success">
          		<div class="box-header">
            		<h3 class="box-title"><strong>Master <?php echo $title; ?></strong></h3>
          		</div>
          		<!-- /.box-header -->
	          	<div class="box-body">
	           		<table class="table table-bordered table-striped my-datatable-extends-order">
	              		<thead>
			              	<th>PE Grup</th>
			              	<th>COA</th>
			              	<th>Last Update</th>
			            </thead>
		              	<tbody>
		              		<?php
		              			foreach($list as $l){
		              				$COA = explode(",",rtrim($l->COA_list,","));
		              				echo "<tr>";
		              				echo "	<td>".$l->nama_grup."</td>";
		              				echo "	<td>";
		              					foreach($COA as $p){
		              						echo "<button class='btn btn-sm btn-info btn-role'>".$p."</button>";
		              					}
		              				echo "	</td>";
		              				echo "	<td>".date_format(date_create($l->tanggal_edit),"d-m-Y H:i")."</td>";
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
	          	<form role="form" class="form-setting-pecoa">
            		<div class="box-body">
	              		<div class="form-group">
	                		<label for="pegrup">PE Grup</label>
	                		<select class="form-control select2" name="pegrup" id="pegrup" required="required">
	                			<?php
	                				echo "<option value='0'>Silahkan pilih grup</option>";
	                				foreach($pegrup as $dt){
			                			echo "<option value='".$dt->id_mpegrup."'";
			                			echo ">".$dt->nama_grup."</option>";
			                		}
	                			?>
	                		</select>
	             		</div>
	              		<div class="form-group">
	                		<label for="coa">COA</label>
	                		<select class="form-control select2" name="coa[]" id="coa" multiple="multiple" required="required">
	                		</select>
	             		</div>
	            	</div>
	            	<div class="box-footer">
	              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
	            	</div>
	          	</form>
	        </div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pcs/setting/pecoa.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>