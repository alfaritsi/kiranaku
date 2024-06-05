<!-- 
/*
    @application  : K-IASS
    @author       : MATTHEW JODI (8944)
    @contributor  :
          1. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          2. <insert your fullname> (<insert your nik>) <insert the date>
             <insert what you have modified>
          etc.
    */ -->


 <?php $this->load->view('header') ?>

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
						<div class="pull-right">
							<button type="button"
									class="btn btn-sm btn-success"
									id="btn_form"
									>Form Create Role
							</button>
						</div>
					</div>
					<div class="box-body">
                        <div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label>Flow Approval</label>
									<select class="form-control select2"
											id="flow"
											name="flow"
											style="width: 100%;"
											data-placeholder="Silahkan pilih Flow Approval">
											<option value="0">Silahkan Pilih</option>
											<?php
												if ($flow) {
													foreach ($flow as $flow) {
														echo "<option value='" . $flow->id_flow . "'>" . $flow->lokasi." - ".$flow->keterangan . "</option>";
													}
												}
											?>
									</select>
								</div>
							</div>
                        </div>

						<table class="table table-bordered table-striped my-datatable-order-col2">
							<thead>
								<tr>
									<th>Role</th>
									<th>Level</th>
									<th>Detail</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<!-- </section>
											
	<section class="content" id="bottomView"> -->
		<div class="row" id="bottomView">
			<div class="col-sm-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title pull-left"><strong>Form Create Role</strong></h3>
						<div class="pull-right">
							<button type="button"
									class="btn btn-sm btn-default"
									id="btn-new"
									style="display:none">Buat Role Baru
							</button>
						</div>
					</div>
					<form role="form"
						  class="form-master-role">
						<div class="box-body">
							<div class="form-group">
								<label for="role">Role</label>
								<input type="text"
									   class="form-control"
									   id="role"
									   name="role"
									   required="required"
									   placeholder="Masukkkan nama role">
							</div>
							<div class="form-group">
								<label for="level">Level</label>
								<input type="number"
									   class="form-control"
									   id="level"
									   name="level"
									   required="required"
									   placeholder="Masukkkan level role"
									   min="0">
							</div>
							<div class="form-group">
								<label for="level">Tipe User</label>
								<select class="form-control select2"
										name="tipe_user"
										id="tipe_user"
										required="required">
									<?php
										echo "<option value='nik'>NIK</option>";
										echo "<option value='posisi'>Posisi</option>";
									?>
								</select>
							</div>
							<div class="form-group">
								<div class="checkbox">
					                <label>
					                  <input type="checkbox" id="aksesLimitPabrik" name="aksesLimitPabrik" value="on" > <strong>Limit Pabrik</strong>
					                  <input type="hidden" id="isLimitPabrik" name="isLimitPabrik" value="0">
					                </label>
		              			</div>
							</div>
							<div class="form-group">
								<div class="checkbox">
					                <label>
					                  <input type="checkbox" id="aksesDelete" name="aksesDelete" value="on" > <strong>Akses Delete</strong>
					                  <input type="hidden" id="isDelete" name="isDelete" value="0">
					                </label>
		              			</div>
							</div>
							<label for="role">Setting Flow Approval</label>
							<div class="inpFlow" id="inpFlow">

							
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden"
								   name="kode_role" />
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
<script src="<?php echo base_url() ?>assets/apps/js/kiass/master/role.js?<?php echo time();?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


