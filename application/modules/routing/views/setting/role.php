<!--
/*
@application  : Email Routing
@author       : Matthew Jodi
@contributor  :
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->

<?php $this->load->view('header') ?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-8">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Manage Roles</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped my-datatable-extends-order">
                            <thead>
                            <th>Role Name</th>
                            <th>Created Date</th>
                            <th>Created By</th>
                            <th>Last Modified</th>
                            <th>Last Modified By</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($roles as $dt) {
                                $enId = $this->generate->kirana_encrypt($dt->id_role);
                                echo "<tr>";
                                echo "<td>" . $dt->nama_role . "<br>" . $dt->label_active . "</td>";
                                echo "<td>" . $dt->tanggal_buat . "</td>";
                                echo "<td>" . $dt->login_buat_nama . "</td>";
                                echo "<td>" . $dt->tanggal_edit . "</td>";
                                echo "<td>" . $dt->login_edit_nama . "</td>";
                                echo "<td>
                          <div class='input-group-btn'>
                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
                            <ul class='dropdown-menu pull-right'>";
                                if ($dt->na == 'n') {
                                    echo "<li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                                    <li><a href='#' class='delete' data-delete='" . $enId. "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                                } else {
                                    echo "<li><a href='#' class='set_active-depart' data-activate='" . $dt->id_report . "'><i class='fa fa-check'></i> Set Aktif</a></li>";
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
                    <!-- /.box-body -->
                </div>
            </div> <!-- col 8-->
            <div class="col-sm-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title title-form">Buat Role Baru</h3>
                        <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Buat Role
                            Baru
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" class="form-report">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nama_role">Role Name</label>
                                <input type="text" class="form-control" required name="nama_role" id="nama_role"
                                       placeholder="Masukkkan Role Name">

                                <label for="topics">Akses Topic</label>
                                <input type="hidden" name="topics" id="topics">
                                <a id="btnModalTopics" class="form-control btn btn-default" href="javascript:void(0)" data-toggle="modal"
                                   data-target="#modalTopic">Show</a>

                                <label for="menus">Akses Menu Kiranalytics</label>
                                <input type="hidden" name="menus" id="menus">
                                <a id="btnModalMenus" class="form-control btn btn-default" href="javascript:void(0)" data-toggle="modal"
                                   data-target="#modalMenu">Show</a>

                                <label for="jabatans">Jabatan</label>
                                <input type="hidden" name="jabatans" id="jabatans">
                                <a id="btnModalJabatans" class="form-control btn btn-default" href="javascript:void(0)" data-toggle="modal"
                                   data-target="#modalJabatan">Show</a>

                                <label for="divisis">Divisi</label>
                                <input type="hidden" name="divisis" id="divisis">
                                <a id="btnModalDivisis" class="form-control btn btn-default" href="javascript:void(0)" data-toggle="modal"
                                   data-target="#modalDivisi">Show</a>

                                <label for="departemens">Departement</label>
                                <input type="hidden" name="departemens" id="departemens">
                                <a id="btnModalDepartemens" class="form-control btn btn-default" href="javascript:void(0)" data-toggle="modal"
                                   data-target="#modalDepartemen">Show</a>

                                <label for="report_function">Hak Export Data </label>
                                <div>
                                    <input type="radio" name="hak_export_data" value="1"> Ya &nbsp;
                                    <input type="radio" name="hak_export_data" value="0" checked> Tidak
                                </div>

                                <label for="report_function">Hak Akses Keuangan</label>
                                <div>
                                    <input type="radio" name="hak_data_keuangan" value="1"> Ya &nbsp;
                                    <input type="radio" name="hak_data_keuangan" value="0" checked> Tidak
                                </div>

                                <label for="report_function">Hak general Management</label>
                                <div>
                                    <input type="radio" name="hak_general_management" value="1"> Ya &nbsp;
                                    <input type="radio" name="hak_general_management" value="0" checked> Tidak
                                </div>

                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_role">
                            <button type="button" value="submit" name="action_btn" class="btn btn-success">Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalTopic" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Topics</h4>
            </div>
            <div class="modal-body">
                <table id="tableTopics" class="table table-bordered table-striped">
                    <thead>
                    <th></th>
                    <th>Topic Code</th>
                    <th>Topics</th>
                    <th>Status</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($topics as $dt) {
                        if ($dt->topic_code == null || $dt->topic_code == ' ') {
                            $code = "unknown";
                        } else {
                            $code = $dt->topic_code;
                        }

                        echo "<tr>";
                        echo "<td><input type='checkbox' name='cbTopic[]' value='$dt->id_topic' class='cbTopic cb'/></td>";
                        echo "<td>" . $code . "</td>";
                        echo "<td>" . $dt->topic . "</td>";
                        echo "<td>" . $dt->label_active . "</td>";

                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer text-center">
                <a class="btn btn-success"  data-dismiss="modal">TUTUP</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMenu" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Kiranalytics Menus</h4>
            </div>
            <div class="modal-body">
                <table id="tableMenus" class="table table-bordered table-striped">
                    <thead>
                    <th></th>
                    <th>Parent Menu</th>
                    <th>Menu</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($menus as $dt) {

                        echo "<tr>";
                        echo "<td><input type='checkbox' name='cbMenu[]' value='$dt->id_menu' class='cbMenu cb'/> </td>";
                        echo "<td>" . $dt->parent_menu . "</td>";
                        echo "<td>".$dt->menu."</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer text-center">
                <a class="btn btn-success"  data-dismiss="modal">TUTUP</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalJabatan" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Jabatan</h4>
            </div>
            <div class="modal-body">
                <table id="tableJabatans" class="table table-bordered table-striped">
                    <thead>
                    <th></th>
                    <th>HO/Pabrik</th>
                    <th>Nama Jabatan</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($jabatans as $dt) {
                        $kantor  = explode("-",$dt->nama);
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='cbJabatan[]' value='$dt->id_jabatan' class='cbJabatan cb'/> </td>";
                        echo "<td>" . ($kantor[0]=="HO"?"HO":"PABRIK"). "</td>";
                        echo "<td>".$kantor[1]."</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <div class="modal-footer text-center">
                    <a class="btn btn-success"  data-dismiss="modal">TUTUP</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDivisi" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Divisi</h4>
            </div>
            <div class="modal-body">
                <table id="tableDivisis" class="table table-bordered table-striped">
                    <thead>
                    <th></th>
                    <th>Nama Divisi</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($divisis as $dt) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='cbDivisi[]' value='$dt->id_divisi' class='cbDivisi cb'/> </td>";
                        echo "<td>".$dt->nama."</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <div class="modal-footer text-center">
                    <a class="btn btn-success"  data-dismiss="modal">TUTUP</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDepartemen" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Departement</h4>
            </div>
            <div class="modal-body">
                <table id="tableDepartemens" class="table table-bordered table-striped">
                    <thead>
                    <th></th>
                    <th>Pabrik</th>
                    <th>Nama Departement</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($departemens as $dt) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='cbDepartemen[]' value='$dt->id_departemen' class='cbDepartemen cb'/> </td>";
                        echo "<td>".$dt->plant_name."</td>";
                        echo "<td>".$dt->nama."</td>";
                        echo "</tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <div class="modal-footer text-center">
                    <a class="btn btn-success"  data-dismiss="modal">TUTUP</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('footer') ?>

<script src="<?php echo base_url() ?>assets/apps/js/routing/roles.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>