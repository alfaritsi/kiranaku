<?php
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
                        <form method="post" name="filter-privileges">
                            <div class="row">
                                <div class="col-md-offset-4 col-md-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label class="input-group-addon" for="id_divisi">Filter Divisi</label>
                                            <select class="form-control select2" id="id_divisi" name="id_divisi"
                                                    data-placeholder="Pilih divisi" data-allow-clear="true"
                                            >
                                                <option></option>
                                                <?php foreach ($divisis as $divisi): ?>
                                                    <option value="<?php echo $divisi->id_divisi ?>" <?php if ($id_divisi == $divisi->id_divisi) echo "selected"; ?>>
                                                        <?php echo $divisi->nama ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table my-datatable-extends-order table-bordered" id="menus-table"
                               data-page-length="10"
                               style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th width="10%">NIK</th>
                                <th width="30%">Nama</th>
                                <th width="20%">Hirarki</th>
                                <th width="30%">Divisi</th>
                                <th width="5%">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list as $dt) {
                                $na = ($dt->na == 'n') ? "<span class='badge bg-green'>Active</span>" : "<span class='badge bg-red'>Resigned</span>";
                                echo "<tr>";
                                echo "<td>" . $dt->nik . "</td>";
                                echo "<td>" . $dt->nama . "</td>";
                                echo "<td>" . $dt->level . "</td>";
                                if (isset($dt->nama_divisi))
                                    echo "<td>
                                            <a class='assign' data-assign='" . $dt->id_user . "' href='javascript:void(0)'>
                                                <span class='badge bg-blue'><i class='fa fa-pencil'></i> " . $dt->nama_divisi . "</span>
                                            </a>
                                        </td>";
                                else
                                    echo "<td>
                                            <a class='assign' data-assign='" . $dt->id_user . "' href='javascript:void(0)'>
                                                <span class='badge bg-aqua'><i class='fa fa-plus'></i> Assign Divisi</span>
                                            </a>
                                        </td>";
                                echo "<td class='text-center'>" . $na . "</td>";
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
<div class="modal fade" role="dialog" id="modal-assign">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Assign Divisi</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-privileges" id="form-privileges">
                    <input type="hidden" id="id_user" name="id_user"/>
                    <div class="form-group">
                        <label class="col-md-4">Divisi</label>
                        <div class="col-md-8">
                            <select class="form-control select2" id="leg_level_id" name="leg_level_id"
                                    data-placeholder="Pilih divisi" data-allow-clear="true"
                            >
                                <option></option>
                                <?php foreach ($leg_divisis as $divisi): ?>
                                    <option value="<?php echo $divisi->id_divisi ?>">
                                        <?php echo $divisi->nama_divisi ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-danger">Reset</button>
                <button name="simpan" class="btn btn-success" type="button">Save</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spk/master_privileges.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/spk/spk.global.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
