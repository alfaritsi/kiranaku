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
                $('#nik', modal).html(data.data.nik);
                $('#nama_karyawan', modal).html(data.data.nama_karyawan);
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
                    moment(data.data.tanggal_buat).format('DD.MM.YYYY')
                );
                $('#alasan', modal).html(data.data.alasan);
                $('#keterangan', modal).html(data.data.keterangan);
                $('#status', modal).html(data.data.nama_status);

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
        if (typeof defText === "undefined")
            defText = "";

        swal.queue([
            {
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
            }
        ]);
    });

    $('#table-bak-menunggu').DataTable({
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        columnDefs: [
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
        "oSearch": {"sSearch": $('#table-bak-menunggu').attr('data-search')}
    });

    var tableHistory = $('#table-bak-history').DataTable({
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        'order': [[1, 'asc']],
        "oSearch": {"sSearch": $('#table-bak-history').attr('data-search')}
    });

    $(document).on("click", "button[name='btn-approve']", function (e) {
        e.preventDefault();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);

            let chosenBAKs = [];
            $.each($('#table-bak-menunggu').DataTable().column(0).checkboxes.selected(), function (i, value) {
                chosenBAKs.push({
                    id: value
                });
            });

            $.ajax({
                url: baseURL + 'ess/bak/save/approve',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    approvals: JSON.stringify(chosenBAKs)
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

            let chosenBAKs = [];
            let isCatatanFull = true;
            $.each($('#table-bak-menunggu').DataTable().column(0).checkboxes.selected(), function (i, value) {
                if (catatans[value])
                    chosenBAKs.push({
                        id: value,
                        catatan: catatans[value]
                    });
                else
                    isCatatanFull = false;
            });

            if (!isCatatanFull) {
                $("input[name='isproses']").val(0);
                kiranaAlert("NotOK", "Ada penolakan yang belum di isi catatannya. Harap isi catatan.", "warning");
                return;
            }

            $.ajax({
                url: baseURL + 'ess/bak/save/disapprove',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    approvals: JSON.stringify(chosenBAKs)
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

    $(document).on('click', 'button[name="btn-detail-approve"],button[name="btn-detail-disapprove"]',
        function (e) {
            e.preventDefault();
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

                chosenCatatans.push({
                    id: $('#id_bak', modal).val(),
                    catatan: $('#icatatan', modal).val()
                });

                $.ajax({
                    url: baseURL + 'ess/bak/save/' + action,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        approvals: JSON.stringify(chosenCatatans)
                    },
                    success: function (data) {
                        if (data.sts == 'OK') {
                            // modal.modal('hide');
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

    $(document).on('click', '.approval', function (e) {
        e.preventDefault();

        let thisBtn = $(this);
        let id = thisBtn.attr('data-catatan');
        let data = thisBtn.attr('data-data');
        let action = thisBtn.attr('data-action');
        // let template = $('#template-catatan').clone();
        // data = JSON.parse(data);

        let defText = catatans[id];

        if (typeof defText === "undefined")
            defText = "";

        swal.queue([{
            // html: template.html(),
            input: 'textarea',
            inputPlaceholder: 'Ketik catatan anda untuk pengajuan bak ini.',
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
                        url: baseURL + 'ess/bak/save/' + action,
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


    filterStatusHistory($('#status').val());

    $('#status').on('change', function () {
        filterStatusHistory($(this).val());
    });

    function filterStatusHistory(val = '') {
        if (val === 'Semua')
        {
            val = '';
            tableHistory.search('')
        }

        tableHistory.columns(9)
            .search(val)
            .draw(false);
    }
});