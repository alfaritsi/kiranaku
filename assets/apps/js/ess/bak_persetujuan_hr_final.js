$(document).ready(function () {

    let catatans = [];

    $('#filter-date input').on('change', function () {
        $(this).parents('form').submit();
    });

    $(document).on('click', '.bak-detail-persetujuan', function (e) {
        var id = $(this).attr('data-detail');
        var modal = $('#modal-detail-bak-persetujuan');
        $.ajax({
            url: baseURL + 'ess/bak/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                $('#id_bak', modal).val(data.data.enId);
                $('#tanggal_absen', modal).html(
                    moment(data.data.tanggal_absen).format('MM.DD.YYYY')
                );
                $('#absen_masuk', modal).html(data.data.absen_masuk);
                $('#absen_keluar', modal).html(data.data.absen_keluar);
                $('#tanggal_input', modal).html(
                    moment(data.data.tanggal_absen).format('MM.DD.YYYY')
                );
                $('#alasan', modal).html(data.data.alasan);
                $('#keterangan', modal).html(data.data.keterangan);

                if (data.data.login_buat != null) {
                    $('#tanggal_input_div,#alasan_div,#keterangan_div', modal).removeClass('hide');
                } else {
                    $('#tanggal_input_div,#alasan_div,#keterangan_div', modal).addClass('hide');
                }

                if (KIRANAKU.isNullOrEmpty(data.data.catatan, true, false)) {
                    $('#catatan_div', modal).removeClass('hide');
                    $('#catatan', modal).html(data.data.catatan);
                } else {
                    $('#catatan_div', modal).addClass('hide');
                }

                $('#gambar a', modal).attr('href', data.data.gambar);

                modal.modal('show');

            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $('.catatan').on('click', function () {
        let thisBtn = $(this);
        let id = thisBtn.attr('data-catatan');
        let data = thisBtn.attr('data-data');
        let template = $('#template-catatan').clone();
        data = JSON.parse(data);
        template.find('.iperson').html(data.person);
        template.find('.ijenis').html(data.jenis);
        template.find('.itanggal').html(data.tanggal);

        let defText = catatans[id];
        if (typeof defText === "undefined")
            defText = "";

        swal.queue([
            {
                // html: template.html(),
                input: 'textarea',
                title: 'Ketik catatan anda untuk pengajuan cuti ini.',
                inputValue: defText,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                preConfirm: (text) => {
                    if (text) {
                        catatans[id] = text;
                        thisBtn.removeClass('btn-default');
                        thisBtn.addClass('btn-success');
                        thisBtn.find('span').html('Terisi');
                        if (!thisBtn.parents('tr').find('.dt-body-center input[type="checkbox"]').prop('checked'))
                            thisBtn.parents('tr').find('.dt-body-center input[type="checkbox"]').trigger('click');
                    } else if (text == "") {
                        catatans[id] = text;

                        thisBtn.addClass('btn-default');
                        thisBtn.removeClass('btn-success');
                        thisBtn.find('span').html('');
                    }
                }
            }
        ]);
    });

    $('#table-bak-menunggu').DataTable({
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        'columnDefs': [
            {
                'targets': 0,
                'checkboxes': {
                    'selectRow': true,
                    'selectAllPages': false,
                    'selectCallback': function () {

                    }
                }
            }
        ],
        'select': {
            'style': 'multi'
        },
        'order': [[1, 'asc']]
    });

    $(document).on("click", "button[name='btn-approve']", function (e) {
        e.preventDefault();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);

            let formData = new FormData();
            $.each($('#table-bak-menunggu').DataTable().column(0).checkboxes.selected(), function (i, value) {
                formData.append('approvals[' + i + '][id]', value);
            });

            $.ajax({
                url: baseURL + 'ess/bak/save/approve',
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.sts == 'OK') {
                        swal('Success', data.msg, 'success').then(function () {
                            location.reload();
                        });
                    } else {
                        $("input[name='isproses']").val(0);
                        swal('Error', data.msg, 'error');
                    }
                },
                error: function (data) {
                    $("input[name='isproses']").val(0);
                    swal('Error', 'Server error. Mohon ulangi proses.', 'error');
                }
            });
        } else {
            swal({
                title: "Silahkan tunggu sampai proses selesai.",
                type: 'info'
            });
        }
        return false;
    });

    $(document).on("click", "button[name='btn-disapprove']", function (e) {
        e.preventDefault();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);

            let formData = new FormData();
            $.each($('#table-bak-menunggu').DataTable().column(0).checkboxes.selected(), function (i, value) {
                formData.append('approvals[' + i + '][id]', value);
            });

            $.ajax({
                url: baseURL + 'ess/bak/save/disapprove',
                type: 'POST',
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.sts == 'OK') {
                        swal('Success', data.msg, 'success').then(function () {
                            location.reload();
                        });
                    } else {
                        $("input[name='isproses']").val(0);
                        swal('Error', data.msg, 'error');
                    }
                },
                error: function (data) {
                    $("input[name='isproses']").val(0);
                    swal('Error', 'Server error. Mohon ulangi proses.', 'error');
                }
            });
        } else {
            swal({
                title: "Silahkan tunggu sampai proses selesai.",
                type: 'info'
            });
        }
        return false;
    });

    $(document).on('click', 'button[name="btn-detail-approve"],button[name="btn-detail-disapprove"]',
        function (e) {
            var action = $(this).attr('data-action');
            var modal = $('#modal-detail-bak-persetujuan');

            var isproses = $("input[name='isproses']").val();

            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                let formData = new FormData();

                let id = $('#id_bak', modal).val();

                formData.append('approvals[][id]', id);

                $.ajax({
                    url: baseURL + 'ess/bak/save/' + action,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.sts == 'OK') {
                            modal.modal('hide');
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                        swal('Error', 'Server error. Mohon ulangi proses.', 'error');
                    }
                });
            } else {
                kiranaAlert(false, "Silahkan tunggu sampai proses selesai.", 'info', 'no');
            }
        });
});