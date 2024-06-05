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

		            	<?php if($filterpabrik != "" && $filterlimbah != "" && $filterfrom != "" && $filterto != ""){ ?>
			            	<div class="col-md-1 pull-right" style="margin-top: 40px">

							    <form method="POST" target="_blank" action="<?php echo base_url() ?>she/report/pdf/logbookB3" class="filter-logbookb3_pdf" role="form">
					              	<div class="col-md-1">
				            			<button id="print-btn" type="submit" name="printaction_btn" class="btn btn-primary">Print &nbsp; <i class="fa fa-print"></i></button>
										<input type="hidden" id="printto" name="printto" value="<?php echo $filterto; ?>" readonly required>
										<input type="hidden" id="printfrom" name="printfrom" value="<?php echo $filterfrom; ?>" readonly required>
										<input type="hidden" id="printlimbah" name="printlimbah" value="<?php echo $filterlimbah; ?>" readonly required>
										<input type="hidden" id="printpabrik" name="printpabrik" value="<?php echo $filterpabrik; ?>" readonly required>
									</div>
							    </form>

			            	</div>
			            <?php
			        	}
			        	?>

					    <form method="POST" id="filterform" action="<?php echo base_url() ?>she/report/limbahb3/logbook" class="filter-logbookb3" role="form">
			              	<div class="col-md-11" style="margin-top: 20px;">
				              	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Pabrik :</label>
					                  <select name="filterpabrik" id="filterpabrik" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih </option>
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
				              	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Limbah :</label>
					                  <select name="filterlimbah" id="filterlimbah" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih </option>
					                    <?php
					                    	// if($filterlimbah != 0){
												foreach ($limbah as $limbah) {
													if($limbah->id == $filterlimbah){
														$selected = "selected";
													}else{
														$selected = "";
													}
													echo "<option value='".$limbah->id."' ".$selected.">".$limbah->jenis_limbah."</option>";
												}
					                    	// }
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
					                  	<input type="text" class="form-control monthPicker" style="padding:10px;" placeholder="mm.yyyy" id="filterfrom" name="filterfrom" value="<?php echo $filterfrom; ?>" readonly required onchange="filtersubmit()">
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
						                <input type="text" class="form-control opt-control monthPicker" style="padding:10px;" placeholder="mm.yyyy" id="filterto" name="filterto" value="<?php echo $filterto; ?>" readonly required onchange="filtersubmit()">
					                  </div>
					                </div>
				            	</div>
							</div>
					    </form>
			            
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table width="100%" class="table table-bordered table-striped">
		              		<thead id="table_header">
				                <?php
									echo "<tr>";
										echo "<th class='text-center' colspan='8'>Limbah B3 Masuk</th>";
										echo "<th class='text-center' colspan='5'>Limbah B3 Keluar</th>";
										echo "<th class='text-center' colspan='2'>Saldo</th>";
									echo "</tr>";
									echo "<tr>";
										echo "<th class='text-center' rowspan='2'>No.</th>";
										echo "<th class='text-center' rowspan='2'>Jenis Limbah</th>";
										echo "<th class='text-center' rowspan='2'>Kode Material</th>";
										echo "<th class='text-center' rowspan='2'>Tanggal Masuk</th>";
										echo "<th class='text-center' rowspan='2'>Sumber Limbah</th>";
										echo "<th class='text-center' colspan='2'>Jumlah Limbah Masuk</th>";
										echo "<th class='text-center' rowspan='2'>Max. Masa Simpan</th>";
										echo "<th class='text-center' rowspan='2'>Tanggal Keluar</th>";
										echo "<th class='text-center' colspan='2'>Jumlah Limbah Keluar</th>";
										echo "<th class='text-center' rowspan='2'>Tujuan Penyerahan</th>";
										echo "<th class='text-center' rowspan='2'>Nomor Manifest</th>";
										echo "<th class='text-center' colspan='2'>Sisa LB3 yang ada di TPS</th>";
									echo "</tr>";
									echo "<tr>";
										echo "<th class='text-center'>Jumlah</th>";
										echo "<th class='text-center'>Satuan</th>";
										echo "<th class='text-center'>Jumlah</th>";
										echo "<th class='text-center'>Satuan</th>";
										echo "<th class='text-center'>Jumlah</th>";
										echo "<th class='text-center'>Satuan</th>";
									echo "</tr>";
				                ?>									
				            </thead>
			              	<tbody id="table_body">
				                <?php
				       //          	if(1 == 2){
							    //         echo "<tr>";
						     //        	if($dt->number == 0){
											// echo "<td colspan='14'>Data not found</td>";
							    //         echo "</tr>";
				       //          	}else{
					                	$no = 0;
					                	foreach($report as $key => $dt){
								            echo "<tr>";
							            	if($dt->number == 0){
												echo "<td></td>";
												echo "<td>".$dt->jenis_limbah."</td>";
												echo "<td>".$dt->kode_material."</td>";
												echo "<td>".$dt->tgl_masuk."</td>";
												echo "<td></td>";
												echo "<td></td>";
												echo "<td></td>";
												echo "<td></td>";
												echo "<td></td>";
												echo "<td></td>";
												echo "<td></td>";
												echo "<td></td>";
												echo "<td></td>";
												echo "<td align='right'>".$dt->stok."</td>";
												echo "<td align='center'>".$dt->satuan."</td>";
							            	}else{
							            		$qty_in = ($dt->type == 'IN')?$dt->quantity:'';
							            		$satuan_in = ($dt->type == 'IN')?$dt->satuan:'';
							            		$qty_out = ($dt->type == 'OUT')?$dt->quantity:'';
							            		$satuan_out = ($dt->type == 'OUT')?$dt->satuan:'';
							            		$no++;
												echo "<td align='center'>".$no."</td>";
												echo "<td>".$dt->jenis_limbah."</td>";
												echo "<td>".$dt->kode_material."</td>";
												echo "<td>".$dt->tgl_masuk."</td>";
												echo "<td>".$dt->sumber_limbah."</td>";
												echo "<td align='right'>".$qty_in."</td>";
												echo "<td align='center'>".$satuan_in."</td>";
												echo "<td align='center'>".$dt->max_simpan."</td>";
												echo "<td align='center'>".$dt->tgl_keluar."</td>";
												echo "<td align='right'>".$qty_out."</td>";
												echo "<td align='center'>".$satuan_out."</td>";
												echo "<td>".$dt->nama_vendor."</td>";
												echo "<td>".$dt->no_manifest."</td>";
												echo "<td align='right'>".$dt->stok."</td>";
												echo "<td align='center'>".$dt->satuan."</td>";
							            	}
								            echo "</tr>";
							        	}
				                	// }
				                ?>
			              	</tbody>
			            </table>
		            
			        </div>
				</div>
			</div>
		</div>

	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_logbook_b3.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<style>
.small-box .icon{
    top: -13px;
}
.ui-datepicker-calendar {
    display: none;
 }
</style>
