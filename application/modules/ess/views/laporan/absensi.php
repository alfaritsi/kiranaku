<?php $this->load->view('header') ?>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Group Produksi: </label>
				                	<select class="form-control select2" multiple="multiple" id="group_produksi" name="group_produksi[]" style="width: 100%;" data-placeholder="Pilih Group Produksi">
				                  		<?php
					                		// foreach($group as $dt){
					                			// echo "<option value='".$dt->group_produksi."'>".$dt->group_produksi."</option>";
					                		// }
											foreach($group as $dt){
												echo "<option value='".$dt->DESCR."'>".$dt->PRGRP." - ".$dt->DESCR."</option>";
											}
											
					                	?>
				                  	</select>
				            	</div>
			            	</div>
						
							<div class="col-sm-3">
								<div class="form-group">
									<label for="mesin"
										   class="control-label text-left">Tanggal Kehadiran</label>
									<div class="input-group">
										<input type="text"
											   id="tanggal_awal_filter"
											   name="tanggal_awal"
											   value="<?php echo $tanggal_awal->format('d.m.Y'); ?>"
											   class="form-control kiranadatepicker"
											   autocomplete="off" data-autoclose="true">
										<div class="input-group-addon"> - </div>
										<input type="text"
											   id="tanggal_akhir_filter"
											   name="tanggal_akhir"
											   value="<?php echo $tanggal_akhir->format('d.m.Y'); ?>"
											   class="form-control kiranadatepicker"
											   autocomplete="off" data-autoclose="true" data-minDate="#tanggal_awal_filter">
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<table class="table table-bordered table-striped"
									   id="sspTable">
									<thead>
										<tr>
											<th>Group Produksi</th>
											<th>NIK</th>
											<th>Nama</th>
											<th>Jabatan</th>
											<th>Tanggal</th>
											<th>Jam CI</th>
											<th>Jam CO</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-8">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong>Data Man Power Per Bagian</strong></h3>
	          		</div>
		          	<div class="box-body">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>NO</th>
									<th width="30%">Bagian</th>
									<th width="30%">Group Produksi</th>
									<th>Jumlah MP</th>
									<th>Keterangan</th>
									<th>#</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no	= 0;
								foreach($mp as $dt){
									$no++;
									$ket = ($dt->jumlah_mp==$dt->jumlah_hadir)?"Full":"Tidak Full";
									echo "<tr>";
									echo "<td>".$no."</td>";
									echo "<td>".$dt->bagian."</td>";
									echo "<td>".$dt->group_produksi."</td>";
									echo "<td>".$dt->jumlah_mp."</td>";
									echo "<td>".$ket."</td>";
									echo "<td><button type='button' class='btn btn-sm' id='btn_detail' data-group_produksi='".$dt->group_produksi."' data-bagian='".$dt->bagian."'>Detail</button></td>";
									echo "</tr>";
								}
								?>
							</tbody>
						</table>
			        </div>
				</div>
			</div>
		</div>
		<!--modal req-->
		<div class="modal fade" id="detail_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="col-sm-12">
						<div class="modal-content">
							<form role="form" class="form-transaksi-request-ho">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="myModalLabel">Data Ketidakhadiran Karyawan</h4>
								</div>
								<div class="modal-body">
									<table id="periode_detail" class="table table-bordered datatable-periode">
										<thead>
											<tr>
												<th>BAGIAN</th>
												<th>SUB BAGIAN</th>
												<th>GROUP PRODUKSI</th>
												<th>NIK</th>
												<th>NAMA</th>
											</tr>
											</tr>
										</thead>
										<tbody id="show_detail">
										</tbody>
									</table>
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
<script src="<?php echo base_url() ?>assets/apps/js/ess/absensi.js"></script>
