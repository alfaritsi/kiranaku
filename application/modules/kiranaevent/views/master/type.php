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
									style="display:none">Buat Type Berita Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-type">
						<div class="box-body">
							
							<div class="form-group">
								<label for="type_fieldname">Type Berita</label>
								<div>
									<input type="text" class="form-control" name="type_fieldname" id="type_fieldname" placeholder="Masukkan Type Berita" required="required">
								</div>
							</div>

							<div class="form-group">
								<label for="desc_fieldname">Keterangan</label>
								<div>
									<textarea class="form-control" name="desc_fieldname" id="desc_fieldname" placeholder="Keterangan"> </textarea>
								</div>
							</div>
							
						</div>
						<div class="box-footer">
							<input type="hidden" name="id_typeberita" />
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
<script src="<?php echo base_url() ?>assets/apps/js/kiranaevent/master/mtype.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


