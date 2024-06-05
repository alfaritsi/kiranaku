<?php $this->load->view('header') ?>
<style>
    .tab-text {
        /* display: inline-block; */
        margin-left: 40px;
    }

    table th{
        text-align: center;
    }
</style>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Detail Bukti Keluar Barang</h3>
                    </div>

                    <div class="box-body">
                        <form id="form-createpoho" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" name="id_gi" id="id_gi" value="<?php echo $data_gi->id; ?>" required>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label >No BKB</label>
                                        <input type="text" class="form-control" name="no_gi" value="<?php echo $data_gi->no_gi; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label >Pabrik</label>
                                        <input type="text" class="form-control" name="plant" value="<?php echo $data_gi->plant; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal BKB</label>
                                        <input type="text" class="form-control" name="tanggal" value="<?php echo $data_gi->tanggal_format; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Detail Barang</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="table-detail" style="min-width: 1200px !important">
                                            <thead >
                                                <th style="width: 12%;">Kode Barang</th>
                                                <th style="width: 25%;">Nama Barang</th>
                                                <th>G/L Account</th>
                                                <th>Cost Center</th>
                                                <th>TBM/CIP</th>
                                                <th style="width: 8%;">Jumlah</th>
                                                <th style="width: 7%;">Satuan</th>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="box-footer">
                        <?php
                        if ($akses_cetak) {
                            echo '<a href="' . base_url() . '/plantation/transaksi/cetak/gi/' . $data_gi->id . '" target="_blank" class="btn btn-default pull-right"><i class="fa fa-print"></i> Cetak</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/transaksi/gi/detail.js"></script>