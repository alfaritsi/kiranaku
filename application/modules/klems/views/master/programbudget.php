<!--
/*
test ssh
@application  : KLEMS (Kirana Learning Management System)
@author     : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->
<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-8">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong>Setting <?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Kode Program</th>
				              	<th>Nama Program</th>
								<th>Tahun</th>
								<th>Budget Training</th>
								<th>Budget Traveling</th>
								<th>Terakhir Update</th>
								<th>Status</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$no = 1;
				              	foreach($program_budget as $dt){
				              		$no++;
									echo "<tr>";
				              		echo "<td>".$dt->kode_program."</td>";
				              		echo "<td>".$dt->nama_program."</td>";
				              		echo "<td>".$dt->tahun."</td>";
				              		echo "<td>".$dt->budget_training_cur."</td>";
				              		echo "<td>".$dt->budget_traveling_cur."</td>";
				              		echo "<td>".date_format(date_create($dt->tanggal_edit),"d-m-Y H:i")."</td>";
				              		echo "<td>".$dt->label_active."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$generate->kirana_encrypt($dt->id_program_budget)."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
													  <li><a href='javascript:void(0)' class='delete' data-delete='".$generate->kirana_encrypt($dt->id_program_budget)."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
												}
												if($dt->na == 'y'){
												echo "<li><a href='javascript:void(0)' class='set_active-program_budget' data-activate='".$generate->kirana_encrypt($dt->id_program_budget)."'><i class='fa fa-check'></i> Set Aktif</a></li>";
												}
									echo " 	</ul>
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
		              	<h3 class="box-title title-form">Buat Setting Budget Program</h3>
		              	<button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Setting Budget Program</button>
		          	</div>
		          	<!-- /.box-header -->
		          	<!-- form start -->
		          	<form role="form" class="form-master-program_budget">
	            		<div class="box-body">
							<div class="form-group">		
		                		<label for="kode_program">Kode Program</label>
		                		<input type="text" class="form-control" name="kode_program" id="kode_program" value="<?php if (isset($program))echo $program[0]->kode?>" readonly>
							</div>
							<div class="form-group">		
		                		<label for="nama_program">Nama Program</label>
		                		<input type="text" class="form-control" name="nama_program" id="nama_program" value="<?php if (isset($program))echo $program[0]->nama?>" readonly>
							</div>
		              		<div class="form-group">
								<label for="tahun">Tahun</label>
								<select class="form-control select2" name="tahun" id="tahun" required="required">
									<?php
										echo "<option value='0'>-Silahkan Pilih tahun-</option>";
										$startdate = date("Y", strtotime("-2 year", strtotime(date("Y"))));
										$enddate = date("Y", strtotime("+5 year", strtotime(date("Y"))));
										$years = range ($startdate,$enddate);
										foreach($years as $year){
											echo "<option value='$year'>$year</option>";
										}										
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="budget_training">Budget Training</label>
		                		<input type="text" class="angka form-control"  data-currency="no" min="0" name="budget_training" id="budget_training" placeholder="Masukkan Budget Training" required="required">
							</div>
							<div class="form-group">
								<label for="budget_traveling">Budget Traveling</label>
		                		<input type="text" class="angka form-control"  data-currency="no" min="0"  name="budget_traveling" id="budget_traveling" placeholder="Masukkan Budget Traveling" required="required">
							</div>
		            	</div>
		            	<div class="box-footer">
		             		<input type="hidden" name="id_program_budget">
							<input type="hidden" name="id_program" id="id_program"  value="<?php if (isset($program))echo $program[0]->id_program?>">
							<button type="reset" class="btn btn-danger">Reset</button>
		              		<button type="button" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/klems/master/programbudget.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>