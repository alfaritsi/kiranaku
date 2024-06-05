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

		            	<?php if($filterpabrik != "" && $filterperiode != "" && $filtertahun != ""){ ?>

			            	<div class="col-md-1 pull-right" style="margin-top: 30px">	            		
							    <form method="POST" target="_blank" action="<?php echo base_url() ?>she/report/pdf/neracaB3" class="filter-neracalimbahb3" role="form">
					            	<div class="col-md-1">
						                <label></label>
					            		<button id="print-btn" type="submit" name="printaction_btn" class="btn btn-primary">Print &nbsp; <i class="fa fa-print"></i></button>
					            	</div>
									<input type="hidden" id="printtahun" name="printtahun" value="<?php echo $filtertahun; ?>" readonly required>
									<input type="hidden" id="printperiode" name="printperiode" value="<?php echo $filterperiode; ?>" readonly required>
									<input type="hidden" id="printpabrik" name="printpabrik" value="<?php echo $filterpabrik; ?>" readonly required>
							    </form>
							</div>
			            <?php
			        	}
			        	?>

					    <form method="POST" id="filterform" action="<?php echo base_url() ?>she/report/limbahb3/neraca" class="filter-neracalimbahb3" role="form">
			              	<div class="col-md-11" style="margin-top: 20px;">
				              	<div class="col-md-4">
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
					                  <?php echo ($filterperiode == '01-03')?'selected':''; ?>
					                  <select name="filterperiode" id="filterperiode" class="form-control" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih</option>
					                    <?php
					                    	$selected = ($filterperiode == '01-03')?'selected':'';
											echo "<option value='01-03' ".$selected.">Jan - Mar</option>";
					                    	$selected = ($filterperiode == '04-06')?'selected':'';
											echo "<option value='04-06' ".$selected.">Apr - Jun</option>";
					                    	$selected = ($filterperiode == '07-09')?'selected':'';
											echo "<option value='07-09' ".$selected.">Jul - Sep</option>";
					                    	$selected = ($filterperiode == '10-12')?'selected':'';
											echo "<option value='10-12' ".$selected.">Oct - Dec</option>";
					                    ?>
					                  </select>
					                </div>
				            	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Tahun :</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
					                  	<input type="text" class="form-control monthPicker" style="padding:10px;" placeholder="yyyy" id="filtertahun" name="filtertahun" value="<?php echo $filtertahun; ?>" readonly required onchange="filtersubmit()">
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
					                  	echo "<th class='text-center'>No</th>";
					                  	echo "<th class='text-center'>Jenis Limbah B3</th>";
					                  	echo "<th class='text-center'>Kode Limbah</th>";
					                  	echo "<th class='text-center'>Jumlah (Ton)</th>";
					                  	// echo "<th class='text-center'>Jumlah Awal</th>";
					                  	// echo "<th class='text-center'>Satuan</th>";
					                  	// echo "<th class='text-center'>Faktor Konversi</th>";
					                  	echo "<th class='text-center' colspan='3'>Catatan</th>";
				                  	echo "</tr>";
				                ?>									
				            </thead>
			              	<tbody id="table_body">
				                <?php
				                	$total_A = 0;
				                	foreach($report1 as $key => $dt){
							            $no = $key + 1;
										$jumlah = ($dt->jumlah)>0?$dt->jumlah:0;
							            $total_A += $jumlah;
							            $jumlah_arr[] = $jumlah;
							            echo "<tr>";
											echo "<td align='center'>".$no."</td>";
											echo "<td>".$dt->limbah."</td>";
											echo "<td>".$dt->kode_limbah."</td>";
											echo "<td align='right'>".number_format($jumlah,4)."</td>";
											// echo "<td align='right'>".number_format($dt->jumlah,4,",",".")."</td>";
											// echo "<td align='right'>".number_format($dt->jumlah_awal,4,",",".")."</td>";
											// echo "<td align='center'>".$dt->satuan."</td>";
											// echo "<td align='right'>".number_format($dt->fkonv,5,",",".")."</td>";
											echo "<td colspan='3'>-</td>";
							            echo "</tr>";
						        	}
				                  	echo "<tr>";
					                  	echo "<td></td>";
					                  	echo "<td align='center'><strong>TOTAL (A)</strong></td>";
					                  	echo "<td></td>";
					                  	echo "<td align='right'><strong>".number_format($total_A,4)."</strong></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
									if(@$perijinan[0]->tanggal_kadaluarsa!=NULL){
										$cek_ada		= "V";	
										$cek_tidak_ada	= "-";	
									}else{
										$cek_ada		= "-";	
										$cek_tidak_ada	= "V";	
									}
				                  	echo "<tr>";
					                  	echo "<td align='center' rowspan='2'><strong>No</strong></td>";
					                  	echo "<td align='center' colspan='2' rowspan='2'><strong>Pengelolaan</strong></td>";
					                  	echo "<td align='center' rowspan='2'><strong>Jumlah (Ton)</strong></td>";
					                  	echo "<td align='center' rowspan='2'><strong>Jenis Limbah yang Dikelola</strong></td>";
					                  	echo "<td align='center' colspan='2'><strong>Perizinan Limbah B3</strong></td>";
				                  	echo "</tr>";
				                  	echo "<tr>";
					                  	echo "<td align='center'><strong>Ada</strong></td>";
					                  	echo "<td align='center'><strong>Tidak Ada</strong></td>";
				                  	echo "</tr>";
				                  	echo "<tr>";
					                  	echo "<td align='center'>1</td>";
					                  	echo "<td colspan='2'>Disimpan di TPS</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td align='center'>$cek_ada</td>";
					                  	echo "<td align='center'>$cek_tidak_ada</td>";
				                  	echo "</tr>";

				                  	$total_B1 = 0;
				                  	foreach($report2 as $key2 => $dt2){
					                  	// $total_B1 += ($jumlah_arr[$key2] - $dt2->jumlahout);
					                  	// $total_B1 += ($jumlah_arr[$key2] - $dt2->jumlahsimpan);
										$jumlahsimpan = $dt2->jumlah_masuk - $dt2->jumlah_keluar;
					                  	$total_B1 += ($jumlahsimpan > 0 )?$jumlahsimpan:?$jumlahsimpan*-1;
					                  	echo "<tr>";
						                  	echo "<td></td>";
						                  	echo "<td align='center' colspan='2'></td>";
											// echo "<td align='right'>".number_format($dt2->jumlahsimpan,4,",",".")."</td>";
											echo "<td align='right'>".number_format($jumlahsimpan,4)."</td>";
						                  	echo "<td>".$dt2->limbah."</td>";
						                  	echo "<td></td>";
						                  	echo "<td></td>";
					                  	echo "</tr>";
					                }

				                  	echo "<tr>";
					                  	echo "<td align='center'>2</td>";
					                  	echo "<td colspan='2'>Dimanfaatkan</td>";
					                  	echo "<td>-</td>";
					                  	echo "<td>-</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
				                  	echo "<tr>";
					                  	echo "<td align='center'>3</td>";
					                  	echo "<td colspan='2'>Diolah</td>";
					                  	echo "<td>-</td>";
					                  	echo "<td>-</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
				                  	echo "<tr>";
					                  	echo "<td align='center'>4</td>";
					                  	echo "<td colspan='2'>Ditimbun</td>";
					                  	echo "<td>-</td>";
					                  	echo "<td>-</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
				                  	echo "<tr>";
					                  	echo "<td align='center'>5</td>";
					                  	echo "<td colspan='2'>Diserahkan Pihak Ketiga</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";

				                  	$total_B2 = 0;
				                  	foreach($report3 as $key3 => $dt3){
					                  	$izin = ($dt3->lampiran != "")?"Ada":"";
										$jumlah3 = ($dt3->jumlah>0)?$dt3->jumlah:0;
					                  	$total_B2 += $jumlah3;
					                  	echo "<tr>";
						                  	echo "<td></td>";
						                  	echo "<td colspan='2'></td>";
											echo "<td align='right'>".number_format($jumlah3,4)."</td>";
											// echo "<td align='right'>".number_format($dt3->jumlah,4,",",".")."</td>";
						                  	echo "<td>".$dt3->limbah."</td>";
						                  	// echo "<td>".$dt3->manifest."</td>";
						                  	echo "<td>".$izin."</td>";
						                  	echo "<td></td>";
					                  	echo "</tr>";
					                }

				                  	echo "<tr>";
					                  	echo "<td align='center'>6</td>";
					                  	echo "<td colspan='2'>Eksport</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
				                  	echo "<tr>";
					                  	echo "<td align='center'>7</td>";
					                  	echo "<td colspan='2'>Perlakuan Lainya</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
									echo "</tr>";
									$total_B = $total_B1+$total_B2;
				                  	echo "<tr>";
					                  	echo "<td></td>";
					                  	echo "<td colspan='2'><strong>TOTAL (B)</strong></td>";
										echo "<td align='right'><strong>".number_format($total_B,4)."</strong></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
									$total_C = $total_A-$total_B;
				                  	echo "<tr>";
					                  	echo "<td align='center'>8</td>";
					                  	echo "<td colspan='2'>Residu (C)</td>";
					                  	echo "<td>-</strong></td>";
					                  	echo "<td>-</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
				                  	echo "<tr>";
					                  	echo "<td align='center'>9</td>";
					                  	echo "<td colspan='2'>Jumlah limbah belum dikelola (D)</td>";
					                  	echo "<td>-</td>";
					                  	echo "<td>-</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";
				                  	$total_D2 = 0;
				                  	foreach($report4 as $key4 => $dt4){
										//total D2 di nol kan
					                  	// $total_D2 += $dt4->jumlah;
					                  	echo "<tr>";
						                  	echo "<td></td>";
						                  	echo "<td colspan='2'></td>";
											echo "<td>-</td>";
						                  	echo "<td>".$dt4->limbah."</td>";
						                  	echo "<td></td>";
						                  	echo "<td></td>";
					                  	echo "</tr>";
					                }
				                  	echo "<tr>";
					                  	echo "<td></td>";
					                  	echo "<td colspan='2'><strong>Total Jumlah Limbah yang Tersisa (D)</strong></td>";
										echo "<td>-</td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";

				                  	$total_limbah = $total_C+$total_D2;

				                  	$persen = ($total_A == 0)?0:(($total_B - $total_C) / $total_A)*100;
				                  	echo "<tr>";
					                  	echo "<td></td>";
					                  	echo "<td colspan='2'><strong>Kinerja pengelolaan limbah B3 Selama periode penataan</strong></td>";
										echo "<td align='right'><strong>".number_format($persen,2)." %</strong></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
					                  	echo "<td></td>";
				                  	echo "</tr>";

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
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_neracalimbah_b3.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<style>
.small-box .icon{
    top: -13px;
}
</style>
