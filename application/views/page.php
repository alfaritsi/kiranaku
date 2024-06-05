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
<!-- fullCalendar -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fullcalendar/fullcalendar.css">

	  
<style type="text/css">
	
	.circle-avatar{
		/* make it responsive */
		max-width: 100%;
		width:100%;
		height:auto;
		display:block;
		/* div height to be the same as width*/
		padding-top:100%;

		/* make it a circle */
		border-radius:50%;

		/* Centering on image`s center*/
		background-position-y: center;
		background-position-x: center;
		background-repeat: no-repeat;

		/* it makes the clue thing, takes smaller dimension to fill div */
		background-size: cover;

		/* it is optional, for making this div centered in parent*/
		margin: 0 auto;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
	}
</style>
<div class="content-wrapper">
	<section class="content">
		<div class="slide-notif">
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
								<li data-target="#carousel-kirana-dna"
									data-slide-to="4"
									class=""></li>
							</ol>
							<div class="carousel-inner">
								<div class="item active">
									<img src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA.png"; ?>"
										 alt="First slide">
								</div>
								<div class="item">
									<img data-src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA1.png"; ?>"
										 alt="Second slide">
								</div>
								<div class="item">
									<img data-src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA2.png"; ?>"
										 alt="Third slide">
								</div>
								<div class="item">
									<img data-src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA3.png"; ?>"
										 alt="Fourth slide">
								</div>
								<div class="item">
									<img data-src="<?php echo base_url() . "assets/apps/img/slideshow/KiranaDNA4.png"; ?>"
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
			<div class="col-sm-4">
				<div class="box box-success kiranacare-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Kirana Care</strong></h3>
						<div class="box-tools pull-right">
							<button type="button"
									class="btn btn-box-tool"
									data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<!-- /.box-header -->

					<div class="box-body kiranacare-body"
						 id="kiranacare-wrapper">
						<?php
							$image_kiranacare 		= base_url() . "assets/apps/img/kirana_care.jpg";
							$image_kiranacare_big 	= base_url() . "assets/apps/img/kirana_care_big.jpg";
						?>
						<div class="kiranacare-container"
							 style="overflow: hidden; padding-bottom: 10px">
							<div class="row">
								<a href="javascript:void(0)" data-title="Kirana care"
								   data-image="<?php echo $image_kiranacare_big; ?>" class="col-sm-12" style="margin-bottom:10px" id="kirana_care">
									<div style="height: 100%; background: url('<?php echo $image_kiranacare ?>') no-repeat; background-size: 100% 100%;"></div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--dari sini--->
			<div class="col-md-8">
				<div class="box box-success ">
					<div class="box-header with-border">
						<h3 class="box-title"><strong>Berita Kirana</strong></h3>
						<div class="box-tools pull-right">
							<button type="button"
									class="btn btn-box-tool"
									data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
						</div>
					</div>
					<div class="box-body" id="news-wrapper">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tanggal" data-toggle="tab">Ulang Tahun</a></li>
								<?php 
									if(!empty($milad_today)){
										echo'<li><a href="#ulang_tahun" data-toggle="tab">Karyawan Ulang Tahun</a></li>';
									}
									if(!empty($berita_suka)){
										echo'<li><a href="#suka" data-toggle="tab">Berita Gembira</a></li>';
									}
									if(!empty($berita_duka)){
										echo'<li><a href="#duka" data-toggle="tab">Berita Duka</a></li>';
									}
								?>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tanggal">
									<div id="calendar"></div>
								</div>
                                <?php if(!empty($milad_today)) : ?>
								<div class="tab-pane" id="ulang_tahun">
									<div id="carousel-berita-milad" class="carousel slide" data-ride="carousel">
										<div class="carousel-inner">
											<?php 
												$no = 0;
												$message = '';
												foreach($milad_today as $data){
//													if($data->tgl == date("dm")) :
													$no++;
													$filename	= base_url()."assets/file/berita/suka/milad.png";
													$aktif 		= ($no==1)?"item active":"item";
													$message	.= '<div class="'.$aktif.'">';
													$message	.= '	<img src="'. $filename .'" alt="First slide" width="100%">';
                                                        $message	.= '	<div class="carousel-caption" style="padding: 0; height: 100%; width: 100%; left: 0; right: 0; bottom: 0"  width="100%">';
                                                        $message	.= '		<div width="100%" height="100%" style="margin: 0;position: absolute;top: 60%;left: 50%;-ms-transform: translate(-50%, -50%);transform: translate(-50%, -50%);width: 100%;">';
													$message	.= '			<div align="center" style="font-size:55px;color:green;"><b>'.$data->nama.'</b></div>';
													$message	.= '			<div align="center" style="font-size:25px;color:green;">('.$data->posst.')</div>';
													$message	.= '			<div align="center" style="font-size:25px;color:green;">'.$data->tanggal_milad.' '.$data->nama_bulan_milad.' '.date('Y').'</div>';
													$message	.= '			<div align="center" style="font-size:20px;color:green;">Wish you a very happy birthday,</div>';
													$message	.= '			<div align="center" style="font-size:20px;color:green;">May life lead you to great happiness,</div>';
													$message	.= '			<div align="center" style="font-size:20px;color:green;">Success and hope that,</div>';
													$message	.= '			<div align="center" style="font-size:20px;color:green;">all your wishes comes true.</div>';
													$message	.= '		</div>';
													$message	.= '	</div>';
													$message	.= '</div>';
//													endif;
												}
												echo $message;	
											?>
										</div>
										<a class="left carousel-control" href="#carousel-berita-milad" data-slide="prev"><span class="fa fa-angle-left"></span></a>
										<a class="right carousel-control" href="#carousel-berita-milad" data-slide="next"><span class="fa fa-angle-right"></span></a>
									</div>
								</div>
                                <?php endif; ?>
                                <?php if(!empty($berita_suka)) : ?>
								<div class="tab-pane" id="suka">
									<div id="carousel-berita-suka" class="carousel slide" data-ride="carousel">
										<div class="carousel-inner">
											<?php 
												$no = 0;
												$message = '';
												foreach($berita_suka as $data){
													$no++;
													$filename 	= base_url()."".$data->template;
													$aktif 		= ($no==1)?"item active":"item";
													$color	   	= ($data->gender=='Son')?"#57a1e0":"#dc14ba";
													$message	.= '<div class="'.$aktif.'">';
													$message	.= '	<img src="'. $filename .'" alt="First slide">';
													$message	.= '	<div class="carousel-caption" style="padding: 0; height: 100%; bottom: 0; right: 0; left: 50%;">';
													$message	.= '		<div style="top: 50%;transform: translateY(-50%);position: absolute;">';
													$message	.= '			<div id="font_warna_1" style="color:'.$color.'">'.ucwords(strtolower($data->editorial1)).'</div>';
													$message	.= '			<div id="font_warna_2" style="color:'.$color.'"><b>'.ucwords(strtolower($data->nama_karyawan)).' '.$data->gender.'</b></div>';
													$message	.= '			<div id="font_warna_1" style="color:'.$color.'">('.ucwords(strtolower($data->posisi_karyawan)).')</div>';
													$message	.= '			<div id="font_warna_3" style="color:'.$color.'; margin-top: 10%"><b>'.ucwords(strtolower($data->nama_anak)).'</b></div>';
													$message	.= '			<div id="font_warna_1" style="color:'.$color.'">on '.$data->name_days.', '.$data->tanggal_konversi.'</div>';
													$message	.= '		</div>';
													$message	.= '	</div>';
													$message	.= '</div>';
													echo $message;	
												}
											?>
										</div>
										<a class="left carousel-control" href="#carousel-berita-suka" data-slide="prev"><span class="fa fa-angle-left"></span></a>
										<a class="right carousel-control" href="#carousel-berita-suka" data-slide="next"><span class="fa fa-angle-right"></span></a>
									</div>
								</div>
                                <?php endif; ?>
                                <?php if(!empty($berita_duka)) : ?>
								<div class="tab-pane" id="duka">
									<div id="carousel-berita-duka" class="carousel slide" data-ride="carousel">
										<div class="carousel-inner">
											<?php 
												$no = 0;
												$message = '';
												foreach($berita_duka as $data){
													$no++;
													$filename = base_url()."".$data->template;
													$aktif = ($no==1)?"item active":"item";
													$message	.= '<div class="'.$aktif.'">';
													$message	.= '	<img src="'. $filename .'" alt="First slide" width="100%">';
													$message	.= '	<div class="carousel-caption" style="padding: 0; height: 100%; width: 100%; left: 0; right: 0">';
													$message	.= '		<div width="100%" height="100%" style="transform: translateY(20%);">';
													$message	.= '			<div align="center" style="font-size:25px; color:black;">'.$data->editorial1.'</div>';
													$message	.= '			<div align="center" style="font-size:25px; color:black;">'.$data->editorial2.'</div>';
													$message	.= '			<div align="center" style="font-size:25px; color:black;">'.$data->editorial3.'</div>';
													$message	.= '			<div align="center" style="font-size:55px; color:black;"><b>'.$data->nama_keluarga.'</b></div>';
													$message	.= '			<div align="center" style="font-size:25px; color:black;"><b>'.$data->status_keluarga.' dari '.$data->gender_karyawan.' '.$data->nama_karyawan.'</b></div>';
													$message	.= '			<div align="center" style="font-size:25px; color:black;">('.$data->posisi_karyawan.')</div><br>';
													$message	.= '			<div align="center" style="font-size:25px; color:black;">Pada hari '.$data->hari.', '.$data->tanggal_konversi.'</div><br><br>';
													$message	.= '			<div align="center" style="font-size:25px; color:black;">'.$data->editorial4.'</div><br>';
													$message	.= '		</div>';
													$message	.= '	</div>';
													$message	.= '</div>';
												}	
												echo $message;
											?>
										</div>
										<a class="left carousel-control" href="#carousel-berita-duka" data-slide="prev"><span class="fa fa-angle-left"></span></a>
										<a class="right carousel-control" href="#carousel-berita-duka" data-slide="next"><span class="fa fa-angle-right"></span></a>
									</div>
								</div>
                                <?php endif; ?>
							</div>
						</div>
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
			<!--dari sampe sini--->
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
	<!-- modal for detail -->
	<div id="fullCalModal" class="modal fade">
	    <div class="modal-dialog">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span> <span class="sr-only">close</span></button>
	                <h4 id="modalTitle" class="modal-title"></h4>
	            </div>
	            <div id="modalBody" class="modal-body">
	            	<div id="modalBodyDiv"> </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                <!-- <button class="btn btn-primary">Remove</button> -->
	            </div>
	        </div>
	    </div>
	</div>
	<!-- end modal for detail -->
	
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

	#font_warna_1{
		font-size:25px;
		font-family: "Candara";
	}
	#font_warna_2{
		font-size:30px;
		font-family: "Candara";
	}
	#font_warna_3{
		font-size:45px;
		font-family: "Candara";
	}
	#font_nama{
		font-size:45px;
		font-family: "Harlow";
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
<!-- fullCalendar -->
<script type="application/javascript" src="<?php echo base_url() . 'assets/plugins/fullcalendar/fullcalendar.min.js' ?>"></script>
<script type="application/javascript"
		src="<?php echo base_url() . 'assets/apps/js/home.js' ?>"></script>

