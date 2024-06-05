<?php
$this->load->view('header')
?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">


                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="well well-sm ">
                            <form method="get">
                                <div class="row">
                                    <div class="form-group col-md-offset-3 col-md-3">
                                        <div class="input-group"><label class="input-group-addon">Awal</label><input
                                                    type="text" name="awal"
                                                    value="<?php echo date('d.m.Y', strtotime($tgl_awal)) ?>"
                                                    class="form-control datepicker"></div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <div class="input-group">
                                            <label class="input-group-addon">Akhir</label>
                                            <input type="text" name="akhir" value="<?php echo date('d.m.Y',strtotime($tgl_akhir)) ?>"
                                                   class="form-control datepicker"/>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class='row'>
                            <?php
                            foreach ($news as $index => $kirananews) {
                                if($index % 3 == 0) echo "</div><div class='row'>";
                                ?>

                                <div class="col-sm-4">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h4>Edisi <?php echo $kirananews->tanggal; ?></h4>
                                            <p><?php echo $kirananews->judul ?></p>
                                        </div>
                                        <div class="icon">
                                            <img src="<?php echo $kirananews->gambar; ?>" alt="gambar berita" width="70">
                                        </div>
                                        <a href="<?php echo $kirananews->files; ?>" target="blank" class="small-box-footer">
                                            Detail <i class="fa fa-arrow-circle-right"></i>
                                        </a>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/infokirana/infokirana.js"></script>