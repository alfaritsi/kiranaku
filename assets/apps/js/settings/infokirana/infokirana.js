$(document).ready(function () {
    var ckeditor_isi = CKEDITOR.replace('ckeditorIsi');
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true
    });

    $("#btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
    });

    $(".set_active").on("click", function (e) {
        var id = $(this).data("id");
        var action = $(this).data("action");

        $.ajax({
            url: baseURL + 'settings/infokirana/set_data/publish/' + action,
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    });

    function set_komentar_click_event()
    {

        $(".komentar_set_active").on("click", function (e) {
            var id = $(this).data("id");
            var action = $(this).data("action");

            $.ajax({
                url: baseURL + 'settings/infokirana/set_data/komentar-publish/' + action,
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id
                },
                success: function (data) {
                    if (data.sts == 'OK') {
                        alert(data.msg);
                        location.reload();
                    } else {
                        alert(data.msg);
                    }
                }
            });
        });
    }

    $(".delete").on("click", function (e) {
        var id = $(this).data("delete");
        $.ajax({
            url: baseURL + 'settings/infokirana/delete',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                if (data.sts == 'OK') {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            }
        });
    });

    $(".edit").on("click", function (e) {
        var _ckeditorIsi = ckeditor_isi;
        var id = $(this).data("edit");
        $.ajax({
            url: baseURL + 'settings/infokirana/get_data',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $(".title-form").html("Edit News");
                $.each(data.data, function (i, v) {
                    $("#tanggal").val(v.tanggal);
                    $('#tanggal').datepicker('update', v.tanggal);
                    $("#judul").val(v.judul);
                    if (v.gambar !== null) {
                        $("#gambar").attr('value', v.gambar);
                        $("#gambar").removeAttr('required');
                    }

                    $("input[name='id']").val(data.id);
                    _ckeditorIsi.setData(v.isi);
                    $("#btn-new").removeClass("hidden");
                });
            }
        });
    });

    $('.komentars').on('click', function (event) {
        let id = $(this).data('id');
        let el = $(this);

        if(!$(this).hasClass('expanded'))
        {
            $.ajax({
                url: baseURL + 'settings/infokirana/get_list_komentar',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    id: id
                },
                complete: function (data) {
                    $('.table-komentars').remove();
                    $(data.responseText).insertAfter($(el).closest('tr'));
                    $('.table-komentars table').dataTable();
                    set_komentar_click_event();
                }
            });
        }else{
            $('.table-komentars').remove();
        }
        $('.komentars').not(this).removeClass('expanded');
        $(this).toggleClass('expanded');

    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var _ckeditorIsi = ckeditor_isi;
        var empty_form = validate();
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                $("textarea[name='isi']").text(_ckeditorIsi.getData());
                var formData = new FormData($(".form-settings-infokirana")[0]);

                $.ajax({
                    url: baseURL + 'settings/infokirana/set_data/save',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            alert(data.msg);
                            location.reload();
                        } else {
                            alert(data.msg);
                            $("input[name='isproses']").val(0);
                        }
                    }
                });
            } else {
                alert("Silahkan tunggu proses selesai.");
            }
        }
        e.preventDefault();
        return false;
    });
});