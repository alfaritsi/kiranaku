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
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title"><strong>Data Random Check</strong></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form name="filter-data-spk" method="post">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Pabrik: </label>
                                        <select class="form-control select2" multiple="multiple" id="filter_plant" name="filter_plant[]" data-placeholder="Pilih Pabrik">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Periode Tanggal Pengecekan: </label>
                                        <div class="input-group input-daterange">
                                            <input type="text" name="filter_tanggal_perjanjian_awal" class="form-control" autocomplete="off" onkeypress="return false;">
                                            <label class="input-group-addon" for="tanggal-awal">-</label>
                                            <input type="text" name="filter_tanggal_perjanjian_akhir" class="form-control" autocomplete="off" onkeypress="return false;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-filter -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover table-striped" id="table-data" data-ordering="true" data-scrollx="true" data-bautowidth="true" data-pagelength="10">
                            <thead>
                                <tr>
                                    <th>Pabrik</th>
                                    <th>Tanggal</th>
                                    <th>No. Produksi</th>
                                    <th>No. SI</th>
                                    <th>Tahun Produksi</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ABL1</td>
                                    <td>22.01.2022</td>
                                    <td>7531</td>
                                    <td>*98057*</td>
                                    <td>2022</td>
                                    <td>
                                        <div class="btn-group"> <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="fa fa-caret-down"></span></button>
                                            <ul class="dropdown-menu pull-right">
                                                <li><a href="#" class="activate" data-active="dDBTZ3JzMlBjQUg4OExWTXNMRnl5UT09" data-action="deactivate"><i class="fa fa-zoom"></i> Detail</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DWJ1</td>
                                    <td>22.01.2022</td>
                                    <td>7533</td>
                                    <td>*97257*</td>
                                    <td>2022</td>
                                    <td>
                                        <div class="btn-group"> <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="fa fa-caret-down"></span></button>
                                            <ul class="dropdown-menu pull-right">
                                                <li><a href="#" class="activate" data-active="dDBTZ3JzMlBjQUg4OExWTXNMRnl5UT09" data-action="deactivate"><i class="fa fa-zoom"></i> Detail</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>DWJ1</td>
                                    <td>21.01.2022</td>
                                    <td>7423</td>
                                    <td>*96557*</td>
                                    <td>2022</td>
                                    <td>
                                        <div class="btn-group"> <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="fa fa-caret-down"></span></button>
                                            <ul class="dropdown-menu pull-right">
                                                <li><a href="#" class="activate" data-active="dDBTZ3JzMlBjQUg4OExWTXNMRnl5UT09" data-action="deactivate"><i class="fa fa-zoom"></i> Detail</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('footer') ?>
<script>
    $(document).ready(function() {
        $('#table-data').DataTable({
            "order": [],
        });
    });
</script>
<!-- <script src="<?php echo base_url() ?>assets/apps/js/outspec/setting/user.js?<?php echo time(); ?>"></script> -->