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
				                  <th rowspan="2" class="text-center">Tanggal Mulai</th>
				                  <th rowspan="2" class="text-center">Tanggal Berakhir</th>
				                  <th rowspan="2" class="text-center">Modul / Kategori</th>
				                  <th rowspan="2" class="text-center">Lokasi / Jenis</th>
				                  <th rowspan="2" class="text-center">Parameter</th>
				                  <th colspan="3" class="text-center">Kriteria Baku Mutu Hasil Uji</th>
				                  <th colspan="3" class="text-center">Kriteria Baku Mutu Beban Pencemaran</th>
				                  <th rowspan="2" class="text-center"></th>
				                </tr>
				                <tr>
				                  <!--hasil uji-->
				                  <th class="text-center">Limit</th>
				                  <th class="text-center">Min</th>
				                  <th class="text-center">Max</th>
				                  <!--beban cemar-->
				                  <th class="text-center">Limit</th>
				                  <th class="text-center">Min</th>
				                  <th class="text-center">Max</th>
				                </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($bakumutu as $dt){
					                  echo "<tr>";
					                  echo "<td align = 'center'>".$this->generate->generateDateFormat($dt->tgl_mulai)."</td>";
					                  echo "<td align = 'center'>".$this->generate->generateDateFormat($dt->tgl_akhir)."</td>";
					                  echo "<td>".$dt->kategori."</td>";
					                  echo "<td>".$dt->jenis."</td>";
					                  echo "<td>".$dt->parameter."</td>";
					                  echo "<td align='right'>".number_format($dt->bakumutu_hasilujilimit,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->bakumutu_hasilujimin,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->bakumutu_hasilujimax,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->bakumutu_bebancemarlimit,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->bakumutu_bebancemarmin,2,",",".")."</td>";
					                  echo "<td align='right'>".number_format($dt->bakumutu_bebancemarmax,2,",",".")."</td>";
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
	        <form role="form" class="form-master-bakumutu">
	          <div class="modal-content">
	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	              <h4 class="modal-title"><i class="fa fa-plus"></i> Tambah Data Baku Mutu </h4>
	            </div>
	            <div class="modal-body" style="min-height:300px;">

	              <div class="col-md-4">
	                <div class="form-group">
	                  <label>Tanggal Mulai :</label>
	                  <div class="input-group date">
	                    <div class="input-group-addon">
	                      <i class="fa fa-calendar"></i>
	                    </div>
	                    <input type="text" name="tgl_awal" class="form-control pull-right datePicker init" id="tgl_awal" readonly required>
	                  </div>
	                </div>
	              </div>

	              <div class="col-md-4">
	                <div class="form-group">
	                  <label>Tanggal Berakhir :</label>
	                  <div class="input-group date">
	                    <div class="input-group-addon">
	                      <i class="fa fa-calendar"></i>
	                    </div>
	                    <input type="text" name="tgl_akhir" class="form-control pull-right datePicker init" id="tgl_akhir" readonly required>
	                  </div>
	                </div>
	              </div>

	              <div class="col-md-4">
	                <div class="form-group">
	                  <label>Kategory :</label>
	                  <select name="kategori" id="kategori" class="form-control select2" style="width: 100%;" required>
	                    <option value="" selected> </option>
	                    <?php
	                      foreach ($kategori as $kategori) {
	                        echo "<option value='".$kategori->id."'>".$kategori->kategori."</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	              </div>

	              <div class="col-md-4">
	                <div class="form-group">
	                  <label>Jenis :</label>
	                  <select name="jenis" id="jenis" class="form-control select2" style="width: 100%;" required>
	                    <option value="" selected> </option>
	                    <?php
	                      foreach ($jenis as $jenis) {
	                        echo "<option value='".$jenis->id."'>".$jenis->jenis."</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	              </div>

	              <div class="col-md-4">
	                <div class="form-group">
	                  <label>Parameter :</label>
	                  <select name="parameter" id="parameter" class="form-control select2" style="width: 100%;" required>
	                    <option value="" selected> </option>
	                    <?php
	                      foreach ($parameter as $parameter) {
	                        echo "<option value='".$parameter->id."'>".$parameter->parameter."</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	              </div>

	              <div class="clearfix" style="margin-bottom:20px; margin-top:40px;"></div>

	              <div class="col-md-6">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width: 100%;">Kriteria Baku Mutu Hasil Uji</label>
	                </div>
	              </div>

	              <div class="col-md-6">
	                <div class="form-group">
	                  <label for="bakumutu" class="list-group-item list-group-item-info text-center" style="width:100%;">Kriteria Baku Mutu Beban Cemar</label>
	                </div>
	              </div>

	              <div class="clearfix"></div>

	              <div class="col-md-2">
	                <div class="form-group">
	                  <label>Limit :</label>
	                  <input type="text" name="limit_uji" id="limit_uji" style="width:100%;height:32px;text-align:right;padding:10px;" class="init" required>
	                </div>
	              </div>
	              <div class="col-md-2">
	                <div class="form-group">
	                  <label>Min :</label>
	                  <input type="text" name="min_uji" id="min_uji" style="width:100%;height:32px;text-align:right;padding:10px;" class="init" required>
	                </div>
	              </div>
	              <div class="col-md-2">
	                <div class="form-group">
	                  <label>Max :</label>
	                  <input type="text" name="max_uji" id="max_uji" style="width:100%;height:32px;text-align:right;padding:10px;" class="init" required>
	                </div>
	              </div>

	              <div class="col-md-2">
	                <div class="form-group">
	                  <label>Limit :</label>
	                  <input type="text" name="limit_cemar" id="limit_cemar" style="width:100%;height:32px;text-align:right;padding:10px;" class="init" required>
	                </div>
	              </div>
	              <div class="col-md-2">
	                <div class="form-group">
	                  <label>Min :</label>
	                  <input type="text" name="min_cemar" id="min_cemar" style="width:100%;height:32px;text-align:right;padding:10px;" class="init" required>
	                </div>
	              </div>
	              <div class="col-md-2">
	                <div class="form-group">
	                  <label>Max :</label>
	                  <input type="text" name="max_cemar" id="max_cemar" style="width:100%;height:32px;text-align:right;padding:10px;" class="init" required>
	                </div>
	              </div>
	            </div>
	            
	            <div class="modal-footer">
	              <input type="hidden" name="id" id="id">
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
<script src="<?php echo base_url() ?>assets/apps/js/she/master/baku_mutu.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>