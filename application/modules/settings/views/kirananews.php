<?php
/**
 * @application  : Setting (Admin Settings)
 * @author       : Octe Reviyanto Nugroho
 * @contributor  :
 *     1. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     2. <insert your fullname> (<insert your nik>) <insert the date>
 *        <insert what you have modified>
 *     etc.
 */

$this->load->view('header')
?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Setting <?php echo $title; ?></strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped my-datatable-extends-order">
                            <thead>
                                <th>Edisi</th>
                                <th>Judul</th>
                                <th>Creator</th>
                                <th>Publish</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($news as $dt) {
                                $enId = base64_encode($dt->id_kirananews);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                $author = (empty($dt->nama_karyawan)) ? 'Administrator' : $dt->nama_karyawan;
                                echo "<tr>";
                                echo "<td>" . date_format(date_create($dt->tanggal), "d.m.Y") . "</td>";
                                echo "<td>" . $dt->judul . "</td>";
                                echo "<td>" . ucwords($author) . "</td>";
                                echo "<td>" . $na . "</td>";

                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
                                if ($dt->na == 'n') {
                                echo "<li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                                          <li><a href='#' class='delete' data-delete='" . $enId . "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                                }
                                if ($dt->na == 'n') {
                                    echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='delete_na'><i class='fa fa-times'></i> Not Publish</a></li>";
                                } else {
                                    echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='activate_na'><i class='fa fa-check'></i> Publish</a></li>";
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
            <div class="col-sm-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title title-form">
                            Buat <?php echo(isset($title_form) ? $title_form : $title); ?></h3>
                        <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">
                            Buat <?php echo(isset($title_form) ? $title_form : $title); ?> Baru
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" class="form-master-formula" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="tanggal">Edisi</label>
                                <input type="text" class="form-control datepicker" name="tanggal" id="tanggal"
                                       placeholder="Masukkkan Tanggal Edisi" required="required">
                            </div>
                            <div class="form-group">
                                <label for="judul">Judul</label>
                                <input type="text" class="form-control" name="judul" id="judul"
                                       placeholder="Masukkkan Judul" required="required">
                            </div>
                            <div class="form-group">
                                <label for="gambar">Cover</label>
                                <input type="file" class="form-control" name="gambar" id="gambar"
                                       placeholder="Masukkkan Cover" required="required">
                            </div>
                            <div class="form-group">
                                <label for="files">File</label>
                                <input type="file" class="form-control" name="files" id="files"
                                       placeholder="Masukkkan File" required="required">
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css" />
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/settings/kirananews/kirananews.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
</style>


 