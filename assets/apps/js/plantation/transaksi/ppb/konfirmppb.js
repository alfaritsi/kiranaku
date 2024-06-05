$(document).ready(function() {
    $(document).on("change", ".item-check", function(){
        const isChecked = $(this).prop('checked');
        const row = $(this).closest(".row-item");
        if (isChecked) {
            row.find("input[name^='tipe_barang_']").attr('required', true);
            row.find("input[name^='jumlah_disetujui_']").attr('required', true);
            row.find("input[name^='jumlah_disetujui_']").attr('readonly', false);
        } else {
            // row.find("input[name^='jumlah_disetujui_']").val("");
            row.find("input[name^='tipe_barang_']").attr('required', false);
            row.find("input[name^='jumlah_disetujui_']").attr('required', false);
            row.find("input[name^='jumlah_disetujui_']").attr('readonly', true);
        }
        generate_check_all();
    });

    //check all
    $(document).on("change", "#checkall", function(){
        const isChecked = $(this).prop('checked');
        $(".item-check").prop("checked", isChecked).trigger("change");
    });

    //submit
    $(document).on("click", "button[name='action_btn']", function(){
        if ($(".item-check:checkbox:checked").length > 0) {
            $("input[name='action']").val($(this).attr("data-btn").toLowerCase());
            submit_ppb();
        } else {
            swal('Error', 'Tidak Ada item yang dipilih', 'error');
        }
    });

    $(document).on("click", ".view_file", function () {
		if ($(this).data("link") !== "") {
			window.open(baseURL+$(this).data("link"), '_blank');
		}else{
			// kiranaAlert("notOK", "File Tidak Ditemukan", "warning", "no");
			var overlay = "<label class='err_msg' style='font-size:12px;color:red;'>&nbspFile tidak ditemukan</label>"; 
			if ($(".err_msg").length > 0) {
				$(".err_msg").remove();
			}
			$(this).closest("td").append(overlay);
		}
	});
});

function generate_check_all() {
    const jumlah_all_checkbox = $(".item-check:checkbox").length;
    const jumlah_checked = $(".item-check:checkbox:checked").length;
    const isChecked = (jumlah_all_checkbox == jumlah_checked);
    $("#checkall").prop('checked', isChecked);
}

function submit_ppb() {
    const tipe = $("#tipe_po").val();
    $(".item-check:checkbox:checked").each(function(i) {
        const row = $(this).closest(".row-item");
        if (tipe == "REJECT") {
            row.find("input[name^='jumlah_disetujui_']").attr('required', false);
            row.find("input[name^='jumlah_disetujui_']").val(0);
        } else {
            row.find("input[name^='jumlah_disetujui_']").attr('required', true);
            // row.find("input[name^='jumlah_disetujui_']").attr('min', 1);
            const nilai = row.find("input[name^='jumlah_disetujui_']").val();
            if (nilai == 0) row.find("input[name^='jumlah_disetujui_']").val(0);
        }
    });
    
    const empty_form = validate('#form-konfirm-ppb');
    if (empty_form == 0) {
        const isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            var formData = new FormData($("#form-konfirm-ppb")[0]);
            $.ajax({
                url: baseURL + 'plantation/transaksi/save/konfirmppb',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.sts == 'OK') {
                        swal('Success', data.msg, 'success').then(function () {
                            location.reload();
                        });
                    } else {
                        $("input[name='isproses']").val(0);
                        swal('Error', data.msg, 'error');
                    }
                },
                error: function () {
                    swal('Error', 'Server Error', 'error');
                },
                complete: function () {
                    $("input[name='isproses']").val(0);
                }
            });
        } else {
            swal({
                title: "Silahkan tunggu proses selesai.",
                icon: 'info'
            });
        }
    }
    // e.preventDefault();
    return false;
}