$(document).ready(function () {

    $('#filter-date input, select', 'form[name="filter-history"]').on('change', function () {
        $('form[name="filter-history"]').attr('action', baseURL + 'ess/cutiijin/persetujuan#tab-history');
        $('form[name="filter-history"]').submit();
    });

    $('#modal-detail-persetujuan').on('hide.bs.modal',function(e){
        $('#datepicker-detail',  $('#modal-detail-persetujuan')).datepicker('destroy');
    });

    $(document).on('click', '.detail-persetujuan', function (e) {
        let id = $(this).attr('data-detail');
        let showSaldo = $(this).attr('data-saldo');
        let sisa = 0;
        if (typeof showSaldo === "undefined")
            showSaldo = false;

        let modal = $('#modal-detail-persetujuan');
        $.ajax({
            url: baseURL + 'ess/cutiijin/get/pengajuan',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id
            },
            success: function (data) {
                let arTanggalCuti = [];
                for (let tgl of data.tanggal) {
                    arTanggalCuti.push(moment(tgl));
                }
                let arTanggalCutiDate = [];
                let jumlah = 0;
                for (let tgl of arTanggalCuti) {

                    arTanggalCutiDate.push(tgl.toDate());
                }

                sisa = parseInt(data.saldo.sisa);

                $('#datepicker-detail', modal).datepicker({
                    multidate: true,
                    // daysOfWeekDisabled: [0, 1, 2, 3, 4, 5, 6],
                    startDate: moment.min(arTanggalCuti).toDate(),
                    endDate: moment.max(arTanggalCuti).toDate(),
                    defaultViewDate: moment.min(arTanggalCuti).toDate(),
                    maxViewMode: 0,
                    beforeShowDay: function (date) {
                        let curDate = moment(date).format('YYYY-MM-DD');
                        let libur = data.tanggal_libur.indexOf(curDate) >= 0;
                        let active = arTanggalCuti.indexOf(curDate) >= 0;
                        return {
                            enabled: !libur,
                            classes: libur && !active ? 'weekend' : ''
                        };
                    }
                });
                $('#id_cuti', modal).val(id);
                $('#inik', modal).html(data.detail.nik);
                $('#inama_karyawan', modal).html(data.detail.nama_karyawan);
                if (data.detail.form == "Cuti")
                    $('#row-ijenis', modal).addClass('hide');
                else {
                    $('#row-ijenis', modal).removeClass('hide');
                    $('#inama_jenis', modal).html(data.detail.nama_jenis);
                }
                $('.modal-title span', modal).html(data.detail.form);
                $('#ijumlah', modal).html(data.detail.jumlah + ' Hari');
                jumlah = parseInt(data.detail.jumlah);
                sisa = data.saldo.sisa;
                // if (data.saldo.sisa <= 0)
                //     sisa = Math.abs(data.saldo.negatif - data.saldo.sisa);
                // else
                //     sisa = data.saldo.sisa;
                $('#ialasan', modal).html(data.detail.alasan);
                $('#icatatan', modal).val(data.detail.catatan);

                if (KIRANAKU.isNullOrEmpty(data.detail.gambar, true, false)) {
                    $('#div-detail-lampiran', modal).removeClass('hide');
                    $('#ilampiran a', modal).attr('href', data.detail.gambar);

                } else {
                    $('#div-detail-lampiran', modal).addClass('hide');
                }

                if (showSaldo) {
                    $('#row-isaldo', modal).removeClass('hide');
                    $('#isaldo', modal).html(sisa + " Hari");
                } else {
                    $('#row-isaldo', modal).addClass('hide');
                }

                console.log(arTanggalCutiDate);
                $('#datepicker-detail', modal).datepicker('setDates', arTanggalCutiDate);

                modal.modal('show');
            },
            error: function (data) {
                kiranaAlert(false, 'Server error. Mohon ulangi proses.', 'error', 'no');
            }
        });
    });

    $(document).on('hide.bs.modal', '#modal-detail-persetujuan', function () {
        $('#datepicker-detail',this).datepicker('remove');
    });

    let catatans = [];

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

        swal.queue([{
            html: template.html(),
            input: 'textarea',
            inputPlaceholder: 'Ketik catatan anda untuk pengajuan cuti ini.',
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
        }])
    });

    $('#table-persetujuan-cuti').DataTable({
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
        'order': [[1, 'asc']],
        "oSearch": {"sSearch": $('#table-persetujuan-cuti').attr('data-search')}
    });

    $('#table-persetujuan-ijin').DataTable({
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        'order': [[1, 'asc']],
        "oSearch": {"sSearch": $('#table-persetujuan-ijin').attr('data-search')}
    });

    $(document).on("click", "button[name='btn-approve']", function (e) {
        e.preventDefault();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);

            let chosenCatatans = [];
            $.each($('#table-persetujuan-cuti').DataTable().column(0).checkboxes.selected(), function (i, value) {
                chosenCatatans.push({
                    id: value,
                    catatan: catatans[value]
                });
            });

            $.ajax({
                url: baseURL + 'ess/cutiijin/save/approve',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    approvals: JSON.stringify(chosenCatatans)
                },
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

            let chosenCatatans = [];
            let isCatatanFull = true;
            $.each($('#table-persetujuan-cuti').DataTable().column(0).checkboxes.selected(), function (i, value) {
                if (catatans[value])
                    chosenCatatans.push({
                        id: value,
                        catatan: catatans[value]
                    });
                else
                    isCatatanFull = false;
            });

            if (!isCatatanFull) {
                $("input[name='isproses']").val(0);
                kiranaAlert("NotOK", "Ada persetujuan yang belum di isi catatannya. Harap isi catatan.", "warning");
                return;
            }

            $.ajax({
                url: baseURL + 'ess/cutiijin/save/disapprove',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    approvals: JSON.stringify(chosenCatatans)
                },
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

    $(document).on('click', '.approval', function (e) {
        e.preventDefault();

        let thisBtn = $(this);
        let id = thisBtn.attr('data-catatan');
        let data = thisBtn.attr('data-data');
        let action = thisBtn.attr('data-action');
        let template = $('#template-catatan').clone();
        data = JSON.parse(data);
        template.find('.iperson').html(data.person);
        template.find('.ijenis').html(data.jenis);
        template.find('.itanggal').html(data.tanggal);

        let defText = catatans[id];

        if (typeof defText === "undefined")
            defText = "";

        swal.queue([{
            html: template.html(),
            input: 'textarea',
            inputPlaceholder: 'Ketik catatan anda untuk pengajuan ijin ini.',
            inputValue: defText,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: (text) => {
                let canSave = false;

                if (text) {
                    canSave = true;
                } else if (text == "") {
                    if (action == "approve")
                        canSave = true;
                    else {
                        swal.insertQueueStep({
                            type: 'warning',
                            title: 'Harap isi catatan'
                        })
                    }
                }
                return {canSave: canSave, text: text};
            }
        }]).then((result) => {
            if (result.value) {
                if (result.value[0].canSave)
                    $.ajax({
                        url: baseURL + 'ess/cutiijin/save/' + action,
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            approvals: JSON.stringify([
                                {
                                    id: id,
                                    catatan: result.value[0].text
                                }
                            ])
                        },
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
                        error: function (data) {
                            $("input[name='isproses']").val(0);
                            swal('Error', 'Server error. Mohon ulangi proses.', 'error');
                        }
                    });
            }
        });
    });

    $(document).on('click', 'button[name="btn-detail-approve"],button[name="btn-detail-disapprove"]',
        function (e) {
            var action = $(this).attr('data-action');
            var modal = $('#modal-detail-persetujuan');

            var isproses = $("input[name='isproses']").val();

            if (isproses == 0) {
                $("input[name='isproses']").val(1);

                if (action == "disapprove" && $('#icatatan', modal).val() == "") {
                    $("input[name='isproses']").val(0);
                    kiranaAlert("NotOK", "Harap isi catatan.", "warning");
                    return;
                }

                let chosenCatatans = [];

                chosenCatatans.push({
                    id: $('#id_cuti', modal).val(),
                    catatan: $('#icatatan', modal).val()
                });

                $.ajax({
                    url: baseURL + 'ess/cutiijin/save/' + action,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        approvals: JSON.stringify(chosenCatatans)
                    },
                    success: function (data) {
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