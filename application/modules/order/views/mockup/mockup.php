<!-- 
Controller
public function mockup(){

		$this->general->check_access();
	    $data['generate']   = $this->generate; 
	    $data['module']     = $this->router->fetch_module();
        $data['user']       = $this->general->get_data_user();
		$this->load->view("mockup", $data);

	}
 -->


<?php $this->load->view('header') ?>

<div class="content-wrapper">
	<section class="content">
		<div class="box box-success">
			<div class="box-header">
				<h3 class="box-title"><strong>ORDERING FORM</strong></h3>
			</div>
			<div class="box-body">
				<div class="row text-center">
					<div class="text-center">
						<ul class="nav nav-pills nav-justified" style="float: none; margin:0 auto; margin-left: 34%;">
							<li style="width: 40%; background-color: #318860; color: #ffffff; padding: 10px 50px 10px 50px;">STEP 1<br>CHOOSE ITEM</li>
							<li style="width: 40%; background-color: #dadcdb;">STEP 2<br>QUOTATION</li>
						</ul>
					</div>
				</div>

				<fieldset class="fieldset-success" style="padding-bottom: 15px;">
	          		<legend class="text-center">Catalog</legend>

	          		<div class="row" style="padding-bottom: 20px;">
	          			<div class="col-md-3">
	          				<select class="form-control">
				                <option>Category</option>
				                <option>option 2</option>
				                <option>option 3</option>
				                <option>option 4</option>
				                <option>option 5</option>
				              </select>	
	          			</div>
	          			<div class="col-md-6"></div>
	          			<div class="col-md-3">
	          				
		          		<div class="input-group ">
			              <div class="input-group-addon">
			                <i class="fa fa-search"></i>
			              </div>
			              <input type="text" class="form-control">
		            	</div>
	          			</div>
	          		</div>


	          		<div class="row">
					  <div class="col-md-4">
					    <div class="thumbnail" style="height: 300px;">
					      <img src="<?php echo base_url(); ?>/assets/apps/img/test/1.jpg" style="width: 100px;height: 150px;">
					      <div class="caption">
					        <h3>Tutup Pulley</h3>
					        <div style="padding-top: 10px;">
					        	<a href="#" class="btn btn-primary" style="width: 100px;">Buy</a> <a href="#" class="btn btn-default" role="button">View Component</a></p>
					        </div>
					      </div>
					    </div>
					  </div>
					
					  <div class="col-md-4">
					    <div class="thumbnail" style="height: 300px;">
					      <img src="<?php echo base_url(); ?>/assets/apps/img/test/2.jpg" style="width: 100px;height: 150px;">
					      <div class="caption">
					        <h3>Tapak Mesin</h3>
					        <div style="padding-top: 10px;">
					        	<a href="#" class="btn btn-primary" style="width: 100px;">Buy</a> 
					        </div>
					      </div>
					    </div>
					  </div>

					  <div class="col-md-4">
					    <div class="thumbnail" style="height: 300px;">
					      <img src="<?php echo base_url(); ?>/assets/apps/img/test/3.jpg" style="width: 100px;height: 150px;">
					      <div class="caption">
					        <h3>Dudukan Elektro Motor</h3>
					        <div style="padding-top: 10px;">
					        	<a href="#" class="btn btn-primary" style="width: 100px;">Buy</a> 
					        </div>
					      </div>
					    </div>
					  </div>
					</div>

					<div class="row">
					  <div class="col-md-4">
					    <div class="thumbnail" style="height: 300px;">
					      <img src="<?php echo base_url(); ?>/assets/apps/img/test/6.jpg" alt="...">
					      <div class="caption">
					        <h3>Rotor</h3>
					        <div style="padding-top: 10px;">
					        	<a href="#" class="btn btn-primary" style="width: 100px;">Buy</a> <a href="#" class="btn btn-default" role="button">View Component</a></p>
					        </div>
					      </div>
					    </div>
					  </div>
					
					  <div class="col-md-4">
					    <div class="thumbnail" style="height: 300px;">
					      <img src="<?php echo base_url(); ?>/assets/apps/img/test/7.jpg" alt="...">
					      <div class="caption">
					        <h3>Kaki Box Belakang</h3>
					        <div style="padding-top: 10px;">
					        	<a href="#" class="btn btn-primary" style="width: 100px;">Buy</a> 
					        </div>
					      </div>
					    </div>
					  </div>

					  <div class="col-md-4">
					    <div class="thumbnail" style="height: 300px;">
					      <img src="<?php echo base_url(); ?>/assets/apps/img/test/8.jpg" alt="...">
					      <div class="caption">
					        <h3>ROLL 24" x 28"</h3>
					        <div style="padding-top: 10px;">
					        	<a href="#" class="btn btn-primary" style="width: 100px;">Buy</a> 
					        </div>
					      </div>
					    </div>
					  </div>
					</div>

					<div style="margin-left: 43%;">
						<nav aria-label="Page navigation">
						  <ul class="pagination">
						    <li>
						      <a href="#" aria-label="Previous">
						        <span aria-hidden="true">&laquo;</span>
						      </a>
						    </li>
						    <li><a href="#">1</a></li>
						    <li><a href="#">2</a></li>
						    <li><a href="#">3</a></li>
						    <li><a href="#">4</a></li>
						    <li><a href="#">5</a></li>
						    <li>
						      <a href="#" aria-label="Next">
						        <span aria-hidden="true">&raquo;</span>
						      </a>
						    </li>
						  </ul>
						</nav>
					</div>

	          	</fieldset>
	          	<br>
	          	<button class="btn btn-lg btn-success pull-right" style="width: 150px;">Next</button>

			</div>
		</div>
	</section>
</div>



<?php $this->load->view('footer') ?>
