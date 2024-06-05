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
				            <h3 class="box-title"><strong>Master Tipe Merk - <?php echo $judul; ?></strong></h3>
				        </div>
						<div class="box-body">
							<table class="table table-bordered table-striped datatable-custom">
								<thead>
									<tr>
										<th>Tipe Merk</th>
										<th>Keterangan</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
						              	foreach($tipe_merk as $t){
						              		echo "<tr>";
						              		echo "<td>".$t->nama."</td>";
						              		echo "<td>".$t->keterangan."</td>";
						              		echo "<td>";
							              		if($t->na == 'n'){ 
							                        echo "<span class='label label-success'>ACTIVE</span>";
							                      }
							                      if($t->na == 'y'){
							                        echo "<span class='label label-danger'>NOT ACTIVE</span>";
							                      }
						              		echo "</td>";
						              		echo "<td>
						                          <div class='input-group-btn'>
						                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
						                            <ul class='dropdown-menu pull-right'>";
						                      if($t->na == 'n'){ 
						                        echo "<li><a href='javascript:void(0)' class='edit_tipe' data-edit-tipe='".$t->id_merk_tipe."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
						                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='tipe' data-non_active='".$t->id_merk_tipe."'><i class='fa fa-times'></i> Non Aktif</a></li>
						                              <li><a href='javascript:void(0)' class='delete' data-tab='tipe' data-delete='".$t->id_merk_tipe."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
						                      }
						                      if($t->na == 'y'){
						                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='tipe' data-set_active='".$t->id_merk_tipe."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			        <div class="box box-success" id="box-add-tipe">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-tipe"><strong>Buat Tipe Merk Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-tipe">Buat Tipe Merk Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-tipe">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="merk">Merk</label>
			                <input type="text" class="form-control" name="merks" id="merks" value="<?php echo $judul; ?>" readonly="readonly">
			              </div>
			              <div class="form-group">
			                <label for="merk">Tipe Merk</label>
			                <input type="text" class="form-control" name="tipe_merk" id="tipe_merk" required="required" placeholder="Masukkkan Tipe Merk">
			              </div>
			              <div class="form-group">
			                <label for="merk">Keterangan</label>
			              	<textarea class="form-control" name="ket_tipe" id="ket_tipe" placeholder="Masukan Keterangan" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_tipe">
			              <input type="hidden" name="id_merk" value="<?php echo $id_merk; ?>">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			    </div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/merk.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


