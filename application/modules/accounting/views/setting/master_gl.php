<!--
/*
@application    : Attachment Accounting
@author 		: Matthew Jodi (8944)
@contributor	: 
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
			<div class="col-sm-9">
	    		<div class="box box-success">
	          		<div class="box-header">
	            		<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
	          		</div>
	          		<!-- /.box-header -->
		          	<div class="box-body">
		           		<table class="table table-bordered table-striped tbl-gl">
		              		<thead>
				                <tr>
				                  <th class="text-center">G/L Account</th>
				                  <th class="text-center">Date Created</th>
				                  <th class="text-center">Status</th>
				                  <th class="text-center">Action</th>
				                </tr>
				            </thead>
			              	<tbody>
				                <?php
					                foreach($list as $dt){
					                  echo "<tr>";
					                  echo "<td>".$dt->account."</td>";
					                  echo "<td>".$dt->format_tanggal_buat."</td>";
					                  echo "<td>".$dt->view_status."</td>";
					                  echo "<td>
					                          <div class='input-group-btn'>
					                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
					                            <ul class='dropdown-menu pull-right'>";
					                     
					                      if($dt->na == 'n'){
					                        echo "<li><a href='#' class='nonactive' data-nonactive='".$dt->gl_account."'><i class='fa fa-check'></i> Deactivate</a></li>";
					                      }else{
					                        echo "<li><a href='#' class='setactive' data-setactive='".$dt->gl_account."'><i class='fa fa-check'></i> Activate</a></li>";
					                      }
					                      echo "</ul>
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
			<div class="col-sm-3">
				<div class="box box-success">
                    <div class="box-header">
	            		<h3 class="box-title"><strong>Form <?php echo $title; ?></strong></h3>
	          		</div>
		          	<!-- form start -->
		          	<form role="form" class="form-gl">
	            		<div class="box-body">
		              		<div class="form-group">
		                		<label for="gl_account">G/L Account</label>
                                <input type="hidden" name="deskripsi">
				                <select name='gl_account' 
                                class='form-control select-gl autocomplete' required 
                                data-allowclear="true">
                                    <option></option>
                                </select>
		             		</div>
		              		
		            	</div>
		            	<div class="box-footer">
		              		<button type="Submit" name="action_btn" class="btn btn-success">Submit</button>
						</div>
		          	</form>
		        </div>
			</div>

		</div>


	</section>
</div>

<?php $this->load->view('footer') ?>


<script src="<?php echo base_url() ?>assets/apps/js/accounting/setting/master_gl.js"></script>
