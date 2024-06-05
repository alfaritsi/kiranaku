<?php $this->load->view('header') ?>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">Data Bukti Keluar Barang</h1>
                        <div class="btn-group btn-group-sm pull-right">
                            <!-- <?php if ($akses_kirim_sap) { ?>
                                <button id="btn_kirim_sap" class="btn btn-success">
                                    <i class="fa fa-external-link-square"></i> &nbsp Kirim Ke SAP
                                </button>
                            <?php } ?> -->
                        </div>
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
				                	<label> Status SAP: </label>
				                	<select class="form-control select2" multiple="multiple" id="status_sap_filter" name="status_sap_filter[]" style="width: 100%;" data-placeholder="Semua Status" data-allowclear="true">
				                  		<?php
											echo "<option value='success'>Succes</option>";
											echo "<option value='fail'>Fail</option>";
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
                                    <thead>
                                        <th>NO BKB</th>    
                                        <th>Tanggal</th>
                                        <th>Pabrik</th>
                                        <th>Status SAP</th>
                                        <th>NO GI SAP</th>
                                        <th>&nbsp;</th>
                                    </thead>
                                    <tbody></tbody>
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
                                        <td colspan="2">- Status SAP</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 8%;"><span class="label label-success">Success</span></td>
                                        <td>: Submit SAP Berhasil. Menunggu Pembentukan nomor GI SAP</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 8%;"><span class="label label-danger">Fail</span></td>
                                        <td>: Submit SAP Gagal.</td>
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/transaksi/gi/data_gi.js"></script>