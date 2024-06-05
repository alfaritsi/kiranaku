<?php $this->load->view('header') ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                	<div class="box box-success">
			          	<div class="box-header">
			            	<h3 class="box-title"><strong>Master Periode</strong></h3>
			            	<div class="btn-group pull-right pr">
							    <button id="add_periode" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus" style="color:white; padding-right: 5px;"></i> PERIODE</button>
							</div>
			          	</div>
						<div class="box-body">
							<table class="table table-bordered table-striped" id="sspsTable">
								<thead>
									<tr>
										<th>Jenis Aset</th>
										<th>Kode</th>
										<th>Periode</th>
										<th>Sequence</th>
										<th>Jam</th>
										<th>Bulan</th>
										<th>Kategori</th>
										<th>Periode Detail</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
					<!--end box-->
                </div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/periode.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->

<!-- Modal -->
<div class="modal fade" id="add_periode_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="add_modal_label" style="text-transform: capitalize">Tambah Periode</h4>
      		</div>
      		<div class="modal-body">
	      		<div class="row">
		      		<div class="col-sm-12">
		      			<form role="form" class="form-master-periode">
			            <div class="box-body">
			              <div class="form-group">
			                <label for="jenis_aset">Jenis Aset</label>
			                <select id="jenis_aset" name="jenis_aset" class="form-control select2 col-sm-12" required="required">
                            	<option value=''>Pilih Jenis Aset</option>
                            	<?php
	                				foreach ($jenis as $j) {
                                        echo "<option value='$j->id_jenis'>$j->nama</option>";
                                    }
	                			?>
                            </select>
			              </div>
			              <div class="form-group">
			                <label for="kode">Kode</label>
			                <input type="text" class="form-control" name="kode" id="kode" readonly="readonly">
			              </div>
			              <div class="form-group">
			                <label for="instansi">Periode</label>
			                <input type="text" class="form-control" name="periode" id="periode" placeholder="Masukkkan Nama Periode" required="required">
			              </div>
			              <div class="form-group">
			                <label for="keterangan">Keterangan</label>
			              	<textarea class="form-control" name="ket_periode" id="ket_periode" placeholder="Masukan Keterangan" required="required"></textarea>
			              </div>
			              <div class="form-group">
			                <label for="sequence">Sequence</label>
			                	<input type="number" class="form-control" name="sequence" id="sequence" value="0" required="required">
			              </div>
			              <div class="form-group">
			                <label for="jam">Jam</label>
			                <div class="input-group">
			                	<input type="number" class="form-control" name="jam" id="jam" value="0" required="required">
				                <span class="input-group-addon">Jam </span>
				            </div>
			              </div>
			              <div class="form-group">
			                <label for="bulan">Bulan</label>
			                <div class="input-group">
			                	<input type="number" class="form-control" name="bulan" id="bulan" value="0" required="required">
				                <span class="input-group-addon">Bulan </span>
				            </div>
			              </div>
			              <div class="form-group">
			                <label for="service">Kategori Service</label>
			                <select id="service" name="service" class="form-control select2 col-sm-12" required="required">
                            	<option value=''>Pilih Kategori Service</option>
                            	<?php
	                				foreach ($service as $s) {
                                        echo "<option value='$s->id_service'>$s->nama</option>";
                                    }
	                			?>
                            </select>
			              </div>
			            </div>
			            <div class="box-footer">
			              <input type="hidden" name="id_periode">
			              <button type="submit" class="btn btn-success pull-right">Submit</button>
			            </div>
			          </form>
		      		</div>
	      		</div>
      		</div>
    	</div>
  	</div>
</div>
<!-- Modal -->

<!-- Modal -->
<div class="modal fade" id="detail_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="detail_modal_label" style="text-transform: capitalize">Periode Detail</h4>
      		</div>
		    <form role="form" class="form-periode-detail">
      		<div class="modal-body">
		      		<table id="periode_detail" class="table table-bordered datatable-periode">
	              		<thead>
		              		<!-- <th style="display: none;">id_periode_detail</th> -->
		              		<!-- <th style="display: none;">id</th> -->
			              	<th>Jenis Detail</th>
			              	<th>Kegiatan</th>
			              	<th>Keterangan</th>
			              	<th>Select All
			              		<div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
			        				<label><input type="checkbox" class="selectALL"></label>
			        			</div>
			        		</th>

			            </thead>
		              	<tbody id="tbod">
		              	</tbody>
		            </table>

		            
      		</div>
      		<div class="modal-footer">
      			<input type="hidden" name="total_row">
      			<input type="hidden" name="fd_id_periode">
	            <input type="hidden" name="fd_id_jenis">
		        <button type="submit" class="btn btn-success pull-right">Submit</button>
      		</div>
		    </form>
    	</div>
  	</div>
</div>
<!-- Modal 