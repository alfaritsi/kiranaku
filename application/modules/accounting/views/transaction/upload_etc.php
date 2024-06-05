<!--
/*
@application    : Attachment Accounting 
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<style type="text/css">
  .listframe { border:1px solid #ccc; padding: 4px; width:100%; min-height: 50px; overflow-y: }
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	            		<div class="clearfix"></div>

					    <form method="POST" id="filterform" action="<?php echo base_url() ?>accounting/transaction/upload/etc" class="filter-transaction-upload-etc" role="form">
			              	<div class="col-md-12" style="margin-top: 20px; padding-left:0px;">
				              	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Plant</label>
					                  <select data-placeholder="Pilih Plant" name="filterpabrik" id="filterpabrik" class="form-control select2" style="width: 100%;" onchange="filtersubmit()" required>
					                  	<option value=""></option>
					                    <?php		
					                      foreach ($pabrik as $key => $pabrik1) {
					                      	if($pabrik1->kode == $filterpabrik){
					                      		$selected = "selected";
					                      	}else{
					                      		$selected = "";
					                      	}
						                    echo "<option value='".$this->generate->kirana_encrypt($pabrik1->kode)."' ".$selected.">".$pabrik1->nama."</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
				              	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Mapping Doc.</label>
					                  <select data-placeholder="Pilih Mapping Doc." name="filterjenis" id="filterjenis" class="form-control select2" style="width: 100%;" onchange="filtersubmit()" required>
					                  	<option value=""></option>
					                    <?php	
					                      foreach ($jenis as $key => $jenis1) {
					                      	if($jenis1->id_jenis == $filterjenis){
					                      		$selected = "selected";
					                      	}else{
					                      		$selected = "";
					                      	}
						                    echo "<option value='".$this->generate->kirana_encrypt($jenis1->id_jenis)."' ".$selected.">".$jenis1->nama."</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>From</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
						                  <input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" id="filterfrom" name="filterfrom" value="<?php echo $filterfrom; ?>" onchange="filtersubmit()" readonly required>
					                  </div>
					                </div>
				            	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>To</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
						                  <input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" id="filterto" name="filterto" value="<?php echo $filterto; ?>" onchange="filtersubmit()" readonly required>
					                  </div>
					                </div>
				            	</div>
				            	<div class="col-md-2">
				            		  <?php
				            		  	$checked = "";
				            		  	if($filterchknocheck != ""){
				            		  		$checked = "checked";
				            		  	}
					                  	echo "<label class='checkbox' style='margin-top: 30px;'>";
						              	echo "<input type='checkbox' id='chknocheck' name='chknocheck' onchange='filtersubmit()' ".$checked.">document not checked";
						          	  	echo "</label>";
						          	  ?>
				            	</div>
							</div>
					    </form>
			            
	          		</div>
	          		<!-- /.box-header -->

		          	<div class="box-body">
			        	<form role="form" class="form-check">
			            	<!-- <div class="col-md-1 pull-right" style="margin-top: -15px;">
				                <div class="form-group">
				                	<button type="submit" name="check_btn" class="btn btn-primary"> <i class="fa fa-save"></i> Save</button>
				                </div>
			            	</div>
			            	<div class="col-md-1 pull-right">
				                <div class="form-group" style="margin-top: -15px;">
				                	<button type="submit" class="btn btn-default"> <i class="fa fa-search"></i> View</button>
				                	<a href="" class="btn btn-default" value="Add" data-toggle='modal' data-target='#modal-form'> <i class="fa fa-plus"></i> Add Data</a>
				                </div>
			            	</div> -->
			            	<div class="row">
			            		<div class="col-sm-12">
			            			<button type="submit" name="check_btn" class="btn btn-primary pull-right" style="margin: 0px 0px 10px 10px;"> <i class="fa fa-save"></i> Save</button>
			            			<button type="button" class="btn btn-default pull-right" style="margin: 0px 0px 10px 10px;" data-toggle='modal' data-target='#modal-form'> <i class="fa fa-plus"></i> Add Data</button>
			            		</div>
			            	</div>
			           		<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
			              		<thead>
							        <tr>
										<th width="1px" class='text-center'>No</th>          
										<th class='text-center'>Doc. No</th>          
										<th class='text-center'>Subject</th>          
										<th class='text-center'>Report Date</th>          
										<th class='text-center'>File</th>          
										<th class='text-center'>Upload Date</th>          
										<th class='text-center'>Info</th>          
										<th class='text-center'>Mapping Doc.</th>          
										<th width="1px" class='text-center'">Check</th>          
										<th width="1px"></th>          
							        </tr>
					            </thead>
				              	<tbody>
					                <?php
						                foreach($upload as $key => $dt){
						                  $no = $key + 1;
						                  echo "<tr>";
							              echo "<td align='center'>".$no."</td>";
							              echo "<td align='center'>".substr($dt->no_doc, 0, 4).$filterpabrik.substr($dt->no_doc, -3)."</td>";
							              echo "<td>".$dt->text."</td>";
							              echo "<td align='center'>".$this->generate->generateDateFormat($dt->tgl)."</td>";
							              echo "<td>";
							              	if($dt->data != "" && $dt->data != "-" && !empty($dt->data)){
							              		$data = explode("|", $dt->data);
							              		foreach ($data as $key => $file) {
							              			if($file != ""){
							              				if(substr($file, 0, 3) != "img"){
										              		echo "<a href='".base_url().$file. '?' . time() ."' target='_blank' style='color:green;'> <i class='fa fa-file-pdf-o'></i> ".str_replace("assets/file/acc/uploadjurnal/", "", $file)."</a><br/>";
							              				}else{
										              		echo "<a href='http://10.0.0.249/dev/kiranaku/home/pdfviewer.php?q=".$file. '&' . time() ."' target='_blank' style='color:green;'> <i class='fa fa-file-pdf-o'></i> ".str_replace("img/acc/", "", $file)."</a><br/>";
							              				}
							              			}


							              		}
							              	}else{
							              		echo $dt->remark;
							              	}
							              echo "</td>";
							              $uploaddate = empty($dt->in_date)?'':$this->generate->generateDateFormat($dt->in_date);
							              echo "<td align='center'>".$uploaddate."</td>";
							              echo "<td>".$dt->info."</td>";
							              echo "<td>".$dt->nama_jenis."</td>";
							              if($dt->checklist == "y"){
							              	$checked = "checked";
							              	$disabled = "disabled";
							              }else{
							              	$checked = "";
							              	$disabled = "";
							              }
							              echo "<td align='center'> <input type='checkbox' class='checkbox checkjurnal' name='checkjurnal[]' id='checkjurnal' value='".$this->generate->kirana_encrypt($dt->id)."' ".$checked." ".$disabled."></td>";
							              echo "<td>";
							                if($checked == ""){
								                echo "<div class='input-group-btn'>";
								                    echo "<button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
								                                class='fa fa-th-large'></span></button>";
								                    echo "<ul class='dropdown-menu pull-right'>";
						                            echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$this->generate->kirana_encrypt($dt->id)."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
						                            echo "<li><a href='javascript:void(0)' class='delete' data-delete='".$this->generate->kirana_encrypt($dt->id)."'><i class='fa fa-trash-o'></i> Delete</a></li>";
								                    echo "</ul>";
								                echo "</div>";
							                }
							              echo "</td>";
						                  echo "</tr>";
						                }
					                ?>
				              	</tbody>
				            </table>
				        </form>
			        </div>
				</div>
			</div>
		</div>

	    <!-- Modal -->
	    <div class="modal fade" id="modal-form">
	      <div class="modal-dialog" style="width:500px;">
	        <form role="form" class="form-uploadetc">
	          <div class="modal-content">
	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	              <h4 class="modal-title"> Form Upload Laporan Accounting </h4>
	            </div>
	            <div class="modal-body" style="min-height:200px;">
	                <div class="row">
	                	<div class="col-md-12">
		                    <fieldset class="fieldset-primary">
		                    	<legend class="text-center">Document Information</legend>
				                <div class="form-group" style="margin-bottom: 5px;">
									<label class="col-md-4">Judul Laporan</label>
									<div class="col-md-8">
										<input type="text" name="judul" id="judul" class="form-control" autocomplete="off" required>
									</div>
				                </div>
				                <div class="form-group" style="margin-bottom: 5px;">
									<label class="col-md-4">Pabrik</label>
									<div class="col-md-8">
										<select data-placeholder="Pilih Pabrik" name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" required>
											<option value=""></option>
											<?php		
												foreach ($pabrik as $key2 => $pabrik2) {
													echo "<option value='".$pabrik2->kode."'>".$pabrik2->nama."</option>";
												}
											?>
										</select>
									</div>
				                </div>
				                <div class="form-group" style="margin-bottom: 5px;">
									<label class="col-md-4">Jenis Laporan</label>
									<div class="col-md-8">
										<select data-placeholder="Pilih Jenis Laporan" name="jenis" id="jenis" class="form-control select2" style="width: 100%;" required>
											<option value=""></option>
											<?php		
												foreach ($jenis as $key2 => $jenis2) {
													echo "<option value='".$jenis2->id_jenis."'>".$jenis2->nama."</option>";
												}
											?>
										</select>
									</div>
				                </div>
				                <div class="form-group" style="margin-bottom: 5px;">
									<label class="col-md-4">Tanggal Laporan</label>
									<div class="col-md-8">
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" name="date" id="date" class="form-control datePicker" placeholder="dd.mm.yyyy" autocomplete="off" required readonly>
										</div>
									</div>
				                </div>
				                <div id="div_exist"></div>
				                <div class="form-group" style="margin-bottom: 5px;">
									<label class="col-md-4">File</label>
									<div class="col-md-8">
										<input type="file" name="file" id="file" style="width:100%;" required accept='.pdf'>
									</div>
				                </div>
				                <div class="clearfix"></div>
				                <div class="form-group" style="margin-bottom: 5px;">
									<label class="col-md-4">Keterangan</label>
									<div class="col-md-8">
										<textarea name="info" id="info" class="form-control" rows="3"></textarea>
									</div>
				                </div>
				            </fieldset>
			        	</div>
		        	</div>
		        </div>
	            
	            <div class="modal-footer">
	            	<div class="col-md-12">
						<input type="hidden" name="id" id="id" style="width:100%">
						<input type="hidden" name="action" id="action" style="width:100%">
						<button type="submit" name="action_btn" class="btn btn-primary"> <i class="fa fa-save"></i> Save</button>
	                </div>
	            </div>
	          </div>
	        </form>
	        <!-- /.modal-content -->
	      </div>
	      <!-- /.modal-dialog -->
	    </div>
	    <!-- /.modal -->


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/accounting/transaction/upload_etc.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>
