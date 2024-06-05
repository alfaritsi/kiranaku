/*
@application  	: Equipment Management
@author     	: Lukman Hakim (7143)
@contributor  	: 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/

$(document).ready(function () {

    $('.datatable-custom').DataTable({
        order: [[0, 'asc']],
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        "iDisplayLength": 50,
        "paging": true,
        columnDefs: [
            {"className": "text-center", "targets": 2},
            {"className": "text-center", "targets": 1},
            {"className": "text-center", "targets": 3},
        ],
    });


    $(".edit_opsi").on("click", function (e) {
        var id_opsi = $(this).data("edit-opsi");
        $.ajax({
            url: baseURL + 'asset/master/get_aset_detail_opsi',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_opsi: id_opsi
            },
            success: function (data) {
                $(".title-form-opsi").html("<strong>Edit Opsi Detail Aset</strong>");
                $.each(data, function (i, v) {
                    $("#id_aset_detail_master").val(v.id_aset_detail_master).trigger('change');
                    $("#nilai_pilihan").val(v.nilai_pilihan);
                    if(v.satuan)
                    {
                        $('#groupNilai').addClass('input-group');
                        $('#groupNilai span').removeClass('hide');
                        $("#groupNilai span").html(v.satuan);
                    }else{
                        $('#groupNilai').removeClass('input-group');
                        $('#groupNilai span').addClass('hide');
                    }

                    $("input[name='id_aset_detail_opsi']").val(v.id_aset_detail_opsi);
                    $("#btn-new-opsi").removeClass("hidden");
                });
            }
        });
    });

    $("#btn-new-opsi").on("click", function (e) {
        $("#nama_kolom").val(null).trigger('change');
        $("#nilai_pilihan").val("");

        $('#groupNilai').removeClass('input-group');
        $('#groupNilai span').addClass('hide');
        $('#groupNilai span').html("");

        $("input[name='id_aset_detail_opsi']").val("");
        $(".title-form-opsi").html("<strong>Buat Opsi Detail Aset Baru</strong>");
        $("#btn-new-opsi").addClass("hidden");
    });

    $(".form-master-komponen").on("submit", function (e) {

        var jenis_asset = $("#jenis_asset").val();
        if (jenis_asset == 0) {
            kiranaAlert("notOK", "Pilih Jenis Asset", "warning", "no");
            e.preventDefault();
            return false;
        } else {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(this)[0]);

                $.ajax({
                    url: baseURL + 'asset/master/save_aset_detail_opsi',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);
                        } else {
                            kiranaAlert(data.sts, data.msg, "error", "no");
                            $("input[name='isproses']").val(0);
                        }
                    }
                });
            } else {
                alert("Silahkan tunggu proses selesai.");
            }
            e.preventDefault();
            return false;
        }
    });

    $(document).on("click", ".set_active, .non_active, .delete", function (e) {
        $.ajax({
            url: baseURL + "asset/master/set/aset_detail_opsi",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_opsi: $(this).data($(this).attr("class")),
                type: $(this).attr("class")
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg);
                } else {
                    kiranaAlert("notOK", data.msg, "warning", "no");
                }
            }
        });
        e.preventDefault();
        return false;
    });

});