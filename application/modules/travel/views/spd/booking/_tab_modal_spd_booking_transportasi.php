<script src="<?php echo base_url() ?>assets/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script> 
<!-- <div>
    <input class="switch-onoff" type="checkbox" name="condition_fieldname" 
                    id="dot_fieldname_fieldname{no}" checked data-toggle="toggle" data-size="mini"
                    data-on="Issued" data-off="Cancel" data-onstyle="success" data-offstyle="danger">
</div> -->
<div id="divtransport">
    
</div>
<div id="template-transport-pesawat" class="hide">
    <div class="box transport-booking transport-pesawat animated fadeIn">
        <div class="box-header with-border">
            <h4 class="box-title">Transportasi Pesawat</h4>
            <span class="label status_ticket_label{no}"></span>
            
            <h5 class="text-muted transport-tujuan">
                <span class="transport-jadwal-perjalanan_pesawat{no}"></span>
                <span class="pull-right transport-jadwal-keberangkatan_pesawat{no}"></span>
            </h5>
            
            
            <div class="box-tools pull-right">
                <span id="span_status_tiket{no}" class="select_tiket" style="display: none;">                
                    <!-- <input class="switch-onoff" type="checkbox" name="condition_fieldname" 
                        id="dot_fieldname_fieldname{no}" checked data-toggle="toggle" data-size="mini"
                        data-on="Issued" data-off="Cancel" data-onstyle="success" data-offstyle="danger -->">
                </span>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool text-danger transport_remove_btn">
                            <i class="fa fa-trash"></i></button>
                
                    
            </div>
        </div>
        <div class="box-body">
            <input disabled type="hidden" name="transport[{no}][id_travel_transport]" class="transport-id">
            <input disabled type="hidden" name="transport[{no}][jenis_kendaraan]" value="pesawat">
            <div class="form-group no-padding div_perjalanan" >
                <label class="col-md-4" for="transport[{no}][id_travel_detail]">Perjalanan</label>
                <div class="col-md-8">
                    <select disabled class="select-perjalanan form-control" name="transport[{no}][id_travel_detail]">
                        <option value="kembali">Kembali</option>
                    </select>
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Keberangkatan</label>
                <div class="col-md-8">
                    <div class="input-group transport_jadwal">
                        <input disabled readonly name="transport[{no}][jadwal]" type="text"
                               placeholder="Pilih jadwal"
                               required class="form-control">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
                    </div>
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Maskapai</label>
                <div class="col-md-8 divvendor" id="divvendor{no}">
                    <!-- <input disabled name="transport[{no}][vendor]" type="text"
                           placeholder="Ketik nama maskapai"
                           required class="form-control transport-vendor"> -->
                    <select id="select-vendor-pesawat{no}" name="transport[{no}][vendor]"
                            class="transport-vendor"
                            required>
                        <!-- <?php foreach ($pesawat_merk as $vendor) : ?>
                            <option value="<?php echo $vendor->kode_merk ?>" >
                                <?php echo $vendor->merk ?>
                            </option>
                        <?php endforeach; ?> -->
                    </select>

                    <!-- <select name="detail[{no}][trans][]" class="select2 select-trans form-control"
                                    data-placeholder="Pilih Transportasi" multiple>
                                <?php foreach ($transports as $trans) : ?>
                                    <option value="<?php echo $trans->kode ?>">
                                        <?php echo $trans->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select> -->

                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Tiket</label>
                <div class="col-md-4">
                    <input disabled name="transport[{no}][no_tiket]" type="text"
                           placeholder="Ketik no tiket"
                           required class="form-control transport-no_tiket">
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input disabled name="transport[{no}][harga]" type="text"
                               placeholder="Harga tiket"
                               required class="form-control text-right numeric transport-harga">
                        <span class="input-group-addon">IDR</span>
                    </div>
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Status refund</label>
                <div class="col-md-8 divrefund" id="divrefund{no}">
                    <select id="select-refund-pesawat{no}" 
                        name="transport[{no}][status_tiket_refund]"
                        class="transport-refund form-control"
                        required>
                      <option value="Refundable">Bisa direfund</option>
                      <option value="Unrefundable">Tidak bisa direfund</option>
                    </select>'
                     <input name="transport[{no}][status_tiket_primary]" type="hidden"
                        class="form-control transport-status_tiket_primary" readonly>
                     <input name="transport[{no}][status_tiket]" type="hidden"
                        class="form-control transport-status_tiket" readonly>
              </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Lampiran</label>
                <div class="col-md-8" >
                    

                    <div class="fileinput fileinput-new" id='fileinput_{no}' data-provides="fileinput">
                        <div class="btn-group btn-sm no-padding">
                            <a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox><i class="fa fa-search"></i></a>
                            <a class="btn btn-facebook btn-file">
                                <div class="fileinput-new">Attachment</div>
                                <div class="fileinput-exists">
                                    <i class="fa fa-edit"></i>
                                </div>
                                <input disabled name="transport[{no}][lampiran]" type="file"
                                   placeholder="Pilih lampiran tiket"
                                   required class="form-control transport-lampiran">
                                <!-- <input class="pull-left" type="file" name="transport[{no}][lampiran]"> -->
                            </a> 
                            <a href="#" class="btn btn-pinterest fileinput-exists"data-dismiss="fileinput">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Keterangan</label>
                <div class="col-md-8">
                    <textarea disabled name="transport[{no}][keterangan]"
                              placeholder="Ketik keterangan transportasi"
                              class="form-control transport-keterangan"></textarea>
                </div>
            </div>
            <div id="div_alasan_cancel{no}" class="form-group no-padding" style="display:none">
                <label class="col-md-4" for="keberangkatan">Alasan cancel</label>
               <div class="col-md-8">
                    <textarea disabled name="transport[{no}][alasan_cancel]"
                              id="transport_{no}_alasan_cancel"
                              placeholder="Ketik keterangan cancel"
                              class="form-control transport-keterangan_cancel"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hide" id="template-transport-taxi">
    <div class="box transport-booking transport-taxi animated fadeIn">
        <div class="box-header with-border">
            <h3 class="box-title">Transportasi Taksi</h3>
            <h5 class="text-muted transport-tujuan">
                <span class="transport-jadwal-perjalanan_taxi{no}"></span>
                <span class="pull-right transport-jadwal-keberangkatan_taxi{no}"></span>
            </h5>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool text-danger transport_remove_btn"><i
                            class="fa fa-trash"></i></button>
            </div>
        </div>
        <div class="box-body">
            <input disabled type="hidden" name="transport[{no}][id_travel_transport]" class="transport-id">
            <input disabled type="hidden" name="transport[{no}][jenis_kendaraan]" value="taxi">
            <div class="form-group no-padding div_perjalanan" >
                <label class="col-md-4" for="transport[{no}][id_travel_detail]">Perjalanan</label>
                <div class="col-md-8">
                    <select disabled class="select-perjalanan form-control" name="transport[{no}][id_travel_detail]">
                        <option>Kembali</option>
                    </select>
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Taksi</label>
                <div class="col-md-8 divvendor" id="divvendor{no}">
                    <!-- <input disabled name="transport[{no}][vendor]" type="text"
                           placeholder="Ketik nama taksi"
                           required class="form-control transport-vendor"> -->
                    <select id="select-vendor-taxi{no}" name="transport[{no}][vendor]"
                            class="form-control transport-vendor"
                             required>
                        <!-- <?php foreach ($taxi_merk as $vendor) : ?>
                            <option value="<?php echo $vendor->kode_merk ?>"
                                <?php echo $vendor->kode_merk === 'bluebird' ? 'selected' : '' ?>>
                                <?php echo $vendor->merk ?>
                            </option>
                        <?php endforeach; ?> -->
                    </select>
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Voucher</label>
                <div class="col-md-4">
                    <input disabled name="transport[{no}][no_tiket]" type="text"
                           placeholder="Ketik no voucher"
                           required class="form-control transport-no_tiket">
                </div>
            </div>
            <div class="form-group no-padding div_lampiran" class="hide">
                <label class="col-md-4" for="keberangkatan">Lampiran</label>
                <div class="col-md-8">
                    <!-- <input disabled name="transport[{no}][lampiran]" type="file"
                           placeholder="Pilih lampiran tiket"
                           required class="form-control transport-lampiran"> -->
                    <div class="fileinput fileinput-new" id='fileinput_{no}' data-provides="fileinput">
                        <div class="btn-group btn-sm no-padding">
                            <a class="btn btn-default fileinput-exists fileinput-zoom" target="_blank" data-fancybox><i class="fa fa-search"></i></a>
                            <a class="btn btn-facebook btn-file">
                                <div class="fileinput-new">Attachment</div>
                                <div class="fileinput-exists">
                                    <i class="fa fa-edit"></i>
                                </div>
                                <input disabled name="transport[{no}][lampiran]" type="file"
                                   placeholder="Pilih lampiran tiket"
                                    class="form-control transport-lampiran">
                                <!-- <input class="pull-left" type="file" name="transport[{no}][lampiran]"> -->
                            </a> 
                            <a href="#" class="btn btn-pinterest fileinput-exists"data-dismiss="fileinput">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group no-padding">
                <label class="col-md-4" for="keberangkatan">Keterangan</label>
                <div class="col-md-8">
                    <textarea disabled name="transport[{no}][keterangan]"
                              placeholder="Ketik keterangan transportasi"
                              class="form-control transport-keterangan"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="transport-list">

</div>
<div class="row" >
    <div class="col-md-offset-4 col-md-4" id="button-add-trans">
        <div class="btn-group">
            <button class="btn btn-default btn-block btn-xs dropdown-toggle"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    type="button"><i
                        class="fa fa-plus"></i> Tambah Transportasi
            </button>
            <input type="hidden" id="count_field" value='0'>
            <ul class="dropdown-menu" id="ul_list_transport">
                <!-- <li><a href="#" class="transport_add_btn" data-type="pesawat"><i class="fa fa-plane"></i> <span class="pull-right">Pesawat</span></a></li>
                <li><a href="#" class="transport_add_btn" data-type="taxi"><i class="fa fa-taxi"></i> <span class="pull-right">Taksi</span></a></li> -->
            </ul>
        </div>
    </div>
</div>