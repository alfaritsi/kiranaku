<?php $this->load->view('header') ?>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">Data Permohonan Pembelian Barang</h1>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <div class="input-group input-daterange" id="filter-date">
                                        <input type="text" id="tanggal_awal" name="tanggal_awal" value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>" class="form-control" readonly autocomplete="off">
                                        <label class="input-group-addon" for="tanggal-awal">-</label>
                                        <input type="text" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>" class="form-control" readonly autocomplete="off">
                                    </div>
                                </div>
                            </div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
				                	<select class="form-control select2" multiple="multiple" id="pabrik_filter" name="pabrik_filter[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
											if(!empty($pabrik)){
												// $arr_pabrik = explode(",", $pabrik);
												foreach ($pabrik as $pabrik) {
													if($pabrik!=''){
														echo "<option value='$pabrik'>$pabrik</option>";
													}
												}
											}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Status Konfirmasi: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_konfirmasi_filter" name="status_konfirmasi_filter[]" style="width: 100%;" data-placeholder="Semua Status" data-allowclear="true">
                                        <option value='lengkap'>Lengkap</option>
										<option value='sebagian'>Sebagian</option>
										<option value='belum'>Belum</option>
				                  	</select>
				            	</div>
			            	</div>
                            <div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Status PPB: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_konfirmasi_filter" name="status_ppb_filter[]" style="width: 100%;" data-placeholder="Semua Status" data-allowclear="true">
                                        <option value='open'>Open</option>
                                        <option value='closed'>Closed</option>
				                  	</select>
				            	</div>
			            	</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Status PO HO: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_po_ho_filter" name="status_po_ho_filter[]" style="width: 100%;" data-placeholder="Semua Status" data-allowclear="true">
                                        <option value='lengkap'>Lengkap</option>
										<option value='sebagian'>Sebagian</option>
										<option value='belum'>Belum</option>
				                  	</select>
				            	</div>
			            	</div>
                            <div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Status PO Site: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_po_site_filter" name="status_po_site_filter[]" style="width: 100%;" data-placeholder="Semua Status" data-allowclear="true">
                                        <option value='lengkap'>Lengkap</option>
										<option value='sebagian'>Sebagian</option>
										<option value='belum'>Belum</option>
				                  	</select>
				            	</div>
			            	</div>
                            
		            	</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
                                    <thead>
                                        <th>Nomor PPB</th>
                                        <th>Pabrik</th>
                                        <th>Tanggal PPB</th>
                                        <th>Tanggal Upload</th>
                                        <th>Perihal</th>
                                        <th>Status Konfirmasi</th>
                                        <th>Status PO</th>
                                        <th>Status PPB</th>
                                        <th>#</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <div><b>Keterangan</b></div>
                                <table style="width: 100%;">
                                    <tr>
                                        <td colspan="2">- Status Konfirmasi</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 8%;"><span class="label label-success"><i class="icon fa fa-check"></i> Terkonfirmasi Lengkap</span></td>
                                        <td>: Semua item PPB sudah dikonfirmasi.</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 8%;"><span class="label label-warning"><i class="icon fa fa-warning"></i> Terkonfirmasi Sebagian</span></td>
                                        <td>: Sebagian item PPB sudah dikonfirmasi.</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 8%;"><span class="label label-danger"><i class="icon fa fa-warning"></i> Belum Terkonfirmasi</span></td>
                                        <td>: Item PPB belum ada yang dikonfirmasi.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">- Status PO</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 8%;"><span class="label label-success"><i class="icon fa fa-check"></i> Lengkap</span></td>
                                        <td>: Semua item dari PPB sudah pernah dibuatkan PO.</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 8%;"><span class="label label-warning"><i class="icon fa fa-warning"></i> Sebagian</span></td>
                                        <td>: Sebagian item PPB sudah pernah dibuatkan PO.</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 8%;"><span class="label label-danger"><i class="icon fa fa-warning"></i> Belum</span></td>
                                        <td>: Item PPB belum pernah ada yang dibuatkan PO.</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/transaksi/ppb/data_ppb.js?<?php echo time();?>"></script>