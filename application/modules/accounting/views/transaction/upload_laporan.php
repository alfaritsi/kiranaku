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
<link rel='stylesheet' href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.css" />
<link rel='stylesheet' href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.theme.default.css" />
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

					    <form method="POST" id="filterform" action="<?php echo base_url() ?>accounting/transaction/upload/laporan" class="filter-transaction-upload-laporan" role="form">
			              	<div class="col-md-6" style="margin-top: 20px; padding-left:0px;">
				              	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Plant</label>
					                </div>
				              	</div>
				              	<div class="col-md-10">
					                <div class="form-group">
					                  <select data-placeholder="Pilih Plant" name="filterpabrik" id="filterpabrik" class="form-control select2" style="width: 100%;" required>
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
				              	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Source</label>
					                </div>
				              	</div>
				              	<div class="col-md-10">
					                <div class="form-group">
					                  <select data-placeholder="Pilih Source" name="filtersource" id="filtersource" class="form-control select2" style="width: 100%;" required>
					                  	<option value=""></option>
						                <?php
						                	$selected = $filtersource == 2 ? "selected" : "";
						                	echo "<option value='".$this->generate->kirana_encrypt(2)."' ".$selected.">Upload Jurnal</option>";
						                	$selected = $filtersource == 1 ? "selected" : "";
						                	echo "<option value='".$this->generate->kirana_encrypt(1)."' ".$selected.">Upload Lain</option>";
						                ?>
					                  </select>
					                </div>
				              	</div>
								<div class="col-md-2">
					                <div class="form-group">
					                  <label>Type</label>
					                </div>
				              	</div>
				              	<div class="col-md-10">
					                <div class="form-group">
					                  <select data-placeholder="Pilih Type" name="filtertype" id="filtertype" class="form-control select2" style="width: 100%;" required>
					                  	<option value=""></option>
						                <?php
						                	$selected = $filtertype == 'HO' ? "selected" : "";
						                	echo "<option value='HO' ".$selected.">HO</option>";
						                	$selected = $filtertype == 'BRANCH' ? "selected" : "";
						                	echo "<option value='BRANCH' ".$selected.">PABRIK</option>";
						                ?>
					                  </select>
					                </div>
				              	</div>
				              	<div id="div_jenis">
					              	<div class="col-md-2">
						                <div class="form-group">
						                  <label>Mapping Doc.</label>
						                </div>
					              	</div>
					              	<div class="col-md-10">
						                <div class="form-group">
						                  <select data-placeholder="Pilih Mapping Doc." name="filterjenis" id="filterjenis" class="form-control select2" style="width: 100%;" required>
							                <option value=""></option>
						                    <?php		
						                      foreach ($jenis as $key => $jenis1) {
						                      	$selected = ($jenis1->id_jenis == $filterjenis) ? "selected" : "";
							                    echo "<option value='".$this->generate->kirana_encrypt($jenis1->id_jenis)."' ".$selected.">".$jenis1->nama."</option>";
						                      }
						                    ?>
						                  </select>
						                </div>
					              	</div>
				              	</div>
				            </div>

				            <div class="col-md-6" style="margin-top: 20px; padding-left:0px;">
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>From</label>
					                </div>
				            	</div>
				            	<div class="col-md-4">
					                <div class="form-group">
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
						                  <input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" id="filterfrom" name="filterfrom" value="<?php echo $filterfrom; ?>" readonly required>
					                  </div>
					                </div>
				            	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>To</label>
					                </div>
				            	</div>
				            	<div class="col-md-4">
					                <div class="form-group">
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
						                  <input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" id="filterto" name="filterto" value="<?php echo $filterto; ?>" readonly required>
					                  </div>
					                </div>
				            	</div>
				              	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Search</label>
					                </div>
				              	</div>
				              	<div class="col-md-4">
					                <div class="form-group">
					                  <select data-placeholder="Pilih Search" name="filtersearch" id="filtersearch" class="form-control select2" style="width: 100%;">
						                <option value=""></option>

						                <?php
						                	$selected = $filtersearch == "no_doc" ? "selected" : "";
							                echo "<option value='".$this->generate->kirana_encrypt(no_doc)."' ".$selected.">No Doc</option>";
						                	$selected = $filtersearch == "tipe" ? "selected" : "";
							                echo "<option value='".$this->generate->kirana_encrypt(tipe)."' ".$selected.">Doc Type</option>";
						                	$selected = $filtersearch == "in_date" ? "selected" : "";
							                echo "<option value='".$this->generate->kirana_encrypt(in_date)."' ".$selected.">Tanggal Upload</option>";
						                ?>
					                  </select>
					                </div>
				              	</div>
				              	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Parameter Value</label>
					                </div>
				              	</div>
				              	<div class="col-md-4">
					                <div class="form-group">
					                  <input type="text" name="filterparam" id="filterparam" class="form-control" value="<?php echo $filterparam; ?>" autocomplete="off">
					                </div>
				              	</div>
				              	<div class="clearfix"></div>
				              	<div class="col-md-4">
					                <div class="form-group">
										<?php
											$checked = "";
											if($filternoupload != ""){
											$checked = "checked";
											}
											echo "<label class='checkbox' style='margin-top:-10px; padding-left: 20px;'>";
											echo "<input type='checkbox' class='chknoupload' id='chknoupload' name='chknoupload' ".$checked.">document not checked";
											echo "</label>";
										?>
					                </div>
				              	</div>
								<div class="col-md-4">
					                <div class="form-group">
										<?php
											$checkeds = "";
											if($filterisupload != ""){
											$checkeds = "checked";
											}
											echo "<label class='checkbox' style='margin-top:-10px; padding-left: 20px;'>";
											echo "<input type='checkbox' class='chkisupload' id='chkisupload' name='chkisupload' ".$checkeds.">document not uploaded";
											echo "</label>";
										?>
					                </div>
				              	</div>
				            	<div class="col-md-4">
					                <div class="form-group pull-right">
					                	<button type="submit" class="btn btn-default"> <i class="fa fa-search"></i> View</button>
					                </div>
				            	</div>
							</div>
					    </form>
			            
	          		</div>
	          		<!-- /.box-header -->

		          	<div class="box-body">		          		
			        	<legend style="font-size: 14px;"><label> List Data </label></legend>
			        	<form role="form" class="form-check">
				        	<div style="margin-top: -10px; margin-bottom: 10px;">
								<a class="btn btn-sm btn-flat btn-default action_btn">Expand All</a>
								<input type="hidden" name="status_tree" class="status_tree" value="collapsed">
								<button type="submit" class="btn btn-primary" name="check_btn"> <i class="fa fa-save"></i> Save </button>
				        	</div>
			           		<table width="100%" id="example-advanced" class="table table-bordered table-hover">
			              		<thead>
							        <tr>
										<th class='text-center'>Date & Doc. Number</th>          
										<th class='text-center'>Header TExt</th>          
										<th class='text-center'>Amount In LC</th>          
										<th class='text-center'>Refference</th>          
										<th class='text-center'>File</th>          
										<th class='text-center'>Upload Date</th>          
										<th class='text-center'>Transafer Date</th>          
										<th class='text-center'>Doc. Mapping</th>          
										<th class='text-center'>Source</th>          
										<th class='text-center'>Info</th>          
										<th class='text-center'>Uploaded By</th>          
										<th width="1px" class='text-center'>Check</th>          
							        </tr>
					            </thead>
				              	<tbody>
					                <?php
						                foreach($upload as $key => $dt){
						                  $pnode  = ($dt->id == 0 ? '' : 'data-tt-parent-id=' . $dt->periode . '');
						                  $id = ($dt->id == 0 ? $dt->periode : $dt->id);
						                  $label = ($dt->id == 0 ? $this->generate->generateDateFormat($dt->tgl) : $dt->no_doc);
						                  $nilai = ($dt->id == 0 ? "" : number_format(floatval($dt->dmbtr), 0, ',', '.'));
						                  echo "<tr data-tt-id=".$id." ".$pnode.">";
							              echo "<td> <span class='menu-content'>".$label."</span> </td>";
							              echo "<td>".$dt->text."</td>";
							              echo "<td class='text-right'>".$nilai."</td>";
							              echo "<td>".$dt->reff."</td>";
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
							              $transferdate = ($dt->id == 0)?'':$this->generate->generateDateFormat($dt->tgl);
							              echo "<td align='center'>".$transferdate."</td>";
							              echo "<td>".$dt->nama_jenis."</td>";
							              echo "<td>".$dt->nama_jenis."</td>";
							              echo "<td>".$dt->info."</td>";
							              echo "<td>".$dt->nama_user."</td>";
							              if($dt->id == 0){
							              	echo "<td align='center'></td>";						              	
							              }else{
							              	$checked = ($dt->checklist == "y") ? "checked" : "";
							              	$disabled = ($dt->checklist == "y") ? "disabled" : "";
							              	echo "<td align='center'> <lable type='checkbox'><input type='checkbox' class='checkbox checkjurnal' name='checkjurnal[]' id='checkjurnal' value='".$this->generate->kirana_encrypt($dt->id."|".$dt->data)."' ".$checked." ".$disabled."></lable> </td>";		
							              }

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


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() . 'assets/plugins/jquery.treetable/jquery.treetable.js' ?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/accounting/transaction/upload_laporan.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>

<script>
  $('#example-basic').treetable({ expandable: true });
  $('#example-basic-static').treetable();
  $('#example-basic-expandable').treetable({ expandable: true });
  $('#example-advanced').treetable({ expandable: true });
  // Highlight selected row
  $('#example-advanced tbody').on('mousedown', 'tr', function() {
    $('.selected').not(this).removeClass('selected');
    $(this).toggleClass('selected');
  });
  // // Drag & Drop Example Code
  // $('#example-advanced .file, #example-advanced .folder').draggable({
  //   helper: 'clone',
  //   opacity: .75,
  //   refreshPositions: true, // Performance?
  //   revert: 'invalid',
  //   revertDuration: 300,
  //   scroll: true
  // });
  $('#example-advanced .folder').each(function() {
    $(this).parents('#example-advanced tr').droppable({
      accept: '.file, .folder',
      drop: function(e, ui) {
        var droppedEl = ui.draggable.parents('tr');
        $('#example-advanced').treetable('move', droppedEl.data('ttId'), $(this).data('ttId'));
      },
      hoverClass: 'accept',
      over: function(e, ui) {
        var droppedEl = ui.draggable.parents('tr');
        if(this != droppedEl[0] && !$(this).is('.expanded')) {
          $('#example-advanced').treetable('expandNode', $(this).data('ttId'));
        }
      }
    });
  });
</script>

