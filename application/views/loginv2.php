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
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo strtoupper($module) ?> | PT. Kirana Megatara Tbk</title>
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
		<link rel="shortcut icon" type="image/png" href="<?php echo base_url() ?>assets/apps/img/logo-sm.png"/>
		<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/pace/pace.min.css">
		<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/square/aero.css">
		<style type="text/css">
		 	.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover{
		 		background-color: #00a65a;
		 		border-color: #00a65a;
		 	}
		 	.small-box{
		 		margin-bottom: 16px;
		 	}
		 	.logo-lg{
		 		height: 100%;
		 	}
		 	.hidden{
		 		display: none;
		 	}
		 	.text-center{
		 		text-align: center !important;
		 	}
		 	.text-left{
		 		text-align: left;
		 	}
		 	.text-right{
		 		text-align: right;
		 	}
		 	.sidebar-menu li ul li a span{
		 		white-space: normal;
		 	}
		 	.navbar-nav>.user-menu>.dropdown-menu{
		 		border: 1px solid #009551;
    			border-radius: 0;
    			padding:0;
    			margin:2px 0 0 0;
		 	}
		 	.btn-circle{
		 		border-radius: 30px !important;
		 	}
		 	.user-footer .logout{
		 		background-color: #dd4b39 !important;
		 	}
		 	.modal .overlay {
			    position: absolute;
			    top: 0;
			    left: 0;
			    width: 100%;
			    height: 100%;
			}
			table .input-group-addon, .input-group-btn{
				width: auto;
			}
			table{
				width: 100% !important;
			}

			.dataTables_scrollBody .my-datatable-extends,
			.dataTables_scrollBody .my-datatable-order-col2,
			.dataTables_scrollBody .my-datatable-extends-order{
				padding-bottom: 150px !important;
			}
			
			.btn-role{
				margin: 1px;
			}

			/*======*/

			@import url(<?php echo base_url() ?>assets/apps/css/folder/good times rg.ttf);
			
			.wrappers {
			  /*background: #33928c; */ 
			  background: #6ab97f;
			  background: linear-gradient(to bottom right, #6ab97f 15%, #33928c 100%);
			  /*background: linear-gradient(to bottom right, #50a3a2 0%, #53e3a6 100%);*/
			  position: absolute;
			  top: 0;
			  left: 0;
			  width: 100%;
			  height: 100%;
			  margin-top: 0;
			  overflow: hidden;
			}
			.wrappers.form-success .container h1 {
			  -webkit-transform: translateY(85px);
			          transform: translateY(85px);
			}
			.container {
			  max-width: 600px;
			  margin: 150px auto;
			  border: 1px solid rgba(255, 255, 255, 0.4);
			  background: #cce4e224;
			  padding: 60px 0;
			  height: 400px;
			  text-align: center;
			}
			.container h1 {
			  font-family: 'Good Times', arial;
			  font-size: 45px;
			  color: #ffffff;
			  transition-duration: 1s;
			  transition-timing-function: ease-in-put;
			  font-weight: 200;
			}
			form {
			  padding: 20px 0;
			  position: relative;
			  z-index: 2;
			}

			form span {
				/*font-family: 'Good Times', arial;*/
				padding-left: 10px;
				font-size: 16px;
				color:#ffffff;
			}

			form a {
				/*font-family: 'Good Times', arial;*/
				/*font-size: 13px;*/
				color:#ffffff;
			}
			form a:hover {
			  /*font-size: 13px;*/
			  color:#333;
			  /*box-shadow: 0 0 5px 3px #ddd;*/

			}

			form input {
			  -webkit-appearance: none;
			     -moz-appearance: none;
			          appearance: none;
			  outline: 0;
			  border: 1px solid rgba(255, 255, 255, 0.4);
			  background-color: rgba(255, 255, 255, 0.2);
			  width: 250px;
			  border-radius: 3px;
			  padding: 10px 15px;
			  margin: 0 auto 10px auto;
			  display: block;
			  text-align: center;
			  font-size: 18px;
			  color: white;
			  transition-duration: 0.25s;
			  font-weight: 300;
			}

			::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
			    color: white;
			}
			form input:hover {
			  background-color: rgba(255, 255, 255, 0.4);
			}
			form input:focus {
			  background-color: white;
			  width: 300px;
			  color: #333;
			}
			form button {
			  -webkit-appearance: none;
			     -moz-appearance: none;
			          appearance: none;
			  outline: 0;
			  /*background-color: #7dc3c8;*/
			  background-color: #33928c;
			  border: 0;
			  padding: 10px 15px;
			  color: #ffffff;
			  border-radius: 3px;
			  width: 250px;
			  cursor: pointer;
			  font-size: 18px;
			  font-weight: 200;
			  transition-duration: 0.25s;
			  border: 1px solid rgba(255, 255, 255, 0.4);

			}
			form button:hover {
			 /*background: #6ab97f;
			 background: linear-gradient(to bottom right, #6ab97f 15%, #53b2e3 100%);*/
			 background: #33928c;
			 border: 1px solid #7dc3c8;
			}
			.bg-bubbles {
			  position: absolute;
			  top: 0;
			  left: 0;
			  border-radius: 50%;
			  width: 100%;
			  height: 100%;
			  z-index: 1;
			}
			.bg-bubbles li {
			  position: absolute;
			  list-style: none;
			  display: block;
			  width: 40px;
			  height: 40px;
			  /*background-color: rgba(255, 255, 255, 0.15);*/
			  background-color: transparent;
			  border:1px solid #333;
			  bottom: -160px;
			  -webkit-animation: square 25s infinite;
			  animation: square 25s infinite;
			  transition-timing-function: linear;
			}
			.bg-bubbles li:nth-child(1) {
			  left: 10%;
			}
			.bg-bubbles li:nth-child(2) {
			  left: 20%;
			  width: 80px;
			  height: 80px;
			  -webkit-animation-delay: 2s;
			          animation-delay: 2s;
			  -webkit-animation-duration: 17s;
			          animation-duration: 17s;
			}
			.bg-bubbles li:nth-child(3) {
			  left: 25%;
			  -webkit-animation-delay: 4s;
			          animation-delay: 4s;
			}
			.bg-bubbles li:nth-child(4) {
			  left: 40%;
			  width: 60px;
			  height: 60px;
			  -webkit-animation-duration: 22s;
			          animation-duration: 22s;
			  background-color: rgba(255, 255, 255, 0.25);
			}
			.bg-bubbles li:nth-child(5) {
			  left: 70%;
			}
			.bg-bubbles li:nth-child(6) {
			  left: 80%;
			  width: 120px;
			  height: 120px;
			  -webkit-animation-delay: 3s;
			          animation-delay: 3s;
			  background-color: rgba(255, 255, 255, 0.2);
			}
			.bg-bubbles li:nth-child(7) {
			  left: 32%;
			  width: 160px;
			  height: 160px;
			  -webkit-animation-delay: 7s;
			          animation-delay: 7s;
			}
			.bg-bubbles li:nth-child(8) {
			  left: 55%;
			  width: 20px;
			  height: 20px;
			  -webkit-animation-delay: 15s;
			          animation-delay: 15s;
			  -webkit-animation-duration: 40s;
			          animation-duration: 40s;
			}
			.bg-bubbles li:nth-child(9) {
			  left: 25%;
			  width: 10px;
			  height: 10px;
			  -webkit-animation-delay: 2s;
			          animation-delay: 2s;
			  -webkit-animation-duration: 40s;
			          animation-duration: 40s;
			  background-color: rgba(255, 255, 255, 0.3);
			}
			.bg-bubbles li:nth-child(10) {
			  left: 90%;
			  width: 160px;
			  height: 160px;
			  -webkit-animation-delay: 11s;
			          animation-delay: 11s;
			}
			@-webkit-keyframes square {
			  0% {
			  	 border-radius:50% 50% 50% 50%;
			    -webkit-transform: translateY(0);
			            transform: translateY(0);
			  }
			  100% {
			  	 border-radius:50% 50% 50% 50%;
			    -webkit-transform: translateY(-900px) rotate(600deg);
			            transform: translateY(-900px) rotate(600deg);
			  }
			}
			@keyframes square {
			  0% {
			  	 border-radius:50% 50% 50% 50%;
			  	
			    -webkit-transform: translateY(0);
			            transform: translateY(0);
			  }
			  100% {
			  	 border-radius:50% 50% 50% 50%;

			    -webkit-transform: translateY(-900px) rotate(600deg);
			            transform: translateY(-900px) rotate(600deg);
			  }
			}


			/*======*/
		</style>
		<script type="text/javascript">
		 	var baseURL = "<?php echo base_url(); ?>";
		</script>
	</head>
	<body class="skin-green-light sidebar-mini sidebar-collapse fixed">
		<div class="wrapper overlay-wrapper">
			<div class="content-wrapper">
			<!-- <div class="content-wrapper" style="background: url(<?php echo base_url().'assets/apps/img/blur-background09.jpg'; ?>); background-size: cover; margin-left: 0 !important;"> -->
				<section class="content">
					<!-- <div class="login-box">
					  	<div class="login-logo" style="background-color: #00a65a; margin-bottom: 0px;">
					   		<a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>/assets/apps/img/kiranaku.png" style="max-width: 75%"/></a>
					  	</div>
						<div class="login-box-body">
							<p class="login-box-msg">Sign in to start your session</p>
							<form class="form-login-user">
						    	<div class="form-group has-feedback">
						        	<input type="number" name="username" class="form-control" placeholder="NIK" required="required">
						        	<span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
						      	</div>
						      	<div class="form-group has-feedback">
						        	<input type="password" name="password" class="form-control" placeholder="Password" required="required">
						        	<span class="glyphicon glyphicon-lock form-control-feedback"></span>
						      	</div>
						      	<div class="row">
						        	<div class="col-xs-8">
						          		<div class="checkbox icheck">
							            	<label>
							              		<input type="checkbox" name="remember"> Remember Me
							            	</label>
						          		</div>
						       		</div>
						        	<!-- /.col -->
							        <!-- <div class="col-xs-4">
							          	<button type="button" class="btn btn-primary btn-block btn-flat login-btn">Sign In</button>
							        </div>
						        	<!-- /.col -->
						      	<!-- </div>
						    </form>
						    <a href="#" id="forgot-pass-btn">I forgot my password</a><br>
						</div>
					</div> -->

					<!-- ================================================== -->
					<div class="wrappers">
						<div class="container">
							<h1>KIRANAKU</h1>
							<!-- <img src="<?php echo base_url(); ?>/assets/apps/img/km.gif"> -->
							
							<form class="form-login-user">
								<div class="col-md-12">
									<input type="number" name="username" placeholder="NIK" required="required">
								</div>
								<div class="col-md-12">
									<input type="password" name="password" placeholder="Password" required="required">
								</div>
								<div class="col-md-12" style="padding:3px 0px 12px 175px; text-align:left; color:#ffffff; font-weight: 200;">
									<input type="checkbox" name="remember" style="vertical-align: middle;"><span>Remember Me</span>
								</div>
								<div class="col-md-12">
								<button type="button" class="login-btn">Login</button>
								</div>
								<div class="col-md-12" style="padding-top: 6px;">
									<a href="#" id="forgot-pass-btn">Forgot Password</a>
								</div>
							</form>

							<form class="form-forgot-pass hidden">
								<div class="col-md-12" style="padding-bottom: 12px; font-size: 18px; color: #ffffff;">
									<span>Forgot Password</span>
								</div>
								<div class="col-md-12">
									<input type="number" name="nik" placeholder="NIK" required="required">
								</div>
								<div class="col-md-12">
									<input type="text" name="email" placeholder="Email" required="required">
								</div>
								<div class="col-md-12">
								<button type="button" id="action-btn">Submit</button>
								</div>
								<div class="col-md-12" style="padding-top: 6px;">
									<a href="#" id="sign-in-btn">Back to Sign In Form</a>
								</div>
							</form>


						</div>
						
						<ul class="bg-bubbles">
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
							<li></li>
						</ul>
					</div>
					<!-- ================================================== -->

					<!-- Modal -->
					<!-- <div class="modal fade" id="forgot-pass-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="forgot-pass-Label">
						<div class="modal-dialog modal-md" role="document">
					    	<div class="modal-content">
					    		<form class="form-forgot-pass">
						    		<div class="modal-header">
						        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        		<h4 class="modal-title" id="forgot-pass-Label">Forgot Password</h4>
						      		</div>
						      		<div class="modal-body">
							      		<div class="row">
								      		<div class="col-sm-12">
							      				<div class="form-group">
							                		<label for="plant">NIK</label>
							                		<input type="text" class="form-control" name="nik" required="required" placeholder="Masukkan NIK">
							                	</div>
								      		</div>
							      		</div>
							      		<div class="row">
								      		<div class="col-sm-12">
							      				<div class="form-group">
							                		<label for="plant">Email</label>
							                		<input type="text" class="form-control" name="email" required="required" placeholder="Masukkan alamat email">
							                	</div>
								      		</div>
							      		</div>
						      		</div>
						      		<div class="modal-footer">
						        		<button type="button" class="btn btn-flat btn-primary" id="action-btn">Submit</button>
						      		</div>
							    </form>
					    	</div>
					  	</div>
					</div> -->


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
		<script src="<?php echo base_url() ?>assets/plugins/pace/pace.min.js"></script>
		<script src="<?php echo base_url() ?>assets/apps/js/general.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
		<script>
		  $(function () {
		    $('input').iCheck({
		      checkboxClass: 'icheckbox_square-aero',
		      radioClass: 'iradio_square-aero',
		      increaseArea: '20%' // optional
		    });
		  });

		  $(document).ready(function(){
			$(document).on("click", ".login-btn", function(e){
				var empty_form = validate($(".form-login-user"));
		        if(empty_form == 0){
                    login();
			    }
				e.preventDefault();
				return false;
			});

			$(document).on("keyup", "input[name='username'] , input[name='password'] , input[name='remember']", function(e){
				if(e.which == 13){
					var empty_form = validate($(".form-login-user"));
			        if(empty_form == 0){
                        login();
				    }
				}
				e.preventDefault();
				return false;
			});

			$(document).on("click", "#forgot-pass-btn", function(e){
				$('.form-forgot-pass').removeClass('hidden');
				$('.form-login-user').addClass('hidden');
			});

			$(document).on("click", "#sign-in-btn", function(e){
				$('.form-forgot-pass').addClass('hidden');
				$('.form-login-user').removeClass('hidden');
			});

			  $(document).on("click", "#action-btn", function(e){
				  var empty_form 	= validate($(".form-forgot-pass"));
				  if(empty_form == 0){
					  var email 	= $("input[name='email']").val().toLowerCase();

					  if(email.indexOf("kiranamegatara.com") > 0 && email.replace("kiranamegatara.com", "").length > 0){
						  var formData    = new FormData($(".form-forgot-pass")[0]);

						  $.ajax({
							  url: baseURL+'home/forgotpass',
							  type: 'POST',
							  dataType: 'JSON',
							  data: formData,
							  contentType: false,
							  cache: false,
							  processData: false,
							  success: function(data){
								  if(data.sts == 'OK'){
									  alert(data.msg);
									  location.href = baseURL;
								  }else{
									  alert(data.msg);
								  }
							  }
						  });
					  }else{
						  alert("Periksa kembali alamat email yang dimasukkan");
					  }
				  }
				  e.preventDefault();
				  return false;
			  });
		  })
		</script>
		<style type="text/css">
			.select2{
				width: 100% !important;
			}
		</style>
		<style>
			.sidebar-mini.sidebar-collapse .content-wrapper, .sidebar-mini.sidebar-collapse .right-side, .sidebar-mini.sidebar-collapse .main-footer{
				margin-left: 0 !important;
			}
			.main-header .navbar{
				margin-left: 0 !important;
			}
			.small-box .icon{
			    top: -13px;
			}
		</style>
	</footer>
</html>	
