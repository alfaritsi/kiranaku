<!--
	/*
        @application  :
        @author       : Akhmad Syaiful Yamang (8347)
        @date         : 28-Jan-19
        @contributor  :
              1. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              2. <insert your fullname> (<insert your nik>) <insert the date>
                 <insert what you have modified>
              etc.
    */
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/apps/css/order/order.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success page-wrapper">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
					</div>
					<form class="form-tambah-order"
						  enctype="multipart/form-data">
						<div class="box-body">
							<div class="row row-form 1"
								 id="form-katalog">
								<div class="col-sm-12">
									<div class="row">
										<div class="form-horizontal col-sm-6">
											<h4>FILTER BY :</h4>
											<div class="form-group">
												<label for="mesin"
													   class="col-sm-3 control-label text-left">Jenis</label>
												<div class="col-sm-9">
													<select class="form-control select2"
															name="jenis"
															data-katalog=""
															style="width: 100%;">
														<option value="0">All</option>
														<option value="B">Mesin</option>
														<option value="C">Komponen</option>
													</select>
												</div>
											</div>
											<div class="form-group hidden"
												 id="filter_mesin">
												<label for="mesin"
													   class="col-sm-3 control-label text-left">Mesin</label>
												<div class="col-sm-9">
													<select class="form-control select2"
															name="mesin"
															data-katalog=""
															style="width: 100%;">
														<option value="0">Silahkan pilih</option>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12">
									<div class="row">
										<div class="col-sm-3 pull-right">
											<div class="form-group">
												<div class="input-group">
													<input type="text"
														   class="form-control"
														   name="search"
														   placeholder="Search ...">
													<span class="input-group-addon"><i class="fa fa-search"></i>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<ol class="breadcrumb breadcrumb-right-arrow">
												<li class="breadcrumb-item active"><a href="#">Katalog</a></li>
											</ol>
										</div>
									</div>
									<div class="row katalog-product">
									</div>
								</div>
								<div class="col-sm-12 text-right">
									<ul class="pagination pagination-wrapper" data-katalog="">

									</ul>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/katalog/katalog.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/nusira/order/katalog.js"></script>
