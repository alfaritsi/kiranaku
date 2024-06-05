<div class="modal fade" role="dialog" id="modal-permintaan">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span id="title">Tambah</span> Permintaan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-permintaan" enctype="multipart/form-data">
                    <input type="hidden" name="id_permintaan" id="id_permintaan">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-4">Plant</label>
                                <div class="col-md-8">
                                    <p class="form-control-static" id="plant"><?php echo $plant ?></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Kategori</label>
                                <div class="col-md-6">
                                    <select class="form-control select2" name="id_kategori" id="id_kategori"
                                            data-placeholder="Pilih Kategori" required>
                                        <option></option>
                                        <?php foreach ($jenis_kategori as $j) : ?>
                                            <option value="<?php echo $j->id_kategori; ?>"><?php echo $j->jenis_kategori; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nama Dokumen</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="nama_permintaan_dok" name="nama_permintaan_dok" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nomor Dokumen</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="nomor_dokumen" name="nomor_dokumen" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Keperluan</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="keperluan" name="keperluan"
                                              rows="4"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Keterangan</label>
                                <div class="col-md-8">
                                    <textarea class="form-control" id="keterangan" name="keterangan"
                                              rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i>&nbsp;Reset</button>
                <button name="save_permintaan" class="btn btn-success" type="button"><i class="fa fa-save"></i>&nbsp;Save
                </button>
            </div>
        </div>
    </div>
</div>