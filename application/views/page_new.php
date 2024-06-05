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

<?php $this->load->view('header') ?>
<!-- Bootstrap treefy -->
<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/plugins/treetable/css/bootstrap-treefy.css">

<div class="content-wrapper">
	<section class="content">
		<div class="slide-notif">
		</div>			
		<div class="slide-notif-box">
		</div>			
		<div class="row">
			<div class="col-sm-8">
				<div class="box box-success slideshow-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Kirana DNA</strong></h3>
						<div class="box-tools pull-right">
							<button type="button"
									class="btn btn-box-tool"
									data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div id="carousel-kirana-dna"
							 class="carousel slide"
							 data-ride="carousel">
							<ol class="carousel-indicators">
								<li data-target="#carousel-kirana-dna"
									data-slide-to="0"
									class="active"></li>
								<li data-target="#carousel-kirana-dna"
									data-slide-to="1"
									class=""></li>
								<li data-target="#carousel-kirana-dna"
									data-slide-to="2"
									class=""></li>
								<li data-target="#carousel-kirana-dna"
									data-slide-to="3"
									class=""></li>
							</ol>
							<div class="carousel-inner">
								<div class="item active">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA.png"; ?>"
										 alt="First slide">
								</div>
								<div class="item">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA1.png"; ?>"
										 alt="Second slide">
								</div>
								<div class="item">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA2.png"; ?>"
										 alt="Third slide">
								</div>
								<div class="item">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA3.png"; ?>"
										 alt="Fourth slide">
								</div>
								<div class="item">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA4.png"; ?>"
										 alt="Fifth slide">
								</div>
							</div>
							<a class="left carousel-control"
							   href="#carousel-kirana-dna"
							   data-slide="prev">
								<span class="fa fa-angle-left"></span>
							</a>
							<a class="right carousel-control"
							   href="#carousel-kirana-dna"
							   data-slide="next">
								<span class="fa fa-angle-right"></span>
							</a>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="col-sm-4">
				<div class="box box-success notif-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Notifikasi Kiranaku</strong></h3>
						<div class="box-tools pull-right">
							<button type="button"
									class="btn btn-box-tool"
									data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body notif-body">
						<ul class="products-list product-list-in-box"
							style="overflow-y: hidden;">
							<li class="item no-notification">
								<div class="well text-center">No notification</div>
							</li>
						</ul>
					</div>
				</div>
			</div> -->
            <div class="col-md-4">
              	<div class="box box-danger ultah-wrapper">
	                <div class="box-header with-border">
	                  	<h3 class="box-title">Karyawan Ulang Tahun</h3>
	                  	<div class="box-tools pull-right">
	                    	<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                    	</button>
	                  	</div>
	                </div>
	                <div class="box-body no-padding notif-body">
		                <ul class="users-list clearfix">
		                    <li>
			                    <img src="<?php echo base_url() . "assets/dist/img/user1-128x128.jpg" ; ?>" alt="User Image">
			                    <a class="users-list-name" href="#">Alexander Pierce</a>
			                    <span class="users-list-date">Today</span>
		                    </li>
		                    <li>
			                    <img src="<?php echo base_url() . "assets/dist/img/user8-128x128.jpg" ; ?>" alt="User Image">
			                    <a class="users-list-name" href="#">Norman</a>
			                    <span class="users-list-date">Yesterday</span>
		                    </li>
		                    <li>
			                    <img src="<?php echo base_url() . "assets/dist/img/user7-128x128.jpg" ; ?>" alt="User Image">
			                    <a class="users-list-name" href="#">Jane</a>
			                    <span class="users-list-date">12 Jan</span>
		                    </li>
		                    <li>
			                    <img src="<?php echo base_url() . "assets/dist/img/user6-128x128.jpg" ; ?>" alt="User Image">
			                    <a class="users-list-name" href="#">John</a>
			                    <span class="users-list-date">12 Jan</span>
		                    </li>
		                    <li>
								<img src="<?php echo base_url() . "assets/dist/img/user2-160x160.jpg" ; ?>" alt="User Image">
								<a class="users-list-name" href="#">Alexander</a>
								<span class="users-list-date">13 Jan</span>
		                    </li>
		                    <li>
		                      	<img src="<?php echo base_url() . "assets/dist/img/user5-128x128.jpg" ; ?>" alt="User Image">
		                      	<a class="users-list-name" href="#">Sarah</a>
		                      	<span class="users-list-date">14 Jan</span>
		                    </li>
		                    <li>
		                      	<img src="<?php echo base_url() . "assets/dist/img/user4-128x128.jpg" ; ?>" alt="User Image">
		                      	<a class="users-list-name" href="#">Nora</a>
		                      	<span class="users-list-date">15 Jan</span>
		                    </li>
		                    <li>
			                    <img src="<?php echo base_url() . "assets/dist/img/user3-128x128.jpg" ; ?>" alt="User Image">
			                    <a class="users-list-name" href="#">Nadia</a>
			                    <span class="users-list-date">15 Jan</span>
		                    </li>
		                </ul>
	                </div>
	                <!-- <div class="box-footer text-center">
	                  <a href="javascript:void(0)" class="uppercase">View All Users</a>
	                </div> -->
	            </div>
            </div> 
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success slideshow-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Kirana Event Update</strong></h3>
						<div class="box-tools pull-right">
							<button type="button"
									class="btn btn-box-tool"
									data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div id="carousel-kirana-dna" class="carousel slide" data-ride="carousel">
							<ol class="carousel-indicators">
								<li data-target="#carousel-kirana-dna"
									data-slide-to="0"
									class="active"></li>
								<li data-target="#carousel-kirana-dna"
									data-slide-to="1"
									class=""></li>
								<li data-target="#carousel-kirana-dna"
									data-slide-to="2"
									class=""></li>
								<li data-target="#carousel-kirana-dna"
									data-slide-to="3"
									class=""></li>
							</ol>
							<div class="carousel-inner">
								<div class="item active center-block">
									<!-- <img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA.png"; ?>"
										 alt="First slide"> -->
									<p> tes page </p>
								</div>
								<div class="item center-block">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA1.png"; ?>"
										 alt="Second slide">
								</div>
								<div class="item">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA2.png"; ?>"
										 alt="Third slide">
								</div>
								<div class="item">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA3.png"; ?>"
										 alt="Fourth slide">
								</div>
								<div class="item">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA4.png"; ?>"
										 alt="Fifth slide">
								</div>
							</div>
							<a class="left carousel-control"
							   href="#carousel-kirana-dna"
							   data-slide="prev">
								<span class="fa fa-angle-left"></span>
							</a>
							<a class="right carousel-control"
							   href="#carousel-kirana-dna"
							   data-slide="next">
								<span class="fa fa-angle-right"></span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-4">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Galeri Kiranaku</strong></h3>
						<div class="box-tools pull-right">
							<button type="button"
									class="btn btn-box-tool"
									data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body"
						 id="gallery-wrapper">
						<div class="gallery-container"
							 style="overflow: hidden; padding-bottom: 10px">
							<div class="row">

							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Berita Terbaru Kirana</strong></h3>
						<div class="box-tools pull-right">
							<button type="button"
									class="btn btn-box-tool"
									data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body"
						 id="news-wrapper">
						<ul class="products-list product-list-in-box">

						</ul>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Pengumuman Pegawai Baru</strong></h3>
						<div class="box-tools pull-right">
							<button type="button"
									class="btn btn-box-tool"
									data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body"
						 id="newrole-wrapper">
						<div class="newrole-container"
							 style="overflow: hidden; padding-bottom: 10px">
							<ul class="products-list product-list-in-box">
								<?php
									if ($newrole) {
										foreach ($newrole as $dt) {
											if ($dt->gender == "l") {
												$image = base_url() . "assets/apps/img/avatar5.png";
											} else {
												$image = base_url() . "assets/apps/img/avatar2.png";
											}


											if ($dt->gambar) {
												$data_image = base_url() . "/assets/apps/" . strtolower($dt->gambar);
												$headers    = get_headers($data_image);
												if ($headers[0] == "HTTP/1.1 200 OK") {
													$image = $data_image;
												} else {
													$links      = explode("/", $dt->gambar);
													$data_image = base_url() . "/assets/apps/" . $links[0] . "/" . $links[1] . "/" . strtoupper($links[2]);
													$headers    = get_headers($data_image);
													if ($headers[0] == "HTTP/1.1 200 OK") {
														$image = $data_image;
													}
												}
											}

											echo '<li class="item">';
											echo ' <div class="product-img">';
											echo '     <img src="' . $image . '" style="border-radius:50%" alt="Product Image">';
											echo ' </div>';
											echo ' <div class="product-info">';
											echo '     <div class="product-title">' . ucwords(strtolower($dt->nama)) . '</div>';
											echo '     <span class="product-description">';
											echo '         <i class="fa fa-user"></i> ' . ucwords(strtolower($dt->posst));
											echo '     </span>';
											echo ' </div>';
											echo '</li>';
										}
									}
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- MODAL GALLERY -->
	<div class="modal fade"
		 id="modal-gallery"
		 style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button"
							class="close"
							data-dismiss="modal"
							aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<img class="img-responsive"
						 style="margin: 0 auto;"
						 alt="Photo">
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
</div>

<?php $this->load->view('footer') ?>

<style>
	.tableTree td {
		cursor: pointer;
	}

	.tableTree tbody tr td {
		padding-top: 0;
		padding-bottom: 0;
	}
</style>

<!-- Bootstrap treefy -->
<!--<script type="application/javascript"-->
<!--		src="--><?php //echo base_url() . 'assets/plugins/treetable/bootstrap-treefy.js' ?><!--"></script>-->
<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.css" />
<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.theme.default.css" />
<script src="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.js"></script>
<script type="application/javascript"
		src="<?php echo base_url() . 'assets/apps/js/home.js' ?>"></script>
