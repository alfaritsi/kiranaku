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
                        <table class="table my-datatable-extends-order table-bordered" id="menus-table" data-page-length="10"
                               style="font-size: 12px;">
                            <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Jadwal Cutoff</th>
                                <th>Catatan</th>
                                <th data-orderable="false" data-searchable="false"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($list_cutoff as $dt) {

                                $enId = $this->generate->kirana_encrypt($dt->id_fbk_cutoff);
                                echo "<tr>";
                                echo "<td>" . $dt->tahun. "</td>";
                                echo "<td nowrap>" . date('d.m.Y H:i:s',strtotime($dt->jadwal)). "</td>";
                                echo "<td>" . nl2br($dt->catatan). "</td>";
                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
				                            <ul class='dropdown-menu pull-right'>";
                                if ($dt->na == 'n') {
                                    echo "
                <li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>
                <li><a href='#' class='delete' data-delete='" . $enId . "'><i class='fa fa-trash-o'></i> Hapus</a></li>
                  ";
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
                    <form role="form" class="form-medical-cutoff" enctype="multipart/form-data">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-edit">
                                <div class="col-sm-12" style="margin-top: 20px;">
                                    <button type="button" class="btn btn-sm btn-default pull-right hidden btn-new">
                                        Buat <?php echo(isset($title_form) ? $title_form : $title); ?> Baru
                                    </button>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="tahun">Tahun</label>
                                        <div>
                                            <input type="text" class="form-control" name="tahun"
                                                   id="tahun"
                                                   placeholder="Pilih Tahun" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_cutoff">Tanggal Cutoff</label>
                                        <div>
                                            <input type="text" class="form-control" name="tanggal_cutoff"
                                                   id="tanggal_cutoff" data-js="datepicker"
                                                   placeholder="Pilih Tanggal" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="jam_cutoff">Jam Cutoff</label>
                                        <div>
                                            <input type="text" class="form-control" name="jam_cutoff"
                                                   id="jam_cutoff"
                                                   placeholder="Pilih Jam" required="required">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="catatan">Catatan</label>
                                        <div>
                                            <textarea class="form-control" name="catatan" id="catatan"
                                                      placeholder="Masukkkan catatan"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_fbk_cutoff">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.css"/>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/apps/css/ess/ess-global.css"/>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/moment/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/ess/medical_cutoff.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
    .datepicker>div{
        display:inherit;
    }
</style>

