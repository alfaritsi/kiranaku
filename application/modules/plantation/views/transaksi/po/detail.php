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
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Detail Purchase Order</h3>
                    </div>

                    <div class="box-body">
                        <form id="form-createpoho" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" name="id_po" id="id_po" value="<?php echo $data_po->id; ?>" required>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label >Pabrik</label>
                                        <input type="text" class="form-control" name="plant" value="<?php echo $data_po->plant; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Vendor</label>
                                        <select class="form-control" name="vendor" id="vendor" required disabled></select>
                                    </div>
                                    <div class="form-group">
                                        <label>PPN</label>
                                        <select class="form-control" name="ppn" id="ppn" required disabled>
                                            <option value="">Tanpa PPN</option>
                                            <option value="B5">B5</option>
                                            <option value="BK">BK</option>
                                        </select>
                                        <input type="hidden" name="nilai_ppn" value="0">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal PO</label>
                                        <input type="text" class="form-control" name="tanggal" value="<?php echo $data_po->tanggal_format; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label >Tipe PO</label>
                                        <input type="text" class="form-control" name="tipe_po" value="<?php echo $data_po->tipe_po; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php
                                    $output = "";
                                    if ($data_po->id_file) {
                                        $output = "<label for='file_list_attach'>File Attachment</label>";
                                        $output .= '<div><a href="javascript:void(0)" class="view_file" data-link="' . $data_po->location . '"><i class="fa fa-file-o"></i>&nbsp;&nbsp;' . $data_po->filename . '</a></div>';
                                    }
                                    echo '<div class="form-group" id="file_list_attach">' . $output . '</div>';
                                    ?>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Detail Barang</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="table-detail" style="min-width: 1800px !important">
                                            <thead >
                                                <th style="width: 10%;">Kode Barang</th>
                                                <th style="width: 15%;">Nama Barang</th>
                                                <th>NO PPB</th>
                                                <th>Tipe</th>
                                                <th>Asset Class</th>
                                                <th>G/L Account</th>
                                                <th>Cost Center</th>
                                                <th style="width: 6%;">Jumlah</th>
                                                <th style="width: 5%;">Satuan</th>
                                                <th>Harga</th>
                                                <th>Diskon</th>
                                                <th>Total</th>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="11" class="text-right" style="vertical-align: middle;">Subtotal</td>
                                                    <td>
                                                        <input type="text" class="angka form-control text-right" id="subtotal" name="subtotal" value="0" readonly>
                                                    </td>
                                                </tr>
                                                <tr class="hidden">
                                                    <td colspan="11" class="text-right" style="vertical-align: middle;">Diskon</td>
                                                    <td>
                                                        <input type="text" class="angka form-control text-right" id="total_diskon" name="total_diskon" value="0" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="11" class="text-right" style="vertical-align: middle;">PPN</td>
                                                    <td>
                                                        <input type="text" class="angka form-control text-right" id="total_ppn" name="total_ppn" value="0" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="11" class="text-right" style="vertical-align: middle;">Total</td>
                                                    <td>
                                                        <input type="text" class="angka form-control text-right" id="total-item" name="summary_item" value="0" readonly>
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
                        <?php
                        if ($akses_upload_attachment) 
                            echo '<button class="btn btn-success btn_upload">Upload Attachment</button>';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--modal upload-->
    <div class="modal fade" id="modal_upload" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sg" role="document">
            <div class="modal-content">
                <div class="col-sm-12">
                    <div class="modal-content">
                        <form role="form" class="form-upload-attachment">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Attachment PO</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="file">File</label>
                                    <input type="file" class="form-control" name="file[]" id="file" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input name="id_po" type="hidden" value="<?php echo $data_po->id; ?>">
                                <input name="no_ppb" type="hidden" value="<?php echo $data_po->no_ppb; ?>">
                                <input name="tipe" type="hidden" value="po">
                                <input name="action" type="hidden" value="edit">
                                <button id="btn_save" type="button" class="btn btn-success" name="action_btn_save">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>	
    </div>
</div>

<?php $this->load->view('footer') ?>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/transaksi/po/detail.js"></script>