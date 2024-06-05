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
          href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css">
    <style type="text/css">
        .pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
            background-color: #00a65a;
            border-color: #00a65a;
        }

        .login-box{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            margin:0 !important;
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
        .blur {
            background: rgba(255, 255, 255, 0.35); 
            /* backdrop-filter: blur(2px);  */
            min-height: 625px;
            height:100vh;
        }
    </style>
    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>
</head>
<body class="skin-green-light sidebar-mini sidebar-collapse fixed">
<div class="wrapper overlay-wrapper">
<div class="content-wrapper"
				style="background: url(<?php echo base_url() . 'assets/apps/img/km.jpg'; ?>); padding-top:0px !important; background-size: cover; margin-left: 0 !important;">
        <div class='blur'>
        <section class="content">
            <div class="login-box">
                <div class="login-logo"
                    style="background-color: #00a65ae6; margin-bottom: 0px;border-top-right-radius: 20px;border-top-left-radius: 20px;">
                    <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>/assets/apps/img/logo-lg.png"
                                                            style="margin-bottom: 10px" /></a>
                </div>
                <div class="login-box-body" style="background-color:#ffffff80;border-bottom-right-radius: 20px;border-bottom-left-radius: 20px;">
                    <p class="login-box-msg" style="font-size: initial;">Reset Password</p>
                    <form class="form-reset-user">
                        <div class="form-group has-feedback">
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Password"
                                   required="required">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password"
                                   name="konf_password"
                                   class="form-control"
                                   placeholder="Konfirmasi Password"
                                   required="required">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <button type="button"
                                        class="btn btn-primary btn-block btn-flat"
                                        id="action-btn">Submit
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- <section class="content text-center" style="min-height:0px;">
					<h4 style="color: black;">Presented by</h4>
					<img src="<?php echo base_url(); ?>/assets/apps/img/Logo_KM_horizontal.png"
																	style="margin-bottom: 10px" />
				</section> -->
        </div>
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
    <script src="<?php echo base_url() ?>assets/plugins/pace/pace.min.js"></script>
    <script src="<?php echo base_url() ?>assets/apps/js/general.js"></script>
    <!-- SweetAlert -->
    <script src="<?php echo base_url() ?>assets/plugins/sweetalert/sweetalert.min.js"></script>
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
            $(document).on("click", "#action-btn", function (e) {
                var empty_form = validate();
                if (empty_form == 0) {
                    var pass = $("input[name='password']").val();
                    var konf_password = $("input[name='konf_password']").val();
                    if (pass === konf_password) {
                        var formData = new FormData($(".form-reset-user")[0]);
                        $.ajax({
                            url: baseURL + 'home/set/password',
                            type: 'POST',
                            dataType: 'JSON',
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.sts == 'OK') {
                                    kiranaAlert(data.sts, data.msg, null, baseURL+'home');
                                } else {
                                    kiranaAlert("notOK", "Password tidak sama", "warning", "no");
                                }
                            }
                        });

                    } else {
                        kiranaAlert("notOK", "Password tidak sama", "warning", "no");
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