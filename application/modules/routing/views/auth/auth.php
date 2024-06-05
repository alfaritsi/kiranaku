<!--
/*
@application  : Auth Control Report
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
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Authorization Control Report</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6" style="margin-bottom: 10px">
                                <form method="post">
                                    <div class="form-group">
                                        <div class="input-group input-group-sm">
                                            <div class="input-group-addon">Role</div>
                                            <select class="form-control" id="role" name="role" data-placeholder="Pilih">
                                                <option></option>
                                                <?php foreach ($roles as $role) {
                                                    $selected = "";
                                                    if(isset($_POST['role']) && $_POST['role']==$role->id_role)
                                                        $selected="selected";
                                                    echo "<option value='$role->id_role' $selected>$role->nama_role</option>";
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped my-datatable-extends-order">
                            <thead>
                                <th>Nik Karyawan</th>
                                <th>Nama Karyawan</th>
                                <th>HO/Pabrik</th>
                                <th>Bagian</th>
                                <th>Plant</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($auths as $dt) {
                                echo "<tr>";
                                echo "<td>$dt->nik</td>";
                                echo "<td>$dt->nama</td>";
                                echo "<td>".($dt->ho == "n" ? "Pabrik" : "HO")."</td>";
                                echo "<td>$dt->nama_jabatan_karyawan</td>";
                                echo "<td>$dt->plant</td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>

<script src="<?php echo base_url() ?>assets/apps/js/routing/auth.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>