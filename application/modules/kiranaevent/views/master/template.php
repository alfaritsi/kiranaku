<!--
/*
	@application  	  : Kirana Event 
		@author       : Airiza Yuddha (7849)
		@contributor  :
			  1. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  2. <insert your fullname> (<insert your nik>) <insert the date>
				 <insert what you have modified>
			  etc.
*/
 -->
<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-8">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<table id="main_table" class="table table-bordered table-striped my-datatable">
							<thead>
								<tr>
									<th>Type Berita</th>
									<th>Template Gambar</th>

									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong><?php echo $title_form; ?></strong></h3>
						<div class="pull-right">
							<button type="button"
									class="btn btn-sm btn-default"
									id="btn-new"
									style="display:none">Buat Template Gambar Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-type">
						<div class="box-body">
							
							<div class="form-group">
								<label for="type_fieldname">Type Berita</label>
								<div>
									<!-- <input type="text" class="form-control" name="type_fieldname" id="type_fieldname" placeholder="Masukkan Type Berita" required="required"> -->
									<select class="form-control input-xxlarge " name="type_fieldname" id="type_fieldname" style="width: 105%;"  required>
		                				<option value="0">Silahkan pilih tipe berita</option>
			                			<?php
			                				foreach($type as $r){
			                					echo "<option value='".$r->id_typeberita."'>".$r->type_berita."</option>";
			                				}
			                			?>
		                			</select>
								</div>
							</div>
							<div class="form-group">
								<label for="desc_fieldname">Keterangan</label>
								<div>
									<textarea class="form-control" name="desc_fieldname" id="desc_fieldname" placeholder="Keterangan"> </textarea>
								</div>
							</div>

							<div class="form-group">
								<label for="gambar_fieldname"> </label>
								<!-- <div>
									<input type="text" class="form-control" name="gambar_fieldname" id="gambar_fieldname" placeholder="Masukkan Gambar Berita" required="required">
								</div> -->
							<!-- </div> -->

								<div class="form-group" >
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<div class="btn-group btn-sm no-padding">
										<a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" 
											data-fancybox="image" data-type="image"><i class="fa fa-search"></i></a>
										<a class="btn btn-facebook btn-file">
										<div class="fileinput-new">Upload Gambar</div>
										<div class="fileinput-exists"><i class="fa fa-edit"></i></div>
										<input type="file" name="gambar_fieldname[]" id="gambar_fieldname"></a> 
										<a href="#" class="btn btn-pinterest fileinput-exists"data-dismiss="fileinput">
											<i class="fa fa-trash"></i></a>
										</div>
									</div>
								</div>

							</div>

							
						</div>
						<div class="box-footer">
							<input type="hidden" name="id_templategb" />
							<button type="button" class="btn btn-sm btn-success" name="action_btn" value="submit">Submit</button>
						</div>
					</form>
				</div>
			</div>
			<!--modal add_modal_detail-->
			
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/kiranaevent/master/mtemplate.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<!-- for attchment -->
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>


