$(document).ready(function() {
    add_row();
    $(document).on("keyup", ".cek_min_max", function(e) {
        var nilai = $(this).val();
        if (nilai < 6) {
            alert('Nilai Minimal 6');
            $(this).val(6);
        }
        if (nilai > 24) {
            alert('Nilai Miksimal 24');
            $(this).val(24);
        }
    });

    //set on click
    $(document).on("change", "#id_scoring_tipe", function(e) {
        var id_scoring_tipe = $("#id_scoring_tipe").val();
        if ((id_scoring_tipe == '1') || (id_scoring_tipe == '2')) {
            $("#show_kelas").show();
        } else {
            $("#show_kelas").hide();
            $("#kelas").val("");
        }
    });

    $(document).on("click", ".add-row", function() {
        add_row();
    });

    $(document).on("click", ".delete-row", function() {
        var count = $("#input-jaminan-wrapper tr").length;
        if (count > 1) $("#input-jaminan-wrapper tr:eq(" + (count - 1) + ")").remove();
    });

    function add_row(myelement) {
        // $.ajax({
        // url: baseURL + "umb/master/get/plant",
        // type: 'POST',
        // dataType: 'JSON',
        // success: function (data) {
        // if (data.length > 0) {
        // var output = "<tr>";
        // output += '	<td width="70%">';
        // output += '		<input type="text" class="form-control" name="detail[]" required="required"/>';
        // output += '	</td>';
        // output += '	<td>';
        // output += '		<input type="text" class="form-control text-right angka" name="persen_discount[]" required="required"/>';
        // output += '	</td>';
        // output += '</tr>';
        // $("#input-jaminan-wrapper").append(output);
        // }
        // },
        // complete: function () {
        // $(".select2").select2();
        // if (myelement) {
        // $.each(myelement, function (i, v) {
        // $(v.element).val(v.value).trigger("change");
        // });
        // }
        // }
        // });
        var output = "<tr>";
        output += '	<td width="70%">';
        output += '		<input type="text" class="form-control" name="detail[]" required="required"/>';
        output += '	</td>';
        output += '	<td>';
        output += '		<input type="text" class="form-control text-right angka" name="persen_discount[]" required="required"/>';
        output += '	</td>';
        output += '</tr>';
        $("#input-jaminan-wrapper").append(output);
        $(".select2").select2();
        if (myelement) {
            $.each(myelement, function(i, v) {
                $(v.element).val(v.value).trigger("change");
            });
        }

    }

    $(document).on("click", "button[name='action_btn']", function(e) {
        var empty_form = validate();
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-master-jaminan")[0]);

                $.ajax({
                    url: baseURL + 'umb/master/save/jaminan',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        if (data.sts == 'OK') {
                            kiranaAlert(data.sts, data.msg);
                        } else {
                            kiranaAlert(data.sts, data.msg, "error", "no");
                            $("input[name='isproses']").val(0);
                        }
                    },
                    complete: function() {
                        $("input[name='isproses']").val(0);
                    }
                });
            } else {
                kiranaAlert("notOK", "Silahkan tunggu proses selesai", "warning", "no");
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on("click", ".edit", function(e) {
        $(".form-master-jaminan input, .form-master-jaminan select").val(null).trigger("change");
        $.ajax({
            url: baseURL + "umb/master/get/jaminan",
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_mjaminan_header: $(this).data("edit")
            },
            success: function(data) {
                if (data) {
                    $.each(data, function(i, v) {
                        console.log(data);
                        $("input[name='id_mjaminan_header']").val(v.id_mjaminan_header);
                        $("select[name='jenis']").val(v.jenis == null ? 0 : v.jenis).trigger("change");

                        //detail
                        var output = "";
                        if (v.list_detail != null) {
                            var list_detail = v.list_detail.slice(0, -1).split(",");
                            $.each(list_detail, function(x, y) {
                                var a = list_detail[x].split("|");
                                output += '<tr>';
                                output += '	<td width="70%">';
                                output += '		<input type="text" class="form-control" name="detail[]" value="' + a[1] + '" required="required"/>';
                                output += '	</td>';
                                output += '	<td>';
                                output += '		<input type="text" class="form-control text-right angka" name="persen_discount[]" value="' + a[2] + '" required="required"/>';
                                output += '	</td>';
                                output += '</tr>';
                            });
                            $("#input-jaminan-wrapper").html(output);
                        }

                    });

                    $("#btn-new").show();
                }
            }
        });
        e.preventDefault();
        return false;
    });
    //detail
    $(".detail").on("click", function(e) {
        var id_mjaminan_header = $(this).data("detail");
        $.ajax({
            url: baseURL + 'umb/master/get/jaminan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id_mjaminan_header: id_mjaminan_header
            },
            success: function(data) {
                if (data) {
                    $.each(data, function(i, v) {
                        console.log(data);
                        $("input[name='jenis_modal']").val(v.jenis);

                        //detail
                        var output = "";
                        output += '<table class="table table-bordered table-striped">';
                        output += '<thead><tr><th>Detail Jaminan</th><th>% Discount</th></tr></thead><tbody>';
                        if (v.list_detail != null) {
                            var list_detail = v.list_detail.slice(0, -1).split(",");
                            $.each(list_detail, function(x, y) {
                                var a = list_detail[x].split("|");
                                output += '<tr>';
                                output += '	<td width="70%">';
                                output += '		<input type="text" class="form-control" name="jenis[]" value="' + a[1] + '" required="required" disabled/>';
                                output += '	</td>';
                                output += '	<td>';
                                output += '		<input type="text" class="form-control text-right angka" name="persen_discount[]" value="' + a[2] + '" required="required"  disabled/>';
                                output += '	</td>';
                                output += '</tr>';
                            });
                            output += '</tbody></table>';

                        }
                        $("#show_detail_jaminan").html(output);
                    });
                    $('#detail_master_jaminan').modal('show');
                }
            }

        });
    });


    $("#btn-new").on("click", function(e) {
        location.reload();
        e.preventDefault();
        return false;
    });

    $(document).on("click", ".nonactive, .setactive, .delete", function(e) {
        $.ajax({
            url: baseURL + "umb/master/set/jaminan",
            type: 'POST',
            dataType: 'JSON',
            data: {
                kode: $(this).data($(this).attr("class")),
                type: $(this).attr("class")
            },
            success: function(data) {
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