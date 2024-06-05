<!--
/*
@application  : Asset Management
@author		  : Lukman Hakim (7143)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Kategori</th>
				              	<th>Sub Kategori</th>
								<th>Merk</th>
								<th>Tipe</th>
								<th>Beroperasi</th>
								<th>Standby</th>
								<th>Dalam Perbaikan</th>
								<th>Tidak Beroperasi</th>
								<th>Scrap</th>
				              	<th>Action</th>
				            </thead>
			              	<tbody>
			              		<?php
								$total_scrap = 0;
								$total_standby = 0;
								$total_beroperasi = 0;
								$total_dalam_perbaikan = 0;
								$total_tidak_beropersai = 0;
				              	foreach($aset_kategori as $dt){
									if(($dt->jumlah_scrap!=0)or($dt->jumlah_standby!=0)or($dt->jumlah_beroperasi!=0)or($dt->jumlah_dalam_perbaikan!=0)or($dt->jumlah_tidak_beropersai!=0)){
										$total_scrap += $dt->jumlah_scrap;
										$total_standby += $dt->jumlah_standby;
										$total_beroperasi += $dt->jumlah_beroperasi;
										$total_dalam_perbaikan += $dt->jumlah_dalam_perbaikan;
										$total_tidak_beropersai += $dt->jumlah_tidak_beropersai;
										echo "<tr>";
										echo "<td>".$dt->nama_kategori."</td>";
										echo "<td>".$dt->nama_jenis."</td>";
										echo "<td>".$dt->nama_merk."</td>";
										echo "<td>".$dt->nama_merk_tipe."</td>";
										echo "<td align='center'>".$dt->jumlah_beroperasi."</td>";
										echo "<td align='center'>".$dt->jumlah_standby."</td>";
										echo "<td align='center'>".$dt->jumlah_dalam_perbaikan."</td>";
										echo "<td align='center'>".$dt->jumlah_tidak_beropersai."</td>";
										echo "<td align='center'>".$dt->jumlah_scrap."</td>";
										echo "<td>
											  <div class='input-group-btn'>
												<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
													echo"<ul class='dropdown-menu pull-right'>";
													echo "<li><a href='../../transaksi/maintenance/it/0/".$dt->id_merk_tipe."'><i class='fa fa-search'></i> Detail </a></li>";
													echo"</ul>
											  </div>
											  </td>";
										echo "</tr>";
									}
				              	}
				              	?>
			              	</tbody>
							<?php 
							echo"
							<tbody>
									<tr>
										<th colspan='4'>Sub Total</th>
										<td align='center'>".$total_beroperasi."</td>
										<td align='center'>".$total_standby."</td>
										<td align='center'>".$total_dalam_perbaikan."</td>
										<td align='center'>".$total_tidak_beropersai."</td>
										<td align='center'>".$total_scrap."</td>
										<td>&nbsp;</td>
									</tr>	
							</tbody>		
							";
							?>
			            </table>
			        </div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/laporan/problem.js"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>

<style>
.small-box .icon{
    top: -13px;
}
</style>