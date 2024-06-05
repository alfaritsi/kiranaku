$(document).ready(function () {

    validator = $('#form-buat-spk').validate({
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

    $('[data-js=datepicker]').datepicker({
        format: 'dd.mm.yyyy',
        autoclose: true,
        todayHighlight: true,
        weekStart: 1,
        inputs: $('.tgl_awal_akhir')
    });
    $('#start').on('changeDate', function (e) {
        $('#end').datepicker('setStartDate', e.date);
    });

    $('#filter-date input, #pabrik, #status').on('change', function () {
        $(this).parents('form').submit();
    });

    $(document).on('click', '.item-detail', function () {
        let dataTable = $('#spk-table').DataTable();
        let tr = $(this);
        let row = dataTable.row(tr);
        if (row.child.isShown()) {
            $(this).css('background-color', '');
            row.child.hide();
        } else {
            $(this).css('background-color', '#e6e6e6');
            row.child(
                $('#table-items-' + $(this).attr('data-detail')).clone().html()
            )
                .show();
        }
    });

    let dataSO = null;

    $('#modal-detail-so-item').on('hidden.bs.modal', function () {
        $('#table-history-spk').DataTable().destroy();
    });

    $(document).on('click', '.so-detail', function () {
        let modal = $('#modal-detail-so-item');
        let form = $('#form-buat-spk', modal);
        let data = JSON.parse($(this).attr('data-so'));
        dataSO = data;
        $('#start', modal).val('');
        $('#end', modal).val('');
        $('.has-error', modal).removeClass('has-error');
        $('.has-success', modal).removeClass('has-success');
        validator.resetForm();
        $('#plant', modal).html(data.plant);
        $('#no_po', modal).html(data.no_po);
        $('#no_so', modal).html(data.no_so);
        $('#no_pi', modal).html(data.no_pi);
        $('.no_mat', modal).html(data.no_mat);
        $('#tanggal_req_delivery', modal).html(data.tanggal_req_delivery);
        $('#tanggal_plan_delivery', modal).html(data.tanggal_plan_delivery);
        $('#qty', modal).val(data.qty_ord_left);
        $('.uom', modal).html(data.uom);
        $('input[name="uom"]', modal).val(data.uom);
        $('#qty', modal).attr('max', data.qty_ord_left);
        $('#total', modal).html(data.qty_ord - data.qty_ord_left);

        $('#form-buat-spk, .buat-spk', modal).addClass('hide');
        $('.lihat-spk', modal).removeClass('hide');
        if (data.qty_ord_left > 0) {
            $('#form-buat-spk, .buat-spk', modal).removeClass('hide');
            $('.lihat-spk', modal).addClass('hide');
        }

        $('#table-history-spk').dataTable({
            paging: false,
            info: false,
            lengthChange: false,
            pageLength: 5,
            searching: false,
            columnDefs: [
                {
                    "targets": 2,
                    "className": "text-center"
                }
            ]
        });
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'nusira/monitoring/get_list_io',
            type: 'POST',
            dataType: 'JSON',
            data: {
                no_po: data.no_po,
                no_so: data.no_so,
                no_mat: data.no_mat,
                no_pos: data.no_pos
            },
            success: function (data) {
                KIRANAKU.hideLoading();
                if (data.sts == 'OK') {
                    let datatableData = [];
                    let table = $('#table-history-spk').DataTable();
                    $.each(data.data, function (index, item) {
                        datatableData.push([
                            item.start,
                            item.end,
                            item.qty,
                            item.uom,
                            item.no_io,
                        ]);
                    });
                    table.clear();
                    table.rows.add(datatableData).draw(false);
                } else {
                    kiranaAlert(data.sts, data.msg, 'error', 'no');
                }
            },
            error: function (data) {
                KIRANAKU.hideLoading();
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });

        /*
        // Old modal so detail
        $('#id_plant', modal).html(data.id_plant);
        $('#no_so', modal).html(data.no_po);
        $('#tanggal', modal).html(moment(data.tanggal_po).format('DD.MM.YYYY'));
        let items = data.items;
        let datatableData = [];
        let no = 1;
        $.each(items, function (index, item) {
            let dataSpk = JSON.stringify(item);
            let action = '<div class="btn-group btn-group-xs btn-group-justified text-center">' +
                '   <a href="javascript:void(0)" data-spk=\'' + dataSpk + '\' class="btn btn-sm btn-primary btn-spk"><i class="fa fa-plus-circle"></i> SPK</a>' +
                '   <a href="javascript:void(0)" class="btn btn-sm btn-success"><i class="fa fa-database"></i> Stok</a>' +
                '</div>';
            if (item.qty_stock < item.qty_ord)
                action = '<div class="btn-group btn-group-xs btn-group-justified text-center">' +
                    '   <a href="javascript:void(0)" data-spk=\'' + dataSpk + '\' class="btn btn-sm btn-primary btn-spk"><i class="fa fa-plus-circle"></i> Buat SPK</a>' +
                    '</div>';
            if (item.no_io !== '')
                action = "" + item.no_io
            datatableData.push([
                no,
                item.no_pos,
                item.no_mat,
                item.nama_mat,
                item.uom,
                KIRANAKU.isNullOrEmpty(item.tanggal_delivery, item.tanggal_delivery, '-'),
                item.qty_ord,
                item.qty_stock,
                action
            ]);
            no++;
        });
        table.clear();
        table.rows.add(datatableData).draw(false);*/
        modal.modal('show');
    });

    $(document).on('click', '.btn-spk', function (e) {
        e.preventDefault();

        let modal = $('#modal-detail-so-item');
        let data = dataSO;

        let start = $('#start', modal).val();
        let end = $('#end', modal).val();
        let qty = $('#qty', modal).val();
        let uom = $('input[name="uom"]', modal).val();

        let valid = $('#form-buat-spk').valid();

        if (valid) {
            KIRANAKU.showLoading();
            $.ajax({
                url: baseURL + 'nusira/monitoring/rfc/get_no_io',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    no_po: data.no_po,
                    no_so: data.no_so,
                    no_mat: data.no_mat,
                    no_pos: data.no_pos,
                    nama_mat: data.nama_mat,
                    start: start,
                    end: end,
                    qty: qty,
                    uom: uom,
                },
                success: function (data) {
                    KIRANAKU.hideLoading();
                    if (data.sts == 'OK') {
                        kiranaAlert(data.sts, data.msg);
                        // $.ajax({
                        //     url: baseURL + 'nusira/monitoring/rfc/update_io',
                        //     type: 'POST',
                        //     dataType: 'JSON',
                        //     data: {
                        //         no_so: dataSpk.no_so,
                        //         no_io: dataSpk.no_io,
                        //         no_pos: dataSpk.no_pos
                        //     },
                        //     success: function (data) {
                        //         if (data.sts == 'OK') {
                        //             kiranaAlert(data.sts, data.msg);
                        //         } else {
                        //             kiranaAlert(data.sts, data.msg, 'error', 'no');
                        //         }
                        //     },
                        //     error: function (data) {
                        //         kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                        //     }
                        // });
                    } else {
                        kiranaAlert(data.sts, data.msg, 'error', 'no');
                    }
                },
                error: function (data) {
                    KIRANAKU.hideLoading();
                    kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
                }
            });
        }
        return false;
    });

    $('#modal-detail-so').on('shown.bs.modal', function () {
        adjustDatatableWidth();
    });
});