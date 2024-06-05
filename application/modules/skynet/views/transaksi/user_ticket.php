<!--
/*
@application    : SKYNET 
@author 		: Syah Jadianto (8604)
@contributor	: 
			1. Airiza Yuddha (7849) 22.05.2020
         		add proses open tiket			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<style type="text/css">
  .listframe { border:1px solid #ccc; padding: 4px; min-height: 50px; background-color: #efe6e6;}
</style>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	            		<div class="clearfix"></div>

	            		<div class="row">
			            	<div class="col-md-2 pull-right" style="margin-top: -20px;">
				                <div class="form-group pull-right">
						            <!-- <button type="button" class="add btn btn-default" data-toggle="modal" data-target="#modal-form"> -->
						           	<a href='#' class='add btn btn-default' data-add='<?php echo base64_decode($this->session->userdata("-ho-")) == "y" ? "KMTR" : base64_decode($this->session->userdata("-gsber-")); ?>' data-toggle='modal' data-target='#modal-form'>
						              <i class="fa fa-plus"></i> Tambah Tiket
						            </a>
						            <!-- </button> -->
				                </div>
			            	</div>
			            </div>

			            <div class="row">
						    <form method="POST" id="filterform" action="<?php echo base_url() ?>skynet/transaction/ticket/user" class="filter-ticket" role="form">
				              	<div class="col-md-8" style="margin-top: 20px; padding-left:0px;">
					              	<div class="col-md-3">
						                <div class="form-group">
						                  <label>Status</label>
						                  <select name="filterstatus" id="filterstatus" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
						                  	<option value=""></option>
						                    <?php
						                      foreach ($status as $key => $dt) {
						                      	if($dt->id_hd_status == $filterstatus){
						                      		$selected = "selected";
						                      	}else{
						                      		$selected = "";
						                      	}
							                    echo "<option value='".$this->generate->kirana_encrypt($dt->id_hd_status)."' ".$selected.">".$dt->status."</option>";
						                      }
						                    ?>
						                  </select>
						                </div>
					              	</div>
					            	<div class="col-md-3">
						                <div class="form-group">
						                  <label>Category</label>
						                  <select name="filterkategori" id="filterkategori" class="form-control select2" style="width: 100%;" required onchange="filtersubmit()">
						                  	<option value=""></option>
						                    <?php		
						                      foreach ($kategori as $key => $dt) {
						                      	if($dt->id_hd_kategori == $filterkategori){
						                      		$selected = "selected";
						                      	}else{
						                      		$selected = "";
						                      	}
							                    echo "<option value='".$this->generate->kirana_encrypt($dt->id_hd_kategori)."' ".$selected.">".$dt->kategori."</option>";
						                      }
						                    ?>
						                  </select>
						                </div>
					            	</div>
					            	<div class="col-md-3">
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
					            	<div class="col-md-3">
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

				              	<div class="col-md-4">
					                <div class="form-group pull-right">
					                	<img src='<?php echo base_url() ?>assets/apps/img/skynet.png'>
					                </div>
								</div>
						    </form>
						</div>
			            
	          		</div>
	          		<!-- /.box-header -->

		          	<div class="box-body">
			        	<div class="row">
			        		<div class="col-md-12">
				        		<legend style="font-size: 14px;"><label> List Data </label></legend>
				           		<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
				              		<thead>
								        <tr>
											<th width="1px" class='text-center'>No</th>          
											<th class='text-center'>No. Ticket</th>          
											<th class='text-center'>Requestor</th>          
											<th class='text-center'>Create Date</th>          
											<th class='text-center'>Category</th>          
											<th class='text-center'>Sub Category</th>          
											<th class='text-center'>Title</th>          
											<th class='text-center'>Keterangan</th>          
											<th class='text-center'>Status</th>          
											<th class='text-center'>Agent</th>          
											<th width="1px"></th> 
								        </tr>
						            </thead>
					              	<tbody>
						                <?php
							                foreach($ticket as $key => $dt){
							                  $no = $key + 1;
							                  echo "<tr>";
								              echo "<td align='center'>".$no."</td>";
								              echo "<td align='center'>".$dt->no_ticket."</td>";
								              echo "<td>".$dt->nama."</td>";
								              echo "<td>".$this->generate->generateDateFormat($dt->tanggal_buat)."</td>";
								              echo "<td>".$dt->kategori."</td>";
								              echo "<td>".$dt->nama_subkategori."</td>";
								              echo "<td>".$dt->title."</td>";
								              echo "<td>".$dt->keterangan."</td>";
								              echo "<td><span class = 'badge ".$dt->warna."'>".$dt->status."</span></td>";
								              echo "<td>".$dt->agent."</td>";
								              echo "<td align='center'>";
								                echo "<div class='input-group-btn'>";
								                    echo "<button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
								                                class='fa fa-th-large'></span></button>";
								                    echo "<ul class='dropdown-menu pull-right'>";
							                            if($dt->id_hd_status == 4){
								                            echo "<li><a href='#' class='history' data-history='".$this->generate->kirana_encrypt($dt->id_hd_ticket)."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-history'></i> History</a></li>";
							                            }else{
							                            	if($dt->id_hd_status != 1){
								                            	echo "<li><a href='#' class='close_ticket' data-close='".$this->generate->kirana_encrypt($dt->id_hd_ticket)."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-times-circle'></i> Set Close</a></li>";
								                            }
								                            echo "<li><a href='#' class='history' data-history='".$this->generate->kirana_encrypt($dt->id_hd_ticket)."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-history'></i> History</a></li>";
								                            echo "<li><a href='#' class='attachment' data-attachment='".$this->generate->kirana_encrypt($dt->id_hd_ticket)."' data-toggle='modal' data-target='#modal-form'><i class='fa fa-search'></i> View Attachment</a></li>";
							                            }
								                    echo "</ul>";
								                echo "</div>";
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
			</div>
		</div>

	    <!-- Modal -->
	    <div class="modal fade" id="modal-form">
	      <div class="modal-dialog" style="min-width:500px;">
	        <form id="formID" role="form" class="form-ticket">
	          <div class="modal-content">
	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                <span aria-hidden="true">&times;</span></button>
	              <h4 class="modal-title"> <i class='fa fa-plus-circle'></i> <strong> Add Ticket </strong></h4>
	            </div>
	            <div class="modal-body">
	            	<div class="row">
	                	<div class="col-md-12 form-horizontal">
	                		<div id="modal_ticket"></div>
			        	</div>
	            	</div>
		        </div>
	            
	            <div class="modal-footer">
	            	<div class="col-md-12">
	            		<div id="modal_footer_ticket"></div>
	                </div>
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


<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>

<script src="<?php echo base_url() ?>assets/apps/js/skynet/user_ticket.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
.small-box .icon{
    top: -13px;
}
</style>
