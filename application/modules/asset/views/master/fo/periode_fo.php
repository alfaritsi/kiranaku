<?php $this->load->view('header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Master Periode</strong></h3>
                        <div class="btn-group pull-right pr">
                            <button id="add_periode" class="btn btn-sm btn-success pull-right"><i class="fa fa-plus"
                                                                                                  style="color:white; padding-right: 5px;"></i>
                                PERIODE
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped" id="sspsTable">
                            <thead>
                            <tr>
                                <th>Jenis Aset</th>
                                <th>Nama</th>
                                <th>Periode</th>
                                <!-- <th>Lama Pengerjaan</th>
                                <th>Delay Jadwal</th> -->
                                <th>Kategori</th>
                                <th>Periode Detail</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!--end box-->
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/master/periode_fo.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script> -->

<!-- Modal -->
<div class="modal fade" id="add_periode_modal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="add_modal_label" style="text-transform: capitalize">Tambah Periode</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form role="form" class="form-master-periode">
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="id_kategori">Kategori</label>
                                    <select class="form-control select2 col-sm-12" id="id_kategori"  required="required"
                                        data-placeholder="Silahkan Pilih Kategori">
                                        <option></option>
                                        <?php
                                        foreach($kategori as $dt){
                                            echo"<option value='".$dt->id_kategori."'>".$dt->nama."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_aset">Jenis Aset</label>
                                    <select id="jenis_aset" name="jenis_aset" class="form-control select2 col-sm-12"
                                            required="required" data-placeholder="Pilih Jenis Aset">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="instansi">Nama Periode</label>
                                    <input type="text" class="form-control" name="periode" id="periode"
                                           placeholder="Masukkkan Nama Periode" required="required">
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control" name="ket_periode" id="ket_periode"
                                              placeholder="Masukan Keterangan" required="required"></textarea>
                                </div>
                                <div class="form-group hide">
                                    <label for="sequence">Sequence</label>
                                    <input type="number" class="form-control" name="sequence" id="sequence" value="1"
                                           required="required">
                                </div>
                                <div class="form-group">
                                    <label for="bulan">Periode</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="periode_jumlah" id="periode_jumlah" value="1" min="1"
                                               required="required">
                                        <div class="input-group-addon" style="padding:0;border:none;">
                                            <select class="form-control select2" data-dropdown-auto-width="true" name="periode2" id="periode2">
                                                <?php foreach(ASET_PERIODE as $i => $periode): ?>
                                                    <option value="<?php echo $i ?>"><?php echo $periode ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="bulan">Lama Pengerjaan</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="lama_jumlah" id="lama_jumlah" value="1" min="1"
                                               required="required">
                                        <div class="input-group-addon" style="padding:0;border:none;">
                                            <select class="form-control select2" data-dropdown-auto-width="true" name="lama" id="lama">
                                                <?php foreach(ASET_PERIODE as $i => $periode): ?>
                                                <option value="<?php echo $i ?>"><?php echo $periode ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="bulan">Delay Jadwal</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="delay_hari" id="delay_hari"
                                               value="0" required="required">
                                        <span class="input-group-addon">Hari </span>
                                    </div>
                                    <span class="help-block">Berlaku untuk jadwal periode pertama tiap asset</span>
                                </div> -->
                                <div class="form-group">
                                    <label for="service">Kategori Service</label>
                                    <input type="hidden" name="lama" id="lama" value="hari">
                                    <input type="hidden" name="lama_jumlah" id="lama_jumlah" value="0">
                                    <input type="hidden" name="delay_hari" id="delay_hari" value="0">
                                    <select id="service" name="service" class="form-control select2 col-sm-12"
                                            required="required">
                                        <option value=''>Pilih Kategori Service</option>
                                        <?php
                                        foreach ($service as $s) {
                                            echo "<option value='$s->id_service'>$s->nama</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="auto_gen">Generate Jadwal otomatis</label>
                                    <div class="checkbox">
                                        <label>
                                            <input name="auto_gen" type="hidden" value="n" />
                                            <input name="auto_gen"
                                                   id="auto_gen" type="checkbox" value="y" />
                                            Jadwal selanjutnya akan otomatis tergenerate setelah jadwal ini selesai dikerjakan
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <input type="hidden" name="id_periode">
                                <button type="submit" class="btn btn-success pull-right">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div class="modal fade" id="detail_modal" data-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="detail_modal_label" style="text-transform: capitalize">Periode Detail</h4>
            </div>
            <form role="form" class="form-periode-detail">
                <div class="modal-body">
                    <table id="periode_detail" class="table table-responsive table-bordered datatable-periode">
                        <thead>
                        <!-- <th style="display: none;">id_periode_detail</th> -->
                        <!-- <th style="display: none;">id</th> -->
                        <th>Jenis Detail</th>
                        <th>Kegiatan</th>
                        <th>Keterangan</th>
                        <th>Select All
                            <div class="checkbox pull-right select_all" style="margin:0; font-weight: bold;">
                                <label><input type="checkbox" class="selectALL"></label>
                            </div>
                        </th>

                        </thead>
                        <tbody id="tbod">
                        </tbody>
                    </table>


                </div>
                <div class="modal-footer">
                    <input type="hidden" name="total_row">
                    <input type="hidden" name="fd_id_periode">
                    <input type="hidden" name="fd_id_jenis">
                    <button type="submit" class="btn btn-success pull-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal 