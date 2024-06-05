<div class="modal fade modal-pengajuan" role="dialog" id="modal-pengajuan-lensa">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pengajuan Lensa</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-pengajuan" id="form-pengajuan-lensa">
                    <input type="hidden" id="id_fbk_lensa" name="id_fbk"/>
                    <input type="hidden" id="kode_lensa" name="kode" value="BLNS"/>
                    <input type="hidden" id="fbk_jenis_lensa" name="fbk_jenis" value="lensa"/>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="fieldset-primary">
                                <legend class="text-center">Info Pasien</legend>
                                <div class="form-group">
                                    <label class="col-md-4">Nama pasien</label>
                                    <div class="col-md-6">
                                        <select class="form-control select2" id="nama_pasien_lensa"
                                                name="nama_pasien">
                                            <option value="<?php echo $nama_karyawan ?>"><?php echo $nama_karyawan ?></option>
                                            <?php foreach ($data_keluarga as $keluarga): ?>
                                                <option value="<?php echo $keluarga->kode ?>"><?php echo $keluarga->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="fieldset-warning">
                                <legend class="text-center">Data Pengajuan</legend>
                                <div class="form-group">
                                    <label class="col-md-4">Plafon Lensa</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" readonly="readonly" id="plafon_lensa"
                                                   name="plafon_lensa" value="<?php echo $sisa_fbk_lensa ?>"
                                            class="form-control numeric plafon">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Banyak kwitansi</label>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <input type="number" id="jumlah_kwitansi_lensa"
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
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="no-padding">
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
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
                                    <label class="col-md-4">Total kwitansi</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" readonly id="total_lensa"
                                                   name="total_kwitansi" value="0"
                                                   numeric-total-max="<?php echo $sisa_fbk_lensa ?>"
                                                   class="form-control numeric">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4">Keterangan</label>
                                    <div class="col-md-8">
                                            <textarea class="form-control" id="keterangan_lensa"
                                                      name="keterangan" rows="4"
                                            ></textarea>
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