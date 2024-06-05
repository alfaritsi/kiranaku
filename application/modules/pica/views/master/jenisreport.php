<!--
/*
	@application  : PICA 
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
						<table class="table table-bordered table-striped" id="sspTable">
							<thead>
								<tr>
									<th>Jenis Report</th>
									<th>Jenis Temuan</th>									
									<th>Responder</th>
									<th>Due Date(Hari)</th>
									<!-- <th>Verificator</th> -->
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
									style="display:none">Buat baru
							</button>
						</div>
					</div>
					<form role="form" class="form-master-jenisreport">
						<div class="box-body">
							
							<div class="form-group">
								<label for="jenis_report">Jenis Report</label>
								<div>
									<input type="text" class="form-control" name="jenis_report" id="jenis_report" placeholder="Masukkan jenis report" required="required">
								</div>
							</div>

							<div class="form-group">
								<label for="temuan">Jenis Temuan</label>
								<div>
									<select class="form-control select2" id="jenis_temuan" name="jenis_temuan" style="width: 100%;" data-placeholder="Pilih Role">
										<!-- <option value="0"> Masukan Jenis Temuan </option> -->
				                  		<?php
					                		foreach($temuan as $dt){
					                			echo "<option value='".$dt->id_pica_jenis_temuan."|".$dt->jenis_temuan."' >";
					                			echo $dt->jenis_temuan." - ".$dt->requestor."</option>";
					                		}
					                	?>
				                  	</select>
								</div>
							</div>
							
							
							<!-- <div class="form-group">
								<label for="responder">Responder</label>
								<div>
									<select class="form-control select2" id="responder" name="responder[]" multiple="multiple" style="width: 100%;" data-placeholder="Pilih Responder" readonly="readonly">
											                  		<?php
												                		foreach($posisi as $dt){
												                			echo "<option value='".$dt->id_posisi."'";
												                			echo ">".$dt->posisi."</option>";
												                		}
												                	?>
											                  	</select>
								</div>
							</div> -->
							<div class="form-group">
								<label for="responder">Responder</label>
								<div id="divResponder">
									<!-- <select class="form-control select2" id="responder" name="responder" data-placeholder="Pilih Responder" readonly="readonly"></select> -->
									<input type="text" class="form-control" name="responder[]" id="responder" readonly="readonly" required="required">
								</div>
								<input type="hidden" class="form-control" name="id_responder[]" id="id_responder" readonly="readonly">
							</div>
							<div class="form-group">
								<label for="duedate">Lama Due date(Hari)</label>
								<div>
									<input type="text" class="form-control" name="duedate" id="duedate" placeholder="Masukkan duedate" required="required">
								</div>
							</div>
							<!-- <div class="form-group">
								<label for="verificator">Verificator</label>
								<div>
									<select class="form-control select2" id="verificator" name="verificator[]" multiple="multiple" style="width: 100%;" data-placeholder="Pilih verificator">
				                  		<?php
					                		foreach($posisi as $dt){
					                			echo "<option value='".$dt->id_posisi."'";
					                			echo ">".$dt->posisi."</option>";
					                		}
					                	?>
				                  	</select>
								</div>
							</div> -->

						</div>
						<div class="box-footer">
							<input type="hidden" name="id_jenisreport" />
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
<script src="<?php echo base_url() ?>assets/apps/js/pica/master/mreport.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


