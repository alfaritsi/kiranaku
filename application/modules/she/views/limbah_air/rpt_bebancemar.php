<!--
/*
@application    : SHE 
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
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong> <?php echo $title; ?></strong></h3>

	            		<div class="clearfix"></div>
					    <form method="POST" id="filterform" action="<?php echo base_url() ?>she/report/limbahair/bebancemar" role="form">
			              	<div class="col-md-12" style="margin-top: 20px;">
				              	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Pabrik :</label>
					                  <select name="filterpabrik" id="filterpabrik" class="form-control select2" style="width: 100%;" value="<?php echo $filterpabrik; ?>" required onchange="filtersubmit()">
					                    <option value="" selected> </option>
					                    <?php
					                      foreach ($pabrik as $pabrik) {
					                      	if($pabrik->id_pabrik == $filterpabrik){
					                      		$selected = "selected";
					                      	}else{
					                      		$selected = "";
					                      	}
					                        echo "<option value='".$pabrik->id_pabrik."' ".$selected.">".$pabrik->nama." (".$pabrik->kode.")</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>From :</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
					                  	<input type="text" class="form-control monthPicker" placeholder="mm.yyyy" id="from" name="from" value="<?php echo $from; ?>" readonly required onchange="filtersubmit()">
					                  </div>
					                </div>
				            	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>To :</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
					                  	<input type="text" class="form-control monthPicker" placeholder="mm.yyyy" id="to" name="to" value="<?php echo $to; ?>" readonly required onchange="filtersubmit()">
					                  </div>
					                </div>
				            	</div>
								<!--
				            	<div class="col-md-2">
					                <div class="form-group">
										<label>Parameter :</label>
										<select class="form-control select2" multiple="multiple" id="parameter" name="parameter[]" style="width: 100%;" data-placeholder="Pilih Parameter" onchange="filtersubmit()">
											<?php
												foreach($parameter as $dt){
													if(in_array($dt->parameter, $_POST['parameter'])){
														echo "<option value='".$dt->parameter."' selected>".$dt->parameter."</option>";
													}else{
														echo "<option value='".$dt->parameter."'>".$dt->parameter."</option>";
													}
												}
											?>
										</select>
					                </div>
				            	</div>
								-->
				            	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Kategori :</label>
					                  <select name="filterkategori" id="filterkategori" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <!-- <option value="" selected> </option> -->
					                    <?php
					                      foreach ($kategori as $keyo => $dt) {
											  if($dt->id==1){
												if($filterkategori == $dt->id){
													$selected = "selected";
												}else{
													$selected = "";
												}
												echo "<option value='".$dt->id."' ".$selected.">".$dt->kategori."</option>";
											  }
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
								<!--
				            	<div class="col-md-2">
					                <div class="form-group">
										<label>Tidak Sesuai Baku Mutu :</label>
										<div>
										<input type='checkbox' class='switch-onoff' name='req' id='req' data-id='".$dt->id_item_master_matrix."' onchange="filtersubmit()">
										</div>
					                </div>
				            	</div>
								
				            	<div class="col-md-1">
					                <div class="form-group">
										<label>&nbsp;</label>
										<button type="submit" name="action_btn" class="btn btn-primary">Filter</button>
					                </div>
				            	</div>
								-->
							</div>
					    </form>
			            
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table width="100%" class="table table-bordered table-striped my-datatable">
		              		<thead id="table_header">
						        <tr>
				                  	<th rowspan='2'>Parameter</th>
					                <?php
						                foreach($reporth as $dt){
						                  if(strtolower($dt->parameter) == 'ph'){
						                  	echo "<th class='text-center' colspan='3'>".$dt->parameter."</th>";
						                  }else {
						                  	echo "<th class='text-center' colspan='2'>".$dt->parameter."</th>";
						                  }
						                }
					                ?>									
						        </tr>
								<tr>
					                <?php
					                	$kolom = 0;
						                foreach($reporth as $dt){
						                  if(strtolower($dt->parameter) == 'ph'){
						                  	echo "<th class='text-center'>Min</th>";
						                  	echo "<th class='text-center'>Max</th>";
						                  	echo "<th class='text-center'>BP</th>";
						                  	$kolom = $kolom + 3;
						                  }else {
						                  	echo "<th class='text-center'>Baku Mutu</th>";
						                  	echo "<th class='text-center'>BP</th>";
						                  	$kolom = $kolom + 2;
						                  }
						                }
					                ?>									
								</tr>
				            </thead>
			              	<tbody id="table_body">
				                <?php
				                	$dt = 0; $dtred = ""; $avg = 0; 
				                	if($kolom > 0){
						                foreach($report as $result){
											echo "<tr>";
											echo "<td>".$result->PARAMETER."</td>";
											// $data = explode(';', $result->VALUE);	
											// $dtred = explode(';', $result->red_texth);
											$data = explode(';', substr($result->VALUE, 1));	
											$dtred = explode(';', substr($result->red_texth, 1));
											$ii = 0;
											$dt2 = 0;
											foreach($data as $dt) {
												// if($dt>9){
													// echo "<td align='right' style='color:red'>".$dt."</td>";
												// }else{
													// echo "<td align='right' $dtred[$ii]>".$dt."</td>";
												// }
												echo "<td align='right' $dtred[$ii]>".$dt."</td>";
												$ii++;
											}			
											echo "</tr>";		
											// // $dt = explode(';', $result->VALUE);
											// // $dtred = explode(';', $result->red_texth);
											// // $avg = explode(';', $result->AVERAGE);
											// // echo "<tr>";
											// // echo "<td>".$result->PARAMETER."</td>";
											// // echo "<td align='right'>".$dt[0]."</td>";
											// // echo "<td align='right'>".$dt[1]."</td>";
											// // echo "<td align='right' ".$dtred[0].">".$dt[2]."</td>";
											// // $i = 0;
											// // for ($i=1; $i < $kolom; $i++) { 
											  // // echo "<td align='right'>".$dt[$i+2]."</td>";
											  // // $i++;
											  // // echo "<td align='right' ".$dtred[$i+2].">".number_format($dt[$i+2],2,",",".")."</td>";
											// // }
						                }
				                	}
				                ?>
			              	</tbody>
							
								<?php
				                	if(!empty($result->AVERAGE)){
										echo "<tfoot>";
										echo "<tr>";
										echo "<td><strong>Rata - Rata</strong></td>";
										$data = @explode(';', $result->AVERAGE);	
										foreach($data as $dt) {    
											if($dt!=""){
												echo "<td align='right'>".$dt."</td>";
											}	
										}			
										echo "</tr>";		
										echo "</tfoot>";
				                	}
									
					                // // echo "<td></td>";
					                // // echo "<td></td>";
					                // // echo "<td></td>";
                    				  // // echo "<td ".$dt->red_texth.">".number_format($dt->PH_HASIL,2,",",".")."</td>";
					                // $i = 0;
					                // for ($i=1; $i < $kolom; $i++) { 
						                // echo "<td></td>";
						                // $i++;
	                    				// echo "<td align='right'><strong>".number_format($avg[$i+2],2,",",".")."</strong></td>";
					                // }
					                // echo "</tr>";

								?>
										              	
			            </table>
			        </div>
				</div>
			</div>
		</div>

	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_bebancemar.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<style>
.small-box .icon{
    top: -13px;
}
</style>
