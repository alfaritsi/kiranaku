<?php
/**
 * @application  : View Jenis Sakit (Admin Settings)
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
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong><?php echo $title; ?> Karyawan</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6" style="margin-bottom: 10px">
                                <form method="post">
                                    <div class="form-group">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon">NIK</div>
                                            <input type="text" class="form-control" id="search-nik"
                                                   value="<?php echo @$cari ?>"
                                                   name="cari" placeholder="Cari NIK"/>
                                            <span class="input-group-btn"><button class="btn btn-success" type="submit">Cari</button></span>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                        <table class="table table-bordered my-datatable-extends">
                            <thead>
                            <th>Nama (NIK)</th>
                            <th></th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($menuakses as $dt) {
                                $enId = $this->generate->kirana_encrypt($dt->nik);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                echo "<tr>";
                                echo "<td><a data-toggle='collapse' data-parent='#accordion' href='#$dt->nik'><i class='fa fa-tags'></i></a>&nbsp;".$dt->nama
                                    ."<div class='panel-collapse collapse text-info' style='padding-top:10px;' id='$dt->nik'><hr/>"
                                    .$this->dmenuakses->get_menu_akses($dt->nik)."</div>"
                                    ."</td>";

                                echo "<td>
				                          <a href='#' class='btn btn-default edit' data-edit='" . $enId . "' data-nama='".$dt->nama." (".$dt->nik.")'><i class='fa fa-pencil-square-o'></i> Compare</a>				                          
				                        </td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modalCompare" data-backdrop="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Set Compare Menu</h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">

                    <form class="form-horizontal form-compare">
                        <input type="hidden" id="id_karyawan" name="id_karyawan">
                        <div class="form-group">
                            <label for="kapasitas">Nama Karyawan</label>
                            <p class="form-control-static inama"></p>
                        </div>
                        <div class="form-group">
                            <label for="category_name">Compare Karyawan</label>
                            <select class="form-control select2" id="karyawans" name="karyawans[]" data-placeholder="Pilih karyawan" multiple>
                                <?php foreach ($karyawans as $karyawan) : ?>
                                    <option value="<?php echo $karyawan->id_karyawan ?>"><?php echo $karyawan->nama; ?> (<?php echo $karyawan->nik; ?>)</option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button name="action_btn" class="btn btn-success" type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/settings/menuakses/menuakses.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
</style>


