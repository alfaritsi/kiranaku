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
                        <table class="table table-bordered table-striped my-datatable-extends-order table-responsive"
                               id="table-options" style="width: 100%;">
                            <thead>
                            <tr>
                                <th>Personal area</th>
                                <th>Activity</th>
                                <th>Tujuan Trip</th>
                                <th>Expense</th>
                                <th width="20%">Cost Center</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list as $d) : ?>
                                <tr>
                                    <td><?php echo $d->personal_area_text ?></td>
                                    <td>
                                        <?php
                                        foreach (explode('||', $d->activity_text) as $et) {
                                            if(!empty($et))
                                                echo '<span class="label label-default" style="display: inline-block;">' . $et . '</span><br/>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $d->domestik == 1 ? 'Domestik':'Luar Negeri' ?></td>
                                    <td>
                                        <?php
                                        foreach (explode('||', $d->expenses_text) as $et) {
                                            if(!empty($et))
                                                echo '<span class="label label-default" style="display: inline-block;">' . $et . '</span><br/>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach (explode('.', $d->cost_center) as $cc) {
                                            if(!empty($cc))
                                                echo '<span class="label label-default" style="display: inline-block;">' . $cc . '</span>&nbsp;';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class='input-group-btn'>
                                            <button type='button' class='btn btn-default btn-sm dropdown-toggle'
                                                    data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
                                            <ul class='dropdown-menu pull-right'>
                                                <li><a href='#' class='edit'
                                                       data-edit='<?php echo $this->generate->kirana_encrypt($d->id_travel_costcenter_declare); ?>'>
                                                       <i class='fa fa-pencil-square-o'></i> Edit</a>
                                                </li>
                                                <li>
                                                    <a href='#' class='delete'
                                                        data-delete='<?php echo $this->generate->kirana_encrypt($d->id_travel_costcenter_declare); ?>'>
                                                        <i class='fa fa-trash'></i> Delete</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
                                    class="btn btn-sm btn-default hide"
                                    id="btn-new">Buat Baru
                            </button>
                        </div>
                    </div>
                    <form role="form" class="form-costcenter-declare">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="personal_area">
                                    Personal Area
                                </label>
                                <div>
                                    <select class="form-control select2"
                                            id="personal_area" name="personal_area" style="width: 100%;"
                                            data-placeholder="Pilih Personal Area " required="required">
                                        <option value="all">Semua</option>
                                        <?php
                                        foreach ($destinations as $dt) {
                                            echo "<option value='" . $dt->value . "' >[" . $dt->value . "] " . $dt->label . " </option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="activity">Activity</label>
                                <select class="form-control select2"
                                        id="activity" name="activity[]" style="width: 100%;"
                                        data-placeholder="Pilih Activity" required="required"
                                        multiple>
                                    <?php
                                    foreach ($activities as $dt) {
                                        echo "<option value='" . $dt->kode_jns_aktifitas . "' >" . $dt->jenis_aktifitas . " </option>";
                                    }
                                    ?>
                                </select>
                                <div class="btn-group btn-group input-group-btn">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0)" class="select2-all">Pilih Semua</a></li>
                                        <li><a href="javascript:void(0)" class="select2-inverse">Pilih
                                                Sebaliknya</a></li>
                                        <li><a href="javascript:void(0)" class="select2-none">Pilih Kosong</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group no-padding">
                                <label>Tujuan Trip</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>
                                            <input type="radio" value="1" name="domestik" checked />Domestik
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label>
                                            <input type="radio" value="0" name="domestik" />Luar Negeri
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="activity">Expense Type</label>
                                <select class="form-control select2"
                                        id="kode_expense" name="kode_expense[]" style="width: 100%;"
                                        data-placeholder="Pilih Expense Type" required="required"
                                        multiple>
                                    <?php
                                    foreach ($kode_expenses as $dt) {
                                        echo "<option value='" . $dt->kode_expense . "' >[" . $dt->kode_expense . "] " . $dt->tipe_expense_text . " </option>";
                                    }
                                    ?>
                                </select>
                                <div class="btn-group btn-group input-group-btn">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0)" class="select2-all">Pilih Semua</a></li>
                                        <li><a href="javascript:void(0)" class="select2-inverse">Pilih
                                                Sebaliknya</a></li>
                                        <li><a href="javascript:void(0)" class="select2-none">Pilih Kosong</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Hari</label>
                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Min</label>
                                            <input name="day_min" class="form-control" placeholder="min" value="0">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Max</label>
                                            <input name="day_max" class="form-control" placeholder="max" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Total Pengambilan</label>
                                <div class="input-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="control-label">Min</label>
                                            <input name="total_min" class="form-control" placeholder="min" value="0">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Max</label>
                                            <input name="total_max" class="form-control" placeholder="max" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="transport_booked">Otomatisasi</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="hidden" name="auto_total" value="0">
                                                <input type="checkbox" name="auto_total" id="auto_total" value="1">
                                                &nbsp;<small>Total otomatis menggunakan perhitungan hari</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cost_center">Cost Center</label>
                                <select class="form-control select2"
                                        id="cost_center" name="cost_center[]" style="width: 100%;"
                                        data-placeholder="Pilih Cost Center" required="required"
                                        multiple>
                                    <?php
                                    foreach ($costcenter as $dt) {
                                        echo "<option value='" . $dt->cost_center . "' >" . $dt->cost_center . " </option>";
                                    }
                                    ?>
                                </select>
                                <div class="btn-group btn-group input-group-btn">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="javascript:void(0)" class="select2-all">Pilih Semua</a></li>
                                        <li><a href="javascript:void(0)" class="select2-inverse">Pilih
                                                Sebaliknya</a></li>
                                        <li><a href="javascript:void(0)" class="select2-none">Pilih Kosong</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_travel_costcenter_declare"/>
                            <button type="button" class="btn btn-sm btn-success" name="action_btn" value="submit">
                                Submit
                            </button>
                            <button type="reset" class="btn btn-sm btn-warning">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/travel/master/costcenter_declare.js"></script>


