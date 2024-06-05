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
		  href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css" />
	<link rel="stylesheet"
		  href="<?php echo base_url() ?>assets/apps/css/kirana.css">
	<script type="text/javascript">
		var baseURL = "<?php echo base_url(); ?>";
	</script>
</head>
<body class="hold-transition skin-green sidebar-mini sidebar-collapse fixed">
<div class="wrapper overlay-wrapper">
	<header class="main-header">
		<!-- Logo -->
		<?php if (isset($user)) { ?>
			<div class="logo">
				<span class="logo-mini">
					<a href="<?php echo base_url(); ?>">
						<img src="<?php echo base_url(); ?>/assets/apps/img/logo-sm.png">
					</a>
				</span>
				<span class="logo-lg">
					<a href="<?php echo base_url(); ?>">
						<img src="<?php echo base_url(); ?>/assets/apps/img/logo-lg.png">
					</a>
				</span>
			</div>
		<?php } ?>
		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-static-top">
			<?php if (isset($user)) { ?>
				<a href="#"
				   class="sidebar-toggle"
				   data-toggle="offcanvas"
				   role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<div class="navbar-custom-menu">
					<input type="hidden"
						   name="isproses"
						   value="0">
					<ul class="nav navbar-nav">
						<!-- User Account: style can be found in dropdown.less -->
						<li class="dropdown user user-menu">
							<a href="#"
							   class="dropdown-toggle"
							   data-toggle="dropdown">
								<?php
									if ($user->gender == "l") {
										$image = base_url() . "assets/apps/img/avatar5.png";
									} else {
										$image = base_url() . "assets/apps/img/avatar2.png";
									}


									if ($user->gambar) {
										$data_image = "http://kiranaku.kiranamegatara.com/home/" . strtolower($user->gambar);
										$headers    = get_headers($data_image);
										if ($headers[0] == "HTTP/1.1 200 OK") {
											$image = $data_image;
										} else {
											$links      = explode("/", $user->gambar);
											$data_image = "http://kiranaku.kiranamegatara.com/home/" . $links[0] . "/" . $links[1] . "/" . strtoupper($links[2]);
											$headers    = get_headers($data_image);
											if ($headers[0] == "HTTP/1.1 200 OK") {
												$image = $data_image;
											}
										}
									}
								?>
								<img src="<?php echo $image; ?>"
									 class="user-image"
									 alt="User Image">
								<span class="hidden-xs"><?php echo $user->nama ?></span>
							</a>
							<ul class="dropdown-menu">
								<!-- User image -->
								<li class="user-header">
									<img src="<?php echo $image; ?>"
										 class="img-circle"
										 alt="User Image">
									<!-- <img src="<?php echo "http://kiranaku.kiranamegatara.com/home/" . $user->gambar; ?>" class="img-circle" alt="User Image"> -->
									<p>
										<?php echo $user->nama; ?>
										<small><?php echo $user->posst; ?></small>
										<small><?php echo $user->nik; ?></small>
									</p>
								</li>

								<li class="user-footer">
									<div class="pull-left">
										<a href="<?php echo base_url() . 'settings/users' ?>"
										   class="btn btn-default btn-flat btn-circle"
										   title="Setting User"><i class="fa fa-gear"></i></a>
									</div>
									<div class="pull-right">
										<a href="#"
										   class="btn btn-danger btn-flat btn-circle logout"
										   title="Sign Out"><i class="fa fa-power-off"></i></a>
									</div>
								</li>
							</ul>
						</li>
						<!--                        <li>-->
						<!--                            <a href="#"-->
						<!--                               data-toggle="control-sidebar"><i class="fa fa-comments-o"></i></a>-->
						<!--                        </li>-->
					</ul>
				</div>
			<?php } ?>
		</nav>
	</header>
	<!-- Left side column. contains the logo and sidebar -->
	<?php if (isset($user)) { ?>
		<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
				<!-- sidebar menu: : style can be found in sidebar.less -->
				<ul class="sidebar-menu">
				</ul>
			</section>
			<!-- /.sidebar -->
		</aside>


		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<h3 class="text-center"
				style="background-color: rgba(0, 166, 90, 0.43);margin: 0;padding: 10px;">Obral Obrol Kiranaku</h3>
			<!-- Create the tabs -->
			<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
				<li class="active"><a href="#control-sidebar-chat-tab"
									  data-toggle="tab"><i class="fa fa-comments-o"></i> Chats</a></li>
				<li><a href="#control-sidebar-contacts-tab"
					   data-toggle="tab"><i class="fa fa-users"></i> Contacts</a></li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
				<!-- Chats tab content -->
				<div class="tab-pane active"
					 id="control-sidebar-chat-tab">
					<h6 style="margin: 0; padding: 10px;">Recent Chat</h6>
				</div>
				<!-- Home tab content -->
				<div class="tab-pane"
					 id="control-sidebar-contacts-tab">
					<h6 style="margin: 0; padding: 10px;">User Kiranaku</h6>
				</div>
			</div>
		</aside>
		<div class="control-sidebar-bg"></div>
	<?php } ?>
