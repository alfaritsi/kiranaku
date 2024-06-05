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
			            	<h3 class="box-title"><strong>Master Biaya</strong></h3>
			          	</div>
						<div class="box-body">
							<table class="table table-bordered table-striped datatable-custom">
								<thead>
									<tr>
										<th>Kode SAP</th>
										<th>Biaya</th>
										<th>Input KM</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
						              	foreach($biaya as $dk){
						              		echo "<tr>";
						              		echo "<td>".$dk->kode_sap."</td>";
						              		echo "<td>".$dk->nama." Bulan</td>";
						              		echo "<td>";
							              		if($dk->km == 'y'){ 
							                        echo "<span class='label label-success'><i class=' fa fa-check-square'></i> YES</span>";
							                      }
						                        if($dk->km == 'n'){
						                            echo "<span class='label label-danger'><i class=' fa fa-minus-square'></i> NO</span>";
						                        }
						              		echo "</td>";
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
						                        echo "<li><a href='javascript:void(0)' class='edit_biaya' data-biaya='".$dk->id_inv_biaya."'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
						                        	  <li><a href='javascript:void(0)' class='non_active' data-tab='biaya' data-non_active='".$dk->id_inv_biaya."'><i class='fa fa-times'></i> Non Aktif</a></li>
						                              <li><a href='javascript:void(0)' class='delete' data-tab='biaya' data-delete='".$dk->id_inv_biaya."'><i class='fa fa-trash-o'></i> Hapus</a></li>
						                            ";
						                       
						                      }
						                      if($dk->na == 'y'){
						                        echo "<li><a href='javascript:void(0)' class='set_active' data-tab='biaya' data-set_active='".$dk->id_inv_biaya."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
			        <div class="box box-success" id="box-add-biaya">
			          <div class="box-header with-border">
			              <h3 class="box-title title-form-biaya"><strong>Buat Biaya Baru</strong></h3>
			              <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new-biaya">Buat Biaya Baru</button>
			          </div>
			          <!-- /.box-header -->
			          <!-- form start -->
			          <form role="form" class="form-master-biaya">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="kode">Kode SAP</label>
			                <input type="text" class="form-control" name="kode_sap" id="kode_sap" placeholder="Masukkkan Kode SAP" required="required">
			              </div>
			               <div class="form-group">
			                <label for="biaya">Biaya</label>
			                <input type="text" class="form-control" name="biaya" id="biaya" placeholder="Masukkkan nama biaya" required="required">
			              </div>
			              <div class="form-group">
			                <label for="km">Input KM</label><br>
		                	<label class="radio-inline"><input type="radio" id="radio" name="radio" value="1" checked="checked">Yes</label>
							<label class="radio-inline"><input type="radio" id="radio" name="radio" value="0">No</label>
			              	<input type="hidden" name="km" id="km" value="1">
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_inv_biaya">
			              <button type="submit" class="btn btn-success">Submit</button>
			            </div>
			          </form>
			        </div>

			    </div>

		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/biaya.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


