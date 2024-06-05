<!--
/*
    @application  : PM IT FO
    @author       : MATTHEW JODI (8944)
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
						<h3 class="box-title pull-left"><strong>Akses PM Mobile</strong></h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped my-datatable-extends">
							<thead>
								<tr>
									<th>User</th>
									<th>Role</th>
									<th>Pabrik</th>
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
						<h3 class="box-title pull-left title-form-mobile"><strong>Tambah Akses Mobile</strong></h3>
						<div class="pull-right">
							<button type="button"
									class="btn btn-sm btn-default hidden"
									id="btn-new-mobile"
									>Tambah Akses Mobile Baru
							</button>
						</div>
					</div>
					<form role="form"
						  class="form-master-mobile">
						<div class="box-body">
							<div class="form-group">
								<label>Role</label>
								<select class="form-control select2" name="role" data-allowclear="true" required>
									<option value='0'>Silahkan Pilih Role</option>
									<option value='PIC APAR'>PIC Apar</option>
									<option value='PIC ALAT BERAT'>PIC Alat Berat</option>
									<option value='OPERATOR'>Operator</option>
									<option value='Viewer HO'>Viewer HO</option>
									<!-- tambahan -->
								</select>
							</div>
							<div class="form-group">
								<label>User</label>
								<select class="form-control autocomplete" name="user" data-allowclear="true" required>
									<option></option>
								</select>
							</div>
                            <div class="form-group role-based">
								<label>Pabrik</label>
								<div class="checkbox pull-right select_all" style="margin:0;">
									<label><input type="checkbox" class="isSelectAll"> Select All</label>
								</div>
								<select class="form-control select2" name="pabrik[]" data-allowclear="true" multiple="multiple" required>
									<?php
									if ($pabrik) {
										$output = '';
										foreach ($pabrik as $dt) {
											$output .= '<option value="' . $dt->kode . '">' . $dt->nama . '</option>';
										}
										echo $output;
									}
									?>
								</select>
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden"
								   name="pengguna" value="<?php echo $pengguna; ?>" />
							<input type="hidden"
								   name="id_mobile" />
							<button type="button"
									class="btn btn-sm btn-success"
									name="action_btn"
									value="submit">Submit
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/mobile.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


