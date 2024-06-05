<!--
/*
@application  : Equipment Management
@author     : Lukman Hakim (7143)
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
                <div class="col-sm-12">
                	<div class="box box-success">
			          	<div class="box-header">
			            	<h3 class="box-title"><strong>Master Pabrik</strong></h3>
			            	<div class="btn-group pull-right pr">
							    <button id="sinkron" class="btn btn-sm btn-success pull-right"><i class="fa fa-random" style="color:white;padding-right: 5px;"></i> SINKRONISASI DATA</button>
							</div>
			          	</div>
						<div class="box-body">
							<table class="table table-bordered table-striped datatable-custom">
								<thead>
									<tr>
										<th>Kode</th>
										<th>Pabrik</th>
										<th>Alamat</th>
									</tr>
								</thead>
								<tbody>
									<?php
						              	foreach($pabrik as $pab){
						              		echo "<tr>";
						              		echo "<td>".$pab->kode."</td>";
						              		echo "<td>".$pab->nama."</td>";
						              		echo "<td>".$pab->keterangan."</td>";
						              		echo "</tr>";
						              	}
						            ?>
								</tbody>
							</table>
						</div>
					</div>
					<!--end box-->
                </div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/pabrik.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


