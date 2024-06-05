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
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/kirana.css">
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
</head>

<body class="skin-green-light sidebar-mini sidebar-collapse fixed">
	<div class="wrapper overlay-wrapper">
		<div class="content-wrapper" style="background-size: cover; margin-left: 0 !important; padding-top: 0px;">
			<section class="content">
				<div class="row">
					<div class="col-sm-12">
						<input type="hidden" name="isproses" value="0" />
						<div class="text-center" style="margin-bottom:20px">
							<img src="<?php echo base_url() . "assets/apps/img/Logo_KM_horizontal.png" ?>" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 mycontainer">
						<div class="callout callout-success">
							<h4>Satu Warna, Satu Kirana!</h4>
							<p>
								Salam hangat & semangat buat Keluarga besar Kirana Megatara Group,
								Di tengah merebaknya virus Covid-19 (Virus Corona), maka management perlu mengetahui lebih lanjut kondisi dari setiap karyawan Kirana Megatara Group yang memiliki keluhan ataupun masalah pada kesehatan pada hari ini atau dalam 1 minggu terakhir ini.
								<br>
								Langkah ini dilakukan sebagai wujud kepedulian serta tindakan pencegahan dan solusi agar karyawan Kirana Megatara Group selalu dalam keadaan sehat dan tetap dapat beraktifitas seperti biasa.
							</p>
							Salam,<br>Management Kirana Megatara
						</div>
						<div class="box box-success">
							<form class="form-login-user">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-user"></i> Form Identitas</h3>
								</div>
								<div class="box-body">
									<div class="alert alert-warning">
										<h4><i class="icon fa fa-warning"></i> Perhatian!</h4>
										Pengisian form dibatasi hanya 1x per hari
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" name="tanggal" value="<?php echo date('d.m.Y'); ?>" class="form-control" placeholder="Tanggal" required="required" disabled>
										</div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<input type="text" name="nik" class="form-control angka" placeholder="NIK" required="required">
										</div>
									</div>
								</div>
								<div class="box-footer">
									<button type="button" name="action_btn" class="btn btn-success btn-block btn-flat login-btn">Mulai Assessment</button>
								</div>
							</form>
						</div>
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-info-circle"></i> Petunjuk Pengisian</h3>
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
								</div>
							</div>
							<div class="box-body">
								<a href="<?php echo base_url() . "assessment/transaksi/unduh" ?>" class="btn btn-info btn-flat" target="_self"><i class="fa fa-download"></i> Unduh Petunjuk Pengisian</a>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</body>
<footer>
	<script src="<?php echo base_url() ?>assets/plugins/jQuery/jquery-3.3.1.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="<?php echo base_url() ?>assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script src="<?php echo base_url() ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<!-- jQuery Validation -->
	<script src="<?php echo base_url() ?>assets/plugins/jquery-validation/jquery.validate.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/jquery-validation/additional-methods.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/jquery-validation/localization/messages_id.min.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url() ?>assets/dist/js/app.js"></script>
	<!-- SweetAlert -->
	<script src="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert2.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/pace/pace.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
	<script>
		$(document).ready(function() {
			$(document).on("keyup", ".angka", function(e) {
				var angka = $(this).val().replace(/[^0-9.^-]*/g, ''); 
				$(this).val(angka);
				e.preventDefault();
				return false;
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

			$(document).on("click", ".login-btn", function(e) {
				var empty_form = validate(".form-login-user");
				if (empty_form == 0) {
					login();
				}
				e.preventDefault();
				return false;
			});
		})

		const login = () => {
			let formData = new FormData($(".form-login-user")[0]);
			let remember_me = $("input[name='remember']").prop("checked");
			let username = $("input[name='username']").val();
			let searchParams = new URLSearchParams(window.location.search);
			let param = null;
			if (searchParams.has('ref')) {
				param = searchParams.get('ref');
				formData.append('ref', param);
			}
			// if (navigator.geolocation) {
				// navigator.geolocation.getCurrentPosition((position) => {
						// if (position.coords.latitude == null) {
							// myAlert({
								// text: "Please turn on your Location Service",
								// icon: "error",
								// html: false,
								// reload: false
							// });
							// return;
						// }
					// },
					// (error) => {
						// let msg = "Error";
						// switch (error.code) {
							// case error.PERMISSION_DENIED:
								// msg = "User denied the request for Geolocation. Please turn on your Location Service."
								// break;
							// case error.POSITION_UNAVAILABLE:
								// msg = "Location information is unavailable."
								// break;
							// case error.TIMEOUT:
								// msg = "The request to get user location timed out."
								// break;
							// case error.UNKNOWN_ERROR:
								// msg = "An unknown error occurred."
								// break;
						// }

						// myAlert({
							// text: msg,
							// icon: "error",
							// html: false,
							// reload: false
						// });
						// return;
					// });

				// return;
			// } else {
				// myAlert({
					// text: "Geolocation is not supported by this browser.",
					// icon: "error",
					// html: false,
					// reload: false
				// });
				// return;
			// }

			$.ajax({
				url: baseURL + 'assessment/transaksi/checking',
				type: 'POST',
				dataType: 'JSON',
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: (data) => {
					if (data.sts == 'OK') {
						if (data.link == null) {
							location.href = baseURL + "assessment/transaksi/input";
						} else {
							location.href = baseURL + data.link;
						}
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
				}
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
	<style>
		.select2 {
			width: 100% !important;
		}

		.swal2-container {
			zoom: 1.5;
		}

		@media screen and (min-width: 800px) {
			.mycontainer {
				padding: 0 25% !important;
			}
		}
	</style>
</footer>

</html>