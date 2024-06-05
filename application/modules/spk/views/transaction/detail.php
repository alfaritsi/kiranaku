<?php
$this->load->view('header')
?>
<div class="content-wrapper">
	<section class="content">
		<!-- Box View -->
		<div id="box-view">
			<!-- <div class="row">
				<div class="col-md-12">
					<button type="button" class="btn btn-success" onclick="back();" style="width:100px;"><i class="fa fa-angle-left"></i> Kembali</button>
				</div>
			</div>
			<br> -->
			<div class="row">
				<div class="col-md-12">
					<div class="box box-success">
						<div class="box-header">
							<div class="row">
								<div class="col-xs-12">
									<h1 class="box-title" style="vertical-align:middle;">
										<i class="fa fa-file-o" style="font-size: 22px;"></i> <span id="view_judulSPK" style="font-size: 22px;"><?php echo $data_spk->plant . ' / ' . $data_spk->jenis_spk . ' / <i class="fa fa-user-circle"></i> ' . $data_spk->nama_vendor; ?></span>
									</h1>
									<div class="pull-right" id="view_action_button"></div>
								</div>
							</div>
							<hr>
							<!-- <div class="row">
								<div class="col-sm-12" id="view_action_button"></div>
							</div> -->
							<div class="row invoice-info">
								<div class="col-sm-4 invoice-col">
									<strong>Jenis Perjanjian :</strong> <span><?php echo $data_spk->jenis_spk; ?></span><br>
									<!-- <strong>SPPKP :</strong> <span><?php echo $data_spk->SPPKP; ?></span><br> -->
									<strong>Perihal :</strong> <span><?php echo $data_spk->perihal; ?></span><br>
								</div>
								<div class="col-sm-4 invoice-col">
									<strong>Tanggal Perjanjian :</strong> <span><?php echo $data_spk->tanggal_perjanjian_format; ?></span><br>
									<strong>Tanggal Berlaku :</strong> <span><?php echo $data_spk->tanggal_berlaku_format . " - " . $data_spk->tanggal_berakhir_format; ?></span><br>
									<strong>No Perjanjian :</strong> <span><?php echo $data_spk->nomor_spk; ?></span>
								</div>
								<div class="col-sm-4 invoice-col">
									<?php
									$status = "";
									$file = "";
									switch ($data_spk->status) {
										case 'confirmed':
											$status = '<div class="badge bg-navy">CONFIRMED</div>';
											$status .= '<br><small>Menunggu dokumen final draft</small>';
											break;
										case 'finaldraft':
											$status = '<div class="badge bg-purple">FINAL DRAFT</div>';
											if (isset($data_spk->files_1)) {
												$link_file = site_url('spk/view_file?file=' . $data_spk->files_1);
												$file = '<strong>Dokumen Final Draft :</strong> <span><a href="' . $link_file . '" data-fancybox><span class="badge bg-red-gradient"><i class="fa fa-file"></i></span> </a></span><br>';
											}
											break;
										case 'completed':
											$status = '<div class="badge bg-maroon">COMPLETED</div>';
											if (isset($data_spk->files)) {
												$link_file = site_url('spk/view_file?file=' . $data_spk->files);
												$file = '<strong>Dokumen Final Perjanjian :</strong> <span><a href="' . $link_file . '" data-fancybox><span class="badge bg-red-gradient"><i class="fa fa-file"></i></span> </a></span><br>';
											}
											break;
										case 'drop':
											$status = '<div class="badge bg-yellow">DROP</div>';
											break;
										case 'cancelled':
											$status = '<div class="badge bg-yellow">CANCELLED</div>';
											// $status .= '<br><small>Dicancel oleh ' . $data_spk->status_spk_cancel . '</small>';
											break;
										default:
											$sts = ($data_spk->status_spk && $data_spk->status_spk !== NULL) ? substr($data_spk->status_spk, 0, -1) : "";
											$status = '<div class="badge bg-green">ON PROGRESS</div>';
											$status .= '<br><small>Sedang diproses oleh <b>' . $sts . '</b></small>';
											if ($data_spk->paralel === 1) {
												$divisis = explode(",", strtolower($data_spk->nama_divisi_terkait), -1);
												$nama_divisi = implode(", ", array_map('ucwords', $divisis));
												$status .= '<br><small>Divisi Terkait: ' . $nama_divisi . '</small>';
											}
											break;
									}
									echo "<strong>Status :</strong> <span>" . $status . "</span><br>";
									echo $file;
									echo (!empty($data_spk->tanggal_kirim) ? "<strong>Tanggal Kirim :</strong> <span>" . $this->generate->generateDateFormat($data_spk->tanggal_kirim) . "</span><br>" : '');
									echo (!empty($data_spk->no_resi) ? "<strong>No. Resi :</strong> <span>" . $data_spk->no_resi . "</span><br>" : '');
									?>
									<div id="view_status_completed"></div>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab_dokumen" data-toggle="tab" aria-expanded="true">Dokumen Pendukung</a></li>
									<li class="">
										<a href="#tab_komentar" data-toggle="tab" aria-expanded="false" onclick="show_komentar();">
											Komentar
											<span id="view_jumlah_komentar" class="badge bg-yellow"><?php echo ($data_spk->jumlah_komentar > 0) ? $data_spk->jumlah_komentar : ""; ?></span>
										</a>
									</li>
									<!-- <li class=""><a href="#tab_history" data-toggle="tab" aria-expanded="false">History</a></li> -->
								</ul>

								<div class="tab-content" style="min-height: 300px;">
									<!-- tab dokumen Perjanjian -->
									<div class="tab-pane active" id="tab_dokumen">
										<div class="row">
											<!-- dokumen template -->
											<div class="col-sm-12 col-md-4">
												<fieldset class="fieldset-success">
													<legend>Dokumen Templatexxyy</legend>
													<div class="box-body table-responsive" id="tb-dokumen-template" style="padding-bottom: 70px !important;">
														<table table-bordered table-doc>
															<tbody id="view_dokumen_templatexx"></tbody>
														</table>
													</div>
												</fieldset>
											</div>
											<!-- dokumen vendor -->
											<div class="col-sm-12 col-md-4">
												<fieldset class="fieldset-success">
													<legend>Dokumen Vendor</legend>
													<div class="box-body  table-responsive" id="tb-dokumen-vendor" style="padding-bottom: 40px !important;">
														<table table-bordered table-doc>
															<tbody id="view_dokumen_vendor"></tbody>
														</table>
													</div>
												</fieldset>
											</div>
											<!-- dokumen kualifikasi -->
											<div class="col-sm-12 col-md-4">
												<fieldset class="fieldset-success">
													<legend>Dokumen Kualifikasi</legend>
													<div class="box-body table-responsive" id="tb-dokumen-kualifikasi" style="padding-bottom: 40px !important;">
														<table table-bordered table-doc>
															<tbody id="view_dokumen_kualifikasi"></tbody>
														</table>
													</div>
												</fieldset>
											</div>
										</div>
									</div>
									<!-- tab komentar Perjanjian -->
									<div class="tab-pane" id="tab_komentar">
										<div class="direct-chat-messages" style="min-height: 300px;" id="chat-body">
											<div class="direct-chat-msg hide template-left-chat">
												<div class="direct-chat-info clearfix">
													<span class="direct-chat-name pull-left">User</span>
													<span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
												</div>
												<!-- /.direct-chat-info -->
												<img class="direct-chat-img" alt="message user image">
												<!-- /.direct-chat-img -->
												<div class="direct-chat-text">
													-
												</div>
												<!-- /.direct-chat-text -->
											</div>
											<div class="direct-chat-msg right hide template-right-chat">
												<div class="direct-chat-info clearfix">
													<span class="direct-chat-name pull-right">User</span>
													<span class="direct-chat-timestamp pull-left">23 Jan 2:00 pm</span>
												</div>
												<!-- /.direct-chat-info -->
												<img class="direct-chat-img" alt="message user image">
												<!-- /.direct-chat-img -->
												<div class="direct-chat-text">
													-
												</div>
												<!-- /.direct-chat-text -->
											</div>
										</div>
										<!-- form komentar -->
										<form id="form-komentar">
											<div class="input-group">
												<input type="hidden" name="id_spk" value="<?php echo $data_spk->id_spk; ?>">
												<!--<input type="text" name="komentar" placeholder="Type Message ..." class="form-control" maxlength="150">-->
												<textarea class="form-control" name="komentar" id="komentar" placeholder="Type Message ..." style="margin: 0px; width: 95%; height: 71px;" maxlength="255"></textarea>
												<span class="input-group-btn">
													<button name="btn_komentar" type="button" class="btn btn-warning btn-flat">Send</button>
												</span>
											</div>
										</form>
									</div>
									<!-- tab history Perjanjian -->
									<!-- <div class="tab-pane" id="tab_history">
										<div class="row">
											<div class="col-md-12 table-responsive">
												<table class="table table-bordered table-striped " id="tbhistorispk">
													<thead>
														<tr>
															<th>Tanggal</th>
															<th>Status</th>
															<th>Comment</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($data_spk->history as $dt) {
															echo "<tr>";
															echo "<td>" . $dt->tgl_status_format . " " . $dt->jam_status_format . "</td>";
															echo "<td><span style='text-transform: capitalize'>" . $dt->action . "</span> oleh <br>" . $dt->nama_role . " : " . $dt->nama . (($dt->nama_divisi) ? " (" . $dt->nama_divisi . ")" : "") . "</td>";
															echo "<td>" . $dt->comment . "</td>";
															echo "</tr>";
														} ?>
													</tbody>
												</table>
											</div>
										</div>
									</div> -->
								</div>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden" id="id_spk" value="<?php echo $data_spk->id_spk; ?>">
							<input type="hidden" id="id_kualifikasi" value="<?php echo $data_spk->id_kualifikasi; ?>">
							<input type="hidden" id="jumlah_komentar" value="<?php echo $data_spk->jumlah_komentar; ?>">

							<!-- SUBMIT -->
							<?php
							if (
								isset($data_spk) //show only spk data is found
								&& $data_spk->akses == 1 //show only have access (status login == status spk)
								// (in_array($data_spk->last_action->action, ['create', 'edit'])) //if last action create or edit
								&& ($data_spk->if_approve)
								&& $data_spk->status == $data_spk->level_owner
							) :
							?>
								<button type="button" value="submit" name="action_btn" class="btn btn-success">Submit</button>
							<?php
							endif;
							?>

							<!-- APPROVE -->
							<?php
							if (
								isset($data_spk) //show only spk data is found
								&& $data_spk->akses == 1 //show only have access (status login == status spk)
								&& (!in_array($data_spk->last_action->action, ['create', 'edit'])) //if last action not in create or edit
								&& ($data_spk->if_approve) //show only when user have access approve
								&& $data_spk->status !== $data_spk->level_owner
							) :
							?>
								<button type="button" value="approve" name="action_btn" class="btn btn-success">Approve</button>
							<?php
							endif;
							?>

							<!-- DECLINE -->
							<?php
							if (
								isset($data_spk) //show only spk data is found
								&& $data_spk->akses == 1 //show only have access (status login == status spk)
								&& (!in_array($data_spk->last_action->action, ['create', 'edit'])) //if last action not in create or edit
								&& ($data_spk->if_decline) //show only when user have access decline
								&& $data_spk->status !== $data_spk->level_owner
							) :
							?>
								<button type="button" value="decline" name="action_btn" class="btn btn-warning">Decline</button>
							<?php
							endif;
							?>

							<!-- Upload Final Draft -->
							<?php
							if (
								isset($data_spk) && //show only spk data is found
								in_array($data_spk->status, ["confirmed", "finaldraft"])
								&& $data_spk->akses_final_draft
							) :
							?>
								<button type="button" class="btn btn-primary spk-final-draft" data-id_spk="<?php echo $data_spk->id_spk; ?>" data-jenis_spk="<?php echo $data_spk->jenis_spk; ?>" data-nomor_spk="<?php echo $data_spk->nomor_spk; ?>">Final Draft</button>
							<?php
							endif;
							?>

							<!-- Upload Final Draft -->
							<?php
							if (
								isset($data_spk) //show only spk data is found
								&& $data_spk->status == "finaldraft"
								&& $data_spk->akses_final_spk
							) :
							?>
								<button type="button" class="btn btn-primary spk-final-spk" data-id_spk="<?php echo $data_spk->id_spk; ?>" data-jenis_spk="<?php echo $data_spk->jenis_spk; ?>" data-nomor_spk="<?php echo $data_spk->nomor_spk; ?>">SCAN Perjanjian</button>
							<?php
							endif;
							?>

							<!-- CANCEL -->
							<?php
							if (
								isset($data_spk) //show only spk data is found
								&& ($data_spk->akses == 1 || ($data_spk->akses_final_draft && in_array($data_spk->status, ["confirmed", "finaldraft"]))) //show only have access (status login == status spk)
								&& $data_spk->akses_cancel_spk == 1 //show only have access
								&& !in_array($data_spk->status, array("cancelled", "completed"))
								&& $data_spk->tanggal_submit
							) :
							?>
								<button type="button" value="cancel" name="action_btn" class="btn btn-danger">Cancel</button>
							<?php
							endif;
							?>

							<!-- DROP -->
							<!-- <?php
									if (
										isset($data_spk) //show only spk data is found
										&& $data_spk->akses == 1
										&& $data_spk->akses_hapus == 1 //show only have access
										// && (!in_array($data_spk->last_action->action, ['create', 'edit'])) //if last action not in create or edit
									) :
									?>
								<button type="button" class="btn btn-warning spk_drop" data-id_spk="<?php echo $data_spk->id_spk; ?>" data-jenis_spk="<?php echo $data_spk->jenis_spk; ?>" data-nomor_spk="<?php echo $data_spk->nomor_spk; ?>">Drop</button>
							<?php
									endif;
							?> -->

							<!-- DELETE -->
							<?php
							if (
								isset($data_spk) //show only spk data is found
								&& (
									($data_spk->akses == 1 && !$data_spk->tanggal_submit)
									||
									($data_spk->akses_cancel_spk == 1 && !in_array($data_spk->status, ['finaldraft', 'completed', 'cancelled']))
								)
							) :
							?>
								<button type="button" class="btn btn-default spk_delete" data-id_spk="<?php echo $data_spk->id_spk; ?>" data-jenis_spk="<?php echo $data_spk->jenis_spk; ?>" data-nomor_spk="<?php echo $data_spk->nomor_spk; ?>">Hapus</button>
							<?php
							endif;
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('transaction/includes/modal_attachments') ?>
<?php $this->load->view('transaction/includes/modal_spk', compact('plant')) ?>
<?php $this->load->view('transaction/includes/modal_upload') ?>
<?php $this->load->view('transaction/includes/modal_action') ?>
<?php $this->load->view('transaction/includes/modal_final_draft') ?>
<?php $this->load->view('transaction/includes/modal_final') ?>
<?php $this->load->view('transaction/includes/modal_download') ?>
<?php $this->load->view('transaction/includes/modal_drop') ?>
<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css" />
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/spk.global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/detail_spk.js?<?php echo time(); ?>"></script>