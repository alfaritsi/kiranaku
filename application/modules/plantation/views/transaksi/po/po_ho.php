<?php $this->load->view('header') ?>
<style>
    .tab-text {
        /* display: inline-block; */
        margin-left: 40px;
    }

    table th{
        text-align: center;
        vertical-align: middle !important;
    }
</style>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Buat Purchase Order HO</h3>
                    </div>

                    <div class="box-body">
                        <form id="form-createpoho" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" name="id_ppb" id="id_ppb" value="<?php echo $data_ppb->id; ?>" required>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label >No PPB</label>
                                        <input type="text" class="form-control" name="no_ppb" value="<?php echo $data_ppb->no_ppb; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label >Pabrik</label>
                                        <input type="text" class="form-control" name="plant" value="<?php echo $data_ppb->plant; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Vendor</label>
                                        <select class="form-control" name="vendor" id="vendor" required></select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal PPB</label>
                                        <input type="text" class="form-control" name="tanggal_ppb" value="<?php echo $data_ppb->tanggal_format; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal PO</label>
                                        <input type="text" class="form-control" name="tanggal" value="<?php echo date('d.m.Y'); ?>" readonly required>
                                    </div>
                                    <div class="form-group">
                                        <label>PPN</label>
                                        <select class="form-control" name="ppn" id="ppn">
                                            <option value="">Tanpa PPN</option>
                                            <?php if (date('Y-m-d') < "2022-04-01") : ?>
                                                <option value="B5">B5</option>
                                            <?php else : ?>
                                                <option value="BK">BK</option>
                                            <?php endif; ?>
                                        </select>
                                        <input type="hidden" name="nilai_ppn" value="0">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Detail Barang</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="table-detail" style="min-width: 1800px !important">
                                            <thead >
                                                <th style="width: 3%;"><input type="checkbox" id="checkall"></th>
                                                <th style="width: 9%;">Kode Barang</th>
                                                <th style="width: 12%;">Deskripsi 1</th>
                                                <th style="width: 12%;">Deskripsi 2</th>
                                                <th>Tipe</th>
                                                <th>Asset Class</th>
                                                <th>G/L Account</th>
                                                <th>Cost Center</th>
                                                <th style="width: 6%;">Jumlah Disetujui</th>
                                                <th style="width: 6%;">Jumlah</th>
                                                <th style="width: 5%;">Satuan</th>
                                                <th>Harga</th>
                                                <th>Diskon</th>
                                                <th>Total</th>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="13" class="text-right" style="vertical-align: middle;">Subtotal</td>
                                                    <td>
                                                        <!-- <div class="input-group">
                                                            <div class="input-group-addon">Rp</div> -->
                                                            <input type="text" class="angka form-control text-right" id="subtotal" name="subtotal" value="0" readonly>
                                                        <!-- </div> -->
                                                    </td>
                                                </tr>
                                                <tr class="hidden">
                                                    <td colspan="13" class="text-right" style="vertical-align: middle;">Diskon</td>
                                                    <td>
                                                        <!-- <div class="input-group">
                                                            <div class="input-group-addon">Rp</div> -->
                                                            <input type="text" class="angka form-control text-right" id="total_diskon" name="total_diskon" value="0" readonly>
                                                        <!-- </div> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="13" class="text-right" style="vertical-align: middle;">PPN</td>
                                                    <td>
                                                        <!-- <div class="input-group">
                                                            <div class="input-group-addon">Rp</div> -->
                                                            <input type="text" class="angka form-control text-right" id="total_ppn" name="total_ppn" value="0" readonly>
                                                        <!-- </div> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="13" class="text-right" style="vertical-align: middle;">Total</td>
                                                    <td>
                                                        <!-- <div class="input-group">
                                                            <div class="input-group-addon">Rp</div> -->
                                                            <input type="text" class="angka form-control text-right" id="total-item" name="summary_item" value="0" readonly>
                                                        <!-- </div> -->
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="action">
                        <a href="<?php echo base_url().'plantation/transaksi/data'; ?>"><button type="button" class="btn btn-default" style="width:100px;">Kembali</button></a>
                        <button type="button" name="action_btn" class="btn btn-success pull-right" data-btn="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/transaksi/po/po_ho.js?<?php echo time(); ?>"></script>