<?php $this->load->view('header') ?>
<style>
    .tab-text {
        /* display: inline-block; */
        margin-left: 40px;
    }
</style>
<!-- customs apps css -->

<div class="content-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <a href="<?php echo base_url().'tamu/event/data'; ?>"><button type="button" class="btn btn-success" style="width:100px;"><i class="fa fa-angle-left"></i> Kembali</button></a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h1 class="box-title">Detail Event</h1>
                        <hr>
                        <div class="row invoice-info">
                            <div class="col-sm-12 invoice-col">
                                <table>
                                    <tr>
                                        <td><strong>Nama Event</strong></td>
                                        <td><span>: <?php echo $event->nama_event;?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal</strong></td>
                                        <td><span>: <?php echo $event->tanggal_format;?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Waktu</strong></td>
                                        <td><span>: <?php echo $event->waktu_mulai_format.' - '.$event->waktu_selesai_format; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>PIC</strong></td>
                                        <td><span>: <?php echo $event->nama_pic;?></span></td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align:middle;"><strong>Pesan/Catatan</strong></td>
                                        <td><span>: <?php echo $event->pesan;?></span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_peserta" data-toggle="tab" aria-expanded="true">Peserta</a></li>
                            </ul>
                            
                            <div class="tab-content" style="min-height: 300px;">
                                <!-- tab peserta event -->
                                <div class="tab-pane active" id="tab_peserta">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input id="id_event" type="hidden" value="<?php echo $event->id; ?>">
                                            <button class="btn btn-success btn_upload"><i class="fa fa-plus-square"></i> Upload Data Peserta</button>
                                            <button class="btn btn-default send_email pull-right" data-id_event="<?php echo $event->id; ?>"><i class="fa fa-send"></i> Kirim Email</button>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="25">
                                                <thead>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Perusahaan</th>
                                                    <th>Telepon</th>
                                                    <th>NIK KTP</th>
                                                    <th>&nbsp;</th>
                                                    <th>&nbsp;</th>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                            <form role="form" class="form-upload-peserta">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Master Event</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="file">File Excel</label>
                                        <input type="file" class="form-control" name="file_excel" id="file_excel" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input name="id_event" type="hidden" value="<?php echo $event->id; ?>">
                                    <button id="btn_save" type="button" class="btn btn-success" name="action_btn_save">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/apps/js/tamu/event/detail.js"></script>