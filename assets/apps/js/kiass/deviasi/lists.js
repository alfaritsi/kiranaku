$(document).ready(function () {

	$('.my-datatable-extends-order').DataTable({
		ordering: true,
		columnDefs: [
			{"type": 'date-eu',"className": "text-center", "targets": 3},
		],
		order: [2, 'desc'],
	});
	$(document).on("click", "#delete_pp", function (e) {
		$('#KiranaModals .modal-title').css("text-transform", "capitalize");
		$('#KiranaModals .modal-title').html("Delete Deviasi Pengajuan Penjualan");
		var no_pp = $("input[name='no_deviasi']").val();
		var required = "required";

		$("#KiranaModals").removeAttr("class");
		$("#KiranaModals").addClass("modal");
		$("#KiranaModals").addClass("modal-danger");

		var output = '';
		output += '<form class="form-delete-scrap" enctype="multipart/form-data">';
		output += '	<div class="modal-body">';
		output += '		<div class="form-horizontal">';
		output += '			<div class="form-group">';
		output += '				<label for="alasan" class="col-sm-12 control-label text-left">Alasan</label>';
		output += '				<div class="col-sm-12">';
		output += '					<textarea class="form-control" name="alasan" ' + required + '></textarea>';
		output += '				</div>';
		output += '			</div>';
		output += '		</div>';
		output += '	</div>';
		output += '	<div class="modal-footer">';
		output += '		<div class="form-group">';
		output += '			<input type="hidden" name="no_pp" value="' + no_deviasi + '">';
		output += '			<button type="button" class="btn btn-primary" id="submit_delete">Submit</button>';
		output += '		</div>';
		output += '	</div>';
		output += '</form>';

		$('#KiranaModals .modal-body').remove();
		$('#KiranaModals .modal-footer').remove();
		$('#KiranaModals form').remove();
		$('#KiranaModals .modal-content').append(output);

		$('#KiranaModals').modal({
			backdrop: 'static',
			keyboard: true,
			show: true
		});
	});

	$(document).on("click", "#submit_delete", function (e) {

		var no_pp = $("#no_pp").val();
		var alasan = $("#alasan").val();

		if (alasan == '' || alasan == null) {
			alert("Form tidak boleh Kosong.");
			e.preventDefault();
			return false;
		} else {
			$.ajax({
				url: baseURL + "kiass/transaksi/save/delete",
				type: 'POST',
				dataType: 'JSON',
				data: {
					no_pp: no_pp,
					alasan: alasan
				},
				success: function (data) {
					if (data.sts == 'OK') {
						kiranaAlert(data.sts, data.msg);
					} else {
						kiranaAlert("notOK", data.msg, "error", "no");
					}
				},
				error: function () {
					kiranaAlert("notOK", "Server Error", "error", "no");
				}
			});
		}

	});


	$(document).on("change", "#plant, #tahun, #status", function () {	
        
        $.ajax({
			url: baseURL + "kiass/deviasi/get/lists",
			type: 'POST',
			dataType: 'JSON',
			data: {
				plant: $("select[name='plant[]']").val(),
				status: $("select[name='status[]']").val(),
				tahun: $("select[name='tahun[]']").val()
			},
			success: function (data) {
				if(data) {
					var t = $('.my-datatable-extends-order').DataTable();
					t.clear().draw({
						columnDefs: [
							{"className": "text-right", "targets": 1},
							{"type": 'date-eu',"className": "text-center", "targets": 2},
							{"className": "text-center", "targets": 3},
						],
						ordering: true,
						order: [2, 'desc'],
					});
	
					var action = '';
					var output = "";
					var desc = "";
					
					$.each(data, function(i,j){
						desc = '<br><small>Sedang diproses di ' + j.nama_role + '</small>';
						
						var link_detail = baseURL + "kiass/deviasi/detail/" + j.no_deviasi.replace(/\//g, "-");
						var link_edit = baseURL + "kiass/deviasi/edit/" + j.no_deviasi.replace(/\//g, "-");

						var target_link = '';
						
						output = "			<div class='input-group-btn'>";
						output += "				<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>";
						output += "				<ul class='dropdown-menu pull-right'>";
						output += "		<li><a href='" + link_detail + "' class='action' target='" + target_link + "'><i class='fa fa-list'></i> Detail</a></li>";
						if( (j.status == '1' || j.status == '2') && ( $("input[name='session_role_level']").val() == '1' || $("input[name='session_role_level']").val() == '2')){
							output += "		<li><a href='" + link_edit + "' class='action' target='" + target_link + "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";			
						}

						if( $("input[name='session_role_delete']").val() == 'yes' && j.status == $("input[name='session_role_level']").val()){
							output += "		<li><a href='javascript:void(0)' id='delete_pp' data-delete='"+j.no_deviasi.replace(/\//g, "-")+"'><i class='fa fa-trash'></i> Delete</a></li>";			
						}

						output += "				</ul>";
						output += "	        </div>";
	
						t.row.add([
							j.no_pp,
							j.no_deviasi,
							j.perihal,
							generateDateFormat(j.tanggal_pengajuan),
							j.view_status + desc,
							output
						]).draw(false);
							
					});
					
				}
				
			}
		});
	});
});
