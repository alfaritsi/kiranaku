<!--
/*
	@application  : Monitoring CCTV 
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
						<table class="table table-bordered table-striped my-datatable-order-col2">
							<thead>
								<tr>
									<th>Kriteria</th>
									<th>Range Kriteria</th>		
									<th>Warna</th>								
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ($criteria) {
										$output = "";
										foreach ($criteria as $r) {
											$class 	= "<span class='".$r->css."'>".$r->criteria."</span>";
											$output .= '<tr>'; 
											$output .= '	<td>' . $r->criteria . '<br /> '. $r->label_active .'</td>';
											$output .= '	<td>' . $r->val_min .' s/d '. $r->val_max .' </td>';
											$output .= '	<td>' . $class .' </td>';	
											$output .= '	<td>';
											$output .= '		<div class="input-group-btn">';
											$output .= '			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
											$output .= '			<ul class="dropdown-menu pull-right">';
											if ($r->na_achv == 'n' && $r->del_achv == 'n') {
												// $output .= '			<li><a href="javascript:void(0)" class="detail" data-detail="' . $r->id_mdot . '"><i class="fa fa-search"></i> Detail</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="' . $r->id_criteriaAchv . '"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="nonactive" data-nonactive="' . $r->id_criteriaAchv . '"><i class="fa fa-minus-square-o"></i> Non Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_criteriaAchv . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
											} else if ($r->na_achv == 'y' && $r->del_achv == 'n'){
												$output .= '			<li><a href="javascript:void(0)" class="setactive" data-setactive="' . $r->id_criteriaAchv . '"><i class="fa fa-check-square-o"></i> Set Akif</a></li>';
												$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="' . $r->id_criteriaAchv . '"><i class="fa fa-trash-o"></i> Hapus</a></li>';
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
									style="display:none">Buat Kriteria CCTV Baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-criteria">
						<div class="box-body">
							
							<div class="form-group">
								<label for="kriteria_fieldname">Kriteria CCTV</label>
								<input type="text" class="form-control" name="kriteria_fieldname" id="kriteria_fieldname" placeholder="Masukkan Kriteria" required="required">
							</div>
							<div class="form-group">
								<label for="warna_fieldname">Warna</label>
								<select class="form-control input-xxlarge " name="warna_fieldname" id="warna_fieldname" style="width: 100%;"  required>
									<option value="0">Silahkan pilih warna</option>
		                			<?php
		                				foreach($css as $r){
		                					echo "<option value='".$r->id_cssColor."'>".$r->color."</option>";
		                				}
		                			?>
								</select>
							</div>
							<div class="form-group">
								<label for="kriteria_val_fieldname">Nilai Kriteria</label>
								
							</div>
							

							<div class="row">
								<div class="col-sm-1">
									<label for="min_fieldname">Min</label>	
								</div>
								<div class="col-sm-3">							
									<input type="text" class="form-control col-sm-5" name="min_fieldname" id="min_fieldname" placeholder="Min" value=0>	
								</div >
								<div class="col-sm-1">
									<label for="max_fieldname">Max</label>	
								</div>
								<div class="col-sm-3">
									<input type="text" class="form-control col-sm-5" name="max_fieldname" id="max_fieldname" placeholder="Max" required="required">
								</div>
							</div>
							
						</div>
						<div class="box-footer">
							<input type="hidden" name="id_criteriaAchv" />
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
<script src="<?php echo base_url() ?>assets/apps/js/cctv/master/mcriteria.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


