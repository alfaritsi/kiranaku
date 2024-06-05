<div id="template-penginapan-hotel" class="hide">
    <div class="box penginapan-booking penginapan-hotel animated fadeIn">
        <div class="box-header with-border">
            <h3 class="box-title">Penginapan Hotel</h3>
            <h5 class="text-muted penginapan-tujuan">
                <span class="penginapan-jadwal-perjalanan{no}"></span>
                <span class="pull-right penginapan-jadwal-keberangkatan{no}"></span>
            </h5>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool text-danger penginapan_remove_btn"><i
                            class="fa fa-trash"></i></button>
            </div>
        </div>
        <div class="box-body">
            <input disabled type="hidden" name="penginapan[{no}][id_travel_hotel]" class="penginapan-id">
            <div class="form-group no-padding">
                <label class="col-md-4" for="penginapan[{no}][id_travel_detail]">Perjalanan</label>
                <div class="col-md-8">
                    <select disabled class="select-perjalanan form-control" name="penginapan[{no}][id_travel_detail]">
                        <option>Kembali</option>
                    </select>
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Check In & Out Hotel</label>
                <div class="col-md-4">
                    <div class="input-group penginapan_start_date">
                        <input disabled readonly name="penginapan[{no}][start_date]" type="text"
                               placeholder="Check in"
                               required class="form-control">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group penginapan_end_date">
                        <input disabled readonly name="penginapan[{no}][end_date]" type="text"
                               placeholder="Check out"
                               required class="form-control">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                    </div>
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Nama Hotel</label>
                <div class="col-md-8">
                    <input disabled name="penginapan[{no}][nama_hotel]" type="text"
                           placeholder="Ketik nama hotel" maxlength="100"
                           required class="form-control penginapan-nama_hotel">
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">PIC</label>
                <div class="col-md-4">
                    <input disabled name="penginapan[{no}][pic_hotel]" type="text"
                           placeholder="Ketik nama PIC hotel" maxlength="50"
                           required class="form-control penginapan-pic_hotel">
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Alamat</label>
                <div class="col-md-8">
                    <input disabled name="penginapan[{no}][alamat]" type="text"
                           placeholder="Ketik alamat hotel" maxlength="100"
                           required class="form-control penginapan-alamat">
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Lampiran</label>
                <div class="col-md-8">
                    <input disabled name="penginapan[{no}][lampiran]" type="file"
                           placeholder="Pilih lampiran tiket"
                           required class="form-control penginapan-lampiran">
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Keterangan</label>
                <div class="col-md-8">
                    <textarea disabled name="penginapan[{no}][keterangan]"
                              placeholder="Ketik keterangan hotel"
                              class="form-control penginapan-keterangan"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="penginapan-list">

</div>
<div id="div-no-penginapan" class="row hide">
    <div class="col-md-offset-2 col-md-8 text-center">
        <small>Jenis penginapan yang dipilih adalah Mess, tidak diperlukan untuk pemesanan melalui form ini.</small>
    </div>
</div>
<div id="div-add-penginapan" class="row">
    <div class="col-md-offset-4 col-md-4" id="button-add-inn">
        <div class="btn-group">
            <button class="btn btn-default btn-block btn-xs dropdown-toggle"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    type="button"><i
                        class="fa fa-plus"></i> Tambah Penginapan
            </button>
            <ul class="dropdown-menu">
                <li><a href="#" class="penginapan_add_btn" data-type="hotel"><i class="fa fa-building"></i> <span
                                class="pull-right">Hotel</span></a></li>
            </ul>
        </div>
    </div>
</div>