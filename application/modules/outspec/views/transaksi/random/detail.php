<?php
$this->load->view('header')
?>
<div class="content-wrapper">
    <section class="content">
        <!-- Box View -->
        <div id="box-view">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h1 class="box-title" style="vertical-align:middle;">
                                        Detail Cek Random
                                    </h1>
                                </div>
                            </div>
                            <hr>
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <strong>No SI :</strong> <span><?php echo $data_cek->no_si; ?></span><br>
                                    <strong>Tahun Produksi :</strong> <span><?php echo $data_cek->tahun_produksi; ?></span><br>
                                    <strong>No Produksi :</strong> <span><?php echo $data_cek->no_produksi; ?></span><br>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    <strong>Tanggal Cek :</strong> <span><?php echo $data_cek->tanggal_format; ?></span><br>
                                    <strong>Pabrik :</strong> <span><?php echo $data_cek->plant; ?></span><br>
                                    <strong>Jenis Pallet :</strong> <span><?php echo number_format($data_cek->berat_pallet, 0, ',', '.') . ' Kg'; ?></span><br>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    <strong>Suhu Bales :</strong> <span><?php echo number_format($data_cek->suhu_bales, 2, ',', '.'); ?></span><br>
                                    <strong>Berat Bales :</strong> <span><?php echo number_format($data_cek->berat_bales, 2, ',', '.'); ?></span><br>
                                    <strong>Sample :</strong> <span><?php echo (($data_cek->sample == 1) ? 'Ya' : 'Tidak'); ?></span><br>
                                    <strong>Potong Tengah :</strong> <span><?php echo (($data_cek->potong_tengah == 1) ? 'Ya' : 'Tidak'); ?></span><br>
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_layer" data-toggle="tab" aria-expanded="true">Data Layer</a></li>
                                </ul>

                                <div class="tab-content" style="min-height: 300px;">
                                    <!-- tab data layer -->
                                    <div class="tab-pane active" id="tab_layer">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" id="id_cek" value="<?php echo $data_cek->id; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/outspec/transaksi/random/detail.js?<?php echo time(); ?>"></script>