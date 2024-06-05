<!--
/*
@application  : Equipment Management
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
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-sm-8">
                	<div class="box box-success">
			          	<div class="box-header">
			            	<h3 class="box-title"><strong>Master Dokumen</strong></h3>
			          	</div>
						<div class="box-body">
							<table class="table table-bordered table-striped datatable-custom">
								<thead>
									<tr>
										<th>Dokumen</th>
										<th>Periode</th>
										<th>Reminder</th>
										<th>Jenis Kendaraan</th>
										<th>Jenis Instansi</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
						              	foreach($dokumen as $dk){
						              		echo "<tr>";
						              		echo "<td>".$dk->nama."</td>";
						              		echo "<td>".$dk->periode." Bulan</td>";
						              		echo "<td>".$dk->hari." Hari</td>";
						              		echo "<td>".$dk->jenis_kendaraan."</td>";
						              		echo "<td>".$dk->jenis_instansi."</td>";
						              		echo "<td>";
							              		if($dk->na == 'n'){ 
							                        echo "<span class='label label-success'>ACTIVE</span>";
							                      }
							                      if($dk->na == 'y'){
							                        echo "<span class='label label-danger'>NOT ACTIVE</span>";
							                      }
						              		echo "</td>";
						              		echo "<td>
						                          <div class='input-group-btn'>
						                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
						                            <ul class='dropdown-menu pull-right'>";
						                      if($dk->na == 'n'){ 
						                        echo "<li><a href='javascript:void(0)' class='edit_dokumen' data-dokumen='".$dk->id_inv_doc."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
						                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='dokumen' data-non_active='".$dk->id_inv_doc."'><i class='fa fa-times'></i> Non Aktif</a></li>
						                              <li><a href='javascript:void(0)' class='delete' data-tab='dokumen' data-delete='".$dk->id_inv_doc."'><i class='fa fa-trash-o'></i> Hapus</a></li>
						                            ";
						                       
						                      }
						                      if($dk->na == 'y'){
						                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='dokumen' data-set_active='".$dk->id_inv_doc."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			        <div class="box box-success" id="box-add-dokumen">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-dokumen"><strong>Buat Dokumen Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-dokumen">Buat Dokumen Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-dokumen">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="dokumen">Dokumen</label>
			                <input type="text" class="form-control" name="dokumen" id="dokumen" placeholder="Masukkkan nama dokumen" required="required">
			              </div>
			              <div class="form-group">
			                <label for="radio">Expired</label><br>
		                	<label class="radio-inline"><input type="radio" id="radio" name="radio" value="1" checked="checked">Yes</label>
							<label class="radio-inline"><input type="radio" id="radio" name="radio" value="0">No</label>
			              	<input type="hidden" name="expired" id="expired" value="1">
			              </div>
			              <div class="form-group" id='form-periode'>
			                <label for="dokumen">Periode</label>
			                <div class="input-group">
				                <input type="number" class="form-control" name="periode" id="periode" value="0" required="required">
				                <span class="input-group-addon">Bulan</span>
				            </div>
			              </div>
			              <div class="form-group" id='form-reminder'>
			                <label for="dokumen">Reminder</label>
			                <div class="input-group">
			                	<input type="number" class="form-control" name="reminder" id="reminder" value="0" required="required">
				                <span class="input-group-addon">Hari </span>
				            </div>
			              </div>
			              <div class="form-group">
			                <label for="merk">Jenis Kendaraan</label>
			                <select id="jenis_kendaraan" name="jenis_kendaraan[]" multiple="multiple" class="form-control select2 col-sm-12" required="required">
                            	<?php
	                				foreach ($jenis_kendaraan as $jen) {
                                        echo "<option value='$jen->id_jenis'>$jen->nama</option>";
                                    }
	                			?>
                            </select>
			              </div>
			              <div class="form-group">
			                <label for="merk">Jenis Instansi</label>
			                <select id="jenis_instansi" name="jenis_instansi" class="form-control select2 col-sm-12" required="required">
                            	<option value=''>Pilih Jenis Instansi</option>
                            	<?php
	                				foreach ($jenis_instansi as $j) {
                                        echo "<option value='$j->id_jenis_instansi'>$j->nama</option>";
                                    }
	                			?>
                            </select>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_inv_doc">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			    </div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/dokumen.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


