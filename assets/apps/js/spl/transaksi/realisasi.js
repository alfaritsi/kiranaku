$(document).ready(function () {
    getData();

    $(document).on("click", "#confirm-all", function (e) {
        $(".btn-approve-karyawan[data-action='confirm']").each(function (e) {
            $(this).trigger("click");
        });
    });

    $(document).on("click", ".btn-approve-karyawan", function (e) {
        const action = this.dataset.action;
        // const color = (action == "confirm" ? "bg-confirm" : "bg-reject");
        let color = "bg-confirm";
        let status = "ok"
        if (action == "reject") {
            color = "bg-reject";
            status = "no"
        }
        $(this).closest("tr.row-karyawan").find("input[name='status_detail[]']").val(status);
        $(this).closest("tr.row-karyawan")
            .removeClass("bg-confirm")
            .removeClass("bg-reject")
            .addClass(color);
    });

    $(document).on("click", ".btn-edit", function (e) {
        let element = $(this).closest("tr.row-karyawan");
        element.removeClass("bg-confirm bg-reject");
        // element.find("select[name='nik[]']").prop("disabled", false)
        element.find(".input-edit").prop("disabled", false).prop("readonly", false);
        element.find("input[name='status_detail[]']").val('');
    });

    $(document).on('select2:unselecting', "select[name='input_nik[]']", function (e) {
        $(this).closest("tr.row-karyawan").find("input[name='nik[]']").val("");
        $(this).closest("tr.row-karyawan").find("input[name='posisi[]']").val("");
        $(this).closest("tr.row-karyawan").find("input[name='jam_lembur[]']").val("");
        $(this).closest("tr.row-karyawan").find("input[name='menit_lembur[]']").val("");
        $(this).closest("tr.row-karyawan").find("input[name='total_lembur[]']").val("");
    });
    $(document).on('select2:select', "select[name='input_nik[]']", function (e) {
        let elem = $(this).closest("tr.row-karyawan");
        elem.find("input[name='nik[]']").val(e.params.data.nik);
        elem.find("input[name='posisi[]']").val(e.params.data.posst);
        elem.find("input[name='menit_lembur[]']").val(e.params.data.total_lembur_menit);
        elem.find("input[name='jam_lembur[]']").val(e.params.data.lembur_jam + ":" + ('00' + e.params.data.lembur_menit).slice(-2));
        hitungTotalLembur(elem);
    });

    $(document).on("focusout", "input[name='jam_mulai[]'], input[name='jam_selesai[]']", function (e) {
        let elem = $(this).closest("tr.row-karyawan");
        elem.find("input[name='jam_selesai[]']").css("border-color", "#d2d6de");
        elem.find(".help-block").html("");
        const jamMulai = elem.find("input[name='jam_mulai[]']").val();
        const jamSelesai = elem.find("input[name='jam_selesai[]']").val();
        if (jamMulai && jamSelesai) {
            let timeStart = new Date();
            let timeEnd = new Date();
            let valueStart = jamMulai.split(':');
            let valueEnd = jamSelesai.split(':');

            timeStart.setHours(valueStart[0], valueStart[1], '00', 0);
            timeEnd.setHours(valueEnd[0], valueEnd[1], '00', 0);
            let minuteDiff = (timeEnd - timeStart) / 60000;
            // console.log(minuteDiff);
            if (jamSelesai < jamMulai || minuteDiff < 60) {
                elem.find("input[name='jam_selesai[]']").css("border-color", "red");
                elem.find(".help-block").html("Jam Selesai Harus Lebih dari Jam Mulai & Minimal 1 Jam");
                // elem.find("input[name='jam_selesai[]']").val("");
            }
            hitungTotalLembur(elem);
        }
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        const jumlahKaryawan = $("select[name='input_nik[]']").length;
        if (jumlahKaryawan < 1) {
            kiranaAlert("notOK", 'Jumlah Karyawan Tidak Boleh Kosong!', 'error', 'no');
            return false;
        }
        let jumlahKonfirmasi = 0;
        let jumlahKonfirmasiOK = 0;
        $("input[name='status_detail[]']").each(function (e) {
            // console.log($(this).val());
            if ($(this).val())
                jumlahKonfirmasi++;
            if ($(this).val() == "ok")
                jumlahKonfirmasiOK++;
        });
        if (jumlahKaryawan != jumlahKonfirmasi || jumlahKonfirmasiOK == 0) {
            kiranaAlert("notOK", 'Konfirmasi Karyawan Belum Lengkap!', 'error', 'no');
            return false;
        }
        //cek jam lembur tiap karyawan
        let invalidJamLembur = 0;
        $("tr.row-karyawan").each(function () {
            let elem = $(this);
            const jamMulai = elem.find("input[name='jam_mulai[]']").val();
            const jamSelesai = elem.find("input[name='jam_selesai[]']").val();
            if (jamMulai && jamSelesai) {
                let timeStart = new Date();
                let timeEnd = new Date();
                let valueStart = jamMulai.split(':');
                let valueEnd = jamSelesai.split(':');

                timeStart.setHours(valueStart[0], valueStart[1], '00', 0);
                timeEnd.setHours(valueEnd[0], valueEnd[1], '00', 0);
                let minuteDiff = (timeEnd - timeStart) / 60000;
                // console.log(minuteDiff);
                if (jamSelesai < jamMulai || minuteDiff < 60) {
                    elem.find("input[name='jam_selesai[]']").css("border-color", "red");
                    elem.find(".help-block").html("Jam Selesai Harus Lebih dari Jam Mulai & Minimal 1 Jam");
                    // elem.find("input[name='jam_selesai[]']").val("");
                    invalidJamLembur++;
                }
            }
        });
        // console.log(invalidJamLembur);
        if (invalidJamLembur > 0)
            kiranaAlert("notOK", 'Periksa Kembali Jam Lebur Karyawan!', 'error', 'no');
        else
            submitRealisasi();

        e.preventDefault();
        return false;
    });
});

