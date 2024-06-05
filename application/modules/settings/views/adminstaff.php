<?php
/**
 * @application  : View Admin Staff (Admin Settings)
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
                        <h3 class="box-title"><strong>Setting <?php echo $title; ?></strong></h3>

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
                                        </div>

                                    </div>
                                </form>

                            </div>
                            <div class="col-sm-6" style="margin-bottom: 10px">

                                <span class="pull-right">
                                    <a class="btn btn-sm btn-success"
                                       href="<?php echo base_url() . "assets/apps/templates/excels/tbl_ext.xls"; ?>">
                                        <i class="fa fa-download"></i>
                                        Download Format Ext
                                    </a>
                                    <a class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalImport">
                                        <i class="fa fa-cloud-download"></i>
                                        Import Nomor Ext
                                    </a>
                                </span>
                                <span class="clearfix"></span>
                            </div>
                        </div>
                        <table class="table table-bordered my-datatable">
                            <thead>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Departemen</th>
                            <th>Kantor/Pabrik</th>
                            <th>Email</th>
                            <th>Ext</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($staffs as $dt) {
                                $enId = $this->generate->kirana_encrypt($dt->id_karyawan);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                $kantor = ($dt->ho == 'y') ? "Head Office" : "Pabrik";
                                echo "<tr>";
                                echo "<td>" . $dt->nama . "</td>";
                                echo "<td>" . $dt->nik . "</td>";
                                echo "<td>" . $dt->nama_departemen . "</td>";
                                echo "<td>" . $kantor . "</td>";
                                echo "<td><a href='mailto:" . $dt->email . "' >$dt->email</a></td>";
                                echo "<td>" . $dt->telepon . "</td>";

                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";

                                echo "<li><a href='#' class='detail' data-edit='" . $enId . "' data-action='delete_na'><i class='fa fa-search'></i> Detail</a></li>";
                                echo "<li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil'></i> Edit</a></li>";
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
<div class="modal fade" tabindex="-1" role="dialog" id="modalEdit" data-backdrop="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="col-sm-12">
                <div class="nav-tabs-custom" id="tabs-edit">
                    <form role="form" class="form-settings-adminstaff" enctype="multipart/form-data">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab-ext" data-toggle="tab">
                                    Edit Nomor Ext
                                </a>
                            </li>
                            <li id="tabs-tab-hak">
                                <a href="#tab-foto" data-toggle="tab">Edit Foto</a>
                            </li>
                        </ul>
                        <div class="modal-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-ext">
                                    <div class="form-group">
                                        <label for="nama">NIK</label>
                                        <input type="text" class="form-control inik" name="nik"
                                               id="nik" disabled
                                               required="required">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control inama" name="nama"
                                               id="nama" disabled
                                               required="required">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Ext Telepon</label>
                                        <input type="text" class="form-control itelepon" name="telepon"
                                               id="telepon"
                                               >
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab-foto">
                                    <div class="form-group text-center">
                                        <img class="img-thumbnail img-responsive iimage" />
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Foto</label>
                                        <input type="file" class="form-control datepicker" name="gambar"
                                               id="gambar">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <input id="id" name="id" type="hidden">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" name="action_btn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modalDetail" data-backdrop="true">
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
<div class="modal fade" tabindex="-1" role="dialog" id="modalImport" data-backdrop="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form role="form" class="form-import-ext" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Import Data Ext Karyawan
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">File Excel</label>
                        <input type="file" class="form-control"
                               id="excel" name="excel"
                               required="required">
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" name="import_btn">Submit</button>
                </div>

            </form>
        </div>
    </div>

</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/settings/adminstaff/adminstaff.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
</style>
