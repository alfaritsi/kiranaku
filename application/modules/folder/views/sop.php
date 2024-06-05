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


<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/folder/sop.css">

<div class="content-wrapper">
	<section class="content">
		<div class="box box-success">
			<div class="box-header">
	            <h3 class="box-title" id="title-top"><strong>STANDARD OPERATING PROCEDURE</strong></h3>
			    <div class="btn-group pull-right pr">
				    <button id="information" class="btn btn-sm btn-success pull-right"><i class="fa fa-info-circle icons_white"></i> INFORMATION</button>
				</div>
			    <div class="btn-group pull-right pr">
			    	<button id='icon_upload' class="btn btn-sm btn-success pull-right hidden"><i class="fa fa-upload icons_white"></i> UPLOAD FILE</button>
			    </div>
			    <div class="btn-group pull-right pr">
			    	<button id='icon_new_folder' class="btn btn-sm btn-success pull-right hidden"><i class="fa fa-folder icons_white"></i> CREATE FOLDER</button>
			    </div>
			    <!-- VALIDASI ADD NEW ROOT FOLDER DAN SET ADMIN HANYA OLEH DIVISI IT -->
			    <?php 
			    	
			    	if ($nik == "8347" || $nik == "8944" || $nik == "7143" || $nik == "5649") {
			    		// <i title='Create New Root Folder' id='root_folder' class='fa fa-folder-open' style='color: #008d4c;'></i>
			    		echo "
			    			<div class='btn-group pull-right pr'>
				              <button type='button' class='btn btn-sm btn-success'><i id='tanda' class='fa fa-cogs icons_white' style='padding-left:3px;'></i>SETTING SOP</button>
				              <button type='button' class='btn btn-sm btn-success dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
				                <span class='caret'></span>
				                <span class='sr-only'>Toggle Dropdown</span>
				              </button>
				              <ul class='dropdown-menu' role='menu'>
				                <li id='set_adminFolder'><a href='javascript:void(0)'><i class='fa fa-gear icons_green_km'></i>SETTING ADMIN</a></li>
				                <li id='set_aksessop'><a href='javascript:void(0)'><i class='fa fa-cogs icons_green_km'></i>SETTING AKSES</a></li>
				              </ul>
				            </div>
			    		";
			    	}
			     ?>
			</div>
			<div class="box-body">
				<table id="folder" class="table table-bordered datatable-folder hover">
              		<thead>
		              	<th>Name</th>
		              	<th>Upload Date</th>
		              	<th>Modified Date</th>
		              	<th>Type</th>
		              	<th>Size</th>
		              	<th style="display: none;">sort</th>

		            </thead>
	              	<tbody id="tbod">
	              	</tbody>
	            </table>
			</div>
		</div>

		<!-- Root New Folder Modal -->
		<div class="modal fade" id="root_folder_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="comment_modal_label" style="font-weight: bold;">CREATE NEW FOLDER</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<div class="form-group">
		          					<label>Folder Name</label>
		        					<input type="text" name="root_folder_name" class="text-left form-control col-sm-12" >
		          					
		          				</div>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="submit" class="btn btn-success" id="save_root_folder" >Create</button>
		      		</div>
		    	</div>
		  	</div>
		</div>

		<!-- Rename Modal -->
		<div class="modal fade" id="rename_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="rename_title" style="font-weight: bold;"><b>RENAME FOLDER</b></h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
				      			<div class="form-group">
		          					<label>Current Name</label>
		        					<input type="text" name="old_name" id="old_name" class="text-left form-control col-sm-12" readonly="readonly">		          					
		          				</div>
		          				<div class="form-group">
		          					<label>New Name</label>
		        					<input type="text" name="rename" class="text-left form-control col-sm-12">
		          				</div>
		          					<label id="notes" class="hidden" style="color: red; font-weight: 500;">*File name should only contain Alphabet, Number and Special Character <br> &nbsp (Dot . | Comma , | Dash - | Underscore _ ).</label> 
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="submit" class="btn btn-success" id="save_rename">Rename</button>
		      		</div>
		    	</div>
		  	</div>
		</div>

		<!-- New Folder Modal -->
		<div class="modal fade" id="new_folder_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="comment_modal_label" style="font-weight: bold;">CREATE NEW FOLDER</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<div class="form-group">
		          					<label>Folder Name</label>
		        					<input type="text" name="folder_name" class="text-left form-control col-sm-12">
		          				</div>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="submit" class="btn btn-success" id="save_new_folder">Create</button>
		      		</div>
		    	</div>
		  	</div>
		</div>

		<!-- Toolbar New Folder Modal -->
		<div class="modal fade" id="toolbar_new_folder_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="comment_modal_labels" style="font-weight: bold;">CREATE NEW FOLDER</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<div class="form-group">
		          					<label>Folder Name</label>
		        					<input type="text" name="folder_names" class="text-left form-control col-sm-12">
		        					<input type="hidden" name="ids">
		          				</div>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="submit" class="btn btn-success" id="save_toolbar_new_folder">Create</button>
		      		</div>
		    	</div>
		  	</div>
		</div>

		<!-- Delete Modal -->
		<div class="modal fade" id="delete_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="delete_title" style="text-transform: capitalize; font-weight: bold;">Delete Folder Confirmation</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<div class="form-group">
		          					<label id="del_text" style="font-weight: 500;">Delete this Folder?</label><br>		          		
		          					<!-- <label id="del_subtext" style="color: red; font-weight: 500;" >*Seluruh Sub-folder dan file dalam Folder ini akan terhapus.</label>	 -->	          					
		          				</div>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="button" class="btn btn-danger" id="submit_delete">Delete</button>
		      		</div>
		    	</div>
		  	</div>
		</div>

		<!-- Upload Modal -->
		<div class="modal fade" id="upload_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		          	<form role="form" id="form-upload" enctype="multipart/form-data">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="upload_title" style="text-transform: capitalize; font-weight: bold;">UPLOAD FILE</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
								<div class="form-group">
		          					<label>Sosialisasi</label><br>		          					
		          					<select id="sosialisasi" name="sosialisasi" class="sosialisasi form-control select2 col-sm-12">
										<!-- <option value="All">Sosialisasi HO dan Pabrik</option> -->
										<option value="ho">Sosialisasi HO</option>
										<option value="pabrik">Sosialisasi Pabrik</option>
										<option value="none">Tanpa Sosialisasi</option>
									</select>
		          				</div>

								<div class="form-group">
		          					<label>Catatan kaki</label><br>		          					
		          					<textarea name="catatan_kaki" class="form-control col-sm-12" readonly required="required" ></textarea>
		          				</div>
		          				<div class="form-group">
		          					<label id="Upload_text" style="margin-top: 10px;">Please Select Files to Upload Below</label><br>
		          					<input type="hidden" name="id_folder">
              						<input type="hidden" name="name">
		          					<input type="file" class="form-control" id="fileUpload" name="fileUpload[]" multiple="multiple" required="required">
		          					<label style="color: red; font-weight: 500;">*File format (.pdf | .docx | .doc | .xls | .xlsx | .swf | .vsd).</label>
		          					<label style="color: red; font-weight: 500;">*File name should only contain Alphabet, Number and Special Character <br> &nbsp (Dot . | Comma , | Dash - | Underscore _ ).</label> 
		          					<div class="form-group" id="file_list"></div>
		          				</div>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="submit" class="btn btn-success" name="submit_upload" id="submit_upload">Submit</button>
		      		</div>
		      		</form>
		    	</div>
		  	</div>
		</div>

		<!-- Reupload Modal -->
		<div class="modal fade" id="reupload_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		          	<form role="form" id="form-reupload" enctype="multipart/form-data">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="upload_title" style="text-transform: capitalize; font-weight: bold;">RE-UPLOAD FILE</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
							  	<div class="form-group">
		          					<label style="margin-top: 5px;">Current File</label>
		        					<input type="text" name="current_file" id="current_file" class="text-left form-control col-sm-12" readonly="readonly">		          					
		          				</div>
								<div class="form-group">
		          					<label style="margin-top: 5px;">Sosialisasi</label><br>		          					
		          					<input type="text" name="reupload_sosialisasi" id="reupload_sosialisasi" class="text-left form-control col-sm-12" readonly="readonly">		          					
		          				</div>

								<div class="form-group">
		          					<label style="margin-top: 5px;">Catatan kaki</label><br>		          					
		          					<textarea name="reupload_catatan_kaki" class="form-control col-sm-12" readonly></textarea>
		          				</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
		          					<label style="margin-top: 5px;">New File</label><br>
		          					<input type="hidden" name="id_file">
              						<input type="hidden" name="name">
		          					<input type="file" class="form-control" id="fileReupload" name="fileReupload[]" required="required">
		          					
		          				</div>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="submit" class="btn btn-success" name="submit_reupload" id="submit_reupload">Submit</button>
		      		</div>
		      		</form>
		    	</div>
		  	</div>
		</div>

		<!-- Setting Modal -->
		<div class="modal fade" id="setting_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		          	<form role="form" id="form-set">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="setting_title" style="text-transform: capitalize; font-weight: bold;">SET FOLDER ACCESS</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<fieldset class="fieldset-success">
		          					<legend class="text-center">Write Access</legend>
		          					<div class="row">
		          						<div class="col-sm-12">
				          					<div class="form-group">
		          								<label>Division</label>
		          								<div class="checkbox pull-right select_all" style="margin:0;">
						                			<label><input type="checkbox" class="isSelectAll_divisionWrite"> Select All</label>
						                		</div>
		                						<select class="form-control select2 col-sm-12" multiple="multiple" name="divisi_write[]" id="divisi_write">
						                			<?php
						                				foreach ($divisis as $divisi) {
			                                                echo "<option value='$divisi->id_divisi' id='$divisi->id_divisi'>$divisi->nama [$divisi->id_divisi]</option>";
			                                            }
						                			?>
						                		</select>
		                                    </div>
		          						</div>
		          					</div>
		          					<div class="row">
		          						<div class="col-md-12">
				          					<div class="form-group">
		          								<label>Department</label>
		          								<div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
						                			<label><input type="checkbox" class="isSelectAll_departmentWrite"> Select All</label>
						                		</div>
		                                        <select id="department_write" name="department_write[]" multiple="multiple" class="form-control select2 col-sm-12">
		                                        </select>
		                                    </div>
		          						</div>
		          					</div>
		          					<div class="row">
		          						<div class="col-md-12">
				          					<div class="form-group">
		          								<label>Level</label>
		          								<div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
						                			<label><input type="checkbox" class="isSelectAll_levelwrite"> Select All</label>
						                		</div>
		                                        <select id="level_write" name="level_write[]" multiple="multiple" class="form-control select2 col-sm-12">
		                                        	<?php
		                                        	// echo $level;
						                				foreach ($level as $lev) {
			                                                echo "<option value='$lev->id_level'>$lev->nama</option>";
			                                            }
						                			?>
		                                        </select>
		                                    </div>
		          						</div>
		          					</div>
		          				</fieldset>
		          				<fieldset class="fieldset-success">
		          					<legend class="text-center">Read Access</legend>
		          					<div class="row">
		          						<div class="col-sm-12">
				          					<div class="form-group">
		          								<label>Division</label>
		          								<div class="checkbox pull-right select_all" style="margin:0;">
						                			<label><input type="checkbox" class="isSelectAll_divisionRead"> Select All</label>
						                		</div>
		                						<select class="form-control select2 col-sm-12" multiple="multiple" name="divisi_read[]" id="divisi_read">
						                			<?php
						                				foreach ($divisis as $divisi) {
			                                                echo "<option value='$divisi->id_divisi' id='$divisi->id_divisi'>$divisi->nama [$divisi->id_divisi]</option>";
			                                            }
						                			?>
						                		</select>
		                                    </div>
		          						</div>
		          					</div>
		          					<div class="row">
		          						<div class="col-md-12">
				          					<div class="form-group">
		          								<label>Department</label>
		          								<div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
						                			<label><input type="checkbox" class="isSelectAll_departmentRead"> Select All</label>
						                		</div>
		                                        <select id="department_read" name="department_read[]" multiple="multiple" class="form-control select2 col-sm-12">
		                                        </select>
		                                    </div>
		          						</div>
		          					</div>
		          					<div class="row">
		          						<div class="col-md-12">
				          					<div class="form-group">
		          								<label>Level</label>
		          								<div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
						                			<label><input type="checkbox" class="isSelectAll_levelakses"> Select All</label>
						                		</div>
		                                        <select id="level_akses" name="level_akses[]" multiple="multiple" class="form-control select2 col-sm-12">
		                                        	<?php
						                				foreach ($level as $lev) {
			                                                echo "<option value='$lev->id_level'>$lev->nama</option>";
			                                            }
						                			?>
		                                        </select>
		                                    </div>
		          						</div>
		          					</div>     
                                </fieldset>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="button" class="btn btn-success" name="submit_setting" id="submit_setting">Submit</button>
		      		</div>
		      	</form>
		    	</div>
		  	</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="view_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-lg" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		    			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="view_modal_label" style="text-transform: capitalize">FILE</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
				      			<div id="show_file"></div>
				      		</div>
			      		</div>
		      		</div>
		    	</div>
		  	</div>
		</div>
    	<!-- Modal -->

		<!-- Set Admin Modal -->
		<div class="modal fade" id="setAdmin_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="setAdmin_title" style="text-transform: capitalize; font-weight: bold;">SET FOLDER ADMIN</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<div class="form-group">
      								<label>Root Folder</label>
                                    <select id="root_folder_list" name="root_folder_list"  class="form-control select2 col-sm-12">
                                    </select>
                                </div>
				      		</div>
			      		</div>
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<div class="form-group">
      								<label>Folder Admin</label>
                                    <select id="folder_admin" name="folder_admin[]" multiple="multiple" class="form-control select2-user-search col-sm-12"></select>
                                </div>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="button" class="btn btn-success" name="submit_setAdmin">Submit</button>
		      		</div>
		    	</div>
		  	</div>
		</div>



		<!-- Setting Modal SOP Folder -->
		<div class="modal fade" id="setting_modal2" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		          	<form role="form" id="form-set2">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="setting_title" style="text-transform: capitalize; font-weight: bold;">SET FOLDER ACCESS</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<fieldset class="fieldset-success">
		          					<legend class="text-center">Write Access</legend>
		          					<div class="row">
		          						<div class="col-sm-12">
				          					<div class="form-group">
		          								<label>Division</label>
		          								<div class="checkbox pull-right select_all" style="margin:0;">
						                			<label><input type="checkbox" class="isSelectAll_divisionWrite2"> Select All</label>
						                		</div>
		                						<select class="form-control select2 col-sm-12" multiple="multiple" name="divisi_write2[]" id="divisi_write2">
						                			<?php
						                				foreach ($divisis as $divisi) {
			                                                echo "<option value='$divisi->id_divisi' id='$divisi->id_divisi'>$divisi->nama [$divisi->id_divisi]</option>";
			                                            }
						                			?>
						                		</select>
		                                    </div>
		          						</div>
		          					</div>
		          					<div class="row">
		          						<div class="col-md-12">
				          					<div class="form-group">
		          								<label>Department</label>
		          								<div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
						                			<label><input type="checkbox" class="isSelectAll_departmentWrite2"> Select All</label>
						                		</div>
		                                        <select id="department_write2" name="department_write2[]" multiple="multiple" class="form-control select2 col-sm-12">
		                                        </select>
		                                    </div>
		          						</div>
		          					</div>
		          					<div class="row">
		          						<div class="col-md-12">
				          					<div class="form-group">
		          								<label>Level</label>
		          								<div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
						                			<label><input type="checkbox" class="isSelectAll_levelwrite2"> Select All</label>
						                		</div>
		                                        <select id="level_write2" name="level_write2[]" multiple="multiple" class="form-control select2 col-sm-12">
		                                        	<?php
						                				foreach ($level as $lev) {
			                                                echo "<option value='$lev->id_level'>$lev->nama</option>";
			                                            }
						                			?>
		                                        </select>
		                                    </div>
		          						</div>
		          					</div>
		          				</fieldset>
		          				<fieldset class="fieldset-success">
		          					<legend class="text-center">Read Access</legend>
		          					<div class="row">
		          						<div class="col-sm-12">
				          					<div class="form-group">
		          								<label>Division</label>
		          								<div class="checkbox pull-right select_all" style="margin:0;">
						                			<label><input type="checkbox" class="isSelectAll_divisionRead2"> Select All</label>
						                		</div>
		                						<select class="form-control select2 col-sm-12" multiple="multiple" name="divisi_read2[]" id="divisi_read2">
						                			<?php
						                				foreach ($divisis as $divisi) {
			                                                echo "<option value='$divisi->id_divisi' id='$divisi->id_divisi'>$divisi->nama [$divisi->id_divisi]</option>";
			                                            }
						                			?>
						                		</select>
		                                    </div>
		          						</div>
		          					</div>
		          					<div class="row">
		          						<div class="col-md-12">
				          					<div class="form-group">
		          								<label>Department</label>
		          								<div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
						                			<label><input type="checkbox" class="isSelectAll_departmentRead2"> Select All</label>
						                		</div>
		                                        <select id="department_read2" name="department_read2[]" multiple="multiple" class="form-control select2 col-sm-12">
		                                        </select>
		                                    </div>
		          						</div>
		          					</div>
		          					<div class="row">
		          						<div class="col-md-12">
				          					<div class="form-group">
		          								<label>Level</label>
		          								<div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
						                			<label><input type="checkbox" class="isSelectAll_levelakses2"> Select All</label>
						                		</div>
		                                        <select id="level_akses2" name="level_akses2[]" multiple="multiple" class="form-control select2 col-sm-12">
		                                        	<?php
						                				foreach ($level as $lev) {
			                                                echo "<option value='$lev->id_level'>$lev->nama</option>";
			                                            }
						                			?>
		                                        </select>
		                                    </div>
		          						</div>
		          					</div>     
                                </fieldset>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="button" class="btn btn-success" name="submit_setting2" id="submit_setting2">Submit</button>
		      		</div>
		      	</form>
		    	</div>
		  	</div>
		</div>
		<!-- end -->


		<!-- Right Click Context Menu -->
		<ul class="menu" id="menu" style="display: none">
			<li class="menu-item">
		        <button type="button" id="openFile" class="menu-btn">
		        	<i class="fa fa-file"></i>
		            <span class="menu-text">Open</span>
		        </button>
		    </li>
			<li class="menu-item">
		        <button type="button" id="reupload" class="menu-btn">
		        	<i class="fa fa-upload"></i>
		            <span class="menu-text">Reupload</span>
		        </button>
		    </li>
		    <li class="menu-item">
		        <button type="button" id="rename" class="menu-btn">
		        	<i class="fa fa-pencil"></i>
		            <span class="menu-text">Rename</span>
		        </button>
		    </li>
		    <li class="menu-item">
		        <button type="button" id="downloads" class="menu-btn">
		        	<i class="fa fa-download"></i>
		            <span class="menu-text">Download</span>
		        </button>
		    </li>
		    <li class="menu-item">
		        <button type="button" id="new_folder" class="menu-btn">
		        	<i class="fa fa-folder"></i>
		            <span class="menu-text">New Folder</span>
		        </button>
		    </li>
		    <li class="menu-item">
		        <button type="button" id="delete" class="menu-btn">
		            <i class="fa fa-trash"></i>
		            <span class="menu-text">Delete</span>
		        </button>
		    </li>
		    <li class="menu-item">
		        <button type="button" id="upload" class="menu-btn">
		            <i class="fa fa-upload"></i>
		            <span class="menu-text">Upload</span>
		        </button>
		    </li>
		    <li class="menu-item">
		        <button type="button" id="setting" class="menu-btn">
		            <i class="fa fa-gear"></i>
		            <span class="menu-text">Setting</span>
		        </button>
		    </li>
		</ul>
	</section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/folder/sop.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>

<!-- Multiselect References -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.custom.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.theme.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/prettify.css"/>

<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/prettify.js"></script>
<style>
td{
	-webkit-user-select: none; /* webkit (safari, chrome) browsers */
    -moz-user-select: none; /* mozilla browsers */
    -khtml-user-select: none; /* webkit (konqueror) browsers */
    -ms-user-select: none; /* IE10+ */
}
</style>

