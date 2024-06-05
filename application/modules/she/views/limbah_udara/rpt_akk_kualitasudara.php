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
					    <form method="POST" id="filterform" action="<?php echo base_url() ?>she/report/limbahudara/akk" class="filter-akkkualitasudara" role="form">
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
					                  <label>Kategori :</label>
					                  <select name="filterkategori" id="filterkategori" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih </option>
					                    <?php
					                      foreach ($kategori as $kategori) {
					                      	if($kategori->id == $filterkategori){
					                      		$selected = "selected";
					                      	}else{
					                      		$selected = "";
					                      	}
					                        echo "<option value='".$kategori->id."' ".$selected.">".$kategori->kategori."</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
				              	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Jenis :</label>
					                  <select name="filterjenis" id="filterjenis" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih </option>
					                    <?php
					                      foreach ($jenis as $jenis) {
					                      	if($jenis->id == $filterjenis){
					                      		$selected = "selected";
					                      	}else{
					                      		$selected = "";
					                      	}
					                        echo "<option value='".$jenis->id."' ".$selected.">".$jenis->jenis."</option>";
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
					                  	echo "<th class='text-center' rowspan='2'>Kategori</th>";
					                  	echo "<th class='text-center' rowspan='2'>Jenis</th>";
					                  	echo "<th class='text-center' rowspan='2'>Parameter</th>";
					                  	echo "<th class='text-center' colspan='2'>Identitas Sampel (S2 - ".substr($filterperiode, 0, 4).")</th>";
					                  	echo "<th class='text-center' colspan='2'>Variabel (S2 - ".substr($filterperiode, 0, 4).")</th>";
					                  	echo "<th class='text-center' colspan='2'>Identitas Sampel (S1 - ".substr($filterperiode, -4).")</th>";
					                  	echo "<th class='text-center' colspan='2'>Variabel (S1 - ".substr($filterperiode, -4).")</th>";
				                  	echo "</tr>";
				                  	echo "<tr>";
					                  	echo "<th class='text-center'>Tgl Sampling</th>";
					                  	echo "<th class='text-center'>Tgl Analisa</th>";
					                  	echo "<th class='text-center'>Baku Mutu</th>";
					                  	echo "<th class='text-center'>Hasil Uji (mg/m3)</th>";
					                  	echo "<th class='text-center'>Tgl Sampling</th>";
					                  	echo "<th class='text-center'>Tgl Analisa</th>";
					                  	echo "<th class='text-center'>Baku Mutu</th>";
					                  	echo "<th class='text-center'>Hasil Uji (mg/m3)</th>";
				                  	echo "</tr>";
				                ?>									
				            </thead>
			              	<tbody id="table_body">
				                <?php
				                	foreach($report as $dt){
							            echo "<tr>";
											echo "<td>".$dt->kategori."</td>";
											echo "<td>".$dt->jenis."</td>";
											echo "<td>".$dt->parameter."</td>";
											echo "<td align='center'>".$this->generate->generateDateFormat($dt->tgl_sampling_s2)."</td>";
											echo "<td align='center'>".$this->generate->generateDateFormat($dt->tgl_analisa_s2)."</td>";
											echo "<td align='right'>".number_format($dt->BakuMutu_s2,0,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->HasilUji_s2,2,",",".")."</td>";
											echo "<td align='center'>".$this->generate->generateDateFormat($dt->tgl_sampling_s1)."</td>";
											echo "<td align='center'>".$this->generate->generateDateFormat($dt->tgl_analisa_s1)."</td>";
											echo "<td align='right'>".number_format($dt->BakuMutu_s1,0,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->HasilUji_s1,2,",",".")."</td>";
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
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_akk_kualitasudara.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<style>
.small-box .icon{
    top: -13px;
}
</style>
