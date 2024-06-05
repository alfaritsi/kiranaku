<!-- <fieldset class="fieldset-4px-rad fieldset-primary" style="padding: 4px 10px;">
    <div class="row">
        <div class="col-md-10">
            <hr>
        </div>
        <div class="col-md-2 text-sm">
            <a class="btn btn-xs btn-link btn-block" data-toggle="collapse" data-target="#list-approvals">
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
</fieldset> -->
<fieldset class="fieldset-4px-rad fieldset-success no-pad-top">
    <legend class="no-pad-top"><h4>Detail Perjalanan</h4></legend>
    <div id="btn-info-approval" class="legend2 btn">
        <!-- <p class="form-control-static"> <button type="button" id="close" class="close">&times;</button>-->
            <a id="popApproval" class="pull-right text-info" role="button"
                data-toggle="popover" title='Info Approval' data-placement="left"
                data-list="<?php echo htmlspecialchars(json_encode($approval['list_atasan']),ENT_QUOTES,'UTF-8'); ?>">
                
                <i class="fa fa-info-circle"></i>

            </a>
        <table id="template-approval" class="hidden table table-striped text-sm" style="min-width:200px;">
            <thead>
            <!-- <th>Nama</th>             -->
            </thead>
            <tbody></tbody>
        </table>
        <!-- </p> -->
    </div>
    <div class="form-group no-padding">
        <label class="col-md-2">Aktifitas</label>
        <div class="col-md-2">
            <select class="select2 form-control" name="activity" id="activity" data-placeholder="Pilih aktifitas">
                <?php foreach ($jenis_aktifitas as $ja): ?>
                    <option value="<?php echo $ja->kode_jns_aktifitas ?>"><?php echo $ja->jenis_aktifitas ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="form-group no-padding">
        <label class="col-md-2">Perjalanan</label>
        <div class="col-md-10">
            <div class="form-check form-check-inline">
                <div class="iradio_flat-green pull-left">
                    <label>
                        <input type="radio" name="tipe_trip" id="tipe_trip_single"
                               value="single" class="flat-red">
                        <small>Pulang Pergi &nbsp;&nbsp;&nbsp;</small>
                    </label>
                </div>
            </div>
            
            <div class="form-check form-check-inline">
                <div class="iradio_flat-green pull-left">
                    <label>
                        <input type="radio" name="tipe_trip" id="tipe_trip_multi"
                               value="multi" class="flat-red">
                        <small>Multi Perjalanan</small>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group no-padding">
        <label class="col-md-2" for="no_hp">No Handphone</label>
        <div class="col-md-2">
            <div class="input-group">
                <input type="text" name="no_hp" id="no_hp"
                       placeholder="Ketik nomor handphone"
                       class="form-control" required value="<?php //echo $user->no_hp; ?>">
            </div>
        </div>
    </div>

    <div id="div-single-trip_old" class="animated fadeIn hide">
        <div class="form-group no-padding">
            <label class="col-md-4">Keperluan</label>
            <div class="col-md-8">
                <input type="text" name="keperluan" maxlength="59"
                       class="form-control" placeholder="ketik keperluan perjalanan dinas">
            </div>
        </div>
        <div class="form-group no-padding">
            <label class="col-md-4" for="select-country-single">Tujuan</label>
            <div class="col-md-4">
                <select id="select-country-single" name="country"
                        class="select2 form-control select-country"
                        data-placeholder="Pilih Negara">
                    <?php foreach ($countries as $country) : ?>
                        <option value="<?php echo $country->country_code ?>"
                            <?php echo $country->country_code === 'ID' ? 'selected' : '' ?>>
                            <?php echo $country->country_name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group no-padding">
            <div class="col-md-8 col-md-offset-4">
                <select class="select2 form-control select-area" name="tujuan_persa" data-placeholder="Pilih Pabrik">
                </select>
                <select class="select2 form-control select-tujuan" name="tujuan" data-placeholder="Pilih Kota"></select>
                <input name="tujuan_lain" placeholder="Ketik tujuan" type="text"
                       class="form-control hide input-tujuan_lain">
            </div>
        </div>
        <div class="form-group no-padding">
            <label class="col-md-4">Tanggal Perjalanan</label>
            <div class="col-md-4">
                <div class="checkbox">
                    Berangkat
                </div>
                <div class="input-group date trip_start_datetime">
                    <input required id="single_start" name="single_start" readonly type="text" class="form-control">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="checkbox">
                    <!-- <label>
                        <input type="checkbox" class="trip_end_checkbox">&nbsp;
                    </label> -->
                    Kembali
                </div>
                <div class="input-group date trip_end_datetime">
                    <input id="single_end" name="single_end" readonly type="text" class="form-control" required>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                </div>
            </div>
        </div>
    </div>


    <div id="div-multi-trip" class="animated fadeIn hide">
        <div class="row margin-bottom" id="div_detail_add_btn">
            <div class="col-md-9">
                <hr/>
            </div>
            <div class="col-md-3" >
                <button id="detail_add_btn" class="btn btn-dropbox btn-block btn-xs" type="button"><i
                            class="fa fa-plus"></i> Tambah Tujuan
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <script type="text/template" id="multitrip_template">
                    <tr class="template-trip">
                        <td width="5%" style="text-align: center"></td>
                        <td width="20%">
                            <input type="hidden" name="detail[{no}][id]" class="multi-id-detail">
                            <select name="detail[{no}][country]" class="select2 select-country form-control"
                                    data-placeholder="Pilih Negara">
                                <?php foreach ($countries as $country) : ?>
                                    <option value="<?php echo $country->country_code ?>"
                                        <?php echo $country->country_code === 'ID' ? 'selected' : '' ?>>
                                        <?php echo $country->country_name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <select name="detail[{no}][tujuan_persa]" class="select2 form-control select-area"
                                    data-placeholder="Pilih Pabrik">
                            </select>
                            <select name="detail[{no}][tujuan]" class="select2 form-control select-tujuan"
                                    data-placeholder="Pilih Tujuan">
                            </select>
                            <input name="detail[{no}][tujuan_lain]" placeholder="Ketik tujuan" type="text"
                                   style="width: 100%;" class="form-control hide input-tujuan_lain">
                            
                        </td>
                        <td width="25%">                            
                            <input name="detail[{no}][keperluan]"
                                   type="text" class="form-control input-keperluan"
                                   placeholder="Ketik keperluan"
                                   style ="width:100%">
                        </td>
                        <td width="20%">
                            <div class="input-group trip_start_datetime_multi">
                                <input readonly name="detail[{no}][start]" type="text"
                                       placeholder="Pilih jadwal"
                                       required class="form-control select-tanggal-berangkat-multi">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                            </div>
                        </td>
                        <td width="25%">
                            
                            
                            <select name="detail[{no}][trans][]" class="select2 select-trans form-control"
                                    data-placeholder="Pilih Transportasi" multiple>
                                <?php foreach ($transports as $trans) : ?>
                                    <option value="<?php echo $trans->kode ?>">
                                        <?php echo $trans->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="divtiket{no}">
                            <select name="detail[{no}][tiket][]" class="select2 select-tiket form-control"
                                    data-placeholder="Pilih Tiket Pesawat" multiple="multiple">
                                
                                    <option value="berangkat">Berangkat</option>
                                    <option value="pulang">Pulang</option>
                                    <option value="0">Tanpa Pemesanan</option>
                                
                            </select>
                            </div>
                            <select name="detail[{no}][inap]" class="select2 select-inap form-control"
                                    data-placeholder="Pilih Penginapan">
                                
                                    <option value="Mess">Mess</option>
                                    <option value="Hotel">Hotel</option>
                                
                            </select>

                        </td>
                        <td width="5%">
                            <button type="button" class="btn hide btn-xs btn-danger btn-block detail_delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </script>
                <table id="table-multi-trip" class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Tujuan</th>
                        <th width="20%">Keperluan</th>
                        <th width="15%">Keberangkatan</th>
                        <th width="20%">Transportasi & Penginapan</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                    <!-- <tr>
                        <th colspan="2">
                            <div class="checkbox">
                                <label>
                                    <!- <input class="trip_end_checkbox" type="checkbox">&nbsp; ->
                                    <strong>Jadwal kembali</strong>
                                </label>
                            </div>
                        </th>
                        <td>
                            <div class="input-group date trip_end_datetime_multi">
                                <input readonly name="detail_end" type="text" class="form-control" required>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                            </div>
                        </td>
                        <th colspan="2">&nbsp;</th>
                    </tr> -->
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="form-group no-padding">
        <label class="col-md-2" for="kembali">Jadwal kembali</label>
            <div class="col-md-2">
                <div class="input-group date trip_end_datetime_multi">
                    <input readonly name="detail_end" type="text" class="form-control" required>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                </div>
            </div>
        </div>
        <!-- <div class="form-group">
            <div class="col-md-4">
            </div>
            <div class="col-md-3">
            </div>
        </div> -->
    </div>
