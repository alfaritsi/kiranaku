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
<style type="text/css">
	fieldset {
	  position: relative;
	}
	.legend2 {
	  position: absolute;
	  top: 0.7em;
	  right: 20px;
	  background: #fff;
	  line-height:1.2em;
	}
</style>
<script type="text/javascript">
	var login_nik 		= "<?php echo base64_decode($this->session->userdata("-nik-")); ?>";
	var login_posisi	= "<?php echo base64_decode($this->session->userdata("-posst-")); ?>";
	var level_user_sess	= '<?php echo json_encode($this->session->userdata("-sess_pica_data_oto-")); ?>';
	<?php 
		// check otorisasi
		$posisi 	= base64_decode($this->session->userdata("-posst-"));
		$dataposisi = $this->dtranspica->get_data_pica_otorisasi('portal',$posisi);
		$level 		= 0;
		$if_approve = 0;
		$if_decline = 0;
		foreach ($dataposisi as $dt) {
			$level 		= $dt->level;
			$pabrik 	= rtrim($dt->pabrik,', ');
			$nama_role 	= $dt->nama_role;
			$if_approve = $dt->if_approve;
			$if_decline = $dt->if_decline;

		}
		
	?>
	var level_user = "<?php echo $level; ?>"; 
	var if_approve = "<?php echo $if_approve; ?>"; 
	var if_decline = "<?php echo $if_decline; ?>"; 
	// console.log(login_nik,level_user,if_approve,if_decline);
</script>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12 ">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
						
						<!-- <div id="divAddButton">
							<button type="button" class="btn btn-sm btn-success pull-right" id="add_template_button">Tambah Data</button>
						</div> -->
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped" id="sspTable">
							<thead>
								<tr>
									<th>Nomor Pica</th>
									<th>Tanggal</th>
									<th>Pabrik</th>
									<th>Jenis Report</th>									
									<th>Jenis Temuan</th>									
									<th>Buyer</th>
									<!-- <th>Status</th> -->
									<!-- <th>Jumlah Baris</th> -->
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
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/pica/transaksi/approval.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


