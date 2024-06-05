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
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Detail Tanda Terima Gudang</h3>
                    </div>

                    <div class="box-body">
                        <form id="form-createpoho" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" name="id_gr" id="id_gr" value="<?php echo $data_gr->id; ?>" required>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label >No TTG</label>
                                        <input type="text" class="form-control" name="no_gr" value="<?php echo $data_gr->no_gr; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label >Pabrik</label>
                                        <input type="text" class="form-control" name="plant" value="<?php echo $data_gr->plant; ?>" readonly>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label>Vendor</label>
                                        <select class="form-control" name="vendor" id="vendor" required disabled></select>
                                    </div> -->
                                </div>
                                <div class="col-sm-6">
                                    <!-- <div class="form-group">
                                        <label>Tanggal PO</label>
                                        <input type="text" class="form-control" name="tanggal_po" value="<?php echo $data_gr->tanggal_po_format; ?>" readonly>
                                    </div> -->
                                    <div class="form-group">
                                        <label>Tanggal TTG</label>
                                        <input type="text" class="form-control" name="tanggal" value="<?php echo $data_gr->tanggal_format; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label >Tipe PO</label>
                                        <input type="text" class="form-control" name="tipe_po" value="<?php echo $data_gr->tipe_po; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div>
                            <?php
                                    $output = "";
                                    if ($data_gr->id_file) {
                                        $output = "<label for='file_list_attach'>File Attachment</label>";
                                        $output .= '<div><a href="javascript:void(0)" class="view_file" data-link="' . $data_gr->location . '"><i class="fa fa-file-o"></i>&nbsp;&nbsp;' . $data_gr->filename . '</a></div>';
                                    }
                                    echo '<div class="form-group" id="file_list_attach">' . $output . '</div>';
                                    ?>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Detail Barang</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="table-detail">
                                            <thead >
                                                <th style="width: 15%;">Kode Barang</th>
                                                <th style="width: 25%;">Nama Barang</th>
                                                <th>Tipe</th>
                                                <th>Jumlah</th>
                                                <th>Satuan</th>
                                                <th>SLOC</th>
                                                <th>Keterangan</th>
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
                        if ($akses_upload_attachment) 
                            echo '<button class="btn btn-success btn_upload">Upload Attachment</button>';

                        if ($akses_cetak) {
                            $tipe = ($data_gr->tipe_po == 'HO') ? 'gr_ho' : 'gr_site';
                            if (
                                $data_gr->tipe_po == 'HO' 
                                || ($data_gr->tipe_po == 'SITE' && $data_gr->no_po ) 
                            )
                                echo '<a href="' . base_url() . '/plantation/transaksi/cetak/'.$tipe.'/' . $data_gr->id . '" target="_blank" class="btn btn-default pull-right"><i class="fa fa-print"></i> Cetak</a>';
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
                                <h4 class="modal-title" id="myModalLabel">Attachment TTG</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="file">File</label>
                                    <input type="file" class="form-control" name="file[]" id="file" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input name="id_gr" type="hidden" value="<?php echo $data_gr->id; ?>">
                                <input name="no_gr" type="hidden" value="<?php echo $data_gr->no_gr; ?>">
                                <input name="tipe" type="hidden" value="gr">
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
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/transaksi/gr/detail.js"></script>