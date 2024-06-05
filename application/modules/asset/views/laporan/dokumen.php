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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
		          	<div class="box-body">
			          	<div class="row">
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Pabrik: </label>
				                	<select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
				                  		<?php
					                		foreach($pabrik as $dt){
					                			echo "<option value='".$dt->id_pabrik."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
			          		<div class="col-sm-3">
			            		<div class="form-group">
				                	<label> Jenis Dokumen: </label>
				                	<select class="form-control select2" multiple="multiple" id="dokumen" name="dokumen[]" style="width: 100%;" data-placeholder="Pilih Jenis Dokumen">
				                  		<?php
					                		foreach($dokumen as $dt){
					                			echo "<option value='".$dt->id_inv_doc."'";
					                			echo ">".$dt->nama."</option>";
					                		}
					                	?>
				                  	</select>
				            	</div>
			            	</div>
		            	</div>
		            </div>					
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped my-datatable-extends-order">
		              		<thead>
				              	<th>Nomor Dokumen</th>
								<th>Pabrik</th>
								<th>Nomor Asset SAP</th>
								<th>Nomor Polisi</th>
								<th>Sub Kategori Asset</th>
								<th>Merk</th>
								<th>Tipe Dokumen</th>
								<th>Tanggal Berlaku</th>
								<th>Masa Berlaku</th>
								<th>Tanggal Berakhir</th>
								<th>Sisa Hari</th>
				            </thead>
			              	<tbody>
			              		<?php
				              	foreach($transaksi as $dt){
									$selisih_hari = ($dt->selisih_hari>0)?$dt->selisih_hari:"<font color='red'>(".$dt->selisih_hari*-1 .")</font>";
									echo "<tr>";
				              		echo "<td>".$dt->nomor_dokumen."</td>";
				              		echo "<td>".$dt->nama_pabrik."</td>";
				              		echo "<td>".$dt->nomor_sap."</td>";
				              		echo "<td>".$dt->nomor_polisi."</td>";
				              		echo "<td>".$dt->nama_jenis."</td>";
				              		echo "<td>".$dt->nama_merk."</td>";
				              		echo "<td>".$dt->nama_dokumen."</td>";
				              		echo "<td>".$dt->tanggal_berlaku."</td>";
				              		echo "<td>".$dt->periode."</td>";
				              		echo "<td>".$dt->tanggal_berakhir."</td>";
				              		echo "<td>".$selisih_hari."</td>";
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
<script src="<?php echo base_url() ?>assets/apps/js/asset/laporan/dokumen.js"></script>
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