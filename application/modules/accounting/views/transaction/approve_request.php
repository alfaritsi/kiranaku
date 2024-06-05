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

					    <form method="POST" id="filterform" action="<?php echo base_url() ?>accounting/transaction/upload/approve" class="filter-transaction-upload-jurnal" role="form">
			              	<div class="col-md-12" style="margin-top: 20px; padding-left:0px;">
				              	<div class="col-md-6">
					                <div class="form-group">
					                  <label>Pabrik</label>
					                  <select data-placeholder="Pilih Pabrik" name="filterpabrik[]" id="filterpabrik" class="form-control select2" multiple style="width: 100%;" required>
					                  	<option value=""></option>
					                    <?php		
					                      foreach ($pabrik as $pabrik) {
					                      	if(in_array($this->generate->kirana_encrypt($pabrik->kode), $filterpabrik)){
					                      		$selected = "selected";
					                      	}else{
					                      		$selected = "";
					                      	}
						                    echo "<option value='".$this->generate->kirana_encrypt($pabrik->kode)."' ".$selected.">".$pabrik->nama."</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
				            	<div class="col-md-1">
					                <div class="form-group" style="margin-top: 25px;">
					                	<button type="submit" class="btn btn-default"> <i class="fa fa-search"></i> View</button>
					                </div>
				            	</div>
				            	<div class="col-md-5">
					                <div class="form-group pull-right" style="margin-top: 25px;">
					                	<button type="submit" name="approve_btn" class="btn btn-primary"> <i class="fa fa-thumbs-up"></i> Approve</button>
					                	<button type="submit" name="reject_btn" class="btn btn-default"> <i class="fa fa-thumbs-down"></i> Reject</button>
					                </div>
				            	</div>
							</div>
					    </form>
			            
	          		</div>
	          		<!-- /.box-header -->


		          	<div class="box-body">
		          		<form method="POST" id="form" class="form-approval" role="form">
				        	<legend style="font-size: 14px;"><label> List Data </label></legend>
			           		<div class="col-md-12">
				           		<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
				              		<thead>
								        <tr>
											<th width="1px" class='text-center'>
												<input type='checkbox' class='checkbox chkall' name='chkall' id='chkall'>
											</th>
											<th class='text-center'>No</th>          
											<th class='text-center'>No. Dokumen</th>          
											<th class='text-center'>Text</th>          
											<th class='text-center'>Tipe Dokumen</th>          
											<th class='text-center'>File Terupload</th>          
											<th class='text-center'>Tanggal Upload</th>          
											<th class='text-center'>Keterangan</th>          
								        </tr>
						            </thead>
					              	<tbody>
						                <?php
							                foreach($jurnal as $key => $dt){
							                  $no = $key + 1;
							                  echo "<tr>";
								              echo "<td align='center'><input type='checkbox' class='checkbox chkdok' name='chkdok[]' id='chkdok' value='".$this->generate->kirana_encrypt($dt->id)."'></td>";
								              echo "<td align='center'>".$no."</td>";
								              echo "<td align='center'>".$dt->no_doc."</td>";
								              echo "<td>".$dt->text."</td>";
								              echo "<td>".$dt->tipe."</td>";
								              echo "<td>";
								              	if($dt->data != "" && $dt->data != "-" && !empty($dt->data)){
								              		$data = explode("|", $dt->data);
								              		foreach ($data as $key => $file) {
								              			if($file != ""){
										              		echo "<a href='".$file."' target='_blank' style='color:green;'> <i class='fa fa-file-pdf-o'></i> ".str_replace("assets/file/acc/uploadjurnal/", "", $file)."</a><br/>";						              				
								              			}
								              		}
								              	}
								              echo "</td>";
								              $uploaddate = empty($dt->upload_date)?'':$this->generate->generateDateFormat($dt->upload_date);
								              echo "<td align='center'>".$uploaddate."</td>";
								              echo "<td>".$dt->info."</td>";
							                  echo "</tr>";
							                }
						                ?>
					              	</tbody>
					            </table>
				            </div>
				        </form>
			        </div>
				</div>
			</div>
		</div>


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/accounting/transaction/approve_request.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>
