<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>PT. Kirana Megatara Tbk</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>assets/apps/img/logo-sm.png" />
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
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/pace/pace.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert2.min.css" />
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css" />
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
					<div class="row">
						<div class="col-sm-12 mycontainer">
							<div class="box box-success">
								<div class="box-header with-border">
									<h2 class="box-title"><b>Upload Data Karyawan</b></h2>
								</div>
								<div class="box-body">
									<form role="form" class="form-upload-assessment" enctype="multipart/form-data">
										<div class="form-group">
											<label for="nik">NIK</label>
											<input type="text" name="nik" id="nik" class="form-control angka" placeholder="NIK" required="required">
										</div>
										<div class="form-group">
											<label for="pass">Password</label>
											<input type="password" name="pass" id="pass" class="form-control" placeholder="Password" required="required">
										</div>
										<div class="form-group">
											<label for="file">File Excel</label>
											<input type="file" class="form-control" name="file_excel" id="file_excel" multiple>
										</div>
									</form>
								</div>
								<div class="box-footer">
									<button type="button" name="action_btn" class="btn btn-success pull-right" style="margin-left:5px" value="submit">Submit</button>
									<button type="button" name="action_btn" class="btn btn-warning pull-right" style="margin-left:5px" value="view">View</button>
									</form>
								</div>
								<div class="box-body hidden" id="data_view">
									<fieldset class="fieldset-info">
										<legend><b>Data Karyawan</b></legend>

										<table class="table table-responsive table-bordered table-striped" id="tbl_karyawan" style="width: 100%">
											<thead>
												<th>Nama</th>
												<th>NIK</th>
												<th>Divisi</th>
												<th>Department</th>
												<th>Plant</th>
											</thead>
											<tbody id="bodytable">
											</tbody>
										</table>

									</fieldset>
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
		<script src="https://maps.googleapis.com/maps/api/js?key={key}" async defer></script>
		<script type="text/javascript">
			$(document).ready(function() {
				var t = $('#tbl_karyawan').DataTable({
					ordering: true,
					'order': [
						[0, 'asc']
					],
					scrollCollapse: true,
					scrollY: false,
					scrollX: true,
					bautoWidth: false,
					"pageLength": 10,
					"bLengthChange": false

				});

				$(document).on("change", "input", function(e) {
					$('#data_view').addClass('hidden');
					t.clear().draw();
				});

				$(document).on("click", "button[name='action_btn']", function(e) {
					let btn = $(this).val();
					if (btn == "view") {
						var empty_form = validate(".form-upload-assessment");
						if (empty_form == 0) {
							var formData1 = new FormData($(".form-upload-assessment")[0]);
							$.ajax({
								url: baseURL + 'assessment/transaksi/get/karyawan_non_sap',
								type: 'POST',
								dataType: 'JSON',
								data: formData1,
								contentType: false,
								cache: false,
								processData: false,
								success: (data) => {
									t.clear().draw();
									if (data.sts == 'OK') {
										if (data.karyawan) {
											$.each(data.karyawan, function(i, v) {
												// var gender = v.gender.toLowerCase() == "l" ? "Laki - laki" : "Wanita";
												var department = v.department != "" ? v.department : "";
												t.row.add([
													v.nama,
													v.nik,
													v.divisi,
													department,
													v.plant,

												]).draw(false);
											});
										}

										$('#data_view').removeClass('hidden');
										myAlert({
											icon: "success",
											html: data.msg,
											reload: false
										});
									} else {
										$('#data_view').addClass('hidden');
										$("input[name='isproses']").val(0);
										myAlert({
											text: data.msg,
											icon: "error",
											html: false,
											reload: false
										});
									}
								},
								error: () => {
									myAlert({
										text: "Server Error",
										icon: "error",
										html: false,
										reload: false
									});
								},
								complete: () => {
									adjustDatatableWidth();
								}
							});
						}
					} else {
						var empty_form = validate(".form-upload-assessment");
						if (empty_form == 0) {
							var formData = new FormData($(".form-upload-assessment")[0]);
							$.ajax({
								url: baseURL + 'assessment/transaksi/save/upload/karyawan_non_sap',
								type: 'POST',
								dataType: 'JSON',
								data: formData,
								contentType: false,
								cache: false,
								processData: false,
								success: (data) => {
									console.log(data);
									if (data.sts == 'OK') {
										myAlert({
											icon: "success",
											html: data.msg,
											reload: baseURL + 'assessment/transaksi/upload'
										});
									} else {
										$("input[name='isproses']").val(0);
										myAlert({
											text: data.msg,
											icon: "error",
											html: false,
											reload: false
										});
									}
								},
								error: () => {
									myAlert({
										text: "Server Error",
										icon: "error",
										html: false,
										reload: false
									});
								},
								complete: () => {
									adjustDatatableWidth();
								}
							});
						}
						e.preventDefault();
						return false;
					}
				});

				$(document).on("keyup", ".angka", function(e) {
					var angka = $(this).val().replace(/[^0-9.^-]*/g, '');
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

				$(document).ajaxStart(function() {
					$("input[name='isproses']").val(1);
					Pace.restart();
					showLoading();
				});

				$(document).ajaxStop(function() {
					$("input[name='isproses']").val(0);
					hideLoading();
				});

				function adjustDatatableWidth() {
					if ($(".dataTables_wrapper").length > 0) $("table.dataTable").DataTable().columns.adjust();
				}

				// Auto datatable width adjust untuk table di dalam tabs
				$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
					if ($('.dataTable', $('.tab-pane.active')).length > 0)
						adjustDatatableWidth();
					if ($('#news-wrapper').length > 0)
						adjustNotification();
				});
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