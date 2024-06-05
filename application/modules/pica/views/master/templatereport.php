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
	@-moz-document url-prefix() {
	  .legend2 {
		  position: absolute;
		  top: -2.7em;
		  right: 20px;
		  background: #fff;
		  line-height:1.2em;
		  z-index:1;
		}
	}
</style>
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-12 ">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
						<div id="divAddButton">
							<button type="button" class="btn btn-sm btn-success pull-right" id="add_template_button">Tambah Data</button>
						</div>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped" id="sspTable">
							<thead>
								<tr>
									<th>Buyer</th>
									<th>Jenis Temuan</th>									
									<th>Jenis Report</th>
									<th>Jumlah Tipe</th>
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
<script src="<?php echo base_url() ?>assets/apps/js/pica/master/mtemplatereport.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


