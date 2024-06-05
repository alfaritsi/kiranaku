$(document).ready(function () {

    $('#tanggal_bak').datepicker({
        format: 'dd.mm.yyyy',
        autoclose: true,
        weekStart: 1,
    });
    $('#jam_bak_masuk').datetimepicker({
        format: 'HH:mm'
    });
    $('#jam_bak_keluar').datetimepicker({
        format: 'HH:mm'
    });

    $('#id_bak_alasan').on('change',function(){
        switch($(this).val())
        {
            case '2':
                $('#jam_bak_masuk').attr('disabled',false);
                $('#jam_bak_keluar').attr('disabled',true);
                break;
            case '3':
                $('#jam_bak_masuk').attr('disabled',true);
                $('#jam_bak_keluar').attr('disabled',false);
                break;
            case '7':
                $('#jam_bak_masuk').attr('disabled',false);
                $('#jam_bak_keluar').attr('disabled',false);
                break;
        }
    });

    $('#modalKaryawan').on('hidden.bs.modal', function () {
        let selected = [];
        $.each($('table', '#modalKaryawan').DataTable().column(0).checkboxes.selected(), function (i, value) {
            selected.push(value);
        });
        $('#karyawans').val(selected.join('.'));
        $('#btnModalKaryawan').html(selected.length+' karyawan dipilih');
        $('.form-bak-masal').valid();
    });

    let checkboxApi = null;

    $('table', '#modalKaryawan').dataTable({
        'destroy': true,
        "order": [[1, "asc"]],
        'columnDefs': [
            {
                'targets': 0,
                'checkboxes': {
                    'selectRow': true,
                    'selectAllPages': false,
                    'selectCallback': function(nodes, selected){
                        if(selected)
                            $(nodes).parent('tr').addClass('selected');
                        else
                            $(nodes).parent('tr').removeClass('selected');
                        // If "Show all" is not selected
                        if($('#show-selected').val() !== 'all'){
                            // Redraw table to include/exclude selected row
                            table.draw(false);
                        }
                    }
                }
            }
        ],
        'initComplete' : function (settings) {
            checkboxApi = this.api();
        },
        'select': {
            'style': 'multi'
        }
    });

    $('#show-selected').on('change', function(){
        var val = $(this).val();
        var table = $('table', '#modalKaryawan').DataTable();

        // If all records should be displayed
        if(val === 'all'){
            $.fn.dataTable.ext.search.pop();
            table.draw();
        }

        // If selected records should be displayed
        if(val === 'selected'){
            $.fn.dataTable.ext.search.pop();
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex){
                    return ($(table.row(dataIndex).node()).hasClass('selected')) ? true : false;
                }
            );

            table.draw();
        }

        // If selected records should not be displayed
        if(val === 'not-selected'){
            $.fn.dataTable.ext.search.pop();
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex){
                    return ($(table.row(dataIndex).node()).hasClass('selected')) ? false : true;
                }
            );

            table.draw();
        }
    });

    $('#filter_divisi,#filter_departemen').select2({
        allowClear: true
    });

    $('#filter_divisi,#filter_departemen').on('change', function () {
        var table = $('table', '#modalKaryawan').DataTable();
        table.search($('#filter_divisi').val() + " " + $('#filter_departemen').val()).draw();
    });

    validator = $('.form-bak-masal').validate({
        ignore: [],
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.appendTo(element.parents('.form-group > div'));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group > div").addClass("has-error").removeClass("has-success");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".form-group > div").addClass("has-success").removeClass("has-error");
        }

    });

    $(".btn-new").on("click", function (e) {
        location.reload();
        e.preventDefault();
        return false;
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        var valid = $('.form-bak-masal').valid();

        if (valid) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-bak-masal")[0]);

                $.ajax({
                    url: baseURL + 'ess/bak/save/masal',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                $('#modal-cutiijin').modal('hide');
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                        kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu sampai proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on('click', '.edit', function (e) {
        var id = $(this).attr('data-edit');
        $.ajax({
            url: baseURL + 'ess/bak/get/massal',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                validator.resetForm();
                if(data.data)
                {
                    let dataEdit = data.data;
                    $('input[name="id_massal"]').val(id);
                    $('#tanggal_bak').datepicker('setDate',moment(dataEdit.tanggal_bak).format('DD.MM.YYYY'));
                    $('#id_bak_alasan').val(dataEdit.id_bak_alasan);
                    $('#id_bak_alasan').trigger('change');
                    $('#jam_bak_masuk').data('DateTimePicker').date(dataEdit.jam_bak_masuk);
                    $('#jam_bak_keluar').data('DateTimePicker').date(dataEdit.jam_bak_keluar);
                    $('#catatan').val(dataEdit.catatan);
                    $('#karyawans').val(dataEdit.karyawans);

                    let karyawans = dataEdit.karyawans.split('.');

                    karyawans.forEach(function(element,index) {
                        checkboxApi.cells(
                            checkboxApi.rows(function(idx, data, node){
                                return (data[0] === element) ? true : false;
                            }).indexes(),
                            0
                        ).checkboxes.select()
                    });

                    $('#btnModalKaryawan').html(karyawans.length+' karyawan dipilih');
                    $(".btn-new").removeClass("hidden");

                }else{
                    kiranaAlert(false, 'Data tidak tersedia. Mohon ulangi proses.', 'error', 'no');
                }
            }
        });
    })

    $(".delete").on("click", function (e) {
        var id = $(this).attr("data-delete");
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan menghapus data?",
                dangerMode: true,
                successCallback: function () {
                    $.ajax({
                        url: baseURL + 'ess/bak/delete/massal',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id: id
                        },
                        success: function (data) {
                            if (data.sts == 'OK') {
                                kiranaAlert(data.sts, data.msg);
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );

    });
});