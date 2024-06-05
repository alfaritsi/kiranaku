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
	            		<div class="clearfix"></div>
		            	<div class="col-md-8" style="margin-top: 20px;">
			                <div class="form-group">
					            <h3 class="box-title"><strong><?php echo $pabrik[0]->nama.' ('.$pabrik[0]->kode.')'; ?></strong></h3>
			                </div>
		            	</div>

		            	<div class="col-md-2 pull-right" style="margin-top: 20px;">
			                <div class="form-group">
					            <button type="button" id="adddata" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modal-form">
					              <i class="fa fa-plus"></i> Tambah Data
					            </button>
			                </div>
		            	</div>
			            
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
						        <tr>
									<th>Limbah</th>          
									<th>Type</th>          
									<th>Tgl. Masuk</th>          
									<th>Extend Day</th>          
									<th>Extend Date</th>          
									<th>Stock</th>          
									<th width="1px"></th>          
									<th width="1px"></th>          
						        </tr>
				            </thead>
			              	<tbody id="table_trx">
				                <?php
					                foreach($masa_b3 as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->limbah."</td>";
					                  echo "<td align='center'>".$dt->type."</td>";
					                  echo "<td align='center'>".$this->generate->generateDateFormat($dt->tanggal_masuk)."</td>";
					                  echo "<td align='right'>".$dt->ext_days."</td>";
					                  echo "<td align='center'>".$this->generate->generateDateFormat($dt->dsimpan_ext)."</td>";
					                  echo "<td align='right'>".$dt->stok."</td>";
					                  if($dt->lampiran1 == "" || empty($dt->lampiran1)){
						                  echo "<td align='center'></td>";
					                  }else{
						                  echo "<td align='center'><a title='Lihat file lampiran' target='_blank' href='".base_url().$dt->lampiran1."'><i class='fa fa-download'></i></a></td>";
					                  }
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
	      <div class="modal-dialog" style="width:700px;">
	        <form role="form" class="form-perpanjangmasaB3">
	          <div class="modal-content">
	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	              <h4 class="modal-title"> <i class="fa fa-plus"></i> Tambah Data </h4>
	            </div>
	            <div class="modal-body" style="min-height:200px;">

	              <div class="col-md-6">
	                <div class="form-group">
	                  <label>Pabrik :</label>
	                  <select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" disabled required>
	                    <?php
	                      foreach ($pabrik as $pabrik) {
	                      	// if($idpabrik == $pabrik->id_pabrik){
	                      	// 	$selected = "selected";
	                      	// }else{
	                      		$selected = "";
	                      	// }
	                        echo "<option value='".$pabrik->id_pabrik."' ".$selected.">".$pabrik->nama." (".$pabrik->kode.")</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Tgl Limbah Masuk :</label>
	                  <div class="input-group date">
	                    <div class="input-group-addon">
	                      <i class="fa fa-calendar"></i>
	                    </div>	                  
	                  	<input type="text" name="tgllimbahmasuk" id="tgllimbahmasuk" class="form-control datepicker" readonly required>
	                  </div>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Tgl Max Simpan : </label>
	                  <div class="input-group date">
	                    <div class="input-group-addon">
	                      <i class="fa fa-calendar"></i>
	                    </div>	                  
		                <input type="text" name="tglmaxsimpan" id="tglmaxsimpan" class="form-control datepicker" readonly required>
	                  </div>
	                </div>
	              </div>

	              <div class="clearfix"></div>

	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Masa Perpajangan :</label> <div class="clearfix"> </div>
	                  <input type="text" name="masaperpanjangan" id="masaperpanjangan" style="width:50%;height:32px;padding:10px;text-align:right;" required>
	                  <label> &nbsp; Hari</label>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Tgl Max Simpan Baru :</label>
	                  <div class="input-group date">
	                    <div class="input-group-addon">
	                      <i class="fa fa-calendar"></i>
	                    </div>	                  
	                  	<input type="text" name="tglmaxsimpanbaru" id="tglmaxsimpanbaru" class="form-control datepicker" readonly required>
	                  </div>
	                </div>
	              </div>
	              <div class="col-md-6">
	                <div class="form-group">
	                  <label>Lampiran : </label>
	                  <input type="file" name="lampiran1" id="lampiran1" style="width:100%; height:32px;">
	                </div>
	              </div>

		          <div class="clearfix" style="margin-bottom: 20px;"></div>

	              <div class="col-md-12">
	                <div class="form-group">
	                  <label>List Limbah :</label>
	                  <table class="table table-striped" border="1" cellspacing="0" cellpadding="4px">
	                  	<thead>
	                  		<tr>
	                  			<th height="30px" class="text-center">Limbah</th>
	                  			<th class="text-center">Stock</th>
	                  			<th class="text-center">Uom</th>
	                  		</tr>
	                  	</thead>
	                  	<tbody id="custom-table">
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


	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/she/transaction/perpanjang_masa_b3.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>
