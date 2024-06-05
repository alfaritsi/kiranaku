/**
 * TODO : 1. tambah validasi tanggal berangkat single & multi trip
 */
$(document).ready(function () {
    // const modalPengajuan = $('#modal-spd-pengajuan');
    // /** Datatable related */
    // const multiTripTable = $('#table-multi-trip', modalPengajuan).DataTable({
    //     "searching": false,
    //     "paging": false,
    //     "ordering": false,
    //     "info": false
    // });
    // const uangmukaTable = $('#table-uangmuka', modalPengajuan).DataTable({
    //     "searching": false,
    //     "paging": false,
    //     "ordering": false,
    //     "info": false
    // });

    const datetimepickerOptions = {
        // minDate: moment(),

        useCurrent: false,
        format: 'DD.MM.YYYY HH:mm',
        showTodayButton: true,
        sideBySide: true,
        ignoreReadonly: true,

        showClose: true,
        showClear: true,
        toolbarPlacement: 'top',

        widgetPositioning: {
            horizontal: 'left',
            vertical: 'top'
        },
        disabledDates: tanggal_travels,
        // debug: true
    };

    $('#table-pengajuan').DataTable({
        ordering: false,
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
        "oSearch": {"sSearch": $('#table-pengajuan').attr('data-search')}
    });


    $(document).on("click", "#btn-sync-spd", function (e) {
        // console.log('masuk');
        e.preventDefault();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            // $("input[name='isproses']").val(1);

            let chosenCatatans = [];
            $.each($('#table-pengajuan').DataTable().column(0).checkboxes.selected(), function (i, value) {
                chosenCatatans.push({
                    id: value,
                    // catatan: catatans[value]
                });
            });
            console.log(JSON.stringify(chosenCatatans));
            if(chosenCatatans == "") {
                swal({
                    title: "Tidak ada data yang disinkronisasi.",
                    type: 'info'
                }); 
            } else {
                
                $.ajax({
                    url: baseURL + 'travel/sync/save/approve_pengajuan',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        approvals: JSON.stringify(chosenCatatans)
                    },
                    beforeSend: function () {
                        var overlay = "<div class='overlay'><i class='fa fa-refresh fa-spin'></i></div>";
                        $("body .overlay-wrapper").append(overlay);
                    },
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                            location.reload();
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    },
                    error: function (data) {
                        $("input[name='isproses']").val(0);
                        swal('Error', 'Server error. Mohon ulangi proses.', 'error');
                    },
                    complete: function(data){                               
                        //proces spiner
                        $("body .overlay-wrapper .overlay").remove();
                                                
                    }
                });
            }
        } else {
            swal({
                title: "Silahkan tunggu sampai proses selesai.",
                type: 'info'
            });
        }
        return false;
    });

    //export to excel
    $(document).on('click', '#excel_button_pengajuan', function (e) {
        var filter_request_status   = $("#filter_request_status").val();
        var filter_status           = $("#filter_status").val();
        var filter_from             = $("#filter_from").val();
        var filter_to               = $("#filter_to").val();
        var jenis_export            = $(this).data("export");
        // console.log(id_header);
        e.preventDefault();
        window.open(
            baseURL + 'travel/sync/excel/'+jenis_export
        );
    });

    //export to excel
    $(document).on('click', '#excel_button_deklarasi', function (e) {
        var filter_request_status   = $("#filter_request_status").val();
        var filter_status           = $("#filter_status").val();
        var filter_from             = $("#filter_from").val();
        var filter_to               = $("#filter_to").val();
        var jenis_export            = $(this).data("export");
        // console.log(id_header);
        e.preventDefault();
        window.open(
            baseURL + 'travel/sync/excel/'+jenis_export
        );
    });

});