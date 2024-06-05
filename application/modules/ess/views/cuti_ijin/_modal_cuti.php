<div class="modal fade" role="dialog" id="modal-cutiijin">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pengajuan <span id="form-title"></span></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-pengajuan">
                    <input type="hidden" id="id_cuti" name="id_cuti"/>
                    <input type="hidden" id="gambar_old" name="gambar_old"/>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="fieldset-info">
                                <legend class="text-center">Info Approval</legend>
                                <div class="form-group no-padding">
                                    <label class="col-md-4">
                                        Approval cuti
                                    </label>
                                    <div class="col-md-6 collapse" id="collapse-approval">
                                        <input type="hidden" name="atasan" id="atasan"
                                               value="<?php echo $atasan['nik_atasan'] ?>"/>
                                        <ul class="fa-ul">
                                            <?php foreach ($atasan['list_atasan'] as $list) : ?>
                                                <li><i class="fa-li text-success fa fa-user"></i><?php echo $list ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-xs btn-default" role="button"
                                           data-toggle="collapse" href="#collapse-approval"
                                           aria-expanded="false">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Email atasan</label>
                                    <div class="col-md-6 collapse" id="collapse-konfirmasi">
                                        <input type="hidden" name="atasan_email" id="atasan_email"
                                               value="<?php echo $atasan['nik_atasan_email'] ?>"/>
                                        <ul class="fa-ul">
                                            <?php foreach ($atasan['list_atasan_email'] as $list) : ?>
                                                <li>
                                                    <i class="fa-li text-primary fa fa-envelope"></i><?php echo $list ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-xs btn-default" role="button"
                                           data-toggle="collapse" href="#collapse-konfirmasi"
                                           aria-expanded="false">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fieldset-warning">
                                <legend class="text-center">Data Pengajuan</legend>
                                <div class="form-group hide">
                                    <label class="  col-md-4">Form</label>
                                    <div class="col-md-2">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="form" id="jenis_form_cuti"
                                                       value="Cuti" checked>
                                                Cuti
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="form" id="jenis_form_ijin"
                                                       value="Ijin">
                                                Ijin
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="div-jenis-cuti">
                                    <div class="form-group">
                                        <label class="  col-md-4">Saldo saat ini</label>
                                        <div class="col-md-6">
                                            <p class="form-control-static">
                                                <input type="hidden"
                                                       id="saldo_cuti" value="<?php echo $sisa_cuti['sisa']; ?>">
                                                <input type="hidden"
                                                       id="saldo_negatif"
                                                       value="<?php echo $sisa_cuti['negatif']; ?>">
                                                <span id="saldo_cuti_label"><?php echo $sisa_cuti['sisa']; ?></span>
                                                hari&nbsp;
                                                <small class="hide text-danger" id="saldo_help">Sudah tidak bisa
                                                    mengajukan cuti.
                                                </small>
                                                <a id="popSaldoCuti" class="pull-right text-info" role="button"
                                                   data-toggle="popover" title="Info Saldo"
                                                   data-list="<?php echo htmlspecialchars(json_encode($list_saldo), ENT_QUOTES, 'UTF-8') ?>">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                            <table id="template-saldo" class="hidden table table-striped text-sm" style="min-width:200px;">
                                                <thead>
                                                <th>Nama</th>
                                                <th>Sisa</th>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div id="div-jenis-ijin" class="hide">
                                    <div class="form-group">
                                        <label class="  col-md-4">Jenis ijin</label>
                                        <div class="col-md-6">
                                            <select class="form-control select2" id="kode" name="kode"
                                                    data-placeholder="Pilih Jenis Ijin" required>
                                                <option></option>
                                                <?php foreach ($jenis_ijin as $jenis) : ?>
                                                    <option value="<?php echo $jenis->kode; ?>"
                                                            data-jarak="<?php echo $jenis->jarak; ?>"
                                                            data-jumlah="<?php echo $jenis->jumlah; ?>"
                                                    ><?php echo $jenis->nama ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="div-jarak" class="form-group hide">
                                        <label class="col-md-4">Jarak dari kantor</label>
                                        <div class="col-md-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="hidden" name="jarak" value="0">
                                                    <input type="checkbox" name="jarak" id="jarak"
                                                           value="2">
                                                    Ceklist Jika Jarak > 80Km
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="div-tanggal-awal-akhir">
                                    <div class="form-group">
                                        <label class="  col-md-4">Tanggal awal</label>
                                        <div class="col-md-6">
                                            <div class="input-group col-md-12 date" data-js="datepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="hidden" id="tanggal_awal_lama"/>
                                                <input class="form-control tgl_awal_akhir" readonly type="text"
                                                       name="tanggal_awal" id="tanggal_awal" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="  col-md-4">Tanggal akhir</label>
                                        <div class="col-md-6">
                                            <div class="input-group col-md-12 date" data-js="datepicker">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="hidden" id="tanggal_akhir_lama"/>
                                                <input class="form-control tgl_awal_akhir" readonly type="text"
                                                       name="tanggal_akhir" id="tanggal_akhir" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group">
                                            <label class="col-md-4">Jumlah <span
                                                        class="jumlah-hari-label">cuti</span></label>
                                            <div class="col-md-6">
                                                <p class="form-control-static">
                                                    <span id="jumlah_cuti_label">0</span> hari
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="div-lampiran" class="form-group hide">
                                    <label class="  col-md-4">Lampiran</label>
                                    <div class="col-md-8">
                                        <div class="alert alert-info">
                                            <small>
                                                <ol style="padding-inline-start: 10px;">
                                                    <li>File lampiran hanya diperbolehkan yang ber ekstensi JPG, JPEG,
                                                        PNG atau PDF.
                                                    </li>
                                                    <li>Ukuran file lampiran hanya diperbolehkan maksimal 5MB.</li>
                                                </ol>
                                            </small>
                                        </div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="btn-group btn-sm no-padding">
                                                <a class="btn btn-default fileinput-exists fileinput-zoom"
                                                   target="_blank" data-fancybox="gallery"
                                                ><i class="fa fa-search"></i></a>
                                                <a class="btn btn-facebook btn-file">
                                                    <div class="fileinput-new"><i class="fa fa-plus"></i></div>
                                                    <div class="fileinput-exists"><i class="fa fa-edit"></i></div>
                                                    <input type="file" name="lampiran" id="lampiran">
                                                </a>
                                                <a href="#" class="btn btn-pinterest fileinput-exists"
                                                   data-dismiss="fileinput"><i class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="  col-md-4">Catatan</label>
                                    <div class="col-md-8">
                                                <textarea rows="4" id="alasan" name="alasan" class="form-control"
                                                          placeholder="Ketik catatan untuk cuti/ijin"
                                                          required></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button name="reset_btn" class="btn btn-warning" type="reset">Reset</button>
                <button name="simpan_btn" class="btn btn-success" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>