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
                            <th>Category Name</th>
                            <th>Alias / Code</th>
                            <th>Notification Format</th>
                            <th>Notification URL</th>
                            <th>Priority</th>
                            <th>Aktif</th>
                            <th>Action</th>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($datas as $dt) {
                                $enId = $this->generate->kirana_encrypt($dt->notification_category_id);
                                $na = ($dt->na == 'n') ? "<i class='fa fa-check-square'></i>" : "<i class='fa fa-minus-square'></i>";
                                echo "<tr>";
                                echo "<td>" . $dt->app_name . "</td>";
                                echo "<td>" . $dt->category_name . "</td>";
                                echo "<td>" . $dt->alias_code . "</td>";
                                echo "<td>" . $dt->notification_format . "</td>";
                                echo "<td>" . $dt->notification_url . "</td>";
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
                    <form role="form" class="form-notification-category" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="app_id">Notification App</label>
                                <select class="select2" name="app_id" id="app_id">
                                    <?php
                                    foreach ($apps as $app)
                                    {
                                        echo "<option value='$app->notification_app_id'>$app->app_name</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <input type="text" class="form-control" name="category_name" id="category_name"
                                       placeholder="Masukkkan category name" required="required">
                            </div>
                            <div class="form-group">
                                <label for="alias_code">Code / Alias</label>
                                <input type="text" class="form-control" name="alias_code" id="alias_code"
                                       placeholder="Masukkkan Alias / Code" required="required">
                            </div>
                            <div class="form-group">
                                <label for="notification_format">Notification Format</label>
                                <input type="text" class="form-control" name="notification_format" id="notification_format"
                                       placeholder="Masukkkan Format Notifikasi" required="required">
                            </div>
                            <div class="form-group">
                                <label for="notification_url">URL</label>
                                <input type="text" class="form-control" name="notification_url" id="notification_url"
                                       placeholder="Masukkkan URL" required="required">
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <input type="number" class="form-control" name="priority" id="priority"
                                       placeholder="Masukkkan Priority" required="required">
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="notification_category_id" id="notification_category_id">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/notifications/categories.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/datatable.js"></script>
