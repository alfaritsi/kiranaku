<?php
/**
 * @application  : View Approval (Admin Settings)
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
                        <table class="table table-bordered my-datatable-extends-order">
                            <thead>
                            <th>NIK</th>
                            <th>Atasan</th>
                            <th>Atasan Email</th>
                            <th>Last Update</th>
                            <th>Aktif</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($approval as $dt) {
                                $enId = $this->generate->kirana_encrypt($dt->id_atasan_master);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                echo "<tr>";
                                echo "<td>" . $dt->nik . "</td>";
                                echo "<td>" . $dt->atasan . "</td>";
                                echo "<td>" . $dt->atasan_email . "</td>";
                                echo "<td>" . $dt->nama . "<br>".$dt->tanggal."</td>";
                                echo "<td>" . $na . "</td>";

                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";

                                echo "<li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                                      <li><a href='#' class='delete' data-delete='" . $enId . "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
//                                if ($dt->na == 'n') {
//                                    echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='delete_na'><i class='fa fa-times'></i> Deactivate</a></li>";
//                                } else {
//                                    echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='activate_na'><i class='fa fa-check'></i> Activate</a></li>";
//                                }
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
                    <form role="form" class="form-settings-approval" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input type="text" class="form-control" name="nik" id="nik"
                                       placeholder="Masukkkan NIK" required="required">
                            </div>
                            <div class="form-group">
                                <label for="atasan">Atasan</label>
                                <input type="text" class="form-control" name="atasan" id="atasan"
                                       placeholder="Masukkkan Atasan" required="required">
                                <span class="help-block text-sm">
                                    1) Jika Hanya Satu, akhiri dengan tanda '.' ex.(5555.)<br/>
                                    2)Jika lebih dari satu, batasi dan diakhiri dengan tanda '.' ex.(5555.6666.)
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="atasan_email">Atasan Email</label>
                                <input type="text" class="form-control" name="atasan_email" id="atasan_email"
                                       placeholder="Masukkkan Atasan Email" required="required">
                                <span class="help-block text-sm">
                                    1) Jika Hanya Satu, akhiri dengan tanda '.' ex.(5555.)<br/>
                                    2)Jika lebih dari satu, batasi dan diakhiri dengan tanda '.' ex.(5555.6666.)
                                </span>
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
<script src="<?php echo base_url() ?>assets/apps/js/settings/approval/approval.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<!--export to excel-->
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>

<style>
    .small-box .icon {
        top: -13px;
    }
</style>


