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
<?php
// var_dump($filterpabrik); die();
// 						                      	foreach ($filterpabrik as $filterpabrik) {
// 							                      		echo $filterpabrik;
// 						                      	}
// die();
?>


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

					    <form method="POST" id="filterform" action="<?php echo base_url() ?>she/transaction/limbahb3/view" class="filter-transaction-limbahb3" role="form">
			              	<div class="col-md-12" style="margin-top: 20px;">
				              	<div class="col-md-5">
					                <div class="form-group">
					                  <label>Pabrik :</label>
					                  <select name="filterpabrik[]" id="filterpabrik" class="form-control select2" multiple style="width: 100%;" required onchange="filtersubmit()">
					                    <?php
					                      foreach ($pabrik as $pabrik) {
					                      	if(in_array($pabrik->id_pabrik, $filterpabrik)){
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
				            	<div class="col-md-2">
					                <div class="form-group">
					                  <label>Status :</label>
					                  <select name="status" id="status" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
					                  	<option value='0'></option>
					                  	<?php 
					                  		$selected = ($status == "1")? "selected" : "";
				                  			echo "<option value='1' ".$selected.">Not Post</option>";
					                  		$selected = ($status == "2")? "selected" : "";
				                  			echo "<option value='2' ".$selected.">Posted</option>";
					                  		$selected = ($status == "3")? "selected" : "";
					                  		echo "<option value='3' ".$selected.">Requested</option>";
						                 ?>
					                  </select>
					                </div>
				            	</div>

							</div>
					    </form>
			            
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
						        <tr>
									<th class='text-center'>Pabrik</th>          
									<th class='text-center'>Tipe</th>          
									<th class='text-center'>Tgl. Transaksi</th>          
									<th class='text-center'>Tgl. Exp.</th>          
									<th class='text-center'>Jenis</th>   
									<th class='text-center'>Kode Material</th>   
									<th class='text-center'>Sumber</th>          
									<th class='text-center'>Qty</th>          
									<th class='text-center'>Stock Akhir</th>          
									<th class='text-center'>Lampiran & Status</th>          
									<th class='text-center' width="1px"></th>          
						        </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($limbah_b3 as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->pabrik."</td>";
					                  echo "<td align='center'>".$dt->type."</td>";
					                  echo "<td align='center'>".$this->generate->generateDateFormat($dt->tanggal_transaksi)."</td>";
					                  if($dt->type == "OUT"){
					                  	echo "<td align='center'></td>";
					                  }else{
					                  	echo "<td align='center'>".$this->generate->generateDateFormat($dt->tgl_exp)."</td>";
					                  }

					                  echo "<td>".$dt->jenis_limbah."</td>";
					                  echo "<td>".$dt->kode_material."</td>";
					                  echo "<td>".$dt->sumber_limbah."</td>";
					                  echo "<td align='center'>".$dt->quantity." ".$dt->satuan."</td>";
					                  $stock = ($dt->stok == -1)?"":$dt->stok." ".$dt->satuan;
					                  echo "<td align='center'>".$stock."</td>";

					                  if($dt->type == "IN"){
					                      echo "<td align='center'>".$dt->status."</td>";

					                  }elseif($dt->type == "OUT"){
						                  echo "<td align='center'>";

						                  echo "<a title='Lihat file lampiran 1' target='_blank' href='".base_url().$dt->lampiran1."'><i class='fa fa-download'></i></a> &nbsp &nbsp";
						                  echo "<a title='Lihat file lampiran 2' target='_blank' href='".base_url().$dt->lampiran2."'><i class='fa fa-download'></i></a> &nbsp &nbsp";
						                  echo "<a title='Lihat file lampiran 3' target='_blank' href='".base_url().$dt->lampiran3."'><i class='fa fa-download'></i></a>";

					                      echo $dt->status;

						                  echo "</td>";
					                  }
						              
					                  echo "<td align='center'>";
				                      if($dt->request_del == 1){
				                        echo "
				                        <div class='input-group-btn pull-right'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>
				                              	<li><a href='#' class='delete' data-delete='".$dt->id."'><i class='fa fa-trash-o'></i> Approve For Delete</a></li>
					                    	</ul>
					                    </div>";
					                  }

					                  echo "</td>";

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
	      <div class="modal-dialog" style="width:900px;">
	        <form role="form" class="form-limbahb3_inputdata">
	          <div class="modal-content">
	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	              <h4 class="modal-title"> <i class="fa fa-plus"></i> Tambah Data </h4>
	            </div>
	            <div class="modal-body" style="min-height:200px;">

	              <div class="col-md-4">
	                <div class="form-group">
	                  <label>Pabrik :</label>
	                  <select name="pabrik" id="pabrik" class="form-control select2" style="width: 100%;" required disabled>
	                    <option value="" selected>Silahkan Pilih</option>
	                    <?php
	                      foreach ($pabrik as $pabrik) {
	                        echo "<option value='".$pabrik->id_pabrik."' selected>".$pabrik->nama."</option>";
	                      }
	                    ?>
	                  </select>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Tipe Input :</label>
	                  <select name="tipe" id="tipe" class="form-control select2" style="width: 100%;" required>
	                    <option value="" selected>Silahkan Pilih</option>
	                    <option value="IN">Limbah Masuk</option>
	                    <option value="OUT">Limbah Keluar</option>
	                  </select>
	                </div>
	              </div>
	              <div class="col-md-3">
	                <div class="form-group">
	                  <label>Tanggal :</label>
	                  <div class="input-group date">
	                    <div class="input-group-addon">
	                      <i class="fa fa-calendar"></i>
	                    </div>
		                <input type="text" name="tanggal" id="tanggal" class="datePicker init" style="width:100%; height:32px;padding:10px;" required readonly>
	                  </div>
	                </div>
	              </div>

	              <div class="clearfix"></div>

	              <div id="divIn">
		              <div class="col-md-4">
		                <div class="form-group">
		                  <label>Jenis Limbah :</label>
		                  <select name="jenislimbah" id="jenislimbah" class="form-control select2" style="width: 100%;" required onchange="jenislimbah_masuk()">
		                    <option value="" selected>Silahkan Pilih</option>
		                    <?php
		                      foreach ($limbah as $limbah) {
		                        echo "<option value='".$limbah->id."'>".$limbah->jenis_limbah."</option>";
		                      }
		                    ?>
		                  </select>
		                </div>
		              </div>
		              <div class="col-md-3">
		                <div class="form-group">
		                  <label>Sumber Limbah :</label>
		                  <select name="sumberlimbah" id="sumberlimbah" class="form-control select2" style="width: 100%;" required>
		                    <option value="" selected>Silahkan Pilih</option>
		                    <?php
		                      foreach ($sumberlimbah as $sumberlimbah) {
		                        echo "<option value='".$sumberlimbah->id."'>".$sumberlimbah->sumber_limbah."</option>";
		                      }
		                    ?>
		                  </select>
		                </div>
		              </div>
		              <div class="col-md-3">
		                <div class="form-group">
		                  <label>Quantity :</label>
		                  <input type="text" name="qty" id="qty" class="init" style="width:100%;height:32px;padding:10px;text-align:right;" required autocomplete="off">
		                </div>
		              </div>
		          </div>

	              <div class="clearfix"></div>
	              <div id="divOut">
		          </div>
	            
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
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/she/transaction/limbahb3_inputdata.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>
