<!--
/*
	@application  		: Travel 
		@author       	: Airiza Yuddha (7849)
		@contributor  	:
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
                        <h3 class="box-title pull-left"><strong><?php echo $title; ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered table-striped" id="sspTable"
                               data-order='[[1,"asc"]]'>
                            <thead>
                            <tr>
                                <th>Role</th>
                                <th>Level</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title pull-left"><strong><?php echo $title_form; ?></strong></h3>
                        <div class="pull-right">
                            <button type="button"
                                    class="btn btn-sm btn-default"
                                    id="btn-new"
                                    style="display:none">Buat Baru
                            </button>
                        </div>
                    </div>
                    <form role="form" class="form-master-role">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="nama_role">Role</label>
                                <div>
                                    <input type="text" class="form-control" id="nama_role" name="nama_role"
                                           placeholder="Masukkkan nama role" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="level">Level</label>
                                <div>
                                    <input type="number" class="form-control" id="level" name="level" min='0'
                                           placeholder="Masukkkan level role">
                                </div>
                            </div>

                            <fieldset class="fieldset-success">
                                <legend class="text-left">Form SPD</legend>
                                <div class="row" id="div_spd">
                                    <div class="form-group">
                                        <label for="if_approve_spd">Jika Disetujui</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_approve_spd_hidden"
                                                   id="if_approve_spd_hidden" placeholder="Masukkan role ketika Approve"
                                                   required="required">
                                            <select class="form-control select2" id="if_approve_spd"
                                                    name="if_approve_spd" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Disetujui">
                                                <?php
                                                echo "<option value='0' > Silahkan Pilih Approval </option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                echo "<option value='99' > Finish </option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="transport_booked">Validasi</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="hidden" name="v_transport_spd" value="0">
                                                        <input type="checkbox" name="v_transport_spd"
                                                               id="v_transport_spd" value="1">
                                                        &nbsp;<small>Sudah dibelikan Tiket Transport & Penginapan
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="if_decline_spd">Jika Ditolak/Revisi</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_decline_spd_hidden"
                                                   id="if_decline_spd_hidden"
                                                   placeholder="Masukkan role ketika Decline">
                                            <select class="form-control select2" id="if_decline_spd"
                                                    name="if_decline_spd" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Ditolak">
                                                <?php
                                                echo "<option value='0' > Silahkan Pilih Approval </option>";
                                                echo "<option value='0' > User</option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="fieldset-success">
                                <legend class="text-left">Form SPD dengan UM</legend>
                                <div class="row" id="div_spd">
                                    <div class="form-group">
                                        <label for="if_approve_spd_um">Jika Disetujui</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_approve_spd_um_hidden"
                                                   id="if_approve_spd_um_hidden"
                                                   placeholder="Masukkan role ketika Approve" required="required">
                                            <select class="form-control select2" id="if_approve_spd_um"
                                                    name="if_approve_spd_um" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Disetujui">
                                                <?php
                                                echo "<option value='0' > Silahkan Pilih Approval </option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                echo "<option value='99' > Finish </option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="transport_booked">Validasi</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="hidden" name="v_transport_spd_um" value="0">
                                                        <input type="checkbox" name="v_transport_spd_um"
                                                               id="v_transport_spd_um" value="1">
                                                        &nbsp;<small>Sudah dibelikan Tiket Transport & Penginapan
                                                        </small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="if_decline_spd_um">Jika Ditolak/Revisi</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_decline_spd_um_hidden"
                                                   id="if_decline_spd_um_hidden"
                                                   placeholder="Masukkan role ketika Decline">
                                            <select class="form-control select2" id="if_decline_spd_um"
                                                    name="if_decline_spd_um" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Ditolak">
                                                <?php
                                                echo "<option value='0' > Silahkan Pilih Approval </option>";
                                                echo "<option value='0' > User</option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="fieldset-success">
                                <legend class="text-left">Form Declaration</legend>
                                <div class="row" id="div_dec">
                                    <div class="form-group">
                                        <label for="if_approve_dec">Jika Disetujui</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_approve_dec_hidden"
                                                   id="if_approve_dec_hidden" placeholder="Masukkan role ketika Approve"
                                                   required="required">
                                            <select class="form-control select2" id="if_approve_dec"
                                                    name="if_approve_dec" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Disetujui">
                                                <?php
                                                echo "<option value='0' > Silahkan Pilih Approval </option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                echo "<option value='99' > Finish </option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="if_decline_dec">Jika Ditolak/Revisi</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_decline_dec_hidden"
                                                   id="if_decline_dec_hidden"
                                                   placeholder="Masukkan role ketika Decline">
                                            <select class="form-control select2" id="if_decline_dec"
                                                    name="if_decline_dec" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Ditolak">
                                                <?php
                                                echo "<option value='0'> Silahkan Pilih Approval </option>";
                                                echo "<option value='0'> User</option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="fieldset-success">
                                <legend class="text-left">Form Cancel</legend>
                                <div class="row" id="divheader">
                                    <div class="form-group">
                                        <label for="if_approve_cancel">Jika Disetujui</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_approve_cancel_hidden"
                                                   id="if_approve_cancel_hidden"
                                                   placeholder="Masukkan role ketika Approve">
                                            <select class="form-control select2" id="if_approve_cancel"
                                                    name="if_approve_cancel" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Disetujui">
                                                <?php
                                                echo "<option value='0' > Silahkan Pilih Approval </option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                echo "<option value='99' > Finish </option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="if_decline_cancel">Jika Ditolak/Revisi</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_decline_cancel_hidden"
                                                   id="if_decline_cancel_hidden"
                                                   placeholder="Masukkan role ketika Decline">
                                            <select class="form-control select2" id="if_decline_cancel"
                                                    name="if_decline_cancel" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Ditolak">
                                                <?php
                                                echo "<option value='0'> Silahkan Pilih Approval </option>";
                                                echo "<option value='0'> User</option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="fieldset-success">
                                <legend class="text-left">Form Perubahan Data Pengajuan</legend>
                                <div class="row" id="divheader">
                                    <div class="form-group">
                                        <label for="if_approve_cancel">Jika Disetujui</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_approve_modify_hidden"
                                                   id="if_approve_modify_hidden">
                                            <select class="form-control select2" id="if_approve_modify"
                                                    name="if_approve_modify" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Disetujui">
                                                <?php
                                                echo "<option value='0' > Silahkan Pilih Approval </option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                echo "<option value='99' > Finish </option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="if_decline_cancel">Jika Ditolak/Revisi</label>
                                        <div>
                                            <input type="hidden" class="form-control" name="if_decline_modify_hidden"
                                                   id="if_decline_modify_hidden">
                                            <select class="form-control select2" id="if_decline_modify"
                                                    name="if_decline_modify" style="width: 100%;"
                                                    data-placeholder="Pilih Role Jika Ditolak">
                                                <?php
                                                echo "<option value='0'> Silahkan Pilih Approval </option>";
                                                echo "<option value='0'> User</option>";
                                                foreach ($role as $dt) {
                                                    echo "<option value='" . $dt->level . "' >" . $dt->role . " </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <!-- <div class="form-group ">
                                <label>
                                    <input class=" pull-left" type="checkbox" name="multiple_plan" id="multiple_plan" value="1">
                                    &nbsp; Multiple Pabrik
                                </label>
                            </div>
                            <div class="form-group " style="display: none">
                                <label>
                                    <input class=" pull-left" type="checkbox" name="akses_delete" id="akses_delete" value="1">
                                    &nbsp; Akses Delete
                                </label>
                            </div>
                            <div class="form-group ">
                                <label>
                                    <input class=" pull-left" type="checkbox" name="isresponder" id="isresponder" value="1">
                                    &nbsp; Role sebagai responder
                                </label>
                            </div> -->

                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_role"/>
                            <button type="button" class="btn btn-sm btn-success" name="action_btn" value="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!--modal add_modal_detail-->

        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/travel/master/mrole.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>