// <script>
                // /* initialize the calendar
                 // -----------------------------------------------------------------*/
                // //Date for the calendar events (dummy data)
                // var date = new Date();
                // var d = date.getDate(),
                        // m = date.getMonth(),
                        // y = date.getFullYear();
                // $('#calendar').fullCalendar({
                    // header: {
                        // left: 'prev,next today',
                        // center: 'title',
                        // right: ''
                    // },
                    // buttonText: {//This is to add icons to the visible buttons
                        // prev: "<span class='fa fa-caret-left'></span>",
                        // next: "<span class='fa fa-caret-right'></span>",
                        // today: 'today',
                        // month: 'month',
                        // week: 'week',
                        // day: 'day'
                    // },
					// // dayClick: function() {
							// // alert('a day has been clicked!');
					// // },
                    // //Random default events
                    // events: [
						// <?php 
							// foreach($milad as $dt){
								// $gambar = base_url().'assets/apps/'.$dt->gambar;
								// echo "
										// {
											// title			: '$dt->id_karyawan - $dt->nama',
											// start			: new Date(y, ($dt->bulan_milad-1), $dt->tanggal_milad),
											// allDay			: true,
											// backgroundColor	: '#0073b7',
											// borderColor		: '#0073b7',
											// nik				: '$dt->id_karyawan',
											// nama			: '$dt->nama',
											// email			: '$dt->email',
											// bagian			: '$dt->posst',
											// gambar			: '$gambar'
										// },
								// ";
							// }
						// ?>
                    // ],
					// //event click event calendar
					// eventClick: function(event){
						// //detail
						// var det = "<div class='row'><div class='col-sm-12 text-center margin-bottom'><img src='"+event.gambar+"' class='img-thumbnail img-responsive iimage' /></div></div>";
							// det	+= "<table class='table table-bordered'>";
							// det	+= 		"<tr><td>NIK</td><td>"+event.nik+"</td></tr>";
							// det	+= 		"<tr><td>Nama</td><td>"+event.nama+"</td></tr>";
							// det	+= 		"<tr><td>Email</td><td>"+event.email+"</td></tr>";
							// det	+= 		"<tr><td>Bagian</td><td>"+event.bagian+"</td></tr>";
							// // det	+= 		"<tr><td>Bagian</td><td>"+event.gambar+"</td></tr>";
							// det	+= "</table>";
						// $("#modalBody").html(det);
						// $('#fullCalModal').modal();
					 // },
					// lang: 'en',
					// eventLimit: true, // If you set a number it will hide the itens
					// eventLimitText: "Something", // Default is `more` (or "more" in the lang you pick in the option)
					// editable: false,
					// droppable: false, // this allows things to be dropped onto the calendar !!!
					// eventRender: function(event, eventElement) { //set icon to title
						// if (event.imageurl) {
							// //console.log(eventElement.find("span.fc-event-title"));
							// eventElement.find("span.fc-event-title").prepend(" <img src='" + event.imageurl +"' width='20' height='20'> ");
						
						// }
						
						
					// },
                // });	

// </script>

