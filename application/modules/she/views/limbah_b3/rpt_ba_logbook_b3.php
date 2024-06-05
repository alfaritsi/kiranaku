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
	            		<h3 class="box-title"><strong> <?php echo $title; ?></strong></h3>

	            		<div class="clearfix"></div>

					    <form method="POST" id="filterform" action="<?php echo base_url() ?>she/report/limbahb3/beritaacara" class="filter-ba_logbookb3" role="form">
			              	<div class="col-md-11" style="margin-top: 20px;">
				              	<div class="col-md-3">
					                <div class="form-group">
					                  <label>Pabrik :</label>
					                  <select name="filterpabrik" id="filterpabrik" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                    <option value="" selected> Silahkan Pilih </option>
					                    <?php
					                      foreach ($pabrik as $pabrik) {
					                      	if($pabrik->id_pabrik == $filterpabrik){
					                      		$selected = "selected";
					                      	}else{
					                      		$selected = "";
					                      	}
					                        echo "<option value='".$pabrik->id_pabrik."' ".$selected.">".$pabrik->nama." (".$pabrik->kode.")</option>";
					                      }
					                    ?>
					                  </select>
					                </div>
				              	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>From :</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
						                  <input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" id="filterfrom" name="filterfrom" value="<?php echo $filterfrom; ?>" readonly required onchange="filtersubmit()">
					                  </div>
					                </div>
				            	</div>
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>To :</label>
					                  <div class="input-group date">
					                    <div class="input-group-addon">
					                      <i class="fa fa-calendar"></i>
					                    </div>
						                  <input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" id="filterto" name="filterto" value="<?php echo $filterto; ?>" readonly required onchange="filtersubmit()">
					                  </div>
					                </div>
				            	</div>
							</div>
					    </form>
			            
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead id="table_header">
				                <?php
									echo "<tr>";
										echo "<th class='text-center'>No.</th>";
										echo "<th class='text-center'>Pabrik</th>";
										echo "<th class='text-center'>Vendor</th>";
										echo "<th class='text-center'>No. Berita Acara</th>";
										echo "<th class='text-center'>Tanggal Kirim</th>";
										echo "<th class='text-center'>Jenis Kendaraan</th>";
										echo "<th class='text-center'>No. Kendaraan</th>";
										echo "<th class='text-center'>Nama Driver</th>";
										echo "<th class='text-center'>Transfer SAP</th>";
										echo "<th class='text-center'>Tanggal Transfer SAP</th>";
										echo "<th class='text-center'></th>";
									echo "</tr>";
				                ?>									
				            </thead>
			              	<tbody id="table_body">
				                <?php
				       //          	if(1 == 2){
							    //         echo "<tr>";
						     //        	if($dt->number == 0){
											// echo "<td colspan='14'>Data not found</td>";
							    //         echo "</tr>";
				       //          	}else{
					                	$no = 0;
					                	foreach($report as $key => $dt){
								            echo "<tr>";
							            		$no++;
												echo "<td>".$no."</td>";
												echo "<td>".$dt->nama." (".$dt->kode.")</td>";
												echo "<td>".$dt->nama_vendor."</td>";
												echo "<td>".$dt->no_berita_acara."</td>";
												echo "<td>".$this->generate->generateDateFormat($dt->tanggal_keluar)."</td>";
												echo "<td>".$dt->jenis_kendaraan."</td>";
												echo "<td>".$dt->nomor_kendaraan."</td>";
												echo "<td>".$dt->nama_driver."</td>";
												echo "<td align='center'>".$dt->transfer_ba_sap.$dt->stok."</td>";
												if($dt->transfer_ba_sap != 'Transfered'){
													echo "<td align='center'></td>";
												}else{
													echo "<td align='center'>".$this->generate->generateDateFormat($dt->tanggal_transfer_sap)."</td>";
												}

												$beritaacara = str_replace('/', '-', $dt->no_berita_acara);
						                        echo "
						                        <td align='center'>
							                        <div class='input-group-btn'>
							                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
							                            <ul class='dropdown-menu pull-right'>
							                        		<li><a href='#' onclick='loadDetail(".'"'.$beritaacara.'"'.", ".'"'.base_url().'"'.")' <i class='fa fa-pencil-square-o'></i> Detail</a></li>";
															if($dt->stok != -1){
							                              		echo"<li><a target='_blank' href='".base_url()."she/report/pdf/beritaacara/".$beritaacara."'><i class='fa fa-print'></i> Print</a></li>";
							                              	}
							                              	if($dt->transfer_ba_sap != 'Transfered' && $dt->stok != -1){
							                              		echo "<li><a href='#' class='post' data-post='".$beritaacara."'><i class='fa fa-cloud-upload'></i> Transfer to SAP</a></li>";
							                              	}
							                            echo "
								                    	</ul>
								                    </div>
							                   	</td>";
								            echo "</tr>";
							        	}
				                	// }
				                ?>
			              	</tbody>
			            </table>
		            
			        </div>
				</div>
			</div>
		</div>

	    <!-- Modal -->
	    <div class="modal fade" id="form-beritaacara-detail">
	      <div class="modal-dialog" style="width:900px;">
	        <form role="form" class="form-beritaacara">
	          <div class="modal-content">
	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	              <h4 class="modal-title"> <i class="fa fa-plus"></i> Detail Berita Acara </h4>
	            </div>
	            <div class="modal-body" style="min-height:200px;">

	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Pabrik :</label>
	                  <input type="text" name="pabrik" id="pabrik" style="width:100%;height:32px;padding:10px;" readonly>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Tipe Input :</label>
	                  <input type="text" name="tipe" id="tipe" style="width:100%;height:32px;padding:10px;" readonly>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Tanggal :</label>
	                  <input type="text" name="tanggal" id="tanggal" style="width:100%;height:32px;padding:10px;" readonly>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Vendor :</label>
	                  <input type="text" name="vendor" id="vendor" style="width:100%;height:32px;padding:10px;" readonly>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Jenis Kendaraan :</label>
	                  <input type="text" name="jeniskendaraan" id="jeniskendaraan" style="width:100%;height:32px;padding:10px;" readonly>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Nomor Kendaraan :</label>
	                  <input type="text" name="nomorkendaraan" id="nomorkendaraan" style="width:100%;height:32px;padding:10px;" readonly>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Nama Driver :</label>
	                  <input type="text" name="driver" id="driver" style="width:100%;height:32px;padding:10px;" readonly>
	                </div>
	              </div>

	              <div class="clearfix"></div>

				  <div class='col-md-12' style='margin-top: 20px;'>
					<div class='form-group'>
					   <label for='limbah_b3' class='list-group-item list-group-item-info text-center' style='width: 100%;'>Detail Item</label>
					</div>
				  </div>

				  <div class='col-md-12'>
					<table width='100%' class="table-striped" border="2">
						<thead>
							<tr>
								<th rowspan='2' width='3%' class='text-center'>Item</th>
								<th rowspan='2' width='20%' class='text-center'>Jenis Limbah</th>
								<th rowspan='2' width='3%' class='text-center'>Stock Tersedia</th>
								<th rowspan='2' width='3%' class='text-center'>Qty</th>
								<th rowspan='2' width='10%' class='text-center'>UoM</th>
								<th rowspan='2' width='3%' class='text-center'>Qty (Konversi)</th>
								<th rowspan='2'width='5%' class='text-center'>Uom (Konversi Ton)</th>
								<th rowspan='2'width='10%' class='text-center'>No. Manifest</th>
								<th colspan='3' class='text-center'>Lampiran Manifest</th>
							</tr>
							<tr>
								<th class='text-center'>Lembar 2</th>
								<th class='text-center'>Lembar 3</th>
								<th class='text-center'>Lembar 7</th>
							</tr>
						</thead>
						<tbody id='detailitem'>
						</tbody>
					</table>
				  
				  </div>
				  <div class='clearfix'></div>

	            </div>
	            
	            <div class="modal-footer">
	              <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
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
<script src="<?php echo base_url() ?>assets/apps/js/she/report/rpt_ba_logbook_b3.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>
