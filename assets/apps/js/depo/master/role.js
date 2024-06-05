$(document).ready(function(){
    $("#btn-new").on("click", function(e){
    	location.reload();
    	e.preventDefault();
		return false;
    });
    $(document).on("change", "input[name='is_paralel']", function(){
        const isChecked = $(this).prop('checked');
        if (isChecked) {
            $(".input-paralel").removeClass("hidden");
            $("select[name='divisi_pembukaan_tetap[]']").attr('required', true);
            $("select[name='divisi_penutupan_tetap[]']").attr('required', true);
            $("select[name='divisi_evaluasi_tetap[]']").attr('required', true);
            $("select[name='divisi_realisasi_tetap[]']").attr('required', true);
            $("select[name='divisi_pembukaan_trial[]']").attr('required', true);
            $("select[name='divisi_penutupan_trial[]']").attr('required', true);
            $("select[name='divisi_evaluasi_trial[]']").attr('required', true);
            $("select[name='divisi_realisasi_trial[]']").attr('required', true);
        } else {
            $(".input-paralel").addClass("hidden");
            $("select[name='divisi_pembukaan_tetap[]']").attr('required', false);
            $("select[name='divisi_penutupan_tetap[]']").attr('required', false);
            $("select[name='divisi_evaluasi_tetap[]']").attr('required', false);
            $("select[name='divisi_realisasi_tetap[]']").attr('required', false);
            $("select[name='divisi_pembukaan_trial[]']").attr('required', false);
            $("select[name='divisi_penutupan_trial[]']").attr('required', false);
            $("select[name='divisi_evaluasi_trial[]']").attr('required', false);
            $("select[name='divisi_realisasi_trial[]']").attr('required', false);
        }
    });
	
	$(document).on("click", ".nonactive, .setactive", function (e) {
		$.ajax({
			url: baseURL + "depo/master/set/role",
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_role : $(this).data($(this).attr("class")),	
				type 	: $(this).attr("class")
			},
			success: function(data){
				if(data.sts == 'OK'){
					kiranaAlert(data.sts, data.msg);
				}else{
					kiranaAlert("notOK", data.msg, "warning", "no");
				}
			}
		});
		e.preventDefault();
		return false;
	});	

	$(document).on("click", ".edit", function (e) {	
    	var id_role	= $(this).data("edit");
		$.ajax({
    		url: baseURL+'depo/master/get/role',
			type: 'POST',
			dataType: 'JSON',
			data: {
				id_role : id_role
			},
			success: function(data){
				$(".title-form").html("Form Role Bank Specimen");
				$.each(data, function(i, v){
					$("#id_role").val(v.id_role);
					$("input[name='nama']").val(v.nama);
					$("input[name='level']").val(v.level);
					$("select[name='tipe_user']").val(v.tipe_user).trigger("change.select2");
					$('input[name="is_paralel"]').prop("checked", v.is_paralel).trigger("change");
					
					$("select[name='if_approve_pembukaan_tetap']").val(v.if_approve_pembukaan_tetap).trigger("change.select2");
					$("select[name='if_decline_pembukaan_tetap']").val(v.if_decline_pembukaan_tetap).trigger("change.select2");
                    if (v.divisi_pembukaan_tetap) {
                        const divisi_pembukaan_tetap = v.divisi_pembukaan_tetap.split(",");
                        $("select[name='divisi_pembukaan_tetap[]']").val(divisi_pembukaan_tetap).trigger("change");
                    }
					$("select[name='if_approve_evaluasi_tetap']").val(v.if_approve_evaluasi_tetap).trigger("change.select2");
					$("select[name='if_decline_evaluasi_tetap']").val(v.if_decline_evaluasi_tetap).trigger("change.select2");
                    if (v.divisi_evaluasi_tetap) {
                        const divisi_evaluasi_tetap = v.divisi_evaluasi_tetap.split(",");
                        $("select[name='divisi_evaluasi_tetap[]']").val(divisi_evaluasi_tetap).trigger("change");
                    }
					$("select[name='if_approve_penutupan_tetap']").val(v.if_approve_penutupan_tetap).trigger("change.select2");
					$("select[name='if_decline_penutupan_tetap']").val(v.if_decline_penutupan_tetap).trigger("change.select2");
                    if (v.divisi_penutupan_tetap) {
                        const divisi_penutupan_tetap = v.divisi_penutupan_tetap.split(",");
                        $("select[name='divisi_penutupan_tetap[]']").val(divisi_penutupan_tetap).trigger("change");
                    }
					$("select[name='if_approve_realisasi_tetap']").val(v.if_approve_realisasi_tetap).trigger("change.select2");
					$("select[name='if_decline_realisasi_tetap']").val(v.if_decline_realisasi_tetap).trigger("change.select2");
                    if (v.divisi_realisasi_tetap) {
                        const divisi_realisasi_tetap = v.divisi_realisasi_tetap.split(",");
                        $("select[name='divisi_realisasi_tetap[]']").val(divisi_realisasi_tetap).trigger("change");
                    }

					$("select[name='if_approve_pembukaan_trial']").val(v.if_approve_pembukaan_trial).trigger("change.select2");
					$("select[name='if_decline_pembukaan_trial']").val(v.if_decline_pembukaan_trial).trigger("change.select2");
                    if (v.divisi_pembukaan_trial) {
                        const divisi_pembukaan_trial = v.divisi_pembukaan_trial.split(",");
                        $("select[name='divisi_pembukaan_trial[]']").val(divisi_pembukaan_trial).trigger("change");
                    }
					$("select[name='if_approve_evaluasi_trial']").val(v.if_approve_evaluasi_trial).trigger("change.select2");
					$("select[name='if_decline_evaluasi_trial']").val(v.if_decline_evaluasi_trial).trigger("change.select2");
                    if (v.divisi_evaluasi_trial) {
                        const divisi_evaluasi_trial = v.divisi_evaluasi_trial.split(",");
                        $("select[name='divisi_evaluasi_trial[]']").val(divisi_evaluasi_trial).trigger("change");
                    }
					$("select[name='if_approve_penutupan_trial']").val(v.if_approve_penutupan_trial).trigger("change.select2");
					$("select[name='if_decline_penutupan_trial']").val(v.if_decline_penutupan_trial).trigger("change.select2");
                    if (v.divisi_penutupan_trial) {
                        const divisi_penutupan_trial = v.divisi_penutupan_trial.split(",");
                        $("select[name='divisi_penutupan_trial[]']").val(divisi_penutupan_trial).trigger("change");
                    }
					$("select[name='if_approve_realisasi_trial']").val(v.if_approve_realisasi_trial).trigger("change.select2");
					$("select[name='if_decline_realisasi_trial']").val(v.if_decline_realisasi_trial).trigger("change.select2");
                    if (v.divisi_realisasi_trial) {
                        const divisi_realisasi_trial = v.divisi_realisasi_trial.split(",");
                        $("select[name='divisi_realisasi_trial[]']").val(divisi_realisasi_trial).trigger("change");
                    }
					
					$("#btn-new").removeClass("hidden");
				});
			}
		});
    });
	
	$(document).on("click", "button[name='action_btn']", function(e){
		var empty_form = validate(".form-master-role-master_depo");
		if(empty_form == 0){
	    	var isproses 		= $("input[name='isproses']").val();
	    	if(isproses == 0){
	    		$("input[name='isproses']").val(1);
		    	var formData = new FormData($(".form-master-role-master_depo")[0]);
				$.ajax({
					url: baseURL+'depo/master/save/role',
					type: 'POST',
					dataType: 'JSON',
					data: formData,
					contentType: false,
					cache: false,
					processData: false,
					success: function(data){
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
					}
				});
			}else{
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
			}
		}
		e.preventDefault();
		return false;
    });
	//export to excel
	$('.my-datatable-extends-order').DataTable( {
        ordering : true,
        scrollCollapse: true,
        scrollY: false,
        scrollX : true,
        bautoWidth: false,
        pageLength: $(".my-datatable-extends-order",this).data("page") ? $(".my-datatable-extends-order",this).data("page") : 10,
		dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Master Role Bank Specimen',
                download: 'open',
                orientation:'landscape',
                exportOptions: {
                    columns: [0,1,2]
                }
            }
        ]
    } );	

});
