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
					    <form method="POST" id="filterform" action="<?php echo base_url() ?>she/report/limbahudara/bpaemisiudara" class="filter-bpaemisiudara" role="form">
			              	<div class="col-md-12" style="margin-top: 20px;">
				              	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Pabrik :</label>
					                  <select name="filterpabrik" id="filterpabrik" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih </option>
					                    <?php
					                      foreach ($pabrik as $pabrik) {
					                      	if($pabrik->id_pabrik == $filterpabrik){
					                      		echo $filterpabrik;
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
					                  <label>Jenis :</label>
					                  <select name="filterjenis" id="filterjenis" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih </option>
					                    <?php
					                      foreach ($jenis as $jenis) {
					                      	if($jenis->id == $filterjenis){
					                      		echo $filterjenis;
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
					                    <option value="" selected> Silahkan Pilih </option>
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
					                  	echo "<th class='text-center' rowspan='2'>Parameter</th>";
					                  	echo "<th class class='text-center'='text-center' rowspan='2'>Jenis</th>";
					                  	echo "<th class='text-center' colspan='2'>Identitas Sampel S1</th>";
					                  	echo "<th class='text-center' colspan='3'>Variable S1</th>";
					                  	echo "<th class='text-center' colspan='2'>Identitas Sampel S2</th>";
					                  	echo "<th class='text-center' colspan='3'>Variable S2</th>";
					                  	echo "<th class='text-center' colspan='2'>Other Variable</th>";
					                  	echo "<th class='text-center' colspan='2'>Formula</th>";
				                  	echo "</tr>";
				                  	echo "<tr>";
					                  	echo "<th class='text-center'>Tanggal Sampling</th>";
					                  	echo "<th class='text-center'>Tanggal Analisa</th>";
					                  	echo "<th class='text-center'>Kriteria Baku Mutu Hasil Uji</th>";
					                  	echo "<th class='text-center'>Hasil Uji (mg/m<sup>3</sup>)</th>";
					                  	echo "<th class='text-center'>Laju Alir (m<sup>3</sup>/detik)</th>";
					                  	echo "<th class='text-center'>Tanggal Sampling</th>";
					                  	echo "<th class='text-center'>Tanggal Analisa</th>";
					                  	echo "<th class='text-center'>Kriteria Baku Mutu Hasil Uji</th>";
					                  	echo "<th class='text-center'>Hasil Uji (mg/m<sup>3</sup>)</th>";
					                  	echo "<th class='text-center'>Laju Alir (m<sup>3</sup>/detik)</th>";
					                  	echo "<th class='text-center'>Jam Operasi (jam/tahun)</th>";
					                  	echo "<th class='text-center'>Frek. Pemantauan</th>";
					                  	echo "<th class='text-center'>Beban Pencemaran Aktual (BPA) - ton/tahun</th>";
					                  	echo "<th class='text-center'>Beban Pencemaran Tahunan - ton/tahun</th>";
				                  	echo "</tr>";
				                ?>									
				            </thead>
			              	<tbody id="table_body">
				                <?php
				                	foreach($report as $dt){
							            echo "<tr>";
											echo "<td>".$dt->parameter."</td>";
											echo "<td>".$dt->jenis."</td>";
											echo "<td align='center'>".$this->generate->generateDateFormat($dt->s1_tgl_sampling)."</td>";
											echo "<td align='center'>".$this->generate->generateDateFormat($dt->s1_tgl_analisa)."</td>";
											echo "<td align='right'>".number_format($dt->s1_bakumutu_hasilujilimit,2,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->s1_hasil_uji,2,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->s1_laju_air,2,",",".")."</td>";
											echo "<td align='center'>".$this->generate->generateDateFormat($dt->s2_tgl_sampling)."</td>";
											echo "<td align='center'>".$this->generate->generateDateFormat($dt->s2_tgl_analisa)."</td>";
											echo "<td align='right'>".number_format($dt->s2_bakumutu_hasilujilimit,2,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->s2_hasil_uji,2,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->s2_laju_air,2,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->jam_operasi,2,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->FREK,2,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->BPA,2,",",".")."</td>";
											echo "<td align='right'>".number_format($dt->BPT,2,",",".")."</td>";
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
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_bpa_emisiudara.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<style>
.small-box .icon{
    top: -13px;
}
</style>
