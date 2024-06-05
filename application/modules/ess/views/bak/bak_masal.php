<?php
$this->load->view('header')
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-8">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>List <?php echo $title; ?></strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table my-datatable-extends-order table-bordered" id="menus-table" data-page-length="10"
                               style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th>Tanggal Absen</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Keterangan</th>
                                <th>Catatan</th>
                                <th>Employee</th>
                                <th data-orderable="false" data-searchable="false"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list_bak_massal as $dt) {

                                $enId = $this->generate->kirana_encrypt($dt->id_bak_massal);
                                $keterangan = "Datang Terlambat & Pulang Cepat";
                                if($dt->id_bak_alasan == ESS_BAK_ALASAN_PULANG_CEPAT)
                                    $keterangan = "Pulang Cepat";
                                else if($dt->id_bak_alasan == ESS_BAK_ALASAN_TERLAMBAT)
                                    $keterangan = "Datang Terlambat";
                                echo "<tr>";
                                echo "<td nowrap>" . $this->generate->generateDateFormat($dt->tanggal_bak). "</td>";
                                echo "<td>" . $dt->jam_bak_masuk. "</td>";
                                echo "<td>" . $dt->jam_bak_keluar. "</td>";
                                echo "<td>" . $keterangan . "</td>";
                                echo "<td>" . $dt->catatan. "</td>";
                                echo "<td>" . count(explode('.',$dt->karyawans)). " Karyawan</td>";
                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
				                            <ul class='dropdown-menu pull-right'>";
                                if ($dt->na == 'n') {
                                    echo "
                <li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                <li><a href='#' class='delete' data-delete='" . $enId . "'><i class='fa fa-trash-o'></i> Hapus</a></li>
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
                                        <label for="id_bak_alasan">Keterangan</label>
                                        <div>
                                            <select class="form-control select2" name="id_bak_alasan" id="id_bak_alasan"
                                                    data-placeholder="Masukkkan Keterangan" required="required">
                                                <option value="<?php echo ESS_BAK_ALASAN_TERLAMBAT; ?>">Datang Terlambat</option>
                                                <option value="<?php echo ESS_BAK_ALASAN_PULANG_CEPAT; ?>">Pulang Cepat</option>
                                                <option value="<?php echo ESS_BAK_ALASAN_KOMBINASI_DTG_PLG; ?>">Pulang Cepat & Terlambat</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_bak">Tanggal Absen</label>
                                        <div>
                                            <input type="text" class="form-control datepicker" name="tanggal_bak"
                                                   id="tanggal_bak"
                                                   placeholder="Masukkkan Tanggal Absen" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jam_bak_masuk">Jam Masuk</label>
                                        <div>
                                            <input type="text" class="form-control" name="jam_bak_masuk"
                                                   id="jam_bak_masuk" placeholder="Jam BAK masuk"
                                                   required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jam_bak_keluar">Jam Keluar</label>
                                        <div>
                                            <input type="text" class="form-control" name="jam_bak_keluar"
                                                   id="jam_bak_keluar" placeholder="Jam BAK keluar"
                                                   required="required" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="list_employee">List Employee</label>
                                        <div>
                                            <input type="hidden" id="karyawans" name="karyawans" required="required">
                                            <a id="btnModalKaryawan" class="form-control btn btn-default" href="#"
                                               data-toggle="modal"
                                               data-target="#modalKaryawan">Show</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="catatan">Catatan</label>
                                        <div>
                                            <textarea class="form-control" name="catatan" id="catatan"
                                                  placeholder="Masukkkan catatan" required="required"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_massal">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalKaryawan" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Karyawan</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="show-selected">Show</label>
                                <select class="form-control" id="show-selected" name="show-selected">
                                    <option value="all" selected>Show all</option>
                                    <option value="selected">Show selected</option>
                                    <option value="not-selected">Show not selected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="form_filter">Divisi</label>
                                <select class="form-control" id="filter_divisi" name="filter_divisi"
                                    data-placeholder="Pilih divisi"
                                >
                                    <option></option>
                                    <?php
                                    foreach ($divisi as $d) :
                                        echo "<option value='$d->nama' >$d->nama</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="filter_departemen">Departemen</label>
                                <select class="form-control" id="filter_departemen" name="filter_departemen"
                                    data-placeholder="Pilih departemen"
                                >
                                    <option></option>
                                    <?php
                                    foreach ($departemen as $d) :
                                        echo "<option value='$d->nama' >$d->nama</option>";
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-responsive table-striped"  data-page-length='15' data-length-change="false">
                    <thead>
                    <th></th>
                    <th>NIK</th>
                    <th>Nama Karyawan</th>
                    <th>Divisi</th>
                    <th>Departemen</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($karyawan as $dt) {
                        echo "<tr>";
                        echo "<td>$dt->id_karyawan</td>";
                        echo "<td>" . $dt->nik . "</td>";
                        echo "<td>" . $dt->nama . "</td>";
                        echo "<td>" . $dt->nama_divisi . "</td>";
                        echo "<td>" . $dt->nama_departemen . "</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <div class="modal-footer text-center">
                    <a class="btn btn-success" data-dismiss="modal">TUTUP</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet"
      href="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>

<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/bak_masal.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
</style>

