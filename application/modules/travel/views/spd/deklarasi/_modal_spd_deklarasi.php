<div class="modal fade" role="dialog" id="modal-spd-deklarasi" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    Form Deklarasi Perjalanan Dinas
                </h4>
            </div>
            <div class="modal-body no-padding">
                <form class="form-horizontal form-deklarasi" style="padding: 4px 10px; position:relative;">
                    <input type="hidden" name="id_travel_deklarasi_header" id="id_travel_deklarasi_header">
                    <input type="hidden" name="id_travel_header" id="id_travel_header">
                    <fieldset class="fieldset-4px-rad fieldset-primary" style="padding: 4px 10px;">
                        <div class="row">
                            <div class="col-md-10">
                                <hr>
                            </div>
                            <div class="col-md-2 text-sm">
                                <a class="btn btn-xs btn-link btn-block" data-toggle="collapse"
                                   data-target="#list-approvals">
                                    Approval&nbsp;<i class="fa fa-bars"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row collapse" id="list-approvals">
                            <div class="col-md-12">
                                <ul class="fa-ul">
                                    <?php foreach ($approval['list_atasan'] as $list) : ?>
                                        <li>
                                            <i class="fa-li text-success fa fa-user"></i><?php echo $list ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
                        <legend class="no-pad-top"><h4>Detail Perjalanan</h4></legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="label_no_trip">No Trip</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static no-padding" id="label_no_trip">-</p>
                                    </div>
                                </div>
                                <div class="div-single-trip">
                                    <div class="form-group no-padding">
                                        <label class="col-md-4" for="label_single_start">Tanggal Berangkat</label>
                                        <div class="col-md-8">
                                            <input type="hidden" id="tanggal_berangkat">
                                            <p class="form-control-static no-padding" id="label_single_start">-</p>
                                        </div>
                                    </div>
                                    <div class="form-group no-padding">
                                        <label class="col-md-4" for="label_tujuan">Tujuan</label>
                                        <div class="col-md-8">
                                            <p class="form-control-static no-padding" id="label_tujuan">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="label_p_kantor">Aktifitas</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static no-padding" id="label_activity">-</p>
                                    </div>
                                </div>
                                <div class="div-single-trip">
                                    <div class="form-group no-padding">
                                        <label class="col-md-4" for="trip_end_datetime">Tanggal Kembali</label>
                                        <div class="col-md-8">
                                            <div class="input-group date trip_end_datetime">
                                                <input required id="single_end" name="single_end" readonly type="text"
                                                       class="form-control">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group no-padding">
                                        <label class="col-md-4" for="trip_keperluan">Keperluan</label>
                                        <div class="col-md-8">
                                            <input required id="keperluan" name="keperluan" type="text"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="div-multi-trip">
                            <div class="row">
                                <div class="col-md-12">
                                    <script type="text/template" id="multitrip_template">
                                        <tr class="template-trip">
                                            <td width="15%">
                                                <input type="hidden" name="detail[{no}][id]" class="multi-id-detail">
                                                <p class="label_multi_tujuan"></p>
                                            </td>
                                            <td width="20%" class="text-center">
                                                <p class="label_multi_start"></p>
                                            </td>
                                            <td width="25%">
                                                <input required name="detail[{no}][keperluan]" type="text"
                                                       class="form-control label_multi_keperluan">
                                            </td>
                                        </tr>
                                    </script>
                                    <table id="table-multi-trip" class="table table-responsive table-bordered">
                                        <thead>
                                        <tr>
                                            <th>Tujuan</th>
                                            <th>Keberangkatan</th>
                                            <th>Keperluan</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <div class="form-group">
                                        <label class="col-md-4">Kembali</label>
                                        <div class="col-md-4">
                                            <div class="input-group date trip_end_datetime">
                                                <input readonly id='multi_end' name="multi_end" type="text" class="form-control">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top animated fadeIn">
                        <legend class="no-pad-top"><h4>Detail Biaya</h4></legend>
                        <script type="text/template" id="biaya_template">
                            <tr class="template-trip">
                                <td width="25%">
                                    <div class="input-group biaya_tanggal">
                                        <input readonly name="biaya[{no}][tanggal]" type="text"
                                               placeholder="Pilih tanggal"
                                               required class="form-control">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                                    </div>
                                </td>
                                <td width="25%">
                                    <input type="hidden" name="biaya[{no}][id]" class="deklarasi-detail-id">
                                    <select name="biaya[{no}][biaya]"
                                            required
                                            class="select2 select-biaya form-control"
                                            data-placeholder="Pilih Jenis Biaya">
                                    </select>
                                </td>
                                <td width="15%">
                                    <input name="biaya[{no}][keterangan]"
                                           type="text" class="form-control biaya-keterangan"
                                           placeholder="Ketik keterangan">
                                </td>
                                <td width="20%">
                                    <div class="input-group">
                                        <input id="biaya_{no}_jumlah"
                                               required value="0"
                                               name="biaya[{no}][jumlah]" numeric-min="0"
                                               class="form-control biaya-jumlah text-right numeric">
                                    </div>
                                </td>
                                <td width="10%">
                                    <select name="biaya[{no}][currency]"
                                            required
                                            class="select2 select-currency form-control"
                                            data-placeholder="Pilih Mata uang">
                                    </select>
                                </td>
                                <td width="50">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="btn-group btn-sm no-padding">
                                            <a class="btn btn-default fileinput-exists fileinput-zoom"
                                               target="_blank" data-fancybox="gallery"
                                            ><i class="fa fa-search"></i></a>
                                            <a class="btn btn-facebook btn-file btn-sm">
                                                <div class="fileinput-new"><i class="fa fa-plus"></i></div>
                                                <div class="fileinput-exists"><i class="fa fa-edit"></i></div>
                                                <input type="file" name="biaya[{no}][lampiran]" id="biaya_{no}_lampiran">
                                            </a>
                                            <a href="#" class="btn btn-pinterest fileinput-exists btn-sm"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </td>
                                <td width="5%">
                                    <button type="button"
                                            class="btn hide btn-sm btn-flat btn-danger btn-block biaya_delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </script>
                        <table id="table-biaya" class="table table-bordered table-striped table-condensed">
                            <thead>
                            <tr>
                                <th width="15%">Tanggal</th>
                                <th width="25%">Biaya</th>
                                <th width="15%">Keterangan</th>
                                <th width="20%">Jumlah</th>
                                <th width="10%">Mata uang</th>
                                <th width="10%"><i class="fa fa-image"></i></th>
                                <th width="5%"></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-4">
                                <hr/>
                            </div>
                            <div class="col-md-4">
                                <button id="biaya_add_btn" class="btn btn-default btn-block btn-xs" type="button"><i
                                            class="fa fa-plus"></i> Tambah Biaya
                                </button>
                            </div>
                            <div class="col-md-4">
                                <hr/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <hr/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="total_biaya">Jumlah Biaya</label>
                                    <div class="col-md-8">
                                        <input required id="total_biaya" readonly name="total_biaya" type="text"
                                               class="form-control numeric text-right" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="total_um">Uang muka</label>
                                    <div class="col-md-8">
                                        <input required id="total_um" readonly name="total_um" type="text"
                                               class="form-control numeric text-right" value="0">
                                    </div>
                                </div>
                                <div class="form-group no-padding">
                                    <label class="col-md-4" for="total_bayar">Dibayarkan</label>
                                    <div class="col-md-8">
                                        <input required id="total_bayar" readonly name="total_bayar" type="text"
                                               numeric-total-min="0"
                                               class="form-control numeric text-right" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer text-center">
                <button name="simpan_btn" class="btn btn-success" type="button">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>