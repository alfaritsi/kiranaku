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
                        <h3 class="box-title"><i class="fa fa-briefcase"></i> Konfirmasi Permohonan Pembelian Barang</h3>
                    </div>

                    <div class="box-body">
                        <form id="form-konfirm-ppb" enctype="multipart/form-data">
                            <div class="row">
                                <input type="hidden" name="id_ppb" id="id_ppb" value="<?php echo $data_ppb->id; ?>" required>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label >No PPB</label>
                                        <input type="text" class="form-control" value="<?php echo $data_ppb->no_ppb; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label >Pabrik</label>
                                        <input type="text" class="form-control" value="<?php echo $data_ppb->plant; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Tipe PO</label>
                                        <select class="form-control" name="tipe_po" id="tipe_po" required>
                                            <option value="HO">PO HO</option>
                                            <option value="SITE">PO SITE</option>
                                            <option value="REJECT">REJECT</option>
                                        </select>
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
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal PPB</label>
                                        <input type="text" class="form-control" value="<?php echo $data_ppb->tanggal_format; ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Konfirmasi</label>
                                        <input type="text" class="form-control" name="tanggal_konfirmasi" value="<?php echo date('d.m.Y'); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>Detail Barang</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped" id="table-detail" style="min-width: 1500px !important">
                                            <thead >
                                                <th><input type="checkbox" id="checkall"></th>
                                                <th style="width: 10%;">Kode Barang</th>
                                                <th>Deskripsi</th>
                                                <th>Deskripsi 2</th>
                                                <th style="width: 7%;">Tipe</th>
                                                <th style="width: 6%;">Jumlah<br>Diminta</th>
                                                <th style="width: 6%;">Satuan</th>
                                                <th>Harga</th>
                                                <th style="width: 6%;">Sisa<br>Stok</th>
                                                <th style="width: 6%;">Jumlah<br>Disetujui</th>
                                                <th style="width: 8%;">Status<br>Konfirmasi</th>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    foreach($data_ppb->detail as $detail) {
                                                        $checkbox = '<input type="checkbox" class="item-check" name="item_ppb[]" value="' . $detail->no_detail . '">';
                                                        if($detail->jumlah_po > 0) {
                                                            $checkbox = '';
                                                        }

                                                        $classification = "";
                                                        switch ($detail->classification_master) {
                                                            case 'A':
                                                                $classification = "Asset";
                                                                break;
                                                            case 'K':
                                                                $classification = "Expense";
                                                                break;
                                                            case 'I':
                                                                $classification = "Inventory";
                                                                break;
                                                            default:
                                                                $classification = "";
                                                                break;
                                                        }

                                                        $tipe_po = (!$detail->tipe_po) ? "-" : $detail->tipe_po;
                                                        $jumlah_disetujui = (!$detail->jumlah_disetujui) ? "" : number_format($detail->jumlah_disetujui, 2, '.', ',');

                                                        echo '<tr class="row-item">';
                                                        echo '    <td style="text-align: center; vertical-align: middle;">' . $checkbox . '</td>';
                                                        echo '    <td><input type="text" class="form-control" name="kode_barang_' . $detail->no_detail . '" value="' . $detail->kode_barang . '" readonly></td>';
                                                        echo '    <td><input type="text" class="form-control" value="' . $detail->nama_barang . '" readonly></td>';
                                                        echo '    <td><input type="text" class="form-control" value="' . $detail->deskripsi2 . '" readonly></td>';
                                                        echo '    <td><input type="hidden" name="classification_' . $detail->no_detail . '" value="' . $detail->classification_master . '" readonly><input type="text" class="form-control" name="tipe_barang_' . $detail->no_detail . '" value="' . $classification . '" readonly></td>';
                                                        echo '    <td><input type="text" class="form-control text-right" value="' . $detail->jumlah . '" readonly></td>';
                                                        echo '    <td><input type="text" class="form-control text-center" value="' . $detail->satuan . '" readonly></td>';
                                                        echo '    <td><input type="text" class="form-control text-right" value="' . number_format($detail->harga, 2, '.', ',') . '" readonly></td>';
                                                        echo '    <td><input type="text" class="form-control text-right" name="jumlah_stok_' . $detail->no_detail . '" value="' . number_format($detail->stok, 2, '.', ',') . '" readonly></td>';
                                                        echo '    <td><input type="text" class="form-control text-right angka" name="jumlah_disetujui_' . $detail->no_detail . '" value="' . $jumlah_disetujui . '" max="' . $detail->jumlah . '" readonly></td>';
                                                        echo '    <td><input type="text" class="form-control" name="tipe_po_' . $detail->no_detail . '" value="' . $tipe_po . '" readonly></td>';
                                                        echo '</tr>';
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="action">
                        <!-- <a href="<?php echo base_url().'plantation/transaksi/data'; ?>"><button type="button" class="btn btn-default" style="width:100px;">Kembali</button></a> -->
                        <button type="button" name="action_btn" class="btn btn-success pull-right" data-btn="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php $this->load->view('footer') ?>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/plantation/transaksi/ppb/konfirmppb.js"></script>