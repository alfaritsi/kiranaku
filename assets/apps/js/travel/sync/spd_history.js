/**
 * TODO : 1. tambah validasi tanggal berangkat single & multi trip
 */
$(document).ready(function () {
    const modal_revisi  = $('#modal-spd-revisi');    

    $('#filter-date input', 'form[name="filter-history"]').on('change', function () {
        $('form[name="filter-history"]').attr('action', baseURL + 'travel/sync/data#tab-history');
        $('form[name="filter-history"]').submit();
    });
    
    $('#table-history').DataTable({
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        // 'columnDefs': [
        //     {
        //         'targets': 0,
        //         // 'checkboxes': {
        //         //     'selectRow': true,
        //         //     'selectAllPages': false,
        //         //     'selectCallback': function () {

        //         //     }
        //         // }
        //     }
        // ],
        // 'select': {
        //     'style': 'multi'
        // },
        'order': [[1, 'asc']],
        "oSearch": {"sSearch": $('#table-history').attr('data-search')}
    });

    $(document).on('click', '.spd-revisi-trip', function (e) {
        // console.log('masuk');
        e.preventDefault();
        
        const idHeader  = $(this).data('id');
        KIRANAKU.showLoading();
        $.ajax({
            url: baseURL + 'travel/sync/get/notrip',
            type: 'POST',
            dataType: 'JSON',
            data: {
                idheader : idHeader
            },
            success: function (data) {
                if (data.sts === 'OK') {
                    const {details} = data.data;

                    let template = $('#tujuan_spd_template', modal_revisi).html();
                    // console.log(data);
                    $("#nomor_fieldname").val(data.data);
                    $("#simpan_btn_revisi").attr("data-id" , idHeader);
                    $("#id_hide").val(idHeader);
                    /*
                        aVZZempEelNpMDk4S245SEgvb3hCRjB2RmNFS3lZZzZaTENUUExacytLTT0
                        aVZZempEelNpMDk4S245SEgvb3hCS24wcS9odjVZcWw4TDJpbENuWFZPdz0=
                        aVZZempEelNpMDk4S245SEgvb3hCRm5oaFFTeFNkbW5ORlQyRy9NTFZSdz0
                    */
                    
                    modal_revisi.modal('show');
                } else {
                    KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
                }
                KIRANAKU.hideLoading();
            },
            error: function (data) {
                KIRANAKU.hideLoading();
                KIRANAKU.alert(data.sts, data.msg, 'error', 'no');
            }
        });
    });

    // revisi no trip
    $("#simpan_btn_revisi").on("click", function (e) {
        var id          = $(this).attr("data-id");
        var new_notrip  = $("#nomor_baru_fieldname").val();
        var old_notrip  = $("#nomor_fieldname").val();
        kiranaConfirm(
            {
                title: "Konfirmasi",
                text: "Apakah anda akan merevisi data trip?",
                dangerMode: true,
                successCallback: function () {
                    KIRANAKU.hideLoading();
                    $.ajax({
                        url: baseURL + 'travel/sync/edit/notrip',
                        type: 'POST',
                        dataType: 'JSON',
                        data: {
                            id          : id,
                            nomor       : new_notrip,
                            nomor_ex    : old_notrip
                        },
                        success: function (data) {
                            KIRANAKU.hideLoading();
                            if (data.sts === 'OK') {
                                modal_revisi.modal("hide");
                                kiranaAlert(data.sts, data.msg);
                            } else {
                                kiranaAlert(data.sts, data.msg, 'error', 'no');
                            }
                        },
                        error: function (data) {
                            KIRANAKU.hideLoading();
                            kiranaAlert('notOK', 'Server error. Mohon ulangi proses.', 'error', 'no');
                        }
                    });
                }
            }
        );
    });

       
});