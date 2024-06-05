<?php
/**
 * @application  : View Management Reporting App Settings (Reports)
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
            <div class="col-sm-8">
                <div class="box box-success">
                    <div class="box-header">
                        <div class="pull-left" style="margin-right: 10px;">
                            <a href="<?php echo base_url('mrapp/reports')?>" class="btn btn-success btn-xs">Kembali</a>
                        </div>
                        <h3 class="box-title"><strong>Manage <?php echo $title; ?></strong></h3>
                        <div class="clearfix"></div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered my-datatable-extends-order">
                            <thead>
                            <th>Alias</th>
                            <th>Nama Parameter</th>
                            <th>Kode Kolom Hasil</th>
                            <th>Deskripsi</th>
                            <th>Aktif</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($datas as $dt) {
                                $enId = $this->generate->kirana_encrypt($dt->id_report_parameter);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                echo "<tr>";
                                echo "<td>" . $dt->parameter_alias . "</td>";
                                echo "<td>" . $dt->parameter_nama . "</td>";
                                echo "<td>" . $dt->parameter_kolom . "</td>";
                                echo "<td>" . $dt->deskripsi . "</td>";
                                echo "<td>" . $na . "</td>";

                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";


                                if ($dt->na == 'n') {
                                    echo "<li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                                      <li><a href='#' class='delete' data-delete='" . $enId . "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                                    echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='delete_na'><i class='fa fa-times'></i> Deactivate</a></li>";
                                } else {
                                    echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='activate_na'><i class='fa fa-check'></i> Activate</a></li>";
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
                    <form role="form" class="form-mrapp-report-parameters" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parameter_nama">Nama Parameter</label>
                                <input type="text" class="form-control" name="parameter_nama" id="parameter_nama"
                                       placeholder="Masukkkan Nama Parameter" required="required">
                            </div>
                            <div class="form-group">
                                <label for="parameter_alias">Alias</label>
                                <input type="text" class="form-control" name="parameter_alias" id="parameter_alias"
                                       placeholder="Masukkkan Alias" required="required">
                            </div>
                            <div class="form-group">
                                <label for="parameter_kolom">Kolom</label>
                                <input type="text" class="form-control" name="parameter_kolom" id="parameter_kolom"
                                       placeholder="Masukkkan Kolom">
                            </div>
                            <div class="form-group">
                                <label for="parameter_default">Nilai Default</label>
                                <input type="text" class="form-control" name="parameter_default" id="parameter_default"
                                       placeholder="Masukkkan Nilai Default">
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" id="deskripsi"
                                          placeholder="Masukkkan Deksripsi Report"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="parameter_default">Urutan</label>
                                <input type="number" class="form-control" name="urutan" id="urutan"
                                       placeholder="Masukkkan Urutan" required="required" value="0">
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_report" id="id_report" value="<?php echo $id_report; ?>">
                            <input type="hidden" name="id_report_parameter" id="id_report_parameter">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<style type="text/css">
    .datepicker{
        border-radius: 0;
    }
</style>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css" />
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/mrapp/parameters.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
</style>