<?php
/**
 * @application  : Info Kirana (Admin Settings)
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
                        <h3 class="box-title"><strong>Setting <?php echo $title; ?></strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table treetable" id="menus-table" style="font-size: 12px;">
                            <thead>
                                <th width="200">Nama Menu (Main)</th>
<!--                                <th>Parent Menu</th>-->
                                <th>URL</th>
                                <th>URL External</th>
                                <th>Urutan</th>
                                <th>Icon</th>
                                <th>NA</th>
                                <th></th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($menus as $dt) {

                                $enId = $this->generate->kirana_encrypt($dt->id_menu);
                                $pEnId = $this->generate->kirana_encrypt($dt->id_parent);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square text-success'></i>" : "<i class='fa fa-minus-square text-danger'></i>";
                                $pnode = ($dt->id_parent != 0) ? "data-tt-parent-id='$pEnId'" : "";
                                echo "<tr data-tt-id='$enId' $pnode>";
                                echo "<td nowrap>" . $dt->nama . "</td>";
//        echo "<td>" . ($dt->id_parent == 0 ? "Root" : "") . "</td>";
                                echo "<td>" . $dt->url . "</td>";
                                echo "<td>" . $dt->url_external . "</td>";
                                echo "<td>" . $dt->urut . "</td>";
                                echo "<td><i class='fa " . $dt->kelas . "'></i></td>";
                                echo "<td>" . $na . "</td>";

                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
				                            <ul class='dropdown-menu pull-right'>";
                                if ($dt->na == 'n') {
                                    echo "
                <li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                <li><a href='#' class='akses' data-akses='" . $enId . "'><i class='fa fa-users'></i> Hak Akses Karyawan</a></li>
                <li><a href='#' class='delete' data-delete='" . $enId . "'><i class='fa fa-trash-o'></i> Hapus</a></li>
                  ";
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
            <div class="col-sm-4">

                <div class="nav-tabs-custom" id="tabs-edit">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab-edit" data-toggle="tab">
                                <strong class="title-form">
                                    Buat <?php echo(isset($title_form) ? $title_form : $title); ?>
                                </strong>
                            </a>
                        </li>
                        <!--<li id="tabs-tab-hak" class="hide">
                            <a href="#tab-hak" data-toggle="tab">Edit Hak Akses Karyawan</a>
                        </li>-->
                    </ul>
                    <form role="form" class="form-settings-menus" enctype="multipart/form-data">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-edit">
                                <div class="col-sm-12" style="margin-top: 20px;">
                                    <button type="button" class="btn btn-sm btn-default pull-right hidden btn-new">
                                        Buat <?php echo(isset($title_form) ? $title_form : $title); ?> Baru
                                    </button>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="nama">Nama Menu</label>
                                        <input type="text" class="form-control datepicker" name="nama" id="nama"
                                               placeholder="Masukkkan Nama Menu" required="required">
                                    </div>
                                    <div class="form-group">
                                        <label for="id_parent">Parent Menu</label>
                                        <select id="id_parent" name="id_parent" class="form-control select2">
                                            <option value="0">Root</option>
                                            <?php foreach($menus as $menu):?>
                                            <option value="<?php echo $menu->id_menu;?>">
                                                <?php echo str_pad($menu->nama,strlen($menu->nama)+($menu->tree*2),"--",STR_PAD_LEFT);?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="url">URL</label>
                                        <input type="text" class="form-control datepicker" name="url" id="url"
                                               placeholder="Masukkkan URL" required="required">
                                    </div>
                                    <div class="form-group">
                                        <label for="url_external">URL External</label>
                                        <input type="text" class="form-control datepicker" name="url_external"
                                               id="url_external"
                                               placeholder="Masukkkan URL External">
                                    </div>
                                    <div class="form-group">
                                        <label for="urutan">Urutan</label>
                                        <input type="number" class="form-control datepicker" name="urutan" id="urutan"
                                               placeholder="Masukkkan Urutan" min="0">
                                    </div>
                                    <div class="form-group">
                                        <label for="kelas">Kelas</label>
                                        <input type="text" class="form-control datepicker" name="kelas" id="kelas"
                                               placeholder="Masukkkan Kelas" required="required">
                                    </div>
                                    <div class="form-group">
                                        <label for="id_level">Jabatan</label>
                                        <select id="id_level" name="id_level"
                                                class="form-control select2" data-placeholder="Pilih jabatan">
                                            <option></option>
                                            <?php
                                            foreach ($levels as $level) {
                                                echo "<option value='$level->id_level'>$level->nama</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="divisi_akses">Divisi</label>
                                        <input type="hidden" name="divisi_akses" id="divisi_akses2">
                                        <select id="divisi_akses" multiple
                                                class="form-control">
                                            <?php
                                            foreach ($divisis as $divisi) {
                                                echo "<option value='$divisi->id_divisi'>$divisi->nama ($divisi->id_divisi)</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="departemen_akses">Department</label>
                                        <input type="hidden" id="departemen_akses2" name="departemen_akses" />
                                        <select id="departemen_akses" multiple
                                                class="form-control">
                                            <?php
                                            foreach ($departments as $department) {
                                                echo "<option value='$department->id_departemen'>$department->nama ($department->id_departemen | " . strtoupper($department->gsber) . " )</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="notification_categories">Notification Categories</label>
                                        <input type="hidden" id="notification_categories2" name="notification_categories">
                                        <select id="notification_categories" multiple
                                                class="form-control select2">
                                            <?php
                                            foreach ($notif_categories as $notif_category) {
                                                echo "<option value='$notif_category->alias_code'>$notif_category->category_name</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="report_function">Buka Halaman Baru</label>
                                        <div>
                                            <input class="target" type="radio" name="target" value="_blank"> Ya &nbsp;
                                            <input class="target" type="radio" name="target" value="_self" checked> Tidak
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="report_function">Ke Portal Lama</label>
                                        <div>
                                            <input class="oldportal" type="radio" name="na_oldportal" value="1"> Ya &nbsp;
                                            <input class="oldportal" type="radio" name="na_oldportal" value="0" checked> Tidak
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="tab-pane" id="tab-hak">
                                <div class="box-body">
                                    <div class="form-group">
                                        <button class="btn btn-default btn-xs" id="pilih_semua" type="button">Pilih semua karyawan</button>
                                        <button class="btn btn-info btn-xs" id="pilih_clear" type="button">Clear</button>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>

                <div class="nav-tabs-custom hide" id="tabs-akses">
                    <ul class="nav nav-tabs">
                        <li id="tabs-tab-hak" class="active">
                            <a href="#tab-hak-akses" data-toggle="tab">Edit Hak Akses Karyawan</a>
                        </li>
                    </ul>

                    <form class="form-horizontal form-settings-hak-akses">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-hak-akses">
                                <div class="col-sm-12" style="margin-top: 20px;">
                                    <button type="button" class="btn btn-sm btn-default pull-right btn-new">
                                        Buat <?php echo(isset($title_form) ? $title_form : $title); ?> Baru
                                    </button>
                                </div>
                                <div class="box-body">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="nama">Nama Menu</label>
                                            <p class="form-control-static" id="nama"></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="nik_akses">Akses Karyawan</label>
                                            <input type="hidden" name="nik_akses" id="nik_akses2">
                                            <select id="nik_akses" multiple
                                                    class="form-control">
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="nik_akses_ktp">User KTP</label>
                                            <select id="nik_akses_ktp" multiple
                                                    class="form-control">
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="box-footer">
                                    <input type="hidden" name="id">
                                    <button type="button" name="action_hak_btn" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.css"/>
<!--<link rel="stylesheet" href="--><?php //echo base_url() ?><!--assets/plugins/treetable/css/bootstrap-treefy.min.css"/>-->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.theme.default.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.custom.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui-1.10.3.theme.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/multiselect/prettify.css"/>

<!--<script src="--><?php //echo base_url() ?><!--assets/plugins/treetable/bootstrap-treefy.js"></script>-->
<script src="<?php echo base_url() ?>assets/plugins/jquery.treetable/jquery.treetable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery-ui.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/jquery.multiselect.filter.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/multiselect/prettify.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/settings/menus/menus.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
</style>


