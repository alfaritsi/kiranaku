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
			            	<h3 class="box-title"><strong>Master Merk & Tipe </strong></h3>
			          	</div>
						<div class="box-body">
							<table class="table table-bordered table-striped datatable-customs">
								<thead>
									<tr>
										<th>Sub Kategori Asset</th>
										<th>Merk</th>
										<th>Keterangan</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
						              	foreach($merk as $m){
						              		echo "<tr>";
						              		echo "<td>".$m->jenis_asset."</td>";
						              		echo "<td>".$m->nama."</td>";
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
						                        echo "<li><a href='javascript:void(0)' class='edit_merk' data-edit-merk='".$m->id_merk."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
						                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='merk' data-non_active='".$m->id_merk."'><i class='fa fa-times'></i> Non Aktif</a></li>
						                              <li><a href='javascript:void(0)' class='delete' data-tab='merk' data-delete='".$m->id_merk."'><i class='fa fa-trash-o'></i> Hapus</a></li>
						                              <li><a target='_blank' href='".base_url()."asset/master/tipe_merk/".$m->id_merk."'><i class='fa fa-list'></i> List Tipe Merk</a></li>";
						                       
						                      }
						                      if($m->na == 'y'){
						                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='merk' data-set_active='".$m->id_merk."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			        <div class="box box-success" id="box-add-merk">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-merk"><strong>Buat Merk Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-merk">Buat Merk Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-merk">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="merk">Sub Kategori Asset</label>
			                <select id="jenis_asset" name="jenis_asset" class="form-control select2 col-sm-12">
                            	<option value='0'>Pilih Sub Kategori Asset</option>
                            	<?php
	                				foreach ($jenis as $jen) {
                                        echo "<option value='$jen->id_jenis'>$jen->nama</option>";
                                    }
	                			?>
                            </select>
			              </div>
			              <div class="form-group">
			                <label for="merk">Merk</label>
			                <input type="text" class="form-control" name="merk" id="merk" placeholder="Masukkkan Merk" required="required">
			              </div>
			              <div class="form-group">
			                <label for="merk">Keterangan</label>
			              	<textarea class="form-control" name="ket_merk" id="ket_merk" placeholder="Masukan Keterangan" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_merk">
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


