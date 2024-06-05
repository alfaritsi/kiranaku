<?php
$this->load->view('header')
?>
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h2 class="box-title text-success">
                                <?php echo $news->judul ?>
                            </h2>
                            <a href="<?php echo base_url('infokirana'); ?>" class="btn btn-xs btn-success pull-right">Kembali</a>
                            <ul class="list-inline text-muted" style="margin-bottom:0">
                                <li><i class="fa fa-user"></i>
                                    by <?php
                                    if (isset($news->id_karyawan))
                                        echo anchor('#', $news->nama_karyawan);
                                    else
                                        echo $news->nama_karyawan;
                                    ?>
                                </li>
                                <li><i class="fa fa-calendar"></i> <?php echo $news->tanggal; ?></li>
                                <li><i class="fa fa-clock-o"></i> <?php echo $news->jam; ?></li>
                            </ul>
                        </div>
                        <div class="box-body">
                            <?php echo nl2br($news->isi) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">


                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#komentars" data-toggle="tab">Komentar
                                    (<?php echo $news->komentar_publish; ?>)</a></li>
                            <li><a href="#kirim" data-toggle="tab">Kirim Komentar</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="komentars">
                                <div class="box-body box-comments">
                                    <?php
                                    foreach ($komentars as $komentar) {
                                        ?>
                                        <div class="box-comment">
                                            <!-- User image -->
                                            <img class="img-circle img-sm" src="http://via.placeholder.com/50?text=foto"
                                                 alt="User Image">

                                            <div class="comment-text">
                                                <div class="username">
                                                    <?php
                                                    if (isset($komentar->id_karyawan))
                                                        echo anchor('#', $komentar->nama_karyawan);
                                                    else
                                                        echo $komentar->nama_karyawan;
                                                    ?>
                                                    <ul class="list-inline pull-right" style="margin-bottom:0">
                                                        <li><i class="fa fa-calendar"></i> <?php echo $komentar->tanggal; ?></li>
                                                        <li><i class="fa fa-clock-o"></i> <?php echo $komentar->jam; ?></li>
                                                    </ul>
                                                </div><!-- /.username -->
                                                <?php echo $komentar->komentar ?>
                                            </div>
                                            <!-- /.comment-text -->
                                        </div>
                                        <!-- /.box-comment -->
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="tab-pane" id="kirim">
                                <div class="box-body">
                                    <form class="form-send-komentar">
                                        <div class="form-group">
                                            <textarea id="komentar" name="komentar" placeholder="Ketik komentar anda"
                                                      class="form-control"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="id_news" id="id_news"
                                                   value="<?php echo $news->id_news; ?>">
                                            <button type="button" name="action_btn" class="btn btn-success">Send
                                                Comment
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php $this->load->view('footer') ?>

<script src="<?php echo base_url() ?>assets/apps/js/infokirana/infokirana.js"></script>
