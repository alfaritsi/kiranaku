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
			            	<h3 class="box-title"><strong>Master Jenis Instansi </strong></h3>
			          	</div>
						<div class="box-body">
							<table class="table table-bordered table-striped datatable-custom">
								<thead>
									<tr>
										<th>Jenis Instansi</th>
										<th>Keterangan</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
						              	foreach($jenis_instansi as $jm){
						              		echo "<tr>";
						              		echo "<td>".$jm->nama."</td>";
						              		echo "<td>".$jm->keterangan."</td>";
						              		echo "<td>";
							              		if($jm->na == 'n'){ 
							                        echo "<span class='label label-success'>ACTIVE</span>";
							                      }
							                      if($jm->na == 'y'){
							                        echo "<span class='label label-danger'>NOT ACTIVE</span>";
							                      }
						              		echo "</td>";
						              		echo "<td>
						                          <div class='input-group-btn'>
						                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
						                            <ul class='dropdown-menu pull-right'>";
						                      if($jm->na == 'n'){ 
						                        echo "<li><a href='javascript:void(0)' class='edit_jenis' data-edit-jenis='".$jm->id_jenis_instansi."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
						                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='jenis_instansi' data-non_active='".$jm->id_jenis_instansi."'><i class='fa fa-times'></i> Non Aktif</a></li>
						                              <li><a href='javascript:void(0)' class='delete' data-tab='jenis_instansi' data-delete='".$jm->id_jenis_instansi."'><i class='fa fa-trash-o'></i> Hapus</a></li>
						                              <li><a target='_blank' href='".base_url()."asset/master/instansi/".$jm->id_jenis_instansi."'><i class='fa fa-list'></i> List Instansi</a></li>";
						                       
						                      }
						                      if($jm->na == 'y'){
						                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='jenis_instansi' data-set_active='".$jm->id_jenis_instansi."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			        <div class="box box-success" id="box-add-jenis-instansi">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-jenis_instansi"><strong>Buat Jenis Instansi Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-jenis_instansi">Buat Jenis Instansi Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-jenis-instansi">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="jenis_instansi">Jenis Instansi</label>
			                <input type="text" class="form-control" name="jenis_instansi" id="jenis_instansi" placeholder="Masukkkan Jenis Instansi" required="required">
			              </div>
			              <div class="form-group">
			                <label for="merk">Keterangan</label>
			              	<textarea class="form-control" name="ket_jenis_instansi" id="ket_jenis_instansi" placeholder="Masukan Keterangan" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_jenis_instansi">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			    </div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/instansi.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


