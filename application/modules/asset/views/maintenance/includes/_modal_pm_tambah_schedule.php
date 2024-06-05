<!-- Modal PM tambah schedule -->
<div class="modal fade" id="add_modal" data-backdrop="static" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="col-sm-12">
                <form role="form" class="form-transaksi-jadwal-pm" name="form-transaksi-jadwal-pm" enctype="multipart/form-data">
                    <div class="nav-tabs-custom" id="tabs-edit">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Tambah Jadwal PM</h4>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-data" data-toggle="tab">Jadwal Schedule</a></li>
                            <li class="fade"><a href="#tab-assets" data-toggle="tab">List Asset (<span id="jumlah_asset">0</span>)</a></li>
                        </ul>
                        <div class="modal-body">
                            <div class="tab-content">
                                <!--data-->
                                <div class="tab-pane active" id="tab-data">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label for="kategori">Kategori</label>
                                            </div>
                                            <div class="col-xs-8">
                                                <select class="form-control select2modal" name="id_kategori" id="id_kategori" required="required" data-placeholder="Silahkan Pilih Kategori">
                                                    <?php
                                                    echo "<option></option>";
                                                    foreach ($kategori as $dt) {
                                                        echo "<option value='" . $dt->id_kategori . "'>" . $dt->nama . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label for="jenis">Jenis</label>
                                            </div>
                                            <div class="col-xs-8">
                                                <select class="form-control select2modal" name="id_jenis" id="id_jenis" required="required" data-placeholder="Silahkan pilih Jenis" data-allow-clear="true">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label for="id_pabrik">Pabrik</label>
                                            </div>
                                            <div class="col-xs-8">
                                                <select class="form-control" multiple="multiple" id="id_pabrik" data-placeholder="Pilih Pabrik atau semua">
                                                    <?php
                                                    foreach ($pabrik as $dt) {
                                                        echo "<option value='" . $dt->id_pabrik . "'";
                                                        echo ">" . $dt->nama . " - " . $dt->kode . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label for="jenis">Periode</label>
                                            </div>
                                            <div class="col-xs-8">
                                                <select class="form-control select2modal" name="id_periode" id="id_periode" required="required" data-placeholder="Silahkan Pilih Periode" data-allowClear="true" data-allow-clear="true" disabled>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label for="jenis">Jadwal</label>
                                            </div>
                                            <div class="col-xs-8">
                                                <input class="form-control kiranadatepicker" name="jadwal_service" id="jadwal_service" required="required" placeholder="Pilih Tanggal Jadwal service" disabled />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group operatorFo hide">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label for="jenis">Operator</label>
                                            </div>
                                            <div class="col-xs-8">
                                                <input class="form-control" type="text" name="operatorFo" id="operatorFo" value="<?php echo base64_decode($this->session->userdata("-nik-")) . " - " . base64_decode($this->session->userdata("-nama-")); ?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-assets">
                                    <table class="table table-responsive" id="table-tab-assets">
                                        <thead>
                                            <th>Pilih semua</th>
                                            <th></th>
                                            <th>Detail Aset</th>
                                            <th>Pabrik</th>
                                            <th class='thuser'>User</th>
                                            <th>Kondisi</th>
                                            <th>Status</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" name="action_btn">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>