<!--
/*
@application  : Email Routing
@author       : Akhmad Syaiful Yamang (8347)
@contributor  :
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->
<?php $this->load->view('header') ?>

<!-- Bootstrap treefy -->
<link rel="stylesheet"
	  href="<?php echo base_url() ?>assets/plugins/treetable/css/bootstrap-treefy.css">

<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-sm-8">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
					</div>
					<div class="box-body">
						<table class="table tableTree table-bordered my-datatable-extends-order">
							<thead>
							<tr>
								<th>Nama Menu</th>
								<th>URL</th>
								<th>Tooltips</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
							</thead>
							<tbody id="menu_wrapper">
							<?php
								if ($menu) {
									$output = "";
									foreach ($menu as $m) {
										$tooltips = "";
										if ($m->tooltips !== null) {
											$tooltips .= '<a href="#collapse' . $m->urut . '" class="collapsible-toogle" data-urut="' . $m->urut . '" data-toggle="collapse" data-parent="#accordion" aria-expanded="false">Show</a>';
											$tooltips .= '<div id="collapse' . $m->urut . '" class="panel-collapse collapse">' . $m->tooltips . '</div>';
										}
										$pnode  = ($m->parent_urut == 0 ? '' : 'data-pnode="treetable-parent-' . $m->parent_urut . '"');
										$output .= '<tr data-node="treetable-' . $m->urut . '"  ' . $pnode . '>';
										$output .= '<td width="40%"><span class="menu-content">' . $m->nama_menu . '</span></td>';
										$output .= '<td>' . ($m->link == null ? '' : $m->link) . '</td>';
										$output .= '<td width="20%">' . $tooltips . '</td>';
										$output .= '<td width="20%">' . ($m->is_active == 1 ? '<i title="active" class="fa fa-check-square"></i>' : '<i title="not active" class="fa fa-minus-square"></i>') . '</td>';
										$output .= '<td>';
										$output .= '	<div class="input-group-btn">';
										$output .= '		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
										$output .= '		<ul class="dropdown-menu pull-right">';
										$output .= '			<li><a href="javascript:void(0)" class="edit" data-edit="'.$generate->kirana_encrypt($m->id_menu).'"><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
										if($m->is_active == 1){
											$output .= '			<li><a href="javascript:void(0)" class="delete" data-delete="'.$generate->kirana_encrypt($m->id_menu).'"><i class="fa fa-minus-square-o"></i> Set Not Active</a></li>';
										}else{
											$output .= '			<li><a href="javascript:void(0)" class="activate" data-delete="'.$generate->kirana_encrypt($m->id_menu).'"><i class="fa fa-check-square-o"></i> Set Active</a></li>';
										}
										$output .= '		</ul>';
										$output .= '	</div>';
										$output .= '</td>';
										$output .= '</tr>';
									}
									echo $output;
								}
							?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="form-title"><strong>Setting <?php echo $title; ?></strong></h3>
					</div>
					<form role="form"
						  class="form-menu-kiranalytics">
						<div class="box-body">
							<div class="form-group">
								<label for="formula">Nama Menu</label>
								<input type="text"
									   name="nama_menu"
									   id="nama_menu"
									   class="form-control"
									   required="required"
									   placeholder="Masukkan Nama Menu"
									   autocomplete="off">
							</div>
							<div class="form-group">
								<label for="formula">Parent</label>
								<select name="parent_id"
										id="parent_id"
										class="form-control select2">
									<?php
										if ($menu) {
											$output = "<option value='0'>ROOT</option>";
											foreach ($menu as $m) {
												$output .= '<option value="'.$m->id_menu.'">'.$m->nama_menu.'</option>';
											}
											echo $output;
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="formula">Link</label>
								<input type="text"
									   name="link"
									   id="link"
									   class="form-control"
									   required="required"
									   placeholder="Masukkan Link"
									   autocomplete="off">
							</div>
							<div class="form-group">
								<label for="formula">Tooltips</label>
								<input type="text"
									   name="tooltips"
									   id="tooltips"
									   class="form-control"
									   placeholder="Masukkan Tooltips"
									   autocomplete="off">
							</div>
						</div>
		            	<div class="box-footer">
		            		<input type="hidden" name="id_menu" id="id_menu">
		              		<button type="Submit" name="action_btn" class="btn btn-success">Submit</button>
						</div>
					</form>
					<div class="box-body">
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php $this->load->view('footer') ?>
<!--<script src="-->
<?php //echo base_url() ?><!--assets/apps/js/pcs/menu.js"></script>-->
<!-- Bootstrap treefy -->
<script type="application/javascript" src="<?php echo base_url() . 'assets/plugins/treetable/bootstrap-treefy.js' ?>"></script>
<script src="<?php echo base_url() ?>assets/apps/js/routing/menu.js"></script>

<script>
	$(document).ready(function () {
		// $.ajax({
		// 	url: baseURL + "routing/setting/get_data/menu_kiranalytics",
		// 	type: 'POST',
		// 	dataType: 'JSON',
		// 	data: {
		// 		id_menu: 0
		// 	},
		// 	success: function (data) {
		// 		console.log(data);
		// 		var output = "";
		// 		var output_select = "";
		// 		if (data) {
		// 			$.each(data, function (i, v) {
		// 				var tooltips = "";
		// 				if (v.tooltips !== null) {
		// 					tooltips += '<a href="#collapse' + v.urut + '" class="collapsible-toogle" data-urut="' + v.urut + '" data-toggle="collapse" data-parent="#accordion" aria-expanded="false">Show</a>';
		// 					tooltips += '<div id="collapse' + v.urut + '" class="panel-collapse collapse">' + v.tooltips + '</div>';
		// 				}
		// 				var pnode = (v.parent_urut == 0 ? '' : 'data-pnode="treetable-parent-' + v.parent_urut + '"');
		// 				output += '<tr data-node="treetable-' + v.urut + '"  ' + pnode + '>';
		// 				output += '<td width="40%"><span class="menu-content">' + v.nama_menu + '</span></td>';
		// 				output += '<td>' + (v.link == null ? '' : v.link) + '</td>';
		// 				output += '<td width="20%">' + tooltips + '</td>';
		// 				output += '<td width="20%">' + (v.is_active == 1 ? '<i title="active" class="fa fa-check-square"></i>' : '<i title="not active" class="fa fa-minus-square"></i>') + '</td>';
		// 				output += '<td>';
		// 				output += '	<div class="input-group-btn">';
		// 				output += '		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action <span class="fa fa-caret-down"></span></button>';
		// 				output += '		<ul class="dropdown-menu pull-right">';
		// 				output += '			<li><a href="javascript:void(0)" class="edit" data-edit=""><i class="fa fa-pencil-square-o"></i> Edit</a></li>';
		// 				output += '			<li><a href="javascript:void(0)" class="delete" data-delete=""><i class="fa fa-trash-o"></i> Hapus</a></li>';
		// 				output += '		</ul>';
		// 				output += '	</div>';
		// 				output += '</td>';
		// 				output += '</tr>';
		//
		//
		// 				output_select	= '<option value="'+v.+'"></option>';
		// 			});
		// 			$(".tableTree tbody#menu_wrapper").html(output);
		// 		}
		// 	},
		// 	complete: function () {
		// 		$(".tableTree").treeFy({
		// 			initStatusClass: 'treetable-collapsed',
		// 			treeColumn: 0,
		// 			expanderExpandedClass: 'fa fa-folder-open',
		// 			expanderCollapsedClass: 'fa fa-folder'
		// 		});
		// 	}
		// });

		$(".tableTree").treeFy({
			initStatusClass: 'treetable-collapsed',
			treeColumn: 0,
			expanderExpandedClass: 'fa fa-folder-open',
			expanderCollapsedClass: 'fa fa-folder'
		});

		$(document).on("click", ".collapsible-toogle", function (e) {
			if ($(this).attr("aria-expanded") === "true") {
				$(this).html("Hide");
			} else {
				$(this).html("Show");
			}
		});

		$(document).on("click", ".edit", function(){

		});
	});

</script>
<style>
	.treetable-indent,
	.treetable-expander,
	.menu-content
	{
		width: 10%;
		float: left;
	}

	.menu-content{
		width: inherit;
	}
</style>
