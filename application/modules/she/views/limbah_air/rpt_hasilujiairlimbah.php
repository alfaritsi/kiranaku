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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong> <?php echo $title; ?></strong></h3>

	            		<div class="clearfix"></div>
					    <form method="POST" id="filterform" action="<?php echo base_url() ?>she/report/limbahair/hasiluji" class="filter-airlimbah_harian" role="form">
			              	<div class="col-md-9" style="margin-top: 20px;">
				              	<div class="col-md-5">
					                <div class="form-group">
					                  <label>Pabrik :</label>
					                  <select name="filterpabrik" id="filterpabrik" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
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
				            	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Kategori :</label>
					                  <select name="filterkategori" id="filterkategori" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <!-- <option value="" selected> </option> -->
					                    <?php
					                      foreach ($kategori as $keyo => $dt) {
					                        if($filterkategori == $dt->id){
					                        	$selected = "selected";
					                        }else{
						                        $selected = "";
					                      	}
				                        	echo "<option value='".$dt->id."' ".$selected.">".$dt->kategori."</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
							</div>
					    </form>
		            	<div class="col-md-3 pull-right" style="margin-top: 20px;">
							<div class="btn-group pull-right">
								<button type="button" class="btn btn-md btn-success" id="excel_button"><i class="fa fa-table"></i> Export To Excel</button>
							</div>					
		            	</div>
			            
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<!--<table width="100%" class="table table-bordered table-striped" id="excel">-->
						<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead id="table_header">
				                <?php
					                if(empty($reporth)){
					                  	echo "<tr>";
						                  	echo "<th class='text-center'>Parameter</th>";
						                  	echo "<th colspan='3' class='text-center'>pH</th>";
						                  	echo "<th colspan='2' class='text-center'>COD</th>";
						                  	echo "<th colspan='2' class='text-center'>BOD</th>";
						                  	echo "<th colspan='2' class='text-center'>TSS</th>";
						                  	echo "<th colspan='2' class='text-center'>Ammonia</th>";
						                  	echo "<th colspan='2' class='text-center'>Total Nitrogen</th>";
					                  	echo "</tr>";
					                  	echo "<tr>";
						                  	echo "<th class='text-center'>Min</th>";
						                  	echo "<th class='text-center'>Max</th>";
						                  	echo "<th class='text-center'>Hasil Uji</th>";
						                  	echo "<th class='text-center'>Baku Mutu</th>";
						                  	echo "<th class='text-center'>Hasil Uji</th>";
						                  	echo "<th class='text-center'>Baku Mutu</th>";
						                  	echo "<th class='text-center'>Hasil Uji</th>";
						                  	echo "<th class='text-center'>Baku Mutu</th>";
						                  	echo "<th class='text-center'>Hasil Uji</th>";
						                  	echo "<th class='text-center'>Baku Mutu</th>";
						                  	echo "<th class='text-center'>Hasil Uji</th>";
						                  	echo "<th class='text-center'>Baku Mutu</th>";
						                  	echo "<th class='text-center'>Hasil Uji</th>";
					                  	echo "</tr>";
					                  	echo "</thead>";
					                  	echo "</table>";

										$this->load->view('footer') ?> 
										<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
										<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
										<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_hasilujiairlimbah.js"></script>
										<style>
										.small-box .icon{
										    top: -13px;
										}
										</style>
										<?php
				                  		exit();
				                  	}
				                ?>									
						        <tr>
				                  	<th rowspan='2'>Parameter</th>
					                <?php
						                foreach($reporth as $dt){
						                  if($dt['param'] == 1){
						                  	echo "<th colspan='3' class='text-center'>".$dt['parameter']."</th>";
						                  }else {
						                  	echo "<th colspan='2' class='text-center'>".$dt['parameter']."</th>";
						                  }
						                }
					                ?>
					                <th rowspan='2'>Lampiran</th>									
						        </tr>
								<tr>
					                <?php
					                	$kolom = 0;
						                foreach($reporth as $dt){
						                  if($dt['param'] == 1){
						                  	echo "<th class='text-center'>Min</th>";
						                  	echo "<th class='text-center'>Max</th>";
						                  	echo "<th class='text-center'>Hasil Uji</th>";
						                  	// $kolom = $kolom + 3;
						                  }else {
						                  	echo "<th class='text-center'>Baku Mutu</th>";
						                  	echo "<th class='text-center'>Hasil Uji</th>";
						                  	$kolom = $kolom + 2;
						                  }
						                }
					                ?>									
								</tr>
				            </thead>
			              	<tbody id="table_body">
				                <?php
				                	if($kolom > 0){
						                foreach($report as $result){
						                  $dt = explode(';', $result['VALUE']);
						                  $dtred = explode(';', $result['red_texth']);
						                  echo "<tr>";
						                  echo "<td>".$result['PARAMETER']."</td>";
						                  echo "<td align='right'>".$dt[0]."</td>";
						                  echo "<td align='right'>".$dt[1]."</td>";
						                  echo "<td align='right' ".$dtred[0].">".$dt[2]."</td>";
	                    				  // echo "<td ".$dt->red_texth.">".number_format($dt->PH_HASIL,2,",",".")."</td>";
						                  $i = 0;
						                  for ($i=1; $i < $kolom; $i++) { 
							                  echo "<td align='right'>".$dt[$i+2]."</td>";
							                  $i++;
		                    				  echo "<td align='right' ".$dtred[$i+2].">".number_format($dt[$i+2],2,",",".")."</td>";
						                  }
						                  if($result['LAMPIRAN'] != "" && $result['LAMPIRAN'] != null){
						                  	$gb = "<a class='glyphicon glyphicon-download-alt' href='".base_url().$result['LAMPIRAN']."' target='_blank'></a>";
						                  } else {
						                  	$gb = "";
						                  }
						                  echo "<td align=center>".$gb."</td>";
						                  // echo "<td>".$dt[3]."</td>";
	                    			// 	  echo "<td ".$dtred[1].">".number_format($dt[4],2,",",".")."</td>";
						                  // echo "<td>".$dt[5]."</td>";
	                    			// 	  echo "<td ".$dtred[2].">".number_format($dt[6],2,",",".")."</td>";
						                  // echo "<td>".$dt[7]."</td>";
	                    			// 	  echo "<td ".$dtred[3].">".number_format($dt[8],2,",",".")."</td>";
						                  // echo "<td>".$dt[9]."</td>";
	                    			// 	  echo "<td ".$dtred[4].">".number_format($dt[10],2,",",".")."</td>";
						                  // echo "<td>".$dt[11]."</td>";
	                    			// 	  echo "<td ".$dtred[5].">".number_format($dt[12],2,",",".")."</td>";
	                    
						                }
				                	}
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
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_hasilujiairlimbah.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->

<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>
