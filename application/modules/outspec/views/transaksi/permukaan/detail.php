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
                                        Detail Cek Permukaan
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
                                    <strong>Kondisi Pallet :</strong> <span><?php echo ($data_cek->kondisi_pallet == "OK" ? "OK" : "NOT OK"); ?></span><br>
                                    <?php
                                    if ($data_cek->kondisi_pallet != "OK") {
                                        echo '<strong>Catatan :</strong> <span>' . $data_cek->catatan . '</span>';
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-4 invoice-col">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_bales" data-toggle="tab" aria-expanded="true">Data Bales</a></li>
                                    <li><a href="#tab_label" data-toggle="tab" >Data Label</a></li>
                                    <li id="link-tab-attachment" class="hidden"><a href="#tab_attachment" data-toggle="tab" >Foto</a></li>
                                </ul>

                                <div class="tab-content" style="min-height: 300px;">
                                    <!-- tab data bales -->
                                    <div class="tab-pane active" id="tab_bales">
                                    </div>
                                    <!-- tab data label -->
                                    <div class="tab-pane" id="tab_label">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th>Label</th>
                                                    <th style="width: 10%;">OK</th>
                                                </tr>
                                            </thead>
                                            <tbody id="list-label"></tbody>
                                        </table>
                                    </div>
                                    <!-- tab data attachment -->
                                    <div class="tab-pane" id="tab_attachment">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="attachment-cek"></div>
                                            </div>
                                        </div>
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
<script src="<?php echo base_url() ?>assets/apps/js/outspec/transaksi/permukaan/detail.js?<?php echo time(); ?>"></script>