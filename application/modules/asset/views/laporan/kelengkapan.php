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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
				                	<select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
					                		foreach($pabrik as $dt){
					                			echo "<option value='".$dt->id_pabrik."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
								<th>Pabrik</th>
								<th>Nomor Polisi</th>
								<th>Sub Kategori Asset</th>
								<th>Merk</th>
								<th>Tipe</th>
								<th>Dokumen Yang Belum Ada</th>
								<th>Dokumen Yang Expired</th>
								<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($aset as $dt){
									$selisih_doc = $dt->total_dokumen - $dt->jumlah_dokumen;
									echo "<tr>";
				              		echo "<td>".$dt->nama_pabrik."</td>";
				              		echo "<td>".$dt->nomor_polisi."</td>";
				              		echo "<td>".$dt->nama_jenis."</td>";
				              		echo "<td>".$dt->nama_merk."</td>";
				              		echo "<td>".$dt->nama_merk_tipe."</td>";
				              		echo "<td>".$selisih_doc." of ".$dt->total_dokumen."</td>";
				              		echo "<td>".$dt->jumlah_expired."</td>";
									echo "
											<td>
												<div class='input-group-btn'>
													<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
													<ul class='dropdown-menu pull-right'>
														<li><a href='javascript:void(0)' class='detail' data-detail='".$dt->id_aset."' data-id_jenis='".$dt->id_jenis."'><i class='fa fa-search'></i> Detail</a></li>
													</ul>
												</div>
											</td>
											";
									echo "</tr>";
				              	}
				              	?>
			              	</tbody>
			            </table>
			        </div>
				</div>
			</div>
			
			<!--modal detaik-->
			<div class="modal fade" id="add_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-md" role="document">
			    	<div class="modal-content">
						<div class="col-sm-12">
							<div class="nav-tabs-custom" id="tabs-edit">
								<form role="form" class="form-transaksi-hrga" enctype="multipart/form-data">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Detail Kelengkapan Dokumen Asset HRGA</h4>
									</div>
									<div class="modal-body">
										<div class="tab-content">
											<table class="table table-bordered table-striped my-datatable-extends-order-detail">
												<thead>
													<th>Jenis Dokumen</th>
													<th>Tanggal Berlaku</th>
													<th>Tanggal Berakhir</th>
													<th>Sisa Hari</th>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
									<div class="modal-footer">
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>	
			</div>
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/laporan/kelengkapan.js"></script>
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