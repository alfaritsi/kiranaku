<?php
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
                            <th>App Name</th>
                            <th>Alias/Code</th>
                            <th>URL</th>
                            <th>Label</th>
                            <th>Priority</th>
                            <th>Aktif</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($datas as $dt) {
                                $enId = $this->generate->kirana_encrypt($dt->notification_app_id);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                $label = "<span class='label label-default' style='background-color: ".$dt->label_background_color."; color: ".$dt->label_text_color."'>
                                    ".$dt->label_name."
                                    </span>";
                                echo "<tr>";
                                echo "<td>" . $dt->app_name . "</td>";
                                echo "<td>" . $dt->alias_code . "</td>";
                                echo "<td>" . $dt->url . "</td>";
                                echo "<td>" . $label . "</td>";
                                echo "<td>" . $dt->priority . "</td>";
                                echo "<td>" . $na . "</td>";

                                echo "<td>
				                          <div class='input-group-btn'>
				                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Action <span class='fa fa-caret-down'></span></button>
				                            <ul class='dropdown-menu pull-right'>";


                                if ($dt->na == 'n') {
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
                    <form role="form" class="form-notification-app" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="app_name">Nama App</label>
                                <input type="text" class="form-control" name="app_name" id="app_name"
                                       placeholder="Masukkkan Nama App" required="required">
                            </div>
                            <div class="form-group">
                                <label for="alias_code">Code / Alias</label>
                                <input type="text" class="form-control" name="alias_code" id="alias_code"
                                       placeholder="Masukkkan Alias / Code" required="required">
                            </div>
                            <div class="form-group">
                                <label for="app_icon">Class Icon</label>
                                <input type="text" class="form-control" name="app_icon" id="app_icon"
                                       placeholder="Masukkkan Class icon">
                                <small class="help-block">contoh : fa fa-user</small>
                            </div>
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" class="form-control" name="url" id="url"
                                       placeholder="Masukkkan URL">
                            </div>
                            <div class="form-group">
                                <label for="label_name">Label Name</label>
                                <input type="text" class="form-control" name="label_name" id="label_name"
                                       placeholder="Masukkkan Label" required="required">
                            </div>
                            <div class="form-group">
                                <label for="label_background_color">Label BG Color</label>
                                <input type="text" class="form-control colorpicker"  name="label_background_color" id="label_background_color"
                                       placeholder="Masukkkan BG Color" required="required" value="#d2d6de">
                            </div>
                            <div class="form-group">
                                <label for="label_text_color">Label Text Color</label>
                                <input type="text" class="form-control colorpicker" name="label_text_color" id="label_text_color"
                                       placeholder="Masukkkan Text Color" required="required" value="#333333">
                            </div>
                            <div class="form-group">
                                <label for="label_text_color">Label Preview</label>
                                <div class="">
                                    <span id="preview-label" class='label label-default'>
                                    Label Preview
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <input type="number" class="form-control" name="priority" id="priority"
                                       placeholder="Masukkkan Priority" required="required">
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="notification_app_id" id="notification_app_id">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/colorpicker/css/bootstrap-colorpicker.min.css"/>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/notifications/apps.js"></script>