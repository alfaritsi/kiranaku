<div class="modal fade" role="dialog" id="modal-pengajuan-bak">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pengajuan BAK</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-pengajuan">
                    <input type="hidden" id="id_bak" name="id_bak"/>
                    <input type="hidden" name="absen_masuk"/>
                    <input type="hidden" name="absen_keluar"/>
                    <input type="hidden" name="id_bak_alasan"/>
                    <input type="hidden" id="jenis" name="jenis"/>
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="fieldset-info">
                                <legend class="text-center">Info approval</legend>
                                <div class="form-group no-padding">
                                    <label class="col-md-4">
                                        Approval bak
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
                                <legend class="text-center">
                                    Data Pengajuan
                                </legend>
                                <div class="form-group">
                                    <label class="  col-md-4">Tanggal absen</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static" id="tanggal_absen"></p>
                                    </div>
                                </div>
                                <div id="div-absen">
                                    <div class="form-group">
                                        <label class="col-md-4">Absen masuk</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="tanggal_masuk"
                                                       id="tanggal_masuk" placeholder="Tanggal Masuk">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="absen_masuk"
                                                       id="absen_masuk" placeholder="Jam Masuk">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="  col-md-4">Absen keluar</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="tanggal_keluar"
                                                       id="tanggal_keluar" placeholder="Tanggal Keluar">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="absen_keluar"
                                                       id="absen_keluar" placeholder="Jam Keluar">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="  col-md-4">Alasan</label>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <select class="form-control select2" id="id_bak_alasan" name="id_bak_alasan"
                                                        data-placeholder="Pilih Alasan" required>
                                                    <option></option>
                                                    <?php foreach ($bak_alasan as $alasan) : ?>
                                                        <option value="<?php echo $alasan->id_bak_alasan; ?>">
                                                            <?php echo $alasan->nama ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="alasan_lain hide">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <input type="text" class="form-control"
                                                           placeholder="Sebutkan alasan"
                                                           name="alasan" id="alasan"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="  col-md-4">Keterangan</label>
                                    <div class="col-md-8">
                                                <textarea rows="4" id="keterangan" name="keterangan" class="form-control"
                                                          placeholder="Ketik keterangan untuk berita kehadiran"
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