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
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table my-datatable-extends-order table-bordered" id="menus-table"
                               data-page-length="10"
                               style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th width="20%">Jenis Perjanjian</th>
                                <th width="20%">Kualifikasi Perjanjian</th>
                                <th width="5%">Aktif</th>
                                <th data-orderable="false" data-searchable="false" width="5%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list as $dt) {
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square text-success'></i>" : "<i class='fa fa-minus-square text-danger'></i>";
                                echo "<tr>";
                                echo "<td>" . $dt->jenis_spk. "</td>";
                                echo "<td>" . $dt->kualifikasi_spk . "</td>";
                                echo "<td class='text-center'>" . $na . "</td>";
                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
				                            <ul class='dropdown-menu pull-right'>";
                                echo "<li><a href='#' class='edit' data-edit='" . $dt->id_kualifikasi_spk . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
                                if ($dt->na == 'n') {
                                    echo "
                                        <li><a href='#' class='activate' data-active='" . $dt->id_kualifikasi_spk . "' data-action='deactivate'><i class='fa fa-minus text-danger'></i> Non Active</a></li>
                                        <li><a href='#' class='delete' data-delete='" . $dt->id_kualifikasi_spk . "'><i class='fa fa-trash-o'></i> Hapus</a></li>
                                        ";
                                } else {
                                    echo "<li><a href='#' class='activate' data-active='" . $dt->id_kualifikasi_spk . "' data-action='activate'><i class='fa fa-check text-success'></i> Set Active</a></li>";
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
                                    Form <?php echo(isset($title_form) ? $title_form : $title); ?>
                                </strong>
                            </a>
                        </li>
                    </ul>
                    <form role="form" class="form-bak-masal" enctype="multipart/form-data">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-edit">
                                <div class="col-sm-12" style="margin-top: 20px;">
                                    <button type="button" class="btn btn-sm btn-default pull-right hidden btn-new">
                                        Buat <?php echo(isset($title_form) ? $title_form : $title); ?> Baru
                                    </button>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="tanggal_bak">Jenis Perjanjian</label>
                                        <div>
                                            <select class="form-control select2" name="id_jenis_spk"
                                                    id="id_jenis_spk"
                                                    data-placeholder="Pilih Jenis Perjanjian" required="required">
                                                <?php foreach ($jenis_spk as $data): ?>
                                                    <option value="<?php echo $data->id_jenis_spk ?>"><?php echo $data->jenis_spk ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_bak">Kualifikasi Perjanjian</label>
                                        <div>
                                            <input type="text" class="form-control" name="kualifikasi_spk"
                                                   id="kualifikasi_spk"
                                                   placeholder="Masukkkan Kualifikasi Perjanjian" required="required">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_kualifikasi_spk">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/spk/master_kualifikasi_spk.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>