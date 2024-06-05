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
				            <h3 class="box-title"><strong>Master Komponen Jenis - <?php echo $judul; ?></strong></h3>
				        </div>
						<div class="box-body">
							<table class="table table-bordered table-striped datatable-custom">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Komponen</th>
										<th>Keterangan</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
						              	foreach($komponen as $k){
						              		echo "<tr>";
						              		echo "<td>".$k->kode."</td>";
						              		echo "<td>".$k->nama."</td>";
						              		echo "<td>".$k->keterangan."</td>";
						              		echo "<td>";
							              		if($k->na == 'n'){ 
							                        echo "<span class='label label-success'>ACTIVE</span>";
							                      }
							                      if($k->na == 'y'){
							                        echo "<span class='label label-danger'>NOT ACTIVE</span>";
							                      }
						              		echo "</td>";
						              		echo "<td>
						                          <div class='input-group-btn'>
						                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
						                            <ul class='dropdown-menu pull-right'>";
						                      if($k->na == 'n'){ 
						                        echo "<li><a href='javascript:void(0)' class='edit_komponen' data-edit-komponen='".$k->id_jenis_detail."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
						                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='komponen' data-non_active='".$k->id_jenis_detail."'><i class='fa fa-times'></i> Non Aktif</a></li>
						                              <li><a href='javascript:void(0)' class='delete' data-tab='komponen' data-delete='".$k->id_jenis_detail."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
						                      }
						                      if($k->na == 'y'){
						                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='komponen' data-set_active='".$k->id_jenis_detail."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			        <div class="box box-success" id="box-add-komponen">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-komponen"><strong>Buat Komponen Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-komponen">Buat Komponen Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-komponen">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="merk">Kategori</label>
			                <input type="text" class="form-control" name="kategori_komponen" id="kategori_komponen" value="<?php echo $kategori ; ?>" readonly="readonly">
			              </div>
			              <div class="form-group">
			                <label for="merk">Jenis</label>
			                <input type="text" class="form-control" name="jenis_komponen" id="jenis_komponen" value="<?php echo $judul ; ?>" readonly="readonly">
			              </div>
			              <div class="form-group">
			                <label for="merk">Komponen</label>
			                <input type="text" class="form-control" name="jenis_detail" id="jenis_detail" required="required" placeholder="Masukkkan Komponen">
			              </div>
			              <div class="form-group">
			                <label for="merk">Keterangan</label>
			              	<textarea class="form-control" name="ket_komponen" id="ket_komponen" placeholder="Masukan Keterangan" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_jenis_detail">
			              <input type="hidden" name="id_jenis" value="<?php echo $id_jenis; ?>">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			    </div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/kategori.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


