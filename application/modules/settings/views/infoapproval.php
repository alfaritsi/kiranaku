<?php
/**
 * @application  : View Info Approval    (Admin Settings)
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
                        <h3 class="box-title"><strong><?php echo $title; ?></strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6" style="margin-bottom: 10px">
                                <form method="post">
                                    <div class="form-group">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon">Pabrik</div>
                                            <select class="form-control" id="search-pabrik"
                                                    name="pabrik[]" placeholder="Cari NIK" multiple>
                                                <?php
                                                foreach ($pabrik as $pb) {
                                                    if(in_array($pb->kode,$_POST['pabrik']))
                                                        echo "<option value='$pb->kode' selected>$pb->nama</option>";
                                                    else
                                                        echo "<option value='$pb->kode'>$pb->nama</option>";
                                                }
                                                ?>
                                            </select>
                                            <span class="input-group-btn"><button class="btn btn-success" type="submit">Cari</button></span>
                                        </div>

                                    </div>
                                </form>

                            </div>
                            <div class="col-sm-6" style="margin-bottom: 10px">

                                <span class="pull-right">
                                    <a class="btn btn-sm btn-success export-ho" href="<?php echo base_url()."settings/approval/detail_export_ho"; ?>">
                                        <i class="fa fa-file-excel-o"></i>
                                        Export Excel HO
                                    </a>
                                    <a class="btn btn-sm btn-success export-pabrik" href="<?php echo base_url()."settings/approval/detail_export_pabrik"; ?>">
                                        <i class="fa fa-file-excel-o"></i>
                                        Export Excel Pabrik
                                    </a>
                                </span>
                                <span class="clearfix"></span>
                            </div>
                        </div>
                        <table class="table table-bordered my-datatable-extends-order">
                            <thead>
                            <!--                            <th>No</th>-->
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Entity</th>
                            <th>Bagian</th>
                            <th>Ext</th>
                            <th>Approval</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            foreach ($approval as $dt) {
                                $lokasi = ($dt->ho == 'y') ? "Head Office" : $dt->nama_pabrik;
                                if ($dt->ho == 'y') {
                                    $bagian = (empty($dt->nama_departemen)) ? $dt->nama_divisi : $dt->nama_departemen;
                                } else {
                                    $bagian = (empty($dt->nama_seksi)) ? $dt->nama_departemen : $dt->nama_seksi;
                                    $bagian = (empty($bagian)) ? $dt->nama_sub_divisi : $bagian;
                                    $bagian = (empty($bagian)) ? $dt->nama_pabrik : $bagian;
                                }
                                $enId = $this->generate->kirana_encrypt($dt->id_karyawan);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                echo "<tr>";
//                                echo "<td>" . $i++ . "</td>";
                                echo "<td>" . $dt->nama . "</td>";
                                echo "<td>" . $dt->nik . "</td>";
                                echo "<td>" . $lokasi . "</td>";
                                echo "<td>" . $bagian . "</td>";
                                echo "<td>" . $dt->telepon . "</td>";
                                echo "<td><ul>";
                                foreach ($dt->atasan_nama as $atasan)
                                {
                                    echo "<li>$atasan</li>";
                                }
                                echo "</ul></td>";

                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";
                                echo "<li><a href='#' class='detail' data-detail='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Detail</a></li>";

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
        </div>
    </section>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modalDetail" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="col-sm-12">
                <div class="nav-tabs-custom" id="tabs-edit">
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <img id="user-image" class="img-thumbnail img-responsive iimage" />
                        </div>

                        <div class="form-group">
                            <label for="nama">NIK</label>
                            <input type="text" class="form-control inik"
                                   id="detail-nik" disabled
                                   required="required">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control inama"
                                   id="detail-nama" disabled
                                   required="required">
                        </div>
                        <div class="form-group">
                            <label for="nama">Email</label>
                            <input type="text" class="form-control iemail" disabled
                                   required="required">
                        </div>
                        <div class="form-group">
                            <label for="nama">Departemen</label>
                            <input type="text" class="form-control idepartemen" disabled
                                   required="required">
                        </div>
                        <div class="form-group">
                            <label for="nama">Ext Telepon</label>
                            <input type="text" class="form-control itelepon" disabled
                                   id="detail-telepon">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $this->load->view('footer') ?>

<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.custom.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.theme.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/prettify.css"/>

<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/prettify.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/settings/approval/infoapproval.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
</style>


