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
	            		<h3 class="box-title"><strong>Master <?php echo $title; ?></strong></h3>
			            <button type="button" class="btn btn-primary pull-right" onclick="init()" data-toggle="modal" data-target="#modal-form">
			              <i class="fa fa-plus"></i> Tambah Data
			            </button>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
						        <tr>
							        <th class="text-center text-top" rowspan="2">Pabrik</th>
							        <th class="text-center text-top" rowspan="2">Nama Transporter</th>
							        <th class="text-center text-top" rowspan="2">Nama Pengumpul</th>
							        <th class="text-center text-top" rowspan="2">Nama Pemanfaat</th>
							        <th class="text-center text-top" rowspan="2">Exp MoU</th>
									<th class="text-center" colspan="4">Jenis</th>
							        <th class="text-center text-top" rowspan="2"></th>
						        </tr>
						        <tr>
									<th class="text-center">Ijin Pengumpulan</th>
									<th class="text-center">MoU Pihak Ketiga</th>
									<th class="text-center">Rekom Angkut KLHK</th>
									<th class="text-center">Ijin Angkut DirjenHubDar</th>
						        </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($dtvendor as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->pabrik."</td>";
					                  echo "<td>".$dt->nama_vendor."</td>";
					                  echo "<td>".$dt->nama_pengumpul."</td>";
					                  echo "<td>".$dt->nama_pemanfaat."</td>";
					                  echo "<td>".$this->generate->generateDateFormat($dt->kumpul_expdate);
					                  // if(!empty($dt->file_ikumpul)){
					                  // 		echo "&nbsp; <a title='Lihat file lampiran izin pengumpul' class='glyphicon glyphicon-download-alt' href='".base_url().$dt->file_ikumpul."' target='_blank'></a>";
					                  // }
					                  echo "</td>";
					                  echo "<td>".$dt->kumpul."</td>";
					                  echo "<td>".$dt->pk."</td>";
					                  echo "<td>".$dt->klhk."</td>";
					                  echo "<td>".$dt->dhd."</td>";
					                  echo "<td align='center'>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
					                        echo "<li><a href='#' class='detail' data-detail='".$dt->id."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-folder-open-o'></i> Detail</a></li>";
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
	      	<div class="modal-dialog" style="width:800px;">
	        	<form role="form" class="form-master-vendor">
	          		<div class="modal-content">
	            		<div class="modal-header">
	              			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                		<span aria-hidden="true">&times;</span></button>
	              			<h4 class="modal-title"> <i class="fa fa-plus"></i> Tambah Data Transporter </h4>
	            		</div>
	            		<div class="modal-body" style="min-height:300px;">

						    <ul class="nav nav-tabs">
						      <li class="active"><a href="#tab_1" data-toggle="tab">Ijin Pengumpulan</a></li>
						      <li><a href="#tab_2" data-toggle="tab">MoU dengan Pihak Ketiga</a></li>
						      <li><a href="#tab_3" data-toggle="tab">Rekom Angkut KLHK</a></li>
						      <li><a href="#tab_4" data-toggle="tab">Ijin Angkut dirjenhub</a></li>
						    </ul>

					        <div class="tab-content">
					            <div class="tab-pane active" id="tab_1">
					              	<div class="row" style="margin-top: 40px;">
						              	<div class="col-md-4">
						                	<div class="form-group">
						                  		<label>Pabrik :</label>
						                  		<select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" required>
						                        	<option value=''>Silahkan Pilih</option>";
						                    		<?php
						                      		foreach ($pabrik as $pabrik) {
						                        		echo "<option value='".$pabrik->id_pabrik."'>".$pabrik->nama." (".$pabrik->kode.")</option>";
						                      		}
						                    		?>
						                  		</select>
						                	</div>
						              	</div>
					              		<div class="col-md-4">
					                		<div class="form-group">
					                  			<label>Nama Transporter :</label>
					                  			<select name="vendor" id="vendor" class="form-control select2" style="width: 100%;" required>
					                    		<option value="" selected> </option>
					                  			</select>
					                		</div>
					              		</div>
					              		<div class="col-md-4">
					                		<div class="form-group">
					                  			<label>Kode Transporter :</label>
					                  			<input type="text" name="kodevendor" id="kodevendor" class="init" style="width:100%;height:32px;padding:10px;" readonly required>
					                		</div>
					              		</div>
					              		<div class="clearfix"></div>

					              		<div class="col-md-6">
							                <div class="form-group">
							                  	<label> Nama Pengumpul :</label> &nbsp; &nbsp;
							                  	<label>
							                  		<input type="checkbox" id="chktranspengumpul" name="chktranspengumpul"/> Transporter
							                  	</label>
						                    	<input type="text" name="namapengumpul" id="namapengumpul" class="init" style="width:100%;height:32px;padding:10px;">
							                </div>
							            </div>
					              		<div class="col-md-6">
							                <div class="form-group">
							                  	<label> Nama Pemanfaat :</label> &nbsp; &nbsp;
							                  	<label>
							                  		<input type="checkbox" id="chkpengumpulpemanfaat" name="chkpengumpulpemanfaat"/> Pengumpul
							                  	</label> &nbsp; &nbsp;
							                  	<label>
							                  		<input type="checkbox" id="chktranspemanfaat" name="chktranspemanfaat"/> Transporter
							                  	</label>
						                    	<input type="text" name="namapemanfaat" id="namapemanfaat" class="init" style="width:100%;height:32px;padding:10px;">
							                </div>
							            </div>
							            <div class="clearfix"></div>
					              		<div class="col-md-12">
							                <div class="form-group">
						                  		<label>Jenis Limbah :</label>
					                  			<select name="jenislimbah_pengumpul[]" id="jenislimbah_pengumpul" multiple="multiple" class="form-control select2 init" style="width: 100%;" required>
					                  			</select>
							                </div>
							            </div>
							            <div class="clearfix"></div>

					              		<div id="ijinpengumpul"></div>

					              	</div>
					            </div>
					            <!-- /.tab-pane -->

					            <div class="tab-pane" id="tab_2">
					              	<div class="row" style="margin-top: 40px;">
					              		<div class="col-md-12">
							                <div class="form-group">
						                  		<label>Jenis Limbah :</label>
					                  			<select name="jenislimbah_mou[]" id="jenislimbah_mou" multiple="multiple" class="form-control select2 init" style="width: 100%;" required>
					                  			</select>
							                </div>
							            </div>
							            <div class="clearfix"></div>

					              		<div class="col-md-4">
							                <div class="form-group">
						                  		<label style="width:100%">Expdate MoU :</label>
								                <div class="input-group date">
								                    <div class="input-group-addon">
								                      <i class="fa fa-calendar"></i>
								                    </div>						                  		
							                    	<input type="text" class="datePicker init" name="expmou" id="expmou" onchange="cek_expmou()" style="width:100%;height:32px;padding:10px;" readonly required>
							                    	<div id='div_warning_expmou'></div>
						                  		</div>
						                  	</div>
						                </div>
					              		<div class="col-md-8">
							                <div class="form-group">
							                  	<label>MoU :</label>
							                  	<div id='view_file_ipihak_ketiga'></div>
							                    <input type="file" class="input-sm init" name="file_ipihak_ketiga" id="file_ipihak_ketiga" onchange="cek_expmou()" style="width:100%" required>
						                  	</div>
						                </div>
						                <div class="clearfix"></div>

					              		<div class="col-md-4">
							                <div class="form-group">
						                  		<label>Expdate SP Bebas Pencemaran :</label>
								                <div class="input-group date">
								                    <div class="input-group-addon">
								                      <i class="fa fa-calendar"></i>
								                    </div>	
							                    	<input type="text" class="datePicker init" name="expbebascemar" id="expbebascemar" onchange="cek_expbebascemar()" style="width:100%;height:32px;padding:10px;" readonly required>
							                    	<div id='div_warning_expbebascemar'></div>
						                  		</div>
						                  	</div>
						                </div>
					              		<div class="col-md-8">
							                <div class="form-group">
							                  	<label>Dokumen SP Bebas Pencemaran :</label>
							                  	<div id='view_file_pihak_ketiga_spbp'></div>
							                    <input type="file" class="input-sm init" name="file_pihak_ketiga_spbp" id="file_pihak_ketiga_spbp" onchange="cek_expbebascemar()" style="width:100%" required>
						                  	</div>
						                </div>
						                <div class="clearfix"></div>

					              		<div id="SPBP2"> </div>
					              		<div id="SPBP3"> </div>
					              		<div id="pemanfaat"> </div>
										<div id="pengumpulpemanfaat"> </div>


					              	</div>
					            </div>
					            <!-- /.tab-pane -->


					            <div class="tab-pane" id="tab_3">
					              	<div class="row" style="margin-top: 40px;">
					              		<div class="col-md-12">
							                <div class="form-group">
						                  		<label>Jenis Limbah :</label>
					                  			<select name="jenislimbah_rekom[]" id="jenislimbah_rekom" multiple="multiple" class="form-control select2" style="width: 100%;" required>
					                  			</select>
							                </div>
							            </div>
							            <div class="clearfix"></div>

					              		<div class="col-md-4">
							                <div class="form-group">
						                  		<label style="width:100%">Expdate :</label>
								                <div class="input-group date">
								                    <div class="input-group-addon">
								                      <i class="fa fa-calendar"></i>
								                    </div>						                  		
							                    	<input type="text" class="datePicker init" name="exprekom" id="exprekom" onchange="cek_exprekom()" style="width:100%;height:32px;padding:10px;" readonly required>
							                    	<div id='div_warning_exprekom'></div>
						                  		</div>
						                  	</div>
						                </div>
					              		<div class="col-md-8">
							                <div class="form-group">
							                  	<label>Dokumen Rekom Angkut KLHK :</label>
							                  	<div id='view_file_angkut_klhk'></div>
							                    <input type="file" class="input-sm init" name="file_angkut_klhk" id="file_angkut_klhk" onchange="cek_exprekom()" style="width:100%">
						                  	</div>
						                </div>
						                <div class="clearfix"></div>
					              	</div>
					            </div>
					            <!-- /.tab-pane -->

					            <div class="tab-pane" id="tab_4">
					              	<div class="row" style="margin-top: 40px;">
					              		<div class="col-md-12">
							                <div class="form-group">
						                  		<label>Jenis Limbah :</label>
					                  			<select name="jenislimbah_hubdar[]" id="jenislimbah_hubdar" multiple="multiple" class="form-control select2" style="width: 100%;" required>
					                  			</select>
							                </div>
							            </div>
							            <div class="clearfix"></div>

					              		<div class="col-md-4">
							                <div class="form-group">
						                  		<label style="width:100%">Expdate Ijin Angkut DirjenHubDar :</label>
								                <div class="input-group date">
								                    <div class="input-group-addon">
								                      <i class="fa fa-calendar"></i>
								                    </div>						                  		
							                    	<input type="text" class="datePicker init" name="exphubdar" id="exphubdar" onchange="cek_exphubdar()" style="width:100%;height:32px;padding:10px;" readonly required>
							                    	<div id='div_warning_exphubdar'></div>
						                  		</div>
						                  	</div>
						                </div>
					              		<div class="col-md-8">
							                <div class="form-group">
							                  	<label>Dokumen Ijin Angkut DirjenHubDar :</label>
							                  	<div id='view_file_angkut_dhd'></div>
							                    <input type="file" class="input-sm init" name="file_angkut_dhd" id="file_angkut_dhd" onchange="cek_exphubdar()" style="width:100%">
						                  	</div>
						                </div>
						                <div class="clearfix"></div>

					              		<div class="col-md-4">
							                <div class="form-group">
						                  		<label style="width:100%">Expdate SP Bebas Pencemaran :</label>
								                <div class="input-group date">
								                    <div class="input-group-addon">
								                      <i class="fa fa-calendar"></i>
								                    </div>						                  		
							                    	<input type="text" class="datePicker init" name="exphubdarspbp" id="exphubdarspbp" onchange="cek_exphubdarspbp()" style="width:100%;height:32px;padding:10px;" readonly required>
							                    	<div id='div_warning_exphubdarspbp'></div>
						                  		</div>
						                  	</div>
						                </div>
					              		<div class="col-md-8">
							                <div class="form-group">
							                  	<label>Dokumen SP Bebas Pencemaran :</label>
							                  	<div id='view_file_angkut_dhd_spbp'></div>
							                    <input type="file" class="input-sm init" name="file_angkut_dhd_spbp" id="file_angkut_dhdspbp" onchange="cek_exphubdarspbp()" style="width:100%">
						                  	</div>
						                </div>
						                <div class="clearfix"></div>

					              	</div>
					            </div>
					            <!-- /.tab-pane -->
					        </div>

					    </div>

		            	<div class="clearfix"></div>

		            	<div class="modal-footer">
		              		<input type="hidden" name="id" id="id" style="width:100%">
		              		<button type="submit" name="action_btn" id="action_btn" class="btn btn-primary">Save</button>
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
<script src="<?php echo base_url() ?>assets/apps/js/she/master/vendor.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>

