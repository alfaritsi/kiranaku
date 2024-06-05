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
						<table class="table table-bordered table-striped my-datatable-extends">
							<thead>
								<tr>
									<th>Lokasi</th>
									<th>Jenis Barang</th>
									<th>Alias Flow</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($flow) {
                                        $output = "";
										foreach ($flow as $dt) {
											$output .= '<tr>';
											$output .= '	<td>' . $dt->lokasi . '</td>';
											$output .= '	<td>' . $dt->keterangan . '</td>';
											$output .= '	<td>' . $dt->alias_flow . '</td>';
                                            if ($dt->del !== 'n' || $dt->na !== 'n') {
                                                $output .= '	<td><label class="label label-danger">NOT ACTIVE</label></td>';
											}else{
                                                $output .= '	<td><label class="label label-success">ACTIVE</label></td>';
                                            }
											$output .= '	<td>';
											$output .= '		<div class="input-group-btn">';
											$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
											$output .= '			<ul class="dropdown-menu pull-right">';
											if ($dt->na == 'n') {
												$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="' . $dt->id_flow . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="nonactive" data-nonactive="' . $dt->id_flow . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $dt->id_flow . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											} else {
												$output .= '			<li><a href="javascript:void(0)" class="setactive" data-setactive="' . $dt->id_flow . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $dt->id_flow . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											}
											$output .= '			</ul>';
											$output .= '		</div>';
											$output .= '	</td>';
											$output .= '</tr>';
										}
										echo $output;
									}
								?>
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
									style="display:none">Buat Flow Approval Baru
							</button>
						</div>
					</div>
					<form role="form"
						  class="form-master-flow">
						<div class="box-body">
                            <div class="form-group">
								<label for="status">Lokasi</label>
								<select class="form-control select2"
										name="lokasi"
										id="lokasi"
										required="required">
									<?php
										echo "<option value='HO'>Head Office</option>";
										echo "<option value='Pabrik'>Pabrik</option>";
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="role">Keterangan (Jenis Barang)</label>
								<input type="text"
									   class="form-control"
									   id="keterangan"
									   name="keterangan"
									   required="required"
									   placeholder="Masukkkan keterangan jenis barang">
							</div>
							<div class="form-group">
								<label for="role">Alias (Kode Flow)</label>
								<input type="text"
									   class="form-control"
									   id="alias_flow"
									   name="alias_flow"
									   required="required"
									   placeholder="Masukkkan Alias (Kode Flow)">
							</div>
						</div>
						<div class="box-footer">
							<input type="hidden"
								   name="id_flow" />
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
<script src="<?php echo base_url() ?>assets/apps/js/kiass/master/flow.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


