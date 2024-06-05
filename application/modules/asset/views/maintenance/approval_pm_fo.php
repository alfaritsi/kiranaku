<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
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
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> Sub Kategori Asset: </label>
                                    <select class="form-control select2" multiple="multiple" id="jenis" name="jenis[]"
                                            style="width: 100%;" data-placeholder="Pilih Jenis">
                                        <?php
                                        foreach ($jenis as $dt) {
                                            echo "<option value='" . $dt->id_jenis . "'";
                                            echo ">" . $dt->nama . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> Merk Asset: </label>
                                    <select class="form-control select2" multiple="multiple" id="merk" name="merk[]"
                                            style="width: 100%;" data-placeholder="Pilih Merk">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> Pabrik: </label>
                                    <select class="form-control select2" multiple="multiple" id="pabrik" name="pabrik[]"
                                            style="width: 100%;" data-placeholder="Pilih Pabrik">
                                        <?php
                                        foreach ($pabrik as $dt) {
                                            echo "<option value='" . $dt->id_pabrik . "'";
                                            echo ">" . $dt->nama . " - ".$dt->kode."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> Lokasi: </label>
                                    <select class="form-control select2" multiple="multiple" id="lokasi" name="lokasi[]"
                                            style="width: 100%;" data-placeholder="Pilih Lokasi">
                                        <?php
                                        foreach ($lokasi as $dt) {
                                            echo "<option value='" . $dt->id_lokasi . "'";
                                            echo ">" . $dt->nama . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> Area: </label>
                                    <select class="form-control select2" multiple="multiple" id="area" name="area[]"
                                            style="width: 100%;" data-placeholder="Pilih Area"></select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label> Status: </label>
                                    <select class="form-control select2" id="main_status" multiple name="main_status"
                                            style="width: 100%;" data-placeholder="Pilih Status">
                                        <option value="noschedule">Not Scheduled</option>
                                        <option value="scheduled" selected>Scheduled</option>
                                        <option value="onprogress">On Progress</option>
                                        <option value="confirmpic" selected>Waiting PIC Confirmation</option>
                                        <option value="complete" selected>Complete</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Jadwal</label>
                                    <div class="input-group">
										<input type="text" id="tanggal_awal_filter" name="tanggal_awal" value="<?php echo $tanggal_awal; ?>" class="form-control kiranadatepicker" autocomplete="off" data-autoclose="true">
										<div class="input-group-addon"> - </div>
										<input type="text" id="tanggal_akhir_filter" name="tanggal_akhir" value="<?php echo $tanggal_akhir; ?>" class="form-control kiranadatepicker" autocomplete="off" data-autoclose="true" data-minDate="#tanggal_awal_filter">
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-sm-12" style="padding-top: 5px;">
                                            <label for="outstanding">
                                                <input id="outstanding" type="checkbox">&nbsp;Outstanding
                                            </label>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-filter -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped dataTable"
                               id="sspTable">
                            <thead>
                            <tr>
                                <th data-orderable="false"></th>
                                <th>Detail Aset</th>
                                <th>Pabrik</th>
                                <th>Sub Lokasi</th>
                                <th>Area</th>
                                <th>PIC Asset</th>
                                <th>Jadwal</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Jenis Maintenance</th>
                                <th>Operator</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="box-footer">
                        <div class="text-center">
                            <?php
								if (isset($akses)) {
									if ($akses->role == 'PIC ALAT BERAT' || $akses->role == 'PIC APAR')
										echo '<button type="submit" class="btn btn-success" name="action_btn">Approve</button>';
								}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('maintenance/includes/_modal_pm_history') ?>
<?php $this->load->view('maintenance/includes/_modal_pm_detail') ?>


<?php $this->load->view('footer') ?>
<script>
    var pengguna = '<?php echo $pengguna?>';
</script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/all.css"/>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/approval_pm_fo.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>

<style>
    .small-box .icon{
        top: -13px;
    }
</style>