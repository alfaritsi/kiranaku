<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>PT. Kirana Megatara Tbk</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/fontawesome/css/font-awesome.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/dataTables.bootstrap.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/select2/select2.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/skins/_all-skins.min.css">
	<link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>assets/apps/img/logo-sm.png" />
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/pace/pace.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert2.min.css" />
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/kirana.css">
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
</head>

<body>

	<body class="skin-green-light sidebar-mini sidebar-collapse fixed">
		<div class="wrapper overlay-wrapper">
			<div class="content-wrapper" style="background-size: cover; margin-left: 0 !important;padding-top:0px;">
				<section class="content">
					<div class="row">
						<div class="col-sm-12 mycontainer">
							<input type="hidden" name="isproses" value="0" />
							<div class="text-center" style="margin-bottom:20px">
								<img src="<?php echo base_url() . "assets/apps/img/Logo_KM_horizontal.png" ?>" />
							</div>
						</div>
					</div>
					<?php if (base64_decode($this->session->userdata("-is_admin-")) == true) : ?>
						<div class="row">
							<div class="col-sm-12 mycontainer">
								<div class="text-center" style="margin-bottom:20px">
									<a href="javascript:void(0)" class="btn btn-default btn-flat"><i class="fa fa-list"></i> List Data</a>
								</div>
							</div>
						</div>
					<?php endif; ?>
					<div class="row">
						<div class="col-sm-12 mycontainer">
							<!-- Horizontal Form -->
							<div class="box box-success">
								<div class="box-header with-border">
									<h3 class="box-title">Instrument Daily Self Assessment Risiko COVID-19</h3>
								</div>
								<!-- /.box-header -->
								<!-- form start -->
								<!--<form class="form-horizontal">-->
								<form role="form" class="form-input-assessment">
									<div class="box-body">
										<div class="form-group">
											<label for="Nama">Nama</label>
											<input type="text" class="form-control" id="nama" placeholder="Nama" value="<?php echo base64_decode($this->session->userdata("-nama-")); ?>" disabled>
										</div>
										<div class="form-group">
											<label for="Divisi">Divisi</label>
											<input type="text" class="form-control" id="divisi" value="<?php echo base64_decode($this->session->userdata("-divisi-")); ?>" placeholder="Divisi" disabled>
										</div>
										<div class="form-group">
											<label for="Perusahaan">Perusahaan</label>
											<input type="text" class="form-control" id="perusahaan" value="<?php echo base64_decode($this->session->userdata("-perusahaan-")); ?>" placeholder="Perusahaan" disabled>
										</div>
										<div class="form-group">
											<label for="Time">Time</label>
											<input type="text" class="form-control" id="time" value="<?php echo date('d-m-Y h:i:s'); ?>" placeholder="Time" disabled>
										</div>										
										<div class="form-group">
											<label for="Lokasi Kerja">Status Kerja</label>
											<select class="form-control select2" name="lokasi_kerja" id="lokasi_kerja" required="required">
												<option></option>
												<?php if (base64_decode($this->session->userdata("-ho-")) == 'y') : ?>
													<option value='wfh' lokasi="true">WFH (Work From Home)</option>
													<option value='wfo' lokasi="false">WFO (Work From Office)</option>
													<option value='si_wfh' lokasi="true">SI (Isolasi Mandiri) - WFH</option>
													<option value='si_cuti' lokasi="true">SI (Isolasi Mandiri) - Cuti</option>
													<option value='si_ijin' lokasi="true">SI (Isolasi Mandiri) - Ijin</option>
													<option value='absen' lokasi="true">Cuti/Sakit/Ijin</option>
													<option value='libur' lokasi="true">Off/Libur</option>
												<?php else : ?>
													<option value='mobtas' lokasi="false">Mobilitas Terbatas</option>
													<option value='si_wfh' lokasi="false">SI (Isolasi Mandiri) - WFH</option>
													<option value='si_cuti' lokasi="false">SI (Isolasi Mandiri) - Cuti</option>
													<option value='si_ijin' lokasi="false">SI (Isolasi Mandiri) - Ijin</option>
													<option value='kerja_pabrik' lokasi="false">Kerja dari Pabrik</option>
													<option value='absen' lokasi="true">Cuti/Sakit/Ijin</option>
													<option value='libur' lokasi="false">Off/Libur</option>
												<?php endif; ?>
											</select>
										</div>
										<div class="form-group">
											<label for="Lokasi">Lokasi</label>
											<textarea name="lokasi" class="form-control" readonly="readonly" required>-</textarea>
										</div>
										<div class="form-group">
											<label for="Suhu Badan Saat Ini">Suhu Badan Saat Ini</label>
											<input type="text" class="form-control angka" min="33" max="45" name="suhu_tubuh" id="suhu_tubuh" placeholder="Suhu" required="required">
										</div>
										<p>
											Demi kesehatan dan keselamatan bersama ditempat kerja, anda harus <b>JUJUR</b> menjawab pertanyaan di bawah ini!
										</p>
										<fieldset class="fieldset-info">
											<legend>Covid-19 Care</legend>
											<p>
												Berisi 6 pertanyaan dengan bobot yang berbeda.<br>
												Pertanyaan no 1-4, mengisi “YA” nilai 1, mengisi “TIDAK” nilai 0<br>
												Pertanyaan no 5-6, mengisi “YA” nilai 5, mengisi “TIDAK” nilai 0<br>
												<b>Dalam 14 hari terakhir, apakah anda pernah mengalami hal-hal dibawah ini?</b>
											</p>
											<table class="table table-striped">
												<thead>
													<th>No</th>
													<th>Pertanyaan</th>
													<th>Ya</th>
													<th>Tidak</th>
												</thead>
												<tbody>
													<?php
													$all_pertanyaan = "";
													foreach ($pertanyaan as $dt) {
														$all_pertanyaan .= $dt->id_pertanyaan . ",";
														$id_pertanyaan	 = 'id_pertanyaan_' . $dt->id_pertanyaan;
														$score			 = 'score_' . $dt->id_pertanyaan;
														$catatan		 = 'catatan_' . $dt->id_pertanyaan;
														$hubungan_keluarga	= 'hubungan_keluarga_' . $dt->id_pertanyaan;
														$hubungan_kategori 	= 'hubungan_kategori_' . $dt->id_pertanyaan;
														$suhu_tertinggi 	= 'suhu_tertinggi_' . $dt->id_pertanyaan;
														$gejala 			= 'gejala_' . $dt->id_pertanyaan;
														$riwayat_dokter		= 'riwayat_dokter_' . $dt->id_pertanyaan;
														echo "<input type='hidden' name='$id_pertanyaan' value='" . $dt->id_pertanyaan . "'>";
														echo "<tr>";
														echo "<td>" . $dt->id_pertanyaan . "</td>";
														echo "<td>" . $dt->pertanyaan . "<br>";
														if($dt->id_pertanyaan == 5){
															echo "Jika 'YA', apa hubungannya (Tetangga, Saudara, Mertua, dll):";
															echo "<input type='text' class='form-control' name='$hubungan_keluarga' id='$hubungan_keluarga' placeholder='Masukan Data' readonly>";
															echo "dan apa status kategorinya:";
															echo "
																<select class='form-control select2' name='$hubungan_kategori' id='$hubungan_kategori' disabled>
																	<option></option>
																	<option value='odp'>ODP</option>
																	<option value='pdp'>PDP</option>
																	<option value='positif'>Positif</option>
																</select>";
															//hidden	
															// echo "<input type='hidden' class='form-control' name='$hubungan_kategori' id='$hubungan_kategori' placeholder='Masukan Data'>";	
														}
														if($dt->id_pertanyaan == 6){
															echo "Jika Ya, Berapa suhu tertinggi selama 14 hari terakhir ini:";
															echo "<input type='text' class='form-control angka' name='$suhu_tertinggi' id='$suhu_tertinggi' placeholder='Masukan Data' readonly>";
															echo "Apa gejala/sakitnya:";
															echo "<input type='text' class='form-control' name='$gejala' id='$gejala' placeholder='Masukan Data' readonly>";
															echo "dan jelaskan sudah konsultasi dokter atau dirawat dimana:";
															echo "<input type='text' class='form-control' name='$riwayat_dokter' id='$riwayat_dokter' placeholder='Masukan Data' readonly>";

														}
														echo "</td>";
														$score_class_1 	= $score.'_1';
														$score_class_0 	= $score.'_0';
														$score_val		= $score.'_val';
														echo "<td align='center'>
																<input type='hidden' name='$score_val' value='0'> 
																<input type='radio' class='$score_class_1' name='$score' id='$score' value='" . $dt->score . "' required>
															</td>";
														echo "<td align='center'><input type='radio' class='$score_class_0' name='$score' id='$score' value='0' required></td>";
														echo "</tr>"; 
													}
													echo "<input type='hidden' name='all_pertanyaan' value='" . substr($all_pertanyaan, 0, -1) . "'>";
													echo "<input type='hidden' name='nik' value='" . base64_decode($this->session->userdata("-nik-")) . "'>";
													echo "<input type='hidden' name='nik' value='" . base64_decode($this->session->userdata("-nik-")) . "'>";
													echo "<input type='hidden' name='nama' value='" . base64_decode($this->session->userdata("-nama-")) . "'>";
													echo "<input type='hidden' name='nik' value='" . base64_decode($this->session->userdata("-nik-")) . "'>";
													?>
												</tbody>
											</table>
											<p>*) jika ada gejala sakit yang <b>TIDAK</b> mengarah ke gejala covid-19, dapat diisi di form Kirana Care (kondisi saat ini) di nomer 1.</p>
										</fieldset>
										<fieldset class="fieldset-info">
											<legend>Form Kirana Care (Kondisi Saat Ini)</legend>
											<table class="table table-striped">
												<tbody>
													<?php
													$all_pertanyaan_ganda = "";
													foreach ($pertanyaan_ganda as $dt) {
														$all_pertanyaan_ganda .= $dt->id_pertanyaan . ",";
														$id_pertanyaan_ganda	 = 'id_pertanyaan_ganda_' . $dt->id_pertanyaan;
														$jawaban				 = 'jawaban_' . $dt->id_pertanyaan;
														$catatan_ganda			 = 'catatan_ganda_' . $dt->id_pertanyaan;
														
														$hubungan_keluarga_ganda = 'hubungan_keluarga_ganda_' . $dt->id_pertanyaan;
														$jarak_ganda 			 = 'jarak_ganda_' . $dt->id_pertanyaan;
														$interaksi_ganda 		 = 'interaksi_ganda_' . $dt->id_pertanyaan;
														
														echo "<input type='hidden' name='$id_pertanyaan_ganda' value='" . $dt->id_pertanyaan . "'>";
														echo "<tr>";
														echo "	<td>" . $dt->no . "</td>";
														echo "	<td>";
														if($dt->no==5){
															echo "Pertanyaan no 5 dipecah menjadi 4 bagian sebagai berikut:<br>Apakah saat ini ada yang terkena kasus Covid-19 di lingkungan Anda? Sebutkan kategorinya!<br> (boleh memilih lebih dari satu sesuai kondisinya)?<br><br>";
														}
														echo $dt->pertanyaan;
														echo "<br>";
														if ($dt->jawaban != NULL && $dt->jawaban != "" && $dt->jawaban != "-" && !empty($dt->jawaban)) {
															if ($dt->id_pertanyaan != 6) {
																$data = explode("|", $dt->jawaban);
																$no_class = 0;
																foreach ($data as $key => $file) {
																	if ($file != "") {
																		$no_class++;
																		$jawaban_class = $jawaban . '_' . $no_class;
																		echo "<input type='radio' class='$jawaban_class' name='$jawaban' id='$jawaban' value='$file' required> $file<br/>";
																	}
																}
															} else {
																//hidden
																echo "<input type='hidden' class='form-control' name='$jawaban' id='$jawaban' placeholder='Masukan Data'>";
															}
														} else {
															//hidden
															echo "<input type='hidden' class='form-control' name='$jawaban' id='$jawaban' placeholder='Masukan Data'>";
														}
														// if(($dt->id_pertanyaan!=5)and($dt->id_pertanyaan!=7)){
														if ($dt->id_pertanyaan < 5) {
															if ($dt->id_pertanyaan == 6) {
																echo "<input type='text' class='form-control' name='$catatan_ganda' id='$catatan_ganda' placeholder='Masukan Data' required>";
															} else {
																echo "<div class='col-sm-7'><input type='text' class='form-control' name='$catatan_ganda' id='$catatan_ganda' placeholder='Masukan Data' disabled></div>";
																//hidden
																echo "<input type='hidden' class='form-control' name='$catatan_ganda' id='$catatan_ganda' placeholder='Masukan Data'>";
															}
														} else {
															//hidden
															echo "<input type='hidden' class='form-control' name='$catatan_ganda' id='$catatan_ganda' placeholder='Masukan Data'>";
														}
														
														//tambahan
														if (($dt->id_pertanyaan == 8)or($dt->id_pertanyaan == 9)or($dt->id_pertanyaan == 10)or($dt->id_pertanyaan == 11)){
															echo "
																<div class='col-sm-7'>
																	Apa hubungannya / kaitannya:<br>
																	<input type='text' class='form-control' name='$hubungan_keluarga_ganda' id='$hubungan_keluarga_ganda' placeholder='Masukan Data' readonly>
																</div>";
															echo "
																<div class='col-sm-7'>
																	Jarak dari rumah:<br>
																	<select class='form-control select2' name='$jarak_ganda' id='$jarak_ganda' disabled>
																		<option></option>
																		<option value='<500 M'><500 M</option>
																		<option value='500 M - <1KM'>500 M - <1KM</option>
																		<option value='1 KM - <2KM'>1 KM - <2KM</option>
																		<option value='> 2KM'>> 2KM</option>
																	</select>
																</div>
																";
															echo "
																<div class='col-sm-7'>
																	Apakah anda berinteraksi dengan orang tersebut:<br>
																	<input type='radio' class='$interaksi_ganda' name='$interaksi_ganda' id='$interaksi_ganda' value='1' disabled> Ya	
																	<input type='radio' class='$interaksi_ganda' name='$interaksi_ganda' id='$interaksi_ganda' value='0' disabled> Tidak
																</div>";
														}

														echo "	</td>";
														echo "</tr>";
													}
													echo "<input type='hidden' name='all_pertanyaan_ganda' value='" . substr($all_pertanyaan_ganda, 0, -1) . "'>";
													?>
												</tbody>
											</table>
										</fieldset>
										<!--tambahan berita acara-->
										<div id="div_berita_acara" style="display: none;">
										<fieldset class="fieldset-info" display="none">
											<legend>Berita Acara Pemeriksaan Risiko</legend>
												<input type="hidden" name="jumlah_berita_acara" value="0">
												<input type="button" class="add-row" value="Tambah Baris">
												<table id="table-berita-acara" class="table table-bordered table-striped table-condensed">
													<thead>
														<tr>
															<th>#</th>
															<th>Tanggal</th>
															<th>Gejala yang dirasakan*</th>
															<th>Riwayat Penyakit</th>
															<th>Tindakan yang dilakukan</th>
														</tr>
													</thead> 
													<tbody>
														<tr>
															<td></td> 
															<td><input class='form-control tanggal' type="text" id="tanggal_ba_1" name="tanggal_ba_1" placeholder="Tanggal"></td>
															<td><input class='form-control' type="text" id="gejala_ba_1" name="gejala_ba_1" placeholder="Gejala"></td>
															<td><input class='form-control' type="text" id="riwayat_ba_1" name="riwayat_ba_1" placeholder="Riwayat"></td>
															<td><input class='form-control' type="text" id="tindakan_ba_1" name="tindakan_ba_1" placeholder="Tindakan"></td>
														</tr>
													</tbody>
												</table>
												<div align="right">*Dilengkapi dengan kronologis</div>
												<button type="button" class="delete-row">Hapus Baris</button>
												
										</fieldset>
										</div>
										<?php if(count($berita_acara)>0){?>
										<fieldset class="fieldset-info" display="none">
											<legend>History Inputan Berita Acara Pemeriksaan Risiko</legend>
												<table id="table-berita-acara-history" class="table table-bordered table-striped table-condensed">
													<thead>
														<tr>
															<th>Tanggal</th>
															<th>Gejala yang dirasakan*</th>
															<th>Riwayat Penyakit</th>
															<th>Tindakan yang dilakukan</th>
														</tr>
													</thead> 
													<tbody>
														<?php
															foreach($berita_acara as $dt){
																echo "<tr>";
																echo 	"<td>".$dt->tanggal_ba."</td>";
																echo 	"<td>".$dt->gejala_ba."</td>";
																echo 	"<td>".$dt->riwayat_ba."</td>";
																echo 	"<td>".$dt->tindakan_ba."</td>";
																echo "</tr>";
															}
														?>
													</tbody>
												</table>
												<div align="right">*Dilengkapi dengan kronologis</div>
										</fieldset>
										<?php }?>
										<!-- /. berita acara sampe sini-->

										<!-- /.box-body -->
										<div class="box-footer">
											<button type="button" name="action_btn" class="btn btn-success pull-right" style="margin-left:5px" value="submit">Submit</button>
											<button type="button" name="action_btn" class="btn btn-danger pull-right" style="margin-left:5px" value="back">Kembali</button>
										</div>
										<!-- /.box-footer -->
								</form>
							</div>
						</div>
					</div>
					<!-- /.box -->
				</section>
			</div>
		</div>
	</body>

	<footer class="main-footer" style="margin-left: 0 !important;">
		<div class="pull-right hidden-xs">
			<b>Version</b> 2.0.0 Beta
		</div>
		<strong>Copyright © 2020 Kirana Megatara ICT Division.</strong> All rights reserved.
		<!-- Moment Js -->
		<script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
		<!-- jQuery 2.2.3 -->
		<script src="<?php echo base_url() ?>assets/plugins/jQuery/jquery-3.3.1.min.js"></script>
		<!-- Bootstrap 3.3.6 -->
		<script src="<?php echo base_url() ?>assets/bootstrap/js/bootstrap.min.js"></script>
		<!-- DataTables -->
		<script src="<?php echo base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/datatables/plugins/sorting/datetime-moment.js"></script>
		<!-- SlimScroll -->
		<script src="<?php echo base_url() ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<!-- jQuery Validation -->
		<script src="<?php echo base_url() ?>assets/plugins/jquery-validation/jquery.validate.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/jquery-validation/additional-methods.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/jquery-validation/localization/messages_id.min.js"></script>
		<!-- AdminLTE App -->
		<script src="<?php echo base_url() ?>assets/dist/js/app.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/select2/select2.full.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/pace/pace.min.js"></script>
		<!-- SweetAlert -->
		<script src="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert2.min.js"></script>
		<!-- Datepicker -->
		<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
		<script type="application/javascript" src="<?php echo base_url() . 'assets/apps/js/notifications/notifications.js' ?>"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4Wx7EkE7GguvBWFIDNDjwccEmMldxwo0" async defer></script>
		<script type="text/javascript">
			$(document).ready(function() {
				$(document).on("keyup", ".angka", function(e) {
					var angka = $(this).val().replace(/[^0-9.^-]*/g, '');
					if(angka>44){
						$(this).val('');
					}else{
						$(this).val(angka);
					}
					e.preventDefault();
					return false;
				});

				$(document).on("change", ".angka", function(e) {
					var angka = $(this).val();
					if (angka) {
						angka = parseFloat(angka);
						angka = angka.toFixed(2);
					}
					$(this).val(angka);
					e.preventDefault();
					return false;
				});
				if ($(".select2").length > 0) {
					$(".select2").each(function() {
						$(this).select2({
							allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
							placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
						});
					});
				}

				$(document).on("change", "select[name='lokasi_kerja']", function() {
					$("textarea[name='lokasi']").html("");
					const access_lokasi = $("option:selected", this).attr("lokasi");
					if (access_lokasi === "true") {
						$("textarea[name='lokasi']").closest(".form-group").show();
						let lokasi = sessionStorage.getItem("lokasi");
						if (lokasi) {
							$("textarea[name='lokasi']").html(lokasi);
						} else {
							initLocation();
						}
					} else {
						$("textarea[name='lokasi']").html("Tidak Perlu Lokasi");
						$("textarea[name='lokasi']").closest(".form-group").hide();
					}
				});

				$(document).ajaxStart(function() {
					$("input[name='isproses']").val(1);
					Pace.restart();
					showLoading();
				});

				$(document).ajaxStop(function() {
					$("input[name='isproses']").val(0);
					hideLoading();
				});

				// let lokasi = sessionStorage.getItem("lokasi");
				// if (lokasi) {
				// 	$("textarea[name='lokasi']").html(lokasi);
				// } else {
				// 	initLocation();
				// }
			});

			const initLocation = () => {
				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(searchLocation, errorLocation);
				} else {
					myAlert({
						text: "Geolocation is not supported by this browser.",
						icon: "error",
						html: false,
						reload: false
					});
				}
			}

			const searchLocation = (position) => {
				var latlng = {
					lat: position.coords.latitude,
					lng: position.coords.longitude
				};
				var geocoder = new google.maps.Geocoder;
				geocoder.geocode({
					'location': latlng
				}, function(results, status) {
					if (status === 'OK') {
						if (results[0]) {
							rs = results[0].formatted_address;
							sessionStorage.setItem("lokasi", rs);
						} else {
							rs = 'No results found';
						}
					} else {
						rs = 'Geocoder failed due to: ' + status;
					}
					$("textarea[name='lokasi']").html(rs);
				});
			}

			const errorLocation = (error) => {
				let msg = "Error";
				switch (error.code) {
					case error.PERMISSION_DENIED:
						msg = "User denied the request for Geolocation. Please turn on your Location Service."
						break;
					case error.POSITION_UNAVAILABLE:
						msg = "Location information is unavailable."
						break;
					case error.TIMEOUT:
						msg = "The request to get user location timed out."
						break;
					case error.UNKNOWN_ERROR:
						msg = "An unknown error occurred."
						break;
				}

				myAlert({
					text: msg,
					icon: "error",
					html: false,
					reload: false
				});
			}

			const myAlert = (param) => {
				let {
					title = "Daily Assessment",
						text = "Gagal",
						icon = "error",
						html = false,
						reload = true,
						callback = null
				} = param;

				Swal.fire({
					title: title,
					text: text,
					html: html,
					type: icon
				}).then(function() {
					if (typeof callback === "function")
						callback();
					else {
						if (reload === true) location.reload();
						else if (reload === false) false;
						else location.href = reload;
					}
				});
			}

			const validate = (target = "form") => {
				let count = 0;
				validator = $(target).validate({
					ignore: ".ignore",
					errorClass: "is-invalid",
					validClass: "is-valid",
					errorPlacement: function(error, element) {
						error.addClass("invalid-feedback");
						if ($(element).closest(".form-group").length > 0) {
							$(element).closest(".form-group").append(error);
						} else
							$(element).parent().append(error);
					},
					highlight: function(element, errorClass, validClass) {
						if ($(element).hasClass("select2-hidden-accessible")) {
							if ($(element).closest(".form-group").length > 0) {
								$(element)
									.closest(".form-group")
									.find(".select2-selection")
									.removeClass("select2-valid");
								$(element)
									.closest(".form-group")
									.find(".select2-selection")
									.addClass("select2-invalid");
							} else {
								$(element)
									.parent()
									.find(".select2-selection")
									.removeClass("select2-valid");
								$(element)
									.parent()
									.find(".select2-selection")
									.addClass("select2-invalid");
							}
						} else {
							$(element).removeClass(validClass);
							$(element).addClass(errorClass);
						}
					},
					unhighlight: function(element, errorClass, validClass) {
						if ($(element).hasClass("select2-hidden-accessible")) {
							if ($(element).closest(".form-group").length > 0) {
								$(element)
									.closest(".form-group")
									.find(".select2-selection")
									.removeClass("select2-invalid");
								$(element)
									.closest(".form-group")
									.find(".select2-selection")
									.addClass("select2-valid");
							} else {
								$(element)
									.parent()
									.find(".select2-selection")
									.removeClass("select2-invalid");
								$(element)
									.parent()
									.find(".select2-selection")
									.addClass("select2-valid");
							}
						} else {
							$(element).removeClass(errorClass);
							$(element).addClass(validClass);
						}
					}
				});

				$("select", target).on("select2:close", function(e) {
					$(this).valid();
				});

				if (!$(target).valid())
					count = 1;

				if (count > 0) {
					myAlert({
						text: "Form tidak boleh kosong",
						icon: "warning",
						html: false,
						reload: false
					});
				}

				return count;
			}

			const showLoading = () => {
				var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";

				if ($("body .overlay").length > 0)
					$("body .overlay").remove();

				$("body .modal .modal-content").append(overlay);
				$("body .overlay-wrapper").append(overlay);
			}

			const hideLoading = () => {
				$("body .overlay").remove();
			}
		</script>
		<script src="<?php echo base_url() ?>assets/apps/js/assessment/transaksi/input.js"></script>
		<style type="text/css">
			.select2 {
				width: 100% !important;
			}

			.swal2-container {
				zoom: 1.5;
			}

			@media screen and (min-width: 800px) {
				.mycontainer {
					padding: 0 25%;
				}
			}
		</style>
	</footer>

</html>