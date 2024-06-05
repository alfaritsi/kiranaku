let counterList = 1;
$(document).ready(function () {
    $(document).on('changeDate', "input[name='tanggal_spl']", function (e) {
        let selected = $(this).val();
        if (selected) {
            // selected = selected.split(".");
            // const date1 = new Date();
            // const date2 = new Date(selected[2], selected[1] - 1, selected[0]);
            // const diffTime = date2 - date1;
            // const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const diffDays = hitungSelisihHari();
            let plan = (diffDays > 2) ? "PLAN" : "UNPLAN";
            $("#plan").val(plan);
            if (diffDays < 0) {
                kiranaAlert("notOK", "Anda membuat SPL backdate. Harap menyertakan lampiran BA.", "warning", "no");
                $(".data-ba").removeClass("d-none");
                $(".input-ba").prop("required", true);
            } else {
                $(".data-ba").addClass("d-none");
                $(".input-ba").val("");
                $(".input-ba").prop("required", false);
            }
            clearListKaryawan();
            cekMasterPlan();
        }
    });

    $(document).on('change', "select[name='id_departemen']", function (e) {
        $("#id_seksie").val('').trigger('change');
        generateSeksie();
        cekAlasanLembur();
    });

    $(document).on('change', "select[name='id_seksie']", function (e) {
        generateUnit();
        clearListKaryawan();
        cekMasterPlan();
    });

    $(document).on('change', "select[name='keterangan_lembur']", function (e) {
        // const checkMaster = $(this).find(':selected').data('check_master');
        // const isPlanFo = $("#is_plan_fo").val();
        // if (checkMaster == 1 && isPlanFo != 1) {
        //     console.log("Alasan Tidak Valid");
        //     $(this).val("").trigger("change");
        // }
        cekAlasanLembur();
    });

    $(document).on('click', ".add_karyawan", function (e) {
        const id_seksie = $("#id_seksie").val();
        const seksiRequired = $("#id_seksie").prop("required");
        if (id_seksie || !seksiRequired) {
            addListKaryawan();
            cekListKaryawan();
        } else {
            kiranaAlert("notOK", "Pilih Seksie Terlebih Dahulu", "warning", "no");
        }
    });

    $(document).on("click", ".remove_karyawan", function (e) {
        $(this).closest("tr.row-karyawan").remove();
        generateRowNumber();
        cekListKaryawan();
    });

    // master_seksie("select[name='id_seksie']");

    master_nik("select[name='nik[]']");
    $(document).on('select2:unselecting', "select[name='nik[]']", function (e) {
        $(this).closest("tr.row-karyawan").find("input[name='posisi[]']").val("");
        $(this).closest("tr.row-karyawan").find("input[name='jam_lembur[]']").val("");
        $(this).closest("tr.row-karyawan").find("input[name='menit_lembur[]']").val("");
        $(this).closest("tr.row-karyawan").find("input[name='total_lembur[]']").val("");
    });
    $(document).on('select2:select', "select[name='nik[]']", function (e) {
        const elem = $(this).closest("tr.row-karyawan");
        elem.find("input[name='posisi[]']").val(e.params.data.posst);
        elem.find("input[name='menit_lembur[]']").val(e.params.data.total_lembur_menit);
        elem.find("input[name='jam_lembur[]']").val(e.params.data.lembur_jam + ":" + ('00' + e.params.data.lembur_menit).slice(-2));
        hitungTotalLembur(elem);
    });

    $(".timepicker").datetimepicker({
        sideBySide: true,
        keepOpen: true,
        format: 'HH:mm',
        // stepping: 30
    });

    $(document).on("click", "button[name='action_btn']", function (e) {
        const jumlahKaryawan = $("select[name='nik[]']").length;
        if (jumlahKaryawan < 1) {
            kiranaAlert("notOK", 'Jumlah Karyawan Tidak Boleh Kosong!', 'error', 'no');
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
            submitSpl();
        e.preventDefault();
        return false;
    });

    $(document).on("focusout", "#jam_mulai, #jam_selesai", function (e) {
        const field = this.id;
        $("#jam_selesai").css("border-color", "#d2d6de");
        let elem = $(this).closest("div.form-group");
        elem.find(".help-block").html("");
        const jamMulai = $("#jam_mulai").val();
        const jamSelesai = $("#jam_selesai").val();
        if (jamMulai && jamSelesai) {
            // const timeStart = new Date("01/01/2007 " + jamMulai).getHours();
            // const timeEnd = new Date("01/01/2007 " + jamSelesai).getHours();

            // let hourDiff = timeEnd - timeStart;

            let time_start = new Date();
            let time_end = new Date();
            let value_start = jamMulai.split(':');
            let value_end = jamSelesai.split(':');

            time_start.setHours(value_start[0], value_start[1], '00', 0);
            time_end.setHours(value_end[0], value_end[1], '00', 0);
            let minuteDiff = (time_end - time_start) / 60000;
            // console.log(minuteDiff);
            if (jamSelesai < jamMulai || minuteDiff < 60) {
                // console.log("jam tidak valid");
                $("#jam_selesai").css("border-color", "red");
                elem.find(".help-block").html("Jam Selesai Harus Lebih dari Jam Mulai & Minimal 1 Jam");
                // $("#jam_selesai").val("");
            }
        }
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

    const today = new Date();
    let day = today.getDay();
    let startdate = (day == 1 ? '-2d' : '-1d');
    $("#tanggal_spl").datepicker({
        startDate: startdate,
        endDate: ($(this).data("enddate") != null ? $(this).data("enddate") : ''),
        todayHighlight: true,
        disableTouchKeyboard: true,
        format: ($(this).data("format") != null ? $(this).data("format") : "dd.mm.yyyy"),
        startView: ($(this).data("startview") != null ? $(this).data("startview") : "days"),
        minViewMode: ($(this).data("minviewmode") != null ? $(this).data("minviewmode") : "days"),
        autoclose: true
    });
});

const addListKaryawan = () => {
    counterList++;
    let elem = ".row-karyawan.karyawan" + counterList;

    let output = `<tr class="row-karyawan karyawan${counterList}">
        <td>
            <input type="text" name="number[]" class="form-control text-center" readonly>
        </td>
        <td>
            <select class="form-control" name="nik[]" required></select>
        </td>
        <td>
            <input type="text" class="form-control" name="posisi[]" readonly required>
        </td>
        <td>
            <input type="hidden" class="form-control" name="menit_lembur[]" readonly>
            <input type="text" class="form-control text-center" name="jam_lembur[]" readonly>
        </td>
        <td>
            <input type="time" class="form-control timepicker" name="jam_mulai[]" required>
        </td>
        <td>
            <input type="time" class="form-control timepicker" name="jam_selesai[]" required>
            <span class="help-block" style="color: red;"></span>
        </td>
        <td>
            <input type="text" class="form-control text-center" name="total_lembur[]" readonly>
        </td>
        <td><button type="button" class="btn btn-sm btn-danger remove_karyawan" title="Remove"><i class="fa fa-trash"></i></button></td>
    </tr>`;
    $("#list-karyawan").append(output);
    master_nik(elem + " select[name='nik[]']");
    $(".timepicker").datetimepicker({
        sideBySide: true,
        keepOpen: true,
        format: 'HH:mm'
    });
    $(elem + " input[name='jam_mulai[]']").val($("#jam_mulai").val());
    $(elem + " input[name='jam_selesai[]']").val($("#jam_selesai").val());

    generateRowNumber();
}

const master_seksie = (elem) => {
    $(elem).select2({
        allowClear: true,
        placeholder: {
            id: "",
            text: "Silahkan Pilih"
        },
        ajax: {
            url: baseURL + "spl/master/get/seksie",
            dataType: "json",
            delay: 750,
            cache: false,
            data: function (params) {
                let data = {
                    plant: $("input[name='plant']").val(),
                    id_departemen: $("select[name='id_departemen']").val(),
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
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
        minimumInputLength: 3,
        templateResult: function (repo) {
            if (repo.loading) return repo.text;
            return `<div class="clearfix">${repo.seksie}</div>`;
        },
        templateSelection: function (repo) {
            let markup = "Silahkan Pilih";
            if (repo.text && repo.id) return repo.text;
            if (repo.id_seksie)
                markup = `${repo.seksie}`;

            return markup;
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
                let selected_nik = $("select[name='nik[]']").map(function () {
                    if (this.value)
                        return this.value;
                }).get();
                let data = {
                    plant: $("input[name='plant']").val(),
                    search: params.term, // search term
                    return: "autocomplete",
                    page: params.page,
                    tanggal_spl: $("input[name='tanggal_spl']").val(),
                    id_departemen: $("select[name='id_departemen']").val(),
                    id_seksie: $("select[name='id_seksie']").val(),
                    in_unit: $("select[name='id_unit']").val(),
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

const generateSeksie = () => {
    $("#id_seksie").empty();
    $("#id_seksie").append(`<option value=""></option>`)
    const id_departemen = $("#id_departemen").val();
    const departemen = $("#id_departemen option:selected").text();
    if (id_departemen) {
        $.ajax({
            url: baseURL + 'spl/master/get/seksie',
            type: 'POST',
            dataType: 'JSON',
            data: {
                plant: $("input[name='plant']").val(),
                id_departemen: id_departemen,
                return: 'json',
            },
            success: function (data) {
                if (data) {
                    $.each(data, function (i, v) {
                        $("#id_seksie").append(`<option value="${v.id}">${v.nama}</option>`);
                    });
                    if (data.length < 1 && departemen.toLowerCase().includes("depo"))
                        $("#id_seksie").prop("required", false);
                    else
                        $("#id_seksie").prop("required", true);
                }
            }
        });
    }
}

const generateUnit = () => {
    $("#id_unit").empty();
    const id_seksie = $("#id_seksie").val();
    if (id_seksie) {
        $.ajax({
            url: baseURL + 'spl/master/get/unit',
            type: 'POST',
            dataType: 'JSON',
            data: {
                plant: $("input[name='plant']").val(),
                id_seksie: id_seksie,
                return: 'json',
            },
            success: function (data) {
                if (data) {
                    $.each(data, function (i, v) {
                        $("#id_unit").append(`<option value="${v.id}">${v.nama}</option>`);
                    })
                }
            }
        });
    }
}

const generateRowNumber = () => {
    let renum = 1;
    $("input[name='number[]']").each(function () {
        $(this).val(renum);
        renum++;
    });
}

const cekListKaryawan = () => {
    if (!$("#list-karyawan tr.row-karyawan").length)
        $("#no-data-karyawan").removeClass('d-none');
    else
        $("#no-data-karyawan").addClass('d-none');
}

const clearListKaryawan = () => {
    $('#list-karyawan tr.row-karyawan').remove();
    cekListKaryawan();
}

const submitSpl = () => {
    const empty_form = validate('#form-spl');

    if (empty_form == 0) {
        let isproses = $("input[name='isproses']").val();
        if (isproses == 0) {
            $("input[name='isproses']").val(1);
            let formData = new FormData($("#form-spl")[0]);

            $.ajax({
                url: baseURL + 'spl/transaksi/save/pengajuan',
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
        // console.log(lemburAwal);
        // console.log(minuteDiff);
        const totalLembur = lemburAwal + minuteDiff;
        // console.log(totalLembur);
        const hours = Math.floor(totalLembur / 60);
        const minutes = totalLembur % 60;
        elem.find("input[name='total_lembur[]']").val(hours + ":" + ('00' + minutes).slice(-2));
    }
}

const cekMasterPlan = () => {
    // $("#plan").attr('rows', 1);
    $(".data-master-plan").addClass("d-none");
    $("#rincian_plan").val("");
    $("input[name='is_plan_fo']").val(0);
    const tanggal_spl = $("input[name='tanggal_spl']").val();
    const id_departemen = $("#id_departemen").val();
    const departemen = $("#id_departemen option:selected").text();
    const id_seksie = $("#id_seksie").val();
    const seksie = $("#id_seksie option:selected").text();
    // console.log(seksie);
    // console.log(seksie.toLowerCase().includes("produksi"));
    const checkMaster = $("select[name='keterangan_lembur']").find(':selected').data('check_master');
    if (id_departemen && id_seksie && tanggal_spl) {
        // let selectedDate = tanggal_spl.split(".");
        // const date1 = new Date();
        // const date2 = new Date(selectedDate[2], selectedDate[1] - 1, selectedDate[0]);
        // const diffTime = date2 - date1;
        const diffDays = hitungSelisihHari();
        // if (diffDays < 0) {
        //     kiranaAlert("notOK", "Anda membuat SPL backdate. Harap menyertakan lampiran BA.", "warning", "no");
        //     $(".data-ba").removeClass("d-none");
        //     $(".input-ba").prop("required", true);
        // } else {
        //     $(".data-ba").addClass("d-none");
        //     $(".input-ba").val("");
        //     $(".input-ba").prop("required", false);
        // }

        if (
            // seksie.toLowerCase().includes("produksi")
            departemen.toLowerCase().includes("pabrik")
            ||
            departemen.toLowerCase().includes("quality")
        ) {
            $("#plan").val("UNPLAN");
            $.ajax({
                url: baseURL + 'spl/master/get/data',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    plant: $("input[name='plant']").val(),
                    id_seksie: id_seksie,
                    id_departemen: id_departemen,
                    tanggal_spl: tanggal_spl,
                    is_lembur: true,
                    return: 'json',
                },
                success: function (data) {
                    if (data && data.length > 0) {
                        $("select[name='keterangan_lembur'] option[data-check_master='1']").removeAttr("disabled");
                        $("select[name='keterangan_lembur']").select2({
                            allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
                            placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
                        });
                        let rincianPlan = "";
                        $.each(data, function (i, v) {
                            let jam_lembur = parseFloat(v.jumlah_jam_lembur).toFixed(1);
                            rincianPlan += `${v.unit} - Shift ${v.shift} : ${jam_lembur} jam.\r\n`;
                        });
                        $("#plan").val("PLAN");
                        $("#rincian_plan").val(rincianPlan);
                        $("input[name='is_plan_fo']").val(1);
                        $(".data-master-plan").removeClass("d-none");
                        // $("#plan").attr('rows', 3);
                    } else {
                        $("select[name='keterangan_lembur'] option[data-check_master='1']").attr("disabled", "disabled");
                        $("select[name='keterangan_lembur']").select2({
                            allowClear: ($(this).attr("data-allowclear") == "true" ? true : false),
                            placeholder: ($(this).attr("data-placeholder") ? $(this).attr("data-placeholder") : "Silahkan Pilih")
                        });
                    }
                    cekAlasanLembur();
                }
            });
        } else {
            let plan = (diffDays > 2) ? "PLAN" : "UNPLAN";
            $("#plan").val(plan);
            cekAlasanLembur();
        }
    }
}

const hitungSelisihHari = () => {
    const tanggal_spl = $("input[name='tanggal_spl']").val();
    if (tanggal_spl) {
        let selectedDate = tanggal_spl.split(".");
        const date1 = new Date();
        const date2 = new Date(selectedDate[2], selectedDate[1] - 1, selectedDate[0]);
        const diffTime = date2 - date1;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays;
    } else
        return 0
}

const cekAlasanLembur = () => {
    let elem = $("select[name='keterangan_lembur']");
    const checkMaster = elem.find(':selected').data('check_master');
    const isPlanFo = $("#is_plan_fo").val();
    const seksie = $("#id_seksie option:selected").text();
    if (checkMaster == 1 && isPlanFo != 1) {
        console.log("Alasan Tidak Valid");
        elem.val("").trigger("change");
    } else if (!seksie.toLowerCase().includes("produksi")) {
        const diffDays = hitungSelisihHari();
        let plan = (diffDays > 2) ? "PLAN" : "UNPLAN";
        $("#plan").val(plan);
    }
}