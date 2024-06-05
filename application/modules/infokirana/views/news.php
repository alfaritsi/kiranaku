<?php
$this->load->view('header')
?>

    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs pull-right">
                            <li class="pull-left header"><i class="fa fa-bullhorn"></i><?php echo $title; ?></li>
                            <li class="active"><a href="#terbaru" data-toggle="tab">Terbaru</a></li>
                            <li><a href="#terkomentar" data-toggle="tab">Terkomentar</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="well well-sm ">
                                <form method="get">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-3">
                                            <div class="input-group"><label class="input-group-addon">Awal</label><input
                                                        type="text" name="awal" value="<?php echo date('d.m.Y',strtotime($tgl_awal))?>"
                                                        class="form-control datepicker"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="input-group-addon"> Akhir</label>
                                                <input type="text" name="akhir" value="<?php echo date('d.m.Y',strtotime($tgl_akhir))?>" class="form-control datepicker" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane active" id="terbaru">
                                <?php
                                foreach ($news['terbaru'] as $newsTerbaru) {
                                    ?>
                                    <div class="post">
                                        <div class="user-block">
                                            <a target="_blank" href="<?php echo site_url('infokirana/detail/' . $newsTerbaru->id_news) ?>">
                                                <h3><?php echo $newsTerbaru->judul; ?></h3>
                                            </a>
                                            <span>
                                            by <?php
                                                if (isset($newsTerbaru->id_karyawan))
                                                    echo anchor('#', $newsTerbaru->nama_karyawan);
                                                else
                                                    echo $newsTerbaru->nama_karyawan;
                                                ?>
                                        </span>
                                        </div>
                                        <p>
                                            <?php echo $newsTerbaru->isi; ?>
                                            <?php echo anchor('infokirana/detail/' . $newsTerbaru->id_news, 'selengkapnya') ?>
                                        </p>
                                        <ul class="list-inline">
                                            <li><i class="fa fa-calendar"></i> <?php echo $newsTerbaru->tanggal; ?></li>
                                            <li><i class="fa fa-clock-o"></i> <?php echo $newsTerbaru->jam; ?></li>
                                            <li class="pull-right">Komentar (<?php echo $newsTerbaru->komentar; ?>)</li>
                                        </ul>
                                    </div>
                                    <?php
                                }

                                ?>
                            </div>
                            <div class="tab-pane" id="terkomentar">
                                <?php
                                foreach ($news['terkomentar'] as $newsTerkomentar) {
                                    ?>
                                    <div class="post">
                                        <div class="user-block">
                                            <a href="<?php echo base_url('infokirana/detail/' . $newsTerkomentar->id_news) ?>">
                                                <h3><?php echo $newsTerkomentar->judul; ?></h3>
                                            </a>
                                            <span>
                                            by <?php
                                                if (!isset($newsTerkomentar->id_karyawan))
                                                    echo anchor(base_url('#'), $newsTerkomentar->nama_karyawan);
                                                else
                                                    echo $newsTerkomentar->nama_karyawan;
                                                ?>
                                        </span>
                                        </div>
                                        <p>
                                            <?php echo $newsTerkomentar->isi; ?>
                                            <?php echo anchor(base_url('infokirana/detail/' . $newsTerkomentar->id_news), 'selengkapnya') ?>
                                        </p>
                                        <ul class="list-inline">
                                            <li><i class="fa fa-calendar"></i> <?php echo $newsTerkomentar->tanggal; ?>
                                            </li>
                                            <li><i class="fa fa-clock-o"></i> <?php echo $newsTerkomentar->jam; ?></li>
                                            <li class="pull-right">Komentar (<?php echo $newsTerkomentar->komentar; ?>
                                                )
                                            </li>
                                        </ul>
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
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css" />
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/infokirana/infokirana.js"></script>