<!--
/*
@application  	: Equipment Management
@author     	: Airiza Yuddha (7849)
@contributor  	: 
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
		            	<h3 class="box-title"><strong>Master Jenis Kerusakan </strong></h3>
		          	</div>
					<div class="box-body">
						<table id="maintable" 
							class="table table-bordered table-striped datatable-custom">
							<thead>
								<tr>
									<th>Jenis Kerusakan</th>
									<th>Keterangan</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									// echo json_encode($kerusakan);
					              	foreach($kerusakan as $m){
					              		echo "<tr>";
					              		echo "<td>".$m->kerusakan."</td>";
					              		echo "<td>".$m->keterangan."</td>";
					              		echo "<td>";
						              		if($m->na == 'n'){ 
						                        echo "<span class='label label-success'>ACTIVE</span>";
						                      }
						                      if($m->na == 'y'){
						                        echo "<span class='label label-danger'>NOT ACTIVE</span>";
						                      }
					              		echo "</td>";
					              		echo "<td>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
					                      if($m->na == 'n'){ 
					                        echo "<li><a href='javascript:void(0)' class='edit_kerusakan' data-edit-kerusakan='".$m->id_kerusakan."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
					                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='kerusakan' data-non_active='".$m->id_kerusakan."'><i class='fa fa-times'></i> Non Aktif</a></li>
					                              <li><a href='javascript:void(0)' class='delete' data-tab='kerusakan' data-delete='".$m->id_kerusakan."'><i class='fa fa-trash-o'></i> Hapus</a></li>
					                              <li><a target='_blank' href='".base_url()."asset/master/tipe_merk/".$m->id_kerusakan."'><i class='fa fa-list'></i> List Tipe Merk</a></li>";
					                       
					                      }
					                      if($m->na == 'y'){
					                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='kerusakan' data-set_active='".$m->id_kerusakan."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
				<!--end box-->
            </div>

            <div class="col-sm-4">
		        <div class="box box-success" id="box-add-kerusakan">
					<div class="box-header with-border">
					  	<h3 class="box-title title-form-kerusakan">
					  		<strong>Buat Jenis Kerusakan Baru</strong>
					  	</h3>
					  	<button type="button" class="btn btn-sm btn-default pull-right hidden" 
					  		id="btn-new-kerusakan">Buat Jenis Kerusakan Baru
					  	</button>
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					<form role="form" class="form-master-kerusakan">
						<div class="box-body">
							<div class="form-group">
								<label for="kerusakan">Jenis Kerusakan</label>
								<input type="text" class="form-control" name="kerusakan" id="kerusakan" 
									placeholder="Masukkkan Jenis Kerusakan" required="required">
							</div>
								<div class="form-group">
								<label for="kerusakan_ket">Keterangan</label>
								<textarea class="form-control" name="kerusakan_ket" id="kerusakan_ket" 
									placeholder="Masukan Keterangan" ></textarea>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden" name="id_kerusakan">
							<button type="submit" class="btn btn-success">Submit</button>
						</div>
					</form>
				</div>
		    </div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/kerusakan.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


