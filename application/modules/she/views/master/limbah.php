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
				                  <th class="text-center">Jenis Limbah</th>
				                  <th class="text-center">Kode Material</th>
				                  <th class="text-center">Kode Limbah Regulasi</th>
				                  <th class="text-center">Satuan</th>
				                  <th class="text-center">Konversi (Ton)</th>
				                  <th class="text-center">Satuan Pengiriman</th>
				                  <th class="text-center">Konversi Satuan Pengiriman</th>
				                  <th class="text-center">No. Form Log Book Limbah B3</th>
				                  <th class="text-center">Status</th>
				                  <th></th>
				                </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($limbah as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->jenis_limbah."</td>";
					                  echo "<td>".$dt->kode_material."</td>";
					                  echo "<td>".$dt->kode_reglimbah."</td>";
					                  echo "<td>".$dt->satuan."</td>";
					                  echo "<td align='right'>".number_format($dt->konversi_ton,5,",",".")."</td>";
					                  echo "<td>".$dt->satuan_pengiriman."</td>";
					                  echo "<td align='right'>".number_format($dt->konversi_satuan_pengiriman,0,",",".")."</td>";
					                  echo "<td>".$dt->form_log_book_number."</td>";
					                  echo "<td>".$dt->label_active."</td>";
					                  echo "<td>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
											// if($dt->na == null){ 
											// echo "<li><a href='#' class='edit' data-edit='".$dt->jenis_limbah."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
												  // <li><a href='#' class='delete' data-delete='".$dt->jenis_limbah."'><i class='fa fa-trash-o'></i> Hapus</a></li>";
											// }
											// if($dt->na != null){
											// echo "<li><a href='#' class='set_active-kategori' data-activate='".$dt->jenis_limbah."'><i class='fa fa-check'></i> Set Aktif</a></li>";
											// }
											if($dt->del == 1){ 
												echo "<li><a href='javascript:void(0)' class='edit' data-edit='".$dt->jenis_limbah."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-pencil-square-o'></i> Edit </a></li>";
												echo "<li><a href='javascript:void(0)' class='delete' data-delete='".$dt->jenis_limbah."'><i class='fa fa-minus-square-o'></i> Non Aktif</a></li>";
											}
											if($dt->del == 0){
												echo "<li><a href='javascript:void(0)' class='set_aktif' data-delete='".$dt->jenis_limbah."'><i class='fa fa fa-check'></i> Set Aktif</a></li>";
												// echo "<li><a href='javascript:void(0)' class='setactive' data-setactive='".$dt->id_role."'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
	        <form role="form" class="form-master-limbah" enctype="multipart/form-data">
	          <div class="modal-content">
	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	              <h4 class="modal-title"> <i class="fa fa-plus"></i> Tambah Data Limbah </h4>
	            </div>
	            <div class="modal-body" style="min-height:200px;">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							<label>Jenis Limbah :</label>
							<input type="text" name="jenislimbah" id="jenislimbah" class="init" style="width:100%;height:32px;padding:10px;" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<label>Kode Material :</label>
							<select name="kodematerial" id="kodematerial" class="form-control init" style="width:100%;" required></select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<label>Kode Limbah Regulasi :</label>
							<input type="text" name="kodelimbahregulasi" id="kodelimbahregulasi" class="init" style="width:100%;height:32px;padding:10px;" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							<label>Satuan :</label>
							<select name="satuan" id="satuan" class="form-control select2" style="width: 100%;" required>
								<option value="" selected> </option>
								<?php
								foreach ($satuan as $key => $satuan1) {
									echo "<option value='".$satuan1->id_uom."'>".$satuan1->nama."</option>";
								}
								?>
							</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<label>Konversi (Ton) :</label>
							<input type="text" name="konversiton" id="konversiton" class="init" style="width:100%;height:32px;padding:10px;text-align:right;" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<label>Satuan Pengiriman :</label>
							<select name="satuanpengiriman" id="satuanpengiriman" class="form-control select2" style="width: 100%;" required>
								<option value="" selected> </option>
								<?php
								foreach ($satuan as $key => $satuan2) {
									echo "<option value='".$satuan2->id_uom."'>".$satuan2->nama."</option>";
								}
								?>
							</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							<label>Konveri Satuan Pengiriman :</label>
							<input type="text" name="konversisatuanpengiriman" id="konversisatuanpengiriman" class="init" style="width:100%;height:32px;padding:10px;text-align:right;" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
							<label>No. Form Log Book Limbah B3 :</label>
							<input type="text" name="formlog" id="formlog" class="init" style="width:100%;height:32px;padding:10px;" required>
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
<script src="<?php echo base_url() ?>assets/apps/js/she/master/limbah.js?<?php echo time();?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>