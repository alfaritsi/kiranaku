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
						<input type="hidden" name="session_role_level" value="<?php echo $session_role[0]->level; ?>">
						<input type="hidden" name="session_role_nama" value="<?php echo $session_role[0]->nama_role; ?>">
						<input type="hidden" name="session_role_delete" value="<?php echo $session_role[0]->akses_delete; ?>">
					</div>
					<!-- FILTER -->
					<div class="box-body">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label>Pabrik / Plant</label>
									<select class="form-control select2"
											multiple="multiple"
											id="plant"
											name="plant[]"
											style="width: 100%;"
											data-placeholder="Silahkan pilih pabrik / plant">
										<?php
											if ($pabrik) {
												$output = "";
												foreach ($pabrik as $pl) {
													$output .= "<option value='" . $pl->plant . "'>" . $pl->nama . "</option>";
												}
												echo $output;
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>Tahun</label>
									<select class="form-control select2"
											multiple="multiple"
											id="tahun"
											name="tahun[]"
											style="width: 100%;"
											data-placeholder="Silahkan pilih tahun">
										<?php
											foreach ($tahun as $dt) {
												echo "<option value='" . $dt->tahun . "'";
												
												if ($dt->tahun == date('Y')) {
													echo "selected";
												}
												
												echo ">" . $dt->tahun . "</option>";
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label>Status</label>
									<select class="form-control select2"
											multiple="multiple"
											id="status"
											name="status[]"
											style="width: 100%;"
											data-placeholder="Silahkan pilih status">
										<option value="onprogress">
											On Progress
										</option>
										<option value="drop">Drop</option>
										<option value="finish">Finish</option>
										<option value="completed">Completed</option>
										<option value="deleted">Deleted</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="box-body">
						<table class="table table-bordered table-striped table-responsive table-hover my-datatable-extends-order"
							   data-scrollx="true"
							   data-textright="3"
							   data-textcenter="0-6-7">
							<thead>
								<th>No PP</th>
								<th>Perihal</th>
								<th>Tanggal</th>
								<th>Status</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php
									foreach ($list as $dt) {
										$list_so = "";
										if($dt->list_so !== ""){
											$list_so .= '<div>';
											foreach (explode(",", $dt->list_so) as $v) {
												if ($v)
													$list_so .= ' <span class="label label-primary">No SO : '.$v.'</span>';
											}
											$list_so .= '</div>';
										}


										
										echo "<tr>";

										echo "<td>" . $dt->no_pp."<br>".$list_so."</td>";
										echo "<td>" . $dt->perihal . "</td>";
										echo "<td>" . $dt->format_tanggal_pengajuan . "</td>";
										$pic_ho = '';
										if($dt->status == '9' || $dt->status == '16'){
											$pic_ho = $dt->pic_ho;
										}
										
										
										$desc = "<br><small>Sedang diproses di " . $dt->nama_role . " ". $pic_ho ."</small>";

										if($dt->status == 'finish'){
											$desc = $dt->cek_so == '0' ? "" : "<br><small>Menunggu Kode Customer Accounting</small>";
										}

										if($dt->status == 'deleted'){
											$desc = "";
										}

										echo "<td>" . $dt->view_status . $desc ."</td>";

										
										echo "<td>
												<div class='input-group-btn'>
												<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
												<ul class='dropdown-menu pull-right'>";
										$link_detail = base_url() . "kiass/transaksi/detail/" . str_replace("/", "-", $dt->no_pp);
										$link_edit = base_url() . "kiass/transaksi/edit/" . str_replace("/", "-", $dt->no_pp);
										$link_deviasi = base_url() . "kiass/deviasi/tambah/" . str_replace("/", "-", $dt->no_pp);
										$target_link = "_blank";
										
										echo "      <li><a href='" . $link_detail . "' class='action' target='" . $target_link . "'><i class='fa fa-list'></i> Detail</a></li>";
										if(($dt->status == '1' || $dt->status == '2') && ( $session_role[0]->level == '1' ||  $session_role[0]->level == '2') ){
											echo "      <li><a href='" . $link_edit . "' class='action' target='" . $target_link . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
										}

										if($session_role[0]->akses_delete == 'yes' && $dt->status == $session_role[0]->level ){
											echo "      <li><a href='javascript:void(0)' id='delete_pp' data-delete='".$dt->no_pp."'><i class='fa fa-trash'></i> Delete</a></li>";
										}

										if($dt->status == 'finish' && $session_role[0]->level == '1' && $dt->cek_so == '0' && $dt->count_deviasi == '0'){
											echo "      <li><a href='" . $link_deviasi . "' class='action' target='" . $target_link . "'><i class='fa fa-plus'></i>Buat Deviasi</a></li>";
										}

										echo "	</ul>
												</div>
											</td>";
										echo "</tr>";
									}
								?>
							
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/kiass/transaksi/lists.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
