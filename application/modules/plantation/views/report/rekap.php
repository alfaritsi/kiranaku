<?php $this->load->view('header') ?>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row" id="box-form">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">Data Rekap Mutasi</h1>
                    </div>
                    <div class="box-body">
                        <div class="row-form" id="form-report">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label">Pabrik</label>
                                        <select class="form-control select2" multiple="multiple" id="pabrik_filter" name="pabrik_filter[]" style="width: 100%;" data-placeholder="Pilih Pabrik">
                                            <?php
                                            if (!empty($pabrik)) {
                                                // $arr_pabrik = explode(",", $pabrik);
                                                foreach ($pabrik as $pabrik) {
                                                    if ($pabrik != '') {
                                                        echo "<option value='$pabrik'>$pabrik</option>";
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label">Periode Transaksi</label>
                                        <div class="input-group input-daterange" id="filter-date">
                                            <input type="text" id="tanggal_awal" name="tanggal_awal" value="<?php echo $this->generate->generateDateFormat($tanggal_awal); ?>" class="form-control" readonly autocomplete="off" required>
                                            <label class="input-group-addon" for="tanggal-awal">-</label>
                                            <input type="text" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo $this->generate->generateDateFormat($tanggal_akhir); ?>" class="form-control" readonly autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" id="btn-submit" class="btn btn-success" data-btn="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="box-data">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title" id="box-data-title">Data Rekap Mutasi</h1>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-sm btn-success back_btn">Kembali</button>
                            </div>
                            <div class="col-sm-6">
                                <div id="action-button-datatable" style="float: right;"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
                                    <thead>
                                        <th>Plant</th>
                                        <th>Kode</th>
                                        <th>Deskripsi 1</th>
                                        <th>Deskripsi 2</th>
                                        <th>Total TTG</th>
                                        <th>Total BKB</th>
                                        <th>Current Stock</th>
                                        <th>Satuan</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/report/rekap.js?<?php echo time(); ?>"></script>