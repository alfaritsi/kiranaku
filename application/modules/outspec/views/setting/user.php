<!--
/*
@application  : Outspec Confirmation
@author       : Benazi S. Bahari (10183)
@contributor  : 
      1. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>         
      2. <insert your fullname> (<insert your nik>) <insert the date>
         <insert what you have modified>
      etc.
*/
-->

<?php $this->load->view('header') ?>
<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables/buttons.dataTables.min.css">
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-sm-8">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Setting User</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover table-striped" id="sspTable" data-ordering="true" data-scrollx="false" data-bautowidth="true" data-pagelength="10">
                            <thead>
                                <th>User</th>
                                <th>Pabrik</th>
                                <th>Action</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title title-form">Form Tambah User</h3>
                        <button type="button" class="btn btn-sm btn-default pull-right hidden" id="btn-new">Tambah User Baru</button>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="form-setting" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="user">User</label>
                                <select class="form-control select2" name="nik" id="nik" required="required">
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pabrik</label>
                                <input type="text" class="form-control" name="plant" id="plant" readonly required>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input id="id_user" name="id_user" type="hidden">
                            <button type="button" name="action_btn" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script src="<?php echo base_url() ?>assets/apps/js/outspec/setting/user.js?<?php echo time(); ?>"></script>