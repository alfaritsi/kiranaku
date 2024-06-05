<?php $this->load->view('header') ?>
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
                        <div class="row">
                            <?php if (isset($main) and $main->pic_approve != 'y'): ?>
                                <form class="form-horizontal" method="post">
                                    <p class="col-sm-12">Berikut adalah detail konfirmasi Maintenance Aset :</p>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label text-left col-sm-3">Jenis Maintenance</label>
                                            <div class="col-sm-9 form-control-static text-uppercase"><?php echo $main->jenis_tindakan; ?></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label text-left col-sm-3">Operator</label>
                                            <div class="col-sm-9 form-control-static"><?php echo $main->operator.' - '.$main->nama_operator; ?></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label text-left col-sm-3">Nama Aset</label>
                                            <div class="col-sm-9 form-control-static"><?php echo join('<br/>', explode('||', $main->detail_aset)); ?></div>
                                        </div>
                                        <?php if (isset($main->pic)) : ?>
                                            <div class="form-group">
                                                <label class="control-label text-left col-sm-3">PIC Aset</label>
                                                <div class="col-sm-9 form-control-static"><?php echo $main->pic . " " . $main->nama_pic; ?></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <h4><strong>Detail Maintenance Aset</strong></h4>
                                        <div class="form-group">
                                            <label class="control-label text-left col-sm-3">Kondisi Aset</label>
                                            <div class="col-sm-9 form-control-static"><?php echo $main->nama_kondisi; ?></div>
                                        </div>
                                        <table class="table-responsive table-striped table table-bordered">
                                            <thead>
                                            <tr>
                                                <?php if ($main->jenis_tindakan == 'perawatan') : ?>
                                                    <th>Komponen</th>
                                                    <th>Kegiatan</th>
                                                    <th>Keterangan</th>
                                                <?php elseif ($main->jenis_tindakan == 'perbaikan') : ?>
                                                    <th>Komponen</th>
                                                    <th colspan="2">Keterangan</th>
                                                <?php endif; ?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php if ($main->jenis_tindakan == 'perawatan') : ?>
                                                <?php foreach ($main->detail as $detail) : ?>
                                                    <tr>
                                                        <td class="text-uppercase text-bold"><?php echo $detail->nama_jenis_detail; ?></td>
                                                        <td><?php echo $detail->nama_periode_detail; ?></td>
                                                        <td><?php echo $detail->keterangan; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php elseif ($main->jenis_tindakan == 'perbaikan') : ?>
                                                <?php foreach ($main->detail as $detail) : ?>
                                                    <tr>
                                                        <td class="text-uppercase text-bold"><?php echo $detail->nama; ?></td>
                                                        <td colspan="2"><?php echo $detail->keterangan; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <input type="hidden" name="id_main" value="<?php echo $id_main ?>">
                                        <button name="action_btn" type="submit" class="btn btn-success">
                                            Konfirmasi
                                        </button>
                                    </div>
                                </form>

                            <?php else: ?>
                                <div class="col-sm-12">
                                    <p>Konfirmasi tidak ditemukan atau sudah dikonfirmasi sebelumnya.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/konfirmasi_user.js"></script>

<style>
    .small-box .icon {
        top: -13px;
    }
</style>