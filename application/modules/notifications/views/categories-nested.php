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
                        <?php
                        echo $trees;
                        ?>
                    </div>
                    <div class="box-footer">
                        <form class="form-notification-category-tree">
                            <input type="hidden" id="result" name="result"/>
                            <button class="btn btn-success" id="btn-simpan-tree">Simpan</button>
                        </form>
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
<style type="text/css">
    .placeholder {
        outline: 1px dashed #4183C4;
        margin: 4px;
    }

    ol.sortable,ol.sortable ol {
        list-style-type: none;
    }

    ol {
        padding-left: 25px;
    }

    .sortable li div {
        border: 1px solid #d4d4d4;
        margin: 4px;
        padding: 10px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        cursor: move;
    }

</style>
<!--<link rel="stylesheet" href="--><?php //echo base_url() ?><!--assets/plugins/jQueryUI/themes/smoothness/jquery-ui.css"/>-->
<script src="<?php echo base_url() ?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/nestedsortable/jquery.mjs.nestedSortable.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/notifications/categories.js"></script>
<script src="<?php echo base_url() ?>assets/apps/js/notifications/categories.nested.js"></script>
