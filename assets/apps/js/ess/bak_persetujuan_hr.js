$(document).ready(function () {

    let catatans = [];
    let files = [];

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
                $('#absen_masuk .jam', modal).html(data.data.absen_masuk);
                $('#absen_masuk .tanggal', modal).html(
                    KIRANAKU.isNullOrEmpty(
                        data.data.tanggal_masuk,
                        moment(data.data.tanggal_masuk).format('DD.MM.YYYY'),
                        ''
                    )
                );
                $('#absen_keluar .jam', modal).html(data.data.absen_keluar);
                $('#absen_keluar .tanggal', modal).html(
                    KIRANAKU.isNullOrEmpty(
                        data.data.tanggal_keluar,
                        moment(data.data.tanggal_keluar).format('DD.MM.YYYY'),
                        ''
                    )
                );
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
        let defFile = files[id];
        if (typeof defText === "undefined")
            defText = "";
        if (typeof defFile === "undefined")
            defFile = null;

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
                    } else if (text == "") {
                        catatans[id] = text;
                    }
                }
            }, {
                title: 'Masukkan file bukti atasan',
                input: 'file',
                // inputPlaceholder: 'Ketik catatan anda untuk pengajuan cuti ini.',
                inputValue: defFile,
                showCancelButton: true,
                showLoaderOnConfirm: true,
                preConfirm: (file) => {
                    if (file != null) {
                        files[id] = file;
                        thisBtn.removeClass('btn-default');
                        thisBtn.addClass('btn-success');
                        thisBtn.find('span').html('Terisi');
                        if (!thisBtn.parents('tr').find('.dt-body-center input[type="checkbox"]').prop('checked'))
                            thisBtn.parents('tr').find('.dt-body-center input[type="checkbox"]').trigger('click');
                    } else {
                        files[id] = null;
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
        'order': [[1, 'asc']]
    });

    $(document).on("click", "button[name='btn-approve']", function (e) {
        e.preventDefault();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);

            let isFilesFull = true;

            let formData = new FormData();
            $.each($('#table-bak-menunggu').DataTable().column(0).checkboxes.selected(), function (i, value) {
                if (files[value]) {
                    let id = value;

                    formData.append('approvals['+i+'][id]', id);
                    formData.append('approvals['+i+'][catatan]', catatans[value]);
                    formData.append(id, files[value]);
                }
                else
                    isFilesFull = false;
            });

            if (!isFilesFull) {
                $("input[name='isproses']").val(0);
                kiranaAlert("NotOK", "Ada persetujuan yang belum di isi file buktinya.", "warning");
                return;
            }

            $.ajax({
                url: baseURL + 'ess/bak/save/approve_hr',
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

            let isCatatanFull = true;

            let formData = new FormData();
            $.each($('#table-bak-menunggu').DataTable().column(0).checkboxes.selected(), function (i, value) {
                if (catatans[value]) {
                    let id = value;

                    formData.append('approvals['+i+'][id]', id);
                    formData.append('approvals['+i+'][catatan]', catatans[value]);
                    formData.append(id, files[value]);
                }
                else
                    isCatatanFull = false;
            });

            if (!isCatatanFull) {
                $("input[name='isproses']").val(0);
                kiranaAlert("NotOK", "Ada persetujuan yang belum di isi catatannya. Harap isi catatan.", "warning");
                return;
            }

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

                if (action == "disapprove" && $('#icatatan', modal).val() == "") {
                    $("input[name='isproses']").val(0);
                    kiranaAlert("NotOK", "Harap isi catatan.", "warning");
                    return;
                }

                let chosenCatatans = [];

                let formData = new FormData();

                let id = $('#id_bak', modal).val();

                formData.append('approvals[][id]', id);
                formData.append('approvals[][catatan]', $('#icatatan', modal).val());
                $.each($('#ifile', modal)[0].files, function (i, file) {
                    formData.append(id, file);
                });

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