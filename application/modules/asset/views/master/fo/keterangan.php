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
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li class="pull-left header"><strong>Master Kegiatan, Service, dan Satuan</strong></li>
							<li><a href="#satuan" id="tab3" data-toggle="tab">Satuan</a></li>
							<li><a href="#service" id="tab2" data-toggle="tab">Service</a></li>
							<li class="active"><a href="#kegiatan" id="tab1" data-toggle="tab">Kegiatan</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="kegiatan">
								<!--lokasi-->
								<div class="box-body">
									<table class="table table-bordered table-striped datatable-custom">
										<thead>
											<tr>
												<th>Kegiatan</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
								              	foreach($kegiatan as $k){
								              		echo "<tr>";
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
								                        echo "<li><a href='javascript:void(0)' class='edit_kegiatan' data-edit-kegiatan='".$k->id_kegiatan."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
								                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='kegiatan' data-non_active='".$k->id_kegiatan."'><i class='fa fa-times'></i> Non Aktif</a></li>
								                              <li><a href='javascript:void(0)' class='delete' data-tab='kegiatan' data-delete='".$k->id_kegiatan."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
								                       
								                      }
								                      if($k->na == 'y'){
								                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='kegiatan' data-set_active='".$k->id_kegiatan."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
								<!--end lokasi-->
                            </div>
                            <div class="tab-pane" id="service">
								<!--begin sub lokasi-->
								<div class="box-body">
									<table class="table table-bordered table-striped datatable-custom">
										<thead>
											<tr>
												<th>Service</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
								              	foreach($service as $s){
								              		echo "<tr>";
								              		echo "<td>".$s->nama."</td>";
								              		echo "<td>".$s->keterangan."</td>";
								              		echo "<td>";
									              		if($s->na == 'n'){ 
									                        echo "<span class='label label-success'>ACTIVE</span>";
									                      }
									                      if($s->na == 'y'){
									                        echo "<span class='label label-danger'>NOT ACTIVE</span>";
									                      }
								              		echo "</td>";
								              		echo "<td>
								                          <div class='input-group-btn'>
								                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
								                            <ul class='dropdown-menu pull-right'>";
								                      if($s->na == 'n'){ 
								                        echo "<li><a href='javascript:void(0)' class='edit_service' data-edit-service='".$s->id_service."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
								                        	<li><a href='javascript:void(0)' class='non_active' data-tab='service' data-non_active='".$s->id_service."'><i class='fa fa-times'></i> Non Aktif</a></li>
								                            <li><a href='javascript:void(0)' class='delete' data-tab='service' data-delete='".$s->id_service."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
								                      }
								                      if($s->na == 'y'){
								                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='service' data-set_active='".$s->id_service."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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

                            <div class="tab-pane" id="satuan">
								<!--begin sub lokasi-->
								<div class="box-body">
									<table class="table table-bordered table-striped datatable-custom">
										<thead>
											<tr>
												<th>Satuan</th>
												<th>Keterangan</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
								              	foreach($satuan as $sat){
								              		echo "<tr>";
								              		echo "<td>".$sat->nama."</td>";
								              		echo "<td>".$sat->keterangan."</td>";
								              		echo "<td>";
									              		if($sat->na == 'n'){ 
									                        echo "<span class='label label-success'>ACTIVE</span>";
									                      }
									                      if($sat->na == 'y'){
									                        echo "<span class='label label-danger'>NOT ACTIVE</span>";
									                      }
								              		echo "</td>";
								              		echo "<td>
								                          <div class='input-group-btn'>
								                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
								                            <ul class='dropdown-menu pull-right'>";
								                      if($sat->na == 'n'){ 
								                        echo "<li><a href='javascript:void(0)' class='edit_satuan' data-edit-satuan='".$sat->id_satuan."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
								                        	<li><a href='javascript:void(0)' class='non_active' data-tab='satuan' data-non_active='".$sat->id_satuan."'><i class='fa fa-times'></i> Non Aktif</a></li>
								                            <li><a href='javascript:void(0)' class='delete' data-tab='satuan' data-delete='".$sat->id_satuan."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
								                      }
								                      if($sat->na == 'y'){
								                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='satuan' data-set_active='".$sat->id_satuan."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
								<!--end sub lokasi-->
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
			        <div class="box box-success" id="box-add-kegiatan">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-kegiatan"><strong>Buat Kegiatan Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-kegiatan">Buat Kegiatan Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-kegiatan">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="kegiatan">Kegiatan</label>
			                <input type="text" class="form-control" name="kegiatan" id="kegiatan" placeholder="Masukkkan Nama Kegiatan" required="required">
			              </div>
			              <div class="form-group">
			                <label for="kegiatan">Keterangan</label>
			              	<textarea class="form-control" name="ket_kegiatan" id="ket_kegiatan" placeholder="Masukan Keterangan Kegiatan" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_kegiatan">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>


			        <div class="box box-success hidden" id="box-add-service">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-service"><strong>Buat Service Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-service">Buat Service Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-service">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="Service">Service</label>
			                <input type="text" class="form-control" name="service" id="service" placeholder="Masukkkan Nama Service" required="required">
			              </div>
			              <div class="form-group">
			                <label for="Service">Keterangan</label>
			              	<textarea class="form-control" name="ket_service" id="ket_service" placeholder="Masukan Keterangan Service" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_service">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			        <div class="box box-success hidden" id="box-add-satuan">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-satuan"><strong>Buat Satuan Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-satuan">Buat Satuan Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-satuan">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="satuan">Satuan</label>
			                <input type="text" class="form-control" name="satuan" id="satuan" placeholder="Masukkkan Satuan" required="required">
			              </div>
			              <div class="form-group">
			                <label for="satuan">Keterangan</label>
			              	<textarea class="form-control" name="ket_satuan" id="ket_satuan" placeholder="Masukan Keterangan Satuan" required="required"></textarea>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_satuan">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			       

			    </div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/keterangan.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


