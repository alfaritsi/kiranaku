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
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Detail Permohonan Pembelian Barang</h3>
                    </div>

                    <div class="box-body" style="min-height: 500px;">
                        <!-- <form id="form-konfirm-ppb" enctype="multipart/form-data"> -->
                            <div class="row">
                                <input type="hidden" name="id_ppb" id="id_ppb" value="<?php echo $data_ppb->id; ?>" required>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label >No PPB</label>
                                        <input type="text" class="form-control" id="no_ppb" value="<?php echo $data_ppb->no_ppb; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label >Pabrik</label>
                                        <input type="text" class="form-control" value="<?php echo $data_ppb->plant; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label >Perihal</label>
                                        <textarea type="text" class="form-control" readonly><?php echo $data_ppb->perihal; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal PPB</label>
                                        <input type="text" class="form-control" value="<?php echo $data_ppb->tanggal_format; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Konfirmasi</label>
                                        <input type="text" class="form-control" name="tanggal_konfirmasi" value="<?php echo $data_ppb->tanggal_konfirmasi_format; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php
                                    $output = "";
                                    if ($data_ppb->id_file) {
                                        $output = "<label for='file_list_attach'>File Attachment</label>";
                                        $output .= '<div><a href="javascript:void(0)" class="view_file" data-link="' . $data_ppb->location . '"><i class="fa fa-file-o"></i>&nbsp;&nbsp;' . $data_ppb->filename . '</a></div>';
                                    }
                                    echo '<div class="form-group" id="file_list_attach">' . $output . '</div>';
                                    ?>
                            </div>
                            <hr>
                            <div class="row" style="margin-bottom: 5px;">
                                <div class="col-sm-12">
                                    <span style="font-weight: bold; vertical-align:middle;">Detail Barang</span>
                                    <button onclick="export_detail();" class="btn btn-default btn-sm pull-right"><i class="fa fa-file"></i> Export Excel</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="table-detail" style="min-width: 1500px !important">
                                            <thead >
                                                <th style="width: 7%;">Tipe PO</th>
                                                <th style="width: 10%;">Kode Barang</th>
                                                <th>Deskripsi 1</th>
                                                <th>Deskripsi 2</th>
                                                <th style="width: 8%;">Tipe</th>
                                                <th style="width: 6%;">Jumlah<br>Diminta</th>
                                                <th style="width: 7%;">Satuan</th>
                                                <th style="width: 10%;">Harga</th>
                                                <th style="width: 6%;">Jumlah<br>Disetujui</th>
                                                <th style="width: 6%;">Jumlah<br>PO</th>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <!-- </form> -->
                    </div>
                    <div class="box-footer">
                        <?php
                        if ($akses_upload_attachment) 
                            echo '<button class="btn btn-success btn_upload">Upload Attachment</button>';

                        if ($akses_cetak) {
                            echo '<a href="' . base_url() . '/plantation/transaksi/cetak/ppb/' . $data_ppb->id . '" target="_blank" class="btn btn-default pull-right"><i class="fa fa-print"></i> Cetak</a>';
                        }
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
                                <h4 class="modal-title" id="myModalLabel">Attachment PPB</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="file">File</label>
                                    <input type="file" class="form-control" name="file_ppb[]" id="file_ppb" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input name="id_ppb" type="hidden" value="<?php echo $data_ppb->id; ?>">
                                <input name="no_ppb" type="hidden" value="<?php echo $data_ppb->no_ppb; ?>">
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/transaksi/ppb/detail.js?<?php echo time();?>"></script>