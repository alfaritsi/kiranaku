<?php
$this->load->view('header')
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-8">
                <div class="box box-success">
                    <div class="box-header">
                        <div class="box-tools">
                            <a class="btn btn-xs btn-success" href="<?php echo base_url('spk/master/jenisspk') ?>">Kembali</a>
                        </div>
                        <h3 class="box-title"><strong>List <?php echo $title; ?></strong></h3>
                        <h5><strong>Jenis Perjanjian :</strong> <?php echo $jenis_spk->jenis_spk ?></h5>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table my-datatable-extends-order table-bordered" id="menus-table"
                               data-page-length="10"
                               style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th width="30%">Nama Dokumen</th>
                                <th width="10%"><i class="fa fa-file"></i></th>
                                <th width="5%">Aktif</th>
                                <th data-orderable="false" data-searchable="false" width="5%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list as $dt) {
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square text-success'></i>" : "<i class='fa fa-minus-square text-danger'></i>";
                                echo "<tr>";
                                echo "<td>" . $dt->nama_dokumen . "</td>";
                                echo "<td class='text-center'><a href='" . $dt->url_files . "' target='_blank'><i class='fa fa-search'></i>&nbsp;Lihat </a> </td>";
                                echo "<td class='text-center'>" . $na . "</td>";
                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
				                            <ul class='dropdown-menu pull-right'>";
                                if ($dt->na == 'n') {
                                    echo "
                <li><a href='#' class='edit' data-edit='" . $dt->id_oto_jenis . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                <li><a href='#' class='delete' data-delete='" . $dt->id_oto_jenis . "'><i class='fa fa-trash-o'></i> Hapus</a></li>
                  ";
                                }
                                echo "    </ul>
				                          </div>
				                        </td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">

                <div class="nav-tabs-custom" id="tabs-edit">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab-edit" data-toggle="tab">
                                <strong class="title-form">
                                    Form <?php echo(isset($title_form) ? $title_form : $title); ?>
                                </strong>
                            </a>
                        </li>
                    </ul>
                    <form role="form" class="form-bak-masal" enctype="multipart/form-data">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-edit">
                                <div class="col-sm-12" style="margin-top: 20px;">
                                    <button type="button" class="btn btn-sm btn-default pull-right hidden btn-new">
                                        Buat <?php echo(isset($title_form) ? $title_form : $title); ?> Baru
                                    </button>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="tanggal_bak">Nama Dokumen</label>
                                        <div>
                                            <input type="hidden" name="gambar_old" id="gambar_old">
                                            <input type="text" class="form-control" name="nama_dokumen"
                                                   id="nama_dokumen"
                                                   placeholder="Masukkkan Nama Dokumen" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_bak">File Dokumen</label>
                                        <div>
                                            <input type="file" class="form-control" name="file[]"
                                                   id="file"
                                                   placeholder="Pilih Dokumen" required="required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_jenis_spk" value="<?php echo $jenis_spk->id_jenis_spk; ?>">
                            <input type="hidden" name="id_oto_jenis">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<!-- INCLUDE ASSET PLUGIN FILE VIEWER -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/css/jasny-bootstrap.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.css"/>
<script src="<?php echo base_url() ?>assets/plugins/jasny-bootstrap/js/jasny-bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<!-- END INCLUDE ASSET PLUGIN FILE VIEWER -->

<script src="<?php echo base_url() ?>assets/apps/js/spk/master_oto_jenis_spk.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>