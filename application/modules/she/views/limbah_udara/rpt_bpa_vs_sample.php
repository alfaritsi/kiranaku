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


<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong> <?php echo $title; ?></strong></h3>

	            		<div class="clearfix"></div>
					    <form method="POST" id="filterform" action="<?php echo base_url() ?>she/report/limbahudara/bpavssampleloc" class="filter-bpavssample" role="form">
			              	<div class="col-md-12" style="margin-top: 20px;">
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
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Periode :</label>
					                  <select name="filterperiode" id="filterperiode" class="form-control" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> </option>
					                    <?php
											$y=date('Y')+5; $x=date('Y')-5;  
											for ($i=$x; $i < $y;) { 
												$i = $i; $j= $i++;
												if($j.'-'.$i == $filterperiode){
					                      			$selected = "selected";
						                      	}else{
						                      		$selected = "";
						                      	}							
												echo "<option value='".$j."-".$i."' ".$selected.">".$j."-".$i."</option>";
											} 
					                    ?>
					                  </select>
					                </div>
				            	</div>
							</div>
					    </form>
			            
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table width="100%" class="table table-bordered table-striped my-datatable">
		              		<thead id="table_header">
				                <?php
				                  	echo "<tr>";
					                  	echo "<th>Jenis</th>";
					                  	$kolom = 0;
					                  	foreach($reporth as $dth){
					                  		echo "<th class='text-center'>".$dth->parameter."</th>";
					                  		$kolom += 1;
					                  	}
				                  	echo "</tr>";
				                ?>									
				            </thead>
			              	<tbody id="table_body">
				                <?php
				                	foreach($report as $dt){
							            echo "<tr>";
											echo "<td>".$dt->jenis."</td>";
											$parameter = explode(';', $dt->value);
											for ($i=0; $i < $kolom; $i++) { 
												echo "<td align='right'>".number_format($parameter[$i],2,",",".")."</td>";
											}
							            echo "</tr>";
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
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_bpa_vs_sample.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<style>
.small-box .icon{
    top: -13px;
}
</style>
