<!--
/*
@application    : Kiranaku v2
@author 		: Akhmad Syaiful Yamang (8347)
@contributor	: 
			1. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>			   
			2. <insert your fullname> (<insert your nik>) <insert the date>
			   <insert what you have modified>
			etc.
*/
-->

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible"
		  content="IE=edge">
	<title><?php echo strtoupper($module) ?> | PT. Kirana Megatara Tbk</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"
		  name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/fontawesome/css/font-awesome.min.css">
	<!-- DataTables -->
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/plugins/datatables/dataTables.bootstrap.css">
	<!-- Select2 -->
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/plugins/select2/select2.min.css">
	<!-- Theme style -->
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/dist/css/skins/_all-skins.min.css">
	<link rel="shortcut icon"
		  type="image/png"
		  href="<?php echo base_url() ?>assets/apps/img/logo-sm.png" />
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/plugins/pace/pace.min.css">
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert2.min.css" />
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css">
	<style type="text/css">
		.pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
			background-color: #00a65a;
			border-color: #00a65a;
		}

		.small-box {
			margin-bottom: 16px;
		}

		.logo-lg {
			height: 100%;
		}

		.hidden {
			display: none;
		}

		.text-center {
			text-align: center !important;
		}

		.text-left {
			text-align: left;
		}

		.text-right {
			text-align: right;
		}

		.sidebar-menu li ul li a span {
			white-space: normal;
		}

		.navbar-nav > .user-menu > .dropdown-menu {
			border: 1px solid #009551;
			border-radius: 0;
			padding: 0;
			margin: 2px 0 0 0;
		}

		.btn-circle {
			border-radius: 30px !important;
		}

		.user-footer .logout {
			background-color: #dd4b39 !important;
		}

		.modal .overlay {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
		}

		table .input-group-addon, .input-group-btn {
			width: auto;
		}

		table {
			width: 100% !important;
		}

		.dataTables_scrollBody .my-datatable-extends,
		.dataTables_scrollBody .my-datatable-order-col2,
		.dataTables_scrollBody .my-datatable-extends-order {
			padding-bottom: 150px !important;
		}

		.btn-role {
			margin: 1px;
		}
	</style>
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
</head>
<body class="skin-green-light sidebar-mini sidebar-collapse fixed">
<div class="wrapper overlay-wrapper">
	<div class="content-wrapper"
		 style="background: url(<?php echo base_url() . 'assets/apps/img/blur-background09.jpg'; ?>); background-size: cover; margin-left: 0 !important;">
		<section class="content">
			<div class="login-box">
				<div class="login-logo"
					 style="background-color: #00a65a; margin-bottom: 0px;">
					<a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>/assets/apps/img/kiranaku.png"
															 style="max-width: 75%" /></a>
				</div>
				<div class="login-box-body">
					<p class="login-box-msg">Sign in to start your session</p>
					<form class="form-login-user">
						<div class="form-group has-feedback">
							<input type="number"
								   name="username"
								   class="form-control"
								   placeholder="NIK"
								   required="required">
							<span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
						</div>
						<div class="form-group has-feedback">
							<input type="password"
								   name="password"
								   class="form-control"
								   placeholder="Password"
								   required="required">
							<span class="glyphicon glyphicon-lock form-control-feedback"></span>
						</div>
						<div class="row">
							<div class="col-xs-8">
								<div class="checkbox icheck">
									<label>
										<input type="checkbox"
											   name="remember"> Remember Me
									</label>
								</div>
							</div>
							<!-- /.col -->
							<div class="col-xs-4">
								<button type="button"
										class="btn btn-primary btn-block btn-flat login-btn">Sign In
								</button>
							</div>
							<!-- /.col -->
						</div>
					</form>
					<a href="#"
					   id="forgot-pass-btn">I forgot my password</a><br>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade"
				 id="forgot-pass-modal"
				 data-backdrop="static"
				 tabindex="-1"
				 role="dialog"
				 aria-labelledby="forgot-pass-Label">
				<div class="modal-dialog modal-md"
					 role="document">
					<div class="modal-content">
						<form class="form-forgot-pass">
							<div class="modal-header">
								<button type="button"
										class="close"
										data-dismiss="modal"
										aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
								<h4 class="modal-title"
									id="forgot-pass-Label">Forgot Password</h4>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label for="plant">NIK</label>
											<input type="text"
												   class="form-control"
												   name="nik"
												   required="required"
												   placeholder="Masukkan NIK">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label for="plant">Email</label>
											<input type="text"
												   class="form-control"
												   name="email"
												   required="required"
												   placeholder="Masukkan alamat email">
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button"
										class="btn btn-flat btn-primary"
										id="action-btn">Submit
								</button>
							</div>
						</form>
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
	<!-- AdminLTE App -->
	<script src="<?php echo base_url() ?>assets/dist/js/app.js"></script>
	<!-- SweetAlert -->
	<script src="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert2.min.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/pace/pace.min.js"></script>
	<script src="<?php echo base_url() ?>assets/apps/js/general.js"></script>
	<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
	<script>
		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green',
				increaseArea: '20%' // optional
			});
		});

		$(document).ready(function () {
			$(document).on("click", ".login-btn", function (e) {
				var empty_form = validate($(".form-login-user"));
				if (empty_form == 0) {
					login();
				}
				e.preventDefault();
				return false;
			});

			$(document).on("keyup", "input[name='username'] , input[name='password'] , input[name='remember']", function (e) {
				if (e.which == 13) {
					var empty_form = validate($(".form-login-user"));
					if (empty_form == 0) {
						login();
					}
				}
				e.preventDefault();
				return false;
			});

			$(document).on("click", "#forgot-pass-btn", function (e) {
				$('#forgot-pass-modal').modal('show');
			});

			$(document).on("click", "#action-btn", function (e) {
				var empty_form = validate($(".form-forgot-pass"));
				if (empty_form == 0) {
					var email = $("input[name='email']").val().toLowerCase();

					if (email.indexOf("kiranamegatara.com") > 0 && email.replace("kiranamegatara.com", "").length > 0) {
						var formData = new FormData($(".form-forgot-pass")[0]);

						$.ajax({
							url: baseURL + 'home/forgotpass',
							type: 'POST',
							dataType: 'JSON',
							data: formData,
							contentType: false,
							cache: false,
							processData: false,
							success: function (data) {
								if (data.sts == 'OK') {
									kiranaAlert(data.sts, data.msg, "success", baseURL);
								} else {
									kiranaAlert(data.sts, data.msg, "error", "no");
								}
							}
						});
					} else {
						kiranaAlert("notOK", "Periksa kembali alamat email yang dimasukkan", "error", "no");
					}
				}
				e.preventDefault();
				return false;
			});
		})
	</script>
	<style type="text/css">
		.select2 {
			width: 100% !important;
		}

		.swal2-container {
			zoom: 1.5;
		}
	</style>
	<style>
		.sidebar-mini.sidebar-collapse .content-wrapper, .sidebar-mini.sidebar-collapse .right-side, .sidebar-mini.sidebar-collapse .main-footer {
			margin-left: 0 !important;
		}

		.main-header .navbar {
			margin-left: 0 !important;
		}

		.small-box .icon {
			top: -13px;
		}
	</style>
</footer>
</html>	
