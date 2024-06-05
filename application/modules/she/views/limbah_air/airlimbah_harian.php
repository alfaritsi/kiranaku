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
<style type="text/css">
  .disabled.day {
    opacity: 0.90;
    filter: alpha(opacity=90);
    background-color: lightgrey !important;
    color: black !important;

  }
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>

	            		<div class="clearfix"></div>

		            	<div class="col-md-2" style="margin-top: 20px;">
			                <div class="form-group">
			                  <label>Kapasitas IPAL :</label>
			                  <?php 
			                  	$ipal = "";
			                  	if(!empty($limbah_air_harian_ipal)) {
			                  		$ipal = number_format($limbah_air_harian_ipal[0]->kapasitas_ipal,2,",","."); 
			                  	}
			                  ?>
			                  <input type="text" class="form-control" id="ipal" name="ipal" value="<?php echo $ipal; ?>" readonly>
			                </div>
		            	</div>

					    <form class="filter-airlimbah_harian" id='filterform' role="form" method="POST" action="<?php echo base_url() ?>she/transaction/limbahair/harian">
			              	<div class="col-md-7" style="margin-top: 20px;">
				              	<div class="col-md-4">
					                <div class="form-group">
					                  <label>Pabrik :</label>
					                  <select name="filterpabrik" id="filterpabrik" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> </option>
					                    <?php
					                      foreach ($pabrik as $keyo => $pabrik1) {
					                        if($filterpabrik == $pabrik1->id_pabrik){
					                        	$selected = "selected";
					                        }else{
						                        $selected = "";
					                      	}
				                        	echo "<option value='".$pabrik1->id_pabrik."' ".$selected.">".$pabrik1->nama." (".$pabrik1->kode.")</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
				            	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Periode :</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
					                  	<input type="text" class="form-control monthPicker" placeholder="mm.yyyy" id="filterperiode" name="filterperiode" value="<?php echo $filterperiode; ?>" readonly required onchange="filtersubmit()">
					                  </div>
					                </div>
				            	</div>
				            	<div class="col-md-4">
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
								<button type="button" class="btn btn-md btn-primary" id="add_button" data-toggle="modal" data-target="#modal-form"><i class="fa fa-plus"></i> Tambah Data</button>
								<?php 
								if(base64_decode($this->session->userdata("-ho-"))=='y'){
									echo'<button type="button" class="btn btn-info" id="imp_button">Import Excel</button>';	
								}
								?>
								
							</div>					
							<!--
			                <div class="form-group">
					            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal-form">
					              <i class="fa fa-plus"></i> Tambah Data
					            </button>
			                </div>
							-->	
		            	</div>
			            
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<!--<table width="100%" class="table table-bordered table-striped">-->
						<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
						        <tr>
									<th rowspan="3" class="text-center">Tanggal</th>          
									<th rowspan="3" class="text-center">Produksi SIR(Ton)</th>          
									<th rowspan="3" class="text-center">Debit Inlet</th>          
									<th colspan="6" class="text-center">Segmen Bak Aerasi</th>
									<th colspan="2" class="text-center">Segmen Denitrifikasi</th>
									<th colspan="2" class="text-center">Saluran Lumpur Balik</th>
									<th colspan="3" class="text-center">Outlet IPAL</th>
									<th colspan="2" class="text-center">Bak Indikator</th>        
									<th rowspan="3" class="text-center">Debit Outlet(M3/Ton)</th>
									<th rowspan="3" class="text-center"></th>
						        </tr>
								<tr>
									<!-- Segmen Bak Aerasi -->
									<th colspan="2" class="text-center">DO (mg/l)</th>
									<th colspan="2" class="text-center">SV 30 (mg/l)</th>
									<th colspan="2" class="text-center">pH</th>
									<!-- Denitrifikasi -->
									<th colspan="2" class="text-center">DO (mg/l)</th>
									<!-- Lumpur balik -->
									<th colspan="2" class="text-center">SV 30 (mg/l)</th>
									<!-- Outlet Ipal -->
									<th class="text-center">Debit (m3)</th>
									<th colspan="2" class="text-center">pH</th>
									<!-- Bak Indikator -->
									<th colspan="2" class="text-center">Transparansi(cm)</th> 
								</tr>
								<tr>
									<!-- Segmen Bak Aerasi -->
									<!-- DO -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- SV -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- PH -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- Denitrifikasi -->
									<!-- DO -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- Lumpur balik -->
									<!-- SV -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- Outlet Ipal -->          
									<!-- Debit -->
									<th class="text-center">Hasil</th>
									<!-- PH -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
									<!-- Bak Indikator -->
									<!-- Transparansi -->
									<th class="text-center">Standar</th>
									<th class="text-center">Hasil</th>
								</tr>
				            </thead>
			              	<tbody id="table_trx">
				                <?php
					                foreach($limbah_air_harian as $dt){
					                  echo "<tr>";
					                  echo "<td>".$this->generate->generateDateFormat($dt->tanggal)."</td>";
									  echo "<td align='right'>".number_format($dt->produksi_sir,2,",",".")."</td>";
									  echo "<td align='right'>".number_format($dt->debit_harian,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s1."</td>";
                    				  echo "<td align='right' ".$dt->red_texth1.">".number_format($dt->sba_do,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s2."</td>";
                    				  echo "<td align='right' ".$dt->red_texth2.">".number_format($dt->sba_sv,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s3."</td>";
                    				  echo "<td align='right' ".$dt->red_texth3.">".number_format($dt->sba_ph,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s4."</td>";
                    				  echo "<td align='right' ".$dt->red_texth4.">".number_format($dt->sd_do,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s5."</td>";
                    				  echo "<td align='right' ".$dt->red_texth5.">".number_format($dt->slb_sv,2,",",".")."</td>";
                    				  echo "<td align='right'>".number_format($dt->oi_debit,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->s6."</td>";
                    				  echo "<td align='right' ".$dt->red_texth6.">".number_format($dt->oi_ph,2,",",".")."</td>";
					                  echo "<td align='right'>".$dt->s7."</td>";
                    				  echo "<td align='right' ".$dt->red_texth7.">".number_format($dt->bi_transparansi,2,",",".")."</td>";
									  echo "<td align='right'>".number_format($dt->satuan_produksi,2,",",".")."</td>";
					                  echo "<td align='center'>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
											if($dt->na == null){ 
												$datetime1 	= new DateTime($dt->tanggal);
												$datetime2 	= new DateTime(date('Y-m-d'));
												$interval 	= $datetime1->diff($datetime2);
												$diff   	= $interval->days;
												if($diff<=7){
													echo "<li><a href='#' class='edit' data-edit='".$dt->id."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
												}
					                            echo"<li><a href='#' class='delete' data-delete='".$dt->id."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
											}
											if($dt->na != null){
												echo "<li><a href='#' class='set_active-kategori' data-activate='".$dt->id."'><i class='fa fa-check'></i> Set Aktif</a></li>";
											}
					                      echo "</ul>
					                          </div>
					                        </td>";
					                  echo "</tr>";
					                }
								foreach($limbah_air_harian_ipal as $keyipal => $dt2){	
					                  echo "<tr class='danger'>";
											echo "<td>Average</td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td id='avg_sbado' align=right>".number_format($dt2->sba_do_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_sbasv' align=right>".number_format($dt2->sba_sv_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_sbaph' align=right>".number_format($dt2->sba_ph_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_sddo' align=right>".number_format($dt2->sd_do_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_slbsv' align=right>".number_format($dt2->slb_sv_avg,2,",",".")."</td>";
											
											echo "<td id='avg_oidebit' align=right>".number_format($dt2->oi_debit_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_oiph' align=right>".number_format($dt2->oi_ph_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td id='avg_bitrans' align=right>".number_format($dt2->bi_transparansi_avg,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
					                  echo "</tr>";
					                  echo "<tr class='danger'>";
											echo "<td>Total</td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
											echo "<td id='tot_oidebit' align=right>".number_format($dt2->oi_debit_sum,2,",",".")."</td>";
											echo "<td align=right></td>";
											echo "<td align=right></td>";
					                  echo "</tr>";
								}	  
									
				                ?>
			              	</tbody>
			              	<tfoot>
				                <?php
					                // foreach($limbah_air_harian_ipal as $keyipal => $dt2){			              		
										// echo "<tr class='danger'>";
											// echo "<td>Average</td>";
											// echo "<td align=right></td>";
											// echo "<td align=right></td>";
											// echo "<td align=right></td>";
											// echo "<td id='avg_sbado' align=right>".number_format($dt2->sba_do_avg,2,",",".")."</td>";
											// echo "<td align=right></td>";
											// echo "<td id='avg_sbasv' align=right>".number_format($dt2->sba_sv_avg,2,",",".")."</td>";
											// echo "<td align=right></td>";
											// echo "<td id='avg_sbaph' align=right>".number_format($dt2->sba_ph_avg,2,",",".")."</td>";
											// echo "<td align=right></td>";
											// echo "<td id='avg_sddo' align=right>".number_format($dt2->sd_do_avg,2,",",".")."</td>";
											// echo "<td align=right></td>";
											// echo "<td id='avg_slbsv' align=right>".number_format($dt2->slb_sv_avg,2,",",".")."</td>";
											
											// echo "<td id='avg_oidebit' align=right>".number_format($dt2->oi_debit_avg,2,",",".")."</td>";
											// echo "<td align=right></td>";
											// echo "<td id='avg_oiph' align=right>".number_format($dt2->oi_ph_avg,2,",",".")."</td>";
											// echo "<td align=right></td>";
											// echo "<td id='avg_bitrans' align=right>".number_format($dt2->bi_transparansi_avg,2,",",".")."</td>";
											// echo "<td align=right></td>";
											// echo "<td align=right></td>";
										// echo "</tr>";
										// echo "<tr class='danger'>";
											// echo "<td>Total</td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td></td>";
											// echo "<td id='tot_oidebit' align=right>".number_format($dt2->oi_debit_sum,2,",",".")."</td>";
											// echo "<td colspan='6'></td>";
										// echo "</tr>";
					                // }
				                ?>
			              	</tfoot>
			            </table>
			        </div>
				</div>
			</div>
		</div>

	    <!-- Modal -->
	    <div class="modal fade" id="modal-form">
	      <div class="modal-dialog" style="width:900px;">
	        <form role="form" class="form-airlimbah_harian">
	          <div class="modal-content">
	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	              <h4 class="modal-title"> <i class="fa fa-plus"></i> Tambah Data </h4>
	            </div>
	            <div class="modal-body" style="min-height:200px;">

	              <div class="col-md-12">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Air Limbah Harian</label>
	                </div>
	              </div>
	              <div class="col-md-4">
	                <div class="form-group">
	                  <label>Pabrik :</label>
	                  <select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" required>
	                    <option value="" selected> </option>
	                    <?php
	                      foreach ($pabrik as $key => $pabrik2) {
	                        echo "<option value='".$pabrik2->id_pabrik."'>".$pabrik2->nama."</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Tanggal :</label>
	                  <div class="input-group date">
	                    <div class="input-group-addon">
	                      <i class="fa fa-calendar"></i>
	                    </div>
	                  	<input type="text" name="tanggal" id="tanggal" style="width:100%;height:32px;padding:10px;" class="datePicker_7" readonly required>
	                  </div>
	                </div>
	              </div>
	              <div class="col-md-4">
	                <div class="form-group">
	                  <label>Kategori :</label>
	                  <select name="kategori" id="kategori" class="form-control select2" style="width: 100%;" required>
	                    <option value="" selected> </option>
	                    <?php
	                      foreach ($kategori as $key => $dt) {
	                        echo "<option value='".$dt->id."'>".$dt->kategori."</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	              </div>

	              <div class="clearfix"></div>

	              <div class="col-md-6" style="margin-top: 20px;">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Segmen Bak Aerasi</label>
	                </div>
		              <div class="col-md-4">
		                <div class="form-group">
		                  <label>DO (mg/L) :</label>
		                  <input type="text" name="bakaerasi_do" id="bakaerasi_do" style="width:100%;height:32px;padding:10px;text-align:right;" required>
		                </div>
		              </div>
		              <div class="col-md-4">
		                <div class="form-group">
		                  <label>SV 30 (mg/L) :</label>
		                  <input type="text" name="bakaerasi_sv" id="bakaerasi_sv" style="width:100%;height:32px;padding:10px;text-align:right;" required>
		                </div>
		              </div>
		              <div class="col-md-4">
		                <div class="form-group">
		                  <label>pH :</label>
		                  <input type="number" min="0" max="14" name="bakaerasi_ph" id="bakaerasi_ph" style="width:100%;height:32px;padding:10px;text-align:right;" required>
						  
		                </div>
		              </div>
              	  </div>
	              
	              <div class="col-md-6" style="margin-top: 20px;">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Segmen Denitrifikasi</label>
	                </div>
		              <div class="col-md-6">
		                <div class="form-group">
		                  <label>DO (mg/L) :</label>
		                  <input type="text" name="denitrifikasi_do" id="denitrifikasi_do" style="width:100%;height:32px;padding:10px;text-align:right;" required>
						  
		                </div>
		              </div>
	              </div>

	              <div class="col-md-6" style="margin-top: 20px;">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Saluran Lumpur Balik</label>
	                </div>
		              <div class="col-md-6">
		                <div class="form-group">
		                  <label>SV 30 (mg/L) :</label>
		                  <input type="text" name="lumpurbalik_sv" id="lumpurbalik_sv" style="width:100%;height:32px;padding:10px;text-align:right;" required>
		                </div>
		              </div>
	              </div>

	              <div class="col-md-6" style="margin-top: 20px;">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Outlet IPAL</label>
	                </div>
		              <div class="col-md-6">
		                <div class="form-group">
		                  <label>Debit (m3) :</label>
		                  <input type="text" name="ipal_debit" id="ipal_debit" style="width:100%;height:32px;padding:10px;text-align:right;" required>
		                </div>
		              </div>
		              <div class="col-md-6">
		                <div class="form-group">
		                  <label>pH :</label>
		                  <input type="number" min="0" max="14" name="ipal_ph" id="ipal_ph" style="width:100%;height:32px;padding:10px;text-align:right;" required>
						  
		                </div>
		              </div>
		              <div class="col-md-6">
		                <div class="form-group">
		                  <label>Standar Debit (m3) :</label>
		                  <input type="text" name="ipal_debit_standar" id="ipal_debit_standar" style="width:100%;height:32px;padding:10px;text-align:right;" required>
		                </div>
		              </div>
					  
		              <div class="col-md-6" id="show_debit"  style="display: none">
		                <div class="form-group">
		                  <label>Debit / Satuan Produksi :</label>
		                  <input type="text" name="satuan_produksi" id="satuan_produksi" style="width:100%;height:32px;padding:10px;text-align:right;" readonly>
		                </div>
		              </div>
					  
	              </div>

	              <div class="col-md-6" style="margin-top: 20px;">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Bak Indikator</label>
	                </div>
		              <div class="col-md-6">
		                <div class="form-group">
		                  <label>Transparansi (cm) :</label>
		                  <input type="text" name="bi_trans" id="bi_trans" style="width:100%;height:32px;padding:10px;text-align:right;" required>
		                </div>
		              </div>
	              </div>
	              <div class="col-md-6" style="margin-top: 20px;">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Inlet IPAL</label>
	                </div>
		              <div class="col-md-6">
		                <div class="form-group">
		                  <label>Debit (m3/hari) :</label>
		                  <input type="text" name="debit_harian" id="debit_harian" style="width:100%;height:32px;padding:10px;text-align:right;" required>
		                </div>
		              </div>
	              </div>
	              <div class="col-md-6" style="margin-top: 20px;">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Produksi</label>
	                </div>
		              <div class="col-md-6">
		                <div class="form-group">
		                  <label>Produksi SIR (Ton) :</label>
		                  <input type="text" name="produksi_sir" id="produksi_sir" style="width:100%;height:32px;padding:10px;text-align:right;" readonly>
		                </div>
		              </div>
	              </div>
				  
				  
	            </div>

	            <div class="clearfix"></div>

	            <div class="modal-footer">
	              <input type="hidden" name="id" id="id" style="width:100%">
	              <button type="submit" name="action_btn" class="btn btn-primary">Save</button>
	            </div>
	          </div>
	        </form>
	        <!-- /.modal-content -->
	      </div>
	      <!-- /.modal-dialog -->
	    </div>
	    <!-- /.modal -->

			<!--modal imp-->
			<div class="modal fade" id="imp_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-mg" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="modal-content">
								<form role="form" class="form-transaksi-harian-imp">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title">Import Data Excel</h4>
									</div>
									<div class="modal-body">
										<div class="form-group">
											<div class="row">
												<div class="col-xs-12">
													<label for="file_excel">Upload File Excel</label>
													<input type="file" class="form-control" name="file_excel" id="file_excel" required>
												</div>
											</div>
										</div>	
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-primary" name="action_btn_imp">Import</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>	
			</div>


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/she/transaction/airlimbah_harian.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>
