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
	            <div class="row">
					<div class="col-sm-6">
						<h3 class="box-title"><strong>DOKUMEN DIVISI</strong></h3><br><br>
					</div>
				</div>
				<div class="row">
					<div class='col-sm-6'>
						
						<code id="judul_atas">Path Folder : <em id="title-top">DOKUMEN DIVISI</em></code>
					</div>
					<div class='col-sm-6'>
					
			    
				<!-- =====version 1==== -->
			    <div class="btn-group pull-right pr">
				    <button id="information" class="btn btn-sm btn-success pull-right"><i class="fa fa-info-circle icons_white"></i> INFORMATION</button>
				</div>
			    <div class="btn-group pull-right pr">
			    	<button id='icon_upload' class="btn btn-sm btn-success pull-right hidden"><i class="fa fa-upload icons_white"></i> UPLOAD FILE</button>
			    </div>
			    <div class="btn-group pull-right pr">
			    	<button id='icon_new_folder' class="btn btn-sm btn-success pull-right hidden"><i class="fa fa-folder icons_white"></i> CREATE FOLDER</button>
			    </div>

			    <!-- =====version 2====
			    <div class="btn-group pull-right pr">
				    <button id="information" class="btn btn-sm btn-default pull-right"><i class="fa fa-info-circle icons_orange"></i> INFORMATION</button>
				</div>
			    <div class="btn-group pull-right pr">
			    	<button id='icon_upload' class="btn btn-sm btn-default pull-right hidden"><i class="fa fa-upload icons_orange"></i> UPLOAD FILE</button>
			    </div>
			    <div class="btn-group pull-right pr">
			    	<button id='icon_new_folder' class="btn btn-sm btn-default pull-right hidden"><i class="fa fa-folder icons_orange"></i> CREATE FOLDER</button>
			    </div> -->

			    <!-- =====version 3==== -->
			    <!-- <i title='Info' class="fa fa-info-circle icons_orange pull-right" id="information"></i>
			    <i title='Create New Folder' id='icon_new_folder' class='fa fa-folder hidden pull-right icons_orange'></i>
			    <i title='Upload Files' id='icon_upload' class='fa fa-upload hidden pull-right icons_orange'></i> -->
			    
			    <!-- VALIDASI ADD NEW ROOT FOLDER DAN SET ADMIN HANYA OLEH DIVISI IT -->
			    <?php 
			    	if ($nik == "8347" || $nik == "8944" || $nik == "7143" || $nik == "5649") {
						// echo $id_departemen;
			    		// echo "
			    		// 	<i title='Create New Root Folder' id='root_folder' class='fa fa-folder-open pull-right icons_red pr'></i>
			    		// 	<i title='Set Admin Folder' id='set_adminFolder' class='fa fa-gear pull-right icons_red'></i>
			    		// 	<i title='Rename Root Folder' id='rename_root_folder' class='fa fa-pencil pull-right icons_red'></i>
			    		// 	<i title='Delete Root Folder' id='delete_root_folder' class='fa fa-trash pull-right icons_red'></i>
			    		// ";
						
						// 		echo "
						// 			<div class='btn-group pull-right pr'>
						//    	<button id='ict_button' data-target='#ict_control' class='btn btn-sm btn-success pull-right'><i id='tanda' class='fa fa-cogs icons_white' style='padding-left:3px;'></i> SETTING ROOT</button>
						//    </div>
						// 			<br><br>
						// 			<div id='ict_control' class='pull-left'>
						//  			<div class='btn-group pull-left pr'>
						//     	<button id='root_folder' class='btn btn-sm btn-success pull-right'><i class='fa fa-folder-open icons_white'></i> CREATE ROOT FOLDER</button>
						//     </div>
						//     <div class='btn-group pull-left pr'>
						//     	<button id='set_adminFolder' class='btn btn-sm btn-success pull-right'><i class='fa fa-gear icons_white'></i> SET ADMIN</button>
						//     </div>
						//     <div class='btn-group pull-left pr'>
						//     	<button id='rename_root_folder' class='btn btn-sm btn-success pull-right'><i class='fa fa-pencil icons_white'></i> RENAME ROOT FOLDER</button>
						//     </div>
						//     <div class='btn-group pull-left pr'>
						//     	<button id='delete_root_folder' class='btn btn-sm btn-success pull-right'><i class='fa fa-trash icons_white'></i> DELETE ROOT FOLDER</button>
						//     </div>
						// </div>
						// 		";
						
			    		echo "
						
						    <div class='btn-group pull-right pr'>
							<button type='button' class='btn btn-sm btn-success'><i id='tanda' class='fa fa-cogs icons_white' style='padding-left:3px;'></i>SETTING ROOT</button>
				              <button type='button' class='btn btn-sm btn-success dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
							  <span class='caret'></span>
							  <span class='sr-only'>Toggle Dropdown</span>
				              </button>
				              <ul class='dropdown-menu' role='menu'>
							  <li id='root_folder'><a href='javascript:void(0)'><i class='fa fa-folder-open icons_green_km'></i>CREATE ROOT FOLDER</a></li>
							  <li id='set_adminFolder'><a href='javascript:void(0)'><i class='fa fa-gear icons_green_km'></i>SET ADMIN</a></li>
							  <li id='rename_root_folder'><a href='javascript:void(0)'><i class='fa fa-pencil icons_green_km'></i>RENAME ROOT FOLDER</a></li>
							  <li id='delete_root_folder'><a href='javascript:void(0)'><i class='fa fa-trash icons_green_km'></i>DELETE ROOT FOLDER</a></li>
				              </ul>
							  </div>
							  
							  ";
							}
							?>
					</div>
				</div>
			</div>
			<div class="box-body">
				<table id="folder" class="table table-bordered datatable-folder hover">
              		<thead>
		              	<th>Name</th>
		              	<th>Date</th>
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
		          					<label id="Upload_text" style="font-weight: 500;">Please Select Files to Upload Below</label><br>
		          					<input type="hidden" name="id_folder">
              						<input type="hidden" name="name">
		          					<input type="file" class="form-control" id="fileUpload" name="fileUpload[]" multiple="multiple" required="required">
		          					<label style="color: red; font-weight: 500;">*File format (.pdf | .docx | .doc | .xls | .xlsx | .swf | .vsd).</label>
		          					<label style="color: red; font-weight: 500;">*File name should only contain Alphabet, Number and Special Character<br> &nbsp (Dot . | Comma , | Dash - | Underscore _ ).</label> 
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


		<!-- Rename Root Folder Modal -->
		<div class="modal fade" id="rename_root_folder_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" id="setAdmin_title" style="text-transform: capitalize; font-weight: bold;">Rename Root Folder</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<div class="form-group">
      								<label>Root Folder</label>
                                    <select id="root_folder_name" name="root_folder_name"  class="form-control select2 col-sm-12">
                                    </select>
                                </div>
				      		</div>
			      		</div>
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<div class="form-group">
      								<label>Folder New Name</label>
		        					<input type="text" name="root_folder_rename" class="text-left form-control col-sm-12">
                                </div>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="button" class="btn btn-success" id="save_rename_root_folder" name="save_rename_root_folder">Submit</button>
		      		</div>
		    	</div>
		  	</div>
		</div>


		<!-- Delete Root Folder Modal -->
		<div class="modal fade" id="delete_root_folder_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		    		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        		<h4 class="modal-title" style="text-transform: capitalize; font-weight: bold;">Delete Root Folder</h4>
		      		</div>
		      		<div class="modal-body">
			      		<div class="row">
				      		<div class="col-sm-12">
		          				<div class="form-group">
      								<label>Root Folder</label>
                                    <select id="root_folder_names" name="root_folder_names"  class="form-control select2 col-sm-12">
                                    </select>
                                    <label class="hidden" id="confirm_label" style="color: red; font-weight: 500;">This Folder is not empty, Delete this folder?</label>
                                </div>
				      		</div>
			      		</div>
		      		</div>
		      		<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        		<button type="button" class="btn btn-success" id="submit_delete_root_folder" name="submit_delete_root_folder">Delete</button>
		      		</div>
		    	</div>
		  	</div>
		</div>



		<!-- Right Click Context Menu -->
		<ul class="menu" id="menu" style="display: none">
			<li class="menu-item">
		        <button type="button" id="openFile" class="menu-btn">
		        	<i class="fa fa-file"></i>
		            <span class="menu-text">Open</span>
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
<script src="<?php echo base_url() ?>assets/apps/js/folder/folder.js"></script>
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

