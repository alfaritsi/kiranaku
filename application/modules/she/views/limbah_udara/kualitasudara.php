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
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>

					    <form class="filter-kualitasudara" id="filterform" role="form" method="POST" action="<?php echo base_url() ?>she/transaction/limbahudara/kualitas">
			              	<div class="col-md-12" style="margin-top: 20px;">
				              	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Pabrik :</label>
					                  <select name="filterpabrik" id="filterpabrik" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih</option>
					                    <?php
					                      foreach ($pabrik as $keypabrik1 => $pabrik1) {
					                        if($pabrik1->id_pabrik == $filterpabrik){
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
				              	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Kategori :</label>
					                  <select name="filterkategori" id="filterkategori" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih</option>
					                    <?php
					                      foreach ($kategori as $keykat1 => $kat1) {
					                        if($kat1->id == $filterkategori){
					                        	$selected = "selected";
					                        	echo "<option value='".$kat1->id."' selected>".$kat1->kategori."</option>";
					                        }else{
					                        	$selected = "";
					                        }
					                        echo "<option value='".$kat1->id."' ".$selected.">".$kat1->kategori."</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
				              	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Jenis :</label>
					                  <select name="filterjenis" id="filterjenis" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih</option>
					                    <?php
					                      foreach ($jenis as $keyjenis1 => $jenis1) {
					                        if($jenis1->id == $filterjenis){
					                        	$selected = "selected";
					                        }else{
					                        	$selected = "";
					                        }
					                        echo "<option value='".$jenis1->id."' ".$selected.">".$jenis1->jenis."</option>";
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
					                  	<input type="text" class="form-control monthPicker" style="padding: 5px;" placeholder="mm-yyyy" id="date" name="from" value="<?php echo $from; ?>" readonly required onchange="filtersubmit()">
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
					                  	<input type="text" class="form-control monthPicker" style="padding: 5px;" placeholder="mm-yyyy" id="to" name="to" value="<?php echo $to; ?>" readonly required onchange="filtersubmit()">
					                  </div>
					                </div>
				            	</div>
							</div>
					    </form>

		            	<div class="col-md-2 pull-right" style="margin-top: 20px;">
			                <div class="form-group">
					            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal-form">
					              <i class="fa fa-plus"></i> Tambah Data
					            </button>
			                </div>
		            	</div>
			            
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table id="tablelist" width="100%" class="table table-bordered table-striped my-datatable">
		              		<thead>
						        <tr>
									<th class="text-center">Pabrik</th>          
									<th class="text-center">Tgl. Sampling</th>          
									<th class="text-center">Tgl. Analisa</th>          
									<th class="text-center">Kategori</th>          
									<th class="text-center">Jenis</th>          
									<th class="text-center">Parameter</th>          
									<th class="text-center">Hasil Uji</th>          
									<th class="text-center">Laju Air</th>          
									<th class="text-center">Jam Operasi / Tahun</th>          
									<th width="1px"></th>          
									<th width="1px"></th>          
						        </tr>
				            </thead>
			              	<tbody id="table_trx">
				                <?php
					                foreach($limbah_udara as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->nama_pabrik." (".$dt->kode_pabrik.")</td>";
					                  echo "<td align='center'>".$this->generate->generateDateFormat($dt->tanggal_sampling)."</td>";
					                  echo "<td align='center'>".$this->generate->generateDateFormat($dt->tanggal_analisa)."</td>";
					                  echo "<td>".$dt->kategori."</td>";
					                  echo "<td>".$dt->jenis."</td>";
					                  echo "<td>".$dt->parameter."</td>";
					                  echo "<td align='right'>".$dt->hasil_uji."</td>";
					                  echo "<td align='right'>".$dt->laju_air."</td>";
					                  echo "<td align='right'>".$dt->jam_operasi."</td>";
					                  echo "<td align='center'><a title='Lihat file lampiran 1' target='_blank' href='".base_url().$dt->lampiran."'><i class='fa fa-download'></i></a></td>";
					                  echo "<td>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
					                      if($dt->na == null){ 
												$datetime1 	= new DateTime($dt->tanggal_buat);
												$datetime2 	= new DateTime(date('Y-m-d'));
												$interval 	= $datetime1->diff($datetime2);
												$diff   	= $interval->days;
												if($diff<=30){
													echo "<li><a href='#' class='edit' data-edit='".$dt->id."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
												}
												echo "<li><a href='#' class='delete' data-delete='".$dt->id."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
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
	      <div class="modal-dialog" style="width:900px;">
	        <form role="form" class="kualitasudara-form">
				<div class="modal-content">
		            <div class="modal-header">
		              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                <span aria-hidden="true">&times;</span></button>
		              <h4 class="modal-title"> <i class="fa fa-plus"></i> Tambah Data </h4>
		            </div>
		            <div class="modal-body" style="min-height:200px;">
		              	<div class="col-md-5">
			                <div class="form-group">
			                  <label>Pabrik :</label>
			                  <select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" required>
			                    <option value="" selected> Silahkan Pilih</option>
			                    <?php
			                      foreach ($pabrik as $keypabrik2 => $pabrik2) {
			                        echo "<option value='".$pabrik2->id_pabrik."'>".$pabrik2->nama." (".$pabrik2->kode.")</option>";
			                      }
			                    ?>
			                  </select>
			                </div>
			                <div class="form-group">
			                  <label>Kategori :</label>
			                  <select id="kategori" name = "kategori" class="form-control select2" style="width: 100%;" required>
			                    <option value="" selected> Silahkan Pilih</option>
			                    <?php
			                      foreach ($kategori as $keykat2 => $kat2) {
			                        echo "<option value='".$kat2->id."'>".$kat2->kategori."</option>";
			                      }
			                    ?>
			                  </select>
			                </div>
			                <div class="form-group">
			                  <label>Jenis :</label>
			                  <select id="jenis" name="jenis" class="form-control select2" style="width: 100%;" required>
			                    <option value="" selected>Silahkan Pilih</option>
			                    <?php
			                      foreach ($jenis as $keyjenis2 => $jenis2) {
			                        echo "<option value='".$jenis2->id."'>".$jenis2->jenis."</option>";
			                      }
			                    ?>
			                  </select>
			                </div>
			                <div class="form-group">
			                  <label>Tanggal Sampling :</label>
			                  <div class="input-group date">
			                    <div class="input-group-addon">
			                      <i class="fa fa-calendar"></i>
			                    </div>
			                  	<input type="text" name="tglsampling" id="tglsampling" class="form-control datePicker" style="width:100%; height:32px;" readonly required>
			                  </div>
			                </div>
			                <div class="form-group">
			                  <label>Tanggal Analisa :</label>
			                  <div class="input-group date">
			                    <div class="input-group-addon">
			                      <i class="fa fa-calendar"></i>
			                    </div>
			                  	<input type="text" name="tglanalisa" id="tglanalisa" class="form-control datePicker" style="width:100%; height:32px;" readonly required>
			                  </div>
			                </div>

			                <div id="emisiform"></div>

			                <div class="form-group">
			                  <label>Lampiran :</label>
			                  <input type="file" name="lampiran1" id="lampiran1" style="width:100%">
			                </div>
		              	</div>

						<div class="col-md-7">
							<div class="col-md-12" style="margin-top: 20px;">
								<div class="form-group">
								  	<label for="limbah_b3" class="list-group-item list-group-item-info text-center" style="width: 100%;">Parameter Hasil Uji</label>
									<table id='tablelist' width="100%" border="2">
									  	<thead>
									  		<tr>
									  			<th class="text-center">Parameter</th>
									  			<th class="text-center">Hasil Uji (mg/l)</th>
									  		</tr>
									  	</thead>
									  	<tbody id="parameter_hasiluji">
									  	</tbody>
									</table>
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


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/she/transaction/kualitasudara.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>
