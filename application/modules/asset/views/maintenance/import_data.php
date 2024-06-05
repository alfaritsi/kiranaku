<?php $this->load->view('header') ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <form id="import_excel" method="post" enctype="multipart/form-data">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title"><strong><?php echo $title ?></strong></h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <p>Download file template excel untuk import data PM.</p>
                                    <a class="btn btn-sm btn-primary" href="<?php echo site_url('assets/apps/templates/excels/pm_import_template.xls')?>">Download Template</a>
                                </div>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> Kategori: </label>
                                                <select class="form-control select2" id="kategori" style="width: 100%;" data-placeholder="Pilih Kategori">
                                                    <option></option>
                                                    <?php
                                                    foreach($kategori as $dt){
                                                        echo "<option value='".$dt->id_kategori."'";
                                                        echo ">".$dt->nama."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> Sub Kategori Asset: </label>
                                                <select class="form-control select2" id="id_jenis" name="id_jenis" style="width: 100%;" data-placeholder="Pilih Jenis">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label> Periode: </label>
                                                <select class="form-control select2" id="id_periode" name="id_periode" data-placeholder="Pilih Periode">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label> File excel hasil PM: </label>
                                                <input type="file" class="form-control" name="import_file" id="import_file">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn-success" name="import_btn" value="import"><i class="fa fa-cloud-upload"></i>&nbsp;Import</button>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="import_validate" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_periode" id="id_periode_validate">
                    <input type="hidden" name="id_jenis" id="id_jenis_validate">
                    <div id="box-validasi-import" class="box box-success fade">
                        <div class="box-header">
                            <h3 class="box-title"><strong>Validasi Import</strong></h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- /.box-filter -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Detail Periode </label>
                                        <p class="form-control-static" id="label_detail_periode"></p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Jadwal: </label>
                                        <input type="text" class="form-control" name="jadwal_service" id="jadwal_service"
                                               placeholder="Pilih tanggal awal jadwal PM" required/>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <table id="table-validasi-tasks" class="table table-bordered table-striped table-responsive">
                                        <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Komponen</th>
                                            <th>Kegiatan</th>
                                            <th>Keterangan</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr/>
                            <table class="table table-bordered table-striped dataTable"
                                   id="table-validasi-import">
                                <thead>
                                <tr>
                                    <th data-orderable="false"></th>
                                    <th>Detail Aset</th>
                                    <th>NIK User</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>NIK Operator</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="box-footer">
                            <div class="text-center">
                                <button type="submit" class="btn btn-success" name="action_btn">Valid</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script>
    var pengguna = '<?php echo $pengguna?>';
</script>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/all.css"/>
<script src="<?php echo base_url() ?>assets/apps/js/asset/transaksi/import_data.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datatables/plugins/checkboxes/dataTables.checkboxes.min.js"></script>

<style>
    .small-box .icon{
        top: -13px;
    }
    table.dataTable thead > tr > th.sorting_disabled {
        padding-right: 8px;
    }
</style>