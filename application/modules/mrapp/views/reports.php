<?php
/**
 * @application  : View Management Reporting App Settings (Reports)
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
                        <h3 class="box-title"><strong>Manage <?php echo $title; ?></strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered my-datatable-extends-order">
                            <thead>
                            <th>Kode Report</th>
                            <th>Nama Report</th>
                            <th>Schedule</th>
                            <th>Schedule Start Date</th>
                            <th>Last Sent</th>
                            <th>Aktif</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($datas as $dt) {
                                $enId = $this->generate->kirana_encrypt($dt->id_report);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                $schedule = "Tidak aktif";
                                $schedule_start = "";
                                if ($dt->scheduled) {
                                    if ($dt->schedule_period == "xmonthly")
                                        $schedule = "ONCE PER $dt->schedule_period_counter MONTH(S)";
                                    else
                                        $schedule = strtoupper($dt->schedule_period);

                                    $schedule_start = $dt->schedule_start;
                                }
                                echo "<tr>";
                                echo "<td>" . $dt->kode_report . "</td>";
                                echo "<td>" . $dt->nama_report . "</td>";
                                echo "<td>" . $schedule . "</td>";
                                echo "<td>" . $schedule_start . "</td>";
                                echo "<td>" . $dt->schedule_last_sent . "</td>";
                                echo "<td>" . $na . "</td>";

                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";


                                if ($dt->na == 'n') {
                                    echo "<li>" .
                                        "<form method='post' action='" . base_url('mrapp/reports/parameters') . "' id='param-$enId' class='param-form'>" .
                                        "<input type='hidden' name='id_report' value='$enId' />" .
                                        "</form>" .
                                        "<a href='#' class='parameter' data-id='" . $enId . "'><i class='fa fa-gear'></i> Parameters</a>" .
                                        "</li>";
                                    echo "<li>" .
                                        "<form method='post' action='" . base_url('mrapp/reports/links') . "' id='link-$enId' class='link-form'>" .
                                        "<input type='hidden' name='id_report' value='$enId' />" .
                                        "</form>" .
                                        "<a href='#' class='link' data-id='" . $enId . "'><i class='fa fa-link'></i> Links</a>" .
                                        "</li>";
                                    echo "<li>" .
                                        "<form method='post' action='" . base_url('mrapp/reports/subscribers') . "' id='subscriber-$enId' class='subscriber-form'>" .
                                        "<input type='hidden' name='id_report' value='$enId' />" .
                                        "</form>" .
                                        "<a href='#' class='subscriber' data-id='" . $enId . "'><i class='fa fa-users'></i> Subscribers</a>" .
                                        "</li>";
                                    echo "<li>" .
                                        "<form method='post' action='" . base_url('mrapp/reports/thresholds') . "' id='threshold-$enId' class='threshold-form'>" .
                                        "<input type='hidden' name='id_report' value='$enId' />" .
                                        "</form>" .
                                        "<a href='#' class='threshold' data-id='" . $enId . "'><i class='fa fa-balance-scale'></i> Thresholds</a>" .
                                        "</li>";
                                    echo "<li><a href='#' class='edit' data-edit='" . $enId . "'><i class='fa fa-pencil-square-o'></i> Edit</a></li>";
                                    echo "<li><a href='#' class='delete' data-delete='" . $enId . "'><i class='fa fa-trash-o'></i> Hapus</a></li>";
                                    echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='delete_na'><i class='fa fa-times'></i> Deactivate</a></li>";
                                } else {
                                    echo "<li><a href='#' class='set_active' data-id='" . $enId . "' data-action='activate_na'><i class='fa fa-check'></i> Activate</a></li>";
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
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title title-form">
                            Buat <?php echo(isset($title_form) ? $title_form : $title); ?></h3>
                        <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">
                            Buat <?php echo(isset($title_form) ? $title_form : $title); ?> Baru
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" class="form-mrapp-reports" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="kode_report">Kode Report</label>
                                <input type="text" class="form-control" name="kode_report" id="kode_report"
                                       placeholder="Masukkkan Kode" required="required">
                            </div>
                            <div class="form-group">
                                <label for="nama_report">Nama Report</label>
                                <input type="text" class="form-control" name="nama_report" id="nama_report"
                                       placeholder="Masukkkan Nama Report" required="required">
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" id="deskripsi"
                                          placeholder="Masukkkan Deksripsi Report"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="id_report_type">Tipe Report</label>
                                <select required class="form-control select2" id="id_report_type" name="id_report_type">
                                    <?php
                                    foreach ($types as $type) {
                                        echo '<option value="' . $type->id_report_type . '">' . $type->nama_type . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="report_function">Report Function</label>
                                <select required class="form-control select2" id="report_function"
                                        name="report_function">
                                    <?php
                                    foreach ($functions as $key => $value) {
                                        echo '<option value="' . $key . '">' . $value . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="scheduled">Alert Schedule</label>
                                <input type="hidden" name="scheduled" value="0">
                                <div class="checkbox icheck">
                                    <input type="checkbox" id="scheduled" name="scheduled" value="1" min="1"> Aktifkan
                                    schedule
                                </div>
                            </div>
                            <div id="div_schedule">

                                <div class="form-group">
                                    <label for="schedule_period">Periode</label>
                                    <select class="form-control select2" id="schedule_period" name="schedule_period">
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="biweekly">Bi-weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="xmonthly">x Monthly</option>
                                    </select>
                                </div>
                                <div class="form-group" id="div_schedule_periode_counter">
                                    <label for="nama_report">Jumlah Bulan</label>
                                    <input type="number" class="form-control" name="schedule_period_counter"
                                           id="schedule_period_counter"
                                           placeholder="Masukkkan Jumlah periode" required="required" value="1">
                                </div>
                                <div class="form-group">
                                    <label for="schedule_start">Starting Date</label>
                                    <div class="input-group date">
                                        <input type="text" class="form-control datepicker" id="schedule_start"
                                               name="schedule_start" required="required">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="schedule_last_sent">Tanggal Terakhir dikirim</label>
                                    <input type="text" class="form-control" id="schedule_last_sent"
                                           placeholder="belum terkirim" readonly="readonly">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_report" id="id_report">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datepicker/datepicker3.min.css">
<style type="text/css">
    .datepicker {
        border-radius: 0;
    }
</style>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/square/green.css"/>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/mrapp/reports.js"></script>
<style>
    .small-box .icon {
        top: -13px;
    }
</style>