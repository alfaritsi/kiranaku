$(document).ready(function () {

    $('.slot-reserved').each(function (i, el) {
        var curEl = $(el);

        var sibEl = $(el).closest('.slot-reserved').next();

        checkSibling(curEl, sibEl);
    });

    function checkSibling(curEl, sibEl) {
        if (
            curEl.attr('data-id_ruang') == sibEl.attr('data-id_ruang') &&
            curEl.attr('data-id_karyawan') == sibEl.attr('data-id_karyawan') &&
            curEl.attr('data-keperluan') == sibEl.attr('data-keperluan')
        ) {
            curEl.attr('colspan', parseInt(curEl.attr('colspan')) + 1);
            curEl.attr('data-jam_akhir_reservasi', sibEl.attr('data-jam_akhir_reservasi'));
            sibEl.remove();

            let next = curEl.next();
            if (next.hasClass('slot-reserved'))
                checkSibling(curEl, next);
        }
    }

    $('.slot').on('click', function () {
        var editable = $(this).hasClass('editable') || $(this).hasClass('slot-free');
        var id = $(this).attr('data-id');
        var tgl = $(this).attr('data-tgl');
        var id_ruang = $(this).attr('data-id_ruang');
        var jam_awal = $(this).attr('data-jam_awal_reservasi');
        var jam_akhir = $(this).attr('data-jam_akhir_reservasi');
        $.ajax({
            url: baseURL + 'reservasiruangan/get/reservasi',
            type: 'POST',
            dataType: 'JSON',
            data: {
                id: id,
                tgl: tgl,
                id_ruang: id_ruang,
                jam_awal: jam_awal,
                jam_akhir: jam_akhir
            }, success: function (data) {
                if (editable) {

                    $('#modal-reservasi #div_keperluan').show();
                    $('#modal-reservasi #label_keperluan').hide();

                    $('#modal-reservasi #div_jumlah').show();
                    $('#modal-reservasi #label_jumlah').hide();

                    $('#modal-reservasi #jam_akhir').empty();
                    $('#modal-reservasi #jam_akhir').select2({
                        data: data.available_jam_akhir
                    });
                    $('#modal-reservasi #jam_akhir').trigger('change');;

                    $('#modal-reservasi #jam_akhir_reservasi').val(jam_akhir);
                    $('#modal-reservasi #label_jam_akhir_reservasi').hide();
                    $('#modal-reservasi .modal-footer').show();

                }
                else {
                    if($('#modal-reservasi #jam_akhir').data('select2'))
                        $('#modal-reservasi #jam_akhir').select2('destroy');

                    $('#modal-reservasi #jam_akhir').hide();

                    $('#modal-reservasi #div_keperluan').hide();
                    $('#modal-reservasi #label_keperluan').show();

                    $('#modal-reservasi #div_jumlah').hide();
                    $('#modal-reservasi #label_jumlah').show();

                    $('#modal-reservasi #label_jam_akhir_reservasi .value').html(jam_akhir);
                    $('#modal-reservasi #label_jam_akhir_reservasi').show();
                    $('#modal-reservasi .modal-footer').hide();
                }

                if(data.reservasi != null)
                {
                    if(editable)
                    {
                        $('#modal-reservasi button[name="batal_btn"]').show();
                        $('#modal-reservasi button[name="reset_btn"]').show();
                    }

                    $('#modal-reservasi #keperluan').val(data.reservasi.keperluan);
                    $('#modal-reservasi #label_keperluan').html(data.reservasi.keperluan);
                    $('#modal-reservasi #jumlah').val(data.reservasi.jumlah);
                    $('#modal-reservasi #jam_akhir').val(jam_akhir);
                    $('#modal-reservasi #label_jumlah .value').html(data.reservasi.jumlah);
                    $('#modal-reservasi #tanggal .value').html(data.reservasi.tanggal);
                    $('#modal-reservasi #kapasitas .value').html(data.reservasi.kapasitas);

                    $('#modal-reservasi #detail-reservator').removeClass('hide');
                    $('#modal-reservasi #detail-reservator .iimage').attr('src',data.reservasi.gambar);
                    $('#modal-reservasi #detail-reservator .inik').html(data.reservasi.id_karyawan);
                    $('#modal-reservasi #detail-reservator .inama').html(data.reservasi.nama_karyawan);
                    $('#modal-reservasi #detail-reservator .itelepon').html(data.reservasi.telepon);
                }else{
                    $('#modal-reservasi button[name="batal_btn"]').hide();
                    $('#modal-reservasi button[name="reset_btn"]').hide();
                    $('#modal-reservasi #keperluan').val('');
                    $('#modal-reservasi #jumlah').val('');
                    $('#modal-reservasi #jam_akhir').val($('#modal-reservasi #jam_akhir option:first-child').val());
                    $('#modal-reservasi #detail-reservator').addClass('hide');
                }

                $('#modal-reservasi #jam_akhir').trigger('change');

                $('#modal-reservasi #nama-ruangan').html(data.ruangan.nama);
                $('#modal-reservasi #jumlah').attr('max',data.ruangan.kapasitas);
                $('#modal-reservasi #kapasitas .value').html(data.ruangan.kapasitas);

                $('#modal-reservasi #fasilitas').html(data.available_fasilitas);
                $('#modal-reservasi #label_jam_awal .value').html(jam_awal);
                $('#modal-reservasi #jam_awal').val(jam_awal);
                $('#modal-reservasi #id_ruang').val(id_ruang);
                $('#modal-reservasi #tanggal').val(tgl);
                $('#modal-reservasi #tanggal .value').html(moment(tgl).format('DD.MM.YYYY'));

                $('#modal-reservasi').modal('show');

                validate(".form-reservasi",true);
            }
        });
    });

    $(document).on("click", "button[name='simpan_btn']", function (e) {
        var empty_form = validate(".form-reservasi",true);
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-reservasi")[0]);

                $.ajax({
                    url: baseURL + 'reservasiruangan/save/reservasi',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });

    $(document).on("click", "button[name='batal_btn']", function (e) {
        var empty_form = validate(".form-reservasi");
        if (empty_form == 0) {
            var isproses = $("input[name='isproses']").val();
            if (isproses == 0) {
                $("input[name='isproses']").val(1);
                var formData = new FormData($(".form-reservasi")[0]);

                $.ajax({
                    url: baseURL + 'reservasiruangan/delete/reservasi',
                    type: 'POST',
                    dataType: 'JSON',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.sts == 'OK') {
                            swal('Success', data.msg, 'success').then(function () {
                                location.reload();
                            });
                        } else {
                            $("input[name='isproses']").val(0);
                            swal('Error', data.msg, 'error');
                        }
                    }
                });
            } else {
                swal({
                    title: "Silahkan tunggu proses selesai.",
                    icon: 'info'
                });
            }
        }
        e.preventDefault();
        return false;
    });

    let now = new Date();

    $('#filter-tgl').datepicker({
        format: 'dd.mm.yyyy',
        todayHighlight: true,
        endDate: new Date(now.setDate(now.getDate()+14))
    });

    $('#filter-tgl').on('change', function () {
        $('#form-filter').submit();
    });
});