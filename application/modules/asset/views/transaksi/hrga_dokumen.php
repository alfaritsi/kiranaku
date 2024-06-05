<!--
/*
@application  : Asset Management
@author		  : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->
<?php $this->load->view('header') ?>
<?php 
$awal = (empty($_POST['awal']))?date('Y-m-d', strtotime(date('Y-m-d').'-3 months')):$_POST['awal'];
$akhir = (empty($_POST['akhir']))?date('Y-m-d'):$_POST['akhir'];
?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
						<button type="button" class="btn btn-sm btn-default pull-right" id="add_button">Pembaruan Dokumen</button> 
	          		</div>
	          		<!-- /.box-header -->
					<!--
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Jenis Dokumen: </label>
				                	<select class="form-control select2" multiple="multiple" id="id_dokumen" name="id_dokumen[]" style="width: 100%;" data-placeholder="Pilih Dokumen">
				                  		<?php
					                		foreach($dokumen as $dt){
					                			echo "<option value='".$dt->id_inv_doc."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            </div>					
					-->	
					<!-- /.box-filter -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Tipe Dokumens</th>
								<th>Nomor Dokumen</th>
								<th>Tanggal Berlaku</th>
								<th>Masa Berlaku</th>
								<th>Tanggal Berakhir</th>
								<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($dokumen_transaksi as $dt){
									echo "<tr>";
				              		echo "<td>".$dt->nama_dokumen."</td>";
				              		echo "<td>".$dt->nomor_dokumen."</td>";
									echo "<td>".$dt->tanggal_berlaku."</td>";
									echo "<td>".$dt->periode." Bulan</td>";
									echo "<td>".date("d.m.Y",strtotime("+".$dt->periode." month",strtotime($dt->tanggal_berlaku)))."</td>";
				              		echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
												if($dt->na == 'n'){ 
												echo "
													  <li><a href='javascript:void(0)' class='edit' data-edit='".$dt->id_inv_doc_transaksi."'><i class='fa fa-pencil-square-o'></i> Edit Pembaruan Dokumen</a></li>
													  ";
												}
									echo " 	</ul>
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
			<!--add dokumen-->
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
		          	<form role="form" class="form-dokumen-hrga">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">Tambah/ Edit Pembaruan Dokumen</h4>
						</div>
	            		<div class="modal-body">
							<div class="form-group">		
								<div class="row">
									<div class="col-xs-3">
										<label for="nomor_sap">Nomor SAP</label>
									</div>
									<div class="col-xs-8">
										<input type="text" class="form-control" name="nomor_sap" id="nomor_sap" placeholder="Nomor SAP" value="<?php if (isset($aset))echo $aset[0]->nomor_sap?>"  required="required" readonly>
									</div>
								</div>
							</div>
							<div class="form-group">		
								<div class="row">
									<div class="col-xs-3">
										<label for="dokumen">Tipe Dokumen</label>
									</div>
									<div class="col-xs-8">
										<select class="form-control select2modal" name="id_inv_doc" id="id_inv_doc"  required="required">
											<?php
												echo "<option value='0'>Silahkan Pilih Jenis</option>";
												foreach($dokumen as $dt){
													echo"<option value='".$dt->id_inv_doc."'>".$dt->nama."</option>";
												}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group">		
								<div class="row">
									<div class="col-xs-3">
										<label for="nomor_dokumen">Nomor Dokumen</label>
									</div>
									<div class="col-xs-8">
										<input type="text" class="form-control" name="nomor_dokumen" id="nomor_dokumen" placeholder="Nomor Dokumen"  required="required">
									</div>
								</div>
							</div>
							<div class="form-group">		
								<div class="row">
									<div class="col-xs-3">
										<label for="tanggal_berlaku">Tgl Mulai Berlaku</label>
									</div>
									<div class="col-xs-8">
										<input type="text" class="form-control tanggal" name="tanggal_berlaku" id="tanggal_berlaku" placeholder="Tanggal Berlaku"  required="required">
									</div>
								</div>
							</div>
							<div class="form-group">	
								<div class="row">
									<div class="col-xs-3">
										<label for="tanggal_perolehan">Foto Dokumen</label>
									</div>
									<div class="col-xs-8">
										<div class="form-group">
											<input type="file" multiple="multiple" class="form-control" id="gambar" name="gambar[]">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">		
								<div class="row">
									<div class="col-xs-3">
										<label for="keterangan">Keterangan</label>
									</div>
									<div class="col-xs-8">
										<textarea rows="4" id="keterangan" name="keterangan" class="form-control" placeholder="Keterangan"></textarea>														
									</div>
								</div>
							</div>
							
		            	</div>
						<div class="modal-footer">
							<input id="hidden_gambar" name="hidden_gambar" type="hidden">
							<input id="id_inv_doc_transaksi" name="id_inv_doc_transaksi" type="hidden">
							<input id="id_aset" name="id_aset" value="<?php if (isset($aset))echo $aset[0]->id_aset?>" type="hidden">
							<input id="plat" name="plat" value="<?php if (isset($aset))echo $aset[0]->plat?>" type="hidden">
							<input id="no_pol" name="no_pol" value="<?php if (isset($aset))echo $aset[0]->no_pol?>" type="hidden">
							<input id="bel_nomor_polisi" name="bel_nomor_polisi" value="<?php if (isset($aset))echo $aset[0]->bel_nomor_polisi?>" type="hidden">
							<button type="button" class="btn btn-primary" name="action_btn">Submit</button>
						</div>
						
		          	</form>
					</div>
				</div>	
			</div>	

			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/hrga_dokumen.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>


<style>
.small-box .icon{
    top: -13px;
}
</style>