$(document).ready(function() {
    //=======FILTER=======//
    $("#bulan").datepicker().on("changeDate", function() {
        var bulan = $("#bulan").val();
        $.ajax({
            url: baseURL + 'pcs/transaksi/listrik_filter',
            type: 'POST',
            dataType: 'JSON',
            data: {
                bulan: bulan
            },
            success: function(data) {
                var t = $('.my-datatable-extends-order').DataTable();
                t.clear().draw();
                $.each(data, function(i, v) {
                    if (v.lwbp != null) {
                        var inp_lwbp = "<input type='text' value='" + numberWithCommas(parseFloat(v.lwbp)) + "' class='form-control angka nilai_lwbp' data-plant='" + v.plant + "' data-bulan='" + v.bulan + "'>";
                    } else {
                        var inp_lwbp = "<input type='text' value='' class='form-control angka nilai_lwbp' data-plant='" + v.plant + "' data-bulan='" + v.bulan + "'>";
                    }
                    if (v.wbp != null) {
                        var inp_wbp = "<input type='text' value='" + numberWithCommas(parseFloat(v.wbp)) + "' class='form-control angka nilai_wbp' data-plant='" + v.plant + "' data-bulan='" + v.bulan + "'>";
                    } else {
                        var inp_wbp = "<input type='text' value='' class='form-control angka nilai_wbp' data-plant='" + v.plant + "' data-bulan='" + v.bulan + "'>";
                    }
                    var list = [
                        v.plant,
                        v.plant_name,
                        v.bulan,
                        inp_lwbp,
                        inp_wbp,
                    ];
                    //generate rows
                    t.row.add(list).draw(false);
                });

            }
        });
    });

    $('.my-datatable-extends-order').DataTable({
        ordering: true,
        scrollCollapse: true,
        scrollY: false,
        scrollX: true,
        bautoWidth: false,
        paging: false
    });

    $(document).on("change", ".nilai_lwbp", function() {
        var nilai = $(this).val();
        var plant = $(this).data("plant");
        var bulan = $(this).data("bulan");

        $.ajax({
            url: baseURL + 'pcs/transaksi/save/listrik_lwbp',
            type: 'POST',
            dataType: 'JSON',
            data: {
                plant: plant,
                bulan: bulan,
                nilai: nilai
            },
            success: function(data) {
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg, "success", "no");
                } else {
                    kiranaAlert(data.sts, data.msg, "error", "no");
                }
            }
        });
    });
    $(document).on("change", ".nilai_wbp", function() {
        var nilai = $(this).val();
        var plant = $(this).data("plant");
        var bulan = $(this).data("bulan");
        $.ajax({
            url: baseURL + 'pcs/transaksi/save/listrik_wbp',
            type: 'POST',
            dataType: 'JSON',
            data: {
                plant: plant,
                bulan: bulan,
                nilai: nilai
            },
            success: function(data) {
                if (data.sts == 'OK') {
                    kiranaAlert(data.sts, data.msg, "success", "no");
                } else {
                    kiranaAlert(data.sts, data.msg, "error", "no");
                }
            }
        });
    });

});
// function save_nilai(plant,bulan,nilai,jenis){
// alert('aaa');
// var nilai = nilai.value;
// $.ajax({
// url: baseURL+'pcs/transaksi/save/listrik',
// type: 'POST',
// dataType: 'JSON',
// data: {
// plant 		: plant,
// bulan 		: bulan,
// nilai		: nilai,
// jenis		: jenis
// },
// success: function(data){
// console.log(data);
// if(data.sts == 'OK'){
// // alert(data.msg);
// // location.reload();
// }else{
// alert(data.msg);
// }
// }
// });
// }