</fieldset>

<fieldset class="fieldset-4px-rad fieldset-info no-pad-top" id="div-single-trip-trans" >
    <legend class="no-pad-top"><h4>Transportasi & Penginapan</h4></legend>
    <div class="form-group no-padding" >
        <label class="col-md-4">Transportasi</label>
        <div class="col-md-8">
            <div class="row">
                <?php foreach ($transports as $transport): ?>
                <div class="col-md-6">
                    <div class="checkbox">
                        <label><input type="checkbox" name="transport[]" value="<?php echo $transport->kode ?>">&nbsp;<small><?php echo $transport->nama ?></small>
                        </label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="form-group no-padding">
        <!-- <div class="col-md-8 col-md-offset-4"> -->
        <label class="col-md-4" for="booking_tiket">Dipesankan tiket &nbsp;</label>
        <div class="col-md-6">
            <select required name="tiket_single[]" class="select2 select-tiket form-control" data-placeholder="Pilih Tiket Pesawat" multiple="multiple">
                
                <option value="berangkat">Berangkat</option>
                <option value="pulang">Pulang</option>
                <option value="0">Tanpa Pemesanan</option>    
            </select>
        </div>
    </div>
    <!-- <div class="form-group no-padding">
        <label class="col-md-4" for="booking_brgkt">&nbsp;&nbsp;&nbsp;Berangkat&nbsp;</label>
        <div class="col-md-2">
            <input type="hidden" name="booking_brgkt" value="0">
            <input class="icheck pull-left" type="checkbox" checked name="booking_brgkt"
                   id="booking_brgkt" value="1">
        </div>
    </div>
    <div class="form-group no-padding">
        <label class="col-md-4" for="booking_kembali">&nbsp;&nbsp;&nbsp;Kembali&nbsp;</label>
        <div class="col-md-2">
            <input type="hidden" name="booking_kembali" value="0">
            <input class="icheck" type="checkbox" name="booking_kembali" id="booking_kembali" value="1">
        </div>
    </div>  -->     
    

    <div class="form-group no-padding">
        <label class="col-md-4">Penginapan</label>
        <div class="col-md-2 div-mess">
            <div class="radio">
                <label>
                    <input type="radio" name="jenis_penginapan" id="penginapan_mess" checked
                           value="Mess">
                    <small>Mess</small>
                </label>
            </div>
        </div>
        <div class="col-md-2 div-hotel">
            <div class="radio">
                <label>
                    <input type="radio" name="jenis_penginapan" id="penginapan_hotel"
                           value="Hotel">
                    <small>Hotel</small>
                </label>
            </div>
        </div>

    </div>
</fieldset>

<!-- <fieldset class="fieldset-4px-rad fieldset-warning no-pad-top">
    <legend class="no-pad-top"><h4>Kontak</h4></legend>
    <div class="form-group no-padding">
        <label class="col-md-4" for="no_hp">No Handphone</label>
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" name="no_hp" id="no_hp"
                       placeholder="Ketik nomor handphone"
                       class="form-control" required value="<?php echo $user->no_hp; ?>">
            </div>
        </div>
    </div>
</fieldset> -->