<!-- /*
@application  : Folder Explorer
@author       : Matthew Jodi
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/ -->
<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/order/order.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">

<div class="content-wrapper">
	<section class="content">
	    <div class="row">
	    	<div class="col-sm-12">
	    		<div class="box box-success">
		          	<div class="box-header with-border">
		              	<h3 class="box-title" id="title-top"><strong>Form Order</strong></h3>
		          	</div>

					<!-- <div class="nav-tabs-custom">

	                    <ul class="nav nav-tabs">
	                        <li class="active take-all-space-you-can">
	                            <a href="#tab-1" class="active" data-toggle="tab">Input Detail</a>
	                        </li>
	                        <li class="take-all-space-you-can">
	                            <a href="#tab-2" data-toggle="tab">Pick Item(s)</a>
	                        </li>
	                        <li class="take-all-space-you-can">
	                            <a href="#tab-3" data-toggle="tab">Summary</a>
	                        </li>
	                    </ul>
                	</div>
                    <div class="tab-content">
					    <div class="tab-pane active" id="tab-1">
					    	<div class="row">
		              			<div class="col-sm-6">
		              				<div class="form-group">
					                	<label for="kpd" class="col-sm-3 control-label">Kepada</label>
					                	<div class="col-sm-9">
					                    	<select class="form-control select2" name="kpd" id="kpd" style="width: 100%;" required="required">
						                    	<option value="0">Silahkan pilih</option>
					                    	</select>
					                  	</div>
					                </div>
		              				<div class="form-group">
					                	<label for="tujuan_inv" class="col-sm-3 control-label">Tujuan Investasi</label>
					                	<div class="col-sm-9">
					                    	<select class="form-control select2" name="tujuan_inv" id="tujuan_inv" style="width: 100%;" required="required">
					                    		<option value="0">Silahkan pilih</option>
					                    	</select>
					                    </div>
					                </div>
		              				<div class="form-group">
					                	<label for="perihal" class="col-sm-3 control-label">Perihal</label>
					                	<div class="col-sm-9">
					                    	<input type="text" class="form-control" name="perihal" id="perihal" placeholder="Perihal" required="required">
					                  	</div>
					                </div>
		              				<div class="form-group">
					                	<label for="pic_proj" class="col-sm-3 control-label">PIC Proyek</label>
					                	<div class="col-sm-9">
					                    	<select class="form-control select2" name="pic_proj" id="pic_proj" style="width: 100%;"  required="required">
					                  			<option value="0">Silahkan pilih</option>
					                  		</select>
					                  	</div>
					                </div>
		              			</div>
		              			<div class="col-sm-6">
		              				<div class="form-group">
					                	<label for="no_pi" class="col-sm-3 control-label">No. PI</label>
					                	<div class="col-sm-9">
					                    	<input type="text" class="form-control" name="no_pi" id="no_pi" placeholder="Masukkan No PI" value="" readonly="readonly" required="required">
					                  	</div>
					                </div>
		              				<div class="form-group">
					                	<label for="tanggal" class="col-sm-3 control-label">Tanggal</label>
					                	<div class="col-sm-9">
					                    	<div class="input-group date">
							                  	<input type="text" class="form-control datepicker" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>">
							                	<div class="input-group-addon">
							                		<i class="fa fa-calendar"></i>
							                	</div>
							                </div>
					                  	</div>
					                </div>
		              			</div>
		              		</div> <!--end row-->
		              		<!-- <div class="row pull-right">
			              		<div class="col-sm-12">
									<a href="#" class="btn btn-success" id="btn-next" style="width: 70px;">Next</a> 
								</div>
		              		</div>
					
					    </div>
					    <div class="tab-pane" id="tab-2">
					    	
					    </div>
					    <div class="tab-pane" id="tab-3">
					    	
					    </div>
                    </div> -->
                
                    <div class="box-body form-horizontal" id="box-awal">
	              		<div class="row">
	              			<div class="col-sm-6">
	              				<div class="form-group">
				                	<label for="kpd" class="col-sm-3 control-label">Kepada</label>
				                	<div class="col-sm-9">
				                    	<select class="form-control select2" name="kpd" id="kpd" style="width: 100%;" required="required">
					                    	<option value="0">Silahkan pilih</option>
				                    	</select>
				                  	</div>
				                </div>
	              				<div class="form-group">
				                	<label for="tujuan_inv" class="col-sm-3 control-label">Tujuan Investasi</label>
				                	<div class="col-sm-9">
				                    	<select class="form-control select2" name="tujuan_inv" id="tujuan_inv" style="width: 100%;" required="required">
				                    		<option value="0">Silahkan pilih</option>
				                    	</select>
				                    </div>
				                </div>
	              				<div class="form-group">
				                	<label for="perihal" class="col-sm-3 control-label">Perihal</label>
				                	<div class="col-sm-9">
				                    	<input type="text" class="form-control" name="perihal" id="perihal" placeholder="Perihal" required="required">
				                  	</div>
				                </div>
				                <div class="form-group">
				                	<label for="tujuan_inv" class="col-sm-3 control-label">Tipe PI</label>
				                	<div class="col-sm-9">
				                    	<select class="form-control select2" name="tujuan_inv" id="tujuan_inv" style="width: 100%;" required="required">
				                    		<option value="0">Silahkan pilih</option>
				                    		<option value="1">Budgeted</option>
				                    		<option value="2">Unbudgeted</option>
				                    	</select>
				                    </div>
				                </div>
	              			</div>
	              			<div class="col-sm-6">
	              				<div class="form-group">
				                	<label for="no_pi" class="col-sm-3 control-label">No. PI</label>
				                	<div class="col-sm-9">
				                    	<input type="text" class="form-control" name="no_pi" id="no_pi" placeholder="Masukkan No PI" value="" readonly="readonly" required="required">
				                  	</div>
				                </div>
	              				<div class="form-group">
				                	<label for="tanggal" class="col-sm-3 control-label">Tanggal</label>
				                	<div class="col-sm-9">
				                    	<div class="input-group date">
						                  	<input type="text" class="form-control datepicker" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>">
						                	<div class="input-group-addon">
						                		<i class="fa fa-calendar"></i>
						                	</div>
						                </div>
				                  	</div>
				                </div>
				                <div class="form-group">
				                	<label for="pic_proj" class="col-sm-3 control-label">PIC Proyek</label>
				                	<div class="col-sm-9">
				                    	<select class="form-control select2" name="pic_proj" id="pic_proj" style="width: 100%;"  required="required">
				                  			<option value="0">Silahkan pilih</option>
				                  		</select>
				                  	</div>
				                </div>
				                <div class="form-group">
				                	<label for="pic_proj" class="col-sm-3 control-label">Budget</label>
				                	<div class="col-sm-9">
				                    	<input type="text" class="form-control" name="perihal" id="perihal" placeholder="" required="required">
				                  	</div>
				                </div>
	              			</div>
	              		</div> <!--end row-->

	              		<div class="row pull-right">
		              		<div class="col-sm-12">
								<a href="#" class="btn btn-success" id="btn-next" style="width: 70px;">Next</a> 
							</div>
	              		</div>

	            	</div> <!--end box-body-->


		          	<div class="box-body hidden" id="box-akhir">
	              		<div class="row">
	              			<div class="col-sm-12">
	              				<div class="table-responsive">
		              				<table class="table table-hover table-form">
					            		<thead>
					            			<th class="text-center">NO</th>
					            			<th class="text-center">Item</th>
					            			<th class="text-center">Qty</th>
					            			<th class="text-center">Request Date</th>
					            			<th class="text-center">Delivery Date</th>
					            			<th class="text-center">Action</th>
					            		</thead>
					            		<tbody>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">1</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321 - Mesin A" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td class="text-center">
										        	<a href="#" class="btn btn-danger" style="width: 70px;">Remove</a> 
					            				</td>
					            			</tr>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">2</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321A - Mesin A Komponen 1" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td class="text-center">
										        	<a href="#" class="btn btn-danger" style="width: 70px;">Remove</a> 
					            				</td>
					            			</tr>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">3</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321B - Mesin A Komponen 2" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td class="text-center">
										        	<a href="#" class="btn btn-danger" style="width: 70px;">Remove</a> 
					            				</td>
					            			</tr>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">4</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321C - Mesin A Komponen 3" class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td class="text-center">
										        	<a href="#" class="btn btn-danger" style="width: 70px;">Remove</a> 
					            				</td>
					            			</tr>
					            			<tr class="input-table-row row1">
					            				<td class="text-center"><span class="form-control">5</span></td>
					            				<td>
					            					<input type="text" name="item[]" value="CODE321D - Mesin A Komponen 4 " class="text-center form-control col-sm-12" readonly="readonly">
					            				</td>
					            				<td>
					            					<input type="number" name="item[]" value="0" class="text-center form-control col-sm-12">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" readonly="readonly" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td>
					            					<input type="text" class="form-control datepicker text-right" id="tanggal" name="tanggal" required="required" value="<?php echo date("Y-m-d"); ?>">
					            				</td>
					            				<td class="text-center">
										        	<a href="#" class="btn btn-danger" style="width: 70px;">Remove</a> 
					            				</td>
					            			</tr>
					            		</tbody>
					            	</table>
				            	</div>
				            </div>
				        </div>
				        <div class="row pull-right">
		              		<div class="col-sm-12">
								<a href="#" class="btn btn-success" id="btn-prev2" style="width: 70px;">Prev</a> 
								<a href="#" class="btn btn-success" id="btn-submit" style="width: 70px;">Submit</a> 
							</div>
	              		</div>
				    </div><!--end box-body-->



					


	            	<!-- Katalog 1 -->
	            	<div class="box-body hidden" id="box-katalog-detail">
		              		<div class="row">
		              			<div class="col-sm-12">
		              				<div class="">

		              					<div class="form-group">
				              				<div class="row" style="padding-bottom: 20px; padding-top: 10px;">
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
		              					</div>

		              					<div class="form-group">
		              						<div class="row">
								      			<div class="col-md-12">
			              							<ol class="breadcrumb breadcrumb-right-arrow">
													  <li class="breadcrumb-item"><a href="#">...</a></li>
													  <li class="breadcrumb-item"><a href="#">Mesin A</a></li>
													  <li class="breadcrumb-item"><a href="#">Komponen A-3</a></li>
													  <li class="breadcrumb-item active">Komponen A-3-3</li>
													</ol>
								      			</div>
		              						</div>
		              					</div>

		              					<div class="form-group">
		              						<div class="row" style="border:1px solid black;">
		              							<div class="col-md-4 item-photo">
								                    <img style="max-width:100%;" src="https://ak1.ostkcdn.com/images/products/8818677/Samsung-Galaxy-S4-I337-16GB-AT-T-Unlocked-GSM-Android-Cell-Phone-85e3430e-6981-4252-a984-245862302c78_600.jpg" />
								                </div>
								                <div class="col-md-5" style="border:0px solid gray">
								                    <!-- Datos del vendedor y titulo del producto -->
								                    <h3>Samsung Galaxy S4 I337 16GB 4G LTE Unlocked GSM Android Cell Phone</h3>    
								                    <h5 style="color:#337ab7">vendido por <a href="#">Samsung</a> · <small style="color:#337ab7">(5054 ventas)</small></h5>
								        
								                    <!-- Precios -->
								                    <h6 class="title-price"><small>PRECIO OFERTA</small></h6>
								                    <h3 style="margin-top:0px;">U$S 399</h3>
								        
								                    <!-- Detalles especificos del producto -->
								                    <div class="section">
								                        <h6 class="title-attr" style="margin-top:15px;" ><small>COLOR</small></h6>                    
								                        <div>
								                            <div class="attr" style="width:25px;background:#5a5a5a;"></div>
								                            <div class="attr" style="width:25px;background:white;"></div>
								                        </div>
								                    </div>
								                    <div class="section" style="padding-bottom:5px;">
								                        <h6 class="title-attr"><small>CAPACIDAD</small></h6>                    
								                        <div>
								                            <div class="attr2">16 GB</div>
								                            <div class="attr2">32 GB</div>
								                        </div>
								                    </div>   
								                    <div class="section" style="padding-bottom:20px;">
								                        <h6 class="title-attr"><small>CANTIDAD</small></h6>                    
								                        <div>
								                            <div class="btn-minus"><span class="glyphicon glyphicon-minus"></span></div>
								                            <input value="1" />
								                            <div class="btn-plus"><span class="glyphicon glyphicon-plus"></span></div>
								                        </div>
								                    </div>                
								        
								                    <!-- Botones de compra -->
								                    <div class="section" style="padding-bottom:20px;">
								                        <button class="btn btn-success"><span style="margin-right:20px" class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> Agregar al carro</button>
								                        <h6><a href="#"><span class="glyphicon glyphicon-heart-empty" style="cursor:pointer;"></span> Agregar a lista de deseos</a></h6>
								                    </div>                                        
								                </div>
		              						</div>
		              					</div>

		              					<div class="form-group">
		              						<div class="row">
											  <div class="col-md-2 pop">
											    <div class="thumbnail" style="height: 300px;">
											      <img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.jpg" style="width: 100%;height: 150px;">
											      <div class="caption">
											        <h3>Komponen A</h3>
											        <div style="padding-top: 10px;">
											        	<a href="#" class="btn btn-primary" style="width: 70px;">Buy</a> 
											        	<a href="#" class="btn btn-default" role="button" style="width: 70px;">Details</a></p>
											        </div>
											      </div>
											    </div>
											  </div>
											
											  <div class="col-md-2 pop">
											    <div class="thumbnail" style="height: 300px;">
											      <img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.jpg" style="width: 100%;height: 150px;">
											      <div class="caption">
											        <h3>Komponen B</h3>
											        <div style="padding-top: 10px;">
											        	<a href="#" class="btn btn-primary" style="width: 70px;">Buy</a> 
											        </div>
											      </div>
											    </div>
											  </div>

											   <div class="col-md-2 pop">
											    <div class="thumbnail" style="height: 300px;">
											      <img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.jpg" style="width: 100%;height: 150px;">
											      <div class="caption">
											        <h3>Komponen C</h3>
											        <div style="padding-top: 10px;">
											        	<a href="#" class="btn btn-primary" style="width: 70px;">Buy</a> 
											        </div>
											      </div>
											    </div>
											  </div>

											  <div class="col-md-2 pop">
											    <div class="thumbnail" style="height: 300px;">
											      <img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.jpg" style="width: 100%;height: 150px;">
											      <div class="caption">
											        <h3>Komponen D</h3>
											        <div style="padding-top: 10px;">
											        	<a href="#" class="btn btn-primary" style="width: 70px;">Buy</a> 
											        </div>
											      </div>
											    </div>
											  </div>

											<div class="col-md-2 pop">
											    <div class="thumbnail" style="height: 300px;">
											      <img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.jpg" style="width: 100%;height: 150px;">
											      <div class="caption">
											        <h3>Komponen E</h3>
											        <div style="padding-top: 10px;">
											        	<a href="#" class="btn btn-primary" style="width: 70px;">Buy</a> 
											        </div>
											      </div>
											    </div>
											  </div>


											  <div class="col-md-2 pop">
											    <div class="thumbnail" style="height: 300px;">
											      <img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.jpg" style="width: 100%;height: 150px;">
											      <div class="caption">
											        <h3>Komponen F</h3>
											        <div style="padding-top: 10px;">
											        	<a href="#" class="btn btn-primary" style="width: 70px;">Buy</a> 
											        	<a href="#" class="btn btn-default" style="width: 70px;">Details</a> 
											        </div>
											      </div>
											    </div>
											  </div>
											</div>
		              					</div>

		              				</div>
		              			</div>
		              		</div>
		              		<div class="row pull-right">
			              		<div class="col-sm-12">
									<a href="#" class="btn btn-success" id="btn-prev2" style="width: 70px;">Prev</a> 
									<a href="#" class="btn btn-success" id="btn-rfq" style="width: 70px;">Next</a> 
								</div>
		              		</div>
		            	</div> <!--end box-body-->

		            	<!-- Katalog 2 -->
		            	<div class="box-body hidden" id="box-katalog">
		              		<div class="row">
		              			<div class="col-sm-12">
		              				
		              				<div class="">

		              					<div class="form-group">
				              				<div class="row" style="padding-bottom: 20px; padding-top: 10px;">
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
		              					</div>

		              					<div class="form-group">
		              						<div class="row">
								      			<div class="col-md-12">
			              							<ol class="breadcrumb breadcrumb-right-arrow">
													  <li class="breadcrumb-item active"><a href="#">Katalog</a></li>
		              						</div>
		              					</div>

		              					<div class="form-group">
		              						<div class="row">
											  
											  <div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
													<div id="product1" class="product-thumb transition">
														<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
														<div class="caption">
															<h4>Mesin</h4>
															<p>
															Intel Core 2 Duo processor
															Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
															<p>
															$602.00<br>
															<span>Price updated 18/11/2018</span>
															</p>
														</div>
														<div class="button-group">
															<button type="button" id="btn-req1"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">Request</span></button>
															<button type="button" class="hidden" id="btn-cancel1"><i class="fa fa-times"></i> <span class="hidden-xs hidden-sm hidden-md">cancel</span></button>
															<button type="button" id="btn-detail"><span >Details</span></button>
														</div>
													</div>
												</div>

												<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
													<div id="product2" class="product-thumb transition">
														<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
														<div class="caption">
															<h4>Mesin</h4>
															<p>
															Intel Core 2 Duo processor
															Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
															<p>
															$602.00<br>
															<span>Price updated 18/11/2018</span>
															</p>
														</div>
														<div class="button-group">
															<button type="button" id="btn-req2"><i class="fa fa-shopping-cart"></i> Request</button>
															<button type="button" class="hidden" id="btn-cancel2"><i class="fa fa-times"></i> cancel</button>
															<button type="button" id="btn-detail2"><span>Details</span></button>
														</div>
													</div>
												</div>

												<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
													<div id="product3" class="product-thumb transition selecteds">
														<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
														<div class="caption">
															<h4>Mesin</h4>
															<p>
															Intel Core 2 Duo processor
															Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
															<p>
															$602.00<br>
															<span>Price updated 18/11/2018</span>
															</p>
														</div>
														<div class="button-group">
															<button type="button" class="hidden" id="btn-req3"><i class="fa fa-shopping-cart"></i> Request</button>
															<button type="button" id="btn-cancel3"><i class="fa fa-times"></i> cancel</button>
															<button type="button" id="btn-detail3"><span >Details</span></button>
														</div>
													</div>
												</div>

												<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
													<div id="product4" class="product-thumb transition">
														<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
														<div class="caption">
															<h4>Mesin</h4>
															<p>
															Intel Core 2 Duo processor
															Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
															<p>
															$602.00<br>
															<span>Price updated 18/11/2018</span>
															</p>
														</div>
														<div class="button-group">
															<button type="button" id="btn-req4"><i class="fa fa-shopping-cart"></i> Request</button>
															<button type="button" class="hidden" id="btn-cancel4"><i class="fa fa-times"></i> cancel</button>
															<button type="button" id="btn-detail4"><span >Details</span></button>
														</div>
													</div>
												</div>


											</div>
		              					</div>

		              					<div class="form-group">
		              						<div class="row">

											  <div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
													<div id="product5" class="product-thumb transition">
														<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
														<div class="caption">
															<h4>Mesin</h4>
															<p>
															Intel Core 2 Duo processor
															Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
															<p>
															$602.00<br>
															<span>Price updated 18/11/2018</span>
															</p>
														</div>
														<div class="button-group">
															<button type="button" id="btn-req5"><i class="fa fa-shopping-cart"></i> Request</button>
															<button type="button" class="hidden" id="btn-cancel5"><i class="fa fa-times"></i> cancel</button>
															<button type="button" id="btn-detail5"><span >Details</span></button>
														</div>
													</div>
												</div>

												<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
													<div id="product6" class="product-thumb transition">
														<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
														<div class="caption">
															<h4>Mesin</h4>
															<p>
															Intel Core 2 Duo processor
															Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
															<p>
															$602.00<br>
															<span>Price updated 18/11/2018</span>
															</p>
														</div>
														<div class="button-group">
															<button type="button" id="btn-req6"><i class="fa fa-shopping-cart"></i> Request</button>
															<button type="button" class="hidden" id="btn-cancel6"><i class="fa fa-times"></i>cancel</button>
															<button type="button" id="btn-detail6"><span >Details</span></button>
														</div>
													</div>
												</div>

												<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
													<div id="product7" class="product-thumb transition">
														<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
														<div class="caption">
															<h4>Mesin</h4>
															<p>
															Intel Core 2 Duo processor
															Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
															<p>
															$602.00<br>
															<span>Price updated 18/11/2018</span>
															</p>
														</div>
														<div class="button-group">
															<button type="button" id="btn-req7"><i class="fa fa-shopping-cart"></i> Request</button>
															<button type="button" class="hidden" id="btn-cancel7"><i class="fa fa-times"></i> cancel</button>
															<button type="button" id="btn-detail7"><span >Details</span></button>
														</div>
													</div>
												</div>

												<div class="product-layout col-lg-3 col-md-3 col-sm-6 col-xs-12">
													<div id="product8" class="product-thumb transition">
														<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
														<div class="caption">
															<h4>Mesin</h4>
															<p>
															Intel Core 2 Duo processor
															Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
															<p>
															$602.00<br>
															<span>Price updated 18/11/2018</span>
															</p>
														</div>
														<div class="button-group">
															<button type="button" id="btn-req8"><i class="fa fa-shopping-cart"></i> Request</button>
															<button type="button" class="hidden" id="btn-cancel8"><i class="fa fa-times"></i> cancel</button>
															<button type="button" id="btn-detail8"><span >Details</span></button>
														</div>
													</div>
												</div>

											</div>
		              					</div>

		              					<div class="pull-right">
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
									</div>
		              				</div>
		              			</div>
		              		</div>
		              		<div class="row pull-right">
			              		<div class="col-sm-12">
									<a href="#" class="btn btn-success" id="btn-prev" style="width: 70px;">Prev</a> 
									<a href="#" class="btn btn-success" id="btn-lsg" style="width: 70px;">Next</a> 
								</div>
		              		</div>
		            	</div> <!--end box-body-->


		            	<!-- Modal -->
						<div class="modal fade" id="view_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog modal-lg" role="document">
						    	<div class="modal-content">
						    		<div class="modal-header">
						    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        		<h4 class="modal-title" id="view_modal_label" style="text-transform: capitalize">MESIN</h4>
						      		</div>
						      		<div id="modal1" class="modal-body selecteds">
							      		<div class="row" style="padding-bottom: 30px;">
								      		
							      			<div class="product-layout col-md-3">
							      				<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
							      			</div>
							      			<div class="product-layout col-md-8">
							      				<div class="row">
								      				<div class="caption">
														<h4>Mesin</h4>
														<p>
														Intel Core 2 Duo processor
														Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
														<p>
														$602.00<br>
														<span>Price updated 18/11/2018</span>
														</p>
													</div>
							      				</div>
							      				<div class="row product-thumb" style="width: 40%;">
							      					<div class="button-group">
														<button type="button" class="hidden" id="btn-req-modal"><i class="fa fa-shopping-cart"></i> Request</button>
														<button type="button" id="btn-cancel-modal"><i class="fa fa-times"></i> cancel</button>
														<button type="button" id="btn-comp-modal"><span >Component</span></button>
													</div>
							      				</div>
							      			</div>
							      			<div class="col-md-1"></div>
							      		</div>

							      		<div id="comp1" class="row hidden" style="padding: 30px 20px 0 20px; border-top: 1px solid #ddd;">
											<div class="col-md-12">
												<div id="Carousel" class="carousel slide">
                 
									                <ol class="carousel-indicators">
									                    <li data-target="#Carousel" data-slide-to="0" class="active"></li>
									                    <li data-target="#Carousel" data-slide-to="1"></li>
									                    <li data-target="#Carousel" data-slide-to="2"></li>
									                </ol>

									                <div class="carousel-inner">
									                	<div class="item active">
										                	<div class="row">
										                	  	<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
										                	</div><!--.row-->
										                </div><!--.item-->

										                <div class="item">
										                	<div class="row">
										                	  	<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
										                	</div><!--.row-->
										                </div><!--.item-->

										                <div class="item">
										                	<div class="row">
										                	  	<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
										                	</div><!--.row-->
										                </div><!--.item-->

									                </div><!--.carousel-inner-->
									             
									                <a data-slide="prev" href="#Carousel" class="left carousel-control">‹</a>
									                <a data-slide="next" href="#Carousel" class="right carousel-control">›</a>

									            </div>
											</div>
										</div>

						      		</div>
						    	</div>
						  	</div>
						</div>
				    	<!-- Modal -->

				    	<div class="modal fade" id="view_modal2" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog modal-lg" role="document">
						    	<div class="modal-content">
						    		<div class="modal-header">
						    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        		<h4 class="modal-title" id="view_modal_label" style="text-transform: capitalize">MESIN</h4>
						      		</div>
						      		<div id="modal2" class="modal-body">
							      		<div class="row" style="padding-bottom: 30px;">
								      		


							      			<!-- <div class="col-md-8 col-sm-12 co-xs-12 gal-item">
											   <div class="row h-50">
													  <div class="col-md-12 col-sm-12 co-xs-12 gal-item">
																<div class="box">
															 <img src="http://fakeimg.pl/758x370/" class="img-ht img-fluid rounded">
																</div>
														</div>
												</div>
										  
										    	<div class="row h-50">
													<div class="col-md-6 col-sm-6 co-xs-12 gal-item">
													  <div class="box">
														<img src="http://fakeimg.pl/748x177/" class="img-ht img-fluid rounded">
													  </div>
													</div>

													<div class="col-md-6 col-sm-6 co-xs-12 gal-item">
													 <div class="box">
														<img src="http://fakeimg.pl/371x370/" class="img-ht img-fluid rounded">
													 </div>
													</div>
									            </div>
									        </div> -->


							      			<div class="product-layout col-md-3">
							      				<div class="row">
							      					<div class="col-md-12 col-sm-12 co-xs-12">
							      						<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
							      					</div>
							      				</div>
							      				<div class="row">
							      					<div class="col-md-6 col-sm-6 co-xs-12">
							      						<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
							      					</div>
							      					<div class="col-md-6 col-sm-6 co-xs-12">
							      						<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
							      					</div>
							      				</div>
							      			</div>
							      			<div class="product-layout col-md-8">
							      				<div class="row">
								      				<div class="caption">
														<h4>Mesin</h4>
														<p>
														Intel Core 2 Duo processor
														Powered by an Intel Core 2 Duo processor at speeds up to 2.1..</p>
														<p>
														$602.00<br>
														<span>Price updated 18/11/2018</span>
														</p>
													</div>
							      				</div>
							      				<div class="row product-thumb" style="width: 40%;">
							      					<div class="button-group">
														<button type="button" class="hidden" id="btn-req-modal2"><i class="fa fa-shopping-cart"></i> Request</button>
														<button type="button" id="btn-cancel-modal2"><i class="fa fa-times"></i> cancel</button>
														<button type="button" id="btn-comp-modal2"><span >Component</span></button>
													</div>
							      				</div>
							      			</div>
							      			<div class="col-md-1"></div>

							      		</div>

							      		<div class="row" style="padding: 30px 20px 0 20px; border-top: 1px solid #ddd;">
											<div class="col-md-12">
												<div id="Carousel" class="carousel slide">
                 
									                <ol class="carousel-indicators">
									                    <li data-target="#Carousel" data-slide-to="0" class="active"></li>
									                    <li data-target="#Carousel" data-slide-to="1"></li>
									                    <li data-target="#Carousel" data-slide-to="2"></li>
									                </ol>

									                <div class="carousel-inner">
									                	<div class="item active">
										                	<div class="row">
										                	  	<div class="product-layout col-md-2">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-2">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-2">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-2">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-2">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-2">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View</span></button>
																		</div>
												      				</div>
												      			</div>
										                	</div><!--.row-->
										                </div><!--.item-->

										                <div class="item">
										                	<div class="row">
										                	  	<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
										                	</div><!--.row-->
										                </div><!--.item-->

										                <div class="item">
										                	<div class="row">
										                	  	<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
												      			<div class="product-layout col-md-3">
											      					<div class="image"><img src="<?php echo base_url(); ?>/assets/apps/img/test/dummy.png" alt="images" title="imgs" class="img-responsive"></div>
												      				<div class="product-thumb">
												      					<div class="button-group">
																			<button type="button" id="btn-comp" style="width: 100%;"><span>View Component</span></button>
																		</div>
												      				</div>
												      			</div>
										                	</div><!--.row-->
										                </div><!--.item-->

									                </div><!--.carousel-inner-->
									             
									                <a data-slide="prev" href="#Carousel" class="left carousel-control">‹</a>
									                <a data-slide="next" href="#Carousel" class="right carousel-control">›</a>

									            </div>
											</div>
										</div>
						      		</div>
						    	</div>
						  	</div>
						</div>
				    	<!-- Modal -->

	            </div>	
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/order/order.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>



