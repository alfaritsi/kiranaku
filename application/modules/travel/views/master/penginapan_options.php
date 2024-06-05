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
                        <table class="table table-bordered table-striped my-datatable-extends-order" id="table-options">
                            <thead>
                            <tr>
                                <th>Tujuan</th>
                                <th>Ketersediaan Mess</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list as $d) : ?>
                                <tr>
                                    <td><?php echo $d->persa_text ?></td>
                                    <td><?php echo $d->available ? 'Ya' : 'Tidak' ?></td>
                                    <td>
                                        <div class='input-group-btn'>
                                            <button type='button' class='btn btn-default btn-sm dropdown-toggle'
                                                    data-toggle='dropdown'><i class='fa fa-th-large'></i></button>
                                            <ul class='dropdown-menu pull-right'>
                                                <li><a href='#' class='edit'
                                                       data-edit='<?php echo $d->id_travel_mess_option; ?>'><i
                                                                class='fa fa-pencil-square-o'></i> Edit</a></li>
                                                <li><a href='#' class='delete'
                                                       data-delete='<?php echo $d->id_travel_mess_option; ?>'><i
                                                                class='fa fa-trash'></i> Delete</a></li>
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
                    <form role="form" class="form-mess-option">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="personal_area">Personal Area</label>
                                <div>
                                    <select class="form-control select2"
                                            id="persa" name="persa" style="width: 100%;"
                                            data-placeholder="Pilih Personal Area " required="required">
                                        <?php
                                        foreach ($destinations as $dt) {
                                            echo "<option value='" . $dt->value . "' >" . $dt->label . " </option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group no-padding">
                                <label>Mess</label>
                                <div class="checkbox">
                                    <input type="hidden" name="available" value="0">
                                    <label><input type="checkbox" name="available" value="1" id="available">Tersedia</label>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="id_travel_transport_options"/>
                            <button type="button" class="btn btn-sm btn-success" name="action_btn" value="submit">
                                Submit
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
<script src="<?php echo base_url() ?>assets/apps/js/travel/master/mess_options.js"></script>


