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
			            	<h3 class="box-title"><strong>Master Periode Detail<br> Periode <?php echo $judul; ?> </strong></h3>
			          	</div>
						<div class="box-body">
							<table class="table table-bordered table-striped datatable-custom">
								<thead>
									<tr>
										<th>Jenis Detail</th>
										<th>Kegiatan</th>
										<th>Keterangan</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
						              	foreach($periode as $i){
						              		echo "<tr>";
						              		echo "<td>".$i->nama."</td>";
						              		echo "<td>".$i->keterangan."</td>";
						              		echo "<td>".$i->keterangan."</td>";
						              		echo "<td>
						                          <div class='input-group-btn'>
						                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
						                            <ul class='dropdown-menu pull-right'>";
						                      if($i->na == 'n'){ 
						                        echo "<li><a href='javascript:void(0)' class='edit_instansi' data-edit-instansi='".$i->id_instansi."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
						                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='merk' data-non_active='".$i->id_instansi."'><i class='fa fa-times'></i> Non Aktif</a></li>
						                              <li><a href='javascript:void(0)' class='delete' data-tab='instansi' data-delete='".$i->id_instansi."'><i class='fa fa-trash-o'></i> Hapus</a></li>
						                              ";
						                       
						                      }
						                      if($i->na == 'y'){
						                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='instansi' data-set_active='".$i->id_instansi."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			        <div class="box box-success" id="box-add-periode">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-periode"><strong>Buat Instansi Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-periode">Buat  Instansi Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-periode">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="jenis_instansi">Jenis Instansi</label>
			                <input type="text" class="form-control" name="jenis_instansi" id="jenis_instansi" value="<?php echo $judul; ?>" readonly="readonly">
			              </div>
			              <div class="form-group">
			                <label for="periode">periode</label>
			                <input type="text" class="form-control" name="periode" id="periode" placeholder="Masukkkan periode" required="required">
			              </div>
			              <div class="form-group">
			                <label for="keterangan">Keterangan</label>
			              	<textarea class="form-control" name="ket_instansi" id="ket_instansi" placeholder="Masukan Keterangan" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_instansi">
			              <input type="hidden" name="id_jenis_instansi" value="<?php echo $id_jenis_instansi; ?>">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			    </div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/pdetail.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


