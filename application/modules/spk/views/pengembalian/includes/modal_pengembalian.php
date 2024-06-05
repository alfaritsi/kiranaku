<div class="modal fade" role="dialog" id="modal-pengembalian">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><span id="title">Tambah</span> Pengembalian</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-pengembalian" enctype="multipart/form-data">
                    <input type="hidden" name="id_pengembalian" id="id_pengembalian">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-4">Plant</label>
                                <div class="col-md-4">
                                    <select class="form-control select2" name="plant" id="plant"
                                            data-placeholder="Pilih Plant" required>
                                        <option></option>
                                        <?php foreach ($plants as $j) : ?>
                                            <option value="<?php echo $j->plant; ?>"><?php echo $j->plant; ?></option>
                                        <?php endforeach; ?>
                                    </select>
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
                                <label class="col-md-4">Nomor Dokumen</label>
                                <div class="col-md-6">
                                    <select class="form-control select2" id="nomor_dokumen"
                                            data-placeholder="Pilih Nomor Dokumen" required>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4">Nama Dokumen</label>
                                <div class="col-md-6">
                                    <select class="form-control select2" id="id_permintaan" name="id_permintaan"
                                            data-placeholder="Pilih Nama Dokumen" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="reset"><i class="fa fa-undo"></i>&nbsp;Reset</button>
                <button name="save_pengembalian" class="btn btn-success" type="button"><i class="fa fa-save"></i>&nbsp;Save
                </button>
            </div>
        </div>
    </div>
</div>