const getData = () => {
    const no_spl = $("#no_spl").val();
    const plant = $("#plant").val();
    $("#list-karyawan").empty();
    $.ajax({
        url: baseURL + "spl/transaksi/get/spl",
        type: "POST",
        dataType: "JSON",
        data: {
            no_spl: no_spl,
            plant: plant,
            return: "json",
            data: "complete",
        },
        beforeSend: function () { },
        success: function (data) {
            if (data) {
                if (data.access != 1)
                    $("#col-action").css("display", "none");

                if (data.detail) {
                    $("#jumlah_orang").val(data.detail.length);
                    $.each(data.detail, function (i, v) {
                        $("#nodata").remove();
                        let outputPengajuan = "";
                        outputPengajuan += '<tr class="row-karyawan-pengajuan">';
                        outputPengajuan += ' <td><input type="text" class="form-control text-center" readonly value="' + (i + 1) + '"></td>';
                        outputPengajuan += ' <td>';
                        outputPengajuan += '     <input type="text" class="form-control" value="' + v.nik + ' - ' + v.nama + '" readonly>';
                        outputPengajuan += ' </td>';
                        outputPengajuan += ' <td><input type="text" class="form-control" value="' + v.posst + '" readonly></td>';
                        outputPengajuan += ' <td><input type="time" class="form-control" value="' + v.jam_mulai_format + '" readonly required></td>';
                        outputPengajuan += ' <td><input type="time" class="form-control" value="' + v.jam_selesai_format + '" readonly required></td>';
                        outputPengajuan += '</tr>';
                        $(outputPengajuan).appendTo("#list-karyawan-pengajuan");

                        let output = "";
                        output += '<tr class="row-karyawan karyawan' + v.no_urut + '">';
                        output += ' <td><input type="text" name="number[]" class="form-control text-center" readonly value="' + (i + 1) + '"></td>';
                        output += ' <td>';
                        output += '     <input type="hidden" class="form-control" name="no_urut[]" value="' + v.no_urut + '" required>';
                        output += '     <input type="hidden" class="form-control" name="nik[]" value="' + v.nik + '" required>';
                        output += '     <select class="form-control input-edit" name="input_nik[]" disabled required></select>';
                        output += ' </td>';
                        output += ' <td><input type="text" class="form-control" name="posisi[]" value="' + v.posst + '" readonly></td>';
                        output += ' <td>';
                        output += '     <input type="hidden" class="form-control" name="menit_lembur[]" value="' + v.total_sebelum_spl_menit + '" readonly>';
                        output += '     <input type="text" class="form-control text-center" name="jam_lembur[]" value="' + v.sebelum_spl_jam + ":" + ('00' + v.sebelum_spl_menit).slice(-2) + '" readonly>';
                        output += ' </td>';
                        output += ' <td><input type="time" class="form-control timepicker" name="jam_mulai[]" value="' + v.jam_mulai_format + '" readonly required></td>';
                        output += ' <td><input type="time" class="form-control timepicker" name="jam_selesai[]" value="' + v.jam_selesai_format + '" readonly required></td>';
                        output += ' <td><input type="text" class="form-control text-center" name="total_lembur[]" value="' + v.sesudah_spl_jam + ":" + ('00' + v.sesudah_spl_menit).slice(-2) + '" readonly></td>';
                        if (data.status == 'finish' && data.access == 1) {
                            output += ' <td>';
                            output += '     <div class="btn-group">';
                            output += '         <input type="hidden" class="form-control" name="status_detail[]" required>';
                            output += '         <button type="button" class="btn btn-sm btn-success btn-approve-karyawan" data-action="confirm"><i class="fa fa-check"></i></button>';
                            output += '         <button type="button" class="btn btn-sm btn-danger btn-approve-karyawan" data-action="reject"><i class="fa fa-remove"></i></button>';
                            output += '         <button type="button" class="btn btn-sm btn-warning btn-edit" data-action="edit"><i class="fa fa-edit"></i></button>';
                            output += '     </div>';
                            output += ' </td>';
                        }
                        output += '</tr>';
                        $(output).appendTo("#list-karyawan");

                        const elem = ".row-karyawan.karyawan" + v.no_urut;

                        master_nik(elem + " select[name='input_nik[]']");
                        let control = $("select[name='input_nik[]']", elem).empty().data("select2");
                        if (v.nik) {
                            let adapter = control.dataAdapter;
                            let desc = `${v.nik} - ${v.nama}`;
                            adapter.addOptions(
                                adapter.convertToOptions([{
                                    id: v.nik,
                                    text: desc,
                                },])
                            );
                            $("select[name='input_nik[]']", elem).trigger("change.select2");
                        }

                        $(elem + " .timepicker").datetimepicker({
                            sideBySide: true,
                            keepOpen: true,
                            format: 'HH:mm'
                        });
                    });
                }

                if (
                    (data.departemen.toLowerCase().includes("pabrik") || data.departemen.toLowerCase().includes("quality"))
                    &&
                    data.rincian_plan_lembur
                    &&
                    data.rincian_plan_lembur.trim() != ''
                ) {
                    $("#rincian_plan").val(data.rincian_plan_lembur);
                    $(".data-master-plan").removeClass("d-none");
                }
            }
        },
        error: function (xhr, status, error) {
            let errorMessage = xhr.status + ': ' + xhr.statusText;
            KIRANAKU.alert({
                text: `Server Error, (${errorMessage})`,
                icon: "error",
                html: false,
                reload: false
            });
        },
        complete: function () {

        }
    });
}

