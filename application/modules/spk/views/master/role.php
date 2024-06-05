<?php
$this->load->view('header')
?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-8">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>List <?php echo $title; ?></strong></h3>
                        <div class="btn-group btn-group-sm pull-right">
                            <button class="btn btn-sm btn-default btn-new">
                                <i class="fa fa-plus"></i> Buat Master Role Baru
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-5 col-sm-12">
                                <div class="form-group">
                                    <label>Jenis Perjanjian: </label>
                                    <select class="form-control select2" id="jenis_spk_filter" name="jenis_spk_filter" style="width: 100%;" data-placeholder="Pilih Jenis Perjanjian">
                                        <?php
                                        echo "<option value='0'>Pilih Jenis Perjanjian</option>";
                                        foreach ($jenis_spk as $dt) {
                                            echo "<option value='$dt->id_jenis_spk'>$dt->jenis_spk</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <th>Level</th>
                                    <th>Detail</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Form <?php echo (isset($title_form) ? $title_form : $title); ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <form role="form" id="form-master" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Nama Role</label>
                                <div>
                                    <input type="text" class="form-control" name="nama_role" id="nama_role" placeholder="Masukkkan Nama Role" required="required">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Level</label>
                                <div>
                                    <input type="number" class="form-control" name="level" id="level" placeholder="" required="required" min="1" oninput="validity.valid||(value='');">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tipe_user">Tipe User</label>
                                <select class="form-control select2" name="tipe_user" id="tipe_user" required="required" data-allowClear="true">
                                    <?php
                                    echo "<option value=''></option>";
                                    echo "<option value='nik'>NIK</option>";
                                    echo "<option value='posisi'>Posisi</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="is_akses_buat" name="is_akses_buat"> Akses Buat Perjanjian
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="is_akses_hapus" name="is_akses_hapus"> Akses Cancel Perjanjian
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="is_ho" name="is_ho"> HO
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="is_paralel" name="is_paralel"> Approve Paralel
                                </label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="tipe_user">Jenis Perjanjian</label>
                                <select class="form-control select2" name="id_jenis_spk" id="id_jenis_spk" data-allowClear="true">
                                    <?php
                                    echo "<option value=''>Pilih Jenis Perjanjian</option>";
                                    foreach ($jenis_spk as $dt) {
                                        echo "<option value='$dt->id_jenis_spk'>$dt->jenis_spk</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tipe_user">Jika Approve Lanjut Ke:</label>
                                <select class="form-control select2" name="if_approve" id="if_approve" data-allowClear="true">
                                    <?php
                                    echo "<option value=''></option>";
                                    foreach ($role as $dt) {
                                        echo "<option value='$dt->level'>$dt->nama_role</option>";
                                    }
                                    echo "<option value='confirmed'>Confirmed (Final Draft)</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tipe_user">Jika Decline Kembali Ke:</label>
                                <select class="form-control select2" name="if_decline" id="if_decline" data-allowClear="true">
                                    <?php
                                    echo "<option value=''></option>";
                                    foreach ($role as $dt) {
                                        echo "<option value='$dt->level'>$dt->nama_role</option>";
                                    }
                                    echo "<option value='owner'>Pembuat Perjanjian</option>";
                                    ?>
                                </select>
                            </div>
                            <div class="form-group input-paralel hidden">
                                <label> Divisi Terkait: </label>
                                <select class="form-control select2" multiple="multiple" id="divisi_terkait" name="divisi_terkait[]" style="width: 100%;" data-placeholder="Pilih Divisi">
                                    <?php
                                    foreach ($divisi as $dt) {
                                        echo "<option value='" . $dt->id_divisi . "'>" . $dt->nama . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="id_role">
                        </form>
                    </div>
                    <div class="box-footer">
                        <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spk/master_role.js?<?php echo time(); ?>"></script>