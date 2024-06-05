<!--
/*
@application    : SKYNET 
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css">
<style type="text/css">
	.listframe {
		border: 1px solid #ccc;
		padding: 4px;
		min-height: 50px;
		background-color: #efe6e6;
	}

	.boximg {
		margin: 1em;
		text-align: center;
	}

	.boximg img {
		vertical-align: middle;
	}

	.fullsize {
		display: none;
	}

	.boximg--thumb:hover+.boximg--fullsize>.fullsize {
		display: initial;
	}
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
							<form method="POST" id="filterform" action="<?php echo base_url() ?>skynet/transaction/ticket/admin" class="filter-ticket" role="form">
								<div class="col-md-2 pull-right" style="margin-top: -20px;">
									<div class="form-group pull-right">
										<a href='#' class='excel btn btn-primary'><i class='fa fa-file-excel-o'></i> Cetak Excel</a>
									</div>
								</div>

								<div class="clearfix"></div>

								<div class="col-md-8" style="margin-top: 20px; padding-left:0px;">
									<div class="col-md-4">
										<div class="form-group">
											<label>Pabrik</label>
											<select data-placeholder="Pilih Pabrik" name="filterpabrik[]" id="filterpabrik" multiple class="form-control" style="width: 100%;" required onchange="filtersubmit()">
												<?php
												foreach ($pabrik as $key => $dt) {
													// if($dt->plant == $filterpabrik){
													// 	$selected = "selected";
													// }else{
													// 	$selected = "";
													// }
													$selected = (in_array($dt->plant, $filterpabrik)) ? "selected='selected'" : "";
													echo "<option value='" . $dt->plant . "' " . $selected . ">" . $dt->plant_name . "</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Status</label>
											<select name="filterstatus" id="filterstatus" class="form-control select2" style="width: 100%;" onchange="filtersubmit()">
												<option value="">Select All</option>
												<?php
												foreach ($status as $key => $dt) {
													if ($dt->id_hd_status == $filterstatus) {
														$selected = "selected";
													} else {
														$selected = "";
													}
													echo "<option value='" . $this->generate->kirana_encrypt($dt->id_hd_status) . "' " . $selected . ">" . $dt->status . "</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Category</label>
											<select name="filterkategori" id="filterkategori" class="form-control select2" style="width: 100%;" onchange="filtersubmit()">
												<option value="">Select All</option>
												<?php
												foreach ($kategori as $key => $dt) {
													if ($dt->id_hd_kategori == $filterkategori) {
														$selected = "selected";
													} else {
														$selected = "";
													}
													echo "<option value='" . $this->generate->kirana_encrypt($dt->id_hd_kategori) . "' " . $selected . ">" . $dt->kategori . "</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-3">
										<div class="form-group">
											<label>From </label>
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
											<label>To </label>
											<div class="input-group date">
												<div class="input-group-addon">
													<i class="fa fa-calendar"></i>
												</div>
												<input type="text" class="form-control datePicker" style="padding: 10px;" placeholder="dd.mm.yyyy" id="filterto" name="filterto" value="<?php echo $filterto; ?>" readonly required onchange="filtersubmit()">
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-4" style="margin-top: 20px; padding-left:0px;">
									<div class="form-group pull-right row">
										<img src='<?php echo base_url() ?>assets/apps/img/skynet.png'>
									</div>
									<div class="form-group pull-right row">
										<div class="col-md-4 pull-right">
											<input type="text" nama="total_ticket" id="total_ticket" class="form-control text-center" disabled>
										</div>
										<div class="col-md-8 pull-right row">
											<label class="pull-right">Total Ticket :</label>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="form-group pull-right row">
										<div class="col-md-4 pull-right">
											<input type="text" nama="total_actual" id="total_actual" class="form-control text-center" disabled>
										</div>
										<div class="col-md-8 pull-right">
											<label class="pull-right">Total Actual :</label>
										</div>
									</div>
								</div>
							</form>
						</div>

					</div>
					<!-- /.box-header -->

					<div class="box-body" style="margin-top: -160px">
						<div class="row">
							<div class="col-md-12">
								<legend style="font-size: 14px;"><label> List Data </label></legend>
								<table width="100%" class="table table-bordered table-striped my-datatable-extends-order">
									<thead>
										<tr>
											<th class='text-center'>No. Ticket</th>
											<th class='text-center'>Requestor</th>
											<th class='text-center'>Date Open</th>
											<th class='text-center'>Date Pickup By Agent</th>
											<th class='text-center'>Response Time</th>
											<th class='text-center'>Date Close By Agent</th>
											<th class='text-center'>Date Close By User</th>
											<th class='text-center'>Leadtime</th>
											<th class='text-center'>Actual</th>
											<th class='text-center'>Agent</th>
											<th class='text-center'>Category</th>
											<th class='text-center'>Sub Category</th>
											<th class='text-center'>Title</th>
											<th class='text-center'>Keterangan</th>
											<th class='text-center'>Lokasi</th>
											<th class='text-center'>Status</th>
											<th width="1px"></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$total_actual 	= 0;
										$no 			= 0;
										foreach ($ticket as $key => $dt) {
											$no = $key + 1;
											echo "<tr>";
											echo "<td align='center'>" . $dt->no_ticket . "</td>";
											echo "<td>" . $dt->nama . "</td>";
											echo "<td>" . $this->generate->generateDatetimeFormat($dt->tanggal_awal) . "</td>";
											echo "<td>" . ($dt->open_tiket_end ? $this->generate->generateDatetimeFormat($dt->open_tiket_end) : '-') . "</td>";
											echo "<td>" . ($dt->responsetime ? $dt->responsetime : '-') . "</td>";
											echo "<td>" . ($dt->tglwaktu_touser ? $this->generate->generateDatetimeFormat($dt->tglwaktu_touser) : '-') . "</td>";
											echo "<td>" . ($dt->tglwaktu_userclose ? $this->generate->generateDatetimeFormat($dt->tglwaktu_userclose) : '-') . "</td>";
											echo "<td>" . ($dt->leadtime ? $dt->leadtime : '-') . "</td>";
											if ($dt->actual == "2" || $dt->actual == "1" || $dt->actual == "0") {
												$actualtable = "1";
											} elseif ($dt->actual >= "3") {
												$actualtable = $dt->actual;
											} else {
												$actualtable = "";
											}
											echo "<td>" . $actualtable . "</td>";
											echo "<td>" . $dt->agent . "</td>";
											$total_actual += $actualtable == "" ? 0 : $actualtable;
											echo "<td>" . $dt->kategori . "</td>";
											echo "<td>" . $dt->nama_subkategori . "</td>";
											echo "<td>" . $dt->title . "</td>";
											if(($dt->downtime_tiket_begin!=null)&&($dt->downtime_tiket_end!=null)){
											echo "<td>" . $dt->keterangan . "<br><b>Downtime:</b><br><b>Start :</b>".$this->generate->generateDatetimeFormat(@$dt->downtime_tiket_begin)." <br><b>End :</b> ".$this->generate->generateDatetimeFormat(@$dt->downtime_tiket_end)."</td>";	
											}else{
											echo "<td>" . $dt->keterangan . "</td>";	
											}
											echo "<td>" . $dt->lokasi . "</td>";
											echo "<td><span class = 'badge " . $dt->warna . "'>" . $dt->status . "</span></td>";
											echo "<td align='center'>";
											echo "<div class='input-group-btn'>";
											echo "<button type='button' class='btn btn-xs btn-default dropdown-toggle' data-toggle='dropdown'><span
								                                class='fa fa-th-large'></span></button>";
											echo "<ul class='dropdown-menu pull-right'>";
											if ($dt->id_hd_status == 4) {
												echo "<li><a href='#' class='history' data-history='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-history'></i> History</a></li>";
											} elseif ($dt->id_hd_status == 2) {
												if (date_format(date_create($dt->tanggal_awal), "Y-m-d H:i:s") <= date("Y-m-d H:i:s"))
													echo "<li><a href='#' class='pending_user' data-pending_user='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-users'></i> Set Pending User</a></li>";
												echo "<li><a href='#' class='history' data-history='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-history'></i> History</a></li>";
												echo "<li><a href='#' class='attachment' data-attachment='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-search'></i> View Attachment</a></li>";
											} elseif ($dt->id_hd_status == 1) {
												if (date_format(date_create($dt->tanggal_awal), "Y-m-d H:i:s") <= date("Y-m-d H:i:s"))
													echo "<li><a href='#' class='pending_user' data-pending_user='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-users'></i> Confirm Open Ticket</a></li>";
												echo "<li><a href='#' class='history' data-history='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-history'></i> History</a></li>";
												echo "<li><a href='#' class='attachment' data-attachment='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-search'></i> View Attachment</a></li>";
											} else {
												echo "<li><a href='#' class='history' data-history='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-history'></i> History</a></li>";
												echo "<li><a href='#' class='attachment' data-attachment='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-search'></i> View Attachment</a></li>";
												echo "<li><a href='#' class='forceclose_ticket' data-forceclose='" . $this->generate->kirana_encrypt($dt->id_hd_ticket) . "' data-toggle='modal' data-target='#modal-form'><i class='fa fa-times-circle'></i> Set Force Close</a></li>";
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
						<input type="hidden" name="totticket" id="totticket" value="<?php echo $no; ?>">
						<input type="hidden" name="totactual" id="totactual" value="<?php echo $total_actual; ?>">
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
								<div class="col-md-12">
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.custom.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.theme.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/prettify.css" />

<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/prettify.js"></script>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css" />
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>

<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/skynet/user_ticket.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#filterpabrik').multiselect({
			classes: 'form-control',
			buttonWidth: '100%',
			menuHeight: '200px',
			menuWidth: '100%'
		}).multiselectfilter();
	});
</script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


<!---------datetimepitcher------------>
<style>
hr {
    margin-top: 10px;
    margin-bottom: 10px;
}

.datepicker > div {
    display: block;
}

.bootstrap-datetimepicker-widget.dropdown-menu {
    z-index: 9999 !important;
}

.disabled.day {
    color: #ccc !important;
}

.disabled.day.traveled {
    color: #77bacc !important;
}

.weekend.day {
    color: #ff3333 !important;
}

.active.day {
    border-color: unset;
    border-radius: 0 !important;
    color: white !important;
}

.active.day {
    background-color: #005f33 !important;
}

.day.new, .day.old {
    visibility: hidden;
}

.active.selected.day, .selected.day, .range.day {
    border-color: unset;
    border-radius: 0 !important;
    color: white !important;
}

.active.selected.day {
    background-color: #005f33 !important;
}

.range.disabled.weekend {
    background-color: #ff3333 !important;
    color: white !important;
}

.range.disabled.cuti {
    background-color: #39a0ff !important;
    color: white !important;
}

.range.day:not(.disabled), .selected.day {
    /*background-color: rgba(0, 141, 76, 0.8) !important;*/
    background-color: #008d4c !important;
}

.bootstrap-datetimepicker-widget.dropdown-menu {
    z-index: 9999 !important;
}

.bootstrap-datetimepicker-widget {
    box-shadow: 0 2px 4px rgba(0, 0, 0, .175) !important;
}

.bootstrap-datetimepicker-widget table thead > tr > th {
    padding-right: 5px !important;
}



.bootstrap-datetimepicker-widget {z-index: 9999 !important;}
</style>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/animatecss/animate.min.css" />
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css" />
<!---------datetimepitcher------------>