const master_nik = (elem) => {
    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        ajax: {
            url: baseURL + "spl/transaksi/get/karyawan",
            dataType: "json",
            delay: 750,
            cache: false,
            data: function (params) {
                let selected_nik = $("select[name='input_nik[]']").map(function () {
                    if (this.value)
                        return this.value;
                }).get();
                let data = {
                    plant: $("input[name='plant']").val(),
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
                    tanggal_spl: $("input[name='tanggal_spl']").val(),
                    id_departemen: $("input[name='id_departemen']").val(),
                    id_seksie: $("input[name='id_seksie']").val(),
                    in_lini: $("select[name='lini']").val(),
                    not_in_nik: selected_nik,
                    in_golongan: ['NS', 'HR']
                };

                return data;
            },
            processResults: function (data, page) {
                return {
                    results: data.items
                };
            },
            cache: false,
            error: function (xhr, status, error) {
                let errorMessage = xhr.status + ': ' + xhr.statusText;
                KIRANAKU.alert({
                    text: `Server Error, (${errorMessage})`,
                    icon: "error",
                    html: false,
                    reload: false
                });
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            return `<div class="clearfix">${repo.nik} - ${repo.nama}</div>`;
        },
        templateSelection: function (repo) {
            let markup = "Silahkan Pilih";
            if (repo.text && repo.id) return repo.text;
            if (repo.nik)
                markup = `${repo.nik} - ${repo.nama}`;

            return markup;
        }
    });
}

const submitRealisasi = () => {
    const empty_form = validate('#form-spl');

    if (empty_form == 0) {
        let isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            let formData = new FormData($("#form-spl")[0]);

            $.ajax({
                url: baseURL + 'spl/transaksi/save/realisasi',
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.sts == 'OK') {
                        swal('Success', data.msg, 'success').then(function () {
                            location.href = baseURL + 'spl/transaksi/pengajuan';
                            $("input[name='isproses']").val(0);
                        });
                    } else {
                        $("input[name='isproses']").val(0);
                        swal('Error', data.msg, 'error');
                    }
                },
                error: function (data) {
                    $("input[name='isproses']").val(0);
                    kiranaAlert("notOK", 'Server error. Mohon ulangi proses.', 'error', 'no');
                }
            });
        } else {
            swal({
                title: "Silahkan tunggu sampai proses selesai.",
                icon: 'info'
            });
        }
    }
}

const hitungTotalLembur = (elem) => {
    elem.find("input[name='total_lembur[]']").val('');
    const lemburAwal = parseInt(elem.find("input[name='menit_lembur[]']").val());
    const jamMulai = elem.find("input[name='jam_mulai[]']").val();
    const jamSelesai = elem.find("input[name='jam_selesai[]']").val();
    if (jamMulai && jamSelesai) {
        let timeStart = new Date();
        let timeEnd = new Date();
        let valueStart = jamMulai.split(':');
        let valueEnd = jamSelesai.split(':');

        timeStart.setHours(valueStart[0], valueStart[1], '00', 0);
        timeEnd.setHours(valueEnd[0], valueEnd[1], '00', 0);
        let minuteDiff = (timeEnd - timeStart) / 60000;
        const totalLembur = lemburAwal + minuteDiff;
        const hours = Math.floor(totalLembur / 60);
        const minutes = totalLembur % 60;
        elem.find("input[name='total_lembur[]']").val(hours + ":" + ('00' + minutes).slice(-2));
    }
}