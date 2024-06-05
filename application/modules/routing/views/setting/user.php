<!--
/*
@application  : Email Routing Setting User
@author       : Octe Reviyanto Nugroho
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
                        <h3 class="box-title"><strong>Manage User Routing</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6" style="margin-bottom: 10px">
                                <form method="post">
                                    <div class="form-group">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon">Plant</div>
                                            <select class="form-control" id="plantFilter" name="plant" data-placeholder="Pilih">
                                                <option value="HO">HO</option>
                                                <?php foreach ($companies as $company) {
                                                    $selected = "";
                                                    if (isset($_POST['plant']) && $_POST['plant'] == $company->plant)
                                                        $selected = "selected";
                                                    echo "<option value='$company->plant' $selected>$company->plant_name</option>";
                                                } ?>
                                            </select><span class="input-group-btn"><button class="btn btn-success"
                                                                                           type="submit">Cari</button></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped my-datatable-extends-order">
                            <thead>
                            <th>Business Unit</th>
                            <th>Nik Karyawan</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Tipe Karyawan</th>
                            <th>Company</th>
                            <th>Last Login</th>
                            <th>Active</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($users as $dt) {
                                $enId = $this->generate->kirana_encrypt($dt->id_user);
                                $labelAktif = '<span class="label label-success">ACTIVE</span>';
                                if($dt->na=='y')
                                    $labelAktif = '<span class="label label-danger">NOT ACTIVE</span>';
                                echo "<tr>";
                                echo "<td>$dt->business_unit</td>";
                                echo "<td>$dt->nik</td>";
                                echo "<td>$dt->nama</td>";
                                echo "<td>$dt->jabatan</td>";
                                echo "<td>$dt->email</td>";
                                echo "<td>$dt->status</td>";
                                echo "<td>" . $dt->tipe_karyawan . "</td>";
                                echo "<td></td>";
                                echo "<td>$dt->last_login</td>";
                                echo "<td>$labelAktif</td>";
                                echo "<td>
                          <div class='input-group-btn'>
                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
                            <ul class='dropdown-menu pull-right'>";
                                echo "<li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
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
            </div>
            <div class="col-sm-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title title-form">Edit User</h3>
                        <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">
                            Edit User
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" class="form-user">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nik">NIK Karyawan</label>
                                <input type="text" class="form-control" readonly id="nik">

                                <label for="business_unit">Business Unit</label>
                                <input type="text" class="form-control" readonly id="business_unit">

                                <label for="nama">Nama Lengkap</label>
                                <input type="text" class="form-control" readonly id="nama">

                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" readonly id="jabatan">

                                <label for="email">Email</label>
                                <input type="text" class="form-control" readonly id="email">

                                <label for="status">Status</label>
                                <input type="text" class="form-control" readonly id="status">

                                <label for="tipe_karyawan">Tipe Karyawan</label>
                                <input type="text" class="form-control" readonly id="tipe_karyawan">

                                <label for="company">Company</label>
                                <input type="hidden" name="companies" id="companies">
                                <a id="btnModalCompanies" class="form-control btn btn-default" href="#" data-toggle="modal"
                                   data-target="#modalCompany">Show</a>
                                <span style="display:block;margin-top: 5px;margin-bottom: 10px;" class="hide text-warning companiesSaved">
                                    <i class="fa fa-warning"></i>&nbsp;Data belum disimpan
                                </span>

                                <label for="buyers">Buyer</label>
                                <input type="hidden" name="buyers" id="buyers">
                                <a id="btnModalBuyers" class="form-control btn btn-default" href="#" data-toggle="modal"
                                   data-target="#modalBuyer">Show</a>
                                <span style="display:block;margin-top: 5px;margin-bottom: 10px;" class="hide text-warning buyersSaved">
                                    <i class="fa fa-warning"></i>&nbsp;Data belum disimpan
                                </span>

                            </div>
                        </div>
                        <div class="box-footer hide">
                            <input type="hidden" name="id_user">
                            <button type="button" value="submit" name="action_btn" class="btn btn-success">Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalCompany" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Company</h4>
            </div>
            <div class="modal-body">
                <table id="tableCompanies" class="table table-bordered table-striped">
                    <thead>
                    <th></th>
                    <th>Plant</th>
                    <th>Nama Pabrik</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($companies as $dt) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='cbCompany[]' value='$dt->id_plant' data-plant='$dt->plant' class='cbCompany cb'/> </td>";
                        echo "<td>" . $dt->plant . "</td>";
                        echo "<td>" . $dt->plant_name. "</td>";

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

<div class="modal fade" id="modalBuyer" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Buyer</h4>
            </div>
            <div class="modal-body">
                <table id="tableBuyers" class="table table-bordered table-striped">
                    <thead>
                    <th></th>
                    <th>Nama Buyer</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($buyers as $dt) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='cbBuyer[]' value='$dt->id' class='cbBuyer cb'/> </td>";
                        echo "<td>" . $dt->name. "</td>";

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

<?php $this->load->view('footer') ?>

<script src="<?php echo base_url() ?>assets/apps/js/routing/user.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>