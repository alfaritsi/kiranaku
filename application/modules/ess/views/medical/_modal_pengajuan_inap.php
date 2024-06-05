<div class="modal fade modal-pengajuan" role="dialog" id="modal-pengajuan-inap">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Form Pengajuan Rawat Inap</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-pengajuan" id="form-pengajuan-inap">
                    <input type="hidden" id="id_fbk_inap" name="id_fbk"/>
                    <input type="hidden" id="kode_inap" name="kode" value="BRIN"/>
                    <input type="hidden" id="fbk_jenis_inap" name="fbk_jenis" value="inap"/>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="fieldset-primary">
                                <legend class="text-center">Info Pasien</legend>
                                <div class="form-group">
                                    <label class="col-md-4">Nama Pasien</label>
                                    <div class="col-md-6">
                                        <select class="form-control select2" id="nama_pasien_jalan"
                                                name="nama_pasien">
                                            <option value="<?php echo $nama_karyawan ?>"><?php echo $nama_karyawan ?></option>
                                            <?php foreach ($data_keluarga as $keluarga): ?>
                                                <option value="<?php echo $keluarga->kode ?>"><?php echo $keluarga->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Jenis Sakit</label>
                                    <div class="col-md-6">
                                        <select class="form-control select2 jenis-sakit"
                                                id="id_fbk_sakit_jalan"
                                                name="id_fbk_sakit"
                                                required="required"
                                                data-placeholder="Pilih Jenis Sakit">
                                            <option></option>
                                            <?php foreach ($jenis_sakit as $jenis): ?>
                                                <option value="<?php echo $jenis->id_fbk_sakit ?>">
                                                    <?php echo $jenis->nama ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group jenis_sakit_lain hide">
                                    <div class="col-sm-offset-4 col-sm-6">
                                        <input type="text" class="form-control"
                                               placeholder="Sebutkan jenis sakit"
                                               name="sakit" id="sakit_jalan"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Rumah Sakit</label>
                                    <div class="col-md-6">
                                        <div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <select class="form-control select2"
                                                            id="id_rs"
                                                            name="id_rs"
                                                            required="required"
                                                            data-placeholder="Pilih Rumah Sakit">
                                                        <option></option>
                                                        <?php foreach ($rumah_sakit as $rs): ?>
                                                            <option value="<?php echo $rs->id_rs ?>">
                                                                <?php echo $rs->nama ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hide" id="rs_lain">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control"
                                                           placeholder="Sebutkan rumah sakit"
                                                           name="rs" id="rs"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fieldset-warning">
                                <legend class="text-center">Info Kamar</legend>
                                <div class="form-group">
                                    <label class="col-md-4">Plafon Kamar / Hari</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" readonly="readonly" id="plafon_inap"
                                                   name="plafon_kamar" value="<?php echo $plafon_fbk_inap ?>"
                                                   class="form-control numeric">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Biaya Kamar <sup>(Aktual)</sup></label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" id="biaya_kamar"
                                                   name="biaya_kamar"
                                                   class="form-control numeric"
                                                   numeric-min="1" required>
                                            <span class="input-group-addon">per hari</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Jumlah Hari</label>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <input type="number" id="jumlah_hari"
                                                       name="jumlah_hari" min="1" required
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fieldset-success">
                                <legend class="text-center">Data Pengajuan</legend>
                                <div class="form-group">
                                    <label class="col-md-4">Banyak Kwitansi</label>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <input type="number" id="jumlah_kwitansi_jalan"
                                                       name="jumlah_kwitansi" max="5" min="1" required
                                                       class="form-control jumlah-kwitansi">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 hide div-kwitansi" style="margin-top:20px;">
                                        <div class="box box-success">
                                            <div class="box-body no-padding">
                                                <table class="table table-responsive">
                                                    <thead>
                                                    <tr>
                                                        <th width="15%">Tanggal</th>
                                                        <th width="15%">Nomor</th>
                                                        <th width="20%">Nominal</th>
                                                        <th width="20%">Lampiran</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr class="template hide">
                                                        <td class="no-padding">
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="input-group">
                                                                    <span class="input-group-addon"><i
                                                                                class="fa fa-calendar"></i></span>
                                                                        <input type="hidden" disabled name="kwitansi[$][id_fbk_kwitansi]">
                                                                        <input type="text" readonly name="kwitansi[$][tanggal]"
                                                                               disabled
                                                                               autocomplete="false"
                                                                               class="form-control datepicker"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="no-padding">
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <input type="text" name="kwitansi[$][nomor]"
                                                                           maxlength="10"
                                                                           disabled
                                                                           autocomplete="false"
                                                                           aria-autocomplete="none"
                                                                           class="form-control"
                                                                    >
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="no-padding">
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">Rp.</span>
                                                                        <input type="text" name="kwitansi[$][nominal]"
                                                                           disabled
                                                                           autocomplete="false"
                                                                           class="form-control numeric amount"
                                                                           numeric-min="1"
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="no-padding">
                                                            <div class="form-group">
                                                                <div class="col-sm-12" id="div-lampiran-$">
                                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                        <div class="btn-group btn-sm no-padding">
                                                                            <a class="btn btn-default fileinput-exists fileinput-zoom"
                                                                               target="_blank" data-fancybox
                                                                            ><i class="fa fa-search"></i></a>
                                                                            <a class="btn btn-facebook btn-file">
                                                                                <div class="fileinput-new"><i class="fa fa-plus"></i> Pilih</div>
                                                                                <div class="fileinput-exists"><i class="fa fa-edit"></i> Ganti</div>
                                                                                <input type="file" name="kwitansi[$][lampiran]" id="kwitansi_$" disabled required="required">
                                                                            </a>
                                                                            <a href="#" class="btn btn-pinterest fileinput-exists"
                                                                               data-dismiss="fileinput"><i class="fa fa-trash"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Keterangan</label>
                                    <div class="col-md-8">
                                            <textarea class="form-control" id="keterangan_jalan"
                                                      name="keterangan" rows="4"
                                            ></textarea>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fieldset-info">
                                <legend class="text-center">Estimasi Pengajuan</legend>
                                <div class="form-group">
                                    <label class="col-md-4">Total Kwitansi</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" readonly id="total_inap"
                                                   name="total_kwitansi" value="0"
                                                   class="form-control numeric">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Estimasi Dibayar Perusahaan </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" readonly="readonly" id="estimasi_inap"
                                                   value="0"
                                                   class="form-control numeric">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Estimasi Dibayar Karyawan </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" readonly="readonly" id="estimasi_inap_karyawan"
                                                   value="0"
                                                   class="form-control numeric">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button name="reset_btn" class="btn btn-warning btn-reset" type="reset">Reset</button>
                <button name="simpan_btn" class="btn btn-success btn-simpan" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>