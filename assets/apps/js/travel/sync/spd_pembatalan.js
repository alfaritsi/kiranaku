/**
 * TODO : 1. tambah validasi tanggal berangkat single & multi trip
 */
$(document).ready(function () {
    // const modalPengajuan = $('#modal-spd-pengajuan');
    /** Datatable related */
    
    $('#table-declare').DataTable({
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
        "oSearch": {"sSearch": $('#table-declare').attr('data-search')}
    });

    $(document).on("click", "#btn-sync-cancelation", function (e) {
        // console.log('masuk');
        e.preventDefault();
        var isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            // $("input[name='isproses']").val(1);

            let chosenCatatans = [];
            $.each($('#table-cancel').DataTable().column(0).checkboxes.selected(), function (i, value) {
                chosenCatatans.push({
                    id: value,
                    // catatan: catatans[value]
                });
            });
            if(chosenCatatans == "") {
                swal({
                    title: "Tidak ada data yang disinkronisasi.",
                    type: 'info'
                }); 
            } else {
                $.ajax({
                    url: baseURL + 'travel/sync/save/approve_cancel',
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

    
});