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
		            	<div class="col-md-8" style="margin-top: 20px;">
			                <div class="form-group">
					            <h3 class="box-title"><strong><?php echo $pabrik[0]->nama.' ('.$pabrik[0]->kode.')'; ?></strong></h3>
			                </div>
		            	</div>
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
							<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal-form">
							  <i class="fa fa-plus"></i> Tambah Data
							</button>
							-->
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				                <tr>
						            <th class="text-center">Kategori</th>
						            <th class="text-center">Parameter</th>
						            <th class="text-center">Lokasi Sampling</th>
						            <th class="text-center">Tanggal Sampling</th>
						            <th class="text-center">Tanggal Analisa</th>
						            <th class="text-center">Baku Mutu Hasil Uji</th>
						            <th class="text-center">Hasil Uji (mg/l)</th>          
						            <th class="text-center">Debit Air (m&#179;/bln)</th>
						            <th class="text-center">Crumbing (ton/bln)</th>
						            <th class="text-center">Baku Mutu Beban Pencemaran</th>
						            <th class="text-center">Beban Pencemaran - kg/ton</th>            
						            <th class="text-center">Beban Pencemaran Aktual - ton/periode</th>
						            <th></th>
						            <th></th>
				                </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($limbah_air_bulanan as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->kategori."</td>";
					                  echo "<td>".$dt->parameter."</td>";
					                  echo "<td>".$dt->lokasi."</td>";
					                  echo "<td>".$this->generate->generateDateFormat($dt->tanggal_sampling)."</td>";
					                  echo "<td>".$this->generate->generateDateFormat($dt->tanggal_analisa)."</td>";
					                  echo "<td align='right'>".number_format($dt->bakumutu_hasilujilimit,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->hasil_uji,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->oi_debit,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->crumbing,2,",",".")."</td>";
					                  echo "<td align='center'>".$dt->bakumutu_bebancemar."</td>";
					                  echo "<td align='right'>".number_format($dt->bp,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->bpa,2,",",".")."</td>";
					                  echo "<td><a class='glyphicon glyphicon-download-alt' href='".base_url().$dt->lampiran."' target='_blank'></a></td>";
					                  echo "<td>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
					                      if($dt->na == null){ 
					                        echo "<li><a href='#' class='edit' data-edit='".$dt->id."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
					                              <li><a href='#' class='delete' data-delete='".$dt->id."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
					                      }
					                      if($dt->na != null){
					                        echo "<li><a href='#' class='set_active-kategori' data-activate='".$dt->id."'><i class='fa fa-check'></i> Set Aktif</a></li>";
					                      }
					                      echo "</ul>
					                          </div>
					                        </td>";
					                  echo "</tr>";
					                }
				                ?>
			              	</tbody>
			            </table>
			        </div>
				</div>
			</div>
		</div>

	    <!-- Modal -->
	    <div class="modal fade" id="modal-form">
	      <div class="modal-dialog" style="width:600px;">
	        <form role="form" class="form-airlimbah_bulanan">
	          <div class="modal-content">
	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	              <h4 class="modal-title"> <i class="fa fa-plus"></i> Tambah Data </h4>
	            </div>
	            <div class="modal-body" style="min-height:200px;">

	              <div class="col-md-6">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Air Limbah Bulanan</label>
	                </div>
	                <div class="form-group">
	                  <label>Pabrik :</label>
	                  <select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" required>
	                    <option value="" selected> </option>
	                    <?php
	                      foreach ($pabrik as $pabrik) {
	                        echo "<option value='".$pabrik->id_pabrik."'>".$pabrik->nama." (".$pabrik->kode.")</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	                <div class="form-group">
	                  <label>Lokasi :</label>
	                  <select name="lokasi" id="lokasi" class="form-control select2" style="width: 100%;" required>
	                    <option value="" selected> </option>
	                    <?php
	                      foreach ($jenis as $jenis) {
	                        echo "<option value='".$jenis->id."'>".$jenis->jenis."</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	                
	                <div class="form-group">
	                  <label>Kategori :</label>
	                  <select name="kategori" id="kategori" class="form-control select2" style="width: 100%;" required>
	                    <!-- <option value="" selected></option> -->
	                    <?php
	                      foreach ($kategori as $dt) {
	                        echo "<option value='".$dt->id."'>".$dt->kategori."</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	                
	                <div class="form-group">
	                  <label>Tgl Sampling :</label>
	                  <div class="input-group date">
	                    <div class="input-group-addon">
	                      <i class="fa fa-calendar"></i>
	                    </div>
	                  	<input type="text" name="tgl_sampling" id="tgl_sampling" class="datePicker" style="width:100%;height:32px;padding:10px;" readonly required>
	                  </div>
	                </div>
	                <div class="form-group">
	                  <label>Tgl Analisa :</label>
	                  <div class="input-group date">
	                    <div class="input-group-addon">
	                      <i class="fa fa-calendar"></i>
	                    </div>
						<div id="div_tgl_analisa">
	                  	<input type="text" name="tgl_analisa" id="tgl_analisa" class="datePicker" style="width:100%;height:32px;padding:10px;" readonly required>
						</div>
	                  </div>
	                </div>
	                <div class="form-group">
	                  <label>Lampiran :</label>
	                  <input type="file" name="lampiran1" id="lampiran1" style="width:100%">
	                </div>
	              </div>

	              <div class="col-md-6">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Parameter - Hasil Uji</label>
	                </div>
	                <div class="form-group">
		           		<table width="50%" class="table-bordered table-striped">
		              		<thead>
				                <tr>
				                	<th>Parameter</th>
				                	<th>Hasil Uji (mg/l)</th>
				                </tr>
				            </thead>
				            <tbody id="table_param">
				            </tbody>
				        </table>

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
								<form role="form" class="form-transaksi-bulanan-imp">
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
<script src="<?php echo base_url() ?>assets/apps/js/she/transaction/airlimbah_bulanan.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->
<style>
.small-box .icon{
    top: -13px;
}
</style